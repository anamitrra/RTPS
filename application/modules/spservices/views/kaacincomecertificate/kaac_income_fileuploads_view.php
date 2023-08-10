<?php

//new

// pre($dbrow);

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


$land_patta_copy_type_frm = set_value("land_patta_copy_type");
$land_patta_copy_frm = $uploadedFiles['land_patta_copy_old'] ?? null;
$land_patta_copy_type_db = $dbrow->form_data->land_patta_copy_type ?? null;
$land_patta_copy_db = $dbrow->form_data->land_patta_copy ?? null;
$land_patta_copy = strlen($land_patta_copy_frm) ? $land_patta_copy_frm : $land_patta_copy_db;
$land_patta_copy_type = strlen($land_patta_copy_type_frm) ? $land_patta_copy_type_frm : $land_patta_copy_type_db;


$updated_land_revenue_receipt_type_frm = set_value("updated_land_revenue_receipt_type");
$updated_land_revenue_receipt_frm = $uploadedFiles['updated_land_revenue_receipt_old'] ?? null;
$updated_land_revenue_receipt_type_db = $dbrow->form_data->updated_land_revenue_receipt_type ?? null;
$updated_land_revenue_receipt_db = $dbrow->form_data->updated_land_revenue_receipt ?? null;
$updated_land_revenue_receipt = strlen($updated_land_revenue_receipt_frm) ? $updated_land_revenue_receipt_frm : $updated_land_revenue_receipt_db;
$updated_land_revenue_receipt_type = strlen($updated_land_revenue_receipt_type_frm) ? $updated_land_revenue_receipt_type_frm : $updated_land_revenue_receipt_type_db;



$salary_slip_type_frm = set_value("salary_slip_type");
$salary_slip_frm = $uploadedFiles['salary_slip_old'] ?? null;
$salary_slip_type_db = $dbrow->form_data->salary_slip_type ?? null;
$salary_slip_db = $dbrow->form_data->salary_slip ?? null;
$salary_slip = strlen($salary_slip_frm) ? $salary_slip_frm : $salary_slip_db;
$salary_slip_type = strlen($salary_slip_type_frm) ? $salary_slip_type_frm : $salary_slip_type_db;


$other_doc_type_frm = set_value("other_doc_type");
$other_doc_frm = $uploadedFiles['other_doc_old'] ?? null;
$other_doc_type_db = $dbrow->form_data->other_doc_type ?? null;
$other_doc_db = $dbrow->form_data->other_doc ?? null;
$other_doc = strlen($other_doc_frm) ? $other_doc_frm : $other_doc_db;
$other_doc_type = strlen($other_doc_type_frm) ? $other_doc_type_frm : $other_doc_type_db;



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


        $("#land_patta_copy").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        $("#updated_land_revenue_receipt").fileinput({
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

        
        
        $("#salary_slip").fileinput({
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
        
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form method="POST" action="<?= base_url('spservices/kaacincomecertificate/registration/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?= $obj_id ?>" name="obj_id" type="hidden" />

            <input name="address_proof_old" value="<?= $address_proof ?>" type="hidden" />
            <input name="identity_proof_old" value="<?= $identity_proof ?>" type="hidden" />
            <input name="land_patta_copy_old" value="<?= $land_patta_copy ?>" type="hidden" />
            <input name="updated_land_revenue_receipt_old" value="<?= $updated_land_revenue_receipt ?>" type="hidden" />
            <input name="salary_slip_old" value="<?= $salary_slip ?>" type="hidden" />
            <input name="other_doc_old" value="<?= $other_doc ?>" type="hidden" />

            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                <h4><b><?= $pageTitle ?><br>
                            আয়ৰ প্ৰমাণ পত্ৰ প্ৰদান <b>
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
                                                <td>Address Proof <span class="text-danger">*</span></td>
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
                                                <td>Identity proof <span class="text-danger">*</span></td>
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
                                       
                                            <?php if ($dbrow->form_data->applicant_category == 1 || $dbrow->form_data->applicant_category == 2) { ?>
                                            <tr>
                                                <td>Land patta copy <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="land_patta_copy_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Land Patta Copy" <?= ($land_patta_copy_type === 'Land Patta Copy') ? 'selected' : '' ?>>Land Patta Copy</option>
                                                    </select>
                                                    <?= form_error("land_patta_copy_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="land_patta_copy" name="land_patta_copy" type="file" />
                                                    </div>
                                                    <?php if (strlen($land_patta_copy)) { ?>
                                                        <a href="<?= base_url($land_patta_copy) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="land_patta_copy" type="hidden" name="land_patta_copy_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('land_patta_copy'); ?>
                                                </td>
                                            </tr>
                                      
                                            <tr>
                                                <td>Updated Land revenue receipt <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="updated_land_revenue_receipt_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Updated Land revenue receipt" <?= ($updated_land_revenue_receipt_type === 'Updated Land revenue receipt') ? 'selected' : '' ?>>Updated Land revenue receipt</option>
                                                    </select>
                                                    <?= form_error("updated_land_revenue_receipt_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="updated_land_revenue_receipt" name="updated_land_revenue_receipt" type="file" />
                                                    </div>
                                                    <?php if (strlen($updated_land_revenue_receipt)) { ?>
                                                        <a href="<?= base_url($updated_land_revenue_receipt) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="updated_land_revenue_receipt" type="hidden" name="updated_land_revenue_receipt_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('updated_land_revenue_receipt'); ?>
                                                </td>
                                            </tr>

                                                   <?php } ?>
                                        <?php if ($dbrow->form_data->applicant_category == 1 || $dbrow->form_data->applicant_category == 3) { ?>
                                            <tr>
                                                <td>Salary Slip <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="salary_slip_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Salary Slip" <?= ($salary_slip_type === 'Salary Slip') ? 'selected' : '' ?>>Salary Slip</option>
                                                    </select>
                                                    <?= form_error("salary_slip_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="salary_slip" name="salary_slip" type="file" />
                                                    </div>
                                                    <?php if (strlen($salary_slip)) { ?>
                                                        <a href="<?= base_url($salary_slip) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="salary_slip" type="hidden" name="salary_slip_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('salary_slip'); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if ($dbrow->form_data->applicant_category == 4) { ?>
                                            <tr>
                                                <td>Other Document(Ration card/Job Card/Trading License/Goan Bura Certificate) <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="other_doc_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Ration Card" <?= ($other_doc_type === 'Ration Card') ? 'selected' : '' ?>>Ration Card</option>
                                                        <option value="Job Card" <?= ($other_doc_type === 'Job Card') ? 'selected' : '' ?>>Job Card</option>
                                                        <option value="Trading License" <?= ($other_doc_type === 'Trading License') ? 'selected' : '' ?>>Trading License</option>
                                                        <option value="Goan Bura Certificate" <?= ($other_doc_type === 'Goan Bura Certificate') ? 'selected' : '' ?>>Goan Bura Certificate</option>
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
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?= site_url('spservices/kaacincomecertificate/registration/index/' . $obj_id) ?>" class="btn btn-primary">
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