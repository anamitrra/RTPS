<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class States_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("states");
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
    
    public function get_rows($filter = null) {
        $this->mongo_db->order_by('state_name_english', 'ASC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()
    
    public function get_distinct_results() {
        $operation = [
            ['$group' => [
                    '_id' => [
                        "state_name_english" => '$state_name_english',
                        "slc" => '$slc'
                    ]
                ]
            ],
            [
                '$addFields' => [
                    "state_name_english" => '$_id.state_name_english',
                    "slc" => '$_id.slc'
                ]
            ],
            [
                '$unset' => '_id'
            ]
        ];
        $res = $this->mongo_db->aggregate($this->table, $operation);
        if (count((array) $res)) {
            return $res;
        } else {
            return false;
        }//End of if else 
    }//End of get_distinct_results()
}
