<?php
$depts = $this->depts_model->get_rows(array("status"=>1));
$empCode = $this->config->item('emp_exc_code');
$conRegCodes = $this->config->item('con_reg_codes');
$services = $this->services_model->get_rows(array("status"=>1));
$districts = $this->districts_model->get_rows(array("country_id"=>1));
$users = $this->users_model->get_rows(array("status"=>1));
$serviceCodes = array();
$additionalLevelNos = array();
if($dbrow) {
    $title = "Edit existing user";
    $obj_id = $dbrow->{'_id'}->{'$id'};        
    $user_services = $dbrow->user_services??array();
    if(is_object($user_services)) {
        $service_code = $user_services->service_code??'';
    } else {
        foreach($user_services as $user_service) {
            $serviceCodes[] = $user_service->service_code??'';
        }//End of foreach()
        $service_code = $user_services[0]->service_code??'';
    }//End of if else    
    $offices_info = $dbrow->offices_info??array();
    $dept_info = $dbrow->dept_info??array();
    $dept_code = $dept_info->dept_code??'';
    
    $zone_info = $dbrow->zone_info??array();
    $zone_name = $zone_info->zone_name??'';  
    $zone_circle = $dbrow->zone_circle??'';
    $totalLevels = $this->levels_model->get_total_rows(array("level_services.service_code"=>$service_code));
    $user_roles = $dbrow->user_roles??array();
    $userRolesArr = (array)$user_roles;
    $role_code = $user_roles->role_code??'';
    $role_name = $user_roles->role_name??'';    
    $additional_roles = $dbrow->additional_roles??array();    
    if(count($additional_roles)) { 
        foreach($additional_roles as $aRole) {
            $additionalLevelNos[] = $aRole->level_no;
        }//End of foreach()
    }//End of if                            
    $location_id = $dbrow->user_location->location_id??'';
    $district_id = $dbrow->district_info->district_id??'';
    $office_id = $dbrow->office_info->office_id??'';
    $office_name = $dbrow->office_info->office_name??'';
    $user_fullname = $dbrow->user_fullname;
    $mobile_number = $dbrow->mobile_number;
    $email_id = $dbrow->email_id;
    $login_username = $dbrow->login_username;
    $user_rights = $dbrow->user_rights??array();
    $forward_levels = $dbrow->forward_levels??'';
    $forward_level_no = $forward_levels->level_no??'';
    $backward_levels = $dbrow->backward_levels??'';
    $backward_level_no = $backward_levels->level_no??'';
    $generate_certificate_levels = $dbrow->generate_certificate_levels??'';
    $generate_certificate_level_no = $generate_certificate_levels->level_no??'';    
    $status = $dbrow->status;


    $reports_url = $dbrow->reports_url;

} else {
    $title = "New user registration";
    $obj_id = null;    
    $user_services = set_value("user_services");
    $service_code = '';
    $offices_info = array();
    
    $dept_info = set_value("dept_info"); 
    $deptInfo = json_decode(htmlspecialchars_decode(html_entity_decode($dept_info)));
    $dept_code = $deptInfo->dept_code??'';
    
    $zone_info = set_value("zone_info"); 
    $zoneInfo = json_decode(htmlspecialchars_decode(html_entity_decode($zone_info)));
    $zone_name = $zoneInfo->zone_name??'';
    
    $zone_circle = set_value("zone_circle");
    
    $user_roles = array();
    $role_code = '';
    $role_name = 'Select a role';
    $location_id = '';    
    $district_id = '';
    $office_id = '';
    $office_name = '';
    $user_fullname = set_value("user_fullname");
    $mobile_number = set_value("mobile_number");
    $email_id = set_value("email_id");
    $login_username = set_value("login_username");
    $user_rights = array();
    $forward_levels = array();
    $forward_level_no = '';
    $backward_levels = array();
    $backward_level_no = '';
    $generate_certificate_levels = array();
    $generate_certificate_level_no = '';
    $status = null;
}//End of if else

$levels = $this->levels_model->get_rows(array("level_services.service_code"=>$service_code));

function isRightExist($arrayOfObjects, $right_code) {
    if(is_array($arrayOfObjects) && count($arrayOfObjects)) {
        foreach ($arrayOfObjects as $obj) {
            if ($obj->right_code == $right_code) {
                return true;
            }//End of if
        }//End of foreach()
    }//End of if
    return false;
}//End of isRightExist()

