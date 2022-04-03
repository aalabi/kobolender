<?php
require_once "controller.php";
?>
<!DOCTYPE html>
<html lang="en">
<?= $Tag->createHead($title, $headerFiles) ?>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <?= $sideBar ?>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                    <nav class="nav navbar-nav">
                        <ul class=" navbar-right">
                            <li class="nav-item dropdown open" style="padding-left: 15px;">
                                <a href="javascript:;" class="user-profile" aria-haspopup="true" id="navbarDropdown" data-toggle="dropdown" aria-expanded="false">
                                    <?= $loggerName ?>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="page-title">
                        <div class="title_left">
                            <h3><?= $pageName ?></h3>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <div class="row">
                        <?= $responseOperation ?>
                        <div class="col-md-12 col-sm-12 ">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>
                                        <?= "{$profileInfo['profile']['surname']}" ?>
                                        <small>
                                            <u>
                                                <?= "{$profileInfo['profile']['middlename']} {$profileInfo['profile']['firstname']} $profileAccoutNo" ?>
                                            </u>
                                        </small>
                                    </h2>
                                    <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <div class="col-md-3 col-sm-3  profile_left">
                                        <div class="profile_img">
                                            <div id="crop-avatar">
                                                <img class="img-responsive avatar-view al-passport" src="<?= URLBACKEND . "asset/image/loan.png" ?>" alt="Avatar">
                                            </div>
                                        </div>
                                        <ul class="list-unstyled user_data">
                                            <li class="m-top-xs">
                                                <i class="fa fa-asterisk user-profile-icon"></i>
                                                <strong>Status: </strong>
                                                <?= strtoupper($loanInfo['status']) ?><br />
                                                <?= $rejectionReason ?>
                                                <small><?= $approver ?></small>
                                            </li>

                                            <li class="m-top-xs">
                                                <i class="fa fa-asterisk user-profile-icon"></i>
                                                <strong>Approved Amount: </strong>
                                                <?= number_format($loanInfo['approved_amount'], 2) ?>
                                            </li>

                                            <li class="m-top-xs">
                                                <i class="fa fa-asterisk user-profile-icon"></i>
                                                <strong>Applied Amount: </strong>
                                                <?= number_format($loanInfo['amount'], 2) ?>
                                            </li>

                                            <li class="m-top-xs">
                                                <i class="fa fa-asterisk user-profile-icon"></i>
                                                <strong>Purpose: </strong>
                                                <?= $loanInfo['purpose'] ?>
                                            </li>

                                            <li class="m-top-xs">
                                                <i class="fa fa-asterisk user-profile-icon"></i>
                                                <strong>Repayment Source: </strong>
                                                <?= $loanInfo['repymt_source'] ?>
                                            </li>

                                            <li class="m-top-xs">
                                                <i class="fa fa-asterisk user-profile-icon"></i>
                                                <strong>Application Date: </strong>
                                                <?= date("jS F Y", strtotime($loanInfo['created_at'])) ?>
                                            </li>

                                        </ul>

                                        <!-- start other account info -->
                                        <h4><strong><?= $guarantorsCaption ?></strong></h4>
                                        <ul class="list-unstyled user_data">
                                            <li>
                                                <p><a href="<?= URLBACKEND . "a-guarantor/?id=$gId" ?>"><u><?= $gurantorsName ?></u></a>
                                                </p>
                                                <p><?= $gurantorsPhone ?></p>
                                                <p><?= $gurantorsEmail ?></p>
                                            </li>
                                        </ul>
                                        <!-- end of other account info -->

                                        <!-- start other account info -->
                                        <h4><strong>Repayment</strong></h4>
                                        <form action='' method='post'>
                                            <script src="https://checkout.flutterwave.com/v3.js"></script>
                                            <div class="row">
                                                <div class="col-12">
                                                    <span class='d-block'>amount</span>
                                                    <div class='form-group'>
                                                        <input <?= $disabled ?> step="2" required type='number' max='<?= $max ?>' id='amount' class='form-control'>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class='form-group'>
                                                        <button <?= $disabled ?> type="button" onClick="makePayment()" class='btn btn-primary'>Pay</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        <script>
                                            function makePayment() {
                                                let amtPaid = document.getElementById("amount").value;
                                                let url = "<?= URLBACKEND . Functions::pwdName(__FILE__) . "/?id=" . $id ?>&amount=" + amtPaid;
                                                FlutterwaveCheckout({
                                                    public_key: "<?= PUBLIC_KEY ?>",
                                                    tx_ref: "<?= $trnRef ?>",
                                                    amount: amtPaid,
                                                    currency: "NGN",
                                                    country: "NG",
                                                    payment_options: " ",
                                                    redirect_url: url,
                                                    meta: {
                                                        consumer_id: 23,
                                                        consumer_mac: "92a3-912ba-1192a",
                                                    },
                                                    customer: {
                                                        email: "<?= $profileInfo['login']['email'] ?>",
                                                        name: "<?= "{$profileInfo['profile']['firstname']} {$profileInfo['profile']['surname']}" ?>",
                                                    },
                                                    callback: function(data) {
                                                        console.log(data);
                                                    },
                                                    onclose: function() {
                                                        // close modal
                                                    },
                                                    customizations: {
                                                        title: "Loan Repayment",
                                                        description: "Loan repayment for loan ".<?= $loanAccoutNo ?>,
                                                        logo: "<?= Functions::ASSET_IMG_URLBACKEND . Functions::LOGO ?>",
                                                    },
                                                });
                                            }
                                        </script>
                                        <!-- end of other account info -->
                                    </div>

                                    <div class="col-md-9 col-sm-9 ">
                                        <div class="profile_title">
                                            <div class="col-md-6">
                                                <h2>Other Lender's Information</h2>
                                            </div>
                                        </div>
                                        <div>
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th style="width:60%">
                                                            BUSINESS OWNER
                                                        </th>
                                                        <td><strong><?= $businessName ?></strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Other bank(s)/Lending Institution borrowing from?
                                                        </th>
                                                        <td><?= $loanInfo['other_bank_own'] ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Total amount owe from all sources (employers inclusive)
                                                        </th>
                                                        <td><?= ($loanInfo['amt_own']) ? number_format($loanInfo['amt_own'], 2) : "0.00" ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total monthly repayment from all existing loans</th>
                                                        <td><?= ($loanInfo['mthly_repymt']) ? number_format($loanInfo['mthly_repymt'], 2) : "0.00" ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Direct Debit on bank account (amount)</th>
                                                        <td><?= ($loanInfo['direct_debit_amt']) ? number_format($loanInfo['direct_debit_amt'], 2) : "0.00" ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Outstanding obligation yet to be paid(amount)</th>
                                                        <td><?= ($loanInfo['outstand_obligation']) ? number_format($loanInfo['outstand_obligation'], 2) : "0.00" ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Repayment Period <small>in months</small></th>
                                                        <td><?= ($loanInfo['repayment_period']) ? $loanInfo['repayment_period'] : "" ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Repayment Source </th>
                                                        <td><?= ($loanInfo['repymt_source']) ? $loanInfo['repymt_source'] : "" ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Collateral</th>
                                                        <td><?= $collateral ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <br /><br />

                                        <div class="profile_title">
                                            <div class="col-md-6">
                                                <h2>Balance <?= number_format($balance, 2) ?></h2>
                                            </div>
                                        </div>
                                        <!-- start of user transactions -->
                                        <div>
                                            <table id="datatable-buttons" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Description</th>
                                                        <th>Debit &#8358;</th>
                                                        <th>Credit &#8358;</th>
                                                        <th>Balance &#8358; </th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?= $tr ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <br /><br />
                                        <!-- end of user transactions -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /page content -->

            <!-- footer content -->
            <?= $Tag->createFooterSlogan() ?>
            <!-- /footer content -->
        </div>
    </div>

    <?= $Tag->createFooterJS($footJs); ?>
</body>

</html>