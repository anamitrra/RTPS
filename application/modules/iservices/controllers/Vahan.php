<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Vahan extends Rtps
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('intermediator_model');
        $this->load->model('portals_model');
        $this->config->load('rtps_services');
        $this->load->library('AES');
        $this->load->library('AESrathi');
       // $this->encryption_key=$this->config->item("encryption_key");
        $this->load->library('form_validation');
        $this->encryption_key=$this->config->item("agencyKey");

    }

    public function index($pur_cd)
    {
      check_application_count_for_citizen();
      $service_id=$pur_cd;
      $data=array("pageTitle" => "Guidelines");
      $data['service_id']=$service_id;
      $guidelines=$this->portals_model->get_guidelines($service_id);
      $external_service_id=property_exists($guidelines,"external_service_id") ? $guidelines->external_service_id : $guidelines->service_id;

      if($guidelines){
        if($guidelines->portal_no != "2"){
          return $this->output
              ->set_content_type('application/json')
              ->set_status_header(200)
              ->set_output(json_encode(array(
                  'response_data' => "Invalid service",

              )));
              return;
        }

        $user = $this->session->userdata();
        if (isset($user['role']) && !empty($user['role'])) {
          $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "https") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
          if (strpos($url, "admin") == false) {
            $admin_url = str_replace("iservices", "iservices/admin", $url);
            redirect($admin_url);
          }
        }
        $data['pur_cd']=$external_service_id;//$pur_cd;
        $data['portal_no']="2";
        $data['service_name']=$guidelines->service_name;
        $data['guidelines']=isset($guidelines->guidelines) ? $guidelines->guidelines : array();
        $data['url']=isset($guidelines->url) ? $guidelines->url : '';
        $data['action']=base_url('iservices/v-procced');
        $this->load->view('includes/frontend/header');
        $this->load->view('v_guidelines',$data);
        $this->load->view('includes/frontend/footer');
      }else {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'response_data' => "Invalid service",

            )));
            return;
      }

    }
    public function acknowledgement($vahan_app_no){
      if(empty($vahan_app_no)){
        redirect('iservices/transactions');
        return;
      }
      $application_details=$this->intermediator_model->get_application_details(array("vahan_app_no"=>$vahan_app_no,'mobile'=>$this->session->userdata('mobile')));
      if($application_details->service_id)
        $departmental_data=$this->portals_model->get_departmental_data($application_details->service_id);
      else
        redirect('iservices/transactions');

      if(  isset($application_details->convenience_fee_required) && $application_details->pfc_payment_status !=="Y"){
        redirect(base_url('iservices/convenience_fee/payment/').$application_details->rtps_trans_id);
      }
      $data=array();
      $data['response']=(array)$application_details;
      $data['timeline_days']=$departmental_data->timeline_days;
      $data['department_name']=$departmental_data->department_name;
      $data['service_name']=$departmental_data->service_name;

      $this->load->view('includes/frontend/header');
      $this->load->view('vahan_acknowledgment',$data);
      $this->load->view('includes/frontend/footer');
    }


    function generateRandomString($length = 7) {
            $number = '';
            for ($i = 0; $i < $length; $i++){
                $number .= rand(0,9);
            }
            return (int)$number;
    }

    public function generate_id(){
      $date=date('ydm');
      $str="AS".$date."A".$this->generateRandomString(7);
      return $str;
    }
    public function retry($rtps_trans_id){
      if($rtps_trans_id){
        $target_url=$this->config->item("vahan_retry_url");
        if(empty($target_url)){
          redirect(base_url("iservices/transactions"));
          return;
        }
        $transaction_details=$this->intermediator_model->get_transaction_detail($rtps_trans_id);
              $pur_cd=$transaction_details->purpose_code;
              $portal_cd="AS";
              $regn_no=$transaction_details->regn_no;
              $chassi_no=$transaction_details->chassi_no;
              $mobile=$transaction_details->mobile;
              $userid=$transaction_details->user_id;
              $vahan_app_no=isset($transaction_details->vahan_app_no) ? $transaction_details->vahan_app_no : "";
              $return_url=base_url("iservices/get/vahan-response");
              $urlData ="pur_cd=".$pur_cd."&portal_cd=".$portal_cd."&regn_no=".$regn_no;
              $urlData .= "&chassi_no=".$chassi_no."&mobileNo=".$mobile;
              $urlData .=  "&return_url=".$return_url;
              $urlData .= "&user_id=".$userid;
              $urlData .= "&edist_trans_no=".$rtps_trans_id;
              if(!empty($vahan_app_no)){
              $urlData .= "&transaction_no=".$vahan_app_no;
              }
// var_dump($urlData);die;
              $aes = new AESrathi($urlData, $this->encryption_key,128);
              $enc = $aes->encrypt();

            //  $enc = $this->encrypt(array('result'=>$urlData));
              if(empty($enc)){
                redirect(base_url("iservices/transactions"));
              }
              $url=$target_url."?data=".$enc;//var_dump($url);die;

              redirect($url);
      }else {
        redirect(base_url("iservices/transactions"));
      }


    }
    public function procced(){
      $service_id=$this->input->post('service_id');
      $service_name=$this->input->post('service_name');
      $portal_no=$this->input->post('portal_no');
      $regn_no=$this->input->post('regn_no');
      $chassi_no=$this->input->post('chassi_no');
      $pur_cd=$this->input->post('pur_cd');
      $target_url=$this->input->post('url');

      $this->form_validation->set_rules('service_id', 'service_id', 'required|trim');
      $this->form_validation->set_rules('service_name', 'service_name', 'required|trim');
      $this->form_validation->set_rules('portal_no', 'portal_no', 'required|trim');
      $this->form_validation->set_rules('regn_no', 'regn_no', 'required|trim');
      $this->form_validation->set_rules('chassi_no', 'chassi_no', 'required|trim');
      $this->form_validation->set_rules('pur_cd', 'pur_cd', 'required|trim');
      $this->form_validation->set_rules('url', 'url', 'required|trim');
      $this->form_validation->set_rules('pur_cd', 'pur_cd', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
          $status["status"] = false;
          $status["message"] = "Please enter input for all require fields";
          return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
        }

      $user=$this->session->userdata();
      $date=date('ydm');
      $rtps_trans_id=$this->generate_id();
      A1:
      if($this->intermediator_model->is_exist_transaction_no($rtps_trans_id)){
        $rtps_trans_id=$this->generate_id();
        goto A1;
      }



      $portal_cd="AS";
      // $regn_no="AS01DB0032";
      // $chassi_no="11856";
      $mobile=$user['mobile'];//"8095473661";
      $return_url=base_url("iservices/get/vahan-response");
    //  $redirect_url=base_url("request/create");
      // $rtps_trans_id="AS202110A1568012";
      $urlData ="pur_cd=".$pur_cd."&portal_cd=".$portal_cd."&regn_no=".$regn_no;
      $urlData .= "&chassi_no=".$chassi_no."&mobileNo=".$mobile;
      $urlData .=  "&return_url=".$return_url;
      $urlData .= "&user_id=".$user['userId']->{'$id'};
      $urlData .= "&edist_trans_no=".$rtps_trans_id;
      //pre($urlData);
      $aes = new AESrathi($urlData, $this->encryption_key,128);
      $enc = $aes->encrypt();
      // $enc = $this->encrypt(array('result'=>$urlData));
      if(empty($enc)){
        $status["status"] = false;
        $status["message"] = "Something went wrong. Please try again";
        $status["ErrCode"] = "100";
        $status["ErrDetails"] = "Encryption server may be down";
        return $this->output
          ->set_content_type('application/json')
          ->set_status_header(200)
          ->set_output(json_encode($status));
      }
      $url=$target_url."?data=".$enc;
      //AS200226V0313962/AS
      // 9x5CCz4G5GnxwULPdHXcPmkH9Xdg9KjENTl9OuKf3tk
      $user_data=array(
        'user_id'=>$user['userId']->{'$id'},
        'mobile'=>$user['mobile'],
        'rtps_trans_id'=>$rtps_trans_id,
        "service_id"=>$service_id,
        "service_name"=>$service_name,
        "portal_no"=>$portal_no,
        'target_url'=>$url,
        'chassi_no'=>$chassi_no,
        'purpose_code'=>$pur_cd,
        'regn_no'=>$regn_no,
        'convenience_fee_required'=>true,
        'return_url'=>base_url("iservices/get/vahan-response"),
        "status"=>"",
        "createdDtm"=> new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
      );
      $res=$this->intermediator_model->insert($user_data);
      $status=array();
      if($res){
          $status["status"] = true;
          $status["url"] = $url;
          $status["encrypted_data"] = $enc;
          $status["message"] = "Need to redirect to third party urls";
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
      }else {
          $status["status"] = false;
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
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

 // $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      curl_close($curl);
     // pre($response);
      return $response;
    }


}
