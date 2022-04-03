<?php

/**
 * Users
 *
 * This class is used for user interaction
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   2021 Alabian Solutions Limited
 * @link        alabiansolutions.com
 */

class UsersException extends Exception
{
}

class Users implements IItems
{
    /** @var  Database an instance of Database */
    protected $_db;

    /** @var  PDO an instance of PDO type */
    protected $_pdo;

    /** @var array the user type in the app */
    protected static $types;

    /** @var array the table name for each user type*/
    protected static $userTypeTable;

    /** @var  string table for common profile info of users */
    public const TABLE_PROFILE = "profile";

    /** @var  string name of the table holding user type info details */
    public const USERTYPE_TABLE = "user_type";

    /** @var string male default profile image  */
    public const PROFILE_IMG_MALE = "default-profile-male.png";

    /** @var string female default profile image  */
    public const PROFILE_IMG_FEMALE = "default-profile-female.png";

    /** @var string default profile image */
    public const PROFILE_IMG = "default-profile.png";

    /** @var string profile image pathbackend */
    public const PROFILE_IMG_PATHBACKEND = Functions::ASSET_IMG_PATHBACKEND . "profile-image/";

    /** @var string profile image urlbackend */
    public const PROFILE_IMG_URLBACKEND = Functions::ASSET_IMG_URLBACKEND . "profile-image/";

    /** @var string password reset urlbackend */
    public const PW_RESET_URLBACKEND = URLBACKEND . "password-reset/";

    /**
     * Setup up Users
     * @param PDO $pdo an instant of PDO
     */
    public function __construct(PDO $pdo)
    {
        $this->_db = new Database(__FILE__, $pdo, Authentication::TABLE);
        $this->_pdo = $pdo;
    }

