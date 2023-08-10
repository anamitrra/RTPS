<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use Mpdf\Tag\Th;

class Application_model extends Mongo_model
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

    public function get_application_count($service_id)
    {
        $collection = 'sp_applications';
        $operations = [
            [ 
                '$match' => [
                    'service_data.service_id' => $service_id,
                    'form_data.pa_district_name' => $this->session->userdata('district_name'),
                    'form_data.payment_status' => 'PAYMENT_COMPLETED',

                ]
            ],

            ['$group' => [
                "_id" => '$service_data.service_id',
                "total" => ['$sum' => 1],
                "delivered" => [
                    '$sum' => [
                        '$cond' => [
                            [
                                '$eq' => ['$service_data.appl_status', "DELIVERED"],
                            ],
                            1, 0
                        ],
                    ],
                ],
                "rejected" => [
                    '$sum' => [
                        '$cond' => [
                            [
                                '$eq' => ['$service_data.appl_status', "REJECTED"],
                            ],
                            1, 0
                        ],
                    ],
                ],
                "applicant" => [
                    '$sum' => [
                        '$cond' => [
                            [
                                '$eq' => ['$applicant_query', true],
                            ],
                            1, 0
                        ],
                    ],
                ]
            ]]
        ];
// pre($operations);
        return $this->mongo_db->aggregate($collection, $operations);
        
    }
    // get_pending_count
    public function get_pending_count($service_id)
    {
        $collection = 'sp_applications';
        if ($this->session->userdata('role_slug') == 'DPS') {
            $match = [
                'service_data.service_id' => $service_id,
                'form_data.pa_district_name' => $this->session->userdata('district_name'),
                'service_data.appl_status' => ['$in' => ["PAYMENT_COMPLETED", "UNDER_PROCESSING", "QUERY_ARISE", "QUERY_SUBMITTED"]],
                '$or' => [
                    ['execution_data.0.task_details.user_detail.user_id' => $this->session->userdata('userId')->{'$id'}],
                    ['execution_data' => ['$size' => 1]]
                ]
            ];
        } else {
            $match = [
                'service_data.service_id' => $service_id,
                'form_data.pa_district_name' => $this->session->userdata('district_name'),
                // 'service_data.appl_status' => ['$in' => ["PAYMENT_COMPLETED", "UNDER_PROCESSING", "QUERY_ARISE", "QUERY_SUBMITTED"]],
                '$or' => [
                    ['execution_data.0.task_details.user_detail.user_id' => $this->session->userdata('userId')->{'$id'}],
                ]
            ];
        }
        // pre($match);
        $operations = [
            ['$match' => $match],

            ['$count' => "total"]
        ];
        
        return $this->mongo_db->aggregate($collection, $operations);
    }
    public function all_applications($filter, $limit, $start)
    {
        // $this->mongo_db->where('pa_district_name', $this->session->userdata('district_name'));
        // $this->mongo_db->where(['payment_status' => 'PAYMENT_COMPLETED']);
        // $applications = $this->mongo_db->order_by('created_at', 'DESC')->get($this->table);
        // return $applications;
        // ->limit($limit, $start)
        return $applications = $this->mongo_db->where($filter)->limit($limit, $start)->get($this->table);
    }
    public function pending_applications()
    {
        $user_role = $this->session->userdata('role_slug');
        // $user_id = $this->session->userdata('unique_user_id');
        // for dps role
        if ($user_role == 'DPS') {
            $applications = [];
            $this->mongo_db->where('form_data.pa_district_name', $this->session->userdata('district_name'));
            $this->mongo_db->where('applicant_query', false);
            $this->mongo_db->where_not_in('service_data.appl_status', ['DRAFT', 'SUBMITTED', 'REJECTED', 'DELIVERED']);
            // $this->mongo_db->where('applicant_query', false);
            $data = $this->mongo_db->get($this->table);
            // pre($data);
            foreach ($data as $val) {
                if (count($val->execution_data) == 1) {
                    // $applications[] =  (array)$data;
                    // $data1[$val->rtps_trans_id] =  $val->rtps_trans_id;
                    $applications[] = $val;
                } else {
                    if (($val->execution_data[0]->task_details->user_detail->user_id == $this->session->userdata('userId')->{'$id'}) && ($val->execution_data[0]->task_details->action_taken == 'N')) {
                        // $data1[$val->rtps_trans_id] =  $val->execution_data[0]->task_details->user_detail->user_id;
                        // $data1['1'] = $user_id;
                        $applications[] =  $val;
                    }
                }
            }
        }
        // for other roles
        else {
            $this->mongo_db->where('execution_data.0.task_details.user_detail.designation', $this->session->userdata('designation'));
            $this->mongo_db->where('execution_data.0.task_details.user_detail.user_id', $this->session->userdata('userId')->{'$id'});
            $this->mongo_db->where('execution_data.0.task_details.action_taken', 'N');
            $this->mongo_db->where('applicant_query', false);
            $this->mongo_db->where_not_in('status', ['REJECTED', 'DELIVERED']);
            $applications = $this->mongo_db->order_by('created_at', 'DESC')->get($this->table);
        }
        return $applications;
    }

    public function all_certificate()
    {
        $applications = $this->mongo_db
            ->where(['form_data.pa_district_name' => $this->session->userdata('district_name'), 'service_data.appl_status' => 'DELIVERED'])->order_by('_id', 'DESC')
            ->get($this->table);

        // $applications= $this->mongo_db->where(array('execution_data'=>array('$exists'=>false)))->get($this->table);
        return $applications;
    }
    // get_ro_aro_user
    public function get_ro_aro_user($district, $circle)
    {
        $collection = 'office_users';
        $operations = [
            [
                '$match' => [
                    'district_name' => $district,
                    'circle_name' => $circle,
                    'is_active' => 1,
                    '$or' => [
                        ['role_slug_name' => 'RO'],
                        ['role_slug_name' => 'ARO']
                    ]
                ]
            ],
        ];
        return $this->mongo_db->aggregate($collection, $operations);
    }

    public function get_single_application($id)
    {
        $depts = $this->mongo_db->where(['service_data.appl_ref_no' => $id])->get($this->table);
        return $depts;
    }

    public function common_count($m)
    {
        $collection = 'sp_applications';
        $options = array("allowDiskUse" => true);
        $operations = [
            ['$match' => $m],
            ['$count' => 'total']
        ];
        $data = $this->mongo_db->aggregate($collection, $operations, $options);
        return $arr = (array) $data;
    }

    public function get_row($filter = null)
    {
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->find_one($this->table);
        if (count((array)$res)) {
            return $res;
        } else {
            return false;
        } //End of if else        
    } //End of get_row()
}
