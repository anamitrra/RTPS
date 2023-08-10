<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Reports_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection("appeal_applications");
        $this->user_type = !empty($this->session->userdata('role')->slug) ? $this->session->userdata('role')->slug : "";
    }
    /**
     * appeals_filter
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $temp
     * @param mixed $col
     * @param mixed $dir
     * @param int $appealType
     * @return void
     */
    public function appeals_filter($limit, $start, $temp, $col, $dir, $appealType = 1)
    {
        $user_type = !empty($this->session->userdata('role')->slug) ? $this->session->userdata('role')->slug : "";
        if (isset($temp["startDate"]) && isset($temp["endDate"])) {
            $filter["created_at"] = array(
                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                '$lte' => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
            );
        }
     //   $filter['$and'][] = ['appeal_type' => $appealType];
        // $filter['$and'][] = ['appeal_expiry_status' => FALSE];
        $col = "created_at";
        $dir = "asc";
        $user_id = $this->session->userdata('userId')->{'$id'};
        //pre($filter);
        // if ($user_type === "SA") {
        //     return $this->search_rows($limit, $start, $filter, $col, $dir);
        // } else if ($user_type === 'PFC') {
        //     $filter['$and'][] = ['applied_by_user_id' => new  ObjectId($user_id)];
        //     return $this->search_rows($limit, $start, $filter, $col, $dir);
        // } else if ($user_type === 'DPS') {
        //     $filter['$and'][] = ['process_users.userId' => new ObjectId($user_id)];
        //     return $this->search_rows($limit, $start, $filter, $col, $dir);
        // } else {

        //     $location=$this->session->userdata['location'];
        //     $filter['$and'][] = ['location_id' => new  ObjectId($location)];
        //     return $this->search_rows($limit, $start, $filter, $col, $dir);
        //     // echo 'Not Authorised!';
        // }



        if ($user_type === "SA") {
            return $this->search_rows($limit, $start, $filter, $col, $dir);
        } else if ($user_type === 'PFC') {
            $filter['$and'][] = ['applied_by_user_id' => new  ObjectId($user_id)];
            return $this->search_rows($limit, $start, $filter, $col, $dir);
        } else {
            $filter['$and'][] = ['process_users.userId' => new ObjectId($user_id)];
            return $this->search_rows($limit, $start, $filter, $col, $dir);
        } 
    }
    /**
     * appeals_filter_count
     *
     * @param int $appealType
     * @return void
     */
    public function appeals_filter_count($appealType = 1)
    {
        $user_type = !empty($this->session->userdata('role')->slug) ? $this->session->userdata('role')->slug : "";
        if (isset($temp["startDate"]) && isset($temp["endDate"])) {
            $filter["created_at"] = array(
                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000),
                '$lte' => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
            );
        }
        $filter['$and'][] = ['appeal_type' => $appealType];
        $filter['$and'][] = ['appeal_expiry_status' => FALSE];
        $user_id = $this->session->userdata('userId')->{'$id'};
        if ($user_type = "SA") {
            return $this->tot_search_rows($filter);
        } else if ($user_type === 'PFC') {
            $filter['$and'][] = ['applied_by_user_id' => new  ObjectId($user_id)];
            return $this->tot_search_rows($filter);
        } else if ($user_type === 'DPS') {
            $filter['$and'][] = ['process_users.userId' => new ObjectId($user_id)];
            return $this->tot_search_rows($filter);
        } else {
            echo 'Not Authorised!';
        }
    }
    /**
     * appeal
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $keyword
     * @param mixed $col
     * @param mixed $dir
     * @param int $appealType
     * @return void
     */
    public function appeals_search_rows($limit, $start, $keyword, $col, $dir, $appealType = 1)
    {
        $filter = array(
            '$or' => [
                ['appl_ref_no' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['appeal_id' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['contact_number' => ['$regex' => '^' . $keyword . '', '$options' => 'i']]
            ],
            '$and' => [
                ['appeal_type' => $appealType],
                ['appeal_expiry_status' => FALSE]
            ]
        );
        $user_type = !empty($this->session->userdata('role')->slug) ? $this->session->userdata('role')->slug : "";
        // $filter['$and'][] = ['appeal_type' => $appealType];
        //$filter['$and'][] = ['appeal_expiry_status' => FALSE];
        $user_id = $this->session->userdata('userId')->{'$id'};
        // if ($user_type = "SA") {
        //     return $this->search_rows($limit, $start, $filter, $col, $dir);
        // } else if ($user_type === 'PFC') {
        //     $filter['$and'][] = ['applied_by_user_id' => new  ObjectId($user_id)];
        //     return $this->search_rows($limit, $start, $filter, $col, $dir);
        // } else if ($user_type === 'DPS') {
        //     $filter['$and'][] = ['process_users.userId' => new ObjectId($user_id)];
        //     return $this->search_rows($limit, $start, $filter, $col, $dir);
        // } else {
        //     echo 'Not Authorised!';
        // }
        if ($user_type === "SA") {
            return $this->search_rows($limit, $start, $filter, $col, $dir);
        } else if ($user_type === 'PFC') {
            $filter['$and'][] = ['applied_by_user_id' => new  ObjectId($user_id)];
            return $this->search_rows($limit, $start, $filter, $col, $dir);
        } else {
            $filter['$and'][] = ['process_users.userId' => new ObjectId($user_id)];
            return $this->search_rows($limit, $start, $filter, $col, $dir);
        } 

    }
    /**
     * appeals_tot_search_rows
     *
     * @param mixed $keyword
     * @param int $appealType
     * @return void
     */
    public function appeals_tot_search_rows($keyword, $appealType = 1)
    {
        $filter = array(
            '$or' => [
                ['appl_ref_no' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['appeal_id' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['contact_number' => ['$regex' => '^' . $keyword . '', '$options' => 'i']]
            ],
            '$and' => [
                ['appeal_type' => $appealType],
                ['appeal_expiry_status' => FALSE]
            ]
        );
        $user_type = !empty($this->session->userdata('role')->slug) ? $this->session->userdata('role')->slug : "";
        $filter['$and'][] = ['appeal_type' => $appealType];
        $filter['$and'][] = ['appeal_expiry_status' => FALSE];
        $user_id = $this->session->userdata('userId')->{'$id'};
        // if ($user_type = "SA") {
        //     return $this->tot_search_rows($filter);
        // } else if ($user_type === 'PFC') {
        //     $filter['$and'][] = ['applied_by_user_id' => new  ObjectId($user_id)];
        //     return $this->tot_search_rows($filter);
        // } else if ($user_type === 'DPS') {
        //     $filter['$and'][] = ['process_users.userId' => new ObjectId($user_id)];
        //     return $this->tot_search_rows($filter);
        // } else {
        //     echo 'Not Authorised!';
        // }
        if ($user_type === "SA") {
            return $this->tot_search_rows($filter);
        } else if ($user_type === 'PFC') {
            $filter['$and'][] = ['applied_by_user_id' => new  ObjectId($user_id)];
            return $this->tot_search_rows($filter);
        } else {
            $filter['$and'][] = ['process_users.userId' => new ObjectId($user_id)];
            return $this->tot_search_rows($filter);
        } 

    }
    public function pending_total_rows($temp)
    {
        $collection = 'appeal_applications';
        $operations = [];
        if (isset($temp["startDate"]) && isset($temp["endDate"])) {
            $operations[] = array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$gte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000)
                            ]
                        ]],
                        ['$expr' => [
                            '$lte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                            ]
                        ]],
                    ]
                ]
            );
        }
        $user_type = !empty($this->session->userdata('role')->slug) ? $this->session->userdata('role')->slug : "";
    
        if ($user_type !== "SA") {
            
            // $operations[] = array(
            //     '$match' => [
            //         '$and' => [
            //             ['$expr' => [
            //                 '$eq' => [
            //                     '$location_id',  new  $this->session->userdata['location']
            //                 ]
            //             ]],
            //         ]
            //     ]
            // );
            $user_id = $this->session->userdata('userId')->{'$id'};
            $operations[] =array(
                '$match' => [
                    '$expr' => [
                        '$eq' => [
                            '$process_users.userId', new ObjectId( $user_id)
                        ]
                       
                    ]
                ]
                        );

        }
        $operations[] =
            array(
                '$match' => [
                    '$and' => [
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
            );
        $operations[] =    array(
            '$count' => 'pass'
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr[0]->pass;
        } else {
            return 0;
        }
    }
    public function pending_appeals_filter($limit, $start, $temp, $order, $dir)
    {
        $collection = 'appeal_applications';
        $operations = [];
        if (isset($temp["startDate"]) && isset($temp["endDate"])) {
            $operations[] = array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$gte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000)
                            ]
                        ]],
                        ['$expr' => [
                            '$lte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                            ]
                        ]],
                    ]
                ]
            );
        }
        if ($this->user_type !== "SA") {
           
            // $operations[] = array(
            //     '$match' => [
            //         '$and' => [
            //             ['$expr' => [
            //                 '$eq' => [
            //                     '$location_id',  new  $this->session->userdata['location']
            //                 ]
            //             ]],
            //         ]
            //     ]
            // );
            $user_id = $this->session->userdata('userId')->{'$id'};
            $operations[] =array(
                '$match' => [
                    '$expr' => [
                        '$eq' => [
                            '$process_users.userId', new ObjectId( $user_id)
                        ]
                       
                    ]
                ]
                        );

        }
        $operations[] =
            array(
                '$match' => [
                    '$and' => [
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
            );
        $operations[] = array(
            '$skip' => (int)$start
        );
        $operations[] = array(
            '$limit' => (int)$limit
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr;
        } else {
            return [];
        }
    }
    public function pending_appeals_search_rows($limit, $start, $keyword, $order, $dir)
    {
        $collection = 'appeal_applications';
        $operations = [];
        if (isset($keyword) && $keyword != "" && $keyword != NULL) {
            $operations[] = array(
                '$match' => [
                    'appeal_id' => ['$regex' => '^' . $keyword . '', '$options' => 'i']
                ]
            );
        }
       
    
        if ($this->user_type !== "SA") {
          
            $user_id = $this->session->userdata('userId')->{'$id'};
            $operations[] =array(
                '$match' => [
                    '$expr' => [
                        '$eq' => [
                            '$process_users.userId', new ObjectId( $user_id)
                        ]
                       
                    ]
                ]
                        );

        }
        $operations[] =
            array(
                '$match' => [
                    '$and' => [
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
            );
        $operations[] = array(
            '$skip' => (int)$start
        );
        $operations[] = array(
            '$limit' => (int)$limit
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr;
        } else {
            return [];
        }
    }
    public function pending_appeals_tot_search_rows($keyword)
    {
        $collection = 'appeal_applications';
        if (isset($keyword) && $keyword != "" && $keyword != NULL) {
            $operations[] = array(
                '$match' => [
                    'appeal_id' => ['$regex' => '^' . $keyword . '', '$options' => 'i']
                ]
            );
        }
        $operations[] =
            array(
                '$match' => [
                    '$and' => [
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
            );
        $operations[] = array(
            '$count' => 'pass'
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr[0]->pass;
        } else {
            return 0;
        }
    }
    public function resolved_total_rows($temp)
    {
        $collection = 'appeal_applications';
        $operations = [];
        if (isset($temp["startDate"]) && isset($temp["endDate"])) {
            $operations[] = array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$gte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000)
                            ]
                        ]],
                        ['$expr' => [
                            '$lte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                            ]
                        ]],
                    ]
                ]
            );
        }
        $user_type = !empty($this->session->userdata('role')->slug) ? $this->session->userdata('role')->slug : "";
    
        if ($user_type !== "SA") {
            
            // $operations[] = array(
            //     '$match' => [
            //         '$and' => [
            //             ['$expr' => [
            //                 '$eq' => [
            //                     '$location_id',  new  $this->session->userdata['location']
            //                 ]
            //             ]],
            //         ]
            //     ]
            // );
            $user_id = $this->session->userdata('userId')->{'$id'};
            $operations[] =array(
                '$match' => [
                    '$expr' => [
                        '$eq' => [
                            '$process_users.userId', new ObjectId( $user_id)
                        ]
                       
                    ]
                ]
                        );

        }
        $operations[] =
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'resolved'
                            ]
                        ]]
                       
                    ]
                ]
            );
        $operations[] =    array(
            '$count' => 'pass'
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr[0]->pass;
        } else {
            return 0;
        }
    }
    public function resolved_appeals_filter($limit, $start, $temp, $order, $dir)
    {
        $collection = 'appeal_applications';
        $operations = [];
        if (isset($temp["startDate"]) && isset($temp["endDate"])) {
            $operations[] = array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$gte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000)
                            ]
                        ]],
                        ['$expr' => [
                            '$lte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                            ]
                        ]],
                    ]
                ]
            );
        }
        $user_type = !empty($this->session->userdata('role')->slug) ? $this->session->userdata('role')->slug : "";
    
        if ($user_type !== "SA") {
            
            // $operations[] = array(
            //     '$match' => [
            //         '$and' => [
            //             ['$expr' => [
            //                 '$eq' => [
            //                     '$location_id',  new  $this->session->userdata['location']
            //                 ]
            //             ]],
            //         ]
            //     ]
            // );
            $user_id = $this->session->userdata('userId')->{'$id'};
            $operations[] =array(
                '$match' => [
                    '$expr' => [
                        '$eq' => [
                            '$process_users.userId', new ObjectId( $user_id)
                        ]
                       
                    ]
                ]
                        );

        }
        $operations[] =
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'resolved'
                            ]
                        ]]
                    ]
                ]
            );
        $operations[] = array(
            '$skip' => (int)$start
        );
        $operations[] = array(
            '$limit' => (int)$limit
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr;
        } else {
            return [];
        }
    }
    public function resolved_appeals_search_rows($limit, $start, $keyword, $order, $dir)
    {
        $collection = 'appeal_applications';
        $operations = [];
        if (isset($keyword) && $keyword != "" && $keyword != NULL) {
            $operations[] = array(
                '$match' => [
                    'appeal_id' => ['$regex' => '^' . $keyword . '', '$options' => 'i']
                ]
            );
        }
        $operations[] =
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'resolved'
                            ]
                        ]]
                    ]
                ]
            );
        $operations[] = array(
            '$skip' => (int)$start
        );
        $operations[] = array(
            '$limit' => (int)$limit
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr;
        } else {
            return [];
        }
    }
    public function resolved_appeals_tot_search_rows($keyword)
    {
        $collection = 'appeal_applications';
        if (isset($keyword) && $keyword != "" && $keyword != NULL) {
            $operations[] = array(
                '$match' => [
                    'appeal_id' => ['$regex' => '^' . $keyword . '', '$options' => 'i']
                ]
            );
        }
        $operations[] =
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'resolved'
                            ]
                        ]]
                    ]
                ]
            );
        $operations[] = array(
            '$count' => 'pass'
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr[0]->pass;
        } else {
            return 0;
        }
    }
    public function rejected_total_rows($temp)
    {
        $collection = 'appeal_applications';
        $operations = [];
        if (isset($temp["startDate"]) && isset($temp["endDate"])) {
            $operations[] = array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$gte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000)
                            ]
                        ]],
                        ['$expr' => [
                            '$lte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                            ]
                        ]],
                    ]
                ]
            );
        }
        $user_type = !empty($this->session->userdata('role')->slug) ? $this->session->userdata('role')->slug : "";
    
        if ($user_type !== "SA") {
            
            // $operations[] = array(
            //     '$match' => [
            //         '$and' => [
            //             ['$expr' => [
            //                 '$eq' => [
            //                     '$location_id',  new  $this->session->userdata['location']
            //                 ]
            //             ]],
            //         ]
            //     ]
            // );
            $user_id = $this->session->userdata('userId')->{'$id'};
            $operations[] =array(
                '$match' => [
                    '$expr' => [
                        '$eq' => [
                            '$process_users.userId', new ObjectId( $user_id)
                        ]
                       
                    ]
                ]
                        );

        }
        $operations[] =
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'rejected'
                            ]
                        ]]
                       
                    ]
                ]
            );
        $operations[] =    array(
            '$count' => 'pass'
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr[0]->pass;
        } else {
            return 0;
        }
    }
    public function rejected_appeals_filter($limit, $start, $temp, $order, $dir)
    {
        $collection = 'appeal_applications';
        $operations = [];
        if (isset($temp["startDate"]) && isset($temp["endDate"])) {
            $operations[] = array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$gte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000)
                            ]
                        ]],
                        ['$expr' => [
                            '$lte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                            ]
                        ]],
                    ]
                ]
            );
        }
        $user_type = !empty($this->session->userdata('role')->slug) ? $this->session->userdata('role')->slug : "";
    
        if ($user_type !== "SA") {
            
            // $operations[] = array(
            //     '$match' => [
            //         '$and' => [
            //             ['$expr' => [
            //                 '$eq' => [
            //                     '$location_id',  new  $this->session->userdata['location']
            //                 ]
            //             ]],
            //         ]
            //     ]
            // );
            $user_id = $this->session->userdata('userId')->{'$id'};
            $operations[] =array(
                '$match' => [
                    '$expr' => [
                        '$eq' => [
                            '$process_users.userId', new ObjectId( $user_id)
                        ]
                       
                    ]
                ]
                        );

        }
        $operations[] =
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'rejected'
                            ]
                        ]]
                    ]
                ]
            );
        $operations[] = array(
            '$skip' => (int)$start
        );
        $operations[] = array(
            '$limit' => (int)$limit
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr;
        } else {
            return [];
        }
    }
    public function rejected_appeals_search_rows($limit, $start, $keyword, $order, $dir)
    {
        $collection = 'appeal_applications';
        $operations = [];
        if (isset($keyword) && $keyword != "" && $keyword != NULL) {
            $operations[] = array(
                '$match' => [
                    'appeal_id' => ['$regex' => '^' . $keyword . '', '$options' => 'i']
                ]
            );
        }
        $operations[] =
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'rejected'
                            ]
                        ]]
                    ]
                ]
            );
        $operations[] = array(
            '$skip' => (int)$start
        );
        $operations[] = array(
            '$limit' => (int)$limit
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr;
        } else {
            return [];
        }
    }
    public function rejected_appeals_tot_search_rows($keyword)
    {
        $collection = 'appeal_applications';
        $operations = [];
        if (isset($keyword) && $keyword != "" && $keyword != NULL) {
            $operations[] = array(
                '$match' => [
                    'appeal_id' => ['$regex' => '^' . $keyword . '', '$options' => 'i']
                ]
            );
        }
        $operations[] =
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'rejected'
                            ]
                        ]]
                    ]
                ]
            );
        $operations[] = array(
            '$count' => 'pass'
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr[0]->pass;
        } else {
            return 0;
        }
    }
    public function disposed_within_30_total_rows($temp)
    {
        $collection = 'appeal_applications';
        $operations = [];
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        if (isset($temp["startDate"]) && isset($temp["endDate"])) {
            $operations[] = array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$gte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000)
                            ]
                        ]],
                        ['$expr' => [
                            '$lte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                            ]
                        ]],
                    ]
                ]
            );
        }
        $operations[] =
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
        );
        $operations[] =    array(
            '$count' => 'pass'
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr[0]->pass;
        } else {
            return 0;
        }
    }
    public function disposed_within_30_appeals_filter($limit, $start, $temp, $order, $dir)
    {
        $collection = 'appeal_applications';
        $operations = [];
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        if (isset($temp["startDate"]) && isset($temp["endDate"])) {
            $operations[] = array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$gte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime($temp["startDate"]) * 1000)
                            ]
                        ]],
                        ['$expr' => [
                            '$lte' => [
                                '$created_at', new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($temp["endDate"])) * 1000)
                            ]
                        ]],
                    ]
                ]
            );
        }
        $operations[] =
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
        );
        $operations[] = array(
            '$skip' => (int)$start
        );
        $operations[] = array(
            '$limit' => (int)$limit
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr;
        } else {
            return [];
        }
    }
    public function disposed_within_30_appeals_search_rows($limit, $start, $keyword, $order, $dir)
    {
        $collection = 'appeal_applications';
        $operations = [];
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        if (isset($keyword) && $keyword != "" && $keyword != NULL) {
            $operations[] = array(
                '$match' => [
                    'appeal_id' => ['$regex' => '^' . $keyword . '', '$options' => 'i']
                ]
            );
        }
        $operations[] =
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
        );
        $operations[] = array(
            '$skip' => (int)$start
        );
        $operations[] = array(
            '$limit' => (int)$limit
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr;
        } else {
            return [];
        }
    }
    public function disposed_within_30_appeals_tot_search_rows($keyword)
    {
        $collection = 'appeal_applications';
        $operations=[];
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        if (isset($keyword) && $keyword != "" && $keyword != NULL) {
            $operations[] = array(
                '$match' => [
                    'appeal_id' => ['$regex' => '^' . $keyword . '', '$options' => 'i']
                ]
            );
        }
        $operations[] =
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
        );
        $operations[] = array(
            '$count' => 'pass'
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr[0]->pass;
        } else {
            return 0;
        }
    }
}
