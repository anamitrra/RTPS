<?php
$admit_card_type_frm = set_value("admit_card_type");
$hs_marksheet_type_frm = set_value("hs_marksheet_type");
$reg_certificate_type_frm = set_value("reg_certificate_type");
$mbbs_marksheet_type_frm = set_value("mbbs_marksheet_type");
$pass_certificate_type_frm = set_value("pass_certificate_type");
$college_noc_type_frm = set_value("college_noc_type");
$director_noc_type_frm = set_value("director_noc_type");
$university_noc_type_frm = set_value("university_noc_type");
$institute_noc_type_frm = set_value("institute_noc_type");
$eligibility_certificate_type_frm = set_value("eligibility_certificate_type");
$screening_result_type_frm = set_value("screening_result_type");
$passport_visa_type_frm = set_value("passport_visa_type");
$all_docs_type_frm = set_value("all_docs_type");
$annexure_type_frm = set_value("annexure_type");
$ten_pass_certificate_frm = set_value("ten_pass_certificate");
$photograph_type_frm = set_value("photograph_type");
$signature_frm = set_value("signature");



$uploadedFiles = $this->session->flashdata('uploaded_files');
$admit_card_frm = $uploadedFiles['admit_card_old']??null;
$hs_marksheet_frm = $uploadedFiles['hs_marksheet_old']??null;
$reg_certificate_frm = $uploadedFiles['reg_certificate_old']??null;
$mbbs_marksheet_frm = $uploadedFiles['mbbs_marksheet_old']??null;
$pass_certificate_frm = $uploadedFiles['pass_certificate_old']??null;
$college_noc_frm = $uploadedFiles['college_noc_old']??null;
$director_noc_frm = $uploadedFiles['director_noc_old']??null;
$university_noc_frm = $uploadedFiles['university_noc_old']??null;
$institute_noc_frm = $uploadedFiles['institute_noc_old']??null;
$eligibility_certificate_frm = $uploadedFiles['eligibility_certificate_old']??null;
$screening_result_frm = $uploadedFiles['screening_result_old']??null;
$passport_visa_frm = $uploadedFiles['passport_visa_old']??null;
$all_docs_frm = $uploadedFiles['all_docs_old']??null;
$annexure_frm = $uploadedFiles['annexure_old']??null;
$ten_pass_certificate_frm = $uploadedFiles['ten_pass_certificate_old']??null;
$photograph_frm = $uploadedFiles['photograph_old']??null;
$signature_frm = $uploadedFiles['signature_old']??null;


$admit_card_type_db = $dbrow->form_data->admit_card_type??null;
$hs_marksheet_type_db = $dbrow->form_data->hs_marksheet_type??null;
$reg_certificate_type_db = $dbrow->form_data->reg_certificate_type??null;
$mbbs_marksheet_type_db = $dbrow->form_data->mbbs_marksheet_type??null;
$pass_certificate_type_db = $dbrow->form_data->pass_certificate_type??null;
$college_noc_type_db = $dbrow->form_data->college_noc_type??null;
$director_noc_type_db = $dbrow->form_data->director_noc_type??null;
$university_noc_type_db = $dbrow->form_data->university_noc_type??null;
$institute_noc_type_db = $dbrow->form_data->institute_noc_type??null;
$eligibility_certificate_type_db = $dbrow->form_data->eligibility_certificate_type??null;
$screening_result_type_db = $dbrow->form_data->screening_result_type??null;
$passport_visa_type_db = $dbrow->form_data->passport_visa_type??null;
$all_docs_type_db = $dbrow->form_data->all_docs_type??null;
$annexure_type_db = $dbrow->form_data->annexure_type??null;
$ten_pass_certificate_type_db = $dbrow->form_data->ten_pass_certificate_type??null;
$photograph_type_db = $dbrow->form_data->photograph_type??null;
$signature_type_db = $dbrow->form_data->signature_type??null;


