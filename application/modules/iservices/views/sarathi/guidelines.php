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
                  <!-- <h2 class="transport-appl-header"><?=$service_name?></h2>
                  <p class="transport-appl-para">Supporting Documents:</p> -->
                  
                
                  <!-- <button type="button" id="procced" class="btn  my-datatable-button" >
                      <span id="submitProcced" class="d-none">
                          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                      </span>
                      <span id="btnProccedTxt">Procced</span>
                  </button> -->
                  <span id="procced">You are getting redirect to your application form .</span>
                </div>
            </div>
        </div>

        <div>
          <form id="process_form" action="<?=$url?>" method="post">
                        <input type="hidden" name="agId" id="agId" >
                        <input type="hidden" name="agencyPassword" id="agencyPassword" >
                        <input type="hidden" name="tkNo" id="tkNo" >
                        <input type="hidden" name="agCd" id="agCd" >
                        <input type="hidden" name="serCd" id="serCd" >
                        <input type="hidden" name="kioskId" id="kioskId" >
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
  var service_id='<?=$service_id ?>';
  var portal_no='<?=$portal_no ?>';
  var service_name='<?=$service_name ?>';
  var url='<?=$url ?>';

  procced.click(function () {

          if(submitProcced.hasClass('d-none')){
              submitProcced.removeClass('d-none');
              btnProccedTxtRef.addClass('d-none');
          }
          $.ajax({
                    url : "<?=base_url('iservices/sarathi/proceed')?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {
                       service_id : service_id,
                       service_name : service_name,
                       portal_no : portal_no,
                       url : url,
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
                            $("#agId").val(response.agId);
                            $("#agencyPassword").val(response.agId);
                            $("#tkNo").val(response.tkNo);
                            $("#agCd").val(response.agCd);
                            $("#serCd").val(response.serCd);
                            $("#kioskId").val(response.kioskId);

                           $("#process_form").submit();
                            

                        }else{
                            Swal.fire(
                                'Failed!',
                                response.error_msg !== undefined ? response.error_msg : response.msg,
                                'error'
                            );
                        }
                    },
                    error:function (error) {
                        console.log(error);

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

        //   Swal.fire({
        //       title: 'Are you sure?',
        //       text: "You are redirecting to external site. Please do not refresh or hit the refresh button ",
        //       icon: 'warning',
        //       showCancelButton: true,
        //       confirmButtonColor: '#28a745',
        //       cancelButtonColor: '#d33',
        //       showLoaderOnConfirm: true,
        //       confirmButtonText: 'Procced'
        //   }).then((result) => {
        //       if (result.value) {
               
        //       }else {
        //         if(btnProccedTxtRef.hasClass('d-none')) {
        //             submitProcced.addClass('d-none');
        //             btnProccedTxtRef.removeClass('d-none');
        //         }
        //         procced.prop('disabled',false);
        //       }


        //   });


  });

  setTimeout(function() {
        $("#procced").trigger('click');
    },1000);
})

</script>
