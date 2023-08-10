<?php

use MongoDB\BSON\ObjectId;

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
                                '$first.official_form_details.action', 'Deliver'
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
    public function check_timeline_for_rejected_applications()
    {
        $collection = 'applications';
        $operations = array(
            array('$unwind' => '$execution_data'),
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
                                '$execution_data.official_form_details.action', 'Reject'
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
            array('$unwind' => '$execution_data'),
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
                    'time_elapsed' => ['$toInt' => [
                        '$divide' => [
                            ['$subtract' => [
                                ['$toDate' => '$first.task_details.executed_time'], '$initiated_data.submission_date'
                            ]],
                            86400000
                        ]
                    ]]
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
                '$sort' => ['total_received' => -1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return $data;
    }

    // get latest
    public function get_latest($limit = 6,$order = 'DESC'){
        return $this->mongo_db->limit($limit)->order_by('_id',$order)->get($this->table);
    }

    // get distinct locations
    public function get_distinct_locations(){
        return $this->mongo_db->distinct($this->table,'initiated_data.submission_location');
    }

    public function update($id,$data){
      $this->mongo_db->set($data);
      $this->mongo_db->where(array("_id"=> new MongoDB\BSON\ObjectId($id)));
      return $this->mongo_db->update($this->table);
    }


    //for all application
    /**
     * applications_filter_count
     *
     * @param mixed $temp
     * @return void
     */
    public function all_applications_filter_count()
    {
        $this->set_collection("applications");
        return $this->total_rows();

    }

    /**
 * applications_filter
 *
 * @param mixed $limit
 * @param mixed $start
 * @param mixed $temp
 * @param mixed $col
 * @param mixed $dir
 * @return void
 */
public function all_applications_filter($limit, $start, $col, $dir)
{
    $this->set_collection("applications");
    $filter=array();
    return $this->search_rows($limit, $start, $filter, $col, $dir);

}

  //for my appeals for admin user
  public function my_appeals_count(){
    $user_type=!empty($this->session->userdata('role')->slug) ? $this->session->userdata('role')->slug : "";
    $user_id=$this->session->userdata('userId')->{'$id'};
    $this->set_collection("appeal_applications");
    $filter=['$and'=>[
        ['applied_by'=>$user_type],
        ['applied_by_user_id'=>new MongoDB\BSON\ObjectId($user_id)]
    ]];
    return $this->tot_search_rows($filter);

  }

  /**
   * appeals_filter
   *
   * @param mixed $limit
   * @param mixed $start
   * @param mixed $temp
   * @param mixed $col
   * @param mixed $dir
   * @return void
   */
  public function appeals_filter($limit, $start, $temp, $col="created_at", $dir="asc")
  {
      $user_type = !empty($this->session->userdata('role')->slug) ? $this->session->userdata('role')->slug : "";
      $user_id=$this->session->userdata('userId')->{'$id'};
      $this->set_collection("appeal_applications");
      $filter=['$and'=>[
          ['process_users.userId'=> new MongoDB\BSON\ObjectId($user_id) ],
          ['process_users.active'=> true],
      ]];
      return $this->search_rows($limit, $start, $filter, $col, $dir);
  }
    public function my_appeals_filter($limit, $start, $temp, $col="created_at", $dir="asc")
    {
        $user_id=$this->session->userdata('userId')->{'$id'};
        $this->set_collection("appeal_applications");
        $filter=['$and'=>[
            ['applied_by_user_id' => new ObjectId($user_id)]
        ]];
        return $this->search_rows($limit, $start, $filter, $col, $dir);
    }
  /**
   * appeals_filter_count
   *
   * @param mixed $temp
   * @return void
   */
  public function appeals_filter_count()
  {
      $user_type=!empty($this->session->userdata('role')->slug) ? $this->session->userdata('role')->slug : "";
      $user_id=$this->session->userdata('userId')->{'$id'};
      $this->set_collection("appeal_applications");
      $filter=['$and'=>[
        ['process_users.userId'=>new MongoDB\BSON\ObjectId($user_id)]
    ]];

      return $this->tot_search_rows($filter);
  }

  /**
   * appeal
   *
   * @param mixed $limit
   * @param mixed $start
   * @param mixed $keyword
   * @param mixed $col
   * @param mixed $dir
   * @return void
   */
  public function appeals_search_rows($limit, $start, $keyword, $col, $dir)
  {
      $this->set_collection("appeal_applications");
      $user_id=$this->session->userdata('userId')->{'$id'};
      $temp = array(
          '$or'=>[
            ['applicant_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            ['appeal_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']]
          ],
          '$and' => [
              ['applied_by_user_id' => new ObjectId($user_id)]
          ]
      );
      //print_r($temp);
      return $this->search_rows($limit, $start, $temp, $col, $dir);
  }
  /**
   * appeals_tot_search_rows
   *
   * @param mixed $keyword
   * @return void
   */
  public function appeals_tot_search_rows($keyword)
  {
      $this->set_collection("appeal_applications");
      $temp = array(
          '$or'=>[
            ['applicant_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            ['appeal_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']]
          ]
      );
      //print_r($temp);
      return $this->tot_search_rows($temp);
  }
}
