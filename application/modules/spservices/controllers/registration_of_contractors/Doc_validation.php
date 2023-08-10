<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Doc_validation extends frontend
{
    public function submit_labour_licence()
    {
        $reg_no = $this->input->post("reg_no");

        $this->form_validation->set_rules("reg_no", "Registration Number", "required");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Please enter a registration no.');
            $this->labourLicence();
        }

        $data = array(
            "regno" => $reg_no
        );

        $url = 'https://eodbmis.assam.gov.in/FetchApiLabour/index';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            $resData = array(
                'status' => 0,
                'ret' => "",
                "msg" => "Error fetching data."
            );
        }
        else {
            $resData = array(
                'status' => 1,
                'ret' => $response,
                "msg" => ""
            );
        }
        curl_close($curl);     
        echo json_encode($resData);
    }

    public function submit_caste_cert()
    {
        $caste_cert_no = $this->input->post("caste_cert_no");

        $this->form_validation->set_rules("caste_cert_no", "Certificate Number", "required");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Please enter a Certificate no.');
            //$this->labourLicence();
        }

        $data = array(
            "certificateNo" => $caste_cert_no
        );
        $data = json_encode($data);

        $url = 'http://103.8.249.110:9080/RTPSWebService/getCertificateData?apiKey=hndr5lqiifz0ki3y8iyy';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            $resData = array(
                'status' => 0,
                'ret' => "",
                "msg" => "Error fetching data."
            );
        }
        else {
            $resData = array(
                'status' => 1,
                'ret' => $response,
                "msg" => ""
            );
        }
        curl_close($curl);     
        echo json_encode($resData);
    }

    public function submit_gst_cert()
    {
        $gst_cert_no = $this->input->post("gst_cert_no");

        $this->form_validation->set_rules("gst_cert_no", "GST Number", "required");

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Please enter a GST No.');
            //$this->labourLicence();
        }

        $url = 'https://apisetu.gov.in/gstn/v1/taxpayers/'.$gst_cert_no;
        $headers = [
            'X-APISETU-CLIENTID: in.gov.assam.rtps',
            'X-APISETU-APIKEY: kI8X3npgeNRi9HcrehpWsaa1u9C2M1PK'
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            $resData = array(
                'status' => 0,
                'ret' => "",
                "msg" => "Error fetching data."
            );
        }
        else {
            $resData = array(
                'status' => 1,
                'ret' => $response,
                "msg" => ""
            );
        }
        curl_close($curl);     
        echo json_encode($resData);
    }
}