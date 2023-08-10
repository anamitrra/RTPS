<?php
$role = $this->session->userdata("role");
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url("expr/admin/dashboard") ?>" class="brand-link text-center">

        <span class="brand-text font-weight-light ">Dashboard</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <?php if ($role->slug == "SA") { ?>
                  <li class="nav-item">
                      <a href="<?= base_url("expr/admin/users") ?>" class="nav-link">
                          <i class="nav-icon fas fa-users"></i>
                          <p class="text-break">Users</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="<?= base_url("expr/admin/roles") ?>" class="nav-link">
                          <i class="nav-icon fas fa-user-shield"></i>
                          <p class="text-break">Roles</p>
                      </a>
                  </li>
                  <!-- <li class="nav-item">
                      <a href="<?= base_url("expr/admin/departments") ?>" class="nav-link">
                          <i class="nav-icon fas fa-landmark"></i>
                          <p class="text-wrap">Departments</p>
                      </a>
                  </li> -->


                  <li class="nav-item">
                      <a href="<?= base_url("expr/admin/Portals") ?>" class="nav-link">
                          <i class="nav-icon fas fa-landmark"></i>
                          <p class="text-break">Portals</p>
                      </a>
                  </li>
              <?php } ?>
              <li class="nav-item">
                  <a href="<?= base_url("expr/admin/services") ?>" class="nav-link">
                      <i class="nav-icon fas fa-list"></i>
                      <p class="text-wrap">Services</p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="<?= base_url("expr/admin/my-transactions") ?>" class="nav-link">
                      <i class="nav-icon far fa-clipboard"></i>
                      <p class="text-wrap">My Transactions</p>
                  </a>
              </li>

       </nav>
        <!-- /.sidebar-menu -->
        <!-- /.sidebar -->
    </div>
</aside>
