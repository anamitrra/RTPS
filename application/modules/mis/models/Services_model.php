<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Services_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('services');
    }

    // get all
    function all()
    {
        return $this->mongo_db->get("services");
    }
    function all_dept()
    {
        return $this->mongo_db->get("departments");
    }
    function all_dist()
    {
        return $this->mongo_db->get("districts");
    }

    // delete data
    function delete_service($id)
    {
        $this->mongo_db->where('_id', $id);
        $this->mongo_db->delete("services");
    }

    // get collection data
    public function get_service($id)
    {
        $this->mongo_db->where("_id", $id);
        return $this->mongo_db->get("services");
    }
    public function get_service_doc_id($service_id){
        $filter['service_id'] = $service_id;
        return $this->mongo_db->where($filter)->find_one("services");
        
       
    }

    public function get_services_by_dept($department_id='')
    {
        $this->mongo_db->order_by(array('service_name' => 'ASC'));
        return  $this->get_all(array('department_id' => $department_id));
    }
}
