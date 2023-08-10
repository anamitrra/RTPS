<?php
$old = $this->session->flashdata('old') ?? [];
?>
<style>
    .btn-red {
        background-color: #dc3545;
    }
</style>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>"></script>

<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<link href="<?= base_url('assets/fileupload/themes/explorer-fas/theme.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/themes/explorer-fas/theme.js') ?>" type="text/javascript"></script>
<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Apply For Second Appeal</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Apply for Second appeal</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
<div class="container">
    <div class="card my-2 border-0 shadow">
        <div class="card-header bg-info">
            <h4 class="text-white font-weight-bold text-center">Second Appeal Application Form</h4>
        </div>
        <form id="appealForm" method="POST" action="<?= base_url('appeal/second/submit') ?>">
            <input type="hidden" name="previous_appeal_id" value="<?=$appealApplicationPrevious[0]->appeal_id?>">
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
                    <button type="button" class="btn btn-sm btn-outline-danger mb-2 mr-2 font-weight-bold"
                            data-toggle="modal" data-target="#previousAppealViewModal">
                        View Previous Appeal Details
                    </button>
                    <?php
                    if(isset($applicationData)){
                    ?>
                        <button type="button" class="btn btn-sm btn-outline-danger mb-2 ml-2 font-weight-bold"
                                data-toggle="modal" data-target="#rtpsApplicationViewModal">
                            View RTPS Application
                        </button>
                    <?php
                    }
                    ?>

                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="applicationNumber">Application Number</label>
                            <input type="text" class="form-control" id="applicationNumber" name="applicationNumber"
                                   value="<?= isset($applicationData) ? $applicationData->initiated_data->appl_ref_no : $appealApplicationPrevious[0]->appl_ref_no?>" readonly>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="contactNumber">Contact Number</label>
                            <input type="text" class="form-control" id="contactNumber" name="contactNumber"
                                   value="<?= isset($applicationData->initiated_data->attribute_details->mobile_number           ) ? $applicationData->initiated_data->attribute_details->mobile_number : (isset($appealApplicationPrevious[0]->contact_number)?$appealApplicationPrevious[0]->contact_number: 'NA') ?>"
                                   readonly>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="gender">Gender</label>
                            <input type="text" class="form-control" id="gender" name="gender"
                                   value="<?= isset($appealApplicationPrevious[0]->gender) ? ucfirst($appealApplicationPrevious[0]->gender) : "N/A"?>"
                                   readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nameOfThePerson">Name of the person</label>
                            <input type="text" class="form-control" id="nameOfThePerson" name="nameOfThePerson"
                                   value="<?= isset($applicationData) ? $applicationData->initiated_data->attribute_details->applicant_name :$appealApplicationPrevious[0]->applicant_name ?>" readonly>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <div class="form-inline mb-2">
                                <label for="additionalContactNumber">Additional Contact Number</label>
                                <input type="checkbox" name="contactInAdditionContactNumber"
                                       id="contactInAdditionContactNumber" class="ml-2" <?=$appealApplicationPrevious[0]->contact_in_addition_contact_number?'checked':''?>>
                                <label for="contactInAdditionContactNumber" class="ml-2">
                                    <small class="font-weight-normal">Contact in this number</small>
                                </label>
                                <a href="javascript:void(0)" class="ml-2" data-toggle="tooltip"
                                   title="OTP and Notifications will be sent to this number"><i
                                            class="fa fa-info-circle text-secondary"></i></a>
                            </div>
                            <input type="text" class="form-control" id="additionalContactNumber"
                                   name="additionalContactNumber" minlength="10" maxlength="10" pattern="^[6-9]\d{9}$"
                                   placeholder="Enter additional contact number if applicable" value="<?=isset($appealApplicationPrevious[0]->additional_contact_number)?$appealApplicationPrevious[0]->additional_contact_number:''?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="emailId">Email ID</label>
                            <input type="email" class="form-control" id="emailId" name="emailId" value="<?=isset($appealApplicationPrevious[0]->email_id)?$appealApplicationPrevious[0]->email_id:''?>" readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <div class="form-inline mb-2">
                                <label for="additionalEmailId">Additional Email ID</label>
                                <input type="checkbox" class="ml-2" name="contactInAdditionEmail"
                                       id="contactInAdditionEmail" <?=$appealApplicationPrevious[0]->contact_in_addition_email ? 'checked' :''?>>
                                <label for="contactInAdditionEmail" class="ml-2">
                                    <small class="font-weight-normal">contact in this email</small>
                                </label>
                                <a href="javascript:void(0)" class="ml-2" data-toggle="tooltip"
                                   title="Notifications will be sent to this number"><i
                                            class="fa fa-info-circle text-secondary"></i></a>
                            </div>
                            <input type="email" class="form-control" id="additionalEmailId" name="additionalEmailId"
                                   placeholder="Enter additional email id if applicable" value="<?=isset($appealApplicationPrevious[0]->additional_email_id)?$appealApplicationPrevious[0]->additional_email_id:''?>">
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
                         $this->load->view('includes/address',$appealApplicationPrevious);
                        ?>
                        
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nameOfService">Name of service</label>
                            <input type="text" class="form-control" id="nameOfService" name="nameOfService"
                                   value="<?= isset($applicationData) ? $applicationData->initiated_data->service_name : $appealApplicationPrevious[0]->name_of_service ?>" readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="dateOfApplication">Date of application</label>
                            <input type="text" class="form-control" id="dateOfApplication" name="dateOfApplication"
                                   value="<?=isset($applicationData) ? format_mongo_date($applicationData->initiated_data->submission_date,'d-m-Y'): format_mongo_date($appealApplicationPrevious[0]->date_of_application,'d-m-Y')?>"
                                   readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="dateOfAppeal">Date of appeal (previous)</label>
                            <input type="text" class="form-control" id="dateOfAppeal" name="dateOfAppeal"
                                   value="<?= format_mongo_date($appealApplicationPrevious[0]->created_at) ?>" readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nameOfPFC">Name of the PFC/CSC <small>(if application was submitted through PFC/CSC)</small></label>
                            <input type="text" class="form-control" id="nameOfPFC" name="nameOfPFC" value="<?= $applicationData->initiated_data->pfc_name ?? $appealApplicationPrevious[0]->name_of_PFC ?? 'Not Available' ?>" readonly>
                        </div>
                    </div>
                </div>
                <!-- <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="groundForAppeal">Ground for Appeal</label>
                            <textarea class="form-control" id="groundForAppeal" name="groundForAppeal"
                                      placeholder="Write you ground for Appeal here" required><?=$old['groundForAppeal']??''?></textarea>
                        </div>
                    </div>
                </div> -->
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="groundForAppeal">Ground for Appeal <span class="text-danger">*</span></label>
                            <select class="form-control" name="groundForAppeal" id="groundForAppeal" data-parsley-errors-container="#groundForAppealErroxBox" required>
                                <option value="">Please select an option</option>
                                <option value="Service Delivery Delay"  >Service Delivery Delay</option>
                                <option value="Denial of Service"  >Denial of Service</option>
                                <option value="Reject (non-receipt) of applications">Reject (non-receipt) of applications</option>
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
                            <small class="text-info">Maximum 1500 characters are allowed (<span  class="char-remaining">1500</span> Remaining )</small>
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
                            <small class="text-info">Maximum 1500 characters are allowed (<span  class="char-remaining">1500</span> Remaining )</small>
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
                $reviewing_id = $review->{'_id'}->{'$id'};
                $reviewingAuthorityName = $review->name;
                $reviewingAuthorityDesignation = $review->designation;
                ?>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="DPSName">DPS Name</label>
                            <input type="hidden" class="form-control" id="dps_id" name="dps_id" value="<?= $dps_id ?>"
                                   readonly>
                            <input type="text" class="form-control" id="DPSName" name="DPSName" value="<?= $DPSName ?>"
                                   readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="DPSPosition">DPS Position</label>
                            <input type="text" class="form-control" id="DPSPosition" name="DPSPosition"
                                   value="<?= $DPSPosition ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="appellateAuthorityName">Appellate Authority Name</label>
                            <input type="hidden" class="form-control" id="appellate_id" name="appellate_id"
                                   value="<?= $appellate_id ?>" readonly>
                            <input type="text" class="form-control" id="appellateAuthorityName"
                                   name="appellateAuthorityName" value="<?= $appellateAuthorityName ?>" readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="appellateAuthorityDesignation">Appellate Authority Designation</label>
                            <input type="text" class="form-control" id="appellateAuthorityDesignation"
                                   name="appellateAuthorityDesignation" value="<?= $appellateAuthorityDesignation ?>"
                                   readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="reviewingAuthorityName">Reviewing Authority Name</label>
                            <input type="hidden" class="form-control" id="reviewing_id" name="reviewing_id"
                                   value="<?= $reviewing_id ?>" readonly>
                            <input type="text" class="form-control" id="reviewingAuthorityName"
                                   name="reviewingAuthorityName" value="<?= $reviewingAuthorityName ?>" readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="reviewingAuthorityDesignation">Reviewing Authority Designation</label>
                            <input type="text" class="form-control" id="reviewingAuthorityDesignation"
                                   name="reviewingAuthorityDesignation" value="<?= $reviewingAuthorityDesignation ?>"
                                   readonly>
                        </div>
                    </div>
                </div>
