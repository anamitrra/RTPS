<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
//$apiServer = "https://localhost/wptbcapis/"; //For testing
// pre($dbrow);
$countries = $this->countries_model->get_rows(array());
$states = $this->States_model->get_rows(array());
$districts = $this->Districts_model->get_rows(array());


//pre($districts);
//return;

//pre($countries);
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
    $university_roll_no = $dbrow->form_data->university_roll_no;
    

    $study_place = $dbrow->form_data->study_place;
    $address1 = $dbrow->form_data->address1;
    $address2 = $dbrow->form_data->address2;
    $country = $dbrow->form_data->country;
    $statee = $dbrow->form_data->statee;
    $state_foreign = $dbrow->form_data->state_foreign;
    
    
    $district = $dbrow->form_data->district;
    $pincode = $dbrow->form_data->pincode;

    $admit_birth_type = isset($dbrow->form_data->admit_birth_type)? $dbrow->form_data->admit_birth_type: ""; 
    $admit_birth = isset($dbrow->form_data->admit_birth)? $dbrow->form_data->admit_birth: ""; 
    $hs_marksheet_type = isset($dbrow->form_data->hs_marksheet_type)? $dbrow->form_data->hs_marksheet_type: ""; 
    $hs_marksheet = isset($dbrow->form_data->hs_marksheet)? $dbrow->form_data->hs_marksheet: ""; 
    $reg_certificate_type = isset($dbrow->form_data->reg_certificate_type)? $dbrow->form_data->reg_certificate_type: ""; 
    $reg_certificate = isset($dbrow->form_data->reg_certificate)? $dbrow->form_data->reg_certificate: "";
    $mbbs_marksheet_type = isset($dbrow->form_data->mbbs_marksheet_type)? $dbrow->form_data->mbbs_marksheet_type: ""; 
    $mbbs_marksheet = isset($dbrow->form_data->mbbs_marksheet)? $dbrow->form_data->mbbs_marksheet: "";
    $pass_certificate_type = isset($dbrow->form_data->pass_certificate_type)? $dbrow->form_data->pass_certificate_type: ""; 
    $pass_certificate = isset($dbrow->form_data->pass_certificate)? $dbrow->form_data->pass_certificate: "";
    $college_noc_type = isset($dbrow->form_data->college_noc_type)? $dbrow->form_data->college_noc_type: ""; 
    $college_noc = isset($dbrow->form_data->college_noc)? $dbrow->form_data->college_noc: ""; 
    $director_noc_type = isset($dbrow->form_data->director_noc_type)? $dbrow->form_data->director_noc_type: ""; 
    $director_noc = isset($dbrow->form_data->director_noc)? $dbrow->form_data->director_noc: ""; 
    $university_noc_type = isset($dbrow->form_data->university_noc_type)? $dbrow->form_data->university_noc_type: ""; 
    $university_noc = isset($dbrow->form_data->university_noc)? $dbrow->form_data->university_noc: "";
    $institute_noc_type = isset($dbrow->form_data->institute_noc_type)? $dbrow->form_data->institute_noc_type: ""; 
    $institute_noc = isset($dbrow->form_data->institute_noc)? $dbrow->form_data->institute_noc: "";
    $pass_certificate_type = isset($dbrow->form_data->pass_certificate_type)? $dbrow->form_data->pass_certificate_type: ""; 
    $pass_certificate = isset($dbrow->form_data->pass_certificate)? $dbrow->form_data->pass_certificate: "";
    $eligibility_certificate_type = isset($dbrow->form_data->eligibility_certificate_type)? $dbrow->form_data->eligibility_certificate_type: ""; 
    $eligibility_certificate = isset($dbrow->form_data->eligibility_certificate)? $dbrow->form_data->eligibility_certificate: ""; 
    $screening_result_type = isset($dbrow->form_data->screening_result_type)? $dbrow->form_data->screening_result_type: ""; 
    $screening_result = isset($dbrow->form_data->screening_result)? $dbrow->form_data->screening_result: ""; 
    $passport_visa_type = isset($dbrow->form_data->passport_visa_type)? $dbrow->form_data->passport_visa_type: ""; 
    $passport_visa = isset($dbrow->form_data->passport_visa)? $dbrow->form_data->passport_visa: "";
    $all_docs_type = isset($dbrow->form_data->all_docs_type)? $dbrow->form_data->all_docs_type: ""; 
    $all_docs = isset($dbrow->form_data->all_docs)? $dbrow->form_data->all_docs: "";
    $soft_copy_type = isset($dbrow->form_data->soft_copy_type)? $dbrow->form_data->soft_copy_type: ""; 
    $soft_copy = isset($dbrow->form_data->soft_copy)? $dbrow->form_data->soft_copy: "";
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL;//set_value("rtps_trans_id");

    // $study_place = set_value("study_place");
    // if(strlen($study_place)) {
    //     $studyplace = json_decode(html_entity_decode($study_place));
    //     $st_id = $studyplace->st_id;
    // } else {
    //     $st_id = '';
    // }

    $applicant_name = set_value("applicant_name");
    $father_name = set_value("father_name");
    $mother_name = set_value("mother_name");
    $applicant_gender = set_value("applicant_gender");
    $dob = set_value("dob");
    $mobile = $this->session->mobile;//set_value("mobile_number");
    $email = set_value("email");
    $pan_no = set_value("pan_no");
    $aadhar_no = set_value("aadhar_no");
    $permanent_addr = set_value("permanent_addr");
    $correspondence_addr = set_value("correspondence_addr");

    $primary_qualification = set_value("primary_qualification");
    $primary_qua_doc = set_value("primary_qua_doc");
    $primary_qua_college_name = set_value("primary_qua_college_name");
    $primary_qua_college_addr = set_value("primary_qua_college_addr");
    $primary_qua_course_dur = set_value("primary_qua_course_dur");
    $primary_qua_doci = set_value("primary_qua_doci");
    $primary_qua_university_award_intership = set_value("primary_qua_university_award_intership");
    $university_roll_no = set_value("university_roll_no");
    

    $study_place = set_value("study_place");
    $address1 = set_value("address1");
    $address2 = set_value("address2");
    $statee = set_value("state");
    $state_foreign = set_value("state_foreign");  
    $country = set_value("country");
    $district = set_value("district");
    $pincode = set_value("pincode");   
    


    $admit_birth_type = "";
    $admit_birth = "";
    $hs_marksheet_type = "";
    $hs_marksheet = "";
    $reg_certificate_type = ""; 
    $reg_certificate = "";
    $mbbs_marksheet_type = ""; 
    $mbbs_marksheet = "";
    $pass_certificate_type = ""; 
    $pass_certificate = "";
    $college_noc_type = "";
    $college_noc = "";
    $director_noc_type = "";
    $director_noc = "";
    $university_noc_type = ""; 
    $university_noc = "";
    $institute_noc_type = ""; 
    $institute_noc = "";
    $eligibility_certificate_type = ""; 
    $eligibility_certificate = "";
    $screening_result_type = "";
    $screening_result = "";
    $passport_visa_type = "";
    $passport_visa = "";
    $all_docs_type = ""; 
    $all_docs = "";
    $soft_copy_type = ""; 
    $soft_copy = "";
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
//     $(document).ready(function () {    
//         $(document).on("change", "#statee", function() {
//     var selectedStateName = $(this).find("option:selected").text();
//     alert("Selected state: " + selectedStateName);
// });

