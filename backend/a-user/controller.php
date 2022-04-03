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

$individual = MyUsers::INDIVIDUAL['name'];
$userType = Users::USERTYPE_TABLE;
$msme = MyUsers::MSME['name'];
$profile = Users::TABLE_PROFILE;
$guarantor = MyUsers::GUARANTOR['name'];

$MyUser = new MyUsers($PDO);
$id = (isset($_GET['id'])) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : false;
if (!$id || !$MyUser->isProfileIdValid($id)) {
    header('Location: ' . URLBACKEND . "home");
    exit;
}

$info = $MyUser->getProfileInfo($id);
if (!($info[$userType]['type_name'] == $individual || $info[$userType]['type_name'] == $msme)) {
    header('Location: ' . URLBACKEND . "home");
    exit;
}

if ($info[$userType]['type_name'] == $individual) $myType = $individual;
if ($info[$userType]['type_name'] == $msme) $myType = $msme;

$accoutNo = Functions::getAcctNo($PDO, $id);
$title = SITENAME . " {$info['profile']['firstname']} {$info['profile']['middlename']} {$info['profile']['surname']} $accoutNo";
$pageName = ($myType == $individual) ?
    "{$info['profile']['firstname']} {$info['profile']['middlename']} {$info['profile']['surname']}" :
    $info[$msme]['company'];
$pageName .= " $accoutNo";
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

$company = ($info[$userType]['type_name'] == $individual) ?
    $info[$individual]['company'] : $info[$msme]['company'];

$gId = $gurantorsName = $gurantorsPhone = $gurantorsEmail = $additionalGurantors = "";
if ($info[$myType]['guarantor_id']) {
    $gId = $MyUser->getProfileIdFrmUserId($info[$myType]['guarantor_id'], $guarantor);
    $gInfo = $MyUser->getProfileInfo($gId);
    $gurantorsName = "{$gInfo[$profile]['firstname']} {$gInfo[$profile]['middlename']} {$gInfo[$profile]['surname']}";
    $gurantorsPhone = "{$gInfo[$guarantor]['phone']}";
    $gurantorsEmail = "{$gInfo[$guarantor]['email']}";

    if (isset($info[$myType]['guarantor_id_others']) && $info[$myType]['guarantor_id_others']) {
        foreach (json_decode($info[$myType]['guarantor_id_others'], true) as $anOtherGuarantorId) {
            $gOtherId = $MyUser->getProfileIdFrmUserId($anOtherGuarantorId, $guarantor);
            $gInfo = $MyUser->getProfileInfo($gOtherId);
            $anOtherGuarantorName = "{$gInfo[$profile]['firstname']} {$gInfo[$profile]['middlename']} {$gInfo[$profile]['surname']}";
            $additionalGurantors .= "<p><a href='" . URLBACKEND . "a-guarantor/?id=$gOtherId'><u>$anOtherGuarantorName </u></a></p>";
        }
    }
}

if ($info[$userType]['type_name'] == $individual) $arr = $individual;
if ($info[$userType]['type_name'] == $msme) $arr = $msme;
$businessName = "";
if ($myType == $msme) $businessName = $info[$msme]['company'];

$dob = "";
if ($info[$arr]['dob']) $dob = date("jS F Y", strtotime($info[$arr]['dob']));

$paySlipDoc = "";
if (($info[$userType]['type_name'] == $individual)) {
    if ($info[$individual]['pay_slip']) {
        $paySlipDoc = "
            <a target='_blank' href='" . Functions::ASSET_IMG_URLBACKEND . "pay-slip/{$info[$individual]['pay_slip']}'>
                <u>view</u>
            </a>";
    }
}

if ($arr == $individual) {
    $idCard = ($info[$arr]['id_card']) ? $info[$arr]['id_card'] : "";
} else {
    $idCard = ($info[$arr]['id_card_type']) ? $info[$arr]['id_card_type'] : "";
}

$bankDetails = "";
if ($info[$arr]['bank_details']) {
    if ($info[$arr]['bank_details']) {
        $bankArray = json_decode($info[$arr]['bank_details'], true);
        $accountName = (isset($bankArray['accountName'])) ? "{$bankArray['accountName']}<br/>" : "";
        $accountType = (isset($bankArray['accountType'])) ? "{$bankArray['accountType']}<br/>" : "";
        $bankDetails = "
            $accountName
            {$bankArray['accountNo']}<br/>
            {$bankArray['bank']}<br/>
            $accountType
        ";
    }
}

