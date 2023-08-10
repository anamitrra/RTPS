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
                    <h1 class="m-0 text-dark">APPEAL<?php //$this->session->userdata("department_name")
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
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-university"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Appeals</span>
                            <a href="#!">
                            <!-- <a href="<?= base_url("appeal/reports/total") ?>"> -->
                            <span class="info-box-number" id="total">
                                    <div class="spinner-grow text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </span></a>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-list"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Appeal initiated</span>
                            <span class="info-box-number" id="new">
                                <div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fa fa-file"></i></span>
                        <div class="info-box-content">
                       <span class="info-box-text">Pending 30 > < 45 </span>
                       <a href="#!">
                       <!-- <a href="<?=base_url("appeal/reports/pending")?>">  -->
                       <span class="info-box-number" id="pending_appeals_beyond_30days_not_beyond_45days">
                                        <div class="spinner-grow text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </span></a>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-check-square"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Pending beyond 45 days</span>
                            <span class="info-box-number" id="pending_appeals_beyond_45days">
                                <div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-university"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Disposed Within 30 days</span>
                            <a href="#!">
                            <!-- <a href="<?= base_url("appeal/reports/disposed_within_30 ") ?>"> -->
                             <span class="info-box-number" id="disposed_appeals_within_30days">
                                <div class="spinner-grow text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <!-- /.col -->
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fa fa-file"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Rejected Appeals</span>
                            <a href="#!">
                            <!-- <a href="<?= base_url("appeal/reports/rejected") ?>"> -->
                                <span class="info-box-number" id="rejected">
                                    <div class="spinner-grow text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </span>
                            </a>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-check-square"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Resolved Appeals</span>
                            <a href="#!">
                            <!-- <a href="<?= base_url("appeal/reports/resolved") ?>"> -->
                            <span class="info-box-number" id="resolved">
                                    <div class="spinner-grow text-primary" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </span></a>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="input-group input-group-sm">
                                        <input class="form-control" id="search_term" type="search" placeholder="Type Appeal Id Here" aria-label="Search">
                                       
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <button id="findAppeal" class="btn btn-primary btn-sm">Find An Appeal</button>
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
                                   <!-- <button id="findAppeal">find</button> -->
                                   <div id="appeal_details">

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
            $.get('<?= base_url("mis/appeal/count_appeals_location") ?>', function(data, status) {
                $('#total').text(data.total);
                $('#new').text(data.new);
                $('#pending_appeals_beyond_30days_not_beyond_45days').text(data.pending_appeals_beyond_30days_not_beyond_45days);
                $('#pending_appeals_beyond_45days').text(data.pending_appeals_beyond_45days);
                $('#disposed_appeals_within_30days').text(data.disposed_appeals_within_30days);
                $('#resolved').text(data.resolved);
                $('#rejected').text(data.rejected);

            });


            $("#findAppeal").on('click',function(){
                // $.get('<?= base_url("mis/appeal/find_appeal") ?>', function(data, status) {
              
                //     $("#appeal_details")
                // });

                    let search_term=$("#search_term").val();
                    
                    if(search_term ==='' || search_term ==='undefine' || search_term === null){
                        alert("Please Enter Appeal Id")
                    }else{
                        $.ajax({
                    url: '<?= base_url("mis/appeal/find_appeal") ?>',
                    type: 'POST',
                    data: {
                       appeal_id:search_term
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
                        $("#appeal_details").html(response);
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