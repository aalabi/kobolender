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
                                        <?= $profileInfo['profile']['surname'] ? $profileInfo['profile']['surname'] : $businessName ?>
                                        <small>
                                            <a href="<?= URLBACKEND ?>a-user/?id=<?= $loanInfo['profile_id'] ?>">
                                                <u>
                                                    <?= "{$profileInfo['profile']['middlename']} {$profileInfo['profile']['firstname']} $profileAccoutNo" ?>
                                                </u>
                                            </a>
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
                                                <strong>Application Date: </strong>
                                                <?= date("jS F Y", strtotime($loanInfo['created_at'])) ?>
                                            </li>

                                        </ul>

                                        <!-- start other account info -->
                                        <h4><strong><?= $guarantorsCaption ?></strong></h4>
                                        <ul class="list-unstyled user_data">
                                            <li>
                                                <p><a href="<?= URLBACKEND . "a-guarantor/?id=$gId" ?>"><u><?= $gurantorsName ?></u></a></p>
                                                <p><?= $gurantorsPhone ?></p>
                                                <p><?= $gurantorsEmail ?></p>
                                            </li>
                                        </ul>
                                        <!-- end of other account info -->

                                        <!-- start other account info -->
                                        <h4><strong>Repayment</strong></h4>
                                        <form action='processor.php' method='post'>
                                            <input type='hidden' name='action' value='search' />
                                            <?= MyTag::getCSRFTokenInputTag() ?>
                                            <input type='hidden' name='loanId' value='<?= $id ?>' />
                                            <div class="row">
                                                <div class="col-12">
                                                    <span class='d-block'>amount</span>
                                                    <div class='form-group'>
                                                        <input <?= $disabled ?> step="0.01" required type='number' max='<?= $max ?>' name='amount' class='form-control'>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class='form-group'>
                                                        <button <?= $disabled ?> type='submit' name='submit' class='btn btn-primary'>Post</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
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
                                        <!-- start of user-activity-graph -->
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
                                        <!-- end of user-activity-graph -->
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