$sourceIncome = "";
if (($myType == $individual && $info[$individual]['employment_letter']) ||
    ($myType == $msme && $info[$msme]['income_source_text'])
) {
    if ($myType == $individual && $info[$individual]['employment_letter']) {
        $incomeSourceName = "Employment Letter";
        $incomeSourceDoc = Functions::ASSET_IMG_URLBACKEND . "employment-letter/{$info[$individual]['employment_letter']}";
    }
    if ($myType == $msme && $info[$msme]['income_source_text']) {
        $incomeSourceName = $info[$msme]['income_source_text'];
        if ($info[$msme]['income_source_file'])
            $incomeSourceDoc = Functions::ASSET_IMG_URLBACKEND . "source-income/{$info[$msme]['income_source_file']}";
    }
    $sourceIncome = "
        $incomeSourceName
        <a target='_blank' href='$incomeSourceDoc'><u>view</u></a>
    ";
}

$assetCollateral = $bizReg = $tin = $natureBusiness = $companyAddres = $companyPhone =
    $companyBankStatment = $companyBankStatmentII = $companyFinances = "";
if ($info[$userType]['type_name'] == $msme) {
    $dob = "";
    if ($myType == $msme && $info[$msme]['collateral_text']) {
        if ($info[$msme]['collateral_file'])
            $assetDoc = Functions::ASSET_IMG_URLBACKEND . "collateral/{$info[$msme]['collateral_file']}";
        $assetCollateral = "
        {$info[$msme]['collateral_text']}
        <a target='_blank' href='$assetDoc'><u>view</u></a>
    ";
    }

    if ($myType == $msme && $info[$msme]['company_cac_doc']) {
        $doc = Functions::ASSET_IMG_URLBACKEND . "cac/{$info[$msme]['company_cac_doc']}";
        $bizReg = "<a target='_blank' href='$doc'><u>view</u></a>";
    }

    $tin = ($info[$msme]['tin']) ? $info[$msme]['tin'] : "";
    $natureBusiness = ($info[$msme]['nature_business']) ? $info[$msme]['nature_business'] : "";
    $companyAddres = ($info[$msme]['company_address']) ? "{$info[$msme]['company_address']}<br/>" : "";
    $companyPhone = ($info[$msme]['company_phone']) ? $info[$msme]['company_phone'] : "";
    $companyBankStatment = ($info[$msme]['bank_statement']) ?
        "<a target='_blank' href='" . Functions::ASSET_IMG_URLBACKEND . "source-income/{$info[$msme]['bank_statement']}'><u>view I</u></a>" : "";
    $companyBankStatmentII = ($info[$msme]['bank_statement2']) ?
        "<a target='_blank' href='" . Functions::ASSET_IMG_URLBACKEND . "source-income/{$info[$msme]['bank_statement2']}'><u>view II</u></a>" : "";
    $companyFinances = ($info[$msme]['financial_statement']) ?
        "<a target='_blank' href='" . Functions::ASSET_IMG_URLBACKEND . "source-income/{$info[$msme]['financial_statement']}'><u>view</u></a>" : "";

    $guarantorsCaption =  "Promoters";
}

$employerAddress = $employerName = $bvn = $idCardUrl = $idCardExpirationDate = "";
if ($info[$userType]['type_name'] == $individual) {
    $employerName = $info[$individual]['company'];
    $employerAddress = $info[$individual]['company_address'];
    $idCardExpirationDate = $info[$individual]['id_card_expiry_date'];
    $idCardUrl = Functions::ASSET_IMG_URLBACKEND . "id-card/{$info[$individual]['id_card_doc']}";
    $bvn = $info[$individual]['bvn'];
    $guarantorsCaption =  "Guarantors";
}

$loansLi = "
    <li>
        <p> No loan yet</p>
    </li>
";
$Db = new Database(__FILE__, $PDO, 'loan');
$where = [
    ['column' => 'profile_id', 'comparsion' => '=', 'bindAbleValue' => $id, 'logic' => 'AND'],
    ['column' => 'status', 'comparsion' => '<>', 'bindAbleValue' => 'applied', 'logic' => 'AND'],
    ['column' => 'status', 'comparsion' => '<>', 'bindAbleValue' => 'wip']
];
if ($result = $Db->select(__LINE__, [], $where)) {
    $Transaction = new Transactions($PDO);
    $loansLi = "";
    foreach ($result as $aResult) {
        $balance = $Transaction->getBalance($aResult['id']);
        $loansLi .= "
            <li>
                <p>                                        
                    " . Functions::getLoanProducts()[$aResult['product']]['market'] . "
                    " . Functions::encodeLoadId($aResult['id']) . "                    
                </p>
                <p>" . number_format($balance, 2) . "</p>
                <small>{$aResult['status']}</small> <br/>
                <a href='" . URLBACKEND . "a-loan/?id={$aResult['id']}' class='btn btn-primary btn-sm'>view</a>
            </li>";
    }
}
