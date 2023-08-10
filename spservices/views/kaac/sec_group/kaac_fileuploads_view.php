<?php
if($dbrow) {
$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;  

$signature = $dbrow->form_data->signature ?? '';
$signature_type = $dbrow->form_data->signature_type ?? '';

$photo = $dbrow->form_data->photo ?? '';
$photo_type = $dbrow->form_data->photo_type ?? '';

$identity_proof = $dbrow->form_data->identity_proof ?? '';
$identity_proof_type = $dbrow->form_data->identity_proof_type ?? '';   

$address_proof = $dbrow->form_data->address_proof ?? '';
$address_proof_type = $dbrow->form_data->address_proof_type ?? '';   


$house_tax_reciept = $dbrow->form_data->house_tax_reciept ?? '';
$house_tax_reciept_type = $dbrow->form_data->house_tax_reciept_type ?? '';   


$room_rent_deposite = $dbrow->form_data->room_rent_deposite ?? '';
$room_rent_deposite_type = $dbrow->form_data->room_rent_deposite_type ?? '';   


$consideration_letter = $dbrow->form_data->consideration_letter ?? '';
$consideration_letter_type = $dbrow->form_data->consideration_letter_type ?? '';   


$cur_business_copy_rc = $dbrow->form_data->cur_business_copy_rc ?? '';
$cur_business_copy_rc_type = $dbrow->form_data->cur_business_copy_rc_type ?? '';   


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
    $("#identity_proof").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#address_proof").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#house_tax_reciept").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#room_rent_deposite").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#consideration_letter").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#cur_business_copy_rc").fileinput({
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
        <form method="POST" action="<?= base_url('spservices/kaac_brc/registration/submitfiles') ?>"
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
                                            <td>Passport size photograph<span class="text-danger">*</span></td>
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
                                            <td>Signature<span class="text-danger">*</span></td>
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
                                            <td>Identity Proof<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="identity_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Copy of Aadhar Card"
                                                        <?=($identity_proof_type === 'Copy of Aadhar Card')?'selected':''?>>
                                                        Copy of Aadhar Card</option>
                                                    <option value="Copy of PAN Card"
                                                        <?=($identity_proof_type === 'Copy of PAN Card')?'selected':''?>>
                                                        Copy of PAN Card</option>
                                                    <option
                                                        value="Copy of Id proof (Voter ID, Aadhar Card, Pan Card, Passport, of the Employer)"
                                                        <?=($identity_proof_type === 'Copy of Id proof (Voter ID, Aadhar Card, Pan Card, Passport, of the Employer)')?'selected':''?>>
                                                        Copy of Id proof (Voter ID, Aadhar Card, Pan Card, Passport, of
                                                        the Employer)</option>
                                                </select>
                                                <?= form_error("identity_proof_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="identity_proof" name="identity_proof" type="file" />
                                                </div>
                                                <?php if(strlen($identity_proof)){ ?>
                                                <a href="<?=base_url($identity_proof)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="identity_proof_old" value="<?=$identity_proof?>"
                                                    type="hidden" />
                                                <input class="identity_proof" type="hidden" name="identity_proof_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('identity_proof'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Address Proof (DL/Passport/Bank Passbook/ Aadhar Card)<span
                                                    class="text-danger">*</span></td>
                                            <td>
                                                <select name="address_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Driving Licence"
                                                        <?=($address_proof_type === 'Driving Licence')?'selected':''?>>
                                                        Driving Licence</option>
                                                    <option value="Copy of Bank Passbook"
                                                        <?=($address_proof_type === 'Copy of Bank Passbook')?'selected':''?>>
                                                        Copy of Bank Passbook</option>
                                                    <option value="Copy of Aadhar Card"
                                                        <?=($address_proof_type === 'Copy of Aadhar Card')?'selected':''?>>
                                                        Copy of Aadhar Card</option>
                                                    <option value="Copy of Passport"
                                                        <?=($address_proof_type === 'Copy of Passport')?'selected':''?>>
                                                        Copy of Passport</option>
                                                </select>
                                                <?= form_error("address_proof_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="address_proof" name="address_proof" type="file" />
                                                </div>
                                                <?php if(strlen($address_proof)){ ?>
                                                <a href="<?=base_url($address_proof)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="address_proof_old" value="<?=$address_proof?>"
                                                    type="hidden" />
                                                <input class="address_proof" type="hidden" name="address_proof_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('address_proof'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>House Tax Receipt <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="house_tax_reciept_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="House Tax Receipt"
                                                        <?=($house_tax_reciept_type === 'House Tax Receipt')?'selected':''?>>
                                                        House Tax Receipt</option>
                                                </select>
                                                <?= form_error("house_tax_reciept_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="house_tax_reciept" name="house_tax_reciept"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($house_tax_reciept)){ ?>
                                                <a href="<?=base_url($house_tax_reciept)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="house_tax_reciept_old" value="<?=$house_tax_reciept?>"
                                                    type="hidden" />
                                                <input class="house_tax_reciept" type="hidden"
                                                    name="house_tax_reciept_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('house_tax_reciept'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Valid MBTC Room rent deposit
                                                <!-- <span class="text-danger">*</span> -->
                                            </td>
                                            <td>
                                                <select name="room_rent_deposite_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Valid MBTC Room rent deposit"
                                                        <?=($room_rent_deposite_type === 'Valid MBTC Room rent deposit')?'selected':''?>>
                                                        Valid MBTC Room rent deposit</option>
                                                </select>
                                                <?= form_error("room_rent_deposite_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="room_rent_deposite" name="room_rent_deposite"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($room_rent_deposite)){ ?>
                                                <a href="<?=base_url($room_rent_deposite)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="room_rent_deposite_old" value="<?=$room_rent_deposite?>"
                                                    type="hidden" />
                                                <input class="room_rent_deposite" type="hidden"
                                                    name="room_rent_deposite_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('room_rent_deposite'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Special reason for Consideration letter
                                                <!-- <span class="text-danger">*</span> -->
                                            </td>
                                            <td>
                                                <select name="consideration_letter_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Special reason for Consideration letter"
                                                        <?=($consideration_letter_type === 'Special reason for Consideration letter')?'selected':''?>>
                                                        Special reason for Consideration letter</option>
                                                </select>
                                                <?= form_error("consideration_letter_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="consideration_letter" name="consideration_letter"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($consideration_letter)){ ?>
                                                <a href="<?=base_url($consideration_letter)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="consideration_letter_old"
                                                    value="<?=$consideration_letter?>" type="hidden" />
                                                <input class="consideration_letter" type="hidden"
                                                    name="consideration_letter_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('consideration_letter'); ?>
                                            </td>
                                        </tr>

                                        <?php  if($dbrow->form_data->service_id == 'KRBC') {?>

                                        <tr>
                                            <td>Copy of current Business Registration Certificate
                                                <!-- <span class="text-danger">*</span> -->
                                            </td>
                                            <td>
                                                <select name="cur_business_copy_rc_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Copy of current Business Registration Certificate"
                                                        <?=($cur_business_copy_rc_type === 'Copy of current Business Registration Certificate')?'selected':''?>>
                                                        Copy of current Business Registration Certificate</option>
                                                </select>
                                                <?= form_error("cur_business_copy_rc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="cur_business_copy_rc" name="cur_business_copy_rc"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($cur_business_copy_rc)){ ?>
                                                <a href="<?=base_url($cur_business_copy_rc)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="cur_business_copy_rc_old"
                                                    value="<?=$cur_business_copy_rc?>" type="hidden" />
                                                <input class="cur_business_copy_rc" type="hidden"
                                                    name="cur_business_copy_rc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('cur_business_copy_rc'); ?>
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
                    <a href="<?= site_url('spservices/kaac_brc/registration/index/' . $obj_id) ?>"
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