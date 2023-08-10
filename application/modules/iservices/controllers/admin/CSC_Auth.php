<?php
if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;

class CSC_Auth extends Frontend
{
  /**
   * __construct
   *
   * @return void
   */
  function __construct()
  {
    parent::__construct();
    
    // for production envrointment
    $this->REDIRECT_URI = "https://sewasetu.assam.gov.in/iservices/admin/csc-response";
    $this->CLIENT_ID = "0a739c35-3401-495e-9ea8-7f767cfb0bac";
    $this->CLIENT_SECRET = "orDxZaYnJAp5";
    $this->AUTHORIZATION_ENDPOINT = 'https://connect.csc.gov.in/account/authorize';
    $this->TOKEN_ENDPOINT = "https://connect.csc.gov.in/account/token";
    $this->CLIENT_TOKEN = "kJFK3PaEYM4ksM7l";
    $this->RESOURCE_URL = "https://connect.csc.gov.in/account/resource";

    // for test envrointment
    // $this->REDIRECT_URI = "https://103.8.249.17/rtps/iservices/admin/csc-response";
    // $this->CLIENT_ID = "df71396c-f82c-4430-8ee8-5a3d53486165";
    // $this->CLIENT_SECRET = "FA0sxD5VpSu1";
    // $this->AUTHORIZATION_ENDPOINT = 'https://connectuat.csccloud.in/account/authorize';

    // $this->TOKEN_ENDPOINT = "https://connectuat.csccloud.in/account/token";
    // $this->CLIENT_TOKEN = "3NvW0nplnNDGVs0a";
    // $this->RESOURCE_URL = "https://connectuat.csccloud.in/account/resource";

    $this->load->model('login_model');
    $this->load->library('AES');
    $this->encryption_key="1234567890123456";
  }
  public function index()
  {
    $url = base_url('iservices/admin/CSC_Auth/initiate');
    echo '<a href="' . $url . '">Connect CSC</a>';
  }

  public function initiate()
  {
    $state = rand(10000, 99999);
    
    $auth_parameters =
      "response_type=code&client_id=" . $this->CLIENT_ID .
      "&redirect_uri=" . urlencode($this->REDIRECT_URI) .
      "&state=" . $state;
    $url = $this->AUTHORIZATION_ENDPOINT . "?" . $auth_parameters;
   // var_dump(  $url );die;
    redirect($url);
    die;
  }


  function response()
  {
    $code  = $_GET['code'];
    $state = $_GET['state'];

    if (!$code)
      exit('No code!!');
    //fetch token
    $post_data = array(
      'code' => $code,
      'redirect_uri' => $this->REDIRECT_URI,
      'grant_type' => 'authorization_code',
      'client_id' => $this->CLIENT_ID,
      'client_secret' => $this->encrypt($this->CLIENT_SECRET)
    );

    $token_resp = $this->fetch_data($this->TOKEN_ENDPOINT, $post_data, false);

    $token_resp_data = (array)json_decode($token_resp);
    // pre($token_resp_data);
    $access_token = $token_resp_data && isset($token_resp_data['access_token']) ? $token_resp_data['access_token'] : false;

    if (!$access_token)
      exit('No token');
    $header_data = array(
      'Authorization' => 'Bearer ' . $access_token
    );

    $response = $this->fetch_data($this->RESOURCE_URL . '?access_token=' .  $access_token, false, $header_data);

    $resp_json = (array)json_decode($response);
    if($this->session->userdata('mis_csc_connect_auth_initiated')){
         $this->destroysession();
        // for mis
        if ($resp_json && isset($resp_json['User'])) {
          $this->login_model->add_csc($resp_json['User']);
          $sessionArray = array(
            'userId' => $resp_json['User']->csc_id,
            'role' => "csc",
            'image' =>  base_url("storage/images/avatar.png"),
            'name' => $resp_json['User']->fullname,
            'isLoggedIn' => TRUE,
            'user' => $resp_json['User'],
            "key"=>"misiservices"
          );

          //encrypt the data
          $input_array=json_encode($sessionArray);
          $aes = new AES($input_array, $this->encryption_key);
          $enc = $aes->encrypt();
          $redirectUrl="https://sewasetu.assam.gov.in/dashboard/csc/iservices/validate?value=".urlencode($enc);
          redirect($redirectUrl);
          die;
        } else {
          $this->session->set_flashdata('error', 'Authentication Failed');
          redirect(base_url() . 'dashboard');
        }
    }else{

      // for iservices 
      if ($resp_json && isset($resp_json['User'])) {
        $this->login_model->add_csc($resp_json['User']);
        $sessionArray = array(
          'userId' => $resp_json['User']->csc_id,
          'role' => "csc",
          'image' =>  base_url("storage/images/avatar.png"),
          'name' => $resp_json['User']->fullname,
          'isLoggedIn' => TRUE,
          'user' => $resp_json['User']
        );
        //pre($sessionArray);
        $this->session->set_userdata($sessionArray);
        redirect(base_url() . 'iservices/admin/dashboard');
      } else {
        $this->session->set_flashdata('error', 'Authentication Failed');
        redirect(base_url() . 'iservices');
      }
    }
   
  }

