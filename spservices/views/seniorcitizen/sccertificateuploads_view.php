<?php
$passport_photo_type_frm = set_value("passport_photo_type");
$proof_of_retirement_type_frm = set_value("proof_of_retirement_type");
$age_proof_type_frm = set_value("age_proof_type");
$address_proof_type_frm = set_value("address_proof_type");
$other_doc_type_frm = set_value("other_doc_type");
$soft_copy_type_frm = set_value("soft_copy_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');
$passport_photo_frm = $uploadedFiles['passport_photo_old']??null;
$proof_of_retirement_frm = $uploadedFiles['proof_of_retirement_old']??null;
$age_proof_frm = $uploadedFiles['age_proof_old']??null;
$address_proof_frm = $uploadedFiles['address_proof_old']??null;
$other_doc_frm = $uploadedFiles['other_doc_old']??null;
$soft_copy_frm = $uploadedFiles['soft_copy_old']??null;

$passport_photo_type_db = $dbrow->form_data->passport_photo_type??null;
$proof_of_retirement_type_db = $dbrow->form_data->proof_of_retirement_type??null;
$age_proof_type_db = $dbrow->form_data->age_proof_type??null;
$address_proof_type_db = $dbrow->form_data->address_proof_type??null;
$other_doc_type_db = $dbrow->form_data->other_doc_type??null;
$soft_copy_type_db = $dbrow->form_data->soft_copy_type??null;

$passport_photo_db = $dbrow->form_data->passport_photo??null;
$proof_of_retirement_db = $dbrow->form_data->proof_of_retirement??null;
$age_proof_db = $dbrow->form_data->age_proof??null;
$address_proof_db = $dbrow->form_data->address_proof??null;
$other_doc_db = $dbrow->form_data->other_doc??null;
$soft_copy_db = $dbrow->form_data->soft_copy??null;

$passport_photo_type = strlen($passport_photo_type_frm)?$passport_photo_type_frm:$passport_photo_type_db;
$proof_of_retirement_type = strlen($proof_of_retirement_type_frm)?$proof_of_retirement_type_frm:$proof_of_retirement_type_db;
$age_proof_type = strlen($age_proof_type_frm)?$age_proof_type_frm:$age_proof_type_db;
$address_proof_type = strlen($address_proof_type_frm)?$address_proof_type_frm:$address_proof_type_db;
$other_doc_type = strlen($other_doc_type_frm)?$other_doc_type_frm:$other_doc_type_db;
$soft_copy_type = strlen($soft_copy_type_frm)?$soft_copy_type_frm:$soft_copy_type_db;

$passport_photo = strlen($passport_photo_frm)?$passport_photo_frm:$passport_photo_db;
$proof_of_retirement = strlen($proof_of_retirement_frm)?$proof_of_retirement_frm:$proof_of_retirement_db;
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
        var passportPhoto = parseInt(<?=strlen($passport_photo)?1:0?>);
        $("#passport_photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: passportPhoto?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg"]
        }); 
        
        var proofofRetirement = parseInt(<?=strlen($proof_of_retirement)?1:0?>);
        $("#proof_of_retirement").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            //required: proofofRetirement?false:true,
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
        <form method="POST" action="<?= base_url('spservices/seniorcitizen/scc/submitfiles') ?>" enctype="multipart/form-data">
            <input value="<?=$obj_id?>" name="obj_id" type="hidden" />
            <input name="passport_photo_old" value="<?=$passport_photo?>" type="hidden" />
            <input name="proof_of_retirement_old" value="<?=$proof_of_retirement?>" type="hidden" />
            <input name="age_proof_old" value="<?=$age_proof?>" type="hidden" />
            <input name="address_proof_old" value="<?=$address_proof?>" type="hidden" />
            <input name="other_doc_old" value="<?=$other_doc?>" type="hidden" />
            <input name="soft_copy_old" value="<?=$soft_copy?>" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                <h4><b>Application for Senior Citizen Certificate<br>
                            ( জ্যেষ্ঠ নাগৰিকৰ প্ৰমানপত্ৰৰ বাবে আবেদন )<b></h4>
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
                                            <th width="30%">Type of Enclosure</th>
                                            <th width="30%">Enclosure Document</th>
                                            <th width="40%">File/Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Passport size photograph<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="passport_photo_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Photograph" <?=($passport_photo_type === 'Photograph')?'selected':''?>>Passport size photograph</option>
                                                </select>
                                                <?= form_error("passport_photo_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="passport_photo" name="passport_photo" type="file" />
                                                </div>
                                                <?php if(strlen($passport_photo)){ ?>
                                                    <a href="<?=base_url($passport_photo)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td>Proof Of Retirement(for Govt. Servants) or Copy of 1966 Voter list (for other than - Govt. servant)<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="proof_of_retirement_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Proof Of Retirement(for Govt. Servants)" <?=($proof_of_retirement_type === 'Proof Of Retirement(for Govt. Servants)')?'selected':''?>>Proof Of Retirement(for Govt. Servants)</option>
                                                    <option value="Copy of 1966 Voter list (for other than - Govt. servant)" <?=($proof_of_retirement_type === 'Copy of 1966 Voter list (for other than - Govt. servant)')?'selected':''?>>Copy of 1966 Voter list (for other than - Govt. servant)</option>
                                                </select>
                                                <?= form_error("proof_of_retirement_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="proof_of_retirement" name="proof_of_retirement" type="file" />
                                                </div>
                                                <?php if(strlen($proof_of_retirement)){ ?>
                                                    <a href="<?=base_url($proof_of_retirement)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                <input class="proof_of_retirement" type="hidden" name="proof_of_retirement_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('proof_of_retirement'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Age proof<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="age_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Pension Payment Order from the competent authority" <?=($age_proof_type === 'Pension Payment Order from the competent authority')?'selected':''?>>Pension Payment Order from the competent authority</option>
                                                    <option value="School Certificate" <?=($age_proof_type === 'School Certificate')?'selected':''?>>School Certificate</option>
                                                    <option value="Certificate issued by Gaon Burha or Local Mauzadar (In absence of other documents)" <?=($age_proof_type === 'Certificate issued by Gaon Burha or Local Mauzadar (In absence of other documents)')?'selected':''?>>Certificate issued by Gaon Burha or Local Mauzadar (In absence of other documents)</option>
                                                    <option value="Bank Pass Book with photograph" <?=($age_proof_type === 'Bank Pass Book with photograph')?'selected':''?>>Bank Pass Book with photograph</option>
                                                    <option value="Copy of Passport" <?=($age_proof_type === 'Copy of Passport')?'selected':''?>>Copy of Passport</option>
                                                    <option value="Copy of PAN Card" <?=($age_proof_type === 'Copy of PAN Card')?'selected':''?>>Copy of PAN Card</option>
                                                    <option value="Marriage Certificate (Incase of change of name of women)" <?=($age_proof_type === 'Marriage Certificate (Incase of change of name of women)')?'selected':''?>>Marriage Certificate (Incase of change of name of women)</option>
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
                                            <td>Address proof.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="address_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Voter ID Card" <?=($address_proof_type === 'Voter ID Card')?'selected':''?>>Voter ID Card</option>
                                                    <option value="Copy of Passport" <?=($address_proof_type === 'Copy of Passport')?'selected':''?>>Copy of Passport</option>
                                                    <option value="Ration Card" <?=($address_proof_type === 'Ration Card')?'selected':''?>>Ration Card</option>
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
                                            <td>Other documents</td>
                                            <td>
                                                <select name="other_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Electricity Bill" <?=($other_doc_type === 'Electricity Bill')?'selected':''?>>Electricity Bill</option>
                                                    <option value="Voter List" <?=($other_doc_type === 'Voter List')?'selected':''?>>Voter List</option>
                                                    <option value="A copy of affidavit" <?=($other_doc_type === 'A copy of affidavit')?'selected':''?>>A copy of affidavit</option>
                                                    <option value="Telephone Bill" <?=($other_doc_type === 'Telephone Bill')?'selected':''?>>Telephone Bill</option>
                                                    <option value="Other supporting document" <?=($other_doc_type === 'Other supporting document')?'selected':''?>>Other supporting document</option>                                               
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
                                        <?php if($this->slug == 'userr') { ?>
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
                    <a href="<?=site_url('spservices/seniorcitizen/scc/index/'.$obj_id)?>" class="btn btn-primary">
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