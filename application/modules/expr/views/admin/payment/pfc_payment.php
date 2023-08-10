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
                        <input type="number" class="form-control" name="no_printing_page" id="no_printing_page" placeholder=""  />
                        <!-- <span class="error-applicant_name">Please Enter User Contact No.</span> -->
                      </div>
                      <div class="form-group  col-sm-6">
                        <label for="user_mobile">No of Scanning Page. </label>
                        <input type="number" class="form-control" name="no_scanning_page" id="no_scanning_page" placeholder=""  />
                      </div>
                    </div>
                    <?php if ($ApplicationStatus === "S"): ?>
                        <div class="row">
                          <div class="col-sm-4">
                            <form id="paymentForm" action="<?=$this->config->item('egras_url')?>" method="post">
                              <input type="hidden" name="DEPT_CODE" value="<?=$department_data['DEPT_CODE']?>"/>
                              <input type="hidden" name="OFFICE_CODE" value="<?=$department_data['OFFICE_CODE']?>"/>
                              <input type="hidden" name="REC_FIN_YEAR" value="<?=$department_data['REC_FIN_YEAR']?>"/>
                              <input type="hidden" name="HOA1" value="<?=$department_data['HOA1']?>"/>
                              <input type="hidden" name="FROM_DATE" value="<?=$department_data['FROM_DATE']?>"/>
                              <input type="hidden" name="TO_DATE" value="<?=$department_data['TO_DATE']?>"/>
                              <input type="hidden" name="PERIOD" value="<?=$department_data['PERIOD']?>"/>
                              <input type="hidden" name="CHALLAN_AMOUNT" value="<?=$department_data['CHALLAN_AMOUNT']?>"/>
                              <input type="hidden" name="DEPARTMENT_ID" value="<?=$department_data['DEPARTMENT_ID']?>"/>
                              <input type="hidden" name="MOBILE_NO" value="<?=$department_data['MOBILE_NO']?>"/>
                              <input type="hidden" name="SUB_SYSTEM" value="<?=$department_data['SUB_SYSTEM']?>"/>
                              <input type="hidden" name="PARTY_NAME" value="<?=$department_data['PARTY_NAME']?>"/>
                              <input type="hidden" name="PIN_NO" value="<?=$department_data['PIN_NO']?>"/>
                              <input type="hidden" name="ADDRESS1" value="<?=$department_data['ADDRESS1']?>"/>
                              <input type="hidden" name="ADDRESS2" value="<?=$department_data['ADDRESS2']?>"/>
                              <input type="hidden" name="ADDRESS3" value="<?=$department_data['ADDRESS3']?>"/>
                              <input type="hidden" name="MULTITRANSFER" value="<?=$department_data['MULTITRANSFER']?>"/>
                              <input type="hidden" name="NON_TREASURY_PAYMENT_TYPE" value="<?=$department_data['NON_TREASURY_PAYMENT_TYPE']?>"/>
                              <input type="hidden" name="TOTAL_NON_TREASURY_AMOUNT" id="TOTAL_NON_TREASURY_AMOUNT" value="<?=$department_data['TOTAL_NON_TREASURY_AMOUNT']?>"/>
                              <input type="hidden" name="AC1_AMOUNT" id="AC1_AMOUNT" value="<?=$department_data['AC1_AMOUNT']?>"/>
                              <input type="hidden" name="ACCOUNT1" value="<?=$department_data['ACCOUNT1']?>"/>
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

    const updatePfcPaymentAmountURL = '<?=base_url('expr/admin/update-pfc-payment-amount')?>';
    const env='<?= ENV ?>';

    $(document).ready(function () {

        var saveAndPFCAmount = $('#saveAndPFCAmount');


        saveAndPFCAmount.click(function () {

            var amount="<?=$department_data['AC1_AMOUNT']?>";
            var printing_charge="<?=$printing_charge?>";
            var scanning_charge="<?=$scanning_charge?>";
            var rtps_trans_id="<?=$rtps_trans_id?>";
            var service_charge=$("#service_charge").val();
            var no_printing_page=$("#no_printing_page").val();
            var no_scanning_page=$("#no_scanning_page").val();
            var total_amount=parseFloat(service_charge);
            if(no_printing_page.length > 0 ){
              // console.log("printing ::"+no_printing_page)
                total_amount +=parseInt(no_printing_page)*parseFloat(printing_charge);
            }
            if(no_scanning_page.length  > 0 ){
                // console.log("printing ::"+no_scanning_page)
                total_amount +=parseInt(no_scanning_page)*parseFloat(scanning_charge);
            }

            $("#AC1_AMOUNT").val(total_amount);
            $("#TOTAL_NON_TREASURY_AMOUNT").val(total_amount);
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
                      $.ajax({
                          type:'POST',
                          url: updatePfcPaymentAmountURL,
                          dataType: 'json',
                          data: {rtps_trans_id : rtps_trans_id,no_printing_page:no_printing_page,no_scanning_page:no_scanning_page,payment_params:encodeURIComponent($('#paymentForm').serialize())},
                          beforeSend: function(){
                              swal.fire({
                                  html: '<h5>Processing...</h5>',
                                  showConfirmButton: false,
                                  allowOutsideClick: () => !Swal.isLoading(),
                                  onOpen: function() {
                                      Swal.showLoading();
                                  }
                              });
                          },
                          success: function(response){

                            console.log(response);
                            //return  false;
                              if(response.status){
                                  $("#paymentForm").submit();

                              }else{
                                  Swal.fire('Failed','Something went wrong','error');
                              }
                          },
                          error:function(){
                              Swal.fire('Failed','Something went wrong','error');
                              return false;
                          }
                      })
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
