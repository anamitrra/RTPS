<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ahsecolddata_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("ahsec_old_data");
    }//End of __construct()
    
    public function update_row($where,$params){
        $this->mongo_db->set($params);
        $this->mongo_db->where($where);
        return $this->mongo_db->update($this->table);
    }
}
