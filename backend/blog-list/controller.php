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

$title = SITENAME . " Blog Posts";
$pageName = "Blog Posts";
$sideBar = $Tag->createSideMenu(['blog', 'list'], $staffId);
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
$caption = "Blog Posts";
if (isset($_GET['category'])) {
    $Db = new Database(__FILE__, $PDO, 'blog_category');
    $categoryId = filter_var(trim($_GET['category']), FILTER_VALIDATE_INT);
    if ($Db->isDataInColumn(__LINE__, $categoryId, 'id')) {
        $where = [['column' => 'blog_category', 'comparsion' => '=', 'bindAbleValue' => $categoryId]];
        $categoryWhere = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $categoryId]];
        $result = $Db->select(__LINE__, ['id', 'name'], $categoryWhere)[0];
        $caption = "Blog Posts From {$result['name']}";
        $where = " WHERE blog_category.id = $categoryId";
    }
} else {
    $where = "";
}
$sql = "SELECT blog_post.id AS postId, blog_post.title, blog_post.content, blog_post.image, blog_post.display, blog_post.created_at, 
    profile.firstname, profile.surname, profile.middlename, profile.id as profileId, blog_category.name as category 
    FROM blog_post 
        INNER JOIN profile ON blog_post.poster_id = profile.id
        INNER JOIN blog_category ON blog_post.blog_category =  blog_category.id $where";
$Db = new Database(__FILE__, $PDO, 'blog_post');
$result = $Db->queryStatment(__LINE__, $sql)['data'];
$tr = "";
if ($result) {
    $kanter = 0;
    foreach ($result as $aResult) {
        if ($aResult['display'] == "yes") {
            $noDisplay = "<input type='radio' class='display' name='d$kanter' data-token='" . Authentication::getCSRFToken() . "' data-id='{$aResult['postId']}' value='no'/>";
            $yesDisplay = "<input type='radio' class='display' name='d$kanter' data-token='" . Authentication::getCSRFToken() . "' data-id='{$aResult['postId']}' value='yes' checked/>";
        } else {
            $noDisplay = "<input type='radio' class='display' name='d$kanter' data-token='" . Authentication::getCSRFToken() . "' data-id='{$aResult['postId']}' value='no' checked/>";
            $yesDisplay = "<input type='radio' class='display' name='d$kanter'  data-token='" . Authentication::getCSRFToken() . "' data-id='{$aResult['postId']}' value='yes'/>";
        }
        $tr .= "
            <tr>
                <td>" . ++$kanter . "</td>
                <td><a href='" . URLBACKEND . "blog-post/?id={$aResult['postId']}'><u>{$aResult['title']}</u></td>
                <td>" . Functions::getWords($aResult['content'], 50) . "</td>
                <td><img class='al-blog-list-img' src='" . Functions::ASSET_IMG_URLBACKEND . "blog/{$aResult['image']}?version=" . time() . "'/></td>
                <td>{$aResult['category']}</td>
                <td>
                    {$aResult['firstname']} {$aResult['middlename']} {$aResult['surname']}
                    <br/>" . Functions::getAcctNo($PDO, $aResult['profileId']) . "
                </td>
                <td><label>$yesDisplay - Yes</label><br/><label>$noDisplay - No</label></td>
                <td>" . date("jS F Y", strtotime($aResult['created_at'])) . "</td>
                <td>
                    <a href='" . URLBACKEND . "blog-create/?id={$aResult['postId']}' class='btn btn-sm btn-primary'>edit</a>
                    <form action='processor.php' method='post'>
                        <input type='hidden' name='action' value='delete' />
                        <input name='postId' value='{$aResult['postId']}' type='hidden'/>
                        " . MyTag::getCSRFTokenInputTag() . "
                        <button type='submit' name='submit' class='btn btn-sm btn-danger'>delete</button>
                    </form>
                </td>
            </tr>    
        ";
    }
}
