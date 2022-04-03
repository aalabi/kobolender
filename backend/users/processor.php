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
    if ($_POST['action'] == 'search') {
        //get inputted data
        $errors = [];
        $accountNo = filter_var(trim($_POST['accountNo']), FILTER_SANITIZE_STRING);
        $phoneNo = filter_var(trim($_POST['phoneNo']), FILTER_SANITIZE_STRING);
        $customerName = filter_var(trim($_POST['customerName']), FILTER_SANITIZE_STRING);

        //check for validation
        if (!$accountNo && !$phoneNo && !$customerName) {
            $errors[] = "enter a criteria";
        }
        if ($accountNo) {
            $profileId = Functions::getProfileIdFromAcctNo($accountNo);
            if (!$MyUsers->isProfileIdValid($profileId)) $errors[] = "invalid account no";
        }

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

        if ($phoneNo) {
            if ($ids = $User->getIds(['phone' => $phoneNo])) {
                $profileId = $User->getInfo($ids[0])['profile']['id'];
            } else {
                $Db = new Database(__FILE__, $PDO, MyUsers::GUARANTOR['table']);
                $where = [['column' => 'phone', 'comparsion' => '=', 'bindAbleValue' => $phoneNo]];
                if ($result = $Db->select(__LINE__, ['profile_id'], $where)) $profileId = $result[0]['profile_id'];
            }
        }
        if (isset($profileId)) {
            $profileInfo = $MyUsers->getProfileInfo($profileId);
            $page = ($profileInfo[Users::USERTYPE_TABLE]['type_name'] == MyUsers::GUARANTOR['name']) ?
                "a-guarantor" : "a-user";
            header("Location: " . URLBACKEND . "$page/?id=$profileId");
            exit;
        }
        if ($customerName) {
            $_SESSION['customerName'] = $customerName;
            $responseTitle = 'Operation Successful';
            $responseMessage = 'Below are users that meets your search criteria';
            $responseColor = Tag::RESPONSE_POSITIVE;
        } else {
            $responseTitle = 'Operation Successful';
            $responseMessage = 'No users meets your search criteria';
            $responseColor = Tag::RESPONSE_NEGATIVE;
        }
        Tag::setResponse($responseTitle, [$responseMessage], $responseColor);
    }

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
