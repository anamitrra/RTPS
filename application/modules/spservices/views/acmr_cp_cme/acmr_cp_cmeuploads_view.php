<?php
$request_letter_type_frm = set_value("request_letter_type");
$cme_program_type_frm = set_value("cme_program_type");
$draft_copy_type_frm = set_value("draft_copy_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');
$request_letter_frm = $uploadedFiles['request_letter_old']??null;
$cme_program_frm = $uploadedFiles['cme_program_old']??null;
$draft_copy_frm = $uploadedFiles['draft_copy_old']??null;

$request_letter_type_db = $dbrow->form_data->request_letter_type??null;
$cme_program_type_db = $dbrow->form_data->cme_program_type??null;
$draft_copy_type_db = $dbrow->form_data->draft_copy_type??null;

$request_letter_db = $dbrow->form_data->request_letter??null;
$cme_program_db = $dbrow->form_data->cme_program??null;
$draft_copy_db = $dbrow->form_data->draft_copy??null;


$request_letter_type = strlen($request_letter_frm)?$request_letter_type_frm:$request_letter_type_db;
$cme_program_type = strlen($cme_program_type_frm)?$cme_program_type_frm:$cme_program_type_db;
$draft_copy_type = strlen($draft_copy_type_frm)?$draft_copy_type_frm:$draft_copy_type_db;

$request_letter = strlen($request_letter_frm)?$request_letter_frm:$request_letter_db;
$cme_program = strlen($cme_program_frm)?$cme_program_frm:$cme_program_db;
$draft_copy = strlen($draft_copy_frm)?$draft_copy_frm:$draft_copy_db;

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
        var requestLetter = parseInt(<?=strlen($request_letter)?1:0?>);
        $("#request_letter").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: requestLetter?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 
        
        var cmeProgram = parseInt(<?=strlen($cme_program)?1:0?>);
        $("#cme_program").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: cmeProgram?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var draftCopy = parseInt(<?=strlen($draft_copy)?1:0?>);
        $("#draft_copy").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: draftCopy?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form method="POST" action="<?= base_url('spservices/acmr_cp_cme/registration/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
            <input name="request_letter_old" value="<?=$request_letter?>" type="hidden" />
            <input name="cme_program_old" value="<?=$cme_program?>" type="hidden" />
            <input name="draft_copy_old" value="<?=$draft_copy?>" type="hidden" />
            <!-- <input name="others_old" value="<?=$others?>" type="hidden" />
            <input name="soft_copy_old" value="<?=$soft_copy?>" type="hidden" /> -->
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
                                            <td>Request Letter for Physical CME on Letter head.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="request_letter_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Request Letter for Physical CME on Letter head" <?=($request_letter_type === 'Request Letter for Physical CME on Letter head')?'selected':''?>>Request Letter for Physical CME on Letter head </option>
                                                </select>
                                                <?= form_error("request_letter_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="request_letter" name="request_letter" type="file" />
                                                </div>
                                                <?php if(strlen($request_letter)){ ?>
                                                    <a href="<?=base_url($request_letter)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="request_letter" type="hidden" name="request_letter_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('request_letter'); ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>CME Program/Schedule.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="cme_program_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="CME Program/Schedule" <?=($cme_program_type === 'CME Program/Schedule')?'selected':''?>>CME Program/Schedule</option>
                                                </select>
                                                <?= form_error("cme_program_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="cme_program" name="cme_program" type="file" />
                                                </div>
                                                <?php if(strlen($cme_program)){ ?>
                                                    <a href="<?=base_url($cme_program)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="cme_program" type="hidden" name="cme_program_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('cme_program'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Draft copy/copies of Certificate(s) to be issued to doctors.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="draft_copy_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Draft copy/copies of Certificate(s) to be issued to doctors" <?=($draft_copy_type === 'Draft copy/copies of Certificate(s) to be issued to doctors')?'selected':''?>>Draft copy/copies of Certificate(s) to be issued to doctors</option>
                                                </select>
                                                <?= form_error("draft_copy_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="draft_copy" name="draft_copy" type="file" />
                                                </div>
                                                <?php if(strlen($draft_copy)){ ?>
                                                    <a href="<?=base_url($draft_copy)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="draft_copy" type="hidden" name="draft_copy_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('draft_copy'); ?>
                                            </td>
                                        </tr>                         
                                    
                                        <?php //End of if ?> 
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?=site_url('spservices/acmr_cp_cme/registration/index/'.$obj_id)?>" class="btn btn-primary">
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