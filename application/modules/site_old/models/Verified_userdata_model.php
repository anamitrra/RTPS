<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Verified_userdata_model extends Mongo_model
{
    function __construct()
    {
        parent::__construct();
        $this->set_collection('verified_userdata');
    }

}