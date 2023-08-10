<?php
$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;

$language = $dbrow->form_data->fillUpLanguage;

$application_for = $dbrow->form_data->application_for;
$applicant_name = $dbrow->form_data->applicant_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$mobile = $dbrow->form_data->mobile; //set_value("mobile");
$pan_no = !empty($dbrow->form_data->pan_no)? $dbrow->form_data->pan_no: "NA";
$email = !empty($dbrow->form_data->email)? $dbrow->form_data->email: "NA";
$epic_no = !empty($dbrow->form_data->epic_no)? $dbrow->form_data->epic_no: "NA";
$dob = $dbrow->form_data->dob;
$aadhar_no = !empty($dbrow->form_data->aadhar_no)? $dbrow->form_data->aadhar_no: "NA";
//$religion = $dbrow->form_data->religion;
$caste = $dbrow->form_data->caste;
$subcaste = $dbrow->form_data->subcaste;
$father_name = $dbrow->form_data->father_name;
$mother_name = $dbrow->form_data->mother_name;
//$husband_name = !empty($dbrow->form_data->husband_name)? $dbrow->form_data->husband_name: "NA";


$address_line_1 = !empty($dbrow->form_data->address_line_1)? $dbrow->form_data->address_line_1: "NA";
$address_line_2 = !empty($dbrow->form_data->address_line_2)? $dbrow->form_data->address_line_2: "NA";
$house_no = $dbrow->form_data->house_no;
$state = $dbrow->form_data->state;
$district = $dbrow->form_data->district;
$sub_division = $dbrow->form_data->sub_division;
$circle_office = $dbrow->form_data->circle_office;
$mouza = $dbrow->form_data->mouza;
$village_town = $dbrow->form_data->village_town;
$police_station = $dbrow->form_data->police_station;
$post_office = $dbrow->form_data->post_office;
$pin_code = $dbrow->form_data->pin_code;

$photo = $dbrow->form_data->photo ?? '';
$photo_type = $dbrow->form_data->photo_type ?? '';
$date_of_birth  = $dbrow->form_data->date_of_birth ?? '';
$date_of_birth_type  = $dbrow->form_data->date_of_birth_type  ?? '';
$proof_of_residence  = $dbrow->form_data->proof_of_residence  ?? '';
$proof_of_residence_type  = $dbrow->form_data->proof_of_residence_type  ?? '';
$caste_certificate_of_father = $dbrow->form_data->caste_certificate_of_father ?? '';
$recomendation_certificate = $dbrow->form_data->recomendation_certificate ?? '';

