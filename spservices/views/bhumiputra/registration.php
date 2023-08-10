<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://localhost/wptbcapis/"; //For testing
$apiServer1 = "https://localhost/castapis/"; //For testing

$startYear = date('Y') - 10;
$endYear =  date('Y');
if ($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $district_id = $dbrow->form_data->district_id;
    $rtps_trans_id = $dbrow->service_data->rtps_trans_id;
    $certificate_language = $dbrow->form_data->certificate_language;
    $application_for = $dbrow->form_data->application_for;
    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $mobile = $dbrow->form_data->mobile; //set_value("mobile");
    $pan_no = $dbrow->form_data->pan_no;
    $email = $dbrow->form_data->email;
    $epic_no = $dbrow->form_data->epic_no;
    $date = $dbrow->form_data->date;
    //$state = $dbrow->form_data->state;
    $fatherName = $dbrow->form_data->fatherName;
    $motherName = $dbrow->form_data->motherName;
    $husbandName = $dbrow->form_data->husbandName;
    $addressLine1 = $dbrow->form_data->addressLine1;
    $addressLine2 = $dbrow->form_data->addressLine2;
    $village = $dbrow->form_data->village;
    $mouza = $dbrow->form_data->mouza;
    $postOffice = $dbrow->form_data->postOffice;
    $policeStation = $dbrow->form_data->policeStation;
    $pinCode = $dbrow->form_data->pinCode;
    $resState = $dbrow->form_data->resState;
    $resAddressLine1 = $dbrow->form_data->resAddressLine1;
    $resAddressLine2 = $dbrow->form_data->resAddressLine2;
    $resVillageTown = $dbrow->form_data->resVillageTown;
    $resMouza = $dbrow->form_data->resMouza;
    $resPostOffice = $dbrow->form_data->resPostOffice;
    $resPoliceStation = $dbrow->form_data->resPoliceStation;
    $resPinCode = $dbrow->form_data->resPinCode;
    $applicantCaste = $dbrow->form_data->applicantCaste;
    $applicantSubCaste = $dbrow->form_data->applicantSubCaste;
    $applicantReligion = $dbrow->form_data->applicantReligion;
    $occupationOfForefather = $dbrow->form_data->occupationOfForefather;
    $isFatherMotherNameInVoterList = $dbrow->form_data->isFatherMotherNameInVoterList;
    $reasonForApplying = $dbrow->form_data->reasonForApplying;
    $resDistrict = $dbrow->form_data->resDistrict;
    $resSubdivision = $dbrow->form_data->resSubdivision;
    $resCircleOffice = $dbrow->form_data->resCircleOffice;
    $houseNumber = $dbrow->form_data->houseNumber;
    $fillUpLanguage = $dbrow->form_data->fillUpLanguage;
    $fatherOrAncestName = $dbrow->form_data->fatherOrAncestName;
    $fatherOrAncestRelation = $dbrow->form_data->fatherOrAncestRelation;
    $fatherOrAncestAddressLine1 = $dbrow->form_data->fatherOrAncestAddressLine1;
    $fatherOrAncestAddressLine2 = $dbrow->form_data->fatherOrAncestAddressLine2;
    $fatherOrAncestState = $dbrow->form_data->fatherOrAncestState;
    $fatherOrAncestDistrict = $dbrow->form_data->fatherOrAncestDistrict;
    $fatherOrAncestSubdivision = $dbrow->form_data->fatherOrAncestSubdivision;
    $fatherOrAncestCircleOffice = $dbrow->form_data->fatherOrAncestCircleOffice;
    $fatherOrAncestMouza = $dbrow->form_data->fatherOrAncestMouza;
    $fatherOrAncestVillage = $dbrow->form_data->fatherOrAncestVillage;
    $fatherOrAncestPoliceStation = $dbrow->form_data->fatherOrAncestPoliceStation;
    $fatherOrAncestPostOffice = $dbrow->form_data->fatherOrAncestPostOffice;
    $fatherOrAncestPincode = $dbrow->form_data->fatherOrAncestPincode;
    $subCasteOfAncestors = $dbrow->form_data->subCasteOfAncestors;
    $epic = $dbrow->form_data->epic;
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $rtps_trans_id = NULL; //set_value("rtps_trans_id");
    $year_of_registration = set_value("year_of_registration");
    $application_for = set_value("application_for");
    $certificate_language = set_value("certificate_language");
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $mobile = $this->session->mobile; //set_value("mobile");
    $pan_no = set_value("pan_no");
    $email = set_value("email");
    $epic_no = set_value("epic_no");
    $date = set_value("date");
    $state = set_value("state");

    $fatherName = set_value("fatherName");
    $motherName = set_value("motherName");
    $husbandName = set_value("husbandName"); //set_value("mobile_number");
    $age = set_value("age");
    $addressLine1 = set_value("addressLine1");
    $addressLine2 = set_value("addressLine2");
    $village = set_value("village");
    $mouza = set_value("mouza");
    $district_id = set_value("district_id") ?? NULL;
    $postOffice = set_value("postOffice");
    $policeStation = set_value("policeStation");
    $pinCode = set_value("pinCode");
    $resState = set_value("resState");
    $resAddressLine1 = set_value("resAddressLine1");
    $resAddressLine2 = set_value("resAddressLine2");
    $resVillageTown = set_value("resVillageTown");
    $resMouza = set_value("resMouza");
    $resPostOffice = set_value("resPostOffice");
    $resPoliceStation = set_value("resPoliceStation");
    $resPinCode = set_value("resPinCode");
    $applicantCaste = set_value("applicantCaste");
    $applicantSubCaste = set_value("applicantSubCaste");
    $applicantReligion = set_value("applicantReligion");
    $occupationOfForefather = set_value("occupationOfForefather");
    $isFatherMotherNameInVoterList = set_value("isFatherMotherNameInVoterList");
    $reasonForApplying = set_value("reasonForApplying"); //set_value("mobile_number");
    $resDistrict = set_value("resDistrict");
    $resSubdivision = set_value("resSubdivision");
    $resCircleOffice = set_value("resCircleOffice");
    $houseNumber = set_value("houseNumber");
    $fillUpLanguage = set_value("fillUpLanguage");
    $fatherOrAncestName = set_value("fatherOrAncestName");
    $fatherOrAncestRelation = set_value("fatherOrAncestRelation");
    $fatherOrAncestAddressLine1 = set_value("fatherOrAncestAddressLine1");
    $fatherOrAncestAddressLine2 = set_value("fatherOrAncestAddressLine2");
    $fatherOrAncestState = set_value("fatherOrAncestState");
    $fatherOrAncestDistrict = set_value("fatherOrAncestDistrict");
    $fatherOrAncestSubdivision = set_value("fatherOrAncestSubdivision");
    $fatherOrAncestCircleOffice = set_value("fatherOrAncestCircleOffice");
    $fatherOrAncestMouza = set_value("fatherOrAncestMouza");
    $fatherOrAncestVillage = set_value("fatherOrAncestVillage");
    $fatherOrAncestPoliceStation = set_value("fatherOrAncestPoliceStation");
    $fatherOrAncestPostOffice = set_value("fatherOrAncestPostOffice");
    $fatherOrAncestPincode = set_value("fatherOrAncestPincode");
    $subCasteOfAncestors = set_value("subCasteOfAncestors");
    $epic = set_value("epic");
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

    td {
        font-size: 14px;
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#printBtn", function() {
            $("#printDiv").print({
                addGlobalStyles: true,
                stylesheet: null,
                rejectWindow: true,
                noPrintSelector: ".no-print",
                iframe: true,
                append: null,
                prepend: null
            });
        });
    });
