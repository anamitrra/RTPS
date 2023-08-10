<?php $old = $this->session->flashdata('old') ?? null;?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url("assets/plugins/datepicker/datepicker3.css") ?>">

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script src="<?=base_url("assets/plugins/select2/js/select2.min.js")?>"></script>

<style>
    .parsley-errors-list{
        font-size: .9rem;
    }
    legend{
        display: inline;
        width: auto;
    }
    .select2-selection__arrow{
        top: 10px!important;
    }
    .select2-selection__rendered{
        height: 2rem!important;
        padding: .5rem!important;
    }
    .select2-container{
        width: 100%!important;
    }
</style>

<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Grievance Registration</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Grievance Registration</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container">
            <div class="card mt-2 border-0 shadow">
                <div class="card-header bg-info">
                    <h5 class="card-title text-center text-white font-weight-bold m-0">Grievance Registration Form</h5>
                    <small class="float-right text-dark bg-warning px-2">All Fields marked with <span class="text-danger">*</span> are mandatory</small>
                </div>
                <div class="card-body m-0">

                    <?php
                    if ($this->session->flashdata('fail') != null) {
                        ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php
                    }
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
                    <form id="publicGrievanceForm" method="POST" action="<?=base_url('grm/submit')?>" enctype="multipart/form-data">
                        <fieldset class="border border-success p-3">
                            <legend class="h5">Personal and Communication Details</legend>

                            <div class="row form-group">
                                <div class="col-6">
                                    <label for="mobile_number">Mobile Number <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="mobile_number" id="mobile_number"  placeholder="Enter applicant's mobile number and verify" minlength="10" maxlength="10" pattern="^[6-9]\d{9}$" data-parsley-errors-container="#mobile_number_error_container" required>
                                    <small class="text-info">Provide Mobile number in order to receive SMS alerts related to
                                        grievance</small>
                                    <small class="text-danger" id="mobile_number_error_container"></small>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-6">
                                    <label for="name">Name <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter applicant's name" required>
                                </div>
                                <div class="col-6">
                                    <label for="gender">Gender <span class="text-danger">*</span> </label>
                                    <br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="genderMale" value="M" required data-parsley-errors-container="#gender_error_container">
                                        <label class="form-check-label" for="genderMale">Male</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="genderFemale"
                                               value="F" required data-parsley-errors-container="#gender_error_container">
                                        <label class="form-check-label" for="genderFemale">Female</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="gender" id="genderTrans"
                                               value="O" required data-parsley-errors-container="#gender_error_container">
                                        <label class="form-check-label" for="genderTrans">Others</label>
                                    </div>
                                    <br>
                                    <small class="text-danger" id="gender_error_container"></small>
                                    <?=form_error('gender','<small class="text-danger">','</small>')?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-6">
                                    <label for="country">Country <span class="text-danger">*</span> </label>
                                    <input type="text" disabled class="form-control" value="India">
                                </div>
                                <div class="col-6">
                                    <label for="state">State <span class="text-danger">*</span> </label>
                                    <input type="text" disabled class="form-control" value="Assam">

                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-6">
                                    <label for="district">District <span class="text-danger">*</span> </label>
                                    <select class="form-control select2" name="district" id="district" data-parsley-errors-container="#district_error_container" required>
                                        <?php
                                        if(count((array)$districtList)){
                                            ?>
                                            <option value="">Choose One</option>
                                            <?php
                                            foreach ($districtList as $district){
                                                ?>
                                                <option value="<?=$district->{'distcode'}?>"><?=$district->distname?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <small class="text-danger" id="district_error_container"></small>
                                    <?=form_error('district','<small class="text-danger">','</small>')?>
                                </div>
                                <div class="col-6">
                                    <label for="address1">Address 1<span class="text-danger">*</span> </label>
                                    <textarea class="form-control" name="address1" id="address1" rows="1"  placeholder="Enter applicant's Flat/Door/Block No." required></textarea>
                                    <?=form_error('address1','<small class="text-danger">','</small>')?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-6">
                                    <label for="address2">Address 2 </label>
                                    <textarea class="form-control" name="address2" id="address2" rows="1"  placeholder="Enter applicant's Name of Premises, Road/Street"></textarea>
                                    <?=form_error('address2','<small class="text-danger">','</small>')?>
                                </div>
                                <div class="col-6">
                                    <label for="address3">Address 3 </label>
                                    <textarea class="form-control" name="address3" id="address3" rows="1"  placeholder="Enter applicant's Area/Locality"></textarea>
                                    <?=form_error('address3','<small class="text-danger">','</small>')?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-6">
                                    <label for="pincode">Pincode </label>
                                    <input class="form-control" name="pincode" id="pincode"  placeholder="Enter applicant's pincode" maxlength="6" pattern="^[0-9]\d{5}$">
                                    <?=form_error('pincode','<small class="text-danger">','</small>')?>
                                </div>
                                <div class="col-6">
                                    <label for="emailId">Email ID </label>
                                    <input class="form-control" name="emailId" id="emailId"  placeholder="Enter applicant's email ID">
                                    <?=form_error('emailId','<small class="text-danger">','</small>')?>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="border border-success p-3">
                            <legend class="h5">Grievance Details</legend>
                            <?php
                            if(isset($sessionUserRole) && $sessionUserRole->slug == 'HD'){
                                ?>

                                <div class="row form-group">
                                    <div class="col-12">
                                        <label for="">Source of Complaint <span class="text-danger">*</span> </label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="source_of_complaint"
                                                   id="source_of_complaint_complaint_box" value="complaint-box" required data-parsley-errors-container="#source_of_complaint_container">
                                            <label class="form-check-label" for="source_of_complaint_complaint_box">Complaint Box</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="source_of_complaint"
                                                   id="source_of_complaint_email" value="email" required data-parsley-errors-container="#source_of_complaint_container">
                                            <label class="form-check-label" for="source_of_complaint_email">Email</label>
                                        </div>
                                        <br>
                                        <small class="text-danger" id="source_of_complaint_container"></small>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="row form-group">
                                <div class="col-6">
                                    <label for="grievance_category">Grievance Category <span class="text-danger">*</span></label>
                                    <select name="grievance_category" id="grievance_category" class="form-control select2" required>
                                        <option value="">Choose One</option>
                                        <option value="service-related">Services Related</option>
                                        <option value="pfc-related">PFC Related</option>
                                        <option value="portal-related">Portal Related</option>
                                        <option value="others">Others</option>
                                    </select>
                                </div>
                                <div class="col-6 d-none">
                                    <label for="service_name">Name of the Service Applied <span class="text-danger">*</span></label>
                                    <select name="service_name" id="service_name" class="form-control select2">
                                        <option value="">Choose One</option>
                                        <?php
                                        foreach ($serviceList as $service){
                                            ?>
                                            <option value="<?=$service->service_id?>" <?=isset($old['service_name']) && $old['service_name'] == $service->service_id ? 'selected' : ''?>><?=$service->service_name?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-6 d-none">
                                    <label for="concern_authority">Concern Authority</label>
                                    <input type="text" class="form-control" readonly id="concern_authority">
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="grievance_description">Grievance Description <span class="text-danger">*</span></label>
                                    <textarea name="grievance_description" id="grievance_description" class="form-control" placeholder="Write grievance description here" maxlength="4000" required></textarea>
                                    <small class="text-info">Maximum 4000 characters are allowed in description (<span id="char_remaining">4000</span> Remaining )</small>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="grievance_attachments">Attach relevant/Supporting Documents <span id="doc_required"> (if any) </span></label>
                                    <div class="file-loading">
                                        <input type="file" name="grievance_attachments" id="grievance_attachments" class="form-control" >
                                    </div>
                                    <small class="text-info">Only PDF file upto 2 MB allowed.</small>
                                    <?=form_error('grievance_attachments','<small class="text-danger">','</small>')?>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="border border-success p-3">
                            <legend class="h5">Declaration</legend>
                            <p class="text-danger font-weight-bold">
                                <label for="self_declaration">
                                    <input type="checkbox" name="self_declaration" id="self_declaration" data-parsley-error-message="Please check the declaration" required>
                                    I hereby state that the fact mentioned above are true to the best of my knowledge and belief.</label>
                            </p>
                        </fieldset>
                        <div class="d-flex justify-content-center btn-group mt-2" role="group">
                            <button type="button" id="submitGrievanceBtn" class="btn btn-outline-primary mr-2 rounded-0">Submit</button>
                            <a href="" class="btn btn-outline-warning ml-2 rounded-0">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const nameRef      = $('#name');
    const address1Ref  = $('#address1');
    const address2Ref  = $('#address2');
    const address3Ref  = $('#address3');
    const districtRef  = $('#district');
    const emailRef     = $('#emailId')
    const genderMale   = $('#genderMale');
    const genderFemale = $('#genderFemale');
    const genderTrans  = $('#genderTrans');
    const pincodeRef   = $('#pincode');
    const grievanceAttachmentsRef = $('#grievance_attachments');
    const docRequiredRef          = $('#doc_required');
    const grievanceDescriptionRef = $('#grievance_description');
    const charRemainingRef        = $('#char_remaining');
    const publicGrievanceFormRef  = $('#publicGrievanceForm');
    const submitGrievanceBtnRef   = $('#submitGrievanceBtn');
    const mobileNumberRef         = $('#mobile_number');
    const otpRef                  = $('#otp');
    const verifyRef               = $('#verify');
    const submitOtpRef            = $('#submitOtp');
    const resendOtpRef            = $('#resendOtp');
    const verifiedRef             = $('#verified');
    const isRelatedRef            = $('input[name="is_related_to_covid"]');
    const covidCatRef             = $('#covid_cat');
    const datepickerRef           = $('.datepicker');
    const select2Ref              = $('.select2');
    // const exServicemenRef         = $('input[name="ex_servicemen"]');
    // const defenceServicesRef      = $('#defence_services');
    // const serviceNumberRef        = $('#service_number');
    const grievanceCatRef         = $('#grievance_category');
    const serviceNameRef          = $('#service_name');
    const concernAuthorityRef     = $('#concern_authority');
    // const serviceRelatedBlockRef  = $('#serviceRelated');
    const complaintSourceRef      = $('input[name="source_of_complaint"]');
    var isMobileVerified          = "<?= $this->session->userdata('isMobileVerified') ?? false ?>";
    var sendOTPurl                = '<?= base_url('grm/send-otp') ?>';
    var verifyOTPurl              = '<?= base_url('grm/verify') ?>';
    var checkMobileUrl            = '<?= base_url('grm/check-user-data/mobile_number/') ?>';
    var fetchRelatedDeptUrl       = '<?= base_url('grm/related-dept/fetch') ?>';
    $(document).ready(function () {

        datepickerRef.datepicker({
            format: 'dd-mm-yyyy'
        });

        select2Ref.select2();
        publicGrievanceFormRef.parsley();
        submitGrievanceBtnRef.click(function (){
            if(publicGrievanceFormRef.parsley().validate()){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Submit!'
                }).then((result) => {
                    if(result.value){
                        Swal.fire({
                            title: 'Please Wait !',
                            html: 'Data uploading',// add html attribute if you want or remove
                            allowOutsideClick: false,
                            onBeforeOpen: () => {
                                Swal.showLoading();
                            },
                        });
                        publicGrievanceFormRef.submit();
                    }
                });
            }
        });

        mobileNumberRef.change(function(){
            let mobileNumber = $(this).val();
            if(mobileNumber.length === 10){
                $.get(checkMobileUrl+mobileNumber,function(response){
                    if(response.status){
                        nameRef.val(response.name);
                        address1Ref.val(response.address1);
                        address2Ref.val(response.address2);
                        address3Ref.val(response.address3);
                        districtRef.val(response.district);
                        districtRef.select2().trigger('change');
                        emailRef.val(response.email_address);
                        switch (response.gender){
                            case 'M':
                                genderMale.prop('checked',true);
                                break;
                            case 'F':
                                genderFemale.prop('checked',true);
                                break;
                            case 'O':
                                genderTrans.prop('checked',true);
                                break;
                            default:
                                break;
                        }
                        pincodeRef.val(response.pincode);
                    }
                });
            }
        })

        isRelatedRef.change(function (){
            if($(this).val() === 'yes'){
                covidCatRef.closest('.removable').removeClass('d-none');
            }else{
                covidCatRef.closest('.removable').addClass('d-none');
            }
        });

        grievanceCatRef.change(function(){
            if($(this).val() !== 'service-related'){
                serviceNameRef.parent('div').addClass('d-none');
                concernAuthorityRef.parent('div').addClass('d-none');
                serviceNameRef.prop('required',false);
                concernAuthorityRef.prop('required',false);
            }else{
                serviceNameRef.parent('div').removeClass('d-none');
                concernAuthorityRef.parent('div').removeClass('d-none');
                serviceNameRef.prop('required',true);
                concernAuthorityRef.prop('required',true);

            }
        });

        complaintSourceRef.change(function(){
            if($.inArray($(this).val(),['help-desk ','complaint-box','email']) !== -1){
                grievanceAttachmentsRef.prop('required',true);
                docRequiredRef.html('<span class="text-danger">*</span>')
            }else{
                grievanceAttachmentsRef.prop('required',false);
                docRequiredRef.html('if any')
            }
        });
        grievanceDescriptionRef.keyup(charRemaining);

        grievanceDescriptionRef.trigger('keyup');

        serviceNameRef.change(function(){
            $.get(fetchRelatedDeptUrl+'/'+$(this).val(),function(response){
                if(response.status){
                    concernAuthorityRef.val(response.departmentName)
                }else{
                    Swal.fire('Fail','Unable to get Concern Authority','error');
                }
            }).fail(function(){
                Swal.fire('Fail','Unable to get Concern Authority','error');
            });
        });
    });

    const charRemaining = function(){
        let currentLength = $(this).val().length;
        let maxLength     = $(this).attr('maxlength');
        let remaining     = $(this).val().length;
        charRemainingRef.text(maxLength - currentLength);
    }
</script>