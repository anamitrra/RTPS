<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-sm-5 mx-auto">
            <div class="card my-2">
                <div class="card-body">
                    <form id="loginForm" method="POST" action="">
                        <div id="loginInfo">
                            <h2 class="text-center">Login</h2></br>

                            <div class="form-group">
                                <label for="contactEmail">Email-ID</label>
                                <input type="email" class="form-control" name="contactEmail" id="contactEmail" placeholder="Enter your email-id"  required>
                            </div>

                            <button type="button" id="send_otp" class="btn btn-block btn-primary mb-2" >
                                <span id="loginInfoSubmitProcess" class="d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                                </span>
                                <span id="sendOTPbtnTxt">Send OTP</span>
                            </button>


                        </div>


                            <div id="otpDetails" class="d-none">
                                <div class="form-group">
                                    <div class="alert alert-primary" role="alert">
                                        An OTP is being sent to <span class="font-weight-bold" id="mobile_no_span"></span><br/>Please enter the otp.
                                    </div>
                                    <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP" data-parsley-required-message="OTP required" required>
                                </div>

                                <button type="button" id="login" class="btn btn-block btn-primary" >Submit OTP
                                </button>
                                <button type="button" id="resendOtp" class="btn btn-block btn-outline-info mb-2" > <i class="fas fa-paper-plane mr-3"></i>Resend OTP
                                </button>
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var sendOTPurl     = '<?=base_url('expr/admin/send-otp')?>';
    var loginUrl = '<?=base_url('expr/admin/process-login')?>';
    var guidelineUrl = '<?=base_url('expr/guidelines')?>';
</script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/expr/js/custom/admin_login.js') ?>"></script>
