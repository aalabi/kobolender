<?php
require_once dirname(__FILE__, 2) . "/connection.php";
$Authentication = new Authentication($PDO);
$loggerName = "";
$profileImage = URLBACKEND . "asset/image/profile-image/default-profile.png";
$Authentication->keyToPage();
$Tag = new MyTag($PDO);
$User = new Users($PDO);
$MyUser = new MyUsers($PDO);
$staffId = $_SESSION[Authentication::SESSION_NAME]['id'];
$staffInfo = $User->getInfo($staffId);
$Authentication->pageAccessor([MyUsers::STAFF['name']], $staffInfo['user_type']['type_name']);
$loggerName = "{$staffInfo['profile']['surname']} {$staffInfo['profile']['firstname']}";
$profileImage = URLBACKEND . "asset/image/profile-image/{$staffInfo['profile']['profile_image']}";

$individual = MyUsers::INDIVIDUAL['name'];
$userType = Users::USERTYPE_TABLE;
$msme = MyUsers::MSME['name'];
$profile = Users::TABLE_PROFILE;
$guarantor = MyUsers::GUARANTOR['name'];

$id = (isset($_GET['id'])) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : false;
$isIdValid = false;
$Db = new Database(__FILE__, $PDO, 'loan');
$where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
if ($result = $Db->select(__LINE__, [], $where)) $isIdValid = true;
if (!$id || !$isIdValid) {
    header('Location: ' . URLBACKEND . "home");
    exit;
}

$loanInfo = $result[0];
$product = Functions::getLoanProducts()[$loanInfo['product']];
$profileInfo = $MyUser->getProfileInfo($loanInfo['profile_id']);

$loanAccoutNo = Functions::encodeLoadId($id);
$profileAccoutNo = Functions::getAcctNo($PDO, $loanInfo['profile_id']);
$title = SITENAME . " {$product['market']}  $loanAccoutNo";
$pageName = "{$product['market']}  $loanAccoutNo";
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

if ($profileInfo[$userType]['type_name'] == $individual) $myType = $individual;
if ($profileInfo[$userType]['type_name'] == $msme) $myType = $msme;

$gId = $gurantorsName = $gurantorsPhone = $gurantorsEmail = "";
if ($profileInfo[$myType]['guarantor_id']) {
    $gId = $MyUser->getProfileIdFrmUserId($profileInfo[$myType]['guarantor_id'], $guarantor);
    $gInfo = $MyUser->getProfileInfo($gId);
    $gurantorsName = "{$gInfo[$profile]['firstname']} {$gInfo[$profile]['middlename']} {$gInfo[$profile]['surname']}";
    $gurantorsPhone = "{$gInfo[$guarantor]['phone']}";
    $gurantorsEmail = "{$gInfo[$guarantor]['email']}";
}

$collateral = "";
$businessName = "";
if ($myType == $msme) {
    if ($loanInfo['collateral_text'] && $loanInfo['collateral_file']) {
        $link = Functions::ASSET_IMG_URLBACKEND . "collateral/{$loanInfo['collateral_file']}";
        $linkText = $loanInfo['collateral_text'];
    } else {
        $link = Functions::ASSET_IMG_URLBACKEND . "collateral/{$profileInfo[$msme]['collateral_file']}";
        $linkText = $profileInfo[$msme]['collateral_text'];
    }
    $collateral = "<a target='_blank' href='$link'><u>$linkText</u></a>";
    $businessName = $profileInfo[$msme]['company'];
    $guarantorsCaption =  "Promoters";
}

if ($myType == $individual) {
    $guarantorsCaption =  "Guarantors";
}

$approver = "";
if ($loanInfo['staff_profile_id']) {
    $profileStaffInfo = $MyUser->getProfileInfo($loanInfo['staff_profile_id']);
    $approver = "{$profileStaffInfo[$profile]['firstname']} {$profileStaffInfo[$profile]['middlename']} 
    {$profileStaffInfo[$profile]['surname']} " . Functions::getAcctNo($PDO, $loanInfo['staff_profile_id']);
}

$tr = "";
$Transaction = new Transactions($PDO);
if ($statment = $Transaction->getStatement($id)) {
    foreach ($statment as $aStatement) {
        $credit = ($aStatement['type'] == 'credit') ? number_format($aStatement['amount'], 2) : "";
        $debit = ($aStatement['type'] == 'debit') ? number_format($aStatement['amount'], 2) : "";
        $tr .= "
            <tr>
                <td>T{$aStatement['id']}</td>
                <td>{$aStatement['description']}</td>
                <td>$debit</td>
                <td>$credit</td>
                <td>" . number_format($aStatement['balance'], 2) . "</td>
                <td>" . date("jS F Y", strtotime($aStatement['transaction_date'])) . "</td>
            </tr>
        ";
    }
}

$balance = $Transaction->getBalance($id);
$max = abs($Transaction->getBalance($id));
$disabled = ($loanInfo['status'] == 'approved') ? "" : "disabled";
$rejectionReason = ($loanInfo['status'] == 'rejected') ?
    $loanInfo['reject_reason'] . "<br/>" : "";
