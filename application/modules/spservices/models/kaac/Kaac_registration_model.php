<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Kaac_registration_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("sp_applications");
    }//End of __construct()
    
    public function get_row($filter = null) {
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->find_one($this->table);
        // pre($res);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_row()
    
    public function get_rows($filter = null) {
        $this->mongo_db->order_by('created_at', 'DESC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()
    public function add_param($where,$params){
        $this->mongo_db->set($params);
        $this->mongo_db->where($where);
        return $this->mongo_db->update($this->table);
    } 
    public function update_row($where,$params){
        $this->mongo_db->set($params);
        $this->mongo_db->where($where);
        return $this->mongo_db->update($this->table);
    }
    public function update_payment_status($appl_ref_no,$data){
        $this->mongo_db->set($data);
        $this->mongo_db->where(array("service_data.appl_ref_no"=> $appl_ref_no));
        return $this->mongo_db->update('sp_applications');
    }

    public function apiCall($url){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);

        $response = curl_exec($ch);

        if (curl_error($ch)) {
            echo 'Request Error:' . curl_error($ch);
        }
        curl_close($ch);
        
        return $response;
    }

}
