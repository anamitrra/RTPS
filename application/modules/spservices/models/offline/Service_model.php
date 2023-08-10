
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
class Service_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->set_collection("sp_services");
    }
    public function get_services(){
        $this->mongo_db->where(array('is_offline'=>true));
        $res = $this->mongo_db->get($this->table);
        return $res;
    }
    public function get_service_details($service_code){
        $this->mongo_db->where(array("service_code"=>$service_code,'service_mode'=>"OFFLINE"));
        $res = $this->mongo_db->find_one("upms_services");
        return $res;
    }
    public function get_offline_services(){
        $this->mongo_db->where(array('service_mode'=>"OFFLINE"));
        $res = $this->mongo_db->get("upms_services");
        return $res;
    } 
    public function get_offline_office_by_services($service_code){
        $this->mongo_db->where(array("services_mapped.service_code" => $service_code));
        $officeRows = $this->mongo_db->get("upms_offices");
        $offices_info=array();
        if($officeRows) {
            foreach($officeRows as $officeRow) {
                $offices_info[] = array(
                    "office_name"=>$officeRow->office_name,
                    "office_code"=>$officeRow->office_code
                );  
            }//End of foreach();
        }//End of if

        return  $offices_info;
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
    function is_exist_service_id($service_id){
       
        $count = $this->mongo_db->mongo_like_count(array('service_id' => $service_id), $this->table);
        if($count > 0){
          return true;
        }else {
          return false;
        }
  }


  public function applications_filter($limit, $start, $col, $dir)
    {
        $filter["is_offline"]= true;
        $this->set_collection($this->table);
     
        // $this->mongo_db->select("service_id","service_name","mobile","rtps_trans_id","app_ref_no","status");
        // return $this->search_selected_rows($limit, $start, $filter, $col, $dir,$project);
        return $this->search_rows($limit, $start, $filter, $col, $dir);
    }

    public function total_app_rows( ){
    
        $filter["is_offline"]= true;
        $this->set_collection($this->table);
        return $this->tot_search_rows($filter);
      
    }


    public function application_search_rows($limit, $start, $keyword, $col, $dir )
    {
        $filter["is_offline"]= true;
        $temp = array(
            '$and' => [$filter],
            '$or'=>[
              ['service_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['service_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']]
            ]
        );
        //print_r($temp);
        $this->set_collection($this->table);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }


}
