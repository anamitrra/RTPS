<style>
    .main-sidebar {
        background-color: #192587;
    }

    .main-sidebar .sidebar {
        padding: 0;
        margin: 0;
    }

    .active-tab {
        border-left: 4px solid red;
        border-radius: 0 !important;
        color: #fff !important;
        /*     border: 2px solid red */
    }
</style>
<?php $activeLink = $page ?? ''; ?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url("spservices/upms/dashboard") ?>" class="brand-link text-center p-1" style="background-color:orange">
        <!-- <span class="brand-text font-weight-light ">Dashboard</span> -->
        <img src="<?= base_url('assets/site/sewasetu/assets/images/SS_LOGO.png') ?>" alt="logo" width="25%">
        <!-- <span class="brand-text font-weight-light ">Dashboard</span> -->
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= base_url("spservices/upms/dashboard") ?>" class="nav-link <?= $activeLink == 'dashboard' ? 'active-tab' : '' ?>">
                        <i class="fas fa-angle-double-right"></i>
                        <p class="text-break">Dashboard</p>
                    </a>
                </li> 
                <li class="nav-item">
                    <a href="<?= base_url("spservices/upms/myapplications") ?>" class="nav-link <?= $activeLink == 'my_applications' ? 'active-tab' : '' ?>">
                        <i class="fas fa-angle-double-right"></i>
                        <p class="text-break">My applications</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url("spservices/upms/dashboard/appsbystatus/d") ?>" class="nav-link <?= $activeLink == 'd' ? 'active-tab' : '' ?>">
                        <i class="fas fa-angle-double-right"></i>
                        <p class="text-break">Delivered applications</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url("spservices/upms/dashboard/appsbystatus/qs") ?>" class="nav-link <?= $activeLink == 'qs' ? 'active-tab' : '' ?>">
                        <i class="fas fa-angle-double-right"></i>
                        <p class="text-break">Queried applications</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url("spservices/upms/dashboard/appsbystatus/r") ?>" class="nav-link <?= $activeLink == 'r' ? 'active-tab' : '' ?>">
                        <i class="fas fa-angle-double-right"></i>
                        <p class="text-break">Rejected applications</p>
                    </a>
                </li>

                <?php if(isset($this->session->reports_url) && strlen($this->session->reports_url)) { $reportsUrl = base_url("spservices/".$this->session->reports_url.'/'.$this->session->loggedin_login_username);
                    echo "<li class='nav-item'>
                        <a href='{$reportsUrl}' class='nav-link' target='_blank'>
                            <i class='fas fa-angle-double-right'></i>
                            <p class='text-break'>Reports</p>
                        </a>
                    </li>";
                }//End of if ?>
            </ul>
        </nav>
    </div>
</aside>