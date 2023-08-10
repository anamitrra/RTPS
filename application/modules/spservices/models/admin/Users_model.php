<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends Mongo_model
{

    public function __construct()
    {
        parent::__construct();
        $this->set_collection("upms_users");
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
        $this->mongo_db->order_by('created_at', 'DESC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if (count((array)$res)) {
            return $res;
        } else {
            return false;
        } //End of if else        
    } //End of get_rows()


    public function get_counts($filter = null)
    {
        $collection = $this->table;
        if ($filter) {
            $operations = [
                ['$match' => $filter],
                ['$group' => [
                    "_id" => '_id',
                    "total" => ['$sum' => 1],
                    "active" => [
                        '$sum' => [
                            '$cond' => [
                                [
                                    '$eq' => ['$status', 1],
                                ],
                                1, 0
                            ],
                        ],
                    ],
                    "inactive" => [
                        '$sum' => [
                            '$cond' => [
                                [
                                    '$eq' => ['$status', 0],
                                ],
                                1, 0
                            ],
                        ],
                    ]
                ]]
            ];
        } else {
            $operations = [
                ['$group' => [
                    "_id" => '_id',
                    "total" => ['$sum' => 1],
                    "active" => [
                        '$sum' => [
                            '$cond' => [
                                [
                                    '$eq' => ['$status', 1],
                                ],
                                1, 0
                            ],
                        ],
                    ],
                    "inactive" => [
                        '$sum' => [
                            '$cond' => [
                                [
                                    '$eq' => ['$status', 0],
                                ],
                                1, 0
                            ],
                        ],
                    ]
                ]]
            ];
        }
        return $this->mongo_db->aggregate($collection, $operations);
    }
}
