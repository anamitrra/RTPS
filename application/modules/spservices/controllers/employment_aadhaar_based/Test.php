<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Test extends Frontend {
    
    private $aadhaarApi;

    public function __construct() {
        parent::__construct();
        $this->config->load('spservices/spconfig');
        $this->aadhaarApi = $this->config->item('aadhaar_authentication_api');
    }//End of __construct()
    
    public function index() {
        $this->load->view('employment_aadhaar_based/test_view');
    }//End of index()
    
    public function otpsend() {
        $txn_no = uniqid();
        $aadhaar_no = $this->input->post("aadhaar_no");
        $data = array(
            "aadhaar_no" => $aadhaar_no,
            "txn_no" => $txn_no
        );
        $json_obj = json_encode($data); //pre($json_obj);
        
        $headers = array(
            "Content-Type: application/json",
            "Accept: application/json",
        );
        
        $curl = curl_init($this->aadhaarApi."send");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        curl_close($curl);
        
        $result = json_decode($response);
        $data["xml"] = simplexml_load_string($result->status);
        $this->load->view('minoritycertificates/test_view', $data);
    }//End of otpsend()
    
    public function otpverify() {
        $aadhaar_no = $this->input->post("aadhaar_no");
        $txn_no = $this->input->post("txn_no");
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
        $json_obj = json_encode($data); //pre($json_obj);
        
        $headers = array(
            "Content-Type: application/json",
            "Accept: application/json",
        );
        
        $curl = curl_init($this->aadhaarApi."encrypt");
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        curl_close($curl);
        
        $result = json_decode($response);
        $xml = simplexml_load_string($result->status);
        echo '<pre>'; var_dump($xml); echo '</pre>';
        
        
        echo "<br><br><br>";
        echo "ret : ".$xml->attributes()->ret."<br>";
        echo "err : ".$xml->attributes()->err."<br>";
        echo "txn : ".$xml->attributes()->txn."<br>";
        echo "info : ".$xml->attributes()->info."<br>";
        echo "Algorithm : ".$xml->Signature->SignedInfo->CanonicalizationMethod->attributes()->Algorithm."<br>";        
        echo "SignatureValue : ".$xml->Signature->SignatureValue."<br>";
    }//End of otpverify()

}//End of Test
