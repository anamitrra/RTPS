<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Office_user_desig_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("office_user_desig");
    }//End of __construct()

    function getAllUserDesignation() {
        
        $users = $this->mongo_db->order_by('designation_name', 'ASC')->get($this->table);
     
        return $users;
    
        }

    public function getUserRoleId($role_name)
	{

		$data = $this->mongo_db->select(['_id'])->where(['role_name'=>$role_name])->get("office_user_roles");
        return $data;

	}

    public function getUserRoleSlug($role_name)
	{

		$data = $this->mongo_db->select(['slug'])->where(['role_name'=>$role_name])->get("office_user_roles");
        return $data;

	}

    public function getUniqueUserId($role_name,$last_digit)
	{

		$data = $last_digit.$role_name;
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
