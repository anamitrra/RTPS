<?php
$obj_id = $dbrow->{'_id'}->{'$id'};
$rtps_trans_id = $dbrow->rtps_trans_id;    
$marriage_type = $dbrow->marriage_type;
$mt_id = $marriage_type->mt_id;
$marriage_act = $dbrow->marriage_act;
$applicant_prefix = $dbrow->applicant_prefix;
$applicant_first_name = $dbrow->applicant_first_name;
$applicant_middle_name = $dbrow->applicant_middle_name;
$applicant_last_name = $dbrow->applicant_last_name;
$applicant_gender = $dbrow->applicant_gender;
$applicant_mobile_number = $dbrow->applicant_mobile_number;
$applicant_email_id = $dbrow->applicant_email_id;
$office_district = $dbrow->office_district;
$sro_code = $dbrow->sro_code;

$bride_prefix = $dbrow->bride_prefix??'';
$bride_first_name = $dbrow->bride_first_name??'';
$bride_middle_name = $dbrow->bride_middle_name??'';
$bride_last_name = $dbrow->bride_last_name??'';                        
$bride_father_prefix = $dbrow->bride_father_prefix??'';
$bride_father_first_name = $dbrow->bride_father_first_name??'';
$bride_father_middle_name = $dbrow->bride_father_middle_name??'';
$bride_father_last_name = $dbrow->bride_father_last_name??'';                   
$bride_mother_prefix = $dbrow->bride_mother_prefix??'';
$bride_mother_first_name = $dbrow->bride_mother_first_name??'';
$bride_mother_middle_name = $dbrow->bride_mother_middle_name??'';
$bride_mother_last_name = $dbrow->bride_mother_last_name??'';

$bride_status = $dbrow->bride_status??'';
$bride_occupation = $dbrow->bride_occupation??'';
$bride_category = $dbrow->bride_category??'';
$bride_dob = $dbrow->bride_dob??'';
$bride_mobile_number = $dbrow->bride_mobile_number??'';
$bride_email_id = $dbrow->bride_email_id??'';
$bride_disability = $dbrow->bride_disability??'';
$bride_disability_type = $dbrow->bride_disability_type??'';

$bride_children = $dbrow->bride_children??array();
$bride_child_first_name = array();
$bride_child_middle_name = array();
$bride_child_last_name= array();
$bride_child_dob = array();
$bride_child_address = array();    
if(count($bride_children)) {
    foreach($bride_children as $bc) {
        //echo "OBJ : ".$bc_fname->patta_no."<br>";
        array_push($bride_child_first_name, $bc->bride_child_first_name);
        array_push($bride_child_middle_name, $bc->bride_child_middle_name);
        array_push($bride_child_last_name, $bc->bride_child_last_name);
        array_push($bride_child_dob, $bc->bride_child_dob);
        array_push($bride_child_address, $bc->bride_child_address);
    }//End of foreach()
}//End of if

$bride_dependents = $dbrow->bride_dependents??array();    
$bride_dependent_first_name = array();
$bride_dependent_middle_name = array();
$bride_dependent_last_name= array();
$bride_dependent_dob = array();
$bride_dependent_address = array();    
if(count($bride_dependents)) {
    foreach($bride_dependents as $bc) {
        array_push($bride_dependent_first_name, $bc->bride_dependent_first_name);
        array_push($bride_dependent_middle_name, $bc->bride_dependent_middle_name);
        array_push($bride_dependent_last_name, $bc->bride_dependent_last_name);
        array_push($bride_dependent_dob, $bc->bride_dependent_dob);
        array_push($bride_dependent_address, $bc->bride_dependent_address);
    }//End of foreach()
}//End of if

