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
    $dob = $dbrow->form_data->dob;
    $pan_no = $dbrow->form_data->pan_no;
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email;
    $aadhar_no = $dbrow->form_data->aadhar_no;
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $spouse_name = $dbrow->form_data->spouse_name;
    $state = $dbrow->form_data->state;
    $district = $dbrow->form_data->district;
    $sub_division = $dbrow->form_data->sub_division;
    $revenue_circle = $dbrow->form_data->revenue_circle;
    $mouza = $dbrow->form_data->mouza;
    $village_town = $dbrow->form_data->village_town;
    $post_office = $dbrow->form_data->post_office;
    $pin_code = $dbrow->form_data->pin_code;
    $police_station = $dbrow->form_data->police_station;
    $house_no = $dbrow->form_data->house_no;
    $landline_number = $dbrow->form_data->landline_number;
    $name_of_deceased = $dbrow->form_data->name_of_deceased;
    $deceased_gender = $dbrow->form_data->deceased_gender;
    $deceased_dob = $dbrow->form_data->deceased_dob;
    $deceased_dod = $dbrow->form_data->deceased_dod;
    $reason_of_death = $dbrow->form_data->reason_of_death;
    $place_of_death = $dbrow->form_data->place_of_death;
    $other_place_of_death = $dbrow->form_data->other_place_of_death;
    $guardian_name_of_deceased = $dbrow->form_data->guardian_name_of_deceased;
    $father_name_of_deceased = $dbrow->form_data->father_name_of_deceased;
    $mother_name_of_deceased = $dbrow->form_data->mother_name_of_deceased;
    $spouse_name_of_deceased = $dbrow->form_data->spouse_name_of_deceased;
    $relationship_with_deceased = $dbrow->form_data->relationship_with_deceased;
    $other_relation = isset($dbrow->form_data->other_relation)? $dbrow->form_data->other_relation: "";
    $deceased_district = $dbrow->form_data->deceased_district;
    $deceased_sub_division = $dbrow->form_data->deceased_sub_division;
    $deceased_revenue_circle = $dbrow->form_data->deceased_revenue_circle;
    $deceased_mouza = $dbrow->form_data->deceased_mouza;
    $deceased_village = $dbrow->form_data->deceased_village;
    $deceased_town = $dbrow->form_data->deceased_town;
    $deceased_village = $dbrow->form_data->deceased_village;
    $deceased_town = $dbrow->form_data->deceased_town;
    $deceased_post_office = $dbrow->form_data->deceased_post_office;
    $deceased_pin_code = $dbrow->form_data->deceased_pin_code;
    $deceased_police_station = $dbrow->form_data->deceased_police_station;
    $deceased_house_no = $dbrow->form_data->deceased_house_no;
    $family_details = $dbrow->form_data->family_details; 

    $name_of_kins = array();
    $relations = array();
    $age_y_on_the_date_of_applications= array();
    $age_m_on_the_date_of_applications = array();
    $kin_alive_deads = array();
    
    if(count($family_details)) {
        foreach($family_details as $family_detail) {
            //echo "OBJ : ".$plot->patta_no."<br>";
            array_push($name_of_kins, $family_detail->name_of_kin);
            array_push($relations, $family_detail->relation);
            array_push($age_y_on_the_date_of_applications, $family_detail->age_y_on_the_date_of_application);
            array_push($age_m_on_the_date_of_applications, $family_detail->age_m_on_the_date_of_application);
            array_push($kin_alive_deads, $family_detail->kin_alive_dead);
        }//End of foreach()
    }//End of if
    
    $affidavit_type_frm = set_value("affidavit_type");
    $others_type_frm = set_value("others_type");
    $death_proof_type_frm = set_value("death_proof_type");
    $doc_for_relationship_type_frm = set_value("doc_for_relationship_type");
    $soft_copy_type_frm = set_value("soft_copy_type");

    $uploadedFiles = $this->session->flashdata('uploaded_files');
    $affidavit_frm = $uploadedFiles['affidavit_old']??null;
    $others_frm = $uploadedFiles['others_old']??null;
    $death_proof_frm = $uploadedFiles['death_proof_old']??null;
    $doc_for_relationship_frm = $uploadedFiles['doc_for_relationship_old']??null;
    $soft_copy_frm = $uploadedFiles['soft_copy_old']??null;

    $affidavit_type_db = $dbrow->form_data->affidavit_type??null;
    $others_type_db = $dbrow->form_data->others_type??null;
    $death_proof_type_db = $dbrow->form_data->death_proof_type??null;
    $doc_for_relationship_type_db = $dbrow->form_data->doc_for_relationship_type??null;
    $soft_copy_type_db = $dbrow->form_data->soft_copy_type??null;
    $affidavit_db = $dbrow->form_data->affidavit??null;
    $others_db = $dbrow->form_data->others??null;
    $death_proof_db = $dbrow->form_data->death_proof??null;
    $doc_for_relationship_db = $dbrow->form_data->doc_for_relationship??null;
    $soft_copy_db = $dbrow->form_data->soft_copy??null;

    $affidavit_type = strlen($affidavit_type_frm)?$affidavit_type_frm:$affidavit_type_db;
    $others_type = strlen($others_type_frm)?$others_type_frm:$others_type_db;
    $death_proof_type = strlen($death_proof_type_frm)?$death_proof_type_frm:$death_proof_type_db;
    $doc_for_relationship_type = strlen($doc_for_relationship_type_frm)?$doc_for_relationship_type_frm:$doc_for_relationship_type_db;
    $soft_copy_type = strlen($soft_copy_type_frm)?$soft_copy_type_frm:$soft_copy_type_db;
    $affidavit = strlen($affidavit_frm)?$affidavit_frm:$affidavit_db;
    $others = strlen($others_frm)?$others_frm:$others_db;
    $death_proof = strlen($death_proof_frm)?$death_proof_frm:$death_proof_db;
    $doc_for_relationship = strlen($doc_for_relationship_frm)?$doc_for_relationship_frm:$doc_for_relationship_db;
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
                
        $(document).on("change", "#applicant_district", function(){               
            let selectedVal = $(this).val();
            json_body = '{"district_id":"'+selectedVal+'"}';
            if(selectedVal.length) {
                $.getJSON("<?=$apiServer."sub_division_list.php"?>?jsonbody="+json_body+"", function (data) {
                    let selectOption = '';
                    $('#applicant_sub_division').empty().append('<option value="">Select a Sub-Division</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.subdiv_name+'">'+value.subdiv_name+'</option>';
                    });
                    $('#applicant_sub_division').append(selectOption);
                });
            }
        });

        $(document).on("change", "#deceased_district", function(){               
            let selectedVal = $(this).val();
            json_body = '{"district_id":"'+selectedVal+'"}';
            if(selectedVal.length) {
                $.getJSON("<?=$apiServer."sub_division_list.php"?>?jsonbody="+json_body+"", function (data) {
                    let selectOption = '';
                    $('#deceased_sub_division').empty().append('<option value="">Select a Sub-Division</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.subdiv_name+'">'+value.subdiv_name+'</option>';
                    });
                    $('#deceased_sub_division').append(selectOption);
                });
            }
        });

        $(document).on("change", "#applicant_sub_division", function(){               
            let selectedVal = $(this).val();
            json_body = '{"subdiv_id":"'+selectedVal+'"}';
            if(selectedVal.length) {
                $.getJSON("<?=$apiServer."revenue_circle_list.php"?>?jsonbody="+json_body+"", function (data) {
                    let selectOption = '';
                    $('#applicant_revenue_circle').empty().append('<option value="">Select Revenue Circle</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.circle_name+'">'+value.circle_name+'</option>';
                    });
                    $('#applicant_revenue_circle').append(selectOption);
                });
            }
        });

        $(document).on("change", "#deceased_sub_division", function(){               
            let selectedVal = $(this).val();
            json_body = '{"subdiv_id":"'+selectedVal+'"}';
            if(selectedVal.length) {
                $.getJSON("<?=$apiServer."revenue_circle_list.php"?>?jsonbody="+json_body+"", function (data) {
                    let selectOption = '';
                    $('#deceased_revenue_circle').empty().append('<option value="">Select Revenue Circle</option>')
                    $.each(data.records, function (key, value) {
                        selectOption += '<option value="'+value.circle_name+'">'+value.circle_name+'</option>';
                    });
                    $('#deceased_revenue_circle').append(selectOption);
                });
            }
        });
        
        $(document).on("click", "#addlatblrow", function(){
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
            if(totRows <= 80) {
                $('#familydetailstatustbl tr:last').after(trow);
            }
        });

        $(document).on("click", ".deletetblrow", function () {
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
            var dateAr = date_of_birth.split('/');
            var dob = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];
            getAge(dob);
        }//End of if
        
        $(document).on("change", "#dob", function(){             
            var dd = $(this).val(); //alert(dd);
            var dateAr = dd.split('/');
            var dob = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];
            getAge(dob);
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
        
        var deathProof = parseInt(<?=strlen($death_proof)?1:0?>);
        $("#death_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: deathProof?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 
        
        var docForRelationship = parseInt(<?=strlen($doc_for_relationship)?1:0?>);
        $("#doc_for_relationship").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: docForRelationship?false:true,
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
        
        $(document).on("change", "#relationship_with_deceased", function(){ 
            var thisVal = $(this).val();

            if(thisVal == "Other"){
                $('#other_relation_txt').val("");
                $('#other_relation').show();
            }
                
            else{
                $('#other_relation_txt').val("");
                $('#other_relation').hide();
            }
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/nextofkin/registration/querysubmit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
            <input name="affidavit_old" value="<?=$affidavit?>" type="hidden" />
            <input name="others_old" value="<?=$others?>" type="hidden" />
            <input name="death_proof_old" value="<?=$death_proof?>" type="hidden" />
            <input name="doc_for_relationship_old" value="<?=$doc_for_relationship?>" type="hidden" />
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

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Date of Birth/ জন্ম তাৰিখ<span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="dob" id="dob" value="<?=$dob?>" maxlength="10" autocomplete="off" type="text" disabled/>
                                <?= form_error("dob") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Age/ বয়স</label>
                                <input class="form-control" name="age" id="age" value="" type="text" readonly style="font-size:14px" disabled/>
                                <?= form_error("age") ?>
                            </div>  
                        </div>

                        <div class="row"> 
                            <!-- <div class="col-md-6 form-group">
                                <label>Aadhar No./আধাৰ নং </label>
                                <input class="form-control number_input" name="aadhar_no" value="<?=$aadhar_no?>" maxlength="12" type="text" id="aadhar_no" disabled/>
                                <?= form_error("aadhar_no") ?>
                            </div> -->
                            <div class="col-md-6 form-group">
                                <label>PAN No./ পেন নং<span class="text-danger">*</span> </label>
                                <input class="form-control pan_no" name="pan_no" value="<?=$pan_no?>" maxlength="10" type="text" disabled/>
                                <?= form_error("pan_no") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Father's Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input class="form-control" name="father_name" value="<?=$father_name?>" maxlength="100" id="father_name" type="text" disabled/>
                                <?= form_error("father_name") ?>
                            </div> 
                        </div> 

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Mother's Name/ মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input class="form-control" name="mother_name" value="<?=$mother_name?>" maxlength="12" type="text" id="mother_name" disabled/>
                                <?= form_error("mother_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Name of Spouse/ স্বামী/পত্নীৰ নাম </label>
                                <input class="form-control" name="spouse_name" value="<?=$spouse_name?>" maxlength="100" id="spouse_name" type="text" disabled/>
                                <?= form_error("spouse_name") ?>
                            </div> 
                        </div>
                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Address / আবেদনকাৰীৰ ঠিকনা </legend>
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
                                <select name="district" class="form-control district" id="applicant_district" disabled>
                                </select>
                                <?= form_error("district") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Sub-Division/ মহকুমা <span class="text-danger">*</span> </label>
                                <select name="sub_division" class="form-control sub_division" id="applicant_sub_division" disabled>
                                    <option value="">Please Select</option>
                                    <?php if(!empty($sub_division)){ ?>
                                        <option value="<?php print $sub_division; ?>" selected><?php print $sub_division; ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("sub_division") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Revenue Circle/ ৰাজহ চক্ৰ <span class="text-danger">*</span> </label>
                                <select name="revenue_circle" class="form-control revenue_circle" id="applicant_revenue_circle" disabled>
                                    <option value="">Please Select</option>
                                    <?php if(!empty($revenue_circle)){ ?>
                                        <option value="<?php print $revenue_circle; ?>" selected><?php print $revenue_circle; ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("revenue_circle") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Mouza/ মৌজা <span class="text-danger">*</span></label>
                                <input class="form-control" name="mouza" value="<?=$mouza?>" maxlength="100" id="mouza" type="text" disabled/>
                                <?= form_error("mouza") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Village/Town/ গাওঁ/চহৰ <span class="text-danger">*</span></label>
                                <input class="form-control" name="village_town" value="<?=$village_town?>" maxlength="100" id="village_town" type="text" disabled/>
                                <?= form_error("village_town") ?>
                            </div>
                        </div> 

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Post Office/ ডাকঘৰ <span class="text-danger">*</span></label>
                                <input class="form-control" name="post_office" value="<?=$post_office?>" maxlength="100" id="post_office" type="text" disabled/>
                                <?= form_error("post_office") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Pin Code/ পিন ক'ড (e.g. 78xxxx) <span class="text-danger">*</span></label>
                                <input class="form-control number_input pin_code" value="<?=$pin_code?>" maxlength="6" name="pin_code" type="text" disabled/>
                                <?= form_error("pin_code") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Police Station/ থানা <span class="text-danger">*</span></label>
                                <input class="form-control" name="police_station" value="<?=$police_station?>" maxlength="100" id="police_station" type="text" disabled/>
                                <?= form_error("police_station") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>House No/ ঘৰ নং </label>
                                <input class="form-control" name="house_no" value="<?=$house_no?>" maxlength="100" id="house_no" type="text" disabled/>
                                <?= form_error("house_no") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Landline Number/ দূৰভাস (if any) </label>
                                <input class="form-control" name="landline_number" value="<?=$landline_number?>" maxlength="100" id="landline_number" type="text" disabled/>
                                <?= form_error("landline_number") ?>
                            </div> 
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Deceased Person&apos;s Information / মৃতকৰ তথ্য </legend>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
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

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Date of Birth/ জন্মৰ তাৰিখ<span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="deceased_dob" id="deceased_dob" value="<?=$deceased_dob?>" maxlength="10" autocomplete="off" type="text" disabled/>
                                <?= form_error("deceased_dob") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Date of Death/ মৃত্যুৰ তাৰিখ <span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="deceased_dod" id="deceased_dod" value="<?=$deceased_dod?>" maxlength="10" autocomplete="off" type="text" disabled/>
                                <?= form_error("deceased_dod") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Reason of Death/ মৃত্যুৰ কাৰন <span class="text-danger">*</span></label>
                                <input class="form-control" name="reason_of_death" value="<?=$reason_of_death?>" maxlength="100" id="reason_of_death" type="text" disabled/>
                                <?= form_error("reason_of_death") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Place of Death/ মৃত্যুৰ ঠাই<span class="text-danger">*</span> </label>
                                <input class="form-control" name="place_of_death" value="<?=$place_of_death?>" maxlength="100" id="place_of_death" type="text" disabled/>
                                <?= form_error("place_of_death") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Other Place of Death (if any)/ অন্য মৃত্যুস্থান (যদি প্ৰযোজ্য) </label>
                                <input class="form-control" name="other_place_of_death" value="<?=$other_place_of_death?>" maxlength="100" id="other_place_of_death" type="text" disabled/>
                                <?= form_error("other_place_of_death") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Father's/Guardian's Name of the Deceased/ মৃতকৰ পিতৃ/অভিভাৱকৰ নাম <span class="text-danger">*</span> </label>
                                <input class="form-control" name="guardian_name_of_deceased" value="<?=$guardian_name_of_deceased?>" maxlength="100" id="guardian_name_of_deceased" type="text" disabled/>
                                <?= form_error("guardian_name_of_deceased") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Father's Name of the Deceased/ মৃতকৰ পিতৃৰ নাম <span class="text-danger">*</span></label>
                                <input class="form-control" name="father_name_of_deceased" value="<?=$father_name_of_deceased?>" maxlength="100" id="father_name_of_deceased" type="text" disabled/>
                                <?= form_error("father_name_of_deceased") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Mother Name of the Deceased/ মৃতকৰ মাতৃৰ নাম <span class="text-danger">*</span> </label>
                                <input class="form-control" name="mother_name_of_deceased" value="<?=$mother_name_of_deceased?>" maxlength="100" id="mother_name_of_deceased" type="text" disabled/>
                                <?= form_error("mother_name_of_deceased") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Spouse Name of the Deceased/ মৃতকৰ স্বামী/পত্নীৰ নাম </label>
                                <input class="form-control" name="spouse_name_of_deceased" value="<?=$spouse_name_of_deceased?>" maxlength="100" id="spouse_name_of_deceased" type="text" disabled/>
                                <?= form_error("spouse_name_of_deceased") ?>
                            </div> 
                            <div class="col-md-6">
                                <label>Relation of the Applicant with the Deceased/ মৃতকৰ লগত আবেদনকাৰীৰ সম্পৰ্ক <span class="text-danger">*</span> </label>
                                <select name="relationship_with_deceased" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Husband" <?=($relationship_with_deceased === "Husband")?'selected':''?>>Husband</option>
                                    <option value="Wife" <?=($relationship_with_deceased === "Wife")?'selected':''?>>Wife</option>
                                    <option value="Father" <?=($relationship_with_deceased === "Father")?'selected':''?>>Father</option>
                                    <option value="Mother" <?=($relationship_with_deceased === "Mother")?'selected':''?>>Mother</option>
                                    <option value="Son" <?=($relationship_with_deceased === "Son")?'selected':''?>>Son</option>
                                    <option value="Daughter" <?=($relationship_with_deceased === "Daughter")?'selected':''?>>Daughter</option>
                                    <option value="Brother" <?=($relationship_with_deceased === "Brother")?'selected':''?>>Brother</option>
                                    <option value="Sister" <?=($relationship_with_deceased === "Sister")?'selected':''?>>Sister</option>
                                    <option value="Other" <?=($relationship_with_deceased === "Other")?'selected':''?>>Other</option>
                                </select>
                                <?= form_error("relationship_with_deceased") ?>
                            </div>
                        </div>

                        <div class="row" id="other_relation" style="display: <?php ($relationship_with_deceased === "Other")? print 'block': print 'none'; ?>;"> 
                            <div class="col-md-6 form-group">
                                <label>Enter Other Relation (If Any)/ অন্য সম্পৰ্ক প্ৰৱেশ কৰক (যদি আছে)<span class="text-danger">*</span> </label>
                                <input class="form-control" name="other_relation" value="<?=$other_relation?>" maxlength="100" id="other_relation_txt" type="text" required disabled/>
                                <?= form_error("other_relation") ?>
                            </div> 
                            <div class="col-md-6">
                            </div>
                        </div>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Address of the Deceased/ মৃতকৰ ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>District/ জিলা <span class="text-danger">*</span> </label>
                                <select name="deceased_district" class="form-control district" id="deceased_district" disabled>
                                </select>
                                <?= form_error("deceased_district") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Sub-Division/ মহকুমা <span class="text-danger">*</span> </label>
                                <select name="deceased_sub_division" class="form-control" id="deceased_sub_division" disabled>
                                    <option value="">Please Select</option>
                                    <?php if(!empty($deceased_sub_division)){ ?>
                                        <option value="<?php print $deceased_sub_division; ?>" selected><?php print $deceased_sub_division; ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("deceased_sub_division") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Revenue Circle/ ৰাজহ চক্ৰ <span class="text-danger">*</span> </label>
                                <select name="deceased_revenue_circle" class="form-control" id="deceased_revenue_circle" disabled>
                                    <option value="">Please Select</option>
                                    <?php if(!empty($deceased_revenue_circle)){ ?>
                                        <option value="<?php print $deceased_revenue_circle; ?>" selected><?php print $deceased_revenue_circle; ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("deceased_revenue_circle") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Mouza/ মৌজা<span class="text-danger">*</span> </label>
                                <input class="form-control" name="deceased_mouza" value="<?=$deceased_mouza?>" maxlength="100" id="deceased_mouza" type="text" disabled/>
                                <?= form_error("deceased_mouza") ?>
                            </div> 
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Village/ গাওঁ<span class="text-danger">*</span> </label>
                                <input class="form-control" name="deceased_village" value="<?=$deceased_village?>" maxlength="100" id="deceased_village" type="text" disabled/>
                                <?= form_error("deceased_village") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Town/চহৰ <span class="text-danger">*</span></label>
                                <input class="form-control" name="deceased_town" value="<?=$deceased_town?>" maxlength="100" id="deceased_town" type="text" disabled/>
                                <?= form_error("deceased_town") ?>
                            </div>
                        </div> 

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Post Office/ ডাকঘৰ <span class="text-danger">*</span></label>
                                <input class="form-control" name="deceased_post_office" value="<?=$deceased_post_office?>" maxlength="100" id="deceased_post_office" type="text" disabled/>
                                <?= form_error("deceased_post_office") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>Pin Code/ পিন ক'ড (e.g. 78xxxx)<span class="text-danger">*</span> </label>
                                <input class="form-control number_input" name="deceased_pin_code" value="<?=$deceased_pin_code?>" maxlength="6" type="text" disabled/>
                                <?= form_error("deceased_pin_code") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Police Station/ থানা <span class="text-danger">*</span></label>
                                <input class="form-control" name="deceased_police_station" value="<?=$deceased_police_station?>" maxlength="100" id="deceased_police_station" type="text" disabled/>
                                <?= form_error("deceased_police_station") ?>
                            </div> 
                            <div class="col-md-6 form-group">
                                <label>House No/ ঘৰ নং </label>
                                <input class="form-control" name="deceased_house_no" value="<?=$deceased_house_no?>" maxlength="100" id="deceased_house_no" type="text" disabled/>
                                <?= form_error("deceased_house_no") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Family Details/পৰিয়ালৰ বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="familydetailstatustbl">
                                    <thead>
                                        <tr>
                                            <th>Name of Kin/ আত্মীয়ৰ নাম<span class="text-danger">*</span></th>
                                            <th>Relation/ সম্পৰ্ক<span class="text-danger">*</span></th>
                                            <th>Age(Y) on the Date of Application/ আবেদনৰ সময়ত বয়স (বছৰ) <span class="text-danger">*</span></th>
                                            <th>Age(M) on the Date of Application/ আবেদনৰ সময়ত বয়স (মাহ)<span class="text-danger">*</span></th>
                                            <th>Kin Alive or Dead/ আত্মীয় জীৱিত নে মৃত<span class="text-danger">*</span></th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $name_of_kin_cnt = (isset($name_of_kins) && is_array($name_of_kins)) ? count($name_of_kins) : 0;
                                        if ($name_of_kin_cnt > 0) {
                                            for ($i = 0; $i < $name_of_kin_cnt; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="addlatblrow" type="button" disabled><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger deletetblrow" type="button" disabled><i class="fa fa-trash-o"></i></button>';
                                                }// End of if else ?>
                                                <tr>
                                                    <td><input name="name_of_kins[]" value="<?= $name_of_kins[$i] ?>" class="form-control" type="text" disabled/></td>
                                                    <td><input name="relations[]" value="<?= $relations[$i] ?>" class="form-control" type="text" disabled/></td>
                                                    <td><input name="age_y_on_the_date_of_applications[]" value="<?= $age_y_on_the_date_of_applications[$i] ?>" class="form-control" type="text" disabled/></td>
                                                    <td><input name="age_m_on_the_date_of_applications[]" value="<?= $age_m_on_the_date_of_applications[$i] ?>" class="form-control" type="text" disabled/></td>
                                                    <td>
                                                        <select name="kin_alive_deads[]" class="form-control" disabled>
                                                            <option value="">Select</option>
                                                            <option value="Alive" <?=($kin_alive_deads[$i]==='Alive')?'selected':''?>>Alive</option>
                                                            <option value="Expired" <?=($kin_alive_deads[$i]==='Expired')?'selected':''?>>Expired</option>
                                                        </select>
                                                    </td>
                                                    <td><?= $btn ?></td>
                                                </tr>
                                                <?php }
                                        } else { ?>
                                            <tr>
                                                <td><input name="name_of_kins[]" class="form-control" type="text" disabled/></td>
                                                <td><input name="relations[]" class="form-control" type="text" disabled/></td>
                                                <td><input name="age_y_on_the_date_of_applications[]" class="form-control" type="text" disabled/></td>
                                                <td><input name="age_m_on_the_date_of_applications[]" class="form-control" type="text" disabled/></td>
                                                <td>
                                                    <select name="kin_alive_deads[]" class="form-control" disabled>
                                                        <option value="">Select</option>
                                                        <option value="Alive">Alive</option>
                                                        <option value="Expired">Expired</option>
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
                                            <td>Death Proof.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="death_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Death certificate of the deceased person" <?=($death_proof_type === 'Death certificate of the deceased person')?'selected':''?>>Death certificate of the deceased person</option>
                                                </select>
                                                <?= form_error("death_proof_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="death_proof" name="death_proof" type="file" />
                                                </div>
                                                <?php if(strlen($death_proof)){ ?>
                                                    <a href="<?=base_url($death_proof)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download (Old Document)
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Document for relationship proof.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="doc_for_relationship_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Marriage Certificate" <?=($doc_for_relationship_type === 'Marriage Certificate')?'selected':''?>>Marriage Certificate</option>
                                                    <option value="Voter ID Card" <?=($doc_for_relationship_type === 'Voter ID Card')?'selected':''?>>Voter ID Card</option>
                                                    <option value="Copy of PAN Card" <?=($doc_for_relationship_type === 'Copy of PAN Card')?'selected':''?>>Copy of PAN Card</option>
                                                    <option value="Birth Certificate" <?=($doc_for_relationship_type === 'Birth Certificate')?'selected':''?>>Birth Certificate</option>
                                                    <option value="Others" <?=($doc_for_relationship_type === 'Others')?'selected':''?>>Others</option>
                                                </select>
                                                <?= form_error("doc_for_relationship_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="doc_for_relationship" name="doc_for_relationship" type="file" />
                                                </div>
                                                <?php if(strlen($doc_for_relationship)){ ?>
                                                    <a href="<?=base_url($doc_for_relationship)?>" class="btn font-weight-bold text-success" target="_blank">
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
                                            <td>Others.<span class="text-danger">*</span></td>
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