<?php
require_once dirname(__FILE__, 2) . "/connection.php";
$Authentication = new Authentication($PDO);
$Authentication->keyToPage();
$User = new Users($PDO);
$MyUsers = new MyUsers($PDO);
$staffId = $_SESSION[Authentication::SESSION_NAME]['id'];
$staffInfo = $User->getInfo($staffId);
$Authentication->pageAccessor([MyUsers::STAFF['name']], $staffInfo['user_type']['type_name']);

if (
    isset($_POST[Authentication::SESSION_CSRF_TOKEN])
    && Authentication::checkCSRFToken($_POST[Authentication::SESSION_CSRF_TOKEN])
) {
    //get inputted data
    $errors = [];
    $loanId = filter_var(trim($_POST['loanId']), FILTER_VALIDATE_INT);

    //check for validation
    if (!$loanId) {
        $errors[] = "invalid loan";
    } else {
        $Db = new Database(__FILE__, $PDO, 'loan');
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $loanId]];
        if (!$Db->select(__LINE__, ['id'], $where)) $errors[] = "invalid loan";
    }

    //check the action
    if (!in_array($_POST['action'], ['approve', 'reject', 'process']))
        $errors[] = "invalid action";

    //redirect on error
    $responseURLBACKEND = "Location: .";
    if ($errors) {
        Tag::setResponse(
            'Invalid Data Input',
            $errors,
            Tag::RESPONSE_NEGATIVE
        );
        header($responseURLBACKEND);
        exit;
    }

    if ($_POST['action'] == 'approve') {
        //update status in loan table
        $Db->setTable('loan');
        $column = [
            'status' => ['colValue' => 'approved', 'isFunction' => false, 'isBindAble' => true],
            'staff_profile_id' => ['colValue' => $staffId, 'isFunction' => false, 'isBindAble' => true]
        ];
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $loanId]];
        $Db->update(__LINE__, $column, $where);

        //get loan application info
        $result = $Db->select(__LINE__, [], $where)[0];

        //add to loan transaction table
        $Transaction = new Transactions($PDO);
        $Transaction->post($loanId, $staffId, "Loan booking", $result['amount'], 'debit');

        //send mail out
        //TODO

        $responseTitle = 'Operation Successful';
        $responseMessage = 'A loan has been successfully approved';
        $responseColor = Tag::RESPONSE_POSITIVE;
    }

    if ($_POST['action'] == 'process') {
        //update status in loan table
        $Db->setTable('loan');
        $column = [
            'status' => ['colValue' => 'wip', 'isFunction' => false, 'isBindAble' => true],
            'staff_profile_id' => ['colValue' => $staffId, 'isFunction' => false, 'isBindAble' => true]
        ];
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $loanId]];
        $Db->update(__LINE__, $column, $where);

        //send mail out to inform loan is been worked on
        //TODO

        $responseTitle = 'Operation Successful';
        $responseMessage = 'A loan has been successfully placed under WIP for further action';
        $responseColor = Tag::RESPONSE_POSITIVE;
    }

    if ($_POST['action'] == 'reject') {
        //update status in loan table
        $Db->setTable('loan');
        $column = [
            'status' => ['colValue' => 'rejected', 'isFunction' => false, 'isBindAble' => true],
            'staff_profile_id' => ['colValue' => $staffId, 'isFunction' => false, 'isBindAble' => true]
        ];
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $loanId]];
        $Db->update(__LINE__, $column, $where);

        //send mail out to show loan has been rejected
        //TODO

        $responseTitle = 'Operation Successful';
        $responseMessage = 'A loan has been successfully rejected';
        $responseColor = Tag::RESPONSE_POSITIVE;
    }

    Tag::setResponse($responseTitle, [$responseMessage], $responseColor);

    //redirect on completion
    header($responseURLBACKEND);
    exit();
} else {
    new ErrorLog('Suspected CSRF Attack', __FILE__, __LINE__);
    Tag::setResponse(
        'Expired Session',
        ['Your session has expired, please repeat the process again'],
        Tag::RESPONSE_NEGATIVE
    );
    header("Location: .");
    exit();
}
