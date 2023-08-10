<?php
if ($dbrow) {
    $obj_id = $dbrow->_id->{'$id'};
    $uploadedFiles = $this->session->flashdata('uploaded_files');

    $proof_of_residence_type = isset($dbrow->form_data->enclosures->proof_of_residence_type) ? $dbrow->form_data->enclosures->proof_of_residence_type : set_value("proof_of_residence_type");
    // $proof_of_residence = $uploadedFiles['proof_of_residence_old'] ?? null;
    $proof_of_residence = isset($dbrow->form_data->enclosures->proof_of_residence) ? $dbrow->form_data->enclosures->proof_of_residence : ($uploadedFiles['proof_of_residence_old'] ?? null);

    $noc_from_current_employeer_type = isset($dbrow->form_data->enclosures->noc_from_current_employeer_type) ? $dbrow->form_data->enclosures->noc_from_current_employeer_type : set_value("noc_from_current_employeer_type");
    $noc_from_current_employeer = isset($dbrow->form_data->enclosures->noc_from_current_employeer) ? $dbrow->form_data->enclosures->noc_from_current_employeer : ($uploadedFiles['noc_from_current_employeer_old'] ?? null);

    $age_proof_type = isset($dbrow->form_data->enclosures->age_proof_type) ? $dbrow->form_data->enclosures->age_proof_type : set_value("age_proof_type");
    $age_proof = isset($dbrow->form_data->enclosures->age_proof) ? $dbrow->form_data->enclosures->age_proof : ($uploadedFiles['age_proof_old'] ?? null);

    $caste_certificate_type = isset($dbrow->form_data->enclosures->caste_certificate_type) ? $dbrow->form_data->enclosures->caste_certificate_type : set_value("caste_certificate_type");
    $caste_certificate = isset($dbrow->form_data->enclosures->caste_certificate) ? $dbrow->form_data->enclosures->caste_certificate : ($uploadedFiles['caste_certificate_old'] ?? null);

    $educational_qualification_type = isset($dbrow->form_data->enclosures->educational_qualification_type) ? $dbrow->form_data->enclosures->educational_qualification_type : set_value("educational_qualification_type");
    $educational_qualification = isset($dbrow->form_data->enclosures->educational_qualification) ? $dbrow->form_data->enclosures->educational_qualification : ($uploadedFiles['educational_qualification_old'] ?? null);

    $other_qualification_certificate_type = isset($dbrow->form_data->enclosures->other_qualification_certificate_type) ? $dbrow->form_data->enclosures->other_qualification_certificate_type : set_value("other_qualification_certificate_type");
    $other_qualification_certificate = isset($dbrow->form_data->enclosures->other_qualification_certificate) ? $dbrow->form_data->enclosures->other_qualification_certificate : ($uploadedFiles['other_qualification_certificate_old'] ?? null);

    $previous_employment_type = isset($dbrow->form_data->enclosures->previous_employment_type) ? $dbrow->form_data->enclosures->previous_employment_type : set_value("previous_employment_type");
    $previous_employment = isset($dbrow->form_data->enclosures->previous_employment) ? $dbrow->form_data->enclosures->previous_employment : ($uploadedFiles['previous_employment_old'] ?? null);

    $pwd_certificate_type = isset($dbrow->form_data->enclosures->pwd_certificate_type) ? $dbrow->form_data->enclosures->pwd_certificate_type : set_value("pwd_certificate_type");
    $pwd_certificate = isset($dbrow->form_data->enclosures->pwd_certificate) ? $dbrow->form_data->enclosures->pwd_certificate : ($uploadedFiles['pwd_certificate_old'] ?? null);

    $ex_servicemen_certificate_type = isset($dbrow->form_data->enclosures->ex_servicemen_certificate_type) ? $dbrow->form_data->enclosures->ex_servicemen_certificate_type : set_value("ex_servicemen_certificate_type");
    $ex_servicemen_certificate = isset($dbrow->form_data->enclosures->ex_servicemen_certificate) ? $dbrow->form_data->enclosures->ex_servicemen_certificate : ($uploadedFiles['ex_servicemen_certificate_old'] ?? null);

    $work_experience_type = isset($dbrow->form_data->enclosures->work_experience_type) ? $dbrow->form_data->enclosures->work_experience_type : set_value("work_experience_type");
    $work_experience = isset($dbrow->form_data->enclosures->work_experience) ? $dbrow->form_data->enclosures->work_experience : ($uploadedFiles['work_experience_old'] ?? null);

    $any_other_document_type = isset($dbrow->form_data->enclosures->any_other_document_type) ? $dbrow->form_data->enclosures->any_other_document_type : set_value("any_other_document_type");
    $any_other_document = isset($dbrow->form_data->enclosures->any_other_document) ? $dbrow->form_data->enclosures->any_other_document : ($uploadedFiles['any_other_document_old'] ?? null);

    $unique_document_type = isset($dbrow->form_data->enclosures->unique_document_type) ? $dbrow->form_data->enclosures->unique_document_type : set_value("unique_document_type");
    $unique_document = isset($dbrow->form_data->enclosures->unique_document) ? $dbrow->form_data->enclosures->unique_document : ($uploadedFiles['unique_document_old'] ?? null);

    $passport_photo_type = isset($dbrow->form_data->enclosures->passport_photo_type) ? $dbrow->form_data->enclosures->passport_photo_type : set_value("passport_photo_type");
    $passport_photo = isset($dbrow->form_data->enclosures->passport_photo) ? $dbrow->form_data->enclosures->passport_photo : ($uploadedFiles['passport_photo_old'] ?? null);

    $signature_type = isset($dbrow->form_data->enclosures->signature_photo_type) ? $dbrow->form_data->enclosures->signature_photo_type : set_value("signature_type");
    $signature = isset($dbrow->form_data->enclosures->signature_photo) ? $dbrow->form_data->enclosures->signature_photo : ($uploadedFiles['signature_old'] ?? null);
    $custom_fields = isset($dbrow->custom_field_values) ? $dbrow->custom_field_values : [];
    $editable_fields = [];
    if (count($custom_fields)) {
        foreach ($custom_fields as $val) {
            if ($val->field_name == 'editable_fields') {
                $editable_fields = $val->field_value;
            }
        }
    }
} else {
    $editable_fields = [];
    $uploadedFiles = $this->session->flashdata('uploaded_files');
    // $proof_of_residence_type = set_value("id_proof_type");
    $proof_of_residence = $uploadedFiles['proof_of_residence_old'] ?? null;
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
        $(document).on("click", "#open_passport_camera", function() {
            $("#live_passport_photo_div").show();
            Webcam.set({
                width: 320,
                height: 240,
                image_format: 'jpeg',
                jpeg_quality: 90
            });
            Webcam.attach('#passport_camera');
            $("#open_passport_camera").hide();
        });

        $(document).on("click", "#capture_passport_photo", function() {
            Webcam.snap(function(data_uri) { //alert(data_uri);
                $("#captured_passport_photo").attr("src", data_uri);
                $("#passport_photo_data").val(data_uri);
            });
        });

        $(document).on("click", "#open_signature_camera", function() {
            $("#live_signature_photo_div").show();
            Webcam.set({
                width: 320,
                height: 240,
                image_format: 'jpeg',
                jpeg_quality: 90
            });
            Webcam.attach('#my_signature_camera');
            $("#open_signature_camera").hide();
        });

        $(document).on("click", "#capture_signature_photo", function() {
            Webcam.snap(function(data_uri) { //alert(data_uri);
                $("#captured_signature_photo").attr("src", data_uri);
                $("#signature_photo_data").val(data_uri);
            });
        });

        var proofOfResidence = parseInt(<?= strlen($proof_of_residence) ? 1 : 0 ?>);
        $("#proof_of_residence").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            require: false,
            // required: proofOfResidence ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });

        var nocFromCurrentEmployeer = parseInt(<?= strlen($noc_from_current_employeer) ? 1 : 0 ?>);
        $("#noc_from_current_employeer").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            require: false,
            // required: nocFromCurrentEmployeer ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });

        var ageProof = parseInt(<?= strlen($age_proof) ? 1 : 0 ?>);
        $("#age_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            require: false,
            // required: ageProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });

        var casteCertificate = parseInt(<?= strlen($caste_certificate) ? 1 : 0 ?>);
        $("#caste_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            require: false,
            // required: casteCertificate ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });

        var educationalQualification = parseInt(<?= strlen($educational_qualification) ? 1 : 0 ?>);
        $("#educational_qualification").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            require: false,
            // required: educationalQualification ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });
        var otherQualification = parseInt(<?= strlen($other_qualification_certificate) ? 1 : 0 ?>);
        $("#other_qualification_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            require: false,
            // required: otherQualification ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });
        var previousEmployment = parseInt(<?= strlen($previous_employment) ? 1 : 0 ?>);
        $("#previous_employment").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            require: false,
            // required: previousEmployment ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });

        var pwdCertificate = parseInt(<?= strlen($pwd_certificate) ? 1 : 0 ?>);
        $("#pwd_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            require: false,
            // required: pwdCertificate ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });

        var exServicemen = parseInt(<?= strlen($ex_servicemen_certificate) ? 1 : 0 ?>);
        $("#ex_servicemen_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            require: false,
            // required: exServicemen ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });

        var workExperience = parseInt(<?= strlen($work_experience) ? 1 : 0 ?>);
        $("#work_experience").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            require: false,
            // required: workExperience ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });

        var anyOtherDocument = parseInt(<?= strlen($any_other_document) ? 1 : 0 ?>);
        $("#any_other_document").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            require: false,
            // required: anyOtherDocument ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });

        var aadhaarCard = parseInt(<?= strlen($unique_document) ? 1 : 0 ?>);
        $("#unique_document").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            require: false,
            // required: aadhaarCard ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });

        var passportPhoto = parseInt(<?= strlen($passport_photo) ? 1 : 0 ?>);
        $("#passport_photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            require: false,
            // required: passportPhoto ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
        });

        var signature = parseInt(<?= strlen($signature) ? 1 : 0 ?>);
        $("#signature").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            require: false,
            // required: passportPhoto ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png", "gif", "pdf"]
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/employment-reregistration-nonaadhaar/save-enclosures') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="proof_of_residence_old" type="hidden" value="<?= $proof_of_residence ?>" />
            <input name="noc_from_current_employeer_old" type="hidden" value="<?= $noc_from_current_employeer ?>" />
            <input name="age_proof_old" type="hidden" value="<?= $age_proof ?>" />
            <input name="caste_certificate_old" type="hidden" value="<?= $caste_certificate ?>" />
            <input name="educational_qualification_old" type="hidden" value="<?= $educational_qualification ?>" />
            <input name="other_qualification_certificate_old" type="hidden" value="<?= $other_qualification_certificate ?>" />
            <input name="previous_employment_old" type="hidden" value="<?= $previous_employment ?>" />
            <input name="pwd_certificate_old" type="hidden" value="<?= $pwd_certificate ?>" />
            <input name="ex_servicemen_certificate_old" type="hidden" value="<?= $ex_servicemen_certificate ?>" />
            <input name="work_experience_old" type="hidden" value="<?= $work_experience ?>" />
            <input name="any_other_document_old" type="hidden" value="<?= $any_other_document ?>" />
            <input name="unique_document_old" type="hidden" value="<?= $unique_document ?>" />
            <input name="passport_photo_old" type="hidden" value="<?= $passport_photo ?>" />
            <input name="signature_old" type="hidden" value="<?= $signature ?>" />

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
                    <?php
                    if (isset($dbrow->service_data->appl_status) && ($dbrow->service_data->appl_status == 'QS')) {
                        ($this->load->view('employment_nonaadhaar/query_message_view', $dbrow));
                    }
                    ?>
                    <h5 class="text-center mt-3 text-success"><u><strong>ENCLOSURES</strong></u></h5><br>
                    <fieldset class="border border-success" style="margin-top:0px">
                        <p>If you wish to upload your enclosures/documents from digilocker, please link your digilocker account. Click on the below button to link your account.</p>
                        <p><?php $this->digilocker_api->login_btn();  ?></p>
                    </fieldset>
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
                                            <?= (count(employmentcertificate('proof_of_residence')['recomended_documents']) > 1) ? '<option value="">Please Select</option>' : '' ?>
                                            <?php foreach (employmentcertificate('proof_of_residence')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $proof_of_residence_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>
                                            <?php } ?>
                                        </select>
                                        <?= form_error("proof_of_residence_type") ?>
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
                                        <input class="proof_of_residence" type="hidden" name="proof_of_residence_temp">
                                        <?= $this->digilocker_api->digilocker_fetch_btn('proof_of_residence'); ?>
                                        <?= form_error("proof_of_residence") ?>
                                    </td>
                                </tr>
                                <?php if ($dbrow->form_data->current_employment_status === 'Employed - Fulltime Govt.') { ?>
                                    <tr>
                                        <td><?= employmentcertificate('noc_from_current_employeer')['enclosure_type'] ?></td>
                                        <td>
                                            <select name="noc_from_current_employeer_type" class="form-control">
                                                <?= (count(employmentcertificate('noc_from_current_employeer')['recomended_documents']) > 1) ? '<option value="">Please Select</option>' : '' ?>
                                                <?php foreach (employmentcertificate('noc_from_current_employeer')['recomended_documents'] as $doc) { ?>
                                                    <option value="<?= $doc ?>" <?= $noc_from_current_employeer_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                                <?php } ?>
                                            </select>
                                            <?= form_error("noc_from_current_employeer_type") ?>
                                        </td>
                                        <td>
                                            <div class="file-loading">
                                                <input id="noc_from_current_employeer" name="noc_from_current_employeer" type="file" />
                                            </div>
                                            <?php if (strlen($noc_from_current_employeer)) { ?>
                                                <a href="<?= base_url($noc_from_current_employeer) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                            <?php } //End of if 
                                            ?>
                                            <input class="noc_from_current_employeer" type="hidden" name="noc_from_current_employeer_temp">
                                            <?= $this->digilocker_api->digilocker_fetch_btn('noc_from_current_employeer'); ?>
                                            <?= form_error("noc_from_current_employeer") ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td><?= employmentcertificate('age_proof')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="age_proof_type" class="form-control">
                                            <?= (count(employmentcertificate('age_proof')['recomended_documents']) > 1) ? '<option value="">Please Select</option>' : '' ?>
                                            <?php foreach (employmentcertificate('age_proof')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= ($age_proof_type === $doc) ? 'selected' : '' ?>><?= $doc ?></option>
                                            <?php } ?>
                                        </select>
                                        <?= form_error("age_proof_type") ?>
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
                                        <input class="age_proof" type="hidden" name="age_proof_temp">
                                        <?= $this->digilocker_api->digilocker_fetch_btn('age_proof'); ?>
                                        <?= form_error("age_proof") ?>
                                    </td>
                                </tr>
                                <?php if ($dbrow->form_data->caste != 'General') { ?>
                                    <tr>
                                        <td><?= employmentcertificate('caste_certificate')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                        <td>
                                            <select name="caste_certificate_type" class="form-control">
                                                <?= (count(employmentcertificate('caste_certificate')['recomended_documents']) > 1) ? '<option value="">Please Select</option>' : '' ?>
                                                <?php foreach (employmentcertificate('caste_certificate')['recomended_documents'] as $doc) { ?>
                                                    <option value="<?= $doc ?>" <?= $caste_certificate_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                                <?php } ?>
                                            </select>
                                            <?= form_error("caste_certificate_type") ?>
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
                                            <input class="caste_certificate" type="hidden" name="caste_certificate_temp">
                                            <?= $this->digilocker_api->digilocker_fetch_btn('caste_certificate'); ?>
                                            <?= form_error("caste_certificate") ?>
                                        </td>
                                    </tr>
                                <?php } elseif ($dbrow->form_data->economically_weaker_section === 'Yes') { ?>
                                    <tr>
                                        <td><?= employmentcertificate('caste_certificate')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                        <td>
                                            <select name="caste_certificate_type" class="form-control">
                                                <?= (count(employmentcertificate('caste_certificate')['recomended_documents']) > 1) ? '<option value="">Please Select</option>' : '' ?>
                                                <?php foreach (employmentcertificate('caste_certificate')['recomended_documents'] as $doc) { ?>
                                                    <option value="<?= $doc ?>" <?= $caste_certificate_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                                <?php } ?>
                                            </select>
                                            <?= form_error("caste_certificate_type") ?>
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
                                            <input class="caste_certificate" type="hidden" name="caste_certificate_temp">
                                            <?= $this->digilocker_api->digilocker_fetch_btn('caste_certificate'); ?>
                                            <?= form_error("caste_certificate") ?>
                                        </td>
                                    </tr>
                                <?php }
                                if ($dbrow->form_data->highest_educational_level != 'Illiterate') { ?>
                                    <tr>
                                        <td><?= employmentcertificate('educational_qualification')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                        <td>
                                            <select name="educational_qualification_type" class="form-control">
                                                <?= (count(employmentcertificate('educational_qualification')['recomended_documents']) > 1) ? '<option value="">Please Select</option>' : '' ?>
                                                <?php foreach (employmentcertificate('educational_qualification')['recomended_documents'] as $doc) { ?>
                                                    <option value="<?= $doc ?>" <?= $educational_qualification_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                                <?php } ?>
                                            </select>
                                            <?= form_error("educational_qualification_type") ?>
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
                                            <input class="educational_qualification" type="hidden" name="educational_qualification_temp">
                                            <?= $this->digilocker_api->digilocker_fetch_btn('educational_qualification'); ?>
                                            <?= form_error("educational_qualification") ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td><?= employmentcertificate('other_qualification_certificate')['enclosure_type'] ?></td>
                                    <td>
                                        <select name="other_qualification_certificate_type" class="form-control">
                                            <?= (count(employmentcertificate('other_qualification_certificate')['recomended_documents']) > 1) ? '<option value="">Please Select</option>' : '' ?>
                                            <?php foreach (employmentcertificate('other_qualification_certificate')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $other_qualification_certificate_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                        <?= form_error("other_qualification_certificate_type") ?>
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
                                        <input class="other_qualification_certificate" type="hidden" name="other_qualification_certificate_temp">
                                        <?= $this->digilocker_api->digilocker_fetch_btn('other_qualification_certificate'); ?>
                                        <?= form_error("other_qualification_certificate") ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('previous_employment')['enclosure_type'] ?></td>
                                    <td>
                                        <select name="previous_employment_type" class="form-control">
                                            <?= (count(employmentcertificate('previous_employment')['recomended_documents']) > 1) ? '<option value="">Please Select</option>' : '' ?>
                                            <?php foreach (employmentcertificate('previous_employment')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $previous_employment_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                        <?= form_error("previous_employment_type") ?>
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
                                        <input class="previous_employment" type="hidden" name="previous_employment_temp">
                                        <?= $this->digilocker_api->digilocker_fetch_btn('previous_employment'); ?>
                                        <?= form_error("previous_employment") ?>
                                    </td>
                                </tr>
                                <?php if ($dbrow->form_data->are_you_differently_abled__pwd === 'Yes') { ?>
                                    <tr>
                                        <td><?= employmentcertificate('pwd_certificate')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                        <td>
                                            <select name="pwd_certificate_type" class="form-control">
                                                <?= (count(employmentcertificate('pwd_certificate')['recomended_documents']) > 1) ? '<option value="">Please Select</option>' : '' ?>
                                                <?php foreach (employmentcertificate('pwd_certificate')['recomended_documents'] as $doc) { ?>
                                                    <option value="<?= $doc ?>" <?= $pwd_certificate_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                                <?php } ?>
                                            </select>
                                            <?= form_error("pwd_certificate_type") ?>
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
                                            <input class="pwd_certificate" type="hidden" name="pwd_certificate_temp">
                                            <?= $this->digilocker_api->digilocker_fetch_btn('pwd_certificate'); ?>
                                            <?= form_error("pwd_certificate") ?>
                                        </td>
                                    </tr>
                                <?php }
                                if ($dbrow->form_data->{'whether_ex-servicemen'} === 'Yes') { ?>
                                    <tr>
                                        <td><?= employmentcertificate('ex_servicemen_certificate')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                        <td>
                                            <select name="ex_servicemen_certificate_type" class="form-control">
                                                <?= (count(employmentcertificate('ex_servicemen_certificate')['recomended_documents']) > 1) ? '<option value="">Please Select</option>' : '' ?>
                                                <?php foreach (employmentcertificate('ex_servicemen_certificate')['recomended_documents'] as $doc) { ?>
                                                    <option value="<?= $doc ?>" <?= $ex_servicemen_certificate_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                                <?php } ?>
                                            </select>
                                            <?= form_error("ex_servicemen_certificate_type") ?>
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
                                            <input class="ex_servicemen_certificate" type="hidden" name="ex_servicemen_certificate_temp">
                                            <?= $this->digilocker_api->digilocker_fetch_btn('ex_servicemen_certificate'); ?>
                                            <?= form_error("ex_servicemen_certificate") ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td><?= employmentcertificate('work_experience')['enclosure_type'] ?></td>
                                    <td>
                                        <select name="work_experience_type" class="form-control">
                                            <?= (count(employmentcertificate('work_experience')['recomended_documents']) > 1) ? '<option value="">Please Select</option>' : '' ?>
                                            <?php foreach (employmentcertificate('work_experience')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $work_experience_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                        <?= form_error("work_experience_type") ?>
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
                                        <input class="work_experience" type="hidden" name="work_experience_temp">
                                        <?= $this->digilocker_api->digilocker_fetch_btn('work_experience'); ?>
                                        <?= form_error("work_experience") ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('any_other_document')['enclosure_type'] ?></td>
                                    <td>
                                        <select name="any_other_document_type" class="form-control">
                                            <?= (count(employmentcertificate('any_other_document')['recomended_documents']) > 1) ? '<option value="">Please Select</option>' : '' ?>
                                            <?php foreach (employmentcertificate('any_other_document')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $any_other_document_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                        <?= form_error("any_other_document_type") ?>
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
                                        <input class="any_other_document" type="hidden" name="any_other_document_temp">
                                        <?= $this->digilocker_api->digilocker_fetch_btn('any_other_document'); ?>
                                        <?= form_error("any_other_document") ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('unique_document')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="unique_document_type" class="form-control">
                                            <?= (count(employmentcertificate('unique_document')['recomended_documents']) > 1) ? '<option value="">Please Select</option>' : '' ?>
                                            <?php foreach (employmentcertificate('unique_document')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $unique_document_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                        <?= form_error("unique_document_type") ?>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="unique_document" name="unique_document" type="file" />
                                        </div>
                                        <?php if (strlen($unique_document)) { ?>
                                            <a href="<?= base_url($unique_document) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                        <input class="unique_document" type="hidden" name="unique_document_temp">
                                        <?= $this->digilocker_api->digilocker_fetch_btn('unique_document'); ?>
                                        <?= form_error("unique_document") ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('passport_photo')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="passport_photo_type" class="form-control">
                                            <?= (count(employmentcertificate('passport_photo')['recomended_documents']) > 1) ? '<option value="">Please Select</option>' : '' ?>
                                            <?php foreach (employmentcertificate('passport_photo')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $passport_photo_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>;
                                            <?php } ?>
                                        </select>
                                        <?= form_error("passport_photo_type") ?>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="passport_photo" name="passport_photo" type="file" accept="image/*" />
                                        </div>
                                        <?php if (strlen($passport_photo)) { ?>
                                            <a href="<?= base_url($passport_photo) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                        <?= form_error("passport_photo") ?>
                                        <div class="live_photo_passport">
                                            <div id="live_passport_photo_div" class="row text-center" style="display:none;">
                                                <div id="passport_camera" class="col-md-6 text-center"></div>
                                                <div class="col-md-6 text-center">
                                                    <img id="captured_passport_photo" src="<?= base_url('assets/plugins/webcamjs/no-photo.png') ?>" style="width: 320px; height: 240px;" />
                                                </div>
                                                <input id="passport_photo_data" name="passport_photo_data" value="" type="hidden" />
                                                <button id="capture_passport_photo" class="btn btn-warning" style="margin:2px auto" type="button">Capture Photo</button>
                                            </div>
                                            <div style="text-align:right">
                                                <img id="open_passport_camera" src="<?= base_url('assets/plugins/webcamjs/camera.png') ?>" style="width:30px; height: 30px; cursor: pointer" />
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= employmentcertificate('signature')['enclosure_type'] ?><span class="text-danger">*</span></td>
                                    <td>
                                        <select name="signature_type" class="form-control">
                                            <?= (count(employmentcertificate('signature')['recomended_documents']) > 1) ? '<option value="">Please Select</option>' : '' ?>
                                            <?php foreach (employmentcertificate('signature')['recomended_documents'] as $doc) { ?>
                                                <option value="<?= $doc ?>" <?= $signature_type === $doc ? 'selected' : '' ?>><?= $doc ?></option>
                                            <?php } ?>
                                        </select>
                                        <?= form_error("signature_type") ?>
                                    </td>
                                    <td>
                                        <div class="file-loading">
                                            <input id="signature" name="signature" type="file" accept="image/*" />
                                        </div>
                                        <?php if (strlen($signature)) { ?>
                                            <a href="<?= base_url($signature) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                        <?= form_error("signature") ?>
                                        <div class="live_photo_signature">
                                            <div id="live_signature_photo_div" class="row text-center" style="display:none;">
                                                <div id="my_signature_camera" class="col-md-6 text-center"></div>
                                                <div class="col-md-6 text-center">
                                                    <img id="captured_signature_photo" src="<?= base_url('assets/plugins/webcamjs/no-photo.png') ?>" style="width: 320px; height: 240px;" />
                                                </div>
                                                <input id="signature_photo_data" name="signature_photo_data" value="" type="hidden" />
                                                <button id="capture_signature_photo" class="btn btn-warning" style="margin:2px auto" type="button">Capture Photo</button>
                                            </div>
                                            <div style="text-align:right">
                                                <img id="open_signature_camera" src="<?= base_url('assets/plugins/webcamjs/camera.png') ?>" style="width:30px; height: 30px; cursor: pointer" />
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Declaration</legend>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="declaration" value="1" id="declaration">
                                    <label class="form-check-label" for="declaration">
                                        I hereby declare that I have read all the <a href="#" type="button" data-toggle="modal" data-target="#declaration_modal">terms and conditions</a> and I have no objection.
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
                                    <p>Please enter OTP received on your aadhaar registered mobile number to verify your details.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/employment-reregistration-nonaadhaar/slot-booking/' . $obj_id) ?>">
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