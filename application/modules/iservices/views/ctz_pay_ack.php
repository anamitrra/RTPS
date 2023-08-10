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
                    <h3 class="h2"><u>Payment Acknowledgment</u></h3>
                  </div>

                  <div class="row" >

                  <b>Payment Successfull.</b>
                  

                  </div>
                  <div class="row" >
                        <b>GRN : <?=$GRN?></b>
                  </div>
                   <div class="row" >
                   <b>Transaction Ref : <?=strtoupper($DEPARTMENT_ID) ?></b>
                  </div>
                   <div class="row" >
                   <b>Click here to go to <a href="<?=base_url('iservices/transactions')?>">My Applications</a> </b>
                  </div>


                </div>
            </div>
        </div>
    </div>

</div>
</div>
