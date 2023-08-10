<?php
$role = $this->session->userdata("role");
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url("grm/dashboard") ?>" class="brand-link text-center">
        <span class="brand-text font-weight-light ">Dashboard</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-header">Grievance</li>
             <?php if($this->session->userdata('role')->slug === 'PFC') { ?>
            <li class="nav-item">
                <a href="<?= base_url('grm/admin/apply') ?>" class="nav-link">
                    <i class="nav-icon far fa-clipboard"></i>
                    <p class="text-break">Apply for Grievances</p>
                </a>
            </li>
            <?php } ?>
            <li class="nav-item">
                <a href="<?= base_url('grm/my-grievance') ?>" class="nav-link">
                    <i class="nav-icon fa fa-list-ol"></i>
                    <p class="text-break">My Grievances</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= base_url('grm/admin/status/view') ?>" class="nav-link">
                    <i class="nav-icon far fa-eye"></i>
                    <p class="text-break">View Grievance Status</p>
                </a>
            </li>
        </nav>
        <!-- /.sidebar-menu -->
        <!-- /.sidebar -->
    </div>
</aside>
