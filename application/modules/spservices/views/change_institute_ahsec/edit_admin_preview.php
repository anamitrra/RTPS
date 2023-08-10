<?php
//Application entered Data
$objId = $dbrow->{'_id'}->{'$id'};
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
$ahsec_inst_name =  isset($dbrow->form_data->ahsec_inst_name)? $dbrow->form_data->ahsec_inst_name: "";//$dbrow->form_data->ahsec_inst_name?? '';
   
$board_seaking_adm = $dbrow->form_data->board_seaking_adm ?? '';
$course_seaking_adm = $dbrow->form_data->course_seaking_adm ?? '';
$state_seaking_adm = $dbrow->form_data->state_seaking_adm ?? ''; 
$reason_seaking_adm = $dbrow->form_data->reason_seaking_adm ?? '';   
$postal = $dbrow->form_data->postal ?? '';   

$condi_of_doc = $dbrow->form_data->condi_of_doc ?? ''; 
// End /////////

// Master Data - Registration Details
$mobile_no = $reg_data->mobile_no ?? '';
$sl_no = $reg_data->sl_no ?? '';
$institution_code = $reg_data->institution_code ?? '';
$candidate_name = $reg_data->candidate_name ?? '';
$father_name = $reg_data->father_name ?? '';
$mother_name = $reg_data->mother_name ?? '';
$institution_name = $reg_data->institution_name ?? '';
$registration_number = $reg_data->registration_number ?? '';
$issue_date = $reg_data->issue_date ?? '';

// $new_issue_date = explode('-', $issue_date);
$new_issue_date1 =$issue_date; //$new_issue_date[2]."-".$new_issue_date[1]."-".$new_issue_date[0];

$registration_session = $reg_data->registration_session ?? '';
$sub_1 = $reg_data->sub_1 ?? '';
$sub_2 = $reg_data->sub_2 ?? '';
$sub_3 = $reg_data->sub_3 ?? '';
$sub_4 = $reg_data->sub_4 ?? '';
$sub_5 = $reg_data->sub_5 ?? '';
$sub_6 = $reg_data->sub_6 ?? '';
// End /////////
?>



<style>
/* body {font-family: Arial;} */

/* Style the tab */
.tab {
    overflow: hidden;
    /* border: 1px solid #ccc; */
    /* background-color: #f1f1f1; */
}

/* Style the buttons inside the tab */
.tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 4px 6px;
    transition: 0.3s;
    font-size: 13px;
}

/* Change background color of buttons on hover */
.tab button:hover {
    background-color: #fff;
}

/* Create an active/current tablink class */
.tab button.active {
    background-color: #fff;
}

/* Style the tab content */
.tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    /* border-top: none; */
}
</style>
<script src="<?=base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.js")?>"></script>
<script type="text/javascript">
</script>
<link rel="stylesheet" href="<?=base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.css")?>" type="text/css">
<script>
// openDiv(event, 'preview');

