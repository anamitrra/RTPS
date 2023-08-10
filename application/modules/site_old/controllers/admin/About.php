<?php
defined('BASEPATH') or exit('No direct script access allowed');

class About extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/content_model');
    }

    public function index()
    {
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Services"));
        $data['about']=$this->content_model->get_about()->{'0'}->content;
        $this->load->view("admin/about",$data);
        $this->load->view("admin/includes/footer");
    }

    public function add_about_action()
    {

        $data = array(
            'en' => htmlspecialchars($_POST['about-en']),
            'as' => htmlspecialchars($_POST['about-as']),
            'bn' => htmlspecialchars($_POST['about-bn']),
        );

        $this->content_model->update_about($data);

        $this->session->set_flashdata('success', 'About section updated');
        redirect('site/admin/about');
    }

}