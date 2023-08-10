<?php
$currentYear = date('Y');
//$apiServer = $this->config->item('edistrict_base_url');
$apiServer = "https://localhost/wptbcapis/"; //For testing
if ($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $pageTitle = $dbrow->service_data->service_name;
    $pageTitleId = $dbrow->service_data->service_id;
    $status = $dbrow->service_data->appl_status;

    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $mobile = $dbrow->form_data->mobile;
    $email = !empty($dbrow->form_data->email) ? $dbrow->form_data->email : 'NA';

    $p_comp_permanent_address = $dbrow->form_data->p_comp_permanent_address;
    $p_state = $dbrow->form_data->p_state;
    $p_district = $dbrow->form_data->p_district;
    $p_police_st = $dbrow->form_data->p_police_st;
    $p_post_office = $dbrow->form_data->p_post_office;
    $p_pin_code = $dbrow->form_data->p_pin_code;
    $same_as_p_address = $dbrow->form_data->same_as_p_address;
    $c_comp_permanent_address = $dbrow->form_data->c_comp_permanent_address;
    $c_state = $dbrow->form_data->c_state;
    $c_district = $dbrow->form_data->c_district;
    $c_police_st = $dbrow->form_data->c_police_st;
    $c_post_office = $dbrow->form_data->c_post_office;
    $c_pin_code = $dbrow->form_data->c_pin_code;

    $ahsec_reg_session = $dbrow->form_data->ahsec_reg_session;
    $ahsec_reg_no = $dbrow->form_data->ahsec_reg_no;
    $ahsec_admit_roll = $dbrow->form_data->ahsec_admit_roll;
    $ahsec_admit_no = $dbrow->form_data->ahsec_admit_no;
    $institution_name = $dbrow->form_data->institution_name;
    $ahsec_yearofappearing = $dbrow->form_data->ahsec_yearofappearing;
    $results = $dbrow->form_data->results;

    $candidate_name_checkbox = $dbrow->form_data->candidate_name_checkbox;
    $father_name_checkbox = $dbrow->form_data->father_name_checkbox;
    $mother_name_checkbox = $dbrow->form_data->mother_name_checkbox;

    $incorrect_candidate_name = $dbrow->form_data->incorrect_candidate_name;
    $incorrect_father_name = $dbrow->form_data->incorrect_father_name;
    $incorrect_mother_name = $dbrow->form_data->incorrect_mother_name;

    $correct_candidate_name = !empty($dbrow->form_data->correct_candidate_name) ? $dbrow->form_data->correct_candidate_name : 'Correction Not Required';
    $correct_father_name = !empty($dbrow->form_data->correct_father_name) ? $dbrow->form_data->correct_father_name : 'Correction Not Required';
    $correct_mother_name = !empty($dbrow->form_data->correct_mother_name) ? $dbrow->form_data->correct_mother_name : 'Correction Not Required';
    $delivery_mode = $dbrow->form_data->delivery_mode;

    //ENCLOSURES DATA ---START

    $passport_photo_type_frm = set_value("passport_photo_type");
    $signature_type_frm = set_value("signature_type");
    $registration_card_type_frm = set_value("registration_card_type");
    $admit_card_type_frm = set_value("admit_card_type");
    $affidavit_type_frm = set_value("affidavit_type");
    $pass_certificate_type_frm = set_value("pass_certificate_type");
    $marksheet_type_frm = set_value("marksheet_type");
    $other_doc_type_frm = set_value("other_doc_type");
    $soft_copy_type_frm = set_value("soft_copy_type");

    $uploadedFiles = $this->session->flashdata('uploaded_files');
    $passport_photo_frm = $uploadedFiles['passport_photo_old'] ?? null;
    $signature_frm = $uploadedFiles['signature_old'] ?? null;
    $registration_card_frm = $uploadedFiles['registration_card_old'] ?? null;
    $admit_card_frm = $uploadedFiles['admit_card_old'] ?? null;
    $affidavit_frm = $uploadedFiles['affidavit_old'] ?? null;
    $pass_certificate_frm = $uploadedFiles['pass_certificate_old'] ?? null;
    $marksheet_frm = $uploadedFiles['marksheet_old'] ?? null;
    $other_doc_frm = $uploadedFiles['other_doc_old'] ?? null;
    $soft_copy_frm = $uploadedFiles['soft_copy_old'] ?? null;

    $passport_photo_type_db = $dbrow->form_data->passport_photo_type ?? null;
    $signature_type_db = $dbrow->form_data->signature_type ?? null;
    $registration_card_type_db = $dbrow->form_data->registration_card_type ?? null;
    $admit_card_type_db = $dbrow->form_data->admit_card_type ?? null;
    $affidavit_type_db = $dbrow->form_data->affidavit_type ?? null;
    $pass_certificate_type_db = $dbrow->form_data->pass_certificate_type ?? null;
    $marksheet_type_db = $dbrow->form_data->marksheet_type ?? null;
    $other_doc_type_db = $dbrow->form_data->other_doc_type ?? null;
    $soft_copy_type_db = $dbrow->form_data->soft_copy_type ?? null;

    $passport_photo_db = $dbrow->form_data->passport_photo ?? null;
    $signature_db = $dbrow->form_data->signature ?? null;
    $registration_card_db = $dbrow->form_data->registration_card ?? null;
    $admit_card_db = $dbrow->form_data->admit_card ?? null;
    $affidavit_db = $dbrow->form_data->affidavit ?? null;
    $pass_certificate_db = $dbrow->form_data->pass_certificate ?? null;
    $marksheet_db = $dbrow->form_data->marksheet ?? null;
    $other_doc_db = $dbrow->form_data->other_doc ?? null;
    $soft_copy_db = $dbrow->form_data->soft_copy ?? null;

    $passport_photo_type = strlen($passport_photo_type_frm) ? $passport_photo_type_frm : $passport_photo_type_db;
    $signature_type = strlen($signature_type_frm) ? $signature_type_frm : $signature_type_db;
    $registration_card_type = strlen($registration_card_type_frm) ? $registration_card_type_frm : $registration_card_type_db;
    $admit_card_type = strlen($admit_card_type_frm) ? $admit_card_type_frm : $admit_card_type_db;
    $affidavit_type = strlen($affidavit_type_frm) ? $affidavit_type_frm : $affidavit_type_db;
    $pass_certificate_type = strlen($pass_certificate_type_frm) ? $pass_certificate_type_frm : $pass_certificate_type_db;
    $marksheet_type = strlen($marksheet_type_frm) ? $marksheet_type_frm : $marksheet_type_db;
    $other_doc_type = strlen($other_doc_type_frm) ? $other_doc_type_frm : $other_doc_type_db;
    $soft_copy_type = strlen($soft_copy_type_frm) ? $soft_copy_type_frm : $soft_copy_type_db;

    $passport_photo = strlen($passport_photo_frm) ? $passport_photo_frm : $passport_photo_db;
    $signature = strlen($signature_frm) ? $signature_frm : $signature_db;
    $registration_card = strlen($registration_card_frm) ? $registration_card_frm : $registration_card_db;
    $admit_card = strlen($admit_card_frm) ? $admit_card_frm : $admit_card_db;
    $affidavit = strlen($affidavit_frm) ? $affidavit_frm : $affidavit_db;
    $pass_certificate = strlen($pass_certificate_frm) ? $pass_certificate_frm : $pass_certificate_db;
    $marksheet = strlen($marksheet_frm) ? $marksheet_frm : $marksheet_db;
    $other_doc = strlen($other_doc_frm) ? $other_doc_frm : $other_doc_db;
    $soft_copy = strlen($soft_copy_frm) ? $soft_copy_frm : $soft_copy_db;

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

<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/iservices/js/custom/digilocker.js') ?>"></script>
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

        $.getJSON("<?= $apiServer ?>district_list.php", function(data) {
            let selectOption = '';
            $.each(data.ListOfDistricts, function(key, value) {
                selectOption += '<option value="' + value.DistrictName + '">' + value.DistrictName + '</option>';
            });
            $('.dists').append(selectOption);
        });

        $('#checker').change(function() {

            if ($(this).is(':checked')) {
                $('#c_comp_permanent_address').val($('#p_comp_permanent_address').val());
                $('#c_state').val($('#p_state').val());
                $('#c_district').val($('#p_district').val());
                $('#c_police_st').val($('#p_police_st').val());
                $('#c_post_office').val($('#p_post_office').val());
                $('#c_pin_code').val($('#p_pin_code').val());

            } else {
                $('#c_comp_permanent_address').val("");
                $('#c_state').prop('selectedIndex', 0);
                $('#c_district').prop('selectedIndex', 0);
                $('#c_police_st').val("");
                $('#c_post_office').val("");
                $('#c_pin_code').val("");
            }
        });


        let candidate_name_checkbox = '<?= $candidate_name_checkbox ?>';
        let father_name_checkbox = '<?= $father_name_checkbox ?>';
        let mother_name_checkbox = '<?= $mother_name_checkbox ?>';

        let same_as_p_address = '<?= $same_as_p_address ?>';
        if (same_as_p_address == "1") {
            $('#checker').prop('checked', true);
        } else {
            $('#checker').prop('checked', false);
        }
        if (candidate_name_checkbox == "1" && ($('#incorrect_candidate_name').val() != '' || $('#correct_candidate_name').val() != '')) {
            $('#candidate_name_checkbox').prop('checked', true);
            $('#incorrect_candidate_name').prop('disabled', true);
            $('#correct_candidate_name').prop('disabled', true);
        } else {
            $('#candidate_name_checkbox').prop('checked', false);
            $('#incorrect_candidate_name').prop('disabled', true).val('');
            $('#correct_candidate_name').prop('disabled', true).val('');
        }
        if (father_name_checkbox == "2" && ($('#incorrect_father_name').val() != '' || $('#correct_father_name').val() != '')) {
            $("#father_name_checkbox").prop('checked', true);
            $('#incorrect_father_name').prop('disabled', true);
            $('#correct_father_name').prop('disabled', true);
        } else {
            $('#father_name_checkbox').prop('checked', false);
            $('#incorrect_father_name').prop('disabled', true).val('');
            $('#correct_father_name').prop('disabled', true).val('');
        }
        if (mother_name_checkbox == "3" && ($('#incorrect_mother_name').val() != '' || $('#correct_mother_name').val() != '')) {
            $("#mother_name_checkbox").prop('checked', true);
            $('#incorrect_mother_name').prop('disabled', true);
            $('#correct_mother_name').prop('disabled', true);
        } else {
            $('#mother_name_checkbox').prop('checked', false);
            $('#incorrect_mother_name').prop('disabled', true).val('');
            $('#correct_mother_name').prop('disabled', true).val('');
        }

        // Checkbox click event handlers
        $('#candidate_name_checkbox').click(function() {
            if ($(this).is(':checked')) {
                $('#incorrect_candidate_name').prop('disabled', true);
                $('#correct_candidate_name').prop('disabled', true);
            } else {
                $('#incorrect_candidate_name').prop('disabled', true).val('');
                $('#correct_candidate_name').prop('disabled', true).val('');
            }
        });

        $('#father_name_checkbox').click(function() {
            if ($(this).is(':checked')) {
                $('#incorrect_father_name').prop('disabled', true);
                $('#correct_father_name').prop('disabled', true);
            } else {
                $('#incorrect_father_name').prop('disabled', true).val('');
                $('#correct_father_name').prop('disabled', true).val('');
            }
        });

        $('#mother_name_checkbox').click(function() {
            if ($(this).is(':checked')) {
                $('#incorrect_mother_name').prop('disabled', true);
                $('#correct_mother_name').prop('disabled', true);
            } else {
                $('#incorrect_mother_name').prop('disabled', true).val('');
                $('#correct_mother_name').prop('disabled', true).val('');
            }
        });

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

        //var pass_certificateFile = parseInt(<?= strlen($pass_certificate) ? 1 : 0 ?>);
        $("#pass_certificate").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: pass_certificateFile ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        //var idProof = parseInt(<?= strlen($admit_card) ? 1 : 0 ?>);
        $("#admit_card").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: idProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        //var addressProof = parseInt(<?= strlen($registration_card) ? 1 : 0 ?>);
        $("#registration_card").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            // required: addressProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#marksheet").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#affidavit").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/ahsec_correction/ahseccor/querysubmit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="passport_photo_old" value="<?= $passport_photo ?>" type="hidden" />
            <input name="signature_old" value="<?= $signature ?>" type="hidden" />
            <input name="affidavit_old" value="<?= $affidavit ?>" type="hidden" />
            <input name="pass_certificate_old" value="<?= $pass_certificate ?>" type="hidden" />
            <input name="marksheet_old" value="<?= $marksheet ?>" type="hidden" />
            <input name="admit_card_old" value="<?= $admit_card ?>" type="hidden" />
            <input name="registration_card_old" value="<?= $registration_card ?>" type="hidden" />
            <input name="other_doc_old" value="<?= $other_doc ?>" type="hidden" />
            <input name="soft_copy_old" value="<?= $soft_copy ?>" type="hidden" />

            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b><?= $pageTitle ?><br>
                            <?php switch ($pageTitleId) {
                                case "AHSECCRC":
                                    echo '( পঞ্জীয়ন কাৰ্ডত সংশোধনৰ বাবে আবেদন )';
                                    break;
                                case "AHSECCADM":
                                    echo '( এডমিট কাৰ্ডত সংশোধনৰ বাবে আবেদন )';
                                    break;
                                case "AHSECCMRK":
                                    echo '( মাৰ্কশ্বীটত সংশোধনৰ বাবে আবেদন )';
                                    break;
                                case "AHSECCPC":
                                    echo '( উত্তীৰ্ণ প্ৰমাণপত্ৰত সংশোধনৰ বাবে আবেদন )';
                                    break;
                            }
                            ?><b></h4>
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
                        <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
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
                                <label>Father&apos;s Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?= $father_name ?>" maxlength="255" disabled />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mother&apos;s Name/ মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name" value="<?= $mother_name ?>" maxlength="255" disabled />
                                <?= form_error("mother_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" readonly maxlength="10" disabled />
                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="email" value="<?= $email ?>" maxlength="100" disabled />
                                <?= form_error("email") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address/ স্থায়ী ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Complete Permanent Address/ সম্পূৰ্ণ স্থায়ী ঠিকনা <span class="text-danger">*</span></label>
                                <textarea id="p_comp_permanent_address" class="form-control" name="p_comp_permanent_address" rowspan="4" disabled><?= $p_comp_permanent_address ?></textarea>
                                <?= form_error("p_comp_permanent_address") ?>
                            </div>
                            <div class="col-md-6">
                                <label>State/ ৰাজ্য <span class="text-danger">*</span> </label>
                                <select id="p_state" name="p_state" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Assam" autocomplete="off" selected="selected">Assam</option>
                                </select>
                                <?= form_error("p_state") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Select District/ জিলা নিৰ্বাচন কৰক <span class="text-danger">*</span> </label>
                                <select name="p_district" id="p_district" class="form-control dists" disabled>
                                    <option value="<?= strlen($p_district) ? $p_district : '' ?>"><?= strlen($p_district) ? $p_district : 'Select' ?></option>
                                </select>
                                <?= form_error("p_district") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Pin Code/ পিনক'ড (e.g 78xxxx) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="p_pin_code" id="p_pin_code" value="<?= $p_pin_code ?>" maxlength="6" disabled />
                                <?= form_error("p_pin_code") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Postal Address / ডাক ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <input type="checkbox" name="checker" id="checker" value="1" disabled /><b>&nbsp;&nbsp;Same as Permanent Address</b>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Complete Address/ সম্পূৰ্ণ ঠিকনা <span class="text-danger">*</span></label>
                                <textarea id="c_comp_permanent_address" class="form-control" name="c_comp_permanent_address" rowspan="4" disabled><?= $c_comp_permanent_address ?></textarea>
                                <?= form_error("c_comp_permanent_address") ?>
                            </div>
                            <div class="col-md-6">
                                <label>State/ ৰাজ্য <span class="text-danger">*</span> </label>
                                <select id="c_state" name="c_state" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Assam" autocomplete="off" selected="selected">Assam</option>
                                </select>
                                <?= form_error("c_state") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Select District/ জিলা নিৰ্বাচন কৰক <span class="text-danger">*</span> </label>
                                <select name="c_district" id="c_district" class="form-control dists" disabled>
                                    <option value="<?= strlen($c_district) ? $c_district : '' ?>"><?= strlen($c_district) ? $c_district : 'Select' ?></option>
                                </select>
                                <?= form_error("c_district") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Pin Code/ পিনক'ড (e.g 78xxxx) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="c_pin_code" id="c_pin_code" value="<?= $c_pin_code ?>" maxlength="6" disabled />
                                <?= form_error("c_pin_code") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Academic Details / শৈক্ষিক বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>AHSEC Registration Session/ AHSEC পঞ্জীয়ন বৰ্ষ <span class="text-danger">*</span> </label>
                                <select name="ahsec_reg_session" id="ahsec_reg_session" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <?php foreach ($sessions as $session) { ?>
                                        <option value="<?php echo $session ?>" <?= ($ahsec_reg_session === $session) ? 'selected' : '' ?>><?php echo $session ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("ahsec_reg_session") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Valid AHSEC Registration Number / বৈধ AHSEC পঞ্জীয়ন নম্বৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ahsec_reg_no" id="ahsec_reg_no" value="<?= $ahsec_reg_no ?>" maxlength="255" disabled />
                                <?= form_error("ahsec_reg_no") ?>
                            </div>
                        </div>
                        <?php if ($pageTitleId != "AHSECCRC" || ($pageTitleId == "AHSECCRC" && !empty($ahsec_admit_roll) && !empty($ahsec_admit_no))) { ?>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>Valid H.S Final Examination Roll/ বৈধ HS চূড়ান্ত বৰ্ষৰ পৰীক্ষাৰ ৰোল <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="ahsec_admit_roll" id="ahsec_admit_roll" value="<?= $ahsec_admit_roll ?>" maxlength="255" disabled />
                                    <?= form_error("ahsec_admit_roll") ?>
                                </div>
                                <div class="col-md-6">
                                    <label>Valid H.S Final Examination Number/ বৈধ HS চূড়ান্ত বৰ্ষৰ পৰীক্ষাৰ নম্বৰ <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="ahsec_admit_no" id="ahsec_admit_no" value="<?= $ahsec_admit_no ?>" maxlength="255" disabled />
                                    <?= form_error("ahsec_admit_no") ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name of the Institution / প্ৰতিষ্ঠানৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="institution_name" id="institution_name" value="<?= $institution_name ?>" maxlength="255" disabled />
                                <?= form_error("institution_name") ?>
                            </div>
                            <?php if ($pageTitleId != "AHSECCRC-this-cond-toberemoved") { ?>
                                <div class="col-md-6">
                                    <label>
                                        <?php if ($pageTitleId == "AHSECCPC") { ?>Year of Passing in HS Final Examination/ HS চূড়ান্ত পৰীক্ষাত উত্তীৰ্ণ হোৱাৰ বছৰ <?php } else { ?>Year of Appearing in HS Final Examination/ HS চূড়ান্ত পৰীক্ষাত অৱতীৰ্ণ হোৱাৰ বছৰ <?php } ?>
                                    </label>
                                    <span class="text-danger">*</span> </label>
                                    <select name="ahsec_yearofappearing" id="ahsec_yearofappearing" class="form-control" disabled>
                                        <?php if ($pageTitleId == "AHSECCRC") { ?>
                                            <option value="<?php echo 'Not yet appeared'; ?>" <?= ($ahsec_yearofappearing === "Not yet appeared") ? 'selected' : '' ?>>Not yet appeared</option>
                                        <?php } ?>
                                        <option value="">Please Select</option>
                                        <?php foreach ($years as $year) { ?>
                                            <option value="<?php echo $year ?>" <?= ($ahsec_yearofappearing == $year) ? 'selected' : '' ?>><?php echo $year ?></option>
                                        <?php } ?>
                                    </select>
                                    <?= form_error("ahsec_yearofappearing") ?>
                                </div>
                            <?php } ?>
                        </div>
                        <?php if ($pageTitleId == "AHSECCPC") { ?>
                            <div class="col-md-6">
                                <label>Result/ ফলাফল <span class="text-danger">*</span> </label>
                                <select name="results" id="results" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="First Division" <?= ($results === "First Division") ? 'selected' : '' ?>>First Division</option>
                                    <option value="Second Division" <?= ($results === "Second Division") ? 'selected' : '' ?>>Second Division</option>
                                    <option value="Third Division" <?= ($results === "Third Division") ? 'selected' : '' ?>>Third Division</option>
                                </select>
                                <?= form_error("results") ?>
                            </div>
                        <?php } ?>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Name Correction/ নাম সংশোধন </legend>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <input type="checkbox" name="candidate_name_checkbox" value="1" id="candidate_name_checkbox" disabled /><b>&nbsp;&nbsp;Candidate's Name/ প্ৰাৰ্থীৰ নাম</b>
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="Record in my document as" class="form-control" name="incorrect_candidate_name" id="incorrect_candidate_name" value="<?= $incorrect_candidate_name ?>" maxlength="255" disabled />
                                <?= form_error("incorrect_candidate_name") ?>
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="To be corrected as" class="form-control" name="correct_candidate_name" id="correct_candidate_name" value="<?= $correct_candidate_name ?>" maxlength="255" disabled />
                                <?= form_error("correct_candidate_name") ?>
                            </div>

                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <input type="checkbox" name="father_name_checkbox" value="2" id="father_name_checkbox" disabled /><b>&nbsp;&nbsp;Father's Name/ পিতৃৰ নাম</b>
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="Record in my document as" class="form-control" name="incorrect_father_name" id="incorrect_father_name" value="<?= $incorrect_father_name ?>" maxlength="255" disabled />
                                <?= form_error("incorrect_father_name") ?>
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="To be corrected as" class="form-control" name="correct_father_name" id="correct_father_name" value="<?= $correct_father_name ?>" maxlength="255" disabled />
                                <?= form_error("correct_father_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <input type="checkbox" name="mother_name_checkbox" value="3" id="mother_name_checkbox" disabled /><b>&nbsp;&nbsp;Mother's Name/ মাতৃৰ নাম</b>
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="Record in my document as" class="form-control" name="incorrect_mother_name" id="incorrect_mother_name" value="<?= $incorrect_mother_name ?>" maxlength="255" disabled />
                                <?= form_error("incorrect_mother_name") ?>
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="To be corrected as" class="form-control" name="correct_mother_name" id="correct_mother_name" value="<?= $correct_mother_name ?>" maxlength="255" disabled />
                                <?= form_error("correct_mother_name") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Delivery Preference/ বিতৰণৰ অগ্ৰাধিকাৰ </legend>
                        <p>How would you want to receive your corrected documents ?<br> আপুনি আপোনাৰ শুদ্ধ নথিপত্ৰ কেনেকৈ লাভ কৰিব বিচাৰিব ?</p>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <input type="radio" name="delivery_mode" value="FROM AHSEC COUNTER" id="delivery_mode_1" onclick="showMessage(this.value)" <?= ($delivery_mode === "FROM AHSEC COUNTER") ? 'checked' : '' ?> disabled /><b>&nbsp;&nbsp;FROM AHSEC COUNTER (AHSEC কাউণ্টাৰৰ পৰা)</b>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <input type="radio" name="delivery_mode" value="IN MY POSTAL ADDRESS" id="delivery_mode_2" onclick="showMessage(this.value)" <?= ($delivery_mode === "IN MY POSTAL ADDRESS") ? 'checked' : '' ?> disabled /><b>&nbsp;&nbsp;IN MY POSTAL ADDRESS (মোৰ ডাক ঠিকনাত)</b>
                            </div>
                        </div>
                    </fieldset>
                    <p id="message" class="message"></p>

                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>১. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য।</li>
                        </ul>
                    </fieldset>
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
                                            <td>Passport size photograph [Only .JPEG or .JPG File Allowed, Max file size: 1 MB]<span class="text-danger">*</span></td>
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
                                            <td>Applicant Signature [Only .JPEG or .JPG File Allowed, Max file size: 1 MB]<span class="text-danger">*</span></td>
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
                                            <td>HS Registration Card<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="registration_card_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Registration Card" <?= ($registration_card_type === 'Registration Card') ? 'selected' : '' ?>>Registration Card</option>
                                                </select>
                                                <?= form_error("registration_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="registration_card" name="registration_card" type="file" />

                                                </div>
                                                <?php if (strlen($registration_card)) { ?>
                                                    <a href="<?= base_url($registration_card) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="registration_card" type="hidden" name="registration_card_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('registration_card'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php if ($pageTitleId == "AHSECCRC") { ?>HSLC/ 10th Admit Card<?php } ?><?php if ($pageTitleId == "AHSECCADM" || $pageTitleId == "AHSECCMRK" || $pageTitleId == "AHSECCPC") { ?>HS Admit Card<?php } ?><span class="text-danger">*</span>

                                            </td>
                                            <td>
                                                <select name="admit_card_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Admit Card" <?= ($admit_card_type === 'Admit Card') ? 'selected' : '' ?>>Admit Card</option>
                                                </select>
                                                <?= form_error("admit_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="admit_card" name="admit_card" type="file" />
                                                </div>
                                                <?php if (strlen($admit_card)) { ?>
                                                    <a href="<?= base_url($admit_card) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="admit_card" type="hidden" name="admit_card_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('admit_card'); ?>
                                            </td>
                                        </tr>
                                        <?php if ($pageTitleId == "AHSECCRC") { ?>
                                            <tr>
                                                <td>HS Admit Card</td>
                                                <td>
                                                    <select name="other_doc_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="HS admit card" <?= ($other_doc_type === 'HS admit card') ? 'selected' : '' ?>>HS admit card</option>
                                                    </select>
                                                    <?= form_error("other_doc_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="other_doc" name="other_doc" type="file" />
                                                    </div>
                                                    <?php if (strlen($other_doc)) { ?>
                                                        <a href="<?= base_url($other_doc) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="other_doc" type="hidden" name="other_doc_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('other_doc'); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Court Affidavit [Required in case applying for registration correction after 10 years of AHSEC registration]</td>
                                                <td>
                                                    <select name="affidavit_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Affidavit" <?= ($affidavit_type === 'Affidavit') ? 'selected' : '' ?>>Affidavit</option>

                                                    </select>
                                                    <?= form_error("affidavit_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="affidavit" name="affidavit" type="file" />
                                                    </div>
                                                    <?php if (strlen($affidavit)) { ?>
                                                        <a href="<?= base_url($affidavit) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="affidavit" type="hidden" name="affidavit_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('affidavit'); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if (($pageTitleId == "AHSECCMRK") || ($pageTitleId == "AHSECCPC")) { ?>
                                            <tr>
                                                <td>HS Marksheet <span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="marksheet_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Marksheet" <?= ($marksheet_type === 'Marksheet') ? 'selected' : '' ?>>Marksheet</option>
                                                    </select>
                                                    <?= form_error("marksheet_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="marksheet" name="marksheet" type="file" />
                                                    </div>
                                                    <?php if (strlen($marksheet)) { ?>
                                                        <a href="<?= base_url($marksheet) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="marksheet" type="hidden" name="marksheet_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('marksheet'); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if ($pageTitleId == "AHSECCPC") { ?>
                                            <tr>
                                                <td>HS Pass Certificate<span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="pass_certificate_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Pass Certificate" <?= ($pass_certificate_type === 'Pass Certificate') ? 'selected' : '' ?>>Pass Certificate</option>
                                                    </select>
                                                    <?= form_error("pass_certificate_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="pass_certificate" name="pass_certificate" type="file" />
                                                    </div>
                                                    <?php if (strlen($pass_certificate)) { ?>
                                                        <a href="<?= base_url($pass_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                    <input class="pass_certificate" type="hidden" name="pass_certificate_temp">
                                                    <?= $this->digilocker_api->digilocker_fetch_btn('pass_certificate'); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        <?php if ($this->slug == 'userrr') { ?>
                                            <tr>
                                                <td>Soft copy of the applicant form<span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="soft_copy_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Soft copy of the applicant form" <?= ($soft_copy_type === 'Soft copy of the applicant form') ? 'selected' : '' ?>>Soft copy of the applicant form</option>
                                                    </select>
                                                    <?= form_error("soft_copy_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="soft_copy" name="soft_copy" type="file" />
                                                    </div>
                                                    <?php if (strlen($soft_copy)) { ?>
                                                        <a href="<?= base_url($soft_copy) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } //End of if 
                                        ?>
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
                        <?php if ($status === 'QA') { ?>
                            <form id="myfrm" method="POST" action="<?= base_url('spservices/necertificate/querysubmit') ?>" enctype="multipart/form-data">
                                <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
                                <input name="rtps_trans_id" value="<?= $rtps_trans_id ?>" type="hidden" />
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>Remarks </label>
                                        <textarea class="form-control" name="query_description"></textarea>
                                        <?= form_error("query_description") ?>
                                    </div>
                                </div>
                            </form>
                        <?php } //End of if 
                        ?>
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