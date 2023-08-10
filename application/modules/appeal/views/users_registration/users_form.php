<div class=" d-flex justify-content-center" style="margin-top:40px">
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title"> Appeal user registration</h5>
          </div>

          <div class="card-body">
            <form action="<?php echo $action; ?>" method="post">
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="name" class="fb-text-label">Name <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="name" id="name" value="<?= $name; ?>" required="required" aria-required="true" type="text">
                  <?php echo form_error('name') ?>
                </div>
                <div class="form-group field-email col-md-6">
                  <label for="email" class="fb-text-label">Email <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="email" id="email" value="<?= $email; ?>" required="required" aria-required="true" type="email">
                  <?php echo form_error('email') ?> 
                </div>
              </div>
              <div class="row">
                <div class="fb-text form-group col-md-6">
                  <label for="roles" class="fb-text-label">Role  <span class="fb-required text-danger">*</span></label>
                  <select class="form-control" id="roles" name="roleId" required>
                    <?php foreach ($roles as $role) { ?>
                      <option value="<?= $role->{'_id'}->{'$id'}; ?>" <?= $role->{'_id'}->{'$id'} == $roleId ? 'selected' : ''  ?>><?= $role->role_name; ?></option>
                    <?php } ?>
                  </select>
                  <?php echo form_error('roleId') ?>
                </div>

                <div class="fb-text form-group col-md-6">
                  <label for="department" class="fb-text-label">Department <span class="fb-required text-danger">*</span></label>
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
                  <?php echo form_error('department') ?>
                </div>

              </div>
              <div class="row">
                <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="designation" class="fb-number-label">Designation <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="designation" id="designation" value="<?= $designation ?>" type="text">
                  <?php echo form_error('designation') ?> 
                </div>

                <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="mobile" class="fb-number-label">Mobile  <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="mobile" minlength="10" maxlength="10" id="mobile" value="<?= $mobile; ?>" type="text" pattern="^[6-9]\d{9}$">
                  <?php echo form_error('mobile') ?>
                </div>
              </div>
              <div class="row">
                <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="office_name" class="fb-number-label">Office Name  <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="office_name" id="office_name" value="<?= $office_name ?>" type="text" required>
                  <?php echo form_error('office_name') ?>
                </div>

                <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="office_address" class="fb-number-label">Office Address <span class="fb-required text-danger">*</span></label>
                    <textarea class="form-control" name="office_address" id="office_address"  type="number" placeholder="Enter office address here ..." required><?= $office_address; ?></textarea>
                    <?php echo form_error('office_address') ?> 
                </div>
              </div>
              <div class="row">
                <div class="fb-text form-group field-password col-md-6">
                  <label for="password" class="fb-text-label">Password <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="password" id="password" value="<?= $password ?>" type="password">
                  <?php echo form_error('password') ?>
                </div>
                <div class="fb-text form-group field-password col-md-6">
                  <label for="password" class="fb-text-label">Confirm Password <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="c_password" id="c_password" value="<?= $password ?>" type="password">
                  <?php echo form_error('c_password') ?>
                </div>
              </div>


              <input type="hidden" name="userId" value="<?php echo $userId; ?>" />
              <button type="submit" class="btn btn-primary mt-3">Submit</button>
              <!-- <a href="<?php echo site_url('appeal/users') ?>" class="btn btn-default">Cancel</a> -->
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
