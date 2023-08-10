<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Errors extends frontend
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
    }
    // welcome page or page before login

    /**
     * welcome
     *
     * @return void
     */
    public function notfound404()
    {
        $this->load->view('includes/frontend/header');
        $this->load->view('404');
        $this->load->view('includes/frontend/footer');
    }


}