<?php
$applicant_type = $this->session->userdata('applicant_type');
$deptt_name = $this->session->userdata('deptt_name');
$obj_id = null;
if ($dbrow) {
    $obj_id = $dbrow->_id->{'$id'};
    $uploadedFiles = $this->session->flashdata('uploaded_files');
    $photograph = isset($dbrow->form_data->enclosures->photograph) ? $dbrow->form_data->enclosures->photograph : ($uploadedFiles['photograph_old'] ?? null);
    $copy_pan_card = isset($dbrow->form_data->enclosures->copy_pan_card) ? $dbrow->form_data->enclosures->copy_pan_card : ($uploadedFiles['copy_pan_card_old'] ?? null);
    $caste_cert = isset($dbrow->form_data->enclosures->caste_cert) ? $dbrow->form_data->enclosures->caste_cert : ($uploadedFiles['caste_cert_old'] ?? null);
    $pvr_passport = isset($dbrow->form_data->enclosures->pvr_passport) ? $dbrow->form_data->enclosures->pvr_passport : ($uploadedFiles['pvr_passport_old'] ?? null);
    $gst_reg_cert = isset($dbrow->form_data->enclosures->gst_reg_cert) ? $dbrow->form_data->enclosures->gst_reg_cert : ($uploadedFiles['gst_reg_cert_old'] ?? null);
    $bank_solvency_cert = isset($dbrow->form_data->enclosures->bank_solvency_cert) ? $dbrow->form_data->enclosures->bank_solvency_cert : ($uploadedFiles['bank_solvency_cert_old'] ?? null);
    $labour_license = isset($dbrow->form_data->enclosures->labour_license) ? $dbrow->form_data->enclosures->labour_license : ($uploadedFiles['labour_license_old'] ?? null);
    $key_personnel = isset($dbrow->form_data->enclosures->key_personnel) ? $dbrow->form_data->enclosures->key_personnel : ($uploadedFiles['key_personnel_old'] ?? null);
    $turnover_cert = isset($dbrow->form_data->enclosures->turnover_cert) ? $dbrow->form_data->enclosures->turnover_cert : ($uploadedFiles['turnover_cert_old'] ?? null);
    $machinery_details = isset($dbrow->form_data->enclosures->machinery_details) ? $dbrow->form_data->enclosures->machinery_details : ($uploadedFiles['machinery_details_old'] ?? null);
    $tax_clearance_cert = isset($dbrow->form_data->enclosures->tax_clearance_cert) ? $dbrow->form_data->enclosures->tax_clearance_cert : ($uploadedFiles['tax_clearance_cert_old'] ?? null);

    $work_completion_cert = isset($dbrow->form_data->enclosures->work_completion_cert) ? $dbrow->form_data->enclosures->work_completion_cert : ($uploadedFiles['work_completion_cert_old'] ?? null);
    $quantities_work_cert = isset($dbrow->form_data->enclosures->quantities_work_cert) ? $dbrow->form_data->enclosures->quantities_work_cert : ($uploadedFiles['quantities_work_cert_old'] ?? null);

    $proof_of_address = isset($dbrow->form_data->enclosures->proof_of_address) ? $dbrow->form_data->enclosures->proof_of_address : ($uploadedFiles['proof_of_address_old'] ?? null);
    $power_attorney = isset($dbrow->form_data->enclosures->power_attorney) ? $dbrow->form_data->enclosures->power_attorney : ($uploadedFiles['power_attorney_old'] ?? null);
    $firm_reg_cert = isset($dbrow->form_data->enclosures->firm_reg_cert) ? $dbrow->form_data->enclosures->firm_reg_cert : ($uploadedFiles['firm_reg_cert_old'] ?? null);
    $deed_maaa_cert = isset($dbrow->form_data->enclosures->deed_maaa_cert) ? $dbrow->form_data->enclosures->deed_maaa_cert : ($uploadedFiles['deed_maaa_cert_old'] ?? null);

    $emp_provident_fund = isset($dbrow->form_data->enclosures->emp_provident_fund) ? $dbrow->form_data->enclosures->emp_provident_fund : ($uploadedFiles['emp_provident_fund'] ?? null);
    $affidavit_cert = isset($dbrow->form_data->enclosures->affidavit_cert) ? $dbrow->form_data->enclosures->affidavit_cert : ($uploadedFiles['affidavit_cert'] ?? null);
    $regs_other_deptt = isset($dbrow->form_data->enclosures->regs_other_deptt) ? $dbrow->form_data->enclosures->regs_other_deptt : ($uploadedFiles['regs_other_deptt'] ?? null);
    $affidavit_unemployment = isset($dbrow->form_data->enclosures->affidavit_unemployment) ? $dbrow->form_data->enclosures->affidavit_unemployment : ($uploadedFiles['affidavit_unemployment'] ?? null);
    $any_other_docs = isset($dbrow->form_data->enclosures->any_other_docs) ? $dbrow->form_data->enclosures->any_other_docs : ($uploadedFiles['any_other_docs'] ?? null);
    $caste = isset($dbrow->form_data->caste) ? $dbrow->form_data->caste : '';
    $category_of_regs = isset($dbrow->form_data->category_of_regs) ? $dbrow->form_data->category_of_regs : null;
    if($caste == 'General' || $caste == '')
    {
        $caste_flg = false;
    } else {
        $caste_flg = true;
    }
    $class_reg = isset($dbrow->form_data->category) ? $dbrow->form_data->category : null;
}
else {
    $uploadedFiles = $this->session->flashdata('uploaded_files');
}

