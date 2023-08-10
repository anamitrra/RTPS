
<!-- Hello -->
<?php
$currentYear = date('Y');
//$apiServer = $this->config->item('edistrict_base_url');
$apiServer = "https://localhost/wptbcapis/"; //For testing
if($dbrow) {

    // pre($dbrow->form_data);

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

    $permanent_addr = $dbrow->form_data->permanent_addr;

    $correspondence_addr = $dbrow->form_data->correspondence_addr;

    $aadhar_no = $dbrow->form_data->aadhar_no;

    $pan_no = $dbrow->form_data->pan_no;

    $primary_qualification = $dbrow->form_data->primary_qualification;

    $primary_qua_doc = $dbrow->form_data->primary_qua_doc;

    $college_name = $dbrow->form_data->college_name;

    $primary_qua_college_addr = $dbrow->form_data->primary_qua_college_addr;

    $primary_qua_course_dur = $dbrow->form_data->primary_qua_course_dur;

    // $primary_qua_doci = $dbrow->form_data->primary_qua_doci;

    $primary_qua_university_award_intership = $dbrow->form_data->primary_qua_university_award_intership;

    $acmrrno = $dbrow->form_data->acmrrno;

    $prn = $dbrow->form_data->prn;

    $registration_date = $dbrow->form_data->registration_date;


    // $student_studied = $dbrow->form_data->student_studied;


    $study_place = $dbrow->form_data->study_place;
    $address1 = $dbrow->form_data->address1;
    $address2 = $dbrow->form_data->address2;
    $country = $dbrow->form_data->country;
    $state = $dbrow->form_data->state;
    $district = $dbrow->form_data->district;
    $pincode = $dbrow->form_data->pincode;



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
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/iservices/js/custom/digilocker.js') ?>"></script>

<script type="text/javascript">   
    $(document).ready(function () {  
         
         $(document).on("change", "#study_place", function(){            
             var stdType = $(this).val(); 
             if(stdType.length === 0) {
                 $("#applicant_studied").hide();
             } else {
                 $("#applicant_studied").show();
                 if(stdType == 1){
                     $("#country").val('India');
                     $("#country").prop('readonly', true);
                 }
                 else{
                     $("#country").val("");
                     $("#country").prop('readonly', false);
                 }
             }
                 
         }); 

                 var passportPhoto = parseInt(<?=strlen($passport_photo)?1:0?>);
        $("#passport_photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required:false,
            //required: admitbirth?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["PNG"]
        });

        var signature = parseInt(<?=strlen($signature)?1:0?>);
        $("#signature").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required:false,
            //required: admitbirth?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["PNG"]
        });
 
 
         $(document).on("click", ".frmbtn", function(){ 
             let clickedBtn = $(this).attr("id");//alert(clickedBtn);
             $("#submit_mode").val(clickedBtn);
             if(clickedBtn === 'DRAFT') {
                 var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
             } else if(clickedBtn === 'SAVE') {
                 var msg = "Do you want to procced";
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
         
         $(".dp").datepicker({
             format: 'dd/mm/yyyy',
             endDate: '+0d',
             autoclose: true
         });
         
         
         $(document).on("change", "#dob", function(){             
             var dd = $(this).val(); //alert(dd);
             var dateAr = dd.split('/');
             var dob = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];
         });
 
         $(document).on("keyup", "#pan_no", function(){ 
             if($("#pan_no").val().length > 10) {
                 $("#pan_no").val("");
                 alert("Please! Enter upto only 10 digit"); 
             }             
         });
 
         $('.number_input').keypress(function (e) {    
             var charCode = (e.which) ? e.which : event.keyCode    
             if (String.fromCharCode(charCode).match(/[^0-9]/g))    
                 return false;                        
         });
 
         $('#aadhar_no').keyup(function () {    
             if($("#aadhar_no").val().length > 12) {
                 $("#aadhar_no").val("");
                 alert("Please! Enter upto only 12 digit"); 
             }                        
         });
 
         $('.pin_code').keyup(function () {    
             if($(".pin_code").val().length > 6) {
                 $(".pin_code").val("");
                 alert("Please! Enter upto only 6 digit"); 
             }                        
         });


         var admitbirth = parseInt(<?=strlen($admit_birth)?1:0?>);
        $("#admit_birth").fileinput({
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


        $(document).on("click", ".frmbtn", function(){ 
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE') {
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
                    if((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
                        $("#myfrm").submit();
                        $(".frmbtn").hide();
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/permanent_registration_mbbs/registration/querysubmit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />

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
            <!-- End -->

         
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm" >
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
                    if($status === 'QS') { ?>
                        <fieldset class="border border-danger" style="margin-top:40px; margin-bottom: 2px">
                            <legend class="h5">QUERY DETAILS </legend>
                            <div class="row">
                                <div class="col-md-12" style="font-size:16px; margin-bottom: 10px; text-align: justify">                                    
                                    <?=(end($dbrow->processing_history)->remarks)??''?>
                                </div>
                            </div>                            
                            <span style="float:right; font-size: 12px">
                                Query time : <?=isset(end($dbrow->processing_history)->processing_time)?format_mongo_date(end($dbrow->processing_history)->processing_time):''?>
                            </span>
                        </fieldset>
                    <?php }//End of if ?>
                    
                    

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <!-- This -->
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255" readonly />
                                <?= form_error("applicant_name") ?>
                            </div>

                            <!-- This -->
                                 <div class="col-md-6">
                                <label>Father&apos;s Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?=$father_name?>" maxlength="255" readonly />
                                <?= form_error("father_name") ?>
                            </div>

                        </div>


                        <!-- FM -->
                        <div class="row form-group">

                            <!-- This -->
                            <div class="col-md-6">
                                <label>Mother&apos;s Name/ মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name" value="<?=$mother_name?>" maxlength="255" readonly />
                                <?= form_error("mother_name") ?>
                            </div>


                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control" readonly >
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($applicant_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($applicant_gender === "Female")?'selected':''?>>Female</option>
                                    <option value="Transgender" <?=($applicant_gender === "Transgender")?'selected':''?>>Transgender</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                       

                        </div>




                        <div class="row form-group">

                        <!-- This -->
                            <div class="col-md-6 form-group">
                                <label>Date of Birth/ জন্ম তাৰিখ<span class="text-danger">*</span> </label>
                                <input readonly class="form-control dp" name="dob" id="dob" value="<?=$dob?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("dob") ?>
                            </div>

                            <!-- This -->
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" readonly />
                               
                                <?= form_error("mobile") ?>
                            </div>
                        </div>
                    
                        <div class="row form-group">

                        <!-- This -->
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100" readonly />
                                <?= form_error("email") ?>
                            </div>
                            <div class="col-md-6"></div>
                        </div> 

                        <div class="row"> 
                            <!-- This -->
                            <div class="col-md-6 form-group">
                                <label>Permanent Address/ স্থায়ী ঠিকনা <span class="text-danger">*</span> </label>
                                <textarea readonly  class="form-control" name="permanent_addr"><?=$permanent_addr?></textarea>
                                <?= form_error("permanent_addr") ?>
                            </div>

                            <!-- This -->
                            <div class="col-md-6 form-group">
                                <label>Correspondence Address/ চিঠিপত্ৰৰ ঠিকনা <span class="text-danger">*</span> </label>
                                <textarea readonly  class="form-control" name="correspondence_addr"><?=$correspondence_addr?></textarea>
                                <?= form_error("correspondence_addr") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <!-- This -->
                            <div class="col-md-6 form-group">
                                <label>Aadhar No./আধাৰ নং </label>
                                <input readonly class="form-control number_input" name="aadhar_no" value="<?=$aadhar_no?>" maxlength="12" type="text" id="aadhar_no"/>
                                <?= form_error("aadhar_no") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <!-- This -->
                                <label>PAN No./ পেন নং </label>
                                <input readonly  class="form-control pan_no" name="pan_no" value="<?=$pan_no?>" maxlength="10" type="text" />
                                <?= form_error("pan_no") ?>
                            </div>
                        </div> 
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Primary Qualification / প্ৰাথমিক অৰ্হতা </legend>
                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Primary Qualification(MBBS or equivalent)/ প্ৰাথমিক অৰ্হতা(MBBS বা সমতুল্য) <span class="text-danger">*</span></label>
                                <input readonly  class="form-control" name="primary_qualification" value="<?=$primary_qualification?>" type="text" />
                                <?= form_error("primary_qualification") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Date of completion/ সম্পূৰ্ণ হোৱাৰ তাৰিখ <span class="text-danger">*</span></label>
                                <input readonly  class="form-control dp" name="primary_qua_doc" value="<?=$primary_qua_doc?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("primary_qua_doc") ?>
                            </div>
                        </div> 

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>College Name/ কলেজৰ নাম <span class="text-danger">*</span></label>
                                <input readonly  class="form-control" name="college_name" value="<?=$college_name?>" type="text" />
                                <?= form_error("college_name") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>College Address/ কলেজৰ ঠিকনা <span class="text-danger">*</span></label>
                                <input readonly  class="form-control" value="<?=$primary_qua_college_addr?>" name="primary_qua_college_addr" type="text" />
                                <?= form_error("primary_qua_college_addr") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Course Duration/ পাঠ্যক্ৰমৰ সময়সীমা <span class="text-danger">*</span></label>
                                <input readonly  class="form-control" name="primary_qua_course_dur" value="<?=$primary_qua_course_dur?>" type="text" />
                                <?= form_error("primary_qua_course_dur") ?>
                            </div> 
                            <!-- <div class="col-md-6 form-group">
                                <label>Date of Completion of Internship/ ইন্টাৰশ্বিপ সম্পূৰ্ণ হোৱাৰ তাৰিখ <span class="text-danger">*</span></label>
                                <input class="form-control dp" name="primary_qua_doci" value="<?=$primary_qua_doci?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("primary_qua_doci") ?>
                            </div> -->
                            <div class="col-md-6 form-group">
                                <label>University awarding the Internship/ ইন্টাৰশ্বিপ প্ৰদান কৰা বিশ্ববিদ্যালয় <span class="text-danger">*</span> </label>
                                <input readonly class="form-control" name="primary_qua_university_award_intership" value="<?=$primary_qua_university_award_intership?>" type="text" />
                                <?= form_error("primary_qua_university_award_intership") ?>
                            </div> 
                        </div>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Medical Registration Details / চিকিৎসা পঞ্জীয়নৰ বিৱৰণ </legend>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>State Medical Council registered with/ ৰাজ্যিক চিকিৎসা পৰিষদৰ সৈতে পঞ্জীয়ন<span class="text-danger">*</span></label>
                                <input readonly  class="form-control" name="acmrrno" value="<?=$acmrrno?>" id="acmrrno" type="text" />
                                <?= form_error("acmrrno") ?>
                            </div> 
                            
                            <div class="col-md-6 form-group">
                                <label>Provisional Registration Number/ অস্থায়ী পঞ্জীয়ন নম্বৰ<span class="text-danger">*</span></label>
                                <input readonly  class="form-control" name="prn" value="<?=$prn?>" id="prn" type="text" />
                                <?= form_error("prn") ?>
                            </div> 

                        </div> 

                        <div class="row"> 



                            <div class="col-md-6 form-group">
                                <label>Registration Date/ পঞ্জীয়নৰ তাৰিখ <span class="text-danger">*</span></label>
                                <input readonly  class="form-control dp" name="registration_date" value="<?=$registration_date?>" maxlength="10" id="registration_date" type="text" />
                                <?= form_error("registration_date") ?>
                            </div>

                            
                        </div> 

                    </fieldset>


                    <fieldset class="border border-success" style="margin-top:40px">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Please select where the Applicant Studied <span class="text-danger">*</span> </label>
                                <select readonly  name="study_place" id="study_place" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php if(!empty($study_place)){ ?>
                                        <option value="1" <?php ($study_place == 1)? print "selected": '' ?>>Applicant studied within the State/Meghalaya</option>
                                        <option value="2" <?php ($study_place == 2)? print "selected": '' ?>>Applicant studied outside the State/Meghalaya but within India</option>
                                        <option value="3" <?php ($study_place == 3)? print "selected": '' ?>>Applicant studied outside the Country</option>
                                    <?php } else{?>
                                        <option value="1">Applicant studied within the State/Meghalaya</option>
                                        <option value="2">Applicant studied outside the State/Meghalaya but within India</option>
                                        <option value="3">Applicant studied outside the Country</option>
                                    <?php }?>
                                </select>
                                <?= form_error("study_place") ?>
                            </div>    
                               </div>
                               </fieldset>



                               <fieldset id="applicant_studied" class="border border-success" style="margin-top:40px; display: <?php isset($study_place)? 'block': 'none'?>;">
                        <legend class="h5">Address of Study Place</legend>
                        <div class="row form-group">
                             <div class="col-md-6">
                                <label>Address Line 1 / ঠিকনাৰ প্রথ্ম শাৰী<span class="text-danger"></span> </label>
                                <input readonly  type="text" class="form-control" name="address1" value="<?=$address1?>" maxlength="255" />
                                <?= form_error("address1") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address Line 2 / ঠিকনাৰ চিতীয় শাৰ<span class="text-danger"></span> </label>
                                <input readonly  type="text" class="form-control" name="address2" value="<?=$address2?>" maxlength="255" />
                                <?= form_error("address2") ?>
                            </div>
                               </div>
                                <div class="row form-group">
                            <div class="col-md-6">
                                <label>State<span class="text-danger">*</span> </label>
                                <select readonly  name="state" id="state" class="form-control">
                                    <option value="Assam">Assam</option>
                                    <option value="Assam">Meghalaya</option>
                                </select>
                                <?= form_error("state") ?>
                            </div>
                            <div class="col-md-6">
                                <label>District / জিলা<span class="text-danger">*</span> </label>
                                <input readonly type="text" class="form-control" name="district" value="<?=$district?>" maxlength="255" />
                                <?= form_error("district") ?>                       
                            </div>
                               </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>Pincode / পিনকোড<span class="text-danger">*</span> </label>
                                    <input readonly type="text" class="form-control" name="pincode" value="<?=$pincode?>" maxlength="255" />
                                    <?= form_error("pincode") ?>
                                </div>
                                <div class="col-md-6">
                                    <label>Country<span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="country" id="country" value="<?=$country?>" maxlength="255" readonly/>
                                    <?= form_error("country") ?>
                                </div>
                            </div>                            
                     </fieldset>   
                     
                     
                     <!-- Docs -->

                     <fieldset class="border border-success" style="margin-top:0px">
                        <p>If you wish to upload your enclosures/documents from digilocker, please link your digilocker account. Click on the below button to link your account.</p>
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
                                            <td>Internship Completion Certificate</td>
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
                                                    <option value="Registration Certificate from Srimanta Sankaradeva University of Health Sciences or any other University of the State" <?=($reg_certificate_type === 'Registration Certificate from Srimanta Sankaradeva University of Health Sciences or any other University of the State')?'selected':''?>>Registration Certificate from Srimanta Sankaradeva University of Health Sciences or any other University of the State</option>
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
                                            <td>MBBS Pass Certificate from College/University</td>
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
                                            <td>Provisional Registration Certificate of concerned Assam Council of Medical Registration</td>
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
                                            <td>All Marksheets of MBBS or equivalent</td>
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
                                            <td>MBBS or equivalent Degree Pass Certificate from University</td>
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
                                            <td>Eligibility Certificate from National Medical Commission</td>
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
                                            <td>Screening Test Result from National Board of Examination</td>
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
                                            <td>Passport and Visa with travel details</td>
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
                                            <td>All documents related to medical college details</td>
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






                               




                                       

                                        <!-- Applicant studied outside the State/Meghalaya but within India -->
                                        <!-- <?php if ($dbrow->form_data->state_other_than_as_ml &&  $dbrow->form_data->country=="") { ?> -->
                                       




                                      



                                        <!-- Permanent Registration Certificate of concerned Medical Council -->

                                      


                                        <!-- NOC from concerned Medical Council -->


                                        
                                       <!-- <?php }  ?> -->


                                       <!-- Applicant studied outside the Country -->

                                       <?php if ($dbrow->form_data->country) { ?>
                                        <!-- Registration Certificate from respective University or equivalent -->
                                       

                                        <!-- All Marksheets of MBBS or equivalent -->

                                     

                                        <!-- MBBS or equivalent Degree Pass Certificate from University -->

                                     


                                        <!-- Eligibility Certificate from National Medical Commission -->
                                   

                                        <!-- Screening Test Result from National Board of Examination -->
                                      


                                        <?php }  ?>

                                       




                                            <!-- Applicant studied outside the State/Meghalaya but within India -->
                                            <!-- <?php if ($dbrow->form_data->state_other_than_as_ml &&  $dbrow->form_data->country=="") { ?>
                                                <tr>
                                            <td>MBBS Pass Certificate from University</td>
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
                                            </td>
                                        </tr>
                                       <?php }  ?> -->

                                



                                        

                                        <!-- <tr>
                                            <td>PGDegree/DiplomaMarksheet*.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="pg_degree_dip_marksheet_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="PGDegree/DiplomaMarksheet" <?=($pg_degree_dip_marksheet_type === 'PGDegree/DiplomaMarksheet')?'selected':''?>>PGDegree/DiplomaMarksheet</option>
                                                </select>
                                                <?= form_error("pg_degree_dip_marksheet_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="pg_degree_dip_marksheet" name="pg_degree_dip_marksheet" type="file" />
                                                </div>
                                                <?php if(strlen($pg_degree_dip_marksheet)){ ?>
                                                    <a href="<?=base_url($pg_degree_dip_marksheet)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr> -->
<!-- 
                                        <tr>
                                            <td>Permanent Registration Certificate of Assam Council of Medical Registration*.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="prc_acmr_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Permanent Registration Certificate of Assam Council of Medical Registration" <?=($prc_acmr_type === 'Permanent Registration Certificate of Assam Council of Medical Registration')?'selected':''?>>Permanent Registration Certificate of Assam Council of Medical Registration</option>
                                                </select>
                                                <?= form_error("prc_acmr_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="prc_acmr" name="prc_acmr" type="file" />
                                                </div>
                                                <?php if(strlen($prc_acmr)){ ?>
                                                    <a href="<?=base_url($prc_acmr)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr> -->
<!-- 
                                        <tr>
                                            <td>Other Additional Degrees (If any)</td>
                                            <td>
                                                <select name="other_addl_degree_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Other Additional Degrees (If any)" <?=($other_addl_degree_type === 'Other Additional Degrees (If any)')?'selected':''?>>Other Additional Degrees (If any)</option>
                                                </select>
                                                <?= form_error("other_addl_degree_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="other_addl_degree" name="other_addl_degree" type="file" />
                                                </div>
                                                <?php if(strlen($other_addl_degree)){ ?>
                                                    <a href="<?=base_url($other_addl_degree)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr> -->
                                            
                                        <!-- <?php if($this->slug == 'userrr') { ?>
                                            <tr>
                                                <td>Soft copy of the applicant form<span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="soft_copy_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Soft copy of the applicant form" <?=($soft_copy_type === 'Soft copy of the applicant form')?'selected':''?>>Soft copy of the applicant form</option>
                                                    </select>
                                                    <?= form_error("soft_copy_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="soft_copy" name="soft_copy" type="file" />
                                                    </div>
                                                    <?php if(strlen($soft_copy)){ ?>
                                                        <a href="<?=base_url($soft_copy)?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span> 
                                                            View/Download
                                                        </a>
                                                    <?php }//End of if ?>
                                                </td>
                                            </tr>
                                        <?php }//End of if ?> -->
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