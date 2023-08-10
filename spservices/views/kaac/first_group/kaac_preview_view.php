<?php

$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;
$pageTitle = $dbrow->service_data->service_name;
$pageTitleId = $dbrow->form_data->service_id;

$applicant_name = $dbrow->form_data->first_name . " " . $dbrow->form_data->last_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$father_name = $dbrow->form_data->father_name;
// $mother_name = $dbrow->form_data->mother_name;
$mobile = $dbrow->form_data->mobile;
$email = !empty($dbrow->form_data->email) ? $dbrow->form_data->email : 'NA';

if ($pageTitleId == "CCM") {

    $mut_name = $dbrow->form_data->mut_first_name . " " . $dbrow->form_data->mut_last_name;
    $mut_gender = $dbrow->form_data->mut_gender;
    $mut_father_name = $dbrow->form_data->mut_father_name;
    // $mother_name = $dbrow->form_data->mother_name;
    $mut_mobile = $dbrow->form_data->mut_mobile;
    $mut_email = !empty($dbrow->form_data->mut_email) ? $dbrow->form_data->mut_email : 'NA';
}

$circle_office = $dbrow->form_data->circle_office_name;
$mouza_name = $dbrow->form_data->mouza_office_name;
$revenue_village = $dbrow->form_data->revenue_village_name;
$p_police_st = $dbrow->form_data->police_station_name;
$p_post_office = $dbrow->form_data->post_office;

$address_proof_type = isset($dbrow->form_data->address_proof_type) ? $dbrow->form_data->address_proof_type : '';
$address_proof = isset($dbrow->form_data->address_proof) ? $dbrow->form_data->address_proof : '';
$identity_proof_type = isset($dbrow->form_data->identity_proof_type) ? $dbrow->form_data->identity_proof_type : '';
$identity_proof = isset($dbrow->form_data->identity_proof) ? $dbrow->form_data->identity_proof : '';
$land_patta_copy_type = isset($dbrow->form_data->land_patta_copy_type) ? $dbrow->form_data->land_patta_copy_type : '';
$land_patta_copy = isset($dbrow->form_data->land_patta_copy) ? $dbrow->form_data->land_patta_copy : '';
$updated_land_revenue_receipt_type = isset($dbrow->form_data->updated_land_revenue_receipt_type) ? $dbrow->form_data->updated_land_revenue_receipt_type : '';
$updated_land_revenue_receipt = isset($dbrow->form_data->updated_land_revenue_receipt) ? $dbrow->form_data->updated_land_revenue_receipt : '';
$Up_to_date_original_land_documents_type = isset($dbrow->form_data->Up_to_date_original_land_documents_type) ? $dbrow->form_data->Up_to_date_original_land_documents_type : '';
$Up_to_date_original_land_documents = isset($dbrow->form_data->Up_to_date_original_land_documents) ? $dbrow->form_data->Up_to_date_original_land_documents : '';
$up_to_date_khajna_receipt_type = isset($dbrow->form_data->up_to_date_khajna_receipt_type) ? $dbrow->form_data->up_to_date_khajna_receipt_type : '';
$up_to_date_khajna_receipt = isset($dbrow->form_data->up_to_date_khajna_receipt) ? $dbrow->form_data->up_to_date_khajna_receipt : '';
$copy_of_jamabandi_type = isset($dbrow->form_data->copy_of_jamabandi_type) ? $dbrow->form_data->copy_of_jamabandi_type : '';
$copy_of_jamabandi = isset($dbrow->form_data->copy_of_jamabandi) ? $dbrow->form_data->copy_of_jamabandi : '';

