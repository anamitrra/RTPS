<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contact extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/content_model');
    }

    public function index()
    {
        $this->load->view("admin/includes/header", array("pageTitle" => "ARTPS | Services"));
        $data['contact']=$this->content_model->get_contact()->{'0'}->content;
        $this->load->view("admin/contact",$data);
        $this->load->view("admin/includes/footer");
    }

    public function add_contact_action()
    {

        $data = array(
            'en' => htmlspecialchars($_POST['contact-en']),
            'as' => htmlspecialchars($_POST['contact-as']),
            'bn' => htmlspecialchars($_POST['contact-bn']),
        );

        $this->content_model->update_contact($data);

        $this->session->set_flashdata('success', 'Contact section updated');
        redirect('site/admin/contact');
    }

}