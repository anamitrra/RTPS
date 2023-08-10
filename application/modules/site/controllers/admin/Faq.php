<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Faq extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/content_model');
    }

    public function index()
    {
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Services"));
        $data['faq']=$this->content_model->get_faq()->{'0'}->content;
        $this->load->view("admin/faq",$data);
        $this->load->view("admin/includes/footer");
    }

    public function add_faq_action()
    {

        $data = array(
            'en' => htmlspecialchars($_POST['faq-en']),
            'as' => htmlspecialchars($_POST['faq-as']),
            'bn' => htmlspecialchars($_POST['faq-bn']),
        );

        $this->content_model->update_faq($data);

        $this->session->set_flashdata('success', 'FAQ section updated');
        redirect('site/admin/faq');
    }
}