<?php
require_once dirname(__FILE__, 2) . "/connection.php";
$Authentication = new Authentication($PDO);
$loggerName = "";
$profileImage = URLBACKEND . "asset/image/profile-image/default-profile.png";
$Authentication->keyToPage();
$Tag = new MyTag($PDO);
$User = new Users($PDO);
$staffId = $_SESSION[Authentication::SESSION_NAME]['id'];
$staffInfo = $User->getInfo($staffId);
$Authentication->pageAccessor([MyUsers::STAFF['name']], $staffInfo['user_type']['type_name']);
$loggerName = "{$staffInfo['profile']['surname']} {$staffInfo['profile']['firstname']}";
$profileImage = URLBACKEND . "asset/image/profile-image/{$staffInfo['profile']['profile_image']}";

//check for valid post id
$redirect = false;
if (isset($_GET['id'])) {
    $postId = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    $Db = new Database(__FILE__, $PDO, 'blog_post');
    if (!$postId || !$Db->isDataInColumn(__LINE__, $postId, 'id')) $redirect  = true;
} else {
    $redirect = true;
}
if ($redirect) {
    header('Location: ' . URLBACKEND . "blog-create");
    exit;
}

//get post details
$sql = "SELECT blog_post.id AS postId, blog_post.title, blog_post.content, blog_post.image, blog_post.display, blog_post.created_at, 
    profile.firstname, profile.surname, profile.middlename, profile.id as profileId, blog_category.name as category 
    FROM blog_post 
        INNER JOIN profile ON blog_post.poster_id = profile.id
        INNER JOIN blog_category ON blog_post.blog_category =  blog_category.id
    WHERE blog_post.id = $postId";
$Db = new Database(__FILE__, $PDO, 'blog_post');
$result = $Db->queryStatment(__LINE__, $sql)['data'][0];
$heading = $result['title'];
$content = $result['content'];
$image = $result['image'];
$time = $result['created_at'];
$category = $result['category'];
$poster = "{$result['firstname']} {$result['middlename']} {$result['surname']}";
$display = $result['display'];
$postStatus = ($display == 'yes') ? "SHOWING" : "HIDDEN";

$title = SITENAME . " Post:: $heading";
$pageName = "Blog Post";
$sideBar = $Tag->createSideMenu(['blog'], $staffId);
$headerFiles = [
    'css' => [
        URLBACKEND . 'asset/css/jquery.dataTables.min.css',
        URLBACKEND . 'asset/css/dataTables.bootstrap.min.css',
        URLBACKEND . 'asset/css/buttons.bootstrap.min.css',
        URLBACKEND . 'asset/css/fixedHeader.bootstrap.min.css',
        URLBACKEND . 'asset/css/responsive.bootstrap.min.css',
        URLBACKEND . 'asset/css/scroller.bootstrap.min.css',
    ]
];

$footJs = [
    URLBACKEND . 'asset/js/jquery.min.js',
    URLBACKEND . 'asset/js/bootstrap.bundle.min.js',
    URLBACKEND . 'asset/js/fastclick.js',
    URLBACKEND . 'asset/js/nprogress.js',
    URLBACKEND . 'asset/js/bootstrap.bundle.min.js',
    URLBACKEND . 'asset/js/jquery.dataTables.min.js',
    URLBACKEND . 'asset/js/dataTables.bootstrap.min.js',
    URLBACKEND . 'asset/js/dataTables.buttons.min.js',
    URLBACKEND . 'asset/js/buttons.bootstrap.min.js',
    URLBACKEND . 'asset/js/buttons.flash.min.js',
    URLBACKEND . 'asset/js/buttons.html5.min.js',
    URLBACKEND . 'asset/js/buttons.print.min.js',
    URLBACKEND . 'asset/js/pdfmake.min.js',
    URLBACKEND . 'asset/js/vfs_fonts.js',
    URLBACKEND . 'asset/js/custom.js',
    URLBACKEND . 'asset/js/al-custom.js'
];

$responseOperation = "";
if ($theResponse = Tag::getResponse()) {
    $responseMessage = rtrim(implode(", ", $theResponse['messages']), ", ");
    $responseOperation = $Tag->responseTag(
        $theResponse['title'],
        $responseMessage,
        $theResponse['status']
    );
}

//display caption
$displayCaption = ($display == 'yes') ? "Hide" : "Show";

//comments
$Db = new Database(__FILE__, $PDO, 'blog_comment');
$comments = "";
$where = [['column' => 'blog_post', 'comparsion' => '=', 'bindAbleValue' => $postId]];
if ($result = $Db->select(__LINE__, [], $where)) {
    foreach ($result as $aResult) {
        $commentStatus = ($aResult['display'] == 'yes') ? "SHOWING" : "HIDDEN";
        $comments .= "
            <div class='row mb-3'>
                <div class='col-5'>
                    <p class='mb-0'>{$aResult['comment']}</p>
                    <small class='d-block'>
                        <strong>$commentStatus</strong> {$aResult['email']} on " . date('jS F Y', strtotime($aResult['created_at'])) . "
                    </small>
                </div>
            </div>
        ";
    }
}
