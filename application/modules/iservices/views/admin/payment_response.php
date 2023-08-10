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
                  <input type="hidden" id="status" value="<?=$status?>" />
                  <input type="hidden" id="app_ref_no" value="<?=$app_ref_no?>" />
                  <input type="hidden" id="url" value="<?=$url?>" />
                  <?php if ($status === "Y"): ?>
                    <h2>Payment Successfull</h2><br/><br/>
                    <p>Please Wait.......</p>
                  <?php else: ?>
                    <h2>Payment Failed</h2><br/><br/>
                  <?php endif; ?>
                  <!--
                  <?php // TODO: message for pending status, and if failed need to display message ?>
                   your payment status could not be verified. Please click here to check the payment status -->
                </div>
            </div>
        </div>
    </div>
</div>


 <script>
 $(function(){
   var status=$('#status').val();
   console.log(status);
   var app_ref_no=$('#app_ref_no').val();
   var url=$('#url').val();
   if(status === "Y"){
     window.location=url;
   }
 })

</script>