$admit_card_db = $dbrow->form_data->admit_card??null;
$hs_marksheet_db = $dbrow->form_data->hs_marksheet??null;
$reg_certificate_db = $dbrow->form_data->reg_certificate??null;
$mbbs_marksheet_db = $dbrow->form_data->mbbs_marksheet??null;
$pass_certificate_db = $dbrow->form_data->pass_certificate??null;
$college_noc_db = $dbrow->form_data->college_noc??null;
$director_noc_db = $dbrow->form_data->director_noc??null;
$university_noc_db = $dbrow->form_data->university_noc??null;
$institute_noc_db = $dbrow->form_data->institute_noc??null;
$eligibility_certificate_db = $dbrow->form_data->eligibility_certificate??null;
$screening_result_db = $dbrow->form_data->screening_result??null;
$passport_visa_db = $dbrow->form_data->passport_visa??null;
$all_docs_db = $dbrow->form_data->all_docs??null;
$annexure_db = $dbrow->form_data->annexure_docs??null;
$ten_pass_certificate_db = $dbrow->form_data->ten_pass_certificate_docs??null;
$photograph_db = $dbrow->form_data->photograph_docs??null;
$signature_db = $dbrow->form_data->signature_docs??null;


$admit_card_type = strlen($admit_card_type_frm)?$admit_card_type_frm:$admit_card_type_db;
$hs_marksheet_type = strlen($hs_marksheet_type_frm)?$hs_marksheet_type_frm:$hs_marksheet_type_db;
$reg_certificate_type = strlen($reg_certificate_type_frm)?$reg_certificate_type_frm:$reg_certificate_type_db;
$mbbs_marksheet_type = strlen($mbbs_marksheet_type_frm)?$mbbs_marksheet_type_frm:$mbbs_marksheet_type_db;
$pass_certificate_type = strlen($pass_certificate_type_frm)?$pass_certificate_type_frm:$pass_certificate_type_db;
$college_noc_type = strlen($college_noc_type_frm)?$college_noc_type_frm:$college_noc_type_db;
$director_noc_type = strlen($director_noc_type_frm)?$director_noc_type_frm:$director_noc_type_db;
$university_noc_type = strlen($university_noc_type_frm)?$university_noc_type_frm:$university_noc_type_db;
$institute_noc_type = strlen($institute_noc_type_frm)?$institute_noc_type_frm:$institute_noc_type_db;
$eligibility_certificate_type = strlen($eligibility_certificate_type_frm)?$eligibility_certificate_type_frm:$eligibility_certificate_type_db;
$screening_result_type = strlen($screening_result_type_frm)?$screening_result_type_frm:$screening_result_type_db;
$passport_visa_type = strlen($passport_visa_type_frm)?$passport_visa_type_frm:$passport_visa_type_db;
$all_docs_type = strlen($all_docs_type_frm)?$all_docs_type_frm:$all_docs_type_db;
$annexure_type = strlen($annexure_type_frm)?$annexure_type_frm:$annexure_type_db;
$ten_pass_certificate_type = strlen($ten_pass_certificate_frm)?$ten_pass_certificate_type_frm:$ten_pass_certificate_type_db;
$photograph_type = strlen($photograph_type_frm)?$photograph_type_frm:$photograph_type_db;
$signature_type = strlen($signature_frm)?$signature_type_frm:$signature_type_db;


