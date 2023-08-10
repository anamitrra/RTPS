<?php
$apiServer = "https://sewasetu.assam.gov.in/apis/gad_apis/"; //For production
// $apiServer = "https://localhost/wptbcapis/"; //For testing
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $pageTitle = $dbrow->service_data->service_name;
    $pageTitleId = $dbrow->service_data->service_id;
    
    $father_name = $dbrow->form_data->father_name??'';
    $mother_name = $dbrow->form_data->mother_name??'';
    $applicant_name = $dbrow->form_data->applicant_name??'';
    $applicant_gender = $dbrow->form_data->applicant_gender ?? set_value("applicant_gender");
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email??'';

    $comp_permanent_address = $dbrow->form_data->comp_permanent_address??'';
    $pa_state = $dbrow->form_data->pa_state??'';
    $pa_district = $dbrow->form_data->pa_district??'';
    $pa_district_id = "";
    $pa_pincode = $dbrow->form_data->pa_pincode??'';
    
    $comp_postal_address = $dbrow->form_data->comp_postal_address??'';
    $pos_state = $dbrow->form_data->pos_state??'';
    $pos_district = $dbrow->form_data->pos_district??'';
    $pos_district_id = "";
    $pos_pincode = $dbrow->form_data->pos_pincode??'';
    
    $ahsec_reg_session = $dbrow->form_data->ahsec_reg_session??'';
    $ahsec_reg_no = $dbrow->form_data->ahsec_reg_no??'';
    $ahsec_yearofpassing = $dbrow->form_data->ahsec_yearofpassing??'';
    $ahsec_admit_roll = $dbrow->form_data->ahsec_admit_roll??'';
    $ahsec_admit_no = $dbrow->form_data->ahsec_admit_no??'';
    $institution_name = $dbrow->form_data->institution_name??'';
   
    $fir_type = isset($dbrow->form_data->fir_type)? $dbrow->form_data->fir_type: ""; 
    $fir = isset($dbrow->form_data->fir)? $dbrow->form_data->fir: "";
    $paper_advertisement_type = isset($dbrow->form_data->paper_advertisement_type)? $dbrow->form_data->paper_advertisement_type: ""; 
    $paper_advertisement = isset($dbrow->form_data->paper_advertisement)? $dbrow->form_data->paper_advertisement: "";
    $hslc_tenth_mrksht_type = isset($dbrow->form_data->hslc_tenth_mrksht_type)? $dbrow->form_data->hslc_tenth_mrksht_type: ""; 
    $hslc_tenth_mrksht = isset($dbrow->form_data->hslc_tenth_mrksht)? $dbrow->form_data->hslc_tenth_mrksht: "";
    $hs_reg_card_type = isset($dbrow->form_data->hs_reg_card_type)? $dbrow->form_data->hs_reg_card_type: ""; 
    $hs_reg_card = isset($dbrow->form_data->hs_reg_card)? $dbrow->form_data->hs_reg_card: "";
    $hs_admit_card_type = isset($dbrow->form_data->hs_admit_card_type)? $dbrow->form_data->hs_admit_card_type: ""; 
    $hs_admit_card = isset($dbrow->form_data->hs_admit_card)? $dbrow->form_data->hs_admit_card: "";$hs_mrksht_type = isset($dbrow->form_data->hs_mrksht_type)? $dbrow->form_data->hs_mrksht_type: ""; 
    $hs_mrksht = isset($dbrow->form_data->hs_mrksht)? $dbrow->form_data->hs_mrksht: "";
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL;//set_value("rtps_trans_id");
    
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $father_name = set_value("father_name");
    $mother_name = set_value("mother_name");
    $mobile = $this->session->mobile;//set_value("mobile_number");
    $email = set_value("email");
    
    $comp_permanent_address = set_value("comp_permanent_address");
    $pa_state = set_value("pa_state");
    $pa_district = "";
    $pa_district_id = "";
    $pa_pincode = set_value("pa_pincode");
    
    $comp_postal_address = set_value("comp_postal_address");
    $pos_state = set_value("pos_state");
    $pos_district = "";
    $pos_district_id = "";
    $pos_pincode = set_value("pos_pincode");
    $reg_session = set_value("reg_session");
   
    $ahsec_reg_session = set_value("ahsec_reg_session");
    $ahsec_reg_no = set_value("ahsec_reg_no");
    $ahsec_yearofpassing = set_value("ahsec_yearofpassing");
    $ahsec_admit_roll = set_value("ahsec_admit_roll");
    $ahsec_admit_no = set_value("ahsec_admit_no");
    $institution_name = set_value("institution_name");

    $paper_advertisement_type = "";
    $paper_advertisement = "";
    $fir_type = "";
    $fir = "";
    $hslc_tenth_mrksht_type = "";
    $hslc_tenth_mrksht = "";
    $hs_reg_card_type = "";
    $hs_reg_card = "";
    $hs_admit_card_type = "";
    $hs_admit_card = "";
    $hs_mrksht_type = "";
    $hs_mrksht = "";
}//End of if else
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

    $(document).on("click", ".frmbtn", function() {
        let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
        $("#submit_mode").val(clickedBtn);
        if (clickedBtn === 'DRAFT') {
            var msg =
            "You want to save in Draft mode that will allows you to edit and can submit later";
        } else if (clickedBtn === 'SAVE') {
            var msg = "Do you want to procced";
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

    $(".dp").datepicker({
        format: 'dd/mm/yyyy',
        endDate: '+0d',
        autoclose: true
    });


    $('.number_input').keypress(function(e) {
        var charCode = (e.which) ? e.which : event.keyCode
        if (String.fromCharCode(charCode).match(/[^0-9]/g))
            return false;
    });

});
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/duplicate-certificate-ahsec') ?>"
            enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
            <input name="service_id" value="<?=$pageTitleId?>" type="hidden" />
            <input name="fir_type" value="<?=$fir_type?>" type="hidden" />
            <input name="fir" value="<?=$fir?>" type="hidden" />
            <input name="paper_advertisement_type" value="<?=$paper_advertisement_type?>" type="hidden" />
            <input name="paper_advertisement" value="<?=$paper_advertisement?>" type="hidden" />
            <?php if(!empty($hslc_tenth_mrksht_type)){ ?>
            <input name="hslc_tenth_mrksht_type" value="<?=$hslc_tenth_mrksht_type?>" type="hidden" />
            <input name="hslc_tenth_mrksht" value="<?=$hslc_tenth_mrksht?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($hs_reg_card_type)){ ?>
            <input name="hs_reg_card_type" value="<?=$hs_reg_card_type?>" type="hidden" />
            <input name="hs_reg_card" value="<?=$hs_reg_card?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($hs_admit_card_type)){ ?>
            <input name="hs_admit_card_type" value="<?=$hs_admit_card_type?>" type="hidden" />
            <input name="hs_admit_card" value="<?=$hs_admit_card?>" type="hidden" />
            <?php } ?>
            <?php if(!empty($hs_mrksht_type)){ ?>
            <input name="hs_mrksht_type" value="<?=$hs_mrksht_type?>" type="hidden" />
            <input name="hs_mrksht" value="<?=$hs_mrksht?>" type="hidden" />
            <?php } ?>
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <?php echo $pageTitle ?></br>
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

                    <fieldset class="border border-success">
                        <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>User charge / ব্যৱহাৰকাৰী মাচুল – Rs. 300 / ৩০০ টকা.</li>
                            <li>Service charge (PFC/ CSC) / সেৱা মাচুল (পি. এফ. চি./ চি. এছ. চি.) – Rs. 30 / ৩০ টকা</li>
                            <li>Printing charge (in case of any printing from PFC) / ছপা খৰচ (পি.এফ.চি. ৰ পৰা কোনো ধৰণৰ
                                প্ৰিন্টিঙৰ ক্ষেত্ৰত) - Rs. 10 Per Page / প্ৰতি পৃষ্ঠাত ১০ টকা.</li>
                            <li>Scanning charge (in case documents are scanned in PFC) স্কেনিং খৰচ (যদি নথিপত্ৰসমূহ
                                পি.এফ.চি. ত স্কেন কৰা হয়) - Rs. 5 Per page / প্ৰতি পৃষ্ঠা ৫ টকা.</li>
                        </ul>

                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী
                            :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. All the * marked fields are mandatory and need to be filled up..</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব</li>
                            <li>2. The size of documents to be uploaded at the time of Application submission should not
                                exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো
                                অনিবাৰ্য </li>
                        </ul>

                    </fieldset>

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
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mother&apos;s Name/ মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name"
                                    value="<?=$mother_name?>" maxlength="255" />
                                <?= form_error("mother_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <?php if($usser_type === "user"){ ?>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" readonly
                                    maxlength="10" />
                                <?php }else{ ?>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>"
                                    maxlength="10" />
                                <?php }?>

                                <?= form_error("mobile") ?>
                            </div>

                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>"
                                    maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                            <div class="col-md-6"></div>
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
                                    <?= form_error("pa_state") ?>
                                </div>
                                <div class="form-group">
                                    <label>District <span class="text-danger">*</span></label>
                                    <select name="pa_district" id="pa_district" class="form-control pa_dists">
                                    </select>
                                    <?= form_error("pa_district") ?>
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
                                <label>AHSEC Registration Session/ AHSEC পঞ্জীয়ন বৰ্ষ <span class="text-danger">*</span> </label>
                                <select name="ahsec_reg_session" class="form-control" readonly>
                                    <option value="">Please Select</option>
                                    <?php foreach($sessions as $session) { ?>   
                                    <option value="<?php echo $session ?>" <?= ($ahsec_reg_session === $session) ? 'selected' : '' ?>><?php echo $session ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("ahsec_reg_session") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Valid AHSEC Registration Number / বৈধ AHSEC পঞ্জীয়ন নম্বৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ahsec_reg_no" id="ahsec_reg_no" value="<?= $ahsec_reg_no ?>" maxlength="255" readonly/>
                                <?= form_error("ahsec_reg_no") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <?php 
                            //if($pageTitleId != "AHSECDRC" || ($pageTitleId == "AHSECDRC" && !empty($ahsec_admit_roll) && !empty($ahsec_admit_no))){ ?>
                            <div class="col-md-6">
                            <?php if ($pageTitleId == "AHSECDPC") { ?>Year of Passing in HS Final Examination/ HS চূড়ান্ত পৰীক্ষাত উত্তীৰ্ণ হোৱাৰ বছৰ <?php } else { ?>Year of Appearing in HS Final Examination/ HS চূড়ান্ত পৰীক্ষাত অৱতীৰ্ণ হোৱাৰ বছৰ <?php } ?>
                                <select name="ahsec_yearofpassing" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php if ($pageTitleId == "AHSECDRC") { ?>
                                        <option value="<?php echo 'Not yet appeared'; ?>" <?= ($ahsec_yearofpassing == "Not yet appeared") ? 'selected' : '' ?>>Not yet appeared</option>
                                    <?php } ?>
                                    <?php foreach($years as $yr) { ?>   
                                    <option value="<?php echo $yr ?>" <?= ($ahsec_yearofpassing === $yr) ? 'selected' : '' ?>><?php echo $yr ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("ahsec_yearofpassing") ?>
                            </div>
                            <?php //} ?>
                        </div>
                        <div class="row form-group">
                            <?php if($pageTitleId != "AHSECDRC" || ($pageTitleId == "AHSECDRC" && !empty($ahsec_admit_roll) && !empty($ahsec_admit_no))){ ?>
                            <div class="col-md-6">
                                <label>Valid H.S Final Examination Roll/ বৈধ HS চূড়ান্ত বৰ্ষৰ পৰীক্ষাৰ ৰোল <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ahsec_admit_roll" id="ahsec_admit_roll" value="<?= $ahsec_admit_roll ?>" maxlength="255" readonly/>
                                <?= form_error("ahsec_admit_roll") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Valid H.S Final Examination Number/ বৈধ HS চূড়ান্ত বৰ্ষৰ পৰীক্ষাৰ নম্বৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ahsec_admit_no" id="ahsec_admit_no" value="<?= $ahsec_admit_no ?>" maxlength="255" readonly />
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
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Next
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div>
                <!--End of .card-footer-->
            </div>
            <!--End of .card-->
        </form>
    </div>
    <!--End of .container-->
</main>