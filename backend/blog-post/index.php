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
                                    <h2><?= $heading ?></h2>
                                    <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                                        <li>
                                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <div class="row mb-5">
                                        <div class="col-10 offset-2">
                                            <img class="img-responsive al-blog-img" src="<?= Functions::ASSET_IMG_URLBACKEND . "blog/{$image}?version=" . time() ?>">
                                        </div>
                                        <small class="d-block">
                                            <strong class='d-block'><?= $postStatus ?></strong>
                                            posted by <?= $poster ?> on <?= date("jS F Y", strtotime($time)) ?>
                                        </small>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <p><?= $content ?></p>
                                        </div>
                                    </div>
                                    <strong class="d-block">Comments</strong>
                                    <?= $comments ?>
                                    <form method="post" action='processor.php' class="row">
                                        <?= MyTag::getCSRFTokenInputTag() ?>
                                        <input type="hidden" name="postId" value="<?= $postId ?>" />
                                        <div class="col-4 text-center">
                                            <button name='action' value='hideShow' type="submit" class="btn btn-warning"><?= $displayCaption ?></button>
                                        </div>
                                        <div class="col-4 text-center">
                                            <button name='action' value='edit' type="submit" class="btn btn-primary">Edit</button>
                                        </div>
                                        <div class="col-4 text-center">
                                            <button name='action' value='delete' type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                    </form>
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