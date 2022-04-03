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
$loggerName = "{$staffInfo['profile']['surname']} {$staffInfo['profile']['firstname']}";
$profileImage = URLBACKEND . "asset/image/profile-image/{$staffInfo['profile']['profile_image']}";

$individual = MyUsers::INDIVIDUAL['name'];
$userType = Users::USERTYPE_TABLE;
$msme = MyUsers::MSME['name'];
$profile = Users::TABLE_PROFILE;
$guarantor = MyUsers::GUARANTOR['name'];
$login = Authentication::TABLE;

$MyUser = new MyUsers($PDO);
$id = (isset($_GET['id'])) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : false;
if (!$id || !$MyUser->isProfileIdValid($id)) {
    header('Location: ' . URLBACKEND . "home");
    exit;
}

$info = $MyUser->getProfileInfo($id);
if ($info[$userType]['type_name'] != $guarantor) {
    header('Location: ' . URLBACKEND . "home");
    exit;
}

$accoutNo = Functions::getAcctNo($PDO, $id);
$title = SITENAME . " {$info['profile']['firstname']} {$info['profile']['middlename']} {$info['profile']['surname']} $accoutNo";
$pageName = "{$info['profile']['firstname']} {$info['profile']['middlename']} {$info['profile']['surname']} $accoutNo";
$sideBar = $Tag->createSideMenu([], $staffId);
$headerFiles = [
    'css' => [
        URLBACKEND . 'asset/css/jquery.dataTables.min.css',
        URLBACKEND . 'asset/css/dataTables.bootstrap.min.css',
        URLBACKEND . 'asset/css/buttons.bootstrap.min.css',
        URLBACKEND . 'asset/css/fixedHeader.bootstrap.min.css',
        URLBACKEND . 'asset/css/responsive.bootstrap.min.css',
        URLBACKEND . 'asset/css/scroller.bootstrap.min.css',
    ]
];

$footJs = [
    URLBACKEND . 'asset/js/jquery.min.js',
    URLBACKEND . 'asset/js/bootstrap.bundle.min.js',
    URLBACKEND . 'asset/js/fastclick.js',
    URLBACKEND . 'asset/js/nprogress.js',
    URLBACKEND . 'asset/js/bootstrap.bundle.min.js',
    URLBACKEND . 'asset/js/jquery.dataTables.min.js',
    URLBACKEND . 'asset/js/dataTables.bootstrap.min.js',
    URLBACKEND . 'asset/js/dataTables.buttons.min.js',
    URLBACKEND . 'asset/js/buttons.bootstrap.min.js',
    URLBACKEND . 'asset/js/buttons.flash.min.js',
    URLBACKEND . 'asset/js/buttons.html5.min.js',
    URLBACKEND . 'asset/js/buttons.print.min.js',
    URLBACKEND . 'asset/js/pdfmake.min.js',
    URLBACKEND . 'asset/js/vfs_fonts.js',
    URLBACKEND . 'asset/js/custom.js',
    URLBACKEND . 'asset/js/al-custom.js'
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

$phone = $info[$guarantor]['phone'];
$email = $info[$guarantor]['email'];
$letter = ($info[$guarantor]['letter']) ?
    Functions::ASSET_IMG_URLBACKEND . "guarantor-doc/{$info[$guarantor]['letter']}" : "";
$idCard = ($info[$guarantor]['id_card']) ?
    Functions::ASSET_IMG_URLBACKEND . "guarantor-doc/{$info[$guarantor]['id_card']}" : "";
$bankStatement = ($info[$guarantor]['bank_statment']) ?
    Functions::ASSET_IMG_URLBACKEND . "guarantor-doc/{$info[$guarantor]['bank_statment']}" : "";

$guarantorData = "
 <ul class='list-unstyled user_data'>
 " . (($info[$guarantor]['type'] == 'guarantor') ?
    "<li class='m-top-xs'>
        <i class='fa fa-phone user-profile-icon'></i>
        <a href='tel:$phone'>$phone</a>
    </li>"
    : "") . "

" . (($info[$guarantor]['type'] == 'guarantor') ?
    "<li class='m-top-xs'>
        <i class='fa fa-envelope user-profile-icon'></i>
        <a href='mailto: $email'>$email</a>
    </li>"
    : "") . "

