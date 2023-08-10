<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Circles_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("circles");
    }//End of __construct()


    public function getCircleId($cir)
	{

		$data = $this->mongo_db->select(['circle_id'])->where(['circle_name'=>$cir])->get("circles");

        return $data;

	}
    
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
        $this->mongo_db->order_by('circle_name', 'ASC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()
}
