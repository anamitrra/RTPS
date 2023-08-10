<?php
$countries = $this->countries_model->get_rows(array());
$lacs = $this->lac_model->get_rows(array());

$obj_id = $dbrow->{'_id'}->{'$id'};
$rtps_trans_id = $dbrow->rtps_trans_id;

if(isset($dbrow->groom_first_name) && strlen($dbrow->groom_first_name)) {
    $title = "Edit Existing Information";
    $groom_prefix = $dbrow->groom_prefix??'';
    $groom_first_name = $dbrow->groom_first_name??'';
    $groom_middle_name = $dbrow->groom_middle_name??'';
    $groom_last_name = $dbrow->groom_last_name??'';                        
    $groom_father_prefix = $dbrow->groom_father_prefix??'';
    $groom_father_first_name = $dbrow->groom_father_first_name??'';
    $groom_father_middle_name = $dbrow->groom_father_middle_name??'';
    $groom_father_last_name = $dbrow->groom_father_last_name??'';                   
    $groom_mother_prefix = $dbrow->groom_mother_prefix??'';
    $groom_mother_first_name = $dbrow->groom_mother_first_name??'';
    $groom_mother_middle_name = $dbrow->groom_mother_middle_name??'';
    $groom_mother_last_name = $dbrow->groom_mother_last_name??'';

    $groom_status = $dbrow->groom_status??'';
    $groom_occupation = $dbrow->groom_occupation??'';
    $groom_category = $dbrow->groom_category??'';
    $groom_dob = $dbrow->groom_dob??'';
    $groom_mobile_number = $dbrow->groom_mobile_number??'';
    $groom_email_id = $dbrow->groom_email_id??'';
    $groom_disability = $dbrow->groom_disability??'';
    $groom_disability_type = $dbrow->groom_disability_type??'';
       
    $groom_children = $dbrow->groom_children??array();    
    $groom_child_first_name = array();
    $groom_child_middle_name = array();
    $groom_child_last_name= array();
    $groom_child_dob = array();
    $groom_child_address = array();    
    if(count($groom_children)) {
        foreach($groom_children as $bc) {
            //echo "OBJ : ".$bc_fname->patta_no."<br>";
            array_push($groom_child_first_name, $bc->groom_child_first_name);
            array_push($groom_child_middle_name, $bc->groom_child_middle_name);
            array_push($groom_child_last_name, $bc->groom_child_last_name);
            array_push($groom_child_dob, $bc->groom_child_dob);
            array_push($groom_child_address, $bc->groom_child_address);
        }//End of foreach()
    }//End of if

    $groom_dependents = $dbrow->groom_dependents??array();    
    $groom_dependent_first_name = array();
    $groom_dependent_middle_name = array();
    $groom_dependent_last_name= array();
    $groom_dependent_dob = array();
    $groom_dependent_address = array();    
    if(count($groom_dependents)) {
        foreach($groom_dependents as $bc) {
            //echo "OBJ : ".$bc_fname->patta_no."<br>";
            array_push($groom_dependent_first_name, $bc->groom_dependent_first_name);
            array_push($groom_dependent_middle_name, $bc->groom_dependent_middle_name);
            array_push($groom_dependent_last_name, $bc->groom_dependent_last_name);
            array_push($groom_dependent_dob, $bc->groom_dependent_dob);
            array_push($groom_dependent_address, $bc->groom_dependent_address);
        }//End of foreach()
    }//End of if
                        
    $groom_present_country = $dbrow->groom_present_country??'';
    $groom_present_state = $dbrow->groom_present_state??'';
    $groom_present_state_name = $dbrow->groom_present_state_name??'';
    $groom_present_state_foreign = $dbrow->groom_present_state_foreign??'';
    $groom_present_district = $dbrow->groom_present_district??'';
    $groom_present_city = $dbrow->groom_present_city??'';
    $groom_present_ps = $dbrow->groom_present_ps??'';
    $groom_present_po = $dbrow->groom_present_po??'';
    $groom_present_address1 = $dbrow->groom_present_address1??'';
    $groom_present_address2 = $dbrow->groom_present_address2??'';
    $groom_present_pin = $dbrow->groom_present_pin??'';
    $groom_lac = $dbrow->groom_lac??'';
    $lac_id = $groom_lac->lac_id??'';
    $groom_present_period_years = $dbrow->groom_present_period_years??'';
    $groom_present_period_months = $dbrow->groom_present_period_months??'';

    $groom_address_same = $dbrow->groom_address_same??'';
    $groom_permanent_country = $dbrow->groom_permanent_country??'';
    $groom_permanent_state = $dbrow->groom_permanent_state??'';
    $groom_permanent_state_name = $dbrow->groom_permanent_state_name??'';
    $groom_permanent_state_foreign = $dbrow->groom_permanent_state_foreign??'';
    $groom_permanent_district = $dbrow->groom_permanent_district??'';
    $groom_permanent_city = $dbrow->groom_permanent_city??'';
    $groom_permanent_ps = $dbrow->groom_permanent_ps??'';
    $groom_permanent_po = $dbrow->groom_permanent_po??'';
    $groom_permanent_address1 = $dbrow->groom_permanent_address1??'';
    $groom_permanent_address2 = $dbrow->groom_permanent_address2??'';
    $groom_permanent_pin = $dbrow->groom_permanent_pin??'';
} else {
    $title = "New Applicant Registration";
    $groom_prefix = set_value("groom_prefix");
    $groom_first_name = set_value("groom_first_name");
    $groom_middle_name = set_value("groom_middle_name");
    $groom_last_name = set_value("groom_last_name");                        
    $groom_father_prefix = set_value("groom_father_prefix");
    $groom_father_first_name = set_value("groom_father_first_name");
    $groom_father_middle_name = set_value("groom_father_middle_name");
    $groom_father_last_name = set_value("groom_father_last_name");                   
    $groom_mother_prefix = set_value("groom_mother_prefix");
    $groom_mother_first_name = set_value("groom_mother_first_name");
    $groom_mother_middle_name = set_value("groom_mother_middle_name");
    $groom_mother_last_name = set_value("groom_mother_last_name");

    $groom_status = set_value("groom_status");
    $groom_occupation = set_value("groom_occupation");
    $groom_category = set_value("groom_category");
    $groom_dob = set_value("groom_dob");
    $groom_mobile_number = set_value("groom_mobile_number");
    $groom_email_id = set_value("groom_email_id");
    $groom_disability = set_value("groom_disability");
    $groom_disability_type = set_value("groom_disability_type");

    $groom_child_first_name = set_value("groom_child_first_name");
    $groom_child_middle_name = set_value("groom_child_middle_name");
    $groom_child_last_name = set_value("groom_child_last_name");
    $groom_child_dob = set_value("groom_child_dob");
    $groom_child_address = set_value("groom_child_address");

    $groom_dependent_first_name = set_value("groom_dependent_first_name");
    $groom_dependent_middle_name = set_value("groom_dependent_middle_name");
    $groom_dependent_last_name = set_value("groom_dependent_last_name");
    $groom_dependent_dob = set_value("groom_dependent_dob");
    $groom_dependent_address = set_value("groom_dependent_address");
                        
    $groom_present_country = set_value("groom_present_country");
    $groom_present_state = set_value("groom_present_state");
    $groom_present_state_name = set_value("groom_present_state_name");
    $groom_present_state_foreign = set_value("groom_present_state_foreign");
    $groom_present_district = set_value("groom_present_district");
    $groom_present_city = set_value("groom_present_city");
    $groom_present_ps = set_value("groom_present_ps");
    $groom_present_po = set_value("groom_present_po");
    $groom_present_address1 = set_value("groom_present_address1");
    $groom_present_address2 = set_value("groom_present_address2");
    $groom_present_pin = set_value("groom_present_pin");
    $groom_lac = set_value("groom_lac");    
    if(strlen($groom_lac)) {
        $groomLac = json_decode(html_entity_decode($groom_lac));
        $lac_id = $groomLac->lac_id;
    } else {
        $lac_id = '';
    }//End of if else
    $groom_present_period_years = set_value("groom_present_period_years");
    $groom_present_period_months = set_value("groom_present_period_months");

    $groom_address_same = set_value("groom_address_same");
    $groom_permanent_country = set_value("groom_permanent_country");
    $groom_permanent_state = set_value("groom_permanent_state");
    $groom_permanent_state_name = set_value("groom_permanent_state_name");
    $groom_permanent_state_foreign = set_value("groom_permanent_state_foreign");
    $groom_permanent_district = set_value("groom_permanent_district");
    $groom_permanent_city = set_value("groom_permanent_city");
    $groom_permanent_ps = set_value("groom_permanent_ps");
    $groom_permanent_po = set_value("groom_permanent_po");
    $groom_permanent_address1 = set_value("groom_permanent_address1");
    $groom_permanent_address2 = set_value("groom_permanent_address2");
    $groom_permanent_pin = set_value("groom_permanent_pin");
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
        
        $(document).on("change", "#groom_disability", function(){
            let checkedVal = $(this).val(); //alert(checkedVal);
            if(checkedVal === "1") {
                $("#groom_disability_type").prop('disabled', false);
            } else {
                $("#groom_disability_type").val("");
                $("#groom_disability_type").prop('disabled', true);
            }//End of if else
        }); //End of #groom_disability change()
                
        $(document).on("click", "#add_child_tbl_row", function(){
            let totRows = $('#groom_children_tbl tr').length;
            var trow = `<tr>
                            <td><input name="groom_child_first_name[]" class="form-control" type="text" /></td>
                            <td><input name="groom_child_middle_name[]" class="form-control" type="text" /></td>
                            <td><input name="groom_child_last_name[]" class="form-control" type="text" /></td>
                            <td><input name="groom_child_dob[]" class="form-control dp-post" type="text" /></td>
                            <td><input name="groom_child_address[]" class="form-control" type="text" /></td>
                            <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if(totRows <= 5) {
                $('#groom_children_tbl tr:last').after(trow);
                $(".dp-post").datepicker({
                    format: 'dd-mm-yyyy',
                    endDate: '+0d',
                    autoclose: true
                });
            }
        });
                
        $(document).on("click", "#add_dependents_tbl_row", function(){
            let totRows = $('#groom_dependents_tbl tr').length;
            var trow = `<tr>
                            <td><input name="groom_dependent_first_name[]" class="form-control" type="text" /></td>
                            <td><input name="groom_dependent_middle_name[]" class="form-control" type="text" /></td>
                            <td><input name="groom_dependent_last_name[]" class="form-control" type="text" /></td>
                            <td><input name="groom_dependent_dob[]" class="form-control dp-post" type="text" /></td>
                            <td><input name="groom_dependent_address[]" class="form-control" type="text" /></td>
                            <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if(totRows <= 5) {
                $('#groom_dependents_tbl tr:last').after(trow);
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
        
        $("#groom_dob").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '-7665d', //21 yrs
            autoclose: true
        });  
        
        $(".dp-post").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '+0d',
            autoclose: true
        });
            
        $(document).on("change", ".groom_country", function(){
            let field_name = $(this).attr("id"); //alert(field_name);
            let selectedText = $(this).find("option:selected").text();
            var field_names = field_name.split('_'); //alert(field_names[1]);
            if(selectedText === 'India') {
                $("#groom_"+field_names[1]+"_state").prop("disabled", false);     
                $("#groom_"+field_names[1]+"_district").prop("disabled", false);    
                
                $("#groom_"+field_names[1]+"_state_foreign").val("");                
                $("#groom_"+field_names[1]+"_state_foreign").prop("disabled", true);
                $("#groom_"+field_names[1]+"_ps").prop("disabled", false);
                $("#groom_"+field_names[1]+"_po").prop("disabled", false);
                $("#groom_"+field_names[1]+"_address1").val("");
                $("#groom_"+field_names[1]+"_address1").prop("disabled", true);
                $("#groom_"+field_names[1]+"_address2").val(""); 
                $("#groom_"+field_names[1]+"_address2").prop("disabled", true);    
                
                $("#groom_lac").prop("disabled", false);
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/marriageregistration/bridedetails/get_states')?>",
                    data: {"field_name":"groom_"+field_names[1]+"_state", "field_value":selectedText},
                    beforeSend:function(){
                        $("#"+field_names[1]+"_state_div").html("Loading");
                    },
                    success:function(res){
                        $("#"+field_names[1]+"_state_div").html(res);
                    }
                });
            } else {
                $("#groom_"+field_names[1]+"_state").val("");
                $("#groom_"+field_names[1]+"_state").prop("disabled", true);
                $("#groom_"+field_names[1]+"_district").val("");
                $("#groom_"+field_names[1]+"_district").prop("disabled", true);
                $("#groom_lac").val("");
                $("#groom_"+field_names[1]+"_state_foreign").prop("disabled", false);
                $("#groom_"+field_names[1]+"_ps").val("");
                $("#groom_"+field_names[1]+"_ps").prop("disabled", true);
                $("#groom_"+field_names[1]+"_po").val("");
                $("#groom_"+field_names[1]+"_po").prop("disabled", true);
                $("#groom_"+field_names[1]+"_address1").prop("disabled", false);
                $("#groom_"+field_names[1]+"_address2").prop("disabled", false);
            }//End of if else
        });//End of onchange .groom_country
        
        var getDistricts = function(slc, fieldName) {
            //alert(slc+" @ "+fieldName);
            if(slc.length) {
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/marriageregistration/bridedetails/get_districts')?>",
                    data: {
                        "field_name":"groom_"+fieldName+"_district", 
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
                
        $(document).on("change", "#groom_present_state", function(){
            let selectedState = $(this).val();
            getDistricts(selectedState, "present");
            $("#groom_present_state_name").val($(this).find("option:selected").text());
        });
        
        $(document).on("change", "#groom_permanent_state", function(){
            let selectedState = $(this).val();
            getDistricts(selectedState, "permanent");
            $("#groom_permanent_state_name").val($(this).find("option:selected").text());
        });
                  
        $(document).on("change", ".groom_address_same", function(){                
            let checkedVal = $(this).val(); //alert(checkedVal);
            if(checkedVal === "YES") {
                $('#groom_permanent_country').val($("#groom_present_country").val());
                
                let stateId = $("#groom_present_state").find("option:selected").val();
                let stateName = $("#groom_present_state").find("option:selected").text();
                $('#groom_permanent_state').empty().append('<option value="'+stateId+'">'+stateName+'</option>');
                $('#groom_permanent_state').prop('disabled', false);
                
                $('#groom_permanent_state_name').val($('#groom_present_state_name').val());                
                $('#groom_permanent_state_foreign').val($('#groom_present_state_foreign').val());
                $('#groom_permanent_state_foreign').prop('disabled', $('#groom_present_state_foreign').prop('disabled'));
                
                let district = $('#groom_present_district').find("option:selected").val();
                $('#groom_permanent_district').empty().append('<option value="'+district+'">'+district+'</option>');
                $('#groom_permanent_district').prop('disabled', false);
                
                $('#groom_permanent_city').val($('#groom_present_city').val());
                $('#groom_permanent_ps').val($('#groom_present_ps').val());
                $('#groom_permanent_po').val($('#groom_present_po').val());
                $('#groom_permanent_address1').val($('#groom_present_address1').val());
                $('#groom_permanent_address1').prop('disabled', $('#groom_present_address1').prop('disabled'));                
                $('#groom_permanent_address2').val($('#groom_present_address2').val());
                $('#groom_permanent_address2').prop('disabled', $('#groom_present_address2').prop('disabled'));
                $('#groom_permanent_pin').val($('#groom_present_pin').val());
            } else {
                $('#groom_permanent_country').val('');
                $('#groom_permanent_state').val('');
                $('#groom_permanent_state_name').val('');
                $('#groom_permanent_state_foreign').val('');
                $('#groom_permanent_state_foreign').prop('disabled', false);  
                $('#groom_permanent_district').val('');
                $('#groom_permanent_city').val('');
                $('#groom_permanent_ps').val('');
                $('#groom_permanent_po').val('');
                $('#groom_permanent_address1').val('');
                $('#groom_permanent_address1').prop('disabled', false);  
                $('#groom_permanent_address2').val('');
                $('#groom_permanent_address2').prop('disabled', false); 
                $('#groom_permanent_pin').val('');
            }//End of if else
        });//End of onChange .groom_address_same
        
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/marriageregistration_landhub/groomdetails/submit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="rtps_trans_id" value="<?=$rtps_trans_id?>" type="hidden" />
            <input name="groom_present_state_name" id="groom_present_state_name" value="<?=$groom_present_state_name?>" type="hidden" />
            <input name="groom_permanent_state_name" id="groom_permanent_state_name" value="<?=$groom_permanent_state_name?>" type="hidden" />
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
                        <legend class="h5">Groom Details/দৰাৰ বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Prefix/ উপসৰ্গ <span class="text-danger">*</span> </label>
                                <select name="groom_prefix" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Mr." <?=($groom_prefix=="Mr.")?'selected':''?>>Mr.</option>
                                    <option value="Sri" <?=($groom_prefix=="Sri")?'selected':''?>>Sri</option>
                                    <option value="Dr." <?=($groom_prefix=="Dr.")?'selected':''?>>Dr.</option>
                                </select>
                                <?=form_error('groom_prefix')?>
                            </div>

                            <div class="col-md-3">
                                <label>Groom First Name/দৰা প্ৰথম নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_first_name" id="father_name" value="<?= $groom_first_name ?>" maxlength="255" />
                                <?= form_error("groom_first_name") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Groom Middle Name/দৰা মধ্য নাম </label>
                                <input type="text" class="form-control" name="groom_middle_name" value="<?=$groom_middle_name?>" maxlength="255" />
                                <?= form_error("groom_middle_name") ?>
                            </div>

                            <div class="col-md-3">
                                <label>Groom Last Name/দৰা অন্তিম নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_last_name" value="<?=$groom_last_name?>" maxlength="255" />
                                <?= form_error("groom_last_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Prefix/ উপসৰ্গ <span class="text-danger">*</span> </label>
                                <select name="groom_father_prefix" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Mr." <?=($groom_father_prefix=="Mr.")?'selected':''?>>Mr.</option>
                                    <option value="Sri" <?=($groom_father_prefix=="Sri")?'selected':''?>>Sri.</option>
                                    <option value="Dr." <?=($groom_father_prefix=="Dr.")?'selected':''?>>Dr.</option>
                                    <option value="Late" <?=($groom_father_prefix=="Late")?'selected':''?>>Late</option>
                                </select>
                                <?=form_error('groom_father_prefix')?>
                            </div>

                            <div class="col-md-3">
                                <label>Father's First Name/পিতাৰ প্ৰথম নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_father_first_name" value="<?= $groom_father_first_name ?>" maxlength="255" />
                                <?= form_error("groom_father_first_name") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Father's Middle Name/ পিতাৰ মধ্য নাম\ </label>
                                <input type="text" class="form-control" name="groom_father_middle_name" value="<?=$groom_father_middle_name?>" maxlength="255" />
                                <?= form_error("groom_father_middle_name") ?>
                            </div>

                            <div class="col-md-3">
                                <label>Father's Last Name/ পিতাৰ অন্তিম নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_father_last_name" value="<?=$groom_father_last_name?>" maxlength="255" />
                                <?= form_error("groom_father_last_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Prefix/ উপসৰ্গ <span class="text-danger">*</span> </label>
                                <select name="groom_mother_prefix" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Dr." <?=($groom_mother_prefix=="Dr.")?'selected':''?>>Dr.</option>
                                    <option value="Mrs." <?=($groom_mother_prefix=="Mrs.")?'selected':''?>>Mrs.</option>
                                    <option value="Miss" <?=($groom_mother_prefix=="Miss")?'selected':''?>>Miss</option>
                                    <option value="Late" <?=($groom_mother_prefix=="Late")?'selected':''?>>Late</option>
                                </select>
                                <?=form_error('groom_mother_prefix')?>
                            </div>

                            <div class="col-md-3">
                                <label>Mother's First Name/মাতৃৰ প্ৰথম নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_mother_first_name" value="<?= $groom_mother_first_name ?>" maxlength="255" />
                                <?= form_error("groom_mother_first_name") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Mother's Middle Name/ মাতৃৰ মধ্য নাম </label>
                                <input type="text" class="form-control" name="groom_mother_middle_name" value="<?=$groom_mother_middle_name?>" maxlength="255" />
                                <?= form_error("groom_mother_middle_name") ?>
                            </div>

                            <div class="col-md-3">
                                <label>Mother Last Name/ মাতৃৰ অন্তিম নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_mother_last_name" value="<?=$groom_mother_last_name?>" maxlength="255" />
                                <?= form_error("groom_mother_last_name") ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Groom Status/দৰাৰ স্থিতি <span class="text-danger">*</span> </label>
                                <select name="groom_status" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Unmarried" <?=($groom_status=="Unmarried")?'selected':''?>>Unmarried</option>
                                    <option value="Widower" <?=($groom_status=="Widower")?'selected':''?>>Widower</option>
                                    <option value="Widow" <?=($groom_status=="Widow")?'selected':''?>>Widow</option>
                                    <option value="Divorcee" <?=($groom_status=="Divorcee")?'selected':''?>>Divorcee</option>
                                    <option value="Married" <?=($groom_status=="Married")?'selected':''?>>Married</option>
                                </select>
                                <?=form_error('groom_status')?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Occupation/বৃত্তি <span class="text-danger">*</span> </label>
                                <select name="groom_occupation" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Govt. Service" <?=($groom_occupation=="Govt. Service")?'selected':''?>>Govt. Service</option>
                                    <option value="Private Service" <?=($groom_occupation=="Private Service")?'selected':''?>>Private Service</option>
                                    <option value="Business" <?=($groom_occupation=="Business")?'selected':''?>>Business</option>
                                    <option value="Lawyers" <?=($groom_occupation=="Lawyers")?'selected':''?>>Lawyers</option>
                                    <option value="Doctors" <?=($groom_occupation=="Doctors")?'selected':''?>>Doctors</option>
                                    <option value="Other" <?=($groom_occupation=="Other")?'selected':''?>>Other</option>
                                </select>
                                <?=form_error('groom_occupation')?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Category/শ্ৰেণী<span class="text-danger">*</span> </label>
                                <select name="groom_category" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="ST(H)" <?=($groom_category=="ST(H)")?'selected':''?>>ST(H)</option>
                                    <option value="ST(P)" <?=($groom_category=="ST(P)")?'selected':''?>>ST(P)</option>
                                    <option value="SC" <?=($groom_category=="SC")?'selected':''?>>SC</option>
                                    <option value="OBC" <?=($groom_category=="OBC")?'selected':''?>>OBC</option>
                                    <option value="MOBC" <?=($groom_category=="MOBC")?'selected':''?>>MOBC</option>
                                    <option value="GENERAL" <?=($groom_category=="GENERAL")?'selected':''?>>GENERAL</option>
                                </select>
                                <?=form_error('groom_category')?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="groom_dob">Date of Birth/জন্ম তাৰিখ <span class="text-danger">*</span></label>
                                <input name="groom_dob" id="groom_dob" value="<?=$groom_dob?>" class="form-control" type="text" autocomplete="off" />
                                <?=form_error('groom_dob')?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Mobile Number/মোবাইল নম্বৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_mobile_number" value="<?=$groom_mobile_number?>" maxlength="10" />
                                <?= form_error("mother_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>E-Mail/ই-মেইল </label>
                                <input type="text" class="form-control" name="groom_email_id" value="<?=$groom_email_id?>" maxlength="100" />
                                <?= form_error("groom_email_id") ?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Person with Disability/অক্ষম ব্যক্তি<span class="text-danger">*</span> </label>
                                <select name="groom_disability" id="groom_disability" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="1" <?=($groom_disability=="1")?'selected':''?>>YES</option>
                                    <option value="2" <?=($groom_disability=="2")?'selected':''?>>NO</option>
                                </select>
                                <?= form_error("groom_disability") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>If Yes/ যদি হয়<span class="text-danger">*</span> </label>
                                <select name="groom_disability_type" id="groom_disability_type" class="form-control" <?=($groom_disability === "2")?'disabled':''?>>
                                    <option value="">Please Select</option>
                                    <option value="Visually Impared" <?=($groom_disability_type=="Visually Impared")?'selected':''?>>Visually Impared</option>
                                    <option value="Differently Abled" <?=($groom_disability_type=="Differently Abled")?'selected':''?>>Differently Abled</option>
                                </select>
                                <?= form_error("groom_disability_type") ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <legend class="h5">Names of children from earlier marriage(if any)/পূৰ্বৰ বিবাহৰ সন্তানৰ নাম </legend>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="groom_children_tbl">
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
                                        $groomChild_firstNames = (isset($groom_child_first_name) && is_array($groom_child_first_name)) ? count($groom_child_first_name) : 0;
                                        if ($groomChild_firstNames > 0) {
                                            for ($i = 0; $i < $groomChild_firstNames; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="add_child_tbl_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button>';
                                                }// End of if else ?>
                                                <tr>
                                                    <td><input name="groom_child_first_name[]" value="<?= $groom_child_first_name[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="groom_child_middle_name[]" value="<?= $groom_child_middle_name[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="groom_child_last_name[]" value="<?= $groom_child_last_name[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="groom_child_dob[]" value="<?=(strlen($groom_child_dob[$i])==10)?date('d-m-Y', strtotime($groom_child_dob[$i])):'' ?>" class="form-control dp-post" type="text" /></td>
                                                    <td><input name="groom_child_address[]" value="<?= $groom_child_address[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><?= $btn ?></td>
                                                </tr>
                                                <?php }
                                            } else { ?>
                                            <tr>
                                                <td><input name="groom_child_first_name[]" class="form-control" type="text" /></td>
                                                <td><input name="groom_child_middle_name[]" class="form-control" type="text" /></td>
                                                <td><input name="groom_child_last_name[]" class="form-control" type="text" /></td>
                                                <td><input name="groom_child_dob[]" class="form-control dp-post" type="text" /></td>
                                                <td><input name="groom_child_address[]" class="form-control" type="text" /></td>
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
                                <table class="table table-bordered" id="groom_dependents_tbl">
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
                                        $groomDependent_firstNames = (isset($groom_dependent_first_name) && is_array($groom_dependent_first_name)) ? count($groom_dependent_first_name) : 0;
                                        if ($groomDependent_firstNames > 0) {
                                            for ($i = 0; $i < $groomDependent_firstNames; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="add_dependents_tbl_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button>';
                                                }// End of if else ?>
                                                <tr>
                                                    <td><input name="groom_dependent_first_name[]" value="<?= $groom_dependent_first_name[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="groom_dependent_middle_name[]" value="<?= $groom_dependent_middle_name[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="groom_dependent_last_name[]" value="<?= $groom_dependent_last_name[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><input name="groom_dependent_dob[]" value="<?=(strlen($groom_dependent_dob[$i])==10)?date('d-m-Y', strtotime($groom_dependent_dob[$i])):''?>" class="form-control dp-post" type="text" /></td>
                                                    <td><input name="groom_dependent_address[]" value="<?= $groom_dependent_address[$i] ?>" class="form-control" type="text" /></td>
                                                    <td><?= $btn ?></td>
                                                </tr>
                                                <?php }
                                            } else { ?>
                                            <tr>
                                                <td><input name="groom_dependent_first_name[]" class="form-control" type="text" /></td>
                                                <td><input name="groom_dependent_middle_name[]" class="form-control" type="text" /></td>
                                                <td><input name="groom_dependent_last_name[]" class="form-control" type="text" /></td>
                                                <td><input name="groom_dependent_dob[]" class="form-control dp-post" type="text" /></td>
                                                <td><input name="groom_dependent_address[]" class="form-control" type="text" /></td>
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
                    </fieldset>
                    
                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <legend class="h5">Groom Present Address/দৰাৰ বৰ্তমান ঠিকনা</legend>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Country/দেশ <span class="text-danger">*</span> </label>
                                <select name="groom_present_country" id="groom_present_country" class="form-control groom_country">
                                    <option value="">Please Select</option>
                                    <option value="India" <?=($groom_present_country === 'India')?'selected':''?>>India</option>
                                    <?php if($countries) {
                                        foreach($countries as $country) {
                                            if($country->country_name !== 'India') {
                                                $selected = ($groom_present_country === $country->country_name)?'selected':'';
                                                echo '<option value="'.$country->country_name.'" '.$selected.'>'.$country->country_name.'</option>';
                                            }
                                        }//End of foreach()
                                    }//End of if ?>
                                </select>
                                <?=form_error('groom_present_country')?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>State/ৰাজ্য <span class="text-danger">*</span> </label>
                                <div id="present_state_div">
                                    <select name="groom_present_state" id="groom_present_state" class="form-control" <?=($groom_present_country !== 'India')?'disabled':''?>>
                                        <option value="<?=$groom_present_state?>"><?=strlen($groom_present_state)?$groom_present_state_name:'Please Select'?></option>               
                                    </select>
                                </div>                                    
                                <?=form_error('groom_present_state')?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>State/ৰাজ্য/Province/প্ৰদেশ </label>
                                <input type="text" class="form-control" name="groom_present_state_foreign" id="groom_present_state_foreign" value="<?=$groom_present_state_foreign?>" maxlength="255" <?=($groom_present_country === 'India')?'disabled':''?> />
                                <?= form_error("groom_present_state_foreign") ?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>District/জিলা<span class="text-danger">*</span> </label>
                                <div id="present_district_div">
                                    <select name="groom_present_district" id="groom_present_district" class="form-control" <?=($groom_present_country !== 'India')?'disabled':''?>>
                                        <option value="<?=$groom_present_district?>"><?=strlen($groom_present_district)?$groom_present_district:'Please Select'?></option>                            
                                    </select>
                                </div>
                                <?=form_error('groom_present_district')?>
                            </div>
                            
                            <div class="col-md-4 form-group">
                                <label>Village/Town/City/গাওঁ/চহৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_present_city" id="groom_present_city" value="<?=$groom_present_city?>" maxlength="255" />
                                <?= form_error("groom_present_city") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Police Station/থানা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_present_ps" id="groom_present_ps" value="<?=$groom_present_ps?>" maxlength="255" />
                                <?= form_error("groom_present_ps") ?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Post Office/ডাকঘৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_present_po" id="groom_present_po" value="<?=$groom_present_po?>" maxlength="255" />
                                <?= form_error("groom_present_po") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Address Line 1/ঠিকনা ৰেখা ১ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_present_address1" id="groom_present_address1" value="<?=$groom_present_address1?>" maxlength="255" <?=($groom_present_country === 'India')?'disabled':''?> />
                                <?= form_error("groom_present_address1") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Address Line 2/ঠিকনা ৰেখা ২<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_present_address2" id="groom_present_address2" value="<?=$groom_present_address2?>" maxlength="255" <?=($groom_present_country === 'India')?'disabled':''?> />
                                <?= form_error("groom_present_address2") ?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Pin Code/পিন <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_present_pin" id="groom_present_pin" value="<?=$groom_present_pin?>" maxlength="6" />
                                <?= form_error("groom_present_pin") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>LAC/ বিধান সভা সমষ্টি<span class="text-danger">*</span> </label>
                                <select name="groom_lac" id="groom_lac" class="form-control" <?=($groom_present_country !== 'India')?'disabled':''?>>
                                    <option value="">Please Select</option>
                                    <?php if($lacs) {
                                        foreach($lacs as $lac) {
                                            $lacObj = json_encode(array("lac_id"=>$lac->lac_id, "lac_name" => $lac->lac_name));
                                            $selected = ($lac_id === $lac->lac_id)?'selected':'';
                                            echo "<option value='".$lacObj."' ".$selected.">".$lac->lac_name."</option>";
                                        }//End of foreach()
                                    }//End of if ?>
                                </select>
                                <?=form_error('groom_lac')?>
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
                                                <input name="groom_present_period_years" value="<?=$groom_present_period_years?>" maxlength="2" class="form-control text-center" type="text" />
                                                <?= form_error("groom_present_period_years") ?>
                                            </td>
                                            <td class="p-1">
                                                <input name="groom_present_period_months" value="<?=$groom_present_period_months?>" maxlength="2" class="form-control text-center" type="text" />
                                                <?= form_error("groom_present_period_months") ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>                                
                            </div>
                        </div>                                
                    </fieldset>
                    
                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <legend class="h5">Groom Permanent Address/দৰাৰ স্হায়ী ঠিকনা</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label for="groom_address_same">Same as Permanent Address / স্হায়ী ঠিকনাৰ সৈতে একেনে ?<span class="text-danger">*</span> </label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input groom_address_same" type="radio" name="groom_address_same" id="dcsYes" value="YES" <?=($groom_address_same === 'YES')?'checked':''?> />
                                    <label class="form-check-label" for="dcsYes">YES</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input groom_address_same" type="radio" name="groom_address_same" id="dcsNo" value="NO" <?=($groom_address_same === 'NO')?'checked':''?> />
                                    <label class="form-check-label" for="dcsNo">NO</label>
                                </div>
                                <?=form_error("groom_address_same")?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Country/দেশ <span class="text-danger">*</span> </label>
                                <select name="groom_permanent_country" id="groom_permanent_country" class="form-control groom_country">
                                <option value="">Please Select</option>
                                <option value="India" <?=($groom_permanent_country === 'India')?'selected':''?>>India</option>
                                    <?php if($countries) {
                                        foreach($countries as $country) {
                                            if($country->country_name !== 'India') {
                                                $selected = ($groom_permanent_country === $country->country_name)?'selected':'';
                                                echo '<option value="'.$country->country_name.'" '.$selected.'>'.$country->country_name.'</option>';
                                            }
                                        }//End of foreach()
                                    }//End of if ?>
                                </select>
                                <?=form_error('groom_permanent_country')?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>State/ৰাজ্য <span class="text-danger">*</span> </label>
                                <div id="permanent_state_div">
                                    <select name="groom_permanent_state" id="groom_permanent_state" class="form-control" <?=($groom_permanent_country !== 'India')?'disabled':''?>>
                                        <option value="<?=$groom_permanent_state?>"><?=strlen($groom_permanent_state)?$groom_permanent_state_name:'Please Select'?></option>               
                                    </select>
                                </div>
                                <?=form_error('groom_permanent_state')?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>State/ৰাজ্য/Province/প্ৰদেশ </label>
                                <input type="text" class="form-control" name="groom_permanent_state_foreign" id="groom_permanent_state_foreign" value="<?=$groom_permanent_state_foreign?>" maxlength="255" <?=($groom_permanent_country === 'India')?'disabled':''?> />
                                <?= form_error("groom_permanent_state_foreign") ?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>District/জিলা<span class="text-danger">*</span> </label>
                                <div id="permanent_district_div">
                                    <select name="groom_permanent_district" id="groom_permanent_district" class="form-control" <?=($groom_permanent_country !== 'India')?'disabled':''?>>
                                        <option value="<?=$groom_permanent_district?>"><?=strlen($groom_permanent_district)?$groom_permanent_district:'Please Select'?></option>
                                    </select>
                                </div>
                                <?=form_error('groom_permanent_district')?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Village/Town/City/গাওঁ/চহৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_permanent_city" id="groom_permanent_city" value="<?=$groom_permanent_city?>" maxlength="255" />
                                <?= form_error("groom_permanent_city") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Police Station/থানা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_permanent_ps" id="groom_permanent_ps" value="<?=$groom_permanent_ps?>" maxlength="255" />
                                <?= form_error("groom_permanent_ps") ?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Post Office/ডাকঘৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_permanent_po" id="groom_permanent_po" value="<?=$groom_permanent_po?>" maxlength="255" />
                                <?= form_error("groom_permanent_po") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Address Line 1/ঠিকনা ৰেখা ১ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_permanent_address1" id="groom_permanent_address1" value="<?=$groom_permanent_address1?>" maxlength="255" <?=($groom_permanent_country === 'India')?'disabled':''?> />
                                <?= form_error("groom_permanent_address1") ?>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Address Line 2/ঠিকনা ৰেখা ২<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_permanent_address2" id="groom_permanent_address2" value="<?=$groom_permanent_address2?>" maxlength="255" <?=($groom_permanent_country === 'India')?'disabled':''?> />
                                <?= form_error("groom_permanent_address2") ?>
                            </div>
                        </div><!-- End of .row -->
                        
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Pin Code/পিন <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="groom_permanent_pin" id="groom_permanent_pin" value="<?=$groom_permanent_pin?>" maxlength="6" />
                                <?= form_error("groom_permanent_pin") ?>
                            </div>
                        </div>
                    </fieldset>
                     
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?=site_url('spservices/marriageregistration_landhub/bridedetails/index/'.$obj_id)?>" class="btn btn-primary">
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