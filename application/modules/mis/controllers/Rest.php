<?php

use Firebase\JWT\JWT;
use MongoDB\BSON\UTCDateTime;

include APPPATH . '/libraries/jwt/BeforeValidException.php';
include APPPATH . '/libraries/jwt/SignatureInvalidException.php';
include APPPATH . '/libraries/jwt/ExpiredException.php';
include APPPATH . '/libraries/jwt/JWT.php';
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Rest extends frontend
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->config->load('mis_config');
    }

    public function insert()
    {
        $this->load->model('outapi_model');
        $api_key_variable = $this->config->item('rest_key_name');
        $arr = getallheaders();
        $res = $this->outapi_model->get_where('api_key', $arr[$api_key_variable]);
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        if (isset($res->{0})) {
            $result['status'] = true;
            $this->load->model("storeoutapi_model");
            $this->storeoutapi_model->insert(array(
                'created_at' => new UTCDateTime(strtotime(date('d-m-Y h:i A')) * 1000),
                'type' => $res->{0}->type,
                'data' => json_encode($stream_clean),
            ));
        } else {
            $result['status'] = false;
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($result));
    }

    public function verify_jwt()
    {
        $headers = $this->input->get_request_header('Authorization');

        if (!empty($headers)) {

            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                $token = $matches[1];

                try {
                    $key = $this->config->item('jwt_key');
                    $decoded = JWT::decode($token, $key, array('HS256'));

                    $respon_status = 200;
                    $result = $decoded;

                } catch (Exception $e) {
                    $respon_status = 403;
                    $result['error'] = $e->getMessage();

                }
            } else {
                // Token not found
                $respon_status = 401;
                $result = array('error' => 'Token not found');
            }
        } else {
            // Token not found
            $respon_status = 401;
            $result = array('error' => 'Token not found');
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header($respon_status)
            ->set_output(json_encode($result));
    }

    public function generate_jwt($department_id = '')
    {
        $key = $this->config->item('jwt_key');

        $token['sub'] = $department_id;
        $token['iss'] = base_url();
        $token['aud'] = 'Revenue & Disaster Management Department';
        $token['iat'] = time();

        // $token['nbf'] = time();
        // $token['exp'] = time() + 60 ;

        $status['token'] = JWT::encode($token, $key);
        pre($status);
    }
}
