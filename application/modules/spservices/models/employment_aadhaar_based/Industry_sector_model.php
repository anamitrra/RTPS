
<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Industry_sector_model extends Mongo_model
{

    public function __construct()
    {
        parent::__construct();
        $this->set_collection("emp_industry_sector");
    }

    public function get_rows()
    {
        $this->mongo_db->order_by('sl_no', 'ASC');
        $res = $this->mongo_db->get($this->table);
        if (count((array)$res)) {
            return $res;
        } else {
            return false;
        } //End of if else        
    } //End of get_rows()

    public function getDistId($dist)
    {
        $data = $this->mongo_db->select(['id'])->where(['district' => $dist])->get("emp_district");
        return $data;
    }
}
