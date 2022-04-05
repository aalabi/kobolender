<?php
require_once "backend/connection.php";
$User = new Users($PDO);
$MyUsers = new MyUsers($PDO);

if (
    isset($_POST[Authentication::SESSION_CSRF_TOKEN])
    && Authentication::checkCSRFToken($_POST[Authentication::SESSION_CSRF_TOKEN])
) {
    //get inputted data
    $errors = [];
    $permitAllFiles = ['pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg'];
    $permitImageFiles = ['png', 'jpg', 'jpeg'];
    $maxSize = 1200000;

    $productId = filter_var(trim($_POST['productId']), FILTER_VALIDATE_INT);
    $surname = "";
    $firstname = "";
    $middlename = "";
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $phone = "";
    $dob = "NOW()";
    $idCardType = "";
    $idCardFile = "";
    $passport = "";

    $companyName = filter_var(trim($_POST['company_name']), FILTER_SANITIZE_STRING);
    $companyTIN = filter_var(trim($_POST['company_tin']), FILTER_SANITIZE_STRING);
    $companyCACFile = $_FILES['company_reg_doc'];
    $companyAddress = filter_var(trim($_POST['company_address']), FILTER_SANITIZE_STRING);
    $natureBusiness = filter_var(trim($_POST['nature_of_business']), FILTER_SANITIZE_STRING);
    $companyPhone = filter_var(trim($_POST['company_phone']), FILTER_SANITIZE_STRING);
    $companyBankFile = $_FILES['bank_statement'];
    $companyBankFile2 = $_FILES['bank_statement2'];
    $companyFinanceFile = $_FILES['company_finances'];
    $bank = filter_var(trim($_POST['bank']), FILTER_SANITIZE_STRING);
    $accountNo = filter_var(trim($_POST['account_no']), FILTER_SANITIZE_STRING);

    $borrowBank = filter_var(trim($_POST['lending_institute']), FILTER_SANITIZE_STRING);
    $amountOwn = filter_var(trim($_POST['amount_currently_own']), FILTER_VALIDATE_FLOAT);
    $mthlyRepymtAmt = filter_var(trim($_POST['total_monthly_payment']), FILTER_VALIDATE_FLOAT);
    $directDebitAmt = filter_var(trim($_POST['directDebit']), FILTER_VALIDATE_FLOAT);
    $pymtObligation = filter_var(trim($_POST['dueObligation']), FILTER_VALIDATE_FLOAT);

    $loanAmount = filter_var(trim($_POST['loan_amount']), FILTER_VALIDATE_FLOAT);
    $loanPurpose = filter_var(trim($_POST['loan_purpose']), FILTER_SANITIZE_STRING);
    $loanCollateral = filter_var(trim($_POST['collateral']), FILTER_SANITIZE_STRING);
    $loanCollateralFile = $_FILES['collateral_upload'];
    $loanRepymtPeriod = filter_var(trim($_POST['repayment_period']), FILTER_VALIDATE_INT);
    $sourceOfPayment = filter_var(trim($_POST['source_of_repayment']), FILTER_SANITIZE_STRING);

    $promoterName = filter_var(trim($_POST['promoter_name']), FILTER_SANITIZE_STRING);
    $promoterNINBVN = filter_var(trim($_POST['bvn_nin']), FILTER_SANITIZE_STRING);
    $promoterNINBVNNo = filter_var(trim($_POST['bvn_nin_no']), FILTER_SANITIZE_STRING);
    $promoterStatementFile = $_FILES['promoter_bank_statement'];
    $promoterGuaranteeFile = $_FILES['letter_of_guarantor'];

    $otherPromoters = [];
    if (isset($_POST['promoter_name_col'])) {
        $count = count($_POST['promoter_name_col']);
        $fileData = $_FILES['promoter_bank_statement_col']['name'];
        $kanter = 0;
        foreach ($fileData  as $aFileName) {
            $otherBankStatementFILES[$kanter] = [
                "type" => $_FILES['promoter_bank_statement_col']['type'][$kanter],
                "name" =>  $_FILES['promoter_bank_statement_col']['name'][$kanter],
                "tmp_name" => $_FILES['promoter_bank_statement_col']["tmp_name"][$kanter],
                'error' =>  $_FILES['promoter_bank_statement_col']["error"][$kanter],
                'size' =>  $_FILES['promoter_bank_statement_col']["size"][$kanter],
            ];
            $otherLetterFILES[$kanter] = [
                "type" => $_FILES['letter_of_guarantor_col']['type'][$kanter],
                "name" =>  $_FILES['letter_of_guarantor_col']['name'][$kanter],
                "tmp_name" => $_FILES['letter_of_guarantor_col']["tmp_name"][$kanter],
                'error' =>  $_FILES['letter_of_guarantor_col']["error"][$kanter],
                'size' =>  $_FILES['letter_of_guarantor_col']["size"][$kanter],
            ];
            ++$kanter;
        }
        for ($i = 0; $i < $count; $i++) {
            $otherPromoters[$i] = [
                'name' => filter_var(trim($_POST['promoter_name_col'][$i]), FILTER_SANITIZE_STRING),
                'bvn' => filter_var(trim($_POST['bvn_nin_no_col'][$i]), FILTER_SANITIZE_STRING),
                'bankStatment' => $otherBankStatementFILES[$i],
                'guarantorLetter' => $otherLetterFILES[$i],
            ];
        }
    }

    //check for validation
    if (!$productId) {
        $errors[] = "invalid loan product";
    } else {
        if (!isset(Functions::getLoanProducts()[$productId])) $errors[] = "invalid loan product";
    }
    if (!$email) {
        $errors[] = "invalid email";
    } else {
        $Db = new Database(__FILE__, $PDO, Authentication::TABLE);
        if ($Db->isDataInColumn(__LINE__, $email, 'email')) $errors[] = "email associated with another user";
    }

    if (!$companyName)  $errors[] = "invalid business name";
    if (!$companyTIN)  $errors[] = "invalid TIN";
    if (!$bank)  $errors[] = "invalid bank";
    if (!$accountNo)  $errors[] = "invalid account no";
    $fileError = Functions::PHPUploadError($companyCACFile);
    if ($fileError) {
        $errors[] = "Registration Doc " . $fileError;
    } else {
        $fileError = Functions::developerUploadError($companyCACFile, $permitAllFiles, $maxSize);
        if ($fileError) $errors[] = "Registration Doc " . $fileError;
    }
    if (!$companyAddress)  $errors[] = "invalid business address";
    if (!$natureBusiness)  $errors[] = "invalid nature of business";
    if (!$companyPhone)  $errors[] = "invalid business phone";
    $fileError = Functions::PHPUploadError($companyBankFile);
    if ($fileError) {
        $errors[] = "Business Bank Statement " . $fileError;
    } else {
        $fileError = Functions::developerUploadError($companyBankFile, $permitAllFiles, $maxSize);
        if ($fileError) $errors[] = "Business Bank Statement " . $fileError;
    }
    if (!$companyBankFile2['error']) {
        $fileError = Functions::developerUploadError($companyBankFile2, $permitAllFiles, $maxSize);
        if ($fileError) $errors[] = "Business Bank II Statement " . $fileError;
    }
    $fileError = Functions::PHPUploadError($companyFinanceFile);
    if ($fileError) {
        $errors[] = "Financial Statment " . $fileError;
    } else {
        $fileError = Functions::developerUploadError($companyFinanceFile, $permitAllFiles, $maxSize);
        if ($fileError) $errors[] = "Financial Statment " . $fileError;
    }

    if (!$loanAmount)  $errors[] = "invalid loan amount ";
    if (!$loanPurpose)  $errors[] = "invalid loan purpose";
    if (!$loanCollateral)  $errors[] = "invalid collateral";
    $fileError = Functions::PHPUploadError($loanCollateralFile);
    if ($fileError) {
        $errors[] = "Collateral Doc " . $fileError;
    } else {
        $fileError = Functions::developerUploadError($loanCollateralFile, $permitAllFiles, $maxSize);
        if ($fileError) $errors[] = "Collateral Doc " . $fileError;
    }
    if (!$loanRepymtPeriod)  $errors[] = "invalid repayment period";
    if (!$sourceOfPayment) $errors[] = "invalid source repayment";

    if (!$promoterName)  $errors[] = "invalid promoter name";
    if (!$promoterNINBVN)  $errors[] = "invalid promoter identification type";
    if (!$promoterNINBVNNo)  $errors[] = "invalid promoter identification no";
    $fileError = Functions::PHPUploadError($promoterStatementFile);
    if ($fileError) {
        $errors[] = " Promoter bank statment " . $fileError;
    } else {
        $fileError = Functions::developerUploadError($promoterStatementFile, $permitAllFiles, $maxSize);
        if ($fileError) $errors[] = " Promoter bank statment " . $fileError;
    }
    $fileError = Functions::PHPUploadError($promoterGuaranteeFile);
    if ($fileError) {
        $errors[] = " Promoter Guarantee Letter " . $fileError;
    } else {
        $fileError = Functions::developerUploadError($promoterGuaranteeFile, $permitAllFiles, $maxSize);
        if ($fileError) $errors[] = " Promoter Guarantee Letter " . $fileError;
    }

    if ($otherPromoters) {
        $kanter = 2;
        foreach ($otherPromoters as $aOtherPromoter) {
            if (!$aOtherPromoter['name'])  $errors[] = "invalid promoter $kanter name";
            if (!$aOtherPromoter['bvn'])  $errors[] = "invalid promoter $kanter bvn";
            $fileError = Functions::PHPUploadError($aOtherPromoter['bankStatment']);
            if ($fileError) {
                $errors[] = " Promoter $kanter bank statment " . $fileError;
            } else {
                $fileError = Functions::developerUploadError($aOtherPromoter['bankStatment'], $permitAllFiles, $maxSize);
                if ($fileError) $errors[] = " Promoter $kanter bank statment " . $fileError;
            }
            $fileError = Functions::PHPUploadError($aOtherPromoter['guarantorLetter']);
            if ($fileError) {
                $errors[] = " Promoter $kanter guarantee letter " . $fileError;
            } else {
                $fileError = Functions::developerUploadError($aOtherPromoter['guarantorLetter'], $permitAllFiles, $maxSize);
                if ($fileError) $errors[] = " Promoter $kanter guarantee letter " . $fileError;
            }
            ++$kanter;
        }
    }

    //redirect on error
    $responseURL = "Location: " . URL . "msme_form.php";
    if ($errors) {
        //put value in session variable
        $_SESSION['emailValue'] = $_POST['email'];
        $_SESSION['companyNameValue'] = $_POST['company_name'];
        $_SESSION['companyTinValue'] = $_POST['company_tin'];
        $_SESSION['companyAddressValue'] = $_POST['company_address'];
        $_SESSION['natureOfBusinessValue'] = $_POST['nature_of_business'];
        $_SESSION['companyPhoneValue'] = $_POST['company_phone'];
        $_SESSION['accountNoValue'] = $_POST['account_no'];
        $_SESSION['lendingInstituteValue'] = $_POST['lending_institute'];
        $_SESSION['amountCurrentlyOwnValue'] = $_POST['amount_currently_own'];
        $_SESSION['totalMonthPaymentValue'] = $_POST['total_monthly_payment'];
        $_SESSION['directDebitValue'] = $_POST['directDebit'];
        $_SESSION['loanAmountValue'] = $_POST['loan_amount'];
        $_SESSION['loanPurposeValue'] = $_POST['loan_purpose'];
        $_SESSION['collateralValue'] = $_POST['collateral'];
        $_SESSION['dueObligationValue'] = $_POST['dueObligation'];
        $_SESSION['repaymentPeriodValue'] = $_POST['repayment_period'];
        $_SESSION['sourceOfRepaymentValue'] = $_POST['source_of_repayment'];
        $_SESSION['promoterNameValue'] = $_POST['promoter_name'];
        $_SESSION['bvnNinNoValue'] = $_POST['bvn_nin_no'];

        Tag::setResponse(
            'Invalid Data Input',
            $errors,
            Tag::RESPONSE_NEGATIVE
        );
        header($responseURL);
        exit;
    }

    //user using User.create method
    $password = rand(1000000, 9999999);
    $User = new Users($PDO);
    $data = ['email' => $email, 'password' => $password, 'user_type' => MyUsers::MSME['name']];
    $loginId = $User->create($data, true, true);

    //update other info in profile table
    $Db->setTable(Users::TABLE_PROFILE);
    $userTypeId = $User->getTypeInfo()[MyUsers::MSME['name']]['id'];
    $columns = [
        'firstname' => ['colValue' => $firstname, 'isFunction' => false, 'isBindAble' => true],
        'middlename' => ['colValue' => $middlename, 'isFunction' => false, 'isBindAble' => true],
        'surname' => ['colValue' => $surname, 'isFunction' => false, 'isBindAble' => true],
        'address' => ['colValue' => $companyAddress, 'isFunction' => false, 'isBindAble' => true],
        'user_type' => ['colValue' => $userTypeId, 'isFunction' => false, 'isBindAble' => true],
        'profile_image' => ['colValue' => Users::PROFILE_IMG, 'isFunction' => false, 'isBindAble' => true],
    ];
    $where = [['column' => 'login_id', 'comparsion' => '=', 'bindAbleValue' => $loginId]];
    $Db->update(__LINE__, $columns, $where);

    //create the promotor as guarantor user
    $userTypeId = $User->getTypeInfo()[MyUsers::GUARANTOR['name']]['id'];
    $columns = [
        'firstname' => ['colValue' => $promoterName, 'isFunction' => false, 'isBindAble' => true],
        'profile_image' => ['colValue' => Users::PROFILE_IMG, 'isFunction' => false, 'isBindAble' => true],
        'user_type' => ['colValue' => $userTypeId, 'isFunction' => false, 'isBindAble' => true]
    ];
    $Db->setTable(Users::TABLE_PROFILE);
    $promoterProfileId = $Db->insert(__LINE__, $columns)['lastInsertId'];
    if ($otherPromoters) {
        $Db->setTable(Users::TABLE_PROFILE);
        $kanter = 0;
        foreach ($otherPromoters as $aOtherPromoter) {
            $columns = [
                'firstname' => ['colValue' => $aOtherPromoter['name'], 'isFunction' => false, 'isBindAble' => true],
                'profile_image' => ['colValue' => Users::PROFILE_IMG, 'isFunction' => false, 'isBindAble' => true],
                'user_type' => ['colValue' => $userTypeId, 'isFunction' => false, 'isBindAble' => true]
            ];
            $otherPromoters[$kanter]['promoterProfileId'] = $Db->insert(__LINE__, $columns)['lastInsertId'];
            ++$kanter;
        }
    }

    //add promoter info into guarantor table
    $letterDoc = "pl$loginId." . pathinfo($promoterGuaranteeFile['name'], PATHINFO_EXTENSION);
    copy($promoterGuaranteeFile['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "guarantor-doc/$letterDoc");
    $bankStatementDoc = "pSt$loginId." . pathinfo($promoterStatementFile['name'], PATHINFO_EXTENSION);
    copy($promoterStatementFile['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "guarantor-doc/$bankStatementDoc");
    $columns = [
        'profile_id' => ['colValue' => $promoterProfileId, 'isFunction' => false, 'isBindAble' => true],
        'id_card_type' => ['colValue' => $promoterNINBVN, 'isFunction' => false, 'isBindAble' => true],
        'id_card_no' => ['colValue' => $promoterNINBVNNo, 'isFunction' => false, 'isBindAble' => true],
        'type' => ['colValue' => MyUsers::GUARANTOR_TYPE[1], 'isFunction' => false, 'isBindAble' => true],
        'letter' => ['colValue' => $letterDoc, 'isFunction' => false, 'isBindAble' => true],
        'bank_statment' => ['colValue' => $bankStatementDoc, 'isFunction' => false, 'isBindAble' => true],
    ];
    $Db->setTable(MyUsers::GUARANTOR['table']);
    $promoterUserId = $Db->insert(__LINE__, $columns)['lastInsertId'];
    $otherPromoterUserIds = [];
    if ($otherPromoters) {
        $Db->setTable(MyUsers::GUARANTOR['table']);
        $kanter = 0;
        foreach ($otherPromoters as $aOtherPromoter) {
            $letterDoc = "pl{$kanter}$loginId." . pathinfo($aOtherPromoter['guarantorLetter']['name'], PATHINFO_EXTENSION);
            copy($aOtherPromoter['guarantorLetter']['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "guarantor-doc/$letterDoc");
            $bankStatementDoc = "pSt{$kanter}$loginId." . pathinfo($aOtherPromoter['bankStatment']['name'], PATHINFO_EXTENSION);
            copy($aOtherPromoter['bankStatment']['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "guarantor-doc/$bankStatementDoc");
            $columns = [
                'profile_id' => ['colValue' => $aOtherPromoter['promoterProfileId'], 'isFunction' => false, 'isBindAble' => true],
                'id_card_type' => ['colValue' => 'bvn', 'isFunction' => false, 'isBindAble' => true],
                'id_card_no' => ['colValue' => $aOtherPromoter['bvn'], 'isFunction' => false, 'isBindAble' => true],
                'type' => ['colValue' => MyUsers::GUARANTOR_TYPE[1], 'isFunction' => false, 'isBindAble' => true],
                'letter' => ['colValue' => $letterDoc, 'isFunction' => false, 'isBindAble' => true],
                'bank_statment' => ['colValue' => $bankStatementDoc, 'isFunction' => false, 'isBindAble' => true],
            ];
            $otherPromoters[$kanter]['promoterUserId'] = $Db->insert(__LINE__, $columns)['lastInsertId'];
            $otherPromoterUserIds[] = $otherPromoters[$kanter]['promoterUserId'];
            ++$kanter;
        }
    }

    //update promoter/guarantor and other info in msme table
    $profileId = $User->getInfo($loginId)[Users::TABLE_PROFILE]['id'];
    $idCardDoc = "";
    $bankStatementDoc = "msmebs$loginId." . pathinfo($companyBankFile['name'], PATHINFO_EXTENSION);
    copy($companyBankFile['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "source-income/$bankStatementDoc");
    $financialDoc = "msmefs$loginId." . pathinfo($companyFinanceFile['name'], PATHINFO_EXTENSION);
    copy($companyBankFile['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "source-income/$financialDoc");
    $regDoc = "msmeregdoc$loginId." . pathinfo($companyCACFile['name'], PATHINFO_EXTENSION);
    copy($companyBankFile['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "cac/$regDoc");
    $collateralDoc = "msmecolldoc$loginId." . pathinfo($loanCollateralFile['name'], PATHINFO_EXTENSION);
    copy($loanCollateralFile['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "collateral/$collateralDoc");
    $bank = json_encode(['bank' => $bank, 'accountNo' => $accountNo, 'accountName' => $companyName]);
    $columns = [
        'guarantor_id' => ['colValue' => $promoterUserId, 'isFunction' => false, 'isBindAble' => true],
        'dob' => ['colValue' => $dob, 'isFunction' => true, 'isBindAble' => false],
        'company' => ['colValue' => $companyName, 'isFunction' => false, 'isBindAble' => true],
        'tin' => ['colValue' => $companyTIN, 'isFunction' => false, 'isBindAble' => true],
        'company_address' => ['colValue' => $companyAddress, 'isFunction' => false, 'isBindAble' => true],
        'collateral_text' => ['colValue' => $loanCollateral, 'isFunction' => false, 'isBindAble' => true],
        'collateral_file' => ['colValue' => $collateralDoc, 'isFunction' => false, 'isBindAble' => true],
        'bank_statement' => ['colValue' => $bankStatementDoc, 'isFunction' => false, 'isBindAble' => true],
        'nature_business' => ['colValue' => $natureBusiness, 'isFunction' => false, 'isBindAble' => true],
        'company_phone' => ['colValue' => $companyPhone, 'isFunction' => false, 'isBindAble' => true],
        'financial_statement' => ['colValue' => $financialDoc, 'isFunction' => false, 'isBindAble' => true],
        'company_cac_doc' => ['colValue' => $regDoc, 'isFunction' => false, 'isBindAble' => true],
        'id_card_type' => ['colValue' => $idCardType, 'isFunction' => false, 'isBindAble' => true],
        'id_card' => ['colValue' => $idCardDoc, 'isFunction' => false, 'isBindAble' => true],
        'bank_details' => ['colValue' => $bank, 'isFunction' => false, 'isBindAble' => true]
    ];
    if (!$companyBankFile2['error']) {
        $bankStatementDoc2 = "msmebs2$loginId." . pathinfo($companyBankFile2['name'], PATHINFO_EXTENSION);
        copy($companyBankFile2['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "source-income/$bankStatementDoc2");
        $columns['bank_statement2'] = ['colValue' => $bankStatementDoc2, 'isFunction' => false, 'isBindAble' => true];
    }
    if ($otherPromoterUserIds)
        $columns['guarantor_id_others'] = [
            'colValue' => json_encode($otherPromoterUserIds),
            'isFunction' => false, 'isBindAble' => true
        ];
    $where = [['column' => 'profile_id', 'comparsion' => '=', 'bindAbleValue' => $profileId]];
    $Db->setTable(MyUsers::MSME['table']);
    $Db->update(__LINE__, $columns, $where);

    //book loans
    $columns = [
        'profile_id' => ['colValue' => $profileId, 'isFunction' => false, 'isBindAble' => true],
        'product' => ['colValue' => $productId, 'isFunction' => false, 'isBindAble' => true],
        'amount' => ['colValue' => $loanAmount, 'isFunction' => false, 'isBindAble' => true],
        'repymt_source' => ['colValue' => $sourceOfPayment, 'isFunction' => false, 'isBindAble' => true],
        'repayment_period' => ['colValue' => $loanRepymtPeriod, 'isFunction' => false, 'isBindAble' => true],
        'purpose' => ['colValue' => $loanPurpose, 'isFunction' => false, 'isBindAble' => true],
        'other_bank_own' => ['colValue' => $borrowBank, 'isFunction' => false, 'isBindAble' => true],
        'status' => ['colValue' => 'applied', 'isFunction' => false, 'isBindAble' => true],
    ];
    if ($amountOwn)
        $columns['amt_own'] = ['colValue' => $amountOwn, 'isFunction' => false, 'isBindAble' => true];
    if ($mthlyRepymtAmt)
        $columns['mthly_repymt'] = ['colValue' => $mthlyRepymtAmt, 'isFunction' => false, 'isBindAble' => true];
    if ($directDebitAmt)
        $columns['direct_debit_amt'] = ['colValue' => $directDebitAmt, 'isFunction' => false, 'isBindAble' => true];
    if ($pymtObligation)
        $columns['outstand_obligation'] = ['colValue' => $pymtObligation, 'isFunction' => false, 'isBindAble' => true];
    $Db->setTable("loan");
    $Db->insert(__LINE__, $columns);

    //send mail to user with login details
    $Notification = new Notification();
    $content = "
            <p style='margin-bottom:20px;'>Good Day Sir/Madam </p>
            <p style='margin-bottom:8px;'>
                Congratulation we will like to let know that your loan application on " . SITENAME . " 
                has been submitted. The loan information is below.</br>
                <strong>Loan Product</strong>: " . Functions::getLoanProducts()[$productId]['market'] . "<br/>
                <strong>Amount Applied</strong>: " . number_format($loanAmount, 2) . "<br/>
                <strong>Application Date</strong>: " . date("jS F Y") . "<br/>
            </p>
            <p style='margin-bottom:8px;'>
                You can check the status of your loan application on our LOAN MANAGEMENT PORTAL. 
                The login details are below.</br>
                <strong>Email</strong>: $email<br/>
                <strong>Default Password</strong>: $password <small>please change on first successful login</small><br/>
                <strong>URL</strong>: " . URLBACKEND . "<br/>
            </p>

        ";
    $Notification->sendMail(['to' => [$email], 'from' => ['info@' . URLEMAIL]], "Loan Application Status", $content);

    $responseTitle = 'Operation Successful';
    $responseMessage = "Your loan application has been successfully submitted and its under consideration 
    you can view the status by login to our <a href=" . URLBACKEND . "><u>LOAN MANAGER</u></a>. 
    Please check your email '$email' for login details";
    $responseColor = Tag::RESPONSE_POSITIVE;
    Tag::setResponse($responseTitle, [$responseMessage], $responseColor);

    //redirect on completion
    $responseURL = "Location: " . URL . "form-success.php";
    header($responseURL);
    exit();
} else {
    new ErrorLog('Suspected CSRF Attack', __FILE__, __LINE__);
    Tag::setResponse(
        'Expired Session',
        ['Your session has expired, please repeat the process again'],
        Tag::RESPONSE_NEGATIVE
    );
    header($responseURL);
    exit();
}
