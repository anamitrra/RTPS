<?php
// pre("Hello0");
$currentYear = date('Y');
//$apiServer = $this->config->item('edistrict_base_url');
$apiServer = "https://localhost/wptbcapis/"; //For testing
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $pageTitle = $dbrow->service_data->service_name;
    $pageTitleId = $dbrow->service_data->service_id;
    $status = $dbrow->service_data->appl_status;

    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender ?? "";
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email;

    $comp_permanent_address = $dbrow->form_data->comp_permanent_address;
    $pa_state = $dbrow->form_data->pa_state;
    $pa_district = $dbrow->form_data->pa_district;
    $pa_district_id = "";
    $pa_pincode = $dbrow->form_data->pa_pincode;

    $comp_postal_address = $dbrow->form_data->comp_postal_address;
    $pos_state = $dbrow->form_data->pos_state;
    $pos_district = $dbrow->form_data->pos_district;
    $pos_district_id = "";
    $pos_pincode = $dbrow->form_data->pos_pincode;

    $ahsec_reg_session = $dbrow->form_data->ahsec_reg_session;
    $ahsec_reg_no = $dbrow->form_data->ahsec_reg_no;
    $ahsec_yearofpassing = isset($dbrow->form_data->ahsec_yearofpassing)? $dbrow->form_data->ahsec_yearofpassing: "";
    $ahsec_admit_roll = isset($dbrow->form_data->ahsec_admit_roll)? $dbrow->form_data->ahsec_admit_roll: "";
    $ahsec_admit_no = isset($dbrow->form_data->ahsec_admit_no)? $dbrow->form_data->ahsec_admit_no: "";
    $institution_name = $dbrow->form_data->institution_name??'';

    $board_seaking_adm = $dbrow->form_data->board_seaking_adm ?? '';
    $course_seaking_adm = $dbrow->form_data->course_seaking_adm ?? '';
    $state_seaking_adm = $dbrow->form_data->state_seaking_adm ?? ''; 
    $reason_seaking_adm = $dbrow->form_data->reason_seaking_adm ?? '';   
    
    $condi_of_doc = $dbrow->form_data->condi_of_doc ?? '';  

    $postal = $dbrow->form_data->postal ?? ''; 
    
    $photo_of_the_candidate_type_frm = set_value("photo_of_the_candidate_type");
    $candidate_sign_type_frm = set_value("candidate_sign_type");
    $fir_type_frm = set_value("fir_type");
    $paper_advertisement_type_frm = set_value("paper_advertisement_type");
    $hslc_tenth_mrksht_type_frm = set_value("hslc_tenth_mrksht_type");
    $hs_reg_card_type_frm = set_value("hs_reg_card_type");
    $hs_admit_card_type_frm = set_value("hs_admit_card_type");
    $hs_mrksht_type_frm = set_value("hs_mrksht_type");
    $damage_reg_card_type_frm = set_value("damage_reg_card_type");
    $damage_admit_card_type_frm = set_value("damage_admit_card_type");
    $damage_mrksht_type_frm = set_value("damage_mrksht_type");
    $damage_pass_certi_type_frm = set_value("damage_pass_certi_type");

    $uploadedFiles = $this->session->flashdata('uploaded_files');
    $photo_of_the_candidate_frm = $uploadedFiles['photo_of_the_candidate_old']??null;
    $candidate_sign_frm = $uploadedFiles['candidate_sign_old']??null;
    $fir_frm = $uploadedFiles['fir_old']??null;
    $paper_advertisement_frm = $uploadedFiles['paper_advertisement_old']??null;
    $hslc_tenth_mrksht_frm = $uploadedFiles['hslc_tenth_mrksht_old']??null;
    $hs_reg_card_frm = $uploadedFiles['hs_reg_card_old']??null;
    $hs_admit_card_frm = $uploadedFiles['hs_admit_card_old']??null;
    $hs_mrksht_frm = $uploadedFiles['hs_mrksht_old']??null;
    $damage_reg_card_frm = $uploadedFiles['damage_reg_card_old']??null;
    $damage_admit_card_frm = $uploadedFiles['damage_admit_card_old']??null;
    $damage_mrksht_frm = $uploadedFiles['damage_mrksht_old']??null;
    $damage_pass_certi_frm = $uploadedFiles['damage_pass_certi_old']??null;

    $photo_of_the_candidate_type_db = $dbrow->form_data->photo_of_the_candidate_type??null;
    $candidate_sign_type_db = $dbrow->form_data->candidate_sign_type??null;
    $fir_type_db = $dbrow->form_data->fir_type??null;
    $paper_advertisement_type_db = $dbrow->form_data->paper_advertisement_type??null;
    $hslc_tenth_mrksht_type_db = $dbrow->form_data->hslc_tenth_mrksht_type??null;
    $hs_reg_card_type_db = $dbrow->form_data->hs_reg_card_type??null;
    $hs_admit_card_type_db = $dbrow->form_data->hs_admit_card_type??null;
    $hs_mrksht_type_db = $dbrow->form_data->hs_mrksht_type??null;
    $damage_reg_card_type_db = $dbrow->form_data->damage_reg_card_type??null;
    $damage_admit_card_type_db = $dbrow->form_data->damage_admit_card_type??null;
    $damage_mrksht_type_db = $dbrow->form_data->damage_mrksht_type??null;
    $damage_pass_certi_type_db = $dbrow->form_data->damage_pass_certi_type??null;

    $photo_of_the_candidate_db = $dbrow->form_data->photo_of_the_candidate??null;
    $candidate_sign_db = $dbrow->form_data->candidate_sign??null;
    $fir_db = $dbrow->form_data->fir??null;
    $paper_advertisement_db = $dbrow->form_data->paper_advertisement??null;
    $hslc_tenth_mrksht_db = $dbrow->form_data->hslc_tenth_mrksht??null;
    $hs_reg_card_db = $dbrow->form_data->hs_reg_card??null;
    $hs_admit_card_db = $dbrow->form_data->hs_admit_card??null;
    $hs_mrksht_db = $dbrow->form_data->hs_mrksht??null;
    $damage_reg_card_db = $dbrow->form_data->damage_reg_card??null;
    $damage_admit_card_db = $dbrow->form_data->damage_admit_card??null;
    $damage_mrksht_db = $dbrow->form_data->damage_mrksht??null;
    $damage_pass_certi_db = $dbrow->form_data->damage_pass_certi??null;

    $photo_of_the_candidate_type = strlen($photo_of_the_candidate_type_frm)?$photo_of_the_candidate_type_frm:$photo_of_the_candidate_type_db;
    $candidate_sign_type = strlen($candidate_sign_type_frm)?$candidate_sign_type_frm:$candidate_sign_type_db;
    $fir_type = strlen($fir_type_frm)?$fir_type_frm:$fir_type_db;
    $paper_advertisement_type = strlen($paper_advertisement_type_frm)?$paper_advertisement_type_frm:$paper_advertisement_type_db;
    $hslc_tenth_mrksht_type = strlen($hslc_tenth_mrksht_type_frm)?$hslc_tenth_mrksht_type_frm:$hslc_tenth_mrksht_type_db;
    $hs_reg_card_type = strlen($hs_reg_card_type_frm)?$hs_reg_card_type_frm:$hs_reg_card_type_db;
    $hs_admit_card_type = strlen($hs_admit_card_type_frm)?$hs_admit_card_type_frm:$hs_admit_card_type_db;
    $hs_mrksht_type = strlen($hs_mrksht_type_frm)?$hs_mrksht_type_frm:$hs_mrksht_type_db;
    $damage_reg_card_type = strlen($damage_reg_card_type_frm)?$damage_reg_card_type_frm:$damage_reg_card_type_db;
    $damage_admit_card_type = strlen($damage_admit_card_type_frm)?$damage_admit_card_type_frm:$damage_admit_card_type_db;
    $damage_mrksht_type = strlen($damage_mrksht_type_frm)?$damage_mrksht_type_frm:$damage_mrksht_type_db;
    $damage_pass_certi_type = strlen($damage_pass_certi_type_frm)?$damage_pass_certi_type_frm:$damage_pass_certi_type_db;

    $photo_of_the_candidate = strlen($photo_of_the_candidate_frm)?$photo_of_the_candidate_frm:$photo_of_the_candidate_db;
    $candidate_sign = strlen($candidate_sign_frm)?$candidate_sign_frm:$candidate_sign_db;
    $fir = strlen($fir_frm)?$fir_frm:$fir_db;
    $paper_advertisement = strlen($paper_advertisement_frm)?$paper_advertisement_frm:$paper_advertisement_db;
    $hslc_tenth_mrksht = strlen($hslc_tenth_mrksht_frm)?$hslc_tenth_mrksht_frm:$hslc_tenth_mrksht_db;
    $hs_reg_card = strlen($hs_reg_card_frm)?$hs_reg_card_frm:$hs_reg_card_db;
    $hs_admit_card = strlen($hs_admit_card_frm)?$hs_admit_card_frm:$hs_admit_card_db;
    $hs_mrksht = strlen($hs_mrksht_frm)?$hs_mrksht_frm:$hs_mrksht_db;
    $damage_reg_card = strlen($damage_reg_card_frm)?$damage_reg_card_frm:$damage_reg_card_db;
    $damage_admit_card = strlen($damage_admit_card_frm)?$damage_admit_card_frm:$damage_admit_card_db;
    $damage_mrksht = strlen($damage_mrksht_frm)?$damage_mrksht_frm:$damage_mrksht_db;
    $damage_pass_certi = strlen($damage_pass_certi_frm)?$damage_pass_certi_frm:$damage_pass_certi_db;
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
        
        $('#checker').change(function() {
    
            if ($(this).is(':checked')) {
            $('#comp_postal_address').val($('#comp_permanent_address').val());
            $('#pos_state').val($('#pa_state').val());
            $('#pos_district').val($('#pa_district').val());
            $('#pos_pincode').val($('#pa_pincode').val());
            
            } else {
                $('#comp_postal_address').val("");
                $('#pos_state').val("");
                $('#pos_district').val("");
                $('#pos_pincode').val(""); 
            }
        });

        $.getJSON("<?= $apiServer ?>district_list.php", function(data) {
            let selectOption = '';
            $.each(data.ListOfDistricts, function(key, value) {
                if ((value.DistrictName + '/' + value.DistrictId) == "<?php print $pa_district; ?>") {
                    selectOption += '<option value="' + value.DistrictName + '/' + value.DistrictId + '" selected>' + value.DistrictName + '</option>';
                } else 
                    selectOption += '<option value="' + value.DistrictName + '/' + value.DistrictId + '">' + value.DistrictName + '</option>';
            });
            $('.pa_dists').append(selectOption);

            selectOption = '';
            $.each(data.ListOfDistricts, function(key, value) {
                if ((value.DistrictName + '/' + value.DistrictId) == "<?php print $pos_district; ?>") {
                    selectOption += '<option value="' + value.DistrictName + '/' + value.DistrictId + '" selected>' + value.DistrictName + '</option>';
                } else 
                    selectOption += '<option value="' + value.DistrictName + '/' + value.DistrictId + '">' + value.DistrictName + '</option>';
            });
            $('.pos_dists').append(selectOption);
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

        var photoOfTheCandidate = parseInt(<?=strlen($photo_of_the_candidate)?1:0?>);
        $("#photo_of_the_candidate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: photoOfTheCandidate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png"]
        });

        var candidateSign = parseInt(<?=strlen($candidate_sign)?1:0?>);
        $("#candidate_sign").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: candidateSign?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png"]
        });
        
        var fir = parseInt(<?=strlen($fir)?1:0?>);
        $("#fir").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            // required: fir?false:true,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var paperAdvertisement = parseInt(<?=strlen($paper_advertisement)?1:0?>);
        $("#paper_advertisement").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            // required: paperAdvertisement?false:true,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var hslcTenthMrksht = parseInt(<?=strlen($hslc_tenth_mrksht)?1:0?>);
        $("#hslc_tenth_mrksht").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            // required: hslcTenthMrksht?false:true,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var hsRegCard = parseInt(<?=strlen($hs_reg_card)?1:0?>);
        $("#hs_reg_card").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            // required: hsRegCard?false:true,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var hsAdmitCard = parseInt(<?=strlen($hs_admit_card)?1:0?>);
        $("#hs_admit_card").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            // required: hsAdmitCard?false:true,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var hsMrksht = parseInt(<?=strlen($hs_mrksht)?1:0?>);
        $("#hs_mrksht").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            // required: hsMrksht?false:true,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var damageRegCard = parseInt(<?=strlen($damage_reg_card)?1:0?>);
        $("#damage_reg_card").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            // required: prcAcmr?false:true,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var damageAdmitCard = parseInt(<?=strlen($damage_admit_card)?1:0?>);
        $("#damage_admit_card").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            // required: prcAcmr?false:true,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var damagePassCerti = parseInt(<?=strlen($damage_pass_certi)?1:0?>);
        $("#damage_pass_certi").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            // required: prcAcmr?false:true,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/duplicatecertificateahsec/registration/querysubmit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
            <input name="photo_of_the_candidate_old" value="<?=$photo_of_the_candidate?>" type="hidden" />
            <input name="candidate_sign_old" value="<?=$candidate_sign?>" type="hidden" />
            <input name="fir_old" value="<?=$fir?>" type="hidden" />
            <input name="paper_advertisement_old" value="<?=$paper_advertisement?>" type="hidden" />
            <input name="hslc_tenth_mrksht_old" value="<?=$hslc_tenth_mrksht?>" type="hidden" />
            <input name="hs_reg_card_old" value="<?=$hs_reg_card?>" type="hidden" />
            <input name="hs_admit_card_old" value="<?=$hs_admit_card?>" type="hidden" />
            <input name="hs_mrksht_old" value="<?=$hs_mrksht?>" type="hidden" />
            <input name="damage_reg_card_old" value="<?=$damage_reg_card?>" type="hidden" />
            <input name="damage_admit_card_old" value="<?=$damage_admit_card?>" type="hidden" />
            <input name="damage_mrksht_old" value="<?=$damage_mrksht?>" type="hidden" />
            <input name="damage_pass_certi_old" value="<?=$damage_pass_certi?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <?php echo $pageTitle ?><br>
                    <?php 
                    switch ($pageTitleId) {
                        case "AHSECDRC": 
                            echo '( ডুপ্লিকেট পঞ্জীয়ন কাৰ্ড প্ৰদানৰ বাবে আবেদন )';
                            break;
                        case "AHSECDADM":
                            echo '( ডুপ্লিকেট এডমিট কাৰ্ড প্ৰদানৰ বাবে আবেদন )';
                            break;
                        case "AHSECDMRK":
                            echo '( ডুপ্লিকেট মাৰ্কশ্বীটত প্ৰদানৰ বাবে আবেদন )';
                                break;
                        case "AHSECDPC":
                            echo '( ডুপ্লিকেট উত্তীৰ্ণ প্ৰমাণপত্ৰ প্ৰদানৰ বাবে আবেদন )';
                                break;
                    }
                    ?>
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
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255"/>
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?= ($applicant_gender === "Male") ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= ($applicant_gender === "Female") ? 'selected' : '' ?>>Female</option>
                                    <option value="Transgender" <?= ($applicant_gender === "Transgender") ? 'selected' : '' ?>>Transgender</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Father&apos;s Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name"
                                    value="<?=$father_name?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Mother&apos;s Name/ মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name"
                                    value="<?=$mother_name?>" maxlength="255" />
                                <?= form_error("dob") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" readonly/>
                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100"/>
                                <?= form_error("email") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address / স্থায়ী ঠিকনা </legend>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Complete Permanent Address/ সম্পূৰ্ণ স্থায়ী ঠিকনা <span class="text-danger">*</span></label>
                                    <textarea id="comp_permanent_address" class="form-control"
                                        name="comp_permanent_address" rows="4"
                                        cols="50"><?=$comp_permanent_address?></textarea>
                                    <?= form_error("comp_permanent_address") ?>
                                </div>

                                <div class="form-group">
                                    <label>Pincode/ পিনকোড<span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="pa_pincode" id="pa_pincode"
                                    value="<?=$pa_pincode?>" maxlength="6" />
                                    <?= form_error("pa_pincode") ?>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>State <span class="text-danger">*</span></label>
                                    <select class="form-control" name="pa_state" id="pa_state">
                                        <option value="">Please Select</option>
                                        <option value="Assam" autocomplete="off" selected="selected">Assam</option>
                                    </select>
                                    <?= form_error("state") ?>
                                </div>
                                <div class="form-group">
                                    <label>District <span class="text-danger">*</span></label>
                                    <select name="pa_district" id="pa_district" class="form-control pa_dists">
                                    </select>
                                    <?= form_error("state") ?>
                                </div>
                            </diiv>
                        </div>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Postal Address / ডাক ঠিকনা </legend>
                        <div class="row">
                        <div class="col-md-12">
                        <input type="checkbox" name="checker" id="checker"  />&nbsp;&nbsp;&nbsp;<b>Same as Permanent Address</b><br/><br/>                        
                        </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label>Complete Postal Address/ সম্পূৰ্ণ ডাক ঠিকনা <span
                                            class="text-danger">*</span></label>

                                    <textarea id="comp_postal_address" class="form-control"
                                        name="comp_postal_address" rows="4"
                                        cols="50"><?=$comp_postal_address?></textarea>

                                    <?= form_error("comp_postal_address") ?>
                                </div>

                                <div class="form-group">
                                <label>Pincode/ পিনকোড<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pos_pincode" id="pos_pincode"
                                    value="<?=$pos_pincode?>" maxlength="255" />
                                <?= form_error("pos_pincode") ?>
                            </div>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>State <span class="text-danger">*</span></label>
                                    <select class="form-control" name="pos_state" id="pos_state">
                                        <option value="">Please Select</option>
                                        <option value="Assam" autocomplete="off" selected="selected">Assam</option>
                                    </select>
                                    <?= form_error("pos_state") ?>
                                </div>
                                <div class="form-group">
                                    <label>District <span class="text-danger">*</span></label>
                                    <select class="form-control pos_dists" name="pos_district" id="pos_district">
                                    </select>
                                    <?= form_error("pos_district") ?>
                                </div>
                                </diiv>
                            </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Academic Details / শৈক্ষিক বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>AHSEC Registration Session/ এ. এইচ. এছ. ই .চি. পঞ্জীয়ন বৰ্ষ <span class="text-danger">*</span> </label>
                                <select name="ahsec_reg_session" class="form-control" readonly>
                                    <option value="">Please Select</option>
                                    <?php foreach($sessions as $session) { ?>   
                                    <option value="<?php echo $session ?>" <?= ($ahsec_reg_session === $session) ? 'selected' : '' ?>><?php echo $session ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("ahsec_reg_session") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Valid AHSEC Registrtion Number / বৈধ এ. এইচ. এছ. ই. চি. পঞ্জীয়ন নম্বৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ahsec_reg_no" id="ahsec_reg_no" value="<?= $ahsec_reg_no ?>" maxlength="255" readonly/>
                                <?= form_error("ahsec_reg_no") ?>
                            </div>
                            <?php if($pageTitleId != "AHSECDRC"){ ?>
                            <div class="col-md-6">
                                <label>Year of appearing in HS Final Examination / এইচ.এছ. চূড়ান্ত পৰীক্ষাত অৱতীৰ্ণ হোৱা বছৰ <span class="text-danger">*</span> </label>
                                <select name="ahsec_yearofpassing" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php foreach($sessions as $session) { ?>   
                                    <option value="<?php echo $session ?>" <?= ($ahsec_yearofpassing === $session) ? 'selected' : '' ?>><?php echo $session ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("ahsec_yearofpassing") ?>
                            </div>
                            <?php } ?>
                            <?php if($pageTitleId != "AHSECDRC"){ ?>
                            <div class="col-md-6">
                                <label>Valid H.S. Final Year Examination Roll/ বৈধ এইচ.এছ. অন্তিম বৰ্ষৰ পৰীক্ষা ৰ'ল <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ahsec_admit_roll" id="ahsec_admit_roll" value="<?= $ahsec_admit_roll ?>" maxlength="255" readonly/>
                                <?= form_error("ahsec_admit_roll") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Valid H.S. Final Year Examination Number / বৈধ এইচ.এছ. অন্তিম বৰ্ষৰ পৰীক্ষা নম্বৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ahsec_admit_no" id="ahsec_admit_no" value="<?= $ahsec_admit_no ?>" maxlength="255" readonly/>
                                <?= form_error("ahsec_admit_no") ?>
                            </div>
                            <?php } ?>
                            <div class="col-md-6">
                                <label>Institution Name / প্ৰতিষ্ঠানৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="institution_name" id="institution_name" value="<?= $institution_name ?>" maxlength="255"/>
                                <?= form_error("institution_name") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Application Details / আবেদনৰ বিৱৰণ </legend>
                        <div class="row ">
                            <div class="form-group  col-md-6">
                                <label>University/Board where seeking admission / বিশ্ববিদ্যালয়/বৰ্ড য'ত নামভৰ্তি
                                    বিচাৰিছে<span class="text-danger">*</span></label>

                                <input type="text" class="form-control" name="board_seaking_adm" id="board_seaking_adm"
                                    value="<?=$board_seaking_adm?>" />

                                <?= form_error("board_seaking_adm") ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Course name where seeking admission/পাঠ্যক্ৰমৰ নাম য'ত নামভৰ্তি বিচাৰিছে <span
                                        class="text-danger">*</span></label>

                                <input type="text" class="form-control" name="course_seaking_adm"
                                    id="course_seaking_adm" value="<?=$course_seaking_adm?>" />
                                <?= form_error("course_seaking_adm") ?>
                            </div>


                            <div class="form-group col-md-6">
                                <label>State where seeking admission/ ক’ত নামভৰ্তি বিচাৰিছে সেই কথা উল্লেখ কৰক<span
                                        class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="state_seaking_adm" id="state_seaking_adm"
                                    value="<?=$state_seaking_adm?>" maxlength="20" />
                                <?= form_error("state_seaking_adm") ?>
                            </div>

                            <div class="form-group col-md-6">

                                <label>Describe Reason for Seeking Migration/ প্ৰব্ৰজন বিচৰাৰ কাৰণ বৰ্ণনা কৰা <span
                                        class="text-danger">*</span></label>

                                <textarea id="reason_seaking_adm" class="form-control" name="reason_seaking_adm"
                                    rows="4" cols="50"><?=$reason_seaking_adm?></textarea>

                                <?= form_error("reason_seaking_adm") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Condition of the document's / নথিপত্ৰৰ অৱস্থা </legend>
                        <div class="row ">
                            <div class="form-group  col-md-12">
                                <input id="condi_of_doc" name="condi_of_doc" type="radio" class=""
                                    <?php if($condi_of_doc=='PARTIALLY DAMAGED') echo "checked='checked'"; ?>
                                    value="PARTIALLY DAMAGED" /> PARTIALLY DAMAGED
                                <br/>
                                <input id="condi_of_doc" name="condi_of_doc" type="radio" class=""
                                    <?php if($condi_of_doc=='FULLY DAMAGED') echo "checked='checked'"; ?>
                                    value="FULLY DAMAGED" /> FULLY DAMAGED
                                    <br/>
                                <input id="condi_of_doc" name="condi_of_doc" type="radio" class=""
                                    <?php if($condi_of_doc=='LOST') echo "checked='checked'"; ?>
                                    value="LOST" /> LOST
                                <?= form_error("condi_of_doc") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Delivery Preference / ডেলিভাৰীৰ পছন্দ </legend>
                        <div class="row ">
                            <div class="form-group  col-md-12">
                                <label>How would you want to receive your migration certificate ? / আপুনি আপোনাৰ
                                    প্ৰব্ৰজন প্ৰমাণপত্ৰ কেনেকৈ লাভ কৰিব বিচাৰিব<span
                                        class="text-danger">*</span></label>

                                <input id="postal" name="postal" type="radio" class=""
                                    <?php if($postal=='FROM AHSEC COUNTER') echo "checked='checked'"; ?>
                                    value="FROM AHSEC COUNTER" /> FROM AHSEC COUNTER
                                <!-- <label for="postal" class="">FROM AHSEC COUNTER</label> -->
                                <br/>
                                <input id="postal" name="postal" type="radio" class=""
                                    <?php if($postal=='IN MY POSTAL ADDRESS') echo "checked='checked'"; ?>
                                    value="IN MY POSTAL ADDRESS"
                                    <?php echo $this->form_validation->set_radio('postal', 'IN MY POSTAL ADDRESS'); ?> />IN MY POSTAL ADDRESS
                                <!-- <label for="postal" class="">IN MY POSTAL ADDRESS</label> -->
                                <?= form_error("postal") ?>
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
                                            <td>Photo of the Candidate*.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="photo_of_the_candidate_type" class="form-control">
                                                    <option value="Photo of the Candidate" <?=($photo_of_the_candidate_type === 'Photo of the Candidate')?'selected':''?>>Photo of the Candidate</option>
                                                </select>
                                                <?= form_error("photo_of_the_candidate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="photo_of_the_candidate" name="photo_of_the_candidate" type="file" />
                                                </div>
                                                <?php if(strlen($photo_of_the_candidate)){ ?>
                                                    <a href="<?=base_url($photo_of_the_candidate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Signature of the Candidate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="candidate_sign_type" class="form-control">
                                                    <option value="Signature of the Candidate" <?=($candidate_sign_type === 'Signature of the Candidate')?'selected':''?>>Signature of the Candidate</option>
                                                </select>
                                                <?= form_error("candidate_sign_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="candidate_sign" name="candidate_sign" type="file" />
                                                </div>
                                                <?php if(strlen($candidate_sign)){ ?>
                                                    <a href="<?=base_url($candidate_sign)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <?php if(($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST")){ ?>
                                        <tr>
                                            <td>FIR Copy<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="fir_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="FIR Copy" <?=($fir_type === 'FIR Copy')?'selected':''?>>FIR Copy</option>
                                                </select>
                                                <?= form_error("fir_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="fir" name="fir" type="file" />
                                                </div>
                                                <?php if(strlen($fir)){ ?>
                                                    <a href="<?=base_url($fir)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        <?php if($condi_of_doc == "LOST"){ ?>
                                        <tr>
                                            <td>Paper Advertisement Copy<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="paper_advertisement_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Paper Advertisement Copy" <?=($paper_advertisement_type === 'Paper Advertisement Copy')?'selected':''?>>Paper Advertisement Copy</option>
                                                </select>
                                                <?= form_error("paper_advertisement_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="paper_advertisement" name="paper_advertisement" type="file" />
                                                </div>
                                                <?php if(strlen($paper_advertisement)){ ?>
                                                    <a href="<?=base_url($paper_advertisement)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        <?php if(($dbrow->service_data->service_id == "AHSECDRC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))){ ?> 
                                        <tr>
                                            <td>HSLC/10th Marksheet Copy<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="hslc_tenth_mrksht_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="HSLC/10th Marksheet Copy" <?=($hslc_tenth_mrksht_type === 'HSLC/10th Marksheet Copy')?'selected':''?>>HSLC/10th Marksheet Copy</option>
                                                </select>
                                                <?= form_error("hslc_tenth_mrksht_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hslc_tenth_mrksht" name="hslc_tenth_mrksht" type="file" />
                                                </div>
                                                <?php if(strlen($hslc_tenth_mrksht)){ ?>
                                                    <a href="<?=base_url($hslc_tenth_mrksht)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        <?php if(($dbrow->service_data->service_id == "AHSECDRC") && ($condi_of_doc == "PARTIALLY DAMAGED")){ ?> 
                                        <tr>
                                            <td>Damaged portion of the Registration Card<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="damage_reg_card_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Damaged portion of the Registration Card" <?=($damage_reg_card_type === 'Damaged portion of the Registration Card')?'selected':''?>>Damaged portion of the Registration Card</option>
                                                </select>
                                                <?= form_error("damage_reg_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="damage_reg_card" name="damage_reg_card" type="file" />
                                                </div>
                                                <?php if(strlen($damage_reg_card)){ ?>
                                                    <a href="<?=base_url($damage_reg_card)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>  
                                        <?php } ?> 

                                        <?php if(($dbrow->service_data->service_id == "AHSECDADM") && ($condi_of_doc == "PARTIALLY DAMAGED")){ ?>
                                        <tr>
                                            <td>Damaged portion of the Admit Card<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="damage_admit_card_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Damaged portion of the Admit Card" <?=($damage_admit_card_type === 'Damaged portion of the Admit Card')?'selected':''?>>Damaged portion of the Admit Card</option>
                                                </select>
                                                <?= form_error("damage_admit_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="damage_admit_card" name="damage_admit_card" type="file" />
                                                </div>
                                                <?php if(strlen($damage_admit_card)){ ?>
                                                    <a href="<?=base_url($damage_admit_card)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <?php } ?> 

                                        <?php if((($dbrow->service_data->service_id == "AHSECDADM") || ($dbrow->service_data->service_id == "AHSECDMRK") || ($dbrow->service_data->service_id == "AHSECDPC")) && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))){ ?>  
                                        <tr>
                                            <td>HS Registration Card<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="hs_reg_card_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="HS Registration Card" <?=($hs_reg_card_type === 'HS Registration Card')?'selected':''?>>HS Registration Card</option>
                                                </select>
                                                <?= form_error("hs_reg_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hs_reg_card" name="hs_reg_card" type="file" />
                                                </div>
                                                <?php if(strlen($hs_reg_card)){ ?>
                                                    <a href="<?=base_url($hs_reg_card)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <?php } ?>   
                                        
                                        <?php if(($dbrow->service_data->service_id == "AHSECDMRK") && ($condi_of_doc == "PARTIALLY DAMAGED")){ ?>
                                        <tr>
                                            <td>Damaged portion of the Marksheet<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="damage_mrksht_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Damaged portion of the Marksheet" <?=($damage_mrksht_type === 'Damaged portion of the Marksheet')?'selected':''?>>Damaged portion of the Marksheet</option>
                                                </select>
                                                <?= form_error("damage_mrksht_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="damage_mrksht" name="damage_mrksht" type="file" />
                                                </div>
                                                <?php if(strlen($damage_mrksht)){ ?>
                                                    <a href="<?=base_url($damage_mrksht)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                        <?php if(($dbrow->service_data->service_id == "AHSECDMRK") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))){ ?> 
                                        <tr>
                                            <td>HS Admit Card<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="hs_admit_card_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="HS Admit Card" <?=($hs_admit_card_type === 'HS Admit Card')?'selected':''?>>HS Admit Card</option>
                                                </select>
                                                <?= form_error("hs_admit_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hs_admit_card" name="hs_admit_card" type="file" />
                                                </div>
                                                <?php if(strlen($hs_admit_card)){ ?>
                                                    <a href="<?=base_url($hs_admit_card)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <?php } ?> 

                                        <?php if(($dbrow->service_data->service_id == "AHSECDPC") && ($condi_of_doc == "PARTIALLY DAMAGED")){ ?>
                                        <tr>
                                            <td>Damaged portion of the Pass Certificate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="damage_pass_certi_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Damaged portion of the Pass Certificate" <?=($damage_pass_certi_type === 'Damaged portion of the Pass Certificate')?'selected':''?>>Damaged portion of the Pass Certificate</option>
                                                </select>
                                                <?= form_error("damage_pass_certi_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="damage_pass_certi" name="damage_pass_certi" type="file" />
                                                </div>
                                                <?php if(strlen($damage_pass_certi)){ ?>
                                                    <a href="<?=base_url($damage_pass_certi)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                        <?php if(($dbrow->service_data->service_id == "AHSECDPC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))){ ?>
                                        <tr>
                                            <td>HS Marksheet<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="hs_mrksht_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="HS Marksheet" <?=($hs_mrksht_type === 'HS Marksheet')?'selected':''?>>HS Marksheet</option>
                                                </select>
                                                <?= form_error("hs_mrksht_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hs_mrksht" name="hs_mrksht" type="file" />
                                                </div>
                                                <?php if(strlen($hs_mrksht)){ ?>
                                                    <a href="<?=base_url($hs_mrksht)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <?php } ?> 
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