<?php
defined('BASEPATH') or exit('No direct script access allowed');

/* A Demo Contoller for testing */

class Demo extends Site_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->mongo_db->switch_db('nic_db');
    }
    public function index()
    {
        pre('This is a demo page');
    }

    public function read_data($how_many = 100000)
    {
        $this->mongo_db->where([]);
        $this->mongo_db->limit($how_many);
        $data = $this->mongo_db->get('demo_collection');
        pre($data);
    }
    function write_data($how_many = 100000)
    {
        for ($i = 1; $i <= $how_many; $i++) {
            $data =  [
                'username' => "user_{$i}",
                'password' => '#########',
                'user_type' => 'Male',
                'gender' => 'Female',
                'email' => "user_{$i}@example.com",
            ];

            $res[] =  ($this->mongo_db->insert('demo_collection', $data))['_id'];
        }
        var_dump($res);
    }
}
