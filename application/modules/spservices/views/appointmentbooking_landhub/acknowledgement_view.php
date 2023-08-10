<?php 
$appl_ref_no = isset($dbrow->service_data->appl_ref_no) ? $dbrow->service_data->appl_ref_no : '';
$submission_date = isset($dbrow->service_data->submission_date) ? format_mongo_date($dbrow->service_data->submission_date) : '';
$applicant_name = $dbrow->form_data->applicant_name;
$service_name = isset($service_row->service_name) ? $service_row->service_name : '';
$department_name = isset($service_row->department_name) ? $service_row->department_name : '';
$stipulated_timeline = isset($service_row->stipulated_timeline) ? $service_row->stipulated_timeline . ' days' : '';
$amount = isset($dbrow->form_data->amount) ? $dbrow->form_data->amount : 0;
$pfc_payment_status = isset($dbrow->form_data->pfc_payment_status) ? $dbrow->form_data->pfc_payment_status : '';
$rtps_convenience_fee = $dbrow->form_data->rtps_convenience_fee;
$service_charge = isset($dbrow->form_data->service_charge) ? $dbrow->form_data->service_charge : 0;
$no_printing_page = isset($dbrow->form_data->no_printing_page)?$dbrow->form_data->no_printing_page:0;
$printing_charge_per_page = isset($dbrow->form_data->printing_charge_per_page)?$dbrow->form_data->printing_charge_per_page:0;
$no_scanning_page = isset($dbrow->form_data->no_scanning_page)?$dbrow->form_data->no_scanning_page:0;
$scanning_charge_per_page = isset($dbrow->form_data->scanning_charge_per_page)?$dbrow->form_data->scanning_charge_per_page:0;
?>
<style type="text/css">
    .print {
        visibility:visible;
    }
