<?php

/**
 * Functions
 * This class is used to to supply some commonly used functions
 * @author      Alabi A. <alabi.adebayo@alabiansolutions.com>
 * @copyright   2021 Alabian Solutions Limited
 * @version     1.0 => August 2021
 * @link        alabiansolutions.com
 */

class Functions
{

    /** @var int indicate when an integer does not exist */
    public const NO_INT_VALUE = -2147483647;

    /** @var int indicate when an integer does not exist */
    public const INFINITE = 2147483647;

    /** @var string site logo image  */
    public const LOGO = "logo.png";

    /** @var string site favicon image  */
    public const FAVICON = "favicon.ico";

    /** @var string site asset image urlbackend  */
    public const ASSET_IMG_URLBACKEND = URLBACKEND . "asset/image/";

    /** @var string site asset image pathbackend  */
    public const ASSET_IMG_PATHBACKEND = PATHBACKEND . "asset/image/";

    /** @var string site asset css urlbackend  */
    public const ASSET_CSS_URLBACKEND = URLBACKEND . "asset/css/";

    /** @var string site asset css pathbackend  */
    public const ASSET_CSS_PATHBACKEND = PATHBACKEND . "asset/css/";

    /** @var string site asset js urlbackend  */
    public const ASSET_JS_URLBACKEND = URLBACKEND . "asset/js/";

    /** @var string site asset js pathbackend  */
    public const ASSET_JS_PATHBACKEND = PATHBACKEND . "asset/js/";

    public static function magicQuotesOff()
    {
        function stripslashes_deep($value)
        {
            $value = is_array($value) ?
                array_map('stripslashes_deep', $value) :  stripslashes($value);
            return $value;
        }
        $_POST = array_map('stripslashes_deep', $_POST);
        $_GET = array_map('stripslashes_deep', $_GET);
        $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
        $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
    }

    /**
     * Sanitize value passed to it. This help prevent malicious input been passed to your script
     * @param string $var the value to be sanitized
     * @return string $var the value after sanitization
     */

    public static function sanitizeString(string $var): string
    {
        $var = stripslashes($var);
        $var = htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
        $var = strip_tags($var);
        return $var;
    }
    /**
     * Sanitize and echo the string passed to it
     * @param string $var the value to be echo
     */
    public static function sanitizeEchoer(string $var)
    {
        echo self::sanitizeString($var);
    }

    /**
     * Simply sanitize the string passed
     * @param string $var the value to be sanitize
     * @return string $cleanVar the santized string
     */
    public static function sanitizer($var): string
    {
        $cleanVar = self::sanitizeString($var);
        return $cleanVar;
    }

    /**
     * Generate the ASCII code of digits, alphabet upper & case
     * @return array $array an array that contains ASCII of digits, alphabet upper & lower case
     */
    public static function asciiTableDigitalAlphabet(): array
    {
        $array = array();
        //Digitals
        for ($kanter = 48; $kanter <= 57; $kanter++) {
            $array[] = $kanter;
        }
        //Uppercase
        for ($kanter = 65; $kanter <= 90; $kanter++) {
            $array[] = $kanter;
        }
        //Lowercase
        for ($kanter = 97; $kanter <= 122; $kanter++) {
            $array[] = $kanter;
        }
        shuffle($array);
        return $array;
    }

    /**
     * Generate the ASCII code of digits, alphabet upper & case
     * @param array $ASCIIArray an array that contains ASCII Code
     * @param string $dataFormat the format of the return value of array for array or other value for string
     * @return string $characters an array or string that contains character that matches the ASCII Code supplied
     */
    public static function characterFromASCII(array $ASCIIArray, string $dataFormat = 'array'): string
    {
        $max = count($ASCIIArray);
        for ($kanter = 0; $kanter < $max; $kanter++) {
            $array[] = chr($ASCIIArray[$kanter]);
        }
        if ($dataFormat == 'array') {
            $characters = $array;
        } else {
            $characters = "";
            foreach ($array as $anArrayValue) {
                $characters .= $anArrayValue;
            }
        }
        return $characters;
    }

    /**
     * Generate ASCII code
     * 
     * @param int the no of item in the return array
     * @param boolean $onlyDigitAlphabet if true only code of digit, alphabet are return
     * @param array $range an array of start and end of of need ASCII code
     * @param boolean $shuffledIt if true return array is shuffled
     * @param boolean $isChar if true return character other returns the integer code
     * @return array $array an array that contains ASCII code
     */
    public static function asciiCollection(
        int $count = Functions::INFINITE,
        bool $onlyDigitAlphabet = true,
        array $range = [],
        bool $shuffledIt = true,
        bool $isChar = true
    ): array {
        $array = [];
        if ($onlyDigitAlphabet) {
            //Digitals
            for ($kanter = 48; $kanter <= 57; $kanter++) $array[] = $kanter;
            //Uppercase
            for ($kanter = 65; $kanter <= 90; $kanter++) $array[] = $kanter;
            //Lowercase
            for ($kanter = 97; $kanter <= 122; $kanter++) $array[] = $kanter;
        } else if ($range) {
            for ($kanter = $range[0]; $kanter <= $range[1]; $kanter++) $array[] = $kanter;
        } else {
            for ($kanter = 0; $kanter <= 127; $kanter++) $array[] = $kanter;
        }
        if ($shuffledIt) shuffle($array);
        if ($count != Functions::INFINITE) $array = array_slice($array, 0, ($count));
        if ($isChar) {
            $array = array_map(function ($arr) {
                return chr($arr);
            }, $array);
        }
        return $array;
    }



    /**
     * A collection of years from 1940 to present year
     * @return array $years an array of years from 1940 to present year
     */
    public static function yearsCollection(): array
    {
        for ($i = 1940; $i <= date("Y"); $i++) {
            $years[] = $i;
        }
        return $years;
    }

    /**
     * A collection of months in the year
     * @return array $months an array of months in the year
     */
    public static function monthsCollection()
    {
        $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        return $months;
    }

    /**
     * A collection of banks in nigeria
     * @return array $banks an array of banks in nigeria
     */
    public static function banksCollection(): array
    {
        $banks = array(
            "Access Bank", "Citibank", "Diamond Bank", "Ecobank", "Fidelity Bank", "First Bank", "First City Monument Bank",
            "Guaranty Trust Bank", "Heritage Bank Plc", "Keystone Bank", "Polaris Bank", "Stanbic IBTC Bank",
            "Standard Chartered Bank", "Sterling Bank", "Union Bank", "United Bank for Africa",
            "Unity Bank Plc", "Wema Bank", "Zenith Bank", "Jaiz Bank", "Globus Bank", "SunTrust Bank",
            "Providus Bank", "Titan Trust Bank",
        );
        sort($banks);
        return $banks;
    }

