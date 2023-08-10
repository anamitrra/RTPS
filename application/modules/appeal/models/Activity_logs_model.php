<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Activity_logs_model extends CI_Model {
  public $table = 'user_logs';
  public $col = 'timestamp';
  public $dir = 'DESC';

  function __construct() {
    parent::__construct();
  }

  // get all records
  function all_rows($limit, $start, $col, $dir, $filter = NULL) {
    $this->mongo_db->limit($limit, $start);
    $this->mongo_db->order_by($col, $dir);
    $query = $this->mongo_db->get_where($this->table, array('session_id' => $filter));
    return $query;
  }

  // get total rows count
  function total_rows($filter = NULL) {
    return $this->mongo_db->mongo_like_count(array('session_id' => $filter), $this->table);
  }

}
