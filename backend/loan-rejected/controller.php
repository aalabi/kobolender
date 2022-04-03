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

$title = SITENAME . " Rejected Loan";
$pageName = "Rejected Loan";
$sideBar = $Tag->createSideMenu(['loans', 'decline'], $staffId);
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

$tr = "";
$Db = new Database(__FILE__, $PDO, 'loan');
$User = new Users($PDO);
$MyUser = new MyUsers($PDO);

$individual = MyUsers::INDIVIDUAL['name'];
$userType = Users::USERTYPE_TABLE;
$msme = MyUsers::MSME['name'];
$profile = Users::TABLE_PROFILE;
$guarantor = MyUsers::GUARANTOR['name'];
$login = Authentication::TABLE;

$where = [['column' => 'status', 'comparsion' => '=', 'bindAbleValue' => 'rejected']];
$result = $Db->select(__LINE__, [], $where);

if ($result) {
    $kanter = 0;
    foreach ($result as $aResult) {
        $info = $MyUser->getProfileInfo($aResult['profile_id']);
        $accountNo = Functions::getAcctNo($PDO, $aResult['profile_id']);
        $gAccountNo = $gInfo[$profile]['firstname'] = $gInfo[$profile]['middlename'] =
            $gInfo[$profile]['surname'] = "";
        if ($info[$userType]['type_name'] == $individual) $gUserId = $info[$individual]['guarantor_id'];
        if ($info[$userType]['type_name'] == $msme) $gUserId = $info[$msme]['guarantor_id'];
        if ($gUserId) {
            $gId = $MyUser->getProfileIdFrmUserId($gUserId, $guarantor);
            $gInfo = $MyUser->getProfileInfo($gId);
            $gAccountNo = Functions::getAcctNo($PDO, $gId);
        }
        $sInfo = $MyUser->getProfileInfo($aResult['staff_profile_id']);
        $sAccountNo = Functions::getAcctNo($PDO, $aResult['staff_profile_id']);
        $tr .= "
            <tr>
                <td>" . ++$kanter . "</td>
                <td>
                    <a href='" . URLBACKEND . "a-loan/?id={$aResult['id']}'>
                        <u>" . Functions::encodeLoadId($aResult['id']) . "</u>
                    </a><br/>
                    " . ucwords(Functions::getLoanProducts()[$aResult['product']]['market']) . "<br/>
                    " . number_format($aResult['amount'], 2) . "<br/>
                    " . date("jS F Y h:m a", strtotime($aResult['created_at'])) . "

                </td>
                <td>
                    <a href='" . URLBACKEND . "a-user/?id={$aResult['profile_id']}'>
                        {$info[$profile]['firstname']} 
                        {$info[$profile]['middlename']}
                        {$info[$profile]['surname']}
                    </a>
                    <br/>
                    $accountNo <small>{$info[$login]['status']}</small>                                       
                </td>
                <td>
                    <a href='" . URLBACKEND . "a-user/?id={$aResult['profile_id']}'>
                        {$gInfo[$profile]['firstname']} 
                        {$gInfo[$profile]['middlename']}
                        {$gInfo[$profile]['surname']}
                    </a>
                    <br/>
                    $gAccountNo
                </td>
                <td>
                    {$sInfo[$profile]['firstname']} 
                    {$sInfo[$profile]['middlename']}
                    {$sInfo[$profile]['surname']}
                    <br/>
                    $sAccountNo
                    <br/>
                    " . date("jS F Y h:m a", strtotime($aResult['update_at'])) . "
                </td>
            </tr>    
        ";
    }
}
