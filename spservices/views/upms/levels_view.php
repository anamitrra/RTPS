<?php
$depts = $this->depts_model->get_rows(array("status"=>1));
$services = $this->services_model->get_rows(array("status"=>1));
$roles = $this->roles_model->get_rows(array("status"=>1));
$rights = $this->rights_model->get_rows(array("status"=>1));
$levels = $this->levels_model->get_rows(array("status"=>1));
if(count((array)$dbrow)) {
    $title = "Edit existing form processing level";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $dept_info = $dbrow->dept_info??array();
    $dept_code = $dept_info->dept_code??'';
    $service_code = $dbrow->level_services->service_code;
    $level_no = $dbrow->level_no;
    $role_code = $dbrow->level_roles->role_code;
    $role_name = $dbrow->level_roles->role_name;
    $level_name = $dbrow->level_name;
    $level_description = $dbrow->level_description;
    $levelRights = $dbrow->level_rights;
    $backwardLevels = $dbrow->backward_levels;
    $forwardLevels = $dbrow->forward_levels;
    $generateCertificateLevels = $dbrow->generate_certificate_levels;
    $query_payment_amount = $dbrow->query_payment_amount??'';
    $status = $dbrow->status;
} else {
    $title = "Form processing level registration";
    $obj_id = null;
    $service_code = '';
    $dept_info = set_value("dept_info"); 
    $deptInfo = json_decode(htmlspecialchars_decode(html_entity_decode($dept_info)));
    $dept_code = $deptInfo->dept_code??'';
    $level_no = set_value("level_no");
    $role_code = '';
    $role_name = 'Select a role';
    $level_name = set_value("level_name");
    $level_description = set_value("level_description");
    $levelRights = array();
    $backwardLevels = array();
    $forwardLevels = array();
    $generateCertificateLevels = array();
    $status = null;
}//End of if else

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

<link rel="stylesheet" href="<?=base_url("assets/plugins/jquery-multi-select/src/example-styles.css")?>" type="text/css">
<style type="text/css">
    .multi-select-button {
        font-size: 1em;
        padding: 0.4em 0.6em;
    }
    .multi-select-menuitem {
        font-size: 1em;
    }
</style>
<script src="<?=base_url("assets/plugins/jquery-multi-select/src/jquery.multi-select.min.js")?>"></script>
<script type="text/javascript">
    $(document).ready(function () {  
        $(document).on("change", "#level_services", function(){
            //getRights();
        });
        $(document).on("change", "#level_no", function(){
            getRights();
        });
        $(document).on("change", "#level_roles", function(){
            //getRights();
        });
        
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
                            $("#level_services").empty();
                            $("#level_services").append($('<option></option>').val('').html('Please select a service'));
                            $.each(res, function (index, item) {
                                $("#level_services").append($('<option></option>').val(item.service_obj).html(item.service_name));
                            });
                        } else {
                            alert("No records found");
                        }//End of if else
                    }
                });
            } else {
                alert("Please select a valid department");
            }//End of if else
        });//End of onChange #dept_info
        
        var getRights = function() {
            var levelServices = $("#level_services").val();
            if(levelServices.length > 10) {
                var service_code = $.parseJSON(levelServices).service_code;
            } else {
                var service_code = '';
            }//End of if else
            
            var level_no = parseInt($("#level_no").val());
            
            let levelRoles=$("#level_roles").val();            
            if(levelRoles.length > 10){
                 var role_code = $.parseJSON(levelRoles).role_code;
            } else {
                var role_code = '';
            }//End of if else
            //alert(service_code+", "+level_no+", "+role_code);
            if(service_code.length) {
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/upms/levels/get_rights')?>",
                    data: {
                        "service_code":service_code,
                        "level_no":level_no,
                        "role_code":role_code
                    },
                    beforeSend:function(){
                        $("#levelrights_div").html("Loading...");
                    },
                    success:function(res){
                        $("#levelrights_div").html(res);
                    }
                });
            } else {
                $("#levelrights_div").html('');
            }//End of if else
        };
        
        $(document).on("change", ".level_rights", function(){
            var levelRights = $(this).val();
            if(levelRights.length > 10) {
                var right_code = $.parseJSON(levelRights).right_code;
                if($(this).is(":checked")) {
                    $("#"+right_code+"-SPAN").show();
                } else {
                    $("#"+right_code+"-SPAN").hide();
                }//End of if else                
            }//End of if
        });
    });
