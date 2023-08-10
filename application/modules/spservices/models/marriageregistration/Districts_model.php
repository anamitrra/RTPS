<?php

use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Districts_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("districts_all_states");
    }//End of __construct()

    public function get_row($filter = null) {
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->find_one($this->table);
        if (count((array) $res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_row()

    public function get_rows($filter = null) {
        $this->mongo_db->order_by('district_name_english', 'ASC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if (count((array) $res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()
    
    public function get_distinct_results($filter) {
        $operation = [
            [
                '$match' => $filter
            ],
            ['$group' => [
                    '_id' => [
                        "district_name_english" => '$district_name_english',
                        "district_code" => '$district_code'
                    ]
                ]
            ],
            [
                '$addFields' => [
                    "district_name_english" => '$_id.district_name_english',
                    "district_code" => '$_id.district_code'
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
