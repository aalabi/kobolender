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

$title = SITENAME . " Customer Administration";
$pageName = "Customer Administration";
$sideBar = $Tag->createSideMenu(['users'], $staffId);
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

$individual = MyUsers::INDIVIDUAL['name'];
$userType = Users::USERTYPE_TABLE;
$msme = MyUsers::MSME['name'];
$profile = Users::TABLE_PROFILE;
$guarantor = MyUsers::GUARANTOR['name'];

$tr = "";
$Db = new Database(__FILE__, $PDO, 'profile');
$User = new Users($PDO);
$MyUser = new MyUsers($PDO);

$staffTypeId = $User->getTypeInfo()[MyUsers::STAFF['name']]['id'];
$where = [
    ['column' => 'user_type', 'comparsion' => '<>', 'bindAbleValue' => $staffTypeId]
];
$result = $Db->select(__LINE__, ['id'], $where);

if ($result) {
    $kanter = 0;
    foreach ($result as $aResult) {
        $profileInfo = $MyUser->getProfileInfo($aResult['id']);
        if ($profileInfo[$userType]['type_name'] == $individual) $myType = $individual;
        if ($profileInfo[$userType]['type_name'] == $msme) $myType = $msme;
        if ($profileInfo[$userType]['type_name'] == $guarantor) $myType = $guarantor;

        $email = ($profileInfo['user_type']['type_name'] == MyUsers::GUARANTOR['name']) ?
            $profileInfo['guarantor']['email'] : $profileInfo['login']['email'];
        $phone = ($profileInfo['user_type']['type_name'] == MyUsers::GUARANTOR['name']) ?
            $profileInfo['guarantor']['phone'] : $profileInfo['login']['phone'];
        $page = ($profileInfo['user_type']['type_name'] == MyUsers::GUARANTOR['name']) ?
            "a-guarantor" : "a-user";
        $status = ($profileInfo['user_type']['type_name'] == MyUsers::GUARANTOR['name']) ?
            "" : $profileInfo['login']['status'];
        $accountNo = Functions::getAcctNo($PDO, $aResult['id']);
        if ($myType == $individual)
            $name = "{$profileInfo['profile']['firstname']} {$profileInfo['profile']['middlename']} {$profileInfo['profile']['surname']}";
        if ($myType == $msme) $name = $profileInfo[$msme]['company'];
        if ($myType == $guarantor) $name = $profileInfo[$profile]['firstname'];
        $tr .= "
            <tr>
                <td>" . ++$kanter . "</td>
                <td>
                    $name<br/>
                    $accountNo <small>$status</small>
                </td>
                <td><img class='al_small_img img-responsive' src='" . Functions::ASSET_IMG_URLBACKEND . "profile-image/{$profileInfo['profile']['profile_image']}'></td>
                <td>
                    $email<br/>
                    $phone
                </td>
                <td>{$profileInfo['user_type']['type_name']}</td>
                <td><a class='btn btn-sm btn-primary' href='" . URLBACKEND . "$page?id={$aResult['id']}'>view</a></td>
            </tr>    
        ";
    }
}
