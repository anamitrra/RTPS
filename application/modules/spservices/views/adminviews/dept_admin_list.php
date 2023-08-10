<link rel="stylesheet" href="<?= base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.css") ?>" type="text/css">
<?php 
if($dbrow){
    $title = 'Edit Department Admin';
    $name = $dbrow->user_fullname;
    $mobile = $dbrow->mobile_number;
    $email = $dbrow->email_id;
    $department_name = $dbrow->dept_info->dept_code;
    $username = $dbrow->login_username;
    $password = '';
    $designation = $dbrow->login_designation;
}
else{
    $title = 'Create Department Admin';
    $name = set_value('name');
    $mobile = set_value('mobile');
    $email = set_value('email');
    $department_name = set_value('department_name');
    $username = set_value('username');
    $password = set_value('password');
    $designation = set_value('designation');
}
?>
<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php } //End of if 
    ?>
    <form method="POST" action="<?= base_url('spservices/admin/deptadmin/create') ?>">
        <!-- <input name="obj_id" value="<?= $obj_id ?>" type="hidden" /> -->
        <div class="card shadow-sm">
            <div class="card-header bg-secondary">
                <span class="h5 text-white"><?= $title?></span>
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
                <p><span class="text-danger">*</span> All fields are required.</p>
                <div class="row mt-2">
                    <div class="col-md-6 form-group">
                        <label for="name">Name <span class="text-danger">*</span></label>
                        <input class="form-control" id="name" name="name" type="text" value="<?php echo $name; ?>">
                        <?= form_error("name") ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="mobile">Mobile <span class="text-danger">*</span></label>
                        <input class="form-control" id="mobile" name="mobile" type="text" value="<?php echo $mobile; ?>" maxlength="10">
                        <?= form_error("mobile") ?>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6 form-group">
                        <label for="email">Email</label>
                        <input class="form-control" id="email" name="email" type="email" value="<?php echo $email; ?>">
                        <?= form_error("email") ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="department_name">Department Name <span class="text-danger">*</span></label>
                        <select class="form-control" id="department_name" name="department_name">
                            <option value="">Please select</option>
                            <?php if($depts){ foreach ($depts as $dept) : ?>
                                <option value="<?php echo $dept->dept_code; ?>"><?php echo $dept->dept_name; ?></option>
                            <?php endforeach; }?>
                        </select>
                        <?= form_error("department_name") ?>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6 form-group">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input class="form-control" id="username" name="username" type="text" value="<?php echo $username; ?>">
                        <?= form_error("username") ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input class="form-control" id="password" name="password" type="password" value="<?php echo $password; ?>">
                        <?= form_error("password") ?>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6 form-group">
                        <label for="designation">Designation <span class="text-danger">*</span></label>
                        <input class="form-control" id="designation" name="designation" type="designation" value="<?php echo $designation; ?>">
                        <?= form_error("designation") ?>
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


    <div class="card shadow-sm mt-2">
        <div class="card-header bg-secondary">
            <span class="h5">Registered users</span>
        </div>
        <div class="card-body">
            <?php if ($users) : ?>
                <table id="dtbl" class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Department Name</th>
                            <th>Username</th>
                            <th>Login Status</th>
                            <th>Designation</th>
                            <th style=" text-align: center;" width="10%">Action</th>

                        </tr>
                    </thead>
                    <tbody id="">
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td><?php echo $user->user_fullname; ?></td>
                                <td><?php echo $user->mobile_number; ?></td>
                                <td><?php echo $user->email_id; ?></td>
                                <td><?php echo $user->dept_info->dept_name; ?></td>
                                <td><?php echo $user->login_username; ?></td>
                                <td><?= ($user->status == 1) ? '<span class="text-success"><b>Active</b></span>' : '<span class="text-danger"><b>Inactive</b></span>' ?></td>
                                <td><?php echo $user->login_designation ?? ''; ?></td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <a href="<?= base_url('spservices/admin/deptadmin/index/' . $user->_id->{'$id'}) ?>" class="btn btn-warning btn-sm">
                                            <i class="fa fa-edit fa-sm"></i>
                                        </a>

                                        <a href="<?= base_url('spservices/admin/users/password/') ?>" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Change password">
                                            <i class="fas fa-lock fa-sm"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No records found
                <p>
                <?php endif; ?>
        </div>
    </div><!--End of .card-->
</div>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" type="text/css" media='all' href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />
<script type="text/javascript">
    $(document).ready(function() {
        $('#dtbl').DataTable();
    });
</script>