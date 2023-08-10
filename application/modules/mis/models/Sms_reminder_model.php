<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Sms_reminder_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
    }

    public function get_pending_applications_officewise()
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => [
                    'initiated_data.appl_status' => ['$nin' => ["D", "R"]]
                ]
            ),
            array(
                '$group' => [
                    '_id' => ['office_name' => '$initiated_data.submission_location', 'base_service_id' => '$initiated_data.base_service_id'],
                    'service' => ['$first' => '$initiated_data.service_name'],
                    'department_id' => ['$first' => '$initiated_data.department_id'],
                    'count' => ['$sum' => 1],
                ]
            ),
            array(
                '$group' => [
                    '_id' => '$_id.office_name',
                    'department_id' => ['$first' => '$department_id'],
                    'total_pending' => ['$sum' => '$count'],
                    'services' => ['$push' => '$$ROOT'],
                ]
            ),

        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        // pre($data);
        return (array)$data;
    }
    public function get_office($location_name)
    {
        // offices need to be unique
        $this->mongo_db->switch_db('appeal');
        $this->set_collection('locations');

        $filter['location_name'] = $location_name;
        return $this->mongo_db->where($filter)->find_one($this->table);
    }
    public function get_dps_from_offical_mappings($location_id)
    {
        $this->mongo_db->switch_db('appeal');
        $this->set_collection('official_details');

        $filter['location_id'] = new MongoDB\BSON\ObjectId("$location_id");
        $this->mongo_db->select([], ['_id']);
        $this->mongo_db->order_by(['created_at' => 'DESC']);
        // return (array) $this->get_all($filter);

        $data = $this->first_where($filter);
        // pre($data);
        return $data;
    }
    // Get service & dps info with official mappings
    public function get_service_dps_with_official_mappings($location_id)
    {
        $collection = 'official_details';
        $operations = array(
            array(
                '$match' => [
                    'location_id' => new MongoDB\BSON\ObjectId("$location_id")
                ]
            ),
            array(
                '$lookup' => [
                    'from' => 'services',
                    'localField' => 'service_id',
                    'foreignField' => '_id',
                    'as' => 'service_info',
                ]
            ),
            array(
                '$lookup' => [
                    'from' => 'users',
                    'localField' => 'dps_id',
                    'foreignField' => '_id',
                    'as' => 'user_info',
                ]
            ),
            array(
                '$unwind' => [
                    'path' => '$user_info',
                    'preserveNullAndEmptyArrays' => true,
                ]
            ),
            array(
                '$unwind' => [
                    'path' => '$service_info',
                    'preserveNullAndEmptyArrays' => true,
                ]
            ),
            array(
                '$sort' => [
                    'created_at' => -1
                ]
            ),

        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        // pre($data);
        return (array)$data;
    }

    public function get_users_from_dps_id($dps_id)
    {
        $this->mongo_db->switch_db('appeal');
        $this->set_collection('users');

        $filter['_id'] = $dps_id;
        return $this->mongo_db->where($filter)->find_one($this->table);
    }
    public function get_dps_from_dps_id($dps_id = array())
    {
        // pre($dps_id);
        $this->mongo_db->switch_db('appeal');
        $collection = 'users';
        $operations = array(
            array(
                '$match' => [
                    '_id' => ['$in' => $dps_id],
                    'designation' => 'DPS',
                    'is_verified' => true,
                ]
            ),
            array(
                '$addFields' => [
                    'date_arr' => [
                        '$split' => ['$createdDtm', " "]
                    ]
                ]
            ),
            array(
                '$addFields' => [
                    'date_str' => [
                        '$concat' => [['$arrayElemAt' => ['$date_arr', 0]], "T", ['$arrayElemAt' => ['$date_arr', 1]]]
                    ]
                ]
            ),
            array(
                '$addFields' => [
                    'mongo_date' => [
                        '$dateFromString' => [
                            'dateString' => '$date_str',
                            'format' => '%Y-%m-%dT%H:%M:%S',
                            'timezone' => 'Asia/Calcutta'
                        ]
                    ]
                ]
            ),
            array(
                '$sort' => [
                    'mongo_date' => -1
                ]
            ),
            array(
                '$limit' => 1
            ),

        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        return (array)$data;
    }


    public function get_pending_applications_exp_3_officewise()
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => [
                    'initiated_data.pit' => 1
                ]
            ),
            array(
                '$addFields' => [
                    'initiated_data.exp_del_date' => [
                        '$add' => ['$initiated_data.submission_date', [
                            '$multiply' => [['$toInt' => '$initiated_data.service_timeline'], 24 * 60 * 60000]
                        ]]
                    ]
                ]
            ),
            array(
                '$addFields' => [
                    'initiated_data.date_diff' => [
                        '$toInt' => ['$divide' => [
                            ['$subtract' => ['$initiated_data.exp_del_date', '$$NOW']], 24 * 60 * 60000
                        ]]
                    ]
                ]
            ),
            array(
                '$match' => [
                    'initiated_data.date_diff' => 3
                ]
            ),
            array(
                '$group' => [
                    '_id' => ['office_name' => '$initiated_data.submission_location', 'base_service_id' => '$initiated_data.base_service_id'],
                    'service' => ['$first' => '$initiated_data.service_name'],
                    'department_id' => ['$first' => '$initiated_data.department_id'],
                    'count' => ['$sum' => 1],
                ]
            ),
            array(
                '$group' => [
                    '_id' => '$_id.office_name',
                    'department_id' => ['$first' => '$department_id'],
                    'total_pending' => ['$sum' => '$count'],
                    'services' => ['$push' => '$$ROOT'],
                ]
            ),

        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        return (array)$data;
    }
}
