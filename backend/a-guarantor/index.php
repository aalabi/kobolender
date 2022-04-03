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
                                            <?= "{$info['profile']['middlename']} {$info['profile']['firstname']}" ?>
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
                                        <?= $guarantorData ?>

                                        <!-- <a class="btn btn-secondary btn-sm" href="<?= URLBACKEND ?>a-user-edit/?id=<?= $id ?>">
                                            Edit
                                        </a> -->
                                        <br />

                                    </div>
                                    <div class="col-md-9 col-sm-9 ">
                                        <div class="profile_title">
                                            <div class="col-md-6">
                                                <h2>Those Guaranteed</h2>
                                            </div>
                                        </div>
                                        <!-- start of user-activity-graph -->
                                        <div>
                                            <table class="table">
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