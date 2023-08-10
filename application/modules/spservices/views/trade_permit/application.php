<?php
    $circles = $this->circle_model->get_rows();
    $states = $this->state_model->get_rows();
    $tareas = $this->tradearea_model->get_rows();
if($dbrow) {
    $obj_id = $dbrow->{'_id'}->{'$id'};       
    $rtps_trans_id = $dbrow->form_data->rtps_trans_id;
    $form_status = $dbrow->service_data->appl_status;

    $circle_id = $dbrow->form_data->circle_id;
    $district_id_ca = $dbrow->form_data->district_id_ca;
    
    $first_name = $dbrow->form_data->first_name;
    $last_name = $dbrow->form_data->last_name;
    $father_name = $dbrow->form_data->father_name;
    $contact_number = $dbrow->form_data->mobile_number;
    $emailid = $dbrow->form_data->email;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $age = $dbrow->form_data->age;
    $community = $dbrow->form_data->community;
    $profession = $dbrow->form_data->profession;
    $nationality = $dbrow->form_data->nationality;

    $pa_address_line_1 = $dbrow->form_data->pa_address_line_1;
    $pa_address_line_2 = $dbrow->form_data->pa_address_line_2;
    $pa_address_line_3 = $dbrow->form_data->pa_address_line_3;
    $pa_post_office = $dbrow->form_data->pa_post_office;
    $pa_pin_code = $dbrow->form_data->pa_pin_code;
    $pa_village = $dbrow->form_data->pa_village;
    $pa_police_station = $dbrow->form_data->pa_police_station;
    $pa_circle = $dbrow->form_data->pa_circle;

    $address_same = $dbrow->form_data->address_same;

    $ca_address_line_1 = $dbrow->form_data->ca_address_line_1;
    $ca_address_line_2 = $dbrow->form_data->ca_address_line_2;
    $ca_address_line_3 = $dbrow->form_data->ca_address_line_3;
    $ca_post_office = $dbrow->form_data->ca_post_office;
    $ca_pin_code = $dbrow->form_data->ca_pin_code;
    $ca_village = $dbrow->form_data->ca_village;
    $ca_police_station = $dbrow->form_data->ca_police_station;
    $ca_district = $dbrow->form_data->ca_district;
    $ca_state= $dbrow->form_data->ca_state;

    $market_place = $dbrow->form_data->market_place;
    $commodities = $dbrow->form_data->commodities;
    $location = $dbrow->form_data->location;
    $govt_emp = $dbrow->form_data->govt_emp;
    $trade_area = $dbrow->form_data->trade_area;

    $soft_doc_type = $dbrow->form_data->soft_doc_type;
    $soft_doc = $dbrow->form_data->soft_doc;
    $passport_pic_type = $dbrow->form_data->passport_pic_type;
    $passport_pic = $dbrow->form_data->passport_pic;
    $doc_type = $dbrow->form_data->doc_type;
    $doc = $dbrow->form_data->doc;

    if($form_status==='QS'){
        $query_asked = $dbrow->processing_history[count($dbrow->processing_history)-1]->remarks??'This is the query asked by departmental user';
        $queried_by = $dbrow->processing_history[count($dbrow->processing_history)-1]->processed_by??'Departmental user';
        $qTime = $dbrow->execution_data[1]->task_details->received_time??$dbrow->service_data->submission_date;
        $queried_time = date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($qTime)));
        $query_answered = $dbrow->form_data->query_answered??'';
    }
}
else {
    $obj_id = null;        
    $rtps_trans_id = null;
    $status = null;

    $contact_number = (strlen($this->session->mobile)==10)?$this->session->mobile:set_value("contact_number");
    $first_name = set_value("first_name");
    $last_name = set_value("last_name");
    $father_name = set_value("father_name");
    $emailid = set_value("emailid");
    $applicant_gender = set_value("applicant_gender");
    $age = set_value("age");
    $community = set_value("community");
    $profession = set_value("profession");
    $nationality = set_value("nationality");

    $circle_id = set_value("circle_id") ?? NULL;
    $district_id_ca = set_value("district_id_ca") ?? NULL;

    $pa_address_line_1 = set_value("pa_address_line_1");
    $pa_address_line_2 = set_value("pa_address_line_2");
    $pa_address_line_3 = set_value("pa_address_line_3");
    $pa_post_office = set_value("pa_post_office");
    $pa_pin_code = set_value("pa_pin_code");
    $pa_village = set_value("pa_village");
    $pa_police_station = set_value("pa_police_station");
    $pa_circle = set_value("pa_circle");

    $address_same = set_value("address_same");

    $ca_address_line_1 = set_value("ca_address_line_1");
    $ca_address_line_2 = set_value("ca_address_line_2");
    $ca_address_line_3 = set_value("ca_address_line_3");
    $ca_post_office = set_value("ca_post_office");
    $ca_pin_code = set_value("ca_pin_code");
    $ca_village = set_value("ca_village");
    $ca_police_station = set_value("ca_police_station");
    $ca_district = set_value("ca_district");
    $ca_state= set_value("ca_state");

    $market_place = set_value("market_place");
    $commodities = set_value("commodities");
    $location = set_value("location");
    $govt_emp = set_value("govt_emp");
    $trade_area = set_value("trade_area");


    $uploadedFiles = $this->session->flashdata('uploaded_files');
    $soft_doc_type = set_value("soft_doc_type");
    $soft_doc = $uploadedFiles['soft_doc_old']??null;
    $passport_pic_type = set_value("passport_pic_type");
    $passport_pic = $uploadedFiles['passport_pic_old']??null;
    $doc_type = set_value("doc_type");
    $doc = $uploadedFiles['doc_old']??null;

    if($form_status==='QS'){
        $form_status = set_value("form_status");
        $query_asked = '';
        $queried_by = '';
        $queried_time = '';
        $query_answered = set_value("query_answered");
        //$query_doc = $uploadedFiles['query_doc_old']??null;
    }
    $status = null;
}
$mobile_verify_status = (strlen($contact_number) == 10)?1:0;
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
    .form-group {
        margin-bottom: .4rem;
    }
    label {
        margin-bottom: .1rem;
    }
