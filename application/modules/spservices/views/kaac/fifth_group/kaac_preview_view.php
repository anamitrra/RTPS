<?php

$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;
$appl_status = $dbrow->service_data->appl_status;
$pageTitle = $dbrow->service_data->service_name;
$pageTitleId = $dbrow->form_data->service_id;

$applicant_name = $dbrow->form_data->applicant_name;
$applicant_title = $dbrow->form_data->applicant_title;
$first_name = $dbrow->form_data->first_name;
$middle_name =$dbrow->form_data->middle_name;
$last_name = $dbrow->form_data->last_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$caste = $dbrow->form_data->caste;
$father_title = $dbrow->form_data->father_title;
$father_name = $dbrow->form_data->father_name;
$aadhar_no =  $dbrow->form_data->aadhar_no;
$mobile = $dbrow->form_data->mobile; 
$email = $dbrow->form_data->email;


$district =  $dbrow->form_data->district_name;
$police_station = $dbrow->form_data->police_station;
$post_office = $dbrow->form_data->post_office;

$bank_account_no=$dbrow->form_data->bank_account_no;
$bank_name= $dbrow->form_data->bank_name;
$bank_branch=$dbrow->form_data->bank_branch;
$ifsc_code= $dbrow->form_data->ifsc_code;


$land_district =  $dbrow->form_data->land_district_name;
$sub_division =  $dbrow->form_data->sub_division;
$circle_office =  $dbrow->form_data->circle_office_name;
$mouza_name =  $dbrow->form_data->mouza_office_name;
$revenue_village = $dbrow->form_data->revenue_village_name;

$patta_type = $dbrow->form_data->patta_type_name;
$dag_no =  $dbrow->form_data->dag_no;
$patta_no = $dbrow->form_data->patta_no;
$name_of_pattadar =$dbrow->form_data->name_of_pattadar;
$pattadar_father_name = $dbrow->form_data->pattadar_father_name;
$relationship_with_pattadar = $dbrow->form_data->relationship_with_pattadar;
$land_category = $dbrow->form_data->land_category;
$cultivated_land = $dbrow->form_data->cultivated_land;
$production = $dbrow->form_data->production;
$crop_variety = $dbrow->form_data->crop_variety_name;
$surplus_production =$dbrow->form_data->surplus_production;
$cultivator_type = $dbrow->form_data->cultivator_type_name;

$bigha = $dbrow->form_data->bigha;
$kotha = $dbrow->form_data->kotha;
$loosa =  $dbrow->form_data->loosa;
$land_area = $dbrow->form_data->land_area;

$ado_circle_office = $dbrow->form_data->ado_circle_office_name;



$signature = $dbrow->form_data->signature ?? '';
$signature_type = $dbrow->form_data->signature_type ?? '';

$land_owner_signature = $dbrow->form_data->land_owner_signature ?? '';
$land_owner_signature_type = $dbrow->form_data->land_owner_signature_type ?? '';

$photo = $dbrow->form_data->photo ?? '';
$photo_type = $dbrow->form_data->photo_type ?? '';

$bank_passbook = $dbrow->form_data->bank_passbook ?? '';
$bank_passbook_type = $dbrow->form_data->bank_passbook_type ?? '';   

$aadhaar_card = $dbrow->form_data->aadhaar_card ?? '';
$aadhaar_card_type = $dbrow->form_data->aadhaar_card_type ?? '';   


$land_records = $dbrow->form_data->land_records ?? '';
$land_records_type = $dbrow->form_data->land_records_type ?? '';   


$land_document = $dbrow->form_data->land_document ?? '';
$land_document_type = $dbrow->form_data->land_document_type ?? '';   


$agreement_copy = $dbrow->form_data->agreement_copy ?? '';
$agreement_copy_type = $dbrow->form_data->agreement_copy_type ?? '';   


