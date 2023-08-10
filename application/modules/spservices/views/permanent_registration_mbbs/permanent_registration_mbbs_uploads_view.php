<?php
// pre($dbrow->form_data);

?>
<!-- Hello -->
<?php

$admit_birth_type_frm = set_value("admit_birth_type");
$passport_photo_type_frm = set_value("passport_photo_type");
$signature_type_frm = set_value("signature_type");
$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type_frm = set_value("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type");
$internship_completion_certificate_type_frm = set_value("internship_completion_certificate_type");
$hs_marksheet_type_frm = set_value("hs_marksheet_type");
$reg_certificate_type_frm = set_value("reg_certificate_type");
$reg_certificate_of_concerned_university_type_frm = set_value("reg_certificate_of_concerned_university_type");
$mbbs_pass_certificate_from_university_type_frm = set_value("mbbs_pass_certificate_from_university_type");
$permanent_registration_certificate_of_concerned_medical_council_type_frm = set_value("permanent_registration_certificate_of_concerned_medical_council_type");
$noc_from_concerned_medical_council_type_frm = set_value("noc_from_concerned_medical_council_type");
$registration_certificate_from_respective_university_or_equivalent_type_frm = set_value("registration_certificate_from_respective_university_or_equivalent_type");
$all_marksheets_of_mbbs_or_equivalent_type_frm = set_value("all_marksheets_of_mbbs_or_equivalent_type");
$mbbs_or_equivalent_degree_pass_certificate_from_university_type_frm = set_value("mbbs_or_equivalent_degree_pass_certificate_from_university_type");


$eligibility_certificate_from_national_medical_commission_type_frm = set_value("eligibility_certificate_from_national_medical_commission_type");


$screening_test_result_from_national_board_of_examination_type_frm = set_value("screening_test_result_from_national_board_of_examination_type"); 


$passport_and_visa_with_travel_details_type_frm = set_value("passport_and_visa_with_travel_details_type"); 



$all_documents_related_to_medical_college_details_type_frm = set_value("all_documents_related_to_medical_college_details_type"); 



$mbbs_marksheet_type_frm = set_value("mbbs_marksheet_type");

$pass_certificate_type_frm = set_value("pass_certificate_type");
$annexure_type_frm = set_value("annexure_type");


$uploadedFiles = $this->session->flashdata('uploaded_files');
$admit_birth_frm = $uploadedFiles['admit_birth_old']??null;
$passport_photo_frm = $uploadedFiles["passport_photo_old"]??null;
$signature_frm = $uploadedFiles["signature_old"]??null;
$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_frm =  $uploadedFiles['provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_old']??null;

$internship_completion_certificate_frm = $uploadedFiles['internship_completion_certificate_old']??null;
$hs_marksheet_frm = $uploadedFiles['hs_marksheet_old']??null;
$reg_certificate_frm = $uploadedFiles['reg_certificate_old']??null;
$reg_certificate_of_concerned_university_frm = $uploadedFiles['reg_certificate_of_concerned_university_old']??null;
$mbbs_pass_certificate_from_university_frm = $uploadedFiles['mbbs_pass_certificate_from_university_old']??null;
$permanent_registration_certificate_of_concerned_medical_council_frm = $uploadedFiles['permanent_registration_certificate_of_concerned_medical_council_old']??null;
$noc_from_concerned_medical_council_frm = $uploadedFiles['noc_from_concerned_medical_council_old']??null;
$registration_certificate_from_respective_university_or_equivalent_frm = $uploadedFiles['registration_certificate_from_respective_university_or_equivalent_old']??null;
$all_marksheets_of_mbbs_or_equivalent_frm = $uploadedFiles['all_marksheets_of_mbbs_or_equivalent_old']??null;
$mbbs_or_equivalent_degree_pass_certificate_from_university_frm = $uploadedFiles['mbbs_or_equivalent_degree_pass_certificate_from_university_old']??null;

$eligibility_certificate_from_national_medical_commission_frm = $uploadedFiles['eligibility_certificate_from_national_medical_commission_old']??null;


$screening_test_result_from_national_board_of_examination_frm = $uploadedFiles['screening_test_result_from_national_board_of_examination_old']??null;

$passport_and_visa_with_travel_details_frm = $uploadedFiles['passport_and_visa_with_travel_details_old']??null;


$all_documents_related_to_medical_college_details_frm = $uploadedFiles['all_documents_related_to_medical_college_details_old']??null;


$mbbs_marksheet_frm = $uploadedFiles['mbbs_marksheet_old']??null;
$pass_certificate_frm = $uploadedFiles['pass_certificate_old']??null;
$annexure_frm = $uploadedFiles['annexure_old']??null;


