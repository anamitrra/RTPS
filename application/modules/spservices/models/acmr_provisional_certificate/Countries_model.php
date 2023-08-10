<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Countries_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("countries");
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
        $this->mongo_db->order_by('country_name', 'ASC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table); 
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()
}