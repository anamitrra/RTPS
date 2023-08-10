<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "http://localhost/wptbcapis/wptbcapis/"; //For testing
// pre($dbrow);
$startYear = date('Y') - 10;
$endYear =  date('Y');
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $rtps_trans_id = $dbrow->service_data->rtps_trans_id;
    $name_prefix = $dbrow->form_data->name_prefix;
    $applicant_name = $dbrow->form_data->applicant_name;
    $applicant_gender = $dbrow->form_data->applicant_gender;
    $mobile = $dbrow->form_data->mobile;
    $aadhar = $dbrow->form_data->aadhar;
    $pan = $dbrow->form_data->pan;
    $email = $dbrow->form_data->email;
    $relation = $dbrow->form_data->relation;
    $relative = $dbrow->form_data->relative;
    $source_income = $dbrow->form_data->source_income;
    $occupation = $dbrow->form_data->occupation;
    $total_income = $dbrow->form_data->total_income;
    $address1 = $dbrow->form_data->address1;
    $address2 = $dbrow->form_data->address2;
    $state = $dbrow->form_data->state;
    $office_district = $dbrow->form_data->office_district;
    $district_name = $dbrow->form_data->district_name??'';
  //  $district_id = $dbrow->form_data->district_id;  
    
   // $sro_code = $dbrow->sro_code;
    //$office_name = $dbrow->form_data->office_name;

  //  $circle = $dbrow->form_data->circle;
    $circle_name = $dbrow->form_data->circle_office ?? '';
    $mouza = $dbrow->form_data->mouza;
    $subdivision = $dbrow->form_data->subdivision??'';
    
    $village = $dbrow->form_data->village;
    $policestation = $dbrow->form_data->policestation;
    $postoffice = $dbrow->form_data->postoffice;
    $pincode = $dbrow->form_data->pincode;
    
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $rtps_trans_id = NULL;//set_value("rtps_trans_id");
    $name_prefix = set_value("name_prefix");
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $service_mode = set_value("service_mode");
    $mobile = $this->session->mobile;//set_value("mobile_number");
    $aadhar = set_value("aadhar");
    $pan = set_value("pan");
    $email = set_value("email");
    $relation = set_value("relation");
    $relative = set_value("relative");
    $source_income = set_value("source_income");
    $occupation = set_value("occupation");
    $total_income = set_value("total_income");
    $address1 = set_value("address1");
    $address2 = set_value("address2");
    $state = set_value("state");
    $office_district = set_value("office_district");  
    $district_name = set_value("district_name");
   // $district_id = set_value("district_id") ?? NULL;
   // $sro_code = set_value("sro_code");
    $office_name = set_value("office_name");

    $circle = set_value("circle");
    $circle_name = set_value("circle_name");
    $mouza = set_value("mouza");
    
    
    $subdivision = set_value("subdivision");
    
    $village = set_value("village");
    $policestation = set_value("policestation");
    $postoffice = set_value("postoffice");
    $pincode = set_value("pincode");
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
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $.getJSON("<?= $apiServer ?>district_list.php", function(data) {
            let selectOption = '';
            $.each(data.records, function(key, value) {
                selectOption += '<option value="' + value.district_name + '">' + value.district_name + '</option>';
            });
            $('.dists').append(selectOption);
        });
    });
    $(document).on("change", ".dists", function() {
        let selectedVal = $(this).val();
        if (selectedVal.length) { //alert(selectedVal);
            var myObject = new Object();
            myObject.district_id = selectedVal; //alert(JSON.stringify(myObject));
            $.getJSON("<?= $apiServer ?>sub_division_list.php?jsonbody=" + JSON.stringify(myObject), function(data) {
                let selectOption = '';
                $('.SubDivisionName').empty().append('<option value="">Select a sub division</option>')
                $.each(data.records, function(key, value) {
                    selectOption += '<option value="' + value.subdiv_name + '">' + value.subdiv_name + '</option>';
                });
                $('.SubDivisionName').append(selectOption);
            });
        }
    });

    $(document).on("change", ".SubDivisionName", function() {
        let selectedVal = $(this).val();
        if (selectedVal.length) { //alert(selectedVal);
            var myObject = new Object();
            myObject.subdiv_id = selectedVal; //alert(JSON.stringify(myObject));
            $.getJSON("<?= $apiServer ?>revenue_circle_list.php?jsonbody=" + JSON.stringify(myObject), function(data) {
                let selectOption = '';
                $('.circle_office').empty().append('<option value="">Select a Circle Office</option>')
                $.each(data.records, function(key, value) {
                    selectOption += '<option value="' + value.circle_name + '">' + value.circle_name + '</option>';
                });
                $('.circle_office').append(selectOption);
            });
        }
    });



    $.post( "<?=base_url('spservices/incomecertificate/registration1/createcaptcha')?>", function(res) {
            $("#captchadiv").html(res);                  
        });
    $(document).on("click", "#reloadcaptcha", function(){                
            $.post( "<?=base_url('spservices/incomecertificate/registration1/createcaptcha')?>", function(res) {
                $("#captchadiv").html(res);                  
            });
     });//End of onChange #reloadcaptcha
        
        $(document).on("click", ".frmbtn", function(){ 
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE') {
                var msg = "Do you want to proceed";
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
    
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/incomecertificate/registration1/submit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="rtps_trans_id" value="<?=$rtps_trans_id?>" type="hidden" />
            <input id="submit_mode" name="submit_mode" value="" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                     Application for Income Certificate<br>
                        ( আয়ৰ প্রমান পত্রৰ বাবব আববদন ) 
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
                        
                        <ol style="  margin-left: 24px; margin-top: 20px">
                            <li>The certificate will be delivered within 10 Days of application.</li>
                            <li>প্ৰমাণ পত্ৰ  ১0 দিনৰ ভিতৰত(সাধাৰণ) অথবা ৩ দিনৰ ভিতৰত(জৰুৰী) প্ৰদান কৰা হ'ব</li>
                        </ol>
                        
                        
                        <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>                        
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1.  Rs. 5/- Per page(General Delivery) Rs. 10/- Per page(Urgent Delivery).</li>
                            <li>১. প্ৰতিটো পৃষ্ঠাৰ বাবে ৫ টকাকৈ ( সাধাৰণ ) /  ১0 টকাকৈ (জৰুৰীকালীন)</li>
                            <li>2. RTPS fee of rupees 20/- per appilcation.</li>
                            <li>২. প্ৰতিখন আবেদনৰ বাবত ২০ টকা Rtps ফিছ</li>
                        </ul>   
                        
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>                        
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1.  All the * marked fields are mandatory and need to be filled up..</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব</li>
                            <li>2. Payment has to be made online and it is to be done when payment request is made by Official.</li>
                            <li>২. কাৰ্যালয়ৰ পৰা অনুৰোধ আহিলে মাছুল অনলাইনত আদায় দিব লাগিব</li>
                        </ul>   

                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                        <div class="row form-group">
                           <div class="col-md-6">
                                <label>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম<span class="text-danger">*</span> </label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text p-1">
                                            <select name="name_prefix">
                                                <option value="Mr" <?= ($name_prefix === "Mr") ? 'selected' : '' ?>>Mr
                                                </option>
                                                <option value="Mrs" <?= ($name_prefix === "Mrs") ? 'selected' : '' ?>>
                                                    Mrs</option>
                                                <option value="Miss" <?= ($name_prefix === "Miss") ? 'selected' : '' ?>>
                                                    Miss</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" name="applicant_name" id="applicant_name"
                                        value="<?= $applicant_name ?>" maxlength="255" />
                                    <?= form_error("applicant_name") || form_error("name_prefix") ?>
                                </div>
                           </div>    
                            <div class="col-md-6">
                                <label>Gender/ লিংগ <span class="text-danger">*</span> </label>
                                <select name="applicant_gender" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Male" <?=($applicant_gender === "Male")?'selected':''?>>Male</option>
                                    <option value="Female" <?=($applicant_gender === "Female")?'selected':''?>>Female</option>
                                    <option value="Transgender" <?=($applicant_gender === "Transgender")?'selected':''?>>Transgender</option>
                                </select>
                                <?= form_error("applicant_gender") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল )<span class="text-danger">*</span> </label>
                               
                                <?php if($usser_type === "user"){ ?>
                                    <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" readonly maxlength="10" />
                               <?php }else{ ?>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" />
                                <?php }?>
                               
                                <?= form_error("mobile") ?>
                            </div>
                           
                            <div class="col-md-6">
                                <label>Aadhar Number/আধাৰ নম্বৰ <span class="text-danger"></span> </label>
                                <input type="text" class="form-control" name="aadhar" id="aadhar" value="<?=$aadhar?>" maxlength="12" />
                                <?= form_error("aadhar") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Pan Number/পান নম্বৰ <span class="text-danger"></span> </label>
                                <input type="text" class="form-control" name="pan" id="pan" value="<?=$aadhar?>" maxlength="10" />
                                <?= form_error("pan") ?>
                            </div>
                        
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল </label>
                                
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Relationship / সম্পৰ্ক<span class="text-danger">*</span> </label>
                                <select name="relation" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Son" <?=($relation === "Son")?'selected':''?>>Son</option>
                                    <option value="Daughter" <?=($relation === "Daughter")?'selected':''?>>Daughter</option>
                                    <option value="Wife" <?=($relation === "Wife")?'selected':''?>>Wife</option>
                                    <option value="Husband" <?=($relation === "Husband")?'selected':''?>>Husband</option>
                                </select>
                               <?= form_error("relation") ?>
                            </div>
                        
                            <div class="col-md-6">
                                <label>Relative's Name/সম্পকীয়ৰ নাম <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="relative" value="<?=$relative?>" maxlength="100" />
                                <?= form_error("relative") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                            <label>Source of Income /উপাৰ্ক নৰ উৎস<span class="text-danger">*</span> </label>
                                <select name="source_income" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Cultivation" <?=($source_income === "Cultivation")?'selected':''?>>Cultivation</option>
                                    <option value="Labour" <?=($source_income === "Labour")?'selected':''?>>Labour</option>
                                    <option value="Business" <?=($source_income === "Business")?'selected':''?>>Business</option>
                                    <option value="Service & Pension" <?=($source_income === "Service & Pension")?'selected':''?>>Service & Pension</option>
                                </select>
                               <?= form_error("source_income") ?>
                            </div>
                        
                            <div class="col-md-6">
                                <label>Occupation/বৃচি <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="occupation" value="<?=$occupation?>" maxlength="100" />
                                <?= form_error("occupation") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Total Income/মুঠ উপাৰ্ক ন<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="total_income" value="<?=$total_income?>" maxlength="100" />
                                <?= form_error("total_income") ?>
                            </div>
                            <div>
                               </div>
                       </div>      
                     </fieldset>
                     <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address / স্হায়ী ঠিকনা </legend>
                        
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Address Line 1 / ঠিকনাৰ প্রথ্ম শাৰী<span class="text-danger"></span> </label>
                                <input type="text" class="form-control" name="address1" value="<?=$address1?>" maxlength="255" />
                                <?= form_error("address1") ?>
                            </div>
                        
                            <div class="col-md-6">
                                <label>Address Line 2 / ঠিকনাৰ চিতীয় শাৰ<span class="text-danger"></span> </label>
                                <input type="text" class="form-control" name="address2" value="<?=$address2?>" maxlength="255" />
                                <?= form_error("address2") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>State<span class="text-danger">*</span> </label>
                                <select name="state" id="state" class="form-control">
                                    <option value="Assam">Assam</option>
                                </select>
                                <?= form_error("state") ?>
                            </div>
                        
                          
                            <div class="col-md-6">
                                <label>District1 / জিলা<span class="text-danger">*</span> </label>
                                <select name="office_district" id="office_district" class="form-control dists">
                                  <option value="<?= $office_district?>">
                                     <?= strlen($office_district)?$office_district:'Select District'?></option>
                                </select>
                                <?= form_error("office_district")||form_error("district_name") ?>
                               
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Subdivision / মহকুমা<span class="text-danger">*</span> </label>
                                <select name="subdivision" class="form-control SubDivisionName">
                                    <option value="<?=$subdivision?>">
                                       <?= strlen($subdivision)?$subdivision:'Select Sub Division'?></option>
                                </select>
                                <?= form_error("subdivision") ?>
                                <!-- <input class="d-none" type="text" value="<?=$subdivision?>"name="subdivision"
                                  id = "subdivision"/> -->
                            </div>
                            <div class="col-md-6">
                                <label>Circle Office / ৰাৰ্হ িক্র<span class="text-danger">*</span> </label>
                                <select name="circle_name" class="form-control circle_office">
                                   <option value="<?= $circle_name?>">
                                      <?= strlen($circle_name) ? $circle_name : 'Select Revenue Circle' ?>
                                  </option>
                              </select>
                                <?= form_error("circle_name") ?>
                                <!-- <input class="d-none" type="text" value="<?=$circle_name?>"
                                   name = "circle_name" id = "circle_name"/> -->
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mouza / মৌজা<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mouza" value="<?=$mouza?>" maxlength="255" />
                                <?= form_error("mouza") ?>                             
                               
                            </div>
                            
                            <div class="col-md-6">
                                <label>Village / Town /গাও/ঁ টাউন<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="village" value="<?=$village?>" maxlength="255" />
                                <?= form_error("village") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Police Station / থানা<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="policestation" value="<?=$policestation?>" maxlength="255" />
                                <?= form_error("policestation") ?>
                            </div>
                        
                        
                            <div class="col-md-6">
                                <label>Post Office / ডাকঘৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="postoffice" value="<?=$postoffice?>" maxlength="255" />
                                <?= form_error("postoffice") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Pincode / পিনকোড<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="pincode" value="<?=$pincode?>" maxlength="255" />
                                <?= form_error("pincode") ?>
                            </div>
                            <div>
                            </div>
                        </div>

                        
                            
                     </fieldset>
                     

                    
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <span id="captchadiv"></span>
                            <button id="reloadcaptcha" class="btn btn-outline-success" type="button" style="padding:3px 10px; float: right"><i class="fa fa-refresh"></i></button>
                            <input name="inputcaptcha" class="form-control mt-1" placeholder="Enter the text as shown in above image" autocomplete="off" required type="text" maxlength="6" />
                            <?=form_error("inputcaptcha")?>
                        </div>
                        <div class="col-md-4"></div>
                    </div> 
                     <!-- End of .row --> 
                     
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