<?php
    $obj_id = null;
    if ($dbrow) {
        $obj_id = $dbrow->_id->{'$id'};
        $applicant_type = $dbrow->form_data->applicant_type;
        $financial_turnover = isset($dbrow->form_data->financial_turnover) ? $dbrow->form_data->financial_turnover : set_value("");
        
        $deptt_code = isset($dbrow->form_data->deptt_name) ? $dbrow->form_data->deptt_name : set_value("");
        $category = isset($dbrow->form_data->category) ? $dbrow->form_data->category : set_value("");
        $category_of_regs = isset($dbrow->form_data->category_of_regs) ? $dbrow->form_data->category_of_regs : null;
        $year = 3;
        $func_str='';
        if($deptt_code == 'PWDB')
        {
            $year = 5;
            $func_str='getvalues()';
        }
        else {
            if($category == 'Class-II') {
            $year = 2;
            }
            else {
            $year = 3;
            }
        }
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
        var cnt = '<?= $year?>';
        $(document).on("click", "#add_turnover_row", function() {
            let totRows = $('#add_turnover_tbl tr').length;
            var trow = `<tr>
            <td><input name="fin_year_turnover[]" class="form-control" type="text" /></td>
            <td><input onkeypress="return /[0-9]/i.test(event.key)" name="turnover[]" class="form-control" type="text" /></td>
            <td style="text-align:center"><button class="btn btn-danger delete_turnover_row" type="button"><i class="fa fa-trash-o"></i></button></td>
            </tr>`;
            if (totRows <= cnt) {
                $('#add_turnover_tbl tr:last').after(trow);
            } else {
                alertMsg('warning','Only '+cnt+' records allowed');
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

    function getvalues(){
        var inps = document.getElementsByName('turnover[]');
        var total = 0;
        for (var i = 0; i <inps.length; i++) {
        var inp=inps[i];
        total = total + parseInt(inp.value);
        }
        var avg = total/inps.length;
        avg = numberWithCommas(avg);
        $('#avg').show();
        $('#res').html("<span style='text-align: center;color: green'>Rs. "+avg+"</span>");
    }
    function numberWithCommas(x) {
    return x.toString().split('.')[0].length > 3 ? x.toString().substring(0,x.toString().split('.')[0].length-3).replace(/\B(?=(\d{2})+(?!\d))/g, ",") + "," + x.toString().substring(x.toString().split('.')[0].length-3): x.toString();
    }
</script>
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/registration_of_contractors/upgradation/submit_turnover_details') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input id="applicant_type" name="applicant_type" value="<?= $applicant_type ?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Upgradation of Contractors
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
                    if ($this->session->flashdata('error_valid') != null) { ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('error_valid') ?>
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
                    <?php if($category_of_regs == 'Unemployed Graduate Engineer' || $category_of_regs == 'Unemployed Diploma Engineer') { ?>
                        <legend class="h6">Financial Turnover in the last <?= $year?> years </legend>
                    <?php } else { ?>
                        <legend class="h6">Financial Turnover in the last <?= $year?> years<span class="text-danger">*</span> </legend>
                    <?php } ?>
                        <div class="row">
                            <div class="col-md-12">
                            <label><span class="text-primary">#</span> (Current Financial Year not to be included)</label>
                                <table class="table table-bordered" id="add_turnover_tbl">
                                    <thead>
                                        <tr>
                                            <th>Financial Year <?= form_error("fin_year_turnover[]") ?></th>
                                            <th>Turnover (Rs.) <?= form_error("turnover[]") ?></th>
                                            <!-- <th style="width:65px;text-align: center">#</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                            
                                    <?php
                                        $financialTurnover = (isset($financial_turnover) && is_array($financial_turnover)) ? count($financial_turnover) : 0;

                                        if ($financialTurnover > 0) {
                                            for ($i = 0; $i < $year; $i++) {
                                                // if ($i == 0) {
                                                //     $btn = '<button class="btn btn-info" id="add_turnover_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                // } else {
                                                //     $btn = '<button class="btn btn-danger delete_turnover_row" type="button"><i class="fa fa-trash-o"></i></button>';
                                                // }
                                        ?>
                                            <tr>
                                                <td><input name="fin_year_turnover[]" class="form-control" type="text" value="<?= $financial_turnover[$i]->fin_year_turnover ?? set_value('fin_year_turnover[]') ?>"/></td>
                                                <td><input onkeypress="return /[0-9]/i.test(event.key)" onkeyup="<?= $func_str?>" name="turnover[]" class="form-control" type="text" value="<?= $financial_turnover[$i]->turnover ?? set_value('turnover[]') ?>"/></td>
                                                <!-- <td style="text-align:center">
                                                </td> -->
                                            </tr>
                                            <?php }
                                        } else { 
                                            for ($i = 0; $i < $year; $i++) { ?>
                                            <tr>
                                                <td><input name="fin_year_turnover[]" class="form-control" value="<?php echo set_value('fin_year_turnover[]'); ?>" type="text" /></td>
                                                <td><input onkeypress="return /[0-9]/i.test(event.key)" onkeyup="<?= $func_str?>" name="turnover[]" class="form-control" value="<?php echo set_value('turnover[]'); ?>" type="text" /></td>
                                                <!-- <td style="text-align:center">
                                                    <button class="btn btn-info" id="add_turnover_row" type="button">
                                                        <i class="fa fa-plus-circle"></i>
                                                    </button>
                                                </td> -->
                                            </tr>
                                        <?php 
                                        }
                                    } //End of if else  
                                        ?>
                                    <tr id="avg" style="display: none;">
                                                <td><strong>Average Turnover</strong></td>
                                                <td><div id="res"></div></td>
                                                
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </fieldset>

                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/upgradation_of_contractors/machinery-section/'. $obj_id) ?>">
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
