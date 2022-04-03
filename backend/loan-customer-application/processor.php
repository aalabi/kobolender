<?php
require_once dirname(__FILE__, 2) . "/connection.php";
$Authentication = new Authentication($PDO);
$Authentication->keyToPage();
$User = new Users($PDO);
$MyUsers = new MyUsers($PDO);
$customerId = $_SESSION[Authentication::SESSION_NAME]['id'];
$customerInfo = $User->getInfo($customerId);
$Authentication->pageAccessor([MyUsers::INDIVIDUAL['name'], MyUsers::MSME['name']], $customerInfo['user_type']['type_name']);

if (
    isset($_POST[Authentication::SESSION_CSRF_TOKEN])
    && Authentication::checkCSRFToken($_POST[Authentication::SESSION_CSRF_TOKEN])
) {

    $errors = [];
    $permitFiles = ['pdf', 'png', 'jpg', 'jpeg'];
    $maxSize = 1200000;

    //validate email
    $productId = filter_var(trim($_POST['loanType']), FILTER_VALIDATE_INT);
    $loanAmount = filter_var(trim($_POST['loanAmount']), FILTER_VALIDATE_FLOAT);
    $loanPurpose = filter_var(trim($_POST['loanPurpose']), FILTER_SANITIZE_STRING);
    $borrowBank = filter_var(trim($_POST['borrowerBank']), FILTER_SANITIZE_STRING);
    $amountOwn = filter_var(trim($_POST['totalDebt']), FILTER_VALIDATE_FLOAT);
    $mthlyRepymtAmt = filter_var(trim($_POST['mnthlyRepymt']), FILTER_VALIDATE_FLOAT);
    $directDebitAmt = filter_var(trim($_POST['directDebit']), FILTER_VALIDATE_FLOAT);
    $pymtObligation = filter_var(trim($_POST['obligation']), FILTER_VALIDATE_FLOAT);
    $loanRepymtPeriod = filter_var(trim($_POST['repymtPeriod']), FILTER_VALIDATE_INT);
    $sourceOfPayment = filter_var(trim($_POST['repymtSource']), FILTER_SANITIZE_STRING);
    $loanCollateral = filter_var(trim($_POST['collateral']), FILTER_SANITIZE_STRING);
    $loanCollateralFile = $_FILES['collateralFile'];

    //check for validation
    if (!$productId) {
        $errors[] = "invalid loan product";
    } else {
        if (!isset(Functions::getLoanProducts()[$productId])) $errors[] = "invalid loan type";
    }
    if (!$loanAmount) $errors[] = "invalid loan amount";
    if (!$loanPurpose) $errors[] = "invalid loan purpose";
    if (!$loanRepymtPeriod) $errors[] = "invalid repayment period";
    if (!$sourceOfPayment) $errors[] = "invalid source of repayment";
    if (!$loanCollateral) $errors[] = "invalid collateral";
    $fileError = Functions::PHPUploadError($loanCollateralFile);
    if ($fileError) {
        $errors[] = "invalid collateral file " . $fileError;
    } else {
        $fileError = Functions::developerUploadError($loanCollateralFile, $permitFiles, $maxSize);
        if ($fileError) $errors[] = "invalid collateral file " . $fileError;
    }

    //redirect on error
    $responseURLBACKEND = "Location: .";
    if ($errors) {
        Tag::setResponse(
            'Invalid Data Input',
            $errors,
            Tag::RESPONSE_NEGATIVE
        );
        header($responseURLBACKEND);
        exit;
    }

    //copy file
    $loanCollateralDoc = "msmecolldoc" . time() . "$customerId." . pathinfo($loanCollateralFile['name'], PATHINFO_EXTENSION);
    copy($loanCollateralFile['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "collateral/$loanCollateralDoc");

    //book loans
    $columns = [
        'profile_id' => ['colValue' => $customerInfo['profile']['id'], 'isFunction' => false, 'isBindAble' => true],
        'product' => ['colValue' => $productId, 'isFunction' => false, 'isBindAble' => true],
        'amount' => ['colValue' => $loanAmount, 'isFunction' => false, 'isBindAble' => true],
        'repymt_source' => ['colValue' => $sourceOfPayment, 'isFunction' => false, 'isBindAble' => true],
        'repayment_period' => ['colValue' => $loanRepymtPeriod, 'isFunction' => false, 'isBindAble' => true],
        'purpose' => ['colValue' => $loanPurpose, 'isFunction' => false, 'isBindAble' => true],
        'other_bank_own' => ['colValue' => $borrowBank, 'isFunction' => false, 'isBindAble' => true],
        'status' => ['colValue' => 'applied', 'isFunction' => false, 'isBindAble' => true],
        'amt_own' => ['colValue' => $amountOwn ? $amountOwn : 0, 'isFunction' => false, 'isBindAble' => true],
        'mthly_repymt' => ['colValue' => $mthlyRepymtAmt ? $mthlyRepymtAmt : 0, 'isFunction' => false, 'isBindAble' => true],
        'direct_debit_amt' => ['colValue' => $directDebitAmt ? $directDebitAmt : 0, 'isFunction' => false, 'isBindAble' => true],
        'outstand_obligation' => ['colValue' => $pymtObligation ? $pymtObligation : 0, 'isFunction' => false, 'isBindAble' => true],
        'collateral_text' => ['colValue' => $loanCollateral, 'isFunction' => false, 'isBindAble' => true],
        'collateral_file' => ['colValue' => $loanCollateralDoc, 'isFunction' => false, 'isBindAble' => true]
    ];
    $Db = new Database(__FILE__, $PDO, 'loan');
    $Db->setTable("loan");
    $Db->insert(__LINE__, $columns);

    $responseTitle = 'Operation Successful';
    $responseMessage = 'Loan application successful submitted.';
    $responseColor = Tag::RESPONSE_POSITIVE;
    Tag::setResponse($responseTitle, [$responseMessage], $responseColor);

    //redirect on completion
    header($responseURLBACKEND);
    exit();
} else {
    new ErrorLog('Suspected CSRF Attack', __FILE__, __LINE__);
    Tag::setResponse(
        'Expired Session',
        ['Your session has expired, please repeat the process again'],
        Tag::RESPONSE_NEGATIVE
    );
    header("Location: .");
    exit();
}
