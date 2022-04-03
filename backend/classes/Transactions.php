<?php

/**
 * Transactions
 *
 * This class is used for handling transaction
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   2021 Alabian Solutions Limited
 * @link        alabiansolutions.com
 */

class TransactionsException extends Exception
{
}

class Transactions
{
    /** @var  Database an instance of Database */
    protected $_db;

    /** @var  PDO an instance of PDO type */
    private $_pdo;

    /** @var  string the transaction owner  */
    private $_trnxOwner;

    /** @var array the enum values in the transaction type field */
    public const TYPE = ["debit", "credit"];

    /** @var array the table name for various the transactions owner */
    public const TRNX_OWNER = ['loan'];

    /** @var array the table name for various the transactions user=>table */
    protected $_tables;

    /** @var string ticket file pathbackend */
    public const TICKETS_PATHBACKEND = Functions::ASSET_IMG_PATHBACKEND . "tickets/";

    /** @var string ticket file urlbackend */
    public const TICKETS_URLBACKEND = Functions::ASSET_IMG_URLBACKEND . "tickets/";


    /**
     * Setup up Transactions
     * @param PDO $pdo an instant of PDO
     * @param string $trnxOwner the entity that owners the transactions
     */
    public function __construct(PDO $pdo, string $trnxOwner = Transactions::TRNX_OWNER[0])
    {
        $this->setTables();
        if (!array_key_exists($trnxOwner, $this->_tables))
            throw new TransactionsException("invalid transaction owner");

        $this->_db = new Database(__FILE__, $pdo, $this->_tables[$trnxOwner]);
        $this->_pdo = $pdo;
        $this->_trnxOwner = $trnxOwner;
    }

    /**
     * set the array of table used for storing transactions record in the databases
     *
     * @return void
     */
    private function setTables()
    {
        foreach (Transactions::TRNX_OWNER as $aTable) {
            $tables[$aTable] = $aTable . '_transaction';
        }
        $this->_tables = $tables;
    }

    /**
     * get the tables used for storing transactions record in the database
     *
     * @return array
     */
    public function getTables(): array
    {
        return $this->_tables;
    }

    /**
     * a check if the owner id is valid
     *
     * @param integer $id the id of the owner
     * @return boolean
     */
    private function isOwnerValid(int $id): bool
    {
        $status = true;
        $oldTable = $this->_db->getTable();
        $this->_db->setTable($this->_trnxOwner);

        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        $status = $this->_db->select(__LINE__, ['id'], $where) ? true : false;

        $this->_db->setTable($oldTable);
        return $status;
    }

    /**
     * for posting transaction
     *
     * @param integer $ownerId the owner of the posting
     * @param integer $posterId the id of the person doing the posting
     * @param string $description narration for the transaction
     * @param float $amount the amount to be posted
     * @param string  $type if transaction is debit or credit
     * @param DateTime $trnxDate the exact time the transaction took place
     * @param string $ticket filename of the ticket for the transaction
     * @return int id of the posted transaction
     */
    public function post(int $ownerId, int $posterId, string $description, float $amount, $type, DateTime $trnxDate = null, string $ticket = null): int
    {
        if (!in_array(strtolower($type), Transactions::TYPE))
            throw new TransactionsException("invalid transaction type");

        if (!$this->isOwnerValid($ownerId))
            throw new TransactionsException("invalid owner id");

        $oldTable = $this->_db->getTable();
        $this->_db->setTable($this->_tables[$this->_trnxOwner]);

        $tblIdName = "{$this->_trnxOwner}_id";
        if (!$trnxDate) {
            $time = "NOW()";
            $isFunction = true;
            $isBindAble = false;
        } else {
            $time = $trnxDate->format("Y-m-d h:i:s");
            $isFunction = false;
            $isBindAble = true;
        }

        $cols = ['balance'];
        $where = [['column' => $tblIdName, 'comparsion' => '=', 'bindAbleValue' => $ownerId]];
        $orders = ['id' => "DESC"];
        $limit = [1, 0];
        $balanceArray = $this->_db->select(__LINE__, $cols, $where, $orders, $limit);
        $balance = ($type == "debit") ? -$amount : $amount;
        if ($balanceArray) {
            $balance = ($type == "debit") ? $balanceArray[0]['balance'] - $amount : $balanceArray[0]['balance'] + $amount;
        }

        $data = [
            $tblIdName => ['colValue' => $ownerId, 'isFunction' => false, 'isBindAble' => true],
            'poster_id' => ['colValue' => $posterId, 'isFunction' => false, 'isBindAble' => true],
            'description' => ['colValue' => $description, 'isFunction' => false, 'isBindAble' => true],
            'amount' => ['colValue' => $amount, 'isFunction' => false, 'isBindAble' => true],
            'balance' => ['colValue' => $balance, 'isFunction' => false, 'isBindAble' => true],
            'type' => ['colValue' => $type, 'isFunction' => false, 'isBindAble' => true],
            'transaction_date' => ['colValue' => $time, 'isFunction' => $isFunction, 'isBindAble' => $isBindAble],
        ];
        if ($ticket) {
            $data['ticket'] = ['colValue' => $ticket, 'isFunction' => false, 'isBindAble' => true];
        }
        $newTrnx = $this->_db->insert(__LINE__, $data);
        $this->_db->setTable($oldTable);

        return $newTrnx['lastInsertId'];
    }

