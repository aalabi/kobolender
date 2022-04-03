<?php

/**
 * Authority
 *
 * This class is used for handling user roles
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   2022 Alabian Solutions Limited
 * @link        alabiansolutions.com
 */

class AuthorityExpection extends Exception
{
}

class Authority
{
    /** @var  Database an instance of Database */
    protected $_db;

    /** @var  PDO an instance of PDO type */
    private $_pdo;

    /** @var  string the table for task: task that can be performed on the app */
    public const TABLE_TASKS = 'tasks';

    /** @var  string the table for roles: the roles that can perform the tasks in the app */
    public const TABLE_ROLES = 'roles';

    /** @var  string the table for task_role: tasks assigned to each role */
    public const TABLE_TASK_ROLE = 'task_role';

    /** @var  string the table for group: group for grouping of profile for the role allocation purpose */
    public const TABLE_GROUPS = 'groups';

    /** @var  string the table for group_member: profile id in each group */
    public const TABLE_GROUP_PROFILE = 'group_profile';

    /** @var  string the table for role_doers: users that are assigned to a role(for task execution) */
    public const TABLE_ROLE_DOERS = 'role_doers';

    /** @var array a collection of role_doers  */
    public const ROLE_DOERS = ['user_type', 'profile', 'group'];

    /**
     * Setup up Authority
     * @param PDO $pdo an instant of PDO
     */
    public function __construct(PDO $pdo)
    {
        $this->_db = new Database(__FILE__, $pdo, Authentication::TABLE);
        $this->_pdo = $pdo;
    }

    /**
     * check if supplied id is a valid task id
     *
     * @param integer $id the task id
     * @return boolean
     */
    public function isTaskIdValid(int $id): bool
    {
        return $this->isIdValid(Authority::TABLE_TASKS, $id);
    }

    /**
     * for creating task
     *
     * @param string $name the task name
     * @param string $description the task description
     * @return int
     */
    public function createTask(string $name, string $description = null): int
    {
        return $this->create(Authority::TABLE_TASKS, $name, $description,);
    }

    /**
     * find task ids that meets search criteria or return empty array when no id is found
     *
     * @param string $name search task name
     * @param string $description search task description
     * @param array $match ['name'=>exact|like, description=>exact|like]
     * @return array
     */
    public function findTaskIds(string $name = null, string $description = null, $match = ['name' => 'exact', 'description' => 'exact']): array
    {
        return $this->findIds(Authority::TABLE_ROLES, $name, $description, $match);
    }

    /**
     * get a task info or throw exception if task id is invalid
     *
     * @param int $id the task id
     * @return array
     */
    public function getTaskInfo(int $id): array
    {
        return $this->getInfo(Authority::TABLE_TASKS, $id);
    }

    /**
     * change task info or throw exception if task id is invalid
     *
     * @param int $id the task id
     * @param string $name the  task name
     * @param string $description the task description     
     * @return void
     */
    public function changeTaskInfo(int $id, string $name = null, string $description = null): void
    {
        $this->changeInfo(Authority::TABLE_TASKS, $id, $name, $description);
    }

    /**
     * delete task info or throw exception if task id is invalid
     *
     * @param int $id the task id
     * @return void
     */
    public function deleteTask(int $id): void
    {
        $this->delete(Authority::TABLE_TASKS, $id);
    }

    /**
     * check if supplied id is a valid role id
     *
     * @param integer $id the role id
     * @return boolean
     */
    public function isRoleIdValid(int $id): bool
    {
        return $this->isIdValid(Authority::TABLE_ROLES, $id);
    }

    /**
     * for creating role
     *
     * @param string $name the task name
     * @param string $description the task description
     * @return int
     */
    public function createRole(string $name, string $description = null): int
    {
        return $this->create(Authority::TABLE_ROLES, $name, $description);
    }

    /**
     * find role ids that meets search criteria or return empty array when no id is found
     *
     * @param string $name search task name
     * @param string $description search task description
     * @param array $match ['name'=>exact|like, description=>exact|like]
     * @return array
     */
    public function findRoleIds(string $name = null, string $description = null, $match = ['name' => 'exact', 'description' => 'exact']): array
    {
        return $this->findIds(Authority::TABLE_ROLES, $name, $description, $match);
    }

