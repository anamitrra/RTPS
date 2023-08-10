<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RTPS</title>

    <!--Bootstrap 4.5-->
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/frontend/plugins/bootstrap-4.5.0/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/css/hover.css') ?>">
    <!--Fontawesome-->
    <link rel="stylesheet" type="text/css"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/frontend/plugins/css_50NeAi7JFrdZIQ4-8SzJGvFZILwe8ujnNw-BtlD8uFk.css') ?>">
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/frontend/plugins/css_7sFRun3KMLmgqxmwZkZmgWA4IBYgF3fW1AeYVm5Vn3M.css') ?>">
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/frontend/plugins/css_xE-rWrJf-fncB6ztZfd2huxqgxu4WO-qwma6Xer30m4.css') ?>">
    <link rel="stylesheet" type="text/css"
          href="<?= base_url('assets/frontend/plugins/css_ZbgrQ2AkdXebvD2F_kVnXdm4EZhJTHEMbaiPUq-uvgA.css') ?>">
    <!--Custom CSS-->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/frontend/css/style.css') ?>">

    <!--Jquery-->
    <script type="text/javascript"
            src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    <!--Popper-->
    <script type="text/javascript"
            src="<?= base_url('assets/frontend/plugins/bootstrap-4.5.0/js/popper.min.js') ?>"></script>
    <!--Bootstrap 4.5-->
    <script type="text/javascript" src="<?= base_url('assets/frontend/plugins/bootstrap-4.5.0/js/bootstrap.bundle.min.js') ?>"></script>
    <link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
    <style>
        .parsley-errors-list{
            color: red;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-sm-5 mx-auto">
            <div class="card my-2">
                <div class="card-body">
                    <form id="loginForm" method="POST" action="">
                        <div id="loginInfo">
                            <h2 class="text-center">Login</h2></br>

                            <div class="form-group">
                                <label for="contactNumber">Contact Number</label>
                                <input type="text" class="form-control" name="contactNumber" id="contactNumber" placeholder="Enter your contact number"  minlength="10" maxlength="10" pattern="^[6-9]\d{9}$" required>
                            </div>

                            <button type="button" id="send_otp" class="btn btn-block btn-primary mb-2" >
                                <span id="loginInfoSubmitProcess" class="d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                                </span>
                                <span id="sendOTPbtnTxt">Send OTP</span>
                            </button>


                        </div>


                            <div id="otpDetails" class="d-none">
                                <div class="form-group">
                                    <div class="alert alert-primary" role="alert">
                                        An OTP is being sent to <span class="font-weight-bold" id="mobile_no_span"></span><br/>Please enter the otp.
                                    </div>
                                    <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP" data-parsley-required-message="OTP required" required>
                                </div>

                                <button type="button" id="login" class="btn btn-block btn-primary" >Submit OTP
                                </button>
                                <button type="button" id="resendOtp" class="btn btn-block btn-outline-info mb-2" > <i class="fas fa-paper-plane mr-3"></i>Resend OTP
                                </button>
                            </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<body>
</html>
<script>
    var sendOTPurl     = '<?=base_url('external-portal/send-otp')?>';
    var loginUrl = '<?=base_url('external-portal/process-login')?>';
    var guidelineUrl = '<?=base_url('external-portal/guidelines')?>';
</script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/js/custom/login.js') ?>"></script>
