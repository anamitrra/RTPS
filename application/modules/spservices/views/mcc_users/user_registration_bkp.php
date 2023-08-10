<?php
$districts = $this->districts_model->get_rows(array("state_id" => 1));
$title = "New Registration";
$mobile_verify_status = set_value("mobile_verify_status");
$contact_number = set_value("contact_number");
$applicant_name = set_value("applicant_name");
$designation = set_value("designation");
$emailid = set_value("emailid");
$user_role = set_value("user_role");
$uid = set_value("uid");
$is_dps = set_value("isdps");
$pa_district_name = set_value("pa_district_name");
$pa_district_id = set_value("pa_district_id");
$pa_circle = set_value("pa_circle");

// $ca_district_name = set_value("ca_district_name");
// $mobile_verify_status = (strlen($contact_number) == 10)?1:0;
// echo $mobile_verify_status;
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

    /* Style all input fields */
    input {
        width: 100%;
        /* padding: 12px; */
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        /* margin-top: 6px; */
        /* margin-bottom: 16px; */
    }

    /* Style the submit button */
    input[type=submit] {
        background-color: #04AA6D;
        color: white;
    }

    /* Style the container for inputs */
    .container {
        /* background-color: #f1f1f1; */
        /* padding: 20px; */
    }
</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#contact_number").blur(function() {
            let contactNo = $("#contact_number").val();
            $.get("<?= base_url('spservices/mcc_office_user/registration/check_user/') ?>" + contactNo, function(res) {
                if (!res.status) {
                    // alert(res.message);/
                    Swal.fire({
                        icon: 'warning',
                        text: res.message,
                    })
                }
            });


        });
        $(document).on("click", "#send_mobile_otp", function() {
            let contactNo = $("#contact_number").val();
            if (/^\d{10}$/.test(contactNo)) {
                $("#otp_no").val("");
                $("#otpModal").modal("show");
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?= base_url('spservices/minoritycertificates/otps/send_otp') ?>",
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
                    url: "<?= base_url('spservices/minoritycertificates/otps/verify_otp') ?>",
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

        $(document).on("change", "#pa_district_id", function() {
            let district_id = $(this).val();
            // console.log(district_id)

            $("#pa_district_name").val($(this).find("option:selected").text());
            $.ajax({
                type: "POST",
                url: "<?= base_url('spservices/mcc_office_user/registration/get_circles') ?>",
                data: {
                    "input_name": "pa_circle",
                    "fld_name": "district_id",
                    "fld_value": district_id
                },
                beforeSend: function() {
                    $("#pa_circles_div").html("Loading");
                },
                success: function(res) {
                    $("#pa_circles_div").html(res);
                }
            });
        });

        $(document).on("change", "#user_role_id", function() {
            let user_role = $(this).val();
            let slug = $(this).find("option:selected").attr('data-role_slug');
            if (slug === "ADC" || slug === "AC") {
                $('.checkDPS').show();
            } else if (slug === "OFFSU") {
                $("#office_row").show();
                $('.checkDPS').hide();
                $('#isdps').prop('checked', false);
            } else {
                $('.checkDPS').hide();
                $("#office_row").hide();
                $('#isdps').prop('checked', false);
            }

            $("#role_slug_name").val(slug);
            $("#user_role").val($(this).find("option:selected").text());
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-3">
        <form id="myfrm" method="POST" action="<?php echo base_url('spservices/mcc/user-registration/submit') ?>" enctype="multipart/form-data">
            <input id="mobile_verify_status" name="mobile_verify_status" value="<?= $mobile_verify_status ?>" type="hidden" />
            <input id="pa_district_name" name="pa_district_name" value="" type="hidden" />
            <!-- <input id="ca_district_name" name="ca_district_name" value="" type="hidden" /> -->
            <!-- <input id="form_status" name="form_status" value="" type="hidden" /> -->
            <!-- <input id="submit_mode" name="submit_mode" value="" type="hidden" /> -->
            <div class="card shadow">
                <div class="card-header" style="background-color:#55d6c2;text-align: center; font-size: 24px; color: #000; font-family: georgia,serif; font-weight: bold">
                    Application form for User Registration
                </div>
                <div class="card-body" style="padding:5px">
                    <div class="row">
                        <div class="col">
                            <div class="p-2 text-primary bil">
                                <span class="text-danger">*</span> <b>This form is for user registration of Official Users into RTPS portal. Officials may register on their own. The official will be able to login once the account is activated by the Administrator.</b>
                            </div>
                        </div>
                    </div>
                    <?php if ($this->session->flashdata('office_user_creation_fail') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('office_user_creation_error') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>User creation failed!</strong> <?= $this->session->flashdata('office_user_creation_error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('office_user_creation_success') != null) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?= $this->session->flashdata('office_user_creation_success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                    <?php } //End of if 
                    ?>

                    <fieldset class="" style="margin-top:40px">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Name<span class="text-danger">*</span> </label>
                                <div class="input-group"></div>
                                <input value="<?= $applicant_name ?>" class="form-control" name="applicant_name" id="applicant_name" maxlength="255" type="text" />
                                <!-- <?= form_error("applicant_name") ?> -->
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="contact_number">
                                    Mobile Number <span class="text-danger">*</span>
                                    <small class="text-info pull-right">(Please verify the contact number using OTP)</small>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" name="contact_number" type="number" id="contact_number" maxlength="10" value="<?= $contact_number ?>" <?= ($mobile_verify_status == 1) ? 'readonly' : '' ?> type="text" />
                                    <div class="input-group-append ">
                                        <a href="javascript:void(0)" class="btn btn-outline-danger <?= ($mobile_verify_status === '1') ? 'd-none' : '' ?>" id="send_mobile_otp">Verify</a>
                                        <a href="javascript:void(0)" class="btn btn-outline-success <?= ($mobile_verify_status === '1') ? '' : 'd-none' ?>" id="verified"><i class="fa fa-check"></i></a>
                                    </div>
                                </div>
                                <!-- <?= form_error('contact_number') ?> -->
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Email id</label>
                                <input class="form-control" name="emailid" type="email" value="<?= $emailid ?>" maxlength="100" type="text" />
                                <!-- <?= form_error("emailid") ?> -->
                            </div>
                            <input type="hidden" name="role_slug_name" id="role_slug_name" />
                            <input type="hidden" name="user_role" id="user_role" />
                            <div class="col-md-6 form-group">
                                <label>Select Designation<span class="text-danger">*</span> </label>
                                <select id="user_role_id" name="user_role_id" class="form-control">
                                    <option value="<?= $user_role ?>"><?= strlen($user_role) ? $user_role : 'Please Select' ?></option>
                                    <?php if (!empty($user_roles)) { ?>
                                        <?php foreach ($user_roles as $role) { ?>
                                            <option data-role_slug="<?= $role->slug ?>" value="<?= strval($role->_id->{'$id'}) ?>"><?php echo (isset($role->role_name)) ?  $role->role_name : "" ?></option>
                                        <?php }  ?>
                                    <?php } else { ?>
                                        <p>Records not found!</p>
                                    <?php } ?>
                                </select>
                                <!-- <?= form_error("user_role") ?> -->
                            </div>
                        </div>
                        <div class="row checkDPS" style="display: none;">
                            <div class="col form-group">
                                <label>Is Designated Public Servant ?</label>
                                <div class="form-check form-check-inline ">
                                    <input <?php echo ($is_dps == 1) ? 'checked' : '' ?> value="1" class="form-check-input" type="radio" name="isdps" id="isdps">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Yes
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input <?php echo ($is_dps == 0) ? 'checked' : '' ?> value="0" class="form-check-input" type="radio" name="isdps" id="isdps">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        No
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col form-group">
                                <label>District<span class="text-danger">*</span> </label>
                                <select name="pa_district_name" id="pa_district_id" class="form-control">
                                    <option value="<?= $pa_district_name ?>"><?= strlen($pa_district_name) ? $pa_district_name : 'Please Select' ?></option>
                                    <?php if ($districts) {
                                        foreach ($districts as $dist) {
                                            $selectedDist = ($pa_district_name === $dist->district_name) ? 'selected' : '';
                                            echo '<option value="' . $dist->district_name . '">' . $dist->district_name . '</option>';
                                        } //End of foreach()
                                    } //End of if 
                                    ?>
                                </select>
                                <!-- <?= form_error("pa_district_name") ?> -->
                            </div>
                            <div class="col form-group" id="myDiv">
                                <label id="myDiv">Circle</label>
                                <div id="pa_circles_div">
                                    <select name="pa_circle" id="pa_circle" class="form-control">
                                        <option value="<?= $pa_circle ?>"><?= strlen($pa_circle) ? $pa_circle : 'Please Select' ?></option>
                                    </select>
                                </div>
                                <!-- <?= form_error("pa_circle") ?> -->
                            </div>
                        </div>
                    </fieldset>
                    <div class="row" id="office_row" style="display: none;">
                        <div class="col form-group col-sm-12 col-md-6">
                            <label>Offline Office<span class="text-danger">*</span> </label>
                            <select name="office_id" id="office_id" class="form-control">
                                <option value="">Select Office </option>
                                <?php if ($office_list) {
                                    foreach ($office_list as $office) {
                                        // $selectedDist = ($pa_district_name === $dist->district_name) ? 'selected' : '';
                                        echo '<option value="' . $office->office_id . '">' . $office->office_name . '</option>';
                                    } //End of foreach()
                                } //End of if 
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <!--End of .card-body -->
                <div class="card-footer text-center">
                    <button class="btn btn-success" id="SAVE" type="submit">
                        <i class="fa fa-save"></i> SUBMIT
                    </button>
                    <a class="btn btn-secondary" href="<?php echo base_url() . 'spservices/mcc_office_user/registration/reset' ?>" type="button" class="btn btn-primary btn-sm"><i class="fa fa-refresh"></i> RESET</a>
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
            <div class="modal-header" style="padding: 5px 15px; font-weight: bold !important; font-size: 28px !important; text-align: center !important; display: block !important">
                OTP VERIFICATION
            </div>
            <div class="modal-body print-content" id="otpview" style="padding: 5px 15px;">
                <div class="row">
                    <div class="col-md-12">
                        <input id="otp_no" class="form-control text-center" value="" maxlength="6" autocomplete="off" type="text" />
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