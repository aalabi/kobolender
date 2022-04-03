<?php

/**
 * Authentication
 *
 * This class is used for user login and authentication
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   2021 Alabian Solutions Limited
 * @link        alabiansolutions.com
 */
class Authentication
{
    /** @var  Database an instance of Database type */
    protected $_db;

    /** @var  PDO an instance of PDO type */
    protected $_pdo;

    /** @var  string name of the table holding login details */
    public const TABLE = "login";

    /** @var string the fields used with password for login */
    public const LOGIN_FIELDS = ['email'];

    /** @var  string the name of the session holding the login details */
    public const SESSION_NAME = SITENAME . "_login";

    /** @var  string the name of the session holding the login details */
    public const SESSION_CSRF_TOKEN =  SITENAME . "_token";

    /**
     * Setup up db interaction
     * @param PDO $PDO an instant of PDO
     */
    public function __construct(PDO $PDO)
    {
        $this->_db = new Database(__FILE__, $PDO, 'login');
        $this->_pdo = $PDO;
    }

    /**
     * generate the CSRF token
     *
     * @return string
     */
    public static function getCSRFToken(): string
    {
        if (!isset($_SESSION[Authentication::SESSION_CSRF_TOKEN])) {
            $token = bin2hex(random_bytes(32));
            $_SESSION[Authentication::SESSION_CSRF_TOKEN] = $token;
        } else {
            $token = $_SESSION[Authentication::SESSION_CSRF_TOKEN];
        }
        return $token;
    }

    /**
     * check if the active the CSRF token is ok
     *
     * @param string $token the CSRF token to be checked
     * @return bool
     */
    public static function checkCSRFToken(string $token): bool
    {
        $equal = false;
        $equal = hash_equals($token, Authentication::getCSRFToken());
        return $equal;
    }

    /**
     * generate fingerprint for a login user
     *
     * @param integer $id login id of the user
     * @param string $loginField field used with password for login - email, username or phone
     * @param string $password password of the user
     * @return string
     */
    public function generateFingerPrint(int $id, string $loginField, string $password): string
    {
        $fingerPrint = md5("{$_SERVER['HTTP_USER_AGENT']}{$password}{$id}{$loginField}");
        return $fingerPrint;
    }

    /**
     * Log a user into the app 
     * @param string $loginField field used with password for login - email, username or phone
     * @param string $password password of the person to be login
     * @return boolean $loggedIn true if login is true or false otherwise
     */
    public function loginUser($loginField, $password): bool
    {
        $loggedIn = false;
        $User = new Users($this->_pdo);
        $id = $User->getIdFrmLoginField($loginField);
        try {
            $userInfo = $User->getInfo($id);
            $userDetails = $userInfo['login'];
            if (password_verify($password, $userDetails['password'])) {
                $fingerPrint = $this->generateFingerPrint($userDetails['id'], $loginField, $userDetails['password']);
                $sessionCredentials = ['id' => $userDetails['id'], 'fingerPrint' => $fingerPrint];
                $_SESSION[Authentication::SESSION_NAME] = $sessionCredentials;
                $loggedIn = true;
            }
        } catch (UsersException $ue) {
            $loggedIn = false;
        }
        return $loggedIn;
    }


    /**
     * Log out a login user from the app
     * @param string $redirect the webpage a user is direct after login, if user decide to login
     * @return void
     */
    public function logoutUser(string $logoutPage = ""): void
    {
        session_regenerate_id();
        $_SESSION = array();
        session_destroy();
        header("Location: " . URLBACKEND . "$logoutPage");
        exit();
    }

    /**
     * Check if the user viewing a page is login
     * @return void
     */
    public function keyToPage()
    {
        if (isset($_SESSION[Authentication::SESSION_NAME]['fingerPrint'])) {
            $sessionId = $_SESSION[Authentication::SESSION_NAME]['id'];
            $sessionFingerPrint = $_SESSION[Authentication::SESSION_NAME]['fingerPrint'];
            $User = new Users($this->_pdo);
            try {
                $userInfo = $User->getInfo($sessionId);
                $userDetails = $userInfo['login'];
                if (!$userDetails) {
                    $userDetails['password'] = "";
                    $userDetails['id'] = "";
                    $userDetails['email'] = "";
                }
                $fingerPrint = $this->generateFingerPrint($userDetails['id'], $userDetails['email'], $userDetails['password']);
                if (($sessionFingerPrint != $fingerPrint) || ($userDetails['status'] == 'inactive')) {
                    $this->logoutUser();
                }
            } catch (UsersException $ue) {
                $this->logoutUser();
            }
        } else {
            $this->logoutUser();
        }
    }

