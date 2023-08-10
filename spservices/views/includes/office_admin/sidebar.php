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
              <?php if ($slug == "SA") { ?>
                  <li class="nav-item">
                      <a href="<?= base_url("iservices/admin/users") ?>" class="nav-link">
                          <i class="nav-icon fas fa-users"></i>
                          <p class="text-break">Users</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="<?= base_url("iservices/admin/roles") ?>" class="nav-link">
                          <i class="nav-icon fas fa-user-shield"></i>
                          <p class="text-break">Roles</p>
                      </a>
                  </li>
                  <!-- <li class="nav-item">
                      <a href="<?= base_url("iservices/admin/departments") ?>" class="nav-link">
                          <i class="nav-icon fas fa-landmark"></i>
                          <p class="text-wrap">Departments</p>
                      </a>
                  </li> -->


                  <li class="nav-item">
                      <a href="<?= base_url("iservices/admin/Portals") ?>" class="nav-link">
                          <i class="nav-icon fas fa-landmark"></i>
                          <p class="text-break">Portals</p>
                      </a>
                  </li>
              <?php }  ?>

            

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
