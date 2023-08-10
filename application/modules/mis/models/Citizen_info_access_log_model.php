<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Citizen_info_access_log_model extends Mongo_model
{
    function __construct()
    {
        parent::__construct();
        $this->set_collection("citizen_info_access_log");
    }
}