" . (($info[$guarantor]['type'] == 'guarantor') ?
    "<li>
        <i class='fa fa-map-marker user-profile-icon'></i>
        {$info['profile']['address']}
    </li>"
    : "") . "
    <li>
        <i class='fa fa-file user-profile-icon'></i>
        Acceptance Letter <a target='_blank' href=$letter><u>view</u></a>
    </li>

    " . (($info[$guarantor]['type'] == 'guarantor') ?
    "<li>
        <i class='fa fa-file-text-o user-profile-icon'></i>
        ID Card <a target='_blank' href=$idCard><u>view</u></a>
    </li>"
    :
    "<li>
        <i class='fa fa-file-text-o user-profile-icon'></i>
        Bank Statement <a target='_blank' href=$bankStatement><u>view</u></a>
    </li>
    <li>
        <i class='fa fa-asterisk user-profile-icon'></i>
        BVN {$info[$guarantor]['id_card_no']}
    </li>") . "

</ul>";

$Db = new Database(__FILE__, $PDO, $guarantor);
$where = [['column' => 'profile_id', 'comparsion' => '=', 'bindAbleValue' => $id]];
$guarantorUserId = $Db->select(__LINE__, ['id'], $where)[0]['id'];

$guarantees = [];
$Db->setTable($individual);
$where = [['column' => 'guarantor_id', 'comparsion' => '=', 'bindAbleValue' => $guarantorUserId]];
if ($result = $Db->select(__LINE__, ['profile_id'], $where)) {
    foreach ($result as $aResult) {
        $thisInfo = $MyUser->getProfileInfo($aResult['profile_id']);
        $guarantees[] = [
            'name' => "{$thisInfo[$profile]['firstname']} {$thisInfo[$profile]['middlename']} {$thisInfo[$profile]['surname']}",
            'email' => "{$thisInfo[$login]['email']}",
            "type" => "{$thisInfo[$userType]['type_name']}",
            "phone" => "{$thisInfo[$login]['phone']}",
            'profileId' => $aResult['profile_id']
        ];
    }
}
$Db->setTable($msme);
if ($result = $Db->select(__LINE__, ['profile_id'], $where)) {
    foreach ($result as $aResult) {
        $thisInfo = $MyUser->getProfileInfo($aResult['profile_id']);
        $guarantees[] = [
            'name' => "{$thisInfo[$profile]['firstname']} {$thisInfo[$profile]['middlename']} {$thisInfo[$profile]['surname']}",
            'email' => $thisInfo[$login]['email'],
            'type' => $thisInfo[$userType]['type_name'],
            'phone' => $thisInfo[$login]['phone'],
            'profileId' => $aResult['profile_id']
        ];
    }

    //check other guarantor/promoter columns
    if ($result = $Db->select(__LINE__, ['profile_id', 'guarantor_id_others'])) {
        foreach ($result as $aResult) {
            if ($aResult['guarantor_id_others'] && in_array($guarantorUserId, json_decode($aResult['guarantor_id_others'], true))) {
                $thisInfo = $MyUser->getProfileInfo($aResult['profile_id']);
                $guarantees[] = [
                    'name' => "{$thisInfo[$profile]['firstname']} {$thisInfo[$profile]['middlename']} {$thisInfo[$profile]['surname']}",
                    'email' => $thisInfo[$login]['email'],
                    'type' => $thisInfo[$userType]['type_name'],
                    'phone' => $thisInfo[$login]['phone'],
                    'profileId' => $aResult['profile_id']
                ];
            }
        }
    }
}

$tr = "";
if ($guarantees) {
    foreach ($guarantees as $aGuarantee) {
        $accoutNo = Functions::getAcctNo($PDO, $aGuarantee['profileId']);
        $tr .= "
<tr>
    <th style='width:40%'>{$aGuarantee['name']}
        $accoutNo<br />(<small>{$aGuarantee['type']}</small>)
    </th>
    <td>{$aGuarantee['email']}<br />{$aGuarantee['phone']}</td>
</tr>";
    }
}
