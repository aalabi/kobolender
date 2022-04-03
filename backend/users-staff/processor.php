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
    $responseURLBACKEND = "Location: .";

    if ($_POST['action'] == 'create') {
        $errors = [];
        //validate email
        $firstName = filter_var(trim($_POST['firstName']), FILTER_SANITIZE_STRING);
        $surName = filter_var(trim($_POST['surName']), FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $staffType = (in_array($_POST['staffType'], MyUsers::STAFF_TYPE)) ?  $_POST['staffType'] : false;
        $status = $_POST['status'];
        $password = (trim($_POST['password']) == '') ? substr(Functions::characterFromASCII(Functions::asciiTableDigitalAlphabet(), 'string'), 0, 8) : $_POST['password'];
        $middleName = (!isset($_POST['middleName']) || trim($_POST['middleName']) == '') ? "" : $_POST['middleName'];

        //check for validation
        if ($email) {
            $Db = new Database(__FILE__, $PDO, 'login');
            if ($Db->isDataInColumn(__LINE__, $email, 'email')) $errors[] = "email is associated with another user";
        } else {
            $errors[] = "invalid email";
        }
        if (!$staffType) $errors[] = "invalid staff type";
        if (!$firstName) $errors[] = "enter a valid first name";
        if (!$surName)  $errors[] = "enter a valid surname";
        if (!in_array($status, ['active', 'inactive'])) $errors[] = "invalid status";

        //redirect on error        
        if ($errors) {
            Tag::setResponse(
                'Invalid Data Input',
                $errors,
                Tag::RESPONSE_NEGATIVE
            );
            header($responseURLBACKEND);
            exit;
        }

        try {
            $loginId = $User->create(['email' => $email, 'password' => $password, 'user_type' => 'staff'], true, true);
            $Db = new Database(__FILE__, $PDO, 'login');
            $data = ['status' => ['colValue' => $status, 'isFunction' => false, 'isBindAble' => true]];
            $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $loginId]];
            $Db->update(__LINE__, $data, $where);

            $info = $User->getInfo($loginId);
            $profileId = $info['profile']['id'];
            $ProfileDb = new Database(__FILE__, $PDO, 'profile');
            $StaffDb = new Database(__FILE__, $PDO, 'staff');

            $col = [
                'firstname' => ['colValue' => $firstName, 'isFunction' => false, 'isBindAble' => true],
                'middlename' => ['colValue' => $middleName, 'isFunction' => false, 'isBindAble' => true],
                'surname' => ['colValue' => $surName, 'isFunction' => false, 'isBindAble' => true]
            ];
            $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $profileId]];
            $ProfileDb->update(__LINE__, $col, $where);

            $col1 = ['type' => ['colValue' => $staffType, 'isFunction' => false, 'isBindAble' => true]];
            $where1 = [['column' => 'profile_id', 'comparsion' => '=', 'bindAbleValue' => $profileId]];
            $StaffDb->update(__LINE__, $col1, $where1);

            //send mail out
            $Notification = new Notification();
            $email = $info['login']['email'];
            $content = "
                    <p style='margin-bottom:20px;'>Good Day Sir/Madam </p>
                    <p style='margin-bottom:8px;'>
                        Congratulation we will like to let you know that an account was created for you on " . SITENAME . " 
                        and you can login with the password below:</br>
                        <strong>Password</strong>: $password <br/>
                    </p>
                ";
            $Notification->sendMail(['to' => [$email]], "Account Creation", $content);

            $responseTitle = 'Operation Successful';
            $responseMessage = 'Staff Created Successfully.';
            $responseColor = Tag::RESPONSE_POSITIVE;
        } catch (\Throwable $th) {
            $responseTitle = 'Operation Failed';
            $responseMessage = $th->getMessage();
            $responseColor = Tag::RESPONSE_NEGATIVE;
        }
        Tag::setResponse($responseTitle, [$responseMessage], $responseColor);
    }

    if ($_POST['action'] == 'edit') {
        $errors = [];
        //validate email
        $loginId = filter_var(trim($_POST['loginId']), FILTER_VALIDATE_INT);
        $firstName = filter_var(trim($_POST['firstName']), FILTER_SANITIZE_STRING);
        $surName = filter_var(trim($_POST['surName']), FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $staffType = $_POST['staffType'];
        $status = $_POST['status'];
        $middleName = (!isset($_POST['middleName']) || trim($_POST['middleName']) == '') ? NULL : $_POST['middleName'];

        //check for validation
        if (!$loginId) $errors[] = "invalid staff";
        if ($email) {
            $Db = new Database(__FILE__, $PDO, 'login');
            $where = [
                ['column' => 'email', 'comparsion' => '=', 'bindAbleValue' => $email, 'logic' => 'AND'],
                ['column' => 'id', 'comparsion' => '<>', 'bindAbleValue' => $loginId]
            ];
            if ($Db->select(__LINE__, [], $where)) $errors[] = "email is associated with another user";
        } else {
            $errors[] = "invalid email";
        }
        if (!in_array($staffType, MyUsers::STAFF_TYPE)) $errors[] = "invalid staff type";
        if (!in_array($status, ['active', 'inactive'])) $errors[] = "invalid status";
        if (!$firstName) $errors[] = "enter a valid first name";
        if (!$surName)  $errors[] = "enter a valid surname";

        //redirect on error        
        if ($errors) {
            Tag::setResponse(
                'Invalid Data Input',
                $errors,
                Tag::RESPONSE_NEGATIVE
            );
            header($responseURLBACKEND);
            exit;
        }

        //login data
        $Db = new Database(__FILE__, $PDO, 'login');
        $data = [
            'email' => ['colValue' => $email, 'isFunction' => false, 'isBindAble' => true],
            'status' => ['colValue' => $status, 'isFunction' => false, 'isBindAble' => true],
        ];
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $loginId]];
        $Db->update(__LINE__, $data, $where);

        //profile data
        $User = new Users($PDO);
        $info = $User->getInfo($loginId);
        $Db = new Database(__FILE__, $PDO, 'profile');
        $data = [
            'firstname' => ['colValue' => $firstName, 'isFunction' => false, 'isBindAble' => true],
            'middlename' => ['colValue' => $middleName, 'isFunction' => false, 'isBindAble' => true],
            'surname' => ['colValue' => $surName, 'isFunction' => false, 'isBindAble' => true]
        ];
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $info['profile']['id']]];
        $Db->update(__LINE__, $data, $where);

        //staff type data
        $Db = new Database(__FILE__, $PDO, 'staff');
        $data = ['type' => ['colValue' => $staffType, 'isFunction' => false, 'isBindAble' => true]];
        $where = [['column' => 'profile_id', 'comparsion' => '=', 'bindAbleValue' => $info['profile']['id']]];
        $Db->update(__LINE__, $data, $where);

        $responseTitle = 'Operation Successful';
        $responseMessage = 'A staff detail has been successfully updated.';
        $responseColor = Tag::RESPONSE_POSITIVE;
        Tag::setResponse($responseTitle, [$responseMessage], $responseColor);
    }

    if ($_POST['action'] == 'delete') {
        $errors = [];
        $loginId = filter_var(trim($_POST['loginId']), FILTER_VALIDATE_INT);

        if (!$loginId) $errors[] = "invalid staff";

        //redirect on error        
        if ($errors) {
            Tag::setResponse(
                'Invalid Data Input',
                $errors,
                Tag::RESPONSE_NEGATIVE
            );
            header($responseURLBACKEND);
            exit;
        }

        $Db = new Database(__FILE__, $PDO, 'login');
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $loginId]];
        $Db->delete(__LINE__, $where);

        $responseTitle = 'Operation Successful';
        $responseMessage = 'A staff has been successfully deleted.';
        $responseColor = Tag::RESPONSE_POSITIVE;
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
