<?php
$residential_proof_type_frm = set_value("residential_proof_type");
$obc_type_frm = set_value("obc_type");
$income_certificate_type_frm = set_value("income_certificate_type");
$other_doc_type_frm = set_value("other_doc_type");
$soft_copy_type_frm = set_value("soft_copy_type");

$uploadedFiles = $this->session->flashdata('uploaded_files');
$residential_proof_frm = $uploadedFiles['residential_proof_old'] ?? null;
$obc_frm = $uploadedFiles['obc_old'] ?? null;
$income_certificate_frm = $uploadedFiles['income_certificate_old'] ?? null;
$other_doc_frm = $uploadedFiles['other_doc_old'] ?? null;
$soft_copy_frm = $uploadedFiles['soft_copy_old'] ?? null;


$residential_proof_type_db = $dbrow->form_data->residential_proof_type ?? null;
$obc_type_db = $dbrow->form_data->obc_type ?? null;
$income_certificate_type_db = $dbrow->form_data->income_certificate_type ?? null;
$other_doc_type_db = $dbrow->form_data->other_doc_type ?? null;
$soft_copy_type_db = $dbrow->form_data->soft_copy_type ?? null;
$residential_proof_db = $dbrow->form_data->residential_proof ?? null;
$obc_db = $dbrow->form_data->obc ?? null;
$income_certificate_db = $dbrow->form_data->income_certificate ?? null;
$other_doc_db = $dbrow->form_data->other_doc ?? null;
$soft_copy_db = $dbrow->form_data->soft_copy ?? null;

