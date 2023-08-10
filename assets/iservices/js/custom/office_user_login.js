$(function () {
    var loginInfoRef  = $('#loginInfo');
    var otpDetailsRef = $('#otpDetails');
    var contactNumberRef = $('#contactNumber');
    var otpRef = $('#otp');
    var loginFormRef = $('#loginForm');
    var loginInfoSubmitProcessRef = $('#loginInfoSubmitProcess');
    var sendOTPbtnTxtRef = $('#sendOTPbtnTxt');
    var sendOTPRef = $('#send_otp');



    // $('#loginForm').parsley();
    sendOTPRef.click(function () {
        var captchaRef = $('#captcha');
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
                    contactNumber : contactNumberRef.val(),
                    captcha : captchaRef.val()
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
                    // console.log('error');
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
                    // console.log(response);
                    if(response.status){
                        window.location.href = response.url;
                    }
                    else if(response.is_deactive){
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Your account is blocked by admin.',
                        })
                    }
                    // else if(!response.exist){
                    //     Swal.fire({
                    //         icon: 'error',
                    //         title: 'Oops...',
                    //         text: 'Mobile number is not registered.',
                    //     })
                    // }
                    else{
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
                     contactNumber:contactNumber,
                     type:'resend'
                },
                success:function (response) {
                    console.log(response);
                    if(response.status){
                      Swal.fire({
                          position: 'center',
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
