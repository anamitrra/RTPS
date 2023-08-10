<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<style>
    .parsley-errors-list {
        color: red;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-sm-5 mx-auto">
            <?php if ($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger text-center mt-3">
                    <strong><?php echo $this->session->flashdata('error'); ?></strong>
                </div>
            <?php endif; ?>
            <div class="card rounded-0 mt-2 mb-1">
                <div class="card-header">
                    <h5 class="text-center">Admin Login</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?= base_url('spservices/office/pwlogin/password_login') ?>">
                        <div>
                            <?php echo validation_errors(); ?>
                        </div>
                        <div id="password_login_view">
                            <div class="form-group">
                                <label for="username">Mobile Number</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Enter registered mobile no." maxlength="10" value="<?php echo set_value('username'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="*****" value="<?php echo set_value('password'); ?>" required>
                            </div>
                            <button id="pw_login" class="btn btn-block btn-primary mb-2">
                                <i class="fa fa-sign-in"></i> Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

<script>
</script>