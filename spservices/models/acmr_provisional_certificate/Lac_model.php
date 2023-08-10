<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lac_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("lac");
    }//End of __construct()

    public function getLacId($lac)
	{

		$data = $this->mongo_db->select(['lac_id'])->where(['lac_name'=>$lac])->get("lac");

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
        $this->mongo_db->order_by('lac_name', 'ASC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()
}


