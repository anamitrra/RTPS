<?php
if($this->session->userdata('upms_login_status'))
{
    $flg = true;
}
else {
    $flg = false;
}
$obj_id = $dbrow->_id->{'$id'};
$user = $dbrow->form_data->user_type ?? '';

$user_type = $dbrow->form_data->user_type;
$status = $dbrow->service_data->appl_status;
$payment_status = $dbrow->form_data->pfc_payment_status ?? '';

$unformat_date = getDateFormat($dbrow->service_data->created_at);//dateformat helper
$txts = explode(' ', $unformat_date);
$date = $txts[0];

$applicant_type = isset($dbrow->form_data->applicant_type) ? $dbrow->form_data->applicant_type:'';
$category = isset($dbrow->form_data->category) ? $dbrow->form_data->category:'';
$zone = isset($dbrow->form_data->zone) ? $dbrow->form_data->zone:'';
$circle = isset($dbrow->form_data->circle) ? $dbrow->form_data->circle:'';
$department_name = isset($dbrow->service_data->department_name) ? $dbrow->service_data->department_name:'';
$deptt_code = isset($dbrow->form_data->deptt_name) ? $dbrow->form_data->deptt_name:'';
if($applicant_type == 'Individual') {
$applicant_name = isset($dbrow->form_data->applicant_name) ? $dbrow->form_data->applicant_name : '';
$father_husband_name = isset($dbrow->form_data->father_husband_name) ? $dbrow->form_data->father_husband_name:'';
} else {
$father_husband_name = '';

if($applicant_type == 'Proprietorship') {
    $applicant_name = "";
    $guardian_name = isset($dbrow->form_data->owner_director_name) ? $dbrow->form_data->owner_director_name : '';
    $date_of_deed = 'NA';
    $date_of_validity = 'NA';

} else {
    $applicant_name = isset($dbrow->form_data->org_name) ? $dbrow->form_data->org_name : '';
    $guardian_name = isset($dbrow->form_data->owner_director_name) ? $dbrow->form_data->owner_director_name : '';
    $date_of_deed = isset($dbrow->form_data->date_of_deed) ? $dbrow->form_data->date_of_deed : '';
    $date_of_validity = isset($dbrow->form_data->date_of_validity) ? $dbrow->form_data->date_of_validity : '';
}
}

$category_of_regs = isset($dbrow->form_data->category_of_regs) ? $dbrow->form_data->category_of_regs : '';
$mobile = isset($dbrow->form_data->mobile) ? $dbrow->form_data->mobile:'';
$applicant_gender = isset($dbrow->form_data->applicant_gender) ? $dbrow->form_data->applicant_gender:'';
$caste = isset($dbrow->form_data->caste) ? $dbrow->form_data->caste:'';
$email = isset($dbrow->form_data->email) ? $dbrow->form_data->email:'';
$religion = isset($dbrow->form_data->religion) ? $dbrow->form_data->religion:'';
$date_of_birth = isset($dbrow->form_data->date_of_birth) ? $dbrow->form_data->date_of_birth:'';
$nationality = isset($dbrow->form_data->nationality) ? $dbrow->form_data->nationality:'';
$land_line = isset($dbrow->form_data->land_line) ? $dbrow->form_data->land_line:'';
$pan_card = isset($dbrow->form_data->pan_card) ? $dbrow->form_data->pan_card:'';
$gst_no = isset($dbrow->form_data->gst_no) ? $dbrow->form_data->gst_no:'';
$date_of_working_contractor = isset($dbrow->form_data->date_of_working_contractor) ? $dbrow->form_data->date_of_working_contractor:'';
$prev_reg_no = isset($dbrow->form_data->prev_reg_no) ? $dbrow->form_data->prev_reg_no:'';

if($applicant_type == 'Individual') {
$vill_town_city=isset($dbrow->form_data->communication_address->vill_town_city)?$dbrow->form_data->communication_address->vill_town_city:'';
$post_office=isset($dbrow->form_data->communication_address->post_office)?$dbrow->form_data->communication_address->post_office:'';
$pol_station=isset($dbrow->form_data->communication_address->pol_station)?$dbrow->form_data->communication_address->pol_station:'';
$pin_code=isset($dbrow->form_data->communication_address->pin_code)?$dbrow->form_data->communication_address->pin_code:'';
$district=isset($dbrow->form_data->communication_address->district)?$dbrow->form_data->communication_address->district:'';
$house_ward_no=isset($dbrow->form_data->communication_address->house_ward_no)?$dbrow->form_data->communication_address->house_ward_no:'';
$lane_road_loc=isset($dbrow->form_data->communication_address->lane_road_loc)?$dbrow->form_data->communication_address->lane_road_loc:'';
}
else {
$vill_town_city=isset($dbrow->form_data->authorised_signatory_address->vill_town_city)?$dbrow->form_data->authorised_signatory_address->vill_town_city:'';
$post_office=isset($dbrow->form_data->authorised_signatory_address->post_office)?$dbrow->form_data->authorised_signatory_address->post_office:'';
$pol_station=isset($dbrow->form_data->authorised_signatory_address->pol_station)?$dbrow->form_data->authorised_signatory_address->pol_station:'';
$pin_code=isset($dbrow->form_data->authorised_signatory_address->pin_code)?$dbrow->form_data->authorised_signatory_address->pin_code:'';
$district=isset($dbrow->form_data->authorised_signatory_address->district)?$dbrow->form_data->authorised_signatory_address->district:'';
$house_ward_no=isset($dbrow->form_data->authorised_signatory_address->house_ward_no)?$dbrow->form_data->authorised_signatory_address->house_ward_no:'';
$lane_road_loc=isset($dbrow->form_data->authorised_signatory_address->lane_road_loc)?$dbrow->form_data->authorised_signatory_address->lane_road_loc:'';

$vill_town_city_ro=isset($dbrow->form_data->regd_address->vill_town_city_ro)?$dbrow->form_data->regd_address->vill_town_city_ro:'';
$post_office_ro=isset($dbrow->form_data->regd_address->post_office_ro)?$dbrow->form_data->regd_address->post_office_ro:'';
$pol_station_ro=isset($dbrow->form_data->regd_address->pol_station_ro)?$dbrow->form_data->regd_address->pol_station_ro:'';
$pin_code_ro=isset($dbrow->form_data->regd_address->pin_code_ro)?$dbrow->form_data->regd_address->pin_code_ro:'';
$district_ro=isset($dbrow->form_data->regd_address->district_ro)?$dbrow->form_data->regd_address->district_ro:'';
$house_ward_no_ro=isset($dbrow->form_data->regd_address->house_ward_no_ro)?$dbrow->form_data->regd_address->house_ward_no_ro:'';
$lane_road_loc_ro=isset($dbrow->form_data->regd_address->lane_road_loc_ro)?$dbrow->form_data->regd_address->lane_road_loc_ro:'';
}
$all_ow_cnt=isset($dbrow->form_data->addresses_of_all_owners)?count($dbrow->form_data->addresses_of_all_owners):0;