$prime = 'a';
if($deptt_name == 'PWDB' || $deptt_name == 'PWDNH')
{
    $prime = 'prime';
}
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
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/webcamjs/webcam.min.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/iservices/js/custom/digilocker.js') ?>"></script>
<script type="text/javascript">
$(document).ready(function() {

var appl_type = parseInt(<?= ($applicant_type == "Individual") ? 1 : 0 ?>);
var casteFlg = parseInt(<?= ($caste_flg == true) ? 1 : 0 ?>);
var depttName = parseInt(<?= ($deptt_name != 'WRD') ? 1 : 0 ?>);
var applicantType = '<?= $applicant_type?>';
var category_of_regs = '<?= $category_of_regs?>';
var depttCode = '<?= $deptt_name?>';
var class_reg = '<?= $class_reg?>';

if(appl_type){
var photograph = parseInt(<?= strlen($photograph) ? 1 : 0 ?>);
$("#photograph").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: photograph ? false : true,
    maxFileSize: 200,
    allowedFileExtensions: ["jpg", "jpeg", "png"]
});
}

var copy_pan_card = parseInt(<?= strlen($copy_pan_card) ? 1 : 0 ?>);
$("#copy_pan_card").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: copy_pan_card ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"]
});

if(appl_type){
if(casteFlg) {
var casteCert = parseInt(<?= strlen($caste_cert) ? 1 : 0 ?>);
$("#caste_cert").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: casteCert ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"]
});
}
}

var proof_of_address = parseInt(<?= strlen($proof_of_address) ? 1 : 0 ?>);
$("#proof_of_address").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: proof_of_address ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});

if(category_of_regs == 'Unemployed Graduate Engineer' || category_of_regs == 'Unemployed Diploma Engineer') {
    var pvr_passport = parseInt(<?= strlen($pvr_passport) ? 1 : 0 ?>);
} else {
    var pvr_passport = 1;
}

$("#pvr_passport").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: pvr_passport ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});

var gst_reg_cert = parseInt(<?= strlen($gst_reg_cert) ? 1 : 0 ?>);
$("#gst_reg_cert").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: gst_reg_cert ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});

var bank_solvency_cert = parseInt(<?= strlen($bank_solvency_cert) ? 1 : 0 ?>);
$("#bank_solvency_cert").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: bank_solvency_cert ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});

var labour_license = parseInt(<?= strlen($labour_license) ? 1 : 0 ?>);
$("#labour_license").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: labour_license ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});

