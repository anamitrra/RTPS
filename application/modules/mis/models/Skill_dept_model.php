<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Skill_dept_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection("applications");
    }

    // Calculate pending in time for all applications
    public function calculate_pending_in_time($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    "initiated_data.appl_status" => array('$nin' => ["D", "R"]),
                    "initiated_data.pit" => 1,
                ),
            ),
            // array(
            //     '$match' => array(
            //         "execution_data.0.official_form_details.action" => array(
            //             '$nin' => ["Deliver", "Reject"],
            //         ),
            //     ),
            // ),
            // array(
            //     '$lookup' => array(
            //         'from' => 'services',
            //         'localField' => 'initiated_data.base_service_id',
            //         'foreignField' => 'service_id',
            //         'as' => 'services',
            //     ),
            // ),
            // array(
            //     '$project' => array(
            //         'base_service_id' => '$initiated_data.base_service_id',
            //         'submission_date' => '$initiated_data.submission_date',
            //         'timeline_obj' => array(
            //             '$arrayElemAt' => ['$services', 0],
            //         ),
            //     ),
            // ),
            // array(
            //     '$project' => array(
            //         'base_service_id' => 1,
            //         'submission_date' => 1,
            //         'timeline' => array(
            //             '$toInt' => '$timeline_obj.service_timeline',
            //         ),
            //         'expr_date' => array(
            //             '$add' => ['$submission_date', array(
            //                 '$multiply' => [array(
            //                     '$toInt' => '$timeline_obj.service_timeline',
            //                 ), 24 * 60 * 60000],
            //             )],
            //         ),
            //     ),
            // ),
            // array(
            //     '$match' => array(
            //         '$expr' => array(
            //             '$lte' => ['$$NOW', '$expr_date'],
            //         ),
            //     ),
            // ),
            array(
                '$count' => 'total',
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        // pre($data);

        return $data[0]->total ?? 0;
    }

    // Calculate delivered in time for all applications
    public function calculate_delivered_in_time($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    "initiated_data.appl_status" => 'D',
                    "initiated_data.dit" => 1,
                ),
            ),
            // array(
            //     '$match' => array(
            //         "execution_data.0.official_form_details.action" => 'Deliver',
            //     ),
            // ),
            // array(
            //     '$lookup' => array(
            //         'from' => 'services',
            //         'localField' => 'initiated_data.base_service_id',
            //         'foreignField' => 'service_id',
            //         'as' => 'services',
            //     ),
            // ),
            // array(
            //     '$project' => array(
            //         'base_service_id' => '$initiated_data.base_service_id',
            //         'submission_date' => '$initiated_data.submission_date',
            //         'timeline_obj' => array(
            //             '$arrayElemAt' => ['$services', 0],
            //         ),
            //         'exe_obj' => array(
            //             '$arrayElemAt' => ['$execution_data', 0],
            //         ),
            //     ),
            // ),
            // array(
            //     '$project' => array(
            //         'base_service_id' => 1,
            //         'submission_date' => 1,
            //         'timeline' => array(
            //             '$toInt' => '$timeline_obj.service_timeline',
            //         ),
            //         'expr_date' => array(
            //             '$add' => ['$submission_date', array(
            //                 '$multiply' => [array(
            //                     '$toInt' => '$timeline_obj.service_timeline',
            //                 ), 24 * 60 * 60000],
            //             )],
            //         ),
            //         'del_date' => '$exe_obj.task_details.executed_time',
            //     ),
            // ),
            // array(
            //     '$match' => array(
            //         '$expr' => array(
            //             '$lte' => ['$del_date', '$expr_date'],
            //         ),
            //     ),
            // ),
            array(
                '$count' => 'total',
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        // pre($data);

        return $data[0]->total ?? 0;
    }

    // Calculate gender data for all applications
    public function total_applications_gender_wise($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.attribute_details.applicant_gender',
                    'gender' => ['$first' => '$initiated_data.attribute_details.applicant_gender'],
                    'total_received' => array('$sum' => array('$toInt' => 1)),
                ),
            ),
            array(
                '$sort' => ['total_received' => -1],
            ),
        );
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array) $data;
    }

    /* Servicewise applications count */

    public function total_services_group_by_service($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'service_name' => array('$first' => '$initiated_data.service_name'),
                    'count' => array('$sum' => array('$toInt' => 1)),
                ),
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }

    public function services_delivered_group_by_service($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    "initiated_data.appl_status" => 'D',
                )
            ),

            // array(
            //     '$match' => array(
            //         '$and' => [
            //             array('initiated_data.department_id' => $department_id),
            //             array('execution_data.0.official_form_details.action' => 'Deliver'),
            //         ],
            //     ),
            // ),

            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'service_name' => array('$first' => '$initiated_data.service_name'),
                    'count' => array('$sum' => array('$toInt' => 1)),
                ),
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }
    public function delivered_in_time_group_by_service($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    "initiated_data.appl_status" => 'D',
                    "initiated_data.dit" => 1,
                ),
            ),
            // array(
            //     '$lookup' => array(
            //         'from' => 'services',
            //         'localField' => 'initiated_data.base_service_id',
            //         'foreignField' => 'service_id',
            //         'as' => 'services',
            //     ),
            // ),
            // array(
            //     '$unwind' => '$services',
            // ),
            // array(
            //     '$project' => array(
            //         'initiated_data' => 1,
            //         'expr_date' => array(
            //             '$add' => ['$initiated_data.submission_date', array(
            //                 '$multiply' => [array(
            //                     '$toInt' => '$services.service_timeline',
            //                 ), 24 * 60 * 60000],
            //             )],
            //         ),
            //         'exe_obj' => array(
            //             '$arrayElemAt' => ['$execution_data', 0],
            //         ),
            //     ),
            // ),
            // array(
            //     '$project' => array(
            //         'initiated_data' => 1,
            //         'expr_date' => 1,
            //         'del_date' => '$exe_obj.task_details.executed_time',
            //     ),
            // ),
            // array(
            //     '$match' => array(
            //         '$expr' => array(
            //             '$lte' => ['$del_date', '$expr_date'],
            //         ),
            //     ),
            // ),

            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'service_name' => array('$first' => '$initiated_data.service_name'),
                    'count' => array('$sum' => array('$toInt' => 1)),
                ),
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }
    public function services_rejected_group_by_service($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    "initiated_data.appl_status" => 'R',
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'service_name' => array('$first' => '$initiated_data.service_name'),
                    'count' => array('$sum' => array('$toInt' => 1)),
                ),
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }
    public function rejected_in_time_group_by_service($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    "initiated_data.appl_status" => 'R',
                    "initiated_data.rit" => 1,
                ),
            ),
            // array(
            //     '$lookup' => array(
            //         'from' => 'services',
            //         'localField' => 'initiated_data.base_service_id',
            //         'foreignField' => 'service_id',
            //         'as' => 'services',
            //     ),
            // ),
            // array(
            //     '$unwind' => '$services',
            // ),
            // array(
            //     '$project' => array(
            //         'initiated_data' => 1,
            //         'expr_date' => array(
            //             '$add' => ['$initiated_data.submission_date', array(
            //                 '$multiply' => [array(
            //                     '$toInt' => '$services.service_timeline',
            //                 ), 24 * 60 * 60000],
            //             )],
            //         ),
            //         'exe_obj' => array(
            //             '$arrayElemAt' => ['$execution_data', 0],
            //         ),
            //     ),
            // ),
            // array(
            //     '$project' => array(
            //         'initiated_data' => 1,
            //         'expr_date' => 1,
            //         'rej_date' => '$exe_obj.task_details.executed_time',
            //     ),
            // ),
            // array(
            //     '$match' => array(
            //         '$expr' => array(
            //             '$lte' => ['$rej_date', '$expr_date'],
            //         ),
            //     ),
            // ),

            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'service_name' => array('$first' => '$initiated_data.service_name'),
                    'count' => array('$sum' => array('$toInt' => 1)),
                ),
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }
    public function services_pending_group_by_service($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    "initiated_data.appl_status" => array('$nin' => ["D", "R"]),
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'service_name' => array('$first' => '$initiated_data.service_name'),
                    'count' => array('$sum' => array('$toInt' => 1)),
                ),
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }
    public function pending_in_time_group_by_service($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    "initiated_data.appl_status" => array('$nin' => ["D", "R"]),
                    "initiated_data.pit" => 1,
                ),
            ),
            // array(
            //     '$lookup' => array(
            //         'from' => 'services',
            //         'localField' => 'initiated_data.base_service_id',
            //         'foreignField' => 'service_id',
            //         'as' => 'services',
            //     ),
            // ),
            // array(
            //     '$unwind' => '$services',
            // ),
            // array(
            //     '$project' => array(
            //         'initiated_data' => 1,
            //         'expr_date' => array(
            //             '$add' => ['$initiated_data.submission_date', array(
            //                 '$multiply' => [array(
            //                     '$toInt' => '$services.service_timeline',
            //                 ), 24 * 60 * 60000],
            //             )],
            //         ),
            //     ),
            // ),
            // array(
            //     '$match' => array(
            //         '$expr' => array(
            //             '$lte' => ['$$NOW', '$expr_date'],
            //         ),
            //     ),
            // ),

            array(
                '$group' => array(
                    '_id' => '$initiated_data.base_service_id',
                    'service_name' => array('$first' => '$initiated_data.service_name'),
                    'count' => array('$sum' => array('$toInt' => 1)),
                ),
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }

    /* Officewise applications count */

    public function total_services_group_by_office($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.submission_location',
                    'submission_location' => ['$first' => '$initiated_data.submission_location'],
                    'department_name' => ['$first' => '$initiated_data.department_name'],
                    'department_id' => ['$first' => '$initiated_data.department_id'],
                    'received' => array('$sum' => array('$toInt' => 1)),
                ),
            ),
            array(
                '$sort' => ['submission_location' => 1],
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }

    public function services_delivered_group_by_office($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    "initiated_data.appl_status" => 'D',
                ),
            ),
            // array(
            //     '$project' => [
            //         'first' => [
            //             '$arrayElemAt' => ['$execution_data', 0],
            //         ],
            //         'initiated_data' => 1,
            //     ],
            // ),
            // array(
            //     '$match' =>
            //     ['$expr' => [
            //         '$eq' => [
            //             '$first.official_form_details.action', 'Deliver',
            //         ],
            //     ]],
            // ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.submission_location',
                    'submission_location' => ['$first' => '$initiated_data.submission_location'],
                    'count' => array('$sum' => array('$toInt' => 1)),
                ),
            ),
            array(
                '$sort' => ['submission_location' => 1],
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }

    public function services_rejected_group_by_office($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    "initiated_data.appl_status" => 'R',
                ),
            ),
            // array(
            //     '$project' => [
            //         'first' => [
            //             '$arrayElemAt' => ['$execution_data', 0],
            //         ],
            //         'initiated_data' => 1,
            //     ],
            // ),
            // array(
            //     '$match' =>
            //     ['$expr' => [
            //         '$eq' => [
            //             '$first.official_form_details.action', 'Reject',
            //         ],
            //     ]],
            // ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.submission_location',
                    'submission_location' => ['$first' => '$initiated_data.submission_location'],
                    'count' => array('$sum' => array('$toInt' => 1)),
                ),
            ),
            array(
                '$sort' => ['submission_location' => 1],
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }

    public function delivered_in_time_group_by_office($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    "initiated_data.appl_status" => 'D',
                    "initiated_data.dit" => 1,
                ),
            ),
            // array(
            //     '$lookup' => array(
            //         'from' => 'services',
            //         'localField' => 'initiated_data.base_service_id',
            //         'foreignField' => 'service_id',
            //         'as' => 'services',
            //     ),
            // ),
            // array(
            //     '$unwind' => '$services',
            // ),
            // array(
            //     '$project' => array(
            //         'initiated_data' => 1,
            //         'expr_date' => array(
            //             '$add' => ['$initiated_data.submission_date', array(
            //                 '$multiply' => [array(
            //                     '$toInt' => '$services.service_timeline',
            //                 ), 24 * 60 * 60000],
            //             )],
            //         ),
            //         'exe_obj' => array(
            //             '$arrayElemAt' => ['$execution_data', 0],
            //         ),
            //     ),
            // ),
            // array(
            //     '$project' => array(
            //         'initiated_data' => 1,
            //         'expr_date' => 1,
            //         'del_date' => '$exe_obj.task_details.executed_time',
            //     ),
            // ),
            // array(
            //     '$match' => array(
            //         '$expr' => array(
            //             '$lte' => ['$del_date', '$expr_date'],
            //         ),
            //     ),
            // ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.submission_location',
                    'submission_location' => ['$first' => '$initiated_data.submission_location'],
                    'count' => array('$sum' => array('$toInt' => 1)),
                ),
            ),
            array(
                '$sort' => ['submission_location' => 1],
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }

    public function rejected_in_time_group_by_office($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    "initiated_data.appl_status" => 'R',
                    "initiated_data.rit" => 1,

                ),
            ),
            // array(
            //     '$lookup' => array(
            //         'from' => 'services',
            //         'localField' => 'initiated_data.base_service_id',
            //         'foreignField' => 'service_id',
            //         'as' => 'services',
            //     ),
            // ),
            // array(
            //     '$unwind' => '$services',
            // ),
            // array(
            //     '$project' => array(
            //         'initiated_data' => 1,
            //         'expr_date' => array(
            //             '$add' => ['$initiated_data.submission_date', array(
            //                 '$multiply' => [array(
            //                     '$toInt' => '$services.service_timeline',
            //                 ), 24 * 60 * 60000],
            //             )],
            //         ),
            //         'exe_obj' => array(
            //             '$arrayElemAt' => ['$execution_data', 0],
            //         ),
            //     ),
            // ),
            // array(
            //     '$project' => array(
            //         'initiated_data' => 1,
            //         'expr_date' => 1,
            //         'rej_date' => '$exe_obj.task_details.executed_time',
            //     ),
            // ),
            // array(
            //     '$match' => array(
            //         '$expr' => array(
            //             '$lte' => ['$rej_date', '$expr_date'],
            //         ),
            //     ),
            // ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.submission_location',
                    'submission_location' => ['$first' => '$initiated_data.submission_location'],
                    'count' => array('$sum' => array('$toInt' => 1)),
                ),
            ),
            array(
                '$sort' => ['submission_location' => 1],
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }

    public function pending_in_time_group_by_office($department_id = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    "initiated_data.appl_status" => array('$nin' => ["D", "R"]),
                    "initiated_data.pit" => 1,
                ),
            ),
            // array(
            //     '$project' => [
            //         'first' => [
            //             '$arrayElemAt' => ['$execution_data', 0],
            //         ],
            //         'initiated_data' => 1,
            //     ],
            // ),
            // array(
            //     '$lookup' => array(
            //         'from' => 'services',
            //         'localField' => 'initiated_data.base_service_id',
            //         'foreignField' => 'service_id',
            //         'as' => 'service',
            //     ),
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
            //                                     24 * 60 * 60000,
            //                                 ],
            //                             ),
            //                         ],
            //                     ),
            //                 ],
            //             ]],
            //             ['$expr' => [
            //                 '$ne' => [
            //                     '$first.official_form_details.action', 'Reject',
            //                 ],
            //             ]],
            //             ['$expr' => [
            //                 '$ne' => [
            //                     '$first.official_form_details.action', 'Deliver',
            //                 ],
            //             ]],

            //         ],
            //     ),
            // ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.submission_location',
                    'submission_location' => ['$first' => '$initiated_data.submission_location'],
                    'count' => array('$sum' => array('$toInt' => 1)),
                ),
            ),
            array(
                '$sort' => ['submission_location' => 1],
            ),
        );
        $data = (array) $this->mongo_db->aggregate($collection, $operations);

        return $data;
    }

    /* Reports */

    public function all_exchanges($department_id = '', $service_id = '', $from = '', $to = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    'initiated_data.base_service_id' => array('$in' => explode('|', $service_id)),
                ),
            ),
            array(
                '$match' => array(
                    '$expr' => array(
                        '$and' => [
                            array(
                                '$gte' => [
                                    '$initiated_data.submission_date',
                                    array('$toDate' => $from),
                                ],
                            ),
                            array(
                                '$lte' => [
                                    '$initiated_data.submission_date',
                                    array(
                                        '$add' => [array('$toDate' => $to), 1 * 24 * 60 * 60000],
                                    ),
                                ],
                            ),
                        ],
                    ),
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.attribute_details.employment_exchange',
                    'total_male' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Male'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                    'total_female' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Female'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                    'total_others' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Others'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                ),
            ),
            array(
                '$addFields' => array(
                    'caste_wise' => [
                        array(
                            '_id' => 'General',
                            'male' => 0,
                            'female' => 0,
                            'others' => 0,
                        ),
                        array(
                            '_id' => 'SC',
                            'male' => 0,
                            'female' => 0,
                            'others' => 0,
                        ),
                        array(
                            '_id' => 'ST(P)',
                            'male' => 0,
                            'female' => 0,
                            'others' => 0,
                        ),
                        array(
                            '_id' => 'ST(H)',
                            'male' => 0,
                            'female' => 0,
                        ),
                        array(
                            '_id' => 'OBC/MOBC',
                            'male' => 0,
                            'female' => 0,
                            'others' => 0,
                        ),
                    ],
                ),
            ),
            array(
                '$sort' => ['_id' => 1],
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }

    public function gender_exchangewise($department_id = '', $service_id = '', $exchange = '', $from = '', $to = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    'initiated_data.base_service_id' => array('$in' => explode('|', $service_id)),
                    'initiated_data.attribute_details.employment_exchange' => $exchange,
                ),
            ),
            array(
                '$match' => array(
                    '$expr' => array(
                        '$and' => [
                            array(
                                '$gte' => [
                                    '$initiated_data.submission_date',
                                    array('$toDate' => $from),
                                ],
                            ),
                            array(
                                '$lte' => [
                                    '$initiated_data.submission_date',
                                    array(
                                        '$add' => [array('$toDate' => $to), 1 * 24 * 60 * 60000],
                                    ),
                                ],
                            ),
                        ],
                    ),
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.attribute_details.caste',
                    'male' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Male'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                    'female' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Female'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                    'others' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Others'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                ),
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }

    public function all_qualifications($department_id = '', $service_id = '', $from = '', $to = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    'initiated_data.base_service_id' => array('$in' => explode('|', $service_id)),
                ),
            ),
            array(
                '$match' => array(
                    '$expr' => array(
                        '$and' => [
                            array(
                                '$gte' => [
                                    '$initiated_data.submission_date',
                                    array('$toDate' => $from),
                                ],
                            ),
                            array(
                                '$lte' => [
                                    '$initiated_data.submission_date',
                                    array(
                                        '$add' => [array('$toDate' => $to), 1 * 24 * 60 * 60000],
                                    ),
                                ],
                            ),
                        ],
                    ),
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.attribute_details.highest_educational_level',
                    'total_male' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Male'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                    'total_female' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Female'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                    'total_others' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Others'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                ),
            ),
            array(
                '$addFields' => array(
                    'caste_wise' => [
                        array(
                            '_id' => 'General',
                            'male' => 0,
                            'female' => 0,
                            'others' => 0,
                        ),
                        array(
                            '_id' => 'SC',
                            'male' => 0,
                            'female' => 0,
                            'others' => 0,
                        ),
                        array(
                            '_id' => 'ST(P)',
                            'male' => 0,
                            'female' => 0,
                            'others' => 0,
                        ),
                        array(
                            '_id' => 'ST(H)',
                            'male' => 0,
                            'female' => 0,
                        ),
                        array(
                            '_id' => 'OBC/MOBC',
                            'male' => 0,
                            'female' => 0,
                            'others' => 0,
                        ),
                    ],
                ),
            ),
            array(
                '$sort' => ['_id' => 1],
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }

    public function gender_qualificationwise($department_id = '', $service_id = '', $qualification = '', $from = '', $to = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    'initiated_data.base_service_id' => array('$in' => explode('|', $service_id)),
                    'initiated_data.attribute_details.highest_educational_level' => $qualification,
                ),
            ),
            array(
                '$match' => array(
                    '$expr' => array(
                        '$and' => [
                            array(
                                '$gte' => [
                                    '$initiated_data.submission_date',
                                    array('$toDate' => $from),
                                ],
                            ),
                            array(
                                '$lte' => [
                                    '$initiated_data.submission_date',
                                    array(
                                        '$add' => [array('$toDate' => $to), 1 * 24 * 60 * 60000],
                                    ),
                                ],
                            ),
                        ],
                    ),
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.attribute_details.caste',
                    'male' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Male'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                    'female' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Female'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                    'others' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Others'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                ),
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }

    public function all_employments($department_id = '', $service_id = '', $from = '', $to = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    'initiated_data.base_service_id' => array('$in' => explode('|', $service_id)),
                ),
            ),
            array(
                '$match' => array(
                    '$expr' => array(
                        '$and' => [
                            array(
                                '$gte' => [
                                    '$initiated_data.submission_date',
                                    array('$toDate' => $from),
                                ],
                            ),
                            array(
                                '$lte' => [
                                    '$initiated_data.submission_date',
                                    array(
                                        '$add' => [array('$toDate' => $to), 1 * 24 * 60 * 60000],
                                    ),
                                ],
                            ),
                        ],
                    ),
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.attribute_details.current_employment_status',
                    'total_male' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Male'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                    'total_female' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Female'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                    'total_others' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Others'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                ),
            ),
            array(
                '$addFields' => array(
                    'caste_wise' => [
                        array(
                            '_id' => 'General',
                            'male' => 0,
                            'female' => 0,
                            'others' => 0,
                        ),
                        array(
                            '_id' => 'SC',
                            'male' => 0,
                            'female' => 0,
                            'others' => 0,
                        ),
                        array(
                            '_id' => 'ST(P)',
                            'male' => 0,
                            'female' => 0,
                            'others' => 0,
                        ),
                        array(
                            '_id' => 'ST(H)',
                            'male' => 0,
                            'female' => 0,
                        ),
                        array(
                            '_id' => 'OBC/MOBC',
                            'male' => 0,
                            'female' => 0,
                            'others' => 0,
                        ),
                    ],
                ),
            ),
            array(
                '$sort' => ['_id' => 1],
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }

    public function gender_employmentwise($department_id = '', $service_id = '', $employment = '', $from = '', $to = '')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    'initiated_data.base_service_id' => array('$in' => explode('|', $service_id)),
                    'initiated_data.attribute_details.current_employment_status' => $employment,
                ),
            ),
            array(
                '$match' => array(
                    '$expr' => array(
                        '$and' => [
                            array(
                                '$gte' => [
                                    '$initiated_data.submission_date',
                                    array('$toDate' => $from),
                                ],
                            ),
                            array(
                                '$lte' => [
                                    '$initiated_data.submission_date',
                                    array(
                                        '$add' => [array('$toDate' => $to), 1 * 24 * 60 * 60000],
                                    ),
                                ],
                            ),
                        ],
                    ),
                ),
            ),
            array(
                '$group' => array(
                    '_id' => '$initiated_data.attribute_details.caste',
                    'male' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Male'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                    'female' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Female'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                    'others' => array(
                        '$sum' => array(
                            '$cond' => [
                                array(
                                    '$eq' => ['$initiated_data.attribute_details.applicant_gender', 'Others'],
                                ),
                                1, 0,
                            ],
                        ),
                    ),
                ),
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }

    public function get_applicant_details($field, $value, $department_id)
    {
        switch ($field) {
            case 'REG_NO':

                $data = (array) $this->first_where(array(
                    'initiated_data.department_id' => $department_id,
                    '$or' => [
                        ["initiated_data.attribute_details.registration_no" => $value],
                        ["execution_data.0.official_form_details.registration_no" => $value]
                    ]
                ));

                return $data;

            case 'APPL_REF_NO':
                $data = (array) $this->first_where(array(
                    'initiated_data.department_id' => $department_id,
                    'initiated_data.appl_ref_no' => $value
                ));

                return $data;

            default:
                return [];
        }
    }

    // Submitted applications count monthwise by year
    public function get_data_monthwise_by_year($department_id, $year, $service_id = array())
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    'initiated_data.base_service_id' => array('$in' => $service_id),
                ),
            ),
            array(
                '$addFields' => array(
                    'initiated_data.submission_year' => ['$year' => ['date' => '$initiated_data.submission_date', 'timezone' => "Asia/Kolkata"]],
                    'initiated_data.submission_month' => ['$arrayElemAt' => [
                        ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                        ['$month' => ['date' => '$initiated_data.submission_date', 'timezone' => "Asia/Kolkata"]]
                    ]],
                    'initiated_data.submission_month_code' => ['$month' => ['date' => '$initiated_data.submission_date', 'timezone' => "Asia/Kolkata"]],
                    'initiated_data.last_action_date' => ['$dateFromString' => ['dateString' => '$initiated_data.execution_date_str', 'format' => '%d-%m-%Y', 'timezone' => 'Asia/Kolkata', 'onError' => '$initiated_data.submission_date', 'onNull' => '$initiated_data.submission_date']]
                )
            ),
            array(
                '$addFields' => array(
                    'initiated_data.last_action_year' => ['$year' => ['date' => '$initiated_data.last_action_date', 'timezone' => "Asia/Kolkata"]],
                    'initiated_data.last_action_month_code' => ['$month' => ['date' => '$initiated_data.last_action_date', 'timezone' => "Asia/Kolkata"]],
                    'initiated_data.last_action_month' => ['$arrayElemAt' => [
                        ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                        ['$month' => ['date' => '$initiated_data.last_action_date', 'timezone' => "Asia/Kolkata"]]
                    ]],
                )
            ),
            array(
                '$facet' => [
                    'total_submission' => [
                        ['$match' => ['initiated_data.submission_year' => intval($year)]],
                        [
                            '$group' => array(
                                '_id' => '$initiated_data.submission_month',
                                'month' => ['$first' => '$initiated_data.submission_month_code'],
                                'total' => ['$sum' => 1]
                            )
                        ],
                        ['$sort' =>  ['month' => 1]],


                    ],
                    'total_action' => [
                        ['$match' => ['initiated_data.last_action_year' => intval($year), 'initiated_data.appl_status' => ['$in' => ['D', 'R']]]],
                        [
                            '$group' => array(
                                '_id' => '$initiated_data.last_action_month',
                                'month' => ['$first' => '$initiated_data.last_action_month_code'],
                                'delivered' => array(
                                    '$sum' => array(
                                        '$cond' => [
                                            array(
                                                '$eq' => ['$initiated_data.appl_status', 'D'],
                                            ),
                                            1, 0,
                                        ],
                                    ),
                                ),
                                'rejected' => array(
                                    '$sum' => array(
                                        '$cond' => [
                                            array(
                                                '$eq' => ['$initiated_data.appl_status', 'R'],
                                            ),
                                            1, 0,
                                        ],
                                    ),
                                ),

                            )
                        ],
                        ['$sort' =>  ['month' => 1]],
                    ],
                ]
            ),

        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }
}
