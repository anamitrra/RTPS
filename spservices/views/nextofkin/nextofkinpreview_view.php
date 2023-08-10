<?php
//var_dump($dbrow);
//exit();
$obj_id = $dbrow->{'_id'}->{'$id'};  
$appl_ref_no = $dbrow->service_data->appl_ref_no;
 
$applicant_name = $dbrow->form_data->applicant_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$dob = $dbrow->form_data->dob;
$pan_no = $dbrow->form_data->pan_no;
$mobile = $dbrow->form_data->mobile;
$email = $dbrow->form_data->email;
$aadhar_no = $dbrow->form_data->aadhar_no;
$father_name = $dbrow->form_data->father_name;
$mother_name = $dbrow->form_data->mother_name;
$spouse_name = $dbrow->form_data->spouse_name;
$state = $dbrow->form_data->state;
$district = $dbrow->form_data->district;
$sub_division = $dbrow->form_data->sub_division;
$revenue_circle = $dbrow->form_data->revenue_circle;
$mouza = $dbrow->form_data->mouza;
$village_town = $dbrow->form_data->village_town;
$post_office = $dbrow->form_data->post_office;
$pin_code = $dbrow->form_data->pin_code;
$police_station = $dbrow->form_data->police_station;
$house_no = $dbrow->form_data->house_no;
$landline_number = $dbrow->form_data->landline_number;
$name_of_deceased = $dbrow->form_data->name_of_deceased;
$deceased_gender = $dbrow->form_data->deceased_gender;
$deceased_dob = $dbrow->form_data->deceased_dob;
$deceased_dod = $dbrow->form_data->deceased_dod;
$reason_of_death = $dbrow->form_data->reason_of_death;
$place_of_death = $dbrow->form_data->place_of_death;
$other_place_of_death = $dbrow->form_data->other_place_of_death;
$guardian_name_of_deceased = $dbrow->form_data->guardian_name_of_deceased;
$father_name_of_deceased = $dbrow->form_data->father_name_of_deceased;
$mother_name_of_deceased = $dbrow->form_data->mother_name_of_deceased;
$spouse_name_of_deceased = $dbrow->form_data->spouse_name_of_deceased;
$relationship_with_deceased = $dbrow->form_data->relationship_with_deceased;
$other_relation = isset($dbrow->form_data->other_relation)? $dbrow->form_data->other_relation: "";
$deceased_district = $dbrow->form_data->deceased_district;
$deceased_sub_division = $dbrow->form_data->deceased_sub_division;
$deceased_revenue_circle = $dbrow->form_data->deceased_revenue_circle;
$deceased_mouza = $dbrow->form_data->deceased_mouza;
$deceased_village = $dbrow->form_data->deceased_village;
$deceased_town = $dbrow->form_data->deceased_town;
$deceased_village = $dbrow->form_data->deceased_village;
$deceased_town = $dbrow->form_data->deceased_town;
$deceased_post_office = $dbrow->form_data->deceased_post_office;
$deceased_pin_code = $dbrow->form_data->deceased_pin_code;
$deceased_police_station = $dbrow->form_data->deceased_police_station;
$deceased_house_no = $dbrow->form_data->deceased_house_no;

