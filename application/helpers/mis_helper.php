<?php
use Firebase\JWT\JWT;

include APPPATH . '/libraries/jwt/BeforeValidException.php';
include APPPATH . '/libraries/jwt/SignatureInvalidException.php';
include APPPATH . '/libraries/jwt/ExpiredException.php';
include APPPATH . '/libraries/jwt/JWT.php';

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// Verify JWT
if (!function_exists('verify_jwt')) {
    function verify_jwt()
    {
        $CI = &get_instance();
        $CI->config->load('mis_config');

        $result = array();
        
        $headers =  $CI->input->get_request_header('Authorization');


        if (!empty($headers)) {

            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                $token = $matches[1];

                try {
                    // $key = $CI->config->item('jwt_key');
                    // $decoded = JWT::decode($token, $key, array('HS256'));

                    // valid token, get payload

                    // $result['department_id'] = $decoded->sub;
                    $result['department_id'] = '2193';
                    $result['status'] = TRUE;

                } catch (Exception $e) {
                    $result['status'] = false;
                    $result['error'] = $e->getMessage();

                }
            } else {
                // Token not found
                $result['status'] = false;
                $result['error'] = 'Token not found';
            }
        } else {
            // Token not found
            $result['status'] = FALSE;
            $result['error'] = 'Token not found';
        }

        return $result;

    }
}
