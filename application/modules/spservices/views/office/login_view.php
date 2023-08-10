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
                    <h5 class="text-center">Login</h5>
                </div>
                <div class="card-body">
                <form id="loginForm" method="POST" action="">
                        <div id="loginInfo">
                            <div class="form-group">
                                <label for="contactNumber">Contact Number</label>
                                <input type="text" class="form-control" name="contactNumber" id="contactNumber" placeholder="Enter your contact number"  minlength="10" maxlength="10" pattern="^[6-9]\d{9}$" required>
                            </div>
                            <div class="row form-group">
                                <div class="col-6 pr-0" id="captchaParent">
                                    <?=$cap['image'];?>
                                </div>
                                <div class="col-1 pl-0">
                                    <button type="button" class="btn btn-sm btn-outline-info" id="refreshCaptcha">
                                        <i class="fa fa-sync"></i>
                                    </button>
                                </div>
                                <div class="col-5">
                                    <input type="text" class="form-control" name="captcha" id="captcha" placeholder="Enter security code" maxlength="6" required>
                                </div>
                            </div>

                            <button type="button" id="send_otp" class="btn btn-block btn-primary mb-2" >
                                <span id="loginInfoSubmitProcess" class="d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                                </span>
                                <span id="sendOTPbtnTxt">Send OTP</span>
                            </button>
                            <!-- <a class="login_password" href="#" style="color:green;font-size:14px;font-weight:bold">Login with username & password.</a> -->
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
                    <!-- <form id="passwordloginForm" method="POST" action="">
                        <div>
                            <?php echo validation_errors(); ?>
                        </div>
                        <div id="password_login_view" >
                            <div class="form-group">
                                <label for="username">Mobile Number</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Enter registered mobile no." maxlength="10" value="<?php echo set_value('username'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="*****" value="<?php echo set_value('password'); ?>" required>
                            </div>
                            <button id="pw_login" class="btn btn-block btn-primary mb-2">
                                <i class="fa fa-sign-in"></i> Login
                            </button>
                            
                            <p>
                                <a class="otp_login" href="#" style="color:green;font-size:14px;font-weight:bold">Login with OTP.</a>
                            <a class="pull-right" href="<?= base_url('spservices/office/forgot-password') ?>" style="color:red;font-size:14px;font-weight:bold">Forgot password ?</a></p>

                        </div>
                    </form> -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var sendOTPurl = '<?= base_url('spservices/send-otp') ?>';
    var loginUrl = '<?= base_url('spservices/process-login') ?>';
    var guidelineUrl = '<?= base_url('spservices/guidelines') ?>';
</script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/iservices/js/custom/office_user_login.js') ?>"></script>

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
    $('.login_password').on('click', function(e) {
        e.preventDefault();
        $('#password_login_view').fadeIn(500);
        $('#loginInfo').hide();
        $('#passwordloginForm').parsley();
    })
    var login = $('#passwordloginForm');

    
    $('#passwordloginForm').submit(function(e) {
        e.preventDefault();
        if (login.parsley().validate()) {
            $.ajax({
                url: '<?= base_url('spservices/password-login') ?>',
                type: 'POST',
                cache: false,
                data: new FormData(this),
                processData: false,
                contentType: false,
                dataType: "JSON",
                success: function(response) {
                    console.log(response);
                    if (response.status) {
                        window.location.href = response.url;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.msg,
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // alert('Error at add data');
                }
            });
        }
    });
    $('.otp_login').on('click', function(e) {
        e.preventDefault();
        $('#password_login_view').hide();
        $('#loginInfo').fadeIn(500);
        $('#loginForm').parsley();
    })
</script>