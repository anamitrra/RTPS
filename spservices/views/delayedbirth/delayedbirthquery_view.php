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
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email;
    $pan_no = $dbrow->form_data->pan_no;
    $aadhar_no = $dbrow->form_data->aadhar_no;
    $newborn_relation =  $dbrow->form_data->newborn_relation;
    $other_relation = $dbrow->form_data->newborn_relation == "Other" ? $dbrow->form_data->other_relation : 'NA';

    $newborn_name = $dbrow->form_data->newborn_name;
    $dob = $dbrow->form_data->dob;
    $newborn_gender = $dbrow->form_data->newborn_gender;
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $newborn_birthplace = $dbrow->form_data->newborn_birthplace;
    $hospital_name = $dbrow->form_data->newborn_birthplace == "Hospital" ? $dbrow->form_data->hospital_name : 'NA';
    $address_hospital = $dbrow->form_data->newborn_birthplace != "Other" ? $dbrow->form_data->address_hospital : 'NA';
    $other_placeofbirth = $dbrow->form_data->newborn_birthplace == "Other" ? $dbrow->form_data->other_placeofbirth : 'NA';
    $late_reason = $dbrow->form_data->late_reason;

    $state = $dbrow->form_data->state;
    $district = $dbrow->form_data->district;
    $subdivision = $dbrow->form_data->subdivision;
    $circle = $dbrow->form_data->revenuecircle;
    $village = $dbrow->form_data->village;
    $pin_code = $dbrow->form_data->pin_code;

    //ENCLOSURES DATA ---START

    $affidavit_type_frm = set_value("affidavit_type");
    $age_proof_type_frm = set_value("age_proof_type");
    $address_proof_type_frm = set_value("address_proof_type");
    $other_doc_type_frm = set_value("other_doc_type");
    $soft_copy_type_frm = set_value("soft_copy_type");

    $uploadedFiles = $this->session->flashdata('uploaded_files');

    $affidavit_frm = $uploadedFiles['affidavit_old'] ?? null;
    $age_proof_frm = $uploadedFiles['age_proof_old'] ?? null;
    $address_proof_frm = $uploadedFiles['address_proof_old'] ?? null;
    $other_doc_frm = $uploadedFiles['other_doc_old'] ?? null;
    $soft_copy_frm = $uploadedFiles['soft_copy_old'] ?? null;

    $affidavit_type_db = $dbrow->form_data->affidavit_type ?? null;
    $age_proof_type_db = $dbrow->form_data->age_proof_type ?? null;
    $address_proof_type_db = $dbrow->form_data->address_proof_type ?? null;
    $other_doc_type_db = $dbrow->form_data->other_doc_type ?? null;
    $soft_copy_type_db = $dbrow->form_data->soft_copy_type ?? null;

    $affidavit_db = $dbrow->form_data->affidavit ?? null;
    $age_proof_db = $dbrow->form_data->age_proof ?? null;
    $address_proof_db = $dbrow->form_data->address_proof ?? null;
    $other_doc_db = $dbrow->form_data->other_doc ?? null;
    $soft_copy_db = $dbrow->form_data->soft_copy ?? null;

    $affidavit_type = strlen($affidavit_type_frm) ? $affidavit_type_frm : $affidavit_type_db;
    $age_proof_type = strlen($age_proof_type_frm) ? $age_proof_type_frm : $age_proof_type_db;
    $address_proof_type = strlen($address_proof_type_frm) ? $address_proof_type_frm : $address_proof_type_db;
    $other_doc_type = strlen($other_doc_type_frm) ? $other_doc_type_frm : $other_doc_type_db;
    $soft_copy_type = strlen($soft_copy_type_frm) ? $soft_copy_type_frm : $soft_copy_type_db;


    $affidavit = strlen($affidavit_frm) ? $affidavit_frm : $affidavit_db;
    $age_proof = strlen($age_proof_frm) ? $age_proof_frm : $age_proof_db;
    $address_proof = strlen($address_proof_frm) ? $address_proof_frm : $address_proof_db;
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
            $.each(data.records, function(key, value) {
                selectOption += '<option value="' + value.district_name + '">' + value.district_name + '</option>';
            });
            $('.dists').append(selectOption);

        });

        $(".dp").datepicker({
            format: 'dd/mm/yyyy',
            endDate: '-21915d',
            autoclose: true,
        });


        $(document).on("change", "#district", function() {
            let selectedVal = $(this).val();
            json_body = '{"district_id":"' + selectedVal + '"}';
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
            json_body = '{"subdiv_id":"' + selectedVal + '"}';
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
                        $(".frmbtn").hide();
                    } else if (clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {} //End of if else
                }
            });
        });

        var affidavitUpload = parseInt(<?= strlen($affidavit) ? 1 : 0 ?>);
        $("#affidavit").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: affidavitUpload ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var ageProof = parseInt(<?= strlen($age_proof) ? 1 : 0 ?>);
        $("#age_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: ageProof ? false : true,
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
        //$("#myfrm :input").prop("disabled", true);     
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/delayedbirth/pdbr/querysubmit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="affidavit_old" value="<?= $affidavit ?>" type="hidden" />
            <input name="age_proof_old" value="<?= $age_proof ?>" type="hidden" />
            <input name="address_proof_old" value="<?= $address_proof ?>" type="hidden" />
            <input name="other_doc_old" value="<?= $other_doc ?>" type="hidden" />
            <input name="soft_copy_old" value="<?= $soft_copy ?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h3><b>Application Form For Permission For Delayed Birth Registration<br>
                            ( পলমকৈ জন্ম পঞ্জীয়নৰ অনুমতিৰ বাবে আবেদন প্ৰ-পত্ৰ )<b></h3>
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
                                <label>Mobile Number/ দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" readonly maxlength="10" />
                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail/ ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?= $email ?>" maxlength="100" disabled />
                                <?= form_error("email") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Relation with New Born/ নৱজাতকৰ লগত সম্বন্ধ <span class="text-danger">*</span> </label>
                                <select name="newborn_relation" class="form-control" id="newborn_relation" disabled>
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
                            <div class="col-md-6" id="other_relation">
                                <label>Enter Other Relation(if any)/ অন্য সম্পৰ্ক (যদি থাকে)<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control other_relation" name="other_relation" value="<?= $other_relation ?>" maxlength="255" disabled />
                                <?= form_error("other_relation") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <!-- <div class="col-md-6">
                                <label>Aadhar No./ আধাৰ কাৰ্ড নং </label>
                                <input type="text" class="form-control" name="aadhar_no" id="aadhar_no" value="<?= $aadhar_no ?>" maxlength="12" disabled />
                                <?= form_error("aadhar_no") ?>
                            </div> -->
                            <div class="col-md-6">
                                <label>PAN No./ পান কাৰ্ড নং </label>
                                <input type="text" class="form-control" name="pan_no" id="pan_no" value="<?= $pan_no ?>" maxlength="10" disabled />
                                <?= form_error("pan_no") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">New Born Details/ নৱজাতকৰ বিৱৰন </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name of the New Born/ নৱজাতকৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="newborn_name" id="newborn_name" value="<?= $newborn_name ?>" maxlength="255" disabled />
                                <?= form_error("newborn_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Date of Birth/ জন্মৰ তাৰিখ <span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="dob" id="dob" value="<?= $dob ?>" maxlength="10" autocomplete="off" type="text" disabled />
                                <?= form_error("dob") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>New Born Gender/ নৱজাতকৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="newborn_gender" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Male" <?= ($newborn_gender === "Male") ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= ($newborn_gender === "Female") ? 'selected' : '' ?>>Female</option>
                                    <option value="Transgender" <?= ($newborn_gender === "Transgender") ? 'selected' : '' ?>>Transgender</option>
                                </select>
                                <?= form_error("newborn_gender") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Father's Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?= $father_name ?>" maxlength="255" disabled />
                                <?= form_error("father_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mother's Name/ মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" id="mother_name" value="<?= $mother_name ?>" maxlength="255" disabled />
                                <?= form_error("mother_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Place of Birth of the New Born/ নৱজাতকৰ জন্মস্থান <span class="text-danger">*</span> </label>
                                <select name="newborn_birthplace" class="form-control" id="newborn_birthplace" disabled>
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
                                <input type="text" class="form-control" name="hospital_name" id="hospital_name" value="<?= $hospital_name ?>" maxlength="255" disabled />
                                <?= form_error("hospital_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address of Home/Hospital/ ঘৰ/ চিকিৎসালয়ৰ ঠিকনা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="address_hospital" id="address_hospital" value="<?= $address_hospital ?>" maxlength="255" disabled />
                                <?= form_error("address_hospital") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6" id="anyOtherBirthplace">
                                <label>Place of Birth (if any)/ জন্মস্থান (যদি আছে) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="other_placeofbirth" id="other_placeofbirth" value="<?= $other_placeofbirth ?>" maxlength="255" disabled />
                                <?= form_error("other_placeofbirth") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Reason for Being Late/ পলম হোৱাৰ কাৰণ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="late_reason" id="late_reason" value="<?= $late_reason ?>" maxlength="255" disabled />
                                <?= form_error("late_reason") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px;">
                        <legend class="h5">Other Details/ অন্যান্য তথ্য </legend>
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
                                <label>Select District/জিলা নিৰ্বাচন কৰক <span class="text-danger">*</span> </label>
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
                                <label>Village/Town/ গাওঁ/চহৰ <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="village" id="village" value="<?= $village ?>" maxlength="255" disabled />
                                <?= form_error("village") ?>
                            </div>
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
                                            <th>Type of Enclosure</th>
                                            <th>Enclosure Document</th>
                                            <th>File/Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td>Register Hospital Govt. / Pvt. Certificate regarding Birth or Age Proof (any)<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="age_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Register Hospital Govt. / Pvt. Certificate regarding Birth or Age Proof (any)" <?= ($age_proof_type === 'Register Hospital Govt. / Pvt. Certificate regarding Birth or Age Proof (any)') ? 'selected' : '' ?>>Register Hospital Govt. / Pvt. Certificate regarding Birth or Age Proof (any) </option>
                                                </select>
                                                <?= form_error("age_proof_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="age_proof" name="age_proof" type="file" />
                                                </div>
                                                <?php if (strlen($age_proof)) { ?>
                                                    <a href="<?= base_url($age_proof) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>School Certificate/Admit Card (for age 6 and above) or parent's details<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="address_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="School Certificate/Admit Card (for age 6 and above) or parents details" <?= ($address_proof_type === 'School Certificate/Admit Card (for age 6 and above) or parents details') ? 'selected' : '' ?>>School Certificate/Admit Card (for age 6 and above) or parent's details</option>
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
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Affidavit<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="affidavit_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Affidavit duly signed by the Magistrate" <?= ($affidavit_type === 'Affidavit duly signed by the Magistrate') ? 'selected' : '' ?>>Affidavit duly signed by the Magistrate</option>

                                                </select>
                                                <?= form_error("affidavit_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="affidavit" name="affidavit" type="file" />
                                                </div>
                                                <?php if (strlen($affidavit)) { ?>
                                                    <a href="<?= base_url($affidavit) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Any other document</td>
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
                                                        View/Download
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
                                                            View/Download
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

                    </fieldset>

                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <!-- <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button> -->
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Save Data
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