    /**
     * A collection of world country's name, intl phone code and abbreviation
     * @param $expectedData either name, phone or abbreviation
     * @return array $country an array of country name or phone code or abbrevation
     */
    public static function countryCollection($expectedData = "name"): array
    {
        $countriesJSON = '[{"name":"Afghanistan","dial_code":"+93","code":"AF"},{"name":"Albania","dial_code":"+355","code":"AL"},{"name":"Algeria","dial_code":"+213","code":"DZ"},{"name":"AmericanSamoa","dial_code":"+1 684","code":"AS"},{"name":"Andorra","dial_code":"+376","code":"AD"},{"name":"Angola","dial_code":"+244","code":"AO"},{"name":"Anguilla","dial_code":"+1 264","code":"AI"},{"name":"Antarctica","dial_code":"+672","code":"AQ"},{"name":"Antigua and Barbuda","dial_code":"+1268","code":"AG"},{"name":"Argentina","dial_code":"+54","code":"AR"},{"name":"Armenia","dial_code":"+374","code":"AM"},{"name":"Aruba","dial_code":"+297","code":"AW"},{"name":"Australia","dial_code":"+61","code":"AU"},{"name":"Austria","dial_code":"+43","code":"AT"},{"name":"Azerbaijan","dial_code":"+994","code":"AZ"},{"name":"Bahamas","dial_code":"+1 242","code":"BS"},{"name":"Bahrain","dial_code":"+973","code":"BH"},{"name":"Bangladesh","dial_code":"+880","code":"BD"},{"name":"Barbados","dial_code":"+1 246","code":"BB"},{"name":"Belarus","dial_code":"+375","code":"BY"},{"name":"Belgium","dial_code":"+32","code":"BE"},{"name":"Belize","dial_code":"+501","code":"BZ"},{"name":"Benin","dial_code":"+229","code":"BJ"},{"name":"Bermuda","dial_code":"+1 441","code":"BM"},{"name":"Bhutan","dial_code":"+975","code":"BT"},{"name":"Bolivia, Plurinational State of","dial_code":"+591","code":"BO"},{"name":"Bosnia and Herzegovina","dial_code":"+387","code":"BA"},{"name":"Botswana","dial_code":"+267","code":"BW"},{"name":"Brazil","dial_code":"+55","code":"BR"},{"name":"British Indian Ocean Territory","dial_code":"+246","code":"IO"},{"name":"Brunei Darussalam","dial_code":"+673","code":"BN"},{"name":"Bulgaria","dial_code":"+359","code":"BG"},{"name":"Burkina Faso","dial_code":"+226","code":"BF"},{"name":"Burundi","dial_code":"+257","code":"BI"},{"name":"Cambodia","dial_code":"+855","code":"KH"},{"name":"Cameroon","dial_code":"+237","code":"CM"},{"name":"Canada","dial_code":"+1","code":"CA"},{"name":"Cape Verde","dial_code":"+238","code":"CV"},{"name":"Cayman Islands","dial_code":"+ 345","code":"KY"},{"name":"Central African Republic","dial_code":"+236","code":"CF"},{"name":"Chad","dial_code":"+235","code":"TD"},{"name":"Chile","dial_code":"+56","code":"CL"},{"name":"China","dial_code":"+86","code":"CN"},{"name":"Christmas Island","dial_code":"+61","code":"CX"},{"name":"Cocos (Keeling) Islands","dial_code":"+61","code":"CC"},{"name":"Colombia","dial_code":"+57","code":"CO"},{"name":"Comoros","dial_code":"+269","code":"KM"},{"name":"Congo","dial_code":"+242","code":"CG"},{"name":"Congo, The Democratic Republic of the","dial_code":"+243","code":"CD"},{"name":"Cook Islands","dial_code":"+682","code":"CK"},{"name":"Costa Rica","dial_code":"+506","code":"CR"},{"name":"Cote d\'Ivoire","dial_code":"+225","code":"CI"},{"name":"Croatia","dial_code":"+385","code":"HR"},{"name":"Cuba","dial_code":"+53","code":"CU"},{"name":"Cyprus","dial_code":"+537","code":"CY"},{"name":"Czech Republic","dial_code":"+420","code":"CZ"},{"name":"Denmark","dial_code":"+45","code":"DK"},{"name":"Djibouti","dial_code":"+253","code":"DJ"},{"name":"Dominica","dial_code":"+1 767","code":"DM"},{"name":"Dominican Republic","dial_code":"+1 849","code":"DO"},{"name":"Ecuador","dial_code":"+593","code":"EC"},{"name":"Egypt","dial_code":"+20","code":"EG"},{"name":"El Salvador","dial_code":"+503","code":"SV"},{"name":"Equatorial Guinea","dial_code":"+240","code":"GQ"},{"name":"Eritrea","dial_code":"+291","code":"ER"},{"name":"Estonia","dial_code":"+372","code":"EE"},{"name":"Ethiopia","dial_code":"+251","code":"ET"},{"name":"Falkland Islands (Malvinas)","dial_code":"+500","code":"FK"},{"name":"Faroe Islands","dial_code":"+298","code":"FO"},{"name":"Fiji","dial_code":"+679","code":"FJ"},{"name":"Finland","dial_code":"+358","code":"FI"},{"name":"France","dial_code":"+33","code":"FR"},{"name":"French Guiana","dial_code":"+594","code":"GF"},{"name":"French Polynesia","dial_code":"+689","code":"PF"},{"name":"Gabon","dial_code":"+241","code":"GA"},{"name":"Gambia","dial_code":"+220","code":"GM"},{"name":"Georgia","dial_code":"+995","code":"GE"},{"name":"Germany","dial_code":"+49","code":"DE"},{"name":"Ghana","dial_code":"+233","code":"GH"},{"name":"Gibraltar","dial_code":"+350","code":"GI"},{"name":"Greece","dial_code":"+30","code":"GR"},{"name":"Greenland","dial_code":"+299","code":"GL"},{"name":"Grenada","dial_code":"+1 473","code":"GD"},{"name":"Guadeloupe","dial_code":"+590","code":"GP"},{"name":"Guam","dial_code":"+1 671","code":"GU"},{"name":"Guatemala","dial_code":"+502","code":"GT"},{"name":"Guernsey","dial_code":"+44","code":"GG"},{"name":"Guinea","dial_code":"+224","code":"GN"},{"name":"Guinea-Bissau","dial_code":"+245","code":"GW"},{"name":"Guyana","dial_code":"+595","code":"GY"},{"name":"Haiti","dial_code":"+509","code":"HT"},{"name":"Holy See (Vatican City State)","dial_code":"+379","code":"VA"},{"name":"Honduras","dial_code":"+504","code":"HN"},{"name":"Hong Kong","dial_code":"+852","code":"HK"},{"name":"Hungary","dial_code":"+36","code":"HU"},{"name":"Iceland","dial_code":"+354","code":"IS"},{"name":"India","dial_code":"+91","code":"IN"},{"name":"Indonesia","dial_code":"+62","code":"ID"},{"name":"Iran, Islamic Republic of","dial_code":"+98","code":"IR"},{"name":"Iraq","dial_code":"+964","code":"IQ"},{"name":"Ireland","dial_code":"+353","code":"IE"},{"name":"Isle of Man","dial_code":"+44","code":"IM"},{"name":"Israel","dial_code":"+972","code":"IL"},{"name":"Italy","dial_code":"+39","code":"IT"},{"name":"Jamaica","dial_code":"+1 876","code":"JM"},{"name":"Japan","dial_code":"+81","code":"JP"},{"name":"Jersey","dial_code":"+44","code":"JE"},{"name":"Jordan","dial_code":"+962","code":"JO"},{"name":"Kazakhstan","dial_code":"+7 7","code":"KZ"},{"name":"Kenya","dial_code":"+254","code":"KE"},{"name":"Kiribati","dial_code":"+686","code":"KI"},{"name":"Korea, Democratic People\'s Republic of","dial_code":"+850","code":"KP"},{"name":"Korea, Republic of","dial_code":"+82","code":"KR"},{"name":"Kuwait","dial_code":"+965","code":"KW"},{"name":"Kyrgyzstan","dial_code":"+996","code":"KG"},{"name":"Lao People\'s Democratic Republic","dial_code":"+856","code":"LA"},{"name":"Latvia","dial_code":"+371","code":"LV"},{"name":"Lebanon","dial_code":"+961","code":"LB"},{"name":"Lesotho","dial_code":"+266","code":"LS"},{"name":"Liberia","dial_code":"+231","code":"LR"},{"name":"Libyan Arab Jamahiriya","dial_code":"+218","code":"LY"},{"name":"Liechtenstein","dial_code":"+423","code":"LI"},{"name":"Lithuania","dial_code":"+370","code":"LT"},{"name":"Luxembourg","dial_code":"+352","code":"LU"},{"name":"Macao","dial_code":"+853","code":"MO"},{"name":"Macedonia, The Former Yugoslav Republic of","dial_code":"+389","code":"MK"},{"name":"Madagascar","dial_code":"+261","code":"MG"},{"name":"Malawi","dial_code":"+265","code":"MW"},{"name":"Malaysia","dial_code":"+60","code":"MY"},{"name":"Maldives","dial_code":"+960","code":"MV"},{"name":"Mali","dial_code":"+223","code":"ML"},{"name":"Malta","dial_code":"+356","code":"MT"},{"name":"Marshall Islands","dial_code":"+692","code":"MH"},{"name":"Martinique","dial_code":"+596","code":"MQ"},{"name":"Mauritania","dial_code":"+222","code":"MR"},{"name":"Mauritius","dial_code":"+230","code":"MU"},{"name":"Mayotte","dial_code":"+262","code":"YT"},{"name":"Mexico","dial_code":"+52","code":"MX"},{"name":"Micronesia, Federated States of","dial_code":"+691","code":"FM"},{"name":"Moldova, Republic of","dial_code":"+373","code":"MD"},{"name":"Monaco","dial_code":"+377","code":"MC"},{"name":"Mongolia","dial_code":"+976","code":"MN"},{"name":"Montenegro","dial_code":"+382","code":"ME"},{"name":"Montserrat","dial_code":"+1664","code":"MS"},{"name":"Morocco","dial_code":"+212","code":"MA"},{"name":"Mozambique","dial_code":"+258","code":"MZ"},{"name":"Myanmar","dial_code":"+95","code":"MM"},{"name":"Namibia","dial_code":"+264","code":"NA"},{"name":"Nauru","dial_code":"+674","code":"NR"},{"name":"Nepal","dial_code":"+977","code":"NP"},{"name":"Netherlands","dial_code":"+31","code":"NL"},{"name":"Netherlands Antilles","dial_code":"+599","code":"AN"},{"name":"New Caledonia","dial_code":"+687","code":"NC"},{"name":"New Zealand","dial_code":"+64","code":"NZ"},{"name":"Nicaragua","dial_code":"+505","code":"NI"},{"name":"Niger","dial_code":"+227","code":"NE"},{"name":"Nigeria","dial_code":"+234","code":"NG"},{"name":"Niue","dial_code":"+683","code":"NU"},{"name":"Norfolk Island","dial_code":"+672","code":"NF"},{"name":"Northern Mariana Islands","dial_code":"+1 670","code":"MP"},{"name":"Norway","dial_code":"+47","code":"NO"},{"name":"Oman","dial_code":"+968","code":"OM"},{"name":"Pakistan","dial_code":"+92","code":"PK"},{"name":"Palau","dial_code":"+680","code":"PW"},{"name":"Palestinian Territory, Occupied","dial_code":"+970","code":"PS"},{"name":"Panama","dial_code":"+507","code":"PA"},{"name":"Papua New Guinea","dial_code":"+675","code":"PG"},{"name":"Paraguay","dial_code":"+595","code":"PY"},{"name":"Peru","dial_code":"+51","code":"PE"},{"name":"Philippines","dial_code":"+63","code":"PH"},{"name":"Pitcairn","dial_code":"+872","code":"PN"},{"name":"Poland","dial_code":"+48","code":"PL"},{"name":"Portugal","dial_code":"+351","code":"PT"},{"name":"Puerto Rico","dial_code":"+1 939","code":"PR"},{"name":"Qatar","dial_code":"+974","code":"QA"},{"name":"Romania","dial_code":"+40","code":"RO"},{"name":"Russia","dial_code":"+7","code":"RU"},{"name":"Rwanda","dial_code":"+250","code":"RW"},{"name":"Réunion","dial_code":"+262","code":"RE"},{"name":"Saint Barthélemy","dial_code":"+590","code":"BL"},{"name":"Saint Helena, Ascension and Tristan Da Cunha","dial_code":"+290","code":"SH"},{"name":"Saint Kitts and Nevis","dial_code":"+1 869","code":"KN"},{"name":"Saint Lucia","dial_code":"+1 758","code":"LC"},{"name":"Saint Martin","dial_code":"+590","code":"MF"},{"name":"Saint Pierre and Miquelon","dial_code":"+508","code":"PM"},{"name":"Saint Vincent and the Grenadines","dial_code":"+1 784","code":"VC"},{"name":"Samoa","dial_code":"+685","code":"WS"},{"name":"San Marino","dial_code":"+378","code":"SM"},{"name":"Sao Tome and Principe","dial_code":"+239","code":"ST"},{"name":"Saudi Arabia","dial_code":"+966","code":"SA"},{"name":"Senegal","dial_code":"+221","code":"SN"},{"name":"Serbia","dial_code":"+381","code":"RS"},{"name":"Seychelles","dial_code":"+248","code":"SC"},{"name":"Sierra Leone","dial_code":"+232","code":"SL"},{"name":"Singapore","dial_code":"+65","code":"SG"},{"name":"Slovakia","dial_code":"+421","code":"SK"},{"name":"Slovenia","dial_code":"+386","code":"SI"},{"name":"Solomon Islands","dial_code":"+677","code":"SB"},{"name":"Somalia","dial_code":"+252","code":"SO"},{"name":"South Africa","dial_code":"+27","code":"ZA"},{"name":"South Georgia and the South Sandwich Islands","dial_code":"+500","code":"GS"},{"name":"Spain","dial_code":"+34","code":"ES"},{"name":"Sri Lanka","dial_code":"+94","code":"LK"},{"name":"Sudan","dial_code":"+249","code":"SD"},{"name":"Suriname","dial_code":"+597","code":"SR"},{"name":"Svalbard and Jan Mayen","dial_code":"+47","code":"SJ"},{"name":"Swaziland","dial_code":"+268","code":"SZ"},{"name":"Sweden","dial_code":"+46","code":"SE"},{"name":"Switzerland","dial_code":"+41","code":"CH"},{"name":"Syrian Arab Republic","dial_code":"+963","code":"SY"},{"name":"Taiwan, Province of China","dial_code":"+886","code":"TW"},{"name":"Tajikistan","dial_code":"+992","code":"TJ"},{"name":"Tanzania, United Republic of","dial_code":"+255","code":"TZ"},{"name":"Thailand","dial_code":"+66","code":"TH"},{"name":"Timor-Leste","dial_code":"+670","code":"TL"},{"name":"Togo","dial_code":"+228","code":"TG"},{"name":"Tokelau","dial_code":"+690","code":"TK"},{"name":"Tonga","dial_code":"+676","code":"TO"},{"name":"Trinidad and Tobago","dial_code":"+1 868","code":"TT"},{"name":"Tunisia","dial_code":"+216","code":"TN"},{"name":"Turkey","dial_code":"+90","code":"TR"},{"name":"Turkmenistan","dial_code":"+993","code":"TM"},{"name":"Turks and Caicos Islands","dial_code":"+1 649","code":"TC"},{"name":"Tuvalu","dial_code":"+688","code":"TV"},{"name":"Uganda","dial_code":"+256","code":"UG"},{"name":"Ukraine","dial_code":"+380","code":"UA"},{"name":"United Arab Emirates","dial_code":"+971","code":"AE"},{"name":"United Kingdom","dial_code":"+44","code":"GB"},{"name":"United States","dial_code":"+1","code":"US"},{"name":"Uruguay","dial_code":"+598","code":"UY"},{"name":"Uzbekistan","dial_code":"+998","code":"UZ"},{"name":"Vanuatu","dial_code":"+678","code":"VU"},{"name":"Venezuela, Bolivarian Republic of","dial_code":"+58","code":"VE"},{"name":"Viet Nam","dial_code":"+84","code":"VN"},{"name":"Virgin Islands, British","dial_code":"+1 284","code":"VG"},{"name":"Virgin Islands, U.S.","dial_code":"+1 340","code":"VI"},{"name":"Wallis and Futuna","dial_code":"+681","code":"WF"},{"name":"Yemen","dial_code":"+967","code":"YE"},{"name":"Zambia","dial_code":"+260","code":"ZM"},{"name":"Zimbabwe","dial_code":"+263","code":"ZW"},{"name":"Åland Islands","dial_code":"+358","code":"AX"}]';
        $countries = [];
        $countriesObject = json_decode($countriesJSON);
        foreach ($countriesObject as $aCountry) {
            if ($expectedData == "name") $countries[] = $aCountry->name;
            if ($expectedData == "phone") $countries[] = $aCountry->dial_code;
            if ($expectedData == "abbreviation") $countries[] = $aCountry->code;
        }
        return $countries;
    }

