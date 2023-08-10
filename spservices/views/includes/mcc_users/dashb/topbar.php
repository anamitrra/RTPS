  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown user user-menu">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <?php if (!empty($this->session->userdata("image"))) : ?>
            <i class="fas fa-user-circle"></i>
          <?php else : ?>
            <i class="fas fa-user-circle"><?php echo $name ?></i>
          <?php endif; ?>
          <?= $this->session->userdata("name"); ?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a class="dropdown-item">
            <i class="fas fa-user-alt mr-2"></i> <?php echo $name ?>
          </a>
          <div class="dropdown-divider"></div>
          <?php
          $role = $this->session->userdata('admin')['role'];
          if ($role == 'SA') {
            $link = base_url("spservices/mcc_users/login/logout");
          } else if ($role == 'DA') {
            $link = base_url("spservices/mcc_users/login/logout");
          } else {
            $link = '';
          } ?>
          <a href="<?php echo $link ?>" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>