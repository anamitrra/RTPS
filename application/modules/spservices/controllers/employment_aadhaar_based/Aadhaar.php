<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Aadhaar extends Frontend
{

    private $aadhaarApi, $aadhaar_request_id;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('employment_aadhaar_based/employment_model');
        $this->config->load('spservices/spconfig');
        $this->aadhaarApi = $this->config->item('aadhaar_authentication_api');
        $this->load->model('employment_aadhaar_based/logreports_model');
        $this->load->helper("role");
        $this->load->library('user_agent');
    } //End of __construct()

    public function index()
    {
        $this->load->view('minoritycertificates/test_view');
    } //End of index()

    public function otpsend()
    {

        $txn_no = uniqid();
        //loading session library 
        $this->load->library('session');

        //adding data to session 
        $this->session->set_userdata('txn_no', $txn_no);
        $aadhaar_no = $this->input->post("aadhaar_number");
        $data = array(
            "aadhaar_no" => $aadhaar_no,
            "txn_no" => $txn_no
        );
        $this->aadhaar_request_id = $this->addrecord($this->aadhaar_request_id, $this->aadhaarApi . "send", array('txn_no' => $txn_no));

        $json_obj = json_encode($data); //pre($json_obj);

        $headers = array(
            "Content-Type: application/json",
            "Accept: application/json",
        );
        if (strpos(base_url(), 'localhost')) { //For local test  
            $resData = array(
                "status" => 1,
                "ret" => array("0" => "y"),
                "msg" => '',
                "txn_no" => '',
                "info" => '',
                "xml" => array()
            );
            $this->aadhaar_request_id = $this->addrecord($this->aadhaar_request_id, $this->aadhaarApi . "send", $resData);
            echo json_encode($resData);
        } else {
            $curl = curl_init($this->aadhaarApi . "send");
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            curl_close($curl);

            $result = json_decode($response);
            /*$xml = simplexml_load_string($result->status);
            $resData = array(
                "ret" => $xml->attributes()->ret,
                "txn_no" => $txn_no,
                "info" => $xml->attributes()->info,
                "xml" => $xml
            );*/
            if (isset($error_msg)) {
                $resData = array(
                    "status" => 0,
                    "ret" => array("0" => "n"),
                    "msg" => $error_msg,
                    "txn_no" => '',
                    "info" => '',
                    "xml" => array()
                );
            } else {
                $result = json_decode($response);
                $xml = simplexml_load_string($result->status);
                $this->aadhaar_request_id = $this->addrecord($this->aadhaar_request_id, $this->aadhaarApi . "send", $xml);
                $err = $xml->attributes()->err;
                $errorMessage = $this->getErrMsg($err);
                $resData = array(
                    "status" => 1,
                    "ret" => $xml->attributes()->ret,
                    "msg" => $errorMessage,
                    "txn_no" => $xml->attributes()->txn,
                    "info" => $xml->attributes()->info,
                    "xml" => $xml,
                );
            } //End of if else
            echo json_encode($resData);
        } //End of if else
    } //End of otpsend()

    public function otpverify()
    {
        // $objId = $this->input->post("obj_id");        
        $aadhaar_no = $this->input->post("aadhaar_number");
        // $txn_no = $this->input->post("txn");
        $txn_no = $this->session->userdata('txn_no');

        $otp = $this->input->post("otp");
        $name = $this->input->post("name");
        $state = $this->input->post("state");
        $data = array(
            "uid" => $aadhaar_no,
            "otp" => $otp,
            "name" => $name,
            "state" => $state,
            "txn_no" => $txn_no
        );

        pre($data);
        $this->aadhaar_request_id = $this->addrecord($this->aadhaar_request_id, $this->aadhaarApi . "encrypt", array('txn_no' => $txn_no, 'otp' => $otp));
        $json_obj = json_encode($data); //pre($json_obj);

        $headers = array(
            "Content-Type: application/json",
            "Accept: application/json",
        );
        if (strpos(base_url(), 'localhost')) { //For local test            
            $resData = array(
                "status" => 1,
                "ret" => array("0" => "y"),
                "msg" => '',
                "txn_no" => '',
                "info" => '',
                "xml" => array()
            );
            $this->aadhaar_request_id = $this->addrecord($this->aadhaar_request_id, $this->aadhaarApi . "encrypt", $resData);
            echo json_encode($resData);
        } else {
            $curl = curl_init($this->aadhaarApi . "encrypt");
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $error_msg = curl_error($curl);
            }
            curl_close($curl);
            if (isset($error_msg)) {
                $resData = array(
                    "status" => 0,
                    "ret" => array("0" => "n"),
                    "msg" => $error_msg,
                    "txn_no" => '',
                    "info" => '',
                    "xml" => array()
                );
            } else {
                $result = json_decode($response);
                $xml = simplexml_load_string($result->status);
                $this->aadhaar_request_id = $this->addrecord($this->aadhaar_request_id, $this->aadhaarApi . "encrypt", $xml);
                $err = $xml->attributes()->err;
                $errorMessage = $this->getErrMsg($err);
                $resData = array(
                    "status" => 1,
                    "ret" => $xml->attributes()->ret,
                    "msg" => $errorMessage,
                    "txn_no" => $xml->attributes()->txn,
                    "info" => $xml->attributes()->info,
                    "xml" => $xml
                );
                if ($xml->attributes()->ret === 'y') {
                    $data_to_update = array(
                        'aadhaar_verify_status' => 1,
                        "hashed_id" => password_hash($aadhaar_no, PASSWORD_DEFAULT) //to be verify by using password_verify($aadhaar_no, $hashed_id)
                    );
                    $this->employment_model->update_where(['_id' => new ObjectId($objId)], $data_to_update);
                } //End of if
            } //End of if else                
            echo json_encode($resData);
        } //End of if else
    } //End of otpverify()

    function getErrMsg($errCode)
    {
        switch ($errCode) {
            case '100':
                $errMsg = "Attributes(basic) of demographic data did not match";
                break;
            case '200':
                $errMsg = "Attributes(address) of demographic data did not match";
                break;
            case 'PAYMENT_INITIATED':
                $errMsg = "PAYMENT INITIATED";
                break;
            case '331':
                $errMsg = "Aadhaar locked by Aadhaar number holder for all authentications";
                break;
            case '332':
                $errMsg = "Aadhaar number usage is blocked by Aadhaar number holder";
                break;
            case '400':
                $errMsg = "Invalid OTP value";
                break;
            case '998':
                $errMsg = "Invalid Aadhaar Number/Virtual ID.";
                break;
            default:
                $errMsg = $errCode;
                break;
        } //End of switch
        return $errMsg;
    } // End of getErrMsg()

    public function addrecord($aadhaar_request_id, $aadhaar_request_url, $aadhaar_request_content)
    {
        $data = array(
            "client_ip" => $this->input->server('REMOTE_ADDR'),
            "client_browser" => $this->agent->agent_string(),
            "logged_user_id" => $this->session->userId->{'$id'} ?? '',
            "request_url" => $aadhaar_request_url, //$this->input->server('REQUEST_URI'),
            "request_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "request_content" => $aadhaar_request_content
        );

        if (strlen($aadhaar_request_id)) {
            $data = array(
                "response_url" => $aadhaar_request_url,
                "response_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "response_content" => $aadhaar_request_content
            );
            $this->logreports_model->update_where(['_id' => new ObjectId($aadhaar_request_id)], $data);
            //$this->session->unset_userdata('aadhaar_request_id');
            return ''; //'Respond has been logged successfully';
        } else {
            $insert = $this->logreports_model->insert($data);
            //$this->session->set_userdata('aadhaar_request_id', $insert['_id']->{'$id'});
            return $insert['_id']->{'$id'}; //'Request has been logged successfully';
        } //End of if else        
        //pre($data);
    } //End of addrecord()

}//End of Aadhaar
