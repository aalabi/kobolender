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
    //get inputted post id and validate it
    $errors = [];
    $postId = filter_var(trim($_POST['postId']), FILTER_VALIDATE_INT);
    if ($postId) {
        $Db = new Database(__FILE__, $PDO, 'blog_post');
        if (!$Db->isDataInColumn(__LINE__, $postId, 'id')) $errors[] = "invalid post";
    } else {
        $errors[] = "invalid post";
    }

    if ($_POST['action'] == 'hideShow') {
        //redirect on error
        $responseURLBACKEND = "Location: " . URLBACKEND . Functions::pwdName(__FILE__) . "/?id=$postId";
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
        $display = $Db->select(__LINE__, ['display'], $where)[0]['display'];
        $display = ($display == 'yes') ? "no" : "yes";
        $column = ['display' => ['colValue' => $display, 'isFunction' => false, 'isBindAble' => true]];
        $Db->update(__LINE__, $column, $where);

        $responseTitle = 'Operation Successful';
        $responseStatus = ($display == 'yes') ? "SHOW" : "HIDE";
        $responseMessage = "The post display status has been successfully changed to $responseStatus";
        $responseColor = Tag::RESPONSE_POSITIVE;
        Tag::setResponse($responseTitle, [$responseMessage], $responseColor);
    }

    if ($_POST['action'] == 'edit') {

        //redirect on error
        $responseURLBACKEND = "Location: " . URLBACKEND . Functions::pwdName(__FILE__) . "/?id=$postId";
        if ($errors) {
            Tag::setResponse(
                'Invalid Data Input',
                $errors,
                Tag::RESPONSE_NEGATIVE
            );
            header($responseURLBACKEND);
            exit;
        }

        header("Location: " . URLBACKEND . "blog-create/?id=$postId");
        exit;
    }

    if ($_POST['action'] == 'delete') {
        //redirect on error
        $responseURLBACKEND = "Location: " . URLBACKEND . "blog-list";
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
        $Db->delete(__LINE__, $where);
        unlink(Functions::ASSET_IMG_PATHBACKEND . "blog/$image");

        $responseTitle = 'Operation Successful';
        $responseMessage = "A post has been successfully deleted";
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
