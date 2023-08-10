<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\UTCDateTime;

class Test extends Frontend
{
    public function download_cert()
    {
        $ref = 'RTPS-MRG/2021/02397';

        $key = 'mrg_secret_key';

        $headers = array(
            'Content-Type:application/json',
            //'Authorization: Basic '. base64_encode("user:password") // place your auth details here
        );
        $payload = array(
            'id' => 1,
        );

        $process = curl_init("http://10.177.0.33:800/rtps_all_api/mrgapi.php?application_ref_no=$ref"); //your API url
        // $process = curl_init("http://localhost/NIC/rtps_all_api/mrgapi.php?application_ref_no=$ref"); //your API url
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($process);

        if (curl_errno($process) || curl_getinfo($process)['http_code'] >= 400) {
            $this->session->set_flashdata('download_cert_error', '400');
            redirect(base_url('site/marriage/index'));
        } elseif ($return != 'Failure') {

            $data = json_decode($return, true);
            $certi = base64_decode($data['certi']);
            $hash =  base64_decode($data['hash']);

            if (empty($certi)) {
                echo 'empty';
                // $this->session->set_flashdata('download_cert_error', 'empty');
                // redirect(base_url('site/marriage/index'));
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

            //var_dump($decryption);die;

            $auth = sha1($decryption, $key);

            $valid = strcmp($hash, $auth);

            if ($valid == 0) {

                //finally print your API response
                // echo '<PRE>';
                // header('Content-Type: application/pdf');
                // echo $decryption;
                // echo '</PRE>';

                // Get the mimetype
                $finfo = finfo_open();
                $mime_type = finfo_buffer($finfo, $decryption, FILEINFO_MIME_TYPE);
                finfo_close($finfo);
                $ext = (get_file_extension($mime_type) == 'N/A') ? '' : get_file_extension($mime_type);

                // redirect output to client browser
                header("Content-type: {$mime_type}");
                header('Content-Disposition: attachment;filename="marriage_cert_' . $ref . ".{$ext}");
                header('Cache-Control: max-age=0');
                echo $decryption;
            } else {
                // Invalid cert checksum

                echo 'Access Denied';
            }
        } elseif ($return == 'Failure') {
            echo 'fail';
            // $this->session->set_flashdata('download_cert_error', $this->settings->messages[4]->{"$this->lang"});
            // redirect(base_url('site/marriage/index'));
        } else {
            // $this->session->set_flashdata('download_cert_error', $this->settings->messages[3]->{"$this->lang"});
            // redirect(base_url('site/marriage/index'));
        }
    }
}