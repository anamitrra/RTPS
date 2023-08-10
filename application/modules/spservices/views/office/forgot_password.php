<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style>
    .parsley-errors-list {
        color: red;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-sm-5 mx-auto">
            <div class="card rounded-0 mt-2 mb-1">
                <div class="card-header">
                    <h5 class="text-center">Reset Password</h5>
                </div>
                <div class="card-body">
                    <form id="loginForm" method="POST" action="">
                        <div id="loginInfo">
                            <div class="form-group">
                                <label for="contactNumber">Registered Mobile Number</label>
                                <input type="text" class="form-control" name="contactNumber" id="contactNumber" placeholder="Enter your contact number" minlength="10" maxlength="10" pattern="^[6-9]\d{9}$" required>
                            </div>

                            <button type="button" id="send_otp" class="btn btn-block btn-primary mb-2">
                                <span id="loginInfoSubmitProcess" class="d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                                </span>
                                <span id="sendOTPbtnTxt">Send OTP</span>
                            </button>
                        </div>
                        <div id="otpDetails" class="d-none">
                            <!-- <div id="otpDetails"> -->
                                <!-- <div >
                                    <p>Password should be minimum 8 characters including</p>
                                </div> -->
                            <div class="form-group">
                                <label for="contactNumber">New Password</label>
                                <input type="password" class="form-control" name="new_password" id="new_password">
                            </div>
                            <div class="form-group">
                                <label for="contactNumber">Confirm Password</label>
                                <input type="password" class="form-control" name="conf_password" id="conf_password">
                            </div>
                            <div class="form-group">
                                <label for="">Enter OTP</label>
                                <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP" data-parsley-required-message="OTP required">
                            </div>

                            <button type="button" id="pass_change" class="btn btn-block btn-primary">Change Password
                            </button>
                            <button type="button" id="resendOtp" class="btn btn-block btn-outline-info mb-2"> <i class="fas fa-paper-plane mr-3"></i>Resend OTP
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var checkMobile = '<?= base_url('spservices/check-mobile') ?>';
    var sendOTPurl = '<?= base_url('spservices/send-forgot-otp') ?>';
    var resetUrl = '<?= base_url('spservices/reset-password') ?>';
</script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/iservices/js/custom/forgot_password.js') ?>"></script>