$bride_present_country = $dbrow->bride_present_country??'';
$bride_present_state_name = $dbrow->bride_present_state_name??'';
$bride_present_state_foreign = $dbrow->bride_present_state_foreign??'';
$bride_present_district = $dbrow->bride_present_district??'';
$bride_present_city = $dbrow->bride_present_city??'';
$bride_present_ps = $dbrow->bride_present_ps??'';
$bride_present_po = $dbrow->bride_present_po??'';
$bride_present_address1 = $dbrow->bride_present_address1??'';
$bride_present_address2 = $dbrow->bride_present_address2??'';
$bride_present_pin = $dbrow->bride_present_pin??'';
$bride_lac = $dbrow->bride_lac??'';
$bride_present_period_years = $dbrow->bride_present_period_years??'';
$bride_present_period_months = $dbrow->bride_present_period_months??'';

$bride_address_same = $dbrow->bride_address_same??'';
$bride_permanent_country = $dbrow->bride_permanent_country??'';
$bride_permanent_state = $dbrow->bride_permanent_state??'';
$bride_permanent_state_foreign = $dbrow->bride_permanent_state_foreign??'';
$bride_permanent_district = $dbrow->bride_permanent_district??'';
$bride_permanent_city = $dbrow->bride_permanent_city??'';
$bride_permanent_ps = $dbrow->bride_permanent_ps??'';
$bride_permanent_po = $dbrow->bride_permanent_po??'';
$bride_permanent_address1 = $dbrow->bride_permanent_address1??'';
$bride_permanent_address2 = $dbrow->bride_permanent_address2??'';
$bride_permanent_pin = $dbrow->bride_permanent_pin??'';

$groom_prefix = $dbrow->groom_prefix??'';
$groom_first_name = $dbrow->groom_first_name??'';
$groom_middle_name = $dbrow->groom_middle_name??'';
$groom_last_name = $dbrow->groom_last_name??'';                        
$groom_father_prefix = $dbrow->groom_father_prefix??'';
$groom_father_first_name = $dbrow->groom_father_first_name??'';
$groom_father_middle_name = $dbrow->groom_father_middle_name??'';
$groom_father_last_name = $dbrow->groom_father_last_name??'';                   
$groom_mother_prefix = $dbrow->groom_mother_prefix??'';
$groom_mother_first_name = $dbrow->groom_mother_first_name??'';
$groom_mother_middle_name = $dbrow->groom_mother_middle_name??'';
$groom_mother_last_name = $dbrow->groom_mother_last_name??'';

$groom_status = $dbrow->groom_status??'';
$groom_occupation = $dbrow->groom_occupation??'';
$groom_category = $dbrow->groom_category??'';
$groom_dob = $dbrow->groom_dob??'';
$groom_mobile_number = $dbrow->groom_mobile_number??'';
$groom_email_id = $dbrow->groom_email_id??'';
$groom_disability = $dbrow->groom_disability??'';
$groom_disability_type = $dbrow->groom_disability_type??'';
   
$groom_children = $dbrow->groom_children??array();    
$groom_child_first_name = array();
$groom_child_middle_name = array();
$groom_child_last_name= array();
$groom_child_dob = array();
$groom_child_address = array();    
if(count($groom_children)) {
    foreach($groom_children as $bc) {
        //echo "OBJ : ".$bc_fname->patta_no."<br>";
        array_push($groom_child_first_name, $bc->groom_child_first_name);
        array_push($groom_child_middle_name, $bc->groom_child_middle_name);
        array_push($groom_child_last_name, $bc->groom_child_last_name);
        array_push($groom_child_dob, $bc->groom_child_dob);
        array_push($groom_child_address, $bc->groom_child_address);
    }//End of foreach()
}//End of if

$groom_dependents = $dbrow->groom_dependents??array();    
$groom_dependent_first_name = array();
$groom_dependent_middle_name = array();
$groom_dependent_last_name= array();
$groom_dependent_dob = array();
$groom_dependent_address = array();    
if(count($groom_dependents)) {
    foreach($groom_dependents as $bc) {
        //echo "OBJ : ".$bc_fname->patta_no."<br>";
        array_push($groom_dependent_first_name, $bc->groom_dependent_first_name);
        array_push($groom_dependent_middle_name, $bc->groom_dependent_middle_name);
        array_push($groom_dependent_last_name, $bc->groom_dependent_last_name);
        array_push($groom_dependent_dob, $bc->groom_dependent_dob);
        array_push($groom_dependent_address, $bc->groom_dependent_address);
    }//End of foreach()
}//End of if

