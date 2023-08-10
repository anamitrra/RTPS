<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Examination_passed_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("emp_examination_passed");
    }

    public function get_rows($filter = null) {
        $this->mongo_db->order_by('sl_no', 'ASC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()
}