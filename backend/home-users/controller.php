<?php
require_once dirname(__FILE__, 2) . "/connection.php";
$Authentication = new Authentication($PDO);
$loggerName = "";
$profileImage = URLBACKEND . "asset/image/profile-image/default-profile.png";
$Authentication->keyToPage();
$Tag = new MyTag($PDO);
$User = new Users($PDO);
$customerId = $_SESSION[Authentication::SESSION_NAME]['id'];
$customerInfo = $User->getInfo($customerId);
$profileId = $customerInfo['profile']['id'];

$Authentication->pageAccessor([MyUsers::INDIVIDUAL['name'], MyUsers::MSME['name']], $customerInfo['user_type']['type_name']);
$loggerName = "{$customerInfo['profile']['surname']} {$customerInfo['profile']['firstname']}";
$profileImage = URLBACKEND . "asset/image/profile-image/{$customerInfo['profile']['profile_image']}";

$title = SITENAME . " Home";
$pageName = "Home";
$sideBar = $Tag->createSideMenu(['home'], $customerId);
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

$MyUser = new MyUsers($PDO);
$info = $MyUser->getProfileInfo($profileId);
$individual = MyUsers::INDIVIDUAL['name'];
$msme = MyUsers::MSME['name'];
$profile = Users::TABLE_PROFILE;
$userTypeTable = Users::USERTYPE_TABLE;
$myType = ($info[$userTypeTable]['type_name'] == $individual) ? $individual : $msme;

$company = $info[$myType]['company'];
$guarantor = MyUsers::GUARANTOR['name'];
$Db = new Database(__FILE__, $PDO, "profile");

$gId = $gurantorsName = $gurantorsPhone = $gurantorsEmail = $guarantorData = "";
if ($info[$myType]['guarantor_id']) {
    $gId = $MyUser->getProfileIdFrmUserId($info[$myType]['guarantor_id'], $guarantor);
    $gInfo = $MyUser->getProfileInfo($gId);
    $gurantorsName = "{$gInfo[$profile]['firstname']} {$gInfo[$profile]['middlename']} {$gInfo[$profile]['surname']}";
    $gurantorsPhone = "{$gInfo[$guarantor]['phone']}";
    $gurantorsEmail = $gInfo[$guarantor]['email'];
    $gurantorsAddress = $gInfo[$profile]['address'];
    $letter = Functions::ASSET_IMG_URLBACKEND . "guarantor-doc/{$gInfo[$guarantor]['letter']}";
    $bankStatement = Functions::ASSET_IMG_URLBACKEND . "guarantor-doc/{$gInfo[$guarantor]['bank_statment']}";

    $guarantorData = "
           <ul class='list-unstyled user_data'>
                <li>
                    <p>
                        <i class='fa fa-user user-profile-icon'></i>
                        $gurantorsName 
                    </p>
                    " . (($gInfo[$guarantor]['type'] == 'guarantor') ?
        "<p>
                        <i class='fa fa-phone user-profile-icon'></i>
                        <a href='tel: $gurantorsPhone '>
                            $gurantorsPhone
                        </a>
                    </p>" : "
                    <p>
                        <i class='fa fa-file user-profile-icon'></i>
                        <a target='_blank' href=$letter>Letter</a>
                    </p>") . "

                    " . (($gInfo[$guarantor]['type'] == 'guarantor') ?
        "<p>
                        <i class='fa fa-envelope user-profile-icon'></i>
                        <a href='mailto: $gurantorsEmail '>
                            $gurantorsEmail 
                        </a>
                    </p>"
        : "<p>
    <i class='fa fa-file-text-o user-profile-icon'></i>
    <a target='_blank' href=$bankStatement>Bank Statement</a>
</p>") . "

" . (($gInfo[$guarantor]['type'] == 'guarantor') ?
        "<p>
    <i class='fa fa-map-marker user-profile-icon'></i>
     $gurantorsAddress 
</p>"
        : "") . "

</li>
</ul>
";
}

if ($myType == $individual) {
    $promoterGuarantorCaption = "Guarantor";
} else {
    $promoterGuarantorCaption = "Promoter";
}

$totalNewLoan = $totalLiquidatedLoan = $totalApprovedLoan = $totalRejectedLoan = 0;
$query = "SELECT status, count(id) as totalId FROM loan WHERE profile_id = $profileId GROUP BY status";

$result = $Db->queryStatment(__LINE__, $query);

if ($result['data']) {
    foreach ($result['data'] as $aResult) {
        if ($aResult['status'] == 'applied') $totalNewLoan = $aResult['totalId'];
        if ($aResult['status'] == 'approved') $totalApprovedLoan = $aResult['totalId'];
        if ($aResult['status'] == 'liquated') $totalLiquidatedLoan = $aResult['totalId'];
        if ($aResult['status'] == 'rejected') $totalRejectedLoan = $aResult['totalId'];
    }
}
