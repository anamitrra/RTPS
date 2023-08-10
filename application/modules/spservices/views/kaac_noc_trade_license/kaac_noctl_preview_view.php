<?php

$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;
$pageTitle = $dbrow->service_data->service_name;
$pageTitleId = $dbrow->form_data->service_id;
$appl_status = $dbrow->service_data->appl_status;
$applicant_name = $dbrow->form_data->applicant_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$father_name = $dbrow->form_data->father_name;
// $mother_name = $dbrow->form_data->mother_name;
$mobile = $dbrow->form_data->mobile;
$email = !empty($dbrow->form_data->email) ? $dbrow->form_data->email : 'NA';

$address_proof_type = isset($dbrow->form_data->address_proof_type_text) ? $dbrow->form_data->address_proof_type_text : '';
$address_proof = isset($dbrow->form_data->address_proof) ? $dbrow->form_data->address_proof : '';
$identity_proof_type = isset($dbrow->form_data->identity_proof_type_text) ? $dbrow->form_data->identity_proof_type_text : '';
$identity_proof = isset($dbrow->form_data->identity_proof) ? $dbrow->form_data->identity_proof : '';
$house_tax_receipt_type = isset($dbrow->form_data->house_tax_receipt_type_text) ? $dbrow->form_data->house_tax_receipt_type_text : '';
$house_tax_receipt = isset($dbrow->form_data->house_tax_receipt) ? $dbrow->form_data->house_tax_receipt : '';
$business_reg_certificate_type = isset($dbrow->form_data->business_reg_certificate_type_text) ? $dbrow->form_data->business_reg_certificate_type_text : '';
$business_reg_certificate = isset($dbrow->form_data->business_reg_certificate) ? $dbrow->form_data->business_reg_certificate : '';

$mbtc_room_rent_deposit_type = isset($dbrow->form_data->mbtc_room_rent_deposit_type_text) ? $dbrow->form_data->mbtc_room_rent_deposit_type_text : '';;
$mbtc_room_rent_deposit = isset($dbrow->form_data->mbtc_room_rent_deposit) ? $dbrow->form_data->mbtc_room_rent_deposit : '';
$consideration_letter_type = isset($dbrow->form_data->consideration_letter_type_text) ? $dbrow->form_data->consideration_letter_type_text : '';
$consideration_letter = isset($dbrow->form_data->consideration_letter) ? $dbrow->form_data->consideration_letter : '';
$signature_type = isset($dbrow->form_data->signature_type_text) ? $dbrow->form_data->signature_type_text : '';;
$signature = isset($dbrow->form_data->signature) ? $dbrow->form_data->signature : '';
$passport_photo_type = isset($dbrow->form_data->passport_photo_type_text) ? $dbrow->form_data->passport_photo_type_text : '';
$passport_photo = isset($dbrow->form_data->passport_photo) ? $dbrow->form_data->passport_photo : '';

$place_of_business = isset($dbrow->form_data->place_of_business) ? $dbrow->form_data->place_of_business : '';
$class_of_business = isset($dbrow->form_data->class_of_business) ? $dbrow->form_data->class_of_business : '';
$firm_name = isset($dbrow->form_data->firm_name) ? $dbrow->form_data->firm_name : '';
$proprietor_name = isset($dbrow->form_data->proprietor_name) ? $dbrow->form_data->proprietor_name : '';
$community = isset($dbrow->form_data->community) ? $dbrow->form_data->community : '';
$reason = isset($dbrow->form_data->reason) ? $dbrow->form_data->reason : '';
$occupation_trade = isset($dbrow->form_data->occupation_trade) ? $dbrow->form_data->occupation_trade : '';
$address = isset($dbrow->form_data->address) ? $dbrow->form_data->address : '';
$room_occupied = isset($dbrow->form_data->room_occupied) ? $dbrow->form_data->room_occupied : '';
$room_occupied_text = isset($dbrow->form_data->room_occupied_text) ? $dbrow->form_data->room_occupied_text : '';
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
                অনাপত্তি বাণিজ্য অনুজ্ঞাপত্ৰ প্ৰদান
                        <b>

                </h4>
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
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Firm Details </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the Firm<br><strong><?= $firm_name ?></strong> </td>
                                <td>Name of the Proprietor<br><strong><?= $proprietor_name ?></strong> </td>
                                <td>Community<br><strong><?= $community ?></strong></td>

                            </tr>
                            <tr>
                                <td>Occupation/Trade<br><strong><?= $occupation_trade ?></strong></td>
                                <td>Place or places of business<br><strong><?= $place_of_business ?></strong></td>
                                <td>Class of Business<br><strong><?= $class_of_business ?></strong></td>

                            </tr>
                            <tr>
                                <td>Address<br><strong><?= $address ?></strong></td>
                                <td>Special reason for consideration(if any)<br><strong><?= $reason ?></strong></td>
                                <td>If room occupied<br><strong><?= $room_occupied_text ?></strong></td>
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
                            if (strlen($passport_photo)) {
                            ?>
                                <tr>
                                    <td>Passport Photo</td>
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
                            <?php } //End of if 
                            if (strlen($house_tax_receipt)) {
                            ?>
                                <tr>
                                    <td>House Tax Receipt</td>
                                    <td style="font-weight:bold"><?= $house_tax_receipt_type ?></td>
                                    <td>
                                        <?php if (strlen($house_tax_receipt)) { ?>
                                            <a href="<?= base_url($house_tax_receipt) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php }
                            ?>
                            <?php if (strlen($business_reg_certificate)) { ?>
                                <tr>
                                    <td>Copy of current Business Registration Certificate</td>
                                    <td style="font-weight:bold"><?= $business_reg_certificate_type ?></td>
                                    <td>
                                        <a href="<?= base_url($business_reg_certificate) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            ?>
                            <?php if (strlen($mbtc_room_rent_deposit)) { ?>
                                <tr>
                                    <td>Copy of Jamabandi</td>
                                    <td style="font-weight:bold"><?= $mbtc_room_rent_deposit_type ?></td>
                                    <td>
                                        <a href="<?= base_url($mbtc_room_rent_deposit) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            ?>
                            <?php if (strlen($consideration_letter)) { ?>
                                <tr>
                                    <td>Revenue Patta Copy</td>
                                    <td style="font-weight:bold"><?= $consideration_letter_type ?></td>
                                    <td>
                                        <a href="<?= base_url($consideration_letter) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            ?>
                            <?php if (strlen($signature)) { ?>
                                <tr>
                                    <td>Copy of Chitha</td>
                                    <td style="font-weight:bold"><?= $signature_type ?></td>
                                    <td>
                                        <a href="<?= base_url($signature) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </fieldset>
            </div>
            <!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <?php if ($appl_status === 'DRAFT') { ?>
                    <a href="<?= base_url('spservices/kaac_noc_trade_license/registration/index/' . $obj_id) ?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                <?php } ?>
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <?php if (($appl_status != 'QA') && ($appl_status != 'QS') && ($appl_status == 'DRAFT')) { ?>
                    <a href="<?= base_url('spservices/kaac_noc_trade_license/payment/initiate/' . $obj_id) ?>" class="btn btn-success">
                        <i class="fa fa-angle-double-right"></i> Make Payment
                    </a>
                <?php } ?>
            </div>
            <!--End of .card-footer-->
        </div>
        <!--End of .card-->
    </div>
    <!--End of .container-->
</main>