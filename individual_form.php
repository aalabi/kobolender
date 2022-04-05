<?php
require_once('template/head.php');
require_once('template/header.php');
//require_once('template/mast-head.php');
//$pageName = "INDIVIDUAL LOAN";
$products = Functions::getLoanProducts();
$option = "<option value=''>Pick a Product</option>";
foreach ($products as $aProductId => $aProductInfo) {
    if ($aProductInfo['type'] == 'individual')
        $option .= "<option value='$aProductId'>{$aProductInfo['market']}</option>";
    $loanProduct = "
    <div class='row mb-3'>
        <label for='firstname' class='col-md-4 col-form-label'>Loan Product </label>
        <div class='col-md-8'>
            <select name='productId' required class='form-select'>$option</select>
        </div>
    </div>
";
}
$loanName = "Loan Product - Individual";
$max = $maxPlaceholder = "";
if (isset($_GET['productId'])) {
    $loanName = $products[$_GET['productId']]['market'];
    $loanProduct = "<input type='hidden' name='productId' value='{$_GET['productId']}'>";
    $max = "max='{$products[$_GET['productId']]['obligor limit']}'";
    $maxPlaceholder = "maxmium amount " . number_format($products[$_GET['productId']]['obligor limit'], 2);
}

$bankOptions = "";
foreach (Functions::banksCollection() as $bankInfo)
    $bankOptions .= "<option value='$bankInfo'>$bankInfo</option>";

$responseOperation = "";
$emailValue = "";
$lastNameValue = "";
$middleNameValue = "";
$firstNameValue ="";
$phoneValue ="";
$dobValue ="";
$borrowerAddressValue ="";
$employerNameValue ="";
$employerAddressValue ="";
$expiryDateValue ="";
$accountNoValue ="";
$bvnValue ="";
$lendingInstituteValue ="";
$amountCurrencyOwnValue ="";
$totalMonthlyPaymentValue ="";
$directPaymentYesValue ="";
$paymentObligationYesValue ="";
$loanAmountValue ="";
$loanPurposeValue ="";
$repaymentPeriodValue ="";
$sourceOfPaymentValue ="";
$guarantorLastNameValue ="";
$guarantorFirstNameValue ="";
$guarantorMiddleNameValue ="";
$guarantorEmailValue ="";
$guarantorPhoneValue ="";
$guarantorAddressValue ="";