    /**
     * for posting transaction
     *
     * @param integer $ownerId the id of owner
     * @return int balance of the owner
     */
    public function getBalance(int $ownerId): float
    {
        if (!$this->isOwnerValid($ownerId))
            throw new TransactionsException("invalid owner id");

        $oldTable = $this->_db->getTable();
        $this->_db->setTable($this->_tables[$this->_trnxOwner]);

        $tblIdName = "{$this->_trnxOwner}_id";
        $cols = ['balance'];
        $where = [['column' => $tblIdName, 'comparsion' => '=', 'bindAbleValue' => $ownerId]];
        $orders = ['id' => "DESC"];
        $limit = [1, 0];
        $balance = $this->_db->select(__LINE__, $cols, $where, $orders, $limit);
        $balance = ($balance) ? $balance[0]['balance'] : 0;

        $this->_db->setTable($oldTable);
        return $balance;
    }

    /**
     * get latest  transaction of this owner
     *
     * @param integer $ownerId the id of the owner
     * @param integer $max the maxmium no of transaction to get
     * @param DateTime|null $startDate start date of the transaction
     * @param DateTime|null $endDate end date of the transaction
     * @return array
     */
    public function getStatement(int $ownerId, int $max = 1000, DateTime $startDate = null, DateTime $endDate = null): array
    {
        if (!$this->isOwnerValid($ownerId))
            throw new TransactionsException("invalid owner id");
        if (($startDate && $endDate) && ($startDate > $endDate))
            throw new TransactionsException("start date is greater than end date");

        $oldTable = $this->_db->getTable();
        $this->_db->setTable($this->_tables[$this->_trnxOwner]);

        $ownerId = $this->_pdo->quote($ownerId);
        $between = " WHERE {$this->_trnxOwner}_id = $ownerId ";
        if ($startDate && !$endDate)
            $between .= " AND created_at >= '" . $startDate->format("Y-m-d h:i:s") . "'";
        if ($endDate && !$startDate)
            $between .= " AND created_at <= '" . $endDate->format("Y-m-d h:i:s") . "'";
        if ($endDate && $startDate) {
            $between .= " AND created_at >= '" . $startDate->format("Y-m-d h:i:s") . "' 
            AND created_at <= '" . $endDate->format("Y-m-d h:i:s") . "'";
        }

        $table = $this->_tables[$this->_trnxOwner];
        $sql = "SELECT * FROM $table $between ORDER BY id DESC LIMIT $max";
        $statement = $this->_db->queryStatment(__LINE__, $sql)['data'];

        $this->_db->setTable($oldTable);
        return $statement;
    }

    /**
     * for generation of sql for creating transaction tables
     * @param string $table transaction table to be created
     * @return string
     */
    public function generateTblSQL(string $table): string
    {
        if (!in_array($table, $this->_tables))
            throw new TransactionsException("invalid transaction table name");

        $sql = "SELECT table_name FROM information_schema.tables WHERE table_type = 'base table' AND table_schema='" . DB . "'";
        $tableCollection = [];
        if ($collection = $this->_db->queryStatment(__LINE__, $sql)['data']) {
            foreach ($collection as $aTable) $tableCollection[] = $aTable['table_name'];
        }

        foreach ($this->_tables as $aTable) {
            $foreignTable = explode("_", $table)[0];
            if (!in_array($foreignTable, $tableCollection)) {
                $sql = "
                    CREATE TABLE IF NOT EXISTS $foreignTable (
                        id BIGINT UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT ,
                        name VARCHAR(255) NOT NULL ,
                        description VARCHAR(255) NULL ,
                        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
                        updated_at TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,                 
                        PRIMARY KEY (id)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";
                $this->_db->queryStatment(__LINE__, $sql);
                //throw new TransactionsException("'$foreignTable' table is missing");
            }

            $foreignField = "{$foreignTable}_id";
            $foreignKey = " $foreignField BIGINT UNSIGNED NOT NULL ";
            $key = " KEY {$foreignTable}_{$foreignField}_foreign ($foreignField) ";
            $contraint = "ADD CONSTRAINT {$foreignTable}_{$foreignField}_foreign FOREIGN KEY ($foreignField) REFERENCES $foreignTable (id) ON DELETE CASCADE ON UPDATE CASCADE";
        }

        $typeEnum = "";
        foreach (Transactions::TYPE as $aType) {
            $typeEnum .= "'$aType', ";
        }
        $typeEnum = rtrim($typeEnum, ", ");

        $sql = "
            START TRANSACTION;
            CREATE TABLE IF NOT EXISTS $table (
                id BIGINT UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT , 
                $foreignKey,
                poster_id BIGINT UNSIGNED NOT NULL , 
                description VARCHAR(255) NOT NULL , 
                amount DECIMAL(16,2) NOT NULL , 
                balance DECIMAL(16,2) NOT NULL , 
                type ENUM($typeEnum) NOT NULL ,
                ticket VARCHAR(255) NULL , 
                transaction_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
                updated_at TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,                 
                PRIMARY KEY (id), 
                $key
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            ALTER TABLE $table
            $contraint;
            COMMIT;
        ";
        return $sql;
    }
}
