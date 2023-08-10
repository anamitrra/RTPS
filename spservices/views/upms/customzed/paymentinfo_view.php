<?php
$empCode = $this->config->item("emp_exc_code");
$serviceId = $form_row->service_data->service_id ?? '';
$serviceRow = $this->services_model->get_row(['service_code' => $serviceId]);
if ($serviceRow) {
    $previewLink = $serviceRow->preview_link ?? '';
    $queryPayment = $serviceRow->query_payment ?? 'NO';
    $custom_fields = $serviceRow->custom_fields ?? array();
    $service_mode = $serviceRow->service_mode ?? 'ONLINE';
} else {
    $previewLink = '';
    $queryPayment = 'NO';
    $custom_fields = array();
    $service_mode = 'ONLINE';
} //End of if else

$processDirection = $form_row->process_direction ?? 'FORWARD';
$totalLevels = $this->levels_model->get_total_rows(array("level_services.service_code" => $serviceId));
$obj_id = $form_row->_id->{'$id'};
$processing_history = $form_row->processing_history ?? array();
$loggedinUserLevelNo = $this->session->loggedin_user_level_no ?? 0;
$current_status = $form_row->status ?? '';
$levelRow = $this->levels_model->get_row(array("level_services.service_code" => $serviceId, "level_no" => $loggedinUserLevelNo));
$forward_levels = $levelRow->forward_levels ?? array();
$forwardLevels = array();
if (count($forward_levels)) {
    foreach ($forward_levels as $flvls) {
        $forwardLevels[] = $flvls->level_no;
    } //End of foreach()
} //End of if

$filterForward = array(
    "user_services.service_code" => $serviceId,
    "user_levels.level_no" => array('$in' => $forwardLevels),
    "status" => 1
);
$usersForward = count($filterForward) ? $this->users_model->get_rows($filterForward) : false;
?>

<script src="<?= base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.js") ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(document).on("click", "#add_ipo_details", function() {
            let totRows = $('#add_work_com_tbl tr').length;
            var trow = `<tr>
            <td><input name="ipo_number[]" class="form-control" type="text" /></td>
            <td><input name="ipo_date[]" class="form-control" type="date" /></td>
            <td><input name="ipo_amnt[]" class="form-control" type="number" /></td>
            <td style="text-align:center"><button class="btn btn-danger delete_ipo_row" type="button"><i class="fa fa-trash"></i></button></td>
            </tr>`;
            if (totRows <= 10) {
                $('#add_work_com_tbl tr:last').after(trow);
            } else {
                alertMsg('warning', 'Only 10 records allowed');
            }
        });
        $(document).on("click", ".delete_ipo_row", function() {
            $(this).closest("tr").remove();
            return false;
        });

        var submitForm = function(actionLbl, actionRightCode) {
            if (confirm('Are you sure, you want to ' + actionLbl + ' for the application?')) {
                $("#action_taken").val(actionRightCode);
                $("#processForm").submit();
                return true;
            } else {
                return false;
            } //End of if else
        }; //End of submitForm()

        $(document).on("click", ".action-btn", function() {
            var actionRightCode = $(this).attr("data-rightcode");
            var actionLbl = 'Update Payment Info';
            submitForm(actionLbl, actionRightCode);
        }); //End of onClick .action-btn
    });
