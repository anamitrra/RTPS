<?php
//var_dump($dbrow);
//exit();
$obj_id = $dbrow->{'_id'}->{'$id'};  
$appl_ref_no = $dbrow->service_data->appl_ref_no;
 
$applicant_name = $dbrow->form_data->applicant_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$pan_no = isset($dbrow->form_data->pan_no)? $dbrow->form_data->pan_no: "NA";
$mobile = $dbrow->form_data->mobile;
$email = isset($dbrow->form_data->email)? $dbrow->form_data->email: "NA";
$aadhar_no = isset($dbrow->form_data->aadhar_no)? $dbrow->form_data->aadhar_no: "NA";
$father_name = $dbrow->form_data->father_name;
$relation_with_deceased = $dbrow->form_data->relation_with_deceased;
$other_relation = isset($dbrow->form_data->other_relation)? $dbrow->form_data->other_relation: "NA";

$name_of_deceased = $dbrow->form_data->name_of_deceased;
$deceased_gender = $dbrow->form_data->deceased_gender;
$deceased_dod = $dbrow->form_data->deceased_dod;
$age_of_deceased = $dbrow->form_data->age_of_deceased;
$place_of_death = $dbrow->form_data->place_of_death;
$address_of_hospital_home = isset($dbrow->form_data->address_of_hospital_home)? $dbrow->form_data->address_of_hospital_home: "NA";
$other_place_of_death = isset($dbrow->form_data->other_place_of_death)? $dbrow->form_data->other_place_of_death: "NA";
$reason_for_late = $dbrow->form_data->reason_for_late;
$father_name_of_deceased = $dbrow->form_data->father_name_of_deceased;
$mother_name_of_deceased = $dbrow->form_data->mother_name_of_deceased;

$state = $dbrow->form_data->state;
$district = $dbrow->form_data->district;
$sub_division = $dbrow->form_data->sub_division;
$revenue_circle = $dbrow->form_data->revenue_circle;
$village_town = $dbrow->form_data->village_town;
$pin_code = $dbrow->form_data->pin_code;

$doctor_certificate_type = $dbrow->form_data->doctor_certificate_type??'';
$doctor_certificate = $dbrow->form_data->doctor_certificate??'';
$proof_residence_type = $dbrow->form_data->proof_residence_type??'';
$proof_residence = $dbrow->form_data->proof_residence??'';
$affidavit_type = $dbrow->form_data->affidavit_type??'';
$affidavit = $dbrow->form_data->affidavit??'';
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

            $(".frmsbbtn").text("Plese wait..");
            $(".frmsbbtn").prop('disabled',true);
            e.preventDefault();

            let url='<?=base_url('spservices/delayeddeath/registration/finalsubmition')?>'
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
                if (result.value) {
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
                Application Ref. No: <?=$appl_ref_no?>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the Applicant/আবেদনকাৰীৰ নাম<br><strong><?=$applicant_name?></strong> </td>
                                <td>Applicant's Gender/ আবেদনকাৰীৰ লিংগ <br><strong><?=$applicant_gender?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?=$mobile?></strong> </td>
                                <td>E-Mail / ই-মেইল<br><strong><?=$email?></strong> </td>
                            </tr>
                            <tr>
                                <td>Relation with Deceased/ মৃতকৰ সৈতে সম্পৰ্ক <br><strong><?=$relation_with_deceased?></strong> </td>
                                <td>Enter Other Relation (if any)/ অন্য সম্পৰ্ক প্ৰবিষ্ট কৰক (যদি থাকে)<br><strong><?=$other_relation?></strong> </td>
                            </tr>
                            <tr>
                                <td>Aadhar No./আধাৰ নং <br><strong><?=$aadhar_no?></strong> </td>
                                <td>PAN No./ পেন নং<br><strong><?=$pan_no?></strong> </td>
                            </tr>
                            <tr>
                                <td>Father's Name/ পিতৃৰ নাম<br><strong><?=$father_name?></strong> </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Deceased Person's Information / মৃতকৰ তথ্য </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the Deceased/ মৃত ব্যক্তিৰ নাম<br><strong><?=$name_of_deceased?></strong> </td>
                                <td>Deceased Gender/ মৃতকৰ লিংগ <br><strong><?=$deceased_gender?></strong> </td>
                            </tr>
                            <tr>
                                <td>Date of Death/ মৃত্যুৰ তাৰিখ<br><strong><?=$deceased_dod?></strong> </td>
                                <td>Age of the Deceased (in years)/ মৃতকৰ বয়স (বছৰত) <br><strong><?=$age_of_deceased?></strong> </td>
                            </tr>
                            <tr>
                                <td>Place of Death/ মৃত্যুৰ ঠাই<br><strong><?=$place_of_death?></strong> </td>
                                <td>Address of Home/Hospital/ গৃহ/চিকিৎসালয়ৰ ঠিকনা<br><strong><?=$address_of_hospital_home?></strong> </td>
                            </tr>
                            <tr>
                                <td>Other Place of Death (if any)/ অন্য মৃত্যুস্থান (যদি প্ৰযোজ্য) <br><strong><?=$other_place_of_death?></strong> </td>
                                <td>Reason for Being Late/ পলম হোৱাৰ কাৰণ<br><strong><?=$reason_for_late?></strong> </td>
                            </tr>
                            <tr>
                                <td>Father's Name of the Deceased/ মৃতকৰ পিতৃৰ নাম <br><strong><?=$father_name_of_deceased?></strong> </td>
                                <td>Mother Name of the Deceased/ মৃতকৰ মাতৃৰ নাম<br><strong><?=$mother_name_of_deceased?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Address of the Deceased/ মৃতকৰ ঠিকনা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>State/ ৰাজ্য<br><strong><?=$state?></strong> </td>
                                <td>District/ জিলা<br><strong><?=$district?></strong> </td>
                            </tr>
                            <tr>
                                <td>Sub-Division/ মহকুমা <br><strong><?=$sub_division?></strong> </td>
                                <td>Revenue Circle/ ৰাজহ চক্ৰ <br><strong><?=$revenue_circle?></strong> </td>
                            </tr>
                            <tr>
                            <td>Village/ গাওঁ<br><strong><?=$village_town?></strong> </td>
                                <td>Pin Code/ পিন ক'ড (e.g. 78xxxx)<br><strong><?=$pin_code?></strong> </td>
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
                                <td>Hospital or Doctor's Certificate regarding Death / Cremation certificate or Age Proof.</td>
                                <td style="font-weight:bold"><?=$doctor_certificate_type?></td>
                                <td>
                                    <?php if(strlen($doctor_certificate)){ ?>
                                        <a href="<?=base_url($doctor_certificate)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Proof of Resident.</td>
                                <td style="font-weight:bold"><?=$proof_residence_type?></td>
                                <td>
                                    <?php if(strlen($proof_residence)){ ?>
                                        <a href="<?=base_url($proof_residence)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Affidavit.</td>
                                <td style="font-weight:bold"><?=$affidavit_type?></td>
                                <td>
                                    <?php if(strlen($affidavit)){ ?>
                                        <a href="<?=base_url($affidavit)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

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
            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>                
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>