  function fetch_data($url, $post, $heads)
  {

    $curl = curl_init();

    $curl_opts = array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => $url,
      CURLOPT_HEADER => false,
      CURLINFO_HEADER_OUT => false,
      CURLOPT_USERAGENT => 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)',
      CURLOPT_POST => 1,
      // CURLOPT_CAINFO => FCPATH."assets/csccloud-in.pem",
      // CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_POSTFIELDS => array()
    );

    if ($post && is_array($post) && count($post) > 0)
      $curl_opts[CURLOPT_POSTFIELDS] = $post;

    if ($heads && is_array($heads) && count($heads) > 0)
      $curl_opts[CURLOPT_HTTPHEADER] = $heads;

    curl_setopt_array($curl, $curl_opts);

    $result = curl_exec($curl);
    //var_dump(curl_error($curl));
    // var_dump(  curl_getinfo($curl, CURLINFO_HTTP_CODE));die;
    // pre($result );
    if (!$result) {
      $httpcode = curl_getinfo($curl);
      print_r(array('Error code' => $httpcode, 'URL' => $url, 'post' => $post, 'LOG' => ""));
      exit("Error: 378972");
    }
    curl_close($curl);

    echo $result . "\n\n";
    return $result;
    //echo $result . "\n\n";
  }

  function encrypt($in_t)
  {
    $key = $this->CLIENT_TOKEN;
    $pre = ":";
    $post = "@";
    $plaintext = rand(10, 99) . $pre . $in_t . $post . rand(10, 99);
    $iv = "0000000000000000";
    $pval = 16 - (strlen($plaintext) % 16);
    $ptext = $plaintext . str_repeat(chr($pval), $pval);

   // $dec = @mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $ptext, MCRYPT_MODE_CBC, $iv);
   $dec = openssl_encrypt(
      $ptext,
            'AES-128-CBC',
            $key,
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $iv
    );

    return bin2hex($dec);
  }


  //function for mis csc connect login
  public function mis(){
    $private_key='misiservices';
    $data=$_GET['value'];
    if($data){
      $aes = new AES(urldecode( $data), $this->encryption_key);
        $enc = $aes->decrypt();
      if($enc ===  $private_key){
        
        $sessionArray=array(
          "mis_csc_connect_auth_initiated"=>true
        );
        $this->session->set_userdata($sessionArray);

        //pre($this->session->userdata('mis_csc_connect_auth_initiated'));
       $this->initiate();
      }
    }
  }

  private function destroysession(){
    $user_data = $this->session->all_userdata();
    foreach ($user_data as $key => $value) {
        if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
            $this->session->unset_userdata($key);
        }
    }
    $this->session->sess_destroy();
    return;
  }
  
  
  public function dummy(){
        $sessionArray = array(
          'userId' => "123456678",
          'role' => "csc",
          'image' =>  base_url("storage/images/avatar.png"),
          'name' => "alom",
          'isLoggedIn' => TRUE,
          'user' => (Object) array("mobileno"=>"975767676")
        );
        //pre($sessionArray);
        $this->session->set_userdata($sessionArray);
        redirect(base_url() . 'iservices/admin/dashboard');
  }
}
