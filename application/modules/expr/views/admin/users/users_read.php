<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Users</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?=base_url("appeal/dashboard");?>">Home</a></li>
            <li class="breadcrumb-item active">User View</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">User View</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-striped">
              <tbody>
                <tr>
                  <td><strong>Email</strong></td>
                  <td><?php echo $email; ?></td>
                </tr>
                <tr>
                  <td><strong>Name</strong></td>
                  <td><?php echo $name; ?></td>
                </tr>
                <tr>
                  <td><strong>Desgination</strong></td>
                  <td><?php echo $designation; ?></td>
                </tr>
                <tr>
                  <td><strong>Mobile</strong></td>
                  <td><?php echo $mobile; ?></td>
                </tr>
                <tr>
                  <td><strong>Role</strong></td>
                  <td><?=isset($role->role_name)?$role->role_name:"";?></td>
                </tr>
                <tr>
                  <td><strong>Department code</strong></td>
                  <td><?=$dept_code?></td>
                </tr>
                <tr>
                  <td><strong>Office Code</strong></td>
                  <td><?=$office_code?></td>
                </tr>
                <tr>
                  <td><strong>ACCOUNT1</strong></td>
                  <td><?=$account1?></td>
                </tr>
                <tr>
                  <td><strong>Office Address</strong></td>
                  <td><?=$office_address?></td>
                </tr>
                <tr>
                  <td></td>
                  <td><a href="<?php echo site_url('expr/admin/users'); ?>" class="btn btn-default">Close</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Change Password  -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Change Password</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form class="form-inline">

              <input type="password" name="password" class="form-control mb-2 mr-2" placeholder="New passoword"
                required>
              <input type="password" name="c_password" class="form-control mb-2 mr-2" placeholder="Confirm password"
                required>

              <button onclick="changePassword()" type="button" class="btn btn-primary mb-2 mr-2">Submit</button>
              <a href="<?php echo base_url('appeal/users'); ?>" class="btn btn-default mb-2 mr-2">Close</a>

            </form>

            <!-- error/success message -->
            <section class="message"></section>

          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>

  function changePassword() {
    const password = $('input[name=password]');
    const c_password = $('input[name=c_password]');

    $.ajax({
        url: '<?=base_url("dashboard/users/change_user_password");?>',
        type: 'POST',
        data: {
          password: password.val(),
          c_password: c_password.val(),
          userId: '<?=$id;?>'
        },
        dataType: 'json',

        beforeSend: function () {
          // disable the submit button

          $('form.form-inline > button').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>Submitting...');
          $('button').attr('disabled', 'disabled');
        },

        success: function (data) {
          $('section.message').html(data.message);
        },

        complete: function () {
          //  enable the submit button

          $('form.form-inline > button').text('Submit');
          $('button').removeAttr('disabled');

          // reset the input fields
          c_password.val('');
          password.val('');
        }

    });
  }

</script>
