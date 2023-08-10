<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Revenue_circle_model extends Mongo_model
{

    public function __construct()
    {
        parent::__construct();
        $this->set_collection("emp_subdivision_revcircle");
    }

    public function get_rows($filter = null)
    {
        $this->mongo_db->order_by('subdivision', 'ASC');
        if ($filter != null) {
            $this->mongo_db->where($filter);
        }
        $res = $this->mongo_db->get($this->table);
        if (count((array)$res)) {
            return $res;
        } else {
            return false;
        } //End of if else  
    }
}
