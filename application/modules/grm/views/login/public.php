<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-sm-5 mx-auto">
          <h3 class="text-center my-2 mt-4">Public Grievance System</h3>
            <div class="card my-2">

                <div class="card-body">
                    <form id="loginForm" method="POST" action="">
                        <div id="loginInfo">
                            <h2 class="text-center">Login</h2></br>

                            <div class="form-group">
                                <label for="contactNumber">Contact Number</label>
                                <input type="text" class="form-control" name="contactNumber" id="contactNumber" placeholder="Enter your contact number"  minlength="10" maxlength="10" pattern="^[6-9]\d{9}$" required>
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
    var sendOTPurl     = '<?=base_url('grm/send-otp')?>';
    var loginUrl = '<?=base_url('grm/login/process')?>';
    var dashboard = '<?=base_url('grm/my-grm')?>';
</script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/js/custom/login.js') ?>"></script>
<script>
$(function () {
    var loginInfoRef  = $('#loginInfo');
    var otpDetailsRef = $('#otpDetails');
    var contactNumberRef = $('#contactNumber');
    var otpRef = $('#otp');
    var loginFormRef = $('#loginForm');
    var loginInfoSubmitProcessRef = $('#loginInfoSubmitProcess');
    var sendOTPbtnTxtRef = $('#sendOTPbtnTxt');
    var sendOTPRef = $('#send_otp');

    $('#loginForm').parsley();
    sendOTPRef.click(function () {
        $('#mobile_no_span').html(contactNumberRef.val());
        if(loginFormRef.parsley({excluded: '#otp'}).validate()) {

            contactNumberRef.prop('readonly',true);
            sendOTPRef.prop('disabled',true);
            if(loginInfoSubmitProcessRef.hasClass('d-none')){
                loginInfoSubmitProcessRef.removeClass('d-none');
                sendOTPbtnTxtRef.addClass('d-none');
            }
            $.ajax({
                url : sendOTPurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    contactNumber : contactNumberRef.val()
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
                    }else{
                        Swal.fire(
                            'Failed!',
                            response.error_msg !== undefined ? response.error_msg : response.msg,
                            'error'
                        );
                    }
                },
                error:function (error) {
                    console.log('error');
                    // if(!loginInfoRef.hasClass('d-none')) {
                    //     loginInfoRef.addClass('d-none');
                    // }
                    // if(otpDetailsRef.hasClass('d-none')) {
                    //     otpDetailsRef.removeClass('d-none');
                    // }


                    Swal.fire(
                        'Failed!',
                        'Something went wrong!',
                        'error'
                    );
                }
            }).always(function(){
                // console.log('always');
                if(sendOTPbtnTxtRef.hasClass('d-none')) {
                    loginInfoSubmitProcessRef.addClass('d-none');
                    sendOTPbtnTxtRef.removeClass('d-none');
                }
                contactNumberRef.prop('readonly',false);
                sendOTPRef.prop('disabled',false);
            });
        }
    });

    $('#login').click(function () {
        if(loginFormRef.parsley().validate()){
            var contactNumber = contactNumberRef.val();
            var otp = otpRef.val();
            $.ajax({
                url: loginUrl,
                type:'POST',
                dataType: 'json',
                data: {
                     contactNumber, otp
                },
                success:function (response) {
                    console.log(response);
                    if(response.status){
                        window.location.href = dashboard;
                    }else{
                        Swal.fire(
                            'Failed!',
                            'Invalid Inputs!',
                            'fail'
                        );
                    }
                },
                error:function () {
                    Swal.fire(
                        'Failed!',
                        'Invalid Inputs!',
                        'fail'
                    );
                }
            });
        }
    });
    $('#resendOtp').click(function () {
        if(loginFormRef.parsley().validate()){
            var contactNumber = contactNumberRef.val();
            $.ajax({
                url: sendOTPurl,
                type:'POST',
                dataType: 'json',
                data: {
                     contactNumber:contactNumber
                },
                success:function (response) {
                    console.log(response);
                    if(response.status){
                      Swal.fire({
                          position: 'top-end',
                          icon: 'success',
                          title: 'OTP Send Successfully',
                          showConfirmButton: false,
                          timer: 1500
                        })
                    }
                },
                error:function () {
                    Swal.fire(
                        'Failed!',
                        'Something went wrong! Try again.',
                        'fail'
                    );
                }
            });
        }
    });

});

</script>
