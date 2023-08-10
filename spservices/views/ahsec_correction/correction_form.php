<?php
$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
// $apiServer = "https://localhost/wptbcapis/"; //For testing
// pre($dbrow);
if ($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $pageTitle = $dbrow->service_data->service_name;
    $pageTitleId = $dbrow->service_data->service_id;
    $applicant_name = $dbrow->form_data->applicant_name ?? set_value("applicant_name");
    $applicant_gender = $dbrow->form_data->applicant_gender ?? set_value("applicant_gender");
    $father_name = $dbrow->form_data->father_name ?? set_value("father_name");
    $mother_name = $dbrow->form_data->mother_name ?? set_value("mother_name");
    $mobile = $dbrow->form_data->mobile ?? set_value("mobile");
    $email = $dbrow->form_data->email ?? set_value("email");

    $p_comp_permanent_address = $dbrow->form_data->p_comp_permanent_address ?? set_value("p_comp_permanent_address");
    $p_state = $dbrow->form_data->p_state ?? set_value("p_state");
    $p_district = $dbrow->form_data->p_district ?? set_value("p_district");
    $p_police_st = $dbrow->form_data->p_police_st ?? set_value("p_police_st");
    $p_post_office = $dbrow->form_data->p_post_office ?? set_value("p_post_office");
    $p_pin_code = $dbrow->form_data->p_pin_code ?? set_value("p_pin_code");
    $same_as_p_address = $dbrow->form_data->same_as_p_address ?? set_value("checker");
    $c_comp_permanent_address = $dbrow->form_data->c_comp_permanent_address ?? set_value("c_comp_permanent_address");
    $c_state = $dbrow->form_data->c_state ?? set_value("c_state");
    $c_district = $dbrow->form_data->c_district ?? set_value("c_district");
    $c_police_st = $dbrow->form_data->c_police_st ?? set_value("c_police_st");
    $c_post_office = $dbrow->form_data->c_post_office ?? set_value("c_post_office");
    $c_pin_code = $dbrow->form_data->c_pin_code ?? set_value("c_pin_code");

    $ahsec_reg_session = $dbrow->form_data->ahsec_reg_session ?? set_value("ahsec_reg_session");
    $ahsec_reg_no = $dbrow->form_data->ahsec_reg_no ?? set_value("ahsec_reg_no");
    $ahsec_yearofappearing = $dbrow->form_data->ahsec_yearofappearing ?? set_value("ahsec_yearofappearing");
    $ahsec_admit_roll = $dbrow->form_data->ahsec_admit_roll ?? set_value("ahsec_admit_roll");
    $ahsec_admit_no = $dbrow->form_data->ahsec_admit_no ?? set_value("ahsec_admit_no");
    $institution_name = $dbrow->form_data->institution_name ?? set_value("institution_name");
    $results = $dbrow->form_data->results ?? set_value("results");

    $candidate_name_checkbox = $dbrow->form_data->candidate_name_checkbox ?? set_value("candidate_name_checkbox");
    $father_name_checkbox = $dbrow->form_data->father_name_checkbox ?? set_value("father_name_checkbox");
    $mother_name_checkbox = $dbrow->form_data->mother_name_checkbox ?? set_value("mother_name_checkbox");

    $incorrect_candidate_name = $dbrow->form_data->incorrect_candidate_name ?? set_value("incorrect_candidate_name");
    $incorrect_father_name = $dbrow->form_data->incorrect_father_name ?? set_value("incorrect_father_name");
    $incorrect_mother_name = $dbrow->form_data->incorrect_mother_name ?? set_value("incorrect_mother_name");

    $correct_candidate_name = $dbrow->form_data->correct_candidate_name ?? set_value("correct_candidate_name");
    $correct_father_name = $dbrow->form_data->correct_father_name ?? set_value("correct_father_name");
    $correct_mother_name = $dbrow->form_data->correct_mother_name ?? set_value("correct_mother_name");

    $delivery_mode = $dbrow->form_data->delivery_mode ?? set_value("delivery_mode");

    $passport_photo_type = isset($dbrow->form_data->passport_photo_type) ? $dbrow->form_data->passport_photo_type : '';
    $passport_photo = isset($dbrow->form_data->passport_photo) ? $dbrow->form_data->passport_photo : '';
    $signature_type = isset($dbrow->form_data->signature_type) ? $dbrow->form_data->signature_type : '';
    $signature = isset($dbrow->form_data->signature) ? $dbrow->form_data->signature : '';
    $affidavit_type = isset($dbrow->form_data->affidavit_type) ? $dbrow->form_data->affidavit_type : '';
    $affidavit = isset($dbrow->form_data->affidavit) ? $dbrow->form_data->affidavit : '';
    $registration_card_type = isset($dbrow->form_data->registration_card_type) ? $dbrow->form_data->registration_card_type : '';
    $registration_card = isset($dbrow->form_data->registration_card) ? $dbrow->form_data->registration_card : '';
    $admit_card_type = isset($dbrow->form_data->admit_card_type) ? $dbrow->form_data->admit_card_type : '';
    $admit_card = isset($dbrow->form_data->admit_card) ? $dbrow->form_data->admit_card : '';
    $marksheet_type = isset($dbrow->form_data->marksheet_type) ? $dbrow->form_data->marksheet_type : '';
    $marksheet = isset($dbrow->form_data->marksheet) ? $dbrow->form_data->marksheet : '';
    $pass_certificate_type = isset($dbrow->form_data->pass_certificate_type) ? $dbrow->form_data->pass_certificate_type : '';
    $pass_certificate = isset($dbrow->form_data->pass_certificate) ? $dbrow->form_data->pass_certificate : '';

    $soft_copy_type = isset($dbrow->form_data->soft_copy_type) ? $dbrow->form_data->soft_copy_type : '';
    $soft_copy = isset($dbrow->form_data->soft_copy) ? $dbrow->form_data->soft_copy : '';
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL; //set_value("rtps_trans_id");
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $father_name = set_value("father_name");
    $mother_name = set_value("mother_name");
    $mobile = $this->session->mobile; //set_value("mobile_number");
    $email = set_value("email");

    $p_comp_permanent_address = set_value("p_comp_permanent_address");
    $p_state = set_value("p_state");
    $p_district = "";
    $p_district_id = "";
    $p_police_st = set_value("p_police_st");
    $p_post_office = set_value("p_post_office");
    $p_pin_code = set_value("p_pin_code");

    $same_as_p_address = set_value("checker");

    $c_comp_permanent_address = set_value("c_comp_permanent_address");
    $c_state = set_value("c_state");
    $c_district = "";
    $c_district_id = "";
    $c_police_st = set_value("c_police_st");
    $c_post_office = set_value("c_post_office");
    $c_pin_code = set_value("c_pin_code");

    $ahsec_reg_session = set_value("ahsec_reg_session");
    $ahsec_reg_no = set_value("ahsec_reg_no");
    $ahsec_yearofappearing = set_value("ahsec_yearofappearing");
    $ahsec_admit_roll = set_value("ahsec_admit_roll");
    $ahsec_admit_no = set_value("ahsec_admit_no");
    $institution_name = set_value("institution_name");
    $results = set_value("results");

    $candidate_name_checkbox = set_value("candidate_name_checkbox");
    $father_name_checkbox = set_value("father_name_checkbox");
    $mother_name_checkbox = set_value("mother_name_checkbox");

    $incorrect_candidate_name = set_value("incorrect_candidate_name");
    $incorrect_father_name = set_value("incorrect_father_name");
    $incorrect_mother_name = set_value("incorrect_mother_name");

    $correct_candidate_name = set_value("correct_candidate_name");
    $correct_father_name = set_value("correct_father_name");
    $correct_mother_name = set_value("correct_mother_name");
    $delivery_mode = set_value("delivery_mode");

    $passport_photo_type = "";
    $passport_photo = "";
    $signature_type = "";
    $signature = "";
    $affidavit_type = "";
    $affidavit = "";
    $admit_card_type = "";
    $admit_card  = "";
    $registration_card_type = "";
    $registration_card = "";
    $marksheet_type = "";
    $marksheet = "";
    $pass_certificate_type = "";
    $pass_certificate = "";
    $other_doc_type = "";
    $other_doc = "";
    $soft_copy_type = "";
    $soft_copy = "";
} //End of if else
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

    .blink {
        animation: blinker 2s linear infinite;
    }

    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }

    .message {
        color: red;
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
                $('#c_pin_code').val($('#p_pin_code').val());

            } else {
                $('#c_comp_permanent_address').val("");
                $('#c_state').prop('selectedIndex', 0);
                $('#c_district').prop('selectedIndex', 0);
                $('#c_pin_code').val("");
            }
        });


        let candidate_name_checkbox = '<?= $candidate_name_checkbox ?>';
        let father_name_checkbox = '<?= $father_name_checkbox ?>';
        let mother_name_checkbox = '<?= $mother_name_checkbox ?>';

        let same_as_p_address = '<?= $same_as_p_address ?>';
        // alert(same_as_p_address);
        if (same_as_p_address == "1") {
            $('#checker').prop('checked', true);
        } else {
            $('#checker').prop('checked', false);
        }
        if (candidate_name_checkbox == "1" && ($('#incorrect_candidate_name').val() != '' || $('#correct_candidate_name').val() != '')) {
            $('#candidate_name_checkbox').prop('checked', true);
            $('#incorrect_candidate_name').prop('disabled', false);
            $('#correct_candidate_name').prop('disabled', false);
        } else {
            $('#candidate_name_checkbox').prop('checked', false);
            $('#incorrect_candidate_name').prop('disabled', true).val('');
            $('#correct_candidate_name').prop('disabled', true).val('');
        }
        if (father_name_checkbox == "2" && ($('#incorrect_father_name').val() != '' || $('#correct_father_name').val() != '')) {
            $("#father_name_checkbox").prop('checked', true);
            $('#incorrect_father_name').prop('disabled', false);
            $('#correct_father_name').prop('disabled', false);
        } else {
            $('#father_name_checkbox').prop('checked', false);
            $('#incorrect_father_name').prop('disabled', true).val('');
            $('#correct_father_name').prop('disabled', true).val('');
        }
        if (mother_name_checkbox == "3" && ($('#incorrect_mother_name').val() != '' || $('#correct_mother_name').val() != '')) {
            $("#mother_name_checkbox").prop('checked', true);
            $('#incorrect_mother_name').prop('disabled', false);
            $('#correct_mother_name').prop('disabled', false);
        } else {
            $('#mother_name_checkbox').prop('checked', false);
            $('#incorrect_mother_name').prop('disabled', true).val('');
            $('#correct_mother_name').prop('disabled', true).val('');
        }

        // Checkbox click event handlers
        $('#candidate_name_checkbox').click(function() {
            if ($(this).is(':checked')) {
                $('#incorrect_candidate_name').prop('disabled', false);
                $('#correct_candidate_name').prop('disabled', false);
            } else {
                $('#incorrect_candidate_name').prop('disabled', true).val('');
                $('#correct_candidate_name').prop('disabled', true).val('');
            }
        });

        $('#father_name_checkbox').click(function() {
            if ($(this).is(':checked')) {
                $('#incorrect_father_name').prop('disabled', false);
                $('#correct_father_name').prop('disabled', false);
            } else {
                $('#incorrect_father_name').prop('disabled', true).val('');
                $('#correct_father_name').prop('disabled', true).val('');
            }
        });

        $('#mother_name_checkbox').click(function() {
            if ($(this).is(':checked')) {
                $('#incorrect_mother_name').prop('disabled', false);
                $('#correct_mother_name').prop('disabled', false);
            } else {
                $('#incorrect_mother_name').prop('disabled', true).val('');
                $('#correct_mother_name').prop('disabled', true).val('');
            }
        });

        let delivery_mode = '<?= $delivery_mode ?>';
        showMessage(delivery_mode);

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
                    } else if (clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {} //End of if else
                }
            });
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/ahsec-correction') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="service_name" value="<?= $pageTitle ?>" type="hidden" />
            <input name="service_id" value="<?= $pageTitleId ?>" type="hidden" />
            <input name="passport_photo_type" value="<?= $passport_photo_type ?>" type="hidden" />
            <input name="passport_photo" value="<?= $passport_photo ?>" type="hidden" />
            <input name="signature_type" value="<?= $signature_type ?>" type="hidden" />
            <input name="signature" value="<?= $signature ?>" type="hidden" />
            <input name="affidavit_type" value="<?= $affidavit_type ?>" type="hidden" />
            <input name="affidavit" value="<?= $affidavit ?>" type="hidden" />
            <input name="admit_card_type" value="<?= $admit_card_type ?>" type="hidden" />
            <input name="admit_card" value="<?= $admit_card ?>" type="hidden" />
            <input name="registration_card_type" value="<?= $registration_card_type ?>" type="hidden" />
            <input name="registration_card" value="<?= $registration_card ?>" type="hidden" />
            <input name="pass_certificate_type" value="<?= $pass_certificate_type ?>" type="hidden" />
            <input name="pass_certificate" value="<?= $pass_certificate ?>" type="hidden" />
            <?php if (!empty($marksheet_type)) { ?>
                <input name="marksheet_type" value="<?= $marksheet_type ?>" type="hidden" />
                <input name="marksheet" value="<?= $marksheet ?>" type="hidden" />
            <?php } ?>
            <input name="soft_copy_type" value="<?= $soft_copy_type ?>" type="hidden" />
            <input name="soft_copy" value="<?= $soft_copy ?>" type="hidden" />
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

                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>

                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <strong style="font-size:16px;">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                            <li>1. All the <span class="text-danger">*</span> marked fields are mandatory and need to be filled up.</li>
                            <li>১. <span class="text-danger">*</span> চিহ্ন দিয়া স্থানসমূহ বাধ্য়তামুলক আৰু স্থানসমূহ পুৰণ কৰিব লাগিব</li>
                            <li>2. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য।</li>

                        </ul>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?= $applicant_name ?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control">
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
                                <label>Father&apos;s Name/ পিতৃৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?= $father_name ?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mother&apos;s Name/ মাতৃৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name" value="<?= $mother_name ?>" maxlength="255" />
                                <?= form_error("mother_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <?php if ($usser_type === "user") { ?>
                                    <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" readonly maxlength="10" />
                                <?php } else { ?>
                                    <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" maxlength="10" />
                                <?php } ?>

                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="email" value="<?= $email ?>" maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address/ স্থায়ী ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Complete Permanent Address/ সম্পূৰ্ণ স্থায়ী ঠিকনা <span class="text-danger">*</span></label>
                                <textarea id="p_comp_permanent_address" class="form-control" name="p_comp_permanent_address" rowspan="4"><?= $p_comp_permanent_address ?></textarea>
                                <?= form_error("p_comp_permanent_address") ?>
                            </div>
                            <div class="col-md-6">
                                <label>State/ ৰাজ্য <span class="text-danger">*</span> </label>
                                <select id="p_state" name="p_state" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Assam" autocomplete="off" selected="selected">Assam</option>
                                </select>
                                <?= form_error("p_state") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Select District/ জিলা নিৰ্বাচন কৰক <span class="text-danger">*</span> </label>
                                <select name="p_district" id="p_district" class="form-control dists">
                                    <option value="<?= strlen($p_district) ? $p_district : '' ?>"><?= strlen($p_district) ? $p_district : 'Select' ?></option>
                                </select>
                                <?= form_error("p_district") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Pin Code/ পিনক'ড (e.g 78xxxx) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="p_pin_code" id="p_pin_code" value="<?= $p_pin_code ?>" maxlength="6" />
                                <?= form_error("p_pin_code") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Postal Address / ডাক ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <input type="checkbox" name="checker" id="checker" value="1" /><b>&nbsp;&nbsp;Same as Permanent Address</b>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Complete Address/ সম্পূৰ্ণ ঠিকনা <span class="text-danger">*</span></label>
                                <textarea id="c_comp_permanent_address" class="form-control" name="c_comp_permanent_address" rowspan="4"><?= $c_comp_permanent_address ?></textarea>
                                <?= form_error("c_comp_permanent_address") ?>
                            </div>
                            <div class="col-md-6">
                                <label>State/ ৰাজ্য <span class="text-danger">*</span> </label>
                                <select id="c_state" name="c_state" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Assam" autocomplete="off" selected="selected">Assam</option>
                                </select>
                                <?= form_error("c_state") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Select District/ জিলা নিৰ্বাচন কৰক <span class="text-danger">*</span> </label>
                                <select name="c_district" id="c_district" class="form-control dists">
                                    <option value="<?= strlen($c_district) ? $c_district : '' ?>"><?= strlen($c_district) ? $c_district : 'Select' ?></option>
                                </select>
                                <?= form_error("c_district") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Pin Code/ পিনক'ড (e.g 78xxxx) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="c_pin_code" id="c_pin_code" value="<?= $c_pin_code ?>" maxlength="6" />
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
                                    <span class="text-danger">*</span>
                                    </label>
                                    <select name="ahsec_yearofappearing" id="ahsec_yearofappearing" class="form-control" disabled>
                                        <option value="">Please Select</option>
                                        <?php if ($pageTitleId == "AHSECCRC") { ?>
                                            <option value="<?php echo 'Not yet appeared'; ?>" <?= ($ahsec_yearofappearing === "Not yet appeared") ? 'selected' : '' ?>>Not yet appeared</option>
                                        <?php } ?>
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
                                <select name="results" id="results" class="form-control">
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
                                <input type="checkbox" name="candidate_name_checkbox" value="1" id="candidate_name_checkbox" /><b>&nbsp;&nbsp;Candidate's Name/ প্ৰাৰ্থীৰ নাম</b>
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="Record in my document as" class="form-control" name="incorrect_candidate_name" id="incorrect_candidate_name" value="<?= $incorrect_candidate_name ?>" maxlength="255" />
                                <?= form_error("incorrect_candidate_name") ?>
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="To be corrected as" class="form-control" name="correct_candidate_name" id="correct_candidate_name" value="<?= $correct_candidate_name ?>" maxlength="255" />
                                <?= form_error("correct_candidate_name") ?>
                            </div>

                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <input type="checkbox" name="father_name_checkbox" value="2" id="father_name_checkbox" /><b>&nbsp;&nbsp;Father's Name/ পিতৃৰ নাম</b>
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="Record in my document as" class="form-control" name="incorrect_father_name" id="incorrect_father_name" value="<?= $incorrect_father_name ?>" maxlength="255" />
                                <?= form_error("incorrect_father_name") ?>
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="To be corrected as" class="form-control" name="correct_father_name" id="correct_father_name" value="<?= $correct_father_name ?>" maxlength="255" />
                                <?= form_error("correct_father_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <input type="checkbox" name="mother_name_checkbox" value="3" id="mother_name_checkbox" /><b>&nbsp;&nbsp;Mother's Name/ মাতৃৰ নাম</b>
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="Record in my document as" class="form-control" name="incorrect_mother_name" id="incorrect_mother_name" value="<?= $incorrect_mother_name ?>" maxlength="255" />
                                <?= form_error("incorrect_mother_name") ?>
                            </div>
                            <div class="col-md-4">
                                <input type="text" placeholder="To be corrected as" class="form-control" name="correct_mother_name" id="correct_mother_name" value="<?= $correct_mother_name ?>" maxlength="255" />
                                <?= form_error("correct_mother_name") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Delivery Preference/ বিতৰণৰ অগ্ৰাধিকাৰ </legend>
                        <p>How would you want to receive your corrected documents ? <br> আপুনি আপোনাৰ শুদ্ধ নথিপত্ৰ কেনেকৈ লাভ কৰিব বিচাৰিব ?</p>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <input type="radio" name="delivery_mode" value="FROM AHSEC COUNTER" id="delivery_mode_1" onclick="showMessage(this.value)" <?= ($delivery_mode === "FROM AHSEC COUNTER") ? 'checked' : '' ?> /><b>&nbsp;&nbsp;FROM AHSEC COUNTER (AHSEC কাউণ্টাৰৰ পৰা)</b>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <input type="radio" name="delivery_mode" value="IN MY POSTAL ADDRESS" id="delivery_mode_2" onclick="showMessage(this.value)" <?= ($delivery_mode === "IN MY POSTAL ADDRESS") ? 'checked' : '' ?> /><b>&nbsp;&nbsp;IN MY POSTAL ADDRESS (মোৰ ডাক ঠিকনাত)</b>
                            </div>
                        </div>
                    </fieldset>
                    <p id="message" class="message"></p>
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Save & Next
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
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

<script>
    function showMessage(value) {
        var messageElement = document.getElementById("message");

        if (value === "FROM AHSEC COUNTER") {
            messageElement.innerHTML = "<i class='icon fas fa-hand-point-right'></i> APPLICANT MUST SUBMIT THE INCORRECT DOCUMENT AT THE TIME OF RECEIVING THE CORRECTED DOCUMENT";
            messageElement.classList.add("blink");
        } else if (value === "IN MY POSTAL ADDRESS") {
            messageElement.innerHTML = "<i class='icon fas fa-hand-point-right'></i> APPLICANT MUST SEND THE INCORRECT DOCUMENT VIA POST TO AHSEC FOR RECEIVING THE CORRECTED DOCUMENT";
            messageElement.classList.add("blink");
        } else {
            messageElement.innerHTML = "";
            messageElement.classList.remove("blink");
        }
    }
</script>