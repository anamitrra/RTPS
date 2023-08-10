<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Employment_model extends Mongo_model {

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

    public function get_row_last($filter = null) {
      $this->mongo_db->where($filter);
      $this->mongo_db->order_by('service_data.submission_date', 'DESC');
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
    public function add_param($where,$params){
        $this->mongo_db->set($params);
        $this->mongo_db->where($where);
        return $this->mongo_db->update($this->table);
    } 
    public function update_row($where,$params){
        $this->mongo_db->set($params);
        $this->mongo_db->where($where);
        return $this->mongo_db->update($this->table);
    }

    public function update_payment_status($appl_ref_no,$data){
        $this->mongo_db->set($data);
        $this->mongo_db->where(array("service_data.appl_ref_no"=> $appl_ref_no));
        return $this->mongo_db->update('sp_applications');
    }
    public function update_payment($rtps_trans_id,$data){
        $this->mongo_db->set($data);
        $this->mongo_db->where(array("form_data.rtps_trans_id"=> $rtps_trans_id));
        return $this->mongo_db->update($this->table);
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
    //renewal get reg no.
    public function get_previous_data($reg_no,$reg_date){
        $fillter=  array(
            '$and' => array(
              [
              'form_data.submission_date'=>$reg_date,
              'form_data.registration_no'=>$reg_no
              
              ]
            )
        );
        $this->mongo_db->select('*');
        $this->mongo_db->where($fillter);
        return $this->mongo_db->get('sp_applications');
    }

}
