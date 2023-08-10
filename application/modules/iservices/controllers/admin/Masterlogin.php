<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Masterlogin extends Frontend
{
  /**
   * This is default constructor of the class
   */
  public function __construct()
  {
    parent::__construct();
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
    $isLoggedIn = $this->session->userdata('isLoggedIn');
    if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {

      $this->load->helper('captcha');
      $cap = generate_n_store_captcha();
      $data = [
        'cap' => $cap,
        //            "pageTitle" => "Login"
      ];
      $this->load->view('includes/frontend/header');
      $this->load->view('admin/login', $data);
      $this->load->view('includes/frontend/footer');
    } else {
      redirect('iservices/admin/dashboard');
    }
  }

  /**
   * This function used to logged in user
   */
  public function loginMe()
  {

    $this->load->library("encryption_custom");
    $password = $this->encryption_custom->decrypt($this->input->post("password"), 'asdf-ghjk-qwer-tyui');
    $email = $this->encryption_custom->decrypt($this->input->post("email"), 'asdf-ghjk-qwer-tyui');

    //pre($password);
    $this->load->model('admin/login_model');
    $this->load->model('admin/roles_model');

    $this->load->helper('captcha');
    $this->load->library('form_validation');
    $this->form_validation->set_rules('captcha', 'Captcha', 'validate_captcha|trim');

    if ($this->form_validation->run() == FALSE) {
      $this->session->set_flashdata("error", validation_errors());
      redirect(base_url() . 'iservices/admin/login');
    }

    if ($email == "" || $password == "") {
      $this->index();
    } else {

      $result = $this->login_model->loginMe($email, $password);
      //  pre($result);
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
        redirect(base_url() . 'iservices/admin/dashboard');
      } else {
        $this->session->set_flashdata('error', 'Email or password mismatch');
        redirect(base_url() . 'iservices/admin/login');
      }
    }
  }

  /**
   * This function used to load forgot password view
   */
  public function forgotPassword()
  {
    $this->load->helper('captcha');
    $cap = generate_n_store_captcha();
    $data = [
      'cap' => $cap,
      //            "pageTitle" => "Forgot Password"
    ];
    $this->load->view('includes/frontend/header');
    $this->load->view('forgot_password', $data);
    $this->load->view('includes/frontend/footer');
  }

  /**
   * This function used to generate reset password request link
   */
  function resetPasswordUser()
  {
    $this->load->model('login_model');
    $status = '';
    $this->load->library('form_validation');
    $this->form_validation->set_rules('login_email', 'Email', 'trim|required|valid_email|xss_clean');
    $this->load->helper('captcha');
    $this->form_validation->set_rules('captcha', 'Captcha', 'validate_captcha|trim');
    if ($this->form_validation->run() == FALSE) {
      //            $this->forgotPassword();
      $this->session->set_flashdata('error', validation_errors());
      redirect('appeal/admin/login/forgot-password');
    } else {
      $email = $this->input->post('login_email');
      if ($this->login_model->checkEmailExist($email)) {
        $encoded_email = base64_encode($email);
        $this->load->helper('string');
        $data['email'] = $email;
        $data['activation_id'] = random_string('alnum', 15);
        $data['createdDtm'] = date('Y-m-d H:i:s');
        $data['agent'] = getBrowserAgent();
        $data['client_ip'] = $this->input->ip_address();
        $data['is_expired'] = false;
        $save = $this->login_model->resetPasswordUser($data);
        if ($save) {
          $data1['reset_link'] = base_url() . "appeal/reset-password/" . $data['activation_id'] . '/' . $encoded_email;

          $userInfo = $this->login_model->getCustomerInfoByEmail($email);
          if (!empty($userInfo)) {
            $data1["name"] = $userInfo->name;
            $data1["email"] = $userInfo->email;
            $data1["message"] = "Reset Your Password";
          }
          $sendStatus = resetPasswordEmail($data1);
          if ($sendStatus) {
            $status = "success";
            $this->session->set_flashdata($status, "Reset password link sent successfully, please check the registered email.");
          } else {
            $status = "error";
            $this->session->set_flashdata($status, "Email has been failed, try again.");
          }
        } else {
          $status = 'unable';
          $this->session->set_flashdata($status, "It seems an error while sending your details, try again.");
        }
      } else {
        $status = 'error';
        $this->session->set_flashdata($status, "This email is not registered with us.");
      }
      redirect(base_url() . 'appeal/login/forgot-password');
    }
  }

  // This function used to reset the password
  function resetPasswordConfirmUser($activation_id, $encodedEmail)
  {
    $email = base64_decode($encodedEmail);
    $this->load->model('login_model');
    // Get email and activation code from URL values at index 3-4
    //        $email = urldecode($email);
    // Check activation id in database
    $checkActivation = $this->login_model->checkActivationDetails($email, $activation_id);
    $data['email'] = $email;
    $data['activation_code'] = $activation_id;
    if (!empty((array)$checkActivation)) {

      $this->load->helper('captcha');
      $cap = generate_n_store_captcha();
      $data['cap'] = $cap;
      $this->load->view('includes/frontend/header');
      $this->load->view('reset_password', $data);
      $this->load->view('includes/frontend/footer');
    } else {
      redirect('appeal/admin/login');
      exit(404);
    }
  }

  // This function used to create new password
  function createPasswordUser()
  {
    $this->load->model('login_model');
    $email = $this->input->post("email");
    $activation_id = $this->input->post("activation_code");
    $this->load->library('form_validation');
    $this->form_validation->set_rules('password', 'Password', 'required|max_length[20]');
    $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[password]|max_length[20]');
    if ($this->form_validation->run() == FALSE) {
      $this->session->set_flashdata('error', validation_errors());
      redirect('appeal/reset-password/' . $activation_id . '/' . base64_encode($email));
    } else {
      $password = $this->input->post('password');
      // Check activation id in database
      $checkActivation = $this->login_model->checkActivationDetails($email, $activation_id);
      if (!empty((array)$checkActivation)) {
        // update password
        $this->load->model('users_model');
        $userFilter = ['email' => $email, 'isDeleted' => '0'];
        $userInput = ['password' => getHashedPassword($password)];
        $this->users_model->update_where($userFilter, $userInput);
        //                $this->login_model->createPasswordUser($email, $password);
        $this->load->model('reset_password_model');
        // set reset password to expired
        $expireResetPasswordFilter = ['email' => $email, 'activation_id' => $activation_id];
        $expireResetPasswordInput = ['is_expired' => true];
        $this->reset_password_model->update_where($expireResetPasswordFilter, $expireResetPasswordInput);
        $status = 'success';
        $message = 'Password changed successfully';
      } else {
        $status = 'error';
        $message = 'Password changed failed';
      }
      $this->session->set_flashdata($status, $message);
      redirect(base_url() . "appeal/admin/login");
    }
  }

  function newpass()
  {
    $this->login_model->createPasswordUser("prasn2009@gmail.com", "admin");
  }
  public function addadmin()
  {
    $this->load->model('admin/users_model');
    $data = array(
      'email' => "sahebul99@gmail.com",
      'password' => getHashedPassword("admin"),
      'name' => "System Administrator",
      'designation' => "System Administrator",
      'office_name' => "Head office",
      'office_address' => "Guwahati",
      'mobile' => "9742447516",
      'roleId' => "",
      'isDeleted' => '0',
      'createdBy' => "",
      'createdDtm' => date("Y-m-d h:i:s"),
      'updatedBy' => "",
      'updatedDtm' => date("Y-m-d h:i:s"),
      'department' => "",
    );
    $this->users_model->insert($data);
    exit("done");
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
    redirect(base_url());
    // redirect('iservices/login');
  }

  public function elogout()
  {
    if ($this->session->userdata('epramaan_data')) {
      $this->epramaan->epramaan_slo();
    } else {
      $this->logout();
    }
  }

  /**
   * send_otp
   *
   * @return void
   */
  public function send_otp()
  {
    $this->load->helper('captcha');
    $validatedCaptcha = validate_captcha();
    $type = !empty($this->input->post('type', true)) ? $this->input->post('type', true) : false;
    if ($type !== "resend") {
      if (!$validatedCaptcha['status']) {
        return $this->output
          ->set_content_type('application/json')
          ->set_status_header(200)
          ->set_output(json_encode($validatedCaptcha));
      }
    }

    $this->load->model('admin/login_model');
    $email = strval($this->input->post('contactEmail', true));
    $user = $this->login_model->getUserMobileByEmail($email);
    if (empty($user->mobile)) {
      $status["msg"] = "User not registered";
      $status["status"] = false;
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    }

    $mobile = $user->mobile;
    $status = array();
    $status['m_no'] = $mobile;
    //        pre($value);
    if (!empty($mobile)) {
      $status["msg"] = "Entered";
      $status["status"] = true;
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    } else {
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
    $this->load->model('admin/login_model');
    $this->load->model('admin/roles_model');
    $email = strval($this->input->post('contactEmail', true));
    $otp = $this->input->post("otp", TRUE);
    $user = $this->login_model->getUserMobileByEmail($email);

    $mobile = $user->mobile;
    $value = $this->verify_otp($mobile, $otp);
    if (!$value['status']) {
      $status["status"] = false;
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    }


    $sessionArray = array(
      'userId' => $user->_id,
      'role' => $this->roles_model->get_role_info($user->roleId),
      'image' => (isset($user->photo)) ? base_url($user->photo) : base_url("storage/images/avatar.png"),
      'name' => $user->name,
      'isLoggedIn' => TRUE,
    );
    // pre($sessionArray);
    $this->session->set_userdata($sessionArray);
    $status["status"] = true;
    if (!empty($this->session->userdata('redirectTo'))) {
      $url = $this->session->userdata('redirectTo');
      if (strpos($url, "basundhara") == true) {
        $status["url"] = $url;
      } else {
        if (strpos($url, "admin") == false) {
          $status["url"] = str_replace("iservices", "iservices/admin", $url);
        } else {
          $status["url"] = $url;
        }
      }

      $this->session->unset_userdata('redirectTo');
    } else {
      $status["url"] = base_url() . 'iservices/admin/dashboard';
    }

    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($status));
  }

  public function verify_otp($mobile, $otp)
  {
    // dev
    $actual_otp = 'hoIF';
    if ($otp === $actual_otp) {
      return array('status' => true);
    } else {
      return array('status' => false);
    }
  }
}
