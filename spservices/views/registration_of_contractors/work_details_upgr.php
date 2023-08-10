<?php
    $obj_id = null;
    if ($dbrow) {
        $obj_id = $dbrow->_id->{'$id'};
        $applicant_type = $dbrow->form_data->applicant_type;
        $ongoing_works = isset($dbrow->form_data->ongoing_works) ? $dbrow->form_data->ongoing_works : set_value("");
        $works_executed = isset($dbrow->form_data->works_executed) ? $dbrow->form_data->works_executed : set_value("");
        $quantities_of_works_executed = isset($dbrow->form_data->quantities_of_works_executed) ? $dbrow->form_data->quantities_of_works_executed : set_value("");
        $key_personnel = isset($dbrow->form_data->key_personnel) ? $dbrow->form_data->key_personnel : set_value("");

        $deptt_code = isset($dbrow->form_data->deptt_name) ? $dbrow->form_data->deptt_name : set_value("");
        $category = isset($dbrow->form_data->category) ? $dbrow->form_data->category : set_value("");
        $category_of_regs = isset($dbrow->form_data->category_of_regs) ? $dbrow->form_data->category_of_regs : null;
        $prime = '';
        if($deptt_code == 'PWDB')
        {
            $year_label = '5 years';
            $prime = 'prime';
        }
        else {
            if($category == 'Class-II') {
            $year_label = '2 years';
            }
            else {
            $year_label = '3 years';
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
    input[type="text"]
    {
        font-size:13px;
    }
    textarea 
    { 
        font-size: 13px!important; 
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
            autoclose: true
        });

        $(document).on("click", "#add_work_row", function() {
            let totRows = $('#add_work_row_tbl tr').length;
            var trow = `<tr>
            <td><textarea name="desc_work[]" class="form-control" cols="25"></textarea></td>
            <td><textarea name="place_state[]" class="form-control" cols="25"></textarea></td>
            <td><textarea name="contract_no_date[]" class="form-control" cols="25"></textarea></td>
            <td><textarea name="employer_name[]" class="form-control" cols="25"></textarea></td>
            <td><input onkeypress="return /[0-9]/i.test(event.key)" name="value_contract[]" class="form-control" type="text" /></td>
            <td><input name="wo_date[]" class="form-control dp" type="text" /></td>
            <td><input name="st_completion_date[]" class="form-control dp" type="text" /></td>
            <td><input onkeypress="return /[0-9]/i.test(event.key)" name="work_value_remaining[]" class="form-control" type="text" /></td>
            <td><input name="ant_date_completion[]" class="form-control dp" type="text" /></td>
            <td style="text-align:center"><button class="btn btn-danger delete_other_addresses_row" type="button"><i class="fa fa-trash-o"></i></button></td>
            </tr>`;
            if (totRows <= 10) {
                $('#add_work_row_tbl tr:last').after(trow);
            } else {
                alertMsg('warning','Only 10 records allowed');
            }
        });
        $(document).on("click", ".delete_other_addresses_row", function() {
            $(this).closest("tr").remove();
            return false;
        });

        $(document).on("click", "#add_completed_work_row", function() {
            let totRows = $('#add_work_com_tbl tr').length;
            var trow = `<tr>
            <td><textarea name="project_name[]" class="form-control" cols="25"></textarea></td>
            <td><textarea name="employer[]" class="form-control" cols="25"></textarea></td>
            <td><textarea name="work_description[]" class="form-control" cols="25"></textarea></td>
            <td><textarea name="con_wo_no[]" class="form-control" cols="25"></textarea></td>
            <td><input onkeypress="return /[0-9]/i.test(event.key)" name="value_contract_p[]" class="form-control" type="text" /></td>
            <td><input name="wo_date_p[]" class="form-control dp" type="text" /></td>
            <td><input name="st_completion_date_p[]" class="form-control dp" type="text" /></td>
            <td><input name="actual_completion_date[]" class="form-control dp" type="text" /></td>
            <td><textarea name="remarks_reasons[]" class="form-control" cols="25"></textarea></td>
            <td style="text-align:center"><button class="btn btn-danger delete_completed_work_row" type="button"><i class="fa fa-trash-o"></i></button></td>
            </tr>`;
            if (totRows <= 10) {
                $('#add_work_com_tbl tr:last').after(trow);
            } else {
                alertMsg('warning','Only 10 records allowed');
            }
        });
        $(document).on("click", ".delete_completed_work_row", function() {
            $(this).closest("tr").remove();
            return false;
        });

        $(document).on("click", "#add_quantities_work_row", function() {
            let totRows = $('#add_quantities_tbl tr').length;
            var trow = `<tr>
            <td><input name="work_item[]" class="form-control" type="text" /></td>
            <td><input onkeypress="return /[0-9]/i.test(event.key)" name="work_unit[]" class="form-control" type="text" /></td>
            <td>
            <input name="fin_years[]" class="form-control" type="text" />
            </td>
            <td style="text-align:center"><button class="btn btn-danger delete_quantities_work_row" type="button"><i class="fa fa-trash-o"></i></button></td>
            </tr>`;
            if (totRows <= 10) {
                $('#add_quantities_tbl tr:last').after(trow);
            } else {
                alertMsg('warning','Only 10 records allowed');
            }
        });
        $(document).on("click", ".delete_quantities_work_row", function() {
            $(this).closest("tr").remove();
            return false;
        });

        $(document).on("click", "#add_work_emp_row", function() {
            let totRows = $('#add_emp_tbl tr').length;
            var trow = `<tr>
            <td><input name="work_position[]" class="form-control" type="text" /></td>
            <td><input name="emp_name[]" class="form-control" type="text" /></td>
            <td>
            <select name="emp_qualification[]" class="form-control">
                <option value="">Please Select</option>
                <option value="Degree Holder Engineer" >Degree Holder Engineer</option>
                <option value="Diploma Holder Engineer" >Diploma Holder Engineer</option>
            </select>
            </td>
            <td><input name="total_exp[]" class="form-control" type="text" /></td>
            <td><input name="with_contractor_exp[]" class="form-control" type="text" /></td>
            <td style="text-align:center"><button class="btn btn-danger delete_emp_row" type="button"><i class="fa fa-trash-o"></i></button></td>
            </tr>`;
            if (totRows <= 5) {
                $('#add_emp_tbl tr:last').after(trow);
            } else {
                alertMsg('warning','Only 5 records allowed');
            }
        });
        $(document).on("click", ".delete_emp_row", function() {
            $(this).closest("tr").remove();
            return false;
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
    <div class="container-fluid my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/registration_of_contractors/upgradation/submit_work_details') ?>" enctype="multipart/form-data">
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

                    <h5 class="text-center mt-3 text-success"><u><strong>WORKS DETAILS</strong></u></h5>
                    <fieldset class="border border-success deed_div" style="margin-top:10px">
                        <legend class="h6">Existing commitments and ongoing works </legend>
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered small" id="add_work_row_tbl">
                                    <thead>
                                        <tr>
                                            <th>Description of work</th>
                                            <th>Place & State</th>
                                            <th>Contract No. & Date</th>
                                            <th>Employer Name</th>
                                            <th>Value of Contract</th>
                                            <th>Work Order Date</th>
                                            <th>Stipulated Completion date</th>
                                            <th>Work value remaining to be completed</th>
                                            <th>Anticipated date of completion</th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                    <?php
                                        $ongoingWorks = (isset($ongoing_works) && is_array($ongoing_works)) ? count($ongoing_works) : 0;

                                        if ($ongoingWorks > 0) {
                                            for ($i = 0; $i < $ongoingWorks; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="add_work_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger delete_other_addresses_row" type="button"><i class="fa fa-trash-o"></i></button>';
                                                } // End of if else 
                                        ?>
                                                <tr>
                                                <td><textarea name="desc_work[]" class="form-control" cols="25"><?= $ongoing_works[$i]->desc_work ?></textarea></td>
                                                <td><textarea name="place_state[]" class="form-control" cols="25"><?= $ongoing_works[$i]->place_state ?></textarea></td>
                                                <td>
                                                <textarea name="contract_no_date[]" class="form-control" cols="25"><?= $ongoing_works[$i]->contract_no_date ?></textarea>
                                                </td>
                                                <td>
                                                <textarea name="employer_name[]" class="form-control" cols="25"><?= $ongoing_works[$i]->employer_name ?></textarea></td>
                                                <td><input onkeypress="return /[0-9]/i.test(event.key)" name="value_contract[]" class="form-control" type="text" value="<?= $ongoing_works[$i]->value_contract ?>"/></td>
                                                <td><input name="wo_date[]" class="form-control dp" type="text" value="<?= $ongoing_works[$i]->wo_date ?>" autocomplete="off"/></td>
                                                <td><input name="st_completion_date[]" class="form-control dp" type="text" value="<?= $ongoing_works[$i]->st_completion_date ?>" autocomplete="off"/></td>
                                                <td><input onkeypress="return /[0-9]/i.test(event.key)" name="work_value_remaining[]" class="form-control" type="text" value="<?= $ongoing_works[$i]->work_value_remaining ?>"/></td>
                                                <td><input name="ant_date_completion[]" class="form-control dp" type="text" value="<?= $ongoing_works[$i]->ant_date_completion ?>" autocomplete="off"/></td>
                                                <td style="text-align:center">
                                                    <?= $btn?>
                                                </td>
                                            </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td><textarea name="desc_work[]" class="form-control" cols="25"></textarea></td>
                                                <td><textarea name="place_state[]" class="form-control" cols="25"></textarea></td>
                                                <td><textarea name="contract_no_date[]" class="form-control" cols="25"></textarea></td>
                                                <td><textarea name="employer_name[]" class="form-control" cols="25"></textarea></td>
                                                <td><input onkeypress="return /[0-9]/i.test(event.key)" name="value_contract[]" class="form-control" type="text" /></td>
                                                <td><input name="wo_date[]" class="form-control dp" type="text" autocomplete="off"/></td>
                                                <td><input name="st_completion_date[]" class="form-control dp" type="text" autocomplete="off"/></td>
                                                <td><input onkeypress="return /[0-9]/i.test(event.key)" name="work_value_remaining[]" class="form-control" type="text" /></td>
                                                <td><input name="ant_date_completion[]" class="form-control dp" type="text" autocomplete="off"/></td>
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

                    <fieldset class="border border-success deed_div" style="margin-top:10px">
                    <legend class="h6">Works executed as <?= $prime?> contractor in the last <?= $year_label?><?php if($deptt_code != 'WRD' && ($category_of_regs != 'Unemployed Graduate Engineer' && $category_of_regs != 'Unemployed Diploma Engineer')) { ?><span class="text-danger">*</span><?php } ?> </legend>
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <label><span class="text-primary">#</span> (Current Financial Year not to be included)</label>
                                <table class="table table-bordered small" id="add_work_com_tbl">
                                    <thead>
                                        <tr>
                                            <th>Project Name <?= form_error("project_name[]") ?></th>
                                            <th>Employer Name <?= form_error("employer[]") ?></th>
                                            <th>Description of work <?= form_error("work_description[]") ?></th>
                                            <th>Contract / Work Order No. <?= form_error("con_wo_no[]") ?></th>
                                            <th>Value of Contract <?= form_error("value_contract_p[]") ?></th>
                                            <th>Work Order Date <?= form_error("wo_date_p[]") ?></th>
                                            <th>Stipulated Completion date <?= form_error("st_completion_date_p[]") ?></th>
                                            <th>Actual Completion date <?= form_error("actual_completion_date[]") ?></th>
                                            <th>Remarks/ Reasons for delay <?= form_error("remarks_reasons[]") ?></th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            
                                        <?php
                                        $worksExecuted = (isset($works_executed) && is_array($works_executed)) ? count($works_executed) : 0;

                                        if ($worksExecuted > 0) {
                                            for ($i = 0; $i < $worksExecuted; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="add_completed_work_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger delete_completed_work_row" type="button"><i class="fa fa-trash-o"></i></button>';
                                                } // End of if else 
                                        ?>
                                            <tr>
                                                <td><textarea name="project_name[]" class="form-control" cols="25"><?= $works_executed[$i]->project_name ?></textarea></td>
                                                <td><textarea name="employer[]" class="form-control" cols="25"><?= $works_executed[$i]->employer ?></textarea></td>
                                                <td>
                                                <textarea name="work_description[]" class="form-control" cols="25"><?= $works_executed[$i]->work_description ?></textarea>
                                                </td>
                                                <td><textarea name="con_wo_no[]" class="form-control" cols="25"><?= $works_executed[$i]->con_wo_no ?></textarea></td>
                                                <td><input onkeypress="return /[0-9]/i.test(event.key)" name="value_contract_p[]" class="form-control" type="text" value="<?= $works_executed[$i]->value_contract_p ?>"/></td>
                                                <td><input name="wo_date_p[]" class="form-control dp" type="text" value="<?= $works_executed[$i]->wo_date_p ?>" autocomplete="off"/></td>
                                                <td><input name="st_completion_date_p[]" class="form-control dp" type="text" value="<?= $works_executed[$i]->st_completion_date_p ?>" autocomplete="off"/></td>
                                                <td><input name="actual_completion_date[]" class="form-control dp" type="text" value="<?= $works_executed[$i]->actual_completion_date ?>" autocomplete="off"/></td>
                                                <td><textarea name="remarks_reasons[]" class="form-control" cols="25"><?= $works_executed[$i]->remarks_reasons ?></textarea></td>
                                                <td style="text-align:center">
                                                    <?= $btn?>
                                                </td>
                                            </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td><textarea name="project_name[]" class="form-control" cols="25"><?php echo set_value('project_name[]'); ?></textarea></td>
                                                <td><textarea name="employer[]" class="form-control" cols="25"><?php echo set_value('employer[]'); ?></textarea></td>
                                                <td>
                                                <textarea name="work_description[]" class="form-control" cols="25"><?php echo set_value('work_description[]'); ?></textarea>
                                                </td>
                                                <td><textarea name="con_wo_no[]" class="form-control" cols="25"><?php echo set_value('con_wo_no[]'); ?></textarea></td>
                                                <td><input onkeypress="return /[0-9]/i.test(event.key)" name="value_contract_p[]" class="form-control" type="text" value="<?php echo set_value('value_contract_p[]'); ?>"/></td>
                                                <td><input name="wo_date_p[]" class="form-control dp" type="text" value="<?php echo set_value('wo_date_p[]'); ?>" autocomplete="off"/></td>
                                                <td><input name="st_completion_date_p[]" class="form-control dp" value="<?php echo set_value('st_completion_date_p[]'); ?>" type="text" autocomplete="off"/></td>
                                                <td><input name="actual_completion_date[]" class="form-control dp" value="<?php echo set_value('actual_completion_date[]'); ?>" type="text" autocomplete="off"/></td>
                                                <td><textarea name="remarks_reasons[]" class="form-control" cols="25"><?php echo set_value('remarks_reasons[]'); ?></textarea></td>
                                                <td style="text-align:center">
                                                    <button class="btn btn-info" id="add_completed_work_row" type="button">
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
                    <?php if($deptt_code != 'WRD') { ?>
                    <fieldset class="border border-success deed_div" style="margin-top:10px">
                    <legend class="h6">Quantities of works executed as <?= $prime?> contractor in the last <?= $year_label?></legend>
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                            <label><span class="text-primary">#</span> (Current Financial Year not to be included)</label>
                                <table class="table table-bordered" id="add_quantities_tbl">
                                    <thead>
                                        <tr>
                                            <th>Work Item</th>
                                            <th>Unit</th>
                                            <th>Financial Years</th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            
                                        <?php
                                        $quantitiesOfWorksExecuted = (isset($quantities_of_works_executed) && is_array($quantities_of_works_executed)) ? count($quantities_of_works_executed) : 0;

                                        if ($quantitiesOfWorksExecuted > 0) {
                                            for ($i = 0; $i < $quantitiesOfWorksExecuted; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="add_quantities_work_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger delete_quantities_work_row" type="button"><i class="fa fa-trash-o"></i></button>';
                                                } // End of if else 
                                        ?>
                                            <tr>
                                                <td><input name="work_item[]" class="form-control" type="text" value="<?= $quantities_of_works_executed[$i]->work_item ?>"/></td>
                                                <td><input onkeypress="return /[0-9]/i.test(event.key)" name="work_unit[]" class="form-control" type="text" value="<?= $quantities_of_works_executed[$i]->work_unit ?>"/></td>
                                                <td>
                                                <input name="fin_years[]" class="form-control" type="text" value="<?= $quantities_of_works_executed[$i]->fin_years ?>"/>
                                                </td>
                                                <td style="text-align:center">
                                                    <?= $btn?>
                                                </td>
                                            </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td><input name="work_item[]" class="form-control" value="" type="text" /></td>
                                                <td><input onkeypress="return /[0-9]/i.test(event.key)" name="work_unit[]" class="form-control" value="" type="text" /></td>
                                                <td>
                                                <input name="fin_years[]" class="form-control" value="" type="text" />
                                                </td>
                                                <td style="text-align:center">
                                                    <button class="btn btn-info" id="add_quantities_work_row" type="button">
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
                    <?php } ?>
                    <fieldset class="border border-success deed_div" style="margin-top:10px">
                        <legend class="h6">Key Personnel for works and administration <?php if($category_of_regs != 'Unemployed Graduate Engineer' && $category_of_regs != 'Unemployed Diploma Engineer') { ?><span class="text-danger">*</span><?php } ?> </legend>
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="table table-bordered" id="add_emp_tbl">
                                    <thead>
                                        <tr>
                                            <th>Work Position <?= form_error("work_position[]") ?></th>
                                            <th>Name <?= form_error("emp_name[]") ?></th>
                                            <th>Qualifications <?= form_error("emp_qualification[]") ?></th>
                                            <th>Total Experience (Years) <?= form_error("total_exp[]") ?></th>
                                            <th>Experience With Contractor (Years) <?= form_error("with_contractor_exp[]") ?></th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            
                                        <?php
                                        $keyPersonnel = (isset($key_personnel) && is_array($key_personnel)) ? count($key_personnel) : 0;

                                        if ($keyPersonnel > 0) {
                                            for ($i = 0; $i < $keyPersonnel; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="add_work_emp_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger delete_emp_row" type="button"><i class="fa fa-trash-o"></i></button>';
                                                } // End of if else 
                                        ?>
                                            <tr>
                                                <td><input name="work_position[]" class="form-control" type="text" value="<?= $key_personnel[$i]->work_position ?>"/></td>
                                                <td><input name="emp_name[]" class="form-control" type="text" value="<?= $key_personnel[$i]->emp_name ?>"/></td>
                                                <td>
                                                <select name="emp_qualification[]" class="form-control">
                                                        <option value="">Please Select</option>
                                                        <option value="Degree Holder Engineer" <?= ($key_personnel[$i]->emp_qualification === "Degree Holder Engineer") ? 'selected' : '' ?>>Degree Holder Engineer</option>
                                                        <option value="Diploma Holder Engineer" <?= ($key_personnel[$i]->emp_qualification === "Diploma Holder Engineer") ? 'selected' : '' ?>>Diploma Holder Engineer</option>
                                                </select>
                                                </td>
                                                <td><input name="total_exp[]" class="form-control" type="text" value="<?= $key_personnel[$i]->total_exp ?>"/></td>
                                                <td><input name="with_contractor_exp[]" class="form-control" type="text" value="<?= $key_personnel[$i]->with_contractor_exp ?>"/></td>
                                                <td style="text-align:center">
                                                    <?= $btn?>
                                                </td>
                                            </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td><input name="work_position[]" class="form-control" value="<?php echo set_value('work_position[]'); ?>" type="text" /></td>
                                                <td><input name="emp_name[]" class="form-control" value="<?php echo set_value('emp_name[]'); ?>" type="text" /></td>
                                                <td>
                                                <select name="emp_qualification[]" class="form-control">
                                                        <option value="">Please Select</option>
                                                        <option value="Degree Holder Engineer" >Degree Holder Engineer</option>
                                                        <option value="Diploma Holder Engineer" >Diploma Holder Engineer</option>
                                                </select>
                                                </td>
                                                <td><input name="total_exp[]" class="form-control" value="<?php echo set_value('total_exp[]'); ?>" type="text" /></td>
                                                <td><input name="with_contractor_exp[]" class="form-control" value="<?php echo set_value('with_contractor_exp[]'); ?>" type="text" /></td>
                                                <td style="text-align:center">
                                                    <button class="btn btn-info" id="add_work_emp_row" type="button">
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
                    <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/upgradation_of_contractors/address-section/'. $obj_id) ?>">
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
