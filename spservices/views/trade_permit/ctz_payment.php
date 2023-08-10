<main class="rtps-container">
    <form method="POST" action="<?= base_url('spservices/trade_permit/Payment/submit') ?>">
        <div class="container my-2">
            <div class="card shadow-sm" id="printDiv" style="background:#E6F1FF">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Make Payment
                </div>
                <div class="card-body">

                    <?php if ($this->session->flashdata('fail') != null) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php }
                    if ($this->session->flashdata('error') != null) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php }
                    if ($this->session->flashdata('success') != null) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php } ?>



                    <fieldset class="border border-success table-responsive" style="margin-top:40px">
                        <legend class="h5">Payment Details </legend>
                        <input type="hidden" name="objid" value="<?= $objid ?>" />
                        <div class="row form-group">
                            <!-- <div class="col-md-6">
                                <label>Application Charge<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="service_charge" id="service_charge"
                                    value="<?= $application_charge ?>" readonly />
                                <?= form_error("service_charge") ?>
                            </div> -->
                            <div class="col-md-6">
                                <label>Convenience Charge<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="convenience_charge" id="convenience_charge"
                                    value="<?= $convenience_charge ?>" readonly />
                                <?= form_error("service_charge") ?>
                            </div>
                    </fieldset>
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center no-print">
                    <button class="btn btn-success" type="submit">
                        <i class="fa fa-angle-double-right"></i> Pay Now
                    </button>
                </div>
                <!--End of .card-footer-->
            </div>
            <!--End of .card-->
        </div>
        <!--End of .container-->
    </form>
</main>