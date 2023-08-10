<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dashboard_model extends Mongo_model
{

    public function __construct()
    {
        parent::__construct();
    }
    public function getOfficesByDepartmentID($dept_id)
    {
        $this->set_collection("offices");
        return (array)$this->get_where(['department_id'=>$dept_id]);
    }
}
