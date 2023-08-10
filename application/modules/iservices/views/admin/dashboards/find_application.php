<style>
    .cd {
        color: darkcyan;
        font-weight: bold;
    }
</style>

<div class="">
    <?php
    if (!empty($page_path)) { ?>
        <div style="height:121px">
        <?php } else { ?>
            <div style="min-height:121px">
            <?php }

            ?>

            <div class="content-wrapper" style="min-height: 121px !important;">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title cd">Find Application</h3>

                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <form action="<?= base_url("iservices/admin/find_application/find") ?>" method="post">
                                    <div class="row">
                                        <div class="col-lg-6 col-6">
                                            <input type="text" placeholder="Application No / RTPS Transaction No /GRN / Mobile" class="form-control" name="app_ref_no" />
                                        </div>

                                        <div class="col-lg-3 col-6">
                                            <button type="submit" class="btn btn-sm btn-primary">Find</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <div class="row">
                <div class="col-md-12 ">
                    <?php
                    if (!empty($page_path)) {
                        $this->load->view($page_path, $intermediate_ids);
                    }

                    ?>
                </div>
            </div>
        </div>