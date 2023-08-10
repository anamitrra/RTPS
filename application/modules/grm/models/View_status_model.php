<?php


class View_status_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection("view_status");
    }
}