<?php

use Firebase\JWT\JWT;

include APPPATH . '/libraries/jwt/BeforeValidException.php';
include APPPATH . '/libraries/jwt/SignatureInvalidException.php';
include APPPATH . '/libraries/jwt/ExpiredException.php';
include APPPATH . '/libraries/jwt/JWT.php';

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Department_data_api extends frontend
{
    protected $department_id = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->model("department_data_model");
        $this->config->load('mis_config');
    }

    public function generate_jwt()
    {
        $department_id = trim($this->input->get('department', true));

        if (empty($department_id)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array(
                    'status' => false,
                    "msg" => "Departmet ID missing",
                )));
        }

        $this->load->model('department_model');
        $department = $this->department_model->get_department_by_id($department_id);

        if (empty($department)) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(400)
                ->set_output(json_encode(array(
                    'status' => false,
                    "msg" => "Departmet not found",
                )));
        }

        $key = $this->config->item('jwt_key');

        $token['sub'] = $department_id;
        $token['iss'] = base_url();
        $token['aud'] = $department->department_name;
        $token['iat'] = time();
        // $token['nbf'] = time();
        $token['exp'] = time() + (5 * 60 * 60); // valid for 45 MIN

        $result['token'] = JWT::encode($token, $key);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($result));
    }

    private function verify_jwt()
    {
        $headers = $this->input->get_request_header('Authorization');

        if (!empty($headers)) {

            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                $token = $matches[1];

                try {
                    $key = $this->config->item('jwt_key');
                    $decoded = JWT::decode($token, $key, array('HS256'));

                    // valid token, get department id
                    $this->department_id = $decoded->sub;

                    return false;
                } catch (Exception $e) {
                    $result['error'] = $e->getMessage();
                }
            } else {
                // Token not found
                $result['error'] = 'Token not found';
            }
        } else {
            // Token not found
            $result['error'] = 'Token not found';
        }

        return $result;
    }

    // count total applications
    public function index()
    {
        // verify jwt
        $result = $this->verify_jwt();
        if ($this->verify_jwt()) {

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode($result));
            die;
        }

        $total = $this->department_data_model->tot_search_rows(array(
            'initiated_data.department_id' => $this->department_id,
        ));
        $deliver = $this->department_data_model->tot_search_rows(array(
            'initiated_data.department_id' => $this->department_id,
            'execution_data.0.official_form_details.action' => 'Deliver',
        ));
        $reject = $this->department_data_model->tot_search_rows(array(
            'initiated_data.department_id' => $this->department_id,
            'execution_data.0.official_form_details.action' => 'Reject',
        ));
        $pendingIT = $this->department_data_model->calculate_pending_in_time($this->department_id);
        $timelyDelivered = $this->department_data_model->calculate_delivered_in_time($this->department_id);

        $response = array(
            'status' => true,
            'data' => array(
                'received' => $total,
                'pending' => ($total - ($reject + $deliver)),
                'pending_in_time' => $pendingIT,
                'delivered' => $deliver,
                'rejected' => $reject,
                'delivered_in_time' => $timelyDelivered,
            ),
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_header('Access-Control-Allow-Origin: *')
            ->set_header('Access-Control-Allow-Methods: GET')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    // gender data for all applications
    public function gender_wise_application_count()
    {
        // verify jwt
        $result = $this->verify_jwt();
        if ($this->verify_jwt()) {

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode($result));
            die;
        }

        $total_received_gender_wise = $this->department_data_model->total_applications_gender_wise($this->department_id);
        $gender_array = array();
        $gender_array['Male'] = 0;
        $gender_array['Female'] = 0;
        $gender_array['Others'] = 0;
        $gender_array['NA'] = 0;

        // pre($total_received_gender_wise);

        foreach ($total_received_gender_wise as $key => $val) {
            if ($val->gender == 'Male' || $val->gender == 'Male / পুৰুষ') {
                $gender_array['Male'] += $val->total_received;
            } else if ($val->gender == 'Female' || $val->gender == 'Female / মহিলা') {
                $gender_array['Female'] += $val->total_received;
            } else if ($val->gender == 'Others' || $val->gender == 'Others / অন্যান্য') {
                $gender_array['Others'] += $val->total_received;
            } else {
                $gender_array['NA'] += $val->total_received;
            }
        }

        $response['status'] = true;
        $response['data'] = $gender_array;
        return $this->output
            ->set_content_type('application/json')
            ->set_header('Access-Control-Allow-Origin: *')
            ->set_header('Access-Control-Allow-Methods: GET')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    // service status for all services
    public function servicewise()
    {
        // verify jwt
        $result = $this->verify_jwt();
        if ($this->verify_jwt()) {

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode($result));
            die;
        }

        $this->load->model("api_model");
        $this->load->model("services_model");

        $received = $this->department_data_model->total_services_group_by_service($this->department_id);
        $delivered = $this->department_data_model->services_delivered_group_by_service($this->department_id);
        $rejected = $this->department_data_model->services_rejected_group_by_service($this->department_id);
        $pending = $this->department_data_model->services_pending_group_by_service($this->department_id);
        $delivered_in_time = $this->department_data_model->delivered_in_time_group_by_service($this->department_id);
        $rejected_in_time = $this->department_data_model->rejected_in_time_group_by_service($this->department_id);
        $pending_in_time = $this->department_data_model->pending_in_time_group_by_service($this->department_id);
        $all_services = (array) $this->services_model->get_services_by_dept($this->department_id);

        $response = array_map(function ($service) {
            return array(
                'service_id' => $service->service_id,
                'service_name' => $service->service_name,
                'gender_data' => array(
                    'Male' => 0,
                    'Female' => 0,
                    'Others' => 0,
                    'NA' => 0,
                ),
                'received' => 0,
                'delivered' => 0,
                'rejected' => 0,
                'pending' => 0,
                'delivered_in_time' => 0,
                'rejected_in_time' => 0,
                'pending_in_time' => 0,
            );
        }, $all_services);

        /* Adding Service status data */

        foreach ($received as $key => $value) {
            foreach ($response as $k => $v) {

                if ($value->_id == $v['service_id']) {
                    $response[$k]['received'] = $value->count;
                    break;
                }
            }
        }
        foreach ($delivered as $key => $value) {
            foreach ($response as $k => $v) {

                if ($value->_id == $v['service_id']) {
                    $response[$k]['delivered'] = $value->count;
                    break;
                }
            }
        }
        foreach ($rejected as $key => $value) {
            foreach ($response as $k => $v) {

                if ($value->_id == $v['service_id']) {
                    $response[$k]['rejected'] = $value->count;
                    break;
                }
            }
        }
        foreach ($pending as $key => $value) {
            foreach ($response as $k => $v) {

                if ($value->_id == $v['service_id']) {
                    $response[$k]['pending'] = $value->count;
                    break;
                }
            }
        }
        foreach ($delivered_in_time as $key => $value) {
            foreach ($response as $k => $v) {

                if ($value->_id == $v['service_id']) {
                    $response[$k]['delivered_in_time'] = $value->count;
                    break;
                }
            }
        }
        foreach ($rejected_in_time as $key => $value) {
            foreach ($response as $k => $v) {

                if ($value->_id == $v['service_id']) {
                    $response[$k]['rejected_in_time'] = $value->count;
                    break;
                }
            }
        }
        foreach ($pending_in_time as $key => $value) {
            foreach ($response as $k => $v) {

                if ($value->_id == $v['service_id']) {
                    $response[$k]['pending_in_time'] = $value->count;
                    break;
                }
            }
        }

        /* Adding Gender Data */

        foreach ($response as $key => $value) {
            $gender_data = $this->api_model->total_services_group_by_service_gender_wise($value['service_id']);
            $gender_array = array();
            $gender_array['Male'] = 0;
            $gender_array['Female'] = 0;
            $gender_array['Others'] = 0;
            $gender_array['NA'] = 0;

            foreach ($gender_data as $k => $val) {
                if ($val->_id == 'Male' || $val->_id == 'Male / পুৰুষ') {
                    $gender_array['Male'] += $val->total_received;
                } else if ($val->_id == 'Female' || $val->_id == 'Female / মহিলা') {
                    $gender_array['Female'] += $val->total_received;
                } else if ($val->_id == 'Others / অন্যান্য' || $val->_id == 'Others') {
                    $gender_array['Others'] += $val->total_received;
                } else {
                    $gender_array['NA'] += $val->total_received;
                }
            }

            $response[$key]['gender_data'] = $gender_array;
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_header('Access-Control-Allow-Origin: *')
            ->set_header('Access-Control-Allow-Methods: GET')
            ->set_status_header(200)
            ->set_output(json_encode($response));
    }

    // officewise service status
    public function officewise()
    {
        // verify jwt
        $result = $this->verify_jwt();
        if ($this->verify_jwt()) {

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(401)
                ->set_output(json_encode($result));
            die;
        }

        $received = $this->department_data_model->total_services_group_by_office($this->department_id);
        $delivered = $this->department_data_model->services_delivered_group_by_office($this->department_id);
        $rejected = $this->department_data_model->services_rejected_group_by_office($this->department_id);
        $delivered_in_time = $this->department_data_model->delivered_in_time_group_by_office($this->department_id);
        $rejected_in_time = $this->department_data_model->rejected_in_time_group_by_office($this->department_id);
        $pending_in_time = $this->department_data_model->pending_in_time_group_by_office($this->department_id);

        /* Adding Service status data */

        foreach ($received as $key => $r_val) {

            foreach ($delivered as $key => $d_val) {
                if ($d_val->_id == $r_val->_id) {
                    $r_val->delivered = isset($d_val->count) ? $d_val->count : 0;
                }
            }
            foreach ($rejected as $key => $re_val) {
                if ($re_val->_id == $r_val->_id) {
                    $r_val->rejected = isset($re_val->count) ? $re_val->count : 0;
                }
            }
            foreach ($delivered_in_time as $key => $t_val) {
                if ($t_val->_id == $r_val->_id) {
                    $r_val->delivered_in_time = isset($t_val->count) ? $t_val->count : 0;
                }
            }
            foreach ($rejected_in_time as $key => $rej_val) {
                if ($rej_val->_id == $r_val->_id) {
                    $r_val->rejected_in_time = isset($rej_val->count) ? $rej_val->count : 0;
                }
            }
            foreach ($pending_in_time as $key => $pit_val) {
                if ($pit_val->_id == $r_val->_id) {
                    $r_val->pending_in_time = isset($pit_val->count) ? $pit_val->count : 0;
                }
            }

            if (!isset($r_val->rejected)) {
                $r_val->rejected = 0;
            }
            if (!isset($r_val->delivered)) {
                $r_val->delivered = 0;
            }
            if (!isset($r_val->delivered_in_time)) {
                $r_val->delivered_in_time = 0;
            }
            if (!isset($r_val->rejected_in_time)) {
                $r_val->rejected_in_time = 0;
            }
            if (!isset($r_val->pending_in_time)) {
                $r_val->pending_in_time = 0;
            }

            $r_val->pending = $r_val->received - ($r_val->delivered + $r_val->rejected);
        }

        // pre($received);

        return $this->output
            ->set_content_type('application/json')
            ->set_header('Access-Control-Allow-Origin: *')
            ->set_header('Access-Control-Allow-Methods: GET')
            ->set_status_header(200)
            ->set_output(json_encode($received));
    }
}
