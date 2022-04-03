<?php
$pageName = "Blog";
require_once('template/head.php');
require_once('template/header.php');
require_once('template/mast-head.php');

//check for categoryId
$categoryId = null;
if (isset($_GET['cateoryId']) && $categoryId = filter_var($_GET['cateoryId'], FILTER_VALIDATE_INT)) {
    $Db = new Database(__FILE__, $PDO, 'blog_category');
    if (!$Db->isDataInColumn(__LINE__, $categoryId, 'id')) $categoryId = null;
}

//blog post
$blog = "
    <div class='card img-fluid mb-5'>                    
        <div class='card-body'>        
            THERE IS NO POST YET
        </div>
    </div>
";
$Db = new Database(__FILE__, $PDO, 'blog_post');
$where = [['column' => 'display', 'comparsion' => '=', 'bindAbleValue' => 'yes']];
if ($categoryId) {
    $where[0]['logic'] = 'AND';
    $where[] = ['column' => 'blog_category', 'comparsion' => '=', 'bindAbleValue' => $categoryId];
}
if ($blogCollection = $Db->select(__LINE__, [], $where)) {
    $blog = "";
    foreach ($blogCollection as $aBlog) {
        $sql = "SELECT count(id) as totalCount FROM blog_comment WHERE blog_post = :postId AND display = 'yes'";
        $totalComment = $Db->queryStatment(__LINE__, $sql, ['postId' => $aBlog['id']])['data'][0]['totalCount'];
        $blog .= "
            <div class='card img-fluid mb-5'>                    
                <img src='" . Functions::ASSET_IMG_URLBACKEND . "blog/{$aBlog['image']}' class='card-img-top' alt='...'>
                <div class='card-title'><h3>" . strtoupper($aBlog['title']) . "</h3></div>
                <div class='card-body'>
                <div class='conman'>
                    <ul class='post-meta' style='display: flex; flex-direction:row; padding:0; list-style-type: none;'>
                        <li><small class='text-muted'><i class='fa fa-comments'></i> <a href='#'>$totalComment comment</small></a></li>
                        <li style='padding-left: 20px;'><small class='text-muted'><i class='fa fa-clock-o'></i> " . date("M jS, Y", strtotime($aBlog['created_at'])) . "</small></li>
                    </ul>
                </div>
                    <p class='card-text'>" . Functions::getWords($aBlog['content'], 30) . "</p>
                    <a href='" . URL . "a-blog.php?id={$aBlog['id']}'><button class='btn btn-block bg-success text-white'>Read more...</button></a>
                </div>
            </div>
        ";
    }
}

//category list
$categories = "<li><a href=''>No Category</a></li>";
$Db->setTable('blog_category');
if ($categoryCollection = $Db->select(__LINE__, [], [])) {
    $categories = "";
    foreach ($categoryCollection as $aCategory) {
        $categories .= "<li><a href='" . URL . "blog.php?cateoryId={$aCategory['id']}'>{$aCategory['name']}</a></li>";
    }
}

?>
<!--Ownership and Management section--->
<section class="mt-5">
    <div class="container">
        <div class="row">
            <div class="about-sec">
                <!--FAQs section-->
                <section class="mt-5 mb-5">
                    <div class="container">
                        <div class="about-sec">
                            <h3 class="text-center">Blog</h3>
                            <div class="divider"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <?= $blog ?>
                            </div>
                            <div class="col-md-3">
                                <!-- <form class="d-flex mb-4">
                                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                    <button class="btn btn-outline-success" type="submit">Search</button>
                                </form> -->
                                <h3 class="mb-4">Categories</h3>
                                <ul>
                                    <?= $categories ?>
                                </ul>
                                <!-- <h3 class="mb-4">Recent Posts</h3>
                                <div class="card mb-4" style="max-width: 540px;">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <img src="img/africa-america.jpg" class="img-fluid rounded-start" alt="...">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h6 class="card-title">KoboLender is the best</h6>
                                                <p class="card-text">We are the best in the lending sector.</p>
                                                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-4" style="max-width: 540px;">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <img src="img/fin1.jpg" class="img-fluid rounded-start" alt="...">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h6 class="card-title">IMF introduce loan promo</h6>
                                                <p class="card-text">IMF have introduced loan promo.</p>
                                                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-4" style="max-width: 540px;">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <img src="img/business-woman-2756210_640.jpg" class="img-fluid rounded-start" alt="...">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h6 class="card-title">Big boom for Nollywood industry</h6>
                                                <p class="card-text">Entertainment industry booms with financial endorsement.</p>
                                                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-4" style="max-width: 540px;">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <img src="img/ororo.jpg" class="img-fluid rounded-start" alt="...">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h6 class="card-title">Manchester United's season almost over</h6>
                                                <p class="card-text">United were shambolic and disunited against the Saints.</p>
                                                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>


                            <div>

                            </div>
                        </div>
                    </div>
                </section>
                <!---End of FAQs section-->



            </div>
        </div>
</section>
<!--End of Ownership and Management section--->

<!---Footer section-->
<?php
require_once('template/footer.php');
?>

<!---End of Footer-->
<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="js/counter.js"></script>
<script src="js/jquery.counterup.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="js/main.js"></script>

</body>

</html>