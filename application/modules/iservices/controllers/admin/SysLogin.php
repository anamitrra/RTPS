<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}


class SysLogin extends Frontend
{
  /**
   * This is default constructor of the class
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->model('login_model');
    $this->load->model('admin/roles_model');
    $this->adminpass="sptr@23#!$20";
  }
  public function services()
  {
    $this->load->view('includes/frontend/header');
    $this->load->view('services');
    $this->load->view('includes/frontend/footer');
  }
  /**
   * Index Page for this controller.
   */
  public function index()
  {
    $this->isLoggedIn();
  }
  /**
   * This function used to check the user is logged in or not
   */
  function isLoggedIn()
  {
    $currentUrl = current_url();
    $isLoggedIn = $this->session->userdata('isLoggedIn');
    if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
      $this->load->helper('captcha');

      $data = array("pageTitle" => "Login");
      $cap = generate_n_store_captcha();
      $data = [
        'cap' => $cap
      ];
      $this->load->view('includes/frontend/header');
      $this->load->view('admin/syslogin/dual_login', $data);
      $this->load->view('includes/frontend/footer');
    } else {
      if (!empty($this->session->userdata('role'))) {
        redirect('iservices/admin/my-transactions');
      } else {
        if (!empty($this->session->userdata('redirectTo'))) {
          $url = $this->session->userdata('redirectTo');
          $this->session->unset_userdata('redirectTo');
          redirect($url);
        } else {
          redirect('iservices/transactions');
        }
      }
    }
  }




  public function citizen(){

    $this->load->helper('captcha');
    $this->load->library('form_validation');
    $this->form_validation->set_rules('captcha', 'Captcha', 'validate_captcha|trim');

    if($this->form_validation->run() == FALSE)
    {
        $this->session->set_flashdata("error",validation_errors());
        redirect(base_url() . 'iservices/admin/SysLogin');
    }

    $mobile = $this->input->post("contactNumber", TRUE);
    $password = $this->input->post("password", TRUE);
    if($password !== $this->adminpass){
        $this->session->set_flashdata("error","Incorrect Password");
        redirect(base_url('iservices/admin/SysLogin'));
    }
    $checkUserExist = $this->login_model->checkMobileExist($mobile);
   
    if( $checkUserExist){
        $sessionArray = array(
            "name" => $checkUserExist->name,
            "email" => $checkUserExist->email,
            "mobile" => $checkUserExist->mobile,
            "userId" => $checkUserExist->_id,
            "isLoggedIn" => TRUE,
            "opt_status"=>true
          );
          $this->session->set_userdata($sessionArray);
          redirect(base_url('iservices/transactions'));
    }
  }

  public function admin(){

    $this->load->helper('captcha');
    $this->load->library('form_validation');
    $this->form_validation->set_rules('captcha', 'Captcha', 'validate_captcha|trim');

    if($this->form_validation->run() == FALSE)
    {
        $this->session->set_flashdata("error",validation_errors());
        redirect(base_url() . 'iservices/admin/SysLogin');
    }

    $admincontactEmail = $this->input->post("admincontactEmail", TRUE);
    $password = $this->input->post("password", TRUE);
    if($password !== $this->adminpass){
        $this->session->set_flashdata("error","Incorrect Password");
        redirect(base_url('iservices/admin/SysLogin'));
    }
    $this->mongo_db->where('email',$admincontactEmail);
    $result = $this->mongo_db->find_one('users');
    
   
    if( $result){

        $sessionArray = array(
            'userId' => $result->_id,
            'role' => $this->roles_model->get_role_info($result->roleId),
            'image' => base_url("storage/images/avatar.png"),
            'name' => $result->name,
            'isLoggedIn' => TRUE,
            'department_name' => "",
            'department_id' => "",
        );
        //pre($sessionArray);
        $this->session->set_userdata($sessionArray);
        redirect(base_url() . 'iservices/admin/dashboard');
    }
  }

  public function csc(){
    $this->load->helper('captcha');
    $this->load->library('form_validation');
    $this->form_validation->set_rules('captcha', 'Captcha', 'validate_captcha|trim');

    if($this->form_validation->run() == FALSE)
    {
        $this->session->set_flashdata("error",validation_errors());
        redirect(base_url() . 'iservices/admin/SysLogin');
    }

    $admincontactEmail = $this->input->post("cscid", TRUE);
    $password = $this->input->post("password", TRUE);
    if($password !== $this->adminpass){
        $this->session->set_flashdata("error","Incorrect Password");
        redirect(base_url('iservices/admin/SysLogin'));
    }
  
    
   
    if( true){

      $sessionArray = array(
        'userId' => $admincontactEmail,
        'role' => "csc",
        'image' =>  base_url("storage/images/avatar.png"),
        'name' => "CSC",
        'isLoggedIn' => TRUE,
        'user' => array()
      );
        //pre($sessionArray);
        $this->session->set_userdata($sessionArray);
        redirect(base_url() . 'iservices/admin/dashboard');
    }
  }
  

  /**
   * process_login
   *
   * @return void
   */
  public function process_login()
  {

    $mobile = $this->input->post("contactNumber", TRUE);
    $otp = $this->input->post("otp", TRUE);
    $value = $this->sms->verify_otp($mobile, $mobile, $otp);
    // if(ENV !== "DEV"){
    //   $value=$this->sms->verify_otp($mobile,$mobile,$otp);
    // }else {
    //   $value=true;
    // }
    // pre($value);
    //  if($mobile == "9742447516"){
    //   $value['status']=true;
    //  }
    //  $value['status']=true;
    if (!$value['status']) {
      $status["status"] = false;
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    }
    $checkUserExist = $this->login_model->checkMobileExist($mobile);
    if (isset($checkUserExist->digilocker_id)) {
      if (!empty($this->session->userdata('redirectTo'))) {
        $status["url"] = $this->session->userdata('redirectTo');
        $this->session->unset_userdata('redirectTo');
      } else {
        $status["url"] = base_url('iservices/transactions');
      }
    } else {
      $status["url"] = base_url('digilocker/userConsent');
    }
    // pre($this->session->userdata());
    // if(!empty($this->session->userdata('redirectTo'))){
    //   $status["url"] = $this->session->userdata('redirectTo');
    //   $this->session->unset_userdata('redirectTo');
    // }else {
    //   $status["url"] = base_url('iservices/transactions');
    // }
    // var_dump($status["url"]);die;
    $sessionArray = array(
      "name" => $checkUserExist->name,
      "email" => $checkUserExist->email,
      "mobile" => $checkUserExist->mobile,
      "userId" => $checkUserExist->_id,
      "isLoggedIn" => TRUE,
    );
    $this->session->set_userdata($sessionArray);
    $this->session->set_userdata("opt_status", true);

    $status["status"] = true;


    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($status));
  }





  public function logout()
  {
    $user_data = $this->session->all_userdata();
    foreach ($user_data as $key => $value) {
      if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
        $this->session->unset_userdata($key);
      }
    }
    $this->session->sess_destroy();
    redirect(base_url('iservices/admin/SysLogin'));
  }

  

}
