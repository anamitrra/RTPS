<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class District_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->mongo_db->switch_db("grievances");
        $this->set_collection("districts");
    }

    public function get_by_desc(){
        return $this->mongo_db->order_by('distname','ASC')->get($this->table);
    }

}