</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/plugins/webcamjs/webcam.min.js') ?>" type="text/javascript"></script>

<script type="text/javascript">  
 

    $(document).ready(function () {
        $("#ca_state_div_1").hide();
        $("#ca_state_div_2").show();
        $("#ca_district_div_1").hide();
        $("#ca_district_div_2").show(); 

        $('.number_input').keypress(function (e) {    
            var charCode = (e.which) ? e.which : event.keyCode    
            if (String.fromCharCode(charCode).match(/[^0-9]/g))    
                return false;                        
        });

        var soft_doc = parseInt(<?= strlen($soft_doc) ? 1 : 0 ?>);
        $("#soft_doc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required:false,
        maxFileSize: 2000,
        allowedFileExtensions: ["pdf"]
        });

        var doc = parseInt(<?= strlen($doc) ? 1 : 0 ?>);
        $("#doc").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required:false,
        maxFileSize: 2000,
        allowedFileExtensions: ["pdf"]
        });

        var passport_pic = parseInt(<?= strlen($passport_pic) ? 1 : 0 ?>);
        $("#passport_pic").fileinput({
        dropZoneEnabled: false,
        showUpload: false,
        showRemove: false,
        required:false,
        maxFileSize: 1024,
        allowedFileExtensions: ["jpg", "jpeg", "png"]
        });


        $(document).on("click", "#send_mobile_otp", function(){
            let contactNo = $("#contact_number").val();
            if (/^\d{10}$/.test(contactNo)) {
                $("#otp_no").val("");
                $("#otpModal").modal("show");
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?=base_url('spservices/minoritycertificates/otps/send_otp')?>",
                    data: {"mobile_number": contactNo},
                    beforeSend: function () {
                        $("#otp_no").attr("placeholder", "Sending OTP... Please wait");
                    },
                    success: function (res) {
                        if(res.status) {
                            $(".verify_btn").attr("id", "verify_mobile_otp");
                            $("#otp_no").attr("placeholder", "Enter your OTP");
                        } else {
                            alert(res.msg);
                        }//End of if else
                    }
                });
            } else {
                alert("Contact number is invalid. Please enter a valid number");
                $("#contact_number").val();
                $("#contact_number").focus();
                return false;
            }//End of if else
        });//End of onclick #send_mobile_otp

        
        $(document).on("click", "#verify_mobile_otp", function(){
            let contactNo = $("#contact_number").val();
            var otpNo = $("#otp_no").val();
            if (/^\d{6}$/.test(otpNo)) {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?=base_url('spservices/minoritycertificates/otps/verify_otp')?>",
                    data: {"mobile_number":contactNo, "otp": otpNo},
                    beforeSend:function(){
                        $("#otp_no").val("");
                        $("#otp_no").attr("placeholder", "Verifying OTP... Please wait");
                    },
                    success:function(res){ //alert(JSON.stringify(res));
                        if(res.status) {
                            $("#otpModal").modal("hide");
                            $("#mobile_verify_status").val(1);
                            $("#contact_number").prop("readonly", true);
                            $("#send_mobile_otp").addClass('d-none');
                            $("#verified").removeClass('d-none');
                        } else {
                            alert(res.msg);
                            $("#otp_no").attr("placeholder", "ENTER THE 6-DIGIT OTP");
                        }//End of if else
                    }
                });
            } else {
                alert("OTP is invalid. Please enter a valid otp");
                $("#otp_no").val();
                $("#otp_no").focus();
            }//End of if else
        });//End of onClick #verify_mobile_otp
                
        $(".dp").datepicker({
            format: 'yyyy-mm-dd',
            endDate: '+0d',
            autoclose: true
        });
                
        var getDistricts = function(slc) {
            //alert(slc);
            if(slc.length) {
                $.ajax({
                    type: "POST",
                    url: "<?=base_url('spservices/trade_permit/Application/get_districts')?>",
                    data: {
                        "field_name":"ca_district", 
                        "field_value": slc
                    },
                    beforeSend:function(){
                        $("#ca_district").html("Loading");
                    },
                    success:function(res){
                        $("#ca_district").html(res);
                    }
                });
            } else {
                alert("Please select a state");
            }//End of if else
        };//End of if getDistricts()

        $(document).on("change", "#ca_state", function(){           
            //let selectedVal = $(this).val();
            let selectedVal = $(this).find('option:selected').attr('state_id');
            getDistricts(selectedVal);
            $("#ca_district").val($(this).find("option:selected").text());
        });

        $(document).on("change", "#ca_district", function(){           
            let selectedVal = $(this).val();
            let district_id = $(this).find('option:selected').attr("data-district_id_ca");
            $("#district_id_ca").val(district_id);
        });

        $(document).on("change", "#pa_circle", function(){           
            let selectedVal = $(this).val();
            let circle_id = $(this).find('option:selected').attr("circle_id");
            $("#circle_id").val(circle_id);
        });

        $(document).on("change", ".address_same", function(){                
            let checkedVal = $(this).val(); //alert(checkedVal);
            if(checkedVal === "Y") {
                $("#ca_address_line_1").val($("#pa_address_line_1").val());
                $("#ca_address_line_2").val($("#pa_address_line_2").val());
                $("#ca_address_line_3").val($("#pa_address_line_3").val());
                $("#ca_post_office").val($("#pa_post_office").val());
                $("#ca_pin_code").val($("#pa_pin_code").val());
                $("#ca_state_div_2").hide();
                $("#ca_state_div_1").show();
                $("#ca_state").val($("#pa_state").val());
                $("#ca_district_div_2").hide();
                $("#ca_district_div_1").show();
                $("#ca_district_1").val("Dima Hasao");


                //$("#ca_state_div").html('<select name="ca_state" class="form-control"><option value="'+$("#pa_state").val()+'">'+$("#pa_state").val()+'</option></select>');
                //$("#ca_district_div").html('<select name="ca_district" class="form-control"><option value="'+$("#pa_district").val()+'">'+$("#pa_district").val()+'</option></select>');
                
                $("#ca_police_station").val($("#pa_police_station").val());
                $("#ca_village").val($("#pa_village").val());

            } else {
                $("#ca_address_line_1").val("");
                $("#ca_address_line_2").val("");
                $("#ca_address_line_3").val("");
                $("#ca_post_office").val("");
                $("#ca_pin_code").val("");
                //$("#ca_state").val("");
                $("#ca_state_div_1").hide();
                $("#ca_state_div_2").show();
                $("#ca_district_div_1").hide();
                $("#ca_district_div_2").show();
                $("#ca_district").val("");
                $("#ca_police_station").val("");
                $("#ca_village").val("");
                
            }//End of if else
        });//End of onChange .address_same
        
       
        
        $(document).on("click", ".frmbtn", function(){
            var clickedBtn = $(this).attr("id");//alert(clickedBtn);
            if(clickedBtn === 'FORM_SUBMIT') {
                $("#form_status").val("DRAFT");
            } else if(clickedBtn === 'QUERY_SUBMIT') {
                $("#form_status").val("QS");
            } else {
                $("#form_status").val("");
            }//End of if else
            
            Swal.fire({
                title: 'Are you sure?',
                text: "Once you submitted, you will not be able to revert this action",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    $("#myfrm").submit();
                }
            });
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/trade_permit/Application/submit') ?>" enctype="multipart/form-data" onsubmit="$(this).find('select,radio,input').prop('disabled', false)">
            <input id="form_status" name="form_status" value="<?=$form_status?>" type="hidden" />
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="rtps_trans_id" value="<?=$rtps_trans_id?>" type="hidden" />
            <input id="form_status" name="form_status" value="<?=$form_status?>" type="hidden" />

            <input id="district_id" name="district_id" value="1027" type="hidden" />
            <input id="circle_id" name="circle_id" value="<?=$circle_id?>" type="hidden" />
            <input id="district_id_ca" name="district_id_ca" value="<?=$district_id_ca?>" type="hidden" />
            
            <input name="soft_doc_old" value="<?=$soft_doc?>" type="hidden" />
            <input name="passport_pic_old" value="<?=$passport_pic?>" type="hidden" />
            <input name="doc_old" value="<?=$doc?>" type="hidden" />
            
            <div class="card shadow-sm">
                <div class="card-header" style="text-align: center; font-size: 18px; color: #000; font-family: georgia,serif; font-weight: bold">
                    APPLICATION FORM FOR TRADING PERMIT TO CARRY ON BUSINESS FOR TRIBAL</br>
                    <font size="4px"></font>
                </div>

                <fieldset class="border border-success">
                            <legend class="h5">Important</legend>
                            <strong style="font-size:16px; ">Stipulated time limit for delivery</strong>
                            <ol style="margin-left: 24px; margin-top: 10px">
                                <li>The certificate will be delivered within 14 Days of application.</li>
                            </ol>

                            <strong style="font-size:16px;  margin-top: 10px">Fees/Charges :</strong>
                            <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>Service charge (PFC/ CSC) – Rs. 30</li>
                            <li>Printing charge (in case of any printing from PFC)- Rs. 10 Per Page</li>
                            <li>Scanning charge (in case documents are scanned in PFC)- Rs. 5 Per page</li>
                            </ul>

                            <strong style="font-size:16px;  margin-top: 10px">General Instruction</strong>
                            <ul style="  margin-left: 24px; margin-top: 10px">
                                <li>1. All the * marked fields are mandatory and need to be filled up.</li>
                            </ul>

                    </fieldset>

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
                    if($form_status === "QS") { ?>
                        <fieldset class="border border-danger" style="margin-top:40px">
                            <legend class="h5"><?=$this->lang->line('query_details')?></legend>
                            <div class="row">
                                <div class="col-md-12" style="font-size:16px; margin-bottom: 10px; text-align: justify">
                                    <p>The following query is made by <strong><?=$queried_by?></strong> on <strong><?=$queried_time?></strong></p>
                                    <?=$query_asked?>
                                </div>
                            </div>
                        </fieldset>
                    <?php }//End of if ?>
                                
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant Details<font size="3px"></font></legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>First Name <font size="2px"></font> <span class="text-danger">*</span> </label>
                                <input class="form-control" name="first_name" id="first_name" value="<?=$first_name?>" maxlength="255" type="text" required="true"/ <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>>
                                <?= form_error("first_name") ?>
                            </div>
                             <div class="col-md-6 form-group">
                                <label>Last Name <font size="2px"></font> <span class="text-danger">*</span> </label>
                                <input class="form-control" name="last_name" id="last_name" value="<?=$last_name?>" maxlength="255" type="text" required="true"/ <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>>
                                <?= form_error("last_name") ?>
                            </div>
                        </div>
                        <div class="row">  
                            <div class="col-md-6 form-group">
                                <label>Father's Name/Husband Name<font size="2px"></font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="father_name" id="father_name" value="<?=$father_name?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label for="contact_number">Mobile Number<font size="2px"></font><span class="text-danger">*</span>
                                    <small class="text-info pull-right">(Please verify the contact number using OTP)</small> 
                                </label>
                                <div class="input-group">
                                    <input class="form-control" name="contact_number" id="contact_number" maxlength="10" value="<?=$contact_number?>" <?=($mobile_verify_status == 1)?'readonly':''?> type="text" />
                                    <div class="input-group-append">
                                        <a href="javascript:void(0)" class="btn btn-outline-danger <?=($mobile_verify_status == 1)?'d-none':''?>" id="send_mobile_otp">Verify</a>
                                        <a href="javascript:void(0)" class="btn btn-outline-success <?=($mobile_verify_status == 1)?'':'d-none'?>" id="verified"><i class="fa fa-check"></i></a>
                                    </div>
                                </div>                                
                            </div>
                            <?= form_error("contact_number") ?>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Email</label>
                                <input class="form-control" name="emailid" id="emailid" value="<?=$emailid?>" maxlength="100" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("emailid") ?>
                            </div>  
                            <div class="col-md-6 form-group">
                                <label>Age<font size="2px"></font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="age" id="age" value="<?=$age?>" maxlength="3" minlength="1" type="number" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                 <?= form_error("age") ?>
                            </div>

                        </div>  
                        
                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Gender <font size="2px"></font><span class="text-danger">*</span> </label>
                                <select name="applicant_gender" id="applicant_gender"class="form-control" <?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="" >Please Select</option>
                                    <option value="Male" <?=($applicant_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($applicant_gender === "Female")?'selected':''?>>Female</option>
                                    <option value="Others" <?=($applicant_gender === "Others")?'selected':''?>>Others</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>                        
                            <div class="col-md-6 form-group">
                                <label>Profession <font size="2px"></font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="profession" id="profession" value="<?=$profession?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                 <?= form_error("profession") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Nationality <font size="2px"></font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="nationality" id="nationality" value="<?=$nationality?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                 <?= form_error("nationality") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Community<font size="2px"></font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="community" id="community" value="<?=$community?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                 <?= form_error("community") ?>
                            </div>
                        </div>
                    </fieldset>                    
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address<font size="3px"></font></legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Address Line 1<font size="2px" class="text-danger">*</font></label>
                                <input class="form-control" name="pa_address_line_1" id="pa_address_line_1" value="<?=$pa_address_line_1?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("pa_address_line_1") ?>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Address Line 2<font size="2px"></font><span class="text-danger"></span> </label>
                                <input class="form-control" name="pa_address_line_2" id="pa_address_line_2" value="<?=$pa_address_line_2?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("pa_address_line_2") ?>
                            </div>
                        </div> 
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Address Line 3<font size="2px"></font><span class="text-danger"></span> </label>
                                <input class="form-control" name="pa_address_line_3" id="pa_address_line_3" value="<?=$pa_address_line_3?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("pa_address_line_3") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Post Office<font size="2px"></font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_post_office" id="pa_post_office" value="<?=$pa_post_office?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("pa_post_office") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Postal / Zip Code<font size="2px"></font><span class="text-danger">*</span> </label>
                                <input class="form-control number_input" name="pa_pin_code" id="pa_pin_code" value="<?=$pa_pin_code?>" maxlength="6" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("pa_pin_code") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Country<span class="text-danger">*</span> </label>
                                <select name="pa_country" id="pa_country" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="India">India</option>
                                </select>
                                <?= form_error("pa_country") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>State<font size="2px"></font><span class="text-danger">*</span> </label>
                                <select name="pa_state" id="pa_state" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="Assam">Assam</option>
                                </select>
                                <?= form_error("pa_state") ?>
                            </div>
                               <div class="col-md-6 form-group">
                                <label>District<font size="2px"></font><span class="text-danger">*</span> </label>
                                <select name="pa_district" id="pa_district" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="Dima Hasao">Dima Hasao</option>
                                </select>
                                <?= form_error("pa_district") ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Revenue Circle<font size="2px"></font><span class="text-danger">*</span> </label>
                                <div id="pa_circles_div">
                                <select name="pa_circle" id="pa_circle" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option>Select</option>
                                    <?php if($circles) {
                                        foreach($circles as $circle) {
                                            $selectedCircle = ($pa_circle === $circle->circle_name)?'selected':'';
                                            echo '<option value="'.$circle->circle_name.'" '.'circle_id="'.$circle->circle_id.'"'.$selectedCircle.'>'.$circle->circle_name.'</option>';
                                        }//End of foreach()
                                    }//End of if ?>
                                </select>
                                </div>
                                <?= form_error("pa_circle") ?>
                            </div>
                              <div class="col-md-6 form-group">
                                <label>Village<font size="2px"></font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_village" id="pa_village" value="<?=$pa_village?>" maxlength="100" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("pa_village") ?>
                            </div>

                        </div>
                        <div class="row">                                
                            <div class="col-md-6 form-group">
                                <label>Police Station<font size="2px"></font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_police_station" id="pa_police_station" value="<?=$pa_police_station?>" maxlength="100" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("pa_police_station") ?>
                            </div>
                        </div>
                    </fieldset>             
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Current Address <font size="3px"></font></legend>
                           <div class="row mt-2 mb-4">
                            <div class="col-md-6">
                                <label for="address_same">Same as Permanent Address ?<span class="text-danger">*</span> </label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input address_same" type="radio" name="address_same" id="dcsYes" value="Y" <?=($address_same === 'Y')?'checked':''?> <?php if($form_status === "QS"){?>disabled <?php }//End of if ?>/>
                                    <label class="form-check-label" for="dcsYes">YES</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input address_same" type="radio" name="address_same" id="dcsNo" value="N" <?=($address_same === 'N')?'checked':''?> <?php if($form_status === "QS"){?>disabled <?php }//End of if ?>/>
                                    <label class="form-check-label" for="dcsNo">NO</label>
                                </div>
                            </div>
                        </div>
                        

                         <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Address Line 1<font size="2px" class="text-danger">*</font></label>
                                <input class="form-control" name="ca_address_line_1" id="ca_address_line_1" value="<?=$ca_address_line_1?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("ca_address_line_1") ?>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Address Line 2<font size="2px"></font><span class="text-danger"></span> </label>
                                <input class="form-control" name="ca_address_line_2" id="ca_address_line_2" value="<?=$ca_address_line_2?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("ca_address_line_2") ?>
                            </div>
                        </div> 
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Address Line 3<font size="2px"></font><span class="text-danger"></span> </label>
                                <input class="form-control" name="ca_address_line_3" id="ca_address_line_3" value="<?=$ca_address_line_3?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("ca_address_line_3") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Post Office<font size="2px"></font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_post_office" id="ca_post_office" value="<?=$ca_post_office?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("ca_post_office") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Postal / Zip Code<font size="2px"></font><span class="text-danger">*</span> </label>
                                <input class="form-control number_input" name="ca_pin_code" id="ca_pin_code" value="<?=$ca_pin_code?>" maxlength="6" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("ca_pin_code") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Country<span class="text-danger">*</span> </label>
                                <select name="ca_country" id="ca_country" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="India">India</option>
                                </select>
                                <?= form_error("ca_country") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group" >
                                <label>State<font size="2px"></font><span class="text-danger">*</span> </label>
                                
                                <div id="ca_state_div_1">
                                <input class="form-control" name="ca_state" id="ca_state" value="<?=$ca_state?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                </div>

                                <div id="ca_state_div_2">
                                 <select name="ca_state" id="ca_state" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                 <option>Select</option>
                                 <?php if($states) {
                                        foreach($states as $state) {
                                            $selectedState = ($ca_state === $state->state_name_english)?'selected':'';
                                            echo '<option value="'.$state->state_name_english.'" '.'state_id="'.$state->state_code.'"'.$selectedState.'>'.$state->state_name_english.'</option>';
                                        }//End of foreach()
                                    }//End of if ?>
                                    </select>
                                   </div> 
                                <?= form_error("ca_state") ?>
                            </div>
                               <div class="col-md-6 form-group">
                                <label>District<font size="2px"></font><span class="text-danger">*</span> </label>
                                
                                <div id="ca_district_div_2">
                                 <select name="ca_district" id="ca_district" class="form-control ca_dists"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="<?=$ca_district?>"><?=strlen($ca_district)?$ca_district:'Select'?></option>
                                </select>
                            </div>

                                <div id="ca_district_div_1">
                                <input class="form-control" name="ca_district" id="ca_district_1" value="<?=$ca_district?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                </div>

                                
                                <?= form_error("ca_district") ?>
                            </div>
                        </div>

                        <div class="row">
                              <div class="col-md-6 form-group">
                                <label>Village<font size="2px"></font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_village" id="ca_village" value="<?=$ca_village?>" maxlength="100" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("ca_village") ?>
                            </div>
                             <div class="col-md-6 form-group">
                                <label>Police Station<font size="2px"></font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_police_station" id="ca_police_station" value="<?=$ca_police_station?>" maxlength="100" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("ca_police_station") ?>
                            </div>

                        </div>
                        <div class="row">                                
                           
                        </div>
                    </fieldset>     

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5"> Other Details <font size="2px"></font> </legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Name of Market Place <font size="2px"></font><span class="text-danger">*</span> </label>
                               <input class="form-control" name="market_place" id="market_place" value="<?=$market_place?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("market_place") ?>
                            </div> 
                            
                            <div class="col-md-6 form-group">
                                <label>Commodities to be dealt<font size="2px"></font><span class="text-danger">*</span> </label>
                                <input class="form-control" name="commodities" id="commodities" value="<?=$commodities?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("commodities") ?>
                            </div>
                        </div>
                        
                        <div class="row">  
                            <div class="col-md-6 form-group">
                                <label>If any other working unit in N.C. Hills Dist., Please state the location<font size="2px"></font><span class="text-danger">*</span> </label>
                               <input class="form-control" name="location" id="location" value="<?=$location?>" maxlength="255" type="text" <?php if($form_status === "QS"){?>readonly <?php }//End of if ?>/>
                                <?= form_error("location") ?>
                            </div> 

                            <div class="col-md-6 form-group">
                                <label>Are you an employee of Govt./Semi Govt./Govt. Undertaking</font><span class="text-danger">*</span> </label>
                                <select name="govt_emp" id="govt_emp" class="form-control" <?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option value="">Please Select</option>
                                    <option value="Yes" <?=($govt_emp === "Yes")?'selected':''?>>Yes</option>
                                    <option value="No" <?=($govt_emp === "No")?'selected':''?>>No</option>
                                </select>
                                <?= form_error("govt_emp") ?>
                            </div>
                        </div>


                         <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Trading Area<font size="2px"></font><span class="text-danger">*</span> </label>
                                 <select name="trade_area" id="trade_area" class="form-control"<?php if($form_status === "QS"){?>disabled <?php }//End of if ?>>
                                    <option>Select</option>
                                     <?php if($tareas) {
                                        foreach($tareas as $area) {
                                            $selectedArea = ($trade_area === $area->trade_area_name)?'selected':'';
                                            echo '<option value="'.$area->trade_area_name.'" '.$selectedArea.'>'.$area->trade_area_name.'</option>';
                                        }//End of foreach()
                                    }//End of if ?>
                                </select>
                                <?= form_error("trade_area") ?>
                            </div>
                        </div>
                    </fieldset>                    
                                   
                    <fieldset class="border border-success" style="margin-top:10px">
                        <legend class="h5"><?=$this->lang->line('attach_enclosure')?> </legend>
                        <div class="row mt-0">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td colspan="3" style="color:#fd7e14; text-align: center; font-size: 14px">
                                                List of Mandatory documents, Document type allowed is pdf of maximum size 1MB;
                                                For Passport size photo only jpg, jpeg, and png of maximum 200KB is allowed
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Type of Enclosure</th>
                                            <th style="width:30%">Enclosure Document</th>
                                            <th style="width:25%">File/Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($this->slug !== 'user') { ?>
                                        <tr>
                                            <td>Upload hard copy of the Application Form <font>( ইউজাৰ ফৰ্মখন সংলগ্ন কৰা)</font><span class="text-danger">*</span></td>
                                            <td>
                                                <select name="soft_doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Soft Copy" <?=$soft_doc_type==='Soft Copy'?'selected':''?>>Upload soft copy of the User Form</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input id="soft_doc" name="soft_doc" type="file" />
                                                </div>
                                                <?php if(strlen($soft_doc)){ ?>
                                                    <a href="<?=base_url($soft_doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                            </td>
                                        </tr>
                                        <?php }//End of if ?> 
                                       <tr>
                                            <td>Documents<font></font><span class="text-danger">*</span></td>
                                            <td>
                                                <select name="doc_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Land Documents" <?=$doc_type==='Land Documents'?'selected':''?> >Land Documents</option>
                                                    <option value="PRC"<?=$doc_type==='PRC'?'selected':''?>>Permanent Residence Certificate</option>
                                                </select>

                                            </td>
                                            <td>
                                                <div class="">
                                                    <input id="doc" name="doc" type="file" />
                                                     <?php if(strlen($doc)){ ?>
                                                    <a href="<?=base_url($doc)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Passport size photograph<span class="text-danger">*</span></td>
                                            <td>
                                                <select name="passport_pic_type" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Passport size photograph" <?=$passport_pic_type==='Passport size photograph'?'selected':''?>>Passport size photograph</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="">
                                                    <input id="passport_pic" name="passport_pic" type="file" />
                                                     <?php if(strlen($passport_pic)){ ?>
                                                    <a href="<?=base_url($passport_pic)?>" class="btn font-weight-bold text-success" target="_blank">
                                                        <span class="fa fa-download"></span> 
                                                        View/Download
                                                    </a>
                                                <?php }//End of if ?>
                                                
                                                </div>
                                            </td>
                                        </tr>
                                        <?php if($form_status === "QS") { ?>
                                      
                                        <tr>
                                            <td colspan="3">
                                            <label>Please Enter your Remarks(if any) </label>
                                            <textarea class="form-control" name="query_answered"><?=$query_answered?></textarea>
                                            <?= form_error("query_answered") ?>
                                            </tr>
                                        <?php }//End of if ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </fieldset> 
                </div><!--End of .card-body -->

                
                                        

                <div class="card-footer text-center">
                     <?php if($form_status === 'QS') { ?>
                        <button class="btn btn-success frmbtn" id="QUERY_SUBMIT" type="button">
                            <i class="fa fa-angle-double-right"></i>REPLY & NEXT</button>
                    <?php } else { ?>
                        <button class="btn btn-danger" type="reset">
                            <i class="fa fa-refresh"></i> RESET</button>
                        <button class="btn btn-primary frmbtn" id="FORM_SUBMIT" type="button">
                            <i class="fa fa-angle-double-right"></i>SAVE & NEXT</button>
                    <?php }//End of if ?>        
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>


<div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="otpModalLabel">
    <div class="modal-dialog modal-sm" role="document" style="margin:20% auto">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px 15px; font-weight: bold !important; font-size: 28px !important; text-align: center !important; display: block !important">
                <?=$this->lang->line('otp_verification')?>
            </div>
            <div class="modal-body print-content" id="otpview" style="padding: 5px 15px;">
                <div class="row">
                    <div class="col-md-12">
                        <input id="otp_no" class="form-control text-center" value="" maxlength="6" autocomplete="off" type="text" />
                    </div>
                </div> <!-- End of .row -->
            </div><!--End of .modal-body-->
            <div class="modal-footer" style="display: block !important; text-align: center !important">
                <button type="button" id="verify_btn" class="btn btn-success verify_btn">
                    VERIFY
                </button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">
                    CANCEL
                </button>
            </div><!--End of .modal-footer-->
        </div>
    </div>
</div><!--End of #otpModal-->

