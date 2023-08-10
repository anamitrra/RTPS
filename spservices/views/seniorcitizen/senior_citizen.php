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
    $district_id = $dbrow->form_data->district_id;
    $subdivision = $dbrow->form_data->subdivision;
    $subdivision_id = $dbrow->form_data->subdivision_id;
    $circle = $dbrow->form_data->revenuecircle;
    $circle_id = $dbrow->form_data->revenuecircle_id;
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

    $passport_photo_type = isset($dbrow->form_data->passport_photo_type) ? $dbrow->form_data->passport_photo_type : '';
    $passport_photo = isset($dbrow->form_data->passport_photo) ? $dbrow->form_data->passport_photo : '';
    $proof_of_retirement_type = isset($dbrow->form_data->proof_of_retirement_type) ? $dbrow->form_data->proof_of_retirement_type : '';
    $proof_of_retirement = isset($dbrow->form_data->proof_of_retirement) ? $dbrow->form_data->proof_of_retirement : '';
    $age_proof_type = isset($dbrow->form_data->age_proof_type) ? $dbrow->form_data->age_proof_type : '';
    $age_proof = isset($dbrow->form_data->age_proof) ? $dbrow->form_data->age_proof : '';
    $address_proof_type = isset($dbrow->form_data->address_proof_type) ? $dbrow->form_data->address_proof_type: '';
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
    $spouse_name = set_value("spouse_name");
    $dob = set_value("dob");
    $father_name = set_value("father_name");
    $identification_mark = set_value("identification_mark");
    $occupation = set_value("occupation");
    $blood_group = set_value("blood_group");
    $service_type = set_value("service_type");
    $pan_no = set_value("pan_no");
    $aadhar_no = set_value("aadhar_no");

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
    $house_no = set_value("house_no");
    $police_st = set_value("police_st");
    $post_office = set_value("post_office");
    $pin_code = set_value("pin_code");
    $landline_no = set_value("landline_no");

    $caste = set_value("caste");
    $ex_serviceman = set_value("ex_serviceman");
    $minority = set_value("minority");
    $under_bpl = set_value("under_bpl");
    $allowance = set_value("allowance");
    $allowance_details = set_value("allowance_details");

    $passport_photo_type = "";
    $passport_photo = "";
    $proof_of_retirement_type = "";
    $proof_of_retirement = "";
    $age_proof_type = "";
    $age_proof = "";
    $address_proof_type = "";
    $address_proof = "";
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
            $.each(data.ListOfDistricts, function(key, value) {
                selectOption += '<option value="' + value.DistrictName + '/' + value.DistrictId + '">' + value.DistrictName + '</option>';
            });
            $('.dists').append(selectOption);

        });

        $(".dp").datepicker({
            format: 'dd/mm/yyyy',
            endDate: '-21915d',
            autoclose: true,
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/senior-citizen-certificate') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input name="passport_photo_type" value="<?= $passport_photo_type ?>" type="hidden" />
            <input name="passport_photo" value="<?= $passport_photo ?>" type="hidden" />
            <input name="proof_of_retirement_type" value="<?= $proof_of_retirement_type ?>" type="hidden" />
            <input name="proof_of_retirement" value="<?= $proof_of_retirement ?>" type="hidden" />
            <input name="age_proof_type" value="<?= $age_proof_type ?>" type="hidden" />
            <input name="age_proof" value="<?= $age_proof ?>" type="hidden" />
            <input name="address_proof_type" value="<?= $address_proof_type ?>" type="hidden" />
            <input name="address_proof" value="<?= $address_proof ?>" type="hidden" />
            <?php if(!empty($other_doc_type)){ ?>
            <input name="other_doc_type" value="<?= $other_doc_type ?>" type="hidden" />
            <input name="other_doc" value="<?= $other_doc ?>" type="hidden" />
            <?php } ?>
            <input name="soft_copy_type" value="<?= $soft_copy_type ?>" type="hidden" />
            <input name="soft_copy" value="<?= $soft_copy ?>" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b>Application for Senior Citizen Certificate<br>
                            ( জ্যেষ্ঠ নাগৰিকৰ প্ৰমানপত্ৰৰ বাবে আবেদন )<b></h4><br>
                    <h6>(The person should show the document of passport/voter ID/ Ration card where its mentioned that the person is from ASSAM)<br>
                        (ব্যক্তিজনে পাছপোৰ্ট/ভোটাৰ পৰিচয় পত্ৰ/ ৰেচন কাৰ্ডৰ নথি পত্ৰ প্ৰদৰ্শন কৰিব লাগিব য'ত উল্লেখ কৰা হৈছে যে ব্যক্তিজন অসমৰ)</h6>
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
                            <li>The certificate will be delivered within 20 Days of application.</li>
                            <li>প্ৰমাণ পত্ৰ ২0 দিনৰ ভিতৰত(সাধাৰণ) প্ৰদান কৰা হ'ব</li>
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
                            <li>3. Applicant's photo should be in JPEG format.</li>
                            <li>৩. আবেদনকাৰীৰ ফটো jpeg formatত হ’ব লাগিব।</li>
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
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Name of Spouse/ পৰিবাৰ/স্বামীৰ নাম </label>
                                <input class="form-control" name="spouse_name" value="<?= $spouse_name ?>" maxlength="100" type="text" />
                                <?= form_error("spouse_name") ?>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Date of Birth/ জন্মৰ তাৰিখ <span class="text-danger">*</span> </label>
                                <input class="form-control dp" name="dob" id="dob" value="<?= $dob ?>" maxlength="10" autocomplete="off" type="text" />
                                <?= form_error("dob") ?>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Age/ বয়স </label>
                                <input class="form-control" name="age" id="age" value="" type="text" readonly style="font-size:14px" />
                                <?= form_error("age") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Father's Name/ পিতাৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?= $father_name ?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Identification Mark/ চিনাক্তকৰণৰ চিহ্ন </label>
                                <input type="text" class="form-control" name="identification_mark" id="identification_mark" value="<?= $identification_mark ?>" maxlength="255" />
                                <?= form_error("identification_mark") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Occupation/ বৃতি<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="occupation" id="occupation" value="<?= $occupation ?>" maxlength="255" />
                                <?= form_error("occupation") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Blood Group/ তেজৰ গ্ৰূপ <span class="text-danger">*</span> </label>
                                <select name="blood_group" class="form-control">
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
                                <input type="text" class="form-control" name="service_type" id="service_type" value="<?= $service_type ?>" maxlength="255" />
                                <?= form_error("service_type") ?>
                            </div>
                            <div class="col-md-6">
                                <label>PAN No./ পান কাৰ্ড নং<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pan_no" id="pan_no" value="<?= $pan_no ?>" maxlength="10" />
                                <?= form_error("pan_no") ?>
                            </div>
                            <!-- <div class="col-md-3">
                                <label>Aadhar No./ আধাৰ কাৰ্ড নং </label>
                                <input type="text" class="form-control" name="aadhar_no" id="aadhar_no" value="<?= $aadhar_no ?>" maxlength="12" />
                                <?= form_error("aadhar_no") ?>
                            </div> -->
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant Address/ আবেদনকাৰীৰ ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Address Line 1/ ঠিকনাৰ প্ৰথ্ম শাৰী <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="address_line1" value="<?= $address_line1 ?>" />
                                <?= form_error("address_line1") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address Line 2/ ঠিকনাৰ দ্বিতীয় শাৰী <span class="text-danger">*</span></label>
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
                                <label>House No/ ঘৰ নং <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="house_no" id="house_no" value="<?= $house_no ?>" maxlength="255" />
                                <?= form_error("house_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Police Station/ থানা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="police_st" id="police_st" value="<?= $police_st ?>" maxlength="255" />
                                <?= form_error("police_st") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Post Office/ ডাকঘৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="post_office" id="post_office" value="<?= $post_office ?>" maxlength="255" />
                                <?= form_error("post_office") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Pin Code/ পিনক'ড (e.g 78xxxx) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pin_code" id="pin_code" value="<?= $pin_code ?>" maxlength="6" />
                                <?= form_error("pin_code") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Landline Number/ দুৰভাষ (if any) </label>
                                <input type="text" class="form-control" name="landline_no" id="landline_no" value="<?= $landline_no ?>" maxlength="10" />
                                <?= form_error("landline_no") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px;">
                        <legend class="h5">Other Details/ অন্যান্য তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Caste/ জাতি <span class="text-danger">*</span> </label>
                                <select name="caste" class="form-control">
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
                                <select name="ex_serviceman" class="form-control">
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
                                <select name="minority" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Yes" autocomplete="off" <?= ($minority === "Yes") ? 'selected' : '' ?>>Yes</option>
                                    <option value="No" autocomplete="off" <?= ($minority === "No") ? 'selected' : '' ?>>No</option>
                                </select>
                                <?= form_error("minority") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Is Falling Under BPL/ দৰিদ্ৰ সীমাৰেখাৰ ভিতৰত অন্তৰ্ভুক্ত নেকি ? <span class="text-danger">*</span></label>
                                <select name="under_bpl" class="form-control">
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
                                <select name="allowance" id="allowance" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Yes" autocomplete="off" <?= ($allowance === "Yes") ? 'selected' : '' ?>>Yes</option>
                                    <option value="No" autocomplete="off" <?= ($allowance === "No") ? 'selected' : '' ?>>No</option>
                                </select>
                                <?= form_error("allowance") ?>
                            </div>
                            <div class="col-md-6" id="allowance_details" style="display:none;">
                                <label>Allowance Details/ ভাট্টা সবিশেষ <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="allowance_details" id="allowance_details_textarea"><?= $allowance_details ?></textarea>
                                <?= form_error("allowance_details") ?>
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