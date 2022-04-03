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
                        <div class="col-md-12 col-sm-12  ">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>New Loan</h2>
                                    <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                                        <li>
                                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <form action='processor.php' method='post' enctype='multipart/form-data'>
                                        <input type='hidden' name='action' value='create' />
                                        <?= MyTag::getCSRFTokenInputTag() ?>
                                        <div class="row">
                                            <div class="col-4">
                                                <span class='d-block'>Loan type *</span>
                                                <div class='form-group'>
                                                    <select name='loanType' class='form-control' required>
                                                        <option value=''>Pick a Type</option>
                                                        <?= $loanTypeOptions ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <span class='d-block'>Loan amount (requested) *</span>
                                                <div class='form-group'>
                                                    <input type='number' name='loanAmount' class='form-control' required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <span class='d-block'>Loan purpose *</span>
                                                <div class='form-group'>
                                                    <input type='text' name='loanPurpose' class='form-control' required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <span class='d-block'>Bank/lending Institution borrowing from?</span>
                                                <div class='form-group'>
                                                    <input type='text' name='borrowerBank' class='form-control'>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <span class='d-block'>Total indebtedness (employers inclusive)</span>
                                                <div class='form-group'>
                                                    <input type='number' step='0.01' name='totalDebt' class='form-control'>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <span class='d-block'>Monthly repayment from indebtedness</span>
                                                <div class='form-group'>
                                                    <input type='number' step='0.01' name='mnthlyRepymt' class='form-control'>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <span class='d-block'>Direct debit amount on bank account</span>
                                                <div class='form-group'>
                                                    <input type='number' step='0.01' name='directDebit' class='form-control'>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <span class='d-block'>Outstanding obligation</span>
                                                <div class='form-group'>
                                                    <input type='number' step='0.01' name='obligation' class='form-control'>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <span class='d-block'>Repayment period in months *</span>
                                                <div class='form-group'>
                                                    <input type='number' name='repymtPeriod' class='form-control' required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <span class='d-block'>Repayment source *</span>
                                                <div class='form-group'>
                                                    <input type='text' name='repymtSource' class='form-control' required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <span class='d-block'>Collateral *</span>
                                                <div class='form-group'>
                                                    <input type='text' name='collateral' class='form-control' required>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <span class='d-block'>
                                                    Upload collateral document *
                                                    <small class='text-danger'>pdf, jpg, png, max 1mb</small>
                                                </span>
                                                <div class='form-group'>
                                                    <input type='file' name='collateralFile' class='form-control' required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class='form-group'>
                                                    <button <?= $disabled ?> type='submit' name='submit' class='btn btn-primary'>Apply</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
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