<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-sm-5 mx-auto mt-2">
            <div class="card rounded-0">
                <?php if ($this->session->flashdata('error') != null) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>
                <div class="card-body" >
                    <form method="POST" action="<?=base_url('iservices/superadmin/login/password_login')?>">
                        <div id="adminloginInfo">
                            <h5 class="text-center">Department Login</h5></br>

                            <div class="form-group">
                                <label for="contactEmail">Email-ID</label>
                                <input class="form-control" name="email" value="<?=set_value("email")?>" placeholder="Enter your email-id" type="email"  required />
                                <?= form_error("email") ?>
                            </div>
                            
                            <div class="form-group">
                                <label for="contactEmail">Password</label>
                                <input class="form-control" name="password" placeholder="Enter your password" type="password"  required />
                                <?= form_error("password") ?>
                            </div>

                            <div class="row form-group">
                                <div class="col-6 pr-0" id="captchaAdminParent">
                                    <?=$cap['image'];?>
                                </div>
                                <div class="col-1 pl-0">
                                    <button type="button" class="btn btn-sm btn-outline-info" id="refreshAdminCaptcha">
                                        <i class="fa fa-sync"></i>
                                    </button>
                                </div>
                                <div class="col-5">
                                    <input type="text" class="form-control" name="admincaptcha" id="admincaptcha" placeholder="Enter security code" maxlength="6" required />
                                    <?= form_error("admincaptcha") ?>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-block btn-primary mb-2" >
                                <span>Login</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var adminsendOTPurl     = '<?=base_url('iservices/superadmin/send-otp')?>';
    var adminloginUrl = '<?=base_url('iservices/superadmin/process-login')?>';
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
