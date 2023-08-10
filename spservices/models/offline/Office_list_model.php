
<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
class Office_list_model extends Mongo_model
{
    /**
     * __construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->set_collection("offline_office");
    }
    public function get_offices(){
        $res = $this->mongo_db->get($this->table);
        return $res;
    }
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
    public function update_row($fillter,$data){
        $this->mongo_db->set($data);
        $this->mongo_db->where($fillter);
        return $this->mongo_db->update($this->table);
      }
   

      function is_exist_office_id($office_id){
       
        $count = $this->mongo_db->mongo_like_count(array('office_id' => $office_id), $this->table);
        if($count > 0){
          return true;
        }else {
          return false;
        }
  }
  
  public function applications_filter($limit, $start, $col, $dir)
    {
        $filter["is_offline"]= true;
        $this->set_collection($this->table);
        return $this->search_rows($limit, $start, $filter, $col, $dir);
    }

    public function total_app_rows( ){
    
        $filter["is_offline"]= true;
        $this->set_collection($this->table);
        return $this->tot_search_rows($filter);
      
    }


    public function application_search_rows($limit, $start, $keyword, $col, $dir )
    {
        $filter["is_offline"]= true;
        $temp = array(
            '$and' => [$filter],
            '$or'=>[
              ['service_name' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
              ['service_id' =>['$regex'=>'^' . $keyword . '','$options' => 'i']]
            ]
        );
        $this->set_collection($this->table);
        return $this->search_rows($limit, $start, $temp, $col, $dir);
    }

}