</script>
<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <form method="POST" action="<?=base_url('spservices/upms/levels/submit')?>">
        <input name="obj_id" value="<?=$obj_id?>" type="hidden" />
        <div class="card shadow-sm">
            <div class="card-header bg-dark">
                <span class="h5 text-white"><?=$title?></span>
            </div>
            <div class="card-body"> 
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
                        <label>Service<span class="text-danger">*</span> </label>
                        <select name="level_services" id="level_services" class="form-control">
                            <option value="">Please Select</option>
                            <?php if($services) { 
                                foreach($services as $service) {
                                    $serviceObj = json_encode(array("service_code"=>$service->service_code, "service_name" => $service->service_name)); ?>
                                    <option value='<?=$serviceObj?>' <?=($service->service_code===$service_code)?'selected':''?>><?=$service->service_name?></option>
                                <?php }//End of foreach()
                            }//End of if ?>
                        </select>
                        <?= form_error("service_id") ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Task level no. (1 means initial/form landing level)<span class="text-danger">*</span> </label>
                        <select name="level_no" id="level_no" class="form-control">
                            <option value="0">Please Select</option>
                            <?php for($i=1; $i <= 10; $i++) { ?>
                                <option value="<?=$i?>" <?=((int)$level_no === $i)?'selected':''?>><?=sprintf('%02d', $i)?></option>
                            <?php }//End of for() ?>
                        </select>
                        <?= form_error("level_no") ?>
                    </div>
                </div>
                
                <div class="row mt-2"> 
                    <div class="col-md-6 form-group">
                        <label>Role<span class="text-danger">*</span> </label>
                        <select name="level_roles" id="level_roles" class="form-control">
                            <option value="">Please Select</option>
                            <?php if($roles) { 
                                foreach($roles as $role) {
                                    $roleObj = json_encode(array("role_code"=>$role->role_code, "role_name" => $role->role_name)); ?>
                                    <option value='<?=$roleObj?>' <?=($role->role_code===$role_code)?'selected':''?>><?=$role->role_name?></option>
                                <?php }//End of foreach()
                            }//End of if ?>
                        </select>
                        <?= form_error("level_roles") ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Task level name</label>
                        <input class="form-control" name="level_name" value="<?=$level_name?>" maxlength="100" type="text" />
                        <?= form_error("level_name") ?>
                    </div>
                </div>
                
                <div class="row mt-2">  
                    <div class="col-md-12 form-group">
                        <label>Task level description</label>
                        <input class="form-control" name="level_description" value="<?=$level_description?>" maxlength="100" type="text" />
                        <?= form_error("level_description") ?>
                    </div>
                </div>     
                
                <div class="row">
                    <div id="levelrights_div" class="col-md-12 table-responsive mt-2">  
                        <?php if($rights && strlen($service_code)) { ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr style="text-transform: uppercase">
                                        <th colspan="2">Tasks/Rights allocation:</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rights as $urights) {
                                        $right_code = $urights->right_code??'';
                                        $right_name = $urights->right_name;
                                        $rightObj = json_encode(array("right_code"=>$right_code, "right_name" => $right_name));
                                        $isChecked = isRightExist($levelRights, $right_code)?'checked="checked"':''; ?>
                                        <tr>
                                            <td style="font-weight:bold; width: 200px">
                                                <input name="level_rights[]" value='<?=$rightObj?>' id="<?=$right_code?>" <?=$isChecked?> type="checkbox" />
                                                <label for="<?=$right_code?>"><?=$right_name?></label>                                                
                                            </td>
                                            <td>
                                                <?php if(in_array($right_code, ['FORWARD', 'BACKWARD', 'GENERATE_CERTIFICATE'])) {
                                                    $filterLevels = array();
                                                    if($right_code === 'FORWARD') {
                                                        $levelsArray = $forwardLevels;
                                                        $lbl_title = "Select level(s) where application can be forward";
                                                        $filterLevels = array(
                                                            "level_services.service_code" => $service_code,
                                                            "level_no" => array('$gte' => $level_no),
                                                            "status" =>1 
                                                        );
                                                    } elseif($right_code === 'BACKWARD') {
                                                        $levelsArray = $backwardLevels;
                                                        $lbl_title = "Select level(s) where application can be send back";
                                                        $filterLevels = array(
                                                            "level_services.service_code" => $service_code,
                                                            "level_no" => array('$lte' => $level_no),
                                                            "status" =>1 
                                                        );
                                                    } elseif($right_code === 'GENERATE_CERTIFICATE') {
                                                        $levelsArray = $generateCertificateLevels;
                                                        $lbl_title = "Select level(s) whose processes can allows to generate certificate";
                                                        $filterLevels = array(
                                                            "level_services.service_code" => $service_code,
                                                            "status" =>1 
                                                        );
                                                    } else {
                                                        $levelsArray = array();
                                                        $lbl_title = "";
                                                    }//End of if else
                                                    $serviceLevels = $this->levels_model->get_rows($filterLevels); ?>
                                                    <label for="<?=strtolower($right_code)?>_levels"><?=$lbl_title?></label><br>
                                                    <select name="<?=strtolower($right_code)?>_levels[]" id="<?=strtolower($right_code)?>_levels" class="form-control" multiple>
                                                        <option value="" disabled="">Select level(s) </option>
                                                        <?php if($serviceLevels) { 
                                                            foreach($serviceLevels as $level) {
                                                                $levelsObj = json_encode(array("level_no" => $level->level_no, "level_name" => $level->level_name, "service_code" => $service_code));
                                                                $isSelected = isLevelExist($levelsArray, $level->level_no, $service_code)?'selected':'';
                                                                echo "<option value='".$levelsObj."' ".$isSelected.">".$level->level_name."</option>";                 
                                                            }//End of foreach()
                                                        }//End of if ?>
                                                    </select>
                                                    <script>$('#<?=strtolower($right_code)?>_levels').multiSelect();</script>
                                                <?php } elseif($right_code === 'QUERY_PAYMENT') {
                                                    echo "<input name='query_payment_amount' value='{$query_payment_amount}' placeholder='Enter a default payment amount' class='form-control' type='number' />";
                                                }//End of if else ?>
                                            </td>
                                        </tr>
                                    <?php }//End of foreach() ?>
                                </tbody>
                            </table>
                        <?php }//End of if ?>
                    </div><!-- End of .col-md-12 -->
                </div><!-- End of .row -->
                
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
    
    
    <div class="card shadow-sm mt-2">
        <div class="card-header">
            <span class="h5 text-dark">Registered levels</span>
        </div>
        <div class="card-body">                
            <?php if ($levels): ?>
                <table id="dtbl" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Task level no.</th>
                            <th>Service name</th>
                            <th>Task level name</th>
                            <th>Role(s)</th>
                            <th>Allocated rights</th>
                            <th>Task level description</th>
                            <th style="width: 60px; text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="">
                        <?php
                        foreach ($levels as $key => $row) {
                            $obj_id = $row->_id->{'$id'}; ?>
                            <tr>
                                <td><?=sprintf("%02d", $row->level_no)?></td>
                                <td><?=$row->level_services->service_name?></td>
                                <td><?=$row->level_name?></td>
                                <td><?=$row->level_roles->role_name?></td>
                                <td>
                                    <?php                                    
                                    $forward_levels = $row->forward_levels;
                                    $fLbls = '';
                                    if(is_array($forward_levels) && count($forward_levels)) {
                                        foreach ($forward_levels as $flbl) {
                                           $fLbls = $fLbls.$flbl->level_name.", "; 
                                        }//End of foreach()
                                    }//End of if
                                    if(strlen($fLbls)) {
                                        $f_lbls = '<strong class="text-success">'.trim($fLbls, ', ').'</strong>';
                                    } else {
                                        $f_lbls = '<strong class="text-danger">UNDEFINED</strong>';
                                    }//End of if else
                                    
                                    $backward_levels = $row->backward_levels;
                                    $bLbls = '';
                                    if(is_array($backward_levels) && count($backward_levels)) {
                                        foreach ($backward_levels as $blbl) {
                                           $bLbls = $bLbls.$blbl->level_name.", "; 
                                        }//End of foreach()
                                    }//End of if
                                    if(strlen($bLbls)) {
                                        $b_lbls = '<strong class="text-success">'.trim($bLbls, ', ').'</strong>';
                                    } else {
                                        $b_lbls = '<strong class="text-danger">UNDEFINED</strong>';
                                    }//End of if else
                                    
                                    $level_rights = $row->level_rights;
                                    $uRights = '';
                                    if(is_array($level_rights) && count($level_rights)) {
                                        foreach ($level_rights as $uright) {
                                            if($uright->right_code === 'FORWARD') {
                                                $rightName = ' Forward to '.$f_lbls;
                                            } elseif($uright->right_code === 'BACKWARD') {
                                                $rightName = ' Send back to '.$b_lbls;
                                            } else {
                                                $rightName = $uright->right_name;
                                            }//End of if else
                                           $uRights = $uRights.$rightName.", <br>"; 
                                        }//End of foreach()
                                    }//End of if
                                    echo trim($uRights, ', <br>') ?>
                                </td>
                                <td><?=$row->level_description?></td>
                                <td style="text-align:center;">
                                    <a href="<?=base_url('spservices/upms/levels/index/'.$obj_id)?>" class="btn btn-warning btn-sm" >Edit</a>
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