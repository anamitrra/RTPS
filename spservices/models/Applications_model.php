<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Applications_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("sp_applications");
    }//End of __construct()
    
    public function get_row($filter = null) {
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->find_one($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_row()
    
    public function get_rows($filter = null) {
        $this->mongo_db->order_by('created_at', 'DESC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()

    public function get_service_info($service_id){
        $this->mongo_db->where(array("service_id"=>$service_id));
        $res = $this->mongo_db->find_one("sp_services");
       return $res;
    }
    public function get_office_code($sro_code){
        $this->mongo_db->select(array("office_code","treasury_code"));
        $this->mongo_db->where(array("org_unit_code"=>$sro_code));
        $res = $this->mongo_db->find_one("sro_list");
       return $res;
    }

    public function checkPaymentIntitateTime($dept_id)
  {
    $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
    $operations = array(
      [
        '$match' => [
          'query_department_id' => $dept_id
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

  public function checkPFCPaymentIntitateTime($dept_id)
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

  public function checkPFCPaymentIntitateTimeNew($dept_id)
  {
    $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
    $operations = array(
      [
        '$match' => [
          'form_data.department_id' => $dept_id
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
  
}
