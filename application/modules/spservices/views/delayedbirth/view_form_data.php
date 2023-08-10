<?php
$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;

$applicant_name = $dbrow->form_data->applicant_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$mobile = $dbrow->form_data->mobile;
$email = $dbrow->form_data->email;
$pan_no = $dbrow->form_data->pan_no;
$aadhar_no = $dbrow->form_data->aadhar_no;
$newborn_relation =  $dbrow->form_data->newborn_relation;
$other_relation = $dbrow->form_data->newborn_relation == "Other" ? $dbrow->form_data->other_relation : 'NA';

$newborn_name = $dbrow->form_data->newborn_name;
$dob = $dbrow->form_data->dob;
$newborn_gender = $dbrow->form_data->newborn_gender;
$father_name = $dbrow->form_data->father_name;
$mother_name = $dbrow->form_data->mother_name;
$newborn_birthplace = $dbrow->form_data->newborn_birthplace;
$hospital_name = $dbrow->form_data->newborn_birthplace == "Hospital" ? $dbrow->form_data->hospital_name : 'NA';
$address_hospital = $dbrow->form_data->newborn_birthplace != "Other" ? $dbrow->form_data->address_hospital : 'NA';
$other_placeofbirth = $dbrow->form_data->newborn_birthplace == "Other" ? $dbrow->form_data->other_placeofbirth : 'NA';
$late_reason = $dbrow->form_data->late_reason;

$state = $dbrow->form_data->state;
$district = $dbrow->form_data->district;
$subdivision = $dbrow->form_data->subdivision;
$circle = $dbrow->form_data->revenuecircle;
$village = $dbrow->form_data->village;
$pin_code = $dbrow->form_data->pin_code;

$affidavit_type = $dbrow->form_data->affidavit_type ?? '';
$affidavit = $dbrow->form_data->affidavit ?? '';

$age_proof_type = $dbrow->form_data->age_proof_type ?? '';
$age_proof = $dbrow->form_data->age_proof ?? '';

$address_proof_type = $dbrow->form_data->address_proof_type ?? '';
$address_proof = $dbrow->form_data->address_proof ?? '';

$other_doc_type = $dbrow->form_data->other_doc_type ?? '';
$other_doc = $dbrow->form_data->other_doc ?? '';

$soft_copy_type = $dbrow->form_data->soft_copy_type ?? '';
$soft_copy = $dbrow->form_data->soft_copy ?? '';

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
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
            <h4><b>Application Form For Permission For Delayed Birth Registration<br>
                            ( পলমকৈ জন্ম পঞ্জীয়নৰ অনুমতিৰ বাবে আবেদন প্ৰ-পত্ৰ )<b></h4>
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
                Application Ref. No: <?=$appl_ref_no?>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the Applicant/আবেদনকাৰীৰ নাম<br><strong><?= $applicant_name ?></strong> </td>
                                <td>Applicant's Gender/ আবেদনকাৰীৰ লিংগ <br><strong><?= $applicant_gender ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?= $mobile ?></strong> </td>
                                <td>E-Mail / ই-মেইল<br><strong><?= $email ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Relation with New Born/ নৱজাতকৰ লগত সম্বন্ধ <br><strong><?= $newborn_relation ?></strong> </td>
                                <td>Enter Other Relation(if any)/ অন্য সম্পৰ্ক (যদি থাকে) <br><strong><?= $other_relation ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Aadhar No./ আধাৰ কাৰ্ড নং <br><strong><?= $aadhar_no ?></strong> </td>
                                <td>PAN No./ পান কাৰ্ড নং<br><strong><?= $pan_no ?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">New Born Details/ নৱজাতকৰ বিৱৰন </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the New Born/ নৱজাতকৰ নাম<br><strong><?= $newborn_name ?></strong> </td>
                                <td>Date of Birth/ জন্মৰ তাৰিখ<br><strong><?= $dob ?></strong> </td>
                            </tr>
                            <tr>
                                <td>New Born Gender/ নৱজাতকৰ লিংগ <br><strong><?= $newborn_gender ?></strong> </td>
                                <td>Father's Name/ পিতৃৰ নাম<br><strong><?= $father_name ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mother's Name/ মাতৃৰ নাম <br><strong><?= $mother_name ?></strong> </td>
                                <td>Place of Birth of the New Born/ নৱজাতকৰ জন্মস্থান<br><strong><?= $newborn_birthplace ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Name of Hospital/ চিকিৎসালয়ৰ নাম <br><strong><?= $hospital_name ?></strong> </td>
                                <td>Address of Home/Hospital/ ঘৰ/ চিকিৎসালয়ৰ ঠিকনা<br><strong><?= $address_hospital ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Place of Birth (if any)/ জন্মস্থান (যদি আছে) <br><strong><?= $other_placeofbirth ?></strong> </td>
                                <td>Reason for Being Late/ পলম হোৱাৰ কাৰণ<br><strong><?= $late_reason ?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success" style="margin-top:40px;">
                    <legend class="h5">Other Details/ অন্যান্য তথ্য </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>State/ ৰাজ্য<br><strong><?= $state ?></strong> </td>
                                <td>District/ জিলা<br><strong><?= $district ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Sub Division/ মহকুমা <br><strong><?= $subdivision ?></strong> </td>
                                <td>Circle Office/ চক্ৰ<br><strong><?= $circle ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Village/Town/ গাওঁ/চহৰ<br><strong><?= $village ?></strong> </td>
                                <td>Pin Code/ পিনক'ড (e.g 78xxxx)<br><strong><?= $pin_code ?></strong> </td>
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
                                <td>Register Hospital Govt. / Pvt. Certificate regarding Birth or Age Proof (any)</td>
                                <td style="font-weight:bold"><?= $age_proof_type ?></td>
                                <td>
                                    <?php if (strlen($age_proof)) { ?>
                                        <a href="<?= base_url($age_proof) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>School Certificate/Admit Card (for age 6 and above) or parent's details</td>
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
                            <tr>
                                <td>Affidavit</td>
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
                            <?php if (strlen($other_doc)) { ?>
                                <tr>
                                    <td>Other Documents</td>
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