$Tag = new MyTag($PDO);
if ($theResponse = Tag::getResponse()) {
    $responseMessage = rtrim(implode(", ", $theResponse['messages']), ", ");
    $responseOperation = $Tag->responseTag(
        $theResponse['title'],
        $responseMessage,
        $theResponse['status']
    );

    //check for errors messages
    if ($theResponse['status'] == Tag::RESPONSE_NEGATIVE) {
        $lastNameValue = " value='{$_SESSION['lastNameValue']}'";
        unset($_SESSION['lastNameValue']);

        $firstNameValue = " value='{$_SESSION['firstNameValue']}'";
        unset($_SESSION['firstNameValue']);

        $middleNameValue = " value='{$_SESSION['middleNameValue']}'";
        unset($_SESSION['middleNameValue']);

        $emailValue = " value='{$_SESSION['emailValue']}'";
        unset($_SESSION['emailValue']);

        $phoneValue = " value='{$_SESSION['phoneValue']}'";
        unset($_SESSION['phoneValue']);

        $dobValue = " value='{$_SESSION['dobValue']}'";
        unset($_SESSION['dobValue']);

        $borrowerAddressValue = " value='{$_SESSION['borrowerAddressValue']}'";
        unset($_SESSION['borrowerAddressValue']);

        $employerNameValue = " value='{$_SESSION['employerNameValue']}'";
        unset($_SESSION['employerNameValue']);

        $employerAddressValue = " value='{$_SESSION['employerAddressValue']}'";
        unset($_SESSION['employerAddressValue']);

        $expiryDateValue = " value='{$_SESSION['expiryDateValue']}'";
        unset($_SESSION['expiryDateValue']);

        $accountNoValue = " value='{$_SESSION['accountNoValue']}'";
        unset($_SESSION['accountNoValue']);
        
        $bvnValue = " value='{$_SESSION['bvnValue']}'";
        unset($_SESSION['bvnValue']);

        $lendingInstituteValue = " value='{$_SESSION['lendingInstituteValue']}'";
        unset($_SESSION['lendingInstituteValue']);

        $amountCurrencyOwnValue = " value='{$_SESSION['amountCurrencyOwnValue']}'";
        unset($_SESSION['amountCurrencyOwnValue']);

        $totalMonthlyPaymentValue = " value='{$_SESSION['totalMonthlyPaymentValue']}'";
        unset($_SESSION['totalMonthlyPaymentValue']);

        $directPaymentYesValue = " value='{$_SESSION['directPaymentYesValue']}'";
        unset($_SESSION['directPaymentYesValue']);

        $paymentObligationYesValue = " value='{$_SESSION['paymentObligationYesValue']}'";
        unset($_SESSION['paymentObligationYesValue']);

        $loanAmountValue = " value='{$_SESSION['loanAmountValue']}'";
        unset($_SESSION['loanAmountValue']);

        $loanPurposeValue = " value='{$_SESSION['loanPurposeValue']}'";
        unset($_SESSION['loanPurposeValue']);
        
        $repaymentPeriodValue = " value='{$_SESSION['repaymentPeriodValue']}'";
        unset($_SESSION['repaymentPeriodValue']);

        $sourceOfPaymentValue = " value='{$_SESSION['sourceOfPaymentValue']}'";
        unset($_SESSION['sourceOfPaymentValue']);

        $guarantorLastNameValue = " value='{$_SESSION['paymentObligationYesValue']}'";
        unset($_SESSION['paymentObligationYesValue']);

        $guarantorFirstNameValue = " value='{$_SESSION['guarantorFirstNameValue']}'";
        unset($_SESSION['guarantorFirstNameValue']);

        $guarantorMiddleNameValue = " value='{$_SESSION['guarantorMiddleNameValue']}'";
        unset($_SESSION['guarantorMiddleNameValue']);

        $guarantorEmailValue = " value='{$_SESSION['guarantorEmailValue']}'";
        unset($_SESSION['guarantorEmailValue']);

        $guarantorPhoneValue = " value='{$_SESSION['guarantorPhoneValue']}'";
        unset($_SESSION['guarantorPhoneValue']);
        
        $guarantorAddressValue = " value='{$_SESSION['guarantorAddressValue']}'";
        unset($_SESSION['guarantorAddressValue']);
        
       
    }
}
?>

