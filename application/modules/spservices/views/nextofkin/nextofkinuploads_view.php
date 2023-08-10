<?php
$death_proof_type_frm = set_value("death_proof_type");
$doc_for_relationship_type_frm = set_value("doc_for_relationship_type");
$affidavit_type_frm = set_value("affidavit_type");
$others_type_frm = set_value("others_type");
$soft_copy_type_frm = set_value("soft_copy_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');
$death_proof_frm = $uploadedFiles['death_proof_old']??null;
$doc_for_relationship_frm = $uploadedFiles['doc_for_relationship_old']??null;
$affidavit_frm = $uploadedFiles['affidavit_old']??null;
$others_frm = $uploadedFiles['others_old']??null;
$soft_copy_frm = $uploadedFiles['soft_copy_old']??null;

$death_proof_type_db = $dbrow->form_data->death_proof_type??null;
$doc_for_relationship_type_db = $dbrow->form_data->doc_for_relationship_type??null;
$affidavit_type_db = $dbrow->form_data->affidavit_type??null;
$others_type_db = $dbrow->form_data->others_type??null;
$soft_copy_type_db = $dbrow->form_data->soft_copy_type??null;
$death_proof_db = $dbrow->form_data->death_proof??null;
$doc_for_relationship_db = $dbrow->form_data->doc_for_relationship??null;
$affidavit_db = $dbrow->form_data->affidavit??null;
$others_db = $dbrow->form_data->others??null;
$soft_copy_db = $dbrow->form_data->soft_copy??null;

$death_proof_type = strlen($death_proof_type_frm)?$death_proof_type_frm:$death_proof_type_db;
$doc_for_relationship_type = strlen($doc_for_relationship_type_frm)?$doc_for_relationship_type_frm:$doc_for_relationship_type_db;
$affidavit_type = strlen($affidavit_type_frm)?$affidavit_type_frm:$affidavit_type_db;
$others_type = strlen($others_type_frm)?$others_type_frm:$others_type_db;
$soft_copy_type = strlen($soft_copy_type_frm)?$soft_copy_type_frm:$soft_copy_type_db;
$death_proof = strlen($death_proof_frm)?$death_proof_frm:$death_proof_db;
$doc_for_relationship = strlen($doc_for_relationship_frm)?$doc_for_relationship_frm:$doc_for_relationship_db;
$affidavit = strlen($affidavit_frm)?$affidavit_frm:$affidavit_db;
$others = strlen($others_frm)?$others_frm:$others_db;
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
        var deathProof = parseInt(<?=strlen($death_proof)?1:0?>);
        $("#death_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: deathProof?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 
        
        var docForRelationship = parseInt(<?=strlen($doc_for_relationship)?1:0?>);
        $("#doc_for_relationship").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: docForRelationship?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var affidavit = parseInt(<?=strlen($affidavit)?1:0?>);
        $("#affidavit").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: affidavit?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#others").fileinput({
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
        <form method="POST" action="<?= base_url('spservices/nextofkin/registration/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
            <input name="death_proof_old" value="<?=$death_proof?>" type="hidden" />
            <input name="doc_for_relationship_old" value="<?=$doc_for_relationship?>" type="hidden" />
            <input name="affidavit_old" value="<?=$affidavit?>" type="hidden" />
            <input name="others_old" value="<?=$others?>" type="hidden" />
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
                                            <td>Death Proof.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="death_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Death certificate of the deceased person" <?=($death_proof_type === 'Death certificate of the deceased person')?'selected':''?>>Death certificate of the deceased person</option>
                                                </select>
                                                <?= form_error("death_proof_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="death_proof" name="death_proof" type="file" />
                                                </div>
                                                <?php if(strlen($death_proof)){ ?>
                                                    <a href="<?=base_url($death_proof)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="death_proof" type="hidden" name="death_proof_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('death_proof'); ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Document for relationship proof.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="doc_for_relationship_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Marriage Certificate" <?=($doc_for_relationship_type === 'Marriage Certificate')?'selected':''?>>Marriage Certificate</option>
                                                    <option value="Voter ID Card" <?=($doc_for_relationship_type === 'Voter ID Card')?'selected':''?>>Voter ID Card</option>
                                                    <option value="Copy of PAN Card" <?=($doc_for_relationship_type === 'Copy of PAN Card')?'selected':''?>>Copy of PAN Card</option>
                                                    <option value="Birth Certificate" <?=($doc_for_relationship_type === 'Birth Certificate')?'selected':''?>>Birth Certificate</option>
                                                    <option value="Others" <?=($doc_for_relationship_type === 'Others')?'selected':''?>>Others</option>
                                                </select>
                                                <?= form_error("doc_for_relationship_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="doc_for_relationship" name="doc_for_relationship" type="file" />
                                                </div>
                                                <?php if(strlen($doc_for_relationship)){ ?>
                                                    <a href="<?=base_url($doc_for_relationship)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="doc_for_relationship" type="hidden" name="doc_for_relationship_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('doc_for_relationship'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Affidavit.<span class="text-danger">*</span></td>
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
                                            <td>Others</td>
                                            <td>
                                                <select name="others_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Other supporting document" <?=($others_type === 'Other supporting document')?'selected':''?>>Other supporting document </option>
                                                </select>
                                                <?= form_error("others_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="others" name="others" type="file" />
                                                </div>
                                                <?php if(strlen($others)){ ?>
                                                    <a href="<?=base_url($others)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="others" type="hidden" name="others_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('others'); ?>
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
                    <a href="<?=site_url('spservices/nextofkin/registration/index/'.$obj_id)?>" class="btn btn-primary">
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