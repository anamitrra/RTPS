<?php
//var_dump($dbrow);
//exit();
$obj_id = $dbrow->{'_id'}->{'$id'};  
$appl_ref_no = $dbrow->service_data->appl_ref_no;
$pageTitle = $dbrow->service_data->service_name;
$pageTitleId = $dbrow->service_data->service_id;
 
$father_name = $dbrow->form_data->father_name;
$mother_name = $dbrow->form_data->mother_name;
$applicant_name = $dbrow->form_data->applicant_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$mobile = $dbrow->form_data->mobile;
$email = $dbrow->form_data->email;
$comp_permanent_address = $dbrow->form_data->comp_permanent_address;
$pa_state = $dbrow->form_data->pa_state;
$pa_district = explode("/", $dbrow->form_data->pa_district)[0];
$pa_pincode = $dbrow->form_data->pa_pincode;
    
$comp_postal_address = $dbrow->form_data->comp_postal_address;
$pos_state = $dbrow->form_data->pos_state;
$pos_district = explode("/", $dbrow->form_data->pos_district)[0];
$pos_pincode = $dbrow->form_data->pos_pincode;
    
$ahsec_reg_session = $dbrow->form_data->ahsec_reg_session ?? '';
$ahsec_reg_no = $dbrow->form_data->ahsec_reg_no ?? '';
$ahsec_yearofpassing = $dbrow->form_data->ahsec_yearofpassing ?? '';
$ahsec_admit_roll = $dbrow->form_data->ahsec_admit_roll ?? '';
$ahsec_admit_no = $dbrow->form_data->ahsec_admit_no ?? '';
   
$board_seaking_adm = $dbrow->form_data->board_seaking_adm ?? '';
$course_seaking_adm = $dbrow->form_data->course_seaking_adm ?? '';
$state_seaking_adm = $dbrow->form_data->state_seaking_adm ?? ''; 
$reason_seaking_adm = $dbrow->form_data->reason_seaking_adm ?? '';   
$postal = $dbrow->form_data->postal ?? '';   

$condi_of_doc = $dbrow->form_data->condi_of_doc ?? ''; 

$photo_of_the_candidate = $dbrow->form_data->photo_of_the_candidate??'';
$candidate_sign = $dbrow->form_data->candidate_sign??'';
$fir = $dbrow->form_data->fir ?? '';
$paper_advertisement = $dbrow->form_data->paper_advertisement ?? '';
$hslc_tenth_mrksht = $dbrow->form_data->hslc_tenth_mrksht ?? '';
$damage_reg_card = $dbrow->form_data->damage_reg_card ?? '';
$damage_admit_card = $dbrow->form_data->damage_admit_card ?? '';
$hs_reg_card = $dbrow->form_data->hs_reg_card ?? '';
$damage_mrksht = $dbrow->form_data->damage_mrksht ?? '';
$hs_admit_card = $dbrow->form_data->hs_admit_card ?? '';
$damage_pass_certi = $dbrow->form_data->damage_pass_certi ?? '';
$hs_mrksht = $dbrow->form_data->hs_mrksht ?? '';

$photo_of_the_candidate_type = $dbrow->form_data->photo_of_the_candidate_type??'';
$candidate_sign_type = $dbrow->form_data->candidate_sign_type??'';
$fir_type = $dbrow->form_data->fir_type ?? '';
$paper_advertisement_type = $dbrow->form_data->paper_advertisement_type ?? '';   
$hslc_tenth_mrksht_type = $dbrow->form_data->hslc_tenth_mrksht_type ?? ''; 
$damage_reg_card_type = $dbrow->form_data->damage_reg_card_type ?? '';
$damage_admit_card_type = $dbrow->form_data->damage_admit_card_type ?? '';
$hs_reg_card_type = $dbrow->form_data->hs_reg_card_type ?? ''; 
$damage_mrksht_type = $dbrow->form_data->damage_mrksht_type ?? '';
$hs_admit_card_type = $dbrow->form_data->hs_admit_card_type ?? '';
$damage_pass_certi_type = $dbrow->form_data->damage_pass_certi_type ?? '';
$hs_mrksht_type = $dbrow->form_data->hs_mrksht_type ?? '';
    
