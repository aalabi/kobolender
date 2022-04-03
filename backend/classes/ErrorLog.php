<?php

/**
 * ErrorLog
 * 
 * A class for error logging
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   Alabian Solutions Limited
 * @version 	1.0 => August 2021
 * @link        alabiansolutions.com
 */

class ErrorLog
{
    /** @var boolean send mail if set to true */
    public $_sentMail = true;

    /** @var boolean send SMS if set to true */
    public $_sendSMS = false;

    /** @var boolean log error message if set to true */
    public $_logMessage = true;

    /** @var  array a collection of email to be notify */
    private const NOTIFIER_EMAILS = [];

    /** @var  array a collection of phone no to be notify */
    private const NOTIFIER_PHONE_NOS = [];

    /** @var  string directory location for error log file */
    private const DIRECTORY  = PATHBACKEND . "error/";

    /** @var  string file for error log */
    private const FILE = "error.log";

    /** @var  string error message that occur */
    private $_message;

    /** @var  string filename in which the error occur */
    private $_file;

    /** @var  int the line no where the error occur */
    private $_line;

    /**
     * instantiation of ErrorLog
     *
     * @param string $message the error message
     * @param string $file the file where the error occurred
     * @param int $line the line no where the error occurred
     */
    public function __construct(string $message, string $file, int $line)
    {
        $this->_message = $message;
        $this->_file = $file;
        $this->_line = $line;
        $this->_logMessage ? $this->log() : null;
        $this->_sendSMS ? $this->sendSMS() : null;
        $this->_sentMail ? $this->sentMail() : null;
        if (DEVELOPMENT) echo $this->_message;
    }

    /**
     * write the error message to file
     *
     * @return void
     */
    private function log()
    {
        $handle = fopen(ErrorLog::DIRECTORY . ErrorLog::FILE, "a+");
        $message = "{$this->_message}\t {$this->_line}\t {$this->_file}\t " . date("Y-m-d h:ia") . "\n";
        fputs($handle, $message);
        fclose($handle);
    }

    /**
     * Form the error message to be sent out
     * @return string $message the error message to be sent out
     */
    private function setEmailMsg(): string
    {
        $error = $this->_message;
        $message = "
            <p style='margin-bottom:10px; margin-top:10px;'>Good Day Admin</p>
            <p style='margin-bottom:10px;'>
                This is to inform you that something went wrong on " . SITENAME . ". This error has 
                been log to file on server, which you can review anytime.  
            </p>
            <p style='margin-bottom:60px;'>
            <strong>Error Message</strong><br/>
            $error<br/>
            {$_SERVER['REMOTE_ADDR']}<br/>
            {$_SERVER['HTTP_USER_AGENT']}<br/>"
            . date('l F jS, Y - g:ia') . "<br/>
        ";
        return $message;
    }

    /**
     * send the error message to some emails
     *
     * @return void
     */
    private function sentMail()
    {
        if (ErrorLog::NOTIFIER_EMAILS) {
            $Notification = new Notification();
            $subject = SITENAME . " System Error";
            foreach (ErrorLog::NOTIFIER_EMAILS as $anEmail) {
                $Notification->sendMail(['to' => [$anEmail]], $subject, $this->setEmailMsg());
            }
        }
    }

    /**
     * send the error message to some phones
     *
     * @return void
     */
    private function sendSMS()
    {
        if (ErrorLog::NOTIFIER_PHONE_NOS) {
            foreach (ErrorLog::NOTIFIER_PHONE_NOS as $aPhoneNo) {
                $Notification = new Notification();
                $message = "System error occurred on " . SITENAME . " by " . date('l F jS, Y - g:ia') . "check error log for more details";
                $Notification->sendSMS([$aPhoneNo], $message);
            }
        }
    }
}
