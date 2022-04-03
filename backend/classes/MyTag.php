<?php

/**
 * Tag
 *
 * This class is used for creation of HTML tags
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   2021 Alabian Solutions Limited
 * @version     1.0 => August 2021
 * @link        alabiansolutions.com
 */
class MyTag extends Tag
{
    /** @var string response card displays in green */
    public const RESPONSE_POSITIVE = "success";

    /** @var string response card displays in red */
    public const RESPONSE_NEGATIVE = "danger";

    /**
     * Setup up MyTag
     * @param PDO $pdo an instant of PDO
     */
    public function __construct(PDO $pdo)
    {
        /* parent::$_db = new Database(__FILE__, $pdo, Authentication::TABLE);
        parent::$_pdo = $pdo; */
        parent::__construct($pdo);
    }

    /**
     * Used to create the head tag
     * @param string $title the page title
     * @param array $files a 2 dimensional ['css'=>[], 'js'=>[]]
     * @param array $otherTag an array that contains other tags needed in the head
     * @return string $tag HTML tags that represent the head tag
     */
    public function createHead($title, $files = null, $otherMetaTags = null): string
    {
        $styles = "";
        $scripts = "";
        $metaTags = "";
        if ($files) {
            if (isset($files['css'])) {
                foreach ($files['css'] as $cssFile) {
                    $styles .= "<link href='$cssFile' rel='stylesheet'>";
                }
            }
            if (isset($files['js'])) {
                foreach ($files['js'] as $jsFile) {
                    $scripts .= "<script src='$jsFile'></script>";
                }
            }
        }
        if ($otherMetaTags) {
            foreach ($otherMetaTags as $aMetaTag) {
                $metaTags .= $aMetaTag;
            }
        }
        $time = DEVELOPMENT ? "?ver=" . time() : "";
        $tag = "
			<head>
				<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
                <!-- Meta, title, CSS, favicons, etc. -->
                <meta charset='utf-8'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                <meta name='viewport' content='width=device-width, initial-scale=1'>                
				$metaTags
				<title>$title</title>

                <!-- Bootstrap -->
                <link href='" . URLBACKEND . "asset/css/bootstrap.min.css' rel='stylesheet'>
                <!-- Font Awesome -->
                <link href='" . URLBACKEND . "asset/css/font-awesome.min.css' rel='stylesheet'>
                <!-- NProgress -->
                <link href='" . URLBACKEND . "asset/css/nprogress.css' rel='stylesheet'>
                $styles
                <!-- Custom Theme Style -->
                <link href='" . URLBACKEND . "asset/css/custom.css{$time}' rel='stylesheet'>
				<link rel='icon' href='" . URLBACKEND . "asset/image/favicon.ico' type='image/x-icon' />				
				$scripts
				<!--
				Developed by Alabian Solutions Limited
				Phone: 08034265103
				Email: info@alabiansolutions.com
				Lead Developer: Alabi A. (facebook.com/alabi.adebayo)
				-->
			</head>
		";
        return $tag;
    }

    /**
     * used for creation of tag used in the masthead
     * 
     * @param string $page page where masthead is place like homepage, otherpage etc
     * @param array $data other information you may want to this method
     * @param int $loginId login user id
     * @return string
     */
    public function createMastHead(string $page, array $data, int $loginId = null): string
    {

        return "";
    }

    /**
     * for creating for menu
     * @param string $activeMenu the active menu item
     * @param int $loginId login user id
     * @return string
     */
    public function createTopMenu(string $activeMenu = "", int $loginId = null): string
    {

        return "";
    }

    /**
     * get a list of top menu
     *
     * @return array an array of the menu items
     */
    protected function topMenuCollection(): array
    {
        return [];
    }

