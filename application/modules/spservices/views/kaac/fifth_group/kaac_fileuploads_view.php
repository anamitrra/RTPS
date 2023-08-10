<?php
if($dbrow) {
$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;  

$signature = $dbrow->form_data->signature ?? '';
$signature_type = $dbrow->form_data->signature_type ?? '';

$land_owner_signature = $dbrow->form_data->land_owner_signature ?? '';
$land_owner_signature_type = $dbrow->form_data->land_owner_signature_type ?? '';

$photo = $dbrow->form_data->photo ?? '';
$photo_type = $dbrow->form_data->photo_type ?? '';

$bank_passbook = $dbrow->form_data->bank_passbook ?? '';
$bank_passbook_type = $dbrow->form_data->bank_passbook_type ?? '';   

$aadhaar_card = $dbrow->form_data->aadhaar_card ?? '';
$aadhaar_card_type = $dbrow->form_data->aadhaar_card_type ?? '';   

$ncla_certificate = $dbrow->form_data->ncla_certificate ?? '';
$ncla_certificate_type = $dbrow->form_data->ncla_certificate_type ?? '';   

$land_records = $dbrow->form_data->land_records ?? '';
$land_records_type = $dbrow->form_data->land_records_type ?? '';   


$land_document = $dbrow->form_data->land_document ?? '';
$land_document_type = $dbrow->form_data->land_document_type ?? '';   


$agreement_copy = $dbrow->form_data->agreement_copy ?? '';
$agreement_copy_type = $dbrow->form_data->agreement_copy_type ?? '';   


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

<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

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
            $("#photo_data").val(data_uri);
        });
    });


    $("#photo").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg"]
    });
    $("#signature").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg"]
    });
    
    $("#land_owner_signature").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg"]
    });
    
    $("#bank_passbook").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#aadhaar_card").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    
    $("#ncla_certificate").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#land_records").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#land_document").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#agreement_copy").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
});
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form method="POST" action="<?= base_url('spservices/kaac_farmer/registration/submitfiles') ?>"
            enctype="multipart/form-data">
            <input value="<?= $obj_id ?>" name="obj_id" type="hidden" />


            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b><?= $pageTitle ?><br>
                            <b></h4>
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
                        <legend class="h5">ATTACH ENCLOSURE(S) / সংলগ্নক সমূহ </legend>
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
                                            <td>Upload Farmer's Passport Photo <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="photo_type" class="form-control">
                                                    <option value="Passport size photograph"
                                                        <?=($photo_type === 'Passport size photograph')?'selected':''?>>
                                                        Passport size photograph</option>
                                                </select>
                                                <?= form_error("photo_type") ?>
                                            </td>
                                            <td>
                                                <input name="photo_old" value="<?=$photo?>" type="hidden" />
                                                <div class="file-loading">
                                                    <input id="photo" name="photo" type="file" />
                                                </div>

                                                <div class="row mt-1">

                                                    <div class="col-sm-4">
                                                        <?php if(strlen($photo)){ ?>
                                                        <a href="<?=base_url($photo)?>"
                                                            class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>View/Download
                                                        </a>
                                                        <?php }//End of if ?>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div id="live_photo_div" class="row text-center mt-"
                                                            style="display:none;">
                                                            <div id="my_camera" class="col-md-4 text-center"></div>
                                                            <div class="col-md-4 text-center">
                                                                <img id="captured_photo" src="<?=base_url('assets/plugins/webcamjs/no-photo.png')?>" style="width: 320px; height: 240px;" />
                                                            </div>
                                                            <input id="photo_data" name="photo_data" value=""
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
                                            <td>Upload Farmer's Signature/Thumb Impression<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="signature_type" class="form-control">
                                                    <option value="Signature"
                                                        <?=($signature === 'Signature')?'selected':''?>>
                                                        Signature</option>
                                                </select>
                                                <?= form_error("signature_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="signature" name="signature" type="file" />
                                                </div>
                                                <?php if(strlen($signature)){ ?>
                                                <a href="<?=base_url($signature)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Land Owner Signature<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="land_owner_signature_type" class="form-control">
                                                    <option value="land_owner_signature"
                                                        <?=($land_owner_signature === 'Land Owner Signature')?'selected':''?>>
                                                        Land Owner Signature</option>
                                                </select>
                                                <?= form_error("land_owner_signature_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="land_owner_signature" name="land_owner_signature" type="file" />
                                                </div>
                                                <?php if(strlen($land_owner_signature)){ ?>
                                                <a href="<?=base_url($land_owner_signature)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Photocopy of Bank Passbook of Applicant<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="bank_passbook_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Copy of Bank Passbook"
                                                        <?=($bank_passbook_type === 'Copy of Bank Passbook')?'selected':''?>>
                                                        Copy of Bank Passbook</option>
                                                   
                                                </select>
                                                <?= form_error("bank_passbook_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="bank_passbook" name="bank_passbook" type="file" />
                                                </div>
                                                <?php if(strlen($bank_passbook)){ ?>
                                                <a href="<?=base_url($bank_passbook)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="bank_passbook_old" value="<?=$bank_passbook?>"
                                                    type="hidden" />
                                                <input class="bank_passbook" type="hidden" name="bank_passbook_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('bank_passbook'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Aadhar Card<span
                                                    class="text-danger">*</span></td>
                                            <td>
                                                <select name="aadhaar_card_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Aadhaar Card"
                                                        <?=($aadhaar_card_type === 'Aadhaar Card')?'selected':''?>>
                                                        Aadhaar Card</option>
                                                    
                                                </select>
                                                <?= form_error("aadhaar_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="aadhaar_card" name="aadhaar_card" type="file" />
                                                </div>
                                                <?php if(strlen($aadhaar_card)){ ?>
                                                <a href="<?=base_url($aadhaar_card)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="aadhaar_card_old" value="<?=$aadhaar_card?>"
                                                    type="hidden" />
                                                <input class="aadhaar_card" type="hidden" name="aadhaar_card_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('aadhaar_card'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Certificate of Non-Cadastral Land Area<span
                                                    class="text-danger">*</span></td>
                                            <td>
                                                <select name="ncla_certificate_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Certificate of Non-Cadastral Land Area"
                                                        <?=($ncla_certificate_type === 'Certificate of Non-Cadastral Land Area')?'selected':''?>>
                                                        Certificate of Non-Cadastral Land Area</option>
                                                    
                                                </select>
                                                <?= form_error("ncla_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="ncla_certificate" name="ncla_certificate" type="file" />
                                                </div>
                                                <?php if(strlen($ncla_certificate)){ ?>
                                                <a href="<?=base_url($ncla_certificate)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="ncla_certificate_old" value="<?=$ncla_certificate?>"
                                                    type="hidden" />
                                                <input class="ncla_certificate" type="hidden" name="ncla_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('ncla_certificate'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Certificate of Non-digitization/Non-integration of Land Records<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="land_records_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Certificate of Non-digitization/Non-integration of Land Records"
                                                        <?=($land_records_type === 'Certificate of Non-digitization/Non-integration of Land Records')?'selected':''?>>
                                                        Certificate of Non-digitization/Non-integration of Land Records</option>
                                                </select>
                                                <?= form_error("land_records_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="land_records" name="land_records"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($land_records)){ ?>
                                                <a href="<?=base_url($land_records)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="land_records_old" value="<?=$land_records?>"
                                                    type="hidden" />
                                                <input class="land_records" type="hidden"
                                                    name="land_records_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('land_records'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Land Document
                                                <!-- <span class="text-danger">*</span> -->
                                            </td>
                                            <td>
                                                <select name="land_document_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Copy of Land Patta (In case of Patta Holder)"
                                                        <?=($land_document_type === 'Copy of Land Patta (In case of Patta Holder)')?'selected':''?>>
                                                        Copy of Land Patta (In case of Patta Holder)</option>
                                                </select>
                                                <?= form_error("land_document_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="land_document" name="land_document"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($land_document)){ ?>
                                                <a href="<?=base_url($land_document)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="land_document_old" value="<?=$land_document?>"
                                                    type="hidden" />
                                                <input class="land_document" type="hidden"
                                                    name="land_document_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('land_document'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Agreement Copy (In case of contract farming)
                                                <!-- <span class="text-danger">*</span> -->
                                            </td>
                                            <td>
                                                <select name="agreement_copy_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Agreement Copy (In case of contract farming)"
                                                        <?=($agreement_copy_type === 'Agreement Copy (In case of contract farming)')?'selected':''?>>
                                                        Agreement Copy (In case of contract farming)</option>
                                                </select>
                                                <?= form_error("agreement_copy_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="agreement_copy" name="agreement_copy"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($agreement_copy)){ ?>
                                                <a href="<?=base_url($agreement_copy)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="agreement_copy_old"
                                                    value="<?=$agreement_copy?>" type="hidden" />
                                                <input class="agreement_copy" type="hidden"
                                                    name="agreement_copy_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('agreement_copy'); ?>
                                            </td>
                                        </tr>                                     
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?= site_url('spservices/kaac_farmer/registration/index/' . $obj_id) ?>"
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