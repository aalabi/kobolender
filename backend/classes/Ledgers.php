<?php

/**
 * Ledgers
 *
 * This class is used for handling ledgers
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   2021 Alabian Solutions Limited
 * @link        alabiansolutions.com
 */

class LedgersException extends Exception
{
}

class Ledgers
{
    /** @var  Database an instance of Database */
    protected $_db;

    /** @var  PDO an instance of PDO type */
    private $_pdo;

    /** @var string the ledgers and sub ledgers */
    public const TYPE = [
        "asset" => ['asset', 'fixed asset', 'current asset', 'bank', 'cash equivalent', 'pre payment', 'inventory'],
        "expense" => ['expense', 'payable', 'depreciation', 'bad debt'],
        "income" => ['income', 'receivable'],
        "liability" => ['liability', 'current liability', 'loan payable', 'account payable'],
        "capital" => ['capital'],
    ];

    /** @var string the table name for ledgers */
    public const TABLE_LEDGERS = 'ledger';

    /** @var string the table name for ledgers type */
    public const TABLE_LEDGERS_TYPE = 'ledger_type';

    /**
     * Setup up Ledgers
     * @param PDO $pdo an instant of PDO
     */
    public function __construct(PDO $pdo)
    {
        $this->_db = new Database(__FILE__, $pdo, Ledgers::TABLE_LEDGERS);
        $this->_pdo = $pdo;
    }

    /**
     * a check if the ledger type id is valid
     *
     * @param integer $id the id of the ledger type
     * @return boolean
     */
    public function isIdValid(int $id): bool
    {
        $isValid = true;
        $oldTable = $this->_db->getTable();
        $this->_db->setTable(Ledgers::TABLE_LEDGERS);

        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        $isValid = $this->_db->select(__LINE__, ['id'], $where) ? true : false;

        $this->_db->setTable($oldTable);
        return $isValid;
    }

    /**
     * for generating new account for ledger
     *
     * @param integer $id the id of the ledger type
     * @return boolean
     */
    private function generateAccountNo(int $ledgerTypeId): string
    {
        $accountNo = '';
        $ledgerTypeInfo = $this->getLedgerTypeInfo($ledgerTypeId);

        $firstDigits = 0;
        foreach (Ledgers::TYPE as $ledgers => $ledgersTypes) {
            $secondDigits = 0;
            ++$firstDigits;
            if ($ledgers == $ledgerTypeInfo['type']) $accountNo .= $firstDigits;
            foreach ($ledgersTypes as $aType) {
                ++$secondDigits;
                if ($aType == $ledgerTypeInfo['name']) $accountNo .= str_pad($secondDigits, 2, "0", STR_PAD_LEFT);
            }
        }

        $oldTable = $this->_db->getTable();
        $this->_db->setTable(Ledgers::TABLE_LEDGERS);
        $accountNo .= str_pad(1, 3, "0", STR_PAD_LEFT);
        $where = [['column' => 'account_no', 'comparsion' => '=', 'bindAbleValue' => $accountNo]];
        while ($this->_db->select(__LINE__, ['id'], $where)) {
            $accountNo += 1;
            $where = [['column' => 'account_no', 'comparsion' => '=', 'bindAbleValue' => $accountNo]];
        }

        $this->_db->setTable($oldTable);
        return $accountNo;
    }

    /**
     * a check if the ledger type id is valid
     *
     * @param integer $id the id of the ledger type
     * @return boolean
     */
    private function isTypeIdValid(int $id): bool
    {
        $isValid = true;
        $oldTable = $this->_db->getTable();
        $this->_db->setTable(Ledgers::TABLE_LEDGERS_TYPE);

        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        $isValid = $this->_db->select(__LINE__, ['id'], $where) ? true : false;

        $this->_db->setTable($oldTable);
        return $isValid;
    }

