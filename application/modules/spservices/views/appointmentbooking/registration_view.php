<?php
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    
    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $fathers_name = $dbrow->form_data->fathers_name;
    $mobile_number = $dbrow->form_data->mobile_number;
    $email_id = $dbrow->form_data->email_id;
    $applicant_address = $dbrow->form_data->applicant_address;
    $office_district = $dbrow->form_data->office_district;
    $district_name = $dbrow->form_data->district_name;
    $sro_code = $dbrow->form_data->sro_code;
    $office_name = $dbrow->form_data->office_name;
    $appointment_type = $dbrow->form_data->appointment_type;
    $at_id = $appointment_type->at_id;
    $appointment_date = $dbrow->form_data->appointment_date;
    $appointee_ref_no = $dbrow->form_data->appointee_ref_no;
    $appointee_name = $dbrow->form_data->appointee_name;
    $appointee_bride_name = $dbrow->form_data->appointee_bride_name;
    $appointee_groom_name = $dbrow->form_data->appointee_groom_name;
    $deed_type = $dbrow->form_data->deed_type;
    $status = $dbrow->service_data->appl_status??'';
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $fathers_name = set_value("fathers_name");
    $mobile_number = (strlen($this->session->mobile)==10)?$this->session->mobile:set_value("mobile_number");
    $email_id = set_value("email_id");
    $applicant_address = set_value("applicant_address");
    $office_district = set_value("office_district");
    $district_name = set_value("district_name");
    $sro_code = set_value("sro_code");
    $office_name = set_value("office_name");
    $appointment_type = set_value("appointment_type");
    if(strlen($appointment_type)) {
        $appointmentType = json_decode(html_entity_decode($appointment_type));
        $at_id = $appointmentType->at_id;
    } else {
        $at_id = '';
    }//End of if else
    $appointment_date = set_value("appointment_date");
    $appointee_ref_no = set_value("appointee_ref_no");
    $appointee_name = set_value("appointee_name");
    $appointee_bride_name = set_value("appointee_bride_name");
    $appointee_groom_name = set_value("appointee_groom_name");
    $deed_type = set_value("deed_type");
    $status = ''; 
}//End of if else
//die(" mt_id : ".$mt_id);
?>
<style type="text/css">
    legend {
        display: inline;
        width: auto;
    }
    li {
        font-size: 14px;
        line-height: 24px;
    }
