<?php

$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;
$appl_status = $dbrow->service_data->appl_status;
$pageTitle = $dbrow->service_data->service_name;
$pageTitleId = $dbrow->form_data->service_id;

$pre_certificate_no =  $dbrow->form_data->pre_certificate_no ?? null;
$pre_mobile_no =  $dbrow->form_data->pre_mobile_no ?? null;

$applicant_title = $dbrow->form_data->applicant_title_name;
$first_name = $dbrow->form_data->first_name;
$last_name = $dbrow->form_data->last_name;
$applicant_gender = $dbrow->form_data->applicant_gender_name;
$caste = $dbrow->form_data->caste;
$father_title =$dbrow->form_data->father_title;
$father_name = $dbrow->form_data->father_name;
$aadhar_no =$dbrow->form_data->aadhar_no;
$mobile =  $dbrow->form_data->mobile; 
$email =$dbrow->form_data->email;


$district =$dbrow->form_data->district_name;
$police_station =$dbrow->form_data->police_station;
$post_office = $dbrow->form_data->post_office;

$name_of_firm =$dbrow->form_data->name_of_firm; 
$name_of_proprietor =$dbrow->form_data->name_of_proprietor;
$occupation_trade  = $dbrow->form_data->occupation_trade ;
$community = $dbrow->form_data->community;
$class_of_business = $dbrow->form_data->class_of_business;
$address = $dbrow->form_data->address;
$business_locality = $dbrow->form_data->business_locality;
$business_word_no =$dbrow->form_data->business_word_no;
$reason_for_consideration =$dbrow->form_data->reason_for_consideration;
$rented_owned =$dbrow->form_data->rented_owned;
$name_of_owner = $dbrow->form_data->name_of_owner;


$signature = $dbrow->form_data->signature ?? '';
$signature_type = $dbrow->form_data->signature_type ?? '';

