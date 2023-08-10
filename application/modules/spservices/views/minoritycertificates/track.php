<?php
$createdAt = isset($dbrow->service_data->submission_date) ? date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))) : '';
?>
<div class="content-wrapper">
    <div class="container mt-3 mb-3">
        <div class="card shadow">
            <div class="card-header text-center" style="background:#9edad8">Application Processing Details</div>
            <div class="card-body">
                <table class="table  table-sm" id="application_list_table">
                    <tr>
                        <td>
                            <p style="font-size:16px"><b>RTPS Ref. No. : <span class="text-danger"><?php echo $dbrow->service_data->appl_ref_no; ?></span></b></p>
                        </td>
                        <td>
                            <p style="font-size:16px ; text-align:right "><b>Application Receiving Date : <?= $createdAt ?></b></p>
                        </td>
                    </tr>
                </table>
                <table class="table table-sm table-striped table-bordered mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>Sl. No.</th>
                            <th>Submission Location</th>
                            <th>Task Name</th>
                            <th>User</th>
                            <th>Designation</th>
                            <th>Received time</th>
                            <th>Executed time</th>
                            <th>Action</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        if (isset($dbrow->execution_data)) {
                            $exc_data = $dbrow->execution_data;
                            for ($j = 0; $j < count($exc_data); $j++) { ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $dbrow->form_data->pa_circle . ', ' . $dbrow->form_data->pa_district_name ?></td>
                                    <td><?= $exc_data[$j]->task_details->task_name ?></td>
                                    <td>
                                        <?php
                                        if ($exc_data[$j]->task_details->user_type == 'Official') {
                                            echo $exc_data[$j]->task_details->user_detail->user_name;
                                        } else {
                                            echo 'Applicant';
                                        }//End of if else ?>
                                    </td>
                                    <td>
                                        <?php if ($exc_data[$j]->task_details->user_type == 'Official') {
                                            echo $exc_data[$j]->task_details->user_detail->designation;
                                        } else {

                                        }//End of if else ?>
                                    </td>
                                    <td><?= format_mongo_date($exc_data[$j]->task_details->received_time); ?></td>
                                    <td><?= ($exc_data[$j]->task_details->executed_time) ? format_mongo_date($exc_data[$j]->task_details->executed_time) : ''; ?></td>
                                    <td><?=($exc_data[$j]->task_details->action_taken == 'Y') ?((isset($exc_data[$j]->official_form_details->action)) ? $exc_data[$j]->official_form_details->action : '') : '<span class="text-danger"><b>Not taken yet</b></span>'?></td>
                                    <td><?php if (isset($exc_data[$j]->official_form_details->remarks)) {
                                            echo $exc_data[$j]->official_form_details->remarks;
                                        } else if (isset($exc_data[$j]->applicant_task_details->query_answered)) {
                                            echo $exc_data[$j]->applicant_task_details->query_answered;
                                        }//End of if else ?>
                                    </td>
                                </tr>
                            </tbody>
                        <?php }
                    } else {
                        echo '<tr><td colspan="8" class="text-center">No task available</td></tr>';
                    }//End of if else ?>
                </table>
            </div>
        </div>
    </div>
</div>