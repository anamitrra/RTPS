<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<style>
    .parsley-errors-list{
        color: red;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-sm-12 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                  <div class="card-header">
                    <h4 style="text-align: center;flex: 1;">Application Status </h4>
                  </div>
                  <br/><br/>

                  <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="varchar">Application Reference Number </label>
                      <input type="text" class="form-control" name="app_ref_no" id="app_ref_no"  placeholder="" value="" />
                    </div>
                  </div>
                  <!-- <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="varchar">Mobile No </label>
                      <input type="text" class="form-control" name="mobile"  placeholder="" value="" />
                    </div>
                  </div> -->

                  <div class="row">
                    <div class="col-sm-6">
                      <button type="button" id="process" class="btn btn-primary mb-2" >
                          <span id="submitProcced" class="d-none">
                              <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing ...
                          </span>
                          <span id="btnProccedTxt">Check</span>
                      </button>
                    </div>
                  </div>
                  <!-- response block -->
                  <div id="response">
                  </div>
                  <!-- end of response block -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
  let base_url='http://127.0.0.1:8080/transport-1.0/';
  var submitProcced = $('#submitProcced');
  var btnProccedTxtRef = $('#btnProccedTxt');
  // var procced = $('#procced');

  getStatus=function(res){
        var html='<table class="table">\
        <tr>\
        <th>Sl No</th>\
        <th>User Name</th>\
        <th>User Designation</th>\
        <th>User Office</th>\
        <th>Received Time</th>\
        <th>Executed Time</th>\
        <th>Remark</th>\
        <th>Action</th>\
        </tr>';
      $(res.task_details).each(function(key,val){
        let action =val.action;
        let sl=key+1;
        if(action === "query"){
          action='<button class="btn btn-info">Reply</button>';
        }
        // console.log(val.task_details.user_name)
        html+='<tr>\
          <td>'+sl+'</td>\
          <td>'+val.user_name+'</td>\
          <td>'+val.user_designation+'</td>\
          <td>'+val.user_office+'</td>\
          <td>'+val.received_time+'</td>\
          <td>'+val.executed_time+'</td>\
          <td>'+val.remark+'</td>\
          <td>'+action+'</td>\
        </tr>';

      })
      html+"</table>";
      $("#response").html(html);
      handleLoader();

  }
  $("#process").click(function(){
    if(submitProcced.hasClass('d-none')){
        submitProcced.removeClass('d-none');
        btnProccedTxtRef.addClass('d-none');
    }
    var app_ref_no=$("#app_ref_no").val();
//    alert(app_ref_no);
    //get encrypted data
    $.ajax({
          url:"<?=base_url()?>status/getRequestInfo/",
          type:"POST",
          dataType: 'json',
          data:{app_ref_no:app_ref_no},
          success:function(response) {
            if(response.status === "success"){
              getStatus(response.data);
            }else {
                $("#response").html('<div>No records found</div>');
                handleLoader();
            }


          },
          error:function(data, data1, data2){
            handleLoader()
          }
      });


  })

  handleLoader=function(){
    if(!submitProcced.hasClass('d-none')) {
        submitProcced.addClass('d-none');
    }
    if(btnProccedTxtRef.hasClass('d-none')) {
        btnProccedTxtRef.removeClass('d-none');
    }
  }



  //$('#trackStatus').click(function(){
      if($("#applicationNo").val() === ""){
        alert("Please enter application no");
      }else if ($("#stateCode").val() === "") {
        alert("Please select state");
      }else {

           var param={
             "applNo":$("#applicationNo").val(),
             "stateCode":$("#stateCode").val()
           };
      $.ajax({
            url:base_url+"application/current/",
            type:"POST",
            dataType:'json',
            contentType: "application/json",
            // headers: {
            //   'X-Requested-With':  'XMLHttpRequest',
            //   'Accept': '*/*',
            //   'Cache-Control':'no-cache',
            //   "contentType": "application/json; charset=utf-8"
            // },
            data:JSON.stringify(param),
            success:function(response) {
              $('#appHeader').show();
              $("#loader").hide();
              //  console.log(response);
                  $("#applicationDetail").empty();
                $("#applicationDetail").append(
                  '<tr>\
                  <td>Application No.</td><td>'+response.appl_no+'</td>\
                  </tr>\
                  <tr>\
                  <td>Application Date.</td><td>'+response.appl_dt+'</td>\
                  </tr>\
                  <tr>\
                  <td>Registration No.</td><td>'+response.regno+'</td>\
                  </tr>\
                  <tr>\
                  <td>purCd</td><td>'+response.purCd+'</td>\
                  </tr>\
                  <tr>\
                  <td>Description</td><td>'+response.purCdDescr+'</td>\
                  </tr>\
                  <tr>\
                  <td>Registered At</td><td>'+response.registeredAt+'</td>\
                  </tr>'
                );
                get_detail(response.purCd);
            },
            error:function(data, data1, data2){
              $("#loader").hide();
            }
        });

      }

  })




})
</script>
