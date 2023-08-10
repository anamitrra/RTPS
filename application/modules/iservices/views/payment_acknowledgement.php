<style>
/* body {visibility:hidden;} */
.print {visibility:visible;}

</style>
<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>acknowledgment.css">
<?php
$dept=substr($response->app_ref_no,0,3);
?>
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
                    <h3 class="h2"><u>Payment Acknowledgment</u></h3>
                  </div>
                  <div class="row akno">
                    <span><u>Acknowledgement no: </u><?=isset($response->app_ref_no)?$response->app_ref_no:''?></span>
                     <span style="float: right;"><u>Date:</u><?=isset($response->portal_payment_receiptdate)?date("d-m-Y", strtotime($response->portal_payment_receiptdate)):''?></span>
                   </div>
                  <div class="row">
                    <p>
                      Dear <?=isset($response->applicant_details)?$response->applicant_details[0]->applicant_name:''?>,
                      <br/>
                      <br/>
                      Your application for  <?=isset($service_name)?$service_name:''?> has been submitted successfully on <?=isset($response->portal_payment_receiptdate)?date("d-m-Y", strtotime($response->portal_payment_receiptdate)):''?> and
                      your Acknowledgement No. is <?=isset($response->app_ref_no)?$response->app_ref_no:''?>. Please use this Acknowledgement
                      number for tracking the application and for any future communication related to this application.
                      If the application has been accepted by the <?=isset($department_name)?$department_name:''?> department the service shall be provided within
                      <?=isset($timeline_days)?$timeline_days:''?> days. You may raise an appeal at http://rtps.assam.gov.in/ if the service is not delivered within the <?=isset($timeline_days)?$timeline_days:''?> days.
                      You can call us at 1800-345-3574, Mon-Sat - 8am to 8pm or write us at rtps-assam@assam.gov.in for any feedback or grievances.

                    </p>
                  </div>
                  <?php if (strval($response->portal_no) === "1"){ ?>
                    <div class="row">
                       Application Details:
                       <br/>
                       <br/>
                    </div>
                    <div class="row">
                          <table>
                            <?php if (isset($response->application_details->slno)): ?>
                              <tr>
                                <td><b>Sl No:</b></td>
                                <td><?=isset($response->application_details->slno)?$response->application_details->slno:''?></td>
                              </tr>
                            <?php endif; ?>

                            <tr>
                              <td><b>Application No:</b></td>
                              <td><?=isset($response->app_ref_no)?$response->app_ref_no:''?></td>
                            </tr>
                            <tr>
                              <td><b>Application Date:</b></td>
                              <td><?=isset($response->submission_date)?$response->submission_date:''?></td>
                            </tr>
                            <?php if ($response->applicant_details): ?>
                                <?php foreach ($response->applicant_details as $key => $applicant): ?>
                                  <tr>
                                    <td><b>Name of the Applicant:</b></td>
                                    <td><?=isset($applicant->applicant_name)?$applicant->applicant_name:''?></td>
                                  </tr>
                                  <tr>
                                    <td><b>Address of Applicant:</b></td>
                                    <td>
                                      <?=isset($applicant->address_line_1)?$applicant->address_line_1.", ":''?>
                                      <?=isset($applicant->address_line_2)?$applicant->address_line_2.", ":''?>
                                      <?=isset($applicant->district)?$applicant->district:''?>
                                      <?=isset($applicant->pin_code)?$applicant->pin_code:''?>
                                      <?=isset($applicant->state)?$applicant->state:''?>,
                                      <?=isset($applicant->country)?$applicant->country:''?>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td><b>Mobile No:</b></td>
                                    <td><?=isset($applicant->mobile_number)?$applicant->mobile_number:''?></td>
                                  </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if (isset($expected_dt)): ?>
                              <tr>
                                <td><b>Expected Date of Issue of Permission:  </b></td>
                                <td><?=isset($expected_dt)?$expected_dt:''?></td>
                              </tr>
                            <?php endif; ?>


                          </table>
                    </div>
                  <?php } ?>

                  <!-- fee description -->
                  <?php if (!empty($response->amount) || (!empty($response->pfc_payment_status) && $response->pfc_payment_status ==="Y") ) { ?>

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
                                <td><?=$response->amount .".00"?></td>
                              </tr>
                            <?php endif; ?>
                            <?php if (!empty($response->service_charge)): ?>
                            <tr>
                              <td><b>Service Charge:</b></td>
                              <td><?=$response->service_charge.".00"?></td>
                            </tr>
                              <?php endif; ?>
                            <?php if (!empty($response->no_printing_page)): ?>
                            <tr>
                              <td><b>Printing Fee:</b></td>
                              <td><?=($response->no_printing_page) *($response->printing_charge_per_page).".00"?></td>
                            </tr>
                              <?php endif; ?>
                              <?php if (!empty($response->no_scanning_page)): ?>
                              <tr>
                                <td><b>Scanning Fee:</b></td>
                                <td><?=($response->no_scanning_page)*($response->scanning_charge_per_page).".00"?></td>
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

                    <?php }?>
                   
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
    <?= !empty($back_to_dasboard) ? $back_to_dasboard : ""?>
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
  if (!empty($response->pfc_payment_status) && $response->pfc_payment_status === "Y"){

    if(!empty($response->no_printing_page)){
      $total  +=  ($response->no_printing_page) *($response->printing_charge_per_page);
    }

    if(!empty($response->no_scanning_page)){
      $total +=  ($response->no_scanning_page) *($response->scanning_charge_per_page);
    }

    if(!empty($response->service_charge)){
        $total += $response->service_charge;
    }
    if(!empty($response->amount)){
          $total += $response->amount;
    }


  }else {
    if(!empty($response->amount)){
          $total += $response->amount;
    }
  }
  return $total.".00";
}
?>
