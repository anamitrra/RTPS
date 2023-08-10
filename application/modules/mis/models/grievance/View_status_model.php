<?php


class View_status_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->mongo_db->switch_db("grievances");
        $this->set_collection("view_status");
    }
}