<?php

use MongoDB\BSON\ObjectId;


$dpsOptions = '';
$appellateOptions = '';
$reviewingOptions = '';
$completeForwardAbleUserOptions = '';
$appeal_id= is_array($appealApplication) ? $appealApplication[0]->appeal_id : $appealApplication->appeal_id;
//pre($forwardAbleUserList);
if (isset($forwardAbleUserList) && count((array)$forwardAbleUserList)) {
    foreach ($forwardAbleUserList as $forwardAbleUser) {
        foreach (getRoles() as $role) {
            if ($role['id'] == $forwardAbleUser->roleId && $this->session->userdata('userId')->{'$id'} != $forwardAbleUser->{'_id'}->{'$id'}) {
                switch (getRoleKeyById($role['id'])) {
                    case 'DPS':
                        $dpsOptions .= '<option value="' . $forwardAbleUser->{'_id'}->{'$id'} . '">' . $forwardAbleUser->name . ' (DPS)</option>';
                        break;
                    case 'AA':
                        $appellateOptions .= '<option value="' . $forwardAbleUser->{'_id'}->{'$id'} . '">' . $forwardAbleUser->name . ' (Appellate Authority)</option>';
                        break;
                    case 'RA':
                        $reviewingOptions .= '<option value="' . $forwardAbleUser->{'_id'}->{'$id'} . '">' . $forwardAbleUser->name . ' (Reviewing Authority)</option>';
                        break;
                    default:
                        break;
                }
            }
        }
    }
    $dpsOptGroup = $dpsOptions != ''?'<optgroup label="DPS">' . $dpsOptions . '</optgroup>':'';
    $appellateOptGroup = $appellateOptions != ''?'<optgroup label="Appellate Authority">' . $appellateOptions . '</optgroup>':'';
    $reviewingOptGroup = $reviewingOptions != ''?'<optgroup label="Reviewing Authority">' . $reviewingOptions . '</optgroup>':'';
    $completeForwardAbleUserOptions = '<option value="">Please select a user</option>'.$dpsOptGroup . $appellateOptGroup . $reviewingOptGroup;
} else {
    $completeForwardAbleUserOptions = '<option value="">No user available</option>';
}

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
                    <div class="card-header p-2" id="headingOne">
                    <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Appeal Details
                </h3>
                        <div class="card-tools p-2">
                  <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                      <button class="btn btn-sm btn-primary" onclick="history.back()">Close</button>
                    </li>
                  </ul>
                </div>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="" data-parent="">
                        <div class="card-body">
                        <?php if(!empty($applicationData)){?>
                            <button type="button" class="btn btn-outline-info mb-3" data-toggle="modal"
                                    data-target="#applicationDetailsModal">
                                View Application Details
                            </button>
                        <?php }?>
                          
                            <?php if (isset($appealApplication->ref_appeal_id)) { ?>
                                <button type="button" class="btn btn-outline-info mb-3" data-toggle="modal"
                                        data-target="#previousAppealDetailsModal">
                                    View Previous Appeal Details
                                </button>
                                <?php
                            }
                            $this->load->view("appeals/view_appeal_application", ['appealApplication' => $appealApplication]);
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
                    <div id="processTBody" class="collapse show" aria-labelledby="headingTrack" data-parent="#accordionExample">
                        <div class="card-body" id="processTBody">

                        </div>
                    </div>
                </div>
                </div>
                </div>
                </div>
                        </div>
                

                        <div class="modal fade" id="applicationDetailsModal" aria-modal="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Application Details</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <?php if(!empty($applicationData)){?>
                  <div class="modal-body">
                  <?php
                  $this->load->view("applications/view_application", array('data' => $applicationData->initiated_data, 'execution_data' => $applicationData->execution_data));
                  ?>
              </div>
               <?php }?>
          
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


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
            </div>
        </div>
    </div>
</div>
<script>
    var processTBodyRef = $('#processTBody');
    var refreshProcessUrl = '<?= base_url('appeal/process/refresh/' . $appeal_id) ?>';
    var processDocModalRef = $('#processDocModal');
    var processDocModalTBodyRef = $('#processDocModalTBody');
    $(function() {

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
    refreshProcess();
    });

    function openCommentDocModal(processRef){
        $.get("<?=base_url('appeal/process/show-attachments/')?>"+processRef,{docField:"comment_documents"})
            .done(function(response){
                processDocModalTBodyRef.html(response);
            });
        processDocModalRef.modal('show');
    }
</script>