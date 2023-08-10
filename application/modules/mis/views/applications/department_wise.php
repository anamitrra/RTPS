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

                foreach ($data as $applicationInfo) {
                    //pre($applicationInfo);
                ?>

                    <div class="col-4">
                        <div class="small-box border border-info bg-white shadow">
                            <div class="inner">
                                <h4 class="font-weight-bold"><?= $applicationInfo->department_name ?></h4>
                                <span>
                                    <span class="badge badge-primary shadow">Applications Received : <span><?= $applicationInfo->total_received ?></span></span>
                                    <br>
                                    <span class="badge badge-warning shadow">Applications Pending : <span><?= $applicationInfo->pending ?></span></span>
                                    <br>
                                    <span class="badge badge-success shadow">Applications Delivered : <span><?= $applicationInfo->delivered ?></span></span>
                                    <br>
                                    <span class="badge badge-danger shadow">Applications Rejected : <span><?= $applicationInfo->rejected ?></span></span>
                                </span>
                                <a href="<?= base_url('mis/applications/dept-wise/list/' . $applicationInfo->_id) ?>" class="stretched-link"></a>
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