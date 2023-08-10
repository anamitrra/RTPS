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

    $spouse_name =  $dbrow->form_data->spouse_name;
    $dob = $dbrow->form_data->dob;
    $father_name = $dbrow->form_data->father_name;
    $identification_mark = isset($dbrow->form_data->identification_mark) ? $dbrow->form_data->identification_mark : '';
    $occupation = $dbrow->form_data->occupation;
    $blood_group = $dbrow->form_data->blood_group;
    $service_type = $dbrow->form_data->service_type;
    $pan_no = $dbrow->form_data->pan_no;
    $aadhar_no = $dbrow->form_data->aadhar_no;

    $address_line1 = $dbrow->form_data->address_line1;
    $address_line2 = $dbrow->form_data->address_line2;
    $state = $dbrow->form_data->state;
    $district = $dbrow->form_data->district;
    $subdivision = $dbrow->form_data->subdivision;
    $circle = $dbrow->form_data->revenuecircle;
    $mouza = $dbrow->form_data->mouza;
    $village = $dbrow->form_data->village;
    $house_no = $dbrow->form_data->house_no;
    $police_st = $dbrow->form_data->police_st;
    $post_office = $dbrow->form_data->post_office;
    $pin_code = $dbrow->form_data->pin_code;
    $landline_no = $dbrow->form_data->landline_no;

    $caste = $dbrow->form_data->caste;
    $ex_serviceman = $dbrow->form_data->ex_serviceman;
    $minority = $dbrow->form_data->minority;
    $under_bpl = $dbrow->form_data->under_bpl;
    $allowance = $dbrow->form_data->allowance;
    $allowance_details = $dbrow->form_data->allowance_details;

    //ENCLOSURES DATA ---START

    $passport_photo_type_frm = set_value("passport_photo_type");
    $proof_of_retirement_type_frm = set_value("proof_of_retirement_type");
    $age_proof_type_frm = set_value("age_proof_type");
    $address_proof_type_frm = set_value("address_proof_type");
    $other_doc_type_frm = set_value("other_doc_type");
    $soft_copy_type_frm = set_value("soft_copy_type");

    $uploadedFiles = $this->session->flashdata('uploaded_files');
    $passport_photo_frm = $uploadedFiles['passport_photo_old'] ?? null;
    $proof_of_retirement_frm = $uploadedFiles['proof_of_retirement_old'] ?? null;
    $age_proof_frm = $uploadedFiles['age_proof_old'] ?? null;
    $address_proof_frm = $uploadedFiles['address_proof_old'] ?? null;
    $other_doc_frm = $uploadedFiles['other_doc_old'] ?? null;
    $soft_copy_frm = $uploadedFiles['soft_copy_old'] ?? null;

    $passport_photo_type_db = $dbrow->form_data->passport_photo_type ?? null;
    $proof_of_retirement_type_db = $dbrow->form_data->proof_of_retirement_type ?? null;
    $age_proof_type_db = $dbrow->form_data->age_proof_type ?? null;
    $address_proof_type_db = $dbrow->form_data->address_proof_type ?? null;
    $other_doc_type_db = $dbrow->form_data->other_doc_type ?? null;
    $soft_copy_type_db = $dbrow->form_data->soft_copy_type ?? null;

    $passport_photo_db = $dbrow->form_data->passport_photo ?? null;
    $proof_of_retirement_db = $dbrow->form_data->proof_of_retirement ?? null;
    $age_proof_db = $dbrow->form_data->age_proof ?? null;
    $address_proof_db = $dbrow->form_data->address_proof ?? null;
    $other_doc_db = $dbrow->form_data->other_doc ?? null;
    $soft_copy_db = $dbrow->form_data->soft_copy ?? null;

    $passport_photo_type = strlen($passport_photo_type_frm) ? $passport_photo_type_frm : $passport_photo_type_db;
    $proof_of_retirement_type = strlen($proof_of_retirement_type_frm) ? $proof_of_retirement_type_frm : $proof_of_retirement_type_db;
    $age_proof_type = strlen($age_proof_type_frm) ? $age_proof_type_frm : $age_proof_type_db;
    $address_proof_type = strlen($address_proof_type_frm) ? $address_proof_type_frm : $address_proof_type_db;
    $other_doc_type = strlen($other_doc_type_frm) ? $other_doc_type_frm : $other_doc_type_db;
    $soft_copy_type = strlen($soft_copy_type_frm) ? $soft_copy_type_frm : $soft_copy_type_db;

    $passport_photo = strlen($passport_photo_frm) ? $passport_photo_frm : $passport_photo_db;
    $proof_of_retirement = strlen($proof_of_retirement_frm) ? $proof_of_retirement_frm : $proof_of_retirement_db;
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
    let allowance = '<?= $allowance ?>';
    $(document).ready(function() {
        if (allowance == "Yes") {
            $("#allowance_details").show();
        } else {
            $("#allowance_details_textarea").val('');
            $("#allowance_details").hide();
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
            endDate: '+0d',
            autoclose: true
        });
        var getAge = function(db) {
            $.ajax({
                type: "POST",
                url: "<?= base_url('spservices/seniorcitizen/scc/get_age') ?>",
                data: {
                    "dob": db
                },
                beforeSend: function() {
                    $("#age").val("Calculating...");
                },
                success: function(res) {
                    $("#age").val(res);
                }
            });
        };

        var date_of_birth = '<?= $dob ?>';
        if (date_of_birth.length == 10) {
            var dateAr = date_of_birth.split('/');
            var dob = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];
            getAge(dob);
        } //End of if

        $(document).on("change", "#dob", function() {
            var dd = $(this).val(); //alert(dd);
            var dateAr = dd.split('/');
            var dob = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];
            getAge(dob);
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

        $(document).on("change", '#allowance', function() {
            let getVal = $('#allowance').val();
            if (getVal == "Yes") {
                $("#allowance_details").show();
            } else {
                $("#allowance_details_textarea").val('');
                $("#allowance_details").hide();
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

        var passportPhoto = parseInt(<?= strlen($passport_photo) ? 1 : 0 ?>);
        $("#passport_photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: passportPhoto ? false : true,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg"]
        });

        var proofofRetirement = parseInt(<?= strlen($proof_of_retirement) ? 1 : 0 ?>);
        $("#proof_of_retirement").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: proofofRetirement ? false : true,
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/seniorcitizen/scc/querysubmit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="passport_photo_old" value="<?= $passport_photo ?>" type="hidden" />
            <input name="proof_of_retirement_old" value="<?= $proof_of_retirement ?>" type="hidden" />
            <input name="age_proof_old" value="<?= $age_proof ?>" type="hidden" />
            <input name="address_proof_old" value="<?= $address_proof ?>" type="hidden" />
            <input name="other_doc_old" value="<?= $other_doc ?>" type="hidden" />
            <input name="soft_copy_old" value="<?= $soft_copy ?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h3><b>Application for Senior Citizen Certificate<br>
                            ( জ্যেষ্ঠ নাগৰিকৰ প্ৰমানপত্ৰৰ বাবে আবেদন )<b></h3><br>
                    <h6>(The person should show the document of passport/voter ID/ Ration card where its mentioned that the person is from ASSAM)<br>
                        (ব্যক্তিজনে পাছপোৰ্ট/ভোটাৰ পৰিচয় পত্ৰ/ ৰেচন কাৰ্ডৰ নথি পত্ৰ প্ৰদৰ্শন কৰিব লাগিব য'ত উল্লেখ কৰা হৈছে যে ব্যক্তিজন অসমৰ)</h6>
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
                                <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" maxlength="10" disabled />
                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail/ ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?= $email ?>" maxlength="100" disabled />
                                <?= form_error("email") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Name of Spouse/ পৰিবাৰ/স্বামীৰ নাম *<span class="text-danger">*</span></label>
                                <input class="form-control" name="spouse_name" value="<?= $spouse_name ?>" maxlength="100" type="text" disabled />
                                <?= form_error("spouse_name") ?>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Date of Birth/ জন্মৰ তাৰিখ <span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="dob" id="dob" value="<?= $dob ?>" maxlength="10" autocomplete="off" type="text" disabled />
                                <?= form_error("dob") ?>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Age/ বয়স </label>
                                <input class="form-control" name="age" id="age" value="" type="text" readonly style="font-size:14px" disabled />
                                <?= form_error("age") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Father's Name/ পিতাৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?= $father_name ?>" maxlength="255" disabled />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Identification Mark/ চিনাক্তকৰণৰ চিহ্ন </label>
                                <input type="text" class="form-control" name="identification_mark" id="identification_mark" value="<?= $identification_mark ?>" maxlength="255" disabled />
                                <?= form_error("identification_mark") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Occupation/ বৃতি<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="occupation" id="occupation" value="<?= $occupation ?>" maxlength="255" disabled />
                                <?= form_error("occupation") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Blood Group/ তেজৰ গ্ৰূপ <span class="text-danger">*</span> </label>
                                <select name="blood_group" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="A+" autocomplete="off" <?= ($blood_group === "A+") ? 'selected' : '' ?>>A+</option>
                                    <option value="A-" autocomplete="off" <?= ($blood_group === "A-") ? 'selected' : '' ?>>A-</option>
                                    <option value="B+" autocomplete="off" <?= ($blood_group === "B+") ? 'selected' : '' ?>>B+</option>
                                    <option value="B-" autocomplete="off" <?= ($blood_group === "B-") ? 'selected' : '' ?>>B-</option>
                                    <option value="O+" autocomplete="off" <?= ($blood_group === "O+") ? 'selected' : '' ?>>O+</option>
                                    <option value="O-" autocomplete="off" <?= ($blood_group === "O-") ? 'selected' : '' ?>>O-</option>
                                    <option value="AB+" autocomplete="off" <?= ($blood_group === "AB+") ? 'selected' : '' ?>>AB+</option>
                                    <option value="AB-" autocomplete="off" <?= ($blood_group === "AB-") ? 'selected' : '' ?>>AB-</option>
                                </select>
                                <?= form_error("blood_group") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Service Type/ সেৱাৰ প্ৰকাৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="service_type" id="service_type" value="<?= $service_type ?>" maxlength="255" disabled />
                                <?= form_error("service_type") ?>
                            </div>
                            <div class="col-md-6">
                                <label>PAN No./ পান কাৰ্ড নং<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pan_no" id="pan_no" value="<?= $pan_no ?>" maxlength="10" disabled />
                                <?= form_error("pan_no") ?>
                            </div>
                            <!-- <div class="col-md-3">
                                <label>Aadhar No./ আধাৰ কাৰ্ড নং<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="aadhar_no" id="aadhar_no" value="<?= $aadhar_no ?>" maxlength="12" disabled />
                                <?= form_error("aadhar_no") ?>
                            </div> -->
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant Address/ আবেদনকাৰীৰ ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Address Line 1/ ঠিকনাৰ প্ৰথ্ম শাৰী <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="address_line1" value="<?= $address_line1 ?>" disabled />
                                <?= form_error("address_line1") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address Line 2/ ঠিকনাৰ দ্বিতীয় শাৰী <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="address_line2" value="<?= $address_line2 ?>" disabled />
                                <?= form_error("address_line2") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>State/ ৰাজ্য *<span class="text-danger">*</span> </label>
                                <select name="state" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Assam" autocomplete="off" selected="selected">Assam</option>
                                </select>
                                <?= form_error("state") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Select District/জিলা নিৰ্বাচন কৰক *<span class="text-danger">*</span> </label>
                                <select name="district" id="district" class="form-control dists" disabled>
                                    <option value="<?= $district ?>"><?= strlen($district) ? $district : 'Select' ?></option>
                                </select>
                                <?= form_error("district") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Sub Division/ মহকুমা *<span class="text-danger">*</span> </label>
                                <select name="subdivision" id="subdivision" class="form-control subdiv" disabled>
                                    <option value="<?= $subdivision ?>"><?= strlen($subdivision) ? $subdivision : 'Select' ?></option>
                                </select>
                                <?= form_error("subdivision") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Circle Office/ চক্ৰ *<span class="text-danger">*</span> </label>
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
                                <label>House No/ ঘৰ নং <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="house_no" id="house_no" value="<?= $house_no ?>" maxlength="255" disabled />
                                <?= form_error("house_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Police Station/ থানা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="police_st" id="police_st" value="<?= $police_st ?>" maxlength="255" disabled />
                                <?= form_error("police_st") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Post Office/ ডাকঘৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="post_office" id="post_office" value="<?= $post_office ?>" maxlength="255" disabled />
                                <?= form_error("post_office") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Pin Code/ পিনক'ড (e.g 78xxxx) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pin_code" id="pin_code" value="<?= $pin_code ?>" maxlength="6" disabled />
                                <?= form_error("pin_code") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Landline Number/ দুৰভাষ (if any) </label>
                                <input type="text" class="form-control" name="landline_no" id="landline_no" value="<?= $landline_no ?>" maxlength="10" disabled />
                                <?= form_error("landline_no") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px;">
                        <legend class="h5">Other Details/ অন্যান্য তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Caste/ জাতি <span class="text-danger">*</span> </label>
                                <select name="caste" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="General" autocomplete="off" <?= ($caste === "General") ? 'selected' : '' ?>>General</option>
                                    <option value="ST" autocomplete="off" <?= ($caste === "ST") ? 'selected' : '' ?>>ST</option>
                                    <option value="SC" autocomplete="off" <?= ($caste === "SC") ? 'selected' : '' ?>>SC</option>
                                    <option value="OBC" autocomplete="off" <?= ($caste === "OBC") ? 'selected' : '' ?>>OBC</option>
                                    <option value="MOBC" autocomplete="off" <?= ($caste === "MOBC") ? 'selected' : '' ?>>MOBC</option>
                                </select>
                                <?= form_error("caste") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Ex-Serviceman/ প্ৰাক্তন সেৱা বিষয়া <span class="text-danger">*</span></label>
                                <select name="ex_serviceman" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Yes" autocomplete="off" <?= ($ex_serviceman === "Yes") ? 'selected' : '' ?>>Yes</option>
                                    <option value="No" autocomplete="off" <?= ($ex_serviceman === "No") ? 'selected' : '' ?>>No</option>
                                </select>
                                <?= form_error("ex_serviceman") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Minority/ সংখ্যা লঘু <span class="text-danger">*</span> </label>
                                <select name="minority" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Yes" autocomplete="off" <?= ($minority === "Yes") ? 'selected' : '' ?>>Yes</option>
                                    <option value="No" autocomplete="off" <?= ($minority === "No") ? 'selected' : '' ?>>No</option>
                                </select>
                                <?= form_error("minority") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Is Falling Under BPL/ দৰিদ্ৰ সীমাৰেখাৰ ভিতৰত অন্তৰ্ভুক্ত নেকি ? <span class="text-danger">*</span></label>
                                <select name="under_bpl" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Yes" autocomplete="off" <?= ($under_bpl === "Yes") ? 'selected' : '' ?>>Yes</option>
                                    <option value="No" autocomplete="off" <?= ($under_bpl === "No") ? 'selected' : '' ?>>No</option>
                                </select>
                                <?= form_error("under_bpl") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Getting any Allowance(Pension)/ কিবা ভাট্টা পায় নেকি ? <span class="text-danger">*</span> </label>
                                <select name="allowance" id="allowance" class="form-control" disabled>
                                    <option value="">Please Select</option>
                                    <option value="Yes" autocomplete="off" <?= ($allowance === "Yes") ? 'selected' : '' ?>>Yes</option>
                                    <option value="No" autocomplete="off" <?= ($allowance === "No") ? 'selected' : '' ?>>No</option>
                                </select>
                                <?= form_error("allowance") ?>
                            </div>
                            <div class="col-md-6" id="allowance_details" style="display:none;">
                                <label>Allowance Details/ ভাট্টা সবিশেষ <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="allowance_details" id="allowance_details_textarea" disabled><?= $allowance_details ?></textarea>
                                <?= form_error("allowance_details") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>১. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য।</li>
                            <li>2. Applicant's photo should be in JPEG format.</li>
                            <li>২. আবেদনকাৰীৰ ফটো jpeg formatত হ’ব লাগিব।</li>
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
                                            <td>Passport size photograph<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="passport_photo_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Photograph" <?= ($passport_photo_type === 'Photograph') ? 'selected' : '' ?>>Passport size photograph</option>
                                                </select>
                                                <?= form_error("passport_photo_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="passport_photo" name="passport_photo" type="file" />
                                                </div>
                                                <?php if (strlen($passport_photo)) { ?>
                                                    <a href="<?= base_url($passport_photo) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download (Old Document)
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Proof Of Retirement(for Govt. Servants) or Copy of 1966 Voter list (for other than - Govt. servant)<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="proof_of_retirement_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Proof Of Retirement(for Govt. Servants)" <?= ($proof_of_retirement_type === 'Proof Of Retirement(for Govt. Servants)') ? 'selected' : '' ?>>Proof Of Retirement(for Govt. Servants)</option>
                                                    <option value="Copy of 1966 Voter list (for other than - Govt. servant)" <?= ($proof_of_retirement_type === 'Copy of 1966 Voter list (for other than - Govt. servant)') ? 'selected' : '' ?>>Copy of 1966 Voter list (for other than - Govt. servant)</option>
                                                </select>
                                                <?= form_error("proof_of_retirement_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="proof_of_retirement" name="proof_of_retirement" type="file" />
                                                </div>
                                                <?php if (strlen($proof_of_retirement)) { ?>
                                                    <a href="<?= base_url($proof_of_retirement) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download (Old Document)
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Age proof<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="age_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Pension Payment Order from the competent authority" <?= ($age_proof_type === 'Pension Payment Order from the competent authority') ? 'selected' : '' ?>>Pension Payment Order from the competent authority</option>
                                                    <option value="School Certificate" <?= ($age_proof_type === 'School Certificate') ? 'selected' : '' ?>>School Certificate</option>
                                                    <option value="Certificate issued by Gaon Burha or Local Mauzadar (In absence of other documents)" <?= ($age_proof_type === 'Certificate issued by Gaon Burha or Local Mauzadar (In absence of other documents)') ? 'selected' : '' ?>>Certificate issued by Gaon Burha or Local Mauzadar (In absence of other documents)</option>
                                                    <option value="Bank Pass Book with photograph" <?= ($age_proof_type === 'Bank Pass Book with photograph') ? 'selected' : '' ?>>Bank Pass Book with photograph</option>
                                                    <option value="Copy of Passport" <?= ($age_proof_type === 'Copy of Passport') ? 'selected' : '' ?>>Copy of Passport</option>
                                                    <option value="Copy of PAN Card" <?= ($age_proof_type === 'Copy of PAN Card') ? 'selected' : '' ?>>Copy of PAN Card</option>
                                                    <option value="Marriage Certificate (Incase of change of name of women)" <?= ($age_proof_type === 'Marriage Certificate (Incase of change of name of women)') ? 'selected' : '' ?>>Marriage Certificate (Incase of change of name of women)</option>
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
                                                        View/Download (Old Document)
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Address proof.<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="address_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Voter ID Card" <?= ($address_proof_type === 'Voter ID Card') ? 'selected' : '' ?>>Voter ID Card</option>
                                                    <option value="Copy of Passport" <?= ($address_proof_type === 'Copy of Passport') ? 'selected' : '' ?>>Copy of Passport</option>
                                                    <option value="Ration Card" <?= ($address_proof_type === 'Ration Card') ? 'selected' : '' ?>>Ration Card</option>
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
                                            <td>Other documents</td>
                                            <td>
                                                <select name="other_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Electricity Bill" <?= ($other_doc_type === 'Electricity Bill') ? 'selected' : '' ?>>Electricity Bill</option>
                                                    <option value="Voter List" <?= ($other_doc_type === 'Voter List') ? 'selected' : '' ?>>Voter List</option>
                                                    <option value="A copy of affidavit" <?= ($other_doc_type === 'A copy of affidavit') ? 'selected' : '' ?>>A copy of affidavit</option>
                                                    <option value="Telephone Bill" <?= ($other_doc_type === 'Telephone Bill') ? 'selected' : '' ?>>Telephone Bill</option>
                                                    <option value="Other supporting document" <?= ($other_doc_type === 'Other supporting document') ? 'selected' : '' ?>>Other supporting document</option>
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
                                        <?php if ($this->slug == 'userr') { ?>
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