<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Registration_query extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('employment_nonaadhaar/employment_model');
        $this->load->model('employment_nonaadhaar/district_model');
        $this->load->model('employment_nonaadhaar/sub_division_model');
        $this->load->model('employment_nonaadhaar/revenue_circle_model');
        $this->load->model('employment_nonaadhaar/functional_roles_model');
        $this->load->model('employment_nonaadhaar/functional_area_model');
        $this->load->model('employment_nonaadhaar/industry_sector_model');
        $this->load->model('employment_nonaadhaar/employment_office_model');
        $this->load->model('employment_nonaadhaar/highest_examination_passed_model');
        $this->load->model('employment_nonaadhaar/examination_passed_model');

        $this->load->helper("cifileupload");
        $this->load->helper("cifileuploaddigilocker");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');
        $this->load->helper("log");
        $this->load->helper('smsprovider');
        $this->load->helper("employmentcertificate");
        $this->load->model('employment_nonaadhaar/logreports_model');
        $this->load->helper("role");
        $this->load->library('user_agent');
        $this->load->library('digilocker_api');
        if ($this->session->role) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        } //End of if else
    } //End of __construct()

    public function index($objId = null)
    {
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->employment_model->get_row(array("_id" => new ObjectId($objId), "service_data.appl_status" => "QS"));
            if ($dbRow) {
                echo 'valid app';
                // $data = array(
                //     "service_data.service_name" => $this->serviceName,
                //     "dbrow" => $dbRow
                // );
                // $this->load->view('includes/frontend/header');
                // $this->load->view('employment_nonaadhaar/registration_query_view', $data);
                // $this->load->view('includes/frontend/footer');
            } else {
                echo 'not valid app';

                // $this->session->set_flashdata('error', 'No records found in query mode against object id : ' . $objId);
                // redirect('spservices/income/inc');
            } //End of if else
        } else {
            echo 'invalid id';

            // $this->session->set_flashdata('error', 'Invalid application id');
            // redirect('spservices/income/inc');
        } //End of if else
    } //End of query()

    private function checkObjectId($obj)
    {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        } //End of if else
    } //End of checkObjectId()
}