$bank_name=isset($dbrow->form_data->bank_name)?$dbrow->form_data->bank_name:'';
$ifsc_code=isset($dbrow->form_data->ifsc_code)?$dbrow->form_data->ifsc_code:'';
$branch_name=isset($dbrow->form_data->branch_name)?$dbrow->form_data->branch_name:'';
$account_no=isset($dbrow->form_data->account_no)?$dbrow->form_data->account_no:'';

$p_vill_town_city=isset($dbrow->form_data->permanent_address->p_vill_town_city)?$dbrow->form_data->permanent_address->p_vill_town_city:'';
$p_post_office=isset($dbrow->form_data->permanent_address->p_post_office)?$dbrow->form_data->permanent_address->p_post_office:'';
$p_pol_station=isset($dbrow->form_data->permanent_address->p_pol_station)?$dbrow->form_data->permanent_address->p_pol_station:'';
$p_pin_code=isset($dbrow->form_data->permanent_address->p_pin_code)?$dbrow->form_data->permanent_address->p_pin_code:'';
$p_district=isset($dbrow->form_data->permanent_address->p_district)?$dbrow->form_data->permanent_address->p_district:'';
$p_house_ward_no=isset($dbrow->form_data->permanent_address->p_house_ward_no)?$dbrow->form_data->permanent_address->p_house_ward_no:'';
$p_lane_road_loc=isset($dbrow->form_data->permanent_address->p_lane_road_loc)?$dbrow->form_data->permanent_address->p_lane_road_loc:'';

$ow_cnt=isset($dbrow->form_data->ongoing_works)?count($dbrow->form_data->ongoing_works):0;
$qwe_cnt=isset($dbrow->form_data->quantities_of_works_executed)?count($dbrow->form_data->quantities_of_works_executed):0;
$we_cnt=isset($dbrow->form_data->works_executed)?count($dbrow->form_data->works_executed):0;
$kp_cnt=isset($dbrow->form_data->key_personnel)?count($dbrow->form_data->key_personnel):0;
$mo_cnt=isset($dbrow->form_data->machineries_owned)?count($dbrow->form_data->machineries_owned):0;
$ft_cnt=isset($dbrow->form_data->financial_turnover)?count($dbrow->form_data->financial_turnover):0;
$lh_cnt=isset($dbrow->form_data->litigation_history)?count($dbrow->form_data->litigation_history):0;

$fd_cnt=isset($dbrow->form_data->fd_details->bank_name)?1:0;
$ipo_cnt=isset($dbrow->form_data->ipo_details)?count($dbrow->form_data->ipo_details):0;

