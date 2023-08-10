
<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class User_model extends Mongo_model {

  function __construct()
  {
      parent::__construct();
      $this->set_collection("users");
  }
  public function get_da_list($dist) {
	  
	 // new MongoDB\BSON\ObjectId
    $response = array();
    $this->mongo_db->select('*');
    return $da = $this->mongo_db->where(['designation'=>'DA', 'district'=>$this->session->userdata('district')])->get($this->table);
    // return $response[] = $da;
    // $user = $this->mongo_db->find_one($this->table);
  }
  
  public function get_co_list($dist) {
     $response = array();
     $this->mongo_db->select('*');
     return $co = $this->mongo_db->where(['designation'=>'CO', 'district'=>$dist])->get($this->table);
     // return $response[] = $da;
     // $user = $this->mongo_db->find_one($this->table);
   }

   public function get_sk_list($dist) {
    $response = array();
    $this->mongo_db->select('*');
    return $co = $this->mongo_db->where(['designation'=>'SK', 'district'=>$dist])->get($this->table);
  }

  public function get_lm_list($dist) {
    $response = array();
    $this->mongo_db->select('*');
    return $co = $this->mongo_db->where(['designation'=>'LM', 'district'=>$dist])->get($this->table);
  }
}