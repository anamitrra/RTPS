<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://localhost/wptbcapis/"; //For testing
// pre($dbrow);
//  pre ($form_error);
// echo validation_errors();
$startYear = date('Y') - 10;
$endYear =  date('Y');

if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;

    $applicant_name = $dbrow->form_data->applicant_name;

    $father_name = $dbrow->form_data->father_name;

    $mother_name = $dbrow->form_data->mother_name;

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

    $college_name = $dbrow->form_data->college_name;

    $primary_qua_college_addr = $dbrow->form_data->primary_qua_college_addr;

    $primary_qua_course_dur = $dbrow->form_data->primary_qua_course_dur;

    $primary_qua_university_award_intership = $dbrow->form_data->primary_qua_university_award_intership;

    $acmrrno = $dbrow->form_data->acmrrno;

    $prn = $dbrow->form_data->prn;
    $rn_type = $dbrow->form_data->rn_type;


    $registration_date = $dbrow->form_data->registration_date;




    $study_place = $dbrow->form_data->study_place;
    $address1 = $dbrow->form_data->address1;
    $address2 = $dbrow->form_data->address2;
    $country = $dbrow->form_data->country;
    $state = isset($dbrow->form_data->state)??$dbrow->form_data->state;
    $state2 = isset($dbrow->form_data->state2)??$dbrow->form_data->state2;
    $district = $dbrow->form_data->district;
    $pincode = $dbrow->form_data->pincode;


    // ADMIT
    $admit_birth_type = isset($dbrow->form_data->admit_birth_type)? $dbrow->form_data->admit_birth_type: ""; 

    $admit_birth = isset($dbrow->form_data->admit_birth)? $dbrow->form_data->admit_birth: ""; 

    // HS MARKSHEET
    $hs_marksheet_type = isset($dbrow->form_data->hs_marksheet_type)? $dbrow->form_data->hs_marksheet_type: ""; 

    $hs_marksheet = isset($dbrow->form_data->hs_marksheet)? $dbrow->form_data->hs_marksheet: ""; 

    // REG CERTIFICATE
    $reg_certificate_type = isset($dbrow->form_data->reg_certificate_type)? $dbrow->form_data->reg_certificate_type: ""; 

    $reg_certificate = isset($dbrow->form_data->reg_certificate)? $dbrow->form_data->reg_certificate: "";

    // MBBS MARKSHEETS
    $mbbs_marksheet_type = isset($dbrow->form_data->mbbs_marksheet_type)? $dbrow->form_data->mbbs_marksheet_type: ""; 

    $mbbs_marksheet = isset($dbrow->form_data->mbbs_marksheet)? $dbrow->form_data->mbbs_marksheet: "";

    // MBBS PASS CERTUFICATE
    $pass_certificate_type = isset($dbrow->form_data->pass_certificate_type)? $dbrow->form_data->pass_certificate_type: ""; 

    $pass_certificate = isset($dbrow->form_data->pass_certificate)? $dbrow->form_data->pass_certificate: "";


    // INTERSHIP
    $internship_completion_certificate_type = isset($dbrow->form_data->internship_completion_certificate_type)? $dbrow->form_data->internship_completion_certificate_type: ""; 

    $internship_completion_certificate = isset($dbrow->form_data->internship_completion_certificate)? $dbrow->form_data->internship_completion_certificate: "";


    // PROVESIONAL
    $provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type = isset($dbrow->form_data->provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type)? $dbrow->form_data->provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type: ""; 

    $provisional_registration_certificate_of_concerned_assam_council_of_medical_registration = isset($dbrow->form_data->provisional_registration_certificate_of_concerned_assam_council_of_medical_registration)? $dbrow->form_data->provisional_registration_certificate_of_concerned_assam_council_of_medical_registration: "";


} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL;//set_value("rtps_trans_id");


    $applicant_name = set_value("applicant_name");
    $father_name = set_value("father_name");
    $mother_name = set_value("mother_name");
    $applicant_gender = set_value("applicant_gender");
    $dob = set_value("dob");
    $mobile = $this->session->mobile;//set_value("mobile_number");
    $email = set_value("email");
    $permanent_addr = set_value("permanent_addr");
    $correspondence_addr = set_value("correspondence_addr");
    $aadhar_no = set_value("aadhar_no");
    $pan_no = set_value("pan_no");
    $primary_qualification = set_value("primary_qualification");
    $primary_qua_doc = set_value("primary_qua_doc");
    $college_name = set_value("college_name");
    $primary_qua_college_name = set_value("primary_qua_college_name");
    $primary_qua_college_addr = set_value("primary_qua_college_addr");
    $primary_qua_course_dur = set_value("primary_qua_course_dur");
    // $primary_qua_doci = set_value("primary_qua_doci");
    $primary_qua_university_award_intership = set_value("primary_qua_university_award_intership");
    $acmrrno = set_value("acmrrno");
    $prn = set_value("prn");
    $rn_type = set_value("rn_type");

    $registration_date = set_value("registration_date");

    $student_studied = set_value("student_studied");


    $study_place = set_value("study_place");
    $address1 = set_value("address1");
    $address2 = set_value("address2");
    $state = set_value("state");
    $state2 = set_value("state2");
    $country = set_value("country");
    $district = set_value("district");
    $pincode = set_value("pincode");



    // Admit/ Birth Doc
    $admit_birth_type = "";
    $admit_birth = "";

    // HS Marksheet
    $hs_marksheet_type = "";
    $hs_marksheet = "";

    // Reg Certificate
    $reg_certificate_type = ""; 
    $reg_certificate = "";

    // Mbbs Marksheet
    $mbbs_marksheet_type = ""; 
    $mbbs_marksheet = "";

    // Pass Certificate
    $pass_certificate_type = ""; 
    $pass_certificate = "";

    // Internship
    $internship_completion_certificate_type = "";
    $internship_completion_certificate="";


    // Provisional
    $provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type="";
    $provisional_registration_certificate_of_concerned_assam_council_of_medical_registration="";





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

                var stdType = $('#study_place').val();
                
                  if(stdType == 1 || stdType == 2){
                    $("#country").val('India');
                    // $("#country").prop('readonly', true);
                    $("#state2").prop('readonly', true);


                    $("#state").val("");
                    $("#state").prop('disabled', false);

                    $("#district").val("");
                    $("#district").prop('disabled', false);
                }
                else{
                    $("#country").val("");
                    // $("#country").prop('readonly', false);

                    $("#state").val("");
                    $("#state").prop('disabled', true);

                    $("#district").val("");
                    $("#district").prop('disabled', true);

                    $("#state2").prop('readonly', false);



                }
         
        $(document).on("change", "#study_place", function(){      
            
            var stdType = $(this).val(); 
            if(stdType.length === 0) {
                $("#applicant_studied").hide();
            } else {
                $("#applicant_studied").show();
                if(stdType == 1 || stdType == 2){
                    $("#country").val('India');
                    // $("#country").prop('readonly', true);
                    $("#state2").prop('readonly', true);


                    $("#state").val("");
                    $("#state").prop('disabled', false);

                    $("#district").val("");
                    $("#district").prop('disabled', false);
                }
                else{
                    $("#country").val("");
                    // $("#country").prop('readonly', false);

                    $("#state").val("");
                    $("#state").prop('disabled', true);

                    $("#district").val("");
                    $("#district").prop('disabled', true);

                    $("#state2").prop('readonly', false);



                }
            }
                
        }); 

        // $(document).on("change", "#study_place", function(){            
        //     var stdType = $(this).val(); 
        //     if(stdType.length === 0) {
        //         $("#applicant_studied").hide();
        //     } else {
        //         $("#state2").show();
        //         if(stdType == 1 || stdType == 2){
        //             $("#state2").prop('readonly', true);
        //         }
        //         else{
        //             $("#state2").val("");
        //             $("#state2").prop('readonly', false);
        //         }
        //     }
                
        // }); 


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

    //     $(document).ready(function() {
    //     $.ajax({
    //         url: "<?php echo base_url('spservices/permanent_registration_mbbs/registration/getStates'); ?>", // Replace 'controller_name' with your actual controller name
    //         type: "GET",
    //         dataType: "json",
    //         success: function(response) {
    //             // pre(response);
    //             if (response) {
    //                 var options = '';
    //                 $.each(response, function(key, value) {
    //                     options += '<option value="' + value.slc + '">' + value.state_name_english + '</option>';
    //                 });
    //                 $('#state').append(options);
    //             }
    //         }
    //     });
    // });

    $(document).ready(function() {
        // Populate the state dropdown on page load
        $.ajax({
            url: "<?php echo base_url('spservices/permanent_registration_mbbs/registration/getStates'); ?>",
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response) {
                    var options = '';
                    $.each(response, function(key, value) {
                        options += '<option value="' + value.slc + '">' + value.state_name_english + '</option>';
                    });
                    $('#state').append(options);
                }
            }
        });

        // Event listener for state dropdown change event
        $('#state').on('change', function() {
    var selectedState = $(this).val();
    if (selectedState !== '') {
        
        $.ajax({
            url: "<?php echo base_url('spservices/permanent_registration_mbbs/registration/getDistricts'); ?>", // Replace 'controller_name' with your actual controller name and method
            type: "POST",
            dataType: "json",
            data: { state: selectedState },
            success: function(response) {
                if (response) {
                    var options = '';
                    $.each(response, function(key, value) {
                        options += '<option value="' + value.district_name_english + '">' + value.district_name_english + '</option>';
                    });
                    $('#district').html('<option value="">Please Select</option>');
                    $('#district').append(options);
                }
                
            }
        });
    } else {
        // Reset district dropdown if no state is selected
        $('#district').html('<option value="">Please Select</option>');
    }
});

    });



    $(document).ready(function() {
    // AJAX call to retrieve countries
    let c='<?=$country?>';
    $.ajax({
        url: "<?php echo base_url('spservices/permanent_registration_mbbs/registration/getCountries'); ?>",
        type: "GET",
        dataType: "json",
        success: function(response) {
            if (response) {
                var options = '';
                $.each(response, function(key, value) {
                    if(value.country_name === c){
  options += '<option selected value="' + value.country_name + '">' + value.country_name + '</option>';
                    }else{
  options += '<option value="' + value.country_name + '">' + value.country_name + '</option>';
                    }
                  
                });
                $('#country').html('<option value="">Please Select</option>');
                $('#country').append(options);
            }
        }
    });
});




    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/permanent-registration-mbbs') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />

              <!-- Start -->
            <input name="admit_birth_type" value="<?=$admit_birth_type?>" type="hidden" />
            <input name="admit_birth" value="<?=$admit_birth?>" type="hidden" />

            <input name="hs_marksheet_type" value="<?=$hs_marksheet_type?>" type="hidden" />
            <input name="hs_marksheet" value="<?=$hs_marksheet?>" type="hidden" />

            <input name="reg_certificate_type" value="<?=$reg_certificate_type?>" type="hidden" />
            <input name="reg_certificate" value="<?=$reg_certificate?>" type="hidden" />

            <input name="mbbs_marksheet_type" value="<?=$mbbs_marksheet_type?>" type="hidden" />
            <input name="mbbs_marksheet" value="<?=$mbbs_marksheet?>" type="hidden" />

           <input name="pass_certificate_type" value="<?=$pass_certificate_type?>" type="hidden" />
           <input name="pass_certificate" value="<?=$pass_certificate?>" type="hidden" />

           <input name="internship_completion_certificate_type" value="<?=$internship_completion_certificate_type?>" type="hidden" />
           <input name="internship_completion_certificate" value="<?=$internship_completion_certificate?>" type="hidden" />

           <input name="provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type" value="<?=$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type?>" type="hidden" />
           <input name="provisional_registration_certificate_of_concerned_assam_council_of_medical_registration" value="<?=$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration?>" type="hidden" />

            <!-- End -->

         
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm" >
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                Permanent registration of MBBS Doctors<br>
                        ( MBBS ডাক্তৰৰ স্থায়ী পঞ্জীয়ন ) 
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
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px; ">Stipulated time limit for delivery/ প্ৰাপ্তিৰ‌ নিৰ্দ্দিষ্ট সময়সীমা</strong>
                        
                        <ol style="  margin-left: 24px; margin-top: 20px">
                            <li>7 (Seven) working days for Indian Medical Graduates.</li>
                            <li>ভাৰতীয় চিকিৎসা স্নাতকসকলৰ বাবে ৭ (সাত) কৰ্মদিন</li>
                            <!-- <li>45 (Forty-five) working days for Foreign Medical Graduates.</li>
                            <li>বিদেশী চিকিৎসা স্নাতকসকলৰ বাবে ৪৫ (পঞ্চল্লিশ) কৰ্মদিন</li> -->
                        </ol>
                        
                        
                        <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>                        
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>User charge / ব্যৱহাৰকাৰী মাচুল – Rs. 5000 / ৫০০০ টকা.</li>
                            <li>Service charge (PFC/ CSC) / সেৱা মাচুল (পি. এফ. চি./ চি. এছ. চি.) – Rs. 30 / ৩০ টকা</li>
                            <li>Printing charge (in case of any printing from PFC) / ছপা খৰচ (পি.এফ.চি. ৰ পৰা কোনো ধৰণৰ প্ৰিন্টিঙৰ ক্ষেত্ৰত) - Rs. 10 Per Page / প্ৰতি পৃষ্ঠাত ১০ টকা.</li>
                            <li>Scanning charge (in case documents are scanned in PFC) স্কেনিং খৰচ (যদি নথিপত্ৰসমূহ পি.এফ.চি. ত স্কেন কৰা হয়) - Rs. 5 Per page / প্ৰতি পৃষ্ঠা ৫ টকা.</li>
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
                        <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <!-- This -->
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>

                            <!-- This -->
                                 <div class="col-md-6">
                                <label>Father&apos;s Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?=$father_name?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>

                        </div>


                        <!-- FM -->
                        <div class="row form-group">

                            <!-- This -->
                            <div class="col-md-6">
                                <label>Mother&apos;s Name/ মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name" value="<?=$mother_name?>" maxlength="255" />
                                <?= form_error("mother_name") ?>
                            </div>


                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
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

                        <!-- This -->
                            <div class="col-md-6 form-group">
                                <label>Date of Birth/ জন্ম তাৰিখ<span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="dob" id="dob" value="<?=$dob?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("dob") ?>
                            </div>

                            <!-- This -->
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <?php if($usser_type === "user"){ ?>
                                    <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" readonly maxlength="10" />
                               <?php }else{ ?>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" />
                                <?php }?>
                               
                                <?= form_error("mobile") ?>
                            </div>
                        </div>
                    
                        <div class="row form-group">

                        <!-- This -->
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100" />
                                <?= form_error("email") ?>
                            </div>

                        </div> 

                        <div class="row"> 
                            <!-- This -->
                            <div class="col-md-6 form-group">
                                <label>Permanent Address/ স্থায়ী ঠিকনা <span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="permanent_addr"><?=$permanent_addr?></textarea>
                                <?= form_error("permanent_addr") ?>
                            </div>

                            <!-- This -->
                            <div class="col-md-6 form-group">
                                <label>Correspondence Address/ চিঠিপত্ৰৰ ঠিকনা <span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="correspondence_addr"><?=$correspondence_addr?></textarea>
                                <?= form_error("correspondence_addr") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <!-- This -->
                            <div class="col-md-6 form-group">
                                <label>Aadhar No./আধাৰ নং </label>
                                <input class="form-control number_input" name="aadhar_no" value="<?=$aadhar_no?>" maxlength="12" type="text" id="aadhar_no"/>
                                <?= form_error("aadhar_no") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <!-- This -->
                                <label>PAN No./ পেন নং </label>
                                <input class="form-control pan_no" name="pan_no" value="<?=$pan_no?>" maxlength="10" type="text" />
                                <?= form_error("pan_no") ?>
                            </div>
                        </div> 
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Primary Qualification / প্ৰাথমিক অৰ্হতা </legend>
                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Primary Qualification(MBBS or equivalent)/ প্ৰাথমিক অৰ্হতা(MBBS বা সমতুল্য) <span class="text-danger">*</span></label>
                                <input class="form-control" name="primary_qualification" value="<?=$primary_qualification?>" type="text" />
                                <?= form_error("primary_qualification") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Date of completion/ সম্পূৰ্ণ হোৱাৰ তাৰিখ <span class="text-danger">*</span></label>
                                <input class="form-control dp" name="primary_qua_doc" value="<?=$primary_qua_doc?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("primary_qua_doc") ?>
                            </div>
                        </div> 

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>College Name/ কলেজৰ নাম <span class="text-danger">*</span></label>
                                <input class="form-control" name="college_name" value="<?=$college_name?>" type="text" />
                                <?= form_error("college_name") ?>
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
                            <!-- <div class="col-md-6 form-group">
                                <label>Date of Completion of Internship/ ইন্টাৰশ্বিপ সম্পূৰ্ণ হোৱাৰ তাৰিখ <span class="text-danger">*</span></label>
                                <input class="form-control dp" name="primary_qua_doci" value="<?=$primary_qua_doci?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("primary_qua_doci") ?>
                            </div> -->
                            <div class="col-md-6 form-group">
                                <label>University awarding the Internship/ ইন্টাৰশ্বিপ প্ৰদান কৰা বিশ্ববিদ্যালয় <span class="text-danger">*</span> </label>
                                <input class="form-control" name="primary_qua_university_award_intership" value="<?=$primary_qua_university_award_intership?>" type="text" />
                                <?= form_error("primary_qua_university_award_intership") ?>
                            </div> 
                        </div>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Medical Registration Details / চিকিৎসা পঞ্জীয়নৰ বিৱৰণ </legend>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>State Medical Council registered with/ ৰাজ্যিক চিকিৎসা পৰিষদৰ সৈতে পঞ্জীয়ন<span class="text-danger">*</span></label>
                                <input class="form-control" name="acmrrno" value="<?=$acmrrno?>" id="acmrrno" type="text" />
                                <?= form_error("acmrrno") ?>
                            </div> 

                        <div class="col-md-6 form-group">
                             <label>Registration Date/ পঞ্জীয়নৰ তাৰিখ <span class="text-danger">*</span></label>
                                <input class="form-control dp" name="registration_date" value="<?=$registration_date?>" maxlength="10" id="registration_date" type="text" />
                                <?= form_error("registration_date") ?>
                               </div>
                        
                        </div> 

                        <div class="row"> 

                        <div class="col-md-6 form-group">
                                <!-- <label>Provisional Registration Number/ অস্থায়ী পঞ্জীয়ন নম্বৰ<span class="text-danger">*</span></label> -->
                                <!-- <input class="form-control" name="prn" value="<?=$prn?>" id="prn" type="text" /> -->
        <label for="registrationInput">Registration Number:</label>

        <div>
            <select id="registrationNumber" name="rn_type" class="form-control" onchange="toggleInputField()">
            <option value="" disabled selected>Select Registration Number Type</option>
            <option value="Provisional Registration Number" <?=($rn_type === "Provisional Registration Number")?'selected':''?>>Provisional Registration Number</option>
            <option value="Permanent Registration Number" <?=($rn_type === "Permanent Registration Number")?'selected':''?>>Permanent Registration Number</option>
            </select>
        </div>
        <?= form_error("rn_type") ?>

        <br>

            <div class="form-group">
        <input type="text" id="registrationInput" name="prn" value="<?=$prn?>" class="form-control" >
    </div>
                                <?= form_error("prn") ?>
                            </div> 


                            
                        </div> 

                    </fieldset>


                    <fieldset class="border border-success" style="margin-top:40px">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Please select where the Applicant Studied <span class="text-danger">*</span> </label>
                                <select name="study_place" id="study_place" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php if(!empty($study_place)){ ?>
                                        <option value="1" <?php ($study_place == 1)? print "selected": '' ?>>Applicant studied within the State/Meghalaya</option>
                                        <option value="2" <?php ($study_place == 2)? print "selected": '' ?>>Applicant studied outside the State/Meghalaya but within India</option>
                                        <option value="3" <?php ($study_place == 3)? print "selected": '' ?>>Applicant studied outside the Country</option>
                                    <?php } else{?>
                                        <option value="1">Applicant studied within the State/Meghalaya</option>
                                        <option value="2">Applicant studied outside the State/Meghalaya but within India</option>
                                        <option value="3">Applicant studied outside the Country</option>
                                    <?php }?>
                                </select>
                                <?= form_error("study_place") ?>
                            </div>    
                               </div>
                               </fieldset>



                               <fieldset id="applicant_studied" class="border border-success" style="margin-top:40px; display: <?php isset($study_place)? 'block': 'none'?>;">
                        <legend class="h5">Address of Study Place</legend>
                        <div class="row form-group">
                             <div class="col-md-6">
                                <label>Address Line 1 / ঠিকনাৰ প্রথ্ম শাৰী<span class="text-danger"></span> </label>
                                <input type="text" class="form-control" name="address1" value="<?=$address1?>" maxlength="255" />
                                <?= form_error("address1") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address Line 2 / ঠিকনাৰ চিতীয় শাৰ<span class="text-danger"></span> </label>
                                <input type="text" class="form-control" name="address2" value="<?=$address2?>" maxlength="255" />
                                <?= form_error("address2") ?>
                            </div>
                               </div>
                                <div class="row form-group">
                            <!-- <div class="col-md-6">
                                <label>State<span class="text-danger">*</span> </label>
                                <select name="state" id="state" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Assam" <?=($state === "Assam")?'selected':''?>>Assam</option>
                                    <option value="Meghalaya" <?=($state === "Meghalaya")?'selected':''?>>Meghalaya</option>
                                    <option value="Outside the state Assam/Meghalaya" <?=($state === "Outside the state Assam/Meghalaya")?'selected':''?>>Outside the state Assam/Meghalaya</option>
                                </select>
                                <?= form_error("state") ?>
                            </div> -->
                            <div class="col-md-6">
    <label>State<span class="text-danger">*</span></label>
    <select name="state" id="state" class="form-control">
        <option value="">Please Select</option>
    </select>
    <?= form_error("state") ?>
