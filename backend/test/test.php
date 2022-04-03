<?php
require_once dirname(__FILE__, 2) . "/connection.php";
$Db = new Database(__FILE__, $PDO, "tasks");
$User = new Users($PDO);
$userTypeId = $User->getTypeInfo()[MyUsers::INDIVIDUAL['name']]['id'];
var_dump($userTypeId);
exit;

// var_dump(crypt('password', SALT));
// exit();
/*$Db->setTable("migrations");
var_dump($Db->getDataInColumn(__LINE__, "id"));
var_dump($Db->isDataInColumn(__LINE__, 2, "id"));
var_dump($Db->select(__LINE__, ["id", "batch"], [["column" => "id", "comparsion" => ">", "value" => 3]])); */

$Users = new Users($PDO);
//$Users->create(['email' => "email6@company.com", 'password' => 'password'], true, true);
/* var_dump($Users->getIds([], null, null, false));
var_dump($Users->isIdValid(0));
var_dump($Users->isUserAType(6, Users::USER_TYPE[1])); */
/* for ($i = 0; $i < 5; $i++) {
    var_dump($Users->create(['email' => "email$i@company.com", 'password' => 'password'], true, true));
} */
//var_dump($Users->update(['email' => 'email10@company.com'], 6));
//var_dump($Users->getInfo(1));
//var_dump($Users->delete(8));
//var_dump($Users->getIdFrmLoginField("080", 'phone'));
//var_dump($Users->getLoginFieldFrmId(10, 'phone'));
//var_dump($Users->changeResetToken(9));
//var_dump($Users->isResetTokenValid(9, 'JELWtMQXoPgd6f9Z', (60 * 60 * 1)));
//var_dump($Users->changeUserStatus(9, 'inactive', true));
//var_dump($Users->sendResetToken(9, true));
//var_dump($Users->resetPassword(9, 'password', 'TJfBzMhH9054XPF8', ['EMAIL']));

$Authentication = new Authentication($PDO);
/* foreach ($Authentication->generateSQLForUsersTbl() as $table => $sql) {
    echo $sql;
    echo "<br/><br/>";
}
 */

$Notification = new Notification();
/* $emails = [
    'to' => ['alabi10@yahoo', 'alabi11@yahoo.com'], 'from' => ['alabi13@yahoo'],
    'cc' => ['alabi14@yahoo', 'alabi15@yahoo.com'], 'bcc' => [], 'reply-to' => []
];
$Notification->sendMail($emails, "Subject", "Body"); */

$Transactions = new Transactions($PDO);
/* $Transactions->generateTblSQL('customer_transaction');
foreach ($Authentication->generateUsersTblSQL() as $anSql) {
    $Db->queryStatment(__LINE__, $anSql);
} */

/* $Db->queryStatment(
    __LINE__,
    $Transactions->generateTblSQL(Transactions::TABLES[Transactions::TRNX_OWNER[0]])
); */

/* $Db->queryStatment(
    __LINE__,
    $Transactions->generateTblSQL(Transactions::TABLES[Transactions::TRNX_OWNER[1]])
); */

//var_dump((new DateTime())->format("Y-m-d h:i:s"));
/* $customers = [];
for ($i = 0; $i < 5; $i++) {
    $customers[] = rand(1, 5);
}
var_dump($customers);
foreach ($customers as $aCustomer) {
    $action = rand(0, 1) == 0 ? "debit" : "credit";
    echo $Transactions->post($aCustomer, "just a credit", rand(1000, 90000), $action) . "</br>";
} */
//$Transactions->post(6, "just a debit", rand(1000, 90000), 'debit') . "</br>";
//echo $Transactions->post(6, "just a credit", 15001.90, "credit", new DateTime("2021-09-01"), "T6" . time() . ".png") . "</br>";
//echo $Transactions->getBalance(6) . "<br/>";
//var_dump($Transactions->getStatement(1, 1000, new DateTime("2021-10-18"), new DateTime("2021-10-20")));
$Ledger = new Ledgers($PDO);
foreach ($Ledger->generateTblSQL() as $anSQL) {
    //$Db->queryStatment(__LINE__, $anSQL);
}

//$Ledger->getLedgerTypeInfo(1);
//$Ledger->getLedgerInfo(1);
//$Ledger->generateAccountNo(1);
//$Ledger->createLedger("Exam Fee", "Fee paid when exam is been registered for", 8);
//$Ledger->createLedger("Course Change Fee", "Fee paid when canidate are changing course registered for", 8);
//var_dump($Ledger->getLedgerInfo(1));

//var_dump($Ledger->createLedgerType("Tax Liability", "Tax yet to be paid to government", "liability"));
//$Ledger->updateLedger(6, null, null, 12);
//$Ledger->updateLedger(7, null, null, 12);
//$Ledger->updateLedgerType(8, "fixed asset", null, "income");
//$Ledger->deleteLedger(3);
//$Ledger->deleteLedgerType(19);
//var_dump($Ledger->getLedgerIds("income"));

$User = new MyUsers($PDO);
//var_dump($User->getProfileInfo(1));
$Authority = new Authority($PDO);
foreach ($Authority->generateTblsSQL() as $anSQL) {
    //$Db->queryStatment(__LINE__, $anSQL);
}
//var_dump($Authority->createTask("approve users"));
//var_dump($Authority->isTaskIdValid(1));
//var_dump($Authority->findTaskIds());
//var_dump($Authority->getTaskInfo(4));
//$Authority->changeTaskInfo(2, null, 'this task is about editing users');
//var_dump($Authority->getTaskInfo(4));
//var_dump($Authority->deleteTask(4));
//var_dump($Authority->createRole("approver"));
//var_dump($Authority->isRoleIdValid(10));
//var_dump($Authority->findRoleIds());
//var_dump($Authority->getRoleInfo(3));
//var_dump($Authority->changeRoleInfo(3, 'approver I', 'first level approver officer'));
//var_dump($Authority->getRoleInfo(3));
//var_dump($Authority->deleteRole(3));
//var_dump($Authority->findRoleIds());
//var_dump($Authority->addTaskToRole(3, 4));
//var_dump($Authority->removeTaskFromRole(1, 1));
//var_dump($Authority->getTasksInRole(4));
//var_dump($Authority->getTaskRole(2));
//var_dump($Authority->createGroup("delete"));
//var_dump($Authority->isGroupIdValid(1));
//var_dump($Authority->findGroupIds());
//var_dump($Authority->getGroupInfo(2));
//var_dump($Authority->changeGroupInfo(2, null, "supervising officer"));
//var_dump($Authority->getGroupInfo(2));
//var_dump($Authority->deleteGroup(3));
//var_dump($Authority->addProfileToGroup(2, 2));
//var_dump($Authority->removeProfileFromGroup(1, 2));
//$newUserData = ['email' => 'email2@domain.com', 'password' => 'yourpassword', 'user_type' => 'staff'];
//$User->create($newUserData, true, true);
//var_dump($Authority->getProfilesInGroup(2));
//var_dump($Authority->addDoerToRole(1, Authority::ROLE_DOERS[0], 4));
//var_dump($Authority->removeDoerFromRole(2, Authority::ROLE_DOERS[2], 2));
//var_dump($Authority->getDoersInRole(2));
//var_dump($Authority->getDoerRoles(1, Authority::ROLE_DOERS[1]));
//var_dump($Authority->getProfileTask(2));
//var_dump($Authority->getProfileRole(2));
//var_dump($Authority->getProfileGroup(2));