<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Site extends Rtps {

    private $serviceName = "Application for Certified Copy of Registered Deed";
    Private $serviceId = "CRCPY";

    public function __construct() {
        parent::__construct();
        $this->load->model('registered_deed_model');
        $this->load->model('necprocessing_model');
        $this->load->helper("cifileupload");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }
    }//End of __construct()

    public function index($obj_id = null) {
        //check_application_count_for_citizen();
        $data = array("pageTitle" => "Application for Certified Copy of Registered Deed");
        $filter = array("_id" => new ObjectId($obj_id), "status" => "DRAFT");
        $data["dbrow"] = $this->registered_deed_model->get_row($filter);
        $data['usser_type'] = $this->slug;
        $data["sro_dist_list"] = $this->registered_deed_model->sro_dist_list();

        $this->load->view('includes/frontend/header');
        $this->load->view('certified_copy_landhub/registered_deed', $data);
        $this->load->view('includes/frontend/footer');
    }//End of index()
}//End of Site