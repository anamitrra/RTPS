<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Inactive User</h1>
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
                  <td><?=$role->role_name;?></td>
                </tr>
                <tr>
                  <td><strong>Department</strong></td>
                  <td><?=$department->department_name;?></td>
                </tr>
                <tr>
                  <td><strong>Office Name</strong></td>
                  <td><?=$office_name?></td>
                </tr>
                <tr>
                  <td><strong>Office Address</strong></td>
                  <td><?=$office_address?></td>
                </tr>
                <tr>
                  <td><a href="<?php echo site_url('appeal/users/verify/').$id; ?>"  class="btn btn-primary">Verify</a></td>
                  <!-- <td><a href="<?php echo site_url('appeal/users'); ?>" class="btn btn-primary">Verify</a>
                  </td> -->
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

   
  </section>
</div>

