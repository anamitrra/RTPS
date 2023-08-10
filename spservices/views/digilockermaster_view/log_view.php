<link href="<?= base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" />
<script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {

        // $("#dtbl").DataTable({
        //     "lengthMenu": [
        //         [20, 50, 100, 200, 500],
        //         [20, 50, 100, 200, 500]
        //     ]
        // });

        $('#dtbl').DataTable({
            "serverSide": true,
            "pageLength": 10,
            "processing": true,
            "ajax": {
                url: "<?= base_url('spservices/digilockermaster/log/get_records') ?>",
                type: 'POST',
                data: function(d) {
                    d.csrf_mis = $('#csrf').val();
                }
            },
            "columns": [{
                    "data": "sl_no"
                },
                {
                    "data": "ref_no"
                },
                {
                    "data": "status"
                },

                {
                    "data": "digilocker_id"
                },
                {
                    "data": "uri"
                },
                {
                    "data": "doctype"
                },
                {
                    "data": "description"
                },
                {
                    "data": "error_response"
                },
                {
                    "data": "attempt_on"
                }
            ]
        });
    });
</script>
<main class="rtps-container">
    <div class="container-fluid my-4">
        <div class="card">
            <div class="card-header bg-dark text-white">Digilocker push log</div>
            <div class="card-body">
                <table id="dtbl" class="table table-bordered table-striped" style="width:100%">
                    <thead class="table-info">
                        <tr>
                            <th>#</th>
                            <th>RTPS No.</th>
                            <th>Push Status</th>
                            <th>Digilocker ID</th>
                            <th>URI</th>
                            <th>Doc Type</th>
                            <th>Description</th>
                            <th>Error log</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</main>