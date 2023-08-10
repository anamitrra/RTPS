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
        $this->load->library('form_validation');

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

        $this->load->view('includes/header');
        $this->load->view('admin/o_guidelines',$data);
        $this->load->view('includes/footer');
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
        redirect(base_url("expr/admin/my-transactions"));
      }

      $app_ref_no=$_GET['app_ref_no'];
      $application_details=$this->intermediator_model->get_application_details(array("app_ref_no"=>$app_ref_no));
      // var_dump($application_details);die;
      if($application_details->service_id)
        $departmental_data=$this->portals_model->get_departmental_data($application_details->service_id);
      else
        redirect('expr/admin/my-transactions');
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
            "response_url"=>base_url("expr/admin/get/response")
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
        redirect(base_url("expr/admin/my-transactions"));
      }


    }
    public function procced(){
      $service_id=$this->input->post('service_id');
      $service_name=$this->input->post('service_name');
      $portal_no=$this->input->post('portal_no');
      $target_url=$this->input->post('url');

      $mobile=$this->input->post('user_mobile');
      $applicant_name=$this->input->post('applicant_name');
      $address1=$this->input->post('address1');
      $address2=$this->input->post('address2');
      $address3=$this->input->post('address3');
      $pin_code=$this->input->post('pin_code');

      $this->form_validation->set_rules('user_mobile', 'user_mobile', 'required|trim');
      $this->form_validation->set_rules('applicant_name', 'applicant_name', 'required|trim');
      $this->form_validation->set_rules('address1', 'address1', 'required|trim');
      $this->form_validation->set_rules('address2', 'address2', 'required|trim');
      $this->form_validation->set_rules('pin_code', 'pin_code', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
          $status["status"] = false;
          $status["error_msg"] = "Please enter input for all require fields";
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


      $input_array=array(
          "rtps_trans_id"=>$rtps_trans_id,
          "user_id"=>$user['userId']->{'$id'},
          "service_id"=>intval($service_id),
          "portal_no"=>$portal_no,
          "process"=>"N",
          "response_url"=>base_url("expr/admin/get/response")
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

        'mobile'=>$mobile,
        'applicant_name'=>$applicant_name,
        'address1'=>$address1,
        'address2'=>$address2,
        'address3'=>$address3,
        'pin_code'=>$pin_code,

        'rtps_trans_id'=>$rtps_trans_id,
        "service_name"=>$service_name,
        "portal_no"=>$portal_no,
        "service_id"=>intval($service_id),
        'target_url'=>$url,
        'return_url'=>base_url("expr/admin/get/response"),
        "status"=>"",
        "applied_by"=>new ObjectId($this->session->userdata('userId')->{'$id'}),
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
