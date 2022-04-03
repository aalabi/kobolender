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

$title = SITENAME . " Blog Category";
$pageName = "Blog Category";
$sideBar = $Tag->createSideMenu(['blog', 'category'], $staffId);
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

//default mode
$mode = 'create';

//check for edit mode
$name = $description = $catgoryIdInput = "";
if (isset($_GET['id'])) {
    $categoryId = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    $Db = new Database(__FILE__, $PDO, 'blog_category');
    if ($categoryId && $Db->isDataInColumn(__LINE__, $categoryId, 'id')) {
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $categoryId]];
        $result = $Db->select(__LINE__, [], $where)[0];
        $mode = 'edit';
        $name = $result['name'];
        $description = $result['description'];
        $catgoryIdInput = "<input name='categoryId' value='$categoryId' type='hidden'/>";
    }
}

//create edit caption
$createEditCaption = ($mode == 'create') ? "Create Blog Category" : "Edit Blog Category";
$btnCaption = ($mode == 'create') ? "Create" : "Edit";

$Db = new Database(__FILE__, $PDO, 'blog_category');
$result = $Db->select(__LINE__, [], []);
$tr = "";
if ($result) {
    $kanter = 0;
    $Db->setTable('blog_post');
    foreach ($result as $aResult) {
        $page = Functions::pwdName(__FILE__);
        $sql = "SELECT count(id) as totalCount FROM blog_post WHERE blog_category = :category";
        $postCount = $Db->queryStatment(__LINE__, $sql, ['category' => $aResult['id']])['data'][0]['totalCount'];
        $deleteBtn = "";
        if (!$postCount) {
            $deleteBtn = "
                <form action='processor.php' method='post'>
                    <input type='hidden' name='action' value='delete' />
                    <input name='categoryId' value='{$aResult['id']}' type='hidden'/>
                    " . MyTag::getCSRFTokenInputTag() . "
                    <button type='submit' name='submit' class='btn btn-sm btn-danger'>delete</button>
                </form>";
        }
        if ($postCount) {
            $aResult['name'] = "<a href='" . URLBACKEND . "blog-list/?category={$aResult['id']}'><u>{$aResult['name']}</u></a>";
        }
        $tr .= "
            <tr>
                <td>" . ++$kanter . "</td>
                <td>{$aResult['name']}</td>
                <td>{$aResult['description']}</td>
                <td>$postCount</td>
                <td>
                    $deleteBtn                    
                    <a class='btn btn-sm btn-primary' href='" . URLBACKEND . "$page?id={$aResult['id']}'>edit</a>
                </td>
            </tr>    
        ";
    }
}