$groom_present_country = $dbrow->groom_present_country??'';
$groom_present_state_name = $dbrow->groom_present_state_name??'';
$groom_present_state_foreign = $dbrow->groom_present_state_foreign??'';
$groom_present_district = $dbrow->groom_present_district??'';
$groom_present_city = $dbrow->groom_present_city??'';
$groom_present_ps = $dbrow->groom_present_ps??'';
$groom_present_po = $dbrow->groom_present_po??'';
$groom_present_address1 = $dbrow->groom_present_address1??'';
$groom_present_address2 = $dbrow->groom_present_address2??'';
$groom_present_pin = $dbrow->groom_present_pin??'';
$groom_lac = $dbrow->groom_lac??'';
$groom_present_period_years = $dbrow->groom_present_period_years??'';
$groom_present_period_months = $dbrow->groom_present_period_months??'';

$groom_address_same = $dbrow->groom_address_same??'';
$groom_permanent_country = $dbrow->groom_permanent_country??'';
$groom_permanent_state = $dbrow->groom_permanent_state??'';
$groom_permanent_state_foreign = $dbrow->groom_permanent_state_foreign??'';
$groom_permanent_district = $dbrow->groom_permanent_district??'';
$groom_permanent_city = $dbrow->groom_permanent_city??'';
$groom_permanent_ps = $dbrow->groom_permanent_ps??'';
$groom_permanent_po = $dbrow->groom_permanent_po??'';
$groom_permanent_address1 = $dbrow->groom_permanent_address1??'';
$groom_permanent_address2 = $dbrow->groom_permanent_address2??'';
$groom_permanent_pin = $dbrow->groom_permanent_pin??'';

$bride_idproof_type = $dbrow->bride_idproof_type;
$bride_idproof = $dbrow->bride_idproof;
$groom_idproof_type = $dbrow->groom_idproof_type;
$groom_idproof = $dbrow->groom_idproof;
$bride_ageproof_type = $dbrow->bride_ageproof_type;
$bride_ageproof = $dbrow->bride_ageproof;
$marriage_notice_type = $dbrow->marriage_notice_type;
$marriage_notice = $dbrow->marriage_notice;
$groom_ageproof_type = $dbrow->groom_ageproof_type;
$groom_ageproof = $dbrow->groom_ageproof;
$bride_presentaddressproof_type = $dbrow->bride_presentaddressproof_type;
$bride_presentaddressproof = $dbrow->bride_presentaddressproof;
$bride_permanentaddressproof_type = $dbrow->bride_permanentaddressproof_type;
$bride_permanentaddressproof = $dbrow->bride_permanentaddressproof;
$groom_presentaddressproof_type = $dbrow->groom_presentaddressproof_type;
$groom_presentaddressproof = $dbrow->groom_presentaddressproof;
$groom_permanentaddressproof_type = $dbrow->groom_permanentaddressproof_type;
$groom_permanentaddressproof = $dbrow->groom_permanentaddressproof;
$bride_sign_type = $dbrow->bride_sign_type;
$bride_sign = $dbrow->bride_sign;
$groom_sign_type = $dbrow->groom_sign_type;
$groom_sign = $dbrow->groom_sign;
$declaration_certificate_type = $dbrow->declaration_certificate_type;
$declaration_certificate = $dbrow->declaration_certificate;
$marriage_card_type = $dbrow->marriage_card_type;
$marriage_card = $dbrow->marriage_card;
$status = $dbrow->status;
$payment_status = $dbrow->payment_status??"UNPAID";
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
    });
</script>
        
