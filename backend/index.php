<?php
require_once "controller.php";
?>
<!DOCTYPE html>
<html lang="en">
<?= $Tag->createHead($title, $headCss) ?>

<body class="login al_login">
    <div>
        <a class="hiddenanchor" id="signup"></a>
        <a class="hiddenanchor" id="signin"></a>

        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <?= $responseLogin ?>
                    <form action='processor.php' method='post'>
                        <input type='hidden' name='action' value='login' />
                        <?= MyTag::getCSRFTokenInputTag() ?>
                        <h1>Login</h1>
                        <div>
                            <input type="email" class="form-control" placeholder="email" name="email" required />
                        </div>
                        <div>
                            <input type="password" class="form-control" placeholder="Password" name="password" required />
                        </div>
                        <div>
                            <button type='submit' class="btn btn-primary btn-sm submit">Log in</button>
                        </div>

                        <div class="clearfix"></div>

                        <div class="separator row">
                            <p class="change_link col-6">
                                <a href="<?= URL ?>apply-loan.php" class=""> apply for loan </a>
                            </p>
                            <p class="change_link col-6">
                                <a href="#signup" class="to_register"> forgot password </a>
                            </p>
                        </div>
                    </form>
                    <div class="row" style='display:block;'>
                        <?= $footerSlog ?>
                    </div>
                </section>
            </div>

            <div id="register" class="animate form registration_form">
                <section class="login_content">
                    <?= $responseForgotPassword ?>
                    <form action='processor.php' method='post'>
                        <input type='hidden' name='action' value='forgetPassword' />
                        <?= MyTag::getCSRFTokenInputTag() ?>
                        <h1>Forgot Password</h1>
                        <div>
                            <input type="email" name="email" class="form-control" placeholder="Enter Email associated with your account" required="" />
                        </div>
                        <div>
                            <button type='submit' class="btn btn-primary btn-sm submit">Submit</a>
                        </div>

                        <div class="clearfix"></div>

                        <div class="separator">
                            <p class="change_link">
                                <a href="#signin" class="to_register"> log in </a>
                            </p>

                            <div class="clearfix"></div>
                            <br />
                            <?= $footerSlog ?>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
    <?= $Tag->createFooterJS($footJs); ?>
</body>

</html>