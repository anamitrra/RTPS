<?php
    $applicant_type = $this->session->userdata('applicant_type');
    $obj_id = null;
    if ($dbrow) {
        $obj_id = $dbrow->_id->{'$id'};
        $financial_turnover = isset($dbrow->form_data->financial_turnover) ? $dbrow->form_data->financial_turnover : set_value("");
        
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

        $(document).on("click", "#add_turnover_row", function() {
            let totRows = $('#add_turnover_tbl tr').length;
            var trow = `<tr>
            <td><input name="fin_year_turnover[]" class="form-control" type="text" /></td>
            <td><input name="turnover[]" class="form-control" type="text" /></td>
            <td style="text-align:center"><button class="btn btn-danger delete_turnover_row" type="button"><i class="fa fa-trash-o"></i></button></td>
            </tr>`;
            if (totRows <= 5) {
                $('#add_turnover_tbl tr:last').after(trow);
            } else {
                alertMsg('warning','Only 5 records allowed');
            }
        });
        $(document).on("click", ".delete_turnover_row", function() {
            $(this).closest("tr").remove();
            return false;
        });

        $(".dp").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '+0d',
            autoclose: true
        });

        function alertMsg(type, msg) {
            Swal.fire({
                icon: type,
                text: msg,
            })
        }
    });
</script>
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/registration_of_contractors/renewal/submit_turnover_details') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input id="applicant_type" name="applicant_type" value="<?= $applicant_type ?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Renewal of Contractors
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

                    <h5 class="text-center mt-3 text-success"><u><strong>FINANCIAL TURNOVER DETAILS</strong></u></h5>
                    <fieldset class="border border-success deed_div" style="margin-top:10px">
                        <legend class="h6">Financial Turnover in the last 5 years </legend>
                        <div class="row">
                            <div class="col-md-12">
                            <label><span class="text-danger">*</span> (Current Financial Year not to be included)</label>
                                <table class="table table-bordered" id="add_turnover_tbl">
                                    <thead>
                                        <tr>
                                            <th>Financial Year <?= form_error("fin_year_turnover[]") ?></th>
                                            <th>Turnover (Rs.) <?= form_error("turnover[]") ?></th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            
                                        <?php
                                        $financialTurnover = (isset($financial_turnover) && is_array($financial_turnover)) ? count($financial_turnover) : 0;

                                        if ($financialTurnover > 0) {
                                            for ($i = 0; $i < $financialTurnover; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="add_turnover_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger delete_turnover_row" type="button"><i class="fa fa-trash-o"></i></button>';
                                                } // End of if else 
                                        ?>
                                            <tr>
                                                <td><input name="fin_year_turnover[]" class="form-control" type="text" value="<?= $financial_turnover[$i]->fin_year_turnover ?>"/></td>
                                                <td><input name="turnover[]" class="form-control" type="text" value="<?= $financial_turnover[$i]->turnover ?>"/></td>
                                                <td style="text-align:center">
                                                    <?= $btn?>
                                                </td>
                                            </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td><input name="fin_year_turnover[]" class="form-control" value="<?php echo set_value('fin_year_turnover[]'); ?>" type="text" /></td>
                                                <td><input name="turnover[]" class="form-control" value="<?php echo set_value('turnover[]'); ?>" type="text" /></td>
                                                <td style="text-align:center">
                                                    <button class="btn btn-info" id="add_work_row" type="button">
                                                        <i class="fa fa-plus-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } //End of if else  
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>

                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/renewal-of-contractors/machinery-section/' . $obj_id) ?>">
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