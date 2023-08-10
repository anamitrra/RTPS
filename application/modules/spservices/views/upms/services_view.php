<?php
$depts = $this->depts_model->get_rows(array("status"=>1));
$services = $this->services_model->get_rows(array("status"=>1));
$rights = $this->rights_model->get_rows(array("status"=>1));
if(count((array)$dbrow)) {
    $title = "Edit existing service";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $service_name = $dbrow->service_name;
    $service_code = $dbrow->service_code??'';  
    $dept_info = $dbrow->dept_info??array();
    $dept_code = $dept_info->dept_code??'';  
    $preview_link = $dbrow->preview_link??'';
    $dsc_required = $dbrow->dsc_required??'NO';
    $service_mode = $dbrow->service_mode??'ONLINE';
    $timeline = $dbrow->timeline??'';
    $service_description = $dbrow->service_description;
    $input_file = $dbrow->input_file??'';    
    $custom_fields = $dbrow->custom_fields??array();
    $status = $dbrow->status;
} else {
    $title = "New service registration";
    $obj_id = null;
    $service_name = set_value("service_name");
    $service_code = set_value("service_code");    
    $dept_info = set_value("dept_info"); 
    $deptInfo = json_decode(htmlspecialchars_decode(html_entity_decode($dept_info)));
    $dept_code = $deptInfo->dept_code??'';
    $preview_link = set_value("preview_link");
    $dsc_required = set_value("dsc_required");
    $service_mode = set_value("service_mode");
    $timeline = set_value("timeline");
    $service_description = set_value("service_description");
    $input_file = null;
    $custom_fields = set_value("custom_fields");
    $status = null;
}//End of if else
$custom_fields_no = is_array($custom_fields)?count($custom_fields):0; ?>

<style type="text/css">
    .radio-container {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 6px 10px;
        background: #fff;
    }
