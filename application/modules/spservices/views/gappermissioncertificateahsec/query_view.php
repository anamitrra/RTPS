<?php
// pre("Hello0");
$currentYear = date('Y');
$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production

// $apiServer = "https://localhost/wptbcapis/"; //For testing
if($dbrow) {

    $obj_id = $dbrow->{'_id'}->{'$id'};  
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
 
    $father_name = $dbrow->form_data->father_name;
    $mother_name = $dbrow->form_data->mother_name;
    $applicant_name = $dbrow->form_data->applicant_name;
    $mobile = $dbrow->form_data->mobile;
    $email = $dbrow->form_data->email;
    $comp_permanent_address = $dbrow->form_data->comp_permanent_address;
    $pa_state = $dbrow->form_data->pa_state;
    $pa_district = $dbrow->form_data->pa_district;
    $pa_pincode = $dbrow->form_data->pa_pincode;
    
    $comp_postal_address = $dbrow->form_data->comp_postal_address;
    $pos_state = $dbrow->form_data->pos_state;
    $pos_district = $dbrow->form_data->pos_district;
    $pos_pincode = $dbrow->form_data->pos_pincode;
    
    $ahsec_reg_session = $dbrow->form_data->ahsec_reg_session;
    $ahsec_reg_no = $dbrow->form_data->ahsec_reg_no;
    $ahsec_yearofpassing = $dbrow->form_data->ahsec_yearofpassing;
    $ahsec_admit_roll = $dbrow->form_data->ahsec_admit_roll;
    $ahsec_admit_no = $dbrow->form_data->ahsec_admit_no;
    $ahsec_inst_name = $dbrow->form_data->ahsec_inst_name;
    
    $board_seaking_adm = $dbrow->form_data->board_seaking_adm ?? '';
    $course_seaking_adm = $dbrow->form_data->course_seaking_adm ?? '';
    $state_seaking_adm = $dbrow->form_data->state_seaking_adm ?? ''; 
    $reason_seaking_adm = $dbrow->form_data->reason_seaking_adm ?? '';   
    $ahsec_country_seeking = $dbrow->form_data->ahsec_country_seeking;
    $postal = $dbrow->form_data->postal ?? '';   
    
    $photo_of_the_candidate = $dbrow->form_data->photo_of_the_candidate ?? '';
    $candidate_sign = $dbrow->form_data->candidate_sign ?? '';

    $hs_marksheet = $dbrow->form_data->hs_marksheet ?? '';
    $hs_reg_card = $dbrow->form_data->hs_reg_card ?? '';
    $hs_marksheet_type = $dbrow->form_data->hs_marksheet_type ?? '';
    $hs_reg_card_type = $dbrow->form_data->hs_reg_card_type ?? '';   
    $photo_of_the_candidate_type = $dbrow->form_data->photo_of_the_candidate_type ?? '';
    $candidate_sign_type = $dbrow->form_data->candidate_sign_type ?? '';
    
    $status = $dbrow->service_data->appl_status ?? '';


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
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script type="text/javascript">
$(document).ready(function() {



    var pa_state = "<?php echo $pa_state ?>";
    var pa_district = "<?php echo $pa_district ?>";
    if (pa_district != "") {
        getAjaxresults('pa_district', pa_state, pa_district);
    }

    var pos_state = "<?php echo $pos_state ?>";
    var pos_district = "<?php echo $pos_district ?>";
    if (pos_district != "") {
        getAjaxresults('pos_district', pos_state, pos_district);
    }

    $(document).on("change", "#pa_state", function() {
        let selectedVal = $(this).val();
        if (selectedVal.length) {
            var myObject = new Object();
            myObject.slc = selectedVal;
            getAjaxresults('pa_district', selectedVal, 0);
        }
    });

    $(document).on("change", "#pos_state", function() {
        let selectedVal = $(this).val();

        if (selectedVal.length) {

            getAjaxresults('pos_district', selectedVal, 0);
        }
    });

    function getAjaxresults(field_name, field_value, selected_value) {
        $.ajax({
            type: "POST",
            url: "<?=base_url('spservices/migrationcertificateahsec/registration/get_districts')?>",
            data: {
                "field_name": field_name,
                "field_value": field_value,
                "selected_value": selected_value
            },
            beforeSend: function() {
                $("#" + field_name).html("Loading");
            },
            success: function(res) {
                $("#" + field_name).html(res);
            }
        });
    }

    $('#checker').change(function() {
        if ($(this).is(':checked')) {
            // alert($('#pa_district').val());
            $('#comp_postal_address').val($('#comp_permanent_address').val());
            $('#pos_state').val($('#pa_state').val());
            $('#pos_district').val($('#pa_district').val());
            $('#pos_pincode').val($('#pa_pincode').val());
            getAjaxresults('pos_district', $('#pa_state').val(), $('#pa_district').val());

        } else {
            $('#comp_postal_address').val("");
            $('#pos_state').val("");
            $('#pos_district').val("");
            $('#pos_pincode').val("");
        }
    });



    var photoOfTheCandidate = parseInt(<?=strlen($photo_of_the_candidate)?1:0?>);
    $("#photo_of_the_candidate").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        required: photoOfTheCandidate ? false : true,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg", "jpeg", "png"]
    });

    var candidateSign = parseInt(<?=strlen($candidate_sign)?1:0?>);
    $("#candidate_sign").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        required: candidateSign ? false : true,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg", "jpeg", "png"]
    });


    // var hs_marksheet = parseInt(<?=strlen($hs_marksheet)?1:0?>);
    // $("#hs_marksheet").fileinput({
    //     dropZoneEnabled: false,
    //     showUpload: false,
    //     showRemove: false,
    //     required: false,
    //     maxFileSize: 1024,
    //     allowedFileExtensions: ["jpg", "jpeg", "png","pdf"]
    // }); 
    var hs_reg_card = parseInt(<?=strlen($hs_reg_card)?1:0?>);
    $("#hs_reg_card").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required: false,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg", "jpeg", "png", "pdf"]
    });


});
</script>
<script type="text/javascript">
$(document).ready(function() {

    $(document).on("click", ".frmbtn", function() {
        let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
        $("#submit_mode").val(clickedBtn);
        if (clickedBtn === 'DRAFT') {
            var msg =
                "You want to save in Draft mode that will allows you to edit and can submit later";
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

    $(".dp").datepicker({
        format: 'dd/mm/yyyy',
        endDate: '+0d',
        autoclose: true
    });

});
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST"
            action="<?= base_url('spservices/migrationcertificateahsec/registration/querysubmit') ?>"
            enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <?php echo $pageTitle ?><br>
                    ( <?php echo $PageTiteAssamese ?> )
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

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name"
                                    required value="<?=$applicant_name?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>

                            <div class="col-md-6">
                                <label>Father&apos;s Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" required class="form-control" name="father_name" id="father_name"
                                    value="<?=$father_name?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mother&apos;s Name/ মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" required class="form-control" name="mother_name" id="mother_name"
                                    value="<?=$mother_name?>" maxlength="255" />
                                <?= form_error("mother_name") ?>
                            </div>

                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" readonly name="mobile" value="<?=$mobile?>"
                                    maxlength="10" />

                                <?= form_error("mobile") ?>
                            </div>

                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল <span class="text-danger">*</span> </label>
                                <input type="email" required class="form-control" name="email" value="<?=$email?>"
                                    maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address / স্থায়ী ঠিকনা </legend>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">

                                    <label>Complete Permanent Address/ সম্পূৰ্ণ স্থায়ী ঠিকনা <span
                                            class="text-danger">*</span></label>

                                    <textarea id="comp_permanent_address" class="form-control"
                                        name="comp_permanent_address" rows="4" required
                                        cols="50"><?=$comp_permanent_address?></textarea>

                                    <?= form_error("comp_permanent_address") ?>
                                </div>

                                <div class="form-group">
                                    <label>Pincode/ পিনকোড<span class="text-danger">*</span> </label>
                                    <input type="number" required class="form-control" name="pa_pincode" id="pa_pincode"
                                        value="<?=$pa_pincode?>" min="100000" maxlength="6" />
                                    <?= form_error("pa_pincode") ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>State <span class="text-danger">*</span></label>
                                    <select class="form-control" name="pa_state" id="pa_state">
                                        <option value="">Please Select</option>
                                        <!-- <option value="Assam" autocomplete="off" selected="selected">Assam</option> -->
                                        <?php foreach($states as $state) { ?>
                                        <option <?=($pa_state == $state->state_name_english) ?'selected':''?>
                                            value="<?php echo $state->state_name_english ?>">
                                            <?php echo $state->state_name_english ?></option>
                                        <?php }?>
                                    </select>
                                    <?= form_error("state") ?>
                                </div>
                                <div class="form-group">
                                    <label>District <span class="text-danger">*</span></label>
                                    <select name="pa_district" id="pa_district" class="form-control pa_dists">
                                        <option value="">Select Any One</option>

                                    </select>
                                    <?= form_error("pa_district") ?>
                                </div>
                                </diiv>
                            </div>


                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Postal Address / ডাক ঠিকনা </legend>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="checkbox" name="checker" id="checker" />&nbsp;&nbsp;&nbsp;<b>Same as
                                    Permanent Address</b><br /><br />
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <label>Complete Postal Address/ সম্পূৰ্ণ ডাক ঠিকনা <span
                                            class="text-danger">*</span></label>

                                    <textarea id="comp_postal_address" class="form-control" name="comp_postal_address"
                                        rows="4" cols="50"><?=$comp_postal_address?></textarea>

                                    <?= form_error("comp_postal_address") ?>
                                </div>

                                <div class="form-group">
                                    <label>Pincode/ পিনকোড<span class="text-danger">*</span> </label>
                                    <input type="number" class="form-control" name="pos_pincode" id="pos_pincode"
                                        value="<?=$pos_pincode?>" min="100000" maxlength="6" />
                                    <?= form_error("pos_pincode") ?>
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    <label>State <span class="text-danger">*</span></label>
                                    <select class="form-control" name="pos_state" id="pos_state">
                                        <option value="">Please Select</option>
                                        <!-- <option value="Assam" autocomplete="off" selected="selected">Assam</option> -->
                                        <?php foreach($states as $state) { ?>
                                        <option <?=($pa_state == $state->state_name_english) ?'selected':''?>
                                            value="<?php echo $state->state_name_english ?>">
                                            <?php echo $state->state_name_english ?></option>
                                        <?php }?>
                                    </select>
                                    <?= form_error("pos_state") ?>
                                </div>
                                <div class="form-group">
                                    <label>District <span class="text-danger">*</span></label>
                                    <select class="form-control pos_dists" name="pos_district" id="pos_district">
                                        <option value="">Select Any One</option>
                                    </select>
                                    <?= form_error("pos_district") ?>
                                </div>
                                </diiv>
                            </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Academic Details / শৈক্ষিক বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>AHSEC Registration Session/ এ. এইচ. এছ. ই .চি. পঞ্জীয়ন বৰ্ষ <span
                                        class="text-danger">*</span> </label>
                                <select name="ahsec_reg_session" class="form-control">
                                    <option value="">Select AHSEC Registration Session</option>
                                    <?php foreach($sessions as $session) { ?>
                                    <option value="<?php echo $session ?>"
                                        <?= ($ahsec_reg_session === $session) ? 'selected' : '' ?>>
                                        <?php echo $session ?></option>

                                    <?php } ?>
                                </select>
                                <?= form_error("ahsec_reg_session") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Valid AHSEC Registrtion Number / বৈধ এ. এইচ. এছ. ই. চি. পঞ্জীয়ন নম্বৰ <span
                                        class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ahsec_reg_no" id="ahsec_reg_no"
                                    value="<?= $ahsec_reg_no ?>" maxlength="255" />
                                <?= form_error("ahsec_reg_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Year of Appearing in HS Final Examination / এইচ.এছ.ত উপস্থিত হোৱাৰ বছৰটো।
                                    চূড়ান্ত পৰীক্ষা<span class="text-danger">*</span> </label>
                                <select name="ahsec_yearofpassing" id="ahsec_yearofpassing" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php foreach($sessions as $session) { ?>
                                    <option value="<?php echo $session ?>"
                                        <?= ($ahsec_yearofpassing === $session) ? 'selected' : '' ?>>
                                        <?php echo $session ?></option>
                                        <?php } ?>
                                </select>
                                <?= form_error("ahsec_yearofpassing") ?>
                            </div>

                            <div class="col-md-6">
                                <label>Valid H.S. 2nd Year Admit Roll/ বৈধ এইচ.এছ. ২য় বৰ্ষৰ এডমিট ৰোল <span
                                        class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ahsec_admit_roll" id="ahsec_admit_roll"
                                    value="<?= $ahsec_admit_roll ?>" maxlength="255" />
                                <?= form_error("ahsec_admit_roll") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Valid H.S. 2nd Year Admit Number / বৈধ এইচ.এছ. ২য় বৰ্ষৰ এডমিট নম্বৰ <span
                                        class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ahsec_admit_no" id="ahsec_admit_no"
                                    value="<?= $ahsec_admit_no ?>" maxlength="255" />
                                <?= form_error("ahsec_admit_no") ?>
                            </div>
                            <div class="col-md-6"><br />
                                <label>Name of Institution / প্ৰতিষ্ঠানৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ahsec_inst_name" id="ahsec_inst_name"
                                    value="<?= $ahsec_inst_name ?>" maxlength="255" />
                                <?= form_error("ahsec_inst_name") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Details of Course Opting to Study Next / পাঠ্যক্ৰমৰ বিৱৰণ পৰৱৰ্তী অধ্যয়ন
                            কৰিবলৈ বাছি লোৱা </legend>
                        <div class="row ">
                            <div class="form-group  col-md-6">
                                <label>University/Board where seeking admission / বিশ্ববিদ্যালয়/বৰ্ড য'ত নামভৰ্তি
                                    বিচাৰিছে<span class="text-danger">*</span></label>

                                <input type="text" class="form-control" name="board_seaking_adm" id="board_seaking_adm"
                                    value="<?=$board_seaking_adm?>" />

                                <?= form_error("board_seaking_adm") ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Course name where seeking admission/পাঠ্যক্ৰমৰ নাম য'ত নামভৰ্তি বিচাৰিছে <span
                                        class="text-danger">*</span></label>

                                <input type="text" class="form-control" name="course_seaking_adm"
                                    id="course_seaking_adm" value="<?=$course_seaking_adm?>" />
                                <?= form_error("course_seaking_adm") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Name of the Country if seeking admission abroad / বিদেশত নামভৰ্তি বিচাৰিলে দেশৰ
                                    নাম</label>
                                <!-- <input type="text" class="form-control" name="ahsec_country_seeking" id="ahsec_country_seeking"
                                    value="<?= $ahsec_country_seeking ?>" maxlength="255" /> -->

                                <select class="form-control" name="ahsec_country_seeking" required
                                    id="ahsec_country_seeking">
                                    <option value="">Select Any One</option>
                                    <?php foreach($countries as $country) { ?>
                                    <option <?=($ahsec_country_seeking == $country->country_name) ?'selected':''?>
                                        value="<?php echo $country->country_name ?>">
                                        <?php echo $country->country_name ?></option>
                                    <?php }?>
                                </select>
                                <?= form_error("ahsec_country_seeking") ?>
                            </div>

                            <div class="form-group col-md-6">
                                <label>State where seeking admission/ ক’ত নামভৰ্তি বিচাৰিছে সেই কথা উল্লেখ কৰক<span
                                        class="text-danger">*</span> </label>
                                <select class="form-control" name="state_seaking_adm" required id="state_seaking_adm">
                                    <option value="">Select Any One</option>
                                    <?php foreach($states as $state) { ?>
                                    <option <?=($state_seaking_adm == $state->state_name_english) ?'selected':''?>
                                        value="<?php echo $state->state_name_english ?>">
                                        <?php echo $state->state_name_english ?></option>
                                    <?php }?>
                                </select>

                                <!-- <input type="text" class="form-control" name="state_seaking_adm" id="state_seaking_adm"
                                    value="<?=$state_seaking_adm?>" maxlength="20" /> -->
                                <?= form_error("state_seaking_adm") ?>
                            </div>

                            <div class="form-group col-md-6">

                                <label>Describe Reason for Seeking Migration/ প্ৰব্ৰজন বিচৰাৰ কাৰণ বৰ্ণনা কৰা <span
                                        class="text-danger">*</span></label>

                                <textarea id="reason_seaking_adm" class="form-control" name="reason_seaking_adm"
                                    rows="4" cols="50"><?=$reason_seaking_adm?></textarea>

                                <?= form_error("reason_seaking_adm") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Delivery Preference / ডেলিভাৰীৰ পছন্দ </legend>
                        <div class="row ">
                            <div class="form-group  col-md-12">
                                <label>How would you want to receive your migration certificate ? / আপুনি আপোনাৰ
                                    প্ৰব্ৰজন প্ৰমাণপত্ৰ কেনেকৈ লাভ কৰিব বিচাৰিব<span
                                        class="text-danger">*</span></label>

                                <input id="postal_from_counter" name="postal" type="radio" class=""
                                    <?php if($postal=='FROM AHSEC COUNTER') echo "checked='checked'"; ?>
                                    value="FROM AHSEC COUNTER" onclick="displayParagraph()" /> FROM AHSEC COUNTER
                                <br />
                                <p style="color: red; font-weight: bold; display:none" id="from_counter">Applicant must
                                    submit the Registration Card at the time of receiving the Migration Certificate.</p>
                                <br />

                                <input id="postal_in_my_address" name="postal" type="radio" class=""
                                    <?php if($postal=='IN MY POSTAL ADDRESS') echo "checked='checked'"; ?>
                                    value="IN MY POSTAL ADDRESS" onclick="displayParagraph()" /> IN MY POSTAL ADDRESS
                                <!-- <label for="postal" class="">IN MY POSTAL ADDRESS</label> -->
                                <br />
                                <p style="color: red; font-weight: bold; display:none" id="by_post">Applicant must send
                                    the Registration Card via post to AHSEC for receiving the Migration Certificate.</p>
                                <br />
                                <?= form_error("postal") ?>


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
                                            <td>Photo of the Candidate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="photo_of_the_candidate_type" class="form-control">
                                                    <option value="Photo of the Candidate"
                                                        <?=($photo_of_the_candidate_type === 'Photo of the Candidate')?'selected':''?>>
                                                        Photo of the Candidate</option>
                                                </select>
                                                <?= form_error("photo_of_the_candidate_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="photo_of_the_candidate" name="photo_of_the_candidate"
                                                        type="file" />
                                                </div>
                                                <?php if(strlen($photo_of_the_candidate)){ ?>
                                                <a href="<?=base_url($photo_of_the_candidate)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>


                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Signature of the Candidate<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="candidate_sign_type" class="form-control">
                                                    <option value="Signature of the Candidate"
                                                        <?=($candidate_sign_type === 'Signature of the Candidate')?'selected':''?>>
                                                        Signature of the Candidate</option>
                                                </select>
                                                <?= form_error("candidate_sign_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="candidate_sign" name="candidate_sign" type="file" />
                                                </div>
                                                <?php if(strlen($candidate_sign)){ ?>
                                                <a href="<?=base_url($candidate_sign)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>H.S. Registration Card<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="hs_reg_card_type" class="form-control">

                                                    <option value="H.S. Registration Card">H.S. Registration Card
                                                    </option>
                                                </select>
                                                <?= form_error("hs_reg_card_type") ?>
                                            </td>
                                            <td>
                                                <div class="file-loading">
                                                    <input id="hs_reg_card" name="hs_reg_card" type="file" />
                                                </div>
                                                <?php if(strlen($hs_reg_card)){ ?>
                                                <a href="<?=base_url($hs_reg_card)?>"
                                                    class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                                <?php }//End of if ?>
                                                <?= form_error("hs_reg_card") ?>

                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset>

                </div>
                <!--End of .card-body -->

                <div class="card-footer text-center">
                    <!-- <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button> -->
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-angle-double-right"></i> Submit
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

    <script>
    hideshow();

    function hideshow() {

        var fromCounterRadio = document.getElementById("postal_from_counter");
        var inMyPostalAddressRadio = document.getElementById("postal_in_my_address");
        var fromCounterParagraph = document.getElementById("from_counter");
        var byPostParagraph = document.getElementById("by_post");

        if (fromCounterRadio.checked) {
            fromCounterParagraph.style.display = "block";
            byPostParagraph.style.display = "none";
        } else if (inMyPostalAddressRadio.checked) {
            fromCounterParagraph.style.display = "none";
            byPostParagraph.style.display = "block";
        } else {
            fromCounterParagraph.style.display = "none";
            byPostParagraph.style.display = "none";
        }

    }

    function displayParagraph() {
        hideshow();
    }
    </script>
    <!--End of .container-->
</main>