    /**
     * get a list of side menu
     *
     * @param int $loginId login user id
     * @return array an array of the menu items
     */
    protected function sideMenuCollection(int $loginId = null): array
    {
        $Users = new Users($this->_pdo);
        $info = $Users->getInfo($loginId);

        $userType = Users::USERTYPE_TABLE;
        $staff = MyUsers::STAFF['name'];

        $loans = ['link' => '', 'fa' => 'fa-edit'];
        if ($staff == $info[$userType]['type_name']) {
            $type = $info[$staff]['type'];
            if ($type == MyUsers::STAFF_TYPE[0]) $element = URLBACKEND . 'loan-application';
            if ($type == MyUsers::STAFF_TYPE[1]) $element = URLBACKEND . 'loan-wip';
            $loans['sub'] = [
                'application' => $element,
                'approved' => URLBACKEND . 'loan-approved',
                'liquidated' => URLBACKEND . 'loan-liquidated',
                'decline' => URLBACKEND . 'loan-rejected',
            ];
        } else {
            $loans['sub'] = [
                'apply' => URLBACKEND . 'loan-customer-application',
                'my loans' => URLBACKEND . 'loan-customer-my',
            ];
        }

        $home = ($info[$userType]['type_name'] == $staff) ? 'home' : 'home-users';
        $menuItems = [
            'home' => ['link' => URLBACKEND . $home, 'fa' => 'fa-home', 'sub' => []],
            'loans' => $loans,
            'profile' => ['link' => URLBACKEND . 'profile-customer', 'fa' => 'fa-book', 'sub' => []],
            'users' => ['link' => '', 'fa' => 'fa-users', 'sub' => [
                'customers' => URLBACKEND . 'users',
                'staff' => URLBACKEND . 'users-staff'
            ]],
            'blog' => ['link' => '', 'fa' => 'fa-files-o', 'sub' => [
                'create' => URLBACKEND . 'blog-create',
                'list' => URLBACKEND . 'blog-list',
                'comment' => URLBACKEND . 'blog-comment',
                'category' => URLBACKEND . 'blog-category',
            ]],
            'change password' => ['link' => URLBACKEND . 'change-password', 'fa' => 'fa-key', 'sub' => []],
            'logout' => ['link' => URLBACKEND . 'logout.php', 'fa' => 'fa-sign-out', 'sub' => []]
        ];
        return $menuItems;
    }

    /**
     * collection of menu items for each staff type
     *
     * @param string|null $staffType the staff type
     * @return array
     */
    private function staffTypeMenuCollection(?string $staffType): array
    {
        $menuItem = [
            'staff' => [
                'home', 'loans', 'users', 'blog', 'change password', 'logout'
            ],
            'approver' => [
                'home', 'loans', 'users', 'blog', 'change password', 'logout'
            ],
            'reviewer' => [
                'home', 'loans', 'users', 'blog', 'change password', 'logout'
            ],
        ];

        return $menuItem[$staffType];
    }

    /**
     * collection of menu items for individual user type
     *
     * @return array
     */
    private function individualTypeMenuCollection(): array
    {
        $menuItem = [
            'home', 'loans', 'profile', 'change password', 'logout'
        ];
        return $menuItem;
    }

    /**
     * collection of menu items for msme user type
     *
     * @return array
     */
    private function msmeTypeMenuCollection(): array
    {
        $menuItem = [
            'home', 'loans', 'profile', 'change password', 'logout'
        ];
        return $menuItem;
    }

