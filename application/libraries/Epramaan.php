<?php 
defined('BASEPATH') or exit('No direct script access allowed');

class Epramaan
{
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->config('digilocker/dlconfig');
        // $this->CI->load->model('digilocker/digilocker_model');
    }

    public function epramaan_login_btn($userType = null)
    {
        if (strlen($userType)) {
            $this->CI->session->set_userdata("applyBy", $userType);
            if ($userType == 'citizen') {
                setcookie("verifier_c", "", time() - 3600, "/");
                setcookie("nonce_c", "", time() - 3600, "/");
                $scope = 'openid';
                $serviceId = $this->CI->config->item('ePserviceId');
                $aeskey = $this->CI->config->item('ePaesKey');
                $redirect_uri = $this->CI->config->item('SSOurl');
                $response_type = 'code';
                $code_challenge_method = 'S256';
                $request_uri = $this->CI->config->item('ePrequestUrl');
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
                $url = $this->CI->config->item('ePrequestUrl');
                $finalUrl = $url . "?&scope=" . $scope . "&response_type=" . $response_type . "&redirect_uri=" . $redirect_uri . "&state=" . $state . "&code_challenge_method=" . $code_challenge_method . "&nonce=" . $nonce . "&client_id=" . $serviceId . "&code_challenge=" . $code_challenge . "&request_uri=" . $request_uri . "&apiHmac=" . $apiHmac;
                echo '<a class="btn btn-primary login_btn" style="width: 220px;background-color: darkcyan;border-radius:0" href="' . $finalUrl . '">Login with e-Pramaan</a>';
            }
            else if ($userType == 'pfc') {
                setcookie("verifier_c", "", time() - 3600, "/");
                setcookie("nonce_c", "", time() - 3600, "/");
                $scope = 'openid';
                $serviceId = $this->CI->config->item('ePserviceId');
                $aeskey = $this->CI->config->item('ePaesKey');
                $redirect_uri = $this->CI->config->item('SSOurl');
                $response_type = 'code';
                $code_challenge_method = 'S256';
                $request_uri = $this->CI->config->item('ePrequestUrl');
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
                $url = $this->CI->config->item('ePrequestUrl');
                $finalUrl = $url . "?&scope=" . $scope . "&response_type=" . $response_type . "&redirect_uri=" . $redirect_uri . "&state=" . $state . "&code_challenge_method=" . $code_challenge_method . "&nonce=" . $nonce . "&client_id=" . $serviceId . "&code_challenge=" . $code_challenge . "&request_uri=" . $request_uri . "&apiHmac=" . $apiHmac;
                echo '<a class="btn btn-primary login_btn" style="width: 220px;background-color: darkcyan;border-radius:0" href="' . $finalUrl . '">Login with e-Pramaan</a>';
            }
        }
    }

    public function epramaan_slo()
    {
        $epramaan_data = $this->CI->session->userdata('epramaan_data');
        $epramaanSLOurl = $this->CI->config->item('ePSLOurl');
        $service_id = $this->CI->config->item('ePserviceId');
        $redirect_uri = $this->CI->config->item('SLOurl');
        $iss = 'ePramaan';
        $logoutRequestId = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $input = $service_id . $epramaan_data->session_id . $iss . $logoutRequestId . $epramaan_data->sub . $redirect_uri;
        //apiHmac
        $apiHmac = hash_hmac('sha256', $input, $logoutRequestId, true);
        $apiHmac = base64_encode($apiHmac);
        $json = array(
            "clientId" => $service_id,
            "sessionId" => $epramaan_data->session_id,
            "hmac" => $apiHmac,
            "iss" => $iss,
            "logoutRequestId" => $logoutRequestId,
            "sub" => $epramaan_data->sub,
            "redirectUrl" => $redirect_uri,
            "customParameter" => "custom"
        );
        $json_data = json_encode($json);
        echo "
            <form action='" . $epramaanSLOurl . "' method='post' name='slo_form'>
                <input type='hidden' name='data' value='" . $json_data . "'>
            </form>
            
            <script>
            window.onload = function(){
                document.forms['slo_form'].submit();
            }
            </script>
        ";
    }
}
