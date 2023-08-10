<?php
$bride_idproof_type_frm = set_value("bride_idproof_type");
$groom_idproof_type_frm = set_value("groom_idproof_type");
$bride_ageproof_type_frm = set_value("bride_ageproof_type");
$marriage_notice_type_frm = set_value("marriage_notice_type");
$groom_ageproof_type_frm = set_value("groom_ageproof_type");
$bride_presentaddressproof_type_frm = set_value("bride_presentaddressproof_type");
$bride_permanentaddressproof_type_frm = set_value("bride_permanentaddressproof_type");
$groom_presentaddressproof_type_frm = set_value("groom_presentaddressproof_type");
$groom_permanentaddressproof_type_frm = set_value("groom_permanentaddressproof_type");
$bride_sign_type_frm = set_value("bride_sign_type");
$groom_sign_type_frm = set_value("groom_sign_type");
$declaration_certificate_type_frm = set_value("declaration_certificate_type");
$marriage_card_type_frm = set_value("marriage_card_type");
$soft_copy_type_frm = set_value("soft_copy_type");

$bride_idproof_type_db = $dbrow->bride_idproof_type??null;
$groom_idproof_type_db = $dbrow->groom_idproof_type??null;
$bride_ageproof_type_db = $dbrow->bride_ageproof_type??null;
$marriage_notice_type_db = $dbrow->marriage_notice_type??null;
$groom_ageproof_type_db = $dbrow->groom_ageproof_type??null;
$bride_presentaddressproof_type_db = $dbrow->bride_presentaddressproof_type??null;
$bride_permanentaddressproof_type_db = $dbrow->bride_permanentaddressproof_type??null;
$groom_presentaddressproof_type_db = $dbrow->groom_presentaddressproof_type??null;
$groom_permanentaddressproof_type_db = $dbrow->groom_permanentaddressproof_type??null;
$bride_sign_type_db = $dbrow->bride_sign_type??null;
$groom_sign_type_db = $dbrow->groom_sign_type??null;
$declaration_certificate_type_db = $dbrow->declaration_certificate_type??null;
$marriage_card_type_db = $dbrow->marriage_card_type??null;
$soft_copy_type_db = $dbrow->soft_copy_type??null;

$bride_idproof_type = strlen($bride_idproof_type_frm)?$bride_idproof_type_frm:$bride_idproof_type_db;
$groom_idproof_type = strlen($groom_idproof_type_frm)?$groom_idproof_type_frm:$groom_idproof_type_db;
$bride_ageproof_type = strlen($bride_ageproof_type_frm)?$bride_ageproof_type_frm:$bride_ageproof_type_db;
$marriage_notice_type = strlen($marriage_notice_type_frm)?$marriage_notice_type_frm:$marriage_notice_type_db;
$groom_ageproof_type = strlen($groom_ageproof_type_frm)?$groom_ageproof_type_frm:$groom_ageproof_type_db;
$bride_presentaddressproof_type = strlen($bride_presentaddressproof_type_frm)?$bride_presentaddressproof_type_frm:$bride_presentaddressproof_type_db;
$bride_permanentaddressproof_type = strlen($bride_permanentaddressproof_type_frm)?$bride_permanentaddressproof_type_frm:$bride_permanentaddressproof_type_db;
$groom_presentaddressproof_type = strlen($groom_presentaddressproof_type_frm)?$groom_presentaddressproof_type_frm:$groom_presentaddressproof_type_db;
$groom_permanentaddressproof_type = strlen($groom_permanentaddressproof_type_frm)?$groom_permanentaddressproof_type_frm:$groom_permanentaddressproof_type_db;
$bride_sign_type = strlen($bride_sign_type_frm)?$bride_sign_type_frm:$bride_sign_type_db;
$groom_sign_type = strlen($groom_sign_type_frm)?$groom_sign_type_frm:$groom_sign_type_db;
$declaration_certificate_type = strlen($declaration_certificate_type_frm)?$declaration_certificate_type_frm:$declaration_certificate_type_db;
$marriage_card_type = strlen($marriage_card_type_frm)?$marriage_card_type_frm:$marriage_card_type_db;
$soft_copy_type = strlen($soft_copy_type_frm)?$soft_copy_type_frm:$soft_copy_type_db;