    /**
     * for creating menu
     * @param string $activeMenu the active menu item [menu, submenu]
     * @param int $loginId login user id
     * @return string
     */
    public function createSideMenu(array $activeMenu = [], int $loginId = null): string
    {
        $profileImage = URLBACKEND . "asset/image/profile-image/default-profile.png";
        $menuItem = $loggerName = "";
        $User = new Users($this->_pdo);
        if ($loginId) {
            $userInfo = $User->getInfo($loginId);
            $loggerName = "{$userInfo['profile']['surname']} {$userInfo['profile']['firstname']}";
            $profileImage = URLBACKEND . "asset/image/profile-image/{$userInfo['profile']['profile_image']}";
            $staffType = ($userInfo['user_type']['type_name'] == "staff") ? $userInfo['staff']['type'] : "";

            $menuCollection = $this->sideMenuCollection($loginId);
            if ($userInfo[Users::USERTYPE_TABLE]['type_name'] == MyUsers::STAFF['name'])
                $allYourMenus = $this->staffTypeMenuCollection($userInfo['staff']['type']);
            if ($userInfo[Users::USERTYPE_TABLE]['type_name'] == MyUsers::INDIVIDUAL['name'])
                $allYourMenus = $this->individualTypeMenuCollection($userInfo['individual']['type']);
            if ($userInfo[Users::USERTYPE_TABLE]['type_name'] == MyUsers::MSME['name'])
                $allYourMenus = $this->msmeTypeMenuCollection($userInfo['msme']['type']);
            foreach ($allYourMenus as $anItem) {
                $thisMenu = $menuCollection[$anItem];
                $style = "";
                if ($thisMenu['link']) {
                    $href = "href='{$thisMenu['link']}'";
                    $span = "";
                    $subMenu = "";
                } else {
                    $href = "";
                    $span = "<span class='fa fa-chevron-down'></span>";
                    if ($activeMenu && isset($activeMenu[1]))
                        $style = array_key_exists($activeMenu[1], $thisMenu['sub']) ? "style='display: block'" : "";
                    $subMenu = "<ul class='nav child_menu' $style>";
                    foreach ($thisMenu['sub'] as $subMenuName => $subMenuLink) {
                        $subMenuActive = "";
                        if ($activeMenu && isset($activeMenu[1])) {
                            $subMenuActive = ($subMenuName == $activeMenu[1]) ? "class='current-page'" : "";
                        }
                        $subMenu .= "<li $subMenuActive><a href='$subMenuLink'>" . ucwords($subMenuName) . "</a></li>";
                    }
                    $subMenu .= "</ul>";
                }
                $menuActive = "";
                if ($activeMenu) $menuActive = ($anItem == $activeMenu[0]) ? "class='active'" : "";
                $menuItem .= "
                    <li $menuActive>
                        <a $href>
                            <i class='fa {$thisMenu['fa']}'></i>" . ucfirst($anItem) . "
                            $span
                        </a>
                        $subMenu
                    </li>    
                ";
            }
        }

        $tag = "
            <div class='col-md-3 left_col'>
                <div class='left_col scroll-view'>
                    <div class='navbar nav_title' style='border: 0;'>
                        <a href='" . URL . "' class='site_title'><span>" . SITENAME . "</span></a>
                    </div>
                    <div class='clearfix'></div>

                    <!-- menu profile quick info -->
                    <div class='profile clearfix'>
                        <div class='profile_pic'>
                            <img src='$profileImage' alt='...' class='img-circle profile_img'>
                        </div>
                        <div class='profile_info'>
                            <span>Good Day,</span>
                            <h2>$loggerName<br/><small>$staffType</small></h2>
                        </div>
                        <div class='clearfix'></div>
                    </div>
                    <!-- /menu profile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id='sidebar-menu' class='main_menu_side hidden-print main_menu'>
                        <div class='menu_section'>                            
                            <ul class='nav side-menu'>
                                $menuItem                                
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <div class='sidebar-footer hidden-small'></div>
                    <!-- /menu footer buttons -->
                </div>
            </div>
        ";
        return $tag;
    }

    /**
     * Used to create the footer section for developer slogan
     * @return string
     */
    public function createFooterSlogan(): string
    {
        $tag = "
            <footer>
                <div class='pull-right'>
                    " . SITENAME . " a software built by cognetik technology + alabian solutions</a>
                </div>
                <div class='clearfix'></div>
            </footer>
        ";
        return $tag;
    }

    /**
     * Used to create the footer section of the document
     * @param string|null $token CSRF token for any form in the form
     * @param array $js an array that contains js files
     * @return string $tag
     */
    public function createFooter(string $token = null, array $js = []): string
    {
        $tag = "";
        if ($js) $tag .= parent::createFooterJS($js);
        return $tag;
    }

    /**
     * Used to create the footer section of the document
     * @param string|null $token CSRF token for any form in the form
     * @param array $js an array that contains js files
     * @return string $tag
     */
    public function createFooterJS(array $js = []): string
    {
        return parent::createFooterJS($js);
    }

    /**
     * create of response message tag
     * @param string $title the title of the response message
     * @param string $message exact response message
     * @param string $status either POSITIVE|postive or NEGATIVE|negative
     * @return string
     */
    public function responseTag(string $title, string $message, string $status = MyTag::RESPONSE_POSITIVE): string
    {
        $tag = "
            <div class='x_panel'>
                <div class='x_title'>
                    <h2>$title</h2>
                    <ul class='nav navbar-right panel_toolbox al_panel_toolbox'>
                        <li><a class='collapse-link'><i class='fa fa-chevron-up'></i></a>
                        </li>                        
                        <li><a class='close-link'><i class='fa fa-close'></i></a>
                        </li>
                    </ul>
                    <div class='clearfix'></div>
                </div>
                <div class='x_content bs-example-popovers'>
                    <div class='alert alert-$status alert-dismissible ' role='alert'>
                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span>
                        </button>
                        $message
                    </div>                    
                </div>
            </div>
        ";
        return $tag;
    }
}
