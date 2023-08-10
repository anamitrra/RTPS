<style>
.rtps-container {
    width: 1145px;
    margin: 0 auto;
    padding: 1rem 0.5rem;
    max-width: 100%;
}
.rtps-container form {
    display: flex;
    flex-flow: column nowrap;
    align-items: center;
    justify-content: center;
    border: 1px solid lightgray;
    border-radius: 0.25rem;
    padding: 1.2rem;
}
.form-container {
    width: 65%;
    margin: 0.8rem 0;
}
.form-container label {
    display: inline-block;
    cursor: default;
    font-size: larger;
    font-weight: bold;
    padding-bottom: 0.3em;
}
.form-container input, .form-container select {
    display: block;
    width: 100%;
    height: 37px;
    border-radius: 0.25rem;
    font-size: medium;
    padding: 0.5em;
    outline: none;
}
.error-message {
    padding-top: 0.3em;
    margin: 0;
    color: red;
    font-weight: lighter;
    display: none;
}
#track-btn {
    min-width: 200px;
    font-size: medium;
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
    border: none;
}
.track-data {
    margin: 2rem 0;
}
.track-error {
    display: none;
    text-align: center;
    text-transform: capitalize;
    font-weight: bold;
    font-size: larger;
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
    padding: 1rem;
}
</style>


<!-- HTML Body -->

<div id="includedContent_header"></div>

<main class="rtps-container">
  <form>
    <div class="form-container">
      <div class="row">
      <label for="appl-no">Enter Application No. </label>
      <input type="text" id="app_ref_no" name="app_ref_no" placeholder="Application No..." autocomplete="on" required>
      <p class="error-message">Please enter application no.</p>
    </div>
    <div class="row" style="padding-top:5px">
      <label for="appl-no">Enter Mobile No. </label>
      <input type="password" id="mobile" name="mobile" placeholder="Mobile Number" autocomplete="on" required>
    </div>
  </div>

    <button type="submit" class="my-datatable-button" id="track-btn">Track Application</button>
  </form>


  <!-- Show Application Tracking result here...  -->
  <section class="track-data"></section>

</main>


<!-- <div id="includedContent_footer"></div> -->



<script>
  $(document).ready(function () {

    function showResultOnUI(result) {
      // Remove Loading state
      $('#track-btn').text('Track Application')
      $('#track-btn').removeClass('disable-btn');
    //  console.log(result);

      if (result.success) {
        // Check wheather data is Empty or Not

        const response = result.data;
        var html='<table class="table bordered">\
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
      $(response.task_details).each(function(key,val){
        let action =val.action;
        let sl=key+1;
        if(action === "query"){
          action='<a target="_BLANK" href='+val.url+'?data='+response.app_ref_no+' class="btn btn-info">Reply</a>';
        }
        if(action === "complete"){
          action='<a target="_BLANK" href='+val.url+'?data='+response.app_ref_no+' class="btn btn-success">Download</a>';
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
      $(".track-data").html(html);



        // $('.track-data').());

        $(".track-success").fadeIn();

      }
      else {
        // display error message
        $('.track-data').html(`
          <div class="track-error">
            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
            <span>${result.data}</span>
          </div>
        `);

        $(".track-error").fadeIn();
      }
    }


    function getCurrentData(app_ref_no,mobile) {


      $.ajax(
        {
          url: '<?=base_url()?>expr/status/getRequestInfo',
          data:{app_ref_no:app_ref_no,mobile:mobile},
          type: 'POST',
          dataType: 'json',

          beforeSend: function (xhr) {
            // Show loading animation

            $('#track-btn').text('Please Wait...')
            $('#track-btn').addClass('disable-btn');

          },

          success: function (result, status, xhr) {
            // console.log(result);
            if(result.status ==="success"){
              showResultOnUI({
                success: true,
                data: result.data
              });
            }else {
              showResultOnUI({
                success: false,
                data: 'Either application number is invalid or unable to get data from Server. Please try with correct Number or try after sometime'
              });
            }


          },

          error: function (xhr, status, error) {
            // HTTP Error Handler : 404, 500 or NULL response

            showResultOnUI({
              success: false,
              data: 'Either application number is invalid or unable to get data from Server. Please try with correct Number or try after sometime'
            });

          }
        }
      );
    }


    // Track Button Handler
    $('#track-btn').on('click', function (event) {
      event.preventDefault();

      const applNo = $('#app_ref_no').val();
      const mobile = $('#mobile').val();

      if (applNo !== '') {

        // Hide error mesages
        $('.error-message').slideUp();

        // Send data to Server
        getCurrentData(applNo,mobile);
      }
      else {
        if (applNo === '') {
          $($('.error-message')[0]).slideDown();
        }
        else {
          $($('.error-message')[0]).slideUp();
        }

      }

    });

  });
</script>
