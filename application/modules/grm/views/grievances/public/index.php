<?php 
$old = $this->session->flashdata('old') ?? null;
$lang = $this->rtps_lang;
$grievance_category = $old['grievance_category']??'';
$refno = $old['refno']??'';
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url("assets/plugins/datepicker/datepicker3.css") ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets/plugins/datepicker/bootstrap-datepicker.js"); ?>"></script>
<script src="<?=base_url("assets/plugins/select2/js/select2.min.js")?>"></script>

<style type="text/css">
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

<div class="container my-2">
    <div class="card shadow-sm">
        <div class="card-header bg-dark">
            <span class="h5 text-white"><?= isset($form_label->grf->$lang) ? $form_label->grf->$lang : 'Grievance Registration Form' ?></span>
            <span class="float-end text-warning"><?= isset($form_label->afmw->$lang) ? $form_label->afmw->$lang : 'All Fields marked with' ?> <span class="text-danger">*</span> <?= isset($form_label->am->$lang) ? $form_label->am->$lang : 'are mandatory' ?></span>
        </div>
        <div class="card-body">
            <?php
            if ($this->session->flashdata('fail') != null) {
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            }
            if ($this->session->flashdata('error') != null) {
                ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php
            }
            ?>
            <form id="publicGrievanceForm" method="POST" action="<?=base_url('grm/submit')?>" enctype="multipart/form-data">
                <fieldset class="border border-success p-3">
                    <legend class="h5"><?= isset($form_label->pacd->$lang) ? $form_label->pacd->$lang : 'Personal and Communication Details' ?></legend>
                    <hr>

                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label for="mobile_number"><?= isset($form_label->mn->$lang) ? $form_label->mn->$lang : 'Mobile Number' ?> <span class="text-danger">*</span> </label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="mobile_number" id="mobile_number"  placeholder="Enter your mobile number and verify" minlength="10" maxlength="10" pattern="^[6-9]\d{9}$" data-parsley-errors-container="#mobile_number_error_container" value="<?=$old['mobile_number'] ?? ''?>" data-parsley-group="verification" required>
                                <input type="hidden" class="form-control" name="otp" id="otp" placeholder="Enter OTP">
                                <div class="input-group-append">
                                    <a href="javascript:void(0)" class="btn btn-outline-danger" id="verify"><?= isset($form_label->verify->$lang) ? $form_label->verify->$lang : 'Verify' ?></a>
                                    <!--                                    <a href="javascript:void(0)" class="btn btn-outline-primary d-none" id="submitOtp">Submit OTP</a>-->
                                    <!--                                    <a href="javascript:void(0)" class="btn btn-outline-info d-none" id="resendOtp">Resend OTP</a>-->
                                    <a href="javascript:void(0)" class="btn btn-outline-success d-none" id="verified"><i class="fa fa-check"></i></a>
                                </div>
                            </div>
                            <small class="text-info"><?= isset($form_label->pmno->$lang) ? $form_label->pmno->$lang : 'Provide Mobile number in order to receive SMS alerts related to
                                your grievance' ?></small>
                            <small class="text-danger" id="mobile_number_error_container"></small>
                            <?=form_error('mobile_number','<small class="text-danger">','</small>')?>
                        </div>                        
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label for="name"><?= isset($form_label->name->$lang) ? $form_label->name->$lang : 'Name' ?> <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter your name" value="<?=$old['name'] ?? ''?>" required>
                            <?=form_error('name','<small class="text-danger">','</small>')?>
                        </div>
                        <div class="col-sm-6">
                            <label for="gender"><?= isset($form_label->gender->$lang) ? $form_label->gender->$lang : 'Gender' ?>  <span class="text-danger">*</span> </label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="genderMale" value="M" required data-parsley-errors-container="#gender_error_container" <?=!(isset($old['gender']) && $old['gender'] === 'M') ?: 'checked'?>>
                                <label class="form-check-label" for="genderMale"><?= isset($form_label->male->$lang) ? $form_label->male->$lang : 'Male' ?> </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="genderFemale"
                                       value="F" required data-parsley-errors-container="#gender_error_container" <?=!(isset($old['gender']) && $old['gender'] === 'F') ?: 'checked'?>>
                                <label class="form-check-label" for="genderFemale"><?= isset($form_label->female->$lang) ? $form_label->female->$lang : 'Female' ?></label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="gender" id="genderTrans"
                                       value="O" <?=!(isset($old['gender']) && $old['gender'] === 'O') ?: 'checked'?> required data-parsley-errors-container="#gender_error_container">
                                <label class="form-check-label" for="genderTrans"><?= isset($form_label->others->$lang) ? $form_label->others->$lang : 'Others' ?></label>
                            </div>
                            <br>
                            <small class="text-danger" id="gender_error_container"></small>
                            <?=form_error('gender','<small class="text-danger">','</small>')?>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label for="country"><?= isset($form_label->country->$lang) ? $form_label->country->$lang : 'Country' ?> <span class="text-danger">*</span> </label>
                            <input type="text" disabled class="form-control" value="India">
                        </div>
                        <div class="col-sm-6">
                            <label for="state"><?= isset($form_label->state->$lang) ? $form_label->state->$lang : 'State' ?> <span class="text-danger">*</span> </label>
                            <input type="text" disabled class="form-control" value="Assam">

                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label for="district"><?= isset($form_label->district->$lang) ? $form_label->district->$lang : 'District' ?> <span class="text-danger">*</span> </label>
                            <select class="form-control select2" name="district" id="district" data-parsley-errors-container="#district_error_container" required>
                                <?php
                                    if(count((array)$districtList)){
                                ?>
                                        <option value="">Choose One</option>
                                <?php
                                        foreach ($districtList as $district){
                                ?>
                                            <option value="<?=$district->{'distcode'}?>" <?=(isset($old['district']) && $old['district'] == $district->{'distcode'}) ? 'selected' : ''?>><?=$district->distname?></option>
                                <?php
                                        }
                                    }
                                ?>
                            </select>
                            <small class="text-danger" id="district_error_container"></small>
                            <?=form_error('district','<small class="text-danger">','</small>')?>
                        </div>
                        <div class="col-sm-6">
                            <label for="address1"><?= isset($form_label->address1->$lang) ? $form_label->address1->$lang : 'Address 1' ?><span class="text-danger">*</span> </label>
                            <textarea class="form-control" name="address1" id="address1" rows="1"  placeholder="Enter your Flat/Door/Block No." required><?=$old['address1'] ?? ''?></textarea>
                            <?=form_error('address1','<small class="text-danger">','</small>')?>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label for="address2"><?= isset($form_label->address2->$lang) ? $form_label->address2->$lang : 'Address 2' ?> </label>
                            <textarea class="form-control" name="address2" id="address2" rows="1"  placeholder="Enter your Name of Premises, Road/Street"><?=$old['address2'] ?? ''?></textarea>
                            <?=form_error('address2','<small class="text-danger">','</small>')?>
                        </div>
                        <div class="col-sm-6">
                            <label for="address3"><?= isset($form_label->address3 ->$lang) ? $form_label->address3->$lang : 'Address 3 ' ?></label>
                            <textarea class="form-control" name="address3" id="address3" rows="1"  placeholder="Enter your Area/Locality"><?=$old['address3'] ?? ''?></textarea>
                            <?=form_error('address3','<small class="text-danger">','</small>')?>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label for="pincode"><?= isset($form_label->pincode->$lang) ? $form_label->pincode->$lang : 'Pincode' ?>  <span class="text-danger">*</span> </label>
                            <input class="form-control" name="pincode" id="pincode"  placeholder="Enter your pincode" maxlength="6" pattern="^[0-9]\d{5}$" value="<?=$old['pincode'] ?? ''?>" required>
                            <?=form_error('pincode','<small class="text-danger">','</small>')?>
                        </div>
                        <div class="col-sm-6">
                            <label for="emailId"><?= isset($form_label->email->$lang) ? $form_label->email->$lang : 'Email ID' ?></label>
                            <input class="form-control" name="emailId" id="emailId"  placeholder="Enter your email ID" value="<?=$old['emailId'] ?? ''?>" >
                            <?=form_error('emailId','<small class="text-danger">','</small>')?>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="border border-success p-3">
                    <legend class="h5"><?= isset($form_label->gd->$lang) ? $form_label->gd->$lang : 'Grievance Details' ?> </legend>
                    <hr>
                    <?php
                        if(isset($sessionUserRole) && $sessionUserRole->slug == 'HD'){
                    ?>

                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="">Source of Complaint <span class="text-danger">*</span> </label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="source_of_complaint"
                                               id="source_of_complaint_complaint_box" value="complaint-box" required data-parsley-errors-container="#source_of_complaint_container" <?=!(isset($old['source_of_complaint']) && $old['source_of_complaint'] === 'complaint-box' ) ?: 'checked'?>>
                                        <label class="form-check-label" for="source_of_complaint_complaint_box">Complaint Box</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="source_of_complaint"
                                               id="source_of_complaint_email" value="email" <?=!(isset($old['source_of_complaint']) && $old['source_of_complaint'] === 'email' ) ?: 'checked'?> required data-parsley-errors-container="#source_of_complaint_container">
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
                        <div class="col-sm-6">
                            <label for="grievance_category"><?= isset($form_label->gc->$lang) ? $form_label->gc->$lang : 'Grievance Category' ?>  <span class="text-danger">*</span></label>
                            <select name="grievance_category" id="grievance_category" class="form-control select2" required>
                                <option value="">Choose One</option>
                                <option value="Service not delivered" <?=($grievance_category === 'Service not delivered')?'selected': ''?>>Service not delivered</option>
                                <option value="Delayed service delivery" <?=($grievance_category === 'Delayed service delivery')?'selected': ''?>>Delayed service delivery</option>
                                <option value="Other service related issue" <?=($grievance_category === 'Other service related issue')?'selected': ''?>>Other service related issue</option>
                                <option value="PFC operator issue" <?=($grievance_category === 'PFC operator issue')?'selected': ''?>>PFC operator issue</option>
                                <option value="CSC operator issue" <?=($grievance_category === 'CSC operator issue')?'selected': ''?>>CSC operator issue</option>
                                <option value="OTP not working" <?=($grievance_category === 'OTP not working')?'selected': ''?>>OTP not working</option>
                                <option value="Unable to login" <?=($grievance_category === 'Unable to login')?'selected': ''?>>Unable to login</option>
                                <option value="Other technical issue" <?=($grievance_category === 'Other technical issue')?'selected': ''?>>Other technical issue</option>
                            </select>
                        </div>
                        <div class="col-sm-6 d-none">
                            <label for="service_name"><?= isset($form_label->nsa->$lang) ? $form_label->nsa->$lang : 'Name of the Service Applied ' ?> <span class="text-danger">*</span></label>
                            <select name="service_name" id="service_name" class="form-control select2">
                                <option value="">Choose One</option>
                                <?php foreach ($serviceList as $service){
                                    $serviceId = $service->service_id;
                                    $serviceName = $service->service_name;
                                    $excludeServiceNames = array("Issuance of Birth Certificate","Issuance of Delayed Birth Certificate", "Issuance of Death Certificate", "Issuance of Delayed Death Certificate", "Issuance of Disability Certificate");
                                    $excludeServiceIds = array("0076","0077", "0078", "0079", "0056");
                                    if(!in_array($serviceId, $excludeServiceIds)) {
                                        $oldServiceName = $old['service_name']??'';
                                        $selected = ($oldServiceName == $serviceName)? 'selected' : '';
                                        echo '<option value="'.$serviceId.'" '.$selected.'>'.$serviceName.'</option>';
                                    }//End of if
                                }//End of foreach() ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group mt-2 d-none">
                            <label for="concern_authority">Concerned Authority</label>
                            <input type="text" class="form-control" readonly id="concern_authority">
                        </div>
                    </div>
                    <div class="row mt-2 mb-2">
                        <div class="col-md-12 form-group">
                            <label for="grievance_description"><?= isset($form_label->gdes->$lang) ? $form_label->gdes->$lang : 'Grievance Description' ?>  (Characters not allowed -#, $, %, & and *)<span class="text-danger">*</span></label>
                            <textarea name="grievance_description" id="grievance_description" class="form-control" placeholder="Write your description here" maxlength="4000" required  pattern="[^#$%&*]+"><?=$old['grievance_description']??''?></textarea>
                            <small class="text-info"><?= isset($form_label->m4000c->$lang) ? $form_label->m4000c->$lang : 'Maximum 4000 characters are allowed in description' ?>  (<span id="char_remaining">4000</span> Remaining )</small>
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div class="col-md-12 form-group">
                            <label for="refno">Do you have a sewasetu application reference number?<span class="text-danger">*</span> </label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input isrefno" type="radio" name="refno" id="refnoMale" value="YES" required data-parsley-errors-container="#refno_error_container" <?=($refno === 'YES')?'checked':''?>>
                                <label class="form-check-label" for="refnoMale">YES</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input isrefno" type="radio" name="refno" id="refnoFemale" value="NO" required data-parsley-errors-container="#refno_error_container" <?=($refno === 'NO')?'checked':''?>>
                                <label class="form-check-label" for="refnoFemale">NO</label>
                            </div>
                            <br>
                            <small class="text-danger" id="refno_error_container"></small>
                            <?=form_error('refno','<small class="text-danger">','</small>')?>
                        </div>
                    </div>
                    
                    <div class="row mb-2">
                        <div id="rtpsrefno_div" class="col-md-12 form-group" style="display:<?=($refno === 'YES')?'block':'none'?>">
                            <label for="rtpsrefno">
                                Sewasetu application reference number<span class="text-danger">*</span>
                                e.g. RTPS-DEPT/2020/1234567
                            </label>
                            <input name="rtpsrefno" id="rtpsrefno" value="<?=$old['rtpsrefno']??''?>" class="form-control" type="text" />
                            <font id="file_check_status" style="color:red; font-size:12px; font-style:italic; font-weight: bold"></font>
                            <?=form_error('rtpsrefno','<small class="text-danger">','</small>')?>
                        </div>
                    </div>
                                        
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="grievance_attachments"><?= isset($form_label->arsd->$lang) ? $form_label->arsd->$lang : 'Attach relevant/Supporting Documents' ?> <span id="doc_required"> <?= isset($form_label->ifany->$lang) ? $form_label->ifany->$lang : '(if any)' ?> </span></label>
                            <div class="file-loading">
                                <input type="file" name="grievance_attachments" id="grievance_attachments" class="form-control" >
                            </div>
                            <small class="text-info"><?= isset($form_label->opf->$lang) ? $form_label->opf->$lang : 'Only PDF file upto 2 MB allowed.' ?> </small>
                            <?=form_error('grievance_attachments','<small class="text-danger">','</small>')?>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="border border-success p-3">
                    <legend class="h5"><?= isset($form_label->declaration->$lang) ? $form_label->declaration->$lang : 'Declaration' ?> </legend>
                    <hr>
                <p class="text-danger font-weight-bold">
                    <label for="self_declaration">
                        <input type="checkbox" name="self_declaration" id="self_declaration" data-parsley-error-message="Please check the declaration" required>
                        <?= isset($form_label->ihs->$lang) ? $form_label->ihs->$lang : 'I hereby state that the fact mentioned above are true to the best of my knowledge and belief.' ?> </label>
                </p>
                </fieldset>
                <div class="d-flex justify-content-center btn-group mt-2" role="group">
                    <button type="button" id="submitGrievanceBtn" class="btn btn-outline-primary mr-2 rounded-0"><?= isset($form_label->submit->$lang) ? $form_label->submit->$lang : 'Submit' ?> </button>
                    <a href="" class="btn btn-outline-warning ml-2 rounded-0"><?= isset($form_label->back->$lang) ? $form_label->back->$lang : 'Back' ?> </a>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    const nameRef      = $('#name');
    const address1Ref  = $('#address1');
    const address2Ref  = $('#address2');
    const address3Ref  = $('#address3');
    const districtRef  = $('#district');
    const emailRef     = $('#emailId');
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
    const grievanceCatRef         = $('#grievance_category');
    const serviceNameRef          = $('#service_name');
    const concernAuthorityRef     = $('#concern_authority');
    const complaintSourceRef      = $('input[name="source_of_complaint"]');
    var isMobileVerified          = "<?= $this->session->userdata('isMobileVerified') ?? false ?>";
    var sendOTPurl                = '<?= base_url('grm/public/send-otp') ?>';
    var verifyOTPurl              = '<?= base_url('grm/verify') ?>';
    var checkMobileUrl            = '<?= base_url('grm/check-user-data/mobile_number/') ?>';
    var fetchRelatedDeptUrl       = '<?= base_url('grm/related-dept/fetch') ?>';

    $(document).ready(function () {

        datepickerRef.datepicker({
            format: 'dd-mm-yyyy'
        });

        select2Ref.select2();
        publicGrievanceFormRef.parsley();

        setTimeout(function(){
            if(publicGrievanceFormRef.parsley().isValid('verification')){
                mobileNumberRef.trigger('change');
                grievanceCatRef.trigger('change');
                serviceNameRef.trigger('change');
            }

        },1000);

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
                    if (result.value) {
                        if(isMobileVerified){
                            Swal.fire({
                                title: 'Please Wait !',
                                html: 'Data uploading',// add html attribute if you want or remove
                                allowOutsideClick: false,
                                onBeforeOpen: () => {
                                    Swal.showLoading();
                                },
                            });
                            publicGrievanceFormRef.submit();
                        }else{
                            Swal.fire(
                                'Warning!',
                                'Mobile Number not verified.',
                                'warning'
                            );
                        }
                    }
                });
            }
        });
        verifyRef.click(sendOTP);
        resendOtpRef.click(sendOTP);
        submitOtpRef.click(submitOTP);
        isRelatedRef.change(function (){
            if($(this).val() === 'yes'){
                covidCatRef.closest('.removable').removeClass('d-none');
            }else{
                covidCatRef.closest('.removable').addClass('d-none');
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
                        otpRef.attr('type','hidden');
                        verifyRef.addClass('d-none');
                        submitOtpRef.addClass('d-none');
                        resendOtpRef.addClass('d-none');
                        verifiedRef.removeClass('d-none');
                        isMobileVerified = true;
                    }else{
                        otpRef.attr('type','hidden');
                        verifyRef.removeClass('d-none');
                        submitOtpRef.addClass('d-none');
                        resendOtpRef.addClass('d-none');
                        verifiedRef.addClass('d-none');
                    }
                }).fail(function(){
                    otpRef.attr('type','hidden');
                    verifyRef.removeClass('d-none');
                    submitOtpRef.addClass('d-none');
                    resendOtpRef.addClass('d-none');
                    verifiedRef.addClass('d-none');
                });
            }
        });
        
        const cpgramServices = ['Service not delivered', 'Delayed service delivery', 'Other service related issue'];
        grievanceCatRef.change(function(){
            if(cpgramServices.includes($(this).val())){
                serviceNameRef.parent('div').removeClass('d-none');
                concernAuthorityRef.parent('div').removeClass('d-none');
                serviceNameRef.prop('required',true);
            }else{
                serviceNameRef.parent('div').addClass('d-none');
                concernAuthorityRef.parent('div').addClass('d-none');
                serviceNameRef.prop('required',false);
                concernAuthorityRef.prop('required',false);
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

    const sendOTP = function(){
        if(mobileNumberRef.val().length){
            $.ajax({
                type: 'POST',
                url: sendOTPurl,
                dataType: 'json',
                data: {mobile_number: mobileNumberRef.val()},
                beforeSend: function () {
                    swal.fire({
                        html: '<h5>Processing...</h5>',
                        showConfirmButton: false,
                        allowOutsideClick: () => !Swal.isLoading(),
                        onOpen: function () {
                            Swal.showLoading();
                        }
                    });
                },
                success:function(response){
                    swal.close();
                    if(response.status) {
                        Swal.fire({
                            title: 'Enter your OTP',
                            input: 'text',
                            inputAttributes: {
                                autocapitalize: 'off'
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Submit',
                            showLoaderOnConfirm: true,
                            preConfirm: (otp) => {
                                if (otp.length) {
                                    $.post(verifyOTPurl, {mobile_number: mobileNumberRef.val(), otp})
                                        .done(function (response) {
                                            if (response.status) {
                                                otpRef.attr('type', 'hidden');
                                                verifyRef.addClass('d-none');
                                                submitOtpRef.addClass('d-none');
                                                resendOtpRef.addClass('d-none');
                                                verifiedRef.removeClass('d-none');
                                                isMobileVerified = true;
                                                Swal.fire('Success', response.msg, 'success');
                                            } else {
                                                Swal.fire('Error', response.msg, 'error');
                                            }
                                        })
                                        .fail(function () {
                                            Swal.fire('Failed', "Mobile number verification failed!!! Please try again.", 'error');
                                        });
                                } else {
                                    Swal.fire('Warning', "Please enter valid OTP", 'warning');
                                }
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: `${result.value.login}'s avatar`,
                                    imageUrl: result.value.avatar_url
                                });
                            }
                        });
                    }else{
                        otpRef.attr('type','hidden')
                        verifyRef.removeClass('d-none');
                        submitOtpRef.addClass('d-none');
                        resendOtpRef.addClass('d-none');
                        Swal.fire('Error',response.msg,'error');
                    }
                },
                error:function(){
                    Swal.fire('Failed',"Unable to send OTP!!! Please try again.",'error');
                }
            });
        }else{
            Swal.fire('Warning',"Please enter valid mobile number",'warning');
        }
    };

    const submitOTP = function(){
        if(otpRef.val().length){
            $.ajax({
                type: 'POST',
                url: verifyOTPurl,
                dataType: 'json',
                data: {mobile_number:mobileNumberRef.val(),otp: otpRef.val()},
                beforeSend: function() {
                    swal.fire({
                        html: '<h5>Processing...</h5>',
                        showConfirmButton: false,
                        allowOutsideClick: () => !Swal.isLoading(),
                        onOpen: function () {
                            Swal.showLoading();
                        }
                    });
                },
                success:function(response){
                    if(response.status){
                        otpRef.attr('type','hidden');
                        verifyRef.addClass('d-none');
                        submitOtpRef.addClass('d-none');
                        resendOtpRef.addClass('d-none');
                        verifiedRef.removeClass('d-none');
                        isMobileVerified = true;
                        Swal.fire('Success',response.msg,'success');
                    }else{
                        Swal.fire('Error',response.msg,'error');
                    }
                },
                error:function(){
                    Swal.fire('Failed',"Mobile number verification failed!!! Please try again.",'error');
                }
            }).always(function(){
               Swal.close();
            });
        }else{
            Swal.fire('Warning',"Please enter valid OTP",'warning');
        }
    };

    const charRemaining = function(){
        let currentLength = $(this).val().length;
        let maxLength     = $(this).attr('maxlength');
        let remaining     = $(this).val().length;
        charRemainingRef.text(maxLength - currentLength);
    };
    
    $(document).on("change", ".isrefno", function(){
        var isrefno = $(this).val();
        if(isrefno === 'YES') {
            $("#rtpsrefno_div").show();
            $("#rtpsrefno").focus();
        } else {
            $("#rtpsrefno_div").hide();
        }//End of if else
    });
    
    $(document).on("blur", "#rtpsrefno", function(){
        var rtpsrefno = $(this).val();
        if(rtpsrefno.length) {
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "<?=base_url('grm/checkrefno')?>",
                data: {"ref_no": rtpsrefno},
                beforeSend: function () {
                    $("#file_check_status").html("Checking file...");
                },
                success: function (res) {
                    if(res.status) {
                        var fontColor = 'green';
                    } else {
                        var fontColor = 'red';
                        $("#rtpsrefno").val("");
                        $("#rtpsrefno").focus();
                    }//End of if else                    
                    $("#file_check_status").html(res.message);
                    $("#file_check_status").css('color', fontColor);
                }
            });
        }//End of if else
    });
</script>