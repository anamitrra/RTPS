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
                    <img class="img" src="<?=base_url("assets/frontend/images/logo_artps.png")?>" width="100" height="100"/>
                  </div>

                  <div class="row" >
                    <h3 class="h2"><u>Application Acknowledgment</u></h3>
                  </div>
                  <div class="row akno">
                    <span><u>Acknowledgement no: </u><?=isset($response['vahan_app_no'])?$response['vahan_app_no']:''?></span>
                     <span style="float: right;"><u>Date:</u><?=isset($response['submission_date'])?$response['submission_date']:''?></span>
                   </div>
                  <div class="row">
                    <p>
                      Dear <?=isset($response['applicant_name'])?$response['applicant_name']:''?>,
                      <br/>
                      <br/>
                      Your application for  <?=isset($response['PurposeDescription'])?$response['PurposeDescription']:''?> has been submitted successfully on <?=isset($response['submission_date'])?$response['submission_date']:''?> and
                      your Acknowledgement No. is <?=isset($response['vahan_app_no'])?$response['vahan_app_no']:''?>. Please use this Acknowledgement
                      number for tracking the application and for any future communication related to this application.
                      If the application has been accepted by the <?=isset($department_name)?$department_name:''?> department the service shall be provided within
                      <?=isset($timeline_days)?$timeline_days:''?> days. You may raise an appeal at http://rtps.assam.gov.in/ if the service is not delivered within the <?=isset($timeline_days)?$timeline_days:''?> days.
                      You can call us at 6000901977, Mon-Sat - 8am to 8pm or write us at rtps-assam@assam.gov.in for any feedback or grievances.

                    </p>
                  </div>

                  <!-- fee description -->

                    <div class="row">
                       The following fees has been collected for this application:

                    </div>
                    <br/>
                    <br/>
                    <div class="row">
                          <table>
                            <?php if (isset($response['amount'])): ?>
                              <tr>
                                <td><b>Application Fee:</b></td>
                                <td><?=$response['amount'] .".00"?></td>
                              </tr>
                            <?php endif; ?>
                            <?php if (isset($response['service_charge'])): ?>
                            <tr>
                              <td><b>Service Charge:</b></td>
                              <td><?=$response['service_charge'].".00"?></td>
                            </tr>
                              <?php endif; ?>
                            <?php if (isset($response['no_printing_page'])): ?>
                            <tr>
                              <td><b>Printing Fee:</b></td>
                              <td><?=($response['no_printing_page']) *($response['printing_charge_per_page']).".00"?></td>
                            </tr>
                              <?php endif; ?>
                              <?php if (isset($response['no_scanning_page'])): ?>
                              <tr>
                                <td><b>Printing Fee:</b></td>
                                <td><?=($response['no_scanning_page'])*($response['scanning_charge_per_page']).".00"?></td>
                              </tr>
                              <?php endif; ?>
                              <tr>
                                <td>================</td>
                                <td>========</td>
                              </tr>

                              <tr>
                                <td><b>Total:</b></td>
                                <td><?=calculateTotal($response)?></td>
                              </tr>
                          </table>
                    </div>

                  <!-- end of fee description -->

                  <br/>
                  <div class="row">
                    <br/><br/>
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
    <!-- <button type="button" id="" class="btn btn-danger mb-2" >
      Exit
    </button> -->
</div>
</div>
<script>
$(function(){
  $("#btnPrint").on('click',function(){
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
function calculateTotal($response){
  $total=0;
  if (isset($response['pfc_payment_status']) && $response['pfc_payment_status'] === "Y"){

    if(isset($response['no_printing_page'])){
      $total  +=  ($response['no_printing_page']) *($response['printing_charge_per_page']);
    }

    if(isset($response['no_scanning_page'])){
      $total +=  ($response['no_scanning_page']) *($response['scanning_charge_per_page']);
    }

    if(isset($response['service_charge'])){
      $total += $response['service_charge'];
    }
    if(isset($response['amount'])){
      $total += $response['amount'];
    }

  }else {
    if(isset($response['amount'])){
      $total += $response['amount'];
    }
  }
  return $total.".00";
}
?>
