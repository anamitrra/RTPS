<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-sm-5 mx-auto">
            <div class="card border-top-0 rounded-0">
                <div class="card-body">
                    <form id="adminloginForm" method="POST" action="">
                        <div id="adminloginInfo">
                            <h5 class="text-center">PFC Login</h5></br>

                            <div class="form-group">
                                <label for="contactEmail">Email-ID</label>
                                <input type="email" class="form-control" name="admincontactEmail" id="admincontactEmail" placeholder="Enter your email-id"  required>
                            </div>

                            <div class="row form-group" style="margin-top: 10px;">
                                <div class="col-6 pr-0" id="captchaAdminParent">
                                    <?=$cap['image'];?>
                                </div>
                                <div class="col-1 pl-0">
                                    <button type="button" class="btn btn-sm btn-outline-info" id="refreshAdminCaptcha">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>
                                <div class="col-5">
                                    <input type="text" class="form-control" name="admincaptcha" id="admincaptcha" placeholder="Enter security code" maxlength="6" required>
                                </div>
                            </div>

                            <button type="button" id="admin_send_otp" class="btn btn-block btn-primary mb-2" >
                                <span id="adminloginInfoSubmitProcess" class="d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                                </span>
                                <span id="adminsendOTPbtnTxt">Send OTP</span>
                            </button>


                        </div>


                            <div id="adminotpDetails" class="d-none">
                                <div class="form-group">
                                    <div class="alert alert-primary" role="alert">
                                        An OTP is being sent to <span class="font-weight-bold" id="admin_mobile_no_span"></span><br/>Please enter the otp.
                                    </div>
                                    <input type="text" class="form-control" name="otp" id="adminotp" placeholder="Enter OTP" data-parsley-required-message="OTP required" required>
                                </div>

                                <button type="button" id="adminlogin" class="btn btn-block btn-primary" >Submit OTP
                                </button>
                                <button type="button" id="adminResendOtp" class="btn btn-block btn-outline-info mb-2" > <i class="fas fa-paper-plane mr-3"></i>Resend OTP
                                </button>
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var adminsendOTPurl     = '<?=base_url('iservices/admin/send-otp')?>';
    var adminloginUrl = '<?=base_url('iservices/admin/process-login')?>';
    var adminguidelineUrl = '<?=base_url('iservices/guidelines')?>';
</script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/iservices/js/custom/admin_login.js') ?>"></script>

<script>
    $(function(){
        
        var captchaAdminParentRef = $('#captchaAdminParent');
        var refreshAdminCaptchaRef = $('#refreshAdminCaptcha');
        const refreshCaptchaURL = '<?=base_url('appeal/refresh-captcha')?>';
        refreshAdminCaptchaRef.click(function(){
      
            $.get(refreshCaptchaURL,function(response){
                if(response.status){
                    captchaAdminParentRef.html(response.captcha.image);
                }else{
                    Swal.fire('Failed','Failed to refresh captcha!!!','error');
                }
            }).fail(function (){
                Swal.fire('Failed','Failed to refresh captcha!!!','error');
            });
        });
    })
      
</script>
