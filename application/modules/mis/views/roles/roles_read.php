<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Roles</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=base_url("appeal/dashboard")?>">Home</a></li>
                        <li class="breadcrumb-item active">Roles</li>
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
                        <h3 class="card-title">Role List</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">  
        <h2 style="margin-top:0px">VIew</h2>
        <table class="table">
	    <tr><td>role</td><td><?php echo $role; ?></td></tr>
	    <tr><td></td><td><a href="<?php echo base_url('appeal/roles') ?>" class="btn btn-default">Cancel</a></td></tr>
        </table>
        </div>
</div>
</div>
</div>
    </section>
</div>