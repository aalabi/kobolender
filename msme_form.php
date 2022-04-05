<?php
// $pageName = 'COORPORATE LOAN';
require_once('template/head.php');
require_once('template/header.php');
$products = Functions::getLoanProducts();
$option = "<option value=''>Pick a Product</option>";
foreach ($products as $aProductId => $aProductInfo) {
    if ($aProductInfo['type'] == 'msme') {
        $option .= "<option value='$aProductId'>{$aProductInfo['market']}</option>";
    }
    $loanProduct = "
      <div class='row mb-3'>
          <label for='firstname' class='col-md-4 col-form-label'>Loan Product *</label>
          <div class='col-md-8'>
              <select name='productId' required class='form-select'>$option</select>
          </div>
      </div>
  ";
}
$loanName = "Loan Product - MSME";
$max = $maxPlaceholder = "";
if (isset($_GET['productId'])) {
    $loanName = $products[$_GET['productId']]['market'];
    $loanProduct = "<input type='hidden' name='productId'>";
    $max = "max='{$products[$_GET['productId']]['obligor limit']}'";
    $maxPlaceholder = "maxmium amount " . number_format($products[$_GET['productId']]['obligor limit'], 2);
}
$bankOptions = "";
foreach (Functions::banksCollection() as $bankInfo)
    $bankOptions .= "<option value='$bankInfo'>$bankInfo</option>";

$responseOperation = "";
$Tag = new MyTag($PDO);
$emailValue = "";
$companyNameValue = "";
$companyTinValue = "";
$companyAddressValue = "";
$natureOfBusinessValue = "";
$companyPhoneValue = "";
$accountNoValue = "";
$lendingInstituteValue = "";
$amountCurrentlyOwnValue = "";
$totalMonthPaymentValue = "";
$directDebitValue = "";
$loanPurposeValue = "";
$repaymentPeriodValue = "";
$loanAmountValue = "";
$dueObligationValue = "";
$sourceOfRepaymentValue = "";
$promoterNameValue = "";
$bvnNinNoValue = "";
$collateralValue = "";
if ($theResponse = Tag::getResponse()) {
    $responseMessage = rtrim(implode(", ", $theResponse['messages']), ", ");
    $responseOperation = $Tag->responseTag(
        $theResponse['title'],
        $responseMessage,
        $theResponse['status']
    );

    //check for errors messages
    if ($theResponse['status'] == Tag::RESPONSE_NEGATIVE) {
        $emailValue = " value='{$_SESSION['emailValue']}'";
        unset($_SESSION['emailValue']);

        $companyNameValue = "value = '{$_SESSION['companyNameValue']}'";
        unset($_SESSION['companyNameValue']);

        $companyTinValue = "value='{$_SESSION['companyTinValue']}'";
        unset($_SESSION['companyTinValue']);

        $companyAddressValue = $_SESSION['companyAddressValue'];
        unset($_SESSION['companyAddressValue']);

        $natureOfBusinessValue = "value='{$_SESSION['natureOfBusinessValue']}'";
        unset($_SESSION['natureOfBusinessValue']);

        $companyPhoneValue = "value='{$_SESSION['companyPhoneValue']}'";
        unset($_SESSION['companyPhoneValue']);

        $accountNoValue = "value='{$_SESSION['accountNoValue']}'";
        unset($_SESSION['accountNoValue']);

        $lendingInstituteValue = "value='{$_SESSION['lendingInstituteValue']}'";
        unset($_SESSION['lendingInstituteValue']);

        $amountCurrentlyOwnValue = "value='{$_SESSION['amountCurrentlyOwnValue']}'";
        unset($_SESSION['amountCurrentlyOwnValue']);

        $loanPurposeValue = "value='{$_SESSION['loanPurposeValue']}'";
        unset($_SESSION['loanPurposeValue']);

        $totalMonthPaymentValue = "value='{$_SESSION['totalMonthPaymentValue']}'";
        unset($_SESSION['totalMonthPaymentValue']);

        $directDebitValue = "value='{$_SESSION['directDebitValue']}'";
        unset($_SESSION['directDebitValue']);

        $loanAmountValue = "value='{$_SESSION['loanAmountValue']}'";
        unset($_SESSION['loanAmountValue']);

        $collateralValue = "value='{$_SESSION['collateralValue']}'";
        unset($_SESSION['collateralValue']);

        $dueObligationValue = "value='{$_SESSION['dueObligationValue']}'";
        unset($_SESSION['dueObligationValue']);

        $sourceOfRepaymentValue = $_SESSION['sourceOfRepaymentValue'];
        unset($_SESSION['sourceOfRepaymentValue']);

        $repaymentPeriodValue = "value='{$_SESSION['repaymentPeriodValue']}'";
        unset($_SESSION['repaymentPeriodValue']);

        $promoterNameValue = "value='{$_SESSION['promoterNameValue']}'";
        unset($_SESSION['promoterNameValue']);

        $bvnNinNoValue = "value='{$_SESSION['bvnNinNoValue']}'";
        unset($_SESSION['bvnNinNoValue']);
    }
}
?>

