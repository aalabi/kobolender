<?php
require_once dirname(__FILE__, 2) . "/connection.php";
$_POST = json_decode(file_get_contents('php://input'), true);
$Authentication = new Authentication($PDO);
$Authentication->keyToPage();
$User = new Users($PDO);
$MyUsers = new MyUsers($PDO);
$staffId = $_SESSION[Authentication::SESSION_NAME]['id'];
$staffInfo = $User->getInfo($staffId);
$Authentication->pageAccessor([MyUsers::STAFF['name']], $staffInfo['user_type']['type_name']);
$responseData = ['status' => 'fail', 'message' => 'unknown error', 'data' => []];

if (isset($_POST['token']) && $_POST['token'] == Authentication::getCSRFToken()) {
    //get inputted data
    $errors = [];
    $commentId = filter_var(trim($_POST['id']), FILTER_VALIDATE_INT);
    $display = filter_var(trim($_POST['display']), FILTER_SANITIZE_STRING);

    //check for validation
    if ($commentId) {
        $Db = new Database(__FILE__, $PDO, 'blog_comment');
        if (!$Db->isDataInColumn(__LINE__, $commentId, 'id')) $errors[] = "invalid comment";
    } else {
        $errors[] = "invalid comment";
    }
    if ($display) {
        if (!in_array($display, ['no', 'yes'])) $errors[] = "invalid display status";
    } else {
        $errors[] = "invalid display status";
    }

    //check for error
    if ($errors) {
        $responseData['message'] = rtrim(implode(", ", $errors), ", ");
    } else {
        $Db = new Database(__FILE__, $PDO, 'blog_comment');
        $columns = ['display' => ['colValue' => $display, 'isFunction' => false, 'isBindAble' => true]];
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $commentId]];
        $Db->update(__LINE__, $columns, $where);
        $responseData = ['status' => 'fail', 'message' => 'unknown error', 'data' => []];
        $responseData['status'] = 'success';
        $responseData['message'] = 'executed';
        $responseData['data'] = ['id' => $commentId, 'display' => 'yes'];
    }
} else {
    $responseData['message'] = "invalid token " . Authentication::getCSRFToken();
}

echo json_encode(json_encode($responseData));
