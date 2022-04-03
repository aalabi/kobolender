<?php
require_once "backend/connection.php";

if (
    isset($_POST[Authentication::SESSION_CSRF_TOKEN])
    && Authentication::checkCSRFToken($_POST[Authentication::SESSION_CSRF_TOKEN])
) {

    //get inputted data
    $errors = [];
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $comment = filter_var(trim($_POST['comment']), FILTER_SANITIZE_STRING);
    $postId = filter_var(trim($_POST['postId']), FILTER_VALIDATE_INT);

    //check for validation
    if ($postId) {
        $Db = new Database(__FILE__, $PDO, 'blog_post');
        if (!$Db->isDataInColumn(__LINE__, $postId, 'id'))  $errors[] = "invalid post id";
    } else {
        $errors[] = "invalid post id";
    }
    if (!$email) $errors[] = "invalid email";
    if (!$comment) $errors[] = "invalid comment";

    //redirect on error
    $responseURL = "Location: " . URL . "a-blog.php?id=2#reply";
    if ($errors) {
        Tag::setResponse(
            'Invalid Data Input',
            $errors,
            Tag::RESPONSE_NEGATIVE
        );
        header($responseURL);
        exit;
    }

    //add comment to db
    $Db = new Database(__FILE__, $PDO, 'blog_comment');
    $columns = [
        'email' => ['colValue' => $email, 'isFunction' => false, 'isBindAble' => true],
        'comment' => ['colValue' => $comment, 'isFunction' => false, 'isBindAble' => true],
        'blog_post' => ['colValue' => $postId, 'isFunction' => false, 'isBindAble' => true],
    ];
    $Db->insert(__LINE__, $columns);

    $responseTitle = 'Operation Successful';
    $responseMessage = "Your comment has been successfully entered";
    $responseColor = Tag::RESPONSE_POSITIVE;
    Tag::setResponse($responseTitle, [$responseMessage], $responseColor);

    //redirect on completion
    header($responseURL);
    exit();
} else {
    new ErrorLog('Suspected CSRF Attack', __FILE__, __LINE__);
    Tag::setResponse(
        'Expired Session',
        ['Your session has expired, please repeat the process again'],
        Tag::RESPONSE_NEGATIVE
    );
    header($responseURL);
    exit();
}