    /**
     * A collection of states in Nigeria
     * @param boolean withCode true ['LAG'=>'Lagos'] false ['Lagos']
     * @return array $states an array of states
     */
    public static function states($withCode = false): array
    {
        $states = [
            'Abia', 'Abuja', 'Adamawa', 'Akwa Ibom', 'Anambra', 'Bauchi', 'Bayelsa', 'Benue', 'Borno', 'Cross River',
            'Delta', 'Ebonyi', 'Enugu', 'Edo', 'Ekiti', 'Gombe', 'Imo', 'Jigawa', 'Kaduna', 'Kano', 'Katsina',
            'Kebbi', 'Kogi', 'Kwara', 'Lagos', 'Nasarawa', 'Niger', 'Ogun', 'Ondo', 'Osun', 'Oyo', 'Plateau',
            'Rivers', 'Sokoto', 'Taraba', 'Yobe', 'Zamfara'
        ];
        if ($withCode) {
            $states = [
                'ABIA' => 'Abia', 'ABU' => 'Abuja', 'ADA' => 'Adamawa', 'AKW' => 'Akwa Ibom', 'ANA' => 'Anambra', 'BAU' => 'Bauchi',
                'BAY' => 'Bayelsa', 'BEN' => 'Benue', 'BOR' => 'Borno', 'CRO' => 'Cross River', 'DEL' => 'Delta',
                'EBO' => 'Ebonyi', 'ENU' => 'Enugu', 'EDO' => 'Edo', 'EKI' => 'Ekiti', 'GOM' => 'Gombe',
                'IMO' => 'Imo', 'JIG' => 'Jigawa', 'KAD' => 'Kaduna', 'KAN' => 'Kano', 'KAT' => 'Katsina',
                'KEB' => 'Kebbi', 'KOG' => 'Kogi', 'KWA' => 'Kwara', 'LAG' => 'Lagos', 'NAS' => 'Nasarawa',
                'NIG' => 'Niger', 'OGN' => 'Ogun', 'OND' => 'Ondo', 'OSU' => 'Osun', 'OYO' => 'Oyo',
                'PLA' => 'Plateau', 'RIV' => 'Rivers', 'SOK' => 'Sokoto', 'TAR' => 'Taraba', 'YOB' => 'Yobe',
                'ZAM' => 'Zamfara'
            ];
        }
        return $states;
    }

