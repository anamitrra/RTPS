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
                    <form id="appealLoginForm" method="POST" action="<?= base_url('appeals/track/process-login') ?>">
                        <div id="loginInfo">

                            <div class="form-group">
                                <label for="applicationNumber">Appeal ID</label>
                                <input type="text" class="form-control" name="applicationNumber" id="applicationNumber" placeholder="Enter application number" required>
                            </div>
                            <div class="form-group">
                                <label for="contactNumber">Contact Number</label>
                                <input type="text" class="form-control" name="contactNumber" id="contactNumber" placeholder="Enter registered contact number" required>
                            </div>
                            <button type="button" id="submit" class="btn btn-block btn-outline-primary mb-2" >
                                <span id="loginInfoSubmitProcess" class="d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Please wait... Processing ...
                                </span>
                                <span id="submitBtnTxt">Submit</span>
                            </button>
                        </div>
                        <div id="otpDetails" class="d-none">
                            <div class="form-group">
                                <label for="otp">OTP</label>
                                <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP" data-parsley-required-message="OTP required" required>
                            </div>
                            <button type="button" id="resendOtp" class="btn btn-block btn-info mb-2" > <i class="fa fa-paper-plane mr-3"></i>Resend OTP
                            </button>
<!--                            <button type="button" id="login" class="btn btn-block btn-primary" >Submit OTP-->
<!--                            </button>-->
                            <a href="<?=base_url('appeal/preview-n-track')?>" id="login" class="btn btn-block btn-primary" >Submit OTP
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var sendOTPurl     = '<?=base_url('appeals/track/send-otp')?>';
    var appealLoginUrl = '<?=base_url('appeals/track/process-login')?>';
    var appealApplyUrl = '<?=base_url('appeal/apply')?>';
</script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script >
    $(function () {
        var loginInfoRef  = $('#loginInfo');
        var otpDetailsRef = $('#otpDetails');
        var applicationNumberRef = $('#applicationNumber');
        var contactNumberRef = $('#contactNumber');
        var otpRef = $('#otp');
        var appealLoginFormRef = $('#appealLoginForm');
        var loginInfoSubmitProcessRef = $('#loginInfoSubmitProcess');
        var submitBtnTxtRef = $('#submitBtnTxt');

        $('#appealLoginForm').parsley();

        $('#backToLoginInfo').click(function () {
            if(loginInfoRef.hasClass('d-none')) {
                loginInfoRef.removeClass('d-none');
            }
            if(!otpDetailsRef.hasClass('d-none')) {
                otpDetailsRef.addClass('d-none');
            }
        });

        $('#submit').click(function () {
            if(appealLoginFormRef.parsley({excluded: '#otp'}).validate()) {
                applicationNumberRef.prop('readonly',true);
                contactNumberRef.prop('readonly',true);
                if(loginInfoSubmitProcessRef.hasClass('d-none')){
                    loginInfoSubmitProcessRef.removeClass('d-none');
                    submitBtnTxtRef.addClass('d-none');
                }
                $.ajax({
                    url : sendOTPurl,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        applicationNumber : applicationNumberRef.val(), contactNumber : contactNumberRef.val()
                    },
                    success:function (response) {
                        console.log('success');
                        if(response.status){
                            if(!loginInfoRef.hasClass('d-none')) {
                                loginInfoRef.addClass('d-none');
                            }
                            if(otpDetailsRef.hasClass('d-none')) {
                                otpDetailsRef.removeClass('d-none');
                            }
                        }
                    },
                    error:function () {
                        console.log('error');
                        if(!loginInfoRef.hasClass('d-none')) {
                            loginInfoRef.addClass('d-none');
                        }
                        if(otpDetailsRef.hasClass('d-none')) {
                            otpDetailsRef.removeClass('d-none');
                        }
                        // if(loginInfoRef.hasClass('d-none')) {
                        //     loginInfoRef.removeClass('d-none');
                        // }
                        // if(!otpDetailsRef.hasClass('d-none')) {
                        //     otpDetailsRef.addClass('d-none');
                        // }
                    }
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
    });
</script>