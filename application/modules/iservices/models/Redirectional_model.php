<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Redirectional_model extends Mongo_model
{
 /**
  * __construct
  *
  * @return void
  */
  function __construct()
  {
    parent::__construct();
    $this->set_collection("redirectional_payments");
  }
  function is_exist_transaction_no($rtps_trans_id){

        $count = $this->mongo_db->mongo_like_count(array('rtps_trans_id' => $rtps_trans_id), 'redirectional_payments');
        if($count > 0){
          return true;
        }else {
          return false;
        }
  }

  function is_exist_app_no($app_ref_no){

    $count = $this->mongo_db->mongo_like_count(array('service_data.appl_ref_no' => $app_ref_no), 'redirectional_payments');
    if($count > 0){
      return true;
    }else {
      return false;
    }
}
  public function get_row($fillter){
    $this->mongo_db->select("*");
    $this->mongo_db->where($fillter);
    $res=$this->mongo_db->find_one("redirectional_payments");
    return $res;
  }
  public function update_row($fillter,$data){
    $this->mongo_db->set($data);
    $this->mongo_db->where($fillter);
    return $this->mongo_db->update('redirectional_payments');
  }
  public function checkPaymentIntitateTime($dept_id)
  {
    $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
    $operations = array(
      [
        '$match' => [
          'department_id' => $dept_id
        ]
      ],

      [
        '$project' => [
          'item' => 1,
          'dateDifference' => array('$subtract' => [
            $current_time, '$createdDtm'
          ])
        ]
      ]

    );

    $data = $this->mongo_db->aggregate("pfc_payment_history", $operations);
    // pre($data);
    $arr = (array) $data;
    if (!empty($arr) && count($arr) > 0) {
      if(!empty($arr[0]->dateDifference)){
        $miliseconds=$arr[0]->dateDifference;
        $min=$miliseconds/(1000*60);
        return round($min);
      }else{
        return "N";
      }
    
    } else {
        return "N";
    }
  }



  public function get_application_details($data){
    $this->mongo_db->select('*');
    $this->mongo_db->where($data);
    $res=$this->mongo_db->find_one($this->table);
    return $res;
  }
  public function applications_filter($limit, $start,  $apply_by , $col, $dir)
    {
        $filter["service_data.applied_by"]= new ObjectId($apply_by);
        $this->set_collection($this->table);
    
        return $this->search_rows($limit, $start, $filter, $col, $dir);
    }

    public function total_app_rows($apply_by ){
        $filter["service_data.applied_by"]= new ObjectId($apply_by);
        $this->set_collection($this->table);
        return $this->tot_search_rows($filter);
      
    }


    public function application_search_rows($limit, $start, $keyword, $col, $dir,$apply_by )
    {
        $filter["service_data.applied_by"]= new ObjectId($apply_by);
        $temp = array(
            '$and' => [$filter],
            '$or'=>[
              ['service_data.service_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['service_data.rtps_trans_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['form_data.mobile_number' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['form_data.applicant_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']]
            ]
        );
        //print_r($temp);
        $this->set_collection($this->table);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }


}
