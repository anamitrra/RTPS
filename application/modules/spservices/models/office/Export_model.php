<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use MongoDB\BSON\UTCDateTime;

class Export_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->set_collection("sp_applications");
    }

    public function generate_report($status = '', $community = '', $start_date = '', $end_date = '', $limit = '', $start = '')
    {
        $collection = 'sp_applications';
        // $filter = ['status' => ['$in' => ["PAYMENT_COMPLETED", "UNDER_PROCESSING", "QUERY_ARISE", "QUERY_SUBMITTED"]] ];

        if (strlen($status)) {
            $filter['service_data.appl_status'] =  $status;
            // echo $status;
        }
        if (!empty($community)) {
            $filter['form_data.community'] = $community;
        }
        if (!empty($start_date) && !empty($end_date)) {
            $filter['form_data.created_at'] = [
                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($start_date) * 1000),
                '$lte' => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($end_date)) * 1000)
            ];
        }
// pre($filter);
        $operations = [
            [
                '$match' => $filter
            ],
            // [
            //     '$group' => [
            //         "_id" => '$service_id',
            //         "total" => ['$sum' => 1],
            //     ]
            // ]
        ];
        // echo $start_date;
        // echo $end_date;

        // pre($operations);
        return $this->mongo_db->aggregate($collection, $operations);
    }

    public function get_record($status, $community, $start_date, $end_date, $limit, $start)
    {
        if (!empty($status)) {
            $filter = ['service_data.appl_status' => $status];
        }
        if (!empty($community)) {
            $filter = ['form_data.community' => $community];
        }
        if (!empty($start_date) && !empty($end_date)) {
            $filter = ['form_data.created_at' => [
                '$gte' => new MongoDB\BSON\UTCDateTime(strtotime($start_date) * 1000),
                '$lte' => new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($end_date)) * 1000)
            ]];
        }
        $this->mongo_db->where_not_in('service_data.appl_status', ['DRAFT', 'SUBMITTED', 'REJECTED', 'DELIVERED']);
        return $this->mongo_db->where($filter)->limit($limit, $start)->get('sp_applications');
    }
}
