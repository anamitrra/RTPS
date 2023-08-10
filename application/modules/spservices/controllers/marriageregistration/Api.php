<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
class Api extends frontend {
    
    private $serviceName = "Application for Marriage Registration";
    private $serviceId = "MARRIAGE_REGISTRATION";
    
    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('marriageregistration/marriageregistrations_model');
        $this->load->config('spconfig');  
        $this->load->helper('smsprovider');
    }//End of __construct()

    public function update_data() { //echo "<pre>"; var_dump($_POST); echo "</pre>";
        $applId =  $this->input->post("applId");
        $wsResponse =  $this->input->post("wsResponse");
        $decodedText = stripslashes(html_entity_decode($wsResponse));
        $resObj = json_decode($decodedText);
        
        if ($this->checkObjectId($applId)) {
            $dbrow = $this->marriageregistrations_model->get_by_doc_id($applId); //pre($dbrow);
            if(count((array)$dbrow)) {
                $processing_history = $dbrow->processing_history??array();
                $rtps_trans_id = $dbrow->rtps_trans_id;
                $status = $resObj->Status??'';
                $remarks = $resObj->Remarks??'';
                $certificate = $resObj->certificate??'';
                if($status === 'QS') {
                    $processing_history[] = array(
                        "processed_by" => "Query made by Department",
                        "action_taken" => "Query made",
                        "remarks" => "Query made by Department",
                        "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );
                    $data = array(
                        'remarks' => $remarks,
                        'status' => $status,
                        'processing_history' => $processing_history,
                        'query_time' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                    );
                    $this->marriageregistrations_model->update_where(['_id' => new ObjectId($applId)], $data);

                    //Sending Query SMS
                    $sms=array(
                        "mobile" => (int)$dbrow->mobile,
                        "applicant_name" => $dbrow->applicant_first_name." ".$dbrow->applicant_last_name,
                        "service_name" => $this->serviceName,
                        "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->submission_date))),
                        "app_ref_no" => $dbrow->rtps_trans_id,
                        "rtps_trans_id" => $dbrow->rtps_trans_id
                    );
                    sms_provider("query",$sms);
                    $resPost = array('status' => true, 'message' => 'Query sent successfully');
                } elseif($status === 'D') {
                    //Update status and certificate
                    $processing_history[] = array(
                        "processed_by" => "Application delivered by Department",
                        "action_taken" => "Application delivered",
                        "remarks" => "Application delivered by Department",
                        "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );
                    $data = array(
                        'remarks' => $remarks,
                        'status' => $status,
                        'certificate' => '',
                        'processing_history' => $processing_history,
                        'generated_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                    );
                    $this->marriageregistrations_model->update_where(['_id' => new ObjectId($applId)], $data);

                    //Sending delivered SMS
                    $sms=array(
                        "mobile" => (int)$dbrow->applicant_mobile_number,
                        "applicant_name" => $dbrow->applicant_first_name." ".$dbrow->applicant_last_name,
                        "service_name" => $this->serviceName,
                        "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->submission_date))),
                        "app_ref_no" => $dbrow->rtps_trans_id,
                        "rtps_trans_id" => $dbrow->rtps_trans_id
                    );
                    sms_provider("delivery",$sms);

                    $resPost = array('status' => true, 'message' => 'Certificate updated successfully');
                } else {
                    $resPost = array('status' => false, 'message' => 'Status code does not matched');
                }//End of if else
            } else {
                $resPost = array('status' => false, 'message' => 'No records found');
            }//End of if else
        } else {
            $resPost = array('status' => false, 'message' => 'Invalid application id');
        }//End of if else
        $json_obj = json_encode($resPost);
        $this->output->set_content_type('application/json')->set_output($json_obj);
    }//End of update_data()
        
    private function checkObjectId($obj){
        if(preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
           return false;
        }//End of if else
    }//End of checkObjectId()   

}//End of Api