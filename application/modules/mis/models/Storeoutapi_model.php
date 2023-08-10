<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Storeoutapi_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->set_collection("out_api");
    }
}