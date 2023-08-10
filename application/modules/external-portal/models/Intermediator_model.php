<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Intermediator_model extends Mongo_model
{
 /**
  * __construct
  *
  * @return void
  */
  function __construct()
  {
    parent::__construct();
    $this->set_collection("intermediate_ids");
  }
  function is_exist_transaction_no($rtps_trans_id){

        $count = $this->mongo_db->mongo_like_count(array('rtps_trans_id' => $rtps_trans_id), 'intermediate_ids');
        if($count > 0){
          return true;
        }else {
          return false;
        }
  }
  public function update_payment_status($rtps_trans_id,$data){
    $this->mongo_db->set($data);
    $this->mongo_db->where(array("rtps_trans_id"=> $rtps_trans_id));
    return $this->mongo_db->update('intermediate_ids');
  }
  public function add_param($rtps_trans_id,$data){
    $this->mongo_db->set($data);
    $this->mongo_db->where(array("rtps_trans_id"=> $rtps_trans_id));
    return $this->mongo_db->update('intermediate_ids');
  }
  public function get_userid_by_application_ref($app_ref_no){
    $this->mongo_db->select(array("mobile","service_id"));
    $this->mongo_db->where(array("app_ref_no"=>$app_ref_no));
    $res=$this->mongo_db->find_one("intermediate_ids");
    return $res;
  }
  public function get_transaction_detail($rtps_trans_id){
    $this->mongo_db->select('*');
    $this->mongo_db->where(array("rtps_trans_id"=>$rtps_trans_id));
    $res=$this->mongo_db->find_one("intermediate_ids");
    return $res;
  }
  public function get_application_details($data){
    $this->mongo_db->select('*');
    $this->mongo_db->where($data);
    $res=$this->mongo_db->find_one("intermediate_ids");
    return $res;
  }


}
