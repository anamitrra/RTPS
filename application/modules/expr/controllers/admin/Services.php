<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Services extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/roles_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $access_list = $this->roles_model->get_all_access_list();
        $this->load->view('includes/header', array('pageTitle' => 'Services'));
        $this->load->view('admin/services/list');
        $this->load->view('includes/footer');
    }




}
