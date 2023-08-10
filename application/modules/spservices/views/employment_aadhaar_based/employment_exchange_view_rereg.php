<?php

if ($dbrow) {
    $employment_exchange = isset($dbrow->form_data->employment_exchange) ? $dbrow->form_data->employment_exchange : set_value("employment_exchange");
    $type_of_re_reg = isset($dbrow->form_data->type_of_re_reg) && !empty($dbrow->form_data->type_of_re_reg) ? $dbrow->form_data->type_of_re_reg : '';
} else {
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
    .ro {
        pointer-events: none;
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/employment_aadhaar_based/reregistration/submit_exchange_office') ?>" enctype="multipart/form-data">
        <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />

            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Re-registration of employment seeker in Employment Exchange
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

                    <h5 class="text-center mt-3 text-success"><u><strong>EMPLOYMENT EXCHANGE OFFICE</strong></u></h5>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Employment Exchange Office </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Employment Exchange <span class="text-danger">*</span></label>
                                <select name="employment_exchange" id="employment_exchange" class="form-control <?= ($type_of_re_reg!='2')?'ro':'' ?>" <?= ($type_of_re_reg!='2')?'readonly':''?>>
                                    <option value="">Please Select</option>
                                    <?php
                                    foreach ($employment_office as $eo) { ?>
                                        <option value="<?= $eo->employment_exchange_office ?>" <?= ($eo->employment_exchange_office == $employment_exchange) ? 'selected' : '' ?>><?= $eo->employment_exchange_office ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("employment_exchange") ?>
                            </div>
                        </div>
                    </fieldset>

                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/employment-re-registration/work-experiences/' . $obj_id) ?>">
                        <i class="fa fa-angle-double-left"></i> Previous
                    </a>
                    <button class="btn btn-success frmbtn" id="SAVE" type="submit">
                        <i class="fa fa-angle-double-right"></i> Save &amp; Next
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>