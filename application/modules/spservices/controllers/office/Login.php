<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;

class Login extends Frontend
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('office/login_model');
  }

  public function index()
  {
    // $data = $this->login_model->get();
    // pre($data);
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
      $this->load->view('office/login_view', $data);
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

  public function send_otp()
  {
    $this->load->helper('captcha');
    $inputCaptcha = $this->security->xss_clean($this->input->post("captcha"));

    $validatedCaptcha = validate_captcha();
    $type = !empty($this->input->post('type', true)) ? $this->input->post('type', true) : false;
    if ($type !== "resend") {
      if (!$validatedCaptcha['status']) {
        $status["msg"] = "Invalid Captcha";
        $status["status"] = false;
        return $this->output
          ->set_content_type('application/json')
          ->set_status_header(200)
          ->set_output(json_encode($status));
      }
    }
    $mobile = strval($this->input->post('contactNumber', true));
    $check_user = $this->login_model->checkUser(array("mobile" => $mobile, "is_active" => 1));
    if ($check_user) {
      if (strlen($check_user->name) > 20) {
        $name = 'user';
      } else {
        $name = $check_user->name;
      }
      $mobile = $check_user->mobile;
      $status = array();
      $status['m_no'] = $mobile;
      $status["msg"] = "Entered";
      $sms_data = array(
        "name" => $name,
        "service_name" => 'MCC login',
        "time_interval" => '10'
      );
      $this->sms->send_generic_otp($mobile, $mobile, $sms_data);

      $status["status"] = true;
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    } else {
      $status["msg"] = "Invalid user or Account is not activated yet.";
      $status["status"] = false;
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    }
  } //End of send_otp()

  /**
   * process_login
   *
   * @return void
   */

  public function process_login()
  {
    $mobile = strval($this->input->post('contactNumber', true));
    $otp = $this->input->post("otp", TRUE);

    $value = $this->sms->verify_otp($mobile, $mobile, $otp);
    if (!$value['status']) {
      $status["status"] = false;
      return $this->output
        ->set_content_type('application/json')
        ->set_status_header(200)
        ->set_output(json_encode($status));
    }

    $check_user = $this->login_model->checkUser(array("mobile" => $mobile, "is_active" => 1));

    if ($check_user) {
      // $user_role = $this->mongo_db->where(array('_id' => new ObjectId($checkUserExist->user_role_id)))->get('office_user_roles');
      $sessionArray = array(
        "name" => $check_user->name,
        "email" => $check_user->email,
        "mobile" => $check_user->mobile,
        "userId" => $check_user->_id,
        // "user_role" => $user_role->{'0'}->role_name,
        // "role_slug" => $user_role->{'0'}->slug,
        "user_role" => $check_user->user_role,
        "role_slug" => $check_user->role_slug_name,
        // "unique_user_id" => $check_user->unique_user_id,
        "designation" => $check_user->designation,
        "district_name" => $check_user->district_name,
        "circle_name" => $check_user->circle_name,
        "district_id" => $check_user->district_id,
        "circle_id" => $check_user->circle_id,
        "offline_office_id" => !empty($check_user->offline_office_id) ? $check_user->offline_office_id : null,
        "isLoggedIn" => TRUE,
        "isAdmin" => TRUE
      );
      $this->session->set_userdata($sessionArray);
      $this->session->set_userdata("opt_status", true);

      $status["status"] = true;
      $status["url"] = base_url('spservices/office/dashboard');
    } else {
      $status["status"] = false;
      // $status["is_deactive"] = true;
    }

    return $this->output
      ->set_content_type('application/json')
      ->set_status_header(200)
      ->set_output(json_encode($status));
  } //End of process_login()

  public function password_login()
  {
    $username = $this->input->post('username');
    $password = $this->input->post('password');

    if ((!empty($username)) && (!empty($password))) {
      $checkUserExist = (array)$this->mongo_db->where(array('mobile' => $username))->get('office_users');
      if ($checkUserExist) {
        if (verifyHashedPassword($password, $checkUserExist[0]->password)) {
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

            $this->session->set_userdata($sessionArray);
            $status["url"] = base_url('spservices/office/dashboard');
            $status["status"] = true;
          } else {
            $status["status"] = false;
            $status["msg"] = "Your account is deactivated by admin !!!";
          }
        } else {
          $status["status"] = false;
          $status["msg"] = "Invalid username or password";
        }
      } else {
        $status["status"] = false;
        $status["msg"] = "Username doesn't exist!!!";
      }
    } else {
      $status["status"] = false;
      $status["msg"] = "Please enter username and password";
    }
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
    redirect('spservices/mcc/user-login');
    // redirect('iservices/login');
    // redirect('https://rtps.assam.gov.in');
  }

  public function aes_encryption()
  {
    $key = "$1234%&Key%";
    $data = "AS200226V0313962/AS";




    $this->load->library('AES');
    $aes = new AES();
    //  var_dump($this->load->helper('aes'));die;
    $en = $aes->encrypt($data, $key, "12355");

    var_dump($en);
    var_dump($aes->decrypt($en, $key, "12355"));
    die;




    // response ::

    // 4r7wSg1FjwMV33ktC4hAtHMOSjKvzxo7ONZtiVhneug7cmnoUC18HoYoaSMPTZBa1jMrN87yERKLTCtKArSKYOLl1s3hpuPMfysLrh4USV6USE6MvqzMwUI/kSlHr9W7mHMvyJa8Qpo2ykNJZnMYJ18e+ph0n0VaAdKx6Fpn1Yn0itHCI5tDIasg9nvic3M+O+pojqoLEwOUWk0Iw+rlQOgGkkK5kQcYzn7w1fXTQ89Pu4T86zCoIRdGPiE1i9QaWxFCXBsNKb13lvSzL/iQ3ejqtc6TipPQytar6ZgqxMGE9qKji2qQwCHlnsmC6SIHkddpeMGpUm+kdCK+jdYURocR/zX1cfDjg1wjWXKvNzVyjW61Wu4QeoaZddE1fvpovC+StcisgmiYhgtFxchBzoCoPkgZorrRw3F2QC4X+zphtK9zNA9exQKBSgd71G40


    //AS200226V0313962/AS
    // 9x5CCz4G5GnxwULPdHXcPmkH9Xdg9KjENTl9OuKf3tk
    //  $input="AS200226V0313962/AS";

    // byte[] plainTextbytes = plainText.getBytes(characterEncoding);
    // 		byte[] keyBytes = getKeyBytes(key);
    // 		return Base64.encodeBase64URLSafeString(encrypt(plainTextbytes, keyBytes, keyBytes));
    //
    // $inputText = "AS200226V0313962/AS";
    // $inputKey = "$1234%&Key%";
    // $blockSize = 256;
    // $aes = new AES($inputText, $inputKey, $blockSize);
    // $enc = $aes->encrypt(); var_dump($enc);die;
    $url = "http://164.100.78.110/knowapplservice/knowappl/fetchyourApplDetails/?" . $enc;
    var_dump($url);
    die;

    $cURLConnection = curl_init();

    curl_setopt($cURLConnection, CURLOPT_URL, "http://dummy.restapiexample.com/api/v1/employees");
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
      'content-type: application/json'
    ));
    $phoneList = curl_exec($cURLConnection);
    var_dump($phoneList);
    die;
    curl_close($cURLConnection);

    $jsonArrayResponse = json_decode($phoneList);
    var_dump($jsonArrayResponse);
    die;

    // $aes->setData($enc);
    // $dec=$aes->decrypt();
    // echo "After encryption: ".$enc."<br/>";
    // echo "After decryption: ".$dec."<br/>";


  }
}
