<?php
    
if($dbrow) {
    $obj_id = $dbrow->{'_id'}->{'$id'};       
    $rtps_trans_id = $dbrow->form_data->rtps_trans_id;
    $form_status = $dbrow->service_data->appl_status;

    $reg_no = $dbrow->form_data->reg_no;
    $reg_date = $dbrow->form_data->reg_date;
    $department_name = $dbrow->form_data->department_name;
    $designation = $dbrow->form_data->designation;
    $joining_date = $dbrow->form_data->joining_date;
    $current_salary = $dbrow->form_data->current_salary;
    $skill_name = $dbrow->form_data->skill_name;
    $skill_used = $dbrow->form_data->skill_used;
    $notice_period = $dbrow->form_data->notice_period;
    $contact_number = $dbrow->form_data->mobile_number;
    $dob = $dbrow->form_data->dob;
}
else {
    $obj_id = null;        
    $rtps_trans_id = null;
    $status = null;
    $certificate_language = set_value("certificate_language");
    //$contact_number = (strlen($this->session->mobile)==10)?$this->session->mobile:set_value("contact_number");
    $contact_number = null;
    $reg_no = set_value("reg_no");
    $reg_date = set_value("reg_date");
    $department_name = set_value("department_name");
    $designation = set_value("designation");
    $dob = set_value("dob");
    $joining_date = set_value("joining_date");
    $current_salary = set_value("current_salary");
    $skill_name = set_value("skill_name");
    $skill_used = set_value("skill_used");
    $notice_period = set_value("notice_period");
    $status = null;
}
//$mobile_verify_status = (strlen($contact_number) == 10)?1:0;
$mobile_verify_status = 0;
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

.form-group {
    margin-bottom: .4rem;
}

label {
    margin-bottom: .1rem;
}
</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/webcamjs/webcam.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/iservices/js/custom/digilocker.js') ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.number_input').keypress(function(e) {
        var charCode = (e.which) ? e.which : event.keyCode
        if (String.fromCharCode(charCode).match(/[^0-9]/g))
            return false;
    });


    $(document).on("click", "#send_mobile_otp", function() {
        let contactNo = $("#contact_number").val();
        if (/^\d{10}$/.test(contactNo)) {
            $("#otp_no").val("");
            $("#otpModal").modal("show");
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?=base_url('spservices/minoritycertificates/otps/send_otp')?>",
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
            alert("Contact number is invalid. Please enter a valid number");
            $("#contact_number").val();
            $("#contact_number").focus();
            return false;
        } //End of if else
    }); //End of onclick #send_mobile_otp



    $(document).on("click", "#verify_mobile_otp", function() {
        let contactNo = $("#contact_number").val();
        var otpNo = $("#otp_no").val();
        if (/^\d{6}$/.test(otpNo)) {
            $.ajax({
                type: "POST",
                dataType: "json",
                url: "<?=base_url('spservices/employment_aadhaar_based/otps/verify_otp')?>",
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
                        $("#contact_number").prop("readonly", true);
                        $("#send_mobile_otp").addClass('d-none');
                        $("#verified").removeClass('d-none');
                    } else {
                        alert(res.msg);
                        $("#otp_no").attr("placeholder", "ENTER THE 6-DIGIT OTP");
                    } //End of if else
                }
            });
        } else {
            alert("OTP is invalid. Please enter a valid otp");
            $("#otp_no").val();
            $("#otp_no").focus();
        } //End of if else
    }); //End of onClick #verify_mobile_otp

    $(".dp").datepicker({
        format: 'dd/mm/yyyy',
        endDate: '+0d',
        autoclose: true
    });

    $(document).on("click", ".frmbtn", function() {
        var clickedBtn = $(this).attr("id"); //alert(clickedBtn);
        if (clickedBtn === 'FORM_SUBMIT') {
            $("#form_status").val("DRAFT");
        } else if (clickedBtn === 'QUERY_SUBMIT') {
            $("#form_status").val("QS");
        } else {
            $("#form_status").val("");
        } //End of if else

        Swal.fire({
            title: 'Are you sure?',
            text: "Once you submitted, you will not be able to revert this action",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                $("#myfrm").submit();
            }
        });
    });
});

