<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Portals_model extends Mongo_model
{
 /**
  * __construct
  *
  * @return void
  */
  function __construct()
  {
    parent::__construct();
    $this->set_collection("portals");
  }

  // function getOfficeUsers() {
	
  //   $users = $this->mongo_db->order_by('_id', 'DESC')->get($this->table);
 
  //   return $users;

  //   }

  public function get_all_portal_list()
  {
    $this->set_collection("portals");
    return $this->get_all([]);
  }

  public function application_total_rows(){
    return $this->total_rows();
  }
  public function application_all_rows($limit, $start, $col, $dir,$filter=null){
    if(empty($filter)){
      return $this->all_rows($limit, $start, $col, $dir);
    }else {


    }
  }

  public function portal_tot_search_rows($keyword)
    {
      echo $keyword;
      exit;
        $temp = array(
            '$or' => [
                ['portal_name' => ['$regex' => '^' . $keyword . '', '$options' => 'i' ]],
                ['service_name' => ['$regex' => '^' . $keyword  . '', '$options' => 'i']]
            ]
        );
        //print_r($temp);
        return $this->tot_search_rows($temp);
    }

  public function application_search_rows($limit, $start, $search, $order, $dir,$filter=null){
    if(empty($filter)){
      $this->mongo_db->limit($limit, $start);
      $this->mongo_db->order_by($col, $dir);
      return $this->mongo_db->get_data_like($keyword, $this->table);
    }else {
    //  $keyword["\$or"] = array("forwarded_to_departments.department_name"=>$filter);
      $this->mongo_db->limit($limit, $start);
      $this->mongo_db->order_by($col, $dir);
      return $this->mongo_db->get_data_like($keyword, $this->table);
    }
  }

  public function get_data($where,$data){
    $this->mongo_db->select($data);
    $this->mongo_db->where($where);
    $res=$this->mongo_db->find_one($this->table);
    return $res;
  }

  public function get_row($where){
    $this->mongo_db->select("*");
    $this->mongo_db->where($where);
    $res=$this->mongo_db->find_one($this->table);
    return $res;
  }
  public function get_portal_details($id){
    $this->mongo_db->select("*");
    $this->mongo_db->where("_id", new MongoDB\BSON\ObjectId($id));
    $res=$this->mongo_db->find_one($this->table);
    return $res;
  }
  public function existServiceID($service_id){
    $this->mongo_db->select("*");
    $this->mongo_db->where(array("service_id"=>$service_id));
    $res=$this->mongo_db->find_one($this->table);
    return $res;
  }
  public function get_guidelines($service_id){
    $this->mongo_db->select("*");
    $this->mongo_db->where(array("service_id"=>$service_id.""));
    $res=$this->mongo_db->find_one($this->table);
    return $res;
  }
  public function get_departmental_data($service_id){
    $this->mongo_db->select("*");
    $this->mongo_db->where(array("service_id"=>$service_id.""));
    $res=$this->mongo_db->find_one($this->table);
    return $res;
  }

  public function get_departmental_data_by_portal($service_id,$portal_no){
    $this->mongo_db->select("*");
    $this->mongo_db->where(array("external_service_id"=>$service_id."",'portal_no'=>strval($portal_no)));
    $res=$this->mongo_db->find_one($this->table);
    return $res;
  }

  function update($id, $data)
  {
      $this->mongo_db->set($data);
      $this->mongo_db->where("_id", new MongoDB\BSON\ObjectId($id));
      $this->mongo_db->update($this->table, $data);
  }
  public function get_all_services(){
      // $this->mongo_db->select(array("service_name","service_id"));
      // $res=$this->mongo_db->get("portals");
      // return (array)$res;

      $filter=array(
        array(
          '$project'=>array(
            'service_name'=>1,
            'service_id'=>1,
            'portal_no'=>1,
            'collection'=>'intermediate_ids',
            'applied_by_path'=>'applied_by',
            'mobile_path'=>'mobile',
            'service_id_path'=>'service_id',
            'service_name_path'=>'service_name',
            'delivery_status_path'=>'delivery_status',
            '_id'=>0)
        )
        );

        $res=$this->mongo_db->aggregate("portals",$filter);
        return (array ) $res;
  }

  public function get_all_sp_services(){
    // $this->mongo_db->select(array("service_name","service_id"));
    // $res=$this->mongo_db->get("portals");
    // return (array)$res;

    $filter=array(
      array(
        '$match'=>array("service_id"=>array('$nin'=>['CRCPY','NECERTIFICATE','MARRIAGE_REGISTRATION']))
      ),
      array(
        '$project'=>array(
          'service_name'=>1,
          'service_id'=>1,
          'collection'=>'sp_applications',
          'applied_by_path'=>'service_data.applied_by',
          'mobile_path'=>'form_data.mobile',
          'service_id_path'=>'service_data.service_id',
          'service_name_path'=>'service_data->service_name',
          'delivery_status_path'=>'service_data.appl_status',
          '_id'=>0)
      )
      );

      $res=$this->mongo_db->aggregate("sp_services",$filter);
      return (array ) $res;
}

}
