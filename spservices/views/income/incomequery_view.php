<?php
$currentYear = date('Y');
//$apiServer = $this->config->item('edistrict_base_url');
$apiServer = "https://localhost/wptbcapis/"; //For testing
if ($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $service_name = $dbrow->service_data->service_name;
    $status = $dbrow->service_data->appl_status;

    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $father_name = $dbrow->form_data->father_name;
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email;
    $pan_no = $dbrow->form_data->pan_no;
    $aadhar_no = $dbrow->form_data->aadhar_no;
    $relationship = $dbrow->form_data->relationship;
    $relativeName = $dbrow->form_data->relativeName;
    $incomeSource = $dbrow->form_data->incomeSource;
    $occupation = $dbrow->form_data->occupation;
    $totalIncome = $dbrow->form_data->totalIncome;
    $relationshipStatus = $dbrow->form_data->relationshipStatus;

    $address_line1 = $dbrow->form_data->address_line1;
    $address_line2 = $dbrow->form_data->address_line2;
    $state = $dbrow->form_data->state;
    $district = $dbrow->form_data->district;
    $subdivision = $dbrow->form_data->subdivision;
    $circle = $dbrow->form_data->revenuecircle;
    $mouza = $dbrow->form_data->mouza;
    $village = $dbrow->form_data->village;
    $police_st = $dbrow->form_data->police_st;
    $post_office = $dbrow->form_data->post_office;
    $pin_code = $dbrow->form_data->pin_code;

    //ENCLOSURES DATA ---START

    $address_proof_type_frm = set_value("address_proof_type");
    $identity_proof_type_frm = set_value("identity_proof_type");
    $revenuereceipt_type_frm = set_value("revenuereceipt_type");
    $salaryslip_type_frm = set_value("salaryslip_type");
    $other_doc_type_frm = set_value("other_doc_type");
    $soft_copy_type_frm = set_value("soft_copy_type");

    $uploadedFiles = $this->session->flashdata('uploaded_files');
    $address_proof_frm = $uploadedFiles['address_proof_old'] ?? null;
    $identity_proof_frm = $uploadedFiles['identity_proof_old'] ?? null;
    $revenuereceipt_frm = $uploadedFiles['revenuereceipt_old'] ?? null;
    $salaryslip_frm = $uploadedFiles['salaryslip_old'] ?? null;
    $other_doc_frm = $uploadedFiles['other_doc_old'] ?? null;
    $soft_copy_frm = $uploadedFiles['soft_copy_old'] ?? null;

    $address_proof_type_db = $dbrow->form_data->address_proof_type ?? null;
    $identity_proof_type_db = $dbrow->form_data->identity_proof_type ?? null;
    $revenuereceipt_type_db = $dbrow->form_data->revenuereceipt_type ?? null;
    $salaryslip_type_db = $dbrow->form_data->salaryslip_type ?? null;
    $other_doc_type_db = $dbrow->form_data->other_doc_type ?? null;
    $soft_copy_type_db = $dbrow->form_data->soft_copy_type ?? null;

    $address_proof_db = $dbrow->form_data->address_proof ?? null;
    $identity_proof_db = $dbrow->form_data->identity_proof ?? null;
    $revenuereceipt_db = $dbrow->form_data->revenuereceipt ?? null;
    $salaryslip_db = $dbrow->form_data->salaryslip ?? null;
    $other_doc_db = $dbrow->form_data->other_doc ?? null;
    $soft_copy_db = $dbrow->form_data->soft_copy ?? null;

    $address_proof_type = strlen($address_proof_type_frm) ? $address_proof_type_frm : $address_proof_type_db;
    $identity_proof_type = strlen($identity_proof_type_frm) ? $identity_proof_type_frm : $identity_proof_type_db;
    $revenuereceipt_type = strlen($revenuereceipt_type_frm) ? $revenuereceipt_type_frm : $revenuereceipt_type_db;
    $salaryslip_type = strlen($salaryslip_type_frm) ? $salaryslip_type_frm : $salaryslip_type_db;
    $other_doc_type = strlen($other_doc_type_frm) ? $other_doc_type_frm : $other_doc_type_db;
    $soft_copy_type = strlen($soft_copy_type_frm) ? $soft_copy_type_frm : $soft_copy_type_db;

    $address_proof = strlen($address_proof_frm) ? $address_proof_frm : $address_proof_db;
    $identity_proof = strlen($identity_proof_frm) ? $identity_proof_frm : $identity_proof_db;
    $revenuereceipt = strlen($revenuereceipt_frm) ? $revenuereceipt_frm : $revenuereceipt_db;
    $salaryslip = strlen($salaryslip_frm) ? $salaryslip_frm : $salaryslip_db;
    $other_doc = strlen($other_doc_frm) ? $other_doc_frm : $other_doc_db;
    $soft_copy = strlen($soft_copy_frm) ? $soft_copy_frm : $soft_copy_db;

    //ENCLOSURES DATA ---END

} ?>
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