    /**
     * check if supplied id is a valid login id
     *
     * @param integer $id the login id
     * @return boolean
     */
    public function isIdValid(int $id): bool
    {
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authentication::TABLE);
        $isValid = ($Db->isDataInColumn(__LINE__, $id, 'id')) ? true : false;
        $Db->setTable($table);
        return $isValid;
    }

    /**
     * check if id is for the user type specified
     *
     * @param integer $id the login id
     * @param string $userType user type or privilegede
     * @return boolean
     */
    public function isUserAType(int $id, string $userType): bool
    {
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authentication::TABLE);
        $criteria = [
            ['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id, 'logic' => 'AND'],
            ['column' => 'type', 'comparsion' => '=', 'bindAbleValue' => $userType, 'logic' => 'AND'],
        ];
        $yesItIs = ($Db->select(__LINE__, ['id'], $criteria)) ? true : false;
        $Db->setTable($table);
        return $yesItIs;
    }

    /**
     * set user types in the app
     *
     * @param array $types a collection of user types
     * @return void
     */
    public function setType()
    {
        $table = $this->_db->getTable();
        $this->_db->setTable(Users::USERTYPE_TABLE);
        foreach ($this->_db->select(__LINE__, ['type_name'])  as $aType) {
            $types[] = $aType['type_name'];
        }
        $this->_db->setTable($table);
        Users::$types = $types;
    }

    /**
     * get an array of the user types
     *
     * @return array
     */
    public function getType(): array
    {
        $this->setType();
        return Users::$types;
    }

    /**
     * get an array of the user types info
     *
     * @return array
     */
    public function getTypeInfo(): array
    {
        $table = $this->_db->getTable();
        $this->_db->setTable(Users::USERTYPE_TABLE);
        $typesInfo = [];
        foreach ($this->_db->select(__LINE__)  as $aType) {
            $typesInfo[$aType['type_name']] = $aType;
            $typesInfo[$aType['id']] = $aType;
        }
        $this->_db->setTable($table);
        return $typesInfo;
    }

    /**
     * set array of user types table usertype=>usertypeTable
     *
     * @param array $types user types table usertype=>usertypeTable
     * @return void
     */
    public function setUserTypeTable()
    {
        $table = $this->_db->getTable();
        $this->_db->setTable(Users::USERTYPE_TABLE);
        foreach ($this->_db->select(__LINE__, ['table_name'])  as $aType) {
            $types[] = $aType['table_name'];
        }
        $this->_db->setTable($table);
        Users::$userTypeTable = $types;
    }

    /**
     * get array of user types table usertype=>usertypeTable
     *
     * @return array
     */
    public function getUserTypeTable(): array
    {
        $this->setUserTypeTable();
        return Users::$userTypeTable;
    }

    /**
     * get some login ids that matches the criteria specified
     *
     * @param array $criteria the search criteria [col=>val,...]
     * @param integer|null $limit the max no of ids to be get
     * @param integer|null $start the position of the first id
     * @param bool $latest if true the order is DESC
     * @return array
     */
    public function getIds(array $criteria = [], int $limit = null, int $start = null, bool $latest = true): array
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
        $Db->setTable(Authentication::TABLE);

        if ($idCollection = $Db->select(__LINE__, ['id'], $where, ['id' => $order], $limitClause)) {
            foreach ($idCollection as $anId) $ids[] = $anId['id'];
        }

        $Db->setTable($table);
        return $ids;
    }

    /**
     * for creation of users that can login into the app
     *
     * @param array $data user info minimum data are [email/phone/username, password]
     * @param boolean $addToUserTypeTble add the new user to his/her user type table
     * @param boolean $addToProfileTble add the new user to profile table
     * @return int newly created user id
     */
    public function create(array $data, $addToUserTypeTbl = false, $addToProfileTble = false): int
    {
        if ((!isset($data['username']) && !isset($data['email']) && !isset($data['phone'])) || !isset($data['password'])) {
            $message = "";
            if (!isset($data['password']))
                $message .= "password is missing, ";
            if (!isset($data['username']) && !isset($data['email']) && !isset($data['phone']))
                $message .= "either username, email or phone must be supplied, ";
            $message = rtrim($message, ", ");
            throw new UsersException($message);
        }
        if (isset($data['user_type']) && !in_array($data['user_type'], $this->getType())) {
            throw new UsersException("invalid user type");
        }

        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authentication::TABLE);

        $columns = [];
        foreach ($data as $key => $value) {
            if ($key == 'reset_token_time' || $key == 'activation_token_time' || $key == 'created_at') {
                $isFunction = false;
                $isBindAble = true;
                if (substr(trim($value), -2) == '()') {
                    $isFunction = true;
                    $isBindAble = false;
                }
                $columns[$key] = ['colValue' => $value, 'isFunction' => $isFunction, 'isBindAble' => $isBindAble];
            } else {
                if ($key == 'password') {
                    $columns[$key] = [
                        'colValue' => password_hash($value, PASSWORD_DEFAULT), 'isFunction' => false,
                        'isBindAble' => true
                    ];
                } else {
                    $columns[$key] = ['colValue' => $value, 'isFunction' => false, 'isBindAble' => true];
                }
            }
        }
        if (!isset($columns['activation_token_time'])) {
            $columns['activation_token_time'] = ['colValue' => 'NOW()', 'isFunction' => true, 'isBindAble' => false];
        }
        if (!isset($columns['activation_token'])) {
            $activationToken = implode("", Functions::asciiCollection(16));
            $columns['activation_token'] = ['colValue' => $activationToken, 'isFunction' => false, 'isBindAble' => true];
        }
        if (isset($columns['user_type'])) unset($columns['user_type']);
        $newUser = $Db->insert(__LINE__, $columns);

        if ($addToProfileTble) {
            if (Users::TABLE_PROFILE) {
                if (isset($data['user_type'])) {
                    $profileId = $this->addToUserProfileTable($newUser['lastInsertId'], Users::PROFILE_IMG, $data['user_type']);
                } else {
                    $profileId = $this->addToUserProfileTable($newUser['lastInsertId'], Users::PROFILE_IMG);
                }
            }
        }

        if ($addToUserTypeTbl) {
            $type = isset($data['user_type']) ? $data['user_type'] : $this->getType()[0];
            $this->addToUserTypeTable($profileId, $type);
        }

        $table = $Db->setTable($table);
        return $newUser['lastInsertId'];
    }

    /**
     * for adding a new user record to his/her type table
     *
     * @param integer $id the login id
     * @param string $type the user type
     * @return integer
     */
    public function addToUserTypeTable(int $id, string $type): int
    {
        if (!in_array($type, $this->getType())) throw new UsersException("invalid user type");

        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable($this->getTypeInfo()[$type]['table_name']);

        $columns = ['profile_id' => ['colValue' => $id, 'isFunction' => false, 'isBindAble' => true]];
        $newUser = $Db->insert(__LINE__, $columns);

        $table = $Db->setTable($table);
        return $newUser['lastInsertId'];
    }

    /**
     * for adding a new user record to profile table
     *
     * @param integer $id the login id
     * @param string $profileImg the profile image of the user
     * @param string $type the user type
     * @return integer
     */
    public function addToUserProfileTable(int $id, string $profileImg = Users::PROFILE_IMG, string $type = null): int
    {
        if (!Users::TABLE_PROFILE) throw new UsersException("no user profile table");

        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Users::TABLE_PROFILE);

        $columns = [
            'login_id' => ['colValue' => $id, 'isFunction' => false, 'isBindAble' => true],
            'profile_image' => ['colValue' => $profileImg, 'isFunction' => false, 'isBindAble' => true]
        ];
        if ($type) {
            $userTypeId = $this->getTypeInfo()[$type]['id'];
            $columns['user_type'] = ['colValue' => $userTypeId, 'isFunction' => false, 'isBindAble' => true];
        }
        $newUser = $Db->insert(__LINE__, $columns);

        $Db->setTable($table);
        return $newUser['lastInsertId'];
    }

    /**
     * make changes to user info in login table
     *
     * @param array $data [col=>val] the login user details
     * @param integer $id the login id
     * @return void
     */
    public function update(array $data, int $id): void
    {
        if (!$this->isIdValid($id)) throw new UsersException("invalid user id");

        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authentication::TABLE);

        $columns = [];
        foreach ($data as $key => $value) {
            if ($key == 'reset_token_time' || $key == 'activation_token_time' || $key == 'created_at') {
                $isFunction = false;
                $isBindAble = true;
                if (substr(trim($value), -2) == '()') {
                    $isFunction = true;
                    $isBindAble = false;
                }
                $columns[$key] = ['colValue' => $value, 'isFunction' => $isFunction, 'isBindAble' => $isBindAble];
            } else {
                if ($key == 'password') {
                    $columns[$key] = [
                        'colValue' => password_hash($value, PASSWORD_DEFAULT), 'isFunction' => false,
                        'isBindAble' => true
                    ];
                } else {
                    $columns[$key] = ['colValue' => $value, 'isFunction' => false, 'isBindAble' => true];
                }
            }
        }

        $where = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];

        $Db->update(__LINE__, $columns, $where);
        $table = $Db->setTable($table);
    }

    /**
     * for deleting users
     *
     * @param integer $id login id of the user 
     * @return void
     */
    public function delete(int $id): void
    {
        if (!$this->isIdValid($id)) throw new UsersException("invalid user id");

        $userInfo = $this->getInfo($id);
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authentication::TABLE);

        $criteria = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        $Db->delete(__LINE__, $criteria);

        $defaulImg = [
            Users::PROFILE_IMG,
            Users::PROFILE_IMG_FEMALE,
            Users::PROFILE_IMG_MALE
        ];
        if (
            isset($userInfo[Users::TABLE_PROFILE]['profile_image'])
            && !in_array($userInfo[Users::TABLE_PROFILE]['profile_image'], $defaulImg)
        ) {
            unlink(Users::PROFILE_IMG_PATHBACKEND . $userInfo[Users::TABLE_PROFILE]['profile_image']);
        }

        $Db->setTable($table);
    }

    /**
     * used to retrieve a user details
     *
     * @param integer $id login id of the user 
     * @return array
     */
    public function getInfo(int $id): array
    {
        if (!$this->isIdValid($id)) throw new UsersException("invalid user id");

        $info = [];
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authentication::TABLE);
        $criteria = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        $info[Authentication::TABLE] = $Db->select(__LINE__, [], $criteria)[0];

        $Db->setTable(Users::TABLE_PROFILE);
        $criteria = [[
            'column' => 'login_id', 'comparsion' => '=',
            'bindAbleValue' => $info[Authentication::TABLE]['id']
        ]];
        if ($tblInfo = $Db->select(__LINE__, [], $criteria)) {
            $info[Users::TABLE_PROFILE] = $tblInfo[0];
        }

        $Db->setTable(Users::USERTYPE_TABLE);
        $criteria = [[
            'column' => 'id', 'comparsion' => '=',
            'bindAbleValue' => $info[Users::TABLE_PROFILE][Users::USERTYPE_TABLE]
        ]];
        if ($tblInfo = $Db->select(__LINE__, [], $criteria)) {
            $info[Users::USERTYPE_TABLE] = $tblInfo[0];
        }

        $Db->setTable($info[Users::USERTYPE_TABLE]['table_name']);
        $criteria = [[
            'column' => 'profile_id', 'comparsion' => '=',
            'bindAbleValue' => $info[Users::TABLE_PROFILE]['id']
        ]];
        if ($tblInfo = $Db->select(__LINE__, [], $criteria)) {
            $info[$info[Users::USERTYPE_TABLE]['type_name']] = $tblInfo[0];
        }

        $Db->setTable($table);
        return $info;
    }

    /**
     * Get login id from a login field
     * @param string $string $loginFieldValue the login field value
     * @param string $loginField the login field name
     * @return integer the login id or Functions::NO_INT_VALUE when no such data
     */
    public function getIdFrmLoginField(string $loginFieldValue, string $loginField = Authentication::LOGIN_FIELDS[0]): int
    {
        $id = Functions::NO_INT_VALUE;
        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authentication::TABLE);
        $criteria = [['column' => $loginField, 'comparsion' => '=', 'bindAbleValue' => $loginFieldValue]];
        if ($info = $Db->select(__LINE__, ['id'], $criteria)) {
            $id = $info[0]['id'];
        }
        $Db->setTable($table);
        return $id;
    }

    /**
     * Get a user login field from login id
     * @param integer $id the id of the user
     * @param string $loginField the login field name
     * @return string $loginField the login field
     */
    public function getLoginFieldFrmId($id, string $loginField = Authentication::LOGIN_FIELDS[0]): string
    {
        if (!$this->isIdValid($id)) throw new UsersException("invalid user id");

        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authentication::TABLE);
        $criteria = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        if ($info = $Db->select(__LINE__, [$loginField], $criteria)) {
            $loginFieldValue = $info[0][$loginField];
        }
        $Db->setTable($table);
        return $loginFieldValue;
    }

    /**
     * Use to change user status between active and inactive
     * @param int $id the login id of the user
     * @param string $status either active or inactive
     * @param string $sendMail if true user is notify via mail about activation
     * @return void
     */
    public function changeUserStatus(int $id, $status, $sendMail = false)
    {
        if (!$this->isIdValid($id)) throw new UsersException("invalid user id");
        if (!in_array($status, ['active', 'inactive'])) throw new UsersException("invalid status");

        $Db = $this->_db;
        $table = $Db->getTable();
        $Db->setTable(Authentication::TABLE);
        $column = ['status' => ['colValue' => $status, 'isFunction' => false, 'isBindAble' => true]];
        $criteria = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        $Db->update(__LINE__, $column, $criteria);
        $Db->setTable($table);

        if ($sendMail) {
            $userInfo = $this->getInfo($id);
            $Notification = new Notification();

            $content = "<p style='margin-bottom:20px;'>Good Day Sir/Madam</p>";
            if ($status == 'active') {
                $subject = "Account Activation";
                $content .= "
                    <p style='margin-bottom:8px;'>
						Congratulation your account on " . SITENAME . " has been activated. You can now
						<a href='" . URLBACKEND . "' style='color:#fff; text-decoration:underline;'>login</a>, in
						case you have forgotten your password please just do a password reset by clicking
						on 'forgot password'
                    </p>
					";
            } else {
                $subject = "Account Deactivation";
                $content .= "
                    <p style='margin-bottom:8px;'>
                        This is to inform you that your account on " . SITENAME . " has been deactivated.
                    </p>";
            }

            $Notification->sendMail(['to' => [$userInfo['login']['email']]], $subject, $content);
        }
    }

    /**
     * change a user reset token by generating a new one for him/her
     *
     * @param integer $id the login id of the user
     * @return string the new token
     */
    public function changeResetToken(int $id): string
    {
        if (!$this->isIdValid($id)) throw new UsersException("invalid user id");

        $token = implode("", Functions::asciiCollection(16));
        $Db = $this->_db;
        $table = $Db->getTable();

        $Db->setTable(Authentication::TABLE);
        $column = [
            'reset_token' => ['colValue' => $token, 'isFunction' => false, 'isBindAble' => true],
            'reset_token_time' => ['colValue' => "NOW()", 'isFunction' => true, 'isBindAble' => false]
        ];
        $criteria = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        $Db->update(__LINE__, $column, $criteria);
        $Db->setTable($table);

        $Db->setTable($table);
        return $token;
    }

    /**
     * send the token to user
     * @param integer $id the login id of the user
     * @param bool $viaMail if true user is notify via mail
     * @param bool $viaSMS if true user is notify via SMS
     * @return void
     */
    public function sendResetToken(string $id, bool $viaMail = true, bool $viaSMS = false)
    {
        if (!$this->isIdValid($id)) throw new UsersException("invalid user id");
        $userInfo = $this->getInfo($id);
        $token = implode("", Functions::asciiCollection(16));
        $Db = $this->_db;
        $oldTable = $Db->getTable();
        $Db->setTable(Authentication::TABLE);
        $columns = [
            'reset_token' => ['colValue' => $token, 'isFunction' => false, 'isBindAble' => true],
            'reset_token_time' => ['colValue' => 'NOW()', 'isFunction' => true, 'isBindAble' => false]
        ];
        $criteria = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
        $Db->update(__LINE__, $columns, $criteria);

        if ($viaMail) {
            $loginFieldName = 'email';
            $loginFieldValue = $userInfo[Authentication::TABLE][$loginFieldName];
            $email = $loginFieldValue;
            $urlbackend = Users::PW_RESET_URLBACKEND . "?token=" . urlencode($token) . "&{$loginFieldName}=" . urlencode($loginFieldValue);
            $tokenURLBACKEND = "
				<a style='color:#fff; text-decoration:underline;' href='$urlbackend'>
					" . Users::PW_RESET_URLBACKEND . "?token=$token&{$loginFieldName}=$loginFieldValue
				</a>";

            $Notification = new Notification();
            $content = "
				<p style='margin-bottom:20px;'>Good Day Sir/Madam</p>
				<p style='margin-bottom:8px;'>
					You are getting this mail because you requested to change your password on " . SITENAME . " You will
					need to click on the link below or visit the link by copying and pasting it in your browser and hit enter.
                    <em>if you did not make this request please contact us as soon as possible</em>
					<br/>                    
					$tokenURLBACKEND
				</p>
			";
            $Notification->sendMail(['to' => [$email]], "Password Reset Request", $content);
        }

        if ($viaSMS) {
            $Notification = new Notification();
            $phone = $userInfo[Authentication::TABLE]['phone'];
            $content = "Your password reset token is $token";
            $Notification->sendSMS([$phone], $content);
        }

        $Db->setTable($oldTable);
    }

    /**
     * check if the reset code provided is valid
     * @param integer $id the login id of the user
     * @param string $token the reset code to be checked
     * @param int|null $time time in seconds for which the token is valid
     * @return boolean
     */
    public function isResetTokenValid(int $id, string $token, int $time = null): bool
    {
        if (!$this->isIdValid($id)) throw new UsersException("invalid user id");

        $isTokenValid = false;
        $Db = $this->_db;
        $table = $Db->getTable();

        $Db->setTable(Authentication::TABLE);
        $criteria = [
            ['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id, 'logic' => 'AND'],
            ['column' => 'reset_token', 'comparsion' => '=', 'bindAbleValue' => $token],
        ];
        $tokenInfo = $Db->select(__LINE__, ['reset_token_time'], $criteria);
        if ($time) {
            $interval = time() - strtotime($tokenInfo[0]['reset_token_time']);
            $isTokenValid = ($interval <= $time) ? true : false;
        } else {
            $isTokenValid = ($tokenInfo) ? true : false;
        }

        $Db->setTable($table);
        return $isTokenValid;
    }

    /**
     * use for changing user's password
     *
     * @param integer $id the login id of the user
     * @param string $password the new password
     * @param string|null $token password reset token if null then reset token is not checked before changing password
     * @param array $notify ['EMAIL', 'SMS'] if empty array no notification is sent out
     * @return void
     */
    public function resetPassword(int $id, string $password, string $token = null, array $notify = [])
    {
        if (!$this->isIdValid($id)) throw new UsersException("invalid user id");

        $Db = $this->_db;
        $oldTable = $Db->getTable();
        $Db->setTable(Authentication::TABLE);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sendNote = false;
        if ($token) {
            if ($this->isResetTokenValid($id, $token)) {
                $columns = [
                    'password' => ['colValue' => $password, 'isFunction' => false, 'isBindAble' => true]
                ];
                $criteria = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
                $Db->update(__LINE__, $columns, $criteria);
                $sendNote = true;
            }
        } else {
            $columns = [
                'password' => ['colValue' => $password, 'isFunction' => false, 'isBindAble' => true]
            ];
            $criteria = [['column' => 'id', 'comparsion' => '=', 'bindAbleValue' => $id]];
            $Db->update(__LINE__, $columns, $criteria);
            $sendNote = true;
        }

        if ($sendNote) {
            $userInfo = $this->getInfo($id);

            if (in_array('EMAIL', $notify)) {
                $Notification = new Notification();
                $email = $userInfo[Authentication::TABLE]['email'];
                $content = "
				<p style='margin-bottom:20px;'>Good Day Sir/Madam </p>
				<p style='margin-bottom:8px;'>
					This is to notify you that your password on " . SITENAME . " has been changed.
                    <em>if you did not make this change please contact us as soon as possible</em>										
				</p>
			";
                $Notification->sendMail(['to' => [$email]], "Password Change: SUCCESSFUL", $content);
            }

            if (in_array('SMS', $notify)) {
                $Notification = new Notification();
                $phone = $userInfo[Authentication::TABLE]['phone'];
                $content = "This is to notify you that your password on " . SITENAME . " has been changed.";
                $Notification->sendSMS([$phone], $content);
            }
        }

        $Db->setTable($oldTable);
    }
}