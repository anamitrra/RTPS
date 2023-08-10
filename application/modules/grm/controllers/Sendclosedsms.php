<?php

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sendclosedsms extends frontend {

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
                $name=$rows->Name;
                $mobileNumber=$rows->MobileNumber;
                $current_status=$rows->current_status??"Under process";
                $closedSms=$rows->closed_sms??"NA";
                if(($current_status === "Case closed") && ($closedSms === "NA")) {
                    $this->sendSms($mobileNumber, $name);
                    $data = array("closed_sms"=>"SENT");
                    $this->public_grievance_model->update($objId, $data);
                    $counter++;
                }                
            }//End of foreach()
            echo "Toatal no. of sms sent : ".$counter;
        }//End of if
    }//End of index()
    
    public function sendSms($number, $name) {
        $message_body = "Dear ".$name.", your grievance at RTPS portal has been closed by the competent authority. Please click the link below for more details. https://rtps.assam.gov.in/grm/trackstatus";
        $dlt_template_id = '1007163825202792026';        
        $ch = curl_init();
        $message_body = str_replace(" ", "%20", $message_body);
        $url = "https://smsgw.sms.gov.in/failsafe/MLink?username=rtpsasm.otp&pin=P4%26fO3%23xF4&message=" . $message_body . "&mnumber=" . $number . "&signature=ARTPSA&dlt_entity_id=1001367290000017171&dlt_template_id=" . $dlt_template_id;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $head = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        //var_dump($head);
        return $head;
    }//End of sendSms()
}//End of Sendclosedsms