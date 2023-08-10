<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Registration_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("sp_applications");
    }
    
    public function get_row($filter = null) {
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->find_one($this->table);
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
    public function get_sro_list($id){

        $fillter=  array(
            '$and' => array(
              [
              'parent_org_unit_code'=>"$id",
              "office_code"=>array("\$exists" => true,'$ne'=>''),
              "treasury_code"=>array("\$exists" => true,'$ne'=>'')
              ]
            )
               
        );

        $this->mongo_db->select('*');
        $this->mongo_db->where($fillter);
       return $this->mongo_db->get('sro_list');
    } 
    public function update_payment_status($appl_ref_no,$data){
        $this->mongo_db->set($data);
        $this->mongo_db->where(array("service_data.appl_ref_no"=> $appl_ref_no));
        return $this->mongo_db->update('sp_applications');
    }

    public  function append_records($obj_id,$inputs)
	{ 
        // $this->mongo_db->set($data);
         $this->mongo_db->where(array("_id"=> $obj_id))->insert('sp_applications',$inputs);
        //$this->mongo_db->where(['_id' => new ObjectId($obj_id)])->insert('sp_applications',$inputs);
    }
}
