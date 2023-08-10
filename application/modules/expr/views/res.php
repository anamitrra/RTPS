<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
</style>
<div class="container">
  <?php //var_dump($saheb);die;?>
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                  <!-- <h2>Response</h2><br/><br/> -->
                    <div class="row">
                        <div class="col-sm-12">
                          Status : <?=$status?>
                        </div>
                        <?php if ($status === "S"): ?>
                          <div class="col-sm-12">
                            Your application has been submitted successfully
                            <P>Please make payment of Rs. 30</p>
                          </div>
                        <?php endif; ?>
                        <!-- <div class="col-sm-12">
                          data : <?php // print($response); ?>
                        </div> -->


                    </div>
                    <?php if ($status === "S"): ?>
                        <div class="row">
                          <div class="col-sm-4">
                            <form action="<?=$this->config->item('egras_url')?>" method="post">
                              <input type="hidden" name="DEPT_CODE" value="<?=$DEPT_CODE?>"/>
                              <input type="hidden" name="OFFICE_CODE" value="<?=$OFFICE_CODE?>"/>
                              <input type="hidden" name="REC_FIN_YEAR" value="<?=$REC_FIN_YEAR?>"/>
                              <input type="hidden" name="HOA1" value="<?=$HOA1?>"/>
                              <input type="hidden" name="FROM_DATE" value="<?=$FROM_DATE?>"/>
                              <input type="hidden" name="TO_DATE" value="<?=$TO_DATE?>"/>
                              <input type="hidden" name="PERIOD" value="<?=$PERIOD?>"/>
                              <input type="hidden" name="CHALLAN_AMOUNT" value="<?=$CHALLAN_AMOUNT?>"/>
                              <input type="hidden" name="DEPARTMENT_ID" value="<?=$DEPARTMENT_ID?>"/>
                              <input type="hidden" name="MOBILE_NO" value="<?=$MOBILE_NO?>"/>
                              <input type="hidden" name="SUB_SYSTEM" value="<?=$SUB_SYSTEM?>"/>
                              <input type="hidden" name="PARTY_NAME" value="<?=$PARTY_NAME?>"/>
                              <input type="hidden" name="PIN_NO" value="<?=$PIN_NO?>"/>
                              <input type="hidden" name="ADDRESS1" value="<?=$ADDRESS1?>"/>
                              <input type="hidden" name="ADDRESS2" value="<?=$ADDRESS2?>"/>
                              <input type="hidden" name="ADDRESS3" value="<?=$ADDRESS3?>"/>
                              <input type="hidden" name="MULTITRANSFER" value="<?=$MULTITRANSFER?>"/>
                              <input type="hidden" name="NON_TREASURY_PAYMENT_TYPE" value="<?=$NON_TREASURY_PAYMENT_TYPE?>"/>
                              <input type="hidden" name="TOTAL_NON_TREASURY_AMOUNT" value="<?=$TOTAL_NON_TREASURY_AMOUNT?>"/>
                              <input type="hidden" name="AC1_AMOUNT" value="<?=$AC1_AMOUNT?>"/>
                              <input type="hidden" name="ACCOUNT1" value="<?=$ACCOUNT1?>"/>
                              <button type="submit" class="btn btn-primary"  >
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
