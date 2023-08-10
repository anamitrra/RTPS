<?php
//Application entered Data
$objId = $dbrow->{'_id'}->{'$id'};
$father_name_app = $dbrow->form_data->father_name;
$mother_name_app = $dbrow->form_data->mother_name;
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
$ahsec_inst_name =  isset($dbrow->form_data->institution_name) ? $dbrow->form_data->institution_name : ""; //

$ahsec_admit_roll = ((!empty($dbrow->form_data->ahsec_admit_roll)) ? $dbrow->form_data->ahsec_admit_roll : "") ?? '';

if($ahsec_admit_roll !=null && is_numeric($ahsec_admit_roll))
{
    $ahsec_admit_roll = str_pad($ahsec_admit_roll, 4, '0', STR_PAD_LEFT);
}

$ahsec_admit_no = $dbrow->form_data->ahsec_admit_no ?? '';
$ahsec_inst_name =  isset($dbrow->form_data->ahsec_inst_name)? $dbrow->form_data->ahsec_inst_name: "";//$dbrow->form_data->ahsec_inst_name?? '';
$new_issue_date1 = "";
if (!empty($issue_date)) {
    $new_issue_date = explode('-', $issue_date);
    $new_issue_date1 = $new_issue_date[2] . "-" . $new_issue_date[1] . "-" . $new_issue_date[0];
}

$board_seaking_adm = $dbrow->form_data->board_seaking_adm ?? '';
$course_seaking_adm = $dbrow->form_data->course_seaking_adm ?? '';
$state_seaking_adm = $dbrow->form_data->state_seaking_adm ?? ''; 
$reason_seaking_adm = $dbrow->form_data->reason_seaking_adm ?? '';   
$postal = $dbrow->form_data->postal ?? '';   

$hs_marksheet = $dbrow->form_data->hs_marksheet ?? '';
$hs_marksheet_type = $dbrow->form_data->hs_marksheet_type ?? '';
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

$new_issue_date1 = "";
if (!empty($issue_date)) {
    $new_issue_date = explode('-', $issue_date);
    $new_issue_date1 = $new_issue_date[2] . "-" . $new_issue_date[1] . "-" . $new_issue_date[0];
}

