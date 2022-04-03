<?php
require_once "config.php";

try {
    $PDO = new PDO('mysql:dbname=' . DB . ';host=' . SERVER, USERNAME, PASSWORD);
} catch (PDOException $e) {
    new ErrorLog($e->getMessage(), __FILE__, __LINE__);
    if (!DEVELOPMENT) {
        $_SESSION['Invalid Error'] = false;
        header("Location: " . URLBACKEND . ERRORPAGE);
    }
    exit;
}
