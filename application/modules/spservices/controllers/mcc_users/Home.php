<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends Frontend {

    public function index()
	{
        $this->load->view('includes/mcc_users/admin/header');
		$this->load->view('mcc_users/landing_page');
        $this->load->view('includes/mcc_users/admin/footer');
	}

    public function unauthorize()
	{
        $this->load->view('includes/mcc_users/admin/header');
		$this->load->view('mcc_users/error_page');
        $this->load->view('includes/mcc_users/admin/footer');
	}
}