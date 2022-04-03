<?php
require_once "controller.php";

$email = (isset($_GET['email'])) ? $_GET['email'] : 'zeefola@gmail.com';
$token = (isset($_GET['token'])) ? $_GET['token'] : '0yA2hxPs8mZoqBj4';

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
                    <?= $responseOperation ?>
                    <form action='processor.php' method='post'>
                        <input type='hidden' name='action' value='forgetPasswordChange' />
                        <input type='hidden' name='email' value=<?= $email ?> />
                        <input type='hidden' name='token' value=<?= $token ?> />
                        <?= MyTag::getCSRFTokenInputTag() ?>
                        <h1>Change Password</h1>
                        <div>
                            <input type="password" name="password" class="form-control" placeholder="Enter new password" minlength="8" required />
                        </div>
                        <div>
                            <input type="password" name="repeatPassword" class="form-control" placeholder="Repeat password" minlength="8" required />
                        </div>
                        <div>
                            <button type='submit' class="btn btn-primary btn-sm submit">Submit</a>
                        </div>

                        <div class="clearfix"></div>

                        <div class="separator">
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