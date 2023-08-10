<?php
if(!empty($this->session->userdata('role_slug'))){
    $slug=$this->session->userdata('role_slug');
}else{
    $role = $this->session->userdata("role");
    $slug=isset($role->slug) ? $role->slug : "";
}
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url("spservices/office/dashboard") ?>" class="brand-link text-center">

        <span class="brand-text font-weight-light ">Dashboard</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <?php if($slug == "OFFSU"){ ?>
                    <li class="nav-item">
                        <a href="<?= base_url("spservices/offline/acknowledgement/form") ?>" class="nav-link">
                            <i class="nav-icon fas fa-file"></i>
                            <p class="text-wrap">Generate Acknowledgement </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("spservices/offline/acknowledgement/myapplications") ?>" class="nav-link">
                            <i class="nav-icon fas fa-file"></i>
                            <p class="text-wrap">Offline Applicantion </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("spservices/offline/servicelist") ?>" class="nav-link">
                            <i class="nav-icon fas fa-file"></i>
                            <p class="text-wrap">Offline Service List </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url("spservices/offline/office_list") ?>" class="nav-link">
                            <i class="nav-icon fas fa-file"></i>
                            <p class="text-wrap">Offline Office List </p>
                        </a>
                    </li>
                <?php }else{ ?>

               
              <?php if ($slug == "SA") { ?>
                  <li class="nav-item">
                      <a href="<?= base_url("iservices/admin/users") ?>" class="nav-link">
                          <i class="nav-icon fas fa-users"></i>
                          <p class="text-break">Users</p>
                      </a>
                  </li>
                  <li class="nav-item">
                      <a href="<?= base_url("iservices/admin/roles") ?>" class="nav-link">
                          <i class="nav-icon fas fa-user-shield"></i>
                          <p class="text-break">Roles</p>
                      </a>
                  </li>
                  <!-- <li class="nav-item">
                      <a href="<?= base_url("iservices/admin/departments") ?>" class="nav-link">
                          <i class="nav-icon fas fa-landmark"></i>
                          <p class="text-wrap">Departments</p>
                      </a>
                  </li> -->


                  <li class="nav-item">
                      <a href="<?= base_url("iservices/admin/Portals") ?>" class="nav-link">
                          <i class="nav-icon fas fa-landmark"></i>
                          <p class="text-break">Portals</p>
                      </a>
                  </li>
              <?php }  ?>
              <li class="nav-item">
                  <a href="<?= base_url("spservices/office/dashboard") ?>" class="nav-link">
                      <i class="nav-icon far fa-clipboard"></i>
                      <p class="text-wrap">Dashboard</p>
                  </a>
              </li>
              <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            Applications
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url("spservices/office/pending-applications") ?>" class="nav-link">
                                <i class="far fa-file-alt nav-icon"></i>
                                <p>Pending Applications</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("spservices/office/applications") ?>" class="nav-link">
                                <i class="far fa-file-alt nav-icon"></i>
                                <p>All Applications</p>
                            </a>
                        </li>

                        <!-- <li class="nav-item">
                            <a href="<?= base_url("spservices/office/forwarded-applications") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Forwarded Applications</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("spservices/office/office-revert-applications") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Office Revert Back</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("spservices/office/revert-applicant-applications") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Applicant Revert Back</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("spservices/office/delivered-applications") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Delivered Applications</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url("spservices/office/rejected-applications") ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Rejected Applications</p>
                            </a>
                        </li> -->

                    </ul>
                </li>
<!-- 
              <li class="nav-item">
                  <a href="<?= base_url("iservices/admin/my-transactions") ?>" class="nav-link">
                      <i class="nav-icon far fa-clipboard"></i>
                      <p class="text-wrap">My Transactions</p>
                  </a>
              </li> -->
              <li class="nav-item">
                  <a href="<?= base_url("spservices/office/certificates") ?>" class="nav-link">
                      <i class="nav-icon far fa-file-pdf"></i>
                      <p class="text-wrap">Certificates</p>
                  </a>
              </li>
              <!-- <li class="nav-item">
                  <a href="<?= base_url("spservices/office/change-password") ?>" class="nav-link">
                      <i class="nav-icon fas fa-key"></i>
                      <p class="text-wrap">Change Password</p>
                  </a>
              </li> -->
              <li class="nav-item">
                  <a href="<?= base_url("spservices/office/download-report") ?>" class="nav-link">
                      <i class="nav-icon fas fa-file"></i>
                      <p class="text-wrap">Download Reports</p>
                  </a>
              </li>
              <?php } ?>
       </nav>
        <!-- /.sidebar-menu -->
        <!-- /.sidebar -->
    </div>
</aside>
