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

$title = SITENAME . " My Loans";
$pageName = "My Loans";
$sideBar = $Tag->createSideMenu(['loans', 'my loans'], $customerId);
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

$profileId = $customerInfo['profile']['id'];
$info = $MyUser->getProfileInfo($profileId);
$individual = MyUsers::INDIVIDUAL['name'];
$msme = MyUsers::MSME['name'];
$userType = Users::USERTYPE_TABLE;
$profile = Users::TABLE_PROFILE;
$guarantor = MyUsers::GUARANTOR['name'];
$login = Authentication::TABLE;
$profileId = $customerInfo['profile']['id'];
$myType = ($info[$userType]['type_name'] == $individual) ? $individual : $msme;

$where = [['column' => 'profile_id', 'comparsion' => '=', 'bindAbleValue' => $profileId]];
$result = $Db->select(__LINE__, [], $where);

if ($result) {
    $kanter = 0;
    $Transaction = new Transactions($PDO);
    foreach ($result as $aResult) {
        $info = $MyUser->getProfileInfo($aResult['profile_id']);
        $accountNo = Functions::getAcctNo($PDO, $aResult['profile_id']);
        $gAccountNo = $gInfo[$profile]['firstname'] = $gInfo[$profile]['middlename'] =
            $gInfo[$profile]['surname'] = "";
        $gUserId = $info[$myType]['guarantor_id'];
        if ($gUserId) {
            $gId = $MyUser->getProfileIdFrmUserId($gUserId, $guarantor);
            $gInfo = $MyUser->getProfileInfo($gId);
            $gAccountNo = Functions::getAcctNo($PDO, $gId);
        }
        if ($aResult['staff_profile_id']) {
            $sInfo = $MyUser->getProfileInfo($aResult['staff_profile_id']);
            $sAccountNo = Functions::getAcctNo($PDO, $aResult['staff_profile_id']);
        }
        $tr .= "
            <tr>
                <td>" . ++$kanter . "</td>
                <td>
                    <a href='" . URLBACKEND . "a-loan-customer/?id={$aResult['id']}'>
                        <u>" . Functions::encodeLoadId($aResult['id']) . "</u>
                    </a><br/>
                    " . ucwords(Functions::getLoanProducts()[$aResult['product']]['market']) . "<br/>
                    applied: " . number_format($aResult['amount'], 2) . "<br/>
                    approved: " . number_format($aResult['approved_amount'], 2) . "<br/>
                    balance: " . number_format($Transaction->getBalance($aResult['id']), 2) . "<br/>
                    " . date("jS F Y h:m a", strtotime($aResult['created_at'])) . "

                </td>
                <td>  
                    {$gInfo[$profile]['firstname']} 
                    {$gInfo[$profile]['middlename']}
                    {$gInfo[$profile]['surname']}
                    <br/>
                    $gAccountNo
                </td>
                <td>
                " . ucwords($aResult['status']) . "
                </td>
                <td>
                 
                 <button type='submit'  class='btn btn-primary btn-sm'>
                 <a  style='color:white' href='" . URLBACKEND . "a-loan-customer/?id={$aResult['id']}'> View </a>
                 </button> 
                </td>
            </tr>    
        ";
    }
}
