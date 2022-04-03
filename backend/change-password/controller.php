<?php
require_once dirname(__FILE__, 2) . "/connection.php";
$Authentication = new Authentication($PDO);
$loggerName = "";
$profileImage = URLBACKEND . "asset/image/profile-image/default-profile.png";
$Authentication->keyToPage();

$Tag = new MyTag($PDO);
$User = new Users($PDO);
$userId = $_SESSION[Authentication::SESSION_NAME]['id'];

$userInfo = $User->getInfo($userId);
$loggerName = "{$userInfo['profile']['surname']} {$userInfo['profile']['firstname']}";
$profileImage = URLBACKEND . "asset/image/profile-image/{$userInfo['profile']['profile_image']}";

$title = SITENAME . " Change Password";
$pageName = "Change Password";
$sideBar = $Tag->createSideMenu(["change password"], $userId);
$footJs = [
    URLBACKEND . 'asset/js/jquery.min.js',
    URLBACKEND . 'asset/js/bootstrap.bundle.min.js',
    URLBACKEND . 'asset/js/fastclick.js',
    URLBACKEND . 'asset/js/nprogress.js',
    URLBACKEND . 'asset/js/bootstrap.bundle.min.js',
    URLBACKEND . 'asset/js/custom.js'
];

$responseOperation = "";
if ($theResponse = Tag::getResponse()) {
    $responseMessage = rtrim(implode(", ", $theResponse['messages']), ", ");
    $responseOperation = $Tag->responseTag(
        $theResponse['title'],
        $responseMessage,
        $theResponse['status']
    );
}