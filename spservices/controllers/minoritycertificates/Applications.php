<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Applications extends Rtps {
    
    private $serviceName = "Application for Minority Community Certificate";
    private $serviceId = "MCC";

    public function __construct() {
        parent::__construct();
        $this->load->model('minoritycertificates/registrations_model');
        $this->load->helper("minoritycertificate");
        
        if($this->session->role){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }//End of if else
    }//End of __construct()

    public function index() {
        $filter=array(
            "applied_user_id"=> ($this->slug === "CSC") ? $this->session->userdata('userId'):new ObjectId($this->session->userdata('userId')->{'$id'}),
        );
        $data["minoritycertificates"] = $this->registrations_model->get_rows($filter);       
        $this->load->view('includes/frontend/header');
        $this->load->view('minoritycertificates/applications_view',$data);
        $this->load->view('includes/frontend/footer');
    }//End of index()

}//End of Applications