function openDiv(evt, cityName) {

    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
</script>
<!-- 

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script> -->

<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('error') != null) { ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <?= $this->session->flashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
    </div>
    <?php }//End of if ?>
    <div class="accordion" id="accordionTasks">
        <div class="accordion-item">

        </div>
    </div>

    <div class="card shadow-sm mt-2">
        <div class="card-header bg-info">
            <div class="tab">
                <button class="btn-sm tablinks" onclick="openDiv(event, 'preview')">Applicant Data</button>
                <button class="tablinks" onclick="openDiv(event, 'edit')">Edit Migration Details</button>
            </div>
        </div>
        <div class="card-body">
            <div id="preview" class="tabcontent" style="display: block;">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped" style="overflow: scroll;">
                            <tr>
                                <th>ARN</th>
                                <td><?= $dbrow->service_data->appl_ref_no ?></td>
                            </tr>
                            <tr>
                                <th>Name </th>
                                <td><?=$applicant_name?></td>
                            </tr>
                            <tr>
                                <th>Applied For</th>
                                <td><?= $dbrow->service_data->service_name ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><?= $dbrow->service_data->appl_status ?></td>
                            </tr>
                            <tr>
                                <th>Office Copy</th>
                                <td><a target="_blank" class="btn btn-sm btn-primary"href="<?= base_url('/spservices/ahsec_correction/actions/officeCopy/'.$dbrow->service_data->service_id.'/'.$objId)?>">Download Office Copy</a></td>
                            </tr>
                            <tr>
                                <th>Delivery Preference</th>
                                <td><?=$postal?></td>
                            </tr>
                            <tr>
                                <th>Application Recieved</th>
                                <td><?=(!empty($dbrow->service_data->submission_date))? format_mongo_date($dbrow->service_data->submission_date): ""?>
                                </td>
                            </tr>
                        </table>
                        <!-- <h3>Registration Details</h3> -->
                        <br />
                        <br />
                    </div>

                    <div class="col-md-6">
                        <table class="table table-bordered table-striped" style="overflow: scroll;">
                            <tr>
                                <th colspan=2>Applicant Details</th>

                            </tr>
                            <tr>
                                <th>Photo</th>
                                <td> <img src="<?=base_url($dbrow->form_data->photo_of_the_candidate)?>"
                                        style="width:130px; height: 130px; margin: 3px;" /></td>
                            </tr>
                            <tr>
                                <th>Signature</th>
                                <td><img src="<?=base_url($dbrow->form_data->candidate_sign)?>"
                                        style="width:130px;  height: 40px; margin: 3px;" /></td>
                            </tr>
                            <tr>
                                <th>Registrtion Number</th>
                                <td><?=$ahsec_reg_no?></td>
                            </tr>
                            <tr>
                                <th>Registrtion Session</th>
                                <td><?=$ahsec_reg_session?></td>
                            </tr>
                            <tr>
                                <th>Name of the Applicant</th>
                                <td><?=$applicant_name?></td>
                            </tr>
                            <tr>
                                <th>Father&apos;s Name</th>
                                <td><?=$father_name?></td>
                            </tr>
                            <tr>
                                <th>Mother&apos;s Name</th>
                                <td><?=$mother_name?></td>
                            </tr>
                            <tr>
                                <th>Mobile Number</th>
                                <td><?=$mobile?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?=$email?></td>
                            </tr>
                            <tr>
                                <th>Institution Name</th>
                                <td><?=$ahsec_inst_name?></td>
                            </tr>
                            <tr>
                                <th colspan="2">Permanent Address</th>
                            </tr>
                            <tr>
                                <th>Complete Permanent Address</th>
                                <td><?=$comp_permanent_address?></td>
                            </tr>
                            <tr>
                                <th>Pincode</th>
                                <td><?=$pa_pincode?></td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td><?=$pa_state?></td>
                            </tr>
                            <tr>
                                <th>District</th>
                                <td><?=$pa_district?></td>
                            </tr>
                            <tr>
                                <th colspan="2">Postal Address</th>
                            </tr>
                            <tr>
                                <th>Complete Postal Address</th>
                                <td><?=$comp_postal_address?></td>
                            </tr>
                            <tr>
                                <th>Pincode</th>
                                <td><?=$pos_pincode?></td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td><?=$pos_state?></td>
                            </tr>
                            <tr>
                                <th>District</th>
                                <td><?=$pos_district?></td>
                            </tr>
                            <tr>
                                <th colspan="2">Academic Details</th>
                            </tr>

                            <?php if(isset($dbrow->form_data->ahsec_yearofpassing)){ ?>
                            <tr>
                                <th>Years of Passing of H.S. 2nd Year Examination</th>
                                <td><?=$dbrow->form_data->ahsec_yearofpassing?></td>
                            </tr>
                            <?php } ?>
                            <?php if(isset($dbrow->form_data->ahsec_admit_no)){ ?>
                            <tr>
                                <th>Valid H.S. 2nd Year Admit Number</th>
                                <td><?=$dbrow->form_data->ahsec_admit_no?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <th colspan="2">Application Details</th>
                            </tr>
                            <tr>
                                <th>University/Board where seeking admission</th>
                                <td><?=$board_seaking_adm?></td>
                            </tr>
                            <tr>
                                <th>State where seeking admission</th>
                                <td><?=$state_seaking_adm?></td>
                            </tr>
                            <tr>
                                <th>Describe Reason for Seeking Migration</th>
                                <td><?=$reason_seaking_adm?></td>
                            </tr>


                            <?php if(!empty($dbrow->form_data->fir_type)){ ?>
                            <tr>
                                <th><?=$dbrow->form_data->fir_type?></th>
                                <td>
                                    <a href="<?=base_url($dbrow->form_data->fir)?>"
                                        class="btn font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(!empty($dbrow->form_data->paper_advertisement_type)){ ?>
                            <tr>
                                <th><?=$dbrow->form_data->paper_advertisement_type?></th>
                                <td>
                                    <a href="<?=base_url($dbrow->form_data->paper_advertisement)?>"
                                        class="btn font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(!empty($dbrow->form_data->hslc_tenth_mrksht_type)){ ?>
                            <tr>
                                <th><?=$dbrow->form_data->hslc_tenth_mrksht_type?></th>
                                <td>
                                    <a href="<?=base_url($dbrow->form_data->hslc_tenth_mrksht)?>"
                                        class="btn font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(!empty($dbrow->form_data->damage_reg_card_type)){ ?>
                            <tr>
                                <th><?=$dbrow->form_data->damage_reg_card_type?></th>
                                <td>
                                    <a href="<?=base_url($dbrow->form_data->damage_reg_card)?>"
                                        class="btn font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(!empty($dbrow->form_data->damage_admit_card_type)){ ?>
                            <tr>
                                <th><?=$dbrow->form_data->damage_admit_card_type?></th>
                                <td>
                                    <a href="<?=base_url($dbrow->form_data->damage_admit_card)?>"
                                        class="btn font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(!empty($dbrow->form_data->hs_reg_card_type)){ ?>
                            <tr>
                                <th><?=$dbrow->form_data->hs_reg_card_type?></th>
                                <td>
                                    <a href="<?=base_url($dbrow->form_data->hs_reg_card)?>"
                                        class="btn font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(!empty($dbrow->form_data->damage_mrksht_type)){ ?>
                            <tr>
                                <th><?=$dbrow->form_data->damage_mrksht_type?></th>
                                <td>
                                    <a href="<?=base_url($dbrow->form_data->damage_mrksht)?>"
                                        class="btn font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(!empty($dbrow->form_data->hs_admit_card_type)){ ?>
                            <tr>
                                <th><?=$dbrow->form_data->hs_admit_card_type?></th>
                                <td>
                                    <a href="<?=base_url($dbrow->form_data->hs_admit_card)?>"
                                        class="btn font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if(!empty($dbrow->form_data->hs_mrksht_type)){ ?>
                            <tr>
                                <th><?=$dbrow->form_data->hs_mrksht_type?></th>
                                <td>
                                    <a href="<?=base_url($dbrow->form_data->hs_mrksht)?>"
                                        class="btn font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="col-md-6">

                        <table class="table table-bordered table-striped" style="overflow: scroll;">
                            <tr>
                                <th colspan=2>Registration Details</th>

                            </tr>
                            <tr>
                                <th>Photo</th>
                                <td> <img src="" style="width:130px; height: 130px; margin: 3px;" /></td>
                            </tr>
                            <tr>
                                <th>Signature</th>
                                <td><img src="" style="width:130px;  height: 40px; margin: 3px;" /></td>
                            </tr>
                            <tr>
                                <th>Registration Numbers</th>
                                <td><?= $registration_number ?></td>
                            </tr>
                            <tr>
                                <th>Session</th>
                                <td><?= $registration_session ?></td>
                            </tr>

                            <tr>
                                <th>Candidate Name</th>
                                <td><?= $candidate_name ?></td>
                            </tr>
                            <tr>
                                <th>Father&apos;s Name</th>
                                <td><?= $father_name ?></td>
                            </tr>
                            <tr>
                                <th>Mother&apos;s Name</th>
                                <td><?= $mother_name ?></td>
                            </tr>
                            <tr>
                                <th>Mobile Number</th>
                                <td><?=$mobile?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?=$email?></td>
                            </tr>
                            <tr>
                                <th>Institution Name</th>
                                <td><?= $institution_name ?></td>
                            </tr>

                            <tr>
                                <th>Sl No.</th>
                                <td><?= $sl_no ?></td>
                            </tr>
                            <tr>
                                <th>Code No.</th>
                                <td><?= $institution_code ?></td>
                            </tr>

                            <tr>
                                <th>Date</th>
                                <td><?= $issue_date ?></td>
                            </tr>


                            <tr>
                                <th colspan="2">CORE SUBJECTS</th>
                            </tr>
                            <tr>
                                <td><?= $sub_1 ?></td>
                                <td><?= $sub_2 ?></td>
                            </tr>
                            <tr>
                                <th colspan="2">ELECTIVE SUBJECTS</th>
                            </tr>
                            <tr>
                                <td><?= $sub_3 ?></td>
                                <td><?= $sub_4 ?></td>
                            </tr>

                            <tr>
                                <td><?= $sub_5 ?></td>
                                <td><?= $sub_6 ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div id="edit" class="tabcontent" style="color: black;">
                <?php if($dbrow->service_data->service_id == "AHSECMIGR") { ?>
                <form class="form-horizontal"
                    action="<?= base_url('spservices/migrationcertificateahsec/actions/update_reg_master_data/'.$objId) ?>"
                    method="POST" autocomplete="off">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="sl_no">Sl No:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="sl_no" placeholder="Enter Sl No" name="sl_no"
                                value="<?= $sl_no ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="code_no">Code No:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="code_no" placeholder="Enter Code No"
                                name="code_no" value="<?= $institution_code ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="candidate_name">Candidate Name:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="candidate_name"
                                placeholder="Enter Candidate Name" value="<?= $candidate_name ?>" name="candidate_name"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="father_name">Father's Name:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?= $father_name ?>" id="father_name"
                                placeholder="Enter Father's Name" name="father_name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="mother_name">Mother's Name:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?= $mother_name ?>" id="mother_name"
                                placeholder="Enter Mother's Name" name="mother_name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="institution_name">Institution Name:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="institution_name"
                                placeholder="Enter Institution Name" value="<?= $institution_name ?>"
                                name="institution_name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="reg_no">Registration Numbers:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="reg_no" value="<?= $registration_number ?>"
                                placeholder="Enter Registration Numbers" name="reg_no" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="date">Date:</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" value="<?= $new_issue_date1 ?>" id="date"
                                placeholder="Enter Date" name="date" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="session">Session:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?= $registration_session ?>" id="session"
                                placeholder="Enter Session" name="session" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="mobile_no">Mobile No:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="mobile_no" placeholder="Enter Mobile No"
                                name="mobile_no" value="<?= $mobile_no ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="core_sub_1">Core Subject 1:</label>
                        <div class="col-sm-10">

                            <select class="form-control" required id="core_sub_1" name="core_sub_1">
                                <option value="">Select Any One</option>
                                <?php foreach($subjects as $subject) { ?>
                                <option <?php if($subject->subject_code == $sub_1) {?> selected <?php }?>
                                    value="<?= $subject->subject_code ?>">
                                    <?=$subject->subject_code.' | ' .$subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="core_sub_2">Core Subject 2:</label>
                        <div class="col-sm-10">
                            <select class="form-control" required id="core_sub_2" name="core_sub_2">
                                <option value="">Select Any One</option>
                                <?php foreach($subjects as $subject) { ?>
                                <option <?php if($subject->subject_code == $sub_2) {?> selected <?php }?>
                                    value="<?= $subject->subject_code ?>">
                                    <?=$subject->subject_code.' | ' .$subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="elective_sub_3">Elective Subject 1:</label>
                        <div class="col-sm-10">
                            <select class="form-control" required id="elective_sub_3" name="elective_sub_3">
                                <option value="">Select Any One</option>
                                <?php foreach($subjects as $subject) { ?>
                                <option <?php if($subject->subject_code == $sub_3) {?> selected <?php }?>
                                    value="<?= $subject->subject_code ?>">
                                    <?=$subject->subject_code.' | ' .$subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="elective_sub_4">Elective Subject 2:</label>
                        <div class="col-sm-10">
                            <select class="form-control" required id="elective_sub_4" name="elective_sub_4">
                                <option value="">Select Any One</option>
                                <?php foreach($subjects as $subject) { ?>
                                <option <?php if($subject->subject_code == $sub_4) {?> selected <?php }?>
                                    value="<?= $subject->subject_code ?>">
                                    <?=$subject->subject_code.' | ' .$subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="elective_sub_5">Elective Subject 3:</label>
                        <div class="col-sm-10">
                            <select class="form-control" required id="elective_sub_5" name="elective_sub_5">
                                <option value="">Select Any One</option>
                                <?php foreach($subjects as $subject) { ?>
                                <option <?php if($subject->subject_code == $sub_5) {?> selected <?php }?>
                                    value="<?= $subject->subject_code ?>">
                                    <?=$subject->subject_code.' | ' .$subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="elective_sub_6">Elective Subject 4:</label>
                        <div class="col-sm-10">
                            <select class="form-control" required id="elective_sub_6" name="elective_sub_6">
                                <option value="">Select Any One</option>
                                <?php foreach($subjects as $subject) { ?>
                                <option <?php if($subject->subject_code == $sub_6) {?> selected <?php }?>
                                    value="<?= $subject->subject_code ?>">
                                    <?=$subject->subject_code.' | ' .$subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Update Master Data</button>
                        </div>
                    </div>
                </form>
                <?php } ?>
            </div>
            <!--End of .card-->
        </div>
    </div>
</div>