<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;

class Forgot_password extends Frontend
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('office/login_model');
  }

  public function index()
  {
    $this->load->helper('captcha');
    $cap = generate_n_store_captcha();
    $data = [
      'cap' => $cap
    ];
    $this->load->view('includes/frontend/header');
    $this->load->view('office/forgot_password', $data);
    $this->load->view('includes/frontend/footer');
  }

  public function check_mobile_number(){
    $mobile = $this->input->post("contactNumber", TRUE);
    $checkUserExist = $this->login_model->checkMobileExist($mobile);
    if ($checkUserExist) {
      $status["status"] = 1;
    }
    else{
      $status["status"] = 0;
    }
    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($status));
  }

    /**
   * send_otp
   *
   * @return void
   */
  public function send_otp()
  {
    $mobile = strval($this->input->post('contactNumber', true));
    $status = array();
    $status['m_no'] = $mobile;
    if (!empty($mobile)) {
      $status["msg"] = "Entered";
      $this->sms->send_otp($mobile, $mobile);
      // if(ENV !== "DEV"){
      //   $this->sms->send_otp($mobile, $mobile, $msg);
      // }
      $status["status"] = true;
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    } else {
      //            return true;
      $status["status"] = false;
      $status["msg"] = "No OTP";
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    }
  }

  public function reset_password(){
    $mobile = $this->input->post("contactNumber", TRUE);
    $otp = $this->input->post("otp", TRUE);
    // $value = $this->sms->verify_otp($mobile, $mobile, $otp);
    // if (!$value['status']) {
    //   $status["status"] = false;
    //   $status["otp_mismatch"] = true;
    //   return $this->output
    //     ->set_content_type('application/json')
    //     ->set_status_header(200)
    //     ->set_output(json_encode($status));
    // }else{

      $this->form_validation->set_rules(
        'newPassword', 'New Password',
        array(
                'required',
                array(
                        'newPassword_callable',
                        function($newPassword)
                        {
                            if (preg_match('/[0-9]/', $newPassword) && preg_match('/[a-z]/', $newPassword) && preg_match('/[A-Z]/', $newPassword) && preg_match('/[!@#$%^&*()\-_=+{};:,<.>ยง~]/', $newPassword) && strlen($newPassword) >= 8 ) {
                                return true;
                               }
                            else{
                          
                                $this->form_validation->set_message('newPassword_callable', 'Password must contain: Atleast one number, one uppercase letter, one lowercase letter, one special character and atleast 8 characters long');
                                                return FALSE;
                            }
                        }
                )
        )
);

    
    $newPassword = $this->input->post("newPassword", TRUE);
    $confPassword = $this->input->post("confPassword", TRUE);
    if($newPassword != $confPassword){
      $status["status"] = false;

      $status["pass_mismatch"] = true;
      return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($status));
    }
    else{

      $value = $this->sms->verify_otp($mobile, $mobile, $otp);
      if (!$value['status']) {
        $status["status"] = false;
        $status["otp_mismatch"] = true;
        return $this->output
          ->set_content_type('application/json')
          ->set_status_header(200)
          ->set_output(json_encode($status));
      }else{
    $checkUserExist = $this->login_model->checkMobileExist($mobile);
    if ($checkUserExist) {
      if ($checkUserExist->is_active == 1) {
          $haspass = getHashedPassword($newPassword);
          $option = array('upsert' => true);
          $this->mongo_db->where(array('_id' => new ObjectId($checkUserExist->_id->{'$id'})))->set(['password'=>$haspass])->update('office_users', $option);
          $status["status"] = true;
          $status["url"] = base_url('spservices/office-login');
        }
      else{
      $status["status"] = false;
      $status["is_deactive"] = 'not active';
      }
    }
    else{
      $status["status"] = false;
      $status["exist"] = false;


    }
    return $this->output
    ->set_content_type('application/json')
    ->set_status_header(200)
    ->set_output(json_encode($status));
  }
  }
  }
}