<?php

/**
 * Notification
 *
 * This class is used for user interaction
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   2021 Alabian Solutions Limited
 * @link        alabiansolutions.com
 */

class NotificationException extends Exception
{
}

class Notification
{
    /** @var  PDO an instance of PDO type */
    private $_pdo;

    /** @var  int in-built php mail function */
    public const MAIL = 1;

    /** @var  int php mailer class */
    public const PHPMAILER = 2;

    /** @var  array mail method collection */
    private const MAIL_COLLECTION = [Notification::MAIL, Notification::PHPMAILER];

    /** @var  int php mailer class */
    public const SMSAPI_1 = 1;

    /** @var  array mail method collection */
    private const SMSAPI_COLLECTION = [Notification::SMSAPI_1];

    /**
     * Setup up Notification
     */
    public function __construct()
    {
    }

    /**
     * for sending of email
     *
     * @param array $emails ['to'=>[name=>email,...],'from'=>[],'cc'=>[],'bcc'=>[], 'reply-to'=>[]]
     * @param  string $subject the subject of the mail
     * @param string $body the body of the mail can in HTML format
     * @param int $mailMethod the mail functionality to be used
     * @return void
     */
    public function sendMail(array $emails, string $subject, string $body, int $mailMethod = Notification::MAIL)
    {
        if (!in_array($mailMethod, Notification::MAIL_COLLECTION))
            throw new NotificationException("invalid mail method");
        $exceptions = [];
        if (!isset($emails['to'])) $exceptions[] = "'to' key missing in 1st parameter";
        if (isset($emails['to']) && !is_array($emails['to']))
            $exceptions[] = "'to' key must be an array in 1st parameter";
        if (isset($emails['from']) && !is_array($emails['from']))
            $exceptions[] = "'from' key must be an array in 1st parameter";
        if (isset($emails['cc']) && !is_array($emails['cc']))
            $exceptions[] = "'cc' key must be an array in 1st parameter";
        if (isset($emails['bcc']) && !is_array($emails['bcc']))
            $exceptions[] = "'bcc' key must be an array in 1st parameter";
        if (isset($emails['reply-to']) && !is_array($emails['bcc']))
            $exceptions[] = "'reply-to' key must be an array in 1st parameter";
        if ($exceptions) {
            $exceptionMsg = implode(", ", $exceptions);
            $exceptionMsg = rtrim($exceptionMsg, ", ");
            throw new NotificationException($exceptionMsg);
        }

        if ($mailMethod == Notification::MAIL) {
            $to = "";
            foreach ($emails['to'] as $name => $address) {
                $to .= !is_numeric($name) ? "$name <$address>, " : "$address, ";
            }
            $to = rtrim($to, ", ");
            $receipents = $emails['to'];
            unset($emails['to']);

            $headers[] = 'MIME-Version: 1.0';
            $headers[] = 'Content-Type: text/html; charset=utf-8';
            foreach ($emails as $anEmailKey => $emailArray) {
                if ($emailArray) $headers[] = $this->getAdditionalHeader($anEmailKey, $emailArray);
            }

            $from = "";
            if (isset($emails['from']) && !array_keys($emails['from'], 0)) {
                foreach ($emails['from'] as $key => $value) $from = $key;
            }
            $body = $this->bodyContentHead() . $body . $this->bodyContentFooter($from);
            if (!DEVELOPMENT) {
                mail($to, $subject, $body, implode("\r\n", $headers));
            } else {
                foreach ($receipents as $aReceipent)
                    QuasiInbox::mailQuasiInbox($aReceipent, $subject, $body);
            }
        }

        if ($mailMethod == Notification::PHPMAILER) {
            //TODO mail code
        }
    }

    /**
     * generation of php mail additional header parameter
     *
     * @param string $key
     * @param array $array
     * @return string
     */
    private function getAdditionalHeader(string $key, array $array): string
    {
        $header = ($key == 'reply-to') ? "Reply-To : " : ucfirst($key) . ": ";
        foreach ($array as $name => $address) {
            $header .= !is_numeric($name) ? "$name <$address>, " : "$address, ";
        }
        $header = rtrim($header, ", ",);
        return $header;
    }

    /**
     * Simply generate the head part of the HTML string for generation of email template
     * @param string $logoURLBACKEND the urlbackend to the logo of the site
     * @return string
     */
    private function bodyContentHead(string $logoURLBACKEND = ""): string
    {
        $logoURLBACKEND = $logoURLBACKEND ? $logoURLBACKEND :  Functions::ASSET_IMG_URLBACKEND . Functions::LOGO;
        $headEmail = "
            <html>
                <head>
                    <title></title>
                </head>
                <body>
                    <div style='width:88%; color: #fff; padding:0px 0 15px 0; background-color: #222;'>
                        <div style='
                            background-color: #fefefe;
                            border-bottom:2px solid #fbd602;
                            padding:7px 1% 7px;
                            margin-bottom:15px;
                            text-align: center;
                            '>
                                <div style='float:left;'>
                                    <img src='$logoURLBACKEND' style='height:58px; width:64px;'/>
                                </div>
                                <div style='clear:both;'>
                        </div>
                        <a href='" . URLBACKEND . "' style='text-decoration:none; color:#0e1d54;'>
                            <span style='
                                color:#415f7a;
                                display: block;
                                font-size:20px;
                                font-size:1.25rem;
                                font-weight: bold;
                                text-decoration: none;
                                text-align: center;
                            '>
                                " . SITENAME . "
                            </span>
                        </a>
                    </div>
                    <div style='padding:5px 1%; color:#fff; font-size:12px; font-family:Arial;'>";
        return $headEmail;
    }

    /**
     * Simply generate the footer part of the HTML string for generation of email template
     * @return string $footerEmail the footer part of the HTML string
     */
    private function bodyContentFooter(string $senderName = ""): string
    {
        $senderName = ($senderName) ? $senderName : "System Admin";
        $footerEmail = "
            </div>
            <div style='margin-bottom:60px; margin-top:30px; padding: 0px 1%;'>
                $senderName<br/>
                <a href='" . URLBACKEND . "' style='color:#f0f0f0;'>For " . SITENAME . "</a>
            </div>
            <div style='font-size:9px; background-color: #fefefe; border:1px solid #fbd602; padding-top:10px; padding-bottom:10px;'>
                <div style='font-size:9px; float:left; color:#999; padding-left:5px' >
                    <a style='color:#999; text-decoration:none;' rel='nofollow' href='https://alabiansolutions.com'>powered by alabian</a>
                </div>
                <div style='font-size:9px; float:right; color:#999; padding-right:5px' >
                    &copy; " . date("Y") . " " . URLBACKEND . "
                </div>
                <div style='clear:both;'></div>
            </div>
            </div>
        </body>
        </html>
        ";
        return $footerEmail;
    }

    /**
     * for sending of SMS
     *
     * @param array $phones the receipents phones
     * @param string $content the SMS content
     * @param int $smsAPI the SMS API to be used
     * @return void
     */
    public function sendSMS(array $phones, string $content, int $smsAPI = Notification::SMSAPI_1)
    {
        if (!in_array($phones, Notification::SMSAPI_COLLECTION))
            throw new NotificationException("invalid SMS API");

        if ($smsAPI == Notification::SMSAPI_1) {
            //TODO mail code
        }
    }
}
