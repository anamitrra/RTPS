<?php
$role = $this->session->userdata('admin')['role'];
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?php
    // if ($role == 'SA') {
    //     $dashborad = base_url("spservices/mcc_users/admindashboard");
    // } else if ($role == 'DA') {
    //     $dashborad = base_url("spservices/mcc_users/districtdashboard");
    // } else {
    //     $dashborad = base_url("spservices");
    // } 
    $dashborad = base_url("spservices/mcc_users/admindashboard"); ?>
    <a href="<?= $dashborad ?>" class="brand-link text-center">
        <span class="brand-text font-weight-light ">Dashboard</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if ($role == 'DA') { ?>
                    <li class="nav-item">
                        <a href="<?= $dashborad ?>" class="nav-link">
                            <i class="fas fa-tachometer-alt nav-icon"></i>
                            <p class="text-wrap">Dashboard</p>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a href="<?= base_url("spservices/mcc_users/users") ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p class="text-wrap">Manage Users</p>
                    </a>
                </li>
                <?php if ($role == 'SA') { ?>
                    <li class="nav-item">
                        <a href="<?= base_url("spservices/mcc_users/district_admin") ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p class="text-wrap">District Admins</p>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a href="<?= base_url("spservices/mcc_users/admindashboard/application_transfer") ?>" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p class="text-wrap">Transfer Application</p>
                        </a>
                    </li> -->
                <?php } ?>
                <li class="nav-item">
                    <a href="<?= base_url("spservices/mcc_users/admindashboard/application_transfer") ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p class="text-wrap">Transfer Application</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url("spservices/mcc_users/admindashboard/profile") ?>" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p class="text-wrap">Profile</p>
                    </a>
                </li>
        </nav>
        <!-- /.sidebar-menu -->
        <!-- /.sidebar -->
    </div>
</aside>