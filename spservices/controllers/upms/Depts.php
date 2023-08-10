<?php
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Depts extends Upms {

    public function __construct() {
        parent::__construct();
        $this->isdev();
        $this->load->model('upms/depts_model');
    }//End of __construct()
  
    public function index($objId=null) {
        $data['dbrow'] = $this->depts_model->get_by_doc_id($objId);
        $this->load->view('upms/includes/header', ["header_title" => "UPMS : Departments"]);
        $this->load->view('upms/depts_view', $data);
        $this->load->view('upms/includes/footer');
    }//End of index()
    
    public function submit(){
        $objId = $this->input->post("obj_id");
        $obj_id = strlen($objId)?$objId:null;
        $dept_code = strtoupper($this->input->post("dept_code"));
        $this->form_validation->set_rules('dept_code', 'Code', 'required|alpha_dash|max_length[100]');
        $this->form_validation->set_rules('dept_name', 'Name', 'required|max_length[255]');
        $this->form_validation->set_error_delimiters("<font style='color:red; font-size:12px; font-style:italic'>", "</font>"); 
        
        $isExist = $this->depts_model->get_row(['dept_code' => $dept_code]);
            
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('flashMsg','Error in inputs : '.validation_errors());
            $this->index($obj_id);
        } elseif(!strlen($objId) && $isExist) {
            $this->session->set_flashdata('flashMsg','Department code already exist');
            $this->index($obj_id);
        } else {
            $data = array(
                "dept_name" => $this->input->post("dept_name"),
                "dept_description" => $this->input->post("dept_description"),
                "status" => 1
            );
            if(strlen($objId)) {
                $this->depts_model->update_where(['_id' => new ObjectId($objId)], $data);
                $this->session->set_flashdata('flashMsg','Data has been successfully updated');
            } else {
                $data["dept_code"] = $dept_code;
                $this->depts_model->insert($data);
                $this->session->set_flashdata('flashMsg','Data has been successfully submitted');                
            }//End of if else
            redirect('spservices/upms/depts');
        }//End of if else
    }//End of submit()
}//End of Depts