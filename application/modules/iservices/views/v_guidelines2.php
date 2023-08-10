<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
    .error-message {
        padding-top: 0.3em;
        margin: 0;
        color: red;
        font-weight: lighter;
        display: none;
    }
    .error-message1 {
        padding-top: 0.3em;
        margin: 0;
        color: red;
        font-weight: lighter;
        display: none;
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
<div class="content-wrapper">
<div class="container">
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                  <h2 class="transport-appl-header">Certificate of Fitness of Transport Vehicles</h2>
                  <p class="transport-appl-para">Supporting Documents:</p>


                  <ul class="transport-appl-list">
                                                <li>Up-to-date tax receipt [Mandatory]</li>
                                                <li>Up-to-date Pollution Under Control (PUC) Certificate [Mandatory]</li>
                                                <li>Copy of valid Insurance Certificate [Mandatory]</li>
                                                <li>Copy of existing Registration Certificate [Mandatory]</li>
                                                <li>Geographical Positioning System (GPS) Certificate in all kinds of goods and passenger carrying transport vehicle [Mandatory]</li>
                                                <li>Speed Limit Device (SLD) Certificate in all kinds of notified goods and passenger carrying transport vehicle [Mandatory]</li>
                  </ul>
                  <input type="hidden" name="service_id" id="service_id" value="<?= $service_id ?>">
                  <input type="hidden" name="service_name" id="service_name" value="<?= $service_name ?>">
                  <input type="hidden" name="portal_no" id="portal_no" value="<?= $portal_no ?>">
                  <input type="hidden" name="url" id="url" value="<?= $url ?>">
                  <input type="hidden" name="pur_cd" id="pur_cd" value="<?= $pur_cd ?>">
                  <p class="transport-appl-para">Note:</p>
                  <ul class="transport-appl-list">
                      <li>After online submission of the application, applicant needs to visit the DTO for physical verification of the documents. The service delivery process will start only after the hardcopy submission of the form and physical verification of the documents.</li>
                      <li>Post final approval from DTO, applicant needs to visit the AMTRON counter for smart card fee payment</li>
                  </ul>

                  <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="varchar">Registration No. </label>
                      <input type="text" class="form-control" name="regn_no" id="regn_no" placeholder=""  />
                      <span class="error-message">Please enter registration no.</span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="form-group  col-sm-6">
                      <label for="varchar">Chassis No </label>
                      <input type="text" class="form-control" name="chassi_no" id="chassi_no" placeholder=""  />
                      <span class="error-message1">Please enter chassis no.</span>
                    </div>
                  </div>



                  <button type="button" id="procced" class="btn  my-datatable-button" >
                      <span id="submitProcced" class="d-none">
                          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                      </span>
                      <span id="btnProccedTxt">Proceed</span>
                  </button>
                </div>
            </div>
        </div>

        <!-- <div>
          <form id="process_form" action="<?=$url?>" method="post">
            <input type="hidden" name="data" id="data_to_submit" />
          </form>
        </div> -->
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
  var url=$("#url").val();


  procced.click(function () {
    var chassi_no=$("#chassi_no").val();
    var pur_cd=$("#pur_cd").val();
    var regn_no=$("#regn_no").val();
    var service_name=$("#service_name").val();
    var portal_no=$("#portal_no").val();
    let validation =false;


          if(regn_no === ""){
            $('.error-message').slideDown();
              validation=false;
          }else {
            $('.error-message').slideUp();
            validation=true;
          }
          if(chassi_no === ""){
            $('.error-message1').slideDown();
            validation=false;
          }else {
            $('.error-message1').slideUp();
            validation=true;
          }
          if(validation){
            if(submitProcced.hasClass('d-none')){
                submitProcced.removeClass('d-none');
                btnProccedTxtRef.addClass('d-none');
            }
            procced.prop('disabled',true);
            $.ajax({
                url : "<?=base_url('iservices/v-procced')?>",
                type: 'POST',
                dataType: 'json',
                data: {
                   service_id : service_id,
                   url : url,
                   regn_no:regn_no,
                   pur_cd:pur_cd,
                   chassi_no:chassi_no,
                   service_name:service_name,
                   portal_no:portal_no
                },
                success:function (response) {
                    console.log('success');
                  //    procced.prop('disabled',false);
                    if(response.status){
                        if(!submitProcced.hasClass('d-none')) {
                            submitProcced.addClass('d-none');
                        }
                        if(btnProccedTxtRef.hasClass('d-none')) {
                            btnProccedTxtRef.removeClass('d-none');
                        }
                        // $("#data_to_submit").val(response.encrypted_data)
                        // $("#process_form").submit();
                        window.location=response.url;

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
              //  procced.prop('disabled',false);
            });

          }


  });
})

</script>
