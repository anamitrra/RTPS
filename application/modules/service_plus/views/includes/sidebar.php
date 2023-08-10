<?php
$role = $this->session->userdata("role");
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url("appeal/dashboard") ?>" class="brand-link text-center">
        <span class="brand-text font-weight-light ">Dashboard</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if (in_array($role->slug,['SA','ADMIN'])) {
                    ?>
                    <li class="nav-item">
                        <a href="<?= base_url("service_plus/users") ?>" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p class="text-break">Users</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("service_plus/roles") ?>" class="nav-link">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p class="text-break">Roles</p>
                        </a>
                    </li>
                <?php } ?>
        </nav>
        <!-- /.sidebar-menu -->
        <!-- /.sidebar -->
    </div>
</aside>
