<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');


class Application_processing_json_model extends Psql_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'sp_custom.application_processing_json';
    }

}