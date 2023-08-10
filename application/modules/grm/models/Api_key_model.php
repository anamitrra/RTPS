<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Api_key_model extends Mongo_model
{
    public function __construct()
    {
        $this->set_collection("keys");
    }

}