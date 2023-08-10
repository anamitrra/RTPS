<?php
$photo_type_frm = set_value("photo_type");
$date_of_birth_type_frm = set_value("date_of_birth_type");
$proof_of_residence_type_frm = set_value("proof_of_residence_type");
$caste_certificate_of_father_type_frm = set_value("caste_certificate_of_father_type");
$recomendation_certificate_type_frm = set_value("recomendation_certificate_type");
$others_type_frm = set_value("others_type");
$soft_copy_type_frm = set_value("soft_copy_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');
$photo_frm = $uploadedFiles['photo_old']??null;
$date_of_birth_frm = $uploadedFiles['date_of_birth_old']??null;
$proof_of_residence_frm = $uploadedFiles['proof_of_residence_old']??null;
$caste_certificate_of_father_frm = $uploadedFiles['caste_certificate_of_father_old']??null;
$recomendation_certificate_frm = $uploadedFiles['recomendation_certificate_old']??null;
$others_frm = $uploadedFiles['others_old']??null;
$soft_copy_frm = $uploadedFiles['soft_copy_old']??null;

$photo_type_db = $dbrow->form_data->photo_type??null;
$date_of_birth_type_db = $dbrow->form_data->date_of_birth_type??null;
$proof_of_residence_type_db = $dbrow->form_data->proof_of_residence_type??null;
$caste_certificate_of_father_type_db = $dbrow->form_data->caste_certificate_of_father_type??null;
$recomendation_certificate_type_db = $dbrow->form_data->recomendation_certificate_type??null;
$others_type_db = $dbrow->form_data->others_type??null;
$soft_copy_type_db = $dbrow->form_data->soft_copy_type??null;
$photo_db = $dbrow->form_data->photo??null;
$date_of_birth_db = $dbrow->form_data->date_of_birth??null;
$proof_of_residence_db = $dbrow->form_data->proof_of_residence??null;
$caste_certificate_of_father_db = $dbrow->form_data->caste_certificate_of_father??null;
$recomendation_certificate_db = $dbrow->form_data->recomendation_certificate??null;
$others_db = $dbrow->form_data->others??null;
$soft_copy_db = $dbrow->form_data->soft_copy??null;

$photo_type = strlen($photo_type_frm)?$photo_type_frm:$photo_type_db;
$date_of_birth_type = strlen($date_of_birth_type_frm)?$date_of_birth_type_frm:$date_of_birth_type_db;
$proof_of_residence_type = strlen($proof_of_residence_type_frm)?$proof_of_residence_type_frm:$proof_of_residence_type_db;
$caste_certificate_of_father_type = strlen($caste_certificate_of_father_type_frm)?$caste_certificate_of_father_type_frm:$caste_certificate_of_father_type_db;
$recomendation_certificate_type = strlen($recomendation_certificate_type_frm)?$recomendation_certificate_type_frm:$recomendation_certificate_type_db;
$others_type = strlen($others_type_frm)?$others_type_frm:$others_type_db;
$soft_copy_type = strlen($soft_copy_type_frm)?$soft_copy_type_frm:$soft_copy_type_db;
$photo = strlen($photo_frm)?$photo_frm:$photo_db;
$date_of_birth = strlen($date_of_birth_frm)?$date_of_birth_frm:$date_of_birth_db;
$proof_of_residence = strlen($proof_of_residence_frm)?$proof_of_residence_frm:$proof_of_residence_db;
$caste_certificate_of_father = strlen($caste_certificate_of_father_frm)?$caste_certificate_of_father_frm:$caste_certificate_of_father_db;
$recomendation_certificate = strlen($recomendation_certificate_frm)?$recomendation_certificate_frm:$recomendation_certificate_db;
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
    $(document).ready(function() {
        var photo = parseInt(<?=strlen($photo)?1:0?>);
        $("#photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: photo?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png"]
        });

        var dateOfBirth = parseInt(<?=strlen($date_of_birth)?1:0?>);
        $("#date_of_birth").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: dateOfBirth?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var proofOfResidence = parseInt(<?=strlen($proof_of_residence)?1:0?>);
        $("#proof_of_residence").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: proofOfResidence?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        // var casteCertificateOfFather = parseInt(<?=strlen($caste_certificate_of_father)?1:0?>);
        // $("#caste_certificate_of_father").fileinput({
        //     dropZoneEnabled: false,
        //     showUpload: false,
        //     showRemove: false,
        //     required: casteCertificateOfFather?false:true,
        //     maxFileSize: 2000,
        //     allowedFileExtensions: ["pdf"]
        // });

        $("#caste_certificate_of_father").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        // var recomendationCertificate = parseInt(<?=strlen($recomendation_certificate)?1:0?>);
        // $("#recomendation_certificate").fileinput({
        //     dropZoneEnabled: false,
        //     showUpload: false,
        //     showRemove: false,
        //     required: recomendationCertificate?false:true,
        //     maxFileSize: 2000,
        //     allowedFileExtensions: ["pdf"]
        // });

        $("#recomendation_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
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
        <form method="POST" action="<?= base_url('spservices/castecertificate/registration/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?= $obj_id ?>" name="obj_id" type="hidden" />
            <input name="photo_old" value="<?=$photo?>" type="hidden" />
            <input name="date_of_birth_old" value="<?=$date_of_birth?>" type="hidden" />
            <input name="proof_of_residence_old" value="<?=$proof_of_residence?>" type="hidden" />
            <input name="caste_certificate_of_father_old" value="<?=$caste_certificate_of_father?>" type="hidden" />
            <input name="recomendation_certificate_old" value="<?=$recomendation_certificate?>" type="hidden" />
            <input name="others_old" value="<?=$others?>" type="hidden" />
            <input name="soft_copy_old" value="<?=$soft_copy?>" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Issuance Of Caste Certificate<br>
                    (জাতিৰ প্ৰমাণ পত্ৰৰ বাবে আবেদন )
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
                                            <td>Applicant's Photo <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="photo_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Applicant Photo" <?=($photo_type === 'Applicant Photo')?'selected':''?>>Applicant Photo</option>
                                                </select>
                                                <?= form_error("photo_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input name="photo" id="photo" type="file" />
                                                </div>
                                                <?php if(strlen($photo)){ ?>
                                                    <a href="<?=base_url($photo)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Proof of Date of Birth(One of Birth Certificate/Aadhar Card/PAN/Admit Card issued by any recognized Board of Applicant<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="date_of_birth_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Birth Certificate" <?=($date_of_birth_type === 'Birth Certificate')?'selected':''?>>Birth Certificate</option>
                                                    <option value="Aadhar Card" <?=($date_of_birth_type === 'Aadhar Card')?'selected':''?>>Aadhar Card</option>
                                                    <option value="PAN Card" <?=($date_of_birth_type === 'PAN Card')?'selected':''?>>PAN Card</option>
                                                    <option value="Admit Card issued by any recognized Board of Applicant" <?=($date_of_birth_type === 'Admit Card issued by any recognized Board of Applicant')?'selected':''?>>Admit Card issued by any recognized Board of Applicant</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="date_of_birth" name="date_of_birth" type="file" />
                                                </div>
                                                <?php if(strlen($date_of_birth)){ ?>
                                                    <a href="<?=base_url($date_of_birth)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="date_of_birth" type="hidden" name="date_of_birth_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('date_of_birth'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Proof of Residence(One of Permanent Resident Certificate/Aadhar Card/EPIC/Land Document/Electricity Bill,Ration Card of Applicant or Parent<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="proof_of_residence_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Permanent Resident Certificate" <?=($proof_of_residence_type === 'Permanent Resident Certificate')?'selected':''?>>Permanent Resident Certificate</option>
                                                    <option value="Aadhar Card" <?=($proof_of_residence_type === 'Aadhar Card')?'selected':''?>>Aadhar Card</option>
                                                    <option value="EPIC" <?=($proof_of_residence_type === 'EPIC')?'selected':''?>>EPIC</option>
                                                    <option value="Land Document" <?=($proof_of_residence_type === 'Land Document')?'selected':''?>>Land Document</option>
                                                    <option value="Electricity Bill,Ration Card of Applicant or Parent" <?=($proof_of_residence_type === 'Electricity Bill,Ration Card of Applicant or Parent')?'selected':''?>>Electricity Bill,Ration Card of Applicant or Parent</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="proof_of_residence" name="proof_of_residence" type="file" />
                                                </div>
                                                <?php if(strlen($proof_of_residence)){ ?>
                                                    <a href="<?=base_url($proof_of_residence)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="proof_of_residence" type="hidden" name="proof_of_residence_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('proof_of_residence'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Caste certificate of father
                                                <span class="text-danger">*</span>
                                            </td>
                                            <td>
                                                <select name="caste_certificate_of_father_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Caste Certificate of Father" <?=($caste_certificate_of_father_type === 'Caste Certificate of Father')?'selected':''?>>Caste Certificate of Father</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="caste_certificate_of_father" name="caste_certificate_of_father" type="file" />
                                                </div>
                                                <?php if(strlen($caste_certificate_of_father)){ ?>
                                                    <a href="<?=base_url($caste_certificate_of_father)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="caste_certificate_of_father" type="hidden" name="caste_certificate_of_father_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('caste_certificate_of_father'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Recommendation of authorized caste/tribe/community organization notified by State Government/ Existing caste certificate
                                            </td>
                                            <td>
                                                <select name="recomendation_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Recommendation of authorized caste/tribe/community organization notified by State Government" <?=($recomendation_certificate_type === 'Recommendation of authorized caste/tribe/community organization notified by State Government')?'selected':''?>>Recommendation of authorized caste/tribe/community organization notified by State Government</option>
                                                    <option value="Existing caste certificate" <?=($recomendation_certificate_type === 'Existing caste certificate')?'selected':''?>>Existing caste certificate</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="recomendation_certificate" name="recomendation_certificate" type="file" />
                                                </div>
                                                <?php if(strlen($recomendation_certificate)){ ?>
                                                    <a href="<?=base_url($recomendation_certificate)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="recomendation_certificate" type="hidden" name="recomendation_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('recomendation_certificate'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Any other document(Voter List,Affidavit,Existing Caste Certificate etc)</td>
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
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?= base_url('spservices/castecertificate/registration/index/' . $obj_id) ?>" class="btn btn-primary">
                        <i class="fa fa-angle-double-left"></i> Back
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <button type="reset" class="btn btn-danger">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div>
                <!--End of .card-footer-->
            </div>
            <!--End of .card-->
        </form>
    </div>
    <!--End of .container-->
</main>