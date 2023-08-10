<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Site extends Rtps {

    private $serviceName = "Application form for Non-Encumbrance Certificate<br> বোজমুক্ত প্ৰমাণ পত্ৰৰ বাবে আবেদন";
    private $serviceId = "NECERTIFICATE";

    public function __construct() {
        parent::__construct();
        $this->load->model('necertificates_model');
        $this->load->model('sros_model');
        $this->load->helper("cifileupload");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');

        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        } //End of if else
        
        if($this->slug === "CSC"){                
            $this->apply_by = $this->session->userId;
        } else {
            $this->apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
        }//End of if else
    }//End of __construct()

    public function index($objId = null) {        
        //check_application_count_for_citizen();
        $data = array(
            "obj_id" => $objId,
            "service_name" => $this->serviceName
        );
        $data["dbrow"] = false;
        $data["sro_dist_list"] = $this->sros_model->sro_dist_list();
        $this->load->view('includes/frontend/header');
        $this->load->view('nec_landhub/necertificate_view', $data);
        $this->load->view('includes/frontend/footer');
    }//End of index()
}//End of Site