$admit_card = strlen($admit_card_frm)?$admit_card_frm:$admit_card_db;
$hs_marksheet = strlen($hs_marksheet_frm)?$hs_marksheet_frm:$hs_marksheet_db;
$reg_certificate = strlen($reg_certificate_frm)?$reg_certificate_frm:$reg_certificate_db;
$mbbs_marksheet = strlen($mbbs_marksheet_frm)?$mbbs_marksheet_frm:$mbbs_marksheet_db;
$pass_certificate = strlen($pass_certificate_frm)?$pass_certificate_frm:$pass_certificate_db;
$college_noc = strlen($college_noc_frm)?$college_noc_frm:$college_noc_db;
$director_noc = strlen($director_noc_frm)?$director_noc_frm:$director_noc_db;
$university_noc = strlen($university_noc_frm)?$university_noc_frm:$university_noc_db;
$institute_noc = strlen($institute_noc_frm)?$institute_noc_frm:$institute_noc_db;
$eligibility_certificate = strlen($eligibility_certificate_frm)?$eligibility_certificate_frm:$eligibility_certificate_db;
$screening_result = strlen($screening_result_frm)?$screening_result_frm:$screening_result_db;
$passport_visa = strlen($passport_visa_frm)?$passport_visa_frm:$passport_visa_db;
$all_docs = strlen($all_docs_frm)?$all_docs_frm:$all_docs_db;
$annexure = strlen($annexure_frm)?$annexure_frm:$annexure_db;
$ten_pass_certificate = strlen($ten_pass_certificate_frm)?$ten_pass_certificate_frm:$ten_pass_certificate_db;
$photograph = strlen($photograph_frm)?$photograph_frm:$photograph_db;
$signature = strlen($signature_frm)?$signature_frm:$signature_db;
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
        var admitcard = parseInt(<?=strlen($admit_card)?1:0?>);
        $("#admit_card").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: admitbirth?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var hsmarksheet = parseInt(<?=strlen($hs_marksheet)?1:0?>);
        $("#hs_marksheet").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: hsmarksheet?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var regcertificate = parseInt(<?=strlen($reg_certificate)?1:0?>);
        $("#reg_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: regcertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var mbbsmarksheet = parseInt(<?=strlen($mbbs_marksheet)?1:0?>);
        $("#mbbs_marksheet").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: mbbsmarksheet?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var passcertificate = parseInt(<?=strlen($pass_certificate)?1:0?>);
        $("#pass_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: passcertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var collegenoc = parseInt(<?=strlen($college_noc)?1:0?>);
        $("#college_noc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: collegenoc?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var directornoc = parseInt(<?=strlen($director_noc)?1:0?>);
        $("#director_noc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: directornoc?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var universitynoc = parseInt(<?=strlen($university_noc)?1:0?>);
        $("#university_noc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: universitynoc?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        var institutenoc = parseInt(<?=strlen($institute_noc)?1:0?>);
        $("#institute_noc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: institutenoc?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var eligibilitycertificate = parseInt(<?=strlen($eligibility_certificate)?1:0?>);
        $("#eligibility_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: eligibilitycertificate?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var screeningresult = parseInt(<?=strlen($screening_result)?1:0?>);
        $("#screening_result").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
           // required: screeningresult?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        }); 

        var passportvisa = parseInt(<?=strlen($passport_visa)?1:0?>);
        $("#passport_visa").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: passportvisa?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var alldocs = parseInt(<?=strlen($all_docs)?1:0?>);
        $("#all_docs").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            //required: alldocs?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

         var annexure = parseInt(<?=strlen($annexure)?1:0?>);
        $("#annexure").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: alldocs?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        
        var ten_pass_certificate = parseInt(<?=strlen($ten_pass_certificate)?1:0?>);
        $("#ten_pass_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: alldocs?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
          });

        var signature = parseInt(<?=strlen($signature)?1:0?>);
        $("#signature").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: alldocs?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg","jpeg"]
          });
        var photograph = parseInt(<?=strlen($photograph)?1:0?>);
        $("#photograph").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: alldocs?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg","jpeg"]
          });
    });
