<?php

use MongoDB\BSON\ObjectId;

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Circle_model extends Mongo_model
{

    public function __construct()
    {
        parent::__construct();
        $this->set_collection("nchac_revenue_circle");
    } //End of __construct()

    public function get_row($filter = null)
    {
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->find_one($this->table);
        if (count((array)$res)) {
            return $res;
        } else {
            return false;
        } //End of if else        
    } //End of get_row()

    public function get_rows()
    {
        $this->mongo_db->order_by('created_at', 'DESC');
        $res = $this->mongo_db->get($this->table);
        if (count((array)$res)) {
            return $res;
        } else {
            return false;
        } //End of if else        
    } //End of get_rows()
}
