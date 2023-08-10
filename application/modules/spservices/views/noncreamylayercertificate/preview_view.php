<?php
// print_r("Here");
// pre($dbrow);
$obj_id = $dbrow->{'_id'}->{'$id'};
$appl_ref_no = $dbrow->service_data->appl_ref_no;

$certificate_language = $dbrow->form_data->certificate_language;
$applicant_name = $dbrow->form_data->applicant_name;
$applicant_gender = $dbrow->form_data->applicant_gender;
$father_name = $dbrow->form_data->father_name;
$mother_name = $dbrow->form_data->mother_name;
$spouse_name = $dbrow->form_data->spouse_name;
$email = $dbrow->form_data->email;
$dob = $dbrow->form_data->dob;
$mobile_number = $dbrow->form_data->mobile_number;
$aadhaar_number = $dbrow->form_data->aadhaar_number;
$pan_number = $dbrow->form_data->pan_number;

$house_no = $dbrow->form_data->house_no;
// $landmark = $dbrow->landmark;
$state = $dbrow->form_data->state;
$district = $dbrow->form_data->district;
$sub_division = $dbrow->form_data->sub_division;
$revenue_circle = $dbrow->form_data->revenue_circle;
$mouza = $dbrow->form_data->mouza;
$village = $dbrow->form_data->village;
$police_station = $dbrow->form_data->police_station;
$pin_code = $dbrow->form_data->pin_code;
$post_office = $dbrow->form_data->post_office;

$caste_name = $dbrow->form_data->caste_name;
$community_name = $dbrow->form_data->community_name;
$relation = $dbrow->form_data->financial_status->relation;
$organization_types = $dbrow->form_data->financial_status->organization_types;
$organization_names = $dbrow->form_data->financial_status->organization_names;
$fs_designations = $dbrow->form_data->financial_status->fs_designations;

// $isearning_sources = $dbrow->form_data->income_status->isearning_sources;
$annual_salary = $dbrow->form_data->financial_status->annual_salary;
$other_income = $dbrow->form_data->financial_status->other_income;
$total_income = $dbrow->form_data->financial_status->total_income;
// $is_remarks = $dbrow->form_data->income_status->is_remarks;

$residential_proof_type = $dbrow->form_data->residential_proof_type;
$residential_proof = $dbrow->form_data->residential_proof;
$obc_type = $dbrow->form_data->obc_type;
$obc = $dbrow->form_data->obc;
$income_certificate_type = $dbrow->form_data->income_certificate_type;
$income_certificate = $dbrow->form_data->income_certificate;
$other_doc_type = $dbrow->form_data->other_doc_type;
$other_doc = $dbrow->form_data->other_doc;
$soft_copy_type = $dbrow->form_data->soft_copy_type;
$soft_copy = $dbrow->form_data->soft_copy;
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
});
</script>

