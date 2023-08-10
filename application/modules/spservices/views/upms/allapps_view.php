<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')?>">
<script src="<?=base_url('assets/plugins/datatables/jquery.dataTables.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#dtbl").DataTable({
            "columns": [
                {"data": "user_fullname"},
                {"data": "login_username"},
                {"data": "mobile_number"},
                {"data": "user_services"}
            ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?=base_url('spservices/upms/allapps/get_records')?>",
                "dataType": "json",
                "type": "POST"
            },
            language: {
                processing: "<div class='loading'></div>"
            },
            "order": [[1, 'asc']],
            "lengthMenu": [[20, 30, 50, 100, 200], [20, 30, 50, 100, 200]]
        });
    });
</script>
<div class="content-wrapper p-2 pt-3">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <div class="card shadow-sm mt-2">
        <div class="card-header">
            <span class="h5 text-dark">Users List</span>
            <span style="float: right; color:#000">
                Logged in as <strong><?=$this->session->loggedin_user_fullname?></strong>
                (Role <?=$this->session->loggedin_user_role_code?> of Level-<?=$this->session->loggedin_user_level_no?>)
            </span>
        </div>
        <div class="card-body">                
            <table id="dtbl" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name of the user</th>
                        <th>Username</th>
                        <th>Mobile</th>
                        <th>Service</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>