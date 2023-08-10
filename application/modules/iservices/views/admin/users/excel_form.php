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
            <li class="breadcrumb-item active">Upload</li>
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
            <h3 class="card-title">Upload Excel</h3>
          </div>

          <div class="card-body">
            <form action="<?php echo base_url('iservices/admin/users/upload_action'); ?>" method="post" enctype="multipart/form-data">

              <div class="row">
                <!-- <div class="fb-number col-md-6 form-group ">
                  <label for="office_code" class="fb-number-label">Office Code  <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="office_code" id="office_code"  type="text"   >
                </div>
                <div class="fb-number col-md-6 form-group ">
                  <label for="dept_code" class="fb-number-label">Dept Code  <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="dept_code" id="dept_code"  type="text"  >
                </div> -->

              </div>
              <div class="row">
                <div class="fb-number col-md-6 form-group field-mobile">
                  <label for="designation" class="fb-number-label">File  <span class="fb-required text-danger">*</span></label>
                  <input class="form-control" name="file" id="file"  type="file" required accept=".xls, .xlsx" >
                </div>


              </div>

              <button type="submit" class="btn btn-primary">Upload</button>
              <a href="<?php echo site_url('iservices/admin/users') ?>" class="btn btn-default">Cancel</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
