<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Track extends Rtps {

    private $serviceName = "Application for Marriage Registration";

    public function __construct() {
        parent::__construct();
        $this->load->model('marriageregistration/marriageregistrations_model');    
        
        if(!empty($this->session->userdata('role'))){
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        }//End of if else
    }//End of __construct()

    public function index($objId = null) {        
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->marriageregistrations_model->get_by_doc_id($objId);
            if(count((array)$dbRow)) {
                $data=array(
                    "service_name"=>$this->serviceName,
                    "dbrow"=>$dbRow,
                    "user_type"=> $this->slug
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('marriageregistration/track_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found against object id : '.$objId);
                redirect('spservices/marriageregistration');
            }//End of if else                
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/marriageregistration');
        }//End of if else
    }//End of index()

    public function delivered($objId = null) {        
        if ($this->checkObjectId($objId)) {
            $dbRow = $this->marriageregistrations_model->get_by_doc_id($objId);
            if(count((array)$dbRow)) {
                $data=array(
                    "service_name"=>$this->serviceName,
                    "response"=>$dbRow,
                    "user_type"=> $this->slug
                );
                $this->load->view('includes/frontend/header');
                $this->load->view('marriageregistration/delivered_view', $data);
                $this->load->view('includes/frontend/footer');
            } else {
                $this->session->set_flashdata('error','No records found against object id : '.$objId);
                redirect('spservices/marriageregistration');
            }//End of if else                
        } else {
            $this->session->set_flashdata('error', 'Invalid application id');
            redirect('spservices/marriageregistration');
        }//End of if else
    }//End of delivered()

    private function checkObjectId($obj) {
        if (preg_match('/^[0-9a-f]{24}$/i', $obj) === 1) {
            return true;
        } else {
            return false;
        }//End of if else
    }//End of checkObjectId()
}//End of Track