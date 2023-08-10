<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
// $apiURL= "http://13.233.207.96/MMSGNA-TEST/api/"; //For testing
$apiURL = "https://rtps.mmsgna.in/api/"; //For testing

if ($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $pageTitle = $dbrow->service_data->service_name;
    $pageTitleId = $dbrow->form_data->service_id;
    $applicant_name = $dbrow->form_data->applicant_name;
    $first_name = $dbrow->form_data->first_name;
    $last_name = $dbrow->form_data->last_name;
    $gender = $dbrow->form_data->applicant_gender;
    $father_name = $dbrow->form_data->father_name;
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email;
    $status = $dbrow->service_data->appl_status;
    $applicant_title = $dbrow->form_data->applicant_title;
    $father_title = $dbrow->form_data->father_title;
    $place_of_business = isset($dbrow->form_data->place_of_business) ? $dbrow->form_data->place_of_business : '';
    $class_of_business = isset($dbrow->form_data->class_of_business) ? $dbrow->form_data->class_of_business : '';
    $firm_name = isset($dbrow->form_data->firm_name) ? $dbrow->form_data->firm_name : '';
    $proprietor_name = isset($dbrow->form_data->proprietor_name) ? $dbrow->form_data->proprietor_name : '';
    $community = isset($dbrow->form_data->community) ? $dbrow->form_data->community : '';
    $reason = isset($dbrow->form_data->reason) ? $dbrow->form_data->reason : '';
    $occupation_trade = isset($dbrow->form_data->occupation_trade) ? $dbrow->form_data->occupation_trade : '';
    $address = isset($dbrow->form_data->address) ? $dbrow->form_data->address : '';
    $room_occupied = isset($dbrow->form_data->room_occupied) ? $dbrow->form_data->room_occupied : '';
    $room_occupied_text = isset($dbrow->form_data->room_occupied_text) ? $dbrow->form_data->room_occupied_text : '';

    $signature_type_frm = set_value("signature_type");
    $signature_frm = $uploadedFiles['signature_old'] ?? null;
    $signature_type_db = $dbrow->form_data->signature_type ?? null;
    $signature_db = $dbrow->form_data->signature ?? null;
    $signature = strlen($signature_frm) ? $signature_frm : $signature_db;
    $signature_type = strlen($signature_type_frm) ? $signature_type_frm : $signature_type_db;


    $passport_photo_type_frm = set_value("passport_photo_type");
    $passport_photo_frm = $uploadedFiles['passport_photo_old'] ?? null;
    $passport_photo_type_db = $dbrow->form_data->passport_photo_type ?? null;
    $passport_photo_db = $dbrow->form_data->passport_photo ?? null;
    $passport_photo = strlen($passport_photo_frm) ? $passport_photo_frm : $passport_photo_db;
    $passport_photo_type = strlen($passport_photo_type_frm) ? $passport_photo_type_frm : $passport_photo_type_db;


    $address_proof_type_frm = set_value("address_proof_type");
    $address_proof_frm = $uploadedFiles['address_proof_old'] ?? null;
    $address_proof_type_db = $dbrow->form_data->address_proof_type ?? null;
    $address_proof_db = $dbrow->form_data->address_proof ?? null;
    $address_proof = strlen($address_proof_frm) ? $address_proof_frm : $address_proof_db;
    $address_proof_type = strlen($address_proof_type_frm) ? $address_proof_type_frm : $address_proof_type_db;


    $identity_proof_type_frm = set_value("identity_proof_type");
    $identity_proof_frm = $uploadedFiles['identity_proof_old'] ?? null;
    $identity_proof_type_db = $dbrow->form_data->identity_proof_type ?? null;
    $identity_proof_db = $dbrow->form_data->identity_proof ?? null;
    $identity_proof = strlen($identity_proof_frm) ? $identity_proof_frm : $identity_proof_db;
    $identity_proof_type = strlen($identity_proof_type_frm) ? $identity_proof_type_frm : $identity_proof_type_db;


    $house_tax_receipt_type_frm = set_value("house_tax_receipt_type");
    $house_tax_receipt_frm = $uploadedFiles['house_tax_receipt_old'] ?? null;
    $house_tax_receipt_type_db = $dbrow->form_data->house_tax_receipt_type ?? null;
    $house_tax_receipt_db = $dbrow->form_data->house_tax_receipt ?? null;
    $house_tax_receipt = strlen($house_tax_receipt_frm) ? $house_tax_receipt_frm : $house_tax_receipt_db;
    $house_tax_receipt_type = strlen($house_tax_receipt_type_frm) ? $house_tax_receipt_type_frm : $house_tax_receipt_type_db;


    $business_reg_certificate_type_frm = set_value("business_reg_certificate_type");
    $business_reg_certificate_frm = $uploadedFiles['business_reg_certificate_old'] ?? null;
    $business_reg_certificate_type_db = $dbrow->form_data->business_reg_certificate_type ?? null;
    $business_reg_certificate_db = $dbrow->form_data->business_reg_certificate ?? null;
    $business_reg_certificate = strlen($business_reg_certificate_frm) ? $business_reg_certificate_frm : $business_reg_certificate_db;
    $business_reg_certificate_type = strlen($business_reg_certificate_type_frm) ? $business_reg_certificate_type_frm : $business_reg_certificate_type_db;



    $mbtc_room_rent_deposit_type_frm = set_value("mbtc_room_rent_deposit_type");
    $mbtc_room_rent_deposit_frm = $uploadedFiles['mbtc_room_rent_deposit_old'] ?? null;
    $mbtc_room_rent_deposit_type_db = $dbrow->form_data->mbtc_room_rent_deposit_type ?? null;
    $mbtc_room_rent_deposit_db = $dbrow->form_data->mbtc_room_rent_deposit ?? null;
    $mbtc_room_rent_deposit = strlen($mbtc_room_rent_deposit_frm) ? $mbtc_room_rent_deposit_frm : $mbtc_room_rent_deposit_db;
    $mbtc_room_rent_deposit_type = strlen($mbtc_room_rent_deposit_type_frm) ? $mbtc_room_rent_deposit_type_frm : $mbtc_room_rent_deposit_type_db;


    $consideration_letter_type_frm = set_value("consideration_letter_type");
    $consideration_letter_frm = $uploadedFiles['consideration_letter_old'] ?? null;
    $consideration_letter_type_db = $dbrow->form_data->consideration_letter_type ?? null;
    $consideration_letter_db = $dbrow->form_data->consideration_letter ?? null;
    $consideration_letter = strlen($consideration_letter_frm) ? $consideration_letter_frm : $consideration_letter_db;
    $consideration_letter_type = strlen($consideration_letter_type_frm) ? $consideration_letter_type_frm : $consideration_letter_type_db;
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
<script type="text/javascript">
    $(document).ready(function() {


        $("#house_tax_receipt").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        $("#business_reg_certificate").fileinput({
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
        $("#identity_proof").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        $("#mbtc_room_rent_deposit").fileinput({
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
        $("#passport_photo").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png"]
        });
        $("#signature").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            maxFileSize: 1024,
            allowedFileExtensions: ["jpg", "jpeg", "png"]
        });

    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", ".frmbtn", function() {
            let clickedBtn = $(this).attr("id");
            alert(clickedBtn);
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/kaac_noc_trade_license/registration/querysubmit') ?>" enctype="multipart/form-data">

            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="service_name" value="<?= $pageTitle ?>" type="hidden" />
            <input name="service_id" value="<?= $pageTitleId ?>" type="hidden" />

            <input name="address_proof_old" value="<?= $address_proof ?>" type="hidden" />
            <input name="identity_proof_old" value="<?= $identity_proof ?>" type="hidden" />
            <input name="house_tax_receipt_old" value="<?= $house_tax_receipt ?>" type="hidden" />
            <input name="business_reg_certificate_old" value="<?= $business_reg_certificate ?>" type="hidden" />
            <input name="mbtc_room_rent_deposit_old" value="<?= $mbtc_room_rent_deposit ?>" type="hidden" />
            <input name="consideration_letter_old" value="<?= $consideration_letter ?>" type="hidden" />
            <input name="passport_photo_old" value="<?= $passport_photo ?>" type="hidden" />
            <input name="signature_old" value="<?= $signature ?>" type="hidden" />

            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 20px; color: #fff; font-family: Roboto,sans-serif; font-weight: bold">

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
                    <?php if ($status === 'QS') { ?>
                        <fieldset class="border border-danger" style="margin-top:40px; margin-bottom: 2px">
                            <legend class="h5">QUERY DETAILS </legend>
                            <div class="row">
                                <div class="col-md-12" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                    <?= (end($dbrow->processing_history)->remarks) ?? '' ?>
                                </div>
                            </div>
                            <span style="float:right; font-size: 12px">
                                Query time : <?= isset(end($dbrow->processing_history)->processing_time) ? format_mongo_date(end($dbrow->processing_history)->processing_time) : '' ?>
                            </span>
                        </fieldset>
                    <?php } //End of if 
                    ?>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details/ আবেদনকাৰীৰ তথ্য </legend>

                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>Applicant&apos;s First Name/ আবেদনকাৰীৰ প্ৰথম নাম<span class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text p-1">
                                            <select name="applicant_title" disabled>
                                                <option value="">Select</option>
                                                <option value="Mr" <?= ($applicant_title === "Mr") ? 'selected' : '' ?>>Mr
                                                </option>
                                                <option value="Mrs" <?= ($applicant_title === "Mrs") ? 'selected' : '' ?>>
                                                    Mrs</option>
                                                <option value="Sri" <?= ($applicant_title === "Sri") ? 'selected' : '' ?>>
                                                    Sri</option>
                                                <option value="Smt" <?= ($applicant_title === "Smt") ? 'selected' : '' ?>>
                                                    Smt</option>
                                                <option value="Late" <?= ($applicant_title === "Late") ? 'selected' : '' ?>>
                                                    Late</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="first_name" id="first_name" value="<?= $first_name ?>" maxlength="255" readonly />
                                </div>
                                <?= form_error("applicant_title") . form_error("first_name") ?>

                            </div>

                            <div class="col-md-4">
                                <label>Applicant&apos;s Last Name/ আবেদনকাৰীৰ উপাধি<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="last_name" id="last_name" value="<?= $last_name ?>" maxlength="255" readonly />
                                <?= form_error("last_name") ?>
                            </div>
                            <div class="col-md-4">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="gender" class="form-control" disabled>
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
                                            <select name="father_title" disabled>
                                                <option value="">Select</option>
                                                <option value="Mr" <?= ($father_title === "Mr") ? 'selected' : '' ?>>Mr
                                                </option>
                                                <option value="Mrs" <?= ($father_title === "Mrs") ? 'selected' : '' ?>>
                                                    Mrs</option>
                                                <option value="Sri" <?= ($father_title === "Sri") ? 'selected' : '' ?>>
                                                    Sri</option>
                                                <option value="Smt" <?= ($father_title === "Smt") ? 'selected' : '' ?>>
                                                    Smt</option>
                                                <option value="Late" <?= ($father_title === "Late") ? 'selected' : '' ?>>
                                                    Late</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="father_name" id="father_name" value="<?= $father_name ?>" maxlength="255" readonly />
                                </div>
                                <?= form_error("father_title") . form_error("father_name") ?>

                            </div>

                            <div class="col-md-4">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>

                                <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" maxlength="10" readonly />


                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-4">
                                <label>E-Mail / ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?= $email ?>" maxlength="100" readonly />
                                <?= form_error("email") ?>
                            </div>
                        </div>
                    </fieldset>
                   
                    <fieldset class="border border-success" style="margin-top:40px" id="other_fieldset">
                        <legend class="h5">Firm Details / অন্যান্য বিৱৰণ </legend>
                        <div class="row form-group">

                            <div class="col-md-6">
                                <label>Name of the Firm<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="firm_name" id="firm_name" value="<?= $firm_name ?>" maxlength="255" readonly/>
                                <?= form_error("firm_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Name of the Proprietor<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="proprietor_name" id="proprietor_name" value="<?= $proprietor_name ?>" maxlength="255" readonly/>
                                <?= form_error("proprietor_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Community<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="community" id="community" value="<?= $community ?>" maxlength="255" readonly/>
                                <?= form_error("community") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Occupation/Trade<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="occupation_trade" id="occupation_trade" value="<?= $occupation_trade ?>" maxlength="255" readonly/>
                                <?= form_error("occupation_trade") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Place or places of business<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="place_of_business" id="place_of_business" value="<?= $place_of_business ?>" maxlength="255" readonly/>
                                <?= form_error("place_of_business") ?>
                            </div>
                           

                            <div class="col-md-6">
                                <label>Class of Business <span class="text-danger">*</span> </label>
                                <select id="class_of_business" name="class_of_business" class="form-control" readonly>
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
                                <textarea type="text" class="form-control" name="address" id="address" maxlength="255" readonly><?= $address ?></textarea>
                                <?= form_error("address") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Special reason for consideration(if any)<span class="text-danger">*</span> </label>
                                <textarea type="text" class="form-control" name="reason" id="reason" maxlength="255" readonly><?= $reason ?></textarea>
                                <?= form_error("reason") ?>
                            </div>
                            <div class="col-md-6 ">
                                <div>
                                    <label>If room occupied<span class="text-danger">*</span> </label>
                                </div>
                                <input type="hidden" name="room_occupied_text" id="room_occupied_text" value="<?= $room_occupied_text ?>">
                                <input type="hidden" name="room_occupied" id="room_occupied_text" value="<?= $room_occupied ?>">
                                <div class="form-check ">
                                    <input class="form-check-input room_occupied" type="radio" name="room_occupied" id="room_occupied_yes" value="1" <?= ($room_occupied === "1") ? 'checked' : '' ?> style="margin-top: 0.65em;" disabled>
                                    <label class="form-check-label" for="room_occupied_yes">Yes</label>
                                </div>
                                <div class="form-check ">
                                    <input class="form-check-input room_occupied" type="radio" name="room_occupied" id="room_occupied_no" value="2" <?= ($room_occupied === "2") ? 'checked' : '' ?> style="margin-top: 0.65em;" disabled>
                                    <label class="form-check-label" for="room_occupied_no">No</label>
                                </div>
                            </div>
                        </div>
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
                                            <td>Passport Size Photo <span class="text-danger">*</span></td>
                                            <td>

                                                <select name="passport_photo_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="6919-6902" <?= ($passport_photo_type === '6919-6902') ? 'selected' : '' ?>>Passport Photo</option>

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
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="passport_photo" type="hidden" name="passport_photo_temp">

                                                <?= $this->digilocker_api->digilocker_fetch_btn('passport_photo'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Address Proof <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="address_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="7207-7206" <?= ($address_proof_type === '7207-7206') ? 'selected' : '' ?>>Address Proof</option>

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
                                                <input class="address_proof" type="hidden" name="address_proof_temp">

                                                <?= $this->digilocker_api->digilocker_fetch_btn('address_proof'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Identity proof <span class="text-danger">*</span></td>
                                            <td>
                                                <select name="identity_proof_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="159-5346" <?= ($identity_proof_type === '159-5346') ? 'selected' : '' ?>>Identity Proof</option>

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
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="identity_proof" type="hidden" name="identity_proof_temp">

                                                <?= $this->digilocker_api->digilocker_fetch_btn('identity_proof'); ?>

                                            </td>
                                        </tr>
                                        <tr>
                                            <td> House Tax Receipt</td>
                                            <td>
                                                <select name="house_tax_receipt_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="8200-8204" <?= ($house_tax_receipt_type === '8200-8204') ? 'selected' : '' ?>>House Tax Receipt</option>
                                                </select>
                                                <?= form_error("house_tax_receipt_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="house_tax_receipt" name="house_tax_receipt" type="file" />
                                                </div>
                                                <?php if (strlen($house_tax_receipt)) { ?>
                                                    <a href="<?= base_url($house_tax_receipt) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="house_tax_receipt" type="hidden" name="house_tax_receipt_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('house_tax_receipt'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Copy of current Business Registration Certificate </td>
                                            <td>
                                                <select name="business_reg_certificate_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="8224-8225" <?= ($business_reg_certificate_type === '8224-8225') ? 'selected' : '' ?>>Copy of current Business Registration Certificate</option>
                                                </select>
                                                <?= form_error("business_reg_certificate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="business_reg_certificate" name="business_reg_certificate" type="file" />
                                                </div>
                                                <?php if (strlen($business_reg_certificate)) { ?>
                                                    <a href="<?= base_url($business_reg_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="business_reg_certificate" type="hidden" name="business_reg_certificate_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('business_reg_certificate'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Valid MBTC Room rent deposit </td>
                                            <td>
                                                <select name="mbtc_room_rent_deposit_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="8226-8227" <?= ($mbtc_room_rent_deposit_type === '8226-8227') ? 'selected' : '' ?>>Valid MBTC Room rent deposit</option>
                                                </select>
                                                <?= form_error("mbtc_room_rent_deposit_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="mbtc_room_rent_deposit" name="mbtc_room_rent_deposit" type="file" />
                                                </div>
                                                <?php if (strlen($mbtc_room_rent_deposit)) { ?>
                                                    <a href="<?= base_url($mbtc_room_rent_deposit) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="mbtc_room_rent_deposit" type="hidden" name="mbtc_room_rent_deposit_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('mbtc_room_rent_deposit'); ?>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Special reason for Consideration letter </td>
                                            <td>
                                                <select name="consideration_letter_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="8228-8229" <?= ($consideration_letter_type === '8228-8229') ? 'selected' : '' ?>>Special reason for Consideration letter</option>
                                                </select>
                                                <?= form_error("consideration_letter_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="consideration_letter" name="consideration_letter" type="file" />
                                                </div>
                                                <?php if (strlen($consideration_letter)) { ?>
                                                    <a href="<?= base_url($consideration_letter) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="consideration_letter" type="hidden" name="consideration_letter_temp">
                                                <?= $this->digilocker_api->digilocker_fetch_btn('consideration_letter'); ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Applicant Signature </td>
                                            <td>
                                                <select name="signature_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="6865-6866" <?= ($signature_type === '6865-6866') ? 'selected' : '' ?>>Signature</option>

                                                </select>
                                                <?= form_error("signature_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="signature" name="signature" type="file" />
                                                </div>
                                                <?php if (strlen($signature)) { ?>
                                                    <a href="<?= base_url($signature) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span>
                                                        View/Download
                                                    </a>
                                                <?php } //End of if 
                                                ?>
                                                <input class="signature" type="hidden" name="signature_temp">

                                                <?= $this->digilocker_api->digilocker_fetch_btn('signature'); ?>
                                            </td>
                                        </tr>

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