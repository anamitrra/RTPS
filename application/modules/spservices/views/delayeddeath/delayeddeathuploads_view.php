<?php
$doctor_certificate_type_frm = set_value("doctor_certificate_type");
$proof_residence_type_frm = set_value("proof_residence_type");
$affidavit_type_frm = set_value("affidavit_type");
$others_type_frm = set_value("others_type");
$soft_copy_type_frm = set_value("soft_copy_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');
$doctor_certificate_frm = $uploadedFiles['doctor_certificate_old']??null;
$proof_residence_frm = $uploadedFiles['proof_residence_old']??null;
$affidavit_frm = $uploadedFiles['affidavit_old']??null;
$others_frm = $uploadedFiles['others_old']??null;
$soft_copy_frm = $uploadedFiles['soft_copy_old']??null;

$doctor_certificate_type_db = $dbrow->form_data->doctor_certificate_type??null;
$proof_residence_type_db = $dbrow->form_data->proof_residence_type??null;
$affidavit_type_db = $dbrow->form_data->affidavit_type??null;
$others_type_db = $dbrow->form_data->others_type??null;
$soft_copy_type_db = $dbrow->form_data->soft_copy_type??null;
$doctor_certificate_db = $dbrow->form_data->doctor_certificate??null;
$proof_residence_db = $dbrow->form_data->proof_residence??null;
$affidavit_db = $dbrow->form_data->affidavit??null;
$others_db = $dbrow->form_data->others??null;
$soft_copy_db = $dbrow->form_data->soft_copy??null;

$doctor_certificate_type = strlen($doctor_certificate_type_frm)?$doctor_certificate_type_frm:$doctor_certificate_type_db;
$proof_residence_type = strlen($proof_residence_type_frm)?$proof_residence_type_frm:$proof_residence_type_db;
$affidavit_type = strlen($affidavit_type_frm)?$affidavit_type_frm:$affidavit_type_db;
$others_type = strlen($others_type_frm)?$others_type_frm:$others_type_db;
$soft_copy_type = strlen($soft_copy_type_frm)?$soft_copy_type_frm:$soft_copy_type_db;
$doctor_certificate = strlen($doctor_certificate_frm)?$doctor_certificate_frm:$doctor_certificate_db;
$proof_residence = strlen($proof_residence_frm)?$proof_residence_frm:$proof_residence_db;
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
        var doctorCertificate = parseInt(<?=strlen($doctor_certificate)?1:0?>);
        $("#doctor_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: doctorCertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 
        
        var proofResidence = parseInt(<?=strlen($proof_residence)?1:0?>);
        $("#proof_residence").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: proofResidence?false:true,
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
        <form method="POST" action="<?= base_url('spservices/delayeddeath/registration/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
            <input name="doctor_certificate_old" value="<?=$doctor_certificate?>" type="hidden" />
            <input name="proof_residence_old" value="<?=$proof_residence?>" type="hidden" />
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
                                            <td>Hospital or Doctor's Certificate regarding Death / Cremation certificate or Age Proof.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="doctor_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Hospital or Doctor Certificate regarding Death" <?=($doctor_certificate_type === 'Hospital or Doctor Certificate regarding Death')?'selected':''?>>Hospital or Doctor Certificate regarding Death </option>
                                                </select>
                                                <?= form_error("doctor_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="doctor_certificate" name="doctor_certificate" type="file" />
                                                </div>
                                                <?php if(strlen($doctor_certificate)){ ?>
                                                    <a href="<?=base_url($doctor_certificate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="doctor_certificate" type="hidden" name="doctor_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('doctor_certificate'); ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Proof of Resident.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="proof_residence_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Proof of Resident" <?=($proof_residence_type === 'Proof of Resident')?'selected':''?>>Proof of Resident</option>
                                                </select>
                                                <?= form_error("proof_residence_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="proof_residence" name="proof_residence" type="file" />
                                                </div>
                                                <?php if(strlen($proof_residence)){ ?>
                                                    <a href="<?=base_url($proof_residence)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="proof_residence" type="hidden" name="proof_residence_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('proof_residence'); ?>
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
                    <a href="<?=site_url('spservices/delayeddeath/registration/index/'.$obj_id)?>" class="btn btn-primary">
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