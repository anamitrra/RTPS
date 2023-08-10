<style>
    /* body {visibility:hidden;} */
    .print {
        visibility:visible;
    }
</style>
<link rel="stylesheet" href="<?= base_url("assets/css/acknowledgment.css"); ?>">
<?php
if (isset($response->applicant_name) && strlen($response->applicant_name)) {
    $applicant_name = $response->applicant_name;
} elseif (isset($response->applicant_prefix) && strlen($response->applicant_prefix)) {
    $applicant_name = $response->applicant_prefix;
} else {
    $applicant_name = '';
}//End of if else

if (isset($response->applicant_first_name) && strlen($response->applicant_first_name)) {
    $applicant_name = $applicant_name . '&nbsp;' . $response->applicant_first_name;
}
if (isset($response->applicant_middle_name) && strlen($response->applicant_middle_name)) {
    $applicant_name = $applicant_name . '&nbsp;' . $response->applicant_middle_name;
}
if (isset($response->applicant_last_name) && strlen($response->applicant_last_name)) {
    $applicant_name = $applicant_name . '&nbsp;' . $response->applicant_last_name;
}
?>
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
                            <span><u>Acknowledgement no. : </u><?= isset($response->rtps_trans_id) ? $response->rtps_trans_id : '' ?></span>
                            <span style="float: right;"><u>Date : </u><?= isset($response->submission_date) ? format_mongo_date($response->submission_date) : '' ?></span>
                        </div>
                        <div class="row">
                            <p style="text-align:justify; line-height: 24px">
                                Dear <strong><?= $applicant_name ?></strong>,
                                <br/>
                                <br/>
                                Your application for <strong> <?= isset($service_row->service_name) ? $service_row->service_name : '' ?></strong> has been processed successfully on <strong><?= isset($response->generated_at) ? format_mongo_date($response->generated_at) : '' ?></strong> and
                                your Acknowledgement No. is <strong><?= isset($response->rtps_trans_id) ? $response->rtps_trans_id : '' ?></strong>. Please use this Acknowledgement
                                number for tracking the application and for any future communication related to this application.
                            </p>
                        </div>

                        <!-- fee description -->
                        <?php if (!empty($response->amount) || (!empty($response->pfc_payment_status) && $response->pfc_payment_status === "Y")) { ?>

                            <div class="row">
                                The following fees has been collected for this application:
                            </div>
                            <br/>
                            <br/>
                            <div class="row">
                                <table>
                                    <?php if (!empty($response->amount)): ?>
                                        <tr>
                                            <td><b>Application Fee:</b></td>
                                            <td><?= $response->amount ?></td>
                                        </tr>
                                    <?php endif; ?>

                                    <?php if (!empty($response->service_charge)): ?>
                                        <tr>
                                            <td><b>Service Charge:</b></td>
                                            <td><?= $response->service_charge ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if (!empty($response->no_printing_page)): ?>
                                        <tr>
                                            <td><b>Printing Fee:</b></td>
                                            <td><?= ($response->no_printing_page) * ($response->printing_charge_per_page) . ".00" ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if (!empty($response->no_scanning_page)): ?>
                                        <tr>
                                            <td><b>Scanning Fee:</b></td>
                                            <td><?= ($response->no_scanning_page) * ($response->scanning_charge_per_page) . ".00" ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td>================</td>
                                        <td>========</td>
                                    </tr>

                                    <tr>
                                        <td><b>Total:</b></td>
                                        <td><?= calculateTotal($response) ?></td>
                                    </tr>
                                </table>
                            </div>

                        <?php } ?>

                        <!-- end of fee description -->
                        <br>
                        <p style="text-align:center; line-height: 24px">
                            You can take appointment for marriage registration on any suitable date after 30 days from this date of approval
                        </p>
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
        </div>
        <button type="button" id="btnPrint" class="btn btn-primary mb-2" >
            <i class="fas fa-print"></i> Print
        </button>
        <?php if ($this->session->role) { ?>
            <a href="<?= base_url('iservices/admin/my-transactions') ?>" class="btn btn-primary mb-2 pull-right">
                <i class="fas fa-angle-double-left"></i> Back
            </a>
        <?php } else { ?>
            <a href="<?= base_url('iservices/transactions') ?>" class="btn btn-primary mb-2 pull-right">
                <i class="fas fa-angle-double-left"></i> Back
            </a>
        <?php }//End of if else  ?>
    </div>
</div>
<script>
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
        })
    })
</script>
<?php

function calculateTotal($response) {
    $total = 0;
    if (!empty($response->pfc_payment_status) && $response->pfc_payment_status === "Y") {

        if (!empty($response->no_printing_page)) {
            $total += ($response->no_printing_page) * ($response->printing_charge_per_page);
        }

        if (!empty($response->no_scanning_page)) {
            $total += ($response->no_scanning_page) * ($response->scanning_charge_per_page);
        }

        if (!empty($response->service_charge)) {
            $total += $response->service_charge;
        }

        if (property_exists($response, 'amountmut') && !empty($response->amountmut)) {
            $total += floatval($response->amountmut);
        }
        if (property_exists($response, 'amountpart') && !empty($response->amountpart)) {
            $total += floatval($response->amountpart);
        }
        if (!empty($response->amount)) {
            $total += floatval($response->amount);
        }
    } else {
        if (!empty($response->amount)) {
            $total += $response->amount;
        }
    }
    return $total . ".00";
}
?>
