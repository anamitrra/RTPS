<?php


$userdata = ($this->session->userdata());

$username = $userdata['username'];

$this->load->library('uri');
$this->load->helper('url');
$doc_id = $this->uri->segment(5);

// pre($doc_id);

$userdata = $this->session->userdata();
$userdata['doc_id'] = $doc_id;
$this->session->set_userdata($userdata);
// print_r($userdata);

use MongoDB\BSON\ObjectId;
$activeProcessUserRoleArray = [];
$canCurrentUserProcess = false;
$canCurrentUserComment = false;
$didCurrentUserComment = false;
$isCurrentUserChairman = false;
$isFinalVerdictGiven = false;
$isCurrentUserDPS = false;
$isHearingDateProvided = false;
$isHearingGenerated = false;
$isDisposalGenerated = false;
$isRejectionGenerated = false;
$isHearingApproved = false;
$isDisposalApproved = false;
$isRejectionApproved = false;
$isFinalHearing = false;
$isHearingDateConfirmed = false;
$isBenchCreated = false;
$benchAuthorizedPersonRoleSlug = '';
$benchAuthorizedPersonId = '';
$seekInfoFromAppellantCount = 0;
foreach ($appealApplicationProcesses as $process){
    if($process->action == 'second-appeal-seek-info' && $process->notifiable == 'appellant' && strval($process->action_taken_by) == $this->session->userdata('userId')->{'$id'} && $this->session->userdata('role')->slug == 'DA'){
        $seekInfoFromAppellantCount++;
    }
    switch($process->action) {
        case 'comment-by-bench-member':
            if(strval($process->action_taken_by) === $this->session->userdata('userId')->{'$id'}){
                $didCurrentUserComment = true;
            }
            break;
        case 'second-appeal-provide-hearing-date':
            $isHearingDateProvided = true;
            break;
        case 'second-appeal-confirm-hearing-date':
            $isHearingDateConfirmed = true;
            if(property_exists($process,'is_final_hearing') && $process->is_final_hearing){
                $isFinalHearing = true;
            }
            break;
        case 'second-appeal-generate-hearing-order':
        case 'generate-hearing-order':
//        case 'second-appeal-upload-hearing-order':
            $isHearingGenerated = true;
            break;
        case 'second-appeal-approve-hearing-order':
            $isHearingApproved = true;
            break;
        case 'second-appeal-final-verdict':
            $isFinalVerdictGiven = true;
            break;
        case 'generate-disposal-order':
        case 'second-appeal-upload-disposal-order':
            $isDisposalGenerated = true;
            break;
        case 'second-appeal-approve-disposal-order':
            $isDisposalApproved = true;
            break;
        case 'generate-rejection-order':
        case 'second-appeal-upload-rejection-order':
            $isRejectionGenerated = true;
            break;
        case 'second-appeal-approve-rejection-order':
            $isRejectionApproved = true;
            break;
        case 'second-appeal-create-bench':
            $isBenchCreated = true;
            if(property_exists($process,'delegate_to_chairman'))
                $benchAuthorizedPersonRoleSlug = $process->delegate_to_chairman->slug;
                $benchAuthorizedPersonId = $process->delegate_to_chairman->userId;

            if(property_exists($process,'delegate_to_chairman') && $process->delegate_to_chairman->userId === $this->session->userdata('userId')->{'$id'} && $this->session->userdata('role')->slug === 'RA'){
                $isCurrentUserChairman = true;
            }
            break;
        default:
            break;
    }
}
$pr=0;

$daArray=array();
foreach ($appealApplication as $appealData){
    if($appealData->process_users->role_slug === 'DPS'){
        $dpsDetails = $appealData->process_users_data;
        if($this->session->userdata('userId')->{'$id'} === strval($appealData->process_users->{'userId'})){
            $isCurrentUserDPS = true;
        }
    }
    if($appealData->process_users->role_slug === 'DA'){
        array_push($daArray, $appealData->process_users_data);
    }
    if($appealData->process_users->role_slug === 'AA'){
        $aaDetails = $appealData->process_users_data;
    }
    if($appealData->process_users->role_slug === 'RR'){
        $rrDetails = $appealData->process_users_data;
    }
    if($appealData->process_users->role_slug === 'MOC' && $benchAuthorizedPersonId === strval($appealData->process_users->userId)){
        $mocDetails = $appealData->process_users_data;
    }
    $isCurrentUserAProcessUser = (strval($this->session->userdata('userId')->{'$id'}) === strval($appealData->process_users->userId));
    if(($isHearingApproved && !$isFinalVerdictGiven) && in_array($appealData->process_users->role_slug,['MOC','RA']) && $isCurrentUserAProcessUser){
        $canCurrentUserComment = true;
    }
    if($appealData->process_users->role_slug === 'RA'){
        $raDetails = $appealData->process_users_data;
    }
//    if($appealData->process_users->role_slug === 'DA' && $appealData->process_users->active === true){
//        $daDetails = $appealData->process_users_data;
//    }
    if($appealData->process_users->active){
        if($this->session->userdata('userId')->{'$id'} === strval($appealData->process_users->userId)){
            $canCurrentUserProcess = true;
        }
        $activeProcessUserRoleArray[] = $appealData->process_users->role_slug;
    }
}
$forwardAbleUserOptionList = '<option value="">Choose One</option>';
if (isset($forwardAbleUserList) && count((array)$forwardAbleUserList)) {
    foreach ($forwardAbleUserList->users as $key => $forwardAbleUser) {
        if (strval($forwardAbleUser->{'_id'}) !== $this->session->userdata('userId')->{'$id'}) {
            $forwardAbleUserOptionList .= '<option value="' . $forwardAbleUser->{'_id'} . '">' . $forwardAbleUser->name . ' (' . $forwardAbleUserList->role_name . ')</option>';
        }
    }
} else {
    $forwardAbleUserOptionList = '<option value="">No user available</option>';
}
$reAssignAbleUserOptionList = '<option value="">Choose One</option>';
if (isset($reAssignAbleUserList) && count((array)$reAssignAbleUserList)) {
    foreach ($reAssignAbleUserList->{0}->users as $key => $reAssignAbleUser) {
        if (strval($reAssignAbleUser->{'_id'}) !== strval($dpsDetails->{'_id'})) {
            $reAssignAbleUserOptionList .= '<option value="' . $reAssignAbleUser->{'_id'} . '" data-role-slug="' . $reAssignAbleUserList->{0}->slug . '">' . $reAssignAbleUser->name . ' (' . $reAssignAbleUserList->{0}->role_name . ')</option>';
        }
    }
    //    foreach ($reAssignAbleUserList->{1}->users as $key => $reAssignAbleUser) {
    //        if(strval($reAssignAbleUser->{'_id'}) !== $this->session->userdata('userId')->{'$id'}){
    //            $reAssignAbleUserOptionList .= '<option value="' . $reAssignAbleUser->{'_id'} . '" data-role-slug="'.$reAssignAbleUserList->{0}->slug.'">' . $reAssignAbleUser->name . ' ('.$reAssignAbleUserList->{1}->role_name.')</option>';
    //        }
    //
    //    }
} else {
    $reAssignAbleUserList = '<option value="">No user available</option>';
}