if(depttCode == 'GMC' && class_reg == 'Class-II') {
    var key_personnel = 1;
} else {
    var key_personnel = parseInt(<?= strlen($key_personnel) ? 1 : 0 ?>);
}
$("#key_personnel").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: key_personnel ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});

if(category_of_regs != 'Unemployed Graduate Engineer' && category_of_regs != 'Unemployed Diploma Engineer') {
if(depttCode == 'GMC' && class_reg == 'Class-II') {
    var turnover_cert = 1;
} else {
    var turnover_cert = parseInt(<?= strlen($turnover_cert) ? 1 : 0 ?>);
}
$("#turnover_cert").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: turnover_cert ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});
}

var machinery_details = parseInt(<?= strlen($machinery_details) ? 1 : 0 ?>);
$("#machinery_details").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: machinery_details ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});

if(!appl_type) {
var power_attorney = parseInt(<?= strlen($power_attorney) ? 1 : 0 ?>);
$("#power_attorney").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: power_attorney ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});
}

if(category_of_regs != 'Unemployed Graduate Engineer' && category_of_regs != 'Unemployed Diploma Engineer') {
if(depttCode == 'GMC' && (applicantType == 'Proprietorship' || applicantType == 'Individual')) {
    var tax_clearance_cert = 1;
} else {
    var tax_clearance_cert = parseInt(<?= strlen($tax_clearance_cert) ? 1 : 0 ?>);
}
$("#tax_clearance_cert").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: tax_clearance_cert ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});

var work_completion_cert = parseInt(<?= strlen($work_completion_cert) ? 1 : 0 ?>);
$("#work_completion_cert").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: work_completion_cert ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});
}

if(depttName) {
var quantities_work_cert = parseInt(<?= strlen($quantities_work_cert) ? 1 : 0 ?>);
$("#quantities_work_cert").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    //required: quantities_work_cert ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});
}

if(!appl_type){
var firm_reg_cert = parseInt(<?= strlen($firm_reg_cert) ? 1 : 0 ?>);
$("#firm_reg_cert").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: firm_reg_cert ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});

if(applicantType != 'Proprietorship') {
var deed_maaa_cert = parseInt(<?= strlen($deed_maaa_cert) ? 1 : 0 ?>);
$("#deed_maaa_cert").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: deed_maaa_cert ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});
}

if(applicantType == 'Proprietorship') {
var affidavit_cert = parseInt(<?= strlen($affidavit_cert) ? 1 : 0 ?>);
$("#affidavit_cert").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: affidavit_cert ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});
}

}

if(depttCode == 'PWDNH' || category_of_regs == 'Unemployed Graduate Engineer' || category_of_regs == 'Unemployed Diploma Engineer' || (depttCode == 'GMC' && (applicantType == 'Proprietorship' || applicantType == 'Individual'))) {
    var emp_provident_fund = 1;
} else {
    var emp_provident_fund = parseInt(<?= strlen($emp_provident_fund) ? 1 : 0 ?>);
}
$("#emp_provident_fund").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: emp_provident_fund ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});
if(category_of_regs == 'Contractor from other department') {
var regs_other_deptt = parseInt(<?= strlen($regs_other_deptt) ? 1 : 0 ?>);
$("#regs_other_deptt").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: regs_other_deptt ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});
}
if(category_of_regs == 'Unemployed Graduate Engineer' || category_of_regs == 'Unemployed Diploma Engineer') {
var affidavit_unemployment = parseInt(<?= strlen($affidavit_unemployment) ? 1 : 0 ?>);
$("#affidavit_unemployment").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    required: affidavit_unemployment ? false : true,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});
}

$("#any_other_docs").fileinput({
    dropZoneEnabled: false,
    showUpload: false,
    showRemove: false,
    maxFileSize: 1024,
    allowedFileExtensions: ["jpg", "jpeg", "pdf"]
});

