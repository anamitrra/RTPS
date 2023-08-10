<?php
if ($dbrow) {
    $obj_id = $dbrow->_id->{'$id'};
    $highest_educational_level = isset($dbrow->form_data->highest_educational_level) ? $dbrow->form_data->highest_educational_level : set_value("highest_educational_level");
    $highest_examination_passed = isset($dbrow->form_data->highest_examination_passed) ? $dbrow->form_data->highest_examination_passed : set_value("highest_examination_passed");
    $other_examination_passed = isset($dbrow->form_data->other_examination_passed) ? $dbrow->form_data->other_examination_passed : set_value("other_examination_passed");
    $education_qualification = isset($dbrow->form_data->education_qualification) ? $dbrow->form_data->education_qualification : set_value("education_qualification");
    $other_qualification_trainings_courses = isset($dbrow->form_data->other_qualification_trainings_courses) ? $dbrow->form_data->other_qualification_trainings_courses : set_value("other_qualification_trainings_courses");
    $languages_known = isset($dbrow->form_data->languages_known) ? $dbrow->form_data->languages_known : set_value("languages_known");

    $job_preference_key_skills = isset($dbrow->form_data->job_preference_key_skills) ? $dbrow->form_data->job_preference_key_skills : set_value("job_preference_key_skills");
    $exam_diploma_certificate = isset($dbrow->form_data->skill_qualification) ? $dbrow->form_data->skill_qualification : set_value("exam_diploma_certificate");
    //pre($examination_passed_list);
    $exam_passed_list = "";
    foreach($examination_passed_list as $edu_qual)
    {
        $exam_passed_list = $exam_passed_list . "<option value='" . $edu_qual->examination_passed . "' >" . $edu_qual->examination_passed . "</option>";
    }
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

    #education_qualification_tbl td {
        min-width: 180px;
    }

    #education_qualification_tbl td:last-child {
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
        var isOtherExamName = true;

        $(".dp").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '+0d',
            autoclose: true
        });
        $(document).on("click", "#add_edu_qualification", function() {
            var OtherExamNameTd = (isOtherExamName ? '' : 'd-none');
            let totRows = $('#education_qualification_tbl tr').length;
            let highestEducationLevel = $('#highest_educational_level').val();

            var trow = `<tr>
                            <td>
                                <select name="examination_passed[]" class="form-control examination_passed examination_passed_new">
                                </select>
                            </td>
                            <td class="other_examination_name ` + OtherExamNameTd + `"><input name="other_examination_name[]" class="form-control" type="text"  /></td>
                            <td><input name="major_elective_subject[]" class="form-control" type="text" onkeypress="return (event.charCode > 64 && 
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode == 44 || event.charCode == 32)"/></td>
                            <td><input name="subjects_other_subjects[]" class="form-control" type="text" onkeypress="return (event.charCode > 64 && 
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode == 44 || event.charCode == 32)"/></td>
                            <td><input name="board_university[]" class="form-control" type="text" onkeypress="return (event.charCode > 64 && 
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode == 32)"/></td>
                            <td><input name="institution_school_college[]" class="form-control" type="text" onkeypress="return (event.charCode > 64 && 
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode == 32)"/></td>
                            <td><input name="date_of_passing[]" class="form-control dp-post" type="text" /></td>
                            <td><input name="registration_no[]" class="form-control" type="text" /></td>
                            <td><input name="percentage_of_marks[]" class="form-control" type="number" min="1" max="100"/></td>
                            <td><select name="class_division[]" class="form-control" id="">
                                    <option value="">Please Select</option>
                                    <option value="1st">1st</option>
                                    <option value="2nd">2nd</option>
                                    <option value="3rd">3rd</option>
                                </select></td>
                            <td style="text-align:center"><button class="btn btn-danger delete_education_tblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            get_all_exam_list(highestEducationLevel);
            if (totRows <= 5) {
                $('#education_qualification_tbl tr:last').after(trow);
                $(".dp-post").datepicker({
                    format: 'dd-mm-yyyy',
                    endDate: '+0d',
                    autoclose: true
                });
            }
            else {
                alert('You can add maximum of 5 qualifications');
            }
        });

        function get_all_exam_list(highestEducationLevel)
        {
            examinationPassedList = '';
            $.ajax({
                url: '<?= base_url("spservices/employment_aadhaar_based/registration/get_examination_passed") ?>',
                method: 'post',
                data: {
                    highestEducation: highestEducationLevel,
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response)
                    let examPassed = '<option value ="">Please Select</option>';
                    if (response.length > 0) {
                        response.forEach((res) => {
                            examPassed += '<option value="' + res.examination_passed + '">' + res.examination_passed + '</option>';
                        })
                    }
                    // console.log(examPassed)
                    $('.examination_passed_new').html(examPassed);
                    examinationPassedList += examPassed;
                }
            });
        }

        $(document).on("click", ".delete_education_tblrow", function() {
            $(this).closest("tr").remove();
            return false;
        });

        var durationinMonth = '<option value="">Please Select</option>';
        for (var i = 1; i <= 24; i++) {
            durationinMonth += '<option value="' + i + ' Month">' + i + ' Month</option>'
        }

        $(document).on("click", "#add_other_qualification_row", function() {
            let totRows = $('#other_qualification_tbl tr').length;
            var trow = `<tr>
                            <td><input name="certificate_name[]" class="form-control" type="text" /></td>
                            <td><input name="issued_by[]" class="form-control" type="text" /></td>
                            <td>
                                <select name="duration_in_months[]" class="form-control" id="">
                                    ` + durationinMonth + `
                                </select>
                            </td>
                            <td><input name="odate_of_passing[]" class="form-control dp-post" type="text" /></td>
                            <td style="text-align:center"><button class="btn btn-danger delete_other_qualificationrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if (totRows <= 5) {
                $('#other_qualification_tbl tr:last').after(trow);
                $(".dp-post").datepicker({
                    format: 'dd-mm-yyyy',
                    endDate: '+0d',
                    autoclose: true
                });
            }
            else {
                alert('You can add maximum of 5 other qualifications');
            }
        });

        $(document).on("click", ".delete_other_qualificationrow", function() {
            $(this).closest("tr").remove();
            return false;
        });

        $(document).on("click", "#add_skill_qualification_row", function() {
            let totRows = $('#skill_qualification tr').length;
            var trow = `<tr>
                            <td><input name="exam_diploma_certificate[]" class="form-control" type="text" /></td>
                            <td><input name="sector[]" class="form-control" type="text"/></td>
                            <td><input name="course_job_role[]" class="form-control" type="text" /></td>
                            <td><input name="duration[]" class="form-control" type="text" /></td>
                            <td><input name="certificate_id[]" class="form-control" type="text" /></td>
                            <td><input name="engagement[]" class="form-control" type="text" /></td>
                            <td style="text-align:center"><button class="btn btn-danger delete_education_tblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if (totRows <= 5) {
                $('#skill_qualification tr:last').after(trow);
            }
            else {
                alert('You can add maximum of 5 skills');
            }
        });

        $(document).on("click", ".delete_skill_qualificationrow", function() {
            $(this).closest("tr").remove();
            return false;
        });

        $(document).on("click", "#add_language_tbl_row", function() {
            let totRows = $('#language_tbl tr').length;
            var trow = `<tr>
                            <td><input name="languages_known[]" class="form-control" type="text" /></td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <input type="hidden" name="read_option[]" value="">
                                    <input class="form-check-input" type="checkbox" name="read_option_check[]" onclick="$(this).parent().find('input[type=hidden]').val(this.checked ? 'Read' : '')">
                                    <label class="form-check-label" for="">Read</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="hidden" name="write_option[]" value="">
                                    <input class="form-check-input" type="checkbox" name="write_option_check[]" onclick="$(this).parent().find('input[type=hidden]').val(this.checked ? 'Write' : '')">
                                    <label class="form-check-label" for="">Write</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="hidden" name="speak_option[]" value="">
                                    <input class="form-check-input" type="checkbox" name="speak_option_check[]" onclick="$(this).parent().find('input[type=hidden]').val(this.checked ? 'Speak' : '')">
                                    <label class="form-check-label" for="">Speak</label>
                                </div>
                            </td>
                            <td style="text-align:center"><button class="btn btn-danger delete_education_tblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
            if (totRows <= 5) {
                $('#language_tbl tr:last').after(trow);
                $(".dp-post").datepicker({
                    format: 'dd-mm-yyyy',
                    endDate: '+0d',
                    autoclose: true
                });
            }
            else {
                alert('You can add maximum of 5 languages');
            }
        });

        $(document).on("click", ".delete_language_tblrow", function() {
            $(this).closest("tr").remove();
            return false;
        });

        $('#highest_educational_level').on('change', function() {
            let value = $(this).val();
            $.ajax({
                url: '<?= base_url("spservices/employment_aadhaar_based/registration/get_highest_examination") ?>',
                method: 'post',
                data: {
                    highestEducation: value,
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response)
                    let examPassed = '<option value ="">Please Select</option>';
                    if (response.length > 0) {
                        response.forEach((res) => {
                            examPassed += '<option value="' + res.highest_examination_passed + '">' + res.highest_examination_passed + '</option>';
                        })
                    }
                    $('.highest_examination_passed').html(examPassed);
                }
            })
            $('.other_examination_passed_div').addClass('d-none');
            getExaminationPassedList(value);
            if (value === '<?= base64_encode("Illiterate") ?>') {
                $('.education_qualification_div').addClass('d-none');
            } else {
                $('.education_qualification_div').removeClass('d-none');
            }
        })

        // get_examination_passed
        function getExaminationPassedList(highestEducationLevel) {
            examinationPassedList = '';
            $.ajax({
                url: '<?= base_url("spservices/employment_aadhaar_based/registration/get_examination_passed") ?>',
                method: 'post',
                data: {
                    highestEducation: highestEducationLevel,
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response)
                    let examPassed = '<option value ="">Please Select</option>';
                    if (response.length > 0) {
                        response.forEach((res) => {
                            examPassed += '<option value="' + res.examination_passed + '">' + res.examination_passed + '</option>';
                        })
                    }
                    // console.log(examPassed)
                    $('.examination_passed').html(examPassed);
                    examinationPassedList += examPassed;
                }
            })
        }

        // highest_examination_passed
        $('.highest_examination_passed').on('change', function() {
            let value = $(this).val();
            if (value === 'Other') {
                $('.other_examination_passed_div').removeClass('d-none');
            } else {
                $('.other_examination_passed_div').addClass('d-none');
            }
        })

        // other examination name
        $(document).on("change", ".examination_passed", function() {
            // $('.examination_passed').on('change', function() {
            let value = $(this).val();
            // console.log(value)
            if (value === 'Other') {
                isOtherExamName = true;
                $('.other_examination_name').removeClass('d-none')
                $('.other_examination_name_td').removeClass('d-none')
            } else {
                isOtherExamName = false;
                $('.other_examination_name').addClass('d-none')
                $('.other_examination_name_td').addClass('d-none')
            }
        })

    });

    function checkValidAlpha(event) {
        return (event.charCode > 64 && 
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode == 44 || event.charCode == 32);
        };
    function checkValidAlphaWithoutComma(event) {
        return (event.charCode > 64 && 
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode == 32);
        };
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/employment_aadhaar_based/reregistration/submit_education_details') ?>" enctype="multipart/form-data">
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

                    <h5 class="text-center mt-3 text-success"><u><strong>EDUCATION & SKILL DETAILS</strong></u></h5>
                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <legend class="h5">Education & Training Details</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Highest Educational Level <span class="text-danger">*</span></label>
                                <select name="highest_educational_level" class="form-control <?= ($type_of_re_reg=='2')?'ro':'' ?>" id="highest_educational_level" <?= ($type_of_re_reg=='2')?'readonly':'' ?>>
                                    <option value="">Please Select</option>
                                    <?php foreach ($highest_education_level as $hel) { ?>
                                        <option value='<?= base64_encode($hel->highest_educational_level) ?>' <?= ($highest_educational_level === ($hel->highest_educational_level)) ? 'selected' : '' ?>><?= $hel->highest_educational_level ?></option>
                                    <?php } ?>
                                </select>
                                <?= form_error("highest_educational_level") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Highest Examination Passed <span class="text-danger">*</span></label>
                                <select name="highest_examination_passed" class="form-control highest_examination_passed <?= ($type_of_re_reg=='2')?'ro':'' ?>" <?= ($type_of_re_reg=='2')?'readonly':'' ?>>
                                    <?php if (strlen($highest_examination_passed)) {
                                        echo "<option value='" . $highest_examination_passed . "' selected>" . $highest_examination_passed . "</option>";
                                    } else {
                                        echo '<option value="">Please Select</option>';
                                    } ?>
                                </select>
                                <?= form_error("highest_examination_passed") ?>
                            </div>
                            <div class="col-md-6 mt-2 other_examination_passed_div d-none">
                                <label>Other Examination Passed <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="other_examination_passed" value="<?= $other_examination_passed ?>">
                                <?= form_error("other_examination_passed") ?>
                            </div>
                        </div>
                            <div class="row education_qualification_div <?= ($highest_examination_passed === "Illiterate") ? 'd-none' : '' ?>">
                                <div class="col-md-12">
                                    <label>Education Qualification</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="education_qualification_tbl">
                                            <thead>
                                                <tr>
                                                    <th>Examination Passed <span class="text-danger">*</span></th>
                                                    <th class="other_examination_name_td">Other Examination Name</th>
                                                    <th>Major/ Elective Subject</th>
                                                    <th>Subjects/ Other Subjects <span class="text-danger">*</span></th>
                                                    <th>Board/ University</th>
                                                    <th>Institution/ School/ College</th>
                                                    <th>Date of Passing</th>
                                                    <th>Reg. No.</th>
                                                    <th>% of Marks <span class="text-danger">*</span></th>
                                                    <th>Class/ Division</th>
                                                    <th style="width:65px;text-align: center">#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $educationQualification = (isset($education_qualification) && is_array($education_qualification)) ? count($education_qualification) : 0;
                                                if ($educationQualification > 0) {
                                                    for ($i = 0; $i < $educationQualification; $i++) {
                                                        if ($i == 0) {
                                                            $btn = '<button class="btn btn-info '.(($type_of_re_reg=='2')?'ro':'').'" id="add_edu_qualification" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                        } else {
                                                            $btn = '<button class="btn btn-danger delete_education_tblrow '.(($type_of_re_reg=='2')?'ro':'').'" type="button"><i class="fa fa-trash-o"></i></button>';
                                                        } // End of if else 
                                                ?>
                                                        <tr>
                                                            <td>
                                                                <select name="examination_passed[]" class="form-control examination_passed <?= ($type_of_re_reg=='2')?'ro':'' ?>" <?= ($type_of_re_reg=='2')?'readonly':''?>>
                                                                    <option value="<?= $education_qualification[$i]->examination_passed ?>"><?= $education_qualification[$i]->examination_passed ?></option>
                                                                </select>
                                                                <!-- <input name="examination_passed[]" value="<?= $education_qualification[$i]->examination_passed ?>" class="form-control" type="text" /> -->
                                                            </td>
                                                            <td class="other_examination_name">
                                                                <input name="other_examination_name[]" value="<?= isset($education_qualification[$i]->other_examination_name)?$education_qualification[$i]->other_examination_name:'' ?>" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?> onKeyPress="return checkValidAlphaWithoutComma(event);"/>
                                                            </td>
                                                            <td><input name="major_elective_subject[]" value="<?= isset($education_qualification[$i]->major__elective_subject)?$education_qualification[$i]->major__elective_subject:'' ?>" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?> onKeyPress="return checkValidAlpha(event);"/></td>
                                                            <td><input name="subjects_other_subjects[]" value="<?= isset($education_qualification[$i]->subjects__other_subjects)?$education_qualification[$i]->subjects__other_subjects:'' ?>" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?> onKeyPress="return checkValidAlpha(event);"/></td>
                                                            <td><input name="board_university[]" value="<?= isset($education_qualification[$i]->board__university)?$education_qualification[$i]->board__university:'' ?>" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?> onKeyPress="return checkValidAlphaWithoutComma(event);"/></td>
                                                            <td><input name="institution_school_college[]" value="<?= isset($education_qualification[$i]->institution__school__college)?$education_qualification[$i]->institution__school__college:'' ?>" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?> onKeyPress="return checkValidAlphaWithoutComma(event);"/></td>
                                                            <td><input name="date_of_passing[]" value="<?= isset($education_qualification[$i]->date_of_passing)?$education_qualification[$i]->date_of_passing:'' ?>" class="form-control dp <?= ($type_of_re_reg=='2')?'ro':'' ?>" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                            <td><input name="registration_no[]" value="<?= isset($education_qualification[$i]->registration_no)?$education_qualification[$i]->registration_no:'' ?>" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                            <td><input name="percentage_of_marks[]" value="<?= isset($education_qualification[$i]->percentage_of_marks)?$education_qualification[$i]->percentage_of_marks:'' ?>" class="form-control" type="number" min="1" max="100" step="0.01" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                            <td>
                                                                <select name="class_division[]" class="form-control <?= ($type_of_re_reg=='2')?'ro':'' ?>" id="" <?= ($type_of_re_reg=='2')?'readonly':''?>>
                                                                    <option value="">Please Select</option>
                                                                    <option value="1st" <?= ($education_qualification[$i]->class__division == "1st") ? 'selected' : '' ?>>1st</option>
                                                                    <option value="2nd" <?= ($education_qualification[$i]->class__division == "2nd") ? 'selected' : '' ?>>2nd</option>
                                                                    <option value="3rd" <?= ($education_qualification[$i]->class__division == "3rd") ? 'selected' : '' ?>>3rd</option>
                                                                </select>
                                                            </td>
                                                            <td><?= $btn ?></td>
                                                        </tr>
                                                    <?php }
                                                } else { ?>
                                                    <tr>
                                                        <td>
                                                            <select name="examination_passed[]" class="form-control examination_passed <?= ($type_of_re_reg=='2')?'ro':'' ?>" <?= ($type_of_re_reg=='2')?'readonly':''?>>
                                                                <option value="">Please Select</option>
                                                            </select>
                                                            <!-- <input name="examination_passed[]" class="form-control" type="text" /> -->
                                                        </td>
                                                        <td class="other_examination_name d-none"><input name="other_examination_name[]" class="form-control " type="text" <?= ($type_of_re_reg=='2')?'readonly':''?> onKeyPress="return checkValidAlphaWithoutComma(event);"/></td>
                                                        <td><input name="major_elective_subject[]" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?> onKeyPress="return checkValidAlpha(event);"/></td>
                                                        <td><input name="subjects_other_subjects[]" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?> onKeyPress="return checkValidAlpha(event);"/></td>
                                                        <td><input name="board_university[]" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?> onKeyPress="return checkValidAlphaWithoutComma(event);"/></td>
                                                        <td><input name="institution_school_college[]" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?> onKeyPress="return checkValidAlphaWithoutComma(event);"/></td>
                                                        <td><input name="date_of_passing[]" class="form-control dp" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                        <td><input name="registration_no[]" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                        <td><input name="percentage_of_marks[]" class="form-control" type="number" min="1" max="100" step="0.01" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                        <td>
                                                            <select name="class_division[]" class="form-control <?= ($type_of_re_reg=='2')?'ro':'' ?>" id="" <?= ($type_of_re_reg=='2')?'readonly':''?>>
                                                                <option value="">Please Select</option>
                                                                <option value="1st">1st</option>
                                                                <option value="2nd">2nd</option>
                                                                <option value="3rd">3rd</option>
                                                            </select>
                                                        </td>
                                                        <td style="text-align:center">
                                                            <button class="btn btn-info <?= ($type_of_re_reg=='2')?'ro':'' ?>" id="add_edu_qualification" type="button">
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
                        
                    </fieldset>

                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <legend class="h5">Other Qualification/Trainings/Courses </legend>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Other Qualification/Trainings/Courses</label>
                                <table class="table table-bordered" id="other_qualification_tbl">
                                    <thead>
                                        <tr>
                                            <th>Certificate Name</th>
                                            <th>Issued By</th>
                                            <th>Duration in Months</th>
                                            <th>Date of Passing</th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $otherQualificationCourse = (isset($other_qualification_trainings_courses) && is_array($other_qualification_trainings_courses)) ? count($other_qualification_trainings_courses) : 0;

                                        if ($otherQualificationCourse > 0) {
                                            for ($i = 0; $i < $otherQualificationCourse; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info '.(($type_of_re_reg=='2')?'ro':'').'" id="add_other_qualification_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger delete_other_qualificationrow '.(($type_of_re_reg=='2')?'ro':'').'" type="button"><i class="fa fa-trash-o"></i></button>';
                                                } // End of if else 
                                        ?>
                                                <tr>
                                                    <td><input name="certificate_name[]" value="<?= $other_qualification_trainings_courses[$i]->certificate_name ?>" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                    <td><input name="issued_by[]" value="<?= $other_qualification_trainings_courses[$i]->issued_by ?>" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                    <td>
                                                        <select name="duration_in_months[]" class="form-control <?= ($type_of_re_reg=='2')?'ro':'' ?>" <?= ($type_of_re_reg=='2')?'readonly':''?>>
                                                            <option value="">Please Select</option>
                                                            <?php for ($j = 1; $j <= 24; $j++) { ?>
                                                                <option value="<?= $j ?> Month" <?= ($other_qualification_trainings_courses[$i]->duration_in_months === $j . ' Month') ? 'selected' : '' ?>><?= $j ?> Month</option>
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td><input name="odate_of_passing[]" value="<?= (strlen($other_qualification_trainings_courses[$i]->date_of_passing) == 10) ? date('d-m-Y', strtotime($other_qualification_trainings_courses[$i]->date_of_passing)) : '' ?>" class="form-control dp <?= ($type_of_re_reg=='2')?'ro':'' ?>" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                    <td><?= $btn ?></td>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td><input name="certificate_name[]" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                <td><input name="issued_by[]" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                <td>
                                                    <select name="duration_in_months[]" class="form-control <?= ($type_of_re_reg=='2')?'ro':'' ?>" <?= ($type_of_re_reg=='2')?'readonly':''?>>
                                                        <option value="">Please Select</option>
                                                        <?php for ($i = 1; $i <= 24; $i++) {
                                                            echo '<option value="' . $i . ' Month">' . $i . ' Month</option>';
                                                        } ?>
                                                    </select>
                                                </td>
                                                <td><input name="odate_of_passing[]" class="form-control dp <?= ($type_of_re_reg=='2')?'ro':'' ?>" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                <td style="text-align:center">
                                                    <button class="btn btn-info <?= ($type_of_re_reg=='2')?'ro':'' ?>" id="add_other_qualification_row" type="button">
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

                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <legend class="h5">Skill Qualification </legend>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Skill Qualification</label>
                                <table class="table table-bordered" id="skill_qualification">
                                    <thead>
                                        <tr>
                                            <th>Exam/ Diploma/ Certificate</th>
                                            <th>Sector</th>
                                            <th>Course/ Job Role</th>
                                            <th>Duration</th>
                                            <th>Certificate ID</th>
                                            <th>Engagement</th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $exam_diplomaCertificate = (isset($exam_diploma_certificate) && is_array($exam_diploma_certificate)) ? count($exam_diploma_certificate) : 0;
                                        if ($exam_diplomaCertificate > 0) {
                                            $i = 0;
                                            foreach ($exam_diploma_certificate as $skill_row) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info '.(($type_of_re_reg=='2')?'ro':'').'" id="add_skill_qualification_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger delete_skill_qualificationrow '.(($type_of_re_reg=='2')?'ro':'').'" type="button"><i class="fa fa-trash-o"></i></button>';
                                                } // End of if else 
                                        ?>

                                                <tr>
                                                    <td><input name="exam_diploma_certificate[]" value="<?= $skill_row->exam__diploma__certificate ?>" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                    <td><input name="sector[]" value="<?= $skill_row->sector ?>" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                    <td><input name="course_job_role[]" value="<?= $skill_row->course__job_role ?>" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                    <td><input name="duration[]" value="<?= $skill_row->duration ?>" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                    <td><input name="certificate_id[]" value="<?= $skill_row->certificate_id ?>" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                    <td><input name="engagement[]" value="<?= $skill_row->engagement ?>" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                    <td><?= $btn ?></td>
                                                </tr>
                                            <?php
                                            $i++;
                                         }
                                        } else { ?>
                                            <tr>
                                                <td><input name="exam_diploma_certificate[]" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                <td><input name="sector[]" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                <td><input name="course_job_role[]" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                <td><input name="duration[]" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                <td><input name="certificate_id[]" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                <td><input name="engagement[]" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                <td style="text-align:center">
                                                    <button class="btn btn-info <?= ($type_of_re_reg=='2')?'ro':'' ?>" id="add_skill_qualification_row" type="button">
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
                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <legend class="h5">Languages Known </legend>
                        <div class="row">
                            <div class="col-md-12">
                                <label>Languages Known </label>
                                <table class="table table-bordered" id="language_tbl">
                                    <thead>
                                        <tr>
                                            <th width="50%">Language</th>
                                            <th>Options</th>
                                            <th style="width:65px;text-align: center">#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $languagesKnown = (isset($languages_known) && is_array($languages_known)) ? count($languages_known) : 0;
                                        if ($languagesKnown > 0) {
                                            for ($i = 0; $i < $languagesKnown; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info '.(($type_of_re_reg=='2')?'ro':'').'" id="add_language_tbl_row" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger delete_language_tblrow '.(($type_of_re_reg=='2')?'ro':'').'" type="button"><i class="fa fa-trash-o"></i></button>';
                                                } // End of if else 
                                        ?>
                                                <tr>
                                                    <td><input name="languages_known[]" value="<?= $languages_known[$i]->language ?>" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                    <td>
                                                        <div class="form-check form-check-inline">
                                                            <input type="hidden" name="read_option[]" value="<?= ($languages_known[$i]->r_option === 'Read') ? 'Read' : '' ?>">
                                                            <input class="form-check-input <?= ($type_of_re_reg=='2')?'ro':'' ?>" type="checkbox" name="read_option_check[]" onclick="$(this).parent().find('input[type=hidden]').val(this.checked ? 'Read' : '')" value="Read" <?= ($languages_known[$i]->r_option === 'Read') ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="read">Read</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="hidden" name="write_option[]" value="<?= ($languages_known[$i]->w_option === 'Write') ? 'Write' : '' ?>" />
                                                            <input class="form-check-input <?= ($type_of_re_reg=='2')?'ro':'' ?>" type="checkbox" name="write_option_check[]" onclick="$(this).parent().find('input[type=hidden]').val(this.checked ? 'Write' : '')" value="Write" <?= ($languages_known[$i]->w_option === 'Write') ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="write">Write</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input type="hidden" name="speak_option[]" value="<?= ($languages_known[$i]->s_option === 'Speak') ? 'Speak' : '' ?>" />
                                                            <input class="form-check-input <?= ($type_of_re_reg=='2')?'ro':'' ?>" type="checkbox" name="speak_option_check[]" onclick="$(this).parent().find('input[type=hidden]').val(this.checked ? 'Speak' : '')" value="Speak" <?= ($languages_known[$i]->s_option === 'Speak') ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="speak">Speak</label>
                                                        </div>
                                                    </td>
                                                    <td><?= $btn ?></td>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td><input name="languages_known[]" class="form-control" type="text" <?= ($type_of_re_reg=='2')?'readonly':''?>/></td>
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input type="hidden" name="read_option[]" value="">
                                                        <input class="form-check-input <?= ($type_of_re_reg=='2')?'ro':'' ?>" type="checkbox" name="read_option_check[]" onclick="$(this).parent().find('input[type=hidden]').val(this.checked ? 'Read' : '')">
                                                        <label class="form-check-label" for="">Read</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                    <input type="hidden" name="write_option[]" />
                                                        <input class="form-check-input <?= ($type_of_re_reg=='2')?'ro':'' ?>" type="checkbox" name="write_option_check[]" onclick="$(this).parent().find('input[type=hidden]').val(this.checked ? 'Write' : '')">
                                                        <label class="form-check-label" for="">Write</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input type="hidden" name="speak_option[]" value="" />
                                                        <input class="form-check-input <?= ($type_of_re_reg=='2')?'ro':'' ?>" type="checkbox" name="speak_option_check[]" onclick="$(this).parent().find('input[type=hidden]').val(this.checked ? 'Speak' : '')">
                                                        <label class="form-check-label" for="">Speak</label>
                                                    </div>
                                                </td>

                                                <td style="text-align:center">
                                                    <button class="btn btn-info <?= ($type_of_re_reg=='2')?'ro':'' ?>" id="add_language_tbl_row" type="button">
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
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Job Preference/Key Skills </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Job Preference/Key Skills </label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control <?= ($type_of_re_reg=='2')?'ro':'' ?>" name="job_preference_key_skills" id="job_preference_key_skills" value="<?= $job_preference_key_skills ?>" <?= ($type_of_re_reg=='2')?'readonly':''?>/>
                                <?= form_error("job_preference_key_skills") ?>
                            </div>
                        </div>
                    </fieldset>


                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/employment-re-registration/address-details/' . $obj_id) ?>">
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