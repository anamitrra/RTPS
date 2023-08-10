<div class="d-flex justify-content-center">
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center my-2 mt-4">Appeal Management System</h3>
            <div class="login-box">
                <div class="login-logo">

                </div>

                <!-- /.login-logo -->
                <div class="card">
                    <div class="card-body login-card-body">

                        <form id="login-form" action="<?php echo base_url('appeal/login/resetPasswordUser'); ?>" method="post">
                            <h4 class="login-box-msg">Reset Password</h4>
                            <?php $this->load->helper('form'); ?>

                            <div class="mb-3">
                                <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                            </div>

                            <?php
                            $this->load->helper('form');
                            $error = $this->session->flashdata('error');
                            if ($error) {
                            ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <?php echo $error; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php
                            }
                            $success = $this->session->flashdata('success');
                            if ($success) {
                            ?>
                                <div class="alert alert-success alert-dismissable">
                                    <?php echo $success; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php } ?>

                            <div class="mb-3">
                                <input class="form-control" type="email" name="login_email" placeholder="Your login email" autocomplete="off">
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
                                    <input type="text" class="form-control" name="captcha" id="captcha" placeholder="Enter security code" maxlength="6" required>
                                </div>
                            </div>
                            <div class="form-group d-flex justify-content-between my-3">
                                <button class="btn btn-primary btn-sm" type="submit">Send Reset Link</button>

                                <?= anchor('appeal/admin/login', 'Cancel', 'class="btn btn-outline-danger btn-sm"') ?>
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
<script type="text/javascript">
    var captchaRef = $('#captcha');
    var captchaParentRef = $('#captchaParent');
    var refreshCaptchaRef = $('#refreshCaptcha');
    const refreshCaptchaURL = '<?= base_url('appeal/refresh-captcha') ?>';

    $(function() {
        $('#login-form').validate({
            errorClass: "help-block",
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true
                }
            },
            highlight: function(e) {
                $(e).closest(".form-group").addClass("has-error")
            },
            unhighlight: function(e) {
                $(e).closest(".form-group").removeClass("has-error")
            },
        });

        refreshCaptchaRef.click(function() {
            $.get(refreshCaptchaURL, function(response) {
                if (response.status) {
                    captchaParentRef.html(response.captcha.image);
                } else {
                    Swal.fire('Failed', 'Failed to refresh captcha!!!', 'error');
                }
            }).fail(function() {
                Swal.fire('Failed', 'Failed to refresh captcha!!!', 'error');
            });
        });
    });
</script>