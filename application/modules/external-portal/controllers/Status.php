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
        $this->encryption_key=$this->config->item("encryption_key");

    }

    public function index()
    {

      $data=array("pageTitle" => "check Status");


        $this->load->view('includes/frontend/header');
        $this->load->view('check_status',$data);
        $this->load->view('includes/frontend/footer');


    }
    public function check_status(){

      $outputdata=array();
      if(!isset($_GET['app_ref_no']) || empty($_GET['app_ref_no'])){
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => "something went wrong",

            )));
            return;
      }
      $app_ref_no=$_GET['app_ref_no'];
      $res=$this->intermediator_model->get_userid_by_application_ref($app_ref_no);
      $user_mobile=$res->mobile;
      $service_id=$res->service_id;
      $status_url=$this->portals_model->get_departmental_data($service_id);
      $status_url=$status_url->status_url;
      if(empty($status_url)){
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => "Status url not define",

            )));
            return;
      }

      $data=array(
        "app_ref_no"=>$app_ref_no,//'NOC/05/143/2020',//$app_ref_no,
        "mobile"=>$user_mobile,//"9435347177"//$user_mobile
      );
      $input_array=json_encode($data);
      $aes = new AES($input_array, $this->encryption_key);
      $enc = $aes->encrypt();
      //curl request

      $post_data=array('data'=>$enc);
      $curl = curl_init($status_url);
      // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($curl);


      curl_close($curl);

      if($response){
        $response=json_decode($response);
      }
      // var_dump($response);die;
      //decryption
      if(isset($response->data) && !empty($response->data)){
        $aes->setData($response->data);
        $dec=$aes->decrypt();
        $outputdata=json_decode($dec);
      }

        $data=array("pageTitle" => "Application Status");

        $data['result']=$outputdata;
        $this->load->view('includes/frontend/header');
        $this->load->view('status',$data);
        $this->load->view('includes/frontend/footer');

    }

    public function check_vahan_status(){
      $outputdata=array();
      if(!isset($_GET['vahan_app_no']) || empty($_GET['vahan_app_no'])){
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => "something went wrong",

            )));
            return;
      }
      $vahan_app_no=$_GET['vahan_app_no'];
      $status_url=$this->config->item("vahan_status_url");

      if(empty($status_url)){
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'status' => "Status url not define",

            )));
            return;
      }
      // $str=$vahan_app_no.'/AS';
      $str='AS200226V0313962/AS';
      $inp=$this->encrypt(array('result'=>$str));
// var_dump($status_url.$inp);die;

      $curl = curl_init($status_url);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $inp);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
      // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
      // curl_setopt($curl, CURLOPT_CAINFO, getcwd() . "/parivahan-gov-in.pem");

      $response_string = curl_exec($curl);

      // $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      curl_close($curl);

        // $response_string="4r7wSg1FjwMV33ktC4hAtHMOSjKvzxo7ONZtiVhneug7cmnoUC18HoYoaSMPTZBa1jMrN87yERKLTCtKArSKYOLl1s3hpuPMfysLrh4USV6USE6MvqzMwUI/kSlHr9W7mHMvyJa8Qpo2ykNJZnMYJ18e+ph0n0VaAdKx6Fpn1Yn0itHCI5tDIasg9nvic3M+O+pojqoLEwOUWk0Iw+rlQOgGkkK5kQcYzn7w1fXTQ89Pu4T86zCoIRdGPiE1i9QaWxFCXBsNKb13lvSzL/iQ3ejqtc6TipPQytar6ZgqxMGE9qKji2qQwCHlnsmC6SIHkddpeMGpUm+kdCK+jdYURocR/zX1cfDjg1wjWXKvNzVyjW61Wu4QeoaZddE1fvpovC+StcisgmiYhgtFxchBzoCoPkgZorrRw3F2QC4X+zphtK9zNA9exQKBSgd71G40";
        $response=$this->decrypt(array("result"=>$response_string));
        if($response){
            $response=(array)json_decode($response);
            $index="";
            foreach ($response as $key => $value) {
            $index=$key;
            break;
            }
        }

        $data=array("pageTitle" => "Application Status");

        $data['result']=empty($response[$index])  ? array() : $response[$index];
        $this->load->view('includes/frontend/header');
        $this->load->view('vahan_status',$data);
        $this->load->view('includes/frontend/footer');

    }

    public function getRequestInfo(){
      $app_ref_no=$this->input->post('app_ref_no');
      $mobile=$this->input->post('mobile');
      $user=$this->session->userdata();
      $dept=substr($app_ref_no,0,3);
      $res=$this->intermediator_model->get_userid_by_application_ref($app_ref_no);

      $service_id=$res->service_id;
      $status_url=$this->portals_model->get_departmental_data($service_id);
      $status_url=$status_url->status_url;
     if($dept === "NOC"){
       //for noc

       $data=array(
         "app_ref_no"=>$app_ref_no,//'NOC/05/143/2020',//$app_ref_no,
         "mobile"=>$mobile//"9435347177"//$res->mobile
       );
     }else {
       //for other cases

       $data=array(
         "app_ref_no"=>$app_ref_no,
         "user_id"=>isset($user) ? $user['userId']->{'$id'}: ""
       );
     }
     if(isset($status_url) && !empty($status_url)){
       $input_array=json_encode($data);
       $aes = new AES($input_array, $this->encryption_key);
       $enc = $aes->encrypt();
       //curl request

       $data=array('data'=>$enc);
       $curl = curl_init($status_url);
       // curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
       curl_setopt($curl, CURLOPT_POST, true);
       curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       $response = curl_exec($curl);


       curl_close($curl);

       if($response){
         $response=json_decode($response);
       }
       //decryption
      //  var_dump($response->data);die;
       if($response->data){
         $aes->setData($response->data);
         $dec=$aes->decrypt();
         $outputdata=json_decode($dec);
        //var_dump($outputdata);die;
         return $this->output
             ->set_content_type('application/json')
             ->set_status_header(200)
             ->set_output(json_encode(array(
                 'status' => "success",
                 'data'=>$outputdata

             )));
             return;

       }else {
         return $this->output
             ->set_content_type('application/json')
             ->set_status_header(200)
             ->set_output(json_encode(array(
               'status' => "error",
               'data'=>""
             )));
             return;
       }


     }else {
       return $this->output
           ->set_content_type('application/json')
           ->set_status_header(200)
           ->set_output(json_encode(array(
             'status' => "error",
             'data'=>""
           )));
           return;
     }

    }

    public function encrypt($data){
      $url=$this->config->item("encrypt_url");
      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($curl);


      curl_close($curl);
      return $response;
    }

    public function decrypt($data){
      $url=$this->config->item("decrypt_url");
      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($curl);
      curl_close($curl);
      return $response;
    }


}
