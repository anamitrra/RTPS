<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Servicewise_application_count_model extends Mongo_model
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
    function get_all_services()
    {
        return (array)$this->mongo_db->get("services");
    }
    function  officewise_application_count($service_id)
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
                '$group' => array(
                    '_id' => '$initiated_data.submission_location',
                    'submission_location' => ['$first' => '$initiated_data.submission_location'],
                    'service_id' => ['$first' => '$initiated_data.base_service_id'],
                    'department_id' => ['$first' => '$initiated_data.department_id'],
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
    public function total_services_rejected_group_by_service($service_id)
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
                '$project' => [
                    'first' => [
                        '$arrayElemAt' => ['$execution_data', 0]
                    ],
                    'initiated_data' => 1
                ]
            ),
            array('$unwind' => '$execution_data'),
            array(
                '$match' =>
                ['$expr' => [
                    '$eq' => [
                        '$first.official_form_details.action', 'Reject'
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
    public function total_services_delivered_group_by_service($service_id)
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
                '$project' => [
                    'first' => [
                        '$arrayElemAt' => ['$execution_data', 0]
                    ],
                    'initiated_data' => 1
                ]
            ),
            array(
                '$match' =>
                ['$expr' => [
                    '$eq' => [
                        '$first.official_form_details.action', 'Deliver'
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
    public function check_timeline_for_all_services($service_id)
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
                '$project' => [
                    'first' => [
                        '$arrayElemAt' => ['$execution_data', 0]
                    ],
                    'initiated_data' => 1
                ]
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'services',
                    'localField'   => 'initiated_data.base_service_id',
                    'foreignField' => 'service_id',
                    'as'           => 'service'
                )
            ),
            array('$unwind' => '$service'),
            array(
                '$match' => array(
                    '$and' => [
                        ['$expr' => [
                            '$gt' => [
                                ['$toInt' => ['$service.service_timeline']],
                                ['$toInt' => [
                                    '$divide' => [
                                        ['$subtract' => [
                                            ['$toDate' => '$first.task_details.executed_time'], '$initiated_data.submission_date'
                                        ]],
                                        86400000
                                    ]
                                ]]
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$first.official_form_details.action', 'Deliver'
                            ]
                        ]],
                    ]
                )
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
    public function check_timeline_for_all_services_pending_in_time_group_by($service_id)
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
                '$project' => [
                    'first' => [
                        '$arrayElemAt' => ['$execution_data', 0]
                    ],
                    'initiated_data' => 1
                ]
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'services',
                    'localField'   => 'initiated_data.base_service_id',
                    'foreignField' => 'service_id',
                    'as'           => 'service'
                )
            ),
            array('$unwind' => '$service'),
            array(
                '$match' => array(
                    '$and' => [
                        ['$expr' => [
                            '$gt' => [
                                ['$toInt' => ['$service.service_timeline']],
                                ['$toInt' => [
                                    '$divide' => [
                                        ['$subtract' => [
                                            ['$toDate' => '$first.task_details.executed_time'], '$initiated_data.submission_date'
                                        ]],
                                        86400000
                                    ]
                                ]]
                            ]
                        ]],
                        ['$expr' => [
                            '$ne' => [
                                '$first.official_form_details.action', 'Reject'
                            ]
                        ]],
                        ['$expr' => [
                            '$ne' => [
                                '$first.official_form_details.action', 'Deliver'
                            ]
                        ]],

                    ]
                )
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

    public function get_officewise_service_data($service_id, $department_id, $office_name)
    {
        $filter['service_id'] = $service_id;
        $filter['department_id'] = $department_id;
        $filter['submission_location'] = $office_name;
        // pre($filter);
        $this->set_collection('services_api_data');
        $res = (array)$this->get_where($filter);
        if (count($res) > 0 && !empty($res)) {
            return $res[0];
        } else {
            return FALSE;
        }
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
    public function service_mean($s_id)
    {

        $department = (array) $this->mongo_db->command(array(
            'aggregate' => 'applications',
            'pipeline' => [
                array('$match' => array('initiated_data.base_service_id' => $s_id, 'initiated_data.appl_status' => 'D')),
                array('$addFields' => array("latest_exec_date" => array(
                    '$cond' => array(array('$gt' => [array('$size' => '$execution_data'), 0]), array('$arrayElemAt' => ['$execution_data', 0]), '$initiated_data.submission_date')
                ))),

                array('$set' => array('latest_exec_date' => array('$cond' => [array('$eq' => [array('$type' => '$latest_exec_date'), 'object']), '$latest_exec_date.task_details.executed_time', '$latest_exec_date']))),
                // // {$set: {"latest_exec_date": {$cond: [{$eq: [{ $type: "$latest_exec_date"}, "object"] }, "$latest_exec_date.task_details.executed_time", "$latest_exec_date" ] } } },
                array('$addFields' => array('date_diff' => array('$trunc' => array('$divide' => [array('$subtract' => ['$latest_exec_date', '$initiated_data.submission_date']), 1000 * 60 * 60 * 24])))),
                // // {$addFields: {"date_diff": {$trunc: {$divide: [{ $subtract: [ "$latest_exec_date", "$initiated_data.submission_date" ] },  1000 * 60 * 60 * 24]  }  } }},
                array('$group' => array('_id' => '$initiated_data.base_service_id', 'min' => array('$avg' => '$date_diff'))),
                // //{$group: {_id: "$initiated_data.department_id", dept_name: {$first: "$initiated_data.department_name"}, "min":{$avg: "$date_diff"}, "date_diff_arr": {$push: "$date_diff"} } },
                array('$set' => array('min' => array('$trunc' => ['$min', 0]))),

            ],

            'allowDiskUse' => true,
            'cursor' => (object)[],


        ));

        return ($department);
        // echo '<pre>';
        // print_r($department);
        // echo '</pre>';
        // exit();
    }
    public function service_median($a)
    {

        $department_median = (array) $this->mongo_db->command(array(
            'aggregate' => 'applications',
            'pipeline' => [
                array('$match' => array('initiated_data.base_service_id' => $a, 'initiated_data.appl_status' => 'D')),
                array('$addFields' => array("latest_exec_date" => array(
                    '$cond' => array(array('$gt' => [array('$size' => '$execution_data'), 0]), array('$arrayElemAt' => ['$execution_data', 0]), '$initiated_data.submission_date')
                ))),

                array('$set' => array('latest_exec_date' => array('$cond' => [array('$eq' => [array('$type' => '$latest_exec_date'), 'object']), '$latest_exec_date.task_details.executed_time', '$latest_exec_date']))),
                // // {$set: {"latest_exec_date": {$cond: [{$eq: [{ $type: "$latest_exec_date"}, "object"] }, "$latest_exec_date.task_details.executed_time", "$latest_exec_date" ] } } },
                array('$addFields' => array('date_diff' => array('$trunc' => array('$divide' => [array('$subtract' => ['$latest_exec_date', '$initiated_data.submission_date']), 1000 * 60 * 60 * 24])))),
                // // {$addFields: {"date_diff": {$trunc: {$divide: [{ $subtract: [ "$latest_exec_date", "$initiated_data.submission_date" ] },  1000 * 60 * 60 * 24]  }  } }},
                array('$match' => array('date_diff' => array('$gte' => 0))),
                //{$match: {"date_diff": {$gte: 0}  } },
                array('$sort' => array('date_diff' =>  1)),
                array('$project' => array('initiated_data.department_name'=>1,'date_diff' =>  1)),


            ],

            'allowDiskUse' => true,
            'cursor' => (object)[],


        ));
        $dept_arr = array();
        $c = 0;
        foreach ($department_median as $dept) {


            array_push($dept_arr, intval($dept->date_diff));
            $c++;
            // echo "<br>";
        } //pre($dept_arr);
        
        $dept_name = $department_median ? $department_median[0]->initiated_data->department_name :' ';
        $minimum= count($dept_arr) >0 ? min($dept_arr) : '';
        $maximum= count($dept_arr) >0 ?max($dept_arr) : '';

        $m = floor(($c - 1) / 2);

        if ($m < 0) {
            $median2 = 0;
            // pre($a);
        } else {
            $median2 = ($c % 2) ? $dept_arr[$m] : (($dept_arr[$m] + $dept_arr[$m + 1]) / 2);
        }
        // echo $appl_count;
        $median=array($minimum, $maximum,round($median2), $dept_name);
        return ($median);
        // pre($dept_arr);


    }
}
