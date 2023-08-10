<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class Pan extends External
{
    public function __construct()
    {
        parent::__construct();
    }

    function decode()
    {
        //https://rtpsprod.assam.statedatacenter.in/spservices/commonapplication/apply/aHR0cHM6Ly9ydHBzcHJvZC5hc3NhbS5zdGF0ZWRhdGFjZW50ZXIuaW4vc2VydmljZXMvZGlyZWN0QXBwbHkuZG8_c2VydmljZUlkPTE2NTA
        $data = 'aHR0cHM6Ly9ydHBzcHJvZC5hc3NhbS5zdGF0ZWRhdGFjZW50ZXIuaW4vc2VydmljZXMvZGlyZWN0QXBwbHkuZG8_c2VydmljZUlkPTE2NTA';
        echo base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
    
    function sha256_utf8($string)
    {
        $hash = hash('sha256', $string, false);
        return $hash;
    }

    public function index()
    {
        $service_id = "10007";
        echo 'AES key:- <br>' . $aes_key = "ef6e2ede-5516-4e2d-be04-06ad32f2b194";
        echo '<br>----------------<br>';

        echo 'AES key after Hash:- <br>' . $hash_key = $this->sha256_utf8($aes_key);
        echo '<br>----------------<br>';
        $password = "DoITA@007";
        $pantype = "IND";
        echo 'Txn request:- <br>' . $txn_request = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
        echo '<br>-----------------<br>';
        $data = array(
            "name" => "Pathak Abhijit",
            "idvalue" => "CIJPP6232Q",
            "reqtxn" => $txn_request,
            "proof" => "PAN",
            "aesKey" => $aes_key,
            "password" => $password,
            "pantype" => $pantype
        );

        echo 'JSON input data:- <br>' . $jsonData = json_encode($data);
        echo '<br>-----------------------------<br>';
        echo 'Encrypted JSON data:- <br>' . $encryptedData = $this->encrypt($jsonData, $hash_key);
        echo '<br>------------------------------</br>';
        $finaldata = array(
            "serviceId" => $service_id,
            "reqTxnId" => $txn_request,
            "data" => $encryptedData
        );
        echo 'RAW JSON data for Payload:- <br>' . $post_data = json_encode($finaldata);
        echo '<br>---------------------------------</br>';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://department.epramaan.gov.in/other/panverify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        pre($response);
        if (curl_error($curl)) {
            pre(curl_error($curl));
        }
        curl_close($curl);
    }





    function encryption1($json,  $key)
    {

        $iv = openssl_random_pseudo_bytes(16); // Generate a random initialization vector (IV)

        $encrypted_data = openssl_encrypt($json, 'AES-256-ECB', $key, OPENSSL_RAW_DATA);
        return $encoded_data = base64_encode($encrypted_data);
    }
    // AES/ECB/PKCS5Padding with SHA-256 , UTF-8 forma
    public function encrypt($data,  $key)
    {
        $paddedData = $this->pad($data);
        $encrypted = openssl_encrypt($paddedData, 'AES-256-ECB', $key, OPENSSL_RAW_DATA);
        return bin2hex($encrypted);
    }

    private function pad($text)
    {
        $blocksize = 16;
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    // function aes_ecb_encrypt_sha256($data, $key)
    // {
    //     $iv = openssl_random_pseudo_bytes(16);
    //     $ciphertext = openssl_encrypt($data, 'AES-128-ECB', $key, OPENSSL_RAW_DATA, $iv);
    //     return base64_encode($iv . $ciphertext);
    // }

    // function encryptJSON($json, $key)
    // {
    //     // Hash the key using SHA-256
    //     $hashedKey = hash('sha256', $key, true);

    //     // Encrypt the JSON using AES/ECB/PKCS5Padding
    //     $encryptedData = openssl_encrypt($json, 'AES-256-ECB', $hashedKey, OPENSSL_RAW_DATA);

    //     // Base64 encode the encrypted data
    //     $encodedData = base64_encode($encryptedData);

    //     return $encodedData;
    // }
}
