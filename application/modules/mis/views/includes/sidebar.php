<?php
$role = $this->session->userdata("role");
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url("mis") ?>" class="brand-link text-center">
        <span class="brand-text font-weight-light ">MIS</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if (isset($role->slug) && $role->slug == "SA") { ?>
                    <li class="nav-item">
                        <a href="<?= base_url("mis/view") ?>" class="nav-link">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p class="text-wrap">Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("mis/rtps_commission") ?>" class="nav-link">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p class="text-wrap">RTPS Commission</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>
                                RTPS ACT
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="https://rtps.assam.gov.in/storage/PORTAL/2021/03/25/e6fcca32d8edc30f7d00b8019cc750db.pdf" target="_blank" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard"></i>
                                    <p class="text-break">ARTPS - ACT 2019</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="https://rtps.assam.gov.in/storage/PORTAL/2021/03/25/44eb5b5aace93ff3fdeaac7d746be105.pdf" target="_blank" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard"></i>
                                    <p class="text-break">ARTPS - RULES 2012</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="https://rtps.assam.gov.in/storage/PORTAL/2021/04/03/bb7511928a85341659bd23e5155373db.pdf"  target="_blank" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard"></i>
                                    <p class="text-break">ARTPS - ACT 2012</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>
                                Manage Users
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url("mis/users") ?>" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p class="text-break">Users</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("mis/roles") ?>" class="nav-link">
                                    <i class="nav-icon fas fa-user-shield"></i>
                                    <p class="text-break">Roles</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                     <li class="nav-item">
                        <a href="<?= base_url("mis/offices") ?>" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p class="text-break">Offices</p>
                        </a>
                    </li> 
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>
                            Departments
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                            <a href="<?= base_url("mis/departments") ?>" class="nav-link">
                            <i class="nav-icon fas fa-clipboard"></i>
                            <p class="text-wrap">Master</p>
                        </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("mis/online/get_department_services") ?>" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard"></i>
                                    <p class="text-break">Service List</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("mis/online/get_office_services") ?>" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard"></i>
                                    <p class="text-break">Office List</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="nav-item">
                        <a href="<?= base_url("mis/departments") ?>" class="nav-link">
                            <i class="nav-icon fas fa-clipboard"></i>
                            <p class="text-wrap">Departments</p>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a href="<?= base_url("mis/online") ?>" class="nav-link">
                            <i class="nav-icon far fa-clipboard"></i>
                            <p class="text-wrap">Services</p>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a href="<?= base_url("mis/artps-services") ?>" class="nav-link">
                            <i class="nav-icon far fa-clipboard"></i>
                            <p class="text-wrap">ARTPS Services</p>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>
                                Applications
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url("mis/search") ?>" class="nav-link">
                                    <i class="nav-icon fas fa-search"></i>
                                    <p class="text-break">Search Applications</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("mis/applications") ?>" class="nav-link">
                                    <i class="nav-icon far fa-circle nav-icon"></i>
                                    <p class="text-break">All Applications</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("mis/applications/pending") ?>" class="nav-link">
                                    <i class="nav-icon far fa-circle nav-icon"></i>
                                    <p class="text-break">Pending Applications</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("mis/applications/delivered") ?>" class="nav-link">
                                    <i class="nav-icon far fa-circle nav-icon"></i>
                                    <p class="text-break">Delivered Applications</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("mis/applications/dept-wise-summary") ?>" class="nav-link">
                                    <i class="nav-icon fas fa-user-shield"></i>
                                    <p class="text-break">Department Wise</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                   
                    <li class="nav-item">
                        <a href="<?= base_url("mis/grievance/report") ?>" class="nav-link">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p class="text-break">Grievance</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>
                                Reports
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url("mis/servicewise") ?>" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard"></i>
                                    <p class="text-break">Servicewise Data</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("mis/depertmentwise") ?>" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard"></i>
                                    <p class="text-break">Departmentwise Data</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("mis/officewise") ?>" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard"></i>
                                    <p class="text-break">Officewise Data</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("mis/districtewise") ?>" class="nav-link">
                                    <i class="nav-icon fas fa-clipboard"></i>
                                    <p class="text-break">Districtwise Data</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("mis/applications/view-revenue-filter") ?>" class="nav-link">
                                    <i class="nav-icon fas fa-user-shield"></i>
                                    <p class="text-break">Revenue</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>
                                Appeal
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url("mis/appeal") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Find Appeal</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-chart-pie"></i>
                                    <p>
                                        First Appeal
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url("mis/appeal/first-appeal") ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>First Appeal by service</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url("mis/appeal/first-appeal-by-disttrict") ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>First Appeal By Disttrict</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-chart-pie"></i>
                                    <p>
                                        Second Appeal
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="<?= base_url("mis/appeal/second-appeal-by-service") ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Second Appeal by service</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= base_url("mis/appeal/second-appeal-by-disttrict") ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Second Appeal By Disttrict</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                    <a href="<?= base_url("mis/revenue_reports") ?>" class="nav-link">
                        <i class="nav-icon far fa-clipboard"></i>
                        <p class="text-wrap">Kiosk Payment reports</p>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a href="<?= base_url("mis/revenue_reports/citizen") ?>" class="nav-link">
                        <i class="nav-icon far fa-clipboard"></i>
                        <p class="text-wrap">Citizen Payment reports</p>
                    </a>
                    </li>
                    <li class="nav-item">
                    <a href="<?= base_url("mis/feedback/list") ?>" class="nav-link">
                        <i class="nav-icon far fa-clipboard"></i>
                        <p class="text-wrap">Feedback reports</p>
                    </a>
                 </li>

                <?php } ?>
                <li class="nav-item">
                    <a href="<?= base_url("mis/citizen") ?>" class="nav-link">
                        <i class="nav-icon far fa-clipboard"></i>
                        <p class="text-wrap">Citizen Info Report</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url("mis/citizen/access-log") ?>" class="nav-link">
                        <i class="nav-icon far fa-clipboard"></i>
                        <p class="text-wrap">Citizen Info Access Log</p>
                    </a>
                </li>
                
        </nav>
        <!-- /.sidebar-menu -->
        <!-- /.sidebar -->
    </div>
</aside>