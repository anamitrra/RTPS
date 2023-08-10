<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">DASHBOARD<?php //$this->session->userdata("department_name")
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
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-university"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Appeals</span>
                            <span class="info-box-number" id="total">
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
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-list"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">New Appeals</span>
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
                            <span class="info-box-text">Processed Appeals</span>
                            <span class="info-box-number" id="processed">
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
                <div class="col-12 col-sm-6 col-md-3">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-check-square"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Resolved Appeals</span>
                            <span class="info-box-number" id="resolved">
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
            <!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Appeal List</h3>
                            <?php
                            /*                    echo '<a class="float-right btn btn-success btn-xs" href="<?= base_url("ams/excel-export") ?>"><i class="fas fa-file-excel"></i> Export To Excel</a>';*/
                            ?>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <h4>If you want to make an appeal regarding an application please <a href="<?= base_url('appeal/myapplications') ?>">click here</a>
                            </h4>
                            <br />
                            <table id="apeals" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Application No.</th>
                                        <th>Applicant Name</th>
                                        <th>Contact Number</th>
                                        <th>Appeal Date</th>
                                        <th>Appeal Type</th>
                                        <th>Process Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Application No.</th>
                                        <th>Applicant Name</th>
                                        <th>Contact Number</th>
                                        <th>Appeal Date</th>
                                        <th>Appeal Type</th>
                                        <th>Process Status</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var st = $('#traveling_as').val();

        var table = $('#apeals').DataTable({
            "processing": true,
            language: {
                processing: '<div class="lds-ripple"><div></div><div></div></div>',
            },
            "pagingType": "full_numbers",
            "pageLength": 25,
            "serverSide": true,
            "orderMulti": false,
            "columnDefs": [{
                    "targets": 3,
                    "orderable": false
                },
                {
                    "targets": 7,
                    "width": "25%",
                    "orderable": false
                }
            ],
            "columns": [{
                    "data": "sl_no"
                },
                {
                    "data": "appeal_id"
                },
                {
                    "data": "name"
                },
                {
                    "data": "contact_no"
                },
                {
                    "data": "appeal_date",
                },
                {
                    "data": "appeal_type",
                },
                {
                    "data": "process_status",
                },
                {
                    "data": "action"
                },
            ],
            "ajax": {
                url: "<?php echo site_url("appeal/myappeals/get_records") ?>",
                type: 'POST',
                beforeSend: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },
            drawCallback: function () {
                $('[data-toggle="tooltip"]').tooltip();
            },
        });

        // Get Application's details
        $(document).on("click", ".modal-show", function() {
            console.log("modal");
            $("#modal-title").empty().append("Getting Data Of " + $(this).attr("data-appl_ref_no") + ".Please Wait....");
            $('#view-modal-body').empty().html('<div class="lds-ripple"><div></div><div></div></div>');
            var data_id = $(this).attr("data-id");
            var ref_no = $(this).attr("data-appl_ref_no");
            $.ajax({
                url: "<?php echo site_url("applications/view") ?>",
                type: 'GET',
                data: {
                    data_id
                },
                dataType: "html",
                success: function(data) {
                    $('#view-modal-body').empty().html(data);
                    $("#modal-title").empty().append("" + ref_no + "");
                }
            });
            $("#view_traveller").modal("show");
        });

    });
</script>
<script src="<?= base_url("assets/"); ?>plugins/chart.js/Chart.min.js"></script>
<script>
    $(function() {

        $(document).ready(function() {
            $.get('<?= base_url("appeal/status/api") ?>', function(data, status) {
                $('#total').text(data.total);
                $('#new').text(data.new);
                $('#processed').text(data.processed);
                $('#resolved').text(data.resolved);

            });
        });

    });
</script>
