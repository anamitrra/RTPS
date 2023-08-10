<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Handler extends Rtps
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('intermediator_model');
    $this->load->model('portals_model');
    $this->config->load('rtps_services');
    $this->load->library('AES');
    $this->encryption_key = $this->config->item("encryption_key");
    $this->load->helper("minoritycertificate");
    $this->load->helper("appstatus");
  }

  public function index()
  {
   
      $postdata=$_POST;
      if(!empty($postdata) && !empty($postdata['data'])){
        $aes = new AES($postdata['data'],$this->encryption_key);
        $dec=$aes->decrypt();
        $data=json_decode($dec,true);
        if(!empty($data) && !empty($data['service_id'])){
                $service_detail=$this->portals_model->get_row(array("service_id"=>$data['service_id']));
                if(!empty( $service_detail) && !empty($service_detail->rtps_service_url)){
                        //need to update base on service RTPS or external
                        if($service_detail->portal_no == 10){
                            $data_to_save=array(

                            );
                        }
                }
        }
      }
      
  }
  private function store_data(){

  }
  private function create_session(){

  }
 
}