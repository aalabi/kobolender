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
                                    <h2><?= $createEditCaption ?></h2>
                                    <ul class="nav navbar-right panel_toolbox al_panel_toolbox">
                                        <li>
                                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        </li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <form action='processor.php' method='post' enctype='multipart/form-data'>
                                        <input type='hidden' name='action' value='<?= $mode ?>' />
                                        <?= $postIdInput ?>
                                        <?= MyTag::getCSRFTokenInputTag() ?>
                                        <div class="row">
                                            <div class="col-6">
                                                <span class='d-block'>title *</span>
                                                <div class='form-group'>
                                                    <input value="<?= $postTitle ?>" required type='text' name='title' class='form-control'>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <span class='d-block'>
                                                    blog image <small class='text-danger'>.png, .jpg, max 1.2mb, aspect ratio 16:9</small>
                                                    <?= $image ?> <?= $imageRequiredAsterisk ?>
                                                </span>
                                                <div class='form-group'>
                                                    <input <?= $imageRequired ?> type='file' name='image' class='form-control'>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <span class='d-block'>cateogry *</span>
                                                <div class='form-group'>
                                                    <select required name='category' class='form-control'>
                                                        <option value="">Pick a Category</option>
                                                        <?= $catgoryOption ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <span class='d-block'>display *</span>
                                                <div class='form-group row'>
                                                    <label class='col-6'>
                                                        <input required <?= $yesCheck ?> value="yes" type='radio' name='display'> - Yes
                                                    </label>
                                                    <label class='col-6'>
                                                        <input <?= $noChecked ?> value="no" type='radio' name='display'> - No
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <span class='d-block'>content</span>
                                                <div class='form-group'>
                                                    <textarea name='content' class='form-control'><?= $content ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-4">
                                                <div class='form-group'>
                                                    <button type='submit' name='submit' class='btn btn-primary'><?= $btnCaption ?></button>
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