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

$title = SITENAME . " Blog Comment";
$pageName = "Blog Comment";
$sideBar = $Tag->createSideMenu(['blog', 'comment'], $staffId);
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
    URLBACKEND . 'asset/js/al-custom.js',
    'processor-status.js'
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

$where = [];
$caption = "List of Comments";
if (isset($_GET['postId'])) {
    $Db = new Database(__FILE__, $PDO, 'blog_post');
    $postId = filter_var(trim($_GET['postId']), FILTER_VALIDATE_INT);
    if ($Db->isDataInColumn(__LINE__, $postId, 'id')) {
        $where = [['column' => 'blog_post', 'comparsion' => '=', 'bindAbleValue' => $postId]];
        $postWhere = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $postId]];
        $result = $Db->select(__LINE__, ['id', 'title'], $postWhere)[0];
        $caption = "List of Comments on <a href='" . URLBACKEND . "blog-post/?postId={$result['id']}'><u>{$result['title']}</u></a>";
    }
}
//SELECT ``, ``, ``, ``, ``, ``, `updated_at` FROM `blog_comment` WHERE 1
$sql = "SELECT blog_comment.id, blog_comment.blog_post, blog_comment.comment, blog_comment.email, blog_comment.display, 
    blog_comment.created_at, blog_post.title, blog_comment.blog_post as postId
    FROM blog_comment 
        INNER JOIN blog_post ON blog_comment.blog_post = blog_post.id";
$Db = new Database(__FILE__, $PDO, 'blog_comment');
$result = $Db->queryStatment(__LINE__, $sql)['data'];

$tr = "";
if ($result) {
    $kanter = 0;
    foreach ($result as $aResult) {
        if ($aResult['display'] == "yes") {
            $noDisplay = "<input type='radio' class='display' name='d$kanter' data-token='" . Authentication::getCSRFToken() . "' data-id='{$aResult['id']}' value='no'/>";
            $yesDisplay = "<input type='radio' class='display' name='d$kanter' data-token='" . Authentication::getCSRFToken() . "' data-id='{$aResult['id']}' value='yes' checked/>";
        } else {
            $noDisplay = "<input type='radio' class='display' name='d$kanter' data-token='" . Authentication::getCSRFToken() . "' data-id='{$aResult['id']}' value='no' checked/>";
            $yesDisplay = "<input type='radio' class='display' name='d$kanter'  data-token='" . Authentication::getCSRFToken() . "' data-id='{$aResult['id']}' value='yes'/>";
        }

        $tr .= "
            <tr>
                <td>" . ++$kanter . "</td>
                <td><a href='" . URLBACKEND . "blog-post/?id={$aResult['postId']}'><u>{$aResult['title']}</u></a></td>
                <td>{$aResult['comment']}</td>
                <td>{$aResult['email']}</td>
                <td><label>$yesDisplay - Yes</label><br/><label>$noDisplay - No</label></td>
                <td>" . date("jS F Y", strtotime($aResult['created_at'])) . "</td>
                <td>
                    <form action='processor.php' method='post'>
                        <input type='hidden' name='action' value='delete' />
                        <input name='commentId' value='{$aResult['id']}' type='hidden'/>
                        " . MyTag::getCSRFTokenInputTag() . "
                        <button type='submit' name='submit' class='btn btn-sm btn-danger'>delete</button>
                    </form>
                </td>
            </tr>    
        ";
    }
}
