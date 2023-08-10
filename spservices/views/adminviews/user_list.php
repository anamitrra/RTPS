<?php
$depts = $this->depts_model->get_rows(array("status" => 1));
$services = $this->services_model->get_rows(array("status" => 1));
$districts = $this->districts_model->get_rows(array("country_id" => 1));
?>

<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php } //End of if 
    ?>

    <div class="card shadow-sm mt-2">
        <div class="card-header bg-secondary">
            <span class="h5">Registered users</span>
        </div>
        <div class="card-body">
            <?php if ($users) : ?>
                <table id="dtbl" class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Role/Designation</th>
                            <th>Service</th>
                            <th>Organization</th>
                            <th>Status</th>
                            <th style=" text-align: center;" width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody id="">
                        <?php $j = 1;
                        foreach ($users as $key => $row) {
                            if ($row->user_types->utype_id != 1) {

                                $objId = $row->_id->{'$id'};
                                $user_services = $row->user_services ?? array();
                                if (!is_array($user_services)) {
                                    $uservices = (array)$user_services;
                                } else {
                                    $uservices = $user_services;
                                } //End of if else  

                                $myRights = array();
                                $userServices = array();
                                if (count($uservices)) {
                                    foreach ($uservices as $service) {
                                        $service_name = $service->service_name ?? '';
                                        $service_code = $service->service_code ?? '';
                                        $userServices[] = $service_name . ' (' . $service_code . ')';
                                    } //End of foreach()
                                } //End of if

                                $uservices = '';
                                if (isset($row->user_services) && is_array($row->user_services)) {
                                    foreach ($row->user_services as $uService) {
                                        $uservices = $uservices . '<br>' . $uService->service_name;
                                    } //End of foreach()
                                } //End of if 
                        ?>
                                <tr>
                                    <td><?= $j; ?></td>
                                    <td><?= $row->user_fullname ?></td>
                                    <td><?= $row->mobile_number ?></td>
                                    <td><?= $row->user_roles->role_name ?? '' ?></td>
                                    <td><?= implode(',<br>', $userServices) ?></td>
                                    <td><?= $row->dept_info->dept_name ?? '' ?></td>
                                    <td><?= ($row->status == 1) ? '<span class="text-success"><b>Active</b></span>' : '<span class="text-danger"><b>Inactive</b></span>' ?></td>
                                    <td style="text-align: center;">
                                        <div class="btn-group">
                                            <a href="<?= base_url('spservices/admin/users/user/' . $objId) ?>" class="btn btn-primary btn-sm">
                                                <i class="fa fa-user-edit fa-sm"></i>
                                            </a>
                                            <a href="<?= base_url('spservices/admin/users/profile/' . $objId) ?>" class="btn btn-success btn-sm">
                                                <i class="fa fa-user-tie fa-sm"></i>
                                            </a>
                                            <a href="<?= base_url('spservices/admin/users/password/' . $objId) ?>" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Change password">
                                                <i class="fas fa-undo fa-sm"></i>
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                        <?php $j++;
                            }
                        } ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No records found
                <p>
                <?php endif; ?>
        </div>
    </div><!--End of .card-->
</div>

<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" type="text/css" media='all' href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />
<script type="text/javascript">
    $(document).ready(function() {
        $('#dtbl').DataTable();
    });
</script>