function showDiv(divId, element) {
    document.getElementById(divId).style.display = element.value == "others" ? 'block' : 'none';
}

function toggleEmploymentFieldsets() {
    var selectedEmployment = document.getElementById('employment').value;
    var notSelfEmploymentFieldset = document.getElementById('notSelfEmployment');
    var selfEmploymentFieldset = document.getElementById('SelfEmployment');

    if (selectedEmployment === 'Self Employed') {
        notSelfEmploymentFieldset.style.display = 'none';
        selfEmploymentFieldset.style.display = 'block';
    } else {
        notSelfEmploymentFieldset.style.display = 'block';
        selfEmploymentFieldset.style.display = 'none';
        resetFields(notSelfEmploymentFieldset);
    }
}

function resetFields(fieldset) {
    var inputs = fieldset.getElementsByTagName('input');
    var selects = fieldset.getElementsByTagName('select');

    for (var i = 0; i < inputs.length; i++) {
        inputs[i].value = '';
    }

    for (var i = 0; i < selects.length; i++) {
        selects[i].selectedIndex = 0;
    }
}

window.onload = function() {
    toggleEmploymentFieldsets();
};
// Initialize the fieldsets on page load
toggleEmploymentFieldsets();
$(document).ready(function() {
    $('#organization').on('change', function() {
        var selectedOption = $(this).val();
        if (selectedOption === 'Others') {
            $('#otherOrganization').prop('disabled', false);
        } else {
            $('#otherOrganization').prop('disabled', true);
        }
    });
});

function toggleAdditionalTextbox() {
    var skillUsedSelect = document.getElementById("skill_used");
    var skillTextbox = document.getElementById("other_skill");

    if (skillUsedSelect.value === "Yes") {
        skillTextbox.disabled = false;
    } else {
        skillTextbox.disabled = true;
    }
}


function toggleNoticePeriodTextbox() {
    var noticePeriodSelect = document.getElementById("notice_period");
    var noticePeriodTextbox = document.getElementById("notice_period_value");

    if (noticePeriodSelect.value === "Yes") {
        noticePeriodTextbox.disabled = false;
    } else {
        noticePeriodTextbox.disabled = true;
    }
}

function toggleNatureOfSelfEmployment(value) {
    var otherNatureContainer = document.getElementById('otherNatureContainer');
    var otherNatureTextarea = document.getElementById('otherNatureOfSelfEmployment');

    if (value === 'Other') {
        otherNatureContainer.style.display = 'block';
        otherNatureTextarea.disabled = false;
    } else {
        otherNatureContainer.style.display = 'none';
        otherNatureTextarea.disabled = true;
    }
}

