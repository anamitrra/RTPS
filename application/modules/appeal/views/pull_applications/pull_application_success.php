<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">APPEAL DASHBOARD<?php //$this->session->userdata("department_name")
                                                                ?></h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("appeal/dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div><!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->
            
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Application Reassigned</h5>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-success" role="alert">
                                    <h4 class="alert-heading">Success!</h4>
                                    <p>All the pending applications is being reasigned to the current user</p>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </section>
</div>