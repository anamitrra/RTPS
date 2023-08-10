<?php
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

    td {
        font-size: 14px;
    }

    .disabled-link {
        pointer-events: none;
        cursor: default;
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#printBtn", function() {
            $("#printDiv").print({
                addGlobalStyles: true,
                stylesheet: null,
                rejectWindow: true,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null
            });
        });

    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv">
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

                <?php if ($this->session->flashdata('success') != null) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>

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
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">ATTACHED ENCLOSURE(S) </legend>
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
                        </tbody>
                    </table>
                </fieldset>
            </div>
            <!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <?php if ($appl_status === 'DRAFT') { ?>
                    <a href="<?= base_url('spservices/ahsec_correction/ahseccor/index/' . $obj_id) ?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                <?php } ?>
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <?php if (($appl_status != 'QA') && ($appl_status != 'QS')) { ?>
                    <!-- <a href="JavaScript:Void(0);" class="btn btn-success frmsbbtn" id="disableanchor">
                        <i class="fa fa-angle-double-right"></i> Submit
                    </a> -->
                    <a href="<?= base_url('spservices/ahsec_correction/payment/initiate/' . $obj_id) ?>" class="btn btn-success">
                        <i class="fa fa-angle-double-right"></i> Make Payment
                    </a>
                <?php } ?>
                <!-- <?php //if (((//$user_type != 'user') && ($appl_status === 'DRAFT')) || ($appl_status === 'payment_initiated')) { 
                        ?>
                    <a href="<?php //base_url('spservices/income/payment/initiate/' . $obj_id) 
                                ?>" class="btn btn-success">
                        <i class="fa fa-angle-double-right"></i> Make Payment
                    </a>
                <?php //} 
                ?> -->
            </div>
            <!--End of .card-footer-->
        </div>
        <!--End of .card-->
    </div>
    <!--End of .container-->
</main>