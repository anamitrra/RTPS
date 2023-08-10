<main class="rtps-container">
  <div class="container mt-4">
    <div class="row">
      <div class="col-md-5 mx-auto">
        <div class="card">
          <div class="card-header bg-dark text-white"><b>Administrator Login</b></div>
          <div class="card-body">
            <form method="POST" action="<?= base_url('spservices/mcc_users/login/authenticate/') ?>" enctype="multipart/form-data">
              <?php
              if (!empty($this->session->flashdata('msg'))) {
                echo "<div class='alert alert-danger text-center'>" . $this->session->flashdata('msg') . "</div>";
              }
              ?>
              <div class="input-group mb-3">
                <input required type="text" name="username" id="username" class="form-control" placeholder="Username">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-user"></span>
                  </div>
                </div>
              </div>
              <div class="input-group mb-3">
                <input required type="password" name="password" id="password" type="password" class="form-control" placeholder="Password">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <button type="submit" class="btn btn-primary btn-block">Sign In <i class="fa fa-sign-in"></i></button>
                </div>
              </div>
            </form>
            <br>
            <a href="<?= base_url('spservices/mcc/forgot-password') ?>" class="text-info">Trouble in login ? Click here</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>