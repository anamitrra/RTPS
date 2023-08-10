<style>
    .info-box .info-box-text, .info-box .progress-description {
        text-overflow: unset!important;
        white-space: unset!important;
    }
</style>
<!-- Main content -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">Applications<?php //$this->session->userdata("department_name")
                                                                ?></h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("mis"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div><!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->
            
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="input-group input-group-sm">
                                        <input class="form-control" id="search_term" type="search" placeholder="Type Application Ref No Here" aria-label="Search">
                                       
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <button id="findApplication" class="btn btn-primary btn-sm">Search</button>
                                </div>
                            </div>
                       
                            <div class="card-tools">
                                <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>

                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button> -->
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                   <!-- find applea -->
                                   <!-- <button id="findApplication">find</button> -->
                                   <div id="application_details">

                                   </div>
                                </div>

                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- ./card-body -->
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <!-- /.row -->
            
            <!-- /.row -->
        </div>
        <!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script src="<?= base_url("assets/"); ?>plugins/chart.js/Chart.min.js"></script>
<script>
    $(function() {
        'use strict'
      
        $(document).ready(function() {

            $("#findApplication").on('click',function(){
               
                    let search_term=$("#search_term").val();
                    if(search_term ==='' || search_term ==='undefine' || search_term === null){
                        alert("Please Enter Application ref No")
                    }else{
                        $.ajax({
                    url: '<?= base_url("mis/find-application") ?>',
                    type: 'POST',
                    data: {
                        app_ref_no:search_term
                    },
                    beforeSend: function(){
                        swal.fire({
                            html: '<h5>Processing...</h5>',
                            showConfirmButton: false,
                            allowOutsideClick: () => !Swal.isLoading(),
                            onOpen: function() {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        $("#application_details").html(response);
                    },
                    error: function() {
                        console.log('error')
                    },
                }).always(function(){
                    swal.close();
                });
                    }
               
                
            });
           
        });

    });
</script>