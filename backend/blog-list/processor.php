<?php
require_once dirname(__FILE__, 2) . "/connection.php";
$Authentication = new Authentication($PDO);
$Authentication->keyToPage();
$User = new Users($PDO);
$MyUsers = new MyUsers($PDO);
$staffId = $_SESSION[Authentication::SESSION_NAME]['id'];
$staffInfo = $User->getInfo($staffId);
$Authentication->pageAccessor([MyUsers::STAFF['name']], $staffInfo['user_type']['type_name']);

if (
    isset($_POST[Authentication::SESSION_CSRF_TOKEN])
    && Authentication::checkCSRFToken($_POST[Authentication::SESSION_CSRF_TOKEN])
) {
    if ($_POST['action'] == 'edit') {
        //get inputted data
        $errors = [];
        $categoryId = filter_var(trim($_POST['categoryId']), FILTER_VALIDATE_INT);
        $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
        $description = filter_var(trim($_POST['description']), FILTER_SANITIZE_STRING);

        //check for validation
        if (!$name) $errors[] = "invalid name";
        if ($categoryId) {
            $Db = new Database(__FILE__, $PDO, 'blog_category');
            if (!$Db->isDataInColumn(__LINE__, $categoryId, 'id')) $errors[] = "invalid category";
        } else {
            $errors[] = "invalid category";
        }

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

        $Db = new Database(__FILE__, $PDO, 'blog_category');
        $columns = [
            'name' => ['colValue' => $name, 'isFunction' => false, 'isBindAble' => true],
            'description' => ['colValue' => $description, 'isFunction' => false, 'isBindAble' => true],
        ];
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $categoryId]];
        $Db->update(__LINE__, $columns, $where);

        $responseTitle = 'Operation Successful';
        $responseMessage = 'A blog category has been successfully edited';
        $responseColor = Tag::RESPONSE_POSITIVE;
        Tag::setResponse($responseTitle, [$responseMessage], $responseColor);
    }

    if ($_POST['action'] == 'delete') {
        //get inputed data
        $errors = [];
        $postId = filter_var(trim($_POST['postId']), FILTER_VALIDATE_INT);

        //check for validation
        if ($postId) {
            $Db = new Database(__FILE__, $PDO, 'blog_post');
            if (!$Db->isDataInColumn(__LINE__, $postId, 'id')) $errors[] = "invalid post";
        } else {
            $errors[] = "invalid post";
        }

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
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $postId]];
        $image = $Db->select(__LINE__, ['image'], $where)[0]['image'];
        unlink(Functions::ASSET_IMG_PATHBACKEND . "blog/$image");
        $Db->delete(__LINE__, $where);

        $responseTitle = 'Operation Successful';
        $responseMessage = 'A blog post has been successfully deleted';
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
