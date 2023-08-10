<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Buttonlevels extends Upms {

    public function __construct() {
        parent::__construct();
        $this->isdev();
        $this->load->model('upms/rights_model');
        $this->load->model('upms/services_model');
    }//End of __construct()
  
    public function index($serviceCode=null) {
        $data['dbrow'] = $this->services_model->get_row(["service_code"=>$serviceCode]);
        $this->load->view('upms/includes/header', ["header_title" => "UPMS : Button levels"]);
        $this->load->view('upms/buttonlevels_view', $data);
        $this->load->view('upms/includes/footer');
    }//End of index()
    
    public function submit(){
        $service_code = $this->input->post("service_code");
        $rights = $this->rights_model->get_rows(array("status"=>1));
        foreach ($rights as $right) {
            $rightCode = strtolower($right->right_code); 
            $this->form_validation->set_rules($rightCode, ucfirst($rightCode), 'required|max_length[255]');
        }        
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>"); 
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg','Error in inputs : '.validation_errors());            
            $this->index($service_code);
        } else {
            foreach ($rights as $right) {
                $right_code = strtolower($right->right_code);                
                $button_level = $this->input->post($right_code);
                $button_levels[$right_code] = $button_level;
            }//End of foreach()
            $data = array("button_levels" => $button_levels);
            $this->services_model->update_where(['service_code' => $service_code], $data);
            $this->session->set_flashdata('flashMsg','Button levels has been successfully updated');
            redirect('spservices/upms/buttonlevels/index/'.$service_code);            
        }//End of if else
    }//End of submit()
}//End of Buttonlevels