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
                    <li class="breadcrumb-item active">All Issued Certificates</li>
                </ol>
            </div>
        </div>
        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success text-center">
                <strong><?php echo $this->session->flashdata('success'); ?></strong>
            </div>
        <?php endif; ?>
        <div class="card shadow ">
            <div class="card-header" style="background:#1a4066; color:#fff">All Issued Certificates</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="application_list_table" width="100%">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>RTPS Ref. No</th>
                                <th>Certificate No.</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Service Name</th>
                                <th>Community</th>
                                <th>Submission on</th>
                                <th>Delivered on</th>
                                <!-- <th>Mobile Number</th>
                                <th>Community</th>
                                <th>Submission Date</th>
                                <th>Dispose Date</th> -->
                                <th></th>
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
    $(document).ready(function() {
        $('#application_list_table').on('click', '.regenerate_btn', function(event) {
            var id = encodeURIComponent(window.btoa(this.id));
            Swal.fire({
                title: 'Do you want to re-generate the certificate ?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes',
            }).then((result) => { 
                // regenerate_certificate
                if (result.value) {
                    window.location.replace('<?= base_url("spservices/office/actions/regenerate_certificate/") ?>'+id);
                }
            })
        })
    })
    var table = $('#application_list_table').DataTable({
        "ordering": false,
        "pagingType": "full_numbers",
        "lengthChange": false,
        "pageLength": 50,
        "orderMulti": false,
        "columns": [{
                "data": "sl_no"
            },
            {
                "data": "rtps_trans_id"
            },
            {
                "data": "cert_no"
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
                "data": "ddate"
            },
            // {
            //     "data": "status"
            // },
            {
                "data": "certificate",
                fnCreatedCell: function(nTd, sData, oData, iRow, iCol) {
                    $(nTd).html("<a class='btn btn-success btn-sm btn-block' href='" + oData.certificate + "'  target='_blank'>View</a><button type='button' class='btn btn-warning btn-sm btn-block regenerate_btn' id='" + oData.rtps_trans_id + "'>Regenerate</button>");
                }
            },
        ],
        "ajax": {
            url: "<?php echo site_url("spservices/office/get-certificates") ?>",
            type: 'POST',
            beforeSend: function() {
                // $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
            },
            complete: function() {
                // $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
            }
        },
    });
</script>