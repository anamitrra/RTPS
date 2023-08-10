<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Workflow extends Upms {

    public function __construct() {
        parent::__construct();
        $this->isdev();
        $this->load->model('upms/services_model');
        $this->load->model('upms/levels_model');
        $this->load->model('upms/users_model');
    }//End of __construct()
  
    public function index($service_code=null) {
        $dbrow = $this->services_model->get_row(array("service_code" => $service_code));
        if($dbrow) {
            $data['dbrow'] = $dbrow;
            $this->load->view('upms/includes/header', ["header_title" => "UPMS : Workflow for ".$dbrow->service_name]);
            $this->load->view('upms/workflow_view', $data);
            $this->load->view('upms/includes/footer');
        } else {
            $this->session->set_flashdata('flashMsg','Service does not exist');     
            redirect('spservices/upms/svcs');
        }//End of if else            
    }//End of index()
}//End of Workflow