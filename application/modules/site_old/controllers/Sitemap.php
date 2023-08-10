<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Sitemap extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('language');
        $this->load->model('settings_model');
    }
    public function index()
    {
        $this->load->model('cat_model');
        $this->load->model('dept_model');
        
        $data['categories'] = (array) $this->cat_model->get_all_categ($this->lang);
        $data['depts'] = $this->dept_model->get_all_depts($this->lang, false);
        $data['ac'] = $this->dept_model->get_all_depts($this->lang, true);
        $data['docs'] = $this->settings_model->get_settings('documents');
        $data['settings'] = $this->settings_model->get_settings('sitemap');

        $this->render_view_new('sitemap', $data);
    }
}