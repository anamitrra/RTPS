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
                    <h1>Rejected Appeals</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Rejected Appeals</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Rejected Appeal List</h3>
                    <?php
/*                    echo '<a class="float-right btn btn-success btn-xs" href="<?= base_url("ams/excel-export") ?>"><i class="fas fa-file-excel"></i> Export To Excel</a>';*/
                    ?>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="apeals" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Application No.</th>
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
                            <th>Application No.</th>
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

<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script type="text/javascript">
    const appealIdRef = $('#appeal_id');
    const approveExpiredAppealBtnRef = $('#approveExpiredAppealBtn');
    const approveExpiredApplicationFormRef = $('#approveExpiredApplicationForm');
    const approveExpiredURL = '<?=base_url('appeal/approve-expired')?>';
    var table;
    $(document).ready(function() {
        $('#approveExpiredApplicationForm').parsley();
        var st = $('#traveling_as').val();
        table = $('#apeals').DataTable({
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
                url: "<?php echo site_url("appeal/get_resolved_appeals") ?>",
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

    const approveExpiredApplications = function(that){
        let appealId = $(that).attr('data-id');
        $('#appeal_id').val(appealId);
        $('#approveExpiredApplicationModal').modal('show');
    }

    $(document).on('click','#approveExpiredAppealBtn',function(){
        if( $('#approveExpiredApplicationForm').parsley().validate()){
            $.post(approveExpiredURL,$('#approveExpiredApplicationForm').serialize()).done(function(response){
                if(response.success){
                    Swal.fire('success',response.msg,'success');
                    table.ajax.reload();
                    $('#approveExpiredApplicationModal').modal('hide');
                }
            }).fail(function(){
                Swal.fire('Fail','Unable to approve appeal!','error');
            })
        }
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
<div class="modal fade" id="approveExpiredApplicationModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Approve Expired Application</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="view-modal-body">
                <form method="post" id="approveExpiredApplicationForm" action="<?=base_url('appeal/approve-expired')?>">
                    <input type="hidden" name="appeal_id" id="appeal_id">
                    <div class="row">
                        <div class="col-12">
                            <label for="approval_reason">Approval Reason</label>
                            <textarea name="approval_reason" id="approval_reason" class="form-control" placeholder="Write reason for approval" required></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-success" id="approveExpiredAppealBtn">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Multi-select plugin -->