$admit_birth_type_db = $dbrow->form_data->admit_birth_type??null;
$passport_photo_type_db = $dbrow->form_data->passport_photo_type??null;
$signature_type_db = $dbrow->form_data->signature_type??null;
$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type_db = $dbrow->form_data->provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type??null;
$internship_completion_certificate_type_db = $dbrow->form_data->internship_completion_certificate_type??null;
$hs_marksheet_type_db = $dbrow->form_data->hs_marksheet_type??null;
$reg_certificate_type_db = $dbrow->form_data->reg_certificate_type??null;
$reg_certificate_of_concerned_university_type_db = $dbrow->form_data->reg_certificate_of_concerned_university_type??null;
$mbbs_pass_certificate_from_university_type_db = $dbrow->form_data->mbbs_pass_certificate_from_university_type??null;
$permanent_registration_certificate_of_concerned_medical_council_type_db = $dbrow->form_data->permanent_registration_certificate_of_concerned_medical_council_type??null;
$noc_from_concerned_medical_council_type_db = $dbrow->form_data->noc_from_concerned_medical_council_type??null;
$registration_certificate_from_respective_university_or_equivalent_type_db = $dbrow->form_data->registration_certificate_from_respective_university_or_equivalent_type??null;
$all_marksheets_of_mbbs_or_equivalent_type_db = $dbrow->form_data->all_marksheets_of_mbbs_or_equivalent_type??null;
$mbbs_or_equivalent_degree_pass_certificate_from_university_type_db = $dbrow->form_data->mbbs_or_equivalent_degree_pass_certificate_from_university_type??null;
$eligibility_certificate_from_national_medical_commission_type_db = $dbrow->form_data->eligibility_certificate_from_national_medical_commission_type??null;

$screening_test_result_from_national_board_of_examination_type_db = $dbrow->form_data->screening_test_result_from_national_board_of_examination_type??null;


$passport_and_visa_with_travel_details_type_db = $dbrow->form_data->passport_and_visa_with_travel_details_type??null;



$all_documents_related_to_medical_college_details_type_db = $dbrow->form_data->all_documents_related_to_medical_college_details_type??null;


$mbbs_marksheet_type_db = $dbrow->form_data->mbbs_marksheet_type??null;
$pass_certificate_type_db = $dbrow->form_data->pass_certificate_type??null;
$annexure_type_db = $dbrow->form_data->annexure_type??null;



$admit_birth_db = $dbrow->form_data->admit_birth??null;
$passport_photo_db = $dbrow->form_data->passport_photo??null;
$signature_db = $dbrow->form_data->signature??null;
$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_db = $dbrow->form_data->provisional_registration_certificate_of_concerned_assam_council_of_medical_registration??null;

$internship_completion_certificate_db = $dbrow->form_data->internship_completion_certificate??null;
$hs_marksheet_db = $dbrow->form_data->hs_marksheet??null;
$reg_certificate_db = $dbrow->form_data->reg_certificate??null;
$reg_certificate_of_concerned_university_db = $dbrow->form_data->reg_certificate_of_concerned_university??null;
$mbbs_pass_certificate_from_university_db = $dbrow->form_data->mbbs_pass_certificate_from_university??null;
$permanent_registration_certificate_of_concerned_medical_council_db = $dbrow->form_data->permanent_registration_certificate_of_concerned_medical_council??null;
$noc_from_concerned_medical_council_db = $dbrow->form_data->noc_from_concerned_medical_council??null;
$registration_certificate_from_respective_university_or_equivalent_db = $dbrow->form_data->registration_certificate_from_respective_university_or_equivalent??null;
$all_marksheets_of_mbbs_or_equivalent_db = $dbrow->form_data->all_marksheets_of_mbbs_or_equivalent??null;
$mbbs_or_equivalent_degree_pass_certificate_from_university_db = $dbrow->form_data->mbbs_or_equivalent_degree_pass_certificate_from_university??null;
$eligibility_certificate_from_national_medical_commission_db = $dbrow->form_data->eligibility_certificate_from_national_medical_commission??null;

$screening_test_result_from_national_board_of_examination_db= $dbrow->form_data->screening_test_result_from_national_board_of_examination??null;

$passport_and_visa_with_travel_details_db= $dbrow->form_data->passport_and_visa_with_travel_details??null;



$all_documents_related_to_medical_college_details_db= $dbrow->form_data->all_documents_related_to_medical_college_details??null;


$mbbs_marksheet_db = $dbrow->form_data->mbbs_marksheet??null;
$pass_certificate_db = $dbrow->form_data->pass_certificate??null;
$annexure_db = $dbrow->form_data->annexure??null;




$admit_birth_type = strlen($admit_birth_type_frm)?$admit_birth_type_frm:$admit_birth_type_db;

$passport_photo_type = strlen($passport_photo_type_frm)?$passport_photo_type_frm:$passport_photo_type_db;
$signature_type = strlen($signature_type_frm)?$signature_type_frm:$signature_type_db;

$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type = strlen($provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type_frm)?$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type_frm:$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type_db;

$internship_completion_certificate_type = strlen($internship_completion_certificate_type_frm)?$internship_completion_certificate_type_frm:$internship_completion_certificate_type_db;


$hs_marksheet_type = strlen($hs_marksheet_type_frm)?$hs_marksheet_type_frm:$hs_marksheet_type_db;

$reg_certificate_type = strlen($reg_certificate_type_frm)?$reg_certificate_type_frm:$reg_certificate_type_db;

$reg_certificate_of_concerned_university_type = strlen($reg_certificate_of_concerned_university_type_frm)?$reg_certificate_of_concerned_university_type_frm:$reg_certificate_of_concerned_university_type_db;

$mbbs_pass_certificate_from_university_type = strlen($mbbs_pass_certificate_from_university_type_frm)?$mbbs_pass_certificate_from_university_type_frm:$mbbs_pass_certificate_from_university_type_db;

$permanent_registration_certificate_of_concerned_medical_council_type = strlen($permanent_registration_certificate_of_concerned_medical_council_type_frm)?$permanent_registration_certificate_of_concerned_medical_council_type_frm:$permanent_registration_certificate_of_concerned_medical_council_type_db;

