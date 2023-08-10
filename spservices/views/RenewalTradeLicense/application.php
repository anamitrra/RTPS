<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://localhost/wptbcapis/"; //For testing
// pre($apiServer);


// print_r($ulb_list);
// print_r($ulb_list[0]);
// print_r($ulb_list[0]['ulb_name']);


//$startYear = date('Y') - 10;
//$endYear =  date('Y');
if ($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $appref_no = $dbrow->form_data->appref_no;
    $ulb = $dbrow->form_data->ulb;
    $ulb_id = $dbrow->form_data->ulb_id;
    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $ubin = $dbrow->form_data->ubin;
    $father_name =  $dbrow->form_data->father_name;
    $mobile = $dbrow->form_data->mobile;
    $email = isset($dbrow->form_data->email) ? $dbrow->form_data->email : "";
    $area = $dbrow->form_data->area;
    $mouza = $dbrow->form_data->mouza;
    $post_office = $dbrow->form_data->post_office;
    $country = $dbrow->form_data->country;
    $state = $dbrow->form_data->state;
    $district = $dbrow->form_data->district;
    $pin_code = $dbrow->form_data->pin_code;
    $father_name2 = $dbrow->form_data->father_name2;
    $police_station = $dbrow->form_data->police_station;
    $ward_no = $dbrow->form_data->ward_no;
    $applicant_age = $dbrow->form_data->applicant_age;
    $business_est = $dbrow->form_data->business_est;
    $business_name = $dbrow->form_data->business_name;
    $business_type = $dbrow->form_data->business_type;
    $reason = $dbrow->form_data->reason;
    $business_ownership = $dbrow->form_data->business_ownership;
    $owner_name = $dbrow->form_data->owner_name;
    $commencement_business = $dbrow->form_data->commencement_business;
    $ward_no2 = $dbrow->form_data->ward_no2;
    $other_business = $dbrow->form_data->other_business;
    $holding_no = $dbrow->form_data->holding_no;
    $road_name = $dbrow->form_data->road_name;
    $trade_name = $dbrow->form_data->trade_name;
    $fees = $dbrow->form_data->fees;
    $area_in_square = $dbrow->form_data->area_in_square;
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL; //set_value("rtps_trans_id");

    $appref_no = set_value("appref_no");

    $ubin = set_value("ubin");;
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $mobile = $this->session->mobile; //set_value("mobile_number");
    $email = set_value("email");
    $pan_no = set_value("pan_no");
    $father_name = set_value("father_name");
    $ulb = set_value("ulb");
    $ulb_id = set_value("ulb_id");
    $area = set_value("area");
    $mouza = set_value("mouza");
    $post_office = set_value("post_office");
    $country = set_value("country");
    $state = set_value("state");
    $district = set_value("district");
    $pin_code = set_value("pin_code");
    $police_station = set_value("police_station");
    $ward_no = set_value("ward_no");
    $applicant_age = set_value("applicant_age");
    $business_est = set_value("business_est");
    $business_name = set_value("business_name");
    $business_type = set_value("business_type");
    $reason = set_value("reason");
    $business_ownership = set_value("business_ownership");
    $owner_name = set_value("owner_name");
    $father_name2 = set_value("father_name2");
    $commencement_business = set_value("commencement_business");
    $ward_no2 = set_value("ward_no2");
    $other_business = set_value("business_ownership");
    $holding_no = set_value("holding_no");
    $road_name = set_value("road_name");
    $trade_name = set_value("trade_name");
    $fees = set_value("fees");
    $area_in_square = set_value("area_in_square");
} //End of if else //End of if else
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

        $(document).on("click", ".frmbtn", function() {
            let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
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
        <form id="myfrm" method="POST" action="<?= base_url('spservices/tradelicence/application/submit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?= $obj_id ?>" type="hidden" />
            <input name="appl_ref_no" value="<?= $appl_ref_no ?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Trade Licence<br>
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
                            <li>প্ৰমাণ পত্ৰ ৩০ দিনৰ ভিতৰত প্ৰদান কৰা হ'ব</li>
                        </ol>
                        <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>User charge / ব্যৱহাৰকাৰী মাচুল – Rs. 30 / ৩০ টকা</li>
                            <li>Service charge (PFC/ CSC) / সেৱা মাচুল (পি. এফ. চি./ চি. এছ. চি.) – Rs. 30 / ৩০ টকা</li>
                            <li>Printing charge (in case of any printing from PFC) / ছপা খৰচ (পি.এফ.চি. ৰ পৰা কোনো ধৰণৰ প্ৰিন্টিঙৰ ক্ষেত্ৰত) - Rs. 10 Per Page / প্ৰতি
                                পৃষ্ঠাত ১০ টকা</li>
                            <li>Scanning charge (in case documents are scanned in PFC) স্কেনিং খৰচ (যদি নথিপত্ৰসমূহ পি.এফ.চি. ত স্কেন কৰা হয়) - Rs. 5 Per page /
                                প্ৰতি পৃষ্ঠা ৫ টকা</li>
                        </ul>

                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. All the * marked fields are mandatory and need to be filled up.</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব</li>
                            <li>2. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য </li>
                        </ul>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Details of the Trade/Owner </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Application Ref.No. of Common Application Form<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="appref_no" id="appref_no" value="<?= $appref_no ?>" maxlength="255" />
                                <?= form_error("appref_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile" value="<?= $mobile ?>" maxlength="10" <?= (strlen($mobile) == 10) ? 'readonly' : '' ?> />
                                <?= form_error("mobile") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Unique Business Identification No(UBIN)<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="ubin" id="ubin" value="<?= $ubin ?>" maxlength="255" />
                                <?= form_error("ubin") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Name of Applicant<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?= $applicant_name ?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Gender/ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?= ($applicant_gender === "Male") ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= ($applicant_gender === "Female") ? 'selected' : '' ?>>Female</option>
                                    <option value="Others" <?= ($applicant_gender === "Others") ? 'selected' : '' ?>>Others</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Father's/Husband's Name/পিতাৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?= $father_name ?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>ULB,Where you want to apply? <span class="text-danger">*</span> </label>
                                <select name="ulb" id="ulb" class="form-control ">
                                    <option value="<?= $ulb ?>"><?= strlen($ulb) ? $ulb : 'Select' ?></option>
                                    <!-- <option value="<?= $ulb ?>">
                                        <?= strlen($ulb) ? $ulb : 'Select ULB' ?></option> -->
                                    <?php foreach ($ulb_list as $ul) { ?>
                                        <option data-ulb_id="<?php echo  $ul['ulb_id'] ?>" value="<?php echo  $ul['ulb_name'] ?>"><?php echo  $ul['ulb_name'] ?></option>
                                    <?php }  ?>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="ulb_id" id="ulb_id">
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>E-Mail ID <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="email" id="email" value="<?= $email ?>" maxlength="255" />
                                <?= form_error("email") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Area/Village <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="area" value="<?= $area ?>" maxlength="100" />
                                <?= form_error("area") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mouza<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="mouza" value="<?= $mouza ?>" maxlength="100" />
                                <?= form_error("mouza") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Post Office<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="post_office" value="<?= $post_office ?>" maxlength="100" />
                                <?= form_error("post_office") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Country <span class="text-danger">*</span> </label>
                                <select name="country" class="form-control">
                                    <option value="India" selected="selected">India</option>
                                </select>
                                <?= form_error("country") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>State/ ৰাজ্য <span class="text-danger">*</span> </label>
                                <select name="state" class="form-control">
                                    <option value="Assam" selected="selected">Assam</option>
                                </select>
                                <?= form_error("state") ?>
                            </div>
                            <div class="col-md-6">
                                <label>District/ জিলা <span class="text-danger">*</span> </label>
                                <select name="district" id="district" class="form-control dists">
                                    <option value="<?= $district ?>"><?= strlen($district) ? $district : 'Select' ?></option>
                                </select>
                                <?= form_error("district") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Pin Code/ পিন কোড<span class="text-danger">*</span></label>
                                <input type="text" class="form-control pin_code number_input" name="pin_code" value="<?= $pin_code ?>" maxlength="6" />
                                <?= form_error("pin_code") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Police Station/ আৰক্ষী থানা<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="police_station" value="<?= $police_station ?>" maxlength="100" />
                                <?= form_error("police_station") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Ward No<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="ward_no" value="<?= $ward_no ?>" maxlength="100" />
                                <?= form_error("ward_no") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Other Details</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Age of Applicant </label>
                                <input type="text" class="form-control" name="applicant_age" value="<?= $applicant_age ?>" maxlength="100" />
                                <?= form_error("applicant_age") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Name of Business Establishment <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="business_est" value="<?= $business_est ?>" maxlength="100" />
                                <?= form_error("business_est") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name of Business<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="business_name" value="<?= $business_name ?>" maxlength="100" />
                                <?= form_error("business_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Type of Business <span class="text-danger">*</span> </label>
                                <select name="business_type" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Wholesale" <?= ($business_type === "Wholesale") ? 'selected' : '' ?>>Wholesale</option>
                                    <option value="Retailer" <?= ($business_type === "Retailer") ? 'selected' : '' ?>>Retailer</option>
                                    <option value="Dealer" <?= ($business_type === "Dealer") ? 'selected' : '' ?>>Dealer</option>
                                    <option value="Distributor" <?= ($business_type === "Distributor") ? 'selected' : '' ?>>Distributor</option>
                                    <option value="Others(specify)" <?= ($business_type === "Others(specify)") ? 'selected' : '' ?>>Others(specify)</option>
                                </select>
                                <?= form_error("business_type") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>If other,please specify<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="reason" value="<?= $reason ?>" maxlength="100" />
                                <?= form_error("reason") ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Ownership of Building/Space where the business is to be established<span class="text-danger">*</span> </label>
                                <div class="d-flex space-x-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="business_ownership" id="own" value="Own" <?= ($business_ownership === "Own") ? 'checked' : '' ?> />
                                        <label class="form-check-label" for="own">Own</lable>
                                    </div>
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="radio" name="Rental" id="rental" value="Rental" <?= ($business_ownership === "Rental") ? 'checked' : '' ?> />
                                        <label class="form-check-label" for="rental">Rental</lable>
                                    </div>
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="radio" name="business_ownership" id="ulbs" value="ULBs" <?= ($business_ownership === "ULBs") ? 'checked' : '' ?> />
                                        <label class="form-check-label" for="ulbs">ULB's</lable>
                                    </div>
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="radio" name="business_ownership" id="govt" value="Govt" <?= ($business_ownership === "Govt") ? 'checked' : '' ?> />
                                        <label class="form-check-label" for="govt">Government</lable>
                                    </div>
                                    <?= form_error("business_ownership") ?>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Owner of the Building/Space where the business is to be established:</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name of Owner<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="owner_name" value="<?= $owner_name ?>" maxlength="100" />
                                <?= form_error("owner_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Father's/Husband's Name</label>
                                <input type="text" class="form-control" name="father_name2" value="<?= $father_name2 ?>" maxlength="100" />
                                <?= form_error("father_name2") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Date of Commencement of Business<span class="text-danger">*</span></label>
                                <input type="commencement_business" class="form-control dp" name="commencement_business" id="commencement_business" value="<?= $commencement_business ?>" maxlength="255" />
                                <?= form_error("commencement_business") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Ward No.<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="ward_no2" value="<?= $ward_no2 ?>" maxlength="6" />
                                <?= form_error("ward_no2") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Any other Business in the name of Applicant located in the MB Area<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="other_business" value="<?= $other_business ?>" maxlength="100" />
                                <?= form_error("other_business") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Holding no.of the House/Room<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="holding_no" value="<?= $holding_no ?>" maxlength="6" />
                                <?= form_error("holding_no") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name of the Road<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="road_name" value="<?= $road_name ?>" maxlength="6" />
                                <?= form_error("road_name") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Fees</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Name of Trade<span class="text-danger">*</span> </label>
                                <select name="trade_name" id="trade_name" class="form-control ">
                                    <option value="<?= $trade_name ?>"><?= strlen($trade_name) ? $trade_name : 'Select' ?></option>
                                    <!-- <option value="<?= $trade_name ?>">
                            <?= strlen($trade_name) ? $trade_name : 'Select Trade Name' ?></option> -->
                                    <?php foreach ($tradefees as $tl) { ?>
                                        <option value="<?php echo  $tl['trade_name'] ?>"><?php echo  $tl['trade_name'] ?></option>
                                    <?php }  ?>
                                </select>
                            </div>
                            <div class="col-md-6 d-none area_field">
                                <label>Area(in square meter/square feet)<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="area_in_square" id="area_in_square" value="<?= $area ?>" maxlength="100" />
                                <?= form_error("area_in_square") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Fees<span class="text-danger">*</span></label>

                                <input type="text" class="form-control" name="fees" id="fees" value="<?= $fees ?>" maxlength="100" />
                                <?= form_error("fees") ?>
                            </div>

                        </div>
                    </fieldset>
                    <!--End of .card-body -->

                    <div class="card-footer text-center">
                        <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                            <i class="fa fa-file"></i> Draft
                        </button>
                        <button class="btn btn-success frmbtn" id="SAVE" type="button">
                            <i class="fa fa-angle-double-right"></i> Save &amp; Next
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
<script type="text/javascript">
    $(document).ready(function() {
        $.getJSON("<?= $apiServer ?>district_list.php", function(data) {
            let selectOption = '';
            $.each(data.ListOfDistricts, function(key, value) {
                selectOption += '<option value="' + value.DistrictName + '">' + value.DistrictName + '</option>';
            });
            $('.dists').append(selectOption);
        });

        $("#trade_name").change(function() {

            var ulb_id = $("#ulb_id").val();
            var trade_name = $(this).val();

            if (ulb_id.length > 0) {
                $.ajax({
                    url: '<?= base_url('spservices/tradelicence/application/get_fees') ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        ulb_id: ulb_id,
                        trade: trade_name
                    },
                    success: function(response) {
                        if (response.data.flag == 1) {
                            $('.area_field').removeClass('d-none');
                            // d - none
                        } else {
                            $('.area_field').addClass('d-none');
                        }

                        $("#fees").val(response.data.fees)
                    }
                })
            } else {
                alert('Please select ULB first.');
                $(this).val('');
            }
        })
    });
    $(document).ready(function() {
        // $("p").click(function() {
        //     $(this).hide();
        // });
        // $("p").click(function() {
        $("#area").change(function() {


            var area = $('#area').val();
            var fees = $('#fees').val();

            var result = area * fees;

            // console.log(result);
            $('#fees').val(result);
        });
        $("#ulb").change(function() {
            var area = $(this).find(':selected').attr('data-ulb_id')
            $("#ulb_id").val(area);
        });

    });
</script>