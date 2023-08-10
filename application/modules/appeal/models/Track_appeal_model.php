<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Track_appeal_model extends Mongo_model
{
    function __construct()
    {
        parent::__construct();
        $this->set_collection('track_appeal_login_otp');
    }

    public function searchAppeal($appl_no,$mobile)
    {
        $this->mongo_db->where("appeal_id",$appl_no);
        $this->mongo_db->where("contact_number",$mobile);
        return $this->mongo_db->find_one("appeal_applications");
    }

    public function opt_insert($data)
    {
//        $this->set_collection("track_appeal_login_otp");
        $this->insert($data);
    }

    public function verify_otp($appl_no,$mobile,$otp,array $filter = [])
    {
        $this->mongo_db->where("appl_no",$appl_no);
        $this->mongo_db->where("mobile",$mobile); 
        $this->mongo_db->where("otp",$otp); 
        if(!empty($filter)){
            $this->mongo_db->where($filter); 
        }
        return $this->mongo_db->get($this->table);
    }
    
}