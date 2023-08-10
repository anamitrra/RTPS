<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Status extends Rtps
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('intermediator_model');
    $this->load->model('portals_model');
    $this->config->load('rtps_services');
    $this->load->library('AES');
    $this->load->library('AESrathi');
    $this->encryption_key = $this->config->item("encryption_key");
  }

  public function index()
  {

    $data = array("pageTitle" => "check Status");


    $this->load->view('includes/frontend/header');
    $this->load->view('check_status', $data);
    $this->load->view('includes/frontend/footer');
  }
  public function check_status()
  {

    $outputdata = array();
    if (!isset($_GET['app_ref_no']) || empty($_GET['app_ref_no'])) {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'status' => "something went wrong",

        )));
      return;
    }
    $app_ref_no = $_GET['app_ref_no'];
    $res = $this->intermediator_model->get_userid_by_application_ref($app_ref_no);
    $user_mobile = $res->mobile;
    $service_id = $res->service_id;
    $rtps_trans_id=$res->rtps_trans_id;
    $status_url = $this->portals_model->get_departmental_data($service_id);
    $portal_no=$status_url->portal_no;
    $status_url = $status_url->status_url;
    if (empty($status_url)) {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'status' => "Status url not define",

        )));
      return;
    }

    $data = array(
      "app_ref_no" => $app_ref_no, //'NOC/05/143/2020'
      "mobile" => $user_mobile //"9435347177"
    );
    $input_array = json_encode($data);
    $aes = new AES($input_array, $this->encryption_key);
    $enc = $aes->encrypt();
    //curl request

    $post_data = array('data' => $enc);
    $curl = curl_init($status_url);
    // if($portal_no !== "1" || $portal_no == 1){
    // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    // }
    curl_setopt($curl, CURLOPT_POST, true);
    if($portal_no === "1" || $portal_no === 1){
      curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    }else{
      curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));
    }
    
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($curl);


    curl_close($curl);

    if ($response) {
      $response = json_decode($response);
    }

    if($portal_no === "7" || $portal_no === 7){
      if (isset($response->d) && !empty($response->d)) {
        $aes->setData($response->d);
        $dec = $aes->decrypt();
        $outputdata = json_decode($dec);
      }
    }else{
        //decryption
        if (isset($response->data) && !empty($response->data)) {
          $aes->setData($response->data);
          $dec = $aes->decrypt();
          $outputdata = json_decode($dec);
        }
    }
    

    $data = array("pageTitle" => "Application Status");

    $data['result'] = $outputdata;
    if($outputdata){
      $this->save_tracking_data($outputdata,$service_id,$rtps_trans_id);
    }
    
    if (!empty($this->session->userdata('role')) && ($this->session->userdata('role')->slug === "PFC" || $this->session->userdata('role')->slug === "SA")) {
      $this->load->view('includes/header');
      $this->load->view('status', $data);
      $this->load->view('includes/footer');
    } else {
      $this->load->view('includes/frontend/header');
      $this->load->view('status', $data);
      $this->load->view('includes/frontend/footer');
    }
  }

  private function save_tracking_data($res,$service_id,$rtps_trans_id){
    
    $data_to_save=array();
        if($res){
          $status='P';


          $task=$res->task_details;
          $final_task=array_reverse($res->task_details);
         
          if( $final_task){
            if($final_task[0]->action === "Reject" || $final_task[0]->action === "reject")
              $status='R';

              if($service_id == 14 || $service_id == '14' ){
                if($final_task[0]->action === "Delivered" || $final_task[0]->action === "delivered")
                $status='D';
              }else{
                if($final_task[0]->action === "Complete" || $final_task[0]->action === "complete")
                $status='D';
              }
         
              if($final_task[0]->action === "Query" || $final_task[0]->action === "query")
              $status='Q';
              
              if($final_task[0]->action === "Forward" || $final_task[0]->action === "forward")
              $status='F';
          }
         
          $data_to_save['execution_data']=$res->task_details;
          $data_to_save['delivery_status']=$status;
          if(property_exists($final_task[0],'submission_location')){
            $data_to_save['current_location']=$final_task[0]->submission_location;
          }
          if(property_exists($task[0],'submission_location')){
            $data_to_save['submission_location']=$task[0]->submission_location;
          }
          if(property_exists($task[0],'district')){
            $data_to_save['district']=$task[0]->district;
          }
          if(property_exists($task[0],'circle')){
            $data_to_save['circle']=$task[0]->circle;
          }
          $data_to_save['execution_date']=isset($final_task[0]->executed_time)?$final_task[0]->executed_time : '';

          $this->intermediator_model->add_param($rtps_trans_id,$data_to_save);
        
        }
  }

  public function check_vahan_status()
  {
    $encryption_key = $this->config->item("agencyKey");
    $return_url = base_url("iservices");
    $outputdata = array();
    if (!isset($_GET['vahan_app_no']) || empty($_GET['vahan_app_no'])) {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'status' => "something went wrong",

        )));
      return;
    }
    $vahan_app_no = $_GET['vahan_app_no'];
    $status_url = $this->config->item("vahan_status_url");

    if (empty($status_url)) {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'status' => "Status url not define",

        )));
      return;
    }
    $str = $vahan_app_no . '/AS' . "/" . $return_url;
    // pre( $str);
    $aes = new AESrathi($str, $encryption_key, 128);
    $inp = $aes->encrypt();
    //pre( $inp);
    // var_dump($status_url.$inp);die;
    $url = $status_url . "?data=" . $inp;
    // pre( $url);
    redirect($url);
    return false;
    exit();

    //below code is not required for now beacuse they change the api
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Cookie: SERVERID_vahanservice_81=vahan_ser81'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $index = "";


    // $response=$this->decrypt(array("result"=>$response));
    if ($response) {
      $aes->setData($response);
      $response = $aes->decrypt();
      $response = (array)json_decode($response);

      foreach ($response as $key => $value) {
        $index = $key;
        break;
      }
    }

    $data = array("pageTitle" => "Application Status");

    $data['result'] = empty($response[$index])  ? array() : $response[$index];
    if (!empty($this->session->userdata('role')) && ($this->session->userdata('role')->slug === "PFC" || $this->session->userdata('role')->slug === "SA")) {
      $this->load->view('includes/header');
      $this->load->view('vahan_status', $data);
      $this->load->view('includes/footer');
    } else {
      $this->load->view('includes/frontend/header');
      $this->load->view('vahan_status', $data);
      $this->load->view('includes/frontend/footer');
    }
  }

  public function getRequestInfo()
  {
    $app_ref_no = $this->input->post('app_ref_no');
    $mobile = $this->input->post('mobile');
    $user = $this->session->userdata();
    $dept = substr($app_ref_no, 0, 3);
    $res = $this->intermediator_model->get_userid_by_application_ref($app_ref_no);

    $service_id = $res->service_id;
    $status_url = $this->portals_model->get_departmental_data($service_id);
    $status_url = $status_url->status_url;
    if ($dept === "NOC") {
      //for noc

      $data = array(
        "app_ref_no" => $app_ref_no, //'NOC/05/143/2020',//$app_ref_no,
        "mobile" => $mobile //"9435347177"//$res->mobile
      );
    } else {
      //for other cases

      $data = array(
        "app_ref_no" => $app_ref_no,
        "user_id" => isset($user) ? $user['userId']->{'$id'} : ""
      );
    }
    if (isset($status_url) && !empty($status_url)) {
      $input_array = json_encode($data);
      $aes = new AES($input_array, $this->encryption_key);
      $enc = $aes->encrypt();
      //curl request

      $data = array('data' => $enc);
      $curl = curl_init($status_url);
      // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($curl);


      curl_close($curl);

      if ($response) {
        $response = json_decode($response);
      }
      //decryption
      //  var_dump($response->data);die;
      if ($response->data) {
        $aes->setData($response->data);
        $dec = $aes->decrypt();
        $outputdata = json_decode($dec);
        //var_dump($outputdata);die;
        return $this->output
          ->set_content_type('application/json')
          ->set_status_header(200)
          ->set_output(json_encode(array(
            'status' => "success",
            'data' => $outputdata

          )));
        return;
      } else {
        return $this->output
          ->set_content_type('application/json')
          ->set_status_header(200)
          ->set_output(json_encode(array(
            'status' => "error",
            'data' => ""
          )));
        return;
      }
    } else {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'status' => "error",
          'data' => ""
        )));
      return;
    }
  }

  public function encrypt($data)
  {
    $url = $this->config->item("encrypt_url");
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);


    curl_close($curl);
    return $response;
  }

  public function decrypt($data)
  {
    $url = $this->config->item("decrypt_url");
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }
  public function aws_track(){

    $data = array(
      "app_ref_no" => "201306000001", //'NOC/05/143/2020'
      "mobile" => "9954089500"//"9435347177"
    );
    $input_array = json_encode($data);
    $aes = new AES($input_array, $this->encryption_key);
    $enc = $aes->encrypt();
    //curl request

    $post_data = array('data' => $enc);

    $curl = curl_init();
    
    
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://auwssb.online/track.asmx/Track',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode($post_data),
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$r=json_decode( $response,true);

// pre($r['d']);
$aes = new AES($r['d'], $this->encryption_key);
        $dec = $aes->decrypt();
        $outputdata = json_decode($dec);
// pre(    $outputdata );

$data = array("pageTitle" => "Application Status");

$data['result'] = $outputdata;
    $this->load->view('includes/header');
    $this->load->view('aw_status',$data);
    $this->load->view('includes/footer');
  }
}
