<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class SpservicesModel extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("sp_services");
    }//End of __construct()
    
    public function get_all_service(){
        return $res = $this->mongo_db->get($this->table);
    }

    // get_service_data
    public function get_service_data($id){
        $objid = base64_decode($id);
        $this->mongo_db->select('*');
        $this->mongo_db->where(array('_id'=> new ObjectId($objid)));
       return $this->mongo_db->get($this->table);
    } 
}