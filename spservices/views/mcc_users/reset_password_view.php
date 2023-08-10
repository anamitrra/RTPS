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
        <div class="card">
          <div class="card-header bg-dark text-white text-center"><b>Rest Password</b></div>
          <div class="card-body">
            <form method="POST" action="<?= base_url('spservices/mcc/updatepassword') ?>" enctype="multipart/form-data">
              <input type="hidden" name="onject_id" value="<?= $objId ?>" readonly>
              <input type="hidden" name="contact_number" value="<?= $mobile ?>" id="contact_number" readonly>
              <div class="form-group">
                <label for="otp">
                  OTP <span class="text-danger">*</span>
                  <small class="text-info pull-right">(Please enter OTP)</small>
                </label>
                <div class="input-group">
                  <input class="form-control" name="otp_no" type="number" id="otp_no" type="text" />
                  <div class="input-group-append ">
                    <a href="javascript:void(0)" class="btn btn-outline-danger verify_btn" id="verify_btn">Verify</a>
                    <a href="javascript:void(0)" class="btn btn-outline-success d-none" id="verified"><i class="fa fa-check"></i></a>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="password">New Password</label>
                <input required type="password" name="password" id="password" class="form-control">
              </div>
              <div class="form-group">
                <label for="confpass">Confirm Password</label>
                <input required type="password" name="confpass" id="confpass" class="form-control">
              </div>
              <div class="row">
                <div class="col">
                  <button type="submit" class="btn btn-info btn-block">Set Password</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $(document).on("click", "#verify_btn", function() {
      let contactNo = $("#contact_number").val();
      var otpNo = $("#otp_no").val();
      if (/^\d{6}$/.test(otpNo)) {
        $.ajax({
          type: "POST",
          dataType: "json",
          url: "<?= base_url('spservices/mcc_users/otps/verify_otp') ?>",
          data: {
            "mobile_number": contactNo,
            "otp": otpNo
          },
          beforeSend: function() {
            // $("#otp_no").val("");
            // $("#otp_no").attr("placeholder", "Verifying OTP... Please wait");
          },
          success: function(res) { //alert(JSON.stringify(res));
            if (res.status) {
              console.log(res);
              $("#send_mobile_otp").addClass('d-none');
              $("#verified").removeClass('d-none');
              Swal.fire({
                text: "Mobile Number has been successfully verified.",
                icon: 'success',
                showCancelButton: false,
                allowOutsideClick: false,
                allowEscapeKey: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
              }).then((result) => {
                // window.location = "<?= base_url('spservices/mcc_users/admindashboard') ?>";
              })
            } else {
              alert(res.msg);
              $("#otp_no").attr("placeholder", "ENTER THE 6-DIGIT OTP");
            } //End of if else
          }
        });
      } else {
        alert("OTP is invalid. Please enter a valid otp");
        $("#otp_no").val();
        $("#otp_no").focus();
      } //End of if else
    }); //End of onClick #verify_mobile_otp
  })
</script>