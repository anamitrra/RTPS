<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Dashboard extends Rtps
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->load->view('includes/header');
        $this->load->view('dashboard/index');
        $this->load->view('includes/footer');
    }
}