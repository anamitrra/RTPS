<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Preview extends Rtps {

    private $serviceName = "Application for Marriage Registration";

    public function __construct() {
        parent::__construct();
        $this->load->model('marriageregistration/districts_model');
        $this->load->model('marriageregistration/states_model');
        $this->load->model('marriageregistration/countries_model');
        $this->load->model('marriageregistration/marriageregistrations_model');
        $this->load->model('marriageregistration/lac_model');
        $this->load->model('sros_model');
        $this->load->helper("cifileupload");
        $this->load->helper("appstatus");
        $this->load->config('spconfig');

        if (!empty($this->session->userdata('role'))) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }//End of if else
    }//End of __construct()

    public function index($objId = null) {
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->marriageregistrations_model->get_by_doc_id($objId);
            if (count((array) $dbRow)) {
                $data = array(
                    "service_name" => $this->serviceName,
                    "dbrow" => $dbRow,
                    "user_type" => $this->slug
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('marriageregistration/preview_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No records found against object id : ' . $objId);
                redirect('spservices/marriageregistration/');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/marriageregistration/');
        }//End of if else
    }//End of index()

    public function acknowledgement($objId = null) {
        if ($this->checkObjectId($objId)) {
            $this->load->model('services_model');
            
            if($this->slug === "CSC"){                
                $apply_by = $this->session->userId;
            } else {
                $apply_by = new ObjectId($this->session->userdata('userId')->{'$id'});
            }//End of if else
            
            $dbFilter = array(
                '_id' => new ObjectId($objId),
                'applied_by' => $apply_by,
                'payment_status' => 'PAID'
            );
                    
            $dbRow = $this->marriageregistrations_model->get_row($dbFilter);
            if ($dbRow) {
                $data = array(
                    "service_name" => $this->serviceName,
                    "dbrow" => $dbRow,
                    "user_type" => $this->slug
                );
                $data['service_row'] = $this->services_model->get_row(array("service_id"=>"MARRIAGE_REGISTRATION"));
                $data['back_to_dasboard'] = '<a href="' . base_url('spservices/applications/') . '" class="btn btn-primary mb-2 pull-right"  >Back To Dashboard</a>';
                $data['pageTitle'] = "Acknowledgement";
                $this->load->view('includes/frontend/header');
                $this->load->view('marriageregistration/acknowledgement_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error', 'No acknowledgement found against object id : ' . $objId);
                redirect('spservices/marriageregistration/');
            }//End of if else
        } else {
            $this->session->set_flashdata('error','Invalid application id');
            redirect('spservices/marriageregistration/');
        }//End of if else
    }//End of acknowledgement()

    private function checkObjectId($obj) {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        }//End of if else
    }//End of checkObjectId()
}//End of Preview
