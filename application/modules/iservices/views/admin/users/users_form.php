

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
            <li class="breadcrumb-item"><a href="<?= base_url("iservices/admin/dashboard"); ?>">Home</a></li>
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
                  <input class="form-control" name="name" id="name" value="<?= $registration_id; ?>" required="required" aria-required="true" type="text">
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
                <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="OFFICE_CODE" class="fb-number-label">Kiosk Type <?php echo form_error('type_of_kiosk') ?> <span class="fb-required text-danger">*</span></label>
                  
                  <select  class="form-control" name="type_of_kiosk" id="type_of_kiosk">
                      <option  value="">Select Kiosk Type</option>
                      <option <?= $type_of_kiosk==="RTPSKIOSK" ? 'selected':''?> value="RTPSKIOSK">RTPS Kiosk</option>
                      <option <?= $type_of_kiosk==="eDistrict" ? 'selected':''?> value="eDistrict">eDistrict Kiosk</option>
                  </select>
                </div>

              </div>
              <div class="row">
                    <div class="col-md-6">
                                <label>Districts *<span class="text-danger">*</span> </label>
                                <select name="district_id" id="district_id" class="form-control">
                                </select>
                                <?= form_error("district_id") ?>
                     </div>
                     <div class="col-md-6">
                                <label>Registration ID<span class="text-danger">*</span> </label>
                                <input name="registration_id" id="registration_id" class="form-control" value="<?= $name; ?>">
                                <?= form_error("registration_id") ?>
                     </div>
                            
               </div>
              <div class="row">
                <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="designation" class="fb-number-label">Designation <?php echo form_error('designation') ?> <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="designation" id="designation" value="Operator" type="text">
                </div>

                <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="mobile" class="fb-number-label">Mobile <?php echo form_error('mobile') ?> <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="mobile" minlength="10" maxlength="10" id="mobile" value="<?= $mobile; ?>" type="text" pattern="^[6-9]\d{9}$">
                </div>
              </div>
              <div class="row">
                <!-- <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="office_code" class="fb-number-label">Dept Code <?php echo form_error('dept_code') ?> <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="dept_code" id="dept_code" value="<?= $dept_code ?>" type="text" required>
                </div> -->
                <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="account1" class="fb-number-label">ACCOUNT1 <?php echo form_error('account1') ?> <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="account1" id="account1" value="<?= $account1 ?>" type="text" required>
                </div>

                <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="office_address" class="fb-number-label">Office Address <?php echo form_error('office_address') ?> <span class="fb-required text-danger">*</span></label>
                    <textarea class="form-control" name="office_address" id="office_address"  type="number" placeholder="Enter office address here ..." required><?= $office_address; ?></textarea>
                </div>
              </div>
              <!-- <div class="row">
                <div class="fb-text form-group field-password col-md-6">
                  <label for="password" class="fb-text-label">Password <?php echo form_error('password') ?></label>
                  <input class="form-control" name="password" id="password" value="<?= $password ?>" type="password">
                </div>
                <div class="fb-text form-group field-password col-md-6">
                  <label for="password" class="fb-text-label">Confirm Password <?php echo form_error('c_password') ?></label>
                  <input class="form-control" name="c_password" id="c_password" value="<?= $password ?>" type="password">
                </div>
              </div> -->


              <input type="hidden" name="userId" value="<?php echo $userId; ?>" />
              <button type="submit" class="btn btn-primary"><?php echo $button ?></button>
              <a href="<?php echo site_url('iservices/admin/users') ?>" class="btn btn-default">Cancel</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
  let district_id="<?=$district_id?>";
   $(document).on("change", "#type_of_kiosk", function(){               
            let selectedVal = $(this).val();
            if(selectedVal.length && selectedVal ==="eDistrict") {
                $.getJSON("<?=base_url("iservices/admin/users/getdistricts")?>", function (data) {
                    let selectOption = '';
                    $('#district_id').empty().append('<option value="">Select a districts</option>')
                    $.each(data, function (key, value) {
                        selectOption += '<option data-acc="'+value.gras_account_code+'" value="'+value.district_id+'">'+value.district_name+'</option>';
                    });
                    $('#district_id').append(selectOption);
                });
            }else{
              $('#district_id').empty().append('<option value="">Select a districts</option>')
              $('#account1').val('');
            }
        });

      $(document).ready(function(){
        if(district_id){
          $.getJSON("<?=base_url("iservices/admin/users/getdistricts")?>", function (data) {
                          let selectOption = '';
                          $('#district_id').empty().append('<option value="">Select a districts</option>')
                          $.each(data, function (key, value) {
                            if(district_id == value.district_id){
                              selectOption += '<option selected data-acc="'+value.gras_account_code+'" value="'+value.district_id+'">'+value.district_name+'</option>';
                            }else
                              selectOption += '<option data-acc="'+value.gras_account_code+'" value="'+value.district_id+'">'+value.district_name+'</option>';
                          });
                          $('#district_id').append(selectOption);
                      });
        }
      })
        $(document).on("change", "#district_id", function(){               
            // let selectedVal = $(this).data();
            let selectedVal = $(this).find(':selected').data('acc');
           console.log(selectedVal);
           $('#account1').val(selectedVal);
        });

        </script>