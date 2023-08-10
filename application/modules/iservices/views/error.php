<style>
/* body {visibility:hidden;} */
.print {visibility:visible;}

</style>
<link rel="stylesheet" href="<?= base_url("assets/css/"); ?>acknowledgment.css">
<div class="content-wrapper">
<div class="container">
    <div class="row " id="print">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4" style="border: 1px solid;padding:20px">
                <div class="card-body">
                  <div class="row" >
                    <img class="img" src="<?=base_url("assets/frontend/images/logo_artps.png")?>" width="100" height="100"/>
                  </div>

                  <div class="row" >
                    <h3 class="h2"><u>Application Acknowledgment</u></h3>
                  </div>

                  <div class="row" style="padding:20px">

                  <?php if (!empty($this->session->userdata('role')) && ($this->session->userdata('role')->slug === "PFC" || $this->session->userdata('role')->slug === "SA") ): ?>
                    <b>Your application status could not be verified. Please go to <a href="<?=base_url('iservices/admin/my-transactions')?>">My Transactions</a> and click on Re-try button to complete your application submission.</b>
                  <?php else: ?>
                    <b>Your application status could not be verified. Please go to <a href="<?=base_url('iservices/transactions')?>">My Transactions</a> and click on Re-try button to complete your application submission.</b>
                  <?php endif; ?>

                    <p>
                      You can call us at 6000901977, Mon-Sat - 8am to 8pm or write us at rtps-assam@assam.gov.in for any feedback or grievances.

                    </p>

                  </div>



                </div>
            </div>
        </div>
    </div>

</div>
</div>
