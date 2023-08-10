<?php
$role = $this->session->userdata("role");
$slug=isset($role->slug) ? $role->slug : "";
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url("spservices/office_users/admin") ?>" class="brand-link text-center">

        <span class="brand-text font-weight-light ">Dashboard</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

              <li class="nav-item">
                  <a href="<?= base_url("spservices/office_users/user_status") ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                      <p class="text-wrap">View All Office Users</p>
                  </a>
              </li>

              <li class="nav-item">
                  <a href="<?= base_url("spservices/office_users/admin") ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                      <p class="text-wrap">Admin Details</p>
                  </a>
              </li>

             

              <li class="nav-item">
                  <a href="<?= base_url("spservices/office_users/admin/application_transfer") ?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                      <p class="text-wrap">Application Transfer</p>
                  </a>
              </li>

              
       </nav>
        <!-- /.sidebar-menu -->
        <!-- /.sidebar -->
    </div>
</aside>