    /**
     * for creating ledgers
     *
     * @param string $name name of the ledger
     * @param string $description a brief description of the ledger
     * @param integer $ledgerTypeId id of the ledger type this ledger belongs to
     * @param string $account account no of the ledger
     * @return integer
     */
    public function createLedger(string $name, string $description, int $ledgerTypeId, string $account = null): int
    {
        if (!$this->isTypeIdValid($ledgerTypeId))
            throw new LedgersException("invalid ledger type id");

        $oldTable = $this->_db->getTable();
        $this->_db->setTable(Ledgers::TABLE_LEDGERS);

        if ($account) {
            $where = [['column' => 'account_no', 'comparsion' => '=', 'bindAbleValue' => $account]];
            if ($this->_db->select(__LINE__, ['id'], $where))
                throw new LedgersException("account no $account associated with another ledger");
        } else {
            $account = $this->generateAccountNo($ledgerTypeId);
        }

        $data = [
            'ledger_type_id' => ['colValue' => $ledgerTypeId, 'isFunction' => false, 'isBindAble' => true],
            'description' => ['colValue' => $description, 'isFunction' => false, 'isBindAble' => true],
            'name' => ['colValue' => $name, 'isFunction' => false, 'isBindAble' => true],
            'account_no' => ['colValue' => $account, 'isFunction' => false, 'isBindAble' => true],
        ];
        $newLedger = $this->_db->insert(__LINE__, $data);
        $this->_db->setTable($oldTable);

        return $newLedger['lastInsertId'];
    }

