<?php
$old = $this->session->flashdata('old') ?? [];
?>
<style>
    .btn-red {
        background-color: #dc3545;
    }

    #kvFileinputModal embed {
        height: 500px !important;
    }
</style>
<link href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css"/>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<link href="<?= base_url('assets/fileupload/themes/explorer-fas/theme.css') ?>" media="all" rel="stylesheet"
      type="text/css"/>
<script src="<?= base_url('assets/fileupload/themes/explorer-fas/theme.js') ?>" type="text/javascript"></script>
<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Apply For Appeal</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Apply for appeal</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
<div class="container">
    <div class="card my-2 border-0 shadow">
        <div class="card-header bg-info">
            <h4 class="text-white font-weight-bold text-center">Appeal Application Form</h4>
        </div>
        <form id="appealForm" method="POST" action="<?= base_url('appeal/process') ?>">
          <input type="hidden" name="_id" value="<?= $applicationData->_id->{'$id'} ?>">
            <div class="card-body">

                <?php
                if ($this->session->flashdata('error') != null) {
                    ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php
                }
                ?>
                <div class="d-flex justify-content-start">
                    <button type="button" class="btn btn-sm btn-outline-danger mb-2 font-weight-bold"
                            data-toggle="modal" data-target="#rtpsApplicationViewModal">
                        View RTPS Application
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="applicationNumber">Application Number</label>
                            <input type="text" class="form-control" id="applicationNumber" name="applicationNumber"
                                   value="<?= $applicationData->initiated_data->appl_ref_no ?>" readonly>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nameOfThePerson">Name of the Appellant</label>
                            <input type="text" class="form-control" id="nameOfThePerson" name="nameOfThePerson"
                                   value="<?= $applicationData->initiated_data->attribute_details->applicant_name ?>"
                                   readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="gender">Gender <span class="text-danger">*</span> </label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="genderMale" value="male" required data-parsley-errors-container="#gender_error_container">
                            <label class="form-check-label" for="genderMale">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="genderFemale"
                                   value="female" required data-parsley-errors-container="#gender_error_container">
                            <label class="form-check-label" for="genderFemale">Female</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="genderTrans"
                                   value="others" required data-parsley-errors-container="#gender_error_container">
                            <label class="form-check-label" for="genderTrans">Others</label>
                        </div>
                        <br>
                        <small class="text-danger" id="gender_error_container"></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contactNumber">Contact Number</label>
                            <input type="text" class="form-control" id="contactNumber" name="contactNumber"
                                   value="<?= isset($applicationData->initiated_data->attribute_details->mobile_number) ? $applicationData->initiated_data->attribute_details->mobile_number : 'NA' ?>"
                                   readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-inline mb-2">
                                <label for="additionalContactNumber">Additional Contact Number</label>
                                <input type="checkbox" name="contactInAdditionContactNumber"
                                       id="contactInAdditionContactNumber" class="ml-2" <?=isset($old['contactInAdditionContactNumber']) ? 'checked':''?>>
                                <label for="contactInAdditionContactNumber" class="ml-2">
                                    <small class="font-weight-normal">Contact in this number</small>
                                </label>
                                <a href="javascript:void(0)" class="ml-2" data-toggle="tooltip"
                                   title="OTP and Notifications will be sent to this number"><i
                                            class="fa fa-info-circle text-secondary"></i></a>
                            </div>
                            <input type="text" class="form-control" id="additionalContactNumber"
                                   name="additionalContactNumber" minlength="10" maxlength="10" pattern="^[6-9]\d{9}$"
                                   placeholder="Enter additional contact number if applicable"
                                   value="<?= $old['additionalContactNumber']??'' ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="emailId">Email ID</label>
                            <input type="email" class="form-control" id="emailId" value="<?=$applicationData->initiated_data->attribute_details->{'e-mail'}??''?>" name="emailId" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-inline mb-2">
                                <label for="additionalEmailId">Additional Email ID</label>
                                <input type="checkbox" class="ml-2" name="contactInAdditionEmail"
                                       id="contactInAdditionEmail" <?=isset($old['contactInAdditionEmail']) ? 'checked':''?>>
                                <label for="contactInAdditionEmail" class="ml-2">
                                    <small class="font-weight-normal">contact in this email</small>
                                </label>
                                <a href="javascript:void(0)" class="ml-2" data-toggle="tooltip"
                                   title="Notifications will be sent to this number"><i
                                            class="fa fa-info-circle text-secondary"></i></a>
                            </div>
                            <input type="email" class="form-control" id="additionalEmailId" name="additionalEmailId"
                                   placeholder="Enter additional email id if applicable" value="<?= $old['additionalEmailId']??'' ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="addressOfThePerson">Address of the Appellant <span
                                                class="text-danger">*</span></label>
                                    <!-- <textarea type="text" class="form-control" id="addressOfThePerson"
                                              name="addressOfThePerson"
                                              required
                                              placeholder="Enter applicant's address"><?= $old['addressOfThePerson'] ?? '' ?></textarea> -->
                                </div>
                            </div>
                </div>
                <?php 
                $appealApplicationPrevious=array();
                $this->load->view('includes/address',$appealApplicationPrevious);
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nameOfService">Name of service</label>
                            <input type="text" class="form-control" id="nameOfService" name="nameOfService"
                                   value="<?= $applicationData->initiated_data->service_name ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dateOfApplication">Date of application</label>
                            <input type="text" class="form-control" id="dateOfApplication" name="dateOfApplication"
                                   value="<?= date('d-m-Y g:i a', strtotime($this->mongo_db->getDateTime($applicationData->initiated_data->submission_date))); ?>"
                                   readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="dateOfAppeal">Date of appeal</label>
                            <input type="text" class="form-control" id="dateOfAppeal" name="dateOfAppeal"
                                   value="<?= date('d-m-Y') ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nameOfPFC">Name of the PFC/CSC <small>(if application was submitted through PFC/CSC)</small></label>
                            <input type="text" class="form-control" id="nameOfPFC" name="nameOfPFC"
                                   value="<?=$applicationData->initiated_data->pfc_name ?? 'Not Available' ?>"
                                   readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                <div class="col-12">
                        <div class="form-group">
                            <label for="groundForAppeal">Ground for Appeal <span class="text-danger">*</span></label>
                            <select class="form-control" name="groundForAppeal" id="groundForAppeal" data-parsley-errors-container="#groundForAppealErroxBox" required>
                                <option value="">Please select an option</option>
                                <option value="Service Delivery Delay"  <?=(isset($old['groundForAppeal']) && ($old['groundForAppeal'] === 'Service Delivery Delay')) ? 'selected' : '' ?>>Service Delivery Delay</option>
                                <option value="Denial of Service"  <?=(isset($old['groundForAppeal']) && $old['groundForAppeal'] === 'Denial of Service') ? 'selected' : '' ?>>Denial of Service</option>
                                <option value="Reject (non-receipt) of applications" <?=(isset($old['groundForAppeal']) && ($old['groundForAppeal'] === 'Reject (non-receipt) of applications')) ? 'selected' : '' ?>>Refusal to Accept Application</option>
                            </select>
                            <span id="groundForAppealErroxBox"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="appealDescription">Appeal Description <span
                                        class="text-danger">*</span></label>
                            <textarea class="form-control" id="appealDescription" name="appealDescription" required
                                      placeholder="Please provide the details of problem faced while receiving the service" maxlength="1500"><?= $old['appealDescription'] ?? '' ?></textarea>
                            <small class="text-info">Maximum 1500 characters are allowed in description (<span  class="char-remaining">1500</span> Remaining )</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="reliefSoughtFor">Relief sought for <span
                                        class="text-danger">*</span></label>
                            <textarea class="form-control" id="reliefSoughtFor" name="reliefSoughtFor"
                                      required placeholder="Appellant may request for delivery of the service or may request Appellate Authority for necessary action against DPS" maxlength="1500"><?= $old['reliefSoughtFor'] ?? '' ?></textarea>
                            <small class="text-info">Maximum 1500 characters are allowed in description (<span  class="char-remaining">1500</span> Remaining )</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="appeal_attachments">Appeal Attachment (optional)</label>
                            <div class="file-loading">
                                <input id="appeal_attachments" name="appeal_attachments[]" type="file" multiple>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                $dps_id = $dps->{'_id'}->{'$id'};
                $DPSName = $dps->name;
                $DPSPosition = $dps->designation;
                $appellate_id = $appalete->{'_id'}->{'$id'};
                $appellateAuthorityName = $appalete->name;
                $appellateAuthorityDesignation = $appalete->designation;
                ?>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="DPSName">DPS Name</label>
                            <input type="hidden" class="form-control" id="dps_id" name="dps_id" value="<?= $dps_id ?>"
                                   readonly>
                            <input type="text" class="form-control" id="DPSName" name="DPSName" value="<?= $DPSName ?>"
                                   readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="DPSPosition">DPS Position</label>
                            <input type="text" class="form-control" id="DPSPosition" name="DPSPosition"
                                   value="<?= $DPSPosition ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="appellateAuthorityName">Appellate Authority Name</label>
                            <input type="hidden" class="form-control" id="appellate_id" name="appellate_id"
                                   value="<?= $appellate_id ?>" readonly>
                            <input type="text" class="form-control" id="appellateAuthorityName"
                                   name="appellateAuthorityName" value="<?= $appellateAuthorityName ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="appellateAuthorityDesignation">Appellate Authority Designation</label>
                            <input type="text" class="form-control" id="appellateAuthorityDesignation"
                                   name="appellateAuthorityDesignation" value="<?= $appellateAuthorityDesignation ?>"
                                   readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                  <input type="hidden" name="isReasonRequired" value="<?=$isReasonRequired?>">
                  <?php if ($isReasonRequired): ?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="appellateReasonFordelay">Describing the Reason for delay</label>
                            <textarea rows="4" cols="50" class="form-control" id="appellateReasonFordelay" required
                                   name="appellateReasonFordelay" placeholder="Write the reason for delay for appeal ..."><?= isset($appellateReasonFordelay)? $appellateReasonFordelay :'' ?></textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label for="appeal_attachments"> Attachment (optional)</label>
                            <div class="file-loading">
                                <input id="appeal_attachments_delay_reason" name="appeal_attachments_delay_reason[]" type="file" multiple>
                            </div>
                        </div>
                    </div>

                  <?php endif; ?>


                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-left">
                    <button type="button" id="saveAndPreview" class="btn btn-outline-success font-weight-bold">
                        Submit Appeal
                    </button>
                    <a href="<?= base_url("appeal/userarea") ?>" class="btn btn-outline-warning font-weight-bold ml-2">Close</a>
                </div>
            </div>
        </form>
    </div>

