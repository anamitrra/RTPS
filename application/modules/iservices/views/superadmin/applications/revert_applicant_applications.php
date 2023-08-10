<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">

<script src="<?=base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>

<div class="content-wrapper">
<div class="container">
    <div class="row pt-2">
        <div class="col-sm-12 pull-right">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=base_url("iservices/superadmin/dashboard");?>">Home</a></li>
              <li class="breadcrumb-item active">Revert Applications</li>
            </ol>
        </div>
    </div>
<?php if($this->session->flashdata('success')): ?>
    <div class="alert alert-success text-center">
        <strong><?php echo $this->session->flashdata('success'); ?></strong>
    </div>
<?php endif; ?>
    <div class="card shadow">
        <div class="card-header bg-info">Revert Applications to Applicant </div>
        <div class="card-body">
            <table class="table table-striped table-bordered" id="application_list_table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Application Id</th>
                        <th>Name</th>
                        <th width="10%">Mobile Number</th>
                        <th>Service Name</th>
                        <th>Submission Date</th>
                        <!-- <th>Forwarded On</th> -->
                        <!-- <th>Action</th> -->
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
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
            "pagingType": "full_numbers",
            "pageLength": 25,
            "orderMulti": false,
            "columns": [
                {
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
                    "data": "date"
                },
                // {
                //     "data": "rtps_trans_id",
                //     fnCreatedCell: function(nTd, sData, oData, iRow, iCol) {
                //         $(nTd).html("<a class='btn btn-xs btn-danger btn-block application_view_btn' href=''><i class='fa fa-eye'></i> &nbsp;&nbsp;VIEW CERTIFICATE</a>");
                //         }
                // },
            ],
            "ajax": {
                url: "<?php echo site_url("iservices/superadmin/applications/get_revert_applicant_applications") ?>",
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