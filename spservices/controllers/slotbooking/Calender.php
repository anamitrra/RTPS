<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Calender extends Rtps
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('employment_nonaadhaar/employment_model');

        $this->load->config('spconfig');
        $this->load->helper("log");
        $this->load->helper("role");
        $this->load->library('user_agent');
        if ($this->session->role) {
            $this->slug = $this->session->userdata('role') === "csc" ? "CSC" : $this->session->userdata('role')->slug;
        } else {
            $this->slug = "user";
        } //End of if else
    } //End of __construct()

    public function index()
    {

        // $this->load->view('employment_nonaadhaar/test');

    }

    public function holidays(){

    }

    public function save_holidays(){
        
    }
}