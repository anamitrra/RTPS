<?php
if ($dbrow) {
    $title = "Reset Password";
    $obj_id = $dbrow->{'_id'}->{'$id'};
} else {
    $title = "Reset Password";
    $obj_id = null;
} //End of if else
?>

<div class="content-wrapper mt-2 p-2">
    <div class="col-md-10 mx-auto">
        <?php if ($this->session->flashdata('flashMsg') != null) { ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <?= $this->session->flashdata('flashMsg') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        <?php } //End of if 
        ?>
        <form method="POST" action="<?= base_url('spservices/admin/users/edit_password') ?>">
            <input name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header bg-dark">
                    <span class="h5 text-white"><?= $title ?></span>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('error') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('success') != null) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                    <?php } ?>

                    <div class="row mt-2">
                        <div class="col-md-6 form-group">
                            <label>Choose login password</label>
                            <input class="form-control" name="login_password" value="" maxlength="100" type="password" />
                            <?= form_error("login_password") ?>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Confirm login password</label>
                            <input class="form-control" name="login_password_conf" value="" maxlength="100" type="password" />
                            <?= form_error("login_password_conf") ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button class="btn btn-danger" type="reset">
                        <i class="fa fa-refresh"></i> RESET
                    </button>
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-angle-double-right"></i> SUBMIT
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div>
</div>