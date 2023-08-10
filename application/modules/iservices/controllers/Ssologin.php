<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class Ssologin extends Frontend
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
            $this->load->view('includes/frontend/header');
            $this->load->view('epramaan_login_msg');
            $this->load->view('includes/frontend/footer');
        } else {
            if (!empty($this->session->userdata('redirectTo'))) {
                $url = $this->session->userdata('redirectTo');
                $this->session->unset_userdata('redirectTo');
                redirect($url);
            } else if (!empty($this->session->userdata('role'))) {
                redirect('iservices/admin/my-transactions');
            } else {
                redirect('iservices/transactions');
            }
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
        redirect('iservices/login');
    }

    public function elogout()
    {
        if ($this->session->userdata('epramaan_data')) {
            $this->epramaan->epramaan_slo('citizen');
        } else {
            $this->logout();
        }
    }

    public function logoutresponse()
    {
        // pre($_GET['LogoutResponse']);
        if (isset($_GET['LogoutResponse'])) {
            $logout_response = base64_decode($_GET['LogoutResponse']);
            $decrypted_data = json_decode($logout_response);
            if ($decrypted_data->logoutStatus) {
                $this->logout();
                redirect(base_url('iservices/login/logout'));
            } else {
                if ($decrypted_data->optionalLogoutMessage == 'user session expired') {
                    $this->logout();
                }
            }
        } else {
            echo 'Something went wrong. Please try again.';
        }
    }

    public function citizenlogin()
    {
        $this->load->config('digilocker/dlconfig');
        $this->session->set_userdata("applyBy", 'citizen');

        setcookie("verifier_c", "", time() - 3600, "/");
        setcookie("nonce_c", "", time() - 3600, "/");
        $scope = 'openid';
        $serviceId = $this->config->item('ePserviceId');
        $aeskey = $this->config->item('ePaesKey');
        $redirect_uri = $this->config->item('SSOurl');
        $response_type = 'code';
        $code_challenge_method = 'S256';
        $request_uri = $this->config->item('ePrequestUrl');
        $state = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        //nonce
        $nonce = bin2hex(random_bytes(16));
        setcookie("nonce_c", "$nonce", time() + 3600, "/");
        //verifier
        $verifier_bytes = random_bytes(64);
        $code_verifier = rtrim(strtr(base64_encode($verifier_bytes), "+/", "-_"), "=");
        setcookie("verifier_c", "$code_verifier", time() + 3600, "/");
        //code challenge
        $challenge_bytes = hash("sha256", $code_verifier, true);
        $code_challenge = rtrim(strtr(base64_encode($challenge_bytes), "+/", "-_"), "=");
        $input = $serviceId . $aeskey . $state . $nonce . $redirect_uri . $scope . $code_challenge;
        //apiHmac
        $apiHmac = hash_hmac('sha256', $input, $aeskey, true);
        $apiHmac = base64_encode($apiHmac);
        $url = $this->config->item('ePrequestUrl');
        $finalUrl = $url . "?&scope=" . $scope . "&response_type=" . $response_type . "&redirect_uri=" . $redirect_uri . "&state=" . $state . "&code_challenge_method=" . $code_challenge_method . "&nonce=" . $nonce . "&client_id=" . $serviceId . "&code_challenge=" . $code_challenge . "&request_uri=" . $request_uri . "&apiHmac=" . $apiHmac;
        // echo $finalUrl;
        // pre($this->session->userdata());

        redirect($finalUrl);
        // echo '<a class="btn btn-primary login_btn" style="width: 220px;background-color: darkcyan;border-radius:0" href="' . $finalUrl . '">Login with e-Pramaan</a>';
    }


    public function pfclogin()
    {
        $this->load->config('digilocker/dlconfig');
        $this->session->set_userdata("applyBy", 'pfc');

        setcookie("verifier_c", "", time() - 3600, "/");
        setcookie("nonce_c", "", time() - 3600, "/");
        $scope = 'openid';
        $serviceId = $this->config->item('ePserviceId');
        $aeskey = $this->config->item('ePaesKey');
        $redirect_uri = $this->config->item('SSOurl');
        $response_type = 'code';
        $code_challenge_method = 'S256';
        $request_uri = $this->config->item('ePrequestUrl');
        $state = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        //nonce
        $nonce = bin2hex(random_bytes(16));
        setcookie("nonce_c", "$nonce", time() + 3600, "/");
        //verifier
        $verifier_bytes = random_bytes(64);
        $code_verifier = rtrim(strtr(base64_encode($verifier_bytes), "+/", "-_"), "=");
        setcookie("verifier_c", "$code_verifier", time() + 3600, "/");
        //code challenge
        $challenge_bytes = hash("sha256", $code_verifier, true);
        $code_challenge = rtrim(strtr(base64_encode($challenge_bytes), "+/", "-_"), "=");
        $input = $serviceId . $aeskey . $state . $nonce . $redirect_uri . $scope . $code_challenge;
        //apiHmac
        $apiHmac = hash_hmac('sha256', $input, $aeskey, true);
        $apiHmac = base64_encode($apiHmac);
        $url = $this->config->item('ePrequestUrl');
        $finalUrl = $url . "?&scope=" . $scope . "&response_type=" . $response_type . "&redirect_uri=" . $redirect_uri . "&state=" . $state . "&code_challenge_method=" . $code_challenge_method . "&nonce=" . $nonce . "&client_id=" . $serviceId . "&code_challenge=" . $code_challenge . "&request_uri=" . $request_uri . "&apiHmac=" . $apiHmac;
        // echo $finalUrl;
        // pre($this->session->userdata());
        redirect($finalUrl);
        // echo '<a class="btn btn-primary login_btn" style="width: 220px;background-color: darkcyan;border-radius:0" href="' . $finalUrl . '">Login with e-Pramaan</a>';
    }
}
