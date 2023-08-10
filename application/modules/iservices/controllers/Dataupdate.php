<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Dataupdate extends Rtps
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('intermediator_model');
    $this->load->model('portals_model');
    $this->config->load('rtps_services');
    $this->load->library('AES');
    $this->encryption_key = $this->config->item("encryption_key");
  
  }

  public function index()
  {
  }

  public function noc_application($rtps_trans_id){
    if($rtps_trans_id){
        $application=$this->intermediator_model->get_row(array("rtps_trans_id"=>$rtps_trans_id));
        if($application){
            //  pre($item);
            $url='https://ilrms.nic.in/noc/index.php/usercontrol/rtpsfrmnopay';
            $curl=curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl,CURLOPT_URL,$url."?data=".$rtps_trans_id);
            $res=curl_exec($curl);

            curl_close($curl);
            if($res){
            $response=json_decode($res,true);
            if($response['status'] ==="success"){
            if(isset($response['data'])){
                $data_update=array();
                if($response['data']['app_ref_no']) $data_update['app_ref_no']=$response['data']['app_ref_no'];
                if($response['data']['status']) $data_update['status']=$response['data']['status'];
                if($response['data']['amount']) $data_update['amount']=$response['data']['amount'];
                if($response['data']['amountmut']) $data_update['amountmut']=$response['data']['amountmut'];
                if($response['data']['amountpart']) $data_update['amountpart']=$response['data']['amountpart'];
                if($response['data']['applicant_details']) $data_update['applicant_details']=$response['data']['applicant_details'];
           
                $res= $this->intermediator_model->add_param($rtps_trans_id,$data_update);
                if(  $res){
                    echo "Updated";
                }
            }
            }
        
            }
        }
    }
  }
  
}