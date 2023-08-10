<?php
//$apiServer = "https://rtps.assam.gov.in/apis/gad_apis/"; //For production
$apiServer = "https://localhost/wptbcapis/"; //For testing
// pre($dbrow);
//$startYear = date('Y') - 10;
//$endYear =  date('Y');
// pre($dbrow);
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;

    $applying_org = $dbrow->form_data->applying_org;
    $applicant_name = $dbrow->form_data->applicant_name;
    $mobile = $dbrow->form_data->mobile;
    $email = isset($dbrow->form_data->email)? $dbrow->form_data->email: "";
    $per_address = $dbrow->form_data->per_address;
    $corres_address = $dbrow->form_data->corres_address;
    
    $conference_title = $dbrow->form_data->conference_title;
    $start_date = $dbrow->form_data->start_date;
    $end_date = $dbrow->form_data->end_date;
    $academic_day1 = $dbrow->form_data->academic_day1;
    $conclusion_day1 = $dbrow->form_data->conclusion_day1;
    $academic_day2 = $dbrow->form_data->academic_day2;
    $conclusion_day2 = $dbrow->form_data->conclusion_day2;
    $academic_day3 = $dbrow->form_data->academic_day3;
    $conclusion_day3 = $dbrow->form_data->conclusion_day3;
    $live_workshop = $dbrow->form_data->live_workshop;
    $conference_location = $dbrow->form_data->conference_location;
    //$conference_organised_by = $dbrow->form_data->conference_organised_by;

    $draft_copy_type = isset($dbrow->form_data->draft_copy_type)? $dbrow->form_data->draft_copy_type: ""; 
    $draft_copy = isset($dbrow->form_data->draft_copy)? $dbrow->form_data->draft_copy: ""; 
    $request_letter_type = isset($dbrow->form_data->request_letter_type)? $dbrow->form_data->request_letter_type: ""; 
    $request_letter = isset($dbrow->form_data->request_letter)? $dbrow->form_data->request_letter: "";
    $cme_program_type = isset($dbrow->form_data->cme_program_type)? $dbrow->form_data->cme_program_type: ""; 
    $cme_program = isset($dbrow->form_data->cme_program)? $dbrow->form_data->cme_program: "";

} else {
    $title = "New Applicant Registration";
    $obj_id = NULL;
    $appl_ref_no = NULL;//set_value("rtps_trans_id");

    $applying_org = set_value("applying_org");
    $applicant_name = set_value("applicant_name");
    $mobile = $this->session->mobile;//set_value("mobile_number");
    $email = set_value("email");
    $per_address = set_value("per_address");
    $corres_address = set_value("corres_address");
  
    $conference_title = set_value("conference_title");
    $start_date = set_value("start_date");
    $end_date = set_value("end_date");
    $academic_day1 = set_value("academic_day1");
    $conclusion_day1 = set_value("conclusion_day1");
    $academic_day2 = set_value("academic_day2");
    $conclusion_day2 = set_value("conclusion_day2");
    $academic_day3 = set_value("academic_day3");
    $conclusion_day3 = set_value("conclusion_day3");
    $conference_location = set_value("conference_location");
    //$conference_organised_by = set_value("conference_organised_by");   
    $live_workshop = set_value("live_workshop");
    
    $draft_copy_type = "";
    $draft_copy = "";
    $request_letter_type = ""; 
    $request_letter = "";
    $cme_program_type = ""; 
    $cme_program = "";
    
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

<link rel="stylesheet" href="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.css">
<script src="<?= base_url("assets/"); ?>plugins/moment/moment.min.js"></script>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<script src="<?= base_url("assets/"); ?>plugins/daterangepicker/daterangepicker.js"></script>
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
        
        $(".dp").datepicker({
            format: 'dd/mm/yyyy',
            endDate: '+0d',
            autoclose: true
        });

        $(document).on("keyup", "#pan_no", function(){ 
            if($("#pan_no").val().length > 10) {
                $("#pan_no").val("");
                alert("Please! Enter upto only 10 digit"); 
            }             
        });

        $('.number_input').keypress(function (e) {    
            var charCode = (e.which) ? e.which : event.keyCode    
            if (String.fromCharCode(charCode).match(/[^0-9]/g))    
                return false;                        
        });

        $('#aadhar_no').keyup(function () {    
            if($("#aadhar_no").val().length > 12) {
                $("#aadhar_no").val("");
                alert("Please! Enter upto only 12 digit"); 
            }                        
        });

        $('.pin_code').keyup(function () {    
            if($(".pin_code").val().length > 6) {
                $(".pin_code").val("");
                alert("Please! Enter upto only 6 digit"); 
            }                        
        });

         $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
            $('#start_date').val("");
            $('#end_date').val("");
            $('input[name="daterange"]').val("");
            
            });
        $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
            $('#start_date').val(picker.startDate.format('YYYY-MM-DD'));
            $('#end_date').val(picker.endDate.format('YYYY-MM-DD'));
          
        });
        $("#clear").click(function() {
            $("select").val("");
            $("#filters").empty();
        });
        $('input[name="daterange"]').daterangepicker({
            opens: 'left',
            linkedCalendars: false,
            showDropdowns: true,
            // autoUpdateInput:false,
            // startDate: moment().subtract(29, 'days'),
            // endDate: moment(),
           
            locale: {
                "format": "DD/MM/YYYY",
                "separator": " - ",
                "applyLabel": "Apply Date Range",
                "cancelLabel": "Clear Selection",
                "fromLabel": "From",
                "toLabel": "To",
                "customRangeLabel": "Custom",
                "weekLabel": "W",
                "daysOfWeek": [
                    "Su",
                    "Mo",
                    "Tu",
                    "We",
                    "Th",
                    "Fr",
                    "Sa"
                ],
                "monthNames": [
                    "January",
                    "February",
                    "March",
                    "April",
                    "May",
                    "June",
                    "July",
                    "August",
                    "September",
                    "October",
                    "November",
                    "December"
                ],
                "firstDay": 1
            },
        }, function(start, end, label) {
            console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));

            //table.draw();
        });

    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/acmr-cp-cme') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
            <input name="draft_copy_type" value="<?=$draft_copy_type?>" type="hidden" />
            <input name="draft_copy" value="<?=$draft_copy?>" type="hidden" />
            <input name="cme_program_type" value="<?=$cme_program_type?>" type="hidden" />
            <input name="cme_program" value="<?=$cme_program?>" type="hidden" />
            <input name="request_letter_type" value="<?=$request_letter_type?>" type="hidden" />
            <input name="request_letter" value="<?=$request_letter?>" type="hidden" />
           
           
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm" >
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                Application for Issue of Credit Points for attending CME<br>
                        ( চিএমইত উপস্থিত থকাৰ বাবে ক্ৰেডিট পইণ্ট প্ৰদানৰ বাবে আবেদন পত্ৰ ) 
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
                            <li>7 (Seven) working days for Indian Medical Graduates.</li>
                            <li>ভাৰতীয় চিকিৎসা স্নাতকসকলৰ বাবে ৭ (সাত) কৰ্মদিন</li>
                        </ol>
                        
                        
                        <strong style="font-size:16px;  margin-top: 10px">Fees/Charges/ মাছুল :</strong>                        
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>User charge / ব্যৱহাৰকাৰী মাচুল – Rs. 2000 / ২০০০ টকা.</li>
                            <li>GST charge / জি এছ টি মাচুল – Rs. 360 / ৩৬০ টকা.</li>
                            <li>Service charge (PFC/ CSC) / সেৱা মাচুল (পি. এফ. চি./ চি. এছ. চি.)– Rs.30/৩০ টকা</li>
                            <li>Printing charge (in case of any printing from PFC) / ছপা খৰচ (পি.এফ.চি. ৰ পৰা কোনো ধৰণৰ প্ৰিন্টিঙৰ ক্ষেত্ৰত) - Rs. 10 Per Page / প্ৰতি পৃষ্ঠাত ১০ টকা.</li>
                            <li>Scanning charge (in case documents are scanned in PFC) স্কেনিং খৰচ (যদি নথিপত্ৰসমূহ পি.এফ.চি. ত স্কেন কৰা হয়) - Rs. 5 Per page / প্ৰতি পৃষ্ঠা ৫ টকা.</li>
                        </ul>   
                        
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>                        
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1.  All the * marked fields are mandatory and need to be filled up..</li>
                            <li>১. * চিহ্ন দিয়া স্থানসমূহ বাধ্য়তামুলক আৰু স্থানসমূহ পুৰণ কৰিব লাগিব</li>
                            <li>2. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>২. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য </li>
                        </ul>   

                    </fieldset>
                    
                    <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5"><b>Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </b></legend>
                        <div class="row form-group">
                        <div class="col-md-6">
                        <label>Name Applying Organization/ আবেদন কৰা সংস্থাৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applying_org" id="applying_org" value="<?=$applying_org?>" maxlength="255" />
                                <?= form_error("applying_org") ?>
                            </div>
                            <div class="col-md-6">
                            <label>Name of person responsible for applying/ আবেদনৰ বাবে দায়বদ্ধ ব্যক্তিৰ নাম<span class="text-danger">*</span> </label>
                                <input type="text" class="form-control" name="applicant_name" id="applicant_name" value="<?=$applicant_name?>" maxlength="255" />
                                <?= form_error("applicant_name") ?>
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
                            <label>E-Mail / ই-মেইল <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="email" value="<?=$email?>" maxlength="100" />
                                <?= form_error("email") ?>
                            </div>
                        </div> 

                        <div class="row form-group"> 
                        <div class="col-md-6 form-group">
                            <label>Permanent Address/ স্থায়ী ঠিকনা <span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="per_address"><?=$per_address?></textarea>
                                <?= form_error("per_address") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Correspondence Address/ চিঠিপত্ৰৰ ঠিকনা <span class="text-danger">*</span> </label>
                                <textarea class="form-control" name="corres_address"><?=$corres_address?></textarea>
                                <?= form_error("corres_address") ?>
                            </div>
                        </div>                    
                         
                    </fieldset>

                    <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5"><b>Details of the Conference/CME/Workshop / সন্মিলন/চিএমই/কৰ্মশালাৰ সবিশেষ </b></legend>


                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Title of the Conference/CME/Workshop/ সন্মিলনৰ শিৰোনাম/চিএমই/কৰ্মশালা<span class="text-danger">*</span></label>
                                <input class="form-control" name="conference_title" value="<?=$conference_title?>" maxlength="100" id="conference_title" type="text" />
                                <?= form_error("conference_title") ?>
                            </div> 
                            
                            <div class="col-12 col-md-3">
                                    <label for="date-range">Date of commencement/ আৰম্ভণিৰ তাৰিখ</label>
                                    <input id="date-range" class="form-control" type="text" name="daterange" value=""/>
                                    <input type="hidden" id="start_date" name="start_date" value="" />
                                    <input type="hidden" id="end_date" name="end_date" value="" />
                                    <?= form_error("start_date") ?>
                                    <?= form_error("end_date") ?>  
                            </div>
                        </div> 

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Time of start of academic session of Day 1/ প্ৰথম দিনৰ শৈক্ষিক অধিৱেশন আৰম্ভ কৰাৰ সময়<span class="text-danger">*</span> </label>
                                <input class="form-control" type="time" name="academic_day1" value="<?=$academic_day1?>" maxlength="100" id="academic_day1" type="text" />
                                <?= form_error("academic_day1") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Time of conclusion of academic session of Day 1/ প্ৰথম দিনৰ শৈক্ষিক অধিৱেশন সমাপ্ত কৰাৰ সময়<span class="text-danger">*</span></label>
                                <input class="form-control " type="time" name="conclusion_day1" value="<?=$conclusion_day1?>" maxlength="100" id="conclusion_day1" type="text" />
                                <?= form_error("conclusion_day1") ?>
                            </div> 
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Time of start of academic session of Day 2/ দ্বিতীয় দিনৰ শৈক্ষিক অধিৱেশন আৰম্ভ কৰাৰ সময়<span class="text-danger"></span> </label>
                                <input class="form-control" type="time" name="academic_day2" value="<?=$academic_day2?>" maxlength="100" id="academic_day2" type="text" />
                                <?= form_error("academic_day2") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Time of conclusion of academic session of Day 2/ দ্বিতীয় দিনৰ শৈক্ষিক অধিৱেশন সমাপ্ত কৰাৰ সময়<span class="text-danger"></span></label>
                                <input class="form-control " type="time" name="conclusion_day2" value="<?=$conclusion_day2?>" maxlength="100" id="conclusion_day2" type="text" />
                                <?= form_error("conclusion_day2") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Time of start of academic session of Day 3/ তৃতীয় দিনৰ শৈক্ষিক অধিৱেশন আৰম্ভ কৰাৰ সময় <span class="text-danger"></span> </label>
                                <input class="form-control" type="time" name="academic_day3" value="<?=$academic_day3?>" maxlength="100" id="academic_day3" type="text" />
                                <?= form_error("academic_day3") ?>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Time of conclusion of academic session of Day 3/ তৃতীয়  দিনৰ শৈক্ষিক অধিৱেশন সমাপ্ত কৰাৰ সময়<span class="text-danger"></span></label>
                                <input class="form-control " type="time" name="conclusion_day3" value="<?=$conclusion_day3?>" maxlength="100" id="conclusion_day3" type="text" />
                                <?= form_error("conclusion_day3") ?>
                            </div>
                        </div>

                        <div class="row"> 
                            <div class="col-md-6 form-group">
                                <label>Venue of the Conference/CME/Workshop / সন্মিলন/চিএমই/কৰ্মশালাৰ অৱস্থান<span class="text-danger"></span></label>
                                <input class="form-control" name="conference_location" value="<?=$conference_location?>" maxlength="100" id="conference_location" type="text" />
                                <?= form_error("conference_location") ?>
                            </div>                            
                            <div class="col-md-6 form-group">
                                <label>In case of Live Workshop, are all operating Faculties Registered with Assam Council of Medical Registration? 
                                        / লাইভ ৱৰ্কশ্বপৰ ক্ষেত্ৰত, সকলোৱে অসম চিকিৎসা পঞ্জীয়ন পৰিষদৰ সৈতে পঞ্জীয়ন কৰা অনুষদ বোৰ পঞ্জীয়ন কৰিছে নেকি? <span class="text-danger"></span></label>
                                <select name="live_workshop" class="form-control">
                                <option value="">Please Select</option>
                                    <option value="Yes" <?=($live_workshop === "Yes")?'selected':''?>>Yes</option>
                                    <option value="No" <?=($live_workshop === "No")?'selected':''?>>No</option>
                                </select>
                                <?= form_error("live_workshop") ?>
                            </div>
                        </div>
                        
                        <!-- End of .row --> 
                     
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
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>