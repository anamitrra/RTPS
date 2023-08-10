<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://localhost/wptbcapis/"; //For testing
// pre($dbrow);
$startYear = date('Y') - 10;
$endYear =  date('Y');
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $rtps_trans_id = $dbrow->rtps_trans_id;
    $applicant_name = $dbrow->applicant_name;
    $applicant_gender = $dbrow->applicant_gender;
    $mobile = $dbrow->mobile;
    $relation = $dbrow->relation;
    $relative = $dbrow->relative;
    $source_income = $dbrow->source_income;
    $occupation = $dbrow->occupation;
    $total_income = $dbrow->total_income;
    $address1 = $dbrow->address1;
    $address2 = $dbrow->address2;
    $state = $dbrow->state;
    $district = $dbrow->district;
    $subdivision = $dbrow->subdivision;
    $circleoffice = $dbrow->circleoffice;
    $mouza = $dbrow->mouza;
    $village = $dbrow->village;
    $policestation = $dbrow->policestation;
    $postoffice = $dbrow->postoffice;
    $pincode = $dbrow->pincode;
    
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $rtps_trans_id = NULL;//set_value("rtps_trans_id");
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $service_mode = set_value("service_mode");
    $mobile = $this->session->mobile;//set_value("mobile_number");
    $relation = set_value("relation");
    $relative = set_value("relative");
    $source_income = set_value("source_income");
    $occupation = set_value("occupation");
    $total_income = set_value("total_income");
    $address1 = set_value("address1");
    $address2 = set_value("address2");
    $state = set_value("state");
    $district = set_value("district");
    $subdivision = set_value("subdivision");
    $circleoffice = set_value("circleoffice");
    $mouza = set_value("mouza");
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

    $(document).ready(function () {
        $.post( "<?=base_url('iservices/wptbc/castecertificate/createcaptcha')?>", function(res) {
            $("#captchadiv").html(res);                  
        });

        $(document).on("click", "#reloadcaptcha", function(){                
            $.post( "<?=base_url('iservices/wptbc/castecertificate/createcaptcha')?>", function(res) {
                $("#captchadiv").html(res);                  
            });
        });//End of onChange #reloadcaptcha
     
        // $.getJSON("<?=$apiServer?>district_list.php", function (data) {
        //     let selectOption = '';
        //     $.each(data.records, function (key, value) {
        //         selectOption += '<option value="'+value.district_name+'">'+value.district_name+'</option>';
        //     });
        //     $('.dists').append(selectOption);
        // });        
       
  
   
                
        $(document).on("change", "#pa_district", function(){               
            let selectedVal = $(this).val();
            let selectedText = $(this).find("option:selected").text();
            if(selectedVal.length) {
                $("#pa_district_name").val(selectedText)
                $.getJSON("<?=base_url("spservices/registereddeed/getlocation")?>?id="+selectedVal, function (data) {
                    let selectOption = '';
                    $('#sro_code').empty().append('<option value="">Select a location</option>')
                    $.each(data, function (key, value) {
                        selectOption += '<option value="'+value.org_unit_code+'">'+value.org_unit_name+'</option>';
                    });
                    $('#sro_code').append(selectOption);
                });
            }
        });

        $(document).on('change',"#sro_code",function(){
            let selectedText = $(this).find("option:selected").text();
            $("#sro_name").val(selectedText)
        })
        
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
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/registereddeed/submit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="rtps_trans_id" value="<?=$rtps_trans_id?>" type="hidden" />
            <input id="submit_mode" name="submit_mode" value="" type="hidden" />
            <div class="card shadow-sm" >
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                     Application for Non Creamy Layer Certificate<br>
                        ( অনা উচ্চস্তৰীয় স্তৰৰ প্রমান পত্রৰ বাবব আববদন ) 
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
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ <span class="text-danger">*</span> </label>
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
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <?php if($usser_type === "user"){ ?>
                                    <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" readonly maxlength="10" />
                               <?php }else{ ?>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" />
                                <?php }?>
                               
                                <?= form_error("mobile") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Relation / সম্পৰ্ক<span class="text-danger">*</span> </label>
                                <select name="" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Son" <?=($applicant_gender === "Son")?'selected':''?>>Son</option>
                                    <option value="Daughter" <?=($applicant_gender === "Daughter")?'selected':''?>>Daughter</option>
                                    <option value="Wife" <?=($applicant_gender === "Wife")?'selected':''?>>Wife</option>
                                    <option value="Husband" <?=($applicant_gender === "Husband")?'selected':''?>>Husband</option>
                                </select>
                               <?= form_error("relation") ?>
                            </div>
                            
                        </div>
                       <div class="row form-group">
                            <div class="col-md-6">
                                <label>Relative's Name/সম্পকীয়ৰ নাম </label>
                                <input type="text" class="form-control" name="relative" value="<?=$relative?>" maxlength="100" />
                                <?= form_error("relative") ?>
                            </div>
                        
                            <div class="col-md-6">
                                <label>Source of Income /উপাৰ্ক নৰ উৎস </label>
                                <input type="text" class="form-control" name="source_income" value="<?=$source_income?>" maxlength="100" />
                                <?= form_error("source_income") ?>
                            </div>
                       </div>
                       <div class="row form-group">
                            <div class="col-md-6">
                                <label>Occupation/বৃচি </label>
                                <input type="text" class="form-control" name="occupation" value="<?=$occupation?>" maxlength="100" />
                                <?= form_error("occupation") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Total Income/মুঠ উপাৰ্ক ন</label>
                                <input type="text" class="form-control" name="total_income" value="<?=$total_income?>" maxlength="100" />
                                <?= form_error("total_income") ?>
                            </div>
                       </div>
                            
                     </fieldset>
                     <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Permanent Address / স্হায়ী ঠিকনা </legend>
                        
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Address Line 1 / ঠিকনাৰ প্রথ্ম শাৰী<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="address1" value="<?=$address1?>" maxlength="255" />
                                <?= form_error("address1") ?>
                            </div>
                        
                            <div class="col-md-6">
                                <label>Address Line 2 / ঠিকনাৰ চিতীয় শাৰ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="address2" value="<?=$address2?>" maxlength="255" />
                                <?= form_error("address2") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>State / ৰাজ্য<span class="text-danger">*</span> </label>
                                <select name="state" class="form-control">
                                    <option value="Assam" selected="selected">Assam</option>
                                </select>
                                <?= form_error("state") ?>
                            </div>
                        
                          
                            <div class="col-md-6">
                                <label>District / জিলা<span class="text-danger">*</span> </label>
                                <select name="district" id="district" class="form-control dists">
                                    <option value="<?=$district?>"><?=strlen($district)?$district:'Select'?></option>
                                </select>
                                <?= form_error("district") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Subdivision / মহকুমা<span class="text-danger">*</span> </label>
                                <select name="subdivision" id="subdivision" class="form-control">
                                    <option value="<?=$subdivision?>"><?=strlen($subdivision)?$subdivision:'Select'?></option>
                                </select>
                                <?= form_error("subdivision") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Circle Office / ৰাৰ্হ িক্র<span class="text-danger">*</span> </label>
                                <select name="circleoffice" id="circleoffice" class="form-control">
                                    <option value="<?=$circleoffice?>"><?=strlen($circleoffice)?$circleoffice:'Select'?></option>
                                </select>
                                <?= form_error("circleoffice") ?>
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
                     

                    
                    <!-- <div class="row">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <span id="captchadiv"></span>
                            <button id="reloadcaptcha" class="btn btn-outline-success" type="button" style="padding:3px 10px; float: right"><i class="fa fa-refresh"></i></button>
                            <input name="inputcaptcha" class="form-control mt-1" placeholder="Enter the text as shown in above image" autocomplete="off" required type="text" maxlength="6" />
                            <?=form_error("inputcaptcha")?>
                        </div>
                        <div class="col-md-4"></div>
                    </div> -->
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