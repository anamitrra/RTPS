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
                    <h1>Activity Logs</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Activity Logs</li>
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
                        <h3 class="card-title">Activity Logs</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="">
                            <table id="ticket-table" class="table table-bordered table-hover table-striped">
                                <thead>
                                    <tr class="table-header">
                                        <th>No</th>
                                        <th>User</th>
                                        <th>Request URI</th>
                                        <th>Timestamp</th>
                                        <th>IP</th>
                                        <th>User Agent</th>
                                        <th>Refer Page</th>
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
                                "searching": false,
                                "ordering": true,
                                "order": [[ 3, "desc" ]],
                                "columns": [
                                    {
                                        "data": "sl_no"
                                    },
                                    {
                                        "data": "user"
                                    },
                                    {
                                        "data": "request_uri"
                                    },
                                    {
                                        "data": "timestamp"
                                    },
                                    {
                                        "data": "client_ip"
                                    },
                                    {
                                        "data": "client_user_agent"
                                    },
                                    {
                                        "data": "refer_page"
                                    }
                                ],
                                "ajax": {
                                    url: "<?php echo site_url("activity_logs/get_records") ?>",
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
