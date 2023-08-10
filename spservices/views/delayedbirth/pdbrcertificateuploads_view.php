<?php
$affidavit_type_frm = set_value("affidavit_type");
$age_proof_type_frm = set_value("age_proof_type");
$address_proof_type_frm = set_value("address_proof_type");
$other_doc_type_frm = set_value("other_doc_type");
$soft_copy_type_frm = set_value("soft_copy_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');

$affidavit_frm = $uploadedFiles['affidavit_old']??null;
$age_proof_frm = $uploadedFiles['age_proof_old']??null;
$address_proof_frm = $uploadedFiles['address_proof_old']??null;
$other_doc_frm = $uploadedFiles['other_doc_old']??null;
$soft_copy_frm = $uploadedFiles['soft_copy_old']??null;

$affidavit_type_db = $dbrow->form_data->affidavit_type??null;
$age_proof_type_db = $dbrow->form_data->age_proof_type??null;
$address_proof_type_db = $dbrow->form_data->address_proof_type??null;
$other_doc_type_db = $dbrow->form_data->other_doc_type??null;
$soft_copy_type_db = $dbrow->form_data->soft_copy_type??null;

$affidavit_db = $dbrow->form_data->affidavit??null;
$age_proof_db = $dbrow->form_data->age_proof??null;
$address_proof_db = $dbrow->form_data->address_proof??null;
$other_doc_db = $dbrow->form_data->other_doc??null;
$soft_copy_db = $dbrow->form_data->soft_copy??null;

$affidavit_type = strlen($affidavit_type_frm)?$affidavit_type_frm:$affidavit_type_db;
$age_proof_type = strlen($age_proof_type_frm)?$age_proof_type_frm:$age_proof_type_db;
$address_proof_type = strlen($address_proof_type_frm)?$address_proof_type_frm:$address_proof_type_db;
$other_doc_type = strlen($other_doc_type_frm)?$other_doc_type_frm:$other_doc_type_db;
$soft_copy_type = strlen($soft_copy_type_frm)?$soft_copy_type_frm:$soft_copy_type_db;


$affidavit = strlen($affidavit_frm)?$affidavit_frm:$affidavit_db;
$age_proof = strlen($age_proof_frm)?$age_proof_frm:$age_proof_db;
$address_proof = strlen($address_proof_frm)?$address_proof_frm:$address_proof_db;
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
<script src="<?= base_url('assets/iservices/js/custom/digilocker.js') ?>"></script>
<script type="text/javascript">   
    $(document).ready(function () {     
        
        var affidAvit = parseInt(<?=strlen($affidavit)?1:0?>);
        $("#affidavit").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: affidAvit?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var ageProof = parseInt(<?=strlen($age_proof)?1:0?>);
        $("#age_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: ageProof?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var addressProof = parseInt(<?=strlen($address_proof)?1:0?>);
        $("#address_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: addressProof?false:true,
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
        <form method="POST" action="<?= base_url('spservices/delayedbirth/pdbr/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
            <input name="affidavit_old" value="<?=$affidavit?>" type="hidden" />
            <input name="age_proof_old" value="<?=$age_proof?>" type="hidden" />
            <input name="address_proof_old" value="<?=$address_proof?>" type="hidden" />
            <input name="other_doc_old" value="<?=$other_doc?>" type="hidden" />
            <input name="soft_copy_old" value="<?=$soft_copy?>" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                <h4><b>Application Form For Permission For Delayed Birth Registration<br>
                            ( পলমকৈ জন্ম পঞ্জীয়নৰ অনুমতিৰ বাবে আবেদন প্ৰ-পত্ৰ )<b></h4>
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
                                            <td>Register Hospital Govt. / Pvt. Certificate regarding Birth or Age Proof (any)<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="age_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Register Hospital Govt. / Pvt. Certificate regarding Birth or Age Proof (any)" <?=($age_proof_type === 'Register Hospital Govt. / Pvt. Certificate regarding Birth or Age Proof (any)')?'selected':''?>>Register Hospital Govt. / Pvt. Certificate regarding Birth or Age Proof (any) </option>
                                                </select>
                                                <?= form_error("age_proof_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="age_proof" name="age_proof" type="file" />
                                                </div>
                                                <?php if(strlen($age_proof)){ ?>
                                                    <a href="<?=base_url($age_proof)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="age_proof" type="hidden" name="age_proof_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('age_proof'); ?>
                                            </td>
                                        </tr>
                                            
                                        <tr>
                                            <td>School Certificate/Admit Card (for age 6 and above) or parent's details<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="address_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="School Certificate/Admit Card (for age 6 and above) or parents details" <?=($address_proof_type === 'School Certificate/Admit Card (for age 6 and above) or parents details')?'selected':''?>>School Certificate/Admit Card (for age 6 and above) or parent's details</option>                                                                                      
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
                                                <input class="address_proof" type="hidden" name="address_proof_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('address_proof'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Affidavit<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="affidavit_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Affidavit duly signed by the Magistrate" <?=($affidavit_type === 'Affidavit duly signed by the Magistrate')?'selected':''?>>Affidavit duly signed by the Magistrate</option>
                                                    
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
                                            <td>Any other document</td>
                                            <td>
                                                <select name="other_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Any other document" <?=($other_doc_type === 'Any other document')?'selected':''?>>Any other document</option>                                                                                        
                                                </select>
                                                <?= form_error("other_doc_type") ?>
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
                                                <input class="other_doc" type="hidden" name="other_doc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('other_doc'); ?>
                                            </td>
                                        </tr>
                                        <?php if($this->slug == 'userrr') { ?>
                                            <tr>
                                                <td>Soft copy of the applicant form<span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="soft_copy_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Soft copy of the applicant form" <?=($soft_copy_type === 'Soft copy of the applicant form')?'selected':''?>>Soft copy of the applicant form</option>
                                                    </select>
                                                    <?= form_error("soft_copy_type") ?>
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
                                        <?php }//End of if ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?=site_url('spservices/delayedbirth/pdbr/index/'.$obj_id)?>" class="btn btn-primary">
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