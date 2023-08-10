<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Errors extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings_model');   
    }

    public function notfound404()
    {
        $this->output->set_status_header(404);

        $data['settings'] = $this->settings_model->get_settings('404');
        $this->render_view_new('404', $data);
    }
}