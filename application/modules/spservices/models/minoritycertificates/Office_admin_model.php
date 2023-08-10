<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Office_admin_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("office_admin");
    }//End of __construct()
    
   
    
    function getByUserName($id){
        $admin = $this->mongo_db->where(["name" => $id])->get($this->table);
        return $admin;
    }

    function getAdminDetails() {
       
        $adminDetails = $this->mongo_db->get($this->table);
     
        return $adminDetails;
    
        }


        public function updateAdmin($data,$id) {
        
            $option = array('upsert' => true);
    
            $this->mongo_db->where(["_id"=> new ObjectId($id)]);
            $this->mongo_db->set($data);
    
            return $this->mongo_db->update($this->table, $option);
    
              
    
        }


}
