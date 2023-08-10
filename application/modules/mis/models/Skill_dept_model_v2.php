<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Skill_dept_model_v2 extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection("applications");
    }


    /* Reports */

    public function all_exchanges($department_id = '', $service_id = array(), $from = '', $to = '')
    {
        $collection = 'applications';
        $from_date = new MongoDB\BSON\UTCDateTime(strtotime($from) * 1000);
        $to_date = new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($to)) * 1000);

        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    'initiated_data.base_service_id' => array('$in' => $service_id),
                    'initiated_data.attribute_details.lgd_employment_exchange' => array('$ne' => 'Professional and Executive Office- Guwahati')
                ),
            ),
            array(
                '$match' => array(
                    'initiated_data.submission_date' => ['$gte' => $from_date],
                    'initiated_data.submission_date' => ['$lte' => $to_date],
                ),
            ),
            // array(
            //     '$match' => array(
            //         '$expr' => array(
            //             '$and' => [
            //                 array(
            //                     '$gte' => [
            //                         '$initiated_data.submission_date',
            //                         array('$toDate' => $from),
            //                     ],
            //                 ),
            //                 array(
            //                     '$lte' => [
            //                         '$initiated_data.submission_date',
            //                         array(
            //                             '$add' => [array('$toDate' => $to), 1 * 24 * 60 * 60000],
            //                         ),
            //                     ],
            //                 ),
            //             ],
            //         ),
            //     ),
            // ),
            array(
                '$group' => array(
                    '_id' =>  '$initiated_data.attribute_details.lgd_employment_exchange',
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
            // array(
            //     '$addFields' => array(
            //         'caste_wise' => [
            //             array(
            //                 '_id' => 'General',
            //                 'male' => 0,
            //                 'female' => 0,
            //                 'others' => 0,
            //             ),
            //             array(
            //                 '_id' => 'SC',
            //                 'male' => 0,
            //                 'female' => 0,
            //                 'others' => 0,
            //             ),
            //             array(
            //                 '_id' => 'ST(P)',
            //                 'male' => 0,
            //                 'female' => 0,
            //                 'others' => 0,
            //             ),
            //             array(
            //                 '_id' => 'ST(H)',
            //                 'male' => 0,
            //                 'female' => 0,
            //                 'others' => 0,
            //             ),
            //             array(
            //                 '_id' => 'OBC/MOBC',
            //                 'male' => 0,
            //                 'female' => 0,
            //                 'others' => 0,
            //             ),
            //         ],
            //     ),
            // ),
            array(
                '$sort' => ['_id' => 1],
            ),
        );

        $data = (array) $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }

    public function gender_exchangewise($department_id = '', $service_id = array(), $exchange = '', $from = '', $to = '')
    {
        $collection = 'applications';
        $from_date = new MongoDB\BSON\UTCDateTime(strtotime($from) * 1000);
        $to_date = new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($to)) * 1000);

        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    'initiated_data.base_service_id' => array('$in' => $service_id),
                    'initiated_data.attribute_details.lgd_employment_exchange' => $exchange,
                ),
            ),
            array(
                '$match' => array(
                    'initiated_data.submission_date' => ['$gte' => $from_date],
                    'initiated_data.submission_date' => ['$lte' => $to_date],
                ),
            ),
            // array(
            //     '$match' => array(
            //         '$expr' => array(
            //             '$and' => [
            //                 array(
            //                     '$gte' => [
            //                         '$initiated_data.submission_date',
            //                         array('$toDate' => $from),
            //                     ],
            //                 ),
            //                 array(
            //                     '$lte' => [
            //                         '$initiated_data.submission_date',
            //                         array(
            //                             '$add' => [array('$toDate' => $to), 1 * 24 * 60 * 60000],
            //                         ),
            //                     ],
            //                 ),
            //             ],
            //         ),
            //     ),
            // ),
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

    public function all_qualifications($department_id = '', $service_id = array(), $from = '', $to = '')
    {
        $collection = 'applications';
        $from_date = new MongoDB\BSON\UTCDateTime(strtotime($from) * 1000);
        $to_date = new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($to)) * 1000);

        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    'initiated_data.base_service_id' => array('$in' => $service_id),
                ),
            ),
            array(
                '$match' => array(
                    'initiated_data.submission_date' => ['$gte' => $from_date],
                    'initiated_data.submission_date' => ['$lte' => $to_date],
                ),
            ),
            // array(
            //     '$match' => array(
            //         '$expr' => array(
            //             '$and' => [
            //                 array(
            //                     '$gte' => [
            //                         '$initiated_data.submission_date',
            //                         array('$toDate' => $from),
            //                     ],
            //                 ),
            //                 array(
            //                     '$lte' => [
            //                         '$initiated_data.submission_date',
            //                         array(
            //                             '$add' => [array('$toDate' => $to), 1 * 24 * 60 * 60000],
            //                         ),
            //                     ],
            //                 ),
            //             ],
            //         ),
            //     ),
            // ),
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
                            'others' => 0,
                        ),
                        array(
                            '_id' => 'OBC/MOBC',
                            'male' => 0,
                            'female' => 0,
                            'others' => 0,
                        ),
                        array(
                            '_id' => 'ST',
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

    public function gender_qualificationwise($department_id = '', $service_id = array(), $qualification = '', $from = '', $to = '')
    {
        $collection = 'applications';
        $from_date = new MongoDB\BSON\UTCDateTime(strtotime($from) * 1000);
        $to_date = new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($to)) * 1000);

        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    'initiated_data.base_service_id' => array('$in' => $service_id),
                    'initiated_data.attribute_details.highest_educational_level' => $qualification,
                ),
            ),
            array(
                '$match' => array(
                    'initiated_data.submission_date' => ['$gte' => $from_date],
                    'initiated_data.submission_date' => ['$lte' => $to_date],
                ),
            ),
            // array(
            //     '$match' => array(
            //         '$expr' => array(
            //             '$and' => [
            //                 array(
            //                     '$gte' => [
            //                         '$initiated_data.submission_date',
            //                         array('$toDate' => $from),
            //                     ],
            //                 ),
            //                 array(
            //                     '$lte' => [
            //                         '$initiated_data.submission_date',
            //                         array(
            //                             '$add' => [array('$toDate' => $to), 1 * 24 * 60 * 60000],
            //                         ),
            //                     ],
            //                 ),
            //             ],
            //         ),
            //     ),
            // ),
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

    public function all_employments($department_id = '', $service_id = array(), $from = '', $to = '')
    {
        $from_date = new MongoDB\BSON\UTCDateTime(strtotime($from) * 1000);
        $to_date = new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($to)) * 1000);
        $collection = 'applications';

        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    'initiated_data.base_service_id' => array('$in' => $service_id),
                ),
            ),
            array(
                '$match' => array(
                    'initiated_data.submission_date' => ['$gte' => $from_date],
                    'initiated_data.submission_date' => ['$lte' => $to_date],
                ),
            ),


            // array(
            //     '$match' => array(
            //         '$expr' => array(
            //             '$and' => [
            //                 array(
            //                     '$gte' => [
            //                         '$initiated_data.submission_date',
            //                         array('$toDate' => strtotime($from)),
            //           strtotime(   )       ],
            //                 ),
            //                 array(
            //                     '$lte' => [
            //                         '$initiated_data.submission_date',
            //                         array(
            //                             '$add' => [array('$toDate' => $to), 1 * 24 * 60 * 60000],
            //                         ),
            //                     ],
            //                 ),
            //             ],
            //         ),
            //     ),
            // ),
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
                            'others' => 0,
                        ),
                        array(
                            '_id' => 'OBC/MOBC',
                            'male' => 0,
                            'female' => 0,
                            'others' => 0,
                        ),
                        array(
                            '_id' => 'ST',
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

    public function gender_employmentwise($department_id = '', $service_id = array(), $employment = '', $from = '', $to = '')
    {
        $collection = 'applications';
        $from_date = new MongoDB\BSON\UTCDateTime(strtotime($from) * 1000);
        $to_date = new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($to)) * 1000);

        $operations = array(
            array(
                '$match' => array(
                    'initiated_data.department_id' => $department_id,
                    'initiated_data.base_service_id' => array('$in' => $service_id),
                    'initiated_data.attribute_details.current_employment_status' => $employment,
                ),
            ),
            array(
                '$match' => array(
                    'initiated_data.submission_date' => ['$gte' => $from_date],
                    'initiated_data.submission_date' => ['$lte' => $to_date],
                ),
            ),

            // array(
            //     '$match' => array(
            //         '$expr' => array(
            //             '$and' => [
            //                 array(
            //                     '$gte' => [
            //                         '$initiated_data.submission_date',
            //                         array('$toDate' => $from),
            //                     ],
            //                 ),
            //                 array(
            //                     '$lte' => [
            //                         '$initiated_data.submission_date',
            //                         array(
            //                             '$add' => [array('$toDate' => $to), 1 * 24 * 60 * 60000],
            //                         ),
            //                     ],
            //                 ),
            //             ],
            //         ),
            //     ),
            // ),
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


    public function get_exchangewise_report($department_id = '', $service_id = array(), $from = '', $to = '')
    {
        $collection = 'applications';
        $options = ['allowDiskUse' => true];
        $from_date = new MongoDB\BSON\UTCDateTime(strtotime($from) * 1000);
        $to_date = new MongoDB\BSON\UTCDateTime(strtotime("+1 day", strtotime($to)) * 1000);

        $operations = array(
            [
                '$match' => [
                    'initiated_data.department_id' => $department_id,
                    'initiated_data.base_service_id' => array('$in' => $service_id),
                ],
            ],
            [
                '$match' => [
                    'initiated_data.submission_date' => ['$gte' => $from_date],
                    'initiated_data.submission_date' => ['$lte' => $to_date],
                ],
            ],
            [
                '$facet' => [
                    'genderwise_total' => [
                        array(
                            '$group' => array(
                                '_id' =>  '$initiated_data.submission_location',
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
                            '$sort' => ['_id' => 1],
                        ),

                    ],
                    'castewise_total' => [
                        array(
                            '$group' => array(
                                '_id' => [
                                    'emp_ex' => '$initiated_data.submission_location',
                                    'caste' => '$initiated_data.attribute_details.caste'
                                ],
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
                        )
                    ]
                ]
            ]

        );
        $data = (array) $this->mongo_db->aggregate($collection, $operations, $options);

        $response = [];

        foreach ($data[0]->genderwise_total as $key => $value) {
            $value->caste_wise = [];
            $response[] = $value;

            foreach ($data[0]->castewise_total as $k => $v) {
                if ($value->_id == $v->_id->emp_ex) {
                    // Order: General, SC, ST(P), ST(H), OBC/MOBC

                    switch ($v->_id->caste) {
                        case 'General':
                            $value->caste_wise[0] = (object)[
                                '_id' => $v->_id->caste,
                                'male' => $v->male,
                                'female' => $v->female,
                                'others' => $v->others
                            ];
                            break;

                        case 'SC':
                            $value->caste_wise[1] = (object)[
                                '_id' => $v->_id->caste,
                                'male' => $v->male,
                                'female' => $v->female,
                                'others' => $v->others
                            ];
                            break;

                        case 'ST(P)':
                            $value->caste_wise[2] = (object)[
                                '_id' => $v->_id->caste,
                                'male' => $v->male,
                                'female' => $v->female,
                                'others' => $v->others
                            ];
                            break;

                        case 'ST(H)':
                            $value->caste_wise[3] = (object)[
                                '_id' => $v->_id->caste,
                                'male' => $v->male,
                                'female' => $v->female,
                                'others' => $v->others
                            ];
                            break;
                        case 'OBC/MOBC':
                            $value->caste_wise[4] = (object)[
                                '_id' => $v->_id->caste,
                                'male' => $v->male,
                                'female' => $v->female,
                                'others' => $v->others
                            ];
                            break;
                    }

                    // array_push($value->caste_wise, (object)[
                    //     '_id' => $v->_id->caste,
                    //     'male' => $v->male,
                    //     'female' => $v->female,
                    //     'others' => $v->others
                    // ]);
                }
            }

            ksort($value->caste_wise, SORT_STRING);
            $value->caste_wise = array_values($value->caste_wise);
        }

        return $response;
    }
}