</script>

<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv" style="background:#E6F1FF">
            <form id="myfrm" method="POST" action="<?= base_url('spservices/bhumiputra/registration/submit') ?>" enctype="multipart/form-data">
                <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
                <input name="rtps_trans_id" value="<?= $rtps_trans_id ?>" type="hidden" />

                <input class="d-none" type="text" value="<?= $district_id ?>" name="district_id" id="district_id" />

                <input id="submit_mode" name="submit_mode" value="" type="hidden" />
                <div class="card shadow-sm" style="background:#E6F1FF">
                    <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                        Application for Caste Certificate<br>
                        ( জাতিপ্ৰমাণপত্ৰৰ বাবে আবেদন )
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

                            <ol style="margin-left: 24px; margin-top: 20px">
                                <li>The certificate will be delivered within 30 Days of application.</li>
                                <li>প্ৰমাণ পত্ৰ ৩০ দিনৰ ভিতৰত(সাধাৰণ) অথবা ৩ দিনৰ ভিতৰত(জৰুৰী) প্ৰদান কৰা হ'ব</li>
                            </ol>


                            <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>
                            <ul style="  margin-left: 24px; margin-top: 10px">
                                <li>1. Rs. 5/- Per page(General Delivery) Rs. 10/- Per page(Urgent Delivery).</li>
                                <li>১. প্ৰতিটো পৃষ্ঠাৰ বাবে ৫ টকাকৈ ( সাধাৰণ ) / ১0 টকাকৈ (জৰুৰীকালীন)</li>
                                <li>2. RTPS fee of rupees ৩০/- per appilcation.</li>
                                <li>২. প্ৰতিখন আবেদনৰ বাবত 3০ টকা Rtps ফিছ</li>
                            </ul>

                            <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                            <ul style="  margin-left: 24px; margin-top: 10px">
                                <li>1. All the * marked fields are mandatory and need to be filled up..</li>
                                <li>১. * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব</li>
                                <li>2. Payment has to be made online and it is to be done when payment request is made by Official.</li>
                                <li>২. কাৰ্যালয়ৰ পৰা অনুৰোধ আহিলে মাছুল অনলাইনত আদায় দিব লাগিব</li>
                            </ul>

                        </fieldset>
                        <fieldset class=" border border-success" style="margin-top:40px">
                            <legend class="h5">Language of certificate  <span class="text-danger ">*</span></legend>
                            <div class="d-flex space-x-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="certificate_language" id="english" value="English" <?= ($certificate_language === "English") ? 'checked' : '' ?> />
                                    <label class="form-check-label" for="english">English</lable>
                                </div>
                                <div class="form-check ml-3">
                                    <input class="form-check-input" type="radio" name="certificate_language" id="assamese" value="Assamese" <?= ($certificate_language === "Assamese") ? 'checked' : '' ?> />
                                    <label class="form-check-label" for="assamese">Assamese</lable>
                                </div>
                                <div class="form-check ml-3">
                                    <input class="form-check-input" type="radio" name="certificate_language" id="bengali" value="Bengali" <?= ($certificate_language === "Bengali") ? 'checked' : '' ?> />
                                    <label class="form-check-label" for="bengali">Bengali</lable>
                                </div>
                                <div class="form-check ml-3">
                                    <input class="form-check-input" type="radio" name="certificate_language" id="bodo" value="Bodo" <?= ($certificate_language === "Bodo") ? 'checked' : '' ?> />
                                    <label class="form-check-label" for="bodo">Bodo</lable>
                                </div>
                                <?= form_error("certificate_language") ?>
                            </div>
                            <!-- </div> -->
                        </fieldset>


                        <fieldset class="border border-success" style="margin-top:40px">
                            <legend class="h5">Applicant&apos;s Detail </legend>
                            <div class="row form-group">

                                <div class="col-md-6">
                                    <label>Application For <span class="text-danger">*</span> </label>
                                    <select name="application_for" id="application_for" class="form-control appfor" required>

                                    </select>
                                    <?= form_error("application_for") ?>
                                </div>
                                <div class="col-md-6">
                                    <label>Applicant's Name<span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?= $applicant_name ?>" maxlength="255" required />
                                    <?= form_error("applicant_name") ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>Gender <span class="text-danger">*</span> </label>
                                    <select name="applicant_gender" class="form-control" required>
                                        <option value="">Please Select</option>
                                        <option value="Male" <?= ($applicant_gender === "Male") ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= ($applicant_gender === "Female") ? 'selected' : '' ?>>Female</option>
                                        <option value="Other" <?= ($applicant_gender === "Other") ? 'selected' : '' ?>>Other</option>
                                    </select>
                                    <?= form_error("applicant_gender") ?>
                                </div>

                                <div class="col-md-6">
                                    <label>Mobile Number <span class="text-danger">*</span> </label>
                                    <?php if ($usser_type === "user") { ?>
                                        <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" readonly maxlength="10" />
                                    <?php } else { ?>
                                        <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" maxlength="10" />
                                    <?php } ?>

                                    <?= form_error("mobile") ?>
                                </div>

                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>PAN </label>
                                    <input type="text" class="form-control" name="pan_no" id="pan_no" value="<?= $pan_no ?>" maxlength="10" />
                                    <?= form_error("pan_no") ?>
                                </div>

                                <div class="col-md-6">
                                    <label>E-mail</label>
                                    <input type="text" class="form-control" name="email" id="email" value="<?= $email ?>" maxlength="255" />
                                    <?= form_error("email") ?>
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>EPIC No</label>
                                    <input type="text" class="form-control" name="epic_no" id="epic_no" value="<?= $epic_no ?>" maxlength="255" />
                                    <?= form_error("epic_no") ?>
                                </div>

                                <div class="col-md-6">
                                    <label>Date of Birth<span class="text-danger">*</span> </label>
                                    <input type="date" class="form-control" name="date" id="date" value="<?= $date ?>" maxlength="255" required/>
                                    <?= form_error("date") ?>
                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-md-6">
                                    <label> Caste/Tribe/Community<span class="text-danger">*</span> </label>
                                    <select name="applicantCaste" class="form-control caste" required>

                                        <option value="<?= $applicantCaste ?>">
                                            <?= strlen($applicantCaste) ? $applicantCaste : "Caste/Tribe/Community" ?>
                                        </option>

                                        <?= form_error("applicantCaste") ?>
                                    </select>
                                    <?= form_error("applicantCaste") ?>
                                </div>


                                <div class="col-md-6">
                                    <label> Sub Caste<span class="text-danger">*</span> </label>
                                    <select name="applicantSubCaste" class="form-control subcaste">

                                        <option value="<?= $applicantSubCaste ?>">
                                            <?= strlen($applicantSubCaste) ? $applicantSubCaste : "Caste/Tribe/Community" ?>
                                        </option>

                                        <?= form_error("applicantSubCaste") ?>
                                    </select>
                                    <?= form_error("applicantSubCaste") ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>Father's Name<span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="fatherName" id="fatherName" value="<?= $fatherName ?>" maxlength="100"required />
                                    <?= form_error("fatherName") ?>
                                </div>

                                <div class="col-md-6">
                                    <label>Mother's Name<span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="motherName" id="motherName" value="<?= $motherName ?>" maxlength="100" required/>
                                    <?= form_error("motherName") ?>
                                </div>
                            </div>
                            <div class="row form-group">

                                <div class="col-md-6">



                                    <input type="hidden" class="form-control" name="husbandName" id="husbandName" value="NA" maxlength="255" />
                                    <?= form_error("husbandName") ?>
                                </div>

                                <div class="col-md-6">

                                    <input type="hidden" class="form-control" name="age" id="age" value="NA" />
                                    <?= form_error("age") ?>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="border border-success" style="margin-top:40px">
                            <legend class="h5">Address</legend>
                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>Address Line1 </label>
                                    <input type="text" class="form-control" name="resAddressLine1" value="<?= $resAddressLine1 ?>" maxlength="100" />
                                    <?= form_error("resAddressLine1") ?>
                                </div>
                                <div class="col-md-6">
                                    <label>Address Line2 </label>
                                    <input type="text" class="form-control" name="resAddressLine2" value="<?= $resAddressLine2 ?>" maxlength="100" />
                                    <?= form_error("resAddressLine2") ?>
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>State <span class="text-danger">*</span> </label>
                                    <select name="resState" class="form-control">
                                        <option value="Assam" selected="selected">Assam</option>
                                    </select>
                                    <?= form_error("resState") ?>

                                </div>

                                <div class="col-md-6">
                                    <label>District <span class="text-danger">*</span> </label>
                                    <select name="resDistrict" class="form-control dists" id="resDistrict" required>
                                        <option value=" <?= $resDistrict ?>">
                                            <?= strlen($resDistrict) ? $resDistrict : "Select District" ?>
                                        </option>
                                    </select>
                                    <?php
                                    if (form_error("resDistrict")) {
                                        echo form_error("resDistrict");
                                    } elseif (form_error("district_id")) {
                                        echo form_error("district_id");
                                    }
                                    ?>
                                    <!-- <?= form_error("resDistrict") || form_error("district_id") ?> -->
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>subdivision<span class="text-danger">*</span> </label>
                                    <select name="resSubdivision" class="form-control SubDivisionName" required>

                                        <option value="<?= $resSubdivision ?>">
                                            <?= strlen($resSubdivision) ? $resSubdivision : "subdivision" ?>
                                        </option>

                                        <?= form_error("resSubdivision") ?>
                                    </select>
                                    <?= form_error("resSubdivision") ?>


                                </div>
                                <div class="col-md-6">
                                    <label>Circle Office<span class="text-danger">*</span> </label>
                                    <select name="resCircleOffice" class="form-control circle_office" required>

                                        <option value="<?= $resCircleOffice ?>">
                                            <?= strlen($resCircleOffice) ? $resCircleOffice : "Circle Office" ?>
                                        </option>

                                        <?= form_error("resCircleOffice") ?>
                                    </select>
                                    <?= form_error("resCircleOffice") ?>

                                    </select>
                                    <?= form_error("resCircleOffice") ?>
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>Mouza<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="resMouza" value="<?= $resMouza ?>" maxlength="100" required/>
                                    <?= form_error("resMouza") ?>
                                </div>
                                <div class="col-md-6">
                                    <label>Village<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="resVillageTown" value="<?= $resVillageTown ?>" maxlength="100" required />
                                    <?= form_error("resVillageTown") ?>
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>Police Station<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="resPoliceStation" value="<?= $resPoliceStation ?>" maxlength="100" required />
                                    <?= form_error("resPoliceStation") ?>
                                </div>
                                <div class="col-md-6">
                                    <label>Post Office<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="resPostOffice" value="<?= $resPostOffice ?>" maxlength="100" required />
                                    <?= form_error("resPostOffice") ?>
                                </div>
                                <div class="col-md-6">
                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <label>Pin Code<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="resPinCode" value="<?= $resPinCode ?>" maxlength="6" required/>
                                    <?= form_error("resPinCode") ?>
                                </div>
                        </fieldset>


                        <fieldset class="hide">
                            <legend>Present Address:</legend>
                            <div class="row form-group">
                                <div class="col-md-6">

                                    <input type="text" name="addressLine1" placeholder="Address Line1" value=""> <br>

                                    <?= form_error("addressLine1") ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-6">

                                    <input type="text" name="addressLine2" placeholder="Address Line2" value=""> <br>
                                    <?= form_error("addressLine2") ?>

                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="village" placeholder="Village or Town" value=""> <br>
                                    <?= form_error("village") ?>

                                </div>
                                <div class="col-md-6">

                                    <input type="text" name="mouza" placeholder="Mouza" value=""> <br>
                                    <?= form_error("mouza") ?>

                                </div>
                            </div>

                            <div class="row form-group">
                                <div class="col-md-6">
                                    <input type="text" name="postOffice" placeholder="Post Office" value=""> <br>
                                    <?= form_error("postOffice") ?>

                                </div>


                                <div class="col-md-6">
                                    <input type="text" name="policeStation" placeholder="Police Station " value=""> <br>
                                    <?= form_error("policeStation") ?>

                                </div>
                                <div class="col-md-6">


                                    <input type="text" name="pinCode" placeholder="Pin Code" value=""> <br>
                                    <?= form_error("pinCode") ?>

                                </div>
                            </div>

                        </fieldset>


                        <div class="row form-group">
                            <div class="col-md-6">

                                <input type="hidden" class="form-control" name="applicantReligion" id="applicantReligion" value="NA" maxlength="255" />
                                <?= form_error("applicantReligion") ?>
                            </div>


                            <div class="col-md-6">



                                <input type="hidden" class="form-control" name="occupationOfForefather" id="occupationOfForefather" value="NA" maxlength="255" />
                                <?= form_error("occupationOfForefather") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">

                                <input type="hidden" class="form-control" name="isFatherMotherNameInVoterList" id="isFatherMotherNameInVoterList" value="Yes" maxlength="255" />
                                <?= form_error("isFatherMotherNameInVoterList") ?>
                            </div>

                            <div class="col-md-6">

                                <input type="hidden" class="form-control" name="reasonForApplying" id="reasonForApplying" value="NA" maxlength="255" />
                                <?= form_error("reasonForApplying") ?>
                            </div>
                        </div>

                        <div class="row form-group">


                            <div class="col-md-6">


                                <input type="hidden" class="form-control" name="houseNumber" id="houseNumber" value="NA" maxlength="255" />
                                <?= form_error("houseNumber") ?>
                            </div>

                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">


                                <input type="hidden" class="form-control" name="fillUpLanguage" id="fillUpLanguage" value="NA" maxlength="255" />
                                <?= form_error("fillUpLanguage") ?>
                            </div>


                            <div class="col-md-6">



                                <input type="hidden" class="form-control" name="fatherOrAncestName" id="fatherOrAncestName" value="NA" maxlength="255" />
                                <?= form_error("fatherOrAncestName") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">


                                <input type="hidden" class="form-control" name="fatherOrAncestRelation" id="fatherOrAncestRelation" value="NA" maxlength="255" />
                                <?= form_error("fatherOrAncestRelation") ?>
                            </div>


                            <div class="col-md-6">

                                <input type="hidden" class="form-control" name="fatherOrAncestAddressLine1" id="fatherOrAncestAddressLine1" value="NA" maxlength="255" />
                                <?= form_error("fatherOrAncestAddressLine1") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">


                                <input type="hidden" class="form-control" name="fatherOrAncestAddressLine2" id="fatherOrAncestAddressLine2" value="NA" maxlength="255" />
                                <?= form_error("fatherOrAncestAddressLine2") ?>
                            </div>


                            <div class="col-md-6">

                                <input type="hidden" class="form-control" name="fatherOrAncestState" id="fatherOrAncestState" value="NA" maxlength="255" />
                                <?= form_error("fatherOrAncestState") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">


                                <input type="hidden" class="form-control" name="fatherOrAncestDistrict" id="fatherOrAncestDistrict" value="NA" maxlength="255" />
                                <?= form_error("fatherOrAncestDistrict") ?>
                            </div>


                            <div class="col-md-6">


                                <input type="hidden" class="form-control" name="fatherOrAncestSubdivision" id="fatherOrAncestSubdivision" value="NA" maxlength="255" />
                                <?= form_error("fatherOrAncestSubdivision") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">


                                <input type="hidden" class="form-control" name="fatherOrAncestCircleOffice" id="fatherOrAncestCircleOffice" value="NA" maxlength="255" />
                                <?= form_error("fatherOrAncestCircleOffice") ?>
                            </div>


                            <div class="col-md-6">


                                <input type="hidden" class="form-control" name="fatherOrAncestMouza" id="fatherOrAncestMouza" value="NA" maxlength="255" />
                                <?= form_error("fatherOrAncestMouza") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">


                                <input type="hidden" class="form-control" name="fatherOrAncestVillage" id="fatherOrAncestVillage" value="NA" maxlength="255" />
                                <?= form_error("fatherOrAncestVillage") ?>
                            </div>


                            <div class="col-md-6">



                                <input type="hidden" class="form-control" name="fatherOrAncestPoliceStation" id="fatherOrAncestPoliceStation" value="NA" maxlength="255" />
                                <?= form_error("fatherOrAncestPoliceStation") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">



                                <input type="hidden" class="form-control" name="fatherOrAncestPostOffice" id="fatherOrAncestPostOffice" value="NA" maxlength="255" />
                                <?= form_error("fatherOrAncestPostOffice") ?>
                            </div>

                            <div class="col-md-6">



                                <input type="hidden" class="form-control" name="fatherOrAncestPincode" id="fatherOrAncestPincode" value="NA" maxlength="255" />
                                <?= form_error("fatherOrAncestPincode") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">


                                <input type="hidden" class="form-control" name="subCasteOfAncestors" id="subCasteOfAncestors" value="NA" maxlength="255" />
                                <?= form_error("subCasteOfAncestors") ?>
                            </div>


                            <div class="col-md-6">



                                <input type="hidden" class="form-control" name="epic" id="epic" value="NA" maxlength="255" />
                                <?= form_error("epic") ?>
                            </div>
                        </div>


                        <div class="col-md-6">
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
                    <button class="btn btn-success frmbtn" id="SAVE">
                        <i class="fa fa-check"></i> Next
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
    </div>
    <!--End of .container-->