$noc_from_concerned_medical_council_type = strlen($noc_from_concerned_medical_council_type_frm)?$noc_from_concerned_medical_council_type_frm:$noc_from_concerned_medical_council_type_db;

$registration_certificate_from_respective_university_or_equivalent_type = strlen($registration_certificate_from_respective_university_or_equivalent_type_frm)?$registration_certificate_from_respective_university_or_equivalent_type_frm:$registration_certificate_from_respective_university_or_equivalent_type_db;



$all_marksheets_of_mbbs_or_equivalent_type = strlen($all_marksheets_of_mbbs_or_equivalent_type_frm)?$all_marksheets_of_mbbs_or_equivalent_type_frm:$all_marksheets_of_mbbs_or_equivalent_type_db;



$mbbs_or_equivalent_degree_pass_certificate_from_university_type = strlen($mbbs_or_equivalent_degree_pass_certificate_from_university_type_frm)?$mbbs_or_equivalent_degree_pass_certificate_from_university_type_frm:$mbbs_or_equivalent_degree_pass_certificate_from_university_type_db;


$eligibility_certificate_from_national_medical_commission_type = strlen($eligibility_certificate_from_national_medical_commission_frm)?$eligibility_certificate_from_national_medical_commission_type_frm:$eligibility_certificate_from_national_medical_commission_type_db;



$screening_test_result_from_national_board_of_examination_type = strlen($screening_test_result_from_national_board_of_examination_frm)?$screening_test_result_from_national_board_of_examination_type_frm:$screening_test_result_from_national_board_of_examination_type_db;


$passport_and_visa_with_travel_details_type = strlen($passport_and_visa_with_travel_details_frm)?$passport_and_visa_with_travel_details_type_frm:$passport_and_visa_with_travel_details_type_db;



$all_documents_related_to_medical_college_details_type = strlen($all_documents_related_to_medical_college_details_frm)?$all_documents_related_to_medical_college_details_type_frm:$all_documents_related_to_medical_college_details_type_db;



$mbbs_marksheet_type = strlen($mbbs_marksheet_frm)?$mbbs_marksheet_type_frm:$mbbs_marksheet_type_db;

$pass_certificate_type = strlen($pass_certificate_type_frm)?$pass_certificate_type_frm:$pass_certificate_type_db;



$annexure_type = strlen($annexure_frm)?$annexure_type_frm:$annexure_type_db;



$admit_birth = strlen($admit_birth_frm)?$admit_birth_frm:$admit_birth_db;
$passport_photo = strlen($passport_photo_frm)?$passport_photo_frm:$passport_photo_db;
$signature = strlen($signature_frm)?$signature_frm:$signature_db;

$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration = strlen($provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_frm)?$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_frm:$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_db;

$internship_completion_certificate = strlen($internship_completion_certificate_frm)?$internship_completion_certificate_frm:$internship_completion_certificate_db;

$hs_marksheet = strlen($hs_marksheet_frm)?$hs_marksheet_frm:$hs_marksheet_db;
$reg_certificate = strlen($reg_certificate_frm)?$reg_certificate_frm:$reg_certificate_db;
// $reg_certificate = strlen($reg_certificate_frm)?$reg_certificate_frm:$reg_certificate_db;
$reg_certificate_of_concerned_university = strlen($reg_certificate_of_concerned_university_frm)?$reg_certificate_of_concerned_university_frm:$reg_certificate_of_concerned_university_db;

$mbbs_pass_certificate_from_university = strlen($mbbs_pass_certificate_from_university_frm)?$mbbs_pass_certificate_from_university_frm:$mbbs_pass_certificate_from_university_db;


// Solved
$permanent_registration_certificate_of_concerned_medical_council = strlen($permanent_registration_certificate_of_concerned_medical_council_frm)?$permanent_registration_certificate_of_concerned_medical_council_frm:$permanent_registration_certificate_of_concerned_medical_council_db;

$noc_from_concerned_medical_council = strlen($noc_from_concerned_medical_council_frm)?$noc_from_concerned_medical_council_frm:$noc_from_concerned_medical_council_db;

$registration_certificate_from_respective_university_or_equivalent = strlen($registration_certificate_from_respective_university_or_equivalent_frm)?$registration_certificate_from_respective_university_or_equivalent_frm:$registration_certificate_from_respective_university_or_equivalent_db;

$all_marksheets_of_mbbs_or_equivalent = strlen($all_marksheets_of_mbbs_or_equivalent_frm)?$all_marksheets_of_mbbs_or_equivalent_type_frm:$all_marksheets_of_mbbs_or_equivalent_db;


$mbbs_or_equivalent_degree_pass_certificate_from_university = strlen($mbbs_or_equivalent_degree_pass_certificate_from_university_frm)?$mbbs_or_equivalent_degree_pass_certificate_from_university_type_frm:$mbbs_or_equivalent_degree_pass_certificate_from_university_db;


$eligibility_certificate_from_national_medical_commission= strlen($eligibility_certificate_from_national_medical_commission_frm)?$eligibility_certificate_from_national_medical_commission_type_frm:$eligibility_certificate_from_national_medical_commission_db;



$screening_test_result_from_national_board_of_examination= strlen($screening_test_result_from_national_board_of_examination_frm)?$screening_test_result_from_national_board_of_examination_type_frm:$screening_test_result_from_national_board_of_examination_db;