</div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="rtpsApplicationViewModal" tabindex="-1" role="dialog"
     aria-labelledby="rtpsApplicationViewModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rtpsApplicationViewModalLongTitle">RTPS Application View</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $this->load->view("applications/view_application", array('data' => $applicationData->initiated_data, 'execution_data' => $applicationData->execution_data));
                ?>
            </div>
        </div>
    </div>
</div>
<?php
$this->session->unset_userdata("appeal_attachments");
$this->session->unset_userdata("appeal_attachments_delay_reason");
?>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script>
    const appealAttachmentsRef = $('#appeal_attachments');
    const appealAttachmentsDelayRef = $('#appeal_attachments_delay_reason');
    var captchaRef = $('#captcha');
    var captchaParentRef = $('#captchaParent');
    var refreshCaptchaRef = $('#refreshCaptcha');
    var appealDescriptionRef = $('#appealDescription');
    var reliefSoughtForRef = $('#reliefSoughtFor');
    const refreshCaptchaURL = '<?=base_url('appeal/refresh-captcha')?>';

    $(document).ready(function () {
        var appealFormRef = $('#appealForm');
        var saveAndPreviewRef = $('#saveAndPreview');
        appealFormRef.parsley();

        appealDescriptionRef.keyup(charRemaining);
        appealDescriptionRef.trigger('keyup');
        reliefSoughtForRef.keyup(charRemaining);
        reliefSoughtForRef.trigger('keyup');

        //appeal attachment File Upload
        appealAttachmentsRef.fileinput({
            theme: 'explorer-fas',
            uploadUrl: '<?= base_url("upload") ?>',
            uploadAsync: true,
            dropZoneEnabled: false,
            overwriteInitial: false,
            maxFileSize: 1000,
            uploadExtraData: {
                "filename": "appeal_attachments"
            },
            allowedFileExtensions: ['jpg','jpeg', 'png', 'gif', 'pdf'],
            browseOnZoneClick: true,
            initialPreviewAsData: false,
            showUpload: false
        }).on("filebatchselected", function (event, files) {
            appealAttachmentsRef.fileinput("upload");
        });
        //delay File Upload
        appealAttachmentsDelayRef.fileinput({
            theme: 'explorer-fas',
            uploadUrl: '<?= base_url("upload") ?>',
            uploadAsync: true,
            dropZoneEnabled: false,
            overwriteInitial: false,
            maxFileSize: 1000,
            uploadExtraData: {
                "filename": "appeal_attachments_delay_reason"
            },
            allowedFileExtensions: ['jpg','jpeg', 'png', 'gif', 'pdf'],
            browseOnZoneClick: true,
            initialPreviewAsData: false,
            showUpload: false
        }).on("filebatchselected", function (event, files) {
            appealAttachmentsDelayRef.fileinput("upload");
        });


        $(document).on('click', '#headingOne', function () {
            $(this).addClass('btn-danger');
            $('#headingTwo').removeClass('btn-danger');
            $('#collapseTwo').removeClass('show');
            $('#collapseOne').addClass('show');
        });
        $(document).on('click', '#headingTwo', function () {
            $(this).addClass('btn-danger');
            $('#headingOne').removeClass('btn-danger');
            $('#headingOne').removeClass('btn-red');
            $('#collapseOne').removeClass('show');
            $('#collapseTwo').addClass('show');
        });


        saveAndPreviewRef.click(function () {
            if (appealFormRef.parsley().validate()) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Once submitted you will not be able to edit this form!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Submit it!'
                }).then((result) => {
                    if (result.value) {
                        appealFormRef.submit();
                        Swal.fire({
                            html: '<h5>Processing...</h5>',
                            showConfirmButton: false,
                            allowOutsideClick: () => !Swal.isLoading(),
                            onOpen: function() {
                                Swal.showLoading();
                            }
                        });
                    }
                    return false;
                });
            }
        });
    });
    refreshCaptchaRef.click(function () {
        console.log('test')
        $.get(refreshCaptchaURL, function (response) {
            if (response.status) {
                captchaParentRef.html(response.captcha.image);
            } else {
                Swal.fire('Failed', 'Failed to refresh captcha!!!', 'error');
            }
        }).fail(function () {
            Swal.fire('Failed', 'Failed to refresh captcha!!!', 'error');
        });
    });
    const charRemaining = function(){
        let currentLength = $(this).val().length;
        let maxLength     = $(this).attr('maxlength');
        let remaining     = $(this).val().length;
        $(this).parent().find('.char-remaining').text(maxLength - currentLength);
    }
</script>

<!--<script src="--><? //=base_url('assets/frontend/js/appeals/apply_for_appeal.js')?><!--"></script>-->