<section class="individual-management">
    <div class="container">
        <div class="row" style="padding-top: 60px;">
            <div class="individual-management-text text-center pt-5">
                <h1>
                    MSME Loan Product
                </h1>
                <div class="small-text">

                </div>
            </div>
        </div>
    </div>
</section>

<!--Form section--->
<section class="mt-5 mb-5 form-section">
    <div class="container">
        <div class="row about pb-4" data-aos="zoom-in-down">
            <div class="about-sec text-center">
                <h3><?= $loanName ?></h3>
                <div class="divider"></div>
                <p>Please fill the form.</p>
            </div>
        </div>

        <div class="row">
            <?= $responseOperation ?>
            <div class="col-md-9 mx-auto">
                <form action="msme-form-processor.php" id="loanForm" class="row g-4" enctype="multipart/form-data" method="POST">
                    <fieldset>
                        <legend>Personal Details</legend>
                        <?= MyTag::getCSRFTokenInputTag() ?>
                        <?= $loanProduct ?>
                        <!-- 
                            <div class="row mb-3">
                                <label for="last name" class="col-md-4 col-form-label">Last Name *</label>
                                <div class="col-md-8">
                                    <input required type="text" name="last_name" id="" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-3" >
                                <label for="firstname" class="col-md-4 col-form-label">First Name *</label>
                                <div class="col-md-8">
                                    <input required type="text" name="first_name" id="" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-3" >
                                <label for="middlename" class="col-md-4 col-form-label">Middle Name</label>
                                <div class="col-md-8">
                                    <input type="text" name="middle_name" id="" class="form-control">
                                </div>
                            </div> 
                        -->
                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label">Email *</label>
                            <div class="col-md-8">
                                <input required <?= $emailValue ?> type="email" name="email" id="" class="form-control">
                            </div>
                        </div>
                        <!-- 
                            <div class="row mb-3" hidden>
                                <label for="email" class="col-md-4 col-form-label">Phone *</label>
                                <div class="col-md-8">
                                    <input required type="text" name="phone" id="" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-3" hidden>
                                <label class="col-md-4 col-form-label">Date of Birth *</label>
                                <div class="col-md-8">
                                    <input required type="date" name="dob" id="" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-3" hidden>
                                <label class="col-md-4 col-form-label">ID Card type *</label>
                                <div class="col-md-8">
                                    <select required class="form-select" name="id_card_type" class="form-control">
                                        <option value=''>ID Card type</option>
                                        <option>National ID Card</option>
                                        <option>Driver's Licence</option>
                                        <option>International Passport</option>
                                        <option>Voter's Card</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="upload id card" class="col-md-4 col-form-label">Upload ID Card <small>(png, jpg,
                                        doc, pdf max 1mb)</small> *</label>
                                <div class="col-md-8">
                                    <input required type="file" id="myFile" name="id_card" class="form-control al-regular-input">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-md-4 col-form-label">Upload Passport <small>(png, jpg, doc, pdf max
                                        1mb)</small> *</label>
                                <div class="col-md-8">
                                    <input required type="file" id="myFile" name="passport" class="form-control al-regular-input">
                                </div>
                            </div> 
                        -->
                    </fieldset>
                    <hr /><br />
                    <fieldset>
                        <legend>Business Details</legend>
                        <div class="row mb-3">
                            <label for="company name" class="col-md-4 col-form-label">Business Name *</label>
                            <div class="col-md-8">
                                <input required <?= $companyNameValue ?> type="text" name="company_name" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="company name" class="col-md-4 col-form-label">TIN *</label>
                            <div class="col-md-8">
                                <input required<?= $companyTinValue ?> type="text" name="company_tin" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="company cac" class="col-md-4 col-form-label">
                                Business Registration Document <small>(png, jpg, doc, pdf max 1mb)</small> *
                            </label>
                            <div class="col-md-8">
                                <input required type="file" id="myFile" name="company_reg_doc" id="" class="form-control al-regular-input">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Business Address *</label>
                            <div class="col-md-8">
                                <textarea required name="company_address" id="" cols="30" rows="4" class="form-control" placeholder="Address"><?= $companyAddressValue ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Nature of Business *</label>
                            <div class="col-md-8">
                                <input required <?= $natureOfBusinessValue ?> type="text" name="nature_of_business" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Business Phone Number *</label>
                            <div class="col-md-8">
                                <input required <?= $companyPhoneValue ?> type="text" name="company_phone" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="upload evidence of source of income" class="col-md-4 col-form-label">
                                Bank Statment I <small>(6 months, png, jpg, doc, pdf max 1mb)</small> *
                            </label>
                            <div class="col-md-8">
                                <input required type="file" id="myFile" name="bank_statement" class="form-control al-regular-input">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">
                                Bank Statement II <small>(6 months, png, jpg, doc, pdf max 1mb) </small>
                            </label>
                            <div class="col-md-8">
                                <input required type="file" required id="myFile" name="bank_statement2" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="company name" class="col-md-4 col-form-label">
                                Current Financial Statement
                                <small>6 months bank statement (png, jpg, doc, pdf max 1mb)</small>*
                            </label>
                            <div class="col-md-8">
                                <input required type="file" name="company_finances" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Bank *</label>
                            <div class="col-md-8">
                                <select required class="form-select" name="bank" class="form-control">
                                    <option value=''>Select Bank</option>
                                    <?= $bankOptions ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="bank account" class="col-md-4 col-form-label">Account No *</label>
                            <div class="col-md-8">
                                <input required <?= $accountNoValue ?> type="text" name="account_no" id="" class="form-control">
                            </div>
                        </div>
                    </fieldset>
                    <hr /><br />
                    <fieldset>
                        <legend>Payment Details</legend>
                        <div class="row mb-3">
                            <label class="col-md-5 col-form-label">Which other Bank(s)/Lending Institution are you borrowing from? </label>
                            <div class="col-md-7">
                                <input type="text" <?= $lendingInstituteValue ?> name="lending_institute" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="amount" class="col-md-5 col-form-label">What is the total amount you currently owe
                                from the various sources</label>
                            <div class="col-md-7">
                                <<<<<<< HEAD <input type="number" <?= $amountCurrentlyOwnValue ?> step="0.01" name="amount_currently_own" id="" class="form-control">
                                    ||||||| f3269a0
                                    <input type="number" <?= $amountCurrentlyOwnValue ?> step="0.01" name="amount_currently_own" id="" class="form-control">
                                    =======
                                    <input type="number" <?= $amountCurrentlyOwnValue ?> step="0.01" min="0" name="amount_currently_own" id="" class="form-control">
                                    >>>>>>> bussyboo
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-5 col-form-label">
                                What is the total monthly repayment you make for the existing loans
                            </label>
                            <div class="col-md-7">
                                <<<<<<< HEAD <input type="number" <?= $totalMonthPaymentValue ?>step="0.01" name="total_monthly_payment" id="" class="form-control">
                                    ||||||| f3269a0
                                    <input type="number" <?= $totalMonthPaymentValue ?>step="0.01" name="total_monthly_payment" id="" class="form-control">
                                    =======
                                    <input type="number" <?= $totalMonthPaymentValue ?>step="0.01" min="0" name="total_monthly_payment" id="" class="form-control">
                                    >>>>>>> bussyboo
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label id="" class="col-md-5 col-form-label">
                                Do you have any direct payment instruction on your bank account and for how much
                            </label>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-6">
                                        <label><input type="radio" name='direct_payment_yes' value='yes' class='al-regular-input'>
                                            Yes</label>
                                    </div>
                                    <div class="col-6">
                                        <label><input type="radio" name='direct_payment_yes' value='no' class='al-regular-input'>
                                            No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-5 col-form-label">Amount Been Paid</label>
                            <div class="col-md-7">
                                <<<<<<< HEAD <input type="number" <?= $directDebitValue ?> step="0.01" name="directDebit" id="" class="form-control">
                                    ||||||| f3269a0
                                    <input type="number" <?= $directDebitValue ?> step="0.01" name="directDebit" id="" class="form-control">
                                    =======
                                    <input type="number" <?= $directDebitValue ?> step="0.01" min="0" name="directDebit" id="" class="form-control">
                                    >>>>>>> bussyboo
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label id="" class="col-md-5 col-form-label">Do you have any payment obligation that is due but
                                yet to be paid, if yes how much</label>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-6">
                                        <label><input type="radio" name='payment_obligation_yes' value='yes' class='al-regular-input'> Yes</label>
                                    </div>
                                    <div class="col-6">
                                        <label><input type="radio" name='payment_obligation_yes' value='no' class='al-regular-input'>
                                            No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3" id="mamaboy">
                            <label class="col-md-5 col-form-label">Amount Due</label>
                            <div class="col-md-7">
                                <input <?= $dueObligationValue ?> type="number" min="0" step="0.01" name="dueObligation" id="" class="form-control">
                            </div>
                        </div>
                    </fieldset>
                    <hr /><br />
                    <fieldset>
                        <legend>Loan Details</legend>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Loan Amount (requested) *</label>
                            <div class="col-md-8">
                                <input required <?= $loanAmountValue ?> type="number" min="0" step="0.01" min="0" name="loan_amount" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Loan Purpose *</label>
                            <div class="col-md-8">
                                <input required <?= $loanPurposeValue ?> type="text" name="loan_purpose" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="collateral" class="col-md-4 col-form-label">Collateral *</label>
                            <div class="col-md-8">
                                <input required <?= $collateralValue ?> type="text" name="collateral" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">
                                Upload Collateral <small>(png, jpg, doc, pdf max 1mb)</small>
                            </label>
                            <div class="col-md-8">
                                <input type="file" id="myFile" name="collateral_upload" class="form-control al-regular-input">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Repayment Period <small>in months</small> *</label>
                            <div class="col-md-8">
                                <<<<<<< HEAD <input required <?= $repaymentPeriodValue ?> type="number" step="1" name="repayment_period" id="" class="form-control">
                                    ||||||| f3269a0
                                    <input required <?= $repaymentPeriodValue ?> type="number" step="1" name="repayment_period" id="" class="form-control">
                                    =======
                                    <input required <?= $repaymentPeriodValue ?> type="number" min="0" step="1" name="repayment_period" id="" class="form-control">
                                    >>>>>>> bussyboo
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Source of Payment *</label>
                            <div class="col-md-8">
                                <input required type="text" name="source_of_repayment" id="" class="form-control">
                            </div>
                        </div>
                    </fieldset>
                    <hr /><br />
                    <fieldset>
                        <legend>Promoter's Details</legend>
                        <button id='addPromoter' type="button" class="btn btn-sm btn-primary">+</button>
                        <div id="promoterContainer">
                            <div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label">Promoter's Name *</label>
                                    <div class="col-md-8">
                                        <input required <?= $promoterNameValue ?> type="text" name="promoter_name" id="" class="form-control">
                                    </div>
                                </div>
                                <input required type='hidden' name='bvn_nin' class='al-regular-input' required value='BVN'>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label">BVN *</label>
                                    <div class="col-md-8">
                                        <input required <?= $bvnNinNoValue ?> type="text" name="bvn_nin_no" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label">
                                        Bank Statement *
                                        <small>6 months (png, jpg, doc, pdf max 1mb)</small>
                                    </label>
                                    <div class="col-md-8">
                                        <input required type="file" required id="myFile" name="promoter_bank_statement" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label">
                                        Guarantees Letter *
                                        <small>(png, jpg, doc, pdf max 1mb)</small>
                                    </label>
                                    <div class="col-md-8">
                                        <input required type="file" id="myFile" name="letter_of_guarantor" class="form-control al-regular-input">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label">
                                        ID Card *
                                        <small>(png, jpg, doc, pdf max 1mb)</small>
                                    </label>
                                    <div class="col-md-8">
                                        <input required type="file" required id="myFile" name="promoterIdCard" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 col-form-label">
                                        Passport Picture *
                                        <small>(png, jpg, doc, pdf max 1mb)</small>
                                    </label>
                                    <div class="col-md-8">
                                        <input required type="file" id="myFile" name="promoterPassport" class="form-control al-regular-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <hr>
                    <div class="row mb-1">
                        <h6>By completing and submitting this application, I/we understand and hereby affirm as
                            following</h6>
                    </div>

                    <div class="terms mb-3">
                        <ol class="list-group list-group-numbered">
                            <li class="list">
                                that all the information and documents provided are true and correct,
                                and where this was found not to be the case, you hereby indemnify the
                                Company for any loss for decisions made on basis of the false information
                            </li>
                            <li class="list">
                                that I/we authorize you, and have no objection to any further steps
                                or inquiries that your company may wish to undertake to validate any of
                                the information provided in this application
                            </li>
                            <li class="list">
                                that any false information and mis representation shall be a valid
                                reason for rejection of my/our loan application
                            </li>
                            <li class="list">
                                that the information I/we have provided here shall be subject to the
                                Data Protection Act 2018 of the Federal Republic of Nigeria in all
                                ramifications.
                            </li>
                        </ol>
                    </div>
                    <div class="mb-4 form-check">
                        <input required type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">By checking this box, you are agreeing to
                            our terms of service</label>
                    </div>

                    <div class="d-grid gap-2 " style="height:45px">
                        <button class="btn btn-success" type="submit">APPLY FOR A LOAN</button>
                    </div>

                </form>

            </div>
        </div>
</section>
<!--End of Form section--->


<!---Footer section-->
<?php
require_once('template/footer.php');
?>

<!---End of Footer-->



<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="js/main.js"></script>
<script src="<?= URLBACKEND ?>asset/js/al-custom.js?version=" .time()></script>

</body>

</html>