//enclosures
$photograph = isset($dbrow->form_data->enclosures->photograph) ? $dbrow->form_data->enclosures->photograph:'';
$proof_of_address = isset($dbrow->form_data->enclosures->proof_of_address) ? $dbrow->form_data->enclosures->proof_of_address : '';
$bank_solvency_cert = isset($dbrow->form_data->enclosures->bank_solvency_cert) ? $dbrow->form_data->enclosures->bank_solvency_cert : '';
$caste_cert = isset($dbrow->form_data->enclosures->caste_cert) ? $dbrow->form_data->enclosures->caste_cert : '';
$copy_pan_card = isset($dbrow->form_data->enclosures->copy_pan_card) ? $dbrow->form_data->enclosures->copy_pan_card : '';
$deed_maaa_cert = isset($dbrow->form_data->enclosures->deed_maaa_cert) ? $dbrow->form_data->enclosures->deed_maaa_cert : '';
$emp_provident_fund = isset($dbrow->form_data->enclosures->emp_provident_fund) ? $dbrow->form_data->enclosures->emp_provident_fund : '';
$firm_reg_cert = isset($dbrow->form_data->enclosures->firm_reg_cert) ? $dbrow->form_data->enclosures->firm_reg_cert : '';
$gst_reg_cert = isset($dbrow->form_data->enclosures->gst_reg_cert) ? $dbrow->form_data->enclosures->gst_reg_cert : '';
$key_personnel = isset($dbrow->form_data->enclosures->key_personnel) ? $dbrow->form_data->enclosures->key_personnel : '';
$labour_license = isset($dbrow->form_data->enclosures->labour_license) ? $dbrow->form_data->enclosures->labour_license : '';
$machinery_details = isset($dbrow->form_data->enclosures->machinery_details) ? $dbrow->form_data->enclosures->machinery_details : '';
$power_attorney = isset($dbrow->form_data->enclosures->power_attorney) ? $dbrow->form_data->enclosures->power_attorney : '';
$pvr_passport = isset($dbrow->form_data->enclosures->pvr_passport) ? $dbrow->form_data->enclosures->pvr_passport : '';
$quantities_work_cert = isset($dbrow->form_data->enclosures->quantities_work_cert) ? $dbrow->form_data->enclosures->quantities_work_cert : '';
$tax_clearance_cert = isset($dbrow->form_data->enclosures->tax_clearance_cert) ? $dbrow->form_data->enclosures->tax_clearance_cert : '';
$turnover_cert = isset($dbrow->form_data->enclosures->turnover_cert) ? $dbrow->form_data->enclosures->turnover_cert : '';
$work_completion_cert = isset($dbrow->form_data->enclosures->work_completion_cert) ? $dbrow->form_data->enclosures->work_completion_cert : '';
$regs_other_deptt = isset($dbrow->form_data->enclosures->regs_other_deptt) ? $dbrow->form_data->enclosures->regs_other_deptt : '';
$affidavit_unemployment = isset($dbrow->form_data->enclosures->affidavit_unemployment) ? $dbrow->form_data->enclosures->affidavit_unemployment : '';
$any_other_docs = isset($dbrow->form_data->enclosures->any_other_docs) ? $dbrow->form_data->enclosures->any_other_docs : '';

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
    .table td, .table th {
        font-size: 14px;
        padding: 2px;        
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/js/1.6.2/jQuery.print.min.js') ?>"></script>
<script type="text/javascript">   
    $(document).ready(function () {  
        
        var txn, clickedBtnId;
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
    });
    function alertMsg(type, msg) {
            Swal.fire({
                icon: type,
                text: msg,
            })
    }    
    function showLabourModal()
    {
        $("#resultModal").modal("show");
    }
    function showGstModal()
    {
        $("#resultModal1").modal("show");
    }
    function showCasteModal()
    {
        $("#resultModal2").modal("show");
    }
    function verifyLabour()
    {
        var reg_no = $('#reg_no').val();
        if(reg_no != '') {
        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
        $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?= base_url('spservices/registration_of_contractors/doc_validation/submit_labour_licence') ?>",
                    data: {
                        "reg_no": reg_no,
                        csrfName: csrfHash
                    },
                    beforeSend: function() {
                        $("#reg_no").val("");
                        $("#reg_no").attr("placeholder", "Please wait...");
                    },
                    success: function(res) {
                        if (res.status) {
                            let obj = JSON.parse(res.ret);
                            if(obj.data === null)
                            {
                                alertMsg('error', "No record found!!!");
                                //return;
                            }
                            $("#reg_no").attr("placeholder", "");
                            var table = `<table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                        <tr>
                                <td style="width:50%">Name <strong> : `+obj.data.initiated_data.user_name+`</strong> </td>
                                <td style="width:50%">Ref. No.<strong> : `+obj.data.initiated_data.appl_ref_no+`</strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Mode <strong> : `+obj.data.initiated_data.submission_mode+`</strong> </td>
                                <td style="width:50%">Location <strong> : `+obj.data.initiated_data.submission_location+`</strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Payment Date <strong> : `+obj.data.initiated_data.payment_date+`</strong> </td>
                                <td style="width:50%">Payment Ref No. <strong> : `+obj.data.initiated_data.reference_no+`</strong> </td>
                            </tr>
                        </tbody>
            </table>`;
                            $('#res_div').html(table);
                            $('#labSearch').hide();
                        } else {
                            alertMsg('error', res.msg);
                        } //End of if else
                    }
                });
            }
            else {
                alertMsg("error", "Please enter a Labour Reg. No.");
            }
    }

    function verifyGSTNo()
    {
        var gst_cert_no = $('#gst_cert_no').val();
        if(gst_cert_no != '') {
        $.ajax({
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    url: "<?= base_url('spservices/registration_of_contractors/doc_validation/submit_gst_cert') ?>",
                    data: {
                        "gst_cert_no": gst_cert_no
                    },
                    beforeSend: function() {
                        $("#gst_cert_no").val("");
                        $("#gst_cert_no").attr("placeholder", "Please wait...");
                    },
                    success: function(res) {
                        if (res.status) {
                            let obj = JSON.parse(res.ret);
                            if(obj.legalName === undefined || obj.legalName=='')
                            {
                                alertMsg('error', "No record found!!!");
                                return;
                            }
                            $("#gst_cert_no").attr("placeholder", "");
                            var table = `<table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                        <tr>
                                <td style="width:50%">Name <strong> : `+obj.legalName+`</strong> </td>
                                <td style="width:50%">GST No.<strong> : `+obj.gstin+`</strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Status <strong> : `+obj.status+`</strong> </td>
                                <td style="width:50%">Reg. Date <strong> : `+obj.registrationDate+`</strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Tax Payer Type <strong> : `+obj.taxPayerType+`</strong> </td>
                                <td style="width:50%">Category <strong> : `+obj.constitution+`</strong> </td>
                            </tr>
                        </tbody>
            </table>`;
                            $('#res_div1').html(table);
                            $('#gstSearch').hide();
                        } else {
                            alertMsg('error', res.msg);
                        } //End of if else
                    }
                });
            }
            else {
                alertMsg("error", "Please enter a GST No.");
            }
    }

    function verifyCaste()
    {
        var caste_cert_no = $('#caste_cert_no').val();
        if(caste_cert_no != '') {
        $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "<?= base_url('spservices/registration_of_contractors/doc_validation/submit_caste_cert') ?>",
                    data: {
                        "caste_cert_no": caste_cert_no
                    },
                    beforeSend: function() {
                        $("#caste_cert_no").val("");
                        $("#caste_cert_no").attr("placeholder", "Please wait...");
                    },
                    success: function(res) {
                        if (res.status) {
                            let obj = JSON.parse(res.ret);
                            if(obj.applicantName === undefined || obj.applicantName=='')
                            {
                                alertMsg('error', "No record found!!!");
                                return;
                            }
                            $("#caste_cert_no").attr("placeholder", "");
                            var table = `<table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                        <tr>
                                <td style="width:50%">Name <strong> : `+obj.applicantName+`</strong> </td>
                                <td style="width:50%">Caste<strong> : `+obj.caste+`</strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Status <strong> : `+obj.status+`</strong> </td>
                                <td style="width:50%"></td>
                            </tr>
                        </tbody>
            </table>`;
                            $('#res_div2').html(table);
                            $('#casteSearch').hide();
                        } else {
                            alertMsg('error', res.msg);
                        } //End of if else
                    }
                });
            }
            else {
                alertMsg("error", "Please enter a Caste Certificate No.");
            }
    }
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv">
            <div class="card-header" style="text-align: center; font-size: 20px; color: #000; font-family: georgia,serif; font-weight: bold">
                   Preview Of Application 
            </div>
            <div class="card-body" style="padding:5px">

                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td style="text-align: left; width: 25%">
                                <img src="<?=base_url('assets/frontend/images/assam_logo.png')?>" style="width: 80px; height: 100px">
                            </td>
                            <td class="text-center">
                                <h1 style="font-size: 22px; padding: 0px; margin: 0px; line-height: 33px; font-weight: bold; color: #00346c">
                                Contractor's Registration
                                </h1>
                            </td>
                            <td style="text-align: right; width: 25%">
                            <?php if($applicant_type == 'Individual') { ?>
                                <img src="<?=base_url($photograph)?>" style="width: 100px; height: 100px">
                            <?php } ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">
                                
                                <span style="float:right; font-size: 12px;">Date: <strong><?=$date?></strong></span>
                            </td>                                
                        </tr>
                    </tbody>
                </table>
                
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Details of the Applicant </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                        <tr>
                                <td style="width:50%">Department *<strong> : <?=$department_name ?></strong> </td>
                                <td style="width:50%">Category<strong> : <?=$category?></strong> [<?=$applicant_type?>] </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Zone <strong> : <?=$zone ?></strong> </td>
                                <td style="width:50%">Circle <strong> : <?=$circle?></strong> </td>
                            </tr>
                            
                            <?php if($category_of_regs != '') { ?>
                            <tr>
                            <td style="width:50%">Category of Registration<strong> : <?=$category_of_regs?></strong> </td>
                            </tr>
                            <?php } ?>
                            <?php if($applicant_type == 'Individual') { ?>
                            <tr>
                                <td style="width:50%">Name of the Applicant *<strong> : <?=$applicant_name ?></strong> </td>
                                <td style="width:50%">Fathers Name<strong> : <?=$father_husband_name?></strong> </td>
                            </tr>
                            <?php } else if($applicant_type == 'Proprietorship') { ?>
                                <tr>
                                <td style="width:50%">Authorised Signatory / Power of attorney<strong> : <?=$guardian_name?></strong> </td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                <td style="width:50%">Organization Name <strong> : <?=$applicant_name ?></strong> </td>
                                <td style="width:50%">Authorised Signatory / Power of attorney<strong> : <?=$guardian_name?></strong> </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td style="width:50%">Mobile Number<strong> : <?=$mobile?></strong> </td>
                                <td style="width:50%">E-Mail<strong> : <?=$email?></strong> </td>
                            </tr>
                            <?php if($applicant_type == 'Individual') { ?>
                             <tr>
                                <td>Caste<strong> : <?=$caste?></strong></td>
                                <td style="width:50%">Religion<strong> : <?=$religion?></strong> </td>
                                
                            </tr>
                            <tr>
                                <td>Date of birth<strong> : <?=$date_of_birth?></strong></td>
                                <td style="width:50%">Nationality<strong> : <?=$nationality?></strong> </td>
                                
                            </tr>
                            <tr>
                                <td>Gender<strong> : <?=$applicant_gender?></strong></td>
                            </tr>
                            <?php } else if($applicant_type != 'Proprietorship') { ?>
                                <tr>
                                <td>Date of deed<strong> : <?=$date_of_deed?></strong></td>
                                <td style="width:50%">Date of validity<strong> : <?=$date_of_validity?></strong> </td>
                                
                            </tr>
                            <?php } ?>
                            <tr>
                                <td style="width:50%">Phone<strong> : <?=$land_line?></strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">PAN<strong> : <?=$pan_card?></strong> </td>
                                <td style="width:50%">GST No.<strong> : <?=$gst_no?></strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Date from which working as contractor<strong> : <?=$date_of_working_contractor?></strong> </td>
                                <td style="width:50%">Previous Registration No.<strong> : <?=$prev_reg_no?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Bank Details </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">Bank<strong>: <?=$bank_name?> </strong> </td>
                                <td style="width:50%">IFSC<strong>: <?=$ifsc_code?> </strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Branch<strong>: <?=$branch_name?></strong> </td>
                                <td style="width:50%">A/c No.<strong>: <?=$account_no?> </strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Communication Address </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">House No. / Ward No.<strong>: <?=$house_ward_no?> </strong> </td>
                                <td style="width:50%">Lane / Road / Locality<strong>: <?=$lane_road_loc?> </strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Village / Town / City<strong>: <?=$vill_town_city?></strong> </td>
                                <td style="width:50%">Post office<strong>: <?=$post_office?> </strong> </td>
                            </tr>
                             <tr>
                                <td style="width:50%">Police Station<strong>: <?=$pol_station?></strong> </td>
                                <td style="width:50%">Pin Code<strong>: <?=$pin_code?> </strong> </td>
                            </tr>
                             <tr>
                                <td style="width:50%">District<strong>: <?=$district?> </strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                <?php if($p_district != '' && $p_pin_code!= '') { ?>
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Permanent Address </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">House No. / Ward No.<strong>: <?=$p_house_ward_no?> </strong> </td>
                                <td style="width:50%">Lane / Road / Locality<strong>: <?=$p_lane_road_loc?> </strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Village / Town / City<strong>: <?=$p_vill_town_city?></strong> </td>
                                <td style="width:50%">Post office<strong>: <?=$p_post_office?> </strong> </td>
                            </tr>
                             <tr>
                                <td style="width:50%">Police Station<strong>: <?=$p_pol_station?></strong> </td>
                                <td style="width:50%">Pin Code<strong>: <?=$p_pin_code?> </strong> </td>
                            </tr>
                             <tr>
                                <td style="width:50%">District<strong>: <?=$p_district?> </strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                <?php } ?>
                <?php if($applicant_type != 'Individual') { ?>
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Address of Regd. Office </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">House No. / Ward No.<strong>: <?=$house_ward_no_ro?> </strong> </td>
                                <td style="width:50%">Lane / Road / Locality<strong>: <?=$lane_road_loc_ro?> </strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Village / Town / City<strong>: <?=$vill_town_city_ro?></strong> </td>
                                <td style="width:50%">Post office<strong>: <?=$post_office_ro?> </strong> </td>
                            </tr>
                             <tr>
                                <td style="width:50%">Police Station<strong>: <?=$pol_station_ro?></strong> </td>
                                <td style="width:50%">Pin Code<strong>: <?=$pin_code_ro?> </strong> </td>
                            </tr>
                             <tr>
                                <td style="width:50%">District<strong>: <?=$district_ro?> </strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Addresses of all owners </legend>
                    <?php if ($all_ow_cnt!=0){ ?>
                    <table class="table" style="margin-top:0px; margin-bottom:0px;">
                        <tbody>
                            <tr>
                                <td>Sl no:</td>
                                <td>House/ Ward No.</td>
                                <td>Lane / Road / Locality</td>
                                <td>Village / Town / City</td>
                                <td>Post office</td>
                                <td>Police station</td>
                                <td>District</td>
                                <td>Pin code</td>
                            </tr>    
                            <?php for ($i = 0; $i < $all_ow_cnt; $i++){?>
                            <tr>
                                <td><strong><?=$i+1?></strong></td>
                                <td><strong><?=$dbrow->form_data->addresses_of_all_owners[$i]->house_no_ownership?></strong> </td>
                                <td><strong><?=$dbrow->form_data->addresses_of_all_owners[$i]->lane_road_ownership?></strong></td>
                                <td><strong><?=$dbrow->form_data->addresses_of_all_owners[$i]->vill_town_city_ownership?></strong></td>
                                <td><strong><?=$dbrow->form_data->addresses_of_all_owners[$i]->post_office_ownership?></strong> </td>
                                <td><strong><?=$dbrow->form_data->addresses_of_all_owners[$i]->police_station_ownership?></strong></td>
                                <td><strong><?=$dbrow->form_data->addresses_of_all_owners[$i]->district_ownership?></strong> </td>
                                <td><strong><?=$dbrow->form_data->addresses_of_all_owners[$i]->pin_code_ownership?></strong> </td>
                            </tr>
                            <?php
                             }
                            ?>

                        </tbody>
                    </table>
                     <?php
                      } //for if
                     ?>
                </fieldset>
                <?php } ?>
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Existing commitments and ongoing works </legend>
                    <?php if ($ow_cnt!=0){ ?>
                    <table class="table" style="margin-top:0px; margin-bottom:0px;">
                        <tbody>
                            <tr>
                                <td>Sl no:</td>
                                <td>Description of work</td>
                                <td>Place & State</td>
                                <td>Contract No. & Date</td>
                                <td>Employer Name</td>
                                <td>Value of Contract</td>
                                <td>W.O. Date</td>
                                <td>St. Completion date</td>
                                <td>Remaining Work</td>
                                <td>Anticipated date</td>
                            </tr>    
                            <?php for ($i = 0; $i < $ow_cnt; $i++){?>
                            <tr>
                                <td><strong><?=$i+1?></strong></td>
                                <td><strong><?=$dbrow->form_data->ongoing_works[$i]->desc_work?></strong> </td>
                                <td><strong><?=$dbrow->form_data->ongoing_works[$i]->place_state?></strong></td>
                                <td><strong><?=$dbrow->form_data->ongoing_works[$i]->contract_no_date?></strong></td>
                                <td><strong><?=$dbrow->form_data->ongoing_works[$i]->employer_name?></strong> </td>
                                <td><strong><?=$dbrow->form_data->ongoing_works[$i]->value_contract?></strong></td>
                                <td><strong><?=$dbrow->form_data->ongoing_works[$i]->wo_date?></strong> </td>
                                <td><strong><?=$dbrow->form_data->ongoing_works[$i]->st_completion_date?></strong> </td>
                                <td><strong><?=$dbrow->form_data->ongoing_works[$i]->work_value_remaining?></strong> </td>
                                <td><strong><?=$dbrow->form_data->ongoing_works[$i]->ant_date_completion?></strong> </td>
                            </tr>
                            <?php
                             }
                            ?>

                        </tbody>
                    </table>
                     <?php
                      } //for if
                     ?>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Works executed as prime contractor in the last 5 years </legend>
                    <?php if ($we_cnt!=0){ ?>
                    <table class="table" style="margin-top:0px; margin-bottom:0px;">
                        <tbody>
                            <tr>
                                <td>Sl no:</td>
                                <td>Project Name</td>
                                <td>Employer Name</td>
                                <td>Description</td>
                                <td>Contract/W.O. No.</td>
                                <td>Value of Contract</td>
                                <td>W.O. Date</td>
                                <td>St. Completion date</td>
                                <td>Actual date</td>
                                <td>Remarks</td>
                            </tr>    
                            <?php for ($i = 0; $i < $we_cnt; $i++){?>
                            <tr>
                                <td><strong><?=$i+1?></strong></td>
                                <td><strong><?=$dbrow->form_data->works_executed[$i]->project_name?></strong> </td>
                                <td><strong><?=$dbrow->form_data->works_executed[$i]->employer?></strong></td>
                                <td><strong><?=$dbrow->form_data->works_executed[$i]->work_description?></strong></td>
                                <td><strong><?=$dbrow->form_data->works_executed[$i]->con_wo_no?></strong> </td>
                                <td><strong><?=$dbrow->form_data->works_executed[$i]->value_contract_p?></strong></td>
                                <td><strong><?=$dbrow->form_data->works_executed[$i]->wo_date_p?></strong> </td>
                                <td><strong><?=$dbrow->form_data->works_executed[$i]->st_completion_date_p?></strong> </td>
                                <td><strong><?=$dbrow->form_data->works_executed[$i]->actual_completion_date?></strong> </td>
                                <td><strong><?=$dbrow->form_data->works_executed[$i]->remarks_reasons?></strong> </td>
                            </tr>
                            <?php
                             }
                            ?>

                        </tbody>
                    </table>
                     <?php
                      } //for if
                     ?>
                </fieldset>
                <?php if($deptt_code != 'WRD') { ?>
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Quantities of works executed as prime contractor in the last 5 years </legend>
                    <?php if ($qwe_cnt!=0){ ?>
                    <table class="table" style="margin-top:0px; margin-bottom:0px;">
                        <tbody>
                            <tr>
                                <td>Sl no:</td>
                                <td>Work Item</td>
                                <td>Unit</td>
                                <td>Financial Years</td>
                            </tr>    
                            <?php for ($i = 0; $i < $qwe_cnt; $i++){?>
                            <tr>
                                <td><strong><?=$i+1?></strong></td>
                                <td><strong><?=$dbrow->form_data->quantities_of_works_executed[$i]->work_item?></strong> </td>
                                <td><strong><?=$dbrow->form_data->quantities_of_works_executed[$i]->work_unit?></strong></td>
                                <td><strong><?=$dbrow->form_data->quantities_of_works_executed[$i]->fin_years?></strong></td>
                              
                            </tr>
                            <?php
                             }
                            ?>

                        </tbody>
                    </table>
                     <?php
                      } //for if
                     ?>
                </fieldset>
                <?php } ?>
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Key Personnel for works and administration </legend>
                    <?php if ($kp_cnt!=0){ ?>
                    <table class="table" style="margin-top:0px; margin-bottom:0px;">
                        <tbody>
                            <tr>
                                <td>Sl no:</td>
                                <td>Work Position</td>
                                <td>Name</td>
                                <td>Qualifications</td>
                                <td>Total Experience (Years)</td>
                                <td>Experience With Contractor (Years)</td>
                            </tr>    
                            <?php for ($i = 0; $i < $kp_cnt; $i++){?>
                            <tr>
                                <td><strong><?=$i+1?></strong></td>
                                <td><strong><?=$dbrow->form_data->key_personnel[$i]->work_position?></strong> </td>
                                <td><strong><?=$dbrow->form_data->key_personnel[$i]->emp_name?></strong></td>
                                <td><strong><?=$dbrow->form_data->key_personnel[$i]->emp_qualification?></strong></td>
                                <td><strong><?=$dbrow->form_data->key_personnel[$i]->total_exp?></strong></td>
                                <td><strong><?=$dbrow->form_data->key_personnel[$i]->with_contractor_exp?></strong></td>
                              
                            </tr>
                            <?php
                             }
                            ?>

                        </tbody>
                    </table>
                     <?php
                      } //for if
                     ?>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Machineries owned </legend>
                    <?php if ($mo_cnt!=0){ ?>
                    <table class="table" style="margin-top:0px; margin-bottom:0px;">
                        <tbody>
                            <tr>
                                <td>Sl no:</td>
                                <td>Name of the Equipment/ Machinery</td>
                                <td>Numbers Owned</td>
                                <td>Average Age/ Condition of the Equipment</td>
                                <td>Equipment Identification No.</td>
                            </tr>    
                            <?php for ($i = 0; $i < $mo_cnt; $i++){?>
                            <tr>
                                <td><strong><?=$i+1?></strong></td>
                                <td><strong><?=$dbrow->form_data->machineries_owned[$i]->machinery?></strong> </td>
                                <td><strong><?=$dbrow->form_data->machineries_owned[$i]->numbers_owned?></strong></td>
                                <td><strong><?=$dbrow->form_data->machineries_owned[$i]->avg_age_condition?></strong></td>
                                <td><strong><?=$dbrow->form_data->machineries_owned[$i]->equipment_reg_no?></strong></td>
                              
                            </tr>
                            <?php
                             }
                            ?>

                        </tbody>
                    </table>
                     <?php
                      } //for if
                     ?>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Financial Turnover in the last 5 years </legend>
                    <?php if ($ft_cnt!=0){ ?>
                    <table class="table" style="margin-top:0px; margin-bottom:0px;">
                        <tbody>
                            <tr>
                                <td>Sl no:</td>
                                <td>Financial Year</td>
                                <td>Turnover (Rs.)</td>
                            </tr>    
                            <?php for ($i = 0; $i < $ft_cnt; $i++){?>
                            <tr>
                                <td><strong><?=$i+1?></strong></td>
                                <td><strong><?=$dbrow->form_data->financial_turnover[$i]->fin_year_turnover?></strong> </td>
                                <td><strong><?=$dbrow->form_data->financial_turnover[$i]->turnover?></strong></td>
                              
                            </tr>
                            <?php
                             }
                            ?>

                        </tbody>
                    </table>
                     <?php
                      } //for if
                     ?>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Litigation History </legend>
                    <?php if ($lh_cnt!=0){ ?>
                    <table class="table" style="margin-top:0px; margin-bottom:0px;">
                        <tbody>
                            <tr>
                                <td>Sl no:</td>
                                <td>Employer</td>
                                <td>Cause of Dispute</td>
                                <td>Status</td>
                            </tr>    
                            <?php for ($i = 0; $i < $lh_cnt; $i++){?>
                            <tr>
                                <td><strong><?=$i+1?></strong></td>
                                <td><strong><?=$dbrow->form_data->litigation_history[$i]->employer_dispute?></strong> </td>
                                <td><strong><?=$dbrow->form_data->litigation_history[$i]->cause_of_dispute?></strong></td>
                                <td><strong><?=$dbrow->form_data->litigation_history[$i]->status?></strong></td>
                            </tr>
                            <?php
                             }
                            ?>

                        </tbody>
                    </table>
                     <?php
                      } //for if
                     ?>
                </fieldset>

                <?php if ($ipo_cnt!=0) { ?>
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">IPO Details </legend>
                    
                    <table class="table" style="margin-top:0px; margin-bottom:0px;">
                        <tbody>
                            <tr>
                                <td>IPO No.</td>
                                <td>Date</td>
                                <td>Amount</td>
                            </tr>
                            <?php for ($i = 0; $i < $ipo_cnt; $i++){?>    
                            <tr>
                                <td><strong><?=$dbrow->form_data->ipo_details[$i]->ipo_number?></strong> </td>
                                <td><strong><?=$dbrow->form_data->ipo_details[$i]->ipo_date?></strong></td>
                                <td><strong><?=$dbrow->form_data->ipo_details[$i]->ipo_amnt?></strong></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    <table class="table" style="margin-top:0px; margin-bottom:0px;">
                        <tbody>    
                            <tr>
                                <td>
                        <?php if (isset($dbrow->form_data->ipo_copy) && strlen($dbrow->form_data->ipo_copy)) { ?>
                            <a href="<?= base_url($dbrow->form_data->ipo_copy) ?>" title="View/Download" class="btn font-weight-bold text-success" target="_blank">
                                <span class="fa fa-download"></span> Attachment
                            </a>
                        <?php 
                        }
                        ?>
                        </td>
                        </tr>
                        </tbody>
                    </table>
                    
                </fieldset>
                <?php
                      }
                ?>

                <?php if ($fd_cnt!=0) { ?>
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Security Deposits </legend>
                    
                    <table class="table" style="margin-top:0px; margin-bottom:0px;">
                        <tbody>
                            <tr>
                                <td>Bank Name</td>
                                <td>A/C or Ref. No.</td>
                                <td>IFSC</td>
                                <td>Date</td>
                                <td>Amount</td>
                                <td>Attachment</td>
                            </tr>    
                            <tr>
                                <td><strong><?=$dbrow->form_data->fd_details->bank_name?></strong> </td>
                                <td><strong><?=$dbrow->form_data->fd_details->account_no?></strong></td>
                                <td><strong><?=$dbrow->form_data->fd_details->ifsc?></strong></td>
                                <td><strong><?=$dbrow->form_data->fd_details->validity?></strong></td>
                                <td><strong><?=$dbrow->form_data->fd_details->amount?></strong></td>
                                <td>
                                <?php if (isset($dbrow->form_data->fd_dd_copy) && strlen($dbrow->form_data->fd_dd_copy)) { ?>
                                        <a href="<?= base_url($dbrow->form_data->fd_dd_copy) ?>" title="View/Download" class="btn font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span>
                                        </a>
                                <?php }
                                ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                     
                </fieldset>
                <?php
                      }
                ?>

                <fieldset>
                <h5 class="text-center mt-3 text-success"><u><strong>ENCLOSURES</strong></u></h5>
                    <fieldset class="border border-success" style="margin-top:5px">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td colspan="3" style="color:#fd7e14; text-align: center; font-size: 14px">
                                        Note : For pdf of maximum 1MB is allowed. For Passport size photo only jpg, jpeg, and png of maximum 200KB is allowed
                                    </td>
                                </tr>
                                <tr>
                                    <th>Type of Enclosure</th>
                                    <th>File/Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($proof_of_address!=''){?>
                                <tr>
                                    <td>Proof of address</td>
                                    <td>
                                        <?php if (strlen($proof_of_address)) { ?>
                                            <a href="<?= base_url($proof_of_address) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>

                                <?php if($copy_pan_card!=''){?>
                                <tr>
                                    <td>PAN Card</td>
                                    <td>
                                        <?php if (strlen($copy_pan_card)) { ?>
                                            <a href="<?= base_url($copy_pan_card) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>

                                <?php if($caste_cert!=''){?>
                                <tr>
                                    <td>Caste Certificate</td>
                                    <td>
                                        <?php if (strlen($caste_cert)) { ?>
                                            <a href="<?= base_url($caste_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    <?php if($flg) {?>
                                    <button onclick="showCasteModal();" class="btn font-weight-bold text-primary">
                                     <span class="fa fa-info-circle">Verify</span>
                                    </button>
                                    <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?> 
                                
                                <?php if($pvr_passport!=''){?>
                                <tr>
                                    <td>Police Verification Report/Passport</td>
                                    <td>
                                        <?php if (strlen($pvr_passport)) { ?>
                                            <a href="<?= base_url($pvr_passport) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                
                                <?php if($gst_reg_cert!=''){?>
                                <tr>
                                    <td>GST Registration Certificate</td>
                                    <td>
                                        <?php if (strlen($gst_reg_cert)) { ?>
                                            <a href="<?= base_url($gst_reg_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    <?php if($flg) {?>
                                    <button onclick="showGstModal();" class="btn font-weight-bold text-primary">
                                     <span class="fa fa-info-circle">Verify</span>
                                    </button>
                                    <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                
                                <?php if($bank_solvency_cert!=''){?>
                                <tr>
                                    <td>Bank Solvency Certificate</td>
                                    <td>
                                        <?php if (strlen($bank_solvency_cert)) { ?>
                                            <a href="<?= base_url($bank_solvency_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                
                                <?php if($labour_license!=''){?>
                                <tr>
                                    <td>Labour License/ Trade License</td>
                                    <td>
                                        <?php if (strlen($labour_license)) { ?>
                                            <a href="<?= base_url($labour_license) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    <?php if($flg) {?>
                                    <button onclick="showLabourModal();" class="btn font-weight-bold text-primary">
                                     <span class="fa fa-info-circle">Verify</span>
                                    </button>
                                    <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                
                                <?php if($key_personnel!=''){?>
                                <tr>
                                    <td>Certificate of key personnel</td>
                                    <td>
                                        <?php if (strlen($key_personnel)) { ?>
                                            <a href="<?= base_url($key_personnel) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>

                                <?php if($emp_provident_fund!=''){?>
                                <tr>
                                    <td>Employment Provident Fund(E.P.F) with latest challan</td>
                                    <td>
                                        <?php if (strlen($emp_provident_fund)) { ?>
                                            <a href="<?= base_url($emp_provident_fund) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>

                                <?php if($turnover_cert!=''){?>
                                <tr>
                                    <td>Turnover Certificate from Chartered Accountant</td>
                                    <td>
                                        <?php if (strlen($turnover_cert)) { ?>
                                            <a href="<?= base_url($turnover_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>

                                <?php if($machinery_details!=''){?>
                                <tr>
                                    <td>Machinery Details</td>
                                    <td>
                                        <?php if (strlen($machinery_details)) { ?>
                                            <a href="<?= base_url($machinery_details) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>

                                <?php if($tax_clearance_cert!=''){?>
                                <tr>
                                    <td>Tax clearance certificates</td>
                                    <td>
                                        <?php if (strlen($tax_clearance_cert)) { ?>
                                            <a href="<?= base_url($tax_clearance_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>

                                <?php if($work_completion_cert!=''){?>
                                <tr>
                                    <td>Work orders and Work done/ Completion Certificate</td>
                                    <td>
                                        <?php if (strlen($work_completion_cert)) { ?>
                                            <a href="<?= base_url($work_completion_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>

                                <?php if($quantities_work_cert!=''){?>
                                <tr>
                                    <td>Quantities of works executed as prime contractor</td>
                                    <td>
                                        <?php if (strlen($quantities_work_cert)) { ?>
                                            <a href="<?= base_url($quantities_work_cert) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                
                                <?php if($regs_other_deptt!=''){?>
                                <tr>
                                    <td>Registration copy of Other Department</td>
                                    <td>
                                        <?php if (strlen($regs_other_deptt)) { ?>
                                            <a href="<?= base_url($regs_other_deptt) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>

                                <?php if($affidavit_unemployment!=''){?>
                                <tr>
                                    <td>Affidavit of Unemployment</td>
                                    <td>
                                        <?php if (strlen($affidavit_unemployment)) { ?>
                                            <a href="<?= base_url($affidavit_unemployment) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>

                                <?php if($any_other_docs!=''){?>
                                <tr>
                                    <td>Any other supporting documents</td>
                                    <td>
                                        <?php if (strlen($any_other_docs)) { ?>
                                            <a href="<?= base_url($any_other_docs) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                </tbody>
                        </table>
                        </fieldset>

            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <?php
                if(!$flg) { 
                if ($status == 'QS') { ?>
                    <button class="btn btn-success" id="finalBtn" type="button" onclick="location.href='<?= base_url('spservices/registration_of_contractors/registration/submit_query/' . $obj_id) ?>'">
                        <i class="fa fa-check"></i> Final Submit
                    </button>
                    <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/registration_of_contractors/attachments-section/' . $obj_id) ?>">
                        <i class="fa fa-angle-double-left"></i> Previous
                    </a>
                <?php } else { ?>
                    <?php if ($payment_status !== 'Y') { ?>
                        <a class="btn btn-primary frmbtn" id="DRAFT" href="<?= base_url('spservices/registration_of_contractors/attachments-section/' . $obj_id) ?>">
                            <i class="fa fa-angle-double-left"></i> Previous
                        </a>
                        <?php if ($user_type == 'user') { ?>
                            <a href="<?= base_url('spservices/registration_of_contractors/Payment/initiate/' . $obj_id) ?>" class="btn btn-warning">
                                <i class="fa fa-angle-double-right"></i> Make Payment
                            </a>
                        <?php } else { ?>
                            <a href="<?= base_url('spservices/registration_of_contractors/Payment/initiate/' . $obj_id) ?>" class="btn btn-warning">
                                <i class="fa fa-angle-double-right"></i> Make Payment
                            </a>
                <?php } //End of if else
                    }
                } //End of if
            } 
                ?>

            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>

<div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="margin:5% auto">
        <div class="modal-content">
            <div class="modal-body print-content" id="result_view" style="padding: 5px 15px;">
            <div class="card shadow-sm">
                <div class="card-body" style="padding:5px">

                    <h5 class="text-center mt-3 text-success"><u><strong>Labour Licence</strong></u></h5>

                    <fieldset class="border border-success" style="margin-top:10px" id="res_div">
                        <legend class="h6">Verification of Labour Licence</legend>
                        <div class="row form-group">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <label>Registration Number (e.g. CLL/2020/00004)</label>
                            <input type="text" class="form-control" name="reg_no" id="reg_no" value="" autocomplete="off"/>
                            <?= form_error("reg_no") ?>
                        </div>
                        <div class="col-md-3"></div>
                        </div>

                    </fieldset>
                   
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-success frmbtn" id="labSearch" type="button" onclick="verifyLabour();">
                        <i class="fa fa-angle-double-right">Search</i>
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-times">Cancel</i>
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
               
            </div><!--End of .modal-body-->
        </div>
    </div>
</div><!--End of #resultModal-->

<div class="modal fade" id="resultModal1" tabindex="-1" role="dialog" aria-labelledby="resultModal1Label">
    <div class="modal-dialog modal-lg" role="document" style="margin:5% auto">
        <div class="modal-content">
            <div class="modal-body print-content" id="result_view" style="padding: 5px 15px;">
            <div class="card shadow-sm">
                <div class="card-body" style="padding:5px">

                <h5 class="text-center mt-3 text-success"><u><strong>GST Certificate</strong></u></h5>

                <fieldset class="border border-success" style="margin-top:10px" id="res_div1">
                    <legend class="h6">Verification of GST Certificate</legend>
                    <div class="row form-group">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                        <label>GST Number (e.g. 29AAICA3918J1ZE)</label>
                        <input type="text" class="form-control" name="gst_cert_no" id="gst_cert_no" value="" required/>
                        <?= form_error("gst_cert_no") ?>
                    </div>
                    <div class="col-md-3"></div>
                    </div>

                </fieldset>
                                
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-success frmbtn" id="gstSearch" type="button" onclick="verifyGSTNo();">
                        <i class="fa fa-angle-double-right">Search</i>
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-times">Cancel</i>
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
               
            </div><!--End of .modal-body-->
        </div>
    </div>
</div><!--End of #resultModal-->

<div class="modal fade" id="resultModal2" tabindex="-1" role="dialog" aria-labelledby="resultModal2Label">
    <div class="modal-dialog modal-lg" role="document" style="margin:5% auto">
        <div class="modal-content">
            <div class="modal-body print-content" id="result_view" style="padding: 5px 15px;">
            <div class="card shadow-sm">
                <div class="card-body" style="padding:5px">

                <h5 class="text-center mt-3 text-success"><u><strong>Caste Certificate</strong></u></h5>

                <fieldset class="border border-success" style="margin-top:10px" id="res_div2">
                <legend class="h6">Verification of Caste Certificate</legend>
                    <div class="row form-group">
                    <div class="col-md-3"></div>
                    <div class="col-md-6">
                    <label>Certificate Number (e.g. 20220622-001-00480836)</label>
                        <input type="text" class="form-control" name="caste_cert_no" id="caste_cert_no" value="" required/>
                        <?= form_error("caste_cert_no") ?>
                    </div>
                    <div class="col-md-3"></div>
                    </div>

                </fieldset>
                                
                </div><!--End of .card-body -->

                <div class="card-footer text-center">
                    <button class="btn btn-success frmbtn" id="casteSearch" type="button" onclick="verifyCaste();">
                        <i class="fa fa-angle-double-right">Search</i>
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <i class="fa fa-times">Cancel</i>
                    </button>
                </div><!--End of .card-footer-->
            </div><!--End of .card-->
               
            </div><!--End of .modal-body-->
        </div>
    </div>
</div><!--End of #resultModal-->

