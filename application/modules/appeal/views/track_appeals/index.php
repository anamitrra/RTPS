<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-sm-5 mx-auto">
            <div class="card my-2">
                <div class="card-body">
                    <form id="appealLoginForm" method="POST" action="<?= base_url('appeal/track/process-login') ?>">
                        <div id="loginInfo">
                            <h2 class="text-center">Track Appeal</h2></br>
                            <div class="form-group">
                                <label for="appealNumber">Appeal Number</label>
                                <input type="text" class="form-control" name="appealNumber" id="appealNumber" placeholder="Enter appeal number" required>
                            </div>

                            <?php
                            if(isset($this->session->userdata('role')->role_name) && $this->session->userdata('role')->role_name === 'PFC'){
                                ?>

                                <button type="button" id="login" class="btn btn-block btn-outline-primary">Submit</button>
                                <?php
                            }else{
                            ?>
                            <div class="form-group">
                                <label for="contactNumber">Contact Number</label>
                                <input type="text" class="form-control" name="contactNumber" id="contactNumber" placeholder="Enter registered contact number"  minlength="10" maxlength="10" pattern="^[6-9]\d{9}$" required>
                            </div>
                            <div class="row form-group">
                                <div class="col-5 pr-0" id="captchaParent">
                                    <?=$cap['image'];?>
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
                            <button type="button" id="submit" class="btn btn-block btn-outline-primary mb-2" >
                                <span id="loginInfoSubmitProcess" class="d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                                </span>
                                <span id="submitBtnTxt">Submit</span>
                            </button>
                            <a class="btn btn-block btn-outline-warning mb-2" href="<?= base_url("/");?>">Back<a>
                                    <?php
                                    }
                                    ?>
                        </div>

                        <?php
                        if (!$this->session->has_userdata('role')) {
                            ?>

                            <div id="otpDetails" class="d-none">
                                <div class="form-group">
                                    <div class="alert alert-primary" role="alert">
                                        An OTP is being sent to <span class="font-weight-bold" id="mobile_no_span"></span><br/>Please enter the otp.
                                    </div>
                                    <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP" data-parsley-required-message="OTP required" required>
                                </div>

                                <button type="button" id="login" class="btn btn-block btn-outline-primary" >Submit OTP
                                </button>
                                <button type="button" id="resendOtp" class="btn btn-block btn-outline-info mb-2" > <i class="fas fa-paper-plane mr-3"></i>Resend OTP
                                </button>
                            </div>

                            <?php
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var sendOTPurl     = '<?=base_url('appeal/track/send-otp')?>';
    var appealLoginUrl = '<?=base_url('appeal/track/process-login')?>';
    var appealApplyUrl = '<?=base_url('appeal/preview-n-track')?>';
    const refreshCaptchaURL = '<?=base_url('appeal/refresh-captcha')?>';
</script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/frontend/js/track_appeals/index.js') ?>"></script>