<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Doctype_mapping_model extends Mongo_model
{

    public function __construct()
    {
        parent::__construct();
        $this->set_collection("doctype_service_mapping");
    } //End of __construct()

    public function get_row($filter = null)
    {
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->find_one($this->table);
        if (count((array)$res)) {
            return $res;
        } else {
            return false;
        } //End of if else        
    } //End of get_row()

    public function get_rows()
    {
        $res = $this->mongo_db->get($this->table);
        if (count((array)$res)) {
            return $res;
        } else {
            return false;
        } //End of if else        
    } //End of get_rows()

    // public function get_rows($filter = null){
    //     $this->mongo_db->order_by('service_id', 'ASC');
    //     $this->mongo_db->where($filter);
    //     $res = $this->mongo_db->get($this->table);
    //     if(count((array)$res)) {
    //         return $res;
    //     } else {
    //         return false;
    //     }//End of if else        
    // //End of get_rows()
    //     // $operations = [
    //     //     [ '$lookup' => [ 
    //     //         'from'         => 'locations',
    //     //         'localField'   => 'location_id',
    //     //         'foreignField' => '_id',
    //     //         'as'           => 'location_data'
    //     //     ]
    //     //     ],
    //     //     [ '$unwind' => '$location_data']
    //     //     // [
    //     //     //     '$project' => [
    //     //     //         '_id' => 1,
    //     //     //         'doctype' => 1,
    //     //     //         'service_id' => 1,
    //     //     //         'description' => 1
    //     //     //     ]
    //     //     // ]
    //     // ];
    //     // $service_list = $this->mongo_db->aggregate($this->table, $operations);
    //     // return $service_list;
    // }

    public function get_service()
    {
        $operations = [
            [
                '$group' => [
                    "_id" => [
                        "service_name" => '$service_data.service_name',
                        "service_id" => '$service_data.service_id'
                    ]
                ]
            ]
        ];
        $service_list = $this->mongo_db->aggregate('sp_applications', $operations);
        return $service_list;
    }
}
