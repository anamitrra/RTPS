<?php
$serviceId = $form_row->service_data->service_id??'';
$serviceRow = $this->services_model->get_row(['service_code' => $serviceId]);
if($serviceRow) {
    $previewLink = $serviceRow->preview_link??'';
    $custom_fields = $serviceRow->custom_fields??array();
    $button_levels = $serviceRow->button_levels??array();
    $service_mode = $serviceRow->service_mode??'ONLINE';
} else {
    $previewLink = '';
    $custom_fields = array();      
    $button_levels = array(); 
    $service_mode = 'ONLINE';
}//End of if else

$processDirection = $form_row->process_direction??'FORWARD';
$totalLevels = $this->levels_model->get_total_rows(array("level_services.service_code"=>$serviceId));
$obj_id = $form_row->_id->{'$id'};
$processing_history = $form_row->processing_history??array();

$loggedinUserLevelNo = $this->session->loggedin_user_level_no??0;
$current_status = $form_row->status??'';

$category = $form_row->form_data->category??'';
/*if($loggedinUserLevelNo <= 2) {
    $canTakeAction = true;
} elseif(($loggedinUserLevelNo == 3) && in_array($category, ['Class-1(B)', 'Class-1(C)'])) {
    $canTakeAction = true;
} elseif(($loggedinUserLevelNo == 4) && in_array($category, ['Class-1(A)'])) {
    $canTakeAction = true;
} else {
    $canTakeAction = false;
}//End of if else*/
$canTakeAction = true;
$levelRow = $this->levels_model->get_row(array("level_services.service_code" => $serviceId, "level_no" => $loggedinUserLevelNo));
$backward_levels = $levelRow->backward_levels??array();
$forward_levels = $levelRow->forward_levels??array();
$generate_certificate_levels = $levelRow->generate_certificate_levels??array();
$level_rights = $levelRow->level_rights??array();
$query_payment_amount = $levelRow->query_payment_amount??set_value('amount_to_pay');

$forwardLevels = array();
if(count($forward_levels)) {    
    foreach($forward_levels as $flvls) {
        $forwardLevels[]=$flvls->level_no;
    }//End of foreach()
}//End of if

$backwardLevels = array();
if(count($backward_levels)) {    
    foreach($backward_levels as $blvls) {
        $backwardLevels[]=$blvls->level_no;
    }//End of foreach()
}//End of if

$generateCertificateLevels = array();
if(count($generate_certificate_levels)) {    
    foreach($generate_certificate_levels as $blvls) {
        $generateCertificateLevels[]=$blvls->level_no;
    }//End of foreach()
}//End of if

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

function isProcessedBy($arrayOfObjects, $generateCertificateLevels) {
    if(is_array($arrayOfObjects) && count($arrayOfObjects)) {
        foreach ($arrayOfObjects as $obj) {
            if (isset($obj->processed_by_obj->user_level_no) && in_array($obj->processed_by_obj->user_level_no, $generateCertificateLevels)) {
                return true;
            }//End of if
        }//End of foreach()
    }//End of if
    return false;
}//End of isProcessedBy()

if(($totalLevels > 1) && ($loggedinUserLevelNo == 1)) { //Initial level start    
    $filterForward = array(
        "user_services.service_code" => $serviceId,
        "user_levels.level_no" => array('$in'=>$forwardLevels),
        "status"=>1
    );
    $filterBackward = array();
} elseif(($totalLevels > 1) && ($loggedinUserLevelNo == $totalLevels)) { //At the end of level
    $filterForward = array();
    $filterBackward = array(
        "user_services.service_code" => $serviceId,
        "user_levels.level_no" => array('$in'=>$backwardLevels),
        "status"=>1
    );
} else {//For Middle levels
    $filterForward = array(
        "user_services.service_code" => $serviceId,
        "user_levels.level_no" => array('$in'=>$forwardLevels),
        "status"=>1
    );
    $filterBackward = array(
        "user_services.service_code" => $serviceId,
        "user_levels.level_no" => array('$in'=>$backwardLevels),
        "status"=>1
    );
}//End of if else

