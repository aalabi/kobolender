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

                    <!-- <div class="row">                        
                        <div class="col-md-12 col-sm-12  ">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Find a Loan</h2>
                                    <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                                        <li>
                                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <form action='processor.php' method='post'>
                                        <input type='hidden' name='action' value='search' />
                                        <?= MyTag::getCSRFTokenInputTag() ?>
                                        <div class="row">
                                            <div class="col-6">
                                                <span class='d-block'>loan no</span>
                                                <div class='form-group'>
                                                    <input type='text' name='loanNo' class='form-control'>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <span class='d-block'>account no</span>
                                                <div class='form-group'>
                                                    <input type='text' name='accountNo' class='form-control'>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class='form-group'>
                                                    <button type='submit' name='submit' class='btn btn-primary'>Find</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div> -->

                    <div class="row">
                        <?= $responseOperation ?>
                        <div class="col-md-12 col-sm-12  ">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>List of Loan under Review</h2>
                                    <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <table id="datatable-buttons" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Loan Info</th>
                                                <th>Applicant's Info</th>
                                                <th>Guarantor's Info</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?= $tr ?>
                                        </tbody>
                                    </table>
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