<?php
$photo_of_the_candidate_type_frm = set_value("photo_of_the_candidate_type");
$signature_of_the_candidate_type_frm = set_value("signature_of_the_candidate_type");
$pass_certificate_from_uni_coll_type_frm = set_value("pass_certificate_from_uni_coll_type");
$pg_degree_dip_marksheet_type_frm = set_value("pg_degree_dip_marksheet_type");
$prc_acmr_type_frm = set_value("prc_acmr_type");
$other_addl_degree_type_frm = set_value("other_addl_degree_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');
$photo_of_the_candidate_frm = $uploadedFiles['photo_of_the_candidate_old']??null;
$signature_of_the_candidate_frm = $uploadedFiles['signature_of_the_candidate_old']??null;
$pass_certificate_from_uni_coll_frm = $uploadedFiles['pass_certificate_from_uni_coll_old']??null;
$pg_degree_dip_marksheet_frm = $uploadedFiles['pg_degree_dip_marksheet_old']??null;
$prc_acmr_frm = $uploadedFiles['prc_acmr_old']??null;
$other_addl_degree_frm = $uploadedFiles['other_addl_degree_old']??null;

$photo_of_the_candidate_type_db = $dbrow->form_data->photo_of_the_candidate_type??null;
$signature_of_the_candidate_type_db = $dbrow->form_data->signature_of_the_candidate_type??null;
$pass_certificate_from_uni_coll_type_db = $dbrow->form_data->pass_certificate_from_uni_coll_type??null;
$pg_degree_dip_marksheet_type_db = $dbrow->form_data->pg_degree_dip_marksheet_type??null;
$prc_acmr_type_db = $dbrow->form_data->prc_acmr_type??null;
$other_addl_degree_type_db = $dbrow->form_data->other_addl_degree_type??null;

$photo_of_the_candidate_db = $dbrow->form_data->photo_of_the_candidate??null;
$signature_of_the_candidate_db = $dbrow->form_data->signature_of_the_candidate??null;
$pass_certificate_from_uni_coll_db = $dbrow->form_data->pass_certificate_from_uni_coll??null;
$pg_degree_dip_marksheet_db = $dbrow->form_data->pg_degree_dip_marksheet??null;
$prc_acmr_db = $dbrow->form_data->prc_acmr??null;
$other_addl_degree_db = $dbrow->form_data->other_addl_degree??null;

$photo_of_the_candidate_type = strlen($photo_of_the_candidate_type_frm)?$photo_of_the_candidate_type_frm:$photo_of_the_candidate_type_db;
$signature_of_the_candidate_type = strlen($signature_of_the_candidate_type_frm)?$signature_of_the_candidate_type_frm:$signature_of_the_candidate_type_db;
$pass_certificate_from_uni_coll_type = strlen($pass_certificate_from_uni_coll_type_frm)?$pass_certificate_from_uni_coll_type_frm:$pass_certificate_from_uni_coll_type_db;
$pg_degree_dip_marksheet_type = strlen($pg_degree_dip_marksheet_type_frm)?$pg_degree_dip_marksheet_type_frm:$pg_degree_dip_marksheet_type_db;
$prc_acmr_type = strlen($prc_acmr_type_frm)?$prc_acmr_type_frm:$prc_acmr_type_db;
$other_addl_degree_type = strlen($other_addl_degree_type_frm)?$other_addl_degree_type_frm:$other_addl_degree_type_db;

$photo_of_the_candidate = strlen($photo_of_the_candidate_frm)?$photo_of_the_candidate_frm:$photo_of_the_candidate_db;
$signature_of_the_candidate = strlen($signature_of_the_candidate_frm)?$signature_of_the_candidate_frm:$signature_of_the_candidate_db;
$pass_certificate_from_uni_coll = strlen($pass_certificate_from_uni_coll_frm)?$pass_certificate_from_uni_coll_frm:$pass_certificate_from_uni_coll_db;
$pg_degree_dip_marksheet = strlen($pg_degree_dip_marksheet_frm)?$pg_degree_dip_marksheet_frm:$pg_degree_dip_marksheet_db;
$prc_acmr = strlen($prc_acmr_frm)?$prc_acmr_frm:$prc_acmr_db;
$other_addl_degree = strlen($other_addl_degree_frm)?$other_addl_degree_frm:$other_addl_degree_db;
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
        var photoOfTheCandidate = parseInt(<?=strlen($photo_of_the_candidate)?1:0?>);
        $("#photo_of_the_candidate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            required: photoOfTheCandidate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg"]
        }); 

        var signatureOfTheCandidate = parseInt(<?=strlen($signature_of_the_candidate)?1:0?>);
        $("#signature_of_the_candidate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            required: signatureOfTheCandidate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg"]
        }); 

        var passCertificateFromUniColl = parseInt(<?=strlen($pass_certificate_from_uni_coll)?1:0?>);
        $("#pass_certificate_from_uni_coll").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            // required: passCertificateFromUniColl?false:true,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var pgDegreeDipMarksheet = parseInt(<?=strlen($pg_degree_dip_marksheet)?1:0?>);
        $("#pg_degree_dip_marksheet").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            // required: pgDegreeDipMarksheet?false:true,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var prcAcmr = parseInt(<?=strlen($prc_acmr)?1:0?>);
        $("#prc_acmr").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            // required: prcAcmr?false:true,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#other_addl_degree").fileinput({
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
        <form method="POST" action="<?= base_url('spservices/acmr_reg_of_addl_degrees/registration/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
            <input name="photo_of_the_candidate_old" value="<?=$photo_of_the_candidate?>" type="hidden" />
            <input name="signature_of_the_candidate_old" value="<?=$signature_of_the_candidate?>" type="hidden" />
            <input name="pass_certificate_from_uni_coll_old" value="<?=$pass_certificate_from_uni_coll?>" type="hidden" />
            <input name="pg_degree_dip_marksheet_old" value="<?=$pg_degree_dip_marksheet?>" type="hidden" />
            <input name="prc_acmr_old" value="<?=$prc_acmr?>" type="hidden" />
            <input name="other_addl_degree_old" value="<?=$other_addl_degree?>" type="hidden" />
            <!-- <input name="soft_copy_old" value="<?=$soft_copy?>" type="hidden" /> -->
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
                                            <td>Photo of the Candidate.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="photo_of_the_candidate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Photo of the Candidate" <?=($photo_of_the_candidate_type === 'Photo of the Candidate')?'selected':''?>>Photo of the Candidate</option>
                                                </select>
                                                <?= form_error("photo_of_the_candidate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="photo_of_the_candidate" name="photo_of_the_candidate" type="file" />
                                                </div>
                                                <?php if(strlen($photo_of_the_candidate)){ ?>
                                                    <a href="<?=base_url($photo_of_the_candidate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                 <input class="photo_of_the_candidate" type="hidden" name="photo_of_the_candidate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('photo_of_the_candidate'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Signature of the Candidate*.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="signature_of_the_candidate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Signature of the Candidate" <?=($signature_of_the_candidate_type === 'Signature of the Candidate')?'selected':''?>>Signature of the Candidate</option>
                                                </select>
                                                <?= form_error("signature_of_the_candidate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="signature_of_the_candidate" name="signature_of_the_candidate" type="file" />
                                                </div>
                                                <?php if(strlen($signature_of_the_candidate)){ ?>
                                                    <a href="<?=base_url($signatureo_of_the_candidate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="signature_of_the_candidate" type="hidden" name="signature_of_the_candidate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('signature_of_the_candidate'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Pass Certificate from University/College*.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="pass_certificate_from_uni_coll_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Pass Certificate from University/College" <?=($pass_certificate_from_uni_coll_type === 'Pass Certificate from University/College')?'selected':''?>>Pass Certificate from University/College</option>
                                                </select>
                                                <?= form_error("pass_certificate_from_uni_coll_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="pass_certificate_from_uni_coll" name="pass_certificate_from_uni_coll" type="file" />
                                                </div>
                                                <?php if(strlen($pass_certificate_from_uni_coll)){ ?>
                                                    <a href="<?=base_url($pass_certificate_from_uni_coll)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="pass_certificate_from_uni_coll" type="hidden" name="pass_certificate_from_uni_coll_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('pass_certificate_from_uni_coll'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>PGDegree/DiplomaMarksheet*.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="pg_degree_dip_marksheet_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="PGDegree/DiplomaMarksheet" <?=($pg_degree_dip_marksheet_type === 'PGDegree/DiplomaMarksheet')?'selected':''?>>PGDegree/DiplomaMarksheet</option>
                                                </select>
                                                <?= form_error("pg_degree_dip_marksheet_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="pg_degree_dip_marksheet" name="pg_degree_dip_marksheet" type="file" />
                                                </div>
                                                <?php if(strlen($pg_degree_dip_marksheet)){ ?>
                                                    <a href="<?=base_url($pg_degree_dip_marksheet)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="pg_degree_dip_marksheet" type="hidden" name="pg_degree_dip_marksheet_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('pg_degree_dip_marksheet'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Permanent Registration Certificate of Assam Council of Medical Registration*.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="prc_acmr_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Permanent Registration Certificate of Assam Council of Medical Registration" <?=($prc_acmr_type === 'Permanent Registration Certificate of Assam Council of Medical Registration')?'selected':''?>>Permanent Registration Certificate of Assam Council of Medical Registration</option>
                                                </select>
                                                <?= form_error("prc_acmr_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="prc_acmr" name="prc_acmr" type="file" />
                                                </div>
                                                <?php if(strlen($prc_acmr)){ ?>
                                                    <a href="<?=base_url($prc_acmr)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="prc_acmr" type="hidden" name="prc_acmr_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('prc_acmr'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Other Additional Degrees (If any)</td>
                                            <td>
                                                <select name="other_addl_degree_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Other Additional Degrees (If any)" <?=($other_addl_degree_type === 'Other Additional Degrees (If any)')?'selected':''?>>Other Additional Degrees (If any)</option>
                                                </select>
                                                <?= form_error("other_addl_degree_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="other_addl_degree" name="other_addl_degree" type="file" />
                                                </div>
                                                <?php if(strlen($other_addl_degree)){ ?>
                                                    <a href="<?=base_url($other_addl_degree)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="other_addl_degree" type="hidden" name="other_addl_degree_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('other_addl_degree'); ?>
                                            </td>
                                        </tr>
                                            
                                        <!-- <?php if($this->slug == 'userrr') { ?>
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
                                        <?php }//End of if ?> -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?=site_url('spservices/acmr_reg_of_addl_degrees/registration/index/'.$obj_id)?>" class="btn btn-primary">
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