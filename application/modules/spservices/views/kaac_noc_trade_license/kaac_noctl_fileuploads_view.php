<?php

//new

// pre($dbrow);

$signature_type_frm = set_value("signature_type");
$signature_frm = $uploadedFiles['signature_old'] ?? null;
$signature_type_db = $dbrow->form_data->signature_type ?? null;
$signature_db = $dbrow->form_data->signature ?? null;
$signature = strlen($signature_frm) ? $signature_frm : $signature_db;
$signature_type = strlen($signature_type_frm) ? $signature_type_frm : $signature_type_db;


$passport_photo_type_frm = set_value("passport_photo_type");
$passport_photo_frm = $uploadedFiles['passport_photo_old'] ?? null;
$passport_photo_type_db = $dbrow->form_data->passport_photo_type ?? null;
$passport_photo_db = $dbrow->form_data->passport_photo ?? null;
$passport_photo = strlen($passport_photo_frm) ? $passport_photo_frm : $passport_photo_db;
$passport_photo_type = strlen($passport_photo_type_frm) ? $passport_photo_type_frm : $passport_photo_type_db;


$address_proof_type_frm = set_value("address_proof_type");
$address_proof_frm = $uploadedFiles['address_proof_old'] ?? null;
$address_proof_type_db = $dbrow->form_data->address_proof_type ?? null;
$address_proof_db = $dbrow->form_data->address_proof ?? null;
$address_proof = strlen($address_proof_frm) ? $address_proof_frm : $address_proof_db;
$address_proof_type = strlen($address_proof_type_frm) ? $address_proof_type_frm : $address_proof_type_db;


$identity_proof_type_frm = set_value("identity_proof_type");
$identity_proof_frm = $uploadedFiles['identity_proof_old'] ?? null;
$identity_proof_type_db = $dbrow->form_data->identity_proof_type ?? null;
$identity_proof_db = $dbrow->form_data->identity_proof ?? null;
$identity_proof = strlen($identity_proof_frm) ? $identity_proof_frm : $identity_proof_db;
$identity_proof_type = strlen($identity_proof_type_frm) ? $identity_proof_type_frm : $identity_proof_type_db;


$house_tax_receipt_type_frm = set_value("house_tax_receipt_type");
$house_tax_receipt_frm = $uploadedFiles['house_tax_receipt_old'] ?? null;
$house_tax_receipt_type_db = $dbrow->form_data->house_tax_receipt_type ?? null;
$house_tax_receipt_db = $dbrow->form_data->house_tax_receipt ?? null;
$house_tax_receipt = strlen($house_tax_receipt_frm) ? $house_tax_receipt_frm : $house_tax_receipt_db;
$house_tax_receipt_type = strlen($house_tax_receipt_type_frm) ? $house_tax_receipt_type_frm : $house_tax_receipt_type_db;


$business_reg_certificate_type_frm = set_value("business_reg_certificate_type");
$business_reg_certificate_frm = $uploadedFiles['business_reg_certificate_old'] ?? null;
$business_reg_certificate_type_db = $dbrow->form_data->business_reg_certificate_type ?? null;
$business_reg_certificate_db = $dbrow->form_data->business_reg_certificate ?? null;
$business_reg_certificate = strlen($business_reg_certificate_frm) ? $business_reg_certificate_frm : $business_reg_certificate_db;
$business_reg_certificate_type = strlen($business_reg_certificate_type_frm) ? $business_reg_certificate_type_frm : $business_reg_certificate_type_db;



$mbtc_room_rent_deposit_type_frm = set_value("mbtc_room_rent_deposit_type");
$mbtc_room_rent_deposit_frm = $uploadedFiles['mbtc_room_rent_deposit_old'] ?? null;
$mbtc_room_rent_deposit_type_db = $dbrow->form_data->mbtc_room_rent_deposit_type ?? null;
$mbtc_room_rent_deposit_db = $dbrow->form_data->mbtc_room_rent_deposit ?? null;
$mbtc_room_rent_deposit = strlen($mbtc_room_rent_deposit_frm) ? $mbtc_room_rent_deposit_frm : $mbtc_room_rent_deposit_db;
$mbtc_room_rent_deposit_type = strlen($mbtc_room_rent_deposit_type_frm) ? $mbtc_room_rent_deposit_type_frm : $mbtc_room_rent_deposit_type_db;


$consideration_letter_type_frm = set_value("consideration_letter_type");
$consideration_letter_frm = $uploadedFiles['consideration_letter_old'] ?? null;
$consideration_letter_type_db = $dbrow->form_data->consideration_letter_type ?? null;
$consideration_letter_db = $dbrow->form_data->consideration_letter ?? null;
$consideration_letter = strlen($consideration_letter_frm) ? $consideration_letter_frm : $consideration_letter_db;
$consideration_letter_type = strlen($consideration_letter_type_frm) ? $consideration_letter_type_frm : $consideration_letter_type_db;



