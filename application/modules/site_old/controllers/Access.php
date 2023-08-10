<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Access extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('language');
        $this->load->model('settings_model');
    }
    public function index()
    {
       
        $data['access']=$this->settings_model->access()->{'0'};
       // pre($data);
        $this->render_view_new('access',$data);
    }
}