<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $.post("<?= base_url('iservices/wptbc/castecertificate/createcaptcha') ?>", function(res) {
            $("#captchadiv").html(res);
        });

        $(document).on("click", "#reloadcaptcha", function() {
            $.post("<?= base_url('iservices/wptbc/castecertificate/createcaptcha') ?>", function(res) {
                $("#captchadiv").html(res);
            });
        }); //End of onChange #reloadcaptcha

        $.getJSON("<?= $apiServer ?>district_list.php", function(data) {
            let selectOption = '';
            $.each(data.ListOfDistricts, function(key, value) {
                selectOption += '<option value="' + value.DistrictName + '">' + value.DistrictName + '</option>';
            });
            $('.dists').append(selectOption);

        });

        $(document).on("change", "#district", function() {
            let selectedVal = $(this).val();
            json_body = '{"district_name":"' + selectedVal + '"}';
            if (selectedVal.length) {
                $.getJSON("<?= $apiServer . "sub_division_list.php" ?>?jsonbody=" + json_body + "", function(data) {
                    let selectOption = '';
                    $('#subdivision').empty().append('<option value="">Please select</option>')
                    $.each(data.records, function(key, value) {
                        selectOption += '<option value="' + value.subdiv_name + '">' + value.subdiv_name + '</option>';
                    });
                    $('.subdiv').append(selectOption);
                });
            }
        });

        $(document).on("change", "#subdivision", function() {
            let selectedVal = $(this).val();
            json_body = '{"subdiv_name":"' + selectedVal + '"}';
            if (selectedVal.length) {
                $.getJSON("<?= $apiServer . "revenue_circle_list.php" ?>?jsonbody=" + json_body + "", function(data) {
                    let selectOption = '';
                    $('#revenuecircle').empty().append('<option value="">Please select</option>')
                    $.each(data.records, function(key, value) {
                        selectOption += '<option value="' + value.circle_name + '">' + value.circle_name + '</option>';
                    });
                    $('.circle').append(selectOption);
                });
            }
        });

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

        var revenueReceiptFile = parseInt(<?= strlen($revenuereceipt) ? 1 : 0 ?>);
        $("#revenuereceipt").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: revenueReceiptFile ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var idProof = parseInt(<?= strlen($identity_proof) ? 1 : 0 ?>);
        $("#identity_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: idProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var addressProof = parseInt(<?= strlen($address_proof) ? 1 : 0 ?>);
        $("#address_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: addressProof ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#salaryslip").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#other_doc").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#soft_copy").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/income/inc/querysubmit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="identity_proof_old" value="<?= $identity_proof ?>" type="hidden" />
            <input name="address_proof_old" value="<?= $address_proof ?>" type="hidden" />
            <input name="revenuereceipt_old" value="<?= $revenuereceipt ?>" type="hidden" />
            <?php if (!empty($salaryslip)) { ?>
                <input name="salaryslip_old" value="<?= $salaryslip ?>" type="hidden" />
            <?php } ?>
            <?php if (!empty($other_doc)) { ?>
                <input name="other_doc_old" value="<?= $other_doc ?>" type="hidden" />
            <?php } ?>
            <input name="soft_copy_old" value="<?= $soft_copy ?>" type="hidden" />

            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b>Application for Income Certificate<br>
                            ( আয়ৰ প্রমান পত্রৰ বাবে আবেদন ) <b></h4>
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
                    <?php }
                    if ($status === 'QS') { ?>
                        <fieldset class="border border-danger" style="margin-top:40px; margin-bottom: 2px">
                            <legend class="h5">QUERY DETAILS </legend>
                            <div class="row">
                                <div class="col-md-6" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                    <label>Official Remark: </label><?= (end($dbrow->processing_history)->remarks) ?? '' ?>
                                </div>
                                <div class="col-md-6" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                    <span style="float:right; font-size: 12px">
                                        Query time : <?= isset(end($dbrow->processing_history)->processing_time) ? format_mongo_date(end($dbrow->processing_history)->processing_time) : '' ?>
                                    </span>
                                </div>
                            </div>

                        </fieldset>
                    <?php } //End of if 
                    ?>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details/ আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?= $applicant_name ?>" maxlength="255" disabled />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Male" <?= ($applicant_gender === "Male") ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= ($applicant_gender === "Female") ? 'selected' : '' ?>>Female</option>
                                    <option value="Transgender" <?= ($applicant_gender === "Transgender") ? 'selected' : '' ?>>Transgender</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Father's Name/ পিতাৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?= $father_name ?>" maxlength="255" disabled />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile Number/ দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" maxlength="10" disabled />
                                <?= form_error("mobile") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <!-- <div class="col-md-6">
                                <label>Aadhar No./ আধাৰ কাৰ্ড নং </label>
                                <input type="text" class="form-control" name="aadhar_no" id="aadhar_no" value="<?= $aadhar_no ?>" maxlength="12" disabled />
                                <?= form_error("aadhar_no") ?>
                            </div> -->
                            <div class="col-md-3">
                                <label>E-Mail/ ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?= $email ?>" maxlength="100" disabled />
                                <?= form_error("email") ?>
                            </div>
                            <div class="col-md-6">
                                <label>PAN No./ পান কাৰ্ড নং </label>
                                <input type="text" class="form-control" name="pan_no" id="pan_no" value="<?= $pan_no ?>" maxlength="10" disabled />
                                <?= form_error("pan_no") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Relationship/ সম্পৰ্ক<span class="text-danger">*</span> </label>
                                <select name="relationship" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Son Of" <?= ($relationship === "Son Of") ? 'selected' : '' ?>>Son Of</option>
                                    <option value="Daughter Of" <?= ($relationship === "Daughter Of") ? 'selected' : '' ?>>Daughter Of</option>
                                    <option value="Wife Of" <?= ($relationship === "Wife Of") ? 'selected' : '' ?>>Wife Of</option>
                                    <option value="Husband Of" <?= ($relationship === "Husband Of") ? 'selected' : '' ?>>Husband Of</option>
                                </select>
                                <?= form_error("relationship") ?>
                            </div>

                            <div class="col-md-6">
                                <label>Relative's Name/ আত্মীয়ৰ নাম <span class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text p-1">
                                            <select name="relationshipStatus" disabled>
                                                <option value="">Select</option>
                                                <option value="Mr" <?= ($relationshipStatus === "Mr") ? 'selected' : '' ?>>Mr
                                                </option>
                                                <option value="Mrs" <?= ($relationshipStatus === "Mrs") ? 'selected' : '' ?>>
                                                    Mrs</option>
                                                <option value="Sri" <?= ($relationshipStatus === "Sri") ? 'selected' : '' ?>>
                                                    Sri</option>
                                                <option value="Smt" <?= ($relationshipStatus === "Smt") ? 'selected' : '' ?>>
                                                    Smt</option>
                                                <option value="Late" <?= ($relationshipStatus === "Late") ? 'selected' : '' ?>>
                                                    Late</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="relativeName" value="<?= $relativeName ?>" maxlength="100" disabled />
                                    <?= form_error("relativeName") || form_error("relationshipStatus") ?>
                                </div>

                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Source of Income/ উপাৰ্জনৰ উৎস<span class="text-danger">*</span> </label>
                                <select name="incomeSource" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Cultivation" <?= ($incomeSource === "Cultivation") ? 'selected' : '' ?>>Cultivation</option>
                                    <option value="Labour" <?= ($incomeSource === "Labour") ? 'selected' : '' ?>>Labour</option>
                                    <option value="Business" <?= ($incomeSource === "Business") ? 'selected' : '' ?>>Business</option>
                                    <option value="Service & Pension" <?= ($incomeSource === "Service & Pension") ? 'selected' : '' ?>>Service & Pension</option>
                                </select>
                                <?= form_error("incomeSource") ?>
                            </div>

                            <div class="col-md-6">
                                <label>Occupation/ জীৱিকা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="occupation" value="<?= $occupation ?>" maxlength="100" disabled />
                                <?= form_error("occupation") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Total Annual Income/ মুঠ বাৰ্ষিক উপাৰ্জন<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="totalIncome" value="<?= $totalIncome ?>" maxlength="100" disabled />
                                <?= form_error("totalIncome") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address/ স্থায়ী ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Address Line 1/ ঠিকনাৰ প্ৰথ্ম শাৰী <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="address_line1" value="<?= $address_line1 ?>" disabled />
                                <?= form_error("address_line1") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address Line 2/ ঠিকনাৰ দ্বিতীয় শাৰী </label>
                                <input type="text" class="form-control" name="address_line2" value="<?= $address_line2 ?>" disabled />
                                <?= form_error("address_line2") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>State/ ৰাজ্য <span class="text-danger">*</span> </label>
                                <select name="state" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Assam" autocomplete="off" selected="selected">Assam</option>
                                </select>
                                <?= form_error("state") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Select District/ জিলা নিৰ্বাচন কৰক <span class="text-danger">*</span> </label>
                                <select name="district" id="district" class="form-control dists" disabled>
                                    <option value="<?= $district ?>"><?= strlen($district) ? $district : 'Select' ?></option>
                                </select>
                                <?= form_error("district") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Sub Division/ মহকুমা <span class="text-danger">*</span> </label>
                                <select name="subdivision" id="subdivision" class="form-control subdiv" disabled>
                                    <option value="<?= $subdivision ?>"><?= strlen($subdivision) ? $subdivision : 'Select' ?></option>
                                </select>
                                <?= form_error("subdivision") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Circle Office/ চক্ৰ <span class="text-danger">*</span> </label>
                                <select name="revenuecircle" id="revenuecircle" class="form-control circle" disabled>
                                    <option value="<?= $circle ?>"><?= strlen($circle) ? $circle : 'Select' ?></option>
                                </select>
                                <?= form_error("revenuecircle") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mouza/ মৌজা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mouza" id="mouza" value="<?= $mouza ?>" maxlength="255" disabled />
                                <?= form_error("mouza") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Village/Town/ গাওঁ/চহৰ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="village" id="village" value="<?= $village ?>" maxlength="255" disabled />
                                <?= form_error("village") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Police Station/ থানা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="police_st" id="police_st" value="<?= $police_st ?>" maxlength="255" disabled />
                                <?= form_error("police_st") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Post Office/ ডাকঘৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="post_office" id="post_office" value="<?= $post_office ?>" maxlength="255" disabled />
                                <?= form_error("post_office") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Pin Code/ পিনক'ড (e.g 78xxxx) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pin_code" id="pin_code" value="<?= $pin_code ?>" maxlength="6" disabled />
                                <?= form_error("pin_code") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>১. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য।</li>
                        </ul>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">ATTACH ENCLOSURE(S) </legend>
                        <div class="row mt-3">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="30%">Type of Enclosure</th>
                                            <th width="30%">Enclosure Document</th>
                                            <th width="30%">File/Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Address Proof<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="address_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Address Proof" <?= ($address_proof_type === 'Address Proof') ? 'selected' : '' ?>>Address Proof</option>
                                                </select>
                                                <?= form_error("address_proof_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="address_proof" name="address_proof" type="file" />
                                                </div>
                                                <?php if (strlen($address_proof)) { ?>
                                                    <a href="<?= base_url($address_proof) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download (Old Document)
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Identity Proof<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="identity_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Identity Proof" <?= ($identity_proof_type === 'Identity Proof') ? 'selected' : '' ?>>Identity Proof</option>
                                                </select>
                                                <?= form_error("identity_proof_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="identity_proof" name="identity_proof" type="file" />
                                                </div>
                                                <?php if (strlen($identity_proof)) { ?>
                                                    <a href="<?= base_url($identity_proof) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download (Old Document)
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Land Revenue Receipt<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="revenuereceipt_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Land Revenue Receipt" <?= ($revenuereceipt_type === 'Land Revenue Receipt') ? 'selected' : '' ?>>Land Revenue Receipt</option>
                                                </select>
                                                <?= form_error("revenuereceipt_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="revenuereceipt" name="revenuereceipt" type="file" />
                                                </div>
                                                <?php if (strlen($revenuereceipt)) { ?>
                                                    <a href="<?= base_url($revenuereceipt) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download (Old Document)
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Salary Slip</td>
                                            <td>
                                                <select name="salaryslip_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Salary Slip" <?= ($salaryslip_type === 'Salary Slip') ? 'selected' : '' ?>>Salary Slip</option>
                                                </select>
                                                <?= form_error("salaryslip_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="salaryslip" name="salaryslip" type="file" />
                                                </div>
                                                <?php if (strlen($salaryslip)) { ?>
                                                    <a href="<?= base_url($salaryslip) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download (Old Document)
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Any other documents</td>
                                            <td>
                                                <select name="other_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Any other document" <?= ($other_doc_type === 'Any other document') ? 'selected' : '' ?>>Any other document</option>
                                                </select>
                                                <?= form_error("other_doc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="other_doc" name="other_doc" type="file" />
                                                </div>
                                                <?php if (strlen($other_doc)) { ?>
                                                    <a href="<?= base_url($other_doc) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download (Old Document)
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                        <?php if ($this->slug == 'userrr') { ?>
                                            <tr>
                                                <td>Soft copy of the applicant form<span class="text-danger">*</span></td>
                                                <td>
                                                    <select name="soft_copy_type" class="form-control">
                                                        <option value="">Select</option>
                                                        <option value="Soft copy of the applicant form" <?= ($soft_copy_type === 'Soft copy of the applicant form') ? 'selected' : '' ?>>Soft copy of the applicant form</option>
                                                    </select>
                                                    <?= form_error("soft_copy_type") ?>
                                                </td>
                                                <td>
                                                    <div class="file-loading">
                                                        <input id="soft_copy" name="soft_copy" type="file" />
                                                    </div>
                                                    <?php if (strlen($soft_copy)) { ?>
                                                        <a href="<?= base_url($soft_copy) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>
                                                            View/Download (Old Document)
                                                        </a>
                                                    <?php } //End of if 
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } //End of if 
                                        ?>
                                    </tbody>
                                </table>
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
                        <?php if ($status === 'QA') { ?>
                            <form id="myfrm" method="POST" action="<?= base_url('spservices/necertificate/querysubmit') ?>" enctype="multipart/form-data">
                                <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
                                <input name="rtps_trans_id" value="<?= $rtps_trans_id ?>" type="hidden" />
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