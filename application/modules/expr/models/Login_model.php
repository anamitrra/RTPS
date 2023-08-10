<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Login_model extends Mongo_model {

  public $table = 'front_users';
  public $id = 'userId';
  public $order = 'DESC';

  public function checkMobileExist($mobile) {

    $count = $this->mongo_db->mongo_like_count(array('mobile' => $mobile), 'front_users');
    if ($count > 0) {
        return $this->get_user($mobile);
    } else {
        $save_user=array(
          "email"=>"",
          "name"=>"",
          "password"=>"",
          "mobile"=>$mobile,
          "createdDtm"=> new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
          'updatedDtm'=> ""
        );
        $result=$this->mongo_db->insert($this->table,$save_user);
        if($result){
          return $this->get_user($mobile);
        }else {
          return false;
        };
    }
  }
  function get_user($mobile){
    $this->mongo_db->select("*");
    $this->mongo_db->where(array('mobile'=>$mobile));
    $res=$this->mongo_db->find_one($this->table);
    return $res;
  }


}
