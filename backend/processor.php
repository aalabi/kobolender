<?php
require_once "connection.php";

if (
    isset($_POST[Authentication::SESSION_CSRF_TOKEN])
    && Authentication::checkCSRFToken($_POST[Authentication::SESSION_CSRF_TOKEN])
) {
    if ($_POST['action'] == 'login') {
        //get inputted data
        $email = $_POST['email'];
        $password = $_POST['password'];

        //login the new user		
        $Authenticator = new Authentication($PDO);
        if ($Authenticator->loginUser(strtolower($email), $password)) {
            $Users = new Users($PDO);
            $loginId = $Users->getIdFrmLoginField($email);
            $type = $Users->getInfo($loginId)['user_type']['type_name'];
            $home = ($type == MyUsers::STAFF['name']) ? 'home' : 'home-users';
            header("Location:" . URLBACKEND . $home);
        } else {
            Tag::setResponse(
                'Login Failed',
                ['Login failed, please check your password or email'],
                Tag::RESPONSE_NEGATIVE
            );
            header("Location: " . URLBACKEND . "#signin");
        }
    }
    if ($_POST['action'] == 'forgetPassword') {
        //get inputted data
        $email = $_POST['email'];
        $User = new Users($PDO);
        try {
            $userId = $User->getIdFrmLoginField($email);
            $User->sendResetToken($userId, true);
            $_SESSION['action'] = 'forgetPassword';
            Tag::setResponse(
                'Forgot Password',
                ["Check your email '$email' for your password reset code"]
            );
            header("Location: " . URLBACKEND . "#signup");
        } catch (UsersException $ue) {
            $_SESSION['action'] = 'forgetPassword';
            Tag::setResponse(
                'Forgot Password Failed',
                ['Forgot password failed, the email is not associated with any account on our system'],
                Tag::RESPONSE_NEGATIVE
            );
            header("Location: " . URLBACKEND . "#signup");
        }
    }
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