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
abstract class Tag
{
    /** @var string response card displays in green */
    public const RESPONSE_POSITIVE = "success";

    /** @var string response card displays in red */
    public const RESPONSE_NEGATIVE = "danger";

    /** @var string name for session variable that holds response after form processing */
    public const RESPONSE_SESSION = "responseData_" . SITENAME;

    /** @var  Database an instance of Database */
    protected $_db;

    /** @var  PDO an instance of PDO type */
    protected $_pdo;

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
                    $styles .= "<link rel='stylesheet' href='" . URLBACKEND . "$cssFile'>";
                }
            }
            if (isset($files['js'])) {
                foreach ($files['js'] as $jsFile) {
                    $scripts .= "<script src='" . URLBACKEND . "$jsFile'></script>";
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
			<!DOCTYPE html>
			<html lang='en'>
			<head>
				<!-- Required meta tags -->
				<meta charset='utf-8'>
				<meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
				$metaTags
				<title>$title</title>

				<link rel='icon' href='" . URLBACKEND . "asset/image/favicon.ico' type='image/x-icon' />
				$styles
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
    abstract public function createMastHead(string $page, array $data, int $loginId = null): string;

    /**
     * for creating for menu
     * @param string $activeMenu the active menu item
     * @param int $loginId login user id
     * @return string
     */
    abstract  public function createTopMenu(string $activeMenu = "", int $loginId = null): string;

    /**
     * get a list of top menu
     *
     * @return array an array of the menu items
     */
    abstract protected function topMenuCollection(): array;

    /**
     * get a list of side menu
     *
     * @return array an array of the menu items
     */
    abstract protected function sideMenuCollection(): array;

    /**
     * for creating for menu
     * @param array $activeMenu active menu and sub menu menu=>subMenu
     * @param int $loginId login user id
     * @return string
     */
    abstract public function createSideMenu(array $activeMenu = [], int $loginId = null): string;

    /**
     * Used to create the footer section for js inclusion
     * @param array $js an array that contains js files
     * @return string
     */
    protected function createFooterJS(array $js): string
    {
        $scripts = "";
        if ($js) {
            foreach ($js as $aJsFile) {
                $scripts .= "<script src='$aJsFile'></script>";
            }
        }
        $tag = "				
				$scripts
			";
        return $tag;
    }

    /**
     * Used to create the footer section for developer slogan
     * @return string
     */
    abstract protected function createFooterSlogan(): string;

    /**
     * Used to create the footer section of the document
     * @param string|null $token CSRF token for any form in the form
     * @param array $js an array that contains js files
     * @return string $tag
     */
    abstract public function createFooter(string $token = null, array $js = []): string;

    /**
     * create of response message tag
     * @param string $title the title of the response message
     * @param string $message exact response message
     * @param string $status either POSITIVE|postive or NEGATIVE|negative
     * @return string
     */
    abstract public function responseTag(string $title, string $message, string $status = "POSITIVE"): string;

    /**
     * store data in the response session variable
     *
     * @param string $title title of the message
     * @param array $message a collection of the exact message been store
     * @param string $status either the message is success or danger in nature
     * @return void
     */
    public static function setResponse(string $title, array $message, string $status = Tag::RESPONSE_POSITIVE)
    {
        $_SESSION[Tag::RESPONSE_SESSION] = ['title' => $title, 'messages' => $message, 'status' => $status];
    }

    /**
     * get the data store in the response session variable [title, message, status]
     *
     * @return array
     */
    public static function getResponse(): array
    {
        $response = isset($_SESSION[Tag::RESPONSE_SESSION]) ? $_SESSION[Tag::RESPONSE_SESSION] : [];
        unset($_SESSION[Tag::RESPONSE_SESSION]);
        return $response;
    }

    /**
     * generate the hidden input tag for passage of CSRF token
     *
     * @param string $name the value of the name attribute of the input tag
     * @return string
     */
    public static function getCSRFTokenInputTag(string $name = Authentication::SESSION_CSRF_TOKEN): string
    {
        $tag = "<input name='$name' value=" . Authentication::getCSRFToken() . " type='hidden'/>";
        return $tag;
    }
}
