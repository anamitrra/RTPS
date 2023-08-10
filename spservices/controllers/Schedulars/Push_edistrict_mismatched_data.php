<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Push_edistrict_mismatched_data extends frontend
{
    //private $secret_key = "s786odty6t7x"; //For UAT
    private $secret_key = "s696onad8s8m"; //For Prod
    public function __construct()
    {
        parent::__construct();
        $this->load->model('income/track_model');
        $this->load->model('income/income_model');
        $this->load->config('spconfig');
        $this->load->helper("log");
    } //End of __construct()

    public function index($objId = null)
    {
        //pre($this->input->GET('appl_ref_no'));
        $dbRow = $this->income_model->get_row(["service_data.appl_ref_no" => $this->input->GET('appl_ref_no')]);
        if (!empty($dbRow)) {
            pre($dbRow);
        } else {
            pre('No records found against object id : ' . $objId);
        } //End of if else
    } //End of index()


    public function retrive_edist_applications()
    {
        // pre('From CMD');
        // php .\cli.php  spservices/Trackapplication retrive_edist_applications
        $CI = &get_instance();
        $collection = 'temp_edistrict_mismatched_data';
        $operations = array(
            array(
                '$match' => array(
                    "sl_no" => array(
                        '$lte' => 870
                    ),
                    "repushed_application" => array('$exists' => true),
                    "new_edistrict_ref_no" => "SSDG/ED/CASTE/0",
                    "sl_no" => 8
                )
            ),
            array(
                '$project' => [
                    'edistrict_ref_no' => 1
                ]
            )
        );

        $dbrows = $CI->mongo_db->aggregate($collection, $operations);
        if (empty($dbrows)) {
            return false;
        }
        $total_appl = sizeof((array) $dbrows);
        // pre($total_appl);
        echo "Total data to check: {$total_appl}" . PHP_EOL;
        foreach ($dbrows as $document) {
            $edistrict_ref_no = $document->edistrict_ref_no;
            $service_id = $this->extractServiceID($edistrict_ref_no);
            echo "Working on {$edistrict_ref_no}, {$service_id}" . PHP_EOL;

            $this->reinitiate_application($service_id, $edistrict_ref_no);

            $total_appl--;
            echo "Total remainings: {$total_appl}" . PHP_EOL;
            echo PHP_EOL;
        }
    }


    //Re-push eDistrict Applications
    private function reinitiate_application($service_id = null, $edistrict_ref_no = null)
    {
        $CI = &get_instance();
        echo "Trying to re-push: {$edistrict_ref_no}, {$service_id}" . PHP_EOL;
        $data_to_update = array();
        $applJosn = array(
            'service_id' => $service_id,
            'edist_ack_no' => $edistrict_ref_no
        );
        $json_data = json_encode($applJosn);
        $decodedText = stripslashes(html_entity_decode($json_data));
        $hmac_value = hash_hmac('sha256', $decodedText, $this->secret_key);
        // $url = "https://rtps.assam.gov.in/app_test/spservices/edistrict_api/reinitiate_application";
        $url = "https://sewasetu.assam.gov.in/spservices/edistrict_api/manually_initiate";
        //$url = "http://localhost/rtps/spservices/edistrict_api/reinitiate_application";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'applJson' => $decodedText,
            'hmac' => urlencode($hmac_value),
        )));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($curl);
        pre($response);

        $res_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        echo "{$edistrict_ref_no} status received: {$res_status}" . PHP_EOL;

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);

            echo "CURL Error: {$error_msg}" . PHP_EOL;
        }
        curl_close($curl);
        edistrict_mi_log_response($edistrict_ref_no, $response);
        if (isset($error_msg)) {
            //die("CURL ERROR : " . $error_msg);
        } elseif (!empty($response)) {
            $response = json_decode($response);
            if (isset($response->status) && $response->status == true) {
                echo "Successfully re-pushed: {$edistrict_ref_no}" . PHP_EOL;
                $data_to_update['new_edistrict_ref_no'] = $response->edistrict_ref_no;
                $data_to_update['remark'] = $response->message;
                $data_to_update['repushed_application'] = true;
                edistrict_mi_log_response($edistrict_ref_no, "success");

            } else if (isset($response->status) && $response->status == false) {
                echo "Failed to re-push: {$edistrict_ref_no}" . PHP_EOL;
                $data_to_update['remark'] = $response->message;
                $data_to_update['repushed_application'] = false;  

            }
        } else {
            $data_to_update['remark'] = 'INVALID RESPONSE';
            $data_to_update['repushed_application'] = false;
        }
        $CI->mongo_db->set($data_to_update);
        $CI->mongo_db->where(array('edistrict_ref_no' => $edistrict_ref_no));
        $CI->mongo_db->update('temp_edistrict_mismatched_data');
    }

    private function extractServiceID($refNo)
    {
        $parts = explode('/', $refNo);
        $serviceID = '';

        foreach ($parts as $part) {
            if (strpos($part, 'SCTZN') !== false || strpos($part, 'SCC') !== false || strpos($part, 'CASTE') !== false || strpos($part, 'NOK') !== false || strpos($part, 'INC') !== false || strpos($part, 'PDBR') !== false || strpos($part, 'PDDR') !== false | strpos($part, 'BAK') !== false || strpos($part, 'PRC') !== false) {
                $serviceID = $part;
                break;
            }
        }

        return $serviceID;
    }
}//End of reinitiate_applications