</script>     
<main class="rtps-container">
    <div class="container my-2">
        <form method="POST" action="<?= base_url('spservices/acmr_provisional_certificate/registration/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
            <input name="admit_card_old" value="<?=$admit_card?>" type="hidden" />
            <input name="hs_marksheet_old" value="<?=$hs_marksheet?>" type="hidden" />
            <input name="reg_certificate_old" value="<?=$reg_certificate?>" type="hidden" />
            <input name="mbbs_marksheet_old" value="<?=$mbbs_marksheet?>" type="hidden" />
            <input name="pass_certificate_old" value="<?=$pass_certificate?>" type="hidden" />
            <input name="college_noc_old" value="<?=$college_noc?>" type="hidden" />
            <input name="director_noc_old" value="<?=$director_noc?>" type="hidden" />
            <input name="university_noc_old" value="<?=$university_noc?>" type="hidden" />
            <input name="institute_noc_old" value="<?=$institute_noc?>" type="hidden" />
            <input name="eligibility_certificate_old" value="<?=$eligibility_certificate?>" type="hidden" />
            <input name="screening_result_old" value="<?=$screening_result?>" type="hidden" />
            <input name="passport_visa_old" value="<?=$passport_visa?>" type="hidden" />
            <input name="all_docs_old" value="<?=$all_docs?>" type="hidden" />
            <input name="ten_pass_certificate_old" value="<?=$ten_pass_certificate?>" type="hidden" />
            <input name="photograph_old" value="<?=$photograph?>" type="hidden" />
            <input name="signature_old" value="<?=$signature?>" type="hidden" />
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
                        <legend class="h5">Please Download this Annexure II and upload </legend>
                        <a href="<?= base_url('assets/acmr/annexure.pdf') ?>" target="_blank">Please Click</a>
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
                                    <?php if(($dbrow->form_data->study_place == 1) || ($dbrow->form_data->study_place == 2) || ($dbrow->form_data->study_place == 3)){ ?> 
                                         <tr>
                                            <td>Photograph<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="photograph_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Photograph" <?=($photograph_type === 'Photograph')?'selected':''?>>Photograph</option>
                                                </select>
                                                <?= form_error("photograph_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="photograph" name="photograph" type="file" />
                                                </div>
                                                <?php if(strlen($photograph)){ ?>
                                                    <a href="<?=base_url($photograph)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="photograph" type="hidden" name="photograph_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('photograph'); ?>
                                            </td>
                                        </tr>
                                       
                                         <tr>
                                            <td>Signature<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="signature_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Signature" <?=($signature_type === 'Signature')?'selected':''?>>Signature</option>
                                                </select>
                                                <?= form_error("signature_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="signature" name="signature" type="file" />
                                                </div>
                                                <?php if(strlen($signature)){ ?>
                                                    <a href="<?=base_url($signature)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="signature" type="hidden" name="signature_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('signature'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Class 10 Admit card<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="admit_card_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Class 10 Admit card" <?=($admit_card_type === 'Class 10 Admit card')?'selected':''?>>Class 10 Admit card</option>
                                                </select>
                                                <?= form_error("admit_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="admit_card" name="admit_card" type="file" />
                                                </div>
                                                <?php if(strlen($admit_card)){ ?>
                                                    <a href="<?=base_url($admit_card)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="admit_card" type="hidden" name="admit_card_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('admit_card'); ?>
                                            </td>
                                        </tr>
                                          <tr>
                                            <td>Class 10 Pass Certificate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="ten_pass_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Class 10 Pass Certificate" <?=($ten_pass_certificate_type === 'Class 10 Pass Certificate')?'selected':''?>>Class 10 Pass Certificate</option>
                                                </select>
                                                <?= form_error("ten_pass_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="ten_pass_certificate" name="ten_pass_certificate" type="file" />
                                                </div>
                                                <?php if(strlen($ten_pass_certificate)){ ?>
                                                    <a href="<?=base_url($ten_pass_certificate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="ten_pass_certificate" type="hidden" name="ten_pass_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('ten_pass_certificate'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>HS Final Marksheet<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="hs_marksheet_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="HS Final Marksheet" <?=($hs_marksheet_type === 'HS Final Marksheet')?'selected':''?>>HS Final Marksheet</option>
                                                </select>
                                                <?= form_error("hs_marksheet_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hs_marksheet" name="hs_marksheet" type="file" />
                                                </div>
                                                <?php if(strlen($hs_marksheet)){ ?>
                                                    <a href="<?=base_url($hs_marksheet)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                 <?php }//End of if ?>
                                                <input class="hs_marksheet" type="hidden" name="hs_marksheet_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('hs_marksheet'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>University Registration Certificate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="reg_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="University Registration Certificate" <?=($reg_certificate_type === 'University Registration Certificate')?'selected':''?>>University Registration Certificate</option>
                                                </select>
                                                <?= form_error("reg_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="reg_certificate" name="reg_certificate" type="file" />
                                                </div>
                                                <?php if(strlen($reg_certificate)){ ?>
                                                    <a href="<?=base_url($reg_certificate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                 <?php }//End of if ?>
                                                <input class="reg_certificate" type="hidden" name="reg_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('reg_certificate'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>All Marksheets of MBBS/Transcript<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="mbbs_marksheet_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="All Marksheets of MBBS" <?=($mbbs_marksheet_type === 'All Marksheets of MBBS')?'selected':''?>>All Marksheets of MBBS</option>
                                                </select>
                                                <?= form_error("mbbs_marksheet_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="mbbs_marksheet" name="mbbs_marksheet" type="file" />
                                                </div>
                                                <?php if(strlen($mbbs_marksheet)){ ?>
                                                    <a href="<?=base_url($mbbs_marksheet)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="mbbs_marksheet" type="hidden" name="mbbs_marksheet_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('mbbs_marksheet'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>MBBS or equivalent Pass Certificate from College/University<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="pass_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="MBBS or equivalent Pass Certificate from College/University" <?=($pass_certificate_type === 'MBBS or equivalent Pass Certificate from College/University')?'selected':''?>>MBBS or equivalent Pass Certificate from College/University</option>
                                                </select>
                                                <?= form_error("pass_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="pass_certificate" name="pass_certificate" type="file" />
                                                </div>
                                                <?php if(strlen($pass_certificate)){ ?>
                                                    <a href="<?=base_url($pass_certificate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                 <?php }//End of if ?>
                                                <input class="pass_certificate" type="hidden" name="pass_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('pass_certificate'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Annexure II</td>
                                            <td>
                                                <select name="annexure_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Annexure II" <?=($annexure_type === 'Annexure II')?'selected':''?>>Annexure II</option>
                                                </select>
                                                <?= form_error("annexure_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="annexure" name="annexure" type="file" />
                                                </div>
                                                <?php if(strlen($annexure)){ ?>
                                                    <a href="<?=base_url($annexure)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                 <?php }//End of if ?>
                                                <input class="annexure" type="hidden" name="annexure_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('annexure'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        <?php if(($dbrow->form_data->study_place == 2) || ($dbrow->form_data->study_place == 3)){ ?> 
                                        <tr>
                                            <td>NOC from College/University<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="college_noc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="NOC from College/University" <?=($college_noc_type === 'NOC from College/University')?'selected':''?>>NOC from College/University</option>
                                                </select>
                                                <?= form_error("college_noc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="college_noc" name="college_noc" type="file" />
                                                </div>
                                                <?php if(strlen($college_noc)){ ?>
                                                    <a href="<?=base_url($college_noc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                  <?php }//End of if ?>
                                                <input class="college_noc" type="hidden" name="college_noc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('college_noc'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>NOC from Director of Medical Education, Assam<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="director_noc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="NOC from Director of Medical Education,Assam" <?=($director_noc_type === 'NOC from Director of Medical Education,Assam')?'selected':''?>>NOC from Director of Medical Education, Assam</option>
                                                </select>
                                                <?= form_error("director_noc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="director_noc" name="director_noc" type="file" />
                                                </div>
                                                <?php if(strlen($director_noc)){ ?>
                                                    <a href="<?=base_url($director_noc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                               <?php }//End of if ?>
                                                <input class="director_noc" type="hidden" name="director_noc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('director_noc'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>NOC from Srimanta Sankaradeva University of Health Sciences</td>
                                            <td>
                                                <select name="university_noc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="NOC from Srimanta Sankaradeva University of Health Sciences" <?=($university_noc_type === 'NOC from Srimanta Sankaradeva University of Health Sciences')?'selected':''?>>NOC from Srimanta Sankaradeva University of Health Sciences</option>
                                                </select>
                                                <?= form_error("university_noc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="university_noc" name="university_noc" type="file" />
                                                </div>
                                                <?php if(strlen($university_noc)){ ?>
                                                    <a href="<?=base_url($university_noc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                 <?php }//End of if ?>
                                                <input class="university_noc" type="hidden" name="university_noc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('university_noc'); ?>
                                            </td>
                                        </tr>

                                         <tr>
                                            <td>NOC from the Institute from where applicant want to do the internship<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="institute_noc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="NOC from the Institute from where applicant want to do the internship" <?=($institute_noc_type === 'NOC from the Institute from where applicant want to do the internship')?'selected':''?>>NOC from the Institute from where applicant want to do the internship</option>
                                                </select>
                                                <?= form_error("institute_noc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="institute_noc" name="institute_noc" type="file" />
                                                </div>
                                                <?php if(strlen($institute_noc)){ ?>
                                                    <a href="<?=base_url($institute_noc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="institute_noc" type="hidden" name="institute_noc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('institute_noc'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>

                                        <?php if($dbrow->form_data->study_place == 3){ ?> 
                                        <tr>
                                            <td>Eligibility Certificate from National Medical Commission</td>
                                            <td>
                                                <select name="eligibility_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Eligibility Certificate from National Medical Commission" <?=($eligibility_certificate_type === 'Eligibility Certificate from National Medical Commission')?'selected':''?>>Eligibility Certificate from National Medical Commission</option>
                                                </select>
                                                <?= form_error("eligibility_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="eligibility_certificate" name="eligibility_certificate" type="file" />
                                                </div>
                                                <?php if(strlen($eligibility_certificate)){ ?>
                                                    <a href="<?=base_url($eligibility_certificate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="eligibility_certificate" type="hidden" name="eligibility_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('eligibility_certificate'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>FMGE Marksheet<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="screening_result_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="FMGE Marksheet" <?=($screening_result_type === 'FMGE Marksheet')?'selected':''?>>FMGE Marksheet</option>
                                                </select>
                                                <?= form_error("screening_result_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="screening_result" name="screening_result" type="file" />
                                                </div>
                                                <?php if(strlen($screening_result)){ ?>
                                                    <a href="<?=base_url($screening_result)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="screening_result" type="hidden" name="screening_result_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('screening_result'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Passport and Visa with travel details<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="passport_visa_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Passport and Visa with travel details" <?=($passport_visa_type === 'Passport and Visa with travel details')?'selected':''?>>Passport and Visa with travel details</option>
                                                </select>
                                                <?= form_error("passport_visa_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="passport_visa" name="passport_visa" type="file" />
                                                </div>
                                                <?php if(strlen($passport_visa)){ ?>
                                                    <a href="<?=base_url($passport_visa)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="passport_visa" type="hidden" name="passport_visa_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('passport_visa'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>All documents related to medical college details<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="all_docs_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="All documents related to medical college details" <?=($all_docs_type === 'All documents related to medical college details')?'selected':''?>>All documents related to medical college details</option>
                                                </select>
                                                <?= form_error("all_docs_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="all_docs" name="all_docs" type="file" />
                                                </div>
                                                <?php if(strlen($all_docs)){ ?>
                                                    <a href="<?=base_url($all_docs)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="all_docs" type="hidden" name="all_docs_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('all_docs'); ?>
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
                    <a href="<?=site_url('spservices/acmr_provisional_certificate/registration/index/'.$obj_id)?>" class="btn btn-primary">
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