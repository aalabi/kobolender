<?php
require_once dirname(__FILE__, 2) . "/connection.php";
$Authentication = new Authentication($PDO);
$loggerName = "";
$profileImage = URLBACKEND . "asset/image/profile-image/default-profile.png";
$Authentication->keyToPage();
$Tag = new MyTag($PDO);
$User = new Users($PDO);
$staffId = $_SESSION[Authentication::SESSION_NAME]['id'];
$staffInfo = $User->getInfo($staffId);
$Authentication->pageAccessor([MyUsers::STAFF_TYPE[1]], $staffInfo[MyUsers::STAFF['name']]['type']);
$loggerName = "{$staffInfo['profile']['surname']} {$staffInfo['profile']['firstname']}";
$profileImage = URLBACKEND . "asset/image/profile-image/{$staffInfo['profile']['profile_image']}";

$title = SITENAME . " Loan Application";
$pageName = "Loan Application";
$sideBar = $Tag->createSideMenu(['loans', 'application'], $staffId);
$headerFiles = [
    'css' => [
        URLBACKEND . 'asset/css/jquery.dataTables.min.css',
        URLBACKEND . 'asset/css/dataTables.bootstrap.min.css',
        URLBACKEND . 'asset/css/buttons.bootstrap.min.css',
        URLBACKEND . 'asset/css/fixedHeader.bootstrap.min.css',
        URLBACKEND . 'asset/css/responsive.bootstrap.min.css',
        URLBACKEND . 'asset/css/scroller.bootstrap.min.css',
    ]
];

$footJs = [
    URLBACKEND . 'asset/js/jquery.min.js',
    URLBACKEND . 'asset/js/bootstrap.bundle.min.js',
    URLBACKEND . 'asset/js/fastclick.js',
    URLBACKEND . 'asset/js/nprogress.js',
    URLBACKEND . 'asset/js/bootstrap.bundle.min.js',
    URLBACKEND . 'asset/js/jquery.dataTables.min.js',
    URLBACKEND . 'asset/js/dataTables.bootstrap.min.js',
    URLBACKEND . 'asset/js/dataTables.buttons.min.js',
    URLBACKEND . 'asset/js/buttons.bootstrap.min.js',
    URLBACKEND . 'asset/js/buttons.flash.min.js',
    URLBACKEND . 'asset/js/buttons.html5.min.js',
    URLBACKEND . 'asset/js/buttons.print.min.js',
    URLBACKEND . 'asset/js/pdfmake.min.js',
    URLBACKEND . 'asset/js/vfs_fonts.js',
    URLBACKEND . 'asset/js/custom.js',
    URLBACKEND . 'asset/js/al-custom.js'
];

$responseOperation = "";
if ($theResponse = Tag::getResponse()) {
    $responseMessage = rtrim(implode(", ", $theResponse['messages']), ", ");
    $responseOperation = $Tag->responseTag(
        $theResponse['title'],
        $responseMessage,
        $theResponse['status']
    );
}

$tr = "";
$Db = new Database(__FILE__, $PDO, 'loan');
$User = new Users($PDO);
$MyUser = new MyUsers($PDO);

$individual = MyUsers::INDIVIDUAL['name'];
$userType = Users::USERTYPE_TABLE;
$msme = MyUsers::MSME['name'];
$profile = Users::TABLE_PROFILE;
$guarantor = MyUsers::GUARANTOR['name'];
$login = Authentication::TABLE;

$where = [['column' => 'status', 'comparsion' => '=', 'bindAbleValue' => 'wip']];
$result = $Db->select(__LINE__, [], $where);

