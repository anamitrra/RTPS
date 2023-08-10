<?php

if ($dbrow) {
    $obj_id = $dbrow->_id->{'$id'};
    $uploadedFiles = $this->session->flashdata('uploaded_files');

    // $proof_of_residence_type = 'Voter Card';
    $proof_of_residence_type = isset($dbrow->form_data->proof_of_residence_type) ? $dbrow->form_data->proof_of_residence_type : set_value("proof_of_residence_type");
    $proof_of_residence = isset($dbrow->form_data->proof_of_residence) ? $dbrow->form_data->proof_of_residence : null;
    // $photo_frm = $uploadedFiles['photo_old']??null;

} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL; //set_value("rtps_trans_id");

    // $proof_of_residence_type = 'Voter Card';

    $uploadedFiles = $this->session->flashdata('uploaded_files');

    $proof_of_residence_type = set_value("id_proof_type");
    $proof_of_residence = $uploadedFiles['proof_of_residence_old'] ?? null;
    // noc_from_current_employeer
    $noc_from_current_employeer = set_value("noc_from_current_employeer");
    $noc_from_current_employeer_file = $uploadedFiles['noc_from_current_employeer_file_old'] ?? null;
    // age_proof
    $age_proof_type = set_value("age_proof_type");
    $age_proof = $uploadedFiles['age_proof_old'] ?? null;
    // caste_certificate
    $caste_certificate_type = set_value("caste_certificate_type");
    $caste_certificate = $uploadedFiles['caste_certificate_old'] ?? null;
    // educational_qualification
    $educational_qualification_type = set_value("educational_qualification_type");
    $educational_qualification = $uploadedFiles['educational_qualification_old'] ?? null;
    // other_qualification_certificate
    $other_qualification_certificate_type = set_value("other_qualification_certificate_type");
    $other_qualification_certificate = $uploadedFiles['other_qualification_certificate_old'] ?? null;
    // previous_employment
    $previous_employment_type = set_value("previous_employment_type");
    $previous_employment = $uploadedFiles['previous_employment_old'] ?? null;
    // pwd_certificate
    $pwd_certificate_type = set_value("pwd_certificate_type");
    $pwd_certificate = $uploadedFiles['pwd_certificate_old'] ?? null;
    // ex_servicemen_certificate
    $ex_servicemen_certificate_type = set_value("ex_servicemen_certificate_type");
    $ex_servicemen_certificate = $uploadedFiles['ex_servicemen_certificate_old'] ?? null;
    // work_experience
    $work_experience_type = set_value("work_experience_type");
    $work_experience = $uploadedFiles['work_experience_old'] ?? null;
    // any_other_document
    $any_other_document_type = set_value("any_other_document_type");
    $any_other_document = $uploadedFiles['any_other_document_old'] ?? null;
    // aadhaar_card
    $aadhaar_card = set_value("aadhaar_card");
    $aadhaar_card_file = $uploadedFiles['aadhaar_card_file_old'] ?? null;
    // soft_copy
    $soft_copy = set_value("soft_copy");
    $soft_copy_file = $uploadedFiles['soft_copy_file_old'] ?? null;
    // signature_type
    $signature_type = set_value("signature_type");
    $signature_photo = $uploadedFiles['signature_photo_old'] ?? null;
    // passport_photo_type
    $passport_photo_type = set_value("passport_photo_type");
    $passport_photo = $uploadedFiles['passport_photo_old'] ?? null;

    $query_asked = '';
    $queried_by = '';
    $queried_time = '';
    $query_answered = set_value("query_answered");
    $query_doc = $uploadedFiles['query_doc_old'] ?? null;
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
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#open_camera", function() {
            $("#live_photo_div").show();
            Webcam.set({
                width: 320,
                height: 240,
                image_format: 'jpeg',
                jpeg_quality: 90
            });
            Webcam.attach('#my_camera');
            $("#open_camera").hide();
        });

        $(document).on("click", "#capture_photo", function() {
            Webcam.snap(function(data_uri) { //alert(data_uri);
                $("#captured_photo").attr("src", data_uri);
                $("#passport_photo_data").val(data_uri);
            });
        });

        var proofOfResidence = parseInt(<?= strlen($proof_of_residence) ? 1 : 0 ?>);
        $("#proof_of_residence").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: proofOfResidence ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });
        var addressProof = parseInt(<?= strlen($noc_from_current_employeer_file) ? 1 : 0 ?>);
        $("#noc_from_current_employeer_file").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: addressProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });

        var ageProof = parseInt(<?= strlen($age_proof) ? 1 : 0 ?>);
        $("#age_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: ageProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });
        var ageProof = parseInt(<?= strlen($caste_certificate) ? 1 : 0 ?>);
        $("#caste_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: ageProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });
        var ageProof = parseInt(<?= strlen($educational_qualification) ? 1 : 0 ?>);
        $("#educational_qualification").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: ageProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });
        var ageProof = parseInt(<?= strlen($other_qualification_certificate) ? 1 : 0 ?>);
        $("#other_qualification_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: ageProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });
        var ageProof = parseInt(<?= strlen($previous_employment) ? 1 : 0 ?>);
        $("#previous_employment").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: ageProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });
        var ageProof = parseInt(<?= strlen($pwd_certificate) ? 1 : 0 ?>);
        $("#pwd_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: ageProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });
        var ageProof = parseInt(<?= strlen($ex_servicemen_certificate) ? 1 : 0 ?>);
        $("#ex_servicemen_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: ageProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });
        var ageProof = parseInt(<?= strlen($work_experience) ? 1 : 0 ?>);
        $("#work_experience").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: ageProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });
        var ageProof = parseInt(<?= strlen($any_other_document) ? 1 : 0 ?>);
        $("#any_other_document").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: ageProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });

        var passportProof = parseInt(<?= strlen($aadhaar_card_file) ? 1 : 0 ?>);
        $("#aadhaar_card_file").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: passportProof ? false : false,
            maxFileSize: 200,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif"]
        });
        var passportProof = parseInt(<?= strlen($soft_copy_file) ? 1 : 0 ?>);
        $("#soft_copy_file").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: passportProof ? false : false,
            maxFileSize: 200,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif"]
        });
        var passportProof = parseInt(<?= strlen($signature_photo) ? 1 : 0 ?>);
        $("#signature_photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: passportProof ? false : false,
            maxFileSize: 200,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif"]
        });
        var passportProof = parseInt(<?= strlen($passport_photo) ? 1 : 0 ?>);
        $("#passport_photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: passportProof ? false : false,
            maxFileSize: 200,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif"]
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/employment_aadhaar_based/registration/submit_exchange_office') ?>" enctype="multipart/form-data">

            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Registration of employment seeker in Employment Exchange
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

                    <h5 class="text-center mt-3 text-success"><u><strong>ENCLOSURES</strong></u></h5>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td colspan="3" style="color:#fd7e14; text-align: center; font-size: 14px">
                                        Note : For ID proof, Address proof, Age proof only jpg, jpeg, png and pdf of maximum 1MB is allowed;
                                        For Passport size photo only jpg, jpeg, and png of maximum 200KB is allowed
                                    </td>
                                </tr>
                                <tr>
                                    <th>Type of Enclosure</th>
                                    <th>Enclosure Document</th>
                                    <th>File/Reference</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td><?= employmentcertificate('proof_of_residence')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="proof_of_residence_type" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach (employmentcertificate('proof_of_residence')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $proof_of_residence_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="proof_of_residence" name="proof_of_residence" type="file" />
                                        </div>
                                        <?php if (strlen($proof_of_residence)) { ?>
                                            <a href="<?= base_url($proof_of_residence) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('noc_from_current_employeer')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="noc_from_current_employeer" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach (employmentcertificate('noc_from_current_employeer')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $noc_from_current_employeer === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="noc_from_current_employeer_file" name="noc_from_current_employeer_file" type="file" />
                                        </div>
                                        <?php if (strlen($noc_from_current_employeer_file)) { ?>
                                            <a href="<?= base_url($noc_from_current_employeer_file) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('age_proof')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="age_proof_type" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach (employmentcertificate('age_proof')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $age_proof_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="age_proof" name="age_proof" type="file" />
                                        </div>
                                        <?php if (strlen($age_proof)) { ?>
                                            <a href="<?= base_url($age_proof) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('caste_certificate')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="caste_certificate_type" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach (employmentcertificate('caste_certificate')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $caste_certificate_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="caste_certificate" name="caste_certificate" type="file" />
                                        </div>
                                        <?php if (strlen($caste_certificate)) { ?>
                                            <a href="<?= base_url($caste_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('educational_qualification')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="educational_qualification_type" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach (employmentcertificate('educational_qualification')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $educational_qualification_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="educational_qualification" name="educational_qualification" type="file" />
                                        </div>
                                        <?php if (strlen($educational_qualification)) { ?>
                                            <a href="<?= base_url($educational_qualification) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('other_qualification_certificate')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="other_qualification_certificate_type" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach (employmentcertificate('other_qualification_certificate')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $other_qualification_certificate_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="other_qualification_certificate" name="other_qualification_certificate" type="file" />
                                        </div>
                                        <?php if (strlen($other_qualification_certificate)) { ?>
                                            <a href="<?= base_url($other_qualification_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('previous_employment')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="previous_employment_type" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach (employmentcertificate('previous_employment')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $previous_employment_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="previous_employment" name="previous_employment" type="file" />
                                        </div>
                                        <?php if (strlen($previous_employment)) { ?>
                                            <a href="<?= base_url($previous_employment) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('pwd_certificate')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="pwd_certificate_type" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach (employmentcertificate('pwd_certificate')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $pwd_certificate_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="pwd_certificate" name="pwd_certificate" type="file" />
                                        </div>
                                        <?php if (strlen($pwd_certificate)) { ?>
                                            <a href="<?= base_url($pwd_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('ex_servicemen_certificate')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="ex_servicemen_certificate_type" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach (employmentcertificate('ex_servicemen_certificate')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $ex_servicemen_certificate_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="ex_servicemen_certificate" name="ex_servicemen_certificate" type="file" />
                                        </div>
                                        <?php if (strlen($ex_servicemen_certificate)) { ?>
                                            <a href="<?= base_url($ex_servicemen_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('work_experience')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="work_experience_type" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach (employmentcertificate('work_experience')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $work_experience_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="work_experience" name="work_experience" type="file" />
                                        </div>
                                        <?php if (strlen($work_experience)) { ?>
                                            <a href="<?= base_url($work_experience) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('any_other_document')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="any_other_document_type" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach (employmentcertificate('any_other_document')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $any_other_document_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="any_other_document" name="any_other_document" type="file" />
                                        </div>
                                        <?php if (strlen($any_other_document)) { ?>
                                            <a href="<?= base_url($any_other_document) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('aadhaar_card')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="aadhaar_card" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach (employmentcertificate('aadhaar_card')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $aadhaar_card === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="aadhaar_card_file" name="aadhaar_card_file" type="file" />
                                        </div>
                                        <?php if (strlen($aadhaar_card_file)) { ?>
                                            <a href="<?= base_url($aadhaar_card_file) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('soft_copy')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="soft_copy" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach (employmentcertificate('soft_copy')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $soft_copy === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="soft_copy_file" name="soft_copy_file" type="file" />
                                        </div>
                                        <?php if (strlen($soft_copy_file)) { ?>
                                            <a href="<?= base_url($soft_copy_file) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('passport_photo')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="passport_photo_type" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach (employmentcertificate('passport_photo')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $passport_photo_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="passport_photo" name="passport_photo" type="file" />
                                        </div>
                                        <?php if (strlen($passport_photo)) { ?>
                                            <a href="<?= base_url($passport_photo) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('signature')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="signature_type" class="form-control">
                                            <option value="">Select</option>
                                            <?php foreach (employmentcertificate('signature')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $signature_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="signature_photo" name="signature_photo" type="file" />
                                        </div>
                                        <?php if (strlen($signature_photo)) { ?>
                                            <a href="<?= base_url($signature_photo) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <div id="live_photo_div" class="row text-center" style="display:none;">
                                            <div id="my_camera" class="col-md-6 text-center"></div>
                                            <div class="col-md-6 text-center">
                                                <img id="captured_photo" src="<?= base_url('assets/plugins/webcamjs/no-photo.png') ?>" style="width: 320px; height: 240px;" />
                                            </div>
                                            <input id="passport_photo_data" name="passport_photo_data" value="" type="hidden" />
                                            <button id="capture_photo" class="btn btn-warning" style="margin:2px auto" type="button">Capture Photo</button>
                                        </div>
                                        <div style="text-align:right">
                                            <img id="open_camera" src="<?= base_url('assets/plugins/webcamjs/camera.png') ?>" style="width:50px; height: 50px; cursor: pointer" />
                                        </div>
                                    </td>
                                </tr>
                                <?php if ($form_status === 'QUERY_ARISE') { ?>
                                    <tr>
                                        <td></td>
                                        <td>
                                            <select class="form-control">
                                                <option value=""><?= $this->lang->line('query_related_file') ?></option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="file-loading">
                                                <input id="query_doc" name="query_doc" type="file" />
                                            </div>
                                            <?php if (strlen($query_doc)) { ?>
                                                <a href="<?= base_url($query_doc) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                            <?php } //End of if 
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <label><?= $this->lang->line('remarks') ?> </label>
                                            <textarea class="form-control" name="query_answered"><?= $query_answered ?></textarea>
                                            <?= form_error("query_answered") ?>
                </div>
                </tr>
            <?php } //End of if 
            ?>
            </tbody>
            </table>
            </fieldset>
            <fieldset class="border border-success" style="margin-top:40px">
                <legend class="h5">Declaration</legend>
                <div class="row form-group">
                    <div class="col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="declaration">
                            <label class="form-check-label" for="declaration">
                                I hereby declare that I have read all the <a href="#" type="button" data-toggle="modal" data-target="#declaration_modal">terms and conditions</a> and I have no objection.
                            </label>
                        </div>
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
                            <p>Please enter OTP received on your aadhaar registered mobile number to verify your details.</p>
                        </div>
                    </div>
                </div>
            </div>
            </div><!--End of .card-body -->

            <div class="card-footer text-center">
                <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                    <i class="fa fa-angle-double-left"></i> Previous
                </button>
                <button class="btn btn-success frmbtn save_next d-none" id="SAVE" type="submit">
                    <i class="fa fa-angle-double-right"></i> Save &amp; Next
                </button>
            </div><!--End of .card-footer-->
    </div><!--End of .card-->
    </form>
    </div><!--End of .container-->
</main>