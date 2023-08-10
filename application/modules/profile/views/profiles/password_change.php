<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Change Password</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">


                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <?php if (isset($success)) {
                        echo '<div class="alert alert-success">'.$success.'</div>';
                    }elseif (isset($error)) {
                        echo '<div class="alert alert-danger">'.$error.'</div>';
                    }

                ?>
                    <form method="post" action="<?= base_url('profile/password_update') ?>">
                        <div class="row">
                            <div class="offset-md-3 col-md-6 col-12 form-group">
                                <label for="old_pass">Enter Old Password</label>
                                <input type="password" name="old_pass" class="form-control <?php echo form_error('old_pass') ? 'is-invalid' : '' ?>" value="<?= set_value('old_pass') ?>" required>

                                <?php echo form_error('old_pass', '<div class="invalid-feedback" role="alert">', '</div>') ?>
                            </div>
                            <div class="offset-md-3 col-md-6 col-12 form-group">
                                <label for="password">New Password</label>
                                <input type="password" name="password" class="form-control  <?php echo form_error('password') ? 'is-invalid' : '' ?>" value="<?= set_value('password') ?>" required>
                                <?php echo form_error('password', '<div class="invalid-feedback" role="alert">', '</div>') ?>
                            </div>
                            <div class="offset-md-3 col-md-6 col-12 form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control  <?php echo form_error('confirm_password') ? 'is-invalid' : '' ?>" value="<?= set_value('confirm_password') ?>" required>
                                <?php echo form_error('confirm_password', '<div class="invalid-feedback" role="alert">', '</div>') ?>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>

</div>
<!-- Multi-select plugin -->
