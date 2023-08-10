<link rel="stylesheet" href="<?=base_url("assets/");?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?=base_url("assets/");?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<!-- DataTables -->
<script src="<?=base_url("assets/");?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url("assets/");?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?=base_url("assets/");?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?=base_url("assets/");?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Users</h3><?php echo anchor(site_url('appeal/users/create'), 'Create', 'class="float-right"'); ?>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                        <?php
                        if($this->session->flashdata('errors')){
                    ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error :</strong> <?=$this->session->flashdata('errors')?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php
                        }
                    ?>
                    <?php
                        if($this->session->flashdata('message')){
                    ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success :</strong> <?=$this->session->flashdata('message')?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php
                        }
                    ?>
                        </div>
                    </div>
                        <div class="">
                            <table id="ticket-table" class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr class="table-header">
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Department</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="small-text">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            var st = $('#search_type').val();
                            var table = $('#ticket-table').DataTable({
                                "processing": true,
                                "pagingType": "full_numbers",
                                "pageLength": 25,
                                "serverSide": true,
                                "orderMulti": false,
                                "columns": [{
                                        "data": "userId"
                                    },
                                    {
                                        "data": "name"
                                    },
                                    {
                                        "data": "mobile"
                                    },
                                    {
                                        "data": "email"
                                    },
                                    {
                                        "data": "department"
                                    },
                                    {
                                        "data": "action"
                                    }
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("appeal/users/get_records") ?>",
                                    type: 'POST',
                                    data: function(d) {
                                        d.search_type = $('#search_type').val();
                                    }
                                },
                            });
                            $('#form-search-input').on('keyup change', function() {
                                table.search(this.value).draw();
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
</div>
</section>
</div>
