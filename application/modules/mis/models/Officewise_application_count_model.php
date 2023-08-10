<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Officewise_application_count_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->set_collection("applications");
    }
    function  officewise_application_count()
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$group' => array(
                    '_id' => '$initiated_data.submission_location',
                    'submission_location' => ['$first' => '$initiated_data.submission_location'],
                    'department_name' => ['$first' => '$initiated_data.department_name'],
                    'department_id' => ['$first' => '$initiated_data.department_id'],
                    'district' => ['$first' => '$initiated_data.district'],
                    'total_received' => array('$sum' => 1),
                    
                )
            ),
            array(
                '$sort' => ['submission_location' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return $data;
    }

    public function total_services_rejected_group_by_service()    // gruop by office, actually
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
                    '_id' => '$initiated_data.submission_location',
                    'submission_location' => ['$first' => '$initiated_data.submission_location'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['submission_location' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }
    public function total_services_delivered_group_by_service()
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
                        '$initiated_data.appl_status', 'D'
                    ]
                ]],
            ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.submission_location',
                    'submission_location' => ['$first' => '$initiated_data.submission_location'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['submission_location' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return $data;
    }
    public function check_timeline_for_all_services()
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
            //                     '$first.official_form_details.action', 'Deliver'
            //                 ]
            //             ]],
            //         ]
            //     )
            // ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.submission_location',
                    'submission_location' => ['$first' => '$initiated_data.submission_location'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['submission_location' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
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
            //                     '$first.official_form_details.action', 'Reject'
            //                 ]
            //             ]],
            //         ]
            //     )
            // ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.submission_location',
                    'submission_location' => ['$first' => '$initiated_data.submission_location'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['submission_location' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }


    public function check_timeline_for_all_services_pending_in_time_group_by()
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
            //                     '$first.official_form_details.action', 'Reject'
            //                 ]
            //             ]],
            //             ['$expr' => [
            //                 '$ne' => [
            //                     '$first.official_form_details.action', 'Deliver'
            //                 ]
            //             ]],

            //         ]
            //     )
            // ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.submission_location',
                    'submission_location' => ['$first' => '$initiated_data.submission_location'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['submission_location' => 1]
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
            //                     '$first.official_form_details.action', 'Reject'
            //                 ]
            //             ]],
            //             ['$expr' => [
            //                 '$ne' => [
            //                     '$first.official_form_details.action', 'Deliver'
            //                 ]
            //             ]],

            //         ]
            //     )
            // ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.submission_location',
                    'submission_location' => ['$first' => '$initiated_data.submission_location'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['submission_location' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);

        // pre($data);

        return $data;
    }
    public function total_ovca_count()
    {
        $collection = 'ovca';
        $count = $this->total_rows($collection);
        return $count;
    }
    public function officewise_application_count_for_ovca()
    {
        $collection = 'ovca';
        $operations = array(
            array(
                '$unwind' => '$execution_data'
            ),
            array(
                '$group' => array(
                    '_id' => '$execution_data.task_details.user_detail.location_name',
                    'task_name' => ['$first' => '$execution_data.task_details.task_name'],
                    'total_applications' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['task_name' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;
    }
    function  officewise_application_pending_count__for_ovca()
    {
        $collection = 'ovca';
        $operations = array(
            array('$project' => array(
                'sub_loc' => ['$arrayElemAt' => ['$execution_data', 0]],
                'initiated_data' => 1
            )),
            // array(
            //     '$unwind'=>'$execution_data'
            // ), 
            array('$match' => array(
                '$and' => [
                    ['$expr' => [
                        '$ne' => ['$sub_loc.official_form_details.action', 'Deliver']
                    ]],
                    ['$expr' => [
                        '$ne' => ['$sub_loc.official_form_details.action', 'Reject']
                    ]]
                ]

            )),
            // array(
            //     '$project'=>array(
            //         'sub_loc.task_details.user_detail.location_name'=>1
            //     )
            // )
            array(
                '$group' => array(
                    '_id' => '$sub_loc.task_details.user_detail.location_name',
                    'office_name' => ['$first' => '$sub_loc.task_details.user_detail.location_name'],
                    'task_name' => ['$first' => '$sub_loc.task_details.task_name'],
                    'total_applications' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['task_name' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;
    }
    public function officewise_application_pending_count__without_execution_data()
    {
        $collection = 'ovca';
        $operations = array(
            array('$project' => array(
                '_id' => 0,
                'count_aray' => [
                    '$size' => '$execution_data'
                ],

            )),
            array(
                '$match' => [
                    '$expr' => [
                        '$eq' => ['$count_array', 0]
                    ]
                ]
            ),
            array(
                '$count' => 'pass'
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        pre($data);
        return (array)$data;
    }
    function  officewise_application_rejected_count__for_ovca()
    {
        $collection = 'ovca';
        $operations = array(
            array('$project' => array(
                'sub_loc' => ['$arrayElemAt' => ['$execution_data', 0]],
                'initiated_data' => 1
            )),
            array('$match' => array(
                '$expr' => [
                    '$eq' => ['$sub_loc.official_form_details.action', 'Reject']
                ]
            )),
            array(
                '$group' => array(
                    '_id' => '$sub_loc.task_details.user_detail.location_name',
                    'office_name' => ['$first' => '$sub_loc.task_details.user_detail.location_name'],
                    'total_applications' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['task_name' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;
    }
    function  officewise_application_delivered_count__for_ovca()
    {
        $collection = 'ovca';
        $operations = array(
            array('$project' => array(
                'sub_loc' => ['$arrayElemAt' => ['$execution_data', 0]],
                'initiated_data' => 1
            )),
            array('$match' => array(
                '$expr' => [
                    '$eq' => ['$sub_loc.official_form_details.action', 'Deliver']
                ]
            )),
            array(
                '$group' => array(
                    '_id' => '$sub_loc.task_details.user_detail.location_name',
                    'office_name' => ['$first' => '$sub_loc.task_details.user_detail.location_name'],
                    'total_applications' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['task_name' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;
    }
}
