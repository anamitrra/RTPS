
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
class Acknowledgement_model extends Mongo_model
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
    public function update_row($fillter,$data){
        $this->mongo_db->set($data);
        $this->mongo_db->where($fillter);
        return $this->mongo_db->update($this->table);
      }
    function is_exist_transaction_no($rtps_trans_id){
        // $operations = 
        // array(
        //     '$or'=>array(
        //         array("form_data.rtps_trans_id" => $rtps_trans_id),
        //         array("service_data.appl_ref_no" => $rtps_trans_id),
        //       )
        //     );
        $operations = array("service_data.appl_ref_no" => $rtps_trans_id);         
        $count = $this->mongo_db->mongo_like_count($operations , 'sp_applications');
        if($count > 0){
          return true;
        }else {
          return false;
        }
  }


  public function applications_filter($limit, $start,  $apply_by , $col, $dir)
    {
        // $filter["official_userid"]= new ObjectId($apply_by);
        $filter["service_data.applied_by"]= new ObjectId($apply_by);
        $this->set_collection($this->table);
     
        // $this->mongo_db->select("service_id","service_name","mobile","rtps_trans_id","app_ref_no","status");
        // return $this->search_selected_rows($limit, $start, $filter, $col, $dir,$project);
        return $this->search_rows($limit, $start, $filter, $col, $dir);
    }

    public function total_app_rows($apply_by ){
    
        // $filter["official_userid"]= new ObjectId($apply_by);
        $filter["service_data.applied_by"]= new ObjectId($apply_by);
        $this->set_collection($this->table);
        return $this->tot_search_rows($filter);
      
    }


    public function application_search_rows($limit, $start, $keyword, $col, $dir,$apply_by )
    {
        $filter["official_userid"]= new ObjectId($apply_by);
        $temp = array(
            '$and' => [$filter],
            '$or'=>[
              ['service_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['rtps_trans_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['app_ref_no' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['mobile' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['applicant_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']]
            ]
        );
        //print_r($temp);
        $this->set_collection($this->table);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }


}
