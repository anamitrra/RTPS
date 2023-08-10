<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
class Otps extends Frontend {

    public function __construct() {
        parent::__construct();
    }//End of __construct()

    public function send_otp() {
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required|strip_tags|min_length[10]|max_length[10]|regex_match[/^[6-9]\d{9}$/]');
        if ($this->form_validation->run() == FALSE) {
            $status["status"] = false;
            $status["msg"] = validation_errors();
            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode($status));
        }
        $this->load->library('sms');
        $otpMsg = "Dear user, your OTP for EODB portal is {{otp}}. OTP will expire in 10 minutes. On expiry of time please regenerate the OTP";
//        $otpMsg = 'Your Mobile Number verification OTP is {{otp}}';
        $mobileNumber = $this->input->post('mobile_number', true);
//        $this->sms->send_otp($mobileNumber,$mobileNumber,$otpMsg);

        $this->load->config('sms_template');
        $msgTemplate = $this->config->item('otp');
//                pre($msgTemplate);
//                $msg = "Your Otp is for new appeal is {{otp}}";
        $this->sms->send_otp($mobileNumber, $mobileNumber, $msgTemplate);
        $status["status"] = true;
        $status["msg"] = 'OTP successfully sent to your mobile number.';
        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($status));
    }//End of send_otp()

    public function verify_otp() {
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required|strip_tags|min_length[10]|max_length[10]|regex_match[/^[6-9]\d{9}$/]');
        $this->form_validation->set_rules('otp', 'OTP', 'trim|required|strip_tags|min_length[6]|max_length[6]');
        if ($this->form_validation->run() == FALSE) {
            $status["status"] = false;
            $status["msg"] = validation_errors();

            $this->session->userdata('isMobileVerified', $status['status']);
            return $this->output
                            ->set_content_type('application/json')
                            ->set_status_header(200)
                            ->set_output(json_encode($status));
        }
        $this->load->library('sms');
        $otpVerificationStatus = $this->sms->verify_otp($this->input->post('mobile_number', true), $this->input->post('mobile_number', true), $this->input->post('otp', true));

        if ($otpVerificationStatus['status'] == true) {

            $status["status"] = true;
            $status["msg"] = 'Mobile Number verified successfully.';

            // get current session verified numbers

            if ($this->session->has_userdata('verified_mobile_numbers')) {
                $temp = $this->session->userdata('verified_mobile_numbers');
                array_push($temp, $this->input->post('mobile_number', true));
                $this->session->set_userdata('verified_mobile_numbers', $temp);
            } else {
                // add verified numbers to current session
                $verified_mobile_numbers[] = $this->input->post('mobile_number', true);
                $this->session->set_userdata('verified_mobile_numbers', $verified_mobile_numbers);
            }
        } else {
            $status = $otpVerificationStatus;
        }
        $this->session->userdata('isMobileVerified', $status['status']);
        return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($status));
    }//End of verify_otp()

}//End of Otps
