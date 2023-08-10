<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
class Api extends frontend {
    
    private $serviceName = "Appointment booking for Marriage or Deed registration";
    private $serviceId = "APPOINTMENT_BOOKING";
    
    public function __construct() {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $this->load->model('appointmentbooking/appointmentbookings_model');
        $this->load->config('spconfig');  
        $this->load->helper('smsprovider');
    }//End of __construct()
        
    public function index() { //For testing UMANG API
        exit('No direct script access allowed');
    }//End of index()

    public function update_data() { //echo "<pre>"; var_dump($_POST); echo "</pre>";
        $applId =  $this->input->post("applId");
        $taskId =  $this->input->post("taskId");
        $wsId =  $this->input->post("wsId");
        $wsResponse =  $this->input->post("wsResponse");
        $decodedText = stripslashes(html_entity_decode($wsResponse));
        $resObj = json_decode($decodedText);
        
        if ($this->checkObjectId($applId)) {
            $dbrow = $this->appointmentbookings_model->get_by_doc_id($applId); //pre($dbrow);
            if(count((array)$dbrow)) {
                $processing_history = $dbrow->processing_history??array();
                $appl_ref_no = $dbrow->service_data->appl_ref_no;
                $status = $resObj->status??'';
                $remarks = $resObj->remarks??'';
                $doa = $resObj->doa??'';
                $office = $resObj->office??'';
                if(strlen($status) && $status === 'D') {
                    //Update status and certificate
                    $processing_history[] = array(
                        "processed_by" => "Application delivered by Department",
                        "action_taken" => "Application delivered",
                        "remarks" => "Application delivered by Department",
                        "processing_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                    );
                    $data = array(
                        'form_data.remarks' => $remarks,
                        'service_data.appl_status' => $status,
                        'processing_history' => $processing_history,
                        'form_data.doa' => $doa,
                        'form_data.office' => $office,
                        'form_data.updated_at' =>  new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
                    );
                    $this->appointmentbookings_model->update_where(['_id' => new ObjectId($applId)], $data);

                    //Sending delivered SMS
                    $sms=array(
                        "mobile" => (int)$dbrow->form_data->mobile_number,
                        "applicant_name" => $dbrow->form_data->applicant_name,
                        "service_name" => $this->serviceName,
                        "submission_date" => date("d-m-Y h:i a", strtotime($this->mongo_db->getDateTime($dbrow->service_data->submission_date))),
                        "app_ref_no" => $appl_ref_no,
                        "rtps_trans_id" => $appl_ref_no
                    );
                    sms_provider("delivery",$sms);
                    $resPost = array('status' => true, 'message' => 'Application updated successfully');
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