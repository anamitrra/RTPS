<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Kiosk_mapping_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('kiosk_mapping');
    }




// get collection data
public function get_vendor_by_dept($dept_id)
{
    $this->mongo_db->where("department_id", $dept_id); 
    return $this->mongo_db->get($this->table);     
}
public function get_agency_by_vendor($vendor_id)
{
    $this->mongo_db->where("vendor_id", $vendor_id); 
    return $this->mongo_db->get($this->table);     
}
public function get_kiosk_by_agency($agency_id)
{
    $this->mongo_db->where("agency_id", $agency_id); 
    return $this->mongo_db->get($this->table);     
}
}