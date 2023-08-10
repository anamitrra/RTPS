<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;

class Pwlogin extends Frontend
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('office/login_model');
  }

  public function index()
  {
    $this->isLoggedIn();
  }

  function isLoggedIn()
  {
    $isLoggedIn = $this->session->userdata('isLoggedIn');
    if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {

      $this->load->helper('captcha');
      $cap = generate_n_store_captcha();
      $data = [
        'cap' => $cap
      ];
      $this->load->view('includes/frontend/header');
      $this->load->view('office/password_loginview', $data);
      $this->load->view('includes/frontend/footer');
    } else {
      // echo 'logged in';
      redirect('spservices/office/dashboard');
    }
  }

  /**
   * send_otp
   *
   * @return void
   */


  public function password_login()
  {
    $username = $this->input->post('username');
    $password = $this->input->post('password');

    $system_pass = '$2y$10$H5zyZJ8Iwb5hWRQqlelOYOkU0eqw953FxA2WlL7KME3v8jH63d.2.';
    if ((!empty($username)) && (!empty($password))) {
      $checkUserExist = (array)$this->mongo_db->where(array('mobile' => $username, "is_active" => 1))->get('office_users');
      if ($checkUserExist) {
        if (verifyHashedPassword($password, $system_pass)) {
          if ($checkUserExist[0]->is_active == 1) {
            // $user_role = $this->mongo_db->where(array('_id' => new ObjectId($checkUserExist[0]->user_role_id)))->get('office_user_roles');
            $sessionArray = array(
              "name" => $checkUserExist[0]->name,
              "email" => $checkUserExist[0]->email,
              "mobile" => $checkUserExist[0]->mobile,
              "userId" => $checkUserExist[0]->_id,
              // "user_role" => $user_role->{'0'}->role_name,
              // "role_slug" => $user_role->{'0'}->slug,
              "user_role" => $checkUserExist[0]->user_role,
              "role_slug" => $checkUserExist[0]->role_slug_name,
              // "unique_user_id" => $checkUserExist[0]->unique_user_id,
              "designation" => $checkUserExist[0]->designation,
              "district_name" => $checkUserExist[0]->district_name,
              "circle_name" => $checkUserExist[0]->circle_name,
              "district_id" => $checkUserExist[0]->district_id,
              "circle_id" => $checkUserExist[0]->circle_id,
              "isLoggedIn" => TRUE,
              "isAdmin" => TRUE
            );
            $this->session->set_userdata($sessionArray);
            redirect('spservices/office/dashboard');
          } else {
            $this->session->set_flashdata('error', 'Your account is deactivated by admin !!!');
            $this->index();
          }
        } else {
          $this->session->set_flashdata('error', 'Invalid username or password.');
          $this->index();
        }
      } else {
        $this->session->set_flashdata('error', 'Username doesnot exist!!!');
        $this->index();
      }
    } else {
      $this->session->set_flashdata('error', 'Please enter username and password');
      $this->index();
    }
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
    redirect('spservices/mcc/user-login');
    // redirect('iservices/login');
    // redirect('https://rtps.assam.gov.in');
  }
}
