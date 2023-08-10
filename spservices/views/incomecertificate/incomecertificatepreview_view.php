<?php
//pre($dbrow);
$obj_id = $dbrow->{'_id'}->{'$id'};  
$rtps_trans_id = $dbrow->service_data->rtps_trans_id;
//$user_type= "user";
$name_prefix = $dbrow->form_data->name_prefix; 
$applicant_name = $dbrow->form_data->applicant_name;
//$applicant_gender = $dbrow->form_data->applicant_gender;
$mobile = $dbrow->form_data->mobile;
$aadhar = $dbrow->form_data->aadhar;
$pan = $dbrow->form_data->pan;
$email = $dbrow->form_data->email;
$relation = $dbrow->form_data->relation;
$relative = $dbrow->form_data->relative;
$source_income = $dbrow->form_data->source_income;
$occupation = $dbrow->form_data->occupation;
$total_income = $dbrow->form_data->total_income;
$address1 = $dbrow->form_data->address1;
$address2 = $dbrow->form_data->address2;
$state = $dbrow->form_data->state;
$office_district = $dbrow->form_data->office_district;     
$district_name = $dbrow->form_data->district_name??'';   
$district_id  = $dbrow->form_data->district_name??'';   
//$sro_code = $dbrow->form_data->sro_code;
//$office_name = $dbrow->form_data->office_name;
$mouza = $dbrow->form_data->mouza;
$circle_name = $dbrow->form_data->circle_office??'';
$subdivision = $dbrow->form_data->subdivision;
$village = $dbrow->form_data->village;
//$village_name = $dbrow->form_data->village_name??'';
$policestation = isset($dbrow->form_data->policestation) ? $dbrow->form_data->policestation : '';
$postoffice = $dbrow->form_data->postoffice;
$pincode = $dbrow->form_data->pincode;
//$land_doc_ref_no =  $dbrow->form_data->land_doc_ref_no;
//$land_doc_reg_year =  $dbrow->form_data->land_doc_reg_year;    
//$delivery_mode = $dbrow->form_data->delivery_mode;
$address_proof_type = $dbrow->form_data->address_proof_type??'';
$address_proof = $dbrow->form_data->address_proof??'';
$identity_proof_type = $dbrow->form_data->identity_proof_type??'';
$identity_proof = $dbrow->form_data->identity_proof??'';
$land_revenue_receipt_type = $dbrow->form_data->land_revenue_receipt_type??'';
$land_revenue_receipt = $dbrow->form_data->land_revenue_receipt??'';
$salary_slip_type = $dbrow->form_data->salary_slip_type??'';
$salary_slip = $dbrow->form_data->salary_slip??'';
$other_doc_type = $dbrow->form_data->other_doc_type??'';
$other_doc = $dbrow->form_data->other_doc??'';
//$land_patta_type = $dbrow->form_data->land_patta_type??'';
//$land_patta = $dbrow->form_data->land_patta??'';
//$khajna_receipt_type = $dbrow->form_data->khajna_receipt_type??'';
//$khajna_receipt = $dbrow->form_data->khajna_receipt??'';
$soft_copy_type = $dbrow->form_data->soft_copy_type??'';
$soft_copy = $dbrow->form_data->soft_copy??'';
$status = $dbrow->service_data->appl_status;
$payment_status = $dbrow->form_data->payment_status??"UNPAID";
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
        
        $(document).on("click", "#finalsubmit", function(){
            Swal.fire({
                title: 'Are you sure?',
                text: 'Once you submitted, you won&apos;t able to revert this',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    window.location.href = "<?=base_url('spservices/incomecertificate/registration1/post_data/'.$obj_id)?>";
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

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the Applicant/আবেদনকাৰীৰ নাম<br><strong><?=$applicant_name?></strong> </td>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?=$mobile?></strong> </td>
                            </tr>
                            <tr>
                                <td>Aadhar Number<br><strong><?=$aadhar?></strong> </td>
                                <td>Pan Number/<br><strong><?=$pan?></strong> </td>
                            </tr>
                            <tr>
                                <td>E-Mail / ই-মেইল<br><strong><?=$email?></strong> </td>
                                <td>Relationship / সম্পৰ্ক<br><strong><?=$relation?></strong> </td>
                            <tr>
                                <td>Relative's Name/সম্পকীয়ৰ নাম<br><strong><?=$relative?></strong> </td>
                                <td>Source of Income /উপাৰ্ক নৰ উৎস<br><strong><?=$source_income?></strong> </td>
                            </tr>
                            <tr>
                                <td>Occupation/বৃচি<br><strong><?=$occupation?></strong> </td>
                                <td>Total Income/মুঠ উপাৰ্ক ন<br><strong><?=$total_income?></strong> </td>
                            </tr>

                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Permanent Address / স্হায়ী ঠিকনা</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">  
                            <tr>
                                <td>Address Line 1 / ঠিকনাৰ প্রথ্ম শাৰী<br><strong><?=$address1?></strong> </td>
                                <td>Address Line 2 / ঠিকনাৰ চিতীয় শাৰ<br><strong><?=$address2?></strong> </td>
                            </tr>
                            <tr>
                                <td>State / ৰাজ্য<br><strong><?=$state?></strong> </td>
                                <td>District / জিলা<br><strong><?=$office_district?></strong> </td>
                            </tr>
                            <tr>
                                <td>Subdivision/মহকুমা<br><strong><?=$subdivision?></strong> </td>
                                <td>Circle Office /ৰাৰ্হ িক্র<br><strong><?=$circle_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mouza / মৌজা<br><strong><?=$mouza?></strong> </td>
                                <td>Village / Town /গাও/ঁ টাউন<br><strong><?=$village?></strong> </td>
                            </tr>
                            <tr>
                                <td>Police Station / থানা<br><strong><?=$policestation?></strong> </td>
                                <td>Post Office / ডাকঘৰ<br><strong><?=$postoffice?></strong> </td>
                            </tr>
                            <tr>
                                <td>Pincode / পিনকোড<br><strong><?=$pincode?></strong> </td>
                               
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
                                <td>Address Proof</td>
                                <td style="font-weight:bold"><?=$address_proof_type?></td>
                                <td>
                                    <?php if(strlen($address_proof)){ ?>
                                        <a href="<?=base_url($address_proof)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Identity Proof</td>
                                <td style="font-weight:bold"><?=$identity_proof_type?></td>
                                <td>
                                    <?php if(strlen($identity_proof)){ ?>
                                        <a href="<?=base_url($identity_proof)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Land Revenue Receipt</td>
                                <td style="font-weight:bold"><?=$land_revenue_receipt_type?></td>
                                <td>
                                    <?php if(strlen($land_revenue_receipt)){ ?>
                                        <a href="<?=base_url($land_revenue_receipt)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Salary Slip</td>
                                <td style="font-weight:bold"><?=$salary_slip_type?></td>
                                <td>
                                    <?php if(strlen($salary_slip)){ ?>
                                        <a href="<?=base_url($salary_slip)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Any Other Document</td>
                                <td style="font-weight:bold"><?=$other_doc_type?></td>
                                <td>
                                    <?php if(strlen($other_doc)){ ?>
                                        <a href="<?=base_url($other_doc)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <?php if(strlen($soft_copy)) { ?>
                                <tr>
                                    <td>Upload Scanned Copy of the Applicant form</td>
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
                <?php if($status === 'DRAFT') { ?>
                    <a href="<?=base_url('spservices/incomecertificate/registration1/index/'.$obj_id)?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                <?php } ?>
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                 
                <?php if ($user_type == 'user') { ?>
                <a href="<?= base_url('spservices/incomecertificate/registration1/finalsubmition/' . $obj_id) ?>"
                    class="btn btn-success frmsbbtn">
                    <i class="fa fa-angle-double-right"></i> Submit
                </a>
                <?php } else { ?>
                <a href="<?= base_url('spservices/incomecertificate/payment/initiate/' . $obj_id) ?>"
                    class="btn btn-success frmsbbtn">
                    <i class="fa fa-angle-double-right"></i> Make Payment
                </a>
                <?php } ?>                    
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>