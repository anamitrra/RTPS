<?php
if($dbrow) {
    $service_id = $dbrow->service_data->service_id;
    $service_name = $dbrow->service_data->service_name;
    // $date_of_application = $dbrow->service_data->submission_date;
    $applicant_name =$dbrow->form_data->applicant_name;
    $gender = $dbrow->form_data->applicant_gender;
    $age =$dbrow->form_data->age;
  
    $mobile =$dbrow->form_data->mobile;
    $timeline = $dbrow->service_data->service_timeline;
    // $department_name = $dbrow->service_data->department_name;
    $amount = $dbrow->form_data->amount;
    
    $pa_house_no = $dbrow->form_data->pa_house_no;
    $pa_street = $dbrow->form_data->pa_street;
    $pa_village = $dbrow->form_data->pa_village;
    $pa_post_office = $dbrow->form_data->pa_post_office;
    $pa_pin_code = $dbrow->form_data->pa_pin_code;
    $pa_state = $dbrow->form_data->pa_state;
    $pa_district_id = $dbrow->form_data->pa_district_id;
    $pa_district_name = $dbrow->form_data->pa_district_name;
    $pa_circle = $dbrow->form_data->pa_circle;
    $pa_police_station = $dbrow->form_data->pa_police_station;
    
    $address_same = $dbrow->form_data->address_same;
    $ca_house_no = $dbrow->form_data->ca_house_no;
    $ca_street = $dbrow->form_data->ca_street;
    $ca_village = $dbrow->form_data->ca_village;
    $ca_post_office = $dbrow->form_data->ca_post_office;
    $ca_pin_code = $dbrow->form_data->ca_pin_code;
    $ca_state = $dbrow->form_data->ca_state;
    $ca_district_id = $dbrow->form_data->ca_district_id;
    $ca_district_name = $dbrow->form_data->ca_district_name;
    $ca_circle = $dbrow->form_data->ca_circle;
    $ca_police_station =$dbrow->form_data->ca_police_station;
    $office_code = $dbrow->form_data->office_code;
    $office_name = $dbrow->service_data->submission_location;
    $rtps_trans_id = $dbrow->form_data->rtps_trans_id;
    $obj_id = $dbrow->{'_id'}->{'$id'};
}else{
    $service_id = set_value("service_id");
    $service_name = set_value("service_name");
    $date_of_application = set_value("date_of_application");
    $applicant_name = set_value("applicant_name");
    $gender = set_value("gender");
    $age = set_value("age");
    $address = set_value("address");
    $mobile = !empty($user_mobile) ? $user_mobile : set_value("mobile");
    $timeline = set_value("timeline");
    $department_name = set_value("department_name");
    $amount = set_value("amount");
    
    $pa_house_no = set_value("pa_house_no");
    $pa_street = set_value("pa_street");
    $pa_village = set_value("pa_village");
    $pa_post_office = set_value("pa_post_office");
    $pa_pin_code = set_value("pa_pin_code");
    $pa_state = set_value("pa_state");
    $pa_district_id = set_value("pa_district_id");
    $pa_district_name = set_value("pa_district_name");
    $pa_circle = set_value("pa_circle");
    $pa_police_station = set_value("pa_police_station");
    
    $address_same = set_value("address_same");
    $ca_house_no = set_value("ca_house_no");
    $ca_street = set_value("ca_street");
    $ca_village = set_value("ca_village");
    $ca_post_office = set_value("ca_post_office");
    $ca_pin_code = set_value("ca_pin_code");
    $ca_state = set_value("ca_state");
    $ca_district_id = set_value("ca_district_id");
    $ca_district_name = set_value("ca_district_name");
    $ca_circle = set_value("ca_circle");
    $ca_police_station = set_value("ca_police_station");
    $office_code = set_value("office_code");
    $office_name = set_value("office_name");
    $rtps_trans_id = NULL;//set_value("rtps_trans_id");
    $obj_id=NULL;
}


