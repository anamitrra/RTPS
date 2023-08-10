<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Mis extends admin
{
    public function __construct()
    {
        parent::__construct();
        $this->user_type();
    } //End of __construct()

    public function index()
    {

        $token = $this->generateJwtToken();
        $response = array('token' => $token);
        echo json_encode($response);

//         url
// dashboard/external_service/login
        // try {
        //     $decoded_token = $this->validateJwtToken($token);
        //     pre($decoded_token);
        // } catch (Exception $e) {
        //     pre($e);
        // }
    }

    function generateJwtToken()
    {
        $secret_key = 'secret';

        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode([
            "user_id" => $this->session->userdata('administrator')['admin_id'],
            "username" => $this->session->userdata('administrator')['username'],
            "dept_id" => $this->session->userdata('administrator')['dept_id'],
            "dept_name" => $this->session->userdata('administrator')['dept_name'],
            "login_success" => true
        ]);

        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $base64UrlHeader . '.' . $base64UrlPayload, $secret_key, true);
        $base64UrlSignature = $this->base64UrlEncode($signature);

        $token = $base64UrlHeader . '.' . $base64UrlPayload . '.' . $base64UrlSignature;
        return $token;
    }

    function validateJwtToken($token)
    {
        $secret_key = 'secret';

        $tokenParts = explode('.', $token);
        $header = $this->base64UrlDecode($tokenParts[0]);
        $payload = $this->base64UrlDecode($tokenParts[1]);
        $signature = $this->base64UrlDecode($tokenParts[2]);

        $validSignature = hash_hmac('sha256', $tokenParts[0] . '.' . $tokenParts[1], $secret_key, true);

        if (hash_equals($signature, $validSignature)) {
            $decoded = json_decode($payload, true);
            return $decoded;
        } else {
            throw new Exception("Invalid token.");
        }
    }

    function base64UrlDecode($base64Url)
    {
        $base64 = str_replace(['-', '_'], ['+', '/'], $base64Url);
        $decoded = base64_decode($base64);
        return $decoded;
    }

    function base64UrlEncode($data)
    {
        $base64 = base64_encode($data);
        $base64Url = str_replace(['+', '/', '='], ['-', '_', ''], $base64);
        return $base64Url;
    }
}
