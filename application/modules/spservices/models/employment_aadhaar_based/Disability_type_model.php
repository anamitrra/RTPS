<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Disability_type_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("emp_disability_type");
    }
    
   public function get_rows() {
        $this->mongo_db->order_by('additional_disability_type', 'ASC');
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()
}