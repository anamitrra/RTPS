<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ulb_list extends Mongo_model
{

    public function __construct()
    {
        parent::__construct();
        $this->set_collection("ulb_list");
    } //End of __construct()



    function get_ulb_list()
    {

        $ulb_list = $this->mongo_db->order_by('_id', 'DESC')->get($this->table);

        return $ulb_list;
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



}