</script>
<link rel="stylesheet" href="<?= base_url("assets/plugins/jQuery-MultiSelect/jquery.multiselect.css") ?>" type="text/css">
<div class="content-wrapper mt-2 p-2">
    <?php if ($this->session->flashdata('flashMsg') != null) { ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('flashMsg') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php } //End of if 
    ?>
    <div class="accordion" id="accordionTasks">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTasks">
                <button class="btn btn-primary w-100" type="button" data-toggle="collapse" data-target="#form_preview" aria-expanded="true" aria-controls="form_preview" style="font-size:18px; text-transform: uppercase; font-weight: bold">
                    <span style="float:left">Application preview</span>
                    <span style="float:right"><i class="fa fa-chevron-circle-down"></i></span>
                </button>
            </h2><!--End of .accordion-header -->
            <div id="form_preview" class="accordion-collapse collapse" aria-labelledby="headingTasks" data-parent="#accordionTasks">
                <div class="accordion-body p-1">
                    <?php
                    $rawFilePath = FCPATH . 'application/modules/spservices/views/' . $previewLink;
                    $absFilePath = str_replace('\\', '/', $rawFilePath); //echo "File exists : ".file_exists($absFilePath.'.php')?'YES':'NO';
                    if (strlen($previewLink) && file_exists(FCPATH . 'application/modules/spservices/views/' . $previewLink)) {
                        $data = array(
                            "service_name" => $form_row->service_data->service_name,
                            "dbrow" => $form_row,
                            "user_type" => $form_row->form_data->user_type ?? ''
                        );
                        $this->load->view($previewLink, $data);
                    } else {
                        echo '<h2 style="text-align:center; font-size:18px !important; line-height:28px">Unable to locate the view file</h2>';
                    } //End of if else 
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-2">
        <div class="card-header bg-info">
            <span class="h5 text-white">Application processing history</span>
        </div>
        <div class="card-body">
            <?php if (count($processing_history)) { ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Processed on</th>
                            <th>Processed by</th>
                            <th>Action taken</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($processing_history as $prows) { ?>
                            <tr>
                                <td><?= isset($prows->processing_time) ? date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($prows->processing_time))) : '' ?></td>
                                <td><?= $prows->processed_by ?? '' ?></td>
                                <td><?= $prows->action_taken ?? '' ?></td>
                                <td>
                                    <?php echo $prows->remarks;
                                    if (isset($prows->file_uploaded) and strlen($prows->file_uploaded)) {
                                        echo '<br><a href="' . base_url($prows->file_uploaded) . '" class="btn btn-info" target="_blank"><i class="fa fa-file"></i> View uploaded file</a>';
                                    } ?>
                                </td>
                            </tr>
                        <?php } //End of foreach() 
                        ?>
                    </tbody>
                </table>
            <?php } //End of if 
            ?>
        </div>
    </div><!--End of .card-->

    <form method="POST" action="<?= base_url('spservices/upms/paymentinfo/submit_payment') ?>" id="processForm" enctype="multipart/form-data">
        <input name="obj_id" value="<?= $obj_id ?>" type="hidden" />
        <input name="action_taken" id="action_taken" value='' type="hidden" />
        <div class="card shadow-sm mt-2">
            <div class="card-header bg-warning">
                <span class="h5 text-white">Payment Information</span>
                <span style="float: right; color:#fff">
                    Logged in as <strong><?= $this->session->loggedin_user_fullname ?></strong>
                    (Role <?= $this->session->loggedin_user_role_code ?> of Level-<?= $this->session->loggedin_user_level_no ?>)
                </span>
            </div>
            <div class="card-body">
                <legend class="h6"> <b>IPO Details</b></legend>
                <hr class="mt-0">
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered small" id="add_work_com_tbl">
                            <thead>
                                <tr>
                                    <th>IPO Number</th>
                                    <th>IPO Date</th>
                                    <th>IPO Amount</th>
                                    <th style="width:65px;text-align: center">#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input name="ipo_number[]" class="form-control" type="text" /></td>
                                    <td><input name="ipo_date[]" class="form-control" type="date" /></td>
                                    <td><input name="ipo_amnt[]" class="form-control" type="number" /></td>
                                    <td style="text-align:center">
                                        <button class="btn btn-info" id="add_ipo_details" type="button">
                                            <i class="fa fa-plus-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <label for="ipo">Upload IPO</label>
                            <div class="custom-file">
                                <input name="ipo_file" class="custom-file-input" id="ipo_file" type="file" />
                                <label class="custom-file-label" for="ipo_file">Choose a file...</label>
                                <?= form_error("ipo_file") ?>
                            </div>
                        </div>
                    </div>
                </div>
                <legend class="h6 mt-3"> <b>FD/DD Details</b></legend>
                <hr class="mt-0">
                <div class="row">
                    <div class="col-md-12 table-responsive">
                        <table class="table table-bordered small" id="add_work_com_tbl">
                            <thead>
                                <tr>
                                    <th>Bank Name</th>
                                    <th>Account/Ref. Number</th>
                                    <th>IFSC</th>
                                    <th>Validity</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input name="bank_name" class="form-control" type="text" value="<?php echo set_value('bank_name'); ?>" /><?= form_error("bank_name") ?></td>
                                    <td><input name="acc_number" class="form-control" type="text" value="<?php echo set_value('acc_number'); ?>" /><?= form_error("acc_number") ?></td>
                                    <td><input name="ifsc" class="form-control" type="text" value="<?php echo set_value('ifsc'); ?>" /><?= form_error("ifsc") ?></td>
                                    <td><input name="validity" class="form-control" type="date" value="<?php echo set_value('validity'); ?>" /><?= form_error("validity") ?></td>
                                    <td><input name="amount" class="form-control" type="number" value="<?php echo set_value('amount'); ?>" /><?= form_error("amount") ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <label for="fd">Upload FD/DD</label>
                            <div class="custom-file">
                                <input name="fd_file" class="custom-file-input" id="fd_file" type="file" />
                                <label class="custom-file-label" for="fd_file">Choose a file...</label>
                                <?= form_error("fd_file") ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-0">
                    <div class="col-md-12 form-group">
                        <label>Forward to</label>
                        <select name="forward_to" id="forward_to" class="form-control action-select">
                            <option value="">Select </option>
                            <?php foreach ($usersForward as $fUser) {
                                $fUserData = array(
                                    "login_username" => $fUser->login_username,
                                    "user_role_code" => $fUser->user_roles->role_code,
                                    "user_level_no" => $fUser->user_levels->level_no,
                                    "user_fullname" => $fUser->user_fullname
                                );
                                $roleObj = json_encode($fUserData);
                                $fUserText = $fUser->user_fullname . ' (' . $fUser->user_roles->role_code . ' of level-' . $fUser->user_levels->level_no . ')';
                                echo "<option value='" . $roleObj . "'>" . $fUserText . "</option>";
                            } //End of foreach() 
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer text-center">
                <button id="UPDATE_STATUS_BTN" data-rightcode="SAVE_DATA" class="btn btn-primary action-btn" type="button">
                    <i class="fas fa-angle-double-right"></i> SAVE & FORWARD
                </button>
            </div><!--End of .card-footer-->
        </div><!--End of .card-->
    </form>
</div>
<script type="text/javascript">
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val();
        $(this).next('.custom-file-label').html(fileName);
    });
</script>