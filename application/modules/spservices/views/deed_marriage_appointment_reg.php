<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://localhost/wptbcapis/"; //For testing
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $rtps_trans_id = $dbrow->rtps_trans_id;
    $applicant_name = $dbrow->applicant_name;
    $applicant_gender = $dbrow->applicant_gender;
    $father_name = $dbrow->father_name;
    $mother_name = $dbrow->mother_name;
    $mobile_number = $dbrow->mobile_number;
    $email = $dbrow->email;
    $address = $dbrow->address;
    $district = $dbrow->district;
    $office_location = $dbrow->office_location;
    $appointment_type = $dbrow->appointment_type;
    $appointment_dt = $dbrow->appointment_dt;
    $deed_type = $dbrow->deed_type;
    $appl_ref_no = $dbrow->appl_ref_no;
    $bride_name = $dbrow->bride_name;
    $groom_name = $dbrow->groom_name;
    $appl_name = $dbrow->appl_name;
   
} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $rtps_trans_id = NULL;//set_value("rtps_trans_id");
    $applicant_name = set_value("applicant_name");
    $applicant_gender = set_value("applicant_gender");
    $father_name = set_value("father_name");
    $mother_name = set_value("mother_name");
    $mobile_number = $this->session->mobile;//set_value("mobile_number");
    $email = set_value("email");
    $address = set_value("address");
    $district = set_value("district");
    $office_location = set_value("office_location");
    $appointment_type = set_value("appointment_type");
    $appointment_dt = set_value("appointment_dt");
    $deed_type = set_value("deed_type");
    $appl_ref_no = set_value("appl_ref_no");
    $bride_name = set_value("bride_name");
    $groom_name = set_value("groom_name");
    $appl_name = set_value("appl_name");
    $inputcaptcha = set_value("inputcaptcha");
}//End of if else
//die($title." : ".$obj_id);
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

        $.post( "<?=base_url('spservices/necertificate/createcaptcha')?>", function(res) {
            $("#captchadiv").html(res);                  
        });

        $(document).on("click", "#reloadcaptcha", function(){                
            $.post( "<?=base_url('spservices/necertificate/createcaptcha')?>", function(res) {
                $("#captchadiv").html(res);                  
            });
        });//End of onChange #reloadcaptcha
        
        $.getJSON("<?=$apiServer?>district_list.php", function (data) {
            let selectOption = '';
            $.each(data.records, function (key, value) {
                selectOption += '<option value="'+value.district_name+'">'+value.district_name+'</option>';
            });
            $('.dists').append(selectOption);
        });        


        
        $(document).on("click", ".frmbtn", function(){ 
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            $("#submit_mode").val(clickedBtn);
            if(clickedBtn === 'DRAFT') {
                var msg = "You want to save in Draft mode that will allows you to edit and can submit later";
            } else if(clickedBtn === 'SAVE') {
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
                if (result.value) {
                    if((clickedBtn === 'DRAFT') || (clickedBtn === 'SAVE')) {
                        $("#myfrm").submit();
                    } else if(clickedBtn === 'CLEAR') {
                        $("#myfrm")[0].reset();
                    } else {}//End of if else
                }
            });
        });      
        // appointment_type  
        $(document).on("change", "#appointment_type", function(){                
            let type = $(this).val(); //alert(checkedVal);
            if(type === "Deed") {
                $('.marriage_div').addClass('d-none')
                $('.deed_div').removeClass('d-none')
            }
            else{
                $('.deed_div').addClass('d-none')
                $('.marriage_div').removeClass('d-none')
            }
        });
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/deed_marriage_appointment/submit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="rtps_trans_id" value="<?=$rtps_trans_id?>" type="hidden" />
            <input id="submit_mode" name="submit_mode" value="" type="hidden" />
            <div class="card shadow-sm" style="background:#E6F1FF">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                Application form for Appointment - Deed Registration/Marriage<br>
                        ( বিবাহ পঞ্জীয়ন /দলিল পঞ্জীয়নৰ বাবে সময় বিচাৰি আবেদন ) 
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
                        <legend class="h5">Please Note:  </legend>
                        <strong style="font-size:16px; ">General Instruction/সাধাৰণ নিৰ্দেশাৱলী </strong>
                        <ul style="margin-left: 24px; margin-top: 20px">
                            <li>1. All the * marked fields are mandatory and need to be filled up.</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমু বাধ্য়তামুলক আৰু স্থানসমু পুৰণ কৰিব লাগিব</li>
 
                            <li>2.  Applicant can take apppontment for marriage after applying for Marriage Registration.</li>
                            <li>২. আবেদনকাৰীয়ে বিবাহৰ পঞ্জীয়নৰ বাবে আবেদন কৰাৰ পিছতহে বিবাহৰ নিযুক্তিৰ সময় ল'ব পাৰিব</li>
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
                                <label>Father&apos;s Name / পিতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="father_name" id="father_name" value="<?=$father_name?>" maxlength="255" />
                                <?= form_error("father_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Mother&apos;s Name/ মাতৃৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mother_name" value="<?=$mother_name?>" maxlength="255" />
                                <?= form_error("mother_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Mobile Number / দুৰভাষ ( মবাইল ) <span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="mobile_number" value="<?=$mobile_number?>" maxlength="10" readonly />
                                <?= form_error("mobile_number") ?>
                            </div>
                            <div class="col-md-6">
                                <label>E-Mail / ই-মেইল<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                        </div>
                        <div class="row mt-3" id="applicant_photo_row">
                            <div class="col-12">
                                <label for="">Address of the Applicant/আবেদনকাৰীৰ ঠিকনা <span class="text-danger">*</span></label>
                                <textarea name="address" id="" class="form-control" data-parsley-errors-container="#address"><?=$address?></textarea>
                                <span id="address"></span>
                                <?= form_error("address") ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Select Office for Application Submission/কাৰ্য্যালয় বাছনি কৰক, য'ত আবেদন পঠোৱা হ'ব  </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Select District / জিলা নিৰ্বাচন কৰক<span class="text-danger">*</span> </label>
                                <select name="district" id="district" class="form-control dists">
                                    <option value="<?=$district?>"><?=strlen($district)?$district:'Select'?></option>
                                </select>
                                <?= form_error("district") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Select office/কাৰ্য্যালয় নিৰ্বাচন কৰক<span class="text-danger">*</span> </label>
                                <select name="office_location" id="office_location" class="form-control">
                                    <option value="<?=$office_location?>"><?=strlen($office_location)?$office_location:'Select'?></option>
                                    <option value="location">location</option>
                                </select>
                                <?= form_error("office_location") ?>
                            </div>
                        </div>
                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Appointment details/নিযুক্তিৰ বিৱৰণ  </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Select Appointment Type/নিযুক্তিৰ প্ৰকাৰ বাছনি কৰক<span class="text-danger">*</span> </label>
                                <select name="appointment_type" id="appointment_type" class="form-control">
                                    <option value="">Please Select</option>
                                    <option value="Deed">Deed/দলিল</option>
                                    <option value="Marriage">Marriage/বিবাহ</option>
                                </select>
                                <?= form_error("appointment_type") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Date of appointment/নিযুক্তিৰ সময়<span class="text-danger">*</span> </label>
                                <select name="appointment_dt" id="appointment_dt" class="form-control">
                                    <option value="<?=$appointment_dt?>"><?=strlen($appointment_dt)?$appointment_dt:'Select'?></option>
                                    <option value="1">1</option>

                                </select>
                                <?= form_error("appointment_dt") ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="border border-success deed_div" style="margin-top:40px">
                        <legend class="h5">Deed Details/দলিলৰ বিৱৰণ  </legend>
                        <div class="row form-group">
                            <div class="col-md-12">
                                <label>Deed type/দলিলৰ ধৰণ<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" id="deed_type" name="deed_type" value="<?=$deed_type?>" maxlength="255" />
                                <?= form_error("deed_type") ?>
                            </div>
                        </div>
                    </fieldset>
                                        
                    <fieldset class="border border-success d-none marriage_div" style="margin-top:40px">
                        <legend class="h5">Marriage Details/বিবাহৰ বিৱৰণ </legend>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Application Ref No/দৰ্খাস্তৰ নং <span class="text-danger">*</span></label>
                                <select name="appl_ref_no" class="form-control">
                                    <option value="">Please Select</option>
                                    <!-- <option value="YES" <?=($appl_ref_no === "YES")?'selected':''?>>YES</option> -->
                                </select>
                                <?= form_error("appl_ref_no") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Applicant Name/আবেদনকাৰীৰ নাম </label>
                                <input type="text" class="form-control" name="appl_name" value="<?=$appl_name?>" maxlength="255" />
                                <?= form_error("appl_name") ?>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-6">
                                <label>Bride Name/কইনাৰ নাম</label>
                                <input type="text" class="form-control" name="bride_name" value="<?=$bride_name?>" maxlength="255" />
                                <?= form_error("bride_name") ?>
                            </div>
                            <div class="col-md-6">
                                <label>Groom Name/দৰাৰ নাম  </label>
                                <input type="text" class="form-control" name="groom_name" value="<?=$groom_name?>" maxlength="255" />
                                <?= form_error("groom_name") ?>
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
                    </div> <!-- End of .row --> 
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button>
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-check"></i> Submit
                    </button>
                    <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>