<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}
ini_set('MAX_EXECUTION_TIME', -1);
use MongoDB\BSON\UTCDateTime;

class Digilocker_model extends Mongo_model
{

  function __construct()
  {
    parent::__construct();
    $this->set_collection("front_users");
  }

  public function checkMobileExist($mobile, $data)
  {

    $count = $this->mongo_db->mongo_like_count(array('mobile' => $mobile), 'front_users');
    if ($count > 0) {
      $user = $this->get_user($mobile);
      $save_user = array(
        "email" => ($user->email) ? $user->email : ($data->email ?? ''),
        "name" => ($user->name) ? $user->name : $data->name,
        "password" => "",
        "epramaan_res" => $data,
        "updatedDtm" => new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
      );
      $option = array('upsert' => true);
      $this->mongo_db->where(array('mobile' => $mobile))->set($save_user)->update($this->table, $option);
      return $this->get_user($mobile);
    } else {
      $save_user = array(
        "email" => ($data->email) ? $data->email : '',
        "name" => $data->name,
        "password" => "",
        "mobile" => $mobile,
        "epramaan_res" => $data,
        "createdDtm" => new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
        'updatedDtm' => ""
      );
      // return $save_user;
      $result = $this->mongo_db->insert($this->table, $save_user);
      if ($result) {
        return $this->get_user($mobile);
      } else {
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

  public function check_consent($mobile)
  {
    $data = (array)$this->mongo_db->where(array('mobile' => $mobile))->get('front_users');
    $res = array(
      'consent' => isset($data[0]->digilocker_consent) ? $data[0]->digilocker_consent : ''
    );
    return isset($data[0]->digilocker_consent) ? $data[0]->digilocker_consent : '';
  }

  public function save_digilocker_uri($data)
  {
    $check_entry = (array)$this->mongo_db->where('rtps_no', $data['data']['rtps_no'])->get('digilocker_uri');
    $this->mongo_db->insert('digilocker_uri', $data['data']);

    // if (empty(count($check_entry))) {
    //   $this->mongo_db->insert('digilocker_uri', $data['data']);
    // } else {
    //   $udata = [
    //     'file_path' => $data['data']['file_path'],
    //     'uri' => $data['data']['uri'],
    //     'updated_at' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
    //   ];
    //   $option = array('upsert' => true);
    //   $this->mongo_db->where(array('rtps_no' => $data['data']['rtps_no']))->set($udata)->update('digilocker_uri', $option);
    // }
  }

  public function save_push_log($logData){
    $this->mongo_db->insert('digilocker_push_log', $logData);
  }
  
  public function save_digilocker_consent($sts)
  {
    if ($sts == 1) {
      $data = [
        'digilocker_consent' => 1,
        'consent_data' => array(
          'digilocker_data' => $this->session->userdata('token_details'),
          'client_ip' => $this->input->server('REMOTE_ADDR'),
          'client_browser' => $_SERVER['HTTP_USER_AGENT'],
          'ts' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
        )
      ];
    } else {
      $data = [
        'digilocker_consent' => 0,
        'consent_data' => array(
          'digilocker_data' => '',
          'client_ip' => $this->input->server('REMOTE_ADDR'),
          'client_browser' => $_SERVER['HTTP_USER_AGENT'],
          'ts' => new UTCDateTime(strtotime(date('d-m-Y g:i a')) * 1000)
        )
      ];
    }

    $checkDigilockerConsent = (array)$this->mongo_db->where('mobile', $this->session->userdata('mobile'))->get('front_users');
    if (isset($checkDigilockerConsent[0]->digilocker_consent)) {
      if ($checkDigilockerConsent[0]->digilocker_consent == 0) {
        // echo 'no yes'; need to insert
        $option = array('upsert' => true);
        $this->mongo_db->where(array('mobile' => $this->session->userdata('mobile')))->set($data)->update('front_users', $option);
      }
    } else {
      // echo 'fresh '; need to insert
      $option = array('upsert' => true);
      $this->mongo_db->where(array('mobile' => $this->session->userdata('mobile')))->set($data)->update('front_users', $option);
    }
    // pre($checkDigilockerConsent);
  }

  // checking for pfc user account
  public function getUserMobileByEmail($email) {
    $this->mongo_db->select("*");
    $this->mongo_db->where(array('email'=>$email,'isDeleted'=>'0'));
    return $this->mongo_db->find_one('users');
  }

  public function updatePfcdata($email, $data){
    $save_user = array(
      "epramaan_res" => $data,
      "epramaan_linked" => new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
    );
    $this->mongo_db->where(array('email'=>$email,'isDeleted'=>'0'))->set($save_user)->update('users');
  }
}
