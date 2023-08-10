<?php
// var_dump($dbrow->form_data);
// var_dump($dbrow->>form_data->study_place);
//exit();
$obj_id = $dbrow->{'_id'}->{'$id'};  
$appl_ref_no = $dbrow->service_data->appl_ref_no;
 
// pre($dbrow->form_data);
$applicant_name = $dbrow->form_data->applicant_name;

$father_name = $dbrow->form_data->father_name;

$mother_name = $dbrow->form_data->mother_name;

$applicant_gender = $dbrow->form_data->applicant_gender;

$dob = $dbrow->form_data->dob;

$mobile = $dbrow->form_data->mobile;

$email = $dbrow->form_data->email;

$permanent_addr = $dbrow->form_data->permanent_addr;

$correspondence_addr = $dbrow->form_data->correspondence_addr;

$aadhar_no = $dbrow->form_data->aadhar_no;

$pan_no = $dbrow->form_data->pan_no;

$primary_qualification = $dbrow->form_data->primary_qualification;

$primary_qua_doc = $dbrow->form_data->primary_qua_doc;

$college_name = $dbrow->form_data->college_name;

$primary_qua_college_addr = $dbrow->form_data->primary_qua_college_addr;

$primary_qua_course_dur = $dbrow->form_data->primary_qua_course_dur;

// $primary_qua_doci = $dbrow->form_data->primary_qua_doci;

$primary_qua_university_award_intership = $dbrow->form_data->primary_qua_university_award_intership;

$acmrrno = $dbrow->form_data->acmrrno;

$prn = $dbrow->form_data->prn;

$registration_date = $dbrow->form_data->registration_date;




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


// $st_id = $dbrow->form_data->st_id;
$address1 = $dbrow->form_data->address1;
$address2 = $dbrow->form_data->address2;
$country = $dbrow->form_data->country;
$state = $dbrow->form_data->state;
$district = $dbrow->form_data->district;

$this->load->model('permanent_registration_mbbs/states_model');
$states = $this->states_model->get_row(array("slc"=>intval($state)));
// pre($states);
$state = is_object($states) && property_exists($states, 'state_name_english')
    ? $states->state_name_english
    : (isset($dbrow->form_data->state) ? $dbrow->form_data->state : null);
    
$pincode = $dbrow->form_data->pincode;



$admit_birth_type = $dbrow->form_data->admit_birth_type??'';
$admit_birth = $dbrow->form_data->admit_birth??'';

$passport_photo_type = $dbrow->form_data->passport_photo_type??'';
$passport_photo = $dbrow->form_data->passport_photo??'';

$signature_type = $dbrow->form_data->signature_type??'';
$signature = $dbrow->form_data->signature??'';

$hs_marksheet_type = $dbrow->form_data->hs_marksheet_type??'';
$hs_marksheet = $dbrow->form_data->hs_marksheet??'';

$reg_certificate_type = $dbrow->form_data->reg_certificate_type??'';
$reg_certificate = $dbrow->form_data->reg_certificate??'';

$mbbs_marksheet_type = $dbrow->form_data->mbbs_marksheet_type??'';
$mbbs_marksheet = $dbrow->form_data->mbbs_marksheet??'';

$pass_certificate_type = $dbrow->form_data->pass_certificate_type??'';
$pass_certificate = $dbrow->form_data->pass_certificate??'';

$internship_completion_certificate_type = $dbrow->form_data->internship_completion_certificate_type??'';
$internship_completion_certificate = $dbrow->form_data->internship_completion_certificate??'';

