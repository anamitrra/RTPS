<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

// Verify JWT
if (!function_exists('sms_provider')) {
    function sms_provider($type, $data)
    {

        switch ($type) {
            case "submission":
                $message_body = "Dear " . $data['applicant_name'] . " Your ARTPS Application for " . $data['service_name'] . " service has been submitted successfully on [date & time" . $data['submission_date'] . "] .Your Reference No. is " . $data['app_ref_no'] . ". Please use thisRef.No. for tracking your application & for future communication";
                $dlt_template_id = "1007160707760375551";
                sendSms($data['mobile'], $message_body, $dlt_template_id);
                break;
            case "delivery":
                $message_body = "Dear " . $data['applicant_name'] . " Your ARTPS Application for " . $data['service_name'] . " service with Reference No. " . $data['app_ref_no'] . " submitted on [date & time" . $data['submission_date'] . "] has been delivered successfully";
                $dlt_template_id = "1007160707768151335";
                sendSms($data['mobile'], $message_body, $dlt_template_id);
                break;
            case "rejection":
                $message_body = "Dear " . $data['applicant_name'] . " Your ARTPS Application for " . $data['service_name'] . " service with Reference No. " . $data['app_ref_no'] . " submitted on  [date & time" . $data['submission_date'] . "] has been rejected by [office" . $data['submission_office'] . "]";
                $dlt_template_id = "1007160707771234603";
                sendSms($data['mobile'], $message_body, $dlt_template_id);
                break;
            case "query":
                $message_body = "Dear " . $data['applicant_name'] . " , A query has been raised against your application for " . $data['service_name'] . " service with Application Ref No: " . $data['app_ref_no'] . ". Please Log on to https://rtps.assam.gov.in/portal,then go to View Status of Application and then click Track application status for further details.";
                $dlt_template_id = "1007161322342756876";
                sendSms($data['mobile'], $message_body, $dlt_template_id);
                break;
            case "mcc_revert_applicant":
                $message_body = "Dear " . $data['applicant_name'] . " , A query has been raised against your application for " . $data['service_name'] . " service with Application Ref No: " . $data['app_ref_no'] . ". Please Log on to https://rtps.assam.gov.in/spservices/minority-certificate-query/" . $data['obj_id'] . " and respond to the query accordingly. Team ARTPS.";
                $dlt_template_id = "1007166116263249746";
                sendSms($data['mobile'], $message_body, $dlt_template_id);
                break;
            case "emp_slot_update":
                $message_body = "Dear , date has been alloted for physical document verification regarding your application for service Reg of Employment Exchnage on " . $data['slot_data'] . " Please Log on to https://rtps.assam.gov.in/ portal, then go to View Status of Application and then click Track application status for furtherdetails. Your Application Ref No is: " . $data['app_ref_no'] . ".";
                $dlt_template_id = "1007161322684740982";
                sendSms($data['mobile'], $message_body, $dlt_template_id);
                break;

            default:
                break;
        }
    }
}
if (!function_exists('transaction_sms')) {
    function transaction_sms($type, $data)
    {

        switch ($type) {
            case "1":
                $message_body = "Your NOC application with reference no " . $data['app_ref_no'] . " issued by DC office on " . $data['execution_time'] . ". Please visit the concerned SRO for registration -Team ARTPS";
                $dlt_template_id = "1007165190702453880";
                sendSms($data['mobile'], $message_body, $dlt_template_id);
                break;
            case "2":
                $message_body = "Partition completed on " . $data['execution_time'] . " for your application reference no " . $data['app_ref_no'] . " -Team ARTPS";
                $dlt_template_id = "1007165190669339576";
                sendSms($data['mobile'], $message_body, $dlt_template_id);
                break;
            case "3":
                $message_body = "Your application reference no " . $data['app_ref_no'] . " is registered for partition on " . $data['execution_time'] . " -Team ARTPS";
                $dlt_template_id = "1007165190658917830";
                sendSms($data['mobile'], $message_body, $dlt_template_id);
                break;
            case "4":
                $message_body = "Auto mutation completed on " . $data['execution_time'] . " for your application reference no " . $data['app_ref_no'] . " -Team ARTPS";
                $dlt_template_id = "1007165190643529339";
                sendSms($data['mobile'], $message_body, $dlt_template_id);
                break;
            case "5":
                $message_body = "Registration completed for your application reference no " . $data['app_ref_no'] . " on " . $data['execution_time'] . ". Application forwarded for auto mutation -Team ARTPS";
                $dlt_template_id = "1007165190633369072";
                sendSms($data['mobile'], $message_body, $dlt_template_id);
                break;

            case "6":
                $message_body = "Your NOC application reference no " . $data['app_ref_no'] . " forwarded by CO to DC office on " . $data['execution_time'] . " -Team ARTPS";
                $dlt_template_id = "1007165190543813448";
                sendSms($data['mobile'], $message_body, $dlt_template_id);
                break;
            case "7":
                $message_body = "Hearing notice generated for your NOC application reference no " . $data['app_ref_no'] . " on " . $data['execution_time'] . " -Team ARTPS";
                $dlt_template_id = "1007165190533216167";
                sendSms($data['mobile'], $message_body, $dlt_template_id);
                break;
            case "8":
                $message_body = "Your NOC application reference no " . $data['app_ref_no'] . " is rejected by {#var#}{#var#} on " . $data['execution_time'] . " -Team ARTPS";
                $dlt_template_id = "1007165190518264457";
                sendSms($data['mobile'], $message_body, $dlt_template_id);
                break;
            case "10":
                $message_body = "For Appl No " . $data['app_ref_no'] . " by " . $data['applicant'] . " on " . $data['execution_time'] . ", a hearing is scheduled on " . $data['hearing_date'] . " where Pattadars and Copattadars of Dag No. " . $data['dag_no'] . " under " . $data['village'] . " village of " . $data['circle'] . " Circle are requested to be present. Team ARTPS";
                $dlt_template_id = "1007165398984465781";
                sendSms($data['mobile'], $message_body, $dlt_template_id);
                break;

            default:
                break;
        }
    }
}

if (!function_exists('sendSms')) {
    function sendSms($number, $message_body, $dlt_template_id)
    {

        $ch = curl_init();
        // $message_body = str_replace(" ", "%20", $message_body);

        $url = "https://smsgw.sms.gov.in/failsafe/MLink?username=rtpsasm.otp&pin=P4%26fO3%23xF4&message=" . urlencode($message_body) . "&mnumber=" . $number . "&signature=ARTPSA&dlt_entity_id=1001367290000017171&dlt_template_id=" . $dlt_template_id;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $head = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        //var_dump($head);
        return $head;
    } //End of sendSms()
}
