<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
date_default_timezone_set('Asia/Calcutta');

class Statusupdate extends frontend {
    
    public $openApps = array();

    public function __construct() {
        parent::__construct();
        $this->load->model('public_grievance_model');
    }//End of __construct()
           
    public function index() {
        echo $this->getOpenApplications();
    }//End of index()
    
    public function getOpenApplications() {
        $filter['current_status'] = ['$ne' => 'Case closed'];
        $results = $this->public_grievance_model->get_filtered_rows($filter);
        $str = "";
        if($results) {
            foreach ($results as $rows) {
                $objId = $rows->{"_id"}->{'$id'};
                $MobileNumber=$rows->MobileNumber;
                $registration_no = str_replace('/', '__', $rows->registration_no);
                $str = $str.",".$MobileNumber."-".$registration_no."-".$objId;
            }//End of foreach()
        }//End of if
        return trim($str, ",");
    }//End of getOpenApplications()
        
    public function getnupdate($res=null) {
        if(strlen($res) < 30) {
            echo "Invalid parameter";
        } else {
            $pcs = explode("-", $res);
            $mobile_number = $pcs[0];
            $registration_no = str_replace('__', '/', $pcs[1]);
            $objId = $pcs[2];
            $inputs = [
                'RegistrationNumber' => $registration_no,
                'EmailOrMobile' => $mobile_number
            ];               
            $this->load->library('sadbhawana_cpgrams', ['type' => 'view-status']);        
            $apiResponse = $this->sadbhawana_cpgrams->post($inputs);
            $apiResponseObj = json_decode($apiResponse);               
            if(isset($apiResponseObj)) {               
                $serverStatus = $apiResponseObj->CurrentStatus??'';
                $replyDoc = $apiResponseObj->ReplyDocument ?? '';
                $rem = $apiResponseObj->Remark ?? '';
                $reason = $apiResponseObj->Reason ?? '';
                $date_of_action = date("d-m-Y", strtotime($apiResponseObj->DateOfAction)) ?? date('d-m-Y');
                
                $data = array(
                    "current_status"=>$serverStatus,
                    "reply_document" => $replyDoc,
                    "reply_remark" => $rem,
                    "reason"=>$reason,
                    "date_of_action" => $date_of_action
                );
                $this->public_grievance_model->update($objId, $data);     
                echo $registration_no." : ".$serverStatus."<br>";
            } else {
                echo $registration_no." : No response from server<br>";
            }//End of if else
        }//End of if else            
    }//End of getnupdate()
}//End of Statusupdate