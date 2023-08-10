<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">

<script src="<?=base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>

<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Second Appeals</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Second Appeals</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Second Appeal List</h3>
                    <?php
/*                        echo '<a class="float-right btn btn-success btn-xs" href="<?= base_url("ams/excel-export?appeal_type=second") ?>"><i class="fas fa-file-excel"></i> Export To Excel</a>';*/
                    ?>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="apeals" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Appeal No.</th>
                            <th>Applicant Name</th>
                            <th>Contact Number</th>
                            <th>Appeal Date</th>
                            <th>Process Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th width="5%">#</th>
                            <th>Appeal No.</th>
                            <th>Applicant Name</th>
                            <th>Contact Number</th>
                            <th>Appeal Date</th>
                            <th>Process Status</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
                "width": "15%",
                "targets": 0
            },
                {
                    "targets": 3,
                    "orderable": false
                },
                {
                    "targets": 4,
                    "orderable": false
                }
            ],
            "columns": [
                {
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
                    "data": "process_status",
                },
                {
                    "data": "action"
                },
            ],
            "ajax": {
                url: "<?php echo site_url("appeal/get_records/second") ?>",
                type: 'POST',
                beforeSend: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
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
<div class="modal fade" id="view_traveller">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="view-modal-body">
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Multi-select plugin -->