<!--                <div class="row">-->
<!--                    <div class="col-md-6">-->
<!--                        <label for="captcha">Security Code</label>-->
<!--                        <div class="row form-group">-->
<!--                            <div class="col-5 pr-0" id="captchaParent">-->
<!--                                --><?//= $cap['image']; ?>
<!--                            </div>-->
<!--                            <div class="col-1 pl-0">-->
<!--                                <button type="button" class="btn btn-sm btn-outline-info" id="refreshCaptcha">-->
<!--                                    <i class="fa fa-refresh"></i>-->
<!--                                </button>-->
<!--                            </div>-->
<!--                            <div class="col-6">-->
<!--                                <input type="text" class="form-control" name="captcha" id="captcha"-->
<!--                                       placeholder="Enter security code" maxlength="6" required>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
                      <?php if ($delay_reason): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="appealDelayDescription">Reason of delay</label>
                                    <textarea class="form-control" id="appealDelayDescription" name="appealDelayDescription"
                                              placeholder="Write you reason of delay applying" required></textarea>
                                </div>
                            </div>
                        </div>
                      <?php endif; ?>

            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-left">
                    <button type="button" id="saveAndPreview" class="btn btn-outline-primary">Submit Appeal
                    </button>
                    <a class="btn btn-outline-warning ml-2" href="<?= base_url("appeal/userarea") ?>">Close<a>
                </div>
            </div>
        </form>
    </div>

