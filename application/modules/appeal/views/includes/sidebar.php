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
                        <a href="<?= base_url("appeal/users") ?>" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p class="text-break">Users</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("appeal/users/inactive") ?>" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p class="text-break">Inactive Users</p>
                        </a>
                    </li>
                <?php
                if($role->slug !== 'ADMIN' ){
                    ?>
                    <li class="nav-item">
                        <a href="<?= base_url("appeal/roles") ?>" class="nav-link">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p class="text-break">Roles</p>
                        </a>
                    </li>
                <?php }?>
                    <li class="nav-item">
                        <a href="<?= base_url("appeal/departments") ?>" class="nav-link">
                            <i class="nav-icon fas fa-landmark"></i>
                            <p class="text-wrap">Departments</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("appeal/commission") ?>" class="nav-link">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p class="text-wrap">Commission</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("appeal/aservices") ?>" class="nav-link">
                            <i class="nav-icon far fa-clipboard"></i>
                            <p class="text-wrap">Services</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("appeal/locations") ?>" class="nav-link">
                            <i class="nav-icon fas fa-map-marker-alt"></i>
                            <p class="text-wrap">Locations</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("appeal/officials") ?>" class="nav-link">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p class="text-wrap">Officials Mapping</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("appeal/officials_draft") ?>" class="nav-link">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p class="text-wrap">Draft Officials Mapping</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("appeal/holiday") ?>" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p class="text-wrap">Holiday List</p>
                        </a>
                    </li>
                    <?php
                    if($role->slug !== 'ADMIN' ){
                        ?>
                        <li class="nav-item">
                            <a href="<?= base_url("appeal/templates") ?>" class="nav-link">
                                <i class="nav-icon fas fa-columns"></i>
                                <p class="text-wrap">Templates</p>
                            </a>
                        </li>
                    <?php }?>
                <?php } ?>
                <?php if (in_array($role->slug,['AA','DPS'])):?>
                <li class="nav-item has-treeview menu-open">
                    <a href="#!" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p class="text-break">
                            Appeals
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="<?= base_url('appeal/list') ?>" class="nav-link">
                                <i class="far fa-file nav-icon"></i>
                                <p class="text-break">First Appeals</p>
                            </a>
                        </li>

                        <?php if (in_array($role->slug,['DPS'])):?>
                        <li class="nav-item">
                            <a href="<?= base_url('appeal/list/second') ?>" class="nav-link">
                                <i class="far fa-copy nav-icon"></i>
                                <p class="text-break">Second Appeals</p>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                    <?php endif; ?>
                <?php if (in_array($role->slug,['RA'])):?>
                <li class="nav-item has-treeview menu-open">
                    <a href="#!" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p class="text-break">
                            Appeals
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="<?= base_url('appeal/list') ?>" class="nav-link">
                                    <i class="far fa-file nav-icon"></i>
                                    <p class="text-break">First Appeals</p>
                                </a>
                            </li>

                       <li class="nav-item">
                           <a href="<?= base_url('appeal/list/second') ?>" class="nav-link">
                               <i class="far fa-copy nav-icon"></i>
                               <p class="text-break">Second Appeals</p>
                           </a>
                       </li>
                        <li class="nav-item">
                            <a href="<?= base_url('appeal/expired') ?>" class="nav-link">
                                <i class="far fa-file-archive nav-icon"></i>
                                <p class="text-break">Delayed Appeals</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('appeal/locked') ?>" class="nav-link">
                                <i class="far fa-file-archive nav-icon"></i>
                                <p class="text-break">Locked Appeals</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('appeal/rejected') ?>" class="nav-link">
                                <i class="fas fa-file-prescription nav-icon"></i>
                                <p class="text-break">Rejected Appeals</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('appeal/resolved') ?>" class="nav-link">
                                <i class="fas fa-file-signature nav-icon"></i>
                                <p class="text-break">Resolved Appeals</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("appeal/pull/pending/applications") ?>" class="nav-link">
                                <i class="nav-icon fas fa-columns"></i>
                                <p class="text-wrap">Pull pending applications</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif;
                if (in_array($role->slug,['PFC','DA'])) { ?>
                    <li class="nav-item has-treeview menu-open">
                        <a href="!#" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p class="text-break">
                                <?=strtoupper($role->slug)?> Appeals
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('/appeal/applications') ?>" class="nav-link">
                                    <i class="fa fa-folder-plus nav-icon"></i>
                                    <p class="text-break">Applications</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?=base_url('appeal/applications/myappeals')?>" class="nav-link">
                                    <i class="fa fa-folder nav-icon"></i>
                                    <p class="text-break">My Appeals</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('appeal/list') ?>" class="nav-link">
                                    <i class="fa fa-file nav-icon"></i>
                                    <p class="text-break">First Appeals</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('appeal/list/second') ?>" class="nav-link">
                                    <i class="fa fa-copy nav-icon"></i>
                                    <p class="text-break">Second Appeals</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("appeal/make-appeal") ?>" class="nav-link">
                                    <i class="nav-icon fas fa-landmark"></i>
                                    <p class="text-wrap">Make Appeal</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php }

                if (in_array($role->slug,['RR','MOC'])) {?>
                    <li class="nav-item has-treeview menu-open">
                        <a href="!#" class="nav-link">
                            <i class="nav-icon fas fa-list"></i>
                            <p class="text-break">
                                Appeals
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="<?= base_url('appeal/list') ?>" class="nav-link">
                                    <i class="far fa-file nav-icon"></i>
                                    <p class="text-break">First Appeals</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= base_url('appeal/list/second') ?>" class="nav-link">
                                    <i class="far fa-copy nav-icon"></i>
                                    <p class="text-break">Second Appeals</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= base_url("appeal/pull/pending/applications") ?>" class="nav-link">
                                    <i class="nav-icon fas fa-columns"></i>
                                    <p class="text-wrap">Pull pending applications</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>

                <?php if (in_array($role->slug,['RA','SA'])):?>
                <li class="nav-header">Reports</li>
                <li class="nav-item">
                    <a href="<?= base_url('appeal/reports') ?>" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <p class="text-break">View Reports</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('appeal/reports/penalty') ?>" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <p class="text-break">View penalty</p>
                    </a>
                </li>
                <?php endif;?>
        </nav>
        <!-- /.sidebar-menu -->
        <!-- /.sidebar -->
    </div>
</aside>
