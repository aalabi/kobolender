<?php
require_once dirname(__FILE__, 2) . "/connection.php";

/*
    set to true if you want transactions tables to be create
*/
$createTransaction = false;

/*
    set to true if you want ledger tables to be create
*/
$createLedger = false;

/*
    set to true if you want role management tables to be create
*/
$createRoles = false;

/*
    setup the user type
    $userTypeInfo = ["staff" => ["staff", "staff"], "customer" => ["customer", "customer"]]
*/
$userTypeInfo = ["staff" => ["staff", "staff"], "individual" => ["individual", "individual"], "msme" => ["msme", "msme"], "guarantor" => ["guarantor", "guarantor"]];

/*  
    create a user
    $newUserData = ['email'=>'email@domain.com', 'password'=>'yourpassword', 'user_type'=>1]
*/
$newUserData = [];
$newUserData = ['email' => 'email@domain.com', 'password' => 'yourpassword', 'user_type' => 'staff'];



//stop editing and start coding
foreach ($userTypeInfo as $externalName => $typeInfo) {
    $userType[$externalName] = $typeInfo[0];
    $userTypeTable[$typeInfo[0]] = $typeInfo[1];
}

if (!$userTypeInfo) {
    $errorMsg = "user type info is empty";
    throw new Exception($errorMsg, 1);
}

$Db = new Database(__FILE__, $PDO, Authentication::TABLE);
$Authentication = new Authentication($PDO);

$User = new Users($PDO);
$Db->queryStatment(__LINE__, $Authentication->generateUserTypeSQL($userTypeInfo));
$User->setType();
$User->setUserTypeTable();

//Users table
foreach ($Authentication->generateUsersTblSQL() as $table => $sql) {
    $Db->queryStatment(__LINE__, $sql);
}

//Ledgers table
if ($createLedger) {
    $Ledger = new Ledgers($PDO);
    foreach ($Ledger->generateTblSQL() as $anSQL) {
        $Db->queryStatment(__LINE__, $anSQL);
    }
}

//Transaction table
if ($createTransaction) {
    $Transactions = new Transactions($PDO);
    for ($i = 0; $i < count(Transactions::TRNX_OWNER); $i++) {
        $Db->queryStatment(
            __LINE__,
            $Transactions->generateTblSQL($Transactions->getTables()[Transactions::TRNX_OWNER[$i]])
        );
    }
}

//Role tables
if ($createRoles) {
    $Authority = new Authority($PDO);
    foreach ($Authority->generateTblsSQL() as $anSQL) {
        $Db->queryStatment(__LINE__, $anSQL);
    }
}

//Create a user
if ($newUserData) {
    $User->create($newUserData, true, true);
}