</div>
</div>
</div>

<!-- Modal -->
<?php if(isset($applicationData)){
?>
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
}?>
<!-- Modal -->
<div class="modal fade" id="previousAppealViewModal" tabindex="-1" role="dialog"
     aria-labelledby="previousAppealViewModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previousAppealViewModalLongTitle">Previous Appeal Deatils</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php
                $this->load->view("appeals/view_appeal_application", array('appealApplication' => $appealApplicationPrevious));
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->session->unset_userdata("appeal_attachments");
?>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script>
    const appealAttachmentsRef = $('#appeal_attachments');
    var captchaRef = $('#captcha');
    var captchaParentRef = $('#captchaParent');
    var refreshCaptchaRef = $('#refreshCaptcha');
    const refreshCaptchaURL = '<?=base_url('appeal/refresh-captcha')?>';
    var appealDescriptionRef = $('#appealDescription');
    var reliefSoughtForRef = $('#reliefSoughtFor');

    $(document).ready(function () {
        var appealFormRef = $('#appealForm');
        var saveAndPreviewRef = $('#saveAndPreview');
        appealFormRef.parsley();

        appealDescriptionRef.keyup(charRemaining);
        appealDescriptionRef.trigger('keyup');
        reliefSoughtForRef.keyup(charRemaining);
        reliefSoughtForRef.trigger('keyup');

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
        }).on("filebatchselected", function(event, files) {
            appealAttachmentsRef.fileinput("upload");
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
                    }
                    return false;
                });
            }
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
    });

    const charRemaining = function(){
        let currentLength = $(this).val().length;
        let maxLength     = $(this).attr('maxlength');
        let remaining     = $(this).val().length;
        $(this).parent().find('.char-remaining').text(maxLength - currentLength);
    }
</script>

<!--<script src="--><? //=base_url('assets/frontend/js/appeals/apply_for_appeal.js')?><!--"></script>-->