$photo = $dbrow->form_data->photo ?? '';
$photo_type = $dbrow->form_data->photo_type ?? '';
$identity_proof = $dbrow->form_data->identity_proof ?? '';
$identity_proof_type = $dbrow->form_data->identity_proof_type ?? '';   
$address_proof = $dbrow->form_data->address_proof ?? '';
$address_proof_type = $dbrow->form_data->address_proof_type ?? '';   
$house_tax_reciept = $dbrow->form_data->house_tax_reciept ?? '';
$house_tax_reciept_type = $dbrow->form_data->house_tax_reciept_type ?? '';   
$room_rent_deposite = $dbrow->form_data->room_rent_deposite ?? '';
$room_rent_deposite_type = $dbrow->form_data->room_rent_deposite_type ?? '';   
$consideration_letter = $dbrow->form_data->consideration_letter ?? '';
$consideration_letter_type = $dbrow->form_data->consideration_letter_type ?? '';   
$cur_business_copy_rc = $dbrow->form_data->cur_business_copy_rc ?? '';
$cur_business_copy_rc_type = $dbrow->form_data->cur_business_copy_rc_type ?? '';   


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

                <?php if ($pageTitleId == "KRBC") { ?>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Previous Applicant Details/ পূৰ্বৰ আবেদনকাৰীৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Enter Business Registration Certificate No. / ব্যৱসায় পঞ্জীয়ন প্ৰমাণপত্ৰ নং<br>
                                <strong><?= $pre_certificate_no ?></strong> </td>
                                <td>Mobile Number / দুৰভাষ (মবাইল)  <br><strong><?= $pre_mobile_no ?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                        
                    </fieldset>
                    <?php } ?>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Applicant&apos;s  Name/ আবেদনকাৰীৰ  নাম<br><strong><?= $applicant_title.' '.$first_name.' '.$last_name  ?></strong> </td>
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
                    <legend class="h5">Applicant Address/ ঠিকনা</legend>
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
                    <legend class="h5">Business Details / ব্যৱসায়িক বিৱৰণ</legend>
                    <table class="table table-borderless" style="margin-top:0px;margin-bottom:0px;border-collapse: collapse;width: 100%;table-layout: fixed;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the Firm/ ফাৰ্মৰ নাম<br><strong><?= $name_of_firm ?></strong> </td>
                                <td>Name of Proprietor/ মালিকৰ নাম<br><strong><?= $name_of_proprietor ?></strong> </td>
                                <td>Occupation/Trade/ বৃত্তি/বাণিজ্য<br><strong><?= $occupation_trade ?></strong></td>
                            </tr>
                            <tr>
                                <td>Community/ সমুদায়<br><strong><?= $community ?></strong></td>
                                <td>Address/ ঠিকনা<br><strong><?= $address ?></strong></td>
                                <td>Business Location (By Locality)/ ব্যৱসায়িক স্থান (স্থানীয়তা অনুসৰি)<br><strong><?= $business_locality ?></strong></td>
                                </tr>
                            <tr>
                                <td>Business Location(By Ward No)/ ব্যৱসায়িক স্থান(ৱাৰ্ড নং অনুসৰি)<br><strong><?= $business_word_no ?></strong></td>
                                <td>Special reason for Consideration / বিবেচনাৰ বিশেষ কাৰণ<br><strong><?= $reason_for_consideration ?></strong></td>
                                <td>Building Rented/Owned / বিল্ডিং ভাড়া/মালিকানাধীন<br><strong><?= $rented_owned ?></strong></td>
                                </tr>
                              
                                <?php if($rented_owned =="Rent") { ?>
                                <tr>
                                    <td>Name of Owner (if Rented)/মালিকৰ নাম (যদি ভাড়াত দিয়া হয়)<br><strong><?= $name_of_owner ?></strong></td>
                                </tr>
                                <?php } ?>
                            
                            

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
                            <?php } if (strlen($address_proof)) { ?>
                                <tr>
                                    <td>Address Proof</td>
                                    <td style="font-weight:bold"><?= $address_proof_type ?></td>
                                    <td>
                                        <?php if (strlen($address_proof)) { ?>
                                            <a href="<?= base_url($address_proof) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php }
                            if (strlen($identity_proof)) { ?>
                                <tr>
                                    <td>Identity Proof</td>
                                    <td style="font-weight:bold"><?= $identity_proof_type ?></td>
                                    <td>
                                        <?php if (strlen($identity_proof)) { ?>
                                            <a href="<?= base_url($identity_proof) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php } if (strlen($house_tax_reciept)) { ?>
                                <tr>
                                    <td>House Tax Receipt </td>
                                    <td style="font-weight:bold"><?= $house_tax_reciept_type ?></td>
                                    <td>
                                        <?php if (strlen($house_tax_reciept)) { ?>
                                            <a href="<?= base_url($house_tax_reciept) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php  if (strlen($room_rent_deposite)) { ?>
                                <tr>
                                    <td>Valid MBTC Room rent deposit </td>
                                    <td style="font-weight:bold"><?= $room_rent_deposite_type ?></td>
                                    <td>
                                        <?php if (strlen($room_rent_deposite)) { ?>
                                            <a href="<?= base_url($room_rent_deposite) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php  if (strlen($consideration_letter)) { ?>
                                <tr>
                                    <td>Special reason for Consideration letter</td>
                                    <td style="font-weight:bold"><?= $consideration_letter_type ?></td>
                                    <td>
                                        <?php if (strlen($consideration_letter)) { ?>
                                            <a href="<?= base_url($consideration_letter) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>

                            <?php  if (strlen($cur_business_copy_rc)) { ?>
                                <tr>
                                    <td>Copy of current Business Registration Certificate</td>
                                    <td style="font-weight:bold"><?= $cur_business_copy_rc_type ?></td>
                                    <td>
                                        <?php if (strlen($cur_business_copy_rc)) { ?>
                                            <a href="<?= base_url($cur_business_copy_rc) ?>" class="btn btn-block font-weight-bold text-success" target="_blank">
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
                    <a href="<?= base_url('spservices/kaac_brc/registration/index/' . $obj_id) ?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>

                    
                <?php } ?>
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <?php if ($appl_status == 'FRS' || $appl_status == 'DRAFT' || $appl_status =='payment_initiated') { ?>                    
                    <a href="<?= base_url('spservices/kaac_brc/payment/initiate/' . $obj_id) ?>" class="btn btn-success">
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