$revenue_patta_copy_type = isset($dbrow->form_data->revenue_patta_copy_type) ? $dbrow->form_data->revenue_patta_copy_type : '';
$revenue_patta_copy = isset($dbrow->form_data->revenue_patta_copy) ? $dbrow->form_data->revenue_patta_copy : '';
$copy_of_chitha_type = isset($dbrow->form_data->copy_of_chitha_type) ? $dbrow->form_data->copy_of_chitha_type : '';
$copy_of_chitha = isset($dbrow->form_data->copy_of_chitha) ? $dbrow->form_data->copy_of_chitha : '';
$settlement_land_patta_copy_type = isset($dbrow->form_data->settlement_land_patta_copy_type) ? $dbrow->form_data->settlement_land_patta_copy_type : '';
$settlement_land_patta_copy = isset($dbrow->form_data->settlement_land_patta_copy) ? $dbrow->form_data->settlement_land_patta_copy : '';
$land_revenue_receipt_type = isset($dbrow->form_data->land_revenue_receipt_type) ? $dbrow->form_data->land_revenue_receipt_type : '';
$land_revenue_receipt = isset($dbrow->form_data->land_revenue_receipt) ? $dbrow->form_data->land_revenue_receipt : '';
$police_verification_report_type = isset($dbrow->form_data->police_verification_report_type) ? $dbrow->form_data->police_verification_report_type : '';
$police_verification_report = isset($dbrow->form_data->police_verification_report) ? $dbrow->form_data->police_verification_report : '';
$photocopy_of_existing_land_documents_type = isset($dbrow->form_data->photocopy_of_existing_land_documents_type) ? $dbrow->form_data->photocopy_of_existing_land_documents_type : '';
$photocopy_of_existing_land_documents = isset($dbrow->form_data->photocopy_of_existing_land_documents) ? $dbrow->form_data->photocopy_of_existing_land_documents : '';

$no_dues_certificate_from_bank_type = isset($dbrow->form_data->no_dues_certificate_from_bank_type) ? $dbrow->form_data->no_dues_certificate_from_bank_type : '';
$no_dues_certificate_from_bank = isset($dbrow->form_data->no_dues_certificate_from_bank) ? $dbrow->form_data->no_dues_certificate_from_bank : '';

$last_time_paid_Land_revenue_receipt_type = isset($dbrow->form_data->last_time_paid_Land_revenue_receipt_type) ? $dbrow->form_data->last_time_paid_Land_revenue_receipt_type : '';
$last_time_paid_Land_revenue_receipt = isset($dbrow->form_data->last_time_paid_Land_revenue_receipt) ? $dbrow->form_data->last_time_paid_Land_revenue_receipt : '';

$appl_status = $dbrow->service_data->appl_status;