    /**
     * A collection of states and LGA in Nigeria
     * @return array $states an associative array of states and their LGA
     */
    public static function localGovernments(): array
    {
        return [
            "abia" => ["Aba North", "Aba South", "Arochukwu", "Bende", "Ikwuano", "Isiala Ngwa North", "Isiala Ngwa South", "Isuikwuato", "Obi Ngwa", "Ohafia", "Osisioma", "Ugwunagbo", "Ukwa East", "Ukwa West", "Umuahia North", "Umuahia South", "Umu Nneochi"],

            "abuja" => ["Abaji", "Abuja Municipal Area Council", "Bwari", "Gwagwalada", "Kuje", "Kwali"],

            "adamawa" => ["Demsa", "Fufure", "Ganye", "Gayuk", "Gombi", "Grie", "Hong", "Jada", "Lamurde", "Madagali", "Maiha", "Mayo Belwa", "Michika", "Mubi North", "Mubi South", "Numan", "Shelleng", "Song", "Toungo", "Yola North", "Yola South"],

            "akwa ibom" => ["Abak", "Eastern Obolo", "Eket", "Esit Eket", "Essien Udim", "Etim Ekpo", "Etinan", "Ibeno", "Ibesikpo Asutan", "Ibiono-Ibom", "Ika", "Ikono", "Ikot Abasi", "Ikot Ekpene", "Ini", "Itu", "Mbo", "Mkpat-Enin", "Nsit-Atai", "Nsit-Ibom", "Nsit-Ubium", "Obot Akara", "Okobo", "Onna", "Oron", "Oruk Anam", "Udung-Uko", "Ukanafun", "Uruan", "Urue-Offong Oruko", "Uyo"],

            "anambra" => ["Aguata", "Anambra East", "Anambra West", "Anaocha", "Awka North", "Awka South", "Ayamelum", "Dunukofia", "Ekwusigo", "Idemili North", "Idemili South", "Ihiala", "Njikoka", "Nnewi North", "Nnewi South", "Ogbaru", "Onitsha North", "Onitsha South", "Orumba North", "Orumba South", "Oyi", "Aauchi"],

            "bauchi" => ["Alkaleri", "Bauchi", "Bogoro", "Damban", "Darazo", "Dass", "Gamawa", "Ganjuwa", "Giade", "Itas Gadau", "Jama'are", "Katagum", "Kirfi", "Misau", "Ningi", "Shira", "Tafawa Balewa", "Toro", "Warji", "Zaki"],

            "bayelsa" => ["Brass", "Ekeremor", "Kolokuma Opokuma", "Nembe", "Ogbia", "Sagbama", "Southern Ijaw", "Yenagoa"],

            "benue" => ["Agatu", "Apa", "Ado", "Buruku", "Gboko", "Guma", "Gwer East", "Gwer West", "Katsina-Ala", "Konshisha", "Kwande", "Logo", "Makurdi", "Obi", "Ogbadibo", "Ohimini", "Oju", "Okpokwu", "Oturkpo", "Tarka", "Ukum", "Ushongo", "Vandeikya"],

            "borno" => ["Abadam", "Askira Uba", "Bama", "Bayo", "Biu", "Chibok", "Damboa", "Dikwa", "Gubio", "Guzamala", "Gwoza", "Hawul", "Jere", "Kaga", "Kala Balge", "Konduga", "Kukawa", "Kwaya Kusar", "Mafa", "Magumeri", "Maiduguri", "Marte", "Mobbar", "Monguno", "Ngala", "Nganzai", "Shani"],

            "cross River" => ["Abi", "Akamkpa", "Akpabuyo", "Bakassi", "Bekwarra", "Biase", "Boki", "Calabar Municipal", "Calabar South", "Etung", "Ikom", "Obanliku", "Obubra", "Obudu", "Odukpani", "Ogoja", "Yakuur", "Yala"],

            "delta" => ["Aniocha North", "Aniocha South", "Bomadi", "Burutu", "Ethiope East", "Ethiope West", "Ika North East", "Ika South", "Isoko North", "Isoko South", "Ndokwa East", "Ndokwa West", "Okpe", "Oshimili North", "Oshimili South", "Patani", "Sapele", "Udu", "Ughelli North", "Ughelli South", "Ukwuani", "Uvwie", "Warri North", "Warri South", "Warri South West"],

            "ebonyi" => ["Abakaliki", "Afikpo North", "Afikpo South", "Ebonyi", "Ezza North", "Ezza South", "Ikwo", "Ishielu", "Ivo", "Izzi", "Ohaozara", "Ohaukwu", "Onicha"],

            "edo" => ["Akoko-Edo", "Egor", "Esan Central", "Esan North-East", "Esan South-East", "Esan West", "Etsako Central", "Etsako East", "Etsako West", "Igueben", "Ikpoba Okha", "Orhionmwon", "Oredo", "Ovia North-East", "Ovia South-West", "Owan East", "Owan West", "Uhunmwonde"],

            "ekiti" => ["Ado Ekiti", "Efon", "Ekiti East", "Ekiti South-West", "Ekiti West", "Emure", "Gbonyin", "Ido Osi", "Ijero", "Ikere", "Ikole", "Ilejemeje", "Irepodun Ifelodun", "Ise Orun", "Moba", "Oye"],

            "enugu" => ["Aninri", "Awgu", "Enugu East", "Enugu North", "Enugu South", "Ezeagu", "Igbo Etiti", "Igbo Eze North", "Igbo Eze South", "Isi Uzo", "Nkanu East", "Nkanu West", "Nsukka", "Oji River", "Udenu", "Udi", "Uzo Uwani"],

            "fct" => ["Abaji", "Bwari", "Gwagwalada", "Kuje", "Kwali", "Municipal Area Council"],

            "gombe" => ["Akko", "Balanga", "Billiri", "Dukku", "Funakaye", "Gombe", "Kaltungo", "Kwami", "Nafada", "Shongom", "Yamaltu Deba"],

            "imo" => ["Aboh Mbaise", "Ahiazu Mbaise", "Ehime Mbano", "Ezinihitte", "Ideato North", "Ideato South", "Ihitte Uboma", "Ikeduru", "Isiala Mbano", "Isu", "Mbaitoli", "Ngor Okpala", "Njaba", "Nkwerre", "Nwangele", "Obowo", "Oguta", "Ohaji Egbema", "Okigwe", "Orlu", "Orsu", "Oru East", "Oru West", "Owerri Municipal", "Owerri North", "Owerri West", "Unuimo"],

            "jigawa" => ["Auyo", "Babura", "Biriniwa", "Birnin Kudu", "Buji", "Dutse", "Gagarawa", "Garki", "Gumel", "Guri", "Gwaram", "Gwiwa", "Hadejia", "Jahun", "Kafin Hausa", "Kazaure", "Kiri Kasama", "Kiyawa", "Kaugama", "Maigatari", "Malam Madori", "Miga", "Ringim", "Roni", "Sule Tankarkar", "Taura", "Yankwashi"],

            "kaduna" => ["Birnin Gwari", "Chikun", "Giwa", "Igabi", "Ikara", "Jaba", "Jema'a", "Kachia", "Kaduna North", "Kaduna South", "Kagarko", "Kajuru", "Kaura", "Kauru", "Kubau", "Kudan", "Lere", "Makarfi", "Sabon Gari", "Sanga", "Soba", "Zangon Kataf", "Zaria"],

            "kano" => ["Ajingi", "Albasu", "Bagwai", "Bebeji", "Bichi", "Bunkure", "Dala", "Dambatta", "Dawakin Kudu", "Dawakin Tofa", "Doguwa", "Fagge", "Gabasawa", "Garko", "Garun Mallam", "Gaya", "Gezawa", "Gwale", "Gwarzo", "Kabo", "Kano Municipal", "Karaye", "Kibiya", "Kiru", "Kumbotso", "Kunchi", "Kura", "Madobi", "Makoda", "Minjibir", "Nasarawa", "Rano", "Rimin Gado", "Rogo", "Shanono", "Sumaila", "Takai", "Tarauni", "Tofa", "Tsanyawa", "Tudun Wada", "Ungogo", "Warawa", "Wudil"],

            "katsina" => ["Bakori", "Batagarawa", "Batsari", "Baure", "Bindawa", "Charanchi", "Dandume", "Danja", "Dan Musa", "Daura", "Dutsi", "Dutsin Ma", "Faskari", "Funtua", "Ingawa", "Jibia", "Kafur", "Kaita", "Kankara", "Kankia", "Katsina", "Kurfi", "Kusada", "Mai'Adua", "Malumfashi", "Mani", "Mashi", "Matazu", "Musawa", "Rimi", "Sabuwa", "Safana", "Sandamu", "Zango"],

            "kebbi" => ["Aleiro", "Arewa Dandi", "Argungu", "Augie", "Bagudo", "Birnin Kebbi", "Bunza", "Dandi", "Fakai", "Gwandu", "Jega", "Kalgo", "Koko Besse", "Maiyama", "Ngaski", "Sakaba", "Shanga", "Suru", "Wasagu Danko", "Yauri", "Zuru"],

            "kogi" => ["Adavi", "Ajaokuta", "Ankpa", "Bassa", "Dekina", "Ibaji", "Idah", "Igalamela Odolu", "Ijumu", "Kabba Bunu", "Kogi", "Lokoja", "Mopa Muro", "Ofu", "Ogori Magongo", "Okehi", "Okene", "Olamaboro", "Omala", "Yagba East", "Yagba West"],

            "kwara" => ["Asa", "Baruten", "Edu", "Ekiti", "Ifelodun", "Ilorin East", "Ilorin South", "Ilorin West", "Irepodun", "Isin", "Kaiama", "Moro", "Offa", "Oke Ero", "Oyun", "Pategi"],

            "lagos" => ["Agege", "Ajeromi-Ifelodun", "Alimosho", "Amuwo-Odofin", "Apapa", "Badagry", "Epe", "Eti Osa", "Ibeju-Lekki", "Ifako-Ijaiye", "Ikeja", "Ikorodu", "Kosofe", "Lagos Island", "Lagos Mainland", "Mushin", "Ojo", "Oshodi-Isolo", "Shomolu", "Surulere"],

            "nasarawa" => ["Akwanga", "Awe", "Doma", "Karu", "Keana", "Keffi", "Kokona", "Lafia", "Nasarawa", "Nasarawa Egon", "Obi", "Toto", "Wamba"],

            "niger" => ["Agaie", "Agwara", "Bida", "Borgu", "Bosso", "Chanchaga", "Edati", "Gbako", "Gurara", "Katcha", "Kontagora", "Lapai", "Lavun", "Magama", "Mariga", "Mashegu", "Mokwa", "Moya", "Paikoro", "Rafi", "Rijau", "Shiroro", "Suleja", "Tafa", "Wushishi"],

            "ogun" => ["Abeokuta North", "Abeokuta South", "Ado-Odo Ota", "Egbado North", "Egbado South", "Ewekoro", "Ifo", "Ijebu East", "Ijebu North", "Ijebu North East", "Ijebu Ode", "Ikenne", "Imeko Afon", "Ipokia", "Obafemi Owode", "Odeda", "Odogbolu", "Ogun Waterside", "Remo North", "Shagamu"],

            "ondo" => ["Akoko North-East", "Akoko North-West", "Akoko South-West", "Akoko South-East", "Akure North", "Akure South", "Ese Odo", "Idanre", "Ifedore", "Ilaje", "Ile Oluji Okeigbo", "Irele", "Odigbo", "Okitipupa", "Ondo East", "Ondo West", "Ose", "Owo"],

            "osun" => ["Atakunmosa East", "Atakunmosa West", "Aiyedaade", "Aiyedire", "Boluwaduro", "Boripe", "Ede North", "Ede South", "Ife Central", "Ife East", "Ife North", "Ife South", "Egbedore", "Ejigbo", "Ifedayo", "Ifelodun", "Ila", "Ilesa East", "Ilesa West", "Irepodun", "Irewole", "Isokan", "Iwo", "Obokun", "Odo Otin", "Ola Oluwa", "Olorunda", "Oriade", "Orolu", "Osogbo"],

            "oyo" => ["Afijio", "Akinyele", "Atiba", "Atisbo", "Egbeda", "Ibadan North", "Ibadan North-East", "Ibadan North-West", "Ibadan South-East", "Ibadan South-West", "Ibarapa Central", "Ibarapa East", "Ibarapa North", "Ido", "Irepo", "Iseyin", "Itesiwaju", "Iwajowa", "Kajola", "Lagelu", "Ogbomosho North", "Ogbomosho South", "Ogo Oluwa", "Olorunsogo", "Oluyole", "Ona Ara", "Orelope", "Ori Ire", "Oyo", "Oyo East", "Saki East", "Saki West", "Surulere"],

            "plateau" => ["Bokkos", "Barkin Ladi", "Bassa", "Jos East", "Jos North", "Jos South", "Kanam", "Kanke", "Langtang South", "Langtang North", "Mangu", "Mikang", "Pankshin", "Qua'an Pan", "Riyom", "Shendam", "Wase"],

            "rivers" => ["Abua Odual", "Ahoada East", "Ahoada West", "Akuku-Toru", "Andoni", "Asari-Toru", "Bonny", "Degema", "Eleme", "Emuoha", "Etche", "Gokana", "Ikwerre", "Khana", "Obio Akpor", "Ogba Egbema Ndoni", "Ogu Bolo", "Okrika", "Omuma", "Opobo Nkoro", "Oyigbo", "Port Harcourt", "Tai"],

            "sokoto" => ["Binji", "Bodinga", "Dange Shuni", "Gada", "Goronyo", "Gudu", "Gwadabawa", "Illela", "Isa", "Kebbe", "Kware", "Rabah", "Sabon Birni", "Shagari", "Silame", "Sokoto North", "Sokoto South", "Tambuwal", "Tangaza", "Tureta", "Wamako", "Wurno", "Yabo"],

            "taraba" => ["Ardo Kola", "Bali", "Donga", "Gashaka", "Gassol", "Ibi", "Jalingo", "Karim Lamido", "Kumi", "Lau", "Sardauna", "Takum", "Ussa", "Wukari", "Yorro", "Zing"],

            "yobe" => ["Bade", "Bursari", "Damaturu", "Fika", "Fune", "Geidam", "Gujba", "Gulani", "Jakusko", "Karasuwa", "Machina", "Nangere", "Nguru", "Potiskum", "Tarmuwa", "Yunusari", "Yusufari"],

            "zamfara" => ["Anka", "Bakura", "Birnin Magaji Kiyaw", "Bukkuyum", "Bungudu", "Gummi", "Gusau", "Kaura Namoda", "Maradun", "Maru", "Shinkafi", "Talata Mafara", "Chafe", "Zurmi"]
        ];
    }

