<?php

$passport_photo_type_frm = set_value("passport_photo_type");
$signature_type_frm = set_value("signature_type");
$registration_card_type_frm = set_value("registration_card_type");
$admit_card_type_frm = set_value("admit_card_type");
$affidavit_type_frm = set_value("affidavit_type");
$pass_certificate_type_frm = set_value("pass_certificate_type");
$marksheet_type_frm = set_value("marksheet_type");
$other_doc_type_frm = set_value("other_doc_type");
$soft_copy_type_frm = set_value("soft_copy_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');
$passport_photo_frm = $uploadedFiles['passport_photo_old'] ?? null;
$signature_frm = $uploadedFiles['signature_old'] ?? null;
$registration_card_frm = $uploadedFiles['registration_card_old'] ?? null;
$admit_card_frm = $uploadedFiles['admit_card_old'] ?? null;
$affidavit_frm = $uploadedFiles['affidavit_old'] ?? null;
$pass_certificate_frm = $uploadedFiles['pass_certificate_old'] ?? null;
$marksheet_frm = $uploadedFiles['marksheet_old'] ?? null;
$other_doc_frm = $uploadedFiles['other_doc_old'] ?? null;
$soft_copy_frm = $uploadedFiles['soft_copy_old'] ?? null;

$passport_photo_type_db = $dbrow->form_data->passport_photo_type ?? null;
$signature_type_db = $dbrow->form_data->signature_type ?? null;
$registration_card_type_db = $dbrow->form_data->registration_card_type ?? null;
$admit_card_type_db = $dbrow->form_data->admit_card_type ?? null;
$affidavit_type_db = $dbrow->form_data->affidavit_type ?? null;
$pass_certificate_type_db = $dbrow->form_data->pass_certificate_type ?? null;
$marksheet_type_db = $dbrow->form_data->marksheet_type ?? null;
$other_doc_type_db = $dbrow->form_data->other_doc_type ?? null;
$soft_copy_type_db = $dbrow->form_data->soft_copy_type ?? null;

$passport_photo_db = $dbrow->form_data->passport_photo ?? null;
$signature_db = $dbrow->form_data->signature ?? null;
$registration_card_db = $dbrow->form_data->registration_card ?? null;
$admit_card_db = $dbrow->form_data->admit_card ?? null;
$affidavit_db = $dbrow->form_data->affidavit ?? null;
$pass_certificate_db = $dbrow->form_data->pass_certificate ?? null;
$marksheet_db = $dbrow->form_data->marksheet ?? null;
$other_doc_db = $dbrow->form_data->other_doc ?? null;
$soft_copy_db = $dbrow->form_data->soft_copy ?? null;

$passport_photo_type = strlen($passport_photo_type_frm) ? $passport_photo_type_frm : $passport_photo_type_db;
$signature_type = strlen($signature_type_frm) ? $signature_type_frm : $signature_type_db;
$registration_card_type = strlen($registration_card_type_frm) ? $registration_card_type_frm : $registration_card_type_db;
$admit_card_type = strlen($admit_card_type_frm) ? $admit_card_type_frm : $admit_card_type_db;
$affidavit_type = strlen($affidavit_type_frm) ? $affidavit_type_frm : $affidavit_type_db;
$pass_certificate_type = strlen($pass_certificate_type_frm) ? $pass_certificate_type_frm : $pass_certificate_type_db;
$marksheet_type = strlen($marksheet_type_frm) ? $marksheet_type_frm : $marksheet_type_db;
$other_doc_type = strlen($other_doc_type_frm) ? $other_doc_type_frm : $other_doc_type_db;
$soft_copy_type = strlen($soft_copy_type_frm) ? $soft_copy_type_frm : $soft_copy_type_db;

$passport_photo = strlen($passport_photo_frm) ? $passport_photo_frm : $passport_photo_db;
$signature = strlen($signature_frm) ? $signature_frm : $signature_db;
$registration_card = strlen($registration_card_frm) ? $registration_card_frm : $registration_card_db;
$admit_card = strlen($admit_card_frm) ? $admit_card_frm : $admit_card_db;
$affidavit = strlen($affidavit_frm) ? $affidavit_frm : $affidavit_db;
$pass_certificate = strlen($pass_certificate_frm) ? $pass_certificate_frm : $pass_certificate_db;
$marksheet = strlen($marksheet_frm) ? $marksheet_frm : $marksheet_db;
$other_doc = strlen($other_doc_frm) ? $other_doc_frm : $other_doc_db;
$soft_copy = strlen($soft_copy_frm) ? $soft_copy_frm : $soft_copy_db;
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
        var passportPhoto = parseInt(<?= strlen($passport_photo) ? 1 : 0 ?>);
        $("#passport_photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: passportPhoto ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg"]
        });

        var applSign = parseInt(<?= strlen($signature) ? 1 : 0 ?>);
        $("#signature").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: applSign ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg"]
        });

        var pass_certificateFile = parseInt(<?= strlen($pass_certificate) ? 1 : 0 ?>);
        $("#pass_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: pass_certificateFile ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var idProof = parseInt(<?= strlen($admit_card) ? 1 : 0 ?>);
        $("#admit_card").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: idProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var addressProof = parseInt(<?= strlen($registration_card) ? 1 : 0 ?>);
        $("#registration_card").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: addressProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#marksheet").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#affidavit").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#other_doc").fileinput({
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
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form method="POST" action="<?= base_url('spservices/ahsec_correction/ahseccor/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?= $obj_id ?>" name="obj_id" type="hidden" />
            <input name="passport_photo_old" value="<?= $passport_photo ?>" type="hidden" />
            <input name="signature_old" value="<?= $signature ?>" type="hidden" />
            <input name="affidavit_old" value="<?= $affidavit ?>" type="hidden" />
            <input name="pass_certificate_old" value="<?= $pass_certificate ?>" type="hidden" />
            <input name="marksheet_old" value="<?= $marksheet ?>" type="hidden" />
            <input name="admit_card_old" value="<?= $admit_card ?>" type="hidden" />
            <input name="registration_card_old" value="<?= $registration_card ?>" type="hidden" />
            <input name="other_doc_old" value="<?= $other_doc ?>" type="hidden" />
            <input name="soft_copy_old" value="<?= $soft_copy ?>" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b><?= $pageTitle ?><br>
                            <?php switch ($pageTitleId) {
                                case "AHSECCRC":
                                    echo '( পঞ্জীয়ন কাৰ্ডত সংশোধনৰ বাবে আবেদন )';
                                    break;
                                case "AHSECCADM":
                                    echo '( এডমিট কাৰ্ডত সংশোধনৰ বাবে আবেদন )';
                                    break;
                                case "AHSECCMRK":
                                    echo '( মাৰ্কশ্বীটত সংশোধনৰ বাবে আবেদন )';
                                    break;
                                case "AHSECCPC":
                                    echo '( উত্তীৰ্ণ প্ৰমাণপত্ৰত সংশোধনৰ বাবে আবেদন )';
                                    break;
                            }
                            ?><b></h4>
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
                                            <td>Passport size photograph [Only .JPEG or .JPG File Allowed, Max file size: 1 MB]<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="passport_photo_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Photograph" <?= ($passport_photo_type === 'Photograph') ? 'selected' : '' ?>>Passport size photograph</option>
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
                                                <!-- <input class="passport_photo" type="hidden" name="passport_photo_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('passport_photo'); ?> -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Applicant Signature [Only .JPEG or .JPG File Allowed, Max file size: 1 MB]<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="signature_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Applicant Signature" <?= ($signature_type === 'Applicant Signature') ? 'selected' : '' ?>>Applicant Signature</option>
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
                                                <!-- <input class="signature" type="hidden" name="signature_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('signature'); ?> -->
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>HS Registration Card <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="registration_card_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Registration Card" <?= ($registration_card_type === 'Registration Card') ? 'selected' : '' ?>>Registration Card</option>
                                                </select>
                                                <?= form_error("registration_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="registration_card" name="registration_card" type="file" />

                                                </div>
                                                <?php if (strlen($registration_card)) { ?>
                                                    <a href="<?= base_url($registration_card) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="registration_card" type="hidden" name="registration_card_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('registration_card'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?php if ($pageTitleId == "AHSECCRC") { ?>HSLC/ 10th Admit Card<?php } ?><?php if ($pageTitleId == "AHSECCADM" || $pageTitleId == "AHSECCMRK" || $pageTitleId == "AHSECCPC") { ?>HS Admit Card<?php } ?><span class="text-danger">*</span></td>
                                            <td>
                                                <select name="admit_card_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Admit Card" <?= ($admit_card_type === 'Admit Card') ? 'selected' : '' ?>>Admit Card</option>
                                                </select>
                                                <?= form_error("admit_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="admit_card" name="admit_card" type="file" />
                                                </div>
                                                <?php if (strlen($admit_card)) { ?>
                                                    <a href="<?= base_url($admit_card) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="admit_card" type="hidden" name="admit_card_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('admit_card'); ?>
                                            </td>
                                        </tr>
                                        <?php if ($pageTitleId == "AHSECCRC") { ?>
                                            <tr>
                                                <td>HS Admit Card</td>
                                                <td>
                                                    <select name="other_doc_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="HS admit card" <?= ($other_doc_type === 'HS admit card') ? 'selected' : '' ?>>HS admit card</option>
                                                    </select>
                                                    <?= form_error("other_doc_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="other_doc" name="other_doc" type="file" />
                                                    </div>
                                                    <?php if (strlen($other_doc)) { ?>
                                                        <a href="<?= base_url($other_doc) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="other_doc" type="hidden" name="other_doc_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('other_doc'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Court Affidavit [Required in case applying for registration correction after 10 years of AHSEC registration] </td>
                                                <td>
                                                    <select name="affidavit_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Affidavit" <?= ($affidavit_type === 'Affidavit') ? 'selected' : '' ?>>Affidavit</option>

                                                    </select>
                                                    <?= form_error("affidavit_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="affidavit" name="affidavit" type="file" />
                                                    </div>
                                                    <?php if (strlen($affidavit)) { ?>
                                                        <a href="<?= base_url($affidavit) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="affidavit" type="hidden" name="affidavit_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('affidavit'); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if (($pageTitleId == "AHSECCMRK") || ($pageTitleId == "AHSECCPC")) { ?>
                                            <tr>
                                                <td>HS Marksheet <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="marksheet_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Marksheet" <?= ($marksheet_type === 'Marksheet') ? 'selected' : '' ?>>Marksheet</option>
                                                    </select>
                                                    <?= form_error("marksheet_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="marksheet" name="marksheet" type="file" />
                                                    </div>
                                                    <?php if (strlen($marksheet)) { ?>
                                                        <a href="<?= base_url($marksheet) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="marksheet" type="hidden" name="marksheet_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('marksheet'); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if ($pageTitleId == "AHSECCPC") { ?>
                                            <tr>
                                                <td>HS Pass Certificate <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="pass_certificate_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Pass Certificate" <?= ($pass_certificate_type === 'Pass Certificate') ? 'selected' : '' ?>>Pass Certificate</option>
                                                    </select>
                                                    <?= form_error("pass_certificate_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="pass_certificate" name="pass_certificate" type="file" />
                                                    </div>
                                                    <?php if (strlen($pass_certificate)) { ?>
                                                        <a href="<?= base_url($pass_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="pass_certificate" type="hidden" name="pass_certificate_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('pass_certificate'); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if ($this->slug == 'userrr') { ?>
                                            <tr>
                                                <td>Soft copy of the applicant form<span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="soft_copy_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Soft copy of the applicant form" <?= ($soft_copy_type === 'Soft copy of the applicant form') ? 'selected' : '' ?>>Soft copy of the applicant form</option>
                                                    </select>
                                                    <?= form_error("soft_copy_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="soft_copy" name="soft_copy" type="file" />
                                                    </div>
                                                    <?php if (strlen($soft_copy)) { ?>
                                                        <a href="<?= base_url($soft_copy) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } //End of if 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?= site_url('spservices/ahsec_correction/ahseccor/index/' . $obj_id) ?>" class="btn btn-primary">
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