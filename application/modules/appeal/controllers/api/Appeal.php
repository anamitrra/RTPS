<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Appeal extends frontend
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('applications_model');
        $this->load->model('appeal_application_model');
        $this->load->model('appeals_model');
        $this->load->model('appeal_process_model');
        $this->load->model('users_model');
        $this->load->model('official_details_model');
        $this->load->model('services_model');
        $this->load->model('location_model');
        $this->load->helper('role');
        $this->load->library('form_validation');
    }
    public function service_wise_appeal_count()
    {
      $data=$this->appeal_application_model->service_wise_appeal_count();  
      return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode(array(
        'status'=>true,
        'data'=>(array)$data
         )));
      //pre($data);
    }
}
