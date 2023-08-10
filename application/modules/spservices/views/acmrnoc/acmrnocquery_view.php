<?php
$currentYear = date('Y');
//$apiServer = $this->config->item('edistrict_base_url');
$apiServer = "https://localhost/wptbcapis/"; //For testing
if ($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $service_name = $dbrow->service_data->service_name;
    $status = $dbrow->service_data->appl_status;

    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $father_name = $dbrow->form_data->father_name;
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email;
    $dob = $dbrow->form_data->dob;
    $pan_no = !empty($dbrow->form_data->pan_no) ? $dbrow->form_data->pan_no : 'NA';

    $permanent_address = $dbrow->form_data->permanent_address;
    $correspondence_address = $dbrow->form_data->correspondence_address;
    $permanent_reg_no = $dbrow->form_data->permanent_reg_no;
    $permanent_reg_date = $dbrow->form_data->permanent_reg_date;
    $additional_degree_reg_no = !empty($dbrow->form_data->additional_degree_reg_no) ? $dbrow->form_data->additional_degree_reg_no : 'NA';
    $additional_degree_reg_date = !empty($dbrow->form_data->additional_degree_reg_date) ? $dbrow->form_data->additional_degree_reg_date : 'NA';

    $registering_smc = $dbrow->form_data->registering_smc;
    $relocating_reason = $dbrow->form_data->relocating_reason;
    $working_place_add = !empty($dbrow->form_data->working_place_add) ? $dbrow->form_data->working_place_add : 'NA';


    //ENCLOSURES DATA ---START
    $passport_photo_type_frm = set_value("passport_photo_type");
    $signature_type_frm = set_value("signature_type");
    $ug_pg_diploma_type_frm = set_value("ug_pg_diploma_type");
    $prc_type_frm = set_value("prc_type");
    $mbbs_certificate_type_frm = set_value("mbbs_certificate_type");
    $noc_dme_type_frm = set_value("noc_dme_type");
    $seat_allt_letter_type_frm = set_value("seat_allt_letter_type");

    $uploadedFiles = $this->session->flashdata('uploaded_files');
    $passport_photo_frm = $uploadedFiles['passport_photo_old'] ?? null;
    $signature_frm = $uploadedFiles['signature_old'] ?? null;
    $ug_pg_diploma_frm = $uploadedFiles['ug_pg_diploma_old'] ?? null;
    $prc_frm = $uploadedFiles['prc_old'] ?? null;
    $mbbs_certificate_frm = $uploadedFiles['mbbs_certificate_old'] ?? null;
    $noc_dme_frm = $uploadedFiles['noc_dme_old'] ?? null;
    $seat_allt_letter_frm = $uploadedFiles['seat_allt_letter_old'] ?? null;

    $passport_photo_type_db = $dbrow->form_data->passport_photo_type ?? null;
    $signature_type_db = $dbrow->form_data->signature_type ?? null;
    $ug_pg_diploma_type_db = $dbrow->form_data->ug_pg_diploma_type ?? null;
    $prc_type_db = $dbrow->form_data->prc_type ?? null;
    $mbbs_certificate_type_db = $dbrow->form_data->mbbs_certificate_type ?? null;
    $noc_dme_type_db = $dbrow->form_data->noc_dme_type ?? null;
    $seat_allt_letter_type_db = $dbrow->form_data->seat_allt_letter_type ?? null;

    $passport_photo_db = $dbrow->form_data->passport_photo ?? null;
    $signature_db = $dbrow->form_data->signature ?? null;
    $ug_pg_diploma_db = $dbrow->form_data->ug_pg_diploma ?? null;
    $prc_db = $dbrow->form_data->prc ?? null;
    $mbbs_certificate_db = $dbrow->form_data->mbbs_certificate ?? null;
    $noc_dme_db = $dbrow->form_data->noc_dme ?? null;
    $seat_allt_letter_db = $dbrow->form_data->seat_allt_letter ?? null;

    $passport_photo_type = strlen($passport_photo_type_frm) ? $passport_photo_type_frm : $passport_photo_type_db;
    $signature_type = strlen($signature_type_frm) ? $signature_type_frm : $signature_type_db;
    $ug_pg_diploma_type = strlen($ug_pg_diploma_type_frm) ? $ug_pg_diploma_type_frm : $ug_pg_diploma_type_db;
    $prc_type = strlen($prc_type_frm) ? $prc_type_frm : $prc_type_db;
    $mbbs_certificate_type = strlen($mbbs_certificate_type_frm) ? $mbbs_certificate_type_frm : $mbbs_certificate_type_db;
    $noc_dme_type = strlen($noc_dme_type_frm) ? $noc_dme_type_frm : $noc_dme_type_db;
    $seat_allt_letter_type = strlen($seat_allt_letter_type_frm) ? $seat_allt_letter_type_frm : $seat_allt_letter_type_db;

    $passport_photo = strlen($passport_photo_frm) ? $passport_photo_frm : $passport_photo_db;
    $signature = strlen($signature_frm) ? $signature_frm : $signature_db;
    $ug_pg_diploma = strlen($ug_pg_diploma_frm) ? $ug_pg_diploma_frm : $ug_pg_diploma_db;
    $prc = strlen($prc_frm) ? $prc_frm : $prc_db;
    $mbbs_certificate = strlen($mbbs_certificate_frm) ? $mbbs_certificate_frm : $mbbs_certificate_db;
    $noc_dme = strlen($noc_dme_frm) ? $noc_dme_frm : $noc_dme_db;
    $seat_allt_letter = strlen($seat_allt_letter_frm) ? $seat_allt_letter_frm : $seat_allt_letter_db;

    //ENCLOSURES DATA ---END

} ?>
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