$availableProcesses = [];

foreach ($appealApplication as $forAppealProcessUser){
    if(($forAppealProcessUser->process_users->{'userId'} == new ObjectId($this->session->userdata('userId')->{'$id'}))
        && isset($forAppealProcessUser->process_users->action)
    ){
        $availableProcesses = $forAppealProcessUser->process_users->action;
    }
}
//pre($availableProcesses);
?>
<style>
    #kvFileinputModal embed {
        height: 500px !important;
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />

<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<link href="<?= base_url('assets/fileupload/themes/explorer-fas/theme.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/themes/explorer-fas/theme.js') ?>" type="text/javascript"></script>

<style>
    .parsley-errors-list {
        list-style-type: none;
        padding-left: 0px;
    }

    .parsley-errors-list li {
        color: red;
    }
</style>
<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Appeals Processes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item">Appeals</li>
                        <li class="breadcrumb-item active">process</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header p-0" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Appeal Details
                            </button>
                        </h2>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="" data-parent="">
                        <div class="card-body">
                            <?php if (isset($appealApplication[0]->application_data)) {
                            ?>
                                <button type="button" class="btn btn-outline-info mb-3" data-toggle="modal" data-target="#applicationDetailsModal">
                                    View Application Details
                                </button>
                            <?php
                            } ?>

                            <?php if (isset($appealApplication[0]->ref_appeal_id)) { ?>
                                <button type="button" class="btn btn-outline-info mb-3" data-toggle="modal" data-target="#previousAppealDetailsModal">
                                    View Previous Appeal Details
                                </button>
                            <?php
                            }
                            $this->load->view("appeals/view_appeal_application", ['appealApplication' => $appealApplication,'finalVerdictInfoForPreviousAppeal' => $finalVerdictInfoForPreviousAppeal]);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header p-0" id="headingTrack">
                        <h2 class="mb-0">
                            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTrack" aria-expanded="true" aria-controls="collapseTrack">
                                Appeal Process History
                            </button>
                        </h2>
                    </div>
                    <div class="collapse show" aria-labelledby="headingTrack" data-parent="#accordionExample">
                        <div class="card-body" id="processTBody">

                        </div>
                    </div>
                </div>
                <?php if (!in_array($appealApplication[0]->process_status, array('second-appeal-issue-rejection-order','rejected', 'second-appeal-issue-disposal-order','resolved'))) {
                ?>
<!--                   some code were here-->

                    <?php
                    if($canCurrentUserComment && !$didCurrentUserComment && $this->session->userdata('role')->slug !== 'RA'){ //  && !$canCurrentUserProcess
                        ?>
                        <a class="btn btn-success text-white btn-block shadow my-2" data-toggle="modal" data-target="#commentBeforeFinalVerdict">
                            Click here to comment before final verdict
                        </a>
                    <?php }

                    if(!empty(array_intersect($activeProcessUserRoleArray,['DA','AA','RA','RR','DPS','MOC'])) && $canCurrentUserProcess){
//                    if (in_array($appealApplication[0]->current_user, array("APPELLATE", "REVIEWER")) || $this->session->userdata('role')->slug === 'DA') { /* ,new ObjectId($latestForwardedUserId) */ ?>
                        <div class="card">
                            <div class="card-header p-0" id="headingFour">
                                <h2 class="mb-0">
                                    <button class="btn btn-link card-header-btn" type="button" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                        Response to Appeal
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseFour" class="collapse show" aria-labelledby="headingFour" data-parent="#accordionExample">
                                <div class="card-body">
                                    <div id="appellateProcessActionFormMsgContainer"></div>
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="action">Action <span class="text-danger">*</span></label>
                                            <span class="text-danger"><?= (!$username) ?  'Dear User, you are requested to set your unique username by going prifile section. Please note that this is a one time activity which will enable you to login with the username in future.' : '' ?></span>
                                            
                                            <select <?= (!$username) ? 'disabled' : '' ?> name="action" id="action" class="form-control select2 action-selected" data-parsley-errors-container="#actionError" <?=($this->session->userdata('role')->slug === 'DPS')?'readonly': 'required'?>>

                                            <option value="">Please select an action</option>
                                            <?php if(!empty($process_action)){ 
                                                foreach($process_action as $key=>$action ){ ?>
                                                    <option value="<?=$key?>"><?= $action?> </option>
                                                <?php  }
                                             }  ?>
                                            </select>
                                            <div id="actionError"></div>
                                        </div>
                                    </div>
                                    <div id="action_block"></div>

                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php
                } ?>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="actionTemplateModal" aria-modal="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Action Template View</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" id="templatePrintable">
            </div>
            <div id="elementH"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" onclick="printTemplate()"><i class="fa fa-print"></i> Print
                </button>
                <button type="button" class="btn btn-outline-info" onclick="downloadTemplate()"><i class="fa fa-download"></i> Download
                </button>
            </div>
        </div>
    </div>
</div>
<?php if (isset($appealApplication[0]->application_data)) {
?>
    <div class="modal fade" id="applicationDetailsModal" aria-modal="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Application Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    $this->load->view("applications/view_application", array('data' => $appealApplication[0]->application_data->initiated_data, 'execution_data' => $appealApplication[0]->application_data->execution_data));
                    ?>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
<?php
} ?>

<?php if (isset($appealApplication[0]->ref_appeal_id)) { ?>

    <div class="modal fade" id="previousAppealDetailsModal" aria-modal="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Previous Appeal Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    $this->load->view("appeals/view_appeal_application", ['appealApplication' => $appealApplicationPrevious]);
                    ?>
                    <div class="card-body" id="processPreviousTBody">

                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
<?php } ?>


<div class="modal fade" id="processDocModal" aria-modal="true" style="z-index: 9999!important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Documents</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body table-responsive">
                <div id="processDocModalTBody"></div>
                <!--                <table class="table table-bordered table-hover">-->
                <!--                    <thead>-->
                <!--                    <tr>-->
                <!--                        <th class="text-center">Sl No.</th>-->
                <!--                        <th class="text-center">Action</th>-->
                <!--                    </tr>-->
                <!--                    </thead>-->
                <!--                    <tbody id="processDocModalTBody">-->
                <!--                    <tr>-->
                <!--                        <td colspan="2" class="text-center">No Data Available</td>-->
                <!--                    </tr>-->
                <!--                    </tbody>-->
                <!--                </table>-->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewTemplateModal" aria-modal="true" style="z-index: 9999!important;">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <form id="downloadTemplateForm" method="POST" target="_blank" action="<?= base_url('appeal/templates/view_and_download')  ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="templateModalHeading">Hearing Order</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="viewTemplateModalMsg"></div>
                    <div id="viewTemplateModalBody"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="save_template" class="btn btn-success pull-right">Save
                    </button>
                    <button type="submit" id="sub_template" class="btn btn-info pull-right">Download
                    </button>
                    <!-- <button type="submit" id="sub_template" class="btn btn-outline-success pull-right">Update
                    </button> -->
                </div>

            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="commentBeforeFinalVerdict" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="templateModalHeading">Comment Before Final Verdict</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="commentForm" action="<?=base_url('appeal/second/process/comment-before-final-verdict')?>" method="POST">
                <input type="hidden" name="appeal_id" value="<?=$appealApplication[0]->appeal_id?>">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <label for="comment">Comment <span class="text-danger">*</span> <i data-toggle="tooltip" title="Comment by member of the commission should be entered before final verdict is entered by the Chairman/ Authorized member" class="fa fa-question-circle"></i></label>
                            <textarea name="comment" id="comment" class="form-control" placeholder="Enter comment" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success float-right" id="submitComment">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/toastr/toastr.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/jspdf/jspdf.min.js') ?>"></script>
<?php
$this->session->unset_userdata("file_for_dps_action");
$this->session->unset_userdata("file_for_appelete_action");

$this->session->unset_userdata("file_for_action");
$this->session->unset_userdata("appellant_hearing_order");
$this->session->unset_userdata("dps_hearing_order");
$this->session->unset_userdata("signed_order_for_appellant");
$this->session->unset_userdata("signed_order_for_dps");
$this->session->unset_userdata("signed_disposal_order");
$this->session->unset_userdata("signed_rejection_order");
?>
<script>
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0)
    }, 4000);
    var submitCommentRef = $('#submitComment')
    var commentFormRef = $('#commentForm')
    var appealId = '<?=$appealApplication[0]->appeal_id?>';
    const fetchTemplateUrl = '<?= base_url('appeal/templates/generate') ?>';
    const viewTemplateUrl='<?=base_url('appeal/templates/view-order')?>';
    var templateLinkText = '';
    var firstActionText = '';
    var secondActionText = '';
    var showTemplateLinkBox = true;
    var actionRefByName = $('input[name="action"]');
    var actionRef = $('#action');
    var templateLinkBoxRef = $('#templateLinkBox');
    var actionTemplateModalRef = $('#actionTemplateModal');
    var actionTemplateLinkRef = $('#actionTemplateLink');
    var actionTemplateLink2Ref = $('#actionTemplateLink2');
    var additionLinkTextRef = $('#additionLinkText');
    var additionLinkText2Ref = $('#additionLinkText2');
    var templatePrintableRef = $('#templatePrintable');
    var additionalContentRef = $('#additionalContent');
    var penaltyShouldPayWithinDaysRef = $('#penaltyShouldPayWithinDays');
    var certificateIssuedWithinDaysRef = $('#certificateIssuedWithinDays');
    var datepickerRef = $('.datepicker');
    var dateOfHearingRef = $('#date_of_hearing');
    var lastDateOfSubmissionRef = $('#last_date_of_submission');
    var notifiableRef = $('#notifiable');
    var orderNoRef = $('#order_no');
    const orderBoxRef = $('.order-box');
    var processActionUrl = '<?= base_url('appeal/process/dps/action/') ?>';
    var processAppellateActionUrl = '<?= base_url('appeal/process/appellate/action/') ?>';
    var processActionFormRef = $('#processActionForm');
    var processActionFormMsgContainerRef = $('#processActionFormMsgContainer');
    var appellateProcessActionFormMsgContainerRef = $('#appellateProcessActionFormMsgContainer');
    var processSubmitRef = $('#processSubmit');
    var penaltyBoxRef = $('.penalty-box');
    var select2Ref = $('.select2');
    var remarkRef = $('#remark');
    var replyRef = $('#reply');
    var resolvedRef = $('#resolved');
    var inProgressRef = $('#in-progress');
    var rejectedRef = $('#rejected');
    var penalizeRef = $('#penalize');
    var forwardToRef = $('#forwardTo');
    var forwardToBoxRef = $('.forward-box');
    var reassignToRef = $('#reassignTo');
    var reassignToRoleSlugRef = $('#reassignToRoleSlug');
    var reassignToBoxRef = $('.reassign-box');
    var hearingBoxRef = $('.hearing-box');
    var seekBoxRef = $('.seek-box');
    var notifiableBoxRef = $('.notifiable-box');
    var penaltyAmountRef = $('#penaltyAmount');
    var remarksRef = $('#remarks');
    var actionSubmitProgressRef = $('#actionSubmitProgress');
    var submitBtnTxtRef = $('#submitBtnTxt');
    var dpsActionBlockRef = $('#dpsActionBlock');
    var appellateFormRef = $('#appellateForm');
    var reviewerFormRef = $('#reviewerForm');
    var processTBodyRef = $('#processTBody');
    var processPreviousTBodyRef = $('#processPreviousTBody');
    var processDocModalRef = $('#processDocModal');
    var processDocModalTBodyRef = $('#processDocModalTBody');
    var dpsFileUploadRef = $('#dps_file_upload');
    var appellateFileUploadRef = $('#appelete_file_upload');
    var downloadDisposalOrderUrl = '<?=base_url('appeal/templates/view-and-download/disposal-order')?>';
    var downloadRejectionOrderUrl = '<?=base_url('appeal/templates/view-and-download/rejection-order')?>';
    var downloadHearingOrderUrl = '<?=base_url('appeal/templates/view-and-download/hearing-order')?>';
    var refreshProcessUrl = '<?= base_url('appeal/process/refresh/' . $appealApplication[0]->appeal_id) ?>';
    <?php
        if(isset($appealApplication[0]->ref_appeal_id)){
    ?>
    var refreshPreviousProcessUrl = '<?= base_url('appeal/process/refresh/' . $appealApplication[0]->ref_appeal_id) ?>';
    <?php
        }
    ?>

    const viewActionTemplateModal = function(overrideNotifiable = '') {
    let action                         = actionRef.val();
    var notifiable                     = notifiableRef.val();
    var dateOfHearingRef               = $('#date_of_hearing');
    var orderNoRef                     = $('#order_no');
    var additionalContentRef           = $('#additionalContent');
    var penaltyAmountRef               = $('#penaltyAmount');
    var penaltyShouldPayWithinDaysRef  = $('#penaltyShouldPayWithinDays');
    var certificateIssuedWithinDaysRef = $('#certificateIssuedWithinDays');
    var numberOfDaysofDelayRef         = $('#numberOfDaysofDelay');
    var totalPenaltyAmountRef          = $('#totalPenaltyAmount');
    var remarksRef                     = $('#remarks');
    if (notifiable === 'both') {
        notifiable = 'appellant';
    }
    if (overrideNotifiable.length) {
        notifiable = overrideNotifiable;
    }
    let additionalContent = additionalContentRef.val();
    let orderNo = orderNoRef.val();
    if (action === 'hearing' && !dateOfHearingRef.val()) {
        Swal.fire('Warning', 'Please enter date of hearing.', 'warning')
        return;
    }
    if (action === 'second-appeal-upload-rejection-order' && !remarksRef.val()) {
        Swal.fire('Warning', 'Please enter reason for rejection in the remarks box.', 'warning')
        return;
    }
    if ( (action === "generate_penalty_order" ||action === 'penalize') && !penaltyAmountRef.val()) {
        Swal.fire('Warning', 'Please enter penalty amount.', 'warning')
        return;
    }
    if ( (action === "generate_penalty_order" ||action === 'penalize') && (!penaltyShouldPayWithinDaysRef.val() || isNaN(penaltyShouldPayWithinDaysRef.val()))) {
        Swal.fire('Warning', 'Please enter penalty should be paid within (in number format).', 'warning')
        return;
    }
    if ( (action === "generate_penalty_order" ||action === 'penalize')  && (!certificateIssuedWithinDaysRef.val() || isNaN(certificateIssuedWithinDaysRef.val()))) {
        Swal.fire('Warning', 'Please enter certificate should be issued within (in number format).', 'warning')
        return;
    }
    if (action || (action === 'hearing' && notifiable)) {
        // if ($.inArray(action, ['issue-order', 'seek-info', 'forward', 'in-progress']) === -1 && !orderNo.length) {
        //     Swal.fire('Warning', 'Please enter order no.', 'warning');
        //     orderNoRef.focus();
        //     return;
        // }

        let contentToReplace = {
            "date_of_hearing": dateOfHearingRef.val(),
            "reason_for_rejection": remarksRef.val(),
            "penalty_amount": penaltyAmountRef.val(),
            "penalty_should_by_paid_within_days": penaltyShouldPayWithinDaysRef.val(),
            "certificate_to_be_issued_within_days": certificateIssuedWithinDaysRef.val(),
            "number_of_days_of_delay": numberOfDaysofDelayRef.val(),
            "total_penalty_amount": totalPenaltyAmountRef.val(),
            "order_no": orderNo,
            "additional_content": additionalContent,
        }
        if(action === 'second-appeal-upload-hearing-order'){
            action = 'generate-hearing-order';
        }

        if(action === 'second-appeal-upload-disposal-order'){
            action = 'generate-disposal-order';
        }
        if(action === 'second-appeal-upload-rejection-order'){
            action = 'generate-rejection-order';
        }
        // window.open(viewTemplateUrl + '?' + jQuery.param({
        //     action,
        //     notifiable,
        //     appeal_id: '<?= $appealApplication[0]->appeal_id ?>',
        //     appeal_type: 'first',
        //     contentToReplace
        // }), '_blank');

        $.ajax({
            url: viewTemplateUrl + '?' + jQuery.param({
                action,
                notifiable,
                appeal_id: '<?= $appealApplication[0]->appeal_id ?>',
                appeal_type: 'second',
                contentToReplace
            }),
            type: 'GET',
            dataType:'html',
            beforeSend: function() {
                swal.fire({
                    html: '<h5>Processing...</h5>',
                    showConfirmButton: false,
                    allowOutsideClick: () => !Swal.isLoading(),
                    onOpen: function() {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                let downloadTemplateFormRef = $('#downloadTemplateForm');
                let templateModalHeadingRef = $('#templateModalHeading');
                if(action === "generate-penalty-order"){
                    downloadTemplateFormRef.attr('action',downloadPenaltyOrderUrl)
                    templateModalHeadingRef.text('Penalty Order')
                }else if(action === "generate-disposal-order"){
                    downloadTemplateFormRef.attr('action',downloadDisposalOrderUrl)
                    templateModalHeadingRef.text('Disposal Order')
                }else if(action === "generate-rejection-order"){
                    downloadTemplateFormRef.attr('action',downloadRejectionOrderUrl)
                    templateModalHeadingRef.text('Rejection Order')
                }else{
                    downloadTemplateFormRef.attr('action',downloadHearingOrderUrl)
                    templateModalHeadingRef.text('Hearing Order')
                }
                Swal.close()
                $("#viewTemplateModalBody").html(response);
                $("#viewTemplateModal").modal('show');
                // console.log(response);
                // $("#action_block").html(response);
            },
            error: function() {
                Swal.close()
                console.log('error')
            },
        })


    } else {
        Swal.fire('Warning', 'Please select both action and notifiable', 'warning')
    }
}

    actionRef.change(function() {
        let selectedValue = $(this).val()
        if ($.inArray(selectedValue, ['hearing']) !== -1) {
            hearingBoxRef.removeClass('d-none')
            dateOfHearingRef.prop('required', true)
        } else {
            hearingBoxRef.addClass('d-none')
            dateOfHearingRef.prop('required', false)
        }
        if (selectedValue === 'seek-info') {
            seekBoxRef.removeClass('d-none')
            lastDateOfSubmissionRef.prop('required', true)
        } else {
            seekBoxRef.addClass('d-none')
            lastDateOfSubmissionRef.prop('required', false)
        }
        if ($.inArray(selectedValue, ['hearing', 'seek-info', 'issue-order']) !== -1) {
            notifiableBoxRef.removeClass('d-none')
            notifiableRef.prop('required', true)
        } else {
            notifiableBoxRef.addClass('d-none')
            notifiableRef.prop('required', false)
        }

        if (selectedValue === 'forward') {
            forwardToBoxRef.removeClass('d-none')
            forwardToRef.prop('required', true)
        } else {
            forwardToBoxRef.addClass('d-none')
            forwardToRef.prop('required', false)
        }
        if (selectedValue === 'reassign') {
            reassignToBoxRef.removeClass('d-none')
            reassignToRef.prop('required', true)
        } else {
            reassignToBoxRef.addClass('d-none')
            reassignToRef.prop('required', false)
        }
        if (selectedValue === 'penalize') {
            penaltyBoxRef.removeClass('d-none')
            penaltyAmountRef.prop('required', true)
        } else {
            penaltyBoxRef.addClass('d-none')
            penaltyAmountRef.prop('required', false)
        }

        if ($.inArray(selectedValue, ['in-progress', 'forward']) === -1) {
            actionTemplateLinkRef.removeClass('d-none')
        } else {
            actionTemplateLinkRef.addClass('d-none')
        }
        if ($.inArray(selectedValue, ['in-progress', 'forward', 'reassign', 'seek-info', 'issue-order', '']) === -1) {
            orderBoxRef.removeClass('d-none');
        } else {
            // console.log('else')
            orderBoxRef.addClass('d-none');
        }

        showTemplateLinkBox = true;
        switch (selectedValue) {
            case 'resolved':
                templateLinkText = 'Disposal order template'
                break;
            case 'rejected':
                templateLinkText = 'Appeal Rejection order template'
                break;
            case 'penalize':
                templateLinkText = 'Penalty order template'
                break;
                // case 'seek-info':
                //     templateLinkText = 'seek information : template'
                //     break;
            case 'hearing':
                templateLinkText = 'Notice for hearing : template'
                showTemplateLinkBox = false;
                break;
                // case 'issue-order':
                //     templateLinkText = 'Order : template '
                //     break;
            default:
                showTemplateLinkBox = false;
                templateLinkText = ''
                break;
        }
        if ($.inArray(selectedValue, ['seek-info', 'issue-order']) === -1) {
            actionTemplateLinkRef.html('View ' + templateLinkText);
            actionTemplateLink2Ref.html('View ' + templateLinkText);
        }
    });

    notifiableRef.change(function() {
        firstActionText = actionTemplateLinkRef.text();
        secondActionText = actionTemplateLink2Ref.text();
        showTemplateLinkBox = true;
        switch ($(this).val()) {
            case "appellant":
                if (firstActionText.search(':') !== -1) {
                    firstActionText = firstActionText.replace(':', '(appellant)')
                } else if (firstActionText.search('(DPS)') !== -1) {
                    firstActionText = firstActionText.replace('(DPS)', '(appellant)')
                }
                actionTemplateLink2Ref.addClass('d-none')
                break;
            case "dps":
                if (firstActionText.search(':') !== -1) {
                    firstActionText = firstActionText.replace(':', '(DPS)')
                } else if (firstActionText.search('(appellant)') !== -1) {
                    firstActionText = firstActionText.replace('(appellant)', '(DPS)')
                }

                actionTemplateLink2Ref.addClass('d-none')
                break;
            case "both":
                if (firstActionText.search(':') !== -1) {
                    firstActionText = firstActionText.replace(':', '(appellant)')
                } else if (firstActionText.search('(DPS)') !== -1) {
                    firstActionText = firstActionText.replace('(DPS)', '(appellant)')
                }
                secondActionText = secondActionText.replace(':', '(DPS)')
                actionTemplateLink2Ref.html(secondActionText);
                actionTemplateLink2Ref.removeClass('d-none')
                break;
            default:
                actionTemplateLinkRef.addClass('d-none')
                actionTemplateLink2Ref.addClass('d-none')
                showTemplateLinkBox = false;
                return;
                // break;
        }
        if ($.inArray(actionRef.val(), ['seek-info', 'issue-order']) === -1) {
            actionTemplateLinkRef.html(firstActionText);
            actionTemplateLinkRef.removeClass('d-none')
        } else {
            showTemplateLinkBox = false;
        }
        showTemplateLinkCard();
    })

    $(function() {

        commentFormRef.parsley();
        $('[data-toggle="tooltip"]').tooltip();
        datepickerRef.datepicker({
            format: 'dd-mm-yyyy',
            startDate: new Date()
        });

        dateOfHearingRef.datepicker({
            format: 'dd-mm-yyyy',
            startDate: '+7d'
        });

        //DPS File Upload
        var $el1 = $("#dps_file_upload");
        $el1.fileinput({
            theme: 'explorer-fas',
            uploadUrl: '<?= base_url("upload") ?>',
            uploadAsync: true,
            dropZoneEnabled: false,
            overwriteInitial: false,
            maxFileSize: 1000,
            uploadExtraData: {
                "filename": "file_for_dps_action"
            },
            allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
            browseOnZoneClick: true,
            initialPreviewAsData: false,
            showUpload: false
        }).on("filebatchselected", function(event, files) {
            $el1.fileinput("upload");
        });
        //AppeleteFileUpload File Upload
        var $el2 = $("#appelete_file_upload");
        $el2.fileinput({
            theme: 'explorer-fas',
            uploadUrl: '<?= base_url("upload") ?>',
            uploadAsync: true,
            dropZoneEnabled: false,
            overwriteInitial: false,
            maxFileSize: 1000,
            uploadExtraData: {
                "filename": "file_for_appelete_action"
            },
            allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
            browseOnZoneClick: true,
            initialPreviewAsData: false,
            showUpload: false
        }).on("filebatchselected", function(event, files) {
            $el2.fileinput("upload");
        });
        refreshProcess();
        <?php
        if(isset($appealApplication[0]->ref_appeal_id)){
        ?>
        refreshPreviousProcess();
        <?php
        }
        ?>
        select2Ref.select2();
        processActionFormRef.parsley();
        appellateFormRef.parsley();
        <?php if ($isCurrentUserDPS) { // ,new ObjectId($latestForwardedUserId)
            // &&
            //    $appealApplicationProcesses->{$processLastCount}->action == 'seek-info' &&
            //    property_exists($appealApplicationProcesses->{$processLastCount},'notifiable_dps') &&
            //    $appealApplicationProcesses->{$processLastCount}->notifiable_dps == new ObjectId($this->session->userdata('userId')->{'$id'} )
        ?>
            processSubmitRef.click(function() {
                if (processActionFormRef.parsley().validate()) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirm!'
                    }).then((result) => {
                        if (result.value) {

                            showLoadingActionButton();
                            readonlyActionFormForDPS();
                            processSubmitRef.prop('disabled', true);
                            $.ajax({
                                url: processActionUrl,
                                type: 'POST',
                                dataType: 'json',
                                data: processActionFormRef.serialize(),
                                success: function(response) {
                                    // console.log('response');
                                    if (response.success) {
                                        // console.log('if')

                                        // console.log('success');
                                        // if (remarkRef.prop('checked')) {
                                        //     remarkRef.prop('disabled', true);
                                        // }
                                        // if (replyRef.prop('checked')) {
                                        //     replyRef.prop('disabled', true);
                                        // }
                                        // processActionFormMsgContainerRef.html('' +
                                        //     '<div class="alert alert-success">\n' +
                                        //     '        <a class="close" data-dismiss="alert" title="close">x</a>' +
                                        //     response.msg +
                                        //     '</div>'
                                        // );
                                        Swal.fire('Success', response.msg, 'success')
                                        $('#dps_file_upload').fileinput('destroy').fileinput({
                                            showPreview: false
                                        });
                                        refreshProcess();
                                        processActionFormRef.trigger("reset");
                                    } else {
                                        // console.log('else')
                                        // console.log('fail');
                                        processSubmitRef.prop('disabled', false);
                                        // processActionFormMsgContainerRef.html('' +
                                        //     '<div class="alert alert-danger">\n' +
                                        //     '        <a class="close" data-dismiss="alert" title="close">x</a>' +
                                        //     response.validation_errors +
                                        //     '</div>'
                                        // );
                                        Swal.fire('Success', response.validation_errors, 'success')
                                    }
                                    // console.log('out')
                                    if (response.dps_action_done) {
                                        // console.log(dpsActionBlockRef);
                                        dpsActionBlockRef.fadeOut("slow");
                                    }
                                    console.log('success')
                                },
                                error: function() {
                                    // processActionFormMsgContainerRef.html('' +
                                    //     '<div class="alert alert-danger">\n' +
                                    //     '        <a class="close" data-dismiss="alert" title="close">x</a>' +
                                    //     '           Failed to submit action! Please try again.' +
                                    //     '</div>'
                                    // );
                                    Swal.fire('Success', 'Failed to submit action! Please try again.', 'success')
                                    console.log('error')
                                },
                            }).always(function() {
                                console.log('always')
                                hideLoadingActionButton();
                                if ($.inArray(action, ['forward', 'reject', 'resolved']) === -1) {
                                    enableActionFormForDPS();
                                    processSubmitRef.prop('disabled', false);
                                } else {
                                    readonlyActionFormForDPS();
                                    processSubmitRef.prop('disabled', true);
                                }
                            });
                        }
                    });
                }
            });

            function enableActionFormForDPS() {
                remarkRef.prop('readonly', false);
                replyRef.prop('readonly', false);
                remarksRef.prop('readonly', false);
                forwardToRef.prop('readonly', false);
            }

            function readonlyActionFormForDPS() {
                remarkRef.prop('readonly', true);
                replyRef.prop('readonly', true);
                remarksRef.prop('readonly', true);
                forwardToRef.prop('readonly', true);
            }
        <?php
        }
        ?>

        function showLoadingActionButton() {
            if (actionSubmitProgressRef.hasClass('d-none')) {
                actionSubmitProgressRef.removeClass('d-none');
            }
            if (!submitBtnTxtRef.hasClass('d-none')) {
                submitBtnTxtRef.addClass('d-none');
            }
        }

        function hideLoadingActionButton() {
            if (!actionSubmitProgressRef.hasClass('d-none')) {
                actionSubmitProgressRef.addClass('d-none');
            }
            if (submitBtnTxtRef.hasClass('d-none')) {
                submitBtnTxtRef.removeClass('d-none');
            }
        }

        actionRefByName.change(function() {
            if ($(this).val() === 'penalize') {
                penaltyBoxRef.removeClass('d-none');
            } else {
                penaltyBoxRef.addClass('d-none');
            }
            if ($(this).val() === 'forward') {
                forwardToBoxRef.removeClass('d-none');
            } else {
                forwardToBoxRef.addClass('d-none');
            }
        });

        <?php if (!empty(array_intersect($activeProcessUserRoleArray,['DA','AA','RA','DPS']))) {  // ,new ObjectId($latestForwardedUserId)
        ?>

            function enableActionFormForAppellate() {
                remarkRef.prop('readonly', false);
                replyRef.prop('readonly', false);
                resolvedRef.prop('readonly', false);
                inProgressRef.prop('readonly', false);
                rejectedRef.prop('readonly', false);
                penalizeRef.prop('readonly', false);
                forwardToRef.prop('readonly', false);
                penaltyAmountRef.prop('readonly', false);
                remarksRef.prop('readonly', false);
                // actionRef.select2('readonly', false);
            }

            function readonlyActionFormForAppellate() {
                remarkRef.prop('readonly', true);
                replyRef.prop('readonly', true);
                resolvedRef.prop('readonly', true);
                inProgressRef.prop('readonly', true);
                rejectedRef.prop('readonly', true);
                penalizeRef.prop('readonly', true);
                forwardToRef.prop('readonly', true);
                penaltyAmountRef.prop('readonly', true);
                remarksRef.prop('readonly', true);
                // actionRef.select2('readonly', false);
            }

            processSubmitRef.click(function() {

                var action = actionRef.val();

                if ($.inArray(action, ['issue-order', 'seek-info', 'forward', 'in-progress']) === -1 && orderNoRef.length && !orderNoRef.val().length) {
                    orderNoRef.prop('required', true);
                }
                if (action === 'penalize' && !penaltyShouldPayWithinDaysRef.val().length) {
                    penaltyShouldPayWithinDaysRef.prop('required', true);
                }
                if (action === 'penalize' && !certificateIssuedWithinDaysRef.val().length) {
                    certificateIssuedWithinDaysRef.prop('required', true);
                }
                let excludedInputs = actionWiseInputFilter();
                // console.log(excludedInputs);return ;
                if (appellateFormRef.parsley({
                        excluded: excludedInputs
                    }).validate()) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirm!'
                    }).then((result) => {
                        if (result.value) {
                            let formData;
                            if (action === 'forward') {
                                formData = appellateFormRef.serialize()
                            } else {
                                formData = appellateFormRef.find(':not(#forwardTo)').serialize()
                            }

                            showLoadingActionButton();
                            readonlyActionFormForAppellate();

                            processSubmitRef.prop('disabled', true);
                            let processSuccess = false;
                            $.ajax({
                                url: processAppellateActionUrl,
                                type: 'POST',
                                dataType: 'json',
                                data: formData,
                                success: function(response) {
                                    // console.log('response');
                                    if (response.success) {
                                        processSuccess = response.success;
                                        // console.log('success');
                                        // appellateProcessActionFormMsgContainerRef.html('' +
                                        //     '<div class="alert alert-success">\n' +
                                        //     '        <a class="close" data-dismiss="alert" title="close">x</a>' +
                                        //     response.msg +
                                        //     '</div>'
                                        // );
                                        Swal.fire('Success', response.msg, 'success')
                                        if (response.success && $.inArray(actionRefByName.filter(':checked').val(), ['in-progress', 'penalize']) === -1) {

                                            actionRefByName.prop('disabled', true);
                                            processSubmitRef.prop('disabled', true);
                                        } else {
                                            enableActionFormForAppellate();
                                        }
                                        $('#appelete_file_upload').fileinput('destroy').fileinput({
                                            showPreview: false
                                        });
                                        refreshProcess();
                                        appellateFormRef.trigger("reset");
                                    } else {
                                        // console.log('fail');
                                        enableActionFormForAppellate();

                                        Swal.fire('Warning', response.validation_errors, 'warning')
                                        // appellateProcessActionFormMsgContainerRef.html('' +
                                        //     '<div class="alert alert-danger">\n' +
                                        //     '        <a class="close" data-dismiss="alert" title="close">x</a>' +
                                        //     response.validation_errors +
                                        //     '</div>'
                                        // );
                                    }
                                    if (response.dps_action_done) {
                                        // console.log(dpsActionBlockRef);
                                        dpsActionBlockRef.fadeOut("slow");
                                    }
                                    enableActionFormForAppellate();
                                    hideLoadingActionButton();
                                    processSubmitRef.prop('disabled', false);
                                },
                                error: function() {
                                    Swal.fire('Fail', ' Failed to submit action! Please try again.', 'error');
                                    // appellateProcessActionFormMsgContainerRef.html('' +
                                    //     '<div class="alert alert-danger">\n' +
                                    //     '        <a class="close" data-dismiss="alert" title="close">x</a>' +
                                    //     '           Failed to submit action! Please try again.' +
                                    //     '</div>'
                                    // );
                                    hideLoadingActionButton();
                                    enableActionFormForAppellate();
                                }
                            }).always(function() {
                                // console.log(action)
                                // hideLoadingActionButton();
                                // console.log($.inArray(action,['forward','rejected','resolved']))
                                if ($.inArray(action, ['forward', 'rejected', 'resolved']) !== -1 && processSuccess) {
                                    readonlyActionFormForAppellate();
                                    processSubmitRef.prop('disabled', true);
                                    location.reload();
                                } else {
                                    enableActionFormForAppellate();
                                    processSubmitRef.prop('disabled', false);
                                }
                            });
                        }
                    })
                }
            });

            reassignToRef.change(function() {
                let roleSlug = $(this).select2().find(":selected").data("role-slug");
                reassignToRoleSlugRef.val(roleSlug);
            })

        <?php } ?>
    });

    function refreshProcess() {
        processTBodyRef.html('<tr><td colspan="7">\
                                    <div class="d-flex justify-content-center">\
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">\
                                            <span class="sr-only">Loading...</span>\
                                        </div>\
                                    </div>\
                                    </td>\
                                    </tr>');
        $.get(refreshProcessUrl, function(response) {
            processTBodyRef.html(response);
        });
    }
    <?php
    if(isset($appealApplication[0]->ref_appeal_id)){
    ?>
    function refreshPreviousProcess() {
        processPreviousTBodyRef.html('<tr><td colspan="7">\
                                    <div class="d-flex justify-content-center">\
                                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">\
                                            <span class="sr-only">Loading...</span>\
                                        </div>\
                                    </div>\
                                    </td>\
                                    </tr>');
        $.get(refreshPreviousProcessUrl, function(response) {
            processPreviousTBodyRef.html(response);
        });
    }
    <?php
    }
    ?>
    const downloadTemplate = function() {
        let doc = new jsPDF();
        let printableHtml = templatePrintableRef.html();

        var specialElementHandlers = {
            '#elementH': function(element, renderer) {
                return true;
            }
        };
        doc.fromHTML(printableHtml, 15, 15, {
            'width': 170,
            'elementHandlers': specialElementHandlers
        });

        if (templateLinkText === '') {
            templateLinkText = 'template'
        }
        // Save the PDF
        doc.save(templateLinkText.toLowerCase().replace(/ /g, '_') + '.pdf');
    }

    const printTemplate = function() {
        var divToPrint = document.getElementById('templatePrintable');
        var htmlToPrint = '' +
            '<title>' + templateLinkText + '</title>' +
            '<style type="text/css">' +
            'table th, table td {' +
            'border:1px solid #000;' +
            'padding;0.5em;' +
            '}' +
            '</style>';
        htmlToPrint += divToPrint.outerHTML;
        newWin = window.open("");
        newWin.document.write(htmlToPrint);
        newWin.print();
        newWin.close();
    }

    const openActionTemplateModal = function(overrideNotifiable = '') {
        let action                         = actionRef.val();
        var notifiable                     = notifiableRef.val();
        var dateOfHearingRef               = $('#date_of_hearing');
        var orderNoRef                     = $('#order_no');
        var additionalContentRef           = $('#additionalContent');
        var penaltyAmountRef               = $('#penaltyAmount');
        var penaltyShouldPayWithinDaysRef  = $('#penaltyShouldPayWithinDays');
        var certificateIssuedWithinDaysRef = $('#certificateIssuedWithinDays');
        var remarksRef                     = $('#remarks');
        if (notifiable === 'both') {
            notifiable = 'appellant';
        }
        if (overrideNotifiable.length) {
            notifiable = overrideNotifiable;
        }
        let additionalContent = additionalContentRef.val();
        let orderNo = orderNoRef.val();
        if (action === 'hearing' && !dateOfHearingRef.val()) {
            Swal.fire('Warning', 'Please enter date of hearing.', 'warning')
            return;
        }
        if (action === 'second-appeal-upload-rejection-order' && !remarksRef.val()) {
            Swal.fire('Warning', 'Please enter reason for rejection in the remarks box.', 'warning')
            return;
        }
        if (action === 'penalize' && !penaltyAmountRef.val()) {
            Swal.fire('Warning', 'Please enter penalty amount.', 'warning')
            return;
        }
        if (action === 'penalize' && (!penaltyShouldPayWithinDaysRef.val() || isNaN(penaltyShouldPayWithinDaysRef.val()))) {
            Swal.fire('Warning', 'Please enter penalty should be paid within (in number format).', 'warning')
            return;
        }
        if (action === 'penalize' && (!certificateIssuedWithinDaysRef.val() || isNaN(certificateIssuedWithinDaysRef.val()))) {
            Swal.fire('Warning', 'Please enter certificate should be issued within (in number format).', 'warning')
            return;
        }
        if (action || (action === 'hearing' && notifiable)) {
            // if ($.inArray(action, ['issue-order', 'seek-info', 'forward', 'in-progress']) === -1 && !orderNo.length) {
            //     Swal.fire('Warning', 'Please enter order no.', 'warning');
            //     orderNoRef.focus();
            //     return;
            // }

            let contentToReplace = {
                "date_of_hearing": dateOfHearingRef.val(),
                "reason_for_rejection": remarksRef.val(),
                "penalty_amount": penaltyAmountRef.val(),
                "penalty_should_by_paid_within_days": penaltyShouldPayWithinDaysRef.val(),
                "certificate_to_be_issued_within_days": certificateIssuedWithinDaysRef.val(),
                "order_no": orderNo,
                "additional_content": additionalContent,
            }
            if($.inArray(action,['upload-hearing-order','second-appeal-upload-hearing-order']) !== -1){
                action = 'generate-hearing-order';
            }

            if($.inArray(action,['upload-disposal-order','second-appeal-upload-disposal-order']) !== -1){
                action = 'generate-disposal-order';
            }
            if($.inArray(action,['upload-rejection-order','second-appeal-upload-rejection-order']) !== -1){
                action = 'generate-rejection-order';
                notifiable = '';
            }
            window.open(fetchTemplateUrl + '?' + jQuery.param({
                action,
                notifiable,
                appeal_id: '<?= $appealApplication[0]->appeal_id ?>',
                appeal_type: 'second',
                contentToReplace
            }), '_blank');

            //$.get(fetchTemplateUrl,{action, notifiable, appeal_id:'<?//=$appealApplication->appeal_id?>//', appeal_type:'first'}).done(function(response){
            //    if(response.success){
            //        actionTemplateModalRef.find('.modal-title').text(firstActionText.replace('View','').replace('template',''));
            //       let generatedTemplate = response.generated_template;
            //        switch (action){
            //            case "hearing":
            //                generatedTemplate = generatedTemplate.replace('{date_for_hearing}',dateOfHearingRef.val());
            //                break;
            //            case "rejected":
            //                generatedTemplate = generatedTemplate.replace('{reason_for_rejection}',remarksRef.val());
            //                break;
            //            case "penalize":
            //                generatedTemplate = generatedTemplate.replace('{penalty_amount}',penaltyAmountRef.val());
            //                generatedTemplate = generatedTemplate.replace('{penalty_should_by_paid_within_days}',penaltyShouldPayWithinDaysRef.val());
            //                generatedTemplate = generatedTemplate.replace('{certificate_to_be_issued_within_days}',certificateIssuedWithinDaysRef.val());
            //                break;
            //        }
            //        if ($.inArray(action, ['in-progress', 'forward','seek-info','issue-order','']) === -1) {
            //            generatedTemplate = generatedTemplate.replace('{order_no}',orderNo);
            //        }
            //        generatedTemplate = generatedTemplate.replace('{additional_content}',additionalContent);
            //
            //        actionTemplateModalRef.find('.modal-body').html(generatedTemplate);
            //        actionTemplateModalRef.modal('show');
            //    }else{
            //        Swal.fire('Error',response.msg,'error');
            //    }
            //}).fail(function(){
            //    Swal.fire('Error','Unable to show template! Please try again.','error')
            //}).always(function(){
            //
            //})
        } else {
            Swal.fire('Warning', 'Please select both action and notifiable', 'warning')
        }
    }
    // const changeAction = function(){
    //   alert(this);
    //   console.log(this.value)
    // }
    if('<?=$this->session->userdata('role')->slug?>' === 'DPS'){
        actionRef.val('dps-reply');
        setTimeout(function(){
            actionRef.trigger('change');
        },1000);
        actionRef.attr('readonly','readonly'); // not working
    }
    $(document).on('change','#action', function(e) { // $('.action-selected')
        var optionSelected = $("option:selected", this);
        var valueSelected = this.value;
        if(valueSelected === ''){
            valueSelected = 'blank_template';
        }
        // console.log(valueSelected);
        var processActionUrl = '<?= base_url('appeal/second/templates/generated_view_action_template') ?>';
        var name = '<?= $dpsDetails->name ?>';
        var forwardAbleUserOptionList = '<?= ($forwardAbleUserOptionList) ?>';
        var reAssignAbleUserOptionList = '<?= ($reAssignAbleUserOptionList) ?>';
        var aaName = '<?= $aaDetails->name ?>';
        var aaUserId = '<?= strval($aaDetails->{'_id'}) ?>';

        var rrName = '<?= $rrDetails->name ?>';
        var rrUserId = '<?= strval($rrDetails->{'_id'}) ?>';
        var raName = '<?= $raDetails->name ?>';
        var raUserId = '<?= strval($raDetails->{'_id'}) ?>';
        var mocUserId = '<?= isset($mocDetails)?strval($mocDetails->{'_id'}):'' ?>';
        var mocName = '<?= isset($mocDetails)?$mocDetails->name:'' ?>';
        var hearingDate = '<?= format_mongo_date(($appealApplication[0]->date_of_hearing ?? $appealApplication[0]->tentative_hearing_date),'d-m-Y') ?>';
        var appealDate = '<?= format_mongo_date($appealApplication[0]->created_at,'Y-m-d') ?>';
        var daArray='<?=json_encode($daArray)?>';
        // action_block

        $.ajax({
            url: processActionUrl,
            type: 'POST',
            data: {
                view: valueSelected,
                name: name,
                forwardAbleUserOptionList: encodeURI(forwardAbleUserOptionList),
                reAssignAbleUserOptionList: encodeURI(reAssignAbleUserOptionList),
                aaName:encodeURI(aaName),
                aaUserId:encodeURI(aaUserId),
                rrName:encodeURI(rrName),
                rrUserId:encodeURI(rrUserId),
                raName:encodeURI(raName),
                raUserId:encodeURI(raUserId),
                mocUserId:encodeURI(mocUserId),
                mocName:encodeURI(mocName),
                hearingDate:encodeURI(hearingDate),
                appealDate:encodeURI(appealDate),
                appealId:encodeURI(appealId),
                daArray:daArray
            },
            beforeSend: function(){
                swal.fire({
                    html: '<h5>Processing...</h5>',
                    showConfirmButton: false,
                    allowOutsideClick: () => !Swal.isLoading(),
                    onOpen: function() {
                        Swal.showLoading();
                    }
                });
            },
            success: function(response) {
                $("#action_block").html(response);
            },
            error: function() {
                console.log('error')
            },
        }).always(function(){
            swal.close();
        });
    });
    const actionWiseInputFilter = function() {
        let action = actionRef.val();
        let excludedInputs = '';
        switch (action) {
            case 'resolved':
            case 'rejected':
                excludedInputs = '#notifiable ,#last_date_of_submission, #date_of_hearing, #penaltyAmount, #penaltyShouldPayWithinDays, #certificateIssuedWithinDays, #forwardTo, #additionalContent';
                break;
            case 'penalized':
                excludedInputs = '#notifiable ,#last_date_of_submission, #date_of_hearing, #forwardTo';
                break;
            case 'issue-order':
                excludedInputs = '#last_date_of_submission, #date_of_hearing, #penaltyAmount, #penaltyShouldPayWithinDays, #certificateIssuedWithinDays, #forwardTo, #additionalContent';
                break;
            case 'seek-info':
                excludedInputs = '#date_of_hearing, #penaltyAmount, #penaltyShouldPayWithinDays, #certificateIssuedWithinDays, #forwardTo, #additionalContent';
                break;
            case 'forward':
                excludedInputs = '#order_no, #notifiable ,#last_date_of_submission, #date_of_hearing, #penaltyAmount, #penaltyShouldPayWithinDays, #certificateIssuedWithinDays, #additionalContent';
                break;
            case 'reassign':
                excludedInputs = '#order_no, #notifiable ,#last_date_of_submission, #date_of_hearing, #penaltyAmount, #penaltyShouldPayWithinDays, #certificateIssuedWithinDays, #forwardTo, #additionalContent';
                break;
            default:
                excludedInputs = '#order_no, #notifiable ,#last_date_of_submission, #date_of_hearing, #penaltyAmount, #penaltyShouldPayWithinDays, #certificateIssuedWithinDays, #forwardTo, #additionalContent';
                break;
        }
        return excludedInputs;
    }


    function openCommentDocModal(processRef) {
        $.get("<?= base_url('appeal/process/show-attachments/') ?>" + processRef, {
                docField: "comment_documents"
            })
            .done(function(response) {
                processDocModalTBodyRef.html(response);
            });
        processDocModalRef.modal('show');
    }

    submitCommentRef.click(function(){
        if(!commentFormRef.parsley().validate()){
            return
        }
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm!'
        }).then((result) => {
            if(result){
                $.ajax({
                    url: commentFormRef.attr('action'),
                    type: 'POST',
                    dataType: 'JSON',
                    data: commentFormRef.serializeArray(),
                    beforeSend: function(){
                        $('#comment').prop("disabled", true);
                        swal.fire({
                            html: '<h5>Processing...</h5>',
                            showConfirmButton: false,
                            allowOutsideClick: () => !Swal.isLoading(),
                            onOpen: function() {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success', 'Comment submitted successfully', 'success');
                            refreshProcess();
                            setTimeout(function(){
                                window.location.reload();
                            },2000)
                        } else {
                            Swal.fire('Failed', 'Unable to submit comment!!!', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Failed', 'Unable to submit comment!!!', 'error');
                    }
                }).always(function(){
                    forwardFormRef.find(":input").prop("disabled", false);
                    swal.close();
                });
            }

        })

    })
    var saveTemplateRef = $('#save_template')
    var subTemplateRef = $('#sub_template')
    var downloadTemplateFormTRef = $('#downloadTemplateForm')

    saveTemplateRef.click(function(){
        let actionUrl = downloadTemplateFormTRef.attr('action')+'/no-download';
        $.ajax({
            url: actionUrl,
            type:'POST',
            dataType:'JSON',
            data:downloadTemplateFormTRef.serialize(),
            beforeSend: function() {
                swal.fire({
                    html: '<h5>Processing...</h5>',
                    showConfirmButton: false,
                    allowOutsideClick: () => !Swal.isLoading(),
                    onOpen: function() {
                        Swal.showLoading();
                    }
                });
            },
            success:function(res){
                swal.close();
                if(res.success){
                    // hearingFormRef.trigger('reset');
                    // $('#action').val('');
                    // $('#action').trigger('change');
                    refreshProcess();
                    Swal.fire('Success',res.msg,'success')

                    setTimeout(function(){
                        window.location.reload();
                    },3000)
                }else{
                    Swal.fire('Error',res.msg,'error')
                }
                $("#viewTemplateModal").modal('hide');
            },
            error:function(){
                swal.close();
                // Swal.fire('Failed','Unable to save order','error')
                $('#viewTemplateModalMsg').html('<div class="alert alert-danger alert-dismissible fade show text-center">Unable to save order <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>')
            }
        })
    })
    subTemplateRef.click(function(){
        downloadTemplateFormTRef.attr('action',downloadTemplateFormTRef.attr('action').replace('/no-download',''));
        downloadTemplateFormTRef.attr('target','_blank')
        downloadTemplateFormTRef.submit();
    })

</script>
