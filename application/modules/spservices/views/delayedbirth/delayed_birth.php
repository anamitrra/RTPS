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
    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email;
    $pan_no = $dbrow->form_data->pan_no;
    $aadhar_no = $dbrow->form_data->aadhar_no;
    $newborn_relation =  $dbrow->form_data->newborn_relation;
    $other_relation = $dbrow->form_data->other_relation;

    $newborn_name = $dbrow->form_data->newborn_name;
    $dob = $dbrow->form_data->dob;
    $newborn_gender = $dbrow->form_data->newborn_gender;
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $newborn_birthplace = $dbrow->form_data->newborn_birthplace;
    $hospital_name = $dbrow->form_data->hospital_name;
    $address_hospital = $dbrow->form_data->address_hospital;
    $other_placeofbirth = $dbrow->form_data->other_placeofbirth;
    $late_reason = $dbrow->form_data->late_reason;

    $state = $dbrow->form_data->state;
    $district = $dbrow->form_data->district;
    $district_id = $dbrow->form_data->district_id;
    $subdivision = $dbrow->form_data->subdivision;
    $subdivision_id = $dbrow->form_data->subdivision_id;
    $circle = $dbrow->form_data->revenuecircle;
    $circle_id = $dbrow->form_data->revenuecircle_id;
    $village = $dbrow->form_data->village;
    $pin_code = $dbrow->form_data->pin_code;

    $affidavit_type = isset($dbrow->form_data->affidavit_type) ? $dbrow->form_data->affidavit_type : '';
    $affidavit = isset($dbrow->form_data->affidavit) ? $dbrow->form_data->affidavit : '';
    $age_proof_type = isset($dbrow->form_data->age_proof_type) ? $dbrow->form_data->age_proof_type : '';
    $age_proof = isset($dbrow->form_data->age_proof) ? $dbrow->form_data->age_proof : '';
    $address_proof_type = isset($dbrow->form_data->address_proof_type) ? $dbrow->form_data->address_proof_type : '';
    $address_proof = isset($dbrow->form_data->address_proof) ? $dbrow->form_data->address_proof : '';
    $other_doc_type = isset($dbrow->form_data->other_doc_type) ? $dbrow->form_data->other_doc_type : '';
    $other_doc = isset($dbrow->form_data->other_doc) ? $dbrow->form_data->other_doc : '';
    $soft_copy_type = isset($dbrow->form_data->soft_copy_type) ? $dbrow->form_data->soft_copy_type : '';
    $soft_copy = isset($dbrow->form_data->soft_copy) ? $dbrow->form_data->soft_copy : '';
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL; //set_value("rtps_trans_id");
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $mobile = $this->session->mobile; //set_value("mobile_number");
    $email = set_value("email");
    $pan_no = set_value("pan_no");
    $aadhar_no = set_value("aadhar_no");
    $newborn_relation = set_value("newborn_relation");
    $other_relation = set_value("other_relation");

    $newborn_name = set_value("newborn_name");
    $dob = set_value("dob");
    $newborn_gender = set_value("newborn_gender");
    $father_name = set_value("father_name");
    $mother_name = set_value("mother_name");
    $newborn_birthplace = set_value("newborn_birthplace");
    $hospital_name = set_value("hospital_name");
    $address_hospital = set_value("address_hospital");
    $other_placeofbirth = set_value("other_placeofbirth");
    $late_reason = set_value("late_reason");

    $state = set_value("state");
    $district = "";
    $subdivision = "";
    $circle = "";

    $district_id = "";
    $subdivision_id = "";
    $circle_id = "";

    $village = set_value("village");
    $pin_code = set_value("pin_code");

    $age_proof_type = "";
    $age_proof = "";
    $address_proof_type = "";
    $address_proof = "";
    $affidavit_type = "";
    $affidavit = "";
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
    let newborn_birthplace = '<?= $newborn_birthplace ?>';
    let newborn_relation = '<?= $newborn_relation ?>';
    $(document).ready(function() {

        if (newborn_relation == "Other") {
            $("#other_relation").show();
        } else {
            $(".other_relation").val('');
            $("#other_relation").hide();
        }
        if (newborn_birthplace == "Hospital") {
            $("#birthplaceDetails").show();
            $("#hospitalName").show();
            $("#hospital_name").show();
            $("#address_hospital").show();
            $("#other_placeofbirth").val('');
            $("#anyOtherBirthplace").hide();
        } else if (newborn_birthplace == "House") {
            $("#birthplaceDetails").show();
            $("#hospital_name").val('');
            $("#hospitalName").hide();
            $("#address_hospital").show();
            $("#other_placeofbirth").val('');
            $("#anyOtherBirthplace").hide();
        } else if (newborn_birthplace == "Other") {
            $("#hospital_name").val('');
            $("#address_hospital").val('');
            $("#birthplaceDetails").hide();
            $("#anyOtherBirthplace").show();
        } else {
            $("#birthplaceDetails").closest("input").val('');
            $("#birthplaceDetails").hide();
            $("#anyOtherBirthplace").hide();
        }


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

        $(".dp").datepicker({
            format: 'dd/mm/yyyy',
            endDate: '-21d',
            autoclose: true,
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

        $(document).on("change", '#newborn_birthplace', function() {
            let newborn_birthplace = $('#newborn_birthplace').val();
            if (newborn_birthplace == "Hospital") {
                $("#birthplaceDetails").show();
                $("#hospitalName").show();
                $("#hospital_name").show();
                $("#address_hospital").val('');
                $("#address_hospital").show();
                $("#other_placeofbirth").val('');
                $("#anyOtherBirthplace").hide();
            } else if (newborn_birthplace == "House") {
                $("#birthplaceDetails").show();
                $("#hospital_name").val('');
                $("#hospitalName").hide();
                $("#address_hospital").val('');
                $("#address_hospital").show();
                $("#other_placeofbirth").val('');
                $("#anyOtherBirthplace").hide();
            } else if (newborn_birthplace == "Other") {
                $("#hospital_name").val('');
                $("#address_hospital").val('');
                $("#birthplaceDetails").hide();
                $("#anyOtherBirthplace").show();
            }
        });

        $(document).on("change", '#newborn_relation', function() {
            let newborn_relation = $('#newborn_relation').val(); 
            if (newborn_relation == "Other") {
                $("#other_relation").show();
            } else {
                $(".other_relation").val('');
                $("#other_relation").hide();
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/delayed-birth-registration') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input name="age_proof_type" value="<?= $age_proof_type ?>" type="hidden" />
            <input name="age_proof" value="<?= $age_proof ?>" type="hidden" />
            <input name="address_proof_type" value="<?= $address_proof_type ?>" type="hidden" />
            <input name="address_proof" value="<?= $address_proof ?>" type="hidden" />
            <input name="affidavit_type" value="<?= $affidavit_type ?>" type="hidden" />
            <input name="affidavit" value="<?= $affidavit ?>" type="hidden" />
            <?php if (!empty($other_doc_type)) { ?>
                <input name="other_doc_type" value="<?= $other_doc_type ?>" type="hidden" />
                <input name="other_doc" value="<?= $other_doc ?>" type="hidden" />
            <?php } ?>
            <input name="soft_copy_type" value="<?= $soft_copy_type ?>" type="hidden" />
            <input name="soft_copy" value="<?= $soft_copy ?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b>Application Form For Permission For Delayed Birth Registration<br>
                            ( পলমকৈ জন্ম পঞ্জীয়নৰ অনুমতিৰ বাবে আবেদন প্ৰ-পত্ৰ )<b></h4>
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
                            <li>The certificate will be delivered within 10 Days of application.</li>
                            <li>প্ৰমাণ পত্ৰ ১০ দিনৰ ভিতৰত(সাধাৰণ) প্ৰদান কৰা হ'ব</li>
                        </ol>


                        <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. Statutory charges -Nil</li>
                            <li>১. স্থায়ী মাচুল -নাই</li>
                            <li>2. Service charge -Rs. 30</li>
                            <li>২. সেৱা মাচুল -৩০ টকা</li>
                            <li>3. Printing charge (in case of any printing from PFC) -Rs. 10 Per Page</li>
                            <li>৩. ছপা খৰচ -প্ৰতি পৃষ্ঠাত ১০ টকা</li>
                            <li>4. Scanning charge (in case documents are scanned in PFC) -Rs. 5 Per page</li>
                            <li>৪. স্কেনিং খৰচ -প্ৰতি পৃষ্ঠা ৫ টকা</li>
                        </ul>

                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. All the * marked fields are mandatory and need to be filled up..</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমূহ বাধ্য়তামুলক আৰু স্থানসমূহ পুৰণ কৰিব লাগিব</li>
                            <li>2. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য।</li>
                        </ul>

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
                                <label>Mobile Number/ দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <?php if ($usser_type === "user") { ?>
                                    <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" readonly maxlength="10" />
                                <?php } else { ?>
                                    <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" maxlength="10" />
                                <?php } ?>

                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail/ ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?= $email ?>" maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Relation with New Born/ নৱজাতকৰ লগত সম্বন্ধ <span class="text-danger">*</span> </label>
                                <select name="newborn_relation" class="form-control" id="newborn_relation">
                                    <option value="">Please Select</option>
                                    <option value="Brother" <?= ($newborn_relation === "Brother") ? 'selected' : '' ?>>Brother</option>
                                    <option value="Sister" <?= ($newborn_relation === "Sister") ? 'selected' : '' ?>>Sister</option>
                                    <option value="Son" <?= ($newborn_relation === "Son") ? 'selected' : '' ?>>Son</option>
                                    <option value="Daughter" <?= ($newborn_relation === "Daughter") ? 'selected' : '' ?>>Daughter</option>
                                    <option value="Wife" <?= ($newborn_relation === "Wife") ? 'selected' : '' ?>>Wife</option>
                                    <option value="Husband" <?= ($newborn_relation === "Husband") ? 'selected' : '' ?>>Husband</option>
                                    <option value="Other" <?= ($newborn_relation === "Other") ? 'selected' : '' ?>>Other</option>
                                </select>
                                <?= form_error("newborn_relation") ?>
                            </div>
                            <div class="col-md-6">
                                <label>PAN No./ পান কাৰ্ড নং </label>
                                <input type="text" class="form-control" name="pan_no" id="pan_no" value="<?= $pan_no ?>" maxlength="10" />
                                <?= form_error("pan_no") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <!-- <div class="col-md-6">
                                <label>Aadhar No./ আধাৰ কাৰ্ড নং </label>
                                <input type="text" class="form-control" name="aadhar_no" id="aadhar_no" value="<?= $aadhar_no ?>" maxlength="12" />
                                <?= form_error("aadhar_no") ?>
                            </div> -->
                            <div class="col-md-6" id="other_relation">
                                <label>Enter Other Relation(if any)/ অন্য সম্পৰ্ক (যদি থাকে)<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control other_relation" name="other_relation" value="<?= $other_relation ?>" maxlength="255" />
                                <?= form_error("other_relation") ?>
                            </div>
                        </div>

                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">New Born Details/ নৱজাতকৰ বিৱৰন </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name of the New Born/ নৱজাতকৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="newborn_name" id="newborn_name" value="<?= $newborn_name ?>" maxlength="255" />
                                <?= form_error("newborn_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Date of Birth/ জন্মৰ তাৰিখ <span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="dob" id="dob" value="<?= $dob ?>" maxlength="10" autocomplete="off" type="text" readonly="true"/>
                                <?= form_error("dob") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>New Born Gender/ নৱজাতকৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="newborn_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?= ($newborn_gender === "Male") ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= ($newborn_gender === "Female") ? 'selected' : '' ?>>Female</option>
                                    <option value="Transgender" <?= ($newborn_gender === "Transgender") ? 'selected' : '' ?>>Transgender</option>
                                </select>
                                <?= form_error("newborn_gender") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Father's Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?= $father_name ?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mother's Name/ মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name" value="<?= $mother_name ?>" maxlength="255" />
                                <?= form_error("mother_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Place of Birth of the New Born/ নৱজাতকৰ জন্মস্থান <span class="text-danger">*</span> </label>
                                <select name="newborn_birthplace" class="form-control" id="newborn_birthplace">
                                    <option value="">Please Select</option>
                                    <option value="Hospital" <?= ($newborn_birthplace === "Hospital") ? 'selected' : '' ?>>Hospital</option>
                                    <option value="House" <?= ($newborn_birthplace === "House") ? 'selected' : '' ?>>House</option>
                                    <option value="Other" <?= ($newborn_birthplace === "Other") ? 'selected' : '' ?>>Other</option>
                                </select>
                                <?= form_error("newborn_birthplace") ?>
                            </div>
                        </div>
                        <div class="row form-group" id="birthplaceDetails">
                            <div class="col-md-6" id="hospitalName">
                                <label>Name of Hospital/ চিকিৎসালয়ৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="hospital_name" id="hospital_name" value="<?= $hospital_name ?>" maxlength="255" />
                                <?= form_error("hospital_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address of Home/Hospital/ ঘৰ/ চিকিৎসালয়ৰ ঠিকনা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="address_hospital" id="address_hospital" value="<?= $address_hospital ?>" maxlength="255" />
                                <?= form_error("address_hospital") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6" id="anyOtherBirthplace">
                                <label>Place of Birth (if any)/ জন্মস্থান (যদি আছে) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="other_placeofbirth" id="other_placeofbirth" value="<?= $other_placeofbirth ?>" maxlength="255" />
                                <?= form_error("other_placeofbirth") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Reason for Being Late/ পলম হোৱাৰ কাৰণ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="late_reason" id="late_reason" value="<?= $late_reason ?>" maxlength="255" />
                                <?= form_error("late_reason") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px;">
                        <legend class="h5">Other Details/ অন্যান্য তথ্য </legend>
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
                                <label>Select District/জিলা নিৰ্বাচন কৰক <span class="text-danger">*</span> </label>
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
                                    <option value="<?= strlen($circle) ? $circle.'/'.$circle_id : ''?>"><?= strlen($circle) ? $circle : 'Select' ?></option>
                                </select>
                                <?= form_error("revenuecircle") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Village/Town/ গাওঁ/চহৰ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="village" id="village" value="<?= $village ?>" maxlength="255" />
                                <?= form_error("village") ?>
                            </div>
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