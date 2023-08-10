<main class="rtps-container">
  <div class="container mt-4">
    <div class="row">
      <div class="col-md-5 mx-auto">
        <?php
        if (!empty($this->session->flashdata('success_msg'))) {
          echo "<div class='alert alert-success text-center'>" . $this->session->flashdata('success_msg') . "</div>";
        }
        if (!empty($this->session->flashdata('msg'))) {
          echo "<div class='alert alert-danger text-center'>" . $this->session->flashdata('msg') . "</div>";
        }
        ?>
        <div class="card mb-5">
          <div class="mobile_div">
            <div class="card-header bg-dark text-white text-center"><b>Forgot Password</b></div>
            <div class="card-body">
              <div class="send_otp_form">
                <p>Enter your registered Mobile Number to reset your password.</p>
                <div class="form-group">
                  <label for="mobile">Mobile Number</label>
                  <input required type="text" name="contact_number" id="contact_number" class="form-control" maxlength="10">
                </div>
                <div class="row">
                  <div class="col">
                    <button type="button" class="btn btn-info btn-block" id="send_mobile_otp">Reset Password</button>
                  </div>
                </div>
              </div>
              <div class="otp_form d-none">
                <div class='alert alert-primary text-center'>Enter OTP received in your mobile number.</div>
                <div class="form-group">
                  <label for="otp">
                    OTP <span class="text-danger">*</span>
                    <small class="text-info pull-right">(Please enter OTP)</small>
                  </label>
                  <div class="input-group">
                    <input class="form-control" name="otp_no" id="otp_no" type="text" />
                  </div>
                </div>
                <div class="form-group">
                  <button type="button" class="btn btn-success btn-block" id="verify_otp">Verify OTP</button>
                  <button type="button" class="btn btn-warning btn-block" id="resend_otp">Resend OTP</button>
                </div>
              </div>
            </div>
          </div>
          <div class="password_reset_div d-none">
            <div class="card-header bg-dark text-white text-center"><b>Reset Password</b></div>
            <div class="card-body">
              <input type="hidden" id="mobile" value="">
              <div class="form-group">
                <label for="password">New Password</label>
                <input required type="password" name="password" id="password" class="form-control" minlength="5">
              </div>
              <div class="form-group">
                <label for="confpass">Confirm Password</label>
                <input required type="password" name="confpass" id="confpass" class="form-control" minlength="5">
              </div>
              <div class="row">
                <div class="col">
                  <button type="submit" class="btn btn-info btn-block" id="reset_password">Reset Password</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<div class="modal" id="myModal">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center">
        <i class="fas fa-spinner fa-spin" style="font-size: 90px;"></i>
        <p class="mt-3">Please wait..while we are processing your request.</p>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $(document).on("click", "#send_mobile_otp", function() {
      let contactNo = $("#contact_number").val();
      if (/^\d{10}$/.test(contactNo)) {
        $("#otp_no").val("");
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?= base_url('spservices/mcc_users/passwordreset/send_reset_otp') ?>",
          data: {
            "mobile_number": contactNo
          },
          beforeSend: function() {
            $('#myModal').modal()
          },
          success: function(res) {
            $('#myModal').modal('hide')
            if (res.status) {
              Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'OTP sent successfully.',
                showConfirmButton: false,
                timer: 1500
              })
              $('.send_otp_form').addClass('d-none')
              $('.otp_form').removeClass('d-none');
              $('#mobile').val(contactNo)
            } else {
              Swal.fire({
                position: 'center',
                icon: 'error',
                title: res.msg,
                showConfirmButton: false,
              })
            } //End of if else
          }
        });
      } else {
        alert("Contact number is invalid. Please enter a valid number");
        $("#contact_number").val();
        $("#contact_number").focus();
        return false;
      } //End of if else
    }); //End of onclick #send_mobile_otp

    $(document).on("click", "#resend_otp", function() {
      let contactNo = $("#contact_number").val();
      if (/^\d{10}$/.test(contactNo)) {
        $("#otp_no").val("");
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?= base_url('spservices/mcc_users/passwordreset/send_reset_otp') ?>",
          data: {
            "mobile_number": contactNo
          },
          beforeSend: function() {
            $('#myModal').modal()
          },
          success: function(res) {
            $('#myModal').modal('hide')
            if (res.status) {
              Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'OTP sent successfully.',
                showConfirmButton: false,
                timer: 1500
              })
              $('.send_otp_form').addClass('d-none')
              $('.otp_form').removeClass('d-none');
              $('#mobile').val(contactNo)
            } else {
              Swal.fire({
                position: 'center',
                icon: 'error',
                title: res.msg,
                showConfirmButton: false,
              })
            } //End of if else
          }
        });
      } else {
        alert("Contact number is invalid. Please enter a valid number");
        $("#contact_number").val();
        $("#contact_number").focus();
        return false;
      } //End of if else
    }); //End of onclick #send_mobile_otp

    $(document).on("click", "#verify_otp", function() {
      let contactNo = $("#contact_number").val();
      var otpNo = $("#otp_no").val();
      if (/^\d{6}$/.test(otpNo)) {
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?= base_url('spservices/mcc_users/passwordreset/verify_otp') ?>",
          data: {
            "mobile_number": contactNo,
            "otp": otpNo,
          },
          beforeSend: function() {
            $('#myModal').modal()
            $("#otp_no").val("");
          },
          success: function(res) {
            $('#myModal').modal('hide')
            if (res.status) {
              $('.mobile_div').addClass('d-none');
              $('.password_reset_div').removeClass('d-none');
            } else {
              Swal.fire({
                position: 'center',
                icon: 'error',
                title: res.msg,
                showConfirmButton: false,
              })
              $("#otp_no").attr("placeholder", "ENTER THE 6-DIGIT OTP");
            }
          }
        });
      } else {
        Swal.fire({
          position: 'center',
          icon: 'error',
          title: 'OTP is invalid. Please enter a valid otp',
          showConfirmButton: false,
        })
        $("#otp_no").val();
        $("#otp_no").focus();
      }
    });

    $(document).on("click", "#reset_password", function() {
      let contactNo = $("#mobile").val();
      var password = $("#password").val();
      var confpass = $("#confpass").val();
      $.ajax({
        type: "POST",
        dataType: "json",
        url: "<?= base_url('spservices/mcc_users/passwordreset/updatepassword') ?>",
        data: {
          "mobile_number": contactNo,
          "password": password,
          "confpass": confpass,
        },
        beforeSend: function() {
          $('#myModal').modal()
          $("#otp_no").val("");
        },
        success: function(res) {
          $('#myModal').modal('hide')
          if (res.status) {
            Swal.fire({
              text: "Password reset successfully.",
              icon: 'success',
              showCancelButton: false,
              allowOutsideClick: false,
              allowEscapeKey: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'OK'
            }).then((result) => {
              window.location = "<?= base_url('spservices/mcc/admin-login') ?>";
            })
          } else {
            Swal.fire({
              position: 'center',
              icon: 'error',
              title: res.msg,
              showConfirmButton: false,
            })
          }
        }
      });
    });
  })
</script>