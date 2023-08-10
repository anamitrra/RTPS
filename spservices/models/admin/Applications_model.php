<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Applications_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        //$this->set_collection("user_applications");
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
        //$this->mongo_db->order_by('process_no', 'ASC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()
    
    public function get_rows_count($filter = null) {
        $res = $this->mongo_db->mongo_like_count($filter, $this->table);
        return $res;        
    }//End of get_rows_count()
}//End of Applications_model