$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type = $dbrow->form_data->provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type??'';
$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration = $dbrow->form_data->provisional_registration_certificate_of_concerned_assam_council_of_medical_registration??'';
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
        
        $(document).on("click", ".frmsbbtn", function(e){ 
            e.preventDefault();

            let url='<?=base_url('spservices/nextofkin/registration/finalsubmition')?>'
            let ackLocation='<?=base_url('spservices/applications/acknowledgement/')?>'+'<?=$obj_id?>';
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            var msg = "Once you submitted, you won't able to revert this";   

            Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value == true) {
                    $(".frmsbbtn").text("Plese wait..");
                    $(".frmsbbtn").prop('disabled',true);
                    $.ajax({
                        url: url,
                        type:'POST',
                        dataType: 'json',
                        data: {
                            "obj":'<?=$obj_id?>'
                        },
                        success:function (response) {
                            console.log(response);
                            if(response.status){
                                
                                Swal.fire(
                                    'Success',
                                    'Application submited successfully',
                                    'success'
                                );

                                window.location.replace(ackLocation)
                            }else{
                                $(".frmsbbtn").prop('disabled',false);
                                $(".frmsbbtn").text("Save");
                                Swal.fire(
                                    'Failed!',
                                    'Something went wrong please try again!',
                                    'fail'
                                );
                            }
                        },
                        error:function () {
                            $(".frmsbbtn").prop('disabled',false);
                            $(".frmsbbtn").text("Save");
                            Swal.fire(
                                'Failed!',
                                'Something went wrong please try again!',
                                'fail'
                            );
                        }
                    });
                } else {

                }
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

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the Applicant/আবেদনকাৰীৰ নাম<br><strong><?=$applicant_name?></strong> </td>
                                <td>Father&apos;s Name/ পিতৃৰ নাম<br><strong><?=$father_name?></strong> </td>
                            </tr>

                            <tr>
                                <td>Mother&apos;s Name/ মাতৃৰ নাম <br><strong><?=$mother_name?></strong> </td>
                                <td>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ<br><strong><?=$applicant_gender?></strong> </td>
                            </tr>
                            <tr>
                                <td>Date of Birth/ জন্ম তাৰিখ<br><strong><?=$dob?></strong> </td>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?=$mobile?></strong> </td>
                            </tr>
                            <tr>
                                
                                <td>E-Mail / ই-মেইল <br><strong><?=$email?></strong> </td>
                            </tr>
                            <tr>
                                <td>Permanent Address/ স্থায়ী ঠিকনা <br><strong><?=$permanent_addr?></strong> </td>
                                <td>Correspondence Address/ চিঠিপত্ৰৰ ঠিকনা <br><strong><?=$correspondence_addr?></strong> </td>
                            </tr>

                            <tr>
                                <td>Aadhar No./আধাৰ নং <br><strong><?=$aadhar_no?></strong> </td>
                                <td>PAN No./ পেন নং <br><strong><?=$pan_no?></strong> </td>
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
                                <td>Date of completion/ সম্পূৰ্ণ হোৱাৰ তাৰিখ <br><strong><?=$primary_qua_doc?></strong> </td>
                            </tr>
                            <tr>
                                <td>College Name/ কলেজৰ নাম <br><strong><?=$college_name?></strong> </td>
                                <td>College Address/ কলেজৰ ঠিকনা<br><strong><?=$primary_qua_college_addr?></strong> </td>
                            </tr>

                            <!--  -->
                            <tr>
                                <td>Course Duration/ পাঠ্যক্ৰমৰ সময়সীমা <br><strong><?=$primary_qua_course_dur?></strong> </td>
                                <td>University awarding the Internship/ ইন্টাৰশ্বিপ প্ৰদান কৰা বিশ্ববিদ্যালয়<br><strong><?=$primary_qua_university_award_intership?></strong> </td>
                            </tr>
                            <!-- <tr>
                                <td>University awarding the Internship/ ইন্টাৰশ্বিপ প্ৰদান কৰা বিশ্ববিদ্যালয় <br><strong><?=$primary_qua_university_award_intership?></strong> </td>
                                <td></td>
                            </tr> -->
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Medical Registration Details / চিকিৎসা পঞ্জীয়নৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>State Medical Council registered with/ ৰাজ্যিক চিকিৎসা পৰিষদৰ সৈতে পঞ্জীয়ন<br><strong><?=$acmrrno?></strong> </td>
                                <td>Provisional Registration Number/ অস্থায়ী পঞ্জীয়ন নম্বৰ<br><strong><?=$prn?></strong> </td>
                            </tr>
                            <tr>
                                <td>Registration Date/ পঞ্জীয়নৰ তাৰিখ <br><strong><?=$registration_date?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Applicant Study Location Details/ আবেদনকাৰীৰ অধ্যয়নৰ স্থানৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Applicant Study Location/ আবেদনকাৰীৰ অধ্যয়নৰ স্থান<br><strong><?=$study_place?></strong> </td>
                                <!-- <td>Applicant Study Location/ আবেদনকাৰীৰ অধ্যয়নৰ স্থান<br><strong><?=$study_place?></strong> </td> -->
                                <td>Address Line 1 / ঠিকনাৰ প্রথ্ম শাৰী<br><strong><?=$address1?></strong> </td>
                                 <td>Address Line 2 / ঠিকনাৰ চিতীয় শাৰ<br><strong><?=$address2?></strong> </td>
                            </tr>
                            <tr>
                                 <td>State/ ৰাজ্য<br><strong><?=$state?></strong> </td>
                                  <td>Country/ দেশ<br><strong><?=$country?></strong> </td>
                                <td>District / জিলা <br><strong><?=$district?></strong> </td>
                            </tr>
                            <tr>
                                <td>Pincode/ পিনকোড  <br><strong><?=$pincode?></strong> </td>
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

<!-- 1,2,3 -->
<?php if(($dbrow->form_data->study_place == 1) || ($dbrow->form_data->study_place == 2) || ($dbrow->form_data->study_place == 3)){ ?> 


        <!-- Photo -->
    <tr>
<td>Passport Photo</td>
                                <td style="font-weight:bold"><?=$passport_photo_type?></td>
                                <td>
                                    <?php if(strlen($passport_photo)){ ?>
                                        <a href="<?=base_url($passport_photo)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>

    </tr>

    <!-- Photo end -->
    <!-- Sign -->
    <tr>
<td>Signature</td>
                                <td style="font-weight:bold"><?=$signature_type?></td>
                                <td>
                                    <?php if(strlen($signature)){ ?>
                                        <a href="<?=base_url($signature)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>

    </tr>
    <!-- Sign end -->
                                                                                
<tr>
<td>Class 10 Admit card/Birth Certificate</td>
                                <td style="font-weight:bold"><?=$admit_birth_type?></td>
                                <td>
                                    <?php if(strlen($admit_birth)){ ?>
                                        <a href="<?=base_url($admit_birth)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
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
                                        <a href="<?=base_url($hs_marksheet)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Internship Completion Certificate</td>
                                <td style="font-weight:bold"><?=$internship_completion_certificate_type?></td>
                                <td>
                                    <?php if(strlen($internship_completion_certificate)){ ?>
                                        <a href="<?=base_url($internship_completion_certificate)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Annexure II</td>
                                <td style="font-weight:bold"><?=$dbrow->form_data->annexure_type?></td>
                                <td>
                                    <?php if(strlen($dbrow->form_data->annexure)){ ?>
                                        <a href="<?=base_url($dbrow->form_data->annexure)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
<?php } ?>


                        <!-- 1 -->
                        <?php if(($dbrow->form_data->study_place == 1) ){ ?> 

                            <tr>
                                <td>Registration Certificate from Srimanta Sankaradeva University of Health Sciences or any other University of the State</td>
                                <td style="font-weight:bold"><?=$reg_certificate_type?></td>
                                <td>
                                    <?php if(strlen($reg_certificate)){ ?>
                                        <a href="<?=base_url($reg_certificate)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>


                            <tr>
                                <td>MBBS Pass Certificate from College/University</td>
                                <td style="font-weight:bold"><?=$pass_certificate_type?></td>
                                <td>
                                    <?php if(strlen($pass_certificate)){ ?>
                                        <a href="<?=base_url($pass_certificate)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>


                                 


                            

                            <tr>
                                <td>Provisional Registration Certificate of concerned Assam Council of Medical Registration</td>
                                <td style="font-weight:bold"><?=$provisional_registration_certificate_of_concerned_assam_council_of_medical_registration_type?></td>
                                <td>
                                    <?php if(strlen($provisional_registration_certificate_of_concerned_assam_council_of_medical_registration)){ ?>
                                        <a href="<?=base_url($provisional_registration_certificate_of_concerned_assam_council_of_medical_registration)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

<?php } ?>


<!-- 1,2 -->

<?php if(($dbrow->form_data->study_place == 1)|| ($dbrow->form_data->study_place == 2) ){ ?> 


                            <tr>
                                <td>All Marksheets of MBBS</td>
                                <td style="font-weight:bold"><?=$mbbs_marksheet_type?></td>
                                <td>
                                    <?php if(strlen($mbbs_marksheet)){ ?>
                                        <a href="<?=base_url($mbbs_marksheet)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                           
                                    


<?php } ?>


<!-- 2 -->

<?php if(($dbrow->form_data->study_place == 2) ){ ?> 

    
                            <tr>
                                <td>Registration Certificate of concerned University</td>
                                <td style="font-weight:bold"><?=$dbrow->form_data->reg_certificate_of_concerned_university_type?></td>
                                <td>
                                    <?php if(strlen($dbrow->form_data->reg_certificate_of_concerned_university)){ ?>
                                        <a href="<?=base_url($dbrow->form_data->reg_certificate_of_concerned_university)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>



                            <tr>
                                <td>MBBS Pass Certificate from University</td>
                                <td style="font-weight:bold"><?=$dbrow->form_data->mbbs_pass_certificate_from_university_type?></td>
                                <td>
                                    <?php if(strlen($dbrow->form_data->mbbs_pass_certificate_from_university)){ ?>
                                        <a href="<?=base_url($dbrow->form_data->mbbs_pass_certificate_from_university)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>


                                        <?php } ?>



                                        <!-- 3 -->

<?php if(($dbrow->form_data->study_place == 3) ){ ?> 
 

                            <tr>
                                <td>Registration Certificate from respective University or equivalent</td>
                                <td style="font-weight:bold"><?=$dbrow->form_data->registration_certificate_from_respective_university_or_equivalent_type?></td>
                                <td>
                                    <?php if(strlen($dbrow->form_data->registration_certificate_from_respective_university_or_equivalent)){ ?>
                                        <a href="<?=base_url($dbrow->form_data->registration_certificate_from_respective_university_or_equivalent)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>


                            <tr>
                                <td>All Marksheets of MBBS or equivalent</td>
                                <td style="font-weight:bold"><?=$dbrow->form_data->all_marksheets_of_mbbs_or_equivalent_type?></td>
                                <td>
                                    <?php if(strlen($dbrow->form_data->all_marksheets_of_mbbs_or_equivalent)){ ?>
                                        <a href="<?=base_url($dbrow->form_data->all_marksheets_of_mbbs_or_equivalent)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>


                            <tr>
                                <td>MBBS or equivalent Degree Pass Certificate from University</td>
                                <td style="font-weight:bold"><?=$dbrow->form_data->mbbs_or_equivalent_degree_pass_certificate_from_university_type?></td>
                                <td>
                                    <?php if(strlen($dbrow->form_data->mbbs_or_equivalent_degree_pass_certificate_from_university)){ ?>
                                        <a href="<?=base_url($dbrow->form_data->mbbs_or_equivalent_degree_pass_certificate_from_university)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Eligibility Certificate from National Medical Commission</td>
                                <td style="font-weight:bold"><?=$dbrow->form_data->eligibility_certificate_from_national_medical_commission_type?></td>
                                <td>
                                    <?php if(strlen($dbrow->form_data->eligibility_certificate_from_national_medical_commission)){ ?>
                                        <a href="<?=base_url($dbrow->form_data->eligibility_certificate_from_national_medical_commission)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            
                            <tr>
                                <td>Screening Test Result from National Board of Examination</td>
                                <td style="font-weight:bold"><?=$dbrow->form_data->screening_test_result_from_national_board_of_examination_type?></td>
                                <td>
                                    <?php if(strlen($dbrow->form_data->screening_test_result_from_national_board_of_examination)){ ?>
                                        <a href="<?=base_url($dbrow->form_data->screening_test_result_from_national_board_of_examination)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Passport and Visa with travel details</td>
                                <td style="font-weight:bold"><?=$dbrow->form_data->passport_and_visa_with_travel_details_type?></td>
                                <td>
                                    <?php if(strlen($dbrow->form_data->passport_and_visa_with_travel_details)){ ?>
                                        <a href="<?=base_url($dbrow->form_data->passport_and_visa_with_travel_details)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>


                            <tr>
                                <td>All documents related to medical college details</td>
                                <td style="font-weight:bold"><?=$dbrow->form_data->all_documents_related_to_medical_college_details_type?></td>
                                <td>
                                    <?php if(strlen($dbrow->form_data->all_documents_related_to_medical_college_details)){ ?>
                                        <a href="<?=base_url($dbrow->form_data->all_documents_related_to_medical_college_details)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>





                                        

                                        

                                        <?php } ?>


                
                                        
        <!-- 2,3 -->
        <?php if(($dbrow->form_data->study_place == 2) || ($dbrow->form_data->study_place == 3) ){ ?> 

            <tr>
                                <td>Permanent Registration Certificate of concerned Medical Council</td>
                                <td style="font-weight:bold"><?=$dbrow->form_data->permanent_registration_certificate_of_concerned_medical_council_type?></td>
                                <td>
                                    <?php if(strlen($dbrow->form_data->permanent_registration_certificate_of_concerned_medical_council)){ ?>
                                        <a href="<?=base_url($dbrow->form_data->permanent_registration_certificate_of_concerned_medical_council)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
            </tr>

            
            <tr>
                                <td>NOC from concerned Medical Council</td>
                                <td style="font-weight:bold"><?=$dbrow->form_data->noc_from_concerned_medical_council_type?></td>
                                <td>
                                    <?php if(strlen($dbrow->form_data->noc_from_concerned_medical_council)){ ?>
                                        <a href="<?=base_url($dbrow->form_data->noc_from_concerned_medical_council)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
            </tr>






        
        <?php } ?>






 

                            


                        </tbody>
                    </table>
                </fieldset>
            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <!-- <?php if($appl_status === 'DRAFT') { ?>
                <a href="<?= base_url('spservices/permanent_registration_mbbs/registration/index/' . $obj_id) ?>" class="btn btn-primary">
                    <i class="fa fa-pencil"></i> Edit
                </a>
                <?php } ?> -->
                <button class="btn btn-warning" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <!-- <?php if(($appl_status != 'QA') && ($appl_status != 'QS')){ ?>
                <a href="<?= base_url('spservices/permanent_registration_mbbs/payment/initiate/' . $obj_id) ?>" class="btn btn-success">
                    <i class="fa fa-angle-double-right"></i> Make Payment
                </a>
                <?php }?>   -->
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>