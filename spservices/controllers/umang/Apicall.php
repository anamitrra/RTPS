<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Apicall extends frontend {

    public function __construct() {
        parent::__construct();
    }//End of __construct()

    public function index() { //For testing UMANG API
        $postUrl = "https://localhost/rtps/spservices/umang/apipost/getstates";
        $json_obj = json_encode(array("name"=>"Ashraful", "email"=>"nicashraful@gmail.com"));
        $authorization = "Authorization: 43b-b6daab40b04e";

        $curl = curl_init($postUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }//End of if
        curl_close($curl);
        if(isset($error_msg)) {
            die("Error in server communication ".$error_msg);
        } elseif ($response) {
            echo $postUrl." : "; pre($response);
        }
    }//End of index()

    public function submit() { //For testing UMANG API
        $postUrl = "https://localhost/rtps/spservices/umang/apipost/post_data";
        $json_obj = json_encode(array("name"=>"Ashraful", "email"=>"nicashraful@gmail.com"));
        $authorization = "Authorization: Bearer 080042cad6356ad5dc0a720c18b53b8e53d4c274";

        $curl = curl_init($postUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }//End of if
        curl_close($curl);
        if(isset($error_msg)) {
            die("Error in server communication ".$error_msg);
        } elseif ($response) {
            echo $postUrl." : "; pre($response);
        }
    }//End of submit()
    
    function test() {
        echo base64_encode(file_get_contents(FCPATH.'storage/mini.pdf'));
    }
    
}//End of Umangapi