$passport_and_visa_with_travel_details= strlen($passport_and_visa_with_travel_details_frm)?$passport_and_visa_with_travel_details_type_frm:$passport_and_visa_with_travel_details_db;


$all_documents_related_to_medical_college_details= strlen($all_documents_related_to_medical_college_details_frm)?$all_documents_related_to_medical_college_details_type_frm:$all_documents_related_to_medical_college_details_db;



$mbbs_marksheet = strlen($mbbs_marksheet_frm)?$mbbs_marksheet_frm:$mbbs_marksheet_db;
$pass_certificate = strlen($pass_certificate_frm)?$pass_certificate_frm:$pass_certificate_db;

$annexure = strlen($annexure_frm)?$annexure_frm:$annexure_db;


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

<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/iservices/js/custom/digilocker.js') ?>"></script>
<script type="text/javascript">   
    $(document).ready(function () {     
        var admitbirth = parseInt(<?=strlen($admit_birth)?1:0?>);
        $("#admit_birth").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required:false,
            //required: admitbirth?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var passportPhoto = parseInt(<?=strlen($passport_photo)?1:0?>);
        $("#passport_photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required:false,
            //required: admitbirth?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png"]
        });

        var signature = parseInt(<?=strlen($signature)?1:0?>);
        $("#signature").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required:false,
            //required: admitbirth?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png"]
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

        var reg_certificate_of_concerned_university = parseInt(<?=strlen($reg_certificate_of_concerned_university)?1:0?>);
        $("#reg_certificate_of_concerned_university").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: regcertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var mbbs_pass_certificate_from_university = parseInt(<?=strlen($mbbs_pass_certificate_from_university)?1:0?>);
        $("#mbbs_pass_certificate_from_university").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: regcertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });


        var permanent_registration_certificate_of_concerned_medical_council = parseInt(<?=strlen($permanent_registration_certificate_of_concerned_medical_council)?1:0?>);
        $("#permanent_registration_certificate_of_concerned_medical_council").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: regcertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });


        var noc_from_concerned_medical_council = parseInt(<?=strlen($noc_from_concerned_medical_council)?1:0?>);
        $("#noc_from_concerned_medical_council").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: regcertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var registration_certificate_from_respective_university_or_equivalent = parseInt(<?=strlen($registration_certificate_from_respective_university_or_equivalent)?1:0?>);
        $("#registration_certificate_from_respective_university_or_equivalent").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: regcertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });


        var all_marksheets_of_mbbs_or_equivalent = parseInt(<?=strlen($all_marksheets_of_mbbs_or_equivalent)?1:0?>);
        $("#all_marksheets_of_mbbs_or_equivalent").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: regcertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var mbbs_or_equivalent_degree_pass_certificate_from_university = parseInt(<?=strlen($mbbs_or_equivalent_degree_pass_certificate_from_university)?1:0?>);
        $("#mbbs_or_equivalent_degree_pass_certificate_from_university").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: regcertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var eligibility_certificate_from_national_medical_commission = parseInt(<?=strlen($eligibility_certificate_from_national_medical_commission)?1:0?>);
        $("#eligibility_certificate_from_national_medical_commission").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: regcertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });


        var screening_test_result_from_national_board_of_examination = parseInt(<?=strlen($screening_test_result_from_national_board_of_examination)?1:0?>);
        $("#screening_test_result_from_national_board_of_examination").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: regcertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });



        var passport_and_visa_with_travel_details = parseInt(<?=strlen($passport_and_visa_with_travel_details)?1:0?>);
        $("#passport_and_visa_with_travel_details").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: regcertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        

        var all_documents_related_to_medical_college_details = parseInt(<?=strlen($all_documents_related_to_medical_college_details)?1:0?>);
        $("#all_documents_related_to_medical_college_details").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: regcertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        

        // var registration_certificate_from_respective_university_or_equivalent = parseInt(<?=strlen($registration_certificate_from_respective_university_or_equivalent)?1:0?>);
        // $("#registration_certificate_from_respective_university_or_equivalent").fileinput({
        //     dropZoneEnabled: false,
        //     showUpload: false,
        //     showRemove: false,
        //     //required: regcertificate?false:true,
        //     maxFileSize: 1024,
        //     allowedFileExtensions: ["pdf"]
        // });

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



        var internshipcompletioncertificate = parseInt(<?=strlen($internship_completion_certificate)?1:0?>);
        $("#internship_completion_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: mbbsmarksheet?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });


        
        var provisionalregistrationcertificateofconcernedassamcouncilofmedicalregistration = parseInt(<?=strlen($provisional_registration_certificate_of_concerned_assam_council_of_medical_registration)?1:0?>);
        $("#provisional_registration_certificate_of_concerned_assam_council_of_medical_registration").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: mbbsmarksheet?false:true,
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


    });
</script>
        
