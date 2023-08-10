<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Services extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('language');
    }
    public function index()
    {
        $this->load->model('dept_model');
        $this->load->model('settings_model');
        $data = array();
        $data['service_by_dept'] = $this->dept_model->services_by_dept($this->lang);
        $data['settings'] = $this->settings_model->get_settings('apply_online');
        $this->render_view('online_services', $data);
    }

    public function show()
    {
        $this->load->model('dept_model');
        $this->load->model('settings_model');
        $data = array();
        $data['service_by_dept'] = $this->dept_model->services_by_dept($this->lang);
        $data['settings'] = $this->settings_model->get_settings('apply_online');
        $this->render_view('online_services2', $data);
    }
    public function service_wise_data()
    {
        $this->load->model('settings_model');
        $data = array();
        $data['table_header'] = $this->settings_model->get_settings('service-wise-data');
      //  pre($data['settings']);
        $this->render_view('service_wise_data', $data);
        // https://dashboard.amtron.in/rtps/dashboard/api/services/status?_=1616150252010
        // https://dashboard.amtron.in/rtps/dashboard/api/office/status?_=1616150252011
    }
}
