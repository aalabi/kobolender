<?php
$pageName = " KOBOLENDER IS THE BEST IN THE WORLD";
require_once('template/head.php');
$Db = new Database(__FILE__, $PDO, 'blog_post');

//check for postId
if (isset($_GET['id']) && $postId = filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    if (!$Db->isDataInColumn(__LINE__, $postId, 'id')) {
        header("Location: " . URL . "blog.php");
        exit;
    } else {
        $sql = "SELECT count(id) as totalCount FROM blog_comment WHERE blog_post = :postId and display = 'yes'";
        $totalComment = $Db->queryStatment(__LINE__, $sql, ['postId' => $postId])['data'][0]['totalCount'];
        $Db->setTable('blog_post');
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $postId]];
        $postInfo = $Db->select(__LINE__, [], $where)[0];
    }
} else {
    header("Location: " . URL . "blog.php");
    exit;
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

//recent post
$recentPosts = "";
$Db->setTable('blog_post');
$where = [['column' => 'display', 'comparsion' => '=', 'bindAbleValue' => 'yes']];
if ($postCollection = $Db->select(__LINE__, [], $where, ['id' => 'DESC'], [4])) {
    foreach ($postCollection as $aPost) {
        $recentPosts .= "
            <div class='card mb-4' style='max-width: 540px;'>
                <div class='row g-0'>
                    <div class='col-md-4'>
                        <img src='" . Functions::ASSET_IMG_URLBACKEND . "blog/{$aPost['image']}' class='img-fluid rounded-start' alt='...'>
                    </div>
                    <div class='col-md-8'>
                        <div class='card-body'>
                            <h6 class='card-title'>{$aPost['title']}</h6>
                            <p class='card-text'>
                                " . Functions::getWords($aPost['content'], 10) . "
                                <a href='" . URL . "a-blog.php?id={$aPost['id']}'>...</a>
                            </p>
                            <p class='card-text'><small class='text-muted'>" . date("g:i a jS, M'y", strtotime($aPost['created_at'])) . "</small></p>
                        </div>
                    </div>
                </div>
            </div>
        ";
    }
}

$Db = new Database(__FILE__, $PDO, 'blog_comment');
$where = [
    ['column' => 'blog_post', 'comparsion' => '=', 'bindAbleValue' => $postId, 'logic' => 'AND'],
    ['column' => 'display', 'comparsion' => '=', 'bindAbleValue' => 'yes'],
];

$comments = "";
if ($commentCollections = $Db->select(__LINE__, [], $where, ['id' => 'DESC'])) {
    foreach ($commentCollections as $aComment) {
        $comments .=
            "<div class='row mb-3'>
                <div class='col-10'>
                    <p class='mb-0'>{$aComment['comment']}</p>
                    <small class='d-block'>
                        {$aComment['email']} on " . date('jS F Y', strtotime($aComment['created_at'])) . "
                    </small>
                </div>
            </div>";
    }
}

//comment posting response
$responseOperation = "";
$Tag = new MyTag($PDO);
if ($theResponse = Tag::getResponse()) {
    $responseMessage = rtrim(implode(", ", $theResponse['messages']), ", ");
    $responseOperation = $Tag->responseTag(
        $theResponse['title'],
        $responseMessage,
        $theResponse['status']
    );
}

require_once('template/header.php');
require_once('template/mast-head.php');
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
                                <div class="card img-fluid mb-5">
                                    <img src="<?= Functions::ASSET_IMG_URLBACKEND . "blog/" . $postInfo['image'] ?>" class="card-img-top" alt="...">
                                    <div class="card-title">
                                        <h3><?= strtoupper($postInfo['title']) ?></h3>
                                    </div>
                                    <div class="card-body" style="text-align: justify;">
                                        <div class="conman">
                                            <ul class='post-meta' style="display: flex; flex-direction:row; padding:0; list-style-type: none;">
                                                <li><small class="text-muted"><i class="fa fa-comments"></i> <a href="#"><?= $totalComment ?> comment</small></a></li>
                                                <li style="padding-left: 20px;">
                                                    <small class="text-muted">
                                                        <i class='fa fa-clock-o'></i>
                                                        <?= date("M jS, Y", strtotime($postInfo['created_at'])) ?>
                                                    </small>
                                                </li>
                                            </ul>
                                        </div>
                                        <?= $postInfo['content'] ?>
                                    </div>
                                </div>

                                <div id="comments" class="col-md-12 shadow rounded p-5">
                                    <?= $comments ?>
                                    <!-- <div class='row mb-3'>
                                        <div class='col-5'>
                                            <p class='mb-0'>{$aResult['comment']}</p>
                                            <small class='d-block'>
                                                <strong>$commentStatus</strong> {$aResult['email']} on " . date('jS F Y', strtotime($aResult['created_at'])) . "
                                            </small>
                                        </div>
                                    </div> -->
                                </div>

                                <div id="reply" class="col-md-12 shadow rounded p-5">
                                    <?= $responseOperation ?>
                                    <div class="mb-4">
                                        <h3>Leave a Reply</h3>
                                        <small class="text-muted">Your email address will not be published. Required fields are marked *</small>
                                    </div>
                                    <form action="a-blog-processor.php" method="post">
                                        <?= MyTag::getCSRFTokenInputTag() ?>
                                        <input required type="hidden" name="postId" value="<?= $postId ?>">
                                        <div class="row">
                                            <div class=" mb-3">
                                                <input required type="email" name="email" id="" class="form-control" placeholder="Email*">
                                            </div>
                                            <div class="mb-3">
                                                <textarea required class="form-control" name="comment" placeholder="Message*" id="exampleFormControlTextarea1" rows="3"></textarea>
                                            </div>
                                            <div class="d-grid gap-2">
                                                <button class="btn btn-success" type="submit" name="submit">Post Comment</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

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
                                <h3 class="mb-4">Recent Posts</h3>
                                <?= $recentPosts ?>
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