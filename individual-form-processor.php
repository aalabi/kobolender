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
    $surname = filter_var(trim($_POST['last_name']), FILTER_SANITIZE_STRING);
    $firstname = filter_var(trim($_POST['first_name']), FILTER_SANITIZE_STRING);
    $middlename = filter_var(trim($_POST['middle_name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_STRING);
    $phone = filter_var(trim($_POST['phone']), FILTER_SANITIZE_STRING);
    $dob = filter_var(trim($_POST['dob']), FILTER_SANITIZE_STRING);
    $employerName = filter_var(trim($_POST['employer_name']), FILTER_SANITIZE_STRING);
    $employerAddress = filter_var(trim($_POST['employer_address']), FILTER_SANITIZE_STRING);
    $employmentLetter = $_FILES['employment_letter'];
    $payslip = $_FILES['payslip'];
    $idCardType = filter_var(trim($_POST['id_card_type']), FILTER_SANITIZE_STRING);
    $idCardDoc = $_FILES['id_card_doc'];
    $idCardExpirationDate = filter_var(trim($_POST['id_expiry_date']), FILTER_SANITIZE_STRING);
    $bank = filter_var(trim($_POST['bank']), FILTER_SANITIZE_STRING);
    $accountNo = filter_var(trim($_POST['account_no']), FILTER_SANITIZE_STRING);
    $accountType = filter_var(trim($_POST['type_of_bank_account']), FILTER_SANITIZE_STRING);
    $bvn = filter_var(trim($_POST['bvn']), FILTER_SANITIZE_STRING);

    $borrowBank = filter_var(trim($_POST['lending_institute']), FILTER_SANITIZE_STRING);
    $amountOwn = filter_var(trim($_POST['amount_currently_own']), FILTER_VALIDATE_FLOAT);
    $mthlyRepymtAmt = filter_var(trim($_POST['total_monthly_payment']), FILTER_VALIDATE_FLOAT);
    $directDebitAmt = filter_var(trim($_POST['direct_payment_yes']), FILTER_VALIDATE_FLOAT);
    $pymtObligation = filter_var(trim($_POST['payment_obligation_yes']), FILTER_VALIDATE_FLOAT);

    $loanAmount = filter_var(trim($_POST['loan_amount']), FILTER_VALIDATE_FLOAT);
    $loanPurpose = filter_var(trim($_POST['loan_purpose']), FILTER_SANITIZE_STRING);
    $borrowerAddress = filter_var(trim($_POST['borrower_address']), FILTER_SANITIZE_STRING);
    $loanRepymtPeriod = filter_var(trim($_POST['repayment_period']), FILTER_VALIDATE_INT);
    $sourceOfPayment = filter_var(trim($_POST['source_of_payment']), FILTER_SANITIZE_STRING);
    $passport = $_FILES['passport'];

    $guarantorLastname = filter_var(trim($_POST['guarantor_lastname']), FILTER_SANITIZE_STRING);
    $guarantorFirstname = filter_var(trim($_POST['guarantor_firstname']), FILTER_SANITIZE_STRING);
    $guarantorMiddlename = filter_var(trim($_POST['guarantor_middlename']), FILTER_SANITIZE_STRING);
    $guarantorEmail = filter_var(trim($_POST['guarantor_email']), FILTER_VALIDATE_EMAIL);
    $guarantorPhone = filter_var(trim($_POST['guarantor_phone']), FILTER_SANITIZE_STRING);
    $guarantorAddress = filter_var(trim($_POST['guarantor_address']), FILTER_SANITIZE_STRING);
    $guarantorLetter = $_FILES['guarantor_letter'];
    $guarantorPpassport = $_FILES['guarantor_passport'];
    $guarantorIDCard = $_FILES['guarantor_identification'];

    //check for validation
    if (!$productId) {
        $errors[] = "invalid loan product";
    } else {
        if (!isset(Functions::getLoanProducts()[$productId])) $errors[] = "invalid loan product";
    }
    if (!$surname)  $errors[] = "invalid lastname";
    if (!$firstname)  $errors[] = "invalid firstname";
    if (!$email) {
        $errors[] = "invalid email";
    } else {
        $Db = new Database(__FILE__, $PDO, Authentication::TABLE);
        if ($Db->isDataInColumn(__LINE__, $email, 'email')) $errors[] = "email associated with another user";
    }
    if (!$phone)  $errors[] = "invalid phone no";
    if (!$dob)  $errors[] = "invalid date of birth";
    if (!$employerName)  $errors[] = "invalid employer's name";
    if (!$employerAddress)  $errors[] = "invalid employer's address";
    $fileError = Functions::PHPUploadError($employmentLetter);
    if ($fileError) {
        $errors[] = "Employment Letter " . $fileError;
    } else {
        $fileError = Functions::developerUploadError($employmentLetter, $permitAllFiles, $maxSize);
        if ($fileError) $errors[] = "Employment Letter " . $fileError;
    }
    $fileError = Functions::PHPUploadError($payslip);
    if ($fileError) {
        $errors[] = "Payslip " . $fileError;
    } else {
        $fileError = Functions::developerUploadError($payslip, $permitAllFiles, $maxSize);
        if ($fileError) $errors[] = "Payslip " . $fileError;
    }
    if (!$idCardType)  $errors[] = "invalid card type";
    $fileError = Functions::PHPUploadError($idCardDoc);
    if ($fileError) {
        $errors[] = "ID Card File " . $fileError;
    } else {
        $fileError = Functions::developerUploadError($idCardDoc, $permitAllFiles, $maxSize);
        if ($fileError) $errors[] = "ID Card File " . $fileError;
    }
    if (!$bank)  $errors[] = "invalid bank";
    if (!$accountNo)  $errors[] = "invalid account no";
    if (!$accountType)  $errors[] = "invalid account type";
    if (!$bvn)  $errors[] = "invalid BVN";

    if (!$loanAmount)  $errors[] = "invalid loan";
    if (!$loanPurpose)  $errors[] = "invalid loan purpose";
    if (!$borrowerAddress)  $errors[] = "invalid address";
    if (!$sourceOfPayment)  $errors[] = "invalid source of payment";
    if (!$loanRepymtPeriod)  $errors[] = "invalid repayment period";
    $fileError = Functions::PHPUploadError($passport);
    if ($fileError) {
        $errors[] = "Passport " . $fileError;
    } else {
        $fileError = Functions::developerUploadError($passport, $permitImageFiles, $maxSize);
        if ($fileError) $errors[] = "Passport " . $fileError;
    }

    if (trim($_POST['guarantor_email']))
        if (!$guarantorEmail)  $errors[] = "invalid guarantor email";
    if (!Functions::PHPUploadError($guarantorLetter)) {
        $fileError = Functions::developerUploadError($guarantorLetter, $permitAllFiles, $maxSize);
        if ($fileError) $errors[] = " Guarantor's Letter " . $fileError;
    }
    if (!Functions::PHPUploadError($guarantorPpassport)) {
        $fileError = Functions::developerUploadError($guarantorPpassport, $permitImageFiles, $maxSize);
        if ($fileError) $errors[] = " Guarantor's Passport " . $fileError;
    }
    if (!Functions::PHPUploadError($guarantorIDCard)) {
        $fileError = Functions::developerUploadError($guarantorIDCard, $permitImageFiles, $maxSize);
        if ($fileError) $errors[] = " Guarantor's ID Card " . $fileError;
    }

    //redirect on error
    $responseURL = "Location: " . URL . "individual_form.php";
    if ($errors) {

        $_SESSION['lastNameValue'] = $_POST['last_name'];
        $_SESSION['firstNameValue'] = $_POST['first_name'];
        $_SESSION['middleNameValue'] = $_POST['middle_name'];
        $_SESSION['emailValue'] = $_POST['email'];
        $_SESSION['phoneValue'] = $_POST['phone'];
        $_SESSION['dobValue'] = $_POST['dob'];
        $_SESSION['borrowerAddressValue'] = $_POST['borrower_address'];
        $_SESSION['employerNameValue'] = $_POST['employer_name'];
        $_SESSION['employerAddressValue'] = $_POST['employer_address'];
        $_SESSION['expiryDateValue'] = $_POST['id_expiry_date'];
        $_SESSION['accountNoValue'] = $_POST['account_no'];
        $_SESSION['bvnValue'] = $_POST['bvn'];
        $_SESSION['lendingInstituteValue'] = $_POST['lending_institute'];
        $_SESSION['amountCurrencyOwnValue'] = $_POST['amount_currently_own'];
        $_SESSION['totalMonthlyPaymentValue'] = $_POST['total_monthly_payment'];
        $_SESSION['directPaymentYesValue'] = $_POST['direct_payment_yes'];
        $_SESSION['paymentObligationYesValue'] = $_POST['payment_obligation_yes'];
        $_SESSION['loanAmountValue'] = $_POST['loan_amount'];
        $_SESSION['loanPurposeValue'] = $_POST['loan_purpose'];
        $_SESSION['repaymentPeriodValue'] = $_POST['repayment_period'];
        $_SESSION['sourceOfPaymentValue'] = $_POST['source_of_payment'];
        $_SESSION['guarantorLastNameValue'] = $_POST['guarantor_lastname'];
        $_SESSION['guarantorFirstNameValue'] = $_POST['guarantor_firstname'];
        $_SESSION['guarantorMiddleNameValue'] = $_POST['guarantor_middlename'];
        $_SESSION['guarantorEmailValue'] = $_POST['guarantor_email'];
        $_SESSION['guarantorPhoneValue'] = $_POST['guarantor_phone'];
        $_SESSION['guarantorAddressValue'] = $_POST['guarantor_address'];
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
    $data = ['email' => $email, 'password' => $password, 'user_type' => MyUsers::INDIVIDUAL['name']];
    $loginId = $User->create($data, true, true);

    //update in login table
    $Db = new Database(__FILE__, $PDO, Authentication::TABLE);
    $columns = [
        'phone' => ['colValue' => $phone, 'isFunction' => false, 'isBindAble' => true],
    ];
    $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $loginId]];
    $Db->update(__LINE__, $columns, $where);

    //update other info in profile table
    $Db->setTable(Users::TABLE_PROFILE);
    $profileImage = "p$loginId." . pathinfo($passport['name'], PATHINFO_EXTENSION);
    copy($passport['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "profile-image/$profileImage");
    $userTypeId = $User->getTypeInfo()[MyUsers::INDIVIDUAL['name']]['id'];
    $columns = [
        'firstname' => ['colValue' => $firstname, 'isFunction' => false, 'isBindAble' => true],
        'middlename' => ['colValue' => $middlename, 'isFunction' => false, 'isBindAble' => true],
        'surname' => ['colValue' => $surname, 'isFunction' => false, 'isBindAble' => true],
        'address' => ['colValue' => $borrowerAddress, 'isFunction' => false, 'isBindAble' => true],
        'user_type' => ['colValue' => $userTypeId, 'isFunction' => false, 'isBindAble' => true],
        'profile_image' => ['colValue' => $profileImage, 'isFunction' => false, 'isBindAble' => true],
    ];
    $where = [['column' => 'login_id', 'comparsion' => '=', 'bindAbleValue' => $loginId]];
    $Db->update(__LINE__, $columns, $where);

    //create guarantor user
    $profileImage = Users::PROFILE_IMG;
    if (!$guarantorPpassport['error']) {
        $profileImage = "pg$loginId." . pathinfo($guarantorPpassport['name'], PATHINFO_EXTENSION);
        copy($guarantorPpassport['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "profile-image/$profileImage");
    }
    $userTypeId = $User->getTypeInfo()[MyUsers::GUARANTOR['name']]['id'];
    $gurantorUserId = null;
    if ($guarantorFirstname) {
        $columns = [
            'firstname' => ['colValue' => $guarantorFirstname, 'isFunction' => false, 'isBindAble' => true],
            'middlename' => ['colValue' => $guarantorMiddlename, 'isFunction' => false, 'isBindAble' => true],
            'surname' => ['colValue' => $guarantorLastname, 'isFunction' => false, 'isBindAble' => true],
            'address' => ['colValue' => $guarantorAddress, 'isFunction' => false, 'isBindAble' => true],
            'user_type' => ['colValue' => $userTypeId, 'isFunction' => false, 'isBindAble' => true],
            'profile_image' => ['colValue' => $profileImage, 'isFunction' => false, 'isBindAble' => true],
        ];
        $Db->setTable(Users::TABLE_PROFILE);
        $gurantorProfileId = $Db->insert(__LINE__, $columns)['lastInsertId'];
        //add gurarnator info into guarantor table
        $columns = [
            'profile_id' => ['colValue' => $gurantorProfileId, 'isFunction' => false, 'isBindAble' => true],
            'email' => ['colValue' => $guarantorEmail, 'isFunction' => false, 'isBindAble' => true],
            'phone' => ['colValue' => $guarantorPhone, 'isFunction' => false, 'isBindAble' => true]
        ];
        if (!$guarantorLetter['error']) {
            $letterDoc = "gl$loginId." . pathinfo($guarantorLetter['name'], PATHINFO_EXTENSION);
            copy($guarantorLetter['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "guarantor-doc/$letterDoc");
            $columns['letter'] = ['colValue' => $letterDoc, 'isFunction' => false, 'isBindAble' => true];
        }
        if (!$guarantorIDCard['error']) {
            $idCardDoc = "gID$loginId." . pathinfo($guarantorIDCard['name'], PATHINFO_EXTENSION);
            copy($guarantorIDCard['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "guarantor-doc/$idCardDoc");
            $columns['id_card'] = ['colValue' => $idCardDoc, 'isFunction' => false, 'isBindAble' => true];
        }
        $Db->setTable(MyUsers::GUARANTOR['table']);
        $gurantorUserId = $Db->insert(__LINE__, $columns)['lastInsertId'];
    }

    //update data in individual table
    $profileId = $User->getInfo($loginId)[Users::TABLE_PROFILE]['id'];
    $employmentLetterDoc = "el$loginId." . pathinfo($employmentLetter['name'], PATHINFO_EXTENSION);
    copy($employmentLetter['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "employment-letter/$employmentLetterDoc");
    $payslipDoc = "ps$loginId." . pathinfo($payslip['name'], PATHINFO_EXTENSION);
    copy($payslip['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "pay-slip/$payslipDoc");
    $idCardFile = "idcarddoc$loginId." . pathinfo($idCardDoc['name'], PATHINFO_EXTENSION);
    copy($idCardDoc['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "id-card/$idCardFile");
    $bank = json_encode(['bank' => $bank, 'accountNo' => $accountNo, 'accountType' => $accountType]);
    $columns = [
        'dob' => ['colValue' => $dob, 'isFunction' => false, 'isBindAble' => true],
        'company' => ['colValue' => $employerName, 'isFunction' => false, 'isBindAble' => true],
        'company_address' => ['colValue' => $employerAddress, 'isFunction' => false, 'isBindAble' => true],
        'employment_letter' => ['colValue' => $employmentLetterDoc, 'isFunction' => false, 'isBindAble' => true],
        'pay_slip' => ['colValue' => $payslipDoc, 'isFunction' => false, 'isBindAble' => true],
        'bvn' => ['colValue' => $bvn, 'isFunction' => false, 'isBindAble' => true],
        'id_card' => ['colValue' => $idCardType, 'isFunction' => false, 'isBindAble' => true],
        'id_card_doc' => ['colValue' => $idCardFile, 'isFunction' => false, 'isBindAble' => true],
        'id_card_expiry_date' => ['colValue' => $idCardExpirationDate, 'isFunction' => false, 'isBindAble' => true],
        'bank_details' => ['colValue' => $bank, 'isFunction' => false, 'isBindAble' => true]
    ];
    if ($gurantorUserId) {
        $columns['guarantor_id'] = ['colValue' => $gurantorUserId, 'isFunction' => false, 'isBindAble' => true];
    }
    $where = [['column' => 'profile_id', 'comparsion' => '=', 'bindAbleValue' => $profileId]];
    $Db->setTable(MyUsers::INDIVIDUAL['table']);
    $Db->update(__LINE__, $columns, $where);

    //book loans
    $columns = [
        'profile_id' => ['colValue' => $profileId, 'isFunction' => false, 'isBindAble' => true],
        'product' => ['colValue' => $productId, 'isFunction' => false, 'isBindAble' => true],
        'amount' => ['colValue' => $loanAmount, 'isFunction' => false, 'isBindAble' => true],
        'repymt_source' => ['colValue' => $sourceOfPayment, 'isFunction' => false, 'isBindAble' => true],
        'purpose' => ['colValue' => $loanPurpose, 'isFunction' => false, 'isBindAble' => true],
        'repayment_period' => ['colValue' => $loanRepymtPeriod, 'isFunction' => false, 'isBindAble' => true],
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
    $result = $Db->insert(__LINE__, $columns);

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
