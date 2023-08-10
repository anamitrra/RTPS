<?php


class Template_model extends Mongo_model
{

    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->set_collection("templates");
    }
}