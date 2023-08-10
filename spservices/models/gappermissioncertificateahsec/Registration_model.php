<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Registration_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("sp_applications");
    }//End of __construct()

    public function get_row($filter = null) {
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->find_one($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_row()

    public function getStates() {
        $query = $this->mongo_db->select(array('state_name_english','state_code','slc'))->order_by(array('state_name_english' => 'asc'))->get('states');         
        if(count((array)$query)) {
          return $query;
          } else {
              return false;
          }
    }
    public function getDistricts() {
        $query = $this->mongo_db->select(array('district_name','state_id','district_id','slc'))->order_by(array('district_name' => 'asc'))->get('districts_all_states');         
      if(count((array)$query)) {
        return $query;
        } else {
            return false;
        }
      }
}