<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style>
    .parsley-errors-list {
        color: red;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-sm-5 mx-auto">
            <div class="card my-2">
                <div class="card-body">

                    <?php
                    if ($this->session->userdata('success') != null) {
                        ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?= $this->session->userdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                    if ($this->session->userdata('fail') != null) {
                        ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Fail!</strong> <?= $this->session->userdata('fail') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                    if ($this->session->userdata('error') != null) {
                        ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->userdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <?php
                    }
                    ?>

                    <form id="appealLoginForm" method="POST" action="<?= base_url('appeal/process-login') ?>">
                        <div id="loginInfo">
                            <h2 class="text-center">Appeal Login</h2>
                            <br>
                            <div class="form-group">
                                <label for="applicationNumber">Application Number</label>
                                <input type="text" class="form-control" name="applicationNumber" id="applicationNumber" placeholder="Enter application number" required>
                            </div>
                            <?php
                            if(isset($this->session->userdata('role')->role_name) && $this->session->userdata('role')->role_name === 'PFC'){
                                ?>
                                <button type="button" id="login" class="btn btn-block btn-outline-primary">Submit</button>
                                <?php
                            }else{
                            ?>

                            <div class="form-group">
                                <label for="contactNumber">Contact Number</label>
                                <input type="text" class="form-control" name="contactNumber" id="contactNumber" placeholder="Enter registered contact number" minlength="10" maxlength="10" pattern="^[6-9]\d{9}$" required>
                            </div>

                            <div class="row form-group">
                                <div class="col-5 pr-0" id="captchaParent">
                                    <?=$cap['image'];?>
                                </div>
                                <div class="col-1 pl-0">
                                    <button type="button" class="btn btn-sm btn-outline-info" id="refreshCaptcha">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control" name="captcha" id="captcha" placeholder="Enter security code" maxlength="6" required>
                                </div>
                            </div>
                            <button type="button" id="submit" class="btn btn-block btn-outline-primary mb-2">
                                    <span id="loginInfoSubmitProcess" class="d-none">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Processing ...
                                    </span>
                                <span id="submitBtnTxt">Submit</span>
                            </button>
                            <a class="btn btn-block btn-outline-warning mb-2" href="<?= base_url("/");?>">Back<a>
                                    <?php
                                    }
                                    ?>

                        </div>
                        <?php
                        if (!$this->session->has_userdata('role')) {
                            ?>

                            <div id="otpDetails" class="d-none">
                                <div class="form-group">
                                    <div class="alert alert-primary" role="alert">
                                        An OTP is being sent to <span class="font-weight-bold" id="mobile_no_span"></span><br/>Please enter the otp.
                                    </div>
                                    <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP" data-parsley-required-message="OTP required" required>
                                </div>

                                <button type="button" id="login" class="btn btn-block btn-outline-primary">Submit OTP
                                </button>
                                <button type="button" id="resendOtp" class="btn btn-block btn-outline-info mb-2"> <i class="fas fa-paper-plane mr-3"></i>Resend OTP
                                </button>
                            </div>
                            <?php
                        }
                        ?>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var sendOTPurl = '<?= base_url('appeal/send-otp') ?>';
    var appealLoginUrl = '<?= base_url('appeal/process-login') ?>';
    var appealApplyUrl = '<?= base_url('appeal/apply') ?>';
</script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script>
    $(function () {
        var loginInfoRef  = $('#loginInfo');
        var otpDetailsRef = $('#otpDetails');
        var applicationNumberRef = $('#applicationNumber');
        var contactNumberRef = $('#contactNumber');
        var captchaRef = $('#captcha');
        var captchaParentRef = $('#captchaParent');
        var refreshCaptchaRef = $('#refreshCaptcha');
        var otpRef = $('#otp');
        var appealLoginFormRef = $('#appealLoginForm');
        var loginInfoSubmitProcessRef = $('#loginInfoSubmitProcess');
        var submitBtnTxtRef = $('#submitBtnTxt');
        var submitBtnRef = $('#submit,#resendOtp');
        const refreshCaptchaURL = '<?=base_url('appeal/refresh-captcha')?>';
        appealLoginFormRef.parsley();

        $('#backToLoginInfo').click(function () {
            if(loginInfoRef.hasClass('d-none')) {
                loginInfoRef.removeClass('d-none');
            }
            if(!otpDetailsRef.hasClass('d-none')) {
                otpDetailsRef.addClass('d-none');
            }
        });

        submitBtnRef.click(function () {
            $('#mobile_no_span').html(contactNumberRef.val());
            if(appealLoginFormRef.parsley({excluded: '#otp'}).validate()) {
                applicationNumberRef.prop('readonly',true);
                contactNumberRef.prop('readonly',true);
                submitBtnRef.prop('disabled',true);
                if(loginInfoSubmitProcessRef.hasClass('d-none')){
                    loginInfoSubmitProcessRef.removeClass('d-none');
                    submitBtnTxtRef.addClass('d-none');
                }
                $.ajax({
                    url : sendOTPurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        applicationNumber : applicationNumberRef.val(),
                        contactNumber : contactNumberRef.val(),
                        captcha : captchaRef.val()
                    },
                    success:function (response) {

                        console.log('success');
                        if(response.status){
                            if(!loginInfoRef.hasClass('d-none')) {
                                console.log('success1');
                                loginInfoRef.addClass('d-none');
                            }
                            if(otpDetailsRef.hasClass('d-none')) {
                                console.log('success2');
                                otpDetailsRef.removeClass('d-none');
                            }
                        }else{
                            Swal.fire(
                                'Failed!',
                                response.error_msg,
                                'error'
                            );
                        }
                    },
                    error:function () {
                        // console.log('error');
                        if(submitBtnTxtRef.hasClass('d-none')){
                            submitBtnTxtRef.removeClass('d-none');
                            loginInfoSubmitProcessRef.addClass('d-none');
                        }
                        Swal.fire(
                            'Failed!',
                            'Something went wrong!',
                            'error'
                        );
                    }
                }).always(function(){
                    // console.log('always');
                    if(submitBtnTxtRef.hasClass('d-none')) {
                        loginInfoSubmitProcessRef.addClass('d-none');
                        submitBtnTxtRef.removeClass('d-none');
                    }
                    applicationNumberRef.prop('readonly',false);
                    contactNumberRef.prop('readonly',false);
                    submitBtnRef.prop('disabled',false);
                });
            }
        });

        $('#login').click(function () {
            if(appealLoginFormRef.parsley().validate()){
                var applicationNumber = applicationNumberRef.val();
                var contactNumber = contactNumberRef.val();
                var otp = otpRef.val();
                $.ajax({
                    url: appealLoginUrl,
                    type:'POST',
                    dataType: 'json',
                    data: {
                        applicationNumber, contactNumber, otp
                    },
                    success:function (response) {
                        if(response.status){
                            window.location.href = appealApplyUrl;
                        }else{
                            Swal.fire(
                                'Failed!',
                                'Invalid OTP!',
                                'fail'
                            );
                        }
                    },
                    error:function () {
                        Swal.fire(
                            'Failed!',
                            'Invalid OTP!',
                            'fail'
                        );
                    }
                });
            }
        });

        refreshCaptchaRef.click(function(){
            console.log('test')
            $.get(refreshCaptchaURL,function(response){
                if(response.status){
                    captchaParentRef.html(response.captcha.image);
                }else{
                    Swal.fire('Failed','Failed to refresh captcha!!!','error');
                }
            }).fail(function (){
                Swal.fire('Failed','Failed to refresh captcha!!!','error');
            });
        });
    });
</script>