<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    private $table="applications";

    public function __construct()
    {
        parent::__construct();
    }
}

class Mongo_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    public $table;
    public $order = 'DESC';

    public function set_collection($table = "applications", $order = "DESC")
    {
        $this->table = $table;
        $this->order = $order;
    }

    public function get_by_doc_id($id)
    {
        $this->mongo_db->where("_id", new MongoDB\BSON\ObjectId($id));
        return $this->mongo_db->find_one($this->table);
    }

    
    public function get_all($filter)
    {
        $this->mongo_db->where($filter);
        return $this->mongo_db->get($this->table);
    }
    
    public function total_rows($table = null)
    {
        if($table!=null){
            return $this->mongo_db->mongo_count($table);
        }else{
            return $this->mongo_db->mongo_count($this->table);
        }
        
    }
    // get search data with limit
    //All_rows
    public function all_rows($limit, $start, $col, $dir, $filter = null)
    {
        if(!empty($filter)){
            $this->mongo_db->where($filter);
        }
        $this->mongo_db->limit($limit, $start);
        $this->mongo_db->order_by($col, $dir);
        $query = $this->mongo_db->get($this->table);
        return $query;
    }
    public function search_rows($limit, $start, $keyword, $col, $dir)
    {
        //$this->mongo_db->like("$limit", $keyword);
        $this->mongo_db->limit($limit, $start);
        $this->mongo_db->order_by($col, $dir);
        return $this->mongo_db->get_data_like($keyword, $this->table);
    } //End of search_rows()
    public function tot_search_rows($keyword)
    {
        return $this->mongo_db->mongo_like_count($keyword, $this->table);
    } //End of tot_search_rows()
    // insert data
    public function insert($data)
    {
        return $this->mongo_db->insert($this->table, $data);
    }
   
    // update data
    public function update($id, $data)
    {
        $this->mongo_db->set($data);
        $this->mongo_db->where("_id", new MongoDB\BSON\ObjectId($id));
        return $this->mongo_db->update($this->table, $data);
    }

    // delete data
    public function delete($id)
    {
        $this->mongo_db->where("_id", new MongoDB\BSON\ObjectId($id));
        return $this->mongo_db->delete($this->table);
    }

    // delete by filter
    public function delete_by_filter($filter = array())
    {
        $this->mongo_db->where($filter);
        return $this->mongo_db->delete_all($this->table);
    }

    // get where
    public function get_where($filter,$value = ''){
        if (is_array($filter)) {
            $this->mongo_db->where($filter);
        }else{
            $this->mongo_db->where($filter,$value);
        }
        return $this->mongo_db->get($this->table);
    }

    //update where
    public function update_where($filter,$data){
        $this->mongo_db->set($data);
        $this->mongo_db->where($filter);
        $this->mongo_db->update_all($this->table);
    }

    // get where in array
    public function get_where_in($field,$inArray,$columns = []){
        $rows = $this->mongo_db->where_in($field, $inArray);
        if(!empty($columns)){
            $rows = $rows->select($columns);
        }
        return $rows->get($this->table);
    }

    // first where in array
    public function first_where($filter,$value = ''){
        if (is_array($filter)) {
            $this->mongo_db->where($filter);
        }else{
            $this->mongo_db->where($filter,$value);
        }
        return $this->mongo_db->find_one($this->table);
    }

    // last where in array
    public function last_where($filter,$value = ''){
        if (is_array($filter)) {
            $this->mongo_db->where($filter);
        }else{
            $this->mongo_db->where($filter,$value);
        }
        return $this->mongo_db->order_by('_id','DESC')->find_one($this->table);
    }

    public function get_selected($includeField = [],$excludeField = []){
        return $this->mongo_db->select($includeField,$excludeField)->get($this->table);
    }

    public function search_selected_rows($limit, $start, $keyword, $col, $dir,$selectInclude = [],$selectExclude = [])
    {
        //$this->mongo_db->like("$limit", $keyword);
        if(!empty($selectInclude) || !empty($selectExclude)){
            $this->mongo_db->select($selectInclude,$selectExclude);
        }
        $this->mongo_db->limit($limit, $start);
        $this->mongo_db->order_by($col, $dir);
        return $this->mongo_db->get_data_like($keyword, $this->table);
    }

    public function distinct_data($field){
        return $this->mongo_db->distinct($this->table,$field);
    }

    // get where with selected column
    public function get_where_selected($filter,$colInclude = [], $col_exclude = []){
        if (is_array($filter)) {
            $this->mongo_db->where($filter);
        }
        if(!empty($colInclude)){
            $this->mongo_db->select($colInclude,$col_exclude);
        }
        return $this->mongo_db->get($this->table);
    }

    public function get_filtered($filterArray){
        return $this->mongo_db->get_data_like($filterArray, $this->table);
    }
}
