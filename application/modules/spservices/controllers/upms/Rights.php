<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rights extends Upms {

    public function __construct() {
        parent::__construct();
        $this->isdev();
        $this->load->model('upms/rights_model');
    }//End of __construct()
  
    public function index($objId=null) {
        $data['dbrow'] = $this->rights_model->get_by_doc_id($objId);
        $this->load->view('upms/includes/header', ["header_title" => "UPMS : Rights"]);
        $this->load->view('upms/rights_view', $data);
        $this->load->view('upms/includes/footer');
    }//End of index()
    
    public function submit(){
        $objId = $this->input->post("obj_id");
        $obj_id = strlen($objId)?$objId:null;
        $right_code = strtoupper($this->input->post("right_code"));
        $isExist = $this->rights_model->get_row(array("right_code" => $right_code));
        $this->form_validation->set_rules('right_name', 'Name', 'required|max_length[255]');
        $this->form_validation->set_rules('right_code', 'Code', 'required|max_length[255]');
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>"); 
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg','Error in inputs : '.validation_errors());            
            $this->index($obj_id);
        } elseif(!strlen($objId) && $isExist) {
            $this->session->set_flashdata('flashMsg','Code already exist');
            $this->index($obj_id);
        } else {
            $data = array(
                "right_name" => $this->input->post("right_name"),
                "right_description" => $this->input->post("right_description"),
                "status" => 1
            );
            if(strlen($objId)) {
                $this->rights_model->update_where(['_id' => new ObjectId($objId)], $data);
                $this->session->set_flashdata('flashMsg','Data has been successfully updated');
            } else {
                $data["right_code"] = $right_code;
                $this->rights_model->insert($data);
                $this->session->set_flashdata('flashMsg','Data has been successfully submitted');                
            }//End of if else
            redirect('spservices/upms/rights');
        }//End of if else
    }//End of submit()
}//End of Rights