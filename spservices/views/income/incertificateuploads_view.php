<?php

$address_proof_type_frm = set_value("address_proof_type");
$identity_proof_type_frm = set_value("identity_proof_type");
$revenuereceipt_type_frm = set_value("revenuereceipt_type");
$salaryslip_type_frm = set_value("salaryslip_type");
$other_doc_type_frm = set_value("other_doc_type");
$soft_copy_type_frm = set_value("soft_copy_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');
$address_proof_frm = $uploadedFiles['address_proof_old'] ?? null;
$identity_proof_frm = $uploadedFiles['identity_proof_old'] ?? null;
$revenuereceipt_frm = $uploadedFiles['revenuereceipt_old'] ?? null;
$salaryslip_frm = $uploadedFiles['salaryslip_old'] ?? null;
$other_doc_frm = $uploadedFiles['other_doc_old'] ?? null;
$soft_copy_frm = $uploadedFiles['soft_copy_old'] ?? null;

$address_proof_type_db = $dbrow->form_data->address_proof_type ?? null;
$identity_proof_type_db = $dbrow->form_data->identity_proof_type ?? null;
$revenuereceipt_type_db = $dbrow->form_data->revenuereceipt_type ?? null;
$salaryslip_type_db = $dbrow->form_data->salaryslip_type ?? null;
$other_doc_type_db = $dbrow->form_data->other_doc_type ?? null;
$soft_copy_type_db = $dbrow->form_data->soft_copy_type ?? null;

$address_proof_db = $dbrow->form_data->address_proof ?? null;
$identity_proof_db = $dbrow->form_data->identity_proof ?? null;
$revenuereceipt_db = $dbrow->form_data->revenuereceipt ?? null;
$salaryslip_db = $dbrow->form_data->salaryslip ?? null;
$other_doc_db = $dbrow->form_data->other_doc ?? null;
$soft_copy_db = $dbrow->form_data->soft_copy ?? null;

$address_proof_type = strlen($address_proof_type_frm) ? $address_proof_type_frm : $address_proof_type_db;
$identity_proof_type = strlen($identity_proof_type_frm) ? $identity_proof_type_frm : $identity_proof_type_db;
$revenuereceipt_type = strlen($revenuereceipt_type_frm) ? $revenuereceipt_type_frm : $revenuereceipt_type_db;
$salaryslip_type = strlen($salaryslip_type_frm) ? $salaryslip_type_frm : $salaryslip_type_db;
$other_doc_type = strlen($other_doc_type_frm) ? $other_doc_type_frm : $other_doc_type_db;
$soft_copy_type = strlen($soft_copy_type_frm) ? $soft_copy_type_frm : $soft_copy_type_db;

$address_proof = strlen($address_proof_frm) ? $address_proof_frm : $address_proof_db;
$identity_proof = strlen($identity_proof_frm) ? $identity_proof_frm : $identity_proof_db;
$revenuereceipt = strlen($revenuereceipt_frm) ? $revenuereceipt_frm : $revenuereceipt_db;
$salaryslip = strlen($salaryslip_frm) ? $salaryslip_frm : $salaryslip_db;
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
        var revenueReceiptFile = parseInt(<?= strlen($revenuereceipt) ? 1 : 0 ?>);
        $("#revenuereceipt").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: revenueReceiptFile ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var idProof = parseInt(<?= strlen($identity_proof) ? 1 : 0 ?>);
        $("#identity_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: idProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var addressProof = parseInt(<?= strlen($address_proof) ? 1 : 0 ?>);
        $("#address_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: addressProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#salaryslip").fileinput({
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
        <form method="POST" action="<?= base_url('spservices/income/inc/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?= $obj_id ?>" name="obj_id" type="hidden" />
            <input name="revenuereceipt_old" value="<?= $revenuereceipt ?>" type="hidden" />
            <input name="salaryslip_old" value="<?= $salaryslip ?>" type="hidden" />
            <input name="identity_proof_old" value="<?= $identity_proof ?>" type="hidden" />
            <input name="address_proof_old" value="<?= $address_proof ?>" type="hidden" />
            <input name="other_doc_old" value="<?= $other_doc ?>" type="hidden" />
            <input name="soft_copy_old" value="<?= $soft_copy ?>" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b>Application for Income Certificate<br>
                            ( আয়ৰ প্রমান পত্রৰ বাবে আবেদন ) <b></h4>
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
                                            <td>Address Proof<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="address_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Address Proof" <?= ($address_proof_type === 'Address Proof') ? 'selected' : '' ?>>Address Proof</option>
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
                                            <td>Identity Proof<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="identity_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Identity Proof" <?= ($identity_proof_type === 'Identity Proof') ? 'selected' : '' ?>>Identity Proof</option>
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
                                            <td>Land Revenue Receipt<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="revenuereceipt_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Land Revenue Receipt" <?= ($revenuereceipt_type === 'Land Revenue Receipt') ? 'selected' : '' ?>>Land Revenue Receipt</option>
                                                </select>
                                                <?= form_error("revenuereceipt_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="revenuereceipt" name="revenuereceipt" type="file" />
                                                </div>
                                                <?php if (strlen($revenuereceipt)) { ?>
                                                    <a href="<?= base_url($revenuereceipt) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="revenuereceipt" type="hidden" name="revenuereceipt_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('revenuereceipt'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Salary Slip</td>
                                            <td>
                                                <select name="salaryslip_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Salary Slip" <?= ($salaryslip_type === 'Salary Slip') ? 'selected' : '' ?>>Salary Slip</option>
                                                </select>
                                                <?= form_error("salaryslip_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="salaryslip" name="salaryslip" type="file" />
                                                </div>
                                                <?php if (strlen($salaryslip)) { ?>
                                                    <a href="<?= base_url($salaryslip) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="salaryslip" type="hidden" name="salaryslip_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('salaryslip'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Any other documents</td>
                                            <td>
                                                <select name="other_doc_type" class="form-control">
                                                    <option value="">Select</option>
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
                    <a href="<?= site_url('spservices/income/inc/index/' . $obj_id) ?>" class="btn btn-primary">
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