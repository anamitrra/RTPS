<?php
//var_dump($dbrow);
//exit();
$obj_id = $dbrow->{'_id'}->{'$id'};  
$appl_ref_no = $dbrow->service_data->appl_ref_no;
 
$applicant_name = $dbrow->form_data->applicant_name;
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
$primary_qua_college_name = $dbrow->form_data->primary_qua_college_name;
$primary_qua_college_addr = $dbrow->form_data->primary_qua_college_addr;
$primary_qua_course_dur = $dbrow->form_data->primary_qua_course_dur;
$primary_qua_doci = $dbrow->form_data->primary_qua_doci;
$primary_qua_university_award_intership = $dbrow->form_data->primary_qua_university_award_intership;

$acmrrno = $dbrow->form_data->acmrrno;
$registration_date = $dbrow->form_data->registration_date;
$original_registration_number = $dbrow->form_data->original_registration_number;


$add_degree_reg_no = $dbrow->form_data->add_degree_reg_no;

$photo_of_the_candidate_type = $dbrow->form_data->photo_of_the_candidate_type??'';
$photo_of_the_candidate = $dbrow->form_data->photo_of_the_candidate??'';
$signature_of_the_candidate_type = $dbrow->form_data->signature_of_the_candidate_type??'';
$signature_of_the_candidate = $dbrow->form_data->signature_of_the_candidate??'';
$pass_certificate_from_uni_coll_type = $dbrow->form_data->pass_certificate_from_uni_coll_type??'';
$pass_certificate_from_uni_coll = $dbrow->form_data->pass_certificate_from_uni_coll??'';
$pg_degree_dip_marksheet_type = $dbrow->form_data->pg_degree_dip_marksheet_type??'';
$pg_degree_dip_marksheet = $dbrow->form_data->pg_degree_dip_marksheet??'';
$prc_acmr_type = $dbrow->form_data->prc_acmr_type??'';
$prc_acmr = $dbrow->form_data->prc_acmr??'';
$other_addl_degree_type = $dbrow->form_data->other_addl_degree_type??'';
$other_addl_degree = $dbrow->form_data->other_addl_degree??'';
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
                    <legend class="h5">Details of the Applicant/ আবেদনকাৰীৰ তথ্য </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the Applicant/ আবেদনকাৰীৰ নাম<br><strong><?=$applicant_name?></strong> </td>
                                <td>Applicant's Gender/ আবেদনকাৰীৰ লিংগ <br><strong><?=$applicant_gender?></strong> </td>
                            </tr>
                            <tr>
                                <td>Date of Birth/ জন্ম তাৰিখ <br><strong><?=$dob?></strong> </td>
                                <td>Mobile Numbe/ দুৰভাষ ( মবাইল )<br><strong><?=$mobile?></strong> </td>
                            </tr>
                            <tr>
                                <td>E-Mail/ ই-মেইল<br><strong><?=$email?></strong> </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Permanent Address/ স্থায়ী ঠিকনা <br><strong><?=$permanent_addr?></strong> </td>
                                <td>Correspondence Address/ চিঠিপত্ৰৰ ঠিকনা<br><strong><?=$correspondence_addr?></strong> </td>
                            </tr>
                            <tr>
                                <td>Aadhar No./ আধাৰ নং <br><strong><?=$aadhar_no?></strong> </td>
                                <td>PAN No./ পেন নং<br><strong><?=$pan_no?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Primary Qualification/ প্ৰাথমিক অৰ্হতা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Primary Qualification(MBBS or equivalent)/ প্ৰাথমিক অৰ্হতা(MBBS বা সমতুল্য)<br><strong><?=$primary_qualification?></strong> </td>
                                <td>Month & year of passing the Final MBBS Exam/ Final MBBS পৰীক্ষাত উত্তীৰ্ণ হোৱাৰ মাহ & বছৰ <br><strong><?=$primary_qua_doc?></strong> </td>
                            </tr>
                            <tr>
                                <td>College Name/ কলেজৰ নাম <br><strong><?=$primary_qua_college_name?></strong> </td>
                                <td>College Address/ কলেজৰ ঠিকনা<br><strong><?=$primary_qua_college_addr?></strong> </td>
                            </tr>
                            <tr>
                                <td>Course Duration/ পাঠ্যক্ৰমৰ সময়সীমা <br><strong><?=$primary_qua_course_dur?></strong> </td>
                                <td>Date of Completion of Internship/ ইন্টাৰশ্বিপ আৰম্ভ হোৱাৰ তাৰিখ<br><strong><?=$primary_qua_doci?></strong> </td>
                            </tr>
                            <tr>
                                <td>Name of the university awarding the degree/ ডিগ্ৰী প্ৰদান কৰা বিশ্ববিদ্যালয়ৰ নাম <br><strong><?=$primary_qua_university_award_intership?></strong> </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Medical Registration Details/ চিকিৎসা পঞ্জীয়নৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Assam Council of Medical Registration Registration Number<br><strong><?=$acmrrno?></strong> </td>
                                <td>Registration Date <br><strong><?=$registration_date?></strong> </td>
                                <td>Original Registration Number <br><strong><?=$original_registration_number?></strong> </td>

                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Additional Qualification Details/ অতিৰিক্ত অৰ্হতাৰ বিৱৰণ </legend>

                    <?php
                        if (count($dbrow->form_data->addl_qualification_details) > 0) {
                    ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Additional Qualification</th>
                                <th>College Name</th>
                                <th>University Name</th>
                                <th>Date of Qualification</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($dbrow->form_data->addl_qualification_details as $key => $value) {
                                    ?>
                                    <tr>
                                        <td><?php print $value->addl_qualification; ?></td>
                                        <td><?php print $value->addl_college_name; ?></td>
                                        <td><?php print $value->addl_university_name; ?></td>
                                        <td><?php print $value->addl_date_of_qualification; ?></td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                    <?php } ?>

                    <?php if (isset($dbrow->form_data->add_degree_reg_no)) { ?>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Additional Degree Registration Number (if any)/ অতিৰিক্ত ডিগ্ৰী পঞ্জীয়ন নম্বৰ (যদি আছে)<br><strong><?=$add_degree_reg_no?></strong> </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <?php } ?>
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
                                <td>Photo of the Candidate*.</td>
                                <td style="font-weight:bold"><?=$photo_of_the_candidate_type?></td>
                                <td>
                                    <?php if(strlen($photo_of_the_candidate)){ ?>
                                        <a href="<?=base_url($photo_of_the_candidate)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Signature of the Candidate*.</td>
                                <td style="font-weight:bold"><?=$signature_of_the_candidate_type?></td>
                                <td>
                                    <?php if(strlen($signature_of_the_candidate)){ ?>
                                        <a href="<?=base_url($signature_of_the_candidate)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Pass Certificate from University/College*.</td>
                                <td style="font-weight:bold"><?=$pass_certificate_from_uni_coll_type?></td>
                                <td>
                                    <?php if(strlen($pass_certificate_from_uni_coll)){ ?>
                                        <a href="<?=base_url($pass_certificate_from_uni_coll)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>PGDegree/DiplomaMarksheet.</td>
                                <td style="font-weight:bold"><?=$pg_degree_dip_marksheet_type?></td>
                                <td>
                                    <?php if(strlen($pg_degree_dip_marksheet)){ ?>
                                        <a href="<?=base_url($pg_degree_dip_marksheet)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Permanent Registration Certificate of Assam Council of Medical Registration*.</td>
                                <td style="font-weight:bold"><?=$prc_acmr_type?></td>
                                <td>
                                    <?php if(strlen($prc_acmr)){ ?>
                                        <a href="<?=base_url($prc_acmr)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <?php if(strlen($other_addl_degree)) { ?>
                            <tr>
                                <td>Other Additional Degrees (If any).</td>
                                <td style="font-weight:bold"><?=$other_addl_degree_type?></td>
                                <td>
                                    <?php if(strlen($other_addl_degree)){ ?>
                                        <a href="<?=base_url($other_addl_degree)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
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
                <?php if($appl_status === 'DRAFT') { ?>
                <a href="<?= base_url('spservices/acmr_reg_of_addl_degrees/registration/index/' . $obj_id) ?>" class="btn btn-primary">
                    <i class="fa fa-pencil"></i> Edit
                </a>
                <?php } ?>
                <button class="btn btn-warning" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <?php if(($appl_status != 'QA') && ($appl_status != 'QS')){ ?>
                <a href="<?= base_url('spservices/acmr_reg_of_addl_degrees/payment/initiate/' . $obj_id) ?>" class="btn btn-success">
                    <i class="fa fa-angle-double-right"></i> Make Payment
                </a>
                <?php }?>  
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>