<style>
    .main-sidebar .brand-link {
        background: aliceblue;
    }

    .main-sidebar .brand-link span {
        color: #000;
        font-weight: 500 !important;
    }

    .main-sidebar {
        background: #000;
    }
</style>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <?php
    // user_type: 4 for state admin, 5 for deptartment admin
    $user_type = $this->session->userdata('administrator')['user_type']; ?>
    <a href="<?= base_url('spservices/admin/dashboard') ?>" class="brand-link text-center">
        <span class="brand-text font-weight-light "> <?= ($user_type == 4) ? 'Dashboard' : $this->session->userdata('administrator')['dept_id'] .'<br>Dashboard'; ?></span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item ">
                    <a href="<?= base_url("spservices/admin/dashboard") ?>" class="nav-link">
                        <i class="fa fa-tachometer-alt nav-icon"></i>
                        <p class="text-wrap">Dashboard</p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="<?= base_url("spservices/userregistration") ?>" class="nav-link" target="_blank">
                        <i class="fa fa-user-plus nav-icon"></i>
                        <p class="text-wrap">User Registration</p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="<?= base_url("spservices/admin/users") ?>" class="nav-link">
                        <i class="fa fa-users-cog nav-icon"></i>
                        <p class="text-wrap">Manage Users</p>
                    </a>
                </li>

                <?php if ($user_type == 4) { ?>
                    <li class="nav-item ">
                        <a href="<?= base_url("spservices/admin/deptadmin") ?>" class="nav-link">
                            <i class="fas fa-users nav-icon"></i>
                            <p class="text-wrap">Department Admin</p>
                        </a>
                    </li>
                <?php } ?>
                <li class="nav-item ">
                    <a href="<?= base_url("spservices/admin/services") ?>" class="nav-link">
                        <i class="fa fa-cogs nav-icon"></i>
                        <p class="text-wrap">Manage Service</p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="<?= base_url("spservices/admin/applications/migration") ?>" class="nav-link">
                        <i class="fa fa-file-export nav-icon"></i>
                        <p class="text-wrap">Migrate Applications</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url("spservices/admin/mis") ?>" class="nav-link">
                        <i class="far fa-user nav-icon"></i>
                        <p class="text-wrap">MIS</p>
                    </a>
                </li>
        </nav>
        <!-- /.sidebar-menu -->
        <!-- /.sidebar -->
    </div>
</aside>