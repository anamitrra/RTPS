<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Offices_model extends Mongo_model
{

    function __construct()
    {
        parent::__construct();
        $this->set_collection('offices');
      
    }
    function all()
    {
        $this->mongo_db->switch_db("appeal");
         return $this->mongo_db->get('locations');
       
    }
    public function getOfficesByDistrict($district_id)
    {
        $filter['district_id'] = intval($district_id);       
        return $this->get_where($filter);
    }
    public function get_districts()
    {
        return $this->mongo_db->get('districts');
    }

    public function get_district_name($dis_id)
    {
        $filter['distcode'] = intval($dis_id);
        return $this->mongo_db->where($filter)->find_one("districts");
    }
}
