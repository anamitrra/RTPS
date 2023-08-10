<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class District extends RTPS
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getOffices($district_id)
    {
        $this->load->model("offices_model");
        $data = (array)$this->offices_model->getOfficesByDistrict($district_id);
        // pre($data);
        if (count($data) > 0) {
            $res['status'] = TRUE;
            $res['data'] = $data;
        } else {
            $res['status'] = FALSE;
        }
        // pre($val);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($res));
    }
    public function getDistrictData()
    {
        $this->load->model("offices_model");
        $office_name = $this->input->post('office_name');
        $district_id = $this->input->post('district_id');
        $department_id = $this->input->post('department_id');
        $decoded_office_name = base64_decode($office_name);
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type' => 4));
        if ($office_name == "" || empty($office_name) || $office_name == NULL) {            
            $offices = (array)$this->offices_model->getOfficesByDistrict($district_id);
            $total_received = 0;
            $rejected = 0;
            $delivered = 0;
            $timely_delivered = 0;
            $pending = 0;
            $pit = 0;
            if ($result) {
                foreach ($offices as $office) {
                    $val = array();
                    $val['status'] = TRUE;
                    foreach ($result->data as $key => $value) {
                        if (trim($office->office_name) == trim($value->_id)) {
                            $total_received += $value->total_received;
                            $rejected += $value->rejected;
                            $delivered += $value->delivered;
                            $timely_delivered += $value->timely_delivered;
                            $pending += $value->pending;
                            $pit += $value->pit;
                        }
                    }
                }
                $val['data']=[
                    "total_received"=> $total_received,
                    "rejected"=> $rejected,
                    "delivered"=> $delivered,
                    "timely_delivered"=> $timely_delivered,
                    "pending"=> $pending,
                    "pit"=> $pit
                ];
                
            } else {
                $val['status'] = FALSE;
            }
        }else{
            if ($result) {
                $val = array();
                $val['status'] = FALSE;
                foreach ($result->data as $key => $value) {
                    if (trim($decoded_office_name) == trim($value->_id)) {
                        $val['status'] = TRUE;
                        $val["data"] = $value;
                    }
                }
            }
        }
        // pre($result);
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($val));
    }
}
