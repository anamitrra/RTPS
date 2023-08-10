<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-2">
            <div class="col-sm-12 pull-right">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url("spservices/office/dashboard"); ?>">Home</a></li>
                    <li class="breadcrumb-item active">Pending Applications</li>
                </ol>
            </div>
        </div>

        <?php if ($this->session->flashdata('success')) : ?>
            <script>
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Success',
                    text: '<?php echo $this->session->flashdata('success'); ?>',
                    showConfirmButton: false,
                    timer: 1500
                })
            </script>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error')) : ?>
            <script>
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo $this->session->flashdata('error'); ?>',
                    showConfirmButton: false,
                    timer: 1500
                })
            </script>
        <?php endif; ?>
        
        <div class="card shadow ">
            <div class="card-header" style="background:#1a4066; color:#fff">Pending Applications</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="application_list_table" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>RTPS Ref. No</th>
                                <th>Name</th>
                                <th>Mobile Number</th>
                                <th>Service Name</th>
                                <th>Community</th>
                                <th>Submission Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?PHP $csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
); ?>

<input id="csrf" type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
<script type="text/javascript">
    var table = $('#application_list_table').DataTable({
        "ordering": false,
        "lengthChange": false,
        "pagingType": "full_numbers",
        "pageLength": 50,
        "orderMulti": false,
        "language": {
            "processing": "<span class='fa-stack fa-lg'>\n\
                            <i class='fa fa-spinner fa-spin fa-stack-2x fa-fw'></i>\n\
                       </span>&emsp;Processing ..."
        },
        "columns": [{
                "data": "sl_no"
            },
            {
                "data": "rtps_trans_id"
            },
            {
                "data": "name"
            },
            {
                "data": "mobile_number"
            },
            {
                "data": "service_name"
            },
            {
                "data": "community"
            },
            {
                "data": "date"
            },
            {
                "data": "rtps_trans_id",
                fnCreatedCell: function(nTd, sData, oData, iRow, iCol) {
                    $(nTd).html("<a class='btn btn-xs btn-info btn-block application_view_btn' href=<?php echo site_url("spservices/office/application_details") ?>/" + oData.trans_ide + ">VIEW</a><a class='btn btn-xs btn-warning btn-block' href=<?php echo site_url("spservices/office/applications/action") ?>/" + oData.trans_ide + ">TAKE ACTION</a>");
                }
            },
        ],
        "ajax": {
            url: "<?php echo site_url("spservices/office/get-pending-applications") ?>",
            type: 'POST',
            data: function(d) {
                d.csrf_mis = $('#csrf').val();
                d.rtps_no = $('#rtps_no').val();
                // d.status = $('#status').val();
                d.community = $('#community').val();

            },
            beforeSend: function() {
                // $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
            },
            complete: function() {
                // $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
            }
        },
    });
    // filter_btn
    $('.filter_btn').on('click', function() {
        table.draw();
    });
</script>