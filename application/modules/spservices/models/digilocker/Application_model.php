<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Application_model extends Mongo_model
{
    function __construct()
    {
        parent::__construct();
    }

    public function get_data($filter, $db, $collection)
    {
        $this->mongo_db->switch_db($db);
        $this->mongo_db->where($filter);
        $res = (array)$this->mongo_db->get($collection);
        return $res;
    }

    public function get_count($match, $db, $collection)
    {
        $this->mongo_db->switch_db($db);
        $this->mongo_db->where($match);
        $res = $this->mongo_db->find_one($collection);
        if (count((array)$res)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function save_uri($data)
    {
        $this->mongo_db->switch_db("iservices");
        $this->mongo_db->insert('digilocker_uri', $data);

        // $check_entry = (array)$this->mongo_db->where('rtps_no', $data['rtps_no'])->get('digilocker_uri');
        // if (empty(count($check_entry))) {
        //     $this->mongo_db->insert('digilocker_uri', $data);
        // }
    }
}
