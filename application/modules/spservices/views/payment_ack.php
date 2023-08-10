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
                    <span><u>Acknowledgement No: </u><?=isset($response->rtps_trans_id)?$response->rtps_trans_id:''?></span>
                     <span style="float: right;"><u>Payment Date:</u><?=isset($response->payment_date)?$response->payment_date:''?></span>
                   </div>
                  <div class="row">
                      <p style="text-align:justify; line-height: 24px">
                        Dear <strong><?=$response->applicant_name?></strong>,
                      <br/>
                      <br/>
                      Your payment has been made successfully on <strong><?=isset($response->payment_date)?$response->payment_date:''?></strong> and
                      your Acknowledgement No. is <strong><?=isset($response->rtps_trans_id)?$response->rtps_trans_id:''?></strong>. Please use this Acknowledgement
                      number for tracking the application and for any future communication related to this application.

                    </p>
                   
                    
                  </div>

                  <div class="row">
                       <p>Payment Ref. No: <strong><?=isset($response->payment_ref_no)?$response->payment_ref_no:''?></strong></p>

                    </div>

                  <!-- fee description -->
                  <?php if (!empty($response->total_amount)) { ?>

                    <div class="row">
                       The following fees has been collected for this application:

                    </div>
                    <br/>
                    <br/>
                    <div class="row">
                          <table>
                            <?php if (!empty($response->fee1)): ?>
                              <tr>
                                <td><?=$response->fee1 ?></td>
                              </tr>
                            <?php endif; ?>
                            <?php if (!empty($response->fee2)): ?>
                              <tr>
                                <td><?=$response->fee2 ?></td>
                              </tr>
                            <?php endif; ?>
                            <?php if (!empty($response->fee3)): ?>
                              <tr>
                                <td><?=$response->fee3 ?></td>
                              </tr>
                            <?php endif; ?>
                            <?php if (!empty($response->fee4)): ?>
                              <tr>
                                <td><?=$response->fee4 ?></td>
                              </tr>
                            <?php endif; ?>
                            <?php if (!empty($response->fee5)): ?>
                              <tr>
                                <td><?=$response->fee5 ?></td>
                              </tr>
                            <?php endif; ?>
                            <?php if (!empty($response->fee6)): ?>
                              <tr>
                                <td><?=$response->fee6 ?></td>
                              </tr>
                            <?php endif; ?>
                            <?php if (!empty($response->fee7)): ?>
                              <tr>
                                <td><?=$response->fee7 ?></td>
                              </tr>
                            <?php endif; ?>
                            <?php if (!empty($response->fee8)): ?>
                              <tr>
                                <td><?=$response->fee8 ?></td>
                              </tr>
                            <?php endif; ?>
                            <?php if (!empty($response->fee9)): ?>
                              <tr>
                                <td><?=$response->fee9 ?></td>
                              </tr>
                            <?php endif; ?>
                              <tr>
                                <td>================</td>
                                <td>========</td>
                              </tr>

                              <tr>
                                <td><b>Total:</b></td>
                                <td><?=$response->total_amount?></td>
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
      <i class="fas fa-print"></i> Print
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
})
</script>

