<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Trackstatus_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
    }//End of __construct()
    
    public function get_row_by_id($colName=null, $id=null) {
        if(preg_match('/^[0-9a-f]{24}$/i', $id) === 1) {
            $this->mongo_db->where("_id", new MongoDB\BSON\ObjectId($id));
            $res = $this->mongo_db->find_one($colName);
            if(count((array)$res)) {
                return $res;
            } else {
                return false;
            }//End of if else
        } else {
            return false;
        }//End of if else
    }//End of get_row_by_id()
    
    public function get_row($colName=null, $filter = null) {
        //$this->mongo_db->order_by('created_at', 'DESC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->find_one($colName);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_row()
    
    public function get_rows($colName=null, $filter = null) {
        //$this->mongo_db->order_by('created_at', 'DESC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($colName);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()
}//End of Trackstatus_model
