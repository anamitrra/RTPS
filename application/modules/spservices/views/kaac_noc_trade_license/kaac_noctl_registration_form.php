<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
// $apiServer = "https://artpskaac.in/"; //For testing
// pre($dbrow);


if ($dbrow) {
    // pre($dbrow);
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

    $address_proof_type = isset($dbrow->form_data->address_proof_type) ? $dbrow->form_data->address_proof_type : '';
    $address_proof_type_text = isset($dbrow->form_data->address_proof_type_text) ? $dbrow->form_data->address_proof_type_text : '';
    $address_proof = isset($dbrow->form_data->address_proof) ? $dbrow->form_data->address_proof : '';
    $identity_proof_type = isset($dbrow->form_data->identity_proof_type) ? $dbrow->form_data->identity_proof_type : '';
    $identity_proof_type_text = isset($dbrow->form_data->identity_proof_type_text) ? $dbrow->form_data->identity_proof_type_text : '';
    $identity_proof = isset($dbrow->form_data->identity_proof) ? $dbrow->form_data->identity_proof : '';
    $house_tax_receipt_type = isset($dbrow->form_data->house_tax_receipt_type) ? $dbrow->form_data->house_tax_receipt_type : '';
    $house_tax_receipt_type_text = isset($dbrow->form_data->house_tax_receipt_type_text) ? $dbrow->form_data->house_tax_receipt_type_text : '';
    $house_tax_receipt = isset($dbrow->form_data->house_tax_receipt) ? $dbrow->form_data->house_tax_receipt : '';
    $business_reg_certificate_type = isset($dbrow->form_data->business_reg_certificate_type) ? $dbrow->form_data->business_reg_certificate_type : '';
    $business_reg_certificate_type_text = isset($dbrow->form_data->business_reg_certificate_type_text) ? $dbrow->form_data->business_reg_certificate_type_text : '';
    $business_reg_certificate = isset($dbrow->form_data->business_reg_certificate) ? $dbrow->form_data->business_reg_certificate : '';
    $mbtc_room_rent_deposit_type = isset($dbrow->form_data->mbtc_room_rent_deposit_type) ? $dbrow->form_data->mbtc_room_rent_deposit_type : '';;
    $mbtc_room_rent_deposit_type_text = isset($dbrow->form_data->mbtc_room_rent_deposit_type_text) ? $dbrow->form_data->mbtc_room_rent_deposit_type_text : '';;
    $mbtc_room_rent_deposit = isset($dbrow->form_data->mbtc_room_rent_deposit) ? $dbrow->form_data->mbtc_room_rent_deposit : '';
    $consideration_letter_type = isset($dbrow->form_data->consideration_letter_type) ? $dbrow->form_data->consideration_letter_type : '';;
    $consideration_letter_type_text = isset($dbrow->form_data->consideration_letter_type_text) ? $dbrow->form_data->consideration_letter_type_text : '';;
    $consideration_letter = isset($dbrow->form_data->consideration_letter) ? $dbrow->form_data->consideration_letter : '';
    $signature_type = isset($dbrow->form_data->signature_type) ? $dbrow->form_data->signature_type : '';;
    $signature_type_text = isset($dbrow->form_data->signature_type_text) ? $dbrow->form_data->signature_type_text : '';;
    $signature = isset($dbrow->form_data->signature) ? $dbrow->form_data->signature : '';
    $passport_photo_type = isset($dbrow->form_data->passport_photo_type) ? $dbrow->form_data->passport_photo_type : '';;
    $passport_photo_type_text = isset($dbrow->form_data->passport_photo_type_text) ? $dbrow->form_data->passport_photo_type_text : '';;
    $passport_photo = isset($dbrow->form_data->passport_photo) ? $dbrow->form_data->passport_photo : '';

    $place_of_business = isset($dbrow->form_data->place_of_business) ? $dbrow->form_data->place_of_business : '';
    $class_of_business = isset($dbrow->form_data->class_of_business) ? $dbrow->form_data->class_of_business : '';
    $firm_name = $dbrow->form_data->firm_name ?? set_value("firm_name");
    $proprietor_name = $dbrow->form_data->proprietor_name ?? set_value("proprietor_name");
    $community = $dbrow->form_data->community ?? set_value("community");
    $reason = $dbrow->form_data->reason ?? set_value("reason");
    $occupation_trade = $dbrow->form_data->occupation_trade ?? set_value("occupation_trade");
    $address = $dbrow->form_data->address ?? set_value("address");
    $room_occupied = $dbrow->form_data->room_occupied ?? set_value("room_occupied");
    $room_occupied_text = $dbrow->form_data->room_occupied_text ?? set_value("room_occupied_text");
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

    $address_proof_type = "";
    $address_proof_type_text = "";
    $address_proof = "";
    $identity_proof_type = "";
    $identity_proof_type_text = "";
    $identity_proof = "";
    $passport_photo_type = "";
    $passport_photo_type_text = "";
    $passport_photo = "";
    $signature_type = "";
    $signature_type_text = "";
    $signature = "";
    $house_tax_receipt_type = "";
    $house_tax_receipt_type_text = "";
    $house_tax_receipt = "";
    $business_reg_certificate_type = "";
    $business_reg_certificate_type_text = "";
    $business_reg_certificate = "";
    $mbtc_room_rent_deposit_type = "";
    $mbtc_room_rent_deposit_type_text = "";
    $mbtc_room_rent_deposit = "";
    $consideration_letter_type = "";
    $consideration_letter_type_text = "";
    $consideration_letter = "";
    $room_occupied = set_value("room_occupied");
    $firm_name = set_value("firm_name");
    $proprietor_name = set_value("proprietor_name");
    $reason = set_value("reason");
    $community = set_value("community");
    $occupation_trade = set_value("occupation_trade");
    $place_of_business = set_value("place_of_business");
    $class_of_business = set_value("class_of_business");
    $address = set_value("address");
    $room_occupied_text = set_value("room_occupied_text");
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
            if ($('#declaration').is(':checked')) {
                        
            let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
            // console.log(clickedBtn);
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
                        // console.log("Asdasdasd");
                        $("#myfrm").submit();
                    } else if (clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {} //End of if else
                }
            });
        } else {
            Swal.fire({
                title: 'Please Check the Declaration',
                text: msg,
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                // cancelButtonColor: '#d33',
                confirmButtonText: 'OK'
            })
        }  
        });
    });
