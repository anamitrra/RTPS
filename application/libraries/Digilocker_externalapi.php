<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 *@author Abhijit Pathak
 */
class Digilocker_externalapi
{

    /**
     * Constructor
     */
    public function __construct()
    {
        log_message('debug', 'Digilocker_externalapi Class Initialized');
    }
    /**
     * send
     *
     * @param mixed $number
     * @param mixed $message_body
     * @return void
     */
    public function process_api($url, $postFields, $doctype)
    {
        switch ($doctype) {
            case 'RMCER':
                $key = 'mrg_secret_key';
                $headers = array(
                    'Content-Type:application/json',
                    //'Authorization: Basic '. base64_encode("user:password") // place your auth details here
                );
                $payload = array(
                    'id' => 1,
                );

                $url = curl_init($url . $postFields);
                curl_setopt($url, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($url, CURLOPT_TIMEOUT, 30);
                curl_setopt($url, CURLOPT_POST, 1);
                curl_setopt($url, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($url, CURLOPT_RETURNTRANSFER, TRUE);
                $return = curl_exec($url);
                if (curl_errno($url) || curl_getinfo($url)['http_code'] >= 400) {
                    // pre('download_cert_error');
                    $response = array();
                } elseif ($return != 'Failure') {
                    $data = json_decode($return, true);
                    $certi = base64_decode($data['certi']);
                    $hash =  base64_decode($data['hash']);
                    if (empty($certi)) {
                        $response = array();
                    }

                    $options = 0;
                    // Non-NULL Initialization Vector for decryption
                    $decryption_iv = '1234567891011121';
                    $ciphering = "AES-128-CTR";
                    // Use openssl_decrypt() function to decrypt the data
                    $decryption = openssl_decrypt(
                        $certi,
                        $ciphering,
                        $key,
                        $options,
                        $decryption_iv
                    );
                    $auth = sha1($decryption, $key);

                    $valid = strcmp($hash, $auth);

                    if ($valid == 0) {
                        $response = array("certificate" => base64_encode($decryption));
                    } else {
                        // Invalid cert checksum
                        $response = array();
                    }
                }
                $json_data = json_encode($response);
                return $json_data;
                break;
            case 'RECER':
                $curl_handle = curl_init($url);
                curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl_handle, CURLOPT_POST, 1);
                curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $postFields);
                curl_setopt($curl_handle, CURLOPT_TIMEOUT, 600); //timeout in seconds
                $res =  curl_exec($curl_handle);

                if (curl_errno($curl_handle)) {
                    $res =  curl_error($curl_handle);
                }
                curl_close($curl_handle);

                $data = json_decode($res);
                if ($data->responsetType == 2) {
                    $response = array("certificate" => $data->data);
                } else {
                    $response = array();
                }
                // return $data;

                // $response = array($res);

                $json_data = json_encode($response);
                return $json_data;
                break;
            case 'STDOC':
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $postFields);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                $curl_response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $curl_response = curl_error($curl);
                }
                curl_close($curl);
                $data = json_decode($curl_response);
                if ($data->responsetType == 2) {
                    $response = array("certificate" => $data->data[0]);
                } else {
                    $response = array();
                }
                $json_data = json_encode($response);
                return $json_data;
                break;
            case 'ROR1B':
                $payload = $postFields.'&doc_type=ROR';
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                $curl_response = curl_exec($curl);
                if (curl_errno($curl)) {
                    $curl_response = curl_error($curl);
                }
                curl_close($curl);
                $data = json_decode($curl_response);
                if ($data->responsetType == 2) {
                    $response = array("certificate" => $data->data);
                } else {
                    $response = array();
                }
                $json_data = json_encode($response);
                return $json_data;
                break;
            default:
                $json_data = json_encode(array());
                return $json_data;
                break;
        }
    }
}