$(document).ready(function() {
    $(document).on("change", "#study_place", function() {
        var stdType = $(this).val();
        if (stdType.length === 0) {
            $("#applicant_studied").hide();
        } else {
            $("#applicant_studied").show();
            if (stdType == 1) {
                $("#country").val('India');
                $("#country").prop('readonly', true);
            } else {
                $("#country").val("");
                $("#country").prop('readonly', false);
            }

            if (stdType == '1') {
                $("#con").hide();
                $("#state_f").hide();
                $("#sid").show();
                $("#did").show();
                $("#pincode_div").show();
                $("#district_div").show();
                $("#pin").val("");
                $("#pin").show();
                $("#did1").show();
            } else if (stdType == '2') {
                $("#con").hide();
                $("#sid").show();
                $("#did").show();
                $("#state_f").hide();
                $("#pincode_div").show();
                $("#district_div").show();
                $("#pin").val("");
                $("#pin").show();
                $("#did1").show();
            } else {
                $("#con").show();
                $("#state_f").show();
                $("#pincode").val("");
                $("#pincode_div").hide();
                $("#district_div").hide();
                $("#pin").hide();
                $("#did").hide();
                $("#sid").hide();
                $("#did1").hide();
            }
        }

    });

    $(document).on("click", ".frmbtn", function() {
        let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
        $("#submit_mode").val(clickedBtn);
        if (clickedBtn === 'DRAFT') {
            var msg =
                "You want to save in Draft mode that will allows you to edit and can submit later";
        } else if (clickedBtn === 'SAVE') {
            var msg = "Do you want to procced";
        } else if (clickedBtn === 'CLEAR') {
            var msg = "Once you Reset, All filled data will be cleared";
        } else {
            var msg = "";
        } //End of if else            
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
                if ((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
                    $("#myfrm").submit();
                } else if (clickedBtn === 'CLEAR') {
                    $("#myfrm")[0].reset();
                } else {} //End of if else
            }
        });
    });

    $(".dp").datepicker({
        format: 'dd/mm/yyyy',
        endDate: '+0d',
        autoclose: true
    });

    var date_of_birth = '<?=$dob?>';
    if (date_of_birth.length == 10) {
        getAge(date_of_birth);
    } //End of if

    $(document).on("change", "#dob", function() {
        var dd = $(this).val(); //alert(dd);
        var dateAr = dd.split('/');
        var dob = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];
        getAge(dob);
    });

    $(document).on("keyup", "#pan_no", function() {
        if ($("#pan_no").val().length > 10) {
            $("#pan_no").val("");
            alert("Please! Enter upto only 10 digit");
        }
    });

    $('.number_input').keypress(function(e) {
        var charCode = (e.which) ? e.which : event.keyCode
        if (String.fromCharCode(charCode).match(/[^0-9]/g))
            return false;
    });

    $('#aadhar_no').keyup(function() {
        if ($("#aadhar_no").val().length > 12) {
            $("#aadhar_no").val("");
            alert("Please! Enter upto only 12 digit");
        }
    });

    $('.pin_code').keyup(function() {
        if ($(".pin_code").val().length > 6) {
            $(".pin_code").val("");
            alert("Please! Enter upto only 6 digit");
        }
    });

    $(document).on("click", "#addlatblrow", function() {
        let totRows = $('#addlqualificationtbl tr').length;
        var trow = `<tr>
                            <td><input name="addl_qualification[]" class="form-control" type="text" /></td>
                            <td><input name="college_name[]" class="form-control" type="text"/></td>
                            <td><input name="university_name[]" class="form-control" type="text"/></td>
                            <td><input name="date_of_qualification[]" class="form-control" type="text"/></td>
                            <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
        if (totRows <= 10) {
            $('#addlqualificationtbl tr:last').after(trow);
        }
    });

    $(document).on("click", ".deletetblrow", function() {
        $(this).closest("tr").remove();
        return false;
    });
});
</script>
<script type="text/javascript">
$(function() {
    $(".datepicker").datepicker({
        dateFormat: "yy-mm-dd", // Change the date format as needed
        changeMonth: true,
        changeYear: true,
        yearRange: "1900:2050" // Set the range of selectable years as needed
    });
});
$(document).ready(function() {
    $.getJSON("Countries.php", function(data) {
        let selectOption = '';
        $.each(data.records, function(key, value) {
            selectOption += '<option value="' + value.country_name + '">' + value.country_name +
                '</option>';
        });
        $('.country').append(selectOption);
    });
});
$(document).on("change", ".country", function() {
    let selectedVal = $(this).val();

    if (selectedVal.length) {
        var myObject = new Object();
        myObject.name = selectedVal;

        $.getJSON("states.php?jsonbody=" + JSON.stringify(myObject), function(data) {
            let selectOption = '';
            $('#statee').empty().append('<option value="">Select a state</option>');
            //$('.slc').empty().append('<option value="">Select a State</option>');

            $.each(data.records, function(key, value) {
                selectOption += '<option value="' + value.state_name_english + '">' + value
                    .state_name_english + '</option>';
            });

            // $('.slc').append(selectOption);

            // Select the previously selected state, if available
            $('#statee').append(selectOption);
            //$('.slc option[value="' + selectedVal + '"]').prop('selected', true);
        });
    }
});

$(document).on("change", "#statee", function() {
    let selectedVal = $(this).val();
    if (selectedVal.length) {
        var myObject = new Object();
        myObject.slc = selectedVal;

        $.getJSON("districts_all_states.php?jsonbody=" + JSON.stringify(myObject), function(
            data) {
            let selectOption = '';
            $('#district').empty().append('<option value="">Select a District</option>');

            $.each(data.records, function(key, value) {
                selectOption += '<option value="' + value.district_name_english + '">' + value
                    .district_name_english + '</option>';
            });

            $('#district').append(selectOption);
        });

        $.ajax({
            type: "POST",
            url: "<?=base_url('spservices/acmr_provisional_certificate/registration/get_districts')?>",
            data: {
                "field_name": 'district',
                "field_value": selectedVal
            },
            beforeSend: function() {
                $("#did").html("Loading");
            },
            success: function(res) {
                $("#did").html(res);
            }
        });
    }
});
// $(document).on("change", "#study_place", function() {
//   // Get the selected country name
//   var stdType = $(this).val();


// });                           
</script>
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/acmr-provisional-certificate') ?>"
            enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
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
            <input name="college_noc_type" value="<?=$college_noc_type?>" type="hidden" />
            <input name="college_noc" value="<?=$college_noc?>" type="hidden" />
            <input name="director_noc_type" value="<?=$director_noc_type?>" type="hidden" />
            <input name="director_noc" value="<?=$director_noc?>" type="hidden" />
            <input name="university_noc_type" value="<?=$university_noc_type?>" type="hidden" />
            <input name="university_noc" value="<?=$university_noc?>" type="hidden" />
            <input name="institute_noc_type" value="<?=$institute_noc_type?>" type="hidden" />
            <input name="institute_noc" value="<?=$institute_noc?>" type="hidden" />
            <input name="eligibility_certificate_type" value="<?=$eligibility_certificate_type?>" type="hidden" />
            <input name="eligibility_certificate" value="<?=$eligibility_certificate?>" type="hidden" />
            <input name="screening_result_type" value="<?=$screening_result_type?>" type="hidden" />
            <input name="screening_result" value="<?=$screening_result?>" type="hidden" />
            <input name="passport_visa_type" value="<?=$passport_visa_type?>" type="hidden" />
            <input name="passport_visa" value="<?=$passport_visa?>" type="hidden" />
            <input name="all_docs_type" value="<?=$all_docs_type?>" type="hidden" />
            <input name="all_docs" value="<?=$all_docs?>" type="hidden" />
            <input name="soft_copy_type" value="<?=$soft_copy_type?>" type="hidden" />
            <input name="soft_copy" value="<?=$soft_copy?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    PROVISIONAL REGISTRATION CERTIFICATE OF MBBS DOCTOR<br>
                    ( এম.বি.বি.এছ. চিকিৎসকৰ অস্থায়ী পঞ্জীয়ন প্ৰমাণপত্ৰ )
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
                        <legend class="h5">Important/ দৰকাৰী</legend>
                        <strong style="font-size:16px; ">Stipulated time limit for delivery</strong>

                        <ol style="  margin-left: 24px; margin-top: 20px">
                            <li>7 (Seven) working days for Indian Medical Graduates.</li>
                            <li>ভাৰতীয় চিকিৎসা স্নাতকসকলৰ বাবে ৭ (সাত) কৰ্মদিন</li>
                        </ol>


                        <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>User charge / ব্যৱহাৰকাৰী মাচুল – Rs. 2000 / 2০০০ টকা.</li>
                            <li>GST/জিএছটি - Rs. 360/360 টকা.</li>
                            <li>Service charge (PFC/ CSC) / সেৱা মাচুল (পি. এফ. চি./ চি. এছ. চি.) – Rs. 30 / ৩০ টকা</li>
                            <li>Printing charge (in case of any printing from PFC) / ছপা খৰচ (পি.এফ.চি. ৰ পৰা কোনো ধৰণৰ
                                প্ৰিন্টিঙৰ ক্ষেত্ৰত) - Rs. 10 Per Page / প্ৰতি পৃষ্ঠাত ১০ টকা.</li>
                            <li>Scanning charge (in case documents are scanned in PFC) স্কেনিং খৰচ (যদি নথিপত্ৰসমূহ
                                পি.এফ.চি. ত স্কেন কৰা হয়) - Rs. 5 Per page / প্ৰতি পৃষ্ঠা ৫ টকা.</li>
                        </ul>

                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী
                            :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. All the * marked fields are mandatory and need to be filled up..</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমুহ বাধ্য়তামুলক আৰু স্থানসমুহ পুৰণ কৰিব লাগিব</li>
                            <li>2. The size of documents to be uploaded at the time of Application submission should not
                                exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো
                                অনিবাৰ্য </li>
                        </ul>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details/ আবেদনকাৰীৰ তথ্য</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name"
                                    value="<?=$applicant_name?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Father's Name<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name"
                                    value="<?=$father_name?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mother's Name<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name"
                                    value="<?=$mother_name?>" maxlength="255" />
                                <?= form_error("mother_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ<span class="text-danger">*</span>
                                </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($applicant_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($applicant_gender === "Female")?'selected':''?>>Female
                                    </option>
                                    <option value="Transgender"
                                        <?=($applicant_gender === "Transgender")?'selected':''?>>Transgender</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6 form-group">
                                <label>Date of Birth/ জন্ম তাৰিখ<span class="text-danger">*</span></label>
                                <input class="form-control datepicker" name="dob" id="dob" value="<?= $dob ?>"
                                    maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("dob") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile Number/ দুৰভাষ ( মবাইল )<span class="text-danger">*</span> </label>
                                <?php if($usser_type === "user"){ ?>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" readonly
                                    maxlength="10" />
                                <?php }else{ ?>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>"
                                    maxlength="10" />
                                <?php }?>

                                <?= form_error("mobile") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>E-Mail/ ই-মেইল<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>"
                                    maxlength="100" />
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
                                <label>Correspondence Address/ চিঠিপত্ৰৰ ঠিকনা <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control"
                                    name="correspondence_addr"><?=$correspondence_addr?></textarea>
                                <?= form_error("correspondence_addr") ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Aadhar No./আধাৰ নং </label>
                                <input class="form-control number_input" name="aadhar_no" value="<?=$aadhar_no?>"
                                    maxlength="12" type="text" id="aadhar_no" />
                                <?= form_error("aadhar_no") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>PAN No./ পেন নং</label>
                                <input class="form-control pan_no" name="pan_no" value="<?=$pan_no?>" maxlength="10"
                                    type="text" />
                                <?= form_error("pan_no") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Primary Qualification</legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Primary Qualification(MBBS or equivalent)/ প্ৰাথমিক অৰ্হতা(MBBS বা সমতুল্য)<span
                                        class="text-danger">*</span></label>
                                <input class="form-control" name="primary_qualification"
                                    value="<?=$primary_qualification?>" type="text" />
                                <?= form_error("primary_qualification") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Month & year of passing the Final MBBS Exam/চূড়ান্ত এম.বি.বি.এছ. পৰীক্ষাত উত্তীৰ্ণ হোৱাৰ মাহ আৰু বছৰ*<span
                                        class="text-danger">*</span></label>
                                <input class="form-control datepicker" name="primary_qua_doc"
                                    value="<?= $primary_qua_doc ?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("primary_qua_doc") ?>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>College Name/ কলেজৰ নাম<span class="text-danger">*</span></label>
                                <input class="form-control" name="primary_qua_college_name"
                                    value="<?=$primary_qua_college_name?>" type="text" />
                                <?= form_error("primary_qua_college_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Course Duration/ পাঠ্যক্ৰমৰ সময়সীমা <span class="text-danger">*</span></label>
                                <input class="form-control" name="primary_qua_course_dur"
                                    value="<?=$primary_qua_course_dur?>" type="text" />
                                <?= form_error("primary_qua_course_dur") ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Date of Commencement of Internship/ ইন্টাৰশ্বিপ আৰম্ভ হোৱাৰ তাৰিখ</label>
                                <input class="form-control datepicker" name="primary_qua_doci"
                                    value="<?= $primary_qua_doci ?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("primary_qua_doci") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Name of the university awarding the degree/ ইন্টাৰশ্বিপ প্ৰদান কৰা
                                    বিশ্ববিদ্যালয়<span class="text-danger">*</span> </label>
                                <input class="form-control" name="primary_qua_university_award_intership"
                                    value="<?=$primary_qua_university_award_intership?>" type="text" />
                                <?= form_error("primary_qua_university_award_intership") ?>
                            </div>
                            </div>
                            <div class="row">
                            <div class="col-md-6 form-group">
                                <label>University Roll Number/বিশ্ববিদ্যালয় ৰোল নম্বৰ<span class="text-danger">*</span>
                                </label>
                                <input class="form-control" name="university_roll_no" value="<?=$university_roll_no?>"
                                    type="text" />
                                <?= form_error("university_roll_no") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Please select where the Applicant Studied/অনুগ্ৰহ কৰি আবেদনকাৰীয়ে ক'ত অধ্যয়ন কৰিছিল বাছনি কৰক <span class="text-danger">*</span>
                                </label>
                                <select name="study_place" id="study_place" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php if (!empty($study_place)) { ?>
                                    <option value="1" <?php ($study_place == 1) ? print "selected" : '' ?>>Applicant
                                        studied within the State/Meghalaya</option>
                                    <option value="2" <?php ($study_place == 2) ? print "selected" : '' ?>>Applicant
                                        studied outside the State/Meghalaya but within India</option>
                                    <option value="3" <?php ($study_place == 3) ? print "selected" : '' ?>>Applicant
                                        studied outside the Country</option>
                                    <?php } else { ?>
                                    <option value="1">Applicant studied within the State/Meghalaya</option>
                                    <option value="2">Applicant studied outside the State/Meghalaya but within India
                                    </option>
                                    <option value="3">Applicant studied outside the Country</option>
                                    <?php } ?>
                                </select>
                                <?= form_error("study_place") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset id="applicant_studied" class="border border-success"
                        style="margin-top: 40px; display: <?= isset($study_place) ? 'block' : 'none' ?>;">
                        <legend class="h5">Address of Study Place</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Address Line 1 / ঠিকনাৰ প্ৰথম শাৰী<span class="text-danger"></span> </label>
                                <input type="text" class="form-control" name="address1" value="<?= $address1 ?>"
                                    maxlength="255" />
                                <?= form_error("address1") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address Line 2/ ঠিকনাৰ চিতীয় শাৰী<span class="text-danger"></span> </label>
                                <input type="text" class="form-control" name="address2" value="<?= $address2 ?>"
                                    maxlength="255" />
                                <?= form_error("address2") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div id="con" class="col-md-4 form-group"
                                style="display: <?= ($study_place == 3) ? 'block' : 'none' ?>;">
                                <label>Country/দেশ <span class="text-danger">*</span> </label>
                                <select name="country" id="country" class="form-control country">
                                    <option value="">Please Select</option>
                                    <option value="India" <?= ($country === 'India') ? 'selected' : '' ?>>India</option>
                                    <?php if ($countries) {
                    foreach ($countries as $countryObj) {
                        if ($countryObj->country_name !== 'India') {
                            $selected = ($country === $countryObj->country_name) ? 'selected' : '';
                            echo '<option value="' . $countryObj->country_name . '" ' . $selected . '>' . $countryObj->country_name . '</option>';
                        }
                    }
                } ?>
                                </select>
                                <?= form_error('country') ?>
                            </div>
                            <div id="sid" class="col-md-4 form-group" style="display: <?= ($study_place == 3) ? 'none' : 'block' ?>;">
                            <label>State/ৰাজ্য<span class="text-danger">*</span></label>
                            <select name="statee" id="statee" class="form-control">
                                <option value="">Please Select</option>
                                <?php
                                foreach ($states as $stateObj) {
                                    $selected = set_select('statee', $stateObj->slc, ($statee === $stateObj->slc));
                                    echo '<option value="' . $stateObj->slc . '" ' . $selected . '>' . $stateObj->state_name_english . '</option>';
                                }
                                ?>
                            </select>
                            <?= form_error('statee', '<div class="text-danger">', '</div>') ?>
                        </div>
                            <div id="state_f" class="col-md-4 form-group"
                                style="display: <?= ($study_place == 3) ? 'block' : 'none' ?>;">
                                <label>State/ৰাজ্য/Province/প্ৰদেশ </label>
                                <input type="text" class="form-control" name="state_foreign" id="state_foreign"
                                    value="<?= $state_foreign ?>" maxlength="255" />
                                <?= form_error("state_foreign") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group" id="district_div"
                                style="display: <?= ($study_place == 3) ? 'none' : 'block' ?>;">
                                <label id="did1">District/জিলা<span class="text-danger">*</span> </label>
                                <div id="did">
                                    <?php if ($country === 'India') { ?>
                                    <select name="district" id="district" class="form-control">
                                        <option value="">Please Select</option>
                                        <?php if ($districts) {
                                            foreach ($districts as $districtObj) {
                                                $selected = ($district === $districtObj->district_name) ? 'selected' : '';
                                                echo '<option value="' . $districtObj->district_name . '" ' . $selected . '>' . $districtObj->district_name . '</option>';
                                            }
                                        } ?>
                                    </select>
                                    <?php } else { ?>
                                    <input type="text" class="form-control" name="district" value="<?= $district ?>"
                                        maxlength="255" />
                                    <?php } ?>
                                </div>
                                <?= form_error('district') ?>
                            </div>

                            <div class="col-md-4" id="pincode_div"
                                style="display: <?= ($study_place == 3) ? 'none' : 'block' ?>;">
                                <label>Pincode/ পিনকোড<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pincode" id="pincode"
                                    value="<?= $pincode ?>" maxlength="6" pattern="\d{6}" />
                                <?= form_error("pincode") ?>
                            </div>
                        </div>
                    </fieldset>

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
                    </div>
                    <!--End of .card-footer-->
                </div>
                <!--End of .card-->
        </form>
    </div>
    <!--End of .container-->
</main>