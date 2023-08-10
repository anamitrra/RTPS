<?php
$role = $this->session->userdata("role");
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url("dashboard") ?>" class="brand-link text-center">

        <span class="brand-text font-weight-light ">Dashboard</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

              <li class="nav-item">
                  <a href="<?= base_url("") ?>" class="nav-link">
                      <i class="nav-icon fas fa-list"></i>
                      <p class="text-break">Portals</p>
                  </a>
              </li>
       </nav>
        <!-- /.sidebar-menu -->
        <!-- /.sidebar -->
    </div>
</aside>