//Attachment files
$uploadedFiles = $this->session->flashdata('uploaded_files');
$bride_idproof_frm = $uploadedFiles['bride_idproof_old']??null;
$groom_idproof_frm = $uploadedFiles['groom_idproof_old']??null;
$bride_ageproof_frm = $uploadedFiles['bride_ageproof_old']??null;
$marriage_notice_frm = $uploadedFiles['marriage_notice_old']??null;
$groom_ageproof_frm = $uploadedFiles['groom_ageproof_old']??null;
$bride_presentaddressproof_frm = $uploadedFiles['bride_presentaddressproof_old']??null;
$bride_permanentaddressproof_frm = $uploadedFiles['bride_permanentaddressproof_old']??null;
$groom_presentaddressproof_frm = $uploadedFiles['groom_presentaddressproof_old']??null;
$groom_permanentaddressproof_frm = $uploadedFiles['groom_permanentaddressproof_old']??null;
$bride_sign_frm = $uploadedFiles['bride_sign_old']??null;
$groom_sign_frm = $uploadedFiles['groom_sign_old']??null;
$declaration_certificate_frm = $uploadedFiles['declaration_certificate_old']??null;
$marriage_card_frm = $uploadedFiles['marriage_card_old']??null;
$soft_copy_frm = $uploadedFiles['soft_copy_old']??null;

$bride_idproof_db = $dbrow->bride_idproof??null;
$groom_idproof_db = $dbrow->groom_idproof??null;
$bride_ageproof_db = $dbrow->bride_ageproof??null;
$marriage_notice_db = $dbrow->marriage_notice??null;
$groom_ageproof_db = $dbrow->groom_ageproof??null;
$bride_presentaddressproof_db = $dbrow->bride_presentaddressproof??null;
$bride_permanentaddressproof_db = $dbrow->bride_permanentaddressproof??null;
$groom_presentaddressproof_db = $dbrow->groom_presentaddressproof??null;
$groom_permanentaddressproof_db = $dbrow->groom_permanentaddressproof??null;
$bride_sign_db = $dbrow->bride_sign??null;
$groom_sign_db = $dbrow->groom_sign??null;
$declaration_certificate_db = $dbrow->declaration_certificate??null;
$marriage_card_db = $dbrow->marriage_card??null;
$soft_copy_db = $dbrow->soft_copy??null;

$bride_idproof = strlen($bride_idproof_frm)?$bride_idproof_frm:$bride_idproof_db;
$groom_idproof = strlen($groom_idproof_frm)?$groom_idproof_frm:$groom_idproof_db;
$bride_ageproof = strlen($bride_ageproof_frm)?$bride_ageproof_frm:$bride_ageproof_db;
$marriage_notice = strlen($marriage_notice_frm)?$marriage_notice_frm:$marriage_notice_db;
$groom_ageproof = strlen($groom_ageproof_frm)?$groom_ageproof_frm:$groom_ageproof_db;
$bride_presentaddressproof = strlen($bride_presentaddressproof_frm)?$bride_presentaddressproof_frm:$bride_presentaddressproof_db;
$bride_permanentaddressproof = strlen($bride_permanentaddressproof_frm)?$bride_permanentaddressproof_frm:$bride_permanentaddressproof_db;
$groom_presentaddressproof = strlen($groom_presentaddressproof_frm)?$groom_presentaddressproof_frm:$groom_presentaddressproof_db;
$groom_permanentaddressproof = strlen($groom_permanentaddressproof_frm)?$groom_permanentaddressproof_frm:$groom_permanentaddressproof_db;
$bride_sign = strlen($bride_sign_frm)?$bride_sign_frm:$bride_sign_db;
$groom_sign = strlen($groom_sign_frm)?$groom_sign_frm:$groom_sign_db;
$declaration_certificate = strlen($declaration_certificate_frm)?$declaration_certificate_frm:$declaration_certificate_db;
$marriage_card = strlen($marriage_card_frm)?$marriage_card_frm:$marriage_card_db;
$soft_copy = strlen($soft_copy_frm)?$soft_copy_frm:$soft_copy_db;

