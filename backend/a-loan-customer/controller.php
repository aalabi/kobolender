<?php
require_once dirname(__FILE__, 2) . "/connection.php";
$Authentication = new Authentication($PDO);
$loggerName = "";
$profileImage = URLBACKEND . "asset/image/profile-image/default-profile.png";
$Authentication->keyToPage();
$Tag = new MyTag($PDO);
$User = new Users($PDO);
$MyUser = new MyUsers($PDO);
$customerId = $_SESSION[Authentication::SESSION_NAME]['id'];
$customerInfo = $User->getInfo($customerId);
$Authentication->pageAccessor([MyUsers::INDIVIDUAL['name'], MyUsers::MSME['name']], $customerInfo['user_type']['type_name']);
$loggerName = "{$customerInfo['profile']['surname']} {$customerInfo['profile']['firstname']}";
$profileImage = URLBACKEND . "asset/image/profile-image/{$customerInfo['profile']['profile_image']}";

$individual = MyUsers::INDIVIDUAL['name'];
$userType = Users::USERTYPE_TABLE;
$profile = Users::TABLE_PROFILE;
$guarantor = MyUsers::GUARANTOR['name'];

$id = (isset($_GET['id'])) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : false;
$isIdValid = false;
$Db = new Database(__FILE__, $PDO, 'loan');
$where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
if ($result = $Db->select(__LINE__, [], $where)) $isIdValid = true;
if (!$id || !$isIdValid) {
    header('Location: ' . URLBACKEND . "home-users");
    exit;
}

$loanInfo = $result[0];
$product = Functions::getLoanProducts()[$loanInfo['product']];
$profileInfo = $MyUser->getProfileInfo($loanInfo['profile_id']);

$loanAccoutNo = Functions::encodeLoadId($id);
$profileAccoutNo = Functions::getAcctNo($PDO, $loanInfo['profile_id']);
$title = SITENAME . " {$product['market']}  $loanAccoutNo";
$pageName = "{$product['market']}  $loanAccoutNo";

$sideBar = $Tag->createSideMenu([], $customerId);
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

$msme = MyUsers::MSME['name'];
$userType = Users::USERTYPE_TABLE;
$myType = ($profileInfo[$userType]['type_name'] == $individual) ? $individual : $msme;

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

$gId = $gurantorsName = $gurantorsPhone = $gurantorsEmail = "";
if ($profileInfo[$myType]['guarantor_id']) {
    $gId = $MyUser->getProfileIdFrmUserId($profileInfo[$myType]['guarantor_id'], $guarantor);
    $gInfo = $MyUser->getProfileInfo($gId);
    $gurantorsName = "{$gInfo[$profile]['firstname']} {$gInfo[$profile]['middlename']} {$gInfo[$profile]['surname']}";
    $gurantorsPhone = "{$gInfo[$guarantor]['phone']}";
    $gurantorsEmail = "{$gInfo[$guarantor]['email']}";
}

$approver = "";
if ($loanInfo['staff_profile_id']) {
    $staffInfo = $MyUser->getProfileInfo($loanInfo['staff_profile_id']);
    $approver = "{$staffInfo[$profile]['firstname']} {$staffInfo[$profile]['middlename']} 
    {$staffInfo[$profile]['surname']} " . Functions::getAcctNo($PDO, $loanInfo['staff_profile_id']);
}

$Db = new Database(__FILE__, $PDO, 'gateway_log');
if (isset($_GET['status']) && isset($_GET['tx_ref']) && isset($_GET['transaction_id'])) {
    $amount = urldecode($_GET['amount']);
    $status = filter_var($_GET['status'], FILTER_SANITIZE_STRING);
    $trnRef = filter_var($_GET['tx_ref'], FILTER_SANITIZE_STRING);
    $flutterId = filter_var($_GET['transaction_id'], FILTER_SANITIZE_STRING);
    $generateResponse = false;
    $responseMessage = $responseCode = "";

    $url = "https://api.flutterwave.com/v3/transactions/" . urldecode($_GET['transaction_id']) . "/verify";
    $curl = curl_init();
    $curlSetOpt = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_HTTPHEADER => [
            "accept: application/json",
            "authorization: Bearer " . PRIVATE_KEY,
            "cache-control: no-cache"
        ]
    ];
    if (DEVELOPMENT) $curlSetOpt[CURLOPT_SSL_VERIFYPEER] = false;
    curl_setopt_array($curl, $curlSetOpt);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
        $generateResponse = true;
        $responseMessage = $err;
        $responseCode = MyTag::RESPONSE_NEGATIVE;
    } else {
        $generateResponse = true;

        $where = [['column' => 'tx_ref', 'comparsion' => '=', 'bindAbleValue' => $trnRef]];
        $result = $Db->select(__LINE__, ['status'], $where);
        if ($result && $result[0]['status'] != 'successful') {
            $response = json_decode($response, true);
            if ($response['status'] == 'success' && $response['data']['currency'] == 'NGN' && $response['data']['status'] == 'successful') {
                //update payment gateway
                $columns = [
                    'status' => ['colValue' => $response['data']['status'], 'isFunction' => false, 'isBindAble' => true],
                    'amount' => ['colValue' => $amount, 'isFunction' => false, 'isBindAble' => true]
                ];
                $where = [['column' => 'tx_ref', 'comparsion' => '=', 'bindAbleValue' => $trnRef]];
                $Db->update(__LINE__, $columns, $where);

                //post payment into transaction
                $Transaction = new Transactions($PDO);
                $Transaction->post($id, $loanInfo['profile_id'], "Loan Repayment $flutterId", $amount, 'credit');

                //generate response message
                $responseMessage = "The loan repayment was successfully done";
                $responseCode = MyTag::RESPONSE_POSITIVE;

                //check to liquate the loan
                $balance = $Transaction->getBalance($id);
                if ($balance >= 0) {
                    $Db = new Database(__FILE__, $PDO, 'loan');
                    $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $loanInfo['id']]];
                    $column = ['status' => ['colValue' => 'liquated', 'isFunction' => false, 'isBindAble' => true],];
                    $Db->update(__LINE__, $column, $where);
                    $loanInfo['status'] = 'liquated';
                }
            } else {
                $responseMessage = ($response) ? $response['message'] : "Payment Gateway Error, Refresh the browser again";
                $responseCode = MyTag::RESPONSE_NEGATIVE;
            }
        }
    }

    if ($generateResponse)
        $responseOperation = $Tag->responseTag("Payment Status", $responseMessage, $responseCode);
}

//generate transaction reference
$trnRef = $profileAccoutNo . $id . time();
$column = [
    'tx_ref' => ['colValue' => $trnRef, 'isFunction' => false, 'isBindAble' => true],
    'loan_id' => ['colValue' => $id, 'isFunction' => false, 'isBindAble' => true]
];
$Db = new Database(__FILE__, $PDO, 'gateway_log');
$Db->insert(__LINE__, $column);

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
$max = abs($balance);
$disabled = ($loanInfo['status'] == 'approved') ? "" : "disabled";
$rejectionReason = ($loanInfo['status'] == 'rejected') ?
    $loanInfo['reject_reason'] . "<br/>" : "";
