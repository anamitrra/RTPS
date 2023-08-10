<!-- Content Wrapper. Contains page content -->
<div class="container py-5">
    <div class="d-md-flex justify-content-md-between">
    <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>DATABACE BACKUP</h1>
          </div>
          <div class="col-sm-6">
          <form class="search-form" method="post" action="<?=base_url("mongoBackup/backup")?>">
            <div class="input-group">
              <input type="text" name="db" class="form-control" placeholder="database name">
              <br/>
    <br/>
            
              <div class="input-group-append">
                <button type="submit" name="submit" class="btn btn-warning">Backup
                </button>
              </div>
            </div>
            <!-- /.input-group -->
          </form>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <!-- Main content -->
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-warning"> DATABACE RESTORE</h2>

        <div class="error-content">
         

          <form class="search-form" method="post" action="<?=base_url("mongoBackup/restore")?>">
            <div class="input-group">
              <input type="text" name="db" class="form-control" placeholder="database name">
              <br/>
    <br/>
    <textarea type="text" name="path" class="form-control" placeholder="database full path"></textarea>
              <br/>
    <br/>
              <div class="input-group-append">
                <button type="submit" name="submit" class="btn btn-warning">Restore
                </button>
              </div>
            </div>
            <!-- /.input-group -->
          </form>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
    <!-- /.content -->
  </div>
  </div>
  </div>