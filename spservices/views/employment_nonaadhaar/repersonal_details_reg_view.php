<?php
if ($dbrow) {
    $obj_id = $dbrow->_id->{'$id'};
    $type_of_reg = isset($dbrow->form_data->type_of_reg) ? $dbrow->form_data->type_of_reg : set_value("type_of_reg");
    $applicant_name = isset($dbrow->form_data->applicant_name) ? $dbrow->form_data->applicant_name : set_value("applicant_name");
    $applicant_gender = isset($dbrow->form_data->applicant_gender) ? $dbrow->form_data->applicant_gender : set_value("applicant_gender");
    // $mobile_number = $this->session->mobile; //set_value("mobile_number");
    $mobile_number = isset($dbrow->form_data->mobile_number) ? $dbrow->form_data->mobile_number : (($this->session->mobile) ? $this->session->mobile : '');
    // if (isset($dbrow->form_data->mobile_verify_status)) {
    $mobile_verify_status = isset($dbrow->form_data->mobile_verify_status) ? $dbrow->form_data->mobile_verify_status : '';
    // }
    //  else {
    //     if ($this->session->userdata('verified_mobile_numbers')) {
    //         $mobile_verify_status = 1;
    //     } else {
    //         $mobile_verify_status = 0;
    //     }
    // }
    $e_mail = isset($dbrow->form_data->{'e-mail'}) ? $dbrow->form_data->{'e-mail'} : set_value("e_mail");
    $fathers_name = isset($dbrow->form_data->fathers_name) ? $dbrow->form_data->fathers_name : set_value("fathers_name");
    $fathers_name_guardians_name = isset($dbrow->form_data->fathers_name__guardians_name) ? $dbrow->form_data->fathers_name__guardians_name : set_value("fathers_name_guardians_name");
    $mothers_name = isset($dbrow->form_data->mothers_name) ? $dbrow->form_data->mothers_name : set_value("mothers_name");
    $date_of_birth = isset($dbrow->form_data->date_of_birth) ? $dbrow->form_data->date_of_birth : set_value("date_of_birth");
    $caste = isset($dbrow->form_data->caste) ? $dbrow->form_data->caste : set_value("caste");
    $economically_weaker_section = isset($dbrow->form_data->economically_weaker_section) ? $dbrow->form_data->economically_weaker_section : set_value("economically_weaker_section");
    $husbands_name = isset($dbrow->form_data->husbands_name) ? $dbrow->form_data->husbands_name : set_value("husbands_name");
    $religion = isset($dbrow->form_data->religion) ? $dbrow->form_data->religion : set_value("religion");
    $marital_status = isset($dbrow->form_data->marital_status) ? $dbrow->form_data->marital_status : set_value("marital_status");
    $occupation = isset($dbrow->form_data->occupation) ? $dbrow->form_data->occupation : set_value("occupation");
    $occupation_type = isset($dbrow->form_data->occupation_type) ? $dbrow->form_data->occupation_type : set_value("occupation_type");
    $whether_ex_servicemen = isset($dbrow->form_data->{'whether_ex-servicemen'}) ? $dbrow->form_data->{'whether_ex-servicemen'} : set_value("whether_ex_servicemen");
    $ex_servicemen_category = isset($dbrow->form_data->{'category_of_ex-servicemen'}) ? $dbrow->form_data->{'category_of_ex-servicemen'} : set_value("ex_servicemen_category");
    $unique_identification_type = isset($dbrow->form_data->unique_identification_type) ? $dbrow->form_data->unique_identification_type : set_value("unique_identification_type");
    $unique_identification_no = isset($dbrow->form_data->unique_identification_no) ? $dbrow->form_data->unique_identification_no : set_value("unique_identification_no");
    $prominent_identification_mark = isset($dbrow->form_data->prominent_identification_mark) ? $dbrow->form_data->prominent_identification_mark : set_value("prominent_identification_mark");
    $height_in_cm = isset($dbrow->form_data->height__in_cm) ? $dbrow->form_data->height__in_cm : set_value("height_in_cm");
    $weight_kgs = isset($dbrow->form_data->weight__kgs) ? $dbrow->form_data->weight__kgs : set_value("weight_kgs");
    $eye_sight = isset($dbrow->form_data->eye_sight) ? $dbrow->form_data->eye_sight : set_value("eye_sight");
    $chest_inch = isset($dbrow->form_data->chest__inch) ? $dbrow->form_data->chest__inch : set_value("chest_inch");
    $are_you_differently_abled_pwd = isset($dbrow->form_data->are_you_differently_abled__pwd) ? $dbrow->form_data->are_you_differently_abled__pwd : set_value("are_you_differently_abled_pwd");
    $disability_category = isset($dbrow->form_data->disability_category) ? $dbrow->form_data->disability_category : set_value("disability_category");
    $additional_disability_type = isset($dbrow->form_data->additional_disability_type) ? $dbrow->form_data->additional_disability_type : set_value("additional_disability_type");
    $disbility_percentage = isset($dbrow->form_data->disbility_percentage) ? $dbrow->form_data->disbility_percentage : set_value("disbility_percentage");
    $custom_fields = isset($dbrow->custom_field_values) ? $dbrow->custom_field_values : [];
    $editable_fields = [];
    if (count($custom_fields)) {
        foreach ($custom_fields as $val) {
            if ($val->field_name == 'editable_fields') {
                $editable_fields = $val->field_value;
            }
        }
    }
} else {
    $editable_fields = [];
    $obj_id = null;
    $type_of_reg = "";
    $title = "New Applicant Registration";

    $mobile_number = '';
    $mobile_verify_status = '';
    if (isset($this->session->mobile)) {
        $mobile_number = $this->session->mobile;
        $mobile_verify_status = '1';
    }


    // $mobile_number = isset($this->session->mobile) ? $this->session->mobile : '';
    // if (isset($dbrow->form_data->mobile_verify_status)) {
    // $mobile_verify_status = isset($dbrow->form_data->mobile_verify_status) ? $dbrow->form_data->mobile_verify_status : '';
    // }
    //  else {
    //     if ($this->session->userdata('verified_mobile_numbers')) {
    //         $mobile_verify_status = 1;
    //     } else {
    //         $mobile_verify_status = 0;
    //     }
    // }

    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $e_mail = set_value("e_mail");
    $fathers_name = set_value("fathers_name");
    $fathers_name_guardians_name = set_value("fathers_name_guardians_name");
    $mothers_name = set_value("mothers_name");
    $date_of_birth = set_value("date_of_birth");
    $caste = set_value("caste");
    $economically_weaker_section = set_value("economically_weaker_section");
    $husbands_name = set_value("husbands_name");
    $religion = set_value("religion");
    $marital_status = set_value("marital_status");
    $occupation = set_value("occupation");
    $occupation_type = set_value("occupation_type");
    $whether_ex_servicemen = set_value("whether_ex_servicemen");
    $ex_servicemen_category = set_value("ex_servicemen_category");
    $unique_identification_type = set_value("unique_identification_type");
    $unique_identification_no = set_value("unique_identification_no");
    $prominent_identification_mark = set_value("prominent_identification_mark");
    $height_in_cm = set_value("height_in_cm");
    $weight_kgs = set_value("weight_kgs");
    $eye_sight = set_value("eye_sight");
    $chest_inch = set_value("chest_inch");
    $are_you_differently_abled_pwd = set_value("are_you_differently_abled_pwd");
    $disability_category = set_value("disability_category");
    $additional_disability_type = set_value("additional_disability_type");
    $disbility_percentage = set_value("disbility_percentage");
}
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
    $(document).ready(function() {
        $(".dp").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '+0d',
            autoclose: true
        });

        $(document).on("click", "#send_mobile_otp", function() {
            let contactNo = $("#mobile_number").val();
            if (/^\d{10}$/.test(contactNo)) {
                $("#otp_no").val("");
                $("#otpModal").modal("show");
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?= base_url('spservices/employment_aadhaar_based/otps/send_otp') ?>",
                    data: {
                        "mobile_number": contactNo
                    },
                    beforeSend: function() {
                        $("#otp_no").attr("placeholder", "Sending OTP... Please wait");
                    },
                    success: function(res) {
                        if (res.status) {
                            $(".verify_btn").attr("id", "verify_mobile_otp");
                            $("#otp_no").attr("placeholder", "Enter your OTP");
                        } else {
                            alert(res.msg);
                        } //End of if else
                    }
                });
            } else {
                alertMsg('error', 'Contact number is invalid. Please enter a valid number.')
                // alert("Contact number is invalid. Please enter a valid number");
                $("#mobile_number").val();
                $("#mobile_number").focus();
                return false;
            } //End of if else
        }); //End of onclick #send_mobile_otp

        $(document).on("click", "#verify_mobile_otp", function() {
            let contactNo = $("#mobile_number").val();
            var otpNo = $("#otp_no").val();
            if (/^\d{6}$/.test(otpNo)) {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?= base_url('spservices/employment_aadhaar_based/otps/verify_otp') ?>",
                    data: {
                        "mobile_number": contactNo,
                        "otp": otpNo
                    },
                    beforeSend: function() {
                        $("#otp_no").val("");
                        $("#otp_no").attr("placeholder", "Verifying OTP... Please wait");
                    },
                    success: function(res) { //alert(JSON.stringify(res));
                        if (res.status) {
                            $("#otpModal").modal("hide");
                            $("#mobile_verify_status").val(1);
                            $("#mobile_number").prop("readonly", true);
                            $("#send_mobile_otp").addClass('d-none');
                            $("#verified").removeClass('d-none');

                        } else {
                            // alert(res.msg);
                            alertMsg('error', res.msg)
                            $("#otp_no").attr("placeholder", "ENTER THE 6-DIGIT OTP");
                        } //End of if else
                    }
                });
            } else {
                // alert("OTP is invalid. Please enter a valid otp");
                alertMsg('error', 'OTP is invalid. Please enter a valid otp')
                $("#otp_no").val();
                $("#otp_no").focus();
            } //End of if else
        }); //End of onClick #verify_mobile_otp
        // caste
        $(document).on("change", "#caste", function() {
            let casteCategory = $(this).val();
            // alert(casteCategory)
            if (casteCategory == 'General') {
                $('.ews_div').fadeIn('slow');
            } else {
                $('.ews_div').fadeOut(300);
            }
        });
        $(document).on("change", "#are_you_differently_abled_pwd", function() {
            let pwdCategory = $(this).val();
            if (pwdCategory == 'Yes') {
                $('.disablity_cat').removeClass('d-none')
                $('.additional_dis').removeClass('d-none')
            } else {
                $('.disablity_cat').addClass('d-none')
                $('.additional_dis').addClass('d-none')
            }
        });
        $(document).on("change", ".ex_serviceman", function() {
            let exService = $(this).val();
            if (exService == 'Yes') {
                $('.exservice_category').removeClass('d-none')
            } else {
                $('.exservice_category').addClass('d-none')
            }
        });
        $(document).on("change", "#occupation", function() {
            let occupation = $(this).val();
            if (occupation == 'Other') {
                $('.occupationType').removeClass('d-none')
            } else {
                $('.occupationType').addClass('d-none')
            }
        });

        function alertMsg(type, msg) {
            Swal.fire({
                icon: type,
                text: msg,
            })
        }
    });
