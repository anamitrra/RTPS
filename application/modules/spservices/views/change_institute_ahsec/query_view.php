<?php
// pre("Hello0");
$currentYear = date('Y');
//$apiServer = $this->config->item('edistrict_base_url');
$apiServer = "https://localhost/wptbcapis/"; //For testing
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $service_name = $dbrow->service_data->service_name;
    $status = $dbrow->service_data->appl_status;

    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender ?? '';
   
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email;
    $comp_permanent_address = $dbrow->form_data->comp_permanent_address;
    $pa_state = $dbrow->form_data->pa_state;
    $pa_district = $dbrow->form_data->pa_district;
    $pa_pincode = $dbrow->form_data->pa_pincode;
    
    $comp_postal_address = $dbrow->form_data->comp_postal_address;
    $pos_state = $dbrow->form_data->pos_state;
    $pos_district = $dbrow->form_data->pos_district;
    $pos_pincode = $dbrow->form_data->pos_pincode;
    
    $ahsec_reg_session = $dbrow->form_data->ahsec_reg_session;
    $ahsec_reg_no = $dbrow->form_data->ahsec_reg_no;
    $ahsec_inst_name = $dbrow->form_data->ahsec_inst_name;    
      
    $college_seaking_adm = $dbrow->form_data->college_seaking_adm ?? '';
    $state_seaking_adm = $dbrow->form_data->state_seaking_adm ?? ''; 
    $reason_seaking_adm = $dbrow->form_data->reason_seaking_adm ?? '';   
    $postal = $dbrow->form_data->postal ?? '';   

    $photo_of_the_candidate_type = $dbrow->form_data->photo_of_the_candidate_type ?? '';
    $photo_of_the_candidate = $dbrow->form_data->photo_of_the_candidate ?? '';
    $candidate_sign_type = $dbrow->form_data->candidate_sign_type ?? '';
    $candidate_sign = $dbrow->form_data->candidate_sign ?? '';
    $hs_one_marksheet_type = $dbrow->form_data->hs_one_marksheet_type ?? '';
    $hs_one_marksheet = $dbrow->form_data->hs_one_marksheet ?? '';
    $hslc_marksheet_type = $dbrow->form_data->hslc_marksheet_type ?? '';
    $hslc_marksheet = $dbrow->form_data->hslc_marksheet ?? '';
    $recom_letter_type = $dbrow->form_data->recom_letter_type ?? '';
    $recom_letter = $dbrow->form_data->recom_letter ?? '';

  
} ?>
<style type="text/css">
    legend {
        display: inline;
        width: auto;
    }
    ol li {
        font-size: 14px;
        font-weight: bold;
    }
</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/plugins/webcamjs/webcam.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/iservices/js/custom/digilocker.js') ?>"></script>

