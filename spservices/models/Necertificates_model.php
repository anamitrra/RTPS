<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Necertificates_model extends Mongo_model {

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
    public function update_row($where,$params){
        $this->mongo_db->set($params);
        $this->mongo_db->where($where);
        return $this->mongo_db->update($this->table);
    }
    public function checkPaymentIntitateTime($dept_id) {
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
            [
                '$match' => [
                    'transaction_id' => $dept_id
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
            if (!empty($arr[0]->dateDifference)) {
                $miliseconds = $arr[0]->dateDifference;
                $min = $miliseconds / (1000 * 60);
                return round($min);
            } else {
                return "N";
            }
        } else {
            return "N";
        }
    }//End of checkPaymentIntitateTime()

}
