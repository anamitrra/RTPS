<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Thirdpartyverification extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('language');
        $this->load->model('settings_model');
        $this->load->model('site_model');

        $this->config->load('site_config');
    } //End of __construct()

    public function index()
    {
        $data['settings'] = $this->settings_model->get_settings('thirdpartyverification');
        $this->render_view_new('thired_party_verification', $data, array());
    } //End of index()


    private function get_db_record($inputs)
    {
        // Switch to appropiate DB
        $this->mongo_db->switch_db($inputs['db'] ?? 'mis');

        if (array_key_exists('reference_number', $inputs)) {
            // First check on MIS
            // If not found then on  'iservices'

            $row = $this->mongo_db->where(array('initiated_data.appl_ref_no' => trim($inputs['reference_number'])))->get('applications')->{'0'} ?? null;     // MIS.applications

            if (empty($row)) {
                $this->mongo_db->switch_db('iservices');

                // Collection: intermediate_ids
                $row = $this->mongo_db->where(array(
                    'status' => 'S',
                    '$or' => [['app_ref_no' => trim($inputs['reference_number'])], ['vahan_app_no' => trim($inputs['reference_number'])]]

                ))->get('intermediate_ids')->{'0'} ?? null;

                if (empty($row)) {
                    // Collection: sp_applications
                    $row = $this->mongo_db->where(array(
                        '$and' => [['service_data.appl_status' => ['$nin' => ['DRAFT', '']]], ['status' => ['$nin' => ['DRAFT', '']]]],
                        '$or' => [['service_data.appl_ref_no' => trim($inputs['reference_number'])], ['app_ref_no' => trim($inputs['reference_number'])]]

                    ))->get('sp_applications')->{'0'} ?? null;
                }
            }
        } elseif (array_key_exists('consumer_no_water', $inputs)) {
            // Water conection & Bill payment query - GMDWSB, AUWSSB

            $row = $this->mongo_db->where(array(
                'status' => 'S',
                'portal_no' => ['$in' => [7, '7', 9, '9']],
                '$or' => [['application_details.0.Consumer_No' => trim($inputs['consumer_no_water'])]]

            ))->get('intermediate_ids')->{'0'} ?? null;
        }

        return $row;
    }

    private function get_external_record($inputs)
    {
        # code...
    }


    public function get_details()
    {
        // pre($this->config->item('apdcl_track_url'));

        $inputs = $this->input->post(null, true);

        // Check DB first
        switch ($inputs['db'] ?? 'mis') {
            case 'mis':
            case 'iservices':
            default:
                $record = $this->get_db_record($inputs);

                // Switch back to Portal DB
                $this->mongo_db->switch_db('portal');

                break;

            case 'ext':
                // Get data from external API
                $record = $this->get_external_record($inputs);
        }



        // pre($record);


        if (!empty($record)) {
            $appl_ref_no = $record->initiated_data->appl_ref_no ?? '';
            $date_of_submission = $this->mongo_db->getDateTime($record->initiated_data->submission_date);
            $applicant_name = $record->initiated_data->attribute_details->applicant_name ?? '';
            $service_name = $record->initiated_data->service_name ?? '';
            $mobile_number = $record->initiated_data->attribute_details->mobile_number ?? '';
            $email = $record->initiated_data->attribute_details->{'e-mail'} ?? '';
            $gender = $record->initiated_data->attribute_details->applicant_gender ?? '';
            $current_status = $this->getstatusdetails($record->initiated_data->appl_status ?? '');
            $stipulated_delivery_date = date('d-m-Y', strtotime($date_of_submission . ' + ' . $record->initiated_data->service_timeline . ' days'));
            $recordFound = true;
        } else {
            $recordFound = false;
        } //End of if else

        if ($recordFound) { ?>

            <table class="table table-bordered border-warning">
                <thead>
                    <tr>
                        <th colspan="4" class="text-bold bg-warning">Application Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2" style="width:50%;">
                            <span style="float:left;">Reference no. : <strong><?= $appl_ref_no ?></strong></span>
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
                        <td>Mobile Number</td>
                        <td style="font-weight: bold;"><?= $mobile_number ?></td>
                        <td>Email</td>
                        <td style="font-weight: bold"><?= $email ?></td>

                    </tr>

                    <tr>

                        <td>Gender</td>
                        <td style="font-weight: bold"><?= $gender ?></td>
                        <td>Stipulated Delivery Date</td>
                        <td style="font-weight: bold;"><?= $stipulated_delivery_date ?></td>
                    </tr>

                    <tr>

                        <td colspan="2">Current Status</td>
                        <td colspan="2" style="font-weight: bold"><?= $current_status ?></td>
                    </tr>
                </tbody>
            </table>

            <?php if ($record->execution_data ?? null) { ?>
                <table class="table table-bordered border-info">
                    <thead>
                        <tr>
                            <th colspan="3" class="text-bold bg-info">Application Processing History</th>
                        </tr>
                        <tr>
                            <th>Date &amp; Time</th>
                            <th>Task details</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($record->execution_data as $edata) { ?>
                            <tr>
                                <td><?= (isset($edata->task_details->executed_time)) ? $this->mongo_db->getDateTime($edata->task_details->executed_time) : ((isset($edata->task_details->received_time)) ? $this->mongo_db->getDateTime($edata->task_details->received_time) : '')  ?></td>
                                <td><?= $edata->task_details->task_name ?? '' ?></td>
                                <td><?= $edata->official_form_details->remarks ?? $edata->official_form_details->remark ?? '' ?></td>
                            </tr>
                        <?php } //End of foreach() 
                        ?>

                    </tbody>
                </table>
<?php } //End of if
        } else {
            echo '';
        } //End of if else
    } //End of get_details()

    private function getstatusdetails($par = "D")
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
