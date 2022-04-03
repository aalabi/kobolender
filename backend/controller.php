<?php
require_once "connection.php";
$Tag = new MyTag($PDO);

$headCss = ['css' => [URLBACKEND . "asset/css/animate.min.css"]];
$title = SITENAME_EXTERNAL;

$footJs = [
    URLBACKEND . 'asset/js/jquery.min.js',
    URLBACKEND . 'asset/js/bootstrap.bundle.min.js',
    URLBACKEND . 'asset/js/custom.js'
];

$footerSlog = "    
    <a href='" . URL . "'><h1 style='width:100%'> " . SITENAME_EXTERNAL . "</h1></a>";

$responseLogin = "";
$theResponse = Tag::getResponse();
if ($theResponse && !isset($_SESSION['action'])) {
    $responseLogin = $Tag->responseTag(
        $theResponse['title'],
        $theResponse['messages'][0],
        $theResponse['status']
    );
}

$responseForgotPassword = "";
if ($theResponse && isset($_SESSION['action'])) {
    unset($_SESSION['action']);
    $responseForgotPassword = $Tag->responseTag(
        $theResponse['title'],
        $theResponse['messages'][0],
        $theResponse['status']
    );
}
