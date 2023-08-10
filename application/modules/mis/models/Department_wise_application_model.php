<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Department_wise_application_model extends Mongo_model
{
    /**
     * Department_wise_application_model constructor.
     */
    public function __construct()
    {
        $this->set_collection('department_wise_applications');
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
    public function applications_search_rows($dept_id, $limit, $start, $keyword, $col, $dir)
    {
        $this->set_collection('applications');
        $temp = [
            '$and' => [
                ['initiated_data.appl_ref_no' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['initiated_data.department_id' => $dept_id]
            ]
        ];

        //print_r($temp);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }
    /**
     * applications_tot_search_rows
     *
     * @param mixed $keyword
     * @return void
     */
    public function applications_tot_search_rows($dept_id, $keyword)
    {
        $this->set_collection('applications');
        $temp = [
            '$and' => [
                ['initiated_data.appl_ref_no' => ['$regex' => '^' . $keyword . '', '$options' => 'i']],
                ['initiated_data.department_id' => $dept_id]
            ]
        ];
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
    public function applications_filter($dept_id, $limit, $start, $temp, $col, $dir)
    {
        $this->set_collection('applications');
        if (count($temp) == 0) {
            $filter['initiated_data.department_id'] = $dept_id;
            return $this->search_rows($limit, $start, $filter, $col, $dir);
        } else {
            $filter['initiated_data.department_id'] = $dept_id;
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
    public function applications_filter_count($dept_id, $temp)
    {
        $this->set_collection('applications');
        //var_dump($temp);
        if (count($temp) == 0) {
            $filter['initiated_data.department_id'] = $dept_id;
            return $this->tot_search_rows($filter);
        } else {
            $filter['initiated_data.department_id'] = $dept_id;
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
        $this->set_collection('applications');
        $filter['initiated_data.appl_ref_no'] = $id;
        return $this->mongo_db->where($filter)->find_one($this->table);
    }
    public function total_rows_dept_wise($dept_id)
    {
        $this->set_collection('applications');
        $filter['initiated_data.department_id'] = $dept_id;
        return $this->tot_search_rows($filter);
    }

    public function upsert_deptwise_applications($data = array())
    { 
        foreach ($data as $dept) {
            $this->mongo_db->command(array(
                'update' => $this->table,
                'updates' => [
                    array(
                        'q' => array('department_id' => $dept->department_id),
                        'u' => array(
                            'department_id' => $dept->department_id,
                            'department_name' => $dept->department_name,
                            'total_received' => $dept->total_received,
                            'delivered' => $dept->delivered,
                            'rejected' => $dept->rejected,
                            'pit' => $dept->pit,
                            'pending' => $dept->pending,
                            'pbt' => $dept->pbt,
                            'timely_delivered' => $dept->timely_delivered,
                            'rit' => $dept->timely_rejected,
                            'paa' => $dept->paa,
                            'mean' => isset($dept->mean) ? $dept->mean : 0,
                            'median' => $dept->median,
                        ),
                        'upsert' => true,
                        'multi' => false
                    )

                ],

            ));
        }
    }
 Public function insert_deptwise_application($data = array())  
 {
    foreach ($data as $dept) {
        $dept->
        $post_data = array(
            "department_id" => $department_id,
            "department_name" => $department_name,
            "department_users" => !empty($department_user) ? $department_user : null,
            "created_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
            "updated_at" => new MongoDB\BSON\UTCDateTime(strtotime(date("d-m-Y H:i:s")) * 1000),
        );
        $this->department_model->insert($post_data);
        $this->mongo_db->command(array(
            'update' => $this->table,
            'updates' => [
                array(
                    'q' => array('department_id' => $dept->department_id),
                    'u' => array(
                        'department_id' => $dept->department_id,
                        'department_name' => $dept->department_name,
                        'total_received' => $dept->total_received,
                        'delivered' => $dept->delivered,
                        'rejected' => $dept->rejected,
                        'pit' => $dept->pit,
                        'pending' => $dept->pending,
                        'pbt' => $dept->pbt,
                        'timely_delivered' => $dept->timely_delivered,
                        'mean' => $dept->mean,
                        'median' => $dept->median,
                    ),
                    'upsert' => true,
                    'multi' => false
                )

            ],

        ));
    }

 } 
}
