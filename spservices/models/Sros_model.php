<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sros_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("sro_list");
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
        $this->mongo_db->order_by('org_unit_name', 'ASC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()
    public function update_row($where,$params){
        $this->mongo_db->set($params);
        $this->mongo_db->where($where);
        return $this->mongo_db->update($this->table);
    }
    public function sro_dist_list() {
        $operation = [
            ['$group' => [
                    '_id' => [
                        "org_unit_name-2" => '$org_unit_name-2',
                        "parent_org_unit_code" => '$parent_org_unit_code'
                    ]
                ]
            ],
            [
                '$addFields' => [
                    "org_unit_name_2" => '$_id.org_unit_name-2',
                    "parent_org_unit_code" => '$_id.parent_org_unit_code'
                ]
            ],
            [
                '$unset' => '_id'
            ]
        ];
        $data = $this->mongo_db->aggregate("sro_list", $operation);
        return $arr = (array) $data;
    }//End of sro_dist_list()


    public function get_sro_list($id){
        $this->mongo_db->select('*');
        $this->mongo_db->where(array('parent_org_unit_code'=>"$id"));
       return $this->mongo_db->get('sro_list');
    } 

    public function get_sro_extra_data($id){
        $this->mongo_db->select('*');
        $this->mongo_db->where(array('org_unit_code'=>"$id"));
       return $this->mongo_db->get('sro_list');
    } 
    

}
