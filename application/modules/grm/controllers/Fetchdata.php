<?php

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Fetchdata extends frontend {

    public function __construct() {
        parent::__construct();
        $this->load->model('public_grievance_model');
        //$this->isLoggedIn();
    }//End of __construct()
           
    public function index() {
        $filter = array("Country" => "001"); //Only for open applications
        $results = $this->public_grievance_model->get_filtered_rows($filter);
        if($results) {
            $counter = 0;
            foreach ($results as $rows) {
                $objId = $rows->{"_id"}->{'$id'};
                $registration_no=$rows->registration_no;
                $MobileNumber=$rows->MobileNumber;
                $filter = array(
                    "RegistrationNumber" => $registration_no,
                    "EmailOrMobile" => $MobileNumber
                );     
                $this->load->library('sadbhawana_cpgrams', ['type' => 'view-status']);        
                $apiResponse = $this->sadbhawana_cpgrams->post($filter);
                $apiResponseObj = json_decode($apiResponse);
                $current_status = isset($apiResponseObj->CurrentStatus)?$apiResponseObj->CurrentStatus:null;
                $replyDocument = isset($apiResponseObj->ReplyDocument)?$apiResponseObj->ReplyDocument:null;
                $rem = isset($apiResponseObj->Remark)?$apiResponseObj->Remark:null;
                $remark = ($rem=="Auto Forwarded - RI Web API")?"UNDER PROCESS":$rem;                
                $reason = isset($apiResponseObj->Reason)?$apiResponseObj->Reason:null;
                if(isset($apiResponseObj->DateOfAction)) {
                    $date_of_action = date("d-m-Y", strtotime($apiResponseObj->DateOfAction));
                } else {
                    $date_of_action = date('d-m-Y');
                }//End of if else                
                
                $data = array(
                    "current_status"=>$current_status,
                    "reply_document" => $replyDocument,
                    "reply_remark" => $remark,
                    "reason" => $reason,
                    "date_of_action" => $date_of_action
                );
                //echo $registration_no." : ".$MobileNumber." => ".$current_status."<br>";
                $this->public_grievance_model->update($objId, $data);
                $counter++;
            }//End of foreach()
            //echo "Toatal no. of records updated : ".$counter;
        }//End of if
    }//End of index()
}//End of Fetchdata