<main class="rtps-container">
    <!-- Hello -->
    <div class="container my-2">
        <form method="POST" action="<?= base_url('spservices/permanent_registration_mbbs/registration/submitfiles') ?>" enctype="multipart/form-data">
                <!-- <form method="POST" action="<?= base_url('spservices/permanent_registration_mbbs/registration/test') ?>" enctype="multipart/form-data"> -->
            <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
            <input name="study_place" value="<?=$dbrow->form_data->study_place?>" type="hidden" />
            <input name="admit_birth_old" value="<?=$admit_birth?>" type="hidden" />
            <input name="passport_photo_old" value="<?=$passport_photo?>" type="hidden" />
            <input name="signature_old" value="<?=$signature?>" type="hidden" />
            <input name="hs_marksheet_old" value="<?=$hs_marksheet?>" type="hidden" />
            <input name="reg_certificate_old" value="<?=$reg_certificate?>" type="hidden" />
            <input name="reg_certificate_of_concerned_university_old" value="<?=$reg_certificate_of_concerned_university?>" type="hidden" />
            <input name="permanent_registration_certificate_of_concerned_medical_council_old" value="<?=$permanent_registration_certificate_of_concerned_medical_council?>" type="hidden" />
            <input name="noc_from_concerned_medical_council_old" value="<?=$noc_from_concerned_medical_council?>" type="hidden" />
           
            <!-- Outside India -->
            <input name="registration_certificate_from_respective_university_or_equivalent_old" value="<?=$registration_certificate_from_respective_university_or_equivalent?>" type="hidden" />
            <input name="all_marksheets_of_mbbs_or_equivalent_old" value="<?=$all_marksheets_of_mbbs_or_equivalent?>" type="hidden" />
            <input name="mbbs_or_equivalent_degree_pass_certificate_from_university_old" value="<?=$mbbs_or_equivalent_degree_pass_certificate_from_university?>" type="hidden" />

            
            <input name="eligibility_certificate_from_national_medical_commission_old" value="<?=$eligibility_certificate_from_national_medical_commission?>" type="hidden" />

            

            <input name="screening_test_result_from_national_board_of_examination_old" value="<?=$screening_test_result_from_national_board_of_examination?>" type="hidden" />

            

            <input name="passport_and_visa_with_travel_details_old" value="<?=$passport_and_visa_with_travel_details?>" type="hidden" />

            
            <input name="all_documents_related_to_medical_college_details_old" value="<?=$all_documents_related_to_medical_college_details?>" type="hidden" />


            
            <input name="mbbs_pass_certificate_from_university_old" value="<?=$mbbs_pass_certificate_from_university?>" type="hidden" />
            <input name="mbbs_marksheet_old" value="<?=$mbbs_marksheet?>" type="hidden" />
            <input name="pass_certificate_old" value="<?=$pass_certificate?>" type="hidden" />
            <input name="internship_completion_certificate_old" value="<?=$internship_completion_certificate?>" type="hidden" />
            <input name="provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_old" value="<?=$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration?>" type="hidden" />

            <input name="annexure_old" value="<?=$annexure?>" type="hidden" />

            <div class="card shadow-sm" style="background:#E6F1FF">
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
                        <!-- <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div> -->
                    <?php }
                    if ($this->session->flashdata('success') != null) { ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php } ?>

                    <fieldset class="border border-success" style="margin-top:0px">
                        <p>If you wish to upload your enclosures/documents from digilocker, please link your digilocker account. Click on the below button to link your account.</p>
                        <p><?php $this->digilocker_api->login_btn();  ?></p>
                    </fieldset> 

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Please Download this Annexure II and upload</legend>
                        <a href="<?= base_url('assets/acmr_mbbs_permanent_registration/Annexure.pdf') ?>" target="_blank">Please Click</a>
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




