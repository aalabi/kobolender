<?php
require_once dirname(__FILE__, 2) . "/connection.php";
$Authentication = new Authentication($PDO);
$Authentication->keyToPage();
$User = new Users($PDO);
$userId = $_SESSION[Authentication::SESSION_NAME]['id'];
$staffInfo = $User->getInfo($userId);

if (
    isset($_POST[Authentication::SESSION_CSRF_TOKEN])
    && Authentication::checkCSRFToken($_POST[Authentication::SESSION_CSRF_TOKEN])
) {

    //get inputted data
    $errors = [];
    $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
    $repeatPassword = filter_var(trim($_POST['repeatPassword']), FILTER_SANITIZE_STRING);

    //check for validation
    if (!$password) {
        $errors[] = "invalid password";
    }
    if (strlen($password) < 8) {
        $errors[] = "password must be minimum 8 characters";
    }
    if (!$repeatPassword) {
        $errors[] = "invalid repeated password";
    }
    if ($password != $repeatPassword) {
        $errors[] = "password and repeated password differs";
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

    $User->update(['password' => $password], $userId);
    $Authentication->loginUser($staffInfo[Authentication::TABLE]['email'], $password);
    /* $fingerPrint = $Authentication->generateFingerPrint($userId, $staffInfo['login']['email'], $password);
    $session = ['id' => $userId, 'fingerPrint' => $fingerPrint];
    $_SESSION[Authentication::SESSION_NAME] = $session; */

    $responseTitle = 'Operation Successful';
    $responseMessage = 'Your password has been successfully changed';
    $responseColor = Tag::RESPONSE_POSITIVE;
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
