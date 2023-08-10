<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

define('SYS_ADMIN', ['id' => 1, 'display_name' => 'System Administrator']);
define('DEPT_ADMIN', ['id' => 2, 'display_name' => 'Department Administrator']);
define('DPS', ['id' => 3, 'display_name' => 'DPS']);
define('APPELLATE_AUTH', ['id' => 4, 'display_name' => 'Appellate Authority']);
define('REVIEWING_AUTH', ['id' => 5, 'display_name' => 'Reviewing Authority']);

class User_role
{
    protected $roleList = [];

    public function __construct()
    {
        $this->roleList = ['SYS_ADMIN' => SYS_ADMIN, 'DEPT_ADMIN' => DEPT_ADMIN, 'DPS' => DPS, 'APPELLATE_AUTH' => APPELLATE_AUTH, 'REVIEWING_AUTH' => REVIEWING_AUTH];
    }

    /**
     * @return array|array[]
     */
    public function getRoleList()
    {
        return $this->roleList;
    }

}