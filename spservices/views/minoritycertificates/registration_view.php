<?php
$districts = $this->districts_model->get_rows(array("state_id"=>1));
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $aadhaar_consent_status = $dbrow->form_data->aadhaar_consent_status;
    $mobile_verify_status = $dbrow->form_data->mobile_verify_status;
        
    $applicant_name = $dbrow->form_data->applicant_name;
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $mobile_number = $dbrow->form_data->mobile_number;
    $email_id = $dbrow->form_data->email_id;
    $dob = $dbrow->form_data->dob;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $community = $dbrow->form_data->community;

    $pa_house_no = $dbrow->form_data->pa_house_no;
    $pa_street = $dbrow->form_data->pa_street;
    $pa_village = $dbrow->form_data->pa_village;
    $pa_post_office = $dbrow->form_data->pa_post_office;
    $pa_pin_code = $dbrow->form_data->pa_pin_code;
    $pa_state = $dbrow->form_data->pa_state;
    $pa_district_id = $dbrow->form_data->pa_district_id;
    $pa_district_name = $dbrow->form_data->pa_district_name;
    $pa_circle = $dbrow->form_data->pa_circle;
    $pa_police_station = $dbrow->form_data->pa_police_station;

    $address_same = $dbrow->form_data->address_same??'NO';
    $ca_house_no = $dbrow->form_data->ca_house_no;
    $ca_street = $dbrow->form_data->ca_street;
    $ca_village = $dbrow->form_data->ca_village;
    $ca_post_office = $dbrow->form_data->ca_post_office;
    $ca_pin_code = $dbrow->form_data->ca_pin_code;
    $ca_state = $dbrow->form_data->ca_state;
    $ca_district_id = $dbrow->form_data->ca_district_id;
    $ca_district_name = $dbrow->form_data->ca_district_name;
    $ca_circle = $dbrow->form_data->ca_circle;
    $ca_police_station = $dbrow->form_data->ca_police_station;

    $id_proof_type = $dbrow->form_data->id_proof_type;
    $id_proof = $dbrow->form_data->id_proof;
    $address_proof_type = $dbrow->form_data->address_proof_type;
    $address_proof = $dbrow->form_data->address_proof;
    $age_proof_type = $dbrow->form_data->age_proof_type;
    $age_proof = $dbrow->form_data->age_proof;
    $passport_photo_type = $dbrow->form_data->passport_photo_type;
    $passport_photo = $dbrow->form_data->passport_photo;
    $query_doc = $dbrow->form_data->query_doc??'';
    $query_asked = $dbrow->query_asked??'This is the query asked by departmental user';
    $queried_by = $dbrow->execution_data[1]->task_details->user_detail->user_name??'Departmental user';
    $qTime = $dbrow->execution_data[1]->task_details->received_time??$dbrow->form_data->created_at;
    $queried_time = date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($qTime)));
    $query_answered = $dbrow->form_data->query_answered??'';
    $status = $dbrow->service_data->appl_status??'';
} else {
    $title = "New Registration";
    $obj_id = null;
    $aadhaar_consent_status = set_value("aadhaar_consent_status");
    $mobile_verify_status = set_value("mobile_verify_status");
        
    $applicant_name = set_value("applicant_name");
    $father_name = set_value("father_name");
    $mother_name = set_value("mother_name");
    $mobile_number = (strlen($this->session->mobile)==10)?$this->session->mobile:set_value("mobile_number");
    $email_id = set_value("email_id");
    $dob = set_value("dob");
    $applicant_gender = set_value("applicant_gender");
    $community = set_value("community");

    $pa_house_no = set_value("pa_house_no");
    $pa_street = set_value("pa_street");
    $pa_village = set_value("pa_village");
    $pa_post_office = set_value("pa_post_office");
    $pa_pin_code = set_value("pa_pin_code");
    $pa_state = set_value("pa_state");
    $pa_district_id = set_value("pa_district_id");
    $pa_district_name = set_value("pa_district_name");
    $pa_circle = set_value("pa_circle");
    $pa_police_station = set_value("pa_police_station");

    $address_same = set_value("address_same");
    $ca_house_no = set_value("ca_house_no");
    $ca_street = set_value("ca_street");
    $ca_village = set_value("ca_village");
    $ca_post_office = set_value("ca_post_office");
    $ca_pin_code = set_value("ca_pin_code");
    $ca_state = set_value("ca_state");
    $ca_district_id = set_value("ca_district_id");
    $ca_district_name = set_value("ca_district_name");
    $ca_circle = set_value("ca_circle");
    $ca_police_station = set_value("ca_police_station");

    $uploadedFiles = $this->session->flashdata('uploaded_files');
    $id_proof_type = set_value("id_proof_type");
    $id_proof = $uploadedFiles['id_proof_old']??null;
    $address_proof_type = set_value("address_proof_type");
    $address_proof = $uploadedFiles['address_proof_old']??null;
    $age_proof_type = set_value("age_proof_type");
    $age_proof = $uploadedFiles['age_proof_old']??null;
    $passport_photo_type = set_value("passport_photo_type");
    $passport_photo = $uploadedFiles['passport_photo_old']??null;
    $query_asked = '';
    $queried_by = '';
    $queried_time = '';
    $query_answered = set_value("query_answered");
    $query_doc = $uploadedFiles['query_doc_old']??null;
    $status = null;
}//End of if else
$mobile_verify_status = (strlen($mobile_number) == 10)?1:0;
$loggedUserRoleSlug = $this->session->role->slug??'';
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
<script type="text/javascript">   
    $(document).ready(function () {
        var aadhaar_request_id;
        /*
        if(parseInt($("#aadhaar_consent_status").val()) != 1) {
            $("#consentModal").modal("show");
        }//End of if
        
        $('#consentModal').on('hidden.bs.modal', function () {
            if(parseInt($("#aadhaar_consent_status").val()) != 1) {
                $("#consentModal").modal("show");
            }//End of if
        });
        
        $(document).on("click", "#i_agree_consent", function(){
            $("#aadhaar_consent_status").val(1);
            $("#consentModal").modal("hide");
            getNsave();
        });
        */
        
        $(document).on("click", "#open_camera", function(){
            $("#live_photo_div").show();
            Webcam.set({
                width: 320,
                height: 240,
                image_format: 'jpeg',
                jpeg_quality: 90
            });
            Webcam.attach('#my_camera');
            $("#open_camera").hide();
        });       
        
        $(document).on("click", "#capture_photo", function(){
            Webcam.snap(function (data_uri) {//alert(data_uri);
                $("#captured_photo").attr("src", data_uri);
                $("#passport_photo_data").val(data_uri);                
            });
        });
        
        $(document).on("click", "#send_mobile_otp", function(){
            let contactNo = $("#mobile_number").val();
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
                $("#mobile_number").val();
                $("#mobile_number").focus();
                return false;
            }//End of if else
        });//End of onclick #send_mobile_otp
        
        $(document).on("click", "#verify_mobile_otp", function(){
            let contactNo = $("#mobile_number").val();
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
                            $("#mobile_number").prop("readonly", true);
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
            format: 'dd-mm-yyyy',
            endDate: '+0d',
            autoclose: true
        });
        
        var getAge = function(db) {            
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/minoritycertificates/registration/get_age')?>",
                data: {"dob":db},
                beforeSend:function(){
                    $("#age").val("Calculating...");
                },
                success:function(res){
                    $("#age").val(res);
                }
            });
        };
        
        var date_of_birth = '<?=$dob?>';
        if(date_of_birth.length == 10) {
            getAge(date_of_birth);
        }//End of if
        
        $(document).on("change", "#dob", function(){             
            var dd = $(this).val(); //alert(dd);
            var dateAr = dd.split('-');
            var dob = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];
            getAge(dd);
        });
        
        $(document).on("change", "#pa_district_id", function(){
            let district_id = $(this).val();
            $("#pa_district_name").val($(this).find("option:selected").text());
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/minoritycertificates/registration/get_circles')?>",
                data: {"input_name":"pa_circle", "fld_name": "district_id", "fld_value":district_id},
                beforeSend:function(){
                    $("#pa_circles_div").html("Loading");
                },
                success:function(res){
                    $("#pa_circles_div").html(res);
                }
            });
        });
        
        $(document).on("change", "#ca_district_id", function(){
            let district_id = $(this).val();
            $("#ca_district_name").val($(this).find("option:selected").text());
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/minoritycertificates/registration/get_circles')?>",
                data: {"input_name":"ca_circle", "fld_name": "district_id", "fld_value":district_id},
                beforeSend:function(){
                    $("#ca_circles_div").html("Loading");
                },
                success:function(res){
                    $("#ca_circles_div").html(res);
                }
            });
        });
        
        $(document).on("change", ".address_same", function(){                
            let checkedVal = $(this).val(); //alert(checkedVal);
            if(checkedVal === "YES") {
                $("#ca_house_no").val($("#pa_house_no").val());
                $("#ca_street").val($("#pa_street").val());
                $("#ca_village").val($("#pa_village").val());
                $("#ca_post_office").val($("#pa_post_office").val());
                $("#ca_pin_code").val($("#pa_pin_code").val());
                $("#ca_state").val($("#pa_state").val());
                $("#ca_district_id").val($("#pa_district_id").val());
                $("#ca_district_name").val($("#pa_district_name").val());
                $("#ca_circles_div").html('<select name="ca_circle" class="form-control"><option value="'+$("#pa_circle").val()+'">'+$("#pa_circle").val()+'</option></select>');
                $("#ca_police_station").val($("#pa_police_station").val());
            } else {
                $("#ca_house_no").val("");
                $("#ca_street").val("");
                $("#ca_village").val("");
                $("#ca_post_office").val("");
                $("#ca_pin_code").val("");
                $("#ca_state").val("");
                $("#ca_district_id").val("");
                $("#ca_district_name").val("");
                $("#ca_circle").val("");
                $("#ca_circles_div").html('<select name="ca_circle" class="form-control"><option value="">Select a circle</option></select>');
                $("#ca_police_station").val($("#pa_police_station").val());
            }//End of if else
        });//End of onChange .address_same
        
        var idProof = parseInt(<?=strlen($id_proof)?1:0?>);
        $("#id_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: idProof?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif","pdf"],
            browseLabel:'Browse & upload'
        }); 
        
        var addressProof = parseInt(<?=strlen($address_proof)?1:0?>);
        $("#address_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: addressProof?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif","pdf"],
            browseLabel:'Browse & upload'
        });
        
        var ageProof = parseInt(<?=strlen($age_proof)?1:0?>);
        $("#age_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: ageProof?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif","pdf"],
            browseLabel:'Browse & upload'
        });
        
        var passportProof = parseInt(<?=strlen($passport_photo)?1:0?>);
        $("#passport_photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: passportProof?false:false,
            maxFileSize: 200,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif"],
            browseLabel:'Browse & upload'
        });
        
        $("#query_doc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"],
            browseLabel:'Browse & upload'
        });      
        
        $(document).on("click", ".frmbtn", function(){
            var clickedBtn = $(this).attr("id");//alert(clickedBtn);
            if(clickedBtn === 'FORM_SUBMIT') {
                $("#form_status").val("DRAFT");
            } else if(clickedBtn === 'QUERY_SUBMIT') {
                $("#form_status").val("QUERY_ARISE");
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
        
        var getNsave = function() {    
            var htmlContent1 = $('html').html().toString();
            var htmlContent = htmlContent1.replace(/\n/g, ""); //Remove newline
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/minoritycertificates/logreports/addrecord')?>",
                data: {
                    "aadhaar_request_id" : aadhaar_request_id, 
                    "aadhaar_request_url" : window.location.href,
                    "aadhaar_request_content" : htmlContent
                },
                beforeSend:function(){
                    console.log("Saving...");
                },
                success:function(res){
                    aadhaar_request_id = res;                        
                    console.log(res);
                }
            });
        }; //End of getNsave()
        getNsave();
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/minority-certificate') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />                       
            <input id="aadhaar_consent_status" name="aadhaar_consent_status" value="<?=$aadhaar_consent_status?>" type="hidden" />
            <input id="mobile_verify_status" name="mobile_verify_status" value="<?=$mobile_verify_status?>" type="hidden" />
            <input id="pa_district_name" name="pa_district_name" value="<?=$pa_district_name?>" type="hidden" />
            <input id="ca_district_name" name="ca_district_name" value="<?=$ca_district_name?>" type="hidden" />            
            <input name="id_proof_old" value="<?=$id_proof?>" type="hidden" />
            <input name="address_proof_old" value="<?=$address_proof?>" type="hidden" />
            <input name="age_proof_old" value="<?=$age_proof?>" type="hidden" />
            <input name="passport_photo_old" value="<?=$passport_photo?>" type="hidden" />
            <input name="query_doc_old" value="<?=$query_doc?>" type="hidden" />                                                    
            <input id="form_status" name="form_status" value="<?=$form_status?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="text-align: center; font-size: 24px; color: #000; font-family: georgia,serif; font-weight: bold">
                    <?=$this->lang->line('application_title')?>
                </div>
                <div class="card-body" style="padding:5px">
                    
                    <div class="dropdown pull-right">
                        <button type="button" class="btn btn-info" data-toggle="dropdown">
                            LANGUAGE <i class="fa fa-arrow-circle-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="<?=base_url('spservices/languageSwitcher/switchLang/english')?>">English</a>
                            <a class="dropdown-item" href="<?=base_url('spservices/languageSwitcher/switchLang/assamese')?>">Assamese</a>
                            <a class="dropdown-item" href="<?=base_url('spservices/languageSwitcher/switchLang/bengali')?>">Bengali</a>
                        </div>
                    </div>
                    
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
                    if($form_status === 'QUERY_ARISE') { ?>
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
                        <legend class="h5"><?=$this->lang->line('applicant_details')?> </legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('applicant_name')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255" type="text" />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('father_name')?> <span class="text-danger">*</span> </label>
                                <input class="form-control" name="father_name" id="father_name" value="<?=$father_name?>" maxlength="255" type="text" />
                                <?= form_error("father_name") ?>
                            </div>
                        </div>
                        
                        <div class="row">  
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('mother_name')?> <span class="text-danger">*</span></label>
                                <input class="form-control" name="mother_name" id="mother_name" value="<?=$mother_name?>" maxlength="255" type="text" />
                                <?= form_error("mother_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label for="mobile_number">
                                    <?=$this->lang->line('mobile_number')?> <span class="text-danger">*</span>
                                    <small class="text-info pull-right">(Please verify the contact number using OTP)</small> 
                                </label>
                                <div class="input-group">
                                    <input class="form-control" name="mobile_number" id="mobile_number" maxlength="10" value="<?=$mobile_number?>" <?=($mobile_verify_status == 1)?'readonly':''?> type="text" />
                                    <div class="input-group-append">
                                        <a href="javascript:void(0)" class="btn btn-outline-danger <?=($mobile_verify_status == 1)?'d-none':''?>" id="send_mobile_otp">Verify</a>
                                        <a href="javascript:void(0)" class="btn btn-outline-success <?=($mobile_verify_status == 1)?'':'d-none'?>" id="verified"><i class="fa fa-check"></i></a>
                                    </div>
                                </div>                                
                                <?= form_error('mobile_number') ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('email_id')?> </label>
                                <input class="form-control" name="email_id" value="<?=$email_id?>" maxlength="100" type="text" />
                                <?= form_error("email_id") ?>
                            </div>  
                            <div class="col-md-3 form-group">
                                <label><?=$this->lang->line('dob')?> <span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="dob" id="dob" value="<?=$dob?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("dob") ?>
                            </div>
                            <div class="col-md-3 form-group">
                                <label><?=$this->lang->line('age')?> </label>
                                <input class="form-control" name="age" id="age" value="" type="text" readonly style="font-size:14px" />
                                <?= form_error("age") ?>
                            </div>
                        </div>  
                        
                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('applicant_gender')?> <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($applicant_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($applicant_gender === "Female")?'selected':''?>>Female</option>
                                    <option value="Others" <?=($applicant_gender === "Others")?'selected':''?>>Others</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>                        
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('community')?> <span class="text-danger">*</span> </label>
                                <select name="community" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Muslim" <?=($community === "Muslim")?'selected':''?>>Muslim</option>
                                    <option value="Christian" <?=($community === "Christian")?'selected':''?>>Christian</option>
                                    <option value="Sikh" <?=($community === "Sikh")?'selected':''?>>Sikh</option>
                                    <option value="Buddhists" <?=($community === "Buddhists")?'selected':''?>>Buddhists</option>
                                    <option value="Zoroastrians(Parsi)" <?=($community === "Zoroastrians(Parsi)")?'selected':''?>>Zoroastrians(Parsi)</option>
                                    <option value="Jain" <?=($community === "Jain")?'selected':''?>>Jain</option>
                                </select>
                                <?= form_error("community") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                        </div>
                    </fieldset>                    
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5"><?=$this->lang->line('pa_address')?><span style="font-size:12px; color: #f31d12">(In case of minor, address proof of parents will be valid)</span></legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_house_no')?></label>
                                <input class="form-control" name="pa_house_no" id="pa_house_no" value="<?=$pa_house_no?>" maxlength="255" type="text" />
                                <?= form_error("pa_house_no") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_street')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_street" id="pa_street" value="<?=$pa_street?>" maxlength="255" type="text" />
                                <?= form_error("pa_street") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_village')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_village" id="pa_village" value="<?=$pa_village?>" maxlength="255" type="text" />
                                <?= form_error("pa_village") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_post_office')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_post_office" id="pa_post_office" value="<?=$pa_post_office?>" maxlength="255" type="text" />
                                <?= form_error("pa_post_office") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_pin_code')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_pin_code" id="pa_pin_code" value="<?=$pa_pin_code?>" maxlength="6" type="text" />
                                <?= form_error("pa_pin_code") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_state')?><span class="text-danger">*</span> </label>
                                <select name="pa_state" id="pa_state" class="form-control">
                                    <option value="Assam">Assam</option>
                                </select>
                                <?= form_error("pa_state") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_district')?><span class="text-danger">*</span> </label>
                                <select name="pa_district_id" id="pa_district_id" class="form-control">
                                    <option value="">Select a district</option>
                                    <?php if($districts) {                                        
                                        foreach($districts as $dist) {
                                            $selectedDist = ($pa_district_name === $dist->district_name)?'selected':'';
                                            if(strtolower($loggedUserRoleSlug) === 'pfc') {
                                                if(strlen($dist->gras_account_code) > 6) {
                                                    echo '<option value="'.$dist->district_id.'" '.$selectedDist.'>'.$dist->district_name.'</option>';
                                                }//End of if
                                            } else {
                                                echo '<option value="'.$dist->district_id.'" '.$selectedDist.'>'.$dist->district_name.'</option>';
                                            }//End of if else
                                        }//End of foreach()
                                    }//End of if ?>
                                </select>
                                <?= form_error("pa_district_id") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_circle')?><span class="text-danger">*</span> </label>
                                <div id="pa_circles_div">
                                    <select name="pa_circle" id="pa_circle" class="form-control">
                                        <option value="<?=$pa_circle?>"><?=strlen($pa_circle)?$pa_circle:'Select'?></option>
                                    </select>
                                </div>
                                <?= form_error("pa_circle") ?>
                            </div>
                        </div>
                        <div class="row">                                
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_police_station')?> <span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_police_station" id="pa_police_station" value="<?=$pa_police_station?>" maxlength="100" type="text" />
                                <?= form_error("pa_police_station") ?>
                            </div>
                        </div>
                    </fieldset>             
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5"><?=$this->lang->line('ca_address')?> <span style="font-size:12px; color: #f31d12">(In case of minor, address proof of parents will be valid)</span></legend>
                        <div class="row mt-2 mb-4">
                            <div class="col-md-6">
                                <label for="address_same"><?=$this->lang->line('address_same')?> ?<span class="text-danger">*</span> </label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input address_same" type="radio" name="address_same" id="dcsYes" value="YES" <?=($address_same === 'YES')?'checked':''?> />
                                    <label class="form-check-label" for="dcsYes">YES</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input address_same" type="radio" name="address_same" id="dcsNo" value="NO" <?=($address_same === 'NO')?'checked':''?> />
                                    <label class="form-check-label" for="dcsNo">NO</label>
                                </div>
                                <?=form_error("address_same")?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_house_no')?></label>
                                <input class="form-control" name="ca_house_no" id="ca_house_no" value="<?=$ca_house_no?>" maxlength="255" type="text" />
                                <?= form_error("ca_house_no") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_street')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_street" id="ca_street" value="<?=$ca_street?>" maxlength="255" type="text" />
                                <?= form_error("ca_street") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_village')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_village" id="ca_village" value="<?=$ca_village?>" maxlength="255" type="text" />
                                <?= form_error("ca_village") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_post_office')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_post_office" id="ca_post_office" value="<?=$ca_post_office?>" maxlength="255" type="text" />
                                <?= form_error("ca_post_office") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_pin_code')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_pin_code" id="ca_pin_code" value="<?=$ca_pin_code?>" maxlength="6" type="text" />
                                <?= form_error("ca_pin_code") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_state')?><span class="text-danger">*</span> </label>
                                <select name="ca_state" id="ca_state" class="form-control">
                                    <option value="Assam">Assam</option>
                                </select>
                                <?= form_error("ca_state") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_district')?><span class="text-danger">*</span> </label>
                                <select name="ca_district_id" id="ca_district_id" class="form-control districts">
                                    <option value="">Select a district</option>
                                    <?php if($districts) {
                                        foreach($districts as $dist) {
                                            $selectedDist = ($ca_district_name === $dist->district_name)?'selected':'';
                                            if(strtolower($loggedUserRoleSlug) === 'pfc') {
                                                if(strlen($dist->gras_account_code) > 6) {
                                                    echo '<option value="'.$dist->district_id.'" '.$selectedDist.'>'.$dist->district_name.'</option>';
                                                }//End of if
                                            } else {
                                                echo '<option value="'.$dist->district_id.'" '.$selectedDist.'>'.$dist->district_name.'</option>';
                                            }//End of if else
                                        }//End of foreach()
                                    }//End of if ?>
                                </select>
                                <?= form_error("ca_district_id") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_circle')?><span class="text-danger">*</span> </label>
                                <div id="ca_circles_div">
                                    <select name="ca_circle" id="ca_circle" class="form-control">
                                        <option value="<?=$ca_circle?>"><?=strlen($ca_circle)?$ca_circle:'Select'?></option>
                                    </select>
                                </div>                                    
                                <?= form_error("ca_circle") ?>
                            </div>
                        </div>
                        <div class="row">                                
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_police_station')?> <span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_police_station" id="ca_police_station" value="<?=$ca_police_station?>" maxlength="100" type="text" />
                                <?= form_error("ca_police_station") ?>
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
                                                Note : For ID proof, Address proof, Age proof only jpg, jpeg, png and pdf of maximum 1MB is allowed;
                                                For Passport size photo only jpg, jpeg, and png of maximum 200KB is allowed
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Type of Enclosure</th>
                                            <th>Enclosure Document</th>
                                            <th>File/Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?=$this->lang->line('id_proof_type')?><span class="text-danger">*</span></td>
                                            <td>
                                                <select name="id_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Aadhaar Card" <?=$id_proof_type==='Aadhaar Card'?'selected':''?>>Aadhaar Card</option>
                                                    <!--<option value="Voter Card" <?=$id_proof_type==='Voter Card'?'selected':''?>>Voter Card</option>-->
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="id_proof" name="id_proof" type="file" />
                                                </div>
                                                <?php if(strlen($id_proof)){ ?>
                                                    <a href="<?=base_url($id_proof)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td><?=$this->lang->line('address_proof_type')?><span class="text-danger">*</span></td>
                                            <td>
                                                <select name="address_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Aadhaar Card" <?=$address_proof_type==='Aadhaar Card'?'selected':''?>>Aadhaar Card</option>
                                                    <option value="Voter Card" <?=$address_proof_type==='Voter Card'?'selected':''?>>Voter Card</option>
                                                    <option value="Passport" <?=$address_proof_type==='Passport'?'selected':''?>>Passport</option>
                                                    <option value="Driving License" <?=$address_proof_type==='Driving License'?'selected':''?>>Driving License</option>
                                                    <option value="Bank Pass Book with Photo" <?=$address_proof_type==='Bank Pass Book with Photo'?'selected':''?>>Bank Pass Book with Photo</option>
                                                </select>
                                                <span style="font-style:italic; color: #fd7e14">In case of minor, address proof of parents will be valid</span>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="address_proof" name="address_proof" type="file" />
                                                </div>
                                                <?php if(strlen($address_proof)){ ?>
                                                    <a href="<?=base_url($address_proof)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td><?=$this->lang->line('age_proof_type')?><span class="text-danger">*</span></td>
                                            <td>
                                                <select name="age_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Aadhaar Card" <?=$age_proof_type==='Aadhaar Card'?'selected':''?>>Aadhaar Card</option>
                                                    <option value="Birth Certificate" <?=$age_proof_type==='Birth Certificate'?'selected':''?>>Birth Certificate</option>
                                                    <option value="10th Admit Card or 10th School Leaving Certificate with Date of Birth" <?=$age_proof_type==='10th Admit Card or 10th School Leaving Certificate with Date of Birth'?'selected':''?>>10th Admit Card or 10th School Leaving Certificate with Date of Birth</option>
                                                    <option value="PAN Card" <?=$age_proof_type==='PAN Card'?'selected':''?>>PAN Card</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="age_proof" name="age_proof" type="file" />
                                                </div>
                                                <?php if(strlen($age_proof)){ ?>
                                                    <a href="<?=base_url($age_proof)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td><?=$this->lang->line('passport_photo_type')?><span class="text-danger">*</span></td>
                                            <td>
                                                <select name="passport_photo_type" class="form-control">
                                                    <option value="Recent passport size photo">Recent passport size photo</option>
                                                </select>
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
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Capture</td>
                                            <td colspan="2">
                                                <div id="live_photo_div" class="row text-center" style="display:none;">
                                                    <div id="my_camera" class="col-md-6 text-center"></div>
                                                    <div class="col-md-6 text-center">
                                                        <img id="captured_photo" src="<?=base_url('assets/plugins/webcamjs/no-photo.png')?>" style="width: 320px; height: 240px;" />
                                                    </div>                          
                                                    <input id="passport_photo_data" name="passport_photo_data" value="" type="hidden" />
                                                    <button id="capture_photo" class="btn btn-warning" style="margin:2px auto" type="button">Capture Photo</button>
                                                </div>   
                                                <div style="text-align:right">
                                                    <img id="open_camera" src="<?=base_url('assets/plugins/webcamjs/camera.png')?>" style="width:50px; height: 50px; cursor: pointer" />
                                                </div>
                                            </td>
                                        </tr>
                                        <?php if($form_status === 'QUERY_ARISE') { ?>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <select class="form-control">
                                                        <option value=""><?=$this->lang->line('query_related_file')?></option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="query_doc" name="query_doc" type="file" />
                                                    </div>
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
                                                    <label><?=$this->lang->line('remarks')?> </label>
                                                    <textarea class="form-control" name="query_answered"><?=$query_answered?></textarea>
                                                    <?= form_error("query_answered") ?>
                                                </div>
                                            </tr>
                                        <?php }//End of if ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset> 
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <?php if($form_status === 'QUERY_ARISE') { ?>
                        <button class="btn btn-success frmbtn" id="QUERY_SUBMIT" type="button">
                            <i class="fa fa-angle-double-right"></i> <?=$this->lang->line('submit')?>
                        </button>
                    <?php } else { ?>
                        <button class="btn btn-danger" type="reset">
                            <i class="fa fa-refresh"></i> <?=$this->lang->line('reset')?>
                        </button>
                        <button class="btn btn-primary frmbtn" id="FORM_SUBMIT" type="button">
                            <i class="fa fa-angle-double-right"></i> <?=$this->lang->line('save_n_next')?>
                        </button>
                    <?php }//End of if ?>
                        
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>



<div class="modal fade" id="consentModal" tabindex="-1" role="dialog" aria-labelledby="consentModalLabel">
    <div class="modal-dialog modal-md" role="document" style="margin:10% auto">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px; font-weight: bold !important; font-size: 28px !important; text-align: center !important; display: block !important">
                <?=$this->lang->line('aadhaar_consent')?>
            </div>
            <div class="modal-body print-content" id="consentview" style="padding: 5px 15px; text-align: justify">
                <?=$this->lang->line('aadhaar_consent_content')?>
            </div><!--End of .modal-body-->
            <div class="modal-footer" style="display: block !important; text-align: center !important">
                <button type="button" id="i_agree_consent" class="btn btn-success">
                    <?=$this->lang->line('i_agree')?>
                </button>
                <!--<button type="button" class="btn btn-danger" data-dismiss="modal">
                    CANCEL
                </button>-->
            </div><!--End of .modal-footer-->
        </div>
    </div>
</div><!--End of #consentModal-->

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
                    <i class="fa fa-check"></i><?=$this->lang->line('VERIFY')?>
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-trash-o"></i><?=$this->lang->line('CANCEL')?>
                </button>
            </div><!--End of .modal-footer-->
        </div>
    </div>
</div><!--End of #otpModal-->