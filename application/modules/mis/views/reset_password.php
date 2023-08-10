<div class="row justify-content-center">
    <div class="col-md-4">
        <h3 class="text-center my-2 mt-4">Appeal Management System</h3>
        <div class="login-box">
            <div class="login-logo">

            </div>
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <?php
                $error = $this->session->flashdata('error');
                if ($error) {
                    ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $error; ?>
                    </div>
                <?php } ?>
                <?php
                $success = $this->session->flashdata('success');
                if ($success) {
                    ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <?php echo $success; ?>
                    </div>
                <?php } ?>
                <form action="<?=base_url('mis/reset-password/process')?>" method="POST">
                    <label for="">Set New Password</label>
                    <input type="hidden" name="email" value="<?=$email?>">
                    <input type="hidden" name="activation_code" value="<?=$activation_code?>">
                    <div class="form-group">
                        <input type="password" class="form-control" name="password" placeholder="New Password" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                    </div>
                    <div class="row form-group">
                        <div class="col-5 pr-0" id="captchaParent">
                            <?= $cap['image']; ?>
                        </div>
                        <div class="col-1 pl-0">
                            <button type="button" class="btn btn-sm btn-outline-info" id="refreshCaptcha">
                                <i class="fa fa-refresh"></i>
                            </button>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control" name="captcha" id="captcha"
                                   placeholder="Enter security code" maxlength="6" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-success">Submit</button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    var captchaRef = $('#captcha');
    var captchaParentRef = $('#captchaParent');
    var refreshCaptchaRef = $('#refreshCaptcha');
    const refreshCaptchaURL = '<?=base_url('mis/refresh-captcha')?>';
    $(function(){
        refreshCaptchaRef.click(function(){
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
    })
</script>