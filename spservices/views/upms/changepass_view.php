<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <form method="POST" action="<?=base_url('spservices/upms/users/updatepass')?>">
        <input name="login_username" value="<?=$dbrow->login_username?>" type="hidden" />
        <div class="card shadow-sm">
            <div class="card-header bg-dark">
                <span class="h5 text-white">Change login password</span>
            </div>
            <div class="card-body">                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Name of the user</label>
                        <input value="<?=$dbrow->user_fullname?>" class="form-control" type="text" readonly />
                    </div>  
                    <div class="col-md-6 form-group">
                        <label>Login username</label>
                        <input value="<?=$dbrow->login_username?>" class="form-control" type="text" readonly />
                    </div>
                </div>             
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Choose password<span class="text-danger">*</span></label>
                        <input class="form-control" name="pass_new" maxlength="30" autocomplete="off" type="password" />
                        <?= form_error("pass_new") ?>
                    </div>  
                    <div class="col-md-6 form-group">
                        <label>Confirm password<span class="text-danger">*</span></label>
                        <input class="form-control" name="pass_conf" maxlength="30" autocomplete="off"  type="password" />
                        <?= form_error("pass_conf") ?>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button class="btn btn-danger" type="reset">
                    <i class="fa fa-refresh"></i> RESET
                </button>
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-angle-double-right"></i> UPDATE
                </button>
            </div><!--End of .card-footer-->
        </div>
    </form>
</div>