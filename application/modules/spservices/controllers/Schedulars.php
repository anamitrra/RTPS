<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Schedulars extends frontend
{
    private $secret_key = "s786odty6t7x"; //For UAT
    //private $secret_key = "s696onad8s8m"; //For Prod
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


    public function accessLog($applId = null)
    {
        $data = array(
            "app_ref_no" => $applId,
        );
        $dbRows = $this->track_model->get_rows($data);
        if (!empty($dbRows)) {
            pre($dbRows);
        } else {
            pre('No records found against appl. id : ' . $applId);
        } //End of if else
    } //End of track()



    public function response($applId = null)
    {
        $file_name = "DRAFT_ID_REQUEST";
        $json = file_get_contents('php://input');
        $myfile = fopen("storage/docs/" . $file_name . ".txt", "a") or die("Unable to open file!");
        fwrite($myfile, $json);
        fclose($myfile);
    } //End of track()

    public function retrive_edist_applications()
    {
        // pre('From CMD');
        // php .\cli.php  spservices/Trackapplication retrive_edist_applications

        $CI = &get_instance();
        // Define query criteria
        $criteria = [
            "service_data.submission_date" => [
                '$gte' => new MongoDB\BSON\UTCDateTime((time() - 40 * 60) * 1000),
                '$lte' => new MongoDB\BSON\UTCDateTime((time() - 10 * 60) * 1000)
            ],
            "form_data.edistrict_ref_no" => [
                '$exists' => true,
                '$ne' => null
            ],
            "processing_history" => [
                '$exists' => true,
            ]
        ];

        // $criteria = [
        //     "service_data.submission_date" => [
        //         '$gte' => new MongoDB\BSON\UTCDateTime(strtotime("2023-03-13T00:00:00.000Z") * 1000),
        //         '$lte' => new MongoDB\BSON\UTCDateTime(strtotime("2023-03-15T00:00:00.000Z") * 1000)
        //     ],
        //     "form_data.edistrict_ref_no" => [
        //                 '$exists' => true,
        //                 '$ne' => null
        //             ],
        //     "processing_history" => [
        //                 '$exists' => true,
        //             ]
        // ];

        $CI->mongo_db->where($criteria);
        $dbrows = $CI->mongo_db->get('sp_applications');
        if (empty($dbrows)) {
            return false;
        }

        $arr_pushed = [];
        foreach ($dbrows as $document) {
            $edistrict_ref_no = $document->form_data->edistrict_ref_no;
            $service_id = $document->service_data->service_id;
            if ($this->check_status($edistrict_ref_no)){
                if($this->reinitiate_application($service_id, $edistrict_ref_no)){
                    edistrict_log_response($edistrict_ref_no, "success");
                    array_push($arr_pushed, $edistrict_ref_no);
                    $data_to_update['form_data.repushed_application']=true;
                    $CI->mongo_db->set($data_to_update);
                    $CI->mongo_db->where(array('form_data.edistrict_ref_no' => $edistrict_ref_no));
                    $CI->mongo_db->update('sp_applications');
                }
            }
        }
        print_r($arr_pushed);
        // Print the list of appl_ref_no values
    }

    //Check eDistrict application status
    private function check_status($applId = null)
    {
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
        if (curl_errno($curl)) {
            echo $error_msg = curl_error($curl);
        }
        curl_close($curl);
        // pre($error_msg );
        // edistrict_log_response($applId, $response);
        if ($response) {
            $response = json_decode($response, true);
            if (isset($response['status']) && $response['status'] == "fail") {
                edistrict_log_response($applId, $response['status']);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Re-push eDistrict Applications
    private function reinitiate_application($service_id = null, $edistrict_ref_no = null)
    {
        
        $applJosn = array(
            'service_id' => $service_id,
            'edist_ack_no' => $edistrict_ref_no
        );
        $json_data = json_encode($applJosn);
        $decodedText = stripslashes(html_entity_decode($json_data));
        $hmac_value = hash_hmac('sha256', $decodedText, $this->secret_key);
        //$url = "https://rtps.assam.gov.in/app_test/spservices/edistrict_api/reinitiate_application";
        //$url = "https://rtps.assam.gov.in/spservices/edistrict_api/reinitiate_application";
        $url = "http://localhost/rtps/spservices/edistrict_api/reinitiate_application";
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
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        edistrict_log_response($edistrict_ref_no, $response);
        if (isset($error_msg)) {
            die("CURL ERROR : " . $error_msg);
        } elseif (!empty($response)) {
            $response = json_decode($response);
            if (isset($response->status) && $response->status == true) {
                return true;
            } else if (isset($response->status) && $response->status == false) {
                return false;
            }
        } else {
            return false;
        }
    }
}//End of reinitiate_applications
