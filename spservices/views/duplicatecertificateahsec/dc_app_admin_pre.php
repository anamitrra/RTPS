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
$applicant_remarks = $dbrow->form_data->applicant_remarks ?? '';

$photo_of_the_candidate = $dbrow->form_data->photo_of_the_candidate ?? '';
$candidate_sign = $dbrow->form_data->candidate_sign ?? '';
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
$wrongly_generate_certi = $dbrow->form_data->wrongly_generate_certi ?? '';

$photo_of_the_candidate_type = $dbrow->form_data->photo_of_the_candidate_type ?? '';
$candidate_sign_type = $dbrow->form_data->candidate_sign_type ?? '';
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
$wrongly_generate_certi_type = $dbrow->form_data->wrongly_generate_certi_type ?? '';

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
<link rel="stylesheet" href="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.css')?>">
<script src="<?=base_url('assets/plugins/sweetalert2/sweetalert2.min.js')?>"></script>
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

                <?php if ($this->session->flashdata('success') != null) {?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> <?=$this->session->flashdata('success')?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php }?>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of the Applicant/আবেদনকাৰীৰ নাম<br><strong><?=$applicant_name?></strong> </td>
                                <td>AHSEC Registrtion Session/ এ এইচ এছ ই চি পঞ্জীয়ন
                                    অধিবেশন<br><strong><?=$ahsec_reg_session?></strong></td>
                            </tr>
                            <tr>
                                <td>Valid AHSEC Registrtion Number / বৈধ এ এইচ এছ ই চি পঞ্জীয়ন নম্বৰ
                                    <br><strong><?=$ahsec_reg_no?></strong>
                                </td>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br> <strong><?=$mobile?></strong></td>
                            </tr>
                            <?php if (!empty($ahsec_admit_roll)) {?>
                            <tr>
                                <td>Valid H.S. Final Year Examination Roll/ বৈধ এইচ.এছ. অন্তিম বৰ্ষৰ পৰীক্ষা
                                    ৰ'ল<br><strong><?=$ahsec_admit_roll?></strong> </td>
                                <td>Valid H.S. Final Year Examination Number / বৈধ এইচ.এছ. অন্তিম বৰ্ষৰ পৰীক্ষা
                                    নম্বৰ<br><strong><?=$ahsec_admit_no?></strong> </td>
                            </tr>
                            <tr>
                                <td>Year of appearing in HS Final Examination / এইচ.এছ. চূড়ান্ত পৰীক্ষাত অৱতীৰ্ণ হোৱা
                                    বছৰ<br><strong><?=$ahsec_yearofpassing?></strong> </td>
                                <td></td>
                            </tr>
                            <?php }?>
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
                    <legend class="h5">Applicant Remarks / আবেদনকাৰীৰ মন্তব্য</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td><strong><?=$applicant_remarks?></strong> </td>
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
                                        <td>Photo of the Candidate*.</td>
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
                                        <td>Sign of the Candidate*.</td>
                                        <td style="font-weight:bold"><?=$candidate_sign_type?></td>
                                        <td>
                                            <a href="<?=base_url($candidate_sign)?>"
                                                class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        </td>
                                    </tr>

                                    <?php if (!empty($fir_type)) {?>
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
                                    <?php }?>

                                    <?php if (!empty($paper_advertisement_type)) {?>
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
                                    <?php }?>

                                    <?php if (!empty($hslc_tenth_mrksht_type)) {?>
                                    <tr>
                                        <td>HSLC/10th Marksheet Copy<span class="text-danger">*</span></td>
                                        <td style="font-weight:bold"><?=$hslc_tenth_mrksht_type?></td>
                                        <td>
                                            <a href="<?=base_url($hslc_tenth_mrksht)?>"
                                                class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        </td>
                                    </tr>
                                    <?php }?>

                                    <?php if (!empty($damage_reg_card_type)) {?>
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
                                    <?php }?>

                                    <?php if (!empty($damage_admit_card_type)) {?>
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
                                    <?php }?>

                                    <?php if (!empty($hs_reg_card_type)) {?>
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
                                    <?php }?>

                                    <?php if (!empty($damage_mrksht_type)) {?>
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
                                    <?php }?>

                                    <?php if (!empty($damage_pass_certi_type)) {?>
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
                                    <?php }?>

                                    <?php if (!empty($hs_admit_card_type)) {?>
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
                                    <?php }?>

                                    <?php if (!empty($hs_mrksht_type)) {?>
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
                                    <?php }?>

                                    <?php if (!empty($wrongly_generate_certi_type)) {?>
                                    <tr>
                                        <td>Copy of wrongly generate certificate<span class="text-danger">*</span></td>
                                        <td style="font-weight:bold"><?=$wrongly_generate_certi_type?></td>
                                        <td>
                                            <a href="<?=base_url($wrongly_generate_certi)?>"
                                                class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        </td>
                                    </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </fieldset>
            </div>
            <!--End of .card-body -->
            <div class="card-footer text-center no-print">
                <?php if ($this->session->userdata['loggedin_user_level_no'] <= 3) {?>

                <button class="btn btn-primary" id="printBtn" type="button" style="color:white;">
                    <i class="fa fa-download"></i> Download Application
                </button>

                <a class="btn btn-primary"
                    href="<?=base_url('spservices/duplicatecertificateahsec/actions/app_pre_admin/' . $obj_id)?>"
                    type="button" style="color:white;" target="_blank">
                    <i class="fa fa-edit"></i> Access
                </a>

                <?php if(($appl_status == "AA") || ($appl_status == "D")){ ?>
                <a class="btn btn-primary"
                    href="<?=base_url('spservices/duplicatecertificateahsec/actions/eCopy_preview/' . $obj_id)?>"
                    type="button" style="color:white;" target="_blank">
                    <i class="fa fa-file"></i> E-Copy
                </a>
                <?php }?>
                <?php }?>
                <button class="btn btn-warning" id="printBtn" type="button" style="color:white;">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
            <!--End of .card-footer-->
        </div>
        <!--End of .card-->
    </div>
    <!--End of .container-->
</main>