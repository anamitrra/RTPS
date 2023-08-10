<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
class First_appeal_store_api extends frontend
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
    public function servicewise_count_first_appeal()
    {
        
        $this->load->model("appeal_count_model");
        $services=$this->appeal_count_model->get_all_services();
     //  pre( $services);
        $master_array=[];
        foreach($services as $service){
            $service_id=$service->_id->{'$id'};
            $total_appeal=$this->appeal_count_model->first_appeal_total_count($service_id);
            $delivered_within_timeline = $this->appeal_count_model->services_delivered_within_30_days_timeline($service_id);
            $rejected_within_timeline = $this->appeal_count_model->services_rejected_within_30_days_timeline($service_id);
            $delivered_after_timeline = $this->appeal_count_model->services_delivered_after_30_days_timeline($service_id);
            $rejected_after_timeline = $this->appeal_count_model->services_rejected_after_30_days_timeline($service_id);

            $pending_within_timeline = $this->appeal_count_model->services_pending_withing_30_days_timeline($service_id);
            $pending_after_timeline = $this->appeal_count_model->services_pending_after_30_days_timeline($service_id);

            $data_to_save=array(
                "service_id"=>new ObjectId($service_id),
                "service_name"=> $service->service_name,
                "total_appeal"=>$total_appeal,
                "delivered_within_timeline"=>$delivered_within_timeline,
                "rejected_within_timeline"=>$rejected_within_timeline,
                "delivered_after_timeline"=>$delivered_after_timeline,
                "rejected_after_timeline"=>$rejected_after_timeline,
                "pending_within_timeline"=>$pending_within_timeline,
                "pending_after_timeline"=>$pending_after_timeline,
                "created_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000) 

            );

            array_push($master_array,(array)$data_to_save);
           
        }
     //   pre($master_array);
        $this->appeal_count_model->add_data($master_array,"first_appeal_servicewise_api_data");
        
       
    }
    public function districtwise_first_appeal_count(){
         
        $this->load->model("appeal_count_model");
        $services=$this->appeal_count_model->get_all_services();
     //  pre( $services);
        $master_array=[];
        $created_at=new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000) ;
        foreach($services as $service){
            $service_id=$service->_id->{'$id'};
            $total_appeal=$this->appeal_count_model->districtwise_appeal_total_count( $service_id);
            $delivered_within_timeline = $this->appeal_count_model->districtwise_delivered_within_30_days_timeline($service_id);
            $rejected_within_timeline = $this->appeal_count_model->districtwise_rejected_within_30_days_timeline($service_id);
            $delivered_after_timeline = $this->appeal_count_model->districtwise_delivered_after_30_days_timeline($service_id);
            $rejected_after_timeline = $this->appeal_count_model->districtwise_rejected_after_30_days_timeline($service_id);
            $pending_within_timeline = $this->appeal_count_model->districtwise_pending_withing_30_days_timeline($service_id);
            $pending_after_timeline = $this->appeal_count_model->districtwise_pending_after_30_days_timeline($service_id);
           
            if (!is_array($total_appeal)) $total_appeal = (array)$total_appeal;
            if (!is_array($delivered_within_timeline)) $delivered_within_timeline = (array)$delivered_within_timeline;
            if (!is_array($rejected_within_timeline)) $rejected_within_timeline = (array)$rejected_within_timeline;
            if (!is_array($delivered_after_timeline)) $delivered_after_timeline = (array)$delivered_after_timeline;
            if (!is_array($rejected_after_timeline)) $rejected_after_timeline = (array)$rejected_after_timeline;
            if (!is_array($pending_within_timeline)) $pending_within_timeline = (array)$pending_within_timeline;
            if (!is_array($pending_after_timeline)) $pending_after_timeline = (array)$pending_after_timeline;


            foreach ($total_appeal as $key => $t_val) { 
                $t_val->total_appeal=$t_val->count;
                foreach ($delivered_within_timeline as $key => $dwt_val) {//pre( $dwt_val);
                    if ($dwt_val->_id == $t_val->_id) {
                        $t_val->delivered_within_timeline = isset($dwt_val->count) ? $dwt_val->count : 0;
                    }
                }
               
                foreach ($rejected_within_timeline as $key => $rwt_val) {
                    if ($rwt_val->_id == $t_val->_id) {
                        $t_val->rejected_within_timeline = isset($rwt_val->count) ? $rwt_val->count : 0;
                    }
                } 
                foreach ($delivered_after_timeline as $key => $dat_val) {
                    if ($dat_val->_id == $t_val->_id) {
                        $t_val->delivered_after_timeline = isset($dat_val->count) ? $dat_val->count : 0;
                    }
                }
                 foreach ($rejected_after_timeline as $key => $rat_val) {
                    if ($rat_val->_id == $t_val->_id) {
                        $t_val->rejected_after_timeline = isset($rat_val->count) ? $rat_val->count : 0;
                    }
                } 
                foreach ($pending_within_timeline as $key => $pwt_val) {
                    if ($pwt_val->_id == $t_val->_id) {
                        $t_val->pending_within_timeline = isset($pwt_val->count) ? $pwt_val->count : 0;
                    }
                }
                foreach ($pending_after_timeline as $key => $pat_val) {
                    if ($pat_val->_id == $t_val->_id) {
                        $t_val->pending_after_timeline = isset($pat_val->count) ? $pat_val->count : 0;
                    }
                }
               
                if (!isset($t_val->pending_after_timeline)) {
                    $t_val->pending_after_timeline = 0;
                } 
                if (!isset($t_val->pending_within_timeline)) {
                    $t_val->pending_within_timeline = 0;
                }
                 if (!isset($t_val->rejected_after_timeline)) {
                    $t_val->rejected_after_timeline = 0;
                }
                 if (!isset($t_val->delivered_after_timeline)) {
                    $t_val->delivered_after_timeline = 0;
                } 
                
                if (!isset($t_val->delivered_within_timeline)) {
                    $t_val->delivered_within_timeline = 0;
                }
                if (!isset($t_val->rejected_within_timeline)) {
                    $t_val->rejected_within_timeline = 0;
                }
                // $t_val->created_at=$created_at;
               
            }
       //  pre($total_appeal);
            foreach($total_appeal as $data){
              // pre($data);
                array_push($master_array,array(
                    "service_id"=>$data->service_id,
                    "district_name"=>$data->district_name,
                    "service_name"=> $data->service_name,
                    "total_appeal"=>$data->total_appeal,
                    "delivered_within_timeline"=>$data->delivered_within_timeline,
                    "rejected_within_timeline"=>$data->rejected_within_timeline,
                    "delivered_after_timeline"=>$data->delivered_after_timeline,
                    "rejected_after_timeline"=>$data->rejected_after_timeline,
                    "pending_within_timeline"=>$data->pending_within_timeline,
                    "pending_after_timeline"=>$data->pending_after_timeline,
                    "created_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000) 
                ));
            }
            
           
        }
      
        $this->appeal_count_model->add_data($master_array,"first_appeal_districtwise_api_data");
        
    }


    public function servicewise_second_appeal_count()
    {
        
        $this->load->model("appeal_count_model");
        $services=$this->appeal_count_model->get_all_services();
     //  pre( $services);
        $master_array=[];
        foreach($services as $service){
            $service_id=$service->_id->{'$id'};
            $total_appeal=$this->appeal_count_model->first_appeal_total_count($service_id,2);
            $delivered_within_timeline = $this->appeal_count_model->services_delivered_within_30_days_timeline($service_id,2);
            $rejected_within_timeline = $this->appeal_count_model->services_rejected_within_30_days_timeline($service_id,2);
            $delivered_after_timeline = $this->appeal_count_model->services_delivered_after_30_days_timeline($service_id,2);
            $rejected_after_timeline = $this->appeal_count_model->services_rejected_after_30_days_timeline($service_id,2);

            $pending_within_timeline = $this->appeal_count_model->services_pending_withing_30_days_timeline($service_id,2);
            $pending_after_timeline = $this->appeal_count_model->services_pending_after_30_days_timeline($service_id,2);

            $data_to_save=array(
                "service_id"=>new ObjectId($service_id),
                "service_name"=> $service->service_name,
                "total_appeal"=>$total_appeal,
                "delivered_within_timeline"=>$delivered_within_timeline,
                "rejected_within_timeline"=>$rejected_within_timeline,
                "delivered_after_timeline"=>$delivered_after_timeline,
                "rejected_after_timeline"=>$rejected_after_timeline,
                "pending_within_timeline"=>$pending_within_timeline,
                "pending_after_timeline"=>$pending_after_timeline,
                "created_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000) 

            );

            array_push($master_array,(array)$data_to_save);
           
        }
     //   pre($master_array);
        $this->appeal_count_model->add_data($master_array,"second_appeal_servicewise_api_data");
        
       
    }
    public function districtwise_second_appeal_count(){
         
        $this->load->model("appeal_count_model");
        $services=$this->appeal_count_model->get_all_services();
     //  pre( $services);
        $master_array=[];
        $created_at=new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000) ;
        foreach($services as $service){
            $service_id=$service->_id->{'$id'};
            $total_appeal=$this->appeal_count_model->districtwise_appeal_total_count( $service_id,2);
            $delivered_within_timeline = $this->appeal_count_model->districtwise_delivered_within_30_days_timeline($service_id,2);
            $rejected_within_timeline = $this->appeal_count_model->districtwise_rejected_within_30_days_timeline($service_id,2);
            $delivered_after_timeline = $this->appeal_count_model->districtwise_delivered_after_30_days_timeline($service_id,2);
            $rejected_after_timeline = $this->appeal_count_model->districtwise_rejected_after_30_days_timeline($service_id,2);
            $pending_within_timeline = $this->appeal_count_model->districtwise_pending_withing_30_days_timeline($service_id,2);
            $pending_after_timeline = $this->appeal_count_model->districtwise_pending_after_30_days_timeline($service_id,2);
           
            if (!is_array($total_appeal)) $total_appeal = (array)$total_appeal;
            if (!is_array($delivered_within_timeline)) $delivered_within_timeline = (array)$delivered_within_timeline;
            if (!is_array($rejected_within_timeline)) $rejected_within_timeline = (array)$rejected_within_timeline;
            if (!is_array($delivered_after_timeline)) $delivered_after_timeline = (array)$delivered_after_timeline;
            if (!is_array($rejected_after_timeline)) $rejected_after_timeline = (array)$rejected_after_timeline;
            if (!is_array($pending_within_timeline)) $pending_within_timeline = (array)$pending_within_timeline;
            if (!is_array($pending_after_timeline)) $pending_after_timeline = (array)$pending_after_timeline;


            foreach ($total_appeal as $key => $t_val) { 
                $t_val->total_appeal=$t_val->count;
                foreach ($delivered_within_timeline as $key => $dwt_val) {//pre( $dwt_val);
                    if ($dwt_val->_id == $t_val->_id) {
                        $t_val->delivered_within_timeline = isset($dwt_val->count) ? $dwt_val->count : 0;
                    }
                }
               
                foreach ($rejected_within_timeline as $key => $rwt_val) {
                    if ($rwt_val->_id == $t_val->_id) {
                        $t_val->rejected_within_timeline = isset($rwt_val->count) ? $rwt_val->count : 0;
                    }
                } 
                foreach ($delivered_after_timeline as $key => $dat_val) {
                    if ($dat_val->_id == $t_val->_id) {
                        $t_val->delivered_after_timeline = isset($dat_val->count) ? $dat_val->count : 0;
                    }
                }
                 foreach ($rejected_after_timeline as $key => $rat_val) {
                    if ($rat_val->_id == $t_val->_id) {
                        $t_val->rejected_after_timeline = isset($rat_val->count) ? $rat_val->count : 0;
                    }
                } 
                foreach ($pending_within_timeline as $key => $pwt_val) {
                    if ($pwt_val->_id == $t_val->_id) {
                        $t_val->pending_within_timeline = isset($pwt_val->count) ? $pwt_val->count : 0;
                    }
                }
                foreach ($pending_after_timeline as $key => $pat_val) {
                    if ($pat_val->_id == $t_val->_id) {
                        $t_val->pending_after_timeline = isset($pat_val->count) ? $pat_val->count : 0;
                    }
                }
               
                if (!isset($t_val->pending_after_timeline)) {
                    $t_val->pending_after_timeline = 0;
                } 
                if (!isset($t_val->pending_within_timeline)) {
                    $t_val->pending_within_timeline = 0;
                }
                 if (!isset($t_val->rejected_after_timeline)) {
                    $t_val->rejected_after_timeline = 0;
                }
                 if (!isset($t_val->delivered_after_timeline)) {
                    $t_val->delivered_after_timeline = 0;
                } 
                
                if (!isset($t_val->delivered_within_timeline)) {
                    $t_val->delivered_within_timeline = 0;
                }
                if (!isset($t_val->rejected_within_timeline)) {
                    $t_val->rejected_within_timeline = 0;
                }
                // $t_val->created_at=$created_at;
               
            }
       //  pre($total_appeal);
            foreach($total_appeal as $data){
              // pre($data);
                array_push($master_array,array(
                    "service_id"=>$data->service_id,
                    "district_name"=>$data->district_name,
                    "service_name"=> $data->service_name,
                    "total_appeal"=>$data->total_appeal,
                    "delivered_within_timeline"=>$data->delivered_within_timeline,
                    "rejected_within_timeline"=>$data->rejected_within_timeline,
                    "delivered_after_timeline"=>$data->delivered_after_timeline,
                    "rejected_after_timeline"=>$data->rejected_after_timeline,
                    "pending_within_timeline"=>$data->pending_within_timeline,
                    "pending_after_timeline"=>$data->pending_after_timeline,
                    "created_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000) 
                ));
            }
            
           
        }
      
        $this->appeal_count_model->add_data($master_array,"second_appeal_districtwise_api_data");
        
    }
   
    
}