</script>
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/employmentnonaadhaar/reregistration/submit_personal_details') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input id="mobile_verify_status" name="mobile_verify_status" value="<?= $mobile_verify_status ?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Registration of employment seeker in Employment Exchange
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
                    <?php
                    if (isset($dbrow->service_data->appl_status) && ($dbrow->service_data->appl_status == 'QS')) {
                        ($this->load->view('employment_nonaadhaar/query_message_view', $dbrow));
                    }
                    ?>
                    <h5 class="text-center mt-3 text-success"><u><strong>PERSONAL DETAILS</strong></u></h5>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Details of the Applicant </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name of the Applicant <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?= $applicant_name ?>" <?= (count($editable_fields)) ? ((in_array('applicant_name', $editable_fields)) ? '' : 'readonly') : '' ?> <?php ($type_of_reg == '3')? print  "": print "disabled"; ?>/>
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Gender <span class="text-danger">*</span> </label>
                                <select <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> name="applicant_gender" class="form-control">
                                    <?php if (count($editable_fields)) {
                                        if (in_array('applicant_gender', $editable_fields)) { ?>
                                            <option value="">Please Select</option>
                                            <option value="Male" <?= ($applicant_gender === "Male") ? 'selected' : '' ?>>Male</option>
                                            <option value="Female" <?= ($applicant_gender === "Female") ? 'selected' : '' ?>>Female</option>
                                            <option value="Others" <?= ($applicant_gender === "Others") ? 'selected' : '' ?>>Others</option>
                                        <?php } else {
                                            echo '<option value="' . $applicant_gender . '" selected>' . $applicant_gender . '</option>';
                                        }
                                    } else { ?>
                                        <option value="">Please Select</option>
                                        <option value="Male" <?= ($applicant_gender === "Male") ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= ($applicant_gender === "Female") ? 'selected' : '' ?>>Female</option>
                                        <option value="Others" <?= ($applicant_gender === "Others") ? 'selected' : '' ?>>Others</option>
                                    <?php } ?>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mobile Number <span class="text-danger">*</span>
                                    <small class="text-info pull-right">(Please verify the contact number using OTP)</small></label>
                                <div class="input-group">
                                    <input class="form-control" name="mobile_number" id="mobile_number" maxlength="10" value="<?= $mobile_number ?>" <?= ($mobile_verify_status == 1) ? 'readonly' : (($this->slug === 'user') ? 'readonly' : '') ?> type="text" />
                                    <div class="input-group-append">
                                        <a href="javascript:void(0)" class="btn btn-outline-danger <?= ($mobile_verify_status == 1) ? 'd-none' : (($this->slug === 'user') ? 'd-none' : '') ?>" id="send_mobile_otp"><i class="fa fa-check"></i> Verify</a>
                                        <a href="javascript:void(0)" class="btn btn-outline-success <?= ($mobile_verify_status == 1) ? '' : (($this->slug === 'user') ? '' : 'd-none') ?>" id="verified"><i class="fa fa-check"></i></a>
                                    </div>
                                </div>
                                <?= form_error("mobile_number") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail</label>
                                <input type="email" class="form-control" name="e_mail" value="<?= $e_mail ?>" <?= (count($editable_fields)) ? ((in_array('e_mail', $editable_fields)) ? '' : 'readonly') : '' ?> <?php ($type_of_reg == '3')? print  "": print "disabled"; ?>/>
                                <?= form_error("e_mail") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Fathers Name <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="fathers_name" id="fathers_name" value="<?= $fathers_name ?>" <?= (count($editable_fields)) ? ((in_array('fathers_name', $editable_fields)) ? '' : 'readonly') : '' ?> <?php ($type_of_reg == '3')? print  "": print "disabled"; ?>/>
                                <?= form_error("fathers_name") ?>
                            </div>
                        </div>
                    </fieldset>


                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Personal Information of Applicant</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Father's Name/ Guardian's Name <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="fathers_name_guardians_name" id="fathers_name_guardians_name" value="<?= $fathers_name_guardians_name ?>" <?= (count($editable_fields)) ? ((in_array('fathers_name_guardians_name', $editable_fields)) ? '' : 'readonly') : '' ?> <?php ($type_of_reg == '3')? print  "": print "disabled"; ?>/>
                                <?= form_error("fathers_name_guardians_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mother Name <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mothers_name" id="mothers_name" value="<?= $mothers_name ?>" <?= (count($editable_fields)) ? ((in_array('mothers_name', $editable_fields)) ? '' : 'readonly') : '' ?> <?php ($type_of_reg == '3')? print  "": print "disabled"; ?>/>
                                <?= form_error("mothers_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Date of Birth<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control dp" name="date_of_birth" id="date_of_birth" value="<?= $date_of_birth ?>" <?= (count($editable_fields)) ? ((in_array('date_of_birth', $editable_fields)) ? '' : 'readonly') : '' ?> <?php ($type_of_reg == '3')? print  "": print "disabled"; ?>/>
                                <?= form_error("date_of_birth") ?>
                            </div>
                            <div class="col-md-6">
                                <label> Caste <span class="text-danger">*</span> </label>
                                <select name="caste" <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> class="form-control" id="caste">
                                    <?php if (count($editable_fields)) {
                                        if (in_array('applicant_gender', $editable_fields)) { ?>
                                            <option value="">Select Caste</option>
                                            <option value="General" <?= ($caste === "General") ? 'selected' : '' ?>>General</option>
                                            <option value="OBC/MOBC" <?= ($caste === "OBC/MOBC") ? 'selected' : '' ?>>OBC/MOBC</option>
                                            <option value="ST" <?= ($caste === "ST") ? 'selected' : '' ?>>ST</option>
                                            <option value="SC" <?= ($caste === "SC") ? 'selected' : '' ?>>SC</option>
                                        <?php } else {
                                            echo '<option value="' . $caste . '" selected>' . $caste . '</option>';
                                        }
                                    } else { ?>
                                        <option value="">Select Caste</option>
                                        <option value="General" <?= ($caste === "General") ? 'selected' : '' ?>>General</option>
                                        <option value="OBC/MOBC" <?= ($caste === "OBC/MOBC") ? 'selected' : '' ?>>OBC/MOBC</option>
                                        <option value="ST" <?= ($caste === "ST") ? 'selected' : '' ?>>ST</option>
                                        <option value="SC" <?= ($caste === "SC") ? 'selected' : '' ?>>SC</option>
                                    <?php } ?>
                                </select>
                                <?= form_error("caste") ?>
                            </div>
                        </div>
                        <div class="row form-group ews_div" <?= ($caste === "General") ? '' : 'style="display:none"' ?>>
                            <div class="col-md-6">
                                <label>Economically Weaker Section <span class="text-danger">*</span> </label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="economically_weaker_section" id="ews_yes" value="Yes" <?= ($economically_weaker_section === "Yes") ? 'checked' : '' ?> <?php ($type_of_reg == '3')? print  "": print "disabled"; ?>>
                                    <label class="form-check-label" for="ews_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> type="radio" name="economically_weaker_section" id="ews_no" value="No" <?= ($economically_weaker_section === "No") ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="ews_no">No</label>
                                </div>
                                <?= form_error("economically_weaker_section") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Husband Name</label>
                                <input type="text" class="form-control" name="husbands_name" id="husbands_name" value="<?= $husbands_name ?>" <?= (count($editable_fields)) ? ((in_array('husbands_name', $editable_fields)) ? '' : 'readonly') : '' ?> <?php ($type_of_reg == '3')? print  "": print "disabled"; ?>/>
                                <?= form_error("husbands_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label> Whether Ex-Servicemen <span class="text-danger">*</span> </label>
                                <?php if (count($editable_fields)) {
                                    if (in_array('whether_ex_servicemen', $editable_fields)) { ?>
                                        <div class="form-check form-check-inline">
                                            <input <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> class="form-check-input ex_serviceman" type="radio" name="whether_ex_servicemen" id="ex_service_yes" <?= ($whether_ex_servicemen === "Yes") ? 'checked' : '' ?> value="Yes">
                                            <label class="form-check-label" for="ex_service_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> class="form-check-input ex_serviceman" type="radio" name="whether_ex_servicemen" id="ex_service_no" <?= ($whether_ex_servicemen === "No") ? 'checked' : '' ?> value="No">
                                            <label class="form-check-label" for="ex_service_no">No</label>
                                        </div>
                                    <?php } else { ?>
                                        <div class="form-check form-check-inline">
                                            <input <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> class="form-check-input ex_serviceman" type="radio" name="whether_ex_servicemen" id="ex_service_yes" <?= ($whether_ex_servicemen === "Yes") ? 'checked' : 'disabled' ?> value="Yes">
                                            <label class="form-check-label" for="ex_service_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> class="form-check-input ex_serviceman" type="radio" name="whether_ex_servicemen" id="ex_service_no" <?= ($whether_ex_servicemen === "No") ? 'checked' : 'disabled' ?> value="No">
                                            <label class="form-check-label" for="ex_service_no">No</label>
                                        </div>
                                    <?php }
                                } else { ?>
                                    <div class="form-check form-check-inline">
                                        <input <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> class="form-check-input ex_serviceman" type="radio" name="whether_ex_servicemen" id="ex_service_yes" <?= ($whether_ex_servicemen === "Yes") ? 'checked' : '' ?> value="Yes">
                                        <label class="form-check-label" for="ex_service_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> class="form-check-input ex_serviceman" type="radio" name="whether_ex_servicemen" id="ex_service_no" <?= ($whether_ex_servicemen === "No") ? 'checked' : '' ?> value="No">
                                        <label class="form-check-label" for="ex_service_no">No</label>
                                    </div>
                                <?php } ?>
                                <?= form_error("whether_ex_servicemen") ?>
                            </div>
                            <div class="col-md-6 mt-3 exservice_category d-none">
                                <label> Category of ex-servicemen </label>
                                <select <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> name="ex_servicemen_category" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Airforce" <?= ($ex_servicemen_category === "Airforce") ? 'selected' : '' ?>>Airforce</option>
                                    <option value="Army" <?= ($ex_servicemen_category === "Army") ? 'selected' : '' ?>>Army</option>
                                    <option value="Navy" <?= ($ex_servicemen_category === "Navy") ? 'selected' : '' ?>>Navy</option>
                                </select>
                                <?= form_error("ex_servicemen_category") ?>
                            </div>
                            <!-- </div>
                        <div class="row form-group"> -->
                            <div class="col-md-6 mt-3">
                                <label>Religion<span class="text-danger">*</span> </label>
                                <select name="religion" <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> class="form-control">
                                    <?php if (count($editable_fields)) {
                                        if (in_array('religion', $editable_fields)) { ?>
                                            <option value="">Please Select</option>
                                            <option value="Buddhism" <?= ($religion === "Buddhism") ? 'selected' : '' ?>>Buddhism</option>
                                            <option value="Christianity" <?= ($religion === "Christianity") ? 'selected' : '' ?>>Christianity</option>
                                            <option value="Hinduism" <?= ($religion === "Hinduism") ? 'selected' : '' ?>>Hinduism</option>
                                            <option value="Islam" <?= ($religion === "Islam") ? 'selected' : '' ?>>Islam</option>
                                            <option value="Jainism" <?= ($religion === "Jainism") ? 'selected' : '' ?>>Jainism</option>
                                            <option value="Sikhism" <?= ($religion === "Sikhism") ? 'selected' : '' ?>>Sikhism</option>
                                            <option value="Others/Not Specified" <?= ($religion === "Others/Not Specified") ? 'selected' : '' ?>>Others/ Not Specified</option>
                                        <?php } else {
                                            echo '<option value="' . $religion . '" selected>' . $religion . '</option>';
                                        }
                                    } else { ?>
                                        <option value="">Please Select</option>
                                        <option value="Buddhism" <?= ($religion === "Buddhism") ? 'selected' : '' ?>>Buddhism</option>
                                        <option value="Christianity" <?= ($religion === "Christianity") ? 'selected' : '' ?>>Christianity</option>
                                        <option value="Hinduism" <?= ($religion === "Hinduism") ? 'selected' : '' ?>>Hinduism</option>
                                        <option value="Islam" <?= ($religion === "Islam") ? 'selected' : '' ?>>Islam</option>
                                        <option value="Jainism" <?= ($religion === "Jainism") ? 'selected' : '' ?>>Jainism</option>
                                        <option value="Sikhism" <?= ($religion === "Sikhism") ? 'selected' : '' ?>>Sikhism</option>
                                        <option value="Others/Not Specified" <?= ($religion === "Others/Not Specified") ? 'selected' : '' ?>>Others/ Not Specified</option>
                                    <?php } ?>
                                </select>
                                <?= form_error("religion") ?>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Marital Status <span class="text-danger">*</span> </label>
                                <select <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> name="marital_status" class="form-control">
                                    <?php if (count($editable_fields)) {
                                        if (in_array('marital_status', $editable_fields)) { ?>
                                            <option value="">Please Select</option>
                                            <option value="Divorcee" <?= ($marital_status === "Divorcee") ? 'selected' : '' ?>>Divorcee</option>
                                            <option value="Married" <?= ($marital_status === "Married") ? 'selected' : '' ?>>Married</option>
                                            <option value="Single" <?= ($marital_status === "Single") ? 'selected' : '' ?>>Single</option>
                                            <option value="Widow" <?= ($marital_status === "Widow") ? 'selected' : '' ?>>Widow</option>
                                        <?php } else {
                                            echo '<option value="' . $marital_status . '" selected>' . $marital_status . '</option>';
                                        }
                                    } else { ?>
                                        <option value="">Please Select</option>
                                        <option value="Divorcee" <?= ($marital_status === "Divorcee") ? 'selected' : '' ?>>Divorcee</option>
                                        <option value="Married" <?= ($marital_status === "Married") ? 'selected' : '' ?>>Married</option>
                                        <option value="Single" <?= ($marital_status === "Single") ? 'selected' : '' ?>>Single</option>
                                        <option value="Widow" <?= ($marital_status === "Widow") ? 'selected' : '' ?>>Widow</option>
                                    <?php } ?>
                                </select>
                                <?= form_error("marital_status") ?>
                            </div>
                            <!-- </div>
                        <div class="row form-group"> -->
                            <div class="col-md-6 mt-3">
                                <label>Occupation <span class="text-danger">*</span> </label>
                                <select name="occupation" <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> id="occupation" class="form-control">
                                    <?php if (count($editable_fields)) {
                                        if (in_array('occupation', $editable_fields)) { ?>
                                            <option value="">Please Select</option>
                                            <option value="Business" <?= ($occupation === "Business") ? 'selected' : '' ?>>Business</option>
                                            <option value="Clerks" <?= ($occupation === "Clerks") ? 'selected' : '' ?>>Clerks</option>
                                            <option value="Consultant" <?= ($occupation === "Consultant") ? 'selected' : '' ?>>Consultant</option>
                                            <option value="Student" <?= ($occupation === "Student") ? 'selected' : '' ?>>Student</option>
                                            <option value="Other" <?= ($occupation === "Other") ? 'selected' : '' ?>>Other</option>
                                        <?php } else {
                                            echo '<option value="' . $occupation . '" selected>' . $occupation . '</option>';
                                        }
                                    } else { ?>
                                        <option value="">Please Select</option>
                                        <option value="Business" <?= ($occupation === "Business") ? 'selected' : '' ?>>Business</option>
                                        <option value="Clerks" <?= ($occupation === "Clerks") ? 'selected' : '' ?>>Clerks</option>
                                        <option value="Consultant" <?= ($occupation === "Consultant") ? 'selected' : '' ?>>Consultant</option>
                                        <option value="Student" <?= ($occupation === "Student") ? 'selected' : '' ?>>Student</option>
                                        <option value="Other" <?= ($occupation === "Other") ? 'selected' : '' ?>>Other</option>
                                    <?php } ?>
                                </select>
                                <?= form_error("occupation") ?>
                            </div>
                            <div class="col-md-6 mt-3 occupationType <?= strlen($occupation_type) ? '' : 'd-none' ?> ">
                                <label>Occupation Type <span class="text-danger">*</span> </label>
                                <input <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> type="text" class="form-control" name="occupation_type" id="occupation_type" value="<?= $occupation_type ?>" maxlength="255" />
                                <?= form_error("occupation_type") ?>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Unique Identification Type</label>
                                <select <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> name="unique_identification_type" class="form-control unique_identification_type">
                                    <?php if (count($editable_fields)) {
                                        if (in_array('unique_identification_type', $editable_fields)) { ?>
                                            <option value="">Please Select</option>
                                            <option value="Driving Licence" <?= ($unique_identification_type === "Driving Licence") ? 'selected' : '' ?>>Driving Licence</option>
                                            <option value="Passport" <?= ($unique_identification_type === "Passport") ? 'selected' : '' ?>>Passport</option>
                                            <option value="Voter's Identity Card" <?= ($unique_identification_type === "Voter's Identity Card") ? 'selected' : '' ?>>Voter's Identity Card</option>
                                        <?php } else {
                                            echo '<option value="' . $unique_identification_type . '" selected>' . $unique_identification_type . '</option>';
                                        }
                                    } else { ?>
                                        <option value="">Please Select</option>
                                        <option value="Driving Licence" <?= ($unique_identification_type === "Driving Licence") ? 'selected' : '' ?>>Driving Licence</option>
                                        <option value="Passport" <?= ($unique_identification_type === "Passport") ? 'selected' : '' ?>>Passport</option>
                                        <option value="Voter's Identity Card" <?= ($unique_identification_type === "Voter's Identity Card") ? 'selected' : '' ?>>Voter's Identity Card</option>
                                    <?php } ?>
                                </select>
                                <?= form_error("unique_identification_type") ?>
                            </div>
                            <!-- </div>
                        <div class="row form-group"> -->
                            <div class="col-md-6 mt-3">
                                <label>Unique Identification No </label>
                                <input type="text" <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> class="form-control" name="unique_identification_no" id="unique_identification_no" value="<?= $unique_identification_no ?>" <?= count($editable_fields) ? ((in_array('unique_identification_no', $editable_fields)) ? '' : 'readonly') : '' ?> />
                                <?= form_error("unique_identification_no") ?>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Prominent Identification Mark <span class="text-danger">*</span> </label>
                                <input <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> type="text" class="form-control" name="prominent_identification_mark" id="prominent_identification_mark" value="<?= $prominent_identification_mark ?>" <?= count($editable_fields) ? ((in_array('prominent_identification_mark', $editable_fields)) ? '' : 'readonly') : '' ?> />
                                <?= form_error("prominent_identification_mark") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Physical Attributes </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Height (in cm) </label>
                                <input type="text" <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> class="form-control" name="height_in_cm" value="<?= $height_in_cm ?>" <?= count($editable_fields) ? ((in_array('height_in_cm', $editable_fields)) ? '' : 'readonly') : '' ?> />
                                <?= form_error("height_in_cm") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Weight (Kgs) </label>
                                <input type="text" <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> class="form-control" name="weight_kgs" value="<?= $weight_kgs ?>" <?= count($editable_fields) ? ((in_array('weight_kgs', $editable_fields)) ? '' : 'readonly') : '' ?> />
                                <?= form_error("weight_kgs") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Eye Sight </label>
                                <input type="text" <?php ($type_of_reg == '3')? print  "": print "disabled"; ?>  class="form-control" name="eye_sight" value="<?= $eye_sight ?>" <?= count($editable_fields) ? ((in_array('eye_sight', $editable_fields)) ? '' : 'readonly') : '' ?> />
                                <?= form_error("eye_sight") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Chest (Inch) </label>
                                <input type="text" <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> class="form-control" name="chest_inch" value="<?= $chest_inch ?>" <?= count($editable_fields) ? ((in_array('chest_inch', $editable_fields)) ? '' : 'readonly') : '' ?> />
                                <?= form_error("chest_inch") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Are you Differently abled (PwD)? <span class="text-danger">*</span> </label>
                                <select <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> name="are_you_differently_abled_pwd" class="form-control" id="are_you_differently_abled_pwd">
                                    <?php if (count($editable_fields)) {
                                        if (in_array('are_you_differently_abled_pwd', $editable_fields)) { ?>
                                            <option value="">Please Select</option>
                                            <option value="Yes" <?= ($are_you_differently_abled_pwd === "Yes") ? 'selected' : '' ?>>Yes</option>
                                            <option value="No" <?= ($are_you_differently_abled_pwd === "No") ? 'selected' : '' ?>>No</option>
                                        <?php } else {
                                            echo '<option value="' . $are_you_differently_abled_pwd . '" selected>' . $are_you_differently_abled_pwd . '</option>';
                                        }
                                    } else { ?>
                                        <option value="">Please Select</option>
                                        <option value="Yes" <?= ($are_you_differently_abled_pwd === "Yes") ? 'selected' : '' ?>>Yes</option>
                                        <option value="No" <?= ($are_you_differently_abled_pwd === "No") ? 'selected' : '' ?>>No</option>
                                    <?php } ?>
                                </select>
                                <?= form_error("are_you_differently_abled_pwd") ?>
                            </div>

                            <div class="col-md-6 disablity_cat <?= ($are_you_differently_abled_pwd === 'Yes') ? '' : 'd-none' ?>">
                                <label>Disability Category</label>
                                <select <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> name="disability_category" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php foreach ($disability_categories as $dc) {
                                        if ($disability_category === $dc->disability_category) {
                                            echo '<option value="' . $dc->disability_category . '" selected>' . $dc->disability_category . '</option>';
                                        } else {
                                            echo '<option value="' . $dc->disability_category . '">' . $dc->disability_category . '</option>';
                                        }
                                    } ?>
                                </select>
                                <?= form_error("disability_category") ?>
                            </div>
                            <div class="col-md-6 mt-3 disablity_cat <?= ($are_you_differently_abled_pwd === 'Yes') ? '' : 'd-none' ?>">
                                <label>Additional Disability Type </label>
                                <select <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> name="additional_disability_type" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php foreach ($disability_types as $disability_type) {
                                        if ($additional_disability_type === $disability_type->additional_disability_type) {
                                            echo '<option value="' . $disability_type->additional_disability_type . '" selected>' . $disability_type->additional_disability_type . '</option>';
                                        } else {
                                            echo '<option value="' . $disability_type->additional_disability_type . '">' . $disability_type->additional_disability_type . '</option>';
                                        }
                                    } ?>
                                </select>
                                <?= form_error("additional_disability_type") ?>
                            </div>
                            <div class="col-md-6 mt-3 disablity_cat <?= ($are_you_differently_abled_pwd === 'Yes') ? '' : 'd-none' ?>">
                                <label>Disbility Percentage </label>
                                <select <?php ($type_of_reg == '3')? print  "": print "disabled"; ?>  <?php ($type_of_reg == '3')? print  "": print "disabled"; ?> name="disbility_percentage" class="form-control">
                                    <option value="" autocomplete="off">Please Select</option>
                                    <option value="1" <?= ($disbility_percentage === "Business") ? 'selected' : '' ?>>40%-60%</option>
                                    <option value="2" <?= ($disbility_percentage === "Clerks") ? 'selected' : '' ?>>61% &amp; above</option>
                                </select>
                                <?= form_error("disbility_percentage") ?>
                            </div>

                        </div>

                    </fieldset>

                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <?php
                    if (isset($dbrow->service_data->appl_status) && ($dbrow->service_data->appl_status == 'DRAFT')) {
                    ?>
                    <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/employmentnonaadhaar/reregistration/search_reg_details/' . $obj_id) ?>">
                        <i class="fa fa-angle-double-left"></i> Previous
                    </a>
                    <?php
                    }
                    ?>
                    <button class="btn btn-success frmbtn" id="SAVE" type="submit">
                        <i class="fa fa-angle-double-right"></i> Save &amp; Next
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>

<div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="otpModalLabel">
    <div class="modal-dialog modal-sm" role="document" style="margin:20% auto">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px; font-weight: bold !important; font-size: 28px !important; text-align: center !important; display: block !important">
                <?= $this->lang->line('otp_verification') ?>
            </div>
            <div class="modal-body print-content" id="otpview" style="padding: 5px 15px;">
                <div class="row">
                    <div class="col-md-12">
                        <input id="otp_no" class="form-control text-center" value="" maxlength="6" autocomplete="off" type="text" />
                    </div>
                </div> <!-- End of .row -->
            </div><!--End of .modal-body-->
            <div class="modal-footer" style="display: block !important; text-align: center !important">
                <button type="button" id="verify_btn" class="btn btn-success verify_btn">
                    <i class="fa fa-check"></i><?= $this->lang->line('VERIFY') ?>
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-trash-o"></i><?= $this->lang->line('CANCEL') ?>
                </button>
            </div><!--End of .modal-footer-->
        </div>
    </div>
</div><!--End of #otpModal-->