<?php
$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;

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

$passport_photo_type = $dbrow->form_data->passport_photo_type ?? '';
$passport_photo = $dbrow->form_data->passport_photo ?? '';

$signature_type = $dbrow->form_data->signature_type ?? '';
$signature = $dbrow->form_data->signature ?? '';

$ug_pg_diploma_type = $dbrow->form_data->ug_pg_diploma_type ?? '';
$ug_pg_diploma = $dbrow->form_data->ug_pg_diploma ?? '';

$prc_type = $dbrow->form_data->prc_type ?? '';
$prc = $dbrow->form_data->prc ?? '';

$mbbs_certificate_type = $dbrow->form_data->mbbs_certificate_type ?? '';
$mbbs_certificate = $dbrow->form_data->mbbs_certificate ?? '';

$noc_dme_type = $dbrow->form_data->noc_dme_type ?? '';
$noc_dme = $dbrow->form_data->noc_dme ?? '';

$seat_allt_letter_type = $dbrow->form_data->seat_allt_letter_type ?? '';
$seat_allt_letter = $dbrow->form_data->seat_allt_letter ?? '';

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
                <h4><b>Application Form For No Objection Certificate - ACMR<br>
                        ( অনাপত্তি প্ৰমাণপত্ৰৰ বাবে আবেদন পত্ৰ - এচিএমআৰ) <b></h4>
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
                                <td>Date of Birth/ জন্মৰ তাৰিখ<br><strong><?= $dob ?></strong> </td>

                            </tr>
                            <tr>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?= $mobile ?></strong></td>
                                <td>E-Mail / ই-মেইল<br><strong><?= $email ?></strong> </td>
                            </tr>
                            <tr>
                                <td>PAN No./ পান কাৰ্ড নং<br><strong><?= $pan_no ?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Permanent Address/ স্থায়ী ঠিকনা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Permanent Address/ স্থায়ী ঠিকনা<br><strong><?= $permanent_address ?></strong> </td>
                                <td>Correspondence Address/ যোগাযোগ ঠিকনা<br><strong><?= $correspondence_address ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Permanent Registration No/ স্থায়ী পঞ্জীয়ন নং<br><strong><?= $permanent_reg_no ?></strong> </td>
                                <td>Permanent Registration Date/ স্থায়ী পঞ্জীয়নৰ তাৰিখ<br><strong><?= $permanent_reg_date ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Additional Degree Registration No/ অতিৰিক্ত ডিগ্ৰীৰ পঞ্জীয়ন নং <br><strong><?= $additional_degree_reg_no ?></strong> </td>
                                <td>Additional Degree Registration Date/ অতিৰিক্ত ডিগ্ৰীৰ পঞ্জীয়নৰ তাৰিখ<br><strong><?= $additional_degree_reg_date ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Name of the Relocating State Medical Council/ স্থানান্তৰিত ৰাজ্যিক চিকিৎসা পৰিষদৰ নাম <br><strong><?= $registering_smc ?></strong> </td>
                                <td>Relocation Reason/ স্থানান্তৰৰ কাৰণ<br><strong><?= $relocating_reason ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Working Place Address/ কৰ্মস্থলীৰ ঠিকনা<br><strong><?= $working_place_add ?></strong> </td>
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
                            <tr>
                                <td>Certificate of UG/PG/ Diploma</td>
                                <td style="font-weight:bold"><?= $ug_pg_diploma_type ?></td>
                                <td>
                                    <?php if (strlen($ug_pg_diploma)) { ?>
                                        <a href="<?= base_url($ug_pg_diploma) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Permanent Registration certificate of Assam Medical Council</td>
                                <td style="font-weight:bold"><?= $prc_type ?></td>
                                <td>
                                    <?php if (strlen($prc)) { ?>
                                        <a href="<?= base_url($prc) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                            <?php if (strlen($mbbs_certificate)) { ?>
                                <tr>
                                    <td>Rural completion certificate (MBBS) if bond signed with Govt.</td>
                                    <td style="font-weight:bold"><?= $mbbs_certificate_type ?></td>
                                    <td>
                                        <?php if (strlen($mbbs_certificate)) { ?>
                                            <a href="<?= base_url($mbbs_certificate) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            ?>
                            <?php if (strlen($noc_dme)) { ?>
                                <tr>
                                    <td>NOC from Directorate of Medical Education (only for PG Holders under
                                        Bond)</td>
                                    <td style="font-weight:bold"><?= $noc_dme_type ?></td>
                                    <td>
                                        <?php if (strlen($noc_dme)) { ?>
                                            <a href="<?= base_url($noc_dme) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php } //End of if 
                            ?>
                            <?php if (strlen($seat_allt_letter)) { ?>
                                <tr>
                                    <td>Provisional Seat Allotment letter for All India Quota doctors</td>
                                    <td style="font-weight:bold"><?= $seat_allt_letter_type ?></td>
                                    <td>
                                        <?php if (strlen($seat_allt_letter)) { ?>
                                            <a href="<?= base_url($seat_allt_letter) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
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
                </fieldset>
            </div>
            <!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <?php if ($appl_status === 'DRAFT') { ?>
                    <a href="<?= base_url('spservices/acmrnoc/noc/index/' . $obj_id) ?>" class="btn btn-primary">
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
                    <a href="<?= base_url('spservices/acmrnoc/payment/initiate/' . $obj_id) ?>" class="btn btn-success">
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