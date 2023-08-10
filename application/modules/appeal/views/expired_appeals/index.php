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
                    <h1>Delayed Appeals</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                        <li class="breadcrumb-item active">Delayed Appeals</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Delayed Appeal List</h3>
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
    const rejectExpiredURL = '<?=base_url('appeal/reject-expired')?>';
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
                url: "<?php echo site_url("appeal/get_expired_appeals") ?>",
                type: 'POST',
                beforeSend: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },
        });
    });

    const approveExpiredApplications = function(that){
        let appealId = $(that).attr('data-id');
        $('#appeal_id').val(appealId);
        $('#approveExpiredApplicationModal').modal('show');
    }
    const rejectExpiredApplications = function(that){
        let appealId = $(that).attr('data-id');
        $('#reject_appeal_id').val(appealId);
        $('#rejectExpiredApplicationModal').modal('show');
    }
   

    $(document).on('click','#approveExpiredAppealBtn',function(){
        if( $('#approveExpiredApplicationForm').parsley().validate()){
            $.ajax({
                url: approveExpiredURL,
                type: 'POST',
                dataType: 'JSON',
                data: $('#approveExpiredApplicationForm').serialize(),
                beforeSend: function(){
                    Swal.fire({
                        html: '<h5>Processing...</h5>',
                        showConfirmButton: false,
                        allowOutsideClick: () => !Swal.isLoading(),
                        onOpen: function() {
                            Swal.showLoading();
                        }
                    });
                },
                success:function(response){
                    if(response.success){
                        table.ajax.reload();
                        $('#approveExpiredApplicationModal').modal('hide');
                        Swal.close();
                        Swal.fire('success',response.msg,'success');
                    }else{
                        Swal.close();
                        Swal.fire('Fail','Unable to approve appeal!','error');
                    }
                },
                error:function(){
                    Swal.close();
                    Swal.fire('Fail','Unable to approve appeal!','error');
                }
            });
        }
    });
    $(document).on('click','#rejectExpiredAppealBtn',function(){
        if( $('#rejectExpiredApplicationForm').parsley().validate()){
            $.post(rejectExpiredURL,$('#rejectExpiredApplicationForm').serialize()).done(function(response){
                if(response.success){
                    Swal.fire('success',response.msg,'success');
                    table.ajax.reload();
                    $('#rejectExpiredApplicationModal').modal('hide');
                }
            }).fail(function(){
                Swal.fire('Fail','Unable to approve appeal!','error');
            })
        }
    });
</script>
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
<div class="modal fade" id="rejectExpiredApplicationModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-title">Reject Expired Application</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="view-modal-body">
                <form method="post" id="rejectExpiredApplicationForm" action="<?=base_url('appeal/reject-expired')?>">
                    <input type="hidden" name="reject_appeal_id" id="reject_appeal_id">
                    <div class="row">
                        <div class="col-12">
                            <label for="reject_reason">Reject Reason</label>
                            <textarea name="reject_reason" id="reject_reason" class="form-control" placeholder="Write reason for approval" required></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-success" id="rejectExpiredAppealBtn">Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- Multi-select plugin -->