</main>
<script type="text/javascript">
    $(document).ready(function() {
        let val = '<?= $resDistrict ?>';


        $.getJSON("<?= $apiServer ?>district_list.php", function(data) {
            let selectOption = '';
            $.each(data.records, function(key, value) {
                // if (val === value.resDistrict) {
                //     selectOption += '<option value="' + value.district_name + '" data-district_id="' + value.district_id + '">' + value.district_name + '</option>';
                // } else {
                selectOption += '<option value="' + value.district_name + '" data-district_id="' + value.district_id + '">' + value.district_name + '</option>';
                // }
            });
            $('.dists').append(selectOption);
        });

    });

    $(document).on("change", ".dists", function() {
        let selectedVal = $(this).val();
        let district_id = $(this).find('option:selected').attr("data-district_id");
        $("#district_id").val(district_id);
        if (selectedVal.length) { //alert(selectedVal);
            var myObject = new Object();
            myObject.district_id = selectedVal; //alert(JSON.stringify(myObject));
            $.getJSON("<?= $apiServer ?>sub_division_list.php?jsonbody=" + JSON.stringify(myObject), function(data) {
                let selectOption = '';
                $('.SubDivisionName').empty().append('<option value="">Select a sub division</option>')
                $.each(data.records, function(key, value) {
                    selectOption += '<option value="' + value.subdiv_name + '">' + value.subdiv_name + '</option>';
                });
                $('.SubDivisionName').append(selectOption);
            });
        }
    });

    $(document).on("change", ".SubDivisionName", function() {
        let selectedVal = $(this).val();
        if (selectedVal.length) { //alert(selectedVal);
            var myObject = new Object();
            myObject.subdiv_id = selectedVal; //alert(JSON.stringify(myObject));
            $.getJSON("<?= $apiServer ?>revenue_circle_list.php?jsonbody=" + JSON.stringify(myObject), function(data) {
                let selectOption = '';
                $('.circle_office').empty().append('<option value="">Select a Circle Office</option>')
                $.each(data.records, function(key, value) {
                    selectOption += '<option value="' + value.circle_name + '">' + value.circle_name + '</option>';
                });
                $('.circle_office').append(selectOption);
            });
        }
    });


    $(document).ready(function() {
        // alert("OK");
        let val = '<?= $application_for ?>';
        $.getJSON("<?= $apiServer1 ?>caste_list.php", function(data) {
            let selectOption = '';
            $('.appfor').empty().append('<option value="">Application For</option>')
            $.each(data.records, function(key, value) {
                if (val === value.caste_name) {
                    selectOption += '<option selected value="' + value.caste_name + '">' + value.dis_name + '</option>';
                } else {
                    selectOption += '<option value="' + value.caste_name + '">' + value.dis_name + '</option>';
                }

            });
            $('.appfor').append(selectOption);
        });
    });

    $(document).on("change", ".appfor", function() {
        let val = '<?= $applicantCaste ?>';
        let selectedVal = $(this).val();
        if (selectedVal.length) { //alert(selectedVal);
            var myObject = new Object();
            myObject.caste_name = selectedVal; //alert(JSON.stringify(myObject));
            $.getJSON("<?= $apiServer1 ?>community_list.php?jsonbody=" + JSON.stringify(myObject), function(data) {
                let selectOption = '';
                $('.caste').empty().append('<option value="">Select a Caste</option>')
                $.each(data.records, function(key, value) {
                    if (val === value.cname) {
                        selectOption += '<option value="' + value.cname + '">' + value.cname + '</option>';
                    } else {
                        selectOption += '<option value="' + value.cname + '">' + value.cname + '</option>';
                    }

                });
                $('.caste').append(selectOption);
            });
        }
    });

    $(document).on("change", ".caste", function() {
        let selectedVal = $(this).val();
        if (selectedVal.length) { //alert(selectedVal);
            var myObject = new Object();
            myObject.community_name = selectedVal; //alert(JSON.stringify(myObject));

            $.getJSON("<?= $apiServer1 ?>subcategory_list.php?jsonbody=" + JSON.stringify(myObject), function(data) {
                    let selectOption = '';
                    $('.subcaste').empty().append('<option value="">Select a Sub Caste</option>')

                    $.each(data.records, function(key, value) {
                        selectOption += '<option value="' + value.sname + '(' + selectedVal + ')">' + value.sname + '</option>';
                    });
                    $('.subcaste').append(selectOption);
                })
                .fail(function(jqXHR, textStatus, errorThrown) {

                    let selectOption = '';
                    $('.subcaste').empty().append('<option value="">Select a Sub Caste</option>')
                });
        }
    });


    //FRS conditions
    $(document).on("change", ".appfor", function() {
        var appliedForVal = $(this).val();
        $('#resDistrict').val('');
        if (appliedForVal == 'ST(H)') {
            $('#resDistrict').children('option').hide().prop('disabled', true);
            setTimeout(showDistricts(), 200);
        } else if (appliedForVal == "ST(P)") {
            $('#resDistrict').children('option').show().prop('disabled', false);
            setTimeout(hideDistricts(), 200);
        } else {
            $('#resDistrict').children('option').show().prop('disabled', false);
        }

    });

    $(document).on("change", ".caste", function() {
        var CasteVal = $(this).val();
        $('#resDistrict').val('');
        if (CasteVal == 'Ganak in Districts of Cachar,Karimganj,Hailakandi') {
            $('#resDistrict').children('option').hide().prop('disabled', true);
            setTimeout(showObcDistricts(), 200);
        } else if (CasteVal == "Kumar, Rudra Paul of districts of Cachar, Kamriganj & Hailakandi") {
            $('#resDistrict').children('option').hide().prop('disabled', true);
            setTimeout(showObcDistricts(), 200);
        } else {
            $('#resDistrict').children('option').show().prop('disabled', false);
        }
    });



    //SHOW DISTRICTS
    function showDistricts() {
        $('#resDistrict').children('option[value=" "]').show().prop('disabled', false).prop('selected', true);
        $('#resDistrict').children('option[value="Dima Hasao"]').show().prop('disabled', false);
        $('#resDistrict').children('option[value="Karbi Anglong"]').show().prop('disabled', false);
        $('#resDistrict').children('option[value="West Karbi Anglong"]').show().prop('disabled', false);
    }
    //SHOW DISTRICTS FOR OBC
    function showObcDistricts() {
        $('#resDistrict').children('option[value=" "]').show().prop('disabled', false).prop('selected', true);
        $('#resDistrict').children('option[value="Cachar"]').show().prop('disabled', false);
        $('#resDistrict').children('option[value="Karimganj"]').show().prop('disabled', false);
        $('#resDistrict').children('option[value="Hailakandi"]').show().prop('disabled', false);
    }

    //HIDE 3 DISTRICTS
    function hideDistricts() {
        $('#resDistrict').children('option[value=" "]').show().prop('disabled', false).prop('selected', true);
        $('#resDistrict').children('option[value="Dima Hasao"]').hide().prop('disabled', true);
        $('#resDistrict').children('option[value="Karbi Anglong"]').hide().prop('disabled', true);
        $('#resDistrict').children('option[value="West Karbi Anglong"]').hide().prop('disabled', true);
    }
</script>


<script type="text/javascript">
    var presentAddressBox = $('.hide');
    //$(checkbox).on('change', function(event) {
    // $(presentAddressBox).toggleClass('hide');
    //});

    //   Populate present address on Form Submit
    $('.frmbtn').on('click', function(event) {
        event.preventDefault();

        //     DO INPUT VALIDATION FIRST!!

        $('[name="addressLine1"]').val($('[name="resAddressLine1"]').val()); //address 1
        $('[name="addressLine2"]').val($('[name="resAddressLine2"]').val());
        $('[name="village"]').val($('[name="resVillageTown"]').val());
        $('[name="mouza"]').val($('[name="resMouza"]').val());

        $('[name="postOffice"]').val($('[name="resPostOffice"]').val()); //address 1
        $('[name="policeStation"]').val($('[name="resPoliceStation"]').val());
        $('[name="pinCode"]').val($('[name="resPinCode"]').val());


        //     postoffice

        // $()



        //     Finally,  submit the form, Uncomment 
        // $(this).submit();

        console.log($(this));
        $('#myfrm').submit();

    });



    //});
</script>