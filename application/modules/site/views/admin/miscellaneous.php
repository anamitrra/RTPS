<?php
//pre($last_updates);
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <!-- Page Heading & Nav breadcrumb  -->
            <div class="row mb-4">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">Miscellaneous</h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">Miscellaneous</li>
                    </ol>
                </div>
            </div>


            <!-- Action Success/Fail alert messages  -->
            <?php if ($this->session->flashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show pl-2 text-center" role="alert">
                    <?= $this->session->flashdata('success'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show pl-2 text-center" role="alert">
                    <?= $this->session->flashdata('error'); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>


            <div class="row border mb-4 py-3">

                <div class="col-sm-8">
                    <h5 class="text-dark text-capitalize mb-4">ARTPS Portal Last Updated : <?= $last_update ?> </h5>

                    <?= form_open(base_url("site/admin/miscellaneous/set_last_updated_portal"), 
                    array('id' => 'dateForm')); ?>

                        <div class="form-group">

                            <label for="last_update">
                                <span class="text-danger font-weight-bold">*</span>
                                <span class="text-muted font-weight-normal">Set last updated date: </span>
                            </label>

                            <div>
                                <input type="date" id="last_update"  name="last_update" value="" required>
                            </div>

                        </div>

                    </form>
                    
                </div>

                <div class="col-sm-4 d-flex justify-content-end align-items-baseline">

                    <button id="dateBtn" type="submit" class="btn btn-secondary">
                        <i class="far fa-calendar-alt mr-2"></i>
                        Update Date 
                    </button>
                    

                </div>

            </div>

            <div class="row border mb-4 py-3">
                <div class="col-sm-8">
                    <h5 class="text-dark text-capitalize mb-4">ARTPS Portal Alert </h5>

                    <?= form_open(base_url("site/admin/miscellaneous/portal_alert_action"), array('id' => 'alertForm')); ?>
                    <div class="form-group">
                        <label>
                            <span class="text-danger font-weight-bold">*</span>
                            <span class="text-muted font-weight-normal">Please specify the alert messages:</span>
                        </label>
                        <div>
                            <input name="en" type="text" value="<?= $site_alert_model->body->en ?? ''; ?>" placeholder="english" required>
                        </div>
                        <div>
                            <input name="as" type="text" value="<?= $site_alert_model->body->as ?? ''; ?>" placeholder="assamese" required>
                        </div>
                        <div>
                            <input name="bn" type="text" value="<?= $site_alert_model->body->bn ?? ''; ?>" placeholder="bengali" required>
                        </div>
                    </div>

                    <input type="hidden" name="enable" value="<?= $site_alert_model->enable ? '0' : '1' ?>">

                    </form>

                </div>
                <div class="col-sm-4 d-flex justify-content-end align-items-start">

                    <button id="alertBtn" type="submit" class="btn btn-secondary">
                        <i class="far fa-bell mr-2"></i>
                        <?= $site_alert_model->enable ? 'Disable Alert' : 'Enable Alert' ?>
                    </button>

                </div>
            </div>

        </div><!-- /.container-fluid -->
    </div><!-- Main content -->

    <!-- /.content -->
</div>


<script>
    $(document).ready(function() {

        $('#alertBtn').on('click', function(event) {
            $('#alertForm').submit();
        });

        $('#dateBtn').on('click', function(event) {
            $('#dateForm').submit();
        });

    });
</script>
