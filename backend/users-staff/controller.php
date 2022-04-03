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

$title = SITENAME . " Staff Administration";
$pageName = "Staff  Administration";
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

//status
$activeSelected = "checked";
$inActiveSelected = "";

//edit mode
$thisStaffEmail = $firstname = $middlename = $surname = $userTypeZeroSelected = $userTypeOneSelected = "";
$loginIdInput = "";
$action = "create";
$btnCaption = "Create";
$passwordDisabled = "";
if (isset($_GET['id'])) {
    if ($id = filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
        $MyUser = new MyUsers($PDO);
        if ((new Database(__FILE__, $PDO, 'login'))->isDataInColumn(__LINE__, $id, 'id')) {
            $action = "edit";
            $loginIdInput = "<input type='hidden' name='loginId' value='$id' />";
            $btnCaption = "Edit";
            $passwordDisabled = "disabled";
            $thisStaffInfo = $MyUser->getInfo($id);
            $thisStaffEmail = $thisStaffInfo['login']['email'];
            $firstname = $thisStaffInfo['profile']['firstname'];
            $middlename = $thisStaffInfo['profile']['middlename'];
            $surname = $thisStaffInfo['profile']['surname'];
            $userTypeZeroSelected = ($thisStaffInfo['staff']['type'] == MyUsers::STAFF_TYPE[0]) ? "checked" : "";
            $userTypeOneSelected = ($thisStaffInfo['staff']['type'] == MyUsers::STAFF_TYPE[1]) ? "checked" : "";
            $activeSelected = ($thisStaffInfo['login']['status'] == 'active') ? "checked" : "";
            $inActiveSelected = ($thisStaffInfo['login']['status'] == 'inactive') ? "checked" : "";
        }
    }
}

$tr = "";
$Db = new Database(__FILE__, $PDO, 'profile');
$User = new Users($PDO);
$MyUser = new MyUsers($PDO);

$staffTypeId = $User->getTypeInfo()[MyUsers::STAFF['name']]['id'];
$where = [
    ['column' => 'user_type', 'comparsion' => '=', 'bindAbleValue' => $staffTypeId]
];
$result = $Db->select(__LINE__, ['id'], $where);
if ($result) {
    $kanter = 0;
    foreach ($result as $aResult) {
        $profileInfo = $MyUser->getProfileInfo($aResult['id']);
        $email = $profileInfo['login']['email'];
        $phone = $profileInfo['login']['phone'];
        $status = $profileInfo['login']['status'];
        $accountNo = Functions::getAcctNo($PDO, $aResult['id']);
        $editBtn = "<a href='" . URLBACKEND . Functions::pwdName(__FILE__) . "/?id={$profileInfo['login']['id']}' class='btn btn-primary btn-sm'>edit</a>";
        $deleteBtn = "";
        $Db->setTable('loan_transaction');
        if (!$Db->isDataInColumn(__LINE__, $profileInfo['profile']['id'], 'poster_id')) {
            $deleteBtn = "
                <form action='processor.php' method='post'>
                    <input type='hidden' name='action' value='delete' />
                    <input type='hidden' name='loginId' value='{$profileInfo['login']['id']}' />
                    " . MyTag::getCSRFTokenInputTag() . "
                    <button type='submit' name='submit' class='btn btn-danger btn-sm'>delete</button>
                </form>
            ";
        }
        $tr .= "
            <tr>
                <td>" . ++$kanter . "</td>
                <td>
                    {$profileInfo['profile']['firstname']} 
                    {$profileInfo['profile']['middlename']}
                    {$profileInfo['profile']['surname']}<br/>
                    $accountNo <small>$status</small>
                </td>
                <td><img class='al_small_img img-responsive' src='" . Functions::ASSET_IMG_URLBACKEND . "profile-image/{$profileInfo['profile']['profile_image']}'></td>
                <td>
                    $email<br/>
                    $phone
                </td>
                <td>{$profileInfo['staff']['type']}</td>
                <td>
                    $editBtn
                    $deleteBtn
                </td>
            </tr>    
        ";
    }
}
