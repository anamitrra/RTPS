<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Artps_services_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('artps_services');
    }

    // get all
    function all()
    {
        return $this->mongo_db->get("artps_services");
    }

    function all_dept()
    {
        return $this->mongo_db->get("departments");
    }

    // delete data
    function delete_service($id)
    {
        $this->mongo_db->where('_id', $id);
        $this->mongo_db->delete("artps_services");
    }

    // get collection data
    public function get_service($id)
    {
        $this->mongo_db->where("_id", $id);
        return $this->mongo_db->get("artps_services");
    }
    public function get_service_doc_id($service_id){
        $filter['service_id'] = $service_id;
        return $this->mongo_db->where($filter)->find_one("artps_services");
        
       
    }

    /**
     * service_search_rows
     *
     * @param mixed $limit
     * @param mixed $start
     * @param mixed $keyword
     * @param mixed $col
     * @param mixed $dir
     * @return void
     */
    public function service_search_rows($limit, $start, $keyword, $col, $dir)
    {
        $temp = array(
            '$or'=>[
              ['rtps_service' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['department' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['autonomous_council' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            ]
        );
        // print_r($temp);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }
    public function service_tot_search_rows($keyword)
    {
        $temp = array(
            '$or'=>[
              ['rtps_service' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['department' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['autonomous_council' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            ]
        );
        // print_r($temp);
        return $this->tot_search_rows($temp);
    }
}
