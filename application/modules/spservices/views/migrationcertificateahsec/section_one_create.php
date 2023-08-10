<?php

$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
// $apiServer = "https://localhost/wptbcapis/"; //For testing
// pre(set_value("pos_district"));
$startYear = date('Y') - 10;
$endYear =  date('Y');
if($dbrow) {
    
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    
    $father_name = !empty(set_value("father_name"))? set_value("father_name"):(isset($dbrow->form_data->father_name)? $dbrow->form_data->father_name: "");//$dbrow->form_data->father_name ;
    $mother_name =  !empty(set_value("mother_name"))? set_value("mother_name"):(isset($dbrow->form_data->mother_name)? $dbrow->form_data->mother_name: "");// $dbrow->form_data->mother_name;
    $applicant_name =  !empty(set_value("applicant_name"))? set_value("applicant_name"):(isset($dbrow->form_data->applicant_name)? $dbrow->form_data->applicant_name: "");//$dbrow->form_data->applicant_name;
    $applicant_gender =  !empty(set_value("applicant_gender"))? set_value("applicant_gender"):(isset($dbrow->form_data->applicant_gender)? $dbrow->form_data->applicant_gender: "");//$dbrow->form_data->applicant_gender;
    
    $mobile =  $this->session->mobile ?? '';
    $email =  !empty(set_value("email"))? set_value("email"):(isset($dbrow->form_data->email)? $dbrow->form_data->email: "");//$dbrow->form_data->email ?? '';
    $comp_permanent_address =  !empty(set_value("comp_permanent_address"))? set_value("comp_permanent_address"):(isset($dbrow->form_data->comp_permanent_address)? $dbrow->form_data->comp_permanent_address: "");//$dbrow->form_data->comp_permanent_address ?? '';
    $pa_state =  !empty(set_value("pa_state"))? set_value("pa_state"):(isset($dbrow->form_data->pa_state)? $dbrow->form_data->pa_state: "");// $dbrow->form_data->pa_state ?? '';
    $pa_district =   !empty(set_value("pa_district"))? set_value("pa_district"):(isset($dbrow->form_data->pa_district)? $dbrow->form_data->pa_district: "");
    
    
    $pa_pincode =  !empty(set_value("pa_pincode"))? set_value("pa_pincode"):(isset($dbrow->form_data->pa_pincode)? $dbrow->form_data->pa_pincode: "");
    
    $comp_postal_address = !empty(set_value("comp_postal_address"))? set_value("comp_postal_address"):(isset($dbrow->form_data->comp_postal_address)? $dbrow->form_data->comp_postal_address: "");// $dbrow->form_data->comp_postal_address?? '';
    $pos_state =  !empty(set_value("pos_state"))? set_value("pos_state"):(isset($dbrow->form_data->pos_state)? $dbrow->form_data->pos_state: "");// $dbrow->form_data->pos_state?? '';
    $pos_district =  !empty(set_value("pos_district"))? set_value("pos_district"):(isset($dbrow->form_data->pos_district)? $dbrow->form_data->pos_district: "");// $dbrow->form_data->pos_district?? '';
    $pos_pincode = !empty(set_value("pos_pincode"))? set_value("pos_pincode"):(isset($dbrow->form_data->pos_pincode)? $dbrow->form_data->pos_pincode: "");// $dbrow->form_data->pos_pincode?? '';
    
    
    $ahsec_reg_session =  !empty(set_value("ahsec_reg_session"))? set_value("ahsec_reg_session"):(isset($dbrow->form_data->ahsec_reg_session)? $dbrow->form_data->ahsec_reg_session: "");//$dbrow->form_data->ahsec_reg_session?? '';
    $ahsec_reg_no =  !empty(set_value("ahsec_reg_no"))? set_value("ahsec_reg_no"):(isset($dbrow->form_data->ahsec_reg_no)? $dbrow->form_data->ahsec_reg_no: "");//$dbrow->form_data->ahsec_reg_no?? '';
    $ahsec_yearofpassing = !empty(set_value("ahsec_yearofpassing"))? set_value("ahsec_yearofpassing"):(isset($dbrow->form_data->ahsec_yearofpassing)? $dbrow->form_data->ahsec_yearofpassing: "");// $dbrow->form_data->ahsec_yearofpassing?? '';
    
    $ahsec_admit_roll =  !empty(set_value("ahsec_admit_roll"))? set_value("ahsec_admit_roll"):(isset($dbrow->form_data->ahsec_admit_roll)? $dbrow->form_data->ahsec_admit_roll: "");//$dbrow->form_data->ahsec_admit_roll?? '';
    $ahsec_admit_no =  !empty(set_value("ahsec_admit_no"))? set_value("ahsec_admit_no"):(isset($dbrow->form_data->ahsec_admit_no)? $dbrow->form_data->ahsec_admit_no: "");//$dbrow->form_data->ahsec_admit_no?? '';
    $ahsec_inst_name =  !empty(set_value("ahsec_inst_name"))? set_value("ahsec_inst_name"):(isset($dbrow->form_data->ahsec_inst_name)? $dbrow->form_data->ahsec_inst_name: "");//$dbrow->form_data->ahsec_inst_name?? '';
    
    // $ahsec_board_seeking = $dbrow->form_data->ahsec_board_seeking;
    // $ahsec_state_seeking = $dbrow->form_data->ahsec_state_seeking;
    // $ahsec_course_seeking = $dbrow->form_data->ahsec_course_seeking;
    // $ahsec_country_seeking = $dbrow->form_data->ahsec_country_seeking;
    // $ahsec_reason_seeking = $dbrow->form_data->ahsec_reason_seeking;


    
    } else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL;//set_value("rtps_trans_id");
    
    $applicant_name = set_value("applicant_name");
    $father_name = set_value("father_name");
    $mother_name = set_value("mother_name");
    $mobile = $this->session->mobile;//set_value("mobile_number");
    $email = set_value("email");
    $applicant_gender = set_value("applicant_gender");
    
    $comp_permanent_address = set_value("comp_permanent_address");
    $pa_state = set_value("pa_state");
    $pa_district = set_value("pa_district");
    $pa_pincode = set_value("pa_pincode");
    
    $comp_postal_address = set_value("comp_postal_address");
    $pos_state = set_value("pos_state");
    $pos_district = set_value("pos_district");
    $pos_pincode = set_value("pos_pincode");
    $reg_session = set_value("reg_session");
    

    
    
    $ahsec_reg_session = set_value("ahsec_reg_session");
    $ahsec_reg_no = set_value("ahsec_reg_no");
    $ahsec_yearofpassing = set_value("ahsec_yearofpassing");
    $ahsec_admit_roll = set_value("ahsec_admit_roll");
    $ahsec_admit_no = set_value("ahsec_admit_no");
    $ahsec_inst_name = set_value("ahsec_inst_name");
    

    // $ahsec_board_seeking = set_value("ahsec_board_seeking");
    // $ahsec_state_seeking = set_value("ahsec_state_seeking");
    // $ahsec_course_seeking = set_value("ahsec_course_seeking");
    // $ahsec_country_seeking = set_value("ahsec_country_seeking");
    // $ahsec_reason_seeking = set_value("ahsec_reason_seeking");

    
}//End of if else
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


    //  board_name,ahsec_reg_session,ahsec_reg_no, ahsec_yearofpassing, ahsec_admit_roll,ahsec_admit_no  
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


    $('.number_input').keypress(function(e) {
        var charCode = (e.which) ? e.which : event.keyCode
        if (String.fromCharCode(charCode).match(/[^0-9]/g))
            return false;
    });

});
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/migrationcertificateahsec') ?>"
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
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php } if ($this->session->flashdata('error') != null) { ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php } if ($this->session->flashdata('success') != null) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php } ?>

                    <fieldset class="border border-success">
                        <!-- <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px; ">Stipulated time limit for delivery/ প্ৰাপ্তিৰ‌ নিৰ্দ্দিষ্ট সময়সীমা</strong>
                        
                        <ol style="  margin-left: 24px; margin-top: 20px">
                            <li>7 (Seven) working days for Indian Medical Graduates.</li>
                            <li>ভাৰতীয় চিকিৎসা স্নাতকসকলৰ বাবে ৭ (সাত) কৰ্মদিন</li>
                            <li>45 (Forty-five) working days for Foreign Medical Graduates.</li>
                            <li>বিদেশী চিকিৎসা স্নাতকসকলৰ বাবে ৪৫ (পঞ্চল্লিশ) কৰ্মদিন</li>
                        </ol> -->


                        <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>User charge / ব্যৱহাৰকাৰী মাচুল – Rs. 600 / ৬০০ টকা.</li>
                            <li>Service charge (PFC/ CSC) / সেৱা মাচুল (পি. এফ. চি./ চি. এছ. চি.) – Rs. 10 / ১০ টকা</li>                                                       
                        </ul>

                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী
                            :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. All the * marked fields are mandatory and need to be filled up..</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব</li>
                            <li>2. The size of documents to be uploaded at the time of Application submission should not  exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো
                                অনিবাৰ্য </li>
                        </ul>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6"><br />
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name"
                                    required value="<?=$applicant_name?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6"><br />
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control" >
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($applicant_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($applicant_gender === "Female")?'selected':''?>>Female</option>
                                    <option value="Transgender" <?=($applicant_gender === "Transgender")?'selected':''?>>Transgender</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>

                            <div class="col-md-6"><br />
                                <label>Father&apos;s Name/ পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" required class="form-control" name="father_name" id="father_name"
                                    value="<?=$father_name?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6"><br />
                                <label>Mother&apos;s Name/ মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" required class="form-control" name="mother_name" id="mother_name"
                                    value="<?=$mother_name?>" maxlength="255" />
                                <?= form_error("mother_name") ?>
                            </div>
                            <div class="col-md-6"><br />
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <?php if($usser_type === "user"){ ?>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" readonly
                                    maxlength="10" />
                                <?php }else{ ?>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>"
                                    maxlength="10" />
                                <?php }?>

                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-6"><br />
                                <label>E-Mail / ই-মেইল <span class="text-danger">*</span> </label>
                                <input type="email" required class="form-control" name="email" value="<?=$email?>"
                                    maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
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
                                    <label>State / ৰাজ্য<span class="text-danger">*</span></label>
                                    <select class="form-control" name="pa_state" id="pa_state">
                                        <option value="">Please Select</option>
                                        <?php foreach($states as $state) { ?>
                                        <option <?=($pa_state == $state->state_name_english) ?'selected':''?>
                                            value="<?php echo $state->state_name_english ?>">
                                            <?php echo $state->state_name_english ?></option>
                                        <?php }?>
                                    </select>
                                    <?= form_error("pa_state") ?>
                                </div>
                                <div class="form-group">
                                    <!-- <label>District <span class="text-danger">*</span></label>
                                    <select name="pa_district" id="pa_district" class="form-control pa_dists">
                                        <option value="">Select Any One</option>
                                    </select> -->
                                    <label>District / জিলা <span class="text-danger">*</span></label>
                                    <select class="form-control" name="pa_district" id="pa_district">
                                        <option value="">Select Any One</option>
                                        <?php foreach($districts as $district) { ?>
                                        <option <?=($pa_district == $district->district_name) ?'selected':''?>
                                            value="<?php echo $district->district_name ?>">
                                            <?php echo $district->district_name ?></option>
                                        <?php }?>
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
                                <input type="checkbox" name="checker" id="checker" />&nbsp;&nbsp;&nbsp;<b>Same as Permanent Address</b><br /><br />
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
                                    <label>State / ৰাজ্য <span class="text-danger">*</span></label>
                                    <select class="form-control" name="pos_state" id="pos_state">
                                        <option value="">Please Select</option>
                                        <!-- <option value="Assam" autocomplete="off" selected="selected">Assam</option> -->
                                        <?php foreach($states as $state) { ?>
                                        <option <?=($pos_state == $state->state_name_english) ?'selected':''?>
                                            value="<?php echo $state->state_name_english ?>">
                                            <?php echo $state->state_name_english ?></option>
                                        <?php }?>
                                    </select>
                                    <?= form_error("pos_state") ?>
                                </div>
                                <div class="form-group">
                                    <!-- <label>District <span class="text-danger">*</span></label>
                                    <select class="form-control pos_dists" name="pos_district" id="pos_district">
                                        <option value="">Select Any One</option>
                                    </select> -->
                                    <label>District / জিলা <span class="text-danger">*</span></label>
                                    <select class="form-control" name="pos_district" id="pos_district">
                                        <option value="">Select Any One</option>
                                        <?php foreach($districts as $district) { ?>
                                        <option <?=($pos_district == $district->district_name) ?'selected':''?>
                                            value="<?php echo $district->district_name ?>">
                                            <?php echo $district->district_name ?></option>
                                        <?php }?>
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
                                <select name="ahsec_reg_session" readonly class="form-control">
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
                            <input type="text" readonly class="form-control" name="ahsec_reg_no" id="ahsec_reg_no"
                                value="<?= $ahsec_reg_no ?>" maxlength="255" />
                            <?= form_error("ahsec_reg_no") ?>
                        </div>
                        <div class="col-md-6">
                                <label>Year of Appearing H.S. Final Year Examination/ হাজিৰ হোৱাৰ বছৰ এইচ. চূড়ান্ত বৰ্ষৰ পৰীক্ষা</label>
                                <select name="ahsec_yearofpassing" class="form-control">
                                    <option value="">Select Year</option>
                                <?php foreach($years as $year) { ?>   
                                <option value="<?php echo $year ?>" <?= ($ahsec_yearofpassing == $year) ? 'selected' : '' ?>><?php echo $year ?></option>

                                <?php } ?>
                                </select>
                                <?= form_error("ahsec_yearofpassing") ?>
                            </div>
                        <div class="col-md-6"><br />
                            <label>Name of Institution / প্ৰতিষ্ঠানৰ নাম <span class="text-danger">*</span> </label>
                            <input type="text"  class="form-control" name="ahsec_inst_name" id="ahsec_inst_name"
                                value="<?= $ahsec_inst_name ?>" maxlength="255" />
                            <?= form_error("ahsec_inst_name") ?>
                        </div>
                        <div class="col-md-6"><br />
                            <label>Valid H.S. Final Year Examination Roll/ বৈধ এইচ.এছ. চূড়ান্ত বৰ্ষৰ  ৰোল </label>
                            <input type="text" class="form-control" name="ahsec_admit_roll" id="ahsec_admit_roll"
                                value="<?= $ahsec_admit_roll ?>" maxlength="255" />
                            <?= form_error("ahsec_admit_roll") ?>
                        </div>
                        <div class="col-md-6"><br />
                            <label>Valid H.S. Final Year Examination Number / বৈধ এইচ.এছ. চূড়ান্ত বৰ্ষৰ  নম্বৰ  </label>
                            <input type="text" class="form-control" name="ahsec_admit_no" id="ahsec_admit_no"
                                value="<?= $ahsec_admit_no ?>" maxlength="255" />
                            <?= form_error("ahsec_admit_no") ?>
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
    <!--End of .container-->
</main>