    /**
     * change ledger information
     *
     * @param integer $ledgerId the id of the ledger
     * @param string|null $name name of the ledger
     * @param string|null $description a brief description of the ledger
     * @param integer|null $ledgerTypeId id of the ledger type this ledger belongs to
     * @return integer
     */
    public function updateLedger(int $ledgerId, string $name = null, string $description = null, int $ledgerTypeId = null): int
    {
        if (!$this->isIdValid($ledgerId))
            throw new LedgersException("invalid ledger id");

        if ($ledgerTypeId) {
            if (!$this->isTypeIdValid($ledgerTypeId))
                throw new LedgersException("invalid ledger type id");

            $thisTable = (new Transactions($this->_pdo))->getTables()[Ledgers::TABLE_LEDGERS];
            $Db = new Database(__FILE__, $this->_pdo, $thisTable);
            $where = [['column' => Ledgers::TABLE_LEDGERS . '_id', 'comparsion' => '=', 'bindAbleValue' => $ledgerId]];
            if ($Db->select(__LINE__, ['id'], $where)) {
                throw new LedgersException("ledger type cannot be changed it already has transaction(s)");
            }
        }

        $oldTable = $this->_db->getTable();
        $this->_db->setTable(Ledgers::TABLE_LEDGERS);

        $data = [];
        if ($description)
            $data['description'] = ['colValue' => $description, 'isFunction' => false, 'isBindAble' => true];
        if ($name)
            $data['name'] = ['colValue' => $name, 'isFunction' => false, 'isBindAble' => true];
        if ($ledgerTypeId) {
            $account = $this->generateAccountNo($ledgerTypeId);
            $data['account_no'] = ['colValue' => $account, 'isFunction' => false, 'isBindAble' => true];
            $data['ledger_type_id'] = ['colValue' => $ledgerTypeId, 'isFunction' => false, 'isBindAble' => true];
        }
        $updateId = 0;
        if ($data) {
            $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $ledgerId]];
            $updateId = $this->_db->update(__LINE__, $data, $where);
        }

        $this->_db->setTable($oldTable);
        return $updateId;
    }

    /**
     * delete an entire ledger
     *
     * @param integer $ledgerId the id of the ledger
     * @return void
     */
    public function deleteLedger(int $ledgerId)
    {
        if (!$this->isIdValid($ledgerId))
            throw new LedgersException("invalid ledger id");

        $thisTable = (new Transactions($this->_pdo))->getTables()[Ledgers::TABLE_LEDGERS];
        $Db = new Database(__FILE__, $this->_pdo, $thisTable);
        $where = [['column' => Ledgers::TABLE_LEDGERS . '_id', 'comparsion' => '=', 'bindAbleValue' => $ledgerId]];
        if ($Db->select(__LINE__, ['id'], $where))
            throw new LedgersException("ledger type cannot be deleted it already has transaction(s)");

        $oldTable = $this->_db->getTable();
        $this->_db->setTable(Ledgers::TABLE_LEDGERS);

        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $ledgerId]];
        $this->_db->delete(__LINE__, $where);
        $this->_db->setTable($oldTable);
    }

    /**
     * for creating ledgers type
     *
     * @param string $name name of the ledger
     * @param string $description a brief description of the ledger
     * @param integer $type type of ledger
     * @return integer
     */
    public function createLedgerType(string $name, string $description, string $type): int
    {
        if (!array_key_exists($type, Ledgers::TYPE))
            throw new LedgersException("invalid ledger type");

        $oldTable = $this->_db->getTable();
        $this->_db->setTable(Ledgers::TABLE_LEDGERS_TYPE);

        $data = [
            'description' => ['colValue' => $description, 'isFunction' => false, 'isBindAble' => true],
            'name' => ['colValue' => $name, 'isFunction' => false, 'isBindAble' => true],
            'type' => ['colValue' => $type, 'isFunction' => false, 'isBindAble' => true],
        ];
        $newLedgerType = $this->_db->insert(__LINE__, $data);
        $this->_db->setTable($oldTable);

        return $newLedgerType['lastInsertId'];
    }

    /**
     * change ledger type information
     *
     * @param integer $ledgerTypeId the id of the ledger type
     * @param string|null $name name of the ledger
     * @param string|null $description a brief description of the ledger
     * @param integer|null $ledgerTypeId id of the ledger type this ledger belongs to
     * @return integer
     */
    public function updateLedgerType(int $ledgerTypeId, string $name = null, string $description = null, $type = null): int
    {
        if (!$this->isTypeIdValid($ledgerTypeId))
            throw new LedgersException("invalid ledger type id");

        if ($type) {
            if (!array_key_exists(strtolower($type), Ledgers::TYPE))
                throw new LedgersException("invalid ledger type");
            $ledgerTypeInfo = $this->getLedgerTypeInfo($ledgerTypeId);
            $Db = new Database(__FILE__, $this->_pdo, Ledgers::TABLE_LEDGERS);
            $where = [['column' => Ledgers::TABLE_LEDGERS_TYPE . '_id', 'comparsion' => '=', 'bindAbleValue' => $ledgerTypeInfo['id']]];
            if ($Db->select(__LINE__, ['id'], $where)) {
                throw new LedgersException("type cannot be changed, a ledger of this type already exist");
            }
        }

        $oldTable = $this->_db->getTable();
        $this->_db->setTable(Ledgers::TABLE_LEDGERS_TYPE);

        $data = [];
        if ($description)
            $data['description'] = ['colValue' => $description, 'isFunction' => false, 'isBindAble' => true];
        if ($name)
            $data['name'] = ['colValue' => $name, 'isFunction' => false, 'isBindAble' => true];
        if ($type) {
            $data['type'] = ['colValue' => $type, 'isFunction' => false, 'isBindAble' => true];
        }
        $updateId = 0;
        if ($data) {
            $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $ledgerTypeId]];
            $updateId = $this->_db->update(__LINE__, $data, $where);
        }

        $this->_db->setTable($oldTable);
        return $updateId;
    }

    /**
     * change ledger type information
     *
     * @param integer $ledgerTypeId the id of the ledger type
     * @return void
     */
    public function deleteLedgerType(int $ledgerTypeId)
    {
        if (!$this->isTypeIdValid($ledgerTypeId))
            throw new LedgersException("invalid ledger type id");

        $ledgerTypeInfo = $this->getLedgerTypeInfo($ledgerTypeId);
        $Db = new Database(__FILE__, $this->_pdo, Ledgers::TABLE_LEDGERS);
        $where = [['column' => Ledgers::TABLE_LEDGERS_TYPE . '_id', 'comparsion' => '=', 'bindAbleValue' => $ledgerTypeInfo['id']]];
        if ($Db->select(__LINE__, ['id'], $where)) {
            throw new LedgersException("type cannot be deleted, a ledger of this type already exist");
        }
        $oldTable = $this->_db->getTable();
        $this->_db->setTable(Ledgers::TABLE_LEDGERS_TYPE);

        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $ledgerTypeId]];
        $this->_db->delete(__LINE__, $where);
        $this->_db->setTable($oldTable);
    }

    /**
     * get information of a ledger
     *
     * @param integer $id the ledger type id
     * @return array
     */
    public function getLedgerInfo(int $id): array
    {
        if (!$this->isIdValid($id))
            throw new LedgersException("invalid ledger id");

        $oldTable = $this->_db->getTable();
        $this->_db->setTable(Ledgers::TABLE_LEDGERS);

        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        $ledger = $this->_db->select(__LINE__, [], $where)[0];

        $this->_db->setTable($oldTable);
        return $ledger;
    }

    /**
     * get information of a ledger type
     *
     * @param integer $id the ledger type id
     * @return array
     */
    public function getLedgerTypeInfo(int $id): array
    {
        if (!$this->isTypeIdValid($id))
            throw new LedgersException("invalid ledger type id");

        $oldTable = $this->_db->getTable();
        $this->_db->setTable(Ledgers::TABLE_LEDGERS_TYPE);

        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        $ledgerType = $this->_db->select(__LINE__, [], $where)[0];

        $this->_db->setTable($oldTable);
        return $ledgerType;
    }

    /**
     * for getting leger ids based on type an sub type
     *
     * @param string|null $type the ledger type
     * @param string|null $subType the ledger sub type
     * @return array
     */
    public function getLedgerIds(string $type = null, string $subType = null): array
    {
        if ($type) {
            if (!array_key_exists(strtolower($type), Ledgers::TYPE))
                throw new LedgersException("invalid ledger type");
        }

        if ($subType) {
            $invalidSubType = true;
            foreach (Ledgers::TYPE as $aLledgerTypes) {
                if (in_array($subType, $aLledgerTypes)) {
                    $invalidSubType = false;
                    break;
                }
            }
            if ($invalidSubType)
                throw new LedgersException("invalid sub ledger type");
        }

        $oldTable = $this->_db->getTable();
        $isAllLedger = true;
        if ($type && !$subType) {
            $query = "SELECT id FROM " . Ledgers::TABLE_LEDGERS_TYPE . " WHERE type = :type";
            $bind = ['type' => $type];
            $isAllLedger = false;
        }
        if (!$type && $subType) {
            $query = "SELECT id FROM " . Ledgers::TABLE_LEDGERS_TYPE . " WHERE name = :name";
            $bind = ['name' => $subType];
            $isAllLedger = false;
        }
        if ($type && $subType) {
            $query = "SELECT id FROM " . Ledgers::TABLE_LEDGERS_TYPE . " WHERE name = :name AND type = :type";
            $bind = ['type' => $type, 'name' => $subType];
            $isAllLedger = false;
        }

        $ledgerTypeIds['data'] = $ledgerIds = [];
        if (!$isAllLedger) $ledgerTypeIds = $this->_db->queryStatment(__LINE__, $query, $bind);
        if ($ledgerTypeIds['data'] || $isAllLedger) {
            if ($ledgerTypeIds['data']) {
                foreach ($ledgerTypeIds['data'] as $aLedgerTypeId) {
                    $where[] = ['column' => Ledgers::TABLE_LEDGERS_TYPE . '_id', 'comparsion' => '=', 'bindAbleValue' => $aLedgerTypeId['id'], 'logic' => 'OR'];
                }
                unset($where[count($where) - 1]['logic']);
                $this->_db->setTable(Ledgers::TABLE_LEDGERS);
                if ($idCollection = $this->_db->select(__LINE__, ['id'], $where)) {
                    var_dump($idCollection);
                    exit;
                    foreach ($idCollection as $anId) {
                        $ledgerIds[] = $anId['id'];
                    }
                }
            }
            if ($isAllLedger) {
                $this->_db->setTable(Ledgers::TABLE_LEDGERS);
                if ($idCollection = $this->_db->select(__LINE__, ['id'])) {
                    foreach ($idCollection as $anId) {
                        $ledgerIds[] = $anId['id'];
                    }
                }
            }
        }

        $this->_db->setTable($oldTable);
        return $ledgerIds;
    }

    /**
     * check if a ledger has transaction
     *
     * @param int $id the ledger id
     * @return boolean
     */
    public function hasTransaction(int $id): bool
    {
        $has = false;
        if (!$this->isIdValid($id))
            throw new LedgersException("invalid ledger id");

        $oldTable = $this->_db->getTable();
        $this->_db->setTable(Transactions::TRNX_OWNER[0] . '_transaction');
        $where = [['column' => 'ledger_id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        $result = $this->_db->select(__LINE__, ["count(id)"], $where);
        $has = ($result[0]["count(id)"]) ? true : false;
        $this->_db->setTable($oldTable);
        return $has;
    }

    /**
     * for generation of sql for creating ledger type and ledger tables
     *
     * @return array
     */
    public function generateTblSQL(): array
    {
        $sqls = [];

        $typeEnum = $ledgerInsertData = "";
        foreach (Ledgers::TYPE as $aType => $aSubType) {
            $typeEnum .= "'$aType', ";
            foreach ($aSubType as $anItem) {
                $creatadAt = $updatedAt = date("Y-m-d H:i:s");
                $row = "('$anItem', 'default description of $anItem. PLEASE REPLACE WITH YOURS', '$aType', '$creatadAt', '$updatedAt'), ";
                $ledgerInsertData .= $row;
            }
        }
        $typeEnum = rtrim($typeEnum, ", ");
        $ledgerInsertData = rtrim($ledgerInsertData, ", ");

        $sqlLedgerType = "
                START TRANSACTION;
                CREATE TABLE IF NOT EXISTS " . Ledgers::TABLE_LEDGERS_TYPE . " (
                    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, 
                    name VARCHAR(255) NOT NULL ,
                    description VARCHAR(255) NULL , 
                    type ENUM($typeEnum) NOT NULL ,                    
                    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
                    updated_at TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,                     
                    PRIMARY KEY (id), 
                    UNIQUE (name)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

                INSERT INTO " . Ledgers::TABLE_LEDGERS_TYPE . " (name, description, type, created_at, updated_at) VALUES
                $ledgerInsertData;

                COMMIT;
            ";
        $sqls['type'] = $sqlLedgerType;

        $sqlLedger = "
                START TRANSACTION;
                CREATE TABLE IF NOT EXISTS " . Ledgers::TABLE_LEDGERS . " (
                    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT , 
                    ledger_type_id BIGINT UNSIGNED NOT NULL ,
                    account_no VARCHAR(255) NOT NULL , 
                    name VARCHAR(255) NOT NULL , 
                    description VARCHAR(255) NULL ,                     
                    deleteable ENUM('yes', 'no') NOT NULL ,
                    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
                    updated_at TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,                     
                    PRIMARY KEY (id), 
                    UNIQUE (account_no),
                    KEY " . Ledgers::TABLE_LEDGERS_TYPE . "_ledger_type_id_foreign (ledger_type_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ALTER TABLE " . Ledgers::TABLE_LEDGERS . "
                ADD CONSTRAINT " . Ledgers::TABLE_LEDGERS_TYPE . "_ledger_type_id_foreign FOREIGN KEY (ledger_type_id) REFERENCES " . Ledgers::TABLE_LEDGERS_TYPE . " (id) ON DELETE CASCADE ON UPDATE CASCADE;                
                COMMIT;
            ";
        $sqls['ledgers'] = $sqlLedger;

        return $sqls;
    }
}
