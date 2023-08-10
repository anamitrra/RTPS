<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
class Holiday_model extends Mongo_model
{
    function __construct()
    {
        parent::__construct();
        $this->set_collection("holiday_list");
    }
}