<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Levels_model extends Mongo_model
{

    public function __construct()
    {
        parent::__construct();
        $this->set_collection("upms_levels");
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

    public function get_rows($filter = null)
    {
        $this->mongo_db->order_by('level_services.service_name', 'ASC');
        $this->mongo_db->order_by('level_no', 'ASC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if (count((array)$res)) {
            return $res;
        } else {
            return false;
        } //End of if else        
    } //End of get_rows()

    public function get_total_rows($filter = null)
    {
        $this->mongo_db->where($filter);
        return $this->mongo_db->count($this->table);
    } //ENd of get_total_rows()

    public function get_role_from_level($service_id)
    {
        $collection = $this->table;
        $operations = [
            [
                '$match' => [
                    'level_services.service_code' => $service_id
                ]
            ],
            [
                '$group' => [
                    "_id" => '$_id',
                    "role_name" => ['$addToSet' => '$level_roles.role_name'],
                    "role_code" => ['$addToSet' => '$level_roles.role_code']
                ]
            ],
            [
                '$sort' => [
                    'role_name' => 1
                ]
            ]
        ];

        return $this->mongo_db->aggregate($collection, $operations);
    }
}//End of Levels_model