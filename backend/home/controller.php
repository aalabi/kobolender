<?php
require_once dirname(__FILE__, 2) . "/connection.php";
$Authentication = new Authentication($PDO);
$loggerName = "";
$profileImage = URLBACKEND . "asset/image/profile-image/default-profile.png";
$Authentication->keyToPage();
$Tag = new MyTag($PDO);
$User = new Users($PDO);
$staffId = $_SESSION[Authentication::SESSION_NAME]['id'];
$staffInfo = $User->getInfo($staffId);
$Authentication->pageAccessor([MyUsers::STAFF['name']], $staffInfo['user_type']['type_name']);
$loggerName = "{$staffInfo['profile']['surname']} {$staffInfo['profile']['firstname']}";
$profileImage = URLBACKEND . "asset/image/profile-image/{$staffInfo['profile']['profile_image']}";

$title = SITENAME . " Home";
$pageName = "Home";
$sideBar = $Tag->createSideMenu(['home'], $staffId);
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

$totalIndividual = $totalMsme = $totalGuarantor = 0;
$userTypeInfo = $User->getTypeInfo();
$Db = new Database(__FILE__, $PDO, "profile");
$query = "SELECT user_type, count(id) as totalId FROM profile GROUP BY user_type";
$result = $Db->queryStatment(__LINE__, $query);
if ($result['data']) {
    foreach ($result['data'] as $aResult) {
        if ($userTypeInfo[$aResult['user_type']]['type_name'] == MyUsers::GUARANTOR['name'])
            $totalGuarantor = $aResult['totalId'];
        if ($userTypeInfo[$aResult['user_type']]['type_name'] == MyUsers::INDIVIDUAL['name'])
            $totalIndividual = $aResult['totalId'];
        if ($userTypeInfo[$aResult['user_type']]['type_name'] == MyUsers::MSME['name'])
            $totalMsme = $aResult['totalId'];
    }
}

$totalNewLoan = $totalWipLoan = $totalApprovedLoan = $totalRejectedLoan = 0;
$query = "SELECT status, count(id) as totalId FROM loan GROUP BY status";
$result = $Db->queryStatment(__LINE__, $query);
if ($result['data']) {
    foreach ($result['data'] as $aResult) {
        if ($aResult['status'] == 'applied') $totalNewLoan = $aResult['totalId'];
        if ($aResult['status'] == 'approved') $totalApprovedLoan = $aResult['totalId'];
        if ($aResult['status'] == 'wip') $totalWipLoan = $aResult['totalId'];
        if ($aResult['status'] == 'rejected') $totalRejectedLoan = $aResult['totalId'];
    }
}
