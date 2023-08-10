<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
use MongoDB\BSON\ObjectId;
class Task_list_model extends Mongo_model
{
   /**
    * __construct
    *
    * @return void
    */
    function __construct()
    {
        parent::__construct();
        $this->set_collection("task_list");
    }

    function get_action_data($service_id, $action){
        $applications = $this->mongo_db->where(['service_id'=>$service_id, 'task_id'=>$action])->get($this->table);
        return $applications;
    }

    function get_task_name($service_id, $task_no){
        $task_name = $this->mongo_db->where(['task_id'=>$task_no, 'service_id'=>$service_id])->get($this->table);
        return (array)$task_name;
    }
}