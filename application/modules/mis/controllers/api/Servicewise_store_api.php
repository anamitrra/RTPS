<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
class Servicewise_store_api extends frontend
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Generate
     * @return Void
     */
    public function generate()
    {
        
    }
    public function servicewise_count_group_by_office()
    {
        
        $this->load->model("servicewise_application_count_model");
        $services=$this->servicewise_application_count_model->get_all_services();
        // pre($service->service_id);
        $master_array=[];
        foreach($services as $service){
            $received = $this->servicewise_application_count_model->officewise_application_count($service->service_id);
            $rejected = $this->servicewise_application_count_model->total_services_rejected_group_by_service($service->service_id);
            $delivered = $this->servicewise_application_count_model->total_services_delivered_group_by_service($service->service_id);
            $pit = $this->servicewise_application_count_model->check_timeline_for_all_services_pending_in_time_group_by($service->service_id);
            $timely_delivered = $this->servicewise_application_count_model->check_timeline_for_all_services($service->service_id);
            if (!is_array($received)) $received = (array)$received;
            if (!is_array($rejected)) $rejected = (array)$rejected;
            if (!is_array($pit)) $pit = (array)$pit;
            if (!is_array($delivered)) $delivered = (array)$delivered;
            if (!is_array($timely_delivered)) $timely_delivered = (array)$timely_delivered;
            foreach ($received as $key => $r_val) {
                foreach ($delivered as $key => $d_val) {
                    if ($d_val->_id == $r_val->_id) {
                        $r_val->delivered = isset($d_val->count) ? $d_val->count : 0;
                    }
                }
                foreach ($rejected as $key => $re_val) {
                    if ($re_val->_id == $r_val->_id) {
                        $r_val->rejected = isset($re_val->count) ? $re_val->count : 0;
                    }
                }
                foreach ($timely_delivered as $key => $t_val) {
                    if ($t_val->_id == $r_val->_id) {
                        $r_val->timely_delivered = isset($t_val->count) ? $t_val->count : 0;
                    }
                }
                foreach ($pit as $key => $pit_val) {
                    if ($pit_val->_id == $r_val->_id) {
                        $r_val->pit = isset($pit_val->count) ? $pit_val->count : 0;
                    }
                }
                if (!isset($r_val->rejected)) {
                    $r_val->rejected = 0;
                }
                if (!isset($r_val->delivered)) {
                    $r_val->delivered = 0;
                }
                if (!isset($r_val->timely_delivered)) {
                    $r_val->timely_delivered = 0;
                }
                if (!isset($r_val->pit)) {
                    $r_val->pit = 0;
                }
                $r_val->pending = $r_val->total_received - ($r_val->delivered + $r_val->rejected);
            }
            foreach($received as $data){
                array_push($master_array,(array)$data);
            }
            
        }
        // pre($master_array);
        $this->mongo_db->batch_insert('services_api_data', $master_array);
        // $this->stored_api_model->insert(array(
        //     'created_at' => new UTCDateTime(strtotime(date('d-m-Y h:i A')) * 1000),
        //     'type' => 4,
        //     'data' => $received
        // ));
       
    }
    public function get_officewise_service_data()
    {
        $service_id=$this->input->post("service_id");
        $department_id=$this->input->post("department_id");
        $office_name=$this->input->post("officename");
        $this->load->model("servicewise_application_count_model");
        $result=$this->servicewise_application_count_model->get_officewise_service_data($service_id,$department_id,$office_name);
        pre($result); 

    }
    
}