$('input[type="checkbox"]').click(function() {
    if ($(this).prop("checked") == true) {
        console.log("Checkbox is checked.");
        $('.save_next').removeClass('d-none')

    } else if ($(this).prop("checked") == false) {
        console.log("Checkbox is unchecked.");
        $('.save_next').addClass('d-none')

    }
});

});
</script>
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/registration_of_contractors/registration/submit_enclosures') ?>" enctype="multipart/form-data">
        <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
        <input name="photograph_old" type="hidden" value="<?= $photograph ?>" />
        <input name="copy_pan_card_old" type="hidden" value="<?= $copy_pan_card ?>" />
        <input name="caste_cert_old" type="hidden" value="<?= $caste_cert ?>" />
        <input name="pvr_passport_old" type="hidden" value="<?= $pvr_passport ?>" />
        <input name="gst_reg_cert_old" type="hidden" value="<?= $gst_reg_cert ?>" />
        <input name="bank_solvency_cert_old" type="hidden" value="<?= $bank_solvency_cert ?>" />
        <input name="labour_license_old" type="hidden" value="<?= $labour_license ?>" />
        <input name="key_personnel_old" type="hidden" value="<?= $key_personnel ?>" />
        <input name="turnover_cert_old" type="hidden" value="<?= $turnover_cert ?>" />
        <input name="machinery_details_old" type="hidden" value="<?= $machinery_details ?>" />
        <input name="tax_clearance_cert_old" type="hidden" value="<?= $tax_clearance_cert ?>" />
        <input name="work_completion_cert_old" type="hidden" value="<?= $work_completion_cert ?>" />
        <input name="quantities_work_cert_old" type="hidden" value="<?= $quantities_work_cert ?>" />
        <input name="emp_provident_fund_old" type="hidden" value="<?= $emp_provident_fund ?>" />
        <input name="proof_of_address_old" type="hidden" value="<?= $proof_of_address ?>" />
        <input name="affidavit_cert_old" type="hidden" value="<?= $affidavit_cert ?>" />
        <input name="regs_other_deptt_old" type="hidden" value="<?= $regs_other_deptt ?>" />
        <input name="affidavit_unemployment_old" type="hidden" value="<?= $affidavit_unemployment ?>" />
        <input name="any_other_docs_old" type="hidden" value="<?= $any_other_docs ?>" />

        


            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                Application for Registration of Contractors
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
                   
                    <h5 class="text-center mt-3 text-success"><u><strong>ENCLOSURES</strong></u></h5><br>
                    <fieldset class="border border-success" style="margin-top:0px">
                        <p>If you wish to upload your enclosures/documents from digilocker, please link your digilocker account. Click on the below button to link your account.</p>
                        <p><?php $this->digilocker_api->login_btn();  ?></p>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top: 0px">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td colspan="3" style="color:#fd7e14; text-align: center; font-size: 14px">
                                        Note : Only jpg, jpeg, png and pdf of maximum 1MB is allowed;
                                        For Passport size photo only jpg, jpeg, and png of maximum 200KB is allowed
                                    </td>
                                </tr>
                                <tr>
                                    <th>Enclosure Document</th>
                                    <th>File/Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if($applicant_type == 'Individual') { ?>
                                <tr>
                                    <td>Photograph <span class="text-danger">*</span></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="photograph" name="photograph" type="file" />
                                        </div>
                                        <?php if (strlen($photograph)) { ?>
                                            <a href="<?= base_url($photograph) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } 
                                        ?>
                                        <input class="photograph" type="hidden" name="photograph_temp">
                                        <?= form_error("photograph") ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td>PAN Card <span class="text-danger">*</span></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="copy_pan_card" name="copy_pan_card" type="file" />
                                        </div>
                                        <?php if (strlen($copy_pan_card)) { ?>
                                            <a href="<?= base_url($copy_pan_card) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } 
                                        ?>
                                        <input class="copy_pan_card" type="hidden" name="copy_pan_card_temp">
                                        <?= $this->digilocker_api->digilocker_fetch_btn('copy_pan_card'); ?>
                                        <?= form_error("copy_pan_card") ?>
                                    </td>
                                </tr>
                                <?php if($applicant_type == 'Individual' && $caste != 'General') { ?>
                                <tr>
                                    <td>Caste Certificate <span class="text-danger">*</span></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="caste_cert" name="caste_cert" type="file" />
                                        </div>
                                        <?php if (strlen($caste_cert)) { ?>
                                            <a href="<?= base_url($caste_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } 
                                        ?>
                                        <input class="caste_cert" type="hidden" name="caste_cert_temp">
                                        <?= $this->digilocker_api->digilocker_fetch_btn('caste_cert'); ?>
                                        <?= form_error("caste_cert") ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                
                                <tr>
                                    <td>Proof of Address <span class="text-danger">*</span></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="proof_of_address" name="proof_of_address" type="file" />
                                        </div>
                                        <?php if (strlen($proof_of_address)) { ?>
                                            <a href="<?= base_url($proof_of_address) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="proof_of_address" type="hidden" name="proof_of_address_temp">
                                        <?= $this->digilocker_api->digilocker_fetch_btn('proof_of_address'); ?>
                                        <?= form_error("proof_of_address") ?>
                                    </td>
                                </tr>
                               
                                <tr>
                                    <td>Scanned copy of Police Verification Report/Passport <?php if($category_of_regs == 'Unemployed Graduate Engineer' || $category_of_regs == 'Unemployed Diploma Engineer') { ?><span class="text-danger">*</span><?php } ?></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="pvr_passport" name="pvr_passport" type="file" />
                                        </div>
                                        <?php if (strlen($pvr_passport)) { ?>
                                            <a href="<?= base_url($copy_pan_card) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } 
                                        ?>
                                        <input class="pvr_passport" type="hidden" name="pvr_passport_temp">
                                        <?= $this->digilocker_api->digilocker_fetch_btn('pvr_passport'); ?>
                                        <?= form_error("pvr_passport") ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Scanned copy of GST Registration Certificate <span class="text-danger">*</span></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="gst_reg_cert" name="gst_reg_cert" type="file" />
                                        </div>
                                        <?php if (strlen($gst_reg_cert)) { ?>
                                            <a href="<?= base_url($gst_reg_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="gst_reg_cert" type="hidden" name="gst_reg_cert_temp">
                                        <?= form_error("gst_reg_cert") ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Scanned copy of Bank Solvency Certificate <span class="text-danger">*</span></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="bank_solvency_cert" name="bank_solvency_cert" type="file" />
                                        </div>
                                        <?php if (strlen($bank_solvency_cert)) { ?>
                                            <a href="<?= base_url($bank_solvency_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="bank_solvency_cert" type="hidden" name="bank_solvency_cert_temp">
                                        <?= form_error("bank_solvency_cert") ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Scanned copy of upto date <?php if($deptt_name == 'GMC') {echo "Labour License & Trade License";} else {echo "Labour License/ Trade License";}?> <span class="text-danger">*</span></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="labour_license" name="labour_license" type="file" />
                                        </div>
                                        <?php if (strlen($labour_license)) { ?>
                                            <a href="<?= base_url($labour_license) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="labour_license" type="hidden" name="labour_license_temp">
                                        <?= form_error("labour_license") ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Scanned copy of Declaration with certificate of key personnel <?php if(!($deptt_name == 'GMC' && $class_reg == 'Class-II')) {?><span class="text-danger">*</span><?php } ?></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="key_personnel" name="key_personnel" type="file" />
                                        </div>
                                        <?php if (strlen($key_personnel)) { ?>
                                            <a href="<?= base_url($key_personnel) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="key_personnel" type="hidden" name="key_personnel_temp">
                                        <?= form_error("key_personnel") ?>
                                    </td>
                                </tr>
                                
                                <tr>
                                <?php if($deptt_name == 'PWDNH' || $category_of_regs == 'Unemployed Graduate Engineer' || $category_of_regs == 'Unemployed Diploma Engineer' || ($deptt_name == 'GMC' && ($applicant_type == 'Proprietorship' || $applicant_type == 'Individual'))) {
                                    echo '<td>Employment Provident Fund(E.P.F) with latest challan</td>';
                                } else {
                                    echo '<td>Employment Provident Fund(E.P.F) with latest challan <span class="text-danger">*</span></td>';
                                } ?>
                                    <td>
                                        <div class="file-loading">
                                            <input id="emp_provident_fund" name="emp_provident_fund" type="file" />
                                        </div>
                                        <?php if (strlen($emp_provident_fund)) { ?>
                                            <a href="<?= base_url($emp_provident_fund) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="emp_provident_fund" type="hidden" name="emp_provident_fund_temp">
                                        <?= form_error("emp_provident_fund") ?>
                                    </td>
                                </tr>
                                <?php if($category_of_regs != 'Unemployed Graduate Engineer' && $category_of_regs != 'Unemployed Diploma Engineer') { ?>
                                <tr>
                                    <td>Scanned copy of Turnover Certificate from Chartered Accountant <?php if(!($deptt_name == 'GMC' && $class_reg == 'Class-II')) {?><span class="text-danger">*</span><?php } ?></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="turnover_cert" name="turnover_cert" type="file" />
                                        </div>
                                        <?php if (strlen($turnover_cert)) { ?>
                                            <a href="<?= base_url($turnover_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="turnover_cert" type="hidden" name="turnover_cert_temp">
                                        <?= form_error("turnover_cert") ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td>Machinery Details <span class="text-danger">*</span></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="machinery_details" name="machinery_details" type="file" />
                                        </div>
                                        <?php if (strlen($machinery_details)) { ?>
                                            <a href="<?= base_url($machinery_details) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="machinery_details" type="hidden" name="machinery_details_temp">
                                        <?= form_error("machinery_details") ?>
                                    </td>
                                </tr>
                                <?php if($applicant_type != 'Individual') { ?>
                                <tr>
                                    <td>Power of Attorney Certificate(for Partnership Firm or Company) <span class="text-danger">*</span></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="power_attorney" name="power_attorney" type="file" />
                                        </div>
                                        <?php if (strlen($power_attorney)) { ?>
                                            <a href="<?= base_url($power_attorney) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="power_attorney" type="hidden" name="power_attorney_temp">
                                        <?= form_error("power_attorney") ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if($category_of_regs != 'Unemployed Graduate Engineer' && $category_of_regs != 'Unemployed Diploma Engineer') { ?>
                                <tr>
                                    <td>Up to date Tax clearance certificates of all Nature(like Income tax, GSTIN, MV tax etc.) <?php if(!($deptt_name == 'GMC' && ($applicant_type == 'Proprietorship' || $applicant_type == 'Individual'))) {?><span class="text-danger">*</span><?php } ?></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="tax_clearance_cert" name="tax_clearance_cert" type="file" />
                                        </div>
                                        <?php if (strlen($tax_clearance_cert)) { ?>
                                            <a href="<?= base_url($tax_clearance_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="tax_clearance_cert" type="hidden" name="tax_clearance_cert_temp">
                                        <?= form_error("tax_clearance_cert") ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Work orders and Work done/ Completion Certificate <span class="text-danger">*</span></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="work_completion_cert" name="work_completion_cert" type="file" />
                                        </div>
                                        <?php if (strlen($work_completion_cert)) { ?>
                                            <a href="<?= base_url($work_completion_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="work_completion_cert" type="hidden" name="work_completion_cert_temp">
                                        <?= form_error("work_completion_cert") ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if($deptt_name != 'WRD') { ?>
                                <tr>
                                    <td>Quantities of works executed as <?= $prime?> contractor</td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="quantities_work_cert" name="quantities_work_cert" type="file" />
                                        </div>
                                        <?php if (strlen($quantities_work_cert)) { ?>
                                            <a href="<?= base_url($quantities_work_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="quantities_work_cert" type="hidden" name="quantities_work_cert_temp">
                                        <?= form_error("quantities_work_cert") ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if($applicant_type != 'Individual') { ?>
                                <tr>
                                    <td>Firm Registration Certificate(for Partnership Firm)/ Certificate of Incorporation(for Company) <span class="text-danger">*</span></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="firm_reg_cert" name="firm_reg_cert" type="file" />
                                        </div>
                                        <?php if (strlen($firm_reg_cert)) { ?>
                                            <a href="<?= base_url($firm_reg_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="firm_reg_cert" type="hidden" name="firm_reg_cert_temp">
                                        <?= form_error("firm_reg_cert") ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if($applicant_type != 'Individual' && $applicant_type != 'Proprietorship') { ?>
                                <tr>
                                    <td>Partnership Deed(for Partnership Firm)/Memorandum of Association and Articles of Association(for Company) <span class="text-danger">*</span></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="deed_maaa_cert" name="deed_maaa_cert" type="file" />
                                        </div>
                                        <?php if (strlen($deed_maaa_cert)) { ?>
                                            <a href="<?= base_url($deed_maaa_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="deed_maaa_cert" type="hidden" name="deed_maaa_cert_temp">
                                        <?= form_error("deed_maaa_cert") ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if($applicant_type == 'Proprietorship') { ?>
                                <tr>
                                    <td>Affidavit(for Proprietorship Firm) <span class="text-danger">*</span></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="affidavit_cert" name="affidavit_cert" type="file" />
                                        </div>
                                        <?php if (strlen($affidavit_cert)) { ?>
                                            <a href="<?= base_url($affidavit_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="affidavit_cert" type="hidden" name="affidavit_cert_temp">
                                        <?= form_error("affidavit_cert") ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if($category_of_regs == 'Contractor from other department') { ?>
                                <tr>
                                    <td>Registration copy of Other Department <span class="text-danger">*</span></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="regs_other_deptt" name="regs_other_deptt" type="file" />
                                        </div>
                                        <?php if (strlen($regs_other_deptt)) { ?>
                                            <a href="<?= base_url($regs_other_deptt) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="regs_other_deptt" type="hidden" name="regs_other_deptt_temp">
                                        <?= form_error("regs_other_deptt") ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if($category_of_regs == 'Unemployed Graduate Engineer' || $category_of_regs == 'Unemployed Diploma Engineer') { ?>
                                <tr>
                                    <td>Affidavit of Unemployment <span class="text-danger">*</span></td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="affidavit_unemployment" name="affidavit_unemployment" type="file" />
                                        </div>
                                        <?php if (strlen($affidavit_unemployment)) { ?>
                                            <a href="<?= base_url($affidavit_unemployment) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="affidavit_unemployment" type="hidden" name="affidavit_unemployment_temp">
                                        <?= form_error("affidavit_unemployment") ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td>Any other supporting documents</td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="any_other_docs" name="any_other_docs" type="file" />
                                        </div>
                                        <?php if (strlen($any_other_docs)) { ?>
                                            <a href="<?= base_url($any_other_docs) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } ?>
                                        <input class="any_other_docs" type="hidden" name="any_other_docs_temp">
                                        <?= form_error("any_other_docs") ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:0px">
                        <legend class="h5">Declaration</legend>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="declaration" value="1" id="declaration">
                                    <label class="form-check-label" for="declaration">
                                     <p style="text-align: justify;">I do hereby solemnly declare that the information furnished above are true to the best of my knowledge and belief, no any false/forged information/ documents are furnished. Subsequent upon, if found to be forged, I shall be liable to the Department and will accept any action, what so ever, to be imposed by the Department.</p>
                                    </label>
                                </div>
                                <?= form_error("declaration") ?>

                            </div>
                        </div>
                    </fieldset>
                    <!-- Modal -->
                    <div class="modal fade" id="declaration_modal" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header text-center bg-info text-white">
                                    <h5 class="modal-title" id="staticBackdropLabel">Declaration</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p style="text-align: justify;">I do hereby solemnly declare that the information furnished above are true to the best of my knowledge and belief, no any false/forged information/ documents are furnished. Subsequent upon, if found to be forged, I shall be liable to the Department and will accept any action, what so ever, to be imposed by the Department.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/registration_of_contractors/history-section/'. $obj_id) ?>">
                        <i class="fa fa-angle-double-left"></i> Previous
                    </a>
                    <button class="btn btn-success frmbtn save_next d-none" id="SAVE" type="submit">
                        <i class="fa fa-angle-double-right"></i> Save &amp; Next
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>