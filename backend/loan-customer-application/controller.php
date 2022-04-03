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
$Authentication->pageAccessor([MyUsers::INDIVIDUAL['name'], MyUsers::MSME['name']], $customerInfo['user_type']['type_name']);
$loggerName = "{$customerInfo['profile']['surname']} {$customerInfo['profile']['firstname']}";
$profileImage = URLBACKEND . "asset/image/profile-image/{$customerInfo['profile']['profile_image']}";

$title = SITENAME . " Loan Application";
$pageName = "Loan Application";
$sideBar = $Tag->createSideMenu(['loans', 'apply'], $customerId);
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

//loan type options
$loanTypeOptions = "";
foreach (Functions::getLoanProducts() as $aLoanProduct) {
    if ($aLoanProduct['type'] == $customerInfo[$userType]['type_name'])
        $loanTypeOptions .= "<option value='{$aLoanProduct['no']}'>{$aLoanProduct['market']}</option>";
}

$disabled = "";
//approved, wip, applied
$Db = new Database(__FILE__, $PDO, 'loan');
$sql = "SELECT id FROM loan 
    WHERE profile_id = :id AND (status = 'applied' OR status ='approved' OR status ='wip')";
if ($Db->queryStatment(__LINE__, $sql, ['id' => $customerInfo[$profile]['id']])['data']) {
    $disabled = 'disabled';
}
