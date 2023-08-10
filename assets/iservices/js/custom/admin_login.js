$(function () {
    var adminloginInfoRef  = $('#adminloginInfo');
    var adminotpDetailsRef = $('#adminotpDetails');
    var admincontactEmailRef = $('#admincontactEmail');
    var otpRef = $('#adminotp');
    var adminloginFormRef = $('#adminloginForm');
    var adminloginInfoSubmitProcessRef = $('#adminloginInfoSubmitProcess');
    var adminsendOTPbtnTxtRef = $('#adminsendOTPbtnTxt');
    var adminsendOTPRef = $('#admin_send_otp');

    $('#loginForm').parsley();
    adminsendOTPRef.click(function () {
        var admincaptchaRef = $('#admincaptcha');
        if(adminloginFormRef.parsley({excluded: '#adminotp'}).validate()) {

            admincontactEmailRef.prop('readonly',true);
            adminsendOTPRef.prop('disabled',true);
            if(adminloginInfoSubmitProcessRef.hasClass('d-none')){
                adminloginInfoSubmitProcessRef.removeClass('d-none');
                adminsendOTPbtnTxtRef.addClass('d-none');
            }
            $.ajax({
                url : adminsendOTPurl,
                type: 'POST',
                dataType: 'json',
                data: {
                    contactEmail : admincontactEmailRef.val(),
                    captcha : admincaptchaRef.val()
                },
                success:function (response) {
                    console.log('success');
                    if(response.status){
                        if(!adminloginInfoRef.hasClass('d-none')) {
                            adminloginInfoRef.addClass('d-none');
                        }
                        if(adminotpDetailsRef.hasClass('d-none')) {
                            adminotpDetailsRef.removeClass('d-none');
                        }
                        $('#admin_mobile_no_span').html(response.m_no);
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
                    // if(!adminloginInfoRef.hasClass('d-none')) {
                    //     adminloginInfoRef.addClass('d-none');
                    // }
                    // if(adminotpDetailsRef.hasClass('d-none')) {
                    //     adminotpDetailsRef.removeClass('d-none');
                    // }


                    Swal.fire(
                        'Failed!',
                        'Something went wrong!',
                        'error'
                    );
                }
            }).always(function(){
                // console.log('always');
                if(adminsendOTPbtnTxtRef.hasClass('d-none')) {
                    adminloginInfoSubmitProcessRef.addClass('d-none');
                    adminsendOTPbtnTxtRef.removeClass('d-none');
                }
                admincontactEmailRef.prop('readonly',false);
                adminsendOTPRef.prop('disabled',false);
            });
        }
    });

    $('#adminlogin').click(function () {
        if(adminloginFormRef.parsley().validate()){
            var contactEmail = admincontactEmailRef.val();
            var otp = otpRef.val();
            $.ajax({
                url: adminloginUrl,
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
    
    $('#adminResendOtp').click(function () {
        if(adminloginFormRef.parsley().validate()){
            var contactEmail = admincontactEmailRef.val();
            $.ajax({
                url: adminsendOTPurl,
                type:'POST',
                dataType: 'json',
                data: {
                     contactEmail:contactEmail,
                     type:'resend'
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
