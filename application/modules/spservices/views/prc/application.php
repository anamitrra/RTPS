<?php
    //$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
    $apiServer = "https://localhost/wptbcapis/"; //For testing
    //$apiServer = "https://rtps.assam.gov.in/app_test/apis/homeapis/"; //For UAT
    //$apiServer1 = "https://localhost/homeapis/"; //For testing
if($dbrow) {

    $obj_id = $dbrow->{'_id'}->{'$id'};       
    $rtps_trans_id = $dbrow->form_data->rtps_trans_id;
    $form_status = $dbrow->service_data->appl_status;

    $district_id = $dbrow->form_data->district_id;
    $subdivison_id = $dbrow->form_data->subdivison_id;
    $circle_id = $dbrow->form_data->circle_id;

    $district_id_ca = $dbrow->form_data->district_id_ca;
    $subdivison_id_ca = $dbrow->form_data->subdivison_id_ca;
    $circle_id_ca = $dbrow->form_data->circle_id_ca;

    $applicant_name = $dbrow->form_data->applicant_name;
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $contact_number = $dbrow->form_data->mobile_number;
    $emailid = $dbrow->form_data->email;
    $dob = $dbrow->form_data->dob;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $spouse_name = $dbrow->form_data->spouse_name;
    $passport_no = $dbrow->form_data->passport_no;
    $pan = $dbrow->form_data->pan;
    $aadhar_no = $dbrow->form_data->aadhar_no;

    $pa_house_no = $dbrow->form_data->pa_house_no;
    $pa_village = $dbrow->form_data->pa_village;
    $pa_post_office = $dbrow->form_data->pa_post_office;
    $pa_pin_code = $dbrow->form_data->pa_pin_code;
    $pa_state = $dbrow->form_data->pa_state;
    $pa_country = $dbrow->form_data->pa_country;
    $pa_district = $dbrow->form_data->pa_district;
    $pa_police_station = $dbrow->form_data->pa_police_station;
    $pa_police_station_code = $dbrow->form_data->pa_police_station_code;
    $pa_subdivision = $dbrow->form_data->pa_subdivision;
    $pa_revenuecircle = $dbrow->form_data->pa_revenuecircle;
    $pa_year = $dbrow->form_data->pa_year;
    $pa_month = $dbrow->form_data->pa_month;
    $pa_mouza = $dbrow->form_data->pa_mouza;

    $address_same = $dbrow->form_data->address_same;

    $ca_house_no = $dbrow->form_data->ca_house_no;
    $ca_village = $dbrow->form_data->ca_village;
    $ca_post_office = $dbrow->form_data->ca_post_office;
    $ca_pin_code = $dbrow->form_data->ca_pin_code;
    $ca_country = $dbrow->form_data->ca_country;
    $ca_state = $dbrow->form_data->ca_state;
    $ca_district = $dbrow->form_data->ca_district;
    $ca_police_station = $dbrow->form_data->ca_police_station;
    $ca_police_station_code = $dbrow->form_data->ca_police_station_code;
    $ca_subdivision = $dbrow->form_data->ca_subdivision;
    $ca_revenuecircle = $dbrow->form_data->ca_revenuecircle;
    $ca_year = $dbrow->form_data->ca_year;
    $ca_month = $dbrow->form_data->ca_month;
    $ca_mouza = $dbrow->form_data->ca_mouza;

    $purpose = $dbrow->form_data->purpose;
    $institute_name = $dbrow->form_data->institute_name;
    $criminal_rec = $dbrow->form_data->criminal_rec;

    //$soft_doc_type = $dbrow->form_data->soft_doc_type;
    //$soft_doc = $dbrow->form_data->soft_doc;
    $birth_doc_type = $dbrow->form_data->birth_doc_type;
    $birth_doc = $dbrow->form_data->birth_doc;

    $passport_doc_type = $dbrow->form_data->passport_doc_type;
    $passport_doc = $dbrow->form_data->passport_doc;
    $emp_proof_type = $dbrow->form_data->emp_proof_type;
    $emp_doc = $dbrow->form_data->emp_doc;
    $address_doc_type = $dbrow->form_data->address_doc_type;
    $address_doc = $dbrow->form_data->address_doc;
    $forefathers_doc_type = $dbrow->form_data->forefathers_doc_type;
    $forefathers_doc = $dbrow->form_data->forefathers_doc;
    $property_doc_type = $dbrow->form_data->property_doc_type;
    $property_doc = $dbrow->form_data->property_doc;
    $voter_doc_type = $dbrow->form_data->voter_doc_type;
    $voter_doc = $dbrow->form_data->voter_doc;
    $passport_pic_type = $dbrow->form_data->passport_pic_type;
    $passport_pic = $dbrow->form_data->passport_pic;
    $prc_doc_type = $dbrow->form_data->prc_doc_type;
    $prc_doc = $dbrow->form_data->prc_doc;
    $admit_doc_type = $dbrow->form_data->admit_doc_type;
    $admit_doc = $dbrow->form_data->admit_doc;
    $certificate_language = $dbrow->form_data->certificate_language;

    if($form_status==='QS'){
        //$query_doc = $dbrow->query_doc??'';
        $query_asked = $dbrow->processing_history[count($dbrow->processing_history)-1]->remarks??'This is the query asked by departmental user';
        $queried_by = $dbrow->processing_history[count($dbrow->processing_history)-1]->processed_by??'Departmental user';
        $qTime = $dbrow->execution_data[1]->task_details->received_time??$dbrow->service_data->submission_date;
        $queried_time = date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($qTime)));
        //$query_answered = $dbrow->form_data->query_answered??'';
    }
}
else {
    $obj_id = null;        
    $rtps_trans_id = null;
    $status = null;
    $certificate_language = set_value("certificate_language");
    $contact_number = (strlen($this->session->mobile)==10)?$this->session->mobile:set_value("contact_number");
    $applicant_name = set_value("applicant_name");
    $father_name = set_value("father_name");
    $mother_name = set_value("mother_name");
    $emailid = set_value("emailid");
    $dob = set_value("dob");
    $applicant_gender = set_value("applicant_gender");
    $spouse_name = set_value("spouse_name");
    $passport_no = set_value("passport_no");
    $pan = set_value("pan");
    $aadhar_no = set_value("aadhar_no");
    $district_id = set_value("district_id") ?? NULL;
    $subdivison_id = set_value("subdivison_id") ?? NULL;
    $circle_id = set_value("circle_id") ?? NULL;

    $district_id_ca = set_value("district_id_ca") ?? NULL;
    $subdivison_id_ca = set_value("subdivison_id_ca") ?? NULL;
    $circle_id_ca = set_value("circle_id_ca") ?? NULL;

    $pa_house_no = set_value("pa_house_no");
    $pa_village = set_value("pa_village");
    $pa_post_office = set_value("pa_post_office");
    $pa_pin_code = set_value("pa_pin_code");
    $pa_state = set_value("pa_state");
    $pa_country = set_value("pa_country");
    $pa_district = set_value("pa_district");
    $pa_police_station = set_value("pa_police_station");
    $pa_police_station_code = set_value("pa_police_station_code");
    $pa_subdivision = set_value("pa_subdivision");
    $pa_revenuecircle = set_value("pa_revenuecircle");
    $pa_year = set_value("pa_year");
    $pa_month = set_value("pa_month");
    $pa_mouza = set_value("pa_mouza");  

    $address_same = set_value("address_same");

    $ca_house_no = set_value("ca_house_no");
    $ca_village = set_value("ca_village");
    $ca_post_office = set_value("ca_post_office");
    $ca_pin_code = set_value("ca_pin_code");
    $ca_state = set_value("ca_state");
    $ca_country = set_value("ca_country");
    $ca_district = set_value("ca_district");
    $ca_police_station = set_value("ca_police_station");
    $ca_police_station_code = set_value("ca_police_station_code");
    $ca_subdivision = set_value("ca_subdivision");
    $ca_revenuecircle = set_value("ca_revenuecircle");
    $ca_year = set_value("ca_year");
    $ca_month = set_value("ca_month");
    $ca_mouza = set_value("ca_mouza");  

    $purpose = set_value("purpose");
    $institute_name = set_value("institute_name");
    $criminal_rec = set_value("criminal_rec");

    $uploadedFiles = $this->session->flashdata('uploaded_files');
    //$soft_doc_type = set_value("soft_doc_type");
    //$soft_doc = $uploadedFiles['soft_doc_old']??null;
    $birth_doc_type = set_value("birth_doc_type");
    $birth_doc = $uploadedFiles['birth_doc_old']??null;
    $passport_doc_type = set_value("passport_doc_type");
    $passport_doc = $uploadedFiles['passport_doc_old']??null;
    $emp_proof_type = set_value("emp_proof_type");
    $emp_doc = $uploadedFiles['emp_doc_old']??null;
    $address_doc_type = set_value("address_doc_type");
    $address_doc = $uploadedFiles['address_doc_old']??null;
    $forefathers_doc_type = set_value("forefathers_doc_type");
    $forefathers_doc = $uploadedFiles['forefathers_doc_old']??null;
    $property_doc_type = set_value("property_doc_type");
    $property_doc = $uploadedFiles['property_doc_old']??null;
    $voter_doc_type = set_value("voter_doc_type");
    $voter_doc = $uploadedFiles['voter_doc_old']??null;
    $passport_pic_type = set_value("passport_pic_type");
    $passport_pic = $uploadedFiles['passport_pic_old']??null;
    $prc_doc_type = set_value("prc_doc_type");
    $prc_doc = $uploadedFiles['prc_doc_old']??null;
    $admit_doc_type = set_value("admit_doc_type");
    $admit_doc = $uploadedFiles['admit_doc_old']??null;

    if($form_status==='QS'){
        $form_status = set_value("form_status");
        $query_asked = '';
        $queried_by = '';
        $queried_time = '';
        $query_answered = set_value("query_answered");
        //$query_doc = $uploadedFiles['query_doc_old']??null;
    }
    $status = null;
}
$mobile_verify_status = (strlen($contact_number) == 10)?1:0;
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
    .form-group {
        margin-bottom: .4rem;
    }
    label {
        margin-bottom: .1rem;
    }
