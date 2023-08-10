<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Departmentwise_application_count_model extends Mongo_model
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
    function  count()
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$group' => array(
                    '_id' => '$initiated_data.parent_department_id',
                    'department_id' => ['$first' => '$initiated_data.parent_department_id'],
                    'department_name' => ['$first' => '$initiated_data.parent_department_name'],
                    'total_received' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['department_id' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return $data;
    }
    public function total_services_rejected_group_by_service()   // actually group by dept
    {
        $collection = 'applications';
        $operations = array(
            // array('$unwind' => '$execution_data'),
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
                    '_id' => '$initiated_data.parent_department_id',
                    'department_id' => ['$first' => '$initiated_data.parent_department_id'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['department_id' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }
    public function total_services_delivered_group_by_service()
    {
        $collection = 'applications';
        $operations = array(
            // array('$unwind' => '$execution_data'),
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
                    '_id' => '$initiated_data.parent_department_id',
                    'department_id' => ['$first' => '$initiated_data.parent_department_id'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['department_id' => 1]
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
            // array('$unwind' => '$initiated_data'),
            // array('$unwind' => '$execution_data'),

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
                    '_id' => '$initiated_data.parent_department_id',
                    'department_id' => ['$first' => '$initiated_data.parent_department_id'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['department_id' => 1]
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
                    '_id' => '$initiated_data.parent_department_id',
                    'department_id' => ['$first' => '$initiated_data.parent_department_id'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['department_id' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
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
                    '_id' => '$initiated_data.parent_department_id',
                    'department_id' => ['$first' => '$initiated_data.parent_department_id'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['department_id' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }
    public function check_timeline_for_all_services_rejected_in_time_group_by()
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                  "initiated_data.rit" => 1,
                )
            ),
         array(
                '$group' => array(
                    '_id' => '$initiated_data.parent_department_id',
                    'department_id' => ['$first' => '$initiated_data.parent_department_id'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['department_id' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }
    public function department_mean()
    {

        $department =(array) $this->mongo_db->command(array(
            'aggregate' => 'applications',
            'pipeline' => [
                array('$match' => array('initiated_data.appl_status' => 'D')),
                array('$addFields' => array("latest_exec_date" => array(
                    '$cond' => array(array('$gt' => [array('$size' => '$execution_data'), 0]), array('$arrayElemAt' => ['$execution_data', 0]), '$initiated_data.submission_date')
                ))),
                
                array('$set' => array('latest_exec_date' => array('$cond' => [array('$eq' => [array('$type' => '$latest_exec_date'), 'object']), '$latest_exec_date.task_details.executed_time', '$latest_exec_date']))),
                // // {$set: {"latest_exec_date": {$cond: [{$eq: [{ $type: "$latest_exec_date"}, "object"] }, "$latest_exec_date.task_details.executed_time", "$latest_exec_date" ] } } },
                array('$addFields' => array('date_diff' => array('$trunc' => array('$divide' => [array('$subtract' => ['$latest_exec_date', '$initiated_data.submission_date']), 1000 * 60 * 60 * 24])))),
                // // {$addFields: {"date_diff": {$trunc: {$divide: [{ $subtract: [ "$latest_exec_date", "$initiated_data.submission_date" ] },  1000 * 60 * 60 * 24]  }  } }},
                array('$group' => array('_id' => '$initiated_data.parent_department_id', 'department_name' => array('$first' => '$initiated_data.parent_department_id'), 'min' => array('$avg' => '$date_diff') )),
                // //{$group: {_id: "$initiated_data.department_id", dept_name: {$first: "$initiated_data.department_name"}, "min":{$avg: "$date_diff"}, "date_diff_arr": {$push: "$date_diff"} } },
                array('$set' => array('min' => array('$trunc' => ['$min', 0]))),

            ],

            'allowDiskUse' => true,
            'cursor' => (object)[],


        ));

        return($department);
        // echo '<pre>';
        // print_r($department);
        // echo '</pre>';
        // exit();
    }
    public function department_median($a){
      
        $department_median =(array) $this->mongo_db->command(array(
            'aggregate' => 'applications',
            'pipeline' => [
                array('$match' => array('initiated_data.parent_department_id'=> $a,'initiated_data.appl_status' => 'D')),
                array('$addFields' => array("latest_exec_date" => array(
                    '$cond' => array(array('$gt' => [array('$size' => '$execution_data'), 0]), array('$arrayElemAt' => ['$execution_data', 0]), '$initiated_data.submission_date')
                ))),
                
                array('$set' => array('latest_exec_date' => array('$cond' => [array('$eq' => [array('$type' => '$latest_exec_date'), 'object']), '$latest_exec_date.task_details.executed_time', '$latest_exec_date']))),
                // // {$set: {"latest_exec_date": {$cond: [{$eq: [{ $type: "$latest_exec_date"}, "object"] }, "$latest_exec_date.task_details.executed_time", "$latest_exec_date" ] } } },
                array('$addFields' => array('date_diff' => array('$trunc' => array('$divide' => [array('$subtract' => ['$latest_exec_date', '$initiated_data.submission_date']), 1000 * 60 * 60 * 24])))),
                // // {$addFields: {"date_diff": {$trunc: {$divide: [{ $subtract: [ "$latest_exec_date", "$initiated_data.submission_date" ] },  1000 * 60 * 60 * 24]  }  } }},
                array('$match' => array('date_diff'=>array('$gte'=>0))),
                //{$match: {"date_diff": {$gte: 0}  } },
                array( '$sort' =>array('date_diff'=>  1)),
                array( '$project' =>array('date_diff'=>  1)),
               

            ],

            'allowDiskUse' => true,
            'cursor' => (object)[],


        ));
        $dept_arr=array();
        $c=0;
        $m=0;
        $median2=0;
        foreach ($department_median as $dept) {

           
            array_push($dept_arr, intval($dept->date_diff));
            $c++;
            // echo "<br>";
        }
                if($c>0){
                    $m = floor(($c-1)/2);
                    $median2 = ($c % 2) ? $dept_arr[$m] : (($dept_arr[$m]+$dept_arr[$m+1])/2);
                }
               
                
                if($m<0){
                    $median2=0;
                   // pre($a);
                }
        // echo $appl_count;
        return(round($median2));
       // pre($dept_arr);


    }
}