    /**
     * Convert time from mysql database server localtime to Africa/Lagos localtime
     * @param string $time the time from mysql database server
     * @param boolean $development true for development server and false for production server
     * @param boolean $formated the format of the returned time, if UNIX time or human readable time
     * @return mixed $formatedTime Africa/Lagos localtime
     */
    public static function dbTimeToLocal($time, $development, $formated = TRUE)
    {
        $timeDifference = 8 * 60 * 60;
        $localTime = ($development ? strtotime($time) : strtotime($time) + $timeDifference);
        $formatedTime = ($formated ? date("g:ia jS F Y", $localTime) : $localTime);
        return $formatedTime;
    }

    /**
     * get the directory of the running script
     *
     * @param string $file file pathbackend of the running script
     * @return string
     */
    public static function pwdName(string $file): string
    {
        $parentDirectoryArray = explode(DIRECTORY_SEPARATOR, dirname($file));
        $parentDirectory = $parentDirectoryArray[count($parentDirectoryArray) - 1];
        return $parentDirectory;
    }

    /**
     * get the account of a user
     *
     * @param PDO $pdo an instance of PDO object
     * @param integer $id the user profile id
     * @return string
     */
    public static function getAcctNo(PDO $pdo, int $id): string
    {
        $accountNo = "";
        $info = (new MyUsers($pdo))->getProfileInfo($id);
        if ($info[Users::USERTYPE_TABLE]['type_name'] == MyUsers::INDIVIDUAL['name'])
            $prefix = "D";
        if ($info[Users::USERTYPE_TABLE]['type_name'] == MyUsers::MSME['name'])
            $prefix = "B";
        if ($info[Users::USERTYPE_TABLE]['type_name'] == MyUsers::STAFF['name'])
            $prefix = "S";
        if ($info[Users::USERTYPE_TABLE]['type_name'] == MyUsers::GUARANTOR['name'])
            $prefix = "G";
        $accountNo = strtoupper($prefix) . str_pad($accountNo, 5, "0", STR_PAD_LEFT) . $id;
        return $accountNo;
    }

