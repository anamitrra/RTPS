<?php
$currentYear = date('Y');
if ($dbrow) {
    $custom_fields = isset($dbrow->custom_field_values) ? $dbrow->custom_field_values : [];
    $queriedFields = array('applicant_name');
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    // $service_name = $dbrow->service_data->service_name;
    $status = $dbrow->service_data->appl_status;
    $applicant_name = isset($dbrow->form_data->applicant_name) ? $dbrow->form_data->applicant_name : set_value("applicant_name");
    $applicant_gender = isset($dbrow->form_data->applicant_gender) ? $dbrow->form_data->applicant_gender : set_value("applicant_gender");
    $e_mail = isset($dbrow->form_data->{'e-mail'}) ? $dbrow->form_data->{'e-mail'} : set_value("e_mail");
    $fathers_name = isset($dbrow->form_data->fathers_name) ? $dbrow->form_data->fathers_name : set_value("fathers_name");
    $fathers_name_guardians_name = isset($dbrow->form_data->fathers_name__guardians_name) ? $dbrow->form_data->fathers_name__guardians_name : set_value("fathers_name_guardians_name");
    $mothers_name = isset($dbrow->form_data->mothers_name) ? $dbrow->form_data->mothers_name : set_value("mothers_name");
    $date_of_birth = isset($dbrow->form_data->date_of_birth) ? $dbrow->form_data->date_of_birth : set_value("date_of_birth");
    $caste = isset($dbrow->form_data->caste) ? $dbrow->form_data->caste : set_value("caste");
    $economically_weaker_section = isset($dbrow->form_data->economically_weaker_section) ? $dbrow->form_data->economically_weaker_section : set_value("economically_weaker_section");
    $husbands_name = isset($dbrow->form_data->husbands_name) ? $dbrow->form_data->husbands_name : set_value("husbands_name");
    $religion = isset($dbrow->form_data->religion) ? $dbrow->form_data->religion : set_value("religion");
    $marital_status = isset($dbrow->form_data->marital_status) ? $dbrow->form_data->marital_status : set_value("marital_status");
    $occupation = isset($dbrow->form_data->occupation) ? $dbrow->form_data->occupation : set_value("occupation");
    $occupation_type = isset($dbrow->form_data->occupation_type) ? $dbrow->form_data->occupation_type : set_value("occupation_type");
    $whether_ex_servicemen = isset($dbrow->form_data->{'whether_ex-servicemen'}) ? $dbrow->form_data->{'whether_ex-servicemen'} : set_value("whether_ex_servicemen");
    $ex_servicemen_category = isset($dbrow->form_data->{'category_of_ex-servicemen'}) ? $dbrow->form_data->{'category_of_ex-servicemen'} : set_value("ex_servicemen_category");
    $unique_identification_type = isset($dbrow->form_data->unique_identification_type) ? $dbrow->form_data->unique_identification_type : set_value("unique_identification_type");
    $unique_identification_no = isset($dbrow->form_data->unique_identification_no) ? $dbrow->form_data->unique_identification_no : set_value("unique_identification_no");
    $prominent_identification_mark = isset($dbrow->form_data->prominent_identification_mark) ? $dbrow->form_data->prominent_identification_mark : set_value("prominent_identification_mark");
    $height_in_cm = isset($dbrow->form_data->height__in_cm) ? $dbrow->form_data->height__in_cm : set_value("height_in_cm");
    $weight_kgs = isset($dbrow->form_data->weight__kgs) ? $dbrow->form_data->weight__kgs : set_value("weight_kgs");
    $eye_sight = isset($dbrow->form_data->eye_sight) ? $dbrow->form_data->eye_sight : set_value("eye_sight");
    $chest_inch = isset($dbrow->form_data->chest__inch) ? $dbrow->form_data->chest__inch : set_value("chest_inch");
    $are_you_differently_abled_pwd = isset($dbrow->form_data->are_you_differently_abled__pwd) ? $dbrow->form_data->are_you_differently_abled__pwd : set_value("are_you_differently_abled_pwd");
    $disability_category = isset($dbrow->form_data->disability_category) ? $dbrow->form_data->disability_category : set_value("disability_category");
    $additional_disability_type = isset($dbrow->form_data->additional_disability_type) ? $dbrow->form_data->additional_disability_type : set_value("additional_disability_type");
    $disbility_percentage = isset($dbrow->form_data->disbility_percentage) ? $dbrow->form_data->disbility_percentage : set_value("disbility_percentage");
} ?>
<style type="text/css">
    body {
        overflow-x: hidden;
    }

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

