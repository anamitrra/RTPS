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

        $this->config->load('site_config', true);
    } //End of __construct()

    public function index()
    {
        // New requirments
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
        } 
        elseif (array_key_exists('consumer_no_water', $inputs)) {
            // Water conection & Bill payment query - GMDWSB, AUWSSB

            $row = $this->mongo_db->where(array(
                'status' => 'S',
                'portal_no' => ['$in' => [7, '7', 9, '9']],
                '$or' => [
                    ['application_details.0.Consumer_No' => trim($inputs['consumer_no_water'])],
                    // GMDWSB not ready yet
                ]

            ))->get('intermediate_ids')->{'0'} ?? null;


        }

    
        return $this->get_html_data($row);
        
    }

    private function get_external_record($inputs)
    {
        if (isset($inputs['service']) && $inputs['service'] === 'apdcl1') {     // APDCL Track
            // Check if APDCL consumer no. exists in RTPS

            $this->mongo_db->switch_db('iservices');

            $row = $this->mongo_db->where(array(
                'service_data.service_id' => 'apdcl1',
                'service_data.appl_status' => ['$nin' => ['DRAFT', '']],
                'processing_history.consNo' => trim($inputs['consumer_no_apdcl']),
            ))->get('sp_applications')->{'0'} ?? null;

            // pre($row);

            if (empty($row)) {
                return '<h3 class="my-4 text-capitalize text-center text-danger">No Records Found!</h3>';
            }

            // Query track API
            $url = $this->config->item('apdcl_track_url', 'site_config');
            $qs = http_build_query(['consumer' => trim($inputs['consumer_no_apdcl'])]);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, "{$url}?{$qs}");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic Z21jOmFwZGNsQDA5OCM='));
            curl_setopt($curl, CURLOPT_FAILONERROR, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);    // SSL verification OFF
            curl_setopt($curl, CURLOPT_SSL_VERIFYSTATUS, false);
            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                // return (object) ['api_error' => curl_error($curl)];
                // CURL error
                return '<h3 class="my-4 text-capitalize text-center text-danger">Could not Fetch External Record!</h3>';
            }
            curl_close($curl);

            // Build html response
            $data = json_decode($response, true);
            $status = trim($data['msg'] ?? 'failure');

            if ($status == 'failure') {
                return '<h3 class="my-4 text-capitalize text-center text-danger">'.
                $data['error1'] . ' ' . $data['error2'] . ' for ' . $data['consNo'] ?? '' .
                '</h3>';
            }
            elseif ($status == 'success') {
                $html = <<<EOD
                <table class="table table-bordered border-warning">
                <thead>
                    <tr>
                        <th colspan="2" class="text-bold bg-warning">Application Tracking Details</th>
                    </tr>
                </thead>
                <tbody>
                EOD;

                foreach ($data as $key => $value) {
                    if (in_array($key, ['msg', 'error1', 'error2'])) {
                       continue;
                    }

                    $key = ucwords($key);
                    $html .= <<<EOD
                    <tr>
                        <th class="text-bold">$key</th>
                        <td>$value</td>
                    </tr>
                    EOD;
                }

                $html .= '</tbody></table>';

                return $html;

            }
            
        }
    }

    public function get_details()
    {
        $inputs = $this->input->post(null, true);

        // Check DB first
        switch ($inputs['db'] ?? 'mis') {
            case 'mis':
            case 'iservices':
            default:
                $record = $this->get_db_record($inputs);
                break;

            case 'ext':
                // Get data from external API
                $record = $this->get_external_record($inputs);
        }

        // Switch back to Portal DB
        $this->mongo_db->switch_db('portal');

        $this->output
        ->set_status_header(200)
        ->set_content_type('text/html')
        ->set_output($record);



        // pre($record);
    } //End of get_details()

    private function get_html_data($row)
    {
        if (empty($row)) {
            return '<h3 class="my-4 text-capitalize text-center text-danger">No Records Found!</h3>';
        }

        // pre($row);

        $appl_ref_no = $row->app_ref_no ?? '';
        $date_of_submission = $row->submission_date ?? '';
        $applicant_name = $row->applicant_details[0]->applicant_name ?? '';
        $service_name = $row->service_name ?? '';
        $mobile_number = $row->applicant_details[0]->mobile_number ?? 'N/A';
        $email = $row->applicant_details[0]->{'e-mail'} ?? 'N/A';
        $gender = trim($row->applicant_details[0]->gender ?? '');
        if (empty($gender)) {
            $gender = 'N/A';
        }


        $current_status = $this->getstatusdetails($row->delivery_status ?? 'F');
        // $stipulated_delivery_date = date('d-m-Y', strtotime($date_of_submission . ' + ' . $row->initiated_data->service_timeline . ' days'));

        $html = <<<EOD
        <table class="table table-bordered border-warning">
                <thead>
                    <tr>
                        <th colspan="4" class="text-bold bg-warning">Applicant Details</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2" style="width:50%;">
                            <span>Reference Number :: <strong>$appl_ref_no</strong></span>
                        </td>
                        <td colspan="2">
                            <span>Submitted on :: <strong>$date_of_submission</strong></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Applicant Name</td>
                        <td><strong>$applicant_name</strong></td>
                        <td>Service Name</td>
                        <td><strong>$service_name</strong></td>
                    </tr>

                    <tr>
                        <td>Mobile Number</td>
                        <td><strong>$mobile_number</strong></td>
                        <td>Email</td>
                        <td><strong>$email</strong></td>

                    </tr>
                    <tr>
                        <td>Gender</td>
                        <td><strong>$gender</strong></td>
                        <td>Current Status</td>
                        <td><strong>$current_status</strong></td>
                    </tr>
                </tbody>
            </table>
        EOD;

        $html .= '<div class="p-2 my-2 text-capitalize fw-bold bg-secondary text-white">Application Details</div>';



        return $html;
    }

    private function getstatusdetails($par = 'D')
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
        }
        return $action;
    }
}
