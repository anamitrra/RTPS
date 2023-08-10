<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Districts_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("districts");
    }//End of __construct()

    public function getDistId($dist)
	{

		$data = $this->mongo_db->select(['district_id'])->where(['district_name'=>$dist])->get("districts");

        return $data;

	}

    public function getDistName($distId)
	{

        
		$data = $this->mongo_db->select(['district_name'])->where(['district_id'=>$distId])->get('districts');

      
        return $data;



	}

    public function get_d_name($filter = null) {
        $this->mongo_db->where(['district_id'=>$filter]);
        $res = $this->mongo_db->get($this->table);
        
        return $res;
    }//End of get_row()

    
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
        $this->mongo_db->order_by('district_name', 'ASC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()
}
