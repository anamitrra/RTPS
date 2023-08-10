<?php if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

/**
 * Class : Login (LoginController)
 * Login class to control to authenticate user credentials and starts user's session.
 * @author : Prasenjit Das -9401250708
 * @version : 1.1
 * @since : 08 may 2020
 */
class Login extends Frontend
{
  /**
   * This is default constructor of the class
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->model('login_model');
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
      $data=array("pageTitle" => "Login");
      $this->load->view('includes/frontend/header');
      $this->load->view('login',$data);
      $this->load->view('includes/frontend/footer');
    } else {
      redirect('external-portal/transactions');
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
      //    var_dump($mobile);die;
          $status = array();
          $status['m_no'] = $mobile;
  //        pre($value);
          if (!empty($mobile)) {
              $status["msg"] = "Entered";
              $msg = "Your Otp for RTPS Login is {{otp}}";
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

          $value=true;//$this->sms->verify_login_otp($mobile,$otp);
          //pre($value);
          if (!$value) {
              $status["status"] = false;
              return $this->output
                  ->set_content_type('application/json')
                  ->set_status_header(200)
                  ->set_output(json_encode($status));
          }
          $checkUserExist=$this->login_model->checkMobileExist($mobile);
          if(!empty($this->session->userdata('redirectTo'))){
            $status["url"] = $this->session->userdata('redirectTo');
            $this->session->unset_userdata('redirectTo');
          }else {
            $status["url"] = base_url('external-portal/transactions');
          }
// var_dump($status["url"]);die;
          $sessionArray=array(
            "name"=>$checkUserExist->name,
            "email"=>$checkUserExist->email,
            "mobile"=>$checkUserExist->mobile,
            "userId"=>$checkUserExist->_id,
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
    redirect('external-portal/login');
  }

  public function aes_encryption(){
    $key="$1234%&Key%";
    $data="AS200226V0313962/AS";




  $this->load->library('AES');
      $aes = new AES();
  //  var_dump($this->load->helper('aes'));die;
  $en=$aes->encrypt($data,$key,"12355");

    var_dump($en);
    var_dump($aes->decrypt($en,$key,"12355"));die;




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
$url = "http://164.100.78.110/knowapplservice/knowappl/fetchyourApplDetails/?".$enc;
 var_dump($url);die;

$cURLConnection = curl_init();

curl_setopt($cURLConnection, CURLOPT_URL, "http://dummy.restapiexample.com/api/v1/employees");
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
    'content-type: application/json'
));
$phoneList = curl_exec($cURLConnection);var_dump($phoneList);die;
curl_close($cURLConnection);

$jsonArrayResponse = json_decode($phoneList);
var_dump($jsonArrayResponse);die;

// $aes->setData($enc);
// $dec=$aes->decrypt();
// echo "After encryption: ".$enc."<br/>";
// echo "After decryption: ".$dec."<br/>";


  }
}
