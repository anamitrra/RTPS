<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Admin extends Rtps
{
    public function __construct()
    {
        parent::__construct();
    }

    public function view($grievanceRefNo){
        $this->load->model('public_grievance_model');
        $grievanceDetails = $this->public_grievance_model->first_where(['GrievanceReferenceNumber' => $grievanceRefNo]);
        $this->load->model('view_status_model');
        if(!empty($grievanceDetails)){
            $grievanceStatus = $this->view_status_model->first_where(['RegistrationNumber' => $grievanceDetails->registration_no]);
        }else{
            $grievanceStatus = [];
        }
        $data = [
            'grievanceStatus' => $grievanceStatus,
            'grievanceDetails' => $grievanceDetails
        ];
        $this->load->view('includes/header');
        $this->load->view('grievances/admin/view',$data);
        $this->load->view('includes/footer');
    }
}
