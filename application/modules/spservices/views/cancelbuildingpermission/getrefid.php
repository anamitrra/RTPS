<main class="rtps-container">
    <form method="POST" action="<?= base_url('spservices/buildingpermission/registration/cancel_form') ?>">
        <div class="container my-2">
            <div class="card shadow-sm" id="printDiv" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 20px; color: #fff; font-family: Roboto,sans-serif; font-weight: bold">
                    Building Permission upto Ground+2 storied under Mukhya Mantrir Sohoj Griha Nirman Achoni
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
                    <div class="row form-group">
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4 text-center">
                            <label>Enter Application Ref No<span class="text-danger">*</span> </label>
                            <input type="text" class="form-control" name="appl_ref_no" id="appl_ref_no" />
                            <?= form_error("appl_ref_no") ?>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
                </div><!--End of .card-body -->

                <div class="card-footer text-center no-print">
                    <button class="btn btn-success" type="submit">
                        Proceed for Cancellation
                    </button>
                </div>
                <!--End of .card-footer-->
            </div><!--End of .card-->
        </div><!--End of .container-->
    </form>
</main>