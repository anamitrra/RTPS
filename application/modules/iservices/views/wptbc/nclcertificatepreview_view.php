<?php
$obj_id = $dbrow->{'_id'}->{'$id'};
$rtps_trans_id = $dbrow->rtps_trans_id;
$applicant_name = $dbrow->applicant_name;
$applicant_gender = $dbrow->applicant_gender;
$father_name = $dbrow->father_name;
$mother_name = $dbrow->mother_name;
$spouse_name = $dbrow->spouse_name;
$email = $dbrow->email;
$dob = $dbrow->dob;
$mobile_number = $dbrow->mobile_number;

$pa_houseno = $dbrow->pa_houseno;
$pa_landmark = $dbrow->pa_landmark;
$pa_state = $dbrow->pa_state;
$pa_district = $dbrow->pa_district;
$pa_subdivision = $dbrow->pa_subdivision;
$pa_revenuecircle = $dbrow->pa_revenuecircle;
$pa_mouza = $dbrow->pa_mouza;
$pa_village = $dbrow->pa_village;
$pa_ps = $dbrow->pa_ps;
$pa_pincode = $dbrow->pa_pincode;
$pa_po = $dbrow->pa_po;

$caste_name= $dbrow->caste_name;
$fsearning_sources= $dbrow->financial_status->fsearning_sources;
$organization_types= $dbrow->financial_status->organization_types;
$organization_names= $dbrow->financial_status->organization_names;
$fs_designations= $dbrow->financial_status->fs_designations;

$isearning_sources= $dbrow->income_status->isearning_sources;
$annual_salary= $dbrow->income_status->annual_salary;
$other_income= $dbrow->income_status->other_income;
$total_income= $dbrow->income_status->total_income;
$is_remarks= $dbrow->income_status->is_remarks;

