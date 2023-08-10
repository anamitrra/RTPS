<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Logreports extends Frontend {

    private $serviceName = "Application for Minority Community Certificate";
    private $serviceId = "MCC";

    public function __construct() {
        parent::__construct();
        $this->load->model('minoritycertificates/logreports_model');
        $this->load->helper("role");
        $this->load->library('user_agent');
    }//End of __construct()

    public function addrecord() {
        $aadhaar_request_url = $this->input->post('aadhaar_request_url');
        $aadhaar_request_id = $this->input->post('aadhaar_request_id'); 
        $html_content = $_POST['aadhaar_request_content'];//$this->input->post('html_content'); 
        $page_source_code = '<!DOCTYPE html><html lang="en">'.$html_content.'</html>';//pre($html_content);
        $data = array(
            "client_ip" => $this->input->server('REMOTE_ADDR'),
            "client_browser" => $this->agent->agent_string(),
            "logged_user_id" => $this->session->userId->{'$id'} ?? '',
            "request_url" => $aadhaar_request_url,//$this->input->server('REQUEST_URI'),
            "request_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
            "request_content" => $page_source_code//htmlentities($page_source_code, ENT_QUOTES)
        );
        
        if(strlen($aadhaar_request_id)) {
            $data = array(
                "response_url" => $aadhaar_request_url,
                "response_time" => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000),
                "response_content" => $page_source_code
            );
            $this->logreports_model->update_where(['_id' => new ObjectId($aadhaar_request_id)], $data);
            //$this->session->unset_userdata('aadhaar_request_id');
            echo '';//'Respond has been logged successfully';
        } else {
            $insert = $this->logreports_model->insert($data);
            //$this->session->set_userdata('aadhaar_request_id', $insert['_id']->{'$id'});
            echo $insert['_id']->{'$id'};//'Request has been logged successfully';
        }//End of if else        
        //pre($data);
    }//End of addrecord()

}//End of Logreports
