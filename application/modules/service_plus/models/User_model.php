<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends Psql_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'schm_sp.m_adm_sign';
    }
}