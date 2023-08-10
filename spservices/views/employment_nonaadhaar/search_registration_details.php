<?php
if ($dbrow) {
    $obj_id = $dbrow->_id->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;

    $employment_exchange = isset($dbrow->form_data->employment_exchange) ? $dbrow->form_data->employment_exchange : set_value("employment_exchange");
    $registration_no = isset($dbrow->form_data->registration_no) ? $dbrow->form_data->registration_no : set_value("registration_no");
    $date_of_reg = isset($dbrow->form_data->date_of_reg) ? $dbrow->form_data->date_of_reg : set_value("date_of_reg");
    $type_of_reg = isset($dbrow->form_data->type_of_reg) ? $dbrow->form_data->type_of_reg : set_value("type_of_reg");
} else {
    $obj_id = NULL;
    $title = "New Applicant Registration";
    $appl_ref_no = NULL;//set_value("rtps_trans_id");

    $employment_exchange = set_value("employment_exchange");
    $registration_no = set_value("registration_no");
    $date_of_reg = set_value("date_of_reg");
    $type_of_reg = set_value("type_of_reg");
}
?>
<style type="text/css">
    legend {
        display: inline;
        width: auto;
    }

    ol li {
        font-size: 14px;
        font-weight: bold;
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".dp").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '+0d',
            autoclose: true
        });
    });
</script>
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/employmentnonaadhaar/reregistration/submit_search_reg_details') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Registration of employment seeker in Employment Exchange
                </div>
                <div class="card-body" style="padding:5px">
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

                    <h5 class="text-center mt-3 text-success"><u><strong>SEARCH REGISTRATION NUMBER</strong></u></h5>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Please enter Registration number and Registration Date correctly </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Employment Exchange <span class="text-danger">*</span></label>
                                <select name="employment_exchange" class="form-control" id="employment_exchange">
                                    <option value="">Please Select</option>
                                    <?php
                                    if(employment_exchange_offices){
                                    foreach ($employment_exchange_offices as $eo) { ?>
                                        <option value="<?= $eo->employment_exchange_office ?>" <?= ($eo->employment_exchange_office == $employment_exchange) ? 'selected' : '' ?>><?= $eo->employment_exchange_office ?></option>
                                    <?php } }?>
                                </select>
                                <?= form_error("employment_exchange") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Registration No <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="registration_no" id="registration_no" value="<?= $registration_no ?>" maxlength="255" />
                                <?= form_error("registration_no") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Date of Registration<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control dp" name="date_of_reg" id="date_of_reg" value="<?= $date_of_reg ?>" maxlength="255" />
                                <?= form_error("date_of_reg") ?>
                            </div>
                            <div class="col-md-6">
                                <label> Types of Re-registration <span class="text-danger">*</span> </label>
                                <select name="type_of_reg" class="form-control" id="type_of_reg">
                                    <option value="">Select Type of Re-registration</option>
                                    <option value="2" <?= ($type_of_reg === "2") ? 'selected' : '' ?>>Inter District Transfer (Permanent Address Change Documents Required)</option>
                                    <option value="3" <?= ($type_of_reg === "3") ? 'selected' : '' ?>>Application details Update/Correction</option>
                                    <option value="4" <?= ($type_of_reg === "4") ? 'selected' : '' ?>>Qualification Upgrade</option>
                                </select>
                                <?= form_error("caste") ?>
                            </div>
                        </div>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-success frmbtn" id="SAVE" type="submit">
                        <i class="fa fa-angle-double-right"></i> Click here to continue with Re-registration
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>