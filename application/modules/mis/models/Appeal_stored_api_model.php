<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Appeal_stored_api_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->mongo_db->switch_db("appeal");
        $this->table = 'stored_api';
    }
}