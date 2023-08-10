<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">

<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

<div class="content-wrapper" style="min-height: 1376.4px;">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3>Portals</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Portals</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List</h3>
                    <a href="<?=base_url("external-portal/portals/create")?>" class="btn btn-primary btn-sm float-right" ><i class="fas fa-plus" aria-hidden="true"></i>&nbsp;
                        Add</a>
                </div>
                <?php  if ($this->session->flashdata('status')=="error") {
                  ?>
                    <div class="alert alert-danger alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                      <?php echo $this->session->flashdata('message'); ?>
                    </div>
                  <?php
                  }

                  if ($this->session->flashdata('status')=="success") {
                  ?>
                    <div class="alert alert-success alert-dismissable">
                      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('message'); ?>

                    </div>
                  <?php } ?>
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="applications" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th>Portal Name</th>
                                <th>Portal No</th>
                                <th>Service Id</th>
                                <th>URL</th>
                                <th>Service Name</th>
                                <th>Payment Required</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <!-- <tfoot>
                            <tr>
                              <th width="5%">#</th>
                              <th>Portal Name</th>
                              <th>Portal No</th>
                              <th>Service Id</th>
                              <th>URL</th>
                              <th>Guidelines</th>
                              <th>Payment Required</th>
                              <th class="text-center">Action</th>
                            </tr>
                        </tfoot> -->

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
</section>
</div>


<script>
    $(document).ready(function() {

        var table = $('#applications').DataTable({
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
                    "orderable": false,
                    "sClass": "text-center",
                },
            ],
            "columns": [{
                    "data": "sl_no"
                },
                {
                    "data": "portal_name"
                },
                {
                    "data": "portal_no"
                },
                {
                    "data": "service_id"
                },
                {
                    "data": "url"
                },
                {
                    "data": "service_name"
                },
                {
                    "data": "payment_required"
                },
                {
                    "data": "action"
                },
            ],
            "ajax": {
                url: "<?php echo base_url("external-portal/portals/get_records") ?>",
                type: 'POST',
                beforeSend: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Searching..');
                },
                complete: function() {
                    $("#sub").html('<i class="fa fa-search" aria-hidden="true"></i>&nbsp;Search');
                }
            },

        });




    }); //End of ready function
</script>