</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script type="text/javascript">   
    $(document).ready(function () {
        var appointmentDt = '<?=$appointment_date?>';
        var sroCd = '<?=$sro_code?>';
        
        var getSros = function(parent_org_unit_code) {
            $.getJSON("<?=base_url('spservices/necertificate/getlocation/')?>"+parent_org_unit_code, function (data) {
                let selectOption = '';
                $('#sro_code').empty().append('<option value="">Select an office</option>');
                $.each(data, function (key, value) {
                    selectOption += '<option value="'+value.org_unit_code+'">'+value.org_unit_name+'</option>';
                });
                $('#sro_code').append(selectOption);
            });
        };//End of getSros()
        
        var getRefNos = function(sroCode) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/appointmentbooking/registration/get_refnos')?>",
                data: {"sro_code": sroCode},
                beforeSend: function () {
                    $("#appointee_ref_no_div").html('<select name="appointee_ref_no" class="form-control"><option value="">Loading...</option></select>');
                },
                success: function (res) {
                    $("#appointee_ref_no_div").html(res);
                }
            });
        };//End of getRefNos()
        
        var getAppointmentDates = function(appointment_type) {
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/appointmentbooking/registration/get_holidays')?>",
                data: {"appointment_type": appointment_type},
                beforeSend: function () {
                    $("#appointment_date_div").html('<select name="appointment_date" class="form-control"><option value="">Loading working days...</option></select>');
                },
                success: function (res) {
                    $("#appointment_date_div").html(res);
                }
            });
        };//End of getAppointmentDates()
                               
        $(document).on("change", "#appointment_type", function(){            
            let appointmentType = $(this).val(); 
            let sroCode = $("#sro_code").val(); //alert(appointmentType+" : "+sroCode);
            if(sroCode.length === 0) {
                $("#deed_div").hide();
                $("#marriage_div").hide();
                alert("Please select an Office");
                $("#sro_code").focus();
            } else if(appointmentType.length === 0) {
                $("#deed_div").hide();
                $("#marriage_div").hide();
                alert("Please select a type");
                $(this).focus();
            } else {
                var appointment_type = $.parseJSON(appointmentType);
                var atId = parseInt(appointment_type.at_id); //alert(mtId);  
                getAppointmentDates(atId);
                if(atId === 1) {
                    $("#deed_div").show();
                    $("#marriage_div").hide();
                }else if(atId === 2) {
                    $("#deed_div").hide();
                    $("#marriage_div").show();
                    getRefNos(sroCode);
                } else {
                    $("#deed_div").hide();
                    $("#marriage_div").hide();
                }
            }
                
        });
        
        $(document).on("change", "#office_district", function(){               
            let selectedVal = $(this).val();
            if(selectedVal.length) {
                var distName = $(this).find("option:selected").text(); 
                var distNameArr = distName.split(' DISTRICT - ');
                var district_name = distNameArr[1].slice(0,-1); //alert(district_name);
                $("#district_name").val(district_name);
                getSros(selectedVal);
            }
        });
        
        $(document).on("change", "#sro_code", function(){
            $("#office_name").val($(this).find("option:selected").text());
        });
        
        $(document).on("change", "#appointee_ref_no", function(){
            let sroCode = $("#sro_code").val();
            let appointee_ref_no = $("#appointee_ref_no").val(); //alert(appointee_ref_no);
            if(appointee_ref_no.length === 0) {
                alert("Please select a Ref. no.");
                $("#appointee_ref_no").focus();
            } else {
                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?=base_url('spservices/appointmentbooking/registration/get_mrgdetails')?>",
                    data: {"sro_code": sroCode, "application_ref_no": appointee_ref_no},
                    beforeSend: function () {},
                    success: function (res) {
                        if(res.status) {
                            $("#appointee_name").val(res.records[0].appointee_name);
                            $("#appointee_bride_name").val(res.records[0].appointee_bride_name);
                            $("#appointee_groom_name").val(res.records[0].appointee_groom_name);
                        }
                    }
                });
            }//End of if else
        });
        
        $(document).on("click", ".frmbtn", function(){ 
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE_NEXT') {
                var msg = "Once you submitted, you won't able to revert this";
            } else if(clickedBtn === 'CLEAR') {
                var msg = "Once you Reset, All filled data will be cleared";
            } else {
                var msg = "";
            }//End of if else            
            Swal.fire({
                title: 'Are you sure?',
                text: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) { //alert(clickedBtn+" : "+result.value);
                    if((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE_NEXT')) {
                        $("#myfrm").submit();
                    } else if(clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {}//End of if else
                }
            });
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/appointmentbooking/registration/submit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input id="district_name" name="district_name" value="<?=$district_name?>" type="hidden" />
            <input id="office_name" name="office_name" value="<?=$office_name?>" type="hidden" />
            <input id="submit_mode" name="submit_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                       <?=$service_name?> 
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
                        <legend class="h5">Guidelines for Filling Up the Form/প্ৰপত্ৰ ভৰ্তিকৰণৰ নিৰ্দেশনা </legend>
                        
                        <strong style="font-size:16px;  margin-top: 30px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                        <ul style="margin-left: 10px;  margin-bottom: 30px">
                            <li>1. All the <span class="text-danger">*</span> marked fileds are mandatory and need to be filled up.</li>
                            <li>১.  <span class="text-danger">*</span> চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব।</li>
                            <li style="height:20px"></li>
                            
                            <li>2. Applicant can take apppontment for marriage after applying for Marriage Registration.</li>
                            <li>২. আবেদনকাৰীয়ে বিবাহ পঞ্জীয়নৰ বাবে আবেদন কৰাৰ পিছত বিবাহৰ বাবে এপইণ্টমেণ্ট ল’ব পাৰিব |</li>
                            <li style="height:20px"></li>
                        </ul>
                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ</legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Name of the Applicant/আবেদনকাৰীৰ নাম <span class="text-danger">*</span> </label>
                                <input class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255" type="text" />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Gender / লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($applicant_gender == 'Male')?'selected':''?>>Male</option>
                                    <option value="Female" <?=($applicant_gender == 'Female')?'selected':''?>>Female</option>
                                    <option value="Others" <?=($applicant_gender == 'Others')?'selected':''?>>Others</option>
                                </select>
                                <?=form_error('applicant_gender')?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Father's Name/পিতাৰ নাম <span class="text-danger">*</span> </label>
                                <input class="form-control" name="fathers_name" id="fathers_name" value="<?=$fathers_name?>" maxlength="255" type="text" />
                                <?= form_error("fathers_name") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input name="mobile_number" value="<?=$mobile_number?>" maxlength="10" <?=($user_type == "user")?'readonly':''?> class="form-control" type="text" />
                                <?= form_error("mobile_number") ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>E-Mail / ই-মেইল </label>
                                <input class="form-control" name="email_id" value="<?=$email_id?>" maxlength="100" type="text" />
                                <?= form_error("email_id") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Address of the Applicant/আবেদনকাৰীৰ ঠিকনা <span class="text-danger">*</span></label>
                                <input class="form-control" name="applicant_address" value="<?=$applicant_address?>" maxlength="255" type="text" />
                                <?= form_error("applicant_address") ?>
                            </div>
                        </div>

                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applied To/আবেদন কৰা কাৰ্য্যালয়</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Select District / জিলা নিৰ্বাচন কৰক<span class="text-danger">*</span> </label>
                                <select name="office_district" id="office_district" class="form-control">
                                    <option value="">Select</option>
                                    <?php if($sro_dist_list){
                                        foreach($sro_dist_list as $item){ ?>
                                            <option value="<?=$item->parent_org_unit_code?>" <?= ($office_district == $item->parent_org_unit_code) ? "selected":"" ?>><?=$item->org_unit_name_2?></option>
                                        <?php }
                                    } ?>
                                </select>
                                <?= form_error("office_district") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Select office/কাৰ্য্যালয় নিৰ্বাচন কৰক<span class="text-danger">*</span> </label>
                                <select name="sro_code" id="sro_code" class="form-control">
                                    <option value="<?=$sro_code?>"><?=(strlen($sro_code))?$office_name:'Select'?></option>
                                </select>
                                <?= form_error("sro_code") ?>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset id="already_performed_marriage" class="border border-success">
                        <legend class="h5">Appointment details/নিযুক্তিৰ বিৱৰণ</legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Appointment Type/ নিযুক্তিৰ ধৰণ <span class="text-danger">*</span> </label>
                                <select name="appointment_type" id="appointment_type" class="form-control">
                                    <option value="">Select</option>                                    
                                    <option value='<?=json_encode(array("at_id"=>1, "at_name" => "Deed"))?>' <?=($at_id == 1)?'selected':''?>>Deed</option>
                                    <option value='<?=json_encode(array("at_id"=>2, "at_name" => "Marriage"))?>' <?=($at_id == 2)?'selected':''?>>Marriage</option>
                                </select>
                                <?= form_error("appointment_type") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Date of appointment/নিযুক্তিৰ সময় <span class="text-danger">*</span> </label>
                                <span id="appointment_date_div">
                                    <select name="appointment_date" id="appointment_date" class="form-control">
                                        <option value="<?=$appointment_date?>"><?=(strlen($appointment_date)==10)?date('d-m-Y', strtotime($appointment_date)):'Select'?></option>
                                    </select>
                                </span>
                                <?= form_error("appointment_date") ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset id="deed_div" class="border border-success" style="display: <?=($at_id == 1)?'block':'none'?>">
                        <legend class="h5">Deed Details/দলিলৰ বিৱৰণ </legend>                        
                        <div class="row form-group">                            
                            <div class="col-md-12">
                                <label>Deed type/দলিলৰ ধৰণ </label>
                                <input name="deed_type" id="deed_type" value="<?=$deed_type?>" class="form-control" type="text" autocomplete="off" />
                                <?= form_error("deed_type") ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset id="marriage_div" class="border border-success" style="display: <?=($at_id == 2)?'block':'none'?>">
                        <legend class="h5">Marriage Details/বিবাহৰ বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Application Ref No/দৰ্খাস্তৰ নং <span class="text-danger">*</span> </label>
                                <span id="appointee_ref_no_div">
                                    <select name="appointee_ref_no" id="appointee_ref_no" class="form-control">
                                        <option value="<?=$appointee_ref_no?>"><?=(strlen($appointee_ref_no))?$appointee_ref_no:'Select'?></option>
                                    </select>
                                </span>
                                <?= form_error("appointee_ref_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant Name/আবেদনকাৰীৰ নাম <span class="text-danger">*</span> </label>
                                <input name="appointee_name" id="appointee_name" value="<?=$appointee_name?>" class="form-control" type="text" autocomplete="off" />
                                <?= form_error("appointee_name") ?>
                            </div>
                        </div>
                        
                        <div class="row form-group">                            
                            <div class="col-md-6">
                                <label>Bride Name/কইনাৰ নাম </label>
                                <input name="appointee_bride_name" id="appointee_bride_name" value="<?=$appointee_bride_name?>" class="form-control" type="text" autocomplete="off" />
                                <?= form_error("appointee_bride_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Groom Name/দৰাৰ নাম </label>
                                <input name="appointee_groom_name" id="appointee_groom_name" value="<?=$appointee_groom_name?>" class="form-control" type="text" autocomplete="off" />
                                <?= form_error("appointee_groom_name") ?>
                            </div>
                        </div>
                    </fieldset>
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE_NEXT" type="button">
                        <i class="fa fa-angle-double-right"></i> Save &amp; Next
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>