$usersForward = count($filterForward)?$this->users_model->get_rows($filterForward):false;
$usersBackward = count($filterBackward)?$this->users_model->get_rows($filterBackward):false;
//echo '<pre>'; var_dump($filterBackward); '</pre>'; die;
?>

<script src="<?=base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.js")?>"></script>
<script type="text/javascript">
    $(document).ready(function () {  
        $(document).on("change", ".action-select", function(){
            var actionSelectId = $(this).attr("id");
            var actionSelectVal = $(this).val(); //alert(actionSelectId);
            if((actionSelectVal.length > 0) && (actionSelectId === 'backward_to')) {
                $("#forward_to").val("");
                $("#FORWARD_BTN").hide();
                $("#BACKWARD_BTN").show();
                //alert("Only one action is allowed at a time");
            } else if((actionSelectVal.length > 0) && (actionSelectId === 'forward_to')) {
                $("#backward_to").val("");
                $("#FORWARD_BTN").show();
                $("#BACKWARD_BTN").hide();
                //alert("Only one action is allowed at a time");
            } else {
                $("#FORWARD_BTN").hide();
                $("#BACKWARD_BTN").hide();
            }//End of if else
        });//End of onChange .action-select
        
        $(document).on("blur", "#amount_to_pay", function(){
            let amount_to_pay = parseFloat($(this).val());
            if(amount_to_pay > 0){
                $("#QUERY_BTN").html('<i class="fas fa-angle-double-left"></i> QUERY PAYMENT');
            } else {
                $("#QUERY_BTN").html('<i class="fas fa-angle-double-left"></i> QUERY');
            }//End of if else
        });//End of onClick .action-btn
        
        $(document).on("click", ".action-btn", function(){
            var actionRightCode = $(this).attr("data-rightcode");
            var actionLbl = (actionRightCode === 'BACKWARD')?'SEND BACK':actionRightCode;
            var serviceMode = '<?=$service_mode?>';
            if(actionRightCode === 'REJECT') {
               if($("#remarks").val().length === 0) {
                   alert("Remarks is required for Rejection");
                   $("#remarks").focus();
                   return false;
               }//End of if
            } else if((actionRightCode === 'APPROVE') && (serviceMode === 'OFFLINE') && ($('#input_file').get(0).files.length === 0)) {
                $("#input_file").focus();
                alert("Please upload a file");
                return false;
            } else {
                if (confirm('Are you sure, you want to '+actionLbl+' the application?')) {
                    $("#action_taken").val(actionRightCode);
                    $("#processForm").submit();
                    return true;
                } else {
                    return false;
                }//End of if else
            }//End of if else
        });//End of onClick .action-btn
    });
</script>

<link rel="stylesheet" href="<?=base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.css")?>" type="text/css">
<style type="text/css">
    .multi-select-container {
        display: block;
    }
    .multi-select-button {
        width: 100%;
        max-width: 100%;
        font-size: 1em;
        padding: 0.4em 0.6em;
    }
    .multi-select-menuitem {
        font-size: 1em;
    }
    .multi-select-button::after {
        float: right;
        margin-top: 8px;
    }
