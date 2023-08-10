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


class Elogin extends Frontend
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('epramaan');
        $this->load->config('digilocker/dlconfig');
    }
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

            $data = array("pageTitle" => "Login");
            $cap = generate_n_store_captcha();
            $data = [
                'cap' => $cap
            ];

            $this->load->view('includes/frontend/header');
            $this->load->view('elogin', $data);
            $this->load->view('includes/frontend/footer');
        } else {
            if (!empty($this->session->userdata('role'))) {
                redirect('iservices/admin/my-transactions');
            } else {
                redirect('iservices/transactions');
            }
        }
    }

    public function response()
    {
        setcookie("decryptedtoken_c", "", time() - 3600, "/");
        if (isset($_GET["code"])) {

            $code = htmlspecialchars($_GET["code"]);
            //session_start();
            $verifier = $_COOKIE["verifier_c"];

            $nonce = $_COOKIE["nonce_c"];
            $epramaanRequestTokenUrl = 'https://epramaan.meripehchaan.gov.in/openid/jwt/processJwtTokenRequest.do';
            $grant_type = 'authorization_code';
            $scope = 'openid';
            $service_id = $this->config->item('e_serviceid');
            $redirect_uri = $this->config->item('e_redirectUri');

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
            echo $response;
            $this->decrypt_token($response);
        } else {
            echo 'something went wrong.';
        }
    }

    //---------processing token-decrypt--------------
    function decrypt_token($encrypted_hash)
    {
        // echo $encrypted_hash;
        echo '<br>-----------------------<br>';
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
            echo 'decrpt_tkn :- ' . $decryptedtoken;
            // setcookie("decryptedtoken_c", "$decryptedtoken", time() + 3600, "/");
            $this->verify($decryptedtoken);
            //var_dump($jwe->getPayload());
        } else {
            // throw new RuntimeException('Error Decrypting JWE');
            pre('Error Decrypting JWE');
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

    public function verify($decryptedtoken)
    {
        echo '<br>-----------------------<br>';

        // $decryptedtoken1 = 'eyJhbGciOiJSUzI1NiJ9.eyJ1c2VyX3Nzb19pZCI6IkRMLTg1ZWMyODJmLWJlOGUtMTFlOS1iZGVkLTk0NTdhNTY0NTA2OCIsInN1YiI6IjkxMDEzNzk0NjMiLCJiaXJ0aGRhdGUiOiIwNVwvMDFcLzE5OTIiLCJkcml2aW5nX2xpY2VuY2UiOiIiLCJnZW5kZXIiOiJNIiwiaXNzIjoiaHR0cHM6XC9cL2RpZ2lsb2NrZXIubWVyaXBlaGNoYWFuLmdvdi5pbiIsInByZWZlcnJlZF91c2VybmFtZSI6IjkxMDEzNzk0NjMiLCJtYXNrZWRfYWFkaGFhciI6IiIsImdpdmVuX25hbWUiOiJBYmhpaml0IFBhdGhhayIsImF1ZCI6IjU5QkY2QTg2IiwicGFuX251bWJlciI6IiIsImF1dGhfdGltZSI6MS42NzUyMzY1MjZFOSwicGhvbmVfbnVtYmVyIjoiOTEwMTM3OTQ2MyIsImV4cCI6MTY3NTMyMzEzOSwiaWF0IjoxNjc1MjM2NzM5LCJqdGkiOiJlNWRhNGJlMC05NDk3LTRjMDctODc4Yy01NTg5MzUzMDBjODMiLCJlbWFpbCI6ImFiaGlqaXRwYXRoYWs0QGdtYWlsLmNvbSJ9.PeRQZTrZC-MPIfBvrn-ZObWg78NcoDOr4wHgbl5-yyKYQ6hXmweZLM8oNBG7TYimQsK6y-Y73KXJzvytREz25qPep-6rxC77M2fkWq0Tj_mBkGgjsBbH5chTZHJ01xbJ6mTONDSx0JDByZdaanGkor83hrdBcUhZjDJso7oqlf7icEmSzzZOxNo79ZBQ2x8rjmoQ1vDA09AEH55EK2ZukvEysNx3wGXKbJwJGwUuTAl5A-_y8107CFrxexy1KD4_NVvrjnjb5zO0-NI8_lQeLB1DnJ692iq0h-mIyk8DK61qAsLixPNq4iSqOKZS4ZDA7bvNZycbQsBpkXgyQ1nIMQ';
        // $decryptedtoken1  = $_COOKIE["decryptedtoken_c"];
        $decryptedtoken1  = $decryptedtoken;

        // echo '<br>';
        // echo $decryptedtoken1;
        // echo '<br>';
        // The algorithm manager with the HS256 algorithm.
        $algorithmManager = new AlgorithmManager([
            new RS256(),
        ]);

        // We instantiate our JWS Verifier.
        $jwsVerifier = new JWSVerifier(
            $algorithmManager
        );

        $key = JWKFactory::createFromCertificateFile(
            'D:\epramaanprod2016.cer', // The filename
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
        // pre($jws);
        $payload = $jws->getPayload();
        echo '<br>';
        echo '<br>';
        $data = json_decode($payload);
        // pre($data);

        echo 'Name--' . $data->name;
        echo '<br>';
        echo 'Username--' . $data->username;
        echo '<br>';
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
        // $url = base_url('iservices/elogin/res');
        redirect($url);
    }


    public function res()
    {
        // $this->load->view('elogout');
        pre($this->session->userdata());
        // echo 'ok';
    }
    public function logout()
    {
        $this->epramaan->epramaan_logout();
    }
    public function slo()
    {
        $this->load->view('elogout');
    }

    public function logout_res()
    {
        // pre($_GET['LogoutResponse']);
        if (isset($_GET['LogoutResponse'])) {
            $logout_response = base64_decode($_GET['LogoutResponse']);
            $decrypted_data = json_decode($logout_response);
            if ($decrypted_data->logoutStatus) {
                redirect(base_url('iservices/login/logout'));
            } else {
                echo 'Something went wrong. Please try again.';
            }
        } else {
            echo 'Something went wrong. Please try again.';
        }
    }

    // public function logout_res()
    // {
    //     echo 'logout response page';
    //     exit;
    // }
}
