<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Outapi_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('api_keys');
    }
}
