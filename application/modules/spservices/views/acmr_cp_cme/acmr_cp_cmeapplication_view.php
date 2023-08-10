<?php
//var_dump($dbrow);
//exit();
        $obj_id = $dbrow->{'_id'}->{'$id'};  
        $appl_ref_no = $dbrow->service_data->appl_ref_no;
        
        $applicant_name = $dbrow->form_data->applicant_name;
        $mobile = $dbrow->form_data->mobile;
        $email = isset($dbrow->form_data->email)? $dbrow->form_data->email: "NA";
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
        $conference_location = $dbrow->form_data->conference_location;
        //$conference_organised_by = $dbrow->form_data->conference_organised_by;   
        $live_workshop = $dbrow->form_data->live_workshop;

        $request_letter_type = $dbrow->form_data->request_letter_type??'';
        $request_letter = $dbrow->form_data->request_letter??'';
        $cme_program_type = $dbrow->form_data->cme_program_type??'';
        $cme_program = $dbrow->form_data->cme_program??'';
        $draft_copy_type = $dbrow->form_data->draft_copy_type??'';
        $draft_copy = $dbrow->form_data->draft_copy??'';

        $appl_status = $dbrow->service_data->appl_status;
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
    td {
        font-size: 14px;
    }
</style>

<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js"></script>
<script type="text/javascript">   
    $(document).ready(function () {
        $(document).on("click", "#printBtn", function(){
            $("#printDiv").print({
                addGlobalStyles : true,
                stylesheet : null,
                rejectWindow : true,
                noPrintSelector : ".no-print",
                iframe : true,
                append : null,
                prepend : null
            });
        });
        
        $(document).on("click", ".frmsbbtn", function(e){ 

            $(".frmsbbtn").text("Plese wait..");
            $(".frmsbbtn").prop('disabled',true);
            e.preventDefault();

            let url='<?=base_url('spservices/delayeddeath/registration/finalsubmition')?>'
            let ackLocation='<?=base_url('spservices/applications/acknowledgement/')?>'+'<?=$obj_id?>';
            let clickedBtn = $(this).attr("id");//alert(clickedBtn);
            var msg = "Once you submitted, you won't able to revert this";   

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
                    $.ajax({
                        url: url,
                        type:'POST',
                        dataType: 'json',
                        data: {
                            "obj":'<?=$obj_id?>'
                        },
                        success:function (response) {
                            console.log(response);
                            if(response.status){
                                
                                Swal.fire(
                                    'Success',
                                    'Application submited successfully',
                                    'success'
                                );

                                window.location.replace(ackLocation)
                            }else{
                                Swal.fire(
                                    'Failed!',
                                    'Something went wrong please try again!',
                                    'fail'
                                );
                            }
                        },
                        error:function () {
                            $(".frmsbbtn").prop('disabled',false);
                            $(".frmsbbtn").text("Save");
                            Swal.fire(
                                'Failed!',
                                'Something went wrong please try again!',
                                'fail'
                            );
                        }
                    });
                }
            });
        }); 
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv">
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                   <?=$service_name?> 
            </div>
            <div class="card-body" style="padding:5px">

                <?php if ($this->session->flashdata('success') != null) { ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> <?= $this->session->flashdata('success') ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>
                Application Ref. No: <?=$appl_ref_no?>
                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Details of the Applicant/আবেদনকাৰীৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the Applicant/আবেদনকাৰীৰ নাম<br><strong><?=$applicant_name?></strong> </td>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?=$mobile?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?=$mobile?></strong> </td>
                                <td>E-Mail / ই-মেইল<br><strong><?=$email?></strong> </td>
                            </tr>
                            <tr>
                                <td>Permanent Address/ স্থায়ী ঠিকনা <br><strong><?=$per_address?></strong> </td>
                                <td>Correspondence Address/ চিঠিপত্ৰৰ ঠিকনা<br><strong><?=$corres_address?></strong> </td>
                            </tr>
                            
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Details of the Conference/CME/Workshop / সন্মিলন/চিএমই/কৰ্মশালাৰ সবিশেষ
                    </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Title of the Conference/CME/Workshop/ সন্মিলনৰ শিৰোনাম/চিএমই/কৰ্মশালা<br><strong><?=$conference_title?></strong> </td>
                                <td>Date of commencement/ আৰম্ভণিৰ তাৰিখ <br><strong><?=$start_date?> &nbsp;  to &nbsp;  <?=$end_date?></strong> </td>
                            </tr>
                            <tr>
                                <td>Time of start of academic session of Day 1/ প্ৰথম দিনৰ শৈক্ষিক অধিৱেশন আৰম্ভ কৰাৰ সময়<br><strong><?=$academic_day1?></strong> </td>
                                <td>Time of conclusion of academic session of Day 1/ প্ৰথম দিনৰ শৈক্ষিক অধিৱেশন সমাপ্ত কৰাৰ সময় <br><strong><?=$conclusion_day1?></strong> </td>
                            </tr>
                            <tr>
                                <td>Time of start of academic session of Day 2/ প্ৰথম দিনৰ শৈক্ষিক অধিৱেশন আৰম্ভ কৰাৰ সময়<br><strong><?=$academic_day2?></strong> </td>
                                <td>Time of conclusion of academic session of Day 2/ প্ৰথম দিনৰ শৈক্ষিক অধিৱেশন সমাপ্ত কৰাৰ সময় <br><strong><?=$conclusion_day2?></strong> </td>
                            </tr>
                            <tr>
                                <td>Time of start of academic session of Day 3/ প্ৰথম দিনৰ শৈক্ষিক অধিৱেশন আৰম্ভ কৰাৰ সময়<br><strong><?=$academic_day3?></strong> </td>
                                <td>Time of conclusion of academic session of Day 3/ প্ৰথম দিনৰ শৈক্ষিক অধিৱেশন সমাপ্ত কৰাৰ সময় <br><strong><?=$conclusion_day3?></strong> </td>
                            </tr>
                            <tr>
                                <td>Location of the Conference/CME/Workshop / সন্মিলন/চিএমই/কৰ্মশালাৰ অৱস্থান<br><strong><?=$conference_location?></strong> </td>
                                <!-- <td>Conference/CME/Workshop organised by /সন্মিলন/চিএমই/কৰ্মশালাৰ দ্বাৰা আয়োজিত  <br><strong><?=$conference_organised_by?></strong> </td> -->
                            </tr>
                            <tr>
                                <td>In case of Live Workshop, are all operating Faculties Registered with Assam Council of Medical Registration? / লাইভ ৱৰ্কশ্বপৰ ক্ষেত্ৰত, সকলোৱে অসম চিকিৎসা পঞ্জীয়ন পৰিষদৰ সৈতে পঞ্জীয়ন কৰা অনুষদ বোৰ পঞ্জীয়ন কৰিছে নেকি?  <br><strong><?=$live_workshop?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
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
                                <td style="font-weight:bold"><?=$request_letter_type?></td>
                                <td>
                                    <?php if(strlen($request_letter)){ ?>
                                        <a href="<?=base_url($request_letter)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>CME Program/Schedule</td>
                                <td style="font-weight:bold"><?=$cme_program_type?></td>
                                <td>
                                    <?php if(strlen($cme_program)){ ?>
                                        <a href="<?=base_url($cme_program)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Draft copy/copies of Certificate(s) to be issued to doctors.</td>
                                <td style="font-weight:bold"><?=$draft_copy_type?></td>
                                <td>
                                    <?php if(strlen($draft_copy)){ ?>
                                        <a href="<?=base_url($draft_copy)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>                
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>