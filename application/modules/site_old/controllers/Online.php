<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Online extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('language');
    }
    public function index($active = '')
    {
        $this->load->model('dept_model');
        $this->load->model('settings_model');

        $data['active'] =
            ($this->dept_model->tot_search_rows(['department_short_name' => trim($active)])) ? trim($active) : '';

        $data['service_by_dept'] = (array) $this->dept_model->services_by_dept($this->lang);
        $data['settings'] = $this->settings_model->get_settings('apply_online');

        $this->render_view_new('online_services', $data);
    }

    public function service_wise_data()
    {
        $this->load->model('settings_model');
        $data = array();
        $data['table_header'] = $this->settings_model->get_settings('service-wise-data');
        $this->render_view_new('service_wise_data', $data);
    }

    public function basundhara()
    {
        $this->load->model('settings_model');
        $this->load->model('site_model');

        $data['settings'] = $this->settings_model->get_settings('basundhara');
        $data['services'] = (array) $this->site_model->get_basundhara_services($this->lang);
        
        $this->render_view_new('basundhara_services.php', $data);
    }

    public function utility()
    {
        $this->load->model('settings_model');
        $this->load->model('site_model');
        
        $data['settings'] = $this->settings_model->get_settings('utility');
        $data['services'] = (array) $this->site_model->get_utility_services($this->lang);

        //var_dump($data['settings']);die;

        $this->render_view_new('utility.php', $data);
    }
}
