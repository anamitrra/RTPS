<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style>
    .parsley-errors-list {
        color: red;
    }

    .admin-login input {
        border-radius: 0;
    }

    .captchaView {
        display: flex;
        flex-direction: row;
    }

    #captchaAdminParent {
        flex: 70%;
        margin-bottom: 2px;
        border: 1px dotted black;
        margin-right: 5px;
    }

    .captcha-refresh {
        flex: 30%;
        border: 0;
        border-radius: 0;
        width: 50px;
        height: 36px !important;
        background: #888;
        color: #eee;
    }

    .captcha-refresh:hover {
        background: #888;
        color: #eee;
    }

    #captchaAdminParent img {
        width: 100% !important;
        height: 35px !important;
    }

    #admin_send_otp {
        display: block;
        border-radius: 0;
        width: 100%
    }

    #adminlogin,
    #adminResendOtp {
        border-radius: 0;
        display: block;
        width: 100%
    }
</style>
<div class="admin-login">
    <div class="row">
        <div class="card border-top-0 rounded-0">
            <form id="adminloginForm" method="POST" action="<?=base_url('iservices/admin/SysLogin/admin')?>">
                <div class="card-body mt-3">
                    <div id="adminloginInfo">
                        <div class="form-group">
                            <label for="contactEmail">PFC Email-ID</label>
                            <input type="email" class="form-control" name="admincontactEmail" id="admincontactEmail" placeholder="Enter your email-id" required>
                        </div>
                        <div class="form-group">
                            <label for="contactNumber">Password</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="password"  required>
                        </div>
                        <div class="captchaDiv">
                            <div class="form-group mt-2">
                                <label for="captcha">Enter Captcha</label>
                                <div class="captchaView">
                                    <div class="" id="captchaAdminParent">
                                        <?= $cap['image']; ?>
                                    </div>
                                    <button type="button" class="btn btn-sm captcha-refresh" id="refreshAdminCaptcha">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>
                                <input type="text" class="form-control" name="admincaptcha" id="admincaptcha" placeholder="Enter captcha text" maxlength="6" required>
                            </div>
                        </div>
                        <button type="submit" id="admin_send_otp" class="btn btn-block btn-primary mt-2 mb-2">
                            <span id="adminloginInfoSubmitProcess" class="d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                            </span>
                            <span id="adminsendOTPbtnTxt">Login</span>
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>