<?php


class Reset_password_model extends mongo_model
{
    function __construct()
    {
        parent::__construct();
        $this->set_collection("reset_password");
    }


}