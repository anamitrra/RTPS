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
          <li class="breadcrumb-item active"><a href="">Offline Offices</a></li>
          <!-- <li class="breadcrumb-item active">My Applications</li> -->
        </ol>
      </div><!-- /.container-fluid -->
    </div>

    <div class="container">
       
      <div class="card my-12">
        <div class="card-header">
          <h3 class="card-title">Offline Offices</h3>
          <?php echo anchor(site_url('spservices/offline/office_list/form'), 'Create Office', 'class="btn btn-sm btn-primary float-right"'); ?>
        </div>
        <div class="card-body">
        <div class="row">
                        <div class="col-md-12 text-center">
                            <?php if ($this->session->userdata('message') <> '') { ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success</strong> <?php echo $this->session->userdata('message') <> '' ? $this->session->userdata('message') : ''; ?>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php } ?>
                        </div>
         </div>
          <!-- table -->
          <table id="applications" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Office ID</th>
                            <th>Office Name</th>
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
                    "data": "office_id"
                },
                {
                    "data": "office_name"
                },
               
                {
                    "data": "action"
                },
                
            ],
            "ajax": {
                url: "<?php echo site_url("spservices/offline/office_list/get_records") ?>",
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