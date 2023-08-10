<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Artps_model extends Mongo_model
{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('artps_services');
    }

    public function get_all_services()
    {
        $this->mongo_db->select([], ['_id']);
        return (array)$this->get_all([]) ;
    }

}