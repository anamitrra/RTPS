<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Office_admin_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("minority_admin");
    }//End of __construct()
    
   
    // * Get admin by name as id
    function getByUserName($id){
        $admin = $this->mongo_db->where(["name" => $id])->get($this->table);
        return $admin;
    }


    // * Get state admin info
    function getAdminDetails() {
       
        $adminDetails = $this->mongo_db->get($this->table);
     
        return $adminDetails;
    
        }

}