<!-- 1,2,3 -->
                                    <?php if(($dbrow->form_data->study_place == 1) || ($dbrow->form_data->study_place == 2) || ($dbrow->form_data->study_place == 3)){ ?> 

                                         
                                        <!-- Photo -->

                                          <tr>
                                            <td>Passport Photo<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="passport_photo_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Passport Size Photo" <?=($passport_photo_type === 'Passport Size Photo')?'selected':''?>>Passport Photo</option>
                                                </select>
                                                <?= form_error("passport_photo_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="passport_photo" name="passport_photo" type="file" />
                                                </div>
                                                <?php if(strlen($passport_photo)){ ?>
                                                    <a href="<?=base_url($passport_photo)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="passport_photo" type="hidden" name="passport_photo_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('passport_photo'); ?>
                                            </td>
                                        </tr>

                                        <!-- Photo end -->

                                        <!-- Sign -->
      <tr>
                                            <td>Signature<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="signature_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Signature" <?=($signature_type === 'Signature')?'selected':''?>>Signature</option>
                                                </select>
                                                <?= form_error("signature_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="signature" name="signature" type="file" />
                                                </div>
                                                <?php if(strlen($signature)){ ?>
                                                    <a href="<?=base_url($signature)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="signature" type="hidden" name="signature_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('signature'); ?>
                                            </td>
                                        </tr>

                                        <!-- Sign end -->
                                    <tr>
                                            <td>Class 10 Admit card/Birth Certificate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="admit_birth_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Class 10 Admit card/Birth Certificate" <?=($admit_birth_type === 'Class 10 Admit card/Birth Certificate')?'selected':''?>>Class 10 Admit card/Birth Certificate</option>
                                                </select>
                                                <?= form_error("admit_birth_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="admit_birth" name="admit_birth" type="file" />
                                                </div>
                                                <?php if(strlen($admit_birth)){ ?>
                                                    <a href="<?=base_url($admit_birth)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="admit_birth" type="hidden" name="admit_birth_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('admit_birth'); ?>
                                            </td>
                                        </tr>

                                        
                                        <tr>
                                            <td>HS Final Marksheet<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="hs_marksheet_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="HS Final Marksheet" <?=($hs_marksheet_type === 'HS Final Marksheet')?'selected':''?>>HS Final Marksheet</option>
                                                </select>
                                                <?= form_error("hs_marksheet_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hs_marksheet" name="hs_marksheet" type="file" />
                                                </div>
                                                <?php if(strlen($hs_marksheet)){ ?>
                                                    <a href="<?=base_url($hs_marksheet)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="hs_marksheet" type="hidden" name="hs_marksheet_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('hs_marksheet'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Internship Completion Certificate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="internship_completion_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Internship Completion Certificate" <?=($internship_completion_certificate_type === 'Internship Completion Certificate')?'selected':''?>>Internship Completion Certificate</option>
                                                </select>
                                                <?= form_error("internship_completion_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="internship_completion_certificate" name="internship_completion_certificate" type="file" />
                                                </div>
                                                <?php if(strlen($internship_completion_certificate)){ ?>
                                                    <a href="<?=base_url($internship_completion_certificate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="internship_completion_certificate" type="hidden" name="internship_completion_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('internship_completion_certificate'); ?>
                                                
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Annexure II <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="annexure_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Annexure II" <?=($annexure_type === 'Annexure II')?'selected':''?>>Annexure II</option>
                                                </select>
                                                <?= form_error("annexure_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="annexure" name="annexure" type="file" />
                                                </div>
                                                <?php if(strlen($annexure)){ ?>
                                                    <a href="<?=base_url($annexure)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="annexure" type="hidden" name="annexure_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('annexure'); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>


                        <!-- 1 -->
                                    <?php if(($dbrow->form_data->study_place == 1) ){ ?> 



                                        <tr>
                                            <td>Registration Certificate from Srimanta Sankaradeva University of Health Sciences or any other University of the State<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="reg_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Registration MBBS Pass Certificate from College/UniversityCertificate from Srimanta Sankaradeva University of Health Sciences or any other University of the State" <?=($reg_certificate_type === 'Registration Certificate from Srimanta Sankaradeva University of Health Sciences or any other University of the State')?'selected':''?>>Registration Certificate from Srimanta Sankaradeva University of Health Sciences or any other University of the State</option>
                                                </select>
                                                <?= form_error("reg_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="reg_certificate" name="reg_certificate" type="file" />
                                                </div>
                                                <?php if(strlen($reg_certificate)){ ?>
                                                    <a href="<?=base_url($reg_certificate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="reg_certificate" type="hidden" name="reg_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('reg_certificate'); ?>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>MBBS Pass Certificate from College/University <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="pass_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="MBBS Pass Certificate from College/University" <?=($pass_certificate_type === 'MBBS Pass Certificate from College/University')?'selected':''?>>MBBS Pass Certificate from College/University</option>
                                                </select>
                                                <?= form_error("pass_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="pass_certificate" name="pass_certificate" type="file" />
                                                </div>
                                                <?php if(strlen($pass_certificate)){ ?>
                                                    <a href="<?=base_url($pass_certificate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="pass_certificate" type="hidden" name="pass_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('pass_certificate'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Provisional Registration Certificate of concerned Assam Council of Medical Registration <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Provisional Registration Certificate of concerned Assam Council of Medical Registration" <?=($provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type === 'Provisional Registration Certificate of concerned Assam Council of Medical Registration')?'selected':''?>>Provisional Registration Certificate of concerned Assam Council of Medical Registration</option>
                                                </select>
                                                <?= form_error("provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="provisional_registration_certificate_of_concerned_assam_council_of_medical_registration" name="provisional_registration_certificate_of_concerned_assam_council_of_medical_registration" type="file" />
                                                </div>
                                                <?php if(strlen($provisional_registration_certificate_of_concerned_assam_council_of_medical_registration)){ ?>
                                                    <a href="<?=base_url($provisional_registration_certificate_of_concerned_assam_council_of_medical_registration)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="provisional_registration_certificate_of_concerned_assam_council_of_medical_registration" type="hidden" name="provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('provisional_registration_certificate_of_concerned_assam_council_of_medical_registration'); ?>
                                            </td>
                                        </tr>
                                                                                


<?php } ?>

<!-- 1,2 -->

<?php if(($dbrow->form_data->study_place == 1)|| ($dbrow->form_data->study_place == 2) ){ ?> 


    <tr>
                                            <td>All Marksheets of MBBS<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="mbbs_marksheet_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="All Marksheets of MBBS" <?=($mbbs_marksheet_type === 'All Marksheets of MBBS')?'selected':''?>>All Marksheets of MBBS</option>
                                                </select>
                                                <?= form_error("mbbs_marksheet_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="mbbs_marksheet" name="mbbs_marksheet" type="file" />
                                                </div>
                                                <?php if(strlen($mbbs_marksheet)){ ?>
                                                    <a href="<?=base_url($mbbs_marksheet)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="mbbs_marksheet" type="hidden" name="mbbs_marksheet_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('mbbs_marksheet'); ?>
                                            </td>
                                        </tr>

                               
                                        


<?php } ?>


<!-- 2 -->

<?php if(($dbrow->form_data->study_place == 2) ){ ?> 

<tr>
                                            <td>Registration Certificate of concerned University<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="reg_certificate_of_concerned_university_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Registration Certificate of concerned University" <?=($reg_certificate_of_concerned_university_type === 'Registration Certificate of concerned University')?'selected':''?>>Registration Certificate of concerned University</option>
                                                </select>
                                                <?= form_error("reg_certificate_of_concerned_university_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="reg_certificate_of_concerned_university" name="reg_certificate_of_concerned_university" type="file" />
                                                </div>
                                                <?php if(strlen($reg_certificate_of_concerned_university)){ ?>
                                                    <a href="<?=base_url($reg_certificate_of_concerned_university)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="reg_certificate_of_concerned_university" type="hidden" name="reg_certificate_of_concerned_university_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('reg_certificate_of_concerned_university'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>MBBS Pass Certificate from University<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="mbbs_pass_certificate_from_university_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="MBBS Pass Certificate from University" <?=($mbbs_pass_certificate_from_university_type === 'MBBS Pass Certificate from University')?'selected':''?>>MBBS Pass Certificate from University</option>
                                                </select>
                                                <?= form_error("mbbs_pass_certificate_from_university_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="mbbs_pass_certificate_from_university" name="mbbs_pass_certificate_from_university" type="file" />
                                                </div>
                                                <?php if(strlen($mbbs_pass_certificate_from_university)){ ?>
                                                    <a href="<?=base_url($mbbs_pass_certificate_from_university)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>

                                                <input class="mbbs_pass_certificate_from_university" type="hidden" name="mbbs_pass_certificate_from_university_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('mbbs_pass_certificate_from_university'); ?>
                                            </td>
                                        </tr>


                                        


                                       


                                        <?php } ?>

<!-- 3 -->

<?php if(($dbrow->form_data->study_place == 3) ){ ?> 


<tr>
                                            <td>Registration Certificate from respective University or equivalent<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="registration_certificate_from_respective_university_or_equivalent_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Registration Certificate from respective University or equivalent" <?=($registration_certificate_from_respective_university_or_equivalent_type === 'Registration Certificate from respective University or equivalent')?'selected':''?>>Registration Certificate from respective University or equivalent</option>
                                                </select>
                                                <?= form_error("registration_certificate_from_respective_university_or_equivalent_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="registration_certificate_from_respective_university_or_equivalent" name="registration_certificate_from_respective_university_or_equivalent" type="file" />
                                                </div>
                                                <?php if(strlen($registration_certificate_from_respective_university_or_equivalent)){ ?>
                                                    <a href="<?=base_url($registration_certificate_from_respective_university_or_equivalent)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="registration_certificate_from_respective_university_or_equivalent" type="hidden" name="registration_certificate_from_respective_university_or_equivalent_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('registration_certificate_from_respective_university_or_equivalent'); ?>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>All Marksheets of MBBS or equivalent<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="all_marksheets_of_mbbs_or_equivalent_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="All Marksheets of MBBS or equivalent" <?=($all_marksheets_of_mbbs_or_equivalent_type === 'All Marksheets of MBBS or equivalent')?'selected':''?>>All Marksheets of MBBS or equivalent</option>
                                                </select>
                                                <?= form_error("all_marksheets_of_mbbs_or_equivalent_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="all_marksheets_of_mbbs_or_equivalent" name="all_marksheets_of_mbbs_or_equivalent" type="file" />
                                                </div>
                                                <?php if(strlen($all_marksheets_of_mbbs_or_equivalent)){ ?>
                                                    <a href="<?=base_url($all_marksheets_of_mbbs_or_equivalent)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="all_marksheets_of_mbbs_or_equivalent" type="hidden" name="all_marksheets_of_mbbs_or_equivalent_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('all_marksheets_of_mbbs_or_equivalent'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>MBBS or equivalent Degree Pass Certificate from University<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="mbbs_or_equivalent_degree_pass_certificate_from_university_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="MBBS or equivalent Degree Pass Certificate from University" <?=($mbbs_or_equivalent_degree_pass_certificate_from_university_type === 'MBBS or equivalent Degree Pass Certificate from University')?'selected':''?>>MBBS or equivalent Degree Pass Certificate from University</option>
                                                </select>
                                                <?= form_error("mbbs_or_equivalent_degree_pass_certificate_from_university_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="mbbs_or_equivalent_degree_pass_certificate_from_university" name="mbbs_or_equivalent_degree_pass_certificate_from_university" type="file" />
                                                </div>
                                                <?php if(strlen($mbbs_or_equivalent_degree_pass_certificate_from_university)){ ?>
                                                    <a href="<?=base_url($mbbs_or_equivalent_degree_pass_certificate_from_university)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="mbbs_or_equivalent_degree_pass_certificate_from_university" type="hidden" name="mbbs_or_equivalent_degree_pass_certificate_from_university_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('mbbs_or_equivalent_degree_pass_certificate_from_university'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Eligibility Certificate from National Medical Commission<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="eligibility_certificate_from_national_medical_commission_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Eligibility Certificate from National Medical Commission" <?=($eligibility_certificate_from_national_medical_commission_type === 'Eligibility Certificate from National Medical Commission')?'selected':''?>>Eligibility Certificate from National Medical Commission</option>
                                                </select>
                                                <?= form_error("eligibility_certificate_from_national_medical_commission_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="eligibility_certificate_from_national_medical_commission" name="eligibility_certificate_from_national_medical_commission" type="file" />
                                                </div>
                                                <?php if(strlen($eligibility_certificate_from_national_medical_commission)){ ?>
                                                    <a href="<?=base_url($eligibility_certificate_from_national_medical_commission)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="eligibility_certificate_from_national_medical_commission" type="hidden" name="eligibility_certificate_from_national_medical_commission_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('eligibility_certificate_from_national_medical_commission'); ?>
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>Screening Test Result from National Board of Examination<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="screening_test_result_from_national_board_of_examination_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Screening Test Result from National Board of Examination" <?=($screening_test_result_from_national_board_of_examination_type === 'Screening Test Result from National Board of Examination')?'selected':''?>>Screening Test Result from National Board of Examination</option>
                                                </select>
                                                <?= form_error("screening_test_result_from_national_board_of_examination_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="screening_test_result_from_national_board_of_examination" name="screening_test_result_from_national_board_of_examination" type="file" />
                                                </div>
                                                <?php if(strlen($screening_test_result_from_national_board_of_examination)){ ?>
                                                    <a href="<?=base_url($screening_test_result_from_national_board_of_examination)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="screening_test_result_from_national_board_of_examination" type="hidden" name="screening_test_result_from_national_board_of_examination_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('screening_test_result_from_national_board_of_examination'); ?>
                                            </td>
                                        </tr>

                                        <!-- Passport and Visa with travel details -->

                                        <tr>
                                            <td>Passport and Visa with travel details<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="passport_and_visa_with_travel_details_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Passport and Visa with travel details" <?=($passport_and_visa_with_travel_details_type === 'Passport and Visa with travel details')?'selected':''?>>Passport and Visa with travel details</option>
                                                </select>
                                                <?= form_error("passport_and_visa_with_travel_details_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="passport_and_visa_with_travel_details" name="passport_and_visa_with_travel_details" type="file" />
                                                </div>
                                                <?php if(strlen($passport_and_visa_with_travel_details)){ ?>
                                                    <a href="<?=base_url($passport_and_visa_with_travel_details)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="passport_and_visa_with_travel_details" type="hidden" name="passport_and_visa_with_travel_details_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('passport_and_visa_with_travel_details'); ?>
                                            </td>
                                        </tr>


                                        <!-- All documents related to medical college details -->
                                        <tr>
                                            <td>All documents related to medical college details<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="all_documents_related_to_medical_college_details_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="All documents related to medical college details" <?=($all_documents_related_to_medical_college_details_type === 'All documents related to medical college details')?'selected':''?>>All documents related to medical college details</option>
                                                </select>
                                                <?= form_error("all_documents_related_to_medical_college_details_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="all_documents_related_to_medical_college_details" name="all_documents_related_to_medical_college_details" type="file" />
                                                </div>
                                                <?php if(strlen($all_documents_related_to_medical_college_details)){ ?>
                                                    <a href="<?=base_url($all_documents_related_to_medical_college_details)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="all_documents_related_to_medical_college_details" type="hidden" name="all_documents_related_to_medical_college_details_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('all_documents_related_to_medical_college_details'); ?>
                                            </td>
                                        </tr>


                                        <?php } ?>


        <!-- 2,3 -->
        <?php if(($dbrow->form_data->study_place == 2) || ($dbrow->form_data->study_place == 3)){ ?> 


        <tr>
                                            <td>Permanent Registration Certificate of concerned Medical Council<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="permanent_registration_certificate_of_concerned_medical_council_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Permanent Registration Certificate of concerned Medical Council" <?=($permanent_registration_certificate_of_concerned_medical_council_type === 'Permanent Registration Certificate of concerned Medical Council')?'selected':''?>>Permanent Registration Certificate of concerned Medical Council</option>
                                                </select>
                                                <?= form_error("permanent_registration_certificate_of_concerned_medical_council_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="permanent_registration_certificate_of_concerned_medical_council" name="permanent_registration_certificate_of_concerned_medical_council" type="file" />
                                                </div>
                                                <?php if(strlen($permanent_registration_certificate_of_concerned_medical_council)){ ?>
                                                    <a href="<?=base_url($permanent_registration_certificate_of_concerned_medical_council)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="permanent_registration_certificate_of_concerned_medical_council" type="hidden" name="permanent_registration_certificate_of_concerned_medical_council_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('permanent_registration_certificate_of_concerned_medical_council'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>NOC from concerned Medical Council<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="noc_from_concerned_medical_council_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="NOC from concerned Medical Council" <?=($noc_from_concerned_medical_council_type === 'NOC from concerned Medical Council')?'selected':''?>>NOC from concerned Medical Council</option>
                                                </select>
                                                <?= form_error("noc_from_concerned_medical_council_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="noc_from_concerned_medical_council" name="noc_from_concerned_medical_council" type="file" />
                                                </div>
                                                <?php if(strlen($noc_from_concerned_medical_council)){ ?>
                                                    <a href="<?=base_url($noc_from_concerned_medical_council)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="noc_from_concerned_medical_council" type="hidden" name="noc_from_concerned_medical_council_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('noc_from_concerned_medical_council'); ?>
                                            </td>
                                        </tr>

                                        <?php } ?>
                                            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?=site_url('spservices/permanent_registration_mbbs/registration/index/'.$obj_id)?>" class="btn btn-primary">
                        <i class="fa fa-angle-double-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-angle-double-right"></i> Save &amp Next
                    </button>
                    <button type="reset" class="btn btn-danger">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>