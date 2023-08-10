<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://localhost/wptbcapis/"; //For testing
// pre($dbrow);
$startYear = date('Y') - 10;
$endYear =  date('Y');
if ($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $language = $dbrow->form_data->fillUpLanguage;
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
    $district_id = $dbrow->form_data->district_id;

    $subdivision = $dbrow->form_data->subdivision;
    $subdivision_id = $dbrow->form_data->subdivision_id;

    $circle = $dbrow->form_data->revenuecircle;
    $circle_id = $dbrow->form_data->revenuecircle_id;

    $mouza = $dbrow->form_data->mouza;
    $village = $dbrow->form_data->village;
    $police_st = $dbrow->form_data->police_st;
    $post_office = $dbrow->form_data->post_office;
    $pin_code = $dbrow->form_data->pin_code;



    $address_proof_type = isset($dbrow->form_data->address_proof_type) ? $dbrow->form_data->address_proof_type : '';
    $address_proof = isset($dbrow->form_data->address_proof) ? $dbrow->form_data->address_proof : '';
    $identity_proof_type = isset($dbrow->form_data->identity_proof_type) ? $dbrow->form_data->identity_proof_type : '';
    $identity_proof = isset($dbrow->form_data->identity_proof) ? $dbrow->form_data->identity_proof : '';
    $salaryslip_type = isset($dbrow->form_data->salaryslip_type) ? $dbrow->form_data->salaryslip_type : '';
    $salaryslip = isset($dbrow->form_data->salaryslip) ? $dbrow->form_data->salaryslip : '';
    $revenuereceipt_type = isset($dbrow->form_data->revenuereceipt_type) ? $dbrow->form_data->revenuereceipt_type : '';
    $revenuereceipt = isset($dbrow->form_data->revenuereceipt) ? $dbrow->form_data->revenuereceipt : '';

    $other_doc_type = isset($dbrow->form_data->other_doc_type) ? $dbrow->form_data->other_doc_type : '';
    $other_doc = isset($dbrow->form_data->other_doc) ? $dbrow->form_data->other_doc : '';
    $soft_copy_type = isset($dbrow->form_data->soft_copy_type) ? $dbrow->form_data->soft_copy_type : '';
    $soft_copy = isset($dbrow->form_data->soft_copy) ? $dbrow->form_data->soft_copy : '';
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL; //set_value("rtps_trans_id");
    $language = set_value("language");
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $father_name = set_value("father_name");
    $mobile = $this->session->mobile; //set_value("mobile_number");
    $email = set_value("email");
    $pan_no = set_value("pan_no");
    $aadhar_no = set_value("aadhar_no");
    $relationship = set_value("relationship");
    $relativeName = set_value("relativeName");
    $incomeSource = set_value("incomeSource");
    $occupation = set_value("occupation");
    $totalIncome = set_value("totalIncome");
    $relationshipStatus = set_value("relationshipStatus");


    $address_line1 = set_value("address_line1");
    $address_line2 = set_value("address_line2");
    $state = set_value("state");
    $district = "";
    $subdivision = "";
    $circle = "";

    $district_id = "";
    $subdivision_id = "";
    $circle_id = "";

    $mouza = set_value("mouza");
    $village = set_value("village");
    $police_st = set_value("police_st");
    $post_office = set_value("post_office");
    $pin_code = set_value("pin_code");


    $identity_proof_type = "";
    $identity_proof  = "";
    $address_proof_type = "";
    $address_proof = "";
    $salaryslip_type = "";
    $salaryslip = "";
    $revenuereceipt_type = "";
    $revenuereceipt = "";
    $other_doc_type = "";
    $other_doc = "";
    $soft_copy_type = "";
    $soft_copy = "";
} //End of if else
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
                selectOption += '<option value="' + value.DistrictName + '/' + value.DistrictId + '">' + value.DistrictName + '</option>';
            });
            $('.dists').append(selectOption);

        });

        $(document).on("change", "#district", function() {
            $('#revenuecircle').empty().append('<option value="">Please select</option>')
            //let selectedVal = $(this).val();
            const myArray = $(this).val().split("/");
            let selectedVal = myArray[0];

            json_body = '{"district_name":"' + selectedVal + '"}';
            if (selectedVal.length) {
                $.getJSON("<?= $apiServer . "sub_division_list.php" ?>?jsonbody=" + json_body + "", function(data) {
                    let selectOption = '';
                    $('#subdivision').empty().append('<option value="">Please select</option>')
                    $.each(data.records, function(key, value) {
                        selectOption += '<option value="' + value.subdiv_name + '/' + value.subdiv_id + '">' + value.subdiv_name + '</option>';
                    });
                    $('.subdiv').append(selectOption);
                });
            }
        });

        $(document).on("change", "#subdivision", function() {
            $('#revenuecircle').empty().append('<option value="">Please select</option>')
            //let selectedVal = $(this).val();
            const myArray = $(this).val().split("/");
            let selectedVal = myArray[0];

            json_body = '{"subdiv_name":"' + selectedVal + '"}';
            if (selectedVal.length) {
                $.getJSON("<?= $apiServer . "revenue_circle_list.php" ?>?jsonbody=" + json_body + "", function(data) {
                    let selectOption = '';
                    $('#revenuecircle').empty().append('<option value="">Please select</option>')
                    $.each(data.records, function(key, value) {
                        selectOption += '<option value="' + value.circle_name + '/' + value.circle_id + '">' + value.circle_name + '</option>';
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/income-certificate') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="identity_proof_type" value="<?= $identity_proof_type ?>" type="hidden" />
            <input name="identity_proof" value="<?= $identity_proof ?>" type="hidden" />
            <input name="address_proof_type" value="<?= $address_proof_type ?>" type="hidden" />
            <input name="address_proof" value="<?= $address_proof ?>" type="hidden" />
            <input name="revenuereceipt_type" value="<?= $revenuereceipt_type ?>" type="hidden" />
            <input name="revenuereceipt" value="<?= $revenuereceipt ?>" type="hidden" />
            <?php if (!empty($salaryslip_type)) { ?>
                <input name="salaryslip_type" value="<?= $salaryslip_type ?>" type="hidden" />
                <input name="salaryslip" value="<?= $salaryslip ?>" type="hidden" />
            <?php } ?>
            <?php if (!empty($other_doc_type)) { ?>
                <input name="other_doc_type" value="<?= $other_doc_type ?>" type="hidden" />
                <input name="other_doc" value="<?= $other_doc ?>" type="hidden" />
            <?php } ?>
            <input name="soft_copy_type" value="<?= $soft_copy_type ?>" type="hidden" />
            <input name="soft_copy" value="<?= $soft_copy ?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b>Application for Income Certificate<br>
                            ( আয়ৰ প্রমান পত্রৰ বাবে আবেদন ) <b></h4>

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

                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px; ">Stipulated time limit for delivery/ প্ৰাপ্তিৰ‌ নিৰ্দ্দিষ্ট সময়সীমা</strong>

                        <ol style="  margin-left: 24px; margin-top: 20px">
                            <li>The certificate will be delivered within 7 Days of application.</li>
                            <li>প্ৰমাণ পত্ৰ ৭ দিনৰ ভিতৰত প্ৰদান কৰা হ'ব</li>
                        </ol>

                        <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. User/Statutory charges -Rs. 30</li> 
                            <li>১. ব্যৱহাৰকাৰী/ স্থায়ী মাচুল -৩০ টকা</li>
                            <li>2. Service charge (incase of applying from PFC/CSC) -Rs. 30</li>
                            <li>২. সেৱা মাচুল (পি.এফ.চি./চি.এছ.চি.কেন্দ্ৰৰ পৰা আবেদন কৰাৰ ক্ষেত্ৰত) -৩০ টকা</li>
                            <li>3. Printing charge (in case of any printing from PFC/CSC) -Rs. 10 Per Page</li>
                            <li>৩. ছপা খৰচ (পি.এফ.চি./চি.এছ.চি.কেন্দ্ৰৰ পৰা কোনো ধৰণৰ প্ৰিন্টিঙৰ ক্ষেত্ৰত) -প্ৰতি পৃষ্ঠাত ১০ টকা</li>
                            <li>4. Scanning charge (in case documents are scanned in PFC/CSC) -Rs. 5 Per page</li>
                            <li>৪. স্কেনিং খৰচ (যদি নথিপত্ৰসমূহ পি.এফ.চি./চি.এছ.চি. কেন্দ্ৰত স্কেন কৰা হয়) -প্ৰতি পৃষ্ঠা ৫ টকা</li>
                        </ul>

                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. All the * marked fields are mandatory and need to be filled up.</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমূহ বাধ্য়তামুলক আৰু স্থানসমূহ পুৰণ কৰিব লাগিব</li>
                            <li>2. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য।</li>
                            
                        </ul>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Language of the certificate /প্ৰমাণপত্ৰৰ ভাষা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Language/ ভাষা <span class="text-danger">*</span> </label>
                                <select name="language" Selected class="form-control">
                                    <option value="English" selected <?= ($language === "English") ? 'selected' : '' ?>>English/ ইংৰাজী</option>
                                    <option value="Assamese" <?= ($language === "Assamese") ? 'selected' : '' ?>>Assamese/ অসমীয়া</option>
                                    <option value="Bodo" <?= ($language === "Bodo") ? 'selected' : '' ?>>Bodo/ বডো</option>
                                    <option value="Bengali" <?= ($language === "Bengali") ? 'selected' : '' ?>>Bengali/ বাংলা</option>
                                </select>
                                <?= form_error("language") ?>
                            </div>
                            <div class="col-md-6">
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details/ আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?= $applicant_name ?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control">
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
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?= $father_name ?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile Number/ দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <?php if ($usser_type === "user") { ?>
                                    <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" readonly maxlength="10" />
                                <?php } else { ?>
                                    <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" maxlength="10" />
                                <?php } ?>

                                <?= form_error("mobile") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <!-- <div class="col-md-6">
                                <label>Aadhar No./ আধাৰ কাৰ্ড নং </label>
                                <input type="text" class="form-control" name="aadhar_no" id="aadhar_no" value="<?= $aadhar_no ?>" maxlength="12" />
                                <?= form_error("aadhar_no") ?>
                            </div> -->
                            <div class="col-md-6">
                                <label>E-Mail/ ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?= $email ?>" maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                            <div class="col-md-6">
                                <label>PAN No./ পান কাৰ্ড নং </label>
                                <input type="text" class="form-control" name="pan_no" id="pan_no" value="<?= $pan_no ?>" maxlength="10" />
                                <?= form_error("pan_no") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Relationship/ সম্পৰ্ক<span class="text-danger">*</span> </label>
                                <select name="relationship" class="form-control">
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
                                            <select name="relationshipStatus">
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
                                    <input type="text" class="form-control" name="relativeName" value="<?= $relativeName ?>" maxlength="100" />
                                    <?= form_error("relativeName") || form_error("relationshipStatus") ?>
                                </div>

                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Source of Income/ উপাৰ্জনৰ উৎস<span class="text-danger">*</span> </label>
                                <select name="incomeSource" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Cultivation" <?= ($incomeSource === "Cultivation") ? 'selected' : '' ?>>Cultivation</option>
                                    <option value="Labour" <?= ($incomeSource === "Labour") ? 'selected' : '' ?>>Labour</option>
                                    <option value="Business" <?= ($incomeSource === "Business") ? 'selected' : '' ?>>Business</option>
                                    <option value="Service" <?= ($incomeSource === "Service") ? 'selected' : '' ?>>Service</option>
                                    <option value="Pension" <?= ($incomeSource === "Pension") ? 'selected' : '' ?>>Pension</option>
                                </select>
                                <?= form_error("incomeSource") ?>
                            </div>

                            <div class="col-md-6">
                                <label>Occupation/ জীৱিকা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="occupation" value="<?= $occupation ?>" maxlength="100" />
                                <?= form_error("occupation") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Total Annual Income/ মুঠ বাৰ্ষিক উপাৰ্জন<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="totalIncome" value="<?= $totalIncome ?>" maxlength="100" />
                                <?= form_error("totalIncome") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address/ স্থায়ী ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Address Line 1/ ঠিকনাৰ প্ৰথ্ম শাৰী <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="address_line1" value="<?= $address_line1 ?>" />
                                <?= form_error("address_line1") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address Line 2/ ঠিকনাৰ দ্বিতীয় শাৰী </label>
                                <input type="text" class="form-control" name="address_line2" value="<?= $address_line2 ?>" />
                                <?= form_error("address_line2") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>State/ ৰাজ্য <span class="text-danger">*</span> </label>
                                <select name="state" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Assam" autocomplete="off" selected="selected">Assam</option>
                                </select>
                                <?= form_error("state") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Select District/ জিলা নিৰ্বাচন কৰক <span class="text-danger">*</span> </label>
                                <select name="district" id="district" class="form-control dists">
                                    <option value="<?= strlen($district) ? $district.'/'.$district_id : '' ?>"><?= strlen($district) ? $district : 'Select' ?></option>
                                </select>
                                <?= form_error("district") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Sub Division/ মহকুমা <span class="text-danger">*</span> </label>
                                <select name="subdivision" id="subdivision" class="form-control subdiv">
                                    <option value="<?= strlen($subdivision) ? $subdivision.'/'.$subdivision_id : '' ?>"><?= strlen($subdivision) ? $subdivision : 'Select' ?></option>
                                </select>
                                <?= form_error("subdivision") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Circle Office/ চক্ৰ <span class="text-danger">*</span> </label>
                                <select name="revenuecircle" id="revenuecircle" class="form-control circle">
                                    <option value="<?= strlen($circle) ? $circle.'/'.$circle_id : '' ?>"><?= strlen($circle) ? $circle : 'Select' ?></option>
                                </select>
                                <?= form_error("revenuecircle") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mouza/ মৌজা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mouza" id="mouza" value="<?= $mouza ?>" maxlength="255" />
                                <?= form_error("mouza") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Village/Town/ গাওঁ/চহৰ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="village" id="village" value="<?= $village ?>" maxlength="255" />
                                <?= form_error("village") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Police Station/ থানা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="police_st" id="police_st" value="<?= $police_st ?>" maxlength="255" />
                                <?= form_error("police_st") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Post Office/ ডাকঘৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="post_office" id="post_office" value="<?= $post_office ?>" maxlength="255" />
                                <?= form_error("post_office") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Pin Code/ পিনক'ড (e.g 78xxxx) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pin_code" id="pin_code" value="<?= $pin_code ?>" maxlength="6" />
                                <?= form_error("pin_code") ?>
                            </div>
                        </div>
                    </fieldset>



                    <!-- <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <span id="captchadiv"></span>
                            <button id="reloadcaptcha" class="btn btn-outline-success" type="button" style="padding:3px 10px; float: right"><i class="fa fa-refresh"></i></button>
                            <input name="inputcaptcha" class="form-control mt-1" placeholder="Enter the text as shown in above image" autocomplete="off" required type="text" maxlength="6" />
                            <?= form_error("inputcaptcha") ?>
                        </div>
                        <div class="col-md-4"></div>
                    </div> -->
                    <!-- End of .row -->

                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Save & Next
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div>
                <!--End of .card-footer-->
            </div>
            <!--End of .card-->
        </form>
    </div>
    <!--End of .container-->
</main>