<?php
use MongoDB\BSON\ObjectId;
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Registration_model extends Mongo_model {

    public function __construct() {
        parent::__construct();
        $this->set_collection("sp_applications");
    }//End of __construct()

    public function getSubjects() {
        // $query = $this->mongo_db->select(array('subject_name','subject_code'))->order_by(array('stream' => 'asc'))->get('ahsec_subjects');         
        // if(count((array)$query)) {
        //   return $query;
        //   } else {
        //       return false;
        //   }

        $aggregation = array(
            array(
                '$group' => array(
                    '_id' => '$subject_name',
                    'subject_code' => array('$first' => '$subject_code'),
                    'subject_name' => array('$first' => '$subject_name')
                )
            ),
            array(
                '$sort' => array(
                    '_id' => 1 // Sort by subject_name in ascending order
                )
            )
        );
        
        $query = $this->mongo_db->aggregate('ahsec_subjects', $aggregation);
        
        if ($query) {
            return $query;
        } else {
            return false;
        }
    }

    public function get_stream_subjects($filter = null) {
        $this->mongo_db->select(array('subject_name','subject_code'));
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get('ahsec_subjects');
        if(count((array)$res)) {
            return $res;
        } else {
            return false;
        }//End of if else        
    }//End of get_rows()

    
    public function getCommencings() {
        $query = $this->mongo_db->select(array('year','date','month'))->order_by(array('year' => 'desc'))->get('ahsec_commencing');         
        if(count((array)$query)) {
          return $query;
          } else {
              return false;
          }
    }
    
    public function getStates() {
        $query = $this->mongo_db->select(array('state_name_english','state_code'))->order_by(array('state_name_english' => 'asc'))->get('states');         
        if(count((array)$query)) {
          return $query;
          } else {
              return false;
          }
    }

    public function getDistricts() {
        $query = $this->mongo_db->select(array('district_name','state_id','district_id'))->order_by(array('district_name' => 'asc'))->get('districts');         
      if(count((array)$query)) {
        return $query;
        } else {
            return false;
        }
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

    public function rows_count($filter = null) {
        $this->mongo_db->order_by('created_at', 'DESC');
        $this->mongo_db->where($filter);
        $res = $this->mongo_db->get($this->table);
        if(count((array)$res)) {
            return count((array)$res);
        } else {
            return 0;
        }//End of if else        
    }//End of get_rows()

    public function get_rows_where_in($filter = null,$field,$inArray) {
        $this->mongo_db->where($filter);
        $this->mongo_db->where_in($field, $inArray);
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
}
