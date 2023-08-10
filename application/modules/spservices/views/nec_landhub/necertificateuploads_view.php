<?php
$land_patta_type_frm = set_value("land_patta_type");
$khajna_receipt_type_frm = set_value("khajna_receipt_type");
$soft_copy_type_frm = set_value("soft_copy_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');
$land_patta_frm = $uploadedFiles['land_patta_old']??null;
$khajna_receipt_frm = $uploadedFiles['khajna_receipt_old']??null;
$soft_copy_frm = $uploadedFiles['soft_copy_old']??null;

$land_patta_type_db = $dbrow->land_patta_type??null;
$khajna_receipt_type_db = $dbrow->khajna_receipt_type??null;
$soft_copy_type_db = $dbrow->soft_copy_type??null;
$land_patta_db = $dbrow->land_patta??null;
$khajna_receipt_db = $dbrow->khajna_receipt??null;
$soft_copy_db = $dbrow->soft_copy??null;

$land_patta_type = strlen($land_patta_type_frm)?$land_patta_type_frm:$land_patta_type_db;
$khajna_receipt_type = strlen($khajna_receipt_type_frm)?$khajna_receipt_type_frm:$khajna_receipt_type_db;
$soft_copy_type = strlen($soft_copy_type_frm)?$soft_copy_type_frm:$soft_copy_type_db;

$land_patta = strlen($land_patta_frm)?$land_patta_frm:$land_patta_db;
$khajna_receipt = strlen($khajna_receipt_frm)?$khajna_receipt_frm:$khajna_receipt_db;
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
        var landPatta = parseInt(<?=strlen($land_patta)?1:0?>);
        $("#land_patta").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: landPatta?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        }); 
        
        var khajnaReceipt = parseInt(<?=strlen($khajna_receipt)?1:0?>);
        $("#khajna_receipt").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: khajnaReceipt?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
        
        /*$("#soft_copy").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });*/
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form method="POST" action="<?= base_url('spservices/nec_landhub/necertificate/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
            <input name="land_patta_old" value="<?=$land_patta?>" type="hidden" />
            <input name="khajna_receipt_old" value="<?=$khajna_receipt?>" type="hidden" />
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
                                            <td>Up-to-date Original Land Documents.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="land_patta_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Land patta" <?=($land_patta_type === 'Land patta')?'selected':''?>>Land patta</option>
                                                </select>
                                                <?= form_error("land_patta_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="land_patta" name="land_patta" type="file" />
                                                </div>
                                                <?php if(strlen($land_patta)){ ?>
                                                    <a href="<?=base_url($land_patta)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Up-to-date Khajna Receipt.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="khajna_receipt_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Up-to-date Khajna Receipt" <?=($khajna_receipt_type === 'Up-to-date Khajna Receipt')?'selected':''?>>Up-to-date Khajna Receipt</option>
                                                </select>
                                                <?= form_error("khajna_receipt_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="khajna_receipt" name="khajna_receipt" type="file" />
                                                </div>
                                                <?php if(strlen($khajna_receipt)){ ?>
                                                    <a href="<?=base_url($khajna_receipt)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                            
                                        <?php if($this->slug !== 'user') { ?>
                                            <!--<tr>
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
                                            </tr>-->
                                        <?php }//End of if ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?=site_url('spservices/nec_landhub/necertificate/index/'.$obj_id)?>" class="btn btn-primary">
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