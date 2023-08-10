<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Api_model extends Mongo_model
{
    public function total_services_group_by_service_gender_wise($service_id)
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => [
                    '$expr' => [
                        '$eq' => ['$initiated_data.base_service_id', $service_id]
                    ]

                ]
            ),
            array(
                '$bucket' => array(
                    'groupBy' => '$initiated_data.attribute_details.applicant_gender',
                    //'buckets'=>5,
                    'boundaries' => ['Female', 'Female / মহিলা', 'Male', 'Male / পুৰুষ', 'Others', 'Others / অন্যান্য', 'Z'],
                    'default' => 'ANOTAVAILABLE',
                    'output' => [
                        'total_received' => ['$sum' => 1],
                        'gender' => ['$first' => '$initiated_data.attribute_details.applicant_gender']
                        // 'docys'=>[
                        //     '$push'=>[
                        //         'service'=>'$initiated_data.service_name',
                        //         'service_id'=>'$initiated_data.base_service_id'
                        //     ],

                        // ]
                    ]
                )

            ),
            array(
                '$sort' => ['_id' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;
    }


    public function services_group_by_gender_wise_data($service_id, $status)
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => [
                    'initiated_data.base_service_id' => $service_id,
                    'initiated_data.appl_status' => $status,
                ]
            ),
            array(
                '$bucket' => array(
                    'groupBy' => '$initiated_data.attribute_details.applicant_gender',
                    //'buckets'=>5,
                    'boundaries' => ['Female', 'Female / মহিলা', 'Male', 'Male / পুৰুষ', 'Others', 'Others / অন্যান্য', 'Z'],
                    'default' => 'ANOTAVAILABLE',
                    'output' => [
                        'total_received' => ['$sum' => 1],
                        'gender' => ['$first' => '$initiated_data.attribute_details.applicant_gender']
                        // 'docys'=>[
                        //     '$push'=>[
                        //         'service'=>'$initiated_data.service_name',
                        //         'service_id'=>'$initiated_data.base_service_id'
                        //     ],

                        // ]
                    ]
                )

            ),
            array(
                '$sort' => ['_id' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;
    }




    public function total_services_group_by_service()
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'department_id' => ['$first' => '$initiated_data.department_id'],
                    'parent_department_id' => ['$first' => '$initiated_data.parent_department_id'],
                    'service_name' => ['$first' => '$initiated_data.service_name'],
                    'total_received' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['_id' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return $data;
    }
    public function rejected_in_time_all_services()
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    "initiated_data.appl_status" => 'R',
                    "initiated_data.rit" => 1,
                )
            ),

            // array(
            //     '$project' => [
            //         'first' => [
            //             '$arrayElemAt' => ['$execution_data', 0]
            //         ],
            //         'initiated_data' => 1
            //     ]
            // ),
            // array(
            //     '$lookup'  => array(
            //         'from'         => 'services',
            //         'localField'   => 'initiated_data.base_service_id',
            //         'foreignField' => 'service_id',
            //         'as'           => 'service'
            //     )
            // ),
            // array('$unwind' => '$service'),
            // array('$unwind' => '$execution_data'),
            // array(
            //     '$match' => array(
            //         '$and' => [
            //             ['$expr' => [
            //                 '$gt' => [
            //                     ['$toInt' => ['$service.service_timeline']],
            //                     ['$toInt' => [
            //                         '$divide' => [
            //                             ['$subtract' => [
            //                                 ['$toDate' => '$first.task_details.executed_time'], '$initiated_data.submission_date'
            //                             ]],
            //                             86400000
            //                         ]
            //                     ]]
            //                 ]
            //             ]],
            //             ['$expr' => [
            //                 '$eq' => [
            //                     '$initiated_data.appl_status', 'R'
            //                 ]
            //             ]],

            //         ]
            //     )
            // ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'service_name' => ['$first' => '$initiated_data.service_name'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['_id' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }
    public function total_services_rejected_group_by_service()
    {
        $collection = 'applications';
        $operations = array(
            // array(
            //     '$project' => [
            //         'first' => [
            //             '$arrayElemAt' => ['$execution_data', 0]
            //         ],
            //         'initiated_data' => 1
            //     ]
            // ),
            array(
                '$match' =>
                ['$expr' => [
                    '$eq' => [
                        '$initiated_data.appl_status', 'R'
                    ]
                ]],
            ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'count' => array('$sum' => 1),
                    'service_name' => ['$first' => '$initiated_data.service_name'],
                    'service_id' => ['$first' => '$initiated_data.base_service_id']
                )
            ),
            array(
                '$sort' => ['service_id' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }
    public function total_services_delivered_group_by_service()
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' =>
                ['$expr' => [
                    '$eq' => [
                        '$initiated_data.appl_status', 'D'
                    ]
                ]],
            ),
            //array('$unwind' => '$execution_data'),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'count' => array('$sum' => 1),
                    'service_name' => ['$first' => '$initiated_data.service_name'],
                    'service_id' => ['$first' => '$initiated_data.base_service_id']
                )
            ),
            array(
                '$sort' => ['service_id' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return $data;
    }

    // timely delivered services
    public function check_timeline_for_all_delivered_services()
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    "initiated_data.appl_status" => 'D',
                    "initiated_data.dit" => 1,
                )
            ),
            // array(
            //     '$project' => [
            //         'first' => [
            //             '$arrayElemAt' => ['$execution_data', 0]
            //         ],
            //         'initiated_data' => 1
            //     ]
            // ),
            // array(
            //     '$lookup'  => array(
            //         'from'         => 'services',
            //         'localField'   => 'initiated_data.base_service_id',
            //         'foreignField' => 'service_id',
            //         'as'           => 'service'
            //     )
            // ),
            // array('$unwind' => '$service'),
            // array(
            //     '$match' => array(
            //         '$and' => [
            //             ['$expr' => [
            //                 '$gt' => [
            //                     ['$toInt' => ['$service.service_timeline']],
            //                     ['$toInt' => [
            //                         '$divide' => [
            //                             ['$subtract' => [
            //                                 ['$toDate' => '$first.task_details.executed_time'], '$initiated_data.submission_date'
            //                             ]],
            //                             86400000
            //                         ]
            //                     ]]
            //                 ]
            //             ]],
            //             ['$expr' => [
            //                 '$eq' => [
            //                     '$initiated_data.appl_status', 'D'
            //                 ]
            //             ]],
            //         ]
            //     )
            // ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'count' => array('$sum' => 1),
                    'service_name' => ['$first' => '$initiated_data.service_name'],
                    'service_id' => ['$first' => '$initiated_data.base_service_id']
                )
            ),
            array(
                '$sort' => ['service_id' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);

        // pre($data);

        return $data;
    }
    public function pending_in_time_applications()
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    "initiated_data.appl_status" => array('$nin' => ["D", "R"]),
                    'initiated_data.applicant_query' => array('$eq' => false),
                    "initiated_data.pit" => 1,
                )
            ),
            // array(
            //     '$project' => [
            //         'first' => [
            //             '$arrayElemAt' => ['$execution_data', 0]
            //         ],
            //         'initiated_data' => 1
            //     ]
            // ),
            // array(
            //     '$lookup'  => array(
            //         'from'         => 'services',
            //         'localField'   => 'initiated_data.base_service_id',
            //         'foreignField' => 'service_id',
            //         'as'           => 'service'
            //     )
            // ),
            // array('$unwind' => '$service'),
            // array(
            //     '$match' => array(
            //         '$and' => [
            //             ['$expr' => [
            //                 '$lte' => [
            //                     '$$NOW', array(
            //                         '$add' => [
            //                             '$initiated_data.submission_date', array(
            //                                 '$multiply' => [
            //                                     array('$toInt' => '$service.service_timeline'),
            //                                     24 * 60 * 60000
            //                                 ]
            //                             )
            //                         ]
            //                     )
            //                 ]
            //             ]],
            //             ['$expr' => [
            //                 '$ne' => [
            //                     '$initiated_data.appl_status', 'R'
            //                 ]
            //             ]],
            //             ['$expr' => [
            //                 '$ne' => [
            //                     '$initiated_data.appl_status', 'D'
            //                 ]
            //             ]],

            //         ]
            //     )
            // ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'count' => array('$sum' => 1),
                    'service_name' => ['$first' => '$initiated_data.service_name'],
                    'service_id' => ['$first' => '$initiated_data.base_service_id']
                )
            ),
            array(
                '$sort' => ['service_id' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);

        // pre($data);

        return $data;
    }
    public function applicant()
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    "initiated_data.appl_status" => array('$nin' => ["D", "R"]),
                    "initiated_data.applicant_query" => true,
                )
            ),
            // array(
            //     '$project' => [
            //         'first' => [
            //             '$arrayElemAt' => ['$execution_data', 0]
            //         ],
            //         'initiated_data' => 1
            //     ]
            // ),
            // array(
            //     '$lookup'  => array(
            //         'from'         => 'services',
            //         'localField'   => 'initiated_data.base_service_id',
            //         'foreignField' => 'service_id',
            //         'as'           => 'service'
            //     )
            // ),
            // array('$unwind' => '$service'),
            // array(
            //     '$match' => array(
            //         '$and' => [
            //             ['$expr' => [
            //                 '$lte' => [
            //                     '$$NOW', array(
            //                         '$add' => [
            //                             '$initiated_data.submission_date', array(
            //                                 '$multiply' => [
            //                                     array('$toInt' => '$service.service_timeline'),
            //                                     24 * 60 * 60000
            //                                 ]
            //                             )
            //                         ]
            //                     )
            //                 ]
            //             ]],
            //             ['$expr' => [
            //                 '$ne' => [
            //                     '$initiated_data.appl_status', 'R'
            //                 ]
            //             ]],
            //             ['$expr' => [
            //                 '$ne' => [
            //                     '$initiated_data.appl_status', 'D'
            //                 ]
            //             ]],

            //         ]
            //     )
            // ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'count' => array('$sum' => 1),
                    'service_name' => ['$first' => '$initiated_data.service_name'],
                    'service_id' => ['$first' => '$initiated_data.base_service_id']
                )
            ),
            array(
                '$sort' => ['service_id' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);

        // pre($data);

        return $data;
    } 

    public function get_first_appeal_count_by_service($service_id)
    {

        $this->mongo_db->where(array('service_id' => new ObjectId($service_id)));
        return $this->mongo_db->order_by('_id', 'DESC')->find_one("first_appeal_servicewise_api_data");
    }
    public function get_first_appeal_count_by_district($service_id, $district)
    {
        $filter = array();
        if (!empty($service_id)) {
            $filter['service_id'] = new ObjectId($service_id);
        }
        if (!empty($district)) {
            $filter['district_name'] = $district;
        }
        $this->mongo_db->where($filter);
        return $this->mongo_db->order_by('_id', 'DESC')->find_one("first_appeal_districtwise_api_data");
    }
    public function get_second_appeal_count_by_district($service_id, $district)
    {
        $filter = array();
        if (!empty($service_id)) {
            $filter['service_id'] = new ObjectId($service_id);
        }
        if (!empty($district)) {
            $filter['district_name'] = $district;
        }
        $this->mongo_db->where($filter);
        return $this->mongo_db->order_by('_id', 'DESC')->find_one("second_appeal_districtwise_api_data");
    }
    public function get_second_appeal_count_by_service($service_id)
    {

        $this->mongo_db->where(array('service_id' => new ObjectId($service_id)));
        return $this->mongo_db->order_by('_id', 'DESC')->find_one("second_appeal_servicewise_api_data");
    }


    public function get_popular_services()
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'service_id' => ['$first' => '$initiated_data.base_service_id'],
                    'count' => array('$sum' => array('$toInt' => 1)),
                )
            ),
            array(
                '$sort' => ['count' => -1]
            ),
            array(
                '$limit' => 6
            ),
            array(
                '$project' => array('_id' => 0)
            ),
        );
        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }

    public function get_top_services_last_month()
    {
        $collection = 'applications';
        $fromDate = date('Y-m-d', strtotime('-30 days'));
        $toDate = date('Y-m-d');

        $operations = array(
            [
                '$match' => ['initiated_data.submission_date' => array(
                    '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($fromDate) * 1000),
                    '$lte' => new MongoDB\BSON\UTCDateTime(strtotime('+1 day', strtotime($toDate)) * 1000)
                )]
            ],
            [
                '$group' => [
                    '_id' => '$initiated_data.base_service_id',
                    'total' => ['$sum' => 1],
                    'service_name' => ['$first' => '$initiated_data.service_name'],
                ]
            ],
            [
                '$sort' => ['total' => -1, 'service_name' => 1]
            ],
            ['$limit' => 5],
            [
                '$addFields' => [
                    'service_name.en' => '$service_name',
                    'service_name.as' => '',
                    'service_name.bn' => '',

                ]
            ],


        );
        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }
}
