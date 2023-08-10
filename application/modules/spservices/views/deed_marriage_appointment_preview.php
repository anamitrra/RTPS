<?php
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
$appl_ref_no = $dbrow->appl_ref_no??'';
$bride_name = $dbrow->bride_name??'';
$groom_name = $dbrow->groom_name??'';
$appl_name = $dbrow->appl_name??'';
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
                    Application for Issuance Of Scheduled Caste Certificate<br>
                    ( অনুসুচিত জাতিৰ প্ৰমাণ পত্ৰৰ বাবে আবেদন ) 
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
                        <li>Copy of Legacy data as per NRC 1951, Electoral role between 1966 & 1971 [Optional] / NRC 1951 মতে লিগেচী তথ্যৰ নকল</li>
                        <li>Permanent Resident Certificate (PRC). In case PRC is not available voter ID/Electricity bill/Bank Passbook/registered land documents/Jamabandi are accepted in practice [Mandatory] / স্হানীয় বাসিন্দাৰ প্ৰমানপত্ৰ</li>
                        <li> Caste certificate of father or any supporting proof of caste status [Mandatory] / পিতৃৰ জাতি প্ৰমানপত্ৰ ( নাইবা পৰিয়ালৰ আন কাৰোবাৰ )</li>
                        <li> Report of Gaonburah in case of rural areas / Ward Commissioner in case of urban areas. [Mandatory] / গাওঁ অঞ্চলৰ বাবে গাওঁবুঢ়়াৰ প্ৰমানপত্ৰ চহৰ অঞ্চলৰ বাবে ৱাৰ্ড আয়ুক্তৰ প্ৰমানপত্ৰ</li>
                        <li>*If the parents of the applicant has already been issued SC certificate, recommendation of the Gaonburah / Ward Commissioner may be skipped Applicant’s photo [Mandatory] / যদি আবেদনকাৰীৰ পিতৃ মাতৃক আগতেই জাতিৰ প্ৰমানপত্ৰ দিয়া হৈছে ।</li>
                        <li>Recommendation of President / Secretary of District Anuhushit Jati Parishad [Mandatory] / পঞ্চায়ত/জাতি উন্নয়ন পৰিষদৰ সভাপতিৰ অনুমোদন</li>
                        <li>Other document as per requirement (Voter List, bank passbook etc.) [Optional] / প্ৰয়োজনীয় অন্য নথি</li>
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
                                <td>Religion / ধৰ্ম : </td>
                                <td style="font-weight:bold"><?=$religion?></td>
                            </tr>
                            <tr>
                                <td>Mobile Number / দুৰভাষ ( মবাইল ) : </td>
                                <td style="font-weight:bold"><?=$mobile_number?></td>
                                <td>&nbsp;</td>
                                <td>Age / বয়স : </td>
                                <td style="font-weight:bold"><?=$age?></td>
                            </tr>
                            <tr>
                                <td>Sub Caste / উপজাতি : </td>
                                <td style="font-weight:bold"><?=$sub_caste?></td>
                                <td>&nbsp;</td>
                                <td>E-Mail / ই-মেইল : </td>
                                <td style="font-weight:bold"><?=$email?></td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Father or Ancestral Address / পূৰ্ব পুৰুষৰ ঠিকনা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:25%">Name (Father or Ancestor) / পিতৃ অথবা পূৰ্ব পুৰুষৰ নাম : </td>
                                <td style="width:23%; font-weight:bold"><?=$ancestor_name?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td style="width:25%">Address Line 1 (Father or Ancestor) / ঠিকনা- ১: পিতৃ অথবা পূৰ্ব পুৰুষৰ নাম : </td>
                                <td style="width:23%; font-weight:bold"><?=$ancestor_address1?></td>
                            </tr>
                            <tr>
                                <td>Address Line 2 (Father or Ancestor) / ঠিকনা- ২ : </td>
                                <td style="font-weight:bold"><?=$ancestor_address2?></td>
                                <td>&nbsp;</td>
                                <td>State (Father or Ancestor) / ৰাজ্য- পিতৃ বা পূৰ্ব পুৰুষৰ : </td>
                                <td style="font-weight:bold"><?=$ancestor_state?></td>
                            </tr>
                            <tr>
                                <td>District (Father or Ancestor) / জিলাঃ পিতৃ বা পূৰ্ব পুৰুষৰ : </td>
                                <td style="font-weight:bold"><?=$ancestor_district?></td>
                                <td>&nbsp;</td>
                                <td>Subdivision (Father or Ancestor) / মহকুমাঃ পিতৃ বা পূৰ্ব পুৰুষৰ : </td>
                                <td style="font-weight:bold"><?=$ancestor_subdivision?></td>
                            </tr>
                            <tr>
                                <td>Circle Office (Father or Ancestor) / চক্ৰঃ পিতৃ বা পূৰ্ব পুৰুষৰ : </td>
                                <td style="font-weight:bold"><?=$ancestor_circleoffice?></td>
                                <td>&nbsp;</td>
                                <td>Mouza (Father or Ancestor) / মৌজাঃ পিতৃ বা পূৰ্ব পুৰুষৰ : </td>
                                <td style="font-weight:bold"><?=$ancestor_mouza?></td>
                            </tr>
                            <tr>
                                <td>Village (Father or Ancestor) / গাওঁ: পিতৃ বা পূৰ্ব পুৰুষৰ : </td>
                                <td style="font-weight:bold"><?=$ancestor_village?></td>
                                <td>&nbsp;</td>
                                <td>Police Station (Father or Ancestor) / থানাঃ পিতৃ বা পূৰ্ব পুৰুষৰ : </td>
                                <td style="font-weight:bold"><?=$ancestor_ps?></td>
                            </tr>
                            <tr>
                                <td>Post Office (Father or Ancestor) / ডাকঘৰঃ পিতৃ বা পূৰ্ব পুৰুষৰ : </td>
                                <td style="font-weight:bold"><?=$ancestor_po?></td>
                                <td>&nbsp;</td>
                                <td>Pincode (Father or Ancestor) / পিনকোডঃ পিতৃ বা পূৰ্ব পুৰুষৰ : </td>
                                <td style="font-weight:bold"><?=$ancestor_pin?></td>
                            </tr>
                            <tr>
                                <td>Relation / সম্পৰ্ক : </td>
                                <td style="font-weight:bold"><?=$ancestor_relation?></td>
                                <td>&nbsp;</td>
                                <td>Sub Caste of ancestors as per NRC 1951 or Caste census of /NRC 1951 মতে পূৰ্ব পুৰুষৰ উপজাতি 1961 & 1971 : </td>
                                <td style="font-weight:bold"><?=$ancestor_subcaste?></td>
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
                    <legend class="h5">Residential Address / বৰ্তমান বসতি ঠিকনা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:25%">House No/Bylane No/Street Name / ঘৰ নং/ৰাস্তা নং/ৰাস্তাৰ নাম : </td>
                                <td style="width:23%; font-weight:bold"><?=$ra_houseno?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td style="width:25%">Landmark/Locality/Ward No / ৱাৰ্ড নং/সূচক চিহ্ন : </td>
                                <td style="width:23%; font-weight:bold"><?=$ra_landmark?></td>
                            </tr>
                            <tr>
                                <td>State / ৰাজ্য : </td>
                                <td style="font-weight:bold"><?=$ra_state?></td>
                                <td>&nbsp;</td>
                                <td>District (P)/ জিলা : </td>
                                <td style="font-weight:bold"><?=$ra_district?></td>
                            </tr>
                            <tr>
                                <td>Sub Division (p)/ মহকুমা : </td>
                                <td style="font-weight:bold"><?=$ra_subdivision?></td>
                                <td>&nbsp;</td>
                                <td>Revenue Circle(p) / ৰাজহ চক্ৰ : </td>
                                <td style="font-weight:bold"><?=$ra_revenuecircle?></td>
                            </tr>
                            <tr>
                                <td>Mouza / মৌজা : </td>
                                <td style="font-weight:bold"><?=$ra_mouza?></td>
                                <td>&nbsp;</td>
                                <td>Village/Town (p)/ গাওঁ/চহৰ : </td>
                                <td style="font-weight:bold"><?=$ra_village?></td>
                            </tr>
                            <tr>
                                <td>Police Station / থানা : </td>
                                <td style="font-weight:bold"><?=$ra_ps?></td>
                                <td>&nbsp;</td>
                                <td>Pin Code / পিনকোড : </td>
                                <td style="font-weight:bold"><?=$ra_pincode?></td>
                            </tr>
                            <tr>
                                <td>Post Office / ডাকঘৰ : </td>
                                <td style="font-weight:bold" colspan="3"><?=$ra_po?></td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Other Details / অন্যান্য তথ্য  </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td style="width:30%">Occupation of Forefather / পূৰ্ব পুৰুষৰ বৃত্তি : </td>
                                <td style="width:18%; font-weight:bold"><?=$forefather_occupation?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td style="width:30%">Purpose of obtaining certificate / প্ৰমান পত্ৰ লোৱাৰ উদ্দেশ্য : </td>
                                <td style="width:18%; font-weight:bold"><?=$certificate_purpose?></td>
                            </tr>
                            <tr>
                                <td colspan="4">Is Father's/Mother's name present in the voter list? / মাক দেউতাকৰ নাম NRC ত আছেনে ? : </td>
                                <td style="font-weight:bold"><?=$parent_voterlist?></td>
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
                                <td>Caste certificate of father or any supporting proof of caste status.<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?=$caste_certificate_type?></td>
                                <td>
                                    <?php if(strlen($caste_certificate)){ ?>
                                        <a href="<?=base_url($caste_certificate)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Report of Gaonburah in case of rural areas / Ward Commissioner in case of urban areas.<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?=$gaonbura_report_type?></td>
                                <td>
                                    <?php if(strlen($gaonbura_report)){ ?>
                                        <a href="<?=base_url($gaonbura_report)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Permanent Resident Certificate (PRC)<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?=$prc_type?></td>
                                <td>
                                    <?php if(strlen($prc)){ ?>
                                        <a href="<?=base_url($prc)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Copy of Legacy data as per NRC 1951, Electoral role between 1966 & 1971<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?=$nrc_type?></td>
                                <td>
                                    <?php if(strlen($nrc)){ ?>
                                        <a href="<?=base_url($nrc)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
                                            <span class="fa fa-download"></span> 
                                            View/Download
                                        </a>
                                    <?php }//End of if ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Recommendation of President / Secretary of District Anuhushit Jati Parishad<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?=$ajp_type?></td>
                                <td>
                                    <?php if(strlen($ajp)){ ?>
                                        <a href="<?=base_url($ajp)?>" class="btn btn-block font-weight-bold text-success" target="_blank">
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
                <a href="<?=base_url('iservices/wptbc/castecertificate/index/'.$obj_id)?>" class="btn btn-primary">
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