$appl_status = $dbrow->service_data->appl_status ?? '';
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
$(document).ready(function() {
    $(document).on("click", "#printBtn", function() {
        $("#printDiv").print({
            addGlobalStyles: true,
            stylesheet: null,
            rejectWindow: true,
            noPrintSelector: ".no-print",
            iframe: true,
            append: null,
            prepend: null
        });
    });

    $(document).on("click", ".frmsbbtn", function(e) {
        e.preventDefault();

        let url = '<?=base_url('spservices/nextofkin/registration/finalsubmition')?>'
        let ackLocation = '<?=base_url('spservices/applications/acknowledgement/')?>' + '<?=$obj_id?>';
        let clickedBtn = $(this).attr("id"); //alert(clickedBtn);
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
            if (result.value == true) {
                $(".frmsbbtn").text("Plese wait..");
                $(".frmsbbtn").prop('disabled', true);
                $.ajax({
                    url: url,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        "obj": '<?=$obj_id?>'
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.status) {

                            Swal.fire(
                                'Success',
                                'Application submited successfully',
                                'success'
                            );

                            window.location.replace(ackLocation)
                        } else {
                            $(".frmsbbtn").prop('disabled', false);
                            $(".frmsbbtn").text("Save");
                            Swal.fire(
                                'Failed!',
                                'Something went wrong please try again!',
                                'fail'
                            );
                        }
                    },
                    error: function() {
                        $(".frmsbbtn").prop('disabled', false);
                        $(".frmsbbtn").text("Save");
                        Swal.fire(
                            'Failed!',
                            'Something went wrong please try again!',
                            'fail'
                        );
                    }
                });
            } else {

            }
        });
    });
});
</script>

