<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class Panverify extends External
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('panverification');
    }
    public function verifyPan()
    {

        $pan_id = $_POST['pan']; 
        $first_name = $_POST['fname'];
        $middle_name = $_POST['mname'];
        $last_name = $_POST['lname'];
        $type = $_POST['type'];

        header('Content-Type: application/json');
        $response = $this->callPanVerificationApi($pan_id, $first_name, $middle_name, $last_name, $type);
        echo json_encode($response);
    }

    public function callPanVerificationApi($pan_id, $first_name, $middle_name, $last_name, $type)
    {
        $data = $this->prepareData($pan_id, $first_name, $middle_name, $last_name, $type);

        $payload = json_encode(
            array(
                'serviceId' => "10007",
                'reqTxnId' =>  $data[0],
                'data' => $data[1]
            )
        );
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, "https://department.epramaan.gov.in/other/panverify");
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST,  2);
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payload);

        $result = curl_exec($curl_handle);
        $httpcode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
        curl_close($curl_handle);
        if ($httpcode != 200) {
            return $result;
        }
        return  json_decode($result);
    }

    public function prepareData($pan_id, $first_name, $middle_name, $last_name, $type)
    {
        if ($type == 'ORG') {
            $name = $first_name;
        } else {
            $name = $last_name . " " . $first_name . " " . $middle_name;
        }

        $aes_key = "ef6e2ede-5516-4e2d-be04-06ad32f2b194";
        $id_type = "PAN";
        $password = "DoITA@007";


        //AES encryption
        exec('java -cp C:/xampp/htdocs/api_test/PANEncryption1.9.jar in.cdac.mumbai.EncryptPAN "' . $name . '" "' . $pan_id . '" "' . $id_type . '" "' . $aes_key . '" "' . $password . '" "' . $type . '"', $output);
        return $output;
        // echo $output[0]; //reqtxnId
        //echo $output[1]; //encrypted data
    }
}