$residential_proof_type = strlen($residential_proof_type_frm) ? $residential_proof_type_frm : $residential_proof_type_db;
$obc_type = strlen($obc_type_frm) ? $obc_type_frm : $obc_type_db;
$income_certificate_type = strlen($income_certificate_type_frm) ? $income_certificate_type_frm : $income_certificate_type_db;
$other_doc_type = strlen($other_doc_type_frm) ? $other_doc_type_frm : $other_doc_type_db;
$soft_copy_type = strlen($soft_copy_type_frm) ? $soft_copy_type_frm : $soft_copy_type_db;
$residential_proof = strlen($residential_proof_frm) ? $residential_proof_frm : $residential_proof_db;
$obc = strlen($obc_frm) ? $obc_frm : $obc_db;
$income_certificate = strlen($income_certificate_frm) ? $income_certificate_frm : $income_certificate_db;
$other_doc = strlen($other_doc_frm) ? $other_doc_frm : $other_doc_db;
$soft_copy = strlen($soft_copy_frm) ? $soft_copy_frm : $soft_copy_db;
$obj_id = $dbrow->_id->{'$id'};
//pre($obj_id);
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
    var residentialProof = parseInt(<?= strlen($residential_proof) ? 1 : 0 ?>);
    $("#residential_proof").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: residentialProof ? false : true,
        maxFileSize: 1024,
        // allowedFileExtensions: ["jpg", "png", "gif", "pdf"]
        allowedFileExtensions: ["pdf"]
    });

    var obc = parseInt(<?= strlen($obc) ? 1 : 0 ?>);
    $("#obc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: obc ? false : true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    var incomeCertificate = parseInt(<?= strlen($income_certificate) ? 1 : 0 ?>);
    $("#income_certificate").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: incomeCertificate ? false : true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    $("#other_doc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });

    $("#soft_copy").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        //required: true,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
});
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form method="POST" action="<?= base_url('spservices/noncreamylayercertificate/registration/submitfiles') ?>"
            enctype="multipart/form-data">
            <input value="<?= $obj_id ?>" name="obj_id" type="hidden" />
            <input name="residential_proof_old" value="<?= $residential_proof ?>" type="hidden" />
            <input name="obc_old" value="<?= $obc ?>" type="hidden" />
            <input name="income_certificate_old" value="<?= $income_certificate ?>" type="hidden" />
            <input name="other_doc_old" value="<?= $other_doc ?>" type="hidden" />
            <input name="soft_copy_old" value="<?= $soft_copy ?>" type="hidden" />

            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Issuance Of Non Creamy Layer Certificate<br>
                    ( ননক্ৰিমি প্ৰমাণ পত্ৰৰ বাবে আবেদন )
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
                                            <td colspan="3" style="color:#fd7e14; text-align: center; font-size: 12px">
                                                List of Mandatory documents, Document type allowed is pdf of maximum size 2MB
                                            </td>
                                        </tr>
                                        <tr>
                                            <th style="width:50%">Type of Enclosure</th>
                                            <th style="width:25%">Enclosure Document</th>
                                            <th style="width:35%">File/Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Permanent resident certificate or any other proof of residency.<span class="text-danger">*</span><br><font>(স্থায়ী বাসিন্দাৰ প্ৰমাণ পত্ৰ বা বাসিন্দাৰ আন যিকোনো প্ৰমাণ)</font></br></td>
                                            <td>
                                                <select name="residential_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Electricity Bill"
                                                        <?= ($residential_proof_type === 'Electricity Bill') ? 'selected' : '' ?>>
                                                        Electricity Bill/বিদ্যুৎ বিল</option>
                                                    <option value="Registered Land Document"
                                                        <?= ($residential_proof_type === 'Registered Land Document') ? 'selected' : '' ?>>
                                                        Registered Land Document/পঞ্জীয়নভুক্ত ভূমিৰ নথি
                                                    </option>
                                                    <option value="Voter ID Card"
                                                        <?= ($residential_proof_type === 'Voter ID Card') ? 'selected' : '' ?>>
                                                        EPIC Card/ভোটাৰ আইডি কাৰ্ড</option>
                                                    <!--<option value="Jamabandi Copy"
                                                        <?= ($residential_proof_type === 'Jamabandi Copy') ? 'selected' : '' ?>>
                                                        Jamabandi Copy/জামাবন্দী কপি</option>-->
                                                    <option value="Permanent Resident Certificate (PRC)"
                                                        <?= ($residential_proof_type === 'Permanent Resident Certificate (PRC)') ? 'selected' : '' ?>>
                                                        Permanent
                                                        Resident Certificate (PRC)/স্থায়ী বাসিন্দাৰ প্ৰমাণ পত্ৰ</option>
                                                    <option value="Bank Passbook first page with photo"
                                                        <?= ($residential_proof_type === 'Bank Passbook first page with photo') ? 'selected' : '' ?>>
                                                        Bank Passbook
                                                        first page with photo/বেংকৰ পাছবুকৰ প্ৰথম পৃষ্ঠা ফটোৰ সৈতে</option>
                                                    <option value="Aadhaar Card"
                                                        <?= ($residential_proof_type === 'Aadhaar Card') ? 'selected' : '' ?>>
                                                        Aadhaar Card/আধাৰ কাৰ্ড</option>
                                                    <option value="Ration Card of Applicant or Parent"
                                                        <?= ($residential_proof_type === 'Ration Card of Applicant or Parent') ? 'selected' : '' ?>>
                                                        Ration Card of Applicant or Parent/আবেদনকাৰী বা অভিভাৱকৰ ৰেচন কাৰ্ড</option>  
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="residential_proof" name="residential_proof"
                                                        type="file" />
                                                </div>
                                                <?php if (strlen($residential_proof)) { ?>
                                                <a href="<?= base_url($residential_proof) ?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="residential_proof" type="hidden" name="residential_proof_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('residential_proof'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Digital OBC/MOBC certificate issued by competent authority. <span
                                                    class="text-danger">*</span><br><font>(যোগ্য কৰ্তৃপক্ষৰ দ্বাৰা প্ৰদান কৰা ডিজিটেল অ’বিচি/এমঅ’বিচি প্ৰমাণপত্ৰ।)</font></br></td>
                                            <td>
                                                <select name="obc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="OBC/MOBC certificate issued by competent authority"
                                                        <?= ($obc_type === 'OBC/MOBC certificate issued by competent authority') ? 'selected' : '' ?>>
                                                        Digital OBC/MOBC certificate issued by competent authority/যোগ্য কৰ্তৃপক্ষৰ দ্বাৰা প্ৰদান কৰা ডিজিটেল অ’বিচি/এমঅ’বিচি প্ৰমাণপত্ৰ।</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="obc" name="obc" type="file" />
                                                </div>
                                                <?php if (strlen($obc)) { ?>
                                                <a href="<?= base_url($obc) ?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="obc" type="hidden" name="obc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('obc'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Income certificate of the parents/husband/wife of the applicant from the Circle
                                                Officer if they are Agriculturists / Income certificate from controlling
                                                authority / Treasury Officer if retired salaried parents.<span
                                                    class="text-danger">*</span>
                                                <br><font>আবেদনকাৰীৰ পিতৃ-মাতৃ/স্বামী/পত্নীৰ আয়ৰ প্ৰমাণ পত্ৰ যদি তেওঁলোক কৃষক হয় তেন্তে চক্ৰ বিষয়াৰ পৰা / নিয়ন্ত্ৰণ কৰ্তৃপক্ষৰ পৰা আয়ৰ প্ৰমাণপত্ৰ / কোষাগাৰ বিষয়া যদি অৱসৰপ্ৰাপ্ত দৰমহা পোৱা পিতৃ-মাতৃ।</font></br>
                                            <p style="color:Tomato;"><br>(If more than one certificate, then combine all certificates in one pdf file)</br></p>
                                            </td>
                                            <td>
                                                <select name="income_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Income certificate of parents"
                                                        <?= ($income_certificate_type === 'Income certificate of parents') ? 'selected' : '' ?>>
                                                        Income certificate/আয়ৰ প্ৰমাণ পত্ৰ</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="income_certificate" name="income_certificate"
                                                        type="file" />
                                                </div>
                                                <?php if (strlen($income_certificate)) { ?>
                                                <a href="<?= base_url($income_certificate) ?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="income_certificate" type="hidden" name="income_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('income_certificate'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Upload the
                                                Scanned Copy of Application Form <br><font>আবেদন প্ৰপত্ৰৰ স্কেন কৰা কপি আপলোড কৰক</font></br></td>
                                            <td>
                                                <select name="soft_copy_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Scanned Copy of Application Form"
                                                        <?= ($soft_copy_type === 'Scanned Copy of Application Form') ? 'selected' : '' ?>>
                                                        Scanned Copy of Application Form/আবেদন প্ৰপত্ৰৰ স্কেন কৰা কপি</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="soft_copy" name="soft_copy" type="file" />
                                                </div>
                                                <?php if (strlen($soft_copy)) { ?>
                                                <a href="<?= base_url($soft_copy) ?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Others/<font>অন্যান্য</font></td>
                                            <td>
                                                <select name="other_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Voter List"
                                                        <?= ($other_doc_type === 'Voter List') ? 'selected' : '' ?>>Voter List
                                                    </option>
                                                    <option value="Affidavit"
                                                        <?= ($other_doc_type === 'Affidavit') ? 'selected' : '' ?>>Affidavit
                                                    </option>
                                                    <option value="Existing OBC/MOBC certificate"
                                                        <?= ($other_doc_type === 'Existing OBC/MOBC certificate') ? 'selected' : '' ?>>Existing OBC/MOBC certificate
                                                    </option>
                                                    <option value="Others"
                                                        <?= ($other_doc_type === 'Others') ? 'selected' : '' ?>>Others/অন্যান্য
                                                    </option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="other_doc" name="other_doc" type="file" />
                                                </div>
                                                <?php if (strlen($other_doc)) { ?>
                                                <a href="<?= base_url($other_doc) ?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="other_doc" type="hidden" name="other_doc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('other_doc'); ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <a href="<?= base_url('spservices/noncreamylayercertificate/registration/index/'.$obj_id) ?>"
                        class="btn btn-primary">
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