<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {


        $.post("<?= base_url('iservices/wptbc/castecertificate/createcaptcha') ?>", function(res) {
            $("#captchadiv").html(res);
        });

        $(document).on("click", "#reloadcaptcha", function() {
            $.post("<?= base_url('iservices/wptbc/castecertificate/createcaptcha') ?>", function(res) {
                $("#captchadiv").html(res);
            });
        }); //End of onChange #reloadcaptcha

        $(document).on("click", ".frmbtn", function() {
            let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
            $("#submission_mode").val(clickedBtn);
            if (clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if (clickedBtn === 'SAVE') {
                var msg = "Do you want to procced";
            } else if (clickedBtn === 'CLEAR') {
                var msg = "Once you Reset, All filled data will be cleared";
            } else {
                var msg = "";
            } //End of if else            
            Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    if ((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
                        $("#myfrm").submit();
                        $(".frmbtn").hide();
                    } else if (clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {} //End of if else
                }
            });
        });

        $(document).ready(function() {
            var passportPhoto = parseInt(<?= strlen($passport_photo) ? 1 : 0 ?>);
            $("#passport_photo").fileinput({
                dropZoneEnabled: false,
                showUpload: false,
                showRemove: false,
                required: passportPhoto ? false : true,
                maxFileSize: 1024,
                allowedFileExtensions: ["jpg", "jpeg"]
            });

            var applSign = parseInt(<?= strlen($signature) ? 1 : 0 ?>);
            $("#signature").fileinput({
                dropZoneEnabled: false,
                showUpload: false,
                showRemove: false,
                required: applSign ? false : true,
                maxFileSize: 1024,
                allowedFileExtensions: ["jpg", "jpeg"]
            });
            var ugPgDiploma = parseInt(<?= strlen($ug_pg_diploma) ? 1 : 0 ?>);
            $("#ug_pg_diploma").fileinput({
                dropZoneEnabled: false,
                showUpload: false,
                showRemove: false,
                required: ugPgDiploma ? false : true,
                maxFileSize: 1024,
                allowedFileExtensions: ["pdf"]
            });

            var prcDoc = parseInt(<?= strlen($prc) ? 1 : 0 ?>);
            $("#prc").fileinput({
                dropZoneEnabled: false,
                showUpload: false,
                showRemove: false,
                required: prcDoc ? false : true,
                maxFileSize: 1024,
                allowedFileExtensions: ["pdf"]
            });

            $("#mbbs_certificate").fileinput({
                dropZoneEnabled: false,
                showUpload: false,
                showRemove: false,
                required: false,
                maxFileSize: 1024,
                allowedFileExtensions: ["pdf"]
            });

            $("#noc_dme").fileinput({
                dropZoneEnabled: false,
                showUpload: false,
                showRemove: false,
                required: false,
                maxFileSize: 1024,
                allowedFileExtensions: ["pdf"]
            });

            $("#seat_allt_letter").fileinput({
                dropZoneEnabled: false,
                showUpload: false,
                showRemove: false,
                required: false,
                maxFileSize: 1024,
                allowedFileExtensions: ["pdf"]
            });
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/acmrnoc/noc/querysubmit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="passport_photo_old" value="<?= $passport_photo ?>" type="hidden" />
            <input name="signature_old" value="<?= $signature ?>" type="hidden" />
            <input name="ug_pg_diploma_old" value="<?= $ug_pg_diploma ?>" type="hidden" />
            <input name="prc_old" value="<?= $prc ?>" type="hidden" />
            <input name="mbbs_certificate_old" value="<?= $mbbs_certificate ?>" type="hidden" />
            <input name="noc_dme_old" value="<?= $noc_dme ?>" type="hidden" />
            <input name="seat_allt_letter_old" value="<?= $seat_allt_letter ?>" type="hidden" />

            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b>Application Form For No Objection Certificate - ACMR<br>
                            ( অনাপত্তি প্ৰমাণপত্ৰৰ বাবে আবেদন পত্ৰ - এচিএমআৰ) <b></h4>
                </div>
                <div class="card-body" style="padding:5px">

                    <?php if ($this->session->flashdata('fail') != null) { ?>
                        <script>
                            $(".frmbtn").show();
                        </script>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('error') != null) { ?>
                        <script>
                            $(".frmbtn").show();
                        </script>
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
                    <?php }
                    if ($status === 'QS') { ?>
                        <fieldset class="border border-danger" style="margin-top:40px; margin-bottom: 2px">
                            <legend class="h5">QUERY DETAILS </legend>
                            <div class="row">
                                <div class="col-md-6" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                    <label>Official Remark: </label><?= (end($dbrow->processing_history)->remarks) ?? '' ?>
                                </div>
                                <div class="col-md-6" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                    <span style="float:right; font-size: 12px">
                                        Query time : <?= isset(end($dbrow->processing_history)->processing_time) ? format_mongo_date(end($dbrow->processing_history)->processing_time) : '' ?>
                                    </span>
                                </div>
                            </div>

                        </fieldset>
                    <?php } //End of if 
                    ?>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details/ আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?= $applicant_name ?>" maxlength="255" disabled />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Male" <?= ($applicant_gender === "Male") ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= ($applicant_gender === "Female") ? 'selected' : '' ?>>Female</option>
                                    <option value="Transgender" <?= ($applicant_gender === "Transgender") ? 'selected' : '' ?>>Transgender</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Father's Name/ পিতাৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?= $father_name ?>" maxlength="255" disabled />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Date of Birth/ জন্মৰ তাৰিখ <span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="dob" id="dob" value="<?= $dob ?>" maxlength="10" autocomplete="off" type="text" disabled />
                                <?= form_error("dob") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Mobile Number/ দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" maxlength="10" disabled />
                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-3">
                                <label>E-Mail/ ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?= $email ?>" maxlength="100" disabled />
                                <?= form_error("email") ?>
                            </div>
                            <div class="col-md-6">
                                <label>PAN No./ পান কাৰ্ড নং </label>
                                <input type="text" class="form-control" name="pan_no" id="pan_no" value="<?= $pan_no ?>" maxlength="10" disabled />
                                <?= form_error("pan_no") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Address/ ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Permanent Address/ স্থায়ী ঠিকনা <span class="text-danger">*</span></label>
                                <textarea class="form-control address" name="permanent_address" id="permanent_address_textarea" disabled><?= $permanent_address ?></textarea>
                                <?= form_error("permanent_address") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Correspondence Address/ যোগাযোগ ঠিকনা </label>
                                <textarea class="form-control address" name="correspondence_address" id="correspondence_address_textarea" disabled><?= $correspondence_address ?></textarea>
                                <?= form_error("correspondence_address") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Other details/ অন্যান্য সবিশেষ </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Permanent Registration No/ স্থায়ী পঞ্জীয়ন নং <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="permanent_reg_no" id="permanent_reg_no" value="<?= $permanent_reg_no ?>" maxlength="255" disabled />
                                <?= form_error("permanent_reg_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Permanent Registration Date/ স্থায়ী পঞ্জীয়নৰ তাৰিখ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control dp" name="permanent_reg_date" id="permanent_reg_date" value="<?= $permanent_reg_date ?>" maxlength="10" disabled />
                                <?= form_error("permanent_reg_date") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Additional Degree Registration No/ অতিৰিক্ত ডিগ্ৰীৰ পঞ্জীয়ন নং</label>
                                <input type="text" class="form-control" name="additional_degree_reg_no" id="additional_degree_reg_no" value="<?= $additional_degree_reg_no ?>" maxlength="255" disabled />
                                <?= form_error("additional_degree_reg_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Additional Degree Registration Date/ অতিৰিক্ত ডিগ্ৰীৰ পঞ্জীয়নৰ তাৰিখ </label>
                                <input type="text" class="form-control dp" name="additional_degree_reg_date" id="additional_degree_reg_date" value="<?= $additional_degree_reg_date ?>" maxlength="10" disabled />
                                <?= form_error("additional_degree_reg_date") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name of the Relocating State Medical Council/ স্থানান্তৰিত ৰাজ্যিক চিকিৎসা পৰিষদৰ নাম <span class="text-danger">*</span></label>
                                <select name="registering_smc" id="registering_smc" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Andhra Pradesh Medical Council" <?= ($registering_smc === "Andhra Pradesh Medical Council") ? 'selected' : '' ?>>Andhra Pradesh Medical Council</option>
                                    <option value="Arunachal Pradesh Medical Council" <?= ($registering_smc === "Arunachal Pradesh Medical Council") ? 'selected' : '' ?>>Arunachal Pradesh Medical Council</option>
                                    <option value="Assam Medical Council" <?= ($registering_smc === "Assam Medical Council") ? 'selected' : '' ?>>Assam Medical Council</option>
                                    <option value="Bihar Medical Council" <?= ($registering_smc === "Bihar Medical Council") ? 'selected' : '' ?>>Bihar Medical Council</option>
                                    <option value="Chhattisgarh Medical Council" <?= ($registering_smc === "Chhattisgarh Medical Council") ? 'selected' : '' ?>>Chhattisgarh Medical Council</option>
                                    <option value="Delhi Medical Council" <?= ($registering_smc === "Delhi Medical Council") ? 'selected' : '' ?>>Delhi Medical Council</option>
                                    <option value="Goa Medical Council" <?= ($registering_smc === "Goa Medical Council") ? 'selected' : '' ?>>Goa Medical Council</option>
                                    <option value="Gujarat Medical Council" <?= ($registering_smc === "Gujarat Medical Council") ? 'selected' : '' ?>>Gujarat Medical Council</option>
                                    <option value="Haryana Medical Council" <?= ($registering_smc === "Haryana Medical Council") ? 'selected' : '' ?>>Haryana Medical Council</option>
                                    <option value="Himachal Pradesh Medical Council" <?= ($registering_smc === "Himachal Pradesh Medical Council") ? 'selected' : '' ?>>Himachal Pradesh Medical Council</option>
                                    <option value="Jammu & Kashmir Medical Council" <?= ($registering_smc === "Jammu & Kashmir Medical Council") ? 'selected' : '' ?>>Jammu & Kashmir Medical Council</option>
                                    <option value="Jharkhand Medical Council" <?= ($registering_smc === "Jharkhand Medical Council") ? 'selected' : '' ?>>Jharkhand Medical Council</option>
                                    <option value="Karnataka Medical Council" <?= ($registering_smc === "Karnataka Medical Council") ? 'selected' : '' ?>>Karnataka Medical Council</option>
                                    <option value="Madhya Pradesh Medical Council" <?= ($registering_smc === "Madhya Pradesh Medical Council") ? 'selected' : '' ?>>Madhya Pradesh Medical Council</option>
                                    <option value="Maharashtra Medical Council" <?= ($registering_smc === "Maharashtra Medical Council") ? 'selected' : '' ?>>Maharashtra Medical Council</option>
                                    <option value="Manipur Medical Council" <?= ($registering_smc === "Manipur Medical Council") ? 'selected' : '' ?>>Manipur Medical Council</option>
                                    <option value="Mizoram Medical Council" <?= ($registering_smc === "Mizoram Medical Council") ? 'selected' : '' ?>>Mizoram Medical Council</option>
                                    <option value="Nagaland Medical Council" <?= ($registering_smc === "Nagaland Medical Council") ? 'selected' : '' ?>>Nagaland Medical Council</option>
                                    <option value="Orissa Council of Medical Registration" <?= ($registering_smc === "Orissa Council of Medical Registration") ? 'selected' : '' ?>>Orissa Council of Medical Registration</option>
                                    <option value="Punjab Medical Council" <?= ($registering_smc === "Punjab Medical Council") ? 'selected' : '' ?>>Punjab Medical Council</option>
                                    <option value="Rajasthan Medical Council" <?= ($registering_smc === "Rajasthan Medical Council") ? 'selected' : '' ?>>Rajasthan Medical Council</option>
                                    <option value="Sikkim Medical Council" <?= ($registering_smc === "Sikkim Medical Council") ? 'selected' : '' ?>>Sikkim Medical Council</option>
                                    <option value="Tamil Nadu Medical Council" <?= ($registering_smc === "Tamil Nadu Medical Council") ? 'selected' : '' ?>>Tamil Nadu Medical Council</option>
                                    <option value="Telangana State Medical Council" <?= ($registering_smc === "Telangana State Medical Council") ? 'selected' : '' ?>>Telangana State Medical Council</option>
                                    <option value="Travancore Cochin Medical Council, Trivandrum" <?= ($registering_smc === "Travancore Cochin Medical Council, Trivandrum") ? 'selected' : '' ?>>Travancore Cochin Medical Council, Trivandrum</option>
                                    <option value="Tripura State Medical Council" <?= ($registering_smc === "Tripura State Medical Council") ? 'selected' : '' ?>>Tripura State Medical Council</option>
                                    <option value="Uttarakhand Medical Council" <?= ($registering_smc === "Uttarakhand Medical Council") ? 'selected' : '' ?>>Uttarakhand Medical Council</option>
                                    <option value="Uttar Pradesh Medical Council" <?= ($registering_smc === "Uttar Pradesh Medical Council") ? 'selected' : '' ?>>Uttar Pradesh Medical Council</option>
                                    <option value="West Bengal Medical Council" <?= ($registering_smc === "West Bengal Medical Council") ? 'selected' : '' ?>>West Bengal Medical Council</option>
                                </select>
                                <?= form_error("registering_smc") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Relocation Reason/ স্থানান্তৰৰ কাৰণ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="relocating_reason" id="relocating_reason" value="<?= $relocating_reason ?>" maxlength="6" disabled />
                                <?= form_error("relocating_reason") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Working Place Address/ কৰ্মস্থলীৰ ঠিকনা </label>
                                <textarea class="form-control address" name="working_place_add" id="working_place_add" disabled><?= $working_place_add ?></textarea>
                                <?= form_error("working_place_add") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>১. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য।</li>
                            <li>2. Applicant's photo should be in JPEG format.</li>
                            <li>২. আবেদনকাৰীৰ ফটো jpeg formatত হ’ব লাগিব।</li>
                        </ul>
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
                                            <th width="30%">File/Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Passport size photograph <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="passport_photo_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Photograph" <?= ($passport_photo_type === 'Photograph') ? 'selected' : '' ?>>Passport size photograph</option>
                                                </select>
                                                <?= form_error("passport_photo_type") ?>
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
                                            <td>Applicant Signature <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="signature_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Applicant Signature" <?= ($signature_type === 'Applicant Signature') ? 'selected' : '' ?>>Applicant Signature</option>
                                                </select>
                                                <?= form_error("signature_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="signature" name="signature" type="file" />
                                                </div>
                                                <?php if (strlen($signature)) { ?>
                                                    <a href="<?= base_url($signature) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Certificate of UG/PG/ Diploma<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="ug_pg_diploma_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Diploma" <?= ($ug_pg_diploma_type === 'Diploma') ? 'selected' : '' ?>>Certificate of UG/PG/ Diploma</option>
                                                </select>
                                                <?= form_error("ug_pg_diploma_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="ug_pg_diploma" name="ug_pg_diploma" type="file" />
                                                </div>
                                                <?php if (strlen($ug_pg_diploma)) { ?>
                                                    <a href="<?= base_url($ug_pg_diploma) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Permanent Registration certificate of Assam Medical Council<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="prc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="PRC" <?= ($prc_type === 'PRC') ? 'selected' : '' ?>>Permanent Registration certificate of Assam Medical Council</option>

                                                </select>
                                                <?= form_error("prc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="prc" name="prc" type="file" />
                                                </div>
                                                <?php if (strlen($prc)) { ?>
                                                    <a href="<?= base_url($prc) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Rural completion certificate (MBBS) if bond signed with Govt.</td>
                                            <td>
                                                <select name="mbbs_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Rural completion certificate (MBBS)" <?= ($mbbs_certificate_type === 'Rural completion certificate (MBBS)') ? 'selected' : '' ?>>Rural completion certificate (MBBS) if bond signed with Govt</option>
                                                </select>
                                                <?= form_error("mbbs_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="mbbs_certificate" name="mbbs_certificate" type="file" />
                                                </div>
                                                <?php if (strlen($mbbs_certificate)) { ?>
                                                    <a href="<?= base_url($mbbs_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>NOC from Directorate of Medical Education (only for PG Holders under
                                                Bond)</td>
                                            <td>
                                                <select name="noc_dme_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="NOC from Directorate of Medical Education" <?= ($noc_dme_type === 'NOC from Directorate of Medical Education') ? 'selected' : '' ?>>NOC from Directorate of Medical Education</option>
                                                </select>
                                                <?= form_error("noc_dme_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="noc_dme" name="noc_dme" type="file" />
                                                </div>
                                                <?php if (strlen($noc_dme)) { ?>
                                                    <a href="<?= base_url($noc_dme) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Provisional Seat Allotment letter for All India Quota doctors</td>
                                            <td>
                                                <select name="seat_allt_letter_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Provisional Seat Allotment letter" <?= ($seat_allt_letter_type === 'Provisional Seat Allotment letter') ? 'selected' : '' ?>>Provisional Seat Allotment Letter</option>
                                                </select>
                                                <?= form_error("seat_allt_letter_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="seat_allt_letter" name="seat_allt_letter" type="file" />
                                                </div>
                                                <?php if (strlen($seat_allt_letter)) { ?>
                                                    <a href="<?= base_url($seat_allt_letter) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-danger table-responsive" style="overflow:hidden">
                        <legend class="h5">Processing history</legend>
                        <table class="table table-bordered bg-white mt-0">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Date &AMP; time</th>
                                    <th>Action taken</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($dbrow->processing_history)) {
                                    foreach ($dbrow->processing_history as $key => $rows) {
                                        $query_attachment = $rows->query_attachment ?? ''; ?>
                                        <tr>
                                            <td><?= sprintf("%02d", $key + 1) ?></td>
                                            <td><?= date('d/m/Y h:i a', strtotime($this->mongo_db->getDateTime($rows->processing_time))) ?></td>
                                            <td><?= $rows->action_taken ?></td>
                                            <td><?= $rows->remarks ?></td>
                                        </tr>
                                <?php } //End of foreach()
                                } //End of if else 
                                ?>
                            </tbody>
                        </table>
                    </fieldset>

                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <!-- <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button> -->
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Save & Next
                    </button>
                    <!-- <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button> -->
                </div>
                <!--End of .card-footer-->
            </div>
            <!--End of .card-->
        </form>
    </div>
    <!--End of .container-->
</main>