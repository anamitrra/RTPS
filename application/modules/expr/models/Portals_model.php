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

  public function get_all_application_list()
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
  function update($id, $data)
  {
      $this->mongo_db->set($data);
      $this->mongo_db->where("_id", new MongoDB\BSON\ObjectId($id));
      $this->mongo_db->update($this->table, $data);
  }

}
