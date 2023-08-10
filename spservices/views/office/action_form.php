<link rel="stylesheet" href="<?= base_url('assets/plugins/select2/css/select2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">

<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<link href="<?= base_url('assets/fileupload/css/fileinput.css') ?>" media="all" rel="stylesheet" type="text/css" />
<script src="<?= base_url('assets/fileupload/js/fileinput.js') ?>" type="text/javascript"></script>

<style>
    .mandatory {
        color: red
    }

    .wrapper {
        border: 2px solid #808080;
        height: 100%
    }

    .topbox {
        margin: 0 auto;
        padding: 40px 0 0px 0;
        /* border-bottom: 2px solid #eee; */
    }

    .main {
        border-top: 2px solid #9a9a9a;
        margin-top: 20px;
        padding-top: 20px;
        padding: 20px;
        font-size: 14px;
    }
</style>
<div class="content-wrapper">
    <div class="container-fluid mt-3">
        <div class="card shadow" style="background:#9edad8">
            <div class="card-body">
                <table class="table table-bordered table-sm" id="application_list_table">
                    <!-- <thead> -->
                    <?php foreach ($application_data as $value) {
                        foreach ($task_data as $td) { ?>
                            <tr>
                                <th>Service Name</th>
                                <td><?php echo $value->service_data->service_name; ?></td>
                            </tr>
                            <tr>
                                <th>Application Ref. No</th>
                                <td class="text-danger"><b><?php echo $value->service_data->appl_ref_no; ?></b></td>
                            </tr>
                            <tr>
                                <th>Application Received Date</th>
                                <td><?php echo date('d/m/Y', strtotime($this->mongo_db->getDateTime($value->form_data->created_at))); ?></td>
                            </tr>
                            <tr>
                                <th>Task Name</th>
                                <td class=""><b><?php echo $td->task_name; ?></b></td>
                            </tr>
                    <?php }
                    } ?>
                </table>
            </div>
        </div>
        <div id="accordion">
            <div class="row">
                <div class="col-sm-6">
                    <a class="btn btn-dark btn-block" data-toggle="collapse" href="#collapseOne">Application Details</b></a>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-danger btn-block" data-toggle="collapse" href="#collapsetwo">Process History</b></a>
                </div>
            </div>
            <div class="card shadow">
                <div id="collapseOne" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        <?php $this->load->view('office/application_view', $application_data); ?>
                    </div>
                </div>
                <div id="collapsetwo" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                        <table class="table table-bordered table-striped table-sm" id="application_list_table">
                            <thead>
                                <tr>
                                    <th>SL. No</th>
                                    <th>Task Name</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Received date</th>
                                    <th>Action date</th>
                                    <th>Remarks</th>
                                    <th>Attachment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($application_data as $res) {
                                    $exe_data = $res->execution_data;
                                    for ($j = 0; $j < count($exe_data); $j++) {
                                ?>
                                        <tr>
                                            <td><?php echo $i++; ?></td>
                                            <td><?= $exe_data[$j]->task_details->task_name ?></td>
                                            <td>
                                                <?php if ($exe_data[$j]->task_details->user_type == 'Official') {
                                                    echo $exe_data[$j]->task_details->user_detail->user_name . ' (' . $exe_data[$j]->task_details->user_detail->designation . ')';
                                                } else {
                                                    echo 'Applicant';
                                                } ?>
                                            </td>
                                            <td>
                                                <?= (isset($exe_data[$j]->official_form_details->action)) ?  $exe_data[$j]->official_form_details->action : ''  ?>
                                            </td>
                                            <td><?= format_mongo_date($exe_data[$j]->task_details->received_time); ?></td>
                                            <!-- execution_data.applicant_task_details.query_answered -->
                                            <td><?= ($exe_data[$j]->task_details->executed_time) ? format_mongo_date($exe_data[$j]->task_details->executed_time) : '' ?></td>
                                            <td><?= (isset($exe_data[$j]->official_form_details->remarks)) ? $exe_data[$j]->official_form_details->remarks  : ($exe_data[$j]->applicant_task_details->query_answered) ?? '' ?></td>
                                            <td><?= (isset($exe_data[$j]->official_form_details->documents)) ?  '<a href="' . base_url($exe_data[$j]->official_form_details->documents) . '" class="btn btn-xs btn-warning" target="_blank">View/Download</a>' : '' ?></td>

                                            <!-- <td><?= (isset($exe_data[$j]->official_form_details->documents)) ?  '<a href="' . base_url($exe_data[$j]->official_form_details->documents) . '" class="btn btn-xs btn-warning" target="_blank">View/Download</a>' : ((!empty($exe_data[$j]->applicant_task_details->query_ans_file)) ? '<a href="' . base_url($exe_data[$j]->applicant_task_details->query_ans_file) . '" class="btn btn-xs btn-info" target="_blank">View/Download</a>' : '') ?></td> -->
                                        </tr>
                                <?php  }
                                    // }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-header text-white" style="background:#1a4066 !important">Action Form</div>
                <!-- task number 2 -->
                <!-- DPS task  -->
                <?php
                $user_assoc_task = $task_data[0]->user_assoc;
                // print_r($task_data);
                $user_role = $this->session->userdata('role_slug');
                // print_r($user_assoc_task);
                if (in_array($user_role, $user_assoc_task)) {
                    // echo $task_data[0]->user_assoc;
                ?>

                    <?php if (($value->execution_data[0]->task_details->task_id == '1') ||
                        ($value->execution_data[0]->task_details->task_id == '2')
                        && ($value->execution_data[0]->task_details->action_taken == 'N')
                    ) { ?>
                        <div class="card-body">
                            <form id="action_form_1" action="<?= base_url($action) ?>" method="POST">
                                <input type="hidden" value="<?php echo base64_encode(($value->execution_data[0]->task_details->task_id) == '1' ? '2' : '2') ?>" name="task_no">
                                <input type="hidden" value="<?php echo base64_encode($value->service_data->service_id); ?>" name="service">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Action <span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <select name="action_type" id="action_type" class="form-control" required>
                                            <option value="" selected disabled>Select action</option>
                                            <option value="F">Forward to DA</option>
                                            <option value="R">Reject</option>
                                            <option value="RA">Revert back to Applicant</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2 reject_dropdown" style="display:none">
                                    <div class="col-md-6">
                                        <label for="">Reject Reason <span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control" name="reject_reason" style="width:100%">
                                            <option value="" selected disabled>Select Reason</option>
                                            <option value="Insufficient Supporting Documents">Insufficient Supporting Documents</option>
                                            <option value="Improper Data in the Application">Improper Data in the Application</option>
                                            <option value="Not Eligible for Minority Certificate">Not Eligible for Minority Certificate</option>
                                            <option value="Others">Others</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-2 user_div" style="display:none">
                                    <div class="col-md-6">
                                        <label for="">User <span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- <div id="userlist_div"> -->
                                        <select class="form-control select2 userlist_div" name="users" id="user_list" style="width:100%"></select>
                                        <!-- </div> -->
                                    </div>
                                </div>
                                <div class="row mt-1 official_remark_div">
                                    <div class="col-md-6">
                                        <label for="">Remarks <span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <textarea name="remarks" cols="30" rows="2" class="form-control official_remark" required></textarea>
                                    </div>
                                </div>

                                <div class="row mt-2 query_div" style="display:none">
                                    <div class="col-md-6">
                                        <label for="">Remarks <span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <textarea name="applicant_query" id="query_to_application" cols="30" rows="2" class="form-control query_to_application"></textarea>

                                    </div>
                                </div>
                                <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->service_data->appl_ref_no); ?>">
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                            </div>
                        </div>
                        </form>
                        <!-- end DPS task  -->
                        <!-- task number 3 -->
                        <!-- DA task  -->
                    <?php } elseif (($value->execution_data[0]->task_details->task_id == '3') && ($value->execution_data[0]->task_details->action_taken == 'N')) { ?>
                        <div class="card-body">
                            <form id="action_form_1" action="<?= base_url($action) ?>" method="POST">
                                <input type="hidden" value="<?php echo base64_encode($value->execution_data[0]->task_details->task_id) ?>" name="task_no">
                                <input type="hidden" value="<?php echo base64_encode($value->service_data->service_id); ?>" name="service">
                                <input type="hidden" value="<?php echo ($value->form_data->pa_circle); ?>" id="location">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Action <span class="mandatory">*</span></label>
                                    </div>
                                    <?php if (($value->form_data->pa_district_name == 'Karbi Anglong') || ($value->form_data->pa_district_name == 'West Karbi Anglong') || ($value->form_data->pa_district_name == 'Dima Hasao')) { ?>
                                        <div class="col-md-6">
                                            <select name="action_type" id="action_type_2" class="form-control" required>
                                                <option value="" selected disabled>Select action</option>
                                                <option value="F">Forward to RO/ARO</option>
                                                <option value="RO">Revert to DPS</option>
                                            </select>
                                        </div>
                                    <?php } else { ?>
                                        <div class="col-md-6">
                                            <select name="action_type" id="action_type_2" class="form-control" required>
                                                <option value="" selected disabled>Select action</option>
                                                <option value="F">Forward to CO</option>
                                                <option value="RO">Revert to DPS</option>
                                            </select>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="row mt-2 user_div" style="display:none">
                                    <div class="col-md-6">
                                        <label for="">User <span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-control select2 userlist_div" name="users" id="user_list" style="width:100%"></select>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-6">
                                        <label for="">Remarks <span class="mandatory">*</span></label>
                                    </div>
                                    <div class="col-md-6">
                                        <textarea name="remarks" id="" cols="30" rows="2" class="form-control" required></textarea>
                                    </div>
                                </div>

                                <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->service_data->appl_ref_no); ?>">
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-center">
                                <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                            </div>
                            </form>
                            <!-- end DA task  -->
                            <!-- task number 4 -->
                            <!-- CO task  -->
                        <?php } elseif (($value->execution_data[0]->task_details->task_id == '4' || $value->execution_data[0]->task_details->task_id == '16')  && ($value->execution_data[0]->task_details->action_taken == 'N')) { ?>
                            <div class="card-body">

                                <form id="action_form_1" action="<?= base_url($action) ?>" method="POST">
                                    <?php $dps_office = count($value->execution_data) - 1; ?>
                                    <input type="hidden" value="<?php echo base64_encode($value->execution_data[0]->task_details->task_id) ?>" name="task_no">
                                    <input type="hidden" value="<?php echo base64_encode($value->service_data->service_id); ?>" name="service">
                                    <!-- <input type="hidden" value="<?php echo base64_encode($value->execution_data[$dps_office]->task_details->user_detail->user_id); ?>" name="dps_id" id="dps_id">      -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Action <span class="mandatory">*</span></label>
                                        </div>
                                        <?php if (($value->form_data->pa_district_name == 'Karbi Anglong') || ($value->form_data->pa_district_name == 'West Karbi Anglong') || ($value->form_data->pa_district_name == 'Dima Hasao')) { ?>
                                            <div class="col-md-6">
                                                <select name="action_type" id="action_type_3" class="form-control" required>
                                                    <option value="" selected disabled>Select action</option>
                                                    <option value="F">Forward to LP</option>
                                                    <option value="RO">Revert to DA</option>
                                                </select>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-md-6">
                                                <select name="action_type" id="action_type_3" class="form-control" required>
                                                    <option value="" selected disabled>Select action</option>
                                                    <option value="F">Forward to SK</option>
                                                    <option value="RO">Revert to DA</option>
                                                </select>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="row mt-2 user_div" style="display:none">
                                        <div class="col-md-6">
                                            <label for="">User <span class="mandatory">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control select2 userlist_div" name="users" id="user_list" style="width:100%"></select>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <label for="">Remarks <span class="mandatory">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <textarea name="remarks" id="" cols="30" rows="2" class="form-control" required></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->service_data->appl_ref_no); ?>">
                                    <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                                </div>
                            </div>
                            </form>
                            <!-- end CO task  -->
                            <!-- task number 5 -->
                            <!-- SK task  -->
                        <?php } elseif (($value->execution_data[0]->task_details->task_id == '5') && ($value->execution_data[0]->task_details->action_taken == 'N')) { ?>
                            <div class="card-body">
                                <form id="action_form_1" action="<?= base_url($action) ?>" method="POST">
                                    <input type="hidden" value="<?php echo base64_encode($value->execution_data[0]->task_details->task_id) ?>" name="task_no">
                                    <input type="hidden" value="<?php echo base64_encode($value->service_data->service_id); ?>" name="service">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Action <span class="mandatory">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <select name="action_type" id="action_type_4" class="form-control" required>
                                                <option value="" selected disabled>Select action</option>
                                                <option value="F">Forward to LM</option>
                                                <option value="RO">Revert to CO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mt-2 user_div" style="display:none">
                                        <div class="col-md-6">
                                            <label for="">User <span class="mandatory">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control select2 userlist_div" name="users" id="user_list" style="width:100%"></select>
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <label for="">Remarks <span class="mandatory">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <textarea name="remarks" id="" cols="30" rows="2" class="form-control" required></textarea>
                                        </div>
                                    </div>

                                    <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->service_data->appl_ref_no); ?>">
                                    <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                                </div>
                            </div>
                            </form>
                            <!-- end SK task  -->
                            <!-- task number 6 -->
                            <!-- LM task  -->
                        <?php } elseif (($value->execution_data[0]->task_details->task_id == '6' || $value->execution_data[0]->task_details->task_id == '17') && ($value->execution_data[0]->task_details->action_taken == 'N')) { ?>
                            <div class="card-body">
                                <form id="action_form_1" action="<?= base_url($action) ?>" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" value="<?php echo base64_encode($value->execution_data[0]->task_details->task_id) ?>" name="task_no">
                                    <input type="hidden" value="<?php echo base64_encode($value->service_data->service_id); ?>" name="service">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="">Action <span class="mandatory">*</span></label>
                                        </div>
                                        <?php if (($value->form_data->pa_district_name == 'Karbi Anglong') || ($value->form_data->pa_district_name == 'West Karbi Anglong') || ($value->form_data->pa_district_name == 'Dima Hasao')) { ?>
                                            <div class="col-md-6">
                                                <select name="action_type" id="action_type_5" class="form-control" required>
                                                    <option value="" selected disabled>Select action</option>
                                                    <option value="F">Forward to RO/ARO</option>
                                                    <option value="RO">Revert to RO/ARO</option>
                                                    <option value="RA">Revert to Applicant</option>
                                                </select>
                                            </div>
                                        <?php } else { ?>
                                            <div class="col-md-6">
                                                <select name="action_type" id="action_type_5" class="form-control" required>
                                                    <option value="" selected disabled>Select action</option>
                                                    <option value="F">Forward to SK</option>
                                                    <option value="RO">Revert to SK</option>
                                                    <option value="RA">Revert to Applicant</option>
                                                </select>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="row mt-2 user_div" style="display:none">
                                        <div class="col-md-6">
                                            <label for="">User <span class="mandatory">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <select class="form-control select2 userlist_div" name="users" id="user_list" style="width:100%"></select>
                                        </div>
                                    </div>
                                    <div class="row mt-1 official_remark_div">
                                        <div class="col-md-6">
                                            <label for="">Remarks <span class="mandatory">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <textarea name="remarks" cols="30" rows="2" class="form-control official_remark" required></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-2 query_div" style="display:none">
                                        <div class="col-md-6">
                                            <label for="">Remarks <span class="mandatory">*</span></label>
                                        </div>
                                        <div class="col-md-6">
                                            <textarea name="applicant_query" id="query_to_application" cols="30" rows="2" class="form-control query_to_application"></textarea>
                                        </div>
                                    </div>
                                    <div class="row mt-2 file_upload_lm" style="display:none">
                                        <div class="col-md-6">
                                            <label for="">Upload Report </label>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="file" class="form-control-file" id="report_file" name="report_file" />
                                        </div>
                                    </div>
                                    <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->service_data->appl_ref_no); ?>">
                                    <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-center">
                                    <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                                </div>
                                </form>
                                <!-- end LM task  -->
                                <!-- task number 7 -->
                                <!-- SK task  -->
                            <?php } elseif (($value->execution_data[0]->task_details->task_id == '7') && ($value->execution_data[0]->task_details->action_taken == 'N')) { ?>
                                <div class="card-body">
                                    <form id="action_form_1" action="<?= base_url($action) ?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" value="<?php echo base64_encode($value->execution_data[0]->task_details->task_id) ?>" name="task_no">
                                        <input type="hidden" value="<?php echo base64_encode($value->service_data->service_id); ?>" name="service">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Action <span class="mandatory">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="action_type" id="action_type_6" class="form-control" required>
                                                    <option value="" selected disabled>Select action</option>
                                                    <option value="F">Forward to CO</option>
                                                    <option value="RO">Revert to LM</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-2 user_div" style="display:none">
                                            <div class="col-md-6">
                                                <label for="">User <span class="mandatory">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control select2 userlist_div" name="users" id="user_list" style="width:100%"></select>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <label for="">Remarks <span class="mandatory">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <textarea name="remarks" id="" cols="30" rows="2" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                        <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->service_data->appl_ref_no); ?>">
                                        <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                                    </div>
                                </div>
                                </form>
                                <!-- end SK task  -->
                                <!-- task number 8 -->
                                <!-- CO task  -->
                            <?php } elseif (($value->execution_data[0]->task_details->task_id == '8' || $value->execution_data[0]->task_details->task_id == '18') && ($value->execution_data[0]->task_details->action_taken == 'N')) { ?>
                                <div class="card-body">
                                    <?php $dps_office = count($value->execution_data) - 1; ?>
                                    <form id="action_form_1" action="<?= base_url($action) ?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" value="<?php echo base64_encode($value->execution_data[0]->task_details->task_id) ?>" name="task_no">
                                        <input type="hidden" value="<?php echo base64_encode($value->service_data->service_id); ?>" name="service">
                                        <!-- <input type="hidden" value="<?php echo base64_encode($value->execution_data[$dps_office]->task_details->user_detail->user_id); ?>" id="dps_id">      -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Action <span class="mandatory">*</span></label>
                                            </div>
                                            <?php if (($value->form_data->pa_district_name == 'Karbi Anglong') || ($value->form_data->pa_district_name == 'West Karbi Anglong') || ($value->form_data->pa_district_name == 'Dima Hasao')) { ?>
                                                <div class="col-md-6">
                                                    <select name="action_type" id="action_type_7" class="form-control" required>
                                                        <option value="" selected disabled>Select action</option>
                                                        <option value="F">Forward to DA</option>
                                                        <option value="RO">Revert to LP</option>
                                                    </select>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-md-6">
                                                    <select name="action_type" id="action_type_7" class="form-control" required>
                                                        <option value="" selected disabled>Select action</option>
                                                        <option value="F">Forward to DA</option>
                                                        <option value="RO">Revert to SK</option>
                                                    </select>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="row mt-2 user_div" style="display:none">
                                            <div class="col-md-6">
                                                <label for="">User <span class="mandatory">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control select2 userlist_div" name="users" id="user_list" style="width:100%"></select>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <label for="">Remarks <span class="mandatory">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <textarea name="remarks" id="" cols="30" rows="2" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                        <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->service_data->appl_ref_no); ?>">
                                        <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                                    </div>
                                </div>
                                </form>
                                <!-- end CO task  -->
                                <!-- task number 9 -->
                                <!-- DA task  -->
                            <?php } elseif (($value->execution_data[0]->task_details->task_id == '9') && ($value->execution_data[0]->task_details->action_taken == 'N')) { ?>
                                <div class="card-body">
                                    <form id="action_form_1" action="<?= base_url($action) ?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" value="<?php echo base64_encode($value->execution_data[0]->task_details->task_id) ?>" name="task_no">
                                        <input type="hidden" value="<?php echo base64_encode($value->service_data->service_id); ?>" name="service">
                                        <input type="hidden" value="<?php echo ($value->form_data->pa_circle); ?>" name="location" id="location">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Action <span class="mandatory">*</span></label>
                                            </div>
                                            <?php if (($value->form_data->pa_district_name == 'Karbi Anglong') || ($value->form_data->pa_district_name == 'West Karbi Anglong') || ($value->form_data->pa_district_name == 'Dima Hasao')) { ?>
                                                <div class="col-md-6">
                                                    <select name="action_type" id="action_type_8" class="form-control" required>
                                                        <option value="" selected disabled>Select action</option>
                                                        <option value="F">Forward to DPS</option>
                                                        <option value="RO">Revert to RO/ARO</option>
                                                    </select>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-md-6">
                                                    <select name="action_type" id="action_type_8" class="form-control" required>
                                                        <option value="" selected disabled>Select action</option>
                                                        <option value="F">Forward to DPS</option>
                                                        <option value="RO">Revert to CO</option>
                                                    </select>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="row mt-2 user_div" style="display:none">
                                            <div class="col-md-6">
                                                <label for="">User <span class="mandatory">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control select2 userlist_div" name="users" id="user_list" style="width:100%"></select>
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <label for="">Remarks <span class="mandatory">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <textarea name="remarks" id="" cols="30" rows="2" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                        <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->service_data->appl_ref_no); ?>">
                                        <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>
                                    </div>
                                </div>
                                </form>
                                <!-- end DA task  -->
                                <!-- task number 10 -->
                                <!-- DPS task  -->
                            <?php } elseif (($value->execution_data[0]->task_details->task_id == '10') && ($value->execution_data[0]->task_details->action_taken == 'N')) { ?>
                                <div class="card-body">
                                    <form id="action_form_1" action="<?= base_url($action) ?>" method="POST" enctype="multipart/form-data">
                                        <input type="hidden" value="<?php echo base64_encode($value->execution_data[0]->task_details->task_id) ?>" name="task_no">
                                        <input type="hidden" value="<?php echo base64_encode($value->service_data->service_id); ?>" name="service">
                                        <input type="hidden" value="<?php echo ($value->form_data->pa_circle); ?>" name="location" id="location">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="">Action <span class="mandatory">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="action_type" id="action_type_9" class="form-control" required>
                                                    <option value="" selected disabled>Select action</option>
                                                    <option value="D">Deliver</option>
                                                    <option value="R">Reject</option>
                                                    <option value="RD">Revert to DA</option>
                                                    <?php if (($value->form_data->pa_district_name == 'Karbi Anglong') || ($value->form_data->pa_district_name == 'West Karbi Anglong') || ($value->form_data->pa_district_name == 'Dima Hasao')) { ?>
                                                        <option value="RC">Revert to RO/ARO</option>
                                                    <?php } else { ?>
                                                        <option value="RC">Revert to CO</option>
                                                    <?php } ?>
                                                    <!-- <option value="RA">Revert Back to Applicant</option> -->
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-2 reject_dropdown" style="display:none">
                                            <div class="col-md-6">
                                                <label for="">Reject Reason <span class="mandatory">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control" name="reject_reason" style="width:100%">
                                                    <option value="" selected disabled>Select Reason</option>
                                                    <option value="Insufficient Supporting Documents">Insufficient Supporting Documents</option>
                                                    <option value="Improper Data in the Application">Improper Data in the Application</option>
                                                    <option value="Not Eligible for Minority Certificate">Not Eligible for Minority Certificate</option>
                                                    <option value="Others">Others</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-2 user_div" style="display:none">
                                            <div class="col-md-6">
                                                <label for="">User <span class="mandatory">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-control select2 userlist_div" name="users" id="user_list" style="width:100%"></select>
                                            </div>
                                        </div>
                                        <div class="row mt-1 official_remark_div">
                                            <div class="col-md-6">
                                                <label for="">Remarks <span class="mandatory">*</span></label>
                                            </div>
                                            <div class="col-md-6">
                                                <textarea name="remarks" id="" cols="30" rows="2" class="form-control official_remark" required></textarea>
                                            </div>
                                        </div>

                                        <input type="hidden" name="appl_no" value="<?php echo base64_encode($value->service_data->appl_ref_no); ?>">
                                        <input type="hidden" name="action_no" value="<?php echo base64_encode(1); ?>">
                                </div>
                                <div class="card-footer">
                                    <div class="d-flex justify-content-center">
                                        <?php if ($value->execution_data[0]->task_details->task_id == '10') {
                                            echo '<button class="btn btn-success btn-sm final_btn" name="save"><i class="fa fa-save"></i> Submit</button>';
                                        } else {
                                            echo '<button class="btn btn-success btn-sm " name="save"><i class="fa fa-save"></i> Submit</button>';
                                        } ?>
                                    </div>
                                </div>
                                </form>
                            <?php } ?>
                            </div>
                        <?php } else {
                        echo '<p class="text-center text-danger">You have no actions to do in this application.</p>';
                    } ?>
                        </div>
            </div>
        </div>
    </div>

    <?PHP $csrf = array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
    ); ?>

    <input id="csrf" type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>" />
    <script src="<?= base_url('assets/plugins/select2/js/select2.min.js') ?>"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#report_file").fileinput({
                dropZoneEnabled: false,
                showUpload: false,
                showRemove: false,
                required: false,
                maxFileSize: 2000,
                allowedFileExtensions: ["jpg", "png", "gif", "pdf"]
            });
            $('.select2').select2({
                placeholder: "Choose one",
            });
            let district = '<?php echo $this->session->userdata('district_name'); ?>';
            // user_div
            $('#action_type').change(function() {
                $('#user_list').empty();
                let action = $(this).val();
                if (action == 'F') {
                    $('.official_remark_div').show();
                    $('.official_remark').prop('required', true);
                    $('.query_to_application').removeAttr('required');
                    $('.query_div').hide();
                    $('.task_div').show();
                    $('.user_div').show();
                    $('#feedback').val('');
                    $('.reject_dropdown').hide();

                    $.ajax({
                        url: '<?php echo site_url("spservices/office/applications/get_da") ?>',
                        method: 'post',
                        data: {
                            dist: district
                        },
                        dataType: 'json',
                        success: function(response) {
                            let users = '<option value ="">Select</option>';
                            response.data.forEach((res) => {
                                users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                            })
                            $('#user_list').html(users);
                        }
                    })
                } else if (action == 'RA') {
                    // official_remark
                    $('.official_remark_div').hide();
                    $('.official_remark').removeAttr('required');
                    $('.query_to_application').prop('required', true);
                    $('.query_div').show();
                    $('.user_div').hide();
                    $('.reject_dropdown').hide();

                } else if (action == 'R') {
                    $('.reject_dropdown').show();
                    //     $('.official_remark_div').show();
                    //     $('.query_div').hide();
                    //     $('.user_div').hide()
                    //     $('.official_remark').prop('required', true);
                    // }
                    // else {
                    $('.official_remark_div').show();
                    $('.official_remark').prop('required', true);
                    $('.query_to_application').removeAttr('required');
                    $('.task_div').hide();
                    $('.user_div').hide();
                    $('.query_div').hide();
                    $('input[name="task_option"]').prop('checked', false);
                    $('input:checkbox').prop('checked', false);
                    $('#feedback').val('');
                }
            })
            // action_type_2
            $('#action_type_2').on('change', function() {
                $('#user_list').empty();
                let value = $(this).val();
                if (value == 'F') {
                    let location = $('#location').val();
                    $('.user_div').show()
                    $.ajax({
                        url: '<?php echo site_url("spservices/office/applications/get_co_list") ?>',
                        method: 'post',
                        data: {
                            dist: district,
                            location: location
                        },
                        dataType: 'json',
                        success: function(response) {
                            let users = '<option value ="">Select</option>';
                            if (response.data.length > 0) {
                                response.data.forEach((res) => {
                                    users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                                })
                            }
                            $('#user_list').html(users);
                        }
                    })
                } else {
                    // $('.user_div').hide()
                    $('.user_div').show()
                    $.ajax({
                        url: '<?php echo site_url("spservices/office/applications/get_dps") ?>',
                        method: 'post',
                        data: {
                            dist: district
                        },
                        dataType: 'json',
                        success: function(response) {
                            let users = '<option value ="">Select</option>';
                            if (response.data.length > 0) {
                                response.data.forEach((res) => {
                                    users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                                })
                            }
                            $('#user_list').html(users);
                        }
                    })
                }
            })
            // action_type_3
            $('#action_type_3').on('change', function() {
                $('#user_list').empty();
                let value = $(this).val();
                let district = '<?= $this->session->userdata('district_name') ?>';
                let url;
                if ((district == 'Karbi Anglong') || (district == 'West Karbi Anglong') || (district == 'Dima Hasao')) {
                    url = '<?php echo site_url("spservices/office/applications/get_lp_list") ?>';
                } else {
                    url = '<?php echo site_url("spservices/office/applications/get_sk_list") ?>';
                }
                if (value == 'F') {
                    $('.user_div').show()
                    $.ajax({
                        url: url,
                        method: 'post',
                        // data: {dist: district},
                        dataType: 'json',
                        success: function(response) {
                            console.log(response.data);
                            console.log(response.data[0]._id)
                            let users = '<option value ="">Select</option>';
                            response.data.forEach((res) => {
                                users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                            })
                            $('#user_list').html(users);
                        }
                    })
                } else if (value == 'RO') {
                    let dps = $('#dps_id').val();
                    $('.user_div').show();
                    $.ajax({
                        url: '<?php echo site_url("spservices/office/applications/get_da_co") ?>',
                        method: 'post',
                        data: {
                            dist: district,
                            dps: dps
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log(response.data);
                            console.log(response.data[0]._id)
                            let users = '<option value ="">Select</option>';
                            response.data.forEach((res) => {
                                users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                            })
                            $('#user_list').html(users);
                        }
                    })
                }
            })

            // action_type_4
            $('#action_type_4').on('change', function() {
                $('#user_list').empty();
                let value = $(this).val();
                if (value == 'F') {
                    $('.user_div').show()
                    $.ajax({
                        url: '<?php echo site_url("spservices/office/applications/get_lm_list") ?>',
                        method: 'post',
                        // data: {dist: district},
                        dataType: 'json',
                        success: function(response) {
                            console.log(response.data);
                            console.log(response.data[0]._id)
                            let users = '<option value ="">Select</option>';
                            response.data.forEach((res) => {
                                users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                            })
                            $('#user_list').html(users);
                        }
                    })
                } else if (value == 'RO') {
                    $('.user_div').show()
                    $.ajax({
                        url: '<?php echo site_url("spservices/office/applications/get_co_list_sk") ?>',
                        method: 'post',
                        dataType: 'json',
                        success: function(response) {
                            console.log(response.data);
                            console.log(response.data[0]._id)
                            let users = '<option value ="">Select</option>';
                            response.data.forEach((res) => {
                                users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                            })
                            $('#user_list').html(users);
                        }
                    })
                }
            })

            // action_type_5
            $('#action_type_5').on('change', function() {
                $('#user_list').empty();
                let value = $(this).val();
                let district = '<?= $this->session->userdata('district_name') ?>';
                let url;
                if ((district == 'Karbi Anglong') || (district == 'West Karbi Anglong') || (district == 'Dima Hasao')) {
                    url = '<?php echo site_url("spservices/office/applications/get_ro_aro_list") ?>';
                } else {
                    url = '<?php echo site_url("spservices/office/applications/get_sk_list") ?>';
                }

                if (value == 'F') {
                    // $('.file_upload_lm').show()
                    $('.official_remark_div').show();
                    $('.official_remark').prop('required', true);
                    $('.query_to_application').removeAttr('required');
                    $('.query_div').hide();
                    $('.user_div').show()
                    $('#user_list').prop('required', true);
                    $('.file_upload_lm').show()
                    $.ajax({
                        url: url,
                        method: 'post',
                        // data: {dist: district},
                        dataType: 'json',
                        success: function(response) {
                            console.log(response.data);
                            console.log(response.data[0]._id)
                            let users = '<option value ="">Select</option>';
                            response.data.forEach((res) => {
                                users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                            })
                            $('#user_list').html(users);
                        }
                    })

                } else if (value == 'RO') {
                    // $('.file_upload_lm').hide();
                    $('.official_remark_div').show();
                    $('.official_remark').prop('required', true);
                    $('.query_to_application').removeAttr('required');
                    $('.query_div').hide();
                    $('.user_div').show()
                    $('#user_list').prop('required', true);
                    $('.file_upload_lm').hide()
                    let district = '<?= $this->session->userdata('district_name') ?>';
                    let url;
                    if ((district == 'Karbi Anglong') || (district == 'West Karbi Anglong') || (district == 'Dima Hasao')) {
                        url = '<?php echo site_url("spservices/office/applications/get_ro_aro_list") ?>';
                    } else {
                        url = '<?php echo site_url("spservices/office/applications/get_sk_list") ?>';
                    }
                    $.ajax({
                        url: url,
                        method: 'post',
                        // data: {dist: district},
                        dataType: 'json',
                        success: function(response) {
                            console.log(response.data);
                            console.log(response.data[0]._id)
                            let users = '<option value ="">Select</option>';
                            response.data.forEach((res) => {
                                users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                            })
                            $('#user_list').html(users);
                        }
                    })
                } else if (value == 'RA') {
                    // $('.file_upload_lm').hide()
                    $('.user_div').hide()
                    $('.official_remark_div').hide();
                    $('.official_remark').removeAttr('required');
                    $('.query_to_application').prop('required', true);
                    $('.query_div').show();
                    $('#user_list').removeAttr('required');
                    $('.file_upload_lm').hide()

                }
            })
            // action_type_6
            $('#action_type_6').on('change', function() {
                $('#user_list').empty();
                let value = $(this).val();
                // alert(value)
                if (value == 'F') {
                    $('.user_div').show()
                    $('#user_list').prop('required', true);
                    $.ajax({
                        url: '<?php echo site_url("spservices/office/applications/get_co_list_sk") ?>',
                        method: 'post',
                        // data: {dist: district, location:location},
                        dataType: 'json',
                        success: function(response) {
                            console.log(response.data);
                            console.log(response.data[0]._id)
                            let users = '<option value ="">Select</option>';
                            response.data.forEach((res) => {
                                users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                            })
                            $('#user_list').html(users);
                        }
                    })
                } else if (value == 'RO') {
                    $('.user_div').show()
                    $('#user_list').prop('required', true);
                    $.ajax({
                        url: '<?php echo site_url("spservices/office/applications/get_lm_list") ?>',
                        method: 'post',
                        dataType: 'json',
                        success: function(response) {
                            console.log(response.data);
                            console.log(response.data[0]._id)
                            let users = '<option value ="">Select</option>';
                            response.data.forEach((res) => {
                                users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                            })
                            $('#user_list').html(users);
                        }
                    })
                }
            });
            //action_type_6
            // action_type_7
            $('#action_type_7').on('change', function() {
                $('#user_list').empty();
                let value = $(this).val();
                // let dps = $('#dps_id').val();
                // alert(value)
                if (value == 'F') {
                    $('.user_div').show()
                    $('#user_list').prop('required', true);
                    $.ajax({
                        url: '<?php echo site_url("spservices/office/applications/get_da_co") ?>',
                        method: 'post',
                        // data: {dist: district, dps:dps},
                        dataType: 'json',
                        success: function(response) {
                            console.log(response.data);
                            // console.log(response.data[0]._id)
                            let users = '<option value ="">Select</option>';
                            response.data.forEach((res) => {
                                users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                            })
                            $('#user_list').html(users);
                        }
                    })
                } else if (value == 'RO') {
                    let url;
                    if ((district == 'Karbi Anglong') || (district == 'West Karbi Anglong') || (district == 'Dima Hasao')) {
                        url = '<?php echo site_url("spservices/office/applications/get_lp_list") ?>';
                    } else {
                        url = '<?php echo site_url("spservices/office/applications/get_sk_list") ?>';
                    }
                    $('.user_div').show()
                    $('#user_list').prop('required', true);
                    $.ajax({
                        url: url,
                        method: 'post',
                        dataType: 'json',
                        success: function(response) {
                            console.log(response.data);
                            console.log(response.data[0]._id)
                            let users = '<option value ="">Select</option>';
                            response.data.forEach((res) => {
                                users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                            })
                            $('#user_list').html(users);
                        }
                    })
                }
            });
            //action_type_7
            // action_type_8
            $('#action_type_8').on('change', function() {
                $('#user_list').empty();
                let value = $(this).val();
                if (value == 'F') {
                    let location = $('#location').val();
                    $('#user_list').prop('required', true);
                    $('.user_div').show()
                    $.ajax({
                        url: '<?php echo site_url("spservices/office/applications/get_dps") ?>',
                        method: 'post',
                        data: {
                            dist: district
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log(response.data);
                            console.log(response.data[0]._id)
                            let users = '<option value ="">Select</option>';
                            response.data.forEach((res) => {
                                users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                            })
                            $('#user_list').html(users);
                        }
                    })
                } else if (value == 'RO') {
                    let location = $('#location').val();
                    $('#user_list').prop('required', true);
                    $('.user_div').show()
                    $.ajax({
                        url: '<?php echo site_url("spservices/office/applications/get_co_list") ?>',
                        method: 'post',
                        data: {
                            dist: district,
                            location: location
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log(response.data);
                            console.log(response.data[0]._id)
                            let users = '<option value ="">Select</option>';
                            response.data.forEach((res) => {
                                users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                            })
                            $('#user_list').html(users);
                        }
                    })
                }
                // else{
                //     $('.user_div').hide()
                // }
            })
            // action_type_9

            $('#action_type_9').on('change', function() {
                $('#user_list').empty();
                let value = $(this).val();
                let location = $('#location').val();
                if (value == 'D') {
                    $('.official_remark_div').show();
                    $('.query_div').hide();
                    $('.user_div').hide()
                    $('.official_remark').prop('required', true);
                    // $('#query_to_application').removeAttr('required');
                    $('.reject_dropdown').hide();
                    $('.final_btn').html("<i class='fa fa-edit'></i> Proceed to sign");
                }
                // reject_dropdown
                else if (value == 'R') {
                    $('.reject_dropdown').show();
                    $('.official_remark_div').show();
                    $('.query_div').hide();
                    $('.user_div').hide()
                    $('.official_remark').prop('required', true);
                    // $('#query_to_application').removeAttr('required');
                    $('.final_btn').html("<i class='fa fa-save'></i> Submit");
                } else if (value == 'RA') {
                    $('.reject_dropdown').hide();
                    $('.user_div').hide()
                    $('.official_remark_div').hide();
                    $('.official_remark').removeAttr('required');
                    $('#query_to_application').prop('required', true);
                    $('.query_div').show();
                    $('.final_btn').html("<i class='fa fa-save'></i> Submit");
                    $('#user_list').removeAttr('required');


                } else if (value == 'RD') {
                    $('.reject_dropdown').hide();
                    $('#user_list').prop('required', true);
                    $('.official_remark_div').show();
                    $('.official_remark').prop('required', true);
                    $('.query_div').hide();
                    $('.task_div').show();
                    $('.user_div').show();
                    $('#feedback').val('');
                    $('.final_btn').html("<i class='fa fa-save'></i> Submit");

                    $.ajax({
                        url: '<?php echo site_url("spservices/office/applications/get_da") ?>',
                        method: 'post',
                        data: {
                            dist: district
                        },
                        dataType: 'json',
                        success: function(response) {
                            let users = '<option value ="">Select</option>';
                            response.data.forEach((res) => {
                                users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                            })
                            $('#user_list').html(users);
                        }
                    })
                } else if (value == 'RC') {
                    $('.reject_dropdown').hide();
                    $('#user_list').prop('required', true);
                    $('.official_remark_div').show();
                    $('.official_remark').prop('required', true);
                    $('.query_div').hide();
                    $('.task_div').show();
                    $('.user_div').show();
                    $('#feedback').val('');
                    $('.final_btn').html("<i class='fa fa-save'></i> Submit");

                    $.ajax({
                        url: '<?php echo site_url("spservices/office/applications/get_co_list") ?>',
                        method: 'post',
                        data: {
                            dist: district,
                            location: location
                        },
                        dataType: 'json',
                        success: function(response) {
                            let users = '<option value ="">Select</option>';
                            response.data.forEach((res) => {
                                users += '<option value="' + res._id.$id + '">' + res.name + '</option>';
                            })
                            $('#user_list').html(users);
                        }
                    })
                } else {
                    $('.reject_dropdown').hide();
                    $('.official_remark_div').show();
                    $('.query_div').hide();
                    $('.official_remark').prop('required', true);
                    $('#query_to_application').removeAttr('required');
                    $('.final_btn').html("<i class='fa fa-save'></i> Submit");

                }

            })
        })
    </script>