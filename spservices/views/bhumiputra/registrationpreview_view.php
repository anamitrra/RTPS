<?php
$obj_id = $dbrow->{'_id'}->{'$id'};
$rtps_trans_id = $dbrow->service_data->rtps_trans_id;

$application_for = $dbrow->form_data->application_for;

$applicant_name = $dbrow->form_data->applicant_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$mobile = $dbrow->form_data->mobile; //set_value("mobile");
$pan_no = $dbrow->form_data->pan_no;
$email = $dbrow->form_data->email;
$epic_no = $dbrow->form_data->epic_no;
$date = $dbrow->form_data->date;
$fatherName = $dbrow->form_data->fatherName;
$motherName = $dbrow->form_data->motherName;
$husbandName = $dbrow->form_data->husbandName;
$addressLine1 = $dbrow->form_data->addressLine1;
$addressLine2 = $dbrow->form_data->addressLine2;
$village = $dbrow->form_data->village;
$mouza = $dbrow->form_data->mouza;
$postOffice = $dbrow->form_data->postOffice;
$policeStation = $dbrow->form_data->policeStation;
$pinCode = $dbrow->form_data->pinCode;
$resState = $dbrow->form_data->resState;
$resAddressLine1 = $dbrow->form_data->resAddressLine1;
$resAddressLine2 = $dbrow->form_data->resAddressLine2;
$resVillageTown = $dbrow->form_data->resVillageTown;
$resDistrict = $dbrow->form_data->resDistrict;
$resSubdivision = $dbrow->form_data->resSubdivision;
$resCircleOffice = $dbrow->form_data->resCircleOffice;
$resMouza = $dbrow->form_data->resMouza;
$resPostOffice = $dbrow->form_data->resPostOffice;
$resPoliceStation = $dbrow->form_data->resPoliceStation;
$resPinCode = $dbrow->form_data->resPinCode;
$applicantCaste = $dbrow->form_data->applicantCaste;
$applicantSubCaste = $dbrow->form_data->applicantSubCaste;
$applicant_photo = $dbrow->form_data->applicant_photo ?? '';
$applicant_photo_type = $dbrow->form_data->applicant_photo_type ?? '';
$proof_dob  = $dbrow->form_data->proof_dob ?? '';
$proof_dob_type  = $dbrow->form_data->proof_dob_type  ?? '';
$proof_res  = $dbrow->form_data->proof_res  ?? '';
$proof_res_type  = $dbrow->form_data->proof_res_type  ?? '';
$caste_certificate = $dbrow->form_data->caste_certificate ?? '';
$caste_certificate_type = $dbrow->form_data->caste_certificate_type ?? '';
$recommendation_certificate = $dbrow->form_data->recommendation_certificate ?? '';
$recommendation_certificate_type = $dbrow->form_data->recommendation_certificate_type ?? '';
$other_doc = $dbrow->form_data->other_doc ?? '';
$other_doc_type = $dbrow->form_data->other_doc_type ?? '';
$soft_copy_type = $dbrow->form_data->soft_copy_type ?? '';
$soft_copy = $dbrow->form_data->soft_copy ?? '';

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
        <div class="card shadow-sm" id="printDiv" style="background:#E6F1FF">
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                Application for Issuance Of Caste Certificate<br>
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
                    <strong style="font-size:16px; ">Supporting Document / সহায়ক নথি পত্ৰ</strong>

                    <ol style="list-style:decimal-leading-zero;  margin-left: 24px; margin-top: 20px">
                        <li>Applicant's Photo</li>
                        <li>Proof of Date of Birth(One of Birth Certificate/Aadhar Card/PAN/Admit Card issued by any recognized Board of Applicant)
                        </li>
                        <li>Proof of Residence (One of Permanent Resident Certificate/Aadhar Card/EPIC/Land Document/Electricity Bill,Ration Card of Applicant or Parent)
                        </li>
                        <li> Caste certificate of father / Recommendation of authorize caste/tribe/community organization notified by state Government

                        </li>
                        <li>Any other document ( Voter List, Affidavit ,Existing Caste Certificate etc. )
                        </li>
                        <li>Upload Scanned Copy of the Application Form </li>
                    </ol>

                    <strong style="font-size:16px;  margin-top: 10px">Fees / মাচুল :</strong>
                    <ol style="list-style:decimal-leading-zero;  margin-left: 24px; margin-top: 10px">
                        <li>Statutory charges / স্হায়ী মাচুল : NIL</li>
                        <li>Service charge / সেৱা মাচুল – Rs. 30 / ৩০ টকা</li>
                        <li>Printing charge (in case of any printing from PFC) / ছপা খৰচ - Rs. 10 Per Page / প্ৰতি পৃষ্ঠাত ১০ টকা</li>
                        <li>Scanning charge (in case documents are scanned in PFC) স্কেনিং খৰচ - Rs. 5 Per page / প্ৰতি পৃষ্ঠা ৫ টকা</li>
                    </ol>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Application For : </td>
                                <td style="font-weight:bold"><?= $application_for ?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td>Applicant&apos;s Name : </td>
                                <td style="font-weight:bold"><?= $applicant_name ?></td>
                            </tr>
                            <tr>
                                <td>Applicant Gender: </td>
                                <td style="font-weight:bold"><?= $applicant_gender ?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td>Mobile Number: </td>
                                <td style="font-weight:bold"><?= $mobile ?></td>
                                <td>&nbsp;</td>

                            </tr>
                            <tr>
                                <td>E-Mail : </td>
                                <td style="font-weight:bold"><?= $email ?></td>
                                <td>&nbsp;</td>
                                <td>Date of Birth : </td>
                                <td style="font-weight:bold"><?= $date ?></td>
                                <td>&nbsp;</td>

                            </tr>
                            <tr>
                                <td>Applicant’ s Caste/Tribe/Community: </td>
                                <td style="font-weight:bold"><?= $applicantCaste ?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td>Sub-Category : </td>
                                <td style="font-weight:bold"><?= $applicantSubCaste ?></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Mother's Name : </td>
                                <td style="font-weight:bold"><?= $motherName ?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td>EPIC No : </td>
                                <td style="font-weight:bold"><?= $epic_no ?></td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Address </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:25%">Address Line 1 : </td>
                                <td style="width:23%; font-weight:bold"><?= $resAddressLine1 ?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td style="width:25%">Address Line 2: </td>
                                <td style="width:23%; font-weight:bold"><?= $resAddressLine2 ?></td>
                            </tr>
                            <tr>

                                <td style="width:25%">State : </td>
                                <td style="width:23%; font-weight:bold"><?= $resState ?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td style="width:25%">District: </td>
                                <td style="width:23%; font-weight:bold"><?= $resDistrict ?></td>

                            </tr>
                            <tr>
                                <td style="width:25%">Subdivision : </td>
                                <td style="width:23%; font-weight:bold"><?= $resSubdivision ?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td style="width:25%">Circle Office: </td>
                                <td style="width:23%; font-weight:bold"><?= $resCircleOffice ?></td>
                            </tr>
                            <tr>
                                <td style="width:25%">Mouza : </td>
                                <td style="width:23%; font-weight:bold"><?= $resMouza ?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td style="width:25%">Village: </td>
                                <td style="width:23%; font-weight:bold"><?= $resVillageTown ?></td>
                            </tr>
                            <tr>
                                <td style="width:25%">Police Station : </td>
                                <td style="width:23%; font-weight:bold"><?= $resPoliceStation ?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td style="width:25%">Post Office: </td>
                                <td style="width:23%; font-weight:bold"><?= $resPostOffice ?></td>
                            </tr>
                            <tr>
                                <td style="width:25%">Pin Code : </td>
                                <td style="width:23%; font-weight:bold"><?= $resPinCode ?></td>
                                <td style="width:4%">&nbsp;</td>

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
                                <td>Applicant's Photo<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?= $applicant_photo_type ?></td>
                                <td>
                                    <?php if (strlen($applicant_photo)) { ?>
                                        <a href="<?= base_url($applicant_photo) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Proof of Date of Birth <span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?= $proof_dob_type ?></td>
                                <td>
                                    <?php if (strlen($proof_dob)) { ?>
                                        <a href="<?= base_url($proof_dob) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Caste certificate of father or Recommendation of authorized caste/tribe/community organization notified by State Government</td>
                                <td style="font-weight:bold"><?= $caste_certificate_type ?></td>
                                <td>
                                    <?php if (strlen($caste_certificate)) { ?>
                                        <a href="<?= base_url($caste_certificate) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>







                            <tr>
                                <td>Other document as per requirement</td>
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
                            <tr>
                                <td>Upload the Soft copy of the applicant form<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?= $soft_copy_type ?></td>
                                <td>
                                    <?php if (strlen($soft_copy)) { ?>
                                        <a href="<?= base_url($soft_copy) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
            </div>
            <!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <a href="<?= base_url('spservices/bhumiputra/registration/index/' . $obj_id) ?>" class="btn btn-primary">
                    <i class="fa fa-pencil"></i> Edit
                </a>
                <button class="btn btn-warning" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <a href="<?= base_url('spservices/bhumiputra/payment/initiate/' . $obj_id) ?>" class="btn btn-success">
                    <i class="fa fa-angle-double-right"></i> Make Payment
                </a>
            </div>
            <!--End of .card-footer-->
        </div>
        <!--End of .card-->
    </div>
    <!--End of .container-->
</main>