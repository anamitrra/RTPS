<?php
$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;

$applicant_name = $dbrow->form_data->applicant_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$father_name = $dbrow->form_data->father_name;
$mobile = $dbrow->form_data->mobile;
$email = !empty($dbrow->form_data->email) ? $dbrow->form_data->email : 'NA';
$dob = $dbrow->form_data->dob;
$pan_no = !empty($dbrow->form_data->pan_no) ? $dbrow->form_data->pan_no : 'NA';
$aadhar_no = !empty($dbrow->form_data->aadhar_no) ? $dbrow->form_data->aadhar_no : 'NA';
$relationship = $dbrow->form_data->relationship;
$relativeName = $dbrow->form_data->relativeName;
$relationshipStatus = $dbrow->form_data->relationshipStatus;

$address_line1 = $dbrow->form_data->address_line1;
$address_line2 = !empty($dbrow->form_data->address_line2) ? $dbrow->form_data->address_line2 : 'NA';
$state = $dbrow->form_data->state;
$district = $dbrow->form_data->district;
$subdivision = $dbrow->form_data->subdivision;
$circle = $dbrow->form_data->revenuecircle;
$mouza = $dbrow->form_data->mouza;
$village = $dbrow->form_data->village;
$house_no = $dbrow->form_data->house_no;
$police_st = $dbrow->form_data->police_st;
$post_office = $dbrow->form_data->post_office;
$pin_code = $dbrow->form_data->pin_code;

$passport_photo_type = $dbrow->form_data->passport_photo_type ?? '';
$passport_photo = $dbrow->form_data->passport_photo ?? '';

$affidavit_type = $dbrow->form_data->affidavit_type ?? '';
$affidavit = $dbrow->form_data->affidavit ?? '';

$court_fee_type = $dbrow->form_data->court_fee_type ?? '';
$court_fee = $dbrow->form_data->court_fee ?? '';

$paymentreceipt_type = $dbrow->form_data->paymentreceipt_type ?? '';
$paymentreceipt = $dbrow->form_data->paymentreceipt ?? '';

$salaryslip_type = $dbrow->form_data->salaryslip_type ?? '';
$salaryslip = $dbrow->form_data->salaryslip ?? '';

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
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                <h4><b>Application for Bakijai Clearance Certificate<br>
                        ( বাকিজাই আদায়ৰ প্রমান পত্রৰ বাবে আবেদন ) <b></h4>
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

                <?php if ($this->session->flashdata('error') != null) { ?>
                    <script>
                        $(".frmbtn").show();
                    </script>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
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
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?= $mobile ?></strong></td>
                            </tr>
                            <tr>
                                <td>E-Mail / ই-মেইল<br><strong><?= $email ?></strong> </td>
                                <td>Date of Birth/ জন্মৰ তাৰিখ<br><strong><?= $dob ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Aadhar No./ আধাৰ কাৰ্ড নং <br><strong><?= $aadhar_no ?></strong> </td>
                                <td>PAN No./ পান কাৰ্ড নং<br><strong><?= $pan_no ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Relationship/ সম্পৰ্ক <br><strong><?= $relationship ?></strong> </td>
                                <td>Relative's Name/ আত্মীয়ৰ নাম<br><strong><?= $relationshipStatus . " " ?><?= $relativeName ?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Permanent Address/ স্থায়ী ঠিকনা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Address Line 1/ ঠিকনাৰ প্ৰথ্ম শাৰী<br><strong><?= $address_line1 ?></strong> </td>
                                <td>Address Line 2/ ঠিকনাৰ দ্বিতীয় শাৰী<br><strong><?= $address_line2 ?></strong> </td>
                            </tr>
                            <tr>
                                <td>State/ ৰাজ্য<br><strong><?= $state ?></strong> </td>
                                <td>District/ জিলা<br><strong><?= $district ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Sub Division/ মহকুমা <br><strong><?= $subdivision ?></strong> </td>
                                <td>Circle Office/ চক্ৰ<br><strong><?= $circle ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mouza/ মৌজা <br><strong><?= $mouza ?></strong> </td>
                                <td>Village/Town/ গাওঁ/চহৰ<br><strong><?= $village ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Police Station/ থানা<br><strong><?= $police_st ?></strong> </td>
                                <td>Post Office/ ডাকঘৰ <br><strong><?= $post_office ?></strong> </td>
                            </tr>
                            <tr>
                                <td>House No/ ঘৰ নং <br><strong><?= $house_no ?></strong> </td>
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
                                <td>Affidavit Regarding no Pending Bakijai case in Applicants name and in the name of his/her Father, Mother, Brothers, Sisters</td>
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
                            <tr>
                                <td>Scan copy of Court Fee Stamp</td>
                                <td style="font-weight:bold"><?= $court_fee_type ?></td>
                                <td>
                                    <?php if (strlen($court_fee)) { ?>
                                        <a href="<?= base_url($court_fee) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Scan copy of upto date and payment receipt</td>
                                <td style="font-weight:bold"><?= $paymentreceipt_type ?></td>
                                <td>
                                    <?php if (strlen($paymentreceipt)) { ?>
                                        <a href="<?= base_url($paymentreceipt) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                            <?php if (strlen($other_doc)) { ?>
                                <tr>
                                    <td>Any other document</td>
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
                    <a href="<?= base_url('spservices/bakijai/bakcl/index/' . $obj_id) ?>" class="btn btn-primary">
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
                    <a href="<?= base_url('spservices/bakijai/payment/initiate/' . $obj_id) ?>" class="btn btn-success">
                        <i class="fa fa-angle-double-right"></i> Make Payment
                    </a>
                <?php } ?>
                <!-- <?php //if (((//$user_type != 'user') && ($appl_status === 'DRAFT')) || ($appl_status === 'payment_initiated')) { 
                        ?>
                    <a href="<?php //base_url('spservices/bakijai/payment/initiate/' . $obj_id) 
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