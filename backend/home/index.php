<?php
require_once "controller.php";
?>
<!DOCTYPE html>
<html lang="en">
<?= $Tag->createHead($title) ?>

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
                    <?= $responseOperation ?>

                    <div class="row" style="display: block;">
                        <div class="tile_count">
                            <div class="col-md-3 col-sm-4  tile_stats_count">
                                <span class="count_top"><i class="fa fa-file-o"></i> New Loans</span>
                                <div class="count"><?= $totalNewLoan ?></div>
                            </div>
                            <div class="col-md-3 col-sm-4  tile_stats_count">
                                <span class="count_top"><i class="fa fa-file"></i> Loans under Review</span>
                                <div class="count"><?= $totalWipLoan ?></div>
                            </div>
                            <div class="col-md-3 col-sm-4  tile_stats_count">
                                <span class="count_top"><i class="fa fa-file-text"></i> Approved Loans</span>
                                <div class="count"><?= $totalApprovedLoan ?></div>
                            </div>
                            <div class="col-md-3 col-sm-4  tile_stats_count">
                                <span class="count_top"><i class="fa fa-file-excel-o"></i> Declined Loans</span>
                                <div class="count"><?= $totalRejectedLoan ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="display: block;">
                        <div class="col-md-4 col-sm-6">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Individuals</h2>
                                    <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <div class="tile-stats">
                                        <div class="icon"><i class="fa fa-user"></i></div>
                                        <div class="count"><?= $totalIndividual ?></div>
                                        <p>total no of individuals on the platform</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>MSME</h2>
                                    <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <div class="tile-stats">
                                        <div class="icon"><i class="fa fa-users"></i></div>
                                        <div class="count"><?= $totalMsme ?></div>
                                        <p>total no of msme on the platform</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-6  ">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Guarantors</h2>
                                    <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <div class="tile-stats">
                                        <div class="icon"><i class="fa fa-umbrella"></i></div>
                                        <div class="count"><?= $totalGuarantor ?></div>
                                        <p>total no of guarantors on the platform</p>
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