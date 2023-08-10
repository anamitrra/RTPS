<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Appeal_process_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('appeal_processes');
    }
    public function get_process_details($appealId)
    {
        $collection = 'appeal_processes';
        $operations = array(
            array(
                '$match' => ['appeal_id' => ['$eq' => $appealId]]
            ),
            array(
                '$lookup'  => array(
                    'from'         => 'users',
                    'localField'   => 'action_taken_by',
                    'foreignField' => '_id',
                    'as'           => 'user'
                )
            ),
            array('$unwind' => '$user'),

            array(
                '$project' => array(
                    '_id'               => 1,
                    'appeal_id'         => 1,
                    'action'            => 1,
                    'message'           => 1,
                    'comment'           => 1,
                    'documents'         => 1,
                    'dsc_file_appellant'=>1,
                    'dsc_file_dps'=>1,
                    'created_at'        => 1,
                    'penalty_amount'    => 1,
                    'total_penalty_amount' => 1,
                    'number_of_days_of_delay'=>1,
                    'penalty_to_user'   => 1,
                    'notifiable'        => 1,
                    'notifiable_dps'    => 1,
                    'notifiable_appellate' => 1,
                    'date_of_hearing'   => 1,
                    'last_date_of_submission' => 1,
                    'previous_user'     => 1,
                    'forward_to'        => 1,
                    'reassign_to'       => 1,
                    'comment_documents' => 1,
                    'bench_member'      => 1,
                    'delegate_to_chairman' => 1,
                    'user'              => '$user',
                    'approved_files'    => 1,
                    'action_taken_by'   => 1,
                    'appellant_hearing_order' => 1,
                    'dps_hearing_order' => 1,
                    'disposal_order'    => 1,
                    'penalty_order'     => 1,
                    'rejection_order'   => 1,
                    'is_final_hearing'   => 1,
                    "action_taken_by_name"=>1
                )
            )
        );
        //pre($operations);
        $data = $this->mongo_db->aggregate($collection, $operations);
        //pre($data);
        return (array)$data;
    }
    public function is_action_already_submitted($action, $filter)
    {
        $this->mongo_db->where('action', $action);
        $this->mongo_db->where($filter);
        if ($this->mongo_db->count('appeal_processes')) {
            return true;
        } else {
            return false;
        }
    }
    public function check_all_action_submitted(array $actions, $filter)
    {
        $this->mongo_db->where_in('action', $actions);
        $this->mongo_db->where($filter);
        $this->mongo_db->select(array('action'));
        $taskListCount = $this->mongo_db->count('appeal_processes');
        if ($taskListCount == count($actions)) {
            return true;
        }
        return false;
        //todo :: needs fixing
        //        $this->mongo_db->where_in('action',$actions);
        //        $this->mongo_db->where($filter);
        //        $this->mongo_db->select(array('action'));
        //        $taskList = $this->mongo_db->get('appeal_processes');
        //        foreach ($taskList as $task){
        //            if(!in_array($task->action,$actions)){
        //                return false;
        //            }
        //        }
        //        return true;
    }

    public function find_latest_where($filter)
    {
        return $this->mongo_db->where($filter)->order_by(array('created_at' => 'desc'))->find_one($this->table);
    }
}
