<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');

class Sms_reminder extends Frontend
{
    private $log_dir = APPPATH . 'logs' . DIRECTORY_SEPARATOR . 'sms_reminder_pending_appl' . DIRECTORY_SEPARATOR;
    private $error_log_file;
    private $info_log_file;

    // SMS templates 
    private $template_pending_sms = 'Dear $dps, total $count application(s) pending in the service $service at your office $office. Please take necessary actions-Team RTPS';
    private $template_pending_sms_exp_3 = 'Dear $dps, total $count application(s) pending in the service $service at your office $office, due for delivery in next 3 days. Please take necessary actions-Team RTPS';

    private $template_test = 'Dear $count Your  ARTPS Application for Next of Kin Certificate service has been submitted successfully on  [date & time $service] .Your Reference No. is $office. Please use thisRef.No. for tracking your application & for future communication';

    // DLT Template IDs
    private const TEMPLATE_ID_PEN = '1007164007770263064';
    private const TEMPLATE_ID_EXP_3 = '1007164007803823816';

    private const TEMPLATE_ID_TEST = '1007160707760375551';


    public function __construct()
    {
        parent::__construct();
        $this->load->model("sms_reminder_model");

        // check if sms_reminder log dir exists
        if (!file_exists($this->log_dir)) {
            mkdir($this->log_dir);
        }
        $this->error_log_file = $this->log_dir . 'error_log_' . date('Y-m-d') . '.log';
        $this->info_log_file = $this->log_dir . 'info_log_' . date('Y-m-d') . '.log';
    }

    public function send_sms_pending($test_mobile_no = NULL)
    {
        $data = $this->sms_reminder_model->get_pending_applications_officewise();
        $this->find_office_dps_and_send_sms('send_sms_pending', $data, self::TEMPLATE_ID_PEN, $test_mobile_no);
    }

    public function send_sms_exp_within_3($test_mobile_no = NULL)
    {
        $data = $this->sms_reminder_model->get_pending_applications_exp_3_officewise();
        $this->find_office_dps_and_send_sms('send_sms_exp_within_3', $data, self::TEMPLATE_ID_EXP_3, $test_mobile_no);
    }