<script type="text/javascript">   
    $(document).ready(function () {        

        $(document).on("click", ".frmbtn", function(){ 
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE') {
                var msg = "Do you want to procced";
            } else if(clickedBtn === 'CLEAR') {
                var msg = "Once you Reset, All filled data will be cleared";
            } else {
                var msg = "";
            }//End of if else            
            Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    if((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
                        $("#myfrm").submit();
                    } else if(clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {}//End of if else
                }
            });
        });    
        
        $(".dp").datepicker({
            format: 'dd/mm/yyyy',
            endDate: '+0d',
            autoclose: true
        });
        
        
        $(document).on("change", "#dob", function(){             
            var dd = $(this).val(); //alert(dd);
            var dateAr = dd.split('/');
            var dob = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];
        });

        $(document).on("keyup", "#pan_no", function(){ 
            if($("#pan_no").val().length > 10) {
                $("#pan_no").val("");
                alert("Please! Enter upto only 10 digit"); 
            }             
        });

        $('.number_input').keypress(function (e) {    
            var charCode = (e.which) ? e.which : event.keyCode    
            if (String.fromCharCode(charCode).match(/[^0-9]/g))    
                return false;                        
        });

        $('#aadhar_no').keyup(function () {    
            if($("#aadhar_no").val().length > 12) {
                $("#aadhar_no").val("");
                alert("Please! Enter upto only 12 digit"); 
            }                        
        });

        $('.pin_code').keyup(function () {    
            if($(".pin_code").val().length > 6) {
                $(".pin_code").val("");
                alert("Please! Enter upto only 6 digit"); 
            }                        
        });

        var photoOfTheCandidate = parseInt(<?=strlen($photo_of_the_candidate)?1:0?>);
    $("#photo_of_the_candidate").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        // required: photoOfTheCandidate ? false : true,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg", "jpeg", "png"]
    });

    var candidateSign = parseInt(<?=strlen($candidate_sign)?1:0?>);
    $("#candidate_sign").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        required: candidateSign ? false : true,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg", "jpeg", "png"]
    });

    var recom_letter = parseInt(<?=strlen($recom_letter)?1:0?>);
    $("#recom_letter").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"]
    });
    var hslc_marksheet = parseInt(<?=strlen($hslc_marksheet)?1:0?>);
    $("#hslc_marksheet").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"]
    });


    var hs_one_marksheet = parseInt(<?=strlen($hs_one_marksheet)?1:0?>);
    $("#hs_one_marksheet").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"]
    });

      
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST"
            action="<?= base_url('spservices/change_institute_ahsec/registration/querysubmit') ?>"
            enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <?php echo $pageTitle ?><br>
                    ( <?php echo $PageTiteAssamese ?> )
                </div>
                <div class="card-body" style="padding:5px">

                    <?php if ($this->session->flashdata('fail') != null) { ?>
                    <script>
                    $(".frmbtn").show();
                    </script>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php }
                    if ($this->session->flashdata('error') != null) { ?>
                    <script>
                    $(".frmbtn").show();
                    </script>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php }
                    if ($this->session->flashdata('success') != null) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php } if($status === 'QS') { ?>
                    <fieldset class="border border-danger" style="margin-top:40px; margin-bottom: 2px">
                        <legend class="h5">QUERY DETAILS </legend>
                        <div class="row">
                            <div class="col-md-12" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                <?=(end($dbrow->processing_history)->remarks)??''?>
                            </div>
                        </div>
                        <span style="float:right; font-size: 12px">
                            Query time :
                            <?=isset(end($dbrow->processing_history)->processing_time)?format_mongo_date(end($dbrow->processing_history)->processing_time):''?>
                        </span>
                    </fieldset>
                    <?php }//End of if ?>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name"
                                    required value="<?=$applicant_name?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>

                            <div class="col-md-6">
                                <label>Father&apos;s Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" required class="form-control" name="father_name" id="father_name"
                                    value="<?=$father_name?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mother&apos;s Name/ মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" required class="form-control" name="mother_name" id="mother_name"
                                    value="<?=$mother_name?>" maxlength="255" />
                                <?= form_error("mother_name") ?>
                            </div>

                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" readonly name="mobile" value="<?=$mobile?>"
                                    maxlength="10" />

                                <?= form_error("mobile") ?>
                            </div>

                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল <span class="text-danger">*</span> </label>
                                <input type="email" required class="form-control" name="email" value="<?=$email?>"
                                    maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address / স্থায়ী ঠিকনা </legend>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">

                                    <label>Complete Permanent Address/ সম্পূৰ্ণ স্থায়ী ঠিকনা <span
                                            class="text-danger">*</span></label>

                                    <textarea id="comp_permanent_address" class="form-control"
                                        name="comp_permanent_address" rows="4" required
                                        cols="50"><?=$comp_permanent_address?></textarea>

                                    <?= form_error("comp_permanent_address") ?>
                                </div>

                                <div class="form-group">
                                    <label>Pincode/ পিনকোড<span class="text-danger">*</span> </label>
                                    <input type="number" required class="form-control" name="pa_pincode" id="pa_pincode"
                                        value="<?=$pa_pincode?>" min="100000" maxlength="6" />
                                    <?= form_error("pa_pincode") ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>State / ৰাজ্য <span class="text-danger">*</span></label>
                                    <select class="form-control" name="pa_state" id="pa_state">
                                        <option value="">Please Select</option>
                                        <!-- <option value="Assam" autocomplete="off" selected="selected">Assam</option> -->
                                        <?php foreach($states as $state) { ?>
                                        <option <?=($pa_state == $state->state_name_english) ?'selected':''?>
                                            value="<?php echo $state->state_name_english ?>">
                                            <?php echo $state->state_name_english ?></option>
                                        <?php }?>
                                    </select>
                                    <?= form_error("state") ?>
                                </div>
                                <div class="form-group">
                                    <label>District / জিলা <span class="text-danger">*</span></label>
                                    <select name="pa_district" id="pa_district" class="form-control pa_dists">
                                        <option value="">Select Any One</option>

                                    </select>
                                    <?= form_error("pa_district") ?>
                                </div>
                                </diiv>
                            </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Postal Address / ডাক ঠিকনা </legend>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="checkbox" name="checker" id="checker" />&nbsp;&nbsp;&nbsp;<b>Same as
                                    Permanent Address</b><br /><br />
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Complete Postal Address/ সম্পূৰ্ণ ডাক ঠিকনা <span
                                            class="text-danger">*</span></label>

                                    <textarea id="comp_postal_address" class="form-control" name="comp_postal_address"
                                        rows="4" cols="50"><?=$comp_postal_address?></textarea>

                                    <?= form_error("comp_postal_address") ?>
                                </div>

                                <div class="form-group">
                                    <label>Pincode/ পিনকোড<span class="text-danger">*</span> </label>
                                    <input type="number" class="form-control" name="pos_pincode" id="pos_pincode"
                                        value="<?=$pos_pincode?>" min="100000" maxlength="6" />
                                    <?= form_error("pos_pincode") ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>State / ৰাজ্য <span class="text-danger">*</span></label>
                                    <select class="form-control" name="pos_state" id="pos_state">
                                        <option value="">Please Select</option>
                                        <!-- <option value="Assam" autocomplete="off" selected="selected">Assam</option> -->
                                        <?php foreach($states as $state) { ?>
                                        <option <?=($pos_state == $state->state_name_english) ?'selected':''?>
                                            value="<?php echo $state->state_name_english ?>">
                                            <?php echo $state->state_name_english ?></option>
                                        <?php }?>
                                    </select>
                                    <?= form_error("pos_state") ?>
                                </div>
                                <div class="form-group">
                                    <label>District / জিলা <span class="text-danger">*</span></label>
                                    <select class="form-control pos_dists" name="pos_district" id="pos_district">
                                        <option value="">Select Any One</option>
                                    </select>
                                    <?= form_error("pos_district") ?>
                                </div>
                                </diiv>
                            </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Academic Details / শৈক্ষিক বিৱৰণ </legend>
                        <div class="row form-group">
                            

                        <div class="col-md-6">
                            <label>Valid AHSEC Registrtion Number / বৈধ এ. এইচ. এছ. ই. চি. পঞ্জীয়ন নম্বৰ <span
                                    class="text-danger">*</span> </label>
                            <input type="text" readonly class="form-control" name="ahsec_reg_no" id="ahsec_reg_no"
                                value="<?= $ahsec_reg_no ?>" maxlength="255" />
                            <?= form_error("ahsec_reg_no") ?>
                        </div>
                        <div class="col-md-6">
                            <label>Year of Appearing in H.S. 1st Year Examination / এইচ.এছ.ত হাজিৰ হোৱাৰ বছৰ। ১ম বৰ্ষৰ পৰীক্ষা<span class="text-danger">*</span> </label>
                            <select name="ahsec_reg_session" id="ahsec_reg_session" class="form-control">
                                <option value="">Please Select</option>
                               
                                <?php foreach($sessions as $session) { ?>   
                                <option value="<?php echo $session ?>" <?= ($ahsec_reg_session === $session) ? 'selected' : '' ?>><?php echo $session ?></option>

                                <?php } ?>
                            </select>
                            <?= form_error("ahsec_reg_session") ?>
                        </div>
                            <div class="col-md-6"><br />
                            <label>Name of Institution / প্ৰতিষ্ঠানৰ নাম <span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" name="ahsec_inst_name" id="ahsec_inst_name"
                                value="<?= $ahsec_inst_name ?>" maxlength="255" />
                            <?= form_error("ahsec_inst_name") ?>
                        </div>
                            
                        </div>


                        
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Details of Course Opting to Study Next / পাঠ্যক্ৰমৰ বিৱৰণ পৰৱৰ্তী অধ্যয়ন কৰিবলৈ বাছি লোৱা  </legend>
                        <label>Institute name where seeking admission/প্ৰতিষ্ঠানৰ নাম য'ত নামভৰ্তি বিচাৰিছে <span class="text-danger">*</span></label>

                        <div class="row ">
                            <div class="form-group col-md-6">

                                <input type="text" class="form-control" name="college_seaking_adm"
                                    id="college_seaking_adm" value="<?=$college_seaking_adm?>" />
                                <?= form_error("college_seaking_adm") ?>
                            </div>


                            <div class="form-group col-md-6">
                                <label>State where seeking admission/ ক’ত নামভৰ্তি বিচাৰিছে সেই কথা উল্লেখ কৰক<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="state_seaking_adm" id="state_seaking_adm"
                                    value="<?=$state_seaking_adm?>" maxlength="20" />
                                <?= form_error("state_seaking_adm") ?>
                            </div>

                            <div class="form-group col-md-6">

                                <label>Describe Reason for Changing Institute/ প্ৰতিষ্ঠান সলনি কৰাৰ কাৰণ বৰ্ণনা কৰা <span
                                        class="text-danger">*</span></label>

                                <textarea id="reason_seaking_adm" class="form-control" name="reason_seaking_adm"
                                    rows="4" cols="50"><?=$reason_seaking_adm?></textarea>

                                <?= form_error("reason_seaking_adm") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Delivery Preference / ডেলিভাৰীৰ পছন্দ </legend>
                        <label>How would you want to receive your certificate ? / আপুনি আপোনাৰ
                                     প্ৰমাণপত্ৰ কেনেকৈ লাভ কৰিব বিচাৰিব<span class="text-danger">*</span></label>
                                     <div class="row ">
                            <div class="form-group  col-md-12">                               
                                <input id="postal_from_counter" name="postal" type="radio" class=""
                                    <?php if($postal=='FROM AHSEC COUNTER') echo "checked='checked'"; ?>
                                    value="FROM AHSEC COUNTER" onclick="displayParagraph()" /> FROM AHSEC COUNTER
                                <br />
                                <p style="color: red; font-weight: bold; display:none" id="from_counter">Applicant must
                                    submit the Registration Card at the time of receiving the  Certificate.</p>
                                <br />

                                <input id="postal_in_my_address" name="postal" type="radio" class=""
                                    <?php if($postal=='IN MY POSTAL ADDRESS') echo "checked='checked'"; ?>
                                    value="IN MY POSTAL ADDRESS" onclick="displayParagraph()" /> IN MY POSTAL ADDRESS
                                <!-- <label for="postal" class="">IN MY POSTAL ADDRESS</label> -->
                                <br />
                                <p style="color: red; font-weight: bold; display:none" id="by_post">Applicant must send
                                    the Registration Card via post to AHSEC for receiving the Certificate.</p>
                                <br />
                                <?= form_error("postal") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:0px">
                        <p>If you wish to upload your enclosures/documents from digilocker, please link your digilocker
                            account. Click on the below button to link your account.</p>
                        <p><?php $this->digilocker_api->login_btn();  ?></p>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">ATTACH ENCLOSURE(S) / সংলগ্নক সমূহ </legend>
                        <div class="row mt-3">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Type of Enclosure</th>
                                            <th>Enclosure Document</th>
                                            <th>File/Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Photo of the Candidate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="photo_of_the_candidate_type" class="form-control">
                                                    <option value="Photo of the Candidate"
                                                        <?=($photo_of_the_candidate_type === 'Photo of the Candidate')?'selected':''?>>
                                                        Photo of the Candidate</option>
                                                </select>
                                                <?= form_error("photo_of_the_candidate_type") ?>
                                            </td>
                                            <td>
                                                <input name="photo_of_the_candidate_old"
                                                    value="<?=$photo_of_the_candidate?>" type="hidden" />
                                                <div class="file-loading">
                                                    <input id="photo_of_the_candidate" name="photo_of_the_candidate"
                                                        type="file" />
                                                </div>

                                                <div class="row mt-1">

                                                    <div class="col-sm-4">
                                                        <?php if(strlen($photo_of_the_candidate)){ ?>
                                                        <a href="<?=base_url($photo_of_the_candidate)?>"
                                                            class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>View/Download
                                                        </a>
                                                        <?php }//End of if ?>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div id="live_photo_div" class="row text-center mt-"
                                                            style="display:none;">
                                                            <div id="my_camera" class="col-md-6 text-center"></div>
                                                            <div class="col-md-6 text-center">
                                                                <img id="captured_photo"
                                                                    src="<?=base_url('assets/plugins/webcamjs/no-photo.png')?>"
                                                                    style="width: 320px; height: 240px;" />
                                                            </div>
                                                            <input id="photo_of_the_candidate_data"
                                                                name="photo_of_the_candidate_data" value=""
                                                                type="hidden" />
                                                            <button id="capture_photo" class="btn btn-warning"
                                                                style="margin:2px auto" type="button">Capture
                                                                Photo</button>
                                                        </div>
                                                        <div style="text-align:right">
                                                            <span id="open_camera"
                                                                class="btn btn-sm btn-success text-white"> Capture <img
                                                                    src="<?=base_url('assets/plugins/webcamjs/camera.png')?>"
                                                                    style="width:25px; height: 25px; cursor: pointer" />
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Signature of the Candidate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="candidate_sign_type" class="form-control">
                                                    <option value="Signature of the Candidate"
                                                        <?=($candidate_sign_type === 'Signature of the Candidate')?'selected':''?>>
                                                        Signature of the Candidate</option>
                                                </select>
                                                <?= form_error("candidate_sign_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="candidate_sign" name="candidate_sign" type="file" />
                                                </div>
                                                <?php if(strlen($candidate_sign)){ ?>
                                                <a href="<?=base_url($candidate_sign)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>HSLC Marksheet<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="hslc_marksheet_type" class="form-control">

                                                    <option value="HSLC Marksheet">HSLC Marksheet</option>
                                                </select>
                                                <?= form_error("hslc_marksheet_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hslc_marksheet" name="hslc_marksheet" type="file" />
                                                </div>
                                                <?php if(strlen($hslc_marksheet)){ ?>
                                                <a href="<?=base_url($hslc_marksheet)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>

                                                <input name="hslc_marksheet_old" value="<?=$hslc_marksheet?>"
                                                    type="hidden" />
                                                <input class="hslc_marksheet" type="hidden" name="hs_reg_card_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('hslc_marksheet'); ?>
                                                <?= form_error("hslc_marksheet") ?>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Recommendation Letter<span class="text-danger">*</span>
                                                <br />
                                                <a href="https://ahsecservices.in/pdf_samples/coi_sample.pdf"
                                                    target="_blank" style="color: red;">Download Sample</a>
                                            </td>
                                            <td>
                                                <select name="recom_letter_type" class="form-control">
                                                    <option value="Recommendation Letter">Recommendation Letter</option>
                                                </select>
                                                <?= form_error("recom_letter_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="recom_letter" name="recom_letter" type="file" />
                                                </div>
                                                <?php if(strlen($recom_letter)){ ?>
                                                <a href="<?=base_url($recom_letter)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>

                                                <input name="recom_letter_old" value="<?=$recom_letter?>"
                                                    type="hidden" />
                                                <input class="" type="hidden" name="recom_letter_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('recom_letter'); ?>
                                                <?= form_error("recom_letter") ?>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td> HS 1st Year Marksheet/Valid supporting document<span
                                                    class="text-danger">*</span></td>
                                            <td>
                                                <select name="hs_one_marksheet_type" class="form-control">
                                                    <option value=" HS 1st Year Marksheet/Valid supporting document"> HS
                                                        1st Year Marksheet/Valid supporting document</option>
                                                </select>
                                                <?= form_error("hs_one_marksheet_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hs_one_marksheet" name="hs_one_marksheet" type="file" />
                                                </div>
                                                <?php if(strlen($hs_one_marksheet)){ ?>
                                                <a href="<?=base_url($hs_one_marksheet)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>

                                                <input name="hs_one_marksheet_old" value="<?=$hs_one_marksheet?>"
                                                    type="hidden" />
                                                <input class="hs_one_marksheet" type="hidden" name="hs_reg_card_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('hs_one_marksheet'); ?>
                                                <?= form_error("hs_one_marksheet") ?>

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>

                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <!-- <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button> -->
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-angle-double-right"></i> Submit
                    </button>
                    <!-- <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button> -->
                </div>
                <!--End of .card-footer-->
            </div>
            <!--End of .card-->
        </form>
    </div>

    <script>
    hideshow();

    function hideshow() {

        var fromCounterRadio = document.getElementById("postal_from_counter");
        var inMyPostalAddressRadio = document.getElementById("postal_in_my_address");
        var fromCounterParagraph = document.getElementById("from_counter");
        var byPostParagraph = document.getElementById("by_post");

        if (fromCounterRadio.checked) {
            fromCounterParagraph.style.display = "block";
            byPostParagraph.style.display = "none";
        } else if (inMyPostalAddressRadio.checked) {
            fromCounterParagraph.style.display = "none";
            byPostParagraph.style.display = "block";
        } else {
            fromCounterParagraph.style.display = "none";
            byPostParagraph.style.display = "none";
        }

    }

    function displayParagraph() {
        hideshow();
    }
    </script>
    <!--End of .container-->
</main>