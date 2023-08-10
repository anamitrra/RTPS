<?php
// pre("Hello0");
$currentYear = date('Y');
if ($dbrow) {
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $pageTitle = $dbrow->service_data->service_name;
    $pageTitleId = $dbrow->service_data->service_id;

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

    $passport_photo_type = $dbrow->form_data->passport_photo_type ?? '';
    $passport_photo = $dbrow->form_data->passport_photo ?? '';

    $signature_type = $dbrow->form_data->signature_type ?? '';
    $signature = $dbrow->form_data->signature ?? '';

    $affidavit_type = $dbrow->form_data->affidavit_type ?? '';
    $affidavit = $dbrow->form_data->affidavit ?? '';

    $registration_card_type = $dbrow->form_data->registration_card_type ?? '';
    $registration_card = $dbrow->form_data->registration_card ?? '';

    $admit_card_type = $dbrow->form_data->admit_card_type ?? '';
    $admit_card = $dbrow->form_data->admit_card ?? '';

    $pass_certificate_type = $dbrow->form_data->pass_certificate_type ?? '';
    $pass_certificate = $dbrow->form_data->pass_certificate ?? '';

    $marksheet_type = $dbrow->form_data->marksheet_type ?? '';
    $marksheet = $dbrow->form_data->marksheet ?? '';

    $soft_copy_type = $dbrow->form_data->soft_copy_type ?? '';
    $soft_copy = $dbrow->form_data->soft_copy ?? '';

    $other_doc_type = $dbrow->form_data->other_doc_type ?? '';
    $other_doc = $dbrow->form_data->other_doc ?? '';

    $appl_status = $dbrow->service_data->appl_status;
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
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {


        $(document).on("click", ".frmbtn", function() {
            let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
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

        $(".dp").datepicker({
            format: 'dd/mm/yyyy',
            endDate: '+0d',
            autoclose: true
        });


        $("#wrongly_generate_certi").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: true,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/ahsec_correction/ahseccor/request_for_recorrection_submit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
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
                                <div class="col-md-12" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                    <?= (end($dbrow->processing_history)->remarks) ?? '' ?>
                                </div>
                            </div>
                            <span style="float:right; font-size: 12px">
                                Query time : <?= isset(end($dbrow->processing_history)->processing_time) ? format_mongo_date(end($dbrow->processing_history)->processing_time) : '' ?>
                            </span>
                        </fieldset>
                    <?php } //End of if 
                    ?>

                    <fieldset class="border border-success table-responsive" style="margin-top:40px">
                        <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </legend>
                        <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                            <tbody style="border-top: none !important">
                                <tr>
                                    <td>Name of the Applicant/আবেদনকাৰীৰ নাম<br><strong><?= $applicant_name ?></strong> </td>
                                    <td>Applicant's Gender/ আবেদনকাৰীৰ লিংগ <br><strong><?= $applicant_gender ?></strong> </td>
                                </tr>
                                <tr>
                                    <td>Father's Name/ পিতাৰ নাম <br><strong><?= $father_name ?></strong></td>
                                    <td>Mother's Name/ মাতৃৰ নাম <br><strong><?= $mother_name ?></strong></td>
                                </tr>
                                <tr>
                                    <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?= $mobile ?></strong></td>
                                    <td>E-Mail / ই-মেইল<br><strong><?= $email ?></strong> </td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="border border-success table-responsive" style="margin-top:40px">
                        <legend class="h5">Permanent Address/ স্থায়ী ঠিকনা </legend>
                        <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                            <tbody style="border-top: none !important">
                                <tr>
                                    <td>Complete Permanent Address/ সম্পূৰ্ণ স্থায়ী ঠিকনা<br><strong><?= $p_comp_permanent_address ?></strong> </td>
                                    <td>State/ ৰাজ্য<br><strong><?= $p_state ?></strong> </td>
                                </tr>
                                <tr>
                                    <td>District/ জিলা<br><strong><?= $p_district ?></strong></td>
                                    <td>Pin Code/ পিনক'ড (e.g 78xxxx)<br><strong><?= $p_pin_code ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="border border-success table-responsive" style="margin-top:40px">
                        <legend class="h5">Postal Address / ডাক ঠিকনা </legend>
                        <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                            <tbody style="border-top: none !important">
                                <tr>
                                    <td>Complete Permanent Address/ সম্পূৰ্ণ স্থায়ী ঠিকনা<br><strong><?= $c_comp_permanent_address ?></strong> </td>
                                    <td>State/ ৰাজ্য<br><strong><?= $c_state ?></strong> </td>
                                </tr>
                                <tr>
                                    <td>District/ জিলা<br><strong><?= $c_district ?></strong></td>
                                    <td>Pin Code/ পিনক'ড (e.g 78xxxx)<br><strong><?= $c_pin_code ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="border border-success table-responsive" style="margin-top:40px">
                        <legend class="h5">Academic Details / শৈক্ষিক বিৱৰণ</legend>
                        <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                            <tbody style="border-top: none !important">
                                <tr>
                                    <td>AHSEC Registration Session/ AHSEC পঞ্জীয়ন বৰ্ষ<br><strong><?= $ahsec_reg_session ?></strong> </td>
                                    <td>Valid AHSEC Registration Number / বৈধ AHSEC পঞ্জীয়ন নম্বৰ<br><strong><?= $ahsec_reg_no ?></strong></td>
                                </tr>
                                <?php if ($pageTitleId != "AHSECCRC" || ($pageTitleId == "AHSECCRC" && !empty($ahsec_admit_roll) && !empty($ahsec_admit_no))) { ?>
                                    <tr>
                                        <td>Valid H.S Final Examination Roll/ বৈধ HS চূড়ান্ত বৰ্ষৰ পৰীক্ষাৰ ৰোল <br><strong><?= $ahsec_admit_roll ?></strong></td>
                                        <td>Valid H.S Final Examination Number/ বৈধ HS চূড়ান্ত বৰ্ষৰ পৰীক্ষাৰ নম্বৰ <br><strong><?= $ahsec_admit_no ?></strong></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td>Name of the Institution / প্ৰতিষ্ঠানৰ নাম <br><strong><?= $institution_name ?></strong></td>
                                    <?php if ($pageTitleId != "AHSECCRC-this-cond-toberemoved") { ?>
                                        <td>
                                            <?php if ($pageTitleId == "AHSECCPC") { ?>Year of Passing in HS Final Examination/ HS চূড়ান্ত পৰীক্ষাত উত্তীৰ্ণ হোৱাৰ বছৰ <?php } else { ?>Year of Appearing in HS Final Examination/ HS চূড়ান্ত পৰীক্ষাত অৱতীৰ্ণ হোৱাৰ বছৰ <?php } ?>
                                        <br><strong><?= $ahsec_yearofappearing ?></strong>
                                        </td>
                                    <?php } ?>
                                    <?php if ($pageTitleId == "AHSECCPC") { ?>
                                        <td>Result/ ফলাফল <br><strong><?= $results ?></strong></td>
                                    <?php } ?>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="border border-success table-responsive" style="margin-top:40px">
                        <legend class="h5">Name Correction/ নাম সংশোধন</legend>
                        <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                            <tbody style="border-top: none !important">
                                <tr>
                                    <td>Particulars of the Candidate / প্ৰাৰ্থীৰ বিৱৰণ</td>
                                    <td>Record in my document as / বৰ্তমান মোৰ নথিপত্ৰত লিপিবদ্ধ থকা তথ্য</td>
                                    <td>To be corrected as / সংশোধন কৰিব বিচৰা তথ্য</td>
                                </tr>
                                <?php if (!empty($candidate_name_checkbox)) { ?>
                                    <tr>
                                        <td>Candidate's Name / প্ৰাৰ্থীৰ নাম </td>
                                        <td><strong><?= $incorrect_candidate_name ?></strong></td>
                                        <td><strong><?= $correct_candidate_name ?></strong></td>
                                    </tr>
                                <?php }
                                if (!empty($father_name_checkbox)) { ?>
                                    <tr>
                                        <td>Father's Name / পিতৃৰ নাম </td>
                                        <td><strong><?= $incorrect_father_name ?></strong></td>
                                        <td><strong><?= $correct_father_name ?></strong></td>
                                    </tr>
                                <?php }
                                if (!empty($mother_name_checkbox)) { ?>
                                    <tr>
                                        <td>Mother's Name / মাতৃৰ নাম </td>
                                        <td><strong><?= $incorrect_mother_name ?></strong></td>
                                        <td><strong><?= $correct_mother_name ?></strong></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </fieldset>
                    <fieldset class="border border-success table-responsive" style="margin-top:40px">
                        <legend class="h5">Delivery Preference/ বিতৰণৰ অগ্ৰাধিকাৰ </legend>
                        <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                            <tbody style="border-top: none !important">
                                <tr>
                                    <td>How would you want to receive your corrected documents? /আপুনি আপোনাৰ শুদ্ধ নথিপত্ৰ কেনেকৈ লাভ কৰিব বিচাৰিব ? <br><strong><?= $delivery_mode ?></strong> </td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>

                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>১. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য।</li>
                        </ul>
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
                                            <td>Passport size photograph</td>
                                            <td style="font-weight:bold"><?= $passport_photo_type ?></td>
                                            <td>
                                                <?php if (strlen($passport_photo)) { ?>
                                                    <a href="<?= base_url($passport_photo) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Applicant Signature</td>
                                            <td style="font-weight:bold"><?= $signature_type ?></td>
                                            <td>
                                                <?php if (strlen($signature)) { ?>
                                                    <a href="<?= base_url($signature) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                        <?php if (strlen($affidavit)) { ?>
                                            <tr>
                                                <td>Court Affidavit</td>
                                                <td style="font-weight:bold"><?= $affidavit_type ?></td>
                                                <td>
                                                    <?php if (strlen($affidavit)) { ?>
                                                        <a href="<?= base_url($affidavit) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php }
                                        if (strlen($registration_card)) { ?>
                                            <tr>
                                                <td>Registration Card</td>
                                                <td style="font-weight:bold"><?= $registration_card_type ?></td>
                                                <td>
                                                    <?php if (strlen($registration_card)) { ?>
                                                        <a href="<?= base_url($registration_card) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } //End of if 
                                        if (strlen($admit_card)) {
                                        ?>
                                            <tr>
                                                <td>Admit Card</td>
                                                <td style="font-weight:bold"><?= $admit_card_type ?></td>
                                                <td>
                                                    <?php if (strlen($admit_card)) { ?>
                                                        <a href="<?= base_url($admit_card) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } //End of if 
                                        if (strlen($pass_certificate)) {
                                        ?>
                                            <tr>
                                                <td>Pass Certificate</td>
                                                <td style="font-weight:bold"><?= $pass_certificate_type ?></td>
                                                <td>
                                                    <?php if (strlen($pass_certificate)) { ?>
                                                        <a href="<?= base_url($pass_certificate) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php }
                                        if (strlen($marksheet)) { ?>
                                            <tr>
                                                <td>Marksheet</td>
                                                <td style="font-weight:bold"><?= $marksheet_type ?></td>
                                                <td>
                                                    <?php if (strlen($marksheet)) { ?>
                                                        <a href="<?= base_url($marksheet) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } //End of if 
                                        ?>
                                        <?php if (strlen($other_doc)) { ?>
                                            <tr>
                                                <td>HS Admit Card</td>
                                                <td style="font-weight:bold"><?= $other_doc_type ?></td>
                                                <td>
                                                    <?php if (strlen($other_doc)) { ?>
                                                        <a href="<?= base_url($other_doc) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } //End of if 
                                        ?>
                                        <?php if (strlen($soft_copy)) { ?>
                                            <tr>
                                                <td>Soft copy of the applicant form</td>
                                                <td style="font-weight:bold"><?= $soft_copy_type ?></td>
                                                <td>
                                                    <a href="<?= base_url($soft_copy) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } //End of if 
                                        ?>

                                        <tr>
                                            <td>Upload copy of wrongly generate certificate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="wrongly_generate_certi_type" class="form-control">
                                                    <option value="Wrongly generate certificate">Wrongly generate certificate</option>
                                                </select>
                                                <?= form_error("wrongly_generate_certi_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="wrongly_generate_certi" name="wrongly_generate_certi" type="file" />
                                                </div>
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
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <!-- <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button> -->
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-angle-double-right"></i> Submit
                    </button>
                    <!-- <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button> -->
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>