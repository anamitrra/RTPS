<?php defined('BASEPATH') or exit('No direct script access allowed');

class Epramaan
{
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->config('digilocker/dlconfig');
    }

    public function epramaan_login_btn()
    {
        setcookie("verifier_c", "", time() - 3600, "/");
        setcookie("nonce_c", "", time() - 3600, "/");
        $scope = 'openid';
        $serviceId = $this->CI->config->item('e_serviceid');
        $aeskey = $this->CI->config->item('e_aeskey');
        $redirect_uri = $this->CI->config->item('redirectUrl');
        $response_type = 'code';
        $code_challenge_method = 'S256';
        $request_uri = $this->CI->config->item('e_request_uri');
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
        $url = $this->CI->config->item('e_request_uri');
        $finalUrl = $url . "?&scope=" . $scope . "&response_type=" . $response_type . "&redirect_uri=" . $redirect_uri . "&state=" . $state . "&code_challenge_method=" . $code_challenge_method . "&nonce=" . $nonce . "&client_id=" . $serviceId . "&code_challenge=" . $code_challenge . "&request_uri=" . $request_uri . "&apiHmac=" . $apiHmac;
        echo '<a class="btn btn-primary" style="background-color: darkcyan;" href="' . $finalUrl . '">Login with e-Pramaan</a>';
    }

    public function epramaan_slo()
    {

        $epramaan_data = $this->CI->session->userdata('epramaan_data');
        // $epramaanSLOurl = 'https://epramaan.meripehchaan.gov.in/openid/jwt/processOIDCSLORequest.do';
        $epramaanSLOurl = $this->CI->config->item('e_slo_url');
        $service_id = $this->CI->config->item('e_serviceid');
        $redirect_uri = $this->CI->config->item('redirectUrl');
        $iss = 'ePramaan';
        $logoutRequestId = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        $input = $service_id . $epramaan_data->session_id . $iss . $logoutRequestId . $epramaan_data->sub . $redirect_uri;

        //apiHmac
        $apiHmac = hash_hmac('sha256', $input, $logoutRequestId, true);
        $apiHmac = base64_encode($apiHmac);

        $json = array(
            "clientId" => $service_id,
            "sessionId" => $epramaan_data->session_id,
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

    public function epramaan_logout()
    {
        $epramaan_data = $this->CI->session->userdata('epramaan_data');
        $epramaanRequestTokenUrl = 'https://epramaan.meripehchaan.gov.in/openid/jwt/processOIDCSLORequest.do';
        $service_id = $this->CI->config->item('e_serviceid');
        $redirect_uri = $this->CI->config->item('e_slo_url');
        $iss = 'ePramaan';
        $logoutRequestId = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));


        echo $input = $service_id . $epramaan_data->session_id . $iss . $logoutRequestId . $epramaan_data->sub . $redirect_uri;
        echo '<br>';
        //apiHmac
        $apiHmac = hash_hmac('sha256', $input, $logoutRequestId, true);
        echo $apiHmac = base64_encode($apiHmac);
        echo '<br>';
        $json = array(
            "clientId" => $service_id,
            "sessionId" => $epramaan_data->session_id,
            "sessionId" => $epramaan_data->session_id,
            "hmac" => $apiHmac,
            "iss" => $iss,
            "logoutRequestId" => $logoutRequestId,
            "sub" => $epramaan_data->sub,
            "redirectUrl" => $redirect_uri,
            "customParameter" => "custom"
        );
        echo $json_data = json_encode($json);
        echo '<br>';
        // die();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $epramaanRequestTokenUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>  $json_data
        ));
        $response = curl_exec($curl);

        if (curl_error($curl)) {
            echo curl_error($curl);
        }
        curl_close($curl);
        var_dump($response);
        pre($this->CI->session->userdata('epramaan_data'));
    }
}