    /**
     * get a role info or throw exception if role id is invalid
     *
     * @param int $id the role id
     * @return array
     */
    public function getRoleInfo(int $id): array
    {
        return $this->getInfo(Authority::TABLE_ROLES, $id);
    }

    /**
     * change role info or throw exception if role id is invalid
     *
     * @param int $id the role id
     * @param string $name the role name
     * @param string $description the role description     
     * @return void
     */
    public function changeRoleInfo(int $id, string $name = null, string $description = null): void
    {
        $this->changeInfo(Authority::TABLE_ROLES, $id, $name, $description);
    }

    /**
     * delete role info or throw exception if role id is invalid
     *
     * @param int $id the role id
     * @return void
     */
    public function deleteRole(int $id): void
    {
        $this->delete(Authority::TABLE_ROLES, $id);
    }

    /**
     * add a task to a particular role
     *
     * @param int $taskId the task id
     * @param int $roleId the role id
     * @return int
     */
    public function addTaskToRole(int $taskId, int $roleId): int
    {
        $taskRoleId = 0;
        if (!$this->isIdValid(Authority::TABLE_TASKS, $taskId)) throw new AuthorityExpection("invalid task id");
        if (!$this->isIdValid(Authority::TABLE_ROLES, $roleId)) throw new AuthorityExpection("invalid role id");
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authority::TABLE_TASK_ROLE);
        $where = [
            ['column' => 'task', 'comparsion' => '=', 'bindAbleValue' => $taskId, 'logic' => 'AND'],
            ['column' => 'role', 'comparsion' => '=', 'bindAbleValue' => $roleId],
        ];
        if (!$Db->select(__LINE__, [], $where)) {
            $data = [
                'task' => ['colValue' => $taskId, 'isFunction' => false, 'isBindAble' => true],
                'role' => ['colValue' => $roleId, 'isFunction' => false, 'isBindAble' => true],
            ];
            $taskRoleId = $Db->insert(__LINE__, $data)['lastInsertId'];
        }
        $Db->setTable($table);
        return $taskRoleId;
    }

    /**
     * remvove a task from a particular role
     *
     * @param int $taskId the task id
     * @param int $roleId the role id
     * @return void
     */
    public function removeTaskFromRole(int $taskId, int $roleId)
    {
        if (!$this->isIdValid(Authority::TABLE_TASKS, $taskId)) throw new AuthorityExpection("invalid task id");
        if (!$this->isIdValid(Authority::TABLE_ROLES, $roleId)) throw new AuthorityExpection("invalid role id");
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authority::TABLE_TASK_ROLE);
        $where = [
            ['column' => 'task', 'comparsion' => '=', 'bindAbleValue' => $taskId, 'logic' => 'AND'],
            ['column' => 'role', 'comparsion' => '=', 'bindAbleValue' => $roleId],
        ];
        if ($taskRole = $Db->select(__LINE__, [], $where)) {
            $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $taskRole[0]['id']]];
            $Db->delete(__LINE__, $where);
        }
        $Db->setTable($table);
    }

    /**
     * get the task ids of a role
     *
     * @param int $roleId the role id
     * @return array
     */
    public function getTasksInRole(int $roleId): array
    {
        $taskIds = [];
        if (!$this->isIdValid(Authority::TABLE_ROLES, $roleId)) throw new AuthorityExpection("invalid role id");
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authority::TABLE_TASK_ROLE);
        $where = [
            ['column' => 'role', 'comparsion' => '=', 'bindAbleValue' => $roleId],
        ];
        if ($tasks = $Db->select(__LINE__, ['task'], $where)) {
            foreach ($tasks as $aTask) {
                $taskIds[] = $aTask['task'];
            }
        }
        $Db->setTable($table);
        return $taskIds;
    }

    /**
     * check if a role has a particular task
     *
     * @param int $taskId the task id
     * @param int $roleId the role id
     * @return bool
     */
    public function doesRoleHasTask(int $taskId, int $roleId): bool
    {
        $hasTask = false;
        if (!$this->isIdValid(Authority::TABLE_TASKS, $taskId)) throw new AuthorityExpection("invalid task id");
        if (!$this->isIdValid(Authority::TABLE_ROLES, $roleId)) throw new AuthorityExpection("invalid role id");
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authority::TABLE_TASK_ROLE);
        $where = [
            ['column' => 'task', 'comparsion' => '=', 'bindAbleValue' => $taskId, 'logic' => 'AND'],
            ['column' => 'role', 'comparsion' => '=', 'bindAbleValue' => $roleId],
        ];
        if ($Db->select(__LINE__, [], $where)) $hasTask = true;
        $Db->setTable($table);
        return $hasTask;
    }

    /**
     * get all the role ids a task belongs to
     *
     * @param int $taskId the task id
     * @return array
     */
    public function getTaskRole(int $taskId): array
    {
        $rolesIds = [];
        if (!$this->isIdValid(Authority::TABLE_TASKS, $taskId)) throw new AuthorityExpection("invalid task id");
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authority::TABLE_TASK_ROLE);
        $where = [
            ['column' => 'task', 'comparsion' => '=', 'bindAbleValue' => $taskId],
        ];
        if ($roles = $Db->select(__LINE__, ['role'], $where)) {
            foreach ($roles as $aRole) {
                $rolesIds[] = $aRole['role'];
            }
        }
        $Db->setTable($table);
        return $rolesIds;
    }

    /**
     * check if supplied id is a valid group id
     *
     * @param integer $id the group id
     * @return boolean
     */
    public function isGroupIdValid(int $id): bool
    {
        return $this->isIdValid(Authority::TABLE_GROUPS, $id);
    }

    /**
     * for creating group
     *
     * @param string $name the group name
     * @param string $description the group description
     * @return int
     */
    public function createGroup(string $name, string $description = null): int
    {
        return $this->create(Authority::TABLE_GROUPS, $name, $description);
    }

    /**
     * find group ids that meets search criteria or return empty array when no id is found
     *
     * @param string $name search group name
     * @param string $description search group description
     * @param array $match ['name'=>exact|like, description=>exact|like]
     * @return array
     */
    public function findGroupIds(string $name = null, string $description = null, $match = ['name' => 'exact', 'description' => 'exact']): array
    {
        return $this->findIds(Authority::TABLE_GROUPS, $name, $description, $match);
    }

    /**
     * get a group info or throw exception if group id is invalid
     *
     * @param int $id the group id
     * @return array
     */
    public function getGroupInfo(int $id): array
    {
        return $this->getInfo(Authority::TABLE_GROUPS, $id);
    }

    /**
     * change group info or throw exception if group id is invalid
     *
     * @param int $id the group id
     * @param string $name the group name
     * @param string $description the group description     
     * @return void
     */
    public function changeGroupInfo(int $id, string $name = null, string $description = null): void
    {
        $this->changeInfo(Authority::TABLE_GROUPS, $id, $name, $description);
    }

    /**
     * delete group info or throw exception if group id is invalid
     *
     * @param int $id the group id
     * @return void
     */
    public function deleteGroup(int $id): void
    {
        $this->delete(Authority::TABLE_GROUPS, $id);
    }

    /**
     * add a profile to a particular group
     *
     * @param int $profileId the profile id
     * @param int $groupId the group id
     * @return int
     */
    public function addProfileToGroup(int $profileId, int $groupId): int
    {
        $profileGroupId = 0;
        if (!$this->isIdValid(Users::TABLE_PROFILE, $profileId)) throw new AuthorityExpection("invalid profile id");
        if (!$this->isIdValid(Authority::TABLE_GROUPS, $groupId)) throw new AuthorityExpection("invalid group id");
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authority::TABLE_GROUP_PROFILE);
        $where = [
            ['column' => 'profile', 'comparsion' => '=', 'bindAbleValue' => $profileId, 'logic' => 'AND'],
            ['column' => 'groups', 'comparsion' => '=', 'bindAbleValue' => $groupId],
        ];
        if (!$Db->select(__LINE__, [], $where)) {
            $data = [
                'profile' => ['colValue' => $profileId, 'isFunction' => false, 'isBindAble' => true],
                'groups' => ['colValue' => $groupId, 'isFunction' => false, 'isBindAble' => true],
            ];
            $profileGroupId = $Db->insert(__LINE__, $data)['lastInsertId'];
        }
        $Db->setTable($table);
        return $profileGroupId;
    }

    /**
     * remvove a profile from a particular group
     *
     * @param int $profileId the profile id
     * @param int $groupId the group id
     * @return void
     */
    public function removeProfileFromGroup(int $profileId, int $groupId)
    {
        if (!$this->isIdValid(Users::TABLE_PROFILE, $profileId)) throw new AuthorityExpection("invalid profile id");
        if (!$this->isIdValid(Authority::TABLE_GROUPS, $groupId)) throw new AuthorityExpection("invalid group id");

        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authority::TABLE_GROUP_PROFILE);
        $where = [
            ['column' => 'groups', 'comparsion' => '=', 'bindAbleValue' => $groupId, 'logic' => 'AND'],
            ['column' => 'profile', 'comparsion' => '=', 'bindAbleValue' => $profileId],
        ];
        if ($groupProfile = $Db->select(__LINE__, [], $where)) {
            $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $groupProfile[0]['id']]];
            $Db->delete(__LINE__, $where);
        }
        $Db->setTable($table);
    }

    /**
     * get the profile ids in a group
     *
     * @param int $groupId the group id
     * @return array
     */
    public function getProfilesInGroup(int $groupId): array
    {
        $profileIds = [];
        if (!$this->isIdValid(Authority::TABLE_GROUPS, $groupId)) throw new AuthorityExpection("invalid group id");
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authority::TABLE_GROUP_PROFILE);
        $where = [
            ['column' => 'groups', 'comparsion' => '=', 'bindAbleValue' => $groupId],
        ];
        if ($profiles = $Db->select(__LINE__, ['profile'], $where)) {
            foreach ($profiles as $aProfile) {
                $profileIds[] = $aProfile['profile'];
            }
        }
        $Db->setTable($table);
        return $profileIds;
    }

    /**
     * add a doer to a particular role
     *
     * @param int $doerId the id of the doer
     * @param int $doerType the doer type which is either profile, group, or user_type
     * @param int $roleId the role id
     * @return int
     */
    public function addDoerToRole(int $doerId, string $doerType, int $roleId): int
    {
        $roleDoerId = 0;
        if ($doerType == Authority::ROLE_DOERS[0]) {
            if (!$this->isIdValid(Users::USERTYPE_TABLE, $doerId)) throw new AuthorityExpection("invalid user type id");
        }
        if ($doerType == Authority::ROLE_DOERS[1]) {
            if (!$this->isIdValid(Users::TABLE_PROFILE, $doerId)) throw new AuthorityExpection("invalid profile id");
        }
        if ($doerType == Authority::ROLE_DOERS[2]) {
            if (!$this->isIdValid(Authority::TABLE_GROUPS, $doerId)) throw new AuthorityExpection("invalid group id");
        }
        if (!in_array($doerType, Authority::ROLE_DOERS)) throw new AuthorityExpection("invalid doer type");
        if (!$this->isIdValid(Authority::TABLE_ROLES, $roleId)) throw new AuthorityExpection("invalid role id");
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authority::TABLE_ROLE_DOERS);
        $where = [
            ['column' => 'doer', 'comparsion' => '=', 'bindAbleValue' => $doerId, 'logic' => 'AND'],
            ['column' => 'doer_type', 'comparsion' => '=', 'bindAbleValue' => $doerType, 'logic' => 'AND'],
            ['column' => 'role', 'comparsion' => '=', 'bindAbleValue' => $roleId],
        ];
        if (!$Db->select(__LINE__, [], $where)) {
            $data = [
                'doer' => ['colValue' => $doerId, 'isFunction' => false, 'isBindAble' => true],
                'doer_type' => ['colValue' => $doerType, 'isFunction' => false, 'isBindAble' => true],
                'role' => ['colValue' => $roleId, 'isFunction' => false, 'isBindAble' => true],
            ];
            $roleDoerId = $Db->insert(__LINE__, $data)['lastInsertId'];
        }
        $Db->setTable($table);
        return $roleDoerId;
    }

    /**
     * remvove a doer from a particular role
     *
     * @param int $doerId the id of the doer
     * @param int $doerType the doer type which is either profile, group, or user_type
     * @param int $roleId the role id
     * @return void
     */
    public function removeDoerFromRole(int $doerId, string $doerType, int $roleId)
    {
        if ($doerType == Authority::ROLE_DOERS[0]) {
            if (!$this->isIdValid(Users::USERTYPE_TABLE, $doerId)) throw new AuthorityExpection("invalid user type id");
        }
        if ($doerType == Authority::ROLE_DOERS[1]) {
            if (!$this->isIdValid(Users::TABLE_PROFILE, $doerId)) throw new AuthorityExpection("invalid profile id");
        }
        if ($doerType == Authority::ROLE_DOERS[2]) {
            if (!$this->isIdValid(Authority::TABLE_GROUPS, $doerId)) throw new AuthorityExpection("invalid group id");
        }
        if (!in_array($doerType, Authority::ROLE_DOERS)) throw new AuthorityExpection("invalid doer type");
        if (!$this->isIdValid(Authority::TABLE_ROLES, $roleId)) throw new AuthorityExpection("invalid role id");
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authority::TABLE_ROLE_DOERS);
        $where = [
            ['column' => 'doer', 'comparsion' => '=', 'bindAbleValue' => $doerId, 'logic' => 'AND'],
            ['column' => 'doer_type', 'comparsion' => '=', 'bindAbleValue' => $doerType, 'logic' => 'AND'],
            ['column' => 'role', 'comparsion' => '=', 'bindAbleValue' => $roleId],
        ];
        if ($doerRole = $Db->select(__LINE__, [], $where)) {
            $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $doerRole[0]['id']]];
            $Db->delete(__LINE__, $where);
        }
        $Db->setTable($table);
    }

    /**
     * get the doers id and type of a role
     *
     * @param int $roleId the role id
     * @return array ['id'=>$id, 'type'=>$type]
     */
    public function getDoersInRole(int $roleId): array
    {
        $doers = [];
        if (!$this->isIdValid(Authority::TABLE_ROLES, $roleId)) throw new AuthorityExpection("invalid role id");
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authority::TABLE_ROLE_DOERS);
        $where = [
            ['column' => 'role', 'comparsion' => '=', 'bindAbleValue' => $roleId],
        ];
        if ($doerCollection = $Db->select(__LINE__, ['doer', 'doer_type'], $where)) {
            foreach ($doerCollection as $aDoer) {
                $doers[] = ['id' => $aDoer['doer'], 'type' => $aDoer['doer_type']];
            }
        }
        $Db->setTable($table);
        return $doers;
    }

    /**
     * get the doer's role
     *
     * @param int $doerId the id of the doer
     * @param int $doerType the doer type which is either profile, group, or user_type
     * @return array
     */
    public function getDoerRoles(int $doerId, string $doerType): array
    {
        $roles = [];
        if ($doerType == Authority::ROLE_DOERS[0]) {
            if (!$this->isIdValid(Users::USERTYPE_TABLE, $doerId)) throw new AuthorityExpection("invalid user type id");
        }
        if ($doerType == Authority::ROLE_DOERS[1]) {
            if (!$this->isIdValid(Users::TABLE_PROFILE, $doerId)) throw new AuthorityExpection("invalid profile id");
        }
        if ($doerType == Authority::ROLE_DOERS[2]) {
            if (!$this->isIdValid(Authority::TABLE_GROUPS, $doerId)) throw new AuthorityExpection("invalid group id");
        }
        if (!in_array($doerType, Authority::ROLE_DOERS)) throw new AuthorityExpection("invalid doer type");
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authority::TABLE_ROLE_DOERS);
        $where = [
            ['column' => 'doer', 'comparsion' => '=', 'bindAbleValue' => $doerId, 'logic' => 'AND'],
            ['column' => 'doer_type', 'comparsion' => '=', 'bindAbleValue' => $doerType]
        ];
        if ($roleCollection = $Db->select(__LINE__, ['role'], $where)) {
            foreach ($roleCollection as $aRole) {
                $roles[] = [$aRole['role']];
            }
        }
        $Db->setTable($table);
        return $roles;
    }

    /**
     * get the profile's task
     *
     * @param int $profileId the id of the profile
     * @return array
     */
    public function getProfileTask(int $profileId): array
    {
        $tasks = [];
        if (!$this->isIdValid(Users::TABLE_PROFILE, $profileId)) throw new AuthorityExpection("invalid profile id");
        $Db = $this->_db;
        $table = $Db->getTable();

        //get this profile groups id
        $groups = [];
        $Db->setTable(Authority::TABLE_GROUP_PROFILE);
        $where = [
            ['column' => 'profile', 'comparsion' => '=', 'bindAbleValue' => $profileId]
        ];
        if ($groupCollection = $Db->select(__LINE__, ['groups'], $where)) {
            foreach ($groupCollection as $aGroup) {
                $groups[] = $aGroup['groups'];
            }
        }

        //get these groups role
        $roles = [];
        $Db->setTable(Authority::TABLE_ROLE_DOERS);
        if ($groups) {
            foreach ($groups as $aGroup) {
                $where = [
                    ['column' => 'doer', 'comparsion' => '=', 'bindAbleValue' => $aGroup, 'logic' => 'AND'],
                    ['column' => 'doer_type', 'comparsion' => '=', 'bindAbleValue' => Authority::ROLE_DOERS[2]]
                ];
                if ($roleCollection = $Db->select(__LINE__, ['role'], $where)) {
                    foreach ($roleCollection as $aRole) {
                        $roles[] = $aRole['role'];
                    }
                }
            }
        }

        //get this profile's role
        $Db->setTable(Authority::TABLE_ROLE_DOERS);
        $where = [
            ['column' => 'doer', 'comparsion' => '=', 'bindAbleValue' => $profileId, 'logic' => 'AND'],
            ['column' => 'doer_type', 'comparsion' => '=', 'bindAbleValue' => Authority::ROLE_DOERS[1]]
        ];
        if ($roleCollection = $Db->select(__LINE__, ['role'], $where)) {
            foreach ($roleCollection as $aRole) {
                $roles[] = $aRole['role'];
            }
        }

        //get this profile user type id
        $Db->setTable(Users::TABLE_PROFILE);
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $profileId]];
        $userTypeId = $Db->select(__LINE__, ['user_type'], $where)[0]['user_type'];

        //get user type's roles
        $Db->setTable(Authority::TABLE_ROLE_DOERS);
        $where = [
            ['column' => 'doer', 'comparsion' => '=', 'bindAbleValue' => $userTypeId, 'logic' => 'AND'],
            ['column' => 'doer_type', 'comparsion' => '=', 'bindAbleValue' => Authority::ROLE_DOERS[0]]
        ];
        if ($roleCollection = $Db->select(__LINE__, ['role'], $where)) {
            foreach ($roleCollection as $aRole) {
                $roles[] = $aRole['role'];
            }
        }

        //get the tasks
        $Db->setTable(Authority::TABLE_TASK_ROLE);
        if ($roles) {
            foreach ($roles as $aRole) {
                $where = [
                    ['column' => 'role', 'comparsion' => '=', 'bindAbleValue' => $aRole]
                ];
                if ($taskCollection = $Db->select(__LINE__, ['task'], $where)) {
                    foreach ($taskCollection as $aTask) {
                        $tasks[] = $aTask['task'];
                    }
                }
            }
        }

        $Db->setTable($table);
        $tasks = array_unique($tasks);
        return $tasks;
    }

    /**
     * get the profile's Role
     *
     * @param int $profileId the id of the profile
     * @return array
     */
    public function getProfileRole(int $profileId): array
    {
        $roles = [];
        if (!$this->isIdValid(Users::TABLE_PROFILE, $profileId)) throw new AuthorityExpection("invalid profile id");
        $Db = $this->_db;
        $table = $Db->getTable();

        $Db->setTable(Authority::TABLE_ROLE_DOERS);
        $where = [
            ['column' => 'doer', 'comparsion' => '=', 'bindAbleValue' => $profileId, 'logic' => 'AND'],
            ['column' => 'doer_type', 'comparsion' => '=', 'bindAbleValue' => Authority::ROLE_DOERS[1]]
        ];
        if ($roleCollection = $Db->select(__LINE__, ['role'], $where)) {
            foreach ($roleCollection as $aRole) {
                $roles[] = $aRole['role'];
            }
        }

        $Db->setTable($table);
        $roles = array_unique($roles);
        return $roles;
    }

    /**
     * get the profile's group
     *
     * @param int $profileId the id of the profile
     * @return array
     */
    public function getProfileGroup(int $profileId): array
    {
        $groups = [];
        if (!$this->isIdValid(Users::TABLE_PROFILE, $profileId)) throw new AuthorityExpection("invalid profile id");
        $Db = $this->_db;
        $table = $Db->getTable();

        //get this profile groups id
        $groups = [];
        $Db->setTable(Authority::TABLE_GROUP_PROFILE);
        $where = [
            ['column' => 'profile', 'comparsion' => '=', 'bindAbleValue' => $profileId]
        ];
        if ($groupCollection = $Db->select(__LINE__, ['groups'], $where)) {
            foreach ($groupCollection as $aGroup) {
                $groups[] = $aGroup['groups'];
            }
        }

        $Db->setTable($table);
        $groups = array_unique($groups);
        return $groups;
    }

    /**
     * check if supplied id is a valid id
     *
     * @param string $table the db table to be checked
     * @param integer $id the id to be valided
     * @return boolean
     */
    private function isIdValid(string $table, int $id): bool
    {
        $Db = $this->_db;
        $oldTable = $Db->getTable();
        $Db->setTable($table);
        $isValid = ($Db->isDataInColumn(__LINE__, $id, 'id')) ? true : false;
        $Db->setTable($oldTable);
        return $isValid;
    }

    /**
     * for creating row in some db table
     *
     * @param string $table the db table taking the new row
     * @param string $name the row name
     * @param string $description the row description
     * @return int
     */
    private function create(string $table, string $name, string $description = null): int
    {
        $Db = $this->_db;
        $oldTtable = $Db->getTable();
        $Db->setTable($table);
        $data = ['name' => ['colValue' => $name, 'isFunction' => false, 'isBindAble' => true]];
        if ($description)
            $data['description'] = ['colValue' => $description, 'isFunction' => false, 'isBindAble' => true];
        $newTask = $Db->insert(__LINE__, $data);
        $Db->setTable($oldTtable);
        return $newTask['lastInsertId'];
    }

    /**
     * find ids that meets search criteria or return empty array when no id is found
     * @param string $table search table
     * @param string $name search name
     * @param string $description search description
     * @param array $match ['name'=>exact|like, description=>exact|like]
     * @return array
     */
    private function findIds(
        string $table,
        string $name = null,
        string $description = null,
        $match = ['name' => 'exact', 'description' => 'exact']
    ): array {
        $ids = [];
        $Db = $this->_db;
        $sql = " SELECT id FROM $table ";
        if ($name) {
            $sql .= " WHERE ";
            $nameMatch = ($match['name'] == 'exact') ? " name = '$name' " : " name LIKE '%$name%' ";
            $sql .= " $nameMatch ";
        }
        if ($description) {
            $sql .= ($sql == " SELECT id FROM $table ") ? " WHERE " : " AND ";
            $descriptionMatch = ($match['description'] == 'exact') ? " description = '$description' " : " description LIKE '%$description%' ";
            $sql .= " $descriptionMatch ";
        }
        $result = $Db->queryStatment(__LINE__, $sql);
        if ($result['data']) {
            foreach ($result['data'] as $aData) $ids[] = $aData['id'];
        }
        return $ids;
    }

    /**
     * get info or throw exception if id is invalid
     *
     * @param string $table the db table
     * @param int $id the id
     * @return array
     */
    private function getInfo(string $table, int $id): array
    {
        if (!$this->isIdValid($table, $id)) throw new AuthorityExpection("invalid $table id");
        $info = [];
        $Db = $this->_db;
        $oldTable = $Db->getTable();
        $Db->setTable($table);
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        $info = $Db->select(__LINE__, [], $where)[0];
        $Db->setTable($oldTable);
        return $info;
    }

    /**
     * change info or throw exception if id is invalid
     *
     * @param string $table the db table to work on
     * @param int $id the task id
     * @param string $name the  task name
     * @param string $description the task description     
     * @return void
     */
    private function changeInfo(string $table, int $id, string $name = null, string $description = null): void
    {
        if (!$this->isIdValid($table, $id)) throw new AuthorityExpection("invalid $table id");
        $Db = $this->_db;
        $oldTable = $Db->getTable();
        $Db->setTable($table);
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        $columns = [];
        if ($name)
            $columns['name'] = ['colValue' => $name, 'isFunction' => false, 'isBindAble' => true];
        if ($description)
            $columns['description'] = ['colValue' => $description, 'isFunction' => false, 'isBindAble' => true];
        if ($columns)
            $Db->update(__LINE__, $columns, $where);
        $Db->setTable($oldTable);
    }

    /**
     * delete row or throw exception if row id is invalid
     *
     * @param string $table table
     * @param int $id the id
     * @return void
     */
    private function delete(string $table, int $id): void
    {
        if (!$this->isIdValid($table, $id)) throw new AuthorityExpection("invalid $table id");
        $Db = $this->_db;
        $oldTable = $Db->getTable();
        $Db->setTable($table);
        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        $Db->delete(__LINE__, $where);
        $Db->setTable($oldTable);
    }

    /**
     * for generation of an array of sql string for creating tables     
     * @return array
     */
    public function generateTblsSQL(): array
    {
        $sql = [];
        $tableCollection = [
            Authority::TABLE_TASKS, Authority::TABLE_ROLES, Authority::TABLE_ROLE_DOERS,
            Authority::TABLE_TASK_ROLE, Authority::TABLE_GROUPS, Authority::TABLE_GROUP_PROFILE
        ];
        $staticColumns = "name VARCHAR(255) NOT NULL ,description VARCHAR(255) NULL ,";

        $doerTypeEnum = "";
        foreach (Authority::ROLE_DOERS as $aDoer) {
            $doerTypeEnum .= "'$aDoer', ";
        }
        $doerTypeEnum = rtrim($doerTypeEnum, ", ");

        $columns = [
            Authority::TABLE_ROLE_DOERS => "                
                doer BIGINT UNSIGNED NOT NULL ,
                role BIGINT UNSIGNED NOT NULL ,
                doer_type ENUM($doerTypeEnum) NOT NULL,",
            Authority::TABLE_GROUP_PROFILE => "                
                profile BIGINT UNSIGNED NOT NULL ,
                groups BIGINT UNSIGNED NOT NULL ,",
            Authority::TABLE_TASK_ROLE => "                
                role BIGINT UNSIGNED NOT NULL ,
                task BIGINT UNSIGNED NOT NULL ,",
        ];
        $columns[Authority::TABLE_TASKS] = $columns[Authority::TABLE_ROLES] = $columns[Authority::TABLE_GROUPS] = $staticColumns;

        foreach ($tableCollection as $table) {
            $alterString = $key = "";
            if ($table == Authority::TABLE_TASK_ROLE) {
                $key = "
                    , KEY {$table}_role_foreign (role)
                    , KEY {$table}_task_foreign (task)
                ";
                $alterString = "
                    ALTER TABLE $table
                    ADD CONSTRAINT {$table}_role_foreign FOREIGN KEY (role) REFERENCES " . Authority::TABLE_ROLES . " (id) ON DELETE CASCADE ON UPDATE CASCADE;
                    ALTER TABLE $table
                    ADD CONSTRAINT {$table}_task_foreign FOREIGN KEY (task) REFERENCES " . Authority::TABLE_TASKS . " (id) ON DELETE CASCADE ON UPDATE CASCADE;
                ";
            }

            if ($table == Authority::TABLE_GROUP_PROFILE) {
                $key = "
                    , KEY {$table}_group_foreign (groups)
                    , KEY {$table}_profile_foreign (profile)
                ";
                $alterString = "
                    ALTER TABLE $table
                    ADD CONSTRAINT {$table}_group_foreign FOREIGN KEY (groups) REFERENCES " . Authority::TABLE_GROUPS . " (id) ON DELETE CASCADE ON UPDATE CASCADE;
                    ALTER TABLE $table
                    ADD CONSTRAINT {$table}_profile_foreign FOREIGN KEY (profile) REFERENCES " . Users::TABLE_PROFILE . " (id) ON DELETE CASCADE ON UPDATE CASCADE;
                ";
            }

            if ($table == Authority::TABLE_ROLE_DOERS) {
                $key = ", KEY {$table}_role_foreign (role) ";
                $alterString = "
                    ALTER TABLE $table
                    ADD CONSTRAINT {$table}_role_foreign FOREIGN KEY (role) REFERENCES " . Authority::TABLE_ROLES . " (id) ON DELETE CASCADE ON UPDATE CASCADE;
                ";
            }

            $anSql = "
            START TRANSACTION;
            CREATE TABLE IF NOT EXISTS $table (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT ,
                {$columns[$table]}
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
                updated_at TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,                 
                PRIMARY KEY (id)
                $key
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            $alterString
            COMMIT;
            ";
            $sql[] = $anSql;
        }

        return $sql;
    }
}
