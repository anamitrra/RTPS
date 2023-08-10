<style>
/* body {visibility:hidden;} */
.print {
    visibility: visible;
}
</style>
<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>acknowledgment.css">
<?php


?>
<div class="content-wrapper">
    <div class="container">
        <div class="row " id="print">
            <div class="col-sm-12 mx-auto">
                <div class="card my-4 " style="border: 1px solid;padding:20px">
                    <div class="card-body">
                        <div class="row">
                            <img class="img"
                                src="https://upload.wikimedia.org/wikipedia/en/e/e1/Assam_higher_secondary_education_council_logo.png"
                                width="100" height="100" />
                            <br />

                        </div>
                        <div class="row">
                            <h3 class="h2">Assam Higher Secondary Education Council</h3>

                        </div>
                        <div class="row">
                            <h3 class="h2" style="color: red;"><b>Migration Certificate</b></h3>

                        </div>

                        <br />
                        <br />
                        <br />
                        <br />
                        <div class="row akno">
                            <span><u>Sl. No.:
                                MIG-001</u></span>
                            <span style="float: right;"><u>Date: 7/6/2023</u></span>
                        </div>
                        <div class="row">
                            <p style="text-align:justify; line-height: 24px; font-size:18px;">

                                This Certificate is issued in respect of
                                <strong><?=ucwords($dbrow->form_data->applicant_name)?></strong> ,Son/Daughter of
                                <strong><?=ucwords($dbrow->form_data->father_name)?></strong> and
                                <strong><?=ucwords($dbrow->form_data->mother_name)?></strong>
                                <!-- ahsec_reg_no board_seaking_adm-->
                                under registration number <strong><?=$dbrow->form_data->ahsec_reg_no ?? ''?></strong>
                                that this council has no objection to his continuing studies at
                                <strong><?=$dbrow->form_data->board_seaking_adm ?? ''?></strong>.

                            </p>


                        </div>


                        <br />
                        <br />
                        <br />
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                <p style="font-size: 30px; color:red;">Internet Copy</p>
                            </div>
                            <div class="col-md-3">

                            </div>
                            <div class="col-md-3 text-center" style="font-size:16px;">
                                <b>Sd/-</b>
                                <br>
                                <strong>Secretary</strong>
                                <br>
                                Assam Higher Secondary<br> Education Council

                                Bamunimaidam, Guwahati-21
                            </div>

                        </div>
                        <!-- <div class="row">
                            <b>Sd/-</b>
                            <br>
                        </div>
                        <div class="row">
                            <b>Secretary</b>
                            <br />
                        </div>
                        <div class="row">
                            <b>Assam Higher Secondary<br /> Education Council</b>
                            
                        </div>
                        <div class="row">
                            <b>Bamunimaidam,<br>Guwahati -21</b>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        <button type="button" id="btnPrint" class="btn btn-primary mb-2">
            <i class="fas fa-print"></i> Print</button>
    </div>
</div>
<script>
$(document).ready(function() {

    $("#btnPrint").on('click', function() {
        printDiv('print');

    });


    $("#btnPrintAssamese").on('click', function() {

        printDiv('printAssamese');

    });


    function printDiv(divname) {
        var divContents = document.getElementById(divname).innerHTML;
        var a = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        a.document.write('<html>');
        a.document.write('<body >  <br>');
        a.document.write(divContents);
        a.document.writeln(
            '<link rel="stylesheet" type="text/css" href="<?= base_url("assets/css/"); ?>acknowledgment.css">'
        );
        a.document.write('</body></html>');
        a.focus();
        a.print();
        a.document.close();

    }

});
</script>
<?php
function calculateTotal($response){
  $total=0;
  if (!empty($response->pfc_payment_status) && $response->pfc_payment_status === "Y"){

    if(!empty($response->no_printing_page)){
      $total  +=  ($response->no_printing_page) *($response->printing_charge_per_page);
    }

    if(!empty($response->no_scanning_page)){
      $total +=  ($response->no_scanning_page) *($response->scanning_charge_per_page);
    }
    if(!empty($response->rtps_convenience_fee)){
        $total += $response->rtps_convenience_fee;
    }
    if(!empty($response->service_charge)){
        $total += $response->service_charge;
    } 

    if(property_exists($response,'amountmut') && !empty($response->amountmut)){
        $total += floatval($response->amountmut);
    } 
    if(property_exists($response,'amountpart') && !empty($response->amountpart)){
        $total += floatval($response->amountpart);
    }
    if(!empty($response->amount)){
          $total += floatval($response->amount);
    }


  }else {
    if(!empty($response->amount)){
          $total += $response->amount;
    }
      
  }
  return $total.".00";
}
?>