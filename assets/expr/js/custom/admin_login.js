$(function () {
    var loginInfoRef  = $('#loginInfo');
    var otpDetailsRef = $('#otpDetails');
    var contactEmailRef = $('#contactEmail');
    var otpRef = $('#otp');
    var loginFormRef = $('#loginForm');
    var loginInfoSubmitProcessRef = $('#loginInfoSubmitProcess');
    var sendOTPbtnTxtRef = $('#sendOTPbtnTxt');
    var sendOTPRef = $('#send_otp');

    $('#loginForm').parsley();
    sendOTPRef.click(function () {

        if(loginFormRef.parsley({excluded: '#otp'}).validate()) {

            contactEmailRef.prop('readonly',true);
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
                    contactEmail : contactEmailRef.val()
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
                        $('#mobile_no_span').html(response.m_no);
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
                contactEmailRef.prop('readonly',false);
                sendOTPRef.prop('disabled',false);
            });
        }
    });

    $('#login').click(function () {
        if(loginFormRef.parsley().validate()){
            var contactEmail = contactEmailRef.val();
            var otp = otpRef.val();
            $.ajax({
                url: loginUrl,
                type:'POST',
                dataType: 'json',
                data: {
                     contactEmail, otp
                },
                success:function (response) {
                    console.log(response);
                    if(response.status){
                        window.location.href = response.url;
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
            var contactEmail = contactEmailRef.val();
            $.ajax({
                url: sendOTPurl,
                type:'POST',
                dataType: 'json',
                data: {
                     contactEmail:contactEmail
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
