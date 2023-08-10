<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Service_mapping extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('digilockermaster/doctype_mapping_model');
    }

    public function index($obj_id = null)
    {
        $data["dbrow"] = $this->doctype_mapping_model->get_row(array("_id" => new ObjectId($obj_id)));
        $data["mapping_data"] = $this->doctype_mapping_model->get_rows();
        // pre($data);
        $this->load->view('includes/frontend/header');
        $this->load->view('digilockermaster_view/service_mapping_view', $data);
        $this->load->view('includes/frontend/footer');
    }

    public function submit()
    {
        $id = $this->input->post('obj_id');
        $service_name = $this->input->post('service_name');
        $service_id = $this->input->post('service_id');
        $doctype = $this->input->post('doctype');
        $description = $this->input->post('description');

        $data = [
            "service_name" => $service_name,
            "service_id" => $service_id,
            "doctype" => $doctype,
            "description" => $description
        ];

        if (strlen($id)) {
            $this->mongo_db->where(array('_id' => new ObjectId($id)))->set($data)->update('doctype_service_mapping');
            $this->session->set_flashdata('success', 'Data updated successfully.');
            redirect('spservices/digilockermaster/service_mapping');
        } else {

            $this->mongo_db->insert('doctype_service_mapping', $data);
            $this->session->set_flashdata('success', 'Data saved successfully.');
            redirect('spservices/digilockermaster/service_mapping');
        }
    }

    public function delete($id)
    {
        if(strlen($id)){
            $this->mongo_db->where(array('_id' => new ObjectId($id)));
            $this->mongo_db->delete('doctype_service_mapping');
            $this->session->set_flashdata('success', 'Data deleted.');
            redirect('spservices/digilockermaster/service_mapping');
        }
        else{
            $this->session->set_flashdata('error', 'Something went wrong.');
            redirect('spservices/digilockermaster/service_mapping');
        }
    }
}
