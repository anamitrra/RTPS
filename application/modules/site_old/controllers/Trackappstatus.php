<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
define('APPLICATIONS_TABLE', 'sp_custom.application_processing_json');

class Trackappstatus extends Site_Controller
{

    private $pgdb;

    public function __construct()
    {
        parent::__construct();
        $this->load->config('language');
        $this->load->model('settings_model');
        $this->load->model('site_model');

        // $host = "host = 10.194.162.120";
        // $port = "port = 5432";
        // $dbname = "dbname = rtps_prod";
        // $credentials = "user = serviceplusrole password=Artps@p05tgres";

        $host = "host = localhost";
        $port = "port = 5432";
        $dbname = "dbname = rtps_prod";
        $credentials = "user = postgres password=admin";


        // $this->pgdb = pg_connect("$host $port $dbname $credentials");
        // if (!$this->pgdb) die("Error in connection");
    } //End of __construct()

    public function index()
    {
        $data['data'] = $this->settings_model->get_settings('citizen_track');
        $this->render_view_new('trackappstatus_view', $data, array());
    } //End of index()


    // Format exceution data properly 
    private function merge_arrys($arr)
    {
        if (count($arr) > 1) {
            // base case
            return $arr;
        }

        return array_merge([], $this->merge_arrys(reset($arr)));
    }

    public function get_details()
    {
        $ref_no = trim($this->input->post("ref_no", true));  //EPASS/2020/43008
        // $sql = "SELECT submission_date, initiated_data, execution_data, appl_status FROM " . APPLICATIONS_TABLE . " WHERE initiated_data IS NOT NULL AND appl_ref_no = '" . $ref_no . "'";
        // $query = pg_query($this->pgdb, $sql);
        // $row = pg_fetch_object($query);

        $this->mongo_db->switch_db('mis');
        $row = $this->mongo_db->where(array('initiated_data.appl_ref_no' => $ref_no))->get('applications')->{'0'} ?? null;

        // pre($row);

        if ($row) {
            $date_of_submission = $this->mongo_db->getDateTime($row->initiated_data->submission_date);
            // $initiated_data = json_decode($row->initiated_data);
            // $execution_data = json_decode($row->execution_data);
            /* $execution_data = [];

            $exec_data = json_decode($row->execution_data, true) ?? [];

            foreach ($exec_data as $exec) {

                // Check for null values
                if (is_null($exec)) {
                    continue;
                }

                $exec_data_actual = $this->merge_arrys($exec);

                array_push($execution_data, array(
                    'task_details' => $exec_data_actual['task_info'],
                    'official_form_details' => $exec_data_actual['official_form_attributes'],
                    'applicant_task_details' => $exec_data_actual['applicant_task_data'],
                ));
            } */

            // pre($execution_data);

            $applicant_name = $row->initiated_data->attribute_details->applicant_name;
            $service_name = $row->initiated_data->service_name;
            $current_status = $this->getstatusdetails($row->initiated_data->appl_status);
            $stipulated_delivery_date = date('d-m-Y', strtotime($date_of_submission . ' + ' . $row->initiated_data->service_timeline . ' days'));
            $recordFound = true;
        } else {
            $recordFound = false;
            // $this->mongo_db->switch_db("iservices");
            // $this->load->model('trackstatus_model');
            // $filterArray = array("vahan_app_no" => $ref_no);
            // $iserviceRow = $this->trackstatus_model->get_row("intermediate_ids", $filterArray);
            // if ($iserviceRow) {
            //     $applicant_name = $iserviceRow->applicant_name;
            //     $date_of_submission = date("d-m-Y", strtotime($iserviceRow->submission_date));
            //     $service_name = $iserviceRow->service_name;
            //     $stipulated_delivery_date = date('d-m-Y', strtotime($date_of_submission . ' + 15 days'));
            //     $current_status = ($iserviceRow->status == 'S') ? "DELIVERED" : "PENDING";
            //     $recordFound = true;
            // } else {
            //     $recordFound = false;
            // } //End of if else
        } //End of if else

        if ($recordFound) { ?>
            <table class="table table-bordered mt-5 border-warning">
                <thead>
                    <tr>
                        <th colspan="4" class="text-bold bg-warning">Application tracking details</th>
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

            <?php if ($row->execution_data ?? null) { ?>
                <table class="table table-bordered mt-2 border-info">
                    <thead>
                        <tr>
                            <th colspan="3" class="text-bold bg-info">Application processing history</th>
                        </tr>
                        <tr>
                            <th>Date &amp; Time</th>
                            <th>Task details</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($row->execution_data as $edata) { ?>
                            <tr>
                                <td><?= (isset($edata->task_details->executed_time)) ? $this->mongo_db->getDateTime($edata->task_details->executed_time) : ((isset($edata->task_details->received_time)) ? $this->mongo_db->getDateTime($edata->task_details->received_time) : '')  ?></td>
                                <td><?= $edata->task_details->task_name ?? '' ?></td>
                                <td><?= $edata->official_form_details->remarks ?? $edata->official_form_details->remark ?? '' ?></td>
                            </tr>
                        <?php } //End of foreach() 
                        ?>
                        <tr>
                            <td colspan="3" style="text-align: center;">
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

    function getstatusdetails($par = "D")
    {
        switch ($par) {
            case 'R':
                $action = '<span class="text-danger">REJECTED</span>';
                break;
            case 'D':
                $action = '<span class="text-success">DELIVERED</span>';
                break;
            default:
                $action = '<span class="text-info">Under Processed</span>';
                break;
        } //End of getstatusdetails()
        return $action;
    } // End of getstatusdetails()
}//End of Trackappstatus