function isLevelExist($arrayOfObjects, $level_no, $service_code) {
    if(is_array($arrayOfObjects) && count($arrayOfObjects)) {
        foreach ($arrayOfObjects as $obj) {
            if (($obj->service_code == $service_code) && ($obj->level_no == $level_no)) {
                return true;
            }//End of if
        }//End of foreach()
    }//End of if
    return false;
}//End of isLevelExist() ?>
<script src="<?=base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.js")?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#user_services').multiselect({
            columns: 1,
            search:true,
            texts: {
                placeholder: 'Please select service(s)'
            },
            onControlClose: function(element){
                var userServices = element.value;
                if(userServices.length === 0) {
                    //alert("Please select a service");
                } else {
                    var user_services = $.parseJSON(userServices); //console.log(user_services);
                    getRoles(user_services.service_code);
                    getOfflineOffices(user_services.service_code);
                    if(user_services.service_code === '<?=$empCode?>') {
                        $("#emp_extra_div").show();
                    } else {
                         $("#emp_extra_div").hide();
                    }//End of if else

                    var deptInfo = $("#dept_info").val();
                    var dept_code = $.parseJSON(deptInfo).dept_code;
                    var conRegCodes = '<?=json_encode($conRegCodes)?>';
                    if(conRegCodes.includes(user_services.service_code)) {
                        $("#zones_div").show();
                        getZones(dept_code);
                    } else {
                         $("#zones_div").hide();
                    }//End of if else                    
                }//End of if else
            }//End of onControlClose()
        });//End of multiselect #user_services
        
        $(document).on("change", "#dept_info", function(){
            var deptInfo = $(this).val();
            if(deptInfo.length > 10) {
                var dept_code = $.parseJSON(deptInfo).dept_code;
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/upms/svcs/get_services')?>",
                    data: {"dept_code": dept_code},
                    dataType:'json',
                    beforeSend:function(){
                        //$("#services_dropdown").html('<select id="" name="" class="form-control"><option value="">Please wait...</option></select>');
                    },
                    success:function(res){
                        if(res.length) {
                            $("#user_services").empty();
                            $.each(res, function (index, item) {
                                $("#user_services").append($('<option></option>').val(item.service_obj).html(item.service_name));
                            });
                            $('#user_services').multiselect('reload');
                        } else {
                            alert("No records found");
                        }//End of if else
                    }
                });
            } else {
                //alert("Please select a valid department");
            }//End of if else
        });//End of onChange #dept_info
        
        $('#additional_roles').multiselect({
            columns: 1,
            search:true,
            texts: {
                placeholder: 'Please select additional role(s)'
            }
        });
        $(document).on("change", "#user_roles", function(){
            var selectedRole = $(this).val();
            var additionalRoles = $('#additional_roles');
            additionalRoles.empty();
            $("#user_roles option").each(function(){
                var val = $(this).val();
                var txt = $(this).text();
                if(val.length && val !== selectedRole)
                additionalRoles.append($('<option></option>').val(val).html(txt));
            });
            additionalRoles.multiselect('reload');
        });//End of onChange #user_roles
        
        $(document).on("change", "#district_info", function(){
            var districtInfo = $(this).val(); //console.log(districtInfo);
            if(districtInfo.length > 10) {
                var district_id = $.parseJSON(districtInfo).district_id;
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/upms/users/get_empoffices')?>",
                    data: {"district_id": district_id},
                    beforeSend:function(){
                        $("#district_info_div").html('<select id="office_info" name="office_info" class="form-control"><option value="">Please wait...</option></select>');
                    },
                    success:function(res){
                        $("#district_info_div").html(res);
                    }
                });
            } else {
                alert("Please select a district");
                $("#district_info").focus();
            }//End of if else
        });//End of onChange #district_info
        
        $(document).on("change", "#zone_info", function(){
            var zoneInfo = $(this).val();
            if(zoneInfo.length > 10) {
                var zone_code = $.parseJSON(zoneInfo).zone_code;
                $("#zonecircles_div").show();           
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/upms/users/get_zonecircles')?>",
                    data: {"zone_code": zone_code},
                    beforeSend:function(){
                        $("#zonecircles_dropdown").html('<select id="zone_info" name="zone_info" class="form-control"><option value="">Please wait...</option></select>');
                    },
                    success:function(res){
                        $("#zonecircles_dropdown").html(res);
                    }
                });
            } else {
                $("#zonecircles_div").hide(); 
            }//End of if else
        });//End of onChange #zone_info
                              
        var getOfflineOffices = function(service_code) {
            if(service_code.length) { 
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/upms/offices/get_offices')?>",
                    data: {"service_code":service_code},
                    beforeSend:function(){
                        $("#offlineoffices_dropdown").html("Loading...");
                    },
                    success:function(res){
                        if(res.length) {
                            $("#offlineoffices_div").show();                            
                        } else {
                            $("#offlineoffices_div").hide();
                        }//End of if else
                        $("#offlineoffices_dropdown").html(res);
                    }
                });
            } else {
                $("#rights_div").html('');
            }//End of if else
        }; //End of getOfflineOffices()        
        
        var getRoles = function(service_code) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/upms/levels/get_roles')?>",
                data: {"service_code": service_code},
                beforeSend:function(){
                    $("#roles_div").html("Loading...");
                },
                success:function(res){
                    $("#roles_div").html(res);
                }
            });
        }; //End of getRoles()
        
        var getZones = function(dept_code) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/upms/users/get_zones')?>",
                data: {"dept_code": dept_code},
                beforeSend:function(){
                    $("#zones_dropdown").html('<select id="zone_info" name="zone_info" class="form-control"><option value="">Please wait...</option></select>');
                },
                success:function(res){
                    $("#zones_dropdown").html(res);
                }
            });
        }; //End of getZones()
    });
