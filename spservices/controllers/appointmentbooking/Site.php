<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Site extends Rtps {

    private $serviceName = "Appointment booking for Marriage or Deed registration";

    public function __construct() {
        parent::__construct();
        $this->load->model('appointmentbooking/appointmentbookings_model');
        $this->load->model('sros_model'); 
        
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }//End of if else
    }//End of __construct()

    public function index() {
        check_application_count_for_citizen();
        $data = array("service_name" => $this->serviceName);
        $data["dbrow"] = false;     
        $data["user_type"] = $this->slug;
        $data["sro_dist_list"] = $this->sros_model->sro_dist_list();
        $this->load->view('includes/frontend/header');
        $this->load->view('appointmentbooking/registration_view', $data);
        $this->load->view('includes/frontend/footer');
    }//End of index()
}//End of Site