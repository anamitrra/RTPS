<?php
$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;

$applicant_name = $dbrow->form_data->applicant_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$mobile = $dbrow->form_data->mobile;
$email = $dbrow->form_data->email;

$spouse_name =  $dbrow->form_data->spouse_name;
$dob = $dbrow->form_data->dob;
$father_name = $dbrow->form_data->father_name;
$identification_mark = isset($dbrow->form_data->identification_mark) ? $dbrow->form_data->identification_mark : '';
$occupation = $dbrow->form_data->occupation;
$blood_group = $dbrow->form_data->blood_group;
$service_type = $dbrow->form_data->service_type;
$pan_no = $dbrow->form_data->pan_no;
$aadhar_no = $dbrow->form_data->aadhar_no;

$address_line1 = $dbrow->form_data->address_line1;
$address_line2 = $dbrow->form_data->address_line2;
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
$landline_no = $dbrow->form_data->landline_no;

$caste = $dbrow->form_data->caste;
$ex_serviceman = $dbrow->form_data->ex_serviceman;
$minority = $dbrow->form_data->minority;
$under_bpl = $dbrow->form_data->under_bpl;
$allowance = $dbrow->form_data->allowance;
$allowance_details = $dbrow->form_data->allowance == "Yes" ? $dbrow->form_data->allowance_details : 'NA';

$passport_photo_type = $dbrow->form_data->passport_photo_type ?? '';
$passport_photo = $dbrow->form_data->passport_photo ?? '';
$proof_of_retirement_type = $dbrow->form_data->proof_of_retirement_type ?? '';
$proof_of_retirement = $dbrow->form_data->proof_of_retirement ?? '';

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
            <h4><b>Application for Senior Citizen Certificate<br>
                            ( জ্যেষ্ঠ নাগৰিকৰ প্ৰমানপত্ৰৰ বাবে আবেদন )<b></h4>
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
                                <td>Name of Spouse/ পৰিবাৰ/স্বামীৰ নাম <br><strong><?= $spouse_name ?></strong> </td>
                                <td>Date of Birth/ জন্মৰ তাৰিখ<br><strong><?= $dob ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Father's Name/ পিতাৰ নাম <br><strong><?= $father_name ?></strong> </td>
                                <td>Identification Mark/ চিনাক্তকৰণৰ চিহ্ন<br><strong><?= $identification_mark ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Occupation/ বৃতি <br><strong><?= $occupation ?></strong> </td>
                                <td>Blood Group/ তেজৰ গ্ৰূপ<br><strong><?= $blood_group ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Service Type/ সেৱাৰ প্ৰকাৰ <br><strong><?= $service_type ?></strong> </td>
                                <td>PAN No./ পান কাৰ্ড নং<br><strong><?= $pan_no ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Aadhar No./ আধাৰ কাৰ্ড নং <br><strong><?= $aadhar_no ?></strong> </td>
                                <td></td>
                            </tr>

                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Applicant's Address/ আবেদনকাৰীৰ ঠিকনা </legend>
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
                                <td>House No/ ঘৰ নং <br><strong><?= $house_no ?></strong> </td>
                                <td>Police Station/ থানা<br><strong><?= $police_st ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Post Office/ ডাকঘৰ <br><strong><?= $post_office ?></strong> </td>
                                <td>Pin Code/ পিনক'ড (e.g 78xxxx)<br><strong><?= $pin_code ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Landline Number/ দুৰভাষ (if any) <br><strong><?= $landline_no ?></strong> </td>
                                <td></td>
                            </tr>

                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Other Details/ অন্যান্য তথ্য </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Caste/ জাতি<br><strong><?= $caste ?></strong> </td>
                                <td>Ex-Serviceman/ প্ৰাক্তন সেৱা বিষয়া<br><strong><?= $ex_serviceman ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Minority/ সংখ্যা লঘু<br><strong><?= $minority ?></strong> </td>
                                <td>Is Falling Under BPL/ দৰিদ্ৰ সীমাৰেখাৰ ভিতৰত অন্তৰ্ভুক্ত নেকি ?<br><strong><?= $under_bpl ?></strong> </td>
                            </tr>
                            <tr>
                                <td>Getting any Allowance(Pension)/ কিবা ভাট্টা পায় নেকি ? <br><strong><?= $allowance ?></strong> </td>
                                <td>Allowance Details/ ভাট্টা সবিশেষ<br><strong><?= $allowance_details ?></strong> </td>
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
                                <td>Passport size photograph.</td>
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
                                <td>Proof Of Retirement(for Govt. Servants) or Copy of 1966 Voter list (for other than - Govt. servant)</td>
                                <td style="font-weight:bold"><?= $proof_of_retirement_type ?></td>
                                <td>
                                    <?php if (strlen($proof_of_retirement)) { ?>
                                        <a href="<?= base_url($proof_of_retirement) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Age Proof</td>
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