</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/webcamjs/webcam.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/iservices/js/custom/digilocker.js') ?>"></script>

<script type="text/javascript">   
    $(document).ready(function () {
         $('.number_input').keypress(function (e) {    
            var charCode = (e.which) ? e.which : event.keyCode    
            if (String.fromCharCode(charCode).match(/[^0-9]/g))    
                return false;                        
        });

        /*var soft_doc = parseInt(<?= strlen($soft_doc) ? 1 : 0 ?>);
        $("#soft_doc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: soft_doc ? false : true,
        required:false,
        maxFileSize: 2000,
        allowedFileExtensions: ["pdf"]
        });
        */  
        var birth_doc = parseInt(<?= strlen($birth_doc) ? 1 : 0 ?>);
        $("#birth_doc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: birth_doc ? false : true,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
        });

        var passport_doc = parseInt(<?= strlen($passport_doc) ? 1 : 0 ?>);
        $("#passport_doc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
        });

        var emp_doc = parseInt(<?= strlen($emp_doc) ? 1 : 0 ?>);
        $("#emp_doc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
        });

        var address_doc = parseInt(<?= strlen($address_doc) ? 1 : 0 ?>);
        $("#address_doc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: address_doc ? false : true,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
        });

        var forefathers_doc = parseInt(<?= strlen($forefathers_doc) ? 1 : 0 ?>);
        $("#forefathers_doc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: forefathers_doc ? false : true,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
        });
        
        var property_doc = parseInt(<?= strlen($property_doc) ? 1 : 0 ?>);
        $("#property_doc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: property_doc ? false : true,
        required:false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
        });

        var voter_doc = parseInt(<?= strlen($voter_doc) ? 1 : 0 ?>);
        $("#voter_doc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: voter_doc ? false : true,
        required:false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
        });

        var prc_doc = parseInt(<?= strlen($prc_doc) ? 1 : 0 ?>);
        $("#prc_doc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
        });

        var admit_doc = parseInt(<?= strlen($admit_doc) ? 1 : 0 ?>);
        $("#admit_doc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required:false,
        //required: admit_doc ? false : true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
        });

        var passport_pic = parseInt(<?= strlen($passport_pic) ? 1 : 0 ?>);
        $("#passport_pic").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required:false,
        //required: passport_pic ? false : true,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg", "jpeg", "png"]
        });


        $(document).on("click", "#send_mobile_otp", function(){
            let contactNo = $("#contact_number").val();
            if (/^\d{10}$/.test(contactNo)) {
                $("#otp_no").val("");
                $("#otpModal").modal("show");
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?=base_url('spservices/minoritycertificates/otps/send_otp')?>",
                    data: {"mobile_number": contactNo},
                    beforeSend: function () {
                        $("#otp_no").attr("placeholder", "Sending OTP... Please wait");
                    },
                    success: function (res) {
                        if(res.status) {
                            $(".verify_btn").attr("id", "verify_mobile_otp");
                            $("#otp_no").attr("placeholder", "Enter your OTP");
                        } else {
                            alert(res.msg);
                        }//End of if else
                    }
                });
            } else {
                alert("Contact number is invalid. Please enter a valid number");
                $("#contact_number").val();
                $("#contact_number").focus();
                return false;
            }//End of if else
        });//End of onclick #send_mobile_otp


        
        $(document).on("click", "#verify_mobile_otp", function(){
            let contactNo = $("#contact_number").val();
            var otpNo = $("#otp_no").val();
            if (/^\d{6}$/.test(otpNo)) {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?=base_url('spservices/minoritycertificates/otps/verify_otp')?>",
                    data: {"mobile_number":contactNo, "otp": otpNo},
                    beforeSend:function(){
                        $("#otp_no").val("");
                        $("#otp_no").attr("placeholder", "Verifying OTP... Please wait");
                    },
                    success:function(res){ //alert(JSON.stringify(res));
                        if(res.status) {
                            $("#otpModal").modal("hide");
                            $("#mobile_verify_status").val(1);
                            $("#contact_number").prop("readonly", true);
                            $("#send_mobile_otp").addClass('d-none');
                            $("#verified").removeClass('d-none');
                        } else {
                            alert(res.msg);
                            $("#otp_no").attr("placeholder", "ENTER THE 6-DIGIT OTP");
                        }//End of if else
                    }
                });
            } else {
                alert("OTP is invalid. Please enter a valid otp");
                $("#otp_no").val();
                $("#otp_no").focus();
            }//End of if else
        });//End of onClick #verify_mobile_otp
                
        $(".dp").datepicker({
            format: 'yyyy-mm-dd',
            endDate: '+0d',
            autoclose: true,
            disable:true
        });
    
        
        $.getJSON("<?=$apiServer?>district_list.php", function (data) {
            let selectOption = '';
            $.each(data.ListOfDistricts, function (key, value) {
                selectOption += '<option value="' + value.DistrictName + '" data-district_id="' + value.DistrictId + '" district_id="'+ value.DistrictId+'">' + value.DistrictName + '</option>';
            });
            $('.pa_dists').append(selectOption);
        });
                
        $.getJSON("<?=$apiServer?>district_list.php", function (data) {
            let selectOption = '';
            $.each(data.ListOfDistricts, function (key, value) {
                selectOption += '<option value="' + value.DistrictName + '" data-district_id_ca="' + value.DistrictId + '" district_id="'+ value.DistrictId+'">' + value.DistrictName + '</option>';
            });
            $('.ca_dists').append(selectOption);
        });

        $(document).on("change", "#pa_district", function(){           
            let selectedVal = $(this).val();
            let district_id = $(this).find('option:selected').attr("data-district_id");
            $("#district_id").val(district_id);
            if(selectedVal.length) {
                var myObject = new Object();
                myObject.district_name = selectedVal;
                $.getJSON("<?=$apiServer?>sub_division_list.php?jsonbody="+JSON.stringify(myObject), function (data) {
                    let selectOption = '';
                    $('#pa_subdivision').empty().append('<option value="">Select a sub division</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.subdiv_name+'"data-subdivison_id="'+value.subdiv_id+'">'+value.subdiv_name+'</option>';
                    });
                    $('#pa_subdivision').append(selectOption);
                });
            }
        });

        $(document).on("change", "#ca_district", function(){           
            let selectedVal = $(this).val();
            let district_id = $(this).find('option:selected').attr("data-district_id_ca");
            $("#district_id_ca").val(district_id);
            if(selectedVal.length) {
                var myObject = new Object();
                myObject.district_name = selectedVal;
                $.getJSON("<?=$apiServer?>sub_division_list.php?jsonbody="+JSON.stringify(myObject), function (data) {
                    let selectOption = '';
                    $('#ca_subdivision').empty().append('<option value="">Select a sub division</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.subdiv_name+'"data-subdivison_id_ca="'+value.subdiv_id+'">'+value.subdiv_name+'</option>';
                    });
                    $('#ca_subdivision').append(selectOption);
                });
            }
        });
        
        $(document).on("change", "#pa_subdivision", function(){               
            let selectedVal = $(this).val();
            let subdivison_id = $(this).find('option:selected').attr("data-subdivison_id");
            $("#subdivison_id").val(subdivison_id);
            if(selectedVal.length) {//alert(selectedVal);
                var myObject = new Object();
                myObject.subdiv_name = selectedVal;//alert(JSON.stringify(myObject));
                $.getJSON("<?=$apiServer?>revenue_circle_list.php?jsonbody="+JSON.stringify(myObject), function (data) {
                    let selectOption = '';
                    $('#pa_revenuecircle').empty().append('<option value="">Select a revenue circle</option>');
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.circle_name+'"data-circle_id="'+value.circle_id+'">'+value.circle_name+'</option>';
                    });
                    $('#pa_revenuecircle').append(selectOption);
                });
            }
        });

        $(document).on("change", "#ca_subdivision", function(){           
            let selectedVal = $(this).val();
            let subdivison_id = $(this).find('option:selected').attr("data-subdivison_id_ca");
            $("#subdivison_id_ca").val(subdivison_id);
            if(selectedVal.length) {//alert(selectedVal);
                var myObject = new Object();
                myObject.subdiv_name = selectedVal;//alert(JSON.stringify(myObject));
                $.getJSON("<?=$apiServer?>revenue_circle_list.php?jsonbody="+JSON.stringify(myObject), function (data) {
                    let selectOption = '';
                    $('#ca_revenuecircle').empty().append('<option value="">Select a revenue circle</option>');
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.circle_name+'"data-circle_id_ca="'+value.circle_id+'">'+value.circle_name+'</option>';
                    });
                    $('#ca_revenuecircle').append(selectOption);
                });
            }
        });

        $(document).on("change", "#pa_revenuecircle", function(){ 
            let circle_id = $(this).find('option:selected').attr("data-circle_id");
            $("#circle_id").val(circle_id);

        });  

        $(document).on("change", "#ca_revenuecircle", function(){ 
            let circle_id = $(this).find('option:selected').attr("data-circle_id_ca");
            $("#circle_id_ca").val(circle_id);

        });   


       //police station 
       $(document).on("change", "#pa_district", function(){           
            let selectedVal = $(this).val();
            if(selectedVal.length) {
                var myObject = new Object();
                myObject.DistrictId = selectedVal;
                $.getJSON("<?=$apiServer?>ps_list.php?jsonbody="+JSON.stringify(myObject), function (data) {
                    let selectOption = '';
                    $('#pa_police_station').empty().append('<option value="">Select a police station</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.psname+'">'+value.psname+'</option>';
                    });
                    $('#pa_police_station').append(selectOption);
                });
            }
        });

       $(document).on("change", "#pa_district", function(){           
            let selectedVal = $(this).val();
            if(selectedVal.length) {
                var myObject = new Object();
                //alert(selectedVal);
                myObject.DistrictId = selectedVal;
                $.getJSON("<?=$apiServer?>ps_list.php?jsonbody="+JSON.stringify(myObject), function (data) {
                    let selectOption = '';
                    $('#pa_police_station').empty().append('<option value="">Select a police station</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.psname+'">'+value.psname+'</option>';
                    });
                    $('#pa_police_station').append(selectOption);
                });
            }
        });

        $(document).on("change", "#pa_police_station", function(){         
            let selectedVal = $(this).val();
            if(selectedVal.length) {
                var myObject = new Object();
                myObject.psname = selectedVal;
                $.getJSON("<?=$apiServer?>pscode_list.php?jsonbody="+JSON.stringify(myObject), function (data) {
                    //let selectOption = '';
                    $('#pa_police_station_code').empty();
                    //alert(data.pscode);
                    $("#pa_police_station_code").val(data.pscode);
                });
            }
        });



        $(document).on("change", "#ca_district", function(){           
            let selectedVal = $(this).val();
            if(selectedVal.length) {
                var myObject = new Object();
                myObject.DistrictId = selectedVal;
                $.getJSON("<?=$apiServer?>ps_list.php?jsonbody="+JSON.stringify(myObject), function (data) {
                    let selectOption = '';
                    $('#ca_police_station').empty().append('<option value="">Select a police station</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.psname+'">'+value.psname+'</option>';
                    });
                    $('#ca_police_station').append(selectOption);
                });
            }
        });

        $(document).on("change", "#ca_police_station", function(){           
            let selectedVal = $(this).val();
            if(selectedVal.length) {
                var myObject = new Object();
                myObject.psname = selectedVal;
                $.getJSON("<?=$apiServer?>pscode_list.php?jsonbody="+JSON.stringify(myObject), function (data) {
                    //let selectOption = '';
                    $('#ca_police_station_code').empty();
                    //alert(data.pscode);
                    $("#ca_police_station_code").val(data.pscode);
                });
            }
        });



        $(document).on("change", ".address_same", function(){                
            let checkedVal = $(this).val(); //alert(checkedVal);
            if(checkedVal === "Y") {
                $("#ca_house_no").val($("#pa_house_no").val());
                $("#ca_street").val($("#pa_street").val());
                $("#ca_village").val($("#pa_village").val());
                $("#ca_post_office").val($("#pa_post_office").val());
                $("#ca_pin_code").val($("#pa_pin_code").val());
                $("#ca_state").val($("#pa_state").val());
                $("#ca_district").val($("#pa_district").val());
                $("#ca_mouza").val($("#pa_mouza").val());

                $("#ca_circles_div").html('<select name="ca_revenuecircle" class="form-control"><option value="'+$("#pa_revenuecircle").val()+'">'+$("#pa_revenuecircle").val()+'</option></select>');
                
                $("#ca_div").html('<select name="ca_subdivision" class="form-control"><option value="'+$("#pa_subdivision").val()+'">'+$("#pa_subdivision").val()+'</option></select>');

                $("#ca_ps").html('<select name="ca_police_station" class="form-control"><option value="'+$("#pa_police_station").val()+'">'+$("#pa_police_station").val()+'</option></select>');

                //$("#ca_police_station").val($("#pa_police_station").val());

                $("#ca_police_station_code").val($("#pa_police_station_code").val());
                $("#ca_year").val($("#pa_year").val());
                $("#ca_month").val($("#pa_month").val());
            } else {
                $("#ca_house_no").val("");
                $("#ca_street").val("");
                $("#ca_village").val("");
                $("#ca_post_office").val("");
                $("#ca_pin_code").val("");
                $("#ca_state").val("");
                $("#ca_district_id").val("");
                $("#ca_district_name").val("");
                $("#ca_police_station_code").val("");
                $("#ca_mouza").val("");
                $("#ca_circle").val("");
                $("#ca_circles_div").html('<select name="ca_revenuecircle" id="ca_revenuecircle" class="form-control"><option value="">Select a circle</option></select>');

                $("#ca_div").html('<select name="ca_subdivision" id="ca_subdivision" class="form-control"><option value="">Select a subdivision</option></select>');
                
                $("#ca_ps").html('<select name="ca_police_station" class="form-control"><option value="">Select a subdivision</option></select>');

                $("#ca_police_station_code").val("");
            }//End of if else
        });//End of onChange .address_same
        
       
        
        $(document).on("click", ".frmbtn", function(){
            var clickedBtn = $(this).attr("id");//alert(clickedBtn);
            if(clickedBtn === 'FORM_SUBMIT') {
                $("#form_status").val("DRAFT");
            } else if(clickedBtn === 'QUERY_SUBMIT') {
                $("#form_status").val("QS");
            } else {
                $("#form_status").val("");
            }//End of if else
            
            Swal.fire({
                title: 'Are you sure?',
                text: "Once you submitted, you will not be able to revert this action",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    $("#myfrm").submit();
                }
            });
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/prc/Application/submit') ?>" enctype="multipart/form-data" onsubmit="$(this).find('select,radio,input').prop('disabled', false)">
            <input id="form_status" name="form_status" value="<?=$form_status?>" type="hidden" />
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="rtps_trans_id" value="<?=$rtps_trans_id?>" type="hidden" />
            <input id="form_status" name="form_status" value="<?=$form_status?>" type="hidden" />
            <input id="district_id" name="district_id" value="<?=$district_id?>" type="hidden" />
            <input id="subdivison_id" name="subdivison_id" value="<?=$subdivison_id?>" type="hidden" />
            <input id="circle_id" name="circle_id" value="<?=$circle_id?>" type="hidden" />

            <input id="district_id_ca" name="district_id_ca" value="<?=$district_id_ca?>" type="hidden" />
            <input id="subdivison_id_ca" name="subdivison_id_ca" value="<?=$subdivison_id_ca?>" type="hidden" />
            <input id="circle_id_ca" name="circle_id_ca" value="<?=$circle_id_ca?>" type="hidden" />


            <!--<input name="soft_doc_old" value="" type="hidden" />-->
            <input name="birth_doc_old" value="<?=$birth_doc?>" type="hidden" />
            <input name="passport_doc_old" value="<?=$passport_doc?>" type="hidden" />
            <input name="emp_doc_old" value="<?=$emp_doc?>" type="hidden" />
            <input name="address_doc_old" value="<?=$address_doc?>" type="hidden" />
            <input name="forefathers_doc_old" value="<?=$forefathers_doc?>" type="hidden" />
            <input name="property_doc_old" value="<?=$property_doc?>" type="hidden" />
            <input name="voter_doc_old" value="<?=$voter_doc?>" type="hidden" />
            <input name="passport_pic_old" value="<?=$passport_pic?>" type="hidden" />
            <input name="prc_doc_old" value="<?=$prc_doc?>" type="hidden" />
            <input name="admit_doc_old" value="<?=$admit_doc?>" type="hidden" />
            


            <div class="card shadow-sm">
                <div class="card-header" style="text-align: center; font-size: 18px; color: #000; font-family: georgia,serif; font-weight: bold">
                    APPLICATION FORM FOR PERMANENT RESIDENCE CERTIFICATE FOR HIGHER EDUCATION</br>
                    <font size="4px">( স্থায়ী বাসিন্দাৰ প্রমান পত্রৰ বাবে আবেদন )</font>
                </div>

                <fieldset class="border border-success">
                            <legend class="h5">Important / দৰকাৰী </legend>
                            <strong style="font-size:16px; ">Stipulated time limit for delivery/ প্ৰাপ্তিৰ‌ নিৰ্দ্দিষ্ট সময়সীমা</strong>

                            <ol style="margin-left: 24px; margin-top: 20px">
                                <li>The certificate will be delivered within 14 Days of application.</li>
                                <li>প্ৰমাণ পত্ৰ ১৪ দিনৰ ভিতৰত প্ৰদান কৰা হ'ব</li>
                            </ol>


                            <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>
                            <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>Service charge (PFC/ CSC) / সেৱা মাচুল (পি. এফ. চি./ চি. এছ. চি.) – Rs. 30 / ৩০ টকা</li>
                            <li>Printing charge (in case of any printing from PFC) / ছপা খৰচ (পি.এফ.চি. ৰ পৰা কোনো ধৰণৰ প্ৰিন্টিঙৰ ক্ষেত্ৰত) - Rs. 10 Per Page / প্ৰতি
                                পৃষ্ঠাত ১০ টকা</li>
                            <li>Scanning charge (in case documents are scanned in PFC) স্কেনিং খৰচ (যদি নথিপত্ৰসমূহ পি.এফ.চি. ত স্কেন কৰা হয়) - Rs. 5 Per page /
                                প্ৰতি পৃষ্ঠা ৫ টকা</li>
                            </ul>

                            <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                            <ul style="  margin-left: 24px; margin-top: 10px">
                                <li>1. All the * marked fields are mandatory and need to be filled up..</li>
                                <li>১. * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব</li>
                            </ul>

                    </fieldset>




                <div class="card-body" style="padding:5px">
                    <fieldset class="border border-success" style="margin-top:0px">
                        <p>If you wish to upload your enclosures/documents from digilocker, please link your digilocker account. Click on the below button to link your account.</p>
                        <p><?php $this->digilocker_api->login_btn();  ?></p>
                    </fieldset>
                    <fieldset class=" border border-success" style="margin-top:20px">
                        <legend class="h6">Language of certificate / প্রমান পত্রৰ ভাষা <span
                                class="text-danger ">*</span></legend>
                        <div class="d-flex space-x-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="certificate_language" checked id="english"
                                    value="English" <?= ($certificate_language === "English") ? 'checked' : '' ?> <?php if($form_status === "QS"){?>disabled <?php }//End of if ?>/>
                                <label class="form-check-label" for="english">English</lable>
                            </div>
                            <div class="form-check ml-3">
                                <input class="form-check-input" type="radio" name="certificate_language" id="assamese"
                                    value="Assamese" <?= ($certificate_language === "Assamese") ? 'checked' : '' ?> <?php if($form_status === "QS"){?>disabled <?php }//End of if ?>/>
                                <label class="form-check-label" for="assamese">Assamese</lable>
                            </div>
                            <div class="form-check ml-3">
                                <input class="form-check-input" type="radio" name="certificate_language" id="bengali"
                                    value="Bengali" <?= ($certificate_language === "Bengali") ? 'checked' : '' ?> <?php if($form_status === "QS"){?>disabled <?php }//End of if ?>/>
                                <label class="form-check-label" for="bengali">Bengali</lable>
                            </div>
                            <div class="form-check ml-3">
                                <input class="form-check-input" type="radio" name="certificate_language" id="bodo"
                                    value="Bodo" <?= ($certificate_language === "Bodo") ? 'checked' : '' ?> <?php if($form_status === "QS"){?>disabled <?php }//End of if ?>/>
                                <label class="form-check-label" for="bodo">Bodo</lable>
                            </div>
                            <?= form_error("certificate_language") ?>
                        </div>
                        <!-- </div> -->
                    </fieldset>


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
                    <?php }
                    if($form_status === "QS") { ?>
                        <fieldset class="border border-danger" style="margin-top:40px">
                            <legend class="h5"><?=$this->lang->line('query_details')?></legend>
                            <div class="row">
                                <div class="col-md-12" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                    <p>The following query is made by <strong><?=$queried_by?></strong> on <strong><?=$queried_time?></strong></p>
                                    <?=$query_asked?>
                                </div>
                            </div>
                        </fieldset>
                    <?php }//End of if ?>
                                
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant Details<font size="3px">( আবেদনকাৰীৰ বিৱৰণ )</font></legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Applicant’s Name <font size="2px">( আবেদনকাৰীৰ নাম ) </font> <span class="text-danger">*</span> </label>
                                <input class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255" type="text" required="true"/ <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>>
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Father's Name<font size="2px"> ( পিতৃৰ নাম )</font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="father_name" id="father_name" value="<?=$father_name?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("father_name") ?>
                            </div>
                        </div>
                        
                        <div class="row">  
                            <div class="col-md-6 form-group">
                                <label>Mother's Name <font size="2px">( মাতৃৰ নাম )</font><span class="text-danger">*</span></label>
                                <input class="form-control" name="mother_name" id="mother_name" value="<?=$mother_name?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("mother_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label for="contact_number">Mobile Number<font size="2px">
                                ( মবাইল নম্বৰ  )</font><span class="text-danger">*</span>
                                    <small class="text-info pull-right">(Please verify the contact number using OTP)</small> 
                                </label>
                                <div class="input-group">
                                    <input class="form-control" name="contact_number" id="contact_number" maxlength="10" value="<?=$contact_number?>" <?=($mobile_verify_status == 1)?'readonly':''?> type="text" />
                                    <div class="input-group-append">
                                        <a href="javascript:void(0)" class="btn btn-outline-danger <?=($mobile_verify_status == 1)?'d-none':''?>" id="send_mobile_otp">Verify</a>
                                        <a href="javascript:void(0)" class="btn btn-outline-success <?=($mobile_verify_status == 1)?'':'d-none'?>" id="verified"><i class="fa fa-check"></i></a>
                                    </div>
                                </div>                                
                            </div>
                            <?= form_error("contact_number") ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Email <font size="2px">(ইমেইল) </font></label>
                                <input class="form-control" name="emailid" id="emailid" value="<?=$emailid?>" maxlength="100" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("emailid") ?>
                            </div>  
                            <div class="col-md-6 form-group">
                                <label>Date of Birth <font size="2px">( জন্মৰ তাৰিখ )</font><span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="dob" id="dob" value="<?=$dob?>" maxlength="10" autocomplete="off" type="text" readonly<?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("dob") ?>
                            </div>

                        </div>  
                        
                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Gender <font size="2px">(লিংগ)</font><span class="text-danger">*</span> </label>
                                <select name="applicant_gender" id="applicant_gender"class="form-control" <?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="" >Please Select</option>
                                    <option value="Male" <?=($applicant_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($applicant_gender === "Female")?'selected':''?>>Female</option>
                                    <option value="Others" <?=($applicant_gender === "Others")?'selected':''?>>Others</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>                        
                            <div class="col-md-6 form-group">
                                <label>Spouse Name <font size="2px">( পতি/পত্নীৰ  নাম )</font><span class="text-danger"></span> </label>
                                <input class="form-control" name="spouse_name" id="spouse_name" value="<?=$spouse_name?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                 <?= form_error("spouse_name") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Passport Number <font size="2px">(পাছপৰ্ট নম্বৰ)</font><span class="text-danger"></span> </label>
                                <input class="form-control" name="passport_no" id="passport_no" value="<?=$passport_no?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                 <?= form_error("passport_no") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>PAN Number<font size="2px"> (পান নম্বৰ)</font>
                            </label><span class="text-danger"></span> </label>
                                <input class="form-control" name="pan" id="pan" value="<?=$pan?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                 <?= form_error("pan") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Aadhaar Number <font size="2px"> (আধাৰ নম্বৰ)</font><span class="text-danger"></span> </label>
                                <input class="form-control number_input" name="aadhar_no" id="aadhar_no" value="<?=$aadhar_no?>" maxlength="12" minlength="12" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                 <?= form_error("aadhar_no") ?>
                            </div>
                        </div>
                    </fieldset>                    
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address<font size="3px">( স্হায়ী ঠিকনা ) </font></legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>House no./Flat no.<font size="2px">(ঘৰৰ  নং)</font></label>
                                <input class="form-control" name="pa_house_no" id="pa_house_no" value="<?=$pa_house_no?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("pa_house_no") ?>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Village/Town<font size="2px">( গাওঁ/নগৰ ) </font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_village" id="pa_village" value="<?=$pa_village?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("pa_village") ?>
                            </div>
                        </div> 
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Mouza<font size="2px">( মৌজা ) </font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_mouza" id="pa_mouza" value="<?=$pa_mouza?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("pa_mouza") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Post Office<font size="2px">(ডাকঘৰ)</font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_post_office" id="pa_post_office" value="<?=$pa_post_office?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("pa_post_office") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Pin Code<font size="2px">(পিন নং)</font><span class="text-danger">*</span> </label>
                                <input class="form-control number_input" name="pa_pin_code" id="pa_pin_code" value="<?=$pa_pin_code?>" minlength="6" maxlength="6" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("pa_pin_code") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Country<font size="2px">( দেশ )</font><span class="text-danger">*</span> </label>
                                <select name="pa_country" id="pa_country" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="India">India</option>
                                </select>
                                <?= form_error("pa_country") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>State<font size="2px">( ৰাজ্য )</font><span class="text-danger">*</span> </label>
                                <select name="pa_state" id="pa_state" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="Assam">Assam</option>
                                </select>
                                <?= form_error("pa_state") ?>
                            </div>
                               <div class="col-md-6 form-group">
                                <label>District<font size="2px">( জিলা )</font><span class="text-danger">*</span> </label>
                                <select name="pa_district" id="pa_district" class="form-control pa_dists" <?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="<?=$pa_district?>"><?=strlen($pa_district)?$pa_district:'Select'?></option>
                                </select>
                                <?= form_error("pa_district") ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Sub Division<font size="2px">( মহকুমা ) </font><span class="text-danger">*</span> </label>
                                <div id="pa_div">
                                <select name="pa_subdivision" id="pa_subdivision" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
            <option value="<?=$pa_subdivision?>"><?=strlen($pa_subdivision)?$pa_subdivision:'Select'?></option>
                                </select>
                                </div>
                                <?= form_error("pa_subdivision") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Circle<font size="2px">( ৰাজহ চক্র )</font><span class="text-danger">*</span> </label>
                                <div id="pa_circles_div">
                                    <select name="pa_revenuecircle" id="pa_revenuecircle" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
            <option value="<?=$pa_revenuecircle?>"><?=strlen($pa_revenuecircle)?$pa_revenuecircle:'Select'?></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <?= form_error("pa_revenuecircle") ?>
                            </div>
                        </div>
                        <div class="row">                                
                            <div class="col-md-6 form-group">


                                <label>Police Station<font size="2px">(থানা)</font><span class="text-danger">*</span> </label>
                                <select name="pa_police_station" id="pa_police_station" class="form-control" <?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
            <option value="<?=$pa_police_station?>"><?=strlen($pa_police_station)?$pa_police_station:'Select'?></option>
                                        <option value=""></option>
                                    </select>

                                <?= form_error("pa_police_station") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Police Station Code<font size="2px">(থানা ক'ড)</font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_police_station_code" id="pa_police_station_code" value="<?=$pa_police_station_code?>" maxlength="100" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("pa_police_station_code") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                 <label>Duration of stay<font size="2px">( থকাৰ সময়সীমা )</font><span class="text-danger">*</span> </label>
                            </div>
                        </div>    
                        <div class="row">                                
                            <div class="col-md-3 form-group">
                                <label>Year<font size="2px">( বছৰ )</font><span class="text-danger">*</span> </label>
                                <input class="form-control number_input" name="pa_year" id="pa_year" value="<?=$pa_year?>" maxlength="2" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("pa_year") ?>
                            </div>
                            <div class="col-md-3 form-group">
                                <label> Month <font size="2px">(মাহ)</font> <span class="text-danger">*</span> </label>
                                <input class="form-control number_input" name="pa_month" id="pa_month" value="<?=$pa_month?>" maxlength="2" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("pa_month") ?>
                            </div>
                        </div>
                    </fieldset>             
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Current Address <font size="3px"> ( বৰ্তমানৰ ঠিকনা )</font></legend>
                        <div class="row mt-2 mb-4">
                            <div class="col-md-6">
                                <label for="address_same">Same as Permanent Address ?<span class="text-danger">*</span> </label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input address_same" type="radio" name="address_same" id="dcsYes" value="Y" <?=($address_same === 'Y')?'checked':''?> <?php if($form_status === "QS"){?>disabled <?php }//End of if ?>/>
                                    <label class="form-check-label" for="dcsYes">YES</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input address_same" type="radio" name="address_same" id="dcsNo" value="N" <?=($address_same === 'N')?'checked':''?> <?php if($form_status === "QS"){?>disabled <?php }//End of if ?>/>
                                    <label class="form-check-label" for="dcsNo">NO</label>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-6 form-group">
                                <label>House no. /Flat no.<font size="2px">(ঘৰৰ  নং)</font></label>
                                <input class="form-control" name="ca_house_no" id="ca_house_no" value="<?=$ca_house_no?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("ca_house_no") ?>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Village/Town<font size="2px">( গাওঁ/নগৰ ) </font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_village" id="ca_village" value="<?=$ca_village?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("ca_village") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Mouza<font size="2px">( মৌজা ) </font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_mouza" id="ca_mouza" value="<?=$ca_mouza?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("ca_mouza") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Post Office<font size="2px">(ডাকঘৰ)</font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_post_office" id="ca_post_office" value="<?=$ca_post_office?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("ca_post_office") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Pin Code<font size="2px">(পিন নং)</font><span class="text-danger">*</span> </label>
                                <input class="form-control number_input" name="ca_pin_code" id="ca_pin_code" value="<?=$ca_pin_code?>" minlength="6" maxlength="6" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("ca_pin_code") ?>
                            </div>
                            
                            <div class="col-md-6 form-group">
                                <label>Country<font size="2px">( দেশ )</font><span class="text-danger">*</span> </label>
                                <select name="ca_country" id="ca_country" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="India">India</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>State<font size="2px">( ৰাজ্য )</font><span class="text-danger">*</span> </label>
                                <select name="ca_state" id="ca_state" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="Assam">Assam</option>
                                </select>
                            </div>
                              <div class="col-md-6 form-group">
                                <label>District<font size="2px">( জিলা )</font><span class="text-danger">*</span> </label>
                                <select name="ca_district" id="ca_district" class="form-control ca_dists"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="<?=$ca_district?>"><?=strlen($ca_district)?$ca_district:'Select'?></option>
                                </select>
                                <?= form_error("ca_district") ?>
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-md-6 form-group">
                                <label>Sub Division<font size="2px">( মহকুমা )</font><span class="text-danger">*</span> </label>
                                <div id="ca_div">
                                <select name="ca_subdivision" id="ca_subdivision" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                    <option value="<?=$ca_subdivision?>"><?=strlen($ca_subdivision)?$ca_subdivision:'Select'?></option>
                                </select>
                                </div>
                                <?= form_error("ca_subdivision") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Circle<font size="2px">( ৰাজহ চক্র )</font><span class="text-danger">*</span> </label>
                                <div id="ca_circles_div">
                                    <select name="ca_revenuecircle" id="ca_revenuecircle" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
            <option value="<?=$ca_revenuecircle?>"><?=strlen($ca_revenuecircle)?$ca_revenuecircle:'Select'?></option>
                                    </select>
                                </div>
                                <?= form_error("ca_revenuecircle") ?>
                            </div>
                        </div>
                        <div class="row">                                
                            <div class="col-md-6 form-group">
                                <label>Police Station<font size="2px">(থানা)</font><span class="text-danger">*</span> </label>
                                <div id="ca_ps">
                                <select name="ca_police_station" id="ca_police_station" class="form-control" <?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
            <option value="<?=$ca_police_station?>"><?=strlen($ca_police_station)?$ca_police_station:'Select'?></option>
                                        <option value=""></option>
                                    </select>
                                </div>
                                <?= form_error("ca_police_station") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Police Station Code<font size="2px">(থানা ক'ড)</font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_police_station_code" id="ca_police_station_code" value="<?=$ca_police_station_code?>" maxlength="100" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("ca_police_station_code") ?>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-md-3 form-group">
                                 <label>Duration of stay<font size="2px">( থকাৰ সময়সীমা )</font><span class="text-danger">*</span> </label>
                            </div>
                        </div> 
                        <div class="row">                                
                            <div class="col-md-3 form-group">
                                <label>Year<font size="2px">( বছৰ )</font><span class="text-danger">*</span> </label>
                                <input class="form-control number_input" name="ca_year" id="ca_year" value="<?=$ca_year?>" maxlength="2" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("ca_year") ?>
                            </div>
                            <div class="col-md-3 form-group">
                                <label> Month <font size="2px">( মাহ )</font> <span class="text-danger">*</span> </label>
                                <input class="form-control number_input" name="ca_month" id="ca_month" value="<?=$pa_month?>" maxlength="2" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("ca_month") ?>
                            </div>
                        </div>
                    </fieldset>


                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5"> PRC Related Information <font size="2px">( পি আৰ চি সম্পৰ্কীয় তথ্য )</font> </legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Reason for Application for PRC <font size="2px">(পিআৰচি আবেদনৰ কাৰণ)</font><span class="text-danger">*</span> </label>
                                </br>
                                <select name="purpose" id="purpose" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="Higher education">HIGHER EDUCATION</option>
                                </select>
                            </div> 
                            
                            <div class="col-md-6 form-group">
                                <label>Name of the Institution where Studied Last<font size="2px">(শেহতীয়া শিক্ষাগ্ৰহন কৰা অনুস্হানৰ নাম)</font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="institute_name" id="institute_name" value="<?=$institute_name?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("institute_name") ?>
                            </div>
                        </div>
                        
                        <div class="row">  
                            <div class="col-md-6 form-group">
                                <label>Do you have any criminal record or criminal proceeeding of you or your family in any part of the country?<font size="2px">( দেশৰ কোনো অংশত আপোনাৰ বা আপোনাৰ পৰিয়ালৰ কোনো অপৰাধমূলক অভিলেখ বা অপৰাধমূলক কাৰ্য্যবিধি আছেনে?)</font><span class="text-danger">*</span> </label>
                                <select name="criminal_rec" id="criminal_rec" class="form-control" <?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="">Please Select</option>
                                    <option value="Yes" <?=($criminal_rec === "Yes")?'selected':''?>>Yes</option>
                                    <option value="No" <?=($criminal_rec === "No")?'selected':''?>>No</option>
                                </select>
                                <?= form_error("criminal_rec") ?>
                            </div>
                        </div>
                    </fieldset> 

                            
                                                            
                    <fieldset class="border border-success" style="margin-top:10px">
                        <legend class="h5"><?=$this->lang->line('attach_enclosure')?> </legend>
                        <div class="row mt-0">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td colspan="3" style="color:#fd7e14; text-align: center; font-size: 14px">
                                                List of Mandatory documents, Document type allowed is pdf of maximum size 1MB;
                                                For Passport size photo only jpg, jpeg, and png of maximum 1MB is allowed
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Type of Enclosure</th>
                                            <th style="width:20%">Enclosure Document</th>
                                            <th style="width:20%">File/Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--
                                        <?php if($this->slug == 'test') { ?>
                                        <tr>
                                            <td>Upload hard copy of the Application Form <font>( ইউজাৰ ফৰ্মখন সংলগ্ন কৰা)</font><span class="text-danger">*</span></td>
                                            <td>
                                                <select name="soft_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Soft Copy" <?=$soft_doc_type==='Soft Copy'?'selected':''?>>Upload soft copy of the User Form</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input id="soft_doc" name="soft_doc" type="file" />
                                                </div>
                                                <?php if(strlen($soft_doc)){ ?>
                                                    <a href="<?=base_url($soft_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <?php }//End of if ?>
                                        --> 
                                        <tr>
                                            <td>Copy of the Birth Certificate issued by competent authority<font>( জন্মৰ প্রমান পত্র  )</font><span class="text-danger">*</span></td>
                                            <td>
                                                <select name="birth_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Birth Certificate"<?=$birth_doc_type==='Birth Certificate'?'selected':''?>>Copy of the Birth Certificate issued by competent authority</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input id="birth_doc" name="birth_doc" type="file" />
                                                </div>
                                                <?php if(strlen($birth_doc)){ ?>
                                                    <a href="<?=base_url($birth_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                 <input class="birth_doc" type="hidden" name="birth_doc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('birth_doc'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Upload One Address proof documents of Self or Parent’s<font>নিজৰ বা অভিভাৱকৰ এটা ঠিকনা প্ৰমাণ নথিপত্ৰ আপলোড কৰক</font><span class="text-danger">*</span></td>
                                            <td>
                                                <select name="address_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Aadhaar Card" <?=$address_doc_type==='Aadhaar Card'?'selected':''?> >Aadhaar Card</option>
                                                    <option value="Voter Card"<?=$address_doc_type==='Voter Card'?'selected':''?>>Voter Card</option>
                                                    <option value="Ration Card" <?=$address_doc_type==='Ration Card'?'selected':''?>>Ration Card</option>
                                                    <option value="Electricity Bill"<?=$address_doc_type==='Electricity Bill'?'selected':''?>>Electricity Bill</option>
                                                    <option value="LPG Bill"<?=$address_doc_type==='LPG Bill'?'selected':''?>>LPG Bill</option>
                                                    <option value="Passport" <?=$address_doc_type==='Passport'?'selected':''?>>Passport</option>
                                                    <option value="Bank Passbook" <?=$address_doc_type==='Bank Passbook'?'selected':''?>>Bank Passbook</option>
                                                    <option value="Driving License" <?=$address_doc_type==='Driving License'?'selected':''?>>Driving License</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input id="address_doc" name="address_doc" type="file" />
                                                     <?php if(strlen($address_doc)){ ?>
                                                    <a href="<?=base_url($address_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                 <input class="address_doc" type="hidden" name="address_doc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('address_doc'); ?>
                                                
                                                </div>
                                            </td>
                                        </tr>
                                        
                                         <tr>
                                            <td>Documents related to parents and forefathers having continuously resided in Assam for a minimum period of 50 years or Documents related to guardian having continuously resided in Assam for a minimum period of 20 years<font>( পিতৃ-মাতৃ অথবা পূর্ব-পুৰুষৰ অসমত একেৰাহে নূন্যতম ৫০ বছৰ স্হায়ীভাবে বসবাস কৰাৰ নথি নাইবা <br>অভিভাৱকৰ নূন্যতম ২০ বছৰ স্হায়ীভাবে একেৰাহে  বসবাস কৰাৰ নথি পত্র ।  )</font><span class="text-danger">*</span></td>
                                            <td>
                                                <select name="forefathers_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Documents related to parents and forefathers having continuously resided in Assam for a minimum period of 50 years" <?=$forefathers_doc_type==='Documents related to parents and forefathers having continuously resided in Assam for a minimum period of 50 years'?'selected':''?>>Documents related to parents and forefathers having continuously resided in Assam for a minimum period of 50 years</option>
                                                    <option value="Documents related to guardian having continuously resided in Assam for a minimum period of 20 years"<?=$forefathers_doc_type==='Documents related to guardian having continuously resided in Assam for a minimum period of 20 years'?'selected':''?>>Documents related to guardian having continuously resided in Assam for a minimum period of 20 years</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input id="forefathers_doc" name="forefathers_doc" type="file" />
                                                     <?php if(strlen($forefathers_doc)){ ?>
                                                    <a href="<?=base_url($forefathers_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                 <input class="forefathers_doc" type="hidden" name="forefathers_doc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('forefathers_doc'); ?>
                                                
                                                </div>
                                              
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Records of Immovable Property, if any, with up-to-date Land Revenue Paid receipt.<font>( যিকনো স্হাৱৰ সম্পত্তিৰ ৰেকর্ড বা নতুন মাটিৰ খাজানাৰ ৰচিদ )</font><span class="text-danger">*</span></td>
                                            <td>
                                                <select name="property_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Records of Immovable Property, if any, with up-to-date Land Revenue Paid receipt."<?=$property_doc_type==='Records of Immovable Property, if any, with up-to-date Land Revenue Paid receipt.'?'selected':''?>>Records of Immovable Property, if any, with up-to-date Land Revenue Paid receipt.</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input id="property_doc" name="property_doc" type="file" />
                                                     <?php if(strlen($property_doc)){ ?>
                                                    <a href="<?=base_url($property_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="property_doc" type="hidden" name="property_doc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('property_doc'); ?>
                                                
                                                </div>

                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Certified copy of the voters list to check the linkage <font>( ভোটৰ তালিকা  )</font><span class="text-danger">*</span></td>
                                            <td>
                                                <select name="voter_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Voter doc" <?=$voter_doc_type==='Voter doc'?'selected':''?>>Certified copy of the voters list to check the linkage</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input id="voter_doc" name="voter_doc" type="file" />
                                                     <?php if(strlen($voter_doc)){ ?>
                                                    <a href="<?=base_url($voter_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="voter_doc" type="hidden" name="voter_doc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('voter_doc'); ?>
                                                
                                                </div>
                                               
                                            </td>
                                        </tr>
                                       
                                       <tr>
                                            <td>Copy of HSLC Certificate/Admit Card<font>( হাইস্কুল শিক্ষান্ত পৰীক্ষাৰ প্রমান পত্র নাইবা প্রবেশ পত্রৰ ফটোকপি )</font><span class="text-danger">*</span></td>
                                            <td>
                                                <select name="admit_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Admit Card" <?=$admit_doc_type==='Admit Card'?'selected':''?> >Admit Card</option>
                                                    <option value="HSLC Certificate"<?=$admit_doc_type==='HSLC Certificate'?'selected':''?>>Copy of HSLC Certificate</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input id="admit_doc" name="admit_doc" type="file" />
                                                     <?php if(strlen($admit_doc)){ ?>
                                                    <a href="<?=base_url($admit_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="admit_doc" type="hidden" name="admit_doc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('admit_doc'); ?>
                                                
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Passport size photograph<font> (পাছপৰ্ট ফটো)</font><span class="text-danger">*</span></td>
                                            <td>
                                                <select name="passport_pic_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Recent Photo" <?=$passport_pic_type==='Recent Photo'?'selected':''?>>Recent photo</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input id="passport_pic" name="passport_pic" type="file" />
                                                     <?php if(strlen($passport_pic)){ ?>
                                                    <a href="<?=base_url($passport_pic)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="color:#fd7e14; text-align: center; font-size: 14px">
                                                List of Supporting Documents, Document type allowed is pdf of maximum size 1MB;
                                                For Passport size photo only jpg, jpeg, and png of maximum 200KB is allowed
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Type of Enclosure</th>
                                            <th style="width:20%">Enclosure Document</th>
                                            <th style="width:20%">File/Reference</th>
                                        </tr>

                                        <tr>
                                            <td>Copy of Indian Passport or Certified copy of the NRC 1951<font>( ভাৰতীয় পাচপোর্ট নাইবা ৰাষ্ট্রীয় নাগৰিক পঞ্জী ১৯৫১ ৰ প্রমান পত্র  )</font><span class="text-danger"></span></td>
                                            <td>
                                                <select name="passport_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Passport"<?=$passport_doc_type==='Passport'?'selected':''?>>Copy of Indian Passport</option>
                                                     <option value="NRC"<?=$passport_doc_type==='NRC'?'selected':''?>>Certified copy of the NRC 1951</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input id="passport_doc" name="passport_doc" type="file" />
                                                </div>
                                               <?php if(strlen($passport_doc)){ ?>
                                                    <a href="<?=base_url($passport_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                 <input class="passport_doc" type="hidden" name="passport_doc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('passport_doc'); ?>

                                                
                                            </td>
                                        </tr>


                                        <tr>
                                            <td>Employment Certificate issued by the employer showing joining in present place of posting, if any<font>( বর্তমান কার্যনির্বাস কৰি থকা কর্মক্ষেত্ৰৰ নিয়োগ কর্তাৰ পৰা নিয়োগ সম্পর্কীয় প্ৰমান পত্র  (যদি প্রযোজ্য))</font><span class="text-danger"></span></td>
                                            <td>
                                                <select name="emp_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Employment Proof" <?=$emp_proof_type==='Employment Proof'?'selected':''?>>Employment Certificate issued by the employer showing joining in present place of posting</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input id="emp_doc" name="emp_doc" type="file" />
                                                </div>
                                                <?php if(strlen($emp_doc)){ ?>
                                                    <a href="<?=base_url($emp_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                 <input class="emp_doc" type="hidden" name="emp_doc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('emp_doc'); ?>
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Copy of PRC of any member of family of the Applicant stating relationship, if any <font>( আবেদনকাৰীৰ লগত সম্পর্ক থকা পৰিয়ালৰ যিকনো সদস্যৰ স্হানীয় বাসিন্দাৰ প্রমান পত্র(যদি প্রযোজ্য) )</font><span class="text-danger"></span></td>
                                            <td>
                                                <select name="prc_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Copy of PRC of any member of family of the Applicant stating relationship, if any" <?=$prc_doc_type==='Copy of PRC of any member of family of the Applicant stating relationship, if any'?'selected':''?>>Copy of PRC of any member of family of the Applicant stating relationship, if any</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input id="prc_doc" name="prc_doc" type="file" />
                                                     <?php if(strlen($prc_doc)){ ?>
                                                    <a href="<?=base_url($prc_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                 <input class="prc_doc" type="hidden" name="prc_doc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('prc_doc'); ?>
                                                </div>
                                            </td>
                                        </tr>



                                        <!-- commenting for now
                                        <?php if($form_status === "QS") { ?>
                                            <tr>
                                                <td><label>Please upload documents here(if any)</label></td>
                                                <td>
                                                    <select class="form-control">
                                                        <option value=""><?=$this->lang->line('query_related_file')?></option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input id="query_doc" name="query_doc" type="file" />
                                                    
                                                    <?php if(strlen($query_doc)){ ?>
                                                        <a href="<?=base_url($query_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span> 
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if ?>
                                                </td>                                    
                                            </tr>                                        
                                            <tr>
                                                <td colspan="3">
                                                    <label>Please Enter your Remarks(if any) </label>
                                                    <textarea class="form-control" name="query_answered"><?=$query_answered?></textarea>
                                                    <?= form_error("query_answered") ?>
                                                </div>
                                            </tr>
                                        <?php }//End of if ?>
                                        -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset> 
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                     <?php if($form_status === 'QS') { ?>
                        <button class="btn btn-success frmbtn" id="QUERY_SUBMIT" type="button">
                            <i class="fa fa-angle-double-right"></i>REPLY & NEXT</button>
                    <?php } else { ?>
                        <button class="btn btn-danger" type="reset">
                            <i class="fa fa-refresh"></i> RESET</button>
                        <button class="btn btn-primary frmbtn" id="FORM_SUBMIT" type="button">
                            <i class="fa fa-angle-double-right"></i>SAVE & NEXT</button>
                    <?php }//End of if ?>        
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>


<div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="otpModalLabel">
    <div class="modal-dialog modal-sm" role="document" style="margin:20% auto">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px; font-weight: bold !important; font-size: 28px !important; text-align: center !important; display: block !important">
                <?=$this->lang->line('otp_verification')?>
            </div>
            <div class="modal-body print-content" id="otpview" style="padding: 5px 15px;">
                <div class="row">
                    <div class="col-md-12">
                        <input id="otp_no" class="form-control text-center" value="" maxlength="6" autocomplete="off" type="text" />
                    </div>
                </div> <!-- End of .row -->
            </div><!--End of .modal-body-->
            <div class="modal-footer" style="display: block !important; text-align: center !important">
                <button type="button" id="verify_btn" class="btn btn-success verify_btn">
                    VERIFY
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    CANCEL
                </button>
            </div><!--End of .modal-footer-->
        </div>
    </div>
</div><!--End of #otpModal-->

