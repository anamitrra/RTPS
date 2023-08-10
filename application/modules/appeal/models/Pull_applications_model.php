<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Pull_applications_model extends Mongo_model
{
    function __construct()
    {
        parent::__construct();
        $this->set_collection("appeal_applications");
    }
    /**
     * pending_appeals_beyond_30days_not_beyond_45days
     * @return [integer]
     * @param mixed $location_id 
     */
    public function pending_appeals($location_id)
    {
        $collection = 'appeal_applications';
        //$current_time = new MongoDB\BSON\UTCDateTime(microtime(true) * 1000);
        $operations = array(
            array(
                '$match' => [
                    '$expr' => [
                        '$eq' => [
                            '$location_id', new ObjectId($location_id)
                        ]

                    ]
                ]
            ),
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
            ),
            
            array('$project' => [
                '_id'=>1,
                'appeal_id' => 1,
            ])
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        $arr = (array) $data;
        if (!empty($arr) && count($arr) > 0) {
            return $arr;
        } else {
            return 0;
        }
    }
}
