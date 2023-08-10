<style type="text/css">
    /* body {visibility:hidden;} */
    .print {
        visibility:visible;
    }
</style>
<?php 
$appl_ref_no = $dbrow->service_data->appl_ref_no??'';
$submission_date = $dbrow->service_data->submission_date??'';
$applicant_name = $dbrow->form_data->applicant_name??'';
$doa = $dbrow->form_data->doa??'';
$office = $dbrow->form_data->office??'';
$submissionDate = strlen($submission_date)?date('d-m-Y', strtotime($submission_date)):date('d-m-Y');
$appointmentDate = strlen($doa)?date('d-m-Y', strtotime($doa)):'';
$appointment_type = $dbrow->form_data->appointment_type??'';
$at_id = $appointment_type->at_id??'';
if($at_id == 1) {
    $appointmentType = "Deed";
} elseif($at_id == 2) {
    $appointmentType = "Marriage";    
} else {
    $appointmentType = "NA";    
}//End of if else ?>
<link rel="stylesheet" href="<?= base_url("assets/css/acknowledgment.css"); ?>">
<div class="content-wrapper">
    <div class="container">
        <div class="row " id="print">
            <div class="col-sm-12 mx-auto">
                <div class="card my-4 " style="border: 1px solid;padding:20px">
                    <div class="card-body">
                        <div class="row" >
                            <img class="img" src="<?= base_url("assets/frontend/images/assam_logo.png") ?>" width="100" height="100"/>
                        </div>

                        <div class="row" >
                            <h3 class="h2"><u>Appointment confirmation</u></h3>
                        </div>
                        <div class="row">
                            <p style="text-align:justify; line-height: 30px; font-size: 16px">
                                Dear <strong><?=$applicant_name?></strong>,<br/>
                                Your appointment date has been approved on <strong> <?=$appointmentDate?></strong>. 
                                Please visit <strong><?=$office?></strong> on the specific date.<br>
                                Your application ref. no. is <strong><?=$appl_ref_no?></strong> for <strong><?=$appointmentType?> registration</strong>.
                            </p>
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

function calculateTotal($dbrow) {
    $total = 0;
    if (!empty($dbrow->pfc_payment_status) && $dbrow->pfc_payment_status === "Y") {

        if (!empty($dbrow->no_printing_page)) {
            $total += ($dbrow->no_printing_page) * ($dbrow->printing_charge_per_page);
        }

        if (!empty($dbrow->no_scanning_page)) {
            $total += ($dbrow->no_scanning_page) * ($dbrow->scanning_charge_per_page);
        }

        if (!empty($dbrow->service_charge)) {
            $total += $dbrow->service_charge;
        }

        if (property_exists($dbrow, 'amountmut') && !empty($dbrow->amountmut)) {
            $total += floatval($dbrow->amountmut);
        }
        if (property_exists($dbrow, 'amountpart') && !empty($dbrow->amountpart)) {
            $total += floatval($dbrow->amountpart);
        }
        if (!empty($dbrow->amount)) {
            $total += floatval($dbrow->amount);
        }
    } else {
        if (!empty($dbrow->amount)) {
            $total += $dbrow->amount;
        }
    }
    return $total . ".00";
}
?>
