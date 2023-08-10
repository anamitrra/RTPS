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
    $first_name = $dbrow->form_data->first_name ?? set_value("first_name");
    $last_name = $dbrow->form_data->last_name ?? set_value("last_name");
    $gender = $dbrow->form_data->applicant_gender ?? set_value("gender");
    $father_name = $dbrow->form_data->father_name ?? set_value("father_name");
    $mother_name = $dbrow->form_data->mother_name ?? set_value("mother_name");
    $mobile = $dbrow->form_data->mobile ?? set_value("mobile");
    $email = $dbrow->form_data->email ?? set_value("email");

    $circle_office = $dbrow->form_data->circle_office ?? set_value("circle_office");
    $mouza_name = $dbrow->form_data->mouza_name ?? set_value("mouza_name");

    $revenue_village = $dbrow->form_data->revenue_village ?? set_value("revenue_village");
    $police_station = $dbrow->form_data->police_station ?? set_value("police_station");
    $post_office = $dbrow->form_data->post_office ?? set_value("post_office");


    $circle_office_name = $dbrow->form_data->circle_office_name ?? set_value("circle_office_name");
    $mouza_office_name = $dbrow->form_data->mouza_office_name ?? set_value("mouza_office_name");
    $revenue_village_name = $dbrow->form_data->revenue_village_name ?? set_value("revenue_village_name");
    $police_station_name = $dbrow->form_data->police_station_name ?? set_value("police_station_name");
    $patta_type_name = $dbrow->form_data->patta_type_name ?? set_value("patta_type_name");

    $address_proof_type = isset($dbrow->form_data->address_proof_type) ? $dbrow->form_data->address_proof_type : '';
    $address_proof = isset($dbrow->form_data->address_proof) ? $dbrow->form_data->address_proof : '';
    $identity_proof_type = isset($dbrow->form_data->identity_proof_type) ? $dbrow->form_data->identity_proof_type : '';
    $identity_proof = isset($dbrow->form_data->identity_proof) ? $dbrow->form_data->identity_proof : '';
    $land_patta_copy_type = isset($dbrow->form_data->land_patta_copy_type) ? $dbrow->form_data->land_patta_copy_type : '';
    $land_patta_copy = isset($dbrow->form_data->land_patta_copy) ? $dbrow->form_data->land_patta_copy : '';
    $updated_land_revenue_receipt_type = isset($dbrow->form_data->updated_land_revenue_receipt_type) ? $dbrow->form_data->updated_land_revenue_receipt_type : '';
    $updated_land_revenue_receipt = isset($dbrow->form_data->updated_land_revenue_receipt) ? $dbrow->form_data->updated_land_revenue_receipt : '';
    $Up_to_date_original_land_documents_type = isset($dbrow->form_data->Up_to_date_original_land_documents_type) ? $dbrow->form_data->Up_to_date_original_land_documents_type : '';
    $Up_to_date_original_land_documents = isset($dbrow->form_data->Up_to_date_original_land_documents) ? $dbrow->form_data->Up_to_date_original_land_documents : '';
    $up_to_date_khajna_receipt_type = isset($dbrow->form_data->up_to_date_khajna_receipt_type) ? $dbrow->form_data->up_to_date_khajna_receipt_type : '';
    $up_to_date_khajna_receipt = isset($dbrow->form_data->up_to_date_khajna_receipt) ? $dbrow->form_data->up_to_date_khajna_receipt : '';

    $copy_of_jamabandi_type = isset($dbrow->form_data->copy_of_jamabandi_type) ? $dbrow->form_data->copy_of_jamabandi_type : '';
    $copy_of_jamabandi = isset($dbrow->form_data->copy_of_jamabandi) ? $dbrow->form_data->copy_of_jamabandi : '';

    $revenue_patta_copy_type = isset($dbrow->form_data->revenue_patta_copy_type) ? $dbrow->form_data->revenue_patta_copy_type : '';
    $revenue_patta_copy = isset($dbrow->form_data->revenue_patta_copy) ? $dbrow->form_data->revenue_patta_copy : '';
    $copy_of_chitha_type = isset($dbrow->form_data->copy_of_chitha_type) ? $dbrow->form_data->copy_of_chitha_type : '';
    $copy_of_chitha = isset($dbrow->form_data->copy_of_chitha) ? $dbrow->form_data->copy_of_chitha : '';
    $settlement_land_patta_copy_type = isset($dbrow->form_data->settlement_land_patta_copy_type) ? $dbrow->form_data->settlement_land_patta_copy_type : '';
    $settlement_land_patta_copy = isset($dbrow->form_data->settlement_land_patta_copy) ? $dbrow->form_data->settlement_land_patta_copy : '';
    $land_revenue_receipt_type = isset($dbrow->form_data->land_revenue_receipt_type) ? $dbrow->form_data->land_revenue_receipt_type : '';
    $land_revenue_receipt = isset($dbrow->form_data->land_revenue_receipt) ? $dbrow->form_data->land_revenue_receipt : '';
    $police_verification_report_type = isset($dbrow->form_data->police_verification_report_type) ? $dbrow->form_data->police_verification_report_type : '';
    $police_verification_report = isset($dbrow->form_data->police_verification_report) ? $dbrow->form_data->police_verification_report : '';
    $photocopy_of_existing_land_documents_type = isset($dbrow->form_data->photocopy_of_existing_land_documents_type) ? $dbrow->form_data->photocopy_of_existing_land_documents_type : '';
    $photocopy_of_existing_land_documents = isset($dbrow->form_data->photocopy_of_existing_land_documents) ? $dbrow->form_data->photocopy_of_existing_land_documents : '';

    $no_dues_certificate_from_bank_type = isset($dbrow->form_data->no_dues_certificate_from_bank_type) ? $dbrow->form_data->no_dues_certificate_from_bank_type : '';
    $no_dues_certificate_from_bank = isset($dbrow->form_data->no_dues_certificate_from_bank) ? $dbrow->form_data->no_dues_certificate_from_bank : '';

    $last_time_paid_Land_revenue_receipt_type = isset($dbrow->form_data->last_time_paid_Land_revenue_receipt_type) ? $dbrow->form_data->last_time_paid_Land_revenue_receipt_type : '';
    $last_time_paid_Land_revenue_receipt = isset($dbrow->form_data->last_time_paid_Land_revenue_receipt) ? $dbrow->form_data->last_time_paid_Land_revenue_receipt : '';

    //newly added by Bishwajit
    $applicant_title = $dbrow->form_data->applicant_title ?? set_value("applicant_title");
    $father_title = $dbrow->form_data->father_title ?? set_value("father_title");
    $periodic_patta_no = isset($dbrow->form_data->periodic_patta_no) ? $dbrow->form_data->periodic_patta_no : '';
    $dag_no = $dbrow->form_data->dag_no ?? set_value("dag_no");
    $land_area_bigha = $dbrow->form_data->land_area_bigha ?? set_value("land_area_bigha");
    $land_area_katha = $dbrow->form_data->land_area_katha ?? set_value("land_area_katha");
    $land_area_loosa = $dbrow->form_data->land_area_loosa ?? set_value("land_area_loosa");
    $land_area_sq_ft = $dbrow->form_data->land_area_sq_ft ?? set_value("land_area_sq_ft");
    $patta_type = $dbrow->form_data->patta_type ?? set_value("patta_type");
    // pre($land_area_bigha);
    $mut_name_title = $dbrow->form_data->mut_name_title ?? set_value("mut_name_title");
    $mut_father_title = $dbrow->form_data->mut_father_title ?? set_value("mut_father_title");
    $mut_first_name = $dbrow->form_data->mut_first_name ?? set_value("mut_first_name");
    $mut_last_name = $dbrow->form_data->mut_last_name ?? set_value("mut_last_name");
    $mut_gender = $dbrow->form_data->mut_gender ?? set_value("mut_gender");
    $mut_father_name = $dbrow->form_data->mut_father_name ?? set_value("mut_father_name");
    $mut_mobile = $dbrow->form_data->mut_mobile ?? set_value("mut_mobile");
    $mut_email = $dbrow->form_data->mut_email ?? set_value("mut_email");
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

    // pre($email);

    $mut_name_title = set_value("mut_applicant_title"); // newly added 
    $mut_father_title = set_value("mut_father_title"); // newly added 

    $mut_first_name = set_value("mut_first_name");
    $mut_last_name = set_value("mut_last_name");
    $mut_gender = set_value("mut_gender");
    $mut_father_name = set_value("mut_father_name");
    // $mother_name = set_value("mother_name");
    $mut_mobile =  set_value("mut_mobile"); //set_value("mobile_number");
    $mut_email = set_value("mut_email");



    $circle_office = set_value("circle_office");
    $mouza_name = set_value("mouza_name");
    $revenue_village = set_value("revenue_village");
    $dag_no = set_value("dag_no");
    $land_area_bigha = set_value("land_area_bigha");
    $patta_type_name = set_value("patta_type_name");

    // pre($land_area_bigha);
    $land_area_loosa = set_value("land_area_loosa");
    $land_area_katha = set_value("land_area_katha");
    $land_area_sq_ft = set_value("land_area_sq_ft");
    $police_station = set_value("police_station");
    $post_office = set_value("post_office");
    $periodic_patta_no = set_value("periodic_patta_no");
    $patta_type = set_value("patta_type");
    // $p_pin_code = set_value("p_pin_code");

    $address_proof_type = "";
    $address_proof = "";
    $identity_proof_type = "";
    $identity_proof = "";
    $land_patta_copy_type = "";
    $land_patta_copy = "";
    $updated_land_revenue_receipt_type = "";
    $updated_land_revenue_receipt = "";
    $Up_to_date_original_land_documents_type = "";
    $Up_to_date_original_land_documents  = "";
    $up_to_date_khajna_receipt_type = "";
    $up_to_date_khajna_receipt = "";
    $copy_of_jamabandi_type = "";
    $copy_of_jamabandi = "";
    $revenue_patta_copy_type = "";
    $revenue_patta_copy = "";

    $circle_office_name = set_value("circle_office_name");
    $mouza_office_name = set_value("mouza_office_name");
    $revenue_village_name = set_value("revenue_village_name");
    $police_station_name = set_value("police_station_name");
