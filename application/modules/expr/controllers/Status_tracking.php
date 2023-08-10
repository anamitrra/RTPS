<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Status_tracking extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->config->load('rtps_services');


    }

    public function vahan(){
      $data = file_get_contents("php://input");
      $data = json_decode($data, true);


      $vahan_app_no=$data['applNo'];
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
      $str=$vahan_app_no.'/AS';
      // $str='AS200226V0313962/AS';
      $inp=$this->encrypt(array('result'=>$str));
// var_dump($status_url.$inp);die;
  $url=$status_url."?".$inp;
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

        $index="";
        $response=$this->decrypt(array("result"=>$response));

        if($response){
          return $this->output
              ->set_content_type('application/json')
              ->set_status_header(200)
              ->set_output($response);
              return;
        }else {
          return $this->output
              ->set_content_type('application/json')
              ->set_status_header(200)
              ->set_output("");
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
