<?php
require_once dirname(__FILE__, 2) . "/connection.php";
/**
 * MyUsers
 *
 * This class is used for user interaction based on profile and user type
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   2021 Alabian Solutions Limited
 * @link        alabiansolutions.com
 */

class MyUsers extends Users
{
    /** @var array staff user name and table **/
    public const STAFF = ['name' => 'staff', 'table' => 'staff'];

    /** @var array staff user name and table **/
    public const STAFF_TYPE = ['reviewer', 'approver'];

    /** @var array individual user name and table **/
    public const INDIVIDUAL = ['name' => 'individual', 'table' => 'individual'];

    /** @var array msme user name and table **/
    public const MSME = ['name' => 'msme', 'table' => 'msme'];

    /** @var array guarantor user name and table **/
    public const GUARANTOR = ['name' => 'guarantor', 'table' => 'guarantor'];

    /** @var array guarantor user name and table **/
    public const GUARANTOR_TYPE = ['guarantor', 'promoter'];

    /**
     * Setup up Users
     * @param PDO $pdo an instant of PDO
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
    }

    /**
     * check if supplied profile id is a valid id
     *
     * @param integer $id the profile id
     * @return boolean
     */
    public function isProfileIdValid(int $id): bool
    {
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Users::TABLE_PROFILE);
        $isValid = ($Db->isDataInColumn(__LINE__, $id, 'id')) ? true : false;
        $Db->setTable($table);
        return $isValid;
    }

    /**
     * get profile id from user type id
     *
     * @param int $userTypeId the user type id
     * @param string $userType user type table
     * @return int
     */
    public function getProfileIdFrmUserId(int $userTypeId, string $userType): int
    {
        $Db = new Database(__FILE__, $this->_pdo, $userType);
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $userTypeId]];
        if ($info = $Db->select(__LINE__, ['profile_id'], $where)) {
            $profileId = $info[0]['profile_id'];
        } else {
            throw new UsersException("invalid user type id or user type table");
        }
        return $profileId;
    }

    /**
     * get some profile ids that matches the criteria specified
     *
     * @param array $criteria the search criteria [col=>val,...]
     * @param integer|null $limit the max no of ids to be get
     * @param integer|null $start the position of the first id
     * @param bool $latest if true the order is DESC
     * @return array
     */
    public function getProfileIds(array $criteria = [], int $limit = null, int $start = null, bool $latest = true): array
    {
        $ids = $where = [];

        if ($criteria) {
            foreach ($criteria as $key => $value) {
                $where[] = [
                    'column' => $key, 'comparsion' => '=',
                    'value' => $value, 'logic' => 'AND'
                ];
            }
        }
        $limitClause = [];
        if ($limit && !$start) $limitClause = [$limit];
        if (!$limit && $start) $limitClause = [10, $start];
        if ($limit && $start) $limitClause = [$limit, $start];
        $order = ($latest) ? 'DESC' : 'ASC';

        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Users::TABLE_PROFILE);

        if ($idCollection = $Db->select(__LINE__, ['id'], $where, ['id' => $order], $limitClause)) {
            foreach ($idCollection as $anId) $ids[] = $anId['id'];
        }

        $Db->setTable($table);
        return $ids;
    }

    /**
     * used to retrieve a profile details
     *
     * @param integer $id profile id of the user 
     * @return array
     */
    public function getProfileInfo(int $id): array
    {
        $info = [];
        $Db = $this->_db;
        $table = $Db->getTable();

        $Db->setTable(Users::TABLE_PROFILE);
        $criteria = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        if ($tblInfo = $Db->select(__LINE__, [], $criteria)) {
            $info[Users::TABLE_PROFILE] = $tblInfo[0];
        }

        $Db->setTable(Authentication::TABLE);
        $criteria = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $info[Users::TABLE_PROFILE]['login_id']]];
        if ($info[Users::TABLE_PROFILE]['login_id']) {
            $thisInfo = $Db->select(__LINE__, [], $criteria);
            $info[Authentication::TABLE] = $thisInfo[0];
        }

        $userTypeId = $info[Users::TABLE_PROFILE]['user_type'];
        $userTypeTable = $this->getTypeInfo()[$userTypeId]['table_name'];
        $Db->setTable($userTypeTable);
        $criteria = [[
            'column' => 'profile_id', 'comparsion' => '=',
            'bindAbleValue' => $id
        ]];
        if ($tblInfo = $Db->select(__LINE__, [], $criteria)) {
            $info[$userTypeTable] = $tblInfo[0];
        }

        $Db->setTable(Users::USERTYPE_TABLE);
        $criteria = [[
            'column' => 'id', 'comparsion' => '=',
            'bindAbleValue' => $userTypeId
        ]];
        if ($tblInfo = $Db->select(__LINE__, [], $criteria)) {
            $info[Users::USERTYPE_TABLE] = $tblInfo[0];
        }

        $Db->setTable($table);
        return $info;
    }

    /**
     * for deleting users using profile id
     *
     * @param integer $id the profile id of the user
     * @return void
     */
    public function deleteProfile(int $id)
    {
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Users::TABLE_PROFILE);
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        $Db->delete(__LINE__, $where);
        $Db->setTable($table);
    }
}
