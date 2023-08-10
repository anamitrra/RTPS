$(function () {
    var loginInfoRef  = $('#loginInfo');
    var otpDetailsRef = $('#otpDetails');
    var applicationNumberRef = $('#applicationNumber');
    var contactNumberRef = $('#contactNumber');
    var otpRef = $('#otp');
    var appealLoginFormRef = $('#appealLoginForm');
    var loginInfoSubmitProcessRef = $('#loginInfoSubmitProcess');
    var submitBtnTxtRef = $('#submitBtnTxt');
    var submitBtnRef = $('#submit,#resendOtp');

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
                    applicationNumber : applicationNumberRef.val(), contactNumber : contactNumberRef.val()
                },
                success:function (response) {
                    
                    // console.log('success');
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
                            'Invalid inputs!',
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
});