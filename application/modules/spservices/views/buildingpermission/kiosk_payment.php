
<main class="rtps-container">
    <form  method="POST" action="<?= base_url('spservices/buildingpermission/payment/submit') ?>">
    <input type="hidden" class="form-control" name="appl_ref_no" id="appl_ref_no" value="<?=$appl_ref_no?>" readonly />
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv" style="background:#E6F1FF">
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 20px; color: #fff; font-family: Roboto,sans-serif; font-weight: bold">
                Make Payment 
            </div>
            <div class="card-body" >

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
                    <input type="hidden" name="objid" value="<?=$objid?>" />
                    <div class="row form-group">
                            <div class="col-md-6">
                                <label>Service Charge<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="service_charge" id="service_charge" value="<?=$service_charge?>" readonly />
                                <?= form_error("service_charge") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Convenience Fee<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="convenience_fee" id="convenience_fee" value="<?= $convenience_fee ?>" readonly />
                                <?= form_error("convenience_fee") ?>
                            </div>
                    </div>
                    <div class="row form-group">
                            <div class="col-md-6">
                                <label>Number of printing page<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="no_printing_page" id="no_printing_page" value="<?=$no_printing_page?>"  />
                                <?= form_error("no_printing_page") ?>
                            </div>
                            
                            <div class="col-md-6">
                                <label>Number of scanning page<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="no_scanning_page" id="no_scanning_page"  value="<?=$no_scanning_page?>" />
                                <?= form_error("no_scanning_page") ?>
                            </div>
                    </div>

                  
                </fieldset>

               

               

            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                    <button class="btn btn-success" type="submit">
                        <i class="fa fa-angle-double-right"></i> Pay Now
                    </button>
                   
               
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
                </form>
</main>