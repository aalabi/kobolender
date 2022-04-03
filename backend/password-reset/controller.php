<?php
require_once dirname(__FILE__, 2) . "/connection.php";
$Tag = new MyTag($PDO);

$headCss = ['css' => [URLBACKEND . "asset/css/animate.min.css"]];
$title = "Forgot Password Change";

$footJs = [
    URLBACKEND . 'asset/js/jquery.min.js',
    URLBACKEND . 'asset/js/bootstrap.bundle.min.js',
    URLBACKEND . 'asset/js/custom.js'
];

$footerSlog = "    
    <a href='" . URLBACKEND . "'><h1 style='width:100%'> " . SITENAME_EXTERNAL . "</h1></a>";

$responseOperation = "";
if ($theResponse = Tag::getResponse()) {
    $responseMessage = rtrim(implode(", ", $theResponse['messages']), ", ");
    $responseOperation = $Tag->responseTag(
        $theResponse['title'],
        $responseMessage,
        $theResponse['status']
    );
}