<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv">
            <div class="card-header"
                style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                <?php echo $pageTitle ?><br>
                <?php 
                    switch ($pageTitleId) {
                        case "AHSECDRC": 
                            echo '( ডুপ্লিকেট পঞ্জীয়ন কাৰ্ড প্ৰদানৰ বাবে আবেদন )';
                            break;
                        case "AHSECDADM":
                            echo '( ডুপ্লিকেট এডমিট কাৰ্ড প্ৰদানৰ বাবে আবেদন )';
                            break;
                        case "AHSECDMRK":
                            echo '( ডুপ্লিকেট মাৰ্কশ্বীটত প্ৰদানৰ বাবে আবেদন )';
                                break;
                        case "AHSECDPC":
                            echo '( ডুপ্লিকেট উত্তীৰ্ণ প্ৰমাণপত্ৰ প্ৰদানৰ বাবে আবেদন )';
                                break;
                    }
                    ?>
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

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the Applicant/আবেদনকাৰীৰ নাম<br><strong><?=$applicant_name?></strong> </td>
                                <td>Applicant Gender/আবেদনকাৰীৰ লিংগ<br><strong><?=$applicant_gender?></strong></td>

                            </tr>
                            <tr>
                                <td>Father&apos;s Name/ পিতৃৰ নাম <br><strong><?=$father_name?></strong> </td>
                                <td>Mother&apos;s Name/ মাতৃৰ নাম<br><strong><?=$mother_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br> <strong><?=$mobile?></strong></td>
                                <td>E-Mail / ই-মেইল<br><strong><?=$email?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Permanent Address / স্থায়ী ঠিকনা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Complete Permanent Address/ সম্পূৰ্ণ স্থায়ী
                                    ঠিকনা<br><strong><?=$comp_permanent_address?></strong> </td>
                                <td>Pincode/ পিনকোড <br><strong><?=$pa_pincode?></strong> </td>

                            </tr>
                            <tr>
                                <td>State<br><strong><?=$pa_state?></strong> </td>
                                <td>District<br><strong><?=$pa_district?></strong> </td>
                            </tr>

                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Postal Address / ডাক ঠিকনা</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Complete Postal Address/ সম্পূৰ্ণ ডাক
                                    ঠিকনা<br><strong><?=$comp_postal_address?></strong> </td>
                                <td>Pincode/ পিনকোড <br><strong><?=$pos_pincode?></strong> </td>

                            </tr>
                            <tr>
                                <td>State<br><strong><?=$pos_state?></strong> </td>
                                <td>District<br><strong><?=$pos_district?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Academic Details / শৈক্ষিক বিৱৰণ </legend>

                    <!-- <b style="color: #007bff; font-size:16px;">12th(10+2) Board / দ্বাদশ(১০+২) ব'ৰ্ড) :-</b><br /><br /> -->
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>AHSEC Registration Session/ AHSEC পঞ্জীয়ন
                                    বৰ্ষ<br><strong><?=$ahsec_reg_session?></strong> </td>
                                <td>Valid AHSEC Registration Number / বৈধ AHSEC পঞ্জীয়ন নম্বৰ
                                    <br><strong><?=$ahsec_reg_no?></strong>
                                </td>

                            </tr>
                            <tr>

                                <td>Valid H.S Final Examination Roll/ বৈধ HS চূড়ান্ত বৰ্ষৰ পৰীক্ষাৰ ৰোল <br><strong><?=$ahsec_admit_roll?></strong> </td>
                                <td>Valid H.S Final Examination Number/ বৈধ HS চূড়ান্ত বৰ্ষৰ পৰীক্ষাৰ নম্বৰ<br><strong><?=$ahsec_admit_no?></strong> </td>
                            </tr>
                            <tr>
                                <td><?php if ($pageTitleId == "AHSECDPC") { ?>Year of Passing in HS Final Examination/ HS চূড়ান্ত পৰীক্ষাত উত্তীৰ্ণ হোৱাৰ বছৰ <?php } else { ?>Year of Appearing in HS Final Examination/ HS চূড়ান্ত পৰীক্ষাত অৱতীৰ্ণ হোৱাৰ বছৰ <?php } ?><br><strong><?=$ahsec_yearofpassing?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Application Details / আবেদনৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>University/Board where seeking admission / বিশ্ববিদ্যালয়/বৰ্ড য'ত নামভৰ্তি
                                    বিচাৰিছে<br><strong><?=$board_seaking_adm?></strong> </td>
                                <td>Course name where seeking admission/পাঠ্যক্ৰমৰ নাম য'ত নামভৰ্তি
                                    বিচাৰিছে<br><strong><?=$course_seaking_adm?></strong> </td>
                            </tr>
                            <tr>
                                <td>State where seeking admission/ ক’ত নামভৰ্তি বিচাৰিছে সেই কথা উল্লেখ কৰক
                                    <br><strong><?=$state_seaking_adm?></strong>
                                </td>
                                <td>Describe Reason for Seeking Migration/ প্ৰব্ৰজন বিচৰাৰ কাৰণ বৰ্ণনা কৰা
                                    <br><strong><?=$reason_seaking_adm?></strong>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Condition of the document's / নথিপত্ৰ/সমূহৰ অৱস্থা</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td><strong><?=$condi_of_doc?></strong> </td>

                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Delivery Preference / ডেলিভাৰীৰ পছন্দ</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>How would you want to receive your migration certificate ? / আপুনি আপোনাৰ প্ৰব্ৰজন
                                    প্ৰমাণপত্ৰ কেনেকৈ লাভ কৰিব বিচাৰিব<br><strong><?=$postal?></strong> </td>

                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">ATTACH ENCLOSURE(S) / সংলগ্নক সমূহ </legend>
                    <div class="row mt-3">
                        <div class="col-12">
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
                                        <td>Passport size photograph*.</td>
                                        <td style="font-weight:bold"><?=$photo_of_the_candidate_type?></td>
                                        <td>
                                            <a href="<?=base_url($photo_of_the_candidate)?>"
                                                class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Applicant Signature*.</td>
                                        <td style="font-weight:bold"><?=$candidate_sign_type?></td>
                                        <td>
                                            <a href="<?=base_url($candidate_sign)?>"
                                                class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        </td>
                                    </tr>

                                    <?php if(!empty($fir_type)){ ?>
                                    <tr>
                                        <td>FIR Copy<span class="text-danger">*</span></td>
                                        <td style="font-weight:bold"><?=$fir_type?></td>
                                        <td>
                                            <a href="<?=base_url($fir)?>" class="btn font-weight-bold text-success"
                                                target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                    <?php if(!empty($paper_advertisement_type)){ ?>
                                    <tr>
                                        <td>Paper Advertisement Copy<span class="text-danger">*</span></td>
                                        <td style="font-weight:bold"><?=$paper_advertisement_type?></td>
                                        <td>
                                            <a href="<?=base_url($paper_advertisement)?>"
                                                class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                    <?php if(!empty($hslc_tenth_mrksht_type)){ ?>
                                    <tr>
                                        <td>HSLC/10thMarksheet<span class="text-danger">*</span></td>
                                        <td style="font-weight:bold"><?=$hslc_tenth_mrksht_type?></td>
                                        <td>
                                            <a href="<?=base_url($hslc_tenth_mrksht)?>"
                                                class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                    <?php if(!empty($damage_reg_card_type)){ ?>
                                    <tr>
                                        <td>Damaged portion of the Registration Card Copy<span
                                                class="text-danger">*</span></td>
                                        <td style="font-weight:bold"><?=$damage_reg_card_type?></td>
                                        <td>
                                            <a href="<?=base_url($damage_reg_card)?>"
                                                class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                    <?php if(!empty($damage_admit_card_type)){ ?>
                                    <tr>
                                        <td>Damaged portion of the Admit Card Copy<span class="text-danger">*</span>
                                        </td>
                                        <td style="font-weight:bold"><?=$damage_admit_card_type?></td>
                                        <td>
                                            <a href="<?=base_url($damage_admit_card)?>"
                                                class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                    <?php if(!empty($damage_pass_certi_type)){ ?>
                                    <tr>
                                        <td>Damaged portion of the Pass Certificate<span class="text-danger">*</span>
                                        </td>
                                        <td style="font-weight:bold"><?=$damage_pass_certi_type?></td>
                                        <td>
                                            <a href="<?=base_url($damage_pass_certi)?>"
                                                class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                    <?php if(!empty($hs_reg_card_type)){ ?>
                                    <tr>
                                        <td>HS Registration Card<span class="text-danger">*</span></td>
                                        <td style="font-weight:bold"><?=$hs_reg_card_type?></td>
                                        <td>
                                            <a href="<?=base_url($hs_reg_card)?>"
                                                class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                    <?php if(!empty($damage_mrksht_type)){ ?>
                                    <tr>
                                        <td>Damaged portion of the Marksheet<span class="text-danger">*</span></td>
                                        <td style="font-weight:bold"><?=$damage_mrksht_type?></td>
                                        <td>
                                            <a href="<?=base_url($damage_mrksht)?>"
                                                class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                    <?php if(!empty($hs_admit_card_type)){ ?>
                                    <tr>
                                        <td>HS Admit Card<span class="text-danger">*</span></td>
                                        <td style="font-weight:bold"><?=$hs_admit_card_type?></td>
                                        <td>
                                            <a href="<?=base_url($hs_admit_card)?>"
                                                class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>

                                    <?php if(!empty($hs_mrksht_type)){ ?>
                                    <tr>
                                        <td>HS Marksheet<span class="text-danger">*</span></td>
                                        <td style="font-weight:bold"><?=$hs_mrksht_type?></td>
                                        <td>
                                            <a href="<?=base_url($hs_mrksht)?>"
                                                class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>
            </div>
            <!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <?php if($appl_status === 'DRAFT') { ?>
                <a href="<?= base_url('spservices/duplicatecertificateahsec/registration/index/' . $obj_id) ?>"
                    class="btn btn-primary">
                    <i class="fa fa-pencil"></i> Edit
                </a>
                <?php } ?>
                <button class="btn btn-warning" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <?php if(($appl_status != 'QA') && ($appl_status != 'QS')){ ?>
                <a href="<?= base_url('spservices/duplicatecertificateahsec/payment/initiate/' . $obj_id) ?>"
                    class="btn btn-success">
                    <i class="fa fa-angle-double-right"></i> Make Payment
                </a>
                <?php }?>
            </div>
            <!--End of .card-footer-->
        </div>
        <!--End of .card-->
    </div>
    <!--End of .container-->
</main>