</style>
<div class="content-wrapper mt-2 p-2">    
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php }//End of if ?>
    <div class="accordion" id="accordionTasks">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTasks">
                <button class="btn btn-primary w-100" type="button" data-toggle="collapse" data-target="#form_preview" aria-expanded="true" aria-controls="form_preview" style="font-size:18px; text-transform: uppercase; font-weight: bold">
                    <span style="float:left">Application preview</span>
                    <span style="float:right"><i class="fa fa-chevron-circle-down"></i></span>
                </button>
            </h2><!--End of .accordion-header -->
            <div id="form_preview" class="accordion-collapse collapse" aria-labelledby="headingTasks" data-parent="#accordionTasks">
                <div class="accordion-body p-1">
                    <?php 
                    $rawFilePath = FCPATH.'application/modules/spservices/views/'.$previewLink;
                    $absFilePath = str_replace('\\', '/', $rawFilePath);//echo "File exists : ".file_exists($absFilePath.'.php')?'YES':'NO';
                    if(strlen($previewLink) && file_exists(FCPATH.'application/modules/spservices/views/'.$previewLink)) {
                        $data = array(
                            "service_name" => $form_row->service_data->service_name,
                            "dbrow" => $form_row,
                            "user_type" => $form_row->form_data->user_type??''
                        );
                        $this->load->view($previewLink, $data);
                    } else {
                        echo '<h2 style="text-align:center; font-size:18px !important; line-height:28px">Unable to locate the view file</h2>';
                    }//End of if else ?>
                </div>
            </div>
        </div>
    </div>
        
    <div class="card shadow-sm mt-2">
        <div class="card-header bg-info">
            <span class="h5 text-white">Application processing history</span>
        </div>
        <div class="card-body">
            <?php if(count($processing_history)) { ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Processed on</th>
                            <th>Processed by</th>
                            <th>Action taken</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($processing_history as $prows) { ?>
                            <tr>
                                <td><?= isset($prows->processing_time)?date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($prows->processing_time))):''?></td>
                                <td><?=$prows->processed_by??''?></td>
                                <td><?=$prows->action_taken??''?></td>
                                <td>
                                    <?php echo $prows->remarks;
                                    if(isset($prows->file_uploaded) and strlen($prows->file_uploaded)) {
                                        echo '<br><a href="'.base_url($prows->file_uploaded).'" class="btn btn-info" target="_blank"><i class="fa fa-file"></i> View uploaded file</a>';
                                    } ?>                                    
                                </td>
                            </tr>
                        <?php }//End of foreach() ?>
                    </tbody>
                </table>
            <?php }//End of if ?>
        </div>
    </div><!--End of .card-->
    
    <form method="POST" action="<?=base_url('spservices/upms/myapplications/process_submit')?>" id="processForm" enctype="multipart/form-data">
        <input name="obj_id" value="<?=$obj_id?>" type="hidden" />
        <input name="action_taken" id="action_taken" value='' type="hidden" />
        <div class="card shadow-sm mt-2">
            <div class="card-header bg-warning">
                <span class="h5 text-white">Your action</span>
                <span style="float: right; color:#fff">
                    Logged in as <strong><?=$this->session->loggedin_user_fullname?></strong>
                    (Role <?=$this->session->loggedin_user_role_code?> of Level-<?=$this->session->loggedin_user_level_no?>)
                </span>
            </div>
            <div class="card-body">   
                <?php if($usersForward && ($current_status !== "APPROVED")) {
                    //if(($loggedinUserLevelNo == 3) && in_array($category, ['Class-1(B)', 'Class-1(C)'])){
                        
                    //} else { ?>
                        <div class="row mt-0">
                            <div class="col-md-12 form-group">
                                <label><?=$button_levels->forward??'Forward'?> to</label>
                                <select name="forward_to" id="forward_to" class="form-control action-select">
                                    <option value="">Select </option>
                                    <?php foreach($usersForward as $fUser) {
                                        $fUserData = array(
                                            "login_username"=>$fUser->login_username,
                                            "user_role_code" => $fUser->user_roles->role_code,
                                            "user_level_no" => $fUser->user_levels->level_no,
                                            "user_fullname" => $fUser->user_fullname
                                        );
                                        $roleObj = json_encode($fUserData);
                                        $fUserText = $fUser->user_fullname.' ('.$fUser->user_roles->role_code.' of level-'.$fUser->user_levels->level_no.')';
                                        echo "<option value='".$roleObj."'>".$fUserText."</option>";                 
                                    }//End of foreach() ?>
                                </select>
                            </div>
                        </div>
                    <?php //}                    
                    } if($usersBackward && ($current_status !== "APPROVED")) { ?>
                    <div class="row mt-2">
                        <div class="col-md-12 form-group">
                            <label><?=$button_levels->backward??'Send back'?> to</label>
                            <select name="backward_to" id="backward_to" class="form-control action-select">
                                <option value="">Select </option>
                                <?php foreach($usersBackward as $bUser) {
                                    $bUserData = array(
                                        "login_username"=>$bUser->login_username,
                                        "user_role_code" => $bUser->user_roles->role_code,
                                        "user_level_no" => $bUser->user_levels->level_no,
                                        "user_fullname" => $bUser->user_fullname
                                    );
                                    $bUserText = $bUser->user_fullname.' ('.$bUser->user_roles->role_code.' of level-'.$bUser->user_levels->level_no.')';
                                    $roleObj = json_encode($bUserData);
                                    echo "<option value='".$roleObj."'>".$bUserText."</option>";                 
                                }//End of foreach() ?>
                            </select>
                        </div>
                    </div>
                <?php } if((isRightExist($level_rights, "QUERY_PAYMENT")) && (!in_array($current_status, ['APPROVED', 'QUERY_PAYMENT_ANSWERED']))) { ?>
                    <div class="row mt-2">
                        <div class="col-md-12 form-group">
                            <label>Payment amount</label>
                            <input name="amount_to_pay" value="<?=$query_payment_amount?>" id="amount_to_pay" class="form-control" type="number" />
                            <?= form_error("amount_to_pay") ?>
                        </div>
                    </div>
                <?php } if(isRightExist($level_rights, "REMARKS")) { ?>
                    <div class="row mt-2">
                        <div class="col-md-12 form-group">
                            <label><?=$button_levels->remarks??'Remarks'?></label>
                            <textarea id="remarks" name="remarks" class="form-control"><?=set_value("remarks")?></textarea>
                            <?= form_error("remarks") ?>
                        </div>
                    </div>
                <?php } if(isRightExist($level_rights, "FILE_UPLOAD")) { ?>
                    <div class="row mt-2">
                        <div class="col-md-12 form-group">
                            <label><?=$button_levels->file_upload??'File'?></label>
                            <div class="custom-file">
                                <input name="input_file" class="custom-file-input" id="input_file"  type="file" />
                                <label class="custom-file-label" for="input_file">Choose a file...</label>
                                <?= form_error("input_file") ?>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $('.custom-file-input').on('change',function(){
                            var fileName = $(this).val();
                            $(this).next('.custom-file-label').html(fileName);
                        });
                    </script>
                <?php }
                if(count($custom_fields) && $canTakeAction) { echo '<br><hr />';
                    foreach ($custom_fields as $rowIndex=>$custom_field) {
                        $preActionCode = $custom_field->right_code; //e.g. APPROVE
                        if($preActionCode ==="QUERY") {
                            $postActionCode = 'QUERY_ARISE';
                        } elseif($preActionCode ==="APPROVE") {
                            $postActionCode = 'APPROVED';
                        } else {
                            $postActionCode = $preActionCode.'ED';
                        }//End of if else
                        
                        if((isRightExist($level_rights, $preActionCode)) && (!in_array($current_status, [$postActionCode])) && ($custom_field->field_type == 'dropdown') && isset($custom_field->dropdown_data)) { ?>
                            <div class="row"> 
                                <div class="col-md-12 form-group">
                                    <label><?=$custom_field->field_level?><span class="text-danger">*</span> </label>
                                    <select name="<?=$custom_field->field_name?>[]" id="<?=$custom_field->field_name?>" class="form-control" multiple='multiple'>
                                        <?php if(count($custom_field->dropdown_data)) { 
                                            foreach($custom_field->dropdown_data as $ddRow) {
                                                $isSel = '';//in_array($service->service_code, $serviceCodes)?'selected':'';//($service->service_code===$service_code)?'selected':'';
                                                //$serviceObj = json_encode(array("service_code"=>$service->service_code, "service_name" => $service->service_name)); ?>
                                                <option value='<?=$ddRow->dropdown_value?>' <?=$isSel?>><?=$ddRow->dropdown_text?></option>
                                            <?php }//End of foreach()
                                        }//End of if ?>
                                    </select>
                                    <?= form_error($custom_field->field_name) ?>
                                </div>    
                            </div>
                            <script type="text/javascript">
                                $('#<?=$custom_field->field_name?>').multiselect({
                                    columns: 1,
                                    texts: {
                                        placeholder: 'Please select <?=$custom_field->field_level?>'
                                    }
                                });
                            </script>
                        <?php } elseif((isRightExist($level_rights, $preActionCode)) && (!in_array($current_status, [$postActionCode])) && ($custom_field->field_type == 'textarea')) { ?>
                            <div class="row mt-2">
                                <div class="col-md-12 form-group">
                                    <label><?=$custom_field->field_level?></label>
                                    <textarea name="<?=$custom_field->field_name?>" class="form-control"><?=set_value($custom_field->field_name)?></textarea>
                                    <?= form_error($custom_field->field_name) ?>
                                </div>
                            </div>
                        <?php } elseif((isRightExist($level_rights, $preActionCode)) && (!in_array($current_status, [$postActionCode]))) { ?>
                            <div class="row mt-2">
                                <div class="col-md-12 form-group">
                                    <label><?=$custom_field->field_level?></label>
                                    <input name="<?=$custom_field->field_name?>" class="form-control" type="text" />
                                    <?= form_error($custom_field->field_name) ?>
                                </div>
                            </div>
                        <?php } else {}//End of if else                        
                    }//End of foreach()
                }//End of if ?>
            </div>
            <div class="card-footer text-center">
                <?php if((isRightExist($level_rights, "FORWARD")) && (!in_array($current_status, ['APPROVED', 'QUERY_ARISE']))) { ?>
                    <button id="FORWARD_BTN" data-rightcode="FORWARD" class="btn btn-primary action-btn" style="display: none" type="button">
                        <?=$button_levels->forward??'FORWARD'?> <i class="fas fa-angle-double-right"></i>
                    </button>
                <?php } if(isRightExist($level_rights, "APPROVE") && (!in_array($current_status, ['APPROVED', 'QUERY_ARISE'])) && $canTakeAction) { ?>
                    <button id="APPROVE_BTN" data-rightcode="APPROVE" class="btn btn-primary action-btn" type="button">
                        <?=$button_levels->approve??'APPROVE'?> <i class="fas fa-angle-double-right"></i>
                    </button>                    
                <?php } if(isRightExist($level_rights, "REJECT") && (!in_array($current_status, ['APPROVED'])) && $canTakeAction) { ?>
                    <button id="REJECT_BTN" data-rightcode="REJECT" class="btn btn-danger action-btn" type="button">
                        <i class="fas fa-angle-double-left"></i> <?=$button_levels->reject??'REJECT'?>
                    </button>
                <?php } if(isRightExist($level_rights, "BACKWARD") && (!in_array($current_status, ['APPROVED', 'QUERY_ARISE']))) { ?>
                    <button id="BACKWARD_BTN" data-rightcode="BACKWARD" class="btn btn-warning action-btn" style="display: none" type="button">
                        <i class="fas fa-angle-double-left"></i> <?=$button_levels->backward??'SEND BACK'?>
                    </button>
                <?php } if(isRightExist($level_rights, "QUERY") && (!in_array($current_status, ['APPROVED', 'QUERY_ARISE'])) && $canTakeAction) { ?>
                    <button id="QUERY_BTN" data-rightcode="QUERY" class="btn btn-warning action-btn" type="button">
                        <i class="fas fa-angle-double-left"></i> <?=$button_levels->query??'QUERY TO APPLICANT'?>
                    </button>                        
                <?php }                 
                if(isRightExist($level_rights, "GENERATE_CERTIFICATE") && ($current_status === 'APPROVED') && $canTakeAction) { ?>
                    <button id="GENERATE_BTN" data-rightcode="GENERATE_CERTIFICATE" class="btn btn-success action-btn" type="button">
                        <i class="fas fa-check"></i> <?=$button_levels->generate_certificate??'GENERATE CERTIFICATE'?>
                    </button>                            
                <?php } else {}//End of if else                
                if(isRightExist($level_rights, "UPDATE_STATUS") && ($current_status !== 'APPROVED')) { ?>
                    <button id="UPDATE_STATUS_BTN" data-rightcode="UPDATE_STATUS" class="btn btn-secondary action-btn" type="button">
                        <i class="fas fa-plus-circle"></i> <?=$button_levels->update_status??'UPDATE STATUS'?>
                    </button>
                <?php } ?>
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </form>
</div>