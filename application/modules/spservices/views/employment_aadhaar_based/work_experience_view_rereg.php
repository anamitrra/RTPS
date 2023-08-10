<?php

if ($dbrow) {
    $functionalRoles = $functional_role_list;
    $functionalArea = $functional_area_list;
    $industrySector = $industry_sector_list;

    $json_encode_functionalroles = '';
    foreach ($functionalRoles as $functional_role) {
        $json_encode_functionalroles .= '<option value="' . $functional_role->functional_roles . '">' . $functional_role->functional_roles . '</option>';
    }

    $json_encode_industrySector = '';
    foreach ($industrySector as $indus_sec) {
        $json_encode_industrySector .= '<option value="' . $indus_sec->industry_sector . '" >' . $indus_sec->industry_sector . '</option>';
    }

    $json_encode_functionalArea = '';
    foreach ($functionalArea as $function_area) {
        $json_encode_functionalArea .= '<option value="' . $function_area->functional_area . '">' . $function_area->functional_area . '</option>';
    }

    $obj_id = $dbrow->_id->{'$id'};
    $work_experience = isset($dbrow->form_data->work_experience) ? $dbrow->form_data->work_experience : set_value("work_experience");
    $employer = isset($dbrow->form_data->employer) ? $dbrow->form_data->employer : set_value("employer");
    $nature_of_work = isset($dbrow->form_data->nature_of_work) ? $dbrow->form_data->nature_of_work : set_value("nature_of_work");
    $from = isset($dbrow->form_data->from) ? $dbrow->form_data->from : set_value("from");
    $to = isset($dbrow->form_data->to) ? $dbrow->form_data->to : set_value("to");
    $duration = isset($dbrow->form_data->duration) ? $dbrow->form_data->duration : set_value("duration");
    $highest_designation = isset($dbrow->form_data->highest_designation) ? $dbrow->form_data->highest_designation : set_value("highest_designation");
    $last_salary_drawn = isset($dbrow->form_data->last_salary_drawn) ? $dbrow->form_data->last_salary_drawn : set_value("last_salary_drawn");
    $functional_roles = isset($dbrow->form_data->functional_roles) ? $dbrow->form_data->functional_roles : set_value("functional_roles");
    $industry_sector = isset($dbrow->form_data->industry__sector) ? $dbrow->form_data->industry__sector : set_value("industry_sector");
    $functional_area = isset($dbrow->form_data->functional_area) ? $dbrow->form_data->functional_area : set_value("functional_area");
    $years = isset($dbrow->form_data->years) ? $dbrow->form_data->years : set_value("years");
    $months = isset($dbrow->form_data->months) ? $dbrow->form_data->months : set_value("months");
    $current_employment_status = isset($dbrow->form_data->current_employment_status) ? $dbrow->form_data->current_employment_status : set_value("current_employment_status");

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

    #work_experience_tbl td {
        min-width: 180px;
    }

    #work_experience_tbl td:last-child {
        min-width: 10px;
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
<script type="text/javascript">
    $(document).ready(function() {
        var isOtherFunctionRole = false;
        var isOtherIndustry = false;
        var isOtherFunctionArea = false;

        $(".dp").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '+0d',
            autoclose: true
        });
        var functionalRolesList = '<?= $json_encode_functionalroles ?>';
        var functionalAreaList = '<?= $json_encode_functionalArea ?>';
        var industrySectorList = '<?= $json_encode_industrySector ?>';

        $(document).on("click", "#add_work_experience_tbl_row", function() {
            $(".dp").datepicker({
                format: 'dd-mm-yyyy',
                endDate: '+0d',
                autoclose: true
            });
            var OtherFunctionRoleTd = (isOtherFunctionRole ? '' : 'd-none');
            var OtherIndustryTd = (isOtherIndustry ? '' : 'd-none');
            var OtherFunctionAreaTd = (isOtherFunctionArea ? '' : 'd-none');
            let totRows = $('#work_experience_tbl tr').length;
            var trow = `<tr>
                            <td><input name="employer[]" class="form-control" type="text" /></td>
                            <td>
                            <select name="nature_of_work[]" class="form-control" id="">
                                <option value="">Please Select</option>
                                <option value="Full time"> Full time</option>
                                <option value="Part time"> Part time</option>
                                <option value="Internship"> Internship</option>
                                <option value="Contractual"> Contractual</option>
                                <option value="Deputation"> Deputation</option>
                                <option value="Apprentice"> Apprentice</option>
                            </select>
                            </td>
                            <td><input name="from[]" class="form-control dp" type="text" /></td>
                            <td><input name="to[]" class="form-control dp" type="text" /></td>
                            <td><input name="duration[]" class="form-control" type="text" /></td>
                            <td><input name="highest_designation[]" class="form-control" type="text" /></td>
                            <td><input name="last_salary_drawn[]" class="form-control" type="text" /></td>
                            <td>
                            <select name="functional_roles[]" class="form-control functional_roles" id="functional_roles">
                                    <option value="">Please Select</option>
                                    ` + functionalRolesList + `
                                </select>
                            </td>
                            <td class="other_functional_roles ` + OtherFunctionRoleTd + `"><input name="other_functional_roles[]" class="form-control" type="text"  /></td>
                            <td>
                                <select name="industry_sector[]" class="form-control industry_sector" id="industry_sector">
                                    <option value="">Please Select</option>
                                    ` + industrySectorList + `
                                </select>
                            </td>
                            <td class="other_industry_sector ` + OtherIndustryTd + `"><input name="other_industry_sector[]" class="form-control" type="text"  /></td>
                            <td>
                                <select name="functional_area[]" class="form-control functional_area" id="functional_area">
                                    <option value="">Please Select</option>
                                    ` + functionalAreaList + `
                                </select>
                            </td>
                            <td class="other_functional_area ` + OtherFunctionAreaTd + `"><input name="other_functional_area[]" class="form-control" type="text" /></td>

                            <td style="text-align:center"><button class="btn btn-danger delete_work_experience_tblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if (totRows <= 5) {
                $('#work_experience_tbl tr:last').after(trow);
                $(".dp").datepicker({
                    format: 'dd-mm-yyyy',
                    endDate: '+0d',
                    autoclose: true
                });
            }
            else {
                alert('You can add maximum of 5 experiences');
            }
        });
        $(document).on("click", ".delete_work_experience_tblrow", function() {
            $(this).closest("tr").remove();
            return false;
        });
        $(document).on("change", "#functional_roles", function() {
            var value = $(this).val();
            if (value === 'Other') {
                isOtherFunctionRole = true;
                $('.other_functional_roles').removeClass('d-none')
                $('.other_functional_role_td').removeClass('d-none')
            } else {
                isOtherFunctionRole = false;
                $('.other_functional_roles').addClass('d-none')
                $('.other_functional_role_td').addClass('d-none')

            }
        })
        $(document).on("change", "#industry_sector", function() {
            var value = $(this).val();
            if (value === 'Other') {
                isOtherIndustry = true;
                $('.other_industry_sector').removeClass('d-none')
                $('.other_industry_sector_td').removeClass('d-none')

            } else {
                isOtherIndustry = false;
                $('.other_industry_sector').addClass('d-none')
                $('.other_industry_sector_td').addClass('d-none')
            }
        })
        $(document).on("change", "#functional_area", function() {
            var value = $(this).val();
            if (value === 'Other') {
                isOtherFunctionArea = true;
                $('.other_functional_area').removeClass('d-none')
                $('.other_functional_area_td').removeClass('d-none')
            } else {
                isOtherFunctionArea = false;
                $('.other_functional_area').addClass('d-none')
                $('.other_functional_area_td').addClass('d-none')
            }
        })
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/employment_aadhaar_based/reregistration/submit_work_experience') ?>" enctype="multipart/form-data">
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

                    <h5 class="text-center mt-3 text-success"><u><strong>WORK EXPERIENCE DETAILS</strong></u></h5>

                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <legend class="h5">Work Experience </legend>
                        <div class="row">

                            <div class="col-md-12">
                                <label>Work Experience </label>
                                <div class="table-responsive mb-2">
                                    <table class="table table-bordered " id="work_experience_tbl">
                                        <thead>
                                            <tr>
                                                <th>Employer</th>
                                                <th>Nature of Work</th>
                                                <th>From</th>
                                                <th>To</th>
                                                <th>Duration</th>
                                                <th>Highest Designation</th>
                                                <th>Last Salary Drawn</th>
                                                <th>Functional Roles</th>
                                                <th class="other_functional_role_td d-none">Other Functional Roles</th>
                                                <th>Industry/ Sector</th>
                                                <th class="other_industry_sector_td d-none">Other Industry/ Sector</th>
                                                <th>Functional Area</th>
                                                <th class="other_functional_area_td d-none">Other Functional Area</th>
                                                <th style="text-align: center">#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $workExperience = (isset($work_experience) && is_array($work_experience)) ? count($work_experience) : 0;
                                            if ($workExperience > 0) {
                                                for ($i = 0; $i < $workExperience; $i++) {
                                                    if ($i == 0) {
                                                        $btn = '<button class="btn btn-info '.(($type_of_re_reg!='3')?'ro':'').'" id="add_work_experience_tbl_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                    } else {
                                                        $btn = '<button class="btn btn-danger delete_work_experience_tblrow '.(($type_of_re_reg!='3')?'ro':'').'" type="button"><i class="fa fa-trash-o"></i></button>';
                                                    } // End of if else 
                                            ?>
                                                    <tr>
                                                        <td><input name="employer[]" value="<?= $work_experience[$i]->employer ?>" class="form-control" type="text" <?= ($type_of_re_reg!='3')?'readonly':''?>/></td>
                                                        <td>
                                                            <select name="nature_of_work[]" class="form-control <?= ($type_of_re_reg!='3')?'ro':'' ?>" id="" <?= ($type_of_re_reg!='3')?'readonly':''?>>
                                                                <option value="">Please Select</option>
                                                                <option value="Full time" <?= ($work_experience[$i]->nature_of_work == 'Full time') ? 'selected' : '' ?>> Full time</option>
                                                                <option value="Part time" <?= ($work_experience[$i]->nature_of_work == 'Part time') ? 'selected' : '' ?>> Part time</option>
                                                                <option value="Internship" <?= ($work_experience[$i]->nature_of_work == 'Internship') ? 'selected' : '' ?>> Internship</option>
                                                                <option value="Contractual" <?= ($work_experience[$i]->nature_of_work == 'Contractual') ? 'selected' : '' ?>> Contractual</option>
                                                                <option value="Deputation" <?= ($work_experience[$i]->nature_of_work == 'Deputation') ? 'selected' : '' ?>> Deputation</option>
                                                                <option value="Apprentice" <?= ($work_experience[$i]->nature_of_work == 'Apprentice') ? 'selected' : '' ?>> Apprentice</option>
                                                            </select>
                                                        </td>
                                                        <td><input name="from[]" value="<?= (strlen($work_experience[$i]->from) == 10) ? date('d-m-Y', strtotime($work_experience[$i]->from)) : '' ?>" class="form-control dp <?= ($type_of_re_reg!='3')?'ro':'' ?>" type="text" <?= ($type_of_re_reg!='3')?'readonly':''?>/></td>
                                                        <td><input name="to[]" value="<?= (strlen($work_experience[$i]->to) == 10) ? date('d-m-Y', strtotime($work_experience[$i]->to)) : '' ?>" class="form-control dp <?= ($type_of_re_reg!='3')?'ro':'' ?>" type="text" <?= ($type_of_re_reg!='3')?'readonly':''?>/></td>
                                                        <td><input name="duration[]" value="<?= $work_experience[$i]->duration ?>" class="form-control" type="text" <?= ($type_of_re_reg!='3')?'readonly':''?>/></td>
                                                        <td><input name="highest_designation[]" value="<?= $work_experience[$i]->highest_designation ?>" class="form-control" type="text" <?= ($type_of_re_reg!='3')?'readonly':''?>/></td>
                                                        <td><input name="last_salary_drawn[]" value="<?= $work_experience[$i]->last_salary_drawn ?>" class="form-control" type="text" <?= ($type_of_re_reg!='3')?'readonly':''?>/></td>
                                                        <td>
                                                            <select name="functional_roles[]" class="form-control <?= ($type_of_re_reg!='3')?'ro':'' ?>" id="functional_roles" <?= ($type_of_re_reg!='3')?'readonly':''?>>
                                                                <option value="">Please Select</option>
                                                                <?php foreach ($functionalRoles as $functional_role) { ?>
                                                                    <option value="<?= $functional_role->functional_roles ?>" <?= ($work_experience[$i]->functional_roles == $functional_role->functional_roles) ? 'selected' : '' ?>><?= $functional_role->functional_roles ?></option>;
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td class="other_functional_roles d-none"><input name="other_functional_roles[]" class="form-control " type="text" /></td>
                                                        <td>
                                                            <select name="industry_sector[]" class="form-control <?= ($type_of_re_reg!='3')?'ro':'' ?>" id="industry_sector" <?= ($type_of_re_reg!='3')?'readonly':''?>>
                                                                <option value="">Please Select</option>
                                                                <?php foreach ($industrySector as $indus_sec) { ?>
                                                                    <option value="<?= $indus_sec->industry_sector ?>" <?= ($work_experience[$i]->industry__sector == $indus_sec->industry_sector) ? 'selected' : '' ?>><?= $indus_sec->industry_sector ?></option>;
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td class="other_industry_sector d-none"><input name="other_industry_sector[]" class="form-control" type="text" <?= ($type_of_re_reg!='3')?'readonly':''?>/></td>
                                                        <td>
                                                            <select name="functional_area[]" class="form-control <?= ($type_of_re_reg!='3')?'ro':'' ?>" id="functional_area" <?= ($type_of_re_reg!='3')?'readonly':''?>>
                                                                <option value="">Please Select</option>
                                                                <?php foreach ($functionalArea as $function_area) { ?>
                                                                    <option value="<?= $function_area->functional_area ?>" <?= ($work_experience[$i]->functional_area == $function_area->functional_area) ? 'selected' : '' ?>><?= $function_area->functional_area ?></option>;
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td class="other_functional_area d-none"><input name="other_functional_area[]" class="form-control" type="text" <?= ($type_of_re_reg!='3')?'readonly':''?>/></td>
                                                        <td><?= $btn ?></td>
                                                    </tr>
                                                <?php }
                                            } else { ?>
                                                <tr>
                                                    <td><input name="employer[]" class="form-control" type="text" /></td>
                                                    <td>
                                                        <select name="nature_of_work[]" class="form-control <?= ($type_of_re_reg!='3')?'ro':'' ?>" id="" <?= ($type_of_re_reg!='3')?'readonly':''?>>
                                                            <option value="">Please Select</option>
                                                            <option value="Full time"> Full time</option>
                                                            <option value="Part time"> Part time</option>
                                                            <option value="Internship"> Internship</option>
                                                            <option value="Contractual"> Contractual</option>
                                                            <option value="Deputation"> Deputation</option>
                                                            <option value="Apprentice"> Apprentice</option>
                                                        </select>
                                                    </td>
                                                    <td><input name="from[]" class="form-control dp <?= ($type_of_re_reg!='3')?'ro':'' ?>" type="text" <?= ($type_of_re_reg!='3')?'readonly':''?> /></td>
                                                    <td><input name="to[]" class="form-control dp <?= ($type_of_re_reg!='3')?'ro':'' ?>" type="text" <?= ($type_of_re_reg!='3')?'readonly':''?> /></td>
                                                    <td><input name="duration[]" class="form-control" type="text" <?= ($type_of_re_reg!='3')?'readonly':''?> /></td>
                                                    <td><input name="highest_designation[]" class="form-control" type="text" <?= ($type_of_re_reg!='3')?'readonly':''?> /></td>
                                                    <td><input name="last_salary_drawn[]" class="form-control" type="text" <?= ($type_of_re_reg!='3')?'readonly':''?> /></td>
                                                    <td>
                                                        <select name="functional_roles[]" class="form-control <?= ($type_of_re_reg!='3')?'ro':'' ?>" id="functional_roles" <?= ($type_of_re_reg!='3')?'readonly':''?>>
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($functionalRoles as $functional_role) {
                                                                echo '<option value="' . $functional_role->functional_roles . '">' . $functional_role->functional_roles . '</option>';
                                                            } ?>
                                                        </select>
                                                    </td>
                                                    <td class="other_functional_roles d-none"><input name="other_functional_roles[]" class="form-control " type="text" <?= ($type_of_re_reg!='3')?'readonly':''?> /></td>
                                                    <td>
                                                        <select name="industry_sector[]" class="form-control <?= ($type_of_re_reg!='3')?'ro':'' ?>" id="industry_sector" <?= ($type_of_re_reg!='3')?'readonly':''?>>
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($industrySector as $indus_sec) {
                                                                echo '<option value="' . $indus_sec->industry_sector . '">' . $indus_sec->industry_sector . '</option>';
                                                            } ?>
                                                        </select>
                                                    </td>
                                                    <td class="other_industry_sector d-none"><input name="other_industry_sector[]" class="form-control" type="text" <?= ($type_of_re_reg!='3')?'readonly':''?> /></td>
                                                    <td>
                                                        <select name="functional_area[]" class="form-control <?= ($type_of_re_reg!='3')?'ro':'' ?>" id="functional_area" <?= ($type_of_re_reg!='3')?'readonly':''?>>
                                                            <option value="">Please Select</option>
                                                            <?php foreach ($functionalArea as $function_area) {
                                                                echo '<option value="' . $function_area->functional_area . '">' . $function_area->functional_area . '</option>';
                                                            } ?>
                                                        </select>
                                                    </td>
                                                    <td class="other_functional_area d-none"><input name="other_functional_area[]" class="form-control" type="text" /></td>
                                                    <td style="text-align:center">
                                                        <button class="btn btn-info <?= ($type_of_re_reg!='3')?'ro':'' ?>" id="add_work_experience_tbl_row" type="button">
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
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Current Employment Status <span class="text-danger">*</span></label>
                                <select name="current_employment_status" class="form-control <?= ($type_of_re_reg!='3')?'ro':'' ?>" <?= ($type_of_re_reg!='3')?'readonly':''?>>
                                    <option value="">Please Select</option>
                                    <option value="Apprentice" <?= ($current_employment_status === "Apprentice") ? 'selected' : '' ?>>Apprentice</option>
                                    <option value="Employed - Fulltime Govt." <?= ($current_employment_status === "Employed - Fulltime Govt.") ? 'selected' : '' ?>>Employed - Fulltime Govt.</option>
                                    <option value="Employed - Fulltime Private" <?= ($current_employment_status === "Employed - Fulltime Private") ? 'selected' : '' ?>>Employed - Fulltime Private</option>
                                    <option value="Employed - Fulltime on Contract" <?= ($current_employment_status === "Employed - Fulltime on Contract") ? 'selected' : '' ?>>Employed - Fulltime on Contract</option>
                                    <option value="Employed - Part time" <?= ($current_employment_status === "Employed - Part time") ? 'selected' : '' ?>>Employed - Part time</option>
                                    <option value="Employed on daily wage" <?= ($current_employment_status === "Employed on daily wage") ? 'selected' : '' ?>>Employed on daily wage</option>
                                    <option value="Self Employed" <?= ($current_employment_status === "Self Employed") ? 'selected' : '' ?>>Self Employed</option>
                                    <option value="Unemployed" <?= ($current_employment_status === "Unemployed") ? 'selected' : '' ?>>Unemployed</option>
                                </select>
                                <?= form_error("current_employment_status") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Total Work Experience </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Years</label>
                                <input type="text" class="form-control" name="years" id="years" value="<?= $years ?>" maxlength="255" <?= ($type_of_re_reg!='3')?'readonly':''?>/>
                                <?= form_error("years") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Months</label>
                                <input type="text" class="form-control" name="months" id="months" value="<?= $months ?>" maxlength="255" <?= ($type_of_re_reg!='3')?'readonly':''?>/>
                                <?= form_error("months") ?>
                            </div>
                        </div>
                    </fieldset>
                </div><!--End of .card-body -->
                <div class="card-footer text-center">
                    <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/employment-re-registration/education-skill-details/' . $obj_id) ?>">
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