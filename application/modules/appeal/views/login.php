<div class="d-flex justify-content-center container">
    <div class="row">
        <div class="col-md-12">
            <!--        <h1 class="text-center">Assam Right To Public Services</h1>-->
            <h3 class="text-center my-2 mt-4">Appeal Management System</h3>
            <div class="login-box">
                <div class="login-logo">
                    <a href="#"><b></b></a>
                </div>
                <!-- /.login-logo -->
                <div class="card">
                    <div class="card-body login-card-body">
                        <form id="login-form" action="<?php echo base_url(); ?>/appeal/login/loginMe" method="post">
                            <!-- <h4 class="login-box-msg my-1">Log in</h4> -->
                            <?php $this->load->helper('form'); ?>
                            <div class="mb-3">
                                <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                            </div>
                            <?php
                            $this->load->helper('form');
                            $error = $this->session->flashdata('error');
                            if ($error) {
                                ?>
                                <div class="alert alert-danger alert-dismissable" role="alert">
                                    <?php echo $error; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php
                            }
                            $success = $this->session->flashdata('success');
                            if ($success) {
                                ?>
                                <div class="alert alert-success alert-dismissable" role="alert">
                                    <?php echo $success; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php } ?>

<div class="row my-2">
                                <div class="col-6">
                                    <div class="d-grid">
                                    <a class="btn btn-success" href="<?=base_url('appeal/admin/login')?>" class="btn btn-outline-primary">Login using email</a>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-grid">
                                        <a href="<?=base_url('appeal/admin/username-login')?>" class="btn btn-outline-success">Login using username</a>
                                    </div>
                                </div>
                            </div>


                            <div class="mb-3">
                                <input class="form-control" type="email" id="email" name="email" placeholder="Email"
                                       autocomplete="off">
                            </div>
          
                            <div class="mb-3">
                                <input class="form-control" type="password" id="pass" name="password"
                                       placeholder="Password">
                            </div>
                            <div class="row form-group">
                                <div class="col-5 pr-0" id="captchaParent">
                                    <?= $cap['image']; ?>
                                </div>
                                <div class="col-1 pl-0">
                                    <button type="button" class="btn btn-sm btn-outline-info" id="refreshCaptcha">
                                       <i class="fa fa-refresh"></i>
                                        <!-- <i class="fas fa-sync"></i> -->
                                    </button>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control" name="captcha" id="captcha"
                                           placeholder="Enter security code" maxlength="6" required>
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-between">
                                <label class="ui-checkbox ui-checkbox-info">
                                    <input type="checkbox">
                                    <span class="input-span"></span>Remember me</label>
                                <a href="<?php echo base_url() ?>appeal/login/forgot-password">Forgot password?</a>
                            </div>
                            <div class="row mt-2">
                                <div class="col-6">
                                    <div class="d-grid">
                                        <button class="btn btn-primary btn-block" type="submit">Login</button>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-grid">
                                        <a href="<?=base_url('appeal/user_registration/create')?>" class="btn btn-outline-primary">Register</a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center" style="font-size: .9rem">
                                Designed & Developed by<br>National Informatics Centre, Assam
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.login-box -->
            </div>
        </div>
    </div>
</div>

<!-- BEGIN PAGA BACKDROPS-->
<div class="sidenav-backdrop backdrop"></div>
<div class="preloader-backdrop">
    <div class="page-preloader"></div>
</div>
<!-- END PAGA BACKDROPS-->
<!-- CORE PLUGINS -->
<script src="<?= base_url(); ?>assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
<script src="<?= base_url(); ?>assets/plugins/popper/umd/popper.min.js" type="text/javascript"></script>
<script src="<?= base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js" type="text/javascript"></script>
<!-- PAGE LEVEL PLUGINS -->
<script src="<?= base_url(); ?>assets/plugins/jquery-validation/jquery.validate.min.js" type="text/javascript"></script>
<!-- CORE SCRIPTS-->
<!-- <script src="assets/js/app.js" type="text/javascript"></script> -->
<!-- AdminLTE App -->
<script src="<?= base_url(); ?>assets/dist/js/adminlte.min.js"></script>
<!-- PAGE LEVEL SCRIPTS-->
<script src="<?= base_url(); ?>assets/js/crypto-js.js"></script>
<script src="<?= base_url(); ?>assets/js/encrypt.js"></script>
<script type="text/javascript">
    $(function () {
        $('#login-form').on("submit", function (e) {
            e.preventDefault();
            var password = $('#pass').val();
            var email = $('#email').val();
            let encryption = new Encryption();
            var encrypted = encryption.encrypt(password, 'asdf-ghjk-qwer-tyui');
            var encrypted2 = encryption.encrypt(email, 'asdf-ghjk-qwer-tyui');
            $("#pass").val(encrypted);
            $("#email").val(encrypted2).attr("type", "password");
            $('#login-form').unbind('submit').submit();
            return true;
        });
    });

    var captchaRef = $('#captcha');
    var captchaParentRef = $('#captchaParent');
    var refreshCaptchaRef = $('#refreshCaptcha');
    const refreshCaptchaURL = '<?=base_url('appeal/refresh-captcha')?>';
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


    $(document).ready(function() {
    // Initially hide the username input
    $('#username').hide();

    // Listen for changes in the login method select element
    $('#loginChoice').change(function() {
        var selectedMethod = $(this).val();

        if (selectedMethod === 'username') {
            $('#email').hide();
            $('#username').show();
        } else {
            $('#email').show();
            $('#username').hide();
        }
    });
});

</script>
