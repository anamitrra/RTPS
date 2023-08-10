<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Transactions extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('intermediator_model');
        $this->config->load('rtps_services');

    }
    public function index(){
      $user=$this->session->userdata();
    //  $mobile=$user['mobile'];
      $apply_by=new ObjectId($this->session->userdata('userId')->{'$id'});
      $data['intermediate_ids']=$this->intermediator_model->get_where(array('applied_by'=>$apply_by));
      $data['pageTitle']="Transactions";
      $this->load->view('includes/header');
      $this->load->view('admin/transactions',$data);
      $this->load->view('includes/footer');
    }



}
