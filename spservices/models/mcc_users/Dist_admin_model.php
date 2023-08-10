<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dist_admin_model extends Mongo_model
{

    public function __construct()
    {
        parent::__construct();
        $this->set_collection("minority_admin");
    } //End of __construct()


    function getDistAdmin()
    {

        $admins = $this->mongo_db->where(array('role' => 'DA'))->order_by('_id', 'DESC')->get($this->table);

        return $admins;
    }

    function get_single_dist_admin($id)
    {
        $admin = $this->mongo_db->where(["_id" => new MongoDB\BSON\ObjectID($id)])->get($this->table);
        return $admin;
    }

    public function updateadmin($data, $id)
    {

        $option = array('upsert' => true);

        $this->mongo_db->where(["_id" => new ObjectId($id)]);
        $this->mongo_db->set($data);

        return $this->mongo_db->update($this->table, $option);
    }


    public function getDistNames($dist_name)
    {
        $data = $this->mongo_db->select(['district_name'])->where(['district_name' => $dist_name])->get($this->table);
        return $data;
    }

    public function checkUserName($username)
    {
        $data = $this->mongo_db->where(['username' => $username])->get($this->table);
        return $data;
    }
}
