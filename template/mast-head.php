<!---About us section-->
<?php
$pageName = isset($pageName) ? $pageName : '';
?>
<section class="about-management">
    <div class="container">
        <div class="row" style="padding-top: 60px;">
            <div class="about-management-text text-center pt-5">
                <h1>
                    <?= $pageName ?>
                </h1>
                <div class="small-text">
                    <p class="my-text <?= isset($pageName) ? 'active2' : '' ?>"><?= $pageName ?></p>

                </div>
            </div>
        </div>
    </div>
</section>
<!---End of About us section-->