?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script type="text/javascript">   
    $(document).ready(function () {

$(document).on("change", "#pa_district_id", function(){
            let district_id = $(this).val();
            $("#pa_district_name").val($(this).find("option:selected").text());
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/minoritycertificates/registration/get_circles')?>",
                data: {"input_name":"pa_circle", "fld_name": "district_id", "fld_value":district_id},
                beforeSend:function(){
                    $("#pa_circles_div").html("Loading");
                },
                success:function(res){
                    $("#pa_circles_div").html(res);
                }
            });
        });
        
        $(document).on("change", "#ca_district_id", function(){
            let district_id = $(this).val();
            $("#ca_district_name").val($(this).find("option:selected").text());
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/minoritycertificates/registration/get_circles')?>",
                data: {"input_name":"ca_circle", "fld_name": "district_id", "fld_value":district_id},
                beforeSend:function(){
                    $("#ca_circles_div").html("Loading");
                },
                success:function(res){
                    $("#ca_circles_div").html(res);
                }
            });
        });

        $(document).on("change", "#service_id", function(){
            $("#service_name").val($(this).find("option:selected").text());
            $("#timeline").val($(this).find(":selected").attr("data-timeline"))
            let service_code = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?=base_url('spservices/offline/acknowledgement/get_offices')?>",
                data: {"service_code":service_code},
                beforeSend:function(){
                    $("#div_offices").html("Loading");
                },
                success:function(res){
                    $("#div_offices").html(res);
                }
            });

        
        });  

        $(document).on("change", "#office_code", function(){
            $("#office_name").val($(this).find("option:selected").text());
        
        });

        $(document).on("change", "#department_id", function(){
            $("#department_name").val($(this).find("option:selected").text());
        
        });
        
        $(document).on("change", ".address_same", function(){                
            let checkedVal = $(this).val(); //alert(checkedVal);
            if(checkedVal === "YES") {
                $("#ca_house_no").val($("#pa_house_no").val());
                $("#ca_street").val($("#pa_street").val());
                $("#ca_village").val($("#pa_village").val());
                $("#ca_post_office").val($("#pa_post_office").val());
                $("#ca_pin_code").val($("#pa_pin_code").val());
                $("#ca_state").val($("#pa_state").val());
                $("#ca_district_id").val($("#pa_district_id").val());
                $("#ca_district_name").val($("#pa_district_name").val());
                $("#ca_circles_div").html('<select name="ca_circle" class="form-control"><option value="'+$("#pa_circle").val()+'">'+$("#pa_circle").val()+'</option></select>');
                $("#ca_police_station").val($("#pa_police_station").val());
            } else {
                $("#ca_house_no").val("");
                $("#ca_street").val("");
                $("#ca_village").val("");
                $("#ca_post_office").val("");
                $("#ca_pin_code").val("");
                $("#ca_state").val("");
                $("#ca_district_id").val("");
                $("#ca_district_name").val("");
                $("#ca_circle").val("");
                $("#ca_circles_div").html('<select name="ca_circle" class="form-control"><option value="">Select a circle</option></select>');
                $("#ca_police_station").val($("#pa_police_station").val());
            }//End of if else
        });//End of onChange .address_same


        $(document).on("click", ".frmbtn", function(){ 
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE') {
                var msg = "Do you want to procced";
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
                if (result.value) {
                    if((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
                        $("#myfrm").submit();
                    } else if(clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {}//End of if else
                }
            });
        });  


    })
</script>