// pre($police_station);
    $copy_of_chitha_type = "";
    $copy_of_chitha = "";
    $settlement_land_patta_copy_type = "";
    $settlement_land_patta_copy = "";
    $land_revenue_receipt_type = "";
    $land_revenue_receipt = "";
    $police_verification_report_type = "";
    $police_verification_report = "";
    $photocopy_of_existing_land_documents_type = "";
    $photocopy_of_existing_land_documents = "";
    $no_dues_certificate_from_bank_type = "";
    $no_dues_certificate_from_bank = "";
    // $copy_of_chitha = "";
    // $mut_name_title = "";
    // $mut_father_title = "";

    // $mut_first_name = "";
    // $mut_last_name = "";
    // $mut_gender = "";
    // $mut_father_name = "";
    // $mut_mobile =  "";
    // $mut_email = "";
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/kaac-services') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="service_name" value="<?= $pageTitle ?>" type="hidden" />
            <input name="service_id" value="<?= $pageTitleId ?>" type="hidden" />
            <input name="address_proof_type" value="<?= $address_proof_type ?>" type="hidden" />
            <input name="address_proof" value="<?= $address_proof ?>" type="hidden" />
            <input name="identity_proof_type" value="<?= $identity_proof_type ?>" type="hidden" />
            <input name="identity_proof" value="<?= $identity_proof ?>" type="hidden" />
            <input name="land_patta_copy_type" value="<?= $land_patta_copy_type ?>" type="hidden" />
            <input name="land_patta_copy" value="<?= $land_patta_copy ?>" type="hidden" />
            <input name="updated_land_revenue_receipt_type" value="<?= $updated_land_revenue_receipt_type ?>" type="hidden" />
            <input name="updated_land_revenue_receipt" value="<?= $updated_land_revenue_receipt ?>" type="hidden" />
            <input name="Up_to_date_original_land_documents_type" value="<?= $Up_to_date_original_land_documents_type ?>" type="hidden" />
            <input name="Up_to_date_original_land_documents" value="<?= $Up_to_date_original_land_documents ?>" type="hidden" />
            <input name="copy_of_jamabandi_type" value="<?= $copy_of_jamabandi_type ?>" type="hidden" />
            <input name="copy_of_jamabandi" value="<?= $copy_of_jamabandi ?>" type="hidden" />
            <input name="up_to_date_khajna_receipt_type" value="<?= $up_to_date_khajna_receipt_type ?>" type="hidden" />
            <input name="up_to_date_khajna_receipt" value="<?= $up_to_date_khajna_receipt ?>" type="hidden" />


            <input name="revenue_patta_copy_type" value="<?= $revenue_patta_copy_type ?>" type="hidden" />
            <input name="revenue_patta_copy" value="<?= $revenue_patta_copy ?>" type="hidden" />
            <input name="copy_of_chitha_type" value="<?= $copy_of_chitha_type ?>" type="hidden" />
            <input name="copy_of_chitha" value="<?= $copy_of_chitha ?>" type="hidden" />
            <input name="settlement_land_patta_copy_type" value="<?= $settlement_land_patta_copy_type ?>" type="hidden" />
            <input name="settlement_land_patta_copy" value="<?= $settlement_land_patta_copy ?>" type="hidden" />
            <input name="land_revenue_receipt_type" value="<?= $land_revenue_receipt_type ?>" type="hidden" />
            <input name="land_revenue_receipt" value="<?= $land_revenue_receipt ?>" type="hidden" />
            <input name="police_verification_report_type" value="<?= $police_verification_report_type ?>" type="hidden" />
            <input name="police_verification_report" value="<?= $police_verification_report ?>" type="hidden" />
            <input name="photocopy_of_existing_land_documents_type" value="<?= $photocopy_of_existing_land_documents_type ?>" type="hidden" />
            <input name="photocopy_of_existing_land_documents" value="<?= $photocopy_of_existing_land_documents ?>" type="hidden" />
            <input name="no_dues_certificate_from_bank_type" value="<?= $no_dues_certificate_from_bank_type ?>" type="hidden" />
            <input name="no_dues_certificate_from_bank" value="<?= $no_dues_certificate_from_bank ?>" type="hidden" />


            <?php if ($pageTitleId == "CCM") { ?>

            <?php } ?>
            <!-- <input name="soft_copy_type" value="<?= $soft_copy_type ?>" type="hidden" />
            <input name="soft_copy" value="<?= $soft_copy ?>" type="hidden" /> -->
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b><?= $pageTitle ?><br>
                            <?php switch ($pageTitleId) {
                                case "DCTH":
                                    echo '( চিঠাৰ প্ৰমাণিত কপি )';
                                    break;
                                case "CCJ":
                                    echo '( জামাবন্দীৰ প্ৰমাণিত কপি )';
                                    break;
                                case "CCM":
                                    echo '( মিউটেচনৰ প্ৰমাণিত কপি )';
                                    break;
                                case "DLP":
                                    echo '( ভূমি পট্টাৰ ডুপ্লিকেট কপি )';
                                    break;
                                case "ITMKA":
                                    echo '( ট্ৰেচ মেপ জাৰি কৰা )';
                                    break;
                                case "LHOLD":
                                    echo '( ভূমি ৰখাৰ প্ৰমাণ পত্ৰ )';
                                    break;
                                case "LRCC":
                                    echo '( ভূমি ৰাজহ নিষ্কাশনৰ প্ৰমাণ পত্ৰ )';
                                    break;
                                case "LVC":
                                    echo '( ভূমি মূল্যায়নৰ প্ৰমাণ পত্ৰ )';
                                    break;
                                case "NECKA":
                                    echo '( নন-এনকামব্ৰেন্স প্ৰমাণপত্ৰ )';
                                    break;
                            }
                            ?><b></h4>
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
                        <?php switch ($pageTitleId) {
                            case "DCTH":
                                echo '<legend class="h5">Important / দৰকাৰী </legend>
                                    <strong style="font-size:16px;  margin-top: 10px">Documents to be enclosed with the application:/আবেদনৰ সৈতে সংলগ্ন কৰিবলগীয়া নথি-পত্ৰ :</strong>
                                    <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                                        <li>1. Address Proof [Mandatory]</li>
                                        <li>১. ঠিকনাৰ প্ৰমাণ [বাধ্যতামূলক]</li>
                                        <li>2. Identity Proof [Mandatory]</li>
                                        <li>২. পৰিচয় প্ৰমাণ [বাধ্যতামূলক] </li>
                                        <li>3. Land patta copy [Mandatory]</li>
                                        <li>৩. ভূমি পট্টা কপি [বাধ্যতা]।</li>
                                        <li>4. Updated Land revenue receipt [Mandatory]</li>
                                        <li>৪. আপডেট কৰা ভূমি ৰাজহ ৰচিদ [বাধ্যতামূলক]</li>
            
                                    </ul>
                                    <strong style="font-size:16px;  margin-top: 10px">Charge Details:/চাৰ্জৰ বিৱৰণ :</strong>
                                    <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                                        <li>1. For Council receipt Rs: 2/- per certificate</li>
                                        <li>১. কাউন্সিলৰ ৰচিদৰ বাবে প্ৰতি প্ৰমাণ পত্ৰৰ বাবে ২/- টকা</li>
                                        <li>2. For User Charge Rs: 25/- per certificate</li>
                                        <li>২. ইউজাৰ চাৰ্জৰ বাবে : প্ৰতি প্ৰমাণ পত্ৰত ২৫/- টকা</li>
            
                                    </ul>
                                    <strong style="font-size:16px;  margin-top: 10px">Service Delivery Timeline: 07 days (If there is no complaint / objection):/সেৱা প্ৰদানৰ সময়সীমা: ০৭ দিন (যদি কোনো অভিযোগ / আপত্তি নাথাকে)</strong>';
                                break;
                            case "CCJ":
                                echo '<legend class="h5">Important / দৰকাৰী </legend>
                                    <strong style="font-size:16px;  margin-top: 10px">Documents to be enclosed with the application:/আবেদনৰ সৈতে সংলগ্ন কৰিবলগীয়া নথি-পত্ৰ: </strong>
                                    <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                                        <li>1. Up-to-date Original Land Documents (Patta) [Mandatory]</li>
                                        <li>১. শেহতীয়া মূল ভূমিৰ নথিপত্ৰ (পট্টা) [বাধ্যতামূলক]</li>
                                        <li>2. Up-to date Khajna Receipt [Mandatory]</li>
                                        <li>2. ঋণ ভাড়াৰ ৰচিদ [বাধ্যতামূলক]</li>
            
                                    </ul>
                                    <strong style="font-size:16px;  margin-top: 10px">Charge Details:/চাৰ্জৰ বিৱৰণ :</strong>
                                    <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                                        <li>1. For Tribal people Rs: 27/- per certificate</li>
                                        <li>১. জনজাতীয় লোকৰ বাবে প্ৰতি প্ৰমাণ পত্ৰত ২৭/- টকা</li>
                                        <li>2. For Non-Tribal people Rs: 52/- per certificate</li>
                                        <li>২. অজনজাতীয় লোকৰ বাবে প্ৰতি প্ৰমাণ পত্ৰৰ বাবে ৫২/- টকা</li>
            
                                    </ul>
                                    <strong style="font-size:16px;  margin-top: 10px">Service Delivery Timeline: 07 days (If there is no complaint / objection):/সেৱা প্ৰদানৰ সময়সীমা: ০৭ দিন (যদি কোনো অভিযোগ / আপত্তি নাথাকে)</strong>';
                                break;
                            case "CCM":
                                echo '<legend class="h5">Important / দৰকাৰী </legend>
                                    <strong style="font-size:16px;  margin-top: 10px">Documents to be enclosed with the application:/আবেদনৰ সৈতে সংলগ্ন কৰিবলগীয়া নথি-পত্ৰ</strong>
                                    <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                                        <li>1. Address proof [Mandatory]</li>
                                        <li>১. ঠিকনাৰ প্ৰমাণ [বাধ্যতামূলক]</li>
                                        <li>2. Identity proof [Mandatory]</li>
                                        <li>২. পৰিচয় প্ৰমাণ [বাধ্যতামূলক]</li>
                                        <li>3. Copy of Jamabandi [Mandatory] </li>
                                        <li>৩. জামাবন্দীৰ কপি [বাধ্যতামূলক] </li>
                                        <li>4. Revenue Patta copy [Mandatory] </li>
                                        <li>৪. ৰাজহ পট্টা কপি [বাধ্যতামূলক]</li>
                                        <li>5. Copy of Chitha [Mandatory] </li>
                                        <li>৫. চিঠাৰ কপি [বাধ্যতামূলক] </li>
                                        <li>6. Settlement Land patta copy [Optional] </li>
                                        <li>৬. বসতিৰ ভূমি পট্টা কপি [ঐচ্ছিক] </li>
                                        <li>7. Land revenue receipt [on verification by LM in case of farmer] </li>
                                        <li>৭. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ' . 'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য।</li>
            
                                    </ul>
                                    <strong style="font-size:16px;  margin-top: 10px">Service Delivery Timeline: 07 days (If there is no complaint / objection):/সেৱা প্ৰদানৰ সময়সীমা: ০৭ দিন (যদি কোনো অভিযোগ / আপত্তি নাথাকে)</strong>';
                                break;
                            case "DLP":
                                echo '<legend class="h5">Important / দৰকাৰী </legend>
                                    <strong style="font-size:16px;  margin-top: 10px">Documents to be enclosed with the application:/আবেদনৰ সৈতে সংলগ্ন কৰিবলগীয়া নথি-পত্ৰ</strong>
                                    <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                                        <li>1. Up-to date Khajna Receipt [Mandatory]</li>
                                        <li>১. ঋণ ভাড়াৰ ৰচিদ [বাধ্যতামূলক]</li>
                                        <li>2. Police Verification Report (In Case of lost) [Mandatory]</li>
                                        <li>২. আৰক্ষীৰ পৰীক্ষণ প্ৰতিবেদন (হেৰুৱা হোৱাৰ ক্ষেত্ৰত) [বাধ্যতামূলক]</li>
                                        <li>3. Photocopy of existing Land Documents (Patta) (in case of damage) [Not Mandatory]</li>
                                        <li>৩. বৰ্তমানৰ ভূমি নথিপত্ৰৰ ফটোকপি (পট্টা) (ক্ষতিৰ ক্ষেত্ৰত) [বাধ্যতামূলক নহয়]</li>
                                        <li>4. No Dues Certificate from Bank [ Not Mandatory]</li>
                                        <li>৪. বেংকৰ পৰা কোনো বাবদ প্ৰমাণপত্ৰ [ বাধ্যতামূলক নহয়]</li>
            
                                    </ul>
                                    <strong style="font-size:16px;  margin-top: 10px">Charge Details:/চাৰ্জৰ বিৱৰণ :</strong>
                                    <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                                        <li>1. For Annual Patta: Rs: 17 /- per certificate</li>
                                        <li>১. বাৰ্ষিক পট্টাৰ বাবে: প্ৰতি প্ৰমাণপত্ৰৰ বাবে ১৭ /- টকা</li>
                                        <li>2. For Periodic (Myadi) Patta: Rs: 52/- per certificate</li>
                                        <li>২. সাময়িক (ম্যাদি) পট্টাৰ বাবে: প্ৰতি প্ৰমাণ পত্ৰৰ বাবে ৫২/- টকা</li>
            
                                    </ul>
                                    <strong style="font-size:16px;  margin-top: 10px">Service Delivery Timeline: 07 days (If there is no complaint / objection):/সেৱা প্ৰদানৰ সময়সীমা: ০৭ দিন (যদি কোনো অভিযোগ / আপত্তি নাথাকে)</strong>';
                                break;
                            case "ITMKA":
                                echo '<legend class="h5">Important / দৰকাৰী </legend>
                                    <strong style="font-size:16px;  margin-top: 10px">Documents to be enclosed with the application:/আবেদনৰ সৈতে সংলগ্ন কৰিবলগীয়া নথি-পত্ৰ</strong>
                                    <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                                        <li>1. Address proof [Mandatory]</li>
                                        <li>১. ঠিকনাৰ প্ৰমাণ [বাধ্যতামূলক]</li>
                                        <li>2. Identity proof [Mandatory]</li>
                                        <li>২. পৰিচয় প্ৰমাণ [বাধ্যতামূলক]</li>
                                        <li>3. Land patta copy [Mandatory] </li>
                                        <li>৩. ভূমি পট্টা কপি [বাধ্যতামূলক] </li>
                                        <li>4. Settlement Land patta copy [Optional] </li>
                                        <li>৪. বসতিৰ ভূমি পট্টা কপি [ঐচ্ছিক] </li>
                                        <li>5. Land revenue receipt [on verification by LM in case of farmer] </li>
                                        <li>৫. ভূমি ৰাজহৰ ৰচিদ [কৃষকৰ ক্ষেত্ৰত এল এমৰ দ্বাৰা পৰীক্ষণত]।</li>
            
                                    </ul>
                                    <strong style="font-size:16px;  margin-top: 10px">Service Delivery Timeline: 07 days (If there is no complaint / objection):/সেৱা প্ৰদানৰ সময়সীমা: ০৭ দিন (যদি কোনো অভিযোগ / আপত্তি নাথাকে)</strong>';
                                break;
                            case "LHOLD":
                                echo '<legend class="h5">Important / দৰকাৰী </legend>
                                    <strong style="font-size:16px;  margin-top: 10px">Documents to be enclosed with the application:/আবেদনৰ সৈতে সংলগ্ন কৰিবলগীয়া নথি-পত্ৰ</strong>
                                    <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                                        <li>1. Address proof [Mandatory]</li>
                                        <li>১. ঠিকনাৰ প্ৰমাণ [বাধ্যতামূলক]</li>
                                        <li>2. Identity proof [Mandatory]</li>
                                        <li>২. পৰিচয় প্ৰমাণ [বাধ্যতামূলক]</li>
                                        <li>3. Land patta copy [Mandatory] </li>
                                        <li>৩. ভূমি পট্টা কপি [বাধ্যতামূলক] </li>
                                        <li>4. Updated Land revenue receipt [Mandatory] </li>
                                        <li>৪. আপডেট কৰা ভূমি ৰাজহ ৰচিদ[বাধ্যতামূলক] </li>
                                    </ul>
                                    <strong style="font-size:16px;  margin-top: 10px">Charge Details:/চাৰ্জৰ বিৱৰণ :</strong>
                                    <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                                        <li>1. For Council receipt Rs: 2/- per certificate</li>
                                        <li>১. কাউন্সিলৰ ৰচিদৰ বাবে প্ৰতি প্ৰমাণ পত্ৰৰ বাবে ২/- টকা</li>
                                        <li>2. For User Charge Rs: 25/- per certificate</li>
                                        <li>২. ইউজাৰ চাৰ্জৰ বাবে : প্ৰতি প্ৰমাণ পত্ৰত ২৫/- টকা</li>
                                    </ul>

                                    <strong style="font-size:16px;  margin-top: 10px">Service Delivery Timeline: 07 days (If there is no complaint / objection):/সেৱা প্ৰদানৰ সময়সীমা: ০৭ দিন (যদি কোনো অভিযোগ / আপত্তি নাথাকে)</strong>';
                                break;
                            case "LRCC":
                                echo '<legend class="h5">Important / দৰকাৰী </legend>
                                    <strong style="font-size:16px;  margin-top: 10px">Documents to be enclosed with the application:/আবেদনৰ সৈতে সংলগ্ন কৰিবলগীয়া নথি-পত্ৰ</strong>
                                    <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                                        <li>1. Land patta copy [Mandatory]</li>
                                        <li>১. ভূমি পট্টা কপি [বাধ্যতামূলক]</li>
                                        <li>2. Last time paid Land revenue receipt (on verification by LM in case of farmer)</li>
                                        <li>২. শেষবাৰৰ বাবে দিয়া ভূমি ৰাজহৰ ৰচিদ (কৃষকৰ ক্ষেত্ৰত এল এমৰ দ্বাৰা পৰীক্ষণত)</li>
            
                                    </ul>
                                    <strong style="font-size:16px;  margin-top: 10px">Service Delivery Timeline: 07 days (If there is no complaint / objection):/সেৱা প্ৰদানৰ সময়সীমা: ০৭ দিন (যদি কোনো অভিযোগ / আপত্তি নাথাকে)</strong>';
                                break;
                            case "LVC":
                                echo '<legend class="h5">Important / দৰকাৰী </legend>
                                    <strong style="font-size:16px;  margin-top: 10px">Documents to be enclosed with the application:/আবেদনৰ সৈতে সংলগ্ন কৰিবলগীয়া নথি-পত্ৰ</strong>
                                    <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                                        <li>1. Up-to-date Original Land Documents (Patta) [Mandatory]</li>
                                        <li>১. শেহতীয়া মূল ভূমিৰ নথিপত্ৰ (পট্টা) [বাধ্যতামূলক]</li>
                                        <li>2. Up-to date Khajna Receipt [Mandatory]</li>
                                        <li>২. ঋণ ভাড়াৰ ৰচিদ [বাধ্যতামূলক]</li>
            
                                    </ul>
                                    <strong style="font-size:16px;  margin-top: 10px">Charge Details:/চাৰ্জৰ বিৱৰণ :</strong>
                                    <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                                        <li>1. Town commercial land for non-tribal: Rs: 102 /- per katha</li>
                                        <li>১. অজনজাতিৰ বাবে টাউন বাণিজ্যিক ভূমিঃ প্ৰতি কঠাত ১০২ /- টকা</li>
                                        <li>2. Town commercial land for tribal: Rs: 52 /- per katha</li>
                                        <li>২. জনজাতীয় লোকৰ বাবে টাউন বাণিজ্যিক ভূমিঃ প্ৰতি কঠাত ৫২ /- টকা</li>
                                        <li>3. Town residential land for non-tribal: Rs: 82 /- per katha</li>
                                        <li>৩. অজনজাতিৰ বাবে চহৰৰ আৱাসিক মাটিঃ প্ৰতি কঠাত ৮২ /- টকা</li>
                                        <li>4. Town residential land for tribal: Rs: 42 /- per katha</li>
                                        <li>৪. জনজাতীয় লোকৰ বাবে চহৰৰ আৱাসিক মাটিঃ প্ৰতি কঠাত ৪২ /- টকা</li>
                                        <li>5. Rural land valuation certificate for non-tribal: Rs: 202 /- per katha</li>
                                        <li>৫. অজনজাতিৰ বাবে গ্ৰাম্য ভূমিৰ মূল্য নিৰ্ধাৰণৰ প্ৰমাণ পত্ৰঃ প্ৰতি কঠাত ২০২ /- টকা</li>
                                        <li>6. Rural land valuation certificate for tribal: Rs: 102 /- per katha</li>
                                        <li>৬. জনজাতিৰ বাবে গ্ৰাম্য ভূমিৰ মূল্য নিৰ্ধাৰণৰ প্ৰমাণ পত্ৰঃ প্ৰতি কঠাত ১০২ /- টকা</li>
            
                                    </ul>
                                    <strong style="font-size:16px;  margin-top: 10px">Service Delivery Timeline: 07 days (If there is no complaint / objection):/সেৱা প্ৰদানৰ সময়সীমা: ০৭ দিন (যদি কোনো অভিযোগ / আপত্তি নাথাকে)</strong>';
                                break;
                            case "NECKA":
                                echo '<legend class="h5">Important / দৰকাৰী </legend>
                                    <strong style="font-size:16px;  margin-top: 10px">Documents to be enclosed with the application:/আবেদনৰ সৈতে সংলগ্ন কৰিবলগীয়া নথি-পত্ৰ</strong>
                                    <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                                        <li>1. Up-to-date Original Land Documents (Patta) [Mandatory]</li>
                                        <li>১. শেহতীয়া মূল ভূমিৰ নথিপত্ৰ (পট্টা) [বাধ্যতামূলক]</li>
                                        <li>2. Up-to date Khajna Receipt [Mandatory]</li>
                                        <li>২. ঋণ ভাড়াৰ ৰচিদ [বাধ্যতামূলক]</li>
            
                                    </ul>
                                    <strong style="font-size:16px;  margin-top: 10px">Charge Details:/চাৰ্জৰ বিৱৰণ :</strong>
                                    <ul style="  margin-left: 24px; margin-top: 10px" class="instructions">
                                        <li>1. For Tribal People Rs: 27 /- per certificate</li>
                                        <li>১. জনজাতীয় লোকৰ বাবে প্ৰতি প্ৰমাণ পত্ৰত ২৭ /- টকা</li>
                                        <li>2. For Non Tribal People Rs: 52 /- per certificate</li>
                                        <li>২. অজনজাতীয় লোকৰ বাবে প্ৰতি প্ৰমাণ পত্ৰৰ বাবে ৫২ /- টকা</li>
                                    </ul>
                                    <strong style="font-size:16px;  margin-top: 10px">Service Delivery Timeline: 07 days (If there is no complaint / objection):/সেৱা প্ৰদানৰ সময়সীমা: ০৭ দিন (যদি কোনো অভিযোগ / আপত্তি নাথাকে)</strong>';
                                break;
                        }
                        ?>

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
                                                <option value="Sri" <?= ($applicant_title === "Sri") ? 'selected' : '' ?>>
                                                    Sri</option>
                                                <option value="Smt" <?= ($applicant_title === "Smt") ? 'selected' : '' ?>>
                                                    Smt</option>
                                                <option value="Dr" <?= ($applicant_title === "Dr") ? 'selected' : '' ?>>
                                                    Late</option>
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

                    <?php if ($pageTitleId == "CCM") { ?>
                        <fieldset class="border border-success" style="margin-top:40px">
                            <legend class="h5">Mutation order in the Name of/ নামৰ পৰিৱর্তনকাৰীৰ তথ্য </legend>

                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label>First Name/ প্ৰথম নাম<span class="text-danger">*</span> </label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text p-1">
                                                <select name="mut_name_title">
                                                    <option value="">Select</option>
                                                    <option value="Mr" <?= ($mut_name_title === "Mr") ? 'selected' : '' ?>>Mr
                                                    </option>
                                                    <option value="Mrs" <?= ($mut_name_title === "Mrs") ? 'selected' : '' ?>>
                                                        Mrs</option>
                                                    <option value="Sri" <?= ($mut_name_title === "Sri") ? 'selected' : '' ?>>
                                                        Sri</option>
                                                    <option value="Smt" <?= ($mut_name_title === "Smt") ? 'selected' : '' ?>>
                                                        Smt</option>
                                                    <option value="Late" <?= ($mut_name_title === "Late") ? 'selected' : '' ?>>
                                                        Late</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="mut_first_name" id="mut_first_name" value="<?= $mut_first_name ?>" maxlength="255" />
                                    </div>
                                    <?= form_error("mut_name_title") . form_error("mut_first_name") ?>

                                </div>

                                <div class="col-md-4">
                                    <label>Last Name/ উপাধি<span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="mut_last_name" id="mut_last_name" value="<?= $mut_last_name ?>" maxlength="255" />
                                    <?= form_error("mut_last_name") ?>
                                </div>
                                <div class="col-md-4">
                                    <label> Gender/ লিংগ <span class="text-danger">*</span> </label>
                                    <select name="mut_gender" class="form-control">
                                        <option value="">Please Select</option>
                                        <option value="Male" <?= ($mut_gender === "Male") ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= ($mut_gender === "Female") ? 'selected' : '' ?>>Female</option>
                                        <option value="Transgender" <?= ($mut_gender === "Transgender") ? 'selected' : '' ?>>Transgender</option>
                                    </select>
                                    <?= form_error("mut_gender") ?>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label>Father&apos;s/Husband&apos;s Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text p-1">
                                                <select name="mut_father_title">
                                                    <option value="">Select</option>
                                                    <option value="Mr" <?= ($mut_father_title === "Mr") ? 'selected' : '' ?>>Mr
                                                    </option>
                                                    <option value="Mrs" <?= ($mut_father_title === "Mrs") ? 'selected' : '' ?>>
                                                        Mrs</option>
                                                    <option value="Sri" <?= ($mut_father_title === "Sri") ? 'selected' : '' ?>>
                                                        Sri</option>
                                                    <option value="Smt" <?= ($mut_father_title === "Smt") ? 'selected' : '' ?>>
                                                        Smt</option>
                                                    <option value="Late" <?= ($mut_father_title === "Late") ? 'selected' : '' ?>>
                                                        Late</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="text" class="form-control" name="mut_father_name" id="mut_father_name" value="<?= $mut_father_name ?>" maxlength="255" />
                                    </div>
                                    <?= form_error("mut_father_title") . form_error("mut_father_name") ?>

                                </div>

                                <div class="col-md-4">
                                    <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                    <?php if ($usser_type === "user") { ?>
                                        <input type="text" class="form-control" name="mut_mobile" value="<?= $mut_mobile ?>" maxlength="10" />
                                    <?php } else { ?>
                                        <input type="text" class="form-control" name="mut_mobile" value="<?= $mut_mobile ?>" maxlength="10" />
                                    <?php } ?>

                                    <?= form_error("mut_mobile") ?>
                                </div>
                                <div class="col-md-4">
                                    <label>E-Mail / ই-মেইল </label>
                                    <input type="text" class="form-control" name="mut_email" value="<?= $mut_email ?>" maxlength="100" />
                                    <?= form_error("mut_email") ?>
                                </div>
                            </div>
                        </fieldset>
                    <?php } ?>


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

                    <fieldset class="border border-success" style="margin-top:40px">
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
                                    <?php if ($pageTitleId == "DLP") { ?>
                                        <option value="1" <?= ($patta_type === "1") ? 'selected' : '' ?>>Khiraj Miyadi</option>
                                        <option value="2" <?= ($patta_type === "2") ? 'selected' : '' ?>>Nisfi Khiraj</option>
                                    <?php } else { ?>
                                        <option value="1" <?= ($patta_type === "1") ? 'selected' : '' ?>>Periodic Patta (Myadi)</option>
                                    <?php } ?>
                                </select>
                                <?= form_error("patta_type") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
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
            url: "<?= base_url("spservices/kaac/registration/mouza"); ?>",
            type: 'POST',
            // contentType: "application/json",
            data: {
                "jsonbody": circle_id
            },
            dataType: 'json',
            success: function(res) {
                var obj = $.parseJSON(res);
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
            url: "<?= base_url("spservices/kaac/registration/revenue_village"); ?>",
            type: 'POST',
            // contentType: "application/json",
            data: {
                "jsonbody": mouza_id
            },
            dataType: 'json',
            success: function(res) {
                var obj = $.parseJSON(res);
                $.each(obj.records, function(i, val) {
                    id = val.village_id;
                    $("#revenue_village").append('<option value="' + val.village_id + '">' + val.village_name + '</option>');
                });
            }
        });
    });
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
        console.log(value);
        $('#patta_type_name').val(value);
    })
</script>