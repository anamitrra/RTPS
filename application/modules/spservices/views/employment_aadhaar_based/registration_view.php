<?php
    $aadhaar_number_virtual_id = isset($dbrow->form_data->aadhaar_number___virtual_id) ? $dbrow->form_data->aadhaar_number___virtual_id : '';

    $full_name = isset($dbrow->form_data->full_name_as_in_aadhaar_card) ? $dbrow->form_data->full_name_as_in_aadhaar_card : '';
    $state = "Assam";
    $applicant_gender = isset($dbrow->form_data->applicant_gender) ? $dbrow->form_data->applicant_gender : '';
    $mobile_number = isset($dbrow->form_data->mobile_number)?$dbrow->form_data->mobile_number:'';
    $email = isset($dbrow->form_data->{'e-mail'})?$dbrow->form_data->{'e-mail'}:'';
    $fathers_name = isset($dbrow->form_data->fathers_name)?$dbrow->form_data->fathers_name:'';
    $fathers_name__guardians_name = isset($dbrow->form_data->fathers_name__guardians_name)?$dbrow->form_data->fathers_name__guardians_name:'';
    $mothers_name=isset($dbrow->form_data->mothers_name)?$dbrow->form_data->mothers_name:'';
    $date_of_birth=isset($dbrow->form_data->date_of_birth)?$dbrow->form_data->date_of_birth:'';
    $caste=isset($dbrow->form_data->caste)?$dbrow->form_data->caste:'';

    $economically_weaker_section=isset($dbrow->form_data->economically_weaker_section)?$dbrow->form_data->economically_weaker_section:'';
    $husbands_name=isset($dbrow->form_data->husbands_name)?$dbrow->form_data->husbands_name:'';
    $category_of_ex_servicemen=isset($dbrow->form_data->{'category_of_ex-servicemen'})?$dbrow->form_data->{'category_of_ex-servicemen'}:'';
    $whether_ex_servicemen=isset($dbrow->form_data->{'whether_ex-servicemen'})?$dbrow->form_data->{'whether_ex-servicemen'}:'';
    $religion=isset($dbrow->form_data->religion)?$dbrow->form_data->religion:'';
    $marital_status=isset($dbrow->form_data->marital_status)?$dbrow->form_data->marital_status:'';
    $occupation=isset($dbrow->form_data->occupation)?$dbrow->form_data->occupation:'';
    $occupation_type=isset($dbrow->form_data->occupation_type)?$dbrow->form_data->occupation_type:'';
    $prominent_identification_mark=isset($dbrow->form_data->prominent_identification_mark)?$dbrow->form_data->prominent_identification_mark:'';
    $unique_identification_no=isset($dbrow->form_data->unique_identification_no)?$dbrow->form_data->unique_identification_no:'';
    $unique_identification_type=isset($dbrow->form_data->unique_identification_type)?$dbrow->form_data->unique_identification_type:'';
    $weight_kgs=isset($dbrow->form_data->weight__kgs)?$dbrow->form_data->weight__kgs:'';
    $height_in_cm=isset($dbrow->form_data->height__in_cm)?$dbrow->form_data->height__in_cm:'';
    $eye_sight=isset($dbrow->form_data->eye_sight)?$dbrow->form_data->eye_sight:'';
    $chest_inch=isset($dbrow->form_data->chest__inch)?$dbrow->form_data->chest__inch:'';
    $are_you_differently_abled_pwd=isset($dbrow->form_data->are_you_differently_abled__pwd)?$dbrow->form_data->are_you_differently_abled__pwd:'';
    $disability_category=isset($dbrow->form_data->disability_category)?$dbrow->form_data->disability_category:'';
    $disbility_percentage=isset($dbrow->form_data->disbility_percentage)?$dbrow->form_data->disbility_percentage:'';
    $additional_disability_type=isset($dbrow->form_data->additional_disability_type)?$dbrow->form_data->additional_disability_type:'';
    //comm Address
    $same_as_permanent_address=isset($dbrow->form_data->same_as_permanent_address)?$dbrow->form_data->same_as_permanent_address:'';
    $name_of_the_house_apartment=isset($dbrow->form_data->name_of_the_house_apartment)?$dbrow->form_data->name_of_the_house_apartment:'';
    $house_no_apartment_no=isset($dbrow->form_data->house_no_apartment_no)?$dbrow->form_data->house_no_apartment_no:'';
    $building_no_block_no=isset($dbrow->form_data->building_no_block_no)?$dbrow->form_data->building_no_block_no:'';
    $address_locality_street_etc=isset($dbrow->form_data->address__locality_street_etc)?$dbrow->form_data->address__locality_street_etc:'';
    $vill_town_ward_city=isset($dbrow->form_data->vill_town_ward_city)?$dbrow->form_data->vill_town_ward_city:'';
    $post_office=isset($dbrow->form_data->post_office)?$dbrow->form_data->post_office :'';
    $police_station=isset($dbrow->form_data->police_station)?$dbrow->form_data->police_station:'';
    $pin_code=isset($dbrow->form_data->pin_code)?$dbrow->form_data->pin_code:'';
    $district=isset($dbrow->form_data->district)?$dbrow->form_data->district:'';
    //perm Address
    $name_of_the_house_apartment_p=isset($dbrow->form_data->name_of_the_house_apartment_p)?$dbrow->form_data->name_of_the_house_apartment_p:'';
    $house_no_apartment_no_p=isset($dbrow->form_data->house_no_apartment_no_p)?$dbrow->form_data->house_no_apartment_no_p:'';
    $building_no_block_no_p=isset($dbrow->form_data->building_no_block_no__p)?$dbrow->form_data->building_no_block_no__p:'';
    $address_locality_street_etc_p=isset($dbrow->form_data->address__locality_street_etc___p)?$dbrow->form_data->address__locality_street_etc___p:'';

    $vill_town_ward_city_p=isset($dbrow->form_data->vill_town_ward_city_p)?$dbrow->form_data->vill_town_ward_city_p:'';
    $post_office_p=isset($dbrow->form_data->post_office_p)?$dbrow->form_data->post_office_p:'';
    $police_station_p=isset($dbrow->form_data->police_station_p)?$dbrow->form_data->police_station_p:'';
    $pin_code_p=isset($dbrow->form_data->pin_code_p)?$dbrow->form_data->pin_code_p:'';
    $district_p=isset($dbrow->form_data->district_p)?$dbrow->form_data->district_p:'';
    $subdivision=isset($dbrow->form_data->{'sub-division'})? $dbrow->form_data->{'sub-division'}:'';
    $revenue_circle=isset($dbrow->form_data->revenue_circle)? $dbrow->form_data->revenue_circle:'';
    $residence=isset($dbrow->form_data->residence)?$dbrow->form_data->residence:'';
    //
    $highest_educational_level=isset($dbrow->form_data->highest_educational_level)?$dbrow->form_data->highest_educational_level:'';
    $highest_examination_passed=isset($dbrow->form_data->highest_examination_passed)?$dbrow->form_data->highest_examination_passed:'';

    $qtabcnt=isset($dbrow->form_data->education_qualification)?count($dbrow->form_data->education_qualification):0;
    $oqtabcnt=isset($dbrow->form_data->other_qualification_trainings_courses)?count($dbrow->form_data->other_qualification_trainings_courses):0;
    $sktabcnt=isset($dbrow->form_data->skill_qualification)?count($dbrow->form_data->skill_qualification):0;
    $langtabcnt=isset($dbrow->form_data->languages_known)?count($dbrow->form_data->languages_known):0;
    $wkexptabcnt=isset($dbrow->form_data->work_experience)?count($dbrow->form_data->work_experience):0;

    $date_submission = $dbrow->form_data->submission_date ?? '';
    $unformat_date = getDateFormat($date_submission);//dateformat helper
    $txts = explode(' ', $unformat_date);
    $date = $txts[0];

     //enclosure
    $proof_of_residence_type = isset($dbrow->form_data->enclosures->proof_of_residence_type) ? $dbrow->form_data->enclosures->proof_of_residence_type : '';
    $proof_of_residence = isset($dbrow->form_data->enclosures->proof_of_residence) ? $dbrow->form_data->enclosures->proof_of_residence : '';

    $noc_from_current_employeer_type = isset($dbrow->form_data->enclosures->noc_from_current_employeer_type) ? $dbrow->form_data->enclosures->noc_from_current_employeer_type : '';
    $noc_from_current_employeer = isset($dbrow->form_data->enclosures->noc_from_current_employeer) ? $dbrow->form_data->enclosures->noc_from_current_employeer : '';

    $age_proof_type = isset($dbrow->form_data->enclosures->age_proof_type) ? $dbrow->form_data->enclosures->age_proof_type : '';
    $age_proof = isset($dbrow->form_data->enclosures->age_proof) ? $dbrow->form_data->enclosures->age_proof : '';

    $caste_certificate_type = isset($dbrow->form_data->enclosures->caste_certificate_type) ? $dbrow->form_data->enclosures->caste_certificate_type : '';
    $caste_certificate = isset($dbrow->form_data->enclosures->caste_certificate) ? $dbrow->form_data->enclosures->caste_certificate : '';

    $educational_qualification_type = isset($dbrow->form_data->enclosures->educational_qualification_type) ? $dbrow->form_data->enclosures->educational_qualification_type : '';
    $educational_qualification = isset($dbrow->form_data->enclosures->educational_qualification) ? $dbrow->form_data->enclosures->educational_qualification : '';

    $other_qualification_certificate_type = isset($dbrow->form_data->enclosures->other_qualification_certificate_type) ? $dbrow->form_data->enclosures->other_qualification_certificate_type : '';
    $other_qualification_certificate = isset($dbrow->form_data->enclosures->other_qualification_certificate) ? $dbrow->form_data->enclosures->other_qualification_certificate : '';

    $previous_employment_type = isset($dbrow->form_data->enclosures->previous_employment_type) ? $dbrow->form_data->enclosures->previous_employment_type : '';
    $previous_employment = isset($dbrow->form_data->enclosures->previous_employment) ? $dbrow->form_data->enclosures->previous_employment : '';

    $pwd_certificate_type = isset($dbrow->form_data->enclosures->pwd_certificate_type) ? $dbrow->form_data->enclosures->pwd_certificate_type : '';
    $pwd_certificate = isset($dbrow->form_data->enclosures->pwd_certificate) ? $dbrow->form_data->enclosures->pwd_certificate : '';

    $ex_servicemen_certificate_type = isset($dbrow->form_data->enclosures->ex_servicemen_certificate_type) ? $dbrow->form_data->enclosures->ex_servicemen_certificate_type : '';
    $ex_servicemen_certificate = isset($dbrow->form_data->enclosures->ex_servicemen_certificate) ? $dbrow->form_data->enclosures->ex_servicemen_certificate : '';

    $work_experience_type = isset($dbrow->form_data->enclosures->work_experience_type) ? $dbrow->form_data->enclosures->work_experience_type : '';
    $work_experience = isset($dbrow->form_data->enclosures->work_experience) ? $dbrow->form_data->enclosures->work_experience : '';

    $any_other_document_type = isset($dbrow->form_data->enclosures->any_other_document_type) ? $dbrow->form_data->enclosures->any_other_document_type : '';
    $any_other_document = isset($dbrow->form_data->enclosures->any_other_document) ? $dbrow->form_data->enclosures->any_other_document : '';

    $aadhaar_card_type = isset($dbrow->form_data->enclosures->aadhaar_card_type) ? $dbrow->form_data->enclosures->aadhaar_card_type : '';
    $aadhaar_card = isset($dbrow->form_data->enclosures->aadhaar_card) ? $dbrow->form_data->enclosures->aadhaar_card : '';

    $passport_photo_type = isset($dbrow->form_data->enclosures->passport_photo_type) ? $dbrow->form_data->enclosures->passport_photo_type : '';
    $passport_photo = isset($dbrow->form_data->enclosures->passport_photo) ? $dbrow->form_data->enclosures->passport_photo : '';

    $signature_type = isset($dbrow->form_data->enclosures->signature_photo_type) ? $dbrow->form_data->enclosures->signature_photo_type : '';
    $signature = isset($dbrow->form_data->enclosures->signature_photo) ? $dbrow->form_data->enclosures->signature_photo : '';

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
                                    Registration of employment seeker in Employment Exchange
                                </h1>
                            </td>
                            <td style="text-align: right; width: 25%">
                                <img src="<?=base_url($passport_photo)?>" style="width: 100px; height: 100px">
                            </td>
                        </tr>

                        <tr>
                            <td colspan="3">
                                
                                <span style="float:right; font-size: 12px;">Date:<strong><?=$date?></strong></span>
                            </td>                                
                        </tr>
                    </tbody>
                </table>
                
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">AADHAAR Details of the Applicant </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">AADHAAR Number / Virtual Id<strong> : <?=$aadhaar_number_virtual_id?></strong> </td>
                                <td>State (Only Domicile of Assam can apply)<strong> : <?=$state?></strong></td>
                            </tr>
                            <tr>
                                <td style="width:50%">Full Name as in AADHAAR Card<strong> : <?=$full_name?></strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Details of the Applicant </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">Name of the Applicant (As on Aadhaar Card) *<strong> : <?=$full_name?></strong> </td>
                                <td>Gender<strong> :<?=$applicant_gender?></strong></td>
                            </tr>
                            <tr>
                                <td style="width:50%">Mobile Number<strong> : <?=$mobile_number?></strong> </td>
                                <td style="width:50%">E-Mail<strong> : <?=$email?></strong> </td>
                            </tr>
                             <tr>
                                <td style="width:50%">Fathers Name<strong> :<?=$fathers_name?> </strong> </td>
                                
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                 <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Personal Information of Applicant </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">Father's Name/ Guardian's Name<strong> : <?=$fathers_name__guardians_name?> </strong> </td>
                                <td>Mother Name<strong> :<?=$mothers_name?></strong></td>
                            </tr>
                            <tr>
                                <td style="width:50%">Date of Birth<strong> : <?=$date_of_birth?></strong> </td>
                                <td style="width:50%">Caste<strong> : <?=$caste?></strong> </td>
                            </tr>
                            <?php if(!empty($economically_weaker_section)){?>
                            <tr>
                                <td style="width:50%"><strong></strong> </td>

                                <td style="width:50%">Economically Weaker Section <strong> : <?=$economically_weaker_section?></strong> </td>
                            </tr>
                            <?php } ?>
                             <tr>
                                <td style="width:50%">Husband Name<strong> :  <?=$husbands_name?></strong></td>
                                <td style="width:50%">Whether Ex-Servicemen<strong>:<?=$whether_ex_servicemen?> </strong> </td>
                            </tr>
                            <?php if(!empty($category_of_ex_servicemen)){?>
                            <tr>
                                <td style="width:50%"><strong></strong> </td>
                                <td style="width:50%">Category of ex-servicemen<strong> : <?=$category_of_ex_servicemen?> </strong> </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td style="width:50%">Religion<strong>:<?=$religion?></strong> </td>
                                <td style="width:50%">Marital Status<strong> : <?=$marital_status?> </strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Occupation <strong>:<?=$occupation?></strong> </td>
                                <?php if(!empty($occupation_type)){?>
                                <td style="width:50%">Occupation Type <strong>:<?=$occupation_type?></strong> </td>
                                <?php } ?>

                            </tr>
                             <tr>
                                <td style="width:50%">Unique Identification Type <strong>:<?=$unique_identification_type?></strong> </td>
                                <td style="width:50%">Unique Identification No<strong>:<?=$unique_identification_no?></strong> </td>
                            </tr>
                             <tr>
                                <td style="width:50%">Prominent Identification Mark<strong>:<?=$prominent_identification_mark?></strong> </td>
                                <td style="width:50%"><strong> </strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
                

                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Physical Attributes </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">Height (in cm)<strong> :<?=$height_in_cm?> </strong> </td>
                                <td>Weight (Kgs)<strong> :<?=$weight_kgs?></strong></td>
                            </tr>
                            <tr>
                                <td style="width:50%">Eye Sight<strong> :<?=$eye_sight?> </strong> </td>
                                <td style="width:50%">Chest (Inch)<strong> :<?=$chest_inch?> </strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Are you Differently abled (PwD)?<strong>:<?=$are_you_differently_abled_pwd?></strong> </td>
                                <?php if(!empty($disability_category)){?>
                                <td style="width:50%">Disability Category<strong> : <?=$disability_category?></strong> </td>
                                <?php } ?>
                            </tr>
                             <tr>
                                <?php if(!empty($additional_disability_type)){?>
                                <td style="width:50%">Additional Disability Type<strong> :  <?=$additional_disability_type?> </strong> </td>
                                <?php } ?>
                                <?php if(!empty($disbility_percentage)){?>
                                <td style="width:50%">Disbility Percentage<strong> :<?=$disbility_percentage?></strong> </td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Permanent Address </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">Name of the House/Apartment<strong>:<?=$name_of_the_house_apartment_p?> </strong> </td>
                                <td>House No/Apartment No<strong>:<?=$house_no_apartment_no_p?></strong></td>
                            </tr>
                            <tr>
                                <td style="width:50%">Building No/Block No.<strong>:<?=$building_no_block_no_p?>  </strong> </td>
                                <td style="width:50%">Address (Locality/Street/etc.)<strong>:<?=$address_locality_street_etc_p?> </strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Vill/Town/Ward/City<strong>:<?=$vill_town_ward_city_p?></strong> </td>
                                <td style="width:50%">Post Office<strong>:<?=$post_office_p?> </strong> </td>
                            </tr>
                             <tr>
                                <td style="width:50%">Police Station<strong>:<?=$police_station_p?></strong> </td>
                                <td style="width:50%">Pin Code<strong>:<?=$pin_code_p?> </strong> </td>
                            </tr>
                             <tr>
                                <td style="width:50%">District<strong>:<?=$district_p?> </strong> </td>
                                <td style="width:50%">Sub-Division<strong>:<?=$subdivision?> </strong> </td>
                            </tr>
                             <tr>
                                <td style="width:50%">Revenue Circle<strong>:<?=$revenue_circle?> </strong> </td>
                                <td style="width:50%">Residence<strong>:<?=$residence?> </strong> </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                 <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Communication Address </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:50%">Same as permanent address<strong>:<?=$same_as_permanent_address?> </strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Name of the House/Apartment<strong> :<?=$name_of_the_house_apartment?> </strong> </td>
                                <td>House No/Apartment No<strong>:<?=$house_no_apartment_no?></strong></td>
                            </tr>
                            <tr>
                                <td style="width:50%">Building No/Block No.<strong>:<?=$building_no_block_no?> </strong> </td>
                                <td style="width:50%">Address (Locality/Street/etc.)<strong>:<?=$address_locality_street_etc?> </strong> </td>
                            </tr>
                            <tr>
                                <td style="width:50%">Vill/Town/Ward/City<strong>:<?=$vill_town_ward_city?></strong> </td>
                                <td style="width:50%">Post Office<strong>:<?=$post_office?> </strong> </td>
                            </tr>
                             <tr>
                                <td style="width:50%">Police Station<strong>:<?=$police_station?></strong> </td>
                                <td style="width:50%">Pin Code<strong>:<?=$pin_code?> </strong> </td>
                            </tr>
                             <tr>
                                <td style="width:50%">District<strong>:<?=$district?> </strong> </td>
                            </tr>
                             <tr>
                                
                                
                            </tr>
                        </tbody>
                    </table>
                </fieldset>


                 <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Education & Training Details </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                           
                            <tr>
                                <td style="width:50%">Highest Educational Level <strong>: <?=$highest_educational_level?> </strong> </td>
                                <td>Highest Examination Passed<strong>:<?=$highest_examination_passed?></strong></td>
                            </tr>
                            <tr>
                                <td style="width:50%">Education Qualification<strong>: </strong> </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php if ($qtabcnt!=0){ ?>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody>
                            <tr>
                                <td>Sl no:</td>
                                <td>Examination Passed</td>
                                <td>Major/Elective Subject</td>
                                <td>Subjects/Other Subjects</td>
                                <td>Board/University</td>
                                <td>Institution/School/College</td>
                                <td>Date of Passing</td>
                                <td>Reg.No.</td>
                                <td>% of Marks</td>
                                <td>Class/Division</td>
                            </tr>    
                            <?php for ($i = 0; $i < $qtabcnt; $i++){?>
                            <tr>
                                <td><strong><?=$i+1?></strong></td>
                                <td><strong><?=$dbrow->form_data->education_qualification[$i]->examination_passed?></strong> </td>
                                <td><strong><?=$dbrow->form_data->education_qualification[$i]->major__elective_subject?></strong></td>
                                <td><strong><?=$dbrow->form_data->education_qualification[$i]->subjects__other_subjects?></strong></td>
                                <td><strong><?=$dbrow->form_data->education_qualification[$i]->board__university?></strong> </td>
                                <td><strong><?=$dbrow->form_data->education_qualification[$i]->institution__school__college?></strong></td>
                                <td><strong><?=$dbrow->form_data->education_qualification[$i]->date_of_passing?></strong> </td>
                                <td><strong><?=$dbrow->form_data->education_qualification[$i]->registration_no?></strong> </td>
                                <td><strong><?=$dbrow->form_data->education_qualification[$i]->percentage_of_marks?></strong> </td>
                                <td><strong><?=$dbrow->form_data->education_qualification[$i]->class__division?></strong> </td>
                            </tr>
                            <?php
                             } //for for-loop 
                            ?>

                        </tbody>
                    </table>
                     <?php
                      } //for if
                     ?>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Other Qualification/Trainings/Courses </legend>
                    <?php if ($oqtabcnt!=0){ ?>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>    
                                <td>Sl no:</td>
                                <td>Certificate Name</td>
                                <td>Issued By</td>
                                <td>Duration in Months</td>
                                <td>Date of Passing</td>  
                            </tr>
                            <?php for ($i = 0; $i < $oqtabcnt; $i++){?>
                            <tr>
                                <td><strong><?=$i+1?></strong></td>
                                <td><strong><?=$dbrow->form_data->other_qualification_trainings_courses[$i]->certificate_name?></strong> </td>
                                <td><strong><?=$dbrow->form_data->other_qualification_trainings_courses[$i]->issued_by?></strong></td>
                                <td><strong><?=$dbrow->form_data->other_qualification_trainings_courses[$i]->duration_in_months?></strong></td>
                                <td><strong><?=$dbrow->form_data->other_qualification_trainings_courses[$i]->date_of_passing?></strong> </td>
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
                    <legend class="h5">Skill Qualification</legend>
                    <?php if ($sktabcnt!=0){ ?>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>    
                                <td>Sl no:</td>
                                <td>Exam/Diploma/Certificate</td>
                                <td>Sector</td>
                                <td>Course/Job Role</td>
                                <td>Duration</td> 
                                <td>Certificate ID</td> 
                                <td>Engagement</td>
                            </tr>
                            <?php for ($i = 0; $i < $sktabcnt; $i++){?>
                            <tr>
                                <td><strong><?=$i+1?></strong></td>
                                <td><strong><?=$dbrow->form_data->skill_qualification[$i]->exam__diploma__certificate?></strong> </td>
                                <td><strong><?=$dbrow->form_data->skill_qualification[$i]->sector?></strong></td>
                                <td><strong><?=$dbrow->form_data->skill_qualification[$i]->course__job_role?></strong></td>
                                <td><strong><?=$dbrow->form_data->skill_qualification[$i]->duration?></strong> </td>
                                <td><strong><?=$dbrow->form_data->skill_qualification[$i]->certificate_id?></strong></td>
                                <td><strong><?=$dbrow->form_data->skill_qualification[$i]->engagement?></strong> </td>
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
                    <legend class="h5">Languages Known</legend>
                    <?php if ($langtabcnt!=0){ ?>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>    
                                <td>Sl no:</td>
                                <td>Language</td>
                                <td>Options</td>
                            </tr>
                            <?php for ($i = 0; $i < $langtabcnt; $i++){?>
                            <tr>
                                <td><strong><?=$i+1?></strong></td>
                                <td><strong><?=$dbrow->form_data->languages_known[$i]->language?></strong> </td>
                                <td><strong><?=$dbrow->form_data->languages_known[$i]->r_option?>,<?=$dbrow->form_data->languages_known[$i]->w_option?>,<?=$dbrow->form_data->languages_known[$i]->s_option?></strong></td>
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
                    <legend class="h5">Job Preference/Key Skills</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                           
                           <tr>
                                <td style="width:50%">Job Preference/Key Skills<strong>:<?=$dbrow->form_data->job_preference_key_skills?> </strong> </td>
                            </tr>
                           
                        </tbody>
                    </table>
                </fieldset>

                 <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Work Experience</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                           <tr>
                                <td style="width:50%">Current Employment Status<strong> : <?=$dbrow->form_data->current_employment_status?></strong> </td>
                            </tr>
                        </tbody>
                        <?php if ($wkexptabcnt!=0){ ?>
                        <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody>
                            <tr>
                                <td>Sl no:</td>
                                <td>Employer</td>
                                <td>Nature of Work</td>
                                <td>From</td>
                                <td>To</td>
                                <td>Duration</td>
                                <td>Highest Designation</td>
                                <td>Last Salary Drawn</td>
                                <td>Functional Roles</td>
                                <td>Industry/ Sector</td>
                                <td>Functional Area</td>
                            </tr>    
                            <?php for ($i = 0; $i < $wkexptabcnt; $i++){?>
                            <tr>
                                <td><strong><?=$i+1?></strong></td>
                                <td><strong><?=$dbrow->form_data->work_experience[$i]->employer?></strong> </td>
                                <td><strong><?=$dbrow->form_data->work_experience[$i]->nature_of_work?></strong></td>
                                <td><strong><?=$dbrow->form_data->work_experience[$i]->from?></strong></td>
                                <td><strong><?=$dbrow->form_data->work_experience[$i]->to?></strong> </td>
                                <td><strong><?=$dbrow->form_data->work_experience[$i]->duration?></strong></td>
                                <td><strong><?=$dbrow->form_data->work_experience[$i]->highest_designation?></strong> </td>
                                <td><strong><?=$dbrow->form_data->work_experience[$i]->last_salary_drawn?></strong> </td>
                                <td><strong><?=$dbrow->form_data->work_experience[$i]->functional_roles?></strong> </td>
                                <td><strong><?=$dbrow->form_data->work_experience[$i]->industry__sector?></strong> </td>
                                <td><strong><?=$dbrow->form_data->work_experience[$i]->functional_area?></strong> </td>
                            </tr>
                            <?php
                             }
                            ?>

                        </tbody>
                    </table>
                    <?php
                        }
                    ?>
                    </table>
                </fieldset>

                 <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Total Work Experience</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                           
                           <tr>
                                <td style="width:50%">Years<strong> :<?=$dbrow->form_data->years?></strong> </td>
                                <td style="width:50%">Months<strong> :<?=$dbrow->form_data->months?> </strong> </td>
                            </tr>

                        </tbody>
                    </table>
                </fieldset>

                 <fieldset class="border border-success table-responsive" style="margin-top:10px">
                    <legend class="h5">Employment Exchange Office</legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                           
                           <tr>
                                <td style="width:50%">Employment Exchange<strong> :<?=$dbrow->form_data->employment_exchange?> </strong> </td>
                               
                            </tr>

                        </tbody>
                    </table>
                </fieldset>

                 <h5 class="text-center mt-3 text-success"><u><strong>ENCLOSURES</strong></u></h5>
                    <fieldset class="border border-success" style="margin-top:40px">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td colspan="3" style="color:#fd7e14; text-align: center; font-size: 14px">
                                        Note : For ID proof, Address proof, Age proof only jpg, jpeg, png and pdf of maximum 1MB is allowed;
                                        For Passport size photo only jpg, jpeg, and png of maximum 200KB is allowed
                                    </td>
                                </tr>
                                <tr>
                                    <th>Type of Enclosure</th>
                                    <th>Enclosure Document</th>
                                    <th>File/Reference</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($proof_of_residence!=''){?>
                                <tr>
                                    <td><?= employmentcertificate('proof_of_residence')['enclosure_type'] ?></td>
                                    <td><?= $proof_of_residence_type ?></td>
                                    <td>
                                        <?php if (strlen($proof_of_residence)) { ?>
                                            <a href="<?= base_url($proof_of_residence) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span> View/Download
                                            </a>
                                        <?php }
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?> 
                                <?php if($noc_from_current_employeer!=''){?>
                                <tr>
                                   <td><?= employmentcertificate('noc_from_current_employeer')['enclosure_type'] ?></td>
                                   <td><?= $noc_from_current_employeer_type?></td>
                                   <td>
                                        <?php if (strlen($noc_from_current_employeer)) { ?>
                                        <a href="<?= base_url($noc_from_current_employeer) ?>" class="btn font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>View/Download
                                        </a>
                                            <?php } ?>
                                        </td>
                                  </tr>
                                <?php } ?> 
                                <?php if($age_proof!=''){?>
                                <tr>
                                    <td><?= employmentcertificate('age_proof')['enclosure_type'] ?></span></td>
                                    <td><?= $age_proof_type?></td>
                                    <td>
                                        <?php if (strlen($age_proof)) { ?>
                                            <a href="<?= base_url($age_proof) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>

                                <?php if ($caste_certificate_type != '') { ?>
                                    <tr>
                                        <td><?= employmentcertificate('caste_certificate')['enclosure_type'] ?></td>
                                        <td><?= $caste_certificate_type?></td>
                                        <td>
                                            <?php if (strlen($caste_certificate)) { ?>
                                                <a href="<?= base_url($caste_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                            <?php } //End of if 
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                          
                                <?php if ($educational_qualification_type != '') { ?>
                                    <tr>
                                        <td><?= employmentcertificate('educational_qualification')['enclosure_type'] ?></td>
                                        <td><?= $educational_qualification_type?></td> 
                                        <td>
                                            <?php if (strlen($educational_qualification)) { ?>
                                                <a href="<?= base_url($educational_qualification) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                            <?php } //End of if 
                                            ?>
                                            
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if ($other_qualification_certificate_type != '') { ?>
                                <tr>
                                    <td><?= employmentcertificate('other_qualification_certificate')['enclosure_type'] ?></td>
                                    <td><?= $other_qualification_certificate_type ?></td>
                                    <td>
                                        <?php if (strlen($other_qualification_certificate)) { ?>
                                            <a href="<?= base_url($other_qualification_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if ($previous_employment_type != '') { ?>
                                <tr>
                                    <td><?= employmentcertificate('previous_employment')['enclosure_type'] ?></td>
                                    <td><?= $previous_employment_type?></td>
                                    <td>
                                        <?php if (strlen($previous_employment)) { ?>
                                            <a href="<?= base_url($previous_employment) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <?php if ($pwd_certificate_type != '') { ?>
                                    <tr>
                                        <td><?= employmentcertificate('pwd_certificate')['enclosure_type'] ?></td>
                                        <td>
                                            <?= $pwd_certificate_type?> 
                                        </td>
                                        <td>
                                            <?php if (strlen($pwd_certificate)) { ?>
                                                <a href="<?= base_url($pwd_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                            <?php } //End of if 
                                            ?>
                                            
                                        </td>
                                    </tr>
                                <?php }?>

                                <?php if ($ex_servicemen_certificate_type != '') { ?>
                                    <tr>
                                        <td><?= employmentcertificate('ex_servicemen_certificate')['enclosure_type'] ?></td>
                                        <td>
                                            <?= $ex_servicemen_certificate_type ?>
                                        </td>
                                        <td>
                                           
                                            <?php if (strlen($ex_servicemen_certificate)) { ?>
                                                <a href="<?= base_url($ex_servicemen_certificate) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                    <span class="fa fa-download"></span>
                                                    View/Download
                                                </a>
                                            <?php } //End of if 
                                            ?>
                                           
                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php if ($work_experience_type != '') { ?>
                                <tr>
                                    <td><?= employmentcertificate('work_experience')['enclosure_type'] ?></td>
                                    <td><?= $work_experience_type ?></td>
                                    <td>
                                       
                                        <?php if (strlen($work_experience)) { ?>
                                            <a href="<?= base_url($work_experience) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                        
                                    </td>
                                </tr>
                                <?php }?>
                                <?php if ($any_other_document_type != '') { ?>
                                <tr>
                                    <td><?= employmentcertificate('any_other_document')['enclosure_type'] ?></td>
                                    <td>
                                         <?= $any_other_document_type ?>
                                        
                                    </td>
                                    <td>
                                        
                                        <?php if (strlen($any_other_document)) { ?>
                                            <a href="<?= base_url($any_other_document) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                        
                                    </td>
                                </tr>
                                <?php }?>
                                <?php if ($aadhaar_card_type != '') { ?>
                                <tr>
                                    <td><?= employmentcertificate('aadhaar_card')['enclosure_type'] ?></td>
                                    <td>
                                         <?= $aadhaar_card_type ?>
                                    </td>
                                    <td>
                                       
                                        <?php if (strlen($aadhaar_card)) { ?>
                                            <a href="<?= base_url($aadhaar_card) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                        <?= form_error("aadhaar_card") ?>
                                    </td>
                                </tr>
                                <?php }?>
                                <?php if ($signature_type != '') { ?>
                                <tr>
                                    <td><?= employmentcertificate('signature')['enclosure_type'] ?></td>
                                    <td>
                                         <?= $signature_type ?>
                                    </td>
                                    <td>
                                        
                                        <?php if (strlen($signature)) { ?>
                                            <a href="<?= base_url($signature) ?>" class="btn font-weight-bold text-success" target="_blank">
                                                <span class="fa fa-download"></span>
                                                View/Download
                                            </a>
                                        <?php } //End of if 
                                        ?>
                                    </td>
                                </tr>
                                 <?php } //End of if 
                                        ?>
                            </tbody>
                        </table>
                    </fieldset>

            </div><!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <a href="<?= base_url("iservices/transactions")?>" class="btn btn-info btn-sm mbtn" >Back</a>

            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>

