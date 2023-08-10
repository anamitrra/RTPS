<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Office_user_manage_model extends Mongo_model
{

    public function __construct()
    {
        parent::__construct();
        $this->set_collection("office_users");
    } //End of __construct()



    function getOfficeUsers($district = null)
    {
        $collection = 'office_users';
        if (strlen($district)) {
            $operations = array(
                ['$match' => ["district_name" => $district]],
                ['$addFields' => ["userId" => ['$toString' => '$_id']]],
                ['$lookup' => [
                    "from" => "mcc_official_sts_txn",
                    "localField" => "userId",
                    "foreignField" => "user_id",
                    "as" => "sts_txn"
                ]]
            );
        } else {
            $operations = array(
                ['$addFields' => ["userId" => ['$toString' => '$_id']]],
                ['$lookup' => [
                    "from" => "mcc_official_sts_txn",
                    "localField" => "userId",
                    "foreignField" => "user_id",
                    "as" => "sts_txn"
                ]]
            );
        }
        // $users = $this->mongo_db->order_by('_id', 'DESC')->get($this->table);
        $data = $this->mongo_db->aggregate($collection, $operations);
        return $data;
    }


    function get_single_office_user($id)
    {
        $user = $this->mongo_db->where(["_id" => new MongoDB\BSON\ObjectID($id)])->get($this->table);
        return $user;
    }


    function get_same_mobile_office_user($number)
    {
        $data = $this->mongo_db->select(['mobile', 'is_active'])->where(['mobile' => $number])->get("office_users");
        return $data;
    }


    public function get_rows($filter = null)
    {
        $this->mongo_db->order_by('created_at', 'DESC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if (count((array)$res)) {
            return $res;
        } else {
            return false;
        } //End of if else        
    } //End of get_rows()



    public function updateUser($data, $id)
    {
        $option = array('upsert' => true);
        $this->mongo_db->where(["_id" => new ObjectId($id)]);
        $this->mongo_db->set($data);
        return $this->mongo_db->update($this->table, $option);
    }
}
