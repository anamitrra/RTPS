<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $pageTitle; ?></title>

  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url();?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?=base_url();?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url();?>assets/dist/css/adminlte.min.css">

  <link rel="stylesheet" href="<?=base_url();?>assets/css/custom.css">

</head>

<body class="hold-transition login-page">
<h1 class="text-center">Assam Right To Public Services</h1>
  <h4 class="text-center">Appeal Management System</h4>
  <div class="login-box">
    <div class="login-logo">

    </div>

    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">

        <form id="login-form" action="<?php echo base_url(); ?>appeal/login/resetPasswordUser" method="post">
          <h4 class="login-box-msg">Reset Password</h4>
          <?php $this->load->helper('form');?>

          <div class="row">
            <div class="col">
              <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
            </div>
          </div>

          <?php
$this->load->helper('form');
$error = $this->session->flashdata('error');
if ($error) {
    ?>
          <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $error; ?>
          </div>
          <?php
}
$success = $this->session->flashdata('success');
if ($success) {
    ?>
          <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <?php echo $success; ?>
          </div>
          <?php }?>

         <div class="form-group">
                <div class="input-group-icon">
                    <input class="form-control" type="email" name="login_email" placeholder="Your login email" autocomplete="off">
                    <span class="input-icon"><i class="fa fa-envelope"></i></span>
                </div>
            </div>

          <div class="form-group d-flex justify-content-between mb-3">
            <button class="btn btn-primary btn-sm" type="submit">Send Reset Link</button>
            
            <?= anchor('appeal/admin/login', 'Cancel', 'class="btn btn-light btn-sm"') ?>
          </div>

          <div class="form-group text-center" style="font-size: .9rem">
            Designed & Developed by<br>National Informatics Centre, Assam
          </div>


        </form>
      </div>
    </div>
    <!-- /.login-box -->
  </div>

  <!-- BEGIN PAGA BACKDROPS-->
  <div class="sidenav-backdrop backdrop"></div>
  <div class="preloader-backdrop">
    <div class="page-preloader"></div>
  </div>

  <!-- END PAGA BACKDROPS-->
  <!-- CORE PLUGINS -->
  <script src="<?=base_url();?>assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
  <script src="<?=base_url();?>assets/plugins/popper/umd/popper.min.js" type="text/javascript"></script>
  <script src="<?=base_url();?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js" type="text/javascript"></script>
  <!-- PAGE LEVEL PLUGINS -->
  <script src="<?=base_url();?>assets/plugins/jquery-validation/jquery.validate.min.js"
    type="text/javascript"></script>

    <!-- CORE SCRIPTS-->
  <!-- <script src="assets/js/app.js" type="text/javascript"></script> -->

  <!-- AdminLTE App -->
  <script src="<?=base_url();?>assets/dist/js/adminlte.min.js"></script>

  <!-- PAGE LEVEL SCRIPTS-->
  <script type="text/javascript">
    $(function () {
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
        highlight: function (e) {
          $(e).closest(".form-group").addClass("has-error")
        },
        unhighlight: function (e) {
          $(e).closest(".form-group").removeClass("has-error")
        },
      });
    });
  </script>

</body>

</html>
