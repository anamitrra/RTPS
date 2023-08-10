<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("upms_users");
    }//End of __construct()
    
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
        
    public function get_total_rows($filter = null) {
        $this->mongo_db->where($filter);
        return $this->mongo_db->count($this->table);
    }//End of get_total_rows()

    public function get_search_rows($limit, $start, $keyword, $col, $dir, $filter) {
        $filter['$or'] = array(
            ['user_fullname' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            ['mobile_number' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
            ['login_username' =>['$regex'=>'^' . $keyword . '','$options' => 'i']]
        );
        $this->mongo_db->limit($limit, $start);
        $this->mongo_db->order_by($col, $dir);
        $this->mongo_db->where($filter);
        return $this->mongo_db->get($this->table);
    }//End of get_search_rows()

    public function get_tot_search_rows($keyword) {
        $customFilter = array(
            '$or'=>[
                ['user_fullname' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
                ['mobile_number' =>['$regex'=>'^' . $keyword . '','$options' => 'i']],
                ['login_username' =>['$regex'=>'^' . $keyword . '','$options' => 'i']]
              ]
        );
        return $this->tot_search_rows($customFilter);
    }//End of get_tot_search_rows()
}//End of Users_model