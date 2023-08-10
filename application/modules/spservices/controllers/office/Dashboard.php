<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("office/application_model");
        if ($this->session->userdata()) {
            if ($this->session->userdata('isAdmin') === TRUE) {
            } else {
              $this->session->sess_destroy();
              redirect('spservices/mcc/user-login');
            }
          } else {
            redirect('spservices/mcc/user-login');
          }
    }

    public function index()
    {
        $service_id = 'MCC';
        $data['counts'] = (array)$this->application_model->get_application_count($service_id);
        $data['pending'] = (array)$this->application_model->get_pending_count($service_id);
        // pre($data);
        $this->load->view("includes/office_includes/header", array("pageTitle" => "Dashboard"));
        $this->load->view("spservices/office/dashboard", $data);
        $this->load->view("includes/office_includes/footer");
    }
}
