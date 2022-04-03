<?php
require_once dirname(__FILE__, 2) . "/connection.php";

$User = new Users($PDO);

if ($_POST['action'] == 'forgetPasswordChange') {

    //get inputted data
    $errors = [];
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $token = $_POST['token'];
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
    $successResponseUrl = "Location: " . URLBACKEND . "#signin";
    $errorResponseUrl = "Location: " . URLBACKEND . "password-reset/?token=" . urlencode($token) . "&email=" . urlencode($email);
    if ($errors) {
        Tag::setResponse(
            'Invalid Data Input',
            $errors,
            Tag::RESPONSE_NEGATIVE
        );
        header($errorResponseUrl);
        exit;
    }

    $userId = $User->getIdFrmLoginField($email);
    $data = [
        'reset_token' => '',
        'reset_token_time' => 'NOW()',
        'password' => $password
    ];
    $User->update($data, $userId);

    $responseTitle = 'Operation Successful';
    $responseMessage = 'Your password has been successfully changed. Please login with  your new password';
    $responseColor = Tag::RESPONSE_POSITIVE;
    Tag::setResponse($responseTitle, [$responseMessage], $responseColor);

    //redirect on completion
    header($successResponseUrl);
    exit();
} else {
    new ErrorLog('Suspected CSRF Attack', __FILE__, __LINE__);
    Tag::setResponse(
        'Expired Session',
        ['Your session has expired, please repeat the process again'],
        Tag::RESPONSE_NEGATIVE
    );
    header($errorResponseUrl);
    exit();
}
