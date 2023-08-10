<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style>
    .parsley-errors-list {
        color: red;
    }

    .admin-login input {
        border-radius: 0;
    }

    .captchaView {
        display: flex;
        flex-direction: row;
    }

    #captchaAdminParent {
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

    #captchaAdminParent img {
        width: 100% !important;
        height: 35px !important;
    }

    #admin_send_otp {
        display: block;
        border-radius: 0;
        width: 100%
    }

    #adminlogin,
    #adminResendOtp {
        border-radius: 0;
        display: block;
        width: 100%
    }
</style>
<div class="admin-login container">
    <div class="row">
        <div class="col-sm-5 mx-auto">
            <div class="card border-top-0 rounded-0">
                <form id="adminloginForm" method="POST" action="">
                    <div class="card-body mt-3">
                        <div id="adminloginInfo">
                            <div class="form-group">
                                <label for="contactEmail">PFC Email-ID</label>
                                <input type="email" class="form-control" name="admincontactEmail" id="admincontactEmail" placeholder="Enter your email-id" required>
                            </div>
                            <div class="captchaDiv">
                                <div class="form-group mt-2">
                                    <label for="captcha">Enter Captcha</label>
                                    <div class="captchaView">
                                        <div class="" id="captchaAdminParent">
                                            <?= $cap['image']; ?>
                                        </div>
                                        <button type="button" class="btn btn-sm captcha-refresh" id="refreshAdminCaptcha">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control" name="admincaptcha" id="admincaptcha" placeholder="Enter captcha text" maxlength="6" required>
                                </div>
                            </div>
                            <button type="button" id="admin_send_otp" class="btn btn-block btn-primary mt-2 mb-2">
                                <span id="adminloginInfoSubmitProcess" class="d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                                </span>
                                <span id="adminsendOTPbtnTxt">Send OTP</span>
                            </button>
                        </div>

                        <div id="adminotpDetails" class="d-none">
                            <div class="form-group">
                                <div class="alert alert-primary" role="alert">
                                    An OTP is being sent to <span class="font-weight-bold" id="admin_mobile_no_span"></span><br />Please enter the otp.
                                </div>
                                <input type="text" class="form-control" name="otp" id="adminotp" placeholder="Enter OTP" data-parsley-required-message="OTP required" required>
                            </div>
                            <button type="button" id="adminlogin" class="btn btn-block btn-primary mt-1 mb-1">Submit OTP
                            </button>
                            <button type="button" id="adminResendOtp" class="btn btn-block btn-warning mb-2"> <i class="fa fa-paper-plane mr-3"></i>Resend OTP
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    var adminsendOTPurl = '<?= base_url('iservices/admin/send-otp') ?>';
    var adminloginUrl = '<?= base_url('iservices/admin/process-login') ?>';
    var adminguidelineUrl = '<?= base_url('iservices/guidelines') ?>';
</script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/iservices/js/custom/admin_login.js') ?>"></script>

<script>
    $(function() {

        var captchaAdminParentRef = $('#captchaAdminParent');
        var refreshAdminCaptchaRef = $('#refreshAdminCaptcha');
        const refreshCaptchaURL = '<?= base_url('appeal/refresh-captcha') ?>';
        refreshAdminCaptchaRef.click(function() {

            $.get(refreshCaptchaURL, function(response) {
                if (response.status) {
                    captchaAdminParentRef.html(response.captcha.image);
                } else {
                    Swal.fire('Failed', 'Failed to refresh captcha!!!', 'error');
                }
            }).fail(function() {
                Swal.fire('Failed', 'Failed to refresh captcha!!!', 'error');
            });
        });
    })
</script>