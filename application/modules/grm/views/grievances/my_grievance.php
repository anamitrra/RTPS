<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/jquery-multi-select/src/example-styles.css" type="text/css">
<!-- DataTables -->
<script src="<?= base_url("assets/"); ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.js"></script>
<!--<script src="--><?//= base_url("assets/"); ?><!--plugins/jquery-multi-select/src/jquery.multi-select.min.js"></script>-->
<!-- jQuery Multi-select box -->
<!--<script src="/path/to/src/jquery.multi-select.js"></script>-->
<script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>
<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Grievances</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">My Grievances</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header p-0" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne"
                                aria-expanded="true" aria-controls="collapseOne">
                            Grievance List
                        </button>
                    </h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="grievanceListTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Registration No.</th>
                                <th>Grievance Reference No.</th>
                                <th>Mobile Number</th>
                                <th>Date of receipt</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var indexColumnData;
    $(function(){
        $('.select2').select2();
        var table = $('#grievanceListTable').DataTable({
            "processing": true,
            language: {
                processing: '<div class="lds-ripple"><div></div><div></div></div>',
            },
            "pagingType": "full_numbers",
            "pageLength": 25,
            "serverSide": true,
            "orderMulti": false,
            "columnDefs": [
                {
                    "width": "5%",
                    "targets": 0,
                    "orderable": false
                 },
                {
                    "targets": 5,
                    "orderable": false
                }
            ],
            "columns": [
                {
                    "data" : null,
                    "render" : function(){
                        return indexColumnData++;
                    }
                },
                {
                    "data": "registration_no"
                },
                {
                    "data": "GrievanceReferenceNumber"
                },
                {
                    "data": "MobileNumber"
                },
                {
                    "data": "DateOfReceipt"
                },
                {
                    "data": "action",
                    "render": function(data, type, row){
                        $btn = '<a class="btn btn-sm btn-outline-warning" href="<?=base_url('grm/view/')?>'+row.GrievanceReferenceNumber+'">View</a>';
                        return $btn;
                    }
                }
            ],
            "ajax": {
                url: "<?php echo site_url("grm/get_records") ?>",
                type: 'POST',
                data: function(d) {
                    // d.start_date = $('#start_date').val();
                },
                beforeSend: function() {
                    indexColumnData = 1;
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },
        });
        // table.on( 'order.dt search.dt', function () {
        //     table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        //         // console.log(cell,i);
        //         cell.innerHTML = i+1;
        //     } );
        // } ).draw();
    });
</script>