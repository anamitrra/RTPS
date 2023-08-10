
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <div class="row mb-2">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">Department Wise Applications</h1>
                </div>
            </div>
            <div class="row">
                <?php

                foreach ($deptWiseApplications as $applicationInfo){
                    //pre($applicationInfo);
                 ?>

                    <div class="col-4">
                        <div class="small-box border border-info bg-white shadow">
                            <div class="inner">
                                <h4 class="font-weight-bold"><?=$applicationInfo->department->department_name?></h4>
                                <span>
                                <span class="badge badge-primary shadow">Applications Received : <span><?=$applicationInfo->application_received?></span></span>
                                <br>
                                <span class="badge badge-warning shadow">Applications Pending : <span><?=$applicationInfo->application_pending?></span></span>
                                <br>
                                <span class="badge badge-success shadow">Applications Delivered : <span><?=$applicationInfo->application_delivered?></span></span>
                                <br>
                                <span class="badge badge-danger shadow">Applications Rejected : <span><?=$applicationInfo->application_rejected?></span></span>
                            </span>
                                <a href="<?=base_url('applications/dept-wise/list/'.$applicationInfo->department->{'_id'}->__tostring())?>" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>

                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>