$residential_proof_type = $dbrow->residential_proof_type;
$residential_proof = $dbrow->residential_proof;
$obc_type = $dbrow->obc_type;
$obc = $dbrow->obc;
$income_certificate_type = $dbrow->income_certificate_type;
$income_certificate = $dbrow->income_certificate;
$other_doc_type = $dbrow->other_doc_type;
$other_doc = $dbrow->other_doc;
$soft_copy_type = $dbrow->soft_copy_type;
$soft_copy = $dbrow->soft_copy;
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
        <div class="card shadow-sm" id="printDiv" style="background:#E6F1FF">
            <div class="card-header" style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
                    Application for Issuance Of Non Creamy Layer Certificate<br>
                    ( ননক্ৰিমি প্ৰমাণ পত্ৰৰ বাবে আবেদন ) 
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
                    <strong style="font-size:16px; ">Supporting Document / সহায়ক নথি পত্ৰ</strong>

                    <ol style="list-style:decimal-leading-zero;  margin-left: 24px; margin-top: 20px">
                        <li>
                            Permanent Resident Certificate (PRC). In case PRC is not available voter ID/Electricity bill/Bank Passbook/registered land documents/Jamabandi are accepted in practice [Mandatory]
                            / স্হায়ী বাসিন্দাৰ পত্ৰ । ২. যদি  স্হায়ী বাসিন্দাৰ প্ৰমান পত্ৰ নাথাকে, তেন্তে ভোটাৰ কাৰ্ড/বিদুৎ বিল/  বেঙ্ক পাছবুক/ পঞ্জীকৃত মাটিৰ নথি/ জমাবন্দী ( বাধ্যতামূলক )
                        </li>
                        <li>
                            OBC / MOBC certificate issued by competent authority [Mandatory]
                            / পিচপৰা/অন্যান্য পিচপৰা জাতিৰ প্ৰমাণপত্ৰ ( বাধ্যতামূলক )
                        </li>
                        <li>
                            Income certificate of parents [Mandatory]  
                            / পিতৃ মাতৃৰ আয়ৰ প্ৰমাণপত্ৰ ( বাধ্যতামূলক )
                            <ol style="list-style:lower-alpha;  margin-left: 20px; margin-top: 0px">
                                <li>Issued by the Circle Officer ( if the parents are agriculturist ) or / (ক) চক্ৰ বিষয়াৰ দ্বাৰা ( যদি পিতৃ মাতৃ খেতিয়ক হয় )</li>
                                <li>Income certificate of parents issued by Controlling Authority / Treasury officer (if the parents are retired salaried person) or / (খ) কোষাগাৰ বিষয়াৰ দ্বাৰা, যদিহে আবেদনকাৰী পেঞ্চনধাৰী হয় ।</li>
                                <li>Issued by Councillor/Mouzadar (in practice, in case of a non-salaried person) / (গ) কাউন্সিলৰ/মৌজাদাৰৰ দ্বাৰা, যদিহে আবেদনকাৰী দৰমহাবিহীন হয় ।</li>
                            </ol>
                        </li>
                        <li>
                            Other documents as per requirement (Voter card, Bank passbook, etc.) [Optional]  
                            / অন্যান্য নথি যেনে- ভোটাৰ কাৰ্ড, বেঙ্ক পাছবুক 
                        </li>
                    </ol>


                    <strong style="font-size:16px;  margin-top: 10px">Fees / মাচুল :</strong>                        
                    <ol style="list-style:decimal-leading-zero;  margin-left: 24px; margin-top: 10px">
                        <li>Statutory charges / স্হায়ী মাচুল : NIL</li>
                        <li>Service charge / সেৱা মাচুল – Rs. 30 / ৩০ টকা</li>
                        <li>Printing charge (in case of any printing from PFC) /  ছপা খৰচ -  Rs. 10 Per Page / প্ৰতি পৃষ্ঠাত ১০ টকা</li>
                        <li>Scanning charge (in case documents are scanned in PFC)  স্কেনিং খৰচ - Rs. 5 Per page / প্ৰতি পৃষ্ঠা ৫ টকা</li>
                    </ol>                        
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ তথ্য </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম : </td>
                                <td style="font-weight:bold"><?=$applicant_name?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ : </td>
                                <td style="font-weight:bold"><?=$applicant_gender?></td>
                            </tr>
                            <tr>
                                <td>Father&apos;s Name / পিতৃৰ নাম : </td>
                                <td style="font-weight:bold"><?=$father_name?></td>
                                <td>&nbsp;</td>
                                <td>Mother&apos;s Name/ মাতৃৰ নাম : </td>
                                <td style="font-weight:bold"><?=$mother_name?></td>
                            </tr>
                            <tr>
                                <td>Spouse Name / গৃহিনী/স্বামীৰ নাম : </td>
                                <td style="font-weight:bold"><?=$spouse_name?></td>
                                <td>&nbsp;</td>
                                <td>E-Mail / ই-মেইল : </td>
                                <td style="font-weight:bold"><?=$email?></td>
                            </tr>
                            <tr>
                                <td>Date of Birth / জন্মৰ তাৰিখ : </td>
                                <td style="font-weight:bold"><?=$dob?></td>
                                <td>&nbsp;</td>
                                <td>Mobile Number / দুৰভাষ ( মবাইল ) : </td>
                                <td style="font-weight:bold"><?=$mobile_number?></td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Permanent Address / স্হায়ী ঠিকনা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:25%">House No/Bylane No/Street Name / ঘৰ নং/ৰাস্তা নং/ৰাস্তাৰ নাম : </td>
                                <td style="width:23%; font-weight:bold"><?=$pa_houseno?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td style="width:25%">Landmark/Locality/Ward No / ৱাৰ্ড নং/সূচক চিহ্ন : </td>
                                <td style="width:23%; font-weight:bold"><?=$pa_landmark?></td>
                            </tr>
                            <tr>
                                <td>State / ৰাজ্য : </td>
                                <td style="font-weight:bold"><?=$pa_state?></td>
                                <td>&nbsp;</td>
                                <td>District (P)/ জিলা : </td>
                                <td style="font-weight:bold"><?=$pa_district?></td>
                            </tr>
                            <tr>
                                <td>Sub Division (p)/ মহকুমা : </td>
                                <td style="font-weight:bold"><?=$pa_subdivision?></td>
                                <td>&nbsp;</td>
                                <td>Revenue Circle(p) / ৰাজহ চক্ৰ : </td>
                                <td style="font-weight:bold"><?=$pa_revenuecircle?></td>
                            </tr>
                            <tr>
                                <td>Mouza / মৌজা : </td>
                                <td style="font-weight:bold"><?=$pa_mouza?></td>
                                <td>&nbsp;</td>
                                <td>Village/Town (p)/ গাওঁ/চহৰ : </td>
                                <td style="font-weight:bold"><?=$pa_village?></td>
                            </tr>
                            <tr>
                                <td>Police Station / থানা : </td>
                                <td style="font-weight:bold"><?=$pa_ps?></td>
                                <td>&nbsp;</td>
                                <td>Pin Code / পিনকোড : </td>
                                <td style="font-weight:bold"><?=$pa_pincode?></td>
                            </tr>
                            <tr>
                                <td>Post Office / ডাকঘৰ : </td>
                                <td style="font-weight:bold" colspan="3"><?=$pa_po?></td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Other Details / অন্যান্য তথ্য  </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Name of Caste / জাতি : </td>
                                <td style="font-weight:bold"><?=$caste_name?></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <table class="table table-bordered" id="financialstatustbl">
                        <thead>
                            <tr>
                                <th colspan="4" class="text-center" style="font-size:16px">
                                    Financial Status of Parents/ Husband/ Wife / পিতৃ-মাতৃ/স্বামী/স্ত্ৰীৰ আৰ্থিক স্হি
                                </th>
                            </tr>
                            <tr>
                                <th>Source Of Earning / উপাৰ্জনৰ উৎস</th>
                                <th>Type Of Organisation / সংস্হাৰ ধৰণ</th>
                                <th>Name Of Organisation/Department / বিভাগ/সংস্হাৰ নাম</th>
                                <th>Designation/Post Held / পদবী</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $fsearningSources = (isset($fsearning_sources) && is_array($fsearning_sources)) ? count($fsearning_sources) : 0;
                            if ($fsearningSources > 0) {
                                for ($i = 0; $i < $fsearningSources; $i++) { ?>
                                    <tr>
                                        <td><?=$fsearning_sources[$i]?></td>
                                        <td><?= $organization_types[$i] ?></td>
                                        <td><?= $organization_names[$i] ?></td>
                                        <td><?= $fs_designations[$i] ?></td>
                                    </tr>
                                <?php }
                            }//End of if  ?>
                        </tbody>
                    </table>
                    
                    <table class="table table-bordered" id="incomestatustbl">
                        <thead>
                            <tr>
                                <th colspan="5" class="text-center" style="font-size:16px">
                                    Income Status / আয়ৰ স্হিতি
                                </th>
                            </tr>
                            <tr>
                                <th>Source Of Earning / আয়ৰ উৎস</th>
                                <th>Gross Annual Salary/Amount মুঠ বাৰ্ষিক দৰমহা</th>
                                <th>Income From Other Source / অন্য উৎসৰ পৰা</th>
                                <th>Total / মুঠ</th>
                                <th>Remarks / মন্তব্য</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $isearningSources = (isset($isearning_sources) && is_array($isearning_sources)) ? count($isearning_sources) : 0;
                            if ($isearningSources > 0) {
                                for ($i = 0; $i < $isearningSources; $i++) { ?>
                                    <tr>
                                        <td><?=$isearning_sources[$i]?></td>
                                        <td><?= $annual_salary[$i] ?></td>
                                        <td><?= $other_income[$i] ?></td>
                                        <td><?= $total_income[$i] ?></td>
                                        <td><?= $is_remarks[$i] ?></td>
                                    </tr>
                                <?php }
                            }//End of if  ?>
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
                                <td>Residential Proof.<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?=$residential_proof_type?></td>
                                <td>
                                    <?php if(strlen($residential_proof)){ ?>
                                        <a href="<?=base_url($residential_proof)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>OBC / MOBC certificate issued by competent authority.<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?=$obc_type?></td>
                                <td>
                                    <?php if(strlen($obc)){ ?>
                                        <a href="<?=base_url($obc)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Income certificate of parents<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?=$income_certificate_type?></td>
                                <td>
                                    <?php if(strlen($income_certificate)){ ?>
                                        <a href="<?=base_url($income_certificate)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Other document as per requirement</td>
                                <td style="font-weight:bold"><?=$other_doc_type?></td>
                                <td>                                    
                                    <?php if(strlen($other_doc)){ ?>
                                        <a href="<?=base_url($other_doc)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Upload the Soft copy of the applicant form<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?=$soft_copy_type?></td>
                                <td>
                                    <?php if(strlen($soft_copy)){ ?>
                                        <a href="<?=base_url($soft_copy)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
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
                <a href="<?=base_url('iservices/wptbc/nclcertificate/index/'.$obj_id)?>" class="btn btn-primary">
                    <i class="fa fa-pencil"></i> Edit
                </a>
                <button class="btn btn-warning" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <a href="<?=base_url('iservices/wptbc/payments/payment/'.$rtps_trans_id)?>" class="btn btn-success">
                    <i class="fa fa-angle-double-right"></i> Continue
                </a>
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </div><!--End of .container-->
</main>