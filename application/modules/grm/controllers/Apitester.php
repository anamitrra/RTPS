<?php
use MongoDB\BSON\UTCDateTime;
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Apitester extends frontend {
    protected $encryptionKey = 'K^揄i��`���Q.��';
    
    public function __construct() {
        parent::__construct();
        $this->load->library('AES');
    }//End of __construct()
    
    function index() {
        exit('No direct script access allowed');
    }//End of index()
        
    function enc(){
        $data = array(
            "SystemID" => "001",
            "userId" => "001",
            "userType" => "CallCenter",
            "Name"=>"Test application, Please reject",
            "Gender"=>"F",
            "Address1"=>"Address1",
            "Address2"=>"Address2",
            "Address3"=>"Address3",
            "Pincode"=>"781001",
            "District"=>"290",
            "EmailAddress"=>"nicashraful@gmail.com",
            "MobileNumber"=>"9878987678",
            "SubjectContent"=>"For testing purpose only",
            "GrievanceCategory" => "Service not delivered",
            "ServiceId" => "0004",
            "ServiceName" => "Issuance of Non Encumbrance Certificate",
            "refno" => "YES",
            "rtpsrefno" => "RTPS-SCODE/2023/123456"
         );
        $aesObject   = new AES(json_encode($data),$this->encryptionKey);
        $res= $aesObject->encrypt();
        
        $json_obj = json_encode(array("encryptedString"=>$res));
        $this->output->set_content_type('application/json')->set_output($json_obj);
    }//End of enc()
    
    function de(){
        $text = $this->input->post('encrypted_string');
        $aesObject   = new AES($text,$this->encryptionKey);
        echo $aesObject->decrypt();
    }//End of de()
    
    public function cpgram_push($field_name=null,$field_value=null) {
        if(strlen($field_name) && strlen($field_value)) {
            $this->load->model('public_grievance_model');
            $dbRow = $this->public_grievance_model->get_row(["$field_name"=>$field_value]);
            if($dbRow) {
                $inputs = [
                    "GrievanceReferenceNumber" => $dbRow->GrievanceReferenceNumber,
                    "Name" => "RTPS-" . $dbRow->Name,
                    "Gender" => $dbRow->Gender,
                    "Address1" => $dbRow->Address1 ?? ' ',
                    "Address2" => $dbRow->Address2 ?? ' ',
                    "Address3" => $dbRow->Address3 ?? ' ',
                    "Pincode" => $dbRow->Pincode,
                    "District" => $dbRow->District,
                    "State" => $dbRow->State,
                    "Country" => $dbRow->Country,
                    "EmailAddress" => $dbRow->EmailAddress,
                    "MobileNumber" => $dbRow->MobileNumber,
                    "Language" => "E",
                    "SubjectContent" => $dbRow->SubjectContent,
                    "DateOfReceipt" => date('m-d-Y', intval(strval($dbRow->DateOfReceipt)) / 1000),
                    "ComplainantIpAddress" => $dbRow->ComplainantIpAddress,
                    "ExServicemen" => $dbRow->ExServicemen,
                    "AutoForwardOrgCode" => $dbRow->AutoForwardOrgCode,
                    "Document" => $dbRow->Document??''
                ];
                $this->load->library('CPGRMS_api_client', ['type' => 'register-grievance']);
                $apiResponse = $this->cpgrms_api_client->post($inputs);
                echo "CPGram Response : "; pre($apiResponse);
            } else {
                die("No records found for {$field_name} : {$field_value}");
            }//End of if else
        } else {
            die("Parameters cannot be empty");
        }//End of if else
    }//End of cpgram_push()

}