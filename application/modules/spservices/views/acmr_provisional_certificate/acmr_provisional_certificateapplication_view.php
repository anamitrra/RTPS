<?php
//var_dump($dbrow);
//exit();
$obj_id = $dbrow->{'_id'}->{'$id'};  
$appl_ref_no = $dbrow->service_data->appl_ref_no;
 
$applicant_name = $dbrow->form_data->applicant_name;
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $dob = $dbrow->form_data->dob;
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email;
    $pan_no = $dbrow->form_data->pan_no;
    $aadhar_no = $dbrow->form_data->aadhar_no;
    $permanent_addr = $dbrow->form_data->permanent_addr;
    $correspondence_addr = $dbrow->form_data->correspondence_addr;

    $primary_qualification = $dbrow->form_data->primary_qualification;
    $primary_qua_doc = $dbrow->form_data->primary_qua_doc;
    $primary_qua_college_name = $dbrow->form_data->primary_qua_college_name;
    $primary_qua_college_addr = $dbrow->form_data->primary_qua_college_addr;
    $primary_qua_course_dur = $dbrow->form_data->primary_qua_course_dur;
    $primary_qua_doci = $dbrow->form_data->primary_qua_doci;
    $primary_qua_university_award_intership = $dbrow->form_data->primary_qua_university_award_intership;
    $university_roll_no = $dbrow->form_data->university_roll_no;

    $study_place = $dbrow->form_data->study_place;
    if($dbrow->form_data->study_place==="1"){
    $study_place="Applicant studied within the State/Meghalaya";
        }
        if($dbrow->form_data->study_place==="2"){
            $study_place="Applicant studied outside the State/Meghalaya but within India";
        }
        if($dbrow->form_data->study_place==="3"){
            $study_place="Applicant studied outside the Country";
        }
    //$st_id = $dbrow->form_data->st_id;
    $address1 = $dbrow->form_data->address1;
    $address2 = $dbrow->form_data->address2;
    $country = $dbrow->form_data->country;
    $statee = $dbrow->form_data->statee;
    $state_foreign = $dbrow->form_data->state_foreign;
    $district = $dbrow->form_data->district;
    $pincode = $dbrow->form_data->pincode;