$ncla_certificate = $dbrow->form_data->ncla_certificate ?? '';
$ncla_certificate_type = $dbrow->form_data->ncla_certificate_type ?? '';   


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

    .disabled-link {
        pointer-events: none;
        cursor: default;
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
            <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                <h4><b><?= $pageTitle ?><br>
                <?= $pageTitleAssamese ?><b></h4>
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
                
                <?php if(isset($dbrow->processing_history)) {?> Application Ref. No: <?= $appl_ref_no  ?><?php  } ?>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Farmer's Basic Details / কৃষকৰ মৌলিক বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Applicant&apos;s  Name/ আবেদনকাৰীৰ  নাম<br><strong><?= $applicant_name  ?></strong> </td>
                                <td>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ  <br><strong><?= $applicant_gender ?></strong> </td>
                                <td>Applicant&apos;s Caste/ আবেদনকাৰীৰ লিংগ <br><strong><?= $caste ?></strong></td>
                            </tr>

                            <tr>
                                <td>Father&apos;s/Husband&apos;s Name/ পিতৃৰ নাম<br><strong><?= $father_title.' '.$father_name ?></strong></td>
                                <td>Aadhar Number / আধাৰ নম্বৰ<br><strong><?= $aadhar_no ?></strong> </td>
                                <td>E-Mail / ই-মেইল<br><strong><?= $email ?></strong> </td>
                            </tr>
                            <tr>
                                 <td>Mobile Number / দুৰভাষ (মবাইল)<br><strong><?= $mobile ?></strong> </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                           
                            <tr>
                                <td>District/জিলা <br><strong><?= $district ?></strong></td>
                                <td>Post Office/ ডাকঘৰ <br><strong><?= $post_office ?></strong></td>
                                <td>Police Station/ থানা <br><strong><?= $police_station ?></strong></td>
                            </tr>

                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                <legend class="h5">Farmer's Bank Details/ কৃষক বেংকৰ সবিশেষ </legend>

                    <table class="table table-borderless" style="margin-top:0px;margin-bottom:0px;border-collapse: collapse;width: 100%;table-layout: fixed;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Bank Account No./ বেংক একাউণ্ট নং<br><strong><?= $bank_account_no ?></strong> </td>
                                <td>Bank Name/বেংকৰ নাম<br><strong><?= $bank_name ?></strong> </td>
                                <td>Bank Branch/ বেংক শাখা <br><strong><?= $bank_branch ?></strong></td>
                                <td>IFSC Code/আই এফ এছ চি ক'ড  <br><strong><?= $ifsc_code ?></strong></td>
                                
                            </tr>        
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Land details/ ভূমিৰ সবিশেষ </legend>

                    <table class="table table-borderless" style="margin-top:0px;margin-bottom:0px;border-collapse: collapse;width: 100%;table-layout: fixed;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>District / জিলা <br><strong><?= $land_district ?></strong> </td>
                                <td>Sub-Division / মহকুমা<br><strong><?= $sub_division ?></strong> </td>
                                <td>Circle/ চক্ৰ <br><strong><?= $circle_office ?></strong></td>
                                
                            </tr>        
                            <tr>
                                <td>Mouza/ মৌজা<br><strong><?= $mouza_name ?></strong> </td>
                                <td>Revenue Village/ ৰাজহ গাঁও<br><strong><?= $revenue_village ?></strong> </td>                                
                            </tr>    
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success table-responsive" style="margin-top:40px">

                    <table class="table table-borderless" style="margin-top:0px;margin-bottom:0px;border-collapse: collapse;width: 100%;table-layout: fixed;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Patta Type/ পট্টা টাইপ<br><strong><?= $patta_type ?></strong> </td>
                                <td>Dag No. / ডাগ নং<br><strong><?= $dag_no ?></strong> </td>
                                <td>Patta No. / ফ্লেপ নং<br><strong><?= $patta_no ?></strong></td>
                                
                            </tr>        
                            <tr>
                                <td>Name of Pattadar / নাম পট্টাদৰ<br><strong><?= $name_of_pattadar ?></strong> </td>
                                <td>Pattadar Father Name /পট্টাদাৰ পিতৃৰ নাম<br><strong><?= $pattadar_father_name ?></strong> </td>                                
                                <td>Relationship with pattadar/ পট্টাদাৰৰ সৈতে সম্পৰ্ক<br><strong><?= $relationship_with_pattadar ?></strong> </td>                                
                            </tr>   
                            <tr>
                                <td>Land Category / ভূমিৰ শ্ৰেণী<br><strong><?= $land_category ?></strong> </td>
                                <td>Cultivated Land (In Bigha Only) / খেতি কৰা ভূমি (কেৱল বিঘাত)<br><strong><?= $cultivated_land ?></strong> </td>                                
                                <td>Production (In Quintals Only)/ উৎপাদন (কেৱল কুইণ্টালত)<br><strong><?= $production ?></strong> </td>                                
                            </tr>   
                            <tr>
                                <td>Crop Variety/ শস্যৰ জাত<br><strong><?= $crop_variety ?></strong> </td>
                                <td>Surplus Production / উদ্বৃত্ত উৎপাদন<br><strong><?= $surplus_production ?></strong> </td>                                
                                <td>Cultivator Type/ খেতিয়কৰ প্ৰকাৰ<br><strong><?= $cultivator_type ?></strong> </td>                                
                            </tr>   
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">

                    <table class="table table-borderless" style="margin-top:0px;margin-bottom:0px;border-collapse: collapse;width: 100%;table-layout: fixed;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Bigha / বিঘা  <br><strong><?= $bigha ?></strong> </td>
                                <td>Kotha / কঠা<br><strong><?= $kotha ?></strong> </td>
                                <td>Loosa / লেচা<br><strong><?= $loosa ?></strong></td>
                                <td>Land Area / ভূমিৰ আয়তন<br><strong><?= $land_area ?></strong></td>                                
                            </tr>                                      
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                <legend class="h5">Submission Location/ জমা দিয়াৰ স্থান </legend>
                    <table class="table table-borderless" style="margin-top:0px;margin-bottom:0px;border-collapse: collapse;width: 100%;table-layout: fixed;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>ADO Circle Office/ এ ডি অ’ চাৰ্কল অফিচ - <strong><?= $ado_circle_office ?></strong></td>                                
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
                        <?php if (strlen($photo)) { ?>
                                <tr>
                                    <td>Photo</td>
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
                            <?php } if (strlen($signature)) { ?>
                                <tr>
                                    <td>Signature</td>
                                    <td style="font-weight:bold"><?= $signature_type ?></td>
                                    <td>
                                        <?php if (strlen($signature)) { ?>
                                            <a href="<?= base_url($signature) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <?php } if (strlen($land_owner_signature)) { ?>
                                <tr>
                                    <td>Land Owner Signature</td>
                                    <td style="font-weight:bold"><?= $land_owner_signature_type ?></td>
                                    <td>
                                        <?php if (strlen($land_owner_signature)) { ?>
                                            <a href="<?= base_url($land_owner_signature) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php } if (strlen($bank_passbook)) { ?>
                                <tr>
                                    <td>Photocopy of Bank Passbook of Applicant</td>
                                    <td style="font-weight:bold"><?= $bank_passbook_type ?></td>
                                    <td>
                                        <?php if (strlen($bank_passbook)) { ?>
                                            <a href="<?= base_url($bank_passbook) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php }
                            if (strlen($aadhaar_card)) { ?>
                                <tr>
                                    <td>Aadhar Card</td>
                                    <td style="font-weight:bold"><?= $aadhaar_card_type ?></td>
                                    <td>
                                        <?php if (strlen($aadhaar_card)) { ?>
                                            <a href="<?= base_url($aadhaar_card) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php }?>
                            <?php  if (strlen($ncla_certificate)) { ?>
                                <tr>
                                    <td>Certificate of Non-Cadastral Land Area</td>
                                    <td style="font-weight:bold"><?= $ncla_certificate_type ?></td>
                                    <td>
                                        <?php if (strlen($ncla_certificate)) { ?>
                                            <a href="<?= base_url($ncla_certificate) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if (strlen($land_document)) { ?>
                                <tr>
                                    <td>Land Document</td>
                                    <td style="font-weight:bold"><?= $land_document_type ?></td>
                                    <td>
                                        <?php if (strlen($land_document)) { ?>
                                            <a href="<?= base_url($land_document) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php } if (strlen($land_records)) { ?>
                                <tr>
                                    <td>Certificate of Non-digitization/Non-integration of Land Records</td>
                                    <td style="font-weight:bold"><?= $land_records_type ?></td>
                                    <td>
                                        <?php if (strlen($land_records)) { ?>
                                            <a href="<?= base_url($land_records) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php  if (strlen($agreement_copy)) { ?>
                                <tr>
                                    <td>Agreement Copy (In case of contract farming)</td>
                                    <td style="font-weight:bold"><?= $agreement_copy_type ?></td>
                                    <td>
                                        <?php if (strlen($agreement_copy)) { ?>
                                            <a href="<?= base_url($agreement_copy) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>

                            
                        </tbody>
                    </table>
                </fieldset>
            </div>
            <!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <?php if ($appl_status === 'DRAFT') { ?>
                    <a href="<?= base_url('spservices/kaac_farmer/registration/index/' . $obj_id) ?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>

                    
                <?php } ?>
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <?php if ($appl_status == 'FRS' || $appl_status == 'DRAFT' || $appl_status =='payment_initiated') { ?>                    
                    <a href="<?= base_url('spservices/kaac_farmer/payment/initiate/' . $obj_id) ?>" class="btn btn-success">
                        <i class="fa fa-angle-double-right"></i> Make Payment
                    </a>
                <?php } ?>
                
            </div>
            <!--End of .card-footer-->
        </div>
        <!--End of .card-->
    </div>
    <!--End of .container-->
</main>