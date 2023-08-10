<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
// $apiServer = "https://localhost/wptbcapis/"; //For testing
$apiServer = "https://localhost/gad_apis/"; //For testing
if ($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_status = $dbrow->service_data->appl_status;
    $process = $dbrow->processing_history;
    // pre($process[array_key_last($process)]);
    $latestAction = $process[array_key_last($process)];
    $slug = $dbrow->service_data->rtps_trans_id;
    $certificate_language = $dbrow->form_data->certificate_language;
    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $spouse_name = $dbrow->form_data->spouse_name;
    $email = $dbrow->form_data->email;
    $dob = $dbrow->form_data->dob;
    $mobile_number = $dbrow->form_data->mobile_number;
    $aadhaar_number = $dbrow->form_data->aadhaar_number;
    $pan_number = $dbrow->form_data->pan_number;

    $house_no = $dbrow->form_data->house_no;
    // $landmark = $dbrow->form_data->landmark;
    $state = $dbrow->form_data->state;
    $district = $dbrow->form_data->district;
    $district_id = $dbrow->form_data->district_id;
    $sub_division = $dbrow->form_data->sub_division;
    $sub_division_id = $dbrow->form_data->sub_division_id;
    $revenue_circle = $dbrow->form_data->revenue_circle;
    $revenue_circle_id = $dbrow->form_data->revenue_circle_id;
    $mouza = $dbrow->form_data->mouza;
    $village = $dbrow->form_data->village;
    $police_station = $dbrow->form_data->police_station;
    $pin_code = $dbrow->form_data->pin_code;
    $post_office = $dbrow->form_data->post_office;

    $caste_name = $dbrow->form_data->caste_name;
    $community_name = $dbrow->form_data->community_name;
    $relation = $dbrow->form_data->financial_status->relation;
    $organization_types = $dbrow->form_data->financial_status->organization_types;
    $organization_names = $dbrow->form_data->financial_status->organization_names;
    $fs_designations = $dbrow->form_data->financial_status->fs_designations;

    // $isearning_sources = $dbrow->income_status->isearning_sources;
    $annual_salary = $dbrow->form_data->financial_status->annual_salary;
    $other_income = $dbrow->form_data->financial_status->other_income;
    $total_income = $dbrow->form_data->financial_status->total_income;
    // $is_remarks = $dbrow->income_status->is_remarks;

    //DOcuments
    $residential_proof_type = $dbrow->form_data->residential_proof_type ?? NULL;
    $residential_proof = $dbrow->form_data->residential_proof ?? NULL;
    $obc_type = $dbrow->form_data->obc_type ?? NULL;
    $obc = $dbrow->form_data->obc ?? NULL;
    $income_certificate_type = $dbrow->form_data->income_certificate_type ?? NULL;
    $income_certificate = $dbrow->form_data->income_certificate ?? NULL;
    $other_doc_type = $dbrow->form_data->other_doc_type ?? NULL;
    $other_doc = $dbrow->form_data->other_doc ?? NULL;
    $soft_copy_type = $dbrow->form_data->soft_copy_type ?? NULL;
    $soft_copy = $dbrow->form_data->soft_copy ?? NULL;
    $remarks = $dbrow->form_data->remarks ?? NULL;
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
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script type="text/javascript">
$(document).ready(function() {
    $(".dp").datepicker({
        format: 'dd-mm-yyyy',
        endDate: '+0d',
        autoclose: true
    });

    $.post("<?= base_url('spservices/noncreamylayercertificate/registration/createcaptcha') ?>", function(res) {
        $("#captchadiv").html(res);
    });

    $(document).on("click", "#reloadcaptcha", function() {
        $.post("<?= base_url('spservices/noncreamylayercertificate/registration/createcaptcha') ?>",
            function(res) {
                $("#captchadiv").html(res);
            });
    }); //End of onChange #reloadcaptcha

    $(document).on("click", "#addfstblrow", function() {
        let totRows = $('#financialstatustbl tr').length;
        var trow = `<tr>
                            <td>
                                <select class="form-control" name="relation[]">
                                    <option value="">Please Select</option>
                                    <option value="Mother/মাতৃ"> Mother/মাতৃ</option>
                                    <option value="Father/পিতৃ"> Father/পিতৃ</option>
                                    <option value="Husband/স্বামী"> Husband/স্বামী</option>
                                    <option value="Wife/পত্নী"> Wife/পত্নী</option>
                                </select>
                            </td>
                            <td><input name="organization_types[]" class="form-control" type="text" /></td>
                            <td><input name="organization_names[]" class="form-control" type="text" /></td>
                            <td><input name="fs_designations[]" class="form-control" type="text" /></td>
                            <td><input name="annual_salary[]" class="form-control" type="number" /></td>
                            <td><input name="other_income[]" class="form-control" type="number" /></td>
                            <td><input name="total_income[]" class="form-control" type="number" /></td>
                            <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
        if (totRows <= 10) {
            $('#financialstatustbl tr:last').after(trow);
        }
    });

    $(document).on("click", "#addistblrow", function() {
        let totRows = $('#incomestatustbl tr').length;
        var trow = `<tr>
                            <td>
                                <select class="form-control" name="isearning_sources[]">
                                    <option value="">Please Select</option>
                                    <option value="Mother/মাতৃ"> Mother/মাতৃ</option>
                                    <option value="Father/পিতৃ"> Father/পিতৃ</option>
                                    <option value="Husband/স্বামী"> Husband/স্বামী</option>
                                    <option value="Wife/পত্নী"> Wife/পত্নী</option>
                                </select>
                            </td>
                            <td><input name="annual_salary[]" class="form-control" type="text" /></td>
                            <td><input name="other_income[]" class="form-control" type="text" /></td>
                            <td><input name="total_income[]" class="form-control" type="text" /></td>
                            <td><input name="is_remarks[]" class="form-control" type="text" /></td>
                            <td style="text-align:center"><button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button></td>
                        </tr>`;
        if (totRows <= 10) {
            $('#incomestatustbl tr:last').after(trow);
        }
        $(".dp").datepicker({
            format: 'dd-mm-yyyy',
            endDate: '+0d',
            autoclose: true
        });
    });

    $(document).on("click", ".deletetblrow", function() {
        $(this).closest("tr").remove();
        return false;
    });


    $.getJSON("<?= $apiServer . "district_list.php" ?>", function(data) {
        let selectOption = '';
        $.each(data.ListOfDistricts, function(key, value) {
            selectOption += '<option value="' + value.DistrictName + '" data-district_id="' +
                value
                .DistrictId + '">' + value
                .DistrictName + '</option>';
        });
        $('.dists').append(selectOption);
    });

    $(document).on("change", "#district", function() {
        let selectedVal = $(this).val();
        let district_id_val = $(this).find('option:selected').attr("data-district_id");
        $("#district_id").val(district_id_val);
        if (selectedVal.length) {
            var myObject = new Object();
            myObject.district_name = selectedVal;
            $.getJSON("<?= $apiServer ?>sub_division_list.php?jsonbody=" + JSON.stringify(myObject),
                function(data) {
                    let selectOption = '';
                    $('#sub_division').empty().append(
                        '<option value="">Select a sub division</option>')
                    $.each(data.records, function(key, value) {
                        selectOption += '<option value="' + value.subdiv_name +
                            '" data-sub_division_id="' +
                            value
                            .subdiv_id + '">' + value
                            .subdiv_name + '</option>';
                    })
                    $('#sub_division').append(selectOption);
                }).fail(function() {
                $('#sub_division').empty().append(
                    '<option value="">Select a sub division</option>')
            });
        }
    });



    $(document).on("change", "#sub_division", function() {
        let selectedVal = $(this).val();
        let sub_division_id = $(this).find('option:selected').attr("data-sub_division_id");
        $("#sub_division_id").val(sub_division_id);
        if (selectedVal.length) { //alert(selectedVal);
            var myObject = new Object();
            myObject.subdiv_name = selectedVal; //alert(JSON.stringify(myObject));
            $.getJSON("<?= $apiServer ?>revenue_circle_list.php?jsonbody=" + JSON.stringify(
                    myObject),
                function(data) {
                    let selectOption = '';
                    $('#revenue_circle').empty().append(
                        '<option value="">Select a revenue circle</option>');
                    $.each(data.records, function(key, value) {
                        selectOption += '<option value="' + value.circle_name +
                            '" data-revenue_circle_id="' +
                            value
                            .circle_id + '">' + value
                            .circle_name + '</option>';
                    })
                    $('#revenue_circle').append(selectOption);
                }).fail(function() {
                $('#revenue_circle').empty().append(
                    '<option value="">Select a revenue circle</option>');
            });
        }
    });

    $(document).on("change", "#revenue_circle", function() {
        let selectedVal = $(this).val();
        let revenue_circle_id = $(this).find('option:selected').attr("data-revenue_circle_id");
        $("#revenue_circle_id").val(revenue_circle_id);
    });



    $(document).on("click", ".frmbtn", function() {
        let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
        $("#submit_mode").val(clickedBtn);
        if (clickedBtn === 'DRAFT') {
            var msg =
                "You want to save in Draft mode that will allows you to edit and can submit later";
        } else if (clickedBtn === 'SAVE') {
            var msg = "Once you submitted, you won't able to revert this";
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
        <form id="myfrm" method="POST"
            action="<?= base_url('spservices/noncreamylayercertificate/registration/querysubmit') ?>"
            enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input id="appl_status" name="appl_status" value="<?= strlen($appl_status) ? $appl_status : "DRAFT" ?>"
                type="hidden" />
            <input name="rtps_trans_id" value="<?= $slug ?>" type="hidden" />
            <input id="submit_mode" name="submit_mode" value="" type="hidden" />
            <input name="residential_proof_type" value="<?= $residential_proof_type ?>" type="hidden" />
            <input name="residential_proof" value="<?= $residential_proof ?>" type="hidden" />
            <input name="obc_type" value="<?= $obc_type ?>" type="hidden" />
            <input name="obc" value="<?= $obc ?>" type="hidden" />
            <input name="income_certificate_type" value="<?= $income_certificate_type ?>" type="hidden" />
            <input name="income_certificate" value="<?= $income_certificate ?>" type="hidden" />
            <input name="other_doc_type" value="<?= $other_doc_type ?>" type="hidden" />
            <input name="other_doc" value="<?= $other_doc ?>" type="hidden" />
            <input name="soft_copy_type" value="<?= $soft_copy_type ?>" type="hidden" />
            <input name="soft_copy" value="<?= $soft_copy ?>" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Issuance Of Non Creamy Layer Certificate<br>
                    ( অনা উচ্চস্তৰীয় স্তৰৰ প্রমান পত্রৰ বাবে আবেদন )
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
                    <?php }
                    if ($appl_status === 'QS') { ?>
                    <fieldset class="border border-danger" style="margin-top:40px; margin-bottom: 2px">
                        <legend class="h5">QUERY DETAILS </legend>
                        <div class="row">
                            <div class="col-md-12" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                <?= $latestAction->remarks ?? '' ?>
                            </div>
                        </div>
                        <span style="float:right; font-size: 12px">
                            Query time :
                            <?= isset($latestAction->processing_time) ? format_mongo_date($latestAction->processing_time) : '' ?>
                        </span>
                    </fieldset>
                    <?php } //End of if 
                    ?>

                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px; ">Supporting Document / সহায়ক নথি পত্ৰ</strong>

                        <ol style="list-style:decimal-leading-zero;  margin-left: 24px; margin-top: 20px">
                            <li>
                                <!-- Permanent Resident Certificate (PRC). In case PRC is not available voter ID/Electricity
                                bill/Bank Passbook/registered land documents/Jamabandi are accepted in practice
                                [Mandatory]
                                / স্হায়ী বাসিন্দাৰ পত্ৰ । ২. যদি স্হায়ী বাসিন্দাৰ প্ৰমান পত্ৰ নাথাকে, তেন্তে ভোটাৰ
                                কাৰ্ড/বিদুৎ বিল/ বেঙ্ক পাছবুক/ পঞ্জীকৃত মাটিৰ নথি/ জমাবন্দী ( বাধ্যতামূলক ) -->
                                Permanent resident certificate or any other proof of residency [Mandatory] (স্হোনীয়
                                োচসন্দোৰ প্রমোন িত্র ো োচসন্দো সম্পবকজ চযকবনো প্রমোন িত্র [ বাধ্যতামূলক ])
                            </li>
                            <li>
                                OBC / MOBC certificate issued by competent authority [Mandatory]
                                / সংচিষ্ট কতৃজিক্ষৰ িৰো মিোৱো অনযোনয চিছিৰো মেণী / অনযোনয অচত চিছিৰো মেণীৰ প্রমোন িত্র (
                                বাধ্যতামূলক )
                            </li>
                            <li>
                                Income certificate of parents [Mandatory]
                                / পিতৃ মাতৃৰ আয়ৰ প্ৰমাণপত্ৰ ( বাধ্যতামূলক )
                                <ol style="list-style:lower-alpha;  margin-left: 20px; margin-top: 0px">
                                    <li>Issued by the Circle Officer ( if the parents are agriculturist ) or / (ক) চক্ৰ
                                        বিষয়াৰ দ্বাৰা ( যদি পিতৃ মাতৃ খেতিয়ক হয় )</li>
                                    <li>Income certificate of parents issued by Controlling Authority / Treasury officer
                                        (if the parents are retired salaried person) or / (খ) কোষাগাৰ বিষয়াৰ দ্বাৰা,
                                        যদিহে আবেদনকাৰী পেঞ্চনধাৰী হয় ।</li>
                                    <!-- <li>Issued by Councillor/Mouzadar (in practice, in case of a non-salaried person) /
                                        (গ) কাউন্সিলৰ/মৌজাদাৰৰ দ্বাৰা, যদিহে আবেদনকাৰী দৰমহাবিহীন হয় ।</li> -->
                                </ol>
                            </li>
                            <li>
                                Other documents as per requirement (Voter card, Bank passbook, etc.) [Optional]
                                / অন্যান্য নথি যেনে- ভোটাৰ কাৰ্ড, বেঙ্ক পাছবুক
                            </li>
                        </ol>


                        <strong style="font-size:16px;  margin-top: 10px">Fees / মাচুল :</strong>
                        <ol style="list-style:decimal-leading-zero;  margin-left: 24px; margin-top: 10px">
                            <li>Statutory charges / স্হায়ী মাচুল : Rs. 30 / ৩০ টকা</li>
                            <li>Service charge / সেৱা মাচুল – Rs. 30 / ৩০ টকা</li>
                            <li>Printing charge (in case of any printing from PFC) / ছপা খৰচ - Rs. 10 Per Page / প্ৰতি
                                পৃষ্ঠাত ১০ টকা</li>
                            <li>Scanning charge (in case documents are scanned in PFC) স্কেনিং খৰচ - Rs. 5 Per page /
                                প্ৰতি পৃষ্ঠা ৫ টকা</li>
                        </ol>
                    </fieldset>

                    <!-- end(array_values($dbrow->processing_history)) -->

                    <fieldset class=" border border-success" style="margin-top:40px">
                        <legend class="h5">Language of certificate / প্রমান পত্রৰ ভাষা <span
                                class="text-danger ">*</span></legend>
                        <div class="d-flex space-x-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="certificate_language" id="english"
                                    value="English" <?= ($certificate_language === "English") ? 'checked' : '' ?> />
                                <label class="form-check-label" for="english">English</lable>
                            </div>
                            <div class="form-check ml-3">
                                <input class="form-check-input" type="radio" name="certificate_language" id="assamese"
                                    value="Assamese" <?= ($certificate_language === "Assamese") ? 'checked' : '' ?> />
                                <label class="form-check-label" for="assamese">Assamese</lable>
                            </div>
                            <div class="form-check ml-3">
                                <input class="form-check-input" type="radio" name="certificate_language" id="bengali"
                                    value="Bengali" <?= ($certificate_language === "Bengali") ? 'checked' : '' ?> />
                                <label class="form-check-label" for="bengali">Bengali</lable>
                            </div>
                            <div class="form-check ml-3">
                                <input class="form-check-input" type="radio" name="certificate_language" id="bodo"
                                    value="Bodo" <?= ($certificate_language === "Bodo") ? 'checked' : '' ?> />
                                <label class="form-check-label" for="bodo">Bodo</lable>
                            </div>
                            <?= form_error("certificate_language") ?>
                        </div>
                        <!-- </div> -->
                    </fieldset>

                    <fieldset class=" border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="applicant_name" id="applicant_name"
                                        value="<?= $applicant_name ?>" maxlength="255"  readonly/>
                                </div>
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span>
                                </label>
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="applicant_gender" id="applicant_gender"
                                        value="<?= $applicant_gender ?>" maxlength="255"  readonly/>
                                </div>
                               
                                <?= form_error("applicant_gender") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Father&apos;s Name / পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name"
                                    value="<?= $father_name ?>" maxlength="255" readonly/>
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mother&apos;s Name/ মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" value="<?= $mother_name ?>"
                                    maxlength="255" readonly/>
                                <?= form_error("mother_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Spouse Name / পতি/পত্নীৰ নাম</label>
                                <input type="text" class="form-control" name="spouse_name" value="<?= $spouse_name ?>"
                                    maxlength="255" readonly/>
                                <?= form_error("spouse_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল </label>
                                <input type="text" class="form-control" name="email" value="<?= $email ?>"
                                    maxlength="255" readonly/>
                                <?= form_error("email") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Date of Birth / জন্মৰ তাৰিখ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control dp" name="dob" value="<?= $dob ?>"
                                    maxlength="10" readonly/>
                                <?= form_error("dob") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile Number / মবাইল নম্বৰ <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile_number"
                                    value="<?= $mobile_number ?>" maxlength="10"  readonly/>
                                <?= form_error("mobile_number") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Aadhaar Number / আধাৰ নম্বৰ</label>
                                <input type="text" class="form-control number_input" name="aadhaar_number"
                                    value="<?= $aadhaar_number ?>" maxlength="16" readonly/>
                                <?= form_error("aadhaar_number") ?>
                            </div>
                            <div class="col-md-6">
                                <label>PAN Number / পান নম্বৰ</label>
                                <input type="text" class="form-control" name="pan_number" value="<?= $pan_number ?>"
                                    maxlength="10" readonly/>
                                <?= form_error("pan_number") ?>
                            </div>
                        </div>
                    </fieldset>




                                        <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address / স্হায়ী ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>State / ৰাজ্য<span class="text-danger">*</span> </label>
                                <!--
                                <select name="state" class="form-control">
                                    <option value="Assam" selected="selected" >Assam</option>
                                </select>
                                -->
                                <input type="text" class="form-control" name="state" id="state" value="<?= $state ?>" maxlength="10" readonly/>

                                <?= form_error("state") ?>
                            </div>
                            <div class="col-md-6">
                                <label>District/ জিলা<span class="text-danger">*</span> </label>
                                 <input type="text" class="form-control" name="district" id="district" value="<?= $district ?>" maxlength="10" readonly/>
                             
                                <input class="d-none" type="text" value="<?= $district_id ?>" name="district_id"
                                    id="district_id" />
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Sub Division / মহকুমা<span class="text-danger">*</span> </label>
                                <!--
                                <select name="sub_division" id="sub_division" class="form-control">
                                    <option value="<?= $sub_division ?>">
                                        <?= strlen($sub_division) ? $sub_division : 'Select Sub Division' ?></option>
                                </select>
                                -->
                                 <input type="text" class="form-control" name="sub_division" id="sub_division" value="<?= $sub_division ?>" maxlength="10" readonly/>

                                <?= form_error("sub_division") ?>
                                <input class="d-none" type="text" value="<?= $sub_division_id ?>" name="sub_division_id"
                                    id="sub_division_id" />
                                
                            </div>
                            
                            <div class="col-md-6">
                                <label>Revenue Circle / ৰাজহ চক্ৰ<span class="text-danger">*</span> </label>
                                <!--
                                <select name="revenue_circle" id="revenue_circle" class="form-control">
                                    <option value="<?= $revenue_circle ?>">
                                        <?= strlen($revenue_circle) ? $revenue_circle : 'Select Revenue Circle' ?>
                                    </option>
                                </select>
                                -->
                                 <input type="text" class="form-control" name="revenue_circle" id="revenue_circle" value="<?= $revenue_circle ?>" maxlength="10" readonly/>

                                <?= form_error("revenue_circle") ?>
                                <input class="d-none" type="text" value="<?= $revenue_circle_id ?>"
                                    name="revenue_circle_id" id="revenue_circle_id" />
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Village or Town / গাওঁ/চহৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="village" value="<?= $village ?>"
                                    maxlength="255" readonly/>
                                <?= form_error("village") ?>
                            </div>

                            <div class="col-md-6">
                                <label>Post Office / ডাকঘৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="post_office" value="<?= $post_office ?>"
                                    maxlength="255" readonly/>
                                <?= form_error("post_office") ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mouza / মৌজা<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mouza" value="<?= $mouza ?>"
                                    maxlength="255" readonly/>
                                <?= form_error("mouza") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Police Station / থানা<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="police_station"
                                    value="<?= $police_station ?>" maxlength="255" readonly/>
                                <?= form_error("police_station") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>House No / ঘৰ নং<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="house_no" value="<?= $house_no ?>"
                                    maxlength="255" readonly/>
                                <?= form_error("house_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Pin Code / পিনকোড<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pin_code" value="<?= $pin_code ?>"
                                    maxlength="6" minlength="6" readonly/>
                                <?= form_error("pin_code") ?>
                            </div>
                        </div>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px; overflow:hidden;">
                        <legend class="h5">Other Details / অন্য বিৱৰণ </legend>
                         <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name of Caste / জাতি<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="caste_name" name="caste_name" value="<?= $caste_name ?>"
                                    maxlength="255" readonly/>
                                <?= form_error("caste_name") ?>
                            </div>
                            <!--
                            <div class="col-md-6">
                                <label>Name of Caste / জাতিৰ নাম<span class="text-danger">*</span> </label>
                                <select name="caste_name" id="caste_name" class="form-control">
                                    <option value="">Select name of caste</option>
                                    <option value="OBC" <?= ($caste_name === "OBC") ? 'selected' : '' ?>>OBC
                                    </option>
                                    <option value="MOBC" <?= ($caste_name === "MOBC") ? 'selected' : '' ?>>
                                        MOBC</option>
                                </select>
                                <?= form_error("caste_name") ?>
                            </div>-->
                            <div class="col-md-6">
                                <label>Name of Community / সম্প্ৰদায় নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="community_name"
                                    value="<?= $community_name ?>" maxlength="255" readonly/>
                                <?= form_error("community_name") ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <h3 class="text-center font-weight-bold mt-5" style="font-size:16px">Financial Status of
                                    Parents/
                                    Husband/
                                    Wife / পিতৃ-মাতৃ/স্বামী/স্ত্ৰীৰ
                                    আৰ্থিক স্হি</h3>
                                <table class="table table-bordered" id="financialstatustbl" style="width:150%">
                                    <thead>
                                        <!-- <tr>
                                            <th colspan="9" class="text-center" style="font-size:16px">
                                                Financial Status of Parents/ Husband/ Wife / পিতৃ-মাতৃ/স্বামী/স্ত্ৰীৰ
                                                আৰ্থিক স্হি
                                            </th>
                                        </tr> -->

                                        <tr>
                                            <th>Relation / উপাৰ্জনৰ উৎস</th>
                                            <th>Type of Organization (Govt./Pvt.) Profession /Trade / Business /
                                                Agriculture etc.( সংস্হোৰ ধ্ৰণ (িৰকোৰী/ যচক্তগত) জীচ কো য সোয়/ োচণজয
                                                /মখচত)</th>
                                            <th>Name Of Organisation/Department (বিভাগ/সংস্হাৰ নাম)</th>
                                            <th>Designation/Post Held (পদবী)</th>
                                            <th>Gross Annual Salary/Amount (মুঠ বাৰ্ষিক দৰমহা)</th>
                                            <th>Income From Other Source (অন্য উৎসৰ পৰা)</th>
                                            <th>Total Income (মুঠ)</th>
                                            <th style="width:65px;text-align: center">Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $fRelation = (isset($relation) && is_array($relation)) ? count($relation) : 0;
                                        if ($fRelation > 0) {
                                            for ($i = 0; $i < $fRelation; $i++) {
                                                if ($i == 0) {
                                                    $btn = '<button class="btn btn-info" id="addfstblrow" type="button"><i class="fa fa-plus-circle"></i></button>';
                                                } else {
                                                    $btn = '<button class="btn btn-danger deletetblrow" type="button"><i class="fa fa-trash-o"></i></button>';
                                                } // End of if else 
                                        ?>
                                        <tr>
                                            <td>
                                                <select class="form-control" name="relation[]">
                                                    <option value="">Please Select<?= $relation[$i] ?></option>
                                                    <option value="Mother/মাতৃ"
                                                        <?= ($relation[$i] === "Mother/মাতৃ") ? 'selected' : '' ?>>
                                                        Mother/মাতৃ</option>
                                                    <option value="Father/পিতৃ"
                                                        <?= ($relation[$i] === "Father/পিতৃ") ? 'selected' : '' ?>>
                                                        Father/পিতৃ</option>
                                                    <option value="Husband/স্বামী"
                                                        <?= ($relation[$i] === "Husband/স্বামী") ? 'selected' : '' ?>>
                                                        Husband/স্বামী</option>
                                                    <option value="Wife/পত্নী"
                                                        <?= ($relation[$i] === "Wife/পত্নী") ? 'selected' : '' ?>>
                                                        Wife/পত্নী</option>
                                                </select>
                                                <?= form_error("relation[]") ?>
                                            </td>
                                            <td>
                                                <input name="organization_types[]"
                                                    value="<?= $organization_types[$i] ?>" class="form-control"
                                                    type="text" />
                                                <?= form_error("organization_types[]") ?>
                                            </td>
                                            <td>
                                                <input name="organization_names[]"
                                                    value="<?= $organization_names[$i] ?>" class="form-control"
                                                    type="text" />
                                                <?= form_error("organization_names[]") ?>
                                            </td>
                                            <td>
                                                <input name="fs_designations[]" value="<?= $fs_designations[$i] ?>"
                                                    class="form-control" type="text" />
                                                <?= form_error("fs_designations[]") ?>
                                            </td>
                                            <td>
                                                <input name="annual_salary[]" value="<?= $annual_salary[$i] ?>"
                                                    class="form-control" type="number" />
                                                <?= form_error("annual_salary[]") ?>
                                            </td>
                                            <td>
                                                <input name="other_income[]" value="<?= $other_income[$i] ?>"
                                                    class="form-control" type="number" />
                                                <?= form_error("other_income[]") ?>
                                            </td>
                                            <td>
                                                <input name="total_income[]" value="<?= $total_income[$i] ?>"
                                                    class=" form-control" type="number" />
                                                <?= form_error("total_income[]") ?>
                                            </td>
                                            <td><?= $btn ?></td>
                                        </tr>
                                        <?php }
                                        } else { ?>
                                        <tr>
                                            <td>
                                                <select class="form-control" name="relation[]">
                                                    <option value="">Please Select</option>
                                                    <option value="Mother/মাতৃ"> Mother/মাতৃ</option>
                                                    <option value="Father/পিতৃ"> Father/পিতৃ</option>
                                                    <option value="Husband/স্বামী"> Husband/স্বামী</option>
                                                    <option value="Wife/পত্নী"> Wife/পত্নী</option>
                                                </select>
                                                <?= form_error("relation[]") ?>
                                            </td>
                                            <td>
                                                <input name="organization_types[]" class="form-control" type="text" />
                                                <?= form_error("organization_types[]") ?>
                                            </td>
                                            <td>
                                                <input name="organization_names[]" class="form-control" type="text" />
                                                <?= form_error("organization_names[]") ?>
                                            </td>
                                            <td>
                                                <input name="fs_designations[]" class="form-control" type="text" />
                                                <?= form_error("fs_designations[]") ?>
                                            </td>
                                            <td>
                                                <input name="annual_salary[]" class="form-control" type="number" />
                                                <?= form_error("annual_salary[]") ?>
                                            </td>
                                            <td>
                                                <input name="other_income[]" class="form-control" type="number" />
                                                <?= form_error("other_income[]") ?>
                                            </td>
                                            <td>
                                                <input name="total_income[]" class="form-control" type="number" />
                                                <?= form_error("total_income[]") ?>
                                            </td>
                                            <td style="text-align:center">
                                                <button class="btn btn-info" id="addfstblrow" type="button">
                                                    <i class="fa fa-plus-circle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php } //End of if else  
                                        ?>
                                    </tbody>
                                </table>
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
                        <i class="fa fa-check"></i> Submit
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