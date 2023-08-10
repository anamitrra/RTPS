<?php
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
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $pan_no = isset($dbrow->form_data->pan_no)? $dbrow->form_data->pan_no: "";
    $mobile = $dbrow->form_data->mobile;
    $email = isset($dbrow->form_data->email)? $dbrow->form_data->email: "";
    $aadhar_no = isset($dbrow->form_data->aadhar_no)? $dbrow->form_data->aadhar_no: "";
    $father_name = $dbrow->form_data->father_name;
    $relation_with_deceased = $dbrow->form_data->relation_with_deceased;
    $other_relation = isset($dbrow->form_data->other_relation)? $dbrow->form_data->other_relation: "";

    $name_of_deceased = $dbrow->form_data->name_of_deceased;
    $deceased_gender = $dbrow->form_data->deceased_gender;
    $deceased_dod = $dbrow->form_data->deceased_dod;
    $age_of_deceased = $dbrow->form_data->age_of_deceased;
    $place_of_death = $dbrow->form_data->place_of_death;
    $address_of_hospital_home = isset($dbrow->form_data->address_of_hospital_home)? $dbrow->form_data->address_of_hospital_home: "";
    $other_place_of_death = isset($dbrow->form_data->other_place_of_death)? $dbrow->form_data->other_place_of_death: "";
    $reason_for_late = $dbrow->form_data->reason_for_late;
    $father_name_of_deceased = $dbrow->form_data->father_name_of_deceased;
    $mother_name_of_deceased = $dbrow->form_data->mother_name_of_deceased;

    $state = $dbrow->form_data->state;
    $district = $dbrow->form_data->district;
    $sub_division = $dbrow->form_data->sub_division;
    $revenue_circle = $dbrow->form_data->revenue_circle;
    $village_town = $dbrow->form_data->village_town;
    $pin_code = $dbrow->form_data->pin_code;
    
    $affidavit_type_frm = set_value("affidavit_type");
    $others_type_frm = set_value("others_type");
    $doctor_certificate_type_frm = set_value("doctor_certificate_type");
    $proof_residence_type_frm = set_value("proof_residence_type");
    $soft_copy_type_frm = set_value("soft_copy_type");

    $uploadedFiles = $this->session->flashdata('uploaded_files');
    $affidavit_frm = $uploadedFiles['affidavit_old']??null;
    $others_frm = $uploadedFiles['others_old']??null;
    $doctor_certificate_frm = $uploadedFiles['doctor_certificate_old']??null;
    $proof_residence_frm = $uploadedFiles['proof_residence_old']??null;
    $soft_copy_frm = $uploadedFiles['soft_copy_old']??null;

    $affidavit_type_db = $dbrow->form_data->affidavit_type??null;
    $others_type_db = $dbrow->form_data->others_type??null;
    $doctor_certificate_type_db = $dbrow->form_data->doctor_certificate_type??null;
    $proof_residence_type_db = $dbrow->form_data->proof_residence_type??null;
    $soft_copy_type_db = $dbrow->form_data->soft_copy_type??null;
    $affidavit_db = $dbrow->form_data->affidavit??null;
    $others_db = $dbrow->form_data->others??null;
    $doctor_certificate_db = $dbrow->form_data->doctor_certificate??null;
    $proof_residence_db = $dbrow->form_data->proof_residence??null;
    $soft_copy_db = $dbrow->form_data->soft_copy??null;

    $affidavit_type = strlen($affidavit_type_frm)?$affidavit_type_frm:$affidavit_type_db;
    $others_type = strlen($others_type_frm)?$others_type_frm:$others_type_db;
    $doctor_certificate_type = strlen($doctor_certificate_type_frm)?$doctor_certificate_type_frm:$doctor_certificate_type_db;
    $proof_residence_type = strlen($proof_residence_type_frm)?$proof_residence_type_frm:$proof_residence_type_db;
    $soft_copy_type = strlen($soft_copy_type_frm)?$soft_copy_type_frm:$soft_copy_type_db;
    $affidavit = strlen($affidavit_frm)?$affidavit_frm:$affidavit_db;
    $others = strlen($others_frm)?$others_frm:$others_db;
    $doctor_certificate = strlen($doctor_certificate_frm)?$doctor_certificate_frm:$doctor_certificate_db;
    $proof_residence = strlen($proof_residence_frm)?$proof_residence_frm:$proof_residence_db;
    $soft_copy = strlen($soft_copy_frm)?$soft_copy_frm:$soft_copy_db;
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
<script type="text/javascript">   
    $(document).ready(function () {  
        $.getJSON("<?=$apiServer."district_list.php"?>", function (data) {
            let selectOption = '';
            $('.district').empty().append('<option value="">Select District</option>');
            let selectedDistrict = "<?php print $district; ?>"
            $.each(data.records, function (key, value) {
                if(selectedDistrict == value.district_name)
                    selectOption += '<option value="'+value.district_name +'" selected>'+value.district_name+'</option>';
                else
                    selectOption += '<option value="'+value.district_name +'">'+value.district_name+'</option>';
            });
            $('.district').append(selectOption);
        });
                
        $(document).on("change", "#district", function(){               
            let selectedVal = $(this).val();
            json_body = '{"district_id":"'+selectedVal+'"}';
            if(selectedVal.length) {
                $.getJSON("<?=$apiServer."sub_division_list.php"?>?jsonbody="+json_body+"", function (data) {
                    let selectOption = '';
                    $('#sub_division').empty().append('<option value="">Select a Sub-Division</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.subdiv_name+'">'+value.subdiv_name+'</option>';
                    });
                    $('#sub_division').append(selectOption);
                });
            }
        });


        $(document).on("change", "#sub_division", function(){               
            let selectedVal = $(this).val();
            json_body = '{"subdiv_id":"'+selectedVal+'"}';
            if(selectedVal.length) {
                $.getJSON("<?=$apiServer."revenue_circle_list.php"?>?jsonbody="+json_body+"", function (data) {
                    let selectOption = '';
                    $('#revenue_circle').empty().append('<option value="">Select Revenue Circle</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.circle_name+'">'+value.circle_name+'</option>';
                    });
                    $('#revenue_circle').append(selectOption);
                });
            }
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

        $('.pin_code').keyup(function () {    
            if($(".pin_code").val().length > 6) {
                $(".pin_code").val("");
                alert("Please! Enter upto only 6 digit"); 
            }                        
        });
        
        var doctorCertificate = parseInt(<?=strlen($doctor_certificate)?1:0?>);
        $("#doctor_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: doctorCertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 
        
        var proofResidence = parseInt(<?=strlen($proof_residence)?1:0?>);
        $("#proof_residence").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: proofResidence?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var affidavit = parseInt(<?=strlen($affidavit)?1:0?>);
        $("#affidavit").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: affidavit?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#others").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        
        $("#soft_copy").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/delayeddeath/registration/querysubmit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
            <input name="affidavit_old" value="<?=$affidavit?>" type="hidden" />
            <input name="others_old" value="<?=$others?>" type="hidden" />
            <input name="doctor_certificate_old" value="<?=$doctor_certificate?>" type="hidden" />
            <input name="proof_residence_old" value="<?=$proof_residence?>" type="hidden" />
            <input name="soft_copy_old" value="<?=$soft_copy?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
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
                                Query time : <?=isset(end($dbrow->processing_history)->processing_time)?format_mongo_date(end($dbrow->processing_history)->processing_time):''?>
                            </span>
                        </fieldset>
                    <?php }//End of if ?>
                                        
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255" disabled/>
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($applicant_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($applicant_gender === "Female")?'selected':''?>>Female</option>
                                    <option value="Transgender" <?=($applicant_gender === "Transgender")?'selected':''?>>Transgender</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                        </div>
                    
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" disabled />
                                <?= form_error("mobile") ?> 
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100" disabled/>
                                <?= form_error("email") ?>
                            </div>
                        </div> 

                        <div class="row form-group">
                            <!-- <div class="col-md-6">
                                <label>Aadhar No./আধাৰ নং </label>
                                <input class="form-control number_input" name="aadhar_no" value="<?=$aadhar_no?>" maxlength="12" type="text" id="aadhar_no" disabled/>
                                <?= form_error("aadhar_no") ?>
                            </div>   -->
                            <div class="col-md-6">
                                <label>PAN No./ পেন নং<span class="text-danger">*</span> </label>
                                <input class="form-control pan_no" name="pan_no" value="<?=$pan_no?>" maxlength="10" type="text" disabled/>
                                <?= form_error("pan_no") ?>
                            </div> 
                            <div class="col-md-6">
                                <label>Father's Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input class="form-control" name="father_name" value="<?=$father_name?>" maxlength="100" id="father_name" type="text" disabled/>
                                <?= form_error("father_name") ?>
                            </div> 
                        </div>

                        <div class="row form-group"> 
                            <div class="col-md-6">
                                <label>Relation with Deceased/ মৃতকৰ সৈতে সম্পৰ্ক <span class="text-danger">*</span> </label>
                                <select name="relation_with_deceased" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Father" <?=($relation_with_deceased === "Father")?'selected':''?>>Father</option>
                                    <option value="Mother" <?=($relation_with_deceased === "Mother")?'selected':''?>>Mother</option>
                                    <option value="Brother" <?=($relation_with_deceased === "Brother")?'selected':''?>>Brother</option>
                                    <option value="Son" <?=($relation_with_deceased === "Son")?'selected':''?>>Son</option>
                                    <option value="Daughter" <?=($relation_with_deceased === "Daughter")?'selected':''?>>Daughter</option>
                                    <option value="Wife" <?=($relation_with_deceased === "Wife")?'selected':''?>>Wife</option>
                                    <option value="Husband" <?=($relation_with_deceased === "Husband")?'selected':''?>>Husband</option>
                                    <option value="Other" <?=($relation_with_deceased === "Other")?'selected':''?>>Other</option>
                                </select>
                                <?= form_error("relation_with_deceased") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Enter Other Relation (if any)/ অন্য সম্পৰ্ক প্ৰবিষ্ট কৰক (যদি থাকে)<span class="text-danger">*</span> </label>
                                <input class="form-control" name="other_relation" value="<?=$other_relation?>" maxlength="100" id="other_relation" type="text" disabled/>
                                <?= form_error("other_relation") ?>
                            </div>
                        </div>  
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Deceased Person&apos;s Information / মৃতকৰ তথ্য </legend>

                        <div class="row form-group"> 
                            <div class="col-md-6">
                                <label>Name of the Deceased/ মৃত ব্যক্তিৰ নাম<span class="text-danger">*</span></label>
                                <input class="form-control" name="name_of_deceased" value="<?=$name_of_deceased?>" maxlength="100" id="name_of_deceased" type="text" disabled/>
                                <?= form_error("name_of_deceased") ?>
                            </div> 
                            <div class="col-md-6">
                                <label>Deceased Gender/ মৃতকৰ লিংগ<span class="text-danger">*</span> </label>
                                <select name="deceased_gender" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($deceased_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($deceased_gender === "Female")?'selected':''?>>Female</option>
                                    <option value="Transgender" <?=($applicant_gender === "Transgender")?'selected':''?>>Transgender</option>
                                </select>
                                <?= form_error("deceased_gender") ?>
                            </div>
                        </div> 

                        <div class="row form-group"> 
                            <div class="col-md-6">
                                <label>Date of Death/ মৃত্যুৰ তাৰিখ <span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="deceased_dod" id="deceased_dod" value="<?=$deceased_dod?>" maxlength="10" autocomplete="off" type="text" disabled/>
                                <?= form_error("deceased_dod") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Age of the Deceased (in years)/ মৃতকৰ বয়স (বছৰত)<span class="text-danger">*</span></label>
                                <input class="form-control" name="age_of_deceased" value="<?=$age_of_deceased?>" maxlength="100" id="age_of_deceased" type="text" disabled/>
                                <?= form_error("age_of_deceased") ?>
                            </div>
                        </div>

                        <div class="row form-group"> 
                            <div class="col-md-6">
                                <label>Place of Death/ মৃত্যুৰ ঠাই<span class="text-danger">*</span> </label>
                                <input class="form-control" name="place_of_death" value="<?=$place_of_death?>" maxlength="100" id="place_of_death" type="text" disabled/>
                                <?= form_error("place_of_death") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address of Home/Hospital/ গৃহ/চিকিৎসালয়ৰ ঠিকনা<span class="text-danger">*</span></label>
                                <input class="form-control" name="address_of_hospital_home" value="<?=$address_of_hospital_home?>" maxlength="100" id="address_of_hospital_home" type="text" disabled/>
                                <?= form_error("address_of_hospital_home") ?>
                            </div>
                        </div>

                        <div class="row form-group"> 
                            <div class="col-md-6">
                                <label>Other Place of Death (if any)/ অন্য মৃত্যুস্থান (যদি প্ৰযোজ্য) <span class="text-danger">*</span></label>
                                <input class="form-control" name="other_place_of_death" value="<?=$other_place_of_death?>" maxlength="100" id="other_place_of_death" type="text" disabled/>
                                <?= form_error("other_place_of_death") ?>
                            </div> 
                            <div class="col-md-6">
                                <label>Reason for Being Late/ পলম হোৱাৰ কাৰণ<span class="text-danger">*</span> </label>
                                <input class="form-control" name="reason_for_late" value="<?=$reason_for_late?>" maxlength="100" id="reason_for_late" type="text" disabled />
                                <?= form_error("reason_for_late") ?>
                            </div>
                        </div>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Address of the Deceased/ মৃতকৰ ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>State/ ৰাজ্য <span class="text-danger">*</span> </label>
                                <select name="state" class="form-control" disabled>
                                    <option>Please Select</option>
                                    <option value="Assam" selected>Assam</option>
                                </select>
                                <?= form_error("state") ?>
                            </div>
                            <div class="col-md-6">
                                <label>District/ জিলা <span class="text-danger">*</span> </label>
                                <select name="district" class="form-control district" id="district" disabled>
                                </select>
                                <?= form_error("district") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Sub-Division/ মহকুমা <span class="text-danger">*</span> </label>
                                <select name="sub_division" class="form-control" id="sub_division" disabled>
                                    <option value="">Please Select</option>
                                    <?php if(!empty($sub_division)){ ?>
                                        <option value="<?php print $sub_division; ?>" selected><?php print $sub_division; ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("sub_division") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Revenue Circle/ ৰাজহ চক্ৰ <span class="text-danger">*</span> </label>
                                <select name="revenue_circle" class="form-control" id="revenue_circle" disabled>
                                    <option value="">Please Select</option>
                                    <?php if(!empty($revenue_circle)){ ?>
                                        <option value="<?php print $revenue_circle; ?>" selected><?php print $revenue_circle; ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("revenue_circle") ?>
                            </div>
                        </div>

                        <div class="row form-group"> 
                            <div class="col-md-6">
                                <label>Village/ Town/ গাওঁ/চহৰ<span class="text-danger">*</span> </label>
                                <input class="form-control" name="village_town" value="<?=$village_town?>" maxlength="100" id="village_town" type="text" disabled/>
                                <?= form_error("village_town") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Pin Code/ পিন ক'ড (e.g. 78xxxx)<span class="text-danger">*</span> </label>
                                <input class="form-control number_input" name="pin_code" value="<?=$pin_code?>" maxlength="6" type="text" disabled/>
                                <?= form_error("pin_code") ?>
                            </div>
                        </div> 
                    </fieldset>

                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>১. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য।</li>
                        </ul>
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
                                        <tr>
                                            <td>Hospital or Doctor Certificate regarding Death.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="doctor_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Hospital or Doctor Certificate regarding Death" <?=($doctor_certificate_type === 'Hospital or Doctor Certificate regarding Death')?'selected':''?>>Hospital or Doctor Certificate regarding Death</option>
                                                </select>
                                                <?= form_error("doctor_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="doctor_certificate" name="doctor_certificate" type="file" />
                                                </div>
                                                <?php if(strlen($doctor_certificate)){ ?>
                                                    <a href="<?=base_url($doctor_certificate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download (Old Document)
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Proof of Resident.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="proof_residence_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Proof of Resident" <?=($proof_residence_type === 'Proof of Resident')?'selected':''?>>Proof of Resident</option>
                                                </select>
                                                <?= form_error("proof_residence_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="proof_residence" name="proof_residence" type="file" />
                                                </div>
                                                <?php if(strlen($proof_residence)){ ?>
                                                    <a href="<?=base_url($proof_residence)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download (Old Document)
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Affidavit.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="affidavit_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Affidavit" <?=($affidavit_type === 'Affidavit')?'selected':''?>>Affidavit</option>
                                                </select>
                                                <?= form_error("affidavit_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="affidavit" name="affidavit" type="file" />
                                                </div>
                                                <?php if(strlen($affidavit)){ ?>
                                                    <a href="<?=base_url($affidavit)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download (Old Document)
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Others.</td>
                                            <td>
                                                <select name="others_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Other supporting document" <?=($others_type === 'Other supporting document')?'selected':''?>>Other supporting document</option>
                                                </select>
                                                <?= form_error("others_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="others" name="others" type="file" />
                                                </div>
                                                <?php if(strlen($others)){ ?>
                                                    <a href="<?=base_url($others)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download (Old Document)
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                            
                                        <?php if($this->slug == 'userrr') { ?>
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