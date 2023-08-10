<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Office_admin_model extends Mongo_model
{

    public function __construct()
    {
        parent::__construct();
        $this->set_collection("minority_admin");
    } //End of __construct()


    // * Get admin by name as id
    function getByUserName($uname)
    {
        $admin = $this->mongo_db->where(["username" => $uname])->get($this->table);
        return $admin;
    }

    // * Get state admin info
    function getAdminDetails($id)
    {
        // pre($id);
        $adminDetails = $this->mongo_db->where(["_id" => new ObjectId($id)])->get($this->table);
        return $adminDetails;
    }

    // * Get admin info
    function get_rows($filter)
    {
        $res = $this->mongo_db->where($filter)->find_one($this->table);
        if (count((array)$res)) {
            return $res;
        } else {
            return false;
        }
    }
}
