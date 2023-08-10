<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    use MongoDB\BSON\ObjectId;
class Appeal_dashboard_count extends Mongo_model
{
    function __construct()
    {
        parent::__construct();
        $this->set_collection("appeal_applications");

        $this->user_id=$this->session->userdata('userId')->{'$id'};
    }



     
    public function total_rows_count()
    { $filter['$and'][] = [];
        $this->mongo_db->where(array(
            'process_users.userId' => new ObjectId( $this->user_id)
        ));
        $res= $this->mongo_db->get($this->table);
        if($res){
            $res=(array) $res;
            return count($res);
        }else{
            return 0;
        }
    }


    /**
     * search
     *
     * @param mixed $app_ref_no
     * @param mixed $mobile
     * @return void
     */
    public function search($app_ref_no, $mobile = '')
    {
        $expr[] = ['$expr' => [
            '$eq' => [
                '$initiated_data.appl_ref_no', $app_ref_no
            ]
        ]];
        if ($mobile != '') {
            $expr[] = ['$expr' => [
                '$eq' => [
                    '$initiated_data.attribute_details.mobile_number', $mobile
                ]
            ]];
        }
        $this->set_collection("applications");
        $collection = 'applications';
        $timeline1 = $this->config->item('timeline1');
        $timeline2 = $this->config->item('timeline2');
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
            array(
                '$match' => array(
                    '$and' => $expr
                )
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
            // array('$unwind' => '$execution_data'),
            array('$unwind' => array('path'=>'$execution_data','preserveNullAndEmptyArrays'=> true)),
            array(
                '$project' => array(
                    "initiated_data.appl_ref_no" => 1,
                    "initiated_data.attribute_details.mobile_number" => 1,
                    'service_timeline' => '$service.service_timeline',
                    'timeline_1_expired' => [
                        '$gt' => [
                            [
                                '$toInt' => [
                                    '$divide' => [
                                        [
                                            '$subtract' => [$current_time, '$initiated_data.submission_date']
                                        ],
                                        86400000 // equivalent to one day
                                    ]
                                ]
                            ],
                            ['$toInt' => ['$add' => [['$toInt' => ['$service.service_timeline']], $timeline1]]]
                        ]
                    ],
                    'timeline_2_expired' => ['$gt' => [
                        ['$toInt' => [
                            '$divide' => [
                                ['$subtract' => [
                                    $current_time, '$initiated_data.submission_date'
                                ]],
                                86400000 // equivalent to one day
                            ]
                        ]],
                        ['$toInt' => ['$add' => [['$toInt' => ['$service.service_timeline']], $timeline1, $timeline2]]]
                    ]],
                    'rejected_before_service_timeline' => [
                        '$lt' => [
                            [
                                '$toInt' => [
                                    '$divide' => [
                                        ['$subtract' => [
                                            '$execution_data.task_details.executed_time', '$initiated_data.submission_date'
                                        ]],
                                        86400000 // equivalent to one day
                                    ]
                                ]
                            ],
                            [
                                '$add' => [
                                    ['$toInt' => [
                                        '$divide' => [
                                            ['$subtract' => [
                                                '$execution_data.task_details.executed_time', '$initiated_data.submission_date'
                                            ]],
                                            86400000 // equivalent to one day
                                        ]
                                    ]],
                                    $timeline1
                                ]
                            ]
                        ]
                    ],
                    'execution_data.task_details.executed_time' => 1,
                    'initiated_data.submission_date' => 1,
                    //                    'reject_after_timeline' =>['$gt' => [['$toInt' => [
                    //                        '$divide' => [
                    //                            ['$subtract' => [
                    //                                '$initiated_data.submission_date', '$execution_data.task_details.executed_time'
                    //                            ]],
                    //                            86400000 //equivalant to one day
                    //                        ]
                    //                    ]],['$toInt' => ['$service.service_timeline']],
                    //                            ],
                    //                            ],
                    'Reject' => ['$eq' => ['$execution_data.official_form_details.action', 'Reject']]
                )
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        // $data=[
        //     'initiated_data.submission_date'=>new \MongoDB\BSON\UTCDateTime((strtotime('01-10-2020') * 1000)),
        //     'execution_data.0.task_details.executed_time'=>new \MongoDB\BSON\UTCDateTime((strtotime('05-11-2020') * 1000)),
        // ];
        // $this->update('5f3640436d269762522ab5e2',$data);
        // pre($data);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr[0];
        } else {
            return FALSE;
        }
    }
    /**
     * verify_otp
     *
     * @param mixed $app_ref_no
     * @param mixed $mobile
     * @param mixed $otp
     * @param mixed array
     * @return void
     */
    /**
     * verify_otp
     *
     * @param mixed $app_ref_no
     * @param mixed $mobile
     * @param mixed $otp
     * @param mixed array
     * @return void
     */
    public function verify_otp($app_ref_no, $mobile, $otp, array $filter = [])
    {
        $this->mongo_db->where("appl_ref_no", $app_ref_no);
        $this->mongo_db->where("mobile", $mobile);
        $this->mongo_db->where("otp", $otp);
        $this->mongo_db->where("verify_at", NULL);
        if (!empty($filter)) {
            $this->mongo_db->where($filter);
        }
        $this->mongo_db->order_by("created_at", 'DESC');
        return $this->mongo_db->get("mobile_otp");
    }
    // /** TODO may not be needed
    //  * opt_insert
    //  *
    //  * @param mixed $data
    //  * @return void
    //  */
    // public function opt_insert($data)
    // {
    //     $this->set_collection("mobile_otp");
    //     $this->insert($data);
    // }
    /**
     * check_if_otp_time_expired
     *
     * @return void
     */
    public function check_if_otp_time_expired($app_ref_no, $mobile, $otp)
    {
        // '{ $subtract: [ new Date(), "$created_at" ] } '
        $collection = "mobile_otp";
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
            array(
                '$match' => array(
                    "\$and" => array(
                        array("appl_ref_no" => array("\$eq" => $app_ref_no),),
                        array("mobile" => array("\$eq" => $mobile),),
                        array("otp" => array("\$eq" => $otp),),
                    )
                ),
            ),
            array(
                '$project' => array(
                    'dateDifference'    => array("\$subtract" => array($current_time, "\$created_at"))
                ),
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        if (isset($data->{'0'})) {
            $minutes_10 = 10 * 60 * 1000; //milliseconds
            if ($data->{'0'}->dateDifference > $minutes_10) {
                return FALSE;
            } else {
                return $data->{'0'}->{'_id'}->{'$id'};
            }
        } else {
            return FALSE;
        }
    }
    public function pending_appeals_beyond_30days_not_beyond_45days()
    {
        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
            array(
                '$match' => [
                    '$expr' => [
                        '$eq' => [
                            '$process_users.userId', new ObjectId( $this->user_id)
                        ]
                       
                    ]
                ]
            ),
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$gt' => [
                                [
                                    '$toInt' => [
                                        '$divide' => [
                                            [
                                                '$subtract' => [$current_time, '$created_at']
                                            ],
                                            86400000 // equivalent to one day
                                        ]
                                    ]
                                ], 30 //if crossed 30 days
                            ]
                        ]],
                        ['$expr' => [
                            '$lt' => [
                                [
                                    '$toInt' => [
                                        '$divide' => [
                                            [
                                                '$subtract' => [$current_time, '$created_at']
                                            ],
                                            86400000 // equivalent to one day
                                        ]
                                    ]
                                ], 45 // Less Than 45 days
                            ]
                        ]],
                        ['$expr' => [
                            '$ne' => [
                                '$process_status', 'resolved'
                            ]
                        ]],
                        ['$expr' => [
                            '$ne' => [
                                '$process_status', 'rejected'
                            ]
                        ]],
                    ]
                ]
            ),
            array(
                '$count' => 'pass'
            )
            // array(
            //     '$project' => array(
            //         'appeal_id'=>1,
            //         'appl_ref_no'=>1,
            //         'days_passed'=>[
            //             '$toInt' => [
            //                 '$divide' => [
            //                     [
            //                         '$subtract' => [$current_time, '$created_at']
            //                     ],
            //                     86400000 // equivalent to one day
            //                 ]
            //             ]
            //         ],
            //     )
            //     )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr[0]->pass;
        } else {
            return 0;
        }
    }
    public function pending_appeals_beyond_45days()
    {
        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
            array(
                '$match' => [
                    '$expr' => [
                        '$eq' => [
                            '$process_users.userId', new ObjectId( $this->user_id)
                        ]
                       
                    ]
                ]
            ),
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$gt' => [
                                [
                                    '$toInt' => [
                                        '$divide' => [
                                            [
                                                '$subtract' => [$current_time, '$created_at']
                                            ],
                                            86400000 // equivalent to one day
                                        ]
                                    ]
                                ], 45 // Less Than 45 days
                            ]
                        ]],
                        ['$expr' => [
                            '$ne' => [
                                '$process_status', 'resolved'
                            ]
                        ]],
                        ['$expr' => [
                            '$ne' => [
                                '$process_status', 'rejected'
                            ]
                        ]],
                    ]
                ]
            ),
            array(
                '$count' => 'pass'
            )
            // array(
            //     '$project' => array(
            //         'appeal_id'=>1,
            //         'appl_ref_no'=>1,
            //         'days_passed'=>[
            //             '$toInt' => [
            //                 '$divide' => [
            //                     [
            //                         '$subtract' => [$current_time, '$created_at']
            //                     ],
            //                     86400000 // equivalent to one day
            //                 ]
            //             ]
            //         ],
            //     )
            //     )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr[0]->pass;
        } else {
            return 0;
        }
    }

    public function pending_appeals_beyond_45days_by_location($location)
    {
        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $location_id=new  MongoDB\BSON\ObjectId($location);
        $operations = array(
            array(
                '$match' => [
                    '$expr' => [
                        '$eq' => [
                            '$location_id', $location_id
                        ]
                       
                    ]
                ]
            ),
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$gt' => [
                                [
                                    '$toInt' => [
                                        '$divide' => [
                                            [
                                                '$subtract' => [$current_time, '$created_at']
                                            ],
                                            86400000 // equivalent to one day
                                        ]
                                    ]
                                ], 45 // Less Than 45 days
                            ]
                        ]],
                        ['$expr' => [
                            '$ne' => [
                                '$process_status', 'resolved'
                            ]
                        ]],
                        ['$expr' => [
                            '$ne' => [
                                '$process_status', 'rejected'
                            ]
                        ]],
                    ]
                ]
            ),
            array(
                '$count' => 'pass'
            )
            // array(
            //     '$project' => array(
            //         'appeal_id'=>1,
            //         'appl_ref_no'=>1,
            //         'days_passed'=>[
            //             '$toInt' => [
            //                 '$divide' => [
            //                     [
            //                         '$subtract' => [$current_time, '$created_at']
            //                     ],
            //                     86400000 // equivalent to one day
            //                 ]
            //             ]
            //         ],
            //     )
            //     )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr[0]->pass;
        } else {
            return 0;
        }
    }
    public function disposed_appeals_within_30days_by_location($location_id)
    {
        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $location_id=new  MongoDB\BSON\ObjectId($location_id);
        $operations = array(
            array(
                '$match' => [
                    '$expr' => [
                        '$eq' => [
                            '$location_id', $location_id
                        ]
                       
                    ]
                ]
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'appeal_processes',
                    'localField'   => 'appeal_id',
                    'foreignField' => 'appeal_id',
                    'as'           => 'process_table'
                )
            ),
            array(
                '$match' => [
                    '$and' => [
                        ['$or' => [
                            ['$expr' => [
                                '$eq' => [
                                    '$process_status', 'resolved'
                                ]
                            ]],
                            ['$expr' => [
                                '$eq' => [
                                    '$process_status', 'rejected'
                                ]
                            ]],
                        ]],
                        ['$expr' => [
                            '$lt' => [
                                [
                                    '$toInt' => [
                                        '$divide' => [
                                            [
                                                '$subtract' => [$current_time, '$created_at']
                                            ],
                                            86400000 // equivalent to one day
                                        ]
                                    ]
                                ], 30 // Less Than 45 days
                            ]
                        ]],

                    ]
                ]
            )
            ,
            array(
                '$count' => 'pass'
            )
            // array(
            //     '$project' => array(
            //         'appeal_id'=>1,
            //         'appl_ref_no'=>1,
            //         'days_passed'=>[
            //             '$toInt' => [
            //                 '$divide' => [
            //                     [
            //                         '$subtract' => [$current_time, '$created_at']
            //                     ],
            //                     86400000 // equivalent to one day
            //                 ]
            //             ]
            //         ],
            //     )
            //     )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr[0]->pass;
        } else {
            return 0;
        }
    }

    public function pending_appeals_beyond_30days_not_beyond_45days_by_location($location_id)
    {
        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $location_id=new  MongoDB\BSON\ObjectId($location_id);
        $operations = array(
            array(
                '$match' => [
                    '$expr' => [
                        '$eq' => [
                            '$location_id', $location_id
                        ]
                       
                    ]
                ]
            ),
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$gt' => [
                                [
                                    '$toInt' => [
                                        '$divide' => [
                                            [
                                                '$subtract' => [$current_time, '$created_at']
                                            ],
                                            86400000 // equivalent to one day
                                        ]
                                    ]
                                ], 30 //if crossed 30 days
                            ]
                        ]],
                        ['$expr' => [
                            '$lt' => [
                                [
                                    '$toInt' => [
                                        '$divide' => [
                                            [
                                                '$subtract' => [$current_time, '$created_at']
                                            ],
                                            86400000 // equivalent to one day
                                        ]
                                    ]
                                ], 45 // Less Than 45 days
                            ]
                        ]],
                        ['$expr' => [
                            '$ne' => [
                                '$process_status', 'resolved'
                            ]
                        ]],
                        ['$expr' => [
                            '$ne' => [
                                '$process_status', 'rejected'
                            ]
                        ]],
                    ]
                ]
            ),
            array(
                '$count' => 'pass'
            )
            // array(
            //     '$project' => array(
            //         'appeal_id'=>1,
            //         'appl_ref_no'=>1,
            //         'days_passed'=>[
            //             '$toInt' => [
            //                 '$divide' => [
            //                     [
            //                         '$subtract' => [$current_time, '$created_at']
            //                     ],
            //                     86400000 // equivalent to one day
            //                 ]
            //             ]
            //         ],
            //     )
            //     )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr[0]->pass;
        } else {
            return 0;
        }
    }

    public function disposed_appeals_within_30days()
    {
        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
            array(
                '$match' => [
                    '$expr' => [
                        '$eq' => [
                            '$process_users.userId', new ObjectId( $this->user_id)
                        ]
                       
                    ]
                ]
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'appeal_processes',
                    'localField'   => 'appeal_id',
                    'foreignField' => 'appeal_id',
                    'as'           => 'process_table'
                )
            ),
            array(
                '$match' => [
                    '$and' => [
                        ['$or' => [
                            ['$expr' => [
                                '$eq' => [
                                    '$process_status', 'resolved'
                                ]
                            ]],
                            ['$expr' => [
                                '$eq' => [
                                    '$process_status', 'rejected'
                                ]
                            ]],
                        ]],
                        ['$expr' => [
                            '$lt' => [
                                [
                                    '$toInt' => [
                                        '$divide' => [
                                            [
                                                '$subtract' => [$current_time, '$created_at']
                                            ],
                                            86400000 // equivalent to one day
                                        ]
                                    ]
                                ], 30 // Less Than 45 days
                            ]
                        ]],

                    ]
                ]
            )
            ,
            array(
                '$count' => 'pass'
            )
            // array(
            //     '$project' => array(
            //         'appeal_id'=>1,
            //         'appl_ref_no'=>1,
            //         'days_passed'=>[
            //             '$toInt' => [
            //                 '$divide' => [
            //                     [
            //                         '$subtract' => [$current_time, '$created_at']
            //                     ],
            //                     86400000 // equivalent to one day
            //                 ]
            //             ]
            //         ],
            //     )
            //     )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr[0]->pass;
        } else {
            return 0;
        }
    }

    public function total_appeals_by_location($location_id){
       $arr= $this->mongo_db->select(array("appeal_id"))->where(array('location_id'=>new  MongoDB\BSON\ObjectId($location_id)))->get($this->table);
       $arr = (array) $arr;
       if (!empty($arr) && count($arr) > 0) {
        return count($arr);
    } else {
        return 0;
    }
    } 
    public function total_filtered_appeals_by_location($where){
       $arr= $this->mongo_db->select(array("appeal_id"))->where($where)->get($this->table);
       $arr = (array) $arr;
       if (!empty($arr) && count($arr) > 0) {
        return count($arr);
    } else {
        return 0;
    }
    }
}