</script>

<link rel="stylesheet" href="<?=base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.css")?>" type="text/css">
<style type="text/css"></style>
<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <form method="POST" action="<?=base_url('spservices/upms/users/submit')?>">
        <input name="obj_id" value="<?=$obj_id?>" type="hidden" />
        <div class="card shadow-sm">
            <div class="card-header bg-dark">
                <span class="h5 text-white"><?=$title?></span>
            </div>
            <div class="card-body">
                <?php if ($this->session->flashdata('error') != null) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                <?php }
                 if ($this->session->flashdata('success') != null) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                <?php } ?>
                      
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Department<span class="text-danger">*</span> </label>
                        <select name="dept_info" id="dept_info" class="form-control">
                            <option value="">Select a department</option>
                            <?php if($depts) {
                                foreach($depts as $dept) {
                                    $isDeptSel = ($dept->dept_code === $dept_code)?'selected':'';
                                    $deptObj = json_encode(array("dept_code"=>$dept->dept_code, "dept_name" => $dept->dept_name));
                                    echo "<option value='{$deptObj}' {$isDeptSel}>{$dept->dept_name}</option>";
                                }//End of foreach()
                            }//End of if ?>
                        </select>
                        <?= form_error("dept_info") ?>
                    </div>  
                </div>
                
                <div class="row"> 
                    <div class="col-md-6 form-group">
                        <label>Service(s)<span class="text-danger">*</span> </label>
                        <div id="services_dropdown">
                            <select name="user_services[]" id="user_services" class="form-control" multiple='multiple'>
                                <?php if($services) { 
                                    foreach($services as $service) {
                                        $isSel = in_array($service->service_code, $serviceCodes)?'selected':'';//($service->service_code===$service_code)?'selected':'';
                                        $serviceObj = json_encode(array("service_code"=>$service->service_code, "service_name" => $service->service_name)); ?>
                                        <option value='<?=$serviceObj?>' <?=$isSel?>><?=$service->service_name?></option>
                                    <?php }//End of foreach()
                                }//End of if ?>
                            </select>
                        </div>
                        <?= form_error("user_services") ?>
                    </div>                   
                    <div class="col-md-6 form-group">
                        <label>Role<span class="text-danger">*</span> </label>
                        <div id="roles_div">
                            <select name="user_roles" id="user_roles" class="form-control">
                                <?php if($levels) {
                                    foreach($levels as $level) {
                                        $userRoles = (array)$level->level_roles;
                                        $userRoles['level_name'] = $level->level_name;
                                        $userRoles['level_no'] = $level->level_no;
                                        $lbl = $level->level_roles->role_name;
                                        $isSelected = in_array($level->level_no, $userRolesArr)?'selected':'';
                                        $userRolesEncoded = json_encode($userRoles);
                                        echo "<option value='{$userRolesEncoded}' {$isSelected}>{$lbl}</option>";
                                    }//End of foreach()
                                }//End of if ?>
                            </select>
                        </div>
                        <?= form_error("user_roles") ?>
                    </div>
                </div>                
                
                <div class="row mt-2">
                    <div class="col-md-6 form-group">
                        <label>Name<span class="text-danger">*</span></label>
                        <input class="form-control" name="user_fullname" value="<?=$user_fullname?>" maxlength="100" type="text" />
                        <?= form_error("user_fullname") ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Mobile number</label>
                        <input class="form-control" name="mobile_number" value="<?=$mobile_number?>" maxlength="10" type="text" />
                        <?= form_error("mobile_number") ?>
                    </div>
                </div>
                
                <!--For Offline Offices only-->
                <div id="offlineoffices_div" class="row mt-2" style="display:<?=count($offices_info)?'':'none'?>" > 
                    <div class="col-md-12 form-group">
                        <label>Office<span class="text-danger">*</span> </label>
                        <div id="offlineoffices_dropdown">
                            <select id="offices_info" name="offices_info[]" class="form-control" multiple='multiple'>
                                <?php if(count($offices_info)){
                                    foreach($offices_info as $office_info) {
                                        $serviceObj = json_encode(array("office_code"=>$office_info->office_code, "office_name" => $office_info->office_name));
                                        echo "<option value='{$serviceObj}' selected>{$office_info->office_name}</option>";
                                    }//End of foreach()
                                } else {
                                    echo '<option value="">Please Select</option>';
                                } //End of if else ?>
                            </select>
                            <script>$('#offices_info').multiselect();</script>
                        </div>
                        <?= form_error("offices_info") ?>
                    </div>
                </div>
                
                <!--For Registration of contractors only-->                
                <div id="zones_div" class="row mt-2" style="display:<?=strlen($zone_name)?'':'none'?>" > 
                    <div class="col-md-12 form-group">
                        <label>Zone<span class="text-danger">*</span> </label>
                        <div id="zones_dropdown">
                            <select id="zone_info" name="zone_info" class="form-control">
                                <option value='<?=json_encode($zone_info)?>'><?=strlen($zone_name)?$zone_name:'Select'?></option>
                            </select>
                        </div>
                        <?= form_error("zone_info") ?>
                    </div>
                </div>
                
                <div id="zonecircles_div" class="row mt-2" style="display:<?=strlen($zone_circle)?'':'none'?>" > 
                    <div class="col-md-12 form-group">
                        <label>Circle<span class="text-danger">*</span> </label>
                        <div id="zonecircles_dropdown">
                            <select id="zone_circle" name="zone_circle" class="form-control">
                                <option value="<?=$zone_circle?>"><?=strlen($zone_circle)?$zone_circle:'Select a circle'?></option>
                            </select>
                        </div>
                        <?= form_error("zone_circle") ?>
                    </div>
                </div>
                
                <!--For Employment Exchange only-->
                <div id="emp_extra_div" class="row mt-2" style="display:<?=in_array($empCode, $serviceCodes)?'':'none'?>"> 
                    <div class="col-md-6 form-group">
                        <label>District<span class="text-danger">*</span> </label>
                        <select id="district_info" name="district_info" class="form-control">
                            <option value="">Please Select</option>
                            
                        </select>
                        <?= form_error("district_info") ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Office<span class="text-danger">*</span></label>
                        <div id="district_info_div">
                            <select id="office_info" name="office_info" class="form-control">
                                <option value="<?=$office_id?>"><?=strlen($office_name)?$office_name:'Please Select'?></option>
                            </select>
                        </div>
                        <?= form_error("office_info") ?>
                    </div>
                </div>
                
                <div class="row mt-2">  
                    <div class="col-md-6 form-group">
                        <label>Report URL</label>
                        <input class="form-control" name="reports_url" value="<?=$reports_url?>" maxlength="100" type="text" />
                        <?= form_error("reports_url") ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Email id</label>
                        <input class="form-control" name="email_id" value="<?=$email_id?>" maxlength="100" type="text" />
                        <?= form_error("email_id") ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Login username<span class="text-danger">*</span></label>
                        <input class="form-control" name="login_username" value="<?=$login_username?>" maxlength="100" type="text" />
                        <?= form_error("login_username") ?>
                    </div> 
                </div>
                
                <div class="row mt-2"> 
                    <div class="col-md-6 form-group">
                        <label>Choose login password</label>
                        <input class="form-control" name="login_password" value="" maxlength="100" type="password" />
                        <?= form_error("login_password") ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Confirm login password</label>
                        <input class="form-control" name="login_password_conf" value="" maxlength="100" type="password" />
                        <?= form_error("login_password_conf") ?>
                    </div>
                </div>
                
                <div class="row mt-2"> 
                    <div class="col-md-12 form-group">
                        <label>Additional roles (If any)</label>
                        <select id="additional_roles" name="additional_roles[]" class="form-control" multiple='multiple'>
                            <?php
                            if($levels) {
                                foreach($levels as $level) {
                                    $userRoles = (array)$level->level_roles;
                                    $userRoles['level_name'] = $level->level_name;
                                    $userRoles['level_no'] = $level->level_no;
                                    $lbl = $level->level_roles->role_name;                                    
                                    $userRolesEncoded = json_encode($userRoles);
                                    if(!in_array($level->level_no, $userRolesArr)) {
                                        $isSelected = in_array($level->level_no, $additionalLevelNos)?'selected':'';
                                        echo "<option value='{$userRolesEncoded}' {$isSelected}>{$lbl}</option>";
                                    }//End of if                                    
                                }//End of foreach()
                            }//End of if ?>
                        </select>
                        <?= form_error("additional_roles") ?>
                    </div>
                </div>
            </div>            
                
                
            <div class="card-footer text-center">
                <button class="btn btn-danger" type="reset">
                    <i class="fa fa-refresh"></i> RESET
                </button>
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-angle-double-right"></i> SUBMIT
                </button>
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </form>
    
    <?php if($this->session->userdata('upms_user_type') != 3){ ?>
    <div class="card shadow-sm mt-2">
        <div class="card-header">
            <span class="h5 text-dark">Registered users</span>
        </div>
        <div class="card-body">                
            <?php if ($users): ?>
                <table id="dtbl" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Service</th>
                            <th>Role</th>
                            <th>Allocated rights</th>
                            <th style="width:100px; text-align: center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="">
                        <?php
                        foreach ($users as $key => $row) {
                            $objId = $row->_id->{'$id'}; 
                            $user_services = $row->user_services??array();
                            if(!is_array($user_services)) {
                                $uservices = (array)$user_services;
                            } else {
                                $uservices = $user_services;
                            }//End of if else  

                            $myRights = array();
                            $userServices = array();
                            if(count($uservices)) { 
                                foreach($uservices as $service) {
                                    $service_name = $service->service_name??'';
                                    $service_code = $service->service_code??'';
                                    $userServices[] = $service_name.' ('.$service_code.')';

                                    $lvlNo = $row->user_levels->level_no??0;
                                    $levelRow = $this->levels_model->get_row(array("level_services.service_code" => $service_code, "level_no" =>$lvlNo ));
                                    $level_rights = $levelRow->level_rights??array();
                                    if(count($level_rights)) {
                                        foreach ($level_rights as $levelRight) {
                                            if(!in_array($levelRight->right_code, $myRights, true)){
                                                array_push($myRights, $levelRight->right_code);
                                            }//End of if
                                        }//End of foreach()
                                    }//End of if
                                }//End of foreach()
                            }//End of if
        
                            $uservices = '';
                            if(isset($row->user_services) && is_array($row->user_services)) {
                                foreach($row->user_services as $uService) {
                                    $uservices = $uservices.'<br>'.$uService->service_name;
                                }//End of foreach()
                            }//End of if ?>
                            <tr>
                                <td><?=$row->user_fullname.'('.$row->login_username?>)</td>
                                <td><?=implode(',<br>', $userServices)?></td>
                                <td><?=$row->user_roles->role_name??''?></td>
                                <td><?=implode(',<br>', $myRights)?></td>
                                <td style="text-align:center">
                                    <a href="<?=base_url('spservices/upms/users/index/'.$objId)?>" class="btn btn-warning btn-sm">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <a href="<?=base_url('spservices/upms/users/changepass/'.$row->login_username)?>" class="btn btn-info btn-sm">
                                        <i class="fa fa-lock"></i>
                                    </a>
                                    <a href="<?=base_url('spservices/upms/users/profile/'.$objId)?>" class="btn btn-success btn-sm">
                                        <i class="fa fa-user"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No records found<p>
            <?php endif; ?>
        </div>
    </div><!--End of .card-->
    <?php } ?>
</div>

<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" type="text/css" media='all' href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" media='all' href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />
<script type="text/javascript">
$(document).ready(function () {
    $('#dtbl').DataTable();
});
</script>