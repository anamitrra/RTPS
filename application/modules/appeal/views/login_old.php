<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $pageTitle; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url(); ?>assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/custom.css">
</head>

<body class="hold-transition login-page">
  <h1 class="text-center">Assam Right To Public Services</h1>
  <h4 class="text-center">Appeal Management System</h4>
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b></b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <form id="login-form" action="<?php echo base_url(); ?>/appeal/login/loginMe" method="post">
          <h4 class="login-box-msg">Log in</h4>
          <?php $this->load->helper('form'); ?>
          <div class="row">
            <div class="col-md-3">
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
          <?php } ?>
          <div class="form-group">
            <div class="input-group-icon">
              <input class="form-control" type="email" id="email" name="email" placeholder="Email" autocomplete="off">
              <span class="input-icon"><i class="fa fa-envelope"></i></span>
            </div>
          </div>
          <div class="form-group">
            <div class="input-group-icon">
              <input class="form-control" type="password" id="pass" name="password" placeholder="Password">
              <span class="input-icon"><i class="fa fa-lock font-16"></i></span>
            </div>
          </div>
          <div class="form-group d-flex justify-content-between">
            <label class="ui-checkbox ui-checkbox-info">
              <input type="checkbox">
              <span class="input-span"></span>Remember me</label>
            <a href="<?php echo base_url() ?>appeal/login/forgotPassword">Forgot password?</a>
          </div>
          <div class="form-group">
            <button class="btn btn-primary btn-block" type="submit">Login</button>
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
  <script src="<?=base_url();?>assets/js/crypto-js.js"></script>
  <script src="<?=base_url();?>assets/js/encrypt.js"></script>
  <script type="text/javascript">
    $(function() {
      $('#login-form').on("submit", function(e) {
      e.preventDefault();
      var password = $('#pass').val();
      var email = $('#email').val();
      let encryption = new Encryption();
      var encrypted = encryption.encrypt(password, 'asdf-ghjk-qwer-tyui');
      var encrypted2 = encryption.encrypt(email, 'asdf-ghjk-qwer-tyui');
      $("#pass").val(encrypted);
      $("#email").val(encrypted2).attr("type","password");
      $('#login-form').unbind('submit').submit();
      return true;
    });
    });
  </script>
</body>

</html>