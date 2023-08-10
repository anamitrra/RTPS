<?php
$currentYear = date('Y');
//$apiServer = $this->config->item('edistrict_base_url');
if($dbrow) {
    $title = "Edit Existing Information";
    $obj_id = $dbrow->{'_id'}->{'$id'};
    $appl_ref_no = $dbrow->service_data->appl_ref_no;
    $service_name = $dbrow->service_data->service_name;
    $status = $dbrow->service_data->appl_status;

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

    
    $request_letter_type_frm = set_value("request_letter_type");
    $cme_program_type_frm = set_value("cme_program_type");
    $draft_copy_type_frm = set_value("draft_copy_type");

    $uploadedFiles = $this->session->flashdata('uploaded_files');
    $request_letter_frm = $uploadedFiles['request_letter_old']??null;
    $cme_program_frm = $uploadedFiles['cme_program_old']??null;
    $draft_copy_frm = $uploadedFiles['draft_copy_old']??null;

    $request_letter_type_db = $dbrow->form_data->request_letter_type??null;
    $cme_program_type_db = $dbrow->form_data->cme_program_type??null;
    $draft_copy_type_db = $dbrow->form_data->draft_copy_type??null;

    $request_letter_db = $dbrow->form_data->request_letter??null;
    $cme_program_db = $dbrow->form_data->cme_program??null;
    $draft_copy_db = $dbrow->form_data->draft_copy??null;


    $request_letter_type = strlen($request_letter_type_frm)?$request_letter_type_frm:$request_letter_type_db;
    $cme_program_type = strlen($cme_program_type_frm)?$cme_program_type_frm:$cme_program_type_db;
    $draft_copy_type = strlen($draft_copy_type_frm)?$draft_copy_type_frm:$draft_copy_type_db;

    $request_letter = strlen($request_letter_frm)?$request_letter_frm:$request_letter_db;
    $cme_program = strlen($cme_program_frm)?$cme_program_frm:$cme_program_db;
    $draft_copy = strlen($draft_copy_frm)?$draft_copy_frm:$draft_copy_db;
} ?>
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
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datepicker/datepicker3.css') ?>">
<script src="<?= base_url('assets/plugins/datepicker/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/iservices/js/custom/digilocker.js') ?>"></script>
<script type="text/javascript">   
    $(document).ready(function () {        



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

        

        $('.number_input').keypress(function (e) {    
            var charCode = (e.which) ? e.which : event.keyCode    
            if (String.fromCharCode(charCode).match(/[^0-9]/g))    
                return false;                        
        });

        

        var requestLetter = parseInt(<?=strlen($request_letter)?1:0?>);
        $("#request_letter").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            //required: requestLetter?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });
        
        var cmeProgram = parseInt(<?=strlen($cme_program)?1:0?>);
        $("#cme_program").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            //required: cmeProgram?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

        var draftCopy = parseInt(<?=strlen($draft_copy)?1:0?>);
        $("#draft_copy").fileinput({
            dropZoneEnabled: false,
            showUpload: false,
            showRemove: false,
            required: false,
            //required: draftCopy?false:true,
            maxFileSize: 1024,
            allowedFileExtensions: ["pdf"]
        });

                
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <form id="myfrm" method="POST" action="<?= base_url('spservices/acmr_cp_cme/registration/querysubmit') ?>" enctype="multipart/form-data">
            <input id="obj_id" name="obj_id" value="<?=$obj_id?>" type="hidden" />
            <input name="appl_ref_no" value="<?=$appl_ref_no?>" type="hidden" />
            <input name="request_letter_old" value="<?=$request_letter?>" type="hidden" />
            <input name="cme_program_old" value="<?=$cme_program?>" type="hidden" />
            <input name="draft_copy_old" value="<?=$draft_copy?>" type="hidden" />
            <input id="submit_mode" name="submission_mode" value="" type="hidden" />
            <div class="card shadow-sm">
                <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    <?=$service_name?> 
                </div>
                <div class="card-body" style="padding:5px">
                    
                    <?php if ($this->session->flashdata('fail') != null) { ?>
                        <script>
                            $(".frmbtn").show();
                        </script>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> <?= $this->session->flashdata('fail') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php }
                    if ($this->session->flashdata('error') != null) { ?>
                        <script>
                            $(".frmbtn").show();
                        </script>
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
                    <?php } if($status === 'QS') { ?>
                        <fieldset class="border border-danger" style="margin-top:40px; margin-bottom: 2px">
                            <legend class="h5">QUERY DETAILS </legend>
                            <div class="row">
                                <div class="col-md-12" style="font-size:16px; margin-bottom: 10px; text-align: justify">                                    
                                    <?=(end($dbrow->processing_history)->remarks)??''?>
                                </div>
                            </div>                            
                            <span style="float:right; font-size: 12px">
                                Query time : <?=isset(end($dbrow->processing_history)->processing_time)?format_mongo_date(end($dbrow->processing_history)->processing_time):''?>
                            </span>
                        </fieldset>
                    <?php }//End of if ?>
                                        
                   <fieldset class="border border-success" style="margin-top:40px">
                        <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
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
                                <!-- <?php if($usser_type === "user"){ ?>
                                    <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" readonly maxlength="10" />
                               <?php }else{ ?>
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" />
                                <?php }?> -->
                                <input type="text" class="form-control" name="mobile" value="<?=$mobile?>" maxlength="10" />
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
                    <legend class="h5">Details of the Conference/CME/Workshop / সন্মিলন/চিএমই/কৰ্মশালাৰ সবিশেষ </legend>

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
                                    <label>Time of start of academic session of Day 1/ প্ৰথম দিনৰ শৈক্ষিক অধিৱেশন আৰম্ভ কৰাৰ সময় <span class="text-danger">*</span> </label>
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
                                    <label>Time of start of academic session of Day 2/ দ্বিতীয় দিনৰ শৈক্ষিক অধিৱেশন আৰম্ভ কৰাৰ সময় <span class="text-danger">*</span> </label>
                                    <input class="form-control" type="time" name="academic_day2" value="<?=$academic_day2?>" maxlength="100" id="academic_day2" type="text" />
                                    <?= form_error("academic_day2") ?>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Time of conclusion of academic session of Day 2/ দ্বিতীয় দিনৰ শৈক্ষিক অধিৱেশন সমাপ্ত কৰাৰ সময়<span class="text-danger">*</span></label>
                                    <input class="form-control " type="time" name="conclusion_day2" value="<?=$conclusion_day2?>" maxlength="100" id="conclusion_day2" type="text" />
                                    <?= form_error("conclusion_day2") ?>
                                </div>
                            </div>

                            <div class="row"> 
                                <div class="col-md-6 form-group">
                                    <label>Time of start of academic session of Day 3/ তৃতীয় দিনৰ শৈক্ষিক অধিৱেশন আৰম্ভ কৰাৰ সময় <span class="text-danger">*</span> </label>
                                    <input class="form-control" type="time" name="academic_day3" value="<?=$academic_day3?>" maxlength="100" id="academic_day3" type="text" />
                                    <?= form_error("academic_day3") ?>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Time of conclusion of academic session of Day 3/ তৃতীয়  দিনৰ শৈক্ষিক অধিৱেশন সমাপ্ত কৰাৰ সময়<span class="text-danger">*</span></label>
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
                                    / লাইভ ৱৰ্কশ্বপৰ ক্ষেত্ৰত, সকলোৱে অসম চিকিৎসা পঞ্জীয়ন পৰিষদৰ সৈতে পঞ্জীয়ন কৰা অনুষদ বোৰ পঞ্জীয়ন কৰিছে নেকি? <span class="text-danger">*</span></label>
                                    <input class="form-control" name="live_workshop" value="<?=$live_workshop?>" maxlength="100" id="live_workshop" type="text" />
                                    <?= form_error("live_workshop") ?>
                                </div>  
                                                </fieldset>

                    <fieldset class="border border-success">
                        <legend class="h5">Important / দৰকাৰী </legend>
                        <strong style="font-size:16px;  margin-top: 10px">General Instruction/সাধাৰণ নিৰ্দেশাৱলী :</strong>
                        <ul style="  margin-left: 24px; margin-top: 10px">
                            <li>1. The size of documents to be uploaded at the time of Application submission should not exceed 1 MB and format should be pdf. No other format will be accepted.</li>
                            <li>১. আপলোড কৰিব লগিয়া নথিসমুহৰ আকাৰ ১ mb তকৈ সৰু হ'ব লাগিব আৰু পদ্ধতি pdf formatৰ হোৱাতো অনিবাৰ্য।</li>
                        </ul>
                    </fieldset>

                    <!-- Digilocker -->
                    <fieldset class="border border-success" style="margin-top:0px">
                        <p>If you wish to upload your enclosures/documents from digilocker, please link your digilocker account. Click on the below button to link your account.</p>
                        <p><?php $this->digilocker_api->login_btn();  ?></p>
                    </fieldset> 
                    <fieldset class="border border-success table-responsive" style="margin-top:40px">
                        <legend class="h5">ATTACHED ENCLOSURE(S) </legend>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Type of Enclosure</th>
                                <th>Enclosure Document</th>
                                <th>File/Reference</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Request Letter for Physical CME on Letter head.</td>
                                <td>
                                     <select name="request_letter_type" class="form-control">
                                        <!-- <option value="">Select</option> -->
                                        <option value="Request Letter for Physical CME on Letter head" <?=($request_letter_type === 'Request Letter for Physical CME on Letter head')?'selected':''?>>Request Letter for Physical CME on Letter head</option>
                                      </select>
                                      <?= form_error("request_letter_type") ?>
                                </td>
                                <td>
                                    <div class="file-loading">
                                        <input id="request_letter" name="request_letter" type="file" />
                                    </div>
                                    <?php if(strlen($request_letter)){ ?>
                                        <a href="<?=base_url($request_letter)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                    <input class="request_letter" type="hidden" name="request_letter_temp">
                                        <?= $this->digilocker_api->digilocker_fetch_btn('request_letter'); ?>
                                    </td>
                            </tr>

                            <tr>
                                <td>CME Program/Schedule</td>
                                <td>
                                     <select name="cme_program_type" class="form-control">
                                        <!-- <option value="">Select</option> -->
                                        <option value="CME Program/Schedule" <?=($cme_program_type === 'CME Program/Schedule')?'selected':''?>>CME Program/Schedule</option>
                                      </select>
                                      <?= form_error("cme_program_type") ?>
                                </td>
                                <td>
                                    <div class="file-loading">
                                        <input id="cme_program" name="cme_program" type="file" />
                                    </div>
                                    <?php if(strlen($cme_program)){ ?>
                                        <a href="<?=base_url($cme_program)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                    <input class="cme_program" type="hidden" name="cme_program_temp">
                                        <?= $this->digilocker_api->digilocker_fetch_btn('cme_program'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Draft copy/copies of Certificate(s) to be issued to doctors.</td>
                                <td>
                                     <select name="draft_copy_type" class="form-control">
                                        <!-- <option value="">Select</option> -->
                                        <option value="Draft copy/copies of Certificate(s) to be issued to doctors" <?=($draft_copy_type === 'Draft copy/copies of Certificate(s) to be issued to doctors')?'selected':''?>>Draft copy/copies of Certificate(s) to be issued to doctors</option>
                                      </select>
                                      <?= form_error("draft_copy_type") ?>
                                </td>
                                <td>
                                    <div class="file-loading">
                                        <input id="draft_copy" name="draft_copy" type="file" />
                                    </div>
                                    <?php if(strlen($draft_copy)){ ?>
                                        <a href="<?=base_url($draft_copy)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                    <input class="draft_copy" type="hidden" name="draft_copy_temp">
                                         <?= $this->digilocker_api->digilocker_fetch_btn('draft_copy'); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                        
                    <fieldset class="border border-danger table-responsive" style="overflow:hidden">
                        <legend class="h5">Processing history</legend>
                        <table class="table table-bordered bg-white mt-0">
                            <thead>
                                <tr>
                                    <th>Sl. No.</th>
                                    <th>Date &AMP; time</th>
                                    <th>Action taken</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($dbrow->processing_history)) {
                                    foreach($dbrow->processing_history as $key=>$rows) {
                                    $query_attachment = $rows->query_attachment??''; ?>
                                        <tr>
                                            <td><?=sprintf("%02d", $key+1)?></td>
                                            <td><?=date('d/m/Y h:i a', strtotime($this->mongo_db->getDateTime($rows->processing_time)))?></td>
                                            <td><?=$rows->action_taken?></td>
                                            <td><?=$rows->remarks?></td>
                                        </tr>
                                    <?php }//End of foreach()
                                }//End of if else ?>
                            </tbody>
                        </table>
                    </fieldset> 
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <!-- <button class="btn btn-primary frmbtn" id="DRAFT" type="button">
                        <i class="fa fa-file"></i> Draft
                    </button> -->
                    <button class="btn btn-success frmbtn" id="SAVE" type="button">
                        <i class="fa fa-angle-double-right"></i> Submit
                    </button>
                    <!-- <button class="btn btn-danger frmbtn" id="CLEAR" type="button">
                        <i class="fa fa-refresh"></i> Reset
                    </button> -->
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
        </form>
    </div><!--End of .container-->
</main>