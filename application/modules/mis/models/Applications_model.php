<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Applications_model extends Mongo_model
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
    /**
     * applications_search_rows
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $keyword
     * @param mixed $col
     * @param mixed $dir
     * @return search data
     */
    public function applications_search_rows($limit, $start, $keyword, $col, $dir)
    {
        $temp = array(
            "initiated_data.appl_ref_no" => array(
                "\$regex" => '^' . $keyword . '',
                "\$options" => 'i'
            )
        );
        //print_r($temp);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }
    /**
     * applications_tot_search_rows
     *
     * @param mixed $keyword
     * @return void
     */
    public function applications_tot_search_rows($keyword)
    {
        $temp = array(
            "initiated_data.appl_ref_no" => array(
                "\$regex" => '^' . $keyword . '',
                "\$options" => 'i'
            )
        );
        //print_r($temp);
        return $this->tot_search_rows($temp);
    }
    /**
     * 
     * applications_filter
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $temp
     * @param mixed $col
     * @param mixed $dir
     * @return void
     */
    public function applications_filter($limit, $start, $temp, $col, $dir)
    {
        if (count($temp) == 0) {
            return $this->all_rows($limit, $start, $col, $dir);
        } else {
            $filter = array();
            if (isset($temp["startDate"]) && isset($temp["endDate"])) {
                $filter["initiated_data.submission_date"] = array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                );
            }
            if (isset($temp["service_status"]) && $temp["service_status"] != "") {
                if (intval($temp["service_status"]) == 4) {
                    $arr = array();
                    array_push($arr, array("execution_data.0.official_form_details.action" => array("\$ne" => "Deliver")));
                    array_push($arr, array("execution_data.0.official_form_details.action" => array("\$ne" => "Reject")));
                    $filter["\$and"] = $arr;
                } else {
                    $filter["execution_data.0.official_form_details.action"] = get_service_status($temp["service_status"]);
                }
            }
            if (isset($temp["services"]) &&  $temp["services"] != "") {
                $arr = array();
                foreach ($temp["services"] as $service_id) {
                    array_push($arr, array(
                        "initiated_data.base_service_id" => $service_id
                    ));
                }
                $filter["\$or"] = $arr;
            }
            if (isset($temp["office"]) &&  $temp["office"] != "") {
                $arr = array();
                foreach ($temp["office"] as $office_id) {
                    array_push($arr, array(
                        "initiated_data.submission_location" => $office_id
                    ));
                }
                $filter["\$or"] = $arr;
            }
           // pre($filter);
            //pre($temp["service_status"]);
            return $this->search_rows($limit, $start, $filter, $col, $dir);
        }
    }
    /**
     * applications_filter_count
     *
     * @param mixed $temp
     * @return void
     */
    public function applications_filter_count($temp)
    {
        //var_dump($temp);
        if (count($temp) == 0) {
            return $this->total_rows();
        } else {
           // die('here');
            $filter = array();
            if (isset($temp["startDate"]) && isset($temp["endDate"])) {
                $filter["initiated_data.submission_date"] = array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                );
            }
            if (isset($temp["service_status"]) && $temp["service_status"] != "") {
                if (intval($temp["service_status"]) == 4) {
                    $arr = array();
                    array_push($arr, array("execution_data.0.official_form_details.action" => array("\$ne" => "Deliver")));
                    array_push($arr, array("execution_data.0.official_form_details.action" => array("\$ne" => "Reject")));
                    $filter["\$and"] = $arr;
                } else {
                    $filter["execution_data.0.official_form_details.action"] = get_service_status($temp["service_status"]);
                }
            }
            if (isset($temp["services"]) &&  $temp["services"] != "") {
                $arr = array();
                foreach ($temp["services"] as $service_id) {
                    array_push($arr, array(
                        "initiated_data.base_service_id" => $service_id
                    ));
                }
                $filter["\$or"] = $arr;
            }
            if (isset($temp["office"]) &&  $temp["office"] != "") {
                $arr = array();
                foreach ($temp["office"] as $office_id) {
                    array_push($arr, array(
                        "initiated_data.submission_location" => $office_id
                    ));
                }
                $filter["\$or"] = $arr;
            }
            //print_r($filter);
            //var_dump(get_service_status($temp["service_status"]));
            return $this->tot_search_rows($filter);
        }
    }
    /**
     * get_by_appl_ref_no
     *
     * @param mixed $id
     * @return void
     */
    public function get_by_appl_ref_no($id)
    {
        $filter['initiated_data.appl_ref_no'] = $id;
        return $this->mongo_db->where($filter)->find_one($this->table);
    }
    public function check_timeline()
    {
        $collection = 'applications';
        $operations = array(
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
                                '$initiated_data.appl_status', 'D'
                            ]
                        ]]
                    ]
                )
            ),
            array(
                '$count' => 'pass'
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        $arr = (array) $data;
        if (isset($arr[0])) {
            return $arr[0];
        } else {
            return 0;
        }
    }
    public function check_timeline_for_pending_applications()
    {
        $collection = 'applications';
        $operations = array(
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
                '$count' => 'pass'
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        $arr = (array) $data;
        if (isset($arr[0])) {
            return $arr[0];
        } else {
            return 0;
        }
    }


    /* Calculate pending in time  */
    public function calculate_pending_in_time($service_id = null)
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    "initiated_data.appl_status" => array(
                        '$nin' => ["D", "R"]
                    )
                )
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'services',
                    'localField'   => 'initiated_data.base_service_id',
                    'foreignField' => 'service_id',
                    'as'           => 'services'
                )
            ),
            array(
                '$project' => array(
                    'base_service_id' => '$initiated_data.base_service_id',
                    'submission_date' => '$initiated_data.submission_date',
                    'timeline_obj' => array(
                        '$arrayElemAt' => [ '$services', 0 ]
                    )
                )
            ),
            array(
                '$project' => array(
                    'base_service_id' => 1,
                    'submission_date' => 1,
                    'timeline' => array(
                        '$toInt' => '$timeline_obj.service_timeline'
                    ),
                    'expr_date' => array(
                        '$add' => ['$submission_date', array(
                            '$multiply' => [array(
                                '$toInt' => '$timeline_obj.service_timeline'
                            ), 24*60*60000]
                        )]
                    )
                )
            ),
            array(
                '$match' => array(
                    '$expr' => array(
                        '$lte' => ['$$NOW', '$expr_date']
                    )
                )
            ),
            array(
                '$count' => 'total'
            )
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        // pre($data);
        
        return $data[0]->total ?? 0;
    }





    public function check_timeline_for_rejected_applications()
    {
        $collection = 'applications';
        $operations = array(
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
                                '$initiated_data.appl_status', 'R'
                            ]
                        ]],

                    ]
                )
            ),
            array(
                '$count' => 'pass'
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        $arr = (array) $data;
        if (isset($arr[0])) {
            return $arr[0];
        } else {
            return 0;
        }
    }
    public function check_timeline_using_appl_ref_no($appl_ref_no)
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => [
                    '$expr' => [
                        '$eq' => ['$initiated_data.appl_ref_no', $appl_ref_no]
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
                '$project' => [
                    'service_time_line' => ['$toInt' => ['$service.service_timeline']],
                    // 'time_elapsed' => ['$toInt' => [
                    //     '$divide' => [
                    //         ['$subtract' => [
                    //             ['$toDate' => '$first.task_details.executed_time'], '$initiated_data.submission_date'
                    //         ]],
                    //         86400000
                    //     ]
                    // ]]
                ]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        $arr = (array) $data;
        if (isset($arr[0])) {
            return $arr[0];
        } else {
            return 0;
        }
    }
    public function check_timeline_for_specific_service($service_id)
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => ['initiated_data.base_service_id' => $service_id]
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'services',
                    'localField'   => 'initiated_data.base_service_id',
                    'foreignField' => 'service_id',
                    'as'           => 'service'
                )
            ),
            array('$unwind' => '$initiated_data'),
            array('$unwind' => '$execution_data'),
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
                                            ['$toDate' => '$execution_data.task_details.executed_time'], '$initiated_data.submission_date'
                                        ]],
                                        86400000
                                    ]
                                ]]
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$execution_data.official_form_details.action', 'Deliver'
                            ]
                        ]],
                    ]
                )
            ),
            array(
                '$count' => 'pass'
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);

        $arr = (array) $data;

        if (isset($arr[0])) {
            return $arr[0];
        } else {
            return 0;
        }
    }
    
    public function total_services_delivered_group_by_service()
    {
        $collection = 'applications';
        $operations = array(
            array('$unwind' => '$execution_data'),
            array(
                '$match' =>
                ['$expr' => [
                    '$eq' => [
                        '$execution_data.official_form_details.action', 'Deliver'
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
        //pre($data);
        return $data;
    }
    //-----------------------------------------------------------------------------------------------------
    //Below are the functions for pending applications
    /**
     * applications_search_rows_pending
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $keyword
     * @param mixed $col
     * @param mixed $dir
     * @return void
     */
    public function applications_search_rows_pending($limit, $start, $keyword, $col, $dir)
    {
        $filter = [
            '$and' => [
                ['initiated_data.appl_ref_no' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['execution_data.0.official_form_details.action' => ['$ne' => 'Deliver']],
                ['execution_data.0.official_form_details.action' => ['$ne' => 'Reject']]
            ]
        ];
        return $this->search_rows($limit, $start, $filter, $col, $dir);
    }

    /**
     * applications_tot_search_rows_pending
     *
     * @param mixed $keyword
     * @return void
     */
    public function applications_tot_search_rows_pending($keyword)
    {
        $filter = [
            '$and' => [
                ['initiated_data.appl_ref_no' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['execution_data.0.official_form_details.action' => ['$ne' => 'Deliver']],
                ['execution_data.0.official_form_details.action' => ['$ne' => 'Reject']]
            ]
        ];
        //print_r($temp);
        return $this->tot_search_rows($filter);
    }

    /**
     * applications_filter_pending
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $temp
     * @param mixed $col
     * @param mixed $dir
     * @return void
     */
    public function applications_filter_pending($limit, $start, $temp, $col, $dir)
    {
        if (count($temp) == 0) {
            $filter = array();
            $arr = array();
            array_push($arr, array("execution_data.0.official_form_details.action" => array("\$ne" => "Deliver")));
            array_push($arr, array("execution_data.0.official_form_details.action" => array("\$ne" => "Reject")));
            $filter["\$and"] = $arr;
            return $this->search_rows($limit, $start, $filter, $col, $dir);
        } else {
            $filter = array();
            $arr = array();
            array_push($arr, array("execution_data.0.official_form_details.action" => array("\$ne" => "Deliver")));
            array_push($arr, array("execution_data.0.official_form_details.action" => array("\$ne" => "Reject")));
            $filter["\$and"] = $arr;
            if (isset($temp["startDate"]) && isset($temp["endDate"])) {
                $filter["initiated_data.submission_date"] = array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                );
            }
            if (isset($temp["services"]) &&  $temp["services"] != "") {
                $arr = array();
                foreach ($temp["services"] as $service_id) {
                    array_push($arr, array(
                        "initiated_data.base_service_id" => $service_id
                    ));
                }
                $filter["\$or"] = $arr;
            }
            //pre($filter);
            return $this->search_rows($limit, $start, $filter, $col, $dir);
        }
    }

    /**
     * applications_filter_count_pending
     *
     * @param mixed $temp
     * @return void
     */
    public function applications_filter_count_pending($temp)
    {
        //var_dump($temp);
        if (count($temp) == 0) {
            $filter = array();
            $arr = array();
            array_push($arr, array("execution_data.0.official_form_details.action" => array("\$ne" => "Deliver")));
            array_push($arr, array("execution_data.0.official_form_details.action" => array("\$ne" => "Reject")));
            $filter["\$and"] = $arr;
            return $this->tot_search_rows($filter);
        } else {
            $filter = array();
            $arr = array();
            array_push($arr, array("execution_data.0.official_form_details.action" => array("\$ne" => "Deliver")));
            array_push($arr, array("execution_data.0.official_form_details.action" => array("\$ne" => "Reject")));
            $filter["\$and"] = $arr;
            if (isset($temp["startDate"]) && isset($temp["endDate"])) {
                $filter["initiated_data.submission_date"] = array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                );
            }

            if (isset($temp["services"]) &&  $temp["services"] != "") {
                $arr = array();
                foreach ($temp["services"] as $service_id) {
                    array_push($arr, array(
                        "initiated_data.base_service_id" => $service_id
                    ));
                }
                $filter["\$or"] = $arr;
            }
            //print_r($filter);
            //var_dump(get_service_status($temp["service_status"]));
            return $this->tot_search_rows($filter);
        }
    }

    //-----------------------------------------------------------------------------------------------------
    //Below are the functions for delivered applications
    /**
     * applications_search_rows_delivered
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $keyword
     * @param mixed $col
     * @param mixed $dir
     * @return void
     */
    public function applications_search_rows_delivered($limit, $start, $keyword, $col, $dir)
    {
        $filter = [
            '$and' => [
                ['initiated_data.appl_ref_no' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['execution_data.0.official_form_details.action' => ['$eq' => 'Deliver']]
            ]
        ];
        return $this->search_rows($limit, $start, $filter, $col, $dir);
    }

    /**
     * applications_tot_search_rows_delivered
     *
     * @param mixed $keyword
     * @return void
     */
    public function applications_tot_search_rows_delivered($keyword)
    {
        $filter = [
            '$and' => [
                ['initiated_data.appl_ref_no' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['execution_data.0.official_form_details.action' => ['$eq' => 'Deliver']]
            ]
        ];
        //print_r($temp);
        return $this->tot_search_rows($filter);
    }

    /**
     * applications_filter_delivered
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $temp
     * @param mixed $col
     * @param mixed $dir
     * @return void
     */
    public function applications_filter_delivered($limit, $start, $temp, $col, $dir)
    {
        if (count($temp) == 0) {
            $filter = array();
            $filter['execution_data.0.official_form_details.action'] = ['$eq' => 'Deliver'];
            return $this->search_rows($limit, $start, $filter, $col, $dir);
        } else {
            $filter = array();
            $filter['execution_data.0.official_form_details.action'] = ['$eq' => 'Deliver'];
            if (isset($temp["startDate"]) && isset($temp["endDate"])) {
                $filter["initiated_data.submission_date"] = array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                );
            }
            if (isset($temp["services"]) &&  $temp["services"] != "") {
                $arr = array();
                foreach ($temp["services"] as $service_id) {
                    array_push($arr, array(
                        "initiated_data.base_service_id" => $service_id
                    ));
                }
                $filter["\$or"] = $arr;
            }
            //pre($filter);
            return $this->search_rows($limit, $start, $filter, $col, $dir);
        }
    }

    /**
     * applications_filter_count_delivered
     *
     * @param mixed $temp
     * @return void
     */
    public function applications_filter_count_delivered($temp)
    {
        //var_dump($temp);
        if (count($temp) == 0) {
            $filter = array();
            $filter['execution_data.0.official_form_details.action'] = ['$eq' => 'Deliver'];
            return $this->tot_search_rows($filter);
        } else {
            $filter = array();
            $filter['execution_data.0.official_form_details.action'] = ['$eq' => 'Deliver'];
            if (isset($temp["startDate"]) && isset($temp["endDate"])) {
                $filter["initiated_data.submission_date"] = array(
                    "\$gte" => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                    "\$lte" => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                );
            }

            if (isset($temp["services"]) &&  $temp["services"] != "") {
                $arr = array();
                foreach ($temp["services"] as $service_id) {
                    array_push($arr, array(
                        "initiated_data.base_service_id" => $service_id
                    ));
                }
                $filter["\$or"] = $arr;
            }
            //print_r($filter);
            //var_dump(get_service_status($temp["service_status"]));
            return $this->tot_search_rows($filter);
        }
    }
    function  officewise_application_count()
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$group' => array(
                    '_id' => '$initiated_data.submission_location',
                    'submission_location' => ['$first' => '$initiated_data.submission_location'],
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
    public function total_services_group_by_service_top_services()
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'service_name' => ['$first' => '$initiated_data.service_name'],
                    'total_received' => array('$sum' => 1),
                )
            ),
            array(
                '$limit'=>5
            ),
            array(
                '$sort' => ['total_received' => -1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return $data;
    }
    public function total_received_gender_wise()
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$group' => array(
                    '_id' => '$initiated_data.attribute_details.applicant_gender',
                    'gender' => ['$first' => '$initiated_data.attribute_details.applicant_gender'],
                    'total_received' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['total_received' => -1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;
    }

    /**
     *
     * @param $filter
     * @param $filterBy
     * @param array $selectInputs
     * @param array $searchArray // $searchArray has key(field to check) => value pair
     * @return mixed
     */

    public function get_filtered_revenue($filter, $selectInputs = [] , $searchArray = [],$start = null,$limit = null, $order = null, $dir = null){
        // $filterBy todo : day, month, quarterly or annually
//        switch ($filterBy){
//            case 'annually':
//                // TODO :: need to confirm this year or last one year? // currently set to this year
//                $dateObjToCompare = '01-01-'.date('Y');
//                break;
//            case 'quarterly':
//                $dateObjToCompare = date("Y-m-d",strtotime("-1 Months"));
//                break;
//            case 'monthly':
//                $dateObjToCompare = '01-'.date('m-Y');
//                break;
//            default:
//                $dateObjToCompare = date('d-m-Y');
//                break;
//        }

        $query =  $this->mongo_db->where($filter);
//            ->where_gte('initiated_data.submission_date', create_mongo_date($dateObjToCompare));

        if(isset($start) && isset($limit) && isset($order) && isset($dir) ){
            $query = $query->limit($limit, $start)
                ->order_by($order, $dir);
        }

        if(!empty($selectInputs)){
            $query = $query->select($selectInputs);
        }
        if(!empty($searchArray)){
           foreach ($searchArray as $searchKey => $dataToSearchFor){
               $filter = [
                   '$and' => [
                       [$searchKey => ['$regex' => '^' . $dataToSearchFor . '', '$options' => 'i']],
                   ]
               ];
           }

           return $query->get_data_like($filter,$this->table);
        }else{

            return $query->get($this->table);

        }

    }
    public function get_objectid_by_ref($appl_ref_no){
        $filter['initiated_data.appl_ref_no'] = $appl_ref_no;
        $this->mongo_db->select(array('initiated_data'));
        $data= $this->mongo_db->where($filter)->find_one($this->table);
        if( !empty($data)){
            return $data->_id->{'$id'};
        }else{
            return array();
        }
    }


    public function get_filtered_citizen($projectionArray,$match = [],$searchArray = [],$start = false,$limit = false, $orderByArray = []): array
    {
        if(!empty($match)){
            if(array_key_exists(0,$match)){
                $matchArray['$and'] = $match;
            }else{
                $matchArray['$and'] = array($match);
            }
        }else{
            return [];
        }
        if(!empty($searchArray)){
            $searchAnd = [];
            foreach ($searchArray as $searchKey => $dataToSearchFor){
                $searchAnd[] = [$searchKey => ['$regex' => '^' . $dataToSearchFor . '', '$options' => 'i']];
            }
        }
        if(!empty($searchAnd)){
            $matchArray['$or'] = $searchAnd;
        }
        $operations = array(
            array(
                '$project' => $projectionArray
            )
        );
        if(isset($matchArray)){
            $operations[] = array(
                '$match'  => $matchArray
            );
        }
//        pre($operations);
        if($start !== false && $limit !== false){
            $operations[] = ['$skip' => intval($start)];
            $operations[] = ['$limit' => intval($limit)];
        }
//        pre($orderByArray);
        if($orderByArray){
            $operations[] = ['$sort' => $orderByArray];
        }
//        pre($operations);
        $data = $this->mongo_db->aggregate($this->table, $operations);
        //pre($data);
        $data = (array)$data;
        return $data;

    }
}
