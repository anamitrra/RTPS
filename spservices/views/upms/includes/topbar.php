<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?=base_url("spservices/upms/dashboard")?>" class="nav-link"><i class="fas fa-home"></i> HOME</a>
        </li>
        <?php if($this->session->upms_user_type == 1) { ?>
            <li class="nav-item">
                <a class="nav-link" href="<?=base_url('spservices/upms/users')?>"><i class="fas fa-users"></i> USERS</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?=base_url('spservices/upms/apptracker')?>"><i class="fas fa-map-marker"></i> TRACK</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?=base_url('spservices/upms/export')?>"><i class="fas fa-file-export"></i> EXPORT</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?=base_url('spservices/upms/importjson')?>"><i class="fas fa-file-import"></i> IMPORT</a>
            </li>
        <?php } ?>
    </ul>

    <!-- Right navbar links -->    
    <ul class="navbar-nav ml-auto">
        <?php if($this->session->upms_user_type == 1) { ?>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="fas fa-book-open"></i> MASTER ENTRY <i class="fas fa-angle-double-down"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="<?=base_url('spservices/upms/rights')?>" class="dropdown-item">
                    <i class="fas fa-angle-double-right mr-2"></i> Rights
                </a>                
                <div class="dropdown-divider"></div>
                
                <a href="<?=base_url('spservices/upms/roles')?>" class="dropdown-item">
                    <i class="fas fa-angle-double-right mr-2"></i> Roles
                </a>                
                <div class="dropdown-divider"></div>
                
                <a href="<?= base_url("spservices/upms/depts"); ?>" class="dropdown-item">
                    <i class="fas fa-angle-double-right mr-2"></i> Departments
                </a>                
                <div class="dropdown-divider"></div>
                
                <a href="<?= base_url("spservices/upms/svcs"); ?>" class="dropdown-item">
                    <i class="fas fa-angle-double-right mr-2"></i> Services
                </a>                
                <div class="dropdown-divider"></div>
                
                <a href="<?= base_url("spservices/upms/levels"); ?>" class="dropdown-item">
                    <i class="fas fa-angle-double-right mr-2"></i> Task levels
                </a>
                <div class="dropdown-divider"></div>
                
                <a href="<?= base_url("spservices/upms/offices"); ?>" class="dropdown-item">
                    <i class="fas fa-angle-double-right mr-2"></i> Offices
                </a>
                <div class="dropdown-divider"></div>
                
                <a href="<?= base_url("spservices/upms/buttonlevels"); ?>" class="dropdown-item">
                    <i class="fas fa-angle-double-right mr-2"></i> Button/URL levels
                </a>
            </div>
        </li>
        <?php } ?>
        <li class="nav-item dropdown user user-menu">
            <a class="nav-link text-uppercase" data-toggle="dropdown" href="#">
                <i class="fa fa-user-circle"></i>
                <?= $this->session->loggedin_user_fullname; ?>
                <i class="fas fa-angle-double-down"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a class="dropdown-item" href="<?= base_url('spservices/upms/users/profile') ?>">
                    <i class="fas fa-user-circle mr-2"></i> My profile
                </a>
                <a href="<?= base_url("spservices/upms/users/changepass"); ?>" class="dropdown-item">
                    <i class="fas fa-user-cog"></i> Change password
                </a>
                <a href="<?= base_url("spservices/upms/login/logout"); ?>" class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </li>
    </ul>
    
</nav>
