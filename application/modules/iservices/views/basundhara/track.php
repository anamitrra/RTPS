<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>

.loader_2 {
    position: static;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid blue;
  border-bottom: 16px solid blue;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body d-flex flex-column align-items-center" style="gap: 1em;">
                
                      <div class="loader_2"></div>
                      <span id="procced">You are getting redirect to your application form .</span>
                
                </div>
            </div>
        </div>

        <div>
          <form id="process_form" action="<?=$url?>" method="post">
            <input type="hidden" value="<?=$enc?>" name="data" id="data_to_submit" />
          </form>
        </div>
    </div>
</div>
<script>
$(function(){
  setTimeout(function() {
        // $("#procced").trigger('click');
        $("#process_form").submit();
    },1000);

})

</script>
