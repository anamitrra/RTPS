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
    .error-message1,.error-user-mobile {
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
                  <?php
                  $page="guidelines/g".$pur_cd;
                  $this->load->view($page)?>
                  <hr/>
                  <form id="guidelineForm">
                  <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="varchar">Registration No.  <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="regn_no" id="regn_no" placeholder="" required />
                      <span class="error-message">Please enter registration no.</span>
                    </div>
                    <div class="form-group  col-sm-6">
                      <label for="varchar">Chassis No  <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="chassi_no" id="chassi_no" placeholder="" required  />
                      <span class="error-message1">Please enter chassis no.</span>
                    </div>
                  </div>
                  <?php if (isset($apply_by) && ($apply_by === "PFC" || $apply_by === "SA")): ?>
                    <div class="row">
                      <div class="form-group  col-sm-6">
                        <label for="applicant_name">Applicant Name.  <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="applicant_name" id="applicant_name" placeholder=""  required/>
                        <!-- <span class="error-applicant_name">Please Enter User Contact No.</span> -->
                      </div>
                      <div class="form-group  col-sm-6">
                        <label for="user_mobile">Applicant Mobile.  <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="user_mobile" id="user_mobile" minlength="10" maxlength="10"
                        pattern="^[6-9]\d{9}$" placeholder="" required />
                        <!-- <span class="error-user-mobile">Please Enter User Contact No.</span> -->
                      </div>
                    </div>

                    <div class="row">
                      <div class="form-group  col-sm-6">
                        <label for="applicant_name">Address Line 1  <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="address1" id="address1" placeholder="" required />
                        <!-- <span class="error-address1">Please enter address.</span> -->
                      </div>
                      <div class="form-group  col-sm-6">
                        <label for="applicant_name">Address Line 2.  <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="address2" id="address2" placeholder=""  required/>
                        <!-- <span class="error-address2">Please Enter address.</span> -->
                      </div>

                    </div>
                    <div class="row">
                      <div class="form-group  col-sm-6">
                        <label for="applicant_name">Address Line 3. </label>
                        <input type="text" class="form-control" name="address3" id="address3" placeholder=""  />
                        <!-- <span class="error-applicant_name">Please Enter User Contact No.</span> -->
                      </div>
                      <div class="form-group  col-sm-6">
                        <label for="applicant_name">Pin Code  <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="pin_code" id="pin_code" placeholder=""  pattern="\d{6}"  minlength="6" maxlength="6" required/>
                        <!-- <span class="error-applicant_name">Please Enter User Contact No.</span> -->
                      </div>
                    </div>

                  <?php endif; ?>


                  <button type="button" id="procced" class="btn  my-datatable-button" >
                      <span id="submitProcced" class="d-none">
                          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                      </span>
                      <span id="btnProccedTxt">Procced</span>
                  </button>
                </form>
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
<script src="<?= base_url('assets/plugins/parsley/parsley.min.js') ?>"></script>
<script>
$(function(){

  const formRef = $('#guidelineForm');
  var submitProcced = $('#submitProcced');
  var btnProccedTxtRef = $('#btnProccedTxt');
  var procced = $('#procced');
  var service_id=$("#service_id").val();
  var service_name=$("#service_name").val();
  var portal_no=$("#portal_no").val();
  var url=$("#url").val();


  procced.click(function () {
    var chassi_no=$("#chassi_no").val();
    var pur_cd=$("#pur_cd").val();
    var regn_no=$("#regn_no").val();
    var user_mobile=$("#user_mobile").val();
    var applicant_name=$("#applicant_name").val();
    var address1=$("#address1").val();
    var address2=$("#address2").val();
    var address3=$("#address3").val();
    var pin_code=$("#pin_code").val();
        // formRef.parsley().validate()
          if(formRef.parsley().validate()){

            Swal.fire({
                title: 'Are you sure?',
                text: "You are redirecting to external site. Please do not refresh or hit the refresh button ",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                showLoaderOnConfirm: true,
                confirmButtonText: 'Procced'
            }).then((result) => {
                if (result.value) {

                  if(submitProcced.hasClass('d-none')){
                      submitProcced.removeClass('d-none');
                      btnProccedTxtRef.addClass('d-none');
                  }
                //  procced.prop('disabled',true);
                  $.ajax({
                      url : "<?=$action?>",
                      type: 'POST',
                      dataType: 'json',
                      data: {
                         service_id : service_id,
                         url : url,
                         regn_no:regn_no,
                         pur_cd:pur_cd,
                         chassi_no:chassi_no,
                         portal_no:portal_no,
                         service_name:service_name,
                         user_mobile: user_mobile,
                         applicant_name:applicant_name,
                         address1:address1,
                         address2:address2,
                         address3:address3,
                         pin_code:pin_code,

                      },
                      success:function (response) {
                          console.log('success');
                           procced.prop('disabled',false);
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

                }})




          }


  });
})

</script>
