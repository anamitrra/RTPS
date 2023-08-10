<?php
$apiServer = "https://localhost/wptbcapis/"; //For testing

if($data['dbrow']) {
    $title = "Edit Existing Information";
    $obj_id = $data['dbrow']->{'_id'}->{'$id'};
    $rtps_trans_id =$data['dbrow']->service_data->appl_ref_no;
    $district_name = $data['dbrow']->form_data->district_name;
    $sub_division = $data['dbrow']->form_data->sub_division;
    $appl_category = $data['dbrow']->form_data->appl_category;
    $appl_load = $data['dbrow']->form_data->appl_load;
    $no_of_days = $data['dbrow']->form_data->no_of_days;
    $applicant_name = $data['dbrow']->form_data->applicant_name;
    $fathers_name = $data['dbrow']->form_data->fathers_name;
    $applicant_type = $data['dbrow']->form_data->applicant_type;
    $gstn = $data['dbrow']->form_data->gstn;
    $gmc = $data['dbrow']->form_data->gmc;
    $assessment_id = $data['dbrow']->form_data->assessment_id;
    $house_number = $data['dbrow']->form_data->house_number;
    $by_lane = $data['dbrow']->form_data->by_lane;
    $road = $data['dbrow']->form_data->road;
    $area = $data['dbrow']->form_data->area;
    $village_town = $data['dbrow']->form_data->village_town;
    $post_office = $data['dbrow']->form_data->post_office;
    $police_station = $data['dbrow']->form_data->police_station;
    $district = $data['dbrow']->form_data->district;
    $pin = $data['dbrow']->form_data->pin;
    $mobile_number = $data['dbrow']->form_data->mobile_number;
    $e_mail = $data['dbrow']->form_data->e_mail;
    $pan_no = $data['dbrow']->form_data->pan_no;
    $nearest_consumer_no = $data['dbrow']->form_data->nearest_consumer_no ??'';
    $premise_owner = $data['dbrow']->form_data->premise_owner;
    $distance_pole_30 = $data['dbrow']->form_data->distance_pole_30;
    $electricity_due = $data['dbrow']->form_data->electricity_due;
    $existing_connection = $data['dbrow']->form_data->existing_connection;
    $existing_cons_no = $data['dbrow']->form_data->existing_cons_no;
    $existing_connected_load = $data['dbrow']->form_data->existing_connected_load;
    $identity_attach = $data['dbrow']->form_data->identity_attach ?? '';
    $address_attach = $data['dbrow']->form_data->address_attach ?? '';
    $land_attach = $data['dbrow']->form_data->land_attach ?? '';
    $identityFile = $data['dbrow']->form_data->identityFile ?? ''; 
    $addressFile = $data['dbrow']->form_data->addressFile ?? ''; 
    $selffAttestedFile = $data['dbrow']->form_data->selffAttestedFile ?? ''; 
    $testReportFile = $data['dbrow']->form_data->testReportFile ?? ''; 
    $scannedPhoto = $data['dbrow']->form_data->scannedPhoto ?? ''; 
    $gmcFile = $data['dbrow']->form_data->gmcFile ?? ''; 
    $nocFile = $data['dbrow']->form_data->nocFile ?? ''; 
    
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $rtps_trans_id = NULL;
    $district_name = set_value("district_name");
    $sub_division = set_value("sub_division");
    $appl_category = set_value("appl_category");
    $appl_load = set_value("appl_load");
    $no_of_days = set_value("no_of_days");
    $applicant_name = set_value("applicant_name");
    $fathers_name = set_value("fathers_name");
    $applicant_type = set_value("applicant_type");
    $gstn = set_value("gstn");
    $gmc = set_value("gmc");
    $assessment_id = set_value("assessment_id");
    $house_number = set_value("house_number");
    $by_lane = set_value("by_lane");
    $road = set_value("road");
    $area = set_value("area");
    $village_town = set_value("village_town");
    $post_office = set_value("post_office");
    $police_station = set_value("police_station");
    $district = set_value("district");
    $pin = set_value("pin");
    $add1 = set_value("applicant_add1");
    $add2 = set_value("applicant_add2");
    $mobile_number = set_value("mobile_number");
    $e_mail = set_value("e_mail");
    $adhar_no = set_value("adhar_no");
    $pan_no = set_value("pan_no");
    $nearest_consumer_no = set_value("nearest_consumer_no");
    $premise_owner = set_value("premise_owner");
    $distance_pole_30 = set_value("distance_pole_30");
    $electricity_due = set_value("electricity_due");
    $existing_connection = set_value("existing_connection");
    $existing_cons_no = set_value("existing_cons_no");
    $existing_connected_load = set_value("existing_connected_load");  
    $identity_attach = NULL; 
    $address_attach = NULL; 
    $land_attach = NULL; 
    $identityFile = NULL; 
    $addressFile = NULL; 
    $selffAttestedFile =NULL; 
    $testReportFile = NULL; 
    $scannedPhoto = NULL; 
    $gmcFile = NULL; 
    $nocFile = NULL; 
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

.nameLength,.fnameLength,.spanHidePhno, .spanHidePan, .spanHideAadhar, .spanHidePin, .spanHideGstn {
	display: none;
    font-size: 12px;
    color: #d43f3a;
    font-weight: bold;
    font-style: italic;
}
#tooltip {
    position: relative;
    display: inline-block;
	color:green;
	font-size: 20px;
	cursor: pointer;
}
#tooltip #tooltipText {
	font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    visibility: hidden;
    min-width: 700px;
	background-color: black;
    color: white;
    text-align: center;
	font-size: 16px;
    border-radius: 6px;
    padding: 15px 10px 15px 0px;
    position: absolute;
    z-index: 1;
    bottom: 100%;
	right: 50%;
    opacity: 0;
    transition: opacity 1s;
	font-weight: normal;
}
#tooltip:hover #tooltipText {
    visibility: visible;
    opacity: 1;
}
#validate{
	color: black;
	background-color: #EAEAEA;
	border: 2px solid black;
	height: 36px;
	width: 100px;
	border-radius: 5px;
	margin-left: 50px;
	font-size: 16px;
}
.modal-dialog {
  	min-width:800px;
}
</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
  
        
<main class="rtps-container">
    <div class="container my-2">
    
        <form id="apdclForm" method="POST" action="<?= base_url('spservices/apdcl_form')  ?>">

            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$rtps_trans_id?>" type="hidden" />
            <input id="submit_mode" name="submit_mode" value="" type="hidden" />
            <div class="card shadow-sm" >
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                <img src="<?php echo base_url(); ?>storage/apdcl/apdcl.png" alt="" width="35px" height="35px" style="margin-right:20px">
                    ASSAM  POWER  DISTRIBUTION  COMPANY  LIMITED<br>
                        ( অসম শক্তি বিতৰণ কোম্পানী লিমিটেড ) 
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
                    <input type="hidden" name="identity_attach" value="<?=$identity_attach ?>">
                    <input type="hidden" name="address_attach" value="<?=$address_attach ?>">
                    <input type="hidden" name="land_attach" value="<?=$land_attach ?>">
                    <input type="hidden" name="identityFile" value="<?=$identityFile ?>">
                    <input type="hidden" name="addressFile" value="<?=$addressFile ?>">
                    <input type="hidden" name="selffAttestedFile" value="<?=$selffAttestedFile ?>">
                    <input type="hidden" name="testReportFile" value="<?=$testReportFile ?>">
                    <input type="hidden" name="scannedPhoto" value="<?=$scannedPhoto ?>">
                    <input type="hidden" name="gmcFile" value="<?=$gmcFile ?>">
                    <input type="hidden" name="nocFile" value="<?=$nocFile ?>">

                    <h5 style="color: #ffff; background-color:#00838F; padding:10px 0px 10px 0px; border-radius:5px" class="text-center"></i>Application for new Low Tension connection / নতুন নিম্ন টেনচন সংযোগৰ বাবে আবেদন</h5>
                          
                    <h5 style="color: #00838F;margin-top:10px"><i class="fas fa-clipboard-list" style="margin-right: 20px;"></i>REGISTRATION  FORM / পঞ্জীয়ন প্ৰপত্ৰ</h5>
                    <fieldset class="border border-success" style="margin-top:30px">
                        <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                    <label>Name of the Applicant / আবেদনকাৰীৰ নাম <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name ?>"/>
                                    <?= form_error("applicant_name"); ?>
                                    <span class="nameLength">Applicant name length should not exceed 100</span>                                   
                            </div>
                            <div class="col-md-6">
                                    <label>Father&apos;s Name  / পিতৃৰ নাম <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="fathers_name" id="father_name" value="<?=$fathers_name ?>">
						            <?= form_error("fathers_name"); ?>
						            <span class="fnameLength">Father's name length should not exceed 50</span>
                            </div>
                        </div>    

                        <div class="row form-group">
                            <div class="col-md-6">
                                    <label>Mobile Number / দুৰভাষ (মবাইল) <span class="text-danger">*</span> </label>
                                    <input type="number" class="form-control" name="mobile_number" id="mobile_number" maxlength="10" value="<?= $mobile_number ?>" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                                    <?= form_error("mobile_number"); ?>
						            <span class="spanHidePhno">Mobile No. must be 10 digits</span>
                            </div>
                            <div class="col-md-6">
                                    <label>Email Id / ইমেইল </label>
                                    <input type="text" class="form-control" name="e_mail" id="email"  value="<?= $e_mail ?>">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                    <label>PAN No. / পেন নং</label>
                                    <input type="text" class="form-control" name="pan_no" id="pan_number" value="<?= $pan_no ?>" placeholder="Eg: AAAAA5555A">
						            <span class="spanHidePan" id="spanHidePan">Invalid PAN format</span>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Apply to / প্ৰয়োগ কৰা হৈছে </legend>

                        <div class="row form-group">
                            <div class="col-md-6">
                                    <label>Select District / জিলা <span class="text-danger">*</span> </label>
                                    <?php if(strlen($district_name)){ ?>
                                        <select class="form-control" name="district_name" id="district_name">
                                            <option value="<?= $district_name ?>" disable selected hidden><?= $district_name ?></option>
                                            <?php foreach ($districts as $key => $dist){ ?>
                                            <option value="<?php echo $dist ?>"><?php echo $dist ?></option>
                                            <?php } ?>
                                    </select><br>
                                    <?php } else {?>
                                    <select class="form-control" name="district_name" id="district_name">
                                        <option value="" disable selected hidden>--Select--</option>
                                            <?php foreach ($districts as $key => $dist){ ?>
                                            <option value="<?php echo $dist ?>"><?php echo $dist ?></option>
                                            <?php } ?>
                                    </select><br>
                                    <?= form_error("district_name"); ?> 
                                    <?php } ?>                                                              
                            </div>
                            <div class="col-md-6">
                                    <label>Select Sub-Division / মহকুমা <span class="text-danger">*</span> </label>
                                    <?php if(strlen($sub_division)) { ?>
                                        <select class="form-control" name="sub_division" id="sub_division">
								            <option value="<?php echo $sub_division ?>" disable selected hidden><?php echo $sub_division ?></option>
                                        </select><br>
                                    <?php } else { ?>
                                        <select class="form-control" name="sub_division" id="sub_division">
								            <option value="" disable selected>--Select--</option>
								            <option value=""></option>
                                        </select><br>
                                    <?php } ?>    
                                        <?= form_error("sub_division"); ?>
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                    <label>Applied Category / প্ৰয়োগ কৰা শ্ৰেণী <span class="text-danger">*</span> </label>
                                    <?php if(strlen($appl_category)){ ?>
                                        <select class="form-control" name="appl_category" id="applied_category" onblur="category();">
                                            <option value="<?=$appl_category?>" disable selected hidden><?=$appl_category?></option>
                                            <?php foreach ($categories as $key => $category)
                                            { ?>
                                                <option value="<?php echo $category['category_CODE']; ?>"><?php echo $category['category_CODE']; ?></option>
                                            <?php } ?>
						                </select><br>
                                    <?php } else {?>
                                        <select class="form-control" name="appl_category" id="applied_category" onblur="category();">
                                            <option value="" disable selected hidden>--Select Category--</option>
                                            <?php foreach ($categories as $key => $category)
                                            { ?>
                                                <option value="<?php echo $category['category_CODE']; ?>"><?php echo $category['category_CODE']; ?></option>
                                            <?php } ?>
						                </select><br>
						            <?= form_error("appl_category"); ?>
                                    <?php } ?>
                            </div>
                            <div class="col-md-6">
                                    <label>Applied Load(KW) / প্ৰয়োগ কৰা লোড <span class="text-danger">*</span> </label>
                                    <input class="form-control" name="appl_load" id="applied_load" value="<?= $appl_load ?>"/> <br>
						            <?= form_error("appl_load"); ?>
                            </div>
                        </div>
                        <?php if(strlen($no_of_days)) {?>
                        <div class="row form-group" id="noOfDay" <?php if($appl_category == '54' || $appl_category == '55' || $appl_category == '69') { ?> style="" <?php } else {?> style="display:none" <?php } ?>>
                            <div class="col-md-6">
                                    <label>No. of Days / দিনৰ সংখ্যা <span class="text-danger">*</span> </label>
                                    <input type="number" class="form-control" min="0" value="<?= $no_of_days ?>" name="no_of_days" id="no_of_days"><br>
						            <?= form_error("no_of_days"); ?> 
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                        <?php } else { ?>
                            <div class="row form-group" id="noOfDay" style="display: none">
                            <div class="col-md-6">
                                    <label>No. of Days / দিনৰ সংখ্যা <span class="text-danger">*</span> </label>
                                    <input type="number" class="form-control" min="0" value="0" name="no_of_days" id="no_of_days"><br>
						            <?= form_error("no_of_days"); ?>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                        <?php } ?>

                        <div class="row form-group">
                            <div class="col-md-6">
                                    <label>Type of the Applicant / আবেদনকাৰীৰ প্ৰকাৰ <span class="text-danger">*</span> </label>
                                    
                                        <select class="form-control" name="applicant_type" id="applicant_type">
                                            <option value="">--Select</option>
                                            <option value="prePaidConsumer" <?=($applicant_type === "prePaidConsumer")?'selected':''?>>prePaidConsumer</option>
                                            <option value="postPaidConsumer" <?=($applicant_type === "postPaidConsumer")?'selected':''?>>postPaidConsumer</option>
                                        </select><br>
                                        <?= form_error("applicant_type"); ?>                                   
                            </div>
                            <div class="col-md-6">
                                    <label>GSTN(If available) / জি.এছ.টি.এন.(যদি উপলব্ধ) </label>
                                    <input type="text" class="form-control" name="gstn" id="gstn" value="<?= $gstn ?>">
                                    <span class="spanHideGstn" id="spanHideGstn">Invalid GSTN format</span> 
                                    <!-- gstn sample= 01|13|35ABCD64564V3Q4 -->
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                    <label for="" id="labelDark">Wheather the applicant is under GMC / আবেদনকাৰী জিএমচিৰ অধীনত আছে নেকি ?</label>
						            <input class="ml-2" type="radio" name="gmc" value="N" id="gmcNo" style="margin-left: 10px;" <?php if($gmc=='N') echo "checked='checked'"; ?>> No
						            <input type="radio" name="gmc" value="Y" id="gmcYes" style="margin-left: 10px;" <?php if($gmc=='Y') echo "checked='checked'"; ?>> Yes
                            </div>
                            <?php if(strlen($assessment_id)) {?>
                            <div class="col-md-6" id="assessmentDiv">
                                    <label id="labelDark">Assessment Id / মূল্যায়ন আইডি
                                        <i class="fas fa-info-circle assessmentIcon" id="tooltip">
                                        <span id="tooltipText">Uploading of Affidavit / NOC / Self declaration is mandatory if Assesment Id is not available</span></i>
                                    </label>
                                    <input type="text" name="assessment_id" id="assessment_id"  value="<?=$assessment_id ?>" placeholder="20-78-12228540" style="width:350px;height: 36px;">
                                    <a class="btn" id="validate">Validate</a><br>
                                    <!-- <input type="hidden" id="new_assessment_id" name="new_assessment_id" value="<?=$assessment_id ?>" readonly> -->
                                    <a style="margin-right: 60px; font-size:16px; color:blue; text-decoration:none" href="https://ptax.gmcassam.in:8443/GMCPortal/" target="_blank">Find Assesment Id</a><br><br>                    
                            </div><br>
                            <?php } else { ?>
                            <div class="col-md-6" id="assessmentDiv" style="display: none">
                                    <label id="labelDark">Assessment Id / মূল্যায়ন আইডি
                                        <i class="fas fa-info-circle assessmentIcon" id="tooltip">
                                        <span id="tooltipText">Uploading of Affidavit / NOC / Self declaration is mandatory if Assesment Id is not available</span></i>
                                    </label>
                                    <input type="text" name="assessment_id" id="assessment_id" placeholder="20-78-12228540" style="width:350px;height: 36px;">
                                    <a class="btn" id="validate">Validate</a><br>
                                    <!-- <input type="hidden" id="new_assessment_id" name="new_assessment_id" readonly> -->
                                    <a style="margin-right: 60px; font-size:16px; color:blue; text-decoration:none" href="https://ptax.gmcassam.in:8443/GMCPortal/" target="_blank">Find Assesment Id</a><br><br>                    
                            </div><br>
                            <?php } ?>
                        </div>
                            <!-- GMC holding no Modal -->
                            <div class="modal" id="myModal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                            <h5 class="modal-title" style="color: green">Consumer Holding No. Details</h5>
                                    </div>
                                    <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-3"><strong>Assessment ID:</strong></div>
                                                <div class="col-md-3" id="assessmentId"></div>
                                                <div class="col-md-3"><strong>Holding No:</strong></div>
                                                <div class="col-md-3" id="holdingNo"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><strong>Owner Name:</strong></div>
                                                <div class="col-md-3" id="ownerName"></div>
                                                <div class="col-md-3"><strong>Location:</strong></div>
                                                <div class="col-md-3" id="locationName"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><strong>House No:</strong></div>
                                                <div class="col-md-3" id="houseNo"></div>
                                                <div class="col-md-3"><strong>Zone:</strong></div>
                                                <div class="col-md-3" id="zone"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3"><strong>Road No / Road Name:</strong></div>
                                                <div class="col-md-3" id="road"></div>
                                                <div class="col-md-3"><strong>Ward No:</strong></div>
                                                <div class="col-md-3" id="wardNo"></div>
                                            </div>
                                    </div>
                                    <div class="modal-footer">
                                            <button type="button" class="btn btn-success" data-dismiss="modal">OK</button>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <!-- end modal -->
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Desired Connection Details / বিচৰা সংযোগৰ বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                    <label>House No. / ঘৰৰ নং </label>
                                    <input type="text" class="form-control" name="house_number" id="house_number" value="<?= $house_number ?>">
                            </div>
                            <div class="col-md-6">
                                    <label>By lane / উপ-পথ </label>
                                    <input type="text" class="form-control" name="by_lane" id="by_lane" value="<?= $by_lane ?>">
                            </div><br>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                    <label>Road / পথ</label>
                                    <input type="text" class="form-control" name="road" id="road" value="<?= $road ?>">
                            </div>
                            <div class="col-md-6">
                                    <label>Area / অঞ্চল <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="area" id="area" value="<?= $area ?>">
						            <?= form_error("area"); ?>
                            </div><br>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                    <label>Village-Town / গাওঁ-নগৰ <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="village_town" id="village_town" value="<?= $village_town ?>">
                                    <?= form_error("village_town"); ?>
                            </div>
                            <div class="col-md-6">
                                    <label>Post Office / ডাকঘৰ <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="post_office" id="post_office" value="<?= $post_office ?>">
                                    <?= form_error("post_office"); ?>
                            </div><br>
                        </div>
                        <!-- hidden address -->
                            <textarea name="applicant_add1" id="applicant_add1" style="display: none;"></textarea>
                            <textarea name="applicant_add2" id="applicant_add2" style="display: none;"></textarea>
                        <!-- hidden address -->

                        <div class="row form-group">
                            <div class="col-md-6">
                                    <label>Police Station / থানা <span class="text-danger">*</span> </label>
                                    <input type="text" class="form-control" name="police_station" id="police_station" value="<?= $police_station ?>">
						            <?= form_error("police_station"); ?>
                            </div>
                            <div class="col-md-6">
                                    <label>District / জিলা <span class="text-danger">*</span> </label>
                                    <?php if(strlen($district)) { ?>
                                    <input type="text" class="form-control" name="district" id="district" value="<?= $district ?>" readonly>
                                    <?php } else { ?>
                                    <input type="text" class="form-control" name="district" id="district" readonly>
                                    <?php } ?>
                            </div><br>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-6">
                                    <label>PIN Code / পিন নং (e.g. 78xxxx)<span class="text-danger">*</span> </label>
                                    <input type="number" class="form-control" name="pin" id="pin_code" value="<?= $pin ?>" maxlength="6" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
						            <?= form_error("pin"); ?>
                            </div>
                            <div class="col-md-6">
                                    <label>Nearest Consumer No. /  নিকটতম উপভোক্তা নং </label>
                                    <input type="text" class="form-control" name="nearest_consumer_no" id="nearest_consumer_no" value="<?= $nearest_consumer_no ?>">
                            </div>
                            <br>
                        </div>
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Premises Details / চৌহদৰ বিৱৰণ </legend>
                        
                        <div class="row form-group">
                            <div class="col-md-6">
                                    <label><span class="text-danger">* </span> Whether the applicant is the owner of the premises / আবেদনকাৰী চৌহদৰ গৰাকী নে ?</span> </label>
                                    <input type="radio" name="premise_owner" id="ownerNo" value="N" style="margin-left: 20px;" <?php if($premise_owner=='N') echo "checked='checked'"; ?>> No
                                    <input type="radio" name="premise_owner" id="ownerYes" value="Y" style="margin-left: 20px;" <?php if($premise_owner=='Y') echo "checked='checked'"; ?>> Yes<br>
                                    <?= form_error("premise_owner"); ?>
                            </div>
                            <div class="col-md-6">
                                    <label><span class="text-danger">* </span> Whether distance of premises from pole is less than 30m / মেৰুৰ পৰা চৌহদৰ দূৰত্ব 30 মিটাৰতকৈ কম হয় নে ?</span> </label>
                                    <input type="radio" name="distance_pole_30" id="distanceNo" value="N" style="margin-left: 20px;" <?php if($distance_pole_30=='N') echo "checked='checked'"; ?>> No
                                    <input type="radio" name="distance_pole_30" id="distanceYes" value="Y" style="margin-left: 20px;" <?php if($distance_pole_30=='Y') echo "checked='checked'"; ?>> Yes<br>
                                    <?= form_error("distance_pole_30"); ?>
                            </div><br>
                        </div>

                        <div class="row form-group">                           
                            <div class="col-md-6">
                                    <label><span class="text-danger">* </span> Is there any electricity due outstanding in premises / চৌহদত কোনো বিদ্যুৎ বকেয়া আছে নেকি ?</span> </label>
                                    <input type="radio" name="electricity_due" id="outstandingNo" value="N" style="margin-left: 20px;" <?php if($electricity_due=='N') echo "checked='checked'"; ?>> No
                                    <input type="radio" name="electricity_due" id="outstandingYes" value="Y" style="margin-left: 20px;" <?php if($electricity_due=='Y') echo "checked='checked'"; ?>> Yes<br>
                                    <?= form_error("electricity_due"); ?>
                            </div>
                            <div class="col-md-6">
                                    <label><span class="text-danger">* </span> If there is any existing connection of the premises / চৌহদৰ কোনো বিদ্যমান সংযোগ আছে নেকি ?</label>
                                    <input type="radio" name="existing_connection" id="connectionNo" value="N" style="margin-left: 20px;" <?php if($existing_connection=='N') echo "checked='checked'"; ?>> No
                                    <input type="radio" name="existing_connection" id="connectionYes" value="Y" style="margin-left: 20px;" <?php if($existing_connection=='Y') echo "checked='checked'"; ?>> Yes<br>
                                    <?= form_error("existing_connection"); ?>
                            </div><br>
                        </div>

                        <div class="row form-group" id="consumerDiv" style="display: none">                           
                            <div class="col-md-6"></div>
                            <div class="col-md-3">
                                    <label for="" id="labelDark">Consumer Number/উপভোক্তা নং: </label>
                                    <input type="text" class="form-control" name="existing_cons_no" id="consumer_number">
                            </div>
                            <div class="col-md-3">
                                    <label for="" id="labelDark">Consumer Load/উপভোক্তা লোড: </label>
                                    <input type="number" class="form-control" name="existing_connected_load" id="consumer_load" min="0">
                            </div><br>
                        </div>
                        
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Declaration / ঘোষণা </legend>

                        <div class="row" style="margin: 0px 10px 0px 10px; font-size: 16px"> 
                            <div class="col">
                                <input type="checkbox" id="checkbox" name="checkbox" style="width: 20px; height: 20px;">	
                                <span id="declaration">
                                I / We declare that the informations given above is true to the best of my knowledge and belief.
                                I / We further confirm that there are no orders of Court / Govt. restricting electricity connection 
                                in the premises, that I / We will remit electricity dues during every billing cycle and also as and 
                                when demanded as per the applicable electricity tariff, and other charges, that I / We will own the 
                                responsibility of security and safety of the meter, cut-out and the installation thereafter.
                                I / We will not indulge in any misuse of power and will take all necessary steps in the premises for 
                                the efficient use of power and to stop its wastage.
                                APDCL will not be responsible for any untoward happening arising out of unauthorised extension of load, 
                                mishandling of the electrical appliances and wire etc.
                                I / We will abide by the rules and regulations of APDCL. 
                                In case of any wrong information furnished by me / us intentionally or unintentionally, APDCL will be 
                                at liberty not to release service connection and forfeit the money if deposited or disconnect the line.
                                </span>
                            </div>
                        </div>

                    </fieldset>
                     
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Save & Next
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>

