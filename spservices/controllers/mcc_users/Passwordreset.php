<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class Passwordreset extends Frontend
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mcc_users/Office_admin_model');
        $this->load->library('session');
    }

    public function index()
    {
        $this->load->view('includes/mcc_users/admin/header');
        $this->load->view('mcc_users/forgotpassword');
        $this->load->view('includes/mcc_users/admin/footer');
    }
    public function updatepassword()
    {
        if ($this->session->has_userdata('verified_mobile_numbers')) {
            $this->form_validation->set_rules('mobile_number', 'Mobile number', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('confpass', 'Confirm Password', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $status["status"] = false;
                $status["msg"] = validation_errors();
                return $this->output
                    ->set_content_type('application/json')
                    ->set_status_header(200)
                    ->set_output(json_encode($status));
            } else {
                if ((strcmp($this->input->post("password"), $this->input->post("confpass"))) != 0) {
                    $status["status"] = false;
                    $status["msg"] = "Confirm password dosn't match";
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($status));
                } else {
                    $mobile = $this->input->post("mobile_number");
                    $otp = (array)$this->mongo_db->where(array('mobile' => $mobile))->limit(1)->order_by('_id', 'DESC')->get('sms');
                    if ($otp[0]->status == 1) {
                        $data = array(
                            'password' => getHashedPassword($this->input->post("password"))
                        );
                        $this->Office_admin_model->update_where(['mobile' => $mobile], $data);
                        $status["status"] = true;
                        $status["msg"] = 'Password reset successfully.';
                    } else {
                        $status["status"] = false;
                        $status["msg"] = "Something went wrong.";
                    }
                    return $this->output
                        ->set_content_type('application/json')
                        ->set_status_header(200)
                        ->set_output(json_encode($status));
                }
            }
        } else {
            $status["status"] = false;
            $status["msg"] = "Something went wrong.";
        }
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
    }

    public function send_reset_otp()
    {
        $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'trim|required|strip_tags|min_length[10]|max_length[10]|regex_match[/^[6-9]\d{9}$/]');
        if ($this->form_validation->run() == FALSE) {
            $status["status"] = false;
            $status["msg"] = validation_errors();
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($status));
        }
        $mobile = $this->input->post('mobile_number');
        $filter['mobile'] = $mobile;
        $filter['role'] = 'DA';
        $check_admin = $this->Office_admin_model->get_rows($filter);
        if ($check_admin) {
            $this->load->library('sms');
            $mobileNumber = $this->input->post('mobile_number', true);
            $this->load->config('sms_template');
            $msgTemplate = $this->config->item('otp');
            $this->sms->send_otp($mobileNumber, $mobileNumber, $msgTemplate);
            $status["status"] = true;
            $status["msg"] = 'OTP successfully sent to your mobile number.';
        } else {
            $status["status"] = false;
            $status["msg"] = 'No user found.';
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($status));
    } //End of send_otp()

    public function verify_otp()
    {
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
    } //End of verify_otp()
}
