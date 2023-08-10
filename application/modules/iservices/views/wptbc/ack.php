<style>
    /* body {visibility:hidden;} */
    .print {visibility:visible;}

</style>
<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>acknowledgment.css">
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
                        <!-- <div class="row akno">
                          <span><u>Acknowledgement no: </u><?= isset($response->app_ref_no) ? $response->app_ref_no : '' ?></span>
                           <span style="float: right;"><u>Date:</u><?= isset($response->submission_date) ? $response->submission_date : '' ?></span>
                         </div> -->
                        <div class="row">
                            <table>
                                <tr>
                                    <td>Application Number</td>
                                    <td><b><?= $response->rtps_trans_id ?></b></td>
                                    <td>Service Name</td>
                                    <td><b><?= $response->service_name ?></b></td>
                                </tr>

                                <tr>
                                    <td>Applicant's Name</td>
                                    <td><b><?= $response->applicant_name ?></b></td>
                                    <td>Status</td>
                                    <td><b><?= $response->status ?></b></td>
                                </tr>
                                <tr>
                                    <td>Mobile Number </td>
                                    <td><b><?= $response->mobile_number ?></b></td>
                                    <td>Email Id</td>
                                    <td><b><?= $response->email ?></b></td>
                                </tr>
                                <tr>
                                    <td>Receipt Date </td>
                                    <td><b><?= date('d-m-Y', intval(strval($response->created_at)) / 1000) ?></b></td>
                                </tr>
                                <tr>
                                    <td>Proposed Delivery Date </td>
                                    <td><b>Within 30 working days from <?= date('d-m-Y', intval(strval($response->created_at)) / 1000) ?></b></td>
                                </tr>
                            </table>
                        </div>
                        <!-- fee description -->
                        <?php if (false) { ?>

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
                                            <td><?= $response->amount . ".00" ?></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php if (!empty($response->service_charge)): ?>
                                        <tr>
                                            <td><b>Service Charge:</b></td>
                                            <td><?= $response->service_charge . ".00" ?></td>
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
            Print
        </button>
        <?= !empty($back_to_dasboard) ? $back_to_dasboard : "" ?>
        <!-- <button type="button" id="" class="btn btn-danger mb-2" >
          Exit
        </button> -->
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
    });
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
        if (!empty($response->amount)) {
            $total += $response->amount;
        }
    } else {
        if (!empty($response->amount)) {
            $total += $response->amount;
        }
    }
    return $total . ".00";
}
?>