<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/offline/acknowledgement/action') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="rtps_trans_id" value="<?=$rtps_trans_id?>" type="hidden" />
            <input id="submit_mode" name="submit_mode" value="" type="hidden" />
            <div class="card shadow-sm" >
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                Application for <?=isset($service_details) ? $service_details->service_name :""?><br>
                         
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
                    
                    <input id="pa_district_name" name="pa_district_name" value="<?=$pa_district_name?>" type="hidden" />
                            <input id="ca_district_name" name="ca_district_name" value="<?=$ca_district_name?>" type="hidden" />  
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details</legend>
                        
                    
                        
                        <div class="row">
                                
                                <input type="hidden" name="service_name" id="service_name" value="<?= !empty($service_details) ? $service_details->service_name : ''?>"/>
                                <input type="hidden" name="service_id" value="<?=isset($service_details) ? $service_details->service_code :""?>"/>
                                <input name="timeline" id="timeline" value="<?=isset($service_details) ? $service_details->timeline :""?>" class="form-control" type="hidden"/>
                                <div class="col-md-6 form-group">
                                    <label>Applicant Name<span class="text-danger">*</span></label>
                                    <input name="applicant_name" id="applicant_name" value="<?= $applicant_name ?>" class="form-control" type="text" />
                                    <?= form_error("applicant_name") ?>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="gender">Gender<span class="text-danger">*</span></label>
                                    <select name="gender" id="gender" class="form-control">
                                        <option value="">Select</option>
                                        <option <?= $gender==="Male" ? "selected":"" ?> value="Male">Male</option>
                                        <option <?= $gender==="Female" ? "selected":"" ?> value="Female">Female</option>
                                        <option <?= $gender==="Others" ? "selected":"" ?> value="Others">Others</option>
                                    </select>
                                    <?= form_error("gender") ?>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label>Age<span class="text-danger">*</span></label>
                                    <input name="age" id="age" value="<?= $age ?>" class="form-control" type="number" />
                                    <?= form_error("age") ?>
                                </div>
                              

                                <div class="col-md-6 form-group">
                                    <label>Mobile Number<span class="text-danger">*</span></label>
                                    <input <?= !empty($user_mobile) ? "readonly":""?> name="mobile" id="mobile" value="<?= $mobile ?>" class="form-control" type="text" />
                                    <?= form_error("mobile") ?>
                                </div>
                               

                                <!-- <div class="col-md-6 form-group">
                                    <label>Department Name<span class="text-danger">*</span></label>
                                    <select name="department_id" id="department_id" class="form-control" >
                                            <option value="">Select Department</option>
                                    <?php
                                    if(!empty($department_list)){
                                        foreach($department_list as $item){?>
                                            <option value="<?=$item->department_id ?>"><?=$item->department_name->en ?></option>
                                        <?php }    
                                    }
                                     ?>
                                    </select>
                                    <?= form_error("department_id") ?>
                                </div> -->
                                <input type="hidden" id="department_name" name="department_name"/>
                                <!-- <div class="col-md-6 form-group">
                                    <label>User Charges<span class="text-danger">*</span></label>
                                    <input name="amount" id="amount" value="<?= $amount ?>" class="form-control" type="text" />
                                    <?= form_error("amount") ?>
                                </div> -->
                            </div>
                    
                    </fieldset>


                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Office for application submission</legend>
                            <div class="row">
                              
                                <!-- <div class="col-md-6 form-group">
                                    <label>Service Name<span class="text-danger">*</span></label>
                                    <select name="service_id" id="service_id" class="form-control" >
                                        <option value="">Select</option>
                                        <?php if(!empty($service_list)){
                                            foreach ( $service_list as $ser){ ?>
                                                <option data-timeline="<?=$ser->timeline?>" value="<?=$ser->service_code?>" <?= ($service_id === $ser->service_code)?'selected':'' ?> ><?=$ser->service_name?></option>
                                                <?php }
                                        } ?>
                                    </select>
                                    <?= form_error("service_id") ?>
                                </div> -->
                                <!-- <div class="col-md-6 form-group">
                                    <label>Stipulated Timeline for service delivery(days)<span class="text-danger">*</span></label>
                                    <input name="timeline" id="timeline" value="<?= $timeline ?>" class="form-control" type="text" readonly/>
                                    <?= form_error("timeline") ?>
                                </div> -->
                                <!-- <div class="col-md-6 form-group">
                                    <label>Date of Application<span class="text-danger">*</span></label>
                                    <input name="date_of_application" id="date_of_application" value="<?= $date_of_application ?>" class="form-control" type="date" />
                                    <?= form_error("date_of_application") ?>
                                </div> -->

                                <!-- <div class="col-md-6 form-group">
                                    <label>Line Office<span class="text-danger">*</span> </label>
                                    <div id="div_offices"></div>
                                    <?= form_error("office_code") ?>
                                </div> -->
                                <input type="hidden" name="office_name" value="<?=$office_name?>" id="office_name"/>
                                <div class="col-md-6 form-group">
                                     <label>Select Office<span class="text-danger">*</span> </label>
                                    <!-- <div id="div_offices">
                                    </div> -->
                                    <select name="office_code" id="office_code" class="form-control">
                                        <?php if(!empty($office_list)){
                                            foreach($office_list as $office){?>
                                                    <option value="<?=$office['office_code']?>" <?=($office['office_code'] === $office_code)?'selected':'' ?>>
                                                    <?=$office['office_name'] ?>
                                                    </option>
                                        <?php  }
                                        }?>
                                    </select>
                                    <?= form_error("office_code") ?>
                                </div>


                            </div>
                         
                    
                    </fieldset>

                    
                    <fieldset  class="border border-success"  style="margin-top:40px">
                        <legend class="h5"><?=$this->lang->line('pa_address')?><span style="font-size:12px; color: #f31d12"></span></legend>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_house_no')?></label>
                                <input class="form-control" name="pa_house_no" id="pa_house_no" value="<?=$pa_house_no?>" maxlength="255" type="text" />
                                <?= form_error("pa_house_no") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_street')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_street" id="pa_street" value="<?=$pa_street?>" maxlength="255" type="text" />
                                <?= form_error("pa_street") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_village')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_village" id="pa_village" value="<?=$pa_village?>" maxlength="255" type="text" />
                                <?= form_error("pa_village") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_post_office')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_post_office" id="pa_post_office" value="<?=$pa_post_office?>" maxlength="255" type="text" />
                                <?= form_error("pa_post_office") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_pin_code')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_pin_code" id="pa_pin_code" value="<?=$pa_pin_code?>" maxlength="6" type="text" />
                                <?= form_error("pa_pin_code") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_state')?><span class="text-danger">*</span> </label>
                                <select name="pa_state" id="pa_state" class="form-control">
                                    <option value="Assam">Assam</option>
                                </select>
                                <?= form_error("pa_state") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_district')?><span class="text-danger">*</span> </label>
                                <select name="pa_district_id" id="pa_district_id" class="form-control">
                                    <option value="">Select a district</option>
                                    <?php if($districts) {
                                        foreach($districts as $dist) {
                                            $selectedDist = ($pa_district_name === $dist->district_name)?'selected':'';
                                            echo '<option value="'.$dist->district_id.'" '.$selectedDist.'>'.$dist->district_name.'</option>';
                                        }//End of foreach()
                                    }//End of if ?>
                                </select>
                                <?= form_error("pa_district_id") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_circle')?><span class="text-danger">*</span> </label>
                                <div id="pa_circles_div">
                                    <select name="pa_circle" id="pa_circle" class="form-control">
                                        <option value="<?=$pa_circle?>"><?=strlen($pa_circle)?$pa_circle:'Select'?></option>
                                    </select>
                                </div>
                                <?= form_error("pa_circle") ?>
                            </div>
                        </div>
                        <div class="row">                                
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('pa_police_station')?> <span class="text-danger">*</span> </label>
                                <input class="form-control" name="pa_police_station" id="pa_police_station" value="<?=$pa_police_station?>" maxlength="100" type="text" />
                                <?= form_error("pa_police_station") ?>
                            </div>
                        </div>
                    </fieldset>             
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5"><?=$this->lang->line('ca_address')?> <span style="font-size:12px; color: #f31d12"></span></legend>
                        <div class="row mt-2 mb-4">
                            <div class="col-md-6">
                                <label for="address_same"><?=$this->lang->line('address_same')?> ?<span class="text-danger">*</span> </label>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input address_same" type="radio" name="address_same" id="dcsYes" value="YES" <?=($address_same === 'YES')?'checked':''?> />
                                    <label class="form-check-label" for="dcsYes">YES</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input address_same" type="radio" name="address_same" id="dcsNo" value="NO" <?=($address_same === 'NO')?'checked':''?> />
                                    <label class="form-check-label" for="dcsNo">NO</label>
                                </div>
                                <?=form_error("address_same")?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_house_no')?></label>
                                <input class="form-control" name="ca_house_no" id="ca_house_no" value="<?=$ca_house_no?>" maxlength="255" type="text" />
                                <?= form_error("ca_house_no") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_street')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_street" id="ca_street" value="<?=$ca_street?>" maxlength="255" type="text" />
                                <?= form_error("ca_street") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_village')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_village" id="ca_village" value="<?=$ca_village?>" maxlength="255" type="text" />
                                <?= form_error("ca_village") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_post_office')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_post_office" id="ca_post_office" value="<?=$ca_post_office?>" maxlength="255" type="text" />
                                <?= form_error("ca_post_office") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_pin_code')?><span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_pin_code" id="ca_pin_code" value="<?=$ca_pin_code?>" maxlength="6" type="text" />
                                <?= form_error("ca_pin_code") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_state')?><span class="text-danger">*</span> </label>
                                <select name="ca_state" id="ca_state" class="form-control">
                                    <option value="Assam">Assam</option>
                                </select>
                                <?= form_error("ca_state") ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_district')?><span class="text-danger">*</span> </label>
                                <select name="ca_district_id" id="ca_district_id" class="form-control districts">
                                    <option value="">Select a district</option>
                                    <?php if($districts) {
                                        foreach($districts as $dist) {
                                            $selectedDist = ($ca_district_name === $dist->district_name)?'selected':'';
                                            echo '<option value="'.$dist->district_id.'" '.$selectedDist.'>'.$dist->district_name.'</option>';
                                        }//End of foreach()
                                    }//End of if ?>
                                </select>
                                <?= form_error("ca_district_id") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_circle')?><span class="text-danger">*</span> </label>
                                <div id="ca_circles_div">
                                    <select name="ca_circle" id="ca_circle" class="form-control">
                                        <option value="<?=$ca_circle?>"><?=strlen($ca_circle)?$ca_circle:'Select'?></option>
                                    </select>
                                </div>                                    
                                <?= form_error("ca_circle") ?>
                            </div>
                        </div>
                        <div class="row">                                
                            <div class="col-md-6 form-group">
                                <label><?=$this->lang->line('ca_police_station')?> <span class="text-danger">*</span> </label>
                                <input class="form-control" name="ca_police_station" id="ca_police_station" value="<?=$ca_police_station?>" maxlength="100" type="text" />
                                <?= form_error("ca_police_station") ?>
                            </div>
                        </div>
                    </fieldset>
                    
                  
                
               
                     
                </div><!--End of .card-body -->

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
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>