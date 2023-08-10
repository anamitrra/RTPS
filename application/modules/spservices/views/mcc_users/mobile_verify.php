<?php
$mobile_verify_status = set_value("mobile_verify_status");
$contact_number = $this->session->userdata('admin')['mobile'];
?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#send_mobile_otp", function() {
            let contactNo = $("#contact_number").val();
            if (/^\d{10}$/.test(contactNo)) {
                $("#otp_no").val("");
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?= base_url('spservices/mcc_users/otps/send_otp') ?>",
                    data: {
                        "mobile_number": contactNo
                    },
                    beforeSend: function() {
                        $(".otp_div").removeClass("d-none");
                        $("#otp_no").attr("placeholder", "Sending OTP... Please wait");
                    },
                    success: function(res) {
                        if (res.status) {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'OTP sent successfully.',
                                showConfirmButton: false,
                                timer: 1500
                            })
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
                    url: "<?= base_url('spservices/mcc_users/otps/verify_otp') ?>",
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
                            console.log(res);
                            $("#mobile_verify_status").val(1);
                            $("#contact_number").prop("readonly", true);
                            $("#send_mobile_otp").addClass('d-none');
                            $("#verified").removeClass('d-none');
                            Swal.fire({
                                text: "Mobile Number has been successfully verified.",
                                icon: 'success',
                                showCancelButton: false,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                window.location = "<?= base_url('spservices/mcc_users/admindashboard')?>";
                            })
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
    })
</script>
<main class="rtps-container">
    <div class="container mt-4" style="margin-bottom:20%;">
        <div class="row">
            <div class="col-md-5 mx-auto">
                <?php
                if (!empty($this->session->flashdata('success_msg'))) {
                    echo "<div class='alert alert-success text-center'>" . $this->session->flashdata('success_msg') . "</div>";
                }
                if (!empty($this->session->flashdata('msg'))) {
                    echo "<div class='alert alert-danger text-center'>" . $this->session->flashdata('msg') . "</div>";
                }
                ?>
                <div class="card">
                    <div class="card-header bg-dark text-white text-center"><b>Mobile Number Verification</b></div>
                    <div class="card-body">
                        <form method="POST" action="<?= base_url('spservices/mcc/sendResetlink') ?>" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="contact_number">
                                    Mobile Number <span class="text-danger">*</span>
                                    <small class="text-info pull-right">(Please verify the contact number using OTP)</small>
                                </label>
                                <div class="input-group">
                                    <input class="form-control" name="contact_number" type="number" id="contact_number" maxlength="10" value="<?= $contact_number ?>" <?= ($mobile_verify_status == 1) ? 'readonly' : '' ?> type="text" readonly />
                                    <div class="input-group-append ">
                                        <a href="javascript:void(0)" class="btn btn-outline-danger <?= ($mobile_verify_status === '1') ? 'd-none' : '' ?>" id="send_mobile_otp">Send OTP</a>
                                        <a href="javascript:void(0)" class="btn btn-outline-success <?= ($mobile_verify_status === '1') ? '' : 'd-none' ?>" id="verified"><i class="fa fa-check"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="otp_div d-none">
                                <div class="form-group">
                                    <label for="otp">Enter OTP</label>
                                    <input id="otp_no" class="form-control text-center" value="" maxlength="6" autocomplete="off" type="text" />
                                </div>
                                <div class="form-group text-center">
                                    <button type="button" id="verify_btn" class="btn btn-success verify_btn">
                                        VERIFY
                                    </button>
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                                        CANCEL
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>