function toggleOrganization(value) {
    var otherOrganizationContainer = document.getElementById("otherOrganizationContainer");
    var otherOrganizationInput = document.getElementById("otherOrganization");

    if (value === "Others") {
        otherOrganizationInput.removeAttribute("disabled");
    } else {
        otherOrganizationInput.setAttribute("disabled", "disabled");
    }
}
</script>
</script>
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/employment_status/Update/submit') ?>"
            enctype="multipart/form-data" onsubmit="$(this).find('select,radio,input').prop('disabled', false)">
            <input id="form_status" name="form_status" value="<?=$form_status?>" type="hidden" />
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="rtps_trans_id" value="<?=$rtps_trans_id?>" type="hidden" />
            <input id="mobile_verify_status" name="mobile_verify_status" value="<?=$mobile_verify_status?>"
                type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header"
                    style="text-align: center; font-size: 18px; color: #000; font-family: georgia,serif; font-weight: bold">
                    APPLICATION FORM FOR EMPLOYMENT STATUS UPDATE</br>
                </div>

                <fieldset class="border border-success">
                    <legend class="h5">Important / দৰকাৰী </legend>
                    <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                    <ul style="  margin-left: 24px; margin-top: 10px">
                        <li>1. All the * marked fields are mandatory and need to be filled up..</li>
                        <li>১. * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব</li>
                    </ul>
                </fieldset>

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
                    <?php }?>


                    <fieldset id="regDetails" class="border border-success" style="margin-top:40px">
                        <legend class="h5">Registration Card Details<font size="3px"></font>
                        </legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Registration Number of the candidate<font size="2px"></font> <span
                                        class="text-danger">*</span> </label>
                                <input class="form-control" name="reg_no" id="reg_no" value="<?=$reg_no?>"
                                    maxlength="255" type="text" required="true" />
                                <?= form_error("reg_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Registration Date<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control dp" name="reg_date" id="reg_date"
                                    value="<?= $reg_date?>" maxlength="255" />
                                <?= form_error("reg_date") ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label for="contact_number">Mobile Number<font size="2px"></font><span
                                        class="text-danger">*</span>
                                    <small class="text-info pull-right">(Please verify the contact number using
                                        OTP)</small>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" name="contact_number" id="contact_number" maxlength="10"
                                        value="<?=$contact_number?>" <?=($mobile_verify_status == 1)?'readonly':''?>
                                        type="text" />
                                    <div class="input-group-append">
                                        <a href="javascript:void(0)"
                                            class="btn btn-outline-danger <?=($mobile_verify_status == 1)?'d-none':''?>"
                                            id="send_mobile_otp">Verify</a>
                                        <a href="javascript:void(0)"
                                            class="btn btn-outline-success <?=($mobile_verify_status == 1)?'':'d-none'?>"
                                            id="verified"><i class="fa fa-check"></i></a>
                                    </div>
                                </div>
                                <?= form_error("contact_number") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Date of Birth<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control dp" name="dob" id="dob" value="<?= $dob?>"
                                    maxlength="255" />
                                <?= form_error("dob") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="employment">Type of Employment:<span class="text-danger">*</span> </label>
                                <select name="employment" id="employment" class="form-control"
                                    onchange="toggleEmploymentFieldsets()">
                                    <option value="">Please select</option>
                                    <option value="Apprentice"
                                        <?= ($this->input->post('employment') == 'Apprentice') ? 'selected' : '' ?>>
                                        Apprentice
                                    </option>
                                    <option value="Employed-Full time Government"
                                        <?= ($this->input->post('employment') == 'Employed-Full time Government') ? 'selected' : '' ?>>
                                        Employed - Full time Government
                                    </option>
                                    <option value="Employed-Full Time Private"
                                        <?= ($this->input->post('employment') == 'Employed-Full Time Private') ? 'selected' : '' ?>>
                                        Employed - Full Time Private
                                    </option>
                                    <option value="Employed-Full time on Contract"
                                        <?= ($this->input->post('employment') == 'Employed-Full time on Contract') ? 'selected' : '' ?>>
                                        Employed - Full time on Contract
                                    </option>
                                    <option value="Employed Part Time- Govt"
                                        <?= ($this->input->post('employment') == 'Employed Part Time-Govt') ? 'selected' : '' ?>>
                                        Employed Part Time-Govt
                                    </option>
                                     <option value="Employed Part Time- Private"
                                        <?= ($this->input->post('employment') == 'Employed Part Time-Private') ? 'selected' : '' ?>>
                                        Employed Part Time-Private
                                    </option>
                                    <option value="Employed on Daily Wage"
                                        <?= ($this->input->post('employment') == 'Employed on Daily Wage') ? 'selected' : '' ?>>
                                        Employed on Daily Wage
                                    </option>
                                    <option value="Self Employed"
                                        <?= ($this->input->post('employment') == 'Self Employed') ? 'selected' : '' ?>>
                                        Self Employed
                                    </option>
                                </select>
                                <?= form_error("employment") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset id="notSelfEmployment" class="border border-success"
                        style="margin-top: 40px; <?= ($this->input->post('employment') !== 'Self Employed') ? 'display: block;' : 'display: none;' ?>">

                        <legend class="h5">Organization Details<span class="text-danger">*</span>
                            <font size="3px"></font>
                        </legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="organization">Organization:<font size="2px"></font><span
                                        class="text-danger">*</span></label>
                                <select name="organization" id="organization" class="form-control"
                                    onchange="toggleOrganization(this.value)">
                                    <option value="">Please select</option>
                                    <option value="State Government"
                                        <?= set_select('organization', 'State Government') ?>>State Government</option>
                                    <option value="Central Government"
                                        <?= set_select('organization', 'Central Government') ?>>Central Government
                                    </option>
                                    <option value="Public sector undertaking"
                                        <?= set_select('organization', 'Public sector undertaking') ?>>Public sector
                                        undertaking</option>
                                    <option value="Private" <?= set_select('organization', 'Private') ?>>Private
                                    </option>
                                    <option value="Others" <?= set_select('organization', 'Others') ?>>Others</option>
                                </select>
                                <?= form_error("organization") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <div id="otherOrganizationContainer">
                                    <label for="otherOrganization">Specify Other Organization:<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="otherOrganization" id="otherOrganization"
                                        class="form-control"
                                        <?= (set_value('organization') !== 'Others') ? 'disabled' : '' ?> required>
                                </div>
                                <?= form_error("otherOrganization") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Department/ Company/ Sector<font size="2px"></font> <span
                                        class="text-danger">*</span> </label>
                                <input class="form-control" name="department_name" id="department_name"
                                    value="<?=$department_name?>" maxlength="255" type="text" required="true" />
                                <?= form_error("department_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Designation<font size="2px"></font> <span class="text-danger">*</span> </label>
                                <input class="form-control" name="designation" id="designation"
                                    value="<?=$designation?>" maxlength="255" type="text" required="true" />
                                <?= form_error("designation") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Date of Joining<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control dp" name="joining_date" id="joining_date"
                                    value="<?= $joining_date?>" maxlength="255" />
                                <?= form_error("joining_date") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Current Salary <span class="text-danger">*</span>
                                    <font size="2px"></font>
                                </label>
                                <input class="form-control" name="current_salary" id="current_salary"
                                    value="<?=$current_salary?>" maxlength="255" type="text" required="true"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                <?= form_error("current_salary") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Skill used for the post<span class="text-danger">*</span></label>
                                <select name="skill_used" id="skill_used" class="form-control"
                                    onchange="toggleAdditionalTextbox()">
                                    <option value="">Please Select</option>
                                    <option value="Yes"
                                        <?= ($this->input->post('skill_used') === "Yes") ? 'selected' : '' ?>>Yes
                                    </option>
                                    <option value="No"
                                        <?= ($this->input->post('skill_used') === "No") ? 'selected' : '' ?>>No</option>
                                </select>
                                <?= form_error("skill_used") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <div id="additionalTextboxContainer">
                                    <label>Enter the Skill:<span class="text-danger">*</span></label>
                                    <input type="text" name="other_skill" id="other_skill" class="form-control"
                                        <?= form_error("other_skill") ? '' : 'disabled' ?>>
                                </div>
                                <?= form_error("other_skill") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Are you in notice period<span class="text-danger">*</span></label>
                                <select name="notice_period" id="notice_period" class="form-control"
                                    onchange="toggleNoticePeriodTextbox()">
                                    <option value="">Please Select</option>
                                    <option value="Yes"
                                        <?= ($this->input->post('notice_period') === "Yes") ? 'selected' : '' ?>>Yes
                                    </option>
                                    <option value="No"
                                        <?= ($this->input->post('notice_period') === "No") ? 'selected' : '' ?>>No
                                    </option>
                                </select>
                                <?= form_error("notice_period") ?>
                            </div>
                            <div class="col-md-6 form-group" id="noticePeriodTextboxContainer">
                                <label>Enter the notice period:<span class="text-danger">*</span></label>
                                <input type="text" name="notice_period_value" id="notice_period_value"
                                    class="form-control" <?= form_error("notice_period_value") ? '' : 'disabled' ?>>
                                <?= form_error("notice_period_value") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset id="SelfEmployment" class="border border-success"
                        style="margin-top: 40px; <?= ($this->input->post('employment') == 'Self Employed') ? 'display: block;' : 'display: none;' ?>">

                        <legend class="h5">Self Employment Details<font size="3px"></font><span
                                class="text-danger">*</span></legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="natureOfSelfEmployment">Nature of Self-Employment:<font size="2px"></font>
                                    <span class="text-danger">*</span></label>
                                <select name="natureOfSelfEmployment" id="natureOfSelfEmployment" class="form-control"
                                    onchange="toggleNatureOfSelfEmployment(this.value)">
                                    <option value="">Please Select</option>
                                    <option value="Business" <?= set_select('natureOfSelfEmployment', 'Business') ?>>
                                        Business</option>
                                    <option value="Cultivator"
                                        <?= set_select('natureOfSelfEmployment', 'Cultivator') ?>>Cultivator</option>
                                    <option value="NGO" <?= set_select('natureOfSelfEmployment', 'NGO') ?>>NGO</option>
                                    <option value="Other" <?= set_select('natureOfSelfEmployment', 'Other') ?>>Other
                                    </option>
                                </select>
                                <?= form_error("natureOfSelfEmployment") ?>
                            </div>
                            <div class="col-md-6 form-group" id="otherNatureContainer">
                                <label for="otherNatureOfSelfEmployment">Description of Other Self Employment:<span
                                        class="text-danger">*</span></label>
                                <textarea name="otherNatureOfSelfEmployment" id="otherNatureOfSelfEmployment"
                                    class="form-control" maxlength="250" rows="4"
                                    <?= ($this->input->post('natureOfSelfEmployment') !== 'Other') ? 'disabled' : '' ?>><?= set_value('otherNatureOfSelfEmployment') ?></textarea>
                                <?= form_error("otherNatureOfSelfEmployment") ?>
                            </div>
                        </div>
                </div>
                </fieldset>
            </div>
            <!--End of .card-body -->
            <div class="card-footer text-center">
                <button class="btn btn-danger" type="reset">
                    <i class="fa fa-refresh"></i> RESET</button>
                <button class="btn btn-primary frmbtn" id="FORM_SUBMIT" type="button">
                    <i class="fa fa-angle-double-right"></i>SUBMIT</button>
            </div>
            <!--End of .card-footer-->
    </div>
    <!--End of .card-->
    </form>
    </div>
    <!--End of .container-->
</main>
<div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="otpModalLabel">
    <div class="modal-dialog modal-sm" role="document" style="margin:20% auto">
        <div class="modal-content">
            <div class="modal-header"
                style="padding: 5px 15px; font-weight: bold !important; font-size: 28px !important; text-align: center !important; display: block !important">
                <?=$this->lang->line('otp_verification')?>
            </div>
            <div class="modal-body print-content" id="otpview" style="padding: 5px 15px;">
                <div class="row">
                    <div class="col-md-12">
                        <input id="otp_no" class="form-control text-center" value="" maxlength="6" autocomplete="off"
                            type="text" />
                    </div>
                </div> <!-- End of .row -->
            </div>
            <!--End of .modal-body-->
            <div class="modal-footer" style="display: block !important; text-align: center !important">
                <button type="button" id="verify_btn" class="btn btn-success verify_btn">
                    VERIFY
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    CANCEL
                </button>
            </div>
            <!--End of .modal-footer-->
        </div>
    </div>
</div>
<!--End of #otpModal-->