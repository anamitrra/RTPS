<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Time_slot_model extends Mongo_model
{

    public function __construct()
    {
        parent::__construct();
        $this->set_collection("time_slots_txn");
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
        $this->mongo_db->order_by('district_name', 'ASC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if (count((array)$res)) {
            return $res;
        } else {
            return false;
        } //End of if else        
    } //End of get_rows()

    public function get_slot_count($match)
    {
        $collection = 'emp_time_slots';
        $operations = [
            [
                '$match' => $match
            ],

            ['$group' => [
                "_id" => '$time_slot',
                "total" => ['$sum' => 1],
                "delivered" => [
                    '$sum' => [
                        '$cond' => [
                            [
                                '$eq' => ['$time_slot', "10:00 am - 11:00 am"],
                            ],
                            1, 0
                        ],
                    ],
                ],
                "rejected" => [
                    '$sum' => [
                        '$cond' => [
                            [
                                '$eq' => ['$time_slot', "11:00 am - 12:00 am"],
                            ],
                            1, 0
                        ],
                    ],
                ]
            ]]
        ];
        // pre($operations);
        return $this->mongo_db->aggregate($collection, $operations);
    }
}
