<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
use MongoDB\BSON\ObjectId;
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
        $this->set_collection("intermediate_ids");
    }

    function get_application_count($portal_no){
        $collection = 'intermediate_ids';
        $operations = [
            ['$match'=> ['portal_no'=> $portal_no]],
            ['$group'=> [
                "_id"=> '$service_name',
                "total"=> ['$sum'=> 1],
                "under_process"=> [
                    '$sum'=> [
                           '$cond'=> [
                                      [
                                        '$in'=> ['$status', ["Submitted", "UNDER PROCESSED", "QUERY"]]
                                      ],
                                      1, 0
                            ],
                    ],
                ],
                "delivered"=> [
                    '$sum'=> [
                           '$cond'=> [
                                      [
                                        '$eq'=> ['$status', "DELIVERED"],
                                      ],
                                      1, 0
                            ],
                    ],
               ],
               "rejected"=> [
                    '$sum'=> [
                       '$cond'=> [
                                  [
                                    '$eq'=> ['$status', "REJECTED"],
                                  ],
                                  1, 0
                        ],
                    ],
                ],
            ]]
        ];
        return $applications_count = $this->mongo_db->aggregate($collection, $operations);
    }
    function all_applications(){
        $collection = 'intermediate_ids';
        $role = $this->session->userdata("role");
        // echo $role->slug; die();
        //  $applications = $this->mongo_db->where(['execution_data[0].task_details.action_no' => '1'])->get($this->table);

        if ($role->slug == "DPS"){
            // $user = new ObjectId($this->session->userdata('userId')->{'$id'});
            //  $this->applications_for_dps($user);
            $user_name = 'DPS';
        }
        elseif($role->slug == "DA"){
            $user_name = 'DA';
        }
        elseif($role->slug == "CO"){
            $user_name = 'CO';
        }
        elseif($role->slug == "SK"){
            $user_name = 'SK';
        }
        elseif($role->slug == "LM"){
            $user_name = 'LM';
        }
        $applications = $this->mongo_db->where(['service_code'=>'WPTBC', 'pa_district'=> $this->session->userdata('district'), 'assigned_user'=>$user_name])->get($this->table);
        return $applications;
    }
    
    function get_delivered_applications(){
        $collection = 'intermediate_ids';
        $applications = $this->mongo_db->where(['service_code'=>'WPTBC', 'pa_district'=> $this->session->userdata('district'), 'status'=>'DELIVERED'])->get($this->table);
        return $applications;
    }

    function get_forwarded_applications(){
        $collection = 'intermediate_ids';
        $operations = [
            [ '$lookup'=>
                [
                'from'=> 'service_actions',
                'localField'=> 'rtps_trans_id',
                'foreignField'=> 'task_details.appl_no',
                'as'=> 'service_data'
                ]
            ],
           ['$unwind'=> [ 'path'=> '$service_data', 'preserveNullAndEmptyArrays'=> true ]],
           ['$match'=> ['service_data.task_details.pull_user_id'=> new ObjectId($this->session->userdata('userId')->{'$id'})]],
           ['$match'=> ['service_data.official_form_details.action'=> "Forward"]],
           ['$match'=> ['status'=> ['$not'=> [ '$eq'=> 'DELIVERED' ]]]],
            ['$group'=> [
                "_id"=> '$_id',
                "rtps_trans_id"=>  ['$first'=> '$rtps_trans_id'],
                "service_name"=>  ['$first'=> '$service_name'],
                "applicant_name"=>  ['$first'=> '$applicant_name'],
                "mobile_number"=>  ['$first'=> '$mobile_number'],
                "created_at"=>  ['$first'=> '$created_at'],
                "executed_time"=>  ['$last'=> '$service_data.task_details.executed_time'],

                
            ]]
        ];
        return $applications = $this->mongo_db->aggregate($collection, $operations);
    }

    
    // get_rejected_applications
    function get_rejected_applications(){
        // $applications = $this->mongo_db->where(['service_code'=>'WPTBC', 'pa_district'=> $this->session->userdata('district'), 'status'=>'REJECTED'])->get($this->table);
        // return $applications;
        $collection = 'intermediate_ids';
        $operations = [
            [ '$lookup'=>
                [
                'from'=> 'service_actions',
                'localField'=> 'rtps_trans_id',
                'foreignField'=> 'task_details.appl_no',
                'as'=> 'service_data'
                ]
            ],
           ['$unwind'=> [ 'path'=> '$service_data', 'preserveNullAndEmptyArrays'=> true ]],
           ['$match'=> ['status'=> [ '$eq'=> 'REJECTED' ]]],
           ['$match'=> ['service_code'=> [ '$eq'=> 'WPTBC' ]]],
           ['$match'=> ['pa_district'=> [ '$eq'=> $this->session->userdata('district') ]]],

            ['$group'=> [
                "_id"=> '$_id',
                "rtps_trans_id"=>  ['$first'=> '$rtps_trans_id'],
                "service_name"=>  ['$first'=> '$service_name'],
                "applicant_name"=>  ['$first'=> '$applicant_name'],
                "mobile_number"=>  ['$first'=> '$mobile_number'],
                "submission_date"=>  ['$first'=> '$created_at'],
                "executed_time"=>  ['$last'=> '$service_data.task_details.executed_time'],
                "remarks"=>['$last'=> '$service_data.official_form_details.remarks']

                
            ]]
        ];
        return $applications = $this->mongo_db->aggregate($collection, $operations);
    }
    // get_revert_applicant_applications
    function get_revert_applicant_applications(){
        $applications = $this->mongo_db->where(['service_code'=>'WPTBC', 'pa_district'=> $this->session->userdata('district'), 'status'=>'QUERY'])->get($this->table);
        return $applications;
    }

    function applications_for_dps($user){
        $collection = 'intermediate_ids';
        $applications = $this->mongo_db->where(['service_code'=>'WPTBC', 'pa_district'=> $this->session->userdata('district')])->get($this->table);
        foreach($applications as $val){
            if(!isset($val->execution_data)){
                return 'not exist';
            }
            else{
                $last_entry = count($val->execution_data)-1;
            //    return $abc =['$match' => ['execution_data['.$last_entry.'].task_details.user_name' => 'DPS' ]];
                $operation= [
                    ['$match' => ['execution_data[0].task_details.next_users[1]' => '6246a2b201fcf3d32a7692a5']],
                    // ['$match' => ['execution_data['.$last_entry.'].task_details.user_name' => 'DPS' ]],
                    ['$match' => ['service_code' => 'WPTBC']],
                    ['$match' => ['pa_district' => $this->session->userdata('district')]],
                ];
                return $applications = $this->mongo_db->aggregate($collection, $operation);
            }
        }
    }
    function get_single_application($id){
        $depts = $this->mongo_db->where(['rtps_trans_id'=>$id])->get($this->table);
        return $depts;
    }
}