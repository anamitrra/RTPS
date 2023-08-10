<?php

if(!defined('BASEPATH')) exit('No direct script access allowed');

class Kiosk_operator_model extends Psql_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->table = 'schm_sp.kiosk_operators';
    }
}