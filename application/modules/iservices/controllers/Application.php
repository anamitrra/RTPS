<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class Application extends Rtps
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('intermediator_model');
    $this->load->model('portals_model');
    $this->config->load('rtps_services');
    $this->load->library('AES');
    $this->encryption_key = $this->config->item("encryption_key");
    $this->load->helper("minoritycertificate");
    $this->load->helper("appstatus");
  }

  public function index()
  {
  }
 
  public function ngdrs($service_id = null, $portal_no = null)
  {
    if (empty($service_id) || empty($portal_no)) {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'response_data' => "Invalid service",

        )));
      return;
    }
    $user = $this->session->userdata();
    $data = array("pageTitle" => "Guidelines");
    if (!isset($user['role']) && empty($user['role'])) {
        $data['mobile'] = $this->session->userdata("mobile");
    }
  
    $data['service_id'] = $service_id;
    $data['portal_no'] = $portal_no;
    

    $guidelines = $this->portals_model->get_guidelines($service_id);
   
    if ($guidelines) {
      $data['service_name'] = $guidelines->service_name;
      $data['guidelines'] = isset($guidelines->guidelines) ? $guidelines->guidelines : array();
      $data['url'] = isset($guidelines->url) ? $guidelines->url : '';

      $this->load->view('includes/frontend/header');
      $this->load->view('forms/ngdrs', $data);
      $this->load->view('includes/frontend/footer');
    } else {
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode(array(
          'response_data' => "Invalid service",

        )));
      return;
    }
  }
 

  function generateRandomString($length = 7)
  {
    $number = '';
    for ($i = 0; $i < $length; $i++) {
      $number .= rand(0, 9);
    }
    return (int)$number;
  }

  public function generate_id()
  {
    $date = date('ydm');
    $str = "AS" . $date . "A" . $this->generateRandomString(7);
    return $str;
  }
  public function retry($rtps_trans_id, $service_id, $portal_no)
  {
    if ($rtps_trans_id && $service_id) {
      $user = $this->session->userdata();
      $guidelines = $this->portals_model->get_guidelines($service_id);
      $external_service_id = property_exists($guidelines, "external_service_id") ? $guidelines->external_service_id : $guidelines->service_id;

      $target_url = isset($guidelines->url) ? $guidelines->url : '';
      $input_array = array(
        "rtps_trans_id" => $rtps_trans_id,
        "user_id" => $this->session->userdata("mobile"), //$user['userId']->{'$id'},
        "mobile" => $this->session->userdata("mobile"),
        "service_id" => intval($external_service_id),
        "portal_no" => $portal_no,
        "process" => "I",
        "response_url" => base_url("iservices/get/response")
      );
      $input_array = json_encode($input_array);
      $aes = new AES($input_array, $this->encryption_key);
      $enc = $aes->encrypt();
      $data = array(
        "data" => $enc,
        "action" => $target_url
      );
      //  $url=$target_url."?data=".urlencode($enc);
      // redirect($url);
      $this->load->view("retry", $data);
    } else {
      redirect(base_url("iservices/transactions"));
    }
  }
  public function procced()
  {
    $service_id = $this->input->post('service_id');
    $service_name = $this->input->post('service_name');
    $portal_no = $this->input->post('portal_no');
    $target_url = $this->input->post('url');

    $this->form_validation->set_rules('contact_fname', 'First Name', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('contact_mname', 'Middle Name', 'trim|xss_clean|strip_tags');
    $this->form_validation->set_rules('contact_lname', 'Last Name', 'trim|required|xss_clean|strip_tags');
    $this->form_validation->set_rules('building', 'Building', 'trim|xss_clean|required|strip_tags');
    $this->form_validation->set_rules('street', 'Street', 'trim|xss_clean|required|strip_tags');
    $this->form_validation->set_rules('street', 'Street', 'trim|xss_clean|required|strip_tags');
    $this->form_validation->set_rules('city', 'city', 'trim|xss_clean|required|strip_tags');
    $this->form_validation->set_rules('pincode', 'Pincode', 'trim|xss_clean|required|strip_tags');
    $this->form_validation->set_error_delimiters("<font style='font-size:12px; color: #d43f3a; font-weight:bold; font-style:italic'>", "</font>");
      
    if ($this->form_validation->run() == FALSE) {
   
        $this->session->set_flashdata('error','Error in inputs : '.validation_errors());
        $this->ngdrs($service_id,$portal_no);
        return;
    } 
   
    $user = $this->session->userdata();

    $service_details = $this->portals_model->get_guidelines($service_id);
    if (empty($service_details)) {
      return false;
    }

    $external_service_id = property_exists($service_details, "external_service_id") ? $service_details->external_service_id : $service_details->service_id;

    $date = date('ydm');
    $rtps_trans_id = $this->generate_id();
    A1:
    if ($this->intermediator_model->is_exist_transaction_no($rtps_trans_id)) {
      $rtps_trans_id = $this->generate_id();
      goto A1;
    }


    $input_array = array(
      "rtps_trans_id" => $rtps_trans_id,
      "user_id" => $this->session->userdata("mobile"), //$user['userId']->{'$id'},
      "mobile" => $this->session->userdata("mobile"),
      "service_id" => intval($external_service_id),
      "portal_no" => $portal_no,
      "process" => "N",
      "response_url" => base_url("iservices/get/response")
    );
    if( $portal_no === 12 ||  $portal_no === "12"){
      $input_array['contact_fname']=$this->input->post('contact_fname');
      $input_array['contact_mname']=$this->input->post('contact_mname');
      $input_array['contact_lname']=$this->input->post('contact_lname');
      $input_array['building']=$this->input->post('building');
      $input_array['street']=$this->input->post('street');
      $input_array['city']=$this->input->post('city');
      $input_array['pincode']=$this->input->post('pincode');
      $input_array['state_id']=$this->input->post('state_id');
      $input_array['district_id']=$this->input->post('district_id');
      $input_array['taluka_id']=$this->input->post('taluka_id');
      $input_array['email_id']=$this->input->post('email_id');
      $input_array['mobile_no']=$this->session->userdata("mobile");
      $input_array['pan_no']=$this->input->post('pan_no');
      $input_array['uid']=$this->input->post('uid');

    }
    $input_array = json_encode($input_array);
    $aes = new AES($input_array, $this->encryption_key);
    $enc = $aes->encrypt();
    // $url=base_url()."request/create?data=".urlencode($enc);//var_dump($url);die;
    $url = $target_url . "?data=" . urlencode($enc);
    //AS200226V0313962/AS
    // 9x5CCz4G5GnxwULPdHXcPmkH9Xdg9KjENTl9OuKf3tk
    $user_data = array(
      'user_id' => $user['userId']->{'$id'},
      'mobile' => $user['mobile'],
      'rtps_trans_id' => $rtps_trans_id,
      "service_name" => $service_name,
      "portal_no" => $portal_no,
      "service_id" => intval($service_id),
      "external_service_id" => $external_service_id,
      'target_url' => $url,
      'return_url' => base_url("iservices/get/response"),
      "status" => "",
      "payment_rtps_end" => true,
      "createdDtm" => new \MongoDB\BSON\UTCDateTime((strtotime(date("Y-m-d h:i:s")) * 1000)),
    );
    if (empty($user_data['user_id']) || empty($user_data['mobile'])) {

      if( $portal_no === 12 ||  $portal_no === "12"){
        $user_data['contact_fname']=$this->input->post('contact_fname');
        $user_data['contact_mname']=$this->input->post('contact_mname');
        $user_data['contact_lname']=$this->input->post('contact_lname');
        $user_data['building']=$this->input->post('building');
        $user_data['street']=$this->input->post('street');
        $user_data['city']=$this->input->post('city');
        $user_data['pincode']=$this->input->post('pincode');
        $user_data['state_id']=$this->input->post('state_id');
        $user_data['district_id']=$this->input->post('district_id');
        $user_data['taluka_id']=$this->input->post('taluka_id');
        $user_data['email_id']=$this->input->post('email_id');
        $user_data['mobile_no']=$this->session->userdata("mobile");
        $user_data['pan_no']=$this->input->post('pan_no');
        $user_data['uid']=$this->input->post('uid');
  
      }

      $status["status"] = false;
      $status["message"] = "Invalid User Credentials";
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    }
    $res = $this->intermediator_model->insert($user_data);
    $status = array();
    if ($res) {
        $formdata['url']=$target_url;
        $formdata['data']=$enc;
        $this->load->view('includes/frontend/header');
        $this->load->view("forms/redirect",$formdata);
        $this->load->view('includes/frontend/footer');
       
    } else {
      $status["status"] = false;
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    }
  }



  private function is_admin()
  {
    $user = $this->session->userdata();
    if (isset($user['role']) && !empty($user['role'])) {
      return true;
    } else {
      return false;
    }
  }
  private function my_transactions()
  {
    $user = $this->session->userdata();
    if (isset($user['role']) && !empty($user['role'])) {
      redirect(base_url('iservices/admin/my-transactions'));
    } else {
      redirect(base_url('iservices/transactions'));
    }
  }


}