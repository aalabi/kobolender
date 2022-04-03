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
                                        <?= "{$info['profile']['surname']}" ?>
                                        <small>
                                            <?= "{$info['profile']['middlename']} {$info['profile']['firstname']} ({$info['login']['status']})" ?>
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
                                                <img class="img-responsive avatar-view al-passport" src="<?= URLBACKEND . "asset/image/profile-image/{$info['profile']['profile_image']}" ?>" alt="Avatar" title="Change the avatar">
                                            </div>
                                        </div>
                                        <ul class="list-unstyled user_data">
                                            <li class="m-top-xs">
                                                <i class="fa fa-phone user-profile-icon"></i>
                                                <a href="tel:<?= "{$info['login']['phone']}" ?>">
                                                    <?= "{$info['login']['phone']}" ?>
                                                </a>
                                            </li>

                                            <li class="m-top-xs">
                                                <i class="fa fa-envelope user-profile-icon"></i>
                                                <a href="mailto:<?= "{$info['login']['email']}" ?>">
                                                    <?= "{$info['login']['email']}" ?>
                                                </a>
                                            </li>

                                            <li>
                                                <i class="fa fa-briefcase user-profile-icon"></i>
                                                <?= $company ?>
                                            </li>

                                            <li>
                                                <i class="fa fa-map-marker user-profile-icon"></i>
                                                <?= "{$info['profile']['address']}" ?>
                                            </li>

                                        </ul>

                                        <!-- <a class="btn btn-secondary btn-sm" href="<?= URLBACKEND ?>a-user-edit/?id=<?= $id ?>">
                                            Edit
                                        </a> -->
                                        <br />

                                        <!-- start other account info -->
                                        <h4><strong><?= $guarantorsCaption ?></strong></h4>
                                        <ul class="list-unstyled user_data">
                                            <li>
                                                <p>
                                                    <a href="<?= URLBACKEND . "a-guarantor/?id=$gId" ?>"><u><?= $gurantorsName ?></u></a>
                                                </p>
                                                <?= $additionalGurantors ?>
                                                <?= $guarantorData ?>
                                            </li>
                                        </ul>
                                        <!-- end of other account info -->

                                        <h4><strong>My Loans</strong></h4>
                                        <ul class="list-unstyled user_data">
                                            <?= $loansLi ?>
                                        </ul>
                                    </div>
                                    <div class="col-md-9 col-sm-9 ">
                                        <div class="profile_title">
                                            <div class="col-md-6">
                                                <h2>Other Information</h2>
                                            </div>
                                        </div>
                                        <!-- start of user-activity-graph -->
                                        <div>
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th style="width:40%">
                                                            BUSINESS OWNER
                                                        </th>
                                                        <td><strong><?= $businessName ?></strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Date of Birth</th>
                                                        <td><?= $dob ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Employer</th>
                                                        <td><?= $employerName ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Employer's Address</th>
                                                        <td><?= $employerAddress ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Recent Bank Statement</th>
                                                        <td><?= $paySlipDoc ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Means of Identification</th>
                                                        <td><?= "<a target='_blank' href='$idCardUrl'><u>$idCard</u></a>" ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>ID Expiration Date</th>
                                                        <td><?= $idCardExpirationDate ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>BVN</th>
                                                        <td><?= $bvn ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Source of Income</th>
                                                        <td><?= $sourceIncome ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Bank Details</th>
                                                        <td><?= $bankDetails ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Asset for Collateral</th>
                                                        <td><?= $assetCollateral ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Business Registration Document</th>
                                                        <td><?= $bizReg ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Nature of Business</th>
                                                        <td><?= $natureBusiness ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>TIN</th>
                                                        <td><?= $tin ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Bank Statement</th>
                                                        <td><?= "$companyBankStatment $companyBankStatmentII" ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Financial Statement</th>
                                                        <td><?= $companyFinances ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Business Contact</th>
                                                        <td><?= $companyAddres . $companyPhone ?></td>
                                                    </tr>
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