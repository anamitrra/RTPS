<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Track extends Frontend {

    public function __construct() {
        parent::__construct();
        $this->load->model('minoritycertificates/minoritycertificates_model');
        $this->load->model("office/application_model");
    }//End of __construct()

    public function status() {
        $appl_ref_no = $_GET['id'];
        $dbFilter = array(
            "service_data.appl_ref_no" => $appl_ref_no,
            "service_data.service_id" => "MCC",
        );
        $dbRow = $this->minoritycertificates_model->get_row($dbFilter);
        if($dbRow) {
            $this->load->view("includes/frontend/header", array("pageTitle" => "Application Track"));
            $this->load->view("minoritycertificates/track", array('dbrow' => $dbRow));
            $this->load->view("includes/frontend/footer");
        } else {
            $this->session->set_flashdata('pay_message','Records does not exist');
            redirect('spservices/minority-certificate');
        }//End of if else
    }//End of status()

}//End of Track