//die("bride_idproof : ".$dbrow->bride_idproof);
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
        var bride_idproof = parseInt(<?=strlen($bride_idproof)?1:0?>);
        $("#bride_idproof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: bride_idproof?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        }); 
        
        var groom_idproof = parseInt(<?=strlen($groom_idproof)?1:0?>);
        $("#groom_idproof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: groom_idproof?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
        
        var bride_ageproof = parseInt(<?=strlen($bride_ageproof)?1:0?>);
        $("#bride_ageproof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: bride_ageproof?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
        
        var marriage_notice = parseInt(<?=strlen($marriage_notice)?1:0?>);
        $("#marriage_notice").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: marriage_notice?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
        
        var groom_ageproof = parseInt(<?=strlen($groom_ageproof)?1:0?>);
        $("#groom_ageproof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: groom_ageproof?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
        
        var bride_presentaddressproof = parseInt(<?=strlen($bride_presentaddressproof)?1:0?>);
        $("#bride_presentaddressproof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: bride_presentaddressproof?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
        
        var bride_permanentaddressproof = parseInt(<?=strlen($bride_permanentaddressproof)?1:0?>);
        $("#bride_permanentaddressproof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: bride_permanentaddressproof?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
        
        var groom_presentaddressproof = parseInt(<?=strlen($groom_presentaddressproof)?1:0?>);
        $("#groom_presentaddressproof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: groom_presentaddressproof?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
        
        var groom_permanentaddressproof = parseInt(<?=strlen($groom_permanentaddressproof)?1:0?>);
        $("#groom_permanentaddressproof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: groom_permanentaddressproof?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
        
        var bride_sign = parseInt(<?=strlen($bride_sign)?1:0?>);
        $("#bride_sign").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: bride_sign?false:true,
            maxFileSize: 200,
            allowedFileExtensions: ["jpg", "jpeg", "png"]
        });
        
        var groom_sign = parseInt(<?=strlen($groom_sign)?1:0?>);
        $("#groom_sign").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: groom_sign?false:true,
            maxFileSize: 200,
            allowedFileExtensions: ["jpg", "jpeg", "png"]
        });
        
        var declaration_certificate = parseInt(<?=strlen($declaration_certificate)?1:0?>);
        $("#declaration_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: declaration_certificate?false:true,
            maxFileSize: 2000,
            allowedFileExtensions: ["pdf"]
        });
        
        $("#marriage_card").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1000,
            allowedFileExtensions: ["pdf","jpg", "jpeg", "png"]
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
        <form method="POST" action="<?= base_url('spservices/marriageregistration/fileuploads/submit') ?>" enctype="multipart/form-data">
            <input value="<?=$dbrow->{'_id'}->{'$id'}?>" name="obj_id" type="hidden" />
            <input name="bride_idproof_old" value="<?=$bride_idproof?>" type="hidden" />
            <input name="groom_idproof_old" value="<?=$groom_idproof?>" type="hidden" />
            <input name="bride_ageproof_old" value="<?=$bride_ageproof?>" type="hidden" />
            <input name="marriage_notice_old" value="<?=$marriage_notice?>" type="hidden" />
            <input name="groom_ageproof_old" value="<?=$groom_ageproof?>" type="hidden" />
            <input name="bride_presentaddressproof_old" value="<?=$bride_presentaddressproof?>" type="hidden" />
            <input name="bride_permanentaddressproof_old" value="<?=$bride_permanentaddressproof?>" type="hidden" />
            <input name="groom_presentaddressproof_old" value="<?=$groom_presentaddressproof?>" type="hidden" />
            <input name="groom_permanentaddressproof_old" value="<?=$groom_permanentaddressproof?>" type="hidden" />
            <input name="bride_sign_old" value="<?=$bride_sign?>" type="hidden" />
            <input name="groom_sign_old" value="<?=$groom_sign?>" type="hidden" />
            <input name="declaration_certificate_old" value="<?=$declaration_certificate?>" type="hidden" />
            <input name="marriage_card_old" value="<?=$marriage_card?>" type="hidden" />
            <input name="soft_copy_old" value="<?=$soft_copy?>" type="hidden" />
            <div class="card shadow-sm">
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
                                            <td>Identity proof of Bride/Wife<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="bride_idproof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Driving Licence" <?=($bride_idproof_type === 'Driving Licence')?'selected':''?>>Driving Licence</option>                 
                                                    <option value="Voter ID Card" <?=($bride_idproof_type === 'Voter ID Card')?'selected':''?>>Voter ID Card</option>                 
                                                    <option value="Copy of Passport" <?=($bride_idproof_type === 'Copy of Passport')?'selected':''?>>Copy of Passport</option>                 
                                                    <option value="Copy of PAN Card" <?=($bride_idproof_type === 'Copy of PAN Card')?'selected':''?>>Copy of PAN Card</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="bride_idproof" name="bride_idproof" type="file" />
                                                </div>                                                
                                                <?php if(strlen($bride_idproof)){ ?>
                                                    <a href="<?=base_url($bride_idproof)?>" class="btn text-warning pl-0" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download uploaded file
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Identity proof of Groom/Husband<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="groom_idproof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Driving Licence" <?=($groom_idproof_type === 'Driving Licence')?'selected':''?>>Driving Licence</option>                 
                                                    <option value="Voter ID Card" <?=($groom_idproof_type === 'Voter ID Card')?'selected':''?>>Voter ID Card</option>                 
                                                    <option value="Copy of Passport" <?=($groom_idproof_type === 'Copy of Passport')?'selected':''?>>Copy of Passport</option>                 
                                                    <option value="Copy of PAN Card" <?=($groom_idproof_type === 'Copy of PAN Card')?'selected':''?>>Copy of PAN Card</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="groom_idproof" name="groom_idproof" type="file" />
                                                </div>                               
                                                <?php if(strlen($groom_idproof)){ ?>
                                                    <a href="<?=base_url($groom_idproof)?>" class="btn text-warning pl-0" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download uploaded file
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Age proof of Bride/Wife<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="bride_ageproof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Birth Certificate Of Bride" <?=($bride_ageproof_type === 'Birth Certificate Of Bride')?'selected':''?>>Birth Certificate Of Bride</option>                 
                                                    <option value="A copy of Proof of date of birth" <?=($bride_ageproof_type === 'A copy of Proof of date of birth')?'selected':''?>>A copy of Proof of date of birth</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="bride_ageproof" name="bride_ageproof" type="file" />
                                                </div>                               
                                                <?php if(strlen($bride_ageproof)){ ?>
                                                    <a href="<?=base_url($bride_ageproof)?>" class="btn text-warning pl-0" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download uploaded file
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Marriage Notice<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="marriage_notice_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Copy of Marriage Notice" <?=($marriage_notice_type === 'Copy of Marriage Notice')?'selected':''?>>Copy of Marriage Notice</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="marriage_notice" name="marriage_notice" type="file" />
                                                </div>                               
                                                <?php if(strlen($marriage_notice)){ ?>
                                                    <a href="<?=base_url($marriage_notice)?>" class="btn text-warning pl-0" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download uploaded file
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Age proof of Groom/Husband<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="groom_ageproof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Birth Certificate Of Groom" <?=($groom_ageproof_type === 'Birth Certificate Of Groom')?'selected':''?>>Birth Certificate Of Groom</option>                 
                                                    <option value="A copy of Proof of date of birth" <?=($groom_ageproof_type === 'A copy of Proof of date of birth')?'selected':''?>>A copy of Proof of date of birth</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="groom_ageproof" name="groom_ageproof" type="file" />
                                                </div>                               
                                                <?php if(strlen($groom_ageproof)){ ?>
                                                    <a href="<?=base_url($groom_ageproof)?>" class="btn text-warning pl-0" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download uploaded file
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Present Address Proof of Bride/Wife<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="bride_presentaddressproof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Telephone Bill" <?=($bride_presentaddressproof_type === 'Telephone Bill')?'selected':''?>>Telephone Bill</option>                 
                                                    <option value="Copy of Passport" <?=($bride_presentaddressproof_type === 'Copy of Passport')?'selected':''?>>Copy of Passport</option>                 
                                                    <option value="Photocopy of the Bank Pass Book of the Applicant" <?=($bride_presentaddressproof_type === 'Photocopy of the Bank Pass Book of the Applicant')?'selected':''?>>Photocopy of the Bank Pass Book of the Applicant</option>                 
                                                    <option value="Driving License" <?=($bride_presentaddressproof_type === 'Driving License')?'selected':''?>>Driving License</option>                 
                                                    <option value="Photo Identity card with address (of Central Govt./PSU or State Govt./PSU only)" <?=($bride_presentaddressproof_type === 'Photo Identity card with address (of Central Govt./PSU or State Govt./PSU only)')?'selected':''?>>Photo Identity card with address (of Central Govt./PSU or State Govt./PSU only)</option>                 
                                                    <option value="Address Card having Name and Photo issued by Department of Posts" <?=($bride_presentaddressproof_type === 'Address Card having Name and Photo issued by Department of Posts')?'selected':''?>>Address Card having Name and Photo issued by Department of Posts</option>
                                                    <option value="Electricity bill certified by Land Owner" <?=($bride_presentaddressproof_type === 'Electricity bill certified by Land Owner')?'selected':''?>>Electricity bill certified by Land Owner</option>                 
                                                    <option value="Telephone Bill certified by Land Owner" <?=($bride_presentaddressproof_type === 'Telephone Bill certified by Land Owner')?'selected':''?>>Telephone Bill certified by Land Owner</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="bride_presentaddressproof" name="bride_presentaddressproof" type="file" />
                                                </div>                               
                                                <?php if(strlen($bride_presentaddressproof)){ ?>
                                                    <a href="<?=base_url($bride_presentaddressproof)?>" class="btn text-warning pl-0" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download uploaded file
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Permanent Address Proof of Bride/Wife<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="bride_permanentaddressproof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Driving License" <?=($bride_permanentaddressproof_type === 'Driving License')?'selected':''?>>Driving License</option>
                                                    <option value="Telephone Bill" <?=($bride_permanentaddressproof_type === 'Telephone Bill')?'selected':''?>>Telephone Bill</option>                 
                                                    <option value="Copy of Passport" <?=($bride_permanentaddressproof_type === 'Copy of Passport')?'selected':''?>>Copy of Passport</option>
                                                    <option value="Photocopy of the Bank Pass Book of the Applicant" <?=($bride_permanentaddressproof_type === 'Photocopy of the Bank Pass Book of the Applicant')?'selected':''?>>Photocopy of the Bank Pass Book of the Applicant</option>                 
                                                    <option value="Photo Identity card with address (of Central Govt./PSU or State Govt./PSU only)" <?=($bride_permanentaddressproof_type === 'Photo Identity card with address (of Central Govt./PSU or State Govt./PSU only)')?'selected':''?>>Photo Identity card with address (of Central Govt./PSU or State Govt./PSU only)</option>                 
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="bride_permanentaddressproof" name="bride_permanentaddressproof" type="file" />
                                                </div>                               
                                                <?php if(strlen($bride_permanentaddressproof)){ ?>
                                                    <a href="<?=base_url($bride_permanentaddressproof)?>" class="btn text-warning pl-0" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download uploaded file
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Present Address Proof of Groom/Husband<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="groom_presentaddressproof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Driving Licence" <?=($groom_presentaddressproof_type === 'Driving Licence')?'selected':''?>>Driving Licence</option>                 
                                                    <option value="Telephone Bill" <?=($groom_presentaddressproof_type === 'Telephone Bill')?'selected':''?>>Telephone Bill</option>                 
                                                    <option value="Copy of Passport" <?=($groom_presentaddressproof_type === 'Copy of Passport')?'selected':''?>>Copy of Passport</option>                 
                                                    <option value="Photocopy of the Bank Pass Book of the Applicant" <?=($groom_presentaddressproof_type === 'Photocopy of the Bank Pass Book of the Applicant')?'selected':''?>>Photocopy of the Bank Pass Book of the Applicant</option>                 
                                                    <option value="Photo Identity card with address (of Central Govt./PSU or State Govt./PSU only)" <?=($groom_presentaddressproof_type === 'Photo Identity card with address (of Central Govt./PSU or State Govt./PSU only)')?'selected':''?>>Photo Identity card with address (of Central Govt./PSU or State Govt./PSU only)</option>                 
                                                    <option value="Address Card having Name and Photo issued by Department of Posts" <?=($groom_presentaddressproof_type === 'Address Card having Name and Photo issued by Department of Posts')?'selected':''?>>Address Card having Name and Photo issued by Department of Posts</option>                 
                                                    <option value="Electricity bill certified by Land Owner" <?=($groom_presentaddressproof_type === 'Electricity bill certified by Land Owner')?'selected':''?>>Electricity bill certified by Land Owner</option>                 
                                                    <option value="Telephone Bill certified by Land Owner" <?=($groom_presentaddressproof_type === 'Telephone Bill certified by Land Owner')?'selected':''?>>Telephone Bill certified by Land Owner</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="groom_presentaddressproof" name="groom_presentaddressproof" type="file" />
                                                </div>                               
                                                <?php if(strlen($groom_presentaddressproof)){ ?>
                                                    <a href="<?=base_url($groom_presentaddressproof)?>" class="btn text-warning pl-0" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download uploaded file
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Permanent Address Proof of Groom/Husband<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="groom_permanentaddressproof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Driving License" <?=($groom_permanentaddressproof_type === 'Driving License')?'selected':''?>>Driving License</option>
                                                    <option value="Telephone Bill" <?=($groom_permanentaddressproof_type === 'Telephone Bill')?'selected':''?>>Telephone Bill</option>                 
                                                    <option value="Copy of Passport" <?=($groom_permanentaddressproof_type === 'Copy of Passport')?'selected':''?>>Copy of Passport</option>
                                                    <option value="Photocopy of the Bank Pass Book of the Applicant" <?=($groom_permanentaddressproof_type === 'Photocopy of the Bank Pass Book of the Applicant')?'selected':''?>>Photocopy of the Bank Pass Book of the Applicant</option>                 
                                                    <option value="Photo Identity card with address (of Central Govt./PSU or State Govt./PSU only)" <?=($groom_permanentaddressproof_type === 'Photo Identity card with address (of Central Govt./PSU or State Govt./PSU only)')?'selected':''?>>Photo Identity card with address (of Central Govt./PSU or State Govt./PSU only)</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="groom_permanentaddressproof" name="groom_permanentaddressproof" type="file" />
                                                </div>                               
                                                <?php if(strlen($groom_permanentaddressproof)){ ?>
                                                    <a href="<?=base_url($groom_permanentaddressproof)?>" class="btn text-warning pl-0" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download uploaded file
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Signature of Wife<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="bride_sign_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Copy of Signature of Wife" <?=($bride_sign_type === 'Copy of Signature of Wife')?'selected':''?>>Copy of Signature of Wife</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="bride_sign" name="bride_sign" type="file" />
                                                </div>                               
                                                <?php if(strlen($bride_sign)){ ?>
                                                    <a href="<?=base_url($bride_sign)?>" class="btn text-warning pl-0" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download uploaded file
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Signature of Husband<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="groom_sign_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Copy of Signature of Husband" <?=($groom_sign_type === 'Copy of Signature of Husband')?'selected':''?>>Copy of Signature of Husband</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="groom_sign" name="groom_sign" type="file" />
                                                </div>                               
                                                <?php if(strlen($groom_sign)){ ?>
                                                    <a href="<?=base_url($groom_sign)?>" class="btn text-warning pl-0" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download uploaded file
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Declaration Certificate by Parties<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="declaration_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Declaration Certificate by Parties" <?=($declaration_certificate_type === 'Declaration Certificate by Parties')?'selected':''?>>Declaration Certificate by Parties</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="declaration_certificate" name="declaration_certificate" type="file" />
                                                </div>                               
                                                <?php if(strlen($declaration_certificate)){ ?>
                                                    <a href="<?=base_url($declaration_certificate)?>" class="btn text-warning pl-0" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download uploaded file
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Marriage Card of Both Parties (if available)</td>
                                            <td>
                                                <select name="marriage_card_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Marriage Card of Both Parties (if available)" <?=($marriage_card_type === 'Marriage Card of Both Parties (if available)')?'selected':''?>>Marriage Card of Both Parties (if available)</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="marriage_card" name="marriage_card" type="file" />
                                                </div>                               
                                                <?php if(strlen($marriage_card)){ ?>
                                                    <a href="<?=base_url($marriage_card)?>" class="btn text-warning pl-0" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download uploaded file
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
                                                        <a href="<?=base_url($soft_copy)?>" class="btn text-warning pl-0" target="_blank">
                                                            <span class="fa fa-download"></span> 
                                                            View/Download uploaded file
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
                    <a href="<?=site_url('spservices/marriageregistration/groomdetails/index/'.$dbrow->{'_id'}->{'$id'})?>" class="btn btn-primary">
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