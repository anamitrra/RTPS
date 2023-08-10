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
        $this->load->library('epramaan');
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
            if (strpos($currentUrl, 'iservices/devs/login') !== false) {
                $this->load->view('dual_login', $data);
            } else {
                $this->load->view('common_login', $data);
            }
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




    /**
     * send_otp
     *
     * @return void
     */
    public function send_otp()
    {

        $type = !empty($this->input->post('type', true)) ? $this->input->post('type', true) : false;
        $this->load->helper('captcha');
        $validatedCaptcha = validate_captcha();
        if ($type !== "resend") {
            if (!$validatedCaptcha['status']) {
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($validatedCaptcha));
            }
        }

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



    // public function logout()
    // {
    //   // pre($this->session->userdata('epramaan_data'));
    //   $user_data = $this->session->all_userdata();
    //   foreach ($user_data as $key => $value) {
    //     if ($key != 'session_id' && $key != 'ip_address' && $key != 'user_agent' && $key != 'last_activity') {
    //       $this->session->unset_userdata($key);
    //     }
    //   }
    //   $this->session->sess_destroy();
    //   $this->epramaan->epramaan_slo();
    //   // $this->slo_logout();
    //   // redirect(base_url());
    //   redirect('iservices/login');
    //   // redirect('https://rtps.assam.gov.in');
    // }


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
    }

    public function elogout()
    {
        if ($this->session->userdata('epramaan_data')) {
            $this->epramaan->epramaan_slo();
        } else {
            $this->logout();
        }
    }

    public function epramaan_slo_res()
    {
        // pre($_GET['LogoutResponse']);
        if (isset($_GET['LogoutResponse'])) {
            $logout_response = base64_decode($_GET['LogoutResponse']);
            $decrypted_data = json_decode($logout_response);
            if ($decrypted_data->logoutStatus) {
                $this->logout();
            } else {
                echo 'Something went wrong. Please try again.';
            }
        } else {
            echo 'Something went wrong. Please try again.';
        }
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
