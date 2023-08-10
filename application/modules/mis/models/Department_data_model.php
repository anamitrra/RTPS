<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Department_data_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection("applications");
    }

    // Calculate pending in time for all applications
    public function calculate_pending_in_time($department_id='')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                ),
            ),
            array(
                '$match' => array(
                    "execution_data.0.official_form_details.action" => array(
                        '$nin' => ["Deliver", "Reject"],
                    ),
                ),
            ),
            array(
                '$lookup' => array(
                    'from' => 'services',
                    'localField' => 'initiated_data.base_service_id',
                    'foreignField' => 'service_id',
                    'as' => 'services',
                ),
            ),
            array(
                '$project' => array(
                    'base_service_id' => '$initiated_data.base_service_id',
                    'submission_date' => '$initiated_data.submission_date',
                    'timeline_obj' => array(
                        '$arrayElemAt' => ['$services', 0],
                    ),
                ),
            ),
            array(
                '$project' => array(
                    'base_service_id' => 1,
                    'submission_date' => 1,
                    'timeline' => array(
                        '$toInt' => '$timeline_obj.service_timeline',
                    ),
                    'expr_date' => array(
                        '$add' => ['$submission_date', array(
                            '$multiply' => [array(
                                '$toInt' => '$timeline_obj.service_timeline',
                            ), 24 * 60 * 60000],
                        )],
                    ),
                ),
            ),
            array(
                '$match' => array(
                    '$expr' => array(
                        '$lte' => ['$$NOW', '$expr_date'],
                    ),
                ),
            ),
            array(
                '$count' => 'total',
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        // pre($data);

        return $data[0]->total ?? 0;
    }

    // Calculate delivered in time for all applications
    public function calculate_delivered_in_time($department_id='')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                ),
            ),
            array(
                '$match' => array(
                    "execution_data.0.official_form_details.action" => 'Deliver',
                ),
            ),
            array(
                '$lookup' => array(
                    'from' => 'services',
                    'localField' => 'initiated_data.base_service_id',
                    'foreignField' => 'service_id',
                    'as' => 'services',
                ),
            ),
            array(
                '$project' => array(
                    'base_service_id' => '$initiated_data.base_service_id',
                    'submission_date' => '$initiated_data.submission_date',
                    'timeline_obj' => array(
                        '$arrayElemAt' => ['$services', 0],
                    ),
                    'exe_obj' => array(
                        '$arrayElemAt' => ['$execution_data', 0],
                    ),
                ),
            ),
            array(
                '$project' => array(
                    'base_service_id' => 1,
                    'submission_date' => 1,
                    'timeline' => array(
                        '$toInt' => '$timeline_obj.service_timeline',
                    ),
                    'expr_date' => array(
                        '$add' => ['$submission_date', array(
                            '$multiply' => [array(
                                '$toInt' => '$timeline_obj.service_timeline',
                            ), 24 * 60 * 60000],
                        )],
                    ),
                    'del_date' => '$exe_obj.task_details.executed_time',
                ),
            ),
            array(
                '$match' => array(
                    '$expr' => array(
                        '$lte' => ['$del_date', '$expr_date'],
                    ),
                ),
            ),
            array(
                '$count' => 'total',
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        // pre($data);

        return $data[0]->total ?? 0;
    }

    // Calculate gender data for all applications
    public function total_applications_gender_wise($department_id='')
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

    public function total_services_group_by_service($department_id='')
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
    public function services_delivered_group_by_service($department_id='')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    '$and' => [
                        array('initiated_data.department_id' => $department_id),
                        array('execution_data.0.official_form_details.action' => 'Deliver'),
                    ],

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
    public function delivered_in_time_group_by_service($department_id='')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    '$and' => [
                        array('initiated_data.department_id' => $department_id),
                        array('execution_data.0.official_form_details.action' => 'Deliver'),
                    ],

                ),
            ),
            array(
                '$lookup' => array(
                    'from' => 'services',
                    'localField' => 'initiated_data.base_service_id',
                    'foreignField' => 'service_id',
                    'as' => 'services',
                ),
            ),
            array(
                '$unwind' => '$services',
            ),
            array(
                '$project' => array(
                    'initiated_data' => 1,
                    'expr_date' => array(
                        '$add' => ['$initiated_data.submission_date', array(
                            '$multiply' => [array(
                                '$toInt' => '$services.service_timeline',
                            ), 24 * 60 * 60000],
                        )],
                    ),
                    'exe_obj' => array(
                        '$arrayElemAt' => ['$execution_data', 0],
                    ),
                ),
            ),
            array(
                '$project' => array(
                    'initiated_data' => 1,
                    'expr_date' => 1,
                    'del_date' => '$exe_obj.task_details.executed_time',
                ),
            ),
            array(
                '$match' => array(
                    '$expr' => array(
                        '$lte' => ['$del_date', '$expr_date'],
                    ),
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
    public function services_rejected_group_by_service($department_id='')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    '$and' => [
                        array('initiated_data.department_id' => $department_id),
                        array('execution_data.0.official_form_details.action' => 'Reject'),
                    ],

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
    public function rejected_in_time_group_by_service($department_id='')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    '$and' => [
                        array('initiated_data.department_id' => $department_id),
                        array('execution_data.0.official_form_details.action' => 'Reject'),
                    ],

                ),
            ),
            array(
                '$lookup' => array(
                    'from' => 'services',
                    'localField' => 'initiated_data.base_service_id',
                    'foreignField' => 'service_id',
                    'as' => 'services',
                ),
            ),
            array(
                '$unwind' => '$services',
            ),
            array(
                '$project' => array(
                    'initiated_data' => 1,
                    'expr_date' => array(
                        '$add' => ['$initiated_data.submission_date', array(
                            '$multiply' => [array(
                                '$toInt' => '$services.service_timeline',
                            ), 24 * 60 * 60000],
                        )],
                    ),
                    'exe_obj' => array(
                        '$arrayElemAt' => ['$execution_data', 0],
                    ),
                ),
            ),
            array(
                '$project' => array(
                    'initiated_data' => 1,
                    'expr_date' => 1,
                    'rej_date' => '$exe_obj.task_details.executed_time',
                ),
            ),
            array(
                '$match' => array(
                    '$expr' => array(
                        '$lte' => ['$rej_date', '$expr_date'],
                    ),
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
    public function services_pending_group_by_service($department_id='')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    '$and' => [
                        array('initiated_data.department_id' => $department_id),
                        array(
                            "execution_data.0.official_form_details.action" => array(
                                '$nin' => ["Deliver", "Reject"],
                            )),
                    ],

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
    public function pending_in_time_group_by_service($department_id='')
    {
        $collection = 'applications';
        $operations = array(
            array(
                '$match' => array(
                    '$and' => [
                        array('initiated_data.department_id' => $department_id),
                        array(
                            "execution_data.0.official_form_details.action" => array(
                                '$nin' => ["Deliver", "Reject"],
                            )),
                    ],

                ),
            ),
            array(
                '$lookup' => array(
                    'from' => 'services',
                    'localField' => 'initiated_data.base_service_id',
                    'foreignField' => 'service_id',
                    'as' => 'services',
                ),
            ),
            array(
                '$unwind' => '$services',
            ),
            array(
                '$project' => array(
                    'initiated_data' => 1,
                    'expr_date' => array(
                        '$add' => ['$initiated_data.submission_date', array(
                            '$multiply' => [array(
                                '$toInt' => '$services.service_timeline',
                            ), 24 * 60 * 60000],
                        )],
                    ),
                ),
            ),
            array(
                '$match' => array(
                    '$expr' => array(
                        '$lte' => ['$$NOW', '$expr_date'],
                    ),
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
                ),
            ),
            array(
                '$project' => [
                    'first' => [
                        '$arrayElemAt' => ['$execution_data', 0],
                    ],
                    'initiated_data' => 1,
                ],
            ),
            array(
                '$match' =>
                ['$expr' => [
                    '$eq' => [
                        '$first.official_form_details.action', 'Deliver',
                    ],
                ]],
            ),
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
                ),
            ),
            array(
                '$project' => [
                    'first' => [
                        '$arrayElemAt' => ['$execution_data', 0],
                    ],
                    'initiated_data' => 1,
                ],
            ),
            array(
                '$match' =>
                ['$expr' => [
                    '$eq' => [
                        '$first.official_form_details.action', 'Reject',
                    ],
                ]],
            ),
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
                    '$and' => [
                        array('initiated_data.department_id' => $department_id),
                        array('execution_data.0.official_form_details.action' => 'Deliver'),
                    ],

                ),
            ),
            array(
                '$lookup' => array(
                    'from' => 'services',
                    'localField' => 'initiated_data.base_service_id',
                    'foreignField' => 'service_id',
                    'as' => 'services',
                ),
            ),
            array(
                '$unwind' => '$services',
            ),
            array(
                '$project' => array(
                    'initiated_data' => 1,
                    'expr_date' => array(
                        '$add' => ['$initiated_data.submission_date', array(
                            '$multiply' => [array(
                                '$toInt' => '$services.service_timeline',
                            ), 24 * 60 * 60000],
                        )],
                    ),
                    'exe_obj' => array(
                        '$arrayElemAt' => ['$execution_data', 0],
                    ),
                ),
            ),
            array(
                '$project' => array(
                    'initiated_data' => 1,
                    'expr_date' => 1,
                    'del_date' => '$exe_obj.task_details.executed_time',
                ),
            ),
            array(
                '$match' => array(
                    '$expr' => array(
                        '$lte' => ['$del_date', '$expr_date'],
                    ),
                ),
            ),
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
                    '$and' => [
                        array('initiated_data.department_id' => $department_id),
                        array('execution_data.0.official_form_details.action' => 'Reject'),
                    ],

                ),
            ),
            array(
                '$lookup' => array(
                    'from' => 'services',
                    'localField' => 'initiated_data.base_service_id',
                    'foreignField' => 'service_id',
                    'as' => 'services',
                ),
            ),
            array(
                '$unwind' => '$services',
            ),
            array(
                '$project' => array(
                    'initiated_data' => 1,
                    'expr_date' => array(
                        '$add' => ['$initiated_data.submission_date', array(
                            '$multiply' => [array(
                                '$toInt' => '$services.service_timeline',
                            ), 24 * 60 * 60000],
                        )],
                    ),
                    'exe_obj' => array(
                        '$arrayElemAt' => ['$execution_data', 0],
                    ),
                ),
            ),
            array(
                '$project' => array(
                    'initiated_data' => 1,
                    'expr_date' => 1,
                    'rej_date' => '$exe_obj.task_details.executed_time',
                ),
            ),
            array(
                '$match' => array(
                    '$expr' => array(
                        '$lte' => ['$rej_date', '$expr_date'],
                    ),
                ),
            ),
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
                ),
            ),
            array(
                '$project' => [
                    'first' => [
                        '$arrayElemAt' => ['$execution_data', 0],
                    ],
                    'initiated_data' => 1,
                ],
            ),
            array(
                '$lookup' => array(
                    'from' => 'services',
                    'localField' => 'initiated_data.base_service_id',
                    'foreignField' => 'service_id',
                    'as' => 'service',
                ),
            ),
            array('$unwind' => '$service'),
            array(
                '$match' => array(
                    '$and' => [
                        ['$expr' => [
                            '$lte' => [
                                '$$NOW', array(
                                    '$add' => [
                                        '$initiated_data.submission_date', array(
                                            '$multiply' => [
                                                array('$toInt' => '$service.service_timeline'),
                                                24 * 60 * 60000,
                                            ],
                                        ),
                                    ],
                                ),
                            ],
                        ]],
                        ['$expr' => [
                            '$ne' => [
                                '$first.official_form_details.action', 'Reject',
                            ],
                        ]],
                        ['$expr' => [
                            '$ne' => [
                                '$first.official_form_details.action', 'Deliver',
                            ],
                        ]],

                    ],
                ),
            ),
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

}
