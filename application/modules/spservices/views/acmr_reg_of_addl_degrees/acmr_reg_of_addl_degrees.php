<?php
// pre($dbrow);
$startYear = date('Y') - 10;
$endYear =  date('Y');

if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;

    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $dob = $dbrow->form_data->dob;
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email;
    $pan_no = $dbrow->form_data->pan_no;
    $aadhar_no = $dbrow->form_data->aadhar_no;
    $permanent_addr = $dbrow->form_data->permanent_addr;
    $correspondence_addr = $dbrow->form_data->correspondence_addr;

    $primary_qualification = $dbrow->form_data->primary_qualification;
    $primary_qua_doc = $dbrow->form_data->primary_qua_doc;
    $primary_qua_college_name = $dbrow->form_data->primary_qua_college_name;
    $primary_qua_college_addr = $dbrow->form_data->primary_qua_college_addr;
    $primary_qua_course_dur = $dbrow->form_data->primary_qua_course_dur;
    $primary_qua_doci = $dbrow->form_data->primary_qua_doci;
    $primary_qua_university_award_intership = $dbrow->form_data->primary_qua_university_award_intership;

    $acmrrno = $dbrow->form_data->acmrrno;
    $original_registration_number = $dbrow->form_data->original_registration_number;
    $registration_date = $dbrow->form_data->registration_date;

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

    $photo_of_the_candidate_type = isset($dbrow->form_data->photo_of_the_candidate_type)? $dbrow->form_data->photo_of_the_candidate_type: ""; 
    $photo_of_the_candidate = isset($dbrow->form_data->photo_of_the_candidate)? $dbrow->form_data->photo_of_the_candidate: ""; 
    $pass_certificate_from_uni_coll_type = isset($dbrow->form_data->pass_certificate_from_uni_coll_type)? $dbrow->form_data->pass_certificate_from_uni_coll_type: ""; 
    $pass_certificate_from_uni_coll = isset($dbrow->form_data->pass_certificate_from_uni_coll)? $dbrow->form_data->pass_certificate_from_uni_coll: ""; 
    $pg_degree_dip_marksheet_type = isset($dbrow->form_data->pg_degree_dip_marksheet_type)? $dbrow->form_data->pg_degree_dip_marksheet_type: ""; 
    $pg_degree_dip_marksheet = isset($dbrow->form_data->pg_degree_dip_marksheet)? $dbrow->form_data->pg_degree_dip_marksheet: "";
    $prc_acmr_type = isset($dbrow->form_data->prc_acmr_type)? $dbrow->form_data->prc_acmr_type: ""; 
    $prc_acmr = isset($dbrow->form_data->prc_acmr)? $dbrow->form_data->prc_acmr: "";
    $other_addl_degree_type = isset($dbrow->form_data->other_addl_degree_type)? $dbrow->form_data->other_addl_degree_type: ""; 
    $other_addl_degree = isset($dbrow->form_data->other_addl_degree)? $dbrow->form_data->other_addl_degree: "";
    // $soft_copy_type = isset($dbrow->form_data->soft_copy_type)? $dbrow->form_data->soft_copy_type: ""; 
    // $soft_copy = isset($dbrow->form_data->soft_copy)? $dbrow->form_data->soft_copy: "";
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL;//set_value("rtps_trans_id");
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $dob = set_value("dob");
    $mobile = $this->session->mobile;//set_value("mobile_number");
    $email = set_value("email");
    $permanent_addr = set_value("permanent_addr");
    $correspondence_addr = set_value("correspondence_addr");
    $pan_no = set_value("pan_no");
    $aadhar_no = set_value("aadhar_no");

    $primary_qualification = set_value("primary_qualification");
    $primary_qua_doc = set_value("primary_qua_doc");
    $primary_qua_college_name = set_value("primary_qua_college_name");
    $primary_qua_college_addr = set_value("primary_qua_college_addr");
    $primary_qua_course_dur = set_value("primary_qua_course_dur");
    $primary_qua_doci = set_value("primary_qua_doci");
    $primary_qua_university_award_intership = set_value("primary_qua_university_award_intership");

    $acmrrno = set_value("acmrrno");
    $registration_date = set_value("registration_date");
    $original_registration_number = set_value("original_registration_number");

    $addl_qualification = set_value("addl_qualification");
    $addl_college_name = set_value("addl_college_name");
    $addl_university_name = set_value("addl_university_name");
    $addl_date_of_qualification = set_value("addl_date_of_qualification");

    $add_degree_reg_no = set_value("add_degree_reg_no");

    $photo_of_the_candidate_type = "";
    $photo_of_the_candidate = "";
    $pass_certificate_from_uni_coll_type = "";
    $pass_certificate_from_uni_coll = "";
    $pg_degree_dip_marksheet_type = ""; 
    $pg_degree_dip_marksheet = "";
    $prc_acmr_type = ""; 
    $prc_acmr = "";
    $other_addl_degree_type = ""; 
    $other_addl_degree = "";
    // $soft_copy_type = ""; 
    // $soft_copy = "";
}//End of if else
?>
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
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
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

        $(document).on("click", "#addlatblrow", function(){
            let totRows = $('#addlqualificationtbl tr').length;
            var trow = `<tr>
                            <td><input name="addl_qualification[]" class="form-control" type="text" /></td>
                            <td><input name="addl_college_name[]" class="form-control" type="text"/></td>
                            <td><input name="addl_university_name[]" class="form-control" type="text"/></td>
                            <td><input name="addl_date_of_qualification[]" class="form-control dp" type="text"/></td>
                            <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if(totRows <= 10) {
                $('#addlqualificationtbl tr:last').after(trow);
            }
        });

        $(document).on("click", ".deletetblrow", function () {
            $(this).closest("tr").remove();
            return false;
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/acmr-reg-of-addl-degrees') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
            <input name="photo_of_the_candidate_type" value="<?=$photo_of_the_candidate_type?>" type="hidden" />
            <input name="photo_of_the_candidate" value="<?=$photo_of_the_candidate?>" type="hidden" />
            <input name="pass_certificate_from_uni_coll_type" value="<?=$pass_certificate_from_uni_coll_type?>" type="hidden" />
            <input name="pass_certificate_from_uni_coll" value="<?=$pass_certificate_from_uni_coll?>" type="hidden" />
            <input name="pg_degree_dip_marksheet_type" value="<?=$pg_degree_dip_marksheet_type?>" type="hidden" />
            <input name="pg_degree_dip_marksheet" value="<?=$pg_degree_dip_marksheet?>" type="hidden" />
            <input name="prc_acmr_type" value="<?=$prc_acmr_type?>" type="hidden" />
            <input name="prc_acmr" value="<?=$prc_acmr?>" type="hidden" />
            <?php if(!empty($other_addl_degree_type)){ ?>
            <input name="other_addl_degree_type" value="<?=$other_addl_degree_type?>" type="hidden" />
            <input name="other_addl_degree" value="<?=$other_addl_degree?>" type="hidden" />
            <?php } ?>
            <!-- <input name="soft_copy_type" value="<?=$soft_copy_type?>" type="hidden" />
            <input name="soft_copy" value="<?=$soft_copy?>" type="hidden" /> -->
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm" >
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                Registration of Additional Degrees<br>
                ( অতিৰিক্ত ডিগ্ৰী পঞ্জীয়ন )
                </div>
                <div class="card-body" style="padding:5px">
                    
                    <?php if ($this->session->flashdata('fail') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('error') != null) { ?>
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
                    <?php } ?>
                    
                    <fieldset class="border border-success">
                        <legend class="h5">Important </legend>
                        <strong style="font-size:16px; ">Stipulated time limit for delivery</strong>
                        
                        <ol style="  margin-left: 24px; margin-top: 20px">
                            <li>7 (Seven) working days for Indian Medical Graduates.</li>
                            <li>ভাৰতীয় চিকিৎসা স্নাতকসকলৰ বাবে ৭ (সাত) কৰ্মদিন.</li>
                            <li>45 (Forty-five) working days for Foreign Medical Graduates.</li>
                            <li>বিদেশী চিকিৎসা স্নাতকসকলৰ বাবে ৪৫ (পঞ্চল্লিশ) কৰ্মদিন.</li>
                        </ol>
                        
                        
                        <strong style="font-size:16px;  margin-top: 10px">Fees/Charges</strong>                        
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>User charge / ব্যৱহাৰকাৰী মাচুল – Rs. 2000 / 2০০০ টকা.</li>
                            <li>Service charge (PFC/ CSC) / সেৱা মাচুল (পি. এফ. চি./ চি. এছ. চি.) – Rs. 30 / ৩০ টকা</li>
                            <li>Printing charge (in case of any printing from PFC) / ছপা খৰচ (পি.এফ.চি. ৰ পৰা কোনো ধৰণৰ
                                প্ৰিন্টিঙৰ ক্ষেত্ৰত) - Rs. 10 Per Page / প্ৰতি পৃষ্ঠাত ১০ টকা.</li>
                            <li>Scanning charge (in case documents are scanned in PFC) স্কেনিং খৰচ (যদি নথিপত্ৰসমূহ
                                পি.এফ.চি. ত স্কেন কৰা হয়) - Rs. 5 Per page / প্ৰতি পৃষ্ঠা ৫ টকা.</li>
                        </ul>  
                        
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>                        
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1.  All the * marked fields are mandatory and need to be filled up..</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব</li>
                            <li>2. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য </li>
                        </ul>     

                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details/ আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ  <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control">
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
                                <input class="form-control dp" name="dob" id="dob" value="<?=$dob?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("dob") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile Number/ দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <?php if($usser_type === "user"){ ?>
                                    <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" readonly maxlength="10" />
                               <?php }else{ ?>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" />
                                <?php }?>
                               
                                <?= form_error("mobile") ?>
                            </div>
                        </div>
                    
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>E-Mail/ ই-মেইল <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                            <div class="col-md-6"></div>
                        </div> 

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Permanent Address/ স্থায়ী ঠিকনা <span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="permanent_addr"><?=$permanent_addr?></textarea>
                                <?= form_error("permanent_addr") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Correspondence Address/ চিঠিপত্ৰৰ ঠিকনা <span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="correspondence_addr"><?=$correspondence_addr?></textarea>
                                <?= form_error("correspondence_addr") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Aadhar No./ আধাৰ নং </label>
                                <input class="form-control number_input" name="aadhar_no" value="<?=$aadhar_no?>" maxlength="12" type="text" id="aadhar_no"/>
                                <?= form_error("aadhar_no") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>PAN No./ পেন নং </label>
                                <input class="form-control pan_no" name="pan_no" value="<?=$pan_no?>" maxlength="10" type="text" />
                                <?= form_error("pan_no") ?>
                            </div>
                        </div> 
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Primary Qualification/ প্ৰাথমিক অৰ্হতা </legend>
                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Primary Qualification(MBBS or equivalent)/ প্ৰাথমিক অৰ্হতা(MBBS বা সমতুল্য)<span class="text-danger">*</span></label>
                                <input class="form-control" name="primary_qualification" value="<?=$primary_qualification?>" type="text" />
                                <?= form_error("primary_qualification") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Month & year of passing the Final MBBS Exam/ Final MBBS পৰীক্ষাত উত্তীৰ্ণ হোৱাৰ মাহ & বছৰ <span class="text-danger">*</span></label>
                                <input class="form-control dp" name="primary_qua_doc" value="<?=$primary_qua_doc?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("primary_qua_doc") ?>
                            </div>
                        </div> 

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>College Name/ কলেজৰ নাম <span class="text-danger">*</span></label>
                                <input class="form-control" name="primary_qua_college_name" value="<?=$primary_qua_college_name?>" type="text" />
                                <?= form_error("primary_qua_college_name") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>College Address/ কলেজৰ ঠিকনা <span class="text-danger">*</span></label>
                                <input class="form-control" value="<?=$primary_qua_college_addr?>" name="primary_qua_college_addr" type="text" />
                                <?= form_error("primary_qua_college_addr") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Course Duration/ পাঠ্যক্ৰমৰ সময়সীমা <span class="text-danger">*</span></label>
                                <input class="form-control" name="primary_qua_course_dur" value="<?=$primary_qua_course_dur?>" type="text" />
                                <?= form_error("primary_qua_course_dur") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Date of Completion of Internship/ ইন্টাৰশ্বিপ আৰম্ভ হোৱাৰ তাৰিখ <span class="text-danger">*</span></label>
                                <input class="form-control dp" name="primary_qua_doci" value="<?=$primary_qua_doci?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("primary_qua_doci") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Name of the university awarding the degree/ ডিগ্ৰী প্ৰদান কৰা বিশ্ববিদ্যালয়ৰ নাম <span class="text-danger">*</span> </label>
                                <input class="form-control" name="primary_qua_university_award_intership" value="<?=$primary_qua_university_award_intership?>" type="text" />
                                <?= form_error("primary_qua_university_award_intership") ?>
                            </div> 
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Medical Registration Details/ চিকিৎসা পঞ্জীয়নৰ বিৱৰণ </legend>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Assam Council of Medical Registration Registration Number<span class="text-danger">*</span></label>
                                <input class="form-control" name="acmrrno" value="<?=$acmrrno?>" id="acmrrno" type="text" />
                                <?= form_error("acmrrno") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Registration Date <span class="text-danger">*</span></label>
                                <input class="form-control dp" name="registration_date" value="<?=$registration_date?>" maxlength="10" id="registration_date" type="text" />
                                <?= form_error("registration_date") ?>
                            </div>
                        </div> 

                        <div class="row">
                                                            <!-- Original Registration No. -->
                                <div class="col-md-6 form-group">
                                <label>Original Registration Number <span class="text-danger">*</span></label>
                                <input class="form-control" name="original_registration_number" value="<?=$original_registration_number?>"  id="original_registration_number" type="text" />
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
                                                    <td><input name="addl_qualification[]" value="<?= $addl_qualifications[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="addl_college_name[]" value="<?= $addl_college_names[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="addl_university_name[]" value="<?= $addl_university_names[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="addl_date_of_qualification[]" value="<?= $addl_date_of_qualifications[$i] ?>" class="form-control dp" type="text" /></td>
                                                    <td><?= $btn ?></td>
                                                </tr>
                                                <?php }
                                        } else { ?>
                                            <tr>
                                                <td><input name="addl_qualification[]" class="form-control" type="text" /></td>
                                                <td><input name="addl_college_name[]" class="form-control" type="text" /></td>
                                                <td><input name="addl_university_name[]" class="form-control" type="text" /></td>
                                                <td><input name="addl_date_of_qualification[]" class="form-control dp" type="text" /></td>
                                                <td style="text-align:center">
                                                    <button class="btn btn-info" id="addlatblrow" type="button">
                                                        <i class="fa fa-plus-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                    <?php }//End of if else  ?>
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
                    
                    <!-- <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <span id="captchadiv"></span>
                            <button id="reloadcaptcha" class="btn btn-outline-success" type="button" style="padding:3px 10px; float: right"><i class="fa fa-refresh"></i></button>
                            <input name="inputcaptcha" class="form-control mt-1" placeholder="Enter the text as shown in above image" autocomplete="off" required type="text" maxlength="6" />
                            <?=form_error("inputcaptcha")?>
                        </div>
                        <div class="col-md-4"></div>
                    </div> -->
                     <!-- End of .row --> 
                     
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Next
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>