$admit_card_type = $dbrow->form_data->admit_card_type??'';
$admit_card = $dbrow->form_data->admit_card??'';
$hs_marksheet_type = $dbrow->form_data->hs_marksheet_type??'';
$hs_marksheet = $dbrow->form_data->hs_marksheet??'';
$reg_certificate_type = $dbrow->form_data->reg_certificate_type??'';
$reg_certificate = $dbrow->form_data->reg_certificate??'';
$mbbs_marksheet_type = $dbrow->form_data->mbbs_marksheet_type??'';
$mbbs_marksheet = $dbrow->form_data->mbbs_marksheet??'';
$pass_certificate_type = $dbrow->form_data->pass_certificate_type??'';
$pass_certificate = $dbrow->form_data->pass_certificate??'';
$college_noc_type = $dbrow->form_data->college_noc_type??'';
$college_noc = $dbrow->form_data->college_noc??'';
$director_noc_type = $dbrow->form_data->director_noc_type??'';
$director_noc = $dbrow->form_data->director_noc??'';
$university_noc_type = $dbrow->form_data->university_noc_type??'';
$university_noc = $dbrow->form_data->university_noc??'';
$institute_noc_type = $dbrow->form_data->institute_noc_type??'';
$institute_noc = $dbrow->form_data->institute_noc??'';
$eligibility_certificate_type = $dbrow->form_data->eligibility_certificate_type??'';
$eligibility_certificate = $dbrow->form_data->eligibility_certificate??'';
$screening_result_type = $dbrow->form_data->screening_result_type??'';
$screening_result = $dbrow->form_data->screening_result??'';
$passport_visa_type = $dbrow->form_data->passport_visa_type??'';
$passport_visa = $dbrow->form_data->passport_visa??'';
$all_docs_type = $dbrow->form_data->all_docs_type??'';
$all_docs = $dbrow->form_data->all_docs??'';
$ten_pass_certificate_type = $dbrow->form_data->ten_pass_certificate_type??'';
$ten_pass_certificate = $dbrow->form_data->ten_pass_certificate??'';
$photograph_type = $dbrow->form_data->photograph_type??'';
$photograph = $dbrow->form_data->photograph??'';
$signature_type = $dbrow->form_data->signature_type??'';
$signature = $dbrow->form_data->signature??'';
$annexure_type = $dbrow->form_data->annexure_type??'';
$annexure = $dbrow->form_data->annexure??'';
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
    $(document).ready(function () {
        $(document).on("click", "#printBtn", function(){
            $("#printDiv").print({
                addGlobalStyles : true,
                stylesheet : null,
                rejectWindow : true,
                noPrintSelector : ".no-print",
                iframe : true,
                append : null,
                prepend : null
            });
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv">
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                   <?=$service_name?> 
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
                                <td>Applicant's Name/আবেদনকাৰীৰ নাম<br><strong><?=$applicant_name?></strong> </td>
                                 <td>Father's Name/দেউতাৰ নাম<br><strong><?=$father_name?></strong> </td>
                              <td>
                            </tr>
                            <tr>
                                 <td>Mother's Name/মাতৃৰ নাম<br><strong><?=$mother_name?></strong> </td>
                                <td>Applicant's Gender/ আবেদনকাৰীৰ লিংগ <br><strong><?=$applicant_gender?></strong> </td>
                            </tr>
                            <tr>
                                <td>Date of Birth/ জন্ম তাৰিখ <br><strong><?=$dob?></strong> </td>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?=$mobile?></strong> </td>
                            </tr>
                            <tr>
                                <td>E-Mail / ই-মেইল<br><strong><?=$email?></strong> </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Permanent Address/ স্থায়ী ঠিকনা <br><strong><?=$permanent_addr?></strong> </td>
                                <td>Correspondence Address/ চিঠিপত্ৰৰ ঠিকনা<br><strong><?=$correspondence_addr?></strong> </td>
                            </tr>
                            <tr>
                                <td>Aadhar No./আধাৰ নং <br><strong><?=$aadhar_no?></strong> </td>
                                <td>PAN No./ পেন নং<br><strong><?=$pan_no?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Primary Qualification / প্ৰাথমিক অৰ্হতা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Primary Qualification(MBBS or equivalent)/ প্ৰাথমিক অৰ্হতা(MBBS বা সমতুল্য)<br><strong><?=$primary_qualification?></strong> </td>
                                <td>Month & year of passing the Final MBBS Exam/চূড়ান্ত এম.বি.বি.এছ. পৰীক্ষাত উত্তীৰ্ণ হোৱাৰ মাহ আৰু বছৰ* <br><strong><?=$primary_qua_doc?></strong> </td>
                            </tr>
                            <tr>
                                <td>College Name/ কলেজৰ নাম <br><strong><?=$primary_qua_college_name?></strong> </td>
                                 <td>Course Duration/ পাঠ্যক্ৰমৰ সময়সীমা <br><strong><?=$primary_qua_course_dur?></strong> </td>
                            </tr>
                            <tr>
                                <td>Date of Commencement of Internship/ ইন্টাৰশ্বিপ আৰম্ভ হোৱাৰ তাৰিখ<br><strong><?=$primary_qua_doci?></strong> </td>
                                  <td>Name of the university awarding the degree/ ইন্টাৰশ্বিপ প্ৰদান কৰা
                                    বিশ্ববিদ্যালয় <br><strong><?=$primary_qua_university_award_intership?></strong> </td>
                                <td></td>
                            </tr>
                            <tr>  
                                <td>University Roll Number/বিশ্ববিদ্যালয় ৰোল নম্বৰ<br><strong><?=$university_roll_no?></strong> </td>    
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                 <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Applicant Study Location Details/আবেদনকাৰী অধ্যয়নৰ অৱস্থানৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                               <td>Applicant Study Location/আবেদনকাৰী অধ্যয়নৰ অৱস্থান<br><strong><?= $study_place ?></strong></td>
                                <?php if ($dbrow->form_data->study_place == 1 || $dbrow->form_data->study_place == 2 || $dbrow->form_data->study_place == 3) { ?>
                                <td>Address Line 1/ ঠিকনাৰ প্ৰথম শাৰী<br><strong><?= $address1 ?></strong></td>
                                <td>Address Line 2/ ঠিকনাৰ চিতীয় শাৰ<br><strong><?= $address2 ?></strong></td>
                                <?php } ?>
                                <?php if ($dbrow->form_data->study_place == 1 || $dbrow->form_data->study_place == 2) { ?>
                                <td>State/ৰাজ্য<br><strong><?= $statee ?></strong></td>
                                <td>District/জিলা<br><strong><?= $district ?></strong></td>
                                <td>Pincode/পিনকোড<br><strong><?= $pincode ?></strong></td>
                                <?php } elseif ($dbrow->form_data->study_place == 3) { ?>
                                <td>Country/<br><strong><?= $country ?></strong></td>
                                <td>State Foreign/ৰাজ্য/Province/প্ৰদেশ<br><strong><?= $state_foreign ?></strong></td>
                                <?php } ?>
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
                                 <?php if ($dbrow->form_data->study_place == 1 || $dbrow->form_data->study_place == 2 || $dbrow->form_data->study_place == 3) { ?>
                                <td>Class 10 Admit card</td>
                                <td style="font-weight:bold"><?=$admit_card_type?></td>
                                <td>
                                    <?php if(strlen($admit_card)){ ?>
                                    <a href="<?=base_url($admit_card)?>"
                                        class="btn btn-block font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>HS Final Marksheet</td>
                                <td style="font-weight:bold"><?=$hs_marksheet_type?></td>
                                <td>
                                    <?php if(strlen($hs_marksheet)){ ?>
                                    <a href="<?=base_url($hs_marksheet)?>"
                                        class="btn btn-block font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>University Registration Certificate</td>
                                <td style="font-weight:bold"><?=$reg_certificate_type?></td>
                                <td>
                                    <?php if(strlen($reg_certificate)){ ?>
                                    <a href="<?=base_url($reg_certificate)?>"
                                        class="btn btn-block font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>All Marksheets of MBBS/Transcript</td>
                                <td style="font-weight:bold"><?=$mbbs_marksheet_type?></td>
                                <td>
                                    <?php if(strlen($mbbs_marksheet)){ ?>
                                    <a href="<?=base_url($mbbs_marksheet)?>"
                                        class="btn btn-block font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>MBBS or equivalent Pass Certificate from College/University</td>
                                <td style="font-weight:bold"><?=$pass_certificate_type?></td>
                                <td>
                                    <?php if(strlen($pass_certificate)){ ?>
                                    <a href="<?=base_url($pass_certificate)?>"
                                        class="btn btn-block font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                    <?php }//End of if ?>
                                </td>
                                 <?php } ?>
                            </tr>
                              <tr>
                                <td>Class 10 Pass Certificate</td>
                                <td style="font-weight:bold"><?=$ten_pass_certificate_type?></td>
                                <td>
                                    <?php if(strlen($ten_pass_certificate)){ ?>
                                    <a href="<?=base_url($ten_pass_certificate)?>"
                                        class="btn btn-block font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                              <tr>
                                <td>Photograph</td>
                                <td style="font-weight:bold"><?=$photograph_type?></td>
                                <td>
                                    <?php if(strlen($photograph)){ ?>
                                    <a href="<?=base_url($photograph)?>"
                                        class="btn btn-block font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                              <tr>
                                <td>Signature</td>
                                <td style="font-weight:bold"><?=$signature_type?></td>
                                <td>
                                    <?php if(strlen($signature)){ ?>
                                    <a href="<?=base_url($signature)?>"
                                        class="btn btn-block font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                             <tr>
                                <td>Annexure</td>
                                <td style="font-weight:bold"><?=$annexure_type?></td>
                                <td>
                                    <?php if(strlen($annexure)){ ?>
                                    <a href="<?=base_url($annexure)?>"
                                        class="btn btn-block font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                         <?php if(($dbrow->form_data->study_place == 2) || ($dbrow->form_data->study_place == 3)){ ?> 
                            <tr>
                                <td>NOC from College/University</td>
                                <td style="font-weight:bold"><?=$college_noc_type?></td>
                                <td>
                                    <?php if(strlen($college_noc)){ ?>
                                        <a href="<?=base_url($college_noc)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>NOC from Director of Medical Education, Assam</td>
                                <td style="font-weight:bold"><?=$director_noc_type?></td>
                                <td>
                                    <?php if(strlen($director_noc)){ ?>
                                        <a href="<?=base_url($director_noc)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>NOC from Srimanta Sankaradeva University of Health Sciences</td>
                                <td style="font-weight:bold"><?=$university_noc_type?></td>
                                <td>
                                    <?php if(strlen($university_noc)){ ?>
                                        <a href="<?=base_url($university_noc)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>NOC from the Institute from where applicant want to do the internship</td>
                                <td style="font-weight:bold"><?=$institute_noc_type?></td>
                                <td>
                                    <?php if(strlen($institute_noc)){ ?>
                                        <a href="<?=base_url($institute_noc)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php if($dbrow->form_data->study_place == 3){ ?> 
                            <tr>
                                <td>Eligibility Certificate from National Medical Commission</td>
                                <td style="font-weight:bold"><?=$eligibility_certificate_type?></td>
                                <td>
                                    <?php if(strlen($eligibility_certificate)){ ?>
                                        <a href="<?=base_url($eligibility_certificate)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>FMGE Marksheet</td>
                                <td style="font-weight:bold"><?=$screening_result_type?></td>
                                <td>
                                    <?php if(strlen($screening_result)){ ?>
                                        <a href="<?=base_url($screening_result)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Passport and Visa with travel details</td>
                                <td style="font-weight:bold"><?=$passport_visa_type?></td>
                                <td>
                                    <?php if(strlen($passport_visa)){ ?>
                                        <a href="<?=base_url($passport_visa)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>All documents related to medical college details</td>
                                <td style="font-weight:bold"><?=$all_docs_type?></td>
                                <td>
                                    <?php if(strlen($all_docs)){ ?>
                                        <a href="<?=base_url($all_docs)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php } ?>
                            <!-- <?php if(strlen($soft_copy)) { ?>
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
                            <?php }//End of if ?> -->
                        </tbody>
                    </table>
                </fieldset>
            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <button class="btn btn-warning" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button> 
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>