    /**
     * get profile id from account no
     *
     * @param string $accountNo the user account no
     * @return int
     */
    public static function getProfileIdFromAcctNo(string $accountNo): int
    {
        $profileId = intval(substr($accountNo, 1));
        return $profileId;
    }

    /**
     * encode loan id
     *
     * @param integer $id the id of the loan in loan table
     * @return string
     */
    public static function encodeLoadId(int $id): string
    {
        $loanNo = "";
        $loanNo = "LID" . str_pad($id, 5, "0", STR_PAD_LEFT);
        return $loanNo;
    }

    /**
     * decode loan id
     *
     * @param string $loanNo the loan no
     * @return int
     */
    public static function decodeLoadId(string $loanNo): int
    {
        $profileId = intval(substr($loanNo, 4));
        return $profileId;
    }

    /**
     * get a collection of loan products
     *
     * @return array
     */
    public static function getLoanProducts(): array
    {
        $products = [];
        $product1 = [
            'no' => 1,
            'type' => 'individual',
            'market' => 'Salary Earners - Private',
            'characteristics' => ['Professional', 'Knowledgeable', 'Have access to a monthly income'],
            'applicable products' => ['Personal loan', 'Salary Advance', 'School fees loan',  'Leases'],
            'obligor limit' => 1000000,
            'age' => [21, 60],
            'risk acceptance criteria' => [
                'Customer is an employee of approved company and must be Confirmed',
                "Repayment will not exceed 30% of customer' post - tax salary",
                'Customer does not have any non-performing Loan',
                'Customer must have a verifiable mean of identification in addition to BVN/NIN',
                'For personal loan, not more than 50% of the net monthly salary shall be disbursed',
                'Guarantor required',
                "Access to customers' terminal benefit",
                'Credit Insurance'
            ]
        ];
        $product2 = [
            'no' => 2,
            'type' => 'individual',
            'market' => 'Salary Earners - Public(Civil Servants)',
            'characteristics' => ['Professional', 'Knowledgeable', 'Have access to a monthly income', 'Salary may not be regular'],
            'applicable products' => ['Personal loan', 'Salary Advance', 'School fees loan',  'Leases', 'Travel loans'],
            'obligor limit' => 1000000,
            'age' => [21, 60],
            'risk acceptance criteria' => [
                'Customer is an employee of approved Ministries or MDAs & must be confimed',
                "Repayment will not exceed 30% of customer's post - tax salary",
                'Customer does not have any non - performing Loan',
                'Customer must have a verifiable mean of identification in addition to BVN/NIN',
                'For personal loan, not more than 50% of the net monthly salary shall be disbursed',
                'Guarantors required',
                "Access to customers' terminal benefit",
                'Credit Insurance'
            ]
        ];
        $product3 = [
            'no' => 3,
            'type' => 'individual',
            'market' => 'Skilled Labour',
            'characteristics' => ['Very Mobile', 'Skillful', 'Irregular / Low Income'],
            'applicable products' => ['Personal loan', 'School fees loan',  'Leases'],
            'obligor limit' => 500000.00,
            'age' => [20, 50],
            'risk acceptance criteria' => [
                'Customer must have envidence of a resonable skills and income ',
                'Customer does not have not any non-performing Loan',
                'Customer must have an active bank account',
                'Customer must have a verifiable mean of identification in addition to BVN/NIN ',
                'Guarantor with verifiable income is required '
            ]
        ];
        $product4 = [
            'no' => 4,
            'type' => 'individual',
            'market' => 'Unskilled Labour',
            'characteristics' => ['Very Mobile', 'Not Skillful', 'Irregular / Low Income'],
            'applicable products' => ['Personal loan', 'School fees loan', 'Leases'],
            'obligor limit' => 100000.00,
            'age' => [18, 35],
            'risk acceptance criteria' => [
                'Customer must have envidence of a resonable sources of income  *',
                'Customer does not have any non-performing Loan',
                'Customer must have a verifiable mean of identification in addition to BVN/NIN',
                ' Customer must have an active Bank account ',
                'Guarantor with verifiable income is required'
            ]
        ];
        $product5 = [
            'no' => 5,
            'type' => 'individual',
            'market' => 'Self Employed- Artisan',
            'characteristics' => ['Irregular Income', 'Has limited choice ', 'Business mostly unstructured'],
            'applicable products' => ['Personal loan', 'School fees loan', 'Leases', 'Asset finance'],
            'obligor limit' => 300000.00,
            'age' => [30, 55],
            'risk acceptance criteria' => [
                'Customer must have envidence of a resonable sources of income ',
                'Customer does not have not performing Loan',
                'Customer must have a verifiable mean of identification in addition to BVN/NIN',
                'Guarantor with verifiable income is required',
            ]
        ];
        $product6 = [
            'no' => 6,
            'type' => 'individual',
            'market' => 'Self - Employed - Professionals',
            'characteristics' => ['Irregular Income', 'Has many choices', 'Medium to high income level'],
            'applicable products' => ['Personal loan', 'School fees loan', 'Leases'],
            'obligor limit' => 1000000.00,
            'age' => [21, 60],
            'risk acceptance criteria' => [
                'Customer must have envidence of a resonable sources of income *',
                'Customer does not have not  any non-performing Loan',
                'Customer must have a verifiable mean of identification in addition to BVN/NIN',
                'Customer must have an active Bank account',
                'Guarantor with verifiable income is required ',
            ]
        ];
        $product7 = [
            'no' => 7,
            'type' => 'msme',
            'market' => 'Micro Borrower-Petty Traders',
            'characteristics' => ['Irregular /Low Income', 'Mobility is high'],
            'applicable products' => ['Stock Finance', 'Asset finance', 'Leases', 'Working Capital'],
            'obligor limit' => 500000.00,
            'age' => [20, 55],
            'risk acceptance criteria' => [
                'Customer must have envidence of a resonable sources of income *',
                'Customer does not have not performing Loan',
                'Customer must have a verifiable mean of identification in addition to BVN/NIN',
                'Guarantor with verifiable income is required ',
                'Asset used a collateral for Assets finacing products'
            ]
        ];
        $product8 = [
            'no' => 8,
            'type' => 'msme',
            'market' => 'Small  Scale Businesses',
            'characteristics' => ['Irregular Income', 'Has limited choices'],
            'applicable products' => ['Trade finance ', 'Asset finance ', 'Stock Finance', 'Leases', 'Overdraft'],
            'obligor limit' => 1000000.00,
            'age' => [20, 45],
            'risk acceptance criteria' => [
                'Customer must have envidence of a resonable sources of income',
                'Customer must have incorporated the company',
                'Customer does not have any non-performing Loan',
                'Customer must have a verifiable mean of identification in addition to BVN/NIN',
                'Customer must have an active Bank account',
                'Guarantor with verifiable income is required',
                'Asset used a collateral for Assets finacing products',
            ]
        ];
        $product9 = [
            'no' => 9,
            'type' => 'msme',
            'market' => 'Medium Scale Businesses',
            'characteristics' => ['Irregular Income', 'Has many choices'],
            'applicable products' => ['Stock financing', 'invoice discounting ', 'Trade Financing', 'LPO', 'Leases/ Asset finance ', 'Working Capital'],
            'obligor limit' => 1500000.00,
            'age' => [20, 55],
            'risk acceptance criteria' => [
                'Customer must have envidence of a resonable sources of income ',
                'Customer does not have any non- performing Loan',
                'Customer must have a verifiable mean of identification in addition to BVN/NIN',
                'Customer must have a registered business enterprise *Customer must have an active Bank account.',
                'Guarantor with verifiable income is required',
                'Asset used a collateral for Assets finacing products',
            ]
        ];
        $product10 = [
            'no' => 10,
            'type' => 'msme',
            'market' => 'On-Lending (Cooperative Societies )',
            'characteristics' => ['Are in clusters', 'Very structured', 'Applicable rates are usually low'],
            'applicable products' => ['On - lending'],
            'obligor limit' => 10000000.00,
            'age' => NULL,
            'risk acceptance criteria' => [
                'The society must be dully registered with CAC',
                'Credit must be guaranteed by the society',
                'Credit are mostly cash Collaterised'
            ]
        ];
        $products = [
            1 => $product1, 2 => $product2, 3 => $product3, 4 => $product4, 5 => $product5,
            6 => $product6, 7 => $product7, 8 => $product8, 9 => $product9, 10 => $product10
        ];
        return $products;
    }

