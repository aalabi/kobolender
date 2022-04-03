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

    //check for amount
    if ($_POST['action'] == 'approve') {
        $amount = filter_var(trim($_POST['amount']), FILTER_VALIDATE_FLOAT);
        if (!$amount) $errors[] = "invalid amount";
    }

    //check for reason
    if ($_POST['action'] == 'reject') {
        $reason = filter_var(trim($_POST['reason']), FILTER_SANITIZE_STRING);
        if (!$reason) $errors[] = "invalid reason";
    }

    //redirect on error
    $responseUrl = "Location: .";
    if ($errors) {
        Tag::setResponse(
            'Invalid Data Input',
            $errors,
            Tag::RESPONSE_NEGATIVE
        );
        header($responseUrl);
        exit;
    }

    if ($_POST['action'] == 'approve') {
        //update status in loan table
        $Db->setTable('loan');
        $column = [
            'status' => ['colValue' => 'approved', 'isFunction' => false, 'isBindAble' => true],
            'staff_profile_id' => ['colValue' => $staffId, 'isFunction' => false, 'isBindAble' => true],
            'approved_amount' => ['colValue' => $amount, 'isFunction' => false, 'isBindAble' => true]
        ];
        $where = [
            ['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $loanId, 'logic' => 'AND'],
            ['column' => 'status', 'comparsion' => '=', 'bindAbleValue' => 'wip']
        ];
        $Db->update(__LINE__, $column, $where);

        //get loan application info
        unset($where[1]);
        $result = $Db->select(__LINE__, [], $where)[0];

        //add to loan transaction table
        $Transaction = new Transactions($PDO);
        $Transaction->post($loanId, $staffId, "Loan booking", $amount, 'debit');

        //send mail out
        $loanInfo = $Db->select(__LINE__, [], $where)[0];
        $profileInfo = $MyUsers->getProfileInfo($loanInfo['profile_id']);
        $Notification = new Notification();
        $email = $profileInfo[Authentication::TABLE]['email'];
        $content = "
            <p style='margin-bottom:20px;'>Good Day Sir/Madam </p>
            <p style='margin-bottom:8px;'>
                Congratulation we will like to let know that your loan application on " . SITENAME . " 
                has been approved. The loan information is below.</br>
                <strong>Loan Product</strong>: " . Functions::getLoanProducts()[$loanId]['market'] . "<br/>
                <strong>Amount Applied</strong>: " . number_format($loanInfo['amount'], 2) . "<br/>
                <strong>Amount Approved</strong>: " . number_format($loanInfo['approved_amount'], 2) . "<br/>
                <strong>Application Date</strong>: " . date("jS F Y", strtotime($loanInfo['created_at'])) . "<br/>
                <strong>Approval Date</strong>: " . date("jS F Y", strtotime($loanInfo['update_at'])) . "<br/>
            </p>
        ";
        $Notification->sendMail(['to' => [$email]], "Loan Application Status", $content);

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
            'staff_profile_id' => ['colValue' => $staffId, 'isFunction' => false, 'isBindAble' => true],
            'reject_reason' => ['colValue' => $reason, 'isFunction' => false, 'isBindAble' => true]
        ];
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $loanId]];
        $Db->update(__LINE__, $column, $where);

        //send mail out to show loan has been rejected
        $profileId = $Db->select(__LINE__, ['profile_id'], $where)[0]['profile_id'];
        $profileInfo = $MyUsers->getProfileInfo($profileId);
        $Notification = new Notification();
        $email = $profileInfo[Authentication::TABLE]['email'];
        $content = "
            <p style='margin-bottom:20px;'>Good Day Sir/Madam </p>
            <p style='margin-bottom:8px;'>
                We are sorry to let you know that your loan application on " . SITENAME . " 
                was rejected due the reason below<br/>
                <em>$reason</em>
            </p>
        ";
        $Notification->sendMail(['to' => [$email]], "Loan Application Status", $content);

        $responseTitle = 'Operation Successful';
        $responseMessage = 'A loan has been successfully rejected';
        $responseColor = Tag::RESPONSE_POSITIVE;
    }

    Tag::setResponse($responseTitle, [$responseMessage], $responseColor);

    //redirect on completion
    header($responseUrl);
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