    private function find_office_dps_and_send_sms($tag, $data, $dlt_template_id, $test_mobile_no = NULL)
    {
        // pre($data);

        if (empty($data)) {
            $msg = "[{$tag}]: No pending applications found." . PHP_EOL;
            $this->write_logs($this->error_log_file, $msg);
            return;
        }

        foreach ($data as $office) {

            $loc = $this->sms_reminder_model->get_office($office->_id);

            if (empty($loc)) {
                $msg = "[{$tag}]: {$office->_id} office NOT FOUND." . PHP_EOL;
                $this->write_logs($this->error_log_file, $msg);
            } else {

                // get dps_id from location_id
                $offcial_mappings = $this->sms_reminder_model->get_service_dps_with_official_mappings($loc->_id->{'$id'});
                // $offcial_mappings = $this->sms_reminder_model->get_service_dps_with_official_mappings('61e312abb719f22a420b4f15');

                // pre($offcial_mappings);


                if (!empty($test_mobile_no)) {
                    $offcial_mappings = $this->sms_reminder_model->get_dps_from_offical_mappings('612f4e3cc70aeb1af061a54d');
                }


                if (empty($offcial_mappings)) {
                    $msg = "[{$tag}]: {$office->_id} office FOUND.";
                    $msg .= " offical mappings for DPS NOT FOUND." . PHP_EOL;
                    $this->write_logs($this->error_log_file, $msg);
                } else {
                    // $offcial_mappings = array_map(function ($val) {
                    //     return $val->dps_id;
                    // }, $offcial_mappings);
                    // $user = $this->sms_reminder_model->get_dps_from_dps_id($offcial_mappings);
                    // pre($user);


                    // Send SMS for every service
                    foreach ($office->services as $service) {
                        $service_mapping = false;

                        // Find offcial mapping for the service
                        foreach ($offcial_mappings as $om) {
                            if ($om->service_info->service_id == $service->_id->base_service_id) {

                                $service_mapping = true;

                                // Check dps info
                                if ($om->user_info->designation == 'DPS') {
                                    // Verify mobile no.
                                    if ($this->validate_mobile($om->user_info->mobile ?? '') === 1) {

                                        // Create SMS body
                                        $template_vars = array();

                                        // if (!empty($test_mobile_no)) {
                                        //     $template_vars['$dps'] = 'Test_dps';
                                        //     $template_vars['$count'] = 100;
                                        //     $template_vars['$service'] = 'Test_service';
                                        //     $template_vars['$office'] = 'Test_office';

                                        //     $sms_body = strtr($this->template_test, $template_vars);
                                        //     $dlt_template_id = self::TEMPLATE_ID_TEST;
                                        // } 
                                        // else {
                                        $template_vars['$dps'] = trim($om->user_info->name);
                                        $template_vars['$count'] = $service->count;
                                        $template_vars['$service'] = trim($service->service);
                                        $template_vars['$office'] = trim($office->_id);

                                        switch ($dlt_template_id) {
                                            case self::TEMPLATE_ID_PEN:
                                                $sms_body = strtr($this->template_pending_sms, $template_vars);
                                                break;

                                            case self::TEMPLATE_ID_EXP_3:
                                                $sms_body = strtr($this->template_pending_sms_exp_3, $template_vars);
                                                break;
                                        }
                                        // }

                                        // url encode message_body & add 91 before number
                                        $sms_body = rawurlencode(trim($sms_body));
                                        $number = $test_mobile_no ?? $om->user_info->mobile;

                                        if (preg_match('/^(91)\d{10}$/', $number) !== 1) {
                                            $number = '91' . $number;
                                        }

                                        $curl_res = $this->sendSms($number, $sms_body, $dlt_template_id);
                                        // var_dump($curl_res);
                                        // echo "<br>{$sms_body} : " . $number . "<br>";

                                        if (!empty($curl_res)) {
                                            $msg = "[{$tag}]: {$office->_id} office FOUND.";
                                            $msg .= " offical mappings FOUND.";
                                            $msg .= " service mappings FOUND for {$service->service}({$service->_id->base_service_id}).";
                                            $msg .= " SMS SENT to " . $number . PHP_EOL;

                                            // if (!preg_match('/(SMS SENT)/', $msg)) {
                                            //     $msg .= " SMS SENT to " . $number . PHP_EOL;
                                            // }

                                            $this->write_logs($this->info_log_file, $msg);
                                        }
                                    } else {
                                        $msg = "[{$tag}]: {$office->_id} office FOUND.";
                                        $msg .= " offical mappings FOUND.";
                                        $msg .= " service mappings FOUND for {$service->service}({$service->_id->base_service_id}).";
                                        $msg .= " DPS found. Invalid mobile No." . PHP_EOL;
                                        $this->write_logs($this->error_log_file, $msg);
                                    }
                                } else {
                                    $msg = "[{$tag}]: {$office->_id} office FOUND.";
                                    $msg .= " offical mappings FOUND.";
                                    $msg .= " service mappings FOUND for {$service->service}( {$service->_id->base_service_id}).";
                                    $msg .= " DESIGNATION DPS not found." . PHP_EOL;
                                    $this->write_logs($this->error_log_file, $msg);
                                }

                                break;
                            }
                        }

                        if (!$service_mapping) {
                            $msg = "[{$tag}]: {$office->_id} office FOUND.";
                            $msg .= " offical mappings FOUND.";
                            $msg .= " service mappings NOT FOUND for {$service->service}({$service->_id->base_service_id})" . PHP_EOL;
                            $this->write_logs($this->error_log_file, $msg);
                        }
                    }
                }
            }
        }
    }

    private function write_logs($filename, $msg)
    {
        // echo $msg;
        file_put_contents($filename, $msg, FILE_APPEND);
    }

    private function validate_mobile($mobile)
    {
        return preg_match('/^[6-9]\d{9}$/', $mobile);
    }

    //send SMS

    private function sendSms($number, $message_body, $dlt_template_id)
    {
        // return true;

        $url = "https://smsgw.sms.gov.in/failsafe/MLink?username=rtpsasm.otp&pin=P4%26fO3%23xF4&message=" . $message_body . "&mnumber=" . $number . "&signature=ARTPSA&dlt_entity_id=1001367290000017171&dlt_template_id=" . $dlt_template_id;

        // pre($url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    } //End of sendSms()
}
