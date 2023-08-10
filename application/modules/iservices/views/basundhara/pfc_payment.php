<div class="content-wrapper">

<div class="container">
  <?php //var_dump($saheb);die;?>
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                  <!-- <h2>Response</h2><br/><br/> -->
                    <div class="row">


                        <?php if ($ApplicationStatus === "S"): ?>
                          <div class="col-sm-12">
                            Application has been submitted successfully

                          </div>
                        <?php endif; ?>

                    </div>
                   
                    <?php if ($ApplicationStatus === "S"): ?>
                        <div class="row">
                          <div class="col-sm-12">
                            <form id="paymentForm" action="<?=base_url("iservices/basundhara/payment/pfcpayment")?>" method="post">
                            <input type="hidden" name="rtps_trans_id" value="<?=$rtps_trans_id?>"/>
                            <div class="row">
                                <div class="form-group  col-sm-6">
                                    <label for="applicant_name">Service Charge </label>
                                    <input type="text" class="form-control" name="service_charge" id="service_charge" placeholder=""  value="<?=$service_charge?>" readonly/>
                                    <!-- <span class="error-applicant_name">Please Enter User Contact No.</span> -->
                                </div>
                                </div>      
                                <div class="row">
                                <div class="form-group  col-sm-6">
                                    <label for="applicant_name">No of Printing Page. </label>
                                    <input type="text" class="form-control" name="no_printing_page" id="no_printing_page" placeholder=""  />
                                    <!-- <span class="error-applicant_name">Please Enter User Contact No.</span> -->
                                </div>
                                <div class="form-group  col-sm-6">
                                    <label for="user_mobile">No of Scanning Page. </label>
                                    <input type="text" class="form-control" name="no_scanning_page" id="no_scanning_page" placeholder=""  />
                                </div>
                                </div>
                              <button type="button" id="saveAndPFCAmount" class="btn btn-primary"  >
                                Make a payment
                              </button>
                            </form>

                          </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
<script>

    const updatePfcPaymentAmountURL = '<?=base_url('iservices/admin/update-pfc-payment-amount')?>';
    const env='<?= ENV ?>';

    $(document).ready(function () {

        var saveAndPFCAmount = $('#saveAndPFCAmount');


        saveAndPFCAmount.click(function () {

            var printing_charge="<?=$printing_charge?>";
            var scanning_charge="<?=$scanning_charge?>";
            var rtps_trans_id="<?=$rtps_trans_id?>";
            var service_charge=$("#service_charge").val();
            var no_printing_page=$("#no_printing_page").val();
            var no_scanning_page=$("#no_scanning_page").val();
            var total_amount=parseFloat(service_charge);
            if(no_printing_page.length > 0 ){
              if(!isNaN(parseInt(no_printing_page)) && isFinite(no_printing_page) && parseInt(no_printing_page) > 0){
                total_amount +=parseInt(no_printing_page)*parseFloat(printing_charge);
                
              }else{
                // alert("Enter valid page number");
                // return false;
              }  
            }
            if(no_scanning_page.length  > 0 ){
              if(!isNaN(parseInt(no_scanning_page)) && isFinite(no_scanning_page) && parseInt(no_scanning_page) > 0){
                total_amount +=parseInt(no_scanning_page)*parseFloat(scanning_charge);
              }else{
                // alert("Enter valid page number");
                // return false;
              }
            }

            if (true) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Total Payable amount will be : "+total_amount,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    showLoaderOnConfirm: true,
                    confirmButtonText: 'Yes, Submit it!'
                }).then((result) => {
                    if (result.value) {
                       $("#paymentForm").submit();
                    }
                   
                    return false;
                });
            }
        });
    });
    if(env == "PROD"){
      $(document).bind("contextmenu",function(e) {
   e.preventDefault();
  });
  $(document).keydown(function(e){
      if(e.which === 123){
         return false;
      }
  });
    }


</script>
