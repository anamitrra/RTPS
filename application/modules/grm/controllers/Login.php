<?php

use MongoDB\BSON\UTCDateTime;

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Login extends frontend{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
        $this->load->helper('captcha');
        $cap = generate_n_store_captcha();
        $data = [
            'cap' => $cap,
        ];
        $this->load->view('includes/frontend/header');
        $this->load->view('login/public',$data);
        $this->load->view('includes/frontend/footer');
    }

    public function admin_login(){
        $this->load->helper('captcha');
        $cap = generate_n_store_captcha();
        $data = [
            'cap' => $cap,
        ];
        $this->load->view('includes/frontend/header');
        $this->load->view('login/admin',$data);
        $this->load->view('includes/frontend/footer');
    }

    public function process_admin_login(){
        $this->load->library("encryption_custom");
        $password = $this->encryption_custom->decrypt($this->input->post("password"), 'asdf-ghjk-qwer-tyui');
        $email = $this->encryption_custom->decrypt($this->input->post("email"), 'asdf-ghjk-qwer-tyui');

        //pre($password);
        $this->load->model('login_model');
        $this->load->model('roles_model');

        $this->load->helper('captcha');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');
        $this->form_validation->set_rules('captcha', 'Captcha', 'validate_captcha|trim');

        if($this->form_validation->run() == FALSE)
        {
            $this->session->set_flashdata("error",validation_errors());
            redirect(base_url() . 'grm/admin/login');
        }

        if ($email == "" || $password == "") {
            redirect(base_url() . 'grm/admin/login');
        } else {

            $result = $this->login_model->loginMe($email, $password);

            if (isset($result) && $result != NULL) {
                $dept = $this->login_model->getDepartment($result->department[0]);
                $sessionArray = array(
                    'userId' => $result->_id,
                    'role' => $this->roles_model->get_role_info($result->roleId),
                    'image' => (isset($result->photo)) ? base_url($result->photo) : base_url("storage/images/avatar.png"),
                    'name' => $result->name,
                    'isLoggedIn' => TRUE,
                    'department_name' => $dept->department_name,
                    'department_id' => $dept->department_id,
                );
                //pre($sessionArray);
                $this->session->set_userdata($sessionArray);
                redirect(base_url() . 'grm/dashboard/');
            } else {
                $this->session->set_flashdata('error', 'Email or password mismatch');
                redirect(base_url() . 'grm/admin/login');
            }
        }
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
                  $msg = "Your Otp for Public GRM Login is {{otp}}";
                  $this->sms->send_otp($mobile, $mobile, $msg);
                  $status["status"] = true;

      //            return $status;

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

    /**
     * process_login
     *
     * @return void
     */
    public function process_login()
    {
        $mobile = $this->input->post("contactNumber", TRUE);
        $otp=$this->input->post("otp", TRUE);
        $value=$this->sms->verify_otp($mobile,$mobile,$otp);
//         pre($value);
        if (!$value) {
            $status["status"] = false;
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($status));
        }
        // $checkUserExist=$this->login_model->checkMobileExist($mobile);
        // if(!empty($this->session->userdata('redirectTo'))){
        //   $status["url"] = $this->session->userdata('redirectTo');
        //   $this->session->unset_userdata('redirectTo');
        // }else {
        //   $status["url"] = base_url('external-portal/transactions');
        // }
// var_dump($status["url"]);die;
        if(!$value['status']){
          $status["status"] = false;
          return $this->output
              ->set_content_type('application/json')
              ->set_status_header(200)
              ->set_output(json_encode($status));
        }
        $sessionArray=array(
          "name"=>$mobile,
          "email"=>$mobile,
          "mobile"=>$mobile,
          "userId"=>null,
          "isLoggedIn"=>TRUE,
        );
        $this->session->set_userdata($sessionArray);
        $this->session->set_userdata("opt_status",true);

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
        redirect('grm/admin/login');
    }
}
