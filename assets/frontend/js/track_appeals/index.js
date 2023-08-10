$(function () {
    var loginInfoRef  = $('#loginInfo');
    var otpDetailsRef = $('#otpDetails');
    var appealNumberRef = $('#appealNumber');
    var contactNumberRef = $('#contactNumber');
    var otpRef = $('#otp');
    var appealLoginFormRef = $('#appealLoginForm');
    var loginInfoSubmitProcessRef = $('#loginInfoSubmitProcess');
    var submitBtnTxtRef = $('#submitBtnTxt');
    var submitBtnRef = $('#submit');
    var captchaRef = $('#captcha');
    var captchaParentRef = $('#captchaParent');
    var refreshCaptchaRef = $('#refreshCaptcha');

    $('#appealLoginForm').parsley();

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
            appealNumberRef.prop('readonly',true);
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
                    appealNumber : appealNumberRef.val(),
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
                appealNumberRef.prop('readonly',false);
                contactNumberRef.prop('readonly',false);
                submitBtnRef.prop('disabled',false);
            });
        }
    });

    $('#login').click(function () {
        if(appealLoginFormRef.parsley().validate()){
            var appealNumber = appealNumberRef.val();
            var contactNumber = contactNumberRef.val();
            var otp = otpRef.val();
            $.ajax({
                url: appealLoginUrl,
                type:'POST',
                dataType: 'json',
                data: {
                    appealNumber, contactNumber, otp
                },
                success:function (response) {
                    console.log(response);
                    if(response.status){
                        window.location.href = appealApplyUrl;
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