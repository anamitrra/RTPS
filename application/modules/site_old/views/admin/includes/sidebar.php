<?php
$role = $this->session->userdata('designation');
// pre($role);

if ($role == 'System Administrator') {
    # code...
?>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="<?= base_url("site/admin/dashboard") ?>" class="brand-link text-center">

            <span class="brand-text font-weight-light ">Dashboard</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <li class="nav-item">
                        <a href="<?= base_url("site/admin/departments") ?>" class="nav-link">
                            <i class="nav-icon fas fa-landmark"></i>
                            <p class="text-wrap">Departments</p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Services
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url("site/admin/online") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Services</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("site/admin/service_category") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Service Categories</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>
                                Documents
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url("site/admin/documents") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Documents</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("site/admin/document_category"); ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Document Categories</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">

                            <i class="nav-icon fas fa-video"></i>
                            <p>
                                Videos
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url("site/admin/videos") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Videos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("site/admin/videos_category"); ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Video Categories</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-folder-minus"></i>
                            <p>
                                Content
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url("site/admin/banners") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Banners</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("site/admin/about") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>About</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("site/admin/faq"); ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>FAQ</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("site/admin/contact"); ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Contact</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("site/admin/access"); ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Accessibility</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                    <!-- Notice -->
                    <li class="nav-item">
                        <a href="<?= base_url("site/admin/notice") ?>" class="nav-link">
                            <i class="fas fa-flag nav-icon"></i>
                            <p class="text-wrap">Notice Board</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("site/admin/miscellaneous") ?>" class="nav-link">
                            <i class="fas fa-stream nav-icon"></i>
                            <p class="text-wrap">Miscellaneous</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url("site/admin/site_text") ?>" class="nav-link">
                            <i class="fas fa-font nav-icon"></i>
                            <p class="text-wrap">Site Texts</p>
                        </a>
                    </li>

                    <!-- Download MIS Data -->
                    <li class="nav-item">
                        <a href="<?= base_url("site/admin/download_data") ?>" class="nav-link">
                            <i class="fas fa-download nav-icon"></i>
                            <p class="text-wrap">Download MIS Data</p>
                        </a>
                    </li>


                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">

                            <i class="fab fa-searchengin  nav-icon"></i>
                            <p>
                                Elastic Search
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="<?= base_url("site/admin/elastic_insert") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Insert</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url("site/admin/elastic_update"); ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Update</p>
                                </a>
                            </li>

                        </ul>
                    </li>


                    <li class="nav-item">
                        <a href="<?= base_url("site/admin/logs") ?>" class="nav-link">
                            <i class="fas fa-history nav-icon"></i>
                            <p class="text-wrap">User Logs</p>
                        </a>
                    </li>

            </nav>
            <!-- /.sidebar-menu -->
            <!-- /.sidebar -->
        </div>
    </aside>
<?php
} elseif ($role == 'Administrator') {
?>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="<?= base_url("site/admin/dashboard") ?>" class="brand-link text-center">

            <span class="brand-text font-weight-light ">Dashboard</span>
        </a>
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    <!-- <li class="nav-item">
                    <a href="<?= base_url("site/admin/departments") ?>" class="nav-link">
                        <i class="nav-icon fas fa-landmark"></i>
                        <p class="text-wrap">Departments</p>
                    </a>
                </li> -->

                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Services
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url("site/admin/online") ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Services</p>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                            <a href="<?= base_url("site/admin/service_category") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Service Categories</p>
                            </a>
                        </li> -->

                        </ul>
                    </li>

                    <!-- Download MIS Data -->
                    <li class="nav-item">
                        <a href="<?= base_url("site/admin/download_data") ?>" class="nav-link">
                            <i class="fas fa-download nav-icon"></i>
                            <p class="text-wrap">Download MIS Data</p>
                        </a>
                    </li>

                    <!-- <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Documents
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url("site/admin/documents") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Documents</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("site/admin/document_category"); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Document Categories</p>
                            </a>
                        </li>

                    </ul>
                </li> -->

                    <!-- <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                       
                        <i class="nav-icon fas fa-video"></i>
                        <p>
                            Videos
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url("site/admin/videos") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Videos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("site/admin/videos_category"); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Video Categories</p>
                            </a>
                        </li>

                    </ul>
                </li> -->

                    <!-- <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-folder-minus"></i>
                        <p>
                            Content
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                    <li class="nav-item">
                            <a href="<?= base_url("site/admin/banners") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Banners</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("site/admin/about") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>About</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("site/admin/faq"); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>FAQ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("site/admin/contact"); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Contact</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("site/admin/access"); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Accessibility</p>
                            </a>
                        </li>  

                    </ul>
                </li> -->

                    <!-- <li class="nav-item">
                    <a href="<?= base_url("site/admin/miscellaneous") ?>" class="nav-link">
                    <i class="fas fa-stream nav-icon"></i>
                        <p class="text-wrap">Miscellaneous</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url("site/admin/site_text") ?>" class="nav-link">
                    <i class="fas fa-font nav-icon"></i>
                        <p class="text-wrap">Site Texts</p>
                    </a>
                </li>
                 

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                       
                    <i class="fab fa-searchengin  nav-icon"></i>
                        <p>
                        Elastic Search
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                       
                        <li class="nav-item">
                            <a href="<?= base_url("site/admin/elastic_insert") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Insert</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("site/admin/elastic_update"); ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Update</p>
                            </a>
                        </li>

                    </ul>
                </li>


                <li class="nav-item">
                    <a href="<?= base_url("site/admin/logs") ?>" class="nav-link">
                    <i class="fas fa-history nav-icon"></i>
                        <p class="text-wrap">User Logs</p>
                    </a>
                </li> -->

            </nav>
            <!-- /.sidebar-menu -->
            <!-- /.sidebar -->
        </div>
    </aside>
<?php
}
?>