<?php

/**
 * QuasinInbox
 *
 * Handling of Quasi Inbox
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   2021 Alabian Solutions Limited
 * @version     1.0 => August 2021
 * @link        alabiansolutions.com
 */
class QuasiInbox
{
    /** @var string directory for the inbox box where mail are store */
    private const INBOX_DIRECTORY = PATHBACKEND . "inbox/box/";

    /** @var string urlbackend where the inbox can be accessed via the broswer */
    private const INBOX_URLBACKEND = URLBACKEND . "inbox/box/";

    /** @var string filename of the csv file where mail details are saved */
    private const CSV_FILENAME = "inbox.csv";

    /**
     * Send email to Quasi Inbox
     * @param string $recipient the email been sent to
     * @param string  $subject subject of the email
     * @param string $message message been sent
     */
    public static function mailQuasiInbox(string $recipient, string $subject, string $message): void
    {
        static $kanter = 0;
        $mailFilename = ($kanter++) . time() . ".html";
        $emailFile = fopen(self::INBOX_DIRECTORY . $mailFilename, "w");
        fputs($emailFile, $message);
        fclose($emailFile);

        $mailDbFile = fopen(self::INBOX_DIRECTORY . self::CSV_FILENAME, "a");
        $data = [$recipient, $subject, self::INBOX_URLBACKEND . $mailFilename];
        fputcsv($mailDbFile, $data);
        fclose($mailDbFile);
    }

    /**
     * Retrieve the content of Quasi Inbox
     * @param boolean $returnString if true returns a string or array on false
     * @return mixed
     */
    public static function getQuasiInboxContent(bool $returnString = true)
    {
        $inBoxContent = [];
        $mailDbFile = self::INBOX_DIRECTORY . self::CSV_FILENAME;
        if (file_exists($mailDbFile)) {
            if (($mailDbFileHandle = fopen($mailDbFile, "r")) !== false) {
                while (($data = fgetcsv($mailDbFileHandle, 1000, ",")) !== false)
                    $inBoxContent[] = ["recipient" => $data[0], "subject" => $data[1], "urlbackend" => $data[2]];
            }
            fclose($mailDbFileHandle);
        }

        $table = "";
        if ($returnString) {
            $table = "<table border='1'>
                <thead>
                    <tr>
                        <td>S/N</td>
                        <td>Recipient</td>
                        <td>Subject</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>";
            if ($inBoxContent) {
                $sn = 0;
                foreach ($inBoxContent as $anInBoxContent) {
                    $table .= "<tr>
                        <td>" . (++$sn) . "</td>
                        <td>{$anInBoxContent['recipient']}</td>
                        <td>{$anInBoxContent['subject']}</td>
                        <td><a href='{$anInBoxContent['urlbackend']}' target='_blank'>view</a></td>
                    </tr>";
                }
            } else {
                $table .= "
                    <tr>
                        <td colspan='4'>Empty Inbox</td>
                    </tr>";
            }
            $table .= "</tbody></table>";
        }

        $return = ($returnString) ? $table : $inBoxContent;
        return $return;
    }
}
