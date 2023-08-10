<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<style>
    @-webkit-keyframes blinker {
  from {opacity: 1.0;}
  to {opacity: 0.0;}
}
.badges{
	text-decoration: blink;
	-webkit-animation-name: blinker;
	-webkit-animation-duration: 2s;
	-webkit-animation-iteration-count:infinite;
	-webkit-animation-timing-function:ease-in-out;
	-webkit-animation-direction: alternate;
}
</style>
<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-2">
            <div class="col-sm-12 pull-right">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url("spservices/office/dashboard"); ?>">Home</a></li>
                    <li class="breadcrumb-item active">All Applications</li>
                </ol>
            </div>
        </div>
        <?php if ($this->session->flashdata('success')) : ?>
            <div class="alert alert-success text-center">
                <strong><?php echo $this->session->flashdata('success'); ?></strong>
            </div>
        <?php endif; ?>
        <div class="card shadow ">
            <div class="card-header" style="background:#1a4066; color:#fff">Custom Filter</div>
            <div class="card-body py-1">
                <div class="row">
                    <div class="col-sm-3">
                        <label for="">RTPS Ref. No</label>
                        <p><input type="text" class="form-control form-control-sm" id="rtps_no"></p>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Status</label>
                        <select name="" id="status" class="form-control form-control-sm">
                            <option value="">Select Status</option>
                            <option value="UNDER_PROCESSING">Under Processing</option>
                            <option value="QUERY_ARISE">Query to Applicant</option>
                            <option value="QUERY_SUBMITTED">Query Submitted</option>
                            <option value="DELIVERED">Delivered</option>
                            <option value="REJECTED">Rejected</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="">Community</label>
                        <select name="" id="community" class="form-control form-control-sm">
                            <option value="">Select Community</option>
                            <option value="Muslim">Muslim</option>
                            <option value="Christian">Christian</option>
                            <option value="Sikh">Sikh</option>
                            <option value="Buddhists">Buddhists</option>
                            <option value="Zoroastrians(Parsi)">Zoroastrians(Parsi)</option>
                            <option value="Jain">Jain</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="">&nbsp;</label>
                        <p><button class="btn btn-danger btn-sm filter_btn" type="button"><i class="fa fa-search"></i> SEARCH</button>
                        <a href="<?= base_url("spservices/office/applications") ?>" class="btn btn-sm btn-warning"><i class="fa fa-history"></i> RESET</a></p>
                    </div>

                </div>
            </div>
        </div>
        <div class="card shadow ">
            <div class="card-header" style="background:#1a4066; color:#fff">All Applications</div>
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
                                <th>Submission On</th>
                                <th>Delivered On</th>
                                <th>Status</th>
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
    var table = $('#application_list_table').DataTable({
        "bFilter": false,
        "lengthChange": false,
        "ordering": false,
        "pageLength": 50,
        "processing": true,
        "serverSide": true,
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
                "data": "ddate"
            },
            {
                "data": "status",
                // badge
                fnCreatedCell: function(nTd, sData, oData, iRow, iCol) {
                    $(nTd).html(oData.status+"<br>"+oData.badge);
                }
            },
            {
                "data": "rtps_trans_id",
                fnCreatedCell: function(nTd, sData, oData, iRow, iCol) {
                    $(nTd).html("<a class='btn btn-xs application_view_btn' title='Click to view application' href=<?php echo site_url("spservices/office/application_details") ?>/" + oData.trans_ide + "><i class='fa fa-eye' style='color:blue;font-size:14px'></i></a>"+oData.action+oData.certificate);
                }
            },
        ],
        "ajax": {
            url: "<?php echo site_url("spservices/office/get-applications") ?>",
            type: 'POST',
            data: function(d) {
                d.csrf_mis = $('#csrf').val();
                d.rtps_no = $('#rtps_no').val();
                d.status = $('#status').val();
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