<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv" style="background:#FBFBFB">
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                   <?=$service_name?> 
            </div>
            <div class="card-body" style="padding:5px">                
                <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">Applicant&apos;s details/আবেদনকাৰীৰ সবিশেষ</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Marriage type/বিবাহৰ প্ৰকাৰ<br><strong><?=$marriage_type->mt_name?></strong> </td>
                                <td>Marriage Act/ বিবাহ আইন<br><strong><?=$marriage_act->ma_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>
                                    Applicant&apos;s Name /আবেদনকাৰীৰ নাম<br>
                                    <strong><?=$applicant_prefix.' '.$applicant_first_name.' '.$applicant_middle_name.' '.$applicant_last_name?></strong> 
                                </td>
                                <td>Gender / লিংগ<br><strong><?=$applicant_gender?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mobile Number / দুৰভাষ ( মবাইল )<br><strong><?=$applicant_mobile_number?></strong> </td>
                                <td>E-Mail / ই-মেইল<br><strong><?=$applicant_email_id?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">Applied To/আবেদন কৰা কাৰ্য্যালয়</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>District / জিলা<br><strong><?=$dbrow->district_name??''?></strong> </td>
                                <td>Office/কাৰ্য্যালয়<br><strong><?=$dbrow->office_name??''?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success" style="margin-top:40px; display: <?=($mt_id == 2)?'block':'none'?>">
                    <legend class="h5">(In case Marriage Type is selected as "Marriage already performed")</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Relationship of Parties before Marriage (if any)/ বিবাহৰ আগতে পাৰ্টিৰ সম্পৰ্ক<br><strong><?=$dbrow->relationship_before??''?></strong> </td>
                                <td>Date of Marriage Ceremony/ বিবাহ অনুষ্ঠানৰ তাৰিখ<br><strong><?=$dbrow->ceremony_date??''?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">Bride Details/কইনাৰ বিৱৰণ</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Bride Name/কইনা নাম<br><strong><?=$bride_prefix.' '.$bride_first_name.' '.$bride_middle_name.' '.$bride_last_name?></strong> </td>
                                <td>Father's Name/পিতাৰ নাম<br><strong><?=$bride_father_prefix.' '.$bride_father_first_name.' '.$bride_father_middle_name.' '.$bride_father_last_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mother's Name/মাতৃৰ নাম<br><strong><?=$bride_mother_prefix.' '.$bride_mother_first_name.' '.$bride_mother_middle_name.' '.$bride_mother_last_name?></strong> </td>
                                <td>Bride Status/কইনাৰ স্থিতি<br><strong><?=$bride_status?></strong> </td>
                            </tr>
                            <tr>
                                <td>Occupation/বৃত্তি<br><strong><?=$bride_occupation?></strong> </td>
                                <td>Category/শ্ৰেণী<br><strong><?=$bride_category?></strong> </td>
                            </tr>
                            <tr>
                                <td>Date of Birth/জন্ম তাৰিখ<br><strong><?=$bride_dob?></strong> </td>
                                <td>Mobile Number/মোবাইল নম্বৰ<br><strong><?=$bride_mobile_number?></strong> </td>
                            </tr>
                            <tr>
                                <td>E-Mail/ই-মেইল<br><strong><?=$bride_email_id?></strong> </td>
                                <td>Person with Disability/অক্ষম ব্যক্তি<br><strong><?=($bride_disability == "1")?'Yes':'No'?></strong> </td>
                            </tr>
                            <tr>
                                <td colspan="2">If Yes/ যদি হয়<br><strong><?=$bride_disability_type?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                
                <?php
                $brideChild_firstNames = (isset($bride_child_first_name) && is_array($bride_child_first_name)) ? count($bride_child_first_name) : 0;
                if ($brideChild_firstNames > 0) { ?>
                <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">Names of children from earlier marriage(if any)/পূৰ্বৰ বিবাহৰ সন্তানৰ নাম</legend>
                    <table class="table table-bordered" id="bride_children_tbl">
                        <thead>
                            <tr>
                                <th>First name</th>
                                <th>Middle name</th>
                                <th>Last name</th>
                                <th>Date of birth</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < $brideChild_firstNames; $i++) { ?>
                                <tr>
                                    <td><?= $bride_child_first_name[$i] ?></td>
                                    <td><?= $bride_child_middle_name[$i] ?></td>
                                    <td><?= $bride_child_last_name[$i] ?></td>
                                    <td><?= $bride_child_dob[$i] ?></td>
                                    <td><?= $bride_child_address[$i] ?></td>
                                </tr>
                            <?php }//End of for() ?>                                
                        </tbody>
                    </table>
                </fieldset>
                <?php } //End of if
                
                $brideDependent_firstNames = (isset($bride_dependent_first_name) && is_array($bride_dependent_first_name)) ? count($bride_dependent_first_name) : 0;
                if ($brideDependent_firstNames > 0) { ?>                                    
                <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">Name of Dependent(if any)/নিৰ্ভৰশীলৰ নাম</legend>
                    <table class="table table-bordered" id="bride_dependents_tbl">
                        <thead>
                            <tr>
                                <th>First name</th>
                                <th>Middle name</th>
                                <th>Last name</th>
                                <th>Date of birth</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < $brideDependent_firstNames; $i++) { ?>
                                <tr>
                                    <td><?= $bride_dependent_first_name[$i] ?></td>
                                    <td><?= $bride_dependent_middle_name[$i] ?></td>
                                    <td><?= $bride_dependent_last_name[$i] ?></td>
                                    <td><?= $bride_dependent_dob[$i] ?></td>
                                    <td><?= $bride_dependent_address[$i] ?></td>
                                </tr>
                            <?php }//End of for() ?>                                
                        </tbody>
                    </table>
                </fieldset>
                <?php }//End of if else  ?>
                
                <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">Bride Present Address/কইনাৰ বৰ্তমান ঠিকনা</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Country/দেশ<br><strong><?=$bride_present_country?></strong> </td>
                                <td>State/ৰাজ্য<br><strong><?=$bride_present_state_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>State/ৰাজ্য/Province/প্ৰদেশ<br><strong><?=$bride_present_state_foreign?></strong> </td>
                                <td>District/জিলা<br><strong><?=$bride_present_district?></strong> </td>
                            </tr>
                            <tr>
                                <td>Village/Town/City/গাওঁ/চহৰ<br><strong><?=$bride_present_city?></strong> </td>
                                <td>Police Station/থানা<br><strong><?=$bride_present_ps?></strong> </td>
                            </tr>
                            <tr>
                                <td>Post Office/ডাকঘৰ<br><strong><?=$bride_present_po?></strong> </td>
                                <td>Address Line 1/ঠিকনা ৰেখা ১<br><strong><?=$bride_present_address1?></strong> </td>
                            </tr>
                            <tr>
                                <td>Address Line 2/ঠিকনা ৰেখা ২<br><strong><?=$bride_present_address2?></strong> </td>
                                <td>Pin Code/পিন<br><strong><?=$bride_present_pin?></strong> </td>
                            </tr>
                            <tr>
                                <td>LAC/ বিধান সভা সমষ্টি<br><strong><?=$bride_lac->lac_name??''?></strong> </td>
                                <td>Residency period at present address<br><strong><?=$bride_present_period_years.' year(s) '.$bride_present_period_months.' month(s)'?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">Bride Permanent Address/কইনাৰ স্হায়ী ঠিকনা</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td colspan="2">Same as Permanent Address / স্হায়ী ঠিকনাৰ সৈতে একেনে ? <strong><?=$bride_address_same?></strong> </td>
                            </tr>
                            <tr>
                                <td>Country/দেশ<br><strong><?=$bride_permanent_country?></strong> </td>
                                <td>State/ৰাজ্য<br><strong><?=$dbrow->bride_permanent_state_name??''?></strong> </td>
                            </tr>
                            <tr>
                                <td>State/ৰাজ্য/Province/প্ৰদেশ<br><strong><?=$bride_permanent_state_foreign?></strong> </td>
                                <td>District/জিলা<br><strong><?=$bride_permanent_district?></strong> </td>
                            </tr>
                            <tr>
                                <td>Village/Town/City/গাওঁ/চহৰ<br><strong><?=$bride_permanent_city?></strong> </td>
                                <td>Police Station/থানা<br><strong><?=$bride_permanent_ps?></strong> </td>
                            </tr>
                            <tr>
                                <td>Post Office/ডাকঘৰ<br><strong><?=$bride_permanent_po?></strong> </td>
                                <td>Address Line 1/ঠিকনা ৰেখা ১<br><strong><?=$bride_permanent_address1?></strong> </td>
                            </tr>
                            <tr>
                                <td>Address Line 2/ঠিকনা ৰেখা ২<br><strong><?=$bride_permanent_address2?></strong> </td>
                                <td>Pin Code/পিন<br><strong><?=$bride_permanent_pin?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">Bridegroom Details/দৰাৰ বিৱৰণ</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Bridegroom Name/দৰাৰ নাম<br><strong><?=$groom_prefix.' '.$groom_first_name.' '.$groom_middle_name.' '.$groom_last_name?></strong> </td>
                                <td>Father's Name/পিতাৰ নাম<br><strong><?=$groom_father_prefix.' '.$groom_father_first_name.' '.$groom_father_middle_name.' '.$groom_father_last_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>Mother's Name/মাতৃৰ নাম<br><strong><?=$groom_mother_prefix.' '.$groom_mother_first_name.' '.$groom_mother_middle_name.' '.$groom_mother_last_name?></strong> </td>
                                <td>Bridegroom Status/দৰাৰ স্থিতি<br><strong><?=$groom_status?></strong> </td>
                            </tr>
                            <tr>
                                <td>Occupation/বৃত্তি<br><strong><?=$groom_occupation?></strong> </td>
                                <td>Category/শ্ৰেণী<br><strong><?=$groom_category?></strong> </td>
                            </tr>
                            <tr>
                                <td>Date of Birth/জন্ম তাৰিখ<br><strong><?=$groom_dob?></strong> </td>
                                <td>Mobile Number/মোবাইল নম্বৰ<br><strong><?=$groom_mobile_number?></strong> </td>
                            </tr>
                            <tr>
                                <td>E-Mail/ই-মেইল<br><strong><?=$groom_email_id?></strong> </td>
                                <td>Person with Disability/অক্ষম ব্যক্তি<br><strong><?=($groom_disability === "1")?'Yes':'No'?></strong> </td>
                            </tr>
                            <tr>
                                <td colspan="2">If Yes/ যদি হয়<br><strong><?=$groom_disability_type?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                            
                <?php
                $groomChild_firstNames = (isset($groom_child_first_name) && is_array($groom_child_first_name)) ? count($groom_child_first_name) : 0;
                if ($groomChild_firstNames > 0) { ?>
                <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">Names of children from earlier marriage(if any)/পূৰ্বৰ বিবাহৰ সন্তানৰ নাম</legend>
                    <table class="table table-bordered" id="groom_children_tbl">
                        <thead>
                            <tr>
                                <th>First name</th>
                                <th>Middle name</th>
                                <th>Last name</th>
                                <th>Date of birth</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>                            
                            <?php for ($i = 0; $i < $groomChild_firstNames; $i++) { ?>
                            <tr>
                                <td><?= $groom_child_first_name[$i] ?></td>
                                <td><?= $groom_child_middle_name[$i] ?></td>
                                <td><?= $groom_child_last_name[$i] ?></td>
                                <td><?= $groom_child_dob[$i] ?></td>
                                <td><?= $groom_child_address[$i] ?></td>
                            </tr>
                            <?php }//End of for() ?>
                        </tbody>
                    </table>
                </fieldset>
                <?php }//End of if else
                
                $groomDependent_firstNames = (isset($groom_dependent_first_name) && is_array($groom_dependent_first_name)) ? count($groom_dependent_first_name) : 0;
                if ($groomDependent_firstNames > 0) { ?>
                <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">Name of Dependent(if any)/নিৰ্ভৰশীলৰ নাম</legend>
                    <table class="table table-bordered" id="groom_dependents_tbl">
                        <thead>
                            <tr>
                                <th>First name</th>
                                <th>Middle name</th>
                                <th>Last name</th>
                                <th>Date of birth</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for ($i = 0; $i < $groomDependent_firstNames; $i++) { ?>
                            <tr>
                                <td><?= $groom_dependent_first_name[$i] ?></td>
                                <td><?= $groom_dependent_middle_name[$i] ?></td>
                                <td><?= $groom_dependent_last_name[$i] ?></td>
                                <td><?= $groom_dependent_dob[$i] ?></td>
                                <td><?= $groom_dependent_address[$i] ?></td>
                            </tr>
                            <?php }//End of for() ?>
                        </tbody>
                    </table>
                </fieldset>
                <?php }//End of if else ?>
                
                <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">Bridegroom Present Address/দৰাৰ বৰ্তমান ঠিকনা</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Country/দেশ<br><strong><?=$groom_present_country?></strong> </td>
                                <td>State/ৰাজ্য<br><strong><?=$groom_present_state_name?></strong> </td>
                            </tr>
                            <tr>
                                <td>State/ৰাজ্য/Province/প্ৰদেশ<br><strong><?=$groom_present_state_foreign?></strong> </td>
                                <td>District/জিলা<br><strong><?=$groom_present_district?></strong> </td>
                            </tr>
                            <tr>
                                <td>Village/Town/City/গাওঁ/চহৰ<br><strong><?=$groom_present_city?></strong> </td>
                                <td>Police Station/থানা<br><strong><?=$groom_present_ps?></strong> </td>
                            </tr>
                            <tr>
                                <td>Post Office/ডাকঘৰ<br><strong><?=$groom_present_po?></strong> </td>
                                <td>Address Line 1/ঠিকনা ৰেখা ১<br><strong><?=$groom_present_address1?></strong> </td>
                            </tr>
                            <tr>
                                <td>Address Line 2/ঠিকনা ৰেখা ২<br><strong><?=$groom_present_address2?></strong> </td>
                                <td>Pin Code/পিন<br><strong><?=$groom_present_pin?></strong> </td>
                            </tr>
                            <tr>
                                <td>LAC/ বিধান সভা সমষ্টি<br><strong><?=$groom_lac->lac_name??''?></strong> </td>
                                <td>Residency period at present address<br><strong><?=$groom_present_period_years.' year(s) '.$groom_present_period_months.' month(s)'?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                
                <fieldset class="border border-success" style="margin-top:40px">
                    <legend class="h5">Bridegroom Permanent Address/দৰাৰ স্হায়ী ঠিকনা</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td colspan="2">Same as Permanent Address / স্হায়ী ঠিকনাৰ সৈতে একেনে ? <strong><?=$groom_address_same?></strong> </td>
                            </tr>
                            <tr>
                                <td>Country/দেশ<br><strong><?=$groom_permanent_country?></strong> </td>
                                <td>State/ৰাজ্য<br><strong><?=$dbrow->groom_permanent_state_name??''?></strong> </td>
                            </tr>
                            <tr>
                                <td>State/ৰাজ্য/Province/প্ৰদেশ<br><strong><?=$groom_permanent_state_foreign?></strong> </td>
                                <td>District/জিলা<br><strong><?=$groom_permanent_district?></strong> </td>
                            </tr>
                            <tr>
                                <td>Village/Town/City/গাওঁ/চহৰ<br><strong><?=$groom_permanent_city?></strong> </td>
                                <td>Police Station/থানা<br><strong><?=$groom_permanent_ps?></strong> </td>
                            </tr>
                            <tr>
                                <td>Post Office/ডাকঘৰ<br><strong><?=$groom_permanent_po?></strong> </td>
                                <td>Address Line 1/ঠিকনা ৰেখা ১<br><strong><?=$groom_permanent_address1?></strong> </td>
                            </tr>
                            <tr>
                                <td>Address Line 2/ঠিকনা ৰেখা ২<br><strong><?=$groom_permanent_address2?></strong> </td>
                                <td>Pin Code/পিন<br><strong><?=$groom_permanent_pin?></strong> </td>
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
                                <td>Identity proof of Bride/Wife</td>
                                <td style="font-weight:bold"><?=$bride_idproof_type?></td>
                                <td>
                                    <?php if(strlen($bride_idproof)){ ?>
                                        <a href="<?=base_url($bride_idproof)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Identity proof of Groom/Husband</td>
                                <td style="font-weight:bold"><?=$groom_idproof_type?></td>
                                <td>
                                    <?php if(strlen($groom_idproof)){ ?>
                                        <a href="<?=base_url($groom_idproof)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Age proof of Bride/Wife</td>
                                <td style="font-weight:bold"><?=$bride_ageproof_type?></td>
                                <td>
                                    <?php if(strlen($bride_ageproof)){ ?>
                                        <a href="<?=base_url($bride_ageproof)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Marriage Notice</td>
                                <td style="font-weight:bold"><?=$marriage_notice_type?></td>
                                <td>
                                    <?php if(strlen($marriage_notice)){ ?>
                                        <a href="<?=base_url($marriage_notice)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Age proof of Groom/Husband</td>
                                <td style="font-weight:bold"><?=$groom_ageproof_type?></td>
                                <td>
                                    <?php if(strlen($groom_ageproof)){ ?>
                                        <a href="<?=base_url($groom_ageproof)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Present Address Proof of Bride/Wife</td>
                                <td style="font-weight:bold"><?=$bride_presentaddressproof_type?></td>
                                <td>
                                    <?php if(strlen($bride_presentaddressproof)){ ?>
                                        <a href="<?=base_url($bride_presentaddressproof)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Permanent Address Proof of Bride/Wife</td>
                                <td style="font-weight:bold"><?=$bride_permanentaddressproof_type?></td>
                                <td>
                                    <?php if(strlen($bride_permanentaddressproof)){ ?>
                                        <a href="<?=base_url($bride_permanentaddressproof)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Present Address Proof of Groom/Husband</td>
                                <td style="font-weight:bold"><?=$groom_presentaddressproof_type?></td>
                                <td>
                                    <?php if(strlen($groom_presentaddressproof)){ ?>
                                        <a href="<?=base_url($groom_presentaddressproof)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Permanent Address Proof of Groom/Husband</td>
                                <td style="font-weight:bold"><?=$groom_permanentaddressproof_type?></td>
                                <td>
                                    <?php if(strlen($groom_permanentaddressproof)){ ?>
                                        <a href="<?=base_url($groom_permanentaddressproof)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Signature of Wife</td>
                                <td style="font-weight:bold"><?=$bride_sign_type?></td>
                                <td>
                                    <?php if(strlen($bride_sign)){ ?>
                                        <a href="<?=base_url($bride_sign)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Signature of Husband</td>
                                <td style="font-weight:bold"><?=$groom_sign_type?></td>
                                <td>
                                    <?php if(strlen($groom_sign)){ ?>
                                        <a href="<?=base_url($groom_sign)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Declaration Certificate by Parties</td>
                                <td style="font-weight:bold"><?=$declaration_certificate_type?></td>
                                <td>
                                    <?php if(strlen($declaration_certificate)){ ?>
                                        <a href="<?=base_url($declaration_certificate)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Marriage Card of Both Parties (if available)</td>
                                <td style="font-weight:bold"><?=$marriage_card_type?></td>
                                <td>
                                    <?php if(strlen($marriage_card)){ ?>
                                        <a href="<?=base_url($marriage_card)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
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
                <?php if($status === 'DRAFT') { ?>
                    <a href="<?=base_url('spservices/marriageregistration_landhub/applicantdetails/index/'.$obj_id)?>" class="btn btn-primary">
                        <i class="fa fa-pencil"></i> Edit
                    </a>
                <?php } ?>
                <button class="btn btn-warning" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <?php if($payment_status !== 'PAID') { ?>
                    <a href="<?=base_url('spservices/marriageregistration_landhub/payment/initiate/'.$obj_id)?>" class="btn btn-success">
                        <i class="fa fa-angle-double-right"></i> Make Payment
                    </a>
                <?php } ?>
                <?php if($status === 'QS') { ?>
                    <a href="<?=base_url('spservices/marriageregistration_landhub/query/submit/'.$obj_id)?>" class="btn btn-success">
                        <i class="fa fa-angle-double-right"></i> Submit
                    </a>
                <?php } ?>                    
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>