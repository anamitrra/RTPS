<!-- Main content -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Offices</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url("mis"); ?>">Home</a></li>
            <li class="breadcrumb-item active"><?= $breadcrumb_item ??  'Create Office' ?></li>
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
            <h3 class="card-title"><?php echo $button ?> Office</h3>
          </div>

          <div class="card-body">
            <form action="<?php echo $action; ?>" method="post">
            
              <div class="row">
               
              <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="office_name" class="fb-number-label">Office Name <?php echo form_error('office_name') ?> <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="office_name" id="office_name" value="<?= $office_name ?>" type="text" required>
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
                
                <div class="fb-text form-group col-md-6">
                  <label for="department" class="fb-text-label">District <?php echo form_error('department') ?><span class="fb-required text-danger">*</span></label>
                  <select class="form-control" id="district_id" name="district_id">
                    <?php foreach ($districts as $dis) { ?>

                      <option value="<?= $dis->distcode; ?>" <?php
                                                                        if (isset($district_id)) {
                                                                          echo ($dis->distcode === $district_id) ? 'selected' : '';
                                                                        }
                                                                        ?>>

                        <?= $dis->distname; ?>

                      </option>
                    <?php } ?>
                  </select>
                </div>

              </div>

              <input type="hidden" name="officeId" value="<?php echo $officeId; ?>" />
              <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
              <a href="<?php echo base_url('mis/offices') ?>" class="btn btn-default">Cancel</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>