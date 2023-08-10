
<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Highest_educational_level_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("emp_highest_educational_level");
    }
    
     public function get_rows() {
        $this->mongo_db->order_by('sl_no', 'ASC');
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()
}