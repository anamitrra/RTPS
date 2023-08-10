<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Check_aadhaar_model extends Mongo_model
{

    public function __construct()
    {
        parent::__construct();
        $this->set_collection("hash_codes");
    }

    public function get_row($filter = null) {

        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if (count((array)$res)) {
            return count((array)$res);
        } else {
            return 0;
        }         
    }

}