$caste_certificate_of_father_type = $dbrow->form_data->caste_certificate_of_father_type ?? '';
$recomendation_certificate_type = $dbrow->form_data->recomendation_certificate_type ?? '';
$others_type = $dbrow->form_data->others_type??'';
$others = $dbrow->form_data->others??'';
$soft_copy_type = $dbrow->form_data->soft_copy_type??'';
$soft_copy = $dbrow->form_data->soft_copy??'';
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
                <?=$service_name?><br>
                ( জাতিপ্ৰমাণপত্ৰৰ বাবে আবেদন )
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
                Application Ref. No: <?=$appl_ref_no?>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Language of the certificate /প্ৰমাণপত্ৰৰ ভাষা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Language/ ভাষা<br><strong><?=$language?></strong> </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Application For/ আবেদনৰ বাবে<br><strong><?=$application_for?></strong> </td>
                                <td>Name of the Applicant/আবেদনকাৰীৰ নাম <br><strong><?=$applicant_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>Gender/ লিংগ<br><strong><?=$applicant_gender?></strong> </td>
                                <td>Mobile Number / দুৰভাষ ( মবাইল ) <br><strong><?=$mobile?></strong> </td>
                            </tr>
                            <tr>
                                <td>PAN No./ পেন নং<br><strong><?=$pan_no?></strong> </td>
                                <td>E-Mail / ই-মেইল <br><strong><?=$email?></strong> </td>
                            </tr>
                            <tr>
                                <td>EPIC No./ ইপিআইচি নম্বৰ<br><strong><?=$epic_no?></strong> </td>
                                <td>Date of Birth/ জন্মৰ তাৰিখ <br><strong><?=$dob?></strong> </td>
                            </tr>
                            <tr>
                                <td>Aadhar No./আধাৰ নং<br><strong><?=$aadhar_no?></strong> </td>
                                <td>Caste/Tribe/Community/ জাতি/জনজাতি/সম্প্ৰদায়<br><strong><?=$caste?></strong> </td>
                            </tr>
                            <tr>
                                <td>Sub-Caste/ উপ-জাতি <br><strong><?=$subcaste?></strong> </td>
                                <td>Fathers Name/পিতাৰ নাম<br><strong><?=$father_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mother Name/মাতৃৰ নাম <br><strong><?=$mother_name?></strong> </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Address of the Applicant/ আবেদনকাৰীৰ ঠিকনা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Address Line 1/ ঠিকনা ৰেখা ১<br><strong><?=$address_line_1?></strong> </td>
                                <td>Address Line 2/ ঠিকনা ৰেখা ২<br><strong><?=$address_line_2?></strong> </td>
                            </tr>
                            <tr>
                                <td>House No/ ঘৰ নং<br><strong><?=$house_no?></strong> </td>
                                <td>State/ ৰাজ্য <br><strong><?=$state?></strong> </td>
                            </tr>
                            <tr>
                                <td>District/ জিলা<br><strong><?=$district?></strong> </td>
                                <td>Sub-Division/ মহকুমা <br><strong><?=$sub_division?></strong> </td>
                            </tr>
                            <tr>
                                <td>Circle Office/ ৰাজহ চক্ৰ<br><strong><?=$circle_office?></strong> </td>
                                <td>Mouza/ মৌজা <br><strong><?=$mouza?></strong> </td>
                            </tr>
                            <tr>
                                <td>Village/ Town/ গাওঁ/চহৰ<br><strong><?=$village_town?></strong> </td>
                                <td>Police Station/ আৰক্ষী থানা <br><strong><?=$police_station?></strong> </td>
                            </tr>
                            <tr>
                                <td>Post Office/ ডাকঘৰ<br><strong><?=$post_office?></strong> </td>
                                <td>Pin Code/ পিন কোড <br><strong><?=$pin_code?></strong> </td>
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
                                <td style="font-weight:bold"><?= $photo_type ?></td>
                                <td>
                                    <?php if (strlen($photo)) { ?>
                                        <a href="<?= base_url($photo) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Proof of Date of Birth <span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?= $date_of_birth_type ?></td>
                                <td>
                                    <?php if (strlen($date_of_birth)) { ?>
                                        <a href="<?= base_url($date_of_birth) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Proof of Residence <span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?= $proof_of_residence_type ?></td>
                                <td>
                                    <?php if (strlen($proof_of_residence)) { ?>
                                        <a href="<?= base_url($proof_of_residence) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>

                            <?php if(strlen($caste_certificate_of_father)) { ?>
                            <tr>
                                <td>Caste certificate of father</td>
                                <td style="font-weight:bold"><?= $caste_certificate_of_father_type ?></td>
                                <td>
                                    <?php if (strlen($caste_certificate_of_father)) { ?>
                                        <a href="<?= base_url($caste_certificate_of_father) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($recomendation_certificate)) { ?>
                            <tr>
                                <td>Recommendation of authorized caste/tribe/community organization notified by State Government/ Existing caste certificate</td>
                                <td style="font-weight:bold"><?= $recomendation_certificate_type ?></td>
                                <td>
                                    <?php if (strlen($recomendation_certificate)) { ?>
                                        <a href="<?= base_url($recomendation_certificate) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                            View/Download
                                        </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($others)) { ?>
                            <tr>
                                <td>Others.</td>
                                <td style="font-weight:bold"><?=$others_type?></td>
                                <td>
                                    <?php if(strlen($others)){ ?>
                                        <a href="<?=base_url($others)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(strlen($soft_copy)) { ?>
                                <tr>
                                    <td>Soft copy of the applicant form</td>
                                    <td style="font-weight:bold"><?=$soft_copy_type?></td>
                                    <td>
                                        <a href="<?=base_url($soft_copy)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    </td>
                                </tr>
                            <?php }//End of if ?>
                        </tbody>
                    </table>
                </fieldset>
            </div>
            <!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <button class="btn btn-warning" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
            <!--End of .card-footer-->
        </div>
        <!--End of .card-->
    </div>
    <!--End of .container-->
</main>