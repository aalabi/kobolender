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

$title = SITENAME . " Blog Post Creation";
$pageName = "Blog Post Creation";
$sideBar = $Tag->createSideMenu(['blog', 'create'], $staffId);
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
$postTitle = $content = $image = $yesCheck = $noChecked = "";
$postIdInput = $catgoryOption = "";
$imageRequired = "required";
$imageRequiredAsterisk = "*";
$Db = new Database(__FILE__, $PDO, 'blog_category');
if ($categoryCollection = $Db->select(__LINE__, [], [])) {
    foreach ($categoryCollection as $aCategory)
        $catgoryOption .= "<option value='{$aCategory['id']}'>{$aCategory['name']}</option>";
}
if (isset($_GET['id'])) {
    $postId = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    $Db = new Database(__FILE__, $PDO, 'blog_post');
    if ($postId && $Db->isDataInColumn(__LINE__, $postId, 'id')) {
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $postId]];
        $result = $Db->select(__LINE__, [], $where)[0];
        $mode = 'edit';
        $postTitle = $result['title'];
        $content = $result['content'];
        $image = "<a target='_blank' href='" . Functions::ASSET_IMG_URLBACKEND . "blog/{$result['image']}'><u>view</u></a>";
        $postIdInput = "<input type='hidden' name='postId' value='{$result['id']}'>";
        $imageRequired = $imageRequiredAsterisk = "";
        $yesCheck = ($result['display'] == 'yes') ? "checked" : "";
        $noChecked = ($result['display'] == 'no') ? "checked" : "";

        $Db = new Database(__FILE__, $PDO, 'blog_category');
        if ($categoryCollection = $Db->select(__LINE__, [], [])) {
            foreach ($categoryCollection as $aCategory) {
                $selected = ($result['blog_category'] == $aCategory['id']) ? "selected" : "";
                $catgoryOption .= "<option $selected value='{$aCategory['id']}'>{$aCategory['name']}</option>";
            }
        }
    }
}

//create edit caption
$createEditCaption = ($mode == 'create') ? "Create Blog Category" : "Edit Blog Category";
$btnCaption = ($mode == 'create') ? "Create" : "Edit";
if ($mode == 'edit') $title = SITENAME . " Blog Post Edit";
if ($mode == 'edit') $pageName = "Blog Post Edit";