$periodic_patta_no = isset($dbrow->form_data->periodic_patta_no) ? $dbrow->form_data->periodic_patta_no : '';
$dag_no = $dbrow->form_data->dag_no;
$land_area_bigha = $dbrow->form_data->land_area_bigha;
$land_area_katha = $dbrow->form_data->land_area_katha;
$land_area_loosa = $dbrow->form_data->land_area_loosa;
$land_area_sq_ft = $dbrow->form_data->land_area_sq_ft;
$patta_type = $dbrow->form_data->patta_type_name;
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
                            case "DCTH":
                                echo '( চিঠাৰ প্ৰমাণিত কপি )';
                                break;
                            case "CCJ":
                                echo '( জামাবন্দীৰ প্ৰমাণিত কপি )';
                                break;
                            case "CCM":
                                echo '( মিউটেচনৰ প্ৰমাণিত কপি )';
                                break;
                            case "DLP":
                                echo '( ভূমি পট্টাৰ ডুপ্লিকেট কপি )';
                                break;
                            case "ITMKA":
                                echo '( ট্ৰেচ মেপ জাৰি কৰা )';
                                break;
                            case "LHOLD":
                                echo '( ভূমি ৰখাৰ প্ৰমাণ পত্ৰ )';
                                break;
                            case "LRCC":
                                echo '( ভূমি ৰাজহ নিষ্কাশনৰ প্ৰমাণ পত্ৰ )';
                                break;
                            case "LVC":
                                echo '( ভূমি মূল্যায়নৰ প্ৰমাণ পত্ৰ )';
                                break;
                            case "NECKA":
                                echo '( নন-এনকামব্ৰেন্স প্ৰমাণপত্ৰ )';
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
                                <td>Father's Name/ পিতাৰ নাম <br><strong><?= $father_name ?></strong></td>
                            </tr>

                            <tr>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?= $mobile ?></strong></td>
                                <td>E-Mail / ই-মেইল<br><strong><?= $email ?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                <?php if ($pageTitleId == "CCM") { ?>
                    <fieldset class="border border-success table-responsive" style="margin-top:40px">
                        <legend class="h5">Mutation order in the Name of/ আবেদনকাৰীৰ তথ্য </legend>
                        <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                            <tbody style="border-top: none !important">
                                <tr>
                                    <td>Name / নাম<br><strong><?= $mut_name ?></strong> </td>
                                    <td>Gender/ লিংগ <br><strong><?= $mut_gender ?></strong> </td>
                                    <td>Father's/Husband's Name/ পিতাৰ নাম <br><strong><?= $mut_father_name ?></strong></td>
                                </tr>

                                <tr>
                                    <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?= $mut_mobile ?></strong></td>
                                    <td>E-Mail / ই-মেইল<br><strong><?= $mut_email ?></strong> </td>
                                </tr>
                            </tbody>
                        </table>
                    </fieldset>
                <?php } ?>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Address/ ঠিকনা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Circle Name/ চক্ৰ নাম<br><strong><?= $circle_office ?></strong> </td>
                                <td>Mouza Name/ মৌজা নাম <br><strong><?= $mouza_name ?></strong> </td>
                                <td>Revenue Village Name/ ৰাজহ গাঁও নাম<br><strong><?= $revenue_village ?></strong></td>
                            </tr>
                            <tr>
                                <td>Police Station/ থানা <br><strong><?= $p_police_st ?></strong></td>
                                <td>Post Office/ ডাকঘৰ <br><strong><?= $p_post_office ?></strong></td>
                            </tr>

                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Other Details / অন্যান্য বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px;margin-bottom:0px;border-collapse: collapse;width: 100%;table-layout: fixed;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Dag No. / ডাগ নং<br><strong><?= $dag_no ?></strong> </td>
                                <td>Annual Patta/Periodic Patta No./ বাৰ্ষিক পট্টা/সাময়িক পট্টা নং<br><strong><?= $periodic_patta_no ?></strong> </td>
                                <td>Patta Type/ পট্টা টাইপ<br><strong><?= $patta_type ?></strong></td>
                            </tr>
                            <!-- <tr>
                                <td>Police Station/ থানা <br><strong><?= $p_police_st ?></strong></td>
                                <td>Post Office/ ডাকঘৰ <br><strong><?= $p_post_office ?></strong></td>
                            </tr> -->

                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Land Area/ ভূমিৰ আয়তন </legend>
                    <table class="table table-borderless " style="margin-top:0px;margin-bottom:0px;border-collapse: collapse;width: 100%;table-layout: fixed;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Bigha./ বিঘা<br><strong><?= $land_area_bigha ?></strong> </td>
                                <td>Kotha./ কঠা<br><strong><?= $land_area_katha ?></strong> </td>
                                <td>Loosa./ লেচা<br><strong><?= $land_area_loosa ?></strong></td>
                                <td>Land Area./ ভূমিৰ আয়তন<br><strong><?= $land_area_sq_ft ?></strong></td>
                            </tr>
                            <!-- <tr>
                                <td>Police Station/ থানা <br><strong><?= $p_police_st ?></strong></td>
                                <td>Post Office/ ডাকঘৰ <br><strong><?= $p_post_office ?></strong></td>
                            </tr> -->

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
                            <?php if (strlen($address_proof)) { ?>
                                <tr>
                                    <td>Address Proof</td>
                                    <td style="font-weight:bold"><?= $address_proof_type ?></td>
                                    <td>
                                        <?php if (strlen($address_proof)) { ?>
                                            <a href="<?= base_url($address_proof) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php }
                            if (strlen($identity_proof)) { ?>
                                <tr>
                                    <td>Identity Proof</td>
                                    <td style="font-weight:bold"><?= $identity_proof_type ?></td>
                                    <td>
                                        <?php if (strlen($identity_proof)) { ?>
                                            <a href="<?= base_url($identity_proof) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            if (strlen($land_patta_copy)) {
                            ?>
                                <tr>
                                    <td>Land Patta Copy</td>
                                    <td style="font-weight:bold"><?= $land_patta_copy_type ?></td>
                                    <td>
                                        <?php if (strlen($land_patta_copy)) { ?>
                                            <a href="<?= base_url($land_patta_copy) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            if (strlen($updated_land_revenue_receipt)) {
                            ?>
                                <tr>
                                    <td>Updated Land Revenue Receipt</td>
                                    <td style="font-weight:bold"><?= $updated_land_revenue_receipt_type ?></td>
                                    <td>
                                        <?php if (strlen($updated_land_revenue_receipt)) { ?>
                                            <a href="<?= base_url($updated_land_revenue_receipt) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php }
                            if (strlen($Up_to_date_original_land_documents)) { ?>
                                <tr>
                                    <td>Up-to Date Original Land Document</td>
                                    <td style="font-weight:bold"><?= $Up_to_date_original_land_documents_type ?></td>
                                    <td>
                                        <?php if (strlen($Up_to_date_original_land_documents)) { ?>
                                            <a href="<?= base_url($Up_to_date_original_land_documents) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            ?>
                            <?php if (strlen($up_to_date_khajna_receipt)) { ?>
                                <tr>
                                    <td>Up to date Khajana Receipt</td>
                                    <td style="font-weight:bold"><?= $up_to_date_khajna_receipt_type ?></td>
                                    <td>
                                        <a href="<?= base_url($up_to_date_khajna_receipt) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            ?>
                            <?php if (strlen($copy_of_jamabandi)) { ?>
                                <tr>
                                    <td>Copy of Jamabandi</td>
                                    <td style="font-weight:bold"><?= $copy_of_jamabandi_type ?></td>
                                    <td>
                                        <a href="<?= base_url($copy_of_jamabandi) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            ?>
                            <?php if (strlen($revenue_patta_copy)) { ?>
                                <tr>
                                    <td>Revenue Patta Copy</td>
                                    <td style="font-weight:bold"><?= $revenue_patta_copy_type ?></td>
                                    <td>
                                        <a href="<?= base_url($revenue_patta_copy) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            ?>
                            <?php if (strlen($copy_of_chitha)) { ?>
                                <tr>
                                    <td>Copy of Chitha</td>
                                    <td style="font-weight:bold"><?= $copy_of_chitha_type ?></td>
                                    <td>
                                        <a href="<?= base_url($copy_of_chitha) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            ?>
                            <?php if (strlen($settlement_land_patta_copy)) { ?>
                                <tr>
                                    <td>Settelment Land Patta Copy</td>
                                    <td style="font-weight:bold"><?= $settlement_land_patta_copy_type ?></td>
                                    <td>
                                        <a href="<?= base_url($settlement_land_patta_copy) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            ?>
                            <?php if (strlen($land_revenue_receipt)) { ?>
                                <tr>
                                    <td>Land Revenue Receipt</td>
                                    <td style="font-weight:bold"><?= $land_revenue_receipt_type ?></td>
                                    <td>
                                        <a href="<?= base_url($land_revenue_receipt) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            ?>
                            <?php if (strlen($police_verification_report)) { ?>
                                <tr>
                                    <td>Police verification Report</td>
                                    <td style="font-weight:bold"><?= $police_verification_report_type ?></td>
                                    <td>
                                        <a href="<?= base_url($police_verification_report) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            ?>
                            <?php if (strlen($photocopy_of_existing_land_documents)) { ?>
                                <tr>
                                    <td>Photocopy of Existing Land Document</td>
                                    <td style="font-weight:bold"><?= $photocopy_of_existing_land_documents_type ?></td>
                                    <td>
                                        <a href="<?= base_url($photocopy_of_existing_land_documents) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            ?>
                            <?php if (strlen($no_dues_certificate_from_bank)) { ?>
                                <tr>
                                    <td>No dues Certificate from Bank</td>
                                    <td style="font-weight:bold"><?= $no_dues_certificate_from_bank_type ?></td>
                                    <td>
                                        <a href="<?= base_url($no_dues_certificate_from_bank) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            ?>
                            <?php if (strlen($last_time_paid_Land_revenue_receipt)) { ?>
                                <tr>
                                    <td>Last Time Paid Land Revenue Receipt</td>
                                    <td style="font-weight:bold"><?= $last_time_paid_Land_revenue_receipt_type ?></td>
                                    <td>
                                        <a href="<?= base_url($last_time_paid_Land_revenue_receipt) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
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
                    <a href="<?= base_url('spservices/kaac/registration/index/' . $obj_id) ?>" class="btn btn-primary">
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
                    <a href="<?= base_url('spservices/kaac/payment/initiate/' . $obj_id) ?>" class="btn btn-success">
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