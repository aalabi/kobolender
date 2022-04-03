<?php
require_once "connection.php";

$Authenticator = new Authentication($PDO);
$Authenticator->logoutUser();