    /**
     * get error from uploaded file based on PHP documentation
     *
     * @param integer $file the $_FILES['HTMLFormName'] from form html tag
     * @return null|string the error or null if no error
     */
    public static function PHPUploadError(array $file): ?string
    {
        $errors = [
            0 => null,
            1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini.",
            2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
            3 => "The uploaded file was only partially uploaded.",
            4 => "No file was uploaded",
            6 => "Missing a temporary folder",
            7 => "Failed to write file to disk",
            8 => "A PHP extension stopped the file upload",
        ];
        return $errors[$file['error']];
    }

    /**
     * get error from uploaded file based on filesize and file extension
     *
     * @param array $file the $_FILES['HTMLFormName'] from form html tag
     * @param array $permittedExtensions array of permitted file extensions
     * @param int $maxFilesize the max size of the file in bytes
     * @return string the error message or emtpy string if no error
     */
    public static function developerUploadError(array $file, array $permittedExtensions = [], int $maxFilesize = Functions::INFINITE): string
    {
        $errorMessage = "";

        if ($permittedExtensions) {
            $extensions = rtrim(implode(', ', $permittedExtensions), ', ');
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (!in_array(strtolower($extension), $permittedExtensions))
                $errorMessage .= "invalid extension: permitted ($extensions), uploaded ($extension). ";
        }
        if ($maxFilesize != Functions::INFINITE) {
            if ($file['size'] > $maxFilesize)
                $errorMessage .= "uploaded filesize {$file['size']} is larger than $maxFilesize. ";
        }

        return $errorMessage;
    }

    /**
     * get some words out of a sentence without truncating the last word
     *
     * @param string $sentence the sentence to be trucatted
     * @param int $noOfWords the no of the words to be gotten
     * @return string the gotten sentence
     */
    public static function getWords(string $sentence, int $noOfWords): string
    {
        preg_match("/(?:\w+(?:\W+|$)){0,$noOfWords}/", $sentence, $matches);
        return $matches[0];
    }
}
