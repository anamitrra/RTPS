<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Api extends frontend
{
    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {        
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type'=>1));
        if($result){
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($result->data);
        }
    }
    public function gender_wise_application_count()
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type'=>2));
        if($result){
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($result->data);
        }
    }
    public function all_service_status()
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type'=>3));
        if($result){
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($result->data);
        }
    }
    public function officewise()
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type'=>4));
        if($result){
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($result->data);
        }
    }
    public function officewise_appilcation_for_ovca()
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type'=>5));
        if($result){
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($result->data);
        }
  
    }
    public function officewise_appilcation_for_ovca_v2()
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type'=>6));
        if($result){
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($result->data);
        }
  
    }
    public function officewise_appilcation_for_ovca_total()
    {
        $this->load->model("stored_api_model");
        $result = $this->stored_api_model->last_where(array('type'=>7));
        if($result){
            return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output($result->data);
        }
  
    }
    
}
