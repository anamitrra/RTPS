<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/Kolkata');

class Checkrefno extends frontend {

    private $urls = [];

    public function __construct() {
        parent::__construct();
        $this->urls[] = (object) ['url' => 'https://sewasetu.assam.gov.in/spservices/trackapplicationstatus/byrefno', 'secret' => 'rtpsapi#!@'];
        $this->urls[] = (object) ['url' => 'https://sewasetu.assam.gov.in/iservices/misapi/get_edistric_app_status', 'secret' => 'rtpsapi#!@'];
        $this->urls[] = (object) ['url' => 'https://sewasetu.assam.gov.in/iservices/misapi/update_app_statusv2', 'secret' => 'rtpsapi#!@'];
        $this->urls[] = (object) ['url' => 'https://sewasetu.assam.gov.in/iservices/misapi/get_apdcl_app_status', 'secret' => 'rtpsapi#!@'];
        $this->urls[] = (object) ['url' => 'https://sewasetu.assam.gov.in/tools/rtps_id_labels/src/api/external_apis.php/get_sp_data', 'secret' => 'rtpsapi#!@', 'header_token' => '|0VW?z,-w2w"6;{b8v}K6A5+Fdf@l-'];
        $this->urls[] = (object) ['url' => 'https://sewasetu.assam.gov.in/tools/rtps_id_labels/src/api/external_apis.php/get_mis_data', 'secret' => 'rtpsapi#!@', 'header_token' => '|0VW?z,-w2w"6;{b8v}K6A5+Fdf@l-'];
    }//End of __construct()

    public function index() {
        $ref_no = $this->input->post("ref_no", true);
        $row = $this->get_latest_app_status($ref_no);
        if (!empty($row['status'])) {
            $resArr = array("status" => true, "message" => "Record successfully matched against your ref. no. " . $ref_no);
            $headerStatus = 200;
        } else {
            $resArr = array("status" => false, "message" => "No record found for " . $ref_no);
            $headerStatus = 200;
        } //End of if else

        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header($headerStatus)
                        ->set_output(json_encode($resArr));
    }//End of index()

    public function get_latest_app_status($ref_no) {
        $multi_handle = curl_multi_init();
        $handles = [];
        foreach ($this->urls as $key => $value) {
            $handle = curl_init();
            curl_setopt_array($handle, array(
                CURLOPT_URL => $value->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT_MS => 7000,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false, // disable SSL certificate verification
                CURLOPT_SSL_VERIFYHOST => false, // disable hostname verification
                CURLOPT_POSTFIELDS => json_encode(array(
                    'app_ref_no' => $ref_no,
                    'secret' => $value->secret,
                )),
                CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
            ));
            if (!empty($value->header_token)) {
                curl_setopt($handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer {$value->header_token}"));
            }
            $handles[] = $handle;
            curl_multi_add_handle($multi_handle, $handle);
        }

        $running = null;
        do {
            curl_multi_exec($multi_handle, $running);
        } while ($running);

        if (curl_multi_errno($multi_handle)) {
            return [
                'status' => false,
                'message' => 'No records found',
            ];
        }
        $responses = [];
        foreach ($handles as $handle) {
            $response = curl_multi_getcontent($handle);
            if (!curl_errno($handle) && curl_getinfo($handle, CURLINFO_HTTP_CODE) <= 300) {
                $responses[] = json_decode($response, true);
            }
        }

        foreach ($handles as $handle) {
            curl_multi_remove_handle($multi_handle, $handle);
            curl_close($handle);
        }
        curl_multi_close($multi_handle);
        foreach ($responses as $key => $value) {
            if (!empty($value['status'])) {
                return $value;
            }
        }
        return [
            'status' => false,
            'message' => 'No records found'
        ];
    }//End of get_latest_app_status
}//End of Checkrefno