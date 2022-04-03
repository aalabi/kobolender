<?php
require_once dirname(__FILE__, 2) . "/connection.php";

$newUsers[] = ['email' => 'individual1@domain.com', 'password' => 'password', 'user_type' => 'individual'];
$newUsers[] = ['email' => 'individual2@domain.com', 'password' => 'password', 'user_type' => 'individual'];
$newUsers[] = ['email' => 'individual3@domain.com', 'password' => 'password', 'user_type' => 'individual'];
$newUsers[] = ['email' => 'msme1@domain.com', 'password' => 'password', 'user_type' => 'msme'];
$newUsers[] = ['email' => 'msme2@domain.com', 'password' => 'password', 'user_type' => 'msme'];

$User = new Users($PDO);
//Create a users
if ($newUsers) {
    foreach ($newUsers as $newUserData) {
        $User->create($newUserData, true, true);
    }
}
