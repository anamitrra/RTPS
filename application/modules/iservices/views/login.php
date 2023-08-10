<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style>
    .parsley-errors-list {
        color: red;
    }

    .citizen-login input {
        border-radius: 0;
    }

    .captchaView {
        display: flex;
        flex-direction: row;
    }

    #captchaParent {
        flex: 70%;
        margin-bottom: 2px;
        border: 1px dotted black;
        margin-right: 5px;
    }

    .captcha-refresh {
        flex: 30%;
        border: 0;
        border-radius: 0;
        width: 50px;
        height: 36px !important;
        background: #888;
        color: #eee;
    }

    .captcha-refresh:hover {
        background: #888;
        color: #eee;
    }

    #captchaParent img {
        width: 100% !important;
        height: 35px !important;
    }

    #send_otp {
        display: block;
        border-radius: 0;
        width: 100%
    }

    #login,
    #resendOtp {
        border-radius: 0;
        display: block;
        width: 100%
    }
</style>
<div class="citizen-login">
    <div class="row">
        <div class="card border-top-0 rounded-0 ">
            <form id="loginForm" method="POST" action="">
                <div class="card-body mt-3">
                    <div id="loginInfo">
                        <div class="form-group">
                            <label for="contactNumber">Citizen Mobile Number</label>
                            <input type="text" class="form-control" name="contactNumber" id="contactNumber" placeholder="Enter your contact number" minlength="10" maxlength="10" pattern="^[6-9]\d{9}$" required>
                        </div>
                        <div class="captchaDiv">
                            <div class="form-group mt-2">
                                <label for="captcha">Enter Captcha</label>
                                <div class="captchaView">
                                    <div class="" id="captchaParent">
                                        <?= $cap['image']; ?>
                                    </div>
                                    <button type="button" class="btn btn-sm captcha-refresh" id="refreshCaptcha">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control" name="captcha" id="captcha" placeholder="Enter captcha text" maxlength="6" required>
                            </div>
                        </div>
                        <button type="button" id="send_otp" class="btn btn-block btn-primary mt-2 mb-2">
                            <span id="loginInfoSubmitProcess" class="d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                            </span>
                            <span id="sendOTPbtnTxt">Send OTP</span>
                        </button>
                    </div>

                    <div id="otpDetails" class="d-none">
                        <div class="form-group">
                            <div class="alert alert-primary" role="alert">
                                An OTP is being sent to <span class="font-weight-bold" id="mobile_no_span"></span><br />Please enter the otp.
                            </div>
                            <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP" data-parsley-required-message="OTP required" required>
                        </div>
                        <button type="button" id="login" class="btn btn-block btn-primary mt-1 mb-1">Submit OTP
                        </button>
                        <button type="button" id="resendOtp" class="btn btn-block btn-warning mb-2"> <i class="fa fa-paper-plane mr-3"></i>Resend OTP
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var sendOTPurl = '<?= base_url('iservices/send-otp') ?>';
    var loginUrl = '<?= base_url('iservices/process-login') ?>';
    var guidelineUrl = '<?= base_url('iservices/guidelines') ?>';
</script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/iservices/js/custom/login.js') ?>"></script>

<script>
    $(function() {
        var captchaParentRef = $('#captchaParent');
        var refreshCaptchaRef = $('#refreshCaptcha');
        const refreshCaptchaURL = '<?= base_url('appeal/refresh-captcha') ?>';
        refreshCaptchaRef.click(function() {

            $.get(refreshCaptchaURL, function(response) {
                if (response.status) {
                    captchaParentRef.html(response.captcha.image);
                } else {
                    Swal.fire('Failed', 'Failed to refresh captcha!!!', 'error');
                }
            }).fail(function() {
                Swal.fire('Failed', 'Failed to refresh captcha!!!', 'error');
            });
        });
    })
</script>