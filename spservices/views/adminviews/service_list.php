<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php } //End of if 
    ?>

    <div class="card shadow-sm mt-2">
        <div class="card-header bg-secondary">
            <span class="h5">All Services</span>
        </div>
        <div class="card-body">
            <?php if ($services) : ?>
                <table id="dtbl" class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Service Name</th>
                            <th>Department Name</th>
                            <th>Status</th>
                            <th style=" text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="">
                        <?php $j = 1;
                        foreach ($services as $row) {
                            $objId = $row->_id->{'$id'};
                        ?>
                            <tr>
                                <td><?= $j; ?></td>
                                <td><?= $row->service_name ?></td>
                                <td><?= $row->dept_info->dept_name ?? '' ?></td>
                                <td><?= ($row->status == 1) ? '<span class="text-success"><b>Active</b></span>' : '<span class="text-danger"><b>Suspended</b></span>' ?></td>
                                <td style="text-align: center;">
                                    <?php if ($row->status == 1) { ?>
                                        <button class="btn btn-danger btn-sm btn-block change_sts_btn" type="button" data-id="<?= $objId ?>" data-label="Suspend">
                                            <i class="fa fa-unlink fa-sm"></i> Suspend
                                        </button>
                                    <?php } else { ?>
                                        <button class="btn btn-primary btn-sm btn-block change_sts_btn" type="button" data-id="<?= $objId ?>" data-label="Active">
                                            <i class="fa fa-link fa-sm"></i> Active
                                        </button>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php $j++;
                        }
                        ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No records found
                <p>
                <?php endif; ?>
        </div>
    </div><!--End of .card-->
</div>
<div class="modal fade" id="statusModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-info py-2">
                <h4 class="modal-title">Change Service Status</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <p class="msg"></p>
                <input type="hidden" value="" name="objId" id="objId">
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="update_sts">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" type="text/css" media='all' href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />
<script type="text/javascript">
    $(document).ready(function() {
        $('#dtbl').DataTable();
        $('.change_sts_btn').on('click', function() {
            var objId = $(this).data('id');
            $('#statusModal').modal('show');
            $('#objId').val(objId);
            $('.msg').html('Do you want to ' + $(this).data('label') + ' the service ?');
        })

        $('#update_sts').on('click', function() {
            $('#statusModal').modal('hide');
            var objId = $('#objId').val();
            $.ajax({
                url: '<?= base_url('spservices/admin/services/update_service') ?>',
                type: 'POST',
                cache: false,
                data: {
                    "objId": objId
                },
                dataType: "JSON",
                success: function(response) {
                    console.log(response)
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.msg,
                            allowOutsideClick: false,
                        }).then(function(isConfirm) {
                            window.location.href = '<?= base_url("spservices/admin/services") ?>';
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            // title: 'Oops...',
                            text: 'Something went wrong.',
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire({
                        icon: 'error',
                        // title: 'Oops...',
                        text: 'Something went wrong.',
                    })
                }
            });
        })
    });
</script>