$death_proof_type = $dbrow->form_data->death_proof_type??'';
$death_proof = $dbrow->form_data->death_proof??'';
$doc_for_relationship_type = $dbrow->form_data->doc_for_relationship_type??'';
$doc_for_relationship = $dbrow->form_data->doc_for_relationship??'';
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
                                <td>Applicant's Gender/ আবেদনকাৰীৰ লিংগ <br><strong><?=$applicant_gender?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?=$mobile?></strong> </td>
                                <td>E-Mail / ই-মেইল<br><strong><?=$email?></strong> </td>
                            </tr>
                            <tr>
                                <td>Date of Birth/ জন্ম তাৰিখ <br><strong><?=$dob?></strong> </td>
                                <td>PAN No./ পেন নং<br><strong><?=$pan_no?></strong> </td>
                            </tr>
                            <tr>
                                <td>Aadhar No./আধাৰ নং <br><strong><?=$aadhar_no?></strong> </td>
                                <td>Father's Name/ পিতৃৰ নাম<br><strong><?=$father_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mother's Name/ মাতৃৰ নাম <br><strong><?=$mother_name?></strong> </td>
                                <td>Name of Spouse/ স্বামী/পত্নীৰ নাম<br><strong><?=$spouse_name?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Applicant's Address / আবেদনকাৰীৰ ঠিকনা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>State/ ৰাজ্য<br><strong><?=$state?></strong> </td>
                                <td>District/ জিলা <br><strong><?=$district?></strong> </td>
                            </tr>
                            <tr>
                                <td>Sub-Division/ মহকুমা<br><strong><?=$sub_division?></strong> </td>
                                <td>Revenue Circle/ ৰাজহ চক্ৰ<br><strong><?=$revenue_circle?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mouza/ মৌজা <br><strong><?=$mouza?></strong> </td>
                                <td>Village/Town/ গাওঁ/চহৰ<br><strong><?=$village_town?></strong> </td>
                            </tr>
                            <tr>
                                <td>Post Office/ ডাকঘৰ <br><strong><?=$post_office?></strong> </td>
                                <td>Pin Code/ পিন ক'ড (e.g. 78xxxx)<br><strong><?=$pin_code?></strong> </td>
                            </tr>
                            <tr>
                                <td>Police Station/ থানা <br><strong><?=$police_station?></strong> </td>
                                <td>House No/ ঘৰ নং নাম<br><strong><?=$house_no?></strong> </td>
                            </tr>
                            <?php
                            if(!empty($landline_number)){
                            ?>
                            <tr>
                                <td>Landline Number/ দূৰভাস (if any) <br><strong><?=$landline_number?></strong> </td>
                                <td></td>
                            </tr>
                            <?php
                            }
                            ?>
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
                                <td>Date of Birth/ জন্মৰ তাৰিখ <br><strong><?=$deceased_dob?></strong> </td>
                                <td>Date of Death/ মৃত্যুৰ তাৰিখ<br><strong><?=$deceased_dod?></strong> </td>
                            </tr>
                            <tr>
                                <td>Reason of Death/ মৃত্যুৰ কাৰন<br><strong><?=$reason_of_death?></strong> </td>
                                <td>Place of Death/ মৃত্যুৰ ঠাই<br><strong><?=$place_of_death?></strong> </td>
                            </tr>
                            <tr>
                                <td>Other Place of Death (if any)/ অন্য মৃত্যুস্থান (যদি প্ৰযোজ্য) <br><strong><?=$other_place_of_death?></strong> </td>
                                <td>Father's/Guardian's Name of the Deceased/ মৃতকৰ পিতৃ/অভিভাৱকৰ নাম<br><strong><?=$guardian_name_of_deceased?></strong> </td>
                            </tr>
                            <tr>
                                <td>Father's Name of the Deceased/ মৃতকৰ পিতৃৰ নাম <br><strong><?=$father_name_of_deceased?></strong> </td>
                                <td>Mother Name of the Deceased/ মৃতকৰ মাতৃৰ নাম<br><strong><?=$mother_name_of_deceased?></strong> </td>
                            </tr>
                            <tr>
                                <td>Spouse Name of the Deceased/ মৃতকৰ স্বামী/পত্নীৰ নাম <br><strong><?=$spouse_name_of_deceased?></strong> </td>
                                <td>Relation of the Applicant with the Deceased/ মৃতকৰ লগত আবেদনকাৰীৰ সম্পৰ্ক<br><strong><?=$relationship_with_deceased?></strong> </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <?php if($relationship_with_deceased == "Other"){ ?>
                                    Enter Other Relation (If Any)/ অন্য সম্পৰ্ক প্ৰৱেশ কৰক (যদি আছে)<br><strong><?=$other_relation?></strong> 
                                    <?php } ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Address of the Deceased/ মৃতকৰ ঠিকনা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>District/ জিলা<br><strong><?=$deceased_district?></strong> </td>
                                <td>Sub-Division/ মহকুমা <br><strong><?=$deceased_sub_division?></strong> </td>
                            </tr>
                            <tr>
                                <td>Revenue Circle/ ৰাজহ চক্ৰ <br><strong><?=$deceased_revenue_circle?></strong> </td>
                                <td>Mouza/ মৌজা<br><strong><?=$deceased_mouza?></strong> </td>
                            </tr>
                            <tr>
                                <td>Village/ গাওঁ<br><strong><?=$deceased_village?></strong> </td>
                                <td>Town/চহৰ<br><strong><?=$deceased_town?></strong> </td>
                            </tr>
                            <tr>
                                <td>Post Office/ ডাকঘৰ <br><strong><?=$deceased_post_office?></strong> </td>
                                <td>Pin Code/ পিন ক'ড (e.g. 78xxxx)<br><strong><?=$deceased_pin_code?></strong> </td>
                            </tr>
                            <tr>
                                <td>Police Station/ থানা <br><strong><?=$deceased_police_station?></strong> </td>
                                <td>House No/ ঘৰ নং<br><strong><?=$deceased_house_no?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <?php
                    if (count($dbrow->form_data->family_details) > 0) {
                ?>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Family Details/পৰিয়ালৰ বিৱৰণ </legend>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name of Kin/ আত্মীয়ৰ নাম</th>
                                <th>Relation/ সম্পৰ্ক</th>
                                <th>Age(Y) on the Date of Application/ আবেদনৰ সময়ত বয়স (বছৰ)</th>
                                <th>Age(M) on the Date of Application/ আবেদনৰ সময়ত বয়স (মাহ)</th>
                                <th>Kin Alive or Dead/ আত্মীয় জীৱিত নে মৃত</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($dbrow->form_data->family_details as $key => $value) {
                                    ?>
                                    <tr>
                                        <td><?php print $value->name_of_kin; ?></td>
                                        <td><?php print $value->relation; ?></td>
                                        <td><?php print $value->age_y_on_the_date_of_application; ?></td>
                                        <td><?php print $value->age_m_on_the_date_of_application; ?></td>
                                        <td><?php print $value->kin_alive_dead; ?></td>
                                    </tr>
                                    <?php
                                }
                            ?>
                        </tbody>
                    </table>
                </fieldset>

               <?php }
                ?>
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
                                <td>Death Proof.</td>
                                <td style="font-weight:bold"><?=$death_proof_type?></td>
                                <td>
                                    <?php if(strlen($death_proof)){ ?>
                                        <a href="<?=base_url($death_proof)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Document for relationship proof .</td>
                                <td style="font-weight:bold"><?=$doc_for_relationship_type?></td>
                                <td>
                                    <?php if(strlen($doc_for_relationship)){ ?>
                                        <a href="<?=base_url($doc_for_relationship)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
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
                <?php if($appl_status === 'DRAFT') { ?>
                <a href="<?= base_url('spservices/nextofkin/registration/index/' . $obj_id) ?>" class="btn btn-primary">
                    <i class="fa fa-pencil"></i> Edit
                </a>
                <?php } ?>
                <button class="btn btn-warning" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <?php if(($appl_status != 'QA') && ($appl_status != 'QS')){ ?>
                <a href="<?= base_url('spservices/nextofkin/payment/initiate/' . $obj_id) ?>" class="btn btn-success">
                    <i class="fa fa-angle-double-right"></i> Make Payment
                </a>
                <?php }?>  
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>