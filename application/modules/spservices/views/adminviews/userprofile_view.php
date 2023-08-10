<?php
$empCode = $this->config->item('emp_exc_code');
$services = $this->services_model->get_rows(array("status" => 1));
$districts = $this->districts_model->get_rows(array("country_id" => 1));
$users = $this->users_model->get_rows(array("status" => 1));
$serviceCodes = array();

$objId = $dbrow->{'_id'}->{'$id'};
$user_services = $dbrow->user_services ?? array();
if (!is_array($user_services)) {
    $uservices = (array)$user_services;
} else {
    $uservices = $user_services;
} //End of if else
$offices_info = $dbrow->offices_info ?? array();
$office_id = $dbrow->office_info->office_id ?? '';
$office_name = $dbrow->office_info->office_name ?? '';
$dept_info = $dbrow->dept_info ?? array();
$dept_name = $dept_info->dept_name ?? '';

$zone_info = $dbrow->zone_info ?? array();
$zone_name = $zone_info->zone_name ?? '';
$zone_circle = $dbrow->zone_circle ?? '';
$user_roles = $dbrow->user_roles ?? array();
$role_code = $user_roles->role_code ?? '';
$role_name = $user_roles->role_name ?? '';
$level_no = $dbrow->user_levels->level_no ?? 'NA';
$level_name = $dbrow->user_levels->level_name ?? 'NA';
$district_name = $dbrow->district_info->district_name ?? '';
$user_fullname = $dbrow->user_fullname;
$mobile_number = $dbrow->mobile_number;
$email_id = $dbrow->email_id;
$login_username = $dbrow->login_username;


if (count($uservices)) {
    foreach ($uservices as $service) {
        $service_name = $service->service_name ?? '';
        $service_code = $service->service_code ?? '';
        $userServices[] = $service_name . ' (' . $service_code . ')';
    } //End of foreach()
} //End of if

function getLevelDetails($forward_levels, $service_name)
{
    $fLbls = array();
    foreach ($forward_levels as $flbl) {
        $fLbls[] = "<strong>" . $flbl->level_name . "</strong> of " . $service_name;
    } //End of foreach()
    return implode(',<br>', $fLbls);
} //End of getLevelDetails() 
?>
<style type="text/css">
    label {
        font-weight: bold;
        font-style: italic;
        text-decoration: underline;
        display: block;
        margin-bottom: 1px;
    }
</style>
<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php } //End of if 
    ?>
    <div class="card shadow-sm">
        <div class="card-header bg-secondary">
            <span class="h5 text-white">User profile</span>
        </div>
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-md-12">
                    <h6>User is <?= ($dbrow->status == 1) ? '<span class="text-success"><b>Active</b></span>' : '<span class="text-danger"><b>Inactive</b></span>' ?></h6>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Name</label>
                    <?= $user_fullname ?>
                </div>
                <div class="col-md-6">
                    <label>Login username</label>
                    <?= $login_username ?>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-6">
                    <label>Mobile number</label>
                    <?= $mobile_number ?>
                </div>
                <div class="col-md-6">
                    <label>Email id</label>
                    <?= $email_id ?>
                </div>
            </div>
            <!--For PHED, PWDB or WRD only-->
            <div class="row mt-2" style="display:<?= strlen($dept_name) ? '' : 'none' ?>">
                <div class="col-md-6">
                    <label>Organization</label>
                    <?= $dept_name ?>
                </div>
                <div class="col-md-6">
                    <label>Circle &amp; Zone</label>
                    <?= $zone_circle . ' of ' . $zone_name ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label>Service</label>
                    <?= implode(',<br>', $userServices) ?>
                </div>
                <div class="col-md-6">
                    <label>Level number, Level name &amp; Role </label>
                    <?= $level_no . '. ' . $level_name . ' &amp; ' . $role_name ?>
                </div>
            </div>
        </div><!--End of .card-body-->
        <div class="card-footer text-center">
            <a href="<?= base_url('spservices/admin/users/user/' . $objId) ?>" class="btn btn-primary btn-sm">
                <i class="fa fa-edit fa-sm"></i> Edit
            </a>
            <?php if ($dbrow->status == 1) {
                $action = 'Deactive'; ?>
                <button class="btn btn-danger btn-sm status_btn" type="button" data-id="<?= $objId ?>">
                    <i class="fa fa-user-slash fa-sm"></i> Deactive user
                </button>
            <?php } else {
                $action = 'Active'; ?>
                <button class="btn btn-success btn-sm" type="button" data-id="<?= $objId ?>" data-toggle="modal" data-target="#statusModal">
                    <i class="fa fa-user-check fa-sm"></i> Active user
                </button>
            <?php } ?>

            <a href="<?= base_url('spservices/admin/users/password/' . $objId) ?>" class="btn btn-warning btn-sm" data-toggle="tooltip" data-placement="top" title="Change password">
                <i class="fas fa-undo fa-sm"></i> Reset Password
            </a>
        </div>
    </div><!--End of .card-->
</div>

<div class="modal fade" id="statusModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-info py-2">
                <h4 class="modal-title">Change Status</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <p style="font-size:15px">Do you want to <?= $action ?> the user ?</p>
                <input type="hidden" value="<?= $objId ?>" name="objId" id="objId">
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="update_sts">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script>
    $('.status_btn').on('click', function() {
        var objId = $(this).data('id');
        $.ajax({
            url: '<?= base_url('spservices/admin/users/check_pending_appl') ?>',
            type: 'POST',
            cache: false,
            data: {
                "objId": objId
            },
            dataType: "JSON",
            success: function(response) {
                if (response.status) {
                    $('#statusModal').modal('show');
                } else {
                    $('#statusModal').modal('hide');
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.msg,
                    })
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#statusModal').modal('hide');
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong.',
                })
            }
        });
    })
    $('#update_sts').on('click', function() {
        $('#statusModal').modal('hide');
        var objId = $('#objId').val();
        $.ajax({
            url: '<?= base_url('spservices/admin/users/update_user') ?>',
            type: 'POST',
            cache: false,
            data: {
                "objId": objId
            },
            dataType: "JSON",
            success: function(response) {
                if (response.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.msg,
                        allowOutsideClick: false,
                    }).then(function(isConfirm) {
                        window.location.href = '<?= base_url("spservices/admin/users") ?>';
                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.msg,
                    })
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong.',
                })
            }
        });
    })
</script>