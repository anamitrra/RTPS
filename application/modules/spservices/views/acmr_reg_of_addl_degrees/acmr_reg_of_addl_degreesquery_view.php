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

    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $dob = $dbrow->form_data->dob;
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email;
    $permanent_addr = $dbrow->form_data->permanent_addr;
    $correspondence_addr = $dbrow->form_data->correspondence_addr;
    $aadhar_no = $dbrow->form_data->aadhar_no;
    $pan_no = $dbrow->form_data->pan_no;

    $primary_qualification = $dbrow->form_data->primary_qualification;
    $primary_qua_doc = $dbrow->form_data->primary_qua_doc;
    $primary_qua_college_name = $dbrow->form_data->primary_qua_college_name;
    $primary_qua_college_addr = $dbrow->form_data->primary_qua_college_addr;
    $primary_qua_course_dur = $dbrow->form_data->primary_qua_course_dur;
    $primary_qua_doci = $dbrow->form_data->primary_qua_doci;
    $primary_qua_university_award_intership = $dbrow->form_data->primary_qua_university_award_intership;

    $acmrrno = $dbrow->form_data->acmrrno;
    $registration_date = $dbrow->form_data->registration_date;
    $original_registration_number = $dbrow->form_data->original_registration_number;


    $addl_qualification_details = $dbrow->form_data->addl_qualification_details; 
    $addl_qualifications = array();
    $addl_college_names = array();
    $addl_university_names = array();
    $addl_date_of_qualifications = array();
    
    if(count($addl_qualification_details)) {
        foreach($addl_qualification_details as $addl_qualification_detail) {
            //echo "OBJ : ".$plot->patta_no."<br>";
            array_push($addl_qualifications, $addl_qualification_detail->addl_qualification);
            array_push($addl_college_names, $addl_qualification_detail->addl_college_name);
            array_push($addl_university_names, $addl_qualification_detail->addl_university_name);
            array_push($addl_date_of_qualifications, $addl_qualification_detail->addl_date_of_qualification);
        }//End of foreach()
    }//End of if

    $add_degree_reg_no = $dbrow->form_data->add_degree_reg_no;
    
    $photo_of_the_candidate_type_frm = set_value("photo_of_the_candidate_type");
    $signature_of_the_candidate_type_frm = set_value("signature_of_the_candidate_type");
    $pass_certificate_from_uni_coll_type_frm = set_value("pass_certificate_from_uni_coll_type");
    $pg_degree_dip_marksheet_type_frm = set_value("pg_degree_dip_marksheet_type");
    $prc_acmr_type_frm = set_value("prc_acmr_type");
    $other_addl_degree_type_frm = set_value("other_addl_degree_type");
    
    $uploadedFiles = $this->session->flashdata('uploaded_files');
    // pre("$uploadedFiles");
    // return;
    $photo_of_the_candidate_frm = $uploadedFiles['photo_of_the_candidate_old']??null;
    $signature_of_the_candidate_frm = $uploadedFiles['signature_of_the_candidate_old']??null;
    $pass_certificate_from_uni_coll_frm = $uploadedFiles['pass_certificate_from_uni_coll_old']??null;
    $pg_degree_dip_marksheet_frm = $uploadedFiles['pg_degree_dip_marksheet_old']??null;
    $prc_acmr_frm = $uploadedFiles['prc_acmr_old']??null;
    $other_addl_degree_frm = $uploadedFiles['other_addl_degree_old']??null;
    
    $photo_of_the_candidate_type_db = $dbrow->form_data->photo_of_the_candidate_type??null;
    $signature_of_the_candidate_type_db = $dbrow->form_data->signature_of_the_candidate_type??null;
    $pass_certificate_from_uni_coll_type_db = $dbrow->form_data->pass_certificate_from_uni_coll_type??null;
    $pg_degree_dip_marksheet_type_db = $dbrow->form_data->pg_degree_dip_marksheet_type??null;
    $prc_acmr_type_db = $dbrow->form_data->prc_acmr_type??null;
    $other_addl_degree_type_db = $dbrow->form_data->other_addl_degree_type??null;
    
    $photo_of_the_candidate_db = $dbrow->form_data->photo_of_the_candidate??null;
    $signature_of_the_candidate_db = $dbrow->form_data->signature_of_the_candidate??null;
    $pass_certificate_from_uni_coll_db = $dbrow->form_data->pass_certificate_from_uni_coll??null;
    $pg_degree_dip_marksheet_db = $dbrow->form_data->pg_degree_dip_marksheet??null;
    $prc_acmr_db = $dbrow->form_data->prc_acmr??null;
    $other_addl_degree_db = $dbrow->form_data->other_addl_degree??null;
    
    $photo_of_the_candidate_type = strlen($photo_of_the_candidate_type_frm)?$photo_of_the_candidate_type_frm:$photo_of_the_candidate_type_db;
    $signature_of_the_candidate_type = strlen($signature_of_the_candidate_type_frm)?$signature_of_the_candidate_type_frm:$signature_of_the_candidate_type_db;
    $pass_certificate_from_uni_coll_type = strlen($pass_certificate_from_uni_coll_type_frm)?$pass_certificate_from_uni_coll_type_frm:$pass_certificate_from_uni_coll_type_db;
    $pg_degree_dip_marksheet_type = strlen($pg_degree_dip_marksheet_type_frm)?$pg_degree_dip_marksheet_type_frm:$pg_degree_dip_marksheet_type_db;
    $prc_acmr_type = strlen($prc_acmr_type_frm)?$prc_acmr_type_frm:$prc_acmr_type_db;
    $other_addl_degree_type = strlen($other_addl_degree_type_frm)?$other_addl_degree_type_frm:$other_addl_degree_type_db;
    
    $photo_of_the_candidate = strlen($photo_of_the_candidate_frm)?$photo_of_the_candidate_frm:$photo_of_the_candidate_db;
    $signature_of_the_candidate = strlen($signature_of_the_candidate_frm)?$signature_of_the_candidate_frm:$signature_of_the_candidate_db;
    $pass_certificate_from_uni_coll = strlen($pass_certificate_from_uni_coll_frm)?$pass_certificate_from_uni_coll_frm:$pass_certificate_from_uni_coll_db;
    $pg_degree_dip_marksheet = strlen($pg_degree_dip_marksheet_frm)?$pg_degree_dip_marksheet_frm:$pg_degree_dip_marksheet_db;
    $prc_acmr = strlen($prc_acmr_frm)?$prc_acmr_frm:$prc_acmr_db;
    $other_addl_degree = strlen($other_addl_degree_frm)?$other_addl_degree_frm:$other_addl_degree_db;
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
            required: photoOfTheCandidate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg"]
        }); 

        var signatureOfTheCandidate = parseInt(<?=strlen($signature_of_the_candidate)?1:0?>);
        $("#signature_of_the_candidate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: signatureOfTheCandidate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg"]
        }); 

        var passCertificateFromUniColl = parseInt(<?=strlen($pass_certificate_from_uni_coll)?1:0?>);
        $("#pass_certificate_from_uni_coll").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: passCertificateFromUniColl?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var pgDegreeDipMarksheet = parseInt(<?=strlen($pg_degree_dip_marksheet)?1:0?>);
        $("#pg_degree_dip_marksheet").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: pgDegreeDipMarksheet?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var prcAcmr = parseInt(<?=strlen($prc_acmr)?1:0?>);
        $("#prc_acmr").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: prcAcmr?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#other_addl_degree").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/acmr_reg_of_addl_degrees/registration/querysubmit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
            <input name="photo_of_the_candidate_old" value="<?=$photo_of_the_candidate?>" type="hidden" />
            <input name="signature_of_the_candidate_old" value="<?=$signature_of_the_candidate?>" type="hidden" />
            <input name="pass_certificate_from_uni_coll_old" value="<?=$pass_certificate_from_uni_coll?>" type="hidden" />
            <input name="pg_degree_dip_marksheet_old" value="<?=$pg_degree_dip_marksheet?>" type="hidden" />
            <input name="prc_acmr_old" value="<?=$prc_acmr?>" type="hidden" />
            <input name="other_addl_degree_old" value="<?=$other_addl_degree?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <?=$service_name?> 
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
                                Query time : <?=isset(end($dbrow->processing_history)->processing_time)?format_mongo_date(end($dbrow->processing_history)->processing_time):''?>
                            </span>
                        </fieldset>
                    <?php }//End of if ?>
                                        
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details/ আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255" readonly/>
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control" readonly>
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($applicant_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($applicant_gender === "Female")?'selected':''?>>Female</option>
                                    <option value="Transgender" <?=($applicant_gender === "Transgender")?'selected':''?>>Transgender</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6 form-group">
                                <label>Date of Birth/ জন্ম তাৰিখ<span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="dob" id="dob" value="<?=$dob?>" maxlength="10" autocomplete="off" type="text" readonly/>
                                <?= form_error("dob") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile Number/ দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" readonly/>
                                <?= form_error("mobile") ?>
                            </div>
                        </div>
                    
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>E-Mail/ ই-মেইল <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100" readonly/>
                                <?= form_error("email") ?>
                            </div>
                            <div class="col-md-6"></div>
                        </div> 

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Permanent Address/ স্থায়ী ঠিকনা <span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="permanent_addr" readonly><?=$permanent_addr?></textarea>
                                <?= form_error("permanent_addr") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Correspondence Address/ চিঠিপত্ৰৰ ঠিকনা <span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="correspondence_addr" readonly><?=$correspondence_addr?></textarea>
                                <?= form_error("correspondence_addr") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Aadhar No./ আধাৰ নং </label>
                                <input class="form-control number_input" name="aadhar_no" value="<?=$aadhar_no?>" maxlength="12" type="text" id="aadhar_no" readonly/>
                                <?= form_error("aadhar_no") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>PAN No./ পেন নং </label>
                                <input class="form-control pan_no" name="pan_no" value="<?=$pan_no?>" maxlength="10" type="text" readonly/>
                                <?= form_error("pan_no") ?>
                            </div>
                        </div> 
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Primary Qualification/ প্ৰাথমিক অৰ্হতা </legend>
                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Primary Qualification(MBBS or equivalent)/ প্ৰাথমিক অৰ্হতা(MBBS বা সমতুল্য) <span class="text-danger">*</span></label>
                                <input class="form-control" name="primary_qualification" value="<?=$primary_qualification?>" type="text" readonly/>
                                <?= form_error("primary_qualification") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Month & year of passing the Final MBBS Exam/ Final MBBS পৰীক্ষাত উত্তীৰ্ণ হোৱাৰ মাহ & বছৰ <span class="text-danger">*</span></label>
                                <input class="form-control dp" name="primary_qua_doc" value="<?=$primary_qua_doc?>" maxlength="10" autocomplete="off" type="text" readonly/>
                                <?= form_error("primary_qua_doc") ?>
                            </div>
                        </div> 

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>College Name/ কলেজৰ নাম <span class="text-danger">*</span></label>
                                <input class="form-control" name="primary_qua_college_name" value="<?=$primary_qua_college_name?>" type="text" readonly/>
                                <?= form_error("primary_qua_college_name") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>College Address/ কলেজৰ ঠিকনা <span class="text-danger">*</span></label>
                                <input class="form-control" value="<?=$primary_qua_college_addr?>" name="primary_qua_college_addr" type="text" readonly/>
                                <?= form_error("primary_qua_college_addr") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Course Duration/ পাঠ্যক্ৰমৰ সময়সীমা <span class="text-danger">*</span></label>
                                <input class="form-control" name="primary_qua_course_dur" value="<?=$primary_qua_course_dur?>" type="text" readonly/>
                                <?= form_error("primary_qua_course_dur") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Date of Completion of Internship/ ইন্টাৰশ্বিপ আৰম্ভ হোৱাৰ তাৰিখ <span class="text-danger">*</span></label>
                                <input class="form-control dp" name="primary_qua_doci" value="<?=$primary_qua_doci?>" maxlength="10" autocomplete="off" type="text" readonly/>
                                <?= form_error("primary_qua_doci") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Name of the university awarding the degree/ ডিগ্ৰী প্ৰদান কৰা বিশ্ববিদ্যালয়ৰ নাম <span class="text-danger">*</span> </label>
                                <input class="form-control" name="primary_qua_university_award_intership" value="<?=$primary_qua_university_award_intership?>" type="text" readonly/>
                                <?= form_error("primary_qua_university_award_intership") ?>
                            </div> 
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Medical Registration Details/ চিকিৎসা পঞ্জীয়নৰ বিৱৰণ </legend>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Assam Council of Medical Registration Registration Number<span class="text-danger">*</span></label>
                                <input class="form-control" name="acmrrno" value="<?=$acmrrno?>" id="acmrrno" type="text" readonly/>
                                <?= form_error("acmrrno") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Registration Date <span class="text-danger">*</span></label>
                                <input class="form-control dp" name="registration_date" value="<?=$registration_date?>" maxlength="10" id="registration_date" type="text" readonly/>
                                <?= form_error("registration_date") ?>
                            </div>
                        </div> 

                         <div class="row">
                                                            <!-- Original Registration No. -->
                                <div class="col-md-6 form-group">
                                <label>Original Registration Number <span class="text-danger">*</span></label>
                                <input class="form-control" name="original_registration_number" value="<?=$original_registration_number?>"  id="original_registration_number" type="text" readonly/>
                                <?= form_error("original_registration_number") ?>
                            </div>

                        </div>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Additional Qualification Details/ অতিৰিক্ত অৰ্হতাৰ বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="addlqualificationtbl">
                                    <thead>
                                        <tr>
                                            <th>Additional Qualification<span class="text-danger">*</span></th>
                                            <th>College Name* <span class="text-danger">*</span></th>
                                            <th>University Name* <span class="text-danger"></span></th>
                                            <th>Date of Qualification* <span class="text-danger"></span></th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $addl_qualification_details_cnt = (isset($addl_college_names) && is_array($addl_college_names)) ? count($addl_college_names) : 0;
                                        if ($addl_qualification_details_cnt > 0) {
                                            for ($i = 0; $i < $addl_qualification_details_cnt; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="addlatblrow" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button>';
                                                }// End of if else ?>
                                                <tr>
                                                    <td><input name="addl_qualification[]" value="<?= $addl_qualifications[$i] ?>" class="form-control" type="text" readonly/></td>
                                                    <td><input name="addl_college_name[]" value="<?= $addl_college_names[$i] ?>" class="form-control" type="text" readonly/></td>
                                                    <td><input name="addl_university_name[]" value="<?= $addl_university_names[$i] ?>" class="form-control" type="text" readonly/></td>
                                                    <td><input name="addl_date_of_qualification[]" value="<?= $addl_date_of_qualifications[$i] ?>" class="form-control dp" type="text" readonly/></td>
                                                    <td><?= $btn ?></td>
                                                </tr>
                                                <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Additional Degree Registration Number (if any)/ অতিৰিক্ত ডিগ্ৰী পঞ্জীয়ন নম্বৰ (যদি আছে)</label>
                                <input class="form-control" name="add_degree_reg_no" value="<?=$add_degree_reg_no?>" id="add_degree_reg_no" type="text" />
                                <?= form_error("add_degree_reg_no") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                            </div>
                        </div>
                    </fieldset>
                    

                    <fieldset class="border border-success">
                        <legend class="h5">Important </legend>
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>                        
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1.  All the * marked fields are mandatory and need to be filled up..</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব</li>
                            <li>2. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য </li>
                        </ul> 
                    </fieldset>
                                                            
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">ATTACH ENCLOSURE(S) </legend>
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
                                            <td>Photo of the Candidate*.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="photo_of_the_candidate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Photo of the Candidate" <?=($photo_of_the_candidate_type === 'Photo of the Candidate')?'selected':''?>>Photo of the Candidate</option>
                                                </select>
                                                <?= form_error("photo_of_the_candidate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="photo_of_the_candidate" name="photo_of_the_candidate" type="file" />
                                                </div>
                                                <?php if(strlen($photo_of_the_candidate)){ ?>
                                                    <a href="<?=base_url($photo_of_the_candidate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <!-- <td>Signature of the Candidate*.</td>
                                            <td style="font-weight:bold"><?=$signature_of_the_candidate_type?></td> -->
                                            <td>Signature of the Candidate*.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="signature_of_the_candidate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Signature of the Candidate" <?=($signature_of_the_candidate_type === 'Signature of the Candidate')?'selected':''?>>Signature of the Candidate</option>
                                                </select>
                                                <?= form_error("signature_of_the_candidate_type") ?>
                                            </td>
                                            <td>
                                                 <div class="file-loading">
                                                    <input id="signature_of_the_candidate" name="signature_of_the_candidate" type="file" />
                                                </div>
                                                <?php if(strlen($signature_of_the_candidate)){ ?>
                                                    <a href="<?=base_url($signature_of_the_candidate)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Pass Certificate from University/College*.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="pass_certificate_from_uni_coll_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Pass Certificate from University/College" <?=($pass_certificate_from_uni_coll_type === 'Pass Certificate from University/College')?'selected':''?>>Pass Certificate from University/College</option>
                                                </select>
                                                <?= form_error("pass_certificate_from_uni_coll_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="pass_certificate_from_uni_coll" name="pass_certificate_from_uni_coll" type="file" />
                                                </div>
                                                <?php if(strlen($pass_certificate_from_uni_coll)){ ?>
                                                    <a href="<?=base_url($pass_certificate_from_uni_coll)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>PGDegree/DiplomaMarksheet*.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="pg_degree_dip_marksheet_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="PGDegree/DiplomaMarksheet" <?=($pg_degree_dip_marksheet_type === 'PGDegree/DiplomaMarksheet')?'selected':''?>>PGDegree/DiplomaMarksheet</option>
                                                </select>
                                                <?= form_error("pg_degree_dip_marksheet_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="pg_degree_dip_marksheet" name="pg_degree_dip_marksheet" type="file" />
                                                </div>
                                                <?php if(strlen($pg_degree_dip_marksheet)){ ?>
                                                    <a href="<?=base_url($pg_degree_dip_marksheet)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Permanent Registration Certificate of Assam Council of Medical Registration*.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="prc_acmr_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Permanent Registration Certificate of Assam Council of Medical Registration" <?=($prc_acmr_type === 'Permanent Registration Certificate of Assam Council of Medical Registration')?'selected':''?>>Permanent Registration Certificate of Assam Council of Medical Registration</option>
                                                </select>
                                                <?= form_error("prc_acmr_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="prc_acmr" name="prc_acmr" type="file" />
                                                </div>
                                                <?php if(strlen($prc_acmr)){ ?>
                                                    <a href="<?=base_url($prc_acmr)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Other Additional Degrees (If any)</td>
                                            <td>
                                                <select name="other_addl_degree_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Other Additional Degrees (If any)" <?=($other_addl_degree_type === 'Other Additional Degrees (If any)')?'selected':''?>>Other Additional Degrees (If any)</option>
                                                </select>
                                                <?= form_error("other_addl_degree_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="other_addl_degree" name="other_addl_degree" type="file" />
                                                </div>
                                                <?php if(strlen($other_addl_degree)){ ?>
                                                    <a href="<?=base_url($other_addl_degree)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                        
                    <fieldset class="border border-danger table-responsive" style="overflow:hidden">
                        <legend class="h5">Processing history</legend>
                        <table class="table table-bordered bg-white mt-0">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Date &AMP; time</th>
                                    <th>Action taken</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($dbrow->processing_history)) {
                                    foreach($dbrow->processing_history as $key=>$rows) {
                                    $query_attachment = $rows->query_attachment??''; ?>
                                        <tr>
                                            <td><?=sprintf("%02d", $key+1)?></td>
                                            <td><?=date('d/m/Y h:i a', strtotime($this->mongo_db->getDateTime($rows->processing_time)))?></td>
                                            <td><?=$rows->action_taken?></td>
                                            <td><?=$rows->remarks?></td>
                                        </tr>
                                    <?php }//End of foreach()
                                }//End of if else ?>
                            </tbody>
                        </table>
                    </fieldset> 
                </div><!--End of .card-body -->

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
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>