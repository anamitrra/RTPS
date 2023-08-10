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
        // $this->set_collection("applications");
    }

    public function applications_filter($limit, $start,  $apply_by, $col, $dir, $service_id_path, $service_id, $ap_path, $delivery_status_path, $collection, $mobile_path, $slug)
    {
        $filter = array();
        $filter["$service_id_path"] = array('$in' => [intval($service_id), $service_id]);
        if ($slug === "PFC" || $slug === "CSC") {
            $filter["$ap_path"] = $apply_by;
        } elseif ($slug === "USER") {
            $filter["$mobile_path"] = $apply_by;
        } elseif ($slug === "SA") {
            //skip
        } else {
            $filter["$ap_path"] = "error";
        }

        $filter["$delivery_status_path"] = 'D';

        // pre($filter);
        $this->set_collection($collection);
        $project = array(
            "service_id",
            "service_name",
            "mobile",
            "rtps_trans_id",
            "app_ref_no",
            "status",
            "vahan_app_no",
            "applicant_mobile_number",
            "delivery_status",
            "service_data.service_name",
            "service_data.appl_ref_no",
            "form_data.mobile",
            "service_data.appl_status",
            "certificate",
            "certificate_path",
            "form_data.certificate",
            'form_data.service_plus_data'
        );
        // $this->mongo_db->select("service_id","service_name","mobile","rtps_trans_id","app_ref_no","status");
        return $this->search_selected_rows($limit, $start, $filter, $col, $dir, $project);
    }

    public function total_app_rows($apply_by, $service_id_path, $service_id, $ap_path, $delivery_status_path, $collection, $mobile_path, $slug)
    {
        $filter = array();
        $filter["$service_id_path"] = array('$in' => [intval($service_id), $service_id]);
        if ($slug === "PFC" || $slug === "CSC") {
            $filter["$ap_path"] = $apply_by;
        }
        if ($slug === "USER") {
            $filter["$mobile_path"] = $apply_by;
        }


        $filter["$delivery_status_path"] = 'D';

        // pre($filter);
        $this->set_collection($collection);
        return $this->tot_search_rows($filter);
    }


    public function application_search_rows($limit, $start, $keyword, $col, $dir, $apply_by, $service_id_path, $service_id, $ap_path, $delivery_status_path, $collection, $mobile_path, $slug)
    {
        $filter = array();
        $filter["$service_id_path"] = array('$in' => [intval($service_id), $service_id]);
        if ($slug === "PFC" || $slug === "CSC") {
            $filter["$ap_path"] = $apply_by;
        }
        if ($slug === "USER") {
            $filter["$mobile_path"] = $apply_by;
        }

        $filter["$delivery_status_path"] = 'D';

        $temp = array(
            '$and' => [$filter],
            '$or' => [
                ['service_name' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['rtps_trans_id' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['app_ref_no' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['mobile' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['service_data.appl_ref_no' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['service_data.service_name' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['form_data.mobile' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['service_data.rtps_trans_id' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['service_data.appl_ref_no' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
            ]
        );
        //print_r($temp);
        $this->set_collection($collection);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }

    public function get_filtered_citizen($projectionArray, $match = [], $searchArray = [], $start = false, $limit = false, $orderByArray = []): array
    {
        if (!empty($match)) {
            if (array_key_exists(0, $match)) {
                $matchArray['$and'] = $match;
            } else {
                $matchArray['$and'] = array($match);
            }
        } else {
            return [];
        }
        if (!empty($searchArray)) {
            $searchAnd = [];
            foreach ($searchArray as $searchKey => $dataToSearchFor) {
                $searchAnd[] = [$searchKey => ['$regex' => '^' . $dataToSearchFor . '', '$options' => 'i']];
            }
        }
        if (!empty($searchAnd)) {
            $matchArray['$or'] = $searchAnd;
        }
        $operations = array(
            array(
                '$project' => $projectionArray
            )
        );
        if (isset($matchArray)) {
            $operations[] = array(
                '$match'  => $matchArray
            );
        }
       
        if ($start !== false && $limit !== false) {
            $operations[] = ['$skip' => intval($start)];
            $operations[] = ['$limit' => intval($limit)];
        }
     
        if ($orderByArray) {
            $operations[] = ['$sort' => $orderByArray];
        }
       
        $data = $this->mongo_db->aggregate($this->table, $operations);
        $data = (array)$data;
        return $data;
    }


    public function applications_filter_new($searchArray = [], $start = false, $limit = false, $orderByArray = [], $apply_by, $filter_service_id): array
    {
        if (!empty($searchArray)) {
            $searchAnd = [];
            foreach ($searchArray as $searchKey => $dataToSearchFor) {
                $searchAnd[] = [$searchKey => ['$regex' => '^' . $dataToSearchFor . '', '$options' => 'i']];
            }
        }

        $operations = array();

        if (!empty($searchAnd)) {

            $operations[] = array(
                '$match'  => array(
                    '$and' => array($filter_service_id ? ['delivery_status' => "D", 'applied_by' => $apply_by, 'service_id' => $filter_service_id] :  ['delivery_status' => "D", 'applied_by' => $apply_by]),
                    '$or' => $searchAnd
                ),
            );
        } else {
            $operations[] = array(
                '$match'  => array(
                    '$and' => array($filter_service_id ? ['delivery_status' => "D", 'applied_by' => $apply_by, 'service_id' => $filter_service_id] :  ['delivery_status' => "D", 'applied_by' => $apply_by])
                ),
            );
        }

        $operations[] = array(
            '$project' => array(
                "service_name" => 1,
                "mobile" => 1,
                "rtps_trans_id" => 1,
                "app_ref_no" => 1,
                "service_id" => 1,
                "portal_no" => 1,
                "vahan_app_no" => 1,
            )
        );

        if (!empty($searchAnd)) {
            if ($filter_service_id) {
                $unionMatch = array(
                    '$match' => array(
                        '$or' => [
                            array(
                                '$and' => array(['service_data.appl_status' => "D", 'service_data.applied_by' => $apply_by, 'service_data.service_id' => $filter_service_id]),
                                '$or' => $searchAnd
                            ),
                            array(
                                '$and' => array(['status' => "D", 'applied_by' => $apply_by, 'service_id' => $filter_service_id]),
                                '$or' => $searchAnd
                            )

                        ]
                    )

                );
            } else {
                $unionMatch = array(
                    '$match' => array(
                        '$or' => [
                            array(
                                '$and' => array(['service_data.appl_status' => "D", 'service_data.applied_by' => $apply_by]),
                                '$or' => $searchAnd
                            ),
                            array(
                                '$and' => array(['status' => "D", 'applied_by' => $apply_by]),
                                '$or' => $searchAnd
                            )

                        ]
                    )

                );
            }
        } else {
            if ($filter_service_id) {
                $unionMatch = array(
                    '$match' => array(
                        '$or' => [
                            array(
                                '$and' => array(['service_data.appl_status' => "D", 'service_data.applied_by' => $apply_by, 'service_data.service_id' => $filter_service_id]),

                            ),
                            array(
                                '$and' => array(['status' => "D", 'applied_by' => $apply_by, 'service_id' => $filter_service_id]),

                            )

                        ]
                    )

                );
            } else {
                $unionMatch = array(
                    '$match' => array(
                        '$or' => [
                            array(
                                '$and' => array(['service_data.appl_status' => "D", 'service_data.applied_by' => $apply_by])
                            ),
                            array(
                                '$and' => array(['status' => "D", 'applied_by' => $apply_by])
                            )

                        ]
                    )

                );
            }
        }


        $operations[] = array(
            '$unionWith'  => array(
                "coll" => "sp_applications",
                "pipeline" => array(
                    $unionMatch,
                    array(
                        '$project' => array(
                            "service_data" => 1,
                            "service_name" => '$service_name',
                            "mobile" => '$mobile',
                            "sp_mobile" => '$form_data.mobile',
                            "rtps_trans_id" => '$rtps_trans_id',
                            "app_ref_no" => '$rtps_trans_id',
                            "service_id" => '$service_id',
                            "sp_certificate_path" => '$form_data.certificate',
                            "marriage_nec_certificate_path" => '$certificate',
                            "CRCPY_certificate_path" => '$certificate_path',
                        )
                    )

                )
            )
        );


        if ($start !== false && $limit !== false) {
            $operations[] = ['$skip' => intval($start)];
            $operations[] = ['$limit' => intval($limit)];
        }
     
        if ($orderByArray) {
            $operations[] = ['$sort' => $orderByArray];
        }
       
        $data = $this->mongo_db->aggregate("intermediate_ids", $operations);
        $data = (array)$data;
        return $data;
    }


    public function total_app_rows_new($searchArray = [], $apply_by, $filter_service_id)
    {
        if (!empty($searchArray)) {
            $searchAnd = [];
            foreach ($searchArray as $searchKey => $dataToSearchFor) {
                $searchAnd[] = [$searchKey => ['$regex' => '^' . $dataToSearchFor . '', '$options' => 'i']];
            }
        }

        $operations = array();

        if (!empty($searchAnd)) {

            $operations[] = array(
                '$match'  => array(
                    '$and' => array($filter_service_id ? ['delivery_status' => "D", 'applied_by' => $apply_by, 'service_id' => $filter_service_id] :  ['delivery_status' => "D", 'applied_by' => $apply_by]),
                    '$or' => $searchAnd
                ),
            );
        } else {
            $operations[] = array(
                '$match'  => array(
                    '$and' => array($filter_service_id ? ['delivery_status' => "D", 'applied_by' => $apply_by, 'service_id' => $filter_service_id] :  ['delivery_status' => "D", 'applied_by' => $apply_by])
                ),
            );
        }

        if (!empty($searchAnd)) {
            if ($filter_service_id) {
                $unionMatch = array(
                    '$match' => array(
                        '$or' => [
                            array(
                                '$and' => array(['service_data.appl_status' => "D", 'service_data.applied_by' => $apply_by, 'service_data.service_id' => $filter_service_id]),
                                '$or' => $searchAnd
                            ),
                            array(
                                '$and' => array(['status' => "D", 'applied_by' => $apply_by, 'service_id' => $filter_service_id]),
                                '$or' => $searchAnd
                            )

                        ]
                    )

                );
            } else {
                $unionMatch = array(
                    '$match' => array(
                        '$or' => [
                            array(
                                '$and' => array(['service_data.appl_status' => "D", 'service_data.applied_by' => $apply_by]),
                                '$or' => $searchAnd
                            ),
                            array(
                                '$and' => array(['status' => "D", 'applied_by' => $apply_by]),
                                '$or' => $searchAnd
                            )

                        ]
                    )

                );
            }
        } else {
            if ($filter_service_id) {
                $unionMatch = array(
                    '$match' => array(
                        '$or' => [
                            array(
                                '$and' => array(['service_data.appl_status' => "D", 'service_data.applied_by' => $apply_by, 'service_data.service_id' => $filter_service_id])
                            ),
                            array(
                                '$and' => array(['status' => "D", 'applied_by' => $apply_by, 'service_id' => $filter_service_id])
                            )

                        ]
                    )

                );
            } else {
                $unionMatch = array(
                    '$match' => array(
                        '$or' => [
                            array(
                                '$and' => array(['service_data.appl_status' => "D", 'service_data.applied_by' => $apply_by])
                            ),
                            array(
                                '$and' => array(['status' => "D", 'applied_by' => $apply_by])
                            )

                        ]
                    )

                );
            }
        }
        $operations[] = array(
            '$unionWith'  => array(
                "coll" => "sp_applications",
                "pipeline" => array(
                    $unionMatch

                )
            )
        );
        $operations[] = array(
            '$count' => "total_rows"
        );
        // pre( $operations);
        $data = $this->mongo_db->aggregate("intermediate_ids", $operations);

        $data = (array)$data;
        if(count( $data) > 0){
            return $data[0]->total_rows;
        }else{
            return 0;
        }
       
    }
}
