<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Access extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/content_model');
    }

    public function index()
    {
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Services"));
        $data['access']=$this->content_model->get_access()->{'0'}->content;
        $this->load->view("admin/access",$data);
        $this->load->view("admin/includes/footer");
    }

    public function add_access_action()
    {

        $data = array(
            'en' => htmlspecialchars($_POST['about-en']),
            'as' => htmlspecialchars($_POST['about-as']),
            'bn' => htmlspecialchars($_POST['about-bn']),
        );

        $this->content_model->update_access($data);

        $this->session->set_flashdata('success', 'Accessibility section updated');
        redirect('site/admin/access');
    }

}