
<?php
$address_proof_type_frm = set_value("address_proof_type");
$identity_proof_type_frm = set_value("identity_proof_type");
$land_revenue_receipt_type_frm = set_value("land_revenue_receipt_type");
$salary_slip_type_frm = set_value("salary_slip_type");
$other_doc_type_frm = set_value("other_doc_type");
$soft_copy_type_frm = set_value("soft_copy_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');
$address_proof_frm = $uploadedFiles['address_proof_old']??null;
$identity_proof_frm = $uploadedFiles['identity_proof_old']??null;
$land_revenue_receipt_frm = $uploadedFiles['land_revenue_receipt_old']??null;
$salary_slip_frm = $uploadedFiles['salary_slip_old']??null;
$other_doc_frm = $uploadedFiles['other_doc_old']??null;
$soft_copy_frm = $uploadedFiles['soft_copy_old']??null;

$address_proof_type_db = $dbrow->address_proof_type??null;
$identity_proof_type_db = $dbrow->identity_proof_type??null;
$land_revenue_receipt_type_db = $dbrow->land_revenue_receipt_type??null;
$salary_slip_type_db = $dbrow->salary_slip_type??null; 
$other_doc_type_db = $dbrow->other_doc_type??null; 
$soft_copy_type_db = $dbrow->soft_copy_type??null;
$address_proof_db = $dbrow->address_proof??null;
$identity_proof_db = $dbrow->identity_proof??null;
$land_revenue_receipt_db = $dbrow->land_revenue_receipt??null;
$salary_slip_db = $dbrow->salary_slip??null;
$other_doc_db = $dbrow->other_doc??null;
$soft_copy_db = $dbrow->soft_copy??null;

$address_proof_type = strlen($address_proof_type_frm)?$address_proof_type_frm:$address_proof_type_db;
$identity_proof_type = strlen($identity_proof_type_frm)?$identity_proof_type_frm:$identity_proof_type_db;
$land_revenue_receipt_type = strlen($land_revenue_receipt_type_frm)?$land_revenue_receipt_type_frm:$land_revenue_receipt_type_db;
$salary_slip_type = strlen($salary_slip_type_frm)?$salary_slip_type_frm:$salary_slip_type_db;
$other_doc_type = strlen($other_doc_type_frm)?$other_doc_type_frm:$other_doc_type_db;
$soft_copy_type = strlen($soft_copy_type_frm)?$soft_copy_type_frm:$soft_copy_type_db;
$address_proof = strlen($address_proof_frm)?$address_proof_frm:$address_proof_db;
$identity_proof = strlen($identity_proof_frm)?$identity_proof_frm:$identity_proof_db;
$land_revenue_receipt = strlen($land_revenue_receipt_frm)?$land_revenue_receipt_frm:$land_revenue_receipt_db;
$salary_slip = strlen($salary_slip_frm)?$salary_slip_frm:$salary_slip_db;
$other_doc = strlen($other_doc_frm)?$other_doc_frm:$other_doc_db;
$soft_copy = strlen($soft_copy_frm)?$soft_copy_frm:$soft_copy_db;
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
    $(document).ready(function () {           
        $("#address_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
        $("#identity_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        }); 
        $("#land_revenue_receipt").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        }); 
        $("#salary_slip").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        }); 
        
        $("#other_doc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["jpg", "png", "gif","pdf"]
        });
        
        $("#soft_copy").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: true,
            maxFileSize: 2000,
            allowedFileExtensions: ["jpg", "png", "gif","pdf"]
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form method="POST" action="<?= base_url('spservices/incomecertificate/registration1/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
            <input name="address_proof_old" value="<?=$address_proof?>" type="hidden" />
            <input name="identity_proof_old" value="<?=$identity_proof?>" type="hidden" />
            <input name="land_revenue_receipt_old" value="<?=$land_revenue_receipt?>" type="hidden" />
            <input name="salary_slip_old" value="<?=$salary_slip?>" type="hidden" />
            <input name="other_doc_old" value="<?=$other_doc?>" type="hidden" />
            <input name="soft_copy_old" value="<?=$soft_copy?>" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                       <?=$service_name?> 
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
                                            <td>Address Proof/ঠিকনা প্ৰমাণ<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="address_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Address Proof" <?=($address_proof_type === 'Address Proof')?'selected':''?>>Address Proof</option>
                                                </select>
                                                <?= form_error("address_proof_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="address_proof" name="address_proof" type="file" />
                                                </div>
                                                <?php if(strlen($address_proof)){ ?>
                                                    <a href="<?=base_url($address_proof)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                    </tr>
                                    <tr>
                                            <td>Identity Proof/পৰিচয় প্ৰমাণ<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="identity_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Identity Proof" <?=($identity_proof_type === 'Identity Proof')?'selected':''?>>Identity Proof</option>
                                                </select>
                                                <?= form_error("identity_proof_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="identity_proof" name="identity_proof" type="file" />
                                                </div>
                                                <?php if(strlen($identity_proof)){ ?>
                                                    <a href="<?=base_url($identity_proof)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                    </tr>
                                    
                                        
                                        <tr>
                                            
                                            <td>Land Revenue Receipt/ভূমি ৰাজহ ৰচিদ<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="land_revenue_receipt_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Land Revenue Receipt" <?=($land_revenue_receipt_type === 'Land Revenue Receipt')?'selected':''?>>Land Revenue Receipt</option>
                                                </select>
                                                <?= form_error("land_revenue_receipt_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="land_revenue_receipt" name="land_revenue_receipt" type="file" />
                                                </div>
                                                <?php if(strlen($land_revenue_receipt)){ ?>
                                                    <a href="<?=base_url($land_revenue_receipt)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            
                                            <td>Salary Slip/দৰমহাৰ স্লিপ<span class="text-danger"></span></td>
                                            <td>
                                                <select name="salary_slip_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Salary Slip" <?=($salary_slip_type === 'Salary Slip')?'selected':''?>>Salary Slip</option>
                                                </select>
                                                <?= form_error("salary_slip_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="salary_slip" name="salary_slip" type="file" />
                                                </div>
                                                <?php if(strlen($salary_slip)){ ?>
                                                    <a href="<?=base_url($salary_slip)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        
                                        <tr>
                                            <td>Any Other Document/অন্য যিকোনো নথিপত্ৰ</td>
                                            <td>
                                                <select name="other_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Any Other Document"<?=($other_doc_type === 'Any Other Document')?'selected':''?>>Any Other Document</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="other_doc" name="other_doc" type="file" />
                                                </div>
                                                <?php if(strlen($other_doc)){ ?>
                                                    <a href="<?=base_url($other_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Upload Scanned Copy of the Applicant form/আবেদনকাৰীৰ প্ৰ-পত্ৰৰ স্কেন কৰা কপি আপলোড কৰক</td>
                                            <td>
                                                <select name="soft_copy_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Upload Scanned Copy of the Applicant form"<?=($soft_copy_type === 'Upload Scanned Copy of the Applicant form')?'selected':''?>>Upload Scanned Copy of the Applicant form</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="soft_copy" name="soft_copy" type="file" />
                                                </div>
                                                <?php if(strlen($soft_copy)){ ?>
                                                    <a href="<?=base_url($soft_copy)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div><!--End of .card-body -->
                

                <div class="card-footer text-center">
                    <a href="<?=base_url('iservices/wptbc/castecertificate/index/'.$obj_id)?>" class="btn btn-primary">
                        <i class="fa fa-angle-double-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <button type="reset" class="btn btn-danger">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>