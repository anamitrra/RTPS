<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Script_dayend extends frontend
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

        // $criteria = [
        //     "service_data.submission_date" => [
        //         '$type' => "date",
        //         '$gte' => new MongoDB\BSON\UTCDateTime((time() - 24 * 60 * 60) * 1000),
        //     ],
        //     "form_data.edistrict_ref_no" => [
        //         '$exists' => true,
        //         '$ne' => null
        //     ],
        //     "form_data.landing_status" => [
        //         '$exists' => true,
        //         '$eq' => false
        //     ],
        //     "form_data.repushed_application" => [
        //         '$exists' => true,
        //         '$eq' => true
        //     ],
        //     "processing_history" => [
        //         '$exists' => true,
        //     ]
        // ];

        $collection = 'sp_applications';
        $operations = array(
            array(
                '$match' => array(
                    "service_data.appl_status" => array('$in' => ["submitted"]),
                    "service_data.submission_date" => array(
                        '$type' => "date",
                        '$gte' => new MongoDB\BSON\UTCDateTime((time() - 24 * 60 * 60) * 1000),
                        '$lte' => new MongoDB\BSON\UTCDateTime((time() - 3 * 60 * 60) * 1000)
                    ),
                    "form_data.edistrict_ref_no" => array('$exists' => true, '$ne' => null),
                    "form_data.landing_status" => array('$exists' => true, '$eq' => false),
                    "form_data.repushed_application" => array('$exists' => true, '$eq' => true),
                    "processing_history" => array('$exists' => true),
                )
            ),
            array(
                '$project' => [
                    'service_data.service_id' => 1,
                    'form_data.edistrict_ref_no' => 1
                ]
            )
        );

        $dbrows = $CI->mongo_db->aggregate($collection, $operations);
        if (empty($dbrows)) {
            return false;
        }
        $total_appl = sizeof((array) $dbrows);

        echo "Total data to check: {$total_appl}" . PHP_EOL;

        foreach ($dbrows as $document) {
            $edistrict_ref_no = $document->form_data->edistrict_ref_no;
            $service_id = $document->service_data->service_id;

            echo "Working on {$edistrict_ref_no}, {$service_id}" . PHP_EOL;

            if ($this->check_status($edistrict_ref_no)) {
                if ($this->reinitiate_application($service_id, $edistrict_ref_no)) {
                    edistrict_mi_log_response($edistrict_ref_no, "success");
                    // array_push($arr_pushed, $edistrict_ref_no);
                    $data_to_update['form_data.landing_status'] = false;
                    $data_to_update['form_data.repushed_application'] = true;
                } else {
                    $data_to_update['form_data.landing_status'] = false;
                    $data_to_update['form_data.repushed_application'] = false;
                }
            } else {
                $data_to_update['form_data.landing_status'] = true;
            }
            $CI->mongo_db->set($data_to_update);
            $CI->mongo_db->where(array('form_data.edistrict_ref_no' => $edistrict_ref_no));
            $CI->mongo_db->update('sp_applications');

            $total_appl--;
            echo "Total remainings: {$total_appl}" . PHP_EOL;
            echo PHP_EOL;
        }
        // print_r($arr_pushed);
        // Print the list of appl_ref_no values
    }

    //Check eDistrict application status
    private function check_status($applId = null)
    {
        echo "Checking appl status: {$applId}" . PHP_EOL;
        $CI = &get_instance();
        $url = $CI->config->item('track_status_url');
        $postdata = array(
            'edist_ack_no' => $applId
        );
        $json_obj = json_encode($postdata);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json_obj);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $response = curl_exec($curl);

        $res_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        echo "{$applId} status received: {$res_status}" . PHP_EOL;

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);

            echo "CURL Error: {$error_msg}" . PHP_EOL;
        }
        curl_close($curl);
        // pre($error_msg );
        // edistrict_mi_log_response($applId, $response);
        if ($response) {
            $response = json_decode($response, true);
            if (isset($response['status']) && $response['status'] == "fail") {
                edistrict_mi_log_response($applId, $response['status']);
                echo "Edistrict API response: FAIL" . PHP_EOL;
                return true;
            } else if (isset($response['status']) && $response['status'] == "Unable to check") {
                edistrict_mi_log_response($applId, $response['status']);
                echo "Edistrict API response: ALREADY_LANDED" . PHP_EOL;
                return false;
            } else { 
                echo "Edistrict API response: ALREADY_LANDED" . PHP_EOL;
                return false;
            }
        } else {
            return false;
        }
    }

    //Re-push eDistrict Applications
    private function reinitiate_application($service_id = null, $edistrict_ref_no = null)
    {
        echo "Trying to re-push: {$edistrict_ref_no}, {$service_id}" . PHP_EOL;

        $applJosn = array(
            'service_id' => $service_id,
            'edist_ack_no' => $edistrict_ref_no
        );
        $json_data = json_encode($applJosn);
        $decodedText = stripslashes(html_entity_decode($json_data));
        $hmac_value = hash_hmac('sha256', $decodedText, $this->secret_key);
        // $url = "https://rtps.assam.gov.in/app_test/spservices/edistrict_api/reinitiate_application";
        $url = "https://rtps.assam.gov.in/spservices/edistrict_api/manually_initiate";
        //$url = "http://localhost/rtps/spservices/edistrict_api/reinitiate_application";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'applJson' => $decodedText,
            'hmac' => urlencode($hmac_value),
        )));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($curl);
        // pre($response);

        $res_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        echo "{$edistrict_ref_no} status received: {$res_status}" . PHP_EOL;



        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);

            echo "CURL Error: {$error_msg}" . PHP_EOL;
        }
        curl_close($curl);
        edistrict_mi_log_response($edistrict_ref_no, $response);
        if (isset($error_msg)) {
            die("CURL ERROR : " . $error_msg);
        } elseif (!empty($response)) {
            $response = json_decode($response);
            if (isset($response->status) && $response->status == true) {

                echo "Successfully re-pushed: {$edistrict_ref_no}" . PHP_EOL;
                return true;
            } else if (isset($response->status) && $response->status == false) {

                echo "Failed to re-push: {$edistrict_ref_no}" . PHP_EOL;

                return false;
            }
        } else {
            return false;
        }
    }
}//End of reinitiate_applications