<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", ".frmbtn", function() {
            let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
            $("#submission_mode").val(clickedBtn);
            if (clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if (clickedBtn === 'SAVE') {
                var msg = "Do you want to procced";
            } else if (clickedBtn === 'CLEAR') {
                var msg = "Once you Reset, All filled data will be cleared";
            } else {
                var msg = "";
            } //End of if else            
            Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    if ((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
                        $("#myfrm").submit();
                        $(".frmbtn").hide();
                    } else if (clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {} //End of if else
                }
            });
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/income/inc/querysubmit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Registration of employment seeker in Employment Exchange <br>Query Answer
                </div>
                <div class="card-body" style="padding:5px">
                    <?php if ($this->session->flashdata('fail') != null) { ?>
                        <script>
                            $(".frmbtn").show();
                        </script>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('error') != null) { ?>
                        <script>
                            $(".frmbtn").show();
                        </script>
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
                    <?php } //End of if 
                    ?>
<?php if(count($custom_fields)){
    $edit_fields = [];
    foreach($custom_fields as $val){
        if($val->field_name == 'editable_fields'){
            $edit_fields = $val->field_value;
        }
    }
    // pre($edit_fields);
} ?>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Details of the Applicant </legend>
                        <div class="row form-group">
                            <?php if (in_array('applicant_name', $edit_fields)) {?>
                            <div class="col-md-6">
                                <label>Name of the Applicant <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?= $applicant_name ?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <?php } ?>
                            <div class="col-md-6">
                                <label>Gender <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?= ($applicant_gender === "Male") ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= ($applicant_gender === "Female") ? 'selected' : '' ?>>Female</option>
                                    <option value="Others" <?= ($applicant_gender === "Others") ? 'selected' : '' ?>>Others</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>E-Mail</label>
                                <input type="email" class="form-control" name="e_mail" value="<?= $e_mail ?>" />
                                <?= form_error("e_mail") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Fathers Name <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="fathers_name" id="fathers_name" value="<?= $fathers_name ?>" maxlength="255" />
                                <?= form_error("fathers_name") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Father's Name/ Guardian's Name <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="fathers_name_guardians_name" id="fathers_name_guardians_name" value="<?= $fathers_name_guardians_name ?>" maxlength="255" />
                                <?= form_error("fathers_name_guardians_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mother Name <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mothers_name" id="mothers_name" value="<?= $mothers_name ?>" maxlength="255" />
                                <?= form_error("mothers_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Date of Birth<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control dp" name="date_of_birth" id="date_of_birth" value="<?= $date_of_birth ?>" maxlength="255" />
                                <?= form_error("date_of_birth") ?>
                            </div>
                            <div class="col-md-6">
                                <label> Caste <span class="text-danger">*</span> </label>
                                <select name="caste" class="form-control" id="caste">
                                    <option value="">Select Caste</option>
                                    <option value="General" <?= ($caste === "General") ? 'selected' : '' ?>>General</option>
                                    <option value="OBC/MOBC" <?= ($caste === "OBC/MOBC") ? 'selected' : '' ?>>OBC/MOBC</option>
                                    <option value="ST" <?= ($caste === "ST") ? 'selected' : '' ?>>ST</option>
                                    <option value="SC" <?= ($caste === "SC") ? 'selected' : '' ?>>SC</option>
                                </select>
                                <?= form_error("caste") ?>
                            </div>
                        </div>
                        <div class="row form-group ews_div" <?= ($caste === "General") ? '' : 'style="display:none"' ?>>
                            <div class="col-md-6">
                                <label>Economically Weaker Section <span class="text-danger">*</span> </label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="economically_weaker_section" id="ews_yes" value="Yes" <?= ($economically_weaker_section === "Yes") ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="ews_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="economically_weaker_section" id="ews_no" value="No" <?= ($economically_weaker_section === "No") ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="ews_no">No</label>
                                </div>
                                <?= form_error("economically_weaker_section") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label> Whether Ex-Servicemen <span class="text-danger">*</span> </label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input ex_serviceman" type="radio" name="whether_ex_servicemen" id="ex_service_yes" <?= ($whether_ex_servicemen === "Yes") ? 'checked' : '' ?> value="Yes">
                                    <label class="form-check-label" for="ex_service_yes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input ex_serviceman" type="radio" name="whether_ex_servicemen" id="ex_service_no" <?= ($whether_ex_servicemen === "No") ? 'checked' : '' ?> value="No">
                                    <label class="form-check-label" for="ex_service_no">No</label>
                                </div>
                                <?= form_error("whether_ex_servicemen") ?>
                            </div>
                            <div class="col-md-6 mt-3 exservice_category d-none">
                                <label> Category of ex-servicemen </label>
                                <select name="ex_servicemen_category" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Airforce" <?= ($ex_servicemen_category === "Airforce") ? 'selected' : '' ?>>Airforce</option>
                                    <option value="Army" <?= ($ex_servicemen_category === "Army") ? 'selected' : '' ?>>Army</option>
                                    <option value="Navy" <?= ($ex_servicemen_category === "Navy") ? 'selected' : '' ?>>Navy</option>
                                </select>
                                <?= form_error("ex_servicemen_category") ?>
                            </div>
                            <!-- </div>
                        <div class="row form-group"> -->
                            <div class="col-md-6 mt-3">
                                <label>Religion<span class="text-danger">*</span> </label>
                                <select name="religion" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Buddhism" <?= ($religion === "Buddhism") ? 'selected' : '' ?>>Buddhism</option>
                                    <option value="Christianity" <?= ($religion === "Christianity") ? 'selected' : '' ?>>Christianity</option>
                                    <option value="Hinduism" <?= ($religion === "Hinduism") ? 'selected' : '' ?>>Hinduism</option>
                                    <option value="Islam" <?= ($religion === "Islam") ? 'selected' : '' ?>>Islam</option>
                                    <option value="Jainism" <?= ($religion === "Jainism") ? 'selected' : '' ?>>Jainism</option>
                                    <option value="Sikhism" <?= ($religion === "Sikhism") ? 'selected' : '' ?>>Sikhism</option>
                                    <option value="Others/Not Specified" <?= ($religion === "Others/Not Specified") ? 'selected' : '' ?>>Others/ Not Specified</option>
                                </select>
                                <?= form_error("religion") ?>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Marital Status <span class="text-danger">*</span> </label>
                                <select name="marital_status" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Divorcee" <?= ($marital_status === "Divorcee") ? 'selected' : '' ?>>Divorcee</option>
                                    <option value="Married" <?= ($marital_status === "Married") ? 'selected' : '' ?>>Married</option>
                                    <option value="Single" <?= ($marital_status === "Single") ? 'selected' : '' ?>>Single</option>
                                    <option value="Widow" <?= ($marital_status === "Widow") ? 'selected' : '' ?>>Widow</option>
                                </select>
                                <?= form_error("marital_status") ?>
                            </div>
                            <!-- </div>
                        <div class="row form-group"> -->
                            <div class="col-md-6 mt-3">
                                <label>Occupation <span class="text-danger">*</span> </label>
                                <select name="occupation" id="occupation" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Business" <?= ($occupation === "Business") ? 'selected' : '' ?>>Business</option>
                                    <option value="Clerks" <?= ($occupation === "Clerks") ? 'selected' : '' ?>>Clerks</option>
                                    <option value="Consultant" <?= ($occupation === "Consultant") ? 'selected' : '' ?>>Consultant</option>
                                    <option value="Student" <?= ($occupation === "Student") ? 'selected' : '' ?>>Student</option>
                                    <option value="Other" <?= ($occupation === "Other") ? 'selected' : '' ?>>Other</option>
                                </select>
                                <?= form_error("occupation") ?>
                            </div>
                            <div class="col-md-6 mt-3 occupationType <?= strlen($occupation_type) ? '' : 'd-none' ?> ">
                                <label>Occupation Type <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="occupation_type" id="occupation_type" value="<?= $occupation_type ?>" maxlength="255" />
                                <?= form_error("occupation_type") ?>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Unique Identification Type</label>
                                <select name="unique_identification_type" class="form-control unique_identification_type">
                                    <option value="">Please Select</option>
                                    <option value="Driving Licence" <?= ($unique_identification_type === "Driving Licence") ? 'selected' : '' ?>>Driving Licence</option>
                                    <option value="Passport" <?= ($unique_identification_type === "Passport") ? 'selected' : '' ?>>Passport</option>
                                    <option value="Voter's Identity Card" <?= ($unique_identification_type === "Voter's Identity Card") ? 'selected' : '' ?>>Voter's Identity Card</option>
                                </select>
                                <?= form_error("unique_identification_type") ?>
                            </div>
                            <!-- </div>
                        <div class="row form-group"> -->
                            <div class="col-md-6 mt-3">
                                <label>Unique Identification No </label>
                                <input type="text" class="form-control" name="unique_identification_no" id="unique_identification_no" value="<?= $unique_identification_no ?>" />
                                <?= form_error("unique_identification_no") ?>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label>Prominent Identification Mark <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="prominent_identification_mark" id="prominent_identification_mark" value="<?= $prominent_identification_mark ?>" />
                                <?= form_error("prominent_identification_mark") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Are you Differently abled (PwD)? <span class="text-danger">*</span> </label>
                                <select name="are_you_differently_abled_pwd" class="form-control" id="are_you_differently_abled_pwd">
                                    <option value="">Please Select</option>
                                    <option value="Yes" <?= ($are_you_differently_abled_pwd === "Yes") ? 'selected' : '' ?>>Yes</option>
                                    <option value="No" <?= ($are_you_differently_abled_pwd === "No") ? 'selected' : '' ?>>No</option>
                                </select>
                                <?= form_error("are_you_differently_abled_pwd") ?>
                            </div>

                            <div class="col-md-6 disablity_cat <?= ($are_you_differently_abled_pwd === 'Yes') ? '' : 'd-none' ?>">
                                <label>Disability Category</label>
                                <select name="disability_category" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php foreach ($disability_categories as $dc) {
                                        if ($disability_category === $dc->disability_category) {
                                            echo '<option value="' . $dc->disability_category . '" selected>' . $dc->disability_category . '</option>';
                                        } else {
                                            echo '<option value="' . $dc->disability_category . '">' . $dc->disability_category . '</option>';
                                        }
                                    } ?>
                                </select>
                                <?= form_error("disability_category") ?>
                            </div>
                            <div class="col-md-6 mt-3 disablity_cat <?= ($are_you_differently_abled_pwd === 'Yes') ? '' : 'd-none' ?>">
                                <label>Additional Disability Type </label>
                                <select name="additional_disability_type" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php foreach ($disability_types as $disability_type) {
                                        if ($additional_disability_type === $disability_type->additional_disability_type) {
                                            echo '<option value="' . $disability_type->additional_disability_type . '" selected>' . $disability_type->additional_disability_type . '</option>';
                                        } else {
                                            echo '<option value="' . $disability_type->additional_disability_type . '">' . $disability_type->additional_disability_type . '</option>';
                                        }
                                    } ?>
                                </select>
                                <?= form_error("additional_disability_type") ?>
                            </div>
                            <div class="col-md-6 mt-3 disablity_cat <?= ($are_you_differently_abled_pwd === 'Yes') ? '' : 'd-none' ?>">
                                <label>Disbility Percentage </label>
                                <select name="disbility_percentage" class="form-control">
                                    <option value="" autocomplete="off">Please Select</option>
                                    <option value="1" <?= ($disbility_percentage === "Business") ? 'selected' : '' ?>>40%-60%</option>
                                    <option value="2" <?= ($disbility_percentage === "Clerks") ? 'selected' : '' ?>>61% &amp; above</option>
                                </select>
                                <?= form_error("disbility_percentage") ?>
                            </div>
                        </div>
                    </fieldset>



                    <fieldset class="border border-danger table-responsive" style="overflow:hidden">
                        <legend class="h5">Processing history</legend>
                        <table class="table table-bordered bg-white mt-0">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Date &AMP; time</th>
                                    <th>Action taken</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($dbrow->processing_history)) {
                                    foreach ($dbrow->processing_history as $key => $rows) {
                                        $query_attachment = $rows->query_attachment ?? ''; ?>
                                        <tr>
                                            <td><?= sprintf("%02d", $key + 1) ?></td>
                                            <td><?= date('d/m/Y h:i a', strtotime($this->mongo_db->getDateTime($rows->processing_time))) ?></td>
                                            <td><?= $rows->action_taken ?></td>
                                            <td><?= $rows->remarks ?></td>
                                        </tr>
                                <?php } //End of foreach()
                                } //End of if else 
                                ?>
                            </tbody>
                        </table>
                        <?php if ($status === 'QS') { ?>
                            <form id="myfrm" method="POST" action="<?= base_url('spservices/necertificate/querysubmit') ?>" enctype="multipart/form-data">
                                <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        <label>Remarks </label>
                                        <textarea class="form-control" name="query_description"></textarea>
                                        <?= form_error("query_description") ?>
                                    </div>
                                </div>
                            </form>
                        <?php } //End of if 
                        ?>
                    </fieldset>

                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <!-- <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button> -->
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Save & Next
                    </button>
                    <!-- <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button> -->
                </div>
                <!--End of .card-footer-->
            </div>
            <!--End of .card-->
        </form>
    </div>
    <!--End of .container-->
</main>