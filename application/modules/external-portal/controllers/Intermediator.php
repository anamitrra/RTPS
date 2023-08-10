<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
use MongoDB\BSON\ObjectId;
class Intermediator extends Rtps
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


    }
    public function vahan(){
      $data=array(
        // "data"=>"59L3qdsvZxncXciCp1rjP4dL-g9314iBSJfng9P8cEA1rnb8fyfsV-ehtPzJfFvCbEbhR-Pp3nsC8bb0heNapR48Sp8_auA06ew7xpNLd_N9iij3RyhKKCqCD6964pJ6NeHStVAy9EnZyN3esCW988AzFnf0FATYVk6xmHqmaZOlUxGMwqkaPWO59p2SWSbmDGvDOsN1Ap-szuPulbYlFl9KUG9CJE5om-dhcMmqjEJnRp0UIFWf74lJY9staxR_"
        "data"=>"59L3qdsvZxncXciCp1rjP4dL-g9314iBSJfng9P8cEBbGqXC06udcAI_NHHmhJC1IExP7HZ4EzQPs3qG__NnCWVT7La5hhjUWdi0R26Kx_5wlUZj3wE-Igd0StXGOpm_ZDdo6o5F4mrk0Ho1IDScNb3_HXf0M3cDK_JuUb8r7s1Zk5kz6Eu315PhGHo07rQxkAUDE8CGxRhqQ80ILW5mFrbFpPEhKJBUVVxJpPT25GG7bupc4Fh4iV28umDst_hK"
      );

      //for 3 $str="59L3qdsvZxncXciCp1rjP4dL-g9314iBSJfng9P8cEA1rnb8fyfsV-ehtPzJfFvCbEbhR-Pp3nsC8bb0heNapR48Sp8_auA06ew7xpNLd_N9iij3RyhKKCqCD6964pJ6NeHStVAy9EnZyN3esCW981XosynBSYsIzCLEGLHr9oAYO8Ly4DuoyL2VmloHajEC-CFXDu1nzkvJ_s1bZjS702lmTIauuPHG8Ths3QHBfEI5AFS_zIRnBVj0bfMPsezO";
      //for 5
    //  $str="dmEFygndWcyGrvojFd8opBHzk2Q7NZzMwUMSzHwEruXCZl1FqErPMWtWE5r_Sb2erkWPjtz8yFScPsd0KIJhtGQ1Td_bagqsGs5fmp1f2iF0avv7EXxSd1fa_gjY4bbzUM1EFmT_Qnh0JRSp6W6ZhrXeUIuJQkFnYCkn3gTzJByeDP2xjPaY17fPE_Ozl3rySM60j-8Q35Y3zjJ-Xz_8t0peq3UaqHbtojOFFv6UXA_yKSj-Eh2evrrXp3_TM9u3";
      //for 9
    //  $str="3Z0n2EpG4LdS1LKCCq8VgKouvtTIJO1KYPrmSJCSdvJu3Qx-uD9UA42M9dHQ1QXvebEadTblrMo7Ue7dCXeKTV7HUfKxhtEY2chYJdftK4o3OaQXp7e0XseDxPl0wPBtt-3VYvhvqrEgh6qNlYHUqRcqKMmTHnhcsYo6RGUAgzbpuQjA1FjXNuQpdFmsOeiIceJxInbN2VXCl-J0mBcFUZ0XSJb6-Ui15lH_RwGA7X3F0ODmcqkqdOuiA1K4q0jo";
      //for 2
      $str="DphW0DwGrH3HDV7QQ_shs41jYEbYe-eO_7B3GAZRlf7AgIYNAu5Djf8N5sBAFM2ABO6xWNptX4cGY4UrXG-ZrTa5BDqhMW16h5-HcxnsEcCLLx5fsG_naRCe7ZnSui_lpXb1iwGnfzI-bT8LDK1WFyTYbMZahH0uI6xVZy53HWvhWRbG-gYy_wfOrngNsO2uYV7M0EfDhpNVfIq59Y96PmJ8egOZ30GwS_oTCQdAgD3nD-OUhnsUXMxx99Cv8d5Q";
      // $this->load->view('includes/frontend/header');
      // $this->load->view('vahan',$data);
      // $this->load->view('includes/frontend/footer');
      $target_url="https://staging.parivahan.gov.in/vahanservice/vahan/ui/statevalidation/homepage.xhtml?data=".$str;
        redirect($target_url);
        // https://staging.parivahan.gov.in/vahanservice/vahan/ui/eapplication/form_eAppCommonHome.xhtml?data=DphW0DwGrH3HDV7QQ_shs41jYEbYe-eO_7B3GAZRlf7AgIYNAu5Djf8N5sBAFM2ABO6xWNptX4cGY4UrXG-ZrTa5BDqhMW16h5-HcxnsEcCLLx5fsG_naRCe7ZnSui_lpXb1iwGnfzI-bT8LDK1WFyTYbMZahH0uI6xVZy53HWvhWRbG-gYy_wfOrngNsO2uYV7M0EfDhpNVfIq59Y96PmJ8egOZ30GwS_oTCQdAgD3nD-OUhnsUXMxx99Cv8d5Q

    }
    public function guidelines($service_id=null,$portal_no=null)
    {
      if(empty($service_id) || empty($portal_no)){
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(array(
                'response_data' => "Invalid service",

            )));
            return;
      }
      $data=array("pageTitle" => "Guidelines");
      $data['service_id']=$service_id;
      $data['portal_no']=$portal_no;

      $guidelines=$this->portals_model->get_guidelines($service_id);

      if($guidelines){
          $data['service_name']=$guidelines->service_name;
        $data['guidelines']=isset($guidelines->guidelines) ? $guidelines->guidelines : array();
        $data['url']=isset($guidelines->url) ? $guidelines->url : '';

        $this->load->view('includes/frontend/header');
        $this->load->view('guidelines',$data);
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
    public function transactions(){
        $user=$this->session->userdata();
        $mobile=$user['mobile'];
        $data['intermediate_ids']=$this->intermediator_model->get_where(array('mobile'=>$mobile));
        $data['pageTitle']="Transactions";
        $this->load->view('includes/frontend/header');
        $this->load->view('transactions',$data);
        $this->load->view('includes/frontend/footer');

    }
    public function list(){
      echo "<pre>";
        $list=$this->intermediator_model->get_all("intermediate_ids");
        var_dump($list);die;
    }
    public function acknowledgement(){

      if(empty($_GET['app_ref_no'])){
        redirect(base_url("external-portal/transactions"));
      }

      $app_ref_no=$_GET['app_ref_no'];
      $application_details=$this->intermediator_model->get_application_details(array("app_ref_no"=>$app_ref_no));
      // var_dump($application_details);die;
      if($application_details->service_id)
        $departmental_data=$this->portals_model->get_departmental_data($application_details->service_id);
      else
        redirect('external-portal/transactions');
      $data=array();
      $data['response']=$application_details;
      $data['timeline_days']=$departmental_data->timeline_days;
      $data['department_name']=$departmental_data->department_name;
      $data['service_name']=$departmental_data->service_name;

      $this->load->view('includes/frontend/header');
      $this->load->view('noc_ack1',$data);
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
    public function retry($rtps_trans_id,$service_id,$portal_no){
      if($rtps_trans_id && $service_id){
        $user=$this->session->userdata();
        $guidelines=$this->portals_model->get_guidelines($service_id);

        $target_url=isset($guidelines->url) ? $guidelines->url : '';
        $input_array=array(
            "rtps_trans_id"=>$rtps_trans_id,
            "user_id"=>$user['userId']->{'$id'},
            "service_id"=>intval($service_id),
            "portal_no"=>$portal_no,
            "process"=>"I",
            "response_url"=>base_url("external-portal/get/response")
        );
        $input_array=json_encode($input_array);
        $aes = new AES($input_array, $this->encryption_key);
        $enc = $aes->encrypt();
        $data=array(
          "data"=>$enc,
          "action"=>$target_url
        );
      //  $url=$target_url."?data=".urlencode($enc);
        // redirect($url);
        $this->load->view("retry",$data);
      }else {
        redirect(base_url("external-portal/transactions"));
      }


    }
    public function procced(){
      $service_id=$this->input->post('service_id');
      $service_name=$this->input->post('service_name');
      $portal_no=$this->input->post('portal_no');
      $target_url=$this->input->post('url');
      $user=$this->session->userdata();
      $date=date('ydm');
      $rtps_trans_id=$this->generate_id();
      A1:
      if($this->intermediator_model->is_exist_transaction_no($rtps_trans_id)){
        $rtps_trans_id=$this->generate_id();
        goto A1;
      }


      $input_array=array(
          "rtps_trans_id"=>$rtps_trans_id,
          "user_id"=>$user['userId']->{'$id'},
          "service_id"=>intval($service_id),
          "portal_no"=>$portal_no,
          "process"=>"N",
          "response_url"=>base_url("external-portal/get/response")
      );
      $input_array=json_encode($input_array);
      $aes = new AES($input_array, $this->encryption_key);
      $enc = $aes->encrypt();
      // $url=base_url()."request/create?data=".urlencode($enc);//var_dump($url);die;
      $url=$target_url."?data=".urlencode($enc);
      //AS200226V0313962/AS
      // 9x5CCz4G5GnxwULPdHXcPmkH9Xdg9KjENTl9OuKf3tk
      $user_data=array(
        'user_id'=>$user['userId']->{'$id'},
        'mobile'=>$user['mobile'],
        'rtps_trans_id'=>$rtps_trans_id,
        "service_name"=>$service_name,
        "portal_no"=>$portal_no,
        "service_id"=>intval($service_id),
        'target_url'=>$url,
        'return_url'=>base_url("external-portal/get/response"),
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

}
