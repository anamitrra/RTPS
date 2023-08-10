<?php
$countries = $this->countries_model->get_rows(array());
$lacs = $this->lac_model->get_rows(array());
$obj_id = $dbrow->{'_id'}->{'$id'};
$rtps_trans_id = $dbrow->rtps_trans_id;

if(isset($dbrow->bride_first_name) && strlen($dbrow->bride_first_name)) {
    $title = "Edit Existing Information";

    $bride_prefix = $dbrow->bride_prefix??'';
    $bride_first_name = $dbrow->bride_first_name??'';
    $bride_middle_name = $dbrow->bride_middle_name??'';
    $bride_last_name = $dbrow->bride_last_name??'';                        
    $bride_father_prefix = $dbrow->bride_father_prefix??'';
    $bride_father_first_name = $dbrow->bride_father_first_name??'';
    $bride_father_middle_name = $dbrow->bride_father_middle_name??'';
    $bride_father_last_name = $dbrow->bride_father_last_name??'';                   
    $bride_mother_prefix = $dbrow->bride_mother_prefix??'';
    $bride_mother_first_name = $dbrow->bride_mother_first_name??'';
    $bride_mother_middle_name = $dbrow->bride_mother_middle_name??'';
    $bride_mother_last_name = $dbrow->bride_mother_last_name??'';

    $bride_status = $dbrow->bride_status??'';
    $bride_occupation = $dbrow->bride_occupation??'';
    $bride_category = $dbrow->bride_category??'';
    $bride_dob = $dbrow->bride_dob??'';
    $bride_mobile_number = $dbrow->bride_mobile_number??'';
    $bride_email_id = $dbrow->bride_email_id??'';
    $bride_disability = $dbrow->bride_disability??'';
    $bride_disability_type = $dbrow->bride_disability_type??'';
       
    $bride_children = $dbrow->bride_children??array();    
    $bride_child_first_name = array();
    $bride_child_middle_name = array();
    $bride_child_last_name= array();
    $bride_child_dob = array();
    $bride_child_address = array();    
    if(count($bride_children)) {
        foreach($bride_children as $bc) {
            //echo "OBJ : ".$bc_fname->patta_no."<br>";
            array_push($bride_child_first_name, $bc->bride_child_first_name);
            array_push($bride_child_middle_name, $bc->bride_child_middle_name);
            array_push($bride_child_last_name, $bc->bride_child_last_name);
            array_push($bride_child_dob, $bc->bride_child_dob);
            array_push($bride_child_address, $bc->bride_child_address);
        }//End of foreach()
    }//End of if

    $bride_dependents = $dbrow->bride_dependents??array();    
    $bride_dependent_first_name = array();
    $bride_dependent_middle_name = array();
    $bride_dependent_last_name= array();
    $bride_dependent_dob = array();
    $bride_dependent_address = array();    
    if(count($bride_dependents)) {
        foreach($bride_dependents as $bc) {
            //echo "OBJ : ".$bc_fname->patta_no."<br>";
            array_push($bride_dependent_first_name, $bc->bride_dependent_first_name);
            array_push($bride_dependent_middle_name, $bc->bride_dependent_middle_name);
            array_push($bride_dependent_last_name, $bc->bride_dependent_last_name);
            array_push($bride_dependent_dob, $bc->bride_dependent_dob);
            array_push($bride_dependent_address, $bc->bride_dependent_address);
        }//End of foreach()
    }//End of if
    $bride_dependent_income = $dbrow->bride_dependent_income??'';
                        
    $bride_present_country = $dbrow->bride_present_country??'';
    $bride_present_state = $dbrow->bride_present_state??'';
    $bride_present_state_name = $dbrow->bride_present_state_name??'';
    $bride_present_state_foreign = $dbrow->bride_present_state_foreign??'';
    $bride_present_district = $dbrow->bride_present_district??'';
    $bride_present_city = $dbrow->bride_present_city??'';
    $bride_present_ps = $dbrow->bride_present_ps??'';
    $bride_present_po = $dbrow->bride_present_po??'';
    $bride_present_address1 = $dbrow->bride_present_address1??'';
    $bride_present_address2 = $dbrow->bride_present_address2??'';
    $bride_present_pin = $dbrow->bride_present_pin??'';
    $bride_lac = $dbrow->bride_lac??'';
    $lac_id = $bride_lac->lac_id??'';
    $bride_present_period_years = $dbrow->bride_present_period_years??'';
    $bride_present_period_months = $dbrow->bride_present_period_months??'';

    $bride_address_same = $dbrow->bride_address_same??'';
    $bride_permanent_country = $dbrow->bride_permanent_country??'';
    $bride_permanent_state = $dbrow->bride_permanent_state??'';
    $bride_permanent_state_name = $dbrow->bride_permanent_state_name??'';
    $bride_permanent_state_foreign = $dbrow->bride_permanent_state_foreign??'';
    $bride_permanent_district = $dbrow->bride_permanent_district??'';
    $bride_permanent_city = $dbrow->bride_permanent_city??'';
    $bride_permanent_ps = $dbrow->bride_permanent_ps??'';
    $bride_permanent_po = $dbrow->bride_permanent_po??'';
    $bride_permanent_address1 = $dbrow->bride_permanent_address1??'';
    $bride_permanent_address2 = $dbrow->bride_permanent_address2??'';
    $bride_permanent_pin = $dbrow->bride_permanent_pin??'';
} else {
    $title = "New Applicant Registration";
    $bride_prefix = set_value("bride_prefix");
    $bride_first_name = set_value("bride_first_name");
    $bride_middle_name = set_value("bride_middle_name");
    $bride_last_name = set_value("bride_last_name");                        
    $bride_father_prefix = set_value("bride_father_prefix");
    $bride_father_first_name = set_value("bride_father_first_name");
    $bride_father_middle_name = set_value("bride_father_middle_name");
    $bride_father_last_name = set_value("bride_father_last_name");                   
    $bride_mother_prefix = set_value("bride_mother_prefix");
    $bride_mother_first_name = set_value("bride_mother_first_name");
    $bride_mother_middle_name = set_value("bride_mother_middle_name");
    $bride_mother_last_name = set_value("bride_mother_last_name");

    $bride_status = set_value("bride_status");
    $bride_occupation = set_value("bride_occupation");
    $bride_category = set_value("bride_category");
    $bride_dob = set_value("bride_dob");
    $bride_mobile_number = set_value("bride_mobile_number");
    $bride_email_id = set_value("bride_email_id");
    $bride_disability = set_value("bride_disability");
    $bride_disability_type = set_value("bride_disability_type");

    $bride_child_first_name = set_value("bride_child_first_name");
    $bride_child_middle_name = set_value("bride_child_middle_name");
    $bride_child_last_name = set_value("bride_child_last_name");
    $bride_child_dob = set_value("bride_child_dob");
    $bride_child_address = set_value("bride_child_address");

    $bride_dependent_first_name = set_value("bride_dependent_first_name");
    $bride_dependent_middle_name = set_value("bride_dependent_middle_name");
    $bride_dependent_last_name = set_value("bride_dependent_last_name");
    $bride_dependent_dob = set_value("bride_dependent_dob");
    $bride_dependent_address = set_value("bride_dependent_address");
    $bride_dependent_income = set_value("bride_dependent_income");
                        
    $bride_present_country = set_value("bride_present_country");
    $bride_present_state = set_value("bride_present_state");
    $bride_present_state_name = set_value("bride_present_state_name");
    $bride_present_state_foreign = set_value("bride_present_state_foreign");
    $bride_present_district = set_value("bride_present_district");
    $bride_present_city = set_value("bride_present_city");
    $bride_present_ps = set_value("bride_present_ps");
    $bride_present_po = set_value("bride_present_po");
    $bride_present_address1 = set_value("bride_present_address1");
    $bride_present_address2 = set_value("bride_present_address2");
    $bride_present_pin = set_value("bride_present_pin");
    $bride_lac = set_value("bride_lac");
    if(strlen($bride_lac)) {
        $brideLac = json_decode(html_entity_decode($bride_lac));
        $lac_id = $brideLac->lac_id;
    } else {
        $lac_id = '';
    }//End of if else
    $bride_present_period_years = set_value("bride_present_period_years");
    $bride_present_period_months = set_value("bride_present_period_months");

    $bride_address_same = set_value("bride_address_same");
    $bride_permanent_country = set_value("bride_permanent_country");
    $bride_permanent_state = set_value("bride_permanent_state");
    $bride_permanent_state_name = set_value("bride_permanent_state_name");
    $bride_permanent_state_foreign = set_value("bride_permanent_state_foreign");
    $bride_permanent_district = set_value("bride_permanent_district");
    $bride_permanent_city = set_value("bride_permanent_city");
    $bride_permanent_ps = set_value("bride_permanent_ps");
    $bride_permanent_po = set_value("bride_permanent_po");
    $bride_permanent_address1 = set_value("bride_permanent_address1");
    $bride_permanent_address2 = set_value("bride_permanent_address2");
    $bride_permanent_pin = set_value("bride_permanent_pin");
}//End of if else
//die($title." : ".$obj_id);
?>
<style type="text/css">
    body {
        font-size: 12px;
    }
    legend {
        display: inline;
        width: auto;
    }
    li {
        font-size: 14px;
        line-height: 24px;
    }
