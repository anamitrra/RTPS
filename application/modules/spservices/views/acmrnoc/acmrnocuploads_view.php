<?php

$passport_photo_type_frm = set_value("passport_photo_type");
$signature_type_frm = set_value("signature_type");
$ug_pg_diploma_type_frm = set_value("ug_pg_diploma_type");
$prc_type_frm = set_value("prc_type");
$mbbs_certificate_type_frm = set_value("mbbs_certificate_type");
$noc_dme_type_frm = set_value("noc_dme_type");
$seat_allt_letter_type_frm = set_value("seat_allt_letter_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');
$passport_photo_frm = $uploadedFiles['passport_photo_old'] ?? null;
$signature_frm = $uploadedFiles['signature_old'] ?? null;
$ug_pg_diploma_frm = $uploadedFiles['ug_pg_diploma_old'] ?? null;
$prc_frm = $uploadedFiles['prc_old'] ?? null;
$mbbs_certificate_frm = $uploadedFiles['mbbs_certificate_old'] ?? null;
$noc_dme_frm = $uploadedFiles['noc_dme_old'] ?? null;
$seat_allt_letter_frm = $uploadedFiles['seat_allt_letter_old'] ?? null;

$passport_photo_type_db = $dbrow->form_data->passport_photo_type ?? null;
$signature_type_db = $dbrow->form_data->signature_type ?? null;
$ug_pg_diploma_type_db = $dbrow->form_data->ug_pg_diploma_type ?? null;
$prc_type_db = $dbrow->form_data->prc_type ?? null;
$mbbs_certificate_type_db = $dbrow->form_data->mbbs_certificate_type ?? null;
$noc_dme_type_db = $dbrow->form_data->noc_dme_type ?? null;
$seat_allt_letter_type_db = $dbrow->form_data->seat_allt_letter_type ?? null;

$passport_photo_db = $dbrow->form_data->passport_photo ?? null;
$signature_db = $dbrow->form_data->signature ?? null;
$ug_pg_diploma_db = $dbrow->form_data->ug_pg_diploma ?? null;
$prc_db = $dbrow->form_data->prc ?? null;
$mbbs_certificate_db = $dbrow->form_data->mbbs_certificate ?? null;
$noc_dme_db = $dbrow->form_data->noc_dme ?? null;
$seat_allt_letter_db = $dbrow->form_data->seat_allt_letter ?? null;

$passport_photo_type = strlen($passport_photo_type_frm) ? $passport_photo_type_frm : $passport_photo_type_db;
$signature_type = strlen($signature_type_frm) ? $signature_type_frm : $signature_type_db;
$ug_pg_diploma_type = strlen($ug_pg_diploma_type_frm) ? $ug_pg_diploma_type_frm : $ug_pg_diploma_type_db;
$prc_type = strlen($prc_type_frm) ? $prc_type_frm : $prc_type_db;
$mbbs_certificate_type = strlen($mbbs_certificate_type_frm) ? $mbbs_certificate_type_frm : $mbbs_certificate_type_db;
$noc_dme_type = strlen($noc_dme_type_frm) ? $noc_dme_type_frm : $noc_dme_type_db;
$seat_allt_letter_type = strlen($seat_allt_letter_type_frm) ? $seat_allt_letter_type_frm : $seat_allt_letter_type_db;

$passport_photo = strlen($passport_photo_frm) ? $passport_photo_frm : $passport_photo_db;
// pre($passport_photo);
$signature = strlen($signature_frm) ? $signature_frm : $signature_db;
$ug_pg_diploma = strlen($ug_pg_diploma_frm) ? $ug_pg_diploma_frm : $ug_pg_diploma_db;
$prc = strlen($prc_frm) ? $prc_frm : $prc_db;
$mbbs_certificate = strlen($mbbs_certificate_frm) ? $mbbs_certificate_frm : $mbbs_certificate_db;
$noc_dme = strlen($noc_dme_frm) ? $noc_dme_frm : $noc_dme_db;
$seat_allt_letter = strlen($seat_allt_letter_frm) ? $seat_allt_letter_frm : $seat_allt_letter_db;
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

        var ugPgDiploma = parseInt(<?= strlen($ug_pg_diploma) ? 1 : 0 ?>);
        $("#ug_pg_diploma").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: ugPgDiploma ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var prcDoc = parseInt(<?= strlen($prc) ? 1 : 0 ?>);
        $("#prc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: prcDoc ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#mbbs_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#noc_dme").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#seat_allt_letter").fileinput({
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
        <form method="POST" action="<?= base_url('spservices/acmrnoc/noc/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?= $obj_id ?>" name="obj_id" type="hidden" />
            <input name="passport_photo_old" value="<?= $passport_photo ?>" type="hidden" />
            <input name="signature_old" value="<?= $signature ?>" type="hidden" />
            <input name="ug_pg_diploma_old" value="<?= $ug_pg_diploma ?>" type="hidden" />
            <input name="prc_old" value="<?= $prc ?>" type="hidden" />
            <input name="mbbs_certificate_old" value="<?= $mbbs_certificate ?>" type="hidden" />
            <input name="noc_dme_old" value="<?= $noc_dme ?>" type="hidden" />
            <input name="seat_allt_letter_old" value="<?= $seat_allt_letter ?>" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b>Application Form For No Objection Certificate - ACMR<br>
                            ( অনাপত্তি প্ৰমাণপত্ৰৰ বাবে আবেদন পত্ৰ - এচিএমআৰ) <b></h4>
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
                                            <td>Certificate of UG/PG/ Diploma<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="ug_pg_diploma_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Diploma" <?= ($ug_pg_diploma_type === 'Diploma') ? 'selected' : '' ?>>Certificate of UG/PG/ Diploma</option>
                                                </select>
                                                <?= form_error("ug_pg_diploma_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="ug_pg_diploma" name="ug_pg_diploma" type="file" />
                                                </div>
                                                <?php if (strlen($ug_pg_diploma)) { ?>
                                                    <a href="<?= base_url($ug_pg_diploma) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if  
                                                ?>
                                                <input class="ug_pg_diploma" type="hidden" name="ug_pg_diploma_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('ug_pg_diploma'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Permanent Registration certificate of Assam Medical Council<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="prc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="PRC" <?= ($prc_type === 'PRC') ? 'selected' : '' ?>>Permanent Registration certificate of Assam Medical Council</option>

                                                </select>
                                                <?= form_error("prc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="prc" name="prc" type="file" />
                                                </div>
                                                <?php if (strlen($prc)) { ?>
                                                    <a href="<?= base_url($prc) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if  
                                                ?>
                                                <input class="prc" type="hidden" name="prc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('prc'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Rural completion certificate (MBBS) if bond signed with Govt.</td>
                                            <td>
                                                <select name="mbbs_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Rural completion certificate (MBBS)" <?= ($mbbs_certificate_type === 'Rural completion certificate (MBBS)') ? 'selected' : '' ?>>Rural completion certificate (MBBS) if bond signed with Govt</option>
                                                </select>
                                                <?= form_error("mbbs_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="mbbs_certificate" name="mbbs_certificate" type="file" />
                                                </div>
                                                <?php if (strlen($mbbs_certificate)) { ?>
                                                    <a href="<?= base_url($mbbs_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="mbbs_certificate" type="hidden" name="mbbs_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('mbbs_certificate'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>NOC from Directorate of Medical Education (only for PG Holders under
                                                Bond)</td>
                                            <td>
                                                <select name="noc_dme_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="NOC from Directorate of Medical Education" <?= ($noc_dme_type === 'NOC from Directorate of Medical Education') ? 'selected' : '' ?>>NOC from Directorate of Medical Education</option>
                                                </select>
                                                <?= form_error("noc_dme_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="noc_dme" name="noc_dme" type="file" />
                                                </div>
                                                <?php if (strlen($noc_dme)) { ?>
                                                    <a href="<?= base_url($noc_dme) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="noc_dme" type="hidden" name="noc_dme_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('noc_dme'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Provisional Seat Allotment letter for All India Quota doctors</td>
                                            <td>
                                                <select name="seat_allt_letter_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Provisional Seat Allotment letter" <?= ($seat_allt_letter_type === 'Provisional Seat Allotment letter') ? 'selected' : '' ?>>Provisional Seat Allotment Letter</option>
                                                </select>
                                                <?= form_error("seat_allt_letter_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="seat_allt_letter" name="seat_allt_letter" type="file" />
                                                </div>
                                                <?php if (strlen($seat_allt_letter)) { ?>
                                                    <a href="<?= base_url($seat_allt_letter) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="seat_allt_letter" type="hidden" name="seat_allt_letter_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('seat_allt_letter'); ?>
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
                    <a href="<?= site_url('spservices/acmrnoc/noc/index/' . $obj_id) ?>" class="btn btn-primary">
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