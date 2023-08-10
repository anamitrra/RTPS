<?php

$obj_id = $dbrow->{'_id'}->{'$id'};  
$appl_ref_no = $dbrow->service_data->appl_ref_no;
 
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender ?? '';
   
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email;
    $comp_permanent_address = $dbrow->form_data->comp_permanent_address;
    $pa_state = $dbrow->form_data->pa_state;
    $pa_district = $dbrow->form_data->pa_district;
    $pa_pincode = $dbrow->form_data->pa_pincode;
    
    $comp_postal_address = $dbrow->form_data->comp_postal_address;
    $pos_state = $dbrow->form_data->pos_state;
    $pos_district = $dbrow->form_data->pos_district;
    $pos_pincode = $dbrow->form_data->pos_pincode;
    
    $ahsec_reg_session = $dbrow->form_data->ahsec_reg_session;
    $ahsec_reg_no = $dbrow->form_data->ahsec_reg_no;
    $ahsec_inst_name = $dbrow->form_data->ahsec_inst_name;    
      
    $college_seaking_adm = $dbrow->form_data->college_seaking_adm ?? '';
    $state_seaking_adm = $dbrow->form_data->state_seaking_adm ?? ''; 
    $reason_seaking_adm = $dbrow->form_data->reason_seaking_adm ?? '';   
    $postal = $dbrow->form_data->postal ?? '';   

    $photo_of_the_candidate_type = $dbrow->form_data->photo_of_the_candidate_type ?? '';
    $photo_of_the_candidate = $dbrow->form_data->photo_of_the_candidate ?? '';
    $candidate_sign_type = $dbrow->form_data->candidate_sign_type ?? '';
    $candidate_sign = $dbrow->form_data->candidate_sign ?? '';
    $hs_one_marksheet_type = $dbrow->form_data->hs_one_marksheet_type ?? '';
    $hs_one_marksheet = $dbrow->form_data->hs_one_marksheet ?? '';
    $hslc_marksheet_type = $dbrow->form_data->hslc_marksheet_type ?? '';
    $hslc_marksheet = $dbrow->form_data->hslc_marksheet ?? '';
    $recom_letter_type = $dbrow->form_data->recom_letter_type ?? '';
    $recom_letter = $dbrow->form_data->recom_letter ?? '';

  
    
    $appl_status = $dbrow->service_data->appl_status ?? '';
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
                    <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the Applicant/আবেদনকাৰীৰ নাম<br><strong><?=$applicant_name?></strong> </td>
                                <td>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ<br> <strong><?=$applicant_gender?></strong></td>

                            </tr>
                            <tr>
                                <td>Father&apos;s Name/ পিতৃৰ নাম <br><strong><?=$father_name?></strong> </td>
                                <td>Mother&apos;s Name/ মাতৃৰ নাম<br><strong><?=$mother_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br> <strong><?=$mobile?></strong></td>
                                <td>E-Mail / ই-মেইল<br><strong><?=$email?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Permanent Address / স্থায়ী ঠিকনা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Complete Permanent Address/ সম্পূৰ্ণ স্থায়ী ঠিকনা<br><strong><?=$comp_permanent_address?></strong> </td>
                                <td>Pincode/ পিনকোড <br><strong><?=$pa_pincode?></strong> </td>

                            </tr>
                            <tr>
                                <td>State<br><strong><?=$pa_state?></strong> </td>
                                <td>District<br><strong><?=$pa_district?></strong> </td>
                            </tr>
                           
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Postal Address / ডাক ঠিকনা</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Complete Postal Address/ সম্পূৰ্ণ ডাক ঠিকনা<br><strong><?=$comp_postal_address?></strong> </td>
                                <td>Pincode/ পিনকোড <br><strong><?=$pos_pincode?></strong> </td>

                            </tr>
                            <tr>
                                <td>State<br><strong><?=$pos_state?></strong> </td>
                                <td>District<br><strong><?=$pos_district?></strong> </td>
                            </tr>
                           
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Academic Details / শৈক্ষিক বিৱৰণ </legend>

                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>AHSEC Registrtion Session/ এ এইচ এছ ই চি পঞ্জীয়ন অধিবেশন<br><strong><?=$ahsec_reg_session?></strong> </td>
                                <td>Valid AHSEC Registrtion Number / বৈধ এ এইচ এছ ই চি পঞ্জীয়ন নম্বৰ <br><strong><?=$ahsec_reg_no?></strong> </td>

                            </tr>
                            <tr>
                                <td>Valid H.S. 2nd Year Admit Number / বৈধ এইচ.এছ. ২য় বৰ্ষৰ এডমিট নম্বৰ<br><strong>
                                    <?=$ahsec_admit_no ?? '' ?>
                                </strong> </td>
                            </tr>
                           
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Details of Course Opting to Study Next / পাঠ্যক্ৰমৰ বিৱৰণ পৰৱৰ্তী অধ্যয়ন কৰিবলৈ বাছি লোৱা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Institute name where seeking admission/প্ৰতিষ্ঠানৰ নাম য'ত নামভৰ্তি বিচাৰিছে <br><strong><?=$college_seaking_adm?></strong> </td>
                                <td>State where seeking admission/ ক’ত নামভৰ্তি বিচাৰিছে সেই কথা উল্লেখ কৰক<br><strong><?=$state_seaking_adm?></strong> </td>
                            </tr>
                            <tr>
                                <td>Describe Reason for Changing Institute/ প্ৰতিষ্ঠান সলনি কৰাৰ কাৰণ বৰ্ণনা কৰা<br><strong><?=$reason_seaking_adm?></strong> </td>
                                
                            </tr>
                           
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Delivery Preference / ডেলিভাৰীৰ পছন্দ</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>How would you want to receive your certificate ? / আপুনি আপোনাৰ
                                     প্ৰমাণপত্ৰ কেনেকৈ লাভ কৰিব বিচাৰিব<br><strong><?=$postal?></strong> </td>                                
                            </tr>                          
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">ATTACH ENCLOSURE(S) / সংলগ্নক সমূহ </legend>
                        <div class="row mt-3">
                            <div class="col-12">
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
                                            <td>Photo of the Candidate<span class="text-danger">*</span></td>
                                            <td>
                                                Photo of the Candidate
                                            </td>
                                            <td>
                                                
                                                        
                                                        <a href="<?=base_url($photo_of_the_candidate)?>"
                                                            class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>View/Download
                                                        </a>
                                                       
                                                   
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Signature of the Candidate<span class="text-danger">*</span></td>
                                            <td>
                                                Signature of the Candidate
                                            </td>
                                            <td>
                                                
                                               
                                                <a href="<?=base_url($candidate_sign)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                               
                                            </td>
                                        </tr>
                                    <tr>
                                            <td>HSLC Marksheet<span class="text-danger">*</span></td>
                                            <td>
                                                HSLC Marksheet
                                            </td>
                                            <td>
                                                 <?php if(strlen($hslc_marksheet)){ ?>
                                                    <a href="<?=base_url($hslc_marksheet)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?> 
                                                
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Recommendation Letter<span class="text-danger">*</span>
                                                    <br/>
                                                    <a href="https://ahsecservices.in/pdf_samples/coi_sample.pdf" target="_blank" style="color: red;" >Download Sample</a>
                                            </td>
                                            <td>
                                                Recommendation Letter
                                            </td>
                                            <td>
                                               
                                                 <?php if(strlen($recom_letter)){ ?>
                                                    <a href="<?=base_url($recom_letter)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php } ?> 

                                            </td>
                                        </tr>
                                        <tr>
                                            <td> HS 1st Year Marksheet/Valid supporting document<span class="text-danger">*</span></td>
                                            <td>
                                                HS 1st Year Marksheet/Valid supporting document
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                </div>
                                                 <?php if(strlen($hs_one_marksheet)){ ?>
                                                    <a href="<?=base_url($hs_one_marksheet)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php } ?> 

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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