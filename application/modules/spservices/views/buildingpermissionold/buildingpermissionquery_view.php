<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
// $apiURL= "http://13.233.207.96/MMSGNA-TEST/api/"; //For testing
$apiURL= "https://rtps.mmsgna.in/api/"; //For testing

if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;

    $application_type = $dbrow->form_data->application_type;
    $case_type = $dbrow->form_data->case_type;
    $ertp = $dbrow->form_data->ertp;
    $any_old_permission = $dbrow->form_data->any_old_permission;
    $technical_person_name = $dbrow->form_data->technical_person_name;
    $old_permission_no = $dbrow->form_data->old_permission_no;
    $district_emp_tech = $dbrow->form_data->district_emp_tech;
    $district_emp_tech_name = $dbrow->form_data->district_emp_tech_name;
    $empanelled_reg_tech_person = $dbrow->form_data->empanelled_reg_tech_person;
    $empanelled_reg_tech_person_name = $dbrow->form_data->empanelled_reg_tech_person_name;

    $district = $dbrow->form_data->district;
    $district_name = $dbrow->form_data->district_name;
    $house_no = $dbrow->form_data->house_no;
    $mst_pln_dev_auth = $dbrow->form_data->mst_pln_dev_auth;
    $mst_pln_dev_auth_name = $dbrow->form_data->mst_pln_dev_auth_name;
    $name_of_road = $dbrow->form_data->name_of_road;
    $panchayat_ulb = $dbrow->form_data->panchayat_ulb;
    $panchayat_ulb_name = $dbrow->form_data->panchayat_ulb_name;
    $site_pin_code = $dbrow->form_data->site_pin_code;
    $revenue_village = $dbrow->form_data->revenue_village;
    $revenue_village_name = $dbrow->form_data->revenue_village_name;
    $old_dag_no = $dbrow->form_data->old_dag_no;
    $ward_no = $dbrow->form_data->ward_no;
    $ward_no_name = $dbrow->form_data->ward_no_name;
    $new_dag_no = $dbrow->form_data->new_dag_no;
    $mouza = $dbrow->form_data->mouza;
    $mouza_name = $dbrow->form_data->mouza_name;
    $old_patta_no = $dbrow->form_data->old_patta_no;
    $new_patta_no = $dbrow->form_data->new_patta_no;

    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $spouse_name = $dbrow->form_data->spouse_name;
    $permanent_address = $dbrow->form_data->permanent_address;
    $pin_code = $dbrow->form_data->pin_code;
    $mobile = $dbrow->form_data->mobile;
    $monthly_income = $dbrow->form_data->monthly_income;
    $pan_no = $dbrow->form_data->pan_no;
    $email = isset($dbrow->form_data->email)? $dbrow->form_data->email: "";

    $owner_details = $dbrow->form_data->owner_details; 
    $owner_names = array();
    $owner_genders = array();
    
    if(count($owner_details)) {
        foreach($owner_details as $owner_detail) {
            array_push($owner_names, $owner_detail->owner_name);
            array_push($owner_genders, $owner_detail->owner_gender);
        }//End of foreach()
    }//End of if

    $technical_person_document_type = isset($dbrow->form_data->technical_person_document_type)? $dbrow->form_data->technical_person_document_type: ""; 
    $technical_person_document = isset($dbrow->form_data->technical_person_document)? $dbrow->form_data->technical_person_document: ""; 
    $old_permission_copy_type = isset($dbrow->form_data->old_permission_copy_type)? $dbrow->form_data->old_permission_copy_type: ""; 
    $old_permission_copy = isset($dbrow->form_data->old_permission_copy)? $dbrow->form_data->old_permission_copy: ""; 
    $old_drawing_type = isset($dbrow->form_data->old_drawing_type)? $dbrow->form_data->old_drawing_type: ""; 
    $old_drawing = isset($dbrow->form_data->old_drawing)? $dbrow->form_data->old_drawing: ""; 
    $drawing_type = isset($dbrow->form_data->drawing_type)? $dbrow->form_data->drawing_type: ""; 
    $drawing = isset($dbrow->form_data->drawing)? $dbrow->form_data->drawing: "";
    $trace_map_type = isset($dbrow->form_data->trace_map_type)? $dbrow->form_data->trace_map_type: ""; 
    $trace_map = isset($dbrow->form_data->trace_map)? $dbrow->form_data->trace_map: "";
    $key_plan_type = isset($dbrow->form_data->key_plan_type)? $dbrow->form_data->key_plan_type: ""; 
    $key_plan = isset($dbrow->form_data->key_plan)? $dbrow->form_data->key_plan: "";
    $site_plan_type = isset($dbrow->form_data->site_plan_type)? $dbrow->form_data->site_plan_type: ""; 
    $site_plan = isset($dbrow->form_data->site_plan)? $dbrow->form_data->site_plan: "";
    $building_plan_type = isset($dbrow->form_data->building_plan_type)? $dbrow->form_data->building_plan_type: ""; 
    $building_plan = isset($dbrow->form_data->building_plan)? $dbrow->form_data->building_plan: "";
    $certificate_of_supervision_type = isset($dbrow->form_data->certificate_of_supervision_type)? $dbrow->form_data->certificate_of_supervision_type: ""; 
    $certificate_of_supervision = isset($dbrow->form_data->certificate_of_supervision)? $dbrow->form_data->certificate_of_supervision: "";
    $area_statement_type = isset($dbrow->form_data->area_statement_type)? $dbrow->form_data->area_statement_type: ""; 
    $area_statement = isset($dbrow->form_data->area_statement)? $dbrow->form_data->area_statement: "";
    $amended_byelaws_type = isset($dbrow->form_data->amended_byelaws_type)? $dbrow->form_data->amended_byelaws_type: ""; 
    $amended_byelaws = isset($dbrow->form_data->amended_byelaws)? $dbrow->form_data->amended_byelaws: "";
    $form_no_six_type = isset($dbrow->form_data->form_no_six_type)? $dbrow->form_data->form_no_six_type: ""; 
    $form_no_six = isset($dbrow->form_data->form_no_six)? $dbrow->form_data->form_no_six: "";
    $indemnity_bond_type = isset($dbrow->form_data->indemnity_bond_type)? $dbrow->form_data->indemnity_bond_type: ""; 
    $indemnity_bond = isset($dbrow->form_data->indemnity_bond)? $dbrow->form_data->indemnity_bond: "";
    $undertaking_signed_type = isset($dbrow->form_data->undertaking_signed_type)? $dbrow->form_data->undertaking_signed_type: ""; 
    $undertaking_signed = isset($dbrow->form_data->undertaking_signed)? $dbrow->form_data->undertaking_signed: "";
    $party_applicant_form_type = isset($dbrow->form_data->party_applicant_form_type)? $dbrow->form_data->party_applicant_form_type: ""; 
    $party_applicant_form = isset($dbrow->form_data->party_applicant_form)? $dbrow->form_data->party_applicant_form: "";
    $date_property_tax_type = isset($dbrow->form_data->date_property_tax_type)? $dbrow->form_data->date_property_tax_type: ""; 
    $date_property_tax = isset($dbrow->form_data->date_property_tax)? $dbrow->form_data->date_property_tax: "";
    $service_plan_type = isset($dbrow->form_data->service_plan_type)? $dbrow->form_data->service_plan_type: ""; 
    $service_plan = isset($dbrow->form_data->service_plan)? $dbrow->form_data->service_plan: "";
    $parking_plan_type = isset($dbrow->form_data->parking_plan_type)? $dbrow->form_data->parking_plan_type: ""; 
    $parking_plan = isset($dbrow->form_data->parking_plan)? $dbrow->form_data->parking_plan: "";
    $ownership_document_of_land_type = isset($dbrow->form_data->ownership_document_of_land_type)? $dbrow->form_data->ownership_document_of_land_type: ""; 
    $ownership_document_of_land = isset($dbrow->form_data->ownership_document_of_land)? $dbrow->form_data->ownership_document_of_land: "";
    $any_other_document_type = isset($dbrow->form_data->any_other_document_type)? $dbrow->form_data->any_other_document_type: ""; 
    $any_other_document = isset($dbrow->form_data->any_other_document)? $dbrow->form_data->any_other_document: "";
    $construction_estimate_type = isset($dbrow->form_data->construction_estimate_type)? $dbrow->form_data->construction_estimate_type: ""; 
    $construction_estimate = isset($dbrow->form_data->construction_estimate)? $dbrow->form_data->construction_estimate: "";
    $soft_copy_type = isset($dbrow->form_data->soft_copy_type)? $dbrow->form_data->soft_copy_type: ""; 
    $soft_copy = isset($dbrow->form_data->soft_copy)? $dbrow->form_data->soft_copy: "";
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL;//set_value("rtps_trans_id");

    $application_type = set_value("application_type");;
    $case_type = set_value("case_type");
    $ertp = set_value("ertp");
    $any_old_permission = set_value("any_old_permission");
    $technical_person_name = set_value("technical_person_name");
    $old_permission_no = set_value("old_permission_no");
    $district_emp_tech = set_value("district_emp_tech");
    $district_emp_tech_name = set_value("district_emp_tech_name");
    $empanelled_reg_tech_person = set_value("empanelled_reg_tech_person");
    $empanelled_reg_tech_person_name = set_value("empanelled_reg_tech_person_name");

    $district = set_value("district");
    $district_name = set_value("district_name");
    $house_no = set_value("house_no");
    $mst_pln_dev_auth = set_value("mst_pln_dev_auth");
    $mst_pln_dev_auth_name = set_value("mst_pln_dev_auth_name");
    $name_of_road = set_value("name_of_road");
    $panchayat_ulb = set_value("panchayat_ulb");
    $panchayat_ulb_name = set_value("panchayat_ulb_name");
    $site_pin_code = set_value("site_pin_code");
    $revenue_village = set_value("revenue_village");
    $revenue_village_name = set_value("revenue_village_name");
    $old_dag_no = set_value("old_dag_no");
    $ward_no = set_value("ward_no");
    $ward_no_name = set_value("ward_no_name");
    $new_dag_no = set_value("new_dag_no");
    $mouza = set_value("mouza");
    $mouza_name = set_value("mouza_name");
    $old_patta_no = set_value("old_patta_no");
    $new_patta_no = set_value("new_patta_no");

    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $father_name = set_value("father_name");
    $mother_name = set_value("mother_name");
    $spouse_name = set_value("spouse_name");
    $permanent_address = set_value("permanent_address");
    $pin_code = set_value("pin_code");
    $mobile = $this->session->mobile;//set_value("mobile_number");
    $monthly_income = set_value("monthly_income");
    $pan_no = set_value("pan_no");
    $email = set_value("email");

    $owner_name = set_value("owner_name");
    $owner_gender = set_value("owner_gender");

    $technical_person_document_type = "";
    $technical_person_document = "";
    $old_permission_copy_type = "";
    $old_permission_copy = "";
    $old_drawing_type = "";
    $old_drawing = "";
    $drawing_type = "";
    $drawing = "";
    $trace_map_type = "";
    $trace_map = "";
    $key_plan_type = "";
    $key_plan = "";
    $site_plan_type = "";
    $site_plan = "";
    $building_plan_type = "";
    $building_plan = "";
    $certificate_of_supervision_type = "";
    $certificate_of_supervision = "";
    $area_statement_type = "";
    $area_statement = "";
    $amended_byelaws_type = "";
    $amended_byelaws = "";
    $form_no_six_type = "";
    $form_no_six = "";
    $indemnity_bond_type = "";
    $indemnity_bond = "";
    $undertaking_signed_type = "";
    $undertaking_signed = "";
    $party_applicant_form_type = "";
    $party_applicant_form = "";
    $date_property_tax_type = "";
    $date_property_tax = "";
    $service_plan_type = "";
    $service_plan = "";
    $parking_plan_type = "";
    $parking_plan = "";
    $ownership_document_of_land_type = "";
    $ownership_document_of_land = "";
    $any_other_document_type = "";
    $any_other_document = "";
    $construction_estimate_type = "";
    $construction_estimate = "";
    $soft_copy_type = ""; 
    $soft_copy = "";
}//End of if else //End of if else
?>
<style type="text/css">
    legend {
        display: inline;
        width: auto;
    }
    ol li {
        font-size: 14px;
        font-weight: bold;
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {

        <?php if ($ertp != "yes") { ?>
        $("#site_address").hide();
        <?php } ?>

        $(".dp").datepicker({
            format: 'dd/mm/yyyy',
            endDate: '+0d',
            autoclose: true
        });

        $('.number_input').keypress(function (e) {    
            var charCode = (e.which) ? e.which : event.keyCode    
            if (String.fromCharCode(charCode).match(/[^0-9]/g))    
                return false;                        
        });

        $(document).on("focus", ".site_pin_codes", function(){ 
            $(".site_pin_codes").val("781");            
        });

        $(document).on("keydown", ".site_pin_codes", function(e){  
            const key = e.key; 
            if (key === "Backspace" || key === "Delete") {
                if ($(".site_pin_codes").val().length > 3)
                    return true;
                else
                    return false;
            }                     
        });

        $(document).on("keyup", "#pan_no", function(){ 
            if($("#pan_no").val().length > 10) {
                $("#pan_no").val("");
                alert("Please! Enter upto only 10 digit"); 
            }             
        });

        $('.pin_code').keyup(function () {    
            if($(".pin_code").val().length > 6) {
                $(".pin_code").val("");
                alert("Please! Enter upto only 6 digit"); 
            }                        
        });

        $(document).on("click", "#addlatblrow", function(){
            let totRows = $('#ownerinfomationtbl tr').length;
            var trow = `<tr>
                            <td><input name="owner_name[]" class="form-control" type="text" /></td>
                            <td><select name="owner_gender[]" class="form-control"><option value="">Please Select</option><option value="M">Male</option><option value="F">Female</option><option value="O">Others</option> </select></td>
                            <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if(totRows <= 10) {
                $('#ownerinfomationtbl tr:last').after(trow);
            }
        });

        $(document).on("click", ".deletetblrow", function () {
            $(this).closest("tr").remove();
            return false;
        });

        $.getJSON("<?=$apiURL?>application-type", function(data) {
            let selectOption = '';
            $('#application_type').empty().append('<option value="">Please Select</option>');
            let applicationType= "<?php print $application_type; ?>"
            // console.log(data);
            $.each(data, function(key, value) {
                if(applicationType == value.applicationTypeId)
                    selectOption += '<option value="'+value.applicationTypeId +'" selected>'+value.applicationType+'</option>';
                else
                    selectOption += '<option value="'+value.applicationTypeId +'">'+value.applicationType+'</option>';
            });
            $('#application_type').append(selectOption);
        });

        $.getJSON("<?=$apiURL?>case-type", function(data) {
            let selectOption = '';
            $('#case_type').empty().append('<option value="">Please Select</option>');
            let caseType= "<?php print $case_type; ?>"
            //console.log(data);
            $.each(data, function(key, value) {
                if(caseType == value.caseTypeId)
                    selectOption += '<option value="'+value.caseTypeId +'" selected>'+value.caseType+'</option>';
                else
                    selectOption += '<option value="'+value.caseTypeId +'">'+value.caseType+'</option>';
            });
            $('#case_type').append(selectOption);
        });

        $.getJSON("<?=$apiURL?>district", function(data) {
            let selectOption = '';
            $('.district').empty().append('<option value="">Please Select</option>');
            let district= "<?php print $district; ?>"
            // console.log(data);
            $.each(data, function(key, value) {
                if(district == value.districtId)
                    selectOption += '<option value="'+value.districtId +'" selected>'+value.districtName+'</option>';
                else
                    selectOption += '<option value="'+value.districtId +'">'+value.districtName+'</option>';
            });
            $('.district').append(selectOption);

            selectOption = '';
            $('#district_emp_tech').empty().append('<option value="">Please Select</option>');
            let district_emp_tech= "<?php print $district_emp_tech; ?>"
            // console.log(data);
            $.each(data, function(key, value) {
                if(district_emp_tech == value.districtId)
                    selectOption += '<option value="'+value.districtId +'" selected>'+value.districtName+'</option>';
                else
                    selectOption += '<option value="'+value.districtId +'">'+value.districtName+'</option>';
            });
            $('#district_emp_tech').append(selectOption);
        });

        $.getJSON("<?=$apiURL?>income", function(data) {
            let selectOption = '';
            $('#monthly_income').empty().append('<option value="">Please Select</option>');
            let monthly_income= "<?php print $monthly_income; ?>"
            // console.log(data);
            $.each(data, function(key, value) {
                if(monthly_income == value.incomeId)
                    selectOption += '<option value="'+value.incomeId +'" selected>'+value.income+'</option>';
                else
                    selectOption += '<option value="'+value.incomeId +'">'+value.income+'</option>';
            });
            $('#monthly_income').append(selectOption);
        });

        $(document).on("change", "#district_emp_tech", function() {
            $("#empanelled_reg_tech_person").val('');
            $("#empanelled_reg_tech_person_name").val('');

            let selectedVal = $(this).val();
            if (selectedVal.length) {
                $.getJSON("<?= $apiURL ?>ertp?districtId=" + selectedVal, function(data) {
                    let selectOption = '';
                    $('#empanelled_reg_tech_person').empty().append('<option value="">Please Select</option>')
                    $.each(data, function(key, value) {
                        selectOption += '<option value="' + value.rtpId + '">' + value.rtpName + '</option>';
                    });
                    $('#empanelled_reg_tech_person').append(selectOption);
                });
            }
        });

        $(document).on("change", "#district", function() {
            $("#mst_pln_dev_auth").val('');

            let selectedVal = $(this).val();
            if (selectedVal.length) {
                $.getJSON("<?= $apiURL ?>dev-authority?districtId=" + selectedVal, function(data) {
                    let selectOption = '';
                    $('#mst_pln_dev_auth').empty().append('<option value="">Please Select</option>')
                    $.each(data, function(key, value) {
                        selectOption += '<option value="' + value.developmentAuthorityId + '">' + value.developmentAuthorityName + '</option>';
                    });
                    $('#mst_pln_dev_auth').append(selectOption);
                });
            }
        });

        $(document).on("change", "#mst_pln_dev_auth", function() {
            $("#panchayat_ulb").val('');

            let selectedVal = $(this).val();
            if (selectedVal.length) {
                $.getJSON("<?= $apiURL ?>ulb-panchayat?developmentAuthorityId=" + selectedVal, function(data) {
                    let selectOption = '';
                    $('#panchayat_ulb').empty().append('<option value="">Please Select</option>')
                    $.each(data, function(key, value) {
                        selectOption += '<option value="' + value.ulbPanchayatId + '">' + value.ulbPanchayatName + '</option>';
                    });
                    $('#panchayat_ulb').append(selectOption);
                });

                $.getJSON("<?= $apiURL ?>mouza?developmentAuthorityId=" + selectedVal, function(data) {
                    let selectOption = '';
                    $('#mouza').empty().append('<option value="">Please Select</option>')
                    $.each(data, function(key, value) {
                        selectOption += '<option value="' + value.mouzaId + '">' + value.mouzaName + '</option>';
                    });
                    $('#mouza').append(selectOption);
                });
            }
        });

        $(document).on("change", "#panchayat_ulb", function() {
            $("#revenue_village").val('');
            $("#ward_no").val('');

            let selectedVal = $(this).val();
            if (selectedVal.length) {
                $.getJSON("<?= $apiURL ?>village?ulbPanchayatId=" + selectedVal, function(data) {
                    let selectOption = '';
                    $('#revenue_village').empty().append('<option value="">Please Select</option>')
                    $.each(data, function(key, value) {
                        selectOption += '<option value="' + value.villageId + '">' + value.villageName + '</option>';
                    });
                    $('#revenue_village').append(selectOption);
                });

                $.getJSON("<?= $apiURL ?>ward?ulbPanchayatId=" + selectedVal, function(data) {
                    let selectOption = '';
                    $('#ward_no').empty().append('<option value="">Please Select</option>')
                    $.each(data, function(key, value) {
                        selectOption += '<option value="' + value.wardId + '">' + value.wardNo + '</option>';
                    });
                    $('#ward_no').append(selectOption);
                });
            }
        });

        $(document).on("change", "#case_type", function(){
            var case_type = $("#case_type").val();

            $("#any_old_permission").val("");
            $("#old_permission_no").val("");

            if (case_type == "1") {
                $("#any_old_permission").prop("disabled", true);
                $("#old_permission_no").prop("disabled", true);
            } else {
                $("#any_old_permission").prop("disabled", false);
                $("#old_permission_no").prop("disabled", false);
            }
        });
        <?php if ($case_type == "1") { ?>
            $("#any_old_permission").val("");
            $("#old_permission_no").val("");
            $("#any_old_permission").prop("disabled", true);
            $("#old_permission_no").prop("disabled", true);
        <?php } ?>

        $(document).on("change", "#any_old_permission", function(){

            var any_old_permission = $("#any_old_permission").val();
            $("#old_permission_no").val("");

            if (any_old_permission == "no")
                $("#old_permission_no").prop("disabled", true);
            else 
                $("#old_permission_no").prop("disabled", false);
        });
        <?php if ($any_old_permission != "yes") { ?>
        $("#old_permission_no").val("");
        $("#old_permission_no").prop("disabled", true);
        <?php } ?>

        $(document).on("change", "#ertp", function(){
            var ertp = $("#ertp").val();

            $("#technical_person_name").val("");

            $("#house_no_landmak").val("");
            $("#mouza").val("");
            $("#name_of_road").val("");
            $("#panchayat").val("");
            $("#revenue_village").val("");
            $("#dag_no").val("");
            $("#zone").val("");
            $("#new_dag_no").val("");
            $("#ward_no").val("");
            $("#patta_no").val("");
            $("#pin_code").val("");
            $("#new_patta_no").val("");

            if (ertp == "no") {
                $("#technical_person_name").prop("disabled", true);
                $("#site_address").hide();
            } else {
                $("#technical_person_name").prop("disabled", false);
                $("#site_address").show();
            }
        });
        <?php if ($ertp != "yes") { ?>
            
            $("#technical_person_name").val("");

            $("#district").val("");
            $("#district_name").val("");
            $("#mst_pln_dev_auth").val("");
            $("#mst_pln_dev_auth_name").val("");
            $("#house_no").val("");
            $("#mouza").val("");
            $("#mouza_name").val("");
            $("#name_of_road").val("");
            $("#panchayat_ulb").val("");
            $("#panchayat_ulb_name").val("");
            $("#revenue_village").val("");
            $("#revenue_village_name").val("");
            $("#old_dag_no").val("");
            $("#zone").val("");
            $("#new_dag_no").val("");
            $("#ward_no").val("");
            $("#ward_no_name").val("");
            $("#old_patta_no").val("");
            $("#pin_code").val("");
            $("#new_patta_no").val("");
            $("#technical_person_name").prop("disabled", true);
            $("#site_address").hide();
        <?php } ?>
        
        $(document).on("change", "#empanelled_reg_tech_person", function(){

            let selectedVal = $(this).val();
            if (selectedVal.length) {
                $.getJSON("<?= $apiURL ?>ertp-data?rtpId=" + selectedVal, function(data) {
                    console.log(data);
                    if (data.isActive == 'Y') {
                        $("#empanelled_reg_tech_person_name").val("");
                        if ($("#empanelled_reg_tech_person").val() != "")
                            $("#empanelled_reg_tech_person_name").val($("#empanelled_reg_tech_person option:selected").text());
                    } else{
                        alert("Selected ERTP is under suspension for 6 months ");
                        $("#empanelled_reg_tech_person").val("");
                    }
                });
            }else{
                $("#empanelled_reg_tech_person").val("");
            }
        });

        $(document).on("change", "#district_emp_tech", function(){

            $("#district_emp_tech_name").val("");
            if ($("#district_emp_tech").val() != "")
                $("#district_emp_tech_name").val($("#district_emp_tech option:selected").text());
        });

        $(document).on("change", "#district", function(){

            $("#district_name").val("");
            if ($("#district").val() != "")
                $("#district_name").val($("#district option:selected").text());
        });

        $(document).on("change", "#panchayat_ulb", function(){

            $("#panchayat_ulb_name").val("");
            if ($("#panchayat_ulb").val() != "")
                $("#panchayat_ulb_name").val($("#panchayat_ulb option:selected").text());
        });

        $(document).on("change", "#revenue_village", function(){

            $("#revenue_village_name").val("");
            if ($("#revenue_village").val() != "")
                $("#revenue_village_name").val($("#revenue_village option:selected").text());
        });

        $(document).on("change", "#mst_pln_dev_auth", function(){

            $("#mst_pln_dev_auth_name").val("");
            if ($("#mst_pln_dev_auth").val() != "") 
                $("#mst_pln_dev_auth_name").val($("#mst_pln_dev_auth option:selected").text());
        });

        $(document).on("change", "#mouza", function(){

            $("#mouza_name").val("");
            if ($("#mouza").val() != "") 
                $("#mouza_name").val($("#mouza option:selected").text());
        });

        $(document).on("change", "#zone", function(){

            $("#zone_name").val("");
            if ($("#zone").val() != "") 
                $("#zone_name").val($("#zone option:selected").text());
        });

        $(document).on("change", "#ward_no", function(){

            $("#ward_no_name").val("");
            if ($("#ward_no").val() != "")
                $("#ward_no_name").val($("#ward_no option:selected").text());
        });

        $(document).on("click", ".frmbtn", function(){ 
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE') {
                var msg = "Do you want to procced?";
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
                    if((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/buildingpermission/registration/querysubmit') ?>" enctype="multipart/form-data">

            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />         
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />

            <input name="technical_person_document_type" value="<?=$technical_person_document_type?>" type="hidden" />
            <input name="technical_person_document" value="<?=$technical_person_document?>" type="hidden" />
            <input name="old_permission_copy_type" value="<?=$old_permission_copy_type?>" type="hidden" />
            <input name="old_permission_copy" value="<?=$old_permission_copy?>" type="hidden" />
            <input name="old_drawing_type" value="<?=$old_drawing_type?>" type="hidden" />
            <input name="old_drawing" value="<?=$old_drawing?>" type="hidden" />
            <?php if(!empty($drawing_type)){ ?>
            <input name="drawing_type" value="<?=$drawing_type?>" type="hidden" />
            <input name="drawing" value="<?=$drawing?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($trace_map_type)){ ?>
            <input name="trace_map_type" value="<?=$trace_map_type?>" type="hidden" />
            <input name="trace_map" value="<?=$trace_map?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($key_plan_type)){ ?>
            <input name="key_plan_type" value="<?=$key_plan_type?>" type="hidden" />
            <input name="key_plan" value="<?=$key_plan?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($site_plan_type)){ ?>
            <input name="site_plan_type" value="<?=$site_plan_type?>" type="hidden" />
            <input name="site_plan" value="<?=$site_plan?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($building_plan_type)){ ?>
            <input name="building_plan_type" value="<?=$building_plan_type?>" type="hidden" />
            <input name="building_plan" value="<?=$building_plan?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($certificate_of_supervision_type)){ ?>
            <input name="certificate_of_supervision_type" value="<?=$certificate_of_supervision_type?>" type="hidden" />
            <input name="certificate_of_supervision" value="<?=$certificate_of_supervision?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($area_statement_type)){ ?>
            <input name="area_statement_type" value="<?=$area_statement_type?>" type="hidden" />
            <input name="area_statement" value="<?=$area_statement?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($amended_byelaws_type)){ ?>
            <input name="amended_byelaws_type" value="<?=$amended_byelaws_type?>" type="hidden" />
            <input name="amended_byelaws" value="<?=$amended_byelaws?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($form_no_six_type)){ ?>
            <input name="form_no_six_type" value="<?=$form_no_six_type?>" type="hidden" />
            <input name="form_no_six" value="<?=$form_no_six?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($indemnity_bond_type)){ ?>
            <input name="indemnity_bond_type" value="<?=$indemnity_bond_type?>" type="hidden" />
            <input name="indemnity_bond" value="<?=$indemnity_bond?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($undertaking_signed_type)){ ?>
            <input name="undertaking_signed_type" value="<?=$undertaking_signed_type?>" type="hidden" />
            <input name="undertaking_signed" value="<?=$undertaking_signed?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($party_applicant_form_type)){ ?>
            <input name="party_applicant_form_type" value="<?=$party_applicant_form_type?>" type="hidden" />
            <input name="party_applicant_form" value="<?=$party_applicant_form?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($date_property_tax_type)){ ?>
            <input name="date_property_tax_type" value="<?=$date_property_tax_type?>" type="hidden" />
            <input name="date_property_tax" value="<?=$date_property_tax?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($service_plan_type)){ ?>
            <input name="service_plan_type" value="<?=$service_plan_type?>" type="hidden" />
            <input name="service_plan" value="<?=$service_plan?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($parking_plan_type)){ ?>
            <input name="parking_plan_type" value="<?=$parking_plan_type?>" type="hidden" />
            <input name="parking_plan" value="<?=$parking_plan?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($ownership_document_of_land_type)){ ?>
            <input name="ownership_document_of_land_type" value="<?=$ownership_document_of_land_type?>" type="hidden" />
            <input name="ownership_document_of_land" value="<?=$ownership_document_of_land?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($any_other_document_type)){ ?>
            <input name="any_other_document_type" value="<?=$any_other_document_type?>" type="hidden" />
            <input name="any_other_document" value="<?=$any_other_document?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($construction_estimate_type)){ ?>
            <input name="construction_estimate_type" value="<?=$construction_estimate_type?>" type="hidden" />
            <input name="construction_estimate" value="<?=$construction_estimate?>" type="hidden" />
            <?php } ?>
            <input name="soft_copy_type" value="<?=$soft_copy_type?>" type="hidden" />
            <input name="soft_copy" value="<?=$soft_copy?>" type="hidden" />

            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                Application Form for Building Permission upto Ground+2 storied under Mukhya Mantrir Sohoj Griha Nirman Achoni
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

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Proposal Information/ প্ৰস্তাৱ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Application Type/ আবেদন প্ৰকাৰ <span class="text-danger">*</span> </label>
                                <select name="application_type" class="form-control" id="application_type">
                                    <option value="">Please Select</option>
                                </select>
                                <?= form_error("application_type") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Case Type/ কেছৰ ধৰণ <span class="text-danger">*</span> </label>
                                <select name="case_type" class="form-control" id="case_type">
                                    <option value="">Please Select</option>
                                    
                                </select>
                                <?= form_error("case_type") ?>
                            </div>   
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Do you have any technical person to assist other than ERTP?/ ই আৰ টি পিৰ বাহিৰেও সহায় কৰিবলৈ আপোনাৰ কোনো কাৰিকৰী ব্যক্তি আছেনে? <span class="text-danger">*</span> </label>
                                <select name="ertp" class="form-control" id="ertp">
                                    <option value="">Please Select</option>
                                    <option value="yes" <?=($ertp === "yes")?'selected':''?>>Yes</option>
                                    <option value="no" <?=($ertp === "no")?'selected':''?>>No</option>
                                </select>
                                <?= form_error("ertp") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Do you have any old permission?/ আপোনাৰ কিবা পুৰণি অনুমতি আছে নেকি? <span class="text-danger">*</span> </label>
                                <select name="any_old_permission" class="form-control" id="any_old_permission">
                                    <option value="">Please Select</option>
                                    <option value="yes" <?=($any_old_permission === "yes")?'selected':''?>>Yes</option>
                                    <option value="no" <?=($any_old_permission === "no")?'selected':''?>>No</option>
                                </select>
                                <?= form_error("any_old_permission") ?>
                            </div>   
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Technical Person Name/ কাৰিকৰী ব্যক্তিৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="technical_person_name" id="technical_person_name" value="<?=$technical_person_name?>" maxlength="255" />
                                <?= form_error("technical_person_name") ?>
                            </div> 
                            <div class="col-md-6">
                                <label>Old Permission No/ পুৰণি অনুমতি নম্বৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="old_permission_no" value="<?=$old_permission_no?>" id="old_permission_no"  maxlength="255" />
                                <?= form_error("old_permission_no") ?>
                            </div>
                        </div>
                        <!-- Left Work -->
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>District/জিলা <span class="text-danger">*</span> </label>
                                <select name="district_emp_tech" id="district_emp_tech" class="form-control">
                                    <option value="">Please Select</option>
                                </select>
                                <input type="text" name="district_emp_tech_name" value="<?= strlen($district_emp_tech_name) ? $district_emp_tech_name : '' ?>" id="district_emp_tech_name" hidden/>
                                <?= form_error("district_emp_tech") ?>
                            </div>
                            <div class="col-md-6">
                                <label> Empanelled Registered Technical Person/ তালিকাভুক্ত পঞ্জীভুক্ত কাৰিকৰী ব্যক্তি<span class="text-danger">*</span> </label>
                                <select name="empanelled_reg_tech_person" class="form-control" id="empanelled_reg_tech_person">
                                    <option value="">Please Select</option>
                                    <?php
                                    if (strlen($empanelled_reg_tech_person)) {
                                    ?>
                                    <option value="<?= strlen($empanelled_reg_tech_person) ? $empanelled_reg_tech_person : '' ?>" selected><?= strlen($empanelled_reg_tech_person_name) ? $empanelled_reg_tech_person_name : 'Please Select' ?></option>
                                    <?php
                                    }
                                    ?>
                                    <?= form_error("empanelled_reg_tech_person") ?>
                                </select>
                                <input type="text" name="empanelled_reg_tech_person_name" id="empanelled_reg_tech_person_name" value="<?= strlen($empanelled_reg_tech_person_name) ? $empanelled_reg_tech_person_name : '' ?>" hidden/>
                                <?= form_error("empanelled_reg_tech_person") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px" id="site_address">
                        <legend class="h5">Site Address/স্থানৰ ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>District/জিলা <span class="text-danger">*</span> </label>
                                <select name="district" id="district" class="form-control district">
                                    <option value="">Please Select</option>
                                </select>
                                <input type="text" name="district_name" id="district_name" value="<?= strlen($district_name) ? $district_name : '' ?>" hidden/>
                                <?= form_error("district") ?>
                            </div>
                            <div class="col-md-6">
                                <label>House No/ঘৰ নং <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="house_no" value="<?=$house_no?>" id="house_no" maxlength="255" />
                                <?= form_error("house_no") ?>
                            </div>   
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Master Plan/Development Authority/মাষ্টাৰ প্লেন/উন্নয়ন কৰ্তৃপক্ষ <span class="text-danger">*</span> </label>
                                <select name="mst_pln_dev_auth" id="mst_pln_dev_auth" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                    if (strlen($mst_pln_dev_auth)) {
                                    ?>
                                    <option value="<?= strlen($mst_pln_dev_auth) ? $mst_pln_dev_auth : '' ?>" selected><?= strlen($mst_pln_dev_auth_name) ? $mst_pln_dev_auth_name : 'Please Select' ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <input type="text" name="mst_pln_dev_auth_name" id="mst_pln_dev_auth_name" value="<?= strlen($mst_pln_dev_auth_name) ? $mst_pln_dev_auth_name : '' ?>" hidden/>
                                <?= form_error("mst_pln_dev_auth") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Name Of Road/পথৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="name_of_road" value="<?=$name_of_road?>" id="name_of_road" maxlength="255" />
                                <?= form_error("name_of_road") ?>
                            </div>   
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>ULB/Panchayat/পঞ্চায়ত <span class="text-danger">*</span> </label>
                                <select name="panchayat_ulb" id="panchayat_ulb" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                    if (strlen($panchayat_ulb)) {
                                    ?>
                                    <option value="<?= strlen($panchayat_ulb) ? $panchayat_ulb : '' ?>" selected><?= strlen($panchayat_ulb_name) ? $panchayat_ulb_name : 'Please Select' ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <input type="text" name="panchayat_ulb_name" id="panchayat_ulb_name" value="<?= strlen($panchayat_ulb_name) ? $panchayat_ulb_name : '' ?>" hidden/>
                                <?= form_error("panchayat_ulb") ?>
                            </div>   
                            <div class="col-md-6">
                                <label>Pin Code/ পিন কোড <span class="text-danger">*</span></label>
                                <input type="text" class="form-control pin_code number_input site_pin_codes" name="site_pin_code" value="<?=$site_pin_code?>" id="pin_code" maxlength="6" />
                                <?= form_error("site_pin_code") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Revenue Village/ৰাজহ গাঁও <span class="text-danger">*</span> </label>
                                <select name="revenue_village" id="revenue_village" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                    if (strlen($revenue_village)) {
                                    ?>
                                    <option value="<?= strlen($revenue_village) ? $revenue_village : '' ?>" selected><?= strlen($revenue_village_name) ? $revenue_village_name : 'Please Select' ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <input type="text" name="revenue_village_name" id="revenue_village_name" value="<?= strlen($revenue_village_name) ? $revenue_village_name : '' ?>" hidden/>
                                <?= form_error("revenue_village") ?>
                            </div>   
                            <div class="col-md-6">
                                <label>Old Dag No/পুৰণি দাগ নং </label>
                                <input type="text" class="form-control" name="old_dag_no" id="old_dag_no" value="<?=$old_dag_no?>"  maxlength="255" />
                                <?= form_error("old_dag_no") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Ward No/ ৱাৰ্ড নং <span class="text-danger">*</span> </label>
                                <select name="ward_no" class="form-control" id="ward_no">
                                    <option value="">Please Select</option>
                                    <?php
                                    if (strlen($ward_no)) {
                                    ?>
                                    <option value="<?= strlen($ward_no) ? $ward_no : '' ?>" selected><?= strlen($ward_no_name) ? $ward_no_name : 'Please Select' ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <input id="ward_no_name" name="ward_no_name" type="text" value="<?= strlen($ward_no_name) ? $ward_no_name : '' ?>" hidden/>
                                <?= form_error("ward_no") ?>
                            </div>   
                            <div class="col-md-6">
                                <label>New Dag No/ নতুন দাগ নম্বৰ <span class="text-danger">*</span> </label>
                                <input class="form-control" value="<?=$new_dag_no?>" name="new_dag_no" id="new_dag_no" maxlength="10" type="text" />
                                <?= form_error("new_dag_no") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mouza/ মৌজা <span class="text-danger">*</span> </label>
                                <select name="mouza" id="mouza" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                    if (strlen($mouza)) {
                                    ?>
                                    <option value="<?= strlen($mouza) ? $mouza : '' ?>" selected><?= strlen($mouza_name) ? $mouza_name : 'Please Select' ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <input type="text" name="mouza_name" id="mouza_name" value="<?= strlen($mouza_name) ? $mouza_name : '' ?>" hidden/>
                                <?= form_error("mouza") ?>
                            </div>   
                            <div class="col-md-6">
                                <label>Old Patta No/ পুৰণি পট্টা নং <span class="text-danger">*</span> </label>
                                <input class="form-control" value="<?=$old_patta_no?>" id="old_patta_no" name="old_patta_no" maxlength="10" type="text" />
                                <?= form_error("old_patta_no") ?>
                            </div>
                        </div>
                        
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>New Patta No/ নতুন পট্টা নং<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="new_patta_no" value="<?=$new_patta_no?>" name="new_patta_no" maxlength="6" />
                                <?= form_error("new_patta_no") ?>
                            </div>
                            <div class="col-md-6">
                            </div>  
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name of the Applicant/আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255"/>
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Gender/ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="1" <?=($applicant_gender === "1")?'selected':''?>>Male</option>
                                    <option value="2" <?=($applicant_gender === "2")?'selected':''?>>Female</option>
                                    <option value="3" <?=($applicant_gender === "3")?'selected':''?>>Others</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>   
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Fathers Name/পিতাৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?=$father_name?>" maxlength="255"/>
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mother Name/মাতৃৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name" value="<?=$mother_name?>" maxlength="255"/>
                                <?= form_error("mother_name") ?>
                            </div>   
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Spouse Name/পত্নীৰ নাম </label>
                                <input type="text" class="form-control" value="<?=$spouse_name?>" name="spouse_name" id="spouse_name" maxlength="255"/>
                                <?= form_error("spouse_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Permanent Address/স্থায়ী ঠিকনা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" value="<?=$permanent_address?>" name="permanent_address" id="permanent_address" maxlength="255"/>
                                <?= form_error("permanent_address") ?>
                            </div>   
                        </div>

                        <div class="row form-group">      
                            <div class="col-md-6">
                                <label>Pin Code/ পিন কোড<span class="text-danger">*</span></label>
                                <input type="text" class="form-control pin_code number_input" name="pin_code" value="<?= $pin_code ?>" maxlength="6"/>
                                <?= form_error("pin_code") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" <?=(strlen($mobile)==10)?'readonly':''?>/>
                                <?= form_error("mobile") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Monthly Income/ মাহিলী উপাৰ্জন <span class="text-danger">*</span> </label>
                                <select name="monthly_income" class="form-control" id="monthly_income">
                                    <option value="">Please Select</option>
                                </select>
                                <?= form_error("monthly_income") ?>
                            </div>  
                            <div class="col-md-6">
                                <label>PAN No./ পেন নং <span class="text-danger">*</span> </label>
                                <input class="form-control pan_no" name="pan_no" value="<?=$pan_no?>" maxlength="10" type="text"/>
                                <?= form_error("pan_no") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100"/>
                                <?= form_error("email") ?>
                            </div>
                            <div class="col-md-6">
                            </div> 
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Ownership Information/ মালিকীস্বত্বৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="ownerinfomationtbl">
                                    <thead>
                                        <tr>
                                            <th>Owner's Name/ মালিকৰ নাম<span class="text-danger">*</span></th>
                                            <th>Owner's Gender/ মালিকৰ লিংগ <span class="text-danger">*</span></th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $owner_name_cnt = (isset($owner_names) && is_array($owner_names)) ? count($owner_names) : 0;
                                        if ($owner_name_cnt > 0) {
                                            for ($i = 0; $i < $owner_name_cnt; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="addlatblrow" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button>';
                                                }// End of if else ?>
                                                <tr>
                                                    <td><input name="owner_name[]" value="<?= $owner_names[$i] ?>" class="form-control" type="text" /></td>
                                                    <td>
                                                        <select name="owner_gender[]" class="form-control">
                                                            <option value="">Select</option>
                                                            <option value="M" <?=($owner_genders[$i]==='M')?'selected':''?>>Male</option>
                                                            <option value="F" <?=($owner_genders[$i]==='F')?'selected':''?>>Female</option>
                                                            <option value="O" <?=($owner_genders[$i]==='O')?'selected':''?>>Others</option>
                                                        </select>
                                                    </td>
                                                    <td><?= $btn ?></td>
                                                </tr>
                                                <?php }
                                        } else { ?>
                                            <tr>
                                                <td><input name="owner_name[]" class="form-control" type="text" /></td>
                                                <td>
                                                    <select name="owner_gender[]" class="form-control">
                                                        <option value="">Please Select</option>
                                                        <option value="M" <?=($applicant_gender === "M")?'selected':''?>>Male</option>
                                                        <option value="F" <?=($applicant_gender === "F")?'selected':''?>>Female</option>
                                                        <option value="O" <?=($applicant_gender === "O")?'selected':''?>>Others</option>
                                                    </select>
                                                </td>
                                                <td style="text-align:center">
                                                    <button class="btn btn-info" id="addlatblrow" type="button">
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

                    <fieldset class="border border-danger table-responsive" style="overflow:hidden">
                        <legend class="h5">Processing history</legend>
                        <table class="table table-bordered bg-white mt-0">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Date &AMP; time</th>
                                    <th>Action taken</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($dbrow->processing_history)) {
                                    foreach($dbrow->processing_history as $key=>$rows) {
                                    $query_attachment = $rows->query_attachment??''; ?>
                                        <tr>
                                            <td><?=sprintf("%02d", $key+1)?></td>
                                            <td><?=date('d/m/Y h:i a', strtotime($this->mongo_db->getDateTime($rows->processing_time)))?></td>
                                            <td><?=$rows->action_taken?></td>
                                            <td><?=$rows->remarks?></td>
                                        </tr>
                                    <?php }//End of foreach()
                                }//End of if else ?>
                            </tbody>
                        </table>
                    </fieldset> 
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <!-- <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button> -->
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-angle-double-right"></i> Submit
                    </button>
                    <!-- <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button> -->
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>