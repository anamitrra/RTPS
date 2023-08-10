<!-- Main content -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Users</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url("dashboard"); ?>">Home</a></li>
            <li class="breadcrumb-item active"><?= $breadcrumb_item ??  'Create User' ?></li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><?php echo $button ?> User</h3>
          </div>

          <div class="card-body">
            <form action="<?php echo $action; ?>" method="post">
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="name" class="fb-text-label">Name<?php echo form_error('name') ?><span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="name" id="name" value="<?= $name; ?>" required="required" aria-required="true" type="text">
                </div>
                <div class="form-group field-email col-md-6">
                  <label for="email" class="fb-text-label">Email <?php echo form_error('email') ?> <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="email" id="email" value="<?= $email; ?>" required="required" aria-required="true" type="email">
                </div>
              </div>
              <div class="row">
                <div class="fb-text form-group col-md-6">
                  <label for="roles" class="fb-text-label">Role <?php echo form_error('roleId') ?> <span class="fb-required text-danger">*</span></label>
                  <select class="form-control" id="roles" name="roleId" required>
                    <?php foreach ($roles as $role) { ?>
                      <option value="<?= $role->{'_id'}->{'$id'}; ?>" <?= $role->{'_id'}->{'$id'} == $roleId ? 'selected' : ''  ?>><?= $role->role_name; ?></option>
                    <?php } ?>
                  </select>
                </div>

                <div class="fb-text form-group col-md-6">
                  <label for="department" class="fb-text-label">Department <?php echo form_error('department') ?><span class="fb-required text-danger">*</span></label>
                  <select class="form-control" id="department" name="department">
                    <?php foreach ($departments as $department) { ?>

                      <option value="<?= $department->department_id; ?>" <?php
                                                                        if (isset($department_id)) {
                                                                          echo ($department->department_id === $department_id) ? 'selected' : '';
                                                                        }
                                                                        ?>>

                        <?= $department->department_name; ?>

                      </option>
                    <?php } ?>
                  </select>
                </div>

              </div>
              <div class="row">
                <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="designation" class="fb-number-label">Designation <?php echo form_error('designation') ?> <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="designation" id="designation" value="<?= $designation ?>" type="text">
                </div>

                <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="mobile" class="fb-number-label">Mobile <?php echo form_error('mobile') ?> <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="mobile" minlength="10" maxlength="10" id="mobile" value="<?= $mobile; ?>" type="text" pattern="^[6-9]\d{9}$">
                </div>
              </div>
              <div class="row">
                <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="office_name" class="fb-number-label">Office Name <?php echo form_error('office_name') ?> <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="office_name" id="office_name" value="<?= $office_name ?>" type="text" required>
                </div>

                <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="office_address" class="fb-number-label">Office Address <?php echo form_error('office_address') ?> <span class="fb-required text-danger">*</span></label>
                    <textarea class="form-control" name="office_address" id="office_address"  type="number" placeholder="Enter office address here ..." required><?= $office_address; ?></textarea>
                </div>
              </div>
              <div class="row">
                <div class="fb-text form-group field-password col-md-6">
                  <label for="password" class="fb-text-label">Password <?php echo form_error('password') ?></label>
                  <input class="form-control" name="password" id="password" value="<?= $password ?>" type="password">
                </div>
                <div class="fb-text form-group field-password col-md-6">
                  <label for="password" class="fb-text-label">Confirm Password <?php echo form_error('c_password') ?></label>
                  <input class="form-control" name="c_password" id="c_password" value="<?= $password ?>" type="password">
                </div>
              </div>


              <input type="hidden" name="userId" value="<?php echo $userId; ?>" />
              <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
              <a href="<?php echo base_url('dashboard/users') ?>" class="btn btn-default">Cancel</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>