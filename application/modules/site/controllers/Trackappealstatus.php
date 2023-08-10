<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Trackappealstatus extends Site_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->config('language');
        $this->load->model('settings_model');
        $this->load->model('site_model');
        $this->load->model('trackstatus_model');
    } //End of __construct()

    public function index()
    {
        // pre("Hello");
        $data['data'] = $this->settings_model->get_settings('citizen_track');
        $this->render_view_new('trackappealstatus_view', $data, array());
    } //End of index()

    public function get_details()
    {
        // pre("Hello");
        // return;
        $ref_no = $this->input->post("ref_no");
        $this->mongo_db->switch_db("appeal");
        $filterArray = array("appeal_id" => $ref_no);
        $appealRow = $this->trackstatus_model->get_row("appeal_applications", $filterArray);
        if ($appealRow) {
            $appeal_id = $appealRow->appeal_id;
            $applicant_name = $appealRow->applicant_name;
            $date_of_submission = format_mongo_date($appealRow->date_of_application);
            $service_name = $appealRow->name_of_service;
            $stipulated_delivery_date = date('d-m-Y', strtotime($date_of_submission . ' + 15 days'));
            $current_status = $appealRow->process_status;

            $processFilter = array("appeal_id" => $appeal_id);
            $processRows = $this->trackstatus_model->get_rows("appeal_processes", $processFilter);
?>

            <table class="table table-bordered mt-5 border-warning">
                <thead>
                    <tr>
                        <th colspan="4" class="text-bold bg-warning">Appeal tracking details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2" style="width:50%;">
                            <span style="float:left;">Reference no. : <strong><?= $ref_no ?></strong></span>
                        </td>
                        <td colspan="2">
                            <span style="float:right;">Submitted on : <strong><?= $date_of_submission ?></strong></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Applicant Name</td>
                        <td style="font-weight: bold;"><?= $applicant_name ?></td>
                        <td>Service Name</td>
                        <td style="font-weight: bold"><?= $service_name ?></td>
                    </tr>
                    <tr>
                        <td>Stipulated Delivery Date</td>
                        <td style="font-weight: bold;"><?= $stipulated_delivery_date ?></td>
                        <td>Current Status</td>
                        <td style="font-weight: bold"><?= $current_status ?></td>
                    </tr>
                </tbody>
            </table>

            <?php if ($processRows) { ?>
                <table class="table table-bordered mt-2 border-info">
                    <thead>
                        <tr>
                            <th colspan="4" class="text-bold bg-info">Appeal processing history</th>
                        </tr>
                        <tr>
                            <th>Date &amp; Time</th>
                            <th>Process name</th>
                            <th>Process by</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($processRows as $pdata) {
                            $action_taken_by = $pdata->action_taken_by;
                            $userRow = $this->trackstatus_model->get_row_by_id("users", $action_taken_by);
                        ?>
                            <tr>
                                <td><?= format_mongo_date($pdata->created_at) ?></td>
                                <td><?= ucwords(str_replace("-", " ", $pdata->action)) ?></td>
                                <td><?= $userRow ? $userRow->name : "NA" ?></td>
                                <td><?= $pdata->message ?></td>
                            </tr>
                        <?php } //End of foreach() 
                        ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">
                                <a href="<?= base_url('grm') ?>" class="btn btn-primary" target="_blank">
                                    <i class="fa fa-inbox"></i> Lodge a Grievance
                                </a>
                                <?php if (strtotime($stipulated_delivery_date) <= time()) { ?>
                                    <a href="<?= base_url('appeal') ?>" class="btn btn-success" target="_blank">
                                        <i class="fa fa-reply-all"></i> Make an Appeal
                                    </a>
                                <?php } //End of if 
                                ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
<?php } //End of if
        } else {
            echo "<h2 style='text-align:center'>No records found!</h2>";
        } //End of if else
    } //End of get_details()
}//End of Trackappealstatus