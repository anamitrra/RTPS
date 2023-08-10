<?php
defined('BASEPATH') or exit('No direct script access allowed');


class Commission extends Rtps
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->load->view('commission/index');
    }
}