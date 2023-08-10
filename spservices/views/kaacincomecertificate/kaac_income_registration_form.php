<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://artpskaac.in/"; //For testing
// pre($dbrow);


if ($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $pageTitle = $dbrow->service_data->service_name;
    $pageTitleId = $dbrow->form_data->service_id;
    $applicant_title = $dbrow->form_data->applicant_title ?? set_value("applicant_title");
    $first_name = $dbrow->form_data->first_name ?? set_value("first_name");
    $last_name = $dbrow->form_data->last_name ?? set_value("last_name");
    $gender = $dbrow->form_data->applicant_gender ?? set_value("gender");
    $father_title = $dbrow->form_data->father_title ?? set_value("father_title");
    $father_name = $dbrow->form_data->father_name ?? set_value("father_name");
    $mobile = $dbrow->form_data->mobile ?? set_value("mobile");
    $email = $dbrow->form_data->email ?? set_value("email");

    $circle_office = $dbrow->form_data->circle_office ?? set_value("circle_office");
    $mouza_name = $dbrow->form_data->mouza_name ?? set_value("mouza_name");
    $revenue_village = $dbrow->form_data->revenue_village ?? set_value("revenue_village");
    $police_station = $dbrow->form_data->police_station ?? set_value("police_station");
    $police_station_name = $dbrow->form_data->police_station_name ?? set_value("police_station_name");
    $post_office = $dbrow->form_data->post_office ?? set_value("post_office");

    $applicant_category = $dbrow->form_data->applicant_category ?? set_value("applicant_category");
    $applicant_category_text = $dbrow->form_data->applicant_category_text ?? set_value("applicant_category_text");

    $address_proof_type = isset($dbrow->form_data->address_proof_type) ? $dbrow->form_data->address_proof_type : '';
    $address_proof = isset($dbrow->form_data->address_proof) ? $dbrow->form_data->address_proof : '';
    $identity_proof_type = isset($dbrow->form_data->identity_proof_type) ? $dbrow->form_data->identity_proof_type : '';
    $identity_proof = isset($dbrow->form_data->identity_proof) ? $dbrow->form_data->identity_proof : '';
    $land_patta_copy_type = isset($dbrow->form_data->land_patta_copy_type) ? $dbrow->form_data->land_patta_copy_type : '';
    $land_patta_copy = isset($dbrow->form_data->land_patta_copy) ? $dbrow->form_data->land_patta_copy : '';
    $updated_land_revenue_receipt_type = isset($dbrow->form_data->updated_land_revenue_receipt_type) ? $dbrow->form_data->updated_land_revenue_receipt_type : '';
    $updated_land_revenue_receipt = isset($dbrow->form_data->updated_land_revenue_receipt) ? $dbrow->form_data->updated_land_revenue_receipt : '';
    $salary_slip_type = isset($dbrow->form_data->salary_slip_type) ? $dbrow->form_data->salary_slip_type : '';;
    $salary_slip = isset($dbrow->form_data->salary_slip) ? $dbrow->form_data->salary_slip : '';;
    $other_doc_type = isset($dbrow->form_data->other_doc_type) ? $dbrow->form_data->other_doc_type : '';;
    $other_doc = isset($dbrow->form_data->other_doc) ? $dbrow->form_data->other_doc : '';

    $periodic_patta_no = isset($dbrow->form_data->periodic_patta_no) ? $dbrow->form_data->periodic_patta_no : '';
    $dag_no = $dbrow->form_data->dag_no ?? set_value("dag_no");
    $land_area_bigha = $dbrow->form_data->land_area_bigha ?? set_value("land_area_bigha");
    $land_area_katha = $dbrow->form_data->land_area_katha ?? set_value("land_area_katha");
    $land_area_loosa = $dbrow->form_data->land_area_loosa ?? set_value("land_area_loosa");
    $land_area_sq_ft = $dbrow->form_data->land_area_sq_ft ?? set_value("land_area_sq_ft");
    $patta_type = $dbrow->form_data->patta_type ?? set_value("patta_type");
    $circle_office_name = $dbrow->form_data->circle_office_name ?? set_value("circle_office_name");
    $mouza_office_name = $dbrow->form_data->mouza_office_name ?? set_value("mouza_office_name");
    $revenue_village_name = $dbrow->form_data->revenue_village_name ?? set_value("revenue_village_name");
    $patta_type_name = $dbrow->form_data->patta_type_name ?? set_value("patta_type_name");
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL; //set_value("rtps_trans_id");

    $applicant_title = set_value("applicant_title");
    $father_title = set_value("father_title");

    $first_name = set_value("first_name");
    $last_name = set_value("last_name");
    $gender = set_value("gender");
    $father_name = set_value("father_name");

    $mobile = $this->session->mobile; //set_value("mobile_number");
    $email = set_value("email");

    $circle_office = set_value("circle_office");
    $mouza_name = set_value("mouza_name");
    $revenue_village = set_value("revenue_village");

    $applicant_category = set_value("applicant_category");
    $applicant_category_text = set_value("applicant_category_text");

    $address_proof_type = "";
    $address_proof = "";
    $identity_proof_type = "";
    $identity_proof = "";
    $salary_slip_type = "";
    $salary_slip = "";
    $land_patta_copy_type = "";
    $land_patta_copy = "";
    $updated_land_revenue_receipt_type = "";
    $updated_land_revenue_receipt = "";
    $other_doc_type = "";
    $other_doc = "";


    $circle_office_name = set_value("circle_office_name");
    $mouza_office_name = set_value("mouza_office_name");
    $revenue_village_name = set_value("revenue_village_name");
    $patta_type_name = set_value("patta_type_name");

    $dag_no = set_value("dag_no");
    $land_area_bigha = set_value("land_area_bigha");
    $land_area_loosa = set_value("land_area_loosa");
    $land_area_katha = set_value("land_area_katha");
    $land_area_sq_ft = set_value("land_area_sq_ft");
    $police_station = set_value("police_station");
    $police_station_name = set_value("police_station_name");
    $post_office = set_value("post_office");
    $periodic_patta_no = set_value("periodic_patta_no");
    $patta_type = set_value("patta_type");
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

    .blink {
        animation: blinker 2s linear infinite;
    }

    @keyframes blinker {
        50% {
            opacity: 0;
        }
    }

    .message {
        color: red;
        font-weight: bold;
    }

    .instructions li {
        font-size: 13px;
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
            console.log(clickedBtn);
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
                        console.log("Asdasdasd");
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/kaac-incomecertificate') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="service_name" value="<?= $pageTitle ?>" type="hidden" />
            <input name="service_id" value="<?= $pageTitleId ?>" type="hidden" />
            <input name="address_proof_type" value="<?= $address_proof_type ?>" type="hidden" />
            <input name="address_proof" value="<?= $address_proof ?>" type="hidden" />
            <input name="identity_proof_type" value="<?= $identity_proof_type ?>" type="hidden" />
            <input name="identity_proof" value="<?= $identity_proof ?>" type="hidden" />
            <input name="salary_slip_type" value="<?= $salary_slip_type ?>" type="hidden" />
            <input name="salary_slip" value="<?= $salary_slip ?>" type="hidden" />
            <input name="land_patta_copy_type" value="<?= $land_patta_copy_type ?>" type="hidden" />
            <input name="land_patta_copy" value="<?= $land_patta_copy ?>" type="hidden" />
            <input name="updated_land_revenue_receipt_type" value="<?= $updated_land_revenue_receipt_type ?>" type="hidden" />
            <input name="updated_land_revenue_receipt" value="<?= $updated_land_revenue_receipt ?>" type="hidden" />
            <input name="other_doc_type" value="<?= $other_doc_type ?>" type="hidden" />
            <input name="other_doc" value="<?= $other_doc ?>" type="hidden" />

            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b><?= $pageTitle ?><br>
                            আয়ৰ প্ৰমাণ পত্ৰ প্ৰদান <b>
                    </h4>
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
                        <strong style="font-size:16px;  margin-top: 10px">Documents to be enclosed with the application:/আবেদনৰ সৈতে সংলগ্ন কৰিবলগীয়া নথি-পত্ৰ :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                            <li>1. Address Proof [Mandatory]</li>
                            <li>১. ঠিকনাৰ প্ৰমাণ [বাধ্যতামূলক]</li>
                            <li>2. Identity Proof [Mandatory]</li>
                            <li>২. পৰিচয় প্ৰমাণ [বাধ্যতামূলক] </li>
                            <li>3. Salary slip [Mandatory (যদি নিয়োজিত হয়)]</li>
                            <li>৩. দৰমহাৰ স্লিপ [বাধ্যতামূলক] </li>
                            <li>4. Land patta copy [Mandatory]</li>
                            <li>৪. ভূমি পট্টা কপি [বাধ্যতা]।</li>
                            <li>5. Updated Land revenue receipt [Mandatory]</li>
                            <li>৫. আপডেট কৰা ভূমি ৰাজহ ৰচিদ [বাধ্যতামূলক]</li>
                            <li>6. Other documents (Ration card/Job Card/Trading License/Goan Bura Certificate) [Mandatory (if not employed)]</li>
                            <li>৬. অন্যান্য নথিপত্ৰ (ৰেচন কাৰ্ড/জব কাৰ্ড/ট্ৰেডিং লাইচেন্স/গোৱা বুঢ়া প্ৰমাণপত্ৰ) [বাধ্যতামূলক (যদি নিয়োজিত নহয়)]</li>

                        </ul>
                        <strong style="font-size:16px;  margin-top: 10px">Charge Details:/চাৰ্জৰ বিৱৰণ :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                            <li>1. For Council receipt Rs: 2/- per certificate</li>
                            <li>১. কাউন্সিলৰ ৰচিদৰ বাবে প্ৰতি প্ৰমাণ পত্ৰৰ বাবে ২/- টকা</li>
                            <li>2. For User Charge Rs: 10/- per certificate</li>
                            <li>২. ইউজাৰ চাৰ্জৰ বাবে : প্ৰতি প্ৰমাণ পত্ৰত ১০/- টকা</li>

                        </ul>
                        <strong style="font-size:16px;  margin-top: 10px">Service Delivery Timeline: 07 days (If there is no complaint / objection) <br>সেৱা প্ৰদানৰ সময়সীমা: ০৭ দিন (যদি কোনো অভিযোগ / আপত্তি নাথাকে)</strong>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details/ আবেদনকাৰীৰ তথ্য </legend>

                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Applicant&apos;s First Name/ আবেদনকাৰীৰ প্ৰথম নাম<span class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text p-1">
                                            <select name="applicant_title">
                                                <option value="">Select</option>
                                                <option value="Mr" <?= ($applicant_title === "Mr") ? 'selected' : '' ?>>Mr
                                                </option>
                                                <option value="Mrs" <?= ($applicant_title === "Mrs") ? 'selected' : '' ?>>
                                                    Mrs</option>
                                                <option value="Miss" <?= ($applicant_title === "Miss") ? 'selected' : '' ?>>
                                                    Miss</option>
                                                <option value="Shrimati" <?= ($applicant_title === "Shrimati") ? 'selected' : '' ?>>
                                                    Smt</option>
                                                <option value="Dr" <?= ($applicant_title === "Dr") ? 'selected' : '' ?>>
                                                    Dr</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="first_name" id="first_name" value="<?= $first_name ?>" maxlength="255" />
                                </div>
                                <?= form_error("applicant_title") . form_error("first_name") ?>

                            </div>

                            <div class="col-md-4">
                                <label>Applicant&apos;s Last Name/ আবেদনকাৰীৰ উপাধি<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="last_name" id="last_name" value="<?= $last_name ?>" maxlength="255" />
                                <?= form_error("last_name") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?= ($gender === "Male") ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= ($gender === "Female") ? 'selected' : '' ?>>Female</option>
                                    <option value="Transgender" <?= ($gender === "Transgender") ? 'selected' : '' ?>>Transgender</option>
                                </select>
                                <?= form_error("gender") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Father&apos;s/Husband&apos;s Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text p-1">
                                            <select name="father_title">
                                                <option value="">Select</option>
                                                <option value="Mr" <?= ($father_title === "Mr") ? 'selected' : '' ?>>Mr
                                                </option>

                                                <option value="Sri" <?= ($father_title === "Sri") ? 'selected' : '' ?>>
                                                    Sri</option>
                                                <option value="Dr" <?= ($father_title === "Dr") ? 'selected' : '' ?>>
                                                    Sri</option>

                                                <option value="Late" <?= ($father_title === "Late") ? 'selected' : '' ?>>
                                                    Late</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="father_name" id="father_name" value="<?= $father_name ?>" maxlength="255" />
                                </div>
                                <?= form_error("father_title") . form_error("father_name") ?>

                            </div>

                            <div class="col-md-4">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <?php if ($usser_type === "user") { ?>
                                    <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" readonly maxlength="10" />
                                <?php } else { ?>
                                    <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" maxlength="10" />
                                <?php } ?>

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
                        <legend class="h5">Address/ ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Circle/ চক্ৰ <span class="text-danger">*</span> </label>
                                <input type="hidden" name="circle_office_name" id="circle_office_name" value="<?= $circle_office_name ?>">
                                <select id="circle_office" name="circle_office" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="1383118" <?= ($circle_office === "1383118") ? 'selected' : '' ?> data-entity-level="">Diphu</option>
                                    <option value="1383119" data-entity-level="" <?= ($circle_office === "1383119") ? 'selected' : '' ?>>Dongkamukam</option>
                                    <option value="1383120" data-entity-level="" <?= ($circle_office === "1383120") ? 'selected' : '' ?>>Phuloni</option>
                                    <option value="1383121" data-entity-level="" <?= ($circle_office === "1383121") ? 'selected' : '' ?>>Silonijan</option>

                                </select>
                                <?= form_error("circle_office") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Mouza/ মৌজা <span class="text-danger">*</span> </label>
                                <input type="hidden" name="mouza_office_name" id="mouza_office_name" value="<?= $mouza_office_name ?>">
                                <select id="mouza_name" name="mouza_name" class="form-control">
                                    <option value="">Please Select</option>
                                </select>
                                <?= form_error("mouza_name") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Revenue Village/ ৰাজহ গাঁও <span class="text-danger">*</span> </label>
                                <input type="hidden" name="revenue_village_name" id="revenue_village_name" value="<?= $revenue_village_name ?>">
                                <select id="revenue_village" name="revenue_village" class="form-control">
                                    <option value="">Please Select</option>
                                </select>
                                <?= form_error("revenue_village") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Police Station/ থানা <span class="text-danger">*</span> </label>
                                <input type="hidden" name="police_station_name" id="police_station_name" value="<?= $police_station_name ?>" />
                                <select id="police_station" name="police_station" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="1" <?= ($police_station === "1") ? 'selected' : '' ?>>Anjokpani Police Station
                                    </option>


                                </select>
                                <?= form_error("police_station") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Post Office/ ডাকঘৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="post_office" id="post_office" value="<?= $post_office ?>" maxlength="255" />
                                <?= form_error("post_office") ?>
                            </div>
                        </div>

                    </fieldset>

                    <fieldset class="border border-success " style="margin-top:40px">
                        <legend class="h5">Applicant Categories / আবেদনকাৰীৰ শ্ৰেণী নিৰ্বাচন কৰক </legend>
                        <div class="row form-group">

                            <div class="col-md-4">
                                <label>Select Applicant Category/ আবেদনকাৰীৰ শ্ৰেণী নিৰ্বাচন কৰক<span class="text-danger">*</span> </label>
                                <input type="hidden" name="applicant_category_text" id="applicant_category_text" value="<?= $applicant_category_text ?>" />
                                <select name="applicant_category" id="applicant_category" class="form-control">
                                    <option value="">Please Select</option>

                                    <option value="1" <?= ($applicant_category === "1") ? 'selected' : '' ?>>Applicant having Land Document (land patta) and service (salaried)
                                    <option value="2" <?= ($applicant_category === "2") ? 'selected' : '' ?>>Applicant having Land Document (land patta) and without service (salaried)
                                    <option value="3" <?= ($applicant_category === "3") ? 'selected' : '' ?>>Applicant not having Land Document (land patta) but has service (salaried)
                                    <option value="4" <?= ($applicant_category === "4") ? 'selected' : '' ?>>Applicant not having Land Document (land patta) and also no service (salaried)

                                </select>
                                <?= form_error("applicant_category") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success d-none" style="margin-top:40px" id="other_fieldset">
                        <legend class="h5">Other Details / অন্যান্য বিৱৰণ </legend>
                        <div class="row form-group">

                            <div class="col-md-4">
                                <label>Dag No. / ডাগ নং<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="dag_no" id="dag_no" value="<?= $dag_no ?>" maxlength="255" />
                                <?= form_error("dag_no") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Annual Patta/Periodic Patta No./ বাৰ্ষিক পট্টা/সাময়িক পট্টা নং<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="periodic_patta_no" id="periodic_patta_no" value="<?= $periodic_patta_no ?>" maxlength="255" />
                                <?= form_error("periodic_patta_no") ?>
                            </div>

                            <div class="col-md-4">
                                <label>Patta Type/ পট্টা টাইপ<span class="text-danger">*</span> </label>
                                <input type="hidden" name="patta_type_name" id="patta_type_name" value="<?= $patta_type_name ?>" />
                                <select name="patta_type" id="patta_type" class="form-control">
                                    <option value="">Please Select</option>

                                    <option value="1" <?= ($patta_type === "1") ? 'selected' : '' ?>>Periodic Patta (Myadi)
                                    </option>

                                </select>
                                <?= form_error("patta_type") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success d-none" style="margin-top:40px" id="land_area_fieldset">
                        <legend class="h5">Land Area/ ভূমিৰ আয়তন </legend>
                        <div class="row form-group">
                            <div class="col-md-3">
                                <label>Bigha./ বিঘা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control land_area" name="land_area_bigha" id="land_area_bigha" value="<?= $land_area_bigha ?>" maxlength="255" />
                                <?= form_error("land_area_bigha") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Kotha./ কঠা <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control land_area" name="land_area_katha" id="land_area_katha" value="<?= $land_area_katha ?>" maxlength="255" />
                                <?= form_error("land_area_katha") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Loosa./ লেচা<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control land_area" name="land_area_loosa" id="land_area_loosa" value="<?= $land_area_loosa ?>" maxlength="255" />
                                <?= form_error("land_area_loosa") ?>
                            </div>
                            <div class="col-md-3">
                                <label>Land Area./ ভূমিৰ আয়তন<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="land_area_sq_ft" id="land_area_sq_ft" value="<?= $land_area_sq_ft ?>" maxlength="255" readonly />
                                <?= form_error("land_area_sq_ft") ?>
                            </div>

                        </div>
                    </fieldset>
                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Save &amp Next
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

<script>
    $(document).ready(function() {
        res = "<?php echo $police_station ?>"
        $.get("<?= $apiServer . "api/ccsdp/police_station" ?>", function(data) {
            $.each(data.records, function(i, val) {
                id = val.station_id;
                // console.log($id);
                cond = (res == id) ? "selected" : "";
                $("#police_station").append('<option value="' + val.station_id + '"' + cond + '>' + val.police_station_name + '</option>');
            });
        });
    })

    $(document).on('change', '#circle_office', function() {
        // console.log("as");
        $("#mouza_name").empty();
        circle_id = $(this).val();
        $.ajax({
            url: "<?= base_url("spservices/kaacincomecertificate/registration/mouza"); ?>",
            type: 'POST',
            // contentType: "application/json",
            data: {
                "jsonbody": circle_id
            },
            dataType: 'json',
            success: function(res) {
                var obj = $.parseJSON(res);
                $("#mouza_name").append('<option value="" disable>Please Select</option>');
                $.each(obj.records, function(i, val) {
                    id = val.mouza_id;
                    $("#mouza_name").append('<option value="' + val.mouza_id + '">' + val.mouza_name + '</option>');
                });
            }
        });
    });
    $(document).on('change', '#mouza_name', function() {
        $("#revenue_village").empty();
        mouza_id = $(this).val();
        $.ajax({
            url: "<?= base_url("spservices/kaacincomecertificate/registration/revenue_village"); ?>",
            type: 'POST',
            // contentType: "application/json",
            data: {
                "jsonbody": mouza_id
            },
            dataType: 'json',
            success: function(res) {
                var obj = $.parseJSON(res);
                $("#revenue_village").append('<option value="" disable>Please Select</option>');
                $.each(obj.records, function(i, val) {
                    id = val.village_id;
                    $("#revenue_village").append('<option value="' + val.village_id + '">' + val.village_name + '</option>');
                });
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        return application_category();
    })

    $(document).on('change', '#applicant_category', function() {
        return application_category();
    })

    function application_category() {
        id = $('#applicant_category').val();
        if (id == 1 || id == 2) {
            $('#other_fieldset').removeClass('d-none');
            $('#land_area_fieldset').removeClass('d-none');
        } else {
            $('#other_fieldset').addClass('d-none');
            $('#land_area_fieldset').addClass('d-none');
        }
    }
</script>
<script>
    $(document).on('keyup', '.land_area', function() {
        appBigha = parseInt($('#land_area_bigha').val()) ? parseInt($('#land_area_bigha').val()) : 0;
        appKotha = parseInt($('#land_area_katha').val()) ? parseInt($('#land_area_katha').val()) : 0;
        appLoosa = parseInt($('#land_area_loosa').val()) ? parseInt($('#land_area_loosa').val()) : 0;

        oneBigha = 14400
        oneKotha = 2880
        oneLoosa = 144

        bigha = appBigha * oneBigha;
        kotha = appKotha * oneKotha;
        loosa = appLoosa * oneLoosa;

        total = bigha + kotha + loosa

        $('#land_area_sq_ft').val(total);
        // console.log(total);
    })
</script>
<script>
    $(document).on('change', '#circle_office', function() {

        var value = $("#circle_office option:selected").text();
        $('#circle_office_name').val(value);
    })
    $(document).on('change', '#mouza_name', function() {

        var value = $("#mouza_name option:selected").text();
        $('#mouza_office_name').val(value);
    })
    $(document).on('change', '#revenue_village', function() {

        var value = $("#revenue_village option:selected").text();
        $('#revenue_village_name').val(value);
    })
    $(document).on('change', '#police_station', function() {

        var value = $("#police_station option:selected").text();
        $('#police_station_name').val(value);
    })
    $(document).on('change', '#patta_type', function() {

        var value = $("#patta_type option:selected").text();
        $('#patta_type_name').val(value);
    })
    $(document).on('change', '#applicant_category', function() {

        var value = $("#applicant_category option:selected").text();
        $('#applicant_category_text').val(value);
    })
</script>