<?php
$empCode = $this->config->item('emp_exc_code');
$services = $this->services_model->get_rows(array("status" => 1));
$districts = $this->districts_model->get_rows(array("country_id" => 1));
$empDistricts = $this->district_model->get_rows(); //For Employment Exchange only
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
$additional_roles = $dbrow->additional_roles??array();
$district_name = $dbrow->district_info->district_name ?? '';
$user_fullname = $dbrow->user_fullname;
$mobile_number = $dbrow->mobile_number;
$email_id = $dbrow->email_id;
$login_username = $dbrow->login_username;

$myRights = array();
$userServices = array();
$forwardLevels = array();
$backwardLevels = array();
$generateCertificateLevels = array();
if (count($uservices)) {
    foreach ($uservices as $service) {
        $service_name = $service->service_name ?? '';
        $service_code = $service->service_code ?? '';
        $userServices[] = $service_name . ' (' . $service_code . ')';

        $levelRow = $this->levels_model->get_row(array("level_services.service_code" => $service_code, "level_no" => $level_no));
        $level_rights = $levelRow->level_rights ?? array();
        if (count($level_rights)) {
            foreach ($level_rights as $levelRight) {
                if (!in_array($levelRight->right_code, $myRights, true)) {
                    array_push($myRights, $levelRight->right_code);
                } //End of if
            } //End of foreach()
        } //End of if                

        $forward_levels = $levelRow->forward_levels ?? array();
        if (count($forward_levels)) {
            $forwardLevels[] = getLevelDetails($forward_levels, $service_name);
        } //End of if

        $backward_levels = $levelRow->backward_levels ?? array();
        if (count($backward_levels)) {
            $backwardLevels[] = getLevelDetails($backward_levels, $service_name);
        } //End of if 

        $generate_certificate_levels = $levelRow->generate_certificate_levels ?? array();
        if (count($generate_certificate_levels)) {
            $generateCertificateLevels[] = getLevelDetails($generate_certificate_levels, $service_name);
        } //End of if       
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
        <div class="card-header bg-dark">
            <span class="h5 text-white">User profile</span>
        </div>
        <div class="card-body">
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

            <!--For Offline Offices only-->
            <div class="row mt-2" style="display:<?= count($offices_info) ? '' : 'none' ?>">
                <div class="col-md-12">
                    <label>Office </label>
                    <?php if (count($offices_info)) {
                        foreach ($offices_info as $office_info) {
                            echo $office_info->office_name . ',<br>';
                        } //End of foreach()
                    } //End of if 
                    ?>
                </div>
            </div>

            <!--For Employment Exchange only-->
            <div class="row mt-2" style="display:<?= strlen($district_name) ? '' : 'none' ?>">
                <div class="col-md-6">
                    <label>District</label>
                    <?= $district_name ?>
                </div>
                <div class="col-md-6">
                    <label>Office</label>
                    <?= $office_name ?>
                </div>
            </div>

            <!--For PHED, PWDB or WRD only-->
            <div class="row mt-2" style="display:<?= strlen($dept_name) ? '' : 'none' ?>">
                <div class="col-md-6">
                    <label>Department</label>
                    <?= $dept_name ?>
                </div>
                <div class="col-md-6">
                    <label>Circle &amp; Zone</label>
                    <?= $zone_circle . ' of ' . $zone_name ?>
                </div>
            </div>
            
            <!--For Additional roles only-->
            <div class="row mt-2" style="display:<?=count($additional_roles)?'':'none'?>">
                <div class="col-md-12">
                    <label>Additional Roles (Level number, Level name &amp; Role)</label>
                    <?php if(count($additional_roles)) { 
                        foreach($additional_roles as $aRole) {
                            echo $aRole->level_no . '. ' . $aRole->level_name . ' &amp; ' . $aRole->role_name,'<br>';
                        }//End of foreach()
                    }//End of if ?>
                </div>
            </div>

            <div class="row">
                <div id="rights_div" class="col-md-12 table-responsive mt-2">
                    <?php if (count($myRights)) { ?>
                        <div class="accordion" id="accordionTasks">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTasks">
                                    <button class="btn btn-primary w-100" type="button" data-toggle="collapse" data-target="#rights_table" aria-expanded="true" aria-controls="rights_table" style="font-size:18px; text-transform: uppercase; font-weight: bold">
                                        <span style="float:left">User Rights/Tasks allocations</span>
                                        <span style="float:right"><i class="fa fa-chevron-circle-down"></i></span>
                                    </button>
                                </h2><!--End of .accordion-header -->
                                <div id="rights_table" class="accordion-collapse" aria-labelledby="headingTasks" data-parent="#accordionTasks">
                                    <div class="accordion-body p-1">
                                        <table class="table table-bordered mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Right code</th>
                                                    <th>Right details</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($myRights as $rightCode) { ?>
                                                    <tr>
                                                        <td><?= $rightCode ?></td>
                                                        <td>
                                                            <?php if ($rightCode === 'FORWARD') {
                                                                echo implode(',<br><br>', $forwardLevels);
                                                            } elseif ($rightCode === 'BACKWARD') {
                                                                echo implode(',<br><br>', $backwardLevels);
                                                            } elseif ($rightCode === 'GENERATE_CERTIFICATE') {
                                                                echo implode(',<br><br>', $generateCertificateLevels);
                                                            } else {
                                                                echo $rightCode;
                                                            } //End of if else 
                                                            ?>
                                                        </td>
                                                    </tr>
                                                <?php } //End of foreach() 
                                                ?>
                                            </tbody>
                                        </table>
                                    </div><!--End of .accordion-body -->
                                </div><!--End of .accordion-collapse -->
                            </div><!--End of .accordion-item -->
                        </div><!--End of .accordion -->
                    <?php } //End of if 
                    ?>
                </div><!--End of #rights_div-->
            </div>
        </div><!--End of .card-body-->
        <div class="card-footer text-center">
            <?php if ($this->session->userdata('upms_user_type') == 3) { ?>
                <a href="<?= base_url('spservices/upms/edituser/index/' . $objId) ?>" class="btn btn-warning btn-sm">
                    <i class="fa fa-pen"></i> Edit profile
                </a>
            <?php } else { ?>
                <a href="<?= base_url('spservices/upms/users/index/' . $objId) ?>" class="btn btn-warning btn-sm">
                    <i class="fa fa-pen"></i> Edit profile
                </a>
            <?php  } ?>
            <a href="<?= base_url('spservices/upms/users/changepass/' . $login_username) ?>" class="btn btn-info btn-sm">
                <i class="fa fa-lock"></i> Change password
            </a>
        </div>
    </div><!--End of .card-->
</div>