<main class="rtps-container">
    <div class="container my-2">
        <div class="card shadow-sm" id="printDiv" style="background:#E6F1FF">
            <div class="card-header"
                style="background:#589DBF; text-align: center; font-size: 24px; color: #fff; font-family: georgia,serif; font-weight: bold">
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
                            <!-- Permanent Resident Certificate (PRC). In case PRC is not available voter ID/Electricity
                                bill/Bank Passbook/registered land documents/Jamabandi are accepted in practice
                                [Mandatory]
                                / স্হায়ী বাসিন্দাৰ পত্ৰ । ২. যদি স্হায়ী বাসিন্দাৰ প্ৰমান পত্ৰ নাথাকে, তেন্তে ভোটাৰ
                                কাৰ্ড/বিদুৎ বিল/ বেঙ্ক পাছবুক/ পঞ্জীকৃত মাটিৰ নথি/ জমাবন্দী ( বাধ্যতামূলক ) -->
                            Permanent resident certificate or any other proof of residency [Mandatory] (স্হোনীয়
                            োচসন্দোৰ প্রমোন িত্র ো োচসন্দো সম্পবকজ চযকবনো প্রমোন িত্র [ বাধ্যতামূলক ])
                        </li>
                        <li>
                            OBC / MOBC certificate issued by competent authority [Mandatory]
                            / সংচিষ্ট কতৃজিক্ষৰ িৰো মিোৱো অনযোনয চিছিৰো মেণী / অনযোনয অচত চিছিৰো মেণীৰ প্রমোন িত্র (
                            বাধ্যতামূলক )
                        </li>
                        <li>
                            Income certificate of parents [Mandatory]
                            / পিতৃ মাতৃৰ আয়ৰ প্ৰমাণপত্ৰ ( বাধ্যতামূলক )
                            <ol style="list-style:lower-alpha;  margin-left: 20px; margin-top: 0px">
                                <li>Issued by the Circle Officer ( if the parents are agriculturist ) or / (ক) চক্ৰ
                                    বিষয়াৰ দ্বাৰা ( যদি পিতৃ মাতৃ খেতিয়ক হয় )</li>
                                <li>Income certificate of parents issued by Controlling Authority / Treasury officer
                                    (if the parents are retired salaried person) or / (খ) কোষাগাৰ বিষয়াৰ দ্বাৰা,
                                    যদিহে আবেদনকাৰী পেঞ্চনধাৰী হয় ।</li>
                                <!-- <li>Issued by Councillor/Mouzadar (in practice, in case of a non-salaried person) /
                                        (গ) কাউন্সিলৰ/মৌজাদাৰৰ দ্বাৰা, যদিহে আবেদনকাৰী দৰমহাবিহীন হয় ।</li> -->
                            </ol>
                        </li>
                        <li>
                            Other documents as per requirement (Voter card, Bank passbook, etc.) [Optional]
                            / অন্যান্য নথি যেনে- ভোটাৰ কাৰ্ড, বেঙ্ক পাছবুক
                        </li>
                    </ol>


                    <strong style="font-size:16px;  margin-top: 10px">Fees / মাচুল :</strong>
                    <ol style="list-style:decimal-leading-zero;  margin-left: 24px; margin-top: 10px">
                        <li>Statutory charges / স্হায়ী মাচুল : Rs. 30 / ৩০ টকা</li>
                        <li>Service charge / সেৱা মাচুল – Rs. 30 / ৩০ টকা</li>
                        <li>Printing charge (in case of any printing from PFC) / ছপা খৰচ - Rs. 10 Per Page / প্ৰতি
                            পৃষ্ঠাত ১০ টকা</li>
                        <li>Scanning charge (in case documents are scanned in PFC) স্কেনিং খৰচ - Rs. 5 Per page /
                            প্ৰতি পৃষ্ঠা ৫ টকা</li>
                    </ol>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Applicant&apos;s Details / আবেদনকাৰীৰ বিৱৰণ </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr>
                                <td>Certificate Language/ প্রমান পত্রৰ ভাষা : </td>
                                <td colspan="4" style=" font-weight:bold"><?= $certificate_language ?></td>
                            </tr>
                            <tr>
                                <td>Applicant&apos;s Name/ আবেদনকাৰীৰ নাম : </td>
                                <td style="font-weight:bold"><?= $applicant_name ?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td>Applicant&apos;s Gender/ আবেদনকাৰীৰ লিংগ : </td>
                                <td style="font-weight:bold"><?= $applicant_gender ?></td>
                            </tr>
                            <tr>
                                <td>Father&apos;s Name / পিতৃৰ নাম : </td>
                                <td style="font-weight:bold"><?= $father_name ?></td>
                                <td>&nbsp;</td>
                                <td>Mother&apos;s Name/ মাতৃৰ নাম : </td>
                                <td style="font-weight:bold"><?= $mother_name ?></td>
                            </tr>
                            <tr>
                                <td>Spouse Name / গৃহিনী/স্বামীৰ নাম : </td>
                                <td style="font-weight:bold"><?= $spouse_name ?></td>
                                <td>&nbsp;</td>
                                <td>E-Mail / ই-মেইল : </td>
                                <td style="font-weight:bold"><?= $email ?></td>
                            </tr>
                            <tr>
                                <td>Date of Birth / জন্মৰ তাৰিখ : </td>
                                <td style="font-weight:bold"><?= $dob ?></td>
                                <td>&nbsp;</td>
                                <td>Mobile Number / দুৰভাষ ( মবাইল ) : </td>
                                <td style="font-weight:bold"><?= $mobile_number ?></td>
                            </tr>
                            <tr>
                                <td>Aadhaar Number / আধাৰ নম্বৰ : </td>
                                <td style="font-weight:bold"><?= $aadhaar_number ?></td>
                                <td>&nbsp;</td>
                                <td>PAN Number / পান নম্বৰ : </td>
                                <td style="font-weight:bold"><?= $pan_number ?></td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Address / ঠিকনা </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <!-- <tr>
                                <td style="width:25%">House No / ঘৰ নং :
                                </td>
                                <td style="width:23%; font-weight:bold"><?= $house_no ?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td style="width:25%">Landmark/Locality/Ward No / ৱাৰ্ড নং/সূচক চিহ্ন : </td>
                                <td style="width:23%; font-weight:bold"></td>
                            </tr> -->
                            <tr>
                                <td>State / ৰাজ্য : </td>
                                <td style="font-weight:bold"><?= $state ?></td>
                                <td>&nbsp;</td>
                                <td>District (P)/ জিলা : </td>
                                <td style="font-weight:bold"><?= $district ?></td>
                            </tr>
                            <tr>
                                <td>Sub Division (p)/ মহকুমা : </td>
                                <td style="font-weight:bold"><?= $sub_division ?></td>
                                <td>&nbsp;</td>
                                <td>Revenue Circle(p) / ৰাজহ চক্ৰ : </td>
                                <td style="font-weight:bold"><?= $revenue_circle ?></td>
                            </tr>
                            <tr>
                                <td>Mouza / মৌজা : </td>
                                <td style="font-weight:bold"><?= $mouza ?></td>
                                <td>&nbsp;</td>
                                <td>Village/Town (p)/ গাওঁ/চহৰ : </td>
                                <td style="font-weight:bold"><?= $village ?></td>
                            </tr>
                            <tr>
                                <td>Police Station / থানা : </td>
                                <td style="font-weight:bold"><?= $police_station ?></td>
                                <td>&nbsp;</td>
                                <td>Pin Code / পিনকোড : </td>
                                <td style="font-weight:bold"><?= $pin_code ?></td>
                            </tr>
                            <tr>
                                <td style="width:25%">Post Office / ডাকঘৰ :
                                </td>
                                <td style="width:23%; font-weight:bold"><?= $post_office ?></td>
                                <td style="width:4%">&nbsp;</td>
                                <td style="width:25%">House No / ঘৰ নং : </td>
                                <td style="width:23%; font-weight:bold"><?= $house_no ?></td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>

                <fieldset class="border border-success table-responsive" style="margin-top:40px">
                    <legend class="h5">Other Details / অন্যান্য তথ্য </legend>
                    <table class="table table-borderless" style="margin-top:0px; margin-bottom:0px;">
                        <tbody style="border-top: none !important">
                            <tr><!--
                                <td style="width:25%">Name of Caste / জাতিৰ নাম :
                                </td>
                                <td style="width:23%; font-weight:bold"><?= $caste_name ?></td>
                                <td style="width:4%">&nbsp;</td>-->
                                <td style="width:25%">Name of community / সম্প্ৰদায় নাম : </td>
                                <td style="width:23%; font-weight:bold"><?= $community_name ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered" id="financialstatustbl">
                        <thead>
                            <tr>
                                <th colspan="7" class="text-center" style="font-size:16px">
                                    Financial Status of Parents/ Husband/ Wife / পিতৃ-মাতৃ/স্বামী/স্ত্ৰীৰ আৰ্থিক স্হি
                                </th>
                            </tr>
                            <tr>
                                <th>Source Of Earning / উপাৰ্জনৰ উৎস</th>
                                <th>Type Of Organisation / সংস্হাৰ ধৰণ</th>
                                <th>Name Of Organisation/Department / বিভাগ/সংস্হাৰ নাম</th>
                                <th>Designation/Post Held / পদবী</th>
                                <th>Gross Annual Salary/Amount মুঠ বাৰ্ষিক দৰমহা</th>
                                <th>Income From Other Source / অন্য উৎসৰ পৰা</th>
                                <th>Total / মুঠ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $fsearningSources = (isset($relation) && is_array($relation)) ? count($relation) : 0;
                            if ($fsearningSources > 0) {
                                for ($i = 0; $i < $fsearningSources; $i++) { ?>
                            <tr>
                                <td><?= $relation[$i] ?></td>
                                <td><?= $organization_types[$i] ?></td>
                                <td><?= $organization_names[$i] ?></td>
                                <td><?= $fs_designations[$i] ?></td>
                                <td><?= $annual_salary[$i] ?></td>
                                <td><?= $other_income[$i] ?></td>
                                <td><?= $total_income[$i] ?></td>
                            </tr>
                            <?php }
                            } //End of if  
                            ?>
                        </tbody>
                    </table>

                    <!-- <table class="table table-bordered" id="incomestatustbl">
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
                                <td><?= $isearning_sources[$i] ?></td>
                                <td><?= $annual_salary[$i] ?></td>
                                <td><?= $other_income[$i] ?></td>
                                <td><?= $total_income[$i] ?></td>
                                <td><?= $is_remarks[$i] ?></td>
                            </tr>
                            <?php }
                            } //End of if  
                            ?>
                        </tbody>
                    </table> -->
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
                                <td>Permanent resident certificate or any other proof of residency.<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?= $residential_proof_type ?></td>
                                <td>
                                    <?php if (strlen($residential_proof)) { ?>
                                    <a href="<?= base_url($residential_proof) ?>"
                                        class="btn btn-block font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Digital OBC / MOBC certificate issued by competent authority.<span
                                        class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?= $obc_type ?></td>
                                <td>
                                    <?php if (strlen($obc)) { ?>
                                    <a href="<?= base_url($obc) ?>" class="btn btn-block font-weight-bold text-success"
                                        target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Income certificate of the parents/husband/wife of the applicant from the Circle Officer if they are Agriculturists / Income certificate from controlling authority / Treasury Officer if retired salaried parents.<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?= $income_certificate_type ?></td>
                                <td>
                                    <?php if (strlen($income_certificate)) { ?>
                                    <a href="<?= base_url($income_certificate) ?>"
                                        class="btn btn-block font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Others</td>
                                <td style="font-weight:bold"><?= $other_doc_type ?></td>
                                <td>
                                    <?php if (strlen($other_doc)) { ?>
                                    <a href="<?= base_url($other_doc) ?>"
                                        class="btn btn-block font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Upload the Soft copy of the applicant form<span class="text-danger">*</span></td>
                                <td style="font-weight:bold"><?= $soft_copy_type ?></td>
                                <td>
                                    <?php if (strlen($soft_copy)) { ?>
                                    <a href="<?= base_url($soft_copy) ?>"
                                        class="btn btn-block font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                    <?php } //End of if 
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </fieldset>
            </div>
            <!--End of .card-body -->

            <div class="card-footer text-center no-print">
                <?php if ($appl_status === 'DRAFT') { ?>
                <a href="<?= base_url('spservices/noncreamylayercertificate/registration/index/' . $obj_id) ?>"
                    class="btn btn-primary">
                    <i class="fa fa-pencil"></i> Edit
                </a>
                <?php } ?>
                <button class="btn btn-info" id="printBtn" type="button">
                    <i class="fa fa-print"></i> Print
                </button>
                <?php if ($appl_status === 'QS') { ?>
                <a href="<?= base_url('spservices/noncreamylayercertificate/registration/post_query_respose_data/' . $obj_id) ?>"
                    class="btn btn-success frmsbbtn">
                    <i class="fa fa-angle-double-right"></i> Submit
                </a>
                <?php } else { ?>
                <a href="<?= base_url('spservices/noncreamylayercertificate/payment/initiate/' . $obj_id) ?>"
                    class="btn btn-success frmsbbtn">
                    <i class="fa fa-angle-double-right"></i> Make Payment
                </a>
                <?php } ?>
            </div>
            <!--End of .card-footer-->
        </div>
        <!--End of .card-->
    </div>
    <!--End of .container-->
</main>