<?php
$proof_ownership_type = set_value("proof_ownership_type");
$proof_ownership = set_value("proof_ownership");
$identity_proof_type = set_value("identity_proof_type");
$identity_proof = set_value("identity_proof");
$ubin_certificate = set_value("ubin_certificate");
$ubin_certificate_type = set_value("ubin_certificate_type");
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
        var proof_ownership = parseInt(<?= strlen($proof_ownership) ? 1 : 0 ?>);
        $("#proof_ownership").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: proof_ownership ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var identity_proof = parseInt(<?= strlen($identity_proof) ? 1 : 0 ?>);
        $("#identity_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: identity_proof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var ubin_certificate = parseInt(<?= strlen($ubin_certificate) ? 1 : 0 ?>);
        $("#ubin_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: ubin_certificate ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form method="POST" action="<?= base_url('spservices/tradelicence/Application/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?= $obj_id ?>" name="obj_id" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Trade Licence<br>
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
                                            <td>Proof of ownership <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="proof_ownership_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Rent agreement of the property" <?= ($proof_ownership_type === 'Applicant Photo') ? 'selected' : '' ?>>Rent agreement of the property</option>
                                                    <option value="Lease" <?= ($proof_ownership_type === 'Lease') ? 'selected' : '' ?>>Lease</option>
                                                    <option value="Building permit/OC/Jamabandi copy" <?= ($proof_ownership_type === 'Applicant Photo') ? 'selected' : '' ?>>Building permit/OC/Jamabandi copy
                                                    </option>
                                                </select>
                                                <?= form_error("proof_ownership_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input name="proof_ownership" id="proof_ownership" type="file" />
                                                </div>
                                                <?php if (strlen($proof_ownership)) { ?>
                                                    <a href="<?= base_url($proof_ownership) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Proof of Identification <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="identity_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="PAN Card" <?= ($identity_proof_type === 'PAN Card') ? 'selected' : '' ?>>PAN Card</option>
                                                    <option value="Driving License" <?= ($identity_proof_type === 'Driving License') ? 'selected' : '' ?>>Driving License</option>
                                                    <option value="Aadhaar Card" <?= ($identity_proof_type === 'Aadhaar Card') ? 'selected' : '' ?>>Aadhaar Card
                                                    </option>
                                                    <option value="Passport" <?= ($identity_proof_type === 'Passport') ? 'selected' : '' ?>>Passport
                                                    </option>
                                                    <option value="Ration Card" <?= ($identity_proof_type === 'Ration Card') ? 'selected' : '' ?>>Ration Card
                                                    </option>
                                                    <option value="Voter ID Card" <?= ($identity_proof_type === 'Voter ID Card') ? 'selected' : '' ?>>Voter ID Card
                                                    </option>
                                                </select>
                                                <?= form_error("proof_ownership_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input name="identity_proof" id="identity_proof" type="file" />
                                                </div>
                                                <?php if (strlen($identity_proof)) { ?>
                                                    <a href="<?= base_url($identity_proof) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>UBIN Certificate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="ubin_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="UBIN Certificate" <?= ($ubin_certificate_type === 'UBIN Certificate') ? 'selected' : '' ?>>UBIN Certificate</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="ubin_certificate" name="ubin_certificate" type="file" />
                                                </div>
                                                <?php if (strlen($ubin_certificate)) { ?>
                                                    <a href="<?= base_url($ubin_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>


                                        <?php  //End of if 
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?= base_url('spservices/tradelicence/Application/index/' . $obj_id) ?>" class="btn btn-primary">
                        <i class="fa fa-angle-double-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Submit
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