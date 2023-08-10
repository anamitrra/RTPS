<?php
$obj_id = $dbrow->{'_id'}->{'$id'};  
$rtps_trans_id = $dbrow->rtps_trans_id;
 
$applicant_name = $dbrow->applicant_name;
$applicant_gender = $dbrow->applicant_gender;
$father_name = $dbrow->father_name;
$applicant_address = $dbrow->applicant_address;
$mobile = $dbrow->mobile;
$email = $dbrow->email;

$office_district = $dbrow->office_district;     
$district_name = $dbrow->district_name??'';     
$sro_code = $dbrow->sro_code;
$office_name = $dbrow->office_name;

$circle = $dbrow->circle;
$circle_name = $dbrow->circle_name??'';
$village = $dbrow->village;
$village_name = $dbrow->village_name??'';
$plots = $dbrow->plots;

$searched_from = $dbrow->searched_from;
$searched_to = $dbrow->searched_to;
$land_doc_ref_no =  $dbrow->land_doc_ref_no;
$land_doc_reg_year =  $dbrow->land_doc_reg_year;    
$delivery_mode = $dbrow->delivery_mode;

$land_patta_type = $dbrow->land_patta_type??'';
$land_patta = $dbrow->land_patta??'';
$khajna_receipt_type = $dbrow->khajna_receipt_type??'';
$khajna_receipt = $dbrow->khajna_receipt??'';
$soft_copy_type = $dbrow->soft_copy_type??'';
$soft_copy = $dbrow->soft_copy??'';
$status = $dbrow->status;
$payment_status = $dbrow->payment_status??"UNPAID";
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
                    window.location.href = "<?=base_url('spservices/necertificate/post_data/'.$obj_id)?>";
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
                    <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the Applicant/আবেদনকাৰীৰ নাম<br><strong><?=$applicant_name?></strong> </td>
                                <td>Applicant's Gender/ আবেদনকাৰীৰ লিংগ <br><strong><?=$applicant_gender?></strong> </td>
                            </tr>
                            <tr>
                                <td>Father's/Husband's Name / পিতৃৰ নাম<br><strong><?=$father_name?></strong> </td>
                                <td>Address of the applicant/আবেদনকাৰীৰ ঠিকনা<br><strong><?=$applicant_address?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?=$mobile?></strong> </td>
                                <td>E-Mail / ই-মেইল<br><strong><?=$email?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Office for application submission/আবেদন জমা কৰিবলগীয়া কাৰ্য্যালয়</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">                            
                            <tr>
                                <td>District/জিলা নিৰ্বাচন কৰক<br><strong><?=$district_name?></strong> </td>
                                <td> Office/কাৰ্য্যালয় নিৰ্বাচন কৰক<br><strong><?=$office_name?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Particulars of land/মাটি'ৰ বিৱৰণ</legend>
                    <table>
                        <tbody>
                            <tr>
                                <td>Circle(p) / ৰাজহ চক্ৰ<br><strong><?=$circle_name?></strong> </td>
                                <td>Revenue Village/ গাওঁ/চহৰ<br><strong><?=$village_name?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered" id="financialstatustbl">
                        <thead>
                            <tr>
                                <th>Patta Number/পট্টা নং(Please specify Old/New)</th>
                                <th>Daag Number/দাগ নং</th>
                                <th>Land Area (in Bigha, Katha, Lessa)/মাটি'ৰ কালি( বিঘা, কঠা, লেচা)</th>
                                <th>Patta Type/পট্টা প্ৰকাৰ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($plots)) {
                                foreach($plots as $plot) { ?>
                                    <tr>
                                        <td><?=$plot->patta_no?></td>
                                        <td><?=$plot->dag_no?></td>
                                        <td><?=$plot->land_area?></td>
                                        <td><?=$plot->patta_type?></td>
                                    </tr>
                                 <?php }
                            }//End of if else  ?>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Other Details / অন্যান্য তথ্য </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Records to be searched from/কেতিয়াৰ পৰা তথ্য লাগে<br><strong><?=$searched_from?></strong> </td>
                                <td>Records to be searched to/কেতিয়ালৈ তথ্য লাগে<br><strong><?=$searched_to?></strong> </td>
                            </tr>
                            <tr>
                                <td>Reference no of the land document to be uploaded/মাটি'ৰ তথ্যৰ নাম<br><strong><?=$land_doc_ref_no?></strong> </td>
                                <td>Year on which the document is registered/তথ্য নিবন্ধিত বছৰ<br><strong><?=$land_doc_reg_year?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                
                <!--<fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">Mode of service delivery/সেৱা প্ৰদানৰ প্ৰকাৰ</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Desired mode/প্ৰকাৰ বাছনি কৰক</td>
                                <td><strong><?=($delivery_mode === 'delivery_general')?'General (Delivery within 15 days)/সাধাৰণ ( ১৫ দিনৰ ভিতৰত)':'Urgent (Delivery within 3 days)/জৰুৰী ( ৩ দিনৰ ভিতৰত )'?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>-->
                
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
                                <td>Up-to-date Original Land Documents.</td>
                                <td style="font-weight:bold"><?=$land_patta_type?></td>
                                <td>
                                    <?php if(strlen($land_patta)){ ?>
                                        <a href="<?=base_url($land_patta)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Up-to-date Khajna Receipt.</td>
                                <td style="font-weight:bold"><?=$khajna_receipt_type?></td>
                                <td>
                                    <?php if(strlen($khajna_receipt)){ ?>
                                        <a href="<?=base_url($khajna_receipt)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
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
                <?php if($status === 'DRAFT') { ?>
                    <a href="<?=base_url('spservices/necertificate/index/'.$obj_id)?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                <?php } ?>
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <?php if($payment_status !== 'PAID') { ?>    
                    <a href="<?=base_url('spservices/necpayment/initiate/'.$obj_id)?>" class="btn btn-warning frmsbbtn">
                        <i class="fa fa-angle-double-right"></i> Make Payment
                    </a>
                <?php } ?>                    
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>