</style>


<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">

<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script type="text/javascript">   
    $(document).ready(function () {
        
        $(document).on("change", "#bride_disability", function(){
            let checkedVal = $(this).val(); //alert(checkedVal);
            if(checkedVal === "1") {
                $("#bride_disability_type").prop('disabled', false);
            } else {
                $("#bride_disability_type").val("");
                $("#bride_disability_type").prop('disabled', true);
            }//End of if else
        }); //End of #bride_disability change()
                
        $(document).on("click", "#add_child_tbl_row", function(){
            let totRows = $('#bride_children_tbl tr').length;
            var trow = `<tr>
                            <td><input name="bride_child_first_name[]" class="form-control" type="text" /></td>
                            <td><input name="bride_child_middle_name[]" class="form-control" type="text" /></td>
                            <td><input name="bride_child_last_name[]" class="form-control" type="text" /></td>
                            <td><input name="bride_child_dob[]" class="form-control dp-post" type="text" /></td>
                            <td><input name="bride_child_address[]" class="form-control" type="text" /></td>
                            <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if(totRows <= 5) {
                $('#bride_children_tbl tr:last').after(trow);
                $(".dp-post").datepicker({
                    format: 'dd-mm-yyyy',
                    endDate: '+0d',
                    autoclose: true
                });
            }
        });
                
        $(document).on("click", "#add_dependents_tbl_row", function(){
            let totRows = $('#bride_dependents_tbl tr').length;
            var trow = `<tr>
                            <td><input name="bride_dependent_first_name[]" class="form-control" type="text" /></td>
                            <td><input name="bride_dependent_middle_name[]" class="form-control" type="text" /></td>
                            <td><input name="bride_dependent_last_name[]" class="form-control" type="text" /></td>
                            <td><input name="bride_dependent_dob[]" class="form-control dp-post" type="text" /></td>
                            <td><input name="bride_dependent_address[]" class="form-control" type="text" /></td>
                            <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if(totRows <= 5) {
                $('#bride_dependents_tbl tr:last').after(trow);
                $(".dp-post").datepicker({
                    format: 'dd-mm-yyyy',
                    endDate: '+0d',
                    autoclose: true
                });
            }
        });

        $(document).on("click", ".deletetblrow", function () {
            $(this).closest("tr").remove();
            return false;
        });    
        
        $("#bride_dob").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '-6570d', //18 yrs
            autoclose: true
        });  
        
        $(".dp-post").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '+0d',
            autoclose: true
        });
        
        $(document).on("change", ".bride_country", function(){
            let field_name = $(this).attr("id"); //alert(field_name);
            let selectedText = $(this).find("option:selected").text();
            var field_names = field_name.split('_'); //alert(field_names[1]);
            if(selectedText === 'India') {
                $("#bride_"+field_names[1]+"_state").prop("disabled", false);     
                $("#bride_"+field_names[1]+"_district").prop("disabled", false);    
                
                $("#bride_"+field_names[1]+"_state_foreign").val("");                
                $("#bride_"+field_names[1]+"_state_foreign").prop("disabled", true);
                $("#bride_"+field_names[1]+"_ps").prop("disabled", false);
                $("#bride_"+field_names[1]+"_po").prop("disabled", false);
                $("#bride_"+field_names[1]+"_address1").val("");
                $("#bride_"+field_names[1]+"_address1").prop("disabled", true);
                $("#bride_"+field_names[1]+"_address2").val(""); 
                $("#bride_"+field_names[1]+"_address2").prop("disabled", true);    
                
                $("#bride_lac").prop("disabled", false);
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/marriageregistration_landhub/bridedetails/get_states')?>",
                    data: {"field_name":"bride_"+field_names[1]+"_state", "field_value":selectedText},
                    beforeSend:function(){
                        $("#"+field_names[1]+"_state_div").html("Loading");
                    },
                    success:function(res){
                        $("#"+field_names[1]+"_state_div").html(res);
                    }
                });
            } else {
                $("#bride_"+field_names[1]+"_state").val("");
                $("#bride_"+field_names[1]+"_state").prop("disabled", true);
                $("#bride_"+field_names[1]+"_district").val("");
                $("#bride_"+field_names[1]+"_district").prop("disabled", true);
                $("#bride_lac").val("");
                $("#bride_"+field_names[1]+"_state_foreign").prop("disabled", false);
                $("#bride_"+field_names[1]+"_ps").val("");
                $("#bride_"+field_names[1]+"_ps").prop("disabled", true);
                $("#bride_"+field_names[1]+"_po").val("");
                $("#bride_"+field_names[1]+"_po").prop("disabled", true);
                $("#bride_"+field_names[1]+"_address1").prop("disabled", false);
                $("#bride_"+field_names[1]+"_address2").prop("disabled", false);
            }//End of if else
        });//End of onchange .bride_country
        
        var getDistricts = function(slc, fieldName) {
            //alert(slc+" @ "+fieldName);
            if(slc.length) {
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/marriageregistration_landhub/bridedetails/get_districts')?>",
                    data: {
                        "field_name":"bride_"+fieldName+"_district", 
                        "field_value": slc
                    },
                    beforeSend:function(){
                        $("#"+fieldName+"_district_div").html("Loading");
                    },
                    success:function(res){
                        $("#"+fieldName+"_district_div").html(res);
                    }
                });
            } else {
                alert("Please select a state");
            }//End of if else
        };//End of if getDistricts()
                
        $(document).on("change", "#bride_present_state", function(){
            let selectedState = $(this).val();
            getDistricts(selectedState, "present");
            $("#bride_present_state_name").val($(this).find("option:selected").text());
        });
        
        $(document).on("change", "#bride_permanent_state", function(){
            let selectedState = $(this).val();
            getDistricts(selectedState, "permanent");
            $("#bride_permanent_state_name").val($(this).find("option:selected").text());
        });
                
        $(document).on("change", ".bride_address_same", function(){                
            let checkedVal = $(this).val(); //alert(checkedVal);
            if(checkedVal === "YES") {
                $('#bride_permanent_country').val($("#bride_present_country").val());
                
                let stateId = $("#bride_present_state").find("option:selected").val();
                let stateName = $("#bride_present_state").find("option:selected").text();
                $('#bride_permanent_state').empty().append('<option value="'+stateId+'">'+stateName+'</option>');  
                $('#bride_permanent_state').prop('disabled', false);      
                
                $('#bride_permanent_state_name').val($('#bride_present_state_name').val());         
                $('#bride_permanent_state_foreign').val($('#bride_present_state_foreign').val());
                $('#bride_permanent_state_foreign').prop('disabled', $('#bride_present_state_foreign').prop('disabled')); 
                
                let district = $('#bride_present_district').find("option:selected").val();
                $('#bride_permanent_district').empty().append('<option value="'+district+'">'+district+'</option>');
                $('#bride_permanent_district').prop('disabled', false); 
                
                $('#bride_permanent_city').val($('#bride_present_city').val());
                $('#bride_permanent_ps').val($('#bride_present_ps').val());
                $('#bride_permanent_po').val($('#bride_present_po').val());
                $('#bride_permanent_address1').val($('#bride_present_address1').val());
                $('#bride_permanent_address1').prop('disabled', $('#bride_present_address1').prop('disabled'));                
                $('#bride_permanent_address2').val($('#bride_present_address2').val());
                $('#bride_permanent_address2').prop('disabled', $('#bride_present_address2').prop('disabled'));
                $('#bride_permanent_pin').val($('#bride_present_pin').val());
            } else {
                $('#bride_permanent_country').val('');
                $('#bride_permanent_state').val('');
                $('#bride_permanent_state_name').val('');
                $('#bride_permanent_state_foreign').val('');
                $('#bride_permanent_state_foreign').prop('disabled', false);
                $('#bride_permanent_district').val('');
                $('#bride_permanent_city').val('');
                $('#bride_permanent_ps').val('');
                $('#bride_permanent_po').val('');
                $('#bride_permanent_address1').val('');
                $('#bride_permanent_address1').prop('disabled', false);  
                $('#bride_permanent_address2').val('');
                $('#bride_permanent_address2').prop('disabled', false); 
                $('#bride_permanent_pin').val('');
            }//End of if else
        });//End of onChange .bride_address_same
        
        $(document).on("click", ".frmbtn", function(){ 
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE_NEXT') {
                var msg = "Once you submitted, you won't able to revert this";
            } else if(clickedBtn === 'CLEAR') {
                var msg = "Once you Reset, All filled data will be cleared";
            } else {
                var msg = "";
            }//End of if else            
            Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    if((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE_NEXT')) {
                        $("#myfrm").submit();
                    } else if(clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {}//End of if else
                }
            });
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/marriageregistration_landhub/bridedetails/submit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="rtps_trans_id" value="<?=$rtps_trans_id?>" type="hidden" />
            <input name="bride_present_state_name" id="bride_present_state_name" value="<?=$bride_present_state_name?>" type="hidden" />
            <input name="bride_permanent_state_name" id="bride_permanent_state_name" value="<?=$bride_permanent_state_name?>" type="hidden" />
            <input id="submit_mode" name="submit_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                       <?=$service_name?> 
                </div>
                <div class="card-body" style="padding:5px">
                    
                    <?php if ($this->session->flashdata('fail') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('error') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('success') != null) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>
                    
                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <legend class="h5">Bride Details/কইনাৰ বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Prefix/ উপসৰ্গ <span class="text-danger">*</span> </label>
                                <select name="bride_prefix" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Miss." <?=($bride_prefix=="Miss.")?'selected':''?>>Miss.</option>
                                    <option value="Srimati" <?=($bride_prefix=="Srimati")?'selected':''?>>Srimati</option>
                                    <option value="Dr." <?=($bride_prefix=="Dr.")?'selected':''?>>Dr.</option>
                                </select>
                                <?=form_error('bride_prefix')?>
                            </div>

                            <div class="col-md-3">
                                <label>Bride First Name/কইনা প্ৰথম নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_first_name" id="father_name" value="<?= $bride_first_name ?>" maxlength="255" />
                                <?= form_error("bride_first_name") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Bride Middle Name/কইনা মধ্য নাম </label>
                                <input type="text" class="form-control" name="bride_middle_name" value="<?=$bride_middle_name?>" maxlength="255" />
                                <?= form_error("bride_middle_name") ?>
                            </div>

                            <div class="col-md-3">
                                <label>Bride Last Name/কইনা অন্তিম নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_last_name" value="<?=$bride_last_name?>" maxlength="255" />
                                <?= form_error("bride_last_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Prefix/ উপসৰ্গ <span class="text-danger">*</span> </label>
                                <select name="bride_father_prefix" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Mr." <?=($bride_father_prefix=="Mr.")?'selected':''?>>Mr.</option>
                                    <option value="Sri" <?=($bride_father_prefix=="Sri")?'selected':''?>>Sri.</option>
                                    <option value="Dr." <?=($bride_father_prefix=="Dr.")?'selected':''?>>Dr.</option>
                                    <option value="Late" <?=($bride_father_prefix=="Late")?'selected':''?>>Late</option>
                                </select>
                                <?=form_error('bride_father_prefix')?>
                            </div>

                            <div class="col-md-3">
                                <label>Father's First Name/পিতাৰ প্ৰথম নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_father_first_name" value="<?= $bride_father_first_name ?>" maxlength="255" />
                                <?= form_error("bride_father_first_name") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Father's Middle Name/ পিতাৰ মধ্য নাম\ </label>
                                <input type="text" class="form-control" name="bride_father_middle_name" value="<?=$bride_father_middle_name?>" maxlength="255" />
                                <?= form_error("bride_father_middle_name") ?>
                            </div>

                            <div class="col-md-3">
                                <label>Father's Last Name/ পিতাৰ অন্তিম নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_father_last_name" value="<?=$bride_father_last_name?>" maxlength="255" />
                                <?= form_error("bride_father_last_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Prefix/ উপসৰ্গ <span class="text-danger">*</span> </label>
                                <select name="bride_mother_prefix" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Dr." <?=($bride_mother_prefix=="Dr.")?'selected':''?>>Dr.</option>
                                    <option value="Mrs." <?=($bride_mother_prefix=="Mrs.")?'selected':''?>>Mrs.</option>
                                    <option value="Miss" <?=($bride_mother_prefix=="Miss")?'selected':''?>>Miss</option>
                                    <option value="Late" <?=($bride_mother_prefix=="Late")?'selected':''?>>Late</option>
                                </select>
                                <?=form_error('bride_mother_prefix')?>
                            </div>

                            <div class="col-md-3">
                                <label>Mother's First Name/মাতৃৰ প্ৰথম নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_mother_first_name" value="<?= $bride_mother_first_name ?>" maxlength="255" />
                                <?= form_error("bride_mother_first_name") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Mother's Middle Name/ মাতৃৰ মধ্য নাম </label>
                                <input type="text" class="form-control" name="bride_mother_middle_name" value="<?=$bride_mother_middle_name?>" maxlength="255" />
                                <?= form_error("bride_mother_middle_name") ?>
                            </div>

                            <div class="col-md-3">
                                <label>Mother Last Name/ মাতৃৰ অন্তিম নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_mother_last_name" value="<?=$bride_mother_last_name?>" maxlength="255" />
                                <?= form_error("bride_mother_last_name") ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Bride Status/কইনাৰ স্থিতি <span class="text-danger">*</span> </label>
                                <select name="bride_status" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Unmarried" <?=($bride_status=="Unmarried")?'selected':''?>>Unmarried</option>
                                    <option value="Widower" <?=($bride_status=="Widower")?'selected':''?>>Widower</option>
                                    <option value="Widow" <?=($bride_status=="Widow")?'selected':''?>>Widow</option>
                                    <option value="Divorcee" <?=($bride_status=="Divorcee")?'selected':''?>>Divorcee</option>
                                    <option value="Married" <?=($bride_status=="Married")?'selected':''?>>Married</option>
                                </select>
                                <?=form_error('bride_status')?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Occupation/বৃত্তি <span class="text-danger">*</span> </label>
                                <select name="bride_occupation" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Govt. Service" <?=($bride_occupation=="Govt. Service")?'selected':''?>>Govt. Service</option>
                                    <option value="Private Service" <?=($bride_occupation=="Private Service")?'selected':''?>>Private Service</option>
                                    <option value="Business" <?=($bride_occupation=="Business")?'selected':''?>>Business</option>
                                    <option value="Lawyers" <?=($bride_occupation=="Lawyers")?'selected':''?>>Lawyers</option>
                                    <option value="Doctors" <?=($bride_occupation=="Doctors")?'selected':''?>>Doctors</option>
                                    <option value="Other" <?=($bride_occupation=="Other")?'selected':''?>>Other</option>
                                </select>
                                <?=form_error('bride_occupation')?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Category/শ্ৰেণী<span class="text-danger">*</span> </label>
                                <select name="bride_category" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="ST(H)" <?=($bride_category=="ST(H)")?'selected':''?>>ST(H)</option>
                                    <option value="ST(P)" <?=($bride_category=="ST(P)")?'selected':''?>>ST(P)</option>
                                    <option value="SC" <?=($bride_category=="SC")?'selected':''?>>SC</option>
                                    <option value="OBC" <?=($bride_category=="OBC")?'selected':''?>>OBC</option>
                                    <option value="MOBC" <?=($bride_category=="MOBC")?'selected':''?>>MOBC</option>
                                    <option value="GENERAL" <?=($bride_category=="GENERAL")?'selected':''?>>GENERAL</option>
                                </select>
                                <?=form_error('bride_category')?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="bride_dob">Date of Birth/জন্ম তাৰিখ <span class="text-danger">*</span></label>
                                <input name="bride_dob" id="bride_dob" value="<?=$bride_dob?>" class="form-control" type="text" autocomplete="off" />
                                <?=form_error('bride_dob')?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Mobile Number/মোবাইল নম্বৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_mobile_number" value="<?=$bride_mobile_number?>" maxlength="10" />
                                <?= form_error("mother_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>E-Mail/ই-মেইল </label>
                                <input type="text" class="form-control" name="bride_email_id" value="<?=$bride_email_id?>" maxlength="100" />
                                <?= form_error("bride_email_id") ?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Person with Disability/অক্ষম ব্যক্তি<span class="text-danger">*</span> </label>
                                <select name="bride_disability" id="bride_disability" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="1" <?=($bride_disability=="1")?'selected':''?>>YES</option>
                                    <option value="2" <?=($bride_disability=="2")?'selected':''?>>NO</option>
                                </select>
                                <?= form_error("bride_disability") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>If Yes/ যদি হয়<span class="text-danger">*</span> </label>
                                <select name="bride_disability_type" id="bride_disability_type" class="form-control" <?=($bride_disability ==="2")?'disabled':''?>>
                                    <option value="">Please Select</option>
                                    <option value="Visually Impared" <?=($bride_disability_type=="Visually Impared")?'selected':''?>>Visually Impared</option>
                                    <option value="Differently Abled" <?=($bride_disability_type=="Differently Abled")?'selected':''?>>Differently Abled</option>
                                </select>
                                <?= form_error("bride_disability_type") ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <legend class="h5">Names of children from earlier marriage(if any)/পূৰ্বৰ বিবাহৰ সন্তানৰ নাম </legend>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="bride_children_tbl">
                                    <thead>
                                        <tr>
                                            <th>First name</th>
                                            <th>Middle name</th>
                                            <th>Last name</th>
                                            <th>Date of birth</th>
                                            <th>Address</th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $brideChild_firstNames = (isset($bride_child_first_name) && is_array($bride_child_first_name)) ? count($bride_child_first_name) : 0;
                                        if ($brideChild_firstNames > 0) {
                                            for ($i = 0; $i < $brideChild_firstNames; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="add_child_tbl_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button>';
                                                }// End of if else ?>
                                                <tr>
                                                    <td><input name="bride_child_first_name[]" value="<?= $bride_child_first_name[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="bride_child_middle_name[]" value="<?= $bride_child_middle_name[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="bride_child_last_name[]" value="<?= $bride_child_last_name[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="bride_child_dob[]" value="<?=(strlen($bride_child_dob[$i])==10)?date('d-m-Y', strtotime($bride_child_dob[$i])):''?>" class="form-control dp-post" type="text" /></td>
                                                    <td><input name="bride_child_address[]" value="<?= $bride_child_address[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><?= $btn ?></td>
                                                </tr>
                                                <?php }
                                            } else { ?>
                                            <tr>
                                                <td><input name="bride_child_first_name[]" class="form-control" type="text" /></td>
                                                <td><input name="bride_child_middle_name[]" class="form-control" type="text" /></td>
                                                <td><input name="bride_child_last_name[]" class="form-control" type="text" /></td>
                                                <td><input name="bride_child_dob[]" class="form-control dp-post" type="text" /></td>
                                                <td><input name="bride_child_address[]" class="form-control" type="text" /></td>
                                                <td style="text-align:center">
                                                    <button class="btn btn-info" id="add_child_tbl_row" type="button">
                                                        <i class="fa fa-plus-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                    <?php }//End of if else  ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <legend class="h5">Name of Dependent(if any)/নিৰ্ভৰশীলৰ নাম </legend>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="bride_dependents_tbl">
                                    <thead>
                                        <tr>
                                            <th>First name</th>
                                            <th>Middle name</th>
                                            <th>Last name</th>
                                            <th>Date of birth</th>
                                            <th>Address</th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $brideDependent_firstNames = (isset($bride_dependent_first_name) && is_array($bride_dependent_first_name)) ? count($bride_dependent_first_name) : 0;
                                        if ($brideDependent_firstNames > 0) {
                                            for ($i = 0; $i < $brideDependent_firstNames; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="add_dependents_tbl_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button>';
                                                }// End of if else ?>
                                                <tr>
                                                    <td><input name="bride_dependent_first_name[]" value="<?= $bride_dependent_first_name[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="bride_dependent_middle_name[]" value="<?= $bride_dependent_middle_name[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="bride_dependent_last_name[]" value="<?= $bride_dependent_last_name[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="bride_dependent_dob[]" value="<?=(strlen($bride_dependent_dob[$i])==10)?date('d-m-Y', strtotime($bride_dependent_dob[$i])):''?>" class="form-control dp-post" type="text" /></td>
                                                    <td><input name="bride_dependent_address[]" value="<?= $bride_dependent_address[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><?= $btn ?></td>
                                                </tr>
                                                <?php }
                                            } else { ?>
                                            <tr>
                                                <td><input name="bride_dependent_first_name[]" class="form-control" type="text" /></td>
                                                <td><input name="bride_dependent_middle_name[]" class="form-control" type="text" /></td>
                                                <td><input name="bride_dependent_last_name[]" class="form-control" type="text" /></td>
                                                <td><input name="bride_dependent_dob[]" class="form-control dp-post" type="text" /></td>
                                                <td><input name="bride_dependent_address[]" class="form-control" type="text" /></td>
                                                <td style="text-align:center">
                                                    <button class="btn btn-info" id="add_dependents_tbl_row" type="button">
                                                        <i class="fa fa-plus-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                    <?php }//End of if else  ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <label>Total Income of Parents (Both Father and Mother)/ পিতৃ-মাতৃৰ উপাৰ্জন<span class="text-danger">*</span> </label>
                                <select name="bride_dependent_income" id="bride_dependent_income" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="0" <?=($bride_dependent_income=="0")?'selected':''?>>0</option>
                                    <option value="Rs. 1 to Rs. 50000" <?=($bride_dependent_income=="Rs. 1 to Rs. 50000")?'selected':''?>>Rs. 1 to Rs. 50000</option>
                                    <option value="Rs. 50001 to Rs. 100000" <?=($bride_dependent_income=="Rs. 50001 to Rs. 100000")?'selected':''?>>Rs. 50001 to Rs. 100000</option>
                                    <option value="Rs. 100001 to Rs. 200000" <?=($bride_dependent_income=="Rs. 100001 to Rs. 200000")?'selected':''?>>Rs. 100001 to Rs. 200000</option>
                                    <option value="Rs. 200001 to Rs. 300000" <?=($bride_dependent_income=="Rs. 200001 to Rs. 300000")?'selected':''?>>Rs. 200001 to Rs. 300000</option>
                                    <option value="Rs. 300001 to Rs. 400000" <?=($bride_dependent_income=="Rs. 300001 to Rs. 400000")?'selected':''?>>Rs. 300001 to Rs. 400000</option>
                                    <option value="Rs. 400001 to Rs. 500000" <?=($bride_dependent_income=="Rs. 400001 to Rs. 500000")?'selected':''?>>Rs. 400001 to Rs. 500000</option>
                                    <option value="Rs. 500000 or more" <?=($bride_dependent_income=="Rs. 500000 or more")?'selected':''?>>Rs. 500000 or more</option>
                                </select>
                                <?=form_error('bride_dependent_income')?>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <legend class="h5">Bride Present Address/কইনাৰ বৰ্তমান ঠিকনা</legend>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Country/দেশ <span class="text-danger">*</span> </label>
                                <select name="bride_present_country" id="bride_present_country" class="form-control bride_country">
                                    <option value="">Please Select</option>
                                    <option value="India" <?=($bride_present_country === 'India')?'selected':''?>>India</option>
                                    <?php if($countries) {
                                        foreach($countries as $country) {
                                            if($country->country_name !== 'India') {
                                                $selected = ($bride_present_country === $country->country_name)?'selected':'';
                                                echo '<option value="'.$country->country_name.'" '.$selected.'>'.$country->country_name.'</option>';
                                            }
                                        }//End of foreach()
                                    }//End of if ?>
                                </select>
                                <?=form_error('bride_present_country')?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>State/ৰাজ্য <span class="text-danger">*</span> </label>
                                <div id="present_state_div">
                                    <select name="bride_present_state" id="bride_present_state" class="form-control" <?=($bride_present_country !== 'India')?'disabled':''?>>
                                        <option value="<?=$bride_present_state?>"><?=strlen($bride_present_state)?$bride_present_state_name:'Please Select'?></option>               
                                    </select>
                                </div>                                    
                                <?=form_error('bride_present_state')?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>State/ৰাজ্য/Province/প্ৰদেশ </label>
                                <input type="text" class="form-control" name="bride_present_state_foreign" id="bride_present_state_foreign" value="<?=$bride_present_state_foreign?>" maxlength="255" <?=($bride_present_country === 'India')?'disabled':''?> />
                                <?= form_error("bride_present_state_foreign") ?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>District/জিলা<span class="text-danger">*</span> </label>
                                <div id="present_district_div">
                                    <select name="bride_present_district" id="bride_present_district" class="form-control" <?=($bride_present_country !== 'India')?'disabled':''?>>
                                        <option value="<?=$bride_present_district?>"><?=strlen($bride_present_district)?$bride_present_district:'Please Select'?></option>                            
                                    </select>
                                </div>
                                <?=form_error('bride_present_district')?>
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <label>Village/Town/City/গাওঁ/চহৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_present_city" id="bride_present_city" value="<?=$bride_present_city?>" maxlength="255" />
                                <?= form_error("bride_present_city") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Police Station/থানা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_present_ps" id="bride_present_ps" value="<?=$bride_present_ps?>" maxlength="255" />
                                <?= form_error("bride_present_ps") ?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Post Office/ডাকঘৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_present_po" id="bride_present_po" value="<?=$bride_present_po?>" maxlength="255" />
                                <?= form_error("bride_present_po") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Address Line 1/ঠিকনা ৰেখা ১ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_present_address1" id="bride_present_address1" value="<?=$bride_present_address1?>" maxlength="255" <?=($bride_present_country === 'India')?'disabled':''?> />
                                <?= form_error("bride_present_address1") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Address Line 2/ঠিকনা ৰেখা ২<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_present_address2" id="bride_present_address2" value="<?=$bride_present_address2?>" maxlength="255" <?=($bride_present_country === 'India')?'disabled':''?> />
                                <?= form_error("bride_present_address2") ?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Pin Code/পিন <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_present_pin" id="bride_present_pin" value="<?=$bride_present_pin?>" maxlength="6" />
                                <?= form_error("bride_present_pin") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>LAC/ বিধান সভা সমষ্টি<span class="text-danger">*</span> </label>
                                <select name="bride_lac" id="bride_lac" class="form-control" <?=($bride_present_country !== 'India')?'disabled':''?>>
                                    <option value="">Please Select</option>
                                    <?php if($lacs) {
                                        foreach($lacs as $lac) {
                                            $lacObj = json_encode(array("lac_id"=>$lac->lac_id, "lac_name" => $lac->lac_name));
                                            $selected = ($lac_id === $lac->lac_id)?'selected':'';
                                            echo "<option value='".$lacObj."' ".$selected.">".$lac->lac_name."</option>";
                                        }//End of foreach()
                                    }//End of if ?>
                                </select>
                                <?=form_error('bride_lac')?>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-bordered bg-white">
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="text-center p-1">
                                                Residency period at present address <span class="text-danger">*</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center p-1">Years</td>
                                            <td class="text-center p-1">Months</td>
                                        </tr>
                                        <tr>
                                            <td class="p-1">
                                                <input name="bride_present_period_years" value="<?=$bride_present_period_years?>" maxlength="2" class="form-control text-center" type="text" />
                                                <?= form_error("bride_present_period_years") ?>
                                            </td>
                                            <td class="p-1">
                                                <input name="bride_present_period_months" value="<?=$bride_present_period_months?>" maxlength="2" class="form-control text-center" type="text" />
                                                <?= form_error("bride_present_period_months") ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>                                
                            </div>
                        </div>                                
                    </fieldset>
                    
                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <legend class="h5">Bride Permanent Address/কইনাৰ স্হায়ী ঠিকনা</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label for="bride_address_same">Same as Permanent Address / স্হায়ী ঠিকনাৰ সৈতে একেনে ?<span class="text-danger">*</span> </label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input bride_address_same" type="radio" name="bride_address_same" id="dcsYes" value="YES" <?=($bride_address_same === 'YES')?'checked':''?> />
                                    <label class="form-check-label" for="dcsYes">YES</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input bride_address_same" type="radio" name="bride_address_same" id="dcsNo" value="NO" <?=($bride_address_same === 'NO')?'checked':''?> />
                                    <label class="form-check-label" for="dcsNo">NO</label>
                                </div>
                                <?=form_error("bride_address_same")?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Country/দেশ <span class="text-danger">*</span> </label>
                                <select name="bride_permanent_country" id="bride_permanent_country" class="form-control bride_country">
                                <option value="">Please Select</option>
                                <option value="India" <?=($bride_permanent_country === 'India')?'selected':''?>>India</option>
                                <?php if($countries) {
                                    foreach($countries as $country) {
                                        if($country->country_name !== 'India') {
                                            $selected = ($bride_permanent_country === $country->country_name)?'selected':'';
                                            echo '<option value="'.$country->country_name.'" '.$selected.'>'.$country->country_name.'</option>';
                                        }
                                    }//End of foreach()
                                }//End of if ?>
                                </select>
                                <?=form_error('bride_permanent_country')?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>State/ৰাজ্য <span class="text-danger">*</span> </label>
                                <div id="permanent_state_div">
                                    <select name="bride_permanent_state" id="bride_permanent_state" class="form-control" <?=($bride_permanent_country !== 'India')?'disabled':''?>>
                                        <option value="<?=$bride_permanent_state?>"><?=strlen($bride_permanent_state)?$bride_permanent_state_name:'Please Select'?></option>               
                                    </select>
                                </div>
                                <?=form_error('bride_permanent_state')?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>State/ৰাজ্য/Province/প্ৰদেশ </label>
                                <input type="text" class="form-control" name="bride_permanent_state_foreign" id="bride_permanent_state_foreign" value="<?=$bride_permanent_state_foreign?>" maxlength="255" <?=($bride_permanent_country === 'India')?'disabled':''?> />
                                <?= form_error("bride_permanent_state_foreign") ?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>District/জিলা<span class="text-danger">*</span> </label>
                                <div id="permanent_district_div">
                                    <select name="bride_permanent_district" id="bride_permanent_district" class="form-control" <?=($bride_permanent_country !== 'India')?'disabled':''?>>
                                        <option value="<?=$bride_permanent_district?>"><?=strlen($bride_permanent_district)?$bride_permanent_district:'Please Select'?></option>
                                    </select>
                                </div>
                                <?=form_error('bride_permanent_district')?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Village/Town/City/গাওঁ/চহৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_permanent_city" id="bride_permanent_city" value="<?=$bride_permanent_city?>" maxlength="255" />
                                <?= form_error("bride_permanent_city") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Police Station/থানা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_permanent_ps" id="bride_permanent_ps" value="<?=$bride_permanent_ps?>" maxlength="255" />
                                <?= form_error("bride_permanent_ps") ?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Post Office/ডাকঘৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_permanent_po" id="bride_permanent_po" value="<?=$bride_permanent_po?>" maxlength="255" />
                                <?= form_error("bride_permanent_po") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Address Line 1/ঠিকনা ৰেখা ১ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_permanent_address1" id="bride_permanent_address1" value="<?=$bride_permanent_address1?>" maxlength="255" <?=($bride_permanent_country === 'India')?'disabled':''?> />
                                <?= form_error("bride_permanent_address1") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Address Line 2/ঠিকনা ৰেখা ২<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_permanent_address2" id="bride_permanent_address2" value="<?=$bride_permanent_address2?>" maxlength="255" <?=($bride_permanent_country === 'India')?'disabled':''?> />
                                <?= form_error("bride_permanent_address2") ?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Pin Code/পিন <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="bride_permanent_pin" id="bride_permanent_pin" value="<?=$bride_permanent_pin?>" maxlength="6" />
                                <?= form_error("bride_permanent_pin") ?>
                            </div>
                        </div>
                    </fieldset>
                     
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?=site_url('spservices/marriageregistration_landhub/applicantdetails/index/'.$obj_id)?>" class="btn btn-primary">
                        <i class="fa fa-angle-double-left"></i> Back
                    </a>
                    <button class="btn btn-success frmbtn" id="SAVE_NEXT" type="button">
                        <i class="fa fa-angle-double-right"></i> Save &amp; Next
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>