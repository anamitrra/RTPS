<?php
defined("BASEPATH") or exit("No direct script access allowed");

use MongoDB\BSON\UTCDateTime;

if (!function_exists("fetchEdistrictData")) {
    function fetchEdistrictData($app_id)
    {
        if (empty($app_id)) {
            return false;
        }
        $CI = &get_instance();
        $CI->mongo_db->select(array('service_data.service_id', 'service_data.appl_ref_no', 'service_data.appl_status', 'form_data.certificate', 'form_data.applicant_name', 'form_data.mobile','form_data.edistrict_ref_no', 'processing_history'));
        $CI->mongo_db->where(array('form_data.edistrict_ref_no' => $app_id));
        $dbrow = $CI->mongo_db->find_one('sp_applications');
        // echo sizeof($dbrow->processing_history);
        //$existing_history = sizeof($dbrow->processing_history);

        if (empty($dbrow)) {
            return false;
        }
        $url = $CI->config->item('track_status_url');
        $postdata = array(
            'edist_ack_no' => $app_id
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
            $error_msg = curl_error($curl);
        }
        curl_close($curl);
        // pre($response );
        if ($response) {
            $response = json_decode($response, true);
            if (isset($response['data'])) {
                // pre($response['applicantName']);
                $history = json_decode($response['data'], true);
                $processing_history = array();
                if (is_array($history) && count($history) > 0) {
                    foreach ($history as $item) {
                        $item_arr = json_decode($item, true);

                        array_push($processing_history, array(
                            'processed_by' => $item_arr['fromRole'],
                            'action_taken' => "Forwarded",
                            'remarks' => $item_arr['remarks'],
                            'processing_time' => new UTCDateTime(strtotime($item_arr['processing_time']) * 1000),
                        ));
                    }
                }
                // echo sizeof($processing_history);
                // pre($processing_history); die;
               // $incoming_history = sizeof($processing_history);

                $data_to_update = array(
                    'service_data.appl_status' => $response['current_status'],
                    'form_data.edist_ack_no' => $response['edist_ack_no'],
                    'processing_history' => $processing_history
                );

                //echo $response['applicantName']; die;
                if (($dbrow->form_data->applicant_name === $response['applicantName']) && ($dbrow->form_data->mobile == $response['mobileNumber']) && ($dbrow->form_data->edistrict_ref_no == extractSubstring($dbrow->form_data->edistrict_ref_no) . $response['edist_ack_no'])) {

                    // pre('Matched');

                    if (empty($dbrow->form_data->certificate) || (property_exists($dbrow->form_data, 'certificate') && !file_exists($dbrow->form_data->certificate)) ||  ($dbrow->service_data->appl_status != "D" && $response['current_status'] == "D")) {
                        //save the cerificate
                        if (isset($response['certificate']) && !empty($response['certificate'])) {
                            $fileName = str_replace('/', '-', $dbrow->service_data->appl_ref_no) . '.pdf';
                            $dirPath = 'storage/docs' . $dbrow->service_data->service_id . '/';
                            if (!is_dir($dirPath)) {
                                mkdir($dirPath, 0777, true);
                                file_put_contents($dirPath . "index.html", '<html><head></head><body>This directory contains files for Senior Citizens only</body></html>');
                            }
                            $filePath = $dirPath . $fileName;

                            $is_file_created = file_put_contents(FCPATH . $filePath, base64_decode($response['certificate']));
                            if ($is_file_created) {
                                $data_to_update['form_data.certificate'] = $filePath;
                            }
                        }
                    }

                    // pre($data_to_update);
                    //if ($existing_history <= $incoming_history) {
                        // echo $existing_history.'<br>'. $incoming_history.'<br>'; 
                        $CI->mongo_db->set($data_to_update);
                        $CI->mongo_db->where(array('form_data.edistrict_ref_no' => $app_id));
                        return $CI->mongo_db->update('sp_applications');
                    //}
                }
            }
        }
    }
} // End of if statement


function extractSubstring($input)
{
    // Find the last occurrence of '/'
    $lastSlashPos = strrpos($input, '/');

    // Extract the substring up to the last slash position
    $output = substr($input, 0, $lastSlashPos + 1);

    return $output;
}
