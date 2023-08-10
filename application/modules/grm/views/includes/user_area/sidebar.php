<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url("appeal/userarea") ?>" class="brand-link text-center">
        <span class="brand-text font-weight-light ">Dashboard</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="<?= base_url("appeal/myapplications") ?>" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p class="text-break">My Applications</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("appeal/myappeals") ?>" class="nav-link">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p class="text-break">My Appeals</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("appeal/make-appeal") ?>" class="nav-link">
                            <i class="nav-icon fas fa-landmark"></i>
                            <p class="text-wrap">Make Appeal</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("appeal/services") ?>" class="nav-link">
                            <i class="nav-icon far fa-clipboard"></i>
                            <p class="text-wrap">Make Second Appeal</p>
                        </a>
                    </li>
            </ul>
                    
        </nav>
        <!-- /.sidebar-menu -->
        <!-- /.sidebar -->
    </div>
</aside>