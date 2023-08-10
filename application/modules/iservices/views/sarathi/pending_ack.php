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
                    <span><u>Acknowledgement no: </u><?=isset($app_ref_no)?$app_ref_no:''?></span>
                     <span style="float: right;"><u>Date:</u><?=isset($submission_date)?$submission_date:''?></span>
                   </div>
                  <div class="row">
                    <p>
                      Dear <?=isset($applicant_details)?$applicant_details[0]['applicant_name']:''?>,
                      <br/>
                      <br/>
                      Your application for  <?=isset($transaction_name)?$transaction_name:''?> has been submitted on <?=isset($submission_date)?$submission_date:''?> and
                      your Acknowledgement No. is <?=isset($app_ref_no)?$app_ref_no:''?>. 
                      <?php if (!empty($this->session->userdata('role')) && ($this->session->userdata('role')->slug === "PFC" || $this->session->userdata('role')->slug === "SA") ): ?>
                      <b>Your application status could not be verified. Please go to <a href="<?=base_url('iservices/admin/my-transactions')?>">My Transactions</a> and click on Complete Pending Application button to complete your application process.</b>
                    <?php else: ?>
                      <b>Your application status could not be verified. Please go to <a href="<?=base_url('iservices/transactions')?>">My Transactions</a> and click on Complete Pending Application button to complete your application process.</b>
                    <?php endif; ?>
                      You can call us at 1800-345-3574, Mon-Sat - 8am to 8pm or write us at rtps-assam@assam.gov.in for any feedback or grievances.

                    </p>
                  </div>
              
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
