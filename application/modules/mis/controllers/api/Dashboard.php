<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Dashboard extends RTPS
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * getServiceWiseData
     * @param mixed $service_id
     * 
     * @return json
     */
    public function getServiceWiseData($service_id)
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type' => 3));
        if ($result) {
            $val = array();
            $val['status'] = FALSE;
            foreach ($result->data as $key => $value) {
                if ($service_id == $value->_id) {
                    $val["data"] = $value;
                    $val['status'] = TRUE;
                }
            }
            //pre($val);
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($val));
        }
    }
    public function getOfficeWiseData()
    {

        $office_name = $this->input->post('office_name');
        $service_id = $this->input->post('service_id');

        $department_id = $this->input->post('department_id');
        $decoded_office_name = base64_decode($office_name);
        if ($service_id == "" || empty($service_id) || $service_id == NULL) {
          
            $this->load->model("stored_api_model");
            $result = $this->stored_api_model->last_where(array('type' => 4));
            if ($result) {
               
                $val = array();
                $val['status'] = FALSE;
                foreach ($result->data as $key => $value) {
                   // die($value->_id.'*'.$decoded_office_name);
                    if (trim($decoded_office_name) == trim($value->_id)) {
                        
                        $val['status'] = TRUE;
                        $val["data"] = $value;
                    }
                }
            }
        } else {

            $this->load->model("servicewise_application_count_model");
            $result = $this->servicewise_application_count_model->get_officewise_service_data($service_id, $department_id, $decoded_office_name);
            if($result){
                $val['status'] = TRUE;
                $val["data"] = $result;
            }else{
                $val['status'] = FALSE;
                $val["data"] = "";
            }
            
        }
        // pre($val);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($val));
    }
    public function getDepartmentWiseData($department_id)
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type' => 8));
        if ($result) {
            $val = array();
            $val['status'] = FALSE;
            foreach ($result->data as $key => $value) {
                if (trim($department_id) == trim($value->_id)) {
                    $val["data"] = $value;
                    $val['status'] = TRUE;
                }
            }
            // pre($val);
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($val));
        }
    }
    public function getOfficesByDepartmentID($department_id)
    {
        $this->load->model("dashboard_model");
        $result = $this->dashboard_model->getOfficesByDepartmentID($department_id);
       // die($department_id);
        if ($result) {
            $response['status'] = TRUE;
            $response['data'] = $result;
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($response));
        }
    }
    public function insertOfficesIntoDatabase()
    {
        $this->load->model("stored_api_model");
        $this->load->model("officewise_application_count_model");
        $insert_array=[];
        $offices=$this->officewise_application_count_model->officewise_application_count();
        foreach((array)$offices as $key=>$value){
            $temp=[];
            $temp['office_name']=$value->submission_location;
            $temp['department_id']=$value->department_id;
            array_push($insert_array,$temp);
        }
        $this->mongo_db->batch_insert('offices', $insert_array);
        pre($insert_array);
    }
}