//end

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
<script type="text/javascript">
    $(document).ready(function() {


        $("#house_tax_receipt").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        $("#business_reg_certificate").fileinput({
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
        $("#identity_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#mbtc_room_rent_deposit").fileinput({
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
        $("#passport_photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png"]
        });
        $("#signature").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png"]
        });

    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form method="POST" action="<?= base_url('spservices/kaac_noc_trade_license/registration/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?= $obj_id ?>" name="obj_id" type="hidden" />

            <input name="address_proof_old" value="<?= $address_proof ?>" type="hidden" />
            <input name="identity_proof_old" value="<?= $identity_proof ?>" type="hidden" />
            <input name="house_tax_receipt_old" value="<?= $house_tax_receipt ?>" type="hidden" />
            <input name="business_reg_certificate_old" value="<?= $business_reg_certificate ?>" type="hidden" />
            <input name="mbtc_room_rent_deposit_old" value="<?= $mbtc_room_rent_deposit ?>" type="hidden" />
            <input name="consideration_letter_old" value="<?= $consideration_letter ?>" type="hidden" />
            <input name="passport_photo_old" value="<?= $passport_photo ?>" type="hidden" />
            <input name="signature_old" value="<?= $signature ?>" type="hidden" />

            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b><?= $pageTitle ?><br>
                    অনাপত্তি বাণিজ্য অনুজ্ঞাপত্ৰ প্ৰদান <b>
                    </h4>
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

                                        <tr>
                                            <td>Passport Size Photo <span class="text-danger">*</span></td>
                                            <td>
                                                
                                                <select name="passport_photo_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="6919-6902" <?= ($passport_photo_type === '6919-6902') ? 'selected' : '' ?>>Passport Photo</option>

                                                </select>
                                                <?= form_error("passport_photo_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="passport_photo" name="passport_photo" type="file" />
                                                </div>
                                                <?php if (strlen($passport_photo)) { ?>
                                                    <a href="<?= base_url($passport_photo) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="passport_photo" type="hidden" name="passport_photo_temp">

                                                <?= $this->digilocker_api->digilocker_fetch_btn('passport_photo'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Address Proof <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="address_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="7207-7206" <?= ($address_proof_type === '7207-7206') ? 'selected' : '' ?>>Address Proof</option>

                                                </select>
                                                <?= form_error("address_proof_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="address_proof" name="address_proof" type="file" />
                                                </div>
                                                <?php if (strlen($address_proof)) { ?>
                                                    <a href="<?= base_url($address_proof) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="address_proof" type="hidden" name="address_proof_temp">

                                                <?= $this->digilocker_api->digilocker_fetch_btn('address_proof'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Identity proof <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="identity_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="159-5346" <?= ($identity_proof_type === '159-5346') ? 'selected' : '' ?>>Identity Proof</option>

                                                </select>
                                                <?= form_error("identity_proof_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="identity_proof" name="identity_proof" type="file" />
                                                </div>
                                                <?php if (strlen($identity_proof)) { ?>
                                                    <a href="<?= base_url($identity_proof) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="identity_proof" type="hidden" name="identity_proof_temp">

                                                <?= $this->digilocker_api->digilocker_fetch_btn('identity_proof'); ?>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td> House Tax Receipt</td>
                                            <td>
                                                <select name="house_tax_receipt_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="8200-8204" <?= ($house_tax_receipt_type === '8200-8204') ? 'selected' : '' ?>>House Tax Receipt</option>
                                                </select>
                                                <?= form_error("house_tax_receipt_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="house_tax_receipt" name="house_tax_receipt" type="file" />
                                                </div>
                                                <?php if (strlen($house_tax_receipt)) { ?>
                                                    <a href="<?= base_url($house_tax_receipt) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="house_tax_receipt" type="hidden" name="house_tax_receipt_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('house_tax_receipt'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Copy of current Business Registration Certificate </td>
                                            <td>
                                                <select name="business_reg_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="8224-8225" <?= ($business_reg_certificate_type === '8224-8225') ? 'selected' : '' ?>>Copy of current Business Registration Certificate</option>
                                                </select>
                                                <?= form_error("business_reg_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="business_reg_certificate" name="business_reg_certificate" type="file" />
                                                </div>
                                                <?php if (strlen($business_reg_certificate)) { ?>
                                                    <a href="<?= base_url($business_reg_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="business_reg_certificate" type="hidden" name="business_reg_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('business_reg_certificate'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Valid MBTC Room rent deposit </td>
                                            <td>
                                                <select name="mbtc_room_rent_deposit_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="8226-8227" <?= ($mbtc_room_rent_deposit_type === '8226-8227') ? 'selected' : '' ?>>Valid MBTC Room rent deposit</option>
                                                </select>
                                                <?= form_error("mbtc_room_rent_deposit_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="mbtc_room_rent_deposit" name="mbtc_room_rent_deposit" type="file" />
                                                </div>
                                                <?php if (strlen($mbtc_room_rent_deposit)) { ?>
                                                    <a href="<?= base_url($mbtc_room_rent_deposit) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="mbtc_room_rent_deposit" type="hidden" name="mbtc_room_rent_deposit_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('mbtc_room_rent_deposit'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Special reason for Consideration letter </td>
                                            <td>
                                                <select name="consideration_letter_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="8228-8229" <?= ($consideration_letter_type === '8228-8229') ? 'selected' : '' ?>>Special reason for Consideration letter</option>
                                                </select>
                                                <?= form_error("consideration_letter_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="consideration_letter" name="consideration_letter" type="file" />
                                                </div>
                                                <?php if (strlen($consideration_letter)) { ?>
                                                    <a href="<?= base_url($consideration_letter) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="consideration_letter" type="hidden" name="consideration_letter_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('consideration_letter'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Applicant Signature </td>
                                            <td>
                                                <select name="signature_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="6865-6866" <?= ($signature_type === '6865-6866') ? 'selected' : '' ?>>Signature</option>

                                                </select>
                                                <?= form_error("signature_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="signature" name="signature" type="file" />
                                                </div>
                                                <?php if (strlen($signature)) { ?>
                                                    <a href="<?= base_url($signature) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="signature" type="hidden" name="signature_temp">

                                                <?= $this->digilocker_api->digilocker_fetch_btn('signature'); ?>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?= site_url('spservices/kaac_noc_trade_license/registration/index/' . $obj_id) ?>" class="btn btn-primary">
                        <i class="fa fa-angle-double-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-angle-double-right"></i> Save &amp Next
                    </button>
                    <button type="reset" class="btn btn-danger">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>