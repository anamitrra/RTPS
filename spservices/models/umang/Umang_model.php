
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
class Umang_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->set_collection("sp_applications");
    }

    public function add_row($data = null)
    {
        $res = $this->mongo_db->insert($this->table,$data );
        if (count((array)$res)) {
            return $res;
        } else {
            return false;
        } //End of if else        
    } //End of get_row() 
    public function get_row($filter = null)
    {
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->find_one($this->table);
        if (count((array)$res)) {
            return $res;
        } else {
            return false;
        } //End of if else        
    } //End of get_row() 
    public function get_app_ref($filter = null)
    {
        $this->mongo_db->where($filter);
        $this->mongo_db->select(array('service_data'));
        $res = $this->mongo_db->find_one($this->table);
        if (count((array)$res)) {
            return $res;
        } else {
            return false;
        } //End of if else        
    } //End of get_row()
    public function update_row($fillter,$data){
        $this->mongo_db->set($data);
        $this->mongo_db->where($fillter);
        return $this->mongo_db->update($this->table);
      }
    function is_exist_transaction_no($rtps_trans_id){
        $operations = array("service_data.appl_ref_no" => $rtps_trans_id);         
        $count = $this->mongo_db->mongo_like_count($operations , 'sp_applications');
        if($count > 0){
          return true;
        }else {
          return false;
        }
  }
}
