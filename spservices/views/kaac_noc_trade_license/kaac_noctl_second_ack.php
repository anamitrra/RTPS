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
                    <h3 class="h2"><u>Payment Acknowledgement</u></h3>
                  </div>
                  <div class="row akno">
                    <span><u>Acknowledgement no</u>: <?= $dbrow->service_data->appl_ref_no ?></span>
                   </div>
                  <div class="row">
                      <p style="text-align:justify; line-height: 24px">
                        Dear <strong><?= $dbrow->form_data->applicant_name ?></strong>,
                      <br/>
                      <br/>
                      
                      Your payment for <strong><?= $dbrow->service_data->service_name ?></strong> service has been made successfully and your Acknowledgement No. <strong><?= $dbrow->service_data->appl_ref_no ?></strong>. Please use this Acknowledgement number for tracking the application and for any future communication related to this application.

                    </p>
                   
                    
                  </div>

                  <!-- fee description -->
                  <?php if((isset($dbrow->form_data->frs_request->bpPaymentTypeStatus) && !empty($dbrow->form_data->frs_request->bpPaymentTypeStatus) == true) && (isset($dbrow->form_data->frs_request->ppPaymentTypeStatus) && !empty($dbrow->form_data->frs_request->ppPaymentTypeStatus) == true)) { ?>

                    <div class="row">
                       The following fees has been collected for this application:

						<?php
						if (!empty($dbrow->form_data->frs_request->bpPaymentStatus)){
							if ($dbrow->form_data->frs_request->bpPaymentStatus == "UNPAID"){ ?>
							<br><br>Building Permit Fee Payment Ref No: <?php print $dbrow->form_data->query_bp_payment_response->GRN; ?>, Payment Date: <?php print $dbrow->form_data->query_bp_payment_response->ENTRY_DATE; ?>
						<?php }} ?>

						<?php
						if (!empty($dbrow->form_data->frs_request->ppPaymentStatus)){
							if ($dbrow->form_data->frs_request->ppPaymentStatus == "UNPAID"){ ?>
							<br>Planning Permit Fee Payment Ref No: <?php print $dbrow->form_data->query_pp_payment_response->GRN; ?>, Payment Date: <?php print $dbrow->form_data->query_pp_payment_response->ENTRY_DATE; ?>
						<?php }} ?>

                    </div>
                    <br/>
                    <br/>
                    <div class="row">
                          <table>
                            <?php $total_amount = 0; ?>
							
							<?php
							if (!empty($dbrow->form_data->frs_request->bpPaymentStatus)){
								if ($dbrow->form_data->frs_request->bpPaymentStatus == "UNPAID"){ 
									$total_amount = $total_amount + $dbrow->form_data->frs_request->bpAmount;?>
									<tr>
									  <td><b>Building Permit Fee:</b></td>
									  <td><?php print $dbrow->form_data->frs_request->bpAmount; ?></td>
									</tr>
							<?php }} ?>

                            <?php if (!empty($dbrow->form_data->frs_request->ppPaymentStatus)){ 
								if ($dbrow->form_data->frs_request->ppPaymentStatus == "UNPAID"){ 
								$total_amount = $total_amount + $dbrow->form_data->frs_request->ppAmount;?>
                              <tr>
                                <td><b>Planning Permit Fee:</b></td>
                                <td><?= $dbrow->form_data->frs_request->ppAmount ?></td>
                              </tr>
                            <?php }} ?>

                            <?php if (!empty($dbrow->form_data->frs_request->lcPaymentStatus)){ 
								if ($dbrow->form_data->frs_request->lcPaymentStatus == "UNPAID"){ 
								$total_amount = $total_amount + $dbrow->form_data->frs_request->labourCess; ?>
                              <tr>
                                <td><b>Labour Cess Fee:</b></td>
                                <td><?= $dbrow->form_data->frs_request->labourCess ?></td>
                              </tr>
                            <?php }} ?>

                              <tr>
                                <td>================</td>
                                <td>========</td>
                              </tr>

                              <tr>
                                <td><b>Total:</b></td>
                                <td><?= $total_amount ?></td>
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
      <i class="fas fa-print"></i> Print Acknowledgement
    </button>
    <?php if($this->session->role) { ?>
        <a href="<?=base_url('iservices/admin/my-transactions')?>" class="btn btn-primary mb-2 pull-right">
            <i class="fas fa-angle-double-left"></i> Back
        </a>
    <?php } else { ?>
        <a href="<?=base_url('iservices/transactions')?>" class="btn btn-primary mb-2 pull-right">
            <i class="fas fa-angle-double-left"></i> Back
        </a>
    <?php }//End of if else ?>
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


  $("#btnPrintAssamese").on('click', function() {
      var prtContent = document.getElementById("printAssamese");
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
