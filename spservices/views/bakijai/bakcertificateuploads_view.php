<?php

$passport_photo_type_frm = set_value("passport_photo_type");
$affidavit_type_frm = set_value("affidavit_type");
$court_fee_type_frm = set_value("court_fee_type");
$paymentreceipt_type_frm = set_value("paymentreceipt_type");
$other_doc_type_frm = set_value("other_doc_type");
$soft_copy_type_frm = set_value("soft_copy_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');
$passport_photo_frm = $uploadedFiles['passport_photo_old']??null;
$affidavit_frm = $uploadedFiles['affidavit_old']??null;
$court_fee_frm = $uploadedFiles['court_fee_old'] ?? null;
$paymentreceipt_frm = $uploadedFiles['paymentreceipt_old'] ?? null;
$other_doc_frm = $uploadedFiles['other_doc_old'] ?? null;
$soft_copy_frm = $uploadedFiles['soft_copy_old'] ?? null;

$passport_photo_type_db = $dbrow->form_data->passport_photo_type??null;
$affidavit_type_db = $dbrow->form_data->affidavit_type??null;
$court_fee_type_db = $dbrow->form_data->court_fee_type ?? null;
$paymentreceipt_type_db = $dbrow->form_data->paymentreceipt_type ?? null;
$other_doc_type_db = $dbrow->form_data->other_doc_type ?? null;
$soft_copy_type_db = $dbrow->form_data->soft_copy_type ?? null;

$passport_photo_db = $dbrow->form_data->passport_photo??null;
$affidavit_db = $dbrow->form_data->affidavit??null;
$court_fee_db = $dbrow->form_data->court_fee ?? null;
$paymentreceipt_db = $dbrow->form_data->paymentreceipt ?? null;
$other_doc_db = $dbrow->form_data->other_doc ?? null;
$soft_copy_db = $dbrow->form_data->soft_copy ?? null;

$passport_photo_type = strlen($passport_photo_type_frm)?$passport_photo_type_frm:$passport_photo_type_db;
$affidavit_type = strlen($affidavit_type_frm)?$affidavit_type_frm:$affidavit_type_db;
$court_fee_type = strlen($court_fee_type_frm) ? $court_fee_type_frm : $court_fee_type_db;
$paymentreceipt_type = strlen($paymentreceipt_type_frm) ? $paymentreceipt_type_frm : $paymentreceipt_type_db;
$other_doc_type = strlen($other_doc_type_frm) ? $other_doc_type_frm : $other_doc_type_db;
$soft_copy_type = strlen($soft_copy_type_frm) ? $soft_copy_type_frm : $soft_copy_type_db;

$passport_photo = strlen($passport_photo_frm)?$passport_photo_frm:$passport_photo_db;
$affidavit = strlen($affidavit_frm)?$affidavit_frm:$affidavit_db;
$court_fee = strlen($court_fee_frm) ? $court_fee_frm : $court_fee_db;
$paymentreceipt = strlen($paymentreceipt_frm) ? $paymentreceipt_frm : $paymentreceipt_db;
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
        var passportPhoto = parseInt(<?=strlen($passport_photo)?1:0?>);
        $("#passport_photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: passportPhoto?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg"]
        });

        var affidAvit = parseInt(<?=strlen($affidavit)?1:0?>);
        $("#affidavit").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: affidAvit?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var courtFee = parseInt(<?= strlen($court_fee) ? 1 : 0 ?>);
        $("#court_fee").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: courtFee?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var paymentRcpt = parseInt(<?= strlen($paymentreceipt) ? 1 : 0 ?>);
        $("#paymentreceipt").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: paymentRcpt?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var otherDoc = parseInt(<?= strlen($other_doc) ? 1 : 0 ?>);
        $("#other_doc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: otherDoc?false:true,
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
        <form method="POST" action="<?= base_url('spservices/bakijai/bakcl/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?= $obj_id ?>" name="obj_id" type="hidden" />
            <input name="passport_photo_old" value="<?=$passport_photo?>" type="hidden" />
            <input name="affidavit_old" value="<?=$affidavit?>" type="hidden" />
            <input name="court_fee_old" value="<?= $court_fee ?>" type="hidden" />
            <input name="paymentreceipt_old" value="<?= $paymentreceipt ?>" type="hidden" />
            <input name="other_doc_old" value="<?= $other_doc ?>" type="hidden" />
            <input name="soft_copy_old" value="<?= $soft_copy ?>" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b>Application for Bakijai Clearance Certificate<br>
                            ( বাকিজাই আদায়ৰ প্রমান পত্রৰ বাবে আবেদন ) <b></h4>
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
                                            <th style="width:30%">Type of Enclosure</th>
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
                                            <td>Affidavit Regarding no Pending Bakijai case in Applicants name and in the name of his/her Father, Mother, Brothers, Sisters [Only PDF File Allowed, Max file size: 1 MB]<span class="text-danger">*</span></td>
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
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="affidavit" type="hidden" name="affidavit_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('affidavit'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Scan copy of Court Fee Stamp [Only PDF File Allowed, Max file size: 1 MB]<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="court_fee_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Scan copy of Court Fee Stamp" <?= ($court_fee_type === 'Scan copy of Court Fee Stamp') ? 'selected' : '' ?>>Scan copy of Court Fee Stamp</option>
                                                </select>
                                                <?= form_error("court_fee_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="court_fee" name="court_fee" type="file" />
                                                </div>
                                                <?php if (strlen($court_fee)) { ?>
                                                    <a href="<?= base_url($court_fee) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="court_fee" type="hidden" name="court_fee_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('court_fee'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Scan copy of upto date and payment receipt [Only PDF File Allowed, Max file size: 1 MB]<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="paymentreceipt_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Scan copy of upto date and payment receipt" <?= ($paymentreceipt_type === 'Scan copy of upto date and payment receipt') ? 'selected' : '' ?>>Scan copy of upto date and payment receipt</option>
                                                </select>
                                                <?= form_error("paymentreceipt_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="paymentreceipt" name="paymentreceipt" type="file" />
                                                </div>
                                                <?php if (strlen($paymentreceipt)) { ?>
                                                    <a href="<?= base_url($paymentreceipt) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="paymentreceipt" type="hidden" name="paymentreceipt_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('paymentreceipt'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Any other documents [Only PDF File Allowed, Max file size: 1 MB]<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="other_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Voter List" <?= ($other_doc_type === 'Voter List') ? 'selected' : '' ?>>Voter List</option>
                                                    <option value="Affidavit" <?= ($other_doc_type === 'Affidavit') ? 'selected' : '' ?>>Affidavit</option>
                                                    <option value="Any other document" <?= ($other_doc_type === 'Any other document') ? 'selected' : '' ?>>Any other document</option>
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
                                        <?php if ($this->slug == 'userrr') { ?>
                                            <tr>
                                                <td>Soft copy of the applicant form [Only PDF File Allowed, Max file size: 1 MB]<span class="text-danger">*</span></td>
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
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?= site_url('spservices/bakijai/bakcl/index/' . $obj_id) ?>" class="btn btn-primary">
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