</div>

                            <div class="col-md-6">
                                <label>District<span class="text-danger">*</span></label>
                                <select name="district" id="district" class="form-control">
                                    <option value="">Please Select</option>
                                </select>
                                <?= form_error("district") ?>
                            </div>

                               </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>Pincode / পিনকোড<span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="pincode" value="<?=$pincode?>" maxlength="255" />
                                    <?= form_error("pincode") ?>
                                </div>
                                <!-- <div class="col-md-6">
                                    <label>Country<span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="country" id="country" value="<?=$country?>" maxlength="255" />
                                    <?= form_error("country") ?>
                                </div> -->
        <div class="col-md-6">
    <label>Country<span class="text-danger">*</span></label>
    <select name="country" id="country" class="form-control">
        <option value="">Please Select</option>
    </select>
    <?= form_error("country") ?>
</div>
                                
                            </div>  
                            
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>State <span class="text-danger">*</span> (In case of Applicant studied outside the Country)</label>
                                    <input  type="text" class="form-control" name="state2" id="state2" value="<?=$state2?>" maxlength="255" />
                                    <?= form_error("state2") ?>
                                </div>
                            </div> 
                     </fieldset>            
     

                                    
                     
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


<script>
    // function toggleInputField() {
    //     var selectedOption = $("#registrationNumber").val();
    //     var registrationInput = $("#registrationInput");

    //     // Check if any option is selected
    //     if (selectedOption !== "") {
    //         registrationInput.prop("disabled", false);
    //     } else {
    //         registrationInput.prop("disabled", true);
    //     }
    // }
</script>