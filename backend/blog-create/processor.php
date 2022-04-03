<?php
require_once dirname(__FILE__, 2) . "/connection.php";
$Authentication = new Authentication($PDO);
$Authentication->keyToPage();
$User = new Users($PDO);
$MyUsers = new MyUsers($PDO);
$staffId = $_SESSION[Authentication::SESSION_NAME]['id'];
$staffInfo = $User->getInfo($staffId);
$Authentication->pageAccessor([MyUsers::STAFF['name']], $staffInfo['user_type']['type_name']);

$permittedFiles = ['png', 'jpg', 'jpeg'];
$maxSize = 1200000;

if (
    isset($_POST[Authentication::SESSION_CSRF_TOKEN])
    && Authentication::checkCSRFToken($_POST[Authentication::SESSION_CSRF_TOKEN])
) {
    if ($_POST['action'] == 'create') {
        //get inputted data
        $errors = [];
        $title = filter_var(trim($_POST['title']), FILTER_SANITIZE_STRING);
        $imgFile = $_FILES['image'];
        $category = filter_var(trim($_POST['category']), FILTER_VALIDATE_INT);
        $display = filter_var(trim($_POST['display']), FILTER_SANITIZE_STRING);
        $content = filter_var(trim($_POST['content']), FILTER_SANITIZE_STRING);

        //check for validation
        if (!$title) $errors[] = "invalid title";
        $fileError = Functions::PHPUploadError($imgFile);
        if ($fileError) {
            $errors[] = $fileError;
        } else {
            $fileError = Functions::developerUploadError($imgFile, $permittedFiles, $maxSize);
            if ($fileError) $errors[] = $fileError;
        }
        if ($category) {
            $Db = new Database(__FILE__, $PDO, 'blog_category');
            if (!$Db->isDataInColumn(__LINE__, $category, 'id')) $errors[] = "invalid category";
        } else {
            $errors[] = "invalid category";
        }
        if ($display) {
            if (!in_array($display, ['no', 'yes'])) $errors[] = "invalid display value";
        } else {
            $errors[] = "invalid display value";
        }
        if (!$content) $errors[] = "invalid content";

        //redirect on error
        $responseURLBACKEND = "Location: .";
        if ($errors) {
            Tag::setResponse(
                'Invalid Data Input',
                $errors,
                Tag::RESPONSE_NEGATIVE
            );
            header($responseURLBACKEND);
            exit;
        }

        $Db = new Database(__FILE__, $PDO, 'blog_post');
        $filename = $staffId . time() . pathinfo($imgFile['name'], PATHINFO_EXTENSION);
        copy($imgFile['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "blog/$filename");
        $columns = [
            'title' => ['colValue' => $title, 'isFunction' => false, 'isBindAble' => true],
            'content' => ['colValue' => $content, 'isFunction' => false, 'isBindAble' => true],
            'image' => ['colValue' => $filename, 'isFunction' => false, 'isBindAble' => true],
            'blog_category' => ['colValue' => $category, 'isFunction' => false, 'isBindAble' => true],
            'poster_id' => ['colValue' => $staffId, 'isFunction' => false, 'isBindAble' => true],
            'display' => ['colValue' => $display, 'isFunction' => false, 'isBindAble' => true],
        ];
        $Db->insert(__LINE__, $columns);

        $responseTitle = 'Operation Successful';
        $responseMessage = 'A new blog post has been successfully created';
        $responseColor = Tag::RESPONSE_POSITIVE;
        Tag::setResponse($responseTitle, [$responseMessage], $responseColor);
    }

    if ($_POST['action'] == 'edit') {
        //get inputted data
        $errors = [];
        $postId = filter_var(trim($_POST['postId']), FILTER_VALIDATE_INT);
        $title = filter_var(trim($_POST['title']), FILTER_SANITIZE_STRING);
        $imgFile = $_FILES['image'];
        $category = filter_var(trim($_POST['category']), FILTER_VALIDATE_INT);
        $display = filter_var(trim($_POST['display']), FILTER_SANITIZE_STRING);
        $content = filter_var(trim($_POST['content']), FILTER_SANITIZE_STRING);

        //check for validation
        if ($postId) {
            $Db = new Database(__FILE__, $PDO, 'blog_post');
            if (!$Db->isDataInColumn(__LINE__, $postId, 'id')) $errors[] = "invalid post";
        } else {
            $errors[] = "invalid category";
        }
        if (!$title) $errors[] = "invalid title";
        if (!Functions::PHPUploadError($imgFile)) {
            $fileError = Functions::developerUploadError($imgFile, $permittedFiles, $maxSize);
            if ($fileError) $errors[] = $fileError;
        }
        if ($category) {
            $Db = new Database(__FILE__, $PDO, 'blog_category');
            if (!$Db->isDataInColumn(__LINE__, $category, 'id')) $errors[] = "invalid category";
        } else {
            $errors[] = "invalid category";
        }
        if ($display) {
            if (!in_array($display, ['no', 'yes'])) $errors[] = "invalid display value";
        } else {
            $errors[] = "invalid display value";
        }
        if (!$content) $errors[] = "invalid content";

        //redirect on error
        $responseURLBACKEND = "Location: .";
        if ($errors) {
            Tag::setResponse(
                'Invalid Data Input',
                $errors,
                Tag::RESPONSE_NEGATIVE
            );
            header($responseURLBACKEND);
            exit;
        }

        $Db = new Database(__FILE__, $PDO, 'blog_post');
        $columns = [
            'title' => ['colValue' => $title, 'isFunction' => false, 'isBindAble' => true],
            'content' => ['colValue' => $content, 'isFunction' => false, 'isBindAble' => true],
            'blog_category' => ['colValue' => $category, 'isFunction' => false, 'isBindAble' => true],
            'poster_id' => ['colValue' => $staffId, 'isFunction' => false, 'isBindAble' => true],
            'display' => ['colValue' => $display, 'isFunction' => false, 'isBindAble' => true],
        ];
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $postId]];
        if ($imgFile['name']) {
            $filename = $Db->select(__LINE__, ['image'], $where)[0]['image'];
            copy($imgFile['tmp_name'], Functions::ASSET_IMG_PATHBACKEND . "blog/$filename");
            $columns['image'] = ['colValue' => $filename, 'isFunction' => false, 'isBindAble' => true];
        }
        $Db->update(__LINE__, $columns, $where);

        $responseTitle = 'Operation Successful';
        $responseMessage = 'A blog post has been successfully changed';
        $responseColor = Tag::RESPONSE_POSITIVE;
        Tag::setResponse($responseTitle, [$responseMessage], $responseColor);
    }

    //redirect on completion
    header($responseURLBACKEND);
    exit();
} else {
    new ErrorLog('Suspected CSRF Attack', __FILE__, __LINE__);
    Tag::setResponse(
        'Expired Session',
        ['Your session has expired, please repeat the process again'],
        Tag::RESPONSE_NEGATIVE
    );
    header("Location: .");
    exit();
}
