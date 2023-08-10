<?php
$countries = $this->countries_model->get_rows(array());
$states = $this->States_model->get_rows(array());
$districts = $this->Districts_model->get_rows(array());
$currentYear = date('Y');
//$apiServer = $this->config->item('edistrict_base_url');
$apiServer = "https://localhost/wptbcapis/"; //For testing
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $service_name = $dbrow->service_data->service_name;
    $status = $dbrow->service_data->appl_status;

    $applicant_name = $dbrow->form_data->applicant_name;
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $dob = $dbrow->form_data->dob;
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email;
    $pan_no = $dbrow->form_data->pan_no;
    $aadhar_no = $dbrow->form_data->aadhar_no;
    $permanent_addr = $dbrow->form_data->permanent_addr;
    $correspondence_addr = $dbrow->form_data->correspondence_addr;

    $primary_qualification = $dbrow->form_data->primary_qualification;
    $primary_qua_doc = $dbrow->form_data->primary_qua_doc;
    $primary_qua_college_name = $dbrow->form_data->primary_qua_college_name;
    $primary_qua_college_addr = $dbrow->form_data->primary_qua_college_addr;
    $primary_qua_course_dur = $dbrow->form_data->primary_qua_course_dur;
    $primary_qua_doci = $dbrow->form_data->primary_qua_doci;
    $primary_qua_university_award_intership = $dbrow->form_data->primary_qua_university_award_intership;
    $university_roll_no = $dbrow->form_data->university_roll_no;

    $study_place = $dbrow->form_data->study_place;
    $address1 = $dbrow->form_data->address1;
    $address2 = $dbrow->form_data->address2;
    $country = $dbrow->form_data->country;
    $statee = $dbrow->form_data->statee;
    $state_foreign=$dbrow->form_data->state_foreign;
    $district = $dbrow->form_data->district;
    $pincode = $dbrow->form_data->pincode;

    
    $admit_card_type_frm = set_value("admit_card_type");
    $hs_marksheet_type_frm = set_value("hs_marksheet_type");
    $reg_certificate_type_frm = set_value("reg_certificate_type");
    $mbbs_marksheet_type_frm = set_value("mbbs_marksheet_type");
    $pass_certificate_type_frm = set_value("pass_certificate_type");
    $college_noc_type_frm = set_value("college_noc_type");
    $director_noc_type_frm = set_value("director_noc_type");
    $university_noc_type_frm = set_value("university_noc_type");
    $institute_noc_type_frm = set_value("institute_noc_type");
    $eligibility_certificate_type_frm = set_value("eligibility_certificate_type");
    $screening_result_type_frm = set_value("screening_result_type");
    $passport_visa_type_frm = set_value("passport_visa_type");
    $all_docs_type_frm = set_value("all_docs_type");
    $soft_copy_type_frm = set_value("soft_copy_type");
    $annexure_type_frm = set_value("annexure_type");
    $ten_pass_certificate_type_frm = set_value("ten_pass_certificate_type");
    $photograph_type_frm = set_value("photograph_type");
    $signature_type_frm = set_value("signature_type");

    $uploadedFiles = $this->session->flashdata('uploaded_files');
    $admit_card_frm = $uploadedFiles['admit_card_old']??null;
    $hs_marksheet_frm = $uploadedFiles['hs_marksheet_old']??null;
    $reg_certificate_frm = $uploadedFiles['reg_certificate_old']??null;
    $mbbs_marksheet_frm = $uploadedFiles['mbbs_marksheet_old']??null;
    $pass_certificate_frm = $uploadedFiles['pass_certificate_old']??null;
    $college_noc_frm = $uploadedFiles['college_noc_old']??null;
    $director_noc_frm = $uploadedFiles['director_noc_old']??null;
    $university_noc_frm = $uploadedFiles['university_noc_old']??null;
    $institute_noc_frm = $uploadedFiles['institute_noc_old']??null;
    $eligibility_certificate_frm = $uploadedFiles['eligibility_certificate_old']??null;
    $screening_result_frm = $uploadedFiles['screening_result_old']??null;
    $passport_visa_frm = $uploadedFiles['passport_visa_old']??null;
    $all_docs_frm = $uploadedFiles['all_docs_old']??null;
    $soft_copy_frm = $uploadedFiles['soft_copy_old']??null;
    $annexure_frm = $uploadedFiles['annexure_old']??null;
    $ten_pass_certificate_frm = $uploadedFiles['ten_pass_certificate_old']??null;
    $photograph_frm = $uploadedFiles['photograph_old']??null;
    $signature_frm = $uploadedFiles['signature_old']??null;

    $admit_card_type_db = $dbrow->form_data->admit_card_type??null;
    $hs_marksheet_type_db = $dbrow->form_data->hs_marksheet_type??null;
    $reg_certificate_type_db = $dbrow->form_data->reg_certificate_type??null;
    $mbbs_marksheet_type_db = $dbrow->form_data->mbbs_marksheet_type??null;   
    $pass_certificate_type_db = $dbrow->form_data->pass_certificate_type??null;
    $college_noc_type_db = $dbrow->form_data->college_noc_type??null;
    $director_noc_type_db = $dbrow->form_data->director_noc_type??null;
    $university_noc_type_db = $dbrow->form_data->university_noc_type??null;
    $institute_noc_type_db = $dbrow->form_data->institute_noc_type??null;
    $eligibility_certificate_type_db = $dbrow->form_data->eligibility_certificate_type??null;
    $screening_result_type_db = $dbrow->form_data->screening_result_type??null;
    $passport_visa_type_db = $dbrow->form_data->passport_visa_type??null;
    $all_docs_type_db = $dbrow->form_data->all_docs_type??null;
    $soft_copy_type_db = $dbrow->form_data->soft_copy_type??null;
    $annexure_type_db = $dbrow->form_data->annexure_type??null;
    $ten_pass_certificate_type_db = $dbrow->form_data->ten_pass_certificate_type??null;
    $photograph_type_db = $dbrow->form_data->photograph_type??null;
    $signature_type_db = $dbrow->form_data->signature_type??null;


    $admit_card_db = $dbrow->form_data->admit_card??null;
    $hs_marksheet_db = $dbrow->form_data->hs_marksheet??null;
    $reg_certificate_db = $dbrow->form_data->reg_certificate??null;
    $mbbs_marksheet_db = $dbrow->form_data->mbbs_marksheet??null;
    $pass_certificate_db = $dbrow->form_data->pass_certificate??null;
    $college_noc_db = $dbrow->form_data->college_noc??null;
    $director_noc_db = $dbrow->form_data->director_noc??null;
    $university_noc_db = $dbrow->form_data->university_noc??null;
    $institute_noc_db = $dbrow->form_data->institute_noc??null;
    $eligibility_certificate_db = $dbrow->form_data->eligibility_certificate??null;
    $screening_result_db = $dbrow->form_data->screening_result??null;
    $passport_visa_db = $dbrow->form_data->passport_visa??null;
    $all_docs_db = $dbrow->form_data->all_docs??null;
    $soft_copy_db = $dbrow->form_data->soft_copy??null;
    $annexure_db = $dbrow->form_data->annexure??null;
    $ten_pass_certificate_db = $dbrow->form_data->ten_pass_certificate??null;
    $photograph_db = $dbrow->form_data->photograph??null;
    $signature_db = $dbrow->form_data->signature??null;



     $admit_card_type = strlen($admit_card_type_frm)?$admit_card_type_frm:$admit_card_type_db;
    $hs_marksheet_type = strlen($hs_marksheet_type_frm)?$hs_marksheet_type_frm:$hs_marksheet_type_db;
    $reg_certificate_type = strlen($reg_certificate_type_frm)?$reg_certificate_type_frm:$reg_certificate_type_db;
    $mbbs_marksheet_type = strlen($mbbs_marksheet_type_frm)?$mbbs_marksheet_type_frm:$mbbs_marksheet_type_db;
    $pass_certificate_type = strlen($pass_certificate_type_frm)?$pass_certificate_type_frm:$pass_certificate_type_db;
    $college_noc_type = strlen($college_noc_type_frm)?$college_noc_type_frm:$college_noc_type_db;
    $director_noc_type = strlen($director_noc_type_frm)?$director_noc_type_frm:$director_noc_type_db;
    $university_noc_type = strlen($university_noc_type_frm)?$university_noc_type_frm:$university_noc_type_db;
    $institute_noc_type = strlen($institute_noc_type_frm)?$institute_noc_type_frm:$institute_noc_type_db;
    $eligibility_certificate_type = strlen($eligibility_certificate_type_frm)?$eligibility_certificate_type_frm:$eligibility_certificate_type_db;
    $screening_result_type = strlen($screening_result_type_frm)?$screening_result_type_frm:$screening_result_type_db;
    $passport_visa_type = strlen($passport_visa_type_frm)?$passport_visa_type_frm:$passport_visa_type_db;
    $all_docs_type = strlen($all_docs_type_frm)?$all_docs_type_frm:$all_docs_type_db;
    $soft_copy_type = strlen($soft_copy_type_frm)?$soft_copy_type_frm:$soft_copy_type_db;
    $annexure_type = strlen($annexure_type_frm)?$annexure_type_frm:$annexure_type_db;
    $ten_pass_certificate_type = strlen($ten_pass_certificate_type_frm)?$ten_pass_certificate_type_frm:$ten_pass_certificate_type_db;
    $photograph_type = strlen($photograph_type_frm)?$photograph_type_frm:$photograph_type_db;
    $signature_type = strlen($signature_type_frm)?$signature_type_frm:$signature_type_db;


    $admit_card = strlen($admit_card_frm)?$admit_card_frm:$admit_card_db;
    $hs_marksheet = strlen($hs_marksheet_frm)?$hs_marksheet_frm:$hs_marksheet_db;
    $reg_certificate = strlen($reg_certificate_frm)?$reg_certificate_frm:$reg_certificate_db;
    $mbbs_marksheet = strlen($mbbs_marksheet_frm)?$mbbs_marksheet_frm:$mbbs_marksheet_db;
    $pass_certificate = strlen($pass_certificate_frm)?$pass_certificate_frm:$pass_certificate_db;
    $college_noc = strlen($college_noc_frm)?$college_noc_frm:$college_noc_db;
    $director_noc = strlen($director_noc_frm)?$director_noc_frm:$director_noc_db;
    $university_noc = strlen($university_noc_frm)?$university_noc_frm:$university_noc_db;
    $institute_noc = strlen($institute_noc_frm)?$institute_noc_frm:$institute_noc_db;
    $eligibility_certificate = strlen($eligibility_certificate_frm)?$eligibility_certificate_frm:$eligibility_certificate_db;
    $screening_result = strlen($screening_result_frm)?$screening_result_frm:$screening_result_db;
    $passport_visa = strlen($passport_visa_frm)?$passport_visa_frm:$passport_visa_db;
    $all_docs = strlen($all_docs_frm)?$all_docs_frm:$all_docs_db;
    $soft_copy = strlen($soft_copy_frm)?$soft_copy_frm:$soft_copy_db;
    $annexure = strlen($annexure_frm)?$annexure_frm:$annexure_db;
    $ten_pass_certificate = strlen($ten_pass_certificate_frm)?$ten_pass_certificate_frm:$ten_pass_certificate_db;
    $photograph = strlen($photograph_frm)?$photograph_frm:$photograph_db;
    $signature = strlen($signature_frm)?$signature_frm:$signature_db;
} ?>
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
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/iservices/js/custom/digilocker.js') ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
    $.getJSON("<?=$apiServer."district_list.php"?>", function(data) {
        let selectOption = '';
        $('.district').empty().append('<option value="">Select District</option>');
        let selectedDistrict = "<?php print $district; ?>"
        $.each(data.records, function(key, value) {
            if (selectedDistrict == value.district_name)
                selectOption += '<option value="' + value.district_name + '" selected>' + value
                .district_name + '</option>';
            else
                selectOption += '<option value="' + value.district_name + '">' + value
                .district_name + '</option>';
        });
        $('.district').append(selectOption);
    });
    $(document).ready(function() {
        $.getJSON("Countries.php", function(data) {
            let selectOption = '';
            $.each(data.records, function(key, value) {
                selectOption += '<option value="' + value.country_name + '">' + value
                    .country_name +
                    '</option>';
            });
            $('.country').append(selectOption);
        });
    });
    $(document).on("change", ".country, #statee", function() {
        if ($(this).hasClass("country")) {
            let selectedVal = $(this).val();

            if (selectedVal.length) {
                var myObject = {
                    name: selectedVal
                };

                $.getJSON("states.php?jsonbody=" + JSON.stringify(myObject), function(data) {
                    updateOptions(data.records, '#statee', 'Select a state');
                });
            }
        } else if ($(this).attr("id") === "statee") {
            let selectedVal = $(this).val();

            if (selectedVal.length) {
                var myObject = {
                    slc: selectedVal
                };

                $.getJSON("districts_all_states.php?jsonbody=" + JSON.stringify(myObject), function(
                    data) {
                    updateOptions(data.records, '#district', 'Select a District');
                });
            }
        }
    });

    function updateOptions(data, targetId, defaultOptionText) {
        let selectOption = '';
        let targetElement = $(targetId);

        targetElement.empty().append('<option value="">' + defaultOptionText + '</option>');

        $.each(data, function(key, value) {
            selectOption += '<option value="' + value.state_name_english + '">' + value
                .state_name_english + '</option>';
        });

        targetElement.append(selectOption);
    }

    $(document).on("change", "#applicant_district", function() {
        let selectedVal = $(this).val();
        json_body = '{"district_id":"' + selectedVal + '"}';
        if (selectedVal.length) {
            $.getJSON("<?=$apiServer."sub_division_list.php"?>?jsonbody=" + json_body + "", function(
                data) {
                let selectOption = '';
                $('#applicant_sub_division').empty().append(
                    '<option value="">Select a Sub-Division</option>')
                $.each(data.records, function(key, value) {
                    selectOption += '<option value="' + value.subdiv_name + '">' + value
                        .subdiv_name + '</option>';
                });
                $('#applicant_sub_division').append(selectOption);
            });
        }
    });

    $(document).on("change", "#deceased_district", function() {
        let selectedVal = $(this).val();
        json_body = '{"district_id":"' + selectedVal + '"}';
        if (selectedVal.length) {
            $.getJSON("<?=$apiServer."sub_division_list.php"?>?jsonbody=" + json_body + "", function(
                data) {
                let selectOption = '';
                $('#deceased_sub_division').empty().append(
                    '<option value="">Select a Sub-Division</option>')
                $.each(data.records, function(key, value) {
                    selectOption += '<option value="' + value.subdiv_name + '">' + value
                        .subdiv_name + '</option>';
                });
                $('#deceased_sub_division').append(selectOption);
            });
        }
    });

    $(document).on("change", "#applicant_sub_division", function() {
        let selectedVal = $(this).val();
        json_body = '{"subdiv_id":"' + selectedVal + '"}';
        if (selectedVal.length) {
            $.getJSON("<?=$apiServer."revenue_circle_list.php"?>?jsonbody=" + json_body + "", function(
                data) {
                let selectOption = '';
                $('#applicant_revenue_circle').empty().append(
                    '<option value="">Select Revenue Circle</option>')
                $.each(data.records, function(key, value) {
                    selectOption += '<option value="' + value.circle_name + '">' + value
                        .circle_name + '</option>';
                });
                $('#applicant_revenue_circle').append(selectOption);
            });
        }
    });

    $(document).on("change", "#deceased_sub_division", function() {
        let selectedVal = $(this).val();
        json_body = '{"subdiv_id":"' + selectedVal + '"}';
        if (selectedVal.length) {
            $.getJSON("<?=$apiServer."revenue_circle_list.php"?>?jsonbody=" + json_body + "", function(
                data) {
                let selectOption = '';
                $('#deceased_revenue_circle').empty().append(
                    '<option value="">Select Revenue Circle</option>')
                $.each(data.records, function(key, value) {
                    selectOption += '<option value="' + value.circle_name + '">' + value
                        .circle_name + '</option>';
                });
                $('#deceased_revenue_circle').append(selectOption);
            });
        }
    });

    $(document).on("click", "#addlatblrow", function() {
        let totRows = $('#familydetailstatustbl tr').length;
        var trow = `<tr>
                            <td><input name="name_of_kins[]" class="form-control" type="text" /></td>
                            <td><input name="relations[]" class="form-control" type="text" /></td>
                            <td><input name="age_y_on_the_date_of_applications[]" class="form-control" type="text" /></td>
                            <td><input name="age_m_on_the_date_of_applications[]" class="form-control" type="text" /></td>
                            <td>
                                <select name="kin_alive_deads[]" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Alive">Alive</option>
                                    <option value="Expired">Expired</option>
                                </select>
                            </td>
                            <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
        if (totRows <= 10) {
            $('#familydetailstatustbl tr:last').after(trow);
        }
    });

    $(document).on("click", ".deletetblrow", function() {
        $(this).closest("tr").remove();
        return false;
    });

    $(".dp").datepicker({
        format: 'dd/mm/yyyy',
        endDate: '+0d',
        autoclose: true
    });

    var getAge = function(db) {
        $.ajax({
            type: "POST",
            url: "<?=base_url('spservices/nextofkin/registration/get_age')?>",
            data: {
                "dob": db
            },
            beforeSend: function() {
                $("#age").val("Calculating...");
            },
            success: function(res) {
                $("#age").val(res);
            }
        });
    };

    var date_of_birth = '<?=$dob?>';
    if (date_of_birth.length == 10) {
        var dateAr = date_of_birth.split('/');
        var dob = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];
        getAge(dob);
    } //End of if

    $(document).on("change", "#dob", function() {
        var dd = $(this).val(); //alert(dd);
        var dateAr = dd.split('/');
        var dob = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];
        getAge(dob);
    });

    $(document).on("keyup", "#pan_no", function() {
        if ($("#pan_no").val().length > 10) {
            $("#pan_no").val("");
            alert("Please! Enter upto only 10 digit");
        }
    });

    $('.number_input').keypress(function(e) {
        var charCode = (e.which) ? e.which : event.keyCode
        if (String.fromCharCode(charCode).match(/[^0-9]/g))
            return false;
    });

    $('#aadhar_no').keyup(function() {
        if ($("#aadhar_no").val().length > 12) {
            $("#aadhar_no").val("");
            alert("Please! Enter upto only 12 digit");
        }
    });

    $('.pin_code').keyup(function() {
        if ($(".pin_code").val().length > 6) {
            $(".pin_code").val("");
            alert("Please! Enter upto only 6 digit");
        }
    });

    var admitcard = parseInt(<?=strlen($admit_card)?1:0?>);
    $("#admit_card").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: admitbirth?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var hsmarksheet = parseInt(<?=strlen($hs_marksheet)?1:0?>);
    $("#hs_marksheet").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: hsmarksheet?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var regcertificate = parseInt(<?=strlen($reg_certificate)?1:0?>);
    $("#reg_certificate").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: regcertificate?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var mbbsmarksheet = parseInt(<?=strlen($mbbs_marksheet)?1:0?>);
    $("#mbbs_marksheet").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: mbbsmarksheet?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var passcertificate = parseInt(<?=strlen($pass_certificate)?1:0?>);
    $("#pass_certificate").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: passcertificate?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var collegenoc = parseInt(<?=strlen($college_noc)?1:0?>);
    $("#college_noc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: collegenoc?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var directornoc = parseInt(<?=strlen($director_noc)?1:0?>);
    $("#director_noc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: directornoc?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var universitynoc = parseInt(<?=strlen($university_noc)?1:0?>);
    $("#university_noc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: universitynoc?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    var institutenoc = parseInt(<?=strlen($institute_noc)?1:0?>);
    $("#institute_noc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: institutenoc?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var eligibilitycertificate = parseInt(<?=strlen($eligibility_certificate)?1:0?>);
    $("#eligibility_certificate").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: eligibilitycertificate?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var screeningresult = parseInt(<?=strlen($screening_result)?1:0?>);
    $("#screening_result").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        // required: screeningresult?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var passportvisa = parseInt(<?=strlen($passport_visa)?1:0?>);
    $("#passport_visa").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: passportvisa?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var alldocs = parseInt(<?=strlen($all_docs)?1:0?>);
    $("#all_docs").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: alldocs?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    var annexure = parseInt(<?=strlen($annexure)?1:0?>);
    $("#annexure").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: alldocs?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var ten_pass_certificate = parseInt(<?=strlen($ten_pass_certificate)?1:0?>);
    $("#ten_pass_certificate").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: alldocs?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var signature = parseInt(<?=strlen($signature)?1:0?>);
    $("#signature").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: alldocs?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg","jpeg"]
    });
    var photograph = parseInt(<?=strlen($photograph)?1:0?>);
    $("#photograph").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: alldocs?false:true,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg","jpeg"]
    });

    $("#soft_copy").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    $(document).on("click", ".frmbtn", function() {
        let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
        $("#submit_mode").val(clickedBtn);
        if (clickedBtn === 'DRAFT') {
            var msg =
            "You want to save in Draft mode that will allows you to edit and can submit later";
        } else if (clickedBtn === 'SAVE') {
            var msg = "Once you submitted, you won't able to revert this";
        } else if (clickedBtn === 'CLEAR') {
            var msg = "Once you Reset, All filled data will be cleared";
        } else {
            var msg = "";
        } //End of if else            
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
                if ((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
                    $("#myfrm").submit();
                    $(".frmbtn").hide();
                } else if (clickedBtn === 'CLEAR') {
                    $("#myfrm")[0].reset();
                } else {} //End of if else
            }
        });
    });
});
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST"
            action="<?= base_url('spservices/acmr_provisional_certificate/registration/querysubmit') ?>"
            enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="admit_card_old" value="<?=$admit_card?>" type="hidden" />
            <input name="hs_marksheet_old" value="<?=$hs_marksheet?>" type="hidden" />
            <input name="reg_certificate_old" value="<?=$reg_certificate?>" type="hidden" />
            <input name="mbbs_marksheet_old" value="<?=$mbbs_marksheet?>" type="hidden" />
            <input name="pass_certificate_old" value="<?=$pass_certificate?>" type="hidden" />
            <input name="college_noc_old" value="<?=$college_noc?>" type="hidden" />
            <input name="director_noc_old" value="<?=$director_noc?>" type="hidden" />
            <input name="university_noc_old" value="<?=$university_noc?>" type="hidden" />
            <input name="institute_noc_old" value="<?=$institute_noc?>" type="hidden" />
            <input name="eligibility_certificate_old" value="<?=$eligibility_certificate?>" type="hidden" />
            <input name="screening_result_old" value="<?=$screening_result?>" type="hidden" />
            <input name="passport_visa_old" value="<?=$passport_visa?>" type="hidden" />
            <input name="all_docs_old" value="<?=$all_docs?>" type="hidden" />
            <input name="photograph_old" value="<?=$photograph?>" type="hidden" />
            <input name="signature_old" value="<?=$signature?>" type="hidden" />
            <input name="ten_pass_certificate_old" value="<?=$ten_pass_certificate?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <?=$service_name?>
                </div>
                <div class="card-body" style="padding:5px">

                    <?php if ($this->session->flashdata('fail') != null) { ?>
                    <script>
                    $(".frmbtn").show();
                    </script>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php }
                    if ($this->session->flashdata('error') != null) { ?>
                    <script>
                    $(".frmbtn").show();
                    </script>
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
                    <?php } if($status === 'QS') { ?>
                    <fieldset class="border border-danger" style="margin-top:40px; margin-bottom: 2px">
                        <legend class="h5">QUERY DETAILS </legend>
                        <div class="row">
                            <div class="col-md-12" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                <?=(end($dbrow->processing_history)->remarks)??''?>
                            </div>
                        </div>
                        <span style="float:right; font-size: 12px">
                            Query time :
                            <?=isset(end($dbrow->processing_history)->processing_time)?format_mongo_date(end($dbrow->processing_history)->processing_time):''?>
                        </span>
                    </fieldset>
                    <?php }//End of if ?>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name"
                                    value="<?=$applicant_name?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Father's Name/ দেউতাৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name"
                                    value="<?=$father_name?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mother's Name/মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name"
                                    value="<?=$mother_name?>" maxlength="255" />
                                <?= form_error("mother_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span>
                                </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($applicant_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($applicant_gender === "Female")?'selected':''?>>Female
                                    </option>
                                    <option value="Transgender"
                                        <?=($applicant_gender === "Transgender")?'selected':''?>>Transgender</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6 form-group">
                                <label>Date of Birth/ জন্ম তাৰিখ<span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="dob" id="dob" value="<?=$dob?>" maxlength="10"
                                    autocomplete="off" type="text" />
                                <?= form_error("dob") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" readonly
                                    maxlength="10" />
                                <?= form_error("mobile") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>"
                                    maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                            <div class="col-md-6"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Permanent Address/ স্থায়ী ঠিকনা <span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="permanent_addr"><?=$permanent_addr?></textarea>
                                <?= form_error("permanent_addr") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Correspondence Address/ চিঠিপত্ৰৰ ঠিকনা <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control"
                                    name="correspondence_addr"><?=$correspondence_addr?></textarea>
                                <?= form_error("correspondence_addr") ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Aadhar No./আধাৰ নং </label>
                                <input class="form-control number_input" name="aadhar_no" value="<?=$aadhar_no?>"
                                    maxlength="12" type="text" id="aadhar_no" />
                                <?= form_error("aadhar_no") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>PAN No./ পেন নং </label>
                                <input class="form-control pan_no" name="pan_no" value="<?=$pan_no?>" maxlength="10"
                                    type="text" />
                                <?= form_error("pan_no") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Primary Qualification / প্ৰাথমিক অৰ্হতা </legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Primary Qualification(MBBS or equivalent)/ প্ৰাথমিক অৰ্হতা(MBBS বা সমতুল্য) <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" name="primary_qualification"
                                    value="<?=$primary_qualification?>" type="text" />
                                <?= form_error("primary_qualification") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Month & year of passing the Final MBBS Exam/চূড়ান্ত এম.বি.বি.এছ. পৰীক্ষাত উত্তীৰ্ণ হোৱাৰ মাহ আৰু বছৰ* <span
                                        class="text-danger">*</span></label>
                                <input class="form-control dp" name="primary_qua_doc" value="<?=$primary_qua_doc?>"
                                    maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("primary_qua_doc") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>College Name/ কলেজৰ নাম <span class="text-danger">*</span></label>
                                <input class="form-control" name="primary_qua_college_name"
                                    value="<?=$primary_qua_college_name?>" type="text" />
                                <?= form_error("primary_qua_college_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Course Duration/ পাঠ্যক্ৰমৰ সময়সীমা <span class="text-danger">*</span></label>
                                <input class="form-control" name="primary_qua_course_dur"
                                    value="<?=$primary_qua_course_dur?>" type="text" />
                                <?= form_error("primary_qua_course_dur") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Date of Commeencement of Internship/ ইন্টাৰশ্বিপ সম্পূৰ্ণ হোৱাৰ তাৰিখ </label>
                                <input class="form-control dp" name="primary_qua_doci" value="<?=$primary_qua_doci?>"
                                    maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("primary_qua_doci") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Name of the university awarding the degree/ ইন্টাৰশ্বিপ প্ৰদান কৰা
                                    বিশ্ববিদ্যালয় <span
                                        class="text-danger">*</span> </label>
                                <input class="form-control" name="primary_qua_university_award_intership"
                                    value="<?=$primary_qua_university_award_intership?>" type="text" />
                                <?= form_error("primary_qua_university_award_intership") ?>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6 form-group">
                                <label>University Roll Number/বিশ্ববিদ্যালয় ৰোল নম্বৰ</label>
                                <input class="form-control dp" name="university_roll_no" value="<?=$university_roll_no?>"
                                    maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("university_roll_no") ?>
                            </div>
                            </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Please select where the Applicant Studied <span class="text-danger">*</span>
                                </label>
                                <select name="study_place" id="study_place" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php if(!empty($study_place)){ ?>
                                    <option value="1" <?php ($study_place == 1)? print "selected": '' ?>>Applicant
                                        studied within the State/Meghalaya</option>
                                    <option value="2" <?php ($study_place == 2)? print "selected": '' ?>>Applicant
                                        studied outside the State/Meghalaya but within India</option>
                                    <option value="3" <?php ($study_place == 3)? print "selected": '' ?>>Applicant
                                        studied outside the Country</option>
                                    <?php } else{?>
                                    <option value="1">Applicant studied within the State/Meghalaya</option>
                                    <option value="2">Applicant studied outside the State/Meghalaya but within India
                                    </option>
                                    <option value="3">Applicant studied outside the Country</option>
                                    <?php }?>
                                </select>
                                <?= form_error("study_place") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset id="applicant_studied" class="border border-success"
                        style="margin-top: 40px; display: <?= isset($study_place) ? 'block' : 'none' ?>;">
                        <legend class="h5">Address of Study Place</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Address Line 1 / ঠিকনাৰ প্ৰথম শাৰী<span class="text-danger"></span> </label>
                                <input type="text" class="form-control" name="address1" value="<?= $address1 ?>"
                                    maxlength="255" />
                                <?= form_error("address1") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address Line 2/ ঠিকনাৰ চিতীয় শাৰ<span class="text-danger"></span> </label>
                                <input type="text" class="form-control" name="address2" value="<?= $address2 ?>"
                                    maxlength="255" />
                                <?= form_error("address2") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div id="con" class="col-md-4 form-group"
                                style="display: <?= ($study_place == 3) ? 'block' : 'none' ?>;">
                                <label>Country/দেশ <span class="text-danger">*</span> </label>
                                <select name="country" id="country" class="form-control country">
                                    <option value="">Please Select</option>
                                    <option value="India" <?= ($country === 'India') ? 'selected' : '' ?>>India</option>
                                    <?php if ($countries) {
                                        foreach ($countries as $countryObj) {
                                            if ($countryObj->country_name !== 'India') {
                                                $selected = ($country === $countryObj->country_name) ? 'selected' : '';
                                                echo '<option value="' . $countryObj->country_name . '" ' . $selected . '>' . $countryObj->country_name . '</option>';
                                            }
                                        }
                                    } ?>
                                </select>
                                <?= form_error('country') ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div id="sid" class="col-md-4 form-group"
                                style="display: <?= ($study_place == 3) ? 'none' : 'block' ?>;">
                                <label>State/ৰাজ্য<span class="text-danger">*</span></label>
                                <div id="did">
                                    <?php if ($country === 'India') { ?>
                                    <select name="statee" id="statee" class="form-control">
                                        <option value="">Please Select</option>
                                        <?php foreach ($states as $stateObj) : ?>
                                        <?php $selected = ($statee === $stateObj->slc) ? 'selected' : ''; ?>
                                        <option value="<?= $stateObj->slc ?>" <?= $selected ?>>
                                            <?= $stateObj->state_name_english ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php } else { ?>
                                    <input type="text" class="form-control" name="statee" value="<?= $statee ?>"
                                        maxlength="255" />
                                    <?php } ?>
                                </div>
                                <?= form_error('statee') ?>
                            </div>

                            <div id="state_f" class="col-md-4 form-group"
                                style="display: <?= ($study_place == 3) ? 'block' : 'none' ?>;">
                                <label>State/ৰাজ্য/Province/প্ৰদেশ </label>
                                <input type="text" class="form-control" name="state_foreign" id="state_foreign"
                                    value="<?= $state_foreign ?>" maxlength="255" />
                                <?= form_error("state_foreign") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 form-group" id="district_div"
                                style="display: <?= ($study_place == 3) ? 'none' : 'block' ?>;">
                                <label id="did1">District/জিলা<span class="text-danger">*</span> </label>
                                <div id="did">
                                    <?php if ($country === 'India') { ?>
                                    <select name="district" id="district" class="form-control">
                                        <option value="">Please Select</option>
                                        <?php if ($districts) {
                    foreach ($districts as $districtObj) {
                        $selected = ($district === $districtObj->district_name) ? 'selected' : '';
                        echo '<option value="' . $districtObj->district_name . '" ' . $selected . '>' . $districtObj->district_name . '</option>';
                    }
                } ?>
                                    </select>
                                    <?php } else { ?>
                                    <input type="text" class="form-control" name="district" value="<?= $district ?>"
                                        maxlength="255" />
                                    <?php } ?>
                                </div>
                                <?= form_error('district') ?>
                            </div>

                            <div class="col-md-4" id="pincode_div"
                                style="display: <?= ($study_place == 3) ? 'none' : 'block' ?>;">
                                <label>Pincode/ পিনকোড<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pincode" id="pincode"
                                    value="<?= $pincode ?>" maxlength="6" pattern="\d{6}" />
                                <?= form_error("pincode") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী
                            :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. The size of documents to be uploaded at the time of Application submission should not
                                exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>১. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো
                                অনিবাৰ্য।</li>
                        </ul>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:0px">
                        <p>If you wish to upload your enclosures/documents from digilocker, please link your digilocker
                            account. Click on the below button to link your account.</p>
                        <p><?php $this->digilocker_api->login_btn();  ?></p>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">ATTACH ENCLOSURE(S) </legend>
                        <div class="row mt-3">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Type of Enclosure</th>
                                            <th>Enclosure Document</th>
                                            <th>File/Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(($dbrow->form_data->study_place == 1) || ($dbrow->form_data->study_place == 2) || ($dbrow->form_data->study_place == 3)){ ?>
                                        <tr>
                                            <td>Photograph<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="photograph_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Photograph"
                                                        <?=($photograph_type === 'Photograph')?'selected':''?>>
                                                        Photograph</option>
                                                </select>
                                                <?= form_error("photograph_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="photograph" name="photograph" type="file" />
                                                </div>
                                                <?php if(strlen($photograph)){ ?>
                                                <a href="<?=base_url($photograph)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="photograph" type="hidden" name="photograph_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('photograph'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Signature<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="signature_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Signature"
                                                        <?=($signature_type === 'Signature')?'selected':''?>>Signature
                                                    </option>
                                                </select>
                                                <?= form_error("signature_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="signature" name="signature" type="file" />
                                                </div>
                                                <?php if(strlen($signature)){ ?>
                                                <a href="<?=base_url($signature)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="signature" type="hidden" name="signature_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('signature'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Class 10 Pass Certificate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="ten_pass_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Class 10 Pass Certificate"
                                                        <?=($ten_pass_certificate_type === 'Class 10 Pass Certificate')?'selected':''?>>
                                                        Class 10 Pass Certificate</option>
                                                </select>
                                                <?= form_error("ten_pass_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="ten_pass_certificate" name="ten_pass_certificate"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($ten_pass_certificate)){ ?>
                                                <a href="<?=base_url($ten_pass_certificate)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="ten_pass_certificate" type="hidden"
                                                    name="ten_pass_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('ten_pass_certificate'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Class 10 Admit card<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="admit_card_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Class 10 Admit card"
                                                        <?=($admit_card_type === 'Class 10 Admit card')?'selected':''?>>
                                                        Class 10 Admit card</option>
                                                </select>
                                                <?= form_error("admit_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="admit_card" name="admit_card" type="file" />
                                                </div>
                                                <?php if(strlen($admit_card)){ ?>
                                                <a href="<?=base_url($admit_card)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="admit_card" type="hidden" name="admit_card_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('admit_card'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>HS Final Marksheet<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="hs_marksheet_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="HS Final Marksheet"
                                                        <?=($hs_marksheet_type === 'HS Final Marksheet')?'selected':''?>>
                                                        HS Final Marksheet</option>
                                                </select>
                                                <?= form_error("hs_marksheet_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hs_marksheet" name="hs_marksheet" type="file" />
                                                </div>
                                                <?php if(strlen($hs_marksheet)){ ?>
                                                <a href="<?=base_url($hs_marksheet)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="hs_marksheet" type="hidden" name="hs_marksheet_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('hs_marksheet'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>University Registration Certificate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="reg_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option
                                                        value="University Registration Certificate"
                                                        <?=($reg_certificate_type === 'University Registration Certificate')?'selected':''?>>
                                                        University Registration Certificate</option>
                                                </select>
                                                <?= form_error("reg_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="reg_certificate" name="reg_certificate" type="file" />
                                                </div>
                                                <?php if(strlen($reg_certificate)){ ?>
                                                <a href="<?=base_url($reg_certificate)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="reg_certificate" type="hidden"
                                                    name="reg_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('reg_certificate'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>All Marksheets of MBBS/Transcript<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="mbbs_marksheet_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="All Marksheets of MBBS"
                                                        <?=($mbbs_marksheet_type === 'All Marksheets of MBBS')?'selected':''?>>
                                                        All Marksheets of MBBS</option>
                                                </select>
                                                <?= form_error("mbbs_marksheet_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="mbbs_marksheet" name="mbbs_marksheet" type="file" />
                                                </div>
                                                <?php if(strlen($mbbs_marksheet)){ ?>
                                                <a href="<?=base_url($mbbs_marksheet)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="mbbs_marksheet" type="hidden" name="mbbs_marksheet_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('mbbs_marksheet'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>MBBS or equivalent Pass Certificate from College/University</td>
                                            <td>
                                                <select name="pass_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="MBBS or equivalent Pass Certificate from College/University"
                                                        <?=($pass_certificate_type === 'MBBS or equivalent Pass Certificate from College/University')?'selected':''?>>
                                                        MBBS or equivalent Pass Certificate from College/University</option>
                                                </select>
                                                <?= form_error("pass_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="pass_certificate" name="pass_certificate" type="file" />
                                                </div>
                                                <?php if(strlen($pass_certificate)){ ?>
                                                <a href="<?=base_url($pass_certificate)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="pass_certificate" type="hidden"
                                                    name="pass_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('pass_certificate'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Annexure II</td>
                                            <td>
                                                <select name="annexure_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Annexure II"
                                                        <?=($annexure_type === 'Annexure II')?'selected':''?>>Annexure
                                                        II</option>
                                                </select>
                                                <?= form_error("annexure_type_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="annexure" name="annexure" type="file" />
                                                </div>
                                                <?php if(strlen($annexure)){ ?>
                                                <a href="<?=base_url($annexure)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="annexure" type="hidden" name="annexure_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('annexure'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(($dbrow->form_data->study_place == 2) || ($dbrow->form_data->study_place == 3)){ ?>
                                        <tr>
                                            <td>NOC from College/University</td>
                                            <td>
                                                <select name="college_noc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="NOC from College/University"
                                                        <?=($college_noc_type === 'NOC from College/University')?'selected':''?>>
                                                        NOC from College/University</option>
                                                </select>
                                                <?= form_error("college_noc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="college_noc" name="college_noc" type="file" />
                                                </div>
                                                <?php if(strlen($college_noc)){ ?>
                                                <a href="<?=base_url($college_noc)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="college_noc" type="hidden" name="college_noc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('college_noc'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>NOC from Director of Medical Education, Assam</td>
                                            <td>
                                                <select name="director_noc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="NOC from Director of Medical Education,Assam"
                                                        <?=($director_noc_type === 'NOC from Director of Medical Education,Assam')?'selected':''?>>
                                                        NOC from Director of Medical Education, Assam</option>
                                                </select>
                                                <?= form_error("director_noc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="director_noc" name="director_noc" type="file" />
                                                </div>
                                                <?php if(strlen($director_noc)){ ?>
                                                <a href="<?=base_url($director_noc)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="director_noc" type="hidden" name="director_noc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('director_noc'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>NOC from Srimanta Sankaradeva University of Health Sciences</td>
                                            <td>
                                                <select name="university_noc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option
                                                        value="NOC from Srimanta Sankaradeva University of Health Sciences"
                                                        <?=($university_noc_type === 'NOC from Srimanta Sankaradeva University of Health Sciences')?'selected':''?>>
                                                        NOC from Srimanta Sankaradeva University of Health Sciences
                                                    </option>
                                                </select>
                                                <?= form_error("university_noc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="university_noc" name="university_noc" type="file" />
                                                </div>
                                                <?php if(strlen($university_noc)){ ?>
                                                <a href="<?=base_url($university_noc)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="university_noc" type="hidden" name="university_noc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('university_noc'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>NOC from the Institute from where applicant want to do the internship
                                            </td>
                                            <td>
                                                <select name="institute_noc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option
                                                        value="NOC from the Institute from where applicant want to do the internship"
                                                        <?=($institute_noc_type === 'NOC from the Institute from where applicant want to do the internship')?'selected':''?>>
                                                        NOC from the Institute from where applicant want to do the
                                                        internship</option>
                                                </select>
                                                <?= form_error("institute_noc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="institute_noc" name="institute_noc" type="file" />
                                                </div>
                                                <?php if(strlen($institute_noc)){ ?>
                                                <a href="<?=base_url($institute_noc)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="institute_noc" type="hidden" name="institute_noc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('institute_noc'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <?php if($dbrow->form_data->study_place == 3){ ?>
                                        <tr>
                                            <td>Eligibility Certificate from National Medical Commission</td>
                                            <td>
                                                <select name="eligibility_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option
                                                        value="Eligibility Certificate from National Medical Commission"
                                                        <?=($eligibility_certificate_type === 'Eligibility Certificate from National Medical Commission')?'selected':''?>>
                                                        Eligibility Certificate from National Medical Commission
                                                    </option>
                                                </select>
                                                <?= form_error("eligibility_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="eligibility_certificate" name="eligibility_certificate"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($eligibility_certificate)){ ?>
                                                <a href="<?=base_url($eligibility_certificate)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="eligibility_certificate" type="hidden"
                                                    name="eligibility_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('eligibility_certificate'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>FMGE Marksheet</td>
                                            <td>
                                                <select name="screening_result_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option
                                                        value="FMGE Marksheet"
                                                        <?=($screening_result_type === 'FMGE Marksheet')?'selected':''?>>
                                                        FMGE Marksheet
                                                    </option>
                                                </select>
                                                <?= form_error("screening_result_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="screening_result" name="screening_result" type="file" />
                                                </div>
                                                <?php if(strlen($screening_result)){ ?>
                                                <a href="<?=base_url($screening_result)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="screening_result" type="hidden"
                                                    name="screening_result_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('screening_result'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Passport and Visa with travel details</td>
                                            <td>
                                                <select name="passport_visa_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Passport and Visa with travel details"
                                                        <?=($passport_visa_type === 'Passport and Visa with travel details')?'selected':''?>>
                                                        Passport and Visa with travel details</option>
                                                </select>
                                                <?= form_error("passport_visa_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="passport_visa" name="passport_visa" type="file" />
                                                </div>
                                                <?php if(strlen($passport_visa)){ ?>
                                                <a href="<?=base_url($passport_visa)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="passport_visa" type="hidden" name="passport_visa_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('passport_visa'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>All documents related to medical college details</td>
                                            <td>
                                                <select name="all_docs_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="All documents related to medical college details"
                                                        <?=($all_docs_type === 'All documents related to medical college details')?'selected':''?>>
                                                        All documents related to medical college details</option>
                                                </select>
                                                <?= form_error("all_docs_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="all_docs" name="all_docs" type="file" />
                                                </div>
                                                <?php if(strlen($all_docs)){ ?>
                                                <a href="<?=base_url($all_docs)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="all_docs" type="hidden" name="all_docs_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('all_docs'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <?php if($this->slug == 'userrr') { ?>
                                        <tr>
                                            <td>Soft copy of the applicant form<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="soft_copy_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Soft copy of the applicant form"
                                                        <?=($soft_copy_type === 'Soft copy of the applicant form')?'selected':''?>>
                                                        Soft copy of the applicant form</option>
                                                </select>
                                                <?= form_error("soft_copy_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="soft_copy" name="soft_copy" type="file" />
                                                </div>
                                                <?php if(strlen($soft_copy)){ ?>
                                                <a href="<?=base_url($soft_copy)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download (Old Document)
                                                </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <?php }//End of if ?>
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
                                    <td><?=date('d/m/Y h:i a', strtotime($this->mongo_db->getDateTime($rows->processing_time)))?>
                                    </td>
                                    <td><?=$rows->action_taken?></td>
                                    <td><?=$rows->remarks?></td>
                                </tr>
                                <?php }//End of foreach()
                                }//End of if else ?>
                            </tbody>
                        </table>
                    </fieldset>
                </div>
                <!--End of .card-body -->

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
                </div>
                <!--End of .card-footer-->
            </div>
            <!--End of .card-->
        </form>
    </div>
    <!--End of .container-->
</main>