</style>
<link rel="stylesheet" href="<?=base_url('assets/css/acknowledgment.css')?>">
<div class="content-wrapper">
    <div class="container">
        <div class="row " id="print">
            <div class="col-sm-12 mx-auto">
                <div class="card my-4 " style="border: 1px solid;padding:20px">
                    <div class="card-body">
                        <div class="row" >
                            <img class="img" src="<?= base_url("assets/frontend/images/logo_artps.png") ?>" width="100" height="100"/>
                        </div>

                        <div class="row" >
                            <h3 class="h2"><u>Application Acknowledgement</u></h3>
                        </div>
                        <div class="row akno">
                            <span><u>Acknowledgement no: </u><?=$appl_ref_no?></span>
                            <span style="float: right;"><u>Date:</u><?=$submission_date?></span>
                        </div>
                        <div class="row">
                            <p style="text-align:justify; line-height: 28px">
                                Dear <strong><?=$applicant_name?></strong>,
                                <br/>
                                <br/>
                                Your application for <strong> <?=$service_name?></strong> has been submitted successfully on <strong><?=$submission_date?></strong> and
                                your Acknowledgement No. is <strong><?=$appl_ref_no?></strong>. Please use this Acknowledgement
                                number for tracking the application and for any future communication related to this application.
                                If the application has been accepted by the <strong><?=$department_name?></strong> the service shall be provided within
                                stipulated timeline . You may raise an appeal at http://rtps.assam.gov.in/ if the service is not delivered within the <strong><?=$stipulated_timeline?></strong> days.
                                You can call us at 1800-345-3574, Mon-Sat - 8am to 8pm or write us at rtps-assam@assam.gov.in for any feedback or grievances.

                            </p>
                        </div>
                        <!-- fee description -->
                        <?php if ($amount || ($pfc_payment_status === "Y")) { ?>

                            <div class="row">
                                The following fees has been collected for this application:
                            </div>
                            <br/>
                            <br/>
                            <div class="row">
                                <table>
                                    <?php if ($amount): ?>
                                        <tr>
                                            <td><b>Application Fee:</b></td>
                                            <td><?= $amount ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if ($rtps_convenience_fee): ?>
                                        <tr>
                                            <td><b>Convenience fee:</b></td>
                                            <td><?= $rtps_convenience_fee ?></td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php if ($service_charge): ?>
                                        <tr>
                                            <td><b>Service Charge:</b></td>
                                            <td><?= $service_charge ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if ($no_printing_page): ?>
                                        <tr>
                                            <td><b>Printing Fee:</b></td>
                                            <td><?=sprintf("%0.2f", $no_printing_page*$printing_charge_per_page)?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if ($no_scanning_page): ?>
                                        <tr>
                                            <td><b>Scanning Fee:</b></td>
                                            <td><?=sprintf("%0.2f", $no_scanning_page*$scanning_charge_per_page)?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td>================</td>
                                        <td>========</td>
                                    </tr>

                                    <tr>
                                        <td><b>Total:</b></td>
                                        <td><?= calculateTotal($dbrow) ?></td>
                                    </tr>
                                </table>
                            </div>

                        <?php } ?>

                        <!-- end of fee description -->

                        <br/>
                        <br/>
                        <div class="row">
                            <b>Thank You.</b>
                        </div>
                        <div class="row">
                            <b>ARTPS</b>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--End of print -->
        
        <div class="row " id="printAssamese">
            <div class="col-sm-12 mx-auto">
                <div class="card my-4 " style="border: 1px solid;padding:20px">
                    <div class="card-body">
                        <div class="row" >
                            <img class="img" src="<?= base_url("assets/frontend/images/logo_artps.png") ?>" width="100" height="100"/>
                        </div>

                        <div class="row" >
                            <h3 class="h2"><u>আবেদন স্বীকৃতি</u></h3>
                        </div>
                        <div class="row akno">
                            <span><u>স্বীকৃতি নম্বৰ: </u><?=$appl_ref_no?></span>
                            <span style="float: right;"><u>তাৰিখ:</u><?=$submission_date?></span>
                        </div>
                        <div class="row">
                             <p style="text-align:justify; line-height: 28px">
                                প্ৰিয় <strong><?=$applicant_name?></strong>,
                                <br/>
                                <br/>
                                <strong> <?=$service_name?></strong>  বাবে দাখিল কৰা  আপোনাৰ আবেদনপত্রখন <strong><?=$submission_date?></strong>  তাৰিখে সফলতাৰে সম্পূর্ণ হৈছে আৰু আপোনাৰ স্বীকৃতি সংখ্যা হৈছে,  <strong><?=$appl_ref_no?></strong> । ভৱিষ্যতে আবেদন সম্পর্কীয় যিকোনো যোগাযোগৰ বাবে আৰু আবেদনৰ স্থিতি নিৰীক্ষণ কৰিবলৈ, অনুগ্ৰহ কৰি এই স্বীকৃতি নম্বৰটো ব্যৱহাৰ কৰক। যদি আবেদনপত্ৰখন  <strong><?=$department_name?></strong> বিভাগৰ দ্বাৰা গ্ৰহণ কৰা হৈছে, তেন্তে <strong><?=$stipulated_timeline?></strong> দিনৰ ভিতৰত সেৱা প্ৰদান কৰিব লাগিব। যদি <strong><?=$stipulated_timeline?></strong> দিনৰ ভিতৰত সেৱা আগবঢ়োৱা নহয়, তেনেহলে আবেদনকাৰীয়ে http://rtps.assam.gov.in/ ত আবেদন কৰিব পাৰে। আপুনি আমাক ১৮০০-৩৪৫-৩৫৭৪ নম্বৰত  প্ৰতি সোমবাৰৰ পৰা শনিবাৰলৈ, পুৱা ৮ বজাৰ পৰা নিশা ৮ বজালৈ ফোন কৰিব পাৰে অথবা 
                                কোনো ধৰণৰ মতামত বা অভিযোগৰ বাবে  rtps-assam@assam.gov.in লৈ ইমেইল প্ৰেৰণ কৰিব পাৰে। 
                            </p>
                        </div>

                        <!-- fee description -->
                        <?php if ($amount || ($pfc_payment_status === "Y")) { ?>

                            <div class="row">
                                এই আবেদনৰ বাবে নিম্নলিখিত মাচুল সংগ্ৰহ কৰা হৈছে:
                            </div>
                            <br/>
                            <br/>
                            <div class="row">
                                <table>
                                    <?php if ($amount): ?>
                                        <tr>
                                            <td><b>আবেদন মাচুল:</b></td>
                                            <td><?= sprintf("%.2f", $amount) ?></td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php if ($rtps_convenience_fee): ?>
                                        <tr>
                                            <td><b>সুবিধা মাচুল:</b></td>
                                            <td><?= $rtps_convenience_fee ?></td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php if ($service_charge): ?>
                                        <tr>
                                            <td><b>সেৱা মাচুল:</b></td>
                                            <td><?= $service_charge ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if ($no_printing_page): ?>
                                        <tr>
                                            <td><b>প্ৰিন্ট কৰা মাচুল:</b></td>
                                            <td><?=sprintf("%0.2f", $no_printing_page*$printing_charge_per_page)?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if ($no_scanning_page): ?>
                                        <tr>
                                            <td><b>স্কেনিং মাচুল:</b></td>
                                            <td><?=sprintf("%0.2f", $no_scanning_page*$scanning_charge_per_page)?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td>================</td>
                                        <td>========</td>
                                    </tr>

                                    <tr>
                                        <td><b>মুঠ:</b></td>
                                        <td><?= calculateTotal($dbrow) ?></td>
                                    </tr>
                                </table>
                            </div>
                        <?php } ?>
                        <!-- end of fee description -->
                        <br/>
                        <br/>
                        <div class="row">
                            <b>ধন্যবাদ</b>
                        </div>
                        <div class="row">
                            <b>এআৰটিপিএছ</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <button type="button" id="btnPrint" class="btn btn-primary mb-2" >
            <i class="fas fa-print"></i> Print English Acknowledgement
        </button>
        <button type="button" id="btnPrintAssamese" class="btn btn-success mb-2">
            প্ৰিণ্ট অসমস্বীকৃতি
        </button>
        <?php if ($this->session->role) { ?>
            <a href="<?= base_url('iservices/admin/my-transactions') ?>" class="btn btn-primary mb-2 pull-right">
                <i class="fas fa-angle-double-left"></i> Back
            </a>
        <?php } else { ?>
            <a href="<?= base_url('iservices/transactions') ?>" class="btn btn-primary mb-2 pull-right">
                <i class="fas fa-angle-double-left"></i> Back
            </a>
        <?php }//End of if else ?>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $("#btnPrint").on('click', function () {
            var prtContent = document.getElementById("print");
            var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
            WinPrint.document.write(prtContent.innerHTML);
            WinPrint.document.writeln('<link rel="stylesheet" type="text/css" href="<?= base_url("assets/css/"); ?>acknowledgment.css">');
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        });
        
        $("#btnPrintAssamese").on('click', function() {
            var prtContent = document.getElementById("printAssamese");
            var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
            WinPrint.document.write(prtContent.innerHTML);
            WinPrint.document.writeln('<link rel="stylesheet" type="text/css" href="<?= base_url("assets/css/"); ?>acknowledgment.css">');
            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            WinPrint.close();
        });
    });
