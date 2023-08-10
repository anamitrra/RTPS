<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Gras_pp_dev_auth_config_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("gmda_egras_development_auth_payment_config_production");
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
        $this->mongo_db->order_by('created_at', 'DESC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()
    public function add_param($where,$params){
        $this->mongo_db->set($params);
        $this->mongo_db->where($where);
        return $this->mongo_db->update($this->table);
    } 
    public function update_row($where,$params){
        $this->mongo_db->set($params);
        $this->mongo_db->where($where);
        return $this->mongo_db->update($this->table);
    }
    public function get_sro_list($id){

        $fillter=  array(
            '$and' => array(
              [
              'parent_org_unit_code'=>"$id",
              "office_code"=>array("\$exists" => true,'$ne'=>''),
              "treasury_code"=>array("\$exists" => true,'$ne'=>'')
              ]
            )
               
    );

        $this->mongo_db->select('*');
        $this->mongo_db->where($fillter);
       return $this->mongo_db->get('sro_list');
    } 
    public function update_payment_status($appl_ref_no,$data){
        $this->mongo_db->set($data);
        $this->mongo_db->where(array("service_data.appl_ref_no"=> $appl_ref_no));
        return $this->mongo_db->update('sp_applications');
    }
     public function sro_dist_list(){

        // db.sro_list.aggregate([
        //     {$group: {_id: {"org_unit_name-2": "$org_unit_name-2", "parent_org_unit_code":"$parent_org_unit_code" } }},
            
        //     {$addFields: {"org_unit_name-2": "$_id.org_unit_name-2", "parent_org_unit_code": "$_id.parent_org_unit_code"}  },
        //     {$unset: "_id"},
            
        //     ])

            $operation=[
                ['$group'=>[
                    '_id'=>[
                        "org_unit_name-2"=> '$org_unit_name-2', 
                        "parent_org_unit_code"=>'$parent_org_unit_code'
                    ]
                    ]
                    
                    
                    ],
                    [
                        '$addFields'=>[
                            "org_unit_name_2"=> '$_id.org_unit_name-2', 
                            "parent_org_unit_code"=>'$_id.parent_org_unit_code'
                        ]
                        ],
                        [
                            '$unset'=>'_id'
                        ]

            ];
            $data = $this->mongo_db->aggregate("sro_list", $operation);
            return $arr = (array) $data;
       
    }
}
