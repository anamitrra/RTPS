<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Registrations_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("minoritycertificates");
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
    
    public function update_payment($rtps_trans_id,$data){
        $this->mongo_db->set($data);
        $this->mongo_db->where(array("rtps_trans_id"=> $rtps_trans_id));
        return $this->mongo_db->update($this->table);
    }
}
