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
    $amount = filter_var(trim($_POST['amount']), FILTER_VALIDATE_FLOAT);

    //check for validation
    if (!$amount) $errors[] = "invalid amount";
    if (!$loanId) {
        $errors[] = "invalid loan";
    } else {
        $Db = new Database(__FILE__, $PDO, 'loan');
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $loanId]];
        if (!$Db->select(__LINE__, ['id'], $where)) $errors[] = "invalid loan";
    }

    //redirect on error
    $responseURLBACKEND = "Location: ." . "/?id=$loanId";
    if ($errors) {
        Tag::setResponse(
            'Invalid Data Input',
            $errors,
            Tag::RESPONSE_NEGATIVE
        );
        header($responseURLBACKEND);
        exit;
    }

    //add to loan transaction table
    $Transaction = new Transactions($PDO);
    $Transaction->post($loanId, $staffInfo['profile']['id'], "Loan repayment", $amount, 'credit');

    //check to liquate the loan
    $balance = $Transaction->getBalance($loanId);
    if ($balance >= 0) {
        $Db = new Database(__FILE__, $PDO, 'loan');
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $loanId]];
        $column = ['status' => ['colValue' => 'liquated', 'isFunction' => false, 'isBindAble' => true],];
        $Db->update(__LINE__, $column, $where);
    }

    $responseTitle = 'Operation Successful';
    $responseMessage = 'A loan repayment has been successfully done';
    $responseColor = Tag::RESPONSE_POSITIVE;
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
