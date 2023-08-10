<?php

if(!defined('BASEPATH')) exit('No direct script access allowed');

class Application_detail_page_model extends Psql_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'schm_sp.application_detail_pages';
    }
}