<section class="individual-management">
    <div class="container">
        <div class="row" style="padding-top: 60px;">
            <div class="individual-management-text text-center pt-5">
                <h1>
                    Individual Loan Product
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
                <form action="individual-form-processor.php" id="loanForm" class="row g-4" enctype="multipart/form-data" method="POST">
                    <fieldset>
                        <legend>Personal Details:</legend>
                        <?= MyTag::getCSRFTokenInputTag() ?>
                        <?= $loanProduct ?>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Surname *</label>
                            <div class="col-md-8">
                                <input type="text" <?= $lastNameValue ?> name="last_name" id="" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">First Name *</label>
                            <div class="col-md-8">
                                <input <?= $firstNameValue ?> type="text" name="first_name" id="" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Middle Name</label>
                            <div class="col-md-8">
                                <input  <?= $middleNameValue ?> type="text" name="middle_name" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Email *</label>
                            <div class="col-md-8">
                            <input required <?= $emailValue ?> type="email" name="email" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Phone Number *</label>
                            <div class="col-md-8">
                                <input type="number" <?= $phoneValue ?> name="phone" id="" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Date of Birth *</label>
                            <div class="col-md-8">
                                <input type="date"  <?= $dobValue ?>  name="dob" id="" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Upload Passport <small>(png, jpg, max 1mb, 1 by 1 aspect ratio)</small> *</label>
                            <div class="col-md-8">
                                <input type="file" id="myFile" name="passport" required class="form-control al-regular-input">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Permanent Address *</label>
                            <div class="col-md-8">
                                <textarea name="borrower_address" required id="borrower_address" cols="30" rows="4" class="form-control" placeholder="Address"><?= $borrowerAddressValue ?></textarea>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Employer's Name *</label>
                            <div class="col-md-8">
                                <input type="text"  <?= $employerNameValue ?> name="employer_name" id="" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Employer's Address *</label>
                            <div class="col-md-8">
                                <textarea name="employer_address" required id="borrower_address" cols="30" rows="4" class="form-control" placeholder="Address"> <?= $employerAddressValue ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Employment Letter <small>(png, pdf, doc, jpg, max 1mb)</small> *</label>
                            <div class="col-md-8">
                                <input type="file" id="myFile" name="employment_letter" required class="form-control al-regular-input">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Statement of Account<small> (recent 6 months, png, pdf, doc, jpg, max 1mb)</small> *</label>
                            <div class="col-md-8">
                                <input type="file" id="myFile" name="payslip" required class="form-control al-regular-input">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Upload ID Card *</label>
                            <div class="col-md-8">
                                <input type="file" id="myFile" name="id_card_doc" required class="form-control al-regular-input">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">ID Card type *</label>
                            <div class="col-md-8">
                                <select required class="form-select" name="id_card_type" class="form-control">
                                    <option value="">ID Card type</option>
                                    <option>National ID Card</option>
                                    <option>Driver's Licence</option>
                                    <option>International Passport</option>
                                    <option>Voter's Card</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Expiration of ID Card </label>
                            <div class="col-md-8">
                                <input type="date" name="id_expiry_date" id="" <?= $expiryDateValue ?> class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Name of Bank *</label>
                            <div class="col-md-8">
                                <select required class="form-select" name="bank" class="form-control">
                                    <option value=''>Select Bank</option>
                                    <?= $bankOptions ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Account No *</label>
                            <div class="col-md-8">
                                <input required  <?= $accountNoValue ?>  type="text" name="account_no" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Type of Account *</label>
                            <div class="col-md-8">
                                <select required class="form-select" name="type_of_bank_account" class="form-control">
                                    <option value=''>Select Type of Account</option>
                                    <option>Current</option>
                                    <option>Saving</option>
                                    <option>Investment</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">BVN*</label>
                            <div class="col-md-8">
                                <input type="text" <?= $bvnValue ?> name="bvn" class="form-control">
                            </div>
                        </div>
                    </fieldset>
                    <hr>
                    <fieldset>
                        <legend>Payment Details</legend>
                        <div class="row mb-3">
                            <label class="col-md-5 col-form-label">Which other Bank(s)/Lending Institution are you borrowing from? </label>
                            <div class="col-md-7">
                                <input type="text" <?= $lendingInstituteValue ?> name="lending_institute" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-5 col-form-label">What is the total amount you currently owe from the various sources, including your employers if applicable</label>
                            <div class="col-md-7">
                                <input type="number" step="0.01" <?= $amountCurrencyOwnValue ?> name="amount_currently_own" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-5 col-form-label">What is the total monthly repayment you make for the existing loans</label>
                            <div class="col-md-7">
                                <input type="number" step="0.01" <?= $totalMonthlyPaymentValue ?> name="total_monthly_payment" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label id="" class="col-md-5 col-form-label">Do you have any direct payment instruction on your bank account and for how much</label>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-6">
                                        <label><input type="radio" name='directDebit' value='yes' class='al-regular-input'> Yes</label>
                                    </div>
                                    <div class="col-6">
                                        <label><input type="radio" name='directDebit' value='no' class='al-regular-input'> No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3" id="how_much">
                            <label class="col-md-5 col-form-label">Amount Been Paid</label>
                            <div class="col-md-7">
                                <input type="number" step="0.01" <?= $directPaymentYesValue ?> name="direct_payment_yes" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label id="" class="col-md-5 col-form-label">Do you have any payment obligation that is due but yet to be paid, if yes how much</label>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-6">
                                        <label><input type="radio" name='dueObligation' value='yes' class='al-regular-input'> Yes</label>
                                    </div>
                                    <div class="col-6">
                                        <label><input type="radio" name='dueObligation' value='no' class='al-regular-input'> No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3" id="price">
                            <label class="col-md-5 col-form-label">Amount Due</label>
                            <div class="col-md-7">
                                <input type="number" step="0.01" <?= $paymentObligationYesValue ?> name="payment_obligation_yes" id="" class="form-control">
                            </div>
                        </div>
                    </fieldset>
                    <hr />
                    <fieldset>
                        <legend>Loan Details</legend>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label" placeholder='#'>Loan Amount (requested) *</label>
                            <div class="col-md-8">
                                <input type="number" step="0.01" <?= $max ?> placeholder="<?= $maxPlaceholder ?>" <?= $loanAmountValue ?> name="loan_amount" required class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Loan Purpose *</label>
                            <div class="col-md-8">
                                <textarea name="loan_purpose"  required cols="30" rows="4" class="form-control" placeholder="Loan Purpose"><?= $loanPurposeValue ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Repayment Period <small>in months</small> *</label>
                            <div class="col-md-8">
                                <input required type="number" step="1"<?= $repaymentPeriodValue ?> name="repayment_period" id="" class="form-control">
                            </div>
                        </div>
                        <!-- <div class="row mb-3">
                            <label class="col-md-2 col-form-label">Date of Loan</label>
                            <div class="col-md-10">
                                <input type="date" name="date_of_loan" id="" class="form-control">
                            </div>
                        </div> -->
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label" placeholder='#'>Source of Payment *</label>
                            <div class="col-md-8">
                                <input type="text" name="source_of_payment" <?= $sourceOfPaymentValue ?> requirede class="form-control">
                            </div>
                        </div>
                    </fieldset>
                    <hr>
                    <fieldset>
                        <legend>Guarantor's Details</legend>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Guarantor's Surname </label>
                            <div class="col-md-8">
                                <input type="text" <?= $guarantorLastNameValue ?> name="guarantor_lastname" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Guarantor's First Name </label>
                            <div class="col-md-8">
                                <input type="text" <?= $guarantorFirstNameValue ?> name="guarantor_firstname" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Guarantor's Middle Name</label>
                            <div class="col-md-8">
                                <input type="text" <?= $guarantorMiddleNameValue ?> name="guarantor_middlename" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Guarantor's Email </label>
                            <div class="col-md-8">
                                <input type="email" <?= $guarantorEmailValue ?> name="guarantor_email" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Guarantor's Phone Number </label>
                            <div class="col-md-8">
                                <input type="text" <?= $guarantorPhoneValue ?> name="guarantor_phone" id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Guarantor's Contact Address </label>
                            <div class="col-md-8">
                                <textarea name="guarantor_address" id="" cols="30" rows="4" class="form-control" placeholder="Address"><?= $guarantorAddressValue ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">
                                Guarantor's Form
                                <small>(pdf max 1mb, <a href='<?= URL . "img/gurantor-form.docx" ?>' target='_blank'><u>download sample</u></a>)</small>
                            </label>
                            <div class="col-md-8">
                                <input type="file" id="myFile" name="guarantor_letter" class="form-control al-regular-input">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Guarantor's Passport <small>(.png, .jpg, max 1mb)</small> </label>
                            <div class="col-md-8">
                                <input type="file" id="myFile" name="guarantor_passport" class="form-control al-regular-input">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label">Guarantor's Means of Identification <small>(.png, .jpg, max 1mb)</small> </label>
                            <div class="col-md-8">
                                <input type="file" id="myFile" name="guarantor_identification" class="form-control al-regular-input">
                            </div>
                        </div>
                    </fieldset>
                    <hr>
                    <div class="row mb-1">
                        <h6>By completing and submitting this application, I/we understand and hereby affirm as following</h6>
                    </div>

                    <div class="terms">
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

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" required>
                        <label class="form-check-label" for="exampleCheck1">By checking this box, you are agreeing to our terms of service</label>
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
<script src="js/counter.js"></script>
<script src="js/jquery.counterup.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="js/main.js"></script>
<script src="js/visibility.js"></script>
<script>
    let chooseDirectDebit = $('input[name=directDebit]:checked', '#loanForm').val();
    console.log(chooseDirectDebit);
</script>
</body>

</html>