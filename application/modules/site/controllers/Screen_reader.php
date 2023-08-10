<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Screen_reader extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('language');
        $this->load->model('settings_model');
    }
    public function index()
    {
       
        $data['screen']=$this->settings_model->screen()->{'0'};
        //pre($data);
        $this->render_view_new('screen',$data);
    }
}