<script>
	$(document).ready(function(){
		$(".assessmentIcon").hover(function () {
        		$('[data-toggle="tooltip"]').tooltip();
    	}); 
        $('#gmcNo').click(function(){
			$('#assessmentDiv').hide();
            $('#assessment_id').val('');		
		});
		$('#gmcYes').click(function(){
			$('#assessmentDiv').show();
		});
        $('#connectionYes').click(function(){
			$('#consumerDiv').show();
		});
		$('#connectionNo').click(function(){
			$('#consumerDiv').hide();
		});					
		
        $('#district_name').blur(function(){
				var distName = $("#district_name").val();
					$('#district').val(distName);
		});
        $('#mobile_number').blur(function() {
			var mobile = $('#mobile_number').val();
			if(mobile.length == 0)
				{
					$('.spanHidePhno').hide();
				} else {
						if (mobile.length > 10 || mobile.length < 10) {
							$('.spanHidePhno').show();
						} else {
								$('.spanHidePhno').hide();
						}
				}
		});
        $('#pan_number').blur(function() {
					var panSize = $('#pan_number').val();
					if(panSize.length == 0)
					{
						$('.spanHidePan').hide();
					}else {
						var panFormat = /[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
						if(!panFormat.test(panSize)){
								$('.spanHidePan').show();
						}
						else {
								$('.spanHidePan').hide();
						}
					}
		});
        $('#gstn').blur(function() {
					var gstn = $('#gstn').val();
					if(gstn.length == 0)
					{
						$('.spanHideGstn').hide();
					}else {
						var gstnFormat = /([0]{1}[1-9]{1}|[1-2]{1}[0-9]{1}|[3]{1}[0-7]{1})([A-Z]{4}[0-9A-Z]{1}[0-9]{4}[A-Z]{1}[1-9]{1}[A-Z]{1}[0-9A-Z]{1})$/;
						if(!gstnFormat.test(gstn)){
								$('.spanHideGstn').show();
						}
						else {
								$('.spanHideGstn').hide();
						}
					}
		});
        $('#outstandingYes').click(function(){
            var msg1 = 'Attention Please / মনোযোগ দিয়ক';
            var msg2 = 'If you have electricity due outstanding, your Application will be rejected. ( যদি আপোনাৰ ওচৰত বিদ্যুৎ বকেয়া আছে, আপোনাৰ আবেদন নাকচ কৰা হব ) ';
						Swal.fire({
								title: msg1,
                                text: msg2,
                                icon: 'error',
								color:'#FEFEFE',
								showConfirmButton: false,
								showCloseButton: true
						});            
        });
	});
</script>
<!-- get sub division -->
<script>
 $(document).ready(function(){
	$('#district_name').change(function(){
		var dist = $('#district_name').val();
		$.ajax({
			url: 'https://www.apdclrms.com/cbs/RestAPI/getSubdiv?districtName='+dist,
			type: 'GET',
            dataType: 'json',
            success: function(data) {
                    $("#sub_division").empty();
                    $("#sub_division").append('<option disable selected hidden>--Select--</option>');
                    $.each(data,function(key,value){
                                        $("#sub_division").append('<option value="'+value+'">'+value+'</option>');
            		});
            }
		});
	});
 });
</script>
<!-- end sub division -->
<!-- Category,Address Function -->
<script>
    function category(){
        var appliedCat = $("#applied_category").val();
        var applCat = appliedCat.substring(0,2);
        if(applCat == 69 || applCat == 54 || applCat == 55)
            {
                $('#noOfDay').show();
                $('.spanHideDay').hide();
            }
        else
            {
                $('#noOfDay').hide();
                $('#no_of_days').val('0');
            }	
    }

    //holding no validation
    $('#validate').click(function(){
	    var assessmentId = $('#assessment_id').val();
		$.ajax({
				url:'https://ptax.gmcassam.in:8443/GMCPortal/GetAssessmentServices?assessmentId='+assessmentId,
				method: 'GET',
				dataType: 'JSON',
				success:function(data, status) {
					
							if(data.AssessmentKey != null) {
								var msg = 'GMC Holding No. Validated. Please Proceed further !!!';
								Swal.fire({
									text: msg,
									timer: 4000,
									color:'#FEFEFE',
									background:'#30C60F',
									showConfirmButton: false,
									width: 600
								});							

								$("#myModal").modal({backdrop: 'static'},'show');
								$('#assessmentId').html(data.AssessmentKey);
								$('#holdingNo').html(data.HoldingNo);
								$('#ownerName').html(data.OwnerName);
								$('#locationName').html(data.LocationName);
								$('#houseNo').html(data.HouseNo);
								$('#zone').html(data.Zone);
								$('#road').html(data.RoadNo+' / '+data.RoadName);
								$('#wardNo').html(data.Ward);
								console.log(status);

								//var new_assessment = $('#assessment_id').val();
                                //$('#new_assessment_id').val(new_assessment);
                               // $('#assessment_id').attr('disabled', true);
								$('#validate').attr('disabled', true);
							} else {
								var msg = 'Holding No. validation Failed. Kindly Check !!';	
								Swal.fire({
									text: msg,
									color: '#000000',
									background: '#F96C11',
									width: 500,
									showConfirmButton: false,
									timer:4000
								});
							}				
				}
		});
    }); //end of holding no validation
 $('.frmbtn').click(function(){
	event.preventDefault();
    category();

    let clickedBtn = $(this).attr("id");
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE') {
                var msg = "Do you want to procced";
            } else if(clickedBtn === 'CLEAR') {
                var msg = "Once you Reset, All filled data will be cleared";
            } else {
                var msg = "";
            }           
            Swal.fire({
                title: 'Are you sure?',
                text: msg,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    if((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
                            $("#apdclForm").submit();
                                        
                    } else if(clickedBtn === 'CLEAR') {
                                $("#apdclForm")[0].reset();
                    } else {}
                }
            });
});

</script>