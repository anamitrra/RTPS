<style>
    /* body {visibility:hidden;} */
    .print {
        visibility: visible;
    }
</style>
<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>acknowledgment.css">
<div class="content-wrapper">
    <div class="container">
        <div class="row " id="print">
            <div class="col-sm-12 mx-auto">
                <div class="card my-4 " style="border: 1px solid;padding:20px">
                    <div class="card-body">
                        <div class="row">
                            <img class="img" src="<?= base_url("assets/frontend/images/logo_artps.png") ?>" width="100" height="100" />
                        </div>
                        <div class="row">
                            <h3 class="h2"><u>Application Acknowledgement</u></h3>
                        </div>
                        <div class="row akno">
                            <span><u>Acknowledgement no: </u><?= isset($response->service_data->rtps_trans_id) ? $response->service_data->rtps_trans_id : '' ?></span>

                            <span style="float: right;"><u>Date:</u><?= isset($response->service_data->submission_date) ? format_mongo_date($response->service_data->submission_date) : '' ?></span>
                        </div>
                        <div class="row">
                            <p style="text-align:justify; line-height: 24px">
                                Dear <strong><?= (isset($response->form_data->applicant_name)) ? $response->form_data->applicant_name : '' ?></strong>,
                                <br />
                                <br />
                                Your application for <strong> <?= isset($response->service_data->service_name) ? $response->service_data->service_name : '' ?></strong> has been submitted successfully on <strong><?= isset($response->form_data->submission_date) ? format_mongo_date($response->form_data->submission_date) : '' ?></strong> and
                                your Acknowledgement No. is <strong><?= isset($response->form_data->rtps_trans_id) ? $response->form_data->rtps_trans_id : '' ?></strong>. Please use this Acknowledgement
                                number for tracking the application and for any future communication related to this application.
                                If the application has been accepted by the <strong><?= isset($response->service_data->department_name) ? $response->service_data->department_name : '' ?></strong> the service shall be provided within
                                stipulated timeline . You may raise an appeal at http://rtps.assam.gov.in/ if the service is not delivered within the <strong><?= isset($response->service_data->service_timeline) ? $response->service_data->service_timeline : '' ?></strong>.
                                You can call us at 1800-345-3574, Mon-Sat - 8am to 8pm or write us at rtps-assam@assam.gov.in for any feedback or grievances.
                            </p>
                        </div>
                        <br />
                        <br />
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
        <button type="button" id="btnPrint" class="btn btn-primary mb-2">
            <i class="fas fa-print"></i> Print
        </button>
        <?php if ($this->session->role) { ?>
            <a href="<?= base_url('spservices/tradelicence/certificate/index/' . $response->{'_id'}->{'$id'}) ?>" class="btn btn-primary mb-2 pull-right">
                <i class="fas fa-angle-double-right"></i> Click here to download certificate
            </a>
        <?php } else { ?>
            <a href="<?= base_url('spservices/tradelicence/certificate/index/' . $response->{'_id'}->{'$id'}) ?>" class="btn btn-primary mb-2 pull-right">
                <i class="fas fa-angle-double-right"></i> Click here to download certificate
            </a>
            </a>
        <?php } //End of if else 
        ?>
    </div>
</div>
<script>
    $(function() {
        $("#btnPrint").on('click', function() {
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
function calculateTotal($response)
{
    $total = 30;
    if (!empty($response->form_data->payment_status) && $response->form_data->payment_status === "Y") {
        if (!empty($response->form_data->no_printing_page)) {
            $total  +=  ($response->form_data->no_printing_page) * 10;
        }
        if (!empty($response->form_data->no_scanning_page)) {
            $total +=  ($response->form_data->no_scanning_page) * 5;
        }
    }
    return $total . ".00";
}
?>