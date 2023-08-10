$(function () {
    var loginInfoRef = $('#loginInfo');
    var otpDetailsRef = $('#otpDetails');
    var contactNumberRef = $('#contactNumber');
    var newPasswordRef = $('#new_password');
    var confPasswordRef = $('#conf_password');
    var otpRef = $('#otp');
    var loginFormRef = $('#loginForm');
    var loginInfoSubmitProcessRef = $('#loginInfoSubmitProcess');
    var sendOTPbtnTxtRef = $('#sendOTPbtnTxt');
    var sendOTPRef = $('#send_otp');


    // $('#loginForm').parsley();
    sendOTPRef.click(function () {
        var captchaRef = $('#captcha');
        var x = $('#mobile_no_span').html(contactNumberRef.val());

        if (loginFormRef.parsley({ excluded: '#otp' }).validate()) {

            contactNumberRef.prop('readonly', true);
            sendOTPRef.prop('disabled', true);
            $.ajax({
                url: checkMobile,
                type: 'POST',
                dataType: 'json',
                data: {
                    contactNumber: contactNumberRef.val()
                },
                success: function (response) {
                    if (response.status) {
                        $.ajax({
                            url: sendOTPurl,
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                contactNumber: contactNumberRef.val(),
                            },
                            success: function (response) {
                                console.log('success');
                                if (response.status) {
                                    if (!loginInfoRef.hasClass('d-none')) {
                                        loginInfoRef.addClass('d-none');
                                    }
                                    if (otpDetailsRef.hasClass('d-none')) {
                                        otpDetailsRef.removeClass('d-none');
                                    }
                                } else {
                                    Swal.fire(
                                        'Failed!',
                                        response.error_msg !== undefined ? response.error_msg : response.msg,
                                        'error'
                                    );
                                }
                            },
                            error: function (error) {
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
                        }).always(function () {
                            // console.log('always');
                            if (sendOTPbtnTxtRef.hasClass('d-none')) {
                                loginInfoSubmitProcessRef.addClass('d-none');
                                sendOTPbtnTxtRef.removeClass('d-none');
                            }
                            contactNumberRef.prop('readonly', false);
                            sendOTPRef.prop('disabled', false);
                        });

                    }
                    else {
                        Swal.fire(
                            'Failed!',
                            'Something went wrong!',
                            'error'
                        );
                    }
                }
            })
        }
    });

    $('#pass_change').click(function () {
        if (loginFormRef.parsley().validate()) {
            var contactNumber = contactNumberRef.val();
            var newPassword = newPasswordRef.val();
            var confPassword = confPasswordRef.val();
            var otp = otpRef.val();
            $.ajax({
                url: resetUrl,
                type: 'POST',
                dataType: 'json',
                data: {
                    contactNumber, otp, newPassword, confPassword
                },
                success: function (response) {
                    console.log(response);
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Password reset successfully.',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                          }).then((result) => {
                                window.location.href = response.url;
                            
                          })
                    }
                    else {
                        if (response.otp_mismatch) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'OTP is not valid.',
                            })
                        }
                        else if (response.pass_mismatch) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Password and confirm password is not matched.',
                            })
                        }
                    }
                },
                error: function () {
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
        if (loginFormRef.parsley().validate()) {
            var contactNumber = contactNumberRef.val();
            $.ajax({
                url: sendOTPurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    contactNumber: contactNumber,
                    type: 'resend'
                },
                success: function (response) {
                    console.log(response);
                    if (response.status) {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'OTP Send Successfully',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                },
                error: function () {
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
