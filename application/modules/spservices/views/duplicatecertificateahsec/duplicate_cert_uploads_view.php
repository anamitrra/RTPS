<?php
$pageTitle = $dbrow->service_data->service_name;
$pageTitleId = $dbrow->service_data->service_id;

$condi_of_doc = $dbrow->form_data->condi_of_doc ?? ''; 

$photo_of_the_candidate_type_frm = set_value("photo_of_the_candidate_type");
$candidate_sign_type_frm = set_value("candidate_sign_type");
$fir_type_frm = set_value("fir_type");
$paper_advertisement_type_frm = set_value("paper_advertisement_type");
$hslc_tenth_mrksht_type_frm = set_value("hslc_tenth_mrksht_type");
$damage_reg_card_type_frm = set_value("damage_reg_card_type");
$hs_reg_card_type_frm = set_value("hs_reg_card_type");
$damage_admit_card_type_frm = set_value("damage_admit_card_type");
$hs_admit_card_type_frm = set_value("hs_admit_card_type");
$damage_mrksht_type_frm = set_value("damage_mrksht_type");
$hs_mrksht_type_frm = set_value("hs_mrksht_type");
$damage_pass_certi_type_frm = set_value("damage_pass_certi_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');
$photo_of_the_candidate_frm = $uploadedFiles['photo_of_the_candidate_old']??null;
$candidate_sign_frm = $uploadedFiles['candidate_sign_old']??null;
$fir_frm = $uploadedFiles['fir_old']??null;
$paper_advertisement_frm = $uploadedFiles['paper_advertisement_old']??null;
$hslc_tenth_mrksht_frm = $uploadedFiles['hslc_tenth_mrksht_old']??null;
$damage_reg_card_frm = $uploadedFiles['damage_reg_card_old']??null;
$hs_reg_card_frm = $uploadedFiles['hs_reg_card_old']??null;
$damage_admit_card_frm = $uploadedFiles['damage_admit_card_old']??null;
$hs_admit_card_frm = $uploadedFiles['hs_admit_card_old']??null;
$damage_mrksht_frm = $uploadedFiles['damage_mrksht_old']??null;
$hs_mrksht_frm = $uploadedFiles['hs_mrksht_old']??null;
$damage_pass_certi_frm = $uploadedFiles['damage_pass_certi_old']??null;

$photo_of_the_candidate_type_db = $dbrow->form_data->photo_of_the_candidate_type??null;
$candidate_sign_type_db = $dbrow->form_data->candidate_sign_type??null;
$fir_type_db = $dbrow->form_data->fir_type??null;
$paper_advertisement_type_db = $dbrow->form_data->paper_advertisement_type??null;
$hslc_tenth_mrksht_type_db = $dbrow->form_data->hslc_tenth_mrksht_type??null;
$damage_reg_card_type_db = $dbrow->form_data->damage_reg_card_type??null;
$hs_reg_card_type_db = $dbrow->form_data->hs_reg_card_type??null;
$hs_admit_card_type_db = $dbrow->form_data->hs_admit_card_type??null;
$damage_admit_card_type_db = $dbrow->form_data->damage_admit_card_type??null;
$hs_mrksht_type_db = $dbrow->form_data->hs_mrksht_type??null;
$damage_mrksht_type_db = $dbrow->form_data->damage_mrksht_type??null;
$damage_pass_certi_type_db = $dbrow->form_data->damage_pass_certi_type??null;

$photo_of_the_candidate_db = $dbrow->form_data->photo_of_the_candidate??null;
$candidate_sign_db = $dbrow->form_data->candidate_sign??null;
$fir_db = $dbrow->form_data->fir??null;
$paper_advertisement_db = $dbrow->form_data->paper_advertisement??null;
$hslc_tenth_mrksht_db = $dbrow->form_data->hslc_tenth_mrksht??null;
$hs_reg_card_db = $dbrow->form_data->hs_reg_card??null;
$damage_reg_card_db = $dbrow->form_data->damage_reg_card??null;
$damage_admit_card_db = $dbrow->form_data->damage_admit_card??null;
$hs_admit_card_db = $dbrow->form_data->hs_admit_card??null;
$hs_mrksht_db = $dbrow->form_data->hs_mrksht??null;
$damage_mrksht_db = $dbrow->form_data->damage_mrksht??null;
$damage_pass_certi_db = $dbrow->form_data->damage_pass_certi??null;

$photo_of_the_candidate_type = strlen($photo_of_the_candidate_type_frm)?$photo_of_the_candidate_type_frm:$photo_of_the_candidate_type_db;
$candidate_sign_type = strlen($candidate_sign_type_frm)?$candidate_sign_type_frm:$candidate_sign_type_db;
$fir_type = strlen($fir_type_frm)?$fir_type_frm:$fir_type_db;
$paper_advertisement_type = strlen($paper_advertisement_type_frm)?$paper_advertisement_type_frm:$paper_advertisement_type_db;
$hslc_tenth_mrksht_type = strlen($hslc_tenth_mrksht_type_frm)?$hslc_tenth_mrksht_type_frm:$hslc_tenth_mrksht_type_db;
$damage_reg_card_type = strlen($damage_reg_card_type_frm)?$damage_reg_card_type_frm:$damage_reg_card_type_db;
$hs_reg_card_type = strlen($hs_reg_card_type_frm)?$hs_reg_card_type_frm:$hs_reg_card_type_db;
$damage_admit_card_type = strlen($damage_admit_card_type_frm)?$damage_admit_card_type_frm:$damage_admit_card_type_db;
$hs_admit_card_type = strlen($hs_admit_card_type_frm)?$hs_admit_card_type_frm:$hs_admit_card_type_db;
$hs_mrksht_type = strlen($hs_mrksht_type_frm)?$hs_mrksht_type_frm:$hs_mrksht_type_db;
$damage_mrksht_type = strlen($damage_mrksht_type_frm)?$damage_mrksht_type_frm:$damage_mrksht_type_db;
$damage_pass_certi_type = strlen($damage_pass_certi_type_frm)?$damage_pass_certi_type_frm:$damage_pass_certi_type_db;

$photo_of_the_candidate = strlen($photo_of_the_candidate_frm)?$photo_of_the_candidate_frm:$photo_of_the_candidate_db;
$candidate_sign = strlen($candidate_sign_frm)?$candidate_sign_frm:$candidate_sign_db;
$fir = strlen($fir_frm)?$fir_frm:$fir_db;
$paper_advertisement = strlen($paper_advertisement_frm)?$paper_advertisement_frm:$paper_advertisement_db;
$hslc_tenth_mrksht = strlen($hslc_tenth_mrksht_frm)?$hslc_tenth_mrksht_frm:$hslc_tenth_mrksht_db;
$damage_reg_card = strlen($damage_reg_card_frm)?$damage_reg_card_frm:$damage_reg_card_db;
$hs_reg_card = strlen($hs_reg_card_frm)?$hs_reg_card_frm:$hs_reg_card_db;
$damage_admit_card = strlen($damage_admit_card_frm)?$damage_admit_card_frm:$damage_admit_card_db;
$hs_admit_card = strlen($hs_admit_card_frm)?$hs_admit_card_frm:$hs_admit_card_db;
$hs_mrksht = strlen($hs_mrksht_frm)?$hs_mrksht_frm:$hs_mrksht_db;
$damage_mrksht = strlen($damage_mrksht_frm)?$damage_mrksht_frm:$damage_mrksht_db;
$damage_pass_certi = strlen($damage_pass_certi_frm)?$damage_pass_certi_frm:$damage_pass_certi_db;
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
<script src="<?= base_url('assets/plugins/webcamjs/webcam.min.js') ?>" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(document).on("click", "#open_camera", function() {
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

    $(document).on("click", "#capture_photo", function() {
        Webcam.snap(function(data_uri) { //alert(data_uri);
            $("#captured_photo").attr("src", data_uri);
            $("#photo_of_the_candidate_data").val(data_uri);
        });
    });

    var photoOfTheCandidate = parseInt(<?=strlen($photo_of_the_candidate)?1:0?>);
    $("#photo_of_the_candidate").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        // required: photoOfTheCandidate ? false : true,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg", "jpeg", "png"]
    });

    var candidateSign = parseInt(<?=strlen($candidate_sign)?1:0?>);
    $("#candidate_sign").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        required: candidateSign ? false : true,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg", "jpeg", "png"]
    });

    var fir = parseInt(<?=strlen($fir)?1:0?>);
    $("#fir").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        // required: passCertificateFromUniColl?false:true,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var paperAdvertisement = parseInt(<?=strlen($paper_advertisement)?1:0?>);
    $("#paper_advertisement").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        // required: passCertificateFromUniColl?false:true,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var hslcTenthMrksht = parseInt(<?=strlen($hslc_tenth_mrksht)?1:0?>);
    $("#hslc_tenth_mrksht").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        // required: pgDegreeDipMarksheet?false:true,
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

    var hsRegCard = parseInt(<?=strlen($hs_reg_card)?1:0?>);
    $("#hs_reg_card").fileinput({
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

    var hsAdmitCard = parseInt(<?=strlen($hs_admit_card)?1:0?>);
    $("#hs_admit_card").fileinput({
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

    var damageMrksht = parseInt(<?=strlen($damage_mrksht)?1:0?>);
    $("#damage_mrksht").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        // required: prcAcmr?false:true,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var hsMrksht = parseInt(<?=strlen($hs_mrksht)?1:0?>);
    $("#hs_mrksht").fileinput({
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
        <form method="POST" action="<?= base_url('spservices/duplicatecertificateahsec/registration/submitfiles') ?>"
            enctype="multipart/form-data">
            <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
            <input name="photo_of_the_candidate_old" value="<?=$photo_of_the_candidate?>" type="hidden" />
            <input name="candidate_sign_old" value="<?=$candidate_sign?>" type="hidden" />
            <input name="fir_old" value="<?=$fir?>" type="hidden" />
            <input name="paper_advertisement_old" value="<?=$paper_advertisement?>" type="hidden" />
            <input name="hslc_tenth_mrksht_old" value="<?=$hslc_tenth_mrksht?>" type="hidden" />
            <input name="damage_reg_card_old" value="<?=$damage_reg_card?>" type="hidden" />
            <input name="hs_reg_card_old" value="<?=$hs_reg_card?>" type="hidden" />
            <input name="damage_admit_card_old" value="<?=$damage_admit_card?>" type="hidden" />
            <input name="hs_admit_card_old" value="<?=$hs_admit_card?>" type="hidden" />
            <input name="damage_mrksht_old" value="<?=$damage_mrksht?>" type="hidden" />
            <input name="hs_mrksht_old" value="<?=$hs_mrksht?>" type="hidden" />
            <input name="damage_pass_certi_old" value="<?=$damage_pass_certi?>" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
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
                                        <tr>
                                            <td>Passport size  photograph [Only .JPEG or .JPG File Allowed, Max file size: 1MB]<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="photo_of_the_candidate_type" class="form-control">
                                                    <option value="Passport size photograph"
                                                        <?=($photo_of_the_candidate_type === 'Passport size photograph')?'selected':''?>>
                                                        Passport size photograph</option>
                                                </select>
                                                <?= form_error("photo_of_the_candidate_type") ?>
                                            </td>
                                            <td>
                                                <input name="photo_of_the_candidate_old"
                                                    value="<?=$photo_of_the_candidate?>" type="hidden" />
                                                <div class="file-loading">
                                                    <input id="photo_of_the_candidate" name="photo_of_the_candidate"
                                                        type="file" />
                                                </div>

                                                <div class="row mt-1">

                                                    <div class="col-sm-4">
                                                        <?php if(strlen($photo_of_the_candidate)){ ?>
                                                        <a href="<?=base_url($photo_of_the_candidate)?>"
                                                            class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>View/Download
                                                        </a>
                                                        <?php }//End of if ?>
                                                    </div>
                                                    <div class="col-sm-8">

                                                        <div id="live_photo_div" class="row text-center mt-"
                                                            style="display:none;">
                                                            <div id="my_camera" class="col-md-6 text-center"></div>
                                                            <div class="col-md-6 text-center">
                                                                <img id="captured_photo"
                                                                    src="<?=base_url('assets/plugins/webcamjs/no-photo.png')?>"
                                                                    style="width: 320px; height: 240px;" />
                                                            </div>
                                                            <input id="photo_of_the_candidate_data"
                                                                name="photo_of_the_candidate_data" value=""
                                                                type="hidden" />
                                                            <button id="capture_photo" class="btn btn-warning"
                                                                style="margin:2px auto" type="button">Capture
                                                                Photo</button>
                                                        </div>
                                                        <div style="text-align:right">
                                                            <span id="open_camera"
                                                                class="btn btn-sm btn-success text-white"> Capture <img
                                                                    src="<?=base_url('assets/plugins/webcamjs/camera.png')?>"
                                                                    style="width:25px; height: 25px; cursor: pointer" />
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Applicant Signature [Only .JPEG or .JPG File Allowed, Max file size: 1 MB]<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="candidate_sign_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Applicant Signature"
                                                        <?=($candidate_sign_type === 'Applicant Signature')?'selected':''?>>
                                                        Applicant Signature</option>
                                                </select>
                                                <?= form_error("candidate_sign_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="candidate_sign" name="candidate_sign" type="file" />
                                                </div>
                                                <?php if(strlen($candidate_sign)){ ?>
                                                <a href="<?=base_url($candidate_sign)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
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
                                                    <option value="FIR Copy"
                                                        <?=($fir_type === 'FIR Copy')?'selected':''?>>FIR Copy</option>
                                                </select>
                                                <?= form_error("fir_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="fir" name="fir" type="file" />
                                                </div>
                                                <?php if(strlen($fir)){ ?>
                                                <a href="<?=base_url($fir)?>" class="btn font-weight-bold text-success"
                                                    target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="fir" type="hidden" name="fir_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('fir'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        <?php if($condi_of_doc == "LOST"){ ?>
                                        <tr>
                                            <td>Paper Advertisement Copy<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="paper_advertisement_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Paper Advertisement Copy"
                                                        <?=($paper_advertisement_type === 'Paper Advertisement Copy')?'selected':''?>>
                                                        Paper Advertisement Copy</option>
                                                </select>
                                                <?= form_error("paper_advertisement_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="paper_advertisement" name="paper_advertisement"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($paper_advertisement)){ ?>
                                                <a href="<?=base_url($paper_advertisement)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="paper_advertisement" type="hidden"
                                                    name="paper_advertisement_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('paper_advertisement'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        <?php if(($dbrow->service_data->service_id == "AHSECDRC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))){ ?>
                                        <tr>
                                            <td>HSLC/10thMarksheet<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="hslc_tenth_mrksht_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="HSLC/10thMarksheet"
                                                        <?=($hslc_tenth_mrksht_type === 'HSLC/10thMarksheet')?'selected':''?>>
                                                        HSLC/10thMarksheet</option>
                                                </select>
                                                <?= form_error("hslc_tenth_mrksht_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hslc_tenth_mrksht" name="hslc_tenth_mrksht"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($hslc_tenth_mrksht)){ ?>
                                                <a href="<?=base_url($hslc_tenth_mrksht)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="hslc_tenth_mrksht" type="hidden"
                                                    name="hslc_tenth_mrksht_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('hslc_tenth_mrksht'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        <?php if(($dbrow->service_data->service_id == "AHSECDRC") && ($condi_of_doc == "PARTIALLY DAMAGED")){ ?>
                                        <tr>
                                            <td>Damaged portion of the Registration Card<span
                                                    class="text-danger">*</span></td>
                                            <td>
                                                <select name="damage_reg_card_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Damaged portion of the Registration Card"
                                                        <?=($damage_reg_card_type === 'Damaged portion of the Registration Card')?'selected':''?>>
                                                        Damaged portion of the Registration Card</option>
                                                </select>
                                                <?= form_error("damage_reg_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="damage_reg_card" name="damage_reg_card" type="file" />
                                                </div>
                                                <?php if(strlen($damage_reg_card)){ ?>
                                                <a href="<?=base_url($damage_reg_card)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="damage_reg_card" type="hidden"
                                                    name="damage_reg_card_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('damage_reg_card'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        <?php if(($dbrow->service_data->service_id == "AHSECDADM") && ($condi_of_doc == "PARTIALLY DAMAGED")){ ?>
                                        <tr>
                                            <td>Damaged portion of the Admit Card<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="damage_admit_card_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Damaged portion of the Admit Card"
                                                        <?=($damage_admit_card_type === 'Damaged portion of the Admit Card')?'selected':''?>>
                                                        Damaged portion of the Admit Card</option>
                                                </select>
                                                <?= form_error("damage_admit_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="damage_admit_card" name="damage_admit_card"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($damage_admit_card)){ ?>
                                                <a href="<?=base_url($damage_admit_card)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="damage_admit_card" type="hidden"
                                                    name="damage_admit_card_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('damage_admit_card'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        <?php if((($dbrow->service_data->service_id == "AHSECDADM") || ($dbrow->service_data->service_id == "AHSECDMRK") || ($dbrow->service_data->service_id == "AHSECDPC")) && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))){ ?>
                                        <tr>
                                            <td>HS Registration Card<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="hs_reg_card_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="HS Registration Card"
                                                        <?=($hs_reg_card_type === 'HS Registration Card')?'selected':''?>>
                                                        HS Registration Card</option>
                                                </select>
                                                <?= form_error("hs_reg_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hs_reg_card" name="hs_reg_card" type="file" />
                                                </div>
                                                <?php if(strlen($hs_reg_card)){ ?>
                                                <a href="<?=base_url($hs_reg_card)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="hs_reg_card" type="hidden" name="hs_reg_card_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('damage_reg_card'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        <?php if(($dbrow->service_data->service_id == "AHSECDMRK") && ($condi_of_doc == "PARTIALLY DAMAGED")){ ?>
                                        <tr>
                                            <td>Damaged portion of the Marksheet<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="damage_mrksht_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Damaged portion of the Marksheet"
                                                        <?=($damage_mrksht_type === 'Damaged portion of the Marksheet')?'selected':''?>>
                                                        Damaged portion of the Marksheet</option>
                                                </select>
                                                <?= form_error("damage_mrksht_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="damage_mrksht" name="damage_mrksht" type="file" />
                                                </div>
                                                <?php if(strlen($damage_mrksht)){ ?>
                                                <a href="<?=base_url($damage_mrksht)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="damage_mrksht" type="hidden" name="damage_mrksht_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('damage_mrksht'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        <?php if(($dbrow->service_data->service_id == "AHSECDMRK") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))){ ?>
                                        <tr>
                                            <td>HS Admit Card<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="hs_admit_card_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="HS Admit Card"
                                                        <?=($hs_admit_card_type === 'HS Admit Card')?'selected':''?>>HS
                                                        Admit Card</option>
                                                </select>
                                                <?= form_error("hs_admit_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hs_admit_card" name="hs_admit_card" type="file" />
                                                </div>
                                                <?php if(strlen($hs_admit_card)){ ?>
                                                <a href="<?=base_url($hs_admit_card)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="hs_admit_card" type="hidden" name="hs_admit_card_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('hs_admit_card'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        <?php if(($dbrow->service_data->service_id == "AHSECDPC") && ($condi_of_doc == "PARTIALLY DAMAGED")){ ?>
                                        <tr>
                                            <td>Damaged portion of the Pass Certificate<span
                                                    class="text-danger">*</span></td>
                                            <td>
                                                <select name="damage_pass_certi_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Damaged portion of the Pass Certificate"
                                                        <?=($damage_pass_certi_type === 'Damaged portion of the Pass Certificate')?'selected':''?>>
                                                        Damaged portion of the Pass Certificate</option>
                                                </select>
                                                <?= form_error("damage_pass_certi_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="damage_pass_certi" name="damage_pass_certi"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($damage_pass_certi)){ ?>
                                                <a href="<?=base_url($damage_pass_certi)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="damage_pass_certi" type="hidden"
                                                    name="damage_pass_certi_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('damage_pass_certi'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        <?php if(($dbrow->service_data->service_id == "AHSECDPC") && (($condi_of_doc == "PARTIALLY DAMAGED") || ($condi_of_doc == "FULLY DAMAGED") || ($condi_of_doc == "LOST"))){ ?>
                                        <tr>
                                            <td>HS Marksheet<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="hs_mrksht_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="HS Marksheet"
                                                        <?=($hs_mrksht_type === 'HS Marksheet')?'selected':''?>>HS
                                                        Marksheet</option>
                                                </select>
                                                <?= form_error("hs_mrksht_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hs_mrksht" name="hs_mrksht" type="file" />
                                                </div>
                                                <?php if(strlen($hs_mrksht)){ ?>
                                                <a href="<?=base_url($hs_mrksht)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <input class="hs_mrksht" type="hidden" name="hs_mrksht_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('hs_mrksht'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?=site_url('spservices/duplicatecertificateahsec/registration/index/'.$obj_id)?>"
                        class="btn btn-primary">
                        <i class="fa fa-angle-double-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-angle-double-right"></i> Save &amp Next
                    </button>
                    <button type="reset" class="btn btn-danger">
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