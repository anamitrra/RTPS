<style>
  #captchaImgId {
    border: 1px solid #ddd !important;
    /* height: 33px !important;
    width: 120px !important; */
  }
</style>
<main class="rtps-container">
  <div class="container mt-4">
    <div class="row">
      <div class="col-md-5 mx-auto">
        <div class="card shadow">
          <div class="card-header bg-dark text-white text-center"><h5><b>Administrator Login</b></h5></div>
          <div class="card-body">
            <form method="POST" action="<?= base_url('spservices/admin/login/authenticate') ?>" enctype="multipart/form-data">
              <?php
              if (!empty($this->session->flashdata('login_error'))) {
                echo "<div class='alert alert-danger text-center'>" . $this->session->flashdata('login_error') . "</div>";
              }
              if (!empty($this->session->flashdata('er_msg'))) {
                echo "<div class='alert alert-danger text-center'>" . $this->session->flashdata('er_msg') . "</div>";
              }
              ?>
              <div class="input-group mb-3">
                <input required type="text" name="username" id="username" class="form-control" placeholder="Username">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fa fa-user"></span>
                  </div>
                </div>
              </div>
              <div class="input-group mb-3">
                <input required type="password" name="password" id="password" type="password" class="form-control" placeholder="Password">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fa fa-lock"></span>
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6">
                  <div class="input-group">
                    <span id="captchadiv" style="float:left;"></span>
                    <div class="input-group-prepend">
                      <button class="btn btn-success" id="reloadcaptcha" type="button"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <input required type="text" name="captcha" id="captcha" type="captcha" class="form-control" placeholder="Enter Captcha">
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <button type="submit" class="btn btn-primary btn-block">Sign In <i class="fa fa-sign-in"></i></button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<script type="text/javascript">
  $(function() {
    $.post("<?= base_url('spservices/admin/login/createcaptcha') ?>", function(res) {
      $("#captchadiv").html(res);
    });
    $(document).on("click", "#reloadcaptcha", function() {
      $.post("<?= base_url('spservices/admin/login/createcaptcha') ?>", function(res) {
        $("#captchadiv").html(res);
      });
    });
  })
</script>