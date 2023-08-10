<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    use MongoDB\BSON\ObjectId;
class Appeal_count_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->mongo_db->switch_db("appeal");
        $this->set_collection("appeal_applications");
    }
    function get_all_services()
    {
        return (array)$this->mongo_db->get("services");
    }

    
    public function first_appeal_total_count($service_id,$appealType=1)
    {
       
        $filter['$and'][] =  ['appeal_type' => $appealType];
        if($service_id){
            $filter['$and'][] =  ['service_id' => new ObjectId($service_id)];
        }
        return $this->tot_search_rows($filter);
    } 
    public function services_delivered_within_30_days_timeline($service_id,$appealType=1)
    {
        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
          
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'resolved'
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$appeal_type', $appealType
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$service_id', new ObjectId($service_id)
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
    public function services_delivered_after_30_days_timeline($service_id,$appealType=1)
    {
        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
          
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'resolved'
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$appeal_type', $appealType
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$service_id', new ObjectId($service_id)
                            ]
                        ]],
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
    
    public function services_rejected_within_30_days_timeline($service_id,$appealType=1)
    {
        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
          
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'rejected'
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$appeal_type', $appealType
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$service_id', new ObjectId($service_id)
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
    
    public function services_rejected_after_30_days_timeline($service_id,$appealType=1)
    {
        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
          
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'rejected'
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$appeal_type', $appealType
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$service_id', new ObjectId($service_id)
                            ]
                        ]],
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
    
    public function services_pending_withing_30_days_timeline($service_id,$appealType=1)
    {
        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
          
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
                        ['$expr' => [
                            '$eq' => [
                                '$appeal_type', $appealType
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$service_id', new ObjectId($service_id)
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

    public function services_pending_after_30_days_timeline($service_id,$appealType=1)
    {
        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
          
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
                        ['$expr' => [
                            '$eq' => [
                                '$appeal_type', $appealType
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$service_id', new ObjectId($service_id)
                            ]
                        ]],
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

    public function add_data($data,$collection_name){
        $this->mongo_db->switch_db("mis");
        $this->mongo_db->batch_insert($collection_name, $data);
    }

    function  districtwise_appeal_total_count($service_id,$appeal_type=1)
    {
        $collection = 'appeal_applications';
        $operations = array(
            array(
                '$match' => [
                    '$and' => [
    
                        ['$expr' => [
                            '$eq' => [
                                '$appeal_type', $appeal_type
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$service_id', new ObjectId($service_id)
                            ]
                        ]],
                        
                    
                    ]
                   

                ]
            ),

            array(
                '$group' => array(
                    '_id' => '$district',
                    'district_name' => ['$first' => '$district'],
                    'service_name' => ['$first' => '$name_of_service'],
                    'service_id' => ['$first' => '$service_id'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['district' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return $data;
    }

    public function districtwise_delivered_within_30_days_timeline($service_id,$appeal_type=1)
    {

        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'resolved'
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$appeal_type', $appeal_type
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$service_id', new ObjectId($service_id)
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
                                ], 30 // Less Than 45 days
                            ]
                        ]],

                    ]
                ]
            ),

            array(
                '$group' => array(
                    '_id' => '$district',
                    'district_name' => ['$first' => '$district'],
                    'service_id' => ['$first' => '$service_id'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['district' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return $data;
    }



    public function districtwise_rejected_within_30_days_timeline($service_id,$appeal_type=1)
    {

        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'rejected'
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$appeal_type', 1
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$service_id', new ObjectId($service_id)
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
                                ], 30 // Less Than 45 days
                            ]
                        ]],

                    ]
                ]
            ),

            array(
                '$group' => array(
                    '_id' => '$district',
                    'district_name' => ['$first' => '$district'],
                    'service_id' => ['$first' => '$service_id'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['district' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return $data;
    }
    public function districtwise_delivered_after_30_days_timeline($service_id,$appeal_type=1)
    {

        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'resolved'
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$appeal_type', 1
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$service_id', new ObjectId($service_id)
                            ]
                        ]],
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
                                ], 30 // Less Than 45 days
                            ]
                        ]],

                    ]
                ]
            ),

            array(
                '$group' => array(
                    '_id' => '$district',
                    'district_name' => ['$first' => '$district'],
                    'service_id' => ['$first' => '$service_id'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['district' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return $data;
    }
    
    public function districtwise_rejected_after_30_days_timeline($service_id,$appeal_type=1)
    {

        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
            array(
                '$match' => [
                    '$and' => [
                        ['$expr' => [
                            '$eq' => [
                                '$process_status', 'rejected'
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$appeal_type', 1
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$service_id', new ObjectId($service_id)
                            ]
                        ]],
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
                                ], 30 // Less Than 45 days
                            ]
                        ]],

                    ]
                ]
            ),

            array(
                '$group' => array(
                    '_id' => '$district',
                    'district_name' => ['$first' => '$district'],
                    'service_id' => ['$first' => '$service_id'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['district' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return $data;
    }
    
    public function districtwise_pending_withing_30_days_timeline($service_id,$appeal_type=1)
    {

        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
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
                        ['$expr' => [
                            '$eq' => [
                                '$appeal_type', 1
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$service_id', new ObjectId($service_id)
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
                                ], 30 // Less Than 45 days
                            ]
                        ]],

                    ]
                ]
            ),

            array(
                '$group' => array(
                    '_id' => '$district',
                    'district_name' => ['$first' => '$district'],
                    'service_id' => ['$first' => '$service_id'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['district' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return $data;
    }
    
    public function districtwise_pending_after_30_days_timeline($service_id,$appeal_type=1)
    {

        $collection = 'appeal_applications';
        $current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
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
                        ['$expr' => [
                            '$eq' => [
                                '$appeal_type', 1
                            ]
                        ]],
                        ['$expr' => [
                            '$eq' => [
                                '$service_id', new ObjectId($service_id)
                            ]
                        ]],
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
                                ], 30 // Less Than 45 days
                            ]
                        ]],

                    ]
                ]
            ),

            array(
                '$group' => array(
                    '_id' => '$district',
                    'district_name' => ['$first' => '$district'],
                    'service_id' => ['$first' => '$service_id'],
                    'count' => array('$sum' => 1),
                )
            ),
            array(
                '$sort' => ['district' => 1]
            )
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return $data;
    }

    

}
