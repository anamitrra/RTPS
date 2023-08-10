<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
// $apiURL= "http://13.233.207.96/MMSGNA-TEST/api/"; //For testing
$apiURL = "https://rtps.mmsgna.in/api/"; //For testing

if ($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $pageTitle = $dbrow->service_data->service_name;
    $pageTitleId = $dbrow->form_data->service_id;
    $first_name = $dbrow->form_data->first_name ?? set_value("first_name");
    $last_name = $dbrow->form_data->last_name ?? set_value("last_name");
    $gender = $dbrow->form_data->applicant_gender ?? set_value("gender");
    $father_name = $dbrow->form_data->father_name ?? set_value("father_name");
    $mobile = $dbrow->form_data->mobile ?? set_value("mobile");
    $email = $dbrow->form_data->email ?? set_value("email");

    $circle_office = $dbrow->form_data->circle_office ?? set_value("circle_office");
    $mouza_name = $dbrow->form_data->mouza_name ?? set_value("mouza_name");

    $revenue_village = $dbrow->form_data->revenue_village ?? set_value("revenue_village");
    $police_station = $dbrow->form_data->police_station ?? set_value("police_station");
    $post_office = $dbrow->form_data->post_office ?? set_value("post_office");


    // $address_proof_type = isset($dbrow->form_data->address_proof_type) ? $dbrow->form_data->address_proof_type : '';
    // $address_proof = isset($dbrow->form_data->address_proof) ? $dbrow->form_data->address_proof : '';
    // $identity_proof_type = isset($dbrow->form_data->identity_proof_type) ? $dbrow->form_data->identity_proof_type : '';
    // $identity_proof = isset($dbrow->form_data->identity_proof) ? $dbrow->form_data->identity_proof : '';
    // $land_patta_copy_type = isset($dbrow->form_data->land_patta_copy_type) ? $dbrow->form_data->land_patta_copy_type : '';
    // $land_patta_copy = isset($dbrow->form_data->land_patta_copy) ? $dbrow->form_data->land_patta_copy : '';
    // $updated_land_revenue_receipt_type = isset($dbrow->form_data->updated_land_revenue_receipt_type) ? $dbrow->form_data->updated_land_revenue_receipt_type : '';
    // $updated_land_revenue_receipt = isset($dbrow->form_data->updated_land_revenue_receipt) ? $dbrow->form_data->updated_land_revenue_receipt : '';
    // $Up_to_date_original_land_documents_type = isset($dbrow->form_data->Up_to_date_original_land_documents_type) ? $dbrow->form_data->Up_to_date_original_land_documents_type : '';
    // $Up_to_date_original_land_documents = isset($dbrow->form_data->Up_to_date_original_land_documents) ? $dbrow->form_data->Up_to_date_original_land_documents : '';
    // $up_to_date_khajna_receipt_type = isset($dbrow->form_data->up_to_date_khajna_receipt_type) ? $dbrow->form_data->up_to_date_khajna_receipt_type : '';
    // $up_to_date_khajna_receipt = isset($dbrow->form_data->up_to_date_khajna_receipt) ? $dbrow->form_data->up_to_date_khajna_receipt : '';

    // $copy_of_jamabandi_type = isset($dbrow->form_data->copy_of_jamabandi_type) ? $dbrow->form_data->copy_of_jamabandi_type : '';
    // $copy_of_jamabandi = isset($dbrow->form_data->copy_of_jamabandi) ? $dbrow->form_data->copy_of_jamabandi : '';

    // $revenue_patta_copy_type = isset($dbrow->form_data->revenue_patta_copy_type) ? $dbrow->form_data->revenue_patta_copy_type : '';
    // $revenue_patta_copy = isset($dbrow->form_data->revenue_patta_copy) ? $dbrow->form_data->revenue_patta_copy : '';
    // $copy_of_chitha_type = isset($dbrow->form_data->copy_of_chitha_type) ? $dbrow->form_data->copy_of_chitha_type : '';
    // $copy_of_chitha = isset($dbrow->form_data->copy_of_chitha) ? $dbrow->form_data->copy_of_chitha : '';
    // $settlement_land_patta_copy_type = isset($dbrow->form_data->settlement_land_patta_copy_type) ? $dbrow->form_data->settlement_land_patta_copy_type : '';
    // $settlement_land_patta_copy = isset($dbrow->form_data->settlement_land_patta_copy) ? $dbrow->form_data->settlement_land_patta_copy : '';
    // $land_revenue_receipt_type = isset($dbrow->form_data->land_revenue_receipt_type) ? $dbrow->form_data->land_revenue_receipt_type : '';
    // $land_revenue_receipt = isset($dbrow->form_data->land_revenue_receipt) ? $dbrow->form_data->land_revenue_receipt : '';
    // $police_verification_report_type = isset($dbrow->form_data->police_verification_report_type) ? $dbrow->form_data->police_verification_report_type : '';
    // $police_verification_report = isset($dbrow->form_data->police_verification_report) ? $dbrow->form_data->police_verification_report : '';
    // $photocopy_of_existing_land_documents_type = isset($dbrow->form_data->photocopy_of_existing_land_documents_type) ? $dbrow->form_data->photocopy_of_existing_land_documents_type : '';
    // $photocopy_of_existing_land_documents = isset($dbrow->form_data->photocopy_of_existing_land_documents) ? $dbrow->form_data->photocopy_of_existing_land_documents : '';

    // $no_dues_certificate_from_bank_type = isset($dbrow->form_data->no_dues_certificate_from_bank_type) ? $dbrow->form_data->no_dues_certificate_from_bank_type : '';
    // $no_dues_certificate_from_bank = isset($dbrow->form_data->no_dues_certificate_from_bank) ? $dbrow->form_data->no_dues_certificate_from_bank : '';

    // $last_time_paid_Land_revenue_receipt_type = isset($dbrow->form_data->last_time_paid_Land_revenue_receipt_type) ? $dbrow->form_data->last_time_paid_Land_revenue_receipt_type : '';
    // $last_time_paid_Land_revenue_receipt = isset($dbrow->form_data->last_time_paid_Land_revenue_receipt) ? $dbrow->form_data->last_time_paid_Land_revenue_receipt : '';

    //newly added by Bishwajit
    $applicant_title = $dbrow->form_data->applicant_title ?? set_value("applicant_title");
    $father_title = $dbrow->form_data->father_title ?? set_value("father_title");
    $periodic_patta_no = isset($dbrow->form_data->periodic_patta_no) ? $dbrow->form_data->periodic_patta_no : '';
    $dag_no = $dbrow->form_data->dag_no ?? set_value("dag_no");
    $land_area_bigha = $dbrow->form_data->land_area_bigha ?? set_value("land_area_bigha");
    $land_area_katha = $dbrow->form_data->land_area_katha ?? set_value("land_area_katha");
    $land_area_loosa = $dbrow->form_data->land_area_loosa ?? set_value("land_area_loosa");
    $land_area_sq_ft = $dbrow->form_data->land_area_sq_ft ?? set_value("land_area_sq_ft");
    $patta_type = $dbrow->form_data->patta_type ?? set_value("patta_type");
    // pre($land_area_bigha);
    $mut_name_title = $dbrow->form_data->mut_name_title ?? set_value("mut_name_title");
    $mut_father_title = $dbrow->form_data->mut_father_title ?? set_value("mut_father_title");
    $mut_first_name = $dbrow->form_data->mut_first_name ?? set_value("mut_first_name");
    $mut_last_name = $dbrow->form_data->mut_last_name ?? set_value("mut_last_name");
    $mut_gender = $dbrow->form_data->mut_gender ?? set_value("mut_gender");
    $mut_father_name = $dbrow->form_data->mut_father_name ?? set_value("mut_father_name");
    $mut_mobile = $dbrow->form_data->mut_mobile ?? set_value("mut_mobile");
    $mut_email = $dbrow->form_data->mut_email ?? set_value("mut_email");

    $address_proof_type_frm = set_value("address_proof_type");
    $address_proof_frm = $uploadedFiles['address_proof_old'] ?? null;
    $address_proof_type_db = $dbrow->form_data->address_proof_type ?? null;
    $address_proof_db = $dbrow->form_data->address_proof ?? null;
    $address_proof = strlen($address_proof_frm) ? $address_proof_frm : $address_proof_db;
    $address_proof_type = strlen($address_proof_type_frm) ? $address_proof_type_frm : $address_proof_type_db;
    
    
    $identity_proof_type_frm = set_value("identity_proof_type");
    $identity_proof_frm = $uploadedFiles['identity_proof_old'] ?? null;
    $identity_proof_type_db = $dbrow->form_data->identity_proof_type ?? null;
    $identity_proof_db = $dbrow->form_data->identity_proof ?? null;
    $identity_proof = strlen($identity_proof_frm) ? $identity_proof_frm : $identity_proof_db;
    $identity_proof_type = strlen($identity_proof_type_frm) ? $identity_proof_type_frm : $identity_proof_type_db;
    
    
    $land_patta_copy_type_frm = set_value("land_patta_copy_type");
    $land_patta_copy_frm = $uploadedFiles['land_patta_copy_old'] ?? null;
    $land_patta_copy_type_db = $dbrow->form_data->land_patta_copy_type ?? null;
    $land_patta_copy_db = $dbrow->form_data->land_patta_copy ?? null;
    $land_patta_copy = strlen($land_patta_copy_frm) ? $land_patta_copy_frm : $land_patta_copy_db;
    $land_patta_copy_type = strlen($land_patta_copy_type_frm) ? $land_patta_copy_type_frm : $land_patta_copy_type_db;
    
    
    $updated_land_revenue_receipt_type_frm = set_value("updated_land_revenue_receipt_type");
    $updated_land_revenue_receipt_frm = $uploadedFiles['updated_land_revenue_receipt_old'] ?? null;
    $updated_land_revenue_receipt_type_db = $dbrow->form_data->updated_land_revenue_receipt_type ?? null;
    $updated_land_revenue_receipt_db = $dbrow->form_data->updated_land_revenue_receipt ?? null;
    $updated_land_revenue_receipt = strlen($updated_land_revenue_receipt_frm) ? $updated_land_revenue_receipt_frm : $updated_land_revenue_receipt_db;
    $updated_land_revenue_receipt_type = strlen($updated_land_revenue_receipt_type_frm) ? $updated_land_revenue_receipt_type_frm : $updated_land_revenue_receipt_type_db;
    
    
    $Up_to_date_original_land_documents_type_frm = set_value("Up_to_date_original_land_documents_type");
    $Up_to_date_original_land_documents_frm = $uploadedFiles['Up_to_date_original_land_documents_old'] ?? null;
    $Up_to_date_original_land_documents_type_db = $dbrow->form_data->Up_to_date_original_land_documents_type ?? null;
    $Up_to_date_original_land_documents_db = $dbrow->form_data->Up_to_date_original_land_documents ?? null;
    $Up_to_date_original_land_documents = strlen($Up_to_date_original_land_documents_frm) ? $Up_to_date_original_land_documents_frm : $Up_to_date_original_land_documents_db;
    $Up_to_date_original_land_documents_type = strlen($Up_to_date_original_land_documents_type_frm) ? $Up_to_date_original_land_documents_type_frm : $Up_to_date_original_land_documents_type_db;
    
    
    $up_to_date_khajna_receipt_type_frm = set_value("up_to_date_khajna_receipt_type");
    $up_to_date_khajna_receipt_frm = $uploadedFiles['up_to_date_khajna_receipt_old'] ?? null;
    $up_to_date_khajna_receipt_type_db = $dbrow->form_data->up_to_date_khajna_receipt_type ?? null;
    $up_to_date_khajna_receipt_db = $dbrow->form_data->up_to_date_khajna_receipt ?? null;
    $up_to_date_khajna_receipt = strlen($up_to_date_khajna_receipt_frm) ? $up_to_date_khajna_receipt_frm : $up_to_date_khajna_receipt_db;
    $up_to_date_khajna_receipt_type = strlen($up_to_date_khajna_receipt_type_frm) ? $up_to_date_khajna_receipt_type_frm : $up_to_date_khajna_receipt_type_db;
    
    
    $copy_of_jamabandi_type_frm = set_value("copy_of_jamabandi_type");
    $copy_of_jamabandi_frm = $uploadedFiles['copy_of_jamabandi_old'] ?? null;
    $copy_of_jamabandi_type_db = $dbrow->form_data->copy_of_jamabandi_type ?? null;
    $copy_of_jamabandi_db = $dbrow->form_data->copy_of_jamabandi ?? null;
    $copy_of_jamabandi = strlen($copy_of_jamabandi_frm) ? $copy_of_jamabandi_frm : $copy_of_jamabandi_db;
    $copy_of_jamabandi_type = strlen($copy_of_jamabandi_type_frm) ? $copy_of_jamabandi_type_frm : $copy_of_jamabandi_type_db;
    
    $revenue_patta_copy_type_frm = set_value("revenue_patta_copy_type");
    $revenue_patta_copy_frm = $uploadedFiles['revenue_patta_copy_old'] ?? null;
    $revenue_patta_copy_type_db = $dbrow->form_data->revenue_patta_copy_type ?? null;
    $revenue_patta_copy_db = $dbrow->form_data->revenue_patta_copy ?? null;
    $revenue_patta_copy = strlen($revenue_patta_copy_frm) ? $revenue_patta_copy_frm : $revenue_patta_copy_db;
    $revenue_patta_copy_type = strlen($revenue_patta_copy_type_frm) ? $revenue_patta_copy_type_frm : $revenue_patta_copy_type_db;
    
    
    $copy_of_chitha_type_frm = set_value("copy_of_chitha_type");
    $copy_of_chitha_frm = $uploadedFiles['copy_of_chitha_old'] ?? null;
    $copy_of_chitha_type_db = $dbrow->form_data->copy_of_chitha_type ?? null;
    $copy_of_chitha_db = $dbrow->form_data->copy_of_chitha ?? null;
    $copy_of_chitha = strlen($copy_of_chitha_frm) ? $copy_of_chitha_frm : $copy_of_chitha_db;
    $copy_of_chitha_type = strlen($copy_of_chitha_type_frm) ? $copy_of_chitha_type_frm : $copy_of_chitha_type_db;
    
    
    $settlement_land_patta_copy_type_frm = set_value("settlement_land_patta_copy_type");
    $settlement_land_patta_copy_frm = $uploadedFiles['settlement_land_patta_copy_old'] ?? null;
    $settlement_land_patta_copy_type_db = $dbrow->form_data->settlement_land_patta_copy_type ?? null;
    $settlement_land_patta_copy_db = $dbrow->form_data->settlement_land_patta_copy ?? null;
    $settlement_land_patta_copy = strlen($settlement_land_patta_copy_frm) ? $settlement_land_patta_copy_frm : $settlement_land_patta_copy_db;
    $settlement_land_patta_copy_type = strlen($settlement_land_patta_copy_type_frm) ? $settlement_land_patta_copy_type_frm : $settlement_land_patta_copy_type_db;
    
    
    $land_revenue_receipt_type_frm = set_value("land_revenue_receipt_type");
    $land_revenue_receipt_frm = $uploadedFiles['land_revenue_receipt_old'] ?? null;
    $land_revenue_receipt_type_db = $dbrow->form_data->land_revenue_receipt_type ?? null;
    $land_revenue_receipt_db = $dbrow->form_data->land_revenue_receipt ?? null;
    $land_revenue_receipt = strlen($land_revenue_receipt_frm) ? $land_revenue_receipt_frm : $land_revenue_receipt_db;
    $land_revenue_receipt_type = strlen($land_revenue_receipt_type_frm) ? $land_revenue_receipt_type_frm : $land_revenue_receipt_type_db;
    
    
    $police_verification_report_type_frm = set_value("police_verification_report_type");
    $police_verification_report_frm = $uploadedFiles['police_verification_report_old'] ?? null;
    $police_verification_report_type_db = $dbrow->form_data->police_verification_report_type ?? null;
    $police_verification_report_db = $dbrow->form_data->police_verification_report ?? null;
    $police_verification_report = strlen($police_verification_report_frm) ? $police_verification_report_frm : $police_verification_report_db;
    $police_verification_report_type = strlen($police_verification_report_type_frm) ? $police_verification_report_type_frm : $police_verification_report_type_db;
    
    
    $photocopy_of_existing_land_documents_type_frm = set_value("photocopy_of_existing_land_documents_type");
    $photocopy_of_existing_land_documents_frm = $uploadedFiles['photocopy_of_existing_land_documents_old'] ?? null;
    $photocopy_of_existing_land_documents_type_db = $dbrow->form_data->photocopy_of_existing_land_documents_type ?? null;
    $photocopy_of_existing_land_documents_db = $dbrow->form_data->photocopy_of_existing_land_documents ?? null;
    $photocopy_of_existing_land_documents = strlen($photocopy_of_existing_land_documents_frm) ? $photocopy_of_existing_land_documents_frm : $photocopy_of_existing_land_documents_db;
    $photocopy_of_existing_land_documents_type = strlen($photocopy_of_existing_land_documents_type_frm) ? $photocopy_of_existing_land_documents_type_frm : $photocopy_of_existing_land_documents_type_db;
    
    
    $no_dues_certificate_from_bank_type_frm = set_value("no_dues_certificate_from_bank_type");
    $no_dues_certificate_from_bank_frm = $uploadedFiles['no_dues_certificate_from_bank_old'] ?? null;
    $no_dues_certificate_from_bank_type_db = $dbrow->form_data->no_dues_certificate_from_bank_type ?? null;
    $no_dues_certificate_from_bank_db = $dbrow->form_data->no_dues_certificate_from_bank ?? null;
    $no_dues_certificate_from_bank = strlen($no_dues_certificate_from_bank_frm) ? $no_dues_certificate_from_bank_frm : $no_dues_certificate_from_bank_db;
    $no_dues_certificate_from_bank_type = strlen($no_dues_certificate_from_bank_type_frm) ? $no_dues_certificate_from_bank_type_frm : $no_dues_certificate_from_bank_type_db;
    
    
    $last_time_paid_Land_revenue_receipt_type_frm = set_value("last_time_paid_Land_revenue_receipt_type");
    $last_time_paid_Land_revenue_receipt_frm = $uploadedFiles['last_time_paid_Land_revenue_receipt_old'] ?? null;
    $last_time_paid_Land_revenue_receipt_type_db = $dbrow->form_data->last_time_paid_Land_revenue_receipt_type ?? null;
    $last_time_paid_Land_revenue_receipt_db = $dbrow->form_data->last_time_paid_Land_revenue_receipt ?? null;
    $last_time_paid_Land_revenue_receipt = strlen($last_time_paid_Land_revenue_receipt_frm) ? $last_time_paid_Land_revenue_receipt_frm : $last_time_paid_Land_revenue_receipt_db;
    $last_time_paid_Land_revenue_receipt_type = strlen($last_time_paid_Land_revenue_receipt_type_frm) ? $last_time_paid_Land_revenue_receipt_type_frm : $last_time_paid_Land_revenue_receipt_type_db;
}
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


        $("#land_patta_copy").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        $("#updated_land_revenue_receipt").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        $("#Up_to_date_original_land_documents").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#address_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        $("#identity_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#up_to_date_khajna_receipt").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#copy_of_jamabandi").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        $("#revenue_patta_copy").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        $("#copy_of_chitha").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        $("#settlement_land_patta_copy").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        $("#land_revenue_receipt").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        $("#police_verification_report").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        $("#photocopy_of_existing_land_documents").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        $("#no_dues_certificate_from_bank").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        $("#last_time_paid_Land_revenue_receipt").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", ".frmbtn", function() {
            let clickedBtn = $(this).attr("id");
            // alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if (clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if (clickedBtn === 'SAVE') {
                var msg = "Do you want to procced?";
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/kaac/registration/querysubmit') ?>" enctype="multipart/form-data">

            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="service_name" value="<?= $pageTitle ?>" type="hidden" />
            <input name="service_id" value="<?= $pageTitleId ?>" type="hidden" />
            <input name="address_proof_old" value="<?= $address_proof ?>" type="hidden" />
            <input name="identity_proof_old" value="<?= $identity_proof ?>" type="hidden" />
            <input name="land_patta_copy_old" value="<?= $land_patta_copy ?>" type="hidden" />
            <input name="updated_land_revenue_receipt_old" value="<?= $updated_land_revenue_receipt ?>" type="hidden" />
            <input name="Up_to_date_original_land_documents_old" value="<?= $Up_to_date_original_land_documents ?>" type="hidden" />
            <input name="up_to_date_khajna_receipt_old" value="<?= $up_to_date_khajna_receipt ?>" type="hidden" />
            <input name="copy_of_jamabandi_old" value="<?= $copy_of_jamabandi ?>" type="hidden" />
            <input name="revenue_patta_copy_old" value="<?= $revenue_patta_copy ?>" type="hidden" />
            <input name="copy_of_chitha_old" value="<?= $copy_of_chitha ?>" type="hidden" />
            <input name="settlement_land_patta_copy_old" value="<?= $settlement_land_patta_copy ?>" type="hidden" />
            <input name="land_revenue_receipt_old" value="<?= $land_revenue_receipt ?>" type="hidden" />
            <input name="police_verification_report_old" value="<?= $police_verification_report ?>" type="hidden" />
            <input name="photocopy_of_existing_land_documents_old" value="<?= $photocopy_of_existing_land_documents ?>" type="hidden" />
            <input name="no_dues_certificate_from_bank_old" value="<?= $no_dues_certificate_from_bank ?>" type="hidden" />
            <input name="last_time_paid_Land_revenue_receipt_old" value="<?= $last_time_paid_Land_revenue_receipt ?>" type="hidden" />

            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 20px; color: #fff; font-family: Roboto,sans-serif; font-weight: bold">
                    <h4><b><?= $pageTitle ?><br>
                            <?php switch ($pageTitleId) {
                                case "DCTH":
                                    echo '( চিঠাৰ প্ৰমাণিত কপি )';
                                    break;
                                case "CCJ":
                                    echo '( জামাবন্দীৰ প্ৰমাণিত কপি )';
                                    break;
                                case "CCM":
                                    echo '( মিউটেচনৰ প্ৰমাণিত কপি )';
                                    break;
                                case "DLP":
                                    echo '( ভূমি পট্টাৰ ডুপ্লিকেট কপি )';
                                    break;
                                case "ITMKA":
                                    echo '( ট্ৰেচ মেপ জাৰি কৰা )';
                                    break;
                                case "LHOLD":
                                    echo '( ভূমি ৰখাৰ প্ৰমাণ পত্ৰ )';
                                    break;
                                case "LRCC":
                                    echo '( ভূমি ৰাজহ নিষ্কাশনৰ প্ৰমাণ পত্ৰ )';
                                    break;
                                case "LVC":
                                    echo '( ভূমি মূল্যায়নৰ প্ৰমাণ পত্ৰ )';
                                    break;
                                case "NECKA":
                                    echo '( নন-এনকামব্ৰেন্স প্ৰমাণপত্ৰ )';
                                    break;
                            }
                            ?><b></h4>
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
                        <legend class="h5">Applicant&apos;s Details/ আবেদনকাৰীৰ তথ্য </legend>

                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Applicant&apos;s First Name/ আবেদনকাৰীৰ প্ৰথম নাম<span class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text p-1">
                                            <select name="applicant_title" disabled>
                                                <option value="">Select</option>
                                                <option value="Mr" <?= ($applicant_title === "Mr") ? 'selected' : '' ?>>Mr
                                                </option>
                                                <option value="Mrs" <?= ($applicant_title === "Mrs") ? 'selected' : '' ?>>
                                                    Mrs</option>
                                                <option value="Sri" <?= ($applicant_title === "Sri") ? 'selected' : '' ?>>
                                                    Sri</option>
                                                <option value="Smt" <?= ($applicant_title === "Smt") ? 'selected' : '' ?>>
                                                    Smt</option>
                                                <option value="Late" <?= ($applicant_title === "Late") ? 'selected' : '' ?>>
                                                    Late</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="first_name" id="first_name" value="<?= $first_name ?>" maxlength="255" readonly/>
                                </div>
                                <?= form_error("applicant_title") . form_error("first_name") ?>

                            </div>

                            <div class="col-md-4">
                                <label>Applicant&apos;s Last Name/ আবেদনকাৰীৰ উপাধি<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="last_name" id="last_name" value="<?= $last_name ?>" maxlength="255" readonly/>
                                <?= form_error("last_name") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="gender" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Male" <?= ($gender === "Male") ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= ($gender === "Female") ? 'selected' : '' ?>>Female</option>
                                    <option value="Transgender" <?= ($gender === "Transgender") ? 'selected' : '' ?>>Transgender</option>
                                </select>
                                <?= form_error("gender") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Father&apos;s/Husband&apos;s Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text p-1">
                                            <select name="father_title" disabled>
                                                <option value="">Select</option>
                                                <option value="Mr" <?= ($father_title === "Mr") ? 'selected' : '' ?>>Mr
                                                </option>
                                                <option value="Mrs" <?= ($father_title === "Mrs") ? 'selected' : '' ?>>
                                                    Mrs</option>
                                                <option value="Sri" <?= ($father_title === "Sri") ? 'selected' : '' ?>>
                                                    Sri</option>
                                                <option value="Smt" <?= ($father_title === "Smt") ? 'selected' : '' ?>>
                                                    Smt</option>
                                                <option value="Late" <?= ($father_title === "Late") ? 'selected' : '' ?>>
                                                    Late</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="father_name" id="father_name" value="<?= $father_name ?>" maxlength="255" readonly/>
                                </div>
                                <?= form_error("father_title") . form_error("father_name") ?>

                            </div>

                            <div class="col-md-4">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>

                                <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" maxlength="10" readonly/>


                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-4">
                                <label>E-Mail / ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?= $email ?>" maxlength="100" readonly/>
                                <?= form_error("email") ?>
                            </div>
                        </div>
                    </fieldset>

                    <?php if ($pageTitleId == "CCM") { ?>
                        <fieldset class="border border-success" style="margin-top:40px">
                            <legend class="h5">Mutation order in the Name of/ আবেদনকাৰীৰ তথ্য </legend>

                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label>First Name/ প্ৰথম নাম<span class="text-danger">*</span> </label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text p-1">
                                                <select name="mut_name_title" disabled>
                                                    <option value="">Select</option>
                                                    <option value="Mr" <?= ($mut_name_title === "Mr") ? 'selected' : '' ?>>Mr
                                                    </option>
                                                    <option value="Mrs" <?= ($mut_name_title === "Mrs") ? 'selected' : '' ?>>
                                                        Mrs</option>
                                                    <option value="Sri" <?= ($mut_name_title === "Sri") ? 'selected' : '' ?>>
                                                        Sri</option>
                                                    <option value="Smt" <?= ($mut_name_title === "Smt") ? 'selected' : '' ?>>
                                                        Smt</option>
                                                    <option value="Late" <?= ($mut_name_title === "Late") ? 'selected' : '' ?>>
                                                        Late</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="mut_first_name" id="mut_first_name" value="<?= $mut_first_name ?>" maxlength="255" readonly/>
                                    </div>
                                    <?= form_error("mut_name_title") . form_error("mut_first_name") ?>

                                </div>

                                <div class="col-md-4">
                                    <label>Last Name/ আবেদনকাৰীৰ উপাধি<span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="mut_last_name" id="mut_last_name" value="<?= $mut_last_name ?>" maxlength="255" readonly/>
                                    <?= form_error("mut_last_name") ?>
                                </div>
                                <div class="col-md-4">
                                    <label> Gender/ লিংগ <span class="text-danger">*</span> </label>
                                    <select name="mut_gender" class="form-control" disabled>
                                        <option value="">Please Select</option>
                                        <option value="Male" <?= ($mut_gender === "Male") ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= ($mut_gender === "Female") ? 'selected' : '' ?>>Female</option>
                                        <option value="Transgender" <?= ($mut_gender === "Transgender") ? 'selected' : '' ?>>Transgender</option>
                                    </select>
                                    <?= form_error("mut_gender") ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label>Father&apos;s/Husband&apos;s Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text p-1">
                                                <select name="mut_father_title" disabled>
                                                    <option value="">Select</option>
                                                    <option value="Mr" <?= ($mut_father_title === "Mr") ? 'selected' : '' ?>>Mr
                                                    </option>
                                                    <option value="Mrs" <?= ($mut_father_title === "Mrs") ? 'selected' : '' ?>>
                                                        Mrs</option>
                                                    <option value="Sri" <?= ($mut_father_title === "Sri") ? 'selected' : '' ?>>
                                                        Sri</option>
                                                    <option value="Smt" <?= ($mut_father_title === "Smt") ? 'selected' : '' ?>>
                                                        Smt</option>
                                                    <option value="Late" <?= ($mut_father_title === "Late") ? 'selected' : '' ?>>
                                                        Late</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="mut_father_name" id="mut_father_name" value="<?= $mut_father_name ?>" maxlength="255" readonly/>
                                    </div>
                                    <?= form_error("mut_father_title") . form_error("mut_father_name") ?>

                                </div>

                                <div class="col-md-4">
                                    <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>

                                    <input type="text" class="form-control" name="mut_mobile" value="<?= $mut_mobile ?>" maxlength="10" readonly/>

                                    <?= form_error("mut_mobile") ?>
                                </div>
                                <div class="col-md-4">
                                    <label>E-Mail / ই-মেইল </label>
                                    <input type="text" class="form-control" name="mut_email" value="<?= $mut_email ?>" maxlength="100" readonly/>
                                    <?= form_error("mut_email") ?>
                                </div>
                            </div>
                        </fieldset>
                    <?php } ?>


                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Address/ ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Circle/ চক্ৰ <span class="text-danger">*</span> </label>
                                <select id="circle_office" name="circle_office" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="1383118" <?= ($circle_office === "1383118") ? 'selected' : '' ?> data-entity-level="">Diphu</option>
                                    <option value="1383119" data-entity-level="" <?= ($circle_office === "1383119") ? 'selected' : '' ?>>Dongkamukam</option>
                                    <option value="1383120" data-entity-level="" <?= ($circle_office === "1383120") ? 'selected' : '' ?>>Phuloni</option>
                                    <option value="1383121" data-entity-level="" <?= ($circle_office === "1383121") ? 'selected' : '' ?>>Silonijan</option>
                                </select>
                                <?= form_error("circle_office") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Mouza/ মৌজা <span class="text-danger">*</span> </label>
                                <select id="mouza_name" name="mouza_name" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="11" <?= ($mouza_name === "11") ? 'selected' : '' ?>>Hamren
                                    </option>


                                </select>
                                <?= form_error("mouza_name") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Revenue Village/ ৰাজহ গাঁও <span class="text-danger">*</span> </label>
                                <select id="revenue_village" name="revenue_village" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="1" <?= ($revenue_village === "1") ? 'selected' : '' ?>>Dakhin Dhannsiri
                                    </option>


                                </select>
                                <?= form_error("revenue_village") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Police Station/ থানা <span class="text-danger">*</span> </label>
                                <!-- <input type="text" class="form-control" name="police_station" id="police_station" value="<?= $police_station ?>" maxlength="255" /> -->
                                <select id="police_station" name="police_station" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="1" <?= ($police_station === "1") ? 'selected' : '' ?>>Anjokpani Police Station
                                    </option>


                                </select>
                                <?= form_error("police_station") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Post Office/ ডাকঘৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="post_office" id="post_office" value="<?= $post_office ?>" maxlength="255" readonly/>
                                <?= form_error("post_office") ?>
                            </div>
                        </div>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Other Details / অন্যান্য বিৱৰণ </legend>
                        <div class="row form-group">

                            <div class="col-md-4">
                                <label>Dag No. / ডাগ নং<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="dag_no" id="dag_no" value="<?= $dag_no ?>" maxlength="255" readonly/>
                                <?= form_error("dag_no") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Annual Patta/Periodic Patta No./ বাৰ্ষিক পট্টা/সাময়িক পট্টা নং<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="periodic_patta_no" id="periodic_patta_no" value="<?= $periodic_patta_no ?>" maxlength="255" readonly/>
                                <?= form_error("periodic_patta_no") ?>
                            </div>

                            <div class="col-md-4">
                                <label>Patta Type/ পট্টা টাইপ<span class="text-danger">*</span> </label>
                                <select name="patta_type" id="patta_type" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <?php if ($pageTitleId == "DLP") { ?>
                                        <option value="1" <?= ($patta_type === "1") ? 'selected' : '' ?>>Khiraj Miyadi
                                        <option value="2" <?= ($patta_type === "2") ? 'selected' : '' ?>>Nisfi Khiraj
                                        <?php } else { ?>
                                        <option value="1" <?= ($patta_type === "1") ? 'selected' : '' ?>>Periodic Patta (Myadi)
                                        </option>
                                    <?php } ?>
                                </select>
                                <?= form_error("patta_type") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Land Area/ ভূমিৰ আয়তন </legend>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Bigha./ বিঘা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="land_area_bigha" id="land_area_bigha" value="<?= $land_area_bigha ?>" maxlength="255" readonly/>
                                <?= form_error("land_area_bigha") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Kotha./ কঠা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="land_area_katha" id="land_area_katha" value="<?= $land_area_katha ?>" maxlength="255" readonly/>
                                <?= form_error("land_area_katha") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Loosa./ লেচা<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="land_area_loosa" id="land_area_loosa" value="<?= $land_area_loosa ?>" maxlength="255" readonly/>
                                <?= form_error("land_area_loosa") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Land Area./ ভূমিৰ আয়তন<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="land_area_sq_ft" id="land_area_sq_ft" value="<?= $land_area_sq_ft ?>" maxlength="255" readonly/>
                                <?= form_error("land_area_sq_ft") ?>
                            </div>

                        </div>
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
                                        <?php if ($pageTitleId == "DCTH" || $pageTitleId == "CCM" || $pageTitleId == "ITMKA" || $pageTitleId == "LHOLD") { ?>
                                            <tr>
                                                <td>Address Proof <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="address_proof_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Address Proof" <?= ($address_proof_type === 'Address Proof') ? 'selected' : '' ?>>Address Proof</option>

                                                    </select>
                                                    <?= form_error("address_proof_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="address_proof" name="address_proof" type="file" />
                                                    </div>
                                                    <?php if (strlen($address_proof)) { ?>
                                                        <a href="<?= base_url($address_proof) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="address_proof" type="hidden" name="address_proof_temp">

                                                    <?= $this->digilocker_api->digilocker_fetch_btn('address_proof'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Identity proof <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="identity_proof_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Identity Proof" <?= ($identity_proof_type === 'Identity Proof') ? 'selected' : '' ?>>Identity Proof</option>

                                                    </select>
                                                    <?= form_error("identity_proof_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="identity_proof" name="identity_proof" type="file" />
                                                    </div>
                                                    <?php if (strlen($identity_proof)) { ?>
                                                        <a href="<?= base_url($identity_proof) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="identity_proof" type="hidden" name="identity_proof_temp">

                                                    <?= $this->digilocker_api->digilocker_fetch_btn('identity_proof'); ?>

                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if (($pageTitleId == "DCTH") || ($pageTitleId == "ITMKA" || $pageTitleId == "LHOLD") || ($pageTitleId == "LRCC")) { ?>
                                            <tr>
                                                <td>Land patta copy <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="land_patta_copy_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Land Patta Copy" <?= ($land_patta_copy_type === 'Land Patta Copy') ? 'selected' : '' ?>>Land Patta Copy</option>
                                                    </select>
                                                    <?= form_error("land_patta_copy_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="land_patta_copy" name="land_patta_copy" type="file" />
                                                    </div>
                                                    <?php if (strlen($land_patta_copy)) { ?>
                                                        <a href="<?= base_url($land_patta_copy) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="land_patta_copy" type="hidden" name="land_patta_copy_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('land_patta_copy'); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if (($pageTitleId == "DCTH") || ($pageTitleId == "LHOLD")) { ?>
                                            <tr>
                                                <td>Updated Land revenue receipt <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="updated_land_revenue_receipt_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Updated Land revenue receipt" <?= ($updated_land_revenue_receipt_type === 'Updated Land revenue receipt') ? 'selected' : '' ?>>Updated Land revenue receipt</option>
                                                    </select>
                                                    <?= form_error("updated_land_revenue_receipt_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="updated_land_revenue_receipt" name="updated_land_revenue_receipt" type="file" />
                                                    </div>
                                                    <?php if (strlen($updated_land_revenue_receipt)) { ?>
                                                        <a href="<?= base_url($updated_land_revenue_receipt) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="updated_land_revenue_receipt" type="hidden" name="updated_land_revenue_receipt_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('updated_land_revenue_receipt'); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if (($pageTitleId == "CCJ") || ($pageTitleId == "LVC") || ($pageTitleId == "NECKA")) { ?>
                                            <tr>
                                                <td>Up-to-date Original Land Documents <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="Up_to_date_original_land_documents_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Up-to Date Original Land Documents" <?= ($Up_to_date_original_land_documents_type === 'Up-to Date Original Land Documents') ? 'selected' : '' ?>>Up-to-date Original Land Documents</option>
                                                    </select>
                                                    <?= form_error("Up_to_date_original_land_documents_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="Up_to_date_original_land_documents" name="Up_to_date_original_land_documents" type="file" />
                                                    </div>
                                                    <?php if (strlen($Up_to_date_original_land_documents)) { ?>
                                                        <a href="<?= base_url($Up_to_date_original_land_documents) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="Up_to_date_original_land_documents" type="hidden" name="Up_to_date_original_land_documents_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('Up_to_date_original_land_documents'); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if (($pageTitleId == "CCJ") || ($pageTitleId == "LVC") || ($pageTitleId == "NECKA") || ($pageTitleId == "DLP")) { ?>
                                            <tr>
                                                <td>Up-to date Khajna Receipt <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="up_to_date_khajna_receipt_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Up-to Date Khajna Receipt" <?= ($up_to_date_khajna_receipt_type === 'Up-to Date Khajna Receipt') ? 'selected' : '' ?>>Up-to date Khajna Receipt</option>
                                                    </select>
                                                    <?= form_error("up_to_date_khajna_receipt_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="up_to_date_khajna_receipt" name="up_to_date_khajna_receipt" type="file" />
                                                    </div>
                                                    <?php if (strlen($up_to_date_khajna_receipt)) { ?>
                                                        <a href="<?= base_url($up_to_date_khajna_receipt) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="up_to_date_khajna_receipt" type="hidden" name="updated_land_revenue_receipt_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('up_to_date_khajna_receipt'); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if ($pageTitleId == "CCM") { ?>
                                            <tr>
                                                <td>Copy of Jamabandi <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="copy_of_jamabandi_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Copy of Jamabandi" <?= ($copy_of_jamabandi_type === 'Copy of Jamabandi') ? 'selected' : '' ?>>Copy of Jamabandi</option>
                                                    </select>
                                                    <?= form_error("copy_of_jamabandi_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="copy_of_jamabandi" name="copy_of_jamabandi" type="file" />
                                                    </div>
                                                    <?php if (strlen($copy_of_jamabandi)) { ?>
                                                        <a href="<?= base_url($copy_of_jamabandi) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="copy_of_jamabandi" type="hidden" name="copy_of_jamabandi_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('copy_of_jamabandi'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Revenue Patta copy <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="revenue_patta_copy_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Revenue Patta Copy" <?= ($revenue_patta_copy_type === 'Revenue Patta Copy') ? 'selected' : '' ?>>Revenue Patta copy</option>
                                                    </select>
                                                    <?= form_error("revenue_patta_copy_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="revenue_patta_copy" name="revenue_patta_copy" type="file" />
                                                    </div>
                                                    <?php if (strlen($revenue_patta_copy)) { ?>
                                                        <a href="<?= base_url($revenue_patta_copy) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="revenue_patta_copy" type="hidden" name="revenue_patta_copy_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('revenue_patta_copy'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Copy of Chitha <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="copy_of_chitha_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Copy of Chitha" <?= ($copy_of_chitha_type === 'Copy of Chitha') ? 'selected' : '' ?>>Copy of Chitha</option>
                                                    </select>
                                                    <?= form_error("copy_of_chitha_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="copy_of_chitha" name="copy_of_chitha" type="file" />
                                                    </div>
                                                    <?php if (strlen($copy_of_chitha)) { ?>
                                                        <a href="<?= base_url($copy_of_chitha) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="copy_of_chitha" type="hidden" name="copy_of_chitha_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('copy_of_chitha'); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if (($pageTitleId == "CCM") || ($pageTitleId == "ITMKA")) { ?>
                                            <tr>
                                                <td>Settlement Land patta copy <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="settlement_land_patta_copy_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Settlement Land Patta Copy" <?= ($settlement_land_patta_copy_type === 'Settlement Land Patta Copy') ? 'selected' : '' ?>>Settlement Land patta copy</option>
                                                    </select>
                                                    <?= form_error("settlement_land_patta_copy_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="settlement_land_patta_copy" name="settlement_land_patta_copy" type="file" />
                                                    </div>
                                                    <?php if (strlen($settlement_land_patta_copy)) { ?>
                                                        <a href="<?= base_url($settlement_land_patta_copy) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="settlement_land_patta_copy" type="hidden" name="settlement_land_patta_copy_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('settlement_land_patta_copy'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Land revenue receipt <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="land_revenue_receipt_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Land Revenue Receipt" <?= ($land_revenue_receipt_type === 'Land Revenue Receipt') ? 'selected' : '' ?>>Land revenue receipt</option>
                                                    </select>
                                                    <?= form_error("land_revenue_receipt_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="land_revenue_receipt" name="land_revenue_receipt" type="file" />
                                                    </div>
                                                    <?php if (strlen($land_revenue_receipt)) { ?>
                                                        <a href="<?= base_url($land_revenue_receipt) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="land_revenue_receipt" type="hidden" name="land_revenue_receipt_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('land_revenue_receipt'); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>

                                        <?php if ($pageTitleId == "DLP") { ?>
                                            <tr>
                                                <td>Police Verification Report <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="police_verification_report_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Police Verification Report" <?= ($police_verification_report_type === 'Police Verification Report') ? 'selected' : '' ?>>Police Verification Report</option>
                                                    </select>
                                                    <?= form_error("police_verification_report_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="police_verification_report" name="police_verification_report" type="file" />
                                                    </div>
                                                    <?php if (strlen($police_verification_report)) { ?>
                                                        <a href="<?= base_url($police_verification_report) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="police_verification_report" type="hidden" name="police_verification_report_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('police_verification_report'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Photocopy of existing Land Documents <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="photocopy_of_existing_land_documents_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Photocopy of existing Land Documents" <?= ($photocopy_of_existing_land_documents_type === 'Photocopy of existing Land Documents') ? 'selected' : '' ?>>Photocopy of existing Land Documents</option>
                                                    </select>
                                                    <?= form_error("photocopy_of_existing_land_documents_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="photocopy_of_existing_land_documents" name="photocopy_of_existing_land_documents" type="file" />
                                                    </div>
                                                    <?php if (strlen($photocopy_of_existing_land_documents)) { ?>
                                                        <a href="<?= base_url($photocopy_of_existing_land_documents) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="photocopy_of_existing_land_documents" type="hidden" name="photocopy_of_existing_land_documents_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('photocopy_of_existing_land_documents'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>No Dues Certificate from Bank <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="no_dues_certificate_from_bank_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="No Dues Certificate from Bank" <?= ($no_dues_certificate_from_bank_type === 'No Dues Certificate from Bank') ? 'selected' : '' ?>>No Dues Certificate from Bank</option>
                                                    </select>
                                                    <?= form_error("no_dues_certificate_from_bank_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="no_dues_certificate_from_bank" name="no_dues_certificate_from_bank" type="file" />
                                                    </div>
                                                    <?php if (strlen($no_dues_certificate_from_bank)) { ?>
                                                        <a href="<?= base_url($no_dues_certificate_from_bank) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="no_dues_certificate_from_bank" type="hidden" name="no_dues_certificate_from_bank_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('no_dues_certificate_from_bank'); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if ($pageTitleId == "LRCC") { ?>
                                            <tr>
                                                <td>Last time paid Land Revenue Receipt <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="last_time_paid_Land_revenue_receipt_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Last time paid Land revenue receipt" <?= ($last_time_paid_Land_revenue_receipt_type === 'Last time paid Land revenue receipt') ? 'selected' : '' ?>>Last time paid Land revenue receipt</option>
                                                    </select>
                                                    <?= form_error("last_time_paid_Land_revenue_receipt_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="last_time_paid_Land_revenue_receipt" name="last_time_paid_Land_revenue_receipt" type="file" />
                                                    </div>
                                                    <?php if (strlen($last_time_paid_Land_revenue_receipt)) { ?>
                                                        <a href="<?= base_url($last_time_paid_Land_revenue_receipt) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="last_time_paid_Land_revenue_receipt" type="hidden" name="last_time_paid_Land_revenue_receipt_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('last_time_paid_Land_revenue_receipt'); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if ($this->slug == 'userrr') { ?>
                                            <tr>
                                                <td>Soft copy of the applicant form<span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="soft_copy_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Soft copy of the applicant form" <?= ($soft_copy_type === 'Soft copy of the applicant form') ? 'selected' : '' ?>>Soft copy of the applicant form</option>
                                                    </select>
                                                    <?= form_error("soft_copy_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="soft_copy" name="soft_copy" type="file" />
                                                    </div>
                                                    <?php if (strlen($soft_copy)) { ?>
                                                        <a href="<?= base_url($soft_copy) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } //End of if 
                                        ?>
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
                                <?php if (isset($dbrow->processing_history)) {
                                    foreach ($dbrow->processing_history as $key => $rows) {
                                        $query_attachment = $rows->query_attachment ?? ''; ?>
                                        <tr>
                                            <td><?= sprintf("%02d", $key + 1) ?></td>
                                            <td><?= date('d/m/Y h:i a', strtotime($this->mongo_db->getDateTime($rows->processing_time))) ?></td>
                                            <td><?= $rows->action_taken ?></td>
                                            <td><?= $rows->remarks ?></td>
                                        </tr>
                                <?php } //End of foreach()
                                } //End of if else 
                                ?>
                            </tbody>
                        </table>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <!-- <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button> -->
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <!-- <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button> -->
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>