</script>
<?php

function calculateTotal($dbrow) {
    $total = 0;
    if (!empty($dbrow->form_data->pfc_payment_status) && $dbrow->form_data->pfc_payment_status === "Y") {

        if (!empty($dbrow->form_data->no_printing_page)) {
            $total += ($dbrow->form_data->no_printing_page) * ($dbrow->form_data->printing_charge_per_page);
        }

        if (!empty($dbrow->form_data->no_scanning_page)) {
            $total += ($dbrow->form_data->no_scanning_page) * ($dbrow->form_data->scanning_charge_per_page);
        }
        if (!empty($dbrow->form_data->rtps_convenience_fee)) {
            $total += $dbrow->form_data->rtps_convenience_fee;
        }

        if (!empty($dbrow->form_data->service_charge)) {
            $total += $dbrow->form_data->service_charge;
        }

        if (property_exists($dbrow, 'amountmut') && !empty($dbrow->form_data->amountmut)) {
            $total += floatval($dbrow->form_data->amountmut);
        }
        if (property_exists($dbrow, 'amountpart') && !empty($dbrow->form_data->amountpart)) {
            $total += floatval($dbrow->form_data->amountpart);
        }
        if (!empty($dbrow->form_data->amount)) {
            $total += floatval($dbrow->form_data->amount);
        }
    } else {
        if (!empty($dbrow->form_data->amount)) {
            $total += $dbrow->form_data->amount;
        }
    }
    return sprintf("%0.2f", $total);
}//End of calculateTotal()