$gender = $reg_data->gender ?? '';

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
        tablinks[i].className = tablinks[i].className.replace("active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += "active";
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
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php } //End of if 
    ?>
    <?php if ($this->session->flashdata('success') != null) { ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <a href='<?= base_url('spservices/migrationcertificateahsec/actions/preview/' . $objId) ?>' class='btn btn-primary btn-sm' target='_blank'>View</a>
        <?php $processLink = base_url('spservices/upms/myapplications/process/' . $objId); ?>
        <a href="<?= $processLink ?>" class="btn btn-warning btn-sm">Process</a>
    <?php } //End of if 
    ?>
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
                                <td><?= $applicant_name ?></td>
                            </tr>
                            <tr>
                                <th>Applied For</th>
                                <td><?= $dbrow->service_data->service_name ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <?php
                                    if ($dbrow->service_data->appl_status == "submitted") {
                                        print "New Application";
                                    } elseif ($dbrow->service_data->appl_status == "F") {
                                        print "Forwarded";
                                    } elseif ($dbrow->service_data->appl_status == "QS") {
                                        print "Query Made";
                                    } elseif ($dbrow->service_data->appl_status == "R") {
                                        print "Rejected";
                                    } elseif ($dbrow->service_data->appl_status == "D") {
                                        print "Delivered";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Office Copy</th>
                                <td><a target="_blank" class="btn btn-sm btn-primary"
                                        href="<?= base_url('/spservices/migrationcertificateahsec/actions/officeCopy/' . $dbrow->service_data->service_id . '/' . $objId) ?>">Download
                                        Office Copy</a></td>
                            </tr>
                            <tr>
                                <th>Delivery Preference</th>
                                <td><?= $postal ?></td>
                            </tr>
                            <tr>
                                <th>Application Recieved</th>
                                <td><?= (!empty($dbrow->service_data->submission_date)) ? format_mongo_date($dbrow->service_data->submission_date) : "" ?>
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
                                <th>Particulers</th>
                                <th>Applicant Details</th>
                            </tr>
                            <tr>
                                <th>Photo</th>
                                <td> <img src="<?= base_url($dbrow->form_data->photo_of_the_candidate) ?>"
                                        style="width:130px; height: 130px; margin: 3px;" /></td>
                            </tr>
                            <tr>
                                <th>Signature</th>
                                <td><img src="<?= base_url($dbrow->form_data->candidate_sign) ?>"
                                        style="width:130px;  height: 40px; margin: 3px;" /></td>
                            </tr>
                            <tr>
                                <th>Registrtion Number</th>
                                <td><?= $ahsec_reg_no ?></td>
                            </tr>
                            <tr>
                                <th>Registrtion Session</th>
                                <td><?= $ahsec_reg_session ?></td>
                            </tr>
                            <?php if (isset($ahsec_yearofpassing) && !empty($ahsec_yearofpassing)) { ?>
                            <tr>
                                <th><?php if ($dbrow->service_data->service_id == "AHSECDPC") { ?>Year of Passing in HS
                                    Final Examination <?php } else { ?>Year of Appearing in HS Final
                                    Examination<?php } ?></th>
                                <td><?= $ahsec_yearofpassing ?></td>
                            </tr>
                            <?php } ?>
                            <?php if (isset($ahsec_admit_roll) && !empty($ahsec_admit_roll)) { ?>
                            <tr>
                                <th>Valid H.S Final Examination Roll</th>
                                <td><?= $ahsec_admit_roll ?></td>
                            </tr>
                            <?php } ?>
                            <?php if (isset($ahsec_admit_no) && !empty($ahsec_admit_no)) { ?>
                            <tr>
                                <th>Valid H.S. Final Year Examination Admit Number</th>
                                <td><?= $ahsec_admit_no ?></td>
                            </tr>
                            <?php } ?>

                            <?php if ($applicant_name != $candidate_name) { ?>
                            <tr style="border: 2px solid red;">
                                <th>Name of the Applicant</th>
                                <td>
                                    <?= $applicant_name ?>
                                </td>
                            </tr>
                            <?php } else { ?>
                            <tr>
                                <th>Name of the Applicant</th>
                                <td>
                                    <?= $applicant_name ?>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php if ($father_name_app != $father_name) { ?>
                            <tr style="border: 2px solid red;">
                                <th>Father&apos;s Name</th>
                                <td>
                                    <?= $father_name_app ?>
                                </td>
                            </tr>
                            <?php } else { ?>
                            <tr>
                                <th>Father&apos;s Name</th>
                                <td><?= $father_name_app ?></td>
                            </tr>
                            <?php } ?>
                            <?php if ($mother_name_app != $mother_name) { ?>
                            <tr style="border: 2px solid red;">
                                <th>Mother&apos;s Name</th>
                                <td>
                                    <?= $mother_name_app ?>
                                </td>
                            </tr>
                            <?php } else { ?>
                            <tr>
                                <th>Mother&apos;s Name</th>
                                <td><?= $mother_name_app ?></td>
                            </tr>
                            <?php } ?>
                            <?php if ($mobile_no != $mobile) { ?>
                            <tr style="border: 2px solid red;">
                                <th>Mobile Number</th>
                                <td><?= $mobile ?></td>
                            </tr>
                            <?php } else { ?>
                            <tr>
                                <th>Mobile Number</th>
                                <td><?= $mobile ?></td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <th>Email</th>
                                <td><?= $email ?></td>
                            </tr>
                            <?php if ($institution_name != $ahsec_inst_name) { ?>
                            <tr style="border: 2px solid red;">
                                <th>Institution Name</th>
                                <td><?= $ahsec_inst_name ?></td>
                            </tr>
                            <?php } else { ?>
                            <tr>
                                <th>Institution Name</th>
                                <td><?= $ahsec_inst_name ?></td>
                            </tr>
                            <?php } ?>

                            
                            <tr>
                                <th>Application details</th>
                                <td></td>
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
                            <?php if (!empty($dbrow->form_data->hs_reg_card_type)) { ?>
                            <tr>
                                <th><?= $dbrow->form_data->hs_reg_card_type ?></th>
                                <td>
                                    <a href="<?= base_url($dbrow->form_data->hs_reg_card) ?>"
                                        class="btn font-weight-bold text-success" target="_blank">
                                        <span class="fa fa-download"></span>
                                        View/Download
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>

                            <?php if (!empty($dbrow->form_data->hs_marksheet_type)) { ?>
                            <tr>
                                <th><?= $dbrow->form_data->hs_marksheet_type ?></th>
                                <td>
                                    <a href="<?= base_url($dbrow->form_data->hs_marksheet) ?>"
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
                        <?php if ($reg_data_cnt == 0) { ?>
                        <p>No Record found in Master Database</p>
                        <?php } else if ($reg_data_cnt < 2) { ?>
                        <table class="table table-bordered table-striped" style="overflow: scroll;">
                            <tr>
                                <th></th>
                                <th>Registration Details</th>
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
                                <th>CORE SUBJECTS</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td><?= $sub_1 ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><?= $sub_2 ?></td>
                            </tr>
                            <tr>
                                <th>ELECTIVE SUBJECTS</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td><?= $sub_3 ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><?= $sub_4 ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><?= $sub_5 ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><?= $sub_6 ?></td>
                            </tr>
                        </table>
                        <?php } else { ?>
                        <p>We have found multiple records in Master Database agaist Registration No. and Registration
                            Session.</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div id="edit" class="tabcontent" style="color: black;">
                <?php if ($reg_data_cnt == 0) { ?>
                <p>No Record found in Master Database. You can enter applicant data in master database.</p>
                <form class="form-horizontal"
                    action="<?= base_url('spservices/migrationcertificateahsec/actions/insert_reg_master_data/' . $objId) ?>"
                    method="POST" autocomplete="off">
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="sl_no">Sl No:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="sl_no" placeholder="Enter Sl No" name="sl_no"
                                required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="code_no">Code No:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="code_no" placeholder="Enter Code No"
                                name="code_no" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" for="candidate_name">Candidate Name:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="candidate_name"
                                placeholder="Enter Candidate Name" name="candidate_name" required>
                            <input type="hidden" value="<?= $dbrow->service_data->service_id ?>" name="service_id"
                                readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" for="father_name">Father's Name: </label></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="father_name" placeholder="Enter Father's Name"
                                name="father_name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" for="mother_name">Mother's Name: </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="mother_name" placeholder="Enter Mother's Name"
                                name="mother_name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" for="gender">Gender: </label>
                        <div class="col-sm-10">
                            <select name="gender" class="form-control" id="gender" required>
                                <option selected disabled>Select Gender</option>
                                <option value="MALE">MALE</option>
                                <option value="FEMALE">FEMALE</option>
                                <option value="TRANSGENDER">TRANSGENDER</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="institution_name">Institution Name:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="institution_name"
                                placeholder="Enter Institution Name" name="institution_name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="reg_no">Registration Numbers:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="reg_no" placeholder="Enter Registration Numbers"
                                name="reg_no" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" for="date">Date Issued:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?= date('d-m-Y') ?>" id="date" name="date"
                                required readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" for="session">Session:</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="session" name="session" required>
                                <option selected disabled>Please Select</option>
                                <?php foreach ($sessions as $sessionss) { ?>
                                <option value="<?php echo $sessionss ?>"><?php echo $sessionss ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="mobile_no">Mobile No:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="mobile_no" placeholder="Enter Mobile No"
                                name="mobile_no" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="core_sub_1">Core Subject 1:</label>
                        <div class="col-sm-10">

                            <select class="form-control" required id="core_sub_1" name="core_sub_1">
                                <option disabled selected>Select Any One</option>
                                <?php foreach ($subjects as $subject) { ?>
                                <option value="<?= $subject->subject_code ?>">
                                    <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="core_sub_2">Core Subject 2:</label>
                        <div class="col-sm-10">
                            <select class="form-control" required id="core_sub_2" name="core_sub_2">
                                <option disabled selected>Select Any One</option>
                                <?php foreach ($subjects as $subject) { ?>
                                <option value="<?= $subject->subject_code ?>">
                                    <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="elective_sub_3">Elective Subject 1:</label>
                        <div class="col-sm-10">
                            <select class="form-control" required id="elective_sub_3" name="elective_sub_3">
                                <option disabled selected>Select Any One</option>
                                <?php foreach ($subjects as $subject) { ?>
                                <option value="<?= $subject->subject_code ?>">
                                    <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="elective_sub_4">Elective Subject 2:</label>
                        <div class="col-sm-10">
                            <select class="form-control" required id="elective_sub_4" name="elective_sub_4">
                                <option disabled selected>Select Any One</option>
                                <?php foreach ($subjects as $subject) { ?>
                                <option value="<?= $subject->subject_code ?>">
                                    <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="elective_sub_5">Elective Subject 3:</label>
                        <div class="col-sm-10">
                            <select class="form-control" required id="elective_sub_5" name="elective_sub_5">
                                <option disabled selected>Select Any One</option>
                                <?php foreach ($subjects as $subject) { ?>
                                <option value="<?= $subject->subject_code ?>">
                                    <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="elective_sub_6">Elective Subject 4:</label>
                        <div class="col-sm-10">
                            <select class="form-control" required id="elective_sub_6" name="elective_sub_6">
                                <option disabled selected>Select Any One</option>
                                <?php foreach ($subjects as $subject) { ?>
                                <option value="<?= $subject->subject_code ?>">
                                    <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Submit Master Data</button>
                        </div>
                    </div>
                </form>
                <?php } else if ($reg_data_cnt < 2) { ?>
                <form class="form-horizontal"
                    action="<?= base_url('spservices/migrationcertificateahsec/actions/update_reg_master_data/' . $objId) ?>"
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
                        <label class="control-label col-sm-12" for="candidate_name">Candidate Name:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="candidate_name"
                                placeholder="Enter Candidate Name" value="<?= $candidate_name ?>" name="candidate_name"
                                required>

                            <input type="hidden" value="<?= $dbrow->service_data->service_id ?>" name="service_id"
                                readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" for="father_name">Father's Name: </label></label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?= $father_name ?>" id="father_name"
                                placeholder="Enter Father's Name" name="father_name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" for="mother_name">Mother's Name: </label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?= $mother_name ?>" id="mother_name"
                                placeholder="Enter Mother's Name" name="mother_name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" for="gender">Gender: </label>
                        <div class="col-sm-10">
                            <select name="gender" class="form-control" required>
                                <option value="">Please Select</option>
                                <option value="MALE" <?= ($gender === "MALE") ? 'selected' : '' ?>>MALE
                                </option>
                                <option value="FEMALE" <?= ($gender === "FEMALE") ? 'selected' : '' ?>>FEMALE
                                </option>
                                <option value="TRANSGENDER" <?= ($gender === "TRANSGENDER") ? 'selected' : '' ?>>
                                    TRANSGENDER</option>
                            </select>
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
                                placeholder="Enter Registration Numbers" name="reg_no" required readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" for="date">Registration Date:</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" value="<?= $new_issue_date1 ?>" id="date"
                                placeholder="Enter Date" name="date" required readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" for="date">Date Issued:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?= date('d-m-Y') ?>" id="date"
                                name="current_date" required readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-12" for="session">Session:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" value="<?= $registration_session ?>" id="session"
                                placeholder="Enter Session" name="session" required readonly>
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
                                <?php foreach ($subjects as $subject) { ?>
                                <option <?php if ($subject->subject_code == $sub_1) { ?> selected <?php } ?>
                                    value="<?= $subject->subject_code ?>">
                                    <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="core_sub_2">Core Subject 2:</label>
                        <div class="col-sm-10">
                            <select class="form-control" required id="core_sub_2" name="core_sub_2">
                                <option value="">Select Any One</option>
                                <?php foreach ($subjects as $subject) { ?>
                                <option <?php if ($subject->subject_code == $sub_2) { ?> selected <?php } ?>
                                    value="<?= $subject->subject_code ?>">
                                    <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="elective_sub_3">Elective Subject 1:</label>
                        <div class="col-sm-10">
                            <select class="form-control" required id="elective_sub_3" name="elective_sub_3">
                                <option value="">Select Any One</option>
                                <?php foreach ($subjects as $subject) { ?>
                                <option <?php if ($subject->subject_code == $sub_3) { ?> selected <?php } ?>
                                    value="<?= $subject->subject_code ?>">
                                    <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="elective_sub_4">Elective Subject 2:</label>
                        <div class="col-sm-10">
                            <select class="form-control" required id="elective_sub_4" name="elective_sub_4">
                                <option value="">Select Any One</option>
                                <?php foreach ($subjects as $subject) { ?>
                                <option <?php if ($subject->subject_code == $sub_4) { ?> selected <?php } ?>
                                    value="<?= $subject->subject_code ?>">
                                    <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="elective_sub_5">Elective Subject 3:</label>
                        <div class="col-sm-10">
                            <select class="form-control" required id="elective_sub_5" name="elective_sub_5">
                                <option value="">Select Any One</option>
                                <?php foreach ($subjects as $subject) { ?>
                                <option <?php if ($subject->subject_code == $sub_5) { ?> selected <?php } ?>
                                    value="<?= $subject->subject_code ?>">
                                    <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-2" for="elective_sub_6">Elective Subject 4:</label>
                        <div class="col-sm-10">
                            <select class="form-control" required id="elective_sub_6" name="elective_sub_6">
                                <option value="">Select Any One</option>
                                <?php foreach ($subjects as $subject) { ?>
                                <option <?php if ($subject->subject_code == $sub_6) { ?> selected <?php } ?>
                                    value="<?= $subject->subject_code ?>">
                                    <?= $subject->subject_code . ' | ' . $subject->subject_name ?></option>
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
                <?php } else { ?>
                <p>We have found multiple records in Master Database agaist Registration No. and Registration Session.
                </p>
                <?php } ?>
            </div>
            <!--End of .card-->
        </div>
    </div>
</div>