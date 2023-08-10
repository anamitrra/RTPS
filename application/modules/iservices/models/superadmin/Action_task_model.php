
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Action_task_model extends Mongo_model
{
   /**
    * __construct
    *
    * @return void
    */
    function __construct()
    {
        parent::__construct();
        $this->set_collection("service_actions");
    }

    function all_applications(){
        $filter['service_id'] = '54321';
        $result = $this->mongo_db->where($filter)->get($this->table);
        return $result;
    }
    

    function get_single_application($id){
        $result = $this->mongo_db->where(['service_id'=>'54321', 'rtps_trans_id'=>$id])->get($this->table);
        return $result;
    }

    function get_prev_users($id, $appl_no){
        $result = $this->mongo_db->where(['task_details.next_users'=>$id])->get($this->table);
        return (array)$result;
    }

    function get_remarks($appl_no){
        $result = $this->mongo_db->where(['task_details.appl_no'=>$appl_no])->get($this->table);
        return $result;
    }
}