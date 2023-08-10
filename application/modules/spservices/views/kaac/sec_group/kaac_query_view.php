<?php

if ($dbrow) {
$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;
$appl_status = $dbrow->service_data->appl_status;
$pageTitle = $dbrow->service_data->service_name;
$pageTitleId = $dbrow->form_data->service_id;

$pre_certificate_no =  $dbrow->form_data->pre_certificate_no ?? null;
$pre_mobile_no =  $dbrow->form_data->pre_mobile_no ?? null;


$applicant_title = $dbrow->form_data->applicant_title;
$first_name = $dbrow->form_data->first_name;
$last_name = $dbrow->form_data->last_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$caste = $dbrow->form_data->caste;
$father_title =$dbrow->form_data->father_title;
$father_name = $dbrow->form_data->father_name;
$aadhar_no =$dbrow->form_data->aadhar_no;
$mobile = $this->session->mobile; 
$email =$dbrow->form_data->email;


$district =$dbrow->form_data->district;
$police_station =$dbrow->form_data->police_station;
$post_office = $dbrow->form_data->post_office;

$name_of_firm =$dbrow->form_data->name_of_firm; 
$name_of_proprietor =$dbrow->form_data->name_of_proprietor;
$occupation_trade  = $dbrow->form_data->occupation_trade ;
$community = $dbrow->form_data->community;
$class_of_business = $dbrow->form_data->class_of_business;
$address = $dbrow->form_data->address;
$business_locality = $dbrow->form_data->business_locality;
$business_word_no =$dbrow->form_data->business_word_no;
$reason_for_consideration =$dbrow->form_data->reason_for_consideration;
$rented_owned =$dbrow->form_data->rented_owned;
$name_of_owner = $dbrow->form_data->name_of_owner;


$signature = $dbrow->form_data->signature ?? '';
$signature_type = $dbrow->form_data->signature_type ?? '';

$photo = $dbrow->form_data->photo ?? '';
$photo_type = $dbrow->form_data->photo_type ?? '';
$identity_proof = $dbrow->form_data->identity_proof ?? '';
$identity_proof_type = $dbrow->form_data->identity_proof_type ?? '';   
$address_proof = $dbrow->form_data->address_proof ?? '';
$address_proof_type = $dbrow->form_data->address_proof_type ?? '';   
$house_tax_reciept = $dbrow->form_data->house_tax_reciept ?? '';
$house_tax_reciept_type = $dbrow->form_data->house_tax_reciept_type ?? '';   
$room_rent_deposite = $dbrow->form_data->room_rent_deposite ?? '';
$room_rent_deposite_type = $dbrow->form_data->room_rent_deposite_type ?? '';   
$consideration_letter = $dbrow->form_data->consideration_letter ?? '';
$consideration_letter_type = $dbrow->form_data->consideration_letter_type ?? '';   
$cur_business_copy_rc = $dbrow->form_data->cur_business_copy_rc ?? '';
$cur_business_copy_rc_type = $dbrow->form_data->cur_business_copy_rc_type ?? '';   



$signature = $dbrow->form_data->signature ?? '';
$signature_type = $dbrow->form_data->signature_type ?? '';

$photo = $dbrow->form_data->photo ?? '';
$photo_type = $dbrow->form_data->photo_type ?? '';

$identity_proof = $dbrow->form_data->identity_proof ?? '';
$identity_proof_type = $dbrow->form_data->identity_proof_type ?? '';   

$address_proof = $dbrow->form_data->address_proof ?? '';
$address_proof_type = $dbrow->form_data->address_proof_type ?? '';   


$house_tax_reciept = $dbrow->form_data->house_tax_reciept ?? '';
$house_tax_reciept_type = $dbrow->form_data->house_tax_reciept_type ?? '';   


$room_rent_deposite = $dbrow->form_data->room_rent_deposite ?? '';
$room_rent_deposite_type = $dbrow->form_data->room_rent_deposite_type ?? '';   


$consideration_letter = $dbrow->form_data->consideration_letter ?? '';
$consideration_letter_type = $dbrow->form_data->consideration_letter_type ?? '';   


$cur_business_copy_rc = $dbrow->form_data->cur_business_copy_rc ?? '';
$cur_business_copy_rc_type = $dbrow->form_data->cur_business_copy_rc_type ?? '';   


$status = $dbrow->service_data->appl_status ?? '';

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
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/webcamjs/webcam.min.js') ?>" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function() {


        
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {

        $(document).on("click", "#open_camera", function() {
        $("#live_photo_div").show();
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#my_camera');
        $("#open_camera").hide();
    });

    $(document).on("click", "#capture_photo", function() {
        Webcam.snap(function(data_uri) { //alert(data_uri);
            $("#captured_photo").attr("src", data_uri);
            $("#photo_data").val(data_uri);
        });
    });


    $("#photo").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg"]
    });
    $("#signature").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg"]
    });
    $("#identity_proof").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#address_proof").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#house_tax_reciept").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#room_rent_deposite").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#consideration_letter").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });
    $("#cur_business_copy_rc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["pdf"]
    });




        $(document).on("click", ".frmbtn", function() {
            let clickedBtn = $(this).attr("id");
            $("#submit_mode").val(clickedBtn);
            if (clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if (clickedBtn === 'SAVE') {
                var msg = "Do you want to procced?";
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/kaac_brc/registration/querysubmit') ?>" enctype="multipart/form-data">

            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="service_name" value="<?= $pageTitle ?>" type="hidden" />
            <input name="service_id" value="<?= $pageTitleId ?>" type="hidden" />
           
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 20px; color: #fff; font-family: Roboto,sans-serif; font-weight: bold">
                    <h4><b><?= $pageTitle ?><br>
                            <b></h4>
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
                    <?php } if($status === 'QS') { ?>
                    <fieldset class="border border-danger" style="margin-top:40px; margin-bottom: 2px">
                        <legend class="h5">QUERY DETAILS </legend>
                        <div class="row">
                            <div class="col-md-12" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                <?=(end($dbrow->processing_history)->remarks)??''?>
                            </div>
                        </div>
                        <span style="float:right; font-size: 12px">
                            Query time :
                            <?=isset(end($dbrow->processing_history)->processing_time)?format_mongo_date(end($dbrow->processing_history)->processing_time):''?>
                        </span>
                    </fieldset>
                    <?php }//End of if ?>


                
                    <?php if ($pageTitleId == "KRBC") { ?>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Previous Applicant Details/ পূৰ্বৰ আবেদনকাৰীৰ বিৱৰণ </legend>

                        <div class="row form-group">
                            <div class="col-md-6">

                                <label>Enter Business Registration Certificate No. / ব্যৱসায় পঞ্জীয়ন প্ৰমাণপত্ৰ নং
                                    <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pre_certificate_no"
                                    value="<?= $pre_certificate_no ?>" />

                                <?= form_error("pre_certificate_no") ?>
                            </div>


                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>

                                <input type="text" class="form-control" name="pre_mobile_no"
                                    value="<?= $pre_mobile_no ?>" maxlength="10" />


                                <?= form_error("pre_mobile_no") ?>
                            </div>

                        </div>
                    </fieldset>
                    <?php } ?>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details/ আবেদনকাৰীৰ তথ্য </legend>

                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Applicant&apos;s First Name/ আবেদনকাৰীৰ প্ৰথম নাম<span
                                        class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text p-1">
                                        <select name="applicant_title">
                                            <option value="">Select</option>
                                            <?php
                                            $titles = array(
                                                "Mr" => "Mr",
                                                "Mrs" => "Mrs",
                                                "Sri" => "Sri",
                                                "Smt" => "Smt",
                                                "Late" => "Late"
                                            );

                                            foreach ($titles as $value => $display) {
                                                $selected = ($applicant_title === $value) ? 'selected' : '';
                                                echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                            }
                                            ?>
                                        </select>

                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="first_name" id="first_name"
                                        value="<?= $first_name ?>" maxlength="255" />
                                </div>
                                <?= form_error("applicant_title") . form_error("first_name") ?>

                            </div>

                            <div class="col-md-4">
                                <label>Applicant&apos;s Last Name/ আবেদনকাৰীৰ উপাধি<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="last_name" id="last_name"
                                    value="<?= $last_name ?>" maxlength="255" />
                                <?= form_error("last_name") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span>
                                </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                    $genders = array(
                                        "Male" => "Male",
                                        "Female" => "Female",
                                        "Transgender" => "Transgender"
                                    );

                                    foreach ($genders as $value => $display) {
                                        $selected = ($applicant_gender === $value) ? 'selected' : '';
                                        echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                    }
                                    ?>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Applicant&apos;s Caste/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span>
                                </label>
                                <select name="caste" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                    $castes = array(
                                        "General" => "General",
                                        "ST(H)" => "ST(H)",
                                        "ST(P)" => "ST(P)",
                                        "OBC" => "OBC",
                                        "Other" => "Other"
                                    );

                                    foreach ($castes as $value => $display) {
                                        $selected = ($caste === $value) ? 'selected' : '';
                                        echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                    }
                                    ?>
                                </select>

                                <?= form_error("caste") ?>
                            </div>

                            <div class="col-md-4">
                                <label>Father&apos;s/Husband&apos;s Name/ পিতৃৰ নাম<span class="text-danger">*</span>
                                </label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text p-1">
                                            
                                            <select name="father_title">
                                                <option value="">Select</option>
                                                <?php
                                                $father_titles = array(
                                                    "Mr" => "Mr",
                                                    "Mrs" => "Mrs",
                                                    "Sri" => "Sri",
                                                    "Smt" => "Smt",
                                                    "Late" => "Late"
                                                );

                                                foreach ($father_titles as $value => $display) {
                                                    $selected = ($father_title === $value) ? 'selected' : '';
                                                    echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                                }
                                                ?>
                                            </select>

                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="father_name" id="father_name"
                                        value="<?= $father_name ?>" maxlength="255" />
                                </div>
                                <?= form_error("father_title") . form_error("father_name") ?>

                            </div>
                            <div class="col-md-4">
                                <label>Aadhar Number / আধাৰ নম্বৰ </label>
                                <input type="text" class="form-control" name="aadhar_no" value="<?= $aadhar_no ?>" />
                                <?= form_error("aadhar_no") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                
                                <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>"
                                    maxlength="10" />
                               

                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-4">
                                <label>E-Mail / ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?= $email ?>" maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant Address/ ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>District/জিলা <span class="text-danger">*</span></label>
                                <select id="district" name="district" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                $districts = array(
                                    "East Karbi Anglong" => "East Karbi Anglong",
                                    "West Karbi Anglong" => "West Karbi Anglong"
                                );

                                foreach ($districts as $value => $display) {
                                    $selected = ($district === $value) ? 'selected' : '';
                                    echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                }
                                ?>
                                </select>
                                <?= form_error("district") ?>
                            </div>

                            <div class="col-md-4">
                                <label>Post Office/ ডাকঘৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="post_office" id="post_office"
                                    value="<?= $post_office ?>" maxlength="255" />
                                <?= form_error("post_office") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Police Station/থানা <span class="text-danger">*</span></label>
                                <select id="police_station" name="police_station" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                        $police_stations = array(
                                            "Anjokpani Police Station" => "Anjokpani Police Station",
                                            "Baithalangso Police Station" => "Baithalangso Police Station",
                                            "Bokajan Police Station" => "Bokajan Police Station",
                                            "Bokulia Police Station" => "Bokulia Police Station",
                                            "Borlongfer Police Station" => "Borlongfer Police Station",
                                            "Borpathar Police Station" => "Borpathar Police Station",
                                            "Chowkohola Police Station" => "Chowkohola Police Station",
                                            "Deithor Police Station" => "Deithor Police Station",
                                            "Dillai Police Station" => "Dillai Police Station",
                                            "Diphu Police Station" => "Diphu Police Station",
                                            "Dokmoka Police Station" => "Dokmoka Police Station",
                                            "Dolamara Police Station" => "Dolamara Police Station",
                                            "Hamren Police Station" => "Hamren Police Station",
                                            "Howraghat Police Station" => "Howraghat Police Station",
                                            "Jirikingding Police Station" => "Jirikingding Police Station",
                                            "Khatkhati Police Station" => "Khatkhati Police Station",
                                            "Kheroni Police Station" => "Kheroni Police Station",
                                            "Manja Police Station" => "Manja Police Station",
                                            "Rongmongwe Police Station" => "Rongmongwe Police Station"
                                        );

                                        foreach ($police_stations as $value => $display) {
                                            $selected = ($value === $police_station) ? 'selected' : '';
                                            echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                        }
                                    ?>
                                </select>
                                <?= form_error("police_station") ?>
                            </div>
                        </div>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Business Details / ব্যৱসায়িক বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Name of the Firm/ ফাৰ্মৰ নাম<span
                                        class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="name_of_firm" id="name_of_firm"
                                    value="<?= $name_of_firm ?>" maxlength="255" />
                                <?= form_error("name_of_firm") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Name of Proprietor/ মালিকৰ নাম<span
                                        class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="name_of_proprietor"
                                    id="name_of_proprietor" value="<?= $name_of_proprietor ?>" maxlength="255" />
                                <?= form_error("name_of_proprietor") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Occupation/Trade/ বৃত্তি/বাণিজ্য<span
                                        class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="occupation_trade" id="occupation_trade"
                                    value="<?= $occupation_trade ?>" maxlength="255" />
                                <?= form_error("occupation_trade") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Community/ সমুদায়<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="community" id="community"
                                    value="<?= $community ?>" maxlength="255" />
                                <?= form_error("community") ?>
                            </div>

                            <div class="col-md-4">
                                <label>Class of Business/ব্যৱসায়ৰ শ্ৰেণী<span class="text-danger">*</span></label>
                                <select name="class_of_business" id="class_of_business" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                    $options = array(
                                        "Wholesale" => "Wholesale",
                                        "Retail" => "Retail",
                                        "Daily Weekly Market" => "Daily Weekly Market",
                                        "Organisation" => "Organisation"
                                    );

                                    foreach ($options as $value => $display) {
                                        $selected = ($class_of_business === $value) ? 'selected' : '';
                                        echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                    }
                                    ?>
                                </select>
                                <?= form_error("class_of_business") ?>
                            </div>

                            <div class="col-md-4">
                                <label>Address/ ঠিকনা<span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" name="address" id="address"
                                    value="<?= $address ?>" maxlength="255" />
                                <?= form_error("address") ?>
                            </div>

                            <div class="col-md-4">
                                <label>Business Location (By Locality)/ ব্যৱসায়িক স্থান (স্থানীয়তা অনুসৰি)<span
                                        class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="business_locality" id="business_locality"
                                    value="<?= $business_locality ?>" maxlength="255" />
                                <?= form_error("business_locality") ?>
                            </div>

                            <div class="col-md-4">
                                <label>Business Location(By Ward No)/ ব্যৱসায়িক স্থান(ৱাৰ্ড নং অনুসৰি)<span
                                        class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="business_word_no" id="business_word_no"
                                    value="<?= $business_word_no ?>" maxlength="255" />
                                <?= form_error("business_word_no") ?>
                            </div>

                            <div class="col-md-4">
                                <label>Special reason for Consideration / বিবেচনাৰ বিশেষ কাৰণ<span
                                        class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="reason_for_consideration"
                                    id="reason_for_consideration" value="<?= $reason_for_consideration ?>"
                                    maxlength="255" />
                                <?= form_error("reason_for_consideration") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Building Rented/Owned / বিল্ডিং ভাড়া/মালিকানাধীন<span class="text-danger">*</span> </label>
                                <select name="rented_owned" id="rented_owned" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                        $options = array(
                                            "Rent" => "Rent",
                                            "Owned" => "Owned"
                                        );

                                        foreach ($options as $value => $display) {
                                            $selected = ($rented_owned === $value) ? 'selected' : '';
                                            echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                        }
                                        ?>
                                </select>
                                <?= form_error("rented_owned") ?>
                            </div>
                            <div class="col-md-4" id="name_of_owner_container" style="display:none;">
                                <label>Name of Owner (if Rented)/মালিকৰ নাম (যদি ভাড়াত দিয়া হয়)<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="name_of_owner" id="name_of_owner"
                                    value="<?= $name_of_owner ?>" maxlength="255" />
                                <?= form_error("name_of_owner") ?>
                            </div>

                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">ATTACH ENCLOSURE(S) / সংলগ্নক সমূহ </legend>
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
                                            <td>Passport size photograph<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="photo_type" class="form-control">
                                                    <option value="Passport size photograph"
                                                        <?=($photo_type === 'Passport size photograph')?'selected':''?>>
                                                        Passport size photograph</option>
                                                </select>
                                                <?= form_error("photo_type") ?>
                                            </td>
                                            <td>
                                                <input name="photo_old" value="<?=$photo?>" type="hidden" />
                                                <div class="file-loading">
                                                    <input id="photo" name="photo" type="file" />
                                                </div>

                                                <div class="row mt-1">

                                                    <div class="col-sm-4">
                                                        <?php if(strlen($photo)){ ?>
                                                        <a href="<?=base_url($photo)?>"
                                                            class="btn font-weight-bold text-success" target="_blank">
                                                            <span class="fa fa-download"></span>View/Download
                                                        </a>
                                                        <?php }//End of if ?>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <div id="live_photo_div" class="row text-center mt-"
                                                            style="display:none;">
                                                            <div id="my_camera" class="col-md-4 text-center"></div>
                                                            <div class="col-md-4 text-center">
                                                                <img id="captured_photo" src="<?=base_url('assets/plugins/webcamjs/no-photo.png')?>" style="width: 320px; height: 240px;" />
                                                            </div>
                                                            <input id="photo_data" name="photo_data" value=""
                                                                type="hidden" />
                                                            <button id="capture_photo" class="btn btn-warning"
                                                                style="margin:2px auto" type="button">Capture
                                                                Photo</button>
                                                        </div>
                                                        <div style="text-align:right">
                                                            <span id="open_camera"
                                                                class="btn btn-sm btn-success text-white"> Capture <img
                                                                    src="<?=base_url('assets/plugins/webcamjs/camera.png')?>"
                                                                    style="width:25px; height: 25px; cursor: pointer" />
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Signature<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="signature_type" class="form-control">
                                                    <option value="Signature"
                                                        <?=($signature === 'Signature')?'selected':''?>>
                                                        Signature</option>
                                                </select>
                                                <?= form_error("signature_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="signature" name="signature" type="file" />
                                                </div>
                                                <?php if(strlen($signature)){ ?>
                                                <a href="<?=base_url($signature)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Identity Proof<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="identity_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Copy of Aadhar Card"
                                                        <?=($identity_proof_type === 'Copy of Aadhar Card')?'selected':''?>>
                                                        Copy of Aadhar Card</option>
                                                    <option value="Copy of PAN Card"
                                                        <?=($identity_proof_type === 'Copy of PAN Card')?'selected':''?>>
                                                        Copy of PAN Card</option>
                                                    <option
                                                        value="Copy of Id proof (Voter ID, Aadhar Card, Pan Card, Passport, of the Employer)"
                                                        <?=($identity_proof_type === 'Copy of Id proof (Voter ID, Aadhar Card, Pan Card, Passport, of the Employer)')?'selected':''?>>
                                                        Copy of Id proof (Voter ID, Aadhar Card, Pan Card, Passport, of
                                                        the Employer)</option>
                                                </select>
                                                <?= form_error("identity_proof_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="identity_proof" name="identity_proof" type="file" />
                                                </div>
                                                <?php if(strlen($identity_proof)){ ?>
                                                <a href="<?=base_url($identity_proof)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="identity_proof_old" value="<?=$identity_proof?>"
                                                    type="hidden" />
                                                <input class="identity_proof" type="hidden" name="identity_proof_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('identity_proof'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Address Proof (DL/Passport/Bank Passbook/ Aadhar Card)<span
                                                    class="text-danger">*</span></td>
                                            <td>
                                                <select name="address_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Driving Licence"
                                                        <?=($address_proof_type === 'Driving Licence')?'selected':''?>>
                                                        Driving Licence</option>
                                                    <option value="Copy of Bank Passbook"
                                                        <?=($address_proof_type === 'Copy of Bank Passbook')?'selected':''?>>
                                                        Copy of Bank Passbook</option>
                                                    <option value="Copy of Aadhar Card"
                                                        <?=($address_proof_type === 'Copy of Aadhar Card')?'selected':''?>>
                                                        Copy of Aadhar Card</option>
                                                    <option value="Copy of Passport"
                                                        <?=($address_proof_type === 'Copy of Passport')?'selected':''?>>
                                                        Copy of Passport</option>
                                                </select>
                                                <?= form_error("address_proof_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="address_proof" name="address_proof" type="file" />
                                                </div>
                                                <?php if(strlen($address_proof)){ ?>
                                                <a href="<?=base_url($address_proof)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="address_proof_old" value="<?=$address_proof?>"
                                                    type="hidden" />
                                                <input class="address_proof" type="hidden" name="address_proof_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('address_proof'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>House Tax Receipt <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="house_tax_reciept_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="House Tax Receipt"
                                                        <?=($house_tax_reciept_type === 'House Tax Receipt')?'selected':''?>>
                                                        House Tax Receipt</option>
                                                </select>
                                                <?= form_error("house_tax_reciept_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="house_tax_reciept" name="house_tax_reciept"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($house_tax_reciept)){ ?>
                                                <a href="<?=base_url($house_tax_reciept)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="house_tax_reciept_old" value="<?=$house_tax_reciept?>"
                                                    type="hidden" />
                                                <input class="house_tax_reciept" type="hidden"
                                                    name="house_tax_reciept_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('house_tax_reciept'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Valid MBTC Room rent deposit
                                                <!-- <span class="text-danger">*</span> -->
                                            </td>
                                            <td>
                                                <select name="room_rent_deposite_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Valid MBTC Room rent deposit"
                                                        <?=($room_rent_deposite_type === 'Valid MBTC Room rent deposit')?'selected':''?>>
                                                        Valid MBTC Room rent deposit</option>
                                                </select>
                                                <?= form_error("room_rent_deposite_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="room_rent_deposite" name="room_rent_deposite"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($room_rent_deposite)){ ?>
                                                <a href="<?=base_url($room_rent_deposite)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="room_rent_deposite_old" value="<?=$room_rent_deposite?>"
                                                    type="hidden" />
                                                <input class="room_rent_deposite" type="hidden"
                                                    name="room_rent_deposite_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('room_rent_deposite'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Special reason for Consideration letter
                                                <!-- <span class="text-danger">*</span> -->
                                            </td>
                                            <td>
                                                <select name="consideration_letter_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Special reason for Consideration letter"
                                                        <?=($consideration_letter_type === 'Special reason for Consideration letter')?'selected':''?>>
                                                        Special reason for Consideration letter</option>
                                                </select>
                                                <?= form_error("consideration_letter_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="consideration_letter" name="consideration_letter"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($consideration_letter)){ ?>
                                                <a href="<?=base_url($consideration_letter)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="consideration_letter_old"
                                                    value="<?=$consideration_letter?>" type="hidden" />
                                                <input class="consideration_letter" type="hidden"
                                                    name="consideration_letter_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('consideration_letter'); ?>
                                            </td>
                                        </tr>

                                        <?php  if($dbrow->form_data->service_id == 'KRBC') {?>

                                        <tr>
                                            <td>Copy of current Business Registration Certificate
                                                <!-- <span class="text-danger">*</span> -->
                                            </td>
                                            <td>
                                                <select name="cur_business_copy_rc_type" class="form-control">
                                                    <!-- <option value="">Select</option> -->
                                                    <option value="Copy of current Business Registration Certificate"
                                                        <?=($cur_business_copy_rc_type === 'Copy of current Business Registration Certificate')?'selected':''?>>
                                                        Copy of current Business Registration Certificate</option>
                                                </select>
                                                <?= form_error("cur_business_copy_rc_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="cur_business_copy_rc" name="cur_business_copy_rc"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($cur_business_copy_rc)){ ?>
                                                <a href="<?=base_url($cur_business_copy_rc)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php } ?>
                                                <input name="cur_business_copy_rc_old"
                                                    value="<?=$cur_business_copy_rc?>" type="hidden" />
                                                <input class="cur_business_copy_rc" type="hidden"
                                                    name="cur_business_copy_rc_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('cur_business_copy_rc'); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
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
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <!-- <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button> -->
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <!-- <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button> -->
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>