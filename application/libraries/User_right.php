<?php

define('CREATE', ['id' => 1, 'display_name' => 'Create']);
define('READ', ['id' => 2, 'display_name' => 'Read']);
define('UPDATE', ['id' => 3, 'display_name' => 'Update']);
define('DELETE', ['id' => 4, 'display_name' => 'Delete']);

class User_right
{
    protected $rightList = [];

    public function __construct()
    {
        $this->rightList = ['CREATE' => CREATE, 'READ' => READ, 'UPDATE' => UPDATE, 'DELETE' => DELETE];
    }

    /**
     * @return array|array[]
     */
    public function getRightList()
    {
        return $this->rightList;
    }
}