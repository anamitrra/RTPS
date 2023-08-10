<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">


<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
<style>
.parsley-errors-list {
    color: red;
}

.mbtn {
    width: 100% !important;
    margin-bottom: 3px;
}

.blk {
    display: block;
}
</style>
<div class="content-wrapper">
    
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?= base_url("spservices/office/dashboard"); ?>">Home</a></li>
          <li class="breadcrumb-item active"><a href="">My Applications</a></li>
          <!-- <li class="breadcrumb-item active">My Applications</li> -->
        </ol>
      </div><!-- /.container-fluid -->
    </div>

    <div class="container">
       
      <div class="card my-12">
        <div class="card-header">
          <h3>Your Applications</h3>
          <a style="float: right;" class="btn btn-sm btn-primary" href="<?=base_url('iservices/admin/redirectional_payment/new')?>"> New Payment</a>
        </div>
        <div class="card-body">
          <!-- table -->
          <table id="applications" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>RTPS Trans No</th>
                            <th>Service Name</th>
                            <th>Applicant Name</th>
                            <th>Mobile</th>
                            <th>Status</th>
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

<script>
  $(document).ready(function() {
  
  
    var table = $('#applications').DataTable({
            "processing": true,
            language: {
                processing: '<div class="lds-ripple"><div></div><div></div></div>',
            },
            "pagingType": "full_numbers",
            "paging": true,
            "pageLength": 25,
            "serverSide": true,
            "orderMulti": false,
            "order": [[0, "desc"]],
            "columnDefs": [{
                "targets": 0,
                "orderable": true
                },
                {
                    "targets": 1,
                    "orderable": false
                }
            ],
            "columns": [
              {
                "data": "sl_no"
            },
                {
                    "data": "rtps_trans_id"
                },
                {
                    "data": "service_name"
                },
               
                {
                    "data": "applicant_name"
                },
                {
                    "data": "mobile"
                },
                {
                    "data": "status"
                },
                {
                    "data": "action"
                },
                
            ],
            "ajax": {
                url: "<?php echo site_url("iservices/admin/redirectional_payment/get_records") ?>",
                type: 'POST',
                beforeSend: function () {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function () {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },
        });



  
    // console.log("filer data",filter_data)
  });
</script>