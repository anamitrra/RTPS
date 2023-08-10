<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration extends Rtps
{
    private $serviceName = "Application for Gap Permission";
    private $serviceAssameseName = "গেপ অনুমতিৰ বাবে আবেদন";
    private $serviceId = "AHSECGAP";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('gappermissioncertificateahsec/registration_model');
        $this->load->model('duplicatecertificateahsec/ahsecregistration_model');
        $this->load->helper("cifileupload");
        $this->load->helper("cifileuploaddigilocker");
        $this->load->config('spconfig');
        $this->load->helper("appstatus");
        $this->load->helper('smsprovider');
        $this->load->helper('log');
        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }
    } //End of __construct()
    public function fetchsessions($no_of_year)
    {
        $currentYear = date('Y') - 3;
        // var_dump($currentYear);
        $yearArray = array();
        for ($i = $currentYear; $i >= ($currentYear - $no_of_year); $i--) {
            $present_year = $i;
            $next_year = sprintf("%02d", (substr($i, -2) + 1));
            $yearArray[$i . '-' . ($next_year)] = $i . '-' . ($next_year);
        }
        // var_dump($yearArray);
        return $yearArray;
    }
    public function index($obj_id = null)
    {
        if ($obj_id == null) {
            $this->my_transactions();
        } else {
            $data = array(
                "pageTitle" => $this->serviceName,
                "PageTiteAssamese" => $this->serviceAssameseName,
            );
            $filter = array(
                "_id" => new ObjectId($obj_id),
                "service_data.appl_status" => "DRAFT",
            );
            $data["dbrow"] = $this->registration_model->get_row($filter);
            $data['usser_type'] = $this->slug;
            $data["states"] = $this->registration_model->getStates();
            $data["districts"] = $this->registration_model->getDistricts();
            $data["sessions"] = $this->fetchsessions(15);
            // pre($data["dbrow"]);
            // echo "asdasd";
            // exit();
            $this->load->view('includes/frontend/header');
            $this->load->view('gappermissioncertificateahsec/section_one_create', $data);
            $this->load->view('includes/frontend/footer');
        }
    } //End of index()
   
}
