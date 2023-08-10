<?php 
defined('BASEPATH') or exit('No direct script access allowed');
class Kys_model extends Mongo_model{
    public function __construct()
    {
        parent::__construct();
        $this->set_collection('services');
        
    }

    public function get_by_service_id($service_id)
    {
    
        return $this->get_all(['service_id' => $service_id]);
    }

    public function get_by_seo_url($url)
    {
    
        return $this->get_all(['seo_url' => $url]);
        
        // $data1= $this->get_all(['service_id'=>$url]);
        // // pre($data1);
        // return $data1;
    }
   
}