</style>
<script type="text/javascript">
    var serviceCode = '';
    var fieldName = '';
    $(document).ready(function () {
        $(document).on("keyup", "#custom_fields", function(){
            var customFields = $(this).val(); //alert(customFields);
            if(!/^[0-9]+$/.test(customFields)){
                alert("Please enter a valid number");
                $(this).val("");
            } else {
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/upms/svcs/create_table')?>",
                    data: {"custom_fields": customFields},
                    beforeSend: function () {
                        $("#custom_fields_tbl").html('Loading...');
                    },
                    success: function (res) {
                        $("#custom_fields_tbl").html(res);
                    }
                });
                
            }//End of if else
        });//End of onKeyUp #custom_fields
                
        $(document).on("click", "#add_custom_tbl_row", function(){
            let totRows = $('#custom_tbl tr').length;
            var trow = `<tr>
                        <td><input id="field_name-${totRows-1}" name="field_names[]" class="form-control" required type="text" /></td>
                        <td><input id="field_level-${totRows-1}" name="field_levels[]" class="form-control" required type="text" /></td>
                        <td>
                            <select id="field_type-${totRows-1}" name="field_types[]" class="form-control field_types" required>
                                <option value="">Choose</option>
                                <option value="text">Text</option>
                                <option value="textarea">Text-area</option>
                                <option value="radio">Radio button</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="dropdown">Drop-down</option>
                            </select>
                        </td>
                        <td style="text-align: center">
                            <button class="btn btn-danger deletetblrow"><i class="fa fa-trash"></i></button>
                        </td>
                        </tr>`;
            if(totRows <= 10) {
                $('#custom_tbl tr:last').after(trow);
            }
        });
        
        $(document).on("change", ".field_types", function(){
            var selectVal = $(this).val();
            if(selectVal === 'dropdown') {
                var service_code = $("#service_code").val();
                var row_index = $(this).attr("id").split("-"); 
                var currentFieldName = $("#field_name-"+row_index[1]).val();
                if(service_code.length === 0) {
                    alert("Please enter a valid service code");
                    $("#service_code").focus();
                } else if(currentFieldName.length === 0) {
                    alert("Please enter a valid field name");
                    $("#field_name-"+row_index[1]).focus();
                } else {
                    serviceCode = service_code;
                    fieldName = currentFieldName;
                    //alert(row_index[1]+" : "+currentFieldName);
                    $('#dropdownModal').modal('show');
                }//End of if else
            }//End of if
        });
        
        $(document).on("click", "#addlatblrow", function(){
            let totRows = $('#dropdownTbl tr').length;
            var trow = `<tr>
                            <td><input name="dropdown_values[]" class="form-control dd_values" type="text" /></td>
                            <td><input name="dropdown_texts[]" class="form-control dd_texts" type="text" /></td>
                            <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash"></i></button></td>
                        </tr>`;
            if(totRows <= 10) {
                $('#dropdownTbl tr:last').after(trow);
            }
        });
        
        $(document).on("click", ".deletetblrow", function () {
            if (confirm('Are you sure, you want to delete this row?')) {
                var cf = parseInt($("#custom_fields").val())-1;
                $("#custom_fields").val(cf);
                $(this).closest("tr").remove();
                return false;
            } else {
                return true;
            }//End of if else                
        });
        
        $( "#dropdownModal" ).on('shown.bs.modal', function (e) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/upms/svcs/get_dropdown')?>",
                data: {"service_code": serviceCode, "field_name": fieldName},
                beforeSend: function () {
                    $("#dropdownModalBody").html('<div style="text-align:center; line-height:200px">Loading... Please wait</div>');
                },
                success: function (res) {
                    $("#dropdownModalBody").html(res);
                }
            });
        });
        
        $("#linkModal").on('shown.bs.modal', function (e) {
            var serviceCode = $(e.relatedTarget).attr('id');
            var linkCode = `
$userFilter = array(
    'user_services.service_code' => ${serviceCode},
    'user_levels.level_no' => 1
);
$userRows = $this->users_model->get_rows($userFilter);
$current_users = array();
if($userRows) {
    foreach ($userRows as $key => $userRow) {
        $current_user = array(
            'login_username' => $userRow->login_username,
            'email_id' => $userRow->email_id,
            'mobile_number' => $userRow->mobile_number,
            'user_level_no' => $userRow->user_levels->level_no,
            'user_fullname' => $userRow->user_fullname,
        );
        $current_users[] = $current_user;
    } //End of foreach()         

    $data = array(
        "current_users" => $current_users,
        "service_data" => array(
            ........
            ........
            ........
        ),
        "form_data" => array(
            ........
            ........
            ........
        ),
        ........
        ........
        ........
    );
    $this->applications_model->update_where(['_id' => new ObjectId($objId)], $data);
    $this->session->set_flashdata('flash_msg','Your application has been successfully linked');     
} else {
    $this->session->set_flashdata('flash_msg','User does not exist or mapped for the selected service');
} //End of if  else;`;
            $("#linkModalBody").html(linkCode).wrap('<pre />');            
        });        
        
        $(document).on("click", "#save_dropdown", function () {
            var ddValues = $(".dd_values");
            var ddTexts = $(".dd_texts");
            var ddArr = [];
            ddValues.each(function(idx){//alert($(this).val()+" => "+ddTexts[idx].value);  
                ddArr.push({
                    dropdown_value: $(this).val(),
                    dropdown_text: ddTexts[idx].value
                });  
            });
            var jsonString = JSON.stringify(ddArr);
            //console.log(ddArr);
            
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "<?=base_url('spservices/upms/svcs/create_dropdown')?>",
                data: {"service_code": serviceCode, "field_name": fieldName, "dropdown_data": jsonString},
                beforeSend: function () {
                    //$("#village_div").html('<select name="village" class="form-control"><option value="">Loading...</option></select>');
                },
                success: function (res) {
                    alert(res.post_msg);
                    $('#dropdownModal').modal('hide');
                }
            });
        });
        
        $(document).on("blur", "#preview_link", function () {
            var filePath = $(this).val();
            if(filePath.length < 5) {
                alert("Please enter a valid file name");
            } else {
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: "<?=base_url('spservices/upms/svcs/check_file')?>",
                    data: {"file_path": filePath},
                    beforeSend: function () {
                        $("#file_check_status").html("Checking file...");
                    },
                    success: function (res) {
                        var fontColor = (res.post_status == 1)?'green':'red';
                        $("#file_check_status").html(res.post_msg);
                        $("#file_check_status").css('color', fontColor);
                    }
                });
            }//End of if else                
        });
        $('.custom-file-input').on('change',function(){
            var fileName = $(this).val();
            $(this).next('.custom-file-label').html(fileName);
        });
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php } ?>
    <form method="POST" action="<?=base_url('spservices/upms/svcs/submit')?>" enctype="multipart/form-data">
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
                        <label>Service name <span class="text-danger">*</span></label>
                        <input class="form-control" name="service_name" value="<?=$service_name?>" maxlength="100" autocomplete="off" type="text" />
                        <?= form_error("service_name") ?>
                    </div>  
                    <div class="col-md-6 form-group">
                        <label>Service code <span class="text-danger">*</span></label>
                        <input id="service_code" class="form-control" name="service_code" value="<?=$service_code?>" maxlength="30" autocomplete="off" <?=strlen($obj_id)?'readonly':''?> type="text" />
                        <?= form_error("service_code") ?>
                    </div>
                </div>    
                
                <div class="row mt-2"> 
                    <div class="col-md-6 form-group">
                        <label>Digital Signature Certificates (DSC)</label>
                        <div class="radio-container">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="dsc_required" id="radioYes" value="YES" <?=($dsc_required=="YES")?'checked':''?> />
                                <label class="form-check-label" for="radioYes">YES</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="dsc_required" id="radioNo" value="NO" <?=($dsc_required=="NO")?'checked':''?> />
                                <label class="form-check-label" for="radioNo">NO</label>
                            </div>
                        </div><!--End of .radio-container -->
                        <?= form_error("dsc_required") ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Mode of service delivery</label>
                        <div class="radio-container">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="service_mode" id="online" value="ONLINE" <?=($service_mode=="ONLINE")?'checked':''?> />
                                <label class="form-check-label" for="online">ONLINE</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="service_mode" id="offline" value="OFFLINE" <?=($service_mode=="OFFLINE")?'checked':''?> />
                                <label class="form-check-label" for="offline">OFFLINE</label>
                            </div>
                        </div><!--End of .radio-container -->
                        <?= form_error("service_mode") ?>
                    </div>
                </div>
                
                <div class="row mt-2"> 
                    <div class="col-md-6 form-group">
                        <label>Timeline</label>
                        <input id="timeline" class="form-control" name="timeline" value="<?=$timeline?>" maxlength="30" type="text" />
                        <?= form_error("timeline") ?>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Application preview file location <span class="text-danger">*</span></label>
                        <input id="preview_link" name="preview_link" class="form-control" value="<?=$preview_link?>" placeholder="e.g. upms/userprofile_view.php" autocomplete="off" type="text" />
                        <font id="file_check_status" style="color:red; font-size:12px; font-style:italic; font-weight: bold"></font>
                        <?=form_error("preview_link")?>
                    </div>
                </div>
                                
                <div class="row mt-2">
                    <div class="col-md-12 form-group">
                        <label>Description (If any)</label>
                        <input class="form-control" name="service_description" value="<?=$service_description?>" maxlength="100" type="text" />
                        <?= form_error("service_description") ?>
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-md-12 form-group">
                        <label>Work-flow diagram/chart/notes</label>
                        <div class="custom-file">
                            <input name="input_file" class="custom-file-input" id="input_file"  type="file" />
                            <label class="custom-file-label" for="input_file">Choose a file...</label>
                            <?= form_error("input_file") ?>
                        </div>
                        <?php if(strlen($input_file)>10) {
                            $downloadLink = base_url($input_file);
                            echo "<a href='{$downloadLink}' target='_blank'><i class='fa fa-cloud-download'></i> View uploaded file</a>";
                        } ?>
                    </div>
                </div>
                
                <div class="row mt-2">  
                    <div class="col-md-12 form-group">
                        <label>Total no. of custom fields <span class="text-danger">*</span></label>
                        <input id="custom_fields" name="custom_fields" class="form-control" value="<?=$custom_fields_no?$custom_fields_no:''?>" placeholder="" type="text" />
                        <?=form_error("custom_fields")?>
                    </div>
                    <div id="custom_fields_tbl" class="col-md-12">
                        <?php if($custom_fields_no) { ?>
                        <table id="custom_tbl" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Field name</th>
                                    <th>Field level</th>
                                    <th>Field type</th>
                                    <!--<th data-toggle="tooltip" title="At which level users can access this field">Level no.</th>-->
                                    <th>
                                        <div data-toggle="tooltip" data-placement="top" title="When will it appear">Right</div>
                                    </th>
                                    <th style="text-align: center">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($custom_fields as $rowIndex=>$custom_field) {
                                    if ($rowIndex == 0) {
                                    $btn = '<button class="btn btn-info" id="add_custom_tbl_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                } else {
                                    $btn = '<button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash"></i></button>';
                                }// End of if else ?>
                                <tr>
                                    <td><input id="field_name-<?=$rowIndex?>" name="field_names[]" value="<?=$custom_field->field_name?>" class="form-control" required type="text" /></td>
                                    <td><input id="field_level-<?=$rowIndex?>" name="field_levels[]" value="<?=$custom_field->field_level?>" class="form-control" required type="text" /></td>
                                    <td>
                                        <select id="field_type-<?=$rowIndex?>" name="field_types[]" class="form-control field_types" required>
                                            <option value="">Choose</option>
                                            <option value="text" <?=($custom_field->field_type == 'text')?'selected':''?>>Text</option>
                                            <option value="textarea" <?=($custom_field->field_type == 'textarea')?'selected':''?>>Text-area</option>
                                            <option value="radio" <?=($custom_field->field_type == 'radio')?'selected':''?>>Radio button</option>
                                            <option value="checkbox" <?=($custom_field->field_type == 'checkbox')?'selected':''?>>Checkbox</option>
                                            <option value="dropdown" <?=($custom_field->field_type == 'dropdown')?'selected':''?>>Drop-down</option>
                                        </select>
                                    </td>
                                    <!--<td>
                                        <select id="level_no-<?=$rowIndex?>" name="level_nos[]" class="form-control">
                                            <option value="0">Please Select</option>
                                            <?php for($lno=1; $lno <= 10; $lno++) { ?>
                                                <option value="<?=$lno?>" <?=((int)$custom_field->level_no === $lno)?'selected':''?>><?=sprintf('%02d', $lno)?></option>
                                            <?php }//End of for() ?>
                                        </select>
                                    </td>-->
                                    <td>
                                        <select id="right_code-<?=$rowIndex?>" name="right_codes[]" class="form-control">
                                            <?php if ($rights) {
                                                echo '<option value="0">Please Select</option>';
                                                foreach ($rights as $key => $right) {
                                                    $isRightSel = ($custom_field->right_code === $right->right_code)?'selected':'';
                                                    echo "<option value='{$right->right_code}' {$isRightSel}>{$right->right_name}</option>";
                                                }//End of foreach()
                                            } else {
                                                echo '<option value="0">No records found</option>';
                                            }//End of if else ?>
                                        </select>
                                    </td>
                                    <td style="text-align: center"><?=$btn?></td>
                                <?php }//End of foreach() ?>
                                </tr>
                            </tbody>
                        </table>
                        <?php }//End of if ?>
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
    
    <div class="card shadow-sm mt-2">
        <div class="card-header">
            <span class="h5 text-dark">Registered services</span>
        </div>
        <div class="card-body">                
            <?php if ($services): ?>
                <table id="dtbl" class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Service code</th>
                            <th>Service name</th>
                            <th>Department name</th>
                            <th style="width: 100px; text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($services as $key => $row):
                            $service_code = $row->service_code??'';
                            $obj_id = $row->_id->{'$id'}; ?>
                            <tr>
                                <td><?= ($key + 1) ?></td>
                                <td><?=$service_code?></td>
                                <td><?=$row->service_name?></td>
                                <td><?=$row->dept_info->dept_name??'Undefined'?></td>
                                <td style="text-align:center">
                                    <a href="<?=base_url('spservices/upms/svcs/index/'.$obj_id)?>" class="btn btn-warning btn-sm" ><i class="fa fa-pen"></i></a>
                                    <a href="<?=base_url('spservices/upms/workflow/index/'.$service_code)?>" class="btn btn-info btn-sm" target="_blank">
                                        <i class="fa fa-sitemap"></i>
                                    </a>
                                    <button id="<?=$service_code?>" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#linkModal">
                                        <i class="fa fa-link"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No records found<p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="dropdownModal" tabindex="-1" role="dialog" aria-labelledby="dropdownModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dropdownModalTitle">Define Drop-down list</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="dropdownModalBody" class="modal-body">
                <div style="text-align:center; line-height:200px">Loading... Please wait</div>
            </div>
            <div class="modal-footer d-block text-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                <button id="save_dropdown" type="button" class="btn btn-success" >SAVE</button>
            </div>
        </div>
    </div>
</div><!--End of #dropdownModal -->

<div class="modal fade" id="linkModal" tabindex="-1" role="dialog" aria-labelledby="linkModalTitle" aria-hidden="true">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="linkModalTitle">Fetched and linked user</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div id="linkModalBody" class="modal-body bg-dark text-white" style="font-size:12px; line-height: 12px; padding:5px">
                <div style="text-align:center; line-height:200px">Loading... Please wait</div>
            </div>
            <div class="modal-footer d-block text-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal">CLOSE</button>
                <button id="save_dropdown" type="button" class="btn btn-success" >SAVE</button>
            </div>
        </div>
    </div>
</div><!--End of #linkModal -->

<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<link rel="stylesheet" type="text/css" media='all' href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" media='all' href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />
<script type="text/javascript">
$(document).ready(function () {
    $('#dtbl').DataTable();
});
</script>