    /**
     * Grant user access to pages based on their priviledge
     * @param array $accessorRight collection of permitted priviledge
     * @param string $urRight your priviledge
     * @param string $redirect page to redirect after login
     * @return void
     */
    public function pageAccessor(array $accessorRight, string $urRight, string $redirect = "")
    {
        if (!in_array($urRight, $accessorRight)) {
            $this->logoutUser($redirect);
        }
    }

    /**
     * for generation of sql for creating login and users mgt table
     *
     * @return array
     */
    public function generateUsersTblSQL(): array
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS " . Authentication::TABLE . " (
                id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                username varchar(255) COLLATE utf8_general_ci DEFAULT NULL,
                email varchar(255) COLLATE utf8_general_ci DEFAULT NULL,
                phone varchar(255) COLLATE utf8_general_ci DEFAULT NULL,
                password varchar(255) COLLATE utf8_general_ci NOT NULL,
                reset_token varchar(255) COLLATE utf8_general_ci DEFAULT NULL,
                reset_token_time datetime DEFAULT NULL,
                activation_token varchar(255) COLLATE utf8_general_ci NOT NULL,
                activation_token_time datetime NOT NULL,                
                status enum('inactive','active') COLLATE utf8_general_ci NOT NULL DEFAULT 'inactive',
                created_at datetime DEFAULT CURRENT_TIMESTAMP,
                update_at TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY login_user_name_unique (username),
                UNIQUE KEY login_email_unique (email),
                UNIQUE KEY login_phone_unique (phone)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
        ";
        $sqlCollection[Authentication::TABLE] = $sql;

        if (Users::TABLE_PROFILE) {
            $sql = "
                START TRANSACTION;
                CREATE TABLE IF NOT EXISTS " . Users::TABLE_PROFILE . " (
                    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    login_id bigint(20) UNSIGNED NULL,
                    user_type bigint(20) UNSIGNED NULL,
                    profile_image varchar(255) DEFAULT NULL,
                    created_at datetime DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
                    PRIMARY KEY (id),
                    KEY " . Users::TABLE_PROFILE . "_login_id_foreign (login_id),
                    KEY " . Users::TABLE_PROFILE . "_user_type_foreign (user_type)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ALTER TABLE " . Users::TABLE_PROFILE . "
                ADD CONSTRAINT " . Users::TABLE_PROFILE . "_login_id_foreign FOREIGN KEY (login_id) REFERENCES " . Authentication::TABLE . " (id) ON DELETE CASCADE ON UPDATE CASCADE;
                ALTER TABLE " . Users::TABLE_PROFILE . "
                ADD CONSTRAINT " . Users::TABLE_PROFILE . "_user_type_foreign FOREIGN KEY (user_type) REFERENCES " . Users::USERTYPE_TABLE . " (id) ON DELETE CASCADE ON UPDATE CASCADE;
                COMMIT;
            ";
            $sqlCollection[Users::TABLE_PROFILE] = $sql;
        }

        foreach ((new Users($this->_pdo))->getUserTypeTable() as $user => $aUserTable) {
            $sql = "
                START TRANSACTION;
                CREATE TABLE IF NOT EXISTS $aUserTable (
                    id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                    profile_id bigint(20) UNSIGNED NOT NULL,
                    type varchar(255) DEFAULT NULL,
                    other_info longtext,
                    created_at datetime DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
                    PRIMARY KEY (id),
                    KEY {$aUserTable}_profile_id_foreign (profile_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                ALTER TABLE $aUserTable
                ADD CONSTRAINT {$aUserTable}_profile_id_foreign FOREIGN KEY (profile_id) REFERENCES " . Users::TABLE_PROFILE . " (id) ON DELETE CASCADE ON UPDATE CASCADE;
                COMMIT;
            ";
            $sqlCollection[$aUserTable] = $sql;
        }

        return $sqlCollection;
    }

    /**
     * for generation of sql for creating user type table
     * @param array $useTypeInfo an array containing the user type info
     * @return string
     */
    public function generateUserTypeSQL(array $userTypeInfo): string
    {
        $userTypeData = "";
        foreach ($userTypeInfo as $externalName => $typeInfo) {
            $userTypeData .= "(NULL, '{$typeInfo[0]}', '$externalName', '{$typeInfo[1]}', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP), ";
        }
        $userTypeData = rtrim($userTypeData, ", ");
        $sql = "
            START TRANSACTION;
            CREATE TABLE IF NOT EXISTS " . Users::USERTYPE_TABLE . " (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT , 
                type_name VARCHAR(255) NOT NULL , 
                external_name VARCHAR(255) NOT NULL ,
                table_name VARCHAR(255) NOT NULL , 
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
                updated_at DATETIME on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
                PRIMARY KEY (`id`)
            ) 
            ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;     
            INSERT INTO " . Users::USERTYPE_TABLE . " (id, type_name, external_name, table_name, created_at, updated_at) VALUES
            $userTypeData;
            COMMIT;
        ";
        return $sql;
    }
}
