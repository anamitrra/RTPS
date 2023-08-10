<?php
$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;
$pageTitle = $dbrow->service_data->service_name;
$pageTitleId = $dbrow->form_data->service_id;
$applicant_name = $dbrow->form_data->first_name . " " . $dbrow->form_data->last_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$father_name = $dbrow->form_data->father_name;
$mobile = $dbrow->form_data->mobile;
$email = !empty($dbrow->form_data->email) ? $dbrow->form_data->email : 'NA';

$circle_office = $dbrow->form_data->circle_office_name;
$mouza_name = $dbrow->form_data->mouza_office_name;
$revenue_village = $dbrow->form_data->revenue_village_name;
$p_police_st = $dbrow->form_data->police_station_name;
$p_post_office = $dbrow->form_data->post_office;

$applicant_category = $dbrow->form_data->applicant_category;
$applicant_category_text = $dbrow->form_data->applicant_category_text;

$address_proof_type = isset($dbrow->form_data->address_proof_type) ? $dbrow->form_data->address_proof_type : '';
$address_proof = isset($dbrow->form_data->address_proof) ? $dbrow->form_data->address_proof : '';
$identity_proof_type = isset($dbrow->form_data->identity_proof_type) ? $dbrow->form_data->identity_proof_type : '';
$identity_proof = isset($dbrow->form_data->identity_proof) ? $dbrow->form_data->identity_proof : '';
$land_patta_copy_type = isset($dbrow->form_data->land_patta_copy_type) ? $dbrow->form_data->land_patta_copy_type : '';
$land_patta_copy = isset($dbrow->form_data->land_patta_copy) ? $dbrow->form_data->land_patta_copy : '';
$updated_land_revenue_receipt_type = isset($dbrow->form_data->updated_land_revenue_receipt_type) ? $dbrow->form_data->updated_land_revenue_receipt_type : '';
$updated_land_revenue_receipt = isset($dbrow->form_data->updated_land_revenue_receipt) ? $dbrow->form_data->updated_land_revenue_receipt : '';
$salary_slip_type = isset($dbrow->form_data->salary_slip_type) ? $dbrow->form_data->salary_slip_type : '';;
$salary_slip = isset($dbrow->form_data->salary_slip) ? $dbrow->form_data->salary_slip : '';;
$other_doc_type = isset($dbrow->form_data->other_doc_type) ? $dbrow->form_data->other_doc_type : '';;
$other_doc = isset($dbrow->form_data->other_doc) ? $dbrow->form_data->other_doc : '';
$appl_status = $dbrow->service_data->appl_status;

$periodic_patta_no = isset($dbrow->form_data->periodic_patta_no) ? $dbrow->form_data->periodic_patta_no : '';
$dag_no = $dbrow->form_data->dag_no ?? set_value("dag_no");
$land_area_bigha = $dbrow->form_data->land_area_bigha ?? set_value("land_area_bigha");
$land_area_katha = $dbrow->form_data->land_area_katha ?? set_value("land_area_katha");
$land_area_loosa = $dbrow->form_data->land_area_loosa ?? set_value("land_area_loosa");
$land_area_sq_ft = $dbrow->form_data->land_area_sq_ft ?? set_value("land_area_sq_ft");
$patta_type = $dbrow->form_data->patta_type ?? set_value("patta_type");

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
                Application Ref. No: <?= $appl_ref_no ?>
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
                            if (strlen($salary_slip)) { ?>
                                <tr>
                                    <td>Salary Slip</td>
                                    <td style="font-weight:bold"><?= $salary_slip_type ?></td>
                                    <td>
                                        <?php if (strlen($salary_slip)) { ?>
                                            <a href="<?= base_url($salary_slip) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
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
                                    <td>Other Document</td>
                                    <td style="font-weight:bold"><?= $other_doc_type ?></td>
                                    <td>
                                        <a href="<?= base_url($other_doc) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
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
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
            <!--End of .card-footer-->
        </div>
        <!--End of .card-->
    </div>
    <!--End of .container-->
</main>