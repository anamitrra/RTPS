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

    public function get_application_count()
    {
        $collection = 'sp_applications';
        $operations = [
            [
                '$match' => [
                    'service_data.service_id' => 'MCC',
                    'form_data.pa_district_name' => $this->session->userdata('admin')['district'],
                    'form_data.payment_status' => 'PAYMENT_COMPLETED',
                ]
            ],

            ['$group' => [
                "_id" => '$service_id',
                "total" => ['$sum' => 1],
                "under_process" => [
                    '$sum' => [
                        '$cond' => [
                            [
                                '$eq' => ['$service_data.appl_status', "UNDER_PROCESSING"],
                            ],
                            1, 0
                        ],
                    ],
                ],
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
    public function get_pending_count()
    {
        $collection = 'sp_applications';
        if ($this->session->userdata('role_slug') == 'DPS') {
            $match = [
                'service_id' => 'MCC',
                'pa_district_name' => $this->session->userdata('district_name'),
                'status' => ['$in' => ["PAYMENT_COMPLETED", "UNDER_PROCESSING", "QUERY_ARISE", "QUERY_SUBMITTED"]],
                '$or' => [
                    ['execution_data.0.task_details.user_detail.user_id' => $this->session->userdata('userId')->{'$id'}],
                    ['execution_data' => ['$size' => 1]]
                ]
            ];
        } else {
            $match = [
                'service_id' => 'MCC',
                'pa_district_name' => $this->session->userdata('district_name'),
                'status' => ['$in' => ["PAYMENT_COMPLETED", "UNDER_PROCESSING", "QUERY_ARISE", "QUERY_SUBMITTED"]],
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
        $user_id = $this->session->userdata('unique_user_id');
        // for dps role
        if ($user_role == 'DPS') {
            $applications = [];
            $this->mongo_db->where('pa_district_name', $this->session->userdata('district_name'));
            $this->mongo_db->where('applicant_query', false);
            $this->mongo_db->where_not_in('status', ['DRAFT', 'SUBMITTED', 'REJECTED', 'DELIVERED']);
            // $this->mongo_db->where('applicant_query', false);
            $data = $this->mongo_db->get($this->table);
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

    // public function pending_applications($rtps_no, $community, $limit, $start)
    // {
    //     $user_role = $this->session->userdata('role_slug');
    //     $user_id = $this->session->userdata('unique_user_id');
    //     // for dps role
    //     if ($user_role == 'DPS') {
    //         $applications = [];
    //         $this->mongo_db->where('pa_district_name', $this->session->userdata('district_name'));
    //         $this->mongo_db->where('applicant_query', false);
    //         $this->mongo_db->where_not_in('status', ['DRAFT', 'SUBMITTED', 'REJECTED', 'DELIVERED']);
    //         if (!empty($rtps_no)) {
    //             $this->mongo_db->where('rtps_trans_id', trim(strtoupper($rtps_no)));
    //         }
    //         if (!empty($community)) {
    //             $this->mongo_db->where('community', trim($community));
    //         }
    //         // $this->mongo_db->where('applicant_query', false);
    //         $data = $this->mongo_db->order_by('created_at', 'DESC')->limit($limit, $start)->get($this->table);
    //         foreach ($data as $val) {
    //             if (count($val->execution_data) == 1) {
    //                 // $applications[] =  (array)$data;
    //                 // $data1[$val->rtps_trans_id] =  $val->rtps_trans_id;
    //                 $applications[] = $val;
    //             } else {
    //                 if (($val->execution_data[0]->task_details->user_detail->user_id == $this->session->userdata('userId')->{'$id'}) && ($val->execution_data[0]->task_details->action_taken == 'N')) {
    //                     // $data1[$val->rtps_trans_id] =  $val->execution_data[0]->task_details->user_detail->user_id;
    //                     // $data1['1'] = $user_id;
    //                     $applications[] =  $val;
    //                 }
    //             }
    //         }
    //     }
    //     // for other roles
    //     else {
    //         $this->mongo_db->where('execution_data.0.task_details.user_detail.designation', $this->session->userdata('designation'));
    //         $this->mongo_db->where('execution_data.0.task_details.user_detail.user_id', $this->session->userdata('userId')->{'$id'});
    //         $this->mongo_db->where('execution_data.0.task_details.action_taken', 'N');
    //         $this->mongo_db->where('applicant_query', false);
    //         $this->mongo_db->where_not_in('status', ['Rejected', 'Delivered']);
    //         $applications = $this->mongo_db->order_by('created_at', 'DESC')->get($this->table);
    //     }
    //     return $applications;
    // }

    public function all_certificate()
    {
        $applications = $this->mongo_db
            ->where(['pa_district_name' => $this->session->userdata('district_name'), 'status' => 'DELIVERED'])->order_by('_id', 'DESC')
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
    // function get_delivered_applications(){
    //     $this->mongo_db->where('pa_district', $this->session->userdata('district'));
    //     $this->mongo_db->where('status', 'Delivered');
    //     $applications = $this->mongo_db->get($this->table);
    //     return $applications;
    // }

    // function get_forwarded_applications(){
    //     $this->mongo_db->where('execution_data.1.task_details.user_detail.designation', $this->session->userdata('designation'));
    //     $this->mongo_db->where('execution_data.1.task_details.user_detail.user_id', $this->session->userdata('userId')->{'$id'});
    //     $this->mongo_db->where('execution_data.1.official_form_details.action', 'Forward');
    //     // $this->mongo_db->where('execution_data.1.official_form_details.status !=', 'RO');
    //     $this->mongo_db->where('applicant_query', false);
    //     $this->mongo_db->where_not_in('status', ['Rejected', 'Delivered']);
    //     $applications = $this->mongo_db->order_by('_id', 'DESC')->get($this->table);
    //     return $applications;
    // }


    // // get_rejected_applications
    // function get_rejected_applications(){
    //     $this->mongo_db->where('pa_district', $this->session->userdata('district'));
    //     $this->mongo_db->where('status', 'Rejected');
    //     $applications = $this->mongo_db->get($this->table);
    //     return $applications;
    // }
    // // get_revert_applicant_applications
    // function get_revert_applicant_applications(){
    //     $this->mongo_db->where('applicant_query',true);
    //     $this->mongo_db->where('execution_data.0.task_details.user_detail.user_id',$this->session->userdata('userId')->{'$id'});
    //     // $this->mongo_db->where('status', 'Query');
    //     $applications = $this->mongo_db->get('sp_applications');
    //     return $applications;
    // }

    // function get_office_revert_applications(){
    //     $this->mongo_db->where('execution_data.1.task_details.user_detail.designation', $this->session->userdata('designation'));
    //     $this->mongo_db->where('execution_data.1.task_details.user_detail.user_id', $this->session->userdata('userId')->{'$id'});
    //     $this->mongo_db->where('execution_data.2.official_form_details.status', 'RO');
    //     $this->mongo_db->where('applicant_query', false);
    //     $this->mongo_db->where_not_in('status', ['Rejected', 'Delivered']);
    //     $applications = $this->mongo_db->get($this->table);
    //     return $applications;
    // }
    // function applications_for_dps($user){
    //     $collection = 'intermediate_ids';
    //     $applications = $this->mongo_db->where(['service_code'=>'WPTBC', 'pa_district'=> $this->session->userdata('district')])->get($this->table);
    //     foreach($applications as $val){
    //         if(!isset($val->execution_data)){
    //             return 'not exist';
    //         }
    //         else{
    //             $last_entry = count($val->execution_data)-1;
    //         //    return $abc =['$match' => ['execution_data['.$last_entry.'].task_details.user_name' => 'DPS' ]];
    //             $operation= [
    //                 ['$match' => ['execution_data[0].task_details.next_users[1]' => '6246a2b201fcf3d32a7692a5']],
    //                 // ['$match' => ['execution_data['.$last_entry.'].task_details.user_name' => 'DPS' ]],
    //                 ['$match' => ['service_code' => 'WPTBC']],
    //                 ['$match' => ['pa_district' => $this->session->userdata('district')]],
    //             ];
    //             return $applications = $this->mongo_db->aggregate($collection, $operation);
    //         }
    //     }
    // }
    public function get_single_application($id)
    {
        $depts = $this->mongo_db->where(['rtps_trans_id' => $id])->get($this->table);
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
