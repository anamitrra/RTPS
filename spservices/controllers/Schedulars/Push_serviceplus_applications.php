<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Push_serviceplus_applications extends frontend
{
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
        $collection = 'edistrict_push_splus_data_new';
        $operations = array(
            array(
                '$match' => array(
                    "sp_appl_status" => array('$exists' => true, '$eq' => 'F'),
                    "edist_appl_status" => array('$exists' => true, '$eq' => 'D'),
                    "repushed_status" => array('$exists' => false),
                    "rtps_ref_no_edist" => array('$in' => array(
                        "RTPS-SCTZN/2022/04879",
                        "RTPS-SCTZN/2022/05586",
                        "RTPS-SCTZN/2022/05482",
                        "RTPS-SCTZN/2022/05486",
                        "RTPS-SCTZN/2022/05640",
                        "RTPS-SCTZN/2022/05543",
                        "RTPS-SCTZN/2022/05384",
                        "RTPS-SCTZN/2022/05645",
                        "RTPS-SCTZN/2022/05488",
                        "RTPS-SCTZN/2021/00082"
                    ))
                )
            ),
            array(
                '$project' => [
                    'appl_id' => 1,
                    'rtps_ref_no_edist' => 1,
                    'edistrict_ref_no' => 1,
                    'edist_remark' => 1
                ]
            )
        );
        //pre($operations);
        $dbrows = $CI->mongo_db->aggregate($collection, $operations);
        if (empty($dbrows)) {
            return false;
        }
        $total_appl = sizeof((array) $dbrows);
        //  pre($total_appl);

        echo "Total data to check: {$total_appl}" . PHP_EOL;

        foreach ($dbrows as $document) {
            $rtps_ref_no = $document->rtps_ref_no_edist;
            $edistrict_ref_no = $document->edistrict_ref_no;
            //pre($edistrict_ref_no);
            $service_id = $this->extractServiceID($rtps_ref_no);
            $appl_id = $document->appl_id;
            $data_to_update = array();
            $certificate ='';
            echo "Working on {$rtps_ref_no}, {$service_id}" . PHP_EOL;
            $certificate=$this->check_status($edistrict_ref_no);
            if ($certificate) {
                if ($this->reinitiate_application($service_id, $appl_id, $certificate)) {
                    // array_push($arr_pushed, $edistrict_ref_no);
                    $data_to_update['repushed_status'] = true;
                } else {
                    $data_to_update['repushed_status'] = false;
                }
            } else {
                 $data_to_update['repushed_status'] = true;
            }
            $CI->mongo_db->set($data_to_update);
            $CI->mongo_db->where(array('appl_id' => $appl_id));
            $CI->mongo_db->update('edistrict_push_splus_data_new');

            $total_appl--;
            echo "Total remainings: {$total_appl}" . PHP_EOL;
            echo PHP_EOL;
            //break;
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
        //curl_setopt($curl, CURLOPT_TIMEOUT, 5);
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
        // pre($response );
        // edistrict_mi_log_response($applId, $response);
        if ($response) {
            $response = json_decode($response, true);
            if (isset($response['status']) && $response['status'] == "fail") {
                edistrict_mi_log_response($applId, $response['status']);
                echo "Edistrict API response: FAIL" . PHP_EOL;
                return false;
            } else if (isset($response['status']) && $response['status'] == "Unable to check") {
                edistrict_mi_log_response($applId, $response['status']);
                echo "Edistrict API response: ALREADY_LANDED" . PHP_EOL;
                return false;
            } else if(isset($response['certificate']) && $response['current_status']=="D"){

                return $response['certificate'];
            }
        } else {
            return false;
        }
    }

    //Re-push eDistrict Applications
    private function reinitiate_application($service_id = null, $appl_id = null, $certificate = null)
    {
        echo "Trying to re-push: {$appl_id}, {$service_id}" . PHP_EOL;
        // pre($certificate);
        if ($service_id == "BAKCL") {
            $taskId = "20470";
            $wsId = "177";
            $key = "A2DJxr8Jwc";
        } else if ($service_id == "SCTZN") {
            $taskId = "19898";
            $wsId = "139";
            $key = "rdyGt4q4pT";
        } else if ($service_id == "NOKIN") {
            $taskId = "19842";
            $wsId = "134";
            $key = "hAgFbsyQta";
        } else if ($service_id == "PDBR") {
            $taskId = "20662";
            $wsId = "232";
            $key = "nwEa29Mc6j";
        } else if ($service_id == "PDDR") {
            $taskId = "20669";
            $wsId = "234";
            $key = "xz9c8NaxEr";
        }
        $encryptKey = hash('sha256', $appl_id . $taskId . $wsId . $key);
        $fields = array(
            'status' => 'D',
            'remark' => 'Approved',
            'certificate' => $certificate,
        );
// pre($fields);
        $json_data = json_encode($fields);

        $url = 'https://sewasetu.assam.gov.in/services/callback.do';
        $curl = curl_init($url);
        //curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // Disable SSL verification

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query(array(
            'applId' => urlencode($appl_id),
            'taskId' => urlencode($taskId),
            'wsId' => urlencode($wsId),
            'encryptKey' => urlencode($encryptKey),
            'wsResponse' => $json_data
        )));
        $response = curl_exec($curl);
        $res_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        echo "status received: {$res_status}" . PHP_EOL;
        // pre($response);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            echo "CURL Error: {$error_msg}" . PHP_EOL;
        }
        curl_close($curl);

        //edistrict_mi_log_response($appl_id, $response);
        if (isset($error_msg)) {
            //die("CURL ERROR : " . $error_msg);
        } elseif (!empty($response)) {
            $response = json_decode($response);
            if (isset($response->status) && $response->status == "true") {
                // var_dump($response);
                echo PHP_EOL;  
                echo "Successfully updated: {$appl_id}" . PHP_EOL;
                return true;
            } else if (isset($response->status) && $response->status == "false") {

                echo "Failed to update: {$appl_id}" . PHP_EOL;
                return false;
            } else{
                echo "Failed to update: {$response->status}" . PHP_EOL;
                return false;
            }
            
        } else {
            return false;
        }
    }


    private function extractServiceID($appl_ref_no) {
        $pattern = '/RTPS-([A-Z]+)\/\d{4}\/\d+/'; // Regular expression pattern
    
        if (preg_match($pattern, $appl_ref_no, $matches)) {
            // $matches[0] will contain the entire matched pattern
            // $matches[1] will contain the service ID captured by the first group in parentheses
            return $matches[1];
        } else {
            return "Service ID not found";
        }
    }

}//End of reinitiate_applications
