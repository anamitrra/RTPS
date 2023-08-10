<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
    .transport-appl-header {
    padding: 0.5em;
    margin-bottom: 1rem;
    text-align: center;
    font-weight: bold;
    border-bottom: 1px solid lightgray;
}
.transport-appl-para{
  font-size: 1.1rem;
  font-weight: bold;
  padding: 1rem 0;
  margin: 0;
}
ul.transport-appl-list {
    margin: 0.5rem 0;
    list-style-type: decimal;
    list-style-position: inside;
}
.my-datatable-button {
    display: block;
    min-width: 150px;
    background: #C06C84;
    border-radius: .30rem;
    color: aliceblue !important;
    box-shadow: 0 3px 2px 0 rgba(0, 0, 0, 0.1);
    cursor: pointer;
    text-align: center;
    padding: 8px;
    text-transform: uppercase;
    margin: 3px 0 8px 0;
        margin-top: 3px;
    border: none;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                  <!-- <h2 class="transport-appl-header"><?=$service_name?></h2> -->
                  <!-- <p class="transport-appl-para">Supporting Documents:</p>
                  <?php if ($guidelines): ?>
                    <ul class="transport-appl-list">
                      <?php foreach ($guidelines as $key => $value): ?>
                          <li><?=$value;?></li>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif; ?> -->
                  <input type="hidden" name="service_id" id="service_id" value="<?= $service_id ?>">
                  <input type="hidden" name="portal_no" id="portal_no" value="<?= $portal_no ?>">
                  <input type="hidden" name="service_name" id="service_name" value="<?= $service_name ?>">
                  <input type="hidden" name="url" id="url" value="<?= $url ?>">
                
                      <span id="procced">You are getting redirect to your application form .</span>
                
                </div>
            </div>
        </div>

        <div>
          <form id="process_form" action="<?=$url?>" method="post">
            <input type="hidden" name="data" id="data_to_submit" />
          </form>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script>
$(function(){


  var submitProcced = $('#submitProcced');
  var btnProccedTxtRef = $('#btnProccedTxt');
  var procced = $('#procced');
  var service_id=$("#service_id").val();
  var portal_no=$("#portal_no").val();
  var service_name=$("#service_name").val();
  var url=$("#url").val();

  procced.click(function () {

          if(submitProcced.hasClass('d-none')){
              submitProcced.removeClass('d-none');
              btnProccedTxtRef.addClass('d-none');
          }

          $.ajax({
                    url : "<?=base_url('iservices/procced')?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                       service_id : service_id,
                       service_name : service_name,
                       portal_no : portal_no,
                       url : url
                    },
                    success:function (response) {
                        console.log('success');
                        if(response.status){
                            if(!submitProcced.hasClass('d-none')) {
                                submitProcced.addClass('d-none');
                            }
                            if(btnProccedTxtRef.hasClass('d-none')) {
                                btnProccedTxtRef.removeClass('d-none');
                            }
                            $("#data_to_submit").val(response.encrypted_data)
                            $("#process_form").submit();
                            // window.location=response.url;

                            // Swal.fire(
                            //     'Success!',
                            //     response.message !== undefined ? response.message : "Operation success",
                            //     'success'
                            // );

                        }else{
                            Swal.fire(
                                'Failed!',
                                response.error_msg !== undefined ? response.error_msg : response.msg,
                                'error'
                            );
                        }
                    },
                    error:function (error) {
                        console.log('error');

                        Swal.fire(
                            'Failed!',
                            'Something went wrong!',
                            'error'
                        );
                    }
                }).always(function(){
                    // console.log('always');
                    if(btnProccedTxtRef.hasClass('d-none')) {
                        submitProcced.addClass('d-none');
                        btnProccedTxtRef.removeClass('d-none');
                    }
                    procced.prop('disabled',false);
                });

  });

  setTimeout(function() {
        $("#procced").trigger('click');
    },1000);

})

</script>
