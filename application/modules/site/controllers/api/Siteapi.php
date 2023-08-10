<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Siteapi extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->config('language');
        $this->load->model('settings_model');
        $this->load->model('site_model');
    }
  

    public function getServicesAndSettings()
    {
        $data['services_list'] = (array) $this->site_model->get_services_list('en');
        $data['settings'] = $this->settings_model->get_settings('index');
        $this->send_response(200, $data);
    }

    private function send_response($code = 200, $data)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => ($code == 200) ? true : false,
                'data' => $data
            ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
