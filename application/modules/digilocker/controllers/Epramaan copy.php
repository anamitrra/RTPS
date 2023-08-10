<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require FCPATH . 'vendor/autoload.php';

use Jose\Component\Encryption\JWEDecrypterFactory;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Encryption\Algorithm\KeyEncryption\A256KW;

use Jose\Component\Encryption\Algorithm\ContentEncryption\A256GCM;
use Jose\Component\Encryption\Compression\CompressionMethodManager;
use Jose\Component\Encryption\Compression\Deflate;
use Jose\Component\Encryption\JWEBuilder;
use Jose\Component\Core\JWK;
use Jose\Component\Encryption\Serializer\JWESerializerManager;
use Jose\Component\Encryption\Serializer\CompactSerializer;

use Jose\Component\Encryption\JWEDecrypter;

use Jose\Component\Encryption\JWELoader;
use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Jose\Component\Signature\Serializer\CompactSerializer as CS;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Signature\Algorithm\HS256;
use Jose\Component\Signature\JWSLoader;

class Epramaan extends External
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('digilocker_model');
        $this->load->library('epramaan');
        $this->load->config('digilocker/dlconfig');
    }

    public function response()
    {
        if (isset($_GET["code"])) {
            setcookie("decryptedtoken_c", "", time() - 3600, "/");
            $code = htmlspecialchars($_GET["code"]);
            //session_start();
            $verifier = $_COOKIE["verifier_c"];
            $nonce = $_COOKIE["nonce_c"];
            // $epramaanRequestTokenUrl = 'https://epramaan.meripehchaan.gov.in/openid/jwt/processJwtTokenRequest.do';
            $epramaanRequestTokenUrl = $this->config->item('ePrequestTokenUrl');
            $grant_type = 'authorization_code';
            $scope = 'openid';
            // $redirect_uri = 'http://localhost/rtps/digilocker/epramaan/response';
            $redirect_uri = $this->config->item('SSOurl');
            $service_id = $this->config->item('ePserviceId');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $epramaanRequestTokenUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "code":["' . $code . '"], 
                    "grant_type":["' . $grant_type . '"], 
                    "scope":["' . $scope . '"], 
                    "redirect_uri":["' . $redirect_uri . '"], 
                    "request_uri":["' . $epramaanRequestTokenUrl . '"],
                    "code_verifier":["' . $verifier . '"], 
                    "client_id":["' . $service_id . '"]
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                ),
            ));
            $response = curl_exec($curl);
            if (curl_error($curl)) {
                echo curl_error($curl);
            }
            curl_close($curl);
            $this->decrypt_token($response);
        } else {
            echo 'Something went wrong. Please try again.';
        }
    }

    function base64url_encode($data)
    {
        // First of all you should encode $data to Base64 string
        $b64 = base64_encode($data);

        // Convert Base64 to Base64URL by replacing “+” with “-” and “/” with “_”
        $url = strtr($b64, '+/', '-_');

        // Remove padding character from the end of line and return the Base64URL result
        return rtrim($url, '=');
    }

    //---------processing token-decrypt--------------
    function decrypt_token($encrypted_hash)
    {
        // echo 'encrypted token ' . $encrypted_hash;
        // echo '<br>-----------------------------------<br>';
        // The key encryption algorithm manager with the A256KW algorithm.
        $keyEncryptionAlgorithmManager = new AlgorithmManager([
            new A256KW(),
        ]);

        // The content encryption algorithm manager with the A256CBC-HS256 algorithm.
        $contentEncryptionAlgorithmManager = new AlgorithmManager([
            new A256GCM(),
        ]);

        $compressionMethodManager = new CompressionMethodManager([
            new Deflate(),
        ]);

        // Our key.
        $nonce = $_COOKIE["nonce_c"];
        $sha25 = hash('SHA256', $nonce, true);;

        $jwk = new JWK([
            'kty' => 'oct',
            'k' => $this->base64url_encode($sha25),
        ]);
        //decryption
        $jweDecrypter = new JWEDecrypter(
            $keyEncryptionAlgorithmManager,
            $contentEncryptionAlgorithmManager,
            $compressionMethodManager
        );

        // The serializer manager. We only use the JWE Compact Serialization Mode.
        $serializerManager = new JWESerializerManager([
            new CompactSerializer(),
        ]);

        // We try to load the token.
        $jwe = $serializerManager->unserialize($encrypted_hash);

        // We decrypt the token. This method does NOT check the header.
        $success = $jweDecrypter->decryptUsingKey($jwe, $jwk, 0);

        if ($success) {
            $jweLoader = new JWELoader(
                $serializerManager,
                $jweDecrypter,
                null
            );
            $jwe = $jweLoader->loadAndDecryptWithKey($encrypted_hash, $jwk, $recipient);
            $decryptedtoken = $jwe->getPayload();
            // echo 'decrypted token ' . $decryptedtoken;
            // echo '<br>-----------------------------------<br>';
            setcookie("decryptedtoken_c", "$decryptedtoken", time() + 3600, "/");
            $this->verify_token($decryptedtoken);
            //var_dump($jwe->getPayload());
        } else {
            // throw new RuntimeException('Error Decrypting JWE');
            pre('Error Decrypting JWE');
        }
    }

    public function verify_token($decryptedtoken)
    {
        // $decryptedtoken1 = 'eyJhbGciOiJSUzI1NiJ9.eyJzdWIiOiIyOGEwNzU4MS1jNGMxLTQ4YTUtOTI1MC1mMzU1YzdhMTQxOTkiLCJwd2RfYXV0aF9zdGF0dXMiOiJ0cnVlIiwiZ2VuZGVyIjoiTSIsImRpZ2lsb2NrZXJfaWQiOiJhYmhpaml0cGF0aGFrNEBnbWFpbC5jb20iLCJzZXNzaW9uX2lkIjoiMzAzNGJhOTMtMDYwNy00MWMzLThhNWQtN2JjMGM0ZDNkZTk2Iiwic3NvX2lkIjoiMjhhMDc1ODEtYzRjMS00OGE1LTkyNTAtZjM1NWM3YTE0MTk5IiwibG9naW5Nb2RlIjoiQ0lUSVpFTiIsInVuaXF1ZV91c2VyX2lkIjoiMjhhMDc1ODEtYzRjMS00OGE1LTkyNTAtZjM1NWM3YTE0MTk5IiwiZG9iIjoiMDVcLzAxXC8xOTkyIiwibmFtZSI6IkFiaGlqaXQgUGF0aGFrIiwic290cF9hdXRoX3N0YXR1cyI6Im51bGwiLCJleHAiOjE2ODQ2NjI1MTcsIm1vYmlsZV9udW1iZXIiOiI5MTAxMzc5NDYzIiwiaWF0IjoxNjg0NTc2MTE3LCJlbWFpbCI6ImFiaGlqaXRwYXRoYWs0QGdtYWlsLmNvbSIsImp0aSI6IjMwMzRiYTkzLTA2MDctNDFjMy04YTVkLTdiYzBjNGQzZGU5NiIsInVzZXJuYW1lIjoiYWJoaWppdHBhdGhhayJ9.XsnUUl3VIRxNbTifnB2VLnN4B61TWoi9uqw9RDAsnZm_WNQ_S0yhQCX1lGpeIuApBUX_4oMYQxtDaBaNt-tcvahr0ZL2H2LamFisbWng9TZe8rt1d3UlnCpgaql1s4ohwxFuPMIxcCF_WVrgQn_SnzWgQsWwB8Sm39CAy5Jq_CfJe6W8fL6miifr-JgXPqjTR10mFvLoZqZpcLofY-1zl7DhtnWcxFSnbFPhoACFxPQn0qCJt5ieONmkKX_u1YPJk-Ny59xx64UCTwqg09Hrb8LuYSzRfOonZahR64LdfLK5QMeBGowW-6ShuHhb_PMBoUt3he9fGlfPYskFHfgLBw';
        // $decryptedtoken1  = $_COOKIE["decryptedtoken_c"];
        $decryptedtoken1  = $decryptedtoken;
        // The algorithm manager with the HS256 algorithm.
        $algorithmManager = new AlgorithmManager([
            new RS256(),
        ]);

        // We instantiate our JWS Verifier.
        $jwsVerifier = new JWSVerifier(
            $algorithmManager
        );

        $key = JWKFactory::createFromCertificateFile(
            FCPATH . 'storage/epramaanprod2016.cer', // The filename
            [
                'use' => 'sig',         // Additional parameters
            ]
        );

        $serializerManager = new JWSSerializerManager([
            new CS(),
        ]);

        $jws = $serializerManager->unserialize($decryptedtoken1);
        $isVerified = $jwsVerifier->verifyWithKey($jws, $key, 0);
        $jwsLoader = new JWSLoader(
            $serializerManager,
            $jwsVerifier,
            null
        );

        $jws = $jwsLoader->loadAndVerifyWithKey($decryptedtoken1, $key, $signature);
        $payload = $jws->getPayload();
        $data = json_decode($payload);
        if (!empty($this->session->userdata('applyBy'))) {
            $applyBy = $this->session->userdata('applyBy');
            if ($applyBy == 'pfc') {
                // pfc redirect 
                $this->pfc_login_process($data);
            } else {
                $this->citizen_login_process($data);
            }
        }
    }

    public function citizen_login_process($data)
    {
        $checkUserExist = $this->digilocker_model->checkMobileExist($data->mobile_number, $data);
        $sessionArray = array(
            "name" => $checkUserExist->name,
            "email" => $checkUserExist->email,
            "mobile" => $checkUserExist->mobile,
            "userId" => $checkUserExist->_id,
            "isLoggedIn" => TRUE,
        );
        $this->session->set_userdata($sessionArray);
        $this->session->set_userdata("opt_status", true);
        $this->session->set_userdata("epramaan_data", $data);

        if (isset($checkUserExist->digilocker_consent) && ($checkUserExist->digilocker_consent == 1)) {
            if (!empty($this->session->userdata('redirectTo'))) {
                $url = $this->session->userdata('redirectTo');
                $this->session->unset_userdata('redirectTo');
            } else {
                $url = base_url('iservices/transactions');
            }
        } else {
            $url = base_url('digilocker/userConsent');
        }
        redirect($url);
    }

    public function pfc_login_process($data)
    {
        $this->load->model('iservices/admin/roles_model');
        $email = $data->email ?? '';
        $user = $this->digilocker_model->getUserMobileByEmail($email, $data);
        if (empty($user->mobile)) {
            // echo 'not exist';
            $redirect_url = base_url();
        } else {
            if(isset($user->epramaan_res)){
                $this->digilocker_model->updatePfcdata($email, $data);
            }
            $sessionArray = array(
                'userId' => $user->_id,
                'role' => $this->roles_model->get_role_info($user->roleId),
                'image' => (isset($user->photo)) ? base_url($user->photo) : base_url("storage/images/avatar.png"),
                'name' => $user->name,
                'isLoggedIn' => TRUE,
            );

            $this->session->set_userdata($sessionArray);
            $this->session->set_userdata("pfc_epramaan_data", $data);
            if (!empty($this->session->userdata('redirectTo'))) {
                $url = $this->session->userdata('redirectTo');
                if (strpos($url, "basundhara") == true) {
                    $redirect_url = $url;
                } else {
                    if (strpos($url, "admin") == false) {
                        $redirect_url = str_replace("iservices", "iservices/admin", $url);
                    } else {
                        $redirect_url = $url;
                    }
                }
                $this->session->unset_userdata('redirectTo');
            } else {
                $redirect_url = base_url() . 'iservices/admin/dashboard';
            }
        }
        redirect($redirect_url);
    }

    public function logout()
    {
        $this->epramaan->epramaan_logout();
    }
}