if ($result) {
    $kanter = 0;
    foreach ($result as $aResult) {
        $info = $MyUser->getProfileInfo($aResult['profile_id']);
        $accountNo = Functions::getAcctNo($PDO, $aResult['profile_id']);
        $gAccountNo = $gInfo[$profile]['firstname'] = $gInfo[$profile]['middlename'] =
            $gInfo[$profile]['surname'] = "";
        if ($info[$userType]['type_name'] == $individual) $gUserId = $info[$individual]['guarantor_id'];
        if ($info[$userType]['type_name'] == $msme) $gUserId = $info[$msme]['guarantor_id'];
        if ($gUserId) {
            $gId = $MyUser->getProfileIdFrmUserId($gUserId, $guarantor);
            $gInfo = $MyUser->getProfileInfo($gId);
            $gAccountNo = Functions::getAcctNo($PDO, $gId);
        }

        $tr .= "
            <tr>
                <td>" . ++$kanter . "</td>
                <td>
                    <a href='" . URLBACKEND . "a-loan/?id={$aResult['id']}'>
                        <u>" . Functions::encodeLoadId($aResult['id']) . "</u>
                    </a><br/>
                    " . ucwords(Functions::getLoanProducts()[$aResult['product']]['market']) . "<br/>
                    " . number_format($aResult['amount'], 2) . "<br/>
                    " . date("jS F Y i:m a", strtotime($aResult['created_at'])) . "

                </td>
                <td>
                    <a href='" . URLBACKEND . "a-user/?id={$aResult['profile_id']}'>
                        {$info[$profile]['firstname']} 
                        {$info[$profile]['middlename']}
                        {$info[$profile]['surname']}
                    </a>
                    <br/>
                    $accountNo <small>{$info[$login]['status']}</small>                                       
                </td>
                <td>
                    <a href='" . URLBACKEND . "a-user/?id={$aResult['profile_id']}'>
                        {$gInfo[$profile]['firstname']} 
                        {$gInfo[$profile]['middlename']}
                        {$gInfo[$profile]['surname']}
                    </a>
                    <br/>
                    $gAccountNo
                </td>
                <td>
                    " . generateApproveModal($aResult['amount'], $aResult['id']) . "<br/>
                    " . generateRejectModal($aResult['id']) . "<br/>


                </td>
            </tr>    
        ";
    }
}


function generateApproveModal(float $amount, int $loanId)
{
    $modal = "
        <!-- Button trigger modal -->
            <button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#a$loanId'>
            Approve
        </button>

    <!-- Modal -->
    <div class='modal fade' data-backdrop='static' data-keyboard='false' id='a$loanId' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
        <div class='modal-dialog' role='document'>
            <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='exampleModalLabel'>" . Functions::encodeLoadId($loanId) . "</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <form action='processor.php' method='post'>
                <input type='hidden' name='action' value='approve'/>
                " . MyTag::getCSRFTokenInputTag() . "
                <div class='modal-body'>
                    <input type='hidden' name='loanId' value='$loanId'/>
                    <div class='row'>
                        <div class='col-12'>
                            <span class='d-block'>approved amount *</span>
                            <div class='form-group'>
                                <input class='form-control' required max='$amount' type='number' name='amount' value='$amount'/>
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class='modal-footer'>                    
                    <button type='submit' class='btn btn-primary btn-sm'>Save</button>
                </div>
            </form>
            </div>
        </div>
    </div>";
    return $modal;
}

function generateRejectModal(string $loanId)
{
    $modal = "
        <!-- Button trigger modal -->
            <button type='button' class='btn btn-sm btn-danger' data-toggle='modal' data-target='#r$loanId'>
            Decline
        </button>

    <!-- Modal -->
    <div class='modal fade' data-backdrop='static' data-keyboard='false' id='r$loanId' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
        <div class='modal-dialog' role='document'>
            <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title' id='exampleModalLabel'>" . Functions::encodeLoadId($loanId) . "</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <form action='processor.php' method='post'>
                <input type='hidden' name='action' value='reject'/>
                " . MyTag::getCSRFTokenInputTag() . "
                <div class='modal-body'>
                    <input type='hidden' name='loanId' value='$loanId'/>
                    <div class='row'>
                        <div class='col-12'>
                            <span class='d-block'>reason *</span>
                            <div class='form-group'>
                                <textarea class='form-control' name='reason' required></textarea>
                            </div>
                        </div>
                    </div>                    
                </div>
                <div class='modal-footer'>                    
                    <button type='submit' class='btn btn-primary btn-sm'>Save</button>
                </div>
            </form>
            </div>
        </div>
    </div>";
    return $modal;
}