</script>
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/kaac-noc-trade-license') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="service_name" value="<?= $pageTitle ?>" type="hidden" />
            <input name="service_id" value="<?= $pageTitleId ?>" type="hidden" />
            <input name="address_proof_type" value="<?= $address_proof_type ?>" type="hidden" />
            <input name="address_proof_type_text" value="<?= $address_proof_type_text ?>" type="hidden" />
            <input name="address_proof" value="<?= $address_proof ?>" type="hidden" />
            <input name="identity_proof_type" value="<?= $identity_proof_type ?>" type="hidden" />
            <input name="identity_proof_type_text" value="<?= $identity_proof_type_text ?>" type="hidden" />
            <input name="identity_proof" value="<?= $identity_proof ?>" type="hidden" />
            <input name="house_tax_receipt_type" value="<?= $house_tax_receipt_type ?>" type="hidden" />
            <input name="house_tax_receipt_type_text" value="<?= $house_tax_receipt_type_text ?>" type="hidden" />
            <input name="house_tax_receipt" value="<?= $house_tax_receipt ?>" type="hidden" />
            <input name="business_reg_certificate_type" value="<?= $business_reg_certificate_type ?>" type="hidden" />
            <input name="business_reg_certificate_type_text" value="<?= $business_reg_certificate_type_text ?>" type="hidden" />
            <input name="business_reg_certificate" value="<?= $business_reg_certificate ?>" type="hidden" />
            <input name="mbtc_room_rent_deposit_type" value="<?= $mbtc_room_rent_deposit_type ?>" type="hidden" />
            <input name="mbtc_room_rent_deposit_type_text" value="<?= $mbtc_room_rent_deposit_type_text ?>" type="hidden" />
            <input name="mbtc_room_rent_deposit" value="<?= $mbtc_room_rent_deposit ?>" type="hidden" />
            <input name="consideration_letter_type" value="<?= $consideration_letter_type ?>" type="hidden" />
            <input name="consideration_letter_type_text" value="<?= $consideration_letter_type_text ?>" type="hidden" />
            <input name="consideration_letter" value="<?= $consideration_letter ?>" type="hidden" />
            <input name="passport_photo_type" value="<?= $passport_photo_type ?>" type="hidden" />
            <input name="passport_photo_type_text" value="<?= $passport_photo_type_text ?>" type="hidden" />
            <input name="passport_photo" value="<?= $passport_photo ?>" type="hidden" />
            <input name="signature_type" value="<?= $signature_type ?>" type="hidden" />
            <input name="signature_type_text" value="<?= $signature_type_text ?>" type="hidden" />
            <input name="signature" value="<?= $signature ?>" type="hidden" />

            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b><?= $pageTitle ?><br>
                    অনাপত্তি বাণিজ্য অনুজ্ঞাপত্ৰ প্ৰদান <b>
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
                            <li>3. Passport Size Photos [Mandatory]</li>
                            <li>৩. পাছপ'ৰ্ট ফটো [বাধ্যতামূলক] </li>
                            <li>4. Valid MB/TC House Tax deposit receipt.</li>
                            <li>৪. বৈধ এম বি/টি চি হাউচ টেক্স জমা ৰচিদ </li>
                            <li>5. Copy of current Business Registration Certificate.</li>
                            <li>৫. বৰ্তমানৰ ব্যৱসায় পঞ্জীয়ন প্ৰমাণ পত্ৰৰ কপি</li>
                            <li>6. Valid MBTC Room rent deposit [ Not mandatory] [If room occupied than mandatory]</li>
                            <li>৬. বৈধ MBTC ৰুম ভাড়া জমা [ বাধ্যতামূলক নহয়] [যদি ৰুম দখল বাধ্যতামূলকতকৈ]</li>
                            <li>6. Special reason for Consideration letter [ Not Mandatory]</li>
                            <li>৬. বিবেচনা পত্ৰৰ বিশেষ কাৰণ [ বাধ্যতামূলক নহয়]।</li>

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
                                    <option value="Others" <?= ($gender === "Others") ? 'selected' : '' ?>>Others</option>
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

                                                <option value="Shri" <?= ($father_title === "Shri") ? 'selected' : '' ?>>
                                                    Shri</option>
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
                    <fieldset class="border border-success" style="margin-top:40px" id="other_fieldset">
                        <legend class="h5">Firm Details / অন্যান্য বিৱৰণ </legend>
                        <div class="row form-group">

                            <div class="col-md-6">
                                <label>Name of the Firm<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="firm_name" id="firm_name" value="<?= $firm_name ?>" maxlength="255" />
                                <?= form_error("firm_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Name of the Proprietor<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="proprietor_name" id="proprietor_name" value="<?= $proprietor_name ?>" maxlength="255" />
                                <?= form_error("proprietor_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Community<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="community" id="community" value="<?= $community ?>" maxlength="255" />
                                <?= form_error("community") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Occupation/Trade<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="occupation_trade" id="occupation_trade" value="<?= $occupation_trade ?>" maxlength="255" />
                                <?= form_error("occupation_trade") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Place or places of business<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="place_of_business" id="place_of_business" value="<?= $place_of_business ?>" maxlength="255" />
                                <?= form_error("place_of_business") ?>
                            </div>
                           

                            <div class="col-md-6">
                                <label>Class of Business <span class="text-danger">*</span> </label>
                                <select id="class_of_business" name="class_of_business" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="wholesale" <?= ($class_of_business === "wholesale") ? 'selected' : '' ?>>Wholesale
                                    </option>
                                    <option value="Retail" <?= ($class_of_business === "Retail") ? 'selected' : '' ?> >Retail</option>
                                    <option value="Daily" <?= ($class_of_business === "Daily") ? 'selected' : '' ?> >Daily</option>
                                    <option value="Weekly" <?= ($class_of_business === "Weekly") ? 'selected' : '' ?> >Weekly</option>
                                    <option value="Market" <?= ($class_of_business === "Market") ? 'selected' : '' ?> >Market</option>
                                    <option value="Agency" <?= ($class_of_business === "Agency") ? 'selected' : '' ?> >Agency</option>
                                    <option value="Organisation" <?= ($class_of_business === "Organisation") ? 'selected' : '' ?> >Organisation</option>
                                </select>
                                <?= form_error("class_of_business") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Address<span class="text-danger">*</span> </label>
                                <textarea type="text" class="form-control" name="address" id="address" maxlength="255" ><?= $address ?></textarea>
                                <?= form_error("address") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Special reason for consideration(if any)<span class="text-danger">*</span> </label>
                                <textarea type="text" class="form-control" name="reason" id="reason" maxlength="255" ><?= $reason ?></textarea>
                                <?= form_error("reason") ?>
                            </div>
                            <div class="col-md-6 ">
                                <div>
                                    <label>If room occupied<span class="text-danger">*</span> </label>
                                </div>
                                <input type="hidden" name="room_occupied_text" id="room_occupied_text" value="<?= $room_occupied_text ?>">
                                <div class="form-check ">
                                    <input class="form-check-input room_occupied" type="radio" name="room_occupied" id="room_occupied_yes" value="1" <?= ($room_occupied === "1") ? 'checked' : '' ?> style="margin-top: 0.65em;">
                                    <label class="form-check-label" for="room_occupied_yes">Yes</label>
                                </div>
                                <div class="form-check ">
                                    <input class="form-check-input room_occupied" type="radio" name="room_occupied" id="room_occupied_no" value="2" <?= ($room_occupied === "2") ? 'checked' : '' ?> style="margin-top: 0.65em;">
                                    <label class="form-check-label" for="room_occupied_no">No</label>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px" id="other_fieldset">
                        <legend class="h5">Declaration </legend>
                        <div class="row form-group">
                            <div>
                                <p>
                                    I have understood the provisions of the Diphu Town Committee Business Registration Notification No.1882 dt.22.12.2004,and shall abide by the said provisions framed thereunder.
                                </p>
                            </div>
                            <div class="form-check form-check-inline" style="padding-left: 2.5em; display: flex;">
                                <input class="form-check-input" type="checkbox" id="declaration" name="declaration" value="1">
                                <label class="form-check-label" for="declaration">I Agree</label>
                                <?= form_error("declaration") ?>
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

<!-- <script>
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
</script> -->

<script>

    $(document).on('click', '.room_occupied', function() {
        $("input[type='radio']:checked").each(function() {
            console.log(this);
            var idVal = $(this).attr("id");
            // console.log(($("label[for='"+idVal+"']").text()));
            $('#room_occupied_text').val(($("label[for='" + idVal + "']").text()));
        });
    })

    // $(document).on('change', '#mouza_name', function() {

    //     var value = $("#mouza_name option:selected").text();
    //     $('#mouza_office_name').val(value);
    // })
    // $(document).on('change', '#revenue_village', function() {

    //     var value = $("#revenue_village option:selected").text();
    //     $('#revenue_village_name').val(value);
    // })
    // $(document).on('change', '#police_station', function() {

    //     var value = $("#police_station option:selected").text();
    //     $('#police_station_name').val(value);
    // })
    // $(document).on('change', '#applicant_category', function() {

    //     var value = $("#applicant_category option:selected").text();
    //     $('#applicant_category_text').val(value);
    // })
</script>