<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
class Circleoffices extends Rtps {

    public function __construct() {
        parent::__construct();
        $this->load->model('circleoffices_model');
        $this->load->model('district_model');
    }//End of __construct()

    public function index($circle_code=null) {
        $data["dbrow"] = $this->circleoffices_model->get_row(array("circle_code" => (int)$circle_code));            
        $this->load->view('includes/frontend/header');
        $this->load->view('mutationorder/circleoffices_view', $data);
        $this->load->view('includes/frontend/footer');
    }//End of index()
    
    public function submit() {//pre($this->session->userdata());
        $slug = $this->session->role->slug??''; 
        if($slug === "SA"){
            $this->form_validation->set_rules("circle_code", "Code", "trim|required|max_length[255]");
            $this->form_validation->set_rules("circle_name", "Name", "trim|required|max_length[255]");
            $this->form_validation->set_rules("district_code", "District", "trim|required|max_length[255]");
            $this->form_validation->set_rules("treasury_code", "Treasury code", "trim|required|max_length[255]");
            $this->form_validation->set_rules("office_code", "Office code", "trim|required|max_length[255]");
            $this->form_validation->set_rules("remarks", "Remarks", "trim|max_length[255]");

            $objId = $this->input->post("obj_id");

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', 'Error in inputs : ' . validation_errors());
                $this->index($objId);
            } else {            
                $data = array(
                    "circle_code" => (int)$this->input->post("circle_code"),
                    "circle_name" => $this->input->post("circle_name"),
                    "district_code" => $this->input->post("district_code"),
                    "treasury_code" => $this->input->post("treasury_code"),
                    "office_code" => $this->input->post("office_code"),
                    "remarks" => $this->input->post("remarks")
                );
                //pre($data);
                if (strlen($objId)) {
                    $this->circleoffices_model->update_where(['_id' => new ObjectId($objId)], $data);
                    $this->session->set_flashdata('success', 'Data has been successfully submitted');
                    //redirect('spservices/circleoffices/registration/preview/' . $objId);
                    redirect('spservices/circleoffices/');
                } else {                
                    $insert = $this->circleoffices_model->insert($data);
                    if ($insert) {
                        $objectId = $insert['_id']->{'$id'};
                        $this->session->set_flashdata('success', 'Data has been successfully added');
                        redirect('spservices/circleoffices/');
                    } else {
                        $this->session->set_flashdata('fail', 'Unable to submit data!!! Please try again.');
                        $this->index();
                    }//End of if else
                }//End of if else
            }//End of if else
        } else {
            $this->session->set_flashdata('error', 'You are not authorized to access this function');
            redirect('spservices/circleoffices/');
        }//End of if else
    }//End of submit()

}//End of Circleoffices
