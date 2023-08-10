<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://artpskaac.in/"; //For testing
// pre($dbrow);
if ($dbrow) {
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $pre_certificate_no = !empty(set_value("pre_certificate_no"))? set_value("pre_certificate_no"):(isset($dbrow->form_data->pre_certificate_no)? $dbrow->form_data->pre_certificate_no: "");
    $pre_mobile_no = !empty(set_value("pre_mobile_no"))? set_value("pre_mobile_no"):(isset($dbrow->form_data->pre_mobile_no)? $dbrow->form_data->pre_mobile_no: "");
   
   
    $applicant_title = !empty(set_value("applicant_title"))? set_value("applicant_title"):(isset($dbrow->form_data->applicant_title)? $dbrow->form_data->applicant_title: "");//$dbrow->form_data->applicant_title ?? set_value("applicant_title");
    $first_name = !empty(set_value("first_name"))? set_value("first_name"):(isset($dbrow->form_data->first_name)? $dbrow->form_data->first_name: "");
    $last_name = !empty(set_value("last_name"))? set_value("last_name"):(isset($dbrow->form_data->last_name)? $dbrow->form_data->last_name: "");
    $applicant_gender = !empty(set_value("applicant_gender"))? set_value("applicant_gender"):(isset($dbrow->form_data->applicant_gender)? $dbrow->form_data->applicant_gender: "");
    $caste = !empty(set_value("caste"))? set_value("caste"):(isset($dbrow->form_data->caste)? $dbrow->form_data->caste: "");
    $father_title = !empty(set_value("father_title"))? set_value("father_title"):(isset($dbrow->form_data->father_title)? $dbrow->form_data->father_title: "");
    $father_name = !empty(set_value("father_name"))? set_value("father_name"):(isset($dbrow->form_data->father_name)? $dbrow->form_data->father_name: "");
    $aadhar_no = !empty(set_value("aadhar_no"))? set_value("aadhar_no"):(isset($dbrow->form_data->aadhar_no)? $dbrow->form_data->aadhar_no: "");
    $mobile = $this->session->mobile ?? $dbrow->form_data->mobile; 
    $email = !empty(set_value("email"))? set_value("email"):(isset($dbrow->form_data->email)? $dbrow->form_data->email: "");

    
    $district = !empty(set_value("district"))? set_value("district"):(isset($dbrow->form_data->district)? $dbrow->form_data->district: "");
    $police_station = !empty(set_value("police_station"))? set_value("police_station"):(isset($dbrow->form_data->police_station)? $dbrow->form_data->police_station: "");
    $post_office = !empty(set_value("post_office"))? set_value("post_office"):(isset($dbrow->form_data->post_office)? $dbrow->form_data->post_office: "");

    $name_of_firm = !empty(set_value("name_of_firm"))? set_value("name_of_firm"):(isset($dbrow->form_data->name_of_firm)? $dbrow->form_data->name_of_firm: ""); 
    $name_of_proprietor = !empty(set_value("name_of_proprietor"))? set_value("name_of_proprietor"):(isset($dbrow->form_data->name_of_proprietor)? $dbrow->form_data->name_of_proprietor: "");
    $occupation_trade  = !empty(set_value("occupation_trade "))? set_value("occupation_trade "):(isset($dbrow->form_data->occupation_trade )? $dbrow->form_data->occupation_trade : "");
    $community = !empty(set_value("community"))? set_value("community"):(isset($dbrow->form_data->community)? $dbrow->form_data->community: "");
    $class_of_business = !empty(set_value("class_of_business"))? set_value("class_of_business"):(isset($dbrow->form_data->class_of_business)? $dbrow->form_data->class_of_business: "");
    $address = !empty(set_value("address"))? set_value("address"):(isset($dbrow->form_data->address)? $dbrow->form_data->address: "");
    $business_locality = !empty(set_value("business_locality"))? set_value("business_locality"):(isset($dbrow->form_data->business_locality)? $dbrow->form_data->business_locality: "");
    $business_word_no = !empty(set_value("business_word_no"))? set_value("business_word_no"):(isset($dbrow->form_data->business_word_no)? $dbrow->form_data->business_word_no: "");
    $reason_for_consideration = !empty(set_value("reason_for_consideration"))? set_value("reason_for_consideration"):(isset($dbrow->form_data->reason_for_consideration)? $dbrow->form_data->reason_for_consideration: "");
    $rented_owned = !empty(set_value("rented_owned"))? set_value("rented_owned"):(isset($dbrow->form_data->rented_owned)? $dbrow->form_data->rented_owned: "");
    $name_of_owner = !empty(set_value("name_of_owner"))? set_value("name_of_owner"):(isset($dbrow->form_data->name_of_owner)? $dbrow->form_data->name_of_owner: "");
    
    $district_name= !empty(set_value("district_name"))? set_value("district_name"):(isset($dbrow->form_data->district_name)? $dbrow->form_data->district_name: "");
    $applicant_title_name= !empty(set_value("applicant_title_name"))? set_value("applicant_title_name"):(isset($dbrow->form_data->applicant_title_name)? $dbrow->form_data->applicant_title_name: "");
    $gender_name= !empty(set_value("gender_name"))? set_value("gender_name"):(isset($dbrow->form_data->gender_name)? $dbrow->form_data->gender_name: "");
} else {
    $obj_id = NULL;
    $appl_ref_no = NULL;

    $pre_certificate_no = set_value("pre_certificate_no");
    $pre_mobile_no = set_value("pre_mobile_no");
   
   
    $applicant_title = set_value("applicant_title");
    $first_name = set_value("first_name");
    $last_name = set_value("last_name");
    $applicant_gender = set_value("applicant_gender");
    $caste = set_value("caste");
    $father_title = set_value("father_title");
    $father_name = set_value("father_name");
    $aadhar_no = set_value("aadhar_no");
    $mobile = $this->session->mobile ?? ($dbrow->form_data->mobile ?? ''); 
    $email = set_value("email");
    
    
    $district = set_value("district");
    $police_station = set_value("police_station");
    $post_office = set_value("post_office");
    $name_of_firm = set_value("name_of_firm");
    $name_of_proprietor = set_value("name_of_proprietor");
    $occupation_trade  = set_value("occupation_trade");
    $community = set_value("community");
    $class_of_business = set_value("class_of_business");
    $address = set_value("address");
    $business_locality = set_value("business_locality");
    $business_word_no = set_value("business_word_no");
    $reason_for_consideration = set_value("reason_for_consideration");
    $rented_owned = set_value("rented_owned");
    $name_of_owner = set_value("name_of_owner");

    $district_name= set_value("district_name");
    $gender_name= set_value("gender_name");
    $applicant_title_name= set_value("applicant_title_name");
    
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


        $(document).on('change', '#applicant_gender', function() {
            var value = $("#applicant_gender option:selected").text();
            $('#gender_name').val(value);
        })
        $(document).on('change', '#applicant_title', function() {
            var value = $("#applicant_title option:selected").text();
            $('#applicant_title_name').val(value);
        })
        $(document).on('change', '#district', function() {
            var value = $("#district option:selected").text();
            $('#district_name').val(value);
        })


    $(document).ready(function() {
        res = "<?php echo $police_station ?>"
        $.get("<?= $apiServer . "api/ccsdp/police_station" ?>", function(data) {
            $.each(data.records, function(i, val) {
                police_station_name = val.police_station_name;
                // console.log($police_station_name);
                cond = (res == police_station_name) ? "selected" : "";
                $("#police_station").append('<option value="' + val.police_station_name + '"' + cond + '>' + val.police_station_name + '</option>');
            });
        });
    })


    $(document).on("click", "#open_camera", function(){
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
        
        $(document).on("click", "#capture_photo", function(){
            Webcam.snap(function (data_uri) {//alert(data_uri);
                $("#captured_photo").attr("src", data_uri);
                $("#photo_data").val(data_uri);                
            });
        });


    $(document).on("click", ".frmbtn", function() {
        let clickedBtn = $(this).attr("id"); 
        
        $("#submission_mode").val(clickedBtn);
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
});
</script>
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/kaac-brc') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submission_mode" name="submission_mode" value="" type="hidden" />
            <input name="service_name" value="<?= $pageTitle ?>" type="hidden" />
            <input name="service_id" value="<?= $pageTitleId ?>" type="hidden" />


            <!-- <input name="soft_copy_type" value="<?= $soft_copy_type ?>" type="hidden" />
            <input name="soft_copy" value="<?= $soft_copy ?>" type="hidden" /> -->
            <div class="card shadow-sm">
                <div class="card-header"
                    style="background:#589DBF; text-align: center; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <h4><b><?= $pageTitle ?><br><?= $pageTitleAssamese?></b></h4>
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
                        <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>Service charge (PFC/ CSC) / সেৱা মাচুল (পি. এফ. চি./ চি. এছ. চি.) – Rs. 30 / ৩০ টকা</li>
                        </ul>

                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী
                            :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. All the * marked fields are mandatory and need to be filled up..</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব</li>
                            <li>2. The size of documents to be uploaded at the time of Application submission should not
                                exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো
                                অনিবাৰ্য </li>
                        </ul>
                        <strong style="font-size:16px;  margin-top: 10px">Documents to be enclosed with the
                            application:</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>Address proof [Mandatory]</li>
                            <li>Identity proof [Mandatory] </li>
                            <li>Three passport Size Photos [Mandatory] </li>
                            <li>Valid MB/TC House Tax deposit receipt.</li>
                            <li>Valid MBTC Room rent deposit [ Not mandatory] [If room occupied than mandatory]</li>
                            <li>Special reason for Consideration letter [ Not Mandatory] </li>

                        </ul>

                    </fieldset>
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
                                           <input type="hidden" name="applicant_title_name" id="applicant_title_name" value="<?= $applicant_title_name ?>"/> 
                                        <select name="applicant_title" id="applicant_title">
                                            <option value="">Select</option>
                                            <?php $titles = array(
                                                "1" => "Mr",
                                                "2" => "Miss",
                                                "3" => "Mrs" );

                                            foreach ($titles as $value => $display) {
                                                $selected = ($applicant_title == $value) ? 'selected' : '';
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
                                
                                <input type="hidden" name="gender_name" id="gender_name" value="<?= $gender_name?>"/>
                                <select name="applicant_gender" id="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                    $genders = array(
                                        "1" => "Male",
                                        "2" => "Female",
                                        "3" => "Other"
                                    );

                                    foreach ($genders as $value => $display) {
                                        $selected = ($applicant_gender == $value) ? 'selected' : '';
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
                                        $selected = ($caste == $value) ? 'selected' : '';
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
                                                    $selected = ($father_title == $value) ? 'selected' : '';
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
                                <?php if ($usser_type === "user") { ?>
                                <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" readonly
                                    maxlength="10" />
                                <?php } else { ?>
                                <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>"
                                    maxlength="10" />
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
                        <legend class="h5">Applicant Address/ ঠিকনা </legend>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>District/জিলা <span class="text-danger">*</span></label>

                                <input id="district_name" name="district_name" type="hidden" value="<?= $district_name?>"/>
                                <select id="district" name="district" class="form-control">
                                    <option value="">Please Select</option>
                                    <?php
                                $districts = array(
                                    "EastKarbiAnglong" => "East Karbi Anglong",
                                    "WestKarbiAnglong" => "West Karbi Anglong"
                                );

                                foreach ($districts as $value => $display) {
                                    $selected = ($district == $value) ? 'selected' : '';
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
                            <!-- <div class="col-md-4">
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
                                            $selected = ($value == $police_station) ? 'selected' : '';
                                            echo '<option value="' . $value . '" ' . $selected . '>' . $display . '</option>';
                                        }
                                    ?>
                                </select>
                                <?= form_error("police_station") ?>
                            </div> -->

                            <div class="col-md-4">
                                <label>Police Station/ থানা <span class="text-danger">*</span> </label>
                                <input type="hidden" name="police_station_name" id="police_station_name" value="<?= $police_station ?>" />
                                <select id="police_station" name="police_station" class="form-control">
                                    <option value="">Please Select</option>
                                    
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
                                        $selected = ($class_of_business == $value) ? 'selected' : '';
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
                                            $selected = ($rented_owned == $value) ? 'selected' : '';
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
    var nameOfOwnerContainer = document.getElementById("name_of_owner_container");
    var rented_owned = "<?php echo $rented_owned ?>";
    if(rented_owned == "Rent")
    {
        nameOfOwnerContainer.style.display = "block";
        nameOfOwnerInput.value = "<?php echo set_value("name_of_owner") ?>";
    }  
</script>

<script>
    var rentedOwnedSelect = document.getElementById("rented_owned");
    var nameOfOwnerContainer = document.getElementById("name_of_owner_container");
    var nameOfOwnerInput = document.getElementById("name_of_owner");

    if(rentedOwnedSelect == "Rent")
    {
        nameOfOwnerContainer.style.display = "block";
    }
    rentedOwnedSelect.addEventListener("change", function() {
        if (rentedOwnedSelect.value == "Rent") {
            nameOfOwnerContainer.style.display = "block";
        } else {
            nameOfOwnerContainer.style.display = "none";
            nameOfOwnerInput.value = "";
        }
    });
</script>