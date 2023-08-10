<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site extends Frontend {

    public function __construct() {
        parent::__construct();
        $this->load->model('upms/services_model');
        $this->load->model('upms/levels_model');
        $this->load->model('upms/districts_model');
        $this->load->model('upms/users_model');
        $this->load->model('upms/applications_model');
    }//End of __construct()
  
    public function index() {
        $this->load->view('includes/frontend/header');
        $this->load->view('upms/apply_form', array("dbrow"=>[]));
        $this->load->view('includes/frontend/footer');
    }//End of index()
        
    public function submit(){
        $objId = $this->input->post("obj_id");
        $service_details = json_decode(html_entity_decode($this->input->post("service_details")));  
        $submitted_at = json_decode(html_entity_decode($this->input->post("submitted_at")));  
        
        $this->form_validation->set_rules('applicant_name', 'Name', 'required|max_length[255]');
        $this->form_validation->set_rules('email_id', 'Email id', 'valid_email|max_length[255]');
        $this->form_validation->set_rules('service_details', 'Service', 'required|max_length[255]');
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>"); 
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flash_msg','Error in inputs : '.validation_errors());            
            $this->index($objId);
        } else {
            //header("Content-Type: text/plain"); echo '<pre>'; var_dump($service_details->service_code); '</pre>'; die;
            //$userFilter = array('user_department.department_code' => $departmentCode, 'user_location.location_id' => (int)$district_code);
            //$userFilter = array('user_services.service_code' => $service_details->service_code, 'user_location.location_id' => (int)$submitted_at->location_id);
            $userFilter = array('user_services.service_code' => $service_details->service_code);
            $userRows = $this->users_model->get_rows($userFilter);
            $current_users = array();
            if($userRows) {
                foreach ($userRows as $key => $userRow) {
                    $current_user = array(
                        'login_username' => $userRow->login_username,
                        'email_id' => $userRow->email_id,
                        'mobile_number' => $userRow->mobile_number,
                        'user_level_no' => $userRow->user_levels->level_no,
                        'user_fullname' => $userRow->user_fullname,
                    );
                    $current_users[] = $current_user;
                } //End of foreach()         
            
                $appl_ref_no = "RTPS-CODE/2023/". random_int(10000, 99999);
                $data = array(
                    "service_data" => array(
                        "service_id" => $service_details->service_code,
                        "service_name" => $service_details->service_name,
                        "appl_ref_no" => "RTPS-CODE/2023/". random_int(10000, 99999),
                        "submitted_at" => $submitted_at,
                        "appl_status" => "PR",
                        "appl_ref_no" => $appl_ref_no
                    ),
                    "form_data" => array(
                        "user_id" => $service_details->service_code,
                        "applicant_name" => $this->input->post("applicant_name"),
                        "applicant_gender" => $this->input->post("applicant_gender"),
                        "mobile_number" => $this->input->post("mobile_number"),
                        "email_id" => $this->input->post("email_id"),
                    ),
                    "current_users" => $current_users
                );//echo '<pre>'; var_dump($data); die;
                if(strlen($objId)) {
                    $this->applications_model->update_where(['_id' => new ObjectId($objId)], $data);
                    $this->session->set_flashdata('flash_msg','Your application has been successfully updated');
                } else {
                    $this->applications_model->insert($data);
                    $this->session->set_flashdata('flash_msg','Your application has been successfully submitted. Ref. no. '.$appl_ref_no);                
                }//End of if else
                redirect('spservices/upms/');           
            } else {
                $this->session->set_flashdata('flash_msg','User does not exist or mapped for the selected service');            
                $this->index($objId);
            } //End of if  else
        }//End of if else
    }//End of submit()
}//End of Site