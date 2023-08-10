<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Feedback extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/feedback_model');
    }

    public function index()
    {
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Services"));
      // $data['feed']=$this->feedback_model->get_feed();
      // pre( $data['feed']);
        $this->load->view("admin/feedback");
        $this->load->view("admin/includes/footer");
    }